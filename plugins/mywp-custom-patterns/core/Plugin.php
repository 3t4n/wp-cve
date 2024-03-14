<?php

namespace Whodunit\MywpCustomPatterns\Init;

abstract class Plugin {
	public    $slug;
	public    $version;
	public    $name;
	public    $plugin_basename;
	public    $base_dir;
	public    $plugin_url;
	public    $domain_path;
	protected $main_file;
	public    $params = array(
			'Name'       => 'name',
			'Version'    => 'version',
			'TextDomain' => 'slug',
			'DomainPath' => 'domain_path',
		);


	public function __construct() {
		$this->init_data_plugin();
	}

	protected function init_data_plugin() {

		$this->base_dir = dirname( __DIR__, 1 );
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$plugin_data = get_plugin_data( $this->main_file );

		foreach ( $plugin_data as $key => $item ) {
			if ( isset( $this->params[ $key ] ) ) {
				$val        = $this->params[ $key ];
				$this->$val = $item;
			}
		}

		$path = $this->base_dir . '/' . $this->slug . '.php';

		$this->plugin_basename = plugin_basename( $path );
		$this->plugin_url      = plugin_dir_url( $path );
	}

}
