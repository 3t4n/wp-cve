<?php
if(!defined('ABSPATH')){
    exit;
  }
/* 
* Widget templates
* @since 1.0
*/

    $settings_id            = 'shop_ready_templates';
    $option_switch_key      = 'shop_ready_templates_switch';
    $option_presets_key     = 'shop_ready_presets_switch';
    $option_presets_tpl_key = 'option_presets_tpl';
    $switch_js_target       = $settings_id.'_data_tpl';
    $nonce_field_val        = '_shop_ready_templates';
    $action_key             = 'shop_ready_templates_options';
    $option_key             = 'shop_ready_templates';
    $label_identifier       = 'quomodo-template-';
    $label_identifier2      = 'quomodo-template-switch-';
    $elementor_templates    = shop_ready_get_elementor_saved_templates();
    $heading                = esc_html__( 'WooCommerce Templates','shopready-elementor-addon' );
    $template_settings      = shop_ready_templates_config()->all();

    $_template_edit_url = add_query_arg(
        [
            'post'     => '',
            'action'   => 'elementor',
            'sr_tpl'   => 'shop_ready_dashboard',
            'tpl_type' => 'single'
        ],
        admin_url( 'post.php' )
    );
   
   
?>
<?php do_action( 'shop_ready_dashboard_template_before_content', 'template' ); ?>

