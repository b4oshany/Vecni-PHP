<?php
namespace configs;

class Vency{
    /*
    *	Section 1.1 Comapny Information
    *	The following lines below consisit of the company detailed information which is used across this website
    */

    # name of the website
    public static $website_name = 'MyBook';
    # company location
    public static $company_address = 'Kingston, Jamaica';
    # company contact number
    public static $company_number = '(876) 8295969';
    # company email address
    public static $company_email = 'info@feroinc.com';
    
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
    
    private $vars = array();
    
    private static $app_route = array(
            "tattletale" => "welcome",
            "/"=>"welcome",
            "public_html"=>"welcome"    
        );
    
        
    public static function run_config(){
        self::$main_dir = dirname(dirname(dirname(__FILE__)));        
        self::$main_dirname = basename(self::$main_dir );        
        self::$template_dir = self::$main_dirname.DIRECTORY_SEPARATOR.self::$template_dir;
        self::$static_dir = self::$main_dirname.DIRECTORY_SEPARATOR.self::$static_dir;
        self::$css_dir = self::$static_dir.DIRECTORY_SEPARATOR.self::$css_dir;
        self::$routes_file = self::$main_dir.DIRECTORY_SEPARATOR.self::$routes_file;
    }

    /*
    * Section 2.0 Host Configuration
    * The following lines below defines the host specification and configuration that should be adjust based on the server which this site is being hosted on	
    */

    # To setup a server settings, you can make a copy of the default.server.settings.php file located in the same directory as this (etc/config/). Afterwards, you need to uncomment the server settings file in Section 2.1 and place the location of the file in the required filed
    public static function startDatabase(){
        # Section 1 Server Setting
        # change the file location for server setting file which you like to use for the current hosting configuration
        require_once "settings.ini.php";      
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
                $error_page();   
            }
        }else{
            require_once self::$routes_file;
            try{
                self::$current_route = $app_route;
                $app_route();
            }catch(Exception $e){
                $error_page();
            }   
        }
    }
    
    public static function set_route($url, $app_route){
        self::$app_route[$url] = $app_route;      
    }
    
    public static function ok(){
        echo "ok";
    }
}

Vency::run_config();

?>