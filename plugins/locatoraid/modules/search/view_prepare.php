<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Search_View_Prepare_LC_HC_MVC extends _HC_MVC
{
	public function execute( $results = array(), $search = '', $search_coordinates = array() )
	{
		$p = $this->app->make('/locations/presenter');
		$return = array();

		foreach( $results as $id => $res ){
			$this_return = $p->present_front($res, $search, $search_coordinates);

			if( array_key_exists('computed_distance', $res) ){
				$res['distance'] = $res['computed_distance'];
				$this_return['distance_raw'] = $res['distance'];
				$this_return['distance'] = $p->present_distance( $res );
			}

			$return[] = $this_return;
		}

		$return = $this->app
			->after( $this, $return )
			;

		return $return;
	}
}