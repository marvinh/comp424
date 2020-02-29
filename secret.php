<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once('dbconfig.php');
include_once('PDOConnection.php');

if (!isset( $_SESSION['user_id'] ) ) {
    // Grab user data from the database using the user_id
    header("Location: login.html");
} else {

    $db = PDOConnection::getInstance()->connection;
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(":id", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch();

    $stmt = $db->prepare("SELECT * FROM login_log WHERE user = :user");
    $stmt->bindParam(":user",$_SESSION['user_id']);
    $stmt->execute();
    $logins = $stmt->fetch();
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Seceret Page</title>
  </head>
  <body>
    <div class="container">
        <h1> Seceret Page </h1>
        <p> Welcome <?php echo $user['first_name']." ".$user["last_name"]; ?> </p>
        <p> Total Login Attempts: <?php echo $logins['attempts']; ?> </p>
        <p> Total Successful Logins: <?php echo $logins['success']; ?> </p>
        <p> Total Failed Logins: <?php echo $logins['fail']; ?> </p>
        <embed src="secret/company_confidential_file.txt"> </embed>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>


