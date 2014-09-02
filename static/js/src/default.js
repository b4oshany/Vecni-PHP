var supportStorage = false;

if(typeof(Storage) !== undefined){
    supportStorage = true;
}

function flash_notification(message, status){
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
    $.post('sendto', {"user_invite": invites}, function(response){
        if(response.status == 200){
            flash_notification("Your invites has been sent to the following users/email "+invites, "success")
        }else{
            flash_notification("Your invites has not been sent to the following users/email "+invites, "error")
        }
    });
    return false;
});


function show_tab(element){
    $(element).tab("show");
}

/*$("#remote-modal").on('show.bs.modal', function (e) {
    var url = e.relatedTarget.getAttribute("href");
    if(url == undefined)
        url = e.relatedTarget.getAttribute("data-remote");
    console.log(url);
    $("#remote-modal").load("welcome");
});*/
