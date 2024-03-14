<?php
/**
 * Primary Formstack File.
 *
 * @package Formstack
 * @author Formstack
 */

/**
 * Plugin Name: Formstack Plugin
 * Plugin URI: https://wordpress.org/extend/plugins/formstack
 * Description: Easily embed Formstack forms into your blog or WP pages.
 * Version: 2.0.2
 * Author: Formstack, LLC
 * Author URI: https://www.formstack.com
 * Text Domain: formstack
 * Domain Path: /languages
 * License: GPLv3
 */

/**
 * This file is part of Formstack's WordPress Plugin.
 *
 * Formstack's WordPress Plugin is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * Formstack's WordPress Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Class Formstack_Plugin
 */
class Formstack_Plugin {

	/**
	 * URL of plugin directory.
	 *
	 * @var string
	 * @since 1.0.11
	 */
	protected $url = '';

	/**
	 * Path of plugin directory.
	 *
	 * @var string
	 * @since 1.0.11
	 */
	protected $path = '';

	/**
	 * Plugin basename.
	 *
	 * @var string
	 * @since 1.0.11
	 */
	protected $basename = '';

	/**
	 * Available shortcodes.
	 *
	 * @var array
	 * @since 1.0.11
	 */
	private $shortcodes = array();

	/**
	 * Formstack_Plugin constructor.
	 *
	 * @since unknown.
	 */
	public function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );

		$this->shortcodes[] = 'Formstack';
		$this->shortcodes[] = 'formstack';
		$this->shortcodes[] = 'fs';
	}

	/**
	 * Queue up our hooks and shortcodes.
	 *
	 * @since 1.0.11
	 */
	public function hooks() {
		add_action( 'plugins_loaded', array( $this, 'includes' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_init', array( $this, 'settings_registration' ) );
		add_action( 'admin_init', array( $this, 'clear_caches' ) );
		add_action( 'init', array( $this, 'maybe_save_authentication_code' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );

		// Allows for multiple variations with the same results.
		foreach ( $this->shortcodes as $shortcode ) {
			add_shortcode( $shortcode, array( $this, 'render_formstack_shortcode' ) );
		}
	}

	/**
	 * Load our appropriate files.
	 *
	 * @since 1.0.11
	 */
	public function includes() {
		require_once $this->path . 'widget.php';
		require_once $this->path . 'api.php';
		require_once $this->path . 'apiv2.php';
		require_once $this->path . 'helpers.php';
	}

	/**
	 * Register our styles.
	 *
	 * @since 1.0.11
	 */
	public function styles() {
		wp_enqueue_style( 'formstack-css', '//www.formstack.com/forms/css/2/wordpress-post.css' );
	}

	/**
	 * Register our admin styles.
	 *
	 * @since 1.0.11
	 * @since 1.0.13 Added hook suffix parameter.
	 *
	 * @param string $hook_suffix Current page suffix.
	 */
	public function admin_styles( $hook_suffix ) {
		wp_enqueue_style( 'formstack-admin', $this->url . 'assets/formstack-admin.css' );
		wp_enqueue_script( 'formstack-admin', $this->url . 'assets/formstack-admin.js', array( 'jquery' ), '', true );
		if ( 'post.php' === $hook_suffix || 'post-new.php' === $hook_suffix ) {
			wp_localize_script( 'formstack-admin', 'formstack_forms', $this->get_forms() );
			wp_localize_script( 'formstack-admin', 'formstack_tinymce', array(
				'button'        => esc_html__( 'Formstack', 'formstack' ),
				'list_label'    => esc_html__( 'Choose a form to embed:', 'formstack' ),
				'tinymce_title' => esc_html__( 'Embed a Formstack form', 'formstack' ),
				'nojquery'      => esc_html__( "I don't need jQuery", 'formstack' ),
				'nojqueryui'    => esc_html__( "I don't need jQuery UI", 'formstack' ),
				'nomodernizr'   => esc_html__( "I don't need Modernizr", 'formstack' ),
				'nocss'         => esc_html__( 'Use bare-bones CSS', 'formstack' ),
				'nocssstrict'   => esc_html__( 'Use no CSS', 'formstack' ),
				'clear_cache'   =>
				sprintf(
					esc_html__( 'Not seeing a form? %s', 'formstack' ),
					sprintf(
						'<a href="%s">%s</a>',
						add_query_arg( array( 'clear_formstack_cache' => 'true' ) ),
						esc_html__( 'Click to refresh and clear form cache', 'formstack' )
					)
				)
			) );
		}
	}

	/**
	 * Queue up some TinyMCE utilities as needed.
	 *
	 * @since unknown
	 */
	public function admin_init() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( 'true' === get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_buttons', array( $this, 'mce_buttons' ) );
			add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ) );
		}
	}

	/**
	 * Set up our settings page.
	 *
	 * @since 1.0.11
	 */
	public function settings_registration() {
		register_setting( 'formstack_plugin', 'formstack_settings', array( $this, 'formstack_plugin_validate' ) );
		register_setting( 'formstack_plugin', 'formstack_client_id', array( $this, 'formstack_plugin_validate' ) );
		register_setting( 'formstack_plugin', 'formstack_client_secret', array( $this, 'formstack_plugin_validate' ) );
		$settings = get_option( 'formstack_settings', '' );
		$client_id     = ( isset( $settings['client_id'] ) ) ? $settings['client_id'] : '';
		$client_secret = ( isset( $settings['client_secret'] ) ) ? $settings['client_secret'] : '';

		add_settings_section(
			'formstack_plugin_settings',
			esc_html__( 'Formstack', 'formstack' ),
			array( $this, 'formstack_do_section' ),
			'formstack_plugin_do_options'
		);

		add_settings_field(
			'formstack_client_id',
			'<label for="formstack_client_id">' . esc_html__( 'Client ID', 'formstack' ) . '</label>',
			array( $this, 'formstack_plugin_input_fields' ),
			'formstack_plugin_do_options',
			'formstack_plugin_settings',
			array(
				'class' => 'regular-text',
				'id'    => 'formstack_client_id',
				'type'  => 'text',
				'name'  => 'formstack_settings[client_id]',
				'value' => $client_id,
			)
		);

		add_settings_field(
			'formstack_client_secret',
			'<label for="formstack_client_secret">' . esc_html__( 'Client Secret', 'formstack' ) . '</label>',
			array( $this, 'formstack_plugin_input_fields' ),
			'formstack_plugin_do_options',
			'formstack_plugin_settings',
			array(
				'class' => 'regular-text',
				'id'    => 'formstack_client_secret',
				'type'  => 'text',
				'name'  => 'formstack_settings[client_secret]',
				'value' => $client_secret,
			)
		);
	}

	/**
	 * Helper method for displaying our options page.
	 *
	 * @since 1.0.11
	 */
	public function formstack_do_section() {
		?>
		<p>
			<?php
			_e( 'The <a href="https://www.formstack.com" target="_blank">Formstack</a> Plugin uses the Formstack API to get a listing of your forms. An API key is required for this plugin to work.', 'formstack' );
			?>
		</p>
		<p>
			<?php
			_e( 'Need help getting a Formstack application set up? Please visit <a href="https://support.formstack.com/customer/portal/articles/2836361" target="_blank">Formstack WordPress plugin application setup</a> for more information and steps.', 'formstack' );
			?>
		</p>
		<?php
	}

	/**
	 * Helper method to display inputs for settings page.
	 *
	 * @since 1.0.11
	 *
	 * @param array $args Array of arguments for method.
	 */
	public function formstack_plugin_input_fields( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'class'       => null,
			'id'          => null,
			'type'        => null,
			'name'        => null,
			'value'       => '',
			'description' => null,
		) );
		switch ( $args['type'] ) {
			case 'text':
				echo '<input type="' . esc_attr( $args['type'] ) . '" class="' . esc_attr( $args['class'] ) . '" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '" placeholder="' . esc_attr( $args['description'] ) . '" value="' . esc_attr( $args['value'] ) . '" />';
				break;
			default:
				echo '<input type="text" class="' . esc_attr( $args['class'] ) . '" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '" placeholder="' . esc_attr( $args['description'] ) . '" value="' . esc_attr( $args['value'] ) . '" />';
		}
	}

	/**
	 * Helper method for sanitization of our options.
	 *
	 * @since 1.0.11
	 *
	 * @param string $input API key.
	 * @return string
	 */
	function formstack_plugin_validate( $input = '' ) {
		foreach( $input as $name => $value ) {
			$new_input[ $name ] = sanitize_text_field( $value );
		}
		return $new_input;
	}

	/**
	 * Set up our admin menu.
	 *
	 * @since unknown
	 */
	public function admin_menu() {
		add_menu_page(
			esc_html__( 'Formstack Forms', 'formstack' ),
			esc_html__( 'Formstack', 'formstack' ),
			'manage_options',
			'Formstack',
			array( $this, 'plugin_options' ),
			$this->url . 'stack.gif'
		);
	}

	/**
	 * Renders the Formstack shortcodes: [Formstack], [formstack], and [fs].
	 * This function expects `id` and `viewkey` to be present in the shortcode
	 * attributes. The shotcode is replaced with Formstack's standard JavaScript
	 * Embed and a WorPress optimized stylesheet.
	 *
	 * @param array  $atts    The shortcode attributes.
	 * @param string $content The Shortcode inner content.
	 * @param string $code    The shortcode name.
	 * @return mixed
	 */
	public function render_formstack_shortcode( $atts, $content = null, $code = '' ) {

		if ( empty( $atts['id'] ) || empty( $atts['viewkey'] ) || is_feed() ) {
			return '';
		}
		$atts = wp_parse_args( $atts, array(
			'id'          => '',
			'viewkey'     => '',
			'nojquery'    => 'false',
			'nojqueryui'  => 'false',
			'nomodernizr' => 'false',
			'no_style'       => 'false',
			'no_style_strict' => 'false',
		) );
		// An ad request. Displays after the actual form.
		$wp = wp_remote_get( "https://www.formstack.com/forms/wp-ad.php?form={$atts['id']}" );

		$extras = formstack_get_extra_url_params( $atts );

		$script_url = add_query_arg( $extras, "https://www.formstack.com/forms/js.php?{$atts['id']}-{$atts['viewkey']}" );
		$noscript_url = add_query_arg( $extras, "https://www.formstack.com/forms/?{$atts['id']}-{$atts['viewkey']}" );

		ob_start();
		?>
		<script type="text/javascript" src="<?php echo esc_url( $script_url ); ?>"></script>
		<noscript>
			<a href="<?php echo esc_url( $noscript_url ); ?>" title="<?php esc_attr_e( 'Online Form', 'formstack' ); ?>">
				<?php esc_html_e( 'Online Form', 'formstack' ); ?>
			</a>
		</noscript>
		<?php
		echo wp_kses_post( wp_remote_retrieve_body( $wp ) );

		return ob_get_clean();
	}

	/**
	 * Adds Formstack Embed buttons to TinyMCE.
	 *
	 * @param array $buttons Array of TinyMCE buttons.
	 * @return array
	 */
	public function mce_buttons( $buttons ) {
		array_push( $buttons, '|', 'formstack' );
		return $buttons;
	}

	/**
	 * Loads the external Formstack TinyMCE plugin.
	 *
	 * @param array $plugins Array of plugins.
	 * @return array
	 */
	public function mce_external_plugins( $plugins ) {
		$plugins['formstack'] = $this->url . 'tinymce/plugin.js';
		return $plugins;
	}

	/**
	 * Render the Formstack settings page
	 */
	public function plugin_options() {
		include 'tmpl/options.php';
	}

	/**
	 * Fetch out forms from the Formstack API and add to array with name, ID, and viewkey.
	 *
	 * @since 1.0.11
	 *
	 * @return array
	 */
	public function get_forms() {
		$settings      = get_option( 'formstack_settings', '' );
		$client_id     = ( isset( $settings['client_id'] ) ) ? $settings['client_id'] : '';
		$client_secret = ( isset( $settings['client_secret'] ) ) ? $settings['client_secret'] : '';
		$oauth_code    = get_option( 'formstack_oauth2_code', '' );

		if ( $client_id && $client_secret && $oauth_code ) {
			$formstack_api = new Formstack_API_V2(
				array(
					'client_id'     => $client_id,
			        'client_secret' => $client_secret,
			        'redirect_uri'  => admin_url( 'admin.php?page=Formstack' ),
			        'code'          => $oauth_code
			) );
		}

		if ( empty( $client_id ) || empty( $client_secret ) ) {
			return array(
				array(
					'name' => 'no_app',
					'value' => esc_attr__( 'No app set up. Please provide app client details on the Formstack settings page.', 'formstack' ),
				),
			);
		}

		// May not ultimately be returned. Depends on API response.
		$no_forms = array(
			array(
				'name' => 'no_forms',
				'value' => sprintf(
					// translators: Placeholder will hold <a> parts and a link to Formstack.com.
					esc_attr__( 'No found forms or no forms created at the moment. Please %s authenticate site %s or add some at %s', 'formstack' ),
					sprintf(
						'<a href="%s">',
						admin_url( 'admin.php?page=Formstack' )
					),
					'</a>',
					'<a href="https://www.formstack.com" target="_blank">https://www.formstack.com</a>'
				),
			),
		);
		if ( ! isset( $formstack_api ) || '0' == $formstack_api->get_form_count() ) {
			return $no_forms;
		}

		$forms_list = array();
		$forms_response = $formstack_api->get_forms();
		if ( ! empty( $forms_response ) && is_array( $forms_response['forms'] ) ) {
			foreach ( $forms_response['forms'] as $form ) {
				$forms_list[] = array(
					'name'  => $form->name,
					'value' => $form->id . '-' . $form->viewkey,
				);
			}
		}

		return $forms_list;
	}

	/**
	 * Maybe clear caches for various options, if user requested.
	 *
	 * @since 2.0.0
	 */
	public function clear_caches() {
		if ( isset( $_GET['clear_formstack_cache'] ) && 'true' === $_GET['clear_formstack_cache'] ) {
			delete_option( 'formstack_forms' );
			delete_option( 'formstack_form_count' );
			$formstack = new Formstack_API_V2();
			$formstack->get_forms();
		}
		if ( isset( $_GET['clear_formstack_tokens'] ) && 'true' === $_GET['clear_formstack_tokens'] ) {
			delete_option( 'formstack_oauth2_code' );
			delete_option( 'formstack_access_token' );
			delete_option( 'formstack_refresh_token' );
			delete_option( 'formstack_token_expiration' );
		}
	}

	/**
	 * Save our authorization code from Formstack API
	 *
	 * @since 2.0.0
	 */
	public function maybe_save_authentication_code() {
		if ( isset( $_GET['code'] ) ) {
			update_option( 'formstack_oauth2_code', sanitize_text_field( $_GET['code'] ) );
		}
	}
}

$formstack = new Formstack_Plugin;
$formstack->hooks();
