{% extends "base.php" %}    

{% block content %}
<!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class=""></li>
        <li data-target="#myCarousel" data-slide-to="1" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="2" class=""></li>
      </ol>
      <div class="carousel-inner">
        <div class="item">
          <img data-src="holder.js/900x500/auto/#777:#7a7a7a/text:First slide" alt="First slide" src="static/img/photo/device.png">
          <div class="container">
            <div class="carousel-caption">
              <h1>Tattle Tale is coming soon</h1>
              <p>Sign up now for Tattle Tale pre-launch event, July 30, 2014</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
            </div>
          </div>
        </div>
        <div class="item active">
          <img data-src="holder.js/900x500/auto/#666:#6a6a6a/text:Second slide" alt="Second slide" src="static/img/photo/tattletale.png">
          <div class="container">
            <div class="carousel-caption">
              <h1>Tattle Tale, Makes Your University Your Yniversity</h1>
              <p>Keep yourself update with class schedules, bus schedule, campus events and so much more. You will never miss a beat with Tattle Tale at your fingertips</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img data-src="holder.js/900x500/auto/#555:#5a5a5a/text:Third slide" alt="Third slide" src="static/img/photo/student-exam.jpg">
          <div class="container">
            <div class="carousel-caption">
              <h1>Tattle Tale, Improve Your Punctuatilty</h1>
              <p>Keep yourself update with class schedules, bus schedule, campus events and so much more. You will never miss a beat with Tattle Tale at your fingertips.</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div><!-- /.carousel -->



    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="marketing">

      <!-- Three columns of text below the carousel -->
      <div class="row">
        <div class="col-lg-4">
          <img class="img-circle" data-src="holder.js/140x140" alt="140x140" src="static/img/icons/ic_scheduling.png" style="width: 140px; height: 140px;">
          <h2>Poor Time Management, Inaccurate Schedules</h2>
          <p>Never miss a class, bus or even special event again. With Tattle Tale, we got you covered by keeping you up to date with class, clubs and your university events and activities.</p>
          <p><a class="btn btn-default" href="#" role="button">View details »</a></p>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4">
          <img class="img-circle" data-src="holder.js/140x140" alt="140x140" src="static/img/icons/ic_mayday.png" style="width: 140px; height: 140px;">
          <h2>Don't Know Where To Go?</h2>
          <p>Tattle Tale got you covered. With Tattle Tale, you can find places on campus such as cheapest to the most expensivee restaurants, as well as classes, bus stops, agmonst other places. Tattle Tale is your ultimate university guide</p>
          <p><a class="btn btn-default" href="#" role="button">View details »</a></p>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4">
          <img class="img-circle" data-src="holder.js/140x140" alt="140x140" src="static/img/icons/ic_location.png" style="width: 140px; height: 140px;">
          <h2>Do You Have a Burning Question?</h2>
          <p>Then get blazing answers right at your fingertips. With Tattle Tale, you can post your questions in a class, club or general forum and get response from your peers and advisors.</p>
          <p><a class="btn btn-default" href="#" role="button">View details »</a></p>
        </div><!-- /.col-lg-4 -->
      </div><!-- /.row -->


      <!-- START THE FEATURETTES -->

      <div class="row featurette">
        <div class="col-md-7">
          <h2 class="featurette-heading">Just You Wait. <span class="text-muted">It'll blow your mind.</span></h2>
          <p class="lead">Tattle Tale is a mobile application which innovates the university experience. The application is a social network which allows students to interact with their teachers and classmates, schedule and locate their classes, clubs and society and find different places on campus, using an interactive map.</p>
        </div>
        <div class="col-md-5">
             <iframe class="side-display" src="//w2.countingdownto.com/496881" frameborder="0"></iframe>
        </div>
      </div>
        
     <div class="row featurette">
        <div class="col-md-5">
          <img class="featurette-image img-responsive" data-src="holder.js/500x500/auto" alt="500x500" src="static/img/photo/maxtime.png">
        </div>
        <div class="col-md-7">
          <h2 class="featurette-heading">Be Punctual, <span class="text-muted">Reduce Time Spent Doing Unwanted Stuff.</span></h2>
          <p class="lead">Tattle Tale make everything easier and less time consuming. No more asking questions to random people, where to find this and where to find that. With Tattle Tale, your university directory is at your fingertips</p>
        </div>
      </div>


      <div class="row featurette">
        <div class="col-md-7">
          <h2 class="featurette-heading">Oh yeah, it's that good. <span class="text-muted">See for yourself.</span></h2>
          <p class="lead">Students may comment in class forum, of which they would be a part if registered to that class. The lecturers can then view these conversations and offer clarification where necessary. Both classes and extra-curricular activities may be scheduled on Tattle Tale; thus, students may be alerted of any resulting clash.</p>
        </div>
        <div class="col-md-5">
          <iframe class="side-display" src="//www.youtube.com/embed/pIgSeB7IzBg" frameborder="0" allowfullscreen></iframe>
        </div>
      </div>


      <div class="row featurette">
        <div class="col-md-5">
          <img class="featurette-image img-responsive" data-src="holder.js/500x500/auto" alt="500x500" src="static/img/photo/girlphone.jpg">
        </div>
        <div class="col-md-7">
          <h2 class="featurette-heading">Checkmate, <span class="text-muted">Life is much more easier with Tattle Tale.</span></h2>
          <p class="lead">Tattle Tale is a unique platform as it is customizable to suit the needs college student globally. It is an application which would allow an easier transition into the academic and social aspect of the college experience. Tattle Tale has an edge over its competitors in that users can obtain information of their own college or that of others. This inter-college feature of the application is particularly beneficial to exchange students and visiting lecturers. </p>
        </div>
      </div>
{% endblock %}

