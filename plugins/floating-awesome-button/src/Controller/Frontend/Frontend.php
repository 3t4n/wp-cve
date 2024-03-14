<?php

namespace Fab\Controller;

! defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 *
 * @package    Fab
 * @subpackage Fab/Controller
 */

use Fab\View;
use Fab\Wordpress\Hook\Action;
use Fab\Wordpress\Hook\Filter;
use Fab\Metabox\FABMetaboxSetting;

class Frontend extends Base {

	/**
	 * Frontend constructor
	 *
	 * @return void
	 * @var    object   $plugin     Plugin configuration
	 * @pattern prototype
	 */
	public function __construct( $plugin ) {
		parent::__construct( $plugin );

		/** @frontend */
		$action = new Action();
		$action->setComponent( $this );
		$action->setHook( 'widgets_init' );
		$action->setCallback( 'fab_register_widget' );
		$action->setMandatory( true );
		$action->setDescription( 'Add Table Of Content Widget' );
		$action->setFeature( $plugin->getFeatures()['core_frontend'] );
		$this->hooks[] = $action;

		/** @frontend */
		$action = clone $action;
		$action->setHook( 'wp_enqueue_scripts' );
		$action->setCallback( 'frontend_enequeue' );
		$action->setAcceptedArgs( 1 );
		$action->setPriority( 20 );
		$action->setDescription( 'Eneque scripts' );
		$action->setFeature( $plugin->getFeatures()['core_frontend'] );
		$this->hooks[] = $action;

		/** @frontend */
		$action = clone $action;
		$action->setHook( 'wp_footer' );
		$action->setCallback( 'fab_loader' );
		$action->setDescription( 'Display the html element from view Frontend/float_button.php' );
		$action->setPriority( 10 );
		$action->setFeature( $plugin->getFeatures()['core_frontend'] );
		$this->hooks[] = $action;

		/** @frontend */
		$filter = new Filter();
		$filter->setComponent( $this );
		$filter->setHook( 'wp_kses_allowed_html' );
		$filter->setCallback( 'filter_wpkses_posts' );
		$filter->setMandatory( false );
		$filter->setAcceptedArgs( 2 );
		$filter->setDescription( 'Filter wpkses post' );
		$filter->setFeature( $plugin->getFeatures()['core_frontend'] );
		$this->hooks[] = $filter;
	}

	/**
	 * Eneque scripts to @frontend
	 *
	 * @return  void
	 * @var     array   $hook_suffix     The current admin page
	 */
	public function frontend_enequeue( $hook_suffix ) {
        /** Default Variables */
		define( 'FAB_SCREEN', json_encode( $this->WP->getScreen() ) );
		$default = $this->Plugin->getConfig()->default;
		$config  = $this->Plugin->getConfig()->options;
        $options = (object) ( $this->Helper->ArrayMergeRecursive( (array) $default, (array) $config ) );
        $fabTypes = array();

        /** Get FAB for JS Manipulation */
        $fab_to_display = $this->Plugin->getModels()['Fab'];
        $fab_to_display = $fab_to_display->get_lists_of_fab( array(
            'validateLocation' => true
        ) )['items'];
        foreach($fab_to_display as &$fab){
            $fabTypes[$fab->getType()] = $fab->getType();
            if($fab->getModal()) $fabTypes['modal'] = 'modal';
            $fab = $fab->getVars();
        }

        /** Get Features for JS Manipulation */
        $features = $this->Plugin->getFeatures();
        foreach($features as $key => &$feature){
            $feature = $feature->getOptions();
            if(!$feature) unset($features[$key]);
        }

		/** Load Inline Script */
		$this->WP->wp_enqueue_script( 'fab-local', 'local/fab.js', array(), '', true );
		$this->WP->wp_localize_script(
			'fab-local',
			'FAB_PLUGIN',
			array(
				'name'    => FAB_NAME,
				'version' => FAB_VERSION,
				'screen'  => FAB_SCREEN,
				'path'    => FAB_PATH,
				'premium' => $this->Helper->isPremiumPlan(),
                'rest_url'=> esc_url_raw( rest_url() ),
				'options' => $options,
                'to_display' => $fab_to_display,
                'features' => $features
			)
		);

		/** Load WP Core jQuery */
		wp_enqueue_script( 'jquery' );

		/** Load Vendors */
		if ( isset( $config->fab_animation->enable ) && $config->fab_animation->enable ) {
			$this->WP->wp_enqueue_style( 'animatecss', 'vendor/animatecss/animate.min.css' );
		}
		$this->WP->enqueue_assets( $config->fab_assets->frontend );

		/** Load Plugin Assets */
		$this->WP->wp_enqueue_style( 'fab', 'build/css/frontend.min.css' );
		$this->WP->wp_enqueue_script( 'fab', 'build/js/frontend/plugin.min.js', array(), '', true );

        /** Load Components */
        foreach($fab_to_display as $component){
            $this->WP->wp_enqueue_style( sprintf('fab-%s-component', $component['type']), sprintf('build/components/%s/bundle.css', $component['type']) );
            $this->WP->wp_enqueue_script(sprintf('fab-%s-component', $component['type']), sprintf('build/components/%s/bundle.js', $component['type']), array(), '1.0', true);
        }

        /** Load Special Plugin Components */
        $components = ['fab', 'readingbar'];
        foreach($components as $component){
            $this->WP->wp_enqueue_style( sprintf('fab-%s-component', $component), sprintf('build/components/%s/bundle.css', $component) );
            $this->WP->wp_enqueue_script(sprintf('fab-%s-component', $component), sprintf('build/components/%s/bundle.js', $component), array(), '1.0', true);
        }

        /** Special Template/Styles */
        if($options->fab_design->template->name==='shape'){ $this->WP->wp_enqueue_style( 'fab-shapes', sprintf('build/css/fab-shapes.min.css', $component) ); }
        if(isset($fabTypes['modal'])){ $this->WP->wp_enqueue_style( 'fab-modal', sprintf('build/css/fab-modal.min.css', $component) ); }
	}

