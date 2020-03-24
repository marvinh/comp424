<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('source/dbconfig.php');
include_once('source/PDOConnection.php');
require_once 'config/PHPMailer.php';
require_once 'libraries/PHPMailer.php';
require_once 'libraries/class.smtp.php';


// $apc_key = "{$_SERVER['SERVER_NAME']}~login:{$_SERVER['REMOTE_ADDR']}";
// $tries = (int)apc_fetch($apc_key);
// if ($tries >= 10) {
//   header("HTTP/1.1 429 Too Many Requests");
//   echo "You've exceeded the number of login attempts. We've blocked IP address {$_SERVER['REMOTE_ADDR']} for a few minutes.";
//   exit();
// }



$db = PDOConnection::getInstance()->connection;
$stmt = $db->prepare("SELECT * FROM questions");
$stmt->execute();
$result = $stmt->fetchAll();
$question = $result[rand(0,2)];

$message = "";

if(isset($_POST["username"]) && isset($_POST["answer"]))
{
    


$username =  $_POST["username"];
$answer = $_POST["answer"];
$question_id = $question["id"];

$db = PDOConnection::getInstance()->connection;
$stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
$stmt->bindParam(":username", $username);
$stmt->execute();
$user = $stmt->fetch();


if($user)
{
    $user_id = $user["id"];
    $db = PDOConnection::getInstance()->connection;
    $stmt = $db->prepare("SELECT * FROM user_question_answer WHERE user = :user AND question_id = :question_id");
    $stmt->bindParam(":user", $user_id);
    $stmt->bindParam(":question_id", $question_id);
    $stmt->execute(); 
    $userQuestionAnswer = $stmt->fetch();
   
    if($userQuestionAnswer){

        if(password_verify($answer,$userQuestionAnswer["answer"]))
        {
            $message =  "Success";
        }else{
            $message = "Try Again";
        }

    }else{
        $message = "Try Again";
    }

    

}else{
    $message = "Try Again";
    //return;
}

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

    <title> Comp 424 Forgot Password </title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">COMP 424 </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="login.html">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="register.html">Register</a>
      </li>

    </ul>
  </div>
</nav>

<div class="container">
        <h1> Forgot Password </h1>
        <form #passowrdForm id="passwordForm" method="POST">
            <div id="server-notice"> 
                <?php echo $message;?> 
            </div>
            <div class="row">
                <div class="form-group col-12">
                    <label> Username </label>
                    <input name="username" type="text" class="form-control" placeholder="Username" required/>
                </div>
                <div id="securityQuestion" class="form-group col-12">
                <label> <?php echo $question["question"] ?> </label>
                    <input name="answer" type="password" class="form-control" placeholder="Answer" required/>
                </div>
                
                <div class="form-group col-12">
                    <button type="submit" name="submit" class="btn btn-primary"> Submit </button>
                </div>
            </div>
        </form>
    </div>

    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

  </body>
</html>