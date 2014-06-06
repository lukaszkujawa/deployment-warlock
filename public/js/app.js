'use strict';


// Declare app level module which depends on filters, and services
angular.module('depwar', [
  'ngRoute',
  'depwar.filters',
  'depwar.services',
  'depwar.directives',
  'depwar.controllers'
]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/home', 
  		{templateUrl: '/js/partials/index.html', controller: 'IntedxCtrl'});
  
  $routeProvider.when('/projects', 
  		{templateUrl: '/js/partials/projects.html', controller: 'ProjectsCtrl'});

  $routeProvider.when('/deployments', 
      {templateUrl: '/js/partials/deployments.html', controller: 'DeploymentsCtrl'});

  $routeProvider.when('/projects/edit/:projectId', 
  		{templateUrl: '/js/partials/projects-edit.html', controller: 'ProjectsEditCtrl'});
  
  $routeProvider.when('/projects/create', 
  		{templateUrl: '/js/partials/projects-create.html', controller: 'ProjectsCreateCtrl'});


  $routeProvider.when('/servers', 
      {templateUrl: '/js/partials/servers.html', controller: 'ServersCtrl'});

  $routeProvider.when('/servers/create', 
      {templateUrl: '/js/partials/servers-create.html', controller: 'ServersCreateCtrl'});

  $routeProvider.when('/servers/edit/:serverId', 
      {templateUrl: '/js/partials/servers-edit.html', controller: 'ServersEditCtrl'});

  $routeProvider.otherwise({redirectTo: '/home'});
}]);
