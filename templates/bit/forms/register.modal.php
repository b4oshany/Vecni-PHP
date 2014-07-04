<form name="register" method="post" action="procregister" class="form-inline register" role="form" action="#" method="post"> 
  <div class="modal-body">
    <div class="form-group">
        <div class="row">
             <div class="col-md-6">
                <label>
                    <h2>Username:</h2>
                    <input type="text" name="username"  class="form-control" id="username" placeholder="Enter your User name">
                </label>
                <label>
                    <h2>First Name:</h2>
                    <input type="text" name="first_name"  class="form-control" id="username" placeholder="Enter your first name">
                </label>
                 <label>
                    <h2>Last Name:</h2>
                    <input type="text" name="last_name"  class="form-control" id="username" placeholder="Enter your last name">
                 </label>
            </div>
            <div class="col-md-6">
                <img src="static/img/photo/login.png" />
            </div>
        </div>
      <div class="row">
         <div class="col-md-6">
            <label>
                <h2>Email:</h2>
                <input type="email" name="email" class="form-control" id="username" placeholder="Enter your email">
            </label>
         </div>
         <div class="col-md-6">
            <label>
                <h2>Password:</h2>
                <input type="password" name="password"  class="form-control" id="password" placeholder="Enter your password">
            </label>
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
    <input name="signin"  type="button" value="Login" class="btn navbar-left" /> 
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-info " value="Register" name="register" >Register</button>
  </div>
</form>