var loadUser = function(userId) {
	// ajax to get user data, if no data display messages
	$.ajax({
		url:'/controllers/loadUser.php?user=' + userId,
		method: 'GET',
		dataType:'json',
		success: function(response) {
			var _response = response.userReviews;
			var avgReviews = parseInt(0);
			$('#userInfo').html('');
			for(var i=0; i<_response.length; i++) {
				var userReview = '';
				var timeAddress = parseInt(_response[i].months);
				if(timeAddress > 11) {
					timeAddress = timeAddress / 12;
					timeAddress = parseInt(timeAddress) + ' year(s)';
				} else {
					timeAddress = timeAddress + ' months';
				}
				userReview += '<h3>';
				userReview += '<a href="/property.php?id=' + _response[i].propertyId + '">';
				userReview += _response[i].propertyName;
				userReview += '</a>';
				userReview += '</h3>';
				userReview += '<div class="rating">';
				userReview += _response[i].rating;
				userReview += '</div>';
				userReview += '<div class="date">';
				userReview += _response[i].date;
				userReview += '</div>';
				userReview += '<div class="time">';
				userReview += timeAddress;
				userReview += '</div>';
				userReview += '<p>';
				userReview += _response[i].review;
				userReview += '</p>';
				userReview += '<a href="/property.php?id=' + _response[i].propertyId + '" class="btn">';
				userReview += 'View';
				userReview += '</a>';
				userReview += '<a href="/property.php?id=' + _response[i].propertyId + '" class="btn">';
				userReview += 'Edit';
				userReview += '</a>';
				userReview += '<a href="/property.php?id=' + _response[i].propertyId + '" class="btn">';
				userReview += 'Delete';
				userReview += '</a>';
				$('#userInfo').append(userReview);
				avgReviews += parseInt(_response[i].rating);
			}
			avgReviews = avgReviews / _response.length;
			$('#userInfo').append('<div class="avgReviews">' + avgReviews + '</div>');
		},
		error: function() {
			$('#userInfo').html('<div class="alert alert-error">There was a problem with your request, please try again later.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		}
	});
}

function removeRating() {
	$('#rating').removeClass('rate10').removeClass('rate20').removeClass('rate30').removeClass('rate40').removeClass('rate50');
}

function toggleRatingHover(_id) {
	console.log(_id);
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
	console.log('toggle rating');
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
	console.log($('#rating').data('rating'));
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



