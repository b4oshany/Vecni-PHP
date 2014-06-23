var supportStorage = false;
var overcast = document.querySelector("#overcast");
var wrapper = overcast.querySelector(".wrapper");
overcast.style.height = window.innerHeight+"px";

if(typeof(Storage) !== undefined){
    supportStorage = true;
}

function load(element, url, getElement,  fn){
    $(element).load(url+" "+getElement, function(data){
        fn();
        var scripts = $(data).children(getElement+' jscript');
        scripts.each(function(){
           src = this.getAttribute('src');
            //if(src == null || src == undefined){
              //      document.body.innerHTML += "<script>alert('ok')</script>";
            //    }else{
                    $.getScript(src);
                //}
        });
    });    
}

function overcast_reposition(){    
    container = document.querySelector("#overcast .wrapper > *");
    container.style.top = "-"+(container.clientHeight/2)+"px"; 
    container.style.left = "-"+(container.clientWidth/2)+"px";
}

$('form[name="post"]').submit(function(){
    var tex = $("#posting").val();
   $.post('page/request_handler.php', ($(this).serialize())+"&rt=postex&content="+tex, function(data){
       if(data == 1){
            //location.reload(); 
           document.querySelector("#post").innerHTML = data;
       }else{
           //$("#err").remove();
            //$('#container').append('<div id="err">You username or password is invalid, please try log in again');   
       }
    });
    return false;
});