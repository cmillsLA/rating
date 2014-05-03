'use strict';

/* Controllers */
angular.module('myApp.controllers', [])
  .factory( 'auth', [ '$rootScope', '$location', function( $rootScope, $location ) {

    return {
      bindLoginEvents: function() {
				console.log('bind login events');
        var _this = this;
        console.log('bind login events');
        $('#loginStatus').on('click', '.login', function(e) {
          FB.login(function(response) {
            if(response.status === "connected") {
              _this.toggleLoggedIn();
            } else {
              _this.toggleLoggedOut();
            }
          });
          e.preventDefault();
        });

        $('#loginStatus').on('click', '.logout', function(e) {
          FB.logout(function(response) {
            _this.toggleLoggedOut();
          });
          e.preventDefault();
        });
      },
			toggleUI: function(path, status) {
				console.log('toggle ui');
				console.log(path);
				console.log(status);
				switch(path) {
					case '/property':
						if(status === "connected") {
	          	$('#reviewBtn').html('<div class="btn btn-primary" id="reviewSubmit">Submit Review</div>');
	          	console.log('button append');
	        	} else {
	          	$('#reviewBtn').html('<p class="p20">To submit a review please <a class="login">login with Facebok</a>.</p>');
	          	console.log('button append');
	        	}
						break;
				}
			},
      toggleLoggedIn: function() {
				console.log('toggle logged in');
        $('.loggedIn').show();
        $('.loggedOut').hide();
        FB.api('/me', function(response) {
          $rootScope.user.name = response.first_name;
          $rootScope.user.id = response.id;
          $rootScope.user.link = response.link;
          $('#loginStatus').html('<span class="left">Welcome, ' + $rootScope.user.name + '&nbsp; &nbsp;|  </span><a href="/#/profile" class="left">My Profile</a> <a class="logout right">Logout</a>');
        	_this.toggleUI($location.$$path, "connected");
				});
      },
      toggleLoggedOut: function(redirect) {
				console.log('toggle logged out');
				var _this = this;
        $('#loginStatus').html('<a class="right login">Login with Facebook</a>');
        $('.loggedIn').hide();
        $('.loggedOut').show();
				_this.toggleUI($location.$$path, false);
        // Authenticated content, redirect to index.
        if(redirect) {
          window.location.href = "/";
        }
      },
      getStatus: function() {
				console.log('get status');
        var _this = this;
        FB.getLoginStatus(function(response) {
          var status = response.status;
          if(status === "connected") {
            _this.toggleLoggedIn();
          } else {
						var loc = $location.$$path;
						if(loc == 'authenticated_page') {
							_this.toggleLoggedOut(true);
						} else {
							_this.toggleLoggedOut();
						}
          }
          _this.bindLoginEvents();
        }, true);
      }
    }

  }])
 .service( 'propertySearch', [ '$rootScope', function( $rootScope ) {

		var address = null;

		return {

			getAddress: function() {
				return $rootScope.address;
			},

			updateAddress: function(addressObj) {
				$rootScope.address = addressObj;
				return addressObj;
			}

		}

 }])
 .service( 'propertyDisplay', [ '$rootScope', function( $rootScope ) {

		// Pass property id from results to property display page
		var propertyId = null;

		return {

			getPropertyId: function() {
				return $rootScope.propertyId;
			},

			updatePropertyId: function(propId) {
				$rootScope.propertyId = propId;
				return $rootScope.propertyId;
			}

		}

 }])
	.controller('global', ['$scope', '$http', '$location', '$compile', function ($scope, $http, $location, $compile) {}])
	.controller('property', ['propertySearch', 'propertyDisplay', '$scope', '$http', '$location', '$compile', function (propertySearch, propertyDisplay, $scope, $http, $location, $compile) {

		$scope.nullCheck = function(d) {
			if(d) {
				return d;
			} else {
				return '';
			}
		}

		$scope.backResults = function() {
			var addressObj = propertySearch.getAddress();
			if(addressObj != null) {
				$location.path('/search').search(addressObj).replace();
			} else {
				$location.path('/').replace();
			}
		}

		$scope.writeReview = function(d) {
			$('#writeReview').show();
		}

		$scope.displayMap = function(d) {
			// Setup Map
			var latLng = new google.maps.LatLng(d.lat, d.lng);
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
			propertyInfo += '<p><strong>' + $scope.nullCheck(d.name) + '</strong><br />';
			propertyInfo += $scope.nullCheck(d.address) + '<br />';
			propertyInfo += $scope.nullCheck(d.city) + ', ' + $scope.nullCheck(d.state) + ' ' + $scope.nullCheck(d.name) + '</p>';
			if(d.website) { propertyInfo += '<p>' + d.website + '</p>'; }
			var infowindow = new google.maps.InfoWindow({
					content: propertyInfo
			});

			// Setup marker
			var marker = new google.maps.Marker({
				position: latLng,
				map: map,
				title: $scope.nullCheck(d.name)
			});

			// Bind click event
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,marker);
			});

			// Open by default
			infowindow.open(map,marker);

		}

		$scope.populateReviews = function(reviews) {
			if(reviews.results) {
				for(var i=0; i<reviews.results.length; i++) {
					var _this = reviews.results[i];
					var _rating = _this.rating;
					if(_rating.length == 1) {
						_rating = _rating + '0';
					}
					var _review = '';
					if(i % 2 ===0) {
						_review += '<div class="review relative p20 gray">';
					} else {
						_review += '<div class="review relative p20">';
					}
					_review += '<div class="reviewRating rating rate' + _rating + '"></div>';
					_review += '<img src="' + _this.userImg + '" class="left smImg" />';
					_review += '<p class="userName">' + _this.userName + '</p>';
					_review += '<div class="clear"></div>';
					_review += '<h3>' + _this.title + '</h3>';
					_review += '<p>';
					_review += _this.review;
					_review += '</p>';
					_review += '</div>';
					$('#reviews').append($compile(_review)($scope));
				}
			}
		}

		$scope.populateResults = function(d, reviews) {
			var _prop = '';
			var _avg = d.avgRating;
			var _avgCSS = d.avgRating.replace('.','');
			_prop += '<h2>' + $scope.nullCheck(d.name) + '</h2>';
			_prop += '<p><strong>' + $scope.nullCheck(d.address) + '</strong><br />';
			_prop += $scope.nullCheck(d.city) + ', ' + $scope.nullCheck(d.state) + ' ' + $scope.nullCheck(d.zip);
			_prop += '</p>';
			if(d.website) {
				_prop += '<p><a href="' + d.website + '" target=" _blank">' + d.website + '</p>';
			}
			_prop += '<div id="overallRating" class="rating rate' + _avgCSS + '"></div>';
      _prop += '<div id="reviews"></div>';
			$('#property').prepend(_prop);
			$scope.populateReviews(reviews);
		}


		$scope.getReviews = function(d, _propId) {
			$http({
				method: 'GET',
			  url: 'http://74.91.130.13/api/reviews?propertyId=' + _propId
			}).
			success(function (data, status, headers, config) {
				$scope.populateResults(d, data);
				$scope.displayMap(d);
			}).
			error(function (data, status, headers, config) {
				$scope.name = 'Error!'
			});
		}

		$scope.displayProperty = function() {
			var _propId = propertyDisplay.getPropertyId();
			if(!_propId) {
				var params = $location.search();
				_propId = params.property;
			}
			if(_propId) {
				$http({
					method: 'GET',
				  url: 'http://74.91.130.13/api/property?propertyId=' + _propId
				}).
				success(function (data, status, headers, config) {
					$scope.getReviews(data.results[0], _propId);
				}).
				error(function (data, status, headers, config) {
					$scope.name = 'Error!'
				});
			} else { // no property selected, redirect home
				$location.path('/search').replace();
			}
		}

		$scope.displayProperty();

		var addressObj = propertySearch.getAddress();
		if(addressObj != null) {
			$('#backResults').show();
		}

		$('#loading').parent().remove();
		$('#wrap').fadeIn(250);

	}])
	.controller('index', ['propertySearch', '$scope', '$http', '$location', function (propertySearch, $scope, $http, $location) {

		$scope.valCheck = function(d) {
			if(d) { return d } else { return ''; }
		}

		$scope.propSearch = function() {
			var addressObj = {
				line1: $('#searchAddress').val(),
				line2: $('#searchAddress2').val(),
				city: $('#searchCity').val(),
				state: $('#searchState').val(),
				zip: $('#searchZip').val(),
				distance: 10,
				results: 10
			}
			propertySearch.updateAddress(addressObj);
			$location.path('/search').search(addressObj).replace();
			return false;
		}

		$scope.propBrowse = function() {
			var addressObj = {
				city: $scope.valCheck($('#browseCity').val()),
				state: $scope.valCheck($('#browseState').val()),
				zip: $scope.valCheck($('#browseZip').val())
			}
			propertySearch.updateAddress(addressObj);
			$location.path('/search').search(addressObj).replace();
			return false;
		}

		$scope.checkLogin = function() {
			var token = getCookie("token");
			if(!token) {
				$location.path('/login').replace();
			} else { // user logged in, bind page validation
				bindDashboardValidation();
			}
		}

		// Setup search/browse tabs
		$('#searchTabs a:last').tab('show');
		$('#nav li').removeClass('active');
		$('#navHome').addClass('active');

		$('#loading').parent().remove();
		$('#wrap').fadeIn(250);

		// Verify login
		//$scope.checkLogin();

  }])
	.controller('search', ['propertySearch', 'propertyDisplay', '$scope', '$http', '$location', '$compile', function (propertySearch, propertyDisplay, $scope, $http, $location, $compile) {
		
		$scope.nullCheck = function(d) {
			if(d) {
				return d;
			} else {
				return '';
			}
		}
		
		$scope.buildMap = function() {
			
			var markers = $scope.markers;

			// Setup Map
			var latLng = new google.maps.LatLng(markers[0].lat, markers[0].lng);
      var mapOptions = {
      	center: latLng,
        zoom: 11,
        mapTypeId: google.maps.MapTypeId.ROADVIEW
      };
      var map = new google.maps.Map(document.getElementById("resultsMap"),
          mapOptions);
				
			for(var i=0; i< markers.length; i++) {
				var _marker = new google.maps.Marker({
					position: new google.maps.LatLng (markers[i].lat, markers[i].lng),
					map: map,
					title:'tester',
				}); 
			}
		}
		
		$scope.showProperty = function(_propId) {
			propertyDisplay.updatePropertyId(_propId);
			$location.path('/property').search({'property':_propId}).replace();
		}
		
		$scope.displayProperty = function(d) {
			var bg = '';
			var _location = '';
			var _this = d;
			if($('.result').length % 2 === 0) { bg = ' gray'; }
			_location += '<div class="result relative p20' + bg + '" data-result="' + _this.propertyId + '">';
			_location += '<p><strong><span ng-click="showProperty(' + _this.propertyId +')" class="link">' + _this.name + '</span></strong><br />';
      _location += _this.address + '<br />';
			if(_this.address2) { _location += _this.address2 + '<br />'; }
			_location += _this.city + ', ' + _this.state + ' ' + _this.zip;
			if(_this.website) { _location += '<br />' + _this.website + '</p>'; } else { _location += '</p>' }
			_location += '<div class="btn viewResults" ng-click="showProperty(' + _this.propertyId +')">View</div>';
			_location += '</div>';
			var marker = {
				name: _this.name,
				lat: _this.lat,
				lng: _this.lng
			}
			$scope.markers.push(marker);
			$('#results').append($compile(_location)($scope));
		}
		
		$scope.populateResults = function(_results) {
			$('#results').html('');
			$scope.markers = new Array();
			if(_results.length == 0) {
				$('#results').html('<p>No results found, <a href="/add">Add your property here</a>.</p>');
			} else {
				for(var i=0; i<_results.length; i++) {
					$scope.displayProperty(_results[i]);
				}
				$scope.buildMap();
			}
/*if($rowNearby['lat'] == $latitude && $rowNearby['lng'] == $longitude) {
                echo '<div class="result result-exact well relative p20' . $bg . '" data-result="' . $rowNearby['propertyId'] . '">';
                echo '<h5>One exact match</h5>';
              } else {
              
								if($i == 1) {
									echo '<div class="alert">No exact match found, <a href="/add.php">add your property here</a>, or view nearby results below.<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
								}*/

              // Google Maps API
             /* $resultArr = array();
              array_push($resultArr,$rowNearby['name'], $rowNearby['lat'], $rowNearby['lng']); 
              array_push($markersArr,$resultArr);*/
		}

		$scope.propertySearch = function() {
			var _scope = $scope;
			var addressObj = propertySearch.getAddress();
			if(!addressObj) { // address object blank, check url
				var _url = $location.search();
				var addressObj = {
					line1: $scope.nullCheck(_url.line1),
					line2: $scope.nullCheck(_url.line2),
					city: $scope.nullCheck(_url.city),
					state: $scope.nullCheck(_url.state),
					zip: $scope.nullCheck(_url.zip),
					distance: $scope.nullCheck(_url.distance),
					results: $scope.nullCheck(_url.results)
				}
				propertySearch.updateAddress(addressObj); // sync global object
			}
			// sync with results
			$('#resultsDistance').val(addressObj.distance);
			$('#resultsResult').val(addressObj.results);
			var line1 = '';
			if(addressObj.line1) { line1 = addressObj.line1 }
			$http({
				method: 'GET',
			  url: 'http://74.91.130.13/api/search?address=' + line1 + '&city=' + addressObj.city + '&state=' + addressObj.state + '&zip=' + addressObj.zip,
			}).
			success(function (data, status, headers, config) {
				$scope.populateResults(data.results);
			}).
			error(function (data, status, headers, config) {
				$scope.name = 'Error!'
			});
		}
		
		$scope.updateResults = function() {
			var updateObj = propertySearch.getAddress();
			var line1 = '';
			if(updateObj.line1) { line1 = updateObj.line1; }
			updateObj.distance = $('#resultsDistance').val();
			updateObj.results = $('#resultsResult').val();
			propertySearch.updateAddress(updateObj);
			$http({
				method: 'GET',
			  url: 'http://74.91.130.13/api/searchUpdate?address=' + line1 + '&city=' + updateObj.city + '&state=' + updateObj.state + '&zip=' + updateObj.zip + '&distance=' + updateObj.distance + '&results=' + updateObj.results,
			}).success(function (data, status, headers, config) {
				$scope.populateResults(data.results);
			}).
			error(function (data, status, headers, config) {
				$scope.name = 'Error!'
			});
		}
		
		$('#nav li').removeClass('active');
		
		$('#loading').parent().remove();
		$('#wrap').fadeIn(250);
		
		$scope.propertySearch();

  }])
  .run(['$rootScope', '$window', 'auth', function($rootScope, $window, auth) {

    $rootScope.user = {};

    $window.fbAsyncInit = function() {

      FB.init({
        appId      : '221418578022709', // App ID
        channelUrl : 'channel.html', // Channel File
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        xfbml      : true  // parse XFBML
      });

      auth.getStatus();

    };

    (function(d){
      // load the Facebook javascript SDK

      var js,
        id = 'facebook-jssdk',
        ref = d.getElementsByTagName('script')[0];

      if (d.getElementById(id)) {
        return;
      }

      js = d.createElement('script');
      js.id = id;
      js.async = true;
      js.src = "http://connect.facebook.net/en_US/all.js";

      ref.parentNode.insertBefore(js, ref);

    }(document));

  }]);