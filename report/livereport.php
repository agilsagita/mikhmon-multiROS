<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
// hide all error
error_reporting(0);
if (!isset($_SESSION["mikhmon"])) {
  header("Location:../admin.php?id=login");
} else {
// load session MikroTik
  $session = $_GET['session'];
// set  timezone
date_default_timezone_set($_SESSION['timezone']);

// lang
include('../include/lang.php');
include('../lang/'.$langid.'.php');


// load config
  include('../include/config.php');
  include('../include/readcfg.php');

// routeros api
  include_once('../lib/routeros_api.class.php');
  include_once('../lib/formatbytesbites.php');
  $API = new RouterosAPI();
  $API->debug = false;
  $API->connect($iphost, $userhost, decrypt($passwdhost));

  if ($livereport == "disable") {
    $logh = "457px";
    $lreport = "style='display:none;'";
  } else {
    $logh = "350px";
    $lreport = "style='display:block;'";
// get selling report
    $thisD = date("d");
    $thisM = strtolower(date("M"));
    $thisY = date("Y");

    if (strlen($thisD) == 1) {
      $thisD = "0" . $thisD;
    } else {
      $thisD = $thisD;
    }

    $idhr = $thisM . "/" . $thisD . "/" . $thisY;
    $idbl = $thisM . $thisY;
    $idbl_num = date("m") . $thisY;  // Format ROS v7: "072026"
    
    $monthMap = array(
        'jan' => '01', 'feb' => '02', 'mar' => '03', 'apr' => '04', 
        'may' => '05', 'jun' => '06', 'jul' => '07', 'aug' => '08', 
        'sep' => '09', 'oct' => '10', 'nov' => '11', 'dec' => '12'
    );
    $idhr_num = $thisY . "-" . $monthMap[$thisM] . "-" . $thisD;

    $_SESSION[$session.'idhr'] = $idhr;

    /* $getSRHr = $API->comm("/system/script/print", array(
      "?source" => "$idhr",
    ));
    $TotalRHr = count($getSRHr);
    $_SESSION[$session.'totalHr'] = $TotalRHr;*/
    $getSRBl_raw = $API->comm("/system/script/print", array(
      "?comment" => "mikhmon",
    ));

    // Filter by bulan ini di PHP (lebih reliable di semua versi ROS)
    // karena filter ?owner via API tidak konsisten di beberapa versi ROS v6
    // Cek dua format: ROS v6 = "jul2026", ROS v7 = "072026"
    $getSRBl = array_values(array_filter($getSRBl_raw, function($row) use ($idbl, $idbl_num) {
      $owner = trim($row['owner']);
      return $owner === trim($idbl) || $owner === trim($idbl_num);
    }));

    $TotalRBl = count($getSRBl);
    $_SESSION[$session.'totalBl'] = $TotalRBl;

    foreach($getSRBl as $row){
      $script_date = trim(explode("-|-", $row['name'])[0]);
      if($script_date == trim($idhr) || $script_date == trim($idhr_num)){
         $tHr += explode("-|-", $row['name'])[3];
         $TotalRHr += count((array)$row['source']); /*Modif line add (array) by github https://github.com/MasKawer*/
 
       }
       $tBl += explode("-|-", $row['name'])[3];

      if($TotalRHr == ""){
        $TotalRHr = "0";
        $_SESSION[$session.'totalHr'] = "0";
      }else{
        $_SESSION[$session.'totalHr'] = $TotalRHr;
      }
      
    }
  }
}
?>

            <div id="r_4" class="row">
              <div <?= $lreport; ?> class="box bmh-75 box-bordered">
                <div class="box-group">
                  <div class="box-group-icon"><i class="fa fa-money"></i></div>
                    <div class="box-group-area">
                      <span >
                        <div id="reloadLreport">
                        <?php 
                          if ($currency == in_array($currency, $cekindo['indo'])) {
                            $dincome = number_format((float)$tHr, 0, ",", ".");
                            $mincome = number_format((float)$tBl, 0, ",", ".");
                            $_SESSION[$session.'dincome'] = $dincome;
                            $_SESSION[$session.'mincome'] = $mincome;
                          }else{
                            $dincome = number_format((float)$tHr, 2);
                            $mincome = number_format((float)$tBl, 2);
                            $_SESSION[$session.'dincome'] = $dincome;
                            $_SESSION[$session.'mincome'] = $mincome;
                          }
                            echo $_income."<br/>" . "
                          ".$_today." " . $TotalRHr . "vcr : " . $currency . " " . $dincome . "<br/>
                          ".$_this_month." " . $TotalRBl . "vcr : " . $currency . " " . $mincome;
                          ?>
                        </div>
                    </span>
                </div>
              </div>
            </div>
            </div>
            