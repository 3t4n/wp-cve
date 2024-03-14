<?php
/**
 * Image class.
 *
 * @package WP_To_Social_Pro
 * @author WP Zinc
 */

/**
 * Determines optimal image sizes and aspect ratios for each
 * social networks, detects if such sizes are registered
 * in WordPress and (where possible) resizes and crops
 * images.
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 * @version 4.6.6
 */
class WP_To_Social_Pro_Image {

	/**
	 * Holds the base class object.
	 *
	 * @since   4.6.6
	 *
	 * @var     object
	 */
	public $base;

	/**
	 * Constructor
	 *
	 * @since   4.6.6
	 *
	 * @param   object $base    Base Plugin Class.
	 */
	public function __construct( $base ) {

		// Store base class.
		$this->base = $base;

	}

	/**
	 * Helper method to retrieve Featured Image Options
	 *
	 * @since   3.4.3
	 *
	 * @param   bool   $network    Network (false = defaults).
	 * @param   string $post_type  Post Type.
	 * @return  array               Featured Image Options
	 */
	public function get_featured_image_options( $network = false, $post_type = false ) {

		// If a Post Type has been specified, get its featured_image label.
		$label = __( 'Feat. Image', 'wp-to-hootsuite' );
		if ( $post_type !== false && $post_type !== 'bulk' ) {
			$post_type_object = get_post_type_object( $post_type );
			$label            = $post_type_object->labels->featured_image;
		}

		// Build featured image options, depending on the Plugin.
		switch ( $this->base->plugin->name ) {

			case 'wp-to-buffer':
				$options = array(
					-1 => __( 'No Image', 'wp-to-hootsuite' ),
					0  => __( 'Use OpenGraph Settings', 'wp-to-hootsuite' ),
					2  => sprintf(
						/* translators: Translated name for a Post Type's Featured Image (e.g. for WooCommerce, might be "Product image") */
						__( 'Use %s, not Linked to Post', 'wp-to-hootsuite' ),
						$label
					),
				);
				break;

			case 'wp-to-hootsuite':
				$options = array(
					-1 => __( 'No Image', 'wp-to-hootsuite' ),
					2  => sprintf(
						/* translators: Translated name for a Post Type's Featured Image (e.g. for WooCommerce, might be "Product image") */
						__( 'Use %s, not Linked to Post', 'wp-to-hootsuite' ),
						$label
					),
				);
				break;

		}

		// Depending on the network, remove some options that aren't supported.
		switch ( $network ) {
			/**
			 * Twitter
			 * - Remove "Use Feat. Image, Linked to Post"
			 */
			case 'twitter':
				unset( $options[1], $options[3] );
				break;

			/**
			 * Instagram, Pinterest
			 * - Remove all options excluding "Use Feat. Image, not Linked to Post"
			 */
			case 'instagram':
			case 'pinterest':
				unset( $options[0], $options[1], $options[3] );
				break;
		}

		/**
		 * Defines the available Featured Image select dropdown options on a status, depending
		 * on the Plugin and Social Network the status message is for.
		 *
		 * @since   3.4.3
		 *
		 * @param   array   $options    Featured Image Dropdown Options.
		 * @param   string  $network    Social Network.
		 */
		$options = apply_filters( $this->base->plugin->filter_name . '_get_featured_image_options', $options, $network );

		// Return filtered results.
		return $options;

	}

