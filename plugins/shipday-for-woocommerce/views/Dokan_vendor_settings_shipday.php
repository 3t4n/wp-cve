<?php
require_once dirname(__DIR__). '/functions/logger.php';

class Dokan_vendor_settings_shipday
{
    public static function init() {
        add_action('dokan_store_profile_saved', __CLASS__.'::add_api_key', 10, 3);
        add_filter( 'dokan_query_var_filter', __CLASS__.'::dokan_load_document_menu' );
        add_filter( 'dokan_get_dashboard_settings_nav', __CLASS__.'::dokan_add_help_menu' );
        add_action( 'dokan_load_custom_template', __CLASS__.'::dokan_load_template' );
    }

    public static function save_api_key() {
        $post_data = wp_unslash($_POST);
        if (!is_null($post_data) && !is_null($post_data['shipday_api_key']) && !empty(trim($post_data['shipday_api_key'])))
            update_user_meta(wp_get_current_user()->ID, 'shipday_api_key', trim($post_data['shipday_api_key']));
    }

    public static function get_api_key() {
        $api_key            = get_user_meta( wp_get_current_user()->ID, 'shipday_api_key', true );
        return shipday_handle_null($api_key);
    }

    public static function dokan_load_document_menu( $query_vars ) {
        $query_vars['delivery'] = 'delivery';
        return $query_vars;
    }

    public static function dokan_add_help_menu( $urls ) {
        $urls['delivery'] = array(
            'title' => __( 'Delivery', 'shipday'),
            'icon'  => '<i class="fas fa-truck"></i>',
            'url'   => dokan_get_navigation_url( 'delivery' ),
            'pos'   => 10000
        );
        return $urls;
    }

    public static  function dokan_load_template( $query_vars ) {
        if ( isset( $query_vars['delivery'] ) ) {
            ?>
            <?php do_action( 'dokan_dashboard_wrap_start' ); ?>
            <div class="dokan-dashboard-wrap">
                <?php
                do_action( 'dokan_dashboard_content_before' );
                do_action( 'dokan_dashboard_settings_content_before' );
                ?>
                <div class="dokan-dashboard-content dokan-settings-content">
                    <?php do_action( 'dokan_settings_content_inside_before' ); ?>
                    <article class="dokan-settings-area">
                        <?php
                        do_action( 'dokan_settings_content' );
                        ?>
                        <h1>Shipday Settings</h1><br>
                        <form method="post" id="delivery-form"  action="" class="dokan-form-horizontal">

                            <?php wp_nonce_field( 'dokan_delivery_settings_nonce' ); ?>

                            <div class="dokan-form-group">
                                <label class="dokan-w3 dokan-control-label" for="shipday_api_key"><?php esc_html_e( 'Shipday API key', 'shipday' ); ?></label>

                                <div class="dokan-w5 dokan-text-left">
                                    <input id="shipday_api_key" required value="<?php echo esc_attr(self::get_api_key()); ?>" name="shipday_api_key" placeholder="<?php esc_attr_e( 'Enter shipday api key', 'shipday' ); ?>" class="dokan-form-control" type="text">
                                </div>
                            </div>

                            <div class="dokan-form-group">

                                <div class="dokan-w4 ajax_prev dokan-text-left" style="margin-left:24%;">
                                    <input type="submit" name="shipday_vendor_settings" class="dokan-btn dokan-btn-danger dokan-btn-theme" value="<?php esc_attr_e( 'Update Settings', 'dokan-lite' ); ?>">
                                </div>
                            </div>
                        </form>
                    </article>
                </div>
            </div>
            <?php do_action( 'dokan_dashboard_wrap_end' ); ?>

            <?php
            self::save_api_key();
        }
    }
}