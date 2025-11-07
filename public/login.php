<?php
session_start();

require_once __DIR__ . '/../src/config/database.php';

$error = '';
$success = '';

if (isset($_SESSION['usuario_id'])) {
  header("Location: index.php");
  exit;
}

if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
  $success = "Você saiu com sucesso!";
}

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
      $stmt = $pdo->prepare("SELECT id, nome, email, senha, tipo_usuario, ativo FROM usuarios WHERE email = :email LIMIT 1");
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

          header("Location: index.php");
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
            <div class="input-group">
              <span class="input-group-text">
                <svg width="20" height="20" fill="currentColor">
                  <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z" />
                </svg>
              </span>
              <input type="email" class="form-control" id="email" name="email"
                placeholder="seu@email.com"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
            </div>
          </div>

          <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <div class="input-group">
              <span class="input-group-text">
                <svg width="20" height="20" fill="currentColor">
                  <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                </svg>
              </span>
              <input type="password" class="form-control" id="senha" name="senha" placeholder="••••••••" required>
              <span class="input-group-text password-toggle" onclick="togglePassword()">
                <svg width="20" height="20" fill="currentColor" id="eyeIcon">
                  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                </svg>
              </span>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="lembrar" name="lembrar">
              <label class="form-check-label" for="lembrar">Lembrar-me</label>
            </div>
            <a href="recuperar-senha.php" class="text-decoration-none">Esqueceu a senha?</a>
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
      const eyeIcon = document.getElementById('eyeIcon');
      if (senhaInput.type === 'password') {
        senhaInput.type = 'text';
        eyeIcon.innerHTML = '<path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/><path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/><path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>';
      } else {
        senhaInput.type = 'password';
        eyeIcon.innerHTML = '<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>';
      }
    }
  </script>
</body>

</html>