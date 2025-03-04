<?php
/**
 * DemoBar Meta Boxes
 *
 * @package DemoBar/Admin/Classes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * DemoBar_Admin_Meta_Boxes.
 */
class DemoBar_Admin_Meta_Boxes {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 3 );
	}

	/**
	 * Add WC Meta boxes.
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'dbsite-settings',
			esc_html__( 'Site Info', 'demo-bar' ),
			array( $this, 'render_site_settings_metabox' ),
			'dbsite',
			'normal',
			'high'
		);
	}
	/**
	 * Render site settings metabox.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post    WP_Post object.
	 * @param array   $metabox Metabox arguments.
	 */
	function render_site_settings_metabox( $post, $metabox ) {
		// Meta box nonce for verification.
		wp_nonce_field( 'demobar_save_data', 'demobar_meta_nonce' );

		$demo_bar_site_url     = get_post_meta( $post->ID, 'demo_bar_site_url', true );
		$demo_bar_download_label = get_post_meta( $post->ID, 'demo_bar_download_label', true );
		$demo_bar_download_url = get_post_meta( $post->ID, 'demo_bar_download_url', true );
		$demo_bar_purchase_label = get_post_meta( $post->ID, 'demo_bar_purchase_label', true );
		$demo_bar_purchase_url = get_post_meta( $post->ID, 'demo_bar_purchase_url', true );
		?>
		<p>
			<label for="demo_bar_site_url"><?php echo esc_html__( 'Site URL', 'demo-bar' ); ?><br /><input type="text" value="<?php echo esc_url( $demo_bar_site_url ); ?>" class="regular-text" name="demo_bar_site_url" id="demo_bar_site_url" /></label>
		</p>
		<p>
			<label for="demo_bar_download_label"><?php echo esc_html__( 'Download Label', 'demo-bar' ); ?><br /><input type="text" value="<?php echo esc_html( $demo_bar_download_label ); ?>" class="regular-text" name="demo_bar_download_label" id="demo_bar_download_label" /></label>
		</p>
		<p>
			<label for="demo_bar_download_url"><?php echo esc_html__( 'Download URL', 'demo-bar' ); ?><br /><input type="text" value="<?php echo esc_url( $demo_bar_download_url ); ?>" class="regular-text" name="demo_bar_download_url" id="demo_bar_download_url" /></label>
		</p>
		<p>
			<label for="demo_bar_purchase_label"><?php echo esc_html__( 'Purchase Label', 'demo-bar' ); ?><br /><input type="text" value="<?php echo esc_html( $demo_bar_purchase_label ); ?>" class="regular-text" name="demo_bar_purchase_label" id="demo_bar_purchase_label" /></label>
		</p>
		<p>
			<label for="demo_bar_purchase_url"><?php echo esc_html__( 'Purchase URL', 'demo-bar' ); ?><br /><input type="text" value="<?php echo esc_url( $demo_bar_purchase_url ); ?>" class="regular-text" name="demo_bar_purchase_url" id="demo_bar_purchase_url" /></label>
		</p>
		<?php
	}
	/**
	 * Save site settings meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 * @param bool    $update  Whether this is an existing post being updated or not.
	 */
	function save_meta_boxes( $post_id, $post, $update ) {
		// Verify nonce.
		if ( ! isset( $_POST['demobar_meta_nonce'] ) || ! wp_verify_nonce( $_POST['demobar_meta_nonce'], 'demobar_save_data' ) ) {
			return;
		}

		// Bail if auto save or revision.
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if ( '' == $_POST['post_ID'] || absint( $_POST['post_ID'] ) !== $post_id ) {
			return;
		}

		// Check permission.
		if ( 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$site_settings_fields = array(
			'demo_bar_site_url',
			'demo_bar_download_label',
			'demo_bar_download_url',
			'demo_bar_purchase_label',
			'demo_bar_purchase_url',
		);
		foreach ( $site_settings_fields as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				$post_value = $_POST[ $key ];
				if ( empty( $post_value ) ) {
					delete_post_meta( $post_id, $key );

				} elseif ( 'demo_bar_download_label' === $key || 'demo_bar_purchase_label' === $key ) {
					update_post_meta( $post_id, $key, $post_value );
				} else {
					update_post_meta( $post_id, $key, esc_url_raw( $post_value ) );
				}
			}
		} // End foreach loop.
	}
}

new DemoBar_Admin_Meta_Boxes();
