<?php
$address = $_GET["address"];
$address2 = $_GET["address2"];
$city = $_GET["city"];
$state = $_GET["state"];
$zip = $_GET["zip"];

$page = 'search';

$markersArr = array();

$string = $address . $address2 . ',' . $city . ',' . $state . $zip;
$string = str_replace (" ", "+", urlencode($string));
$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$string."&sensor=false";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $details_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = json_decode(curl_exec($ch), true);

// If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
if ($response['status'] != 'OK') {
 return null;
}

$geometry = $response['results'][0]['geometry'];
 
$latitude = $geometry['location']['lat'];
$longitude = $geometry['location']['lng'];

$con = mysqli_connect('mysql1208.ixwebhosting.com','cmills8_rate','C8897163m!','cmills8_rating');
if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

mysqli_select_db($con,"cmills8_rating");

// Nearby results
$sqlNearby = "SELECT *, ( 3959 * acos( cos( radians('".$latitude."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$longitude."') ) + sin( radians('".$latitude."') ) * sin( radians( lat ) ) ) ) AS distance FROM properties HAVING distance < 10 ORDER BY distance LIMIT 0 , 10;";

$nearbyResult = mysqli_query($con,$sqlNearby);

include($_SERVER['DOCUMENT_ROOT'].'/includes/facebook.inc.php'); ?>

