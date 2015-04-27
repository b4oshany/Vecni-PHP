// If no user is signin, enable the listeners for the signin and register form.
if(app.current_user.data === 0){
    app.current_user.events.signin();
    app.current_user.events.register();
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

$(document).on("click", ".property-like", function(e){
  var property_id = this.getAttribute("data-target");
  $.post(host+"/user/favourite/properties/add/", {property_id: property_id},
    function(response){
      flash_notification(response, "success");
  });
});


