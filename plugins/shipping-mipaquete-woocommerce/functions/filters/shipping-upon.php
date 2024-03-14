<?php
if ( ! class_exists( 'MipaqueteShippingMethodUponDelivery' ) ) {
    class MipaqueteShippingMethodUponDelivery extends WC_Shipping_Method {
        /**
         * Constructor for your shipping class
         *
         * @access public
         * @return void
         */
        public function __construct( $instanceId = 0 ) {
            $this->id = 'mipaquete_shipping_upon_delivery';
            $this->method_title       = __( 'mipaquete.com envío contraentrega' );
            $this->method_description = __( 'Envíos contraentrega' );

            $this->enabled = "yes";
            $this->title = "mipaquete.com envío contraentrega";

            $this->instance_id = absint( $instanceId );

            $this->supports  = array(
                'shipping-zones',
                'instance-settings',
                'instance-settings-modal',
                );

            $this->init();
        }

        /**
         * Init your settings
         *
         * @access public
         * @return void
         */
        public function init() {
            // Load the settings API
            // This is part of the settings API. Override the method to add your own settings
            $this->initFormFields();
            $this->init_settings(); //   This is part of the settings API. Loads settings you previously init.

            // Save settings in admin if you have any defined
            add_action('woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options'));
        }
        
        public function initFormFields() {

            $this->instance_form_fields = array(
                'title_collection' => array(
                    'title'       => __( 'Nota' ),
                    'type'        => 'title',
                    'description' => __( 'Ten presente que para procesar tus envíos con
                    pago contra entrega no necesitas tener saldo disponible,
                    ya que del recaudo a realizar descontaremos el valor del envío.
                    Esto aplica para todas las transportadoras excepto para ENVÍA y
                    SERVIENTREGA, con las cuales si debes tener saldo disponible para procesar tus envíos.')
                ),
            );
        }
        /**
         * calculate_shipping function.
         *
         * @access public
         * @param mixed $package
         * @return void
         */
        public function calculate_shipping( $package = array() ) {
            $requestPickupUpon = get_option( 'mpq_pickup' );
            $valueSelectUpon = get_option( 'mpq_value_select' );
            $urlUpon = getUrlApi() . 'quoteShipping';
            global $woocommerce, $post;
            $itemsUpon = $woocommerce->cart->get_cart();
            $calculateDimensionsUpon = calculateDimensions($itemsUpon);
            $heightUpon = $calculateDimensionsUpon['height'];
            $widthUpon = $calculateDimensionsUpon['width'];
            $lengthUpon = $calculateDimensionsUpon['length'];
            $weightUpon = $calculateDimensionsUpon['weight'];
            $totalValorizationUpon = $calculateDimensionsUpon['total_valorization'];
            $countryUpon = $package['destination']['country'];
            $stateDestinationUpon = $package['destination']['state'];
            $cityDestinationUpon  = $package['destination']['city'];
            $quantityCartUpon = $woocommerce->cart->cart_contents_count;
            
            foreach ($itemsUpon as $itemUpon) {
                $_productUpon =  wc_get_product( $itemUpon['data']->get_id());
            }
            $_product_idUpon = $_productUpon->get_id();
            $customerUpon = new WC_Customer(0, true);
            $locationUpon = $customerUpon->get_shipping_state();
            $info_user_locationCodeUpon = ReturnGetUser();
            $dataUpon = array("originLocationCode" => "$info_user_locationCodeUpon[2]",
            "destinyLocationCode" => "$cityDestinationUpon",
            "height" => $heightUpon,
            "width" => $widthUpon,
            "length" => $lengthUpon,
            "weight" => $weightUpon,
            "quantity" => 1,
            "declaredValue" => $totalValorizationUpon,
            "saleValue" => (int)$woocommerce->cart->total,
        );
            $apik = returnGenerateApiKey();
            $data_stringUpon = json_encode($dataUpon);

            
            $chUpon = curl_init($urlUpon);
            curl_setopt($chUpon, CURLOPT_POSTFIELDS, $data_stringUpon);
            curl_setopt($chUpon, CURLOPT_HTTPHEADER, array("Content-Type:application/json",
            "session-tracker:a0c96ea6-b22d-4fb7-a278-850678d5429c",
            "apikey:" .$apik,
        ));

            curl_setopt($chUpon, CURLOPT_RETURNTRANSFER, true);
            $resultUpon = curl_exec($chUpon);
            $resultDataUpon = json_decode($resultUpon, true);
            $totalDataUpon = (int)count($resultDataUpon);
            curl_close($chUpon);
            /* criterios de busqueda*/
            if (!empty($cityDestinationUpon) && $valueSelectUpon == 1) {
                array_multisort(array_column($resultDataUpon, 'shippingCost'), SORT_ASC, $resultDataUpon);
            }
            if (!empty($cityDestinationUpon) && $valueSelectUpon == 2 ) {
                array_multisort(array_column($resultDataUpon, 'shippingTime'), SORT_ASC, $resultDataUpon);
            }
            
            if (!empty($cityDestinationUpon) && $valueSelectUpon == 3 ) {
                array_multisort(array_column($resultDataUpon, 'score'), SORT_DESC, $resultDataUpon);
            }
            if (!empty($resultDataUpon[0]['collectionCommissionWithRate'])
            && $totalDataUpon > 0
            && $heightUpon <= 200
            && $widthUpon <= 200
            && $lengthUpon <= 200
            && $weightUpon <= 150) {
                $array_price_min1 = $resultDataUpon[0]['shippingCost'] + $resultDataUpon[0]['collectionCommissionWithRate'];
                $rate = array(
                    'id' => $this->id,
                    'label' => $this->title,
                    'cost' => $array_price_min1,
                    'calc_tax' => 'per_item'
                );
            }
            /*fin criterios de busqueda */
            else {
                $rate = "No hay mÃ©todos de envÃ­o disponibles";
            }
            
            $this->add_rate( $rate );
        }
    }
}
?>