<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class GeocodeBulk_Json_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$limit = 10;
		$command = $this->app->make('/locations/commands/read');

		$total_count = $command
			->execute(
				array(
					'count',
					array( 'latitude', '=', NULL ),
					array( 'longitude', '=', NULL )
					)
				);
		$total_count2 = $this->app->make('/locations/commands/read')
			->execute(
				array(
					'count',
					array( 'latitude', '=', '0' ),
					array( 'longitude', '=', '0' )
					)
				);
		$total_count += $total_count2;

		$locations = $command
			->execute(
				array(
					array( 'latitude', '=', NULL ),
					array( 'longitude', '=', NULL ),
					array( 'limit', $limit )
					)
				);

		if( ! $locations ){
			$locations = array();
		}

		if( count($locations) < $limit ){
			$locations2 = $command
				->execute(
					array(
						array( 'latitude', '=', 0 ),
						array( 'longitude', '=', 0 ),
						array( 'limit', $limit - count($locations) )
						)
					);

			if( ! $locations2 ){
				$locations2 = array();
			}

			$locations = array_merge( $locations, $locations2 );
		}

		$p = $this->app->make('/locations/presenter');
		$geocoder = $this->app->make('/geocode/lib');

		$out = array();
		$out['total'] = $total_count;
		$out['locations'] = array();
		foreach( $locations as $e ){
			$address = $p->present_address( $e );
			$address = $geocoder->prepare_address( $address );
			$this_e = array(
				'id'		=> $e['id'],
				'address'	=> $address,
				);
			$out['locations'][] = $this_e;
		}

		$out = json_encode( $out );
		echo $out;
		exit;
	}
}