<!-- Widgets Swicher  -->
<form id="woo-ready-admin-module-form" class="woo-ready-components-action quomodo-module-data"
    action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
    <div class="quomodo-container-wrapper">
        <div class="quomodo-row-wrapper">
            <div class="woo-ready-component-form-wrapper Templates">
                <div class="woo-ready-components-topbar">
                    <div class="woo-ready-title">
                        <h3 class="title"><i class="dashicons dashicons-arrow-left-alt woo-ready-offcanvas"></i>
                            <?php echo esc_html($heading); ?></h3>
                    </div>

                    <div class="woo-ready-savechanges">
                        <div class="shop-ready-templates-extra-settings">
                            <div class="shop-ready-clear-template-data">
                                <a class="shop-ready-template-clear"><span class="dashicons dashicons-trash"></span>
                                    <?php echo esc_html__('Clear Settings','shopready-elementor-addon'); ?> </a>
                            </div>
                        </div>
                        <div class="woo-ready-admin-button">
                            <button type="submit" class="woo-ready-component-submit button woo-ready-submit-btn">
                                <i class="dashicons dashicons-yes"></i>
                                <?php echo esc_html__('Save Change','shopready-elementor-addon'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="quomodo-container">
                    <div class="quomodo-row">
                        <div class="woo-ready-col quomodo-col-xl-11">
                            <div class="shopready-template-ajax-message">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="woo-ready-template-container quomodo-container">
                    <?php if( is_array( $template_settings ) ): ?>
                    <?php foreach( $template_settings as $item_key => $item): ?>
                    <div data-target-row="<?php echo esc_attr($item_key); ?>"
                        data-title="<?php echo esc_attr($item['title']); ?>"
                        class="woo-ready-component-row shop-ready-template-watermark<?php echo esc_attr(isset( $item[ 'is_pro' ] ) && $item[ 'is_pro' ]? ' shop-ready-pro-order':''); ?>">
                        <div
                            class="shop-ready-presets-wrapper woo-ready-col quomodo-col-xl-11 <?php echo esc_attr($item_key); ?>">
                            <!-- Presets -->
                            <?php if( isset( $item[ 'presets' ] ) ): ?>

                            <div class="shop-ready-preset-option-wrapper">
                                <div class="quomodo_switch_common woo-ready-common shop-ready-presets-loader">
                                    <div class="shop-ready-preset-heading">
                                        <?php echo wp_kses_post(sprintf('<span> %s </span>', $item['title'])); ?>
                                        <?php echo esc_html__('Presets?','shopready-elementor-addon') ?> </div>
                                    <div class="quomodo_sm_switch shop-ready-preset-swicher-wrp">
                                        <input data-ptarget="<?php echo esc_attr($item_key); ?>"
                                            <?php echo esc_attr($item['presets_active']==1?'checked':''); ?>
                                            name="<?php echo esc_attr( $option_presets_key ); ?>[<?php echo esc_attr($item_key); ?>]"
                                            class="quomodo_switch shop-ready-preset-checkbox <?php echo esc_attr($item_key); ?>"
                                            id="presets-<?php echo esc_attr($label_identifier2); ?><?php echo esc_attr($item_key); ?>"
                                            type="checkbox">
                                        <label
                                            for="presets-<?php echo esc_attr($label_identifier2); ?><?php echo esc_attr($item_key); ?>"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="shop-ready-preset-option-preset-selector <?php echo esc_attr($item_key); ?>">
                                <?php if( is_array( $item['presets'] ) ): ?>
                                <?php foreach($item['presets'] as $preset_key => $preset_value): ?>
                                <div class="shop-ready-image-selector-wrapper">
                                    <input value="<?php echo esc_attr($preset_key); ?>"
                                        <?php echo esc_attr( $item[ 'presets_active_path' ] == $preset_key ? 'checked' : '' ); ?>
                                        type="radio"
                                        name="<?php echo esc_attr($option_presets_tpl_key); ?>[<?php echo esc_attr($item_key); ?>]"
                                        id="presets-tpl-<?php echo esc_attr($preset_key); ?>"
                                        class="shop-ready-input-hidden" />
                                    <label
                                        class="<?php echo esc_attr(isset($preset_value['pro']) && $preset_value['pro'] == true ? 'shop-pro-preset' :''); ?>"
                                        for="presets-tpl-<?php echo esc_attr($preset_key); ?>">
                                        <img src="<?php echo esc_url($preset_value['img_path']); ?>" />
                                        <?php if(isset($preset_value['demo_url']) && $preset_value['demo_url'] !=''): ?>
                                        <a target="_blank" href="<?php echo esc_url($preset_value['demo_url']); ?>">
                                            <?php echo esc_html__('Demo','shopready-elementor-addon'); ?> </a>
                                        <?php endif; ?>

                                    </label>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>

                            </div>

                            <?php endif; ?>
                        </div>
                        <div data-presets="<?php echo esc_attr($item_key); ?>" class="shop-ready-template-ry-wrapper">
                            <div class="woo-ready-col quomodo-col-xl-11 <?php echo esc_attr($item_key); ?>">

                                <!--  Template Activation Switch -->
                                <div class="woo-ready-template-activation">
                                    <div
                                        class="quomodo_switch_common woo-ready-common <?php echo esc_attr(isset( $item[ 'is_pro' ] ) && $item[ 'is_pro' ]?'woo-ready-pro woo-ready-dash-modal-open-btn':''); ?>">

                                        <div class="quomodo_sm_switch woo-ready-templates-swicher-wrp">

                                            <strong><?php echo esc_html($item['title']); ?>
                                                <?php if( isset( $item['is_pro']) && $item['is_pro'] ): ?>
                                                <span> <?php echo esc_html__( 'PRO', 'shopready-elementor-addon' ) ?>
                                                </span>
                                                <?php endif; ?>
                                            </strong>
                                            <?php if(isset($item['demo']) && $item['demo'] !=''): ?>
                                            <a target="_blank" href="<?php echo esc_url($item['demo']); ?>"
                                                class="element-woo-data-tooltip"><?php echo esc_html__('view doc','shopready-elementor-addon'); ?></a>
                                            <?php endif; ?>
                                            <input data-target="<?php echo esc_attr($item_key); ?>"
                                                <?php echo esc_attr(isset($item['is_pro']) && $item['is_pro']?'readonly disabled':''); ?>
                                                <?php echo esc_attr($item['active']==1?'checked':''); ?>
                                                name="<?php echo esc_attr( $option_switch_key ); ?>[<?php echo esc_attr($item_key); ?>]"
                                                class="quomodo_switch <?php echo esc_attr($item_key); ?>"
                                                id="<?php echo esc_attr($label_identifier2); ?><?php echo esc_attr($item_key); ?>"
                                                type="checkbox">
                                            <label
                                                for="<?php echo esc_attr($label_identifier2); ?><?php echo esc_attr($item_key); ?>"></label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div data-targetee="<?php echo esc_attr($item_key); ?>"
                                class="woo-ready-col woo-ready-template-col quomodo-col-xl-11">
                                <!-- Activation Switch container -->

                                <div class="woo-ready-data quomodo-row watermark">
                                    <div class="quomodo-col-3 shop-ready-templates-dashboard quomodo-mt-3 quomodo-mb-3 quomodo-pt-3 quomodo-pb-3 quomodo-d-flex quomodo-justify-content-end quomodo-align-items-end"
                                        data-targetee="<?php echo esc_attr($item_key); ?>">

                                        <?php if( is_numeric( $item[ 'id' ] ) ): ?>
                                        <div data-tpl_id="<?php echo esc_attr($item[ 'id' ]); ?>"
                                            data-target="<?php echo esc_attr($item_key); ?>"
                                            class="shop-ready-dash-edit-template">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                <path
                                                    d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z" />
                                            </svg>
                                        </div>
                                        <?php endif; ?>

                                        <div data-target="<?php echo esc_attr($item_key); ?>"
                                            class="shop-ready-dash-add-new-template">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                <!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                <path
                                                    d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="quomodo-col-7">
                                        <select data-title="<?php echo esc_html($item['title']); ?>"
                                            id="<?php echo esc_attr($item_key); ?>"
                                            class="woo-ready-selectbox shop-ready-dashboard-tpl-select-box"
                                            name="<?php echo esc_attr( $option_key ); ?>[<?php echo esc_attr($item_key); ?>]">

                                            <option data-separator="true">
                                                <?php echo esc_html__('Select Elementor Template','shopready-elementor-addon'); ?>
                                            </option>
                                            <option value=''>
                                                <?php echo esc_html__('None','shopready-elementor-addon'); ?> </option>
                                            <?php foreach( $elementor_templates as $tpl_item ): ?>

                                            <?php if( is_numeric( $item[ 'id' ] ) ): ?>

                                            <?php endif; ?>
                                            <option data-link="<?php echo esc_url( $_template_edit_url ); ?>"
                                                <?php echo esc_attr( $tpl_item->ID == $item[ 'id' ] ? 'selected' : ''); ?>
                                                value="<?php echo esc_attr($tpl_item->ID); ?>">
                                                <?php echo esc_html($tpl_item->post_title); ?>
                                            </option>
                                            <?php endforeach; ?>

                                        </select>
                                    </div>
                                </div>

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