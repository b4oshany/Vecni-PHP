<?php
# define package usage
use libs\vecni\http\Response;
use libs\vecni\http\Request;
use libs\vecni\Vecni as app;
use libs\vecni\Session as session;
use controllers\user;
use controllers\property;

user\User::start_session();

$less = app::use_less();
Response::init();

// Set the default title of website.
app::$twig->addGlobal("title", app::$BRAND_NAME);
if(user\User::is_login()){
    app::$auth->current_user = user\User::get_current_user();
    app::$twig->addGlobal("current_user", app::$auth->current_user);
}else{
    // Login with user key (cookie user key) if present.
    if(user\User::login_with_user_key()){
        app::$twig->addGlobal("current_user", app::$auth->current_user);
    }
    app::$auth->current_user = new user\User();
    app::$twig->addGlobal("current_user", 0);
}


app::set_route("/", "home");
app::set_route("/home", "home");
/**
* Render the homepage.
* @example http://ohomes.com
* @example http://ohomes.com/home
*/
function home(){
    if(user\User::is_login()){
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


app::set_route("/docs", "docs");
/**
* Render the documentation page.
* @example http://ohomes.com/docs/
*/
function docs(){
    if(app::in_development()){
        app::redirect("/docs/index.html");
    }else{
        app::abort();
    }
}

app::set_route("/db-update", "database_update");
/**
* Render the documentation page.
* @example http://ohomes.com/docs/
*/
function database_update(){
    if(app::in_development()){
        $path = app::prependRootFolder("app/data-dump/ohome.sql");
        app::update_database($path);
    }else{
        app::abort();
    }
}


app::set_route("/user/signin", "signin_require");
/**
* Render the sign in page for users
* @example http://ohomes.com/user/signin
*/
function signin_require($message=""){
    if(!user\User::is_login()){
      if(!Request::is_async()){
        echo app::$twig->render('user/signin.html',
                  array(
                    "html_class"=>"signin",
                    "title"=>"Signin Required",
                    "message"=>$message
                  )
              );
       die();
     }else{
        app::abort("You are not authorized to perform this action", 403, "Not Login");
     }
   }else{
      if(!Request::is_async()){
        app::redirect();
      }
   }
}


app::set_route("/user/signin/process", "process_login");
/**
* Process the user signin.
* @example http://ohomes.com/user/sigin/process
*/
function process_login(){
    if(($email = Request::POST('email')) && ($password = Request::POST('password'))){
        $remember_me = Request::POST('remember-me');
        $cookie = app::$cookie;
        $session = app::$session;
        // Check if a user is resetting there password, if so, update the password;
        if($cookie::get("password_reset_token") === $session::get("password_reset_token")){
            user\User::update_password($email, $password);
            $cookie::remove("password_reset_token");
            $session::remove("password_reset_token");
            $remember_me = true;
        }
        $status = user\User::login($email, $password, $remember_me);
        if(Request::is_async()){
            if($status){
                return Response::json_response(200, $email);
            }else{
                return Response::abort("$email, does not exists in our system. Please register for account if you don't have one");
            }
        }else{
            if($status){
                return app::nav_back();
            }
        }
    }
    return signin_require();
}



app::set_route("/user/registration", "reg_request");
/**
* Render the registration page.
* @example http://ohomes.com/user/registration
*/
function reg_request($message=""){
    if(user\User::is_login()){
        app::redirect();
    }
    return app::$twig->render('user/registration.html',
                        array("html_class"=>"user-registration",
                             "title"=>"Registration",
                             )
                        );
}

app::set_route("/user/registration/process", "register");
/**
* Process the user registration.
* @example http://ohomes.com/user/sigin/process
*/
function register(){
    global $user;
    if(($first_name = Request::POST('first_name')) &&
       ($last_name =  Request::POST('last_name')) &&
       ($password = Request::POST('password')) &&
       ($email = Request::POST('email'))){
        $new_user = new user\User();
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

app::set_route("/user/facebook/login", "login_with_social_network");
app::set_route("/user/googleplus/login", "login_with_social_network");
app::set_route("/user/twitter/login", "login_with_social_network");
/**
* Process the user sigin or registration via social netowrk.
* @example http://ohomes.com/user/facebook/login
* @example http://ohomes.com/user/googleplus/login
* @exmaple http://ohomes.com/user/twitter/login
*/
function login_with_social_network(){
    global $user;
    if(user\User::is_login()){
        app::redirect();
    }
    if(!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['social_network']) && !empty($_POST['social_network_id']) && !empty($_POST['email'])){
        $new_user = new user\User();
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


app::set_route("/user/p/{user_id}", "user_profile");
/**
* Render the user profile page.
* @global string user_id User id.
*/
function user_profile($user_id){
    $properties = user\property\ViewProperty::getAll($user_id);
    app::$twig->addGlobal("properties", property\Property::query());
    $user = user\User::get_by_id($user_id);
    $user->favourites_db();
    $property_viewed = new user\property\ViewProperty($user_id);
    $num_property_view = $property_viewed->count();
    return app::$twig->render('user/profile.html',
                        array('html_class'=>'property_advertise user_profile',
                             'title'=>$user->get_fullname(),
                             'owns_profile'=>app::$auth->current_user->is_user($user->user_id),
                             'num_property_viewed'=>$num_property_view,
                             'user'=>$user));
}


app::set_route("/user/password/reset/", "password_reset");
app::set_route("/user/password/reset/{access_token}/", "password_reset");
function password_reset($access_token=null){
    if(empty($access_token)){
        $email = Request::GET("email");
        $result = ($email !== false )? user\User::send_password_request_token($email): false;
        if($email === false){
            $title = "Request password reset";
            $message = "";
        }else{
            $title = "Password reset for $email";
            if($result){
                $message = "Please check your email to reset the form.";
                app::$twig->addGlobal("hide_form", true);
            }else{
                $message = "No such email exist in the system";
            }
        }
        return app::$twig->render("user/password/request_reset.html",
            array(
                "html_class"=>"user-registration password-reset request",
                "title"=>$title,
                "password_status_message"=>$message,
                "password_reset_token"=>$access_token,
                "email"=>$email
            )
        );
    }else{
        $email = Request::GET("email");
        $new_token = md5(uniqid($access_token));
        $cookie = app::$cookie;
        $session = app::$session;
        $cookie::set("password_reset_token", $new_token, false, null, false, true);
        $session::set("password_reset_token", $new_token);
        return app::$twig->render('user/signin.html',
            array(
                'html_class'=>"user-signin password-reset reset",
                "email"=>$email,
                "title"=>"Reset password for $email",
                "signin_status_message"=>"Update your password.",
                "access_token"=>$new_token
            )
        );
    }
}
?>

