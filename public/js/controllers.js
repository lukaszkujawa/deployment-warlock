'use strict';

angular.module('depwar').factory('Model', function( $http ) {

  var Model = function( data ) {
    if( data ) {
      this.populate( data );
    }
  };


  Model._static = {

    getAll: function() {
      var that = this;
      return $http.get( this.endPoint ).then(function(response){
        if( response.data.error == undefined ) {
          return that.init( response.data );
        } else {
          alert( serversData.data.errorMessage );
        }
      });
    },

    getById: function( id ) {
      var that = this;
      return $http.get( this.endPoint + '/' + id).then(function(serversData){
        if( serversData.data.error == undefined ) {
          return that.init( serversData.data );
        } else {
          alert( serversData.data.errorMessage );
        }
      });
    }

  };

  Model.prototype = {

    getEndpoint: function() {
      if( this.endPoint == undefined ) {
        throw "getEndpoint() method has to be defined in the parent child class";
      }

      return this.endPoint;
    },

    populate: function( data ) {
      angular.extend( this, data );
    },

    create: function( callback, errorCallback ) {
      var that = this;
      return $http.put( this.getEndpoint(), this ).then(function(response){
        if( response.data.error == undefined ) {
          return that.init( response.data );
        } else {
          alert( response.data.errorMessage );
        }
      });
    },

    update: function() {
      var that = this;
      return $http.post( this.getEndpoint() + '/' + this.id, this ).then(function(response){
        if( response.data.error == undefined ) {
          return that.init( response.data );
        } else {
          alert( response.data.errorMessage );
        }
      });
    },

    delete: function() {
      var that = this;
      return $http.delete( this.getEndpoint() + '/' + this.id, this ).then(function(response){
        if( response.data.error == undefined ) {
          return that.init( response.data );
        } else {
          alert( response.data.errorMessage );
        }
      });
    }

  };

  Model.extend = function(obj) {
    for( var i in Model.prototype ) {
      if( obj.prototype[ i ] == undefined ) {
        obj.prototype[ i ] = Model.prototype[ i ];
      }
    }

    for( var i in Model._static ) {
      if( obj[ i ] == undefined ) {
        obj[ i ] = Model._static[ i ];
      }
    }
  };

  return Model;  

});

angular.module('depwar').factory('Project', function( $http, Model ) {

  var Project = function( data ) {
    if( data ) {
      angular.extend( this, data );
    }
  };

  Project.endPoint = '/projects';

  Project.init = function( data ) {
    if( data.projects != undefined ) {
      return data.servers;
    }
    if( data.project != undefined ) {
      return new Project( data.project );
    }
    else {
      return data;
    }
  }

  Project.prototype = {
    getEndpoint: function() {
      return Project.endPoint;
    },

    init: function(input) {
      return Project.init(input);
    }
  };

  Model.extend( Project );

  return Project;

});

angular.module('depwar').factory('Server', function( $http, Model ) {

  var Server = function( data ) {
    if( data ) {
      this.populate( data );
    }

  };

  Server.endPoint = '/servers';

  Server.init = function( data ) {
    if( data.servers != undefined ) {
      return data.servers;
    }
    if( data.server != undefined ) {
      return new Server( data.server );
    }
    else {
      return data;
    }
  }

  Server.prototype = {
    getEndpoint: function() {
      return Server.endPoint;
    },

    init: function(input) {
      return Server.init(input);
    }
  };

  Model.extend( Server );

  return Server;

});

angular.module('depwar.controllers', [])
  
  .controller('NavCtrl', ['$scope', '$location', function($scope, $location) {
  
  	$scope.navClass = function (page) {
  		var currentRoute = $location.path().substring(1) || 'home';
  		return page === currentRoute ? 'active' : '';
  	};
  
  }])

  .controller('ModalCtl',['$scope', 'modalWarning', function($scope, modalWarning) {
    
    modalWarning.callback = function( message, callback ) {
      $scope.message = message;
      $scope.continue = function() {
        $('#modal').modal('hide');
        callback();
      }
      $('#modal').modal();
    };

  }])

  .controller('FlashMessageCtl',['$scope', '$timeout', 'flashMessage', function($scope, $timeout, flashMessage) {
    
    $scope.showMessage = false;

    flashMessage.callback = function() {
      $scope.message = flashMessage.message;
      $scope.showMessage = true;
      $timeout(function(){
        $scope.showMessage = false;
      }, 2000);
    }

  }])

  .controller('IntedxCtrl', ['$scope', '$http', function($scope, $http) {

    $http.get('/projects').success(function(data){
  		$scope.projects = data.projects;
  	});

  }])

  .controller('ProjectsCtrl', ['$scope', '$http', function($scope, $http) {

  	$http.get('/projects').success(function(data){
  		$scope.projects = data.projects;
  	});

  }])

  .controller('ProjectsEditCtrl', function($scope, $http, $routeParams, $location, Project, flashMessage, modalWarning) {

    $scope.projectDelete = function() {
      modalWarning.show( 'Click "continue" if you want to remove the project.', function(){
        $http.delete( '/projects/' + $scope.project.id )
              .success(function( data ) {
                $location.path('/projects');
                flashMessage.setMessage( 'Project Deleted' );
              })
              .error(function( data ) {
                alert( 'Error: ' + data );
              });
        });
    }

    $scope.projectUpdate = function() {
      $scope.project.update().then(function(){
        $location.path('/projects');
        flashMessage.setMessage( 'Project Updated' );
      });
    }

    Project.getById( $routeParams.projectId ).then(function( project ) {
      $scope.project = project;
    })
  })

  .controller('DeploymentsCtrl', ['$scope', '$http', function($scope, $http) {

  }])

  .controller('ProjectsCreateCtrl', ['$scope', '$http', '$location', 'flashMessage', function($scope, $http, $location, flashMessage) {
  	$scope.projectSave = function() {
  		$http.put('/projects', $scope.newProject )
      			.success(function( data ) {
              $location.path('/projects');
              flashMessage.setMessage( 'Project Created' );
      			})
      			.error(function( data ) {
      				alert( 'Error: ' + data );
      			});
  	}

  }])

  .controller('ServersCtrl', ['$scope', 'Server', function($scope, Server) {
    Server.getAll().then(function(servers){
      $scope.servers = servers;
    });
  }])

  .controller('ServersCreateCtrl', function($scope, Server, $location) {
    $scope.server = new Server({
      auth_type: 0
    });

    $scope.createServer = function() {
      $scope.server.create().then(function(response){
        $location.path('/servers');
        flashMessage.setMessage( 'Server created' );
      });
    }

    $scope.showPassword = 1;
    $scope.showSSHKey = 0;

    $scope.$watch( 'server.auth_type', function(){
      if( $scope.server.auth_type == 0 ) {
        $scope.showPassword = 1;
        $scope.showSSHKey = 0;
      }
      else {
        $scope.showPassword = 0;
        $scope.showSSHKey = 1;
      }
    });
  })

  .controller('ServersEditCtrl', function($scope, $routeParams, Server, $location, flashMessage, modalWarning) {
    
    $scope.serverUpdate = function() {
      $scope.server.update().then(function(resp){
        $location.path('/servers');
        flashMessage.setMessage( 'Server updated' );
      });
    };

    $scope.serverDelete = function() {
      modalWarning.show( 'Click "continue" if you want to remove the server.', function(){
        $scope.server.delete().then(function(resp){
          $location.path('/servers');
          flashMessage.setMessage( 'Server deleted' );
        });
      });
    };
    
    Server.getById( $routeParams.serverId ).then(function(server){
      $scope.server = server;
    });

  })

  ;
