<?php
      if( ! file_exists( __DIR__.'/config.php' ) ) {
            include_once __DIR__.'/setup.php';    
      } else {
            include_once __DIR__.'/config.php';
            include_once __DIR__.'/includes/application.php';
      }



      
