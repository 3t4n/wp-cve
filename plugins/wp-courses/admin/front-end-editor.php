<?php

function wpc_front_end_editor($post_id){ ?>
<script>
    window.wpc_feePostId = <?php echo (int) $post_id; ?>;
</script>
<div class="wpc-fe-options-wrapper">
    <div id="wpc-fe-options-accordion">
        <h3 class="wpc-accordion-option-header"><?php esc_html_e('Size', 'wp-courses'); ?></h3>
        <div class="wpc-accordion-option-content">
            <div class="wpc-accordion-single-option wpc-range-option">
                <?php 
                    $width = get_option('wpc_row_width'); 
                    $width = wpc_esc_unit($width, 'px');

                    $width_unit = wpc_get_unit($width, '%');
                ?>
                <label><?php esc_html_e('Container Width', 'wp-courses'); ?> (px, em, %)</label><br>
                <input class="wpc-range-ajax wpc-feo-text wpc-range-input" data-class="wpc-main" data-style="width" data-unit="<?php echo esc_attr($width_unit); ?>" data-option="wpc_row_width" type="text" placeholder="0" value="<?php echo esc_attr($width); ?>"/>
            </div>
            <div class="wpc-accordion-single-option wpc-range-option">
                <?php 
                    $max_width = get_option('wpc_row_max_width'); 
                    $max_width = wpc_esc_unit($max_width, 'px');

                    $max_width_unit = wpc_get_unit($max_width, 'px');
                ?>
                <label><?php _e('Container Max Width', 'wp-courses'); ?> (px, em, %)</label>
                <br>
                <input class="wpc-range-ajax wpc-feo-text wpc-range-input" data-class="wpc-main" data-style="max-width" data-unit="<?php echo esc_attr($max_width_unit); ?>" data-option="wpc_row_max_width" type="text" placeholder="0" value="<?php echo esc_attr($max_width); ?>"/>
            </div>
            <div class="wpc-accordion-single-option">
                <?php 
                    $container_padding_top = get_option('wpc_container_padding_top'); 
                    $container_padding_top = wpc_esc_unit($container_padding_top, 'px');

                    $container_padding_top_unit = wpc_get_unit($container_padding_top, 'px');
                ?>
                <label><?php _e('Container Padding', 'wp-courses'); ?> (px, em, %)</label><br>
                <div class="wpc-spacing-wrapper">
                    <input class="wpc-feo-text wpc-spacing-input" data-class="wpc-main" data-style="padding-top" data-unit="<?php echo esc_attr($container_padding_top_unit); ?>" data-option="wpc_container_padding_top" type="text" placeholder="0" value="<?php echo esc_attr($container_padding_top); ?>"/>
                    <label class="wpcb-center-label">Top</label>
                </div>
                <?php 
                    $container_padding_bottom = get_option('wpc_container_padding_bottom'); 
                    $container_padding_bottom = wpc_esc_unit($container_padding_bottom, 'px');

                    $container_padding_bottom_unit = wpc_get_unit($container_padding_bottom, 'px');
                ?>
                <div class="wpc-spacing-wrapper">
                    <input class="wpc-feo-text wpc-spacing-input" data-class="wpc-main" data-style="padding-bottom" data-unit="<?php echo esc_attr($container_padding_bottom_unit); ?>" data-option="wpc_container_padding_bottom" type="text" placeholder="0" value="<?php echo esc_attr($container_padding_bottom); ?>"/>
                    <label class="wpcb-center-label">Bottom</label>
                </div>
                <?php 
                    $container_padding_left = get_option('wpc_container_padding_left'); 
                    $container_padding_left = wpc_esc_unit($container_padding_left, 'px');

                    $container_padding_left_unit = wpc_get_unit($container_padding_left, 'px');
                ?>
                <div class="wpc-spacing-wrapper">
                    <input class="wpc-feo-text wpc-spacing-input" data-class="wpc-main" data-style="padding-left" data-unit="<?php echo esc_attr($container_padding_left_unit); ?>" data-option="wpc_container_padding_left" type="text" placeholder="0" value="<?php echo esc_attr($container_padding_left); ?>"/>
                    <label class="wpcb-center-label">Left</label>
                </div>
                <?php 
                    $container_padding_right = get_option('wpc_container_padding_right'); 
                    $container_padding_right = wpc_esc_unit($container_padding_right, 'px');

                    $container_padding_right_unit = wpc_get_unit($container_padding_right, 'px');
                ?>
                <div class="wpc-spacing-wrapper">
                    <input class="wpc-feo-text wpc-spacing-input" data-class="wpc-main" data-style="padding-right" data-unit="<?php echo esc_attr($container_padding_right_unit); ?>" data-option="wpc_container_padding_right" type="text" placeholder="0" value="<?php echo esc_attr($container_padding_right); ?>"/>
                    <label class="wpcb-center-label">Right</label>
                </div>
            </div>
            <div class="wpc-accordion-single-option">
                <?php 
                    $container_margin_top = get_option('wpc_container_margin_top'); 
                    $container_margin_top = wpc_esc_unit($container_margin_top, 'px');

                    $container_margin_top_unit = wpc_get_unit($container_margin_top, 'px');
                ?>
                <label><?php _e('Container Margin', 'wp-courses'); ?> (px, em, %)</label><br>
                <div class="wpc-spacing-wrapper">
                    <input class="wpc-feo-text wpc-spacing-input" data-class="wpc-main" data-style="margin-top" data-unit="<?php echo esc_attr($container_margin_top_unit); ?>" data-option="wpc_container_margin_top" type="text" placeholder="0" value="<?php echo esc_attr($container_margin_top); ?>"/>
                    <label class="wpcb-center-label">Top</label>
                </div>
                <?php 
                    $container_margin_bottom = get_option('wpc_container_margin_bottom'); 
                    $container_margin_bottom = wpc_esc_unit($container_margin_bottom, 'px');

                    $container_margin_bottom_unit = wpc_get_unit($container_margin_bottom, 'px');
                ?>
                <div class="wpc-spacing-wrapper">
                    <input class="wpc-feo-text wpc-spacing-input" data-class="wpc-main" data-style="margin-bottom" data-unit="<?php echo esc_attr($container_margin_bottom_unit); ?>" data-option="wpc_container_margin_bottom" type="text" placeholder="0" value="<?php echo esc_attr($container_margin_bottom); ?>"/>
                    <label class="wpcb-center-label">Bottom</label>
                </div>
            </div>
        </div>

        <h3 class="wpc-accordion-option-header"><?php esc_html_e('Colors', 'wp-courses'); ?></h3>
        <div class="wpc-accordion-option-content">

            <?php $container_bg_color = get_option('wpc_primary_bg_color'); ?>
            <div class="wpc-accordion-single-option">
                <label><?php esc_html_e('Background', 'wp-courses'); ?></label><br>
                <input type="color" class="wpc-fe-color-field" data-option="wpc_primary_bg_color" data-var="--wpcbg" value="<?php echo esc_attr($container_bg_color); ?>"/>
            </div>

            <?php $primary = get_option('wpc_primary_color'); ?>
            <div class="wpc-accordion-single-option">
                <label><?php esc_html_e('Detail Buttons', 'wp-courses'); ?></label><br>
                <input type="color" class="wpc-fe-color-field" data-option="wpc_primary_color" data-var="--green" value="<?php echo esc_attr($primary); ?>"/>
            </div>

            <?php $secondary = get_option('wpc_secondary_color'); ?>
            <div class="wpc-accordion-single-option">
                <label><?php esc_html_e('Start Course & Add to Cart Buttons', 'wp-courses'); ?></label><br>
                <input type="color" class="wpc-fe-color-field" data-option="wpc_secondary_color" data-var="--blue" value="<?php echo esc_attr($secondary); ?>"/>
            </div>

            <?php $secondary = get_option('wpc_toolbar_buttons_color'); ?>
            <div class="wpc-accordion-single-option">
                <label><?php esc_html_e('Toolbar Buttons', 'wp-courses'); ?></label><br>
                <input type="color" class="wpc-fe-color-field" data-option="wpc_toolbar_buttons_color" data-var="--tool" value="<?php echo esc_attr($secondary); ?>"/>
            </div>

            <?php $secondary = get_option('wpc_selected_bg_color'); ?>
            <div class="wpc-accordion-single-option">
                <label><?php esc_html_e('Selected Item', 'wp-courses'); ?></label><br>
                <input type="color" class="wpc-fe-color-field" data-option="wpc_selected_bg_color" data-var="--sele" value="<?php echo esc_attr($secondary); ?>"/>
            </div>

            <?php $secondary = get_option('wpc_link_color'); ?>
            <div class="wpc-accordion-single-option">
                <label><?php esc_html_e('Links', 'wp-courses'); ?></label><br>
                <input type="color" class="wpc-fe-color-field" data-option="wpc_link_color" data-var="--link" value="<?php echo esc_attr($secondary); ?>"/>
            </div>

            <?php $secondary = get_option('wpc_standard_button_color'); ?>
            <div class="wpc-accordion-single-option">
                <label><?php esc_html_e('Standard Buttons', 'wp-courses'); ?></label><br>
                <input type="color" class="wpc-fe-color-field" data-option="wpc_standard_button_color" data-var="--stand" value="<?php echo esc_attr($secondary); ?>"/>
            </div>

        </div>

        <h3 class="wpc-accordion-option-header"><?php esc_html_e('Headings', 'wp-courses'); ?></h3>
        <div class="wpc-accordion-option-content">
            <?php 
            $h1 = get_option('wpc_h1_font_size'); 
            
            $h1_unit = wpc_get_unit($h1, 'px');
            ?>
            <div class="wpc-accordion-single-option">
                <label>H1 <?php esc_html_e('Font Size', 'wp-courses'); ?> (px, em, %)</label><br>
                <input class="wpc-range-ajax wpc-feo-text wpc-range-input" data-class="wpc-h1" data-style="font-size" data-unit="<?php echo esc_attr($h1_unit); ?>" data-option="wpc_h1_font_size" type="text" placeholder="32px" value="<?php echo esc_attr($h1); ?>"/>
            </div>
            <?php 
            $h2 = get_option('wpc_h2_font_size'); 
            
            $h2_unit = wpc_get_unit($h2, 'px');
            ?>
            <div class="wpc-accordion-single-option">
                <label>H2 <?php _e('Font Size', 'wp-courses'); ?> (px, em, %)</label><br>
                <input class="wpc-range-ajax wpc-feo-text wpc-range-input" data-class="wpc-h2" data-style="font-size" data-unit="<?php echo esc_attr($h2_unit); ?>" data-option="wpc_h2_font_size" type="text" placeholder="26px" value="<?php echo esc_attr($h2); ?>"/>
            </div>
            <?php 
            $h3 = get_option('wpc_h3_font_size'); 
            
            $h3_unit = wpc_get_unit($h3, 'px');
            ?>
            <div class="wpc-accordion-single-option">
                <label>H3 <?php _e('Font Size', 'wp-courses'); ?> (px, em, %)</label><br>
                <input class="wpc-range-ajax wpc-feo-text wpc-range-input" data-class="wpc-h3" data-style="font-size" data-unit="<?php echo esc_attr($h3_unit); ?>"data-option="wpc_h3_font_size" type="text" placeholder="22px" value="<?php echo esc_attr($h3); ?>"/>
            </div>
        </div>
    </div>
</div>

<div id="wpc-fe-setting-icon" data-open="false"><i class="fa fa-cog"></i></div>
<style id="wpc-fe-styles"></style>

<?php }

