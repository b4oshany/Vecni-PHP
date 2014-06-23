<div id="get_login_register">
    <form id="fm_ulogin" name="fm_ulogin" method="post" action="index.php" style="display: none">
        <img src="static/img/user-3.png">
        <div class="input-group">
          <span class="input-group-addon">@</span>
          <input type="text" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="password" class="form-control" name="pass" placeholder="Password">
        </div>
        <input type="submit" value="Sign In" class="btn btn-primary btn-lg" />
        <input type="button" value="Register" class="btn btn-primary btn-lg bn_usignup" onclick="show_uregistration()" />
    </form>  
    
    <form id="fm_usignup" name="fm_usignup" method="post" action="index.php" style="display: none">
        <img src="static/img/user-3.png">
        <div class="input-group">
          <span class="input-group-addon">@</span>
          <input type="text" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="text" class="form-control" name="first_name" placeholder="Firstname">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="text" class="form-control" name="last_name" placeholder="Lastname">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="date" class="form-control" name="dob" placeholder="Date of Birth yyyy/mm/dd">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="email" class="form-control" name="email" placeholder="email">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="password" class="form-control" name="pass" placeholder="Password">
        </div>
        <input type="button" value="Sign In" class="btn btn-primary btn-lg bn_usignin" onclick="show_loginbox()"  />
        <input type="submit" value="Register" class="btn btn-primary btn-lg" />
    </form>
    <jscript src="static/js/logreg.js"></jscript>  
</div>

<div id="fm_uupdate" >
    <form name="fm_uupdate" method="post" action="index.php" >
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="text" class="form-control" name="first_name" placeholder="Firstname">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="text" class="form-control" name="last_name" placeholder="Lastname">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="date" class="form-control" name="dob" placeholder="Date of Birth yyyy/mm/dd">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="email" class="form-control" name="email" placeholder="Email">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="url" class="form-control" name="profile-pic" placeholder="Url for Photo">
        </div>
        <input type="submit" value="Update Profile" class="btn btn-primary btn-lg" />
    </form>
</div>

<div id="fm_addgroup" >
    <form name="fm_addgroup" method="post" action="index.php" >
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="text" class="form-control" name="groupid" placeholder="Group Id">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="text" class="form-control" name="groupname" placeholder="Group Name">
        </div>
        <input type="submit" value="Update Profile" class="btn btn-primary btn-lg" />
    </form>
</div>