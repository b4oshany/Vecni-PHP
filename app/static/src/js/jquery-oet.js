$.fn.extend({
    selector_by_text: function(text){
        $(this.selector).filter(function() {
            //may want to use $.trim in here
            return $(this).val() == text1;
        }).attr('selected', true);
    },
    moveTo: function(selector){
        return this.each(function(){
            var cl = $(this).clone();
            $(cl).appendTo(selector);
            $(this).remove();
        });
    },
    placeAfter: function(selector){
        var cl = $(this.selector).clone()
        $(this.selector).remove();
        return $(cl).insertAfter(selector);
    },
    formSwap: function($from_form){
        $form = $(this.selector);
        $to_input = $form.find("input, select");
        $.each($to_input, function(e){
            $this = $(this);
            input_name = $this.attr("name");
            if(input_name != undefined){
                var value = $from_form[0][input_name].value;
                if($this.hasClass("select2")){
                    $this.select2("val", [value]);
                }else{
                    $type = $this.attr("type");
                    if($type == "radio" || $type == "checkbox"){
                        if(this.value == value)
                            $this.prop('checked','checked');
                    }else{
                        $this.val(value);
                    }
                }
          }
        });
        return $form;
    },
    fitScreenHeight: function(adjustBy){
        var height = $(window).height();
        $(this.selector).height(height + ((adjustBy === undefined)? 0 : adjustBy));
    },
    fitScreenWidth: function(adjustBy){
        var width = $(window).width();
        $(this.selector).width(width + ((adjustBy === undefined)? 0 : adjustBy));
    },
    imageFitContainer: function(){
        $(this.selector).each(function(i, item) {
          var img_height = $(item).height();
          var div_height = $(item).parent().height();
          if(img_height<div_height){
              //INCREASE HEIGHT OF IMAGE TO MATCH CONTAINER
              $(item).css({'width': 'auto', 'height': div_height });
              //GET THE NEW WIDTH AFTER RESIZE
              var img_width = $(item).width();
              //GET THE PARENT WIDTH
              var div_width = $(item).parent().width();
              //GET THE NEW HORIZONTAL MARGIN
              var newMargin = (div_width-img_width)/2+'px';
              //SET THE NEW HORIZONTAL MARGIN (EXCESS IMAGE WIDTH IS CROPPED)
              $(item).css({'margin-left': newMargin });
          }else{
              //CENTER IT VERTICALLY (EXCESS IMAGE HEIGHT IS CROPPED)
              var newMargin = (div_height-img_height)/2+'px';
              $(item).css({'margin-top': newMargin});
          }
        });
        return this;
    },
    flashMessage: function(message, status, time){
        if(isNaN(time))
          time = 5
        if(status == undefined)
          status = "success";
        $(this.selector).append('<div class="alert fade in alert-'+status+' alert-dismissable">'
                         +'<button type="button" class="close" data-dismiss="alert"'
                         +' aria-hidden="true">&times;</button>'
                         +message+'</div>');
        setTimeout(function(){
            $(".alert").alert('close');
        }, time*1000);
    }
});