<?php
namespace libs\vecni;
require_once "__autoload.php";
require_once "Response.php";

class Vecni{
    /*
    *    Section 1.1 Comapny Information
    *    The following lines below consisit of the company detailed information which is used across this website
    */
    # name of the website
    public static $BRAND_NAME = 'Vecni';
    # company location
    public static $company_address = 'Kingston, Jamaica';
    # company contact number
    public static $company_number = '(876) 8295969';
    # company email address
    public static $company_email = 'b4.oshany@gmail.com';
    public static $company_name = 'Osoobe';


    # absolute path of the application folder
    public static $main_dir = "";
    # name of the application folder
    public static $main_dirname = "";
    # currently viewed route of the user
    public static $current_route = "";

    # main directories of the application
    public static $template_dir;
    public static $static_dir;
    public static $libs_dir;
    public static $css_dir;
    public static $routes_file;
    public static $mdb;
    public static $host;
    public static $protocol;

    private $vars = array();
    private static $app_route = array();
    public static $display_error = false;


    public static function run_config(){
        # get the root folder of the application
        # absolute address
        self::$main_dir = dirname(dirname(dirname(dirname(__FILE__))));
        # relative address
        self::$main_dirname = basename(self::$main_dir );

        # set the default subfolders of the application
        self::$template_dir = self::$main_dirname.DIRECTORY_SEPARATOR."templates";
        self::$static_dir = self::$main_dirname.DIRECTORY_SEPARATOR."static";
        self::$css_dir = self::$static_dir.DIRECTORY_SEPARATOR."css";
        self::$routes_file = self::$main_dir.DIRECTORY_SEPARATOR."main.ini.php";
        self::$libs_dir = self::$main_dir.DIRECTORY_SEPARATOR."etc".DIRECTORY_SEPARATOR."libs";

        self::get_host();
    }


    public static function get_host(){
        self::$host = $_SERVER["SERVER_NAME"];
        self::$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
        if(self::$host == "localhost"){
            self::$host = self::$protocol.self::$host.DIRECTORY_SEPARATOR.self::$main_dirname;
        }else{
            self::$host = self::$protocol.self::$host;
        }
    }

    public static function in_development(){
        if($_SERVER["SERVER_NAME"] == "localhost"){
            return true;
        }
        return false;
    }

    public static function enable_error_reporting(){
        if(self::in_development() && self::$display_error){
            error_reporting(E_ALL);
            ini_set('display_errors',1);
            require_once(self::$libs_dir.DIRECTORY_SEPARATOR.'error'.DIRECTORY_SEPARATOR.
                         'src'.DIRECTORY_SEPARATOR.'php_error.php');
            \php_error\reportErrors();
        }
        ini_set('html_errors',1);
    }

    public static function twig_loader(){
        require_once self::$libs_dir.DIRECTORY_SEPARATOR
            ."twig".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR
            ."Twig".DIRECTORY_SEPARATOR."Autoloader.php";

        # register autloading package twig
        \Twig_Autoloader::register();
        # get the templates folder and prepare template rendering
        $loader = new \Twig_Loader_Filesystem(self::$main_dir.DIRECTORY_SEPARATOR."templates");
        $twig = new \Twig_Environment($loader, array(
            'debug' => true,));
        $twig->addExtension(new \Twig_Extension_Debug());
        return $twig;
    }

    public static function less_loader(){
        require_once self::$libs_dir.DIRECTORY_SEPARATOR
            ."less".DIRECTORY_SEPARATOR."lessc.inc.php";
        return new \lessc;
    }

    public static function email_loader(){
        require_once self::$libs_dir.DIRECTORY_SEPARATOR."mailer".DIRECTORY_SEPARATOR."PHPMailerAutoload.php";
        $mailer = new \PHPMailer;
        $mailer->From = self::$company_email;
        $mailer->isHTML(true);
        $mailer->WordWrap = 70;
        return $mailer;
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
        /**
        * Get the url dispatching function to be used to render the view or data.
        *
        * @param string|null $app_route, url for the dispatching function to be used if
            string, else assume it's the home page.
        */
        if($app_route == null){
            krsort(self::$app_route);
            $path = $_SERVER['REQUEST_URI'];
            if(self::$host."/" != self::$protocol.$_SERVER["SERVER_NAME"].$path){
                unset(self::$app_route["/"]);
            }
            // Search for the respective url dispatching function.
            foreach(self::$app_route as $url => $app_route){
                $url_regex = str_replace('/', '\/', $url);
                $url_regexpr = preg_replace("/\{.+?\}/", "\w+", $url_regex);
                $result = preg_match("/".$url_regexpr.'\/?\??.*$'."/", $path);
                // If an unique id is found and has a unique variable id,
                // set it to a GET request.
                if($result){
                    preg_match("/".$url_regexpr."/", $path, $matches);
                    $data = explode("/", $matches[0]);
                    $args = explode("/", $url);
                    foreach($args as $key => $arg){
                        if(strpos($arg, "{") !== false){
                            $_GET[substr($arg, 1, -1)] = $data[$key];
                        }
                    }
                    echo $app_route();
                    return;
                }
            }
            echo self::error_route();
            Response::abort();
        }else{
            require_once self::$routes_file;
            try{
                self::$current_route = $app_route;
                echo $app_route();
            }catch(Exception $e){
                echo self::get_route();
            }
        }
    }

    public static function set_error_route($fn_name){
        self::$app_route["error404"] = $fn_name;
    }

    public static function error_route(){
        if(isset(self::$app_route["error404"])){
            $app_route = self::$app_route["error404"];
            return $app_route();
        }else{
            return "404. Not Found";
        }
    }

    public static function index_route(){
        return "It's working.............";
    }

    public static function set_route($url, $app_route){
        self::$app_route[$url] = $app_route;
    }

    public static function get_path(){
        $path = $_SERVER['REQUEST_URI'];
        $len =  strlen(self::$main_dirname);
        $pos = strripos($path, self::$main_dirname);
        if($pos <= $len){
            return substr($path, ($len+1));
        }
        return substr($path, 1);
    }

    public static function get_configs(){
        return array(
            "BRAND_NAME"=>self::$BRAND_NAME,
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

    public static function redirect($url = "welcome"){
        ?>
            <script>
                window.location.assign("<?php echo self::$host."/".$url ?>");
            </script>
        <?php
    }

    public static function url_for($url_fn){
        try{
            $url = array_search($url_fn, self::$app_route, true);
            if(!empty($url)){
                return;
            }
        }catch(Exception $e){
            return;
        }

    }
}

Vecni::run_config();
Vecni::enable_error_reporting();

?>
