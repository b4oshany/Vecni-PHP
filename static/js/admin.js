
window.onload = function(){  
    var patt = new RegExp("^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])([a-zA-Z0-9]{8,})$");
    var pass = $('#pass');
    var cpass = $('#cpass');
    $('#pass, #cpass').keyup(function(e){
        if(!patt.test(this.value)){
            this.style.outline = '1px solid red';
        }else{
            this.style.outline = 'none';   
        }
    });
	$('form[name="register"]').submit(function(e){   
        if(pass.val() === cpass.val() && patt.test(pass.val())){
		$.post('etc/modules/user/add.php', $(this).serialize(), function(data){
            console.log(data);
           if(data == 1){
                window.location.assign('index.php?do=home');   
           }else{
               $("#err").remove();
                $('#container').append('<div id="err">Something went wrong in registeration</div>');   
           }
        });
        }else{
            if(pass != cpass){
                this.cpass.placeholder = 'passwords did not match';
            }else{
                this.cpass.placeholder = 'the password you enter is invalid'
            }
        }
        return false;
	});   
}
