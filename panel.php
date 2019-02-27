<?php
  session_start();
  if(!isset($_SESSION['logged'])){
    header('Location: index.php');
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>User panel</title>
  </head>
  <link rel="stylesheet" href="style.css">
  <body>

    <?php
      echo "<h1>".$_SESSION['user']." panel</h1>";
      echo "<p><a href='logout.php'>Logout</a></p>";
      echo "Hello <b>".$_SESSION['user']."</b> (<small>".$_SESSION['email']."</small>)!";
      echo "<p>Premium: ".$_SESSION['premium']."</p>";
    ?>

  </body>
</html>
