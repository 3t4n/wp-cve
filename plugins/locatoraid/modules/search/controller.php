<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Search_Controller_LC_HC_MVC extends _HC_MVC
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
ini_set( 'display_errors', false );

// error_reporting( E_ALL );
// ini_set( 'display_errors', true );

		$return = $this->app
			->after( array($this, 'check') )
			;
		if( $return ){
			return $return;
		}

		$uri = $this->app->make('/http/uri');

		$id = $uri->param('id');

		$search = $uri->param('search');
		$lat = $uri->param('lat');
		$lng = $uri->param('lng');
		$limit = $uri->param('limit');
		$sort = $uri->param('sort');
		$radius = $uri->param('radius');
		$offset = $uri->param('offset');

		if( $id ){
			$search = NULL;
			$lat = NULL;
			$lng = NULL;
			$sort = NULL;
			$radius = NULL;
			$offset = NULL;
			// $limit = 1;
			if( ! is_array($id) ) $limit = 1;
		}

		$results = array();

		$command = $this->app->make('/locations/commands/read');

		$command_args = array();

		if( $this->app->has_module('priority') ){
			$command_args[] = array('priority', '<>', '-1');
		}
		$command_args[] = array('with', '-all-');

		if( $id ){
			if( is_array($id) ){
				$command_args[] = array('id', 'IN', $id);
			}
			else {
				$command_args[] = $id;
			}
		}
		if( $limit ){
			$command_args[] = array('limit', $limit);
		}
		if( $sort ){
			$command_args[] = array('sort', $sort);
		}
		if( $offset ){
			$command_args[] = array('offset', $offset);
		}

		$p = $this->app->make('/locations/presenter');
		$also_take = $p->database_fields();
		$also_take[] = 'product';

		reset( $also_take );
		foreach( $also_take as $tk ){
			if( 'product' == $tk ){
				$v = $uri->param($tk);
				if( ! $v ) continue;
			}
			if( 'product' == $tk ){
				$tk2 = 'product2';
				$v2 = $uri->param($tk2);
				if( $v2 ){
					$v = $v2;
				}
				else {
					$v = $uri->param($tk);
				}
			}
			else {
				$v = $uri->param($tk);
			}

			if( is_array($v) ){
				$command_args[] = array($tk, 'IN', $v);
			}
			else {
				if( ! strlen($v) ){
					continue;
				}
				if( substr($v, 0, 1) == '_' ){
					continue;
				}
				$command_args[] = array($tk, '=', $v);
			}
		}

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
			$haveLimit = null;
			reset( $command_args );
			foreach( array_keys($command_args) as $ii ){
				if( 'limit' == $command_args[$ii][0] ){
					$haveLimit = $command_args[$ii][1];
					unset( $command_args[$ii] );
					break;
				}
			}
		}

		if( $haveProduct ){
			// $productAnd = 1;
			$appSettings = $this->app->make('/app/settings');
			$productAnd = $appSettings->get( 'front:product:result_and' );
		}

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

			if( $haveProduct ){
				$exact_results = $this->filterHaveProduct( $exact_results, $haveProduct, $productAnd );
			}
		}

		$command_args['osearch'] = array('osearch', $search);

		if( ! $exact_results ){
			$match_results = null;
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

		$search_coordinates = array();
		if( $lat && $lng && ($lat != '_LAT_') && ($lng != '_LNG_') ){
			$search_coordinates = array($lat, $lng);
		}

		if( $exact_results ){
			$results = $exact_results;
		}

		if( $results && (1 == count($results)) ){
			$match_result = current( $results );
			$lat = $match_result['latitude'];
			$lng = $match_result['longitude'];
			// $command_args[] = array('id', 'NOTIN', $match_result['id']);
		}

	// also find by geo if coordinates
		if( $lat && $lng && ($lat != '_LAT_') && ($lng != '_LNG_') ){
			$search_coordinates = array($lat, $lng);
			$command_args[] = array('lat', $lat);
			$command_args[] = array('lng', $lng);

			if( $radius ){
				$radius = (int) $radius;
				unset( $command_args['osearch'] );
				$command_args[] = array( 'having', 'computed_distance', '<=', $radius );
				$command_args[] = array( 'or_having', 'computed_distance', '=', NULL );
			}
		}

		$geoResults = array();
		if( strlen($search) ){
			if( $lat && $lng && ($lat != '_LAT_') && ($lng != '_LNG_') ){
				$geoResults = $command->execute( $command_args );
			}
		}
		else {
			$geoResults = $command->execute( $command_args );
		}

		if( (! $geoResults) && $results ){
			if( 1 == count($results) ){
				$match_result = current( $results );
				$lat = $match_result['latitude'];
				$lng = $match_result['longitude'];
				$search_coordinates = array($lat, $lng);
			}
			else {
				$search_coordinates = array();
			}
		}

$alwaysIncludePartialMatch = false;
$skipGeoIfHaveMatch = false;

// $alwaysIncludePartialMatch = true;
// $skipGeoIfHaveMatch = true;

		if( $alwaysIncludePartialMatch && $skipGeoIfHaveMatch ){
			if( $match_results ){
				$results = $results + $match_results;
			}
			if( ! $results ){
				$results = $results + $geoResults;
			}
		}
		else {
			$results = $results + $geoResults;

			if( (! $geoResults) && $match_results ){
				$results = $results + $match_results;
			}
		}

// echo "COUNT1 = " . count( $results ) . '<br>';

		if( $haveProduct ){
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

			if( $haveLimit ){
				$results = array_slice( $results, 0, $haveLimit, true );
			}
		}

		if( $results && $limit ){
			if( $limit == 1 ){
				$results = array( $results['id'] => $results );
			}
			else {
				if( $limit < count($results) ){
					$results = array_slice( $results, 0, $limit, TRUE );
				}
			}
		}

// echo "COUNT2 = " . count( $results ) . '<br>';

// _print_r( $results );
// exit;

		$return = $this->app->make('/search/view')
			->render($results, $search, $search_coordinates)
			;

		$return = $this->app
			->after( $this, $return )
			;

		if( ! defined('NTS_DEVELOPMENT2') ){
			echo $return;
			exit;
		}

		return $return;
	}
}