<?php
/**
* Vecni utilizes url dispatching to server files instead of filesystem url resolution.
*
* The aim of Vecni is to allow fast development and separate developers and designers task
* from each other without bombarding them with tones of tools which they may not need.
* In addition, Vecni is about the developers, who want to do most of coding,
* but have unique tools and functions to aid in development. For instance,
* Vecni uses advance namespaces and autoloading tools to access modules
* from anywhere, which is much like python or C++ package management.
* For more information on Vecni {@link http://b4oshany.github.io/vecni/ for Documentation of Vecni PHP}.
*
* @author Oshane Bailey <b4.oshany@gmail.com>
* @version v0.1.0
*/
namespace libs\vecni;
use libs\mysql\PDOConnector;
use libs\scifile\File;
use lessc;
use PHPMailer;
use twig;

ini_set('display_errors',1);
ini_set('html_errors',1);
require_once "libs/Utils.php";


/**
* Manage the dispatching of url as well as application configuration.
* Note: It is recommend to use Vecni namespace as app.
* For example:
* <?php
*   use libs\vecni\Vecni as app;
* ?>
* @version v0.1.0
*/
abstract class Vecni extends Object{
    use vetwig\TwigTrait;

    /** @var string $BRAND_NAME The brand name. */
    public static $BRAND_NAME = 'Vecni';
    /** @var string $company_address Address of the company. */
    public static $company_address = 'Kingston, Jamaica';
    /** @var sting $company_number Company contact number. */
    public static $company_number = '(876) 8295969';
    /** @var string $company_enail Company email address. */
    public static $company_email = 'b4.oshany@gmail.com';
    /** @var string $company_name Name of the company. */
    public static $company_name = 'Osoobe';
    /** @var $paths[] Folders/paths within the application. */
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

    /** Current visited url by the user. */
    private static $current_path;

    /** @var libs\vecni\Object App data; **/
    public static $data;

    /** @var libs\vecni\Object App auth data; **/
    public static $auth;

    /**
    * @var \Twig_Environment $twig Twig Template management
    *   {@link http://twig.sensiolabs.org/ for Twig Documentation}.
    */
    public static $twig;

    /** @var str $host The server root location. */
    public static $host;
    /** @var str $hostname The server host name */
    public static $hostname;

    /**
     * @var string $mode Type of mode the application should be in.var
     * By defualt, the application is in dynamic mode, which means it actual
     * mode will varies depending on the server it is on. Another mode is production
     * , which is used on a live server. The last option is the debug mode, which is used
     * on a development or local server. This will allow developers to view documentations
     * and perform system administration task.
     */
    public static $mode = "dynamic";

    /**
    * @var \php_error\reportErrors $errorHandler PHPError handler.
    * ({@link http://phperror.net/ for PHPError Documentation.})
    */
    private static $errorHandler;

    /** @var $app_route[] User defined urls with their associative dispatching function. */
    private static $app_route = array();
    /** @var int $version Version number. */
    public static $version = 1.0;

    /** @var libs\Cookie $cookie Cookie class. */
    public static $cookie;
    /** @var libs\Session $session Session class. */
    public static $session;
    /** @var http\Request. $request Request class */
    public static $request;
    /** @var http\Response $response Response class */
    public static $response;

    /** @var DateTime $current_date Application current date time. */
    public static $current_date;

