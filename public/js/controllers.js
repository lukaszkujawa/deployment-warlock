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
    },

    deploy: function() {
      var that = this;
      return $http.post( this.getEndpoint() + '/' + this.id + '/deploy', this ).then(function(response){
        if( response.data.error == undefined ) {
          return response.data;
        } else {
          alert( response.data.errorMessage );
        }
      });
    }

  };

  Model.extend( Project );

  return Project;

});

angular.module('depwar').factory('Deployment', function( $http, Model ) {

  var Deployment = function( data ) {
    if( data ) {
      this.populate( data );
    }

  };

  Deployment.endPoint = '/deployments';

  Deployment.init = function( data ) {
    if( data.deployments != undefined ) {
      return data.deployments;
    }
    if( data.deployment != undefined ) {
      return new Deployment( data.deployment );
    }
    else {
      return data;
    }
  }

  Deployment.prototype = {
    getEndpoint: function() {
      return Deployment.endPoint;
    },

    init: function(input) {
      return Deployment.init(input);
    }
  };

  Model.extend( Deployment );

  return Deployment;

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


angular.module('depwar').factory('Sshkey', function( $http, Model ) {

  var Sshkey = function( data ) {
    if( data ) {
      this.populate( data );
    }

  };

  Sshkey.endPoint = '/sshkeys';

  Sshkey.init = function( data ) {
    if( data.sshkeys != undefined ) {
      return data.sshkeys;
    }
    if( data.sshkey != undefined ) {
      return new Sshkey( data.sshkey );
    }
    else {
      return data;
    }
  }

  Sshkey.prototype = {
    getEndpoint: function() {
      return Sshkey.endPoint;
    },

    init: function(input) {
      return Sshkey.init(input);
    }
  };

  Model.extend( Sshkey );

  return Sshkey;

});

angular.module('depwar.controllers', [])
  
  .controller('NavCtrl', function($scope, $location) {
  
  	$scope.navClass = function (page) {
  		var currentRoute = $location.path().substring(1) || 'home';
  		return page === currentRoute ? 'active' : '';
  	};
  
  })

  .controller('ModalCtl', function($scope, modalWarning) {
    
    modalWarning.callback = function( message, callback ) {
      $scope.message = message;
      $scope.continue = function() {
        $('#modal').modal('hide');
        callback();
      }
      $('#modal').modal();
    };

  })
  
  .controller('DeployCtrl', function($scope, $routeParams, Project) {
    $scope.deploy = function() {
      $scope.project.deploy().then(function(resp){
        $scope.console = resp.out;
      });
    };

    Project.getById( $routeParams.projectId ).then(function( project ) {
      $scope.project = project;
    })
  })

  .controller('FlashMessageCtl', function($scope, $timeout, flashMessage) {
    
    $scope.showMessage = false;

    flashMessage.callback = function() {
      $scope.message = flashMessage.message;
      $scope.showMessage = true;
      $timeout(function(){
        $scope.showMessage = false;
      }, 2000);
    }

  })

  .controller('IntedxCtrl', function($scope, $http) {

    $http.get('/projects').success(function(data){
  		$scope.projects = data.projects;
  	});

  })

  .controller('ProjectsCtrl', function($scope, $http) {

  	$http.get('/projects').success(function(data){
  		$scope.projects = data.projects;
  	});

  })

  .controller('ProjectsEditCtrl', function($scope, $http, $routeParams, $location, Project, Server, Deployment, flashMessage, modalWarning) {

    $scope.activeDeployment = null;
    $scope.activeServer = null;

    $scope.deploymentServersRemove = function( id ) {
      $scope.project.deploymentAndServers.splice(id, 1);
    };

    $scope.deploymentServersAdd = function() {
      $scope.project.deploymentAndServers.push({
        deployment: $scope.activeDeployment,
        server: $scope.activeServer
      });

      $scope.activeDeployment = null;
      $scope.activeServer = null;

    }

    $scope.projectDelete = function() {
      modalWarning.show( 'Click "continue" if you want to remove this project.', function(){
        $scope.project.delete().then(function(resp){
           $location.path('/projects');
            flashMessage.setMessage( 'Project Deleted' );
        });
      });
    }

    $scope.projectUpdate = function() {
      console.log( $scope.project );
      $scope.project.update().then(function(){
        $location.path('/projects');
        flashMessage.setMessage( 'Project Updated' );
      });
    }

    Project.getById( $routeParams.projectId ).then(function( project ) {
      $scope.project = project;
    })

    Deployment.getAll().then(function( deployments ) {
      $scope.deployments = deployments;
    });

    Server.getAll().then(function( servers ) {
      $scope.servers = servers;
    });

  })

  .controller('DeploymentsCtrl', function( $scope, Deployment ) {
    Deployment.getAll().then(function(deployments){
      $scope.deployments = deployments;
    });
  })

  .controller('DeploymentsEditCtrl', function( $scope, $routeParams, $location, flashMessage, Deployment, modalWarning ) {
    $scope.deploymentUpdate = function() {
      $scope.deployment.last_update = new Date();
      $scope.deployment.update().then(function(resp){
        $location.path('/deployments');
        flashMessage.setMessage( 'Deployment updated' );
      });
    };

    $scope.deploymentDelete = function() {
      modalWarning.show( 'Click "continue" if you want to remove this deployment.', function(){
        $scope.deployment.delete().then(function(resp){
          $location.path('/deployments');
          flashMessage.setMessage( 'Server deleted' );
        });
      });
    };

    Deployment.getById( $routeParams.deploymentId ).then(function(deployment){
      $scope.deployment = deployment;
    });

  })

  .controller('DeploymentsCreateCtrl', function($scope, $location, flashMessage, Deployment) {
    $scope.deployment = new Deployment();

    $scope.createDeployment = function() {
      $scope.deployment.last_update = new Date();
      $scope.deployment.create().then(function(response){
        $location.path('/deployments');
        flashMessage.setMessage( 'Deployment created' );
      });
    };

  })  

  .controller('ProjectsCreateCtrl', function($scope, $http, $location, flashMessage) {
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

  })

  .controller('ServersCtrl', function($scope, Server) {
    Server.getAll().then(function(servers){
      $scope.servers = servers;
    });
  })

  .controller('ServersCreateCtrl', function($scope, Server, $location, Sshkey) {
    $scope.server = new Server({
      auth_type: 0
    });

    $scope.createServer = function() {
      $scope.server.last_update = new Date();
      $scope.server.create().then(function(response){
        $location.path('/servers');
        flashMessage.setMessage( 'Server created' );
      });
    };

    Sshkey.getAll().then(function(keys){
      $scope.sshkeys = keys;
    });

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

  .controller('ServersEditCtrl', function($scope, $routeParams, Server, $location, flashMessage, modalWarning, Sshkey) {
    
    $scope.serverUpdate = function() {
      $scope.server.last_update = new Date();
      $scope.server.update().then(function(resp){
        $location.path('/servers');
        flashMessage.setMessage( 'Server updated' );
      });
    };

    $scope.serverDelete = function() {
      modalWarning.show( 'Click "continue" if you want to remove this server.', function(){
        $scope.server.delete().then(function(resp){
          $location.path('/servers');
          flashMessage.setMessage( 'Server deleted' );
        });
      });
    };
    
    Sshkey.getAll().then(function(keys){
      $scope.sshkeys = keys;
    });

    Server.getById( $routeParams.serverId ).then(function(server){
      $scope.server = server;

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
    });

    $scope.showPassword = 1;
    $scope.showSSHKey = 0;

  })

  .controller('SshkeysCtrl', function($scope, Sshkey){
    Sshkey.getAll().then(function(keys){
      $scope.sshkeys = keys;
    });
  })

  .controller('SshkeysCreateCtrl', function($scope, $location, Sshkey, flashMessage){
    $scope.sshkey = new Sshkey();

    $scope.createSshkey = function() {
      $scope.sshkey.create().then(function(response){
        $location.path('/sshkeys');
        flashMessage.setMessage( 'Key added' );
      });
    };

  })

  .controller('SshkeysEditCtrl', function($scope, $routeParams, Sshkey, $location, flashMessage, modalWarning){
    $scope.sshkeyUpdate = function() {
      //$scope.sshkey.last_update = new Date();
      $scope.sshkey.update().then(function(resp){
        $location.path('/sshkeys');
        flashMessage.setMessage( 'Key updated' );
      });
    };

    $scope.sshkeyDelete = function() {
      modalWarning.show( 'Click "continue" if you want to remove this SSH key.', function(){
        $scope.sshkey.delete().then(function(resp){
          $location.path('/sshkeys');
          flashMessage.setMessage( 'Key deleted' );
        });
      });
    };

    Sshkey.getById( $routeParams.keyId ).then(function(sshkey){
      $scope.sshkey = sshkey;
    });
  })

  ;