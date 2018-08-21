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
    print_r($uploaded_files_list);
    // echo "<script>window.location = 'http://www.wp.pl'</script>";
      // print_r($uploaded);
}

if(!empty($failed)){
    echo('An error occured!');
    print_r($failed);
}
} 