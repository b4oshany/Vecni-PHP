<link href="static/css/admin.css" rel="stylesheet" type="text/css" />
<?php 

require_once "header.php";
use modules\mybook\user\User;

?>

<div class="container-fluid">
    <div class="row" id="main"  >
        <div class="col-md-8" id="main-container" >  
           <ul class="nav nav-tabs">
              <li class="active"><a href="#users-pane">Users</a></li>
              <li><a href="#post-pane">Posts</a></li>
              <li><a href="#group-pane">Groups</a></li>
            </ul>
            <br/>
           <nav class="navbar navbar-default" role="navigation">
              <div class="container-fluid">

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav post-nav post-data" id="post-nav">
                    <li class="active"><a href="#">Recent Post</a></li>
                    <li><a href="#">Popular Post</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sort by<b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a href="#">User</a></li>
                        <li><a href="#">Group</a></li>
                      </ul>
                    </li>
                  </ul>
                  <ul class="nav navbar-nav user-nav user-data">
                    <li class="active"><a href="#">Recent User</a></li>
                    <li><a href="#">Popular User</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sort by<b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a href="#">First Name</a></li>
                        <li><a href="#">Last Names</a></li>
                      </ul>
                    </li>
                  </ul>
                  <form style="display:none" class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                      <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                  </form>                 
                </div><!-- /.navbar-collapse -->
              </div><!-- /.container-fluid -->
            </nav>
            
            
            <div class="panel panel-default post-data">
              <div class="panel-heading">Post Title <span class="badge navbar-right">14</span></div>
              <div class="panel-body">
                <div class="message">Post message</div>
      
              </div>
                <div class="list-group">
                  <a href="#" class="list-group-item active">Cras justo odio </a>
                  <a href="#" class="list-group-item">Porta ac consectetur ac</a>
                  <a href="#" class="list-group-item">Vestibulum at eros</a>
                </div>
            </div>
            
            <div class="tab-content">
                <div class="tab-pane active" id="users-pane">            
                    <div class="panel panel-default">
                      <!-- Default panel contents -->
                      <div class="panel-heading ">User Information</div>

                      <!-- Table -->
                      <table class="table">
                        <thead>
                          <th>Username</th>
                          <th>First Name</th>
                          <th>Last name</th>
                          </thead>
                          <tbody>                      
                            <?php 
                                $user_data = User::getUsers();
                                foreach($user_data as $usera){
                            ?>
                                <tr>
                                  <td><a href="profile@<?php echo  $usera->getUserBy("user_id"); ?>"><?php echo  $usera->getUserBy("user_id"); ?></a></td>
                                  <td><?php echo  $usera->getUserBy("first_name"); ?></td>
                                  <td><?php echo  $usera->getUserBy("last_name"); ?></td>
                                </tr>
                              <?php } ?>
                          </tbody>
                      </table>
                    </div>
                </div>
                <div class="tab-pane" id="post-pane">
                    <!--<div class="panel panel-default">
                      <div class="panel-heading" user="<?php // $post->getPostBy("user_id") ?>"><?php // $post->getPostBy("user_full_name") ?></div>
                      <div class="panel-body">
                        <?php // $post->getPostBy("content") ?>
                      </div>
                    </div>-->
                </div>
                <div class="tab-pane" id="group-pane">
                    <!--<div class="panel panel-default">
                      <div class="panel-heading" user="<?php // $post->getPostBy("user_id") ?>"><?php // $post->getPostBy("user_full_name") ?></div>
                      <div class="panel-body">
                        <?php // $post->getPostBy("content") ?>
                      </div>
                    </div>-->
                     <table class="table">
                        <thead>
                          <th>Group Id</th>
                          <th>Group Name</th>
                          <th>Group Owner</th>
                          <th>Created Date</th>
                          </thead>
                          <tbody id="groupsc" > 
                              
                          </tbody>
                      </table>                    
                </div>
            </div>
        </div>
        <div class="col-md-4" id="sidebar" >
            <div id="active-friends" class="panel panel-primary">
              <div class="panel-heading">Active Friends</div>
              <div class="panel-body">
                <ul class="list-group">
                  <li class="list-group-item">Cras justo odio<span class="badge">14</span></li>
                  <li class="list-group-item">Dapibus ac facilisis in<span class="badge">14</span></li>
                  <li class="list-group-item">Morbi leo risus<span class="badge">14</span></li>
                  <li class="list-group-item">Porta ac consectetur ac<span class="badge">14</span></li>
                  <li class="list-group-item">Vestibulum at eros<span class="badge">14</span></li>
                </ul>
              </div>
            </div>
        </div>
    </div>
</div>
<script src="static/js/jquery.js"></script>
<script src="static/bootstrap/js/bootstrap.min.js"></script>  
<script src="static/js/admin.js" ></script>
    