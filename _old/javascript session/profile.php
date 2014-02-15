<?php
	if(!$_GET['user']) {
		header('Location: /');
	}
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
				var userId = response.authResponse.userID;
				$('#navProfile a').prop('href','/profile.php?user=' + response.authResponse.userID);
				$('#navProfile').show();
				loadUser(userId);
	    } else {
				console.log('else');
				window.location.href = '/';
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
				<li><a href="/">Home</a></li>
				<li id="navProfile" class="dNone active"><a href="/profile.php">Profile</a></li>
			</ul>
		</div>
		
		<div class="well">
			<h2>My Reviews</h2>
			<div id="userInfo"><p>Loading User Information...</p></div>
			
		</div>

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

		<script src="/js/site.js"></script>

		<script type="text/javascript">
			$('#rateTabs a:first').tab('show'); // Select first tab
		</script>

  </body>
</html>