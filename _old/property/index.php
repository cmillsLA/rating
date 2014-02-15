<?php
	$page = 'property';

	$propertyId = $_GET['property'];
	
	$con = mysqli_connect('mysql1208.ixwebhosting.com','cmills8_rate','C8897163m!','cmills8_rating');
	if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

	mysqli_select_db($con,"cmills8_rating");
	
	// Get business info
	$sqlInv="SELECT * FROM properties WHERE propertyId = '".$propertyId."'";
	
	$invoiceResult = mysqli_query($con,$sqlInv);
	
	while($rowInv = mysqli_fetch_array($invoiceResult)) {
		$prop['name'] = $rowInv['name'];
		$prop['address'] = $rowInv['address'];
		$prop['address2'] = $rowInv['address2'];
		$prop['city'] = $rowInv['city'];
		$prop['state'] = $rowInv['state'];
		$prop['zip'] = $rowInv['zip'];
		$prop['website'] = $rowInv['website'];
		$prop['lat'] = $rowInv['lat'];
		$prop['lng'] = $rowInv['lng'];
		$prop['propertyId'] = $rowInv['propertyId'];
		$prop['avgRating'] = $rowInv['avgRating'];
	}

	// If property is not found, redirect home
	if(count($prop['propertyId']) < 1) {
		header('location: /');
	}
