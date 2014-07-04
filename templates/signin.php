{% extends "base.php" %}  

{% block content %}
<div class="container" style="margin-top: 50px;">
    <div class="row mt" >
        <div class="col-lg-3 opci">
            <img class="img-responsive" src="assets/img/logo banner.png" alt=""><hr style = "color:white;" />
            <form id="defaultForm" method="post" action="target.php" >
              <div class="form-group-lg">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><div class="glyphicon glyphicon-user"></div></span>
                        <input type="text" class="form-control noCurve" name="username" placeholder="Username" autofocus>
                    </div>
                </div>
                <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><div class="glyphicon glyphicon-lock"></div></span>
                    <input type="password" class="form-control noCurve" name="password" placeholder="Password"><br />
                </div>
                </div><br />
                <button type="submit" class="btnNone btn-info" ><div class="glyphicon glyphicon glyphicon-ok"></div> Log in</button>
              </div>
              <p><a href = "#" style="color:white;">Forget your password<i class="glyphicon glyphicon-question-sign"></i></a></p>
             </form>
            <div id="label"><button type="submit" class="btnNone btn-lg btn-info btn-block" >Sign Up</button></div>
        </div><!-- /col-lg-6 -->
    </div><!-- /row -->
</div>
{% endblock %}