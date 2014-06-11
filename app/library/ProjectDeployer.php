<?php

namespace DW;

class ProjectDeployer {

	public function deploy( \DW\Model\Project $project ) {
		foreach( $project->getDeploymentsAndServers() as $deplServ ) {
			$this->deployToServer( $deplServ['deployment'], $deplServ['server'] );
		}
	}

	private function deployToServer( \DW\Model\Deployment $deployment, \DW\Model\Server $server ) {
		$ssh = new SSHClient( $server );
		$fileName = $this->createScriptFile( $deployment );
		$ssh->uploadFile( $fileName );
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

}