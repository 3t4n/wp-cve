<?php
/**
 * El uso de este software está sujeto a las Condiciones de uso de software que se incluyen en el paquete en el documento "Aviso Legal.pdf". También puede obtener una copia en la siguiente url:
 * http://www.redsys.es/wps/portal/redsys/publica/areadeserviciosweb/descargaDeDocumentacionYEjecutables
 * Redsys Servicios de Procesamiento, S.L., CIF B85955367
 *
 * @link       http://www.grafreak.net
 * @since      1.0.0
 * @package    Pago_Redsys_Grafreak_RedsysAPI_Grafreak
 * @subpackage Pago_Redsys_Grafreak/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * The redsys api class.
 *
 * This is used to comunicate and get from Redsys API
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pago_Redsys_Grafreak
 * @subpackage Pago_Redsys_Grafreak/includes
 * @author     Adrian Cobo <adrian@grafreak.net>
 */
class RedsysAPI_Grafreak {

	/**
	 * Array de datos de entrada
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pago_Redsys_Grafreak_vars_pay    $vars_pay    Array de datos de entrada.
	 */
	private $vars_pay = array();

	/**
	 * Set the parameter inside $vars_pay
	 *
	 * @since    1.0.0
	 * @param    string $key           The key in array.
	 * @param    string $value         The value in array.
	 */
	public function set_parameter( $key, $value ) {
		$this->vars_pay[ $key ] = $value;
	}

	/**
	 * Get the parameter inside $vars_pay
	 *
	 * @since    1.0.0
	 * @param    string $key           The key you want to get.
	 */
	public function get_parameter( $key ) {
		return $this->vars_pay[ $key ];
	}

	/**
	 * Encryp to pass
	 *
	 * @since    1.0.0
	 * @param    string $message    The message to encrypt.
	 * @param    string $key        The key to pass.
	 */
	public function encrypt_3des( $message, $key ) {
		// Se establece un IV por defecto.
		$bytes = array( 0, 0, 0, 0, 0, 0, 0, 0 );
		$iv    = implode( array_map( 'chr', $bytes ) );
		// Se cifra.
		$l = ceil( strlen( $message ) / 8 ) * 8;
		return substr( openssl_encrypt( $message . str_repeat( "\0", $l - strlen( $message ) ), 'des-ede3-cbc', $key, OPENSSL_RAW_DATA, "\0\0\0\0\0\0\0\0" ), 0, $l );

		/** Se cifra.
		* $ciphertext = mcrypt_encrypt(MCRYPT_3DES, $key, $message, MCRYPT_MODE_CBC, $iv); //PHP 4 >= 4.0.2
		* return $ciphertext;
		*/
	}

	/**
	 * Base64_url_encode
	 *
	 * @since    1.0.0
	 * @param    string $input  input to encode.
	 */
	public function base64_url_encode( $input ) {
		return strtr( base64_encode( $input ), '+/', '-_' );
	}

	/**
	 * Encode_base64
	 *
	 * @since    1.0.0
	 * @param    string $data   The data to encode.
	 */
	public function encode_base64( $data ) {
		$data = base64_encode( $data );
		return $data;
	}

	/**
	 * Base64_url_decode
	 *
	 * @since    1.0.0
	 * @param    string $input  The url to decode.
	 */
	public function base64_url_decode( $input ) {
		return base64_decode( strtr( $input, '-_', '+/' ) );
	}

	/**
	 * Decode_base_64
	 *
	 * @since    1.0.0
	 * @param    string $data   The data to decode.
	 */
	public function decode_base_64( $data ) {
		$data = base64_decode( $data );
		return $data;
	}

	/**
	 * Mac256
	 *
	 * @since    1.0.0
	 * @param    string $ent    entity to hash.
	 * @param    string $key    key for hash.
	 */
	public function mac256( $ent, $key ) {
		$res = hash_hmac( 'sha256', $ent, $key, true );
		// (PHP 5 >= 5.1.2).
		return $res;
	}

	/**
	 * Get the merchant order
	 *
	 * @since    1.0.0
	 */
	public function get_order() {
		$num_pedido = '';
		if ( empty( $this->vars_pay['DS_MERCHANT_ORDER'] ) ) {
			$num_pedido = $this->vars_pay['Ds_Merchant_Order'];
		} else {
			$num_pedido = $this->vars_pay['DS_MERCHANT_ORDER'];
		}
		return $num_pedido;
	}

	/**
	 * Get all data on vars_pay on json
	 *
	 * @since    1.0.0
	 */
	public function array_to_json() {
		$json = wp_json_encode( $this->vars_pay ); // (PHP 5 >= 5.2.0)
		return $json;
	}

	/**
	 * Put on base 64 all vars_pay variable
	 *
	 * @since    1.0.0
	 */
	public function create_merchant_parameters() {
		// Se transforma el array de datos en un objeto Json.
		$json = $this->array_to_json();
		// Se codifican los datos Base64.
		return $this->encode_base64( $json );
	}

	/**
	 * Put on base 64 all vars_pay variable
	 *
	 * @since    1.0.0
	 * @param    string $key    key for signature.
	 */
	public function create_merchant_signature( $key ) {
		// Se decodifica la clave Base64.
		$key = $this->decode_base_64( $key );
		// Se genera el parámetro Ds_MerchantParameters.
		$ent = $this->create_merchant_parameters();
		// Se diversifica la clave con el Número de Pedido.
		$key = $this->encrypt_3des( $this->get_order(), $key );
		// MAC256 del parámetro Ds_MerchantParameters.
		$res = $this->mac256( $ent, $key );
		// Se codifican los datos Base64.
		return $this->encode_base64( $res );
	}

