<?php
namespace libs\database;
class DatabaseConnection{		
		//privilige user for the database
		protected static $database_user;
		protected static $database_pass;
	
		//set the database to use
		protected static $database_name; 
				
		//database database_host location
		protected static $database_host;
		
		public function __construct(){
            try{
			     $this->connect();		
            }catch(Exception $e){
                echo "Database is not connected";
            }
		}
		
		public function connect(){		
            try{
				mysql_connect(self::$database_host, self::$database_user, self::$database_pass);
                mysql_select_db(self::$database_name);
			}catch(Exception $e){
				echo $e->getMessage();	
			}
		}
		
		public function set_Database($db){
			self::$database_name = $db;
            try{
			     $this->connect();		
            }catch(Exception $e){
                echo "Database is not connected";
            }
		}
        
        public static function set_Connection($user = "root", $password = "", $database = "", $database_host = "localhost"){            
            self::$database_host = $database_host;
			self::$database_name = $database;
			self::$database_pass = $password;
			self::$database_user = $user;
        }
	}
?>