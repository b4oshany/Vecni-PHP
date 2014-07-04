<?php
namespace configs;
require_once "__autoload.php";

class Vecni{
    /*
    *	Section 1.1 Comapny Information
    *	The following lines below consisit of the company detailed information which is used across this website
    */

    # name of the website
    public static $website_name = 'Tattle Tale';
    # company location
    public static $company_address = 'Kingston, Jamaica';
    # company contact number
    public static $company_number = '(876) 8295969';
    # company email address
    public static $company_email = 'info@feroinc.com';
    public static $company_name = 'Fero Inc.';
    
    # absolute path of the application folder
    public static $main_dir = "";    
    # name of the application folder
    public static $main_dirname = "";
    # currently viewed route of the user
    public static $current_route = "";
    
    # main directories of the application
    public static $template_dir = "templates";
    public static $static_dir = "static";
    public static $css_dir = "css";
    public static $routes_file = "main.ini.php";
    
    public static $db = null;
    private static $db_user;
    private static $db_pass;
    private static $db_name;
    private static $db_location;
    
    private $vars = array();    
    private static $app_route = array();
    
        
    public static function run_config(){
        # get the root folder of the application
        # absolute address
        self::$main_dir = dirname(dirname(dirname(__FILE__)));        
        # relative address
        self::$main_dirname = basename(self::$main_dir );
        # set the default page of the application        
        self::set_route("error", "error");
        self::set_route(self::$main_dirname, "welcome");
        self::set_route("", "welcome");
        self::set_route("welcome", "welcome");

        # set the default subfolders of the application
        self::$template_dir = self::$main_dirname.DIRECTORY_SEPARATOR.self::$template_dir;
        self::$static_dir = self::$main_dirname.DIRECTORY_SEPARATOR.self::$static_dir;
        self::$css_dir = self::$static_dir.DIRECTORY_SEPARATOR.self::$css_dir;
        self::$routes_file = self::$main_dir.DIRECTORY_SEPARATOR.self::$routes_file;
        
        self::start_database();
    }

    /*
    * Section 2.0 Host Configuration
    * The following lines below defines the host specification and configuration that should be adjust based on the server which this site is being hosted on	
    */

    # To setup a server settings, you can make a copy of the default.server.settings.php file located in the same directory as this (etc/config/). Afterwards, you need to uncomment the server settings file in Section 2.1 and place the location of the file in the required filed
    public static function start_database(){
        # Section 1 Server Setting
        # change the file location for server setting file which you like to use for the current hosting configuration
        $settings = self::$main_dir.DIRECTORY_SEPARATOR.'etc'
                                  .DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR.'settings.ini.php';
        
        if(file_exists($settings)){
            require_once $settings;      
            self::$db = new \PDO('mysql:host='.self::$db_location.";dbname=".self::$db_name, self::$db_user, self::$db_pass);
        }else{
            if(!file_put_contents($settings, '<?php
/* Developer:			Oshane Bailey
 * Organization:		Osoobe Inc.
 * Description:			Variable definition based on host location
 */
require_once "__autoload.php";
use configs\Vecni;

# Section 2.1.1 Server Settings

# Section 2.1.1.1 Host Location
# By default the host is set to localhost or the IP captured by the server environment
# In order to point this website to a specific location, then change the host location below
$server_location = $_SERVER["HTTP_HOST"];

# Section 2.1.2.1 Mysql Library
# The mysql library consist of database error handling, manipulation and consifiguration
# It is advise to not to mess with this file unless you posses advance experience in PHP object orientated programming and database handling
# The file below is the library of all mysql functions that can be performed on the database


# Section 2.1.2.2 Mysql Settings
# The following lines below defines the database connection settings that will be used across the website

# By default the database location is set to localhost
# To change the default location, change the value of the db_location below
$db_location = "localhost";

# By default the user name and password for the database is set to root and null
# To change this, change the value of the db_user or/and db_name to the appropriate credentials for the databse
$db_user = "root";
$db_pass = "oshany1991";

# Please set the default databse name in db_name variable below
$db_name = "ttale_general";

# Section 2.1.2.3 Databse Connection
# In order for the website to successfully connect to a database all the required fields in Section 2.2.2 Mysql Settings must be valid
# The following lines below uses the values that were set in Section 2.1.2.2 to connect to the database by setting the static variable for the database connection class
Vecni::set_Connection($db_user, $db_pass, $db_name, $db_location, $db_name);

# Section 2.1.3 Sessions
# Start sessions at the begining of the script
session_start();

?>')){
                require_once $settings;
                self::$db = new \PDO("mysql:host=self::$db_location;dbname=self::$db_name", self::$db_user, self::$db_pass);
            }else{
                echo "No DATABASE is set";   
            }
        }
    }

    # Section 2.3 File System
    # This defines the overall structure of the website, that is, it defines the location of the modules, themes, pictures, data, css and javascript files and folders
    # The file below defines the location core folders and files withing the system


    public function render($template_file) {
        try{
            include self::$template_dir.DIRECTORY_SEPARATOR.$template_file;
        }catch(Exception $e){
            print $e;
        }
    }
    
    public function __set($name, $value) {
        $this->vars[$name] = $value;
    }
    
    public function __get($name) {
        return $this->vars[$name];
    }
    
    public static function get_current_route(){
        return self::$current_route;
    }
    
    public static function get_route($app_route=null){
        if($app_route == null){
            $path = basename($_SERVER['REQUEST_URI']);        
            if(array_key_exists($path, self::$app_route)){
                try{
                    $app_route = self::$app_route[$path];
                    self::get_route($app_route);
                }catch(Exception $e){
                    echo "something went wrong";
                }                                  
            }else{
                self::get_route("error");   
            }
        }else{
            require_once self::$routes_file;
            try{
                self::$current_route = $app_route;
                $app_route();
            }catch(Exception $e){
                self::get_route();
            }   
        }
    }
    
    public static function set_route($url, $app_route){
        self::$app_route[$url] = $app_route;      
    }
    
    public static function get_configs(){
        return array(
            "website_name"=>self::$website_name,
            "company_address"=>self::$company_address,
            "company_number"=>self::$company_number,
            "company_email"=>self::$company_email,
            "company_name"=>self::$company_name
        );
    }
    
    public static function set_Connection($db_user, $db_pass, $db_name, $db_location='localhost', $db_name){
        self::$db_location = $db_location;
        self::$db_name = $db_name;
        self::$db_user = $db_user;
        self::$db_pass = $db_pass;
    }
    
    public static function close_database(){
        self::$db = null;
    }
    
}

Vecni::run_config();

?>
