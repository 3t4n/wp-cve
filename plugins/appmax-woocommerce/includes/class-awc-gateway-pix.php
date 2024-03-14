<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AWC_Gateway_Pix class.
 *
 * @extends WC_Payment_Gateway
 */
class AWC_Gateway_Pix extends WC_Payment_Gateway
{
    public function __construct()
    {
        $this->id = 'appmax-pix';
        $this->icon = '';
        $this->has_fields = true;
        $this->method_title = __( 'Appmax - Pix', 'appmax-woocommerce' );
        $this->method_description = __( 'Plataforma de vendas online para produtores e afiliados.', 'appmax-woocommerce' );

        $this->supports = ['products'];

        $this->init_form_fields();

        $this->init_settings();

        $this->title = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );
        $this->awc_api_key = $this->get_option( 'awc_api_key' );
        $this->awc_order_call_center = $this->get_option( 'awc_order_call_center' );
        $this->debug = $this->get_option( 'debug' );

        if ( $this->debug === 'yes' ) {
            $this->log = new WC_Logger();
        }

        $this->awc_payment = new AWC_Process_Payment( $this );

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
    }

    /**
     * Check if the gateway is available to take payments.
     *
     * @return bool
     */
    public function is_available()
    {
        return parent::is_available() && ! empty( $this->awc_api_key );
    }

    public function frontend_scripts()
    {
        if ( is_checkout() ) {
            $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

            wp_enqueue_style( 'awc_checkout_css', plugins_url( AWC_PLUGIN_ROOT_PATH . '/assets/css/my-styles/awc_checkout' . $suffix . '.css' ), array(), AppMax_WC::VERSION );

            wp_enqueue_script('awc_bootstrap_js', plugins_url(AWC_PLUGIN_ROOT_PATH . '/assets/js/bootstrap/bootstrap' . $suffix . '.js'), false, false, true);
            wp_enqueue_script('awc_masked_input_js', plugins_url(AWC_PLUGIN_ROOT_PATH . '/assets/js/masked-input/jquery.maskedinput' . $suffix . '.js'), array('jquery'), false, true);
            wp_enqueue_script('awc_pix_js', plugins_url(AWC_PLUGIN_ROOT_PATH . '/assets/js/my-scripts/awc_pix' . $suffix . '.js'), array('jquery'), AppMax_WC::VERSION, true);
        }
    }

    /**
     * Setting fields plugin.
     */
    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __( 'Habilitar/Desabilitar', 'appmax-woocommerce' ),
                'type' => 'checkbox',
                'label' => __( 'Ativar Appmax - Pix', 'appmax-woocommerce' ),
                'default' => 'yes'
            ),
            'title' => array(
                'title' => __( 'Título', 'appmax-woocommerce' ),
                'type' => 'text',
                'description' => __( 'Título que irá aparecer no gateway no checkout da sua loja.', 'appmax-woocommerce' ),
                'desc_tip' => true,
                'default' => __( 'Appmax - Pix', 'appmax-woocommerce' ),
            ),
            'description' => array(
                'title' => __( 'Descrição', 'appmax-woocommerce' ),
                'type' => 'textarea',
                'description' => __( 'Descrição que irá aparecer no gateway no checkout da sua loja.', 'appmax-woocommerce' ),
                'desc_tip' => true,
                'default' => __( 'Pagamento com Pix', 'appmax-woocommerce' ),
            ),
            'settings' => array(
                'title' => __( 'Configurações', 'appmax-woocommerce' ),
                'type' => 'title',
            ),
            'awc_api_key' => array(
                'title' => __( 'Appmax API Key', 'appmax-woocommerce' ),
                'type' => 'text',
                'description' => __( 'Por favor digite sua chave de API da APPMAX. Esta chave é necessária para processar os pagamentos e notificações.', 'appmax-woocommerce' ),
                'default' => '',
                'custom_attributes' => array(
                    'required' => 'required',
                ),
            ),
            'awc_order_call_center' => array(
                'title' => __( 'Receber Pedidos de CallCenter', 'appmax-woocommerce' ),
                'type' => 'select',
                'class' => 'wc-enhanced-select',
                'default' => 'OrderIntegrated',
                'custom_attributes' => array(
                    'required' => 'required',
                ),
                'options' => array(
                    'OrderIntegrated' => 'Quando estiver integrado',
                    'OrderPaid' => 'Quando estiver pago',
                ),
            ),
            'debug' => array(
                'title' => __( 'Debug Log', 'appmax-woocommerce' ),
                'type' => 'checkbox',
                'label' => __( 'Habilitar log', 'appmax-woocommerce' ),
                'default' => 'yes',
                'description' => sprintf( __( 'Log Appmax - Pix. Você pode verificar o log em %s', 'appmax-woocommerce' ), '<a href="' . esc_url( admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . esc_attr( $this->id ) . '-' . sanitize_file_name( wp_hash( $this->id ) ) . '.log' ) ) . '">' . __( 'System Status &gt; Logs', 'appmax-woocommerce' ) . '</a>' ),
            ),
        );
    }

    /**
     * Payment fields plugin.
     */
    public function payment_fields()
    {
        if ( $this->get_description() ) {
            echo wp_kses_post( wpautop( wptexturize( $this->get_description() ) ) );
        }

        $path_form = 'views/checkout/pix/form-appmax.php';

        $variables = array(
            'display_script_payment' => AWC_Form_Payment::awc_display_script_payment_pix(),
        );

        wc_get_template( $path_form, $variables, 'appmax/woocommerce/', AppMax_WC::awc_get_templates_path() );
    }

    /**
     * @return bool
     */
    public function validate_fields()
    {
        foreach ($_POST as $key => $item) {
            $_POST[$key] = AWC_Helper::awc_clear_input( $item );
        }

        AWC_Validation::awc_unset_variables_post( $_POST, AWC_Post_Payment::awc_get_structure_post_billet() );
        AWC_Validation::awc_unset_variables_post( $_POST, AWC_Post_Payment::awc_get_structure_post_credit_card() );

        list( $validation, $message ) = AWC_Validation::awc_validation_fields( $_POST, AWC_Post_Payment::awc_get_structure_post_pix() );

        if ( $validation ) {
            wc_add_notice( $message, 'error' );
            return false;
        }

        return true;
    }

    /**
     * @param int $order_id
     * @return array|void
     * @throws Exception
     */
    public function process_payment( $order_id ) {
        return $this->awc_payment->awc_process_payment_pix( $order_id );
    }
}