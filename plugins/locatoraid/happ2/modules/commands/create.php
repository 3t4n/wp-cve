<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Commands_Create_HC_MVC extends _HC_MVC
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

// CREATE
	public function execute( $values )
	{
		$cm = $this->app->make('/commands/manager');

		$results = $values;

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
		$q->set( $values );
		$sql = $q->get_compiled_insert( $this->table );
		$result = $this->app->db->query( $sql );

		if( FALSE === $result ){
			$errors = __('Database Error', 'locatoraid') . ': ' . $this->app->db->get_error();
			$cm->set_errors( $this, $errors );
			return;
		}

		$id = $this->app->db->insert_id();
		$results['id'] = $id;

	// relations
		foreach( $related as $k => $v ){
			if( ! isset($this->relations[$k]) ){
				echo '"' . $this->table . '" is not related to "' . $k . '"';
				continue;
			}

			if( ! is_array($v) ){
				$v = array($v);
			}

			$with_config = $this->relations[$k];

			$relname = isset($with_config['relation_name']) ? $with_config['relation_name'] : NULL;

		// relation through relations table
			if( $relname ){
				$their_field = $with_config['their_field'];
				$my_field = ('to_id' == $their_field) ? 'from_id' : 'to_id';
				// $rel_alias = 'relation_' . $related;

				foreach( $v as $their_id ){
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
		// relation through object table
			else {
				$my_field = isset($with_config['my_field']) ? $with_config['my_field'] : NULL;
				$their_field = isset($with_config['their_field']) ? $with_config['their_field'] : NULL;
				$what = $with_config['their_class'];

			// through their table
				if( $my_field ){
					$their_command_slug = '/' . $what . '/commands/update';
					$their_command = $this->app->make( $their_command_slug );

					$values = array( $my_field => $id );
					foreach( $v as $their_id ){
						$their_command->execute( $their_id, $values );
					}
				}
			// through my table
				else {
					foreach( $v as $their_id ){
						$q
							->set( array($their_field => $their_id) )
							->where( $this->id_field, $id )
							;
						$sql = $q->get_compiled_update( $this->table );
						$this->app->db->query( $sql );
					}
				}
				$what = $with_config['their_class'];
			}
		}

		$cm->set_results( $this, $results );
	}
}