<?php
$dataDir = sys_get_temp_dir(); // ou outro lugar como "data/signaling/"
$offerFile = "$dataDir/offer.json";
$answerFile = "$dataDir/answer.json";
$candidateFile = "$dataDir/candidate.json";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $input = json_decode(file_get_contents('php://input'), true);
  switch ($input['type']) {
    case 'offer':
      file_put_contents($offerFile, json_encode($input['data']));
      break;
    case 'answer':
      file_put_contents($answerFile, json_encode($input['data']));
      break;
    case 'candidate':
      file_put_contents($candidateFile, json_encode($input['data']));
      break;
  }
  exit;
}

$type = $_GET['type'] ?? null;
switch ($type) {
  case 'offer':
    echo file_exists($offerFile) ? file_get_contents($offerFile) : 'null';
    break;
  case 'answer':
    echo file_exists($answerFile) ? file_get_contents($answerFile) : 'null';
    break;
  case 'candidate':
    echo file_exists($candidateFile) ? file_get_contents($candidateFile) : 'null';
    break;
  default:
    echo json_encode(['error' => 'invalid type']);
}
