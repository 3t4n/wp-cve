<?php

/**
 * The file that adds services to CF7.
 *
 * @link       http://wensolutions.com/
 * @since      1.0.0
 *
 * @package    Cf7_Gr_Ext
 * @subpackage Cf7_Gr_Ext/includes
 */

/**
 *
 * @since      1.0.0
 * @package    Cf7_Gr_Ext
 * @subpackage Cf7_Gr_Ext/includes
 * @author     WEN Solutions <info@wensolutions.com>
 */

// Check if 'WPCF7_Service' is active.
if( !class_exists( 'WPCF7_Service' ) ){
	return;
}

class Cf7_Gr_Ext_Add_Service extends WPCF7_Service{
	private static $instance;
	private $options;

	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		$this->options = get_option( 'cf7_gs_ext_basics_options' );
	}

	public function get_title(){
		return __( 'Get Response', 'cf7-gr-ext' );
	}
	public function is_active(){
		if ( empty( $this->options ) || ! isset( $this->options['gs_con'] ) ) {
			return false;
		}

		return true;
	}

	public function get_apikey() {
		if ( empty( $this->options ) || ! isset( $this->options['gs_key'] ) ) {
			return false;
		}

		return $this->options['gs_key'];

	}
	
	public function get_categories() {
		return array( 'newsletter' );
	}

	public function icon() {
		return '';
	}

	public function link() {
		echo sprintf( '<a href="%1$s" target="_blank">%2$s</a>',
			'http://getresponse.com',
			__( 'Get API Key', 'cf7-gr-ext' ) );
	}

	public function load( $action = '' ) {
		if ( 'setup' == $action ) {
			if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
				check_admin_referer( 'wpcf7-getresponse-setup' );

				$apikey = isset( $_POST['apikey'] ) ? trim( $_POST['apikey'] ) : '';
				$options = $this->options;

				if ( $apikey ) {
					$options['gs_key'] = $apikey;
					$getresponse = new GetResponse( $apikey );
					$account = $getresponse->accounts();
					if( isset( $account->accountId ) && '' != $account->accountId ){
						$campaigns = $getresponse->getCampaigns();
						$options['gs_camp'] = $campaigns;

						$custom_fields = $getresponse->getCustomFields();
						$options['gs_custom_fields'] = $custom_fields;

						$options['gs_con'] = 1;
						update_option( 'cf7_gs_ext_basics_options', $options );
						$redirect_to = $this->menu_page_url( array(
							'message' => 'success' ) );
					}
					else{
						$redirect_to = $this->menu_page_url( array(
							'action' => 'setup',
							'message' => 'invalid' ) );
					}

				} elseif ( '' === $sitekey ) {
					$options['gs_key'] = '';
					update_option( 'cf7_gs_ext_basics_options', $options );
					$redirect_to = $this->menu_page_url( array(
						'message' => 'empty' ) );
				} else {
					$redirect_to = $this->menu_page_url( array(
						'action' => 'setup',
						'message' => 'invalid' ) );
				}

				wp_safe_redirect( $redirect_to );
				exit();
			}
		}
	}

	private function menu_page_url( $args = '' ) {
		$args = wp_parse_args( $args, array() );

		$url = menu_page_url( 'wpcf7-integration', false );
		$url = add_query_arg( array( 'service' => 'getresponse' ), $url );

		if ( ! empty( $args) ) {
			$url = add_query_arg( $args, $url );
		}

		return $url;
	}

	public function display( $action = '' ) {
		if( !function_exists('curl_version') ){
			$class = 'cf7-gr-ext-notice cf7-gr-ext-notice-error';
			$message = __( 'PHP cURL needs to be active to enter API key.', 'cf7-gr-ext' );

			printf( '<div class="%1$s">%2$s</div>', $class, $message );
			return;
		}
		if( 'setup' == $action ){
			$this->display_setup();
			return;
		}

		if ( $this->is_active() ) {
			?>
			<p><?php echo esc_html( __( 'Current setup', 'cf7-gr-ext' ) ); ?></p>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php echo esc_html( __( 'API Key', 'cf7-gr-ext' ) ); ?></th>
						<td><?php echo $this->get_apikey(); ?></td>
					</tr>
				</tbody>
			</table>
			<p><a href="<?php echo esc_url( $this->menu_page_url( 'action=setup' ) ); ?>" class="button"><?php echo esc_html( __( "Reset Keys", 'cf7-gr-ext' ) ); ?></a></p>
			<?php
		}else{
			?>
			<p><?php echo esc_html( __( "To use GetResponse, you need to install an API key.", 'cf7-gr-ext' ) ); ?></p>

			<p><a href="<?php echo esc_url( $this->menu_page_url( 'action=setup' ) ); ?>" class="button"><?php echo esc_html( __( "Configure Keys", 'cf7-gr-ext' ) ); ?></a></p>

			<p><?php echo sprintf( esc_html( __( "For more details, see %s.", 'cf7-gr-ext' ) ), wpcf7_link( __( 'http://wensolutions.com/contact-form-getresponse-extension-setup/', 'cf7-gr-ext' ), __( 'Setup process', 'cf7-gr-ext' ) ) ); ?></p>
			<?php
		}
	}
	public function display_setup() {
		?>
		<form method="post" action="<?php echo esc_url( $this->menu_page_url( 'action=setup' ) ); ?>">
			<?php wp_nonce_field( 'wpcf7-getresponse-setup' ); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="apikey"><?php echo esc_html( __( 'API Key', 'cf7-gr-ext' ) ); ?></label></th>
						<td><input type="text" aria-required="true" value="" id="apikey" name="apikey" class="regular-text code" /></td>
					</tr>
				</tbody>
			</table>

			<p class="submit"><input type="submit" class="button button-primary" value="<?php echo esc_attr( __( 'Save', 'cf7-gr-ext' ) ); ?>" name="submit" /></p>
		</form>
		<?php
	}
	public function admin_notice( $message = '' ) {
		if ( 'invalid' == $message ) {
			echo sprintf(
				'<div class="error notice notice-error is-dismissible"><p><strong>%1$s</strong>: %2$s</p></div>',
				esc_html( __( "ERROR", 'cf7-gr-ext' ) ),
				esc_html( __( "Invalid key values.", 'cf7-gr-ext' ) ) );
		}

		if ( 'success' == $message ) {
			echo sprintf( '<div class="updated notice notice-success is-dismissible"><p>%s</p></div>',
				esc_html( __( 'Settings saved.', 'cf7-gr-ext' ) ) );
		}

		if ( 'empty' == $message ) {
			echo sprintf( '<div class="error notice notice-error is-dismissible"><p>%s</p></div>',
				esc_html( __( 'Empty settings saved.', 'cf7-gr-ext' ) ) );
		}
	}
}

add_action( 'wpcf7_init', 'wpcf7_gr_register_service' );

function wpcf7_gr_register_service() {
	$integration = WPCF7_Integration::get_instance();

	$categories = array(
		'newsletter' => __( 'Newsletter', 'cf7-gr-ext' ) );

	foreach ( $categories as $name => $category ) {
		$integration->add_category( $name, $category );
	}

	$services = array(
		'getresponse' => Cf7_Gr_Ext_Add_Service::get_instance() );

	foreach ( $services as $name => $service ) {
		$integration->add_service( $name, $service );
	}
}