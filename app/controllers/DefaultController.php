<?php

namespace DW\Controller;

class DefaultController extends AbstractController {

	public function init() {
		$this->get( '/', 'frontend' );
	}

	public function frontendAction() {
		$this->getApp()->render('index.php');
	}

}