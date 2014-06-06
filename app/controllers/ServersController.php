<?php

namespace DW\Controller;

class ServersController extends AbstractController {
	
	public function init() {
		$this->get( '/servers', 'getAll' );
		$this->get( '/servers/:id', 'getServer' );
		$this->delete( '/servers/:id', 'deleteServer' );
		$this->put( '/servers', 'addServer' );
		$this->post( '/servers/:id', 'updateServer' );
	}

	public function getAllAction() {
		$this->view->servers = array();

		$servers = \DW\Model\Server::getAll();
		foreach( $servers as $server ) {
			$this->view->servers[] = $server->getValues( true );
		}

		$this->output();
	}

	public function getServerAction( $id ) {
		$output = array();
		$server = \DW\Model\Server::getById( $id );

		if( ! $server instanceof \DW\Model\Server ) {
			return $this->throwError( sprintf( "Can't find server id %d", $id ) );
		}
		else {
			$this->view->server = (object) $server->getValues( true );
		}
		
		$this->output();
	}

	public function deleteServerAction( $id ) {

	}

	public function addServerAction() {
		$params = $this->getJsonRequest();
		$server = new \DW\Model\Server( $params );
		$server->save();

		$this->view->insertedId = $server->getId();

		$this->output();
	}

	public function updateServerAction( $id ) {
		$server = \DW\Model\Server::getById( $id );

		if( ! $server instanceof \DW\Model\Server ) {
			return $this->throwError( sprintf( "Can't find server id %d", $id ) );
		}
		else {
			$server->populate( $this->getJsonRequest() );
			$server->save();
			$this->view->updatedId = $server->getId();
		}

		$this->output();
	}

}