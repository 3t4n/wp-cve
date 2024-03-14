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

class FABMetaboxLocation extends Metabox {

	/** FAB Metabox Operator */
	public static $operator = array(
		array(
			'id'   => '==',
			'text' => 'is equal to',
		),
		array(
			'id'   => '!=',
			'text' => 'is not equal to',
		),
	);

    /** FAB Metabox Operator Logic */
    public static $logic = array(
        array(
            'id'   => 'OR',
            'text' => 'OR',
        ),
        array(
            'id'   => 'AND',
            'text' => 'AND',
        ),
    );

	/** $_POST input */
	public static $input = array(
		'fab_location_type'     => array(
			'default'      => '',
			'sub_meta_key' => 'type',
		),
		'fab_location_operator' => array(
			'default'      => '',
			'sub_meta_key' => 'operator',
		),
		'fab_location_value'    => array(
			'default'      => '',
			'sub_meta_key' => 'value',
		),
        'fab_location_logic'    => array(
            'default'      => '',
            'sub_meta_key' => 'logic',
        ),
	);

	/** FAB Metabox Post Metas */
	public static $post_metas = array(
		'locations' => array( 'meta_key' => 'fab_location' ),
	);

	/** Constructor */
	public function __construct() {
		$plugin   = \Fab\Plugin::getInstance();
		$this->WP = $plugin->getWP();
	}

	/** Sanitize */
	public function sanitize() {
		/** $_POST Data for metabox location */
		$input = self::$input;

		/** Validate Data Type */
		if ( ! is_array( $_POST ) ) {
			return;
		} else {
			$params = $_POST;
		}

		/** Validate sub Data Type */
		foreach ( $input as $key => $meta ) {
			if ( ! isset( $params[ $key ] ) || ! is_array( $params[ $key ] ) ) {
				return;
			}
		}

		/** Sanitize Params */
		$rules_count = 0;
		foreach ( $input as $key => &$meta ) {
			$meta = array();
			foreach ( $params[ $key ] as $value ) {
				$meta[] = sanitize_text_field( $value );
				$rules_count++;
			}
		}

		/** Save as Params */
		$this->params = $input;
	}

	/** transformData */
	public function setDefaultInput() {
		/** Transform Locations */
		$locations = array();
		foreach ( self::$input as $key => $meta ) {
			foreach ( $this->params[ $key ] as $index => $value ) {
				$locations[ $index ][ $meta['sub_meta_key'] ] = $value;
			}
		}

		/** Merge duplicate rules */
		$duplicate = array();
		foreach ( $locations as $location ) {
			$duplicate[ implode( '', $location ) ] = $location;
		}
		$locations = array_values( $duplicate );

		/** Prepare parmaeters */
		$this->params = array();
		$this->params[ self::$post_metas['locations']['meta_key'] ] = json_encode( $locations );
	}

	/** Save data to database */
	public function save() {
		global $post;
		foreach ( $this->params as $key => $value ) {
			if ( $value ) {
				$this->WP->update_post_meta( $post->ID, $key, $value );
			}
		}
	}

}
