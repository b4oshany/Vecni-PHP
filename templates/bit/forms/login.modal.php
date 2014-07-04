<form name="signin" method="post" action="procsignin" class="form-inline login" role="form" action="#" method="post"> 
      <div class="modal-body">
		<div class="form-group">
            <div class="row">
                 <div class="col-md-6">
                    <label>
                        <h2>Username:</h2>
                        <input type="text" name="username"  class="form-control" id="username" placeholder="Enter your User name">
                    </label>
                    <label>
                        <h2>Password:</h2>
                        <input type="password" name="password"  class="form-control" id="password" placeholder="Enter your password">
                    </label>
                </div>
                <div style="col-md-6">
                    <img src = "static/img/photo/login.png" />
                </div>
            </div>
            <input name="method" id="method" type="hidden" value="$.method.$"></input>
            <input name="nonce" id="nonce" type="hidden" value="$.nonce.$"></input>
            <input name="appid" id="appid" type="hidden" value="$.appid.$"></input>
            <input name="host" id="host" type="hidden" value="$.host.$"></input>
            <input name="uri" id="uri" type="hidden" value="$.uri.$"></input>
      </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default navbar-left" value="Register" name="register" >Register</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <input name="sigin" type="submit" value="Login" class="btn btn-info"  /> 
  </div>
</form>