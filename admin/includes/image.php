<?php
      // check for file upload
      if(isset($_POST['file'])){

            $directory = realpath('../media');

            foreach($_FILES as $key => $file):

                  $filename = $file['name'];
                  $tmpname = $file['tmp_name'];

                  // create directroy with today's date
                  date_default_timezone_set('Asia/dhaka');
                  $date = date('d-m-Y');
                  // check if the file is begin edit

                  if($_POST['file'] == 'edit'){
                        $filename = substr($key,11);
                        $lastIndex = -1;
                        for ($i=0; $i < strlen($filename); $i++) { 
                              if($filename[$i] == '_'){
                                    $lastIndex = $i;
                              }
                        }
                        if($lastIndex !== -1){
                              $filename = substr_replace($filename,'.',$lastIndex,1);
                        }
                        $date = substr($key,0,10);
                  }
                  // check if directory exist in media folder
                  $scandir = scandir($directory);

                  if(in_array($date,$scandir)){
                        $directory .= "\\$date\\";
                  }else{
                        //make a directory
                        if(mkdir($directory."\\$date")){
                              $directory .= "\\$date\\";
                        }
                  }

                  if(move_uploaded_file($tmpname,$directory.$filename)){
                        $sanitize_url = str_replace('\\','/',dirname(__DIR__)).'/media.html';
                        $redirect_url = str_replace($_SERVER['DOCUMENT_ROOT'],$_SERVER['HTTP_ORIGIN'],$sanitize_url);
                        echo json_encode([
                              'status'  => 'success',
                              'code'    => '200',
                              'upload' => true,
                              'redirect_url' => $redirect_url,
                              'message' => 'successfully saved image',
                        ]);
                  }else{
                        echo json_encode([
                              'upload' => false,
                              'status' => 'error',
                              'code'   => '401',
                              'message'=> 'Faild to decode image data',
                        ]);
                  }
                  
            endforeach;
      }
