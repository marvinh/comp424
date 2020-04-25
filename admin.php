<?php
session_start();
require "input.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once('source/env.php');
include_once('source/PDOConnection.php');
$message = "";
$token = null;
if (!isset( $_SESSION['user_id'] ) ) {
    
    header("Location: login.html");
} else {

   

    //GET CURRENT PASSWORD FROM DATABASE WITH USER_ID
    $user_id =  $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
         //csrf token
        if (empty($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }

    }else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        //verify csrf token
        if($_POST['csrf-token']===$_SESSION['token'])
        {
            //my code
            $password = sanitize($_POST['new-password']);
            $repeat = sanitize($_POST['repeat-new-password']);

            //verify passwords match
            //else invalid
            if($password==$repeat)
                {
                    $db = PDOConnection::getInstance()->connection;
                    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
                    $stmt->bindParam(":id", $user_id);
                    $stmt->execute();
                    $results = $stmt->fetch();
                    $current = $results["pass"];
                    
                    if (password_verify($_POST["current"],$current)){

                         //verify current password is correct READ DATABASE ENTRY FOR PASSWORD
                         //else invalid
                        ///password_verify ( string $password , string $hash ) : bool
                        $bcryptPassword = password_hash($password, PASSWORD_BCRYPT);
                        $stmt = $db->prepare("UPDATE users SET pass = :pass WHERE id = :id");
                        $stmt->bindParam(":id", $user_id);
                        $stmt->bindParam(":pass",$bcryptPassword);
                        $stmt->execute();
                        $message = "Password change successful.";
                    }

                    else
                    {
                        $message = "Invalid current password.";
                    }
                 
                }

            else{
                $message = "Password mismatch.";
            }
            
                


            //IF BOTH ARE CORRECT then //update password for user id


        }else{

            $message = "invalid";
        }

        


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

    <title> Comp 424 Change Password </title>
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
            <a class="nav-link" href="secret.php"> Secret </a>
        </li>
        <li class="nav-item active">
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
        <h1> Change Password </h1>
        <form #passowrdForm id="passwordForm" method="POST">
            <div id="server-notice">
                <?php echo $message;?>
            </div>
            
            <div class="row">
            <input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token'];?>" />
            <div class="form-group col-12">
                    <label> Current Password </label>
                    <input id="current"  name="current" type="password" class="form-control" placeholder="Current Password" required/>
                </div>

                <div class="form-group col-12">
                    <label> New Password </label>
                    <input id="password" onkeyup="analyze()" name="new-password" type="password" class="form-control" placeholder="New Password" required/>
                    <p id="password-notice"> </p>
                </div>

                <div class="form-group col-12">
                    <label> Repeat Password </label>
                    <input id="repeat" onkeyup="confirmRepeat()" name="repeat-new-password" type="password" class="form-control" placeholder="Repeat Password" required/>
                    <p id="repeat-notice"> </p>
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

    <script>
        var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
        var mediumRegex = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})");

        function analyze() {
            var value = $("#password").val();
            var notice = document.getElementById("password-notice");
            if(strongRegex.test(value)) {
                notice.innerHTML = "Strong Password";
                notice.setAttribute("style", "color: green;");
            } else if(mediumRegex.test(value)) {
                notice.innerHTML = "Okay Password";
                notice.setAttribute("style", "color: orange;");
            } else {
                notice.innerHTML = "Weak Password";
                notice.setAttribute("style", "color: red;");
            }
        }

        function confirmRepeat()
        {
            var val1 = $("#password").val();
            var val2 = $("#repeat").val();
            var notice = document.getElementById("repeat-notice");
            if(val1 != val2)
            {
                notice.innerHTML = "Passwords do not match!";
                notice.setAttribute("style", "color: red;");
            }else{
                notice.innerHTML = "Passwords match!";
                notice.setAttribute("style", "color: green;");
            }
        }

        

    </script>
  </body>
</html>

