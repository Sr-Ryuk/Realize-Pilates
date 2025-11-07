<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * AUTH.PHP
 * --------------------------------------------
 * Protege páginas internas e valida sessão.
 * Requer login obrigatório para todas as rotas.
 * --------------------------------------------
 */

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

/**
 *  Carrega variáveis do .env (apenas se ainda não carregadas)
 */
$envPath = __DIR__ . '/../../.env';
if (file_exists($envPath)) {
  $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
    [$key, $value] = explode('=', $line, 2);
    putenv(trim($key) . '=' . trim($value));
  }
}

/**
 *  Define timezone (padrão: America/Sao_Paulo)
 */
date_default_timezone_set(getenv('TIMEZONE') ?: 'America/Sao_Paulo');

/**
 *  Controle de erros com base no ambiente (.env)
 */
$appEnv = getenv('APP_ENV') ?: 'production';
$appDebug = getenv('APP_DEBUG') === 'true';

if ($appDebug && $appEnv === 'development') {
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
} else {
  ini_set('display_errors', 0);
  error_reporting(0);
}

/**
 *  Verificação de login
 */
if (empty($_SESSION['usuario_id'])) {

  error_log("Acesso não autorizado: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . " - " . date('Y-m-d H:i:s'));

  header('Location: /realize_pilates_final/public/login.php');

  exit();
}
