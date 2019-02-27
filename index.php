<?php
  session_start();
  if(isset($_SESSION['logged']) && $_SESSION['logged']){
    header('Location: game.php');
    exit;
  }

  if(isset($_SESSION['registerSuccess'])){
    $registerWelcomeText = "Register is successfull, log in!";
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login form</title>
  </head>
  <link rel="stylesheet" href="css/style.css">
  <body>

    <h1>Hello World PHP - login form!</h1>

    <?php
      if(isset($_SESSION['registerSuccess'])){
        echo "<h2>".$registerWelcomeText."</h2>";
        unset($_SESSION['registerSuccess']);
      }
    ?>

    <p><a href="register.php">Sing up here</a></p>

    <form action="login.php" method="post">
      <input type="text" name="login" placeholder="login"><br/>
      <input type="password" name="password" placeholder="password"><br/>
      <input type="submit" value="Log in">
    </form>

    <?php
    if(isset($_SESSION['error'])) {
      echo $_SESSION['error'];
    }
    ?>

  </body>
</html>
