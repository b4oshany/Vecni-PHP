<link href="static/css/home.css" rel="stylesheet" type="text/css" />
<?php require_once "header.php" ?>
<div class="container-fluid">
    <div class="row" id="main"  >
        <div class="col-md-9" id="main-container" >
            
            <div class="panel panel-primary">
              <div class="panel-body">
                <form name="post">
                    <div class="input-group">
                        <span class="input-group-addon"></span>                        
                      <input type="text" class="form-control" name="title" placeholder="Post Title">
                    </div><br/>
                    <textarea id="posting" class="form-control" rows="3" placeholder="What's Kicking?"></textarea><br/>
                    <input type="submit" class="btn btn-primary" value="Share" />                    
                    <button type="button" class="btn btn-default">
                      <span class="glyphicon glyphicon-camera"></span>  Photo
                    </button>                 
                </form>   
              </div>
            </div>
        </div>
        <div class="col-md-3" id="sidebar" >
            <div id="active-friends" class="panel panel-primary">
              <div class="panel-heading">Active Friends</div>
              <div class="panel-body">
                <ul class="list-group">
                  <li class="list-group-item">Cras justo odio<span class="badge">14</span></li>
                  <li class="list-group-item">Dapibus ac facilisis in<span class="badge">14</span></li>
                  <li class="list-group-item">Morbi leo risus<span class="badge">14</span></li>
                  <li class="list-group-item">Porta ac consectetur ac<span class="badge">14</span></li>
                  <li class="list-group-item">Vestibulum at eros<span class="badge">14</span></li>
                </ul>
              </div>
            </div>
        </div>
    </div>
</div>
    
    