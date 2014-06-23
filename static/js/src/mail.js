// JavaScript Document
var window_width = window.innerWidth;
var window_height = window.innerHeight;	
var mitems = document.getElementById("menu_items");
var meslist = document.getElementById('message_list');
var mestable = document.getElementById('tbmes');
document.title = 'Cheapomail Inbox';
function syncd(do_sync){
    if(!do_sync){
        clearInterval(sync);
    }else{
        var sync = setInterval(function(){
            getMessages('get_recent');
        }, 3000);
    }
}

var maintit = document.title;
function alert_new(do_alert){
    if(do_alert){
        var check = 1;
        var ale = setInterval(function(){
            document.title = (check%2 == 0)? document.title = maintit : 'You have a new message';
            check++;
        }, 2000);                
    }else{
        clearInterval(ale);   
    }
}

$(window).bind("load resize", 'window', function(){
    var window_width = window.innerWidth;
    var window_height = window.innerHeight;	
    mitems.style.height = window_height+'px';
    meslist.style.width = (window_width - 30)+'px';
    $('#main').width((window_width - 30)+'px');
});

changeUrl('?do=mail');
$('#menu').click(function(){   
   if($(mitems).css("left") != "0px"){
		$(mitems).animate({"left" :"0"}, "slow");   
		$('#main').animate({"left" :"195px"}, "slow");   
		$(this).animate({"left":"195px"}, "slow");  
       $("#head").css('position', 'fixed');
   }else{
		$(mitems).animate({"left": "-195px"}, "slow");   
		$('#main').animate({"left": "0px"}, "slow");   
		$(this).animate({"left":"3px"}, "slow");
       $("#head").css('position', 'absolute');	
   }
});
$('#compose').click(function(e){
    $("#composer").show();
});

function changeUrl(page){
window.history.replaceState({page: page}, "user history", page);   
}


$('#inbox').click(function(e){
    closeNessage();
    $('#context_switch').text('Sender');
    getMessages('get_ten');
});
$('#sent').click(function(e){
    closeNessage();
    $('#context_switch').text('To');
    getMessages('sent');
});

getMessages('get_ten');
function getMessages(amt){
    $.post('etc/modules/message/controller.php', {'type': amt} , function(data){
	   if(data != 0){
		   var num_mes = $(data).filter('tr');
		   var table = $('#mestable tbody');
           if(amt != 'get_ten'){
               alert_new(true);
           }else if(amt == 'get_ten'){
               table.empty();
           }else if(amt == 'sent'){
                table.empty();   
           }           
		   table.prepend(data);
	   }else{
		   if(amt == 'get_ten'){
			$('#mestable tbody').empty().append('<tr><td></td><td>No Message Available</td></tr>');
		   }
	   }
	});    
}

$('#composer form[name="composer"]').submit(function(e){
    var dats = $(this).serialize();
    dats += '&type=add';
    var str = $('#comprecip').val();
    var patt = new RegExp("[^(a-z|A-Z|0-9|;)]")
   if(patt.test(str)){
       $('#comprecip').css('outline','1px solid red');
       return false;
   }
    $.post('etc/modules/message/controller.php', dats , function(data){
           if(data == 1){
               $('#composer').hide();
           }
        });
	$(this)[0].reset();
    return false;
});


function closeNessage(){
    mitems.style.height = (window_height - 50)+'px';
    meslist.style.width = (window_width - 30)+'px';
    $("#message_content").width('0px').hide();
    
}

function reply(pa){
    var parent = $('#'+pa);   
    var to = parent.find('h3.dfrom label').text();
    var subj = parent.find('h3.dsubject label').text();
    var mes = parent.find('p.dmessage').text();
    $("#comprecip").val(to); 
    $("#compsubj").val('Reply: '+subj); 
    $("#compmessage").val('\n\r\t\t--------     --------\n\r'+mes); 
    $("#composer").show();
}

function readMessage(e, ad){
    var id = $(e).attr('id');
    $(meslist).width('300px');
    var mcon = $("#message_content");           
    mcon.width(($('#main').width() - 320)+"px").show();
    $("#mestable td").css({"max-width": '150px'});
    mcon.width((window_width * 0.71)+'px');
    alert_new(false);
    $.ajax({
       url:'etc/modules/message/controller.php',
        data : {'mid': id, 'type':ad},
        type: 'POST',
        success: function(data){
            mcon.empty();
            mcon.append(data);
            $(e).removeClass('new');
            mcon.height(($(meslist).height() + 'px'));
        }        
    });    
};

$(document).ready(function(e){
    $('#comprecip').keyup(function(e){
       switch(e.keyCode){
            case 32:
               this.value = this.value.substr(0, this.value.length - 1)+';';
               break;
            case 188:
               this.value = this.value.substr(0, this.value.length - 1)+';';
               break;
            default:
               var str = $(this).val();
                var patt = new RegExp("[^(a-z|A-Z|0-9|;)]")
               if(patt.test(str)){
                   this.style.outline = '1px solid red';
               }else{
                   this.style.outline = 'none';
               }
               break;
       }
    });  
    $('#composer .close').click(function(e){
        $("#composer").hide();
    });
    syncd(true);
});