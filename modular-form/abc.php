<?php
    $docroot= $_SERVER['DOCUMENT_ROOT'];
   //Settings
   $max_allowed_file_size = 5000; // size in KB 
   $allowed_extensions = array("jpg", "jpeg", "gif", "bmp","png");
   $to = "service@goldenstatewheels.com";
   $upload_folder = "$docroot/modform/uploads/"; //<-- this folder must be writeable by the script

   $files = array();


   //================== Formun yollandigindan emin olalım ====================== //


   if(isset($_POST['test']))
   {
      $errors='';


      if(empty($_POST["message"])) {
         $errors.='<p class="error">Message field cannot be empty!</p>';
      }


      // Which file fields uploading?
      $uploded_forms = array();
      foreach($_FILES['file']['name'] as $field_number =>$form_field) {
         if(!empty($form_field)) {
            $name_of_uploaded_file =  basename($form_field);
            $uploded_forms[$name_of_uploaded_file]=$field_number;
         }
      }


      if(sizeof($uploded_forms)==0) {
         $errors.='<p class="error">You did not upload any file!</p>';
      }
      else {
         
      // Message fiel bodmu? //

      // ddss

      $is_files_legal=true;

      foreach($uploded_forms as $name => $number ) {
         $type_of_uploaded_file = substr($name, strrpos($name, '.') + 1);
         $size_of_uploaded_file = $_FILES["file"]["size"][$number]/1024;
         if(!(in_array($type_of_uploaded_file,$allowed_extensions))) {
            $errors.='<p class="error">One or more of the files you selected are not allowed</p>';
            $is_files_legal = false;
            break;
         }
         if($size_of_uploaded_file > $max_allowed_file_size) {
            $errors.='<p class="error">One or more of the files you selected were too large.(>'.$max_allowed_file_size.' KB</p>';
            $is_files_legal = false;
            break;
         }
      }

      // aascacacsc

      $is_all_files_uploaded = true;

      if($is_files_legal) {
         foreach($uploded_forms as $name => $number ) {
            /* copy the temp. uploaded file to uploads folder */
            $path_of_uploaded_file = $upload_folder . $name;
            $tmp_path = $_FILES["file"]["tmp_name"][$number];
            if(is_uploaded_file($tmp_path)) {
               if(!copy($tmp_path,$path_of_uploaded_file)) {
                  $is_all_files_uploaded = false;
                  $errors .= '<p class="error">Error while copying the uploaded file</p>';
               }
               else {
                  array_push($files,"uploads/$name");
               }
            }
         }
      }
      if($is_files_legal and $is_all_files_uploaded and empty($errors)) {

         // email fields: to, from, subject, and so on
         $from = "hokten@gmail.com";
         $subject ="My subject"; 
         $message = $_POST["message"];
         $headers = "From: $from";

         // boundary 
         $semi_rand = md5(time()); 
         $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

         // headers for attachment 
         $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

         // multipart boundary 
         $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
         $message .= "--{$mime_boundary}\n";

         // preparing attachments
         for($x=0;$x<count($files);$x++){
            $file = fopen($files[$x],"rb");
            $data = fread($file,filesize($files[$x]));
            fclose($file);
            $data = chunk_split(base64_encode($data));
            $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" . 
            "Content-Disposition: attachment;\n" . " filename=\"$files[$x]\"\n" . 
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
            $message .= "--{$mime_boundary}\n";
         }

         // send

         $ok = @mail($to, $subject, $message, $headers); 
         if ($ok) { 
            echo "<p class='succesful'>Mail sent to $to!</p>"; 
         } else { 
            echo "<p class='error'>Mail could not be sent!</p>"; 
         } 
      }
   }

      echo $errors;
   }
?>
