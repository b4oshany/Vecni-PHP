(function() {
  var po = document.createElement('script');
  po.type = 'text/javascript';
  po.async = true;
  po.src = 'https://apis.google.com/js/client:plusone.js?onload=render';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(po, s);
})();

function render() {
  gapi.signin.render('gplus-login', {
    'callback': 'gplus_signinCallback',
    'clientid': '136591707736-log70mgd8lpelsmt8vrgnv2r0jv1vgnk.apps.googleusercontent.com',
    'cookiepolicy': 'single_host_origin',
    'scope': 'email'
  });
}

function gplus_signinCallback(authResult) {
  if (authResult['status']['signed_in']) {
    /**
    * Handler for the signin callback triggered after the user selects an account.
    */
    gapi.client.load('plus', 'v1', apiClientLoaded);
  } else {
    // Update the app to reflect a signed out user
    // Possible error values:
    //   "user_signed_out" - User is signed-out
    //   "access_denied" - User denied access to your app
    //   "immediate_failed" - Could not automatically log in the user
    console.log('Sign-in state: ' + authResult['error']);
  }
}

/**
   * Sets up an API call after the Google API client loads.
   */
function apiClientLoaded() {
  gapi.client.plus.people.get({userId: 'me'}).execute(gplus_login_user);
  //gapi.client.plus.people.get({userId: 'me'}).execute(gplus_data_dump);
}

function gplus_login_user(json){
    //post the email information to usr.php file which will check if the email is already registered
    var primaryEmail;
    for (var i=0; i < json.emails.length; i++) {
      if (json.emails[i].type === 'account') primaryEmail = json.emails[i].value;
    }
    var school_name = "";
    var school_major = "";
    for (var i=0; i < json.organizations.length; i++) {
      if (json.organizations[i].type === 'school'){
        school_name = json.organizations[i].name;
        school_major = json.organizations[i].title;
        break;
      }
    }
    if(primaryEmail != null && primaryEmail != ''){
      var user_data = {'email': primaryEmail, 'first_name':json.name.givenName,
                         'last_name':json.name.familyName, 'password':json.id,
                         'gender': json.gender,
                         "social_network": "google",
                         'social_network_id': json.id, "education_history": {school:school_name, major:school_major}
                        };
        $.post("user/login/googleplus", user_data, function(response){
           if(response.status == 200){
                window.location.reload();
           }else{
           }
        });
    }else{
        flash_notification('An error occured during google+ login because\nyou did not allow permission for your email', "danger");
    }
}

function gplus_data_dump(json){
  $.post("user/login/googlead", {data:json}, function(response){
    if(response.status == 200){
      window.location.reload();
    }else{
    }
  });
}
