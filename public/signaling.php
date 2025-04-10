<?php

session_start();
header("Content-Type: application/json");

$roomFile = __DIR__ . '/../data/room.json';

if (!file_exists($roomFile)) {
    file_put_contents($roomFile, json_encode([]));
}

$roomData = json_decode(file_get_contents($roomFile), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    $roomData[] = $input;
    file_put_contents($roomFile, json_encode($roomData));
    echo json_encode(["status" => "ok"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($roomData);
    // Limpa após ler
    file_put_contents($roomFile, json_encode([]));
    exit;
}
