<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Commands_Create_LC_HC_MVC extends _HC_MVC
{
	public function prepare( $values = array() )
	{
		$values = $this->app
			->after( array($this, __FUNCTION__), $values )
			;
		return $values;
	}

	public function validators()
	{
		$return = array();

		$return['name'] = array(
			$this->app->make('/validate/required'),
			$this->app->make('/validate/maxlen')
				->params( 250 ),
			);

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function execute( $values = array() )
	{
		$cm = $this->app->make('/commands/manager');

		$values = $this->prepare( $values );

		$validators = $this->validators();
		$errors = $this->app->make('/validate/helper')
			->validate( $values, $validators )
			;
		if( $errors ){
			$cm->set_errors( $this, $errors );
			return;
		}

		$command = $this->app->make('/commands/create')
			->set_table('locations')
			;
		$command->execute( $values );

		$errors = $cm->errors( $command );
		if( $errors ){
			$session = $this->app->make('/session/lib');
			$session
				->set_flashdata('error', $errors)
				;
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