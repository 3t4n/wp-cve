<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Commands_Delete_LC_HC_MVC extends _HC_MVC
{
	public function execute( $id )
	{
		$command = $this->app->make('/commands/delete')
			->set_table('locations')
			;
		$return = $command
			->execute( $id )
			;

		$return = $this->app
			->after( $this, $return )
			;
		return $return;
	}
}