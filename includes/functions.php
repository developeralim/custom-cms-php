<?php
      
      include_once __DIR__.'/db.php';
      require_once __DIR__.'/post.php';
      require_once __DIR__.'/taxonomy.php';
      require_once __DIR__.'/term.php';
      require_once __DIR__.'/options.php';
      require_once __DIR__.'/route.php';
      require_once __DIR__.'/route-template.php';
      require_once __DIR__.'/view.php';
      require_once __DIR__.'/nav-walker.php';

      function is_page( array $args = [] ) 
      {
            global $post;
            return (count(
                  $post->get_posts ($args)
            ) > 0);
      }
      function is_blog_page ( array $args = [] ) 
      {
            global $post;
            $page = $post->get_posts ($args)[0]['post_name'] ?? '';
            return get_option('page_for_posts') === $page ? true : false;
      }
      function is_post ( array $args = [] ) 
      {
            global $post;
            return (count( $post->get_posts ($args) ) > 0);
      }
    
      function is_taxonomy( string | int $tax,string | int $term ) 
      {
            global $taxonomy;
            $tasex = $taxonomy->get_taxonomies([
                  'name' => $tax,
                  'term' => $term,
            ]);
            return count ( $tasex ) > 0;
      }
      function get_option ( string $opt, string $get = 'option_value' ) 
      {
            global $option;
            return $option->get_option((string) $opt, (string) $get);
      }
      function has_option ( string $opt ) 
      {
            global $option;
            return $option->has_option((string) $opt);
      }
      function add_option ( string $opt_name,string $opt_value,string $autoload = 'yes' ) 
      {
            global $option;
            return $option->add_option((string) $opt_name, (string) $opt_value, (string) $autoload);
      }
      function update_option ( string $opt_name,string $opt_value) 
      {
            global $option;
            return $option->update_option((string) $opt_name, (string) $opt_value);
      }
      function delete_option ( string $opt_name,string $opt_id) 
      {
            global $option;
            return $option->delete_option((string) $opt_name, (string) $opt_id);
      }
      function taxonomy_exist( string $tax ) 
      {
            global $db;
            $taxes = $db->query("SELECT * FROM wp_term_taxonomy WHERE taxonomy = :tax",[':tax' => $tax])->fetch_data();
            return ! empty ($taxes);
      }
      function register_post_type( string $post_type,array $args ) 
      {
            global $nav_walker;
            return $nav_walker->register_post_type( $post_type,$args );
      }
      function register_taxonomy( string $tax,string $objet_type,array $args ) 
      {
            global $nav_walker;
            return $nav_walker->register_taxonomy( $tax,$objet_type,$args );
      }
      function register_page( string $page,array $args = [] ) 
      {
            global $nav_walker;
            $nav_walker->register_page($page,$args);
            return $nav_walker;
      }
      function get_posts(array $args = []) {
            global $post;
            return $post->get_posts($args);
      }
      function __( $toshow ) 
      {
            echo '<pre>';
            print_r($toshow);
            echo '</pre>';
      }