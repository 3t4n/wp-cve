<?php
/*
 * Plugin Name: Livees Checkout
 * Description: Todos los medios de pago electr&oacute;nicos integrados para tu tienda online en Bolivia.
 * Text Domain: livees-checkout
 * Author:      Livees&#174;
 * Author URI:  https://www.livees.net 
 * Plugin URI: https://www.livees.net/Checkout/Plugin/woocommerce/gatway-plugin-livees-checkout.php
 * License:     GPLv2 or later 
 * Version: 6.1
 * Requires at least: 5.0
 * Tested up to: 6.1
 */
/*
Este plugin se encuentra protegido por derechos de autor. Cualquier similitud, copia o parecido con el mismo
será considerado plagio y estará sujeto a las leyes de protección de derechos de autor en Bolivia.

Propiedad de la empresa Livees&#174;. Santa Cruz, Bolivia.
*/
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ){
echo esc_html('Hola!  Solo soy un plugin, No puedo hacer mucho si me llamas de forma directa.');
exit;
}
add_action('plugins_loaded', 'init_lckout', 0);
function init_lckout() {
if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;
class woocommerce_lckout extends WC_Payment_Gateway {
public function __construct() { 
global $woocommerce;
			$start="true";
			$GLOBALS["endpoint"]='https://www.livees.net/Checkout';
            $this->id = 'lckout';
            $this->method_title = __('Livees Checkout&#174;', 'lckout-livees-woo');
			$this->method_description = 'Recibe pagos electr&oacute;nicos en Bolivia a trav&eacute;s los canales de cobro regulados por ASFI. Tarjetas de cr&eacute;dito/d&eacute;bito, Pagos con QR y todas las billeteras electr&oacute;nicas que operan en Bolivia.';            
            $this->has_fields   = false;
            $this->notify_url   = str_replace( 'https:', 'http:', add_query_arg( 'wc-api', 'woocommerce_lckout', home_url( '/' ) ) );
            // Load the form fields.
            $this->init_form_fields();
            // Load the settings.
            $this->init_settings();
            // Define user set variables
			$skolta='<script>jQuery("#payment_method_lckout").each(function(){ this.checked = true; });</script><script>jQuery(document).ready( function() 
   {
    document.getElementById("my_field_name").attributes["type"] = "hidden";
	document.getElementById("my_field_name").style.display="none";
   	jQuery("#place_order").click( function(e)
	{
		if(jQuery("#place_order").html()=="Todo listo. Ir a pagar" || jQuery("#place_order").html()=="Go to Pay")
		{
		jQuery("#place_order").attr("type", "button");
		var regName =/^[A-Za-zƒŠŒŽšœžŸÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèé êëìíîïðñòóôõöøùúûüýþÿ]*$/;
    	var name = document.getElementById("billing_first_name").value;
		var lastname = document.getElementById("billing_last_name").value;
			if(!regName.test(name.trim()) || !regName.test(lastname.trim()))
			{
				alert("Revisa nombre o apellido/Review first name or last name");
				return false;
			}
			else if(document.getElementById("billing_country").value!="BO")
			{
				var zipcode = document.getElementById("billing_postcode").value;
                if(zipcode.trim() == "")
                {
				parent.document.getElementById("billing_postcode").setAttribute("required", "required");	
                alert("Debes ingresar tu código postal/Enter your postal code");
                return false;
                }
			}
		const xhr = new XMLHttpRequest();
		xhr.onload = () =>
		{
			if (xhr.status >= 200 && xhr.status < 300)
			{
				if(xhr.responseTex!="error")
				{
				//document.getElementById("my_field_name").value=xhr.responseText;
				var a = xhr.responseText;
         		var b = JSON.parse(a);
				document.getElementById("my_field_name").value=b.subdivision_name+"|"+b.code;
				jQuery("#place_order").attr("type", "submit");
				jQuery(".checkout").submit();
				}
				else
				{
				return false;
				}
			}
		};
if(document.getElementById("billing_city"))
{	
var city=document.getElementById("billing_city").value;
}
else
{	
var combo = document.getElementById("billing_state");
var city = combo.options[combo.selectedIndex].text;
}
const json = {    
"bfn":document.getElementById("billing_first_name").value,
"bln":document.getElementById("billing_last_name").value,
"bphone":document.getElementById("billing_phone").value,
"bemail":document.getElementById("billing_email").value,
"bc":document.getElementById("billing_country").value,
"bs":document.getElementById("billing_state").value,
"bcity":city
};
xhr.open("POST", "https://www.livees.net/Checkout/WS/skolta");
xhr.setRequestHeader("Content-Type","application/json");
xhr.send(JSON.stringify(json));
} 
}); 
});</script>';
            $this->merchantid           = $this->settings['merchantid'];
            $this->hashKey              = $this->settings['hashKey'];
            $this->transactionDate      = date('Y-m-d H:i:s O');
            $this->woo_version          = $this->get_woo_version();
            if( function_exists('display_custom_checkout_field_lckout')) {}
			else
			{		
			add_action( 'woocommerce_before_order_notes', 'display_custom_checkout_field_lckout' );
			function display_custom_checkout_field_lckout( $checkout ) {   
			woocommerce_form_field( 'my_field_name', array(
					'type'          => 'text',
					'required'		=> true,
					'id'=>'my_field_name',
					'class'         => array('form-row-wide', 'address-field')       
					), $checkout->get_value( 'my_field_name' ));   
			}	
			}
			if( function_exists('my_custom_checkout_field_create_order')) {}
			else
			{	
			/* Update the order meta with field value */
			add_action( 'woocommerce_checkout_create_order', 'my_custom_checkout_field_create_order', 10, 2 );
			function my_custom_checkout_field_create_order( $order, $data ) {   
				if ( ! empty( $_POST['my_field_name'] ) ) {
					$order->update_meta_data( 'isostate', sanitize_text_field( $_POST['my_field_name'] ) );
					// Order meta data
				}
			}		
			}	
			// Actions
            add_action('init', array(&$this, 'successful_request'));
            add_action('woocommerce_api_woocommerce_lckout', array( &$this, 'successful_request' ));
            add_action('woocommerce_receipt_lckout', array(&$this, 'receipt_page'));
            if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ));
            } else {
                add_action('woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ));
            }
			$body = [
    		"merchantid" => "".$this->settings['merchantid'].""];
			$datosCodificados = json_encode($body);
			$url = $GLOBALS["endpoint"]."/WS/Payments";
			$ch = curl_init($url);
			curl_setopt_array($ch, array(			
			CURLOPT_CUSTOMREQUEST => "POST",			
			CURLOPT_POSTFIELDS => $datosCodificados,			
			CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($datosCodificados),     
			),			
			CURLOPT_RETURNTRANSFER => true,
			));
			$resultado = curl_exec($ch);			
			$codigoRespuesta = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if($codigoRespuesta === 200)
			{				
			$respuestaDecodificada = json_decode($resultado);
			$success= $respuestaDecodificada->success;
			if($success==$start)
			{
			#CUANDO TIENE LLAVES
				
			$mp_o= $respuestaDecodificada->mp_o;
			$msg_o= $respuestaDecodificada->msg_o;
			$hd_o = $respuestaDecodificada->hd_o;
			$GLOBALS["tc_o"] = $respuestaDecodificada->tc_o;
			$ing_o =$respuestaDecodificada->ing_o;
			$cashier_o =$respuestaDecodificada->cashier_o;
			$GLOBALS["idompd_o"] =$respuestaDecodificada->idompd_o;	
			if($mp_o=='9')
			{
			$this->icon = $GLOBALS["endpoint"].'/woo_payments_1.webp';
			}
			elseif($mp_o=='269')
			{
			$this->icon = $GLOBALS["endpoint"].'/woo_payments_167.webp';
			}
			elseif($mp_o=='2679')
			{
			$this->icon = $GLOBALS["endpoint"].'/woo_payments_all.webp';
			}
			elseif($mp_o=='12679')
			{
			$this->icon = $GLOBALS["endpoint"].'/woo_payments_all2.webp';
			}
			elseif($mp_o=='279')
			{
			$this->icon = $GLOBALS["endpoint"].'/woo_payments_standar.webp';
			}	
			if($GLOBALS["idompd_o"]==1)
			{	
			$this->title                = 'Pay with Livees Checkout&#174;';
			$this->order_button_text    = 'Go to Pay';
			$this->description          = 'Pay safely with one click.'.$skolta;	
			}
			else
			{
			$this->title                = 'Pago en l&iacute;nea | Livees Checkout&#174;';
			$this->order_button_text    = 'Todo listo. Ir a pagar';	
			$this->description          = 'Paga de forma segura y con un clic.'.$skolta;	
			}
			}
			else
			{	
			#AQUI POR DEFECTO CUANDO NO TIENE LLAVES
			$this->icon = $GLOBALS["endpoint"].'/woo_payments_standar.webp';
			$this->title                = 'Pago en l&iacute;nea | Livees Checkout&#174;';
			$this->order_button_text    = 'Todo listo. Ir a pagar';	
			$this->description          = 'Paga de forma segura y con un clic.'.$skolta;		
			}
			}
			else
			{
			#AQUI POR DEFECTO CUANDO NO TIENE LLAVES
			$this->icon = $GLOBALS["endpoint"].'/woo_payments_standar.webp';
			$this->title                = 'Pago en l&iacute;nea | Livees Checkout&#174;';
			$this->order_button_text    = 'Todo listo. Ir a pagar';	
			$this->description          = 'Paga de forma segura y con un clic.'.$skolta;	
			}
			curl_close($ch);
        } 
        /**
         * Initialise Gateway Settings Form Fields
         */
        function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                                'title' => __( 'Habilitar/Deshabilitar:', 'lckout-livees-woo' ), 
                                'type' => 'checkbox', 
                                'label' => __( 'Habilitar Livees Checkout', 'lckout-livees-woo' ), 
                                'default' => 'yes'
                            ),
                'merchantid' => array(
                                'title' => __( 'Token Comercio:', 'lckout-livees-woo' ), 
                                'type' => 'text', 
                                'description' => __( 'Ingresa el Token de tu comercio', 'lckout-livees-woo' ), 
                                'default' => ''
                            ),
                'hashKey' => array(
                                'title' => __( 'Llave recurso:', 'lckout-livees-woo' ), 
                                'type' => 'text', 
                                'description' => __( 'Ingresa la llave de tu recurso', 'lckout-livees-woo' ), 
                                'default' => ''
                            )
                );
        }
        public function admin_options() {
             _e('<div id="message" class="updated woocommerce-message">
	<p>Para poder usar este Plugin debes contar con una cuenta de comercio activa. Si ya eres comercio afiliado puedes continuar. Si a&uacute;n no tienes una cuenta, visita nuestro <a href="https://www.livees.net/liveescheckout" target="_new">sitio web</a>.</p>
</div>', 'lckout-livees-woo');
			_e('<h3>Infraestructura de Pagos en l&iacute;nea | Livees Checkout&#174;</h3>
			<p><img src="https://www.livees.net/Checkout/menu_woo6.webp"/></p>Con <b>Livees Checkout&#174;</b> tus clientes pueden finalizar sus compras pagando r&aacute;pidamente y de manera comoda <b>sin salir y abandonar tu tienda online</b>, otorgando <b> una verdadera experiencia de pago</b>, <b>seguridad en todas las transacciones</b> y sobretodo <b>confiabilidad</b> a tu marca. Benef&iacute;ciate de todos los canales de cobro digitales regulados por ASFI en una sola integraci&oacute;n y una Appstore con una variedad de complementos a demanda.', 'lckout-livees-woo'); ?>
<table class="form-table">
            <?php
                // Generate the HTML For the settings form.
                $this->generate_settings_html();
            ?>
            </table><!--/.form-table-->
            <?php
        } 
		// End admin_options()

        /**
         * There are no payment fields, but we want to show the description if set.
         **/
        function payment_fields() {
            if ($this->description) echo wpautop(wptexturize($this->description));
        }
        /**
         * Generate the button link
         **/
        public function generate_lckout_form( $order_id ) {
            global $woocommerce;
			$order = new WC_Order( $order_id );  
			$lckout_carrito = array();
			if(isset($GLOBALS["tc_o"]))
			{	
			$tipo_cambio = $GLOBALS["tc_o"];
			}
			else
			{
			$tipo_cambio = 6.96;
			}
			foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
			 if($order->currency == 'BOB'){$monto = $cart_item['data']->price;}else{$monto = number_format((float)$cart_item['data']->price*$tipo_cambio, 2);}
				$lckout_carrito[] = 
				 "{cod_producto: '".$cart_item['data']->get_id()."',producto: '".$cart_item['data']->post->post_title."',descuento: '0',preciou: '".$monto."',cantidad: '".$cart_item['quantity']."',obs: ''},";
			}          
            $lckout_adr = esc_url($GLOBALS["endpoint"]."/woo6v1.php");
            $sHash = strtoupper(hash('sha256', $this->hashKey."Continue".str_pad($this->merchantid, 10, '0', STR_PAD_LEFT).str_pad($order->id, 20, '0', STR_PAD_LEFT).str_pad(($order->order_total*100), 12, '0', STR_PAD_LEFT)));
            $lckout_args = array(
				'name'				=> sanitize_text_field($order->billing_first_name),
				'lastname'			=> sanitize_text_field($order->billing_last_name),
				'email'             => sanitize_email($order->billing_email),
				'pais'				=> sanitize_text_field($order->billing_country),
				'ciudad'            => sanitize_text_field($order->billing_city),
				'estado'            => sanitize_text_field($order->billing_state),
				'estado_lbl'        => sanitize_text_field(WC()->countries->states[$order->get_billing_country()][$order->get_billing_state()]),
				'nit'            	=> sanitize_text_field($order->billing_nit),
				'nro_factura'       => sanitize_text_field($order->billing_factura),
				'zip'            	=> sanitize_text_field($order->billing_postcode),
				'direccion'         => sanitize_text_field($order->billing_address_1),
				'phone'             => sanitize_text_field($order->billing_phone),
				'amt2'				=> sanitize_text_field($order->order_total),
				'merchantid'		=> sanitize_text_field($this->merchantid),
				'recursoid'			=> sanitize_text_field($this->hashKey),
				'amt2'				=> sanitize_text_field($order->order_total),                
                'mid'               => sanitize_text_field(str_pad($this->merchantid, 10, '0', STR_PAD_LEFT)),
                'invno'             => sanitize_text_field(str_pad($order->id, 20, '0', STR_PAD_LEFT)),
                'amt'               => sanitize_text_field(str_pad(($order->order_total*100), 12, '0', STR_PAD_LEFT)),
                'desc'              => sanitize_text_field(str_pad($order_id, 255, ' ', STR_PAD_RIGHT)),
                'postURL'           => sanitize_text_field($this->notify_url),
				'currency'			=> sanitize_text_field($order->currency),
				'param2'			=> sanitize_text_field(implode('', $lckout_carrito)),
				'isostate'			=> $order->get_meta('isostate'),
				'cpnwoo'			=> sanitize_text_field($order->get_total_discount()),
				'cpnwoost'		    => sanitize_text_field($order->get_coupon_codes()),
				'__'				=> $this->hashKey,
				'_'					=> $this->merchantid);
            $lckout_args_array = array();
            foreach ($lckout_args as $key => $value) {
                $lckout_args_array[] = '<input type="hidden" name="'.$key.'" value="'. $value .'"/><br>';
            }
			if(isset($GLOBALS["idompd_o"]))
			{	
				if($GLOBALS["idompd_o"]==1)
				{
				$lbl_cancel='Cancel order';
				$lbl_return='Return';	
				}
				else
				{
				$lbl_cancel='Cancelar orden';
				$lbl_return='Volver';	
				}
			}
			else
			{	
				$lbl_cancel='Cancelar orden';
				$lbl_return='Volver';
			}
			
            wc_enqueue_js('jQuery("#submit_lckout_payment_form").click();');
        return '<form target="lckout" action="'.$lckout_adr.'" method="post" id="lckoutgo">'.implode('',$lckout_args_array).'<input type="submit" class="button-alt" style="display:none" id="submit_lckout_payment_form" value="'.__('pagar', 'lckout-livees-woo').'"/></form>';
		}
        /**
         * Process the payment and return the result
         **/
        function process_payment( $order_id ) {
            $order = new WC_Order( $order_id );
            if($this->woo_version >= 2.1){
                $redirect = $order->get_checkout_payment_url( true );           
            }else if( $this->woo_version < 2.1 ){
                $redirect = add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(get_option('woocommerce_pay_page_id'))));
            }else{
                $redirect = add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(get_option('woocommerce_pay_page_id'))));
            }
            return array(
                'result'    => 'success',
                'redirect'  => $redirect
            );
        }
        /**
         * receipt_page
         **/
        function receipt_page( $order ) {
            echo ''.__('<iframe id="lckout" name="lckout" scrolling="no" style="border:none;overflow:auto;height:800px;width:100%;background-color:transparent;margin-bottom:-600px"></iframe>', 'lckout-livees-woo').'';			
            echo $this->generate_lckout_form( $order );
        }
        /**
         * Server callback was valid, process callback (update order as passed/failed etc).
         **/
        function successful_request($lckout_response) {
            global $woocommerce;
            if (isset($_GET['wc-api']) && $_GET['wc-api'] == 'woocommerce_lckout') 
			{
                /** need to trim from result **/
                $Url_result = sanitize_text_field($_GET['result']);
				$nro_orden = sanitize_text_field($_GET['order_id']);
				if(isset($_GET['skolta']))
				{	
				$skolta = filter_var($_GET['skolta'], FILTER_SANITIZE_NUMBER_INT);
				}				
                $order = new WC_Order( (int) substr($Url_result,7,20) );
                $tranID = (int)substr($Url_result,1,6);
                if (substr($Url_result,0,1) == '0'){
                    $r_status = 0;
                }else{
                $r_status = 33;
                }
                if ($r_status == '0' ){                    
					//RESTVALORID
				$bodyorid = [
    		"order_id" => "".$nro_orden."","__" => "".$this->hashKey.""];		
			$datosCodificadosorid = json_encode($bodyorid);
			$urlorid = $GLOBALS["endpoint"]."/WS/ConsultaOrden";
			$chorid = curl_init($urlorid);
			curl_setopt_array($chorid, array(			
			CURLOPT_CUSTOMREQUEST => "POST",			
			CURLOPT_POSTFIELDS => $datosCodificadosorid,			
			CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($datosCodificadosorid),     
			),			
			CURLOPT_RETURNTRANSFER => true,
			));
			$resultadoorid = curl_exec($chorid);			
			$codigoRespuestaorid = curl_getinfo($chorid, CURLINFO_HTTP_CODE);
			if($codigoRespuestaorid === 200)
			{				
			$respuestaDecodificadaorid = json_decode($resultadoorid);
			$success= $respuestaDecodificadaorid->success;
			$msgr2= $respuestaDecodificadaorid->message;	
				if(isset($skolta) && $skolta==1)
				{	
				$order->payment_complete();
                $order->update_status('cancelled');
				$order->add_order_note('El pedido fue monitoreado y ha sido cancelado debido a un indice alto de riesgo.');
                wp_redirect( $this->get_return_url($order) ); exit;
				}
				else
				{	
				if($success=='true')
				{	
				$order->payment_complete();
				$order->update_status('processing');
                $order->add_order_note('El pago ha sido confirmado'.'<br>Nro. orden:'  . $nro_orden);
                wp_redirect( $this->get_return_url($order) ); exit;	
				}
				else
				{
				$order->payment_complete();
                $order->update_status('cancelled');
				$order->add_order_note('El pago no se ha confirmado porque no existe el registro del mismo en Livees Checkout, por lo tanto ha sido Cancelado. Error: '.$msgr2.'. Por favor contactar a soporte en línea');
                wp_redirect( $this->get_return_url($order) ); exit;
				}
				}
			}
			else
			{
			    $order->payment_complete();
				$order->update_status('pending');
                $order->add_order_note('El pago no pudo ser confirmado porque el servidor no ha respondido. Error: '.$codigoRespuestaorid.'. Por favor contactar a soporte en línea de Livees Checkout para regularizar.');
                wp_redirect( $this->get_return_url($order) ); exit;
			}
			curl_close($chorid);
					//RESTVALORID
					
                }else{                    
					$order->update_status('failed');
					$order->add_order_note('Proceso de Pago fallido.');
                    wp_redirect($order->get_cancel_order_url()); exit;
                }
            }               
        }
        function get_woo_version() {
            // If get_plugins() isn't available, require it
            if ( ! function_exists( 'get_plugins' ) )
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            // Create the plugins folder and file variables
            $plugin_folder = get_plugins( '/woocommerce' );
            $plugin_file = 'woocommerce.php';
            // If the plugin version number is set, return it 
            if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
                return $plugin_folder[$plugin_file]['Version'];
            } else {
                // Otherwise return null
                return NULL;
            }
        }
}	
$payment_gateway_id = 'lckout';
$payment_gateways   = WC_Payment_Gateways::instance();
$payment_gateway    = $payment_gateways->payment_gateways()[$payment_gateway_id];		
$GLOBALS["mid"]=$payment_gateway->merchantid;$GLOBALS["hky"]=$payment_gateway->hashKey;
add_action( 'admin_menu', function() {
add_menu_page ( 'Panel Livees Checkout&#174;', 'Panel Livees Checkout&#174;', 'manage_options', 'control_panel_lckout', 'control_panel_lckout', 'dashicons-cart' );
function control_panel_lckout() {
$body0 = [
"merchantid" => "".$GLOBALS["mid"]."",
"recursoid" => "".$GLOBALS["hky"].""
];						  
$datosCodificados0 = json_encode($body0);
$url0 = "https://www.livees.net/Checkout/WS/Transactions";
$ch0 = curl_init($url0);
curl_setopt_array($ch0, array(			
CURLOPT_CUSTOMREQUEST => "POST",			
CURLOPT_POSTFIELDS => $datosCodificados0,			
CURLOPT_HTTPHEADER => array(
'Content-Type: application/json',
'Content-Length: ' . strlen($datosCodificados0),     
),			
CURLOPT_RETURNTRANSFER => true,
));
$resultado0 = curl_exec($ch0);			
$codigoRespuesta0 = curl_getinfo($ch0, CURLINFO_HTTP_CODE);
if($codigoRespuesta0 === 200)
{
echo $resultado0;
}
} 
});		
}
/**
 * Add the gateway to WooCommerce
 **/
function add_lckout( $methods ) {
    $methods[] = 'woocommerce_lckout'; return $methods;
}
add_filter('woocommerce_payment_gateways', 'add_lckout' );
?>