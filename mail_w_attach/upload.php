

<?php



function upload(){
    $uploaded_files_list = [];

if(!empty($_FILES['files']['name'][0])){

    $files = $_FILES['files'];

    $uploaded = array();
    $failed = array();
    $allowed = array('doc','xls', 'pdf', 'jpeg', 'tiff', 'jpg');
    
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

                    

                   $uploaded_files_list[$position] = '<a href=' . '"http://z0xow9x6nl.neotek.waw.pl/mail_w_attach/' . $file_destination . '">'. $file_firstpartname . '.' .$file_ext  . '<a/>';
                    $uploaded_files_list[$position];
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
// return $uploaded_files_list ;
if(!empty($uploaded)){
     
    //  return $uploaded_files_list;
    // to delete
    // echo "<script>window.location = 'http://www.wp.pl'</script>";
    $uploaded_files_list = implode(",", $uploaded_files_list);
      return ($uploaded_files_list);
}

if(!empty($failed)){
    echo('An error occured!'); //to delete
    print_r($failed); //to delete
}
} 
};

$attachements = upload();

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

// $uploaded_files_list = echo upload();
//$email_from = $visitor_email;//<== update the email address
$email_from = "bok@oltom.pl";
$email_subject = "Nowa wiadomość od $name";
$email_body = "Otrzymałeś nową wiadomość od $name.\nEmail: $visitor_email \n\n".
    "o treści:\n\n $message\n\n Załączniki: \n\n\n $attachements\n".   

$to = "pmbwebdev@yahoo.com";//<== update the email address
$headers = "From: $email_from \r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= "Reply-To: $visitor_email \r\n";

//Send the email!

mail($to,$email_subject,$email_body,$headers);

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