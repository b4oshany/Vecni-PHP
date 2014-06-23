<link href="static/css/home.css" rel="stylesheet" type="text/css" />
<?php 
require_once "header.php";
use modules\mybook\user\User;
use modules\mybook\post\Controller as Post_Controller;
use modules\mybook\group\Group;
if($user_name != $user->getUserBy("user_id")){
   $usera = User::getUsers("profile.user_id = '".$user_name."'");
    if(!empty($usera)){
        $user = $usera[0];
    }
}

?>
<div class="container-fluid">
    <div class="row" id="main"  >
        <div class="col-md-9" id="main-container" >
            
            <div class="panel panel-primary">
              <div class="panel-body">
                <form name="post">
                    <div class="input-group">
                        <span class="input-group-addon"></span>                        
                      <input type="text" class="form-control" name="title" placeholder="Post Title">
                    </div><br/>
                    <textarea id="posting" class="form-control" rows="3" placeholder="What's Kicking?"></textarea><br/>
                    <input type="submit" class="btn btn-primary" value="Share" />                    
                    <button type="button" class="btn btn-default">
                      <span class="glyphicon glyphicon-camera"></span>  Photo
                    </button>                 
                </form>    
              </div>
            </div>
            <div id="post">
                <?php echo Post_Controller::getPosts("usr.user_id = '".$user->getUserBy("user_id")."'"); ?>
            </div>
        </div>
        <div class="col-md-3" id="sidebar" >
            <div id="active-friends" class="panel panel-primary">      
                <div class="profile-pic">
                  <img src="<?php $photo = $user->getUserBy("profile_pic"); echo ($photo != 0)? $photo:"static/img/default_user.png"; ?>" class="profile-pics" />
                    <span class="badge pic-changer">Change Profile Picture</span>
                </div>
              <div class="panel-heading username">
                  <?php echo $user->getUserBy("name"); ?></div>
              <div class="panel-body">
                <ul class="list-group">
                   <li class="list-group-item">First Name<span class="badge"><?php echo $user->getUserBy("first_name"); ?></span></li>
                  <li class="list-group-item">Last Name<span class="badge"><?php echo $user->getUserBy("last_name"); ?></span></li>                 
				  <li class="list-group-item">Date of Birth<span class="badge"><?php echo $user->getUserBy("dob"); ?></span></li>
                  <li class="list-group-item">Email<span class="badge"><?php echo $user->getUserBy("email"); ?></span></li>
                  <li class="list-group-item">User Id<span class="badge"><?php echo $user->getUserBy("user_id"); ?></span></li>

                </ul>
                  <input type="button" class="btn btn-default" value="Edit user Profile" id="profile_edit" /> 
              </div>
            </div>
            <div id="groupowned" class="panel panel-primary">  
              <div class="panel-heading">Groups Owned</div>
              <div class="panel-body">
                <ul class="list-group">
                    <?php 
                        $groups = Group::getGroups("owner_id = '".$user->getUserBy("user_id")."'"); 
                        if($groups != null){
                        foreach($groups as $group){
                    ?>
                   <li class="list-group-item"><a href="<?php echo $group->getGroupBy("group_id");  ?>"><?php echo $group->getGroupBy("group_name"); ?></a></li>
                    <?php }
                        }?>
                </ul>
                  <input type="button" class="btn btn-default" value="Create Group" id="create_group" /> 
              </div>
            </div>
            <div id="memberof" class="panel panel-primary">  
              <div class="panel-heading">Member of Group</div>
              <div class="panel-body">
                <ul class="list-group">
                    <?php 
                        $groups = Group::getGroupMembers("adm.user_id = '".$user->getUserBy("user_id")."'"); 
                        if($groups != null){
                        foreach($groups as $group){
                    ?>
                   <li class="list-group-item"><a href="profile@<?php echo $group["group_id"];  ?>"><?php echo $group["group_name"]; ?></a></li>
                    <?php }
                        }?>
                </ul>
                  <input type="button" class="btn btn-default" value="Find Group" id="find_group" /> 
              </div>
            </div>
            <div id="friends" class="panel panel-primary">  
              <div class="panel-heading">Friends</div>
              <div class="panel-body">
                <ul class="list-group">
                    <?php 
                       // $groups = Group::getGroupMembers("adm.user_id = '".$user->getUserBy("user_id")."'"); 
                        //if($groups != null){
                        //foreach($groups as $group){
                        $result = User::getFriends("fof.user_id = '".$user->getUserBy("user_id")."'");
                        if($result != null){
                            foreach($result as $data){
                    ?>
                   <li class="list-group-item"><a href="profile@<?php echo $data["friend_id"];  ?>"><?php echo $data["first_name"]." ".$data["last_name"]; ?></a></li>
                    <?php }
                        }?>
                </ul>
                  <input type="button" class="btn btn-default" value="Find Group" id="find_group" /> 
              </div>
            </div>
        </div>
    </div>
</div>
<script src="static/js/jquery.js"></script>
<script src="static/bootstrap/js/bootstrap.min.js"></script>  
<script src="static/js/default.js"></script>
<script>
    var edit_profile = document.querySelector("#profile_edit");
    edit_profile.onclick = function(){
        load(wrapper, "page/forms.php", "#fm_uupdate", function(){
            overcast.style.display = "block";
            overcast_reposition();
            $('form[name="fm_uupdate"]').submit(function(e){   
                $.post('page/request_handler.php', ($(this).serialize())+"&rt=uupdate", function(data){
                    if(data == 1){
                       /* $.get('page/profile.php', function(data){
                           document.html.innerHTML = data; 
                        });*/
                        location.reload();
                    }
                });
                return false;
            });  
        });
    }
    document.querySelector("#sidebar .pic-changer").onclick=function(){
        var photo = prompt("Enter url for profile image");
        if(photo != null){
            $.post('page/request_handler.php', {"rt":"uupdate", "profile_pic":photo}, function(data){
               // if(data == 1){
                    alert(data);
                //}
            });
        }
    }
    
    document.querySelector("#create_group").onclick=function(){
        load(wrapper, "page/forms.php", "#fm_addgroup", function(){
            overcast.style.display = "block";
            overcast_reposition();
            $('form[name="fm_addgroup"]').submit(function(e){   
                $.post('page/request_handler.php', ($(this).serialize())+"&rt=addgroup", function(data){
                    if(data == 1){
                        var groupid = this.querySelector("input[name='groupid'").getAttribute("value");
                        location.assign("group@"+groupid);
                    }
                });
                return false;
            });  
        });           
    }
    
</script>
    