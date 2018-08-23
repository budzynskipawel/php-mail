

<?php

if(!empty($_FILES['files']['name'][0])){

    $files = $_FILES['files'];

    $uploaded = array();
    $failed = array();
    $allowed = array('doc','xls', 'pdf', 'jpeg', 'tiff', 'jpg');
    $uploaded_files_list = [];
    foreach($files['name'] as $position => $file_name){

    $file_tmp = $files['tmp_name'][$position];
    $file_size = $files['size'][$position];
    $file_error = $files['error'][$position];

    $file_ext = explode('.', $file_name);
    $file_ext = strtolower(end($file_ext));

    $file_firstpartname = explode('.', $file_name);
    $file_firstpartname = $file_firstpartname[0];
    
   

    if(in_array($file_ext, $allowed)){

        if($file_error === 0){


                if($file_size <= 6291456){

                    $file_name_new = $file_firstpartname . '_' . uniqid('', true) . '.' . $file_ext;
                    $file_destination = 'uploads/' . $file_name_new;

                    

                    $uploaded_files_list[$position] = '<a href=' . '"http://z0xow9x6nl.neotek.waw.pl/test_upload/' . $file_destination . '">'. $file_firstpartname . '.' .$file_ext  . '<a/>';

                    if(move_uploaded_file($file_tmp, $file_destination)){
                        $uploaded[$position] = $file_destination;
                        }else{
                            $failed[$position] = "[{$file_name}] failed to upload";
                        }
                }else{
                    $failed[$position] = "[{$file_name}] is too large.";
                }
        }else{

            $failed[$position] = "[{$file_name}] errored with code {$file_name}";
        }
    }else{
        $failed[$position] = "[{$file_name}] file extension '{$file_ext}'is not allowed.";
    }
}

if(!empty($uploaded)){
    // print_r($uploaded_files_list); // to delete
    // echo "<script>window.location = 'http://www.wp.pl'</script>";
      // print_r($uploaded);
}

if(!empty($failed)){
    echo('An error occured!'); //to delete
    print_r($failed); //to delete
}
} 


//START mailing
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
    "Here is the message:\n $message".
$to = "pmbwebdev@yahoo.com";//<== update the email address
$headers = "From: $email_from \r\n";
$headers .= "Reply-To: $visitor_email \r\n";
$confirm_subject = "Your email to OLTOM was sent.";
$confirm_body = "You have sent a new message.\n".
"Here is the message:\n $message" . 'CIAO!';
//Send the email!
mail($to,$email_subject,$email_body,$headers);
mail($visitor_email, $confirm_subject, $confirm_body);
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