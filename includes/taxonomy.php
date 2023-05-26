<?php
      class Taxonomy extends DB{

            public function get_taxonomies( $args = [] ) {
                  $default = [
                        'name' => 'category',
                        'term' => 'uncategorized',
                  ];
                  $default = array_merge( $default,$args );
                  extract ($default);

                  $query = "SELECT * FROM wp_term_taxonomy ";

                  if ( ! empty ($term) ) {
                        $query .= " LEFT JOIN wp_terms on wp_term_taxonomy.term_id = wp_terms.term_id ";
                  }

                  $query .= " WHERE wp_term_taxonomy.taxonomy = :tax  AND wp_terms.slug = :term";

                  $result = $this->query($query,[':tax' => $name,':term' => $term])->fetch_data();

                  return $result;
            }
      }
      global $taxonomy;
      $taxonomy = new Taxonomy();