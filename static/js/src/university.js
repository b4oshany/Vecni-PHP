$('form[name="university_registration"]').submit(function(){
    $.post("university?p=add", $(this).serialize(), function(response){
        if(response.status == 200){
           alert("sada");
        }          
    });
    return false;
});