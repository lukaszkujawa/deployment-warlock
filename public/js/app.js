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
  
  $routeProvider.when('/deploy/:projectId', 
      {templateUrl: '/js/partials/deploy.html', controller: 'DeployCtrl'});

  $routeProvider.when('/projects', 
  		{templateUrl: '/js/partials/projects.html', controller: 'ProjectsCtrl'});

  $routeProvider.when('/deployments', 
      {templateUrl: '/js/partials/deployments.html', controller: 'DeploymentsCtrl'});

  $routeProvider.when('/deployments/edit/:deploymentId', 
      {templateUrl: '/js/partials/deployments-edit.html', controller: 'DeploymentsEditCtrl'});

  $routeProvider.when('/deployments/create', 
      {templateUrl: '/js/partials/deployments-create.html', controller: 'DeploymentsCreateCtrl'});

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

  $routeProvider.when('/sshkeys', 
      {templateUrl: '/js/partials/sshkeys.html', controller: 'SshkeysCtrl'});

  $routeProvider.when('/sshkeys/create', 
      {templateUrl: '/js/partials/sshkeys-create.html', controller: 'SshkeysCreateCtrl'});

  $routeProvider.when('/sshkeys/edit/:keyId', 
      {templateUrl: '/js/partials/sshkeys-edit.html', controller: 'SshkeysEditCtrl'});



  $routeProvider.otherwise({redirectTo: '/home'});
}]);
