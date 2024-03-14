<?php
/**
 * This file contains the class for using External Featured Images in the
 * front-end.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/public
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements all the hooks for inserting External Featured Images
 * in the front-end.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/public
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */
class Nelio_Content_External_Featured_Image_Public {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.0.1
	 * @access protected
	 * @var    self
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_External_Featured_Image_Public the single instance of this class.
	 *
	 * @since  2.0.1
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	/**
	 * The ID of the Placeholder attachment.
	 *
	 * @since  1.1.1
	 * @access private
	 * @var    integer
	 */
	private $placeholder_id = -1;

	/**
	 * Whether we're currently outputting images in the HTML `head` or not.
	 *
	 * @since  1.1.1
	 * @access private
	 * @var    boolean
	 */
	private $in_wp_head = true;

	/**
	 * Initializes the class.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function init() {

		if ( ! nc_get_site_id() ) {
			return;
		}//end if

		add_action( 'plugins_loaded', array( $this, 'add_hooks' ) );
	}//end init()

	/**
	 * Registers the requried hooks for inserting featured images in the
	 * front-end, if the feature is enabled.
	 *
	 * @since  2.0.1
	 * @access public
	 */
	public function add_hooks() {

		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'use_external_featured_image' ) ) {
			return;
		}//end if

		// Disable old hooks.
		add_action( 'init', array( $this, 'disable_original_nelioefi_hooks' ), 1 );

		// Featured Image Hooks.
		add_filter( 'get_post_metadata', array( $this, 'maybe_simulate_post_thumbnail_attachment' ), 10, 3 );
		add_filter( 'wp_get_attachment_image_src', array( $this, 'maybe_return_efi_url' ), 10, 3 );
		add_filter( 'post_thumbnail_html', array( $this, 'maybe_add_efi_as_background' ), 10, 5 );

		// Fix og:image meta tag.
		add_action( 'wp_head', array( $this, 'leave_wp_head_section' ), 99999 );

	}//end add_hooks()

	/**
	 * This function deactivates all public hooks created by the original Nelio
	 * External Featured Image plugin.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function disable_original_nelioefi_hooks() {

		remove_action( 'init', 'nelioefi_add_hooks_for_faking_featured_image_if_necessary' );
		remove_filter( 'post_thumbnail_html', 'nelioefi_replace_thumbnail', 10, 5 );
		remove_filter( 'genesis_pre_get_image', 'nelioefi_genesis_thumbnail', 10, 3 );

	}//end disable_original_nelioefi_hooks()

	/**
	 * Posts with an external featured image don't have a thumbnail. This
	 * function simulates they do by returning an attachment ID different than
	 * zero.
	 *
	 * @param boolean $null      Default return value.
	 * @param integer $object_id The post whose featured image we're interested in.
	 * @param string  $meta_key  The meta property we're trying to retrieve.
	 *
	 * @return integer This function might return different values:
	 *                 * If the meta property isn't `_thumbnail_id`, we return `$null`.
	 *                 * If it is, we simulate it has a thumbnail as required.
	 *                   That is, if a external featured image has been manually
	 *                   set by the user, we return the negative `$object_id`, so
	 *                   that other functions "know" there's a thumbnail (it's
	 *                   different than zero), but that said thumbnail is Nelio's
	 *                   (because it's negative).
	 *                 * If there's an actual thumbnail, its attachment ID is
	 *                   returned.
	 *                 * If none of those is set, we may need to return the "auto-set"
	 *                   featured image (the one selected automatically from the images
	 *                   included in the post).
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function maybe_simulate_post_thumbnail_attachment( $null, $object_id, $meta_key ) {

		// If we're accessing a meta other than the thumbnail, leave.
		if ( '_thumbnail_id' !== $meta_key ) {
			return $null;
		}//end if

		// Let's retrieve the external featured image URL (if any).
		$aux     = Nelio_Content_External_Featured_Image_Helper::instance();
		$efi_url = $aux->get_external_featured_image( $object_id );

		// If the user didn't set an external featured image...
		if ( ! is_string( $efi_url ) || ! strlen( $efi_url ) ) {

			// Let's check if there's a regular featured image.
			remove_filter( 'get_post_metadata', array( $this, 'maybe_simulate_post_thumbnail_attachment' ), 10, 3 );
			$featured_image = get_post_meta( $object_id, '_thumbnail_id', true );
			add_filter( 'get_post_metadata', array( $this, 'maybe_simulate_post_thumbnail_attachment' ), 10, 3 );

			// If that's the case, there's no need to "simulate an attachment".
			if ( $featured_image ) {
				return $null;
			}//end if

			// ...but if it isn't, we might need to use an auto image.
			$settings        = Nelio_Content_Settings::instance();
			$auto_feat_image = $settings->get( 'auto_feat_image' );
			if ( 'disabled' === $auto_feat_image ) {
				return $null;
			}//end if

			$efi_url = $aux->get_auto_featured_image( $object_id );

			// Again, if we didn't find anything, we simply leave.
			if ( ! is_string( $efi_url ) || ! strlen( $efi_url ) ) {
				return $null;
			}//end if
		}//end if

		// If we couldn't create the placeholder, things are not working.
		$result = $this->get_placeholder_id();
		if ( ! $result ) {
			return $null;
		}//end if

		$trace = debug_backtrace(); // phpcs:ignore
		foreach ( $trace as $log ) {
			if ( 'get_the_post_thumbnail' === $log['function'] ) {
				return $result;
			}//end if
		}//end foreach

		return -$object_id;

	}//end maybe_simulate_post_thumbnail_attachment()

	/**
	 * This function replaces the image URL with the actual (external) URL when
	 * there's a "fake" attachment.
	 *
	 * Fake attachment are created by the function
	 * `maybe_simulate_post_thumbnail_attachment`, and they're basically the ID of
	 * the post that has an external featured image, but with a negative sign.
	 *
	 * @param array        $image     the image to return.
	 * @param integer      $attach_id the ID of the attachment.
	 * @param string|array $size      the size of the thumbnail.
	 *
	 * @return array the image to return.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function maybe_return_efi_url( $image, $attach_id, $size ) {

		if ( ! is_numeric( $attach_id ) ) {
			return $image;
		}//end if

		if ( $attach_id < 0 ) {

			$image   = wp_get_attachment_image_src( $this->get_placeholder_id(), $size );
			$post_id = -$attach_id;

			$aux                  = Nelio_Content_External_Featured_Image_Helper::instance();
			$nelio_featured_image = $aux->get_nelio_featured_image( $post_id );

			if ( ! $nelio_featured_image ) {
				return $image;
			}//end if

			$trace = debug_backtrace(); // phpcs:ignore
			foreach ( $trace as $log ) {
				if ( 'wp_get_attachment_image' === $log['function'] ) {
					$image[0] = $nelio_featured_image;
					return $image;
				}//end if
			}//end foreach

			$settings = Nelio_Content_Settings::instance();
			switch ( $settings->get( 'efi_mode' ) ) {

				case 'single-quotes':
					$image[0] = preg_replace( '/.$/', '', $image[0] . '\' ' . $this->generate_style_tag( $nelio_featured_image, 'single-quotes' ) );
					break;

				case 'double-quotes':
					$image[0] = preg_replace( '/.$/', '', $image[0] . '" ' . $this->generate_style_tag( $nelio_featured_image, 'double-quotes' ) );
					break;

				default:
					$image[0] = $nelio_featured_image;

			}//end switch

			if ( $this->in_wp_head ) {
				$image[0] = $nelio_featured_image;
			}//end if
		}//end if

		return $image;

	}//end maybe_return_efi_url()

	/**
	 * If the post is supposed to use an external featured image, this function
	 * modifies the resulting HTML so that the feature image is set as a
	 * background property.
	 *
	 * @param string       $html              the HTML image tag.
	 * @param integer      $post_id           the post whose featured image is to be printed.
	 * @param string       $post_thumbnail_id the post thumbnail ID.
	 * @param array|string $size              the size of the featured image.
	 * @param array        $attr              additional attributes.
	 *
	 * @return string the HTML image tag, with a CSS background property set
	 *                (when required).
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function maybe_add_efi_as_background( $html, $post_id = 0, $post_thumbnail_id = '', $size = false, $attr = array() ) {

		$aux                  = Nelio_Content_External_Featured_Image_Helper::instance();
		$nelio_featured_image = $aux->get_nelio_featured_image( $post_id );
		if ( ! is_string( $nelio_featured_image ) || ! strlen( $nelio_featured_image ) ) {
			return $html;
		}//end if

		// Add featured image as background in style tag.
		$style = $this->generate_style_tag( $nelio_featured_image );
		$html  = str_replace( 'src=', $style . ' src=', $html );

		// Fix the alt tag (if possible).
		$alt = $aux->get_external_featured_alt( $post_id );
		if ( isset( $attr['alt'] ) ) {
			$alt = $attr['alt'];
		}//end if

		if ( $alt ) {
			$html = str_replace( '/(alt=\'[^\']+\'\|alt="[^"]+")/', '', $html );
			$html = str_replace( 'src=', ' alt="' . esc_attr( $alt ) . '" src=', $html );
		}//end if

		return $html;

	}//end maybe_add_efi_as_background()

	/**
	 * This helper function generates a style tag with a cover background
	 * property.
	 *
	 * The background property is used for "inserting" an external featured
	 * image.
	 *
	 * @param string $image_url The URL of the external featured image.
	 * @param string $quote     Optional. The character for opening/closing the
	 *                          style tag. It can either be `single-quotes` or
	 *                          `double-quotes`. Default: `double-quotes`.
	 *
	 * @return string the style tag.
	 *
	 * @since  1.1.1
	 * @access private
	 */
	private function generate_style_tag( $image_url, $quote = 'double-quotes' ) {

		if ( 'single-quotes' === $quote ) {
			$quote = '\'';
		} else {
			$quote = '"';
		}//end if

		return "style={$quote}background:url( {$image_url} ) no-repeat center center;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size: cover;{$quote}";

	}//end generate_style_tag()

	/**
	 * Returns the ID of the attachment that corresponds to our placeholder.
	 *
	 * This placeholder is a transparent png that guarantees that our external
	 * images "scale" properly in all themes.
	 *
	 * @since  1.1.1
	 * @access private
	 */
	private function get_placeholder_id() {

		// If I already loaded the placeholder ID, return it.
		if ( -1 < $this->placeholder_id ) {
			return $this->placeholder_id;
		}//end if

		// Let's see if the placeholder attachment exists and is available...
		$attach_id = get_option( 'nc_efi_placeholder_id', false );
		if ( $attach_id ) {
			$aux = get_post( $attach_id );
			if ( empty( $aux ) ) {
				$attach_id = false;
			}//end if
		}//end if

		if ( $attach_id ) {
			$this->placeholder_id = $attach_id;
			return $this->placeholder_id;
		}//end if

		// If the placeholder didn't exist, or it did but it's no longer available,
		// let's recreate it.
		$wp_upload_dir = wp_upload_dir();
		$source        = nelio_content()->plugin_url . '/assets/dist/images/nc-efi-placeholder.png';
		$filename      = trailingslashit( $wp_upload_dir['basedir'] ) . 'nc-efi-placeholder.png';

		update_option( 'nc_efi_placeholder_id', false );

		if ( copy( $source, $filename ) ) {

			$attachment = array(
				'post_mime_type' => 'image/png',
				'post_title'     => _x( 'Placeholder by Nelio Content', 'text', 'nelio-content' ),
				'post_content'   => _x( 'External Featured Image Placeholder by Nelio Content.', 'text', 'nelio-content' ),
				'post_status'    => 'inherit',
				'guid'           => trailingslashit( $wp_upload_dir['baseurl'] ) . 'nc-efi-placeholder.png',
			);

			$attach_id = wp_insert_attachment( $attachment, $filename );

			if ( ! is_wp_error( $attach_id ) ) {

				update_option( 'nc_efi_placeholder_id', $attach_id );

				// Generate the metadata for the attachment, and update the database record.
				require_once ABSPATH . 'wp-admin/includes/image.php';
				$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
				wp_update_attachment_metadata( $attach_id, $attach_data );

			}//end if
		}//end if

		// If the placeholder was successfully created, use it.
		$this->placeholder_id = get_option( 'nc_efi_placeholder_id', false );
		return $this->placeholder_id;

	}//end get_placeholder_id()

	/**
	 * This callback is called when the head section has been completed.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function leave_wp_head_section() {

		$this->in_wp_head = false;

	}//end leave_wp_head_section()

}//end class

/**
 * Returns the external featured image associated to a post, if any.
 *
 * @param int $post_id the ID of the post.
 *
 * @return string|boolean the URL of the external featured image or \`false\` otherwise.
 *
 * @since 2.0.1
 */
function nc_get_external_featured_image( $post_id ) {
	$aux = Nelio_Content_External_Featured_Image_Helper::instance();
	return $aux->get_external_featured_image( $post_id );
}//end nc_get_external_featured_image()
