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
set_time_limit(0);
ignore_user_abort(true);

if ($removehotspotusers != "") {
	$uids  = explode("~", $removehotspotusers);
	$nuids = count($uids);

	// Tentukan redirect URL
	if ($_SESSION['ubp'] != "") {
		$redirectUrl = './?hotspot=users&profile=' . $_SESSION['ubp'] . '&session=' . $session;
	} elseif ($_SESSION['ubc'] != "") {
		$redirectUrl = './?hotspot=users&comment=' . $_SESSION['ubc'] . '&session=' . $session;
	} else {
		$redirectUrl = './?hotspot=users&profile=all&session=' . $session;
	}

	if ($nuids > 10) {
		// Mode background: gunakan fastcgi_finish_request untuk proses besar
		$gen_id     = uniqid('del_', true);
		$statusFile = './voucher/genstat_' . $gen_id . '.json';
		$redirectUrl .= '&del_qty=' . $nuids . '&gen_id=' . $gen_id;

		$loadingHtml = '<!DOCTYPE html><html><head><meta charset="UTF-8">
<meta http-equiv="refresh" content="2;url=' . $redirectUrl . '">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0f172a;display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:sans-serif}
.card{background:#1e293b;border-radius:16px;padding:40px 48px;text-align:center;box-shadow:0 8px 40px rgba(0,0,0,.5);max-width:400px;width:90%}
.spinner{width:56px;height:56px;border:4px solid #334155;border-top-color:#f87171;border-radius:50%;animation:spin 0.8s linear infinite;margin:0 auto 24px}
@keyframes spin{to{transform:rotate(360deg)}}
h3{color:#f1f5f9;font-size:20px;margin-bottom:8px}
.sub{color:#94a3b8;font-size:14px;margin-bottom:20px;line-height:1.6}
.qty{display:inline-block;background:#0f172a;color:#f87171;font-size:28px;font-weight:700;padding:8px 24px;border-radius:8px;margin-bottom:8px}
.label{color:#64748b;font-size:12px}
.info{margin-top:20px;background:#0f172a;border-radius:8px;padding:10px 14px;color:#f87171;font-size:11px;line-height:1.5}
</style></head>
<body><div class="card">
<div class="spinner"></div>
<h3>&#128465; Sedang Menghapus Voucher</h3>
<p class="sub">Menghapus data dari MikroTik...<br>Halaman akan otomatis diarahkan dalam 2 detik.</p>
<div class="qty">' . $nuids . '</div><br>
<span class="label">voucher sedang dihapus</span>
<div class="info">&#9432; Proses berlanjut di background. Jangan tutup tab ini.</div>
</div></body></html>';

		while (ob_get_level() > 0) {
			ob_end_clean();
		}
		file_put_contents($statusFile, json_encode(['status' => 'processing', 'done' => 0, 'total' => $nuids, 'type' => 'delete']));
		echo $loadingHtml;
		session_write_close();
		if (function_exists('fastcgi_finish_request')) {
			fastcgi_finish_request();
		} else {
			flush();
		}

		// Proses hapus di background
		for ($i = 0; $i < $nuids; $i++) {
			$getuname = $API->comm("/ip/hotspot/user/print", array(
				"?.id" => "$uids[$i]",
			));
			$name = $getuname[0]['name'];

			$getscr = $API->comm("/system/script/print", array(
				"?name" => "$name",
			));
			$scr = $getscr[0]['.id'];

			$getsch = $API->comm("/system/scheduler/print", array(
				"?name" => "$name",
			));
			$sch = $getsch[0]['.id'];

			$API->comm("/system/script/remove", array(".id" => "$scr"));
			$API->comm("/system/scheduler/remove", array(".id" => "$sch"));
			$API->comm("/ip/hotspot/user/remove", array(".id" => "$uids[$i]"));

			// Update status setiap 5 user
			if (($i + 1) % 5 === 0 || ($i + 1) === $nuids) {
				file_put_contents($statusFile, json_encode(['status' => 'processing', 'done' => $i + 1, 'total' => $nuids, 'type' => 'delete']));
			}
		}
		file_put_contents($statusFile, json_encode(['status' => 'done', 'done' => $nuids, 'total' => $nuids, 'type' => 'delete']));
		exit;

	} else {
		// Jumlah kecil (≤10): proses langsung seperti semula
		for ($i = 0; $i < $nuids; $i++) {
			$getuname = $API->comm("/ip/hotspot/user/print", array(
				"?.id" => "$uids[$i]",
			));
			$name = $getuname[0]['name'];

			$getscr = $API->comm("/system/script/print", array(
				"?name" => "$name",
			));
			$scr = $getscr[0]['.id'];

			$getsch = $API->comm("/system/scheduler/print", array(
				"?name" => "$name",
			));
			$sch = $getsch[0]['.id'];

			$API->comm("/system/script/remove", array(".id" => "$scr"));
			$API->comm("/system/scheduler/remove", array(".id" => "$sch"));
			$API->comm("/ip/hotspot/user/remove", array(".id" => "$uids[$i]"));
		}

		if ($_SESSION['ubp'] != "") {
			echo "<script>window.location='./?hotspot=users&profile=" . $_SESSION['ubp'] . "&session=" . $session . "'</script>";
		} elseif ($_SESSION['ubc'] != "") {
			echo "<script>window.location='./?hotspot=users&comment=" . $_SESSION['ubc'] . "&session=" . $session . "'</script>";
		} else {
			echo "<script>window.location='./?hotspot=users&profile=all&session=" . $session . "'</script>";
		}
	}

} else {
	// Hapus single user — langsung seperti semula
	$getuname = $API->comm("/ip/hotspot/user/print", array(
		"?.id" => "$removehotspotuser",
	));
	$name = $getuname[0]['name'];

	$getscr = $API->comm("/system/script/print", array(
		"?name" => "$name",
	));
	$scr = $getscr[0]['.id'];

	$getsch = $API->comm("/system/scheduler/print", array(
		"?name" => "$name",
	));
	$sch = $getsch[0]['.id'];

	$API->comm("/system/script/remove", array(".id" => "$scr"));
	$API->comm("/system/scheduler/remove", array(".id" => "$sch"));
	$API->comm("/ip/hotspot/user/remove", array(".id" => "$removehotspotuser"));

	if ($_SESSION['ubp'] != "") {
		echo "<script>window.location='./?hotspot=users&profile=" . $_SESSION['ubp'] . "&session=" . $session . "'</script>";
	} elseif ($_SESSION['ubc'] != "") {
		echo "<script>window.location='./?hotspot=users&comment=" . $_SESSION['ubc'] . "&session=" . $session . "'</script>";
	} else {
		echo "<script>window.location='./?hotspot=users&profile=all&session=" . $session . "'</script>";
	}
}
?>