	/**
	 * Get the order notification
	 *
	 * @since    1.0.0
	 */
	public function get_order_notif() {
		$num_pedido = '';
		if ( empty( $this->vars_pay['Ds_Order'] ) ) {
			$num_pedido = $this->vars_pay['DS_ORDER'];
		} else {
			$num_pedido = $this->vars_pay['Ds_Order'];
		}
		return $num_pedido;
	}

	/**
	 * Get the order notification SOAP
	 *
	 * @since    1.0.0
	 * @param    string $datos  key for signature.
	 */
	public function get_order_notif_soap( $datos ) {
		$pos_pedido_ini = strrpos( $datos, '<Ds_Order>' );
		$tam_pedido_ini = strlen( '<Ds_Order>' );
		$pos_pedido_fin = strrpos( $datos, '</Ds_Order>' );
		return substr( $datos, $pos_pedido_ini + $tam_pedido_ini, $pos_pedido_fin - ( $pos_pedido_ini + $tam_pedido_ini ) );
	}

	/**
	 * Get the request notification SOAP
	 *
	 * @since    1.0.0
	 * @param    string $datos  requested notification.
	 */
	public function get_request_notif_soap( $datos ) {
		$pos_req_ini = strrpos( $datos, '<Request' );
		$pos_req_fin = strrpos( $datos, '</Request>' );
		$tam_req_fin = strlen( '</Request>' );
		return substr( $datos, $pos_req_ini, ( $pos_req_fin + $tam_req_fin ) - $pos_req_ini );
	}

	/**
	 * Get the response notification SOAP
	 *
	 * @since    1.0.0
	 * @param    string $datos  responsed notification.
	 */
	public function get_response_notif_soap( $datos ) {
		$pos_req_ini = strrpos( $datos, '<Response' );
		$pos_req_fin = strrpos( $datos, '</Response>' );
		$tam_req_fin = strlen( '</Response>' );
		return substr( $datos, $pos_req_ini, ( $pos_req_fin + $tam_req_fin ) - $pos_req_ini );
	}

	/**
	 * String to array
	 *
	 * @since    1.0.0
	 * @param    string $datos_decode data to decode and put.
	 */
	public function string_to_array( $datos_decod ) {
		$this->vars_pay = json_decode( $datos_decod, true ); // (PHP 5 >= 5.2.0)
	}

	/**
	 * Decode parameters
	 *
	 * @since    1.0.0
	 * @param    string $datos  data to decode.
	 */
	public function decode_merchant_parameters( $datos ) {
		// Se decodifican los datos Base64.
		$decodec = $this->base64_url_decode( $datos );
		// Los datos decodificados se pasan al array de datos.
		$this->string_to_array( $decodec );
		return $decodec;
	}

	/**
	 * Create signature notification
	 *
	 * @since    1.0.0
	 * @param    string $key    signature key.
	 * @param    string $datos  data to send.
	 */
	public function create_merchant_signature_notif( $key, $datos ) {
		// Se decodifica la clave Base64.
		$key = $this->decode_base_64( $key );
		// Se decodifican los datos Base64.
		$decodec = $this->base64_url_decode( $datos );
		// Los datos decodificados se pasan al array de datos.
		$this->string_to_array( $decodec );
		// Se diversifica la clave con el Número de Pedido.
		$key = $this->encrypt_3des( $this->get_order_notif(), $key );
		// MAC256 del parámetro Ds_Parameters que envía Redsys.
		$res = $this->mac256( $datos, $key );
		// Se codifican los datos Base64.
		return $this->base64_url_encode( $res );
	}

	/**
	 * Create signature for SOAP request
	 *
	 * @since    1.0.0
	 * @param    string $key    signature key.
	 * @param    string $datos  data to send.
	 */
	public function create_merchant_signature_notif_soap_request( $key, $datos ) {
		// Se decodifica la clave Base64.
		$key = $this->decode_base_64( $key );
		// Se obtienen los datos del Request.
		$datos = $this->get_request_notif_soap( $datos );
		// Se diversifica la clave con el Número de Pedido.
		$key = $this->encrypt_3des( $this->get_order_notif_soap( $datos ), $key );
		// MAC256 del parámetro Ds_Parameters que envía Redsys.
		$res = $this->mac256( $datos, $key );
		// Se codifican los datos Base64.
		return $this->encode_base64( $res );
	}

	/**
	 * Create signature for SOAP notification
	 *
	 * @since    1.0.0
	 * @param    string $key    signature key.
	 * @param    string $datos  data to send.
	 * @param    string $num_pedido  numero de pedido.
	 */
	public function create_merchant_signature_noti_soap_response( $key, $datos, $num_pedido ) {
		// Se decodifica la clave Base64.
		$key = $this->decode_base_64( $key );
		// Se obtienen los datos del Request.
		$datos = $this->get_response_notif_soap( $datos );
		// Se diversifica la clave con el Número de Pedido.
		$key = $this->encrypt_3des( $num_pedido, $key );
		// MAC256 del parámetro Ds_Parameters que envía Redsys.
		$res = $this->mac256( $datos, $key );
		// Se codifican los datos Base64.
		return $this->encode_base64( $res );
	}

}
