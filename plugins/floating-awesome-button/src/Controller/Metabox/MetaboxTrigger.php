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
use Fab\Metabox\FABMetaboxTrigger;
use Fab\Wordpress\Hook\Action;
use Fab\Wordpress\MetaBox;

class MetaboxTrigger extends Base {

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
		$action->setCallback( 'backend_enequeue_metabox_trigger' );
		$action->setAcceptedArgs( 1 );
		$action->setMandatory( true );
		$action->setDescription( 'Enqueue backend metabox trigger' );
		$action->setFeature( $plugin->getFeatures()['core_backend'] );
		$this->hooks[] = $action;

		/** @backend - Add triggers metabox to Fab CPT */
		$action = new Action();
		$action->setComponent( $this );
		$action->setHook( 'add_meta_boxes' );
		$action->setCallback( 'metabox_triggers' );
		$action->setMandatory( false );
		$action->setDescription( 'Add trigger metabox to Fab CPT' );
		$action->setFeature( $plugin->getFeatures()['core_backend'] );
		$action->setPremium( true );
		$this->hooks[] = $action;
	}

	/**
	 * Eneque scripts @backend
	 *
	 * @return  void
	 * @var     array   $hook_suffix     The current admin page
	 */
	public function backend_enequeue_metabox_trigger( $hook_suffix ) {
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
		$this->WP->wp_localize_script(
			'fab-local',
			'FAB_METABOX_TRIGGER',
			array(
				'defaultOptions' => array(
					'types' => FABMetaboxTrigger::$types,
				),
                'data' => compact('fab')
			)
		);

		/** Enqueue */
		$this->WP->wp_enqueue_script( 'fab-trigger', 'build/js/backend/metabox-trigger.min.js', array(), '', true );

        /** Load Component */
        $component = 'metabox-trigger';
        $this->WP->wp_enqueue_style( sprintf('%s-component', $component), sprintf('build/components/%s/bundle.css', $component) );
        $this->WP->wp_enqueue_script(sprintf('%s-component', $component), sprintf('build/components/%s/bundle.js', $component), array(), '1.0', true);
	}

	/**
	 * Register metabox triggers on custom post type Fab
	 *
	 * @return      void
	 */
	public function metabox_triggers() {
		$metabox = new MetaBox();
		$metabox->setScreen( 'fab' );
		$metabox->setId( 'fab-metabox-triggers' );
		$metabox->setTitle( 'Trigger' );
		$metabox->setCallback( array( $this, 'metabox_triggers_callback' ) );
		$metabox->setCallbackArgs( array( 'is_display' => false ) );
		$metabox->build();
	}


	/**
	 * Metabox trigger set view template
	 *
	 * @return      string              Html template string from view View/Template/backend/metabox_triggers.php
	 * @param       object $post      global $post object
	 */
	public function metabox_triggers_callback() {
        View::RenderStatic('Backend.Metabox.trigger');
	}

}
