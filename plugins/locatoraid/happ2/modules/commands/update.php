<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Commands_Update_HC_MVC extends _HC_MVC
{
	protected $table = NULL;

	protected $relations = array();
	protected $reltable = 'relations';
	protected $relfield = 'relation_name';

	protected $id_field = 'id';

	public function set_table( $table )
	{
		$this->table = $table;

		$all_relations_config = $this->app->config->get('relations');
		$this->relations = isset($all_relations_config[$table]) ? $all_relations_config[$table] : array();

		return $this;
	}

// UPDATE
	public function execute( $id, $values )
	{
		$cm = $this->app->make('/commands/manager');

		$values['id'] = $id;
		$results = $values;

	// fetch current value to log changes
		$args = array();
		$args[] = $id;
		$args[] = array('limit', 1);

		foreach( array_keys($values) as $k ){
			if( isset($this->relations[$k]) ){
				$args[] = array('with', $k, 'flat');
			}
		}

		$current = $this->app->make('/commands/read')
			->set_table( $this->table )
			->execute( $args )
			;

		$before = array();
		foreach( $values as $k => $v ){
			$results[$k] = $v;
			if( $v != $current[$k] ){
				$before[$k] = $current[$k];
			}
		}
// _print_r( $before );
// exit;

	// filter related
		$relnames = array_keys( $this->relations );
		$related = array();
		foreach( $relnames as $rn ){
			if( array_key_exists($rn, $values) ){
				$related[$rn] = $values[$rn];
				unset( $values[$rn] );
			}
		}

		$q = $this->app->db->query_builder();

		unset( $values['id'] );

		if( $values ){
			$q
				->set( $values )
				->where( 'id', $id )
				;
			$sql = $q->get_compiled_update( $this->table );
			$result = $this->app->db->query( $sql );

			if( FALSE === $result ){
				$errors = __('Database Error', 'locatoraid') . ': ' . $this->app->db->get_error();
				$cm->set_errors( $this, $errors );
				return;
			}
		}

	// relations
		foreach( $related as $k => $rel_ids ){
			if( ! isset($this->relations[$k]) ){
				echo '"' . $this->table . '" is not related to "' . $k . '"';
				continue;
			}

			$with_config = $this->relations[$k];

			$what = $with_config['their_class'];
			$relname = isset($with_config['relation_name']) ? $with_config['relation_name'] : NULL;

		// through relations table or own tables
			$simple_related = $relname ? FALSE : TRUE;

			if( ! is_array($rel_ids) ){
				$rel_ids = array($rel_ids);
			}
			$current_ids = array();

			if( $simple_related ){
				$my_field = isset($with_config['my_field']) ? $with_config['my_field'] : NULL;
				$their_field = isset($with_config['their_field']) ? $with_config['their_field'] : NULL;
				$what = $with_config['their_class'];
			// key in my table
				if( $their_field ){
					$current_ids[] = $current[$their_field];
				}
			// key in their table
				else {
					$their_command_slug = '/' . $what . '/commands/read';
					$their_command = $this->app->make( $their_command_slug );
					
					$their_command_args = array();
					$their_command_args[] = array('select', $this->id_field);
					$their_command_args[] = array($my_field, '=', $id);
					$their_entries = $their_command->execute( $their_command_args );

					foreach( $their_entries as $theire ){
						$current_ids[] = $theire[ $this->id_field ];
					}
				}
			}
			else {
				$their_field = $with_config['their_field'];

				$my_field = ('to_id' == $their_field) ? 'from_id' : 'to_id';
				// $rel_alias = 'relation_' . $related;

				$q
					->select( $their_field )
					->where( $this->relfield, $relname )
					->where( $my_field, $id )
					;

				$sql = $q->get_compiled_select( $this->reltable );
				$res = $this->app->db->query( $sql );

				foreach( $res as $r ){
					$current_ids[] = $r[$their_field];
				}
			}

			if( $current_ids && (! is_array($current_ids) ) ){
				$current_ids = array( $current_ids );
			}

			$insert_ids = array_diff( $rel_ids, $current_ids );
			$delete_ids = array_diff( $current_ids, $rel_ids );

			foreach( $insert_ids as $their_id ){
				if( $simple_related ){
				// key in my table
					if( $their_field ){
						$new_data = array(
							$their_field	=> $their_id,
							);

						$q
							->where( $this->id_field, $id )
							->set( $new_data )
							;
						$sql = $q->get_compiled_update( $this->table );
						$result = $this->app->db->query( $sql );
						if( FALSE === $result ){
							$errors = __('Database Error', 'locatoraid') . ': ' . $this->app->db->get_error();
							$cm->set_errors( $this, $errors );
							return;
						}
					}
				// key in their table
					else {
						$new_data = array(
							$my_field	=> $id,
							);

						$their_command_slug = '/' . $what . '/commands/update';
						$their_command = $this->app->make( $their_command_slug );
						$their_command->execute( $their_id, $new_data );
					}
				}
			// through relations table
				else {
					$new_data = array(
						$this->relfield		=> $relname,
						$my_field			=> $id,
						$their_field		=> $their_id,
						);

					$q->set( $new_data );
					$sql = $q->get_compiled_insert( $this->reltable );
					$result = $this->app->db->query( $sql );

					if( FALSE === $result ){
						$errors = __('Database Error', 'locatoraid') . ': ' . $this->app->db->get_error();
						$cm->set_errors( $this, $errors );
						return;
					}
				}
			}

			if( $delete_ids ){
				if( $simple_related ){
			// key in my table
					if( $their_field ){
						$new_data = array(
							$their_field	=> NULL,
							);

						$q
							->where( $this->id_field, $id )
							->set( $new_data )
							;
						if( count($delete_ids) == 1 ){
							$q
								->where( $their_field, (int) $delete_ids[0] )
								;
						}
						else {
							$q
								->where_in( $their_field, $delete_ids )
								;
						}

						$sql = $q->get_compiled_update( $this->table );
						$result = $this->app->db->query( $sql );
						if( FALSE === $result ){
							$errors = __('Database Error', 'locatoraid') . ': ' . $this->app->db->get_error();
							$cm->set_errors( $this, $errors );
							return;
						}
					}
			// key in their table
					else {
						$their_command_slug = '/' . $what . '/commands/update';
						$their_command = $this->app->make( $their_command_slug );
						$new_data = array(
							$my_field	=> NULL,
							);

						foreach( $delete_ids as $delid ){
							$their_command->execute( $delid, $new_data );
						}
					}
				}
			// through relations table
				else {
					$q
						->where( $this->relfield, $relname )
						->where( $my_field, $id )
						->where_in( $their_field, $delete_ids )
						;

					$sql = $q->get_compiled_delete( $this->reltable );
					$result = $this->app->db->query( $sql );

					if( FALSE === $result ){
						$errors = __('Database Error', 'locatoraid') . ': ' . $this->app->db->get_error();
						$cm->set_errors( $this, $errors );
						return;
					}
				}
			}
		}

		$cm->set_results( $this, $results );
		$cm->set_before( $this, $before );
	}
}