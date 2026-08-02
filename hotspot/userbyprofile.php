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

// hide all error
error_reporting(0);
if (!isset($_SESSION["mikhmon"])) {
  header("Location:../admin.php?id=login");
} else {
// array color
  $color = array('1' => 'bg-blue', 'bg-indigo', 'bg-purple', 'bg-pink', 'bg-red', 'bg-yellow', 'bg-green', 'bg-teal', 'bg-cyan', 'bg-grey', 'bg-light-blue');

  ?>
<?php if (isset($_GET['del_qty']) && (int)$_GET['del_qty'] > 0): ?>
<?php
    $delQty      = (int)$_GET['del_qty'];
    $delGenId    = preg_replace('/[^a-zA-Z0-9_.]/', '', $_GET['gen_id'] ?? '');
    $afterDoneUrl = './?hotspot=users&profile=' . ($profile ?? 'all') . '&session=' . $session;
?>
<div id="del-banner" style="width:100%; padding:0 0 14px 0;">
    <div style="background:linear-gradient(135deg,#2d0f0f,#4a1212); border:1px solid #7f1d1d; border-radius:14px; padding:18px 20px; box-shadow:0 4px 24px rgba(0,0,0,.35); color:#fff;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <div id="del-spinner" style="flex-shrink:0; width:36px; height:36px; border:3px solid #7f1d1d; border-top-color:#f87171; border-radius:50%; animation:spin-del .8s linear infinite;"></div>
            <div style="flex:1;">
                <div style="font-weight:700; font-size:15px;" id="del-title">&#128465; Proses Hapus Background Berjalan</div>
                <div style="font-size:12px; color:#fca5a5;" id="del-subtitle">
                    Menghapus <strong style="color:#f87171;"><?= $delQty ?></strong> user dari MikroTik...
                </div>
            </div>
            <button onclick="document.getElementById('del-banner').style.display='none'"
                style="background:none;border:none;color:#64748b;font-size:18px;cursor:pointer;padding:0;flex-shrink:0;">&#x2715;</button>
        </div>
        <div style="background:#1a0a0a; border-radius:6px; height:10px; overflow:hidden; margin-bottom:8px;">
            <div id="del-bar" style="height:100%; width:0%; background:linear-gradient(90deg,#dc2626,#f87171); border-radius:6px; transition:width .4s ease;"></div>
        </div>
        <div style="display:flex; justify-content:space-between; font-size:11px; color:#64748b;">
            <span id="del-count">0 / <?= $delQty ?> user</span>
            <span id="del-pct">0%</span>
        </div>
    </div>
</div>
<style>@keyframes spin-del{to{transform:rotate(360deg)}}</style>
<script>
(function () {
    var genId   = '<?= addslashes($delGenId) ?>';
    var total   = <?= $delQty ?>;
    var doneUrl = '<?= addslashes($afterDoneUrl) ?>';
    if (!genId) return;

    function poll() {
        fetch('./process/genstatus.php?gen_id=' + encodeURIComponent(genId) + '&session=<?= $session ?>')
            .then(function(r){ return r.json(); })
            .then(function(data) {
                var done = data.done || 0;
                var pct  = total > 0 ? Math.round((done / total) * 100) : 0;

                document.getElementById('del-bar').style.width   = pct + '%';
                document.getElementById('del-count').textContent = done + ' / ' + total + ' user';
                document.getElementById('del-pct').textContent   = pct + '%';
                document.getElementById('del-subtitle').innerHTML =
                    'Menghapus <strong style="color:#f87171;">' + done + '</strong> dari <strong style="color:#f87171;">' + total + '</strong> user...';

                if (data.status === 'done') {
                    var sp = document.getElementById('del-spinner');
                    sp.style.background = '#22c55e'; sp.style.border = 'none';
                    sp.style.animation  = 'none'; sp.innerHTML = '&#10003;';
                    sp.style.display = 'flex'; sp.style.alignItems = 'center';
                    sp.style.justifyContent = 'center'; sp.style.fontSize = '18px';
                    sp.style.fontWeight = '700'; sp.style.borderRadius = '50%';
                    document.getElementById('del-title').textContent = '✅ Selesai! User berhasil dihapus';
                    document.getElementById('del-subtitle').innerHTML =
                        'Semua <strong style="color:#4ade80;">' + total + '</strong> user telah dihapus dari MikroTik.';
                    document.getElementById('del-bar').style.width      = '100%';
                    document.getElementById('del-bar').style.background = '#22c55e';
                    document.getElementById('del-count').textContent    = total + ' / ' + total + ' user';
                    document.getElementById('del-pct').textContent      = '100%';
                    setTimeout(function() { window.location.href = doneUrl; }, 2000);
                } else {
                    setTimeout(poll, 3000);
                }
            })
            .catch(function() { setTimeout(poll, 5000); });
    }
    setTimeout(poll, 3000);
})();
</script>
<?php endif; ?>
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header">
	<h3><i class=" fa fa-users"></i> <?= $_vouchers ?> &nbsp;&nbsp; | &nbsp;&nbsp;<i onclick="location.reload();" class="fa fa-refresh pointer" title="Reload data"></i></h3>
</div>
<div class="card-body">
<div class="overflow" style="max-height: 80vh">	
<div class="row">	
      <div class="col-4">
        <div class="box bmh-75 box-bordered <?= $color[rand(1, 11)]; ?>">
          <div class="box-group">
            <div class="box-group-icon">
              <a title='Open User by profile <?= $pname; ?>'  href='./?hotspot=users&profile=all&session=<?= $session; ?>'>
              <i class="fa fa-ticket"></i></a>
            </div>
              <div class="box-group-area">
                <h3 >Profile : all<br>
                <?php $countuser = $API->comm("/ip/hotspot/user/print", array("count-only" => ""));
                if ($countuser < 2) {
                  echo $countuser . " Item";
                } elseif ($countuser > 1) {
                  echo $countuser . " Items";
                }
                ?></h3>

              <div class="box-actions">
                <a title="Open User by profile all" href="./?hotspot=users&profile=all&session=<?= $session; ?>"><i class="fa fa-external-link"></i> <?= $_open ?></a>
                <a title="Generate User by profile <?= $pname; ?>" href="./?hotspot-user=generate&session=<?= $session; ?>"><i class="fa fa-users"></i> <?= $_generate ?></a>
              </div>
              </div>
            </div>
            
          </div>
        </div>
<?php
// get user profile
$getprofile = $API->comm("/ip/hotspot/user/profile/print");
$TotalReg = count($getprofile);
for ($i = 0; $i < $TotalReg; $i++) {
  $profiledetalis = $getprofile[$i];
  $pname = $profiledetalis['name'];
  ?>
	     <div class="col-4">
        <div class="box bmh-75 box-bordered <?= $color[rand(1, 11)]; ?>">
          <div class="box-group">
            <div class="box-group-icon">
              <a title='Open User by profile <?= $pname; ?>'  href='./?hotspot=users&profile=<?= $pname; ?>&session=<?= $session; ?>'>
            	<i class="fa fa-ticket"></i></a>
            </div>
              <div class="box-group-area">
                <h3 >Profile : <?= $pname; ?><br>
                <?php	$countuser = $API->comm("/ip/hotspot/user/print", array("count-only" => "", "?profile" => "$pname", ));
                if ($countuser < 2) {
                  echo $countuser . " Item";
                } elseif ($countuser > 1) {
                  echo $countuser . " Items";
                }
                ?></h3>

              <div class="box-actions">
                <a title="Open User by profile <?= $pname; ?>" href="./?hotspot=users&profile=<?= $pname; ?>&session=<?= $session; ?>"><i class="fa fa-external-link"></i> <?= $_open ?></a>
                <a title="Generate User by profile <?= $pname; ?>" href="./?hotspot-user=generate&genprof=<?= $pname; ?>&session=<?= $session; ?>"><i class="fa fa-users"></i> <?= $_generate ?></a>
              </div>
              </div>
            </div>
            
          </div>
        </div>
        <?php 
      }
    } ?>
      </div>
    </div>
</div>
</div>
</div>
</div>