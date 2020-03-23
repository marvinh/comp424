<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('source/dbconfig.php');
include_once('source/PDOConnection.php');
require_once 'config/PHPMailer.php';
require_once 'libraries/PHPMailer.php';
require_once 'libraries/class.smtp.php';

if(!isset($_POST["g-recaptcha-response"]) || $_POST["g-recaptcha-response"] == "")
{
    echo "Invalid!";
    die();
}
$captcha = $_POST["g-recaptcha-response"];
//Verify reCaptcha Server side;
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
$email = $_POST['email'];
$password = $_POST['password'];
$repeat = $_POST['repeat-password'];
$firstName = $_POST['first-name'];
$lastName = $_POST['last-name'];
$birthDate = $_POST['birth-date'];

$answerOne = password_hash($_POST['answer-0'], PASSWORD_BCRYPT);
$answerTwo = password_hash($_POST['answer-1'], PASSWORD_BCRYPT);
$answerThree = password_hash($_POST['answer-2'], PASSWORD_BCRYPT);



if($password!=$repeat)
{
    echo "Password mismatch.";
    return;
}
if(strlen($password) < 6){
	echo "Password Too short.";
	return;
}


///password_verify ( string $password , string $hash ) : bool
$bcryptPassword = password_hash($password, PASSWORD_BCRYPT);
$db = PDOConnection::getInstance()->connection;

//var_dump($db);
$stmt = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
$stmt->bindParam(":username", $username, PDO::PARAM_STR);
$stmt->bindParam(":email", $email, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll();



if(sizeof($result) > 0) {
    echo "Username or email already registered.";
    return;
}

$token = md5($email.$username);

//Create User 
$stmt = $db->prepare("INSERT INTO users (username, email, pass, first_name, last_name, birth_date, token)
                                 values (:username, :email, :pass, :first_name, :last_name, :birth_date, :token)");
$stmt->bindParam(":username", $username, PDO::PARAM_STR);
$stmt->bindParam(":email", $email, PDO::PARAM_STR);
$stmt->bindParam(":pass", $bcryptPassword, PDO::PARAM_STR);
$stmt->bindParam(":first_name", $firstName, PDO::PARAM_STR);
$stmt->bindParam(":last_name", $lastName, PDO::PARAM_STR);
$stmt->bindParam(":birth_date", $birthDate, PDO::PARAM_STR);
$stmt->bindParam(":token", $token, PDO::PARAM_STR);

if($stmt->execute())
{
    //Get ID 
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch();
    $userId = $result['id'];

    //insert questions

    $stmt = $db->prepare("INSERT INTO user_question_answer (user, question_id, answer) values (:user, 1 , :answer)");
    $stmt->bindParam(":user", $userId);
    $stmt->bindParam(":answer", $answerOne, PDO::PARAM_STR);
    $stmt->execute();
    $stmt = $db->prepare("INSERT INTO user_question_answer (user, question_id, answer) values (:user, 2, :answer)");
    $stmt->bindParam(":user", $userId);
    $stmt->bindParam(":answer", $answerTwo, PDO::PARAM_STR);
    $stmt->execute();
    $stmt = $db->prepare("INSERT INTO user_question_answer (user, question_id, answer) values (:user, 3 , :answer)");
    $stmt->bindParam(":user", $userId);
    $stmt->bindParam(":answer", $answerThree, PDO::PARAM_STR);
    $stmt->execute();

    //insert login attempts
    $stmt = $db->prepare("INSERT INTO login_log (user) values (".$userId.")");
    $stmt->execute();

    // send email
    sendVerificationEmail($email,$token);
    echo "Verification email has been sent.";
}


function sendVerificationEmail($email,$token)
{

    $mail = new PHPMailer;

    // please look into the config/config.php for much more info on how to use this!
    // use SMTP or use mail()
    if (EMAIL_USE_SMTP) 
    {
      // Set mailer to use SMTP
      $mail->isSMTP();
      $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
      );
      //useful for debugging, shows full SMTP errors
      //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
      // Enable SMTP authentication
      $mail->SMTPAuth = EMAIL_SMTP_AUTH;
      // Enable encryption, usually SSL/TLS
      if (defined(EMAIL_SMTP_ENCRYPTION)) 
      {
        $mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;
      }
      // Specify host server
      $mail->Host = EMAIL_SMTP_HOST;
      $mail->Username = EMAIL_SMTP_USERNAME;
      $mail->Password = EMAIL_SMTP_PASSWORD;
      $mail->Port = EMAIL_SMTP_PORT;
    } 
    else 
    {
      $mail->isMail();
    }

    $mail->From = EMAIL_VERIFICATION_FROM;
    $mail->FromName = EMAIL_VERIFICATION_FROM_NAME;
    $mail->addAddress($email);
    $mail->Subject = EMAIL_VERIFICATION_SUBJECT;

    $link = EMAIL_VERIFICATION_URL.'?email='.$email.'&token='.$token;
    // the link to your register.php, please set this value in config/email_verification.php
    $mail->Body = EMAIL_VERIFICATION_CONTENT.' '.$link;

    if(!$mail->send()) 
    {
    
      $this->errors[] = MESSAGE_VERIFICATION_MAIL_NOT_SENT . $mail->ErrorInfo;
      return false;
    } 
    else 
    {
      return true;
    }
} 
?>






