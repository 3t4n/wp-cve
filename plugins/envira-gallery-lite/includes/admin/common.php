<?php
/**
 * Common admin class.
 *
 * @since 1.0.0
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Common Helper Class
 *
 * @since 1.0.0
 */
class Envira_Gallery_Common_Admin {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Holds the metabox class object.
	 *
	 * @since 1.3.1
	 *
	 * @var object
	 */
	public $metabox;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Load the base class object.
		$this->base = Envira_Gallery_Lite::get_instance();

		// Handle any necessary DB upgrades.
		add_action( 'admin_init', [ $this, 'db_upgrade' ] );

		// Load admin assets.
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );

		// Delete any gallery association on attachment deletion. Also delete any extra cropped images.
		add_action( 'delete_attachment', [ $this, 'delete_gallery_association' ] );
		add_action( 'delete_attachment', [ $this, 'delete_cropped_image' ] );

		// Ensure gallery display is correct when trashing/untrashing galleries.
		add_action( 'wp_trash_post', [ $this, 'trash_gallery' ] );
		add_action( 'untrash_post', [ $this, 'untrash_gallery' ] );

		// Delete attachments, if setting enabled, when a gallery is permanently deleted.
		add_action( 'before_delete_post', [ $this, 'delete_gallery' ] );

		add_filter( 'admin_footer_text', [ $this, 'admin_footer' ], 1, 2 );
		add_action( 'in_admin_footer', [ $this, 'footer_template' ] );
		add_action( 'admin_footer', [ $this, 'notifications_template' ] );
		add_action( 'admin_menu', [ $this, 'add_upgrade_menu_item' ], 99 );
		add_action('admin_head', [ $this, 'admin_inline_styles' ] );
		add_action( 'admin_footer', [ $this, 'admin_sidebar_target' ] );

	}

	/**
	 * Add inline styles
	 *
	 * @since 1.8.7
	 *
	 * @return void
	 */
	public function admin_inline_styles() {
		echo '<style>
			.envira-sidebar-upgrade-pro {
				background-color: #37993B;
			}
			.envira-sidebar-upgrade-pro a {
				color: #fff !important;
			}
		</style>';
	}

	/**
	 * Sidebar Target Blank
	 *
	 * @since 1.8.7
	 *
	 * @return void
	 */
	public function admin_sidebar_target() {
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('li.envira-sidebar-upgrade-pro a').attr('target','_blank');
		});
		</script>
		<?php
	}

	/**
	 * Add lite-specific upgrade to pro menu item.
	 *
	 * @return void
	 */
	public function add_upgrade_menu_item() {

		global $submenu;

		add_submenu_page(
			'edit.php?post_type=envira',
			esc_html__( 'Upgrade to Pro', 'envira-gallery-lite' ),
			esc_html__( 'Upgrade to Pro', 'envira-gallery-lite' ),
			apply_filters( 'envira_gallery_menu_cap', 'manage_options' ),
			esc_url( $this->get_upgrade_link( 'http://enviragallery.com/lite/', 'adminsidebar', 'unlockprosidebar' ) )
		);

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$upgrade_link_position = key(
			array_filter(
				$submenu['edit.php?post_type=envira'],
				static function ( $item ) {
					return strpos( $item[2], 'http://enviragallery.com/lite/' ) !== false;
				}
			)
		);

		// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited
		if ( isset( $submenu['edit.php?post_type=envira'][ $upgrade_link_position ][4] ) ) {
			$submenu['edit.php?post_type=envira'][ $upgrade_link_position ][4] .= ' envira-sidebar-upgrade-pro';
		} else {
			$submenu['edit.php?post_type=envira'][ $upgrade_link_position ][] = 'envira-sidebar-upgrade-pro';
		}
		// phpcs:enable WordPress.WP.GlobalVariablesOverride.Prohibited

	}

	/**
	 * Load footer template
	 *
	 * @since 1.8.7
	 */
	public function footer_template() {
		global $current_screen;
		if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, 'envira' ) !== false ) {
			// If here, we're on an Envira Gallery, so output the footer.
			$this->base->load_admin_partial(
				'footer'
			);
		}
	}
	/**
	 * Helper Method to load footer template
	 *
	 * @since 1.8.7
	 */
	public function notifications_template() {
		global $current_screen;
		if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, 'envira' ) !== false ) {
			// If here, we're on an Envira Gallery, so output the footer.
			$this->base->load_admin_partial(
				'notifications'
			);
		}
	}

	/**
	 * When user is on a Envira related admin page, display footer text
	 * that graciously asks them to rate us.
	 *
	 * @since
	 * @param string $text Footer text.
	 * @return string
	 */
	public function admin_footer( $text ) {
		global $current_screen;
		if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, 'envira' ) !== false ) {
			$url = 'https://wordpress.org/support/plugin/envira-gallery-lite/reviews/?filter=5#new-post';
			/* translators: %s: url */
			$text = sprintf( __( 'Please rate <strong>Envira Gallery</strong> <a href="%1$s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%2$s" target="_blank">WordPress.org</a> to help us spread the word. Thank you from the Envira Gallery team!', 'envira-gallery-lite' ), $url, $url );
		}
		return $text;
	}


	/**
	 * Handles any necessary DB upgrades for Envira.
	 *
	 * @since 1.0.0
	 */
	public function db_upgrade() {

		// Upgrade to allow captions (v1.1.6).
		$captions = get_option( 'envira_gallery_116' );
		if ( ! $captions ) {
			$galleries = Envira_Gallery_Lite::get_instance()->_get_galleries();
			if ( $galleries ) {
				foreach ( $galleries as $gallery ) {
					foreach ( (array) $gallery['gallery'] as $id => $item ) {
						$gallery['gallery'][ $id ]['caption'] = ! empty( $item['title'] ) ? $item['title'] : '';
						update_post_meta( $gallery['id'], '_eg_gallery_data', $gallery );
						Envira_Gallery_Common::get_instance()->flush_gallery_caches( $gallery['id'], $gallery['config']['slug'] );
					}
				}
			}

			update_option( 'envira_gallery_116', true );
		}

		// 1.2.1: Convert all non-Envira Post Type galleries into Envira CPT galleries.
		$cpt_galleries = get_option( 'envira_gallery_121' );
		if ( ! $cpt_galleries ) {
			// Get Post Types, excluding our own
			// We don't use post_status => 'any', as this doesn't include CPTs where exclude_from_search = true.
			$post_types         = get_post_types(
				[
					'public' => true,
				]
			);
			$excluded_posttypes = [ 'envira', 'envira_album', 'attachment' ];
			foreach ( $post_types as $key => $post_type ) {
				if ( in_array( $post_type, $excluded_posttypes, true ) ) {
					unset( $post_types[ $key ] );
				}
			}

			// Get all Posts that have _eg_gallery_data set.
			$in_post_galleries = new WP_Query(
				[
					'post_type'      => $post_types,
					'post_status'    => 'any',
					'posts_per_page' => -1,
					'meta_query'     => array( // @codingStandardsIgnoreLine - Possible slow query
						[
							'key'     => '_eg_gallery_data',
							'compare' => 'EXISTS',
						],
					),
				]
			);

			// Check if any Posts with galleries exist.
			if ( count( $in_post_galleries->posts ) > 0 ) {
				$migrated_galleries = 0;

				// Iterate through Posts with Galleries.
				foreach ( $in_post_galleries->posts as $post ) {
					// Check if this is an Envira or Envira Album CPT.
					// If so, skip it.
					if ( 'envira' === $post->post_type || 'envira_album' === $post->post_type ) {
						continue;
					}

					// Get metadata.
					$data = get_post_meta( $post->ID, '_eg_gallery_data', true );
					$in   = get_post_meta( $post->ID, '_eg_in_gallery', true );

					// Check if there is at least one image in the gallery.
					// Some Posts save Envira config data but don't have images - we don't want to migrate those,
					// as we would end up with blank Envira CPT galleries.
					if ( ! isset( $data['gallery'] ) || ! is_array( $data['gallery'] ) ) {
						continue;
					}

					// If here, we need to create a new Envira CPT.
					$cpt_args = [
						'post_title'  => ( ! empty( $data['config']['title'] ) ? $data['config']['title'] : $post->post_title ),
						'post_status' => $post->post_status,
						'post_type'   => 'envira',
						'post_author' => $post->post_author,
					];
					if ( ! empty( $data['config']['slug'] ) ) {
						$cpt_args['post_name'] = $data['config']['slug'];
					}
					$envira_gallery_id = wp_insert_post( $cpt_args );

					// Check gallery creation was successful.
					if ( is_wp_error( $envira_gallery_id ) ) {
						// @TODO how to handle errors?
						continue;
					}

					// Get Envira Gallery Post.
					$envira_post = get_post( $envira_gallery_id );

					// Map the title and slug of the post object to the custom fields if no value exists yet.
					$data['config']['title'] = trim( wp_strip_all_tags( $envira_post->post_title ) );
					$data['config']['slug']  = sanitize_text_field( $envira_post->post_name );

					// Store post metadata.
					update_post_meta( $envira_gallery_id, '_eg_gallery_data', $data );
					update_post_meta( $envira_gallery_id, '_eg_in_gallery', $in );
					update_post_meta( $envira_gallery_id, '_eg_gallery_old', $post->ID );
					if ( ! empty( $data['config']['slug'] ) ) {
						update_post_meta( $envira_gallery_id, '_eg_gallery_old_slug', $data['config']['slug'] );
					}

					// Remove post metadata from the original Post.
					delete_post_meta( $post->ID, '_eg_gallery_data' );
					delete_post_meta( $post->ID, '_eg_in_gallery' );

					// Search for the envira shortcode in the Post content, and change its ID to the new Envira Gallery ID.
					if ( has_shortcode( $post->post_content, 'envira-gallery' ) ) {
						$pattern = get_shortcode_regex();
						if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches ) ) {
							foreach ( $matches[2] as $key => $shortcode ) {
								if ( 'envira-gallery' === $shortcode ) {
									// Found an envira-gallery shortcode.
									// Change the ID.
									$original_shortcode    = $matches[0][ $key ];
									$replacement_shortcode = str_replace( 'id="' . $post->ID . '"', 'id="' . $envira_gallery_id . '"', $original_shortcode );
									$post->post_content    = str_replace( $original_shortcode, $replacement_shortcode, $post->post_content );
									wp_update_post( $post );
								}
							}
						}
					}

					// Store a relationship between the gallery and this Post.
					update_post_meta( $post->ID, '_eg_gallery_id', $envira_gallery_id );

					// Increment the counter.
					++$migrated_galleries;
				}

				// Display a one time admin notice so the user knows their in-page galleries were migrated.
				if ( $migrated_galleries > 0 ) {
					add_action( 'admin_notices', [ $this, 'notice_galleries_migrated' ] );
				}
			}

			// Force the tags addon to convert any tags to the new CPT system for any galleries that have been converted to Envira post type.
			delete_option( 'envira_tags_taxonomy_migrated' );

			// Mark upgrade as complete.
			update_option( 'envira_gallery_121', true );
		}
	}
	/**
	 * Displays a notice on screen when a user upgrades from Lite to Pro or Lite to Lite 1.5.x,
	 * telling them that their in-page galleries have been migrated.
	 *
	 * @since 1.5.0
	 */
	public function notice_galleries_migrated() {

		?>
		<div class="notice updated">
			<p><strong><?php esc_html_e( 'Envira Gallery', 'envira-gallery-lite' ); ?>:&nbsp;</strong><?php esc_html_e( 'Your existing in-page Galleries can now be found by clicking on Envira Gallery in the WordPress Admin menu.', 'envira-gallery-lite' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Loads styles for all Envira-based Administration Screens.
	 *
	 * @since 1.3.1
	 *
	 * @return void Return early if not on the proper screen.
	 */
	public function admin_styles() {

		// Get current screen.
		$screen = get_current_screen();

		// Bail if we're not on the Envira Post Type screen.
		if ( 'envira' !== $screen->post_type ) {
			return;
		}

		// Load necessary admin styles.
		wp_register_style( $this->base->plugin_slug . '-admin-style', plugins_url( 'assets/css/admin.css', $this->base->file ), [], $this->base->version );
		wp_enqueue_style( $this->base->plugin_slug . '-admin-style' );

		// Fire a hook to load in custom admin styles.
		do_action( 'envira_gallery_admin_styles' );
	}

	/**
	 * Loads scripts for all Envira-based Administration Screens.
	 *
	 * @since 1.3.5
	 *
	 * @return void Return early if not on the proper screen.
	 */
	public function admin_scripts() {

		// Get current screen.
		$screen = get_current_screen();

		// Bail if we're not on the Envira Post Type screen.
		if ( 'envira' !== $screen->post_type ) {
			return;
		}

		wp_enqueue_script( 'clipboard' );
		// Load necessary admin scripts.
		wp_register_script( $this->base->plugin_slug . '-admin-script', plugins_url( 'assets/js/min/admin-min.js', $this->base->file ), [ 'jquery', 'clipboard' ], $this->base->version, false );
		wp_enqueue_script( $this->base->plugin_slug . '-admin-script' );
		wp_localize_script(
			$this->base->plugin_slug . '-admin-script',
			'envira_gallery_admin',
			[
				'ajax'                       => admin_url( 'admin-ajax.php' ),
				'dismiss_notification_nonce' => wp_create_nonce( 'envira_dismiss_notification' ),
				'dismiss_notice_nonce'       => wp_create_nonce( 'envira-gallery-dismiss-notice' ),
				'dismiss_topbar_nonce'       => wp_create_nonce( 'envira-gallery-dismiss-topbar' ),
				'connect_nonce'              => wp_create_nonce( 'envira_gallery_connect' ),
				'oops'                       => esc_html__( 'Oops!', 'envira-gallery-lite' ),
				'ok'                         => esc_html__( 'OK', 'envira-gallery-lite' ),
				'almost_done'                => esc_html__( 'Almost Done', 'envira-gallery-lite' ),
				'server_error'               => esc_html__( 'Unfortunately there was a server connection error.', 'envira-gallery-lite' ),
				'plugin_activate_btn'        => esc_html__( 'Activate', 'envira-gallery-lite' ),
				'unlock_url'                 => esc_url( $this->get_upgrade_link( 'https://enviragallery.com/pricing', 'listgallery', 'unlock' ) ),
				'unlock_title'               => esc_html__( 'Unlock All Features', 'envira-gallery-lite' ),
				'unlock_text'                => esc_html__( 'Upgrade to Pro to get access to Albums, Protected Images,  Video Galleries, and more!', 'envira-gallery-lite' ),
				'unlock_btn'				 => esc_html__( 'Unlock Gallery Features '),
			]
		);

		// Fire a hook to load in custom admin scripts.
		do_action( 'envira_gallery_admin_scripts' );
	}

	/**
	 * Deletes the Envira gallery association for the image being deleted.
	 *
	 * @since 1.0.0
	 *
	 * @param int $attach_id The attachment ID being deleted.
	 */
	public function delete_gallery_association( $attach_id ) {

		$has_gallery = get_post_meta( $attach_id, '_eg_has_gallery', true );

		// Only proceed if the image is attached to any Envira galleries.
		if ( ! empty( $has_gallery ) ) {
			foreach ( (array) $has_gallery as $post_id ) {
				// Remove the in_gallery association.
				$in_gallery = get_post_meta( $post_id, '_eg_in_gallery', true );
				if ( ! empty( $in_gallery ) ) {
					$key = array_search( $attach_id, (array) $in_gallery, true );
					if ( false !== $key ) {
						unset( $in_gallery[ $key ] );
					}
				}

				update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );

				// Remove the image from the gallery altogether.
				$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
				if ( ! empty( $gallery_data['gallery'] ) ) {
					unset( $gallery_data['gallery'][ $attach_id ] );
				}

				// Update the post meta for the gallery.
				update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

				// Flush necessary gallery caches.
				Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id, ( ! empty( $gallery_data['config']['slug'] ) ? $gallery_data['config']['slug'] : '' ) );
			}
		}
	}

	/**
	 * Removes any extra cropped images when an attachment is deleted.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The post ID.
	 * @return void        Return early if the appropriate metadata cannot be retrieved.
	 */
	public function delete_cropped_image( $post_id ) {

		// Get attachment image metadata.
		$metadata = wp_get_attachment_metadata( $post_id );

		// Return if no metadata is found.
		if ( ! $metadata ) {
			return;
		}

		// Return if we don't have the proper metadata.
		if ( ! isset( $metadata['file'] ) || ! isset( $metadata['image_meta']['resized_images'] ) ) {
			return;
		}

		// Grab the necessary info to removed the cropped images.
		$wp_upload_dir  = wp_upload_dir();
		$pathinfo       = pathinfo( $metadata['file'] );
		$resized_images = $metadata['image_meta']['resized_images'];

		// Loop through and deleted and resized/cropped images.
		foreach ( $resized_images as $dims ) {
			// Get the resized images filename and delete the image.
			$file = $wp_upload_dir['basedir'] . '/' . $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '-' . $dims . '.' . $pathinfo['extension'];

			// Delete the resized image.
			if ( file_exists( $file ) ) {
				@unlink( $file ); // @codingStandardsIgnoreLine
			}
		}
	}

	/**
	 * Trash a gallery when the gallery post type is trashed.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $id   The post ID being trashed.
	 * @return void Return early if no gallery is found.
	 */
	public function trash_gallery( $id ) {

		$gallery = get_post( $id );

		// Flush necessary gallery caches to ensure trashed galleries are not showing.
		Envira_Gallery_Common::get_instance()->flush_gallery_caches( $id );

		// Return early if not an Envira gallery.
		if ( 'envira' !== $gallery->post_type ) {
			return;
		}

		// Check some gallery data exists.
		$gallery_data = get_post_meta( $id, '_eg_gallery_data', true );
		if ( empty( $gallery_data ) ) {
			return;
		}

		// Set the gallery status to inactive.
		$gallery_data['status'] = 'inactive';
		update_post_meta( $id, '_eg_gallery_data', $gallery_data );

		// Allow other addons to run routines when a Gallery is trashed.
		do_action( 'envira_gallery_trash', $id, $gallery_data );
	}

	/**
	 * Untrash a gallery when the gallery post type is untrashed.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $id   The post ID being untrashed.
	 * @return void Return early if no gallery is found.
	 */
	public function untrash_gallery( $id ) {

		$gallery = get_post( $id );

		// Flush necessary gallery caches to ensure untrashed galleries are showing.
		Envira_Gallery_Common::get_instance()->flush_gallery_caches( $id );

		// Return early if not an Envira gallery.
		if ( 'envira' !== $gallery->post_type ) {
			return;
		}

		// Set the gallery status to inactive.
		$gallery_data = get_post_meta( $id, '_eg_gallery_data', true );
		if ( empty( $gallery_data ) ) {
			return;
		}

		if ( isset( $gallery_data['status'] ) ) {
			unset( $gallery_data['status'] );
		}

		update_post_meta( $id, '_eg_gallery_data', $gallery_data );

		// Allow other addons to run routines when a Gallery is untrashed.
		do_action( 'envira_gallery_untrash', $id, $gallery_data );
	}

	/**
	 * Fired when a gallery is about to be permanently deleted from Trash
	 *
	 * Checks if the media_delete setting is enabled, and if so safely deletes
	 * media that isn't being used elsewhere on the site
	 *
	 * @since 1.3.6.1
	 *
	 * @param boolean $id Post ID.
	 * @return void
	 */
	public function delete_gallery( $id ) {

		// Check if the media_delete setting is enabled.
		$media_delete = false;
		if ( '1' !== $media_delete ) {
			return;
		}

		// Get post.
		$gallery = get_post( $id );

		// Flush necessary gallery caches to ensure untrashed galleries are showing.
		Envira_Gallery_Common::get_instance()->flush_gallery_caches( $id );

		// Return early if not an Envira gallery.
		if ( 'envira' !== $gallery->post_type ) {
			return;
		}

		// Get attached media.
		$media = get_attached_media( 'image', $id );
		if ( ! is_array( $media ) || 0 === count( $media ) ) {
			return;
		}

		// Iterate through media, deleting.
		foreach ( $media as $image ) {
			wp_delete_attachment( $image->ID );
		}
	}

	/**
	 * Called whenever an upgrade button / link is displayed in Lite, this function will
	 * check if there's a shareasale ID specified.
	 *
	 * There are three ways to specify an ID, ordered by highest to lowest priority
	 * - add_filter( 'envira_gallery_shareasale_id', function() { return 1234; } );
	 * - define( 'ENVIRA_GALLERY_SHAREASALE_ID', 1234 );
	 * - get_option( 'envira_gallery_shareasale_id' ); (with the option being in the wp_options table)
	 *
	 * utm_source = liteplugin
	 * utm_medium = page
	 * utm_campaign = what button was clicked, etc.
	 *
	 * If an ID is present, returns the ShareASale link with the affiliate ID, and tells
	 * ShareASale to then redirect to enviragallery.com/lite
	 *
	 * If no ID is present, just returns the enviragallery.com/lite URL with UTM tracking.
	 *
	 * @param string $url Url for upgrade.
	 * @param string $medium utm_medium.
	 * @param string $button utm_campaign.
	 * @param string $append append to the end of the url.
	 *
	 * @since 1.5.0
	 */
	public function get_upgrade_link( $url = false, $medium = 'default', $button = 'default', $append = false ) {

		// Check if there's a constant.
		$shareasale_id = '';
		if ( defined( 'ENVIRA_GALLERY_SHAREASALE_ID' ) ) {
			$shareasale_id = ENVIRA_GALLERY_SHAREASALE_ID;
		}

		// If there's no constant, check if there's an option.
		if ( empty( $shareasale_id ) ) {
			$shareasale_id = get_option( 'envira_gallery_shareasale_id', '' );
		}

		// Whether we have an ID or not, filter the ID.
		$shareasale_id = apply_filters( 'envira_gallery_shareasale_id', $shareasale_id );

		// If at this point we still don't have an ID, we really don't have one!
		// Just return the standard upgrade URL.
		if ( empty( $shareasale_id ) ) {
			if ( false === filter_var( $url, FILTER_VALIDATE_URL ) ) {
				// prevent a possible typo.
				$url = false;
			}
			$url = ( false !== $url ) ? trailingslashit( esc_url( $url ) ) : 'https://enviragallery.com/lite/';
			return $url . '?utm_source=liteplugin&utm_medium=' . $medium . '&utm_campaign=' . $button . $append;
		}

		// If here, we have a ShareASale ID
		// Return ShareASale URL with redirect.
		return 'http://www.shareasale.com/r.cfm?u=' . $shareasale_id . '&b=566240&m=51693&afftrack=&urllink=enviragallery%2Ecom%2Flite%2F';
	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return object The Envira_Gallery_Common_Admin object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Gallery_Common_Admin ) ) {
			self::$instance = new Envira_Gallery_Common_Admin();
		}

		return self::$instance;
	}
}

// Load the common admin class.
$envira_gallery_common_admin = Envira_Gallery_Common_Admin::get_instance();
