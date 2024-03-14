<?php

/**
 * Plugin Name: PagueloFacil
 * Plugin URI: https://github.com/maryiliana/gateway-paguelofacil-for-woocommerce
 * Description: A plugin that add a new WooCommerce payment.
 * Author: PagueloFacil
 * Domain Path: /languages
 * Author URI:
 * Version: 3.7
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

 
/**
 * Check if WooCommerce is active
 **/
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    // Put your plugin code here


    add_action('plugins_loaded', 'woocommerce_paguelofacil_init', 100);
    

    function woocommerce_paguelofacil_init()
    {


        /**
         * Pasarela PagueloFacil Gateway Class
         * */
        class woocommerce_paguelofacil extends WC_Payment_Gateway
        {


            /**
             * CONSTRUIMOS LA CLASE
             */
            public function __construct()
            {
                $this->id = 'paguelofacil';
                $this->method_title = 'PagueloFacil';
                $this->icon = "https://pfserver.net/img/VisaMC.png";
                $this->has_fields = true;
                $this->order_button_text = __('Pague', 'paguelofacil');
                $this->supports = array('default_credit_card_form', 'refunds', 'products');
                $this->title = 'PagueloFacil';
                $this->return_url = wc_get_page_permalink('shop') . 'wc-api/woocommerce_paguelofacil/';
                // Load the form fields
                $this->init_form_fields();

                // Load the settings.
                $this->init_settings();
                $this->product = isset($this->settings['product']) ? $this->settings['product'] : null;

                $this->testmode = $this->get_option('testmode');



                // Get setting values
                $this->enabled = $this->settings['enabled'];
                $this->type_auth = isset($this->settings['type_auth']) ? $this->settings['type_auth'] : null;
                $this->description = isset($this->settings['description']) ? $this->settings['description'] : null;

                $this->cclw = $this->settings['cclw'];
                $this->cclw_demo = isset($this->settings['cclw_demo']) ? $this->settings['cclw_demo'] : null;
                $this->token_live = isset($this->settings['token_live']) ? $this->settings['token_live'] : null;
                $this->token_demo = isset($this->settings['token_demo']) ? $this->settings['token_demo'] : null;

                $this->uri_store = isset($this->settings['uri_store']) ? $this->settings['uri_store'] : $this->return_url;

                $this->currency = get_woocommerce_currency();

                $this->pp_code = '';
                $this->pp_monto = '';

                $this->msg_cash=  '<h1>'.esc_html(__('¡Estamos esperando tu pago!', 'paguelofacil')).'</h1><br>
                <p>'.esc_html(__('Disfruta de tus productos, solo debes cancelar en los puntos de pago con el siguiente código', 'paguelofacil')).'</p>
                   <h2>'.esc_html(__('Código Pago Cash: ', 'paguelofacil')).'<strong>' . $this->pp_code . '</strong></h2>
        <h2>'.esc_html(__('Monto: ', 'paguelofacil')).'<strong> $' . $this->pp_monto . '</strong></h2>
                <h2>'.esc_html(__('Instrucciones', 'paguelofacil')).'</h2>
                <p><img src="' . plugin_dir_url(__FILE__) . 'img/pc-esp-desktop.png" width="100%" height="100%"></p>
                <h2><a href="'.esc_url("https://www.puntopago.net/?show=map").'" target="_blank">'.esc_html(__('Ubicación de los puntos de Pago', 'paguelofacil')).'</a></h2>';

                $this->msg_crypto= '<h1>'.esc_html(__('¡Estamos verificando tu pago!','paguelofacil').'</h1><br><p>'.__('Una vez recibamos la confirmación de tu pago en Cripto, te enviaremos tu producto.', 'paguelofacil')).'</p>';

                // Hooks
                add_action('init', array($this, 'check_' . $this->id . '_resquest'));
                add_action('init', array($this, 'parameters' . $this->id . '_response'));

                // Payment listener/API hook
                add_action('woocommerce_api_woocommerce_' . $this->id, array($this, 'check_' . $this->id . '_resquest'));
                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

                add_action('woocommerce_order_status_processing', array($this, 'capture_payment'));

                add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));
                add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
                
            }
            

            /**
             * Initialize Gateway Settings Form Fields
             */
            function init_form_fields()
            {

                $this->form_fields = array(
                    'enabled' => array(
                        'title' => __('Habilitado', 'paguelofacil'),
                        'label' => __('Habilite PagueloFacil Pasarela', 'paguelofacil'),
                        'type' => 'checkbox',
                        'description' => '',
                        'default' => 'no'
                    ),
                    'description' => array(
                        'title' => __('Descripción', 'paguelofacil'),
                        'type' => 'textarea',
                        'description' => __('Esto controla la descripción que el usuario ve durante el pago.', 'paguelofacil'),
                        'default' => '',
                    ),
                    'type_auth' => array(
                        'title' => __('Pre-Autorización', 'paguelofacil'),
                        'label' => __('Habilite transacciones de tipo Pre-autorizaciones (AUTH)', 'paguelofacil'),
                        'type' => 'checkbox',
                        'description' => __('Si habilita esta opción las transacciones solo se autorizarán y deberá realizar la captura a través del pedido', 'paguelofacil'),
                        'default' => 'no'
                    ),
                    'uri_store' => array(
                        'title' => __('URL de tu tienda', 'paguelofacil'),
                        'label' => __('URL de tienda para el retorno del usuario', 'paguelofacil'),
                        'type' => 'text',
                        'description' => __('Nota: Solo debes introducirla en el caso de que todas tus compras queden en estado de pendiente,', 'paguelofacil').' <BR> ' . __('funciona para que el usuario retorne a tu sitio una vez finalizado el pago. Es importante que consultes este cambio con tu webmaster', 'paguelofacil') . '<BR>'. sprintf(__(' Default: %s', 'paguelofacil'), $this->return_url),
                        'default' => $this->return_url
                    ),
                    'cclw' => array(
                        'title' => __('CCLW Live', 'paguelofacil'),
                        'type' => 'text',
                        'description' => '',
                        'default' => ''
                    ),
                    'token_live' => array(
                        'title' => __('Access Token Api Live', 'paguelofacil'),
                        'label' => __('Credenciales para pruebas', 'paguelofacil'),
                        'type' => 'text',
                        'description' => __('Esta llave pertenece a su cuenta live', 'paguelofacil'),
                        'default' => ''
                    ),
                    'testmode' => array(
                        'title' => __('Activar Demo', 'paguelofacil'),
                        'type' => 'checkbox',
                        'label' => __('Habilitar modo de pruebas', 'paguelofacil'),
                        'default' => 'no',
                        'description' => sprintf(__('PagueloFacil demo puede ser usado para transacciones de pruebas', 'paguelofacil')),
                    ),
                    'cclw_demo' => array(
                        'title' => __('CCLW Demo', 'paguelofacil'),
                        'label' => __('Credenciales para pruebas', 'paguelofacil'),
                        'type' => 'text',
                        'description' => '',
                        'default' => ''
                    ),
                    'token_demo' => array(
                        'title' => __('Access Token Api Demo', 'paguelofacil'),
                        'label' => __('Credenciales para pruebas', 'paguelofacil'),
                        'type' => 'text',
                        'description' => __('Esta llave pertenece a su cuenta demo', 'paguelofacil'),
                        'default' => ''
                    ),



                );
            }

            /**
             * There are no payment fields for PagueloFacil, but we want to show the description if set.
             * */
            function payment_fields()
            {

                //MUESTRA EN CAMPO DESCRIPCION EN EL METODO DE PAGO
                if ($this->description)
                    echo wpautop(wptexturize(esc_html($this->description)));
            }

            /**
             * Admin Panel Options
             * CREA LAS PAGINA DE OPCIONES DEL ADMIN
             * */
            public function admin_options()
            {
?>
                <h3><?php __('PagueloFacil Payment Gateway', 'paguelofacil'); ?></h3>
                <div class="woocommerce">
                    <div class="woocommerce-info"><?php __('¿Deseas saber como obtener sus llaves?', 'paguelofacil'); ?> <a href="https://www.iorad.com/player/1665803/Paguelofacil---D-nde-consigo-las-llaves-para-integrar-mi-negocio-con-PagueloFacil#trysteps-1" target="_blank" class="showcoupon"><?php __('Clic aquí para ver', 'paguelofacil'); ?></a></div>
                </div>

                <table class="form-table">
                    <?php $this->generate_settings_html(); ?>

                </table>
                <!--/.form-table-->
<?php
            }

            /**
             * PagueloFacil 
             * CAPTURE PAYMENT PAGUELOFACIL
             * */
            function capture_payment($order_id)
            {

                //OBTENEMOS LA ORDEN DE WOOCOMMERCE
                $order = new WC_Order($order_id);

                // Do your refund here. Refund $amount for the order with ID $order_id
                $meta_tx_type = $order->get_meta('_pf_transaction_type', true);
                $meta_tx = $order->get_meta('_pf_transaction_id', true);

                error_log(print_r('PagueloFacil_meta_tx_type' . json_encode($meta_tx_type), true));
                error_log(print_r('PagueloFacil_order_status' . json_encode($order->get_status()), true));
                //
                if ($meta_tx_type == "AUTH" && $order->get_status() == "processing" && $meta_tx != null) {

                    $CCLW = ('yes' == $this->testmode) ? $this->cclw_demo : $this->cclw;
                    $AccessTokenApi = ('yes' == $this->testmode) ? $this->token_demo : $this->token_live;
                    $authorization = 'authorization' . $AccessTokenApi;
                    $content = array('content-type'=> 'application/json', 'authorization' => $AccessTokenApi);


                    $data = array(
                        "cclw" =>  $CCLW,
                        "amount" => $order->get_total(),
                        "codOper" => $meta_tx,
                        "description" => "Woocommerce Order Nro." . $order_id,

                    );

                    error_log(print_r('PagueloFacil_capture_data ' . json_encode($data), true));

                    $capture = $this->curl_paguelofacil("rest/processTx/CAPTURE", json_encode($data), $content);

                    $response = ($capture['headerStatus']['code'] != 200) ? 'Captura error ' . $capture['headerStatus']['code'] . ' : ' . $capture['headerStatus']['description'] : $capture['data'];

                    if ($capture['headerStatus']['code'] != 200) {
                        $order->update_status('on-hold', __($response, 'paguelofacil'));
                        //wc_add_notice( __($response , 'paguelofacil')  , 'error' );
                        return false;
                    }


                    if ($response['status'] == 0) {

                        //$order->add_order_note(__('PagueloFacil Captura Decline codOper '.$response['codOper'] .' Razón '.$response['messageSys'], 'paguelofacil')); 
                        wc_add_notice(__('Error al capturar de pago:', 'paguelofacil') . $response['messageSys'], 'error');
                        $order->update_status('on-hold', __("Motivo de rechazo de la captura del pago " . $response['messageSys'] . " codOper " . $response['Oper'], 'paguelofacil'));

                        return false;
                    } else {
                        update_post_meta($order->get_id(), '_pf_transaction_type', sanitize_text_field("CAPTURE"));
                        update_post_meta($order->get_id(), '_pf_transaction_id', sanitize_text_field($response['codOper']));

                        $order->set_transaction_id($response['codOper']);
                        $order->add_payment_token($response['codOper']);

                        $order->payment_complete($response['codOper']);
                        return $order->add_order_note(__('PagueloFacil Captura Monto ' . $response['totalPay'] . ' codOper ' . $response['codOper'] . ' Razón ' . $response['messageSys'], 'paguelofacil'));
                    }
                }
            }



            /**
             * PagueloFacil 
             * REFUND PAYMENT PAGUELOFACIL
             * */
            public function process_refund($order_id, $amount = null, $reason = '')
            {

                //OBTENEMOS LA ORDEN DE WOOCOMMERCE
                $order = new WC_Order($order_id);

                // Do your refund here. Refund $amount for the order with ID $order_id
                $meta_method = $order->get_meta('_pf_payment_method', true);
                $meta_tx_type = $order->get_meta('_pf_transaction_type', true);

                error_log(print_r('PagueloFacil_meta_method' . json_encode($meta_method), true));

                if (($meta_method == "VISA" || "MASTERCARD") && $amount > 0) {

                    $CCLW = ('yes' == $this->testmode) ? $this->cclw_demo : $this->cclw;
                    $AccessTokenApi = ('yes' == $this->testmode) ? $this->token_demo : $this->token_live;

                    $meta_tx_id = $order->get_meta('_pf_transaction_id', true);
                    $TransactionId = (null != $order->get_transaction_id()) ? $order->get_transaction_id() : $meta_tx_id;

                    $authorization = 'authorization' . $AccessTokenApi;
                    $CCLW = ('yes' == $this->testmode) ? $this->cclw_demo : $this->cclw;
                    $content = array('content-type'=> 'application/json', 'authorization' => $AccessTokenApi);
                    $reason = ($reason == '') ? "Woocommerce Reembolso" : $reason;

                    $data = array(
                        "cclw" =>  $CCLW,
                        "amount" => $amount,
                        "codOper" => $TransactionId,
                        "description" => $reason,

                    );

                    error_log(print_r('PagueloFacil_refund_data ' . json_encode($data), true));

                    $uri = ('AUTH' == $meta_tx_type) ? 'rest/processTx/REVERSE_AUTH' : 'rest/processTx/REVERSE_CAPTURE';
                    $refund = $this->curl_paguelofacil($uri, json_encode($data), $content);

                    $response = ($refund['headerStatus']['code'] != 200) ? $refund['headerStatus']['code'] . ' : ' . $refund['headerStatus']['description'] : $refund['data'];



                    if ($refund['headerStatus']['code'] != 200) {
                        $order->add_order_note(__('PagueloFacil Reembolso error ' . $response, 'paguelofacil'));

                        wc_add_notice(__('Pago reembolso error:', 'paguelofacil') . $response, 'error');
                        return false;
                    }



                    if ($response['status'] == 0) {

                        $order->add_order_note(__('PagueloFacil Reembolso error:' . $response['totalPay'] . ' codOper ' . $response['codOper'] . ' Razón ' . $response['messageSys'], 'paguelofacil'));
                        wc_add_notice(__('Pago reembolso error:', 'paguelofacil') . $response['messageSys'], 'error');

                        return false;
                    }

                    $order->add_order_note(__('PagueloFacil Reembolso Monto ' . $response['totalPay'] . ' codOper ' . $response['codOper'] . ' Razón ' . $reason, 'paguelofacil'));
                } else {

                    wc_add_notice(__('Error: ', 'paguelofacil') . __('Reembolso no es posible', 'paguelofacil'), 'error');

                    $order->add_order_note(__('PagueloFacil Reembolso no es posible, recuerde que el monto debe ser mayor a $0.00', 'paguelofacil'));

                    return false;
                }

                return true;
            }



            /**
             * PagueloFacil Response
             * NORMALIZE PARAMETERS FOR RESPONSE
             * */
            function parameters_paguelofacil_response($response)
            {

                error_log(print_r('PagueloFacil_log ' . json_encode($response), true));

                if ($response["TotalPay"] <> NULL) {

                    $response_update = array();

                    $response_update['TotalPagado'] = isset($response['TotalPay']) ? $response['TotalPay'] : null;
                    $response_update['Order'] = isset($response['Order']) ? $response['Order'] : null;
                    $response_update['Fecha'] = isset($response['Date']) ? $response['Date'] :  $response['Fecha'];
                    $response_update['Hora'] = isset($response['Date']) ? $response['Date'] :  $response['Hora'];

                    $response_update['Tipo'] = isset($response['Type']) ? $response['Type'] : null;
                    $response_update['Tipo'] = ($response_update['Tipo'] == null && $response['Status'] == null && substr($response['CodOper'], 0, 2) == 'PP') ? 'CASH' :  $response_update['Tipo'];



                    $response_update['Oper'] = isset($response['Oper']) ? $response['Oper'] : $response['CodOper'];

                    $response_update['Usuario'] = isset($response['User']) ? $response['User'] : null;
                    $response_update['Email'] = isset($response['Email']) ? $response['Email'] : null;

                    $response_update['Estado'] = isset($response['Status']) ? $response['Status'] : null;
                    $response_update['Estado'] = ($response_update['Estado'] == null && $response['Status'] == null && substr($response['CodOper'], 0, 2) == 'PP') ? 'Aprobada' :  $response_update['Estado'];

                    $response_update['StatusCode'] = isset($response['StatusCode']) ? $response['StatusCode'] : null;
                    $response_update['Razon'] = isset($response['msg']) ? $response['msg'] : null;
                    $response_update['RequestPay'] = isset($response['RequestPay']) ? $response['RequestPay'] : null;
                    $response_update['CDSC'] = isset($response['CDSC']) ? $response['CDSC'] : null;


                    switch ($response_update['Estado']) {
                        case "Pending":
                            $response_update['Estado'] = 'Pendiente';
                            break;
                        case "Approved":
                            $response_update['Estado'] = 'Aprobada';
                            break;
                        case "Declined":
                            $response_update['Estado'] = 'Denegada';
                            break;
                    }

                    $response = ($response_update != '') ? $response_update : $response;
                }

                error_log(print_r('PagueloFacil_log_response ' . json_encode($response), true));

                return $response;
            }


            /**
             * Check for Paguelo Facil IPN Response
             * CHEKEA LA RESPUESTA DEL SERVIDOR DE PAGUELOFACIL
             * */
            function check_paguelofacil_resquest()
            {

                global $woocommerce;

                $response['Estado'] =  (isset($_GET['Estado'])) ? sanitize_text_field($_GET['Estado']) : null;
                $response['Status'] = (isset($_GET['Status'])) ? sanitize_text_field($_GET['Status']) : null;

                $response['TotalPay'] = (isset($_GET['TotalPay'])) ? sanitize_text_field($_GET['TotalPay']) : null;
                $response['TotalPagado'] = (isset($_GET['TotalPagado'])) ? sanitize_text_field($_GET['TotalPagado']) : null;

                $response['Order'] = (isset($_GET['Order'])) ? sanitize_text_field($_GET['Order']) : null;

                $response['Fecha'] = (isset($_GET['Fecha'])) ? sanitize_text_field($_GET['Fecha']) : null;
                $response['Hora'] = (isset($_GET['Hora'])) ? sanitize_text_field($_GET['Hora']) : null;
                $response['Date'] = (isset($_GET['Date'])) ? sanitize_text_field($_GET['Date']) : null;

                $response['Type'] = (isset($_GET['Type'])) ? sanitize_text_field($_GET['Type']) : null;
                $response['Tipo'] = (isset($_GET['Tipo'])) ? sanitize_text_field($_GET['Tipo']) : null;

                $response['Oper'] = (isset($_GET['Oper'])) ? sanitize_text_field($_GET['Oper']) : null;
                $response['CodOper'] = (isset($_GET['CodOper'])) ? sanitize_text_field($_GET['CodOper']) : null;

                $response['User'] = (isset($_GET['User'])) ? sanitize_text_field($_GET['User']) : null;
                $response['Email'] = (isset($_GET['Email'])) ? sanitize_text_field($_GET['Email']) : null;

                $response['msg'] = (isset($_GET['msg'])) ? sanitize_text_field($_GET['msg']) : null;
                $response['Razon'] = (isset($_GET['Razon'])) ? sanitize_text_field($_GET['Razon']) : null;

                $response['StatusCode'] = (isset($_GET['StatusCode'])) ? sanitize_text_field($_GET['StatusCode']) : null;

                $response['RequestPay'] = (isset($_GET['RequestPay'])) ? sanitize_text_field($_GET['RequestPay']) : null;



                $response = $this->parameters_paguelofacil_response($response);

                if (isset($response['Order'])) {
                    $Order = $response['Order'];
                } else {
                    $Order = $woocommerce->session->paguelofacil_order_id;
                }

                //OBTENEMOS LA ORDEN DE WOOCOMMERCE
                $order = new WC_Order($Order);


                if (isset($response['Estado']) && $response['Estado'] <> '') {

                    $woocommerce->session->paguelofacil_type = $response['Tipo'];
                    $woocommerce->session->paguelofacil_oper = $response['Oper'];

                    switch ($response['Estado']) {
                        case "Pendiente":
                            $order->add_order_note(__('PagueloFacil pago pendiente', 'paguelofacil')); // Mark as on-hold were awaiting the PAGOCASH O CRIPTO
                            $order->update_status('on-hold', __("Esperando " . $response['Tipo'] . " pago codOper " . $response['Oper'], 'paguelofacil'));
                            $order->set_transaction_id($response['Oper']);
                            $order->add_payment_token($response['Oper']);

                            update_post_meta($order->get_id(), '_pf_transaction_id', sanitize_text_field($response['Oper']));
                            update_post_meta($order->get_id(), '_pf_payment_method',  sanitize_text_field($response['Tipo']));
                            update_post_meta($order->get_id(), '_pf_total_pagado',  sanitize_text_field($response['TotalPagado']));

                            do_action('woocommerce_email_before_order_table', 'add_order_email_instructions_lk', 10, 3);
                            remove_action('woocommerce_email_before_order_table', 'add_order_email_instructions_lk', 10, 3);

                            // Return thankyou redirect
                            $url = $this->get_return_url($order);
                            wp_redirect($url, 303);
                            exit();

                            break;
                        case "Aprobada":

                            // Payment completed	
                            $meta_method = $order->get_meta('_pf_payment_method', true);
                            if ($meta_method == "CASH" && $meta_method == "CRYPTO") {

                                $total_pagado = $order->get_meta('_pf_total_pagado', true);
                                $total_pagado = $total_pagado + $response['TotalPagado'];
                                update_post_meta($order->get_id(), '_pf_total_pagado',  sanitize_text_field($total_pagado));

                                $pay_result = ($total_pagado == $order->get_total()) ? 'COMPLETED' : 'PARCIAL';


                                if ($pay_result == 'COMPLETED') {

                                    $order->payment_complete($response['Oper']);
                                } else {

                                    $order->add_order_note(__('Pago Recibido  ' . $pay_result . ' de ' . $response['TotalPagado'], 'paguelofacil'));
                                }
                            } else {

                                update_post_meta($order->get_id(), '_pf_total_pagado',  sanitize_text_field($response['TotalPagado']));
                                update_post_meta($order->get_id(), '_pf_transaction_id', sanitize_text_field($response['Oper']));
                                update_post_meta($order->get_id(), '_pf_paguelofacil_status',  sanitize_text_field($response['Estado']));
                                update_post_meta($order->get_id(), '_pf_payment_method',  sanitize_text_field($response['Tipo']));
                                $order->add_payment_token($response['Oper']);

                                $substring_oper = ('yes' == $this->testmode) ? substr($response['Oper'], 0, 12) : substr($response['Oper'], 0, 3);
                                $value_oper = ('yes' == $this->testmode) ? 'SANDBOX_LKA-' : 'LKA-';

                                if ($response['Oper'] != '' && $substring_oper == $value_oper) {

                                    update_post_meta($order->get_id(), '_pf_transaction_type', sanitize_text_field("AUTH"));
                                    $order->add_order_note(__('Pago Pre-Autorizado' . $pay_result . ' de ' . $response['TotalPagado'], 'paguelofacil'));
                                    $order->add_order_note(__('PagueloFacil pago pendiente', 'paguelofacil')); // Mark as on-hold were awaiting CAPTURE
                                    $order->update_status('on-hold', __("Esperando " . $response['Tipo'] . " payment codOper " . $response['Oper'], 'paguelofacil'));
                                    $order->set_transaction_id($response['Oper']);
                                    $order->add_payment_token($response['Oper']);
                                } else {

                                    $order->add_order_note(__('PagueloFacil Pago Completado Razón: ' . $response['Razon'] . ' CodOper: ' . $response['Oper'] . ' Monto: ' . $response['TotalPagado'], 'paguelofacil'));
                                    $order->payment_complete($response['Oper']);
                                }
                            }


                            // Return thankyou redirect
                            $url = $this->get_return_url($order);
                            wp_redirect($url, 303);
                            exit();

                            break;
                        case "Denegada":

                            error_log(print_r('PagueloFacil_log decline: Payment not complete', true));
                            wc_add_notice(__('Error: ', 'paguelofacil') . $response['Razon'], 'error');

                            $order->add_order_note(__('PagueloFacil pago declinado Razón: ' . $response['Razon'] . ' CodOper' . $response['Oper'], 'paguelofacil'));

                            $order->update_status('failed', __('Pago declinado.', 'wptut'));


                            $url = get_permalink(wc_get_page_id('checkout'));
                            wp_redirect($url);
                            exit();

                            break;
                    }

                    exit();
                }
            }

            /**
             * Get LA url TEST O LIVE ONSITE Y OFFSITE
             * */
            function get_url_process($endpoint = "LinkDeamon.cfm", $type = "REST")
            {


                $paguelofacil_adr = ('yes' == $this->testmode) ? "sandbox" : "secure";
                $url = "https://" . $paguelofacil_adr . ".paguelofacil.com/" . $endpoint;

                return $url;
            }




            /**
             * Get PagueloFacil Args for passing to PAGUELOFACIL OFFSITE
             * */
            function get_paguelofacil_offsite_args($order)
            {

                global $woocommerce;

                $CCLW = ('yes' == $this->testmode) ? $this->cclw_demo : $this->cclw;
                $uri = ('yes' == $this->type_auth) ? 'LinkDeamon.cfm/AUTH' : 'LinkDeamon.cfm';
                $CMTN = $order->get_total();
                $URLOK = $this->get_return_url($order);
                $URLKO = $order->get_cancel_order_url();

                $mensaje = sanitize_url($_SERVER['HTTP_ORIGIN']) . ' Orden Nro.' . $order->get_id();

                $pfCF = hexdec('[{"id":"wooOrderId","nameOrLabel":"Id de Orden en Woocommerce","value":"' . $order->get_id() . '"}]');

                $return_url = (isset($this->uri_store) && $this->uri_store != "") ? bin2hex($this->uri_store) : bin2hex($this->return_url);

                $data = array(
                    'CCLW' => $CCLW,
                    'CMTN' => $CMTN,
                    'CDSC' => $mensaje,
                    'Channel' => 'WOOCOMMERCE',
                    'Order' => $order->get_id(),
                    "PF_CF" => $pfCF,
                    'RETURN_URL' => $return_url
                );


                $result = $this->curl_paguelofacil($uri, http_build_query($data, '', '&'));

                return $result['data']['url'];
            }

            /**
             * REST PagueloFacil 
             * */
            function curl_paguelofacil($endpoint, $data, $content = array('Content-Type: application/x-www-form-urlencoded', 'Accept: */*'))
            {


                $url = $this->get_url_process($endpoint);
                $args =  array(
                    'method'      => 'POST',
                    'timeout'     => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'headers'     => $content,
                    'body'        => $data,
                );

                $result = wp_remote_post($url, $args);
                $body = json_decode($result['body'], true);

                error_log(print_r('PagueloFacil_log ' . json_encode($body), true));

                if ($result === FALSE) {

                    if ($this->debug == 'yes')
                        error_log(print_r('PagueloFacil_log Datos invalidos y/o error de conexión' . $result, true));

                    wc_add_notice(__('Error: ', 'paguelofacil') . __('Datos invalidos y/o error de conexión revise su FIREWALL', 'paguelofacil'), 'error');
                    curl_close($ch);
                    return;
                }


                return $body;
            }


            /**
             * Cash y CRIPTO INSTRUCCIONES OFFSITE
             * */
            function add_order_email_instructions_lk($order)
            {

                global $woocommerce;

                $method = $woocommerce->session->paguelofacil_type;

                $this->pp_monto = number_format(ceil($order->get_total()), 2, ',', ' ');


                switch ($method) {
                    case "CASH":

                        $this->pp_code = $woocommerce->session->paguelofacil_oper;

                        echo $this->msg_cash;
                        break;
                    case "CRYPTO":
                        echo $this->msg_crypto;
                        break;
                }
            }


            function generate_paguelofacil_html($order)
            {

                global $woocommerce;

                $order = new WC_Order($order);
                $this->pp_monto = ceil($order->get_total());
                $message = null;
                $method = $woocommerce->session->paguelofacil_type;

                switch ($method) {
                    case "CASH":

                        $this->pp_code = $woocommerce->session->paguelofacil_oper;
                        echo $this->msg_cash;
                        break;
                    case "CRYPTO":
                        echo $this->msg_crypto;
                        break;
                }

                return $message;
            }

            /**
             * receipt_page PARA CARGAR FORMULARIO QUE SE ENVIA AUTOMATICAMENTE EPARA PAGO ONSITE
             * */
            function receipt_page($order)
            {

                printf( __($this->generate_paguelofacil_html($order)));
            }


            /*
     * thank you page
     */
            public function thankyou_page($order)
            {
                global $woocommerce;


                echo $this->generate_paguelofacil_html($order);
            } //function thankyou_page



            /**
             * PROCESO DE PAGO DE PagueloFacil ONSITE Y OFFSITE
             * */
            function process_payment($order_id)
            {

                global $woocommerce;

                $order = wc_get_order($order_id);

                $woocommerce->session->paguelofacil_order_id = $order_id;

                $paguelofacil_adr = $this->get_url_process();

                $paguelofacil_url = $this->get_paguelofacil_offsite_args($order);

                return array(
                    'result' => 'success',
                    'redirect' => $paguelofacil_url
                );
            }

    
        }

        /**
         * Add the gateway to woocommerce
         * */
        function add_paguelofacil_gateway($methods)
        {
            $methods[] = 'woocommerce_paguelofacil';
            return $methods;
        }

        add_filter('woocommerce_payment_gateways', 'add_paguelofacil_gateway');
    }
}
