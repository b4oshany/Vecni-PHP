{% extends "base.php" %}    
{% block scripts %}
<script src="static/js/src/TimeCircles.js"></script>
<script src="static/js/src/welcome.js"></script>  
{% endblock %} 
{% block content %}
<div id="headerwrap">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<h1>Make your University life<br/>
					Even better with TattleTale</h1>
					<form class="form-inline" role="form">
					  <div class="form-group">
					    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter your email address">
					  </div>
					  <button type="submit" class="btn btn-warning btn-lg">Invite Me!</button>
					</form>					
				</div><!-- /col-lg-6 -->
				<div class="col-lg-6">
					<img class="img-responsive" src="static/img/photo/ipad-hand.png" alt="">					
				</div><!-- /col-lg-6 -->
			</div><!-- /row -->
		</div><!-- /container -->
	</div><!-- /headerwrap -->
	
	<div class="container">
					<div id="DateCountdown" class =" hidden-xs" data-date="2014-08-01 00:00:00" style="width: 100%;"></div>
		<div class="row mt centered">
			<div class="col-lg-6 col-lg-offset-3">
				<h1>Your Landing Page<br/>Looks Wonderful Now.</h1>
				<h3>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</h3>
			</div>
		</div><!-- /row -->
		
		<div class="row mt centered">
			<div class="col-lg-4">
				<img src="static/img/icons/ic_scheduling.png" width="180" alt="">
				<h4>Poor Time Management, Inaccurate Schedules</h4>
				<p>Never miss a class, bus or even special event again. With Tattle Tale, we got you covered by keeping you up to date with class, clubs and your university events and activities.</p>
			</div><!--/col-lg-4 -->

			<div class="col-lg-4">
				<img src="static/img/icons/ic_location.png" width="180" alt="">
				<h4>Don't Know Where To Go?</h4>
				<p>Tattle Tale got you covered. With Tattle Tale, you can find places on campus such as cheapest to the most expensivee restaurants, as well as classes, bus stops, agmonst other places. Tattle Tale is your ultimate university guide</p>

			</div><!--/col-lg-4 -->

			<div class="col-lg-4">
				<img src="static/img/icons/ic_mayday.png" width="180" alt="">
				<h4>Do You Have a Burning Question?</h4>
				<p>Then get blazing answers right at your fingertips. With Tattle Tale, you can post your questions in a class, club or general forum and get response from your peers and advisors.</p>

			</div><!--/col-lg-4 -->
		</div><!-- /row -->
	</div><!-- /container -->
	
	<div class="container">
		<hr>
		<div class="row centered">
			<div class="col-lg-6 col-lg-offset-3">
				<form class="form-inline" role="form">
				  <div class="form-group">
				    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter your email address">
				  </div>
				  <button type="submit" class="btn btn-warning btn-lg">Invite Me!</button>
				</form>					
			</div>
			<div class="col-lg-3"></div>
		</div><!-- /row -->
		<hr>
	</div><!-- /container -->
	
	<div class="container">
		<div class="row mt centered">
			<div class="col-lg-6 col-lg-offset-3">
				<h1>Just You Wait. It'll blow your mind.</h1>
				<h3>Tattle Tale is a mobile application which innovates the university experience. The application is a social network which allows students to interact with their teachers and classmates, schedule and locate their classes, clubs and society and find different places on campus, using an interactive map.</h3>
			</div>
		</div><!-- /row -->
	
		<! -- CAROUSEL -->
		<div class="row mt centered">
			<div class="col-lg-6 col-lg-offset-3">
				<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
				  <!-- Indicators -->
				  <ol class="carousel-indicators">
				    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
				    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
				    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
				  </ol>
				
				  <!-- Wrapper for slides -->
				  <div class="carousel-inner">
				    <div class="item active">
				      <img src="static/img/photo/p01.png" alt="">
				    </div>
				    <div class="item">
				      <img src="static/img/photo/p02.png" alt="">
				    </div>
				    <div class="item">
				      <img src="static/img/photo/p03.png" alt="">
				    </div>
				  </div>
				</div>			
			</div><!-- /col-lg-8 -->
		</div><!-- /row -->
	</div><! --/container -->
	
	<div class="container">
		<hr>
		<div class="row centered">
			<div class="col-lg-6 col-lg-offset-3">
				<form class="form-inline" role="form">
				  <div class="form-group">
				    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter your email address">
				  </div>
				  <button type="submit" class="btn btn-warning btn-lg">Invite Me!</button>
				</form>					
			</div>
			<div class="col-lg-3"></div>
		</div><!-- /row -->
		<hr>
	</div><!-- /container -->

	<div class="container">
		<div class="row mt centered">
			<div class="col-lg-6 col-lg-offset-3">
				<h1>Our Awesome Team.<br/>Design Lovers.</h1>
				<h3>Checkmate, Life is much more easier with Tattle Tale.</h3>
			</div>
		</div><!-- /row -->
		
		<div class="row mt centered">
			<div class="col-lg-4">
				<img class="img-circle" src="static/img/photo/pic1.jpg" width="140" alt="">
				<h4>Oshane Bailey</h4>
				<p>It's a me, oshane bailey.</p>
				<p><i class="glyphicon glyphicon-send"></i> <i class="glyphicon glyphicon-phone"></i> <i class="glyphicon glyphicon-globe"></i></p>
			</div><!--/col-lg-4 -->

			<div class="col-lg-4">
				<img class="img-circle" src="static/img/photo/pic2.jpg" width="140" alt="">
				<h4>Andrew Gray</h4>
				<p>I like pancakes.</p>
				<p><i class="glyphicon glyphicon-send"></i> <i class="glyphicon glyphicon-phone"></i> <i class="glyphicon glyphicon-globe"></i></p>
			</div><!--/col-lg-4 -->

			<div class="col-lg-4">
				<img class="img-circle" src="static/img/photo/pic3.jpg" width="140" alt="">
				<h4>Angelica Finning</h4>
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever.</p>
				<p><i class="glyphicon glyphicon-send"></i> <i class="glyphicon glyphicon-phone"></i> <i class="glyphicon glyphicon-globe"></i></p>
			</div><!--/col-lg-4 -->
		</div><!-- /row -->
	</div><!-- /container -->
{% endblock %}