    /**
    * Vecni uses a base file to figure out the project tree structure.
    * The base file is usual the index.php or a file which which in the root folder of the project.
    * Nevertheless, Vecni uses the index.php file to initiate the application, since all server request
    * is pass through the index.php, where applicable.
    *
    * @param string $file Base file of the application
    * @uses self::get_settings() to get user defined configuration in the
    *   {@link app/configs/settings.ini.php settings.ini.php} file.
    * @uses self::twig_loader() to set the Twig Environment,
    *   see {@link http://twig.sensiolabs.org/ for Twig Documentation}.
    * @uses libs/vecni/Session::start to start a server session.
    */
    public static function init($file)
    {
        self::$data = new Object();
        self::$auth = new Object();
        self::$current_path = remove_extra_slashes(urldecode($_SERVER['REQUEST_URI']));
        self::$hostname = $_SERVER["SERVER_NAME"];
        self::$host = dirname($_SERVER["SCRIPT_NAME"])."/";
        self::$host = remove_extra_slashes(self::$host);

        # get the root folder of the application
        # absolute address
        self::$paths["core"] = dirname($file);
        # relative address
        self::$paths["index_file"] = basename($file);

        # set the default subfolders of the application
        self::$paths["static"] = "app/static";

        self::$paths["route_file"] = self::$paths["core"].DIRECTORY_SEPARATOR."main.ini.php";

        self::$paths["templates"] = self::$paths["core"].DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR;
        self::$paths["libraries"] = self::$paths["core"].DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."libs".DIRECTORY_SEPARATOR;
        self::$paths["plugins"] = self::$paths["core"].DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR;
        self::$paths["controllers"] = self::$paths["core"].DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."controller".DIRECTORY_SEPARATOR;
        self::$paths["configs"] = self::$paths["core"].DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."configs".DIRECTORY_SEPARATOR;


        self::get_settings();
        self::twig_loader();
        self::setup();
        self::set_datetime();
        libs\Session::start();
    }

    private static function setup(){
        libs\Cookie::$domain = self::getHostName();
        libs\Cookie::$path = self::$host;
        self::$cookie = libs\Cookie::get_class();
        self::$session = libs\Session::get_class();
        self::$request = http\Request::get_class();
        self::$response = http\Response::get_class();
    }

    /**
     * Set current date, time and timezone.
     */
    private static function set_datetime(){
        date_default_timezone_set('America/Jamaica');
        self::$current_date = new \DateTime();
    }

    /**
    * Get the user defined configurations found in the settings.ini.php file.
    * @see "https://github.com/b4oshany/vecni/blob/master/app/configs/settings.ini.php"
    *   settings.ini.php settings.ini.php file.
    */
    public static function get_settings(){
        include_once self::$paths["configs"]."settings.ini.php";
    }

    /** Get the plugins folder of application. */
    public static function getPluginsFolder(){
        return self::$paths["plugins"];
    }

    /** Get the templates folder of the application. */
    public static function getTemplatesFolder(){
        return self::$paths["templates"];
    }

    /** Get the libs (library) folder of the application. */
    public static function getLibsFolder(){
        return self::$paths["libraries"];
    }

    /** Get the configs (configuration) folder of the application. */
    public static function getConfigsFolder(){
        return self::$paths["configs"];
    }

    /**
    * Get the base file of the application.
    * The base file is usually the index.php file.
    */
    public static function getIndexFile(){
        return self::$paths["index"];
    }

    /** Get the root folder of the application based on the base file. */
    public static function getRootFolder(){
        return self::$paths["core"];
    }

    /**
    * Prepend root folder with file location.
    * @return string file path with root prepended.
    */
    public static function prependRootFolder($file){
        return File::build_path(self::getRootFolder(), $file);
    }

    /** Get the FQDN or URI of the application */
    public static function getTwigtHost(){
        return (substr(self::$host, -1) == "/")? substr(self::$host, 0, -1) : self::$host;
    }

    /** Get hostname */
    public static function getHostName(){
        return self::$hostname;
    }

    /**
    * Import database.
    * @var strign $file Path of the database file.
    * @return boolean True if update was successful, else false.
    */
    public static function update_database($file){
        if(PDOConnector::import($file)){
            echo "Update was successful";
        }else{
            echo "Something went wrong";
        }
    }

    /**
    * Get the static folder of the application.
    * @param boolean $relative Set it to true for relative path (URL path) or false for absolute path (filesystem).
    * @return string Path of static folder.
    */
    public static function getStaticFolder($relative_path=true){
        return (($relative_path)? self::getTwigtHost()."/" : self::$paths["core"].DIRECTORY_SEPARATOR).self::$paths["static"];
    }

