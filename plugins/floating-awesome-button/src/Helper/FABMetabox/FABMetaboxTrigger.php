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

class FABMetaboxTrigger extends Metabox {

	/** FAB Metabox Settings */
	public static $types = array(
		array(
			'id'   => 'none',
			'text' => 'None',
		),
        array(
            'id'   => 'adblock',
            'text' => 'Adblock',
        ),
		array(
			'id'   => 'exit_intent',
			'text' => 'Exit Intent',
		),
		array(
			'id'   => 'time_delay',
			'text' => 'Time Delay',
		),
	);

	/** $_POST input */
	public static $input = array(
		'fab_trigger' => array(
			'default' => array(
				'delay'  => '1000ms',
				'cookie' => array(
					'expiration' => 30,
				),
			),
		),
	);

	/** FAB Metabox Post Metas */
	public static $post_metas = array(
		'trigger' => array( 'meta_key' => 'fab_trigger' ),
	);

	/** Constructor */
	public function __construct() {
		$plugin   = \Fab\Plugin::getInstance();
		$this->WP = $plugin->getWP();
	}

	/** Sanitize */
	public function sanitize() {
		$input  = self::$input;
		$params = $_POST;

		/** Validate sub Data Type */
		foreach ( $input as $key => $meta ) {
			if ( ! isset( $params[ $key ] ) || ! is_array( $params[ $key ] ) ) {
				return;
			}
		}

		/** Sanitize Params */
		foreach ( $input as $key => &$meta ) {
			$meta = array();
			foreach ( $params[ $key ] as $key => $value ) {
				$meta[ $key ] = $value;
			}
		}

		$this->params = $input;
	}

	/** SetDefaultInput */
	public function setDefaultInput() {
		/** Default Input Function */
		$input = self::$input;
		foreach ( $input as $key => $value ) {
            $this->params[ $key ] = isset( $this->params[ $key ] ) ? $this->params[ $key ] : $value['default'];
            if(is_array($this->params[$key])){
                $this->params[ $key ] += $value['default'];
            }
		}
	}

	/** Save data to database */
	public function save() {
		global $post;
		foreach ( $this->params as $key => $value ) {
			$this->WP->update_post_meta( $post->ID, $key, $value );
		}
	}

}
