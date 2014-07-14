{% import "macros/forms.html" as forms %}
<form name="register" method="post" action="procregister" class="form-inline register" role="form" action="#" method="post"> 
  <div class="modal-body">
    <div class="form-group">
        <div class="row">
             <div class="col-md-6">                
                <div class="form-group input-group ">
                    <span class="input-group-addon noCurve"><div class="glyphicon glyphicon-user"  ></div></span>
                    {{forms.input("username", 'id="username" placeholder="User Name"', "", "full-width")}}
                </div>
                 <br/><br/>
                <div class="form-group input-group ">
                    <span class="input-group-addon noCurve"><div class="glyphicon glyphicon-user"  ></div></span>
                    {{forms.input("first_name", 'id="username" placeholder="Last Name"', "noCurve", "full-width")}}
                    {{forms.input("last_name", 'id="username" placeholder="First Name"', "noCurve", "full-width")}}
                </div>
            </div>
            <div class="col-md-6">
                <img src="static/img/photo/login.png" />
            </div>
        </div>
      <div class="row">
         <br/><br/>
         <div class="col-md-6">
            <label>
                <div class="form-group input-group">
                    <span class="input-group-addon noCurve" ><div class="glyphicon glyphicon-envelope"  ></div></span>
                    {{forms.email_input("email", 'id="email" placeholder="Enter your email"', "", "full-width")}}
                </div>
            </label>
         </div>
         <div class="col-md-6">
            <div class="form-group input-group">
                <span class="input-group-addon noCurve" ><div class="glyphicon glyphicon-lock"  ></div></span>
                {{forms.password_input("password", 'id="password" placeholder="Password"', "", "full-width")}}
            </div>
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