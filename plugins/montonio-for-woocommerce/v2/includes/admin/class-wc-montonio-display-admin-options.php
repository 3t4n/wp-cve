<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Montonio_Display_Admin_Options {

    public static function init() {
		add_action( 'woocommerce_settings_checkout', array( __CLASS__, 'output' ) );
		add_action( 'woocommerce_update_options_checkout', array( __CLASS__, 'save' ) );
	}

	public static function output() {
		global $current_section;
		do_action( 'woocommerce_montonio_settings_checkout_' . $current_section );
	}

    public static function save() {
		global $current_section;
		if ( $current_section && ! did_action( 'woocommerce_update_options_checkout_' . $current_section ) ) {
			do_action( 'woocommerce_update_options_checkout_' . $current_section );
		}
	}

    public static function montonio_admin_menu( $id = null ) {
        $installed_payment_methods = WC()->payment_gateways()->payment_gateways();

        $menu_items = array(
            'wc_montonio_api' => array( 
                'title' => 'API Settings',
                'type' => 'settings',
                'check_status' => false
            ),
            'wc_montonio_payments' => array( 
                'title' => 'Bank Payments',
                'type' => 'payment_method',
                'check_status' => true
            ),
            'wc_montonio_card' => array( 
                'title' => 'Card Payments',
                'type' => 'payment_method',
                'check_status' => true
            ),
            'wc_montonio_blik' => array( 
                'title' => 'BLIK',
                'type' => 'payment_method',
                'check_status' => true
            ),
            'wc_montonio_bnpl' => array( 
                'title' => 'Pay Later',
                'type' => 'payment_method',
                'check_status' => true
            ),
            'wc_montonio_hire_purchase' => array( 
                'title' => 'Financing',
                'type' => 'payment_method',
                'check_status' => true
            ),
            'montonio_shipping' => array( 
                'title' => 'Shipping',
                'type' => 'settings',
                'check_status' => false
            ),
        );
        ?>

        <div class="montonio-menu">
            <ul>
                <?php foreach ( $menu_items as $key => $value ) : ?>
                    <li <?php if ( $id == $key ) { echo 'class="active"'; } ?>>
                        <?php if ( $key == 'montonio_shipping' ) : ?>
                            <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=' . $key ); ?>"><?php echo $value['title']; ?></a>
                        <?php else : ?>
                            <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . $key ); ?>"><?php echo $value['title']; ?></a>
                        <?php endif; ?>

                        <?php if ( $value['check_status'] ) :
                            if ( $value['type'] == 'payment_method' ) :
                                $payment_method_settings = $installed_payment_methods[$key]->settings;

                                if ( $payment_method_settings['enabled'] == 'yes' && $payment_method_settings['sandbox_mode'] == 'yes' ) : ?>
                                   <span class="montonio-status montonio-status--sandbox"></span>
                                <?php
                                endif;
                            endif;
                        endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }

    public static function pis_v2_info_banner() {
        ?>
        <div class="montonio-card montonio-card--banner montonio-card--blue">
            <div class="montonio-card__body">
                <p><?php echo sprintf( __( 'We\'ve released a new version of our Payment Initiation Service. Activate Montonio Bank Payments (2023) to start using it. Read more about the update by <a href="%s" target="_blank">clicking here!</a>', 'montonio-for-woocommerce' ), 'https://help.montonio.com/en/articles/41341-woocommerce-x-pis-v2' ); ?></p>
            </div>
        </div>
        <?php
    }

    public static function payment_kb_banner() {
        ?>
        <div class="montonio-card montonio-card--banner">
            <div class="montonio-card__body">
            <span class="dashicons dashicons-info-outline"></span>
                <p><?php echo sprintf( __( 'Follow these instructions to set up payment methods: <a href="%s" target="_blank">Activating Payment Methods in WooCommerce</a>', 'montonio-for-woocommerce' ), 'https://help.montonio.com/en/articles/68142-activating-payment-methods-in-woocommerce' ); ?></p>
            </div>
        </div>
        <?php
    }

    public static function shipping_kb_banner() {
        ?>
        <div class="montonio-card montonio-card--banner">
            <div class="montonio-card__body">
            <span class="dashicons dashicons-info-outline"></span>
                <p><?php echo sprintf( __( 'Follow these instructions to set up shipping: <a href="%s" target="_blank">How to set up Shipping solution</a>', 'montonio-for-woocommerce' ), 'https://help.montonio.com/en/articles/57066-how-to-set-up-shipping-solution' ); ?></p>
            </div>
        </div>
        <?php
    }

    public static function card_v2_info_banner() {
        ?>
        <div class="montonio-card montonio-card--banner montonio-card--blue">
            <div class="montonio-card__body">
                <p><?php echo sprintf( __( 'Please ensure that you have activated our new card payments in our partner system before activating it on the store side. <a href="%s" target="_blank">Find more information here</a>.', 'montonio-for-woocommerce' ), 'https://help.montonio.com/en/articles/58670-woocommerce-x-montonio-card-payments-2023' ); ?></p>
            </div>
        </div>
        <?php
    }

    public static function shipping_info_banner() {
        ?>
        <div class="montonio-card montonio-card--banner montonio-card--blue">
            <div class="montonio-card__body">
                <p><?php echo __( 'To access the shipping API, you will need to use production (live) keys. Production keys will become available once you sign an agreement for either one of our services.<br/><br/>
                For the shipping API specifically, production keys can also be used for testing purposes since no real costs are involved without sending actual packages.', 'montonio-for-woocommerce' ); ?></p>
            </div>
        </div>
        <?php
    }

    public static function test_mode_banner() {
        ?>
        <div class="montonio-card montonio-card--banner montonio-card--yellow">
            <div class="montonio-card__body">
                <p><?php echo '<strong>' . __( 'TEST MODE ENABLED!', 'montonio-for-woocommerce' ) . '</strong><br>' . __( 'Use test mode, to test your integration. When test mode is enabled, payment providers do not process payments.', 'montonio-for-woocommerce' ); ?></p>
            </div>
        </div>
        <?php
    }

    public static function api_status_banner() {
        $api_settings = get_option( 'woocommerce_wc_montonio_api_settings' );
        ?>

        <div class="montonio-card">
            <div class="montonio-card__body">
                <h4><?php echo __( 'API keys status', 'montonio-for-woocommerce' ); ?></h4>
                <div class="montonio-api-status">    
                    <p><?php echo __( 'Live keys:', 'montonio-for-woocommerce' ); ?></p>
                    <?php if ( ! empty( $api_settings['access_key'] ) && ! empty( $api_settings['secret_key'] ) ) {
                        echo '<span class="api-status--green"><span class="dashicons dashicons-yes-alt"></span>Added</span>';
                    } else {
                        echo '<span class="api-status--gray"><span class="dashicons dashicons-warning"></span>Not Added</span>';
                    } ?>

                    <p><?php echo __( 'Sandbox keys:', 'montonio-for-woocommerce' ); ?></p>
                    <?php if ( ! empty( $api_settings['sandbox_access_key'] ) && ! empty( $api_settings['sandbox_secret_key'] ) ) {
                        echo '<span class="api-status--green"><span class="dashicons dashicons-yes-alt"></span>Added</span>';
                    } else {
                        echo '<span class="api-status--gray"><span class="dashicons dashicons-warning"></span>Not Added</span>';
                    } ?>
                </div>
            </div>

            <div class="montonio-card__footer">
                <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_montonio_api' ); ?>" class="components-button is-secondary"><?php echo __('Edit account keys', 'montonio-for-woocommerce' ); ?></a>
            </div>
        </div>
        <?php
    }

    public static function display_options( $title, $settings, $id, $sandbox_mode = 'no' ) {
        ?>
        <h2><?php echo $title; ?><small class="wc-admin-breadcrumb"><a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=checkout' ); ?>" aria-label="Return to payments">⤴</a></small></h2>

        <div class="montonio-options-wrapper <?php echo $id; ?>">
            <img class="montonio-logo" src="https://montonio.com/wp-content/themes/montonio-theme/assets/img/logo.svg">

            <?php 
            self::montonio_admin_menu( $id );

            if ( $sandbox_mode == 'yes' ) {
                 self::test_mode_banner(); 
            }

            if ( $id == 'wc_montonio_payments' ) {
                self::pis_v2_info_banner(); 
            } 

            if ( $id == 'wc_montonio_card' ) {
                self::card_v2_info_banner(); 
            } 

            if ( $id == 'montonio_shipping' ) {
                self::shipping_info_banner(); 
            } 
            
            if ( $id == 'montonio_shipping' ) {
                self::shipping_kb_banner(); 
            } else {
                self::payment_kb_banner(); 
            }

            if ( $id != 'wc_montonio_api' ) {
                self::api_status_banner(); 
            } 
            ?>

            <div class="montonio-card">
                <div class="montonio-card__body">
                    <table class="form-table">
                        <?php echo $settings; ?>
                    </table>
                </div>
            </div>
        </div>
    

        <?php
    }
}
WC_Montonio_Display_Admin_Options::init();
