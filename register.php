<?php
  session_start();
  require_once "config/db.php";
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(isset($_POST['email'])) {

    $login = $_POST['login'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $email = $_POST['email'];

    $validation = true;

    // Checking login
    if((strlen($login) <= 3) || (strlen($login) >= 25)) {
      $_SESSION['e_login'] = "Login must be between 3 - 25 characters.";
      $validation = false;
    }

    if(ctype_alnum($login) == false) {
      $_SESSION['e_login'] = "Login must consist of letters and numbers only.";
      $validation = false;
    }

    // Checking e-mail
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if((filter_var($email, FILTER_VALIDATE_EMAIL) == false)) {
      $_SESSION['e_email'] = "Check your e-mail.";
      $validation = false;
    }

    // Checking password
    if((strlen($password1)<=8) || (strlen($password1)>=30)) {
      $_SESSION['e_password'] = "Password must be between 8 - 30 characters.";
      $validation = false;
    }

    if($password1!=$password2) {
      $_SESSION['e_password'] = "The passwords must be the same.";
      $validation = false;
    }

    $password1 = password_hash($password1, PASSWORD_DEFAULT);

    // Checking ReCaptcha
    $checkReCaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_secret_key.'&response='.$_POST['g-recaptcha-response']);

    $responseReCaptcha = json_decode($checkReCaptcha);

    if(!($responseReCaptcha->success)) {
      $_SESSION['e_recaptcha'] = "Confirm that you are not a bot.";
      $validation = false;
    }

    try {
      $connection = new mysqli($host, $db_user, $db_password, $db_name);
      if($connection->connect_errno!=0) {
        throw new Exception(mysqli_connect_errno());
      } else {
        $result = $connection->query("select id from $db_user_table where email='$email'");
        if(!$result) throw new Exception($connection->error);
        $emailExist = $result->num_rows;
        // Checking e-mail
        if($emailExist>0) {
          $_SESSION['e_email'] = "This e-mail is exist.";
          $validation = false;
        }

        // Checking login
        $result = $connection->query("select id from $db_user_table where user='$login'");
        if(!$result) throw new Exception($connection->error);
        $emailExist = $result->num_rows;
        if($emailExist>0) {
          $_SESSION['e_login'] = "This login is exist.";
          $validation = false;
        }

        // Checking e-mail
        if($validation == true) {
          if($connection->query("insert into $db_user_table values(null, '$login', '$password1', '$email', 0, 0, 0, 30, null, ' ')")) {
            $_SESSION['registerSuccess'] = true;
            header('Location: index.php');
          } else {
            throw new Exception($connection->error);
          }
        }

        $connection->close();
      }
    } catch(Exception $error) {
      echo "<span class='error'>Error, try again!</span>";
    }
  }

?>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Sing up new user</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <link rel="stylesheet" href="css/style.css">
  <body>

    <h1>Register form</h1>

    <p><a href="index.php">Back to main page</a></p>

    <form action="register.php" method="post">
      <input type="text" name="login" placeholder="Your login"><br/>
        <?php if(isset($_SESSION['e_login'])) { echo "<span class='error'>".$_SESSION['e_login']."</span>"; } unset($_SESSION['e_login']); ?>
      <input type="password" name="password1" placeholder="Your password"><br/>
      <input type="password" name="password2" placeholder="Retype your password"><br/>
        <?php if(isset($_SESSION['e_password'])) { echo "<span class='error'>".$_SESSION['e_password']."</span>"; } unset($_SESSION['e_password']); ?>
      <input type="text" name="email" placeholder="Your e-mail"><br/>
        <?php if(isset($_SESSION['e_email'])) { echo "<span class='error'>".$_SESSION['e_email']."</span>"; } unset($_SESSION['e_email']); ?>
      <div class="g-recaptcha" data-sitekey="6LcuZZQUAAAAANdNUV6W1WYrCV4yFBS-aSV75GBb"></div>
        <?php if(isset($_SESSION['e_recaptcha'])) { echo "<span class='error'>".$_SESSION['e_recaptcha']."</span>"; } unset($_SESSION['e_recaptcha']); ?>
      <input type="submit" value="Register">
    </form>

  </body>
</html>
