<?php
  session_start();
  require_once "config/db.php";

  if(!isset($_POST['login']) && !isset($_POST['password'])){
    header('Location: index.php');
    exit;
  } else {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $login = htmlentities($login, ENT_QUOTES, "UTF-8");
    $password = htmlentities($password, ENT_QUOTES, "UTF-8");
  }

  $connection = @new mysqli($host, $db_user, $db_password, $db_name);

  if($connection->connect_errno!=0) {
    echo "Error ".$connection->connect_errno." Description: ".$connection->connect_error;
  } else {
    if($result = @$connection->query(sprintf("select * from %s where user='%s'",
    mysqli_real_escape_string($connection,$db_user_table),
    mysqli_real_escape_string($connection,$login)))) {
      $howUsers = $result->num_rows;
      if($howUsers>0) {
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])) {
          $_SESSION['logged'] = true;
          $_SESSION['id'] = $row['id'];
          $_SESSION['user'] = $row['user'];
          $_SESSION['email'] = $row['email'];
          $_SESSION['res1'] = $row['res1'];
          $_SESSION['premium'] = $row['premium'];
          unset($_SESSION['error']);
          $result->close();
          header('Location: panel.php');
        } else {
          $_SESSION['error'] = "Check your login or password!";
          header('Location: index.php');
        }
      } else {
        $_SESSION['error'] = "Check your login or password!";
        header('Location: index.php');
      }
    }
$connection->close();
  }

?>
