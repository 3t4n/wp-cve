<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Search_Radius_Controller_LC_HC_MVC extends _HC_MVC
{
	public function filterHaveProduct( array $results, array $haveProduct, $productAnd )
	{
		// filter those with product
		$ids = array_keys( $results );
		foreach( $ids as $id ){
			$thisProducts = isset($results[$id]['product']) ? array_keys( $results[$id]['product'] ) : [];
			$thisFail = false;
			if( $productAnd ){
				$thisFail = array_diff( $haveProduct, $thisProducts ) ? true : false;
			}
			else {
				$thisFail = array_intersect( $haveProduct, $thisProducts ) ? false : true;
			}
			if( $thisFail ){
				unset( $results[$id] );
			}
		}

		return $results;
	}

	public function execute()
	{
error_reporting( 0 );
ini_set( 'display_errors', FALSE );

		$uri = $this->app->make('/http/uri');

		$search = $uri->param('search');
		$lat = $uri->param('lat');
		$lng = $uri->param('lng');
		$limit = $uri->param('limit');

		$link_params = array();
		$link_params['search'] = $search;

		$results = array();
		$command = $this->app->make('/locations/commands/read');

		$command_args = array();

		$p = $this->app->make('/locations/presenter');
		$also_take = $p->database_fields();
		$also_take[] = 'product';

		$exact_results = array();
		if( strlen($search) ){
			$exact_search_in = array( 'name', 'zip', 'state', 'city', 'country' );

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

			$exact_search_in = array_merge( $exact_search_in, $miscFields );

			foreach( $exact_search_in as $search_in ){
				$exact_args = $command_args;
				$exact_args[] = array($search_in, '=', $search);
				$exact_args[] = array('with', '-all-');

				$this_exact_results = $command->execute( $exact_args );
				if( $this_exact_results ){
					$exact_results = $exact_results + $this_exact_results;
				}
			}
		}

		$command_args[] = array('osearch', $search);

		$radius_count = array();

		$search_coordinates = array();
		if( $lat && $lng && ($lat != '_LAT_') && ($lng != '_LNG_') ){
			$search_coordinates = array($lat, $lng);
		}

		if( $lat && $lng && ($lat != '_LAT_') && ($lng != '_LNG_') ){
			$command_args['lat'] = array('lat', $lat);
			$command_args['lng'] = array('lng', $lng);

			$link_params['lat'] = $lat;
			$link_params['lng'] = $lng;
		}

		reset( $also_take );
		foreach( $also_take as $tk ){
			$v = $uri->param($tk);

			if( is_array($v) ){
				$command_args[] = array($tk, 'IN', $v);
				// $link_params[$tk] = array('IN', $v);
				$link_params[$tk] = $v;
			}
			else {
				if( ! strlen($v) ){
					continue;
				}
				if( substr($v, 0, 1) == '_' ){
					continue;
				}
				$command_args[] = array($tk, '=', $v);
				$link_params[$tk] = $v;
			}
		}

		if( $this->app->has_module('priority') ){
			$command_args[] = array('priority', '<>', '-1');
		}

		$command_args[] = array('with', '-all-');

	// if we have product then remove product condition and limit, we'll process these in memory
// _print_r( $command_args );

		$haveProduct = [];
		reset( $command_args );
		foreach( array_keys($command_args) as $ii ){
			if( 'product' == $command_args[$ii][0] ){
				$haveProduct = is_array( $command_args[$ii][2] ) ? $command_args[$ii][2] : [ $command_args[$ii][2] ];
				unset( $command_args[$ii] );
				break;
			}
		}

		if( $haveProduct ){
			// $productAnd = 1;
			$appSettings = $this->app->make('/app/settings');
			$productAnd = $appSettings->get( 'front:product:result_and' );
		}

// _print_r( $command_args );

		$return = array();
		$results = array();

		$exact_results = array();
		if( strlen($search) ){
			$exact_search_in = array( 'name', 'zip', 'state', 'city', 'country' );
			foreach( $exact_search_in as $search_in ){
				$exact_args = $command_args;
				$exact_args[] = array($search_in, '=', $search);
				$exact_args[] = array('with', '-all-');
				$this_exact_results = $command->execute( $exact_args );

				if( $haveProduct ){
					$this_exact_results = $this->filterHaveProduct( $this_exact_results, $haveProduct, $productAnd );
				}

				if( $this_exact_results ){
					$exact_results = $exact_results + $this_exact_results;
				}
			}
		}

		$match_results = NULL;
		if( ! $exact_results ){
			if( strlen($search) ){
				$match_args = $command_args;
				// $match_args[] = array('name', '=', $search);
				$match_args[] = array('with', '-all-');
				$match_args[] = array('search', $search);

				$match_results = $command->execute( $match_args );

				if( $haveProduct ){
					$match_results = $this->filterHaveProduct( $match_results, $haveProduct, $productAnd );
				}
			}
		}

		if( $exact_results ){
			$results = $exact_results;
		}
		elseif( $match_results ){
			$results = $match_results;
		}

		if( $results && (1 == count($results)) ){
			$match_result = current( $results );

			$lat = $match_result['latitude'];
			$lng = $match_result['longitude'];

			$command_args['lat'] = array('lat', $lat);
			$command_args['lng'] = array('lng', $lng);

			$link_params['lat'] = $lat;
			$link_params['lng'] = $lng;
		}

if( isset($command_args['lat']) ){

		// $command_args[] = 'count';
		$radiuses = $uri->param('radius');

		if( ! $radiuses ){
			$radiuses = array( 10, 20, 50, 100, 200, 500 );
		}
		if( ! is_array($radiuses) ){
			$radiuses = array( $radiuses );
		}
		rsort( $radiuses, SORT_NUMERIC );

		$last_count = 0;
		reset( $radiuses );
		foreach( $radiuses as $r ){
			$r = (int) $r;
			$this_command_args = $command_args;

			// $this_command_args[] = array( 'radius', $r );
			$this_command_args[] = array( 'having', 'computed_distance', '<=', $r );
			$this_command_args[] = array( 'or_having', 'computed_distance', '=', NULL );

			$thisResults = $command->execute( $this_command_args );

// _print_r( $this_command_args );
// echo "COUNT1 = " . count($thisResults) . "<br>";
			if( $haveProduct ){
				$thisResults = $this->filterHaveProduct( $thisResults, $haveProduct, $productAnd );
			}
// echo "COUNT2 = " . count($thisResults) . "<br>";

			$this_count = count( $thisResults );

			if( ! $this_count ){
				break;
			}

			if( $this_count == $last_count ){
				array_pop($results); 
			}

		// remove everything above
			if( $limit && ($limit < $this_count) ){
				$results = array();
			}

			$results[ $r ] = $this_count;
			$last_count = $this_count;
		}
		$results = array_reverse( $results, TRUE );

		if( (! $results) && $match_results ){
			$this_count = count( $match_results );
			if( $results ){
				$result_radiuses = array_keys( $results );
				$thisR = current( $result_radiuses );
				$results[ $thisR ] += $this_count;
			}
			else {
				$thisR = $radiuses[ count($radiuses) - 1 ];
				$results[ $thisR ] = $this_count;
			}
		}

		foreach( $results as $radius => $count ){
			$this_link_params = $link_params;
			if( $radius ){
				$this_link_params['radius'] = $radius;
			}
			if( $limit ){
				$this_link_params['limit'] = $limit;
			}

// _print_r( $link_params );

			$link = $this->app->make('/http/uri')
				->url('/search', $this_link_params)
				;
			$return[] = array( $link, $count );
		}
// _print_r( $return );
}
else {
	$this_link_params = $link_params;
	$link = $this->app->make('/http/uri')
		->url('/search', $this_link_params)
		;
	$return[] = array( $link, $count );
}

		$return = json_encode( $return );
echo $return;
exit;

		return $return;
	}
}