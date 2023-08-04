<?php

namespace src\login;

require __DIR__.'/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'canalapenasmaisumdev';

$headers = getallheaders();
$objHeaders = json_decode(json_encode($headers), FALSE);

try {
  if (!empty($objHeaders->Authorization)){
    if (preg_match('/Bearer\s(\S+)/', $objHeaders->Authorization, $matches)) {
      JWT::$leeway = 10;
      $decoded = JWT::decode($matches[1], new Key($key, 'HS256'));
      header('Content-Type: application/json');
      $objDecoded = json_decode(json_encode($decoded), FALSE);

      if ($objDecoded->email == 'email@teste.com') {
        if ($objDecoded->senha == '123456') {
          
          $json_resp = [
            'mensagem' => 'Autorizado!',
            'codigo' => http_response_code(200)
          ];
          
          echo json_encode($json_resp);    
          return;
        }
      }
    }
  }

  header('HTTP/1.1 401 Sem Autorização');
  header('Content-Type: application/json');

  $json_resp = [
    'erro' => 'sem autorizacao',
    'codigo' => http_response_code(401)
  ];

  echo json_encode($json_resp);  
} catch (\Throwable $th) {

  header('HTTP/1.1 403 Forbiden');
  header('Content-Type: application/json');

  $json_resp = [
    'erro' => $th->getMessage(),
    'codigo' => http_response_code(403)
  ];

  echo json_encode($json_resp);
}
