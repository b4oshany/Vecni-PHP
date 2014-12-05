<?php
namespace libs\vecni;
require_once "Object.php";


function staticCall($class, $function, $args = array()){
    if (class_exists($class) && method_exists($class, $function))
        return call_user_func_array(array($class, $function), $args);
    return null;
}

class Vecni extends Object
{
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

    # currently viewed route of the user
    private static $current_route = "";
    
    private static $paths = array(
        "core"=>"",
        "index"=>"",
        "static"=>array(
            "relative"=>"",
            "absolute"=>""
        ),
        "libs"=>"",
        "plugins"=>"",
        "configs"=>"",
        "hostname"=>"",
        "host"=>""
    );

    public static $routes_file;
    public static $mdb;
    
    public static $host;

    public static $twig;

    private $vars = array();
    private static $app_route = array();

    public static function init($file)
    {
        self::$paths["hostname"] = $_SERVER["SERVER_NAME"];
        self::$paths["host"] = dirname($_SERVER["SCRIPT_NAME"]);
        
        # get the root folder of the application
        # absolute address
        self::$paths["core"] = dirname($file);
        # relative address
        self::$paths["index_file"] = basename($file);

        # set the default subfolders of the application
        self::$paths["static"] = "app".DIRECTORY_SEPARATOR."static";

        self::$paths["route_file"] = self::$paths["core"].DIRECTORY_SEPARATOR."main.ini.php";

        self::$paths["templates"] = self::$paths["core"].DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR;
        self::$paths["libraries"] = self::$paths["core"].DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."libs".DIRECTORY_SEPARATOR;
        self::$paths["plugins"] = self::$paths["core"].DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR;
        self::$paths["controllers"] = self::$paths["core"].DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."controller".DIRECTORY_SEPARATOR;
        self::$paths["configs"] = self::$paths["core"].DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."configs".DIRECTORY_SEPARATOR;

        include_once self::$paths["configs"]."settings.ini.php";
        self::twig_loader();
    }
    
    public static function getPluginsFolder(){
        return self::$paths["plugins"];
    }
    
    public static function getTemplatesFolder(){
        return self::$paths["templates"];
    }
    
    public static function getLibsFolder(){
        return self::$paths["libraries"];
    }
    
    public static function getConfigsFolder(){
        return self::$paths["configs"];
    }
    
    public static function getIndexFile(){
        return self::$paths["index"];
    }
    
    public static function getRootFolder(){
        return self::$paths["core"];
    }
    
    public static function getStaticFolder($relative_path=true){
        return (($relative_path)? self::$paths["host"]."/" : self::$paths["core"].DIRECTORY_SEPARATOR).self::$paths["static"];
    }

    public static function in_development(){
        if($_SERVER["SERVER_NAME"] == "localhost"){
            return true;
        }
        return false;
    }

    public static function get_submodules(){
        ?>
            <h3>Please run the following command in git bash.</h3>
            <code> git submodule update --init</code>
        <?php
        http\Response::abort("In order for you to continue.");
    }

    public static function enable_error_reporting($display_error = true, $override_default=false){
        if((self::in_development() && $display_error) || ($display_error && $override_default)){
            error_reporting(E_ALL);
            ini_set('display_errors',1);
            $php_error_file = self::$paths["plugins"].'error'.DIRECTORY_SEPARATOR.
                             'src'.DIRECTORY_SEPARATOR.'php_error.php';
            if(file_exists($php_error_file)){
                require_once $php_error_file;
                \php_error\reportErrors();
            }else{
                self::get_submodules();
            }
            ini_set('html_errors',1);
        }else{
            ini_set('display_errors',0);
        }
    }

    public static function twig_loader(){
        $twig_autoload = self::$paths["plugins"]
                ."twig".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR
                ."Twig".DIRECTORY_SEPARATOR."Autoloader.php";

        if(file_exists($twig_autoload)){
            require_once $twig_autoload;

            # register autloading package twig
            \Twig_Autoloader::register();
            # get the templates folder and prepare template rendering
            $loader = new \Twig_Loader_Filesystem(self::$paths["templates"]);
            self::$twig = new \Twig_Environment($loader, array(
                'debug' => true,));
            self::$twig->addExtension(new \Twig_Extension_Debug());
            # added global variables to twig
            self::$twig->addGlobal("config", self::get_configs());
            self::$twig->addGlobal("host", self::$paths["host"]);
            self::$twig->addGlobal("static", self::getStaticFolder());

            #allow call to static functions.
            self::$twig->addFunction('staticCall', new \Twig_Function_Function('staticCall'));
        }else{
            self::get_submodules();
        }
    }

    public static function use_less(){
        $less_file = self::$paths["plugins"]
                ."less".DIRECTORY_SEPARATOR."lessc.inc.php";
        if(file_exists($less_file)){
            require_once $less_file;
            $less = new \lessc;
            # compile css less files
            $static = self::getStaticFolder(false);
            $css_file = $static
                                  .DIRECTORY_SEPARATOR."gen"
                                  .DIRECTORY_SEPARATOR."css"
                                  .DIRECTORY_SEPARATOR."style.css";
            $less_file = $static
                                  .DIRECTORY_SEPARATOR."src"
                                  .DIRECTORY_SEPARATOR."css"
                                  .DIRECTORY_SEPARATOR."less"
                                  .DIRECTORY_SEPARATOR."style.less";
            $style = $less->compileFile($less_file);
            if(file_exists($css_file)){
                unlink($css_file);
            }
            $fp = fopen($css_file, 'w');
            fwrite($fp, $style);
            fclose($fp);
            chmod($css_file, 0777);
            return $less;
        }else{
            self::get_submodules();
        }
    }

    public static function email_loader(){
        $php_mailer = self::$paths["plugins"]."mailer".DIRECTORY_SEPARATOR."PHPMailerAutoload.php";
        if(file_exists($php_mailer)){
            require_once $php_mailer;
            $mailer = new \PHPMailer;
            $mailer->From = self::$company_email;
            $mailer->isHTML(true);
            $mailer->WordWrap = 70;
            return $mailer;
        }else{
            self::get_submodules();
        }
    }

    public function render($template_file) {
        try{
            include self::$paths["templates"].$template_file;
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
            $path = urldecode($_SERVER['REQUEST_URI']);
            if(self::$paths["host"]."/" != $path){
                unset(self::$app_route["/"]);
            }
            // Search for the respective url dispatching function.
            foreach(self::$app_route as $url => $app_route){
                $url_regex = str_replace('/', '\/', $url);
                $url_regexpr = preg_replace("/\{.+?\}/", ".+", $url_regex);
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
            http\Response::abort();
        }else{
            require_once self::$paths["route_file"];
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
        $len =  strlen(self::$paths["core"]);
        $pos = strripos($path, self::$paths["core"]);
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

    public static function redirect($url = "/home", $async=false, $title=""){
        $url = self::$paths["host"].$url;
        if(!$async){
        ?>
            <script>
                window.location.assign("<?php echo $url; ?>");
            </script>
        <?php
        }else{
            $data = array("page"=>$title);
        ?>
            <script>
                history.replaceState(<?php echo json_encode($data).", \"$title\", \"$url\""; ?>);
                document.title = <?php echo "'$title'"; ?>;
            </script>
        <?php
        }
    }


    public static function reload(){
        ?>
        <script>
            window.location.reload();
        </script>
        <?php
     }

    public static function nav_back(){
        ?>
        <script>
            window.history.back();
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
?>
