<?php
if(!defined('ABSPATH')){
    exit;
  }
/* 
* Data Api
* @since 1.0
*/

    $settings_id       = 'shop_ready_data_api';
    $option_switch_key = 'shop_ready_data_api_switch';
    $switch_js_target  = $settings_id.'_data_api';
    $nonce_field_val   = '_shop_ready_data_api';
    $action_key        = 'shop_ready_data_api_options';
    $option_key        = 'shop_ready_data_api';
    $label_identifier  = 'quomodo-data-api-';
    $heading           = esc_html__( 'Api','shopready-elementor-addon' );
  
    
?>
<!-- Widgets Swicher  -->
<form id="woo-ready-admin-module-form" class="woo-ready-components-action quomodo-api-data"
    action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
    <div class="quomodo-container-wrapper">
        <div class="quomodo-row-wrapper">
            <div class="woo-ready-component-form-wrapper api">
                <div class="woo-ready-components-topbar">
                    <div class="woo-ready-title">
                        <h3 class="title"><i class="dashicons dashicons-arrow-left-alt woo-ready-offcanvas"></i>
                            <?php echo esc_html($heading); ?></h3>
                    </div>
                    <div class="woo-ready-savechanges">

                        <div class="woo-ready-admin-button">
                            <button type="submit" class="woo-ready-component-submit button woo-ready-submit-btn">
                                <i class="dashicons dashicons-yes"></i>
                                <?php echo esc_html__('Save Change','shopready-elementor-addon'); ?>
                            </button>
                        </div>

                    </div>
                </div>
                <div class="quomodo-container">
                    <?php $api_settings = shop_ready_api_config()->all(); ?>

                    <?php if( is_array( $api_settings ) ): ?>

                    <?php foreach( $api_settings as $item_key => $item): ?>
                    <div class="quomodo-row woo-ready-data-row">

                        <div class="woo-ready-col woo-ready-data-api-col quomodo-col-md-12 ">
                            <div class="woo-ready-data">

                                <strong><?php echo esc_html($item['title']); ?></strong>
                                <?php if(isset($item['demo_link']) && $item['demo_link'] !=''): ?>
                                <a target="_blank" href="<?php echo esc_url($item['demo_link']); ?>"
                                    class="woo-ready-data-tooltip"><?php echo esc_html__('view doc','shopready-elementor-addon'); ?></a>
                                <?php endif; ?>
                                <input value="<?php echo esc_attr($item['default']); ?>"
                                    name="<?php echo esc_attr( $option_key ); ?>[<?php echo esc_attr($item_key); ?>]"
                                    class="quomodo_text <?php echo esc_attr($item_key); ?>"
                                    id="<?php echo esc_attr($label_identifier); ?><?php echo esc_attr($item_key); ?>"
                                    type="<?php echo esc_attr($item['type']); ?>">
                                <label
                                    for="<?php echo esc_attr($label_identifier); ?><?php echo esc_attr($item_key); ?>"></label>

                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>
            <input type="hidden" name="action" value="<?php echo esc_attr($action_key); ?>">
            <?php echo wp_kses_post(wp_nonce_field($action_key, $nonce_field_val)); ?>
        </div>
    </div> <!-- container end -->
</form>
<!-- Widget swicher form end -->