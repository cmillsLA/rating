<?php
require_once("facebook.php");

  $config = array();
  $config['appId'] = '221418578022709';
  $config['secret'] = 'c1e95983ffea49cf086ea274481b6015';
  $config['fileUpload'] = false; // optional

  $facebook = new Facebook($config);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Rating Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
		
		<style type="text/css">
			.dNone { display:none; }
		</style>
		
		<script>
			var userId = '';
		</script>
		
  </head>
  <body>

	<!--<div id="fb-root"></div>
	<script>
	
	  window.fbAsyncInit = function() {
	  FB.init({
	    appId      : '221418578022709', // App ID
	    channelUrl : '//rating.chrismills.la/channel.html', // Channel File
	    status     : true, // check login status
	    cookie     : true, // enable cookies to allow the server to access the session
	    xfbml      : true  // parse XFBML
	  });

	  FB.Event.subscribe('auth.authResponseChange', function(response) {
			console.log(response);
	    if (response.status === 'connected') {
				$('#navProfile a').prop('href','/profile.php?user=' + response.authResponse.userID);
				$('#navProfile').show();
	    } else {
	      $('#navProfile').hide();
	    }
	  });
	  };

	  // Load the SDK asynchronously
	  (function(d){
	   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	   if (d.getElementById(id)) {return;}
	   js = d.createElement('script'); js.id = id; js.async = true;
	   js.src = "//connect.facebook.net/en_US/all.js";
	   ref.parentNode.insertBefore(js, ref);
	  }(document));

	</script>

	<fb:login-button show-faces="true" width="200" max-rows="1"></fb:login-button>-->
	
		<div class="container">
			<ul class="nav nav-pills">
				<li class="active"><a href="/">Home</a></li>
				<li id="navProfile" data-user="" class="dNone"><a href="/profile.php">Profile</a></li>
			</ul>
		</div>
    <h1></h1>
		
		<div class="hero">
			<ul class="nav nav-tabs" id="rateTabs">
			  <li><a href="#rateSearch" data-toggle="tab">Search</a></li>
			  <li><a href="#rateBrowse" data-toggle="tab">Browse by Area</a></li>
			</ul>
			
			<div class="tab-content">
			  <div class="tab-pane" id="rateSearch">Search Content</div>
			  <div class="tab-pane" id="rateBrowse">Browse Content</div>
			</div>
			
		</div>

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

		<script type="text/javascript">
			$('#rateTabs a:first').tab('show'); // Select first tab
		</script>

  </body>
</html>