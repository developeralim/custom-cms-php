<?php
      // background remove api key
      $backgroundRemoveAPI = [
            /*'developeralimcu.01@gmail.com' => */'f6HxdS8kMU5e763oFMg1c3Yi',
            /*'developeralimcu.02@gmail.com' => */'q6xDPVUwCbozixmb779TRo7n',
            /*'developeralimcu.03@gmail.com' => */'Wu4hC69qpE7GpAirsoQC9vro',
            /*'developeralimcu.04@gmail.com' => */'WDgCwdVMzPkjcV4TW1GNvX5h',
            /*'developeralimcu.05@gmail.com' => */'MjY8XqbBh8xEfhpQYDRXGXJQ',
            /*'developeralimcu.06@gmail.com' => */'HXTjHpzq5QbiwmGsov3qcn3R',
            /*'developeralimcu.07@gmail.com' => */'RzFQtjBy4croqac4HPfWH8vk',
            /*'developeralimcu.08@gmail.com' => */'HgtNwff4RCpcw6wFhPvBwKCV',
            /*'developeralimcu.09@gmail.com' => */'sCvHBRNAGoU5sHH2r8CwuEWs',
            /*'developeralimcu.10@gmail.com' => */'j13oD59oZPxQMbG9sT2wAPDD',
            /*'developeralimcu.11@gmail.com' => */'2oaXDe3jbGrNdYCsxUEZ8BaH',
            /*'developeralimcu.12@gmail.com' => */'23VR5gKWW9nnaFCiJnzdpQgV',
            /*'developeralimcu.13@gmail.com' => */'6CCQ8CLAQXxVgTjfBfRojg1o',
            /*'developeralimcu.14@gmail.com' => */'m3yfHr94Gt9esj5jMGmBhrH3',
            /*'developeralimcu.15@gmail.com' => */'5SqRUe8ZJRxThkSFJv4otG8M',
            /*'developeralimcu.16@gmail.com' => */'iotooUyDwFc6UYdpp8wSZx1Y',
            /*'developeralimcu.17@gmail.com' => */'SPab75iELvNLYYMpw7RBYJt3',
            /*'developeralimcu.18@gmail.com' => */'pq7bf7MMaWQpsLf5rnuALKaD',
            /*'developeralimcu.19@gmail.com' => */'2ihNj5QMBBQbkfrvM5o2TRep',
            /*'developeralimcu.20@gmail.com' => */'heYM2PdURjajxnY4pTkNK1F5',
            /*'developeralimcu.21@gmail.com' => */'ttNopDsm7fKkmEZN53Sdgd4N',
            /*'developeralimcu.22@gmail.com' => */'QmZ6Sg3agPKmpPUSMcXwgVeZ',
            /*'developeralimcu.23@gmail.com' => */'MX2XX4WMKR8XvxQgh2j6nfAy',
            /*'developeralimcu.24@gmail.com' => '',*/
            /*'developeralimcu.25@gmail.com' => '',*/
            /*'developeralimcu.26@gmail.com' => '',*/
            /*'developeralimcu.27@gmail.com' => '',*/
            /*'developeralimcu.28@gmail.com' => '',*/
            /*'developeralimcu.29@gmail.com' => '',*/
            /*'developeralimcu.30@gmail.com' => '',*/
            /*'developeralimcu@gmail.com'    => */'dT8wmvtfs6WkiDU94wL6t8LA',
            /*'mdalimkhancu@gmail.com'       => */'YWYMFxanAM61Jj2t49kJ54Ct',
            /*'mdalimkhancu794@gmail.com'    => */'K9f4diP4DLkyDYUVcfURDkzb',
            /*'khanmdalim899@gmail.com'      => */'VEBmHFrtr25TCmUeZt27CENS',
            /*'developerrakibcu@gmail.com'   => */'3SeGMVXBwWMF6stdLB67RALr'
      ];    

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
                              'fileinfo'   => pathinfo($filename), 
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

      if(isset($_POST['removebg'])){
           
            $file_content = file_get_contents($_POST['image_url']);
            $background_type = $_POST['backgroundType'];
            $background = $_POST['background'];

            shuffle($backgroundRemoveAPI);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.remove.bg/v1.0/removebg');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            $post = array(
                  'image_file_b64' => base64_encode($file_content),
            );
            $format = 'jpg';
            if($background_type == 'color') {
                  if($background == '#FFFFFF00') {
                        $format = 'png';
                  }
                  $post['bg_color'] = $background;

            }else{
                  $post['bg_image_url'] = $background;
            }
            $post['format'] = $format;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            $headers = array();
            $headers[] = 'X-Api-Key:'.$backgroundRemoveAPI[0].'';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if(curl_errno($ch) === 0){
                  echo json_encode([
                        'status'  => 'success',
                        'image'   => base64_encode($result),
                        'code'    => 200,
                        'message' => 'Background Removed successfully',
                        'backgroundtype' => $background_type,
                        'background' => $background,
                        'format' => $format,
                  ]);
            }else{
                  echo json_encode([
                        'status'  => 'failed',
                        'image'   => base64_encode($result),
                        'code'    => curl_errno($ch),
                        'message' => 'May be your offline.'.curl_error($ch).'. Please try again later',
                  ]);
            }
            curl_close($ch);

      }

      if(isset($_FILES['files'])){
            
            $file_name        = $_POST['file_name'];
            $file_mime        = $_POST['mime_type'];
            $file_ext         = $_POST['file_ext'];
            $file_content     = file_get_contents($_FILES['files']['tmp_name']);
            $path             = realpath("assets/tmp-uploads/");
            $time             = time();
            $file             = "$path/$file_name-$time.$file_ext";
            $response         = [
                  'status'  => '',
                  'code'    => '',
                  'error'   => '',
                  'message' => 'Successfully optimized your image',
            ];

            //create a new file//
            file_put_contents($file,$file_content);

            //if file type is png the send to resmush.it through api to optimize size//
            if($file_ext == 'png'){
                  $data = optimize($file);
                  if( $data['status'] == 'success' && $data['code'] == 200 ) {
                        file_put_contents($file,file_get_contents($data['data']['dest']));
                  }
                  $response = array_merge($response,$data);
            }

            //check if file exist then send as response back to the client//
            if(file_exists($file)){
                  $p = str_replace('\\','/',$file);
                  $url = str_replace($_SERVER['DOCUMENT_ROOT'],$_SERVER['HTTP_ORIGIN'],$p);

                  $response['dest_url']  = $url;
                  $response['dest_size'] = sizeFormat(filesize($file));
                  
                  echo json_encode($response);
            }

      }
      function optimize($file_path = NULL, $is_original = TRUE) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://api.resmush.it/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);

		if (!class_exists('CURLFile')) {
			$arg = array('files' => '@' . $file_path);
		} else {
			$cfile = new CURLFile($file_path);
			$arg = array(
			  'files' => $cfile,
			);
		}

		$arg['exif'] = 'true';

		curl_setopt($ch, CURLOPT_POSTFIELDS, $arg);

		$data = curl_exec($ch);
            if(curl_errno($ch) == 0) {
                  return [
                        'status' => 'success',
                        'code'   => '200',
                        'error'  => 'no',
                        'data'   => json_decode($data,true),
                        'message' => 'Successfully optimized your image',
                  ];
            }else{
                  return [
                        'status' => 'failed',
                        'code'   => curl_errno($ch),
                        'error'  => curl_error($ch),
                        'data'   =>  '',
                        'message' => 'May be your offline.'.curl_error($ch).'. Please try again later',
                  ];
            }
		curl_close($ch);


	}
      function sizeFormat($bytes) {
            if ($bytes > 0)
            {
                $unit = intval(log($bytes, 1024));
                $units = array('B', 'KB', 'MB', 'GB');
  
                if (array_key_exists($unit, $units) === true)
                {
                    return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
                }
            }
            return $bytes;
      }
      delete_uploaded_image(realpath('assets/tmp-uploads'));
      delete_uploaded_image(realpath('assets/uploads'));
      function delete_uploaded_image($path){
            $files = scandir($path);
            foreach ($files as $key => $file) {
                  $file = "$path/$file";
                  if(is_file($file)){
      
                        if( time() - filectime( $file ) >= (60 * 60 * 0.5) )
                              @unlink($file);
      
                  }elseif(is_dir($file)){
                        if( $key == 0 || $key == 1 ) continue;

                        $files = scandir( $file );

                        foreach ($files as $img_file) {
                              $img_file = "$file/$img_file";
                              if( time() - filectime( $img_file ) >= (60 * 60 * 0.5) )
                                    @unlink($img_file);
                        }

                        if(count(glob("$file/*")) === 0) {
                              rmdir($file);
                              continue;
                        }
                  }
            }
      }
      if(isset($_FILES['source'])) {
            echo get_file_live_url(base64_encode(file_get_contents($_FILES['source']['tmp_name'])));
      }
      function get_file_live_url( $base64 ) {
      
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,'https://freeimage.host/api/1/upload?key=6d207e02198a847aa98d0a2a901485a5');
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,[
                  'source' => $base64,
            ]);
            $result = curl_exec($ch);

            return json_decode($result,true)['image']['url'];
      }
      