    /**
    * Check if the project is on a development server or a production server.
    * Note: Caching is disable if the project is on a development server.
    * @return boolean True if it is on a development server, else false.
    */
    public static function in_development(){
        if($_SERVER["SERVER_NAME"] == "localhost" || self::$mode == "debug"){
            return true;
        }
        return false;
    }

    /**
    * Notify the developer that plugins or modules are missing, if applicable.
    * Missing modules will only displayed on development server.
    * Note: if a module is missing, all php process will be terminated for this application.
    *
    * @param string @missing_plugin Module that is missing.
    */
    public static function get_submodules($missing_plugin=""){
        $req_copy = self::getRootFolder().'/app/configs/hooks into '.self::getRootFolder().'/.git/hooks/';
        if(!empty($missing_plugin))
            echo "<p>Missing plugin $missing_plugin.<p>";
        ?>
            <h3>Please run the following command in git bash.</h3>
            <code> git submodule update --init</code>
            <p>In addition, copy the contents of <?php echo $req_copy; ?></p>
            <p>By doing this, it will ensure that the permissions and other
             server settings will not be modified by git during a push or pull</p>
        <?php
        http\Response::abort("In order for you to continue.");
    }

    /**
    * Enable PHPError reporting on local or development server.
    * This plugin is location in the app/plugins folder.
    * By default, error will only be displayed on local or developmenet server.
    * If for some reason there is a need to display error on production server
    * set $display_error=true and $override_default=true.
    *
    * @uses "http://phperror.net/" PHPError reporting.
    * @uses self::enable_PHPError_plugin for php error reporting.
    * @param boolean $display_error Display error if set to true.
    * @param boolean $override_default Override the default displaying of error.
    * @param boolean $enable_error_plugin Enable PHPError plugin.
    */
    public static function enable_error_reporting($display_error = true,
        $override_default=false){
        if((self::in_development() && $display_error) || ($display_error && $override_default)){
            error_reporting(E_ALL);
            ini_set('display_errors',1);
            ini_set('html_errors',1);
            self::enable_PHPError_plugin();
        }else{
            ini_set('display_errors',0);
        }
    }

    /**
    * Enable PHPError plugin on or off.
    * PHPError is used for stylish error reporting.
    * @uses "http://phperror.net/" PHPError reporting.
    */
    public static function enable_PHPError_plugin(){
        self::$errorHandler = new \php_error\ErrorHandler();
        self::toggle_PHPError_plugin(true);
    }

    /**
    * Get PHPError Handler.
    * @return \php_error\ErrorHandler PHPError Handler.
    */
    public static function get_error_handler(){
        return self::$errorHandler;
    }

    /**
    * Toggle PHPError plugin on or off.
    * PHPError is used for stylish error reporting.
    * @uses "http://phperror.net/" PHPError reporting.
    * @param boolean $display_error Set it to true to turn off PHPError plugin
    * else true. The default is true.
    */
    public static function toggle_PHPError_plugin($display_error=true){
        if(!empty(self::$errorHandler)){
            if($display_error){
                self::$errorHandler->turnOn();
            }else{
                self::$errorHandler->turnOff();
            }
        }
    }

    /**
    * Enable Twig Template Management.
    * This plugin is location in the app/plugins folder.
    * By default, three of the app variables will be accessible to twig, i.e.
    * ({@link libs\vecni\Vecni::get_configs() configs}), ({@link libs\vecni\Vecni::getHost() host})
    * ({@link libs\vecni\Vecni::getStaticFolder() static}).
    * @uses "http://twig.sensiolabs.org/" Twig.
    * @uses libs\vecni\vetwig\Filter to register default filters for Twig.
    */
    public static function twig_loader(){
        # get the templates folder and prepare template rendering
        $loader = new \Twig_Loader_Filesystem(self::$paths["templates"]);
        self::$twig = new \Twig_Environment($loader, array(
            'debug' => true,));
        self::$twig->addExtension(new \Twig_Extension_Debug());
        # added global variables to twig
        self::$twig->addGlobal("config", self::get_configs());
        self::$twig->addGlobal("host", self::getTwigtHost());
        self::$twig->addGlobal("static", self::getStaticFolder());

        if(self::in_development()){
            self::$twig->clearCacheFiles();
            self::$twig->clearTemplateCache();
        }
        self::$twig->addGlobal("version", self::version());

        vetwig\TwigFunction::addClassFunction("url_for");
        vetwig\Filter::register();
    }

