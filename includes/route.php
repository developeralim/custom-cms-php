<?php
      // register route
      class Route {

            public array $routes = [];

            public function get ( string $route, callable | array $callback ) : static
            {
                  $this->routes['get'][$route] = $callback;
                  return $this;
            }
            public function post ( string $route, callable | array $callback ) : static
            {
                  $this->routes['post'][$route] = $callback;
                  return $this;
            }
            public function run ( string $method,string $currentRoute ) : void
            {     
                  $method   = strtolower( $method );
                  $callback = $this->routes[$method][$currentRoute] ?? throw new Exception ( 'Routes Not Found' );

                  if( is_callable ( $callback ) ) {
                        call_user_func( $callback );
                  }

                  if( is_array ( $callback ) ) {

                        list($class,$method) = $callback;
                        $class               = new $class();
                        call_user_func_array([$class,$method],[]);
                  }
                  return;
            }

      }

      