	/**
	 * Determines if "Use OpenGraph Settings" is an option available for the Status Image dropdown
	 *
	 * @since   4.2.0
	 *
	 * @return  bool    Supports OpenGraph
	 */
	public function supports_opengraph() {

		$featured_image_options = $this->get_featured_image_options();

		if ( isset( $featured_image_options[0] ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Determines if the WordPress installations has a Plugin installed that outputs
	 * OpenGraph metadata
	 *
	 * @since   4.4.0
	 *
	 * @return  bool    Supports OpenGraph
	 */
	public function is_opengraph_plugin_active() {

		// Load function if required.
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Fetch OpenGraph supported SEO Plugins and Fetured Image Options.
		$featured_image_options = array_keys( $this->get_featured_image_options() );

		// If the Plugin only offers "Use OpenGraph Settings", no need to check for SEO Plugin availability.
		if ( count( $featured_image_options ) === 1 && ! $featured_image_options[0] ) {
			return false;
		}

		foreach ( $this->get_opengraph_seo_plugins() as $seo_plugin ) {
			// If plugin active, use OpenGraph for images.
			if ( is_plugin_active( $seo_plugin ) ) {
				return true;
			}
		}

		return false;

	}

	/**
	 * Returns the image for the given Attachment ID, based on the social media service
	 * the image will be used for.
	 *
	 * If the image isn't a compatible mime type, this function will attempt to convert the image
	 * from e.g. webp --> jpg.
	 *
	 * Checks that the image will meet the aspect ratio requirements for posting to Instagram,
	 * returning a valid image if the large size would fail.
	 *
	 * @since   4.6.6
	 *
	 * @param   int         $image_id   Image ID.
	 * @param   string      $source     Source Image ID was derived from (plugin, featured_image, post_content, text_to_image).
	 * @param   bool|string $service    Social Media Service the image is for. If not defined, just return the large version.
	 * @return  array|WP_Error              Image ID, Image URLs, Source
	 */
	public function get_image_sources( $image_id, $source, $service = false ) {

		$image_mime_type = get_post_mime_type( $image_id );

		// If the image is a webp, attempt to convert it to a JPEG and store in the Media Library
		// as webp isn't supported by all social media services.
		// Check that the image source is a supported format i.e. not a webp.
		switch ( $image_mime_type ) {
			/**
			 * Webp
			 */
			case 'image/webp':
				// Don't do anything if the service supports webp and the image isn't for Instagram.
				// If it is for Instagram, we want to convert to a JPEG as we might need to resize/crop
				// later in this function.
				if ( $this->base->supports( 'webp' ) && $service !== 'instagram' ) {
					break;
				}

				// Get image.
				$image_path_and_file = get_attached_file( $image_id );

				// Just return the original image ID if we couldn't get the image path and file.
				if ( empty( $image_path_and_file ) || ! file_exists( $image_path_and_file ) ) {
					return $image_id;
				}

				// Load webp image.
				$image = wp_get_image_editor( $image_path_and_file );

				// Bail if an error occured.
				if ( is_wp_error( $image ) ) {
					return $image;
				}

				// Save to temporary file on disk.
				$converted_image = $image->save( get_temp_dir() . 'wp-to-social-pro-' . $image_id . '-converted-' . bin2hex( random_bytes( 5 ) ) );

				// Bail if an error occured.
				if ( is_wp_error( $converted_image ) ) {
					return $converted_image;
				}

				// Upload to Media Library.
				$converted_image_id = $this->base->get_class( 'media_library' )->upload_local_image( $converted_image['path'] );

				// Bail if an error occured.
				if ( is_wp_error( $converted_image_id ) ) {
					return $converted_image_id;
				}

				// Assign image ID.
				$image_id = $converted_image_id;
				break;

			default:
				/**
				 * Defines the image ID to use as the image or additional image for the status message.
				 * If an image's mime type is not supported by the social media scheduling service, this
				 * filter can be used to convert the image to a supported type, store it in the Media Library
				 * and return the converted image ID.
				 *
				 * This is already performed for webp images.
				 *
				 * @since   4.6.8
				 *
				 * @param   int     $image_id           Image ID.
				 * @param   string  $source             Source Image ID was derived from (plugin, featured_image, post_content, text_to_image).
				 * @param   string  $service            Social Media Service the image is for. If not defined, just return the large version.
				 * @param   string  $image_mime_type    Image MIME Type.
				 */
				$image_id = apply_filters( 'wp_to_social_pro_image_get_images_sources_convert', $image_id, $source, $service, $image_mime_type );
				break;
		}

		return $this->get_image_source_by_size( $image_id, $source, 'large' );

	}

	/**
	 * Returns an array comprising of the image ID, image URL and alt text for the requested size, thumbnail size
	 * and the source of the image.
	 *
	 * @since   4.6.6
	 *
	 * @param   int    $image_id   Image ID.
	 * @param   string $source     Source Image ID was derived from (plugin, featured_image, post_content, text_to_image).
	 * @param   string $size       WordPress Registered Image Size to return the image as.
	 * @return  array               Image ID, Image URLs, Source
	 */
	private function get_image_source_by_size( $image_id, $source, $size ) {

		// Get image at requested size.
		$image = wp_get_attachment_image_src( $image_id, $size );

		// Get thumbnail version, which some APIs might use for a small preview.
		$thumbnail = wp_get_attachment_image_src( $image_id, 'thumbnail' );

		// Return URLs only.
		return array(
			'id'        => $image_id,
			'image'     => ( is_array( $image ) ? strtok( $image[0], '?' ) : false ), // Strip query parameters that might break some APIs.
			'thumbnail' => ( is_array( $thumbnail ) ? strtok( $thumbnail[0], '?' ) : false ), // Strip query parameters that might break some APIs.
			'alt_text'  => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
			'source'    => $source,
		);

	}

	/**
	 * Return an array of Plugins that output OpenGraph data
	 * which can be used by this Plugin for sharing the Featured Image
	 *
	 * @since   4.6.6
	 *
	 * @return  array   Plugins
	 */
	private function get_opengraph_seo_plugins() {

		// Define Plugins.
		$plugins = array();

		/**
		 * Defines the Plugins that output OpenGraph metadata on Posts, Pages
		 * and Custom Post Types.
		 *
		 * @since   3.7.9
		 *
		 * @param   array   $plugins    Plugins
		 */
		$plugins = apply_filters( $this->base->plugin->filter_name . '_get_opengraph_seo_plugins', $plugins );

		// Return filtered results.
		return $plugins;

	}

}
