<?php
/**
 * Ogp Plus
 *
 * @package    Ogp Plus
 * @subpackage OgpPlusAdmin Management screen
	Copyright (c) 2019- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$ogpplusadmin = new OgpPlusAdmin();

/** ==================================================
 * Management screen
 */
class OgpPlusAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );
	}

	/** ==================================================
	 * Add a "Settings" link to the plugins page
	 *
	 * @param  array  $links  links array.
	 * @param  string $file   file.
	 * @return array  $links  links array.
	 * @since 1.00
	 */
	public function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) {
			$this_plugin = 'ogp-plus/ogpplus.php';
		}
		if ( $file === $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=ogpplus' ) . '">' . __( 'Settings' ) . '</a>';
		}
			return $links;
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_menu() {
		add_options_page( 'Ogp Plus Options', 'Ogp Plus', 'manage_options', 'ogpplus', array( $this, 'plugin_options' ) );
	}

	/** ==================================================
	 * Add Css and Script
	 *
	 * @since 1.00
	 */
	public function load_custom_wp_admin_style() {
		if ( $this->is_my_plugin_screen() ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'ogpplus-admin-js', plugin_dir_url( __DIR__ ) . 'js/jquery.ogpplus.admin.js', array( 'jquery' ), '1.0.0', false );
			wp_enqueue_media();
			wp_localize_script( 'ogpplus-admin-js', 'ogpplus', array( 'button' => __( 'Choose Default OGP image', 'ogp-plus' ) ) );
		}
	}

	/** ==================================================
	 * For only admin style
	 *
	 * @since 1.00
	 */
	private function is_my_plugin_screen() {
		$screen = get_current_screen();
		if ( is_object( $screen ) && 'settings_page_ogpplus' === $screen->id ) {
			return true;
		} else {
			return false;
		}
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_options() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$this->options_updated();

		$scriptname = admin_url( 'options-general.php?page=ogpplus' );
		$ogpplus_settings = get_option(
			'ogpplus_settings',
			array(
				'excerpt' => 100,
				'df_img_id' => null,
				'tw_user_name' => null,
				'fb_app_id' => null,
			)
		);

		if ( ! empty( $ogpplus_settings['df_img_id'] ) ) {
			$thumb = wp_get_attachment_image_src( $ogpplus_settings['df_img_id'], 'medium' );
			$ogp_img_url = $thumb[0];
		} else {
			$ogp_img_url = null;
		}

		?>

		<div class="wrap">
		<h2>Ogp Plus</h2>
		<div style="clear: both;"></div>

			<details>
			<summary><strong><?php esc_html_e( 'Various links of this plugin', 'ogp-plus' ); ?></strong></summary>
			<?php $this->credit(); ?>
			</details>

			<h3><?php esc_html_e( 'Settings' ); ?></h3>	

			<form method="post" action="<?php echo esc_url( $scriptname ); ?>">
			<?php wp_nonce_field( 'ogp_set', 'ogpplus_set' ); ?>

			<div style="display: block;padding:5px 5px">
			<strong><?php esc_html_e( 'Description length', 'ogp-plus' ); ?>: </strong>
			<input type="range" id="excerpt_bar" style="vertical-align:middle;" step="1" min="0" max="120" name="excerpt" value="<?php echo esc_attr( $ogpplus_settings['excerpt'] ); ?>" /><span id="excerpt_range"></span>
			<p class="description">
			<?php esc_html_e( 'Specify the length of each post and page excerpt display other than the homepage.', 'ogp-plus' ); ?>
			</p>
			</div>

			<div style="display: block;padding:5px 5px">
			<input type="hidden" name="df_img_id" value="<?php echo esc_attr( $ogpplus_settings['df_img_id'] ); ?>" id="media-id">
			<div class="image-preview-wrapper">
			<img src="<?php echo esc_url( $ogp_img_url ); ?>" height="100">
			</div>
			<div>
			<button type="button" id="media-upload" class="button"><?php esc_html_e( 'Choose Default OGP image', 'ogp-plus' ); ?></button>
			</div>
			<p class="description">
			<?php esc_html_e( 'If there is an post thumbnail on post or page, it will take precedence.', 'ogp-plus' ); ?>
			</p>
			</div>

			<div style="display: block;padding:5px 5px">
			<strong><?php esc_html_e( 'X(Twitter) user name', 'ogp-plus' ); ?> : </strong>
			<input type="text" name="tw_user_name" value="<?php echo esc_attr( $ogpplus_settings['tw_user_name'] ); ?>">
			</div>

			<div style="display: block;padding:5px 5px">
			<strong><?php esc_html_e( 'Facebook App ID', 'ogp-plus' ); ?> : </strong>
			<input type="text" name="fb_app_id" value="<?php echo esc_attr( $ogpplus_settings['fb_app_id'] ); ?>">
			</div>

			<p class="submit">
			<?php submit_button( __( 'Save Changes' ), 'large', 'Manageset', false ); ?>
			<?php submit_button( __( 'Default' ), 'large', 'Defaultset', false ); ?>
			</p>

			</form>

		</div>
		<?php
	}

	/** ==================================================
	 * Credit
	 *
	 * @since 1.00
	 */
	private function credit() {

		$plugin_name    = null;
		$plugin_ver_num = null;
		$plugin_path    = plugin_dir_path( __DIR__ );
		$plugin_dir     = untrailingslashit( wp_normalize_path( $plugin_path ) );
		$slugs          = explode( '/', $plugin_dir );
		$slug           = end( $slugs );
		$files          = scandir( $plugin_dir );
		foreach ( $files as $file ) {
			if ( '.' === $file || '..' === $file || is_dir( $plugin_path . $file ) ) {
				continue;
			} else {
				$exts = explode( '.', $file );
				$ext  = strtolower( end( $exts ) );
				if ( 'php' === $ext ) {
					$plugin_datas = get_file_data(
						$plugin_path . $file,
						array(
							'name'    => 'Plugin Name',
							'version' => 'Version',
						)
					);
					if ( array_key_exists( 'name', $plugin_datas ) && ! empty( $plugin_datas['name'] ) && array_key_exists( 'version', $plugin_datas ) && ! empty( $plugin_datas['version'] ) ) {
						$plugin_name    = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}
		$plugin_version = __( 'Version:' ) . ' ' . $plugin_ver_num;
		/* translators: FAQ Link & Slug */
		$faq       = sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'ogp-plus' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = __( 'https://shop.riverforest-wp.info/donate/', 'ogp-plus' );

		?>
		<span style="font-weight: bold;">
		<div>
		<?php echo esc_html( $plugin_version ); ?> | 
		<a style="text-decoration: none;" href="<?php echo esc_url( $faq ); ?>" target="_blank" rel="noopener noreferrer">FAQ</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $support ); ?>" target="_blank" rel="noopener noreferrer">Support Forums</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $review ); ?>" target="_blank" rel="noopener noreferrer">Reviews</a>
		</div>
		<div>
		<a style="text-decoration: none;" href="<?php echo esc_url( $translate ); ?>" target="_blank" rel="noopener noreferrer">
		<?php
		/* translators: Plugin translation link */
		echo esc_html( sprintf( __( 'Translations for %s' ), $plugin_name ) );
		?>
		</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-twitter"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-video-alt3"></span></a>
		</div>
		</span>

		<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
		<h3><?php esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'ogp-plus' ); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo esc_url( $donate ); ?>')"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></button>
		</div>

		<?php
	}

	/** ==================================================
	 * Update wp_options table.
	 *
	 * @since 1.00
	 */
	private function options_updated() {

		if ( isset( $_POST['Manageset'] ) && ! empty( $_POST['Manageset'] ) ) {
			if ( check_admin_referer( 'ogp_set', 'ogpplus_set' ) ) {
				if ( isset( $_POST['excerpt'] ) ) {
					$ogpplus_settings['excerpt'] = intval( $_POST['excerpt'] );
				}
				if ( isset( $_POST['df_img_id'] ) && ! empty( $_POST['df_img_id'] ) ) {
					$ogpplus_settings['df_img_id'] = intval( $_POST['df_img_id'] );
				} else {
					$ogpplus_settings['df_img_id'] = null;
				}
				if ( isset( $_POST['tw_user_name'] ) && ! empty( $_POST['tw_user_name'] ) ) {
					$ogpplus_settings['tw_user_name'] = sanitize_text_field( wp_unslash( $_POST['tw_user_name'] ) );
				} else {
					$ogpplus_settings['tw_user_name'] = null;
				}
				if ( isset( $_POST['fb_app_id'] ) && ! empty( $_POST['fb_app_id'] ) ) {
					$ogpplus_settings['fb_app_id'] = sanitize_text_field( wp_unslash( $_POST['fb_app_id'] ) );
				} else {
					$ogpplus_settings['fb_app_id'] = null;
				}
				update_option( 'ogpplus_settings', $ogpplus_settings );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'Settings' ) . ' --> ' . esc_html__( 'Settings saved.' ) . '</li></ul></div>';
			}
		}

		if ( isset( $_POST['Defaultset'] ) && ! empty( $_POST['Defaultset'] ) ) {
			if ( check_admin_referer( 'ogp_set', 'ogpplus_set' ) ) {
				delete_option( 'ogpplus_settings' );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'Settings' ) . ' --> ' . esc_html__( 'Default' ) . '</li></ul></div>';
			}
		}
	}
}