    /**
    * Enable LESS CSS plugin, a functional CSS compiler.
    * Enabling this plugin will generate a new compilied css from the list of less and/or css
    * files defined in {@link https://github.com/b4oshany/vecni/blob/master/app/static/src/css/less/style.less
    *   style.less} file located in app/static/src/css/less/ to
    * {@link https://github.com/b4oshany/vecni/blob/master/app/static/gen/css/style.css style.css} file located
    * in app/static/src/gen/css.
    * Note: Ensure that app/static/src/gen folder have the correct permission of 775.
    * This plugin is location in the app/plugins folder.
    *
    * @uses "http://lesscss.org/" PHPLess Compiler.
    */
    public static function use_less(){
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
    }

    /**
    * Enable PHPMailer to send html emails.
    * @uses "https://github.com/PHPMailer/PHPMailer" PHPMailer plugin located in app/plugins/ folder.
    */
    public static function email_loader(){
        $mailer = new PHPMailer;
        $mailer->From = self::$company_email;
        $mailer->FromName = self::$BRAND_NAME;
        $mailer->isHTML(true);
        $mailer->WordWrap = 70;
        return $mailer;
    }

    /**
    * Get the current visited url.
    *
    * @return string URL the current request url.
    */
    public static function get_current_path(){
        return self::$current_path;
    }

