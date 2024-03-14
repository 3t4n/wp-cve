<?php

namespace TABS_RES_PLUGINS\Extension\WooCommerce\Inc\Admin;

/**
 *
 * @author biplo
 */
trait Editor {

    public function Editor() {

    }

    public function render_meta_box_icon($post) {
        $post_id = $post->ID;
        wp_nonce_field('responsive_tabs_meta_box_icon', 'responsive_tabs_meta_box_icon_nonce');
        $meta_box_icon = get_post_meta($post_id, 'responsive_woo_tabs_icon', true);
        ?>
        Select Font Awesome Icon for this Tab
        <br>
        <br>
        <div class="form-group">
            <div class="input-group iconpicker-container">

                <input data-placement="bottomRight" class="form-control icp icp-auto iconpicker-element iconpicker-input responsive-admin-icon-selector" value="<?php echo esc_attr($meta_box_icon); ?>" type="text" name="responsive-woo-tab-icon">
                <span class="input-group-addon"><i class="<?php echo esc_attr($meta_box_icon); ?>"></i></span>
            </div>
        </div>
        <?php
    }

    public function render_meta_box_callback_function($post) {
        $post_id = $post->ID;
        wp_nonce_field('render_meta_box_callback_function', 'render_meta_box_callback_function_nonce');
        $callback = get_post_meta($post_id, 'responsive_woo_tabs_callback', true);
        ?>
        Add custom callback Function on this Tab. Blank will be render default format.
        <br>
        <br>
        <div class="form-group">
            <div class="input-group">
                <input  class="form-control" value="<?php echo esc_attr($callback); ?>" type="text" name="responsive-woo-tab-callback">

            </div>
        </div>
        <?php
    }

}
