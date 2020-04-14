<?php
include_once("source/env.php");
//($_SERVER['SERVER_ADDR'] == '::1' ? 'localhost' : $_SERVER['SERVER_ADDR']) .':'.$_SERVER['SERVER_PORT']
$host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_ADDR'] : 'localhost' ;//$_SERVER['SERVER_ADDR'] == '::1' ? 'localhost' : $_SERVER['SERVER_ADDR'];
$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
define("COOKIE_RUNTIME", 1209600);
define("COOKIE_DOMAIN", "$domain");
define("COOKIE_SECRET_KEY", "1gp@TMPS{+$78sfpMJFe-92s");
define("EMAIL_USE_SMTP", true);
define("EMAIL_SMTP_HOST", "smtp.gmail.com");
define("EMAIL_SMTP_AUTH", true);
define("EMAIL_SMTP_USERNAME", EMAIL);
define("EMAIL_SMTP_PASSWORD", PASS);
define("EMAIL_SMTP_PORT", 587);
define("EMAIL_SMTP_ENCRYPTION", "tls");

define("EMAIL_PASSWORDRESET_URL", "http://localhost:8888/COMP424/reset.php");
define("EMAIL_PASSWORDRESET_FROM", "comp424group@gmail.com");
define("EMAIL_PASSWORDRESET_FROM_NAME", "comp424group");
define("EMAIL_PASSWORDRESET_SUBJECT", "Password reset for comp424group");
define("EMAIL_PASSWORDRESET_CONTENT", "Please click on this link to reset your password:");

define("EMAIL_VERIFICATION_URL", "http://localhost:8888/COMP424/verification.php");
define("EMAIL_VERIFICATION_FROM", "comp424group@gmail.com");
define("EMAIL_VERIFICATION_FROM_NAME", "comp424group");
define("EMAIL_VERIFICATION_SUBJECT", "Account activation for comp424group");
define("EMAIL_VERIFICATION_CONTENT", "Please click on this link to activate your account: ");


define("HASH_COST_FACTOR", "10");