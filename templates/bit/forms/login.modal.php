{% import "macros/forms.html" as forms %}
<form name="signin" method="post" action="procsignin" class="form-inline login" role="form" action="#" method="post"> 
    <div class="modal-body">
		<div class="form-group">
            <div class="row">
                 <div class="col-md-6">            
                    <div class="form-group input-group ">
                        <span class="input-group-addon noCurve"><div class="glyphicon glyphicon-user"  ></div></span>
                        {{forms.input("username", 'id="username" placeholder="User Name"', "", "full-width")}}
                    </div>
                    <br/><br/>
                    <div class="form-group input-group">
                        <span class="input-group-addon noCurve" ><div class="glyphicon glyphicon-lock"  ></div></span>
                        {{forms.password_input("password", 'id="password" placeholder="Re-enter Password"', "", "full-width")}}
                    </div>
                </div>
                <div style="col-md-6">
                    <img src = "static/img/photo/login.png" />
                </div>
            </div>
      </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default navbar-left" value="Register" name="register" >Register</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <input name="sigin" type="submit" value="Login" class="btn btn-info"  /> 
  </div>
</form>