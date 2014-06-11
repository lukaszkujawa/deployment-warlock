<?php

namespace DW\Model;

class Project extends \DW\ORM\Model {
	
	public $name;

	protected $deploymentsServers = array();
	protected static $tableName = 'projects';

	public function populate( $data ) {
		parent::populate( $data );
		$this->deploymentAndServers = array();
		foreach( $data->deploymentAndServers as $ds ) {
			$this->deploymentAndServers[] = array(
				'deployment' => new Deployment( $ds->deployment ),
				'server' => new Server( $ds->server )
			);
		}
	}

	public function save() {
		parent::save();
		$this->saveDeploymentsServers();
	}

	protected function saveDeploymentsServers() {
		$client = self::getClient();

		$sql = 'DELETE FROM `projects__deployments_servers` WHERE `project_id` = :project_id';
		$client->execute( $sql, array('project_id' => $this->getId() ) );

		if( sizeof( $this->deploymentAndServers ) > 0 ) {
			foreach( $this->deploymentAndServers as $i => $ds ) {
				$sql = 'INSERT INTO `projects__deployments_servers` (project_id, deployment_id, server_id) VALUES ';
				$sql .= sprintf( '(%d,:did,:sid)', $this->getId() );
				$client->execute( $sql, array(
					'did' => $ds['deployment']->getId(),
					'sid' => $ds['server']->getId()
				));
			}
			
		}

	}

	public function getDeployments() {
		return Deploymnet::getByProjectId( $this->getId() );
	}


	public function getDeploymentsAndServers() {
		if( $this->deploymentsServers != null ) {
			return $this->deploymentsServers;
		}

		$sql = 'SELECT %s,%s
				FROM `projects__deployments_servers` as pds
				JOIN `deployments` as d ON ( pds.deployment_id = d.id )
				JOIN `servers` as s ON ( pds.server_id = s.id ) 
				WHERE pds.project_id = :project_id ';

		$sql = sprintf( $sql, Deployment::getColumns('d'), Server::getColumns('s') );
		$sth = self::getClient()->execute( $sql, array( 'project_id' => $this->getId() ) );
		foreach( $this->fetchAllByPrefix( $sth ) as $row ) {
			$this->deploymentsServers[] = array(
				'deployment' => new Deployment( $row['d'] ),
				'server' => new Server( $row['s'] )
			);
		}
		
		return $this->deploymentsServers;
	}	

	public function getValues($deepSearch = false) {
		$values = parent::getValues();

		if( $deepSearch ) {
			$values['deploymentAndServers'] = $this->getDeploymentsAndServers();
		}

		return $values;
	}

}