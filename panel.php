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
  <link rel="stylesheet" href="css/style.css">
  <body>

    <?php
      $actualDate = new DateTime('now');
      $endTime = new DateTime($_SESSION['premium']);
      $diff = $actualDate->diff($endTime);

      echo "<h1>".$_SESSION['user']." panel</h1>";
      echo "<p><a href='logout.php'>Logout</a></p>";
      echo "Hello <b>".$_SESSION['user']."</b> (<small>".$_SESSION['email']."</small>)!";
      if($actualDate<$endTime) {
        echo "<p>Premium expiration date: ".$_SESSION['premium']." (".$diff->h." hours)</p>";
      } else {
        echo "<p>Premium expiration date: no premium</p>";
      }

    ?>

  </body>
</html>
