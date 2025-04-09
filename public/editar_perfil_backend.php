<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$cpf = $_SESSION['usuario'];

$usuariosPath = __DIR__ . '/../data/usuarios.json';
$usuarios = json_decode(file_get_contents($usuariosPath), true);

foreach ($usuarios as &$usuario) {
    if ($usuario['cpf'] === $cpf) {
        $usuario = array_merge($usuario, $data);
        file_put_contents($usuariosPath, json_encode($usuarios, JSON_PRETTY_PRINT));
        echo json_encode(['success' => true]);
        exit();
    }
}

echo json_encode(['success' => false, 'message' => 'Usuário não encontrado.']);
