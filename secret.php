<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once('source/env.php');
include_once('source/PDOConnection.php');

if (!isset( $_SESSION['user_id'] ) ) {
    // Grab user data from the database using the user_id
    header("Location: login.html");
} else {

    function downloadCompanyFile() {
        $file = "secret/company_confidential_file.txt";
        $exists = file_exists($file) ? "true": "false";
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

    $db = PDOConnection::getInstance()->connection;
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(":id", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch();

    $login_time = $user['login_time'];

    $stmt = $db->prepare("SELECT * FROM login_log WHERE user = :user");
    $stmt->bindParam(":user",$_SESSION['user_id']);
    $stmt->execute();
    $logins = $stmt->fetch();

    if(isset($_GET['download']) && $_GET['download']==true)
    {
        downloadCompanyFile();
    }


}

?>

<?php
    

    
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

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">COMP 424 </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link" href="secret.php"> Secret </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="admin.php"> Change Password </a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
          <a class="nav-link" href="logout.php"> Logout </a>
      </li>
    </ul>

    
  </div>
</nav>

    <div class="container">
        <h1> Seceret Page </h1>
        <p> Welcome <?php echo $user['first_name']." ".$user["last_name"]; ?> </p>
        <p> Total Login Attempts: <?php echo $logins['attempts']; ?> </p>
        <p> Total Successful Logins: <?php echo $logins['success']; ?> </p>
        <p> Total Failed Logins: <?php echo $logins['fail']; ?> </p>
        <p> Login Time (GMT): <?php echo $login_time; ?> </p>
        <p> <a href="secret.php?download=true"> Download Company Confidential File </a> </p>
        <!-- <embed src="secret/company_confidential_file.txt"> </embed> -->
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>


