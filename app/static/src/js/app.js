var infer = {
    script: function(script){
      $script = document.createElement('script');
      $script.type = 'text/javascript';
      $script.src = script;
      document.body.appendChild($script);
    }
}
