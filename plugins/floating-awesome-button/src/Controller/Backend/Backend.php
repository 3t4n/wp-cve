<?php

namespace Fab\Controller;

! defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 *setComponent
 *
 * @package    Fab
 * @subpackage Fab/Controller
 */

use Fab\Feature\Design;
use Fab\Wordpress\Hook\Action;
use Fab\Wordpress\Hook\Filter;

class Backend extends Base {

	/**
	 * Admin constructor
	 *
	 * @return void
	 * @var    object   $plugin     Plugin configuration
	 * @pattern prototype
	 */
	public function __construct( $plugin ) {
		parent::__construct( $plugin );

		/** @backend - Handle plugin upgrade */
		$action = new Action();
		$action->setComponent( $this );
		$action->setHook( 'upgrader_process_complete' );
		$action->setCallback( 'upgrade_plugin' );
		$action->setAcceptedArgs( 2 );
		$action->setMandatory( false );
		$action->setDescription( 'Handle plugin upgrade' );
		$action->setFeature( $plugin->getFeatures()['core_backend'] );
		$this->hooks[] = $action;

		/** @backend - Eneque scripts */
		$action = clone $action;
		$action->setHook( 'admin_enqueue_scripts' );
		$action->setCallback( 'backend_enequeue' );
		$action->setAcceptedArgs( 0 );
		$action->setMandatory( true );
		$action->setDescription( 'Enqueue backend plugins assets' );
		$action->setFeature( $plugin->getFeatures()['core_backend'] );
		$this->hooks[] = $action;

		/** @backend - Add setting link for plugin in plugins page */
		$action = clone $action;
		$action->setHook( sprintf( 'plugin_action_links_%s/%s.php', $this->Plugin->getSlug(), $this->Plugin->getSlug() ) );
		$action->setCallback( 'plugin_setting_link' );
		$action->setMandatory( false );
		$action->setAcceptedArgs( 1 );
		$action->setDescription( 'Add setting link for plugin in plugins page' );
		$action->setFeature( $plugin->getFeatures()['core_backend'] );
		$this->hooks[] = $action;

		/** @backend */
		$filter = new Filter();
        $filter->setComponent( $this );
		$filter->setHook( 'plugin_row_meta' );
		$filter->setCallback( 'plugin_row_meta_references' );
		$filter->setAcceptedArgs(4);
		$filter->setMandatory( true );
		$filter->setDescription( 'Add references links Documentations & Tutorials' );
		$filter->setFeature( $plugin->getFeatures()['core_backend'] );
		$this->hooks[] = $filter;
	}

	/**
	 * Handle plugin upgrade
	 *
	 * @return void
	 */
	public function upgrade_plugin( $upgrader_object, $options ) {
		$current_plugin_path_name = plugin_basename( $this->Plugin->getConfig()->path );
		if ( $options['action'] === 'update' && $options['type'] === 'plugin' ) {
			foreach ( $options['plugins'] as $each_plugin ) {
				if ( $each_plugin == $current_plugin_path_name ) {
					/** Update options */
					$this->WP->update_option(
						'fab_config',
						(object) (
						(array) $this->Plugin->getConfig()->options + (array) $this->Plugin->getConfig()->default )
					);
				}
			}
		}
	}

	/**
	 * Eneque scripts @backend
	 *
	 * @return  void
	 */
	public function backend_enequeue() {
		/** Load Data */
		define( 'FAB_SCREEN', json_encode( $this->WP->getScreen() ) );
		$default = $this->Plugin->getConfig()->default;
		$config  = $this->Plugin->getConfig()->options;
		$screen  = $this->WP->getScreen();
		$slug    = sprintf( '%s-setting', $this->Plugin->getSlug() );
		$screens = array( sprintf( 'settings_page_%s', $slug ) );
		$types   = array( 'fab' );

        /** Load Core Vendors */
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_style( 'wp-color-picker' );

		/** Load Inline Script */
        $options = (object) ( $this->Helper->ArrayMergeRecursive( (array) $default, (array) $config ) );
		$this->WP->wp_enqueue_script( 'fab-local', 'local/fab.js', array(), '', true );
		$this->WP->wp_localize_script(
			'fab-local',
			'FAB_PLUGIN',
			array(
				'name'           => FAB_NAME,
				'version'        => FAB_VERSION,
				'screen'         => FAB_SCREEN,
				'path'           => json_decode(FAB_PATH),
				'premium'        => $this->Helper->isPremiumPlan(),
				'production'     => $this->Plugin->getConfig()->production,
				'description'    => $this->Plugin->getConfig()->description,
				'options'        => $options,
				'defaultOptions' => array(
                    'layout' => Design::$layout,
                    'template' => Design::$template
                ),
			)
		);

		/** Load Vendors */
		if ( isset( $config->fab_animation->enable ) && $config->fab_animation->enable ) {
			$this->WP->wp_enqueue_style( 'animatecss', 'vendor/animatecss/animate.min.css' );
		}
		if ( in_array( $screen->base, $screens ) || ( isset( $screen->post->post_type ) && in_array( $screen->post->post_type, $types ) ) ) {
			$this->WP->enqueue_assets( $config->fab_assets->backend );
		}

		/** Load Plugin Assets */
		$this->WP->wp_enqueue_style( 'fab', 'build/css/backend.min.css' );
		$this->WP->wp_enqueue_script( 'fab', 'build/js/backend/plugin.min.js', array( 'wp-color-picker' ), '', true );
	}

	/**
	 * Add setting link in plugin page
	 *
	 * @backend
	 * @return  void
	 * @var     array   $links     Plugin links
	 */
	public function plugin_setting_link( $links ) {
		$slug = sprintf( '%s-setting', $this->Plugin->getSlug() );
		return array_merge( $links, array( '<a href="options-general.php?page=' . $slug . '">Settings</a>' ) );
	}

	/**
	 * Plugin row meta references
	 */
	public function plugin_row_meta_references( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if ( strpos( $plugin_file, sprintf( '%s.php', $this->Plugin->getSlug() ) ) !== false ) {
			$new_links = array(
				'community' => '<a href="https://community.artistudio.xyz/" target="_blank">Community</a>',
				'doc'    => '<a href="https://www.youtube.com/watch?v=MMuhc9pcYew&list=PLnwuifVLRkaXBV9IBTPZeLtduzCdt5cFh" target="_blank">Documentation</a>',
				'tutorial' => '<a href="https://www.youtube.com/watch?v=CkSspyM9yjQ&list=PLnwuifVLRkaXH9I-QAAReVoEv9DClViPG" target="_blank">Tutorial</a>',
			);

			$plugin_meta = array_merge( $plugin_meta, $new_links );
		}
		return $plugin_meta;
	}

}
