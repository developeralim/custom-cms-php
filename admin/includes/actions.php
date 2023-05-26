<?php
      class PostAction {
            public $default_action = [
                 'publish'   => [
                       'edit'  => 'Edit',
                       'trash' => 'Move To Trash',
                  ],
                 'private'   => [
                       'edit'  => 'Edit',
                       'trash' => 'Move To Trash',
                  ],
                 'draft'   => [
                       'edit'    => 'Edit',
                       'trash'   => 'Move To Trash',
                  ],
                 'pending'   => [
                       'edit'  => 'Edit',
                       'trash' => 'Move To Trash',
                  ],
                 'trash'   => [
                       'restore' => 'Restore',
                       'delete'  => 'Delte parmanently',
                  ],
            ];
            public $show_items = [1,2,3,5,10,20,30,100,'all'];
            public $sort_opt  = [
                  'post_date'  => 'Date',
                  'post_title' => 'Title',
                  'post_name'  => 'Name',
            ];
            public $order = ['DESC','ASC'];
            public $filtering = [
                  'category' => [
                        'post_type'      => 'post',
                  ],
                  'post_tag' => [
                        'post_type'      => 'post',
                  ],
            ];

            public function get_actions ( string $post_status ) {
                  $opt = null;
                  foreach ($this->default_action[$post_status] as $key => $value) {
                        $opt .= sprintf ( '<option value="%s">%s</option>',$key,$value );
                  }
                  printf ('
                        <select name="action" id="action">
                              <option value="">Bulk Action</option>
                              %s
                        </select>
                        <button type="submit" class="sm-btn">Action</button>
                  ',$opt);
            }

            function get_show_options ( $limit ) {
                  $opt = null;
                  foreach ($this->show_items as $value) {
                        $selected = ($limit == $value) ? 'selected' : '';
                        $opt .= sprintf('<option %s value="%s">%s</option>',$selected,$value,$value);
                  }
                  printf ('
                        <label for="show">Show :</label>
                        <select name="limit" id="show" class="c-action">%s</select>
                  ',$opt);
            }
            function get_sort_options ( string $post_type,$orderby ) {
                  $opt = null;
                  foreach ($this->sort_opt as $key => $value) {
                        $selected = ( $orderby == $key ) ? 'selected' : '';
                        $opt     .= sprintf ('<option %s value="%s">%s %s</option>',$selected,$key,ucfirst($post_type),$value);
                  }
                  printf ('
                        <label for="orderby">Sort-by :</label>
                        <select name="orderby" id="orderby" class="c-action">%s</select>
                  ',$opt);
            }
            function get_order_options ( string $order ) {
                  $opt = null;
                  foreach ($this->order as $value) {
                        $selected = ( $order == $value ) ? 'selected' : ''; 
                        $opt     .= sprintf('<option %s vlaue="%s">%s</option>',$selected,$value,$value);
                  }
                  printf('
                        <label for="order">Sort-order :</label>
                        <select name="order" id="order" class="c-action">%s</select>
                  ',$opt);
            }
            public function get_filters ( $post_type,$current_filters ) {
                 
                  $opt_html = null;
                  
                  foreach ($this->filtering as $key => $value) {
                        if ( $value['post_type'] !== $post_type ) continue;
                        $options    = function_exists("{$key}_options") ? call_user_func("{$key}_options",$post_type) : [];
                        $opt_string = null;
                        
                        foreach ($options as $option_key => $option_value) {
                              $opt_value = ($option_key === 0) ? '' : $option_value;
                              $selected = ! empty($current_filters) && $current_filters[$key] == $opt_value ? 'selected' : '';
                              $opt_string .= <<<TEXT
                                    <option $selected value="$opt_value">$option_value</option>
                              TEXT;
                        }

                        $opt_html .= <<<TEXT
                              <select name="$key" id="$key">
                                    $opt_string
                              </select>
                        TEXT;
                  }

                  printf ( "%s<button type='submit' class='sm-btn'>Filter</button>",$opt_html );
            }
            public function get_pagination ( array $args ) {

                  extract ( $args );
                  // set post_type
                  $_GET['post_type'] = $post_type;

                  $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]".strtok($_SERVER["REQUEST_URI"],'?');

                  $prev_class = null;
                  $next_class = null;

                  if ( $page == $pages ) {
                        $next_class = 'disabled';
                  }

                  if ( $page == 1 ) {
                        $prev_class = 'disabled';
                  }

                  $pagination = '
                        <ul class="pagi">
                              <li>
                                    <a class="'.$prev_class.'" href="'.$url.'?'.$this->minus($url,$_GET,$page,$pages,true).'">
                                          <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                                    </a>
                              </li>
                              <li>
                                    <a class="'.$prev_class.'" href="'.$url.'?'.$this->minus($url,$_GET,$page,$pages).'">
                                          <i class="fa fa-angle-left" aria-hidden="true"></i>
                                    </a>
                              </li>
                              <li>
                                    <a href="">'.$page.' of '.$pages.'</a>
                              </li>
                              <li>
                                    <a class="'.$next_class.'" href="'.$url.'?'.$this->plus($url,$_GET,$page,$pages).'">
                                          <i class="fa fa-angle-right" aria-hidden="true"></i>
                                    </a>
                              </li>
                              <li>
                                    <a class="'.$next_class.'" href="'.$url.'?'.$this->plus($url,$_GET,$page,$pages,true).'">
                                          <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                                    </a>
                              </li>
                        </ul>
                  ';
                  echo $pagination;
            }

            private function plus( $url,$query,$page,$total_page,$final = false )
            {
                  $query['page'] = $final === true ? $total_page : ($page + 1);
                  return http_build_query($query);
            }
            private function minus ( $url,$query,$page,$total_page,$final = false ) 
            {
                  $query['page'] = $final === true ? 1 : ($page - 1);
                  return http_build_query($query);
            }

            function get_posts_status_lists ( string $post_type = 'post' ) {
                  global $db;
                  $posts = $db->query(
                        'SELECT * FROM wp_posts WHERE post_type = :post_type AND post_status != :post_status',
                        [
                              ':post_type'   => $post_type,
                              ':post_status' => 'auto-draft',
                        ]
                  )->fetch_data();
                  
                  $status = [];

                  foreach ($posts as $_post) {
                       $status[] = $_post['post_status'];
                  }
                  $status = array_reverse(array_unique ( $status ));
                  $status_list = "<ul>";
                  foreach ($status as $_status) {
                        $for_count_post = get_posts ([
                              'post_type'   => $post_type,
                              'post_status' => $_status,
                        ]);
                        $status_list .= "<li><a href='?post_type=$post_type&post_status=$_status'>".ucfirst($_status)." (".count($for_count_post).")</a></li>";
                  }
                  $status_list .= "</ul>";

                  echo $status_list;
            }
      }
      $post_action = new PostAction();









