<?php
      require_once dirname(__DIR__).'/config.php';
      require_once dirname(__DIR__).'/includes/functions.php';
      require_once dirname(__DIR__).'/contents/themes/mytheme/functions.php';
      require_once __DIR__.'/constant.php';

      function register_flash_message ( string $msg,$status = 'success' ) {
            $_SESSION['flash_message'] = sprintf ( 
                  '<div class="flash-message %s">
                        <span>%s</span>
                        <button type="button" onclick="closeFlashMsg(this)">&times;</button>
                  </div>',
                  $status,
                  $msg 
            );
            return true;
      }
      function get_flash_message (  ) {
            if (isset( $_SESSION['flash_message'] )) {
                  echo $_SESSION['flash_message'];
                  unset($_SESSION['flash_message']);
                  return true;
            }
            return false;
      }
      function ip_exist ( string $ip ) {
            global $db;
            return count ( $db->query('SELECT * FROM wp_post_views WHERE ip_address = :ip',[':ip' => $ip] )->fetch_data() ) > 0;
      }

      function is_viewed (string $ip,int $post_id ) {
            global $db;
            $views = $db->query('SELECT * FROM wp_post_views WHERE ip_address = :ip',[':ip' => $ip])->fetch_data();
            if ( count ( $views ) > 0 ) {
                  
                  $views_id = unserialize( array_shift ( $views )['view_count'] );
                  return in_array ( $post_id,$views_id );
            }

            return false;
      }

      function update_view ( string $ip,int $post_id ) {

            global $db;
        
            if ( ip_exist ( $ip )) {

                  if ( !is_viewed ( $ip,$post_id )) {
                        
                        $view     = $db->query ( 'SELECT * FROM wp_post_views WHERE ip_address = :ip',[':ip' => $ip] )->fetch_data();
                        $views_id = unserialize ( array_shift ( $view )['view_count'] );
                        array_push ( $views_id,$post_id );
                        // update views
                        $db->query ( 
                              'UPDATE wp_post_views SET view_count = :view_count WHERE ip_address = :ip',
                              [
                                    ':view_count' => serialize ( $views_id ),
                                    ':ip' => $ip,
                              ] 
                        );

                        return true;
                  }
                 
            } else {
                  // inset views
                  $db->query ( 
                        'INSERT INTO wp_post_views(ip_address,view_count) VALUES (:ip,:view_count)',
                        [
                              ':view_count' => serialize([$post_id]),
                              ':ip'         => $ip,
                        ] 
                  );
                  return true;
            }
      }

      function get_views_count ( int $post_id ) {
            global $db;
            $all_views = $db->query('SELECT * FROM wp_post_views')->fetch_data();
            $all_views = array_filter ( $all_views,function( $view ) use( $post_id ) {
                  return in_array ( $post_id,unserialize ( $view['view_count'] ) );
            });

            return count ( $all_views );
      }
      
      function action_trash ( array $ids ) {
            
      }
      function action_restore ( array $ids ) {

      }
      function action_delete ( array $ids ) {

      }

      function post_date_options( string $post_type ){
            $posts = get_posts ([
                  'post_type'   => $post_type,
            ]);
            $options = ['All Dates'];

            foreach ($posts as $value) {
                  $options[] = $value['post_month_year'];
            }
            return array_values(array_unique( $options ));
      }

      function category_options( $post_type ){

            global $db;

            $query = "SELECT * FROM wp_term_relationships RIGHT JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id RIGHT JOIN wp_terms ON wp_term_taxonomy.term_id = wp_terms.term_id  WHERE wp_term_taxonomy.count > 0 AND wp_term_taxonomy.taxonomy = :taxonomy";

            $result = $db->query($query,[':taxonomy' => 'category'])->fetch_data();
            
            $options = ['All Categories'];

            foreach ($result as $value) {
                  $options[] = $value['slug'];
            }

            return array_values(array_unique( $options ));
      }

      function post_tag_options( $post_type ){

            global $db;

            $query = "SELECT * FROM wp_term_relationships RIGHT JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id RIGHT JOIN wp_terms ON wp_term_taxonomy.term_id = wp_terms.term_id  WHERE wp_term_taxonomy.count > 0 AND wp_term_taxonomy.taxonomy = :taxonomy";

            $result = $db->query($query,[':taxonomy' => 'post_tag'])->fetch_data();
            
            $options = ['All Tags'];

            foreach ($result as $value) {
                  $options[] = $value['slug'];
            }

            return array_values(array_unique( $options ));
      }

      function edit_action_trigger() {
            echo 'hello';
      }