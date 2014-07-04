/************************** Form Validation ********************************/

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 

function validatePhoneNumber(number){
  var re = /(\d{10}|\d{11}})/;
  number = number.replace(" ", "");
  return re.test(number);
}

function formErrorCheck(element){
    if($(element).find(".has-error").length != 0)
        return true;   
    return false;
}

function validatePassword(password){
    var re = new RegExp("^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])([a-zA-Z0-9]{8,})$");
    //return re.test(password);
    return false;
}

function validateForm(element){
    if(formErrorCheck(element)){
        return false;        
    }
  var required = $("form[name^='director_'].active").find("input[required]:visible, input[required][disabled=false]");
  var bool = true;
  $.each(required, function(){
    if($(this).val() == ""){
      bool = false;
      return bool;
    }
  });
  return bool;
}


