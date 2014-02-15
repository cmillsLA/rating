var geoUrl = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=true&address=';

/* Profile */
function setupPageBehaviorProfile() {
	console.log('called');
	$('.editReview').on( "click", function() {
		alert('test');
	});
		/*var _parent = $(this).parent();
		$(_parent + ' .editReview').hide();
		$(_parent + ' .savedReview').hide();
		$(_parent + ' .saveReview').show();
		$(_parent + ' .reviewEdit').show();*/
}

function loadUser(userId) {
	// ajax to get user data, if no data display messages
	$.ajax({
		url:'/controllers/loadUser.php?user=' + userId,
		method: 'GET',
		dataType:'json',
		success: function(response) {
			var _response = response.userReviews;
			if(_response.length != 0) {
				$('#userReviews').html('');
				var avgReviews = parseInt(0);
				$('#userInfo').html('');
				for(var i=0; i<_response.length; i++) {
					var userReview = '';
					var _bg = '';
					if(i % 2 === 0) {
						_bg = ' gray';
					}
					if(_response[i].months) {
						var timeAddress = parseInt(_response[i].months);
						if(timeAddress > 11) {
							timeAddress = timeAddress / 12;
							timeAddress = parseInt(timeAddress) + ' year(s)';
						} else {
							timeAddress = timeAddress + ' months';
						}
					} else {
						var timeAddress = 'Never lived here.';
					}
					var _propertyId = _response[i].propertyId;
					// Format rating to CSS
					var _ratingFormat = _response[i].rating;
					var _rating = _ratingFormat.replace('.','');
					// Format date from object
					var formatDate = new Date(_response[i].date);
					var formatDay = formatDate.getUTCDate();
					var formatMonth = formatDate.getMonth() + 1;
					var formatYear = formatDate.getFullYear();
					userReview += '<div class="p20 ' + _bg + ' relative">';
					userReview += '<h3>';
					userReview += '<a href="/property.php?id=' + _propertyId + '">';
					if(_response[i].propertyName) {
						userReview += _response[i].propertyName;
					}			
					userReview += '</a>';
					userReview += '</h3>';
					userReview += '<div class="profileRating rating rate' + _rating + '0">';
					userReview += '</div>';
					userReview += '<div class="date">';
					userReview += '<p><strong>Review Date: </strong>' + formatMonth + '/' + formatDay + '/' + formatYear + '</p>';
					userReview += '</div>';
					userReview += '<div class="time">';
					userReview += '<strong>How long at this address?</strong> ' + timeAddress;
					userReview += '</div>';
					userReview += '<div><strong>Your Review: </strong></div>';
					userReview += '<p class="savedReview">';
					userReview += _response[i].review;
					userReview += '</p>';
					userReview += '<textarea class="reviewEdit dNone">';
					userReview += _response[i].review;
					userReview += '</textarea>';
					userReview += '<div class="clear"></div>';
					userReview += '<a href="/property.php?property=' + _propertyId + '" class="btn mr20">';
					userReview += 'View';
					userReview += '</a>';
					userReview += '<div id="deleteReview-' + _propertyId + '" class="btn mr20 deleteReview">';
					userReview += 'Delete';
					userReview += '</div>';
					userReview += '<div id="editReview-' + _propertyId + '" class="btn mr20 editReview">';
					userReview += 'Edit';
					userReview += '</div>';
					userReview += '<div id="saveReview-' + _propertyId + '" class="btn mr20 dNone saveReview">';
					userReview += 'Save';
					userReview += '</div>';
					userReview += '</div>';
					$('#userReviews').append(userReview);
					avgReviews += parseInt(_response[i].rating);
				}
				avgReviews = avgReviews / _response.length;
				//$('#userReviews').append('<div class="avgReviews">' + avgReviews + '</div>');
			} else {
				$('#userReviews').html("<p>You have no saved reviews. :(</p>");
			}
		},
		error: function() {
			$('#userReviews').html('<div class="alert alert-error">There was a problem with your request, please try again later.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		}
	});
	setupPageBehaviorProfile();
}

/* Rating */
function removeRating() {
	$('#rating').removeClass('rate10').removeClass('rate20').removeClass('rate30').removeClass('rate40').removeClass('rate50');
}

function toggleRatingHover(_id) {
	removeRating();
	switch(_id) {
		case 'rate1':
		$('#rating').addClass('rate10');
		break;
		case 'rate2':
		$('#rating').addClass('rate20');
		break;
		case 'rate3':
		$('#rating').addClass('rate30');
		break;
		case 'rate4':
		$('#rating').addClass('rate40');
		break;
		case 'rate5':
		$('#rating').addClass('rate50');
		break;
	}
}

function toggleRating(_id) {
	removeRating();
	switch(_id) {
		case 'rate1':
		$('#rating').addClass('rate10');
		$('#rating').data('rating', '1');
		break;
		case 'rate2':
		$('#rating').addClass('rate20');
		$('#rating').data('rating', '2');
		break;
		case 'rate3':
		$('#rating').addClass('rate30');
		$('#rating').data('rating', '3');
		break;
		case 'rate4':
		$('#rating').addClass('rate40');
		$('#rating').data('rating', '4');
		break;
		case 'rate5':
		$('#rating').addClass('rate50');
		$('#rating').data('rating', '5');
		break;
	}
}

$(document).ready(function() {
	// Rating
	$( ".unrated li" ).hover(
	  function() {
			_id = $(this).prop('id');
			toggleRatingHover(_id);
	  }, function() {
			removeRating();
			toggleRatingHover('rate' + $('#rating').data('rating'));
	  }
	);

	$('.unrated li').click(function() {
		$('#rating').removeClass('unrated');
		_id = $(this).prop('id');
		toggleRating(_id);
	});
});

/* Validation */
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

/* Add Property */
function bindValidationAdd() {
	// Add Validation
	$('#addPropertyForm').validate({
		errorClass: 'error',
		errorElement: 'div',
		onkeyup: false,
		validClass: 'valid',
		rules: {
			addAddress: { required: true },
			addCity: { required: true },
			addState: { stateSelected: true },
			addZip: { required:true, number:true, minlength:5 }
		},
			messages: { // Span tags for error message arrow placement, don't remove
			addAddress: 'Please enter an address.',
			addCity: 'Please enter a city.',
			addState: 'Please select a state.',
			addZip: 'Please enter a valid zip code.'
		}
	});
}

function initializeAddMap(d) {
	// Setup Map
	var _geo = d[0].geometry.location;
	var _latLng = new google.maps.LatLng(_geo.lat, _geo.lng);
  var mapOptions = {
  	center: _latLng,
    zoom: 18,
    mapTypeId: google.maps.MapTypeId.SATELLITE
  };
  var map = new google.maps.Map(document.getElementById("propertyMap"),
     mapOptions);
						
	// Setup marker
	var marker = new google.maps.Marker({
		position: _latLng,
		map: map
	});
				
}
			
function checkProperty(d) {
	var _latLng = d[0].geometry.location;
	var _lat = _latLng.lat;
	var _lng = _latLng.lng;
	$.ajax({
		type: 'GET',
		url: '/controllers/checkProperty.php?lat=' + _lat + '&lng=' + _lng,
		dataType: 'json',
		success: function(response) {
			if(response.property == 'no_match') {
				addProperty(d);
			} else {
				$('#propertyContainer').prepend('<div class="p20"><div class="alert alert-success">This property is already in the database, <a href="/property/?property=' + response.property + '">Click here</a> to add a review.<button type="button" class="close" data-dismiss="alert">&times;</button></div></div>');
			}
		},
		error: function() {
			$('#addProperty').html('Add Property').prop('disabled',false);
			$('body').prepend('<div class="alert alert-error">There was a problem with your request, please try again.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		}
	});
}
			
function addProperty(d) {
	var _latLng = d[0].geometry.location;
	var _lat = _latLng.lat;
	var _lng = _latLng.lng;
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

function getLatLngAdd(address, mapPreview) {
	// Get lat/lng from Google Maps API
	$.ajax({
		url: geoUrl + address,
		method: 'GET',
		dataType: 'json',
		success: function(response) {
			var _status = response.status;
			if(mapPreview) {
				initializeAddMap(response.results);
			} else {
				switch(_status) {
					case "OK":
						checkProperty(response.results);
					break;
					case "ZERO_RESULTS":
						$('body').prepend('<div class="alert">No results were found, please check the address or <a href="/add.php">Add the Property</a>.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('#addProperty').html('Add Property').prop('disabled',false);
					break;
					default:
						$('body').prepend('<div class="alert">No results were found, please check the address or <a href="/add.php">Add the Property</a>.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('#addProperty').html('Add Property').prop('disabled',false);
					}
			}
		},
		error: function() {
			$('body').prepend('<div class="alert alert-error">There was a problem with your request, please try again.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		}
	});
}


function formatAddressAdd(mapPreview) {
	var address = encodeURIComponent($('#addAddress').val() + $('#addAddress2').val() + ',' + $('#addCity').val() + ',' + $('#addState').val() + ' ' + $('#addZip').val());
	getLatLngAdd(address, mapPreview);
}

/* Property */
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
				var _bg = '';
				if(i % 2 === 0) {
					_bg = ' gray';
				}
				var _review = '';
				_review += '<div class="review relative p20' + _bg + '">';
				var _rating = _response[i].rating;
				_rating.replace('.','');
				_review += '<div class="reviewRating rating rate' + _rating + '0"></div>';
				_review += '<img src="' + _response[i].userImg + '" class="left smImg" />';
				_review += '<p class="userName">' + _response[i].userName +  '</p>';
				_review += '<div class="clear"></div>';
				_review += '<h3>' + _response[i].title + '</h3>';
				_review += '<p>';
				_review += _response[i].review;
				_review += '</p>';
				_review += '</div>';
				$('#reviews').append(_review);
				_rating += parseInt(_response[i].rating);				
			}
			var _avgRating = response.avgRating;
			var _avgCSS = _avgRating.replace('.','');
			$('#overallRating').prop('class', 'rating rating' + _avgCSS);
		},
		error: function() {
			$('#reviews').prepend('<div class="alert alert-error">Could not refresh reviews feed, please manually refresh your browser. <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		}
	});
}
		
function submitReview(userId, userName, facebookLink) {
	$('.alert').remove();
	// validation
	var reviewObj = {
		title: $('#reviewTitle').val(),
		rating: $('#rating').data('rating'),
		review: $('#reviewReview').val(),
		userId: userId,
		userImg: 'https://graph.facebook.com/' + userId + '/picture',
		userName: userName,
		propertyId: _propertyId,
		date: new Date(),
		years: $('#reviewYears').val(),
		months: $('#reviewMonths').val(),
		facebookUrl: facebookLink
	}
	$.ajax({
		url: '/controllers/addReview.php',
		method: 'POST',
		data: reviewObj,
		dataType: 'json',
		success: function(response) {
			$('#propertyContainer').prepend('<div class="p20"><div class="alert alert-success">Thanks, your review has been successfully added.<button type="button" class="close" data-dismiss="alert">&times;</button></div></div>');
			$('#reviewProperty').unbind('click').remove();
			$('#writeReview').slideUp(250);
			refreshReviews(_propertyId);
		},
		error: function() {
			defaultError();
		}
	});
}

/* Index */
function bindIndexEvents() {
	
	// Setup search/browse tabs
	$('#searchTabs a:last').tab('show');
	
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

function bindIndexValidation() {
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
}
			
/* Setup pages */
function setupPageBehaviorAdd() {
	bindValidationAdd();
}

function setupPageBehaviorProperty() {
	$('#reviewProperty').bind('click', function() {
		$(this).prop('disabled',true);
		$('#writeReview').slideDown(250);
	});
}

function setupPageBehaviorIndex() {
	bindIndexEvents();
	bindIndexValidation();
}