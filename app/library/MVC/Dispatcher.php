<?php

namespace DW\MVC;

class Dispatcher {
	
	const NS = '\\DW\\Controller\\';

	private $app;
	private $uri;
	private $defaultController = 'DefaultController';

	public function __construct( \Slim\Slim $app, $uri = null ) {
		$this->app = $app;
		$this->setUri( $uri );
	}

	public function getUri() {
		return $this->uri;
	}

	public function setUri( $uri = null ) {
		$this->uri = $uri === null ? $_SERVER['REQUEST_URI'] : $uri;
	}

	public function prepare() {
		$controllerClassName = self::NS . $this->defaultController;
		$uri = explode( '/', $this->getUri() );
		if( isset( $uri[ 1 ] ) ) {
			$_className = preg_replace( '/[^a-zA-Z0-9]/', ' ', $uri[ 1 ] );
			$_className = self::NS . ucwords( str_replace( ' ', '', $_className )  ) . 'Controller';
			if( class_exists( $_className ) ) {
				$controllerClassName = $_className;
			}
		}

		return new $controllerClassName( $this->app );
	}

}