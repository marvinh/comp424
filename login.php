<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once("source/env.php");
include_once('source/PDOConnection.php');
require "input.php";

if(!isset($_POST["g-recaptcha-response"]))
{
    header("Location:login.html");
    die();
}

$captcha = $_POST["g-recaptcha-response"];

//Verify Captcha 
if($captcha=="")
{
    echo "invalid";
    die();
}else{
    
    $secretKey = GOOGLE_RECAPTCHA_SECRET;
    $ip = $_SERVER['REMOTE_ADDR'];
    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response,true);
    // should return JSON with success as true
    if($responseKeys["success"]) {
        
        //success

    } else {
        echo 'invalid';
        die();
    }

}



$username = $_POST['username'];
$password = $_POST['password'];

$username = sanitize($username);
$password = sanitize($password);

$db = PDOConnection::getInstance()->connection;
$stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND verified=1");
$stmt->bindParam(":username", $username, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetch();

if($results)
{
    $user_id = $results['id'];
    $stmt = $db->prepare("UPDATE login_log SET attempts = attempts + 1 WHERE user = :user");
    $stmt->bindParam(":user", $user_id);
    $stmt->execute();
    if(password_verify($password,$results['pass']))
    {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['loggedin'] = true;
        
        $stmt = $db->prepare("UPDATE login_log SET success = success + 1 WHERE user = :user");
        $stmt->bindParam(":user", $user_id);
        $stmt->execute();

        $stmt = $db->prepare("UPDATE users SET login_time = :login_time WHERE username = :username");
        $stmt->bindParam(":username", $username);

        $login_time = date('Y-m-d H:i:s',time());

        $stmt->bindParam(":login_time", $login_time);
        $stmt->execute();

        echo "success";
    }else{


        $_SESSION['loggedin'] = false;

        $stmt = $db->prepare("UPDATE login_log SET fail = fail + 1 WHERE user = :user");
        $stmt->bindParam(":user", $user_id);
        $stmt->execute();

        echo "invalid";
    }
}else{
    $_SESSION['loggedin'] = false;
    echo "invalid";
}
