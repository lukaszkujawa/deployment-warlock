<?php

namespace DW\Model;

class Server extends \DW\ORM\Model {
	
	const AUTH_PASSWORD = 0;
	const AUTH_PUBLIC_KEY = 1;

	public $name;
	public $address;
	public $login;
	public $auth_type;
	public $password;
	public $ssh_key_id;
	public $last_update;

	private $sshkey;

	protected static $tableName = 'servers';

	public function getSshKey() {
		if( ! $this->sshkey ) {
			$this->sshkey = SshKey::getById( $this->ssh_key_id );
		}

		return $this->sshkey;
	}

}