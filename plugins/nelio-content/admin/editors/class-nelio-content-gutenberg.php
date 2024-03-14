<?php
/**
 * This file adds a few hooks to work with Gutenberg.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/editors
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that registers specific hooks to work with the Gutenberg.
 */
class Nelio_Content_Gutenberg {

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_custom_metas' ) );
	}//end init()

	public function register_custom_metas() {

		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types', array() );
		if ( empty( $post_types ) ) {
			return;
		}//end if

		register_rest_field(
			$post_types,
			'nelio_content',
			array(
				'get_callback'    => array( $this, 'get_values' ),
				'update_callback' => array( $this, 'save' ),
			)
		);

	}//end register_custom_metas()

	public function get_values( $object ) {
		return $this->load_values( $object['id'] );
	}//end get_values()

	public function save( $values, $post ) {
		$values = $this->parse_values( $values, $post->ID );

		$efi_helper = Nelio_Content_External_Featured_Image_Helper::instance();
		$efi_helper->set_nelio_featured_image( $post->ID, $values['efiUrl'], $values['efiAlt'] );

		$post_helper = Nelio_Content_Post_Helper::instance();
		$post_helper->save_post_followers( $post->ID, $values['followers'] );
		$post_helper->update_post_references( $post->ID, $values['suggestedReferences'], array() );
		$post_helper->enable_auto_share( $post->ID, $values['isAutoShareEnabled'] );
		$post_helper->update_auto_share_end_mode( $post->ID, $values['autoShareEndMode'] );
		$post_helper->update_automation_sources( $post->ID, $values['automationSources'] );
		$post_helper->update_post_highlights( $post->ID, $values['highlights'] );
	}//end save()

	private function load_values( $post_id ) {
		$post_helper = Nelio_Content_Post_Helper::instance();

		$suggested = array_map(
			function ( $reference ) {
				return $reference['url'];
			},
			$post_helper->get_references( $post_id, 'suggested' )
		);

		$efi_url = get_post_meta( $post_id, '_nelioefi_url', true );
		$efi_url = ! empty( $efi_url ) ? $efi_url : '';

		$efi_alt = get_post_meta( $post_id, '_nelioefi_alt', true );
		$efi_alt = ! empty( $efi_alt ) ? $efi_alt : '';

		return array(
			'isAutoShareEnabled'  => $post_helper->is_auto_share_enabled( $post_id ),
			'autoShareEndMode'    => $post_helper->get_auto_share_end_mode( $post_id ),
			'automationSources'   => $post_helper->get_automation_sources( $post_id ),
			'followers'           => $post_helper->get_post_followers( $post_id ),
			'suggestedReferences' => $suggested,
			'efiUrl'              => $efi_url,
			'efiAlt'              => $efi_alt,
			'highlights'          => $post_helper->get_post_highlights( $post_id ),
		);
	}//end load_values()

	private function parse_values( $values, $post_id ) {
		if ( ! is_array( $values ) ) {
			$values = array();
		}//end if

		$defaults = $this->load_values( $post_id );
		return wp_parse_args( $values, $defaults );
	}//end parse_values()
}//end class
