<?php
      class View {
            public function index( ) 
            {
                  echo View::render('index');
            }
            public function frontPage( ) 
            {
                  echo View::render('front-page');
            }
            public function search( ) 
            {
                  echo View::render('search');
            }
            public function _404( ) 
            {
                  echo View::render('404');
            }
            public function archive( ) 
            {
                  echo View::render('archive');
            }
            public function page( ) 
            {
                  echo View::render('page');
            }
            public function single( ) 
            {
                  echo View::render('single');
            }

            // render page 
            private static function render(string $view){
                  $viwePath      = ROOTDIR.'/contents/themes/mytheme/'.$view.'.php';
                  $themeFunction = ROOTDIR.'/contents/themes/mytheme/functions.php';
                  $coreFunction  = ROOTDIR.'/includes/functions.php';
                  $adminFunction  = ROOTDIR.'/admin/functions.php';
                  ob_start();
                  
                  // add core function at the top of all pages
                  file_exists($coreFunction) ? include_once $coreFunction : null;
                  file_exists($adminFunction) ? include_once $adminFunction : null;
                  file_exists($themeFunction) ? include_once $themeFunction : null;
                  file_exists($viwePath) ? include_once $viwePath : null;
                  return ob_get_clean();
            }
      }