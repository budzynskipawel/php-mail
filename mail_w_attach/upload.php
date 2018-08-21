<?php
if(!isset($_POST['submit']))
{
	//This page should not be accessed directly. Need to submit the form.
	echo "error; you need to submit the form!";
}
$name = $_POST['name'];
$visitor_email = $_POST['email'];
$message = $_POST['message'];

//Validate first
if(empty($name)||empty($visitor_email))
{
    echo "Name and email are mandatory!";
    exit;
}

if(IsInjected($visitor_email))
{
    echo "Bad email value!";
    exit;
}

//$email_from = $visitor_email;//<== update the email address
$email_from = "bok@oltom.pl";
$email_subject = "New Form submission";
$email_body = "You have received a new message from the user $name.\nEmail: $visitor_email \n".
    "Here is the message:\n $message" .


$to = "budzynski.pawel@gmail.com";//<== update the email address
$headers = "From: $email_from \r\n";
$headers .= "Reply-To: $visitor_email \r\n";



$confirm_subject = "Your email to OLTOM was sent.";
$confirm_body = "You have received a new message from the user $name.\nEmail: $visitor_email \n".
    "Here is the message:\n $message".


$confirm_headers = "From: $email_from \r\n";
$confirm_headers .= "Reply-To: $visitor_email \r\n";


// $confirm_body = "You have sent a new message.\n".
// "Here is the message:\n $message" . "Notification was sent automatically, please, don't answer to this email, we will answer as soon as possible."\n . "Have a nice day!".





//Send the email!
mail($to,$email_subject,$email_body,$headers);
// mail($visitor_email,$email_subject,$email_body,$headers);
mail($visitor_email, $confirm_subject, $confirm_body, $confirm_headers);

//done. redirect to thank-you page.
header('Location: thank-you.html');


// Function to validate against any email injection attempts
function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}

?>
