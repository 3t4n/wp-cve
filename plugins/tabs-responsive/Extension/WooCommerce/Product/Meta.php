<?php

namespace TABS_RES_PLUGINS\Extension\WooCommerce\Product;

/**
 *
 * @author biplo
 */
trait Meta {

    public function enqueue_scripts_and_styles($hook) {
        global $post;
        global $wp_version;
        if ($hook === 'post-new.php' || $hook === 'post.php') {
            if (isset($post->post_type) && $post->post_type === 'product') {
                if (function_exists('wp_enqueue_editor')) {
                    wp_enqueue_editor();
                }
                wp_enqueue_style('responsive_tabs_woo-styles', RESPONSIVE_TABS_URL . 'Extension/WooCommerce/assets/woocommerce/css/admin.css', false, RESPONSIVE_TABS_PLUGIN_VERSION);
                wp_enqueue_script('responsive_tabs_woo_admin', RESPONSIVE_TABS_URL . 'Extension/WooCommerce/assets/woocommerce/js/admin.js', false, RESPONSIVE_TABS_PLUGIN_VERSION);
                wp_enqueue_style('font-awsome.min', RESPONSIVE_TABS_URL . 'Extension/WooCommerce/assets/frontend/css/font-awsome.min.css', false, RESPONSIVE_TABS_PLUGIN_VERSION);
                wp_enqueue_style('fontawesome-iconpicker', RESPONSIVE_TABS_URL . 'Extension/WooCommerce/assets/backend/css/fontawesome-iconpicker.css', false, RESPONSIVE_TABS_PLUGIN_VERSION);
                wp_enqueue_script('fontawesome-iconpicker', RESPONSIVE_TABS_URL . 'Extension/WooCommerce/assets/backend/js/fontawesome-iconpicker.js', false, RESPONSIVE_TABS_PLUGIN_VERSION);
                wp_enqueue_script('iconpicker', RESPONSIVE_TABS_URL . 'Extension/WooCommerce/assets/js/iconpicker.js', array('jquery'), true, RESPONSIVE_TABS_PLUGIN_VERSION);
            }
        }
    }

    public function product_meta_fields_save($post_id) {
        //save the woo layouts

        $layouts = isset($_POST['_responsive_tabs_woo_layouts']) ? esc_attr($_POST['_responsive_tabs_woo_layouts']) : '';
        if ($layouts != '') :
            update_post_meta($post_id, '_responsive_tabs_woo_layouts', $layouts);
        else :
            delete_post_meta($post_id, '_responsive_tabs_woo_layouts');
        endif;
        $tab_data = [];
// save responsive tabs woo data
        if (isset($_POST['_responsive_tabs_woo_layouts_tab_title_'])) :
            $titles = $_POST['_responsive_tabs_woo_layouts_tab_title_'];
            $icon = $_POST['_responsive_tabs_woo_layouts_tab_icon_'];
            $prioritys = $_POST['_responsive_tabs_woo_layouts_tab_priority_'];
            $contents = $_POST['_responsive_tabs_woo_layouts_tab_content_'];
            $callback = $_POST['_responsive_tabs_woo_layouts_tab_callback_'];

            foreach ($titles as $key => $value) {
                $tab_title = stripslashes($titles[$key]);
                $tab_icon = stripslashes($icon[$key]);
                $tab_priority = stripslashes($prioritys[$key]);
                $tab_callback = stripslashes($callback[$key]);
                $tab_content = stripslashes($contents[$key]);
                if (empty($tab_title) && empty($tab_priority)) :
                    return false;
                else :
                    $tab_data[$key] = [
                        'title' => $tab_title,
                        'icon' => $tab_icon,
                        'priority' => $tab_priority,
                        'callback' => $tab_callback,
                        'content' => $tab_content,
                    ];
                endif;
            }
        endif;
        if (count($tab_data) == 0) :
            delete_post_meta($post_id, '_responsive_tabs_woo_data');
        else :
            $tab_data = array_values($tab_data);
            update_post_meta($post_id, '_responsive_tabs_woo_data', $tab_data);
        endif;
    }

    public function add_product_panels() {
        global $post;
        $post_id = $post->ID;
        ?>
        <div id="responsive_tabs_product_data" class="panel woocommerce_options_panel">
            <?php
            woocommerce_wp_select(array(
                'id' => '_responsive_tabs_woo_layouts',
                'label' => __('Select Tabs Layots', wpshopmart_tabs_r_text_domain),
                'description' => __('Select Layouts which ', wpshopmart_tabs_r_text_domain),
                'desc_tip' => true,
            ));
            $tabs = new \TABS_RES_PLUGINS\Extension\WooCommerce\Admin();
            $tabs->render_html();
            ?>
        </div>
        <?php
    }

    public function add_postbox_tabs($tabs) {
        $tabs['responsive_tabs'] = array(
            'label' => 'Tabs Responsive',
            'target' => 'responsive_tabs_product_data',
        );
        return $tabs;
    }

    public function responsive_tabs_css_icon() {
        echo '<style>
	#woocommerce-product-data ul.wc-tabs li.responsive_tabs_options.responsive_tabs_tab a:before{
		content: "\f163";
	}
	</style>';
    }

}
