<?php

namespace DW;

class IterativeProjectDeployer {

	private $project;
	private $deploymentServers;
	private $current = 0;
	private $uploaded = false;
	private $ssh;

	public function __construct( \DW\Model\Project $project ) {
		$this->project = $project;
		$this->deploymentAndServers = $project->getDeploymentsAndServers();
	}

	protected function deployToServer( \DW\Model\Deployment $deployment, \DW\Model\Server $server ) {
		if( ! $this->uploaded ) {
			$this->ssh = new SSHClient( $server );
			$filePath = $this->createScriptFile( $deployment );
			$destFileName = '/tmp/' . basename( $filePath );
			$this->ssh->uploadFile( $filePath, $destFileName );
			$this->ssh->executeRemoteScript( $destFileName );
			$this->uploaded = true;
		}

		return $this->ssh->next();
	}

	private function createScriptFile( \DW\Model\Deployment $deployment ) {
		$fileName =  sprintf( '%s/cache/dep-%d-%s', 
								APP_PATH, 
								$deployment->getId(), 
								strtotime( $deployment->last_update ) );

		if( file_exists( $fileName ) ) {
			return $fileName;
		}

		file_put_contents( $fileName, $deployment->script );

		return $fileName;
	}

	public function next() {
		if( ! isset( $this->deploymentAndServers[ $this->current ] ) ) {
			return false;
		}

		$deplServ = $this->deploymentAndServers[ $this->current ];

		$ret = $this->deployToServer( $deplServ['deployment'], $deplServ['server'] );

		if( ! $ret ) {
			$this->current++;
			$this->uploaded = false;
			return $this->next();
		}
		else {
			return $ret;
		}
	}


}