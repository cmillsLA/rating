'use strict';


// Declare app level module which depends on filters, and services
angular.module('myApp', [
  'ngRoute',
  'myApp.filters',
  'myApp.services',
  'myApp.directives',
  'myApp.controllers'
]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/', {templateUrl: 'partials/index.html', controller: 'index'});
  $routeProvider.when('/search', {templateUrl: 'partials/search.html', controller: 'search'});
$routeProvider.when('/property', {templateUrl: 'partials/property.html', controller: 'property'});
  $routeProvider.otherwise({redirectTo: '/'});
}]);