<?php

namespace Fab\Metabox;

! defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 *
 * @package    Fab
 * @subpackage Fab/Controller
 */

use Fab\Wordpress\Model\Metabox;

class FABMetaboxDesign extends Metabox {

	/** $_POST input */
	public static $input = array(
        /**
         * General
         */
        'fab_design_animation'  => array(
            'default' => array(
                'modal' => array(
                    'in' => 'fadeIn',
                    'out' => 'fadeOut',
                ),
            ),
        ),

        /**
         * Button
         */
        'fab_design_icon_class'  => array( 'default' => 'fas fa-circle' ), // Icon.
		'fab_design_responsive'  => array( // Responsive.
			'default' => array(
				'device' => array(
					'mobile'  => 'true',
					'tablet'  => 'true',
					'desktop' => 'true',
				),
			),
		),
        'fab_design_standalone'   => array( 'default' => false ), // Standalone.
        'fab_design_size_type'   => array( 'default' => '' ), // Size.
        'fab_design_size_custom' => array( 'default' => '' ), // Size.
        'fab_setting_hotkey'        => array( 'default' => '' ), // Hotkey.
        'fab_design_template'  => array( // Template.
            'default' => array(
                'color' => '',
                'icon' => array(
                    'color' => '',
                ),
                'shape'  => 'none',
                'grouped'  => true,
            ),
        ),
        'fab_design_tooltip'  => array( // Tooltip.
            'default' => array(
                'alwaysdisplay' => false,
                'font' => array(
                    'color' => '',
                ),
            ),
        ),

        /**
         * Modal
         */
        /** Navigation */
        'fab_modal_navigation'  => array(
            'default' => array(
                'backgroundDismiss' => 'true',
                'buttons' => array(
                    'maximize' => 'true'
                ),
                'draggable' => 'true',
                'escapeKey' => 'true',
            ),
        ),
        /** Layout */
        'fab_modal_layout'  => array(
            'default' => array(
                'id' => 'stacked',
                'background' => array(
                    'color' => ''
                ),
                'content' => array(
                    'margin' => array(
                        'top' => '0',
                        'right' => '0',
                        'bottom' => '0',
                        'left' => '0',
                        'sizing' => 'rem',
                    ),
                    'padding' => array(
                        'top' => '1',
                        'right' => '1',
                        'bottom' => '1',
                        'left' => '1',
                        'sizing' => 'rem',
                    ),
                ),
                'overlay' => array(
                    'color' => '',
                    'opacity' => '0.5',
                ),
            ),
        ),
        /** Theme */
        'fab_modal_theme'  => array(
            'default' => array(
                'id' => 'blank',
                'option' => array(),
            ),
        ),
	);

	/** FAB Metabox Post Metas */
	public static $post_metas = array(
        /** General */
        /** Animation */
        'animation'  => array( 'meta_key' => 'fab_design_animation' ), // Animation.

        /*** Button */
		'icon_class'  => array( 'meta_key' => 'fab_design_icon_class' ), // Icon.
		'responsive'  => array( 'meta_key' => 'fab_design_responsive' ), // Responsive.
		'size_type'   => array( 'meta_key' => 'fab_design_size_type' ), // Size.
		'size_custom' => array( 'meta_key' => 'fab_design_size_custom' ), // Size.
		'standalone'  => array( 'meta_key' => 'fab_design_standalone' ), // Standalone.
		'template' => array( 'meta_key' => 'fab_design_template' ), // Template.
		'tooltip' => array( 'meta_key' => 'fab_design_tooltip' ), // Tooltip.
        'hotkey' => array( 'meta_key' => 'fab_setting_hotkey' ), // Hotkey.

        /** Modal */
        'modal_navigation'  => array( 'meta_key' => 'fab_modal_navigation' ), // Navigation.
        'modal_layout'  => array( 'meta_key' => 'fab_modal_layout' ), // Layout.
        'modal_theme'  => array( 'meta_key' => 'fab_modal_theme' ), // Theme.
	);

	/** Constructor */
	public function __construct() {
		$plugin   = \Fab\Plugin::getInstance();
		$this->WP = $plugin->getWP();
	}

	/** Sanitize */
	public function sanitize() {
		$input = self::$input;

		/** Sanitized input */
		$params = array();
		foreach ( $_POST as $key => $value ) {
			if ( isset( $input[ $key ] ) && $value ) {
				$params[ $key ] = $value;
			}
		}

		$this->params = $params;
	}

	/** SetDefaultInput */
	public function setDefaultInput() {
		/** Default Input Function */
		$input = self::$input;
		foreach ( $input as $key => $value ) {
			$this->params[ $key ] = isset( $this->params[ $key ] ) ? $this->params[ $key ] : $value['default'];
			if ( is_array( $this->params[ $key ] ) ) {
				$this->params[ $key ] += $value['default'];
			}
		}

		/** Transform Data */
        $plugin   = \Fab\Plugin::getInstance();
        $this->params['fab_modal_navigation'] = $plugin->getHelper()->transformBooleanValue( $this->params['fab_modal_navigation'] );
		$this->params['fab_design_responsive'] = $plugin->getHelper()->transformBooleanValue( $this->params['fab_design_responsive'] );
		$this->params['fab_design_tooltip'] = $plugin->getHelper()->transformBooleanValue( $this->params['fab_design_tooltip'] );
	}

	/** Save data to database */
	public function save() {
		global $post;
		foreach ( $this->params as $key => $value ) {
			$this->WP->update_post_meta( $post->ID, $key, $value );
		}
	}

}
