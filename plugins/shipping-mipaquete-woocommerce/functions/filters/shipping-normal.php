<?php
if ( ! class_exists( 'MipaqueteShippingMethod' ) ) {
    class MipaqueteShippingMethod extends WC_Shipping_Method {
        /**
         * Constructor for your shipping class
         *
         * @access public
         * @return void
         */
        public function __construct( $instanceId = 0 ) {
            $this->id                 = 'mipaquete_shipping_normal';
            $this->method_title       = __( 'mipaquete envío normal' );
            $this->method_description = __( 'Envíos normales' );

            $this->enabled            = "yes";
            $this->title              = "mipaquete.com envío ";

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
        public function init()
        {
            // Load the settings API
            // This is part of the settings API. Override the method to add your own settings
            $this->initFormFields();
            $this->init_settings(); // This is part of the settings API. Loads settings you previously init.
            
            // Save settings in admin if you have any defined
            add_action('woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options'));
        }

        public function initFormFields()
        {
            $this->instance_form_fields = array(
                'price_personalized' => array(
                    'title' => __( 'Configurar tarifa de envío personalizada', 'mipaquete' ),
                    'type' => 'number',
                    'description' => __('Entendemos que para facilitar procesos de venta
                    algunas veces quieres configurar una única
                    tarifa de envío, si deseas tener una tarifa estándar
                    puedes hacerlo (Una vez generes el envío te cobraremos el valor real)',
                        'mipaquete'),
                    'desc_tip' => true,
                ),
                'free_shipping' => array(
                    'title' => __('¿Deseas que los envíos sean gratuitos para el cliente?'),
                    'type'        => 'select',
                    'class'       => 'wc-enhanced-select',
                    'description' => __('Habilitar el envío gratis para mis clientes'),
                    'desc_tip' => true,
                    'options'     => array(
                        '0' => __('Selecciona opción'),
                        '2' => __('SI'),
                        '3' => __('NO')
                    )
                ),
                'free_shipping_cost_total' => array(
                    'title' => __( 'Envío gratuito a partir de un valor de venta en especifico(Debes tener habilitada la opción si, en envío gratuito)', 'mipaquete' ),
                    'type' => 'number',
                    'description' => __( 'Debes tener habilitada la opción si en el envío gratuito, por defecto el valor será cero ', 'mipaquete' ),
                    'desc_tip' => true,
                    'default'  => 0
                ),
            );
        }
        public function is_available( $package ){
            return true;
        }
        /**
         * calculate_shipping function.
         *
         * @access public
         * @param mixed $packagect
         * @return void
         */
        public function calculate_shipping( $package = array() ) {
            $valueSelect = get_option( 'mpq_value_select' );
            $requestPickup = get_option( 'mpq_pickup' );
            $pricePersonalized = $this->get_option( 'price_personalized' );
            $freeShipping = $this->get_option( 'free_shipping' );
            $freeShippingCostTotal = $this->get_option( 'free_shipping_cost_total' );
            
            $url = getUrlApi() . 'quoteShipping';
            global $woocommerce, $post;
            $items = $woocommerce->cart->get_cart();
            $calculateDimensions = calculateDimensions($items);
            $height = $calculateDimensions['height'];
            $width = $calculateDimensions['width'];
            $length = $calculateDimensions['length'];
            $weight = $calculateDimensions['weight'];
            $totalValorization = $calculateDimensions['total_valorization'];
            $cityDestination  = $package['destination']['city'];
            
            $quantityCart = $woocommerce->cart->cart_contents_count;
            foreach ($items as $item) {
                $_product =  wc_get_product( $item['data']->get_id());
                $productId = $_product->get_id();
            }
            
            //Loop through each item from the cart
            $customer = new WC_Customer(0, true);
            $location = $customer->get_shipping_state();
            $infoUserLocationCode = ReturnGetUser();
            $data = array("originLocationCode" => "$infoUserLocationCode[2]",
            "destinyLocationCode" => "$cityDestination",
            "height" => $height,
            "width" => $width,
            "length" => $length,
            "weight" => $weight,
            "quantity" => 1,
            "declaredValue" => $totalValorization,
            );
            $apik = returnGenerateApiKey();
            $dataString = json_encode($data);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json",
            "session-tracker:a0c96ea6-b22d-4fb7-a278-850678d5429c",
            "apikey:" .$apik,
        ));
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            $resultData = json_decode($result, true);
            $totalData = (int)count($resultData);
            $validationShippingOption = !empty($cityDestination) && $freeShippingCostTotal != 2;
            $validationFreeShippingCost = !empty($cityDestination) && $freeShippingCostTotal == 2;
            curl_close($ch);
            if ($validationShippingOption && $valueSelect == 1) {
                usort($resultData, fn($a, $b) => $a['shippingCost'] <=> $b['shippingCost']);
            }
            if ($validationShippingOption && $valueSelect == 2) {
                usort($resultData, fn($a, $b) => $a['shippingTime'] <=> $b['shippingTime']);
            }
            
            if ($validationShippingOption && $valueSelect == 3) {
                usort($resultData, fn($a, $b) => $a['score'] < $b['score']);

            }
            if ($validationFreeShippingCost && $valueSelect == 1) {
                usort($resultData, fn($a, $b) => $a['shippingCost'] <=> $b['shippingCost']);
            }
            if ($validationFreeShippingCost && $valueSelect == 2) {
                usort($resultData, fn($a, $b) => $a['shippingTime'] <=> $b['shippingTime']);
            }
            if ($validationFreeShippingCost && $valueSelect == 3) {
                usort($resultData, fn($a, $b) => $a['score'] < $b['score']);
            }
            $arrayPriceMin = $resultData[0]['shippingCost'];
            if ($pricePersonalized != '') {
                $arrayPriceMin = $pricePersonalized;
            }
            if ($pricePersonalized == 0) {
                $arrayPriceMin = $resultData[0]['shippingCost'];
            }

            if ($totalData > 0 && $freeShipping != 2
            && $height <= 200
            && $width <= 200
            && $length <= 200
            && $weight <= 150) {
                // Register the rate
                $rate = array(
                    'id' => $this->id,
                    'label' => $this->title ,
                    'cost' => $arrayPriceMin,
                    'calc_tax' => 'per_item'
                );
            } elseif ($totalData > 0 &&
            $freeShippingCostTotal == 0 &&
            $freeShipping == 2) {
                $rate = array(
                    'id' => $this->id,
                    'label' => 'Envío gratis a través de mi paquete',
                    'calc_tax' => 'per_item'
                );
            } elseif ($totalData > 0 &&
            (int)$woocommerce->cart->subtotal >= $freeShippingCostTotal &&
            $freeShipping == 2) {
                $rate = array(
                    'id' => $this->id,
                    'label' => 'Envío gratis a través de mi paquete',
                    'calc_tax' => 'per_item'
                );
            } elseif ($totalData > 0 &&
            (int)$woocommerce->cart->subtotal < $freeShippingCostTotal &&
                $freeShipping == 2) {
                $rate = array(
                    'id' => $this->id,
                    'label' => $this->title ,
                    'cost' => $arrayPriceMin,
                    'calc_tax' => 'per_item'
                );
            } else {
                $rate = "No hay métodos de envío disponibles";
            }
            $this->add_rate( $rate );
        }
    }
}
?>