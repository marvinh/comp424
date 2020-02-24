<?php
include_once('dbconfig.php');
include_once('PDOConnection.php');

require_once 'config/PHPMailer.php';
require_once 'config/LoginRegistration.php';

require_once 'libraries/PHPMailer.php';
require_once 'libraries/class.smtp.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$repeat = $_POST['repeat-password'];
$firstName = $_POST['first-name'];
$lastName = $_POST['last-name'];
$birthDate = $_POST['birth-date'];

$answer0 = $_POST['answer-0'];
$answer1 = $_POST['answer-1'];
$answer2 = $_POST['answer-2'];

if($password!=$repeat)
{
    echo "Password mismatch.";
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



//Add Question Answers





