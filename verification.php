<?php
include('source/dbconfig.php');
include('source/PDOConnection.php');

if(isset($_GET['token']))
{
    $email = $_GET['email'];
    $token  = $_GET['token'];

    $db = PDOConnection::getInstance()->connection;
    $stmt = $db->prepare("UPDATE users SET verified = 1 WHERE email = :email AND token = :token");
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":token", $token, PDO::PARAM_STR);
    if($stmt->execute());
    {
        echo "<a href='login.html'> You may now login</a>";
    }
}