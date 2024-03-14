<?php

namespace QuadLayers\WOOCCM;

/**
 * Helpers Class
 */
class Helpers {

	public static function get_autocomplete_options() {
		$options = array(
			'off',
			'on',
			'name',
			'honorific-prefix',
			'given-name',
			'additional-name',
			'family-name',
			'honorific-suffix',
			'nickname',
			'email',
			'username',
			'new-password',
			'current-password',
			'one-time-code',
			'organization-title',
			'organization',
			'street-address',
			'address-line1',
			'address-line2',
			'address-line3',
			'address-level4',
			'address-level3',
			'address-level2',
			'address-level1',
			'country',
			'country-name',
			'postal-code',
			'cc-name',
			'cc-given-name',
			'cc-additional-name',
			'cc-family-name',
			'cc-exp',
			'cc-exp-month',
			'cc-exp-year',
			'cc-csc',
			'cc-type',
			'transaction-currency',
			'transaction-amount',
			'language',
			'bday',
			'bday-day',
			'bday-month',
			'bday-year',
			'sex',
			'tel',
			'tel-country-code',
			'tel-national',
			'tel-area-code',
			'tel-extension',
			'impp',
			'url',
			'photo',
			'webauthn',
		);
		return $options;
	}

	public static function get_form_action() {

		if ( isset( $_REQUEST['action'] ) && 'edit_address' === $_REQUEST['action'] ) {
			return 'account';
		}

		if ( isset( $_REQUEST['woocommerce-process-checkout-nonce'] ) ) {
			return 'save';
		}

		if ( isset( $_REQUEST['post_data'] ) && isset( $_REQUEST['wc-ajax'] ) && 'update_order_review' == $_REQUEST['wc-ajax'] ) {
			return 'update';
		}

		if ( isset( $_REQUEST['wc-ajax'] ) && 'ppc-create-order' == $_REQUEST['wc-ajax'] ) {
			return 'paypal-payments';
		}
	}

	public static function get_file_extension_icon( string $file_extension = null ) {

		$icons = array(
			'default'     => site_url( 'wp-includes/images/media/default.png' ),
			'interactive' => site_url( 'wp-includes/images/media/interactive.png' ),
			'spreadsheet' => site_url( 'wp-includes/images/media/spreadsheet.png' ),
			'archive'     => site_url( 'wp-includes/images/media/archive.png' ),
			'audio'       => site_url( 'wp-includes/images/media/audio.png' ),
			'text'        => site_url( 'wp-includes/images/media/text.png' ),
			'video'       => site_url( 'wp-includes/images/media/video.png' ),
		);

		if ( ! $file_extension ) {
			return $icons;
		}

		/*
		if (filetype.match('image.*')) {
		source_class = 'image';
		} else if (filetype.match('application/ms.*')) {
		source = wooccm_upload.icons.spreadsheet;
		source_class = 'spreadsheet';
		} else if (filetype.match('application/x.*')) {
		source = wooccm_upload.icons.archive;
		source_class = 'application';
		} else if (filetype.match('audio.*')) {
		source = wooccm_upload.icons.audio;
		source_class = 'audio';
		} else if (filetype.match('text.*')) {
		source = wooccm_upload.icons.text;
		source_class = 'text';
		} else if (filetype.match('video.*')) {
		source = wooccm_upload.icons.video;
		source_class = 'video';
		} else {
		if ((false === filetype.match('application/ms.*') && false === filetype.match('application/x.*') && false === filetype.match('audio.*') && false === filetype.match('text.*') && false === filetype.match('video.*')) || (0 === filetype.length || !filetype)) {
		source = wooccm_upload.icons.interactive;
		source_class = 'interactive';
		}
		*/
	}

	public static function get_attachment_icon( $attachment_id ) {

		$attachment = get_post( $attachment_id );

		if ( ! $attachment ) {
			return false;
		}

		$mime_type = get_post_mime_type( $attachment_id );

		if ( ! $mime_type ) {
			return false;
		}

		$mime_type = explode( '/', $mime_type );

		if ( ! isset( $mime_type[0] ) || ! isset( $mime_type[1] ) ) {
			return false;
		}

		$mime_type = $mime_type[0];

		$icons = self::get_file_extension_icon();

		if ( ! isset( $icons[ $mime_type ] ) ) {
			return $icons['default'];
		}

		return $icons[ $mime_type ];
	}

	public static function get_attachment_thumbail_src( $attachment_id ) {

		$src = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );

		if ( isset( $src[0] ) ) {
			return $src[0];
		}

		return self::get_attachment_icon( $attachment_id );

	}

}
