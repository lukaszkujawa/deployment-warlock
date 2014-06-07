<?php

namespace DW\Controller;

use DW\Model\Project;

class DeploymentsController extends AbstractController {
	
	public function init() {
		$this->get( '/deployments', 'getAll' );
		$this->get( '/deployments/:id', 'getDeployment' );
		$this->delete( '/deployments/:id', 'deleteDeployment' );
		$this->put( '/deployments', 'addDeployment' );
		$this->post( '/deployments/:id', 'updateDeployment' );
	}

	public function getAllAction() {
		$this->view->deployments = array();

		$deployments = \DW\Model\Deployment::getAll();
		foreach( $deployments as $deployment ) {
			$this->view->deployments[] = $deployment->getValues( true );
		}

		$this->output();
	}

	public function getDeploymentAction( $id ) {
		$output = array();
		$deployment = \DW\Model\Deployment::getById( $id );

		if( ! $deployment instanceof \DW\Model\Deployment ) {
			return $this->throwError( sprintf( "Can't find deployment id %d", $id ) );
		}
		else {
			$this->view->deployment = (object) $deployment->getValues( true );
		}
		
		$this->output();
	}

	public function deleteDeploymentAction( $id ) {

	}

	public function addDeploymentAction() {
		$params = $this->getJsonRequest();
		$deployment = new \DW\Model\Deployment( $params );
		$deployment->save();

		$this->view->insertedId = $deployment->getId();

		$this->output();
	}

	public function updateDeploymentAction( $id ) {

	}

}