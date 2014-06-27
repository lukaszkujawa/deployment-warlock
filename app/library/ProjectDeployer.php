<?php

namespace DW;

class ProjectDeployer {

	private $xProcessIO;

	public function __construct( \DW\XProcesIO $xProcessIO ) {
		$this->xProcessIO = $xProcessIO;
	}

	public function deploy( \DW\Model\Project $project ) {
		$return = array();
		foreach( $project->getDeploymentsAndServers() as $deplServ ) {
			$return[] = $this->deployToServer( $deplServ['deployment'], $deplServ['server'] );
		}

		return $return;
	}

	private function deployToServer( \DW\Model\Deployment $deployment, \DW\Model\Server $server ) {
		$ssh = new SSHClient( $server );
		$filePath = $this->createScriptFile( $deployment );
		$destFileName = '/tmp/' . basename( $filePath );

		$ssh->uploadFile( $filePath, $destFileName );
		return $ssh->executeRemoteScript( 	$destFileName, 
											array( $this->xProcessIO, 'onStdOut'), 
											array( $this->xProcessIO, 'onStdErr' ) );
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