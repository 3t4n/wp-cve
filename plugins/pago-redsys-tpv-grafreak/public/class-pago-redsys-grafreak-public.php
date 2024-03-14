<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link  http://www.grafreak.net
 * @since 1.0.0
 *
 * @package    Pago_Redsys_Grafreak
 * @subpackage Pago_Redsys_Grafreak/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Pago_Redsys_Grafreak_Public
 */
class Pago_Redsys_Grafreak_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The options name to be used in this plugin
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string      $option_name    Option name of this plugin
	 */
	private $option_name = 'pago_redsys_grafreak';

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pago-redsys-grafreak-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pago-redsys-grafreak-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	/**
	 * Create random code for not repeat transactions
	 *
	 * @since 1.0.0
	 * @param int $longitud how many numbers.
	 */
	private function generar_codigo( $longitud ) {
		$pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
		$key = substr(str_shuffle($pattern), 0, $longitud);
		return $key;
	}


	/**
	 * Resolve form via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function pago_tpv_ajax() {
		check_ajax_referer( 'tpv_submit', 'noncesecure' );
		// Se crea Objeto.
		$mi_obj = new RedsysAPI_Grafreak();

		// Valores de entrada.
		$fuc      = get_option( $this->option_name . '_idfuc' );
		$terminal = get_option( $this->option_name . '_terminal' );
		$moneda   = get_option( $this->option_name . '_moneda' );
		$trans    = 0;

		if ( ! isset( $_REQUEST['url_ko'] ) ) {
			$url_ko = get_option( $this->option_name . '_urlko' );
			if ( ! $url_ko ) {
				$url_ko = ! empty( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';
			}
		} else {
			$url_ko = $_REQUEST['url_ko'];
		}
		if ( ! isset( $_REQUEST['url_ok'] ) ) {
			$url_ok = get_option( $this->option_name . '_urlok' );
			if ( ! $url_ok ) {
				$url_ok = ! empty( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';
			}
		} else {
			$url_ok = $_REQUEST['url_ok'];
		}
		// creamos un numero aleatorio para evitar que el mismo pedido tenga.
		$random = $this->generar_codigo( 3 );
		$amount = isset( $_REQUEST['c'] ) ? floatval( $_REQUEST['c'] ) : 0;
		$desc = isset( $_REQUEST['desc'] ) ? $_REQUEST['desc'] : "";

		$entornoact = get_option( $this->option_name . '_entornoact' );
		$id         = isset( $_REQUEST['np'] ) ? $random . '-' . sanitize_text_field( wp_unslash( $_REQUEST['np'] ) ) : $random . '-' . $amount;

		$form_tpv = get_option( $this->option_name . '_url' . $entornoact );

		// Se Rellenan los campos.
		$mi_obj->set_parameter( 'DS_MERCHANT_AMOUNT', (int) ( $amount * 100 ) );
		$mi_obj->set_parameter( 'DS_MERCHANT_ORDER', $id );
		$mi_obj->set_parameter( 'DS_MERCHANT_MERCHANTCODE', $fuc );
		$mi_obj->set_parameter( 'DS_MERCHANT_CURRENCY', $moneda );
		$mi_obj->set_parameter( 'DS_MERCHANT_TRANSACTIONTYPE', $trans );
		$mi_obj->set_parameter( 'DS_MERCHANT_TERMINAL', $terminal );
		$mi_obj->set_parameter( 'DS_MERCHANT_MERCHANTURL', $url );
		$mi_obj->set_parameter( 'DS_MERCHANT_URLOK', $url_ok );
		$mi_obj->set_parameter( 'DS_MERCHANT_URLKO', $url_ko );
		$mi_obj->set_parameter( 'DS_MERCHANT_MERCHANTNAME', get_option( $this->option_name . '_nombrecomercio' ) );
		$mi_obj->set_parameter( 'DS_MERCHANT_PRODUCTDESCRIPTION', $desc );
		// Datos de configuración.
		$version = 'HMAC_SHA256_V1';
		$kc      = get_option( $this->option_name . '_encriptkey' );
		// Se generan los parámetros de la petición.
		$request   = '';
		$params    = $mi_obj->create_merchant_parameters();
		$signature = $mi_obj->create_merchant_signature( $kc );

		echo '<form name="frm" id="form_tpv" action="' . esc_html( $form_tpv ) . '" method="POST">			
			<input type="hidden" name="Ds_SignatureVersion" id="inputDs_SignatureVersion" value="' . esc_html( $version ) . '"/>
			<input type="hidden" name="Ds_MerchantParameters" id="inputDs_MerchantParameters" value="' . esc_html( $params ) . '"/>
			<input type="hidden" name="Ds_Signature" id="inputDs_Signature" value="' . esc_html( $signature ) . '"/>
		</form>';
		die();
	}

	/**
	 * Create TPV via shortcode.
	 *
	 * @param array  $atts    available atts on shortocde.
	 * @param string $content content of shortcode.
	 * @since 1.0.0
	 */
	public function pago_tpv_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'url_ok' => '',
				'url_ko' => '',
				'np' => '',
				'desc' => '',
				'c' => '',
			),
			$atts
		);
		//var_dump($atts);
		// Se crea Objeto.
		$mi_obj = new RedsysAPI_Grafreak();
		$enable = get_option( $this->option_name . '_habilitado' );
		if ( 'enabled' === $enable ) {
			ob_start();
			include 'partials/pago-redsys-grafreak-public-display.php';
			$salida = ob_get_contents();
			ob_end_clean();
			return $salida;
		} else {
			return 'Plataforma deshabilitada';
		}
	}

	/**
	 * Create response from TPV OK.
	 *
	 * @param array  $atts    available atts on shortocde.
	 * @param string $content content of shortcode.
	 * @since 1.0.0
	 */
	public function pago_tpv_ok_shortcode( $atts, $content = null ) {
		// Se crea Objeto.
		$mi_obj = new RedsysAPI_Grafreak();
		$enable = get_option( $this->option_name . '_habilitado' );
		if ( isset( $_REQUEST['Ds_Signature'] ) && 'enabled' === $enable ) {
			$version = '';
			if ( isset( $_REQUEST['Ds_SignatureVersion'] ) ) {
				$version = esc_html( sanitize_text_field( wp_unslash( $_REQUEST['Ds_SignatureVersion'] ) ) );
			}
			$datos = '';
			if ( isset( $_REQUEST['Ds_MerchantParameters'] ) ) {
				$datos = esc_html( sanitize_text_field( wp_unslash( $_REQUEST['Ds_MerchantParameters'] ) ) );
			}
			$signaturerecibida = '';
			if ( isset( $_REQUEST['Ds_Signature'] ) ) {
				$signaturerecibida = esc_html( sanitize_text_field( wp_unslash( $_REQUEST['Ds_Signature'] ) ) );
			}

			$decodec = $mi_obj->decode_merchant_parameters( $datos );
			$kc      = get_option( $this->option_name . '_encriptkey' );
			$firma   = $mi_obj->create_merchant_signature_notif( $kc, $datos );
			$decodec = get_object_vars( json_decode( $decodec ) );

			if ( $firma === $signaturerecibida && intval( $decodec['Ds_Response'] ) < 100 ) {
				ob_start();
				echo esc_html( $content );?>
				<h2><?php esc_html_e( 'Payment complete', 'pago-redsys-grafreak' ); ?></h2>
					<table class="table-result-tpv">
						<tr>
							<td><?php esc_html_e( 'Date:', 'pago-redsys-grafreak' ); ?></td>
							<td><?php echo esc_html( urldecode( $decodec['Ds_Date'] . ' ' . $decodec['Ds_Hour'] ) ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Order code:', 'pago-redsys-grafreak' ); ?></td>
							<td><?php echo esc_html( $decodec['Ds_Order'] ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Quantity:', 'pago-redsys-grafreak' ); ?></td>
							<td><?php echo esc_html( $decodec['Ds_Amount'] ) / 100; ?>€</td>
						</tr>
					</table>
				<?php
				$salida = ob_get_contents();
				ob_end_clean();
				return $salida;
			}
		}
	}

	/**
	 * Create response for TPV ko.
	 *
	 * @param array  $atts    available atts on shortocde.
	 * @param string $content content of shortcode.
	 * @since 1.0.0
	 */
	public function pago_tpv_ko_shortcode( $atts, $content = null ) {
		// Se crea Objeto.
		$mi_obj = new RedsysAPI_Grafreak();
		$enable = get_option( $this->option_name . '_habilitado' );

		if ( isset( $_REQUEST['Ds_Signature'] ) && 'enabled' === $enable ) {

			$version = '';
			if ( isset( $_REQUEST['Ds_SignatureVersion'] ) ) {
				$version = esc_html( sanitize_text_field( wp_unslash( $_REQUEST['Ds_SignatureVersion'] ) ) );
			}
			$datos = '';
			if ( isset( $_REQUEST['Ds_MerchantParameters'] ) ) {
				$datos = esc_html( sanitize_text_field( wp_unslash( $_REQUEST['Ds_MerchantParameters'] ) ) );
			}
			$signaturerecibida = '';
			if ( isset( $_REQUEST['Ds_Signature'] ) ) {
				$signaturerecibida = esc_html( sanitize_text_field( wp_unslash( $_REQUEST['Ds_Signature'] ) ) );
			}

			$decodec = $mi_obj->decode_merchant_parameters( $datos );
			$kc      = get_option( $this->option_name . '_encriptkey' ); // Clave recuperada de CANALES.
			$firma   = $mi_obj->create_merchant_signature_notif( $kc, $datos );

			$params    = $mi_obj->create_merchant_parameters();
			$signature = $mi_obj->create_merchant_signature( $kc );
			$decodec   = get_object_vars( json_decode( $decodec ) );
			if ( $firma === $signaturerecibida && intval( $decodec['Ds_Response'] ) > 100 ) {
				ob_start();
				echo esc_html( $content );
				$salida = ob_get_contents();
				ob_end_clean();
				return $salida;
			}
		}

	}
}
