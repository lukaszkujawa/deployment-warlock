<?php

namespace DW\Controller;

abstract class AbstractController {

	private $app;
	protected $view;

	public function __construct( $app ) {
		$this->app = $app;
		$this->view = new \stdClass();
		$this->init();
	}

	protected function init() {}

	protected function getApp() {
		return $this->app;
	}

	private function addRoute( $method, $path, $callback ) {
		$this->app->$method( $path, array( $this, $callback . 'Action') );
	}

	protected function get( $path, $callback ) {
		$this->addRoute( 'get', $path, $callback );
	}

	protected function post( $path, $callback ) {
		$this->addRoute( 'post', $path, $callback );
	}

	protected function put( $path, $callback ) {
		$this->addRoute( 'put', $path, $callback );
	}

	protected function delete( $path, $callback ) {
		$this->addRoute( 'delete', $path, $callback );
	}

	public function getRawRequest() {
		return $this->getApp()->request->getBody();
	}

	public function getJsonRequest() {
		return json_decode( $this->getRawRequest() );
	}

	public function output() {
		if( ! isset( $this->view->status ) ) {
			$this->view->status = 'ok';
		}

 		echo json_encode( $this->view );
	}

	public function throwError( $message, $httpStatus = 400 ) {
		if( $httpStatus == 400 ) {
			$httpMsg = 'Bad request';
		}
		else {
			$httpMsg = 'Error';
		}

		header( sprintf( 'HTTP/1.1 %d %s', $httpStatus, $httpMsg ), true, $httpStatus);

		echo json_encode(array(
			'status' => 'error',
			'error' => 1,
			'errorMessage' => $message
		));
	}
}