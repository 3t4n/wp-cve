<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Commands_Read_HC_MVC extends _HC_MVC
{
	protected $table = NULL;
	protected $search_in = array('title');

	static $query_cache = array();
	protected $use_cache = TRUE;

	protected $relations = array();
	protected $reltable = 'relations';
	protected $relfield = 'relation_name';

	protected $id_field = 'id';
	protected $return_one = FALSE;

	public function is_return_one()
	{
		return $this->return_one;
	}

	public function set_table( $table )
	{
		$this->table = $table;

		$all_relations_config = $this->app->config->get('relations');
		$this->relations = isset($all_relations_config[$table]) ? $all_relations_config[$table] : array();

		return $this;
	}

	public function set_search_in( $fields = array() ){
		$this->search_in = $fields;
		return $this;
	}
	
	public function prepare_args( $args = array(), $skip = array() )
	{
		if( ! is_array($args) ){
			$args = array( $args );
		}

		if( isset($args['_PREPARED']) && $args['_PREPARED'] ){
			return $args;
		}

		$this->return_one = FALSE;
		$return = array(
			'_PREPARED'		=> TRUE,
			'DISTINCT'		=> FALSE,
			'COUNT'			=> FALSE,
			'SELECT'		=> array(),
			'LIMIT'			=> array(),
			'SORT'			=> array(),
			'WHERE'			=> array(),
			'WHERE_RELATED'	=> array(),
			'HAVING'		=> array(),
			'OR_HAVING'		=> array(),
			'WITH'			=> array(),
			'SEARCH'		=> NULL,
			'OSEARCH'		=> NULL,
			'GROUPBY'		=> NULL,
			'SKIP'			=> array(),
			);

		$allowed_compares = array( '=', '<>', '>=', '<=', '>', '<', 'IN', 'NOTIN', 'LIKE', '&');
		$special = array( 'groupby', 'having', 'or_having', 'select', 'limit', 'with', 'distinct', 'sort', 'count', 'search', 'osearch' );

		foreach( $args as $arg ){
			if( ! is_array($arg) ){
				switch( $arg ){
					case 'count':
						$arg = array( 'count', 1 );
						break;
					default:
						$arg = array( $this->id_field, '=', $arg );
						break;
				}
			}

			$k = $arg[0];
			if( in_array($k, $skip) ){
				$return['SKIP'][] = $arg;
				continue;
			}

			$having = ($k == 'having') ? TRUE : FALSE;
			$orHaving = ($k == 'or_having') ? TRUE : FALSE;

			if( in_array($k, $special) ){
				array_shift( $arg );

				switch( $k ){
					case 'groupby':
						$v = array_shift( $arg );
						$return['GROUPBY'] = $v;
						$return['SELECT'][] = $v;
						break;

					case 'search':
						$v = array_shift( $arg );
						$return['SEARCH'] = $v;
						break;

					case 'osearch':
						$v = array_shift( $arg );
						$return['OSEARCH'] = $v;
						break;

					case 'count':
						$return['COUNT'] = TRUE;
						break;

					case 'with':
						$v = array_shift( $arg );
						$deep = $arg ? array_shift($arg) : 'deep';

						if( $v == '-all-' ){
							$vs = array_keys($this->relations);
						}
						else {
							$vs = array( $v );
						}

						foreach( $vs as $v ){
							if( ! isset($this->relations[$v]) ){
								echo '"' . $this->table . '" is not related to "' . $v . '"';
								continue;
							}
							$return['WITH'][] = array( $v, $deep );
						}
						break;

					case 'distinct':
						$v = array_shift( $arg );
						$return['DISTINCT'] = TRUE;
						$return['SELECT'][] = $v;
						break;

					case 'select':
						$v = array_shift($arg);
						if( ! is_array($v) ){
							$v = array( $v );
						}
						$return['SELECT'] = array_merge( $return['SELECT'], $v );
						break;

					case 'limit':
						$v = array_shift( $arg );
						$offset = array_shift( $arg );
						$return['LIMIT'] = array( $v, $offset );
						break;

					case 'sort':
						$sort_by = array_shift( $arg );

						if( is_array($sort_by) ){
							$arg2 = $sort_by;
							$sort_by = array_shift($arg2);
							$sort_how = $arg2 ? array_shift($arg2) : 'asc';
						}
						else {
							$sort_how = strtolower( $arg ? array_shift( $arg ) : 'asc' );
						}

						if( ! in_array($sort_how, array('asc', 'desc')) ){
							echo "SORTING '$sort_how' IS NOT ALLOWED, ONLY ASC OR DESC!<br>";
							$sort_how = 'asc';
						}
						$return['SORT'][] = array( $sort_by, $sort_how );
						break;
				}

				if( ! ($having OR $orHaving) ){
					continue;
				}
			}

		// WHERE
			if( count($arg) < 3 ){
				echo "FOR WHERE ARGUMENTS REQUIRE 3 PARAMS: '$k'";
				_print_r( $args );
				_print_r( $arg );
				exit;
			}

			list( $k, $compare, $v ) = $arg;
			$compare = strtoupper( $compare );
			if( ! in_array($compare, $allowed_compares) ){
				echo "COMPARING BY '$compare' IS NOT ALLOWED!<br>";
				exit;
			}

			if( ($k == $this->id_field) && ( ! is_array($v) ) ){
				$v = (int) $v;
			}

			if( in_array($compare, array('IN', 'NOTIN')) && (! is_array($v)) ){
				$v = strlen($v) ? $v : 0;
				$v = array($v);
			}
			if( $v == 'null' ){
				$v = NULL;
			}
			if( ($k == $this->id_field) && ($compare == '=') ){
				$return['LIMIT'] = array(1, 0);
				$return['SORT'] = NULL;
				$this->return_one = TRUE;
			}

			if( strpos($k, '.') === FALSE ){
 				if( ! isset($this->relations[$k]) ){
					if( $having ){
						$return['HAVING'][] = array( $k, $compare, $v );
					}
					elseif( $orHaving ){
						$return['OR_HAVING'][] = array( $k, $compare, $v );
					}
					else {
						$final_k = $k;
						if( strpos($k, '(') === FALSE ){
// TODO
							$final_k = $this->table . '.' . $k;
						}

						$return['WHERE'][] = array( $final_k, $compare, $v );
					}
				}
				else {
					$related_table = $k;
					$related_k = $this->id_field;
					if( ! isset($return['WHERE_RELATED'][$related_table]) ){
						$return['WHERE_RELATED'][$related_table] = array();
					}
					$return['WHERE_RELATED'][$related_table][] = array( $related_k, $compare, $v );
				}
			}
			else {
				list( $related_table, $related_k ) = explode('.', $k);
				if( ! isset($return['WHERE_RELATED'][$related_table]) ){
					$return['WHERE_RELATED'][$related_table] = array();
				}
				$return['WHERE_RELATED'][$related_table][] = array( $related_k, $compare, $v );
			}
		}

		return $return;
	}

	public function prepare_query( $args = array() )
	{
		$q = $this->app->db->query_builder();

		$escape = TRUE;

		if( $args['SEARCH'] ){
			$q->group_start();
			reset( $this->search_in );
			foreach( $this->search_in as $k ){
				$q->or_like( $k, $args['SEARCH'] );
			}
			$q->group_end();
		}

	// optional search - probably we can find but also add 1=1
		if( $args['OSEARCH'] ){
			$q->group_start();
			reset( $this->search_in );
			foreach( $this->search_in as $k ){
				$q->or_like( $k, $args['OSEARCH'] );
			}
			$q->or_where('1', '1', FALSE);
			$q->group_end();
		}

		if( $args['GROUPBY'] ){
			$q->group_by( $args['GROUPBY'] );
		}

		if( $args['DISTINCT'] ){
			$q->distinct();
		}

		if( $args['COUNT'] ){
			$args['SELECT'][] = 'COUNT(*) AS numrows';
			// $q->select( 'COUNT(*) AS numrows' );
			// $args['SELECT'] = array();
			$args['LIMIT'] = array();
			$args['SORT'] = NULL;
		}

		if( $args['LIMIT'] ){
			$q->limit( $args['LIMIT'][0], $args['LIMIT'][1] );
		}

		if( $args['SORT'] ){
			foreach( $args['SORT'] as $s ){
				$q->order_by( $s[0], $s[1] );
			}
		}

		foreach( $args['WHERE'] as $w ){
			list( $k, $compare, $v ) = $w;

			switch( $compare ){
				case 'IN':
					$q->where_in( $k, $v );
					break;

				case 'NOTIN':
					$q->where_not_in( $k, $v );
					break;

				case 'LIKE':
					$q->like( $k, $v );
					break;

				default:
					$how = ' ' . $compare;
					$q->where($k . $how, $v, $escape);
					break;
			}
		}

		foreach( $args['HAVING'] as $w ){
			list( $k, $compare, $v ) = $w;
			$how = ' ' . $compare;
			$q->having($k . $how, $v, $escape);
		}

		foreach( $args['OR_HAVING'] as $w ){
			list( $k, $compare, $v ) = $w;
			$how = ' ' . $compare;
			$q->or_having($k . $how, $v, $escape);
		}

		if( $args['WHERE_RELATED'] ){
// _print_r( $args['WHERE_RELATED'] );
			foreach( $args['WHERE_RELATED'] as $related => $related_where ){
				$with_config = $this->relations[$related];

				$what = $with_config['their_class'];
				$relname = isset($with_config['relation_name']) ? $with_config['relation_name'] : NULL;

				$their_field = isset($with_config['their_field']) ? $with_config['their_field'] : NULL;
				$my_field = isset($with_config['my_field']) ? $with_config['my_field'] : NULL;

				$many = $with_config['many'];

			// through relations table or own tables
				$simple_related = $relname ? FALSE : TRUE;

				if( $simple_related ){
					if( $my_field ){
						$rel_alias = 'relation_' . $related;

						$q->join(
							$what . ' AS ' . $rel_alias,
							$this->table . '.' . $this->id_field . ' = ' . $rel_alias . '.' . $my_field,
							'LEFT'
							);
					}
					else {
					}
				}
				else {
					$my_field = ('to_id' == $their_field) ? 'from_id' : 'to_id';
					$rel_alias = 'relation_' . $related;

					$q->join(
						$this->reltable . ' AS ' . $rel_alias,
						$this->table . '.' . $this->id_field . ' = ' . $rel_alias . '.' . $my_field,
						'LEFT'
						);
					$q->distinct();
					$q->where( $rel_alias . '.' . $this->relfield, $relname );
				}

				if( ! $args['SELECT'] ){
					$args['SELECT'][] = '*';
				}
				
				for( $jj = 0; $jj < count($args['SELECT']); $jj++ ){
					$this_k = $args['SELECT'][$jj];
					if( (strpos($this_k, '(') === FALSE) && (strpos($this_k, '.') === FALSE) ){
						$this_k = $this->table . '.' . $this_k;
						$args['SELECT'][$jj] = $this_k;
					}
				}

			// now load ids from target table.
			// don't do another join as it will not work for special cases like wordpress users or custom posts
				if( 
					(count($related_where) == 1) &&
					($related_where[0][0] == $this->id_field) &&
					( in_array($related_where[0][1], array('=', 'IN')) )
					){
						$related_ids = is_array($related_where[0][2]) ? $related_where[0][2] : array($related_where[0][2]);
					}
				else {
					$related_command_slug = '/' . $what . '/commands/read';
					$related_command = $this->app->make( $related_command_slug );

					$related_args = $related_where;
					$related_args[] = array( 'select', 'id' );
	// _print_r( $related_args );
					$related_results = $related_command
						->execute( $related_args )
						;
					$related_ids = array();
					foreach( $related_results as $relres ){
						$related_ids[] = (int) $relres['id'];
					}
				}

				if( count($related_ids) == 0 ){
					return NULL;
				}
				elseif( count($related_ids) == 1 ){
					$related_id = (int) array_shift($related_ids);
					if( $simple_related ){
						if( $my_field ){
							$q->where( $rel_alias . '.' . $this->id_field, $related_id );
						}
						else {
							$q->where( $their_field, $related_id );
						}
					}
					else {
						$q->where( $rel_alias . '.' . $their_field, $related_id );
					}
				}
				else {
					if( $simple_related ){
						if( $my_field ){
							$q->where_in( $rel_alias . '.' . $this->id_field, $related_ids );
						}
						else {
							$q->where_in( $their_field, $related_ids );
						}
					}
					else {
						$q->where_in( $rel_alias . '.' . $their_field, $related_ids );
					}
				}

// echo "RELATED IDS";
// echo "$rel_alias . '.' . $their_field";
// _print_r( $related_ids );
				// _print_r( $related_where );

				// $q->join(
					// $what . ' AS ' . $what_alias,
					// $what_alias . '.' . $this->id_field . ' = ' . $rel_alias . '.' . $their_field,
					// 'LEFT'
					// );
			}

			// $include_related = array();
			// foreach( $args['WHERE_RELATED']
		}

		if( $args['SELECT'] ){
			$q->select( $args['SELECT'] );
		}

// _print_r( $args['WHERE_RELATED'] );
		return $q;
	}

	public function execute( $args = array() )
	{
		$return = array();
		$args = $this->prepare_args( $args );

		if( $this->use_cache ){
			$cache_key = $this->table . ':' . json_encode($args);
			if( isset(self::$query_cache[$cache_key]) ){
				// echo "ON CACHE: '$sql'<br>";
				$return = self::$query_cache[$cache_key];
				return $return;
			}
		}

		$q = $this->prepare_query( $args );
		if( $q !== NULL ){
			$sql = $q->get_compiled_select( $this->table );

			$results = $this->app->db->query( $sql );
			if( $args['COUNT'] ){
				if( $args['GROUPBY'] ){
					$grby = $args['GROUPBY'];
					$return = array();
					if( $results ){
						while( $result = array_shift($results) ){
							$return[$result[$grby]] = $result['numrows'];
						}
					}
				}
				else {
					$return = 0;
					if( $results ){
						$results = array_shift($results);
						$return = $results['numrows'];
					}
				}
				return $return;
			}

			if( is_array($results) ){
				foreach( $results as $row ){
					$this_id = $row[$this->id_field];
					$return[ $this_id ] = $row;
				}
			}

		// with
			$withs = $args['WITH'];
			if( $withs && $return ){
				$return = $this->load_with( $return, $withs );
			}

			$return_one = ( isset($args['LIMIT'][0]) && ($args['LIMIT'][0] == 1) ) ? TRUE : FALSE;
			if( $return_one ){
				$return = array_shift( $return );
			}
		}

		if( $this->use_cache ){
			self::$query_cache[$cache_key] = $return;
		}

		return $return;
	}

	public function load_with( $return, $withs )
	{
		$q = $this->app->db->query_builder();
		$db = $this->app->db;

		$my_ids = array_keys( $return );

		foreach( $withs as $with_key => $with_config ){
			list( $with, $deep ) = $with_config;

			$with_config = $this->relations[$with];

			$their_ids = array();
			$relations = array();

			$relname = isset($with_config['relation_name']) ? $with_config['relation_name'] : NULL;
			$many = $with_config['many'];
			$their_field = isset($with_config['their_field']) ? $with_config['their_field'] : NULL;
			$my_field = isset($with_config['my_field']) ? $with_config['my_field'] : NULL;
			$what = $with_config['their_class'];

		// through relations table or own tables
			$simple_related = $relname ? FALSE : TRUE;

		// define their ids
			if( $simple_related ){
				if( $my_field ){
					$with_command_slug = '/' . $what . '/commands/read';
					$with_command = $this->app->make($with_command_slug);

					$their_args = array();
					if( count($my_ids) == 1 ){
						$their_args[] = array( $my_field, '=', $my_ids[0] );
					}
					else {
						$their_args[] = array( $my_field, 'IN', $my_ids );
					}

					if( 'deep' != $deep ){
						$their_args[] = array('select', array($this->id_field, $my_field));
					}

					$their_return = $with_command->execute( $their_args );
					foreach( $their_return as $their_id => $their_model ){
						$myid = $their_model[$my_field];
						if( ! isset($relations[$myid]) ){
							$relations[$myid] = array();
						}
						$relations[$myid][] = $their_id;
					}
				}
				else {
					reset( $return );
					foreach( $return as $row ){
						if( ! $row[$their_field] ){
							continue;
						}
						$their_ids[ $row[$their_field] ] = (int) $row[$their_field];
						if( ! isset($relations[ $row[$this->id_field] ]) ){
							$relations[ $row[$this->id_field] ] = array();
						}
						$relations[ $row[$this->id_field] ][] = $row[$their_field];
					}
				}
			}
			else {
				$my_field = ('to_id' == $their_field) ? 'from_id' : 'to_id';

				$q
					->where( $this->relfield, $relname )
					->select( array($their_field, $my_field) )
					;
				if( count($my_ids) == 1 ){
					$q->where( $my_field, $my_ids[0] );
				}
				else {
					$q->where_in( $my_field, $my_ids );
				}

				$sql = $q->get_compiled_select( $this->reltable );
				$results = $db->query( $sql );
				foreach( $results as $row ){
					$their_ids[ $row[$their_field] ] = (int) $row[$their_field];
					if( ! isset($relations[ $row[$my_field] ]) ){
						$relations[ $row[$my_field] ] = array();
					}
					$relations[ $row[$my_field] ][] = $row[$their_field];
				}
			}

		// now load their objects
			if( ('deep' == $deep) && $their_ids){
				$with_command_slug = '/' . $what . '/commands/read';
				$with_command = $this->app->make($with_command_slug);

				if( count($their_ids) == 1 ){
					$their_one_id = array_shift($their_ids);
					$their_return = array();
					$their_return[$their_one_id] = $with_command
						->execute( $their_one_id )
						;
				}
				else {
					$their_return = $with_command
						->execute( array( array('id', 'IN', $their_ids) ) )
						;
				}
			}

// _print_r( $relations );
			reset( $my_ids );
			foreach( $my_ids as $myid ){
				$return[$myid][$with] = $many ? array() : NULL;

				if( ! (isset($relations[$myid]) && $relations[$myid]) ){
					continue;
				}

				if( 'deep' == $deep ){
					foreach( $their_return as $their_id => $their_model ){
						if( ! in_array($their_id, $relations[$myid]) ){
							continue;
						}

						if( $many ){
							$return[$myid][$with][$their_id] = $their_return[$their_id];
						}
						else {
							$return[$myid][$with] = $their_return[$their_id];
							break;
						}
					}
				}
				else {
					if( $many ){
						$return[$myid][$with] = $relations[$myid];
					}
					else {
						$return[$myid][$with] = array_shift( $relations[$myid] );
					}
				}
			}
		}

		return $return;
	}
}