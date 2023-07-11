<?php

require_once 'db.php';
require_once 'endpoint.php';

// Criando uma nova instância do servidor Swoole
$app = new Swoole\HTTP\Server("0.0.0.0", 8099);

$app->on("start", function ($server) {
  echo "Executando na porta 8099";
});

// Definindo os handlers de requisição e resposta
$app->on('request', 'handleRequest');

// Iniciando o servidor Swoole
$app->start();
