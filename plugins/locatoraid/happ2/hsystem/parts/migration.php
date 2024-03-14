<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
if( class_exists('Migration_HC_System') ){
	return;
}

class Migration_HC_System
{
	public $app;
	private $need_versions = array();

	public function __construct( $app, $need_versions )
	{
		$this->app = $app;

		$this->need_versions = array();

	// reorder them so that the base modules come first
		$move_on_top = array('ormrelations', 'conf');
		foreach( $move_on_top as $m ){
			if( isset($need_versions[$m]) ){
				$this->need_versions[$m] = $need_versions[$m];
				unset( $need_versions[$m] );
			}
		}
		$this->need_versions = array_merge( $this->need_versions, $need_versions );
	}

// create migrations table
	public function init()
	{
		if( ! $this->app->db->table_exists('migrations') ){
			$dbforge = $this->app->db->dbforge();
			$dbforge->add_field(array(
				'module'  => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
				'version' => array('type' => 'INT', 'constraint' => 3),
				));
			$dbforge->create_table('migrations', TRUE);
		}
	}

	protected function _get_current_versions()
	{
		$return = array();

		$q = $this->app->db->query_builder();

		$sql = $q->get_compiled_select('migrations');
		$current_results = $this->app->db->query($sql);

		if( isset($current_results[0]) && (! array_key_exists('module', $current_results[0])) ){
			$dbforge = $this->app->db->dbforge();
			$dbforge->add_column(
				'migrations',
				array(
					'module' => array(
						'type'	=> 'VARCHAR(100)',
						'null'	=> TRUE,
						),
					)
				);
			$current_results = $this->app->db->query($sql);
		}

		foreach( $current_results as $row ){
			$module = strtolower( $row['module'] );
			$return[ $module ] = $row['version'];
		}

		return $return;
	}

	protected function _update_version( $module, $version )
	{
		$q = $this->app->db->query_builder();

		$q->where( 'module', $module );
		$sql = $q->get_compiled_select('migrations');

		$rows = $this->app->db->query( $sql );

		if( count($rows) ){
			$q
				->set( array('version' => $version) )
				->where( 'module', $module )
				;
			$sql = $q->get_compiled_update('migrations');
		}
		else {
			$q
				->set( array('module' => $module, 'version' => $version) )
				->where( 'module', $module )
				;
			$sql = $q->get_compiled_insert('migrations');
		}

// echo "$sql<br>";
		return $this->app->db->query( $sql );
	}

	public function current()
	{
		$return = TRUE;

		if( ! $this->app->db->table_exists('migrations') ){
			return $return;
		}

		$current_versions = $this->_get_current_versions();

	// find which modules should be migrated
		$need_migrate = array();
		foreach( $this->need_versions as $module => $need_version ){
			$current_version = isset($current_versions[$module]) ? $current_versions[$module] : 0;
			$need_steps = array();
			while( $current_version < $need_version ){
				$current_version++;
				$need_steps[] = $current_version;
			}
			if( $need_steps ){
				$need_migrate[$module] = $need_steps;
			}
		}

		if( $need_migrate ){
			foreach( $need_migrate as $module => $steps ){
				foreach( $steps as $step ){
					$this->do_migrate( $module, $step );
				}
			}
		}
		return $return;
	}

	public function do_migrate( $module, $step )
	{
		$slug = '/' . $module . '/migration/' . $step;
		$migrator = $this->app->make( $slug );
		$migrator->up();
		$this->_update_version( $module, $step );
	}
}