?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/facebook.inc.php'); ?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Rating Site</title>
    
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/header.inc.php'); ?>
    
    <style type="text/css">
			#propertyMap {
				width:400px;
				height:400px;
			}
			.left {
				float:left;
			}
			.right {
				float:right;
			}
			.clear {
				clear:both;
			}
			.dNone {
				display:none;
			}
		</style>
    
    <script>
			var _propertyId = <?php echo $prop['propertyId']; ?>
		</script>

  </head>
  <body>
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/topbar.inc.php'); ?>
		<div class="container">
			<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/nav.inc.php'); ?>
		</div>
    
    <div class="container">
    
    	<div class="left">
				<?php if($prop['name']) { ?>
        <h1><?php echo $prop['name']; ?></h1>
        <?php } ?>
        
        <h2><?php echo $prop['address'] ?></h2>
        <h3><?php echo $prop['city'] ?>, <?php echo $prop['state'] ?> <?php echo $prop['zip'] ?></h3>
        
        <?php if($prop['website']) { ?>
        <p><?php echo $prop['website']; ?></p>
        <?php } ?>
        
        <div class="right">
					<?php
						$avgRating = $prop['avgRating']; 
						$avgCSS = str_replace('.', '', $avgRating);
					?>
					<div id="overallRating" class="rating rate<?php echo $avgCSS; ?>"></div>
        </div>
      </div>
      
      <div class="right">
      	<div id="propertyMap" class="dNone"></div>
      </div>
      
      <div class="clear"></div>
      
      <div id="reviews">
				<?php
          // Get reviews
          $reviews_arr = array();
          
          $sqlReviews="SELECT * FROM reviews WHERE propertyId = '".$propertyId."'";
          
          $reviewsResult = mysqli_query($con,$sqlReviews);
          
          while($rowReview = mysqli_fetch_array($reviewsResult)) {
            // add username and photo
            echo '<div class="review">';
            echo '<div class="reviewRating">';
            echo $rowReview['rating'];
            echo '</div>';
            echo '<p>';
            echo $rowReview['review'];
            echo '</p>';
            echo '</div>';
						array_push($reviews_arr,$rowReview['rating']);
          }
        ?>
      </div>
      
      <hr />
      
      <!-- disable if review has already reviewed -->
      <div class="btn btn-primary" id="reviewProperty">Write Review</div>
      
      <div id="writeReview" class="dNone">
      	<form id="newReview">
        
        	<label for="reviewTitle">Review Title</label>
          <input type="text" id="reviewTitle" />
          
          <label for="reviewRating">Rating</label>
          
					<ul id="rating" class="rating right unrated" data-rating="0">
						<li id="rate1"></li>
						<li id="rate2"></li>
						<li id="rate3"></li>
						<li id="rate4"></li>
						<li id="rate5"></li>
					</ul>
          
          <label for="reviewTime">Time spent living at address</label>
          
          <select id="reviewTime">
          	<option value="0">0 Years</option>
            <option value="1">1 Years</option>
            <option value="2">2 Years</option>
            <option value="3">3 Years</option>
            <option value="4">4 Years</option>
            <option value="5">5+ Years</option>
          </select>
          
          <select id="reviewTimeMonths">
          	<option value="0">0 Months</option>
            <option value="1">1 Months</option>
            <option value="2">2 Months</option>
            <option value="3">3 Months</option>
            <option value="4">4 Months</option>
            <option value="5">5 Months</option>
            <option value="6">6 Months</option>
            <option value="7">7 Months</option>
            <option value="8">8 Months</option>
            <option value="9">9 Months</option>
            <option value="10">10 Months</option>
            <option value="11">11 Months</option>
          </select>
          
          <label for="reviewReview"></label>
          <textarea id="reviewReview"></textarea>
          
          <div class="btn btn-primary" id="submitReview">Submit</div>
          
        </form>
      </div>
      
    </div>

    <?php include($_SERVER['DOCUMENT_ROOT'].'/includes/scripts.inc.php'); ?>
    
    <script>
			function defaultError() {
				$('body').prepend('<div class="alert alert-error">There was a problem with your request, please try again later.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			function refreshReviews(_propertyId) {
				$.ajax({
					url: '/controllers/refreshReviews.php?property=' + _propertyId,
					method: 'GET',
					dataType: 'json',
					success: function(response) {
						$('#reviews').html('');
						var _response = response.userReviews;
						var _rating = 0;
						for(var i=0; i<_response.length; i++) {
							var _review = '';
							_review += '<div class="review">';
							_review += '<div class="reviewRating">';
							_review += _response[i].rating;
							_review += '</div>';
							_review += '<p>';
							_review += _response[i].review;
							_review += '</p>';
							_review += '</div>';
							$('#reviews').append(_review);
							_rating += parseInt(_response[i].rating);
						}
						var _avgRating = response.avgRating;
						var _avgCSS = _avgRating.replace('.','');
						$('#overallRating').prop('class', 'rating rating' + response.avgRating);
					},
					error: function() {
						$('#reviews').prepend('<div class="alert alert-error">Could not refresh reviews feed, please manually refresh your browser. <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				});
			}
		
			function submitReview() {
				$('.alert').remove();
				// validation
				var reviewObj = {
					title: $('#reviewTitle').val(),
					rating: $('#rating').data('rating'),
					review: $('#reviewReview').val(),
					userId: null,
					propertyId: _propertyId,
					date: new Date(),
					years: $('#reviewYears').val(),
					months: $('#reviewMonths').val(),
					facebookUrl: null
				}
				$.ajax({
					url: '/controllers/addReview.php',
					method: 'POST',
					data: reviewObj,
					dataType: 'json',
					success: function(response) {
						$('body').prepend('<div class="alert alert-success">Thanks, your review has been successfully added.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('#reviewProperty').unbind('click').remove();
						$('#writeReview').slideUp(250);
						refreshReviews(_propertyId);
					},
					error: function() {
						defaultError();
					}
				});
			}
		
			function setupPageBehavior() {
				$('#reviewProperty').bind('click', function() {
					$(this).prop('disabled',true);
					$('#writeReview').slideDown(250);
				});
				$('#submitReview').bind('click', function() {
					submitReview();
				});
			}
			
			// Property Map
			function initialize() {
				// Setup Map
				var latLng = new google.maps.LatLng(<?php echo $prop['lat']; ?>, <?php echo $prop['lng']; ?>);
        var mapOptions = {
          center: latLng,
          zoom: 18,
          mapTypeId: google.maps.MapTypeId.SATELLITE
        };
        var map = new google.maps.Map(document.getElementById("propertyMap"),
            mapOptions);
				
				// Setup info window
				var propertyInfo = '';
				propertyInfo += '<div id="propertyInfo">';
				propertyInfo += '<p><strong><?php echo $prop['name']; ?></strong><br />';
				propertyInfo += '<?php echo $prop['address']; ?><br />';
				propertyInfo += '<?php echo $prop['city']; ?>, <?php echo $prop['state']; ?> <?php echo $prop['zip']; ?></p>';
				<?php if($prop['website']) { ?>propertyInfo += '<?php echo $prop['website']; ?></p>';<?php } ?>
				
				var infowindow = new google.maps.InfoWindow({
						content: propertyInfo
				});
				
				// Setup marker
				var marker = new google.maps.Marker({
					position: latLng,
					map: map,
					title: '<?php echo $prop['name']; ?>'
				});
				
				// Bind click event
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map,marker);
				});
				
				// Open by default
				infowindow.open(map,marker);
				
				$('#propertyMap').fadeIn(250);

      }
      google.maps.event.addDomListener(window, 'load', initialize);
			
			// Bind elements
			$(document).ready(function() {
				setupPageBehavior();
			});
		</script>
    
  </body>
</html>
<?php mysqli_close($con); ?>