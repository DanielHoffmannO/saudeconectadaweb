<?php

session_start();

define('BASE_URL', 'http://localhost:8000/public');
define('ROOT_PATH', realpath(dirname(__DIR__)));

function sanitize($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$GLOBALS['usuarios'] = [
    'medico' => [
        '123456' => password_hash('senha123', PASSWORD_BCRYPT)
    ],
    'paciente' => [
        '11122233344' => password_hash('senha456', PASSWORD_BCRYPT)
    ]
];
