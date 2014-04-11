var supportStorage = false;
var overcast = document.querySelector("#overcast");
var wrapper = overcast.querySelector(".wrapper");
overcast.style.height = window.innerHeight+"px";

if(typeof(Storage) !== undefined){
    supportStorage = true;
}


$('form[name="login"]').submit(function(e){
    $.post('etc/modules/user/login.php', $(this).serialize(), function(data){
       if(data == 1){
            window.location.assign('?do=mail');   
       }else{
           $("#err").remove();
            $('#container').append('<div id="err">You username or password is invalid, please try log in again');   
       }
    });
    return false;
}); 

function load(element, url, fn){
    $(element).load(url, fn);    
}