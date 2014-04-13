var supportStorage = false;
var overcast = document.querySelector("#overcast");
var wrapper = overcast.querySelector(".wrapper");
overcast.style.height = window.innerHeight+"px";

if(typeof(Storage) !== undefined){
    supportStorage = true;
}

function load(element, url, fn){
    $(element).load(url, function(data){
        fn();
        var scripts = $(data).find('jscript');
        scripts.each(function(){
           src = this.getAttribute('src');
           $.getScript(src);
        });
    });    
}

function overcast_reposition(){    
    container = document.querySelector("#overcast .wrapper > *");
    container.style.top = "-"+(container.clientHeight/2)+"px"; 
    container.style.left = "-"+(container.clientWidth/2)+"px";
}