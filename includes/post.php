<?php
      class Post extends DB{
            
            // get post
            public function get_posts ( $args = [] ) {
                  $default = [
                        'numberposts'    => 500,
                        'orderby'        => 'post_date',
                        'order'          => 'DESC',
                        'post_type'      => 'all', 
                        'include'        => [],
                        'exclude'        => [],
                        'meta_key'       => '',
                        'meta_value'     => '',
                        'post_status'    => 'publish',
                        'offset'         => 0,
                        'tax_query'      => [],
                        'search'         => '',
                  ];
                 
                  $default = array_merge ( $default,$args );
                  extract( $default );
                  $query = "SELECT * FROM wp_posts WHERE wp_posts.post_status = :s";
                  $param = [
                        ':s' => $post_status,
                  ];

                  if ( $post_type !== 'all' ) {
                        $query      .= " AND wp_posts.post_type = :t ";
                        $param[':t'] = $post_type;
                  } 

                 // $query .= " ORDER BY post_{$default['orderby']} {$default['order']} LIMIT {$default['offset']},{$default['numberposts']} ";
                 
                  $result = $this->query( $query,$param )->fetch_data();                  

                  foreach ($result as $key => $value) {
                        $result[$key] = $this->parse_date ($value['post_date'],'Asia/dhaka',$result[$key]);
                  }
                  /**
                   * filter post by post meta key and post meta value
                  */
                  if ( ! empty( $meta_key ) && ! empty ( $meta_value ) ) {
                        $callback = function ( $item ) use( $meta_key,$meta_value ) {
                              $meta = $this->get_post_meta ( $item['ID'] );
                              
                              foreach ( $meta as $m ) {
                                    return ( $m['meta_key'] === $meta_key && $m['meta_value'] === $meta_value );
                              }

                        };
                        $result = array_filter ( $result,$callback );
                        unset ( $callback );
                  }
                  /*
                        filter post by include
                  */
                  if ( count ( $include ) !== 0 ) {
                        
                        $callback = function ( $item ) use( $include ) {
                              return ( count ( array_diff_assoc ( $include,$item ) ) === 0 );
                        };
                        $result = array_filter ( $result,$callback );
                        unset ( $callback );
                  }
                  /**
                   * filter post by exclude
                  */
                  if ( count ( $exclude ) !== 0 ) {
                        $callback = function ( $item ) use( $exclude ) {
                              return ( count ( array_diff_assoc ( $exclude,$item ) ) !== 0 );
                        };
                        $result = array_filter ( $result,$callback );
                        unset ( $callback );
                  }
                  /*
                        filter post by tax query
                  */
                  if ( count ( $tax_query ) > 0 ) {
                        $tax  = [];
                        $term = [];

                        foreach ($tax_query as $tax_key => $tax_value) {
                              $tax[]  = $tax_key;
                              $term[] = $tax_value;
                        }

                        if ( ! empty ( $tax ) && ! empty ( $term ) ) {
                              $callback = function ( $item ) use( $tax,$term ) {
                                    return $this->has_post_term ( (int) $item['ID'],(array) $tax,(array) $term );
                              };
                              $result = array_filter ( $result,$callback );
                              unset ( $callback );
                        }
                  }
                  // search posts
                  if ( ! empty ( $search ) ) {
                        $callback = function ( $item ) use( $search ) {
                              return ( 
                                    preg_match ( "#$search#i",$item['post_title'] )                   ||
                                    preg_match ( "#$search#i",$item['post_name'] )                    ||
                                    preg_match ( "#$search#i",strip_tags ( $item['post_content'] ) )
                              );
                        };
                        $result = array_filter ( $result,$callback );
                        unset ( $callback );
                  }
                  // filter post as per limit
                  if ( ! empty ( $numberposts ) ) {
                        $result = array_slice ( $result,$offset,$numberposts );
                  }
                 
                  // filter posts as per order
                  if ( ! empty ( $orderby ) && ! empty ( $order )) {
                        $order = $order == 'ASC' ? SORT_ASC : SORT_DESC;
                        array_multisort(array_column($result,$orderby),$order,SORT_NATURAL | SORT_FLAG_CASE,$result);
                  }

                  return array_values($result);
            }
            // get post meta
            public function get_post_meta (int $post_id ) {
                  $metas = $this->query ( 'SELECT * FROM wp_postmeta WHERE post_id = :post_id',[':post_id' => $post_id] );
                  return $metas;
            }
            /**
             * parse post data 
            */
            public function parse_date ($date_string,$timezone = 'Asia/dhaka',&$post) {
                  // work with post date
                  $date = new DateTime($date_string);
                  $date->setTimeZone(new DateTimeZone($timezone));
                  #set post date
                  $post['post_date'] = $date->format("Y/m/d");
                  #set post date time
                  $post['post_datetime'] = $date->format("Y/m/d h:i:s A");
                  #set post year
                  $post['post_year'] = $date->format("Y");
                  #set post month
                  $post['post_month'] = $date->format("m");
                  #set post day
                  $post['post_day'] = $date->format("d");
                  #set post time
                  $post['post_time'] = $date->format("h:i:s A");
                  #set post time
                  $post['post_hour'] = $date->format("h");
                  #set post minute
                  $post['post_minute'] = $date->format("i");
                  #set post second
                  $post['post_second'] = $date->format("s");
                  #set post AM/PM
                  $post['post_state'] = $date->format("A");
                  #set post day string
                  $post['post_day_string'] = $date->format("l");
                  #set post day string
                  $post['post_month_string'] = $date->format("F");
                  #set post month year
                  $post['post_month_year'] = sprintf ( "%s %u",$date->format( "F" ),$date->format( "Y" ) );
                  return $post;
            }

            public function is_post_taxonomy ( int $post_id,string $taxonomy ) {
                  $query = "SELECT * FROM wp_term_relationships RIGHT JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id WHERE object_id = :id";

                  $result = $this->query($query,[':id' => $post_id])->fetch_data();

                  foreach ($result as $value) {
                        if ( $value['taxonomy'] === $taxonomy ) {
                              return true;
                        }
                  }
                  return false;
            }

            public function is_post_term ( int $post_id,string $term ) {
                  $query = "SELECT * FROM wp_term_relationships RIGHT JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id RIGHT JOIN wp_terms ON wp_term_taxonomy.term_id = wp_terms.term_id  WHERE object_id = :id";

                  $result = $this->query($query,[':id' => $post_id])->fetch_data();

                  foreach ($result as $value) {
                        if ( $value['slug'] === $term ) {
                              return true;
                        }
                  }
                  return false;
            }

            public function has_post_term ( int $post_id,string | array $taxonomy,string | array $term ) {

                  $query = "SELECT * FROM wp_term_relationships RIGHT JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id RIGHT JOIN wp_terms ON wp_term_taxonomy.term_id = wp_terms.term_id  WHERE object_id = :id";

                  $result = $this->query($query,[':id' => $post_id])->fetch_data();

                  $exist = [];

                  foreach ($result as $value) {
                        if ( is_array ( $taxonomy ) && is_array ( $term )) {
                              $exist[$value['slug']] = $value['taxonomy'];
                        } else {
                              if ( $value['taxonomy'] === $taxonomy && $value['slug'] === $term ) {
                                    return true;
                              }
                        }
                  }
                  if ( is_array ( $taxonomy ) && is_array ( $term ) ) {
                        return count ( array_diff_assoc( array_flip ( array_combine($taxonomy,$term) ),$exist) ) === 0;
                  } else {
                        return false;
                  }
            }
      }

      global $post;
      $post = new Post();
