<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Search_Radius_Controller_LC_HC_MVC extends _HC_MVC
{
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
			}
		}

		if( $exact_results ){
			$results = $exact_results;
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

		$command_args[] = 'count';
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
			$this_command_args[] = array( 'radius', $r );
			$this_count = $command->execute( $this_command_args );

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
_print_r( $return );

		$return = json_encode( $return );
// echo $return;
// exit;

		return $return;
	}
}