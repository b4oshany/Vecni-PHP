function gplus_signinCallback(authResult) {
  if (authResult['status']['signed_in']) {
    // Update the app to reflect a signed in user
    // Hide the sign-in button now that the user is authorized, for example:
    $('#gplus-sigin').hide();
  } else {
    // Update the app to reflect a signed out user
    // Possible error values:
    //   "user_signed_out" - User is signed-out
    //   "access_denied" - User denied access to your app
    //   "immediate_failed" - Could not automatically log in the user
    console.log('Sign-in state: ' + authResult['error']);
  }
}

function gplus_login_user(json){
	//post the email information to usr.php file which will check if the email is already registered
	if(json.email != null && json.email != ''){
		var user_data = {'email':json.email, 'first_name':json.first_name,
                         'last_name':json.last_name, 'password':json.id,
                         'dob': json.birthday, 'gender': json.gender,
                         "social_network": "google",
                         'social_network_id': json.id, "education_history": JSON.stringify(json.education)
                        };
        $.post("googlelogin", user_data, function(response){
           if(response.status == 200){
                window.location.reload();   
           }else{
           }
        });
	}else{
        flash_notification('An error occured during google+ login because\nyou did not allow permission for your email', "danger");
	}
}