add_action('wp_head', 'wpc_front_end_options');

function wpc_front_end_options(){ 

    $is_admin = current_user_can('administrator');
    $post_type = get_post_type();
    $post_id = get_the_ID();

    if(
        $is_admin && $post_type == 'course'
        || $is_admin && $post_type == 'lesson'
        || $is_admin && $post_type == 'wpc-quiz'
        || $is_admin && $post_type == 'teacher'
        || $is_admin && $post_type == 'wpc-certificate'
    ){
        wpc_front_end_editor($post_id); // FEE injected when course, lesson etc. posts are viewed directly
    }
}

add_action( 'wp_ajax_save_fe_option', 'wpc_save_fe_option', 12 );

function wpc_save_fe_option() {
    check_ajax_referer('wpc_nonce', 'security');

    $post_id = (int) $_POST['post_id'];

    if (!current_user_can( 'edit_page', $post_id ) && !current_user_can( 'edit_post', $post_id )) {
        wp_die();
    }

    $option_name = sanitize_text_field($_POST['option']);
    $option_value = sanitize_text_field($_POST['value']);

    if ($option_name !== 'wpc_row_width' && $option_name !== 'wpc_row_max_width' && $option_name !== 'wpc_container_padding_top' && $option_name !== 'wpc_container_padding_bottom' && $option_name !== 'wpc_container_padding_left' && $option_name !== 'wpc_container_padding_right' && $option_name !== 'wpc_container_margin_bottom' && $option_name !== 'wpc_container_margin_top' && $option_name !== 'wpc_primary_bg_color' && $option_name !== 'wpc_primary_color' && $option_name !== 'wpc_secondary_color' && $option_name !== 'wpc_toolbar_buttons_color' && $option_name !== 'wpc_selected_bg_color' && $option_name !== 'wpc_link_color' && $option_name !== 'wpc_standard_button_color' && $option_name !== 'wpc_h1_font_size' && $option_name !== 'wpc_h2_font_size' && $option_name !== 'wpc_h3_font_size') {
        wp_die();
    }

    update_option($option_name, $option_value);
    wp_die();
}

?>