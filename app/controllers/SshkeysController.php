<?php

namespace DW\Controller;

class SshkeysController extends AbstractController {

	public function init() {
		$this->get( '/sshkeys', 'getAll' );
		$this->get( '/sshkeys/:id', 'getKey' );
		$this->delete( '/sshkeys/:id', 'deleteKey' );
		$this->put( '/sshkeys', 'addKey' );
		$this->post( '/sshkeys/:id', 'updateKey' );
	}

	public function getAllAction() {
		$this->view->servers = array();

		$keys = \DW\Model\SshKey::getAll();
		foreach( $keys as $key ) {
			$this->view->sshkeys[] = $key->getValues( true );
		}

		$this->output();
	}

	public function getKeyAction( $id ) {
		$output = array();
		$sshkey = \DW\Model\SshKey::getById( $id );

		if( ! $sshkey instanceof \DW\Model\SshKey ) {
			return $this->throwError( sprintf( "Can't find key id %d", $id ) );
		}
		else {
			$this->view->sshkey = (object) $sshkey->getValues( true );
		}
		
		$this->output();
	}

	public function deleteKeyAction( $id ) {
		$sshkey = \DW\Model\SshKey::getById( $id );

		if( ! $sshkey instanceof \DW\Model\SshKey ) {
			return $this->throwError( sprintf( "Can't find key id %d", $id ) );
		}
		else {
			$sshkey->delete();
			$this->view->deletedId = $sshkey->getId();
		}

		$this->output();
	}

	public function addKeyAction() {
		$params = $this->getJsonRequest();
		$sshkey = new \DW\Model\SshKey( $params );
		$sshkey->save();

		$this->view->insertedId = $sshkey->getId();

		$this->output();
	}

	public function updateKeyAction( $id ) {
		$sshkey = \DW\Model\SshKey::getById( $id );

		if( ! $sshkey instanceof \DW\Model\SshKey ) {
			return $this->throwError( sprintf( "Can't find key id %d", $id ) );
		}
		else {
			$sshkey->populate( $this->getJsonRequest() );
			$sshkey->save();
			$this->view->updatedId = $sshkey->getId();
		}

		$this->output();
	}

}