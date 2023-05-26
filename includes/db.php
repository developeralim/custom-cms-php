<?php
      class DB { 
            public $server   = SERVER;
            public $username = USER_NAME;
            public $password = PASSWORD;
            public $database = DATABASE;
            protected $stmt;
            protected $conn;
            public function __construct( ) {
                  try {
                        $this->conn = new PDO("mysql:host=$this->server;dbname=$this->database",$this->username,$this->password);
                        $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                  } catch ( Exception $e ) {
                       echo $e->getMessage();
                  }
            }

            public function query ( $query,$data=[] ) 
            {
                  $stmt = $this->conn->prepare ( $query );
                  $stmt->execute($data);
                  $this->stmt = $stmt;
                  return $this;
            }

            public function fetch_data() {
                  return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            public function inserted_id() {
                  return $this->conn->lastInsertId();
            }

            public function execute( $query,$data=[] )
            {
                  return $this->query( $query,$data );
            }

            public function add_data( array $args = [] ) {
                  $default = [
                        'table'   => '',
                        'values'  => [],
                        'columns' => [],
                  ];
                  $default = array_merge($default,$args);

                  extract ($default);

                  // if table is not set
                  if ( empty($table) || empty($columns) || empty($table)) throw new Exception("Table or column or value is not set");
                  // if values number is not matched with coulumns number
                  if ( ! empty($values) && (count($values) !== count($columns)) ) throw new Exception('Columns number and values number are not the same');
                  
                  $placeholder = '';
                  $params      = [];
                  $column      = rtrim(implode(',',$columns),',');

                  foreach ($columns as $key => $c) {
                       $params[":$c"] = $values[$key];
                       $placeholder  .= ':'.$c.',';
                  }
       
                  $placeholder = rtrim($placeholder,',');
                  $query       = "INSERT INTO {$table}({$column}) VALUES({$placeholder})";

                  return $this->query($query,$params);

            }
            public function update_data(array $args = []) {
                  $default = [
                        'table'      => '',
                        'update_by'  => 'id',
                        'update_val' => '',
                        'columns'    => [],
                        'values'     => [],
                  ];
                  $default = array_merge($default,$args);
                  extract($default);

                  if( empty($table) || empty($update_by) || empty($update_val) ) throw new Exception('Something is not set');
                  if ( ! empty($values) && (count($values) !== count($columns)) ) throw new Exception('Columns number and values number are not the same');
                  
                  $placeholder = '';
                  $params      = [];

                  foreach ($columns as $key => $c) {
                        $placeholder .= sprintf("%s = :%s,",$c,$c);
                        $params[":$c"] = $values[$key];
                  }

                  $placeholder            = rtrim($placeholder,',');
                  $params[":$update_by"]  = $update_val;

                  $query = "UPDATE {$table} SET {$placeholder} WHERE {$update_by} = :{$update_by}";
        
                  return $this->query($query,$params);
            }     
      }

      global $db;
      $db = new DB( );