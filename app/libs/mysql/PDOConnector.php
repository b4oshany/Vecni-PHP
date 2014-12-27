<?php
namespace libs\mysql;
require_once "MysqlAutoloader.php";
class PDOConnector{
    //privilige user for the database
    public static $database_user;
    protected static $database_pass;

    //set the database to use
    protected static $database_name;

    //database database_host location
    protected static $database_host;

    protected static $is_connected = false;

    public static $db;

    public static function connect(){
        self::has_credentials();
        if(self::$is_connected)
            return true;
        try{
            self::$db = new \PDO("mysql:host=".self::$database_host.";dbname=".self::$database_name,
                    self::$database_user,
                    self::$database_pass);
            self::$is_connected = true;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public static function set_Connection($user = "root", $password = "", $database = "", $database_host = "localhost"){
        self::$database_host = $database_host;
        self::$database_name = $database;
        self::$database_pass = $password;
        self::$database_user = $user;
    }

    public static function has_credentials(){
        if(empty(self::$database_user) or empty(self::$database_name) or empty(self::$database_host)){
            echo "No database connection was provided";
            die();
        }
    }
    

    /**
    * Build update query.
    *
    * @param string $table Table name.
    * @param array $updates Associated array of updates, wher the key is the column name and the value is the data.
    * @param string $condition Condition to append to the update.
    * @param mixed $object Class or object to base the table column on.
    * @param string|bool SQL update string if successful, else false.
    */
    public static function build_update_query($table, $updates, $condition=1, $object=false){
        $temp = array();
        if($object){
            foreach($updates as $key => $value){
                if(property_exists($object, $key)){
                    $value_format = self::format($value);
                    array_push($temp, "$key=$value_format");
                }
            }
        }else{
            foreach($updates as $key => $value){
                array_push($temp, "$key=$value");
            }            
        }
        if(!empty($temp)){
            $updates = implode(",",$temp);
            return "update $table set $updates where $condition";
        }
        return false;
    }
    
    /**
    * Ensures that the mysql table value to the correct format.
    * This will convert the data to the correct format, where applicable.
    *
    * @var mixed $table_data Mysql table data.
    * @return mixed Correct format of the mysql table data.
    */
    public static function format($table_data){
        return (!is_numeric($table_data) || !is_bool($table_data))? "'$table_data'": $table_data;
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
                    $col_key = $id_col[$key];     //the value in the array based on the key
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
}

?>
