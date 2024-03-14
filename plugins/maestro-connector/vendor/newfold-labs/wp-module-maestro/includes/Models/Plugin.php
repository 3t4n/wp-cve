<?php

namespace NewfoldLabs\WP\Module\Maestro\Models;

/**
 * Class for managing plugins
 */
class Plugin {
	/**
	 * Plugin's slug
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Plugin's name
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $name;


	/**
	 * Plugin's version
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $version;

	/**
	 * Plugin's author
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $author;

	/**
	 * Plugin's Author's URI
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $author_uri;

	/**
	 * Plugin's description
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Plugin's title
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Plugin's status, will either be active or inactive
	 *
	 * @since 0.0.1
	 *
	 * @var bool
	 */
	public $active;

	/**
	 * If the Plugin is uninstallable
	 *
	 * @since 0.0.1
	 *
	 * @var bool
	 */
	public $uninstallable;

	/**
	 * Plugin's auto-update toggle
	 *
	 * @since 0.0.1
	 *
	 * @var bool
	 */
	public $auto_updates_enabled;

	/**
	 * Plugin Updates, if any
	 *
	 * @since 0.0.1
	 *
	 * @var array
	 */
	public $update;

	/**
	 * Constructor
	 *
	 * @since 0.0.1
	 *
	 * @param string $plugin_file         The plugin's file location
	 * @param array  $plugin_update       The update for the plugin or null
	 * @param array  $plugin_details      The details for the plugin
	 * @param array  $auto_update_enabled The auto updates option for this plugin
	 */
	public function __construct( $plugin_file, $plugin_update = null, $plugin_details = null, $auto_update_enabled = null ) {
		$update_info = array();

		if ( ! function_exists( 'get_plugin_data' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( empty( $plugin_details ) ) {
			$plugin_details = get_plugin_data( WP_PLUGIN_DIR . "/$plugin_file" );
		}

		if ( is_null( $plugin_update ) ) {
			$plugin_updates = get_site_transient( 'update_plugins' );
			if ( ! empty( $plugin_updates->response[ $plugin_file ] ) ) {
				$plugin_update = array(
					'update_version'      => $plugin_updates->response[ $plugin_file ]->new_version,
					'requires_wp_version' => $plugin_updates->response[ $plugin_file ]->requires,
					'requires_php'        => $plugin_updates->response[ $plugin_file ]->requires_php,
					'tested_wp_version'   => $plugin_updates->response[ $plugin_file ]->tested,
					'last_updated'        => $plugin_updates->response[ $plugin_file ]->last_updated,
				);
			}
		}

		if ( empty( $auto_update_enabled ) ) {
			$auto_update_plugins = (array) get_site_option( 'auto_update_plugins', array() );
			$auto_update_enabled = in_array( $plugin_file, $auto_update_plugins, true );
		}

		$slug_split_array = explode( '/', $plugin_file );
		$plugin_slug      = reset( $slug_split_array );

		$this->slug                 = $plugin_slug;
		$this->name                 = $plugin_details['Name'];
		$this->version              = $plugin_details['Version'];
		$this->author               = $plugin_details['Author'];
		$this->author_uri           = $plugin_details['AuthorURI'];
		$this->description          = $plugin_details['Description'];
		$this->title                = $plugin_details['Title'];
		$this->active               = is_plugin_active( $plugin_file );
		$this->uninstallable        = is_uninstallable_plugin( $plugin_file );
		$this->auto_updates_enabled = $auto_update_enabled;
		$this->update               = empty( $plugin_update ) ? null : $plugin_update;
	}
}
