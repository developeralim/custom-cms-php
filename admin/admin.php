<?php
      require_once __DIR__.'/header.php';
      // check if page is set as url param
      if ( isset ( $_GET['page'] ) && $_GET['page'] !== '' ) {

            $page = str_replace('-','_',$_GET['page']);

            if (  function_exists($page."_content") ) {
                  call_user_func($page."_content");
            }
      }
      require_once __DIR__.'/footer.php';
?>
