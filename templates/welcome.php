{% extends "base.php" %}   
{% import "macros/forms.html" as forms %}
{% block scripts %}
<script src="static/js/src/slideshow/modernizr-2.6.2-respond-1.1.0.min.js"></script>
<script src="static/js/src/slideshow/jquery.fitvids.js"></script>
<script src="static/js/src/slideshow/jquery.sequence-min.js"></script>
<script src="static/js/src/slideshow/jquery.bxslider.js"></script>
<script src="static/js/src/slideshow/main-menu.js"></script>
<script src="static/js/src/slideshow/template.js"></script>
<script src="static/js/src/TimeCircles.js"></script>
<script src="static/js/src/welcome.js"></script>
<script src="static/js/src/registration_login_form.js" ></script>
{% endblock %} 
{% block content %}
<div id="headerwrap">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<h1>Make your University life<br/>
					Even better with TattleTale</h1>					
                    {{forms.user_invite()}}		
					<p style="color:white;">Get on our mailing list today, to get news on releases and updates</p>	
				</div><!-- /col-lg-6 -->
				<div class="col-lg-6">
					<img class="img-responsive" src="static/img/photo/ipad-hand.png" alt="">					
				</div><!-- /col-lg-6 -->
			</div><!-- /row -->
		</div><!-- /container -->
	</div><!-- /headerwrap -->
	
		<!-- Homepage Slider -->
        <div class="homepage-slider">
        	<div id="sequence">
				<ul class="sequence-canvas">
					<!-- Slide 1 -->
					<li class="bg4">
						<!-- Slide Title -->
						<h2 class="title">Schedule Classes</h2>
						<!-- Slide Text -->
						<h3 class="subtitle">Scheduling classes as never been so easy, with Tattle you have you schedule at you finger tip</h3>
						<!-- Slide Image -->
						<img class="slide-img" src="static/img/photo/homepage-slider/slide1.png" alt="Slide 1" />
					</li>
					<!-- End Slide 1 -->
					<!-- Slide 2 -->
					<li class="bg3">
						<!-- Slide Title -->
						<h2 class="title">Can't Find Somewhere?</h2>
						<!-- Slide Text -->
						<h3 class="subtitle">No problem we have you covered with Tattle Tales easy location finder</h3>
						<!-- Slide Image -->
						<img class="slide-img" src="static/img/photo/homepage-slider/slide2.png" alt="Slide 2" />
					</li>
					<!-- End Slide 2 -->
					<!-- Slide 3 -->
					<li class="bg1">
						<!-- Slide Title -->
						<h2 class="title">Feature Rich</h2>
						<!-- Slide Text -->
						<h3 class="subtitle">Huge amount of features and over 6 different app functions in one!</h3>
						<!-- Slide Image -->
						<img class="slide-img" src="static/img/photo/homepage-slider/slide3.png" alt="Slide 3" />
					</li>
					<!-- End Slide 3 -->
				</ul>
				<div class="sequence-pagination-wrapper">
					<ul class="sequence-pagination">
						<li>1</li>
						<li>2</li>
						<li>3</li>
					</ul>
				</div>
			</div>
        </div>
        <!-- End Homepage Slider -->
	<div class="container">
		<div class="row mt centered">
			<div class="col-lg-6 col-lg-offset-3">
				<h1>Your university life<br/>made simple.</h1>
				<h3 class= "text-left">Let us help you make your university your playground, with Tattle you'll get all you need to know when you need to know!.</h3>
			</div>
		</div><!-- /row -->
		
		<div class="row mt centered">
			<div class="col-lg-4">
				<img src="static/img/icons/ic_scheduling.png" width="180" alt="">
				<h4>Poor Time Management?,Inaccurate Schedules?</h4>
				<p class= "text-left">Never miss a class, bus or even special event again. With Tattle Tale, we got you covered by keeping you up to date with class, clubs and your university events and activities.</p>
			</div><!--/col-lg-4 -->

			<div class="col-lg-4">
				<img src="static/img/icons/ic_location.png" width="180" alt="">
				<h4>Don't Know Where You Are?, Or Where To Go?</h4>
				<p class= "text-left">
				Tattle Tale got you covered. With Tattle Tale, you can find places on campus such as the cheapest to the most expensive restaurants, as well as classes amongst other places. Tattle Tale is your ultimate guide.
				</p>

			</div><!--/col-lg-4 -->

			<div class="col-lg-4">
				<img src="static/img/icons/ic_mayday.png" width="180" alt="">
				<h4>Do You Have a Burning Question?, Or a Burning Answer?</h4>
				<p>Then get blazing answers right at your fingertips. With Tattle Tale, you can post your questions in a class, club or general forum and get response from your peers and advisors.</p>

			</div><!--/col-lg-4 -->
		</div><!-- /row -->
		<hr>
	</div><!-- /container -->
		<div class="container">
		<div class="row mt centered">
			<div ><!--class="col-lg-6 col-lg-offset-3"-->
				<h1>Just You Wait. It'll blow your mind.</h1>
				<h3>Tattle Tale is a mobile application which innovates the university experience. The application is a social network which allows students to interact with their teachers and classmates, schedule and locate their classes, clubs and society and find different places on campus, using an interactive map.</h3>
			<div id="DateCountdown" class =" hidden-xs" data-date="2014-08-01 00:00:00" style="width: 100%;"></div>
			</div>
		</div><!-- /row -->	
	</div><! --/container -->
	<div class="container">
		<hr>
		<div class="row centered">
			<h1>Tattle Tale for Business</h2>
			<div class="row mt centered">
			<div class="col-lg-4" >
				<img src="static/img/photo/student-exam.jpg" width="380" height="200" alt="">
				<h3 class= "text-left">About</h3>
				<p class= "text-left">Get your product or on campus business out there to over 1,000 people that are searching on Tattle Tale.
				</p><button type="submit" class="btn btn-info btn-xs" target = "#" align = "center">Learn More</button>
			</div><!--/col-lg-4 -->

			<div class="col-lg-4" >
				<img src="static/img/photo/featurelist.png" width="380" height="200" alt="">
				<h3 class= "text-left">Get listed</h3>
				<p class= "text-left">Take control of your business listing share what you want with your customers.
				</p><button type="submit" class="btn btn-info btn-xs" target = "#" align = "center">Learn More</button>
			</div><!--/col-lg-4 -->
			<div class="col-lg-4">
				<img src="static/img/photo/girlphone.jpg" width="380" height="200" alt="">
				<h3 class= "text-left">Advertise</h3>
				<p class= "text-left">Your business promotions and offers are never more reaching than when you have them on Tattle Tale.
				</p><button type="submit" class="btn btn-info btn-xs" target = "#" align = "center">Learn More</button>
			</div><!--/col-lg-4 -->
		</div><!-- /row -->
		</div><!-- /row -->
		<hr>
	</div><!-- /container -->
	<div class="container">
		<div class="row centered">
			<div class="col-lg-6 col-lg-offset-3">
                {{forms.user_invite()}}					
			</div>
			<div class="col-lg-3"></div>
		</div><!-- /row -->
		<hr>
	</div><!-- /container -->

	<div class="container">
		<div class="row mt centered">
			<div class="col-lg-6 col-lg-offset-3">
				<h1>Hey! meet our<br/>Our Awesome Team.</h1>
				<h3>Checkmate, Life is much more easier with Tattle Tale.</h3>
			</div>
		</div><!-- /row -->
		
		<div class="row mt centered">
			<div class="col-lg-4">
				<img class="img-circle" src="static/img/photo/pic2.jpg" width="140" alt="">
				<h4>Oshane Bailey</h4>
				<p></p>
				<p><i class="glyphicon glyphicon-send"></i> <i class="glyphicon glyphicon-phone"></i> <i class="glyphicon glyphicon-globe"></i></p>
			</div><!--/col-lg-4 -->

			<div class="col-lg-4">
				<img class="img-circle" src="static/img/photo/pic1.jpg" width="140" alt="">
				<h4>Andrew Gray</h4>
				<p></p>
				<p><i class="glyphicon glyphicon-send"></i> <i class="glyphicon glyphicon-phone"></i> <i class="glyphicon glyphicon-globe"></i></p>
			</div><!--/col-lg-4 -->

			<div class="col-lg-4">
				<img class="img-circle" src="static/img/photo/pic3.jpg" width="140" alt="">
				<h4>Danique Hayden</h4>
				<p></p>
				<p><i class="glyphicon glyphicon-send"></i> <i class="glyphicon glyphicon-phone"></i> <i class="glyphicon glyphicon-globe"></i></p>
			</div><!--/col-lg-4 -->
		</div><!-- /row -->
	</div><!-- /container -->
{% endblock %}

