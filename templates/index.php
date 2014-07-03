<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="en" ng-app="depwar" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="en" ng-app="depwar" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="en" ng-app="depwar" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" ng-app="depwar" class="no-js"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Deployment Warlock</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/app.css"/>
</head>
<body>

  <div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
      
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Deployment Warlock</a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav" ng-controller="NavCtrl">
          <li ng-class="navClass('home')"><a href="#home">Home</a></li>
          <li ng-class="navClass('projects')"><a href="#projects">Projects</a></li>
          <li ng-class="navClass('deployments')"><a href="#deployments">Deployments</a></li>
          <li ng-class="navClass('servers')"><a href="#servers">Servers</a></li>
        </ul>
      </div>

    </div>
  </div>

  <!--[if lt IE 7]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
  <![endif]-->

  <div ng-controller="FlashMessageCtl" class="container msg-placeholder">
    <div ng-show="showMessage" class="alert alert-success">{{message}}</div>
  </div>

  <div class="container" ng-view></div>

  <div id="footer">
    <div class="container">
      <p class="text-muted">Place sticky footer content here.</p>
    </div>
  </div>

  <div ng-controller="ModalCtl" class="modal fade bs-example-modal-sm" id="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="mySmallModalLabel">Warning</h4>
        </div>
        <div class="modal-body">
          {{ message }}
          <div class="modal-buttons">
            <button ng-click="continue()" type="button" class="btn btn-danger">Continue</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="/bower_components/angular/angular.js"></script>
  <script src="/bower_components/angular-route/angular-route.js"></script>
  <script src="/bower_components/ace-builds/src-min-noconflict/ace.js"></script>
  <script src="/bower_components/angular-ui-ace/ui-ace.js"></script>

  <script src="/js/app.js"></script>
  <script src="/js/services.js"></script>
  <script src="/js/controllers.js"></script>
  <script src="/js/filters.js"></script>
  <script src="/js/directives.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>