    /**
    * Execute the defined dispatching function on the current request url.
    *
    * @parma string $app_route URL string to execute. $app_route is null by default,
    *   this is done to render the homepage.
    */
    public static function get_route($app_route=null){
        /**
        * Get the url dispatching function to be used to render the view or data.
        *
        * @param string|null $app_route, url for the dispatching function to be used if
            string, else assume it's the home page.
        */
        if($app_route == null){
            $path = self::$current_path;
            krsort(self::$app_route);
            if(self::$host !== $path){
                unset(self::$app_route["/"]);
            }
            // Search for the respective url dispatching function.
            foreach(self::$app_route as $url => $app_route){
                $url_regex = str_replace('/', '\/', $url);
                $url_regexpr = preg_replace("/\{.+?\}/", "[^\/]+", $url_regex);
                $result = preg_match("/".$url_regexpr.'\/?\??.*$'."/", $path);
                // If an unique id is found and has a unique variable id,
                // set it to a GET request.
                if($result){
                    $fn_args = self::get_params($app_route);
                    preg_match("/".$url_regexpr."/", $path, $matches);
                    $data = explode("/", $matches[0]);
                    $args = explode("/", $url);
                    $has_arg_value = false;
                    self::addTwigPersistGlobals();
                    // Get the function arguments.
                    if(!empty($fn_args)){
                        foreach($fn_args as $index => $param){
                            $key = array_search("{{$param}}", $args);
                            if($key !== false){
                                $has_arg_value = true;
                                $value = http\Request::filter_input($data[$key]);
                                $fn_args[$index] = $value;
                                /***** Going to be deprecated ****/
                                $_GET[$param] = $value;
                               /***** *******/
                            }else{
                                $fn_args[$index] = null;
                            }
                        }
                        if($has_arg_value){
                            echo call_user_func_array($app_route, $fn_args);
                            return;
                        }
                    }
                    echo $app_route();
                    return;
                }
            }
            self::error_route();
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

    /**
    * Get url for the dispatching function.
    *
    * @param string $fn_name Name of the dispatching function.
    * @param string[] $args Associative array with the url args (key) and its value.
    * @return string URL to be rendered.
    */
    public static function url_for($fn_name, array $args, $fqnd=true){
        if($url = array_search($fn_name, self::$app_route)){
            foreach($args as $arg => $value){
                $url = str_replace("{{$arg}}", "$value", $url);
            }
            return ($fqnd)? self::$host."/$url" : $url;
        }else{
            throw new error\UrlException("No such dispatching function was defined");
            self::abort();
        }
    }

    /**
    * Get dispatching function for the dispatched url.
    * @param string $dispatched_url Dispatched url that is assigned to the dispatching function.
    * @return string|bool Name of the dispatcheing function if found false if not.
    */
    public static function dispatching_func_for($dispatched_url){
        if(isset(self::$app_route[$dispatched_url])){
            return self::$app_route[$dispatched_url];
        }else{
            throw new error\UrlException("No such dispatching function was defined");
        }
    }

    /**
    * Set the function to render the error page.
    * @param string|function Function name or actual function to use as the dispatching function
    *   for the error page.
    */
    public static function set_error_route($fn_name){
        self::$app_route["error404"] = $fn_name;
    }

    /** Trigger application abort and error page. */
    public static function error_route(){
        if(isset(self::$app_route["error404"])){
            $app_route = self::$app_route["error404"];
            $app_route();
            http\Response::abort();
        }else{
            self::abort();
        }
    }

    /**
    * Abort all php process and return HTTP status code 404 to the connected client.
    * @param string $message - HTTP Response message to output to the client.
        It is default to Not Found.
    * @param int $status_code - HTTP Response status code. The status code is
        default to 404, which means Not Found.
    * @param string $status_text - HTTP Response status text. The status text is
        default to Not Found.
    */
    public static function abort($message = "Not Found", $status_code=404){
        if(!http\Request::is_async()){
            echo self::$twig->render('404.html',
                                     array('message'=>$message)
                                   );
            http\Response::abort($message, $status_code);
        }else{
            http\Response::abort($message, $status_code, true);
        }

    }

    /**
    * Set the url to be rendered by a specific url dispatching function.
    * @param string $url URL to be rendered.
    * @param string|function The function name or the actual function to act as the dispatching function
    *   to render the url.
    */
    public static function set_route($url, $app_route){
        self::$app_route[$url] = $app_route;
    }

    /**
    * Get the current path of the request url.
    * @return string Path of the resquest.
    */
    public static function get_path(){
        $path = $_SERVER['REQUEST_URI'];
        $len =  strlen(self::$paths["core"]);
        $pos = strripos($path, self::$paths["core"]);
        if($pos <= $len){
            return substr($path, ($len+1));
        }
        return substr($path, 1);
    }

    /**
    * Compile the basic information of company into an array.
    * @return string[] Company information.
    */
    public static function get_configs(){
        return array(
            "BRAND_NAME"=>self::$BRAND_NAME,
            "company_address"=>self::$company_address,
            "company_number"=>self::$company_number,
            "company_email"=>self::$company_email,
            "company_name"=>self::$company_name
        );
    }

    /**
    * Perform a server redirection.
    * @param string $url Url to redirect to.
    * @param bool $asnyc Type of response redirect, i.e. Asynchronous redirection or synchronous.
    * @param string $title Title of the redirection.
    * @param int $timeout Amount of seconds to wait before redirection occur.
    */
    public static function redirect($url = "/home", $title="", $async=false, $timeout=0){
        $url = self::$host.$url;
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
                setTimeout(function(){
                    history.replaceState(<?php echo json_encode($data).", \"$title\", \"$url\""; ?>);
                    document.title = <?php echo "'$title'"; ?>;
                }, <?php echo $timeout * 1000; ?>);
            </script>
        <?php
            die();
        }
    }

    /** Reload the page. */
    public static function reload(){
        ?>
        <script>
            window.location.reload();
        </script>
        <?php
     }

    /** Navigate back to a previous browser history. */
    public static function nav_back(){
        ?>
        <script>
            window.history.back();
        </script>
        <?php
    }

    /**
    * Version and compiled time
    * @return string Version number and timestamp compiled.
    */
    public static function version(){
        return self::$version.((self::in_development())? "-".time():"");
    }
}

?>