	/**
	 * Display the html element from view Frontend/float_button.php
	 *
	 * @return  void
	 */
	public function fab_loader() {
		global $post;

		/** Ignore in Pages */
		if ( is_singular() && isset( $post->post_type ) && $post->post_type === 'fab' ) {
			return;
		}

		/** Grab Data */
        $Fab = $this->Plugin->getModels()['Fab'];
        $args = array(
            'validateLocation' => true,
            'filtercustommodule' => true,
        );
        $lists = $Fab->get_lists_of_fab( $args );
		$fab_to_display = $lists['items'];

        /** Show FAB Button */
        View::RenderStatic('Frontend.button');

        /** Show Modal - Only Default */
        if ( ! is_admin() && ( $fab_to_display ) ) {
			$args['builder'] = array( 'default' );
			$fab_to_display  = $Fab->get_lists_of_fab( $args )['items'];
            View::RenderStatic('Frontend.modal',
                compact( 'post', 'fab_to_display' )
            );
		}
	}

	/** Register widgets */
	public function fab_register_widget() {
		/** Grab Widgets Type */
		$types       = FABMetaboxSetting::$types;
		$widgetsType = array();
		foreach ( $types as $type ) {
			if ( $type['text'] === 'Widget' ) {
				foreach ( $type['children'] as $child ) {
					$widgetsType[] = $child['id'];
				}
			}
		}

		/** Grab FAB with widget type */
		$Fab     = $this->Plugin->getModels()['Fab'];
		$widgets = $Fab->get_lists_of_fab(
			array(
				'filterbyType' => $widgetsType,
			)
		)['items'];

		/** Register Sidebar */
		foreach ( $widgets as $widget ) {
			register_sidebar(
				array(
					'name'          => __( $widget->getTitle(), sprintf( 'fab-widget-%s',  $widget->getSlug() ) ),
					'id'            => sprintf( 'fab-widget-%s',  $widget->getSlug() ),
					'before_widget' => '<div id="%1$s" class="widget fab-container %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widgettitle">',
					'after_title'   => '</h3>',
				)
			);
		}
	}

	/**
	 * Filter wpkses posts accept iframe, used to embed iframe (youtube, vimeo, etc) with FAB Content
	 */
	public function filter_wpkses_posts( $tags, $context ) {
		$tags['iframe'] = array(
			'src'             => true,
			'height'          => true,
			'width'           => true,
			'frameborder'     => true,
			'allowfullscreen' => true,
		);
		return $tags;
	}

}
