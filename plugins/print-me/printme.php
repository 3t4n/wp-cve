<?php
/*
Plugin Name: Print me!
Plugin URI: http://wordpress.org/plugins/print-me/
Description: This plugin adds a print version
Version: 0.5.1
Author: Aleksandr Zelenin
Author URI: http://zelenin.me
Author Email: aleksandr@zelenin.me
License: Free
*/

class PrintMe
{
	const VERSION = '0.5.1';
	private $plugin_name = 'Print Me!';
	private $plugin_slug = 'print-me';
	private $options;

	public function __construct()
	{
		$this->options = get_option( $this->plugin_slug . '_options' );
		if ( empty( $this->options ) ) {
			$this->initOptions();
		}
		add_action( 'admin_menu', array( $this, 'adminMenu' ) );
		add_action( 'admin_init', array( $this, 'registerSettings' ) );
		add_shortcode( 'printme', array( $this, 'shortcode' ) );
		add_action( 'template_redirect', array( $this, 'getTemplate' ), 5 );
		if ( !$this->checkPrintTemplate() && $this->options['auto'] ) {
			add_filter( 'the_content', array( $this, 'addLink' ) );
		}
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		add_action( 'init', array( $this, 'loadLanguage' ) );
	}

	public function initOptions()
	{
		$this->options = array(
			'position' => '',
			'size' => 10,
			'margin_v' => 10,
			'margin_h' => 0,
			'print_text' => '<img style="width: 10px;height: 10px;" src="' . plugins_url( '/images/printme.png', __FILE__ ) . '" title="' . __( 'print', $this->plugin_slug ) . '">',
			'auto' => '',
			'head' => ''
		);
		add_option( $this->plugin_slug . '_options', $this->options );
	}

	public function adminMenu()
	{
		add_options_page( $this->plugin_name, $this->plugin_name, 'manage_options', $this->plugin_slug, array( $this, 'optionsPage' ) );
		add_filter( 'plugin_action_links', array( $this, 'optionsLinks' ), 10, 2 );
	}

