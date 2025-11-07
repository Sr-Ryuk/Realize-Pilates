<?php
session_start();

require_once __DIR__ . '/../src/config/database.php';

$error = '';
$success = '';

/**
 * Se o usuário já estiver logado, redireciona para o painel
 */
if (isset($_SESSION['usuario_id'])) {
  $basePath = dirname($_SERVER['SCRIPT_NAME']);
  header("Location: $basePath/index");
  exit;
}

/**
 * Mensagem após logout
 */
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
  $success = "Você saiu com sucesso!";
}

/**
 * Processa o envio do formulário
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $senha = trim($_POST['senha'] ?? '');
  $lembrar = isset($_POST['lembrar']);

  if (empty($email) || empty($senha)) {
    $error = "Por favor, preencha todos os campos.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Por favor, insira um e-mail válido.";
  } else {
    try {
      $stmt = $pdo->prepare("SELECT id, nome, email, senha, tipo_usuario, ativo 
                             FROM usuarios WHERE email = :email LIMIT 1");
      $stmt->execute(['email' => $email]);
      $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($usuario && $usuario['ativo']) {
        if (password_verify($senha, $usuario['senha'])) {
          session_regenerate_id(true);

          $_SESSION['usuario_id'] = $usuario['id'];
          $_SESSION['usuario_nome'] = $usuario['nome'];
          $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];
          $_SESSION['usuario_email'] = $usuario['email'];
          $_SESSION['login_time'] = time();

          if ($lembrar) {
            // implementar cookie com token seguro (lembrar login)
          }

          // Redireciona dinamicamente para /index (sem .php)
          $basePath = dirname($_SERVER['SCRIPT_NAME']);
          header("Location: $basePath/index");
          exit;
        } else {
          $error = "E-mail ou senha inválidos!";
          error_log("Tentativa de login falha (senha incorreta) para: " . $email);
        }
      } else {
        $error = "Usuário não encontrado ou inativo.";
        error_log("Tentativa de login falha (usuário inativo) para: " . $email);
      }
    } catch (PDOException $e) {
      $error = "Erro ao processar login. Tente novamente.";
      error_log("Erro no login: " . $e->getMessage());
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Clínica de Pilates</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/login.css">
</head>

<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
        </svg>
        <h1>Clínica de Pilates</h1>
        <p>Acesse sua conta</p>
      </div>

      <div class="login-body">
        <?php if ($error): ?>
          <div class="alert alert-danger d-flex align-items-center" role="alert">
            <svg width="20" height="20" fill="currentColor" class="me-2">
              <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
            </svg>
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <?php if ($success): ?>
          <div class="alert alert-success d-flex align-items-center" role="alert">
            <svg width="20" height="20" fill="currentColor" class="me-2">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
            </svg>
            <?= htmlspecialchars($success) ?>
          </div>
        <?php endif; ?>

        <form method="POST" id="loginForm">
          <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email"
              placeholder="seu@email.com"
              value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
          </div>

          <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <div class="input-group">
              <input type="password" class="form-control" id="senha" name="senha" placeholder="••••••••" required>
              <span class="input-group-text password-toggle" onclick="togglePassword()">👁️</span>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="lembrar" name="lembrar">
              <label class="form-check-label" for="lembrar">Lembrar-me</label>
            </div>
            <a href="recuperar-senha" class="text-decoration-none">Esqueceu a senha?</a>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-login">Entrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const senhaInput = document.getElementById('senha');
      senhaInput.type = senhaInput.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>

</html>