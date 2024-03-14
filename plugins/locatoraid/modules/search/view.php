<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Search_View_LC_HC_MVC extends _HC_MVC
{
	public function render( $results = array(), $search = '', $search_coordinates = array() )
	{
		$prepare = $this->app->make('/search/view/prepare');
		$results = $prepare
			->execute( $results, $search, $search_coordinates )
			;

		$return = array(
			'search'				=> $search,
			'search_coordinates'	=> $search_coordinates,
			'results'				=> $results,
			);

		$return = json_encode( $return );
		return $return;
	}
}