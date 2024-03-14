<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Bulk_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$post = $this->app->make('/input/lib')->post();

		$inputs = $this->app->make('/locations/bulk/form')
			->inputs()
			;
		$helper = $this->app->make('/form/helper');

		list( $values, $errors ) = $helper->grab( $inputs, $post );

		if( $errors ){
			return $this->app->make('/http/view/response')
				->set_redirect('-referrer-') 
				;
		}

		$action = $values['action'];

		switch( $action ){
			case 'priority_normal':
				return $this->_execute_priority( $values, 0 );
				break;

			case 'priority_featured':
				return $this->_execute_priority( $values, 1 );
				break;

			case 'priority_draft':
				return $this->_execute_priority( $values, -1 );
				break;

			case 'resetcoord':
				return $this->_execute_resetcoord( $values );
				break;

			case 'delete':
				return $this->_execute_delete( $values );
				break;
		}
	}

	protected function _execute_delete( $values )
	{
		$command = $this->app->make('/locations/commands/delete');

		$ids = $values['id'];
		foreach( $ids as $id ){
			$response = $command
				->execute( $id )
				;
			if( isset($response['errors']) ){
				echo $response['errors'];
				exit;
			}
		}

	// OK
		$redirect_to = $this->app->make('/http/uri')
			->url('/locations')
			;
		return $this->app->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}

	protected function _execute_resetcoord( $values )
	{
		$command = $this->app->make('/locations/commands/update');

		$v = array(
			'latitude'	=> NULL, 
			'longitude'	=> NULL,
			);

		$ids = $values['id'];
		foreach( $ids as $id ){
			$response = $command
				->execute( $id, $v )
				;
			if( isset($response['errors']) ){
				echo $response['errors'];
				exit;
			}
		}

	// OK
		$redirect_to = $this->app->make('/http/uri')
			->url('/locations')
			;
		return $this->app->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}

	protected function _execute_priority( $values, $priority )
	{
		$command = $this->app->make('/locations/commands/update');

		$v = array(
			'priority'	=> $priority
			);

		$ids = $values['id'];
		foreach( $ids as $id ){
			$response = $command
				->execute( $id, $v )
				;
			if( isset($response['errors']) ){
				echo $response['errors'];
				exit;
			}
		}

	// OK
		$redirect_to = $this->app->make('/http/uri')
			->url('/locations')
			;
		return $this->app->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}