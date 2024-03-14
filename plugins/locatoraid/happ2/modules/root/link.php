<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Root_Link_HC_MVC extends _HC_MVC
{
	public function single_instance()
	{}

	public function execute( $slug )
	{
		$slug = trim($slug, '/');

		$return = $this->app
			->after( $this, $slug )
			;

		return $return;
	}
}