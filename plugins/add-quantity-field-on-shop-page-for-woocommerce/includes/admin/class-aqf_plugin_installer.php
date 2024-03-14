<?php
if ( ! class_exists( 'AQF_Plugin_Installer' ) ) {
    class AQF_Plugin_Installer {

        public function __construct() {
            $this->init_hooks();
        }

        private function init_hooks() {
            if ( class_exists( 'WooCommerce' ) && ! class_exists( 'Woo_Disable_Variable_Price_Range' ) ) {
                add_action( 'admin_init', array( $this, 'after_plugin_active' ) );
                add_action( 'admin_notices', array( $this, 'internal_feed' ), 30 );

                // add_action( 'woocommerce_variable_product_before_variations', array( $this, 'add_plugin_notice' ) );
                add_action( 'wp_ajax_install_woo_variable_lowest_price', array( $this, 'install_woo_variable_lowest_price' ) );
                add_action( 'admin_footer', array( $this, 'my_action_javascript' ) );
            }
        }

        public function after_plugin_active() {
            if ( isset( $_GET['aqf-hide-notice'] ) && isset( $_GET['_aqf_nonce'] ) && $_GET['aqf-hide-notice'] == 'install-variation-price-plugin' && wp_verify_nonce( $_GET['_aqf_nonce'], 'install-variation-price-plugin' ) ) {
                set_transient( 'aqf_variation_price_plugin_notice', 'yes', 2 * MONTH_IN_SECONDS );
                update_option( 'aqf_variation_price_plugin_notice', 'yes' );
            }
        }

        public function internal_feed() {
            $visible_pages = array( 'dashboard', 'edit-product', 'product', 'plugin-install', 'plugins' );
            $screen        = get_current_screen();

            if ( current_user_can( 'install_plugins' ) && $screen && in_array( $screen->id, $visible_pages ) ) {

                if ( apply_filters( 'stop_aqf_live_feed', false ) ) {
                    return;
                }

                if ( get_option( 'aqf_variation_price_plugin_notice' ) == 'yes' ) {
                    return;
                }

                if ( get_transient( 'aqf_variation_price_plugin_notice' ) == 'yes' ) {
                    return;
                }

                $plugins     = array_keys( get_plugins() );
                $slug        = 'disable-variable-product-price-range-show-only-lowest-price-in-variable-products';
                $plugin_slug = 'disable-variable-product-price-range-show-only-lowest-price-in-variable-products/woo-disable-variable-product-price-range.php';
                $button_text = esc_html__( 'Install Now', 'add-quantity-field-on-shop-page' );
                $install_url = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug ) );

                if ( in_array( $plugin_slug, $plugins ) ) {
                    $button_text = esc_html( 'Activate Plugin', 'add-quantity-field-on-shop-page' );
                    $install_url = esc_url( self_admin_url( 'plugins.php?action=activate&plugin=' . urlencode( $plugin_slug ) . '&plugin_status=all&paged=1&s&_wpnonce=' . urlencode( wp_create_nonce( 'activate-plugin_' . $plugin_slug ) ) ) );
                }

                $popup_url = esc_url(
                    add_query_arg(
                        array(
                            'tab'       => 'plugin-information',
                            'section'   => 'description',
                            'plugin'    => $slug,
                            'TB_iframe' => 'true',
                            'width'     => '950',
                            'height'    => '600',
                        ), self_admin_url( 'plugin-install.php' )
                    )
                );

                $cancel_url = esc_url(
                    add_query_arg(
                        array(
                            'aqf-hide-notice' => 'install-variation-price-plugin',
                            '_aqf_nonce'      => wp_create_nonce( 'install-variation-price-plugin' ),
                        )
                    )
                );

                $image_url = AQF_DIR_URI.'includes/admin/assets/img/wvpd-icon.gif';

                echo sprintf( '<div class="aqf-feed-contents notice notice-info" style="position: relative; clear: both; overflow: hidden; padding: 15px;"><div class="aqf-feed-message-wrap"><image src="%s" style="float: left; margin-right: 15px;"/><p style="padding: 0; margin: 0 0 10px;">5000+ WooCommerce stores increase their sales using <a target="_blank" class="thickbox open-plugin-details-modal" href="%s"><strong>WooCommerce Variation Price Display</strong></a>. Why not yours? <br/> This plugin helps you to show the variable product price in a more user-friendly way to your customers.</p> <a class="button-primary" href="%s" rel="noopener">%s</a></div>
                    <a class="aqf-live-feed-close notice-dismiss" href="%s" style="text-decoration: none; opacity: .5;"></a></div>', $image_url, $popup_url, $install_url, $button_text, $cancel_url );

            }   
        }

        public function add_plugin_notice() {
            ?>
            <div class="inline notice updated woocommerce-message woocommerce-enable-variable-lowest-price-notice" style="margin: 15px;" data-nonce="<?php echo wp_create_nonce( 'install-woo-variable-lowest-price' ); ?>" data-installing="<?php esc_attr_e( 'Installing Plugin...', 'add-quantity-field-on-shop-page' ); ?>" data-activated="<?php esc_attr_e( 'Plugin Installed. Please check the product page.', 'add-quantity-field-on-shop-page' ); ?>">
                <?php
                printf( '<p> %s <a class="install-woo-variable-lowest-price-action" target="_blank" href="#">%s</a> %s</p>', esc_html__( 'Want to show only lowest price in variable products to encourage sells? Install ', 'add-quantity-field-on-shop-page' ), esc_html__( 'WooCommerce Variation Price Display', 'add-quantity-field-on-shop-page' ), esc_html__( 'plugin.', 'add-quantity-field-on-shop-page' ) );
                ?>
            </div>
            <?php
        }

        public function install_woo_variable_lowest_price() {
            if ( is_ajax() && current_user_can( 'install_plugins' ) && wp_verify_nonce( $_GET['nonce'], 'install-woo-variable-lowest-price' ) ) {

                $plugin_slug = 'disable-variable-product-price-range-show-only-lowest-price-in-variable-products/woo-disable-variable-product-price-range.php';
                $plugin_zip  = 'https://downloads.wordpress.org/plugin/disable-variable-product-price-range-show-only-lowest-price-in-variable-products.zip';

                if ( self::is_plugin_installed( $plugin_slug ) ) {
                    $installed = true;
                    self::upgrade_plugin( $plugin_slug );
                } else {
                    $installed = self::install_plugin( $plugin_zip );
                }

                if ( ! is_wp_error( $installed ) && $installed ) {
                    activate_plugin( $plugin_slug );
                }
            }

            exit;
        }

        public function is_plugin_installed( $slug ) {
            if ( ! function_exists( 'get_plugins' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            $all_plugins = get_plugins();

            if ( ! empty( $all_plugins[ $slug ] ) ) {
                return true;
            } else {
                return false;
            }
        }

        public function install_plugin( $plugin_zip ) {
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

            wp_cache_flush();

            $upgrader  = new Plugin_Upgrader();
            $installed = $upgrader->install( $plugin_zip );

            return $installed;
        }

        public function upgrade_plugin( $plugin_slug ) {
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            
            wp_cache_flush();

            $upgrader = new Plugin_Upgrader();
            $upgraded = $upgrader->upgrade( $plugin_slug );

            return $upgraded;
        }

        public function my_action_javascript() { ?>
            <script type="text/javascript" >
                jQuery('.install-woo-variable-lowest-price-action').on( 'click', function(e) {
                    e.preventDefault();

                    let elData = jQuery(this).closest('.woocommerce-enable-variable-lowest-price-notice'),
                        elDataNonce = elData.data('nonce'),
                        elDataInstalling = elData.data('installing'),
                        elDataActivated = elData.data('activated');

                    var data = {
                        'action': 'install_woo_variable_lowest_price',
                        'nonce': elDataNonce,
                    };

                    jQuery.ajax({
                        type: 'get',
                        url: ajaxurl,
                        data: data,
                        beforeSend:function(xhr){
                            elData.children('p').text(elDataInstalling);
                        },
                        success: function (response) {
                            elData.children('p').text(elDataActivated);
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                });
            </script> <?php
        }

    }

    new AQF_Plugin_Installer();
}