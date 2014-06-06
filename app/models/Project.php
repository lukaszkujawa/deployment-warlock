<?php

namespace DW\Model;

class Project extends \DW\ORM\Model {
	
	public $name;

	protected static $tableName = 'projects';

	public function getDeployments() {
		return Deploymnet::getByProjectId( $this->getId() );
	}
	

}