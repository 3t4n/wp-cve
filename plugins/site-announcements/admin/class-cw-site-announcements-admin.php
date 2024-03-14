<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Site_Announcements
 * @subpackage CW_Site_Announcements/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CW_Site_Announcements
 * @subpackage CW_Site_Announcements/admin
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class CW_Site_Announcements_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cw-site-announcements-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );

	}

	public function cw_announcement_meta_boxes() {
		add_meta_box( 'cw-announcement-meta-box', __( 'Announcement Details', 'cw-announcements' ), array( $this, 'cw_announcement_meta_callback' ), 'cw-announcement', 'normal', 'high' );
	}

	public function cw_announcement_meta_callback( $post ) {

		wp_nonce_field( 'cw_announcement_meta', 'cw_announcement_meta_nonce' );

		$a = new CW_Announcement( $post->ID );

		if( empty( $a->background_color ) ) {
			$a->background_color = '#000000';
		}

		if( empty( $a->text_color ) ) {
			$a->text_color = '#FFFFFF';
		}

		echo '<label for="cw_background_color">
						<h4>' . __('Background Color', 'cw-announcement' ) . '</h4>
						<input class="widefat color-picker" type="text" name="cw_background_color" id="cw_background_color" value="' . $a->background_color . '" />
					</label>';

		echo '<label for="cw_text_color">
						<h4>' . __('Text Color', 'cw-announcement' ) . '</h4>
						<input class="widefat color-picker" type="text" name="cw_text_color" id="cw_text_color" value="' . $a->text_color . '" />
					</label>';

		$checked = '' == $a->url ? false : true;
		echo '<h4>' . __('Link to URL', 'cw-announcement' ) . '</h4>
					<input type="checkbox" name="cw_enable_url" id="cw_enable_url" value="1" ' . checked( true, $checked, false ) . '>';

		$url_attr = $checked == true ? '' : 'style="display: none;"';
		echo '<div class="cw_announcement_url" ' . $url_attr . '>
						<label for="cw_announcement_url">
							<h4>' . __('URL Override', 'cw-announcement' ) . '<br>
							<small>&#42;' . __('leave this blank to open the announcement in a modal', 'cw-announcement' ) . '</small>
							</h4>
							<input class="widefat" type="url" name="cw_announcement_url" id="cw_announcement_url" value="' . $a->url . '" />
						</label>
					</div>';

		if( '' == $a->closable ) {
			$a->closable = true;
			$checked = true;
		} else {
			if( $a->closable == 1 ) {
				$a->closable = true;
				$checked = true;
			} else {
				$checked = false;
			}
		}

		echo '<h4>' . __('Is Closable?', 'cw-announcement' ) . '</h4>
					<input type="checkbox" name="cw_is_announcement_closable" id="cw_is_announcement_closable" value="1" ' . checked( true, $checked, false ) . '>';

		$closable_attr = $checked == true ? '' : 'style="display: none;"';

		$closable_opts = array(
			 '0' => __('Always Show', 'cw-announcement' ),
			 '1' => __('Hide for 1 Hour', 'cw-announcement' ),
			 '24' => __('Hide for 1 Day', 'cw-announcement' ),
			 '48' => __('Hide for 2 Days', 'cw-announcement' ),
			 '72' => __('Hide for 3 Days', 'cw-announcement' ),
			 '168' => __('Hide for 7 Days', 'cw-announcement' ),
			 '720' => __('Hide for 30 Days', 'cw-announcement' ),
			 '8760' => __('Hide for 1 Year', 'cw-announcement' ),
			 'forever' => __('Hide Forever', 'cw-announcement' ),
			 );

		echo '<div class="cw_closable_settings" ' . $closable_attr . '>
					<label for="cw_closable_settings">
					<h4>' . __('Duration', 'cw-announcement') . '<br>
					<small>&#42;' . __('amount of time to hide this announcement from the user after they\'ve closed it', 'cw-announcement' ) . '</small></h4>
					<select id="cw_closable_time" name="cw_closable_time">';

					foreach( $closable_opts as $k => $v ) {
						$selected = $a->closable_duration == $k ? 'selected' : '';
						echo '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
					}

		echo '</select>
					</label>
					</div>';

		
	}

	public function cw_announcement_meta_save( $post_id ) {
		if ( ! isset( $_POST['cw_announcement_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['cw_announcement_meta_nonce'], 'cw_announcement_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'cw-announcement' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$closable = empty( $_POST['cw_is_announcement_closable'] ) ? 0 : 1;

		$data = array(
			'cw_background_color' => esc_attr( $_POST['cw_background_color'] ),
			'cw_text_color' => esc_attr( $_POST['cw_text_color'] ),
			'cw_announcement_url' => esc_url( $_POST['cw_announcement_url'] ),
			'cw_announcement_closable_duration' => esc_attr( $_POST['cw_closable_time'] ),
			'cw_announcement_closable' => $closable,
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

	}

}
