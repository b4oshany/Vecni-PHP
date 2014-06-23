$('ul.nav-tabs a').click(function (e) {
  e.preventDefault()
  if($(this).attr("href") == "#post-pane"){
    $.post('page/request_handler.php', {"rt":"apost"}, function(data){
        document.querySelector("#post-pane").innerHTML = data;
    });   
  }else if($(this).attr("href") == "#group-pane"){
      $.post('page/request_handler.php', {"rt":"ggroup"}, function(data){
          document.querySelector("#groupsc").innerHTML = data;
        });   
    }   
  $(this).tab('show')
})