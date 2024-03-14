<?php
/**
 * Simple Ticker
 *
 * @package    SimpleTicker
 * @subpackage SimpleTicker Management screen
/*
	Copyright (c) 2016- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
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

$simpletickeradmin = new SimpleTickerAdmin();

/** ==================================================
 * Management screen
 */
class SimpleTickerAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.06
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_settings' ) );

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
			$this_plugin = 'simple-ticker/simpleticker.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=SimpleTicker' ) . '">' . __( 'Settings' ) . '</a>';
		}
			return $links;
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_menu() {
		add_options_page( 'Simple Ticker Options', 'Simple Ticker', 'manage_options', 'SimpleTicker', array( $this, 'plugin_options' ) );
	}

	/** ==================================================
	 * Add Css and Script
	 *
	 * @since 1.00
	 */
	public function load_custom_wp_admin_style() {
		if ( $this->is_my_plugin_screen() ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'colorpicker-admin-js', plugin_dir_url( __DIR__ ) . 'js/jquery.colorpicker.admin.js', array( 'wp-color-picker' ), '1.0.0', false );
		}
	}

	/** ==================================================
	 * For only admin style
	 *
	 * @since 1.0
	 */
	private function is_my_plugin_screen() {
		$screen = get_current_screen();
		if ( is_object( $screen ) && 'settings_page_SimpleTicker' == $screen->id ) {
			return true;
		} else {
			return false;
		}
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.0
	 */
	public function plugin_options() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$this->options_updated();

		$scriptname = admin_url( 'options-general.php?page=SimpleTicker' );

		$simpleticker_option = get_option( 'simple_ticker' );

		?>

		<div class="wrap">
		<h2>Simple Ticker</h2>

			<details>
			<summary><strong><?php esc_html_e( 'Various links of this plugin', 'simple-ticker' ); ?></strong></summary>
			<?php $this->credit(); ?>
			</details>

			<details style="margin-bottom: 5px;">
			<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'How to use', 'simple-ticker' ); ?></strong></summary>
				<h3><?php esc_html_e( 'Set the widget', 'simple-ticker' ); ?></h3>
				<?php
				$widget_html = '<a href="' . admin_url( 'widgets.php' ) . '" style="text-decoration: none; word-break: break-all;">' . __( 'Widgets' ) . '[' . __( 'Ticker', 'simple-ticker' ) . ']</a>';
				?>
				<div style="padding: 5px 20px; font-weight: bold;">
				<?php
				/* translators: Widget html */
				echo wp_kses_post( sprintf( __( 'Please set the %1$s.', 'simple-ticker' ), $widget_html ) );
				?>
				</div>

				<hr>

				<h3><?php esc_html_e( 'Set up a shortcode', 'simple-ticker' ); ?></h3>

				<div style="padding: 5px 20px; font-weight: bold;"><?php esc_html_e( 'Example', 'simple-ticker' ); ?></div>
				<div style="padding: 5px 25px;"><?php esc_html_e( 'to the post or pages', 'simple-ticker' ); ?></div>
				<div style="padding: 5px 35px;"><code>[simpleticker]</code></div>
				<div style="padding: 5px 35px;"><code>[simpleticker ticker1_text="Ticker test!!" ticker1_color="#008000" sticky_posts_display=FALSE]</code></div>
				<div style="padding: 5px 25px;"><?php esc_html_e( 'to the template of the theme', 'simple-ticker' ); ?></div>
				<div style="padding: 5px 35px;"><code>&lt;?php echo do_shortcode('[simpleticker]'); ?&gt</code></div>
				<div style="padding: 5px 35px;"><code>&lt;?php echo do_shortcode('[simpleticker ticker1_text="Ticker test!!" ticker1_color="#008000" sticky_posts_display=FALSE]'); ?&gt</code></div>

				<div style="padding: 5px 20px; font-weight: bold;"><?php esc_html_e( 'Description of each attribute', 'simple-ticker' ); ?></div>

				<div style="padding: 5px 35px;"><?php esc_html_e( 'Text of ticker', 'simple-ticker' ); ?> : <code>ticker1_text</code> <code>ticker2_text</code> <code>ticker3_text</code></div>
				<div style="padding: 5px 35px;"><?php esc_html_e( 'Color of ticker', 'simple-ticker' ); ?> : <code>ticker1_color</code> <code>ticker2_color</code> <code>ticker3_color</code></div>
				<div style="padding: 5px 35px;"><?php esc_html_e( 'Boolean value of sticky_posts', 'simple-ticker' ); ?> : <code>sticky_posts_display</code></div>
				<div style="padding: 5px 35px;"><?php esc_html_e( 'Title color of sticky_posts', 'simple-ticker' ); ?> : <code>sticky_posts_title_color</code></div>
				<div style="padding: 5px 35px;"><?php esc_html_e( 'Content color of sticky_posts', 'simple-ticker' ); ?> : <code>sticky_posts_content_color</code></div>
				<div style="padding: 5px 35px;"><?php esc_html_e( 'Boolean value of WooCommerce sale', 'simple-ticker' ); ?> : <code>woo_sales_display</code></div>
				<div style="padding: 5px 35px;"><?php esc_html_e( 'Color of WooCommerce sale', 'simple-ticker' ); ?> : <code>woo_sales_color</code></div>
				<div style="padding: 5px 20px; font-weight: bold;"><?php esc_html_e( 'Attribute value of short codes can also be specified in the "Settings". Attribute value of the short code takes precedence.', 'simple-ticker' ); ?></div>
			</details>

			<form method="post" action="<?php echo esc_url( $scriptname ); ?>">
			<?php wp_nonce_field( 'simpleticker_settings', 'simpleticker_tabs' ); ?>

			<details style="margin-bottom: 5px;">
			<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Speed adjust', 'simple-ticker' ); ?></strong></summary>
				<div style="display: block; padding:5px 5px;">
				<?php esc_html_e( 'Up', 'simple-ticker' ); ?><input type="range" style="vertical-align:middle;" step="1" min="20" max="280" name="simple_ticker_speed" value="<?php echo esc_attr( intval( $simpleticker_option['speed'] ) ); ?>" /><?php esc_html_e( 'Down', 'simple-ticker' ); ?>
				</div>
			</details>

			<details style="margin-bottom: 5px;">
			<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Insert without using block or shortcode', 'simple-ticker' ); ?></strong></summary>
				<div><strong><?php esc_html_e( 'Beginning', 'simple-ticker' ); ?></strong></div>
				<div style="display: block;padding:5px 20px">
				<input type="checkbox" name="insert_body_open" value="1" <?php checked( '1', $simpleticker_option['insert_body_open'] ); ?> />
				<?php esc_html_e( 'Beginning', 'simple-ticker' ); ?>
				</div>
				<div><strong><?php esc_html_e( 'Before and after post', 'simple-ticker' ); ?></strong></div>
				<div style="display: block;padding:5px 20px">
				<input type="radio" name="simple_ticker_insert" value="none" 
				<?php
				if ( 'none' === $simpleticker_option['insert'] ) {
					echo 'checked';}
				?>
				>
				<?php esc_html_e( 'none' ); ?>
				</div>
				<div style="display: block;padding:5px 20px">
				<input type="radio" name="simple_ticker_insert" value="before" 
				<?php
				if ( 'before' === $simpleticker_option['insert'] ) {
					echo 'checked';}
				?>
				>
				<?php esc_html_e( 'Before post', 'simple-ticker' ); ?>
				</div>
				<div style="display: block;padding:5px 20px">
				<input type="radio" name="simple_ticker_insert" value="after" 
				<?php
				if ( 'after' === $simpleticker_option['insert'] ) {
					echo 'checked';}
				?>
				>
				<?php esc_html_e( 'After post', 'simple-ticker' ); ?>
				</div>
				<div style="display: block;padding:5px 20px">
				<input type="radio" name="simple_ticker_insert" value="beforeafter" 
				<?php
				if ( 'beforeafter' === $simpleticker_option['insert'] ) {
					echo 'checked';}
				?>
				>
				<?php esc_html_e( 'Before and after post', 'simple-ticker' ); ?>
				</div>
			</details>

			<details style="margin-bottom: 5px;">
			<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Own Ticker', 'simple-ticker' ); ?></strong></summary>
				<div style="display: block; padding:5px 5px;">
					<div style="display: block; padding:5px 20px;">
						<?php esc_html_e( 'Ticker1', 'simple-ticker' ); ?> : 
						<div style="display: block; padding:5px 35px;">
							<div>
							<?php esc_html_e( 'Text', 'simple-ticker' ); ?> : 
							<textarea name="simpleticker_ticker1_text" rows="4" cols="40" style="vertical-align: top"><?php echo esc_textarea( $simpleticker_option['ticker1']['text'] ); ?></textarea>
							</div>
							<div>
							<?php esc_html_e( 'Color', 'simple-ticker' ); ?> : 
							<input type="text" class="wpcolor" name="simpleticker_ticker1_color" value="<?php echo esc_attr( $simpleticker_option['ticker1']['color'] ); ?>" size="10" />
							</div>
							<div>
							URL : 
							<input type="text" style="width: 100%" name="simpleticker_ticker1_url" value="<?php echo esc_attr( $simpleticker_option['ticker1']['url'] ); ?>" />
							</div>
						</div>
					</div>
					<div style="display: block; padding:5px 20px;">
						<?php esc_html_e( 'Ticker2', 'simple-ticker' ); ?> : 
						<div style="display: block; padding:5px 35px;">
							<div>
							<?php esc_html_e( 'Text', 'simple-ticker' ); ?> : 
							<textarea name="simpleticker_ticker2_text" rows="4" cols="40" style="vertical-align: top"><?php echo esc_textarea( $simpleticker_option['ticker2']['text'] ); ?></textarea>
							</div>
							<div>
							<?php esc_html_e( 'Color', 'simple-ticker' ); ?> : 
							<input type="text" class="wpcolor" name="simpleticker_ticker2_color" value="<?php echo esc_attr( $simpleticker_option['ticker2']['color'] ); ?>" size="10" />
							</div>
							<div>
							URL : 
							<input type="text" style="width: 100%;" name="simpleticker_ticker2_url" value="<?php echo esc_attr( $simpleticker_option['ticker2']['url'] ); ?>" />
							</div>
						</div>
					</div>
					<div style="display: block; padding:5px 20px;">
						<?php esc_html_e( 'Ticker3', 'simple-ticker' ); ?> : 
						<div style="display: block; padding:5px 35px;">
							<div>
							<?php esc_html_e( 'Text', 'simple-ticker' ); ?> : 
							<textarea name="simpleticker_ticker3_text" rows="4" cols="40" style="vertical-align: top"><?php echo esc_textarea( $simpleticker_option['ticker3']['text'] ); ?></textarea>
							</div>
							<div>
							<?php esc_html_e( 'Color', 'simple-ticker' ); ?> : 
							<input type="text" class="wpcolor" name="simpleticker_ticker3_color" value="<?php echo esc_attr( $simpleticker_option['ticker3']['color'] ); ?>" size="10" />
							</div>
							<div>
							URL : 
							<input type="text" style="width: 100%;" name="simpleticker_ticker3_url" value="<?php echo esc_attr( $simpleticker_option['ticker3']['url'] ); ?>" />
							</div>
						</div>
					</div>
				</div>
			</details>

			<details style="margin-bottom: 5px;">
			<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Sticky Posts', 'simple-ticker' ); ?></strong></summary>
				<div style="display: block; padding:5px 5px;">
					<div style="display: block; padding:5px 20px;">
						<input type="checkbox" name="simpleticker_sticky_posts" value="1" <?php checked( '1', $simpleticker_option['sticky_posts']['display'] ); ?> />
						<?php esc_html_e( 'Include sticky_posts', 'simple-ticker' ); ?>
					</div>
					<div style="display: block; padding:5px 35px;">
						<?php esc_html_e( 'Title color', 'simple-ticker' ); ?> : 
						<input type="text" class="wpcolor" name="simpleticker_sticky_posts_titlecolor" value="<?php echo esc_attr( $simpleticker_option['sticky_posts']['title_color'] ); ?>" size="10" />
					</div>
					<div style="display: block; padding:5px 35px;">
						<?php esc_html_e( 'Content color', 'simple-ticker' ); ?> : 
						<input type="text" class="wpcolor" name="simpleticker_sticky_posts_contentcolor" value="<?php echo esc_attr( $simpleticker_option['sticky_posts']['content_color'] ); ?>" size="10" />
					</div>
				</div>
			</details>

			<details style="margin-bottom: 5px;">
			<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'WooCommerce Sales', 'simple-ticker' ); ?></strong></summary>
				<div style="display: block; padding:5px 5px;">
					<div style="display: block; padding:5px 20px;">
						<input type="checkbox" name="simpleticker_woo_sales" value="1" <?php checked( '1', $simpleticker_option['woo_sales']['display'] ); ?> />
						<?php esc_html_e( 'Include WooCommerce Sales', 'simple-ticker' ); ?>
					</div>
					<div style="display: block; padding:5px 35px;">
						<?php esc_html_e( 'Color', 'simple-ticker' ); ?> : 
						<input type="text" class="wpcolor" name="simpleticker_woo_sales_color" value="<?php echo esc_attr( $simpleticker_option['woo_sales']['color'] ); ?>" size="10" />
					</div>
					<div style="display: block; padding:5px 35px;">
						<div><?php esc_html_e( 'WooCommerce Sales', 'simple-ticker' ); ?> <?php esc_html_e( 'Tags' ); ?></div>
						<div style="display: block; padding:5px 45px;">
							<li>
							[<b>%regular_price%</b> : <?php esc_html_e( 'List price', 'simple-ticker' ); ?>]
							[<b>%sale_price%</b> : <?php esc_html_e( 'Sale price', 'simple-ticker' ); ?>]
							[<b>%dis_rate_price%</b> : <?php esc_html_e( 'Discount price', 'simple-ticker' ); ?>]
							[<b>%dis_rate_percent%</b> : <?php esc_html_e( 'Discount rate', 'simple-ticker' ); ?>]
							[<b>%to_date%</b> : <?php esc_html_e( 'Last day of sale', 'simple-ticker' ); ?>]
							[<b>%interval_day%</b> : <?php esc_html_e( 'Remaining number of selling days', 'simple-ticker' ); ?>]
							[<b>%interval_time%</b> : <?php esc_html_e( 'Remaining sales time', 'simple-ticker' ); ?>]
							</li>
							<li><b><?php esc_html_e( 'Please write any letters between tags. You can move and delete tags freely.', 'simple-ticker' ); ?></b></li>
							<div>
							<textarea name="simpleticker_woo_sales_text" style="width: 100%;"><?php echo esc_textarea( $simpleticker_option['woo_sales']['text'] ); ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</details>

			<div class="submit">
				<?php submit_button( __( 'Save Changes' ), 'large', 'SaveSimpt', false ); ?>
				<?php submit_button( __( 'Default' ), 'large', 'Default', false ); ?>
			</div>

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
		$faq       = sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'simple-ticker' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = __( 'https://shop.riverforest-wp.info/donate/', 'simple-ticker' );

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
		<h3><?php esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'simple-ticker' ); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo esc_url( $donate ); ?>')"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></button>
		</div>

		<?php
	}

	/** ==================================================
	 * Settings register
	 *
	 * @since 1.00
	 */
	public function register_settings() {

		$woo_sales_text_tag = '%regular_price% %sale_price% %dis_rate_price% %dis_rate_percent% %to_date% %interval_day% %interval_time%';
		$simple_ticker_tbl = array(
			'speed' => 150,
			'insert_body_open' => false,
			'insert' => 'none',
			'ticker1' => array(
				'text' => '',
				'url' => '',
				'color' => '#ff0000',
			),
			'ticker2' => array(
				'text' => '',
				'url' => '',
				'color' => '#ffff00',
			),
			'ticker3' => array(
				'text' => '',
				'url' => '',
				'color' => '#008000',
			),
			'sticky_posts' => array(
				'display' => true,
				'title_color' => '#ff0000',
				'content_color' => '#000000',
			),
			'woo_sales' => array(
				'display' => false,
				'color' => '#000000',
				'text' => $woo_sales_text_tag,
			),
		);

		if ( get_option( 'simple_ticker' ) ) {
			$simple_ticker_settings = get_option( 'simple_ticker' );
			if ( ! array_key_exists( 'speed', $simple_ticker_settings ) ) {
				/* ver 2.00 later */
				$simple_ticker_settings['speed'] = 150;
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			if ( ! array_key_exists( 'insert', $simple_ticker_settings ) ) {
				/* ver 2.04 later */
				$simple_ticker_settings['insert'] = 'none';
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			if ( ! array_key_exists( 'insert_body_open', $simple_ticker_settings ) ) {
				/* ver 3.00 later */
				$simple_ticker_settings['insert_body_open'] = false;
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			if ( ! array_key_exists( 'woo_sales', $simple_ticker_settings ) ) {
				/* ver 2.00 later */
				$simple_ticker_settings['woo_sales']['display'] = false;
				$simple_ticker_settings['woo_sales']['color'] = '#000000';
				$simple_ticker_settings['woo_sales']['text'] = $woo_sales_text_tag;
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			if ( ! array_key_exists( 'url', $simple_ticker_settings['ticker1'] ) ) {
				/* ver 3.01 later */
				$simple_ticker_settings['ticker1']['url'] = null;
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			if ( ! array_key_exists( 'url', $simple_ticker_settings['ticker2'] ) ) {
				/* ver 3.01 later */
				$simple_ticker_settings['ticker2']['url'] = null;
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			if ( ! array_key_exists( 'url', $simple_ticker_settings['ticker3'] ) ) {
				/* ver 3.01 later */
				$simple_ticker_settings['ticker3']['url'] = null;
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			/* 3.02 later */
			if ( strpos( $simple_ticker_settings['ticker1']['color'], '#' ) === false ) {
				$simple_ticker_settings['ticker1']['color'] = '#' . $simple_ticker_settings['ticker1']['color'];
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			if ( strpos( $simple_ticker_settings['ticker2']['color'], '#' ) === false ) {
				$simple_ticker_settings['ticker2']['color'] = '#' . $simple_ticker_settings['ticker2']['color'];
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			if ( strpos( $simple_ticker_settings['ticker3']['color'], '#' ) === false ) {
				$simple_ticker_settings['ticker3']['color'] = '#' . $simple_ticker_settings['ticker3']['color'];
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			if ( strpos( $simple_ticker_settings['sticky_posts']['title_color'], '#' ) === false ) {
				$simple_ticker_settings['sticky_posts']['title_color'] = '#' . $simple_ticker_settings['sticky_posts']['title_color'];
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			if ( strpos( $simple_ticker_settings['sticky_posts']['content_color'], '#' ) === false ) {
				$simple_ticker_settings['sticky_posts']['content_color'] = '#' . $simple_ticker_settings['sticky_posts']['content_color'];
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
			if ( strpos( $simple_ticker_settings['woo_sales']['color'], '#' ) === false ) {
				$simple_ticker_settings['woo_sales']['color'] = '#' . $simple_ticker_settings['woo_sales']['color'];
				update_option( 'simple_ticker', $simple_ticker_settings );
			}
		} else {
			update_option( 'simple_ticker', $simple_ticker_tbl );
		}
	}

	/** ==================================================
	 * Update wp_options table.
	 *
	 * @since 1.00
	 */
	private function options_updated() {

		if ( isset( $_POST['Default'] ) && ! empty( $_POST['Default'] ) ) {
			if ( check_admin_referer( 'simpleticker_settings', 'simpleticker_tabs' ) ) {
				$woo_sales_text_tag_def = '%regular_price% %sale_price% %dis_rate_price% %dis_rate_percent% %to_date% %interval_day% %interval_time%';
				$simple_ticker_reset_tbl = array(
					'speed' => 150,
					'insert_body_open' => false,
					'insert' => 'none',
					'ticker1' => array(
						'text' => '',
						'url' => '',
						'color' => '#ff0000',
					),
					'ticker2' => array(
						'text' => '',
						'url' => '',
						'color' => '#ffff00',
					),
					'ticker3' => array(
						'text' => '',
						'url' => '',
						'color' => '#008000',
					),
					'sticky_posts' => array(
						'display' => true,
						'title_color' => '#ff0000',
						'content_color' => '#000000',
					),
					'woo_sales' => array(
						'display' => false,
						'color' => '#000000',
						'text' => $woo_sales_text_tag_def,
					),
				);
				update_option( 'simple_ticker', $simple_ticker_reset_tbl );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( __( 'Settings' ) . ' --> ' . __( 'Default' ) ) . '</li></ul></div>';
			}
		}

		if ( isset( $_POST['SaveSimpt'] ) && ! empty( $_POST['SaveSimpt'] ) ) {
			if ( check_admin_referer( 'simpleticker_settings', 'simpleticker_tabs' ) ) {
				$simple_ticker_settings = get_option( 'simple_ticker' );
				if ( ! empty( $_POST['simple_ticker_speed'] ) ) {
					$simple_ticker_settings['speed'] = intval( $_POST['simple_ticker_speed'] );
				}
				if ( ! empty( $_POST['insert_body_open'] ) ) {
					$simple_ticker_settings['insert_body_open'] = true;
				} else {
					$simple_ticker_settings['insert_body_open'] = false;
				}
				if ( ! empty( $_POST['simple_ticker_insert'] ) ) {
					$simple_ticker_settings['insert'] = sanitize_text_field( wp_unslash( $_POST['simple_ticker_insert'] ) );
				}
				if ( ! empty( $_POST['simpleticker_ticker1_text'] ) ) {
					$simple_ticker_settings['ticker1']['text'] = apply_filters( 'smptck_html_text', sanitize_text_field( wp_unslash( $_POST['simpleticker_ticker1_text'] ) ) );
				} else {
					$simple_ticker_settings['ticker1']['text'] = null;
				}
				if ( ! empty( $_POST['simpleticker_ticker1_url'] ) ) {
					$simple_ticker_settings['ticker1']['url'] = esc_url_raw( wp_unslash( $_POST['simpleticker_ticker1_url'] ) );
				} else {
					$simple_ticker_settings['ticker1']['url'] = null;
				}
				if ( ! empty( $_POST['simpleticker_ticker1_color'] ) ) {
					$simple_ticker_settings['ticker1']['color'] = sanitize_text_field( wp_unslash( $_POST['simpleticker_ticker1_color'] ) );
				}
				if ( ! empty( $_POST['simpleticker_ticker2_text'] ) ) {
					$simple_ticker_settings['ticker2']['text'] = apply_filters( 'smptck_html_text', sanitize_text_field( wp_unslash( $_POST['simpleticker_ticker2_text'] ) ) );
				} else {
					$simple_ticker_settings['ticker2']['text'] = null;
				}
				if ( ! empty( $_POST['simpleticker_ticker2_url'] ) ) {
					$simple_ticker_settings['ticker2']['url'] = esc_url_raw( wp_unslash( $_POST['simpleticker_ticker2_url'] ) );
				} else {
					$simple_ticker_settings['ticker2']['url'] = null;
				}
				if ( ! empty( $_POST['simpleticker_ticker2_color'] ) ) {
					$simple_ticker_settings['ticker2']['color'] = sanitize_text_field( wp_unslash( $_POST['simpleticker_ticker2_color'] ) );
				}
				if ( ! empty( $_POST['simpleticker_ticker3_text'] ) ) {
					$simple_ticker_settings['ticker3']['text'] = apply_filters( 'smptck_html_text', sanitize_text_field( wp_unslash( $_POST['simpleticker_ticker3_text'] ) ) );
				} else {
					$simple_ticker_settings['ticker3']['text'] = null;
				}
				if ( ! empty( $_POST['simpleticker_ticker3_url'] ) ) {
					$simple_ticker_settings['ticker3']['url'] = esc_url_raw( wp_unslash( $_POST['simpleticker_ticker3_url'] ) );
				} else {
					$simple_ticker_settings['ticker3']['url'] = null;
				}
				if ( ! empty( $_POST['simpleticker_ticker3_color'] ) ) {
					$simple_ticker_settings['ticker3']['color'] = sanitize_text_field( wp_unslash( $_POST['simpleticker_ticker3_color'] ) );
				}
				if ( ! empty( $_POST['simpleticker_sticky_posts'] ) ) {
					$simple_ticker_settings['sticky_posts']['display'] = true;
				} else {
					$simple_ticker_settings['sticky_posts']['display'] = false;
				}
				if ( ! empty( $_POST['simpleticker_sticky_posts_titlecolor'] ) ) {
					$simple_ticker_settings['sticky_posts']['title_color'] = sanitize_text_field( wp_unslash( $_POST['simpleticker_sticky_posts_titlecolor'] ) );
				}
				if ( ! empty( $_POST['simpleticker_sticky_posts_contentcolor'] ) ) {
					$simple_ticker_settings['sticky_posts']['content_color'] = sanitize_text_field( wp_unslash( $_POST['simpleticker_sticky_posts_contentcolor'] ) );
				}
				if ( ! empty( $_POST['simpleticker_woo_sales'] ) ) {
					$simple_ticker_settings['woo_sales']['display'] = true;
				} else {
					$simple_ticker_settings['woo_sales']['display'] = false;
				}
				if ( ! empty( $_POST['simpleticker_woo_sales_color'] ) ) {
					$simple_ticker_settings['woo_sales']['color'] = sanitize_text_field( wp_unslash( $_POST['simpleticker_woo_sales_color'] ) );
				}
				if ( ! empty( $_POST['simpleticker_woo_sales_text'] ) ) {
					$simple_ticker_settings['woo_sales']['text'] = wp_strip_all_tags( wp_unslash( $_POST['simpleticker_woo_sales_text'] ) );
				}
				update_option( 'simple_ticker', $simple_ticker_settings );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'Settings' ) . ' --> ' . esc_html__( 'Changes saved.' ) . '</li></ul></div>';
			}
		}
	}
}


