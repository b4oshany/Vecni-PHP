function property_update_info(data, fn){
  $.post(host+"/property/"+property.property_id+"/update/", data, function(response){
    $("body").flashMessage(response, "success", 10);
      if(fn != undefined){
          fn();
      }
  });
}

function do_update_edit($element, $target, key){
  if($element.hasClass("fa-edit")){
      $element.removeClass("fa-edit").addClass("fa-save");
      $target.attr("contenteditable", "true");
  }else if($element.hasClass("fa-save")){
      $element.removeClass("fa-save").addClass("fa-edit");
      $target.attr("contenteditable", "false");
      var data = {};
      data[key] = $target.text();
      property_update_info(data);
  }
}

/**
 * Enable property title as an editable field.
 */
$(document).on("click", "#edit-property-title", function(e){
  var $this = $(this);
  var $property_title = $("#property-title");
  do_update_edit($this, $property_title, "property_title");
});

/**
 * Update property description.
 */
$(document).on("click", "#edit-property-description", function(e){
  var $this = $(this);
  var $property_desc = $("#property-description");
  do_update_edit($this, $property_desc, "description");
});



/**
 * Allow lairds to update their property address and show it on Google Maps.
 */
$(document).on("submit", "#property_update_address", function(e){
  e.preventDefault();
  $form = $(this);
  $.post($form.attr("action"), $form.serialize(), function(response){
      $(document).refresh('.', function($target, $page){
        $(".property_address").swap($page);
        searchFor($(".property-address").text());
        $(".property_update_address[type=button]").show();
        $(".property_update_address[type=submit]").hide();
        $("#property_update_address").hide();
      });
  });
  return false;
});

/**
 * Toggle the hidden form fields to perform a property address update.
 */
$(document).on("click", ".property_update_address[type=button]", function(e){
    e.preventDefault();
    $("#property_update_address, adderss.property_address").toggle();
    $(this).hide();
    $(".property_update_address[type=submit]").show();
});

/**
 * Allow lairds to remove the current view slide from the list of property images
 * they've uploaded.
 */
$(document).on("click", "#carousel-property-remove-photo", function(e){
    $("#property-profile-carousel").carousel('pause');
    var image_id = $("#property-profile-carousel .item.active img").attr("data-item");
    $.get(host+"/property/"+property.property_id+"/image/"+image_id+"/remove/",
      function(response){
        $('body').flashMessage(response, "success");
        carousel_update();
    });
});

/**
 * Allow lairds to set a property property image as the property profile image.
 */
$(document).on("click", "#carousel-property-profile-photo", function(e){
    $("#property-profile-carousel").carousel('pause');
    var image_id = $("#property-profile-carousel .item.active img").attr("data-item");
    $.get(host+"/property/"+property.property_id+"/image/"+image_id+"/profile/set/",
      function(response){
        $('body').flashMessage(response, "success");
    });
});

/**
 * Re-initiate the carousel after an update is made.
 */
function carousel_update(){
    $("#property-profile-carousel").refresh(".", function($target, page){
        $target.swap(page);
        $("#profile-carousel").carousel()
        $("#property_settings").modal({'show': false});
    });
}

/**
 * Allow lairds to add accessories, equipements and other item within the household.
 * These items are things that will be available for renters to use.
 */
$(document).on("submit", "#accessory_registration", function(e){
  e.preventDefault();
  $form = $(this);
  $.post(host+"/property/"+property.property_id+"/accessories/add/process", $form.serialize(), function(response){
    swal({title: "Good job!",
          text: response,
          timer: 5000,
          type: "success"
         });
    $("#accessories-list").load(window.location.pathname+" #accessories-list")
  });
});

/**
 * Allow users to add nearby places for a property.
 */
$(document).on("submit", "#nearby_place_add", function(e){
  e.preventDefault();
  $form = $(this);
  var target_id = $form.attr("data-target");
  if(!isNaN(target_id)){
    var url = host+"/property/"+property.property_id+"/places/nearby/"+target_id+"/update/";
  }else{
    var url = host+"/property/"+property.property_id+"/places/nearby/add/process/";
  }
  $.post(url, $form.serialize())
    .done(function(response){
      $('#nearby-places').refresh();
      $('body').flashMessage(response);
    })
    .fail(function(response){
      $('body').flashMessage(response, 'danger');
    });
});

/**
 * Remove nearby places.
 */
$(document).on("click", ".remove-vicinity", function(e){
  var target = this.getAttribute("data-target");
  $.post(host+"/property/"+property.property_id+"/places/nearby/"+target+"/remove/process/")
    .done(function(response) {
        $('#nearby-places').refresh();
        $("body").flashMessage(response);
    })
    .fail(function(response){
      $('body').flashMessage(response, 'danger');
    });
});

$('#template_modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var target = button.data('whatever') // Extract info from data-* attributes
    var title = button.data("title");    
    var $template = $(target).clone();
    var size = $template.attr("data-modal-size");
    size = (size !== undefined)? "modal-"+size: "";
    title = (title == undefined)? "":  title;
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-dialog').removeClass('modal-lg').removeClass('modal-sm').addClass(size);
    modal.find('.modal-title').text(title);
    modal.find('.modal-body').html($template);
});

// $(document).on("click", "#edit-property-price", function(e){
//     var $this = $(this);
//     $template.attr("id", "property-price-update");
//     var $modal = $("#template_modal");
//     var $modal_body = $("#template_modal .modal-body");
//     $modal_body.html($template);
//     $modal.modal({show: true});
// });

$(document).on("submit", "#template_modal #property-price-update", function(e){
    e.preventDefault();
    var $form = $(this);
    property_update_info($form.serialize());    
});

/**
 * Edit nearby places.
 */
$(document).on("click", ".edit-vicinity", function(e){
  var target_id = this.getAttribute("data-target");
  $.post(host+"/vicinity/"+target_id+"/edit/", function(html){
    var $source = $($(html).find("#nearby_place_add"));
    var $target = $("#nearby_place_add");
    $target.html($source.html());
    $target.attr("data-target", target_id);
    $("#nearby_place_add_modal").modal({show: true});
  });
});

/**
 * Remove data-target attribute from the nearby place add form.
 * Removing this attribute will allow nearby places to be added to the property.
 * Nevertheless, if the attribute is present, it will perform an vicinity update based
 * on the integer in the data-target attribute.
 */
$('#nearby_place_add_modal').on('hidden.bs.modal', function () {
  $("#nearby_place_add").removeAttr("data-target");
});

/**
 * Upload files on trigger or drag and dropping of a photo.
 */
var uploader = new FileUploader({
        "target": "#room_photo",
        "dragArea": "#drap_drop",
        "trigger": "onSubmit"
}, host+"/property/"+property.property_id+"/image/add/process", function(data){
  carousel_update();
  $("#property_settings").modal({show: false});
});

$(document).on("submit", "#property_photo_add", function(e){
    // Set and enable drag and drop for the photo uploader.
    e.preventDefault();
    e.stopPropagation();
    uploader.submit(this);
    return false;
});