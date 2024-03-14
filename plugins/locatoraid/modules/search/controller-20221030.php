<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Search_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
error_reporting( 0 );
ini_set( 'display_errors', FALSE );

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
			$limit = 1;
			$sort = NULL;
			$radius = NULL;
			$offset = NULL;
		}

		$results = array();

		$command = $this->app->make('/locations/commands/read');

	// name or zip code match
		// if( strlen($search) ){
			// $name_args = array();
			// $name_args[] = array('name', '=', $search);
			// $name_args[] = array('limit', 1);

			// $name_result = $command->execute( $name_args );
			// if( $name_result ){
				// $lat = $name_result['latitude'];
				// $lng = $name_result['longitude'];
			// }
			// else {
				// $zip_args = array();
				// $zip_args[] = array('zip', '=', $search);
				// $zip_args[] = array('limit', 1);

				// $zip_result = $command->execute( $zip_args );
				// if( $zip_result ){
					// $lat = $zip_result['latitude'];
					// $lng = $zip_result['longitude'];
				// }
			// }
		// }

		$command_args = array();

		if( $this->app->has_module('priority') ){
			$command_args[] = array('priority', '<>', '-1');
		}
		$command_args[] = array('with', '-all-');

		if( $id ){
			$command_args[] = $id;
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

		$command_args['osearch'] = array('osearch', $search);

		if( ! $exact_results ){
			$match_results = NULL;
			if( strlen($search) ){
				$match_args = $command_args;
				// $match_args[] = array('name', '=', $search);
				$match_args[] = array('with', '-all-');
				$match_args[] = array('search', $search);

				$match_results = $command->execute( $match_args );
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

		$results = $results + $geoResults;

		if( (! $geoResults) && $match_results ){
			$results = $results + $match_results;
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

		// $productAnd = 1;
		$appSettings = $this->app->make('/app/settings');
		$productAnd = $appSettings->get( 'front:product:result_and' );

		if( $productAnd ){
			$vProduct = $v = $uri->param( 'product' );
			if( $vProduct && is_array($vProduct) ){
				$ids = array_keys( $results );
				foreach( $ids as $id ){
					$thisProducts = isset( $results[$id]['product'] ) ? array_keys($results[$id]['product']) : array();
					if( array_diff($vProduct, $thisProducts) ){
						unset( $results[$id] );
					}
				}
			}
		}

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