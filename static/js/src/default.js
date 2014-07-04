var supportStorage = false;

if(typeof(Storage) !== undefined){
    supportStorage = true;
}

$('#password').keyup(function(e){
    if(!validatePassword(this.value)){
        this.style.outline = '1px solid red';
    }else{
        this.style.outline = 'none';   
    }
});

$("input[type='email'']").blur(function(){
    if(!validateEmail(this.value)){
        this.style.outline = '1px solid red';
    }else{
        this.style.outline = 'none';   
    }
})

$('form[name="signin"]').submit(function(e){
    $.post('procsignin/', $(this).serialize(), function(response){
       if(response.status == 200){  
            window.location.assign("welcome"); 
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
            window.location.assign("welcome");   
       }else{
           //$("#err").remove();
            //$('#container').append('<div id="err">Something went wrong in registeration</div>');   
       }
    });
    return false;
});  