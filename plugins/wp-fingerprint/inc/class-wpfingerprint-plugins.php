<?php
class WPFingerprint_Plugins{
	

	private $plugins;

	function __construct()
	{

	}

	function get()
	{

		if(empty($this->plugins) && !is_array($this->plugins)) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$this->plugins = get_plugins();
		}
		return $this->plugins;
	}

	function all()
	{
		$all = array_keys($this->get());
		$return = array();
		foreach ($all as $plugin => $data) {
			$slug = $this->get_plugin_name( $data );
			$return[$plugin] = array(
				'slug' => $slug,
				'version' => $this->plugins[$data]['Version'],
				'path' => $this->path($data),
				'files' => array(),
				'checksum' => $this->get_plugin_checksum( $slug )
			);
		}
		return $return;
	}

	function path($plugin)
	{
		$path = plugin_dir_path(WP_PLUGIN_DIR.'/'.$plugin);
		if( $path == WP_PLUGIN_DIR.'/') $path = WP_PLUGIN_DIR.'/'.$plugin;
		return $path;
	}

	function get_plugin_name( $basename )
	{
		if ( false === strpos( $basename, '/' ) ) {
			$name = basename( $basename, '.php' );
		} else {
			$name = dirname( $basename );
		}
		return $name;
	}

	function get_all_plugin_names() {
		$names = array();
		foreach ( get_plugins() as $file => $details ) {
			$names[] = $this->get_plugin_name( $file );
		}
		return $names;
	}

	function get_some_plugins_names($plugin)
	{
		$plugins = array();
		if(!is_array($plugin))
		{
			return $plugins[] = $plugin;
		}
		else{
			//Explicit assumption this is a flat array
			return $plugin;
		}
	}

	function get_plugin_files( $path ) {
		$folder = dirname( $this->get_absolute_path( $path ) );
		if ( WP_PLUGIN_DIR === $folder ) {
			return (array) $path;
		}
		return $this->get_files( trailingslashit( $folder ) );
	}

	function get_files( $path ) {
		$filtered_files = array();
		try {
			$files = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $path,
					RecursiveDirectoryIterator::SKIP_DOTS ),
				RecursiveIteratorIterator::CHILD_FIRST
			);
			foreach ( $files as $file_info ) {
				$pathname = self::normalize_directory_separators( substr( $file_info->getPathname(), strlen( $path ) ) );
				if ( $file_info->isFile()) {
					$filtered_files[] = $pathname;
				}
			}
		} catch ( Exception $e ) {

		}
		return $filtered_files;
	}

	public static function normalize_directory_separators( $path ) {
		return str_replace( '\\', '/', $path );
	}

	function get_plugin_version($slug)
	{
		$plugins = $this->get();
		foreach($plugins as $plugin)
		{
			if($plugin['TextDomain'] == $slug)
			{
				return $plugin['Version'];
			}
		}
	}

	function get_plugin_checksum($slug)
	{
		$return = null;
		$checksums = get_option('wpfingerprint_checksum');
		if(is_array($checksums)) $return = checksums[$slug];
		return $return;
	}

	function update_plugin_checksums($slug, $checksum)
	{
		$checksums = get_option('wpfingerprint_checksum');
		$checksums[$slug] = $checksum;
		return update_option('wpfingerprint_checksum', $checksums);
	}

	function remove_plugin_checksums()
	{
		if(get_option('wpfingerprint_checksum')) return update_option('wpfingerprint_checksum', array());
	}

	function read_file_contents($plugin, $file)
	{

		return file_get_contents($this->path($plugin).'/'.$file);
	}

	function filter_files($slug)
	{
		$no_check = array(
			'readme.txt',
			'readme.md',
			'README.md',
			'README.txt'
		);
		if( in_array($slug, $no_check)) return true;
		return false;
	}

}
