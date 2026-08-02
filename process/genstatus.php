<?php
session_start();
error_reporting(0);

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

// Cek session - fallback jika session nama berbeda
if (!isset($_SESSION["mikhmon"]) && !isset($_SESSION["MIKHMON"])) {
    // Coba cek apakah ada session aktif dengan cara lain
    if (empty($_SESSION)) {
        exit(json_encode(['status' => 'unauthorized']));
    }
}

$gen_id = preg_replace('/[^a-zA-Z0-9_.]/', '', $_GET['gen_id'] ?? '');
if (empty($gen_id)) {
    echo json_encode(['status' => 'invalid']);
    exit;
}

// Coba beberapa path untuk menemukan file status
$possiblePaths = [
    __DIR__ . '/../voucher/genstat_' . $gen_id . '.json',                           // /www/.../process/../voucher/
    dirname(__DIR__) . '/voucher/genstat_' . $gen_id . '.json',                     // /www/.../voucher/
    '/www/wwwroot/subhan.semesta.biz.id/voucher/genstat_' . $gen_id . '.json',      // absolute fallback
];

$statusFile = null;
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $statusFile = $path;
        break;
    }
}

if (!$statusFile) {
    echo json_encode(['status' => 'unknown', 'debug' => 'file not found']);
    exit;
}

$content = file_get_contents($statusFile);
$data = json_decode($content, true);
if (!$data) {
    echo json_encode(['status' => 'error']);
    exit;
}

// Hapus file status jika sudah done dan sudah dibaca
if (($data['status'] ?? '') === 'done') {
    @unlink($statusFile);
}

echo json_encode($data);
