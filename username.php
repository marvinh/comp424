<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('source/dbconfig.php');
include_once('source/PDOConnection.php');
require_once 'config/PHPMailer.php';
require_once 'libraries/PHPMailer.php';
require_once 'libraries/class.smtp.php';

$email = $_POST["email"];
$birth = $_POST["birth-date"];

$db = PDOConnection::getInstance()->connection;
$stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND birth_date = :birth_date");
$stmt->bindParam(":email", $email, PDO::PARAM_STR);
$stmt->bindParam(":birth_date", $birth, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetch();

if($results)
{
    sendUsername($results["email"],$results["username"]);
}

echo "Please check your email for your credential.";

function sendUsername($email,$username)
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
    $mail->Subject = "COMP424 forgot username";

    $yourUsername = "Your username is: " . $username;
    
    $mail->Body = $yourUsername;

    if(!$mail->send()) 
    {
    
      $this->errors[] = $mail->ErrorInfo;
      return false;
    } 
    else 
    {
      return true;
    }
} 
?>