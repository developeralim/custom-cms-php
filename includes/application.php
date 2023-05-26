<?php
      require_once __DIR__.'/functions.php';
      //make the connection global
      function dd($toShow) {
            echo '<pre>';
                  print_r($toShow);
            echo '</pre>';
      }
      $route = new Route( );
      try {
            $route 
                  -> get ( '/', [View::class,'frontPage'] )
                  -> get ( '/post', [View::class,'single'] )
                  -> get ( '/search',[View::class,'search'] )
                  -> get ( '/archive', [View::class,'archive'] )
                  -> get ( '/error', [View::class,'_404'] )
                  -> get ( '/page', [View::class,'page'] )
                  -> get ( '/blog', [View::class,'index'] )
            -> run( $_SERVER['REQUEST_METHOD'],(new RouteTemplate)->get_route());

      } catch (Exception $e) {
            http_response_code(404);
            echo $e->getMessage();
      }
