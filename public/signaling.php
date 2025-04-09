<?php
session_start();
header('Content-Type: application/json');

$session = $_REQUEST['session'] ?? 'default';
$file = __DIR__ . "/signals_{$session}.json";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = file_get_contents('php://input');
    file_put_contents($file, $msg);
    echo json_encode(['status'=>'ok']);
    exit;
}

if (file_exists($file)) {
    $data = file_get_contents($file);
    echo $data;
} else {
    echo json_encode(null);
}
