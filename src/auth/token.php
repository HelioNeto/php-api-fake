<?php

namespace src\auth;

require __DIR__.'/../../vendor/autoload.php';

use Firebase\JWT\JWT;

$key = 'canalapenasmaisumdev';

$json = file_get_contents('php://input');
header('Content-Type: application/json');

if (!isset($json) || ($json == '')){
  header('HTTP/1.1 401 Sem Autorização');
  $json_resp = [
    'erro' => 'sem autorizacao',
    'codigo' => http_response_code(401)
  ];

  return;
}

$jsonObj = json_decode($json);
if ($jsonObj->email == 'email@teste.com') {
  if ($jsonObj->senha == '123456') {

    $expirationTime = Time() + 10; //60 segundos.
    $payload = array(
      'email' => $jsonObj->email,
      'senha' => $jsonObj->senha,
      'exp' => $expirationTime,
    );    

    JWT::$leeway = 1;
    $jsonToken = [
      'token' => JWT::encode($payload, $key, 'HS256'),
      'expires_in' => 60
    ];
    header('HTTP/1.1 200 Autorizado');
    http_response_code(200);    
    echo json_encode($jsonToken);    
    return;
  }
}

header('HTTP/1.1 401 Sem Autorização');
$json_resp = [
  'erro' => 'sem autorizacao',
  'codigo' => http_response_code(401)
];

echo json_encode($json_resp);

return;