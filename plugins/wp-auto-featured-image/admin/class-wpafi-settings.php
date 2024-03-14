<?php
/**
 * WP Auto Featured Image Admin Settings
 *
 * @package WP_Auto_Featured_Image
 */

/**
 * Class WPAFI_Settings
 *
 * This file contains the admin settings class for the WP Auto Featured Image plugin.
 *
 * @since   2.0
 * @package WP_Auto_Featured_Image
 */
class WPAFI_Settings {

	/**
	 * Constructor for the admin class.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init_settings' ) );
	}

	/**
	 * Register plugin settings.
	 */
	public function init_settings() {
		register_setting( 'wp_auto_featured_image_options', 'wpafi_options', array( $this, 'sanitize_options' ) );
		add_settings_section( 'wpafi_default_section', 'General Settings', array( $this, 'wpafi_description' ), 'wp_auto_featured_image_options' );
		add_settings_field( 'wpafi_post_type', 'Post Types:', array( $this, 'wpafi_post_types' ), 'wp_auto_featured_image_options', 'wpafi_default_section' );
		add_settings_field( 'wpafi_categories', 'Categories:', array( $this, 'wpafi_categories' ), 'wp_auto_featured_image_options', 'wpafi_default_section' );
		add_settings_field( 'wpafi_tags', 'Tags:', array( $this, 'wpafi_tags' ), 'wp_auto_featured_image_options', 'wpafi_default_section' );
		add_settings_field( 'wpafi_default_thumbnail', 'Default Thumbnail:', array( $this, 'wpafi_default_thumbnail' ), 'wp_auto_featured_image_options', 'wpafi_default_section' );
	}

	/**
	 * Display the description for the General Settings section.
	 */
	public function wpafi_description() {
		echo '<div class="wpafi-description">';
		echo '<p>' . esc_html__( 'WP Auto Featured Image allows you to streamline the process of setting featured images effortlessly for your posts, pages, or custom post types. Establish a default fallback image based on categories and ensure a consistent and efficient way to manage featured images across your content.', 'wp-default-featured-image' ) . '</p>';
		echo '<p>' . esc_html__( 'Please note that the conditions specified below work in conjunction with an AND logical operator. This means that all conditions must be true for the featured image to be set.', 'wp-default-featured-image' ) . '</p>';
		echo '<p>' . esc_html__( 'The thumbnail will be set when a post is published. For "page" post types, conditions such as category and tags will be ignored, and the default thumbnail will be applied to all pages upon publishing.', 'wp-default-featured-image' ) . '</p>';
		echo '</div>';
	}

	/**
	 * Sanitize and validate options.
	 *
	 * @param  array $input The un sanitized input.
	 * @return array The sanitized input.
	 */
	public function sanitize_options( $input ) {
		$sanitized_input = array();

		// List of multi-select fields to sanitize.
		$multi_select_fields = array( 'wpafi_post_type', 'wpafi_categories', 'wpafi_tags' );

		foreach ( $multi_select_fields as $field ) {
			if ( isset( $input[ $field ] ) && is_array( $input[ $field ] ) ) {
				$sanitized_input[ $field ] = array_map( 'sanitize_text_field', $input[ $field ] );
			}
		}

		// Sanitize specific fields.
		$specific_fields = array( 'wpafi_default_thumb_id' );

		foreach ( $specific_fields as $field ) {
			if ( isset( $input[ $field ] ) ) {
				$sanitized_input['wpafi_default_thumb_id'] = intval( $input[ $field ] );
			}
		}

		return $sanitized_input;
	}


	/**
	 * Render a multiselect dropdown for post types in the plugin settings.
	 */
	public function wpafi_post_types() {
		/**
		 * Options saved in the WordPress database.
		 *
		 * @var array
		 */
		$options = get_option( 'wpafi_options' );

		/**
		 * Array of public post types.
		 *
		 * @var array
		 */
		$post_types = get_post_types( array( 'public' => true ), 'names' );
		echo '<select  class="wpafi-select" id="wpafi-multiselect" name="wpafi_options[wpafi_post_type][]" multiple="multiple">';
		foreach ( $post_types as $post_type ) {
			if ( 'attachment' !== $post_type ) {
				$selected = '';
				if ( ! empty( $options['wpafi_post_type'] ) ) {
					if ( in_array( $post_type, $options['wpafi_post_type'], true ) ) {
						$selected = " selected='selected'";
					}
				}
				echo '<option value="' . esc_attr( $post_type ) . '"' . esc_attr( $selected ) . '>' . esc_html( preg_replace( '/[-_]/', ' ', $post_type ) ) . '</option>';
			}
		}
		echo '</select>';
	}

	/**
	 * Render a multiselect dropdown for categories in the plugin settings.
	 */
	public function wpafi_categories() {
		$options    = get_option( 'wpafi_options' );
		$wpafi_cats = get_categories(
			array(
				'hide_empty' => 0,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		echo '<select class="wpafi-select" id="wpafi-category-multiselect" name="wpafi_options[wpafi_categories][]" multiple="multiple">';
		foreach ( $wpafi_cats as $wpafi_cat ) {

			$selected = '';
			if ( ! empty( $options['wpafi_categories'] ) && is_array( $options['wpafi_categories'] ) ) {
				$selected = in_array( $wpafi_cat->slug, $options['wpafi_categories'], true ) ? ' selected="selected"' : '';
			}
			echo '<option value="' . esc_attr( $wpafi_cat->slug ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $wpafi_cat->name ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Render a multiselect dropdown for tags in the plugin settings.
	 */
	public function wpafi_tags() {
		$options = get_option( 'wpafi_options' );
		$tags    = get_tags();

		echo '<select id="wpafi-tag-multiselect" class="wpafi-select"  name="wpafi_options[wpafi_tags][]" multiple="multiple">';
		foreach ( $tags as $tag ) {
			$selected = in_array( $tag->slug, $options['wpafi_tags'], true ) ? ' selected="selected"' : '';
			echo '<option value="' . esc_attr( $tag->slug ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $tag->name ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Renders the HTML for the default thumbnail settings in the admin panel.
	 */
	public function wpafi_default_thumbnail() {
		$options = get_option( 'wpafi_options' );
		?>
		<div class="upload-container">
			<input type="hidden" id="default_thumb_id" name="wpafi_options[wpafi_default_thumb_id]" value="<?php echo esc_attr( $options['wpafi_default_thumb_id'] ); ?>" />
			<button id="upload_default_thumb" class="button" type="button"><?php esc_html_e( 'Upload Thumbnail', 'wp-default-featured-image' ); ?></button>
		<?php if ( ! empty( $options['wpafi_default_thumb_id'] ) ) : ?>
				<button id="delete_thumb" name="delete_thumb" class="button" type="button"><?php esc_html_e( 'Delete Thumbnail', 'wp-default-featured-image' ); ?></button>
		<?php endif; ?>
			<div id="uploaded_thumb_preview">
		<?php
		if ( ! empty( $options['wpafi_default_thumb_id'] ) ) {
			// Use wp_get_attachment_image to display the image by ID.
			echo wp_get_attachment_image( $options['wpafi_default_thumb_id'], 'full', false, array( 'style' => 'max-width:100%;' ) );
		}
		?>
			</div>
		</div>
		<?php
	}
}
