<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Commands_Delete_HC_MVC extends _HC_MVC
{
	protected $table = NULL;

	protected $relations = array();
	protected $reltable = 'relations';
	protected $relfield = 'relation_name';

	public function set_table( $table )
	{
		$this->table = $table;

		$all_relations_config = $this->app->config->get('relations');
		$this->relations = isset($all_relations_config[$table]) ? $all_relations_config[$table] : array();

		return $this;
	}

	// DELETE
	public function execute( $id )
	{
		$return = FALSE;

		if( ! $id ){
			return $return;
		}

		$q = $this->app->db->query_builder();
		$q->where( 'id', $id );
		$sql = $q->get_compiled_delete( $this->table );

		$return = $this->app->db->query( $sql );

	// relations
		foreach( $this->relations as $k => $with_config ){
			$what = $with_config['their_class'];
			$relname = $with_config['relation_name'];
			$their_field = $with_config['their_field'];
			$many = $with_config['many'];

			$my_field = ('to_id' == $their_field) ? 'from_id' : 'to_id';

			$q
				->where( $this->relfield, $relname )
				->where( $my_field, $id )
				;
			$sql = $q->get_compiled_delete( $this->reltable );
			$this->app->db->query( $sql );
		}

		return $return;
	}
}