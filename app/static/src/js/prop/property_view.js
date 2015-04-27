/**
 * Allow users to comment on the property.
 */
$(document).on("submit", "#property_comment_add", function(e){
  e.preventDefault();
  $form = $(this);
  $.post(host+"/property/"+property.property_id+"/comment/add/process", $form.serialize(), function(response){
      $("#property-comments").refresh(response);
  });
});
