<?php
      date_default_timezone_set("Asia/dhaka");
      
      if(isset($_POST['file_upload']) && $_POST['file_upload'] == true){

            foreach ($_FILES as $file) {

                  $filename  = $file['name'];
                  $tmpname   = $file['tmp_name'];
                  $directory = $_POST['upload_dir'].'/';
                  // replace '\' with '/' in directory string
                  $replace_dir = str_replace('\\','/',$directory);
                  $image_uri = str_replace($_SERVER['DOCUMENT_ROOT'],$_SERVER['HTTP_ORIGIN'],$replace_dir);
                  /* make a directory */
                  $date = date('m-d-Y');
                  if(!is_dir($directory.$date)){
                        mkdir($directory.$date);
                  }
                  // move upload file to upload directory
                  if(move_uploaded_file($tmpname,$directory."$date/$filename")){
                        echo json_encode([
                              'upload'     => true,
                              'upload_url' => $image_uri."$date/$filename",
                        ]);
                  }else{
                        echo json_encode([
                              'upload'     => false,
                        ]);
                  }
            }

      }

      if(isset($_POST['edited'])){
            $filename = $_POST['file_name'];
            $filedate = $_POST['file_date'];
            $tmp_name = $_FILES['file']['tmp_name'];

            /* scan directory  */
            $dir = realpath('./media');
            $scandir = scandir($dir);

            if(!in_array($filedate,$scandir)){
                  mkdir($dir."/$filedate");
            }
            if(move_uploaded_file($tmp_name,$dir."/$filedate/$filename")){
                  echo json_encode([
                        'status' => 'Success',
                        'message' => 'File has been saved successfully',
                  ]);
            }else{
                  echo json_encode([
                        'status' => 'Failed',
                        'message' => 'Failed to save edited file',
                  ]);
            }
      }

      /* delete file */
      if(isset($_POST['delete_file'])){
            $dir = realpath($_POST['dir']);
            $file = $_POST['file_name'];
            if(file_exists($dir."/$file")){
                  if(unlink($dir."/$file")) rmdir($dir);
            }
      }

      function indexOf($search,$string){
            $arr = str_split($string);
            $index = -1;
            for ($i=0; $i < count($arr); $i++) { 
                  if($arr[$i] == $search){
                        $index = $i;
                        break;
                  }
            }
            return $index;
      }
      function lastIndexOf($search,$string){
            $arr = str_split($string);
            $index = -1;
            for ($i = 0; $i < count($arr); $i++) { 
                  if($arr[$i] == $search){
                        $index = $i;
                  }
            }
            return $index;
      }