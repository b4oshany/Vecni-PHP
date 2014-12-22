<?php
# define package usage
use libs\vecni\http\Response;
use libs\vecni\http\Request;
use libs\vecni\Vecni as app;
use controller\user\User;

User::start_session();

$less = app::use_less();
Response::init();

// Set the default title of website.
app::$twig->addGlobal("title", app::$BRAND_NAME);
if(User::is_login()){
    app::$twig->addGlobal("user", User::get_current_user());
}

/**
Welcome:
    Navigational view that renders the welcome page to the user.
    This function is the default fall back function that
    have been registered in the system by default.
*/
app::set_route("/", "welcome");
app::set_route("/home", "welcome");
function welcome(){
    if(User::is_login()){
        return app::$twig->render("demo/vecni_docs.html",
                    array(
                        "html_class"=>"welcome"
                    ));
    }else{
        return app::$twig->render('demo/vecni_docs.html',
                      array(
                        "html_class"=>"welcome",
                        "title"=>app::$BRAND_NAME
                      )
                  );
    }
}

/**
* Sign in page for users
*/
app::set_route("/user/signin", "signin_require");
function signin_require($message=""){
    return app::$twig->render('user_signin.html',
              array(
                "html_class"=>"signin",
                "title"=>"Signin Required",
                "message"=>$message
              )
          );
}


/**
* Sign in processing for users
*/
app::set_route("/user/signin/process", "process_login");
function process_login(){
    if(!empty($_POST['email']) && !empty($_POST['password'])){
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $status = User::login($email, $pass);
        if(Request::is_async()){
            if($status){
                return Response::json_response(200, $email);
            }else{
                return Response::abort("$email, does not exists in our system. Please register for account if you don't have one");
            }
        }else{
            if($status){
                return app::nav_back();
            }else{
                return signin_require();
            }
        }
    }
}


/**
* Registration page for users
*/
app::set_route("/user/registration", "reg_request");
function reg_request($message=""){
    if(User::is_login()){
        app::redirect();
    }
    return app::$twig->render('user_registration.html',
                        array("html_class"=>"user-registration",
                             "title"=>"Registration",
                             )
                        );
}

/**
* Registration processing for users
*/
app::set_route("/user/registration/process", "register");
function register(){
    global $user;
    if(($first_name = Request::POST('first_name')) &&
       ($last_name =  Request::POST('last_name')) &&
       ($password = Request::POST('password')) &&
       ($email = Request::POST('email'))){
        $new_user = new User();
        $new_user->first_name = $first_name;
        $new_user->last_name = $last_name;
        if($dob = Request::POST('dob')){
            $new_user->dob  = $dob;
        }else{
            $new_user->dob = "0000-00-00";
        }
        $new_user->gender = Request::POST('gender', "other");
        $status = $new_user->register($email, $password);
        if(Request::is_async()){
            if($status){
                return Response::json_response(200, $email);
            }else{
                return Response::abort("This accound has already been registered");
            }
        }else{
            if($status){
                app::redirect();
            }else{
                app::redirect();
            }
        }
    }
}

app::set_route("/facebooklogin", "login_with_social_network");
app::set_route("/googleplus", "login_with_social_network");
app::set_route("/twitter", "login_with_social_network");
function login_with_social_network(){
    global $user;
    if(User::is_login()){
        app::redirect();
    }
    if(!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['social_network']) && !empty($_POST['social_network_id']) && !empty($_POST['email'])){
        $new_user = new User();
        $new_user->first_name = $_POST['first_name'];
        $new_user->last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $new_user->dob  = DateTime::createFromFormat('m/d/Y',
                                           $_POST['dob']);
        $new_user->gender = $_POST['gender'];
        if(!empty($_POST['school'])){
            $new_user->school = $_POST['school'];
        }
        $account_type = $_POST['social_network'];
        $account_id = $_POST['social_network_id'];
        $status = $new_user->login_with_social_network($email, $account_type, $account_id);
        if($status){
            return Response::json_response(200, $email);
        }else{
            return Response::json_response(204, "Something went wrong");
        }
    }
}


/**
* Log out users out of Tattle Tale
* @redirect page welcome
*/
app::set_route("/logout", "log_out");
function log_out(){
    if(User::is_login()){
        User::log_out();
        app::$twig->addGlobal("user", new User());
    }
    app::redirect();
}

?>


