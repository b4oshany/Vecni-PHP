<div id="get_login_register">
    <form id="fm_ulogin" name="fm_ulogin" method="post" action="index.php" style="display: none">
        <img src="static/img/user-3.png">
        <div class="input-group">
          <span class="input-group-addon">@</span>
          <input type="text" class="form-control" name="user" placeholder="Username">
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
          <input type="text" class="form-control" name="user" placeholder="Username">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="text" class="form-control" name="fname" placeholder="Firstname">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="text" class="form-control" name="lname" placeholder="Lastname">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="date" class="form-control" name="dob" placeholder="Date of Birth yyyy/mm/dd">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="password" class="form-control" name="pass" placeholder="Password">
        </div>
        <div class="input-group">
          <span class="input-group-addon">*</span>
          <input type="password" class="form-control" name="conpass" placeholder="Comfirm Password">
        </div>
        <input type="button" value="Sign In" class="btn btn-primary btn-lg bn_usignin" onclick="show_loginbox()"  />
        <input type="submit" value="Register" class="btn btn-primary btn-lg" />
    </form>
    <jscript src="static/js/logreg.js"></jscript>  
</div>