var accessToken;
window.fbAsyncInit = function() {
    FB.init({
    appId      : '266696206855935',                    // App ID from the app dashboard
    status     : false, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true,  // parse XFBML
    oauth       : true,
    version    : 'v2.0', // use version 2.0
    });

    // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
    // for any authentication related change, such as login, logout or session refresh. This means that
    // whenever someone who was previously logged out tries to log in again, the correct case below
    // will be handled.
    FB.Event.subscribe('auth.authResponseChange', function(response) {
      fb_login_status(response);
    });
};

function fb_login_status(response){
    // Here we specify what we do with the response anytime this event occurs.
    accessToken = response.authResponse.accessToken;
    if (response.status === 'connected') {
      // The response object is returned with a status field that lets the app know the current
      // login status of the person. In this case, we're handling the situation where they
      // have logged in to the app.
      fb_api();
    } else if (response.status === 'not_authorized') {
      // In this case, the person is logged into Facebook, but not into the app, so we call
      // FB.login() to prompt them to do so.
      // In real-life usage, you wouldn't want to immediately prompt someone to login
      // like this, for two reasons:
      // (1) JavaScript created popup windows are blocked by most browsers unless they
      // result from direct interaction from people using the app (such as a mouse click)
      // (2) it is a bad experience to be continually prompted to login upon page load.
      FB.login();
    } else {
      // In this case, the person is not logged into Facebook, so we call the login()
      // function to prompt them to do so. Note that at this stage there is no indication
      // of whether they are logged into the app. If they aren't then they'll see the Login
      // dialog right after they log in to Facebook.
      // The same caveats as above apply to the FB.login() call here.
      FB.login();
    }
}

function fb_logout(element){
   /* FB.logout(function(response) {
        // Person is now logged out
    });*/
}

// Here we run a very simple test of the Graph API after login is successful.
// This testAPI() function is only called in those cases.
function fb_api() {
    FB.api('/me', function(response) {
            fb_login_user(response);
    });
}

(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    // NOTE: Commented the js.src because facebook sdk was already loaded by the Fb.init function
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


// JavaScript Document
function fb_login_user(json){
    //post the email information to usr.php file which will check if the email is already registered
    if(json.email != null && json.email != ''){
        var user_data = {'email':json.email, 'first_name':json.first_name,
                         'last_name':json.last_name, 'password':json.id,
                         'dob': json.birthday, 'gender': json.gender,
                         "social_network": "facebook",
                         'social_network_id': json.id,
                         "access_token": accessToken,
                         "education_history": JSON.stringify(json.education)
                        };
        $.post("user/login/facebook", user_data, function(response){
           if(response.status == 200){
                window.location.reload();
           }else{
           }
        });
    }else{
        alert('An error occured during facebook login because\nyou did not allow permission for your email');
    }
}
