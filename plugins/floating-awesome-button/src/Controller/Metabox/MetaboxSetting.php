<?php

namespace Fab\Controller;

! defined( 'WPINC ' ) or die;

/**
* Initiate plugins
*
* @package    Fab
* @subpackage Fab/Controller
*/

use Fab\View;
use Fab\Helper\FABItem;
use Fab\Metabox\FABMetaboxSetting;
use Fab\Wordpress\Hook\Action;
use Fab\Wordpress\MetaBox;

class MetaboxSetting extends Base {

	/**
	 * Admin constructor
	 *
	 * @return void
	 * @var    object   $plugin     Plugin configuration
	 * @pattern prototype
	 */
	public function __construct( $plugin ) {
		parent::__construct( $plugin );

		/** @backend - Eneque scripts */
		$action = new Action();
		$action->setComponent( $this );
		$action->setHook( 'admin_enqueue_scripts' );
		$action->setCallback( 'backend_enequeue_metabox_setting' );
		$action->setAcceptedArgs( 1 );
		$action->setMandatory( true );
		$action->setDescription( 'Enqueue backend metabox setting' );
		$action->setFeature( $plugin->getFeatures()['core_backend'] );
		$this->hooks[] = $action;

		/** @backend - Add Settings metabox to Fab CPT */
		$action = new Action();
		$action->setComponent( $this );
		$action->setHook( 'add_meta_boxes' );
		$action->setCallback( 'metabox_settings' );
		$action->setMandatory( false );
		$action->setDescription( 'Add Setting metabox to Fab CPT' );
		$action->setFeature( $plugin->getFeatures()['core_backend'] );
		$action->setPremium( false );
		$this->hooks[] = $action;
	}

	/**
	 * Eneque scripts @backend
	 *
	 * @return  void
	 * @var     array   $hook_suffix     The current admin page
	 */
	public function backend_enequeue_metabox_setting( $hook_suffix ) {
		/** Grab Data */
		global $post;
        $screen = $this->WP->getScreen();
        $allowedPage = ['post.php', 'post-new.php'];
        if ( !isset( $post->post_type ) || $post->post_type !== 'fab' || !in_array($screen->pagenow, $allowedPage) ) {
            return;
        }

        /** Grab Data */
        $fab = new FABItem( $post->ID );
        $fab = $fab->getVars();

        /** Add Inline Script */
        $this->WP->wp_localize_script( 'fab-local', 'FAB_METABOX_SETTING', array(
            'defaultOptions' => [
                'types' => FABMetaboxSetting::$types,
                'triggers' => FABMetaboxSetting::$triggers,
            ],
            'data' => compact('fab')
        ));

		/** Enqueue */
		$this->WP->wp_enqueue_script( 'fab-setting', 'build/js/backend/metabox-setting.min.js', array(), '', true );

        /** Load Component */
        $component = 'metabox-setting';
        $this->WP->wp_enqueue_style( sprintf('%s-component', $component), sprintf('build/components/%s/bundle.css', $component) );
        $this->WP->wp_enqueue_script(sprintf('%s-component', $component), sprintf('build/components/%s/bundle.js', $component), array(), '1.0', true);
	}

	/**
	 * Register metabox settings on custom post type Fab
	 *
	 * @return      void
	 */
	public function metabox_settings() {
		$metabox = new MetaBox();
		$metabox->setScreen( 'fab' );
		$metabox->setId( 'fab-metabox-settings' );
		$metabox->setTitle( 'Setting' );
		$metabox->setCallback( array( $this, 'metabox_settings_callback' ) );
		$metabox->setCallbackArgs( array( 'is_display' => false ) );
		$metabox->build();
	}


	/**
	 * Metabox Setting set view template
	 *
	 * @return      string              Html template string from view View/Template/backend/metabox_settings.php
	 * @param       object $post      global $post object
	 */
	public function metabox_settings_callback() {
		global $post;

		/** Set View */
		$view = new View( $this->Plugin );
		$view->setTemplate( 'backend.blank' );
		$view->setSections(
			array(
				'Backend.Metabox.setting' => array(
					'name'   => '',
					'active' => true,
				),
			)
		);
		$view->setData(
			array(
				'fab' => new FABItem( $post->ID ),
			)
		);
		$view->build();
	}

}
