<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once("env.php");
include_once('dbconfig.php');
include_once('PDOConnection.php');

if(!isset($_POST["g-recaptcha-response"]))
{
    header("Location:login.html");
    die();
}

$captcha = $_POST["g-recaptcha-response"];

if($captcha=="")
{
    echo "Verify with reCaptcha!";
    die();
}

$username = $_POST['username'];
$password = $_POST['password'];

$db = PDOConnection::getInstance()->connection;
$stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND verified=1");
$stmt->bindParam(":username", $username, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetch();

if($results)
{
    $user_id = $results['id'];
    $stmt = $db->prepare("UPDATE login_log SET attempts = attempts + 1 WHERE user = :user");
    $stmt->bindParam("user", $user_id);
    $stmt->execute();
    if(password_verify($password,$results['pass']))
    {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['loggedin'] = true;

        $stmt = $db->prepare("UPDATE login_log SET success = success + 1 WHERE user = :user");
        $stmt->bindParam("user", $user_id);
        $stmt->execute();
        
        echo "success";
    }else{
        $_SESSION['loggedin'] = false;

        $stmt = $db->prepare("UPDATE login_log SET fail = fail + 1 WHERE user = :user");
        $stmt->bindParam("user", $user_id);
        $stmt->execute();

        echo "Wrong credentials";
    }
}else{
    $_SESSION['loggedin'] = false;
    echo "Account does not exist or is not verified";
}
