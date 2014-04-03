<?php
    class Database{		
		//privilige user for the database
		protected static $database_user;
		protected static $database_pass;
	
		//set the database to use
		protected static $database_name; 
				
		//database database_host location
		protected static $database_host;
		
		public function __construct(){
			$this->connect();		
		}
		
		public function connect(){		
            try{
				if(!mysql_connect(self::$database_host, self::$database_user, self::$database_pass)){
					throw new Exception("\nError connecting to database host", 1);
				}else{
					if(!mysql_select_db(self::$database_name)){
						throw new Exception("\nError connecting to database ".self::$database_name, 1);						
					}
				}
			}catch(Exception $e){
				echo $e->getMessage();	
			}
		}
		
		public function set_Database($db){
			self::$database_name = $db;
			$this->connect();
		}
        
        public static function set_Connection($user = "root", $password = "", $database = "", $database_host = "localhost"){            
            self::$database_host = $database_host;
			self::$database_name = $database;
			self::$database_pass = $password;
			self::$database_user = $user;
        }
	}
	
    

	class Db_Functions extends Database{			
		public function query($sql, $print_sql=0){
			try{
				if($print_sql == 1){
					echo $sql;	
				}
				$run_query = mysql_query($sql);
				//Checking if the query ran successfully, if not then throw an exception
				if($run_query){
					return $run_query;
				}else{					
					throw new Exception("\nError in sql query CODE:x01f019", 1);
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}		
		}
		
		
		public static function build_sql_update($cols, $id_col, $id, $sql, $udata){
			//for each data in the user data array, collect the key for the table name and set the value as the cell value
			$first = true;
			foreach($udata as $key => $value){
				//check if the preset column name is present in the mapping of the preset columns to db columns
				if(array_key_exists($key, $cols)){
					//if the preset column is present then build the sql from the matching preset columns - db columns
					//get the db column from the column mapping
					//append the db column to the column variable
					//get the value from the udata to for each column and build the sql file with the specific value type
					//build the update sql with both key and value pair
					if($first){
						$col_key = $cols[$key];	 //the value in the array based on the key
						$sql .= " $col_key = ".Db_Functions::returnType($value);
						$first = false;
					}else{
						$col_key = $cols[$key];
						$sql .= ", $col_key = ".Db_Functions::returnType($value);	
					}
				}
			}
			$sql .= " where $id_col = '$id'"; //set the condition as in where the data should be inputed	
			return $sql;
		}
		
		//build an insert or update on duplicate function
		public static function build_sql_insert_or_update($id_col, $sql, $udata){
			$first = true;	 
			$values = $col = $update = ''; 
			//print_r($id_col);
			//echo '<br>';
			//print_r($udata);
			//echo '<br>';
			//get the pre-set column name and its value out of the udata
			foreach($udata as $key => $value){
				//check if the preset column name is present in the mapping of the preset columns to db columns
				if(array_key_exists($key, $id_col)){
					//if the preset column is present then build the sql from the matching preset columns - db columns
					//get the db column from the column mapping
					//append the db column to the column variable
					//get the value from the udata to for each column and build the sql file with the specific value type
					//build the update sql with both key and value pair
					if($first){
						$col_key = $id_col[$key];	 //the value in the array based on the key
						$col = $col_key." " ; 
						$values = " ".Db_Functions::returnType($value); 
						$update = " $col_key = ".Db_Functions::returnType($value);
						$first = false;
					}else{
						$col_key = $id_col[$key];
						$col = $col.",$col_key"; 	
						$values = $values.','.Db_Functions::returnType($value); 
						$update = $update.", $col_key = ".Db_Functions::returnType($value);	
					}
				}
			}
			if($col != ''){
				//set the condition as in where the data should be inputed	
				$sql .= "($col) values($values) on duplicate key update $update"; 
				return $sql;
			}else{
				echo $sql;
				return 'err';
			}		
		}
		
		public static function returnType($var){
			if(!is_numeric($var)){
				$var = "'$var'";
			}
			return $var;
		}	
		
		public function recordExist($sql){
			return ($result = $this->fetch_query_results($sql, 'numbered'))? ($result[0][0] != 0)? 1:0:0;	
		}
		
		//The function below is used to place table data into an array for easier extraction and display
		public function fetch_query_results($sql, $type = 'associated'){
			$count = 0;
			$data = array(); 
			$query = $this->query($sql);
			//Traverse through each row and place them into an associate array
			if(mysql_num_rows($query) > 0){
				if($type == 'associated'){
					while($row = mysql_fetch_assoc($query)) {
						$data[$count] = $row;			
						$count++;			
					}
				}else{
					while($row = mysql_fetch_array($query)) {
						$data[$count] = $row;			
						$count++;			
					}
				}
			// Afterwards return the associate array for display or any other use
			return $data;
			}else{
				return 0;
			}
		}	
	}
?>