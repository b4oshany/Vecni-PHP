<?php
namespace libs\mysql;
require_once "MysqlAutoloader.php";
class PDOConnector{
    //privilige user for the database
    protected static $database_user;
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
                    $col_key = $cols[$key];     //the value in the array based on the key
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
