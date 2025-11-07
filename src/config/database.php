<?php

/**
 * Conexão com o banco de dados (MySQL via PDO)
 * Lê automaticamente as variáveis do arquivo .env
 */

$envPath = __DIR__ . '/../../.env';

if (file_exists($envPath)) {
  $envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($envLines as $line) {
    if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
      continue;
    }
    list($key, $value) = explode('=', $line, 2);
    $key = trim($key);
    $value = trim($value);
    putenv("$key=$value");
  }
} else {
  error_log("Aviso: arquivo .env não encontrado em $envPath");
}

$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbName = getenv('DB_NAME') ?: 'clinica_pilates';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbCharset = getenv('DB_CHARSET') ?: 'utf8mb4';

$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=$dbCharset";

try {
  $pdo = new PDO($dsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);
} catch (PDOException $e) {
  error_log("Erro ao conectar ao banco: " . $e->getMessage());
  die("<h3 style='color:red;'>Erro ao conectar ao banco de dados.</h3>");
}
