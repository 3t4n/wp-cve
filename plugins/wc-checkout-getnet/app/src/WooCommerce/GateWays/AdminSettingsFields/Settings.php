<?php

namespace WcGetnet\WooCommerce\GateWays\AdminSettingsFields;

class Settings {

    public static function getnet_register_settings_fields() {
        $fields = [
            'wc_getnet_settings_environment',
            'wc_getnet_settings_seller_production_id',
            'wc_getnet_settings_client_production_id',
            'wc_getnet_settings_client_production_secret',
            'wc_getnet_settings_seller_homolog_id',
            'wc_getnet_settings_client_homolog_id',
            'wc_getnet_settings_client_homolog_secret'
        ];

        foreach ($fields as $value) {
            register_setting( 'getnet-settings', $value );
        }
    }

    public static function getnet_add_settings_fields() {
		$instance = new Settings();

        add_settings_section(
            'wc_getnet_settings_title',
            '', 
            function(){},
            'getnet-settings'
        );

        add_settings_field(
            'wc_getnet_settings_environment',
            __( 'Environment' ).' '.wc_help_tip( __( 'Selecione o ambiente, homologação ou produção.' ) ),
            [ $instance, 'getnet_settings_select_callback' ],
            'getnet-settings',
            'wc_getnet_settings_title',
            [
                'id' => 'wc_getnet_settings_environment',
                'options'        => [
                    'homolog'    => __( 'Homologação' ),
                    'production' => __( 'Produção' ),
                ]
            ]
        );
    
        add_settings_field(
            'wc_getnet_settings_seller_production_id',
            __( 'Seller ID' ).' '.wc_help_tip( __( 'Seller ID gerado na minha conta GETNET.' ) ),
            [ $instance, 'getnet_settings_text_callback' ],
            'getnet-settings',
            'wc_getnet_settings_title',
            [
                'type' => 'text',
                'id'   => 'wc_getnet_settings_seller_production_id'
            ]
        );
    
        add_settings_field(
            'wc_getnet_settings_client_production_id',
            __( 'Client ID' ).' '.wc_help_tip( __( 'Client ID gerado na minha conta GETNET.' ) ),
            [ $instance, 'getnet_settings_text_callback' ],
            'getnet-settings',
            'wc_getnet_settings_title',
            [
                'type' => 'text',
                'id'   => 'wc_getnet_settings_client_production_id'
            ]
        );
    
        add_settings_field(
            'wc_getnet_settings_client_production_secret',
            __( 'Client Secret' ).' '.wc_help_tip( __( 'Homolog Client ID gerado na minha conta GETNET.' ) ),
            [ $instance, 'getnet_settings_text_callback' ],
            'getnet-settings',
            'wc_getnet_settings_title',
            [
                'type' => 'password',
                'id'   => 'wc_getnet_settings_client_production_secret'
            ]
        );
    
        add_settings_field(
            'wc_getnet_settings_seller_homolog_id',
            __( 'Seller ID' ).' '.wc_help_tip( __( 'Homolog Seller ID gerado na minha conta GETNET.' ) ),
            [ $instance, 'getnet_settings_text_callback' ],
            'getnet-settings',
            'wc_getnet_settings_title',
            [
                'type' => 'text',
                'id'   => 'wc_getnet_settings_seller_homolog_id'
            ]
        );
    
        add_settings_field(
            'wc_getnet_settings_client_homolog_id',
            __( 'Client ID' ).' '.wc_help_tip( __( 'Homolog Client ID gerado na minha conta GETNET.' ) ),
            [ $instance, 'getnet_settings_text_callback' ],
            'getnet-settings',
            'wc_getnet_settings_title',
            [
                'type' => 'text',
                'id'   => 'wc_getnet_settings_client_homolog_id'
            ]
        );
    
        add_settings_field(
            'wc_getnet_settings_client_homolog_secret',
            __( 'Client Secret' ).' '.wc_help_tip( __( 'Homolog Client Secret gerado na minha conta GETNET.' ) ),
            [ $instance, 'getnet_settings_text_callback' ],
            'getnet-settings',
            'wc_getnet_settings_title',
            [
                'type' => 'password',
                'id'   => 'wc_getnet_settings_client_homolog_secret'
            ]
        );
    
        add_settings_section(
            'wc_getnet_settings_bottom',
            __( 'Caso não tenha cadastro na plataforma de e-commerce ou possua apenas a maquininha, solicite o cadastro  <a href="https://site.getnet.com.br/ecommerce" target="_blank"> no formulário no final da página.</a>' ), 
            function(){},
            'getnet-settings'
        );
    }

    public static function getnet_settings_text_callback( $args ) {
        $option = get_option( $args["id"] );
        ?>
        <input type="<?php echo $args["type"]; ?>" name="<?php echo $args["id"]; ?>" id="<?php echo $args["id"]; ?>" value="<?php echo $option; ?>" <?php echo ( get_option( '_policy_privacy_accept' ) == 1 ) ? '' : 'disabled'; ?>>
        <?php
        if($args["id"] == "wc_getnet_settings_client_homolog_secret" || $args["id"] == "wc_getnet_settings_client_production_secret" || $args["id"] == "wc_getnet_settings_client_sandbox_secret"){
            ?>
            <img src="<?php echo esc_url( \WcGetnet::core()->assets()->getAssetUrl( 'images/eye.png' ) )?>" class="show-pass">
            <?php
        }
    }

    public static function getnet_settings_select_callback( $args ) {
        $option = get_option( $args["id"] );
        ?>
        <select id="<?php echo $args['id']; ?>" name="<?php echo $args['id']; ?>" <?php echo ( get_option( '_policy_privacy_accept' ) == 1 ) ? '' : 'disabled'; ?>>
        <?php foreach ($args['options'] as $key => $value) : ?>
            <option value="<?php echo $key; ?>" <?php echo isset( $option ) ? ( selected( $option, $key, false ) ) : ( '' ); ?>>
                <?php echo $value; ?>
            </option>
        <?php endforeach; ?>
        </select>
        <?php
    }

    public static function getnet_set_wc_screen_ids( $screen ){
        $screen[] = 'woocommerce_page_getnet-settings';
        return $screen;
    }

    public static function getnet_admin_notice() {
		$current_screen = get_current_screen();

		if ( 'woocommerce_page_getnet-settings' !== $current_screen->id ) {
			return;
		}
            
        if ( !isset( $_REQUEST['settings-updated'] ) ) {
            return;
        }

        if ( $_REQUEST['settings-updated'] === 'true' ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo __( '<b>Suas configurações foram salvas.</b>' ) ?></p>
            </div>
        <?php else : ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php echo __( '<b>Desculpe, algum erro aconteceu.</b>' ) ?></p>
            </div>
        <?php endif;
    }
}