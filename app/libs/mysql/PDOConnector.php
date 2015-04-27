<?php
namespace libs\mysql;
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
                $value_format = self::format($value);
                array_push($temp, "$key=$value_format");
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
        return (!is_numeric($table_data) && !is_bool($table_data))? "'$table_data'": $table_data;
    }
    
    /**
    * Import database.
    * @var strign $file Path of the database file.
    * @return boolean True if update was successful, else false.
    */
    public static function import($file){
        if(is_file($file)){
            PDODbImporter::importSQL($file, self::$db);
            return true;
        }
        return false;
    }
}

?>
