<?php
      class Options extends DB{
            public function get_options( $args = [] ) {
                  $default = [
                        'option_name'  => '',
                        'option_value' => '',
                        'option_id'    => ''
                  ];

                  $default = array_merge($default,$args);
                  $condition = '';
                  $params    = [];
                  $query     = "SELECT * FROM wp_options";

                  foreach ($default as $key => $value) {
                        if(! empty ($value) ) {
                              $condition .= " $key = :$key AND ";
                              $params[":$key"] = $value;
                        }
                  }

                  $condition = rtrim($condition,' AND ');
                  
                  if(!empty($condition)) {
                        $query .= " WHERE $condition ";
                  }

                  $options = $this->query($query,$params)->fetch_data();
                  return $options;
            }

            public function add_option( string $option_name,string $option_value,string $autoload = 'yes' ) 
            {
                  $query  = 'INSERT INTO wp_options(`option_name`,`option_value`,`autoload`) VALUES(:n,:v,:a)';
                  $params = [
                        ':n' => $option_name,
                        ':v' => $option_value,
                        ':a' => $autoload,
                  ];
                  $result = $this->query($query,$params);
                  return $result;
            }

            public function update_option( string $option_name,string $option_value )
            {
                  $id    = $this->get_option($option_name,'option_id');

                  return $this->update_data([
                        'table'      => 'wp_options',
                        'update_by'  => 'option_id',
                        'update_val' => $id,
                        'columns'    => ['option_value'],
                        'values'     => [$option_value],
                  ]);
            }

            public function get_option( string $option_name,string $get = 'option_value' ) {
                  $option = $this->get_options(['option_name' => $option_name]);
                  return array_shift($option)[$get] ?? throw new Exception("'$option_name' is not available");
            }

            public function has_option ( string $option_name ) {
                  $option = $this->get_options(['option_name' => $option_name]);
                  return count ($option) > 0;
            }
            public function delete_option( string $option_name,int $option_id ) {
                  $query  = 'DELETE FROM wp_options WHERE option_id = :option_id';
                  $params = [
                        ':option_id' => $option_id,
                  ];
                  $result = $this->query($query,$params);
                  return true;
            }
      }
      global $option;
      $option = new Options();