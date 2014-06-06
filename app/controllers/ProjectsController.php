<?php

namespace DW\Controller;

use DW\Model\Project;

class ProjectsController extends AbstractController {
	
	public function init() {
		$this->get( '/projects', 'getAll' );
		$this->get( '/projects/:id', 'getProject' );
		$this->delete( '/projects/:id', 'deleteProject' );
		$this->put( '/projects', 'addProject' );
		$this->post( '/projects/:id', 'updateProject' );
	}

	public function deleteProjectAction( $id ) {
		$project = \DW\Model\Project::getById( $id );

		if( ! $project instanceof \DW\Model\Project ) {
			return $this->throwError( sprintf( "Can't find project id %d", $id ) );
		}
		else {
			$project->delete();
			$this->view->deletedId = $project->getId();
		}

		$this->output();
	}

	public function updateProjectAction( $id ) {
		$project = \DW\Model\Project::getById( $id );

		if( ! $project instanceof \DW\Model\Project ) {
			return $this->throwError( sprintf( "Can't find project id %d", $id ) );
		}
		else {
			$project->populate( $this->getJsonRequest() );
			$project->save();
			$this->view->updatedId = $project->getId();
		}

		$this->output();
	}

	public function getProjectAction( $id ) {
		$output = array();
		$project = \DW\Model\Project::getById( $id );

		if( ! $project instanceof \DW\Model\Project ) {
			return $this->throwError( sprintf( "Can't find project id %d", $id ) );
		}
		else {
			$this->view->project = (object) $project->getValues( true );
		}
		
		$this->output();
	}

	public function getAllAction() {
		$this->view->projects = array();

		$projects = \DW\Model\Project::getAll();
		foreach( $projects as $project ) {
			$this->view->projects[] = $project->getValues( true );
		}

		$this->output();
	}

	public function addProjectAction( ) {
		$params = $this->getJsonRequest();
		$project = new \DW\Model\Project( $params );
		$project->save();

		$this->view->insertedId = $project->getId();
		
		$this->output();
	}


}