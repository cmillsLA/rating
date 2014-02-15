<?php $page = 'add'; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/facebook.inc.php'); ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Rating Site</title>
    
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/header.inc.php'); ?>
		
  </head>
  <body>
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/topbar.inc.php'); ?>

		<div class="container">
			<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/nav.inc.php'); ?>
		</div>
		
		<div class="well">
			<h2>Add a Property</h2>
			
      <form id="addPropertyForm">
      
      	<label for="addName">Property Name</label>
        <input type="text" id="addName" />
      
      	<label for="addAddress">Address</label>
        <input type="text" id="addAddress" />
        
        <label for="addAddress2">Address 2</label>
        <input type="text" id="addAddress2" />	
        
        <label for="addCity">City</label>
        <input type="text" id="addCity" />	
        
        <label for="addState">State</label>
        <select id="addState">
        	<option selected value="default">Select State</option>
          <option value="AL">AL</option>
          <option value="AK">AK</option>
          <option value="AZ">AZ</option>
          <option value="AR">AR</option>
          <option value="CA">CA</option>
          <option value="CO">CO</option>
          <option value="CT">CT</option>
          <option value="DE">DE</option>
          <option value="DC">DC</option>
          <option value="FL">FL</option>
          <option value="GA">GA</option>
          <option value="HI">HI</option>
          <option value="ID">ID</option>
          <option value="IL">IL</option>
          <option value="IN">IN</option>
          <option value="IA">IA</option>
          <option value="KS">KS</option>
          <option value="KY">KY</option>
          <option value="LA">LA</option>
          <option value="ME">ME</option>
          <option value="MD">MD</option>
          <option value="MA">MA</option>
          <option value="MI">MI</option>
          <option value="MN">MN</option>
          <option value="MS">MS</option>
          <option value="MO">MO</option>
          <option value="MT">MT</option>
          <option value="NE">NE</option>
          <option value="NV">NV</option>
          <option value="NH">NH</option>
          <option value="NJ">NJ</option>
          <option value="NM">NM</option>
          <option value="NY">NY</option>
          <option value="NC">NC</option>
          <option value="ND">ND</option>
          <option value="OH">OH</option>
          <option value="OK">OK</option>
          <option value="OR">OR</option>
          <option value="PA">PA</option>
          <option value="RI">RI</option>
          <option value="SC">SC</option>
          <option value="SD">SD</option>
          <option value="TN">TN</option>
          <option value="TX">TX</option>
          <option value="UT">UT</option>
          <option value="VT">VT</option>
          <option value="VA">VA</option>
          <option value="WA">WA</option>
          <option value="WV">WV</option>
          <option value="WI">WI</option>
          <option value="WY">WY</option>
        </select>
        
        <label for="addZip">Zip</label>
        <input type="text" id="addZip" />
        
        <label for="addWebsite">Website</label>
        <input type="text" id="addWebsite" />
        
        <br />
      
      	<?php
					if(!$user) {
						echo '<div class="btn btn-primary" id="addProperty">Add Property</div>';
					} else {
						echo '<p>You must be <a href="' . $loginUrl . '">logged in with Facebook</a> to add a property</p>';
					}
				?>
      </form>
			
		</div>

    <?php include($_SERVER['DOCUMENT_ROOT'].'/includes/scripts.inc.php'); ?>
    
    <script>
			var geoUrl = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=true&address=';
			
			function addProperty(d) {
				var _latLng = d[0].geometry.location;
				var _lat = _latLng.lat;
				var _lng = _latLng.lng
				var propertyObj = {
					name: $('#addName').val(),
					address: $('#addAddress').val(),
					address2: $('#addAddress2').val(),
					city: $('#addCity').val(),
					state: $('#addState').val(),
					zip: $('#addZip').val(),
					lat: _lat,
					lng: _lng,
					website: $('#addWebsite').val()
				}
				$.ajax({
					url: '/controllers/add.php',
					data: propertyObj,
					dataType: 'json',
					method: 'POST',
					success: function(response) {
						$('body').prepend('<div class="alert alert-success">Property added successfully, <a href="/property/?property=' + response.propertyId + '">write a review.</a><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('#addProperty').html('Add Property').prop('disabled',false);
						document.getElementById('addPropertyForm').reset();
					},
					error: function() {
						$('#addProperty').html('Add Property').prop('disabled',false);
						$('body').prepend('<div class="alert alert-error">There was a problem with your request, please try again.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				});
			}
		
			function getLatLngAdd(address) {
				// Get lat/lng from Google Maps API
				$.ajax({
					url: geoUrl + address,
					method: 'GET',
					dataType: 'json',
					success: function(response) {
						var _status = response.status;
						switch(_status) {
							case "OK":
								addProperty(response.results);
							break;
							case "ZERO_RESULTS":
								$('body').prepend('<div class="alert">No results were found, please check the address or <a href="/add.php">Add the Property</a>.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
								$('#addProperty').html('Add Property').prop('disabled',false);
							break;
							default:
								$('body').prepend('<div class="alert">No results were found, please check the address or <a href="/add.php">Add the Property</a>.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
								$('#addProperty').html('Add Property').prop('disabled',false);
						}
					},
					error: function() {
						$('body').prepend('<div class="alert alert-error">There was a problem with your request, please try again.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				});
			}
			
			function formatAddressAdd() {
				var address = encodeURIComponent($('#addAddress').val() + $('#addAddress2').val() + ',' + $('#addCity').val() + ',' + $('#addState').val() + ' ' + $('#addZip').val());
				getLatLngAdd(address);
			}
		</script>
		
    <?php if(!$user) { ?>
		<script>
			$(document).ready(function() {
				$('#addProperty').bind('click', function() {
					$('#addProperty').html('Adding Property...').prop('disabled',true);
					$('.alert').remove();
					formatAddressAdd();
				});
			});
		</script>
		<?php } ?>
  </body>
</html>