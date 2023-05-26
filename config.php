<?php 
      session_start();
      define( 'ABSPATH',$_SERVER['DOCUMENT_ROOT'] );
      define( 'ROOTDIR',__DIR__);
      define( 'SERVER','localhost' );
      define( 'USER_NAME','root' );
      define( 'PASSWORD','' );
      define( 'DATABASE','portfolio' );

      function get_site_url( ) 
      {
            $URL = str_replace ( $_SERVER['DOCUMENT_ROOT'],$_SERVER['HTTP_HOST'],str_replace('\\','/',ROOTDIR) );
            $URL = sprintf('%s://%s/',$_SERVER['REQUEST_SCHEME'],$URL);
            return strtolower($URL);
      }

      function get_root_directory ( ) 
      {     
            $directory = explode('\\',ROOTDIR);
            return array_pop( $directory );
      }

      function get_request_uri ( ) 
      {
            $request_uri  = $_SERVER['REQUEST_URI'];
            $request_uri  = str_replace(strtolower(get_root_directory()),'',strtolower($request_uri));
            $request_uri  = rtrim($request_uri,'/');
            $request_uri  = ltrim($request_uri,'/');
            return $request_uri;
      }