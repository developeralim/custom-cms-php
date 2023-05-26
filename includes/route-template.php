<?php

      class RouteTemplate {

            protected $permalink;
            protected $permalink_struct;
            public $post;
            public $taxonomy;
            public $term;

            public function __construct ( ) {
                  $this->permalink = get_option('permalink_type');
                  $this->permalink_struct = [

                        'post-name' => [
                              0 => 'post_type',
                              1 => 'post_name'
                        ],
                        'plain' => [
                              0 => 'post_type',
                              1 => 'ID'
                        ],
                        'numeric' => [
                              0 => 'post_type',
                              1 => 'ID'
                        ],
                        'day-name' => [
                              0 => 'post_type',
                              1 => 'post_year',
                              2 => 'post_month',
                              3 => 'post_day',
                              4 => 'post_name',
                        ],
                        'month-name' => [
                              0 => 'post_type',
                              1 => 'post_year',
                              2 => 'post_month',
                              3 => 'post_name',
                        ],
                        'custom'   => $this->permalink == 'custom' ? unserialize(get_option('permalink_custom_struct')) : '',
                  ];

                  $this->post     = new Post();
                  $this->taxonomy = new Taxonomy();
                  $this->term     = new Term();
            }
            public function parse_request_uri ( ) 
            {
                  if ( preg_match ( '/^\?[a-zA-Z0-9]+=/i',get_request_uri() ) ) {
                        $request_uri = preg_replace ( '/^\?/','',get_request_uri ( ) );
                        $request_uri = explode ( '=', $request_uri );
                  } else {
                        $request_uri = array_filter( explode('/',get_request_uri()), fn ($item) => !empty($item) );
                        $request_uri = array_values( $request_uri );
                  }
                  if ( $this->permalink === 'plain' ) {
                        if ( count($_GET) === 0 && count($request_uri) === 2 ) {
                              list($tax) = $request_uri;
                              if( taxonomy_exist($tax) ) return $request_uri;
                        }
                        if ( count ( $_GET ) !== 0 ) return $request_uri;
                        return [''];
                  }
                  return $request_uri;
            }

            public function get_route() {
                  if(in_array('search',$this->parse_request_uri())){
                        return '/search';
                  }

                  $count = (int) count($this->parse_request_uri());
                  if($count == 0){
                        return '/';
                  }

                  if($count === 2){
                        list($taxonomy,$term) = $this->parse_request_uri();
                        if(is_taxonomy((string) $taxonomy,(string) $term)){
                              return '/archive';
                        }
                  }

                  return $this->make_route(permalinkType:$this->permalink);

            }

            public function make_route(string $permalinkType) : mixed {

                  $peramlink = $this->permalink_struct[$permalinkType];
                  $count_URL_parameter = count($peramlink) <=> count($this->parse_request_uri());

                  if($count_URL_parameter !== 0){
                        http_response_code(404);
                        return '/error';
                  }

                  $include = array_combine($peramlink,$this->parse_request_uri());
                  $args = [
                        'post_type' => 'page',
                        'include'   => $include,
                        'exclude'   => [
                              'post_name' => get_option('page_for_posts'),
                        ]
                  ];
                  /* check for page */
                  if (is_page ( $args )){
                        return '/page';
                  }
                  /* check for blog page */
                  unset($args['exclude']);
                  if (is_blog_page($args)){
                        return '/blog';
                  }
                  /* check for post */
                  $args['post_type'] = 'post';
                  if (is_post( $args )){
                       return '/post';
                  }
                  /* check for error page */
                  http_response_code(404);
                  return '/error';
            }

      }