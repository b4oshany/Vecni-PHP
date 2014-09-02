var supportStorage = false;

if(typeof(Storage) !== undefined){
    supportStorage = true;
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

$('form[name="user_invite"]').submit(function(e){
    var invites = $("input[name='user_invite']").val();
    $.post('sendto', {"user_invite": invites}, function(response){
        if(response.status == 200){
            flash_notification("Your invites has been sent to the following users/email "+invites, "success")
        }else{
            flash_notification("Your invites has not been sent to the following users/email "+invites, "error")
        }
    });
    return false;
});


function show_tab(element){
    $(element).tab("show");
}

/*$("#remote-modal").on('show.bs.modal', function (e) {
    var url = e.relatedTarget.getAttribute("href");
    if(url == undefined)
        url = e.relatedTarget.getAttribute("data-remote");
    console.log(url);
    $("#remote-modal").load("welcome");
});*/
$.fn.bootstrapValidator.validators.enabled = {
    validate: function(validator, $field, options) {
        return true;
    }
};

$.fn.bootstrapValidator.DEFAULT_OPTIONS = $.extend({}, $.fn.bootstrapValidator.DEFAULT_OPTIONS, {
    excluded: [':disabled', ':hidden', ':not(:visible)'],
    message: 'The field is not valid',
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    }
});

$(document).ready(function() {
  $('form[name="register"]').bootstrapValidator({
      submitButtons: '[type="submit"], .validate',
      fields: {
          first_name: {
              validators: {
                  notEmpty: {
                      message: 'This field is required'
                  },
              }
          },
          last_name: {
              validators: {
                  notEmpty: {
                      message: 'This field is required'
                  },
              }
          },
          dob: {
            validators: {
              notEmpty: {
                messages: "Your date of birth is required"
              }
            }
          },
          school: {
            validators: {
              enabled: {
                valid: true
              }
            }
          },
          gender: {
            validators: {
              notEmpty: {
                messages: "Your date of birth is required"
              }
            }
          },
          email: {
              validators: {
                  notEmpty: {
                      message: 'The email address is required and can\'t be empty'
                  },
                  emailAddress: {
                      message: 'The input is not a valid email address'
                  }
              }
          },
          password: {
              validators: {
                  notEmpty: {
                      message: 'The password is required and can\'t be empty'
                  },
                  regexp: {
                      regexp: /.{6,21}/,
                      message: 'The password can only consist of alphabetical, number, dot and underscore'
                  }
              }
          }
      }
  });

  $('form[name="signin"]').bootstrapValidator({
      fields: {
          email: {
              message: 'The username is not valid',
              validators: {
                  notEmpty: {
                      message: 'Email is required and can\'t be empty'
                  },
                  emailAddress: {
                      message: 'Not a valid email address'
                  }
              }
          },
          password: {
              validators: {
                  notEmpty: {
                      message: 'Password is required and can\'t be empty'
                  },
                  regexp: {
                      regexp: /.{6,21}/,
                      message: 'Psassword can only consist of alphabetical, number, dot and underscore'
                  }
              }
          }
      }
  });

    $('form[name="register"]').on('success.form.bv', function(e) {
        // Prevent form submission
        e.preventDefault();
        // Get the form instance
        var form = e.target;
        var $form = $(e.target);
        $.post('procregister', $form.serialize(), function(response){
            window.location.reload();
        }).fail(function() {
            flash_notification("Sorry you can't register at this time", "danger");
        });
    });

    $('form[name="signin"]').on('success.form.bv', function(e) {
        // Prevent form submission
        e.preventDefault();
        // Get the form instance
        var form = e.target;
        var $form = $(e.target);
        $.post('procsignin', $form.serialize(), function(response){
           if(response.status == 200){
                window.location.reload();
           }else{
               $("#err").remove();
                $('#container').append('<div id="err">You username or password is invalid, please try log in again');
           }
        }).fail(function() {
            flash_notification("Can't signin at this time", "danger");
        });;
    });
});
