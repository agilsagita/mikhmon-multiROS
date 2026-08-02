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
ini_set('max_execution_time', 0);

if (!isset($_SESSION["mikhmon"])) {
	header("Location:../admin.php?id=login");
} else {
	// time zone
	date_default_timezone_set($_SESSION['timezone']);

	$genprof = $_GET['genprof'];
	if ($genprof != "") {
		$getprofile = $API->comm("/ip/hotspot/user/profile/print", array(
			"?name" => "$genprof",
		));
		$ponlogin = $getprofile[0]['on-login'];
		$getprice = explode(",", $ponlogin)[2];
		if ($getprice == "0") {
			$getprice = "";
		} else {
			$getprice = $getprice;
		}

		$getvalid = explode(",", $ponlogin)[3];

		$getlocku = explode(",", $ponlogin)[6];
		if ($getlocku == "") {
			$getprice = "Disable";
		} else {
			$getlocku = $getlocku;
		}

		if ($currency == in_array($currency, $cekindo['indo'])) {
			$getprice = $currency . " " . number_format((float) $getprice, 0, ",", ".");
		} else {
			$getprice = $currency . " " . number_format((float) $getprice);
		}
		$ValidPrice = "<b>Validity : " . $getvalid . " | Price : " . $getprice . " | Lock User : " . $getlocku . "</b>";
	} else {
	}

	$srvlist = $API->comm("/ip/hotspot/print");

	if (isset($_POST['qty'])) {

		$qty = ($_POST['qty']);
		$server = ($_POST['server']);
		$user = ($_POST['user']);
		$userl = ($_POST['userl']);
		$prefix = ($_POST['prefix']);
		$char = ($_POST['char']);
		$profile = ($_POST['profile']);
		$timelimit = ($_POST['timelimit']);
		$datalimit = ($_POST['datalimit']);
		$adcomment = ($_POST['adcomment']);
		$mbgb = ($_POST['mbgb']);
		if ($timelimit == "") {
			$timelimit = "0";
		} else {
			$timelimit = $timelimit;
		}
		if ($datalimit == "") {
			$datalimit = "0";
		} else {
			$datalimit = $datalimit * $mbgb;
		}
		if ($adcomment == "") {
			$adcomment = "";
		} else {
			$adcomment = $adcomment;
		}
		$getprofile = $API->comm("/ip/hotspot/user/profile/print", array("?name" => "$profile"));
		$ponlogin = $getprofile[0]['on-login'];
		$getvalid = explode(",", $ponlogin)[3];
		$getprice = explode(",", $ponlogin)[2];
		$getsprice = explode(",", $ponlogin)[4];
		$getlock = explode(",", $ponlogin)[6];
		$_SESSION['ubp'] = $profile;
		$commt = $user . "-" . rand(100, 999) . "-" . date("m.d.y") . "-" . $adcomment;
		$gentemp = $commt . "|~" . $profile . "~" . $getvalid . "~" . $getprice . "!" . $getsprice . "~" . $timelimit . "~" . $datalimit . "~" . $getlock;
		$gen = '<?php $genu="' . encrypt($gentemp) . '";?>';
		$temp = './voucher/temp.php';
		$handle = fopen($temp, 'w') or die('Cannot open file:  ' . $temp);
		$data = $gen;
		fwrite($handle, $data);

		$a = array("1" => "", "", 1, 2, 2, 3, 3, 4);

		if ($user == "up") {
			for ($i = 1; $i <= $qty; $i++) {
				if ($char == "lower") {
					$u[$i] = randLC($userl);
				} elseif ($char == "upper") {
					$u[$i] = randUC($userl);
				} elseif ($char == "upplow") {
					$u[$i] = randULC($userl);
				} elseif ($char == "mix") {
					$u[$i] = randNLC($userl);
				} elseif ($char == "mix1") {
					$u[$i] = randNUC($userl);
				} elseif ($char == "mix2") {
					$u[$i] = randNULC($userl);
				}
				if ($userl == 3) {
					$p[$i] = randN(3);
				} elseif ($userl == 4) {
					$p[$i] = randN(4);
				} elseif ($userl == 5) {
					$p[$i] = randN(5);
				} elseif ($userl == 6) {
					$p[$i] = randN(6);
				} elseif ($userl == 7) {
					$p[$i] = randN(7);
				} elseif ($userl == 8) {
					$p[$i] = randN(8);
				}

				$u[$i] = "$prefix$u[$i]";
			}

			// Gunakan fastcgi_finish_request() untuk menutup koneksi nginx
			// sementara PHP tetap berjalan di background (khusus PHP-FPM)
			$gen_id = uniqid('gs_', true);
			$statusFile = './voucher/genstat_' . $gen_id . '.json';
			$redirectUrl = ($qty < 2)
				? './?hotspot-user=' . $u[1] . '&session=' . $session . '&gen_qty=' . $qty . '&gen_id=' . $gen_id
				: './?hotspot-user=generate&session=' . $session . '&gen_qty=' . $qty . '&gen_id=' . $gen_id;
			// Tampilkan halaman loading proper selama 2 detik, lalu redirect
			$loadingHtml = '<!DOCTYPE html><html><head><meta charset="UTF-8">
<meta http-equiv="refresh" content="2;url=' . $redirectUrl . '">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0f172a;display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:sans-serif}
.card{background:#1e293b;border-radius:16px;padding:40px 48px;text-align:center;box-shadow:0 8px 40px rgba(0,0,0,.5);max-width:400px;width:90%}
.spinner{width:56px;height:56px;border:4px solid #334155;border-top-color:#38bdf8;border-radius:50%;animation:spin 0.8s linear infinite;margin:0 auto 24px}
@keyframes spin{to{transform:rotate(360deg)}}
h3{color:#f1f5f9;font-size:20px;margin-bottom:8px}
.sub{color:#94a3b8;font-size:14px;margin-bottom:20px;line-height:1.6}
.qty{display:inline-block;background:#0f172a;color:#38bdf8;font-size:28px;font-weight:700;padding:8px 24px;border-radius:8px;margin-bottom:8px}
.label{color:#64748b;font-size:12px}
.info{margin-top:20px;background:#0f172a;border-radius:8px;padding:10px 14px;color:#22d3ee;font-size:11px;line-height:1.5}
</style></head>
<body><div class="card">
<div class="spinner"></div>
<h3>&#9881; Sedang Memproses Voucher</h3>
<p class="sub">Mengirim data ke MikroTik...<br>Halaman akan otomatis diarahkan dalam 2 detik.</p>
<div class="qty">' . $qty . '</div><br>
<span class="label">voucher sedang dibuat</span>
<div class="info">&#9432; Proses berlanjut di background. Jangan tutup tab ini.</div>
</div></body></html>';
			while (ob_get_level() > 0) {
				ob_end_clean();
			}
			// Tulis status awal sebelum fastcgi_finish_request
			file_put_contents($statusFile, json_encode(['status' => 'processing', 'done' => 0, 'total' => (int) $qty]));
			echo $loadingHtml;
			session_write_close();
			if (function_exists('fastcgi_finish_request')) {
				fastcgi_finish_request(); // PHP-FPM: tutup koneksi nginx, PHP tetap jalan
			} else {
				flush();
			}

			for ($i = 1; $i <= $qty; $i++) {
				$API->comm("/ip/hotspot/user/add", array(
					"server" => "$server",
					"name" => "$u[$i]",
					"password" => "$p[$i]",
					"profile" => "$profile",
					"limit-uptime" => "$timelimit",
					"limit-bytes-total" => "$datalimit",
					"comment" => "$commt",
				));
				// Update status setiap 10 voucher
				if ($i % 10 === 0 || $i === (int) $qty) {
					file_put_contents($statusFile, json_encode(['status' => 'processing', 'done' => $i, 'total' => (int) $qty]));
				}
			}
			// Tandai selesai
			file_put_contents($statusFile, json_encode(['status' => 'done', 'done' => (int) $qty, 'total' => (int) $qty]));
			exit;
		}

		if ($user == "vc") {
			$shuf = ($userl - $a[$userl]);
			for ($i = 1; $i <= $qty; $i++) {
				if ($char == "lower") {
					$u[$i] = randLC($shuf);
				} elseif ($char == "upper") {
					$u[$i] = randUC($shuf);
				} elseif ($char == "upplow") {
					$u[$i] = randULC($shuf);
				}
				if ($userl == 3) {
					$p[$i] = randN(1);
				} elseif ($userl == 4 || $userl == 5) {
					$p[$i] = randN(2);
				} elseif ($userl == 6 || $userl == 7) {
					$p[$i] = randN(3);
				} elseif ($userl == 8) {
					$p[$i] = randN(4);
				}

				$u[$i] = "$prefix$u[$i]$p[$i]";

				if ($char == "num") {
					if ($userl == 3) {
						$p[$i] = randN(3);
					} elseif ($userl == 4) {
						$p[$i] = randN(4);
					} elseif ($userl == 5) {
						$p[$i] = randN(5);
					} elseif ($userl == 6) {
						$p[$i] = randN(6);
					} elseif ($userl == 7) {
						$p[$i] = randN(7);
					} elseif ($userl == 8) {
						$p[$i] = randN(8);
					}

					$u[$i] = "$prefix$p[$i]";
				}
				if ($char == "mix") {
					$p[$i] = randNLC($userl);


					$u[$i] = "$prefix$p[$i]";
				}
				if ($char == "mix1") {
					$p[$i] = randNUC($userl);


					$u[$i] = "$prefix$p[$i]";
				}
				if ($char == "mix2") {
					$p[$i] = randNULC($userl);


					$u[$i] = "$prefix$p[$i]";
				}

			}
			// Gunakan fastcgi_finish_request() untuk menutup koneksi nginx
			// sementara PHP tetap berjalan di background (khusus PHP-FPM)
			$gen_id = uniqid('gs_', true);
			$statusFile = './voucher/genstat_' . $gen_id . '.json';
			$redirectUrl = ($qty < 2)
				? './?hotspot-user=' . $u[1] . '&session=' . $session . '&gen_qty=' . $qty . '&gen_id=' . $gen_id
				: './?hotspot-user=generate&session=' . $session . '&gen_qty=' . $qty . '&gen_id=' . $gen_id;
			// Tampilkan halaman loading proper selama 2 detik, lalu redirect
			$loadingHtml = '<!DOCTYPE html><html><head><meta charset="UTF-8">
<meta http-equiv="refresh" content="2;url=' . $redirectUrl . '">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0f172a;display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:sans-serif}
.card{background:#1e293b;border-radius:16px;padding:40px 48px;text-align:center;box-shadow:0 8px 40px rgba(0,0,0,.5);max-width:400px;width:90%}
.spinner{width:56px;height:56px;border:4px solid #334155;border-top-color:#38bdf8;border-radius:50%;animation:spin 0.8s linear infinite;margin:0 auto 24px}
@keyframes spin{to{transform:rotate(360deg)}}
h3{color:#f1f5f9;font-size:20px;margin-bottom:8px}
.sub{color:#94a3b8;font-size:14px;margin-bottom:20px;line-height:1.6}
.qty{display:inline-block;background:#0f172a;color:#38bdf8;font-size:28px;font-weight:700;padding:8px 24px;border-radius:8px;margin-bottom:8px}
.label{color:#64748b;font-size:12px}
.info{margin-top:20px;background:#0f172a;border-radius:8px;padding:10px 14px;color:#22d3ee;font-size:11px;line-height:1.5}
</style></head>
<body><div class="card">
<div class="spinner"></div>
<h3>&#9881; Sedang Memproses Voucher</h3>
<p class="sub">Mengirim data ke MikroTik...<br>Halaman akan otomatis diarahkan dalam 2 detik.</p>
<div class="qty">' . $qty . '</div><br>
<span class="label">voucher sedang dibuat</span>
<div class="info">&#9432; Proses berlanjut di background. Jangan tutup tab ini.</div>
</div></body></html>';
			while (ob_get_level() > 0) {
				ob_end_clean();
			}
			// Tulis status awal sebelum fastcgi_finish_request
			file_put_contents($statusFile, json_encode(['status' => 'processing', 'done' => 0, 'total' => (int) $qty]));
			echo $loadingHtml;
			session_write_close();

			if (function_exists('fastcgi_finish_request')) {
				fastcgi_finish_request(); // PHP-FPM: tutup koneksi nginx, PHP tetap jalan
			} else {
				flush();
			}

			for ($i = 1; $i <= $qty; $i++) {
				$API->comm("/ip/hotspot/user/add", array(
					"server" => "$server",
					"name" => "$u[$i]",
					"password" => "$u[$i]",
					"profile" => "$profile",
					"limit-uptime" => "$timelimit",
					"limit-bytes-total" => "$datalimit",
					"comment" => "$commt",
				));
				// Update status setiap 10 voucher
				if ($i % 10 === 0 || $i === (int) $qty) {
					file_put_contents($statusFile, json_encode(['status' => 'processing', 'done' => $i, 'total' => (int) $qty]));
				}
			}
			// Tandai selesai
			file_put_contents($statusFile, json_encode(['status' => 'done', 'done' => (int) $qty, 'total' => (int) $qty]));
			exit;
		}


	}

	$getprofile = $API->comm("/ip/hotspot/user/profile/print");
	include_once('./voucher/temp.php');
	$genuser = explode("-", decrypt($genu));
	$genuser1 = explode("~", decrypt($genu));
	$umode = $genuser[0];
	$ucode = $genuser[1];
	$udate = $genuser[2];
	$uprofile = $genuser1[1];
	$uvalid = $genuser1[2];
	$ucommt = $genuser[3];
	if ($uvalid == "") {
		$uvalid = "-";
	} else {
		$uvalid = $uvalid;
	}
	$uprice = explode("!", $genuser1[3])[0];
	if ($uprice == "0") {
		$uprice = "-";
	} else {
		$uprice = $uprice;
	}
	$suprice = explode("!", $genuser1[3])[1];
	if ($suprice == "0") {
		$suprice = "-";
	} else {
		$suprice = $suprice;
	}
	$utlimit = $genuser1[4];
	if ($utlimit == "0") {
		$utlimit = "-";
	} else {
		$utlimit = $utlimit;
	}
	$udlimit = $genuser1[5];
	if ($udlimit == "0") {
		$udlimit = "-";
	} else {
		$udlimit = formatBytes($udlimit, 2);
	}
	$ulock = $genuser1[6];
	//$urlprint = "$umode-$ucode-$udate-$ucommt";
	$urlprint = explode("|", decrypt($genu))[0];
	if ($currency == in_array($currency, $cekindo['indo'])) {
		$uprice = $currency . " " . number_format((float) $uprice, 0, ",", ".");
		$suprice = $currency . " " . number_format((float) $suprice, 0, ",", ".");
	} else {
		$uprice = $currency . " " . number_format((float) $uprice);
		$suprice = $currency . " " . number_format((float) $suprice);

	}

}
?>
<div class="row">

	<?php if (isset($_GET['gen_qty']) && (int) $_GET['gen_qty'] > 1): ?>
		<?php
		$genQty = (int) $_GET['gen_qty'];
		$genId = preg_replace('/[^a-zA-Z0-9_.]/', '', $_GET['gen_id'] ?? '');
		$userListUrl = './?hotspot=users&profile=all&session=' . $session;
		?>
		<div id="gen-banner" style="width:100%; padding:0 0 14px 0;">
			<div
				style="background:linear-gradient(135deg,#0d2137,#1a3a5c); border:1px solid #1e4976; border-radius:14px; padding:18px 20px; box-shadow:0 4px 24px rgba(0,0,0,.35); color:#fff;">

				<!-- Header row -->
				<div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
					<div id="gen-spinner"
						style="flex-shrink:0; width:36px; height:36px; border:3px solid #1e4976; border-top-color:#38bdf8; border-radius:50%; animation:spin-b .8s linear infinite;">
					</div>
					<div style="flex:1;">
						<div style="font-weight:700; font-size:15px;" id="gen-title">&#9881; Proses Background Berjalan
						</div>
						<div style="font-size:12px; color:#7dd3fc;" id="gen-subtitle">
							Membuat <strong style="color:#38bdf8;"><?= $genQty ?></strong> voucher ke MikroTik...
						</div>
					</div>
					<button onclick="document.getElementById('gen-banner').style.display='none'"
						style="background:none;border:none;color:#64748b;font-size:18px;cursor:pointer;padding:0;flex-shrink:0;">&#x2715;</button>
				</div>

				<!-- Progress bar -->
				<div style="background:#0f2033; border-radius:6px; height:10px; overflow:hidden; margin-bottom:8px;">
					<div id="gen-bar"
						style="height:100%; width:0%; background:linear-gradient(90deg,#0ea5e9,#38bdf8); border-radius:6px; transition:width .4s ease;">
					</div>
				</div>
				<div style="display:flex; justify-content:space-between; font-size:11px; color:#64748b;">
					<span id="gen-count">0 / <?= $genQty ?> voucher</span>
					<span id="gen-pct">0%</span>
				</div>
			</div>
		</div>
		<style>
			@keyframes spin-b {
				to {
					transform: rotate(360deg)
				}
			}
		</style>
		<script>
			(function () {
				var genId = '<?= addslashes($genId) ?>';
				var total = <?= $genQty ?>;
				var doneUrl = '<?= addslashes($userListUrl) ?>';
				if (!genId) return;

				function poll() {
					fetch('./process/genstatus.php?gen_id=' + encodeURIComponent(genId) + '&session=<?= $session ?>')
						.then(function (r) { return r.json(); })
						.then(function (data) {
							var done = data.done || 0;
							var pct = total > 0 ? Math.round((done / total) * 100) : 0;

							document.getElementById('gen-bar').style.width = pct + '%';
							document.getElementById('gen-count').textContent = done + ' / ' + total + ' voucher';
							document.getElementById('gen-pct').textContent = pct + '%';
							document.getElementById('gen-subtitle').innerHTML =
								'Membuat <strong style="color:#38bdf8;">' + done + '</strong> dari <strong style="color:#38bdf8;">' + total + '</strong> voucher...';

							if (data.status === 'done') {
								// Selesai — ubah tampilan jadi success lalu redirect
								document.getElementById('gen-spinner').style.background = '#22c55e';
								document.getElementById('gen-spinner').style.border = 'none';
								document.getElementById('gen-spinner').style.animation = 'none';
								document.getElementById('gen-spinner').innerHTML = '&#10003;';
								document.getElementById('gen-spinner').style.display = 'flex';
								document.getElementById('gen-spinner').style.alignItems = 'center';
								document.getElementById('gen-spinner').style.justifyContent = 'center';
								document.getElementById('gen-spinner').style.fontSize = '18px';
								document.getElementById('gen-spinner').style.fontWeight = '700';
								document.getElementById('gen-spinner').style.borderRadius = '50%';
								document.getElementById('gen-title').textContent = '✅ Selesai! Voucher berhasil dibuat';
								document.getElementById('gen-subtitle').innerHTML =
									'Semua <strong style="color:#4ade80;">' + total + '</strong> voucher telah ditambahkan ke MikroTik.';
								document.getElementById('gen-bar').style.width = '100%';
								document.getElementById('gen-bar').style.background = '#22c55e';
								document.getElementById('gen-count').textContent = total + ' / ' + total + ' voucher';
								document.getElementById('gen-pct').textContent = '100%';
								// Auto-redirect ke daftar pengguna setelah 2 detik
								setTimeout(function () {
									window.location.href = doneUrl;
								}, 2000);
							} else {
								// Masih processing — poll lagi
								setTimeout(poll, 3000);
							}
						})
						.catch(function () {
							// Error jaringan — coba lagi setelah 5 detik
							setTimeout(poll, 5000);
						});
				}

				// Mulai polling setelah 3 detik (beri waktu file status terbuat)
				setTimeout(poll, 3000);
			})();
		</script>
	<?php endif; ?>



	<div class="col-8">
		<div class="card box-bordered">
			<div class="card-header">
				<h3><i class="fa fa-user-plus"></i> <?= $_generate_user ?> <small id="loader"
						style="display: none;"><i><i class='fa fa-circle-o-notch fa-spin'></i> <?= $_processing ?>
						</i></small></h3>
			</div>
			<div class="card-body">
				<form autocomplete="off" method="post" action="">
					<div>
						<?php if ($_SESSION['ubp'] != "") {
							echo "    <a class='btn bg-warning' href='./?hotspot=users&profile=" . $_SESSION['ubp'] . "&session=" . $session . "'> <i class='fa fa-close'></i> " . $_close . "</a>";
						} elseif ($_SESSION['vcr'] = "active") {
							echo "    <a class='btn bg-warning' href='./?hotspot=users-by-profile&session=" . $session . "'> <i class='fa fa-close'></i> " . $_close . "</a>";
						} else {
							echo "    <a class='btn bg-warning' href='./?hotspot=users&profile=all&session=" . $session . "'> <i class='fa fa-close'></i> " . $_close . "</a>";
						}

						?>
						<a class="btn bg-pink" title="Open User List by Profile 
<?php if ($_SESSION['ubp'] == "") {
	echo "all";
} else {
	echo $uprofile;
} ?>" href="./?hotspot=users&profile=
<?php if ($_SESSION['ubp'] == "") {
	echo "all";
} else {
	echo $uprofile;
} ?>&session=<?= $session; ?>"> <i class="fa fa-users"></i> <?= $_user_list ?></a>
						<button type="submit" name="save" onclick="loader()" class="btn bg-primary"
							title="Generate User"> <i class="fa fa-save"></i> <?= $_generate ?></button>
						<a class="btn bg-secondary" title="Print Default"
							href="./voucher/print.php?id=<?= $urlprint; ?>&qr=no&session=<?= $session; ?>"
							target="_blank"> <i class="fa fa-print"></i> <?= $_print ?></a>
						<a class="btn bg-danger" title="Print QR"
							href="./voucher/print.php?id=<?= $urlprint; ?>&qr=yes&session=<?= $session; ?>"
							target="_blank"> <i class="fa fa-qrcode"></i> <?= $_print_qr ?></a>
						<a class="btn bg-info" title="Print Small"
							href="./voucher/print.php?id=<?= $urlprint; ?>&small=yes&session=<?= $session; ?>"
							target="_blank"> <i class="fa fa-print"></i> <?= $_print_small ?></a>
					</div>
					<table class="table">
						<tr>
							<td class="align-middle"><?= $_qty ?></td>
							<td>
								<div><input class="form-control " type="number" name="qty" min="1" max="500" value="1"
										required="1"></div>
							</td>
						</tr>
						<tr>
							<td class="align-middle">Server</td>
							<td>
								<select class="form-control " name="server" required="1">
									<option>all</option>
									<?php $TotalReg = count($srvlist);
									for ($i = 0; $i < $TotalReg; $i++) {
										echo "<option>" . $srvlist[$i]['name'] . "</option>";
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_user_mode ?></td>
							<td>
								<select class="form-control " onchange="defUserl();" id="user" name="user" required="1">
									<option value="up"><?= $_user_pass ?></option>
									<option value="vc"><?= $_user_user ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_user_length ?></td>
							<td>
								<select class="form-control " id="userl" name="userl" required="1">
									<option>4</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
									<option>7</option>
									<option>8</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_prefix ?></td>
							<td><input class="form-control " type="text" size="6" maxlength="6" autocomplete="off"
									name="prefix" value=""></td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_character ?></td>
							<td>
								<select class="form-control " name="char" required="1">
									<option id="lower" style="display:block;" value="lower"><?= $_random ?> abcd
									</option>
									<option id="upper" style="display:block;" value="upper"><?= $_random ?> ABCD
									</option>
									<option id="upplow" style="display:block;" value="upplow"><?= $_random ?> aBcD
									</option>
									<option id="lower1" style="display:none;" value="lower"><?= $_random ?> abcd2345
									</option>
									<option id="upper1" style="display:none;" value="upper"><?= $_random ?> ABCD2345
									</option>
									<option id="upplow1" style="display:none;" value="upplow"><?= $_random ?> aBcD2345
									</option>
									<option id="mix" style="display:block;" value="mix"><?= $_random ?> 5ab2c34d
									</option>
									<option id="mix1" style="display:block;" value="mix1"><?= $_random ?> 5AB2C34D
									</option>
									<option id="mix2" style="display:block;" value="mix2"><?= $_random ?> 5aB2c34D
									</option>
									<option id="num" style="display:none;" value="num"><?= $_random ?> 1234</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_profile ?></td>
							<td>
								<select class="form-control " onchange="GetVP();" id="uprof" name="profile"
									required="1">
									<?php if ($genprof != "") {
										echo "<option>" . $genprof . "</option>";
									} else {
									}
									$TotalReg = count($getprofile);
									for ($i = 0; $i < $TotalReg; $i++) {
										echo "<option>" . $getprofile[$i]['name'] . "</option>";
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_time_limit ?></td>
							<td><input class="form-control " type="text" size="4" autocomplete="off" name="timelimit"
									value=""></td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_data_limit ?></td>
							<td>
								<div class="input-group">
									<div class="input-group-10 col-box-9">
										<input class="group-item group-item-l" type="number" min="0" max="9999"
											name="datalimit" value="<?= $udatalimit; ?>">
									</div>
									<div class="input-group-2 col-box-3">
										<select style="padding:4.2px;" class="group-item group-item-r" name="mbgb"
											required="1">
											<option value=1048576>MB</option>
											<option value=1073741824>GB</option>
										</select>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td class="align-middle"><?= $_comment ?></td>
							<td><input class="form-control " type="text" title="No special characters" id="comment"
									autocomplete="off" name="adcomment" value=""></td>
						</tr>
						<tr>
							<td colspan="4" class="align-middle w-12" id="GetValidPrice">
								<?php if ($genprof != "") {
									echo $ValidPrice;
								} ?>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>

	<div class="col-4">
		<div class="card">
			<div class="card-header">
				<h3><i class="fa fa-ticket"></i> <?= $_last_generate ?></h3>
			</div>
			<div class="card-body">
				<table class="table table-bordered">
					<tr>
						<td><?= $_generate_code ?></td>
						<td><?= $ucode ?></td>
					</tr>
					<tr>
						<td><?= $_date ?></td>
						<td><?= $udate ?></td>
					</tr>
					<tr>
						<td><?= $_profile ?></td>
						<td><?= $uprofile ?></td>
					</tr>
					<tr>
						<td><?= $_validity ?></td>
						<td><?= $uvalid ?></td>
					<tr>
						<td><?= $_time_limit ?></td>
						<td><?= $utlimit ?></td>
					</tr>
					<tr>
						<td><?= $_data_limit ?></td>
						<td><?= $udlimit ?></td>
					</tr>
					<tr>
						<td><?= $_price ?></td>
						<td><?= $uprice ?></td>
					</tr>
					<tr>
						<td><?= $_selling_price ?></td>
						<td><?= $suprice ?></td>
					</tr>
					<tr>
						<td><?= $_lock_user ?></td>
						<td><?= $ulock ?></td>
					</tr>
					<tr>
						<td colspan="2">
							<p style="padding:0px 5px;">
								<?= $_format_time_limit ?>
							</p>
							<p style="padding:0px 5px;">
								<?= $_details_add_user ?>
							</p>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<script>
		// get valid $ price
		function GetVP() {
			var prof = document.getElementById('uprof').value;
			$("#GetValidPrice").load("./process/getvalidprice.php?name=" + prof + "&session=<?= $session; ?> #getdata");
		}

		// Override loader() — tampilkan fullscreen overlay saat generate
		function loader() {
			var qty = document.querySelector('input[name="qty"]').value;
			document.getElementById('loader').style = 'display:inline;';
			document.getElementById('gen-overlay').style.display = 'flex';
			document.getElementById('gen-overlay-qty').textContent = qty;
		}

		// Tampilkan notifikasi background jika gen_qty ada di URL
		(function () {
			var params = new URLSearchParams(window.location.search);
			var genQty = params.get('gen_qty');
			if (genQty && parseInt(genQty) > 1) {
				var bar = document.getElementById('gen-notify-bar');
				if (bar) {
					document.getElementById('gen-notify-qty').textContent = genQty;
					bar.style.display = 'flex';
					// Auto dismiss setelah 60 detik
					setTimeout(function () { bar.style.display = 'none'; }, 60000);
				}
			}
		})();
	</script>

	<!-- Fullscreen Loading Overlay -->
	<div id="gen-overlay"
		style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.65); z-index:99999; flex-direction:column; align-items:center; justify-content:center;">
		<div
			style="background:#1e2a3a; border-radius:16px; padding:40px 50px; text-align:center; box-shadow:0 8px 40px rgba(0,0,0,0.5); max-width:380px;">
			<div style="margin-bottom:20px;">
				<i class="fa fa-circle-o-notch fa-spin" style="font-size:48px; color:#4fc3f7;"></i>
			</div>
			<h4 style="color:#fff; margin:0 0 8px 0; font-size:18px;">Sedang Memproses</h4>
			<p style="color:#90caf9; margin:0 0 16px 0; font-size:14px;">
				Membuat <strong id="gen-overlay-qty" style="color:#fff;">...</strong> voucher ke MikroTik
			</p>
			<p style="color:#64b5f6; font-size:12px; margin:0; line-height:1.6;">
				Halaman akan otomatis diarahkan.<br>
				Proses berlanjut di background, jangan tutup halaman ini.
			</p>
			<div style="margin-top:20px; background:#0d1b2a; border-radius:8px; padding:10px 16px;">
				<small style="color:#4dd0e1; font-size:11px;">
					<i class="fa fa-info-circle"></i>
					Voucher tetap terbuat meski halaman redirect
				</small>
			</div>
		</div>
	</div>

	<!-- Notification Banner (muncul di halaman setelah redirect) -->
	<div id="gen-notify-bar"
		style="display:none; position:fixed; bottom:24px; right:24px; z-index:9999; align-items:center; background:linear-gradient(135deg,#1565c0,#0d47a1); color:#fff; border-radius:12px; padding:16px 20px; box-shadow:0 4px 24px rgba(0,0,0,0.4); max-width:340px; gap:12px;">
		<i class="fa fa-cog fa-spin" style="font-size:22px; color:#4fc3f7; flex-shrink:0;"></i>
		<div style="flex:1;">
			<div style="font-weight:600; font-size:14px; margin-bottom:4px;">Proses Background Berjalan</div>
			<div style="font-size:12px; color:#bbdefb;">
				Membuat <strong id="gen-notify-qty" style="color:#fff;">...</strong> voucher ke MikroTik.<br>
				Tunggu beberapa menit lalu refresh daftar pengguna.
			</div>
		</div>
		<button onclick="document.getElementById('gen-notify-bar').style.display='none'"
			style="background:none; border:none; color:#90caf9; cursor:pointer; font-size:18px; padding:0; flex-shrink:0;">✕</button>
	</div>
</div>