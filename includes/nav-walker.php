<?php 
      class NavWalker {
            public $site_url;
            public $pages;
            public $subpage;
            public $post_types = [];

            public function __construct( ) {
                  $this->site_url = get_site_url();
                  $this->pages = [
                        'Dashboard' => [
                              'menu_icon'   => 'fa fa-dashboard',
                              'path'        => sprintf('%sadmin/index.php',$this->site_url),
                        ],
                        'Media' => [
                              'menu_icon'   => 'fas fa-camera',
                              'path'        => sprintf('%sadmin/media.php',$this->site_url),
                        ],
                        'Comments' => [
                              'menu_icon'   => 'fas fa-message',
                              'path'        => sprintf('%sadmin/comments.php',$this->site_url),
                        ],
                        'User' => [
                              'menu_icon'   => 'fa fa-user',
                              'path'        => sprintf('%sadmin/user.php',$this->site_url),
                        ],
                        'Customize'         => [
                              'menu_icon'   => 'fas fa-cogs',
                              'path'        => sprintf('%sadmin/layout.php',$this->site_url),
                        ],
                        'Maintenance'       => [
                              'menu_icon'   => 'fa fa-wrench',
                              'path'        => sprintf('%sadmin/general.php',$this->site_url),
                        ],
                  ];
                  $this->subpage = [
                        'Library'    => [
                              'object_page' => 'Media', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/media.php',$this->site_url),
                                    'sub_icon'    => 'fa fa-image',
                              ]
                        ],
                        'Upload'    => [
                              'object_page' => 'Media', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/upload.php',$this->site_url),
                                    'sub_icon'    => 'fa fa-upload',
                              ]
                        ],
                        'All Users'    => [
                              'object_page' => 'User', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/user.php',$this->site_url),
                                    'sub_icon'    => 'fa fa-user',
                              ]
                        ],
                        'Profile'           => [
                              'object_page' => 'User', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/profile.php',$this->site_url),
                                    'sub_icon'    => 'fa fa-home',
                              ]
                        ],
                        'Add new user'      => [
                              'object_page' => 'User', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/user-new.php',$this->site_url),
                                    'sub_icon'    => 'fa fa-user-plus',
                              ]
                        ],
                        'Layout'            => [
                              'object_page' => 'Customize', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/layout.php',$this->site_url),
                                    'sub_icon'    => 'fab fa-themeisle',
                              ]
                        ],
                        'Menu'              => [
                              'object_page' => 'Customize', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/menu.php',$this->site_url),
                                    'sub_icon'    => 'fas fa-bars',
                              ]
                        ],
                        'Header'            => [
                              'object_page' => 'Customize', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/header.php',$this->site_url),
                                    'sub_icon'    => 'far fa-newspaper',
                              ]
                        ],
                        'Footer'            => [
                              'object_page' => 'Customize', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/footer.php',$this->site_url),
                                    'sub_icon'    => 'fas fa-newspaper',
                              ]
                        ],
                        'Custom CSS'        => [
                              'object_page' => 'Customize', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/custom-css.php',$this->site_url),
                                    'sub_icon'    => 'fab fa-css3-alt',
                              ]
                        ],
                        'Custom JS'         => [
                              'object_page' => 'Customize', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/custom-js.php',$this->site_url),
                                    'sub_icon'    => 'fab fa-js',
                              ]
                        ],
                        'General'           => [
                              'object_page' => 'Maintenance', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/general.php',$this->site_url),
                                    'sub_icon'    => 'fas fa-clinic-medical',
                              ]
                        ],
                        'Site Health'       => [
                              'object_page' => 'Maintenance', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/site-health.php',$this->site_url),
                                    'sub_icon'    => 'fas fa-syringe',
                              ]
                        ],
                        'Permalink'         => [
                              'object_page' => 'Maintenance', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/permalink.php',$this->site_url),
                                    'sub_icon'    => 'fas fa-external-link-alt',
                              ]
                        ],
                        'Clener'            => [
                              'object_page' => 'Maintenance', 
                              'args'        => [
                                    'sub_path'    => sprintf('%sadmin/clener.php',$this->site_url),
                                    'sub_icon'    => 'fas fa-broom',
                              ]
                        ],
                  ];
                  // register default post types
                  $this->register_post_type('page',[
                        'menu_icon'   => 'fas fa-file',
                  ]);
                  $this->register_post_type('post',[]);
            }

            public function get_site_nav (  ) {
                  global $post;
                  global $taxonomy;

                  $menu = '';

                  foreach ($this->pages as $name => $args) {
                        extract ( $args );
                        
                        $submenu = '
                              <div class="dropdown">
                                    <ul class="dropdown-nav">
                        ';

                                    foreach ($this->subpage as $page => $sub_args) {
                                          
                                          extract($sub_args);
                                          extract($args);

                                          if (  isset ( $object_page ) && $object_page == $name ) {
                                                $submenu .= sprintf ('
                                                      <li>
                                                            <a href="%s">
                                                                  <i class="%s" aria-hidden="true"></i>
                                                                  <span class="label">%s</span>
                                                            </a>
                                                      </li>',
                                                      $sub_path,
                                                      $sub_icon,
                                                      $page
                                                );     
                                          }
                                    }

                        $submenu .= '
                                    </ul>
                              </div>
                        ';

                        if (! strpos($submenu,'<li>') ) $submenu = '';
                              
                        $menu .= sprintf (
                             '<li class="nav-item">
                                    <a href="%s">
                                          <i class="%s" aria-hidden="true"></i>
                                          <span class="label">%s</span>
                                    </a>
                                    %s
                              </li>',
                              $path,
                              $menu_icon,
                              $name,
                              $submenu
                        );
                  }
                  echo $menu;
            }

            public function register_page( string $page,array $args = [] ) {

                  extract ( $args );
                  $slug_name = $slug_name ?? strtolower ( preg_replace("/-|\W/i",'_',$page) );

                  $default_args = [
                        'menu_icon'  => 'fa fa-map-pin',
                        'path'       => sprintf("%sadmin/admin.php?page=%s",$this->site_url,$slug_name),
                        'sub_page'   => '',
                  ];

                  $default_args = array_merge($default_args,$args);

                  extract (  $default_args );

                  if ( ! empty ($sub_page) ) {

                        if ( is_array ( $sub_page ) ) {

                              foreach ($sub_page as $sub_key => $sub_item) {
                                    // declare some default sub items
                               
                                    $default_sub = [
                                          'sub_icon'  => '',
                                          'sub_name'  => '',
                                    ];
                                    
                                    $default_sub = array_merge ( $default_sub,$sub_item );
                                    
                                    extract ( $default_sub );
                
                                    if ( empty ( $sub_name ) ) $sub_name = $sub_key;
                                    
                                    if ( array_keys(array_slice($sub_page,0,1))[0] === $sub_key ) {
                                          $menu_link = $slug_name;
                                    } else {
                                          $menu_link = $sub_key;
                                    }
                                    
                                    $this->register_sub_page ($sub_name ?? $sub_key,[
                                          'object_page' => $page,
                                          'args'        => [
                                                'sub_path'  => sprintf("%sadmin/admin.php?page=%s",$this->site_url,$menu_link),
                                                'sub_icon'  => $sub_icon,
                                          ]
                                    ]);
                              }
                        }
                  }

                  $this->pages[$page] = array_merge($default_args,$args);

                  return $this;
            }
            private function register_sub_page ( string $title, array $args ) {
                  $this->subpage[$title] = $args;
                  return $this;
            }
            public function register_post_type( string $post_type,array $args ) {
                  $default_args = [
                        'menu_icon'  => 'fa fa-map-pin',
                        'path'       => sprintf("%sadmin/post.php?post_type=%s",$this->site_url,$post_type),
                  ];

                  $this->post_types[$post_type] = array_merge($default_args,$args);

                  //register to sub page with every post type
                  $this->subpage["All $post_type"] = [
                        'object_page' => $post_type,
                        'args'        => [
                              'sub_path'    => sprintf("%sadmin/post.php?post_type=%s",$this->site_url,$post_type),
                              'sub_icon'    => 'fas fa-share'
                        ]
                  ];
                  $this->subpage["Add new $post_type"] = [
                        'object_page' => $post_type,
                        'args'        => [
                              'sub_path'    => sprintf("%sadmin/post-new.php?post_type=%s",$this->site_url,$post_type),
                              'sub_icon'    => 'fas fa-plus',
                        ]
                  ];
                  // check if post type is equal to post
                  if ( $post_type === 'post' ) {
                        $this->subpage["category"] = [
                              'object_page' => $post_type,
                              'args'        => [
                                    'sub_path'    => sprintf("%sadmin/taxonomy.php?taxonomy=category",$this->site_url),
                                    'sub_icon'    => 'fa-solid fa-tag',
                              ]
                        ];
                        $this->subpage["tag"] = [
                              'object_page' => $post_type,
                              'args'        => [
                                    'sub_path'    => sprintf("%sadmin/taxonomy.php?taxonomy=tag",$this->site_url),
                                    'sub_icon'    => 'fa-solid fa-tag',
                              ]
                        ];
                  }

                  // insert element in array specific place
                  $this->pages = array_merge ( 
                        array_slice ( $this->pages,0,2 ),
                        $this->post_types,
                        array_slice ( $this->pages,2 )
                  );

                  return $this;
            }
            public function register_taxonomy(string $label,string $boject_type,array $args) {
                  $default_args = [
                        'sub_path' => sprintf("%sadmin/taxonomy.php?taxonomy=%s",$this->site_url,$label),
                        'sub_icon' => 'fa-solid fa-tag',
                  ];

                  $this->subpage[$label] = [
                        'object_page' => $boject_type,
                        'args'        => array_merge($default_args,$args),
                  ];

                  return $this;
            }
      } 

      $nav_walker = new NavWalker();
      global $nav_walker;