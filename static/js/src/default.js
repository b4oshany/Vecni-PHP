var supportStorage = false;

if(typeof(Storage) !== undefined){
    supportStorage = true;
}

function announcement(message, status){
    $('body').append('<div class="alert fade in alert-'+status+' alert-dismissable">'
                     +'<button type="button" class="close" data-dismiss="alert"'
                     +' aria-hidden="true">&times;</button>'
                     +message+'</div>');
    setTimeout(function(){
        $(".alert").alert('close');
    }, 5000);
}

/*$('#password').keyup(function(e){
    if(!validatePassword(this.value)){
        this.style.outline = '1px solid red';
    }else{
        this.style.outline = 'none';   
    }
});*/

/*$("input[type='email'']").blur(function(){
    if(!validateEmail(this.value)){
        this.style.outline = '1px solid red';
    }else{
        this.style.outline = 'none';   
    }
})*/

$("[name='register'][type='button']").click(function(){
    $("#login-register form.login").hide("slow");
    $("#login-register .modal-title").html("Register");
    $("#login-register form.register").show("slow");
})

$("[name='signin'][type='button']").click(function(){
    $("#login-register form.register").hide("slow");
    $("#login-register .modal-title").html("Sign In");
    $("#login-register form.login").show("slow");
})


$('form[name="user_invite"]').submit(function(e){
    var invites = $("input[name='user_invite']").val();
    $.post('sendto/', {"user_invite": invites}, function(response){
        if(response.status == 200){  
            announcement("Your invites has been sent to the following users/email "+invites, "success")
        }else{
            $("#err").remove();
            $('#container').append('<div id="err">You username or password is invalid, please try log in again');   
        }
    });
    return false;
});

$('form[name="signin"]').submit(function(e){
    $.post('procsignin/', $(this).serialize(), function(response){
       if(response.status == 200){  
            window.location.reload(); 
       }else{
           $("#err").remove();
            $('#container').append('<div id="err">You username or password is invalid, please try log in again');   
       }
    });
    return false;
}); 

$('form[name="register"]').submit(function(e){   
   // if(patt.test(pass.val())){
    $.post('procregister/', $(this).serialize(), function(response){
       if(response.status == 200){
            window.location.reload();   
       }else{
           //$("#err").remove();
            //$('#container').append('<div id="err">Something went wrong in registeration</div>');   
       }
    });
    return false;
});  