<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Rating Site</title>
    
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/header.inc.php'); ?>
    
    <style type="text/css">
			#resultsMap {
				width:500px;
				height:500px;
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
			.mr20 {
				margin-right:20px;
			}
		</style>

  </head>
  <body>
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/topbar.inc.php'); ?>
		<div class="container">
			<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/nav.inc.php'); ?>
		</div>
    <h1>Search Results</h1>
		      
      <div class="well">
      <?php if( mysqli_num_rows($nearbyResult) ) { ?>
      	<div class="well mr20">
        	<div class="left">
            <label for="resultsDistance">Show Results Within:</label>
            <select id="resultsDistance">
              <option value="5">5 Miles</option>
              <option value="10" selected>10 Miles</option>
              <option value="25">25 Miles</option>
              <option value="50">50 Miles</option>
              <option value="100">100 Miles</option>
            </select>
          </div>
          <div class="left mr20">
            <label for="resultsResult">Number of Results:</label>
            <select id="resultsResult">
              <option value="5">5</option>
              <option value="10" selected>10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>
          <div class="left btn btn-primary" id="updateResults">Update Results</div>
          <div class="clear"></div>
        </div>
        <?php } ?>
      
      	<div id="results" class="left" style="width:500px;">
				<?php
					if( ! mysqli_num_rows($nearbyResult) ) {
						echo '<p>No results found, <a href="/add.php">Add your property here</a>.</p>';
					} else {
						while($rowNearby = mysqli_fetch_array($nearbyResult)) {
							if($rowNearby['lat'] == $latitude && $rowNearby['lng'] == $longitude) {
								echo '<div class="result result-exact well" data-result="' . $rowNearby['propertyId'] . '">';
								echo '<h5>One exact match</h5>';
							} else {
								echo '<div class="result" data-result="' . $rowNearby['propertyId'] . '">';
							}
							echo '<p><strong><a href="/property/?property=' . $rowNearby['propertyId'] . '">' . $rowNearby['name'] . '</a></strong><br />';
							echo $rowNearby['address'] . '<br />';
							if($rowNearby['address2']) {
								echo $rowNearby['address2'] . '<br />';
							}
							echo $rowNearby['city'] . ', ' . $rowNearby['state'] . ' ' . $rowNearby['zip'];
							if($rowNearby['website']) {
								echo '<br />' . $rowNearby['website'] . '</p>';
							} else {
								echo '</p></div>';
							}
							// Google Maps API
							$resultArr = array();
							array_push($resultArr,$rowNearby['name'], $rowNearby['lat'], $rowNearby['lng']); 
							array_push($markersArr,$resultArr);
						}
					}
        ?>
      	</div>
        
        <div id="resultsMap" class="right"></div>
        
        <div class="clear"></div>
        
      </div>
      
      <a href="/" class="btn btn-primary">Search Again</a>
			
		</div>

    <?php include($_SERVER['DOCUMENT_ROOT'].'/includes/scripts.inc.php'); ?>
    
    <script>
			function setupPageBehavior() {
				$('#updateResults').bind('click', function() {
					$('#updateResults').html('Updating...').prop('disabled',true);
					var lat = <?php echo $latitude; ?>;
					var lng = <?php echo $longitude; ?>;
					var updateObj = {
						address: '<?php echo $_GET["address"]; ?>',
						address2: '<?php echo $_GET["address2"]; ?>',
						city: '<?php echo $_GET["city"]; ?>',
						state: '<?php echo $_GET["state"]; ?>',
						zip: '<?php echo $_GET["zip"]; ?>',
						distance: $('#resultsDistance').val(),
						results: $('#resultsResult').val()
					}
					$.ajax({
						url: '/controllers/search.php',
						method: 'POST',
						data: updateObj,
						dataType: 'json',
						success: function(response) {
							$('#updateResults').html('Update Results').prop('disabled',false);
							$('#results').html('');
							_response = response.properties;
							for(var i=0; i<_response.length;i++) {
								var _result = '';
								if(lat == _response[i].lat && lng == _response[i].lng) {
									_result += '<div class="result result-exact well" data-result="' + _response[i].propertyId + '">';
									_result += '<h5>One exact match</h5>';
								} else {
									_result += '<div class="result" data-result="' + _response[i].propertyId + '">';
								}
									_result += '<p><strong><a href="/property/?property=' + _response[i].propertyId + '">' + _response[i].name + '</a></strong><br />';
									_result += _response[i].address + '<br />';
									if(_response[i].address2 != '') {
										_result += _response[i].address2 + '<br />';
									}
									_result += _response[i].city + ' ,' + _response[i].state + ' ' + _response[i].zip;
									if(_response[i].website != '') {
										_result += '<br />' + _response[i].website;
									}
								$('#results').append(_result);
							}
							// update google maps api
						},
						error: function() {
							$('#updateResults').html('Update Results').prop('disabled',false);
						}
					});
				});
			}
			
			$(document).ready(setupPageBehavior);
		</script>

		<!--<script>
			function searchProperties(d) {
				// Search db for address matches
				var _latLng = d[0].geometry.location;
				var _lat = _latLng.lat;
				var _lng = _latLng.lng
				$.ajax({
					url: '/controllers/search.php?lat=' + _lat + '&;lng=' + _lng,
					//dataType: 'json',
					method: 'GET',
					success: function(response) {
						var _response = response;
						for(var i=0; i<_response.length; i++) {
							$('#results .well').append();	
						}
						$('#search').html('Search').prop('disabled',false);
					},
					error: function() {
						$('#results').stop().show(function() {
							$('#results .well').html('<div class="alert alert-error">There was a problem with your request, please try again later.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						});
						$('#search').html('Search').prop('disabled',false);
					}
				});
			}
			
			function setupPageBehavior() {
				// Search
				$('#search').bind('click', function() {
					// validate form
					
					// Clear previous results, if any
					$('#results').stop().slideUp(250, function() {
						$('#results .well').html('');
					});
					$(this).html('Searching...').prop('disabled',true);
					
					// Url encode address
					var _address = encodeURIComponent($('#searchAddress').val() + $('#searchAddress2').val() + ',' + $('#searchCity').val() + ',' + $('#searchState').val());					
					
					// Get lat/lng from Google Maps API
					$.ajax({
						url: 'http://maps.googleapis.com/maps/api/geocode/json?address=' + _address + '&sensor=true',
						method: 'GET',
						dataType: 'json',
						success: function(response) {
							if(response.status == "OK") {
								searchProperties(response.results);
							} else if(response.status == "ZERO_RESULTS") {
								$('#results .well').html('<div class="alert">No results were found, please check the address or <a href="/add.php">Add the Property</a>.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
							} else {
								$('#results .well').html('<div class="alert alert-error">There was a problem with your request, please try again.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
							}
						},
						error: function() {
							
						}
					});
				});
				
				// Browse
			}
			
			$(document).ready(setupPageBehavior);
		</script>-->
    <script>
					// Property Map
			function initialize() {
				// Setup Map
				var latLng = new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude ?>);
        var mapOptions = {
          center: latLng,
          zoom: 11,
          mapTypeId: google.maps.MapTypeId.ROADVIEW
        };
        var map = new google.maps.Map(document.getElementById("resultsMap"),
            mapOptions);
				
				// Setup info window
				/*var propertyInfo = '';
				propertyInfo += '<div class="propertyInfo">';
				propertyInfo += '<p><strong><?php echo $prop['name']; ?></strong><br />';
				propertyInfo += '<?php echo $prop['address']; ?><br />';
				propertyInfo += '<?php echo $prop['city']; ?>, <?php echo $prop['state']; ?> <?php echo $prop['zip']; ?></p>';
				<?php if($prop['website']) { ?>propertyInfo += '<?php echo $prop['website']; ?></p>';<?php } ?>
				*/
				
				<?php
				$i = 0;
				foreach ($markersArr as $row) {
					echo "var marker".$i." = new google.maps.Marker({";
					echo "position: new google.maps.LatLng (".$row[1].", ".$row[2]."),";
					echo "map: map,";
					echo "title: '".$row[0]."',";
					echo "});";
					
					/*echo "var propertyInfo".$i."= 'tester';";
					
					echo "var infowindow".$i." = new google.maps.InfoWindow({";
					echo "content: propertyInfo".$i."";
					echo "});";
					
					echo "google.maps.event.addListener(marker".$i.", 'click', function() {";
					echo "infowindow".$i.".open(map,marker".$row.");";
					echo "});";*/
					$i++;
				}
				?>

      }
      google.maps.event.addDomListener(window, 'load', initialize);

		</script>

  </body>
</html>
<?php mysqli_close($con); ?>