	public function optionsLinks( $actions, $plugin_file )
	{
		if ( $plugin_file == plugin_basename( __FILE__ ) ) {
			$settings_link = '<a href="options-general.php?page=' . esc_attr( $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>';
			array_unshift( $actions, $settings_link );
		}
		return $actions;
	}

	public function registerSettings()
	{
		register_setting( $this->plugin_slug, $this->plugin_slug . '_options' );
	}

	public function shortcode()
	{
		$code = '';
		if ( is_singular() && !$this->checkPrintTemplate() ) {
			$this->registerScript();
			$link = add_query_arg( 'print', 1, get_permalink() );

			$text = !empty( $this->options['print_text'] ) ? $this->options['print_text'] : '<img style="width: ' . $this->options['size'] . 'px;height: ' . $this->options['size'] . 'px;" src="' . plugins_url( '/images/printme.png', __FILE__ ) . '" title="' . __( 'print', $this->plugin_slug ) . '">';
			$code = '<p style="margin: ' . $this->options['margin_v'] . 'px ' . $this->options['margin_h'] . 'px;" id="print-page-link"><a id="print-link" href="' . $link . '" rel="nofollow">' . $text . '</a></p>';
		}
		return $code;
	}

	public function addLink( $content )
	{
		if ( is_singular() && !$this->checkPrintTemplate() ) {
			$this->registerScript();
			$link = add_query_arg( 'print', 1, get_permalink() );

			$text = !empty( $this->options['print_text'] ) ? $this->options['print_text'] : '<img style="width: ' . $this->options['size'] . 'px;height: ' . $this->options['size'] . 'px;" src="' . plugins_url( '/images/printme.png', __FILE__ ) . '" title="' . __( 'print', $this->plugin_slug ) . '">';
			$code = '<p style="margin: ' . $this->options['margin_v'] . 'px ' . $this->options['margin_h'] . 'px;" id="print-page-link"><a id="print-link" href="' . $link . '" rel="nofollow">' . $text . '</a></p>';

			if ( !empty( $this->options['position'] ) ) {
				$content = $content . $code;
			} else {
				$content = $code . $content;
			}
		}
		return $content;
	}

	public function getTemplate()
	{
		if ( $this->checkPrintTemplate() ) {
			include( plugin_dir_path( __FILE__ ) . 'print.php' );
			exit();
		}
	}

	private function checkPrintTemplate()
	{
		return isset( $_GET['print'] ) && $_GET['print'] == 1;
	}

	private function registerScript()
	{
		wp_register_script( $this->plugin_slug, plugins_url( '/js/printme.js', __FILE__ ), array(), self::VERSION, true );
		wp_enqueue_script( $this->plugin_slug );
	}

	public function optionsPage()
	{
		?>
		<div class="wrap">
			<h2><?php echo esc_html( $this->plugin_name ); ?></h2>

			<form method="post" action="options.php">
				<?php settings_fields( $this->plugin_slug ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="<?php echo $this->plugin_slug; ?>_options[position]"><?php _e( 'Button below the content', $this->plugin_slug ); ?></label></th>
						<td>
							<input type="checkbox" name="<?php echo $this->plugin_slug; ?>_options[position]" id="<?php echo $this->plugin_slug; ?>_options[position]" <?php checked( $this->options['position'], 'on', true ); ?>>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="<?php echo $this->plugin_slug; ?>_options[size]"><?php _e( 'Width/height of icon', $this->plugin_slug ); ?></label></th>
						<td>
							<input type="text" class="regular-text code" value="<?php echo esc_attr( $this->options['size'] ); ?>" id="<?php echo $this->plugin_slug; ?>_options[size]" name="<?php echo $this->plugin_slug; ?>_options[size]">
							<span class="description"><?php _e( 'Insert width/height of icon', $this->plugin_slug ); ?> (<=64)</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="<?php echo $this->plugin_slug; ?>_options[margin_v]"><?php _e( 'Margin-top/margin-bottom', $this->plugin_slug ); ?></label></th>
						<td>
							<input type="text" class="regular-text code" value="<?php echo esc_attr( $this->options['margin_v'] ); ?>" id="<?php echo $this->plugin_slug; ?>_options[margin_v]" name="<?php echo $this->plugin_slug; ?>_options[margin_v]">
							<span class="description"><?php _e( 'Insert margin-top/margin-bottom of icon', $this->plugin_slug ); ?></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="<?php echo $this->plugin_slug; ?>_options[margin_h]"><?php _e( 'Margin-left/margin-right', $this->plugin_slug ); ?></label></th>
						<td>
							<input type="text" class="regular-text code" value="<?php echo esc_attr( $this->options['margin_h'] ); ?>" id="<?php echo $this->plugin_slug; ?>_options[margin_h]" name="<?php echo $this->plugin_slug; ?>_options[margin_h]">
							<span class="description"><?php _e( 'Insert margin-left/margin-right of icon', $this->plugin_slug ); ?></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="<?php echo $this->plugin_slug; ?>_options[print_text]"><?php _e( 'Text instead of icon', $this->plugin_slug ); ?></label></th>
						<td>
							<input type="text" class="regular-text code" value="<?php echo esc_attr( $this->options['print_text'] ); ?>" id="<?php echo $this->plugin_slug; ?>_options[print_text]" name="<?php echo $this->plugin_slug; ?>_options[print_text]">
							<span class="description"><?php _e( 'Type text', $this->plugin_slug ); ?></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="<?php echo $this->plugin_slug; ?>_options[auto]"><?php _e( 'Turn on icon/text', $this->plugin_slug ); ?></label>
						</th>
						<td>
							<input type="checkbox" name="<?php echo $this->plugin_slug; ?>_options[auto]" id="<?php echo $this->plugin_slug; ?>_options[auto]" <?php checked( $this->options['auto'], 'on', true ); ?>>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="<?php echo $this->plugin_slug; ?>_options[head]"><?php _e( 'Turn on', $this->plugin_slug ); ?> 'wp_head()'</label></th>
						<td>
							<input type="checkbox" name="<?php echo $this->plugin_slug; ?>_options[head]" id="<?php echo $this->plugin_slug; ?>_options[head]" <?php checked( $this->options['head'], 'on', true ); ?>>
							<span class="description"><?php _e( 'Turn on if you have problems with third-party plugins', $this->plugin_slug ); ?></span>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'Update', $this->plugin_slug ), 'primary', 'submit', 'true' ); ?>
			</form>
			<div class="updated settings-error" id="setting-error-settings_updated">
				<p><?php _e( 'Shortcode', $this->plugin_slug ); ?>: <strong>[printme]</strong></p>
				<p><?php _e( 'php function', $this->plugin_slug ); ?>: <strong>printme_link();</strong></p>
				<p><strong><?php _e( 'Donate me via PayPal', $this->plugin_slug ); ?>:</strong></p>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="LW23C9HMMDRSN">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
		</div>
	<?php
	}

	public function loadLanguage()
	{
		load_plugin_textdomain( $this->plugin_slug, false, plugin_basename( plugin_dir_path( __FILE__ ) ) . '/languages' );
	}

	public function activate( $network_wide )
	{
		$data = array(
			'plugin_name' => $this->plugin_name,
			'version' => self::VERSION,
			'url' => get_home_url(),
			'sitename' => get_option( 'blogname' )
		);
		wp_remote_get( 'http://zelenin.me/wp-tracker.php?' . http_build_query( $data ) );
	}
}

new PrintMe;

function printme_link()
{
	$print_me = new PrintMe;
	echo $print_me->shortcode();
}