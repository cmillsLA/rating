<?php $page = 'index'; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/facebook.inc.php'); ?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Rating Site</title>
    
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/header.inc.php'); ?>

  </head>
  <body>
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/topbar.inc.php'); ?>
		<div class="container">
			<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/nav.inc.php'); ?>
		</div>
    <h1></h1>
		
		<div class="hero">
			<ul class="nav nav-tabs" id="rateTabs">
			  <li><a href="#rateSearch" data-toggle="tab">Search</a></li>
			  <li><a href="#rateBrowse" data-toggle="tab">Browse by Area</a></li>
			</ul>
			
			<div class="tab-content">
			  <div class="tab-pane" id="rateSearch">
        	<form action="/search/index.php" method="get" id="searchForm">
          	<label for="searchAddress">Address</label>
            <input type="text" id="searchAddress" name="address" />
            <label for="searchAddress2">Address 2</label>
            <input type="text" id="searchAddress2" name="address2" />
            <label for="searchCity">City</label>
            <input type="text" id="searchCity" name="city" />
            <label for="searchState">State</label>
            <select id="searchState" name="state">
            	<option selected value="">Select State</option>
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
            <label for="searchZip">Zip</label>
            <input type="text" id="searchZip" name="zip" />
            <div class="btn btn-primary" id="search">Submit</div>
          </form>
        </div>

			  <div class="tab-pane" id="rateBrowse">
					<form id="browseForm" action="/search/index.php" method="get">
            <label for="browseCity">City</label>
            <input type="text" id="browseCity" name="city" />
            <label for="browseState">State</label>
            <select id="browseState" name="state">
            	<option selected value="">Select State</option>
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
						<p><strong>OR</strong></p>
            <label for="browseZip">Zip</label>
            <input type="text" id="browseZip" name="zip" />
            <div class="btn btn-primary" id="browse">Submit</div>
					</form>
				
				</div>
			
			</div>
			
		</div>

    <?php include($_SERVER['DOCUMENT_ROOT'].'/includes/scripts.inc.php'); ?>

		<script>
			function setupPageBehavior() {
				// Setup search/browse tabs
				$('#rateTabs a:first').tab('show');
				
				// Validate State
				$.validator.addMethod(
				  "stateSelected",
				  function (value, element) { 
				    if(value != "") {
							return value;
						} else {
							return false;
						}
				  }, "Please select a state."
				);
				
				// Search Validation
				$('#searchForm').validate({
					errorClass: 'error',
					errorElement: 'div',
					onkeyup: false,
					validClass: 'valid',
					rules: {
						address: { required: true },
						city: { required: true },
						state: { stateSelected: true },
						zip: { required:true, number:true, minlength:5 }
					},
					messages: { // Span tags for error message arrow placement, don't remove
						address: 'Please enter an address.',
						city: 'Please enter a city.',
						state: 'Please select a state.',
						zip: 'Please enter a valid zip code.'
					}
				});
				
				// Browse Validation
				$('#browseForm').validate({
					errorClass: 'error',
					errorElement: 'div',
					onkeyup: false,
					validClass: 'valid',
					rules: {
						zip: { number:true, minlength:5 }
					},
					messages: {
						zip: 'Please enter a valid zip code.'
					}
				});
				
				// Browse
				$('#browse').click(function() {
					if($('#browseForm').valid()) {
						$('#browseForm').submit();
					} else {
						return false;
					}
				});
				
				// Search
				$('#search').click(function() {
					if($('#searchForm').valid()) {
						$('#searchForm').submit();
					} else {
						return false;
					}
				});

			}
			
			$(document).ready(setupPageBehavior);
		</script>

  </body>
</html>