var background_slide = document.querySelector("#slider");
background_slide.style.height = window.innerHeight+"px";
var image_urls = ["static/img/bob.jpg", "static/img/people.jpg", "static/img/group.jpg"];
load(wrapper, "page/forms.php #login", null);

function preload_image(image_urls){
    var images = new Array();
    for(x = 0; x < image_urls.length; x++){
        images[x] = new Image();
        images[x].src = image_urls[x];
    }
    return images;
}

function do_slider(element, images){
    var index = 0;
    element.style.backgroundImage = "url('"+images[index++].src+"')";
    setInterval(function(){
        if(index == images.length)
            index = 0;
        element.style.backgroundImage = "url('"+images[index++].src+"')";
    }, 3000);
}

do_slider(background_slide, preload_image(image_urls));

window.onload = function(){
    container = document.querySelector("#overcast .wrapper > *");
    container.style.top = "-"+(container.clientHeight/2)+"px"; 
    container.style.left = "-"+(container.clientWidth/2)+"px";
}



