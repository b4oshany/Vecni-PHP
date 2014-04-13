/*
    Handle registration and login 
*/
var patt = new RegExp("^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])([a-zA-Z0-9]{8,})$");

var form_ulogin = $('form[name="fm_ulogin"]');
var form_uregister = $('form[name="fm_usignup"]');

var pass = $('#pass');
//var cpass = $('#compass');


$('#pass').keyup(function(e){
    if(!patt.test(this.value)){
        this.style.outline = '1px solid red';
    }else{
        this.style.outline = 'none';   
    }
});

form_ulogin.submit(function(e){
    $.post('page/request_handler.php', ($(this).serialize())+"&rt=usignin", function(data){
       if(data == 1){
            window.location.assign('home');   
       }else{
           $("#err").remove();
            $('#container').append('<div id="err">You username or password is invalid, please try log in again');   
       }
    });
    return false;
}); 

form_uregister.submit(function(e){   
   // if(patt.test(pass.val())){
    $.post('page/request_handler.php', ($(this).serialize())+"&rt=usignup", function(data){
        console.log(data);
        alert(data);
       if(data == 1){
            window.location.assign('home');   
       }else{
           //$("#err").remove();
            //$('#container').append('<div id="err">Something went wrong in registeration</div>');   
       }
    });
   /* }else{
            this.pass.placeholder = 'the password you enter is invalid'
    }*/
    return false;
});  

function show_uregistration(){
    form_ulogin.hide();
    form_uregister.show();
    overcast_reposition();
};
function show_loginbox(){
    form_uregister.hide();
    form_ulogin.show();
    overcast_reposition();
};