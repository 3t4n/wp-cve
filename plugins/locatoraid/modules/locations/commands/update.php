<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Commands_Update_LC_HC_MVC extends _HC_MVC
{
	public function prepare( $id, $values = array() )
	{
		$values = $this->app
			->after( array($this, __FUNCTION__), $values, $id )
			;
		return $values;
	}

	public function validators( $id )
	{
		$return = array();

		$return['name'] = array(
			$this->app->make('/validate/required'),
			$this->app->make('/validate/maxlen')
				->params( 250 ),
			);

		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $id )
			;

		return $return;
	}

	public function execute( $id, $values = array() )
	{
		$cm = $this->app->make('/commands/manager');

		$values = $this->prepare( $id, $values );

		$validators = $this->validators( $id );
		$errors = $this->app->make('/validate/helper')
			->validate( $values, $validators, FALSE )
			;
		if( $errors ){
			$cm->set_errors( $this, $errors );
			return;
		}

		$command = $this->app->make('/commands/update')
			->set_table('locations')
			;
		$command->execute( $id, $values );

		$errors = $cm->errors( $command );
		if( $errors ){
			$cm->set_errors( $this, $errors );
			return;
		}

		$results = $cm->results( $command );
		$cm->set_results( $this, $results );

		$this->app
			->after( $this, $this )
			;
	}
}