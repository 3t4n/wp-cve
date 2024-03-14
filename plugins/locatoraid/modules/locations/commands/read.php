<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Commands_Read_LC_HC_MVC extends _HC_MVC
{
	public function args( $return = array() )
	{
		if( ! is_array($return) ){
			$return = array( $return );
		}

		// $return[] = array('sort', 'name', 'asc');

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;
		return $return;
	}

	public function execute( $args = array() )
	{
		$args = $this->args( $args );

		$command = $this->app->make('/commands/read')
			->set_table('locations')
			;

		$search_in = array('name', 'street1', 'street2', 'city', 'state', 'zip', 'country');

		$miscFields = array();
		$p = $this->app->make('/locations/presenter');
		$fields = $p->fields();
		$fields = array_keys( $fields );
		$allMiscFields = array_filter( $fields, function($e){ return ( 'misc' === substr($e, 0, strlen('misc')) ); } );

		$appSettings = $this->app->make('/app/settings');
		foreach( $allMiscFields as $e ){
			$thisFieldPname = 'fields:' . $e . ':use';
			$thisFieldConf = $appSettings->get( $thisFieldPname );
			if( $thisFieldConf ){
				$miscFields[] = $e;
			}
		}

		$search_in = array_merge( $search_in, $miscFields );

		$command
			->set_search_in( $search_in )
			;

		$args = $command->prepare_args( $args, array('lat', 'lng', 'radius') );

		$mylat = NULL;
		$mylng = NULL;
		$radius = NULL;

		foreach( $args['SKIP'] as $arg ){
			$k = array_shift( $arg );
			switch( $k ){
				case 'lng':
					$mylng = array_shift( $arg );
					break;

				case 'lat':
					$mylat = array_shift( $arg );
					break;

				case 'radius':
					$radius = array_shift( $arg );
					$radius = (int) $radius;
					break;
			}
		}

		if( $mylat && $mylng ){
			$app_settings = $this->app->make('/app/settings');
			$measure = $app_settings->get('core:measure');

			$args['WHERE'][] = array( 'latitude', '<>', NULL );
			$args['WHERE'][] = array( 'latitude', '<>', 0 );

		/* miles */
			if( $measure == 'mi' ){
				$nau2measure = 1.1508;
				$per_grad = 69;
			}
		/* km */
			else {
				$nau2measure = 1.852; 
				$per_grad = 111.04;
			}

			$formula = "
				DEGREES(
				ACOS(
					SIN(RADIANS(latitude)) * SIN(RADIANS($mylat))
				+	COS(RADIANS(latitude)) * COS(RADIANS($mylat))
				*	COS(RADIANS(longitude - ($mylng)))
				) * 60 * $nau2measure
				)
				";

			if( $args['COUNT'] ){
				if( $radius ){
					$args['WHERE'][] = array( $formula, '<=', $radius );
				}
			}
			else {
				$add_select = $formula . ' AS computed_distance';
				if( $args['SELECT'] ){
					$args['SELECT'][] = $add_select;
				}
				else {
					$args['SELECT'] = array('locations.*', $add_select);
				}
				$args['SORT'] = array_merge( array(array('computed_distance', 'asc')), $args['SORT'] );
			}
		}

		if( ! isset($args['SORT']) ){
			$args['SORT'] = array();
		}
		if( ! $args['SORT'] ){
			$args['SORT'] = array();
		}

	// if sort by name then move it up, otherwise add to the end 
		$argSortByName = array();

		$argSortCount = count( $args['SORT'] );
		for( $ii = 0; $ii < $argSortCount; $ii++ ){
			$argSort = $args['SORT'][$ii];
			if( 'name' == $argSort[0] ){
				$argSortByName = $argSort;
				array_splice( $args['SORT'], $ii, 1 );
				break;
			}
		}

		if( $argSortByName ){
			$args['SORT'] = array_merge( array($argSortByName), $args['SORT'] );
		}
		else {
			if( ! isset($args['SORT']) ){
				$args['SORT'] = array();
			}
			if( ! $args['SORT'] ){
				$args['SORT'] = array();
			}
			$args['SORT'] = array_merge( $args['SORT'], array(array('name', 'asc')) );
		}

		$return = $command
			->execute( $args )
			;

		$return = $this->app
			->after( $this, $return )
			;
		return $return;
	}
}