<?php
session_start();
// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>SER-E-SER</title>
  <link rel="icon" type="image/x-icon" href="fvicon.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: sans-serif;
    }

    a {
      color: #666;
      font-size: 14px;
      display: block;
    }

    .login-title {
      text-align: center;
    }

    #login-page {
      display: flex;
    }

    .notice {
      font-size: 13px;
      text-align: center;
      color: #666;
    }

    .login {
      width: 30%;
      height: 100vh;
      background: #FFF;
      padding: 70px;
    }

    .login a {
      margin-top: 25px;
      text-align: center;
    }

    .form-login {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      align-content: center;
    }

    .form-login label {
      text-align: left;
      font-size: 13px;
      margin-top: 10px;
      margin-left: 20px;
      display: block;
      color: #666;
    }

    .input-email,
    .input-password {
      width: 100%;
      background: #ededed;
      border-radius: 25px;
      margin: 4px 0 10px 0;
      padding: 10px;
      display: flex;
    }

    .icon {
      padding: 4px;
      color: #666;
      min-width: 30px;
      text-align: center;
    }

    input[type="username"],
    input[type="password"] {
      width: 100%;
      border: 0;
      background: none;
      font-size: 16px;
      padding: 4px 0;
      outline: none;
    }

    button[type="submit"] {
      width: 100%;
      border: 0;
      border-radius: 25px;
      padding: 14px;
      background: #008552;
      color: #FFF;
      display: inline-block;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
      margin-top: 10px;
      transition: ease all 0.3s;
    }

    button[type="submit"]:hover {
      opacity: 0.9;
    }

    .background {
      width: 70%;
      height: 100vh;
      background-size: cover;
      display: flex;
      align-content: center;
      flex-direction: row;
      position: relative;
    }

    .background h1 {
      max-width: 420px;
      color: #FFF;
      text-align: right;
      padding: 0;
      margin: 0;
    }

    .background p {
      max-width: 650px;
      color: #1a1a1a;
      font-size: 15px;
      text-align: right;
      padding: 0;
      margin: 15px 0 0 0;
    }

    .created {
      margin-top: 40px;
      text-align: center;
      border-top: 1px solid #80808054;
    }

    .created p {
      font-size: 13px;
      font-weight: bold;
      color: #008552;
    }

    .created a {
      color: #666;
      font-weight: normal;
      text-decoration: none;
      margin-top: 0;
    }

    .checkbox label {
      display: inline;
      margin: 0;
    }

    .error {
      color: red;
      font-size: 13px;
      text-align: center;
      margin-top: 10px;
    }

    @media screen and (max-width: 768px) {
      .background {
        display: none;
      }

      .login {
        width: 100%;
        padding: 30px;
        height: auto;
        min-height: 100vh;
      }

      #login-page {
        flex-direction: column;
      }

      .form-login {
        margin-top: 20px;
      }

      .login-title {
        margin-top: 20px;
      }
    }
  </style>
</head>

<body>


  <div id="login-page">
    <div class="login">
      <div style="display: flex; justify-content: center; align-items: center; padding-bottom: 30px;border-bottom: 1px solid #80808054;">
        <img src="img/logo.svg" alt="logo da marcar" width="180">
      </div>
      <h2 class="login-title">Login</h2>

      <?php
      if (isset($_SESSION['error'])) {
        echo '<div class="error">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']);
      }
      ?>

      <p class="notice">Faça login para acessar o sistema</p>
      <form class="form-login" action="authenticate.php" method="POST">
        <label for="email">E-mail</label>
        <div class="input-email">
          <i class="fas fa-envelope icon"></i>
          <input type="username" name="username" placeholder="Digite seu username" required>
        </div>
        <label for="password">Password</label>
        <div class="input-password">
          <i class="fas fa-lock icon"></i>
          <input type="password" name="password" placeholder="Digite sua senha" required>
        </div>
        <div class="checkbox">
          <label for="remember">
            <input type="checkbox" name="remember">
            Relembrar
          </label>
        </div>
        <button type="submit"><i class="fas fa-door-open"></i> Entrar</button>
      </form>

      <div class="created">
        <p>Created by <a href="#">Wandayk Cavalcante</a></p>
      </div>
    </div>
    <div class="background">
      <img src="img/background.jpg" alt="background" style="position: absolute;width: 100%;height: 100%;-webkit-transform: scaleX(-1);
  transform: scaleX(-1);">
    </div>
  </div>



</body>

</html>