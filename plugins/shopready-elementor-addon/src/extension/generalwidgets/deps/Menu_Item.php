<?php

namespace Shop_Ready\extension\generalwidgets\deps;


/**
 * @since 1.0
 * Mega Menu option
 */
class Menu_Item
{

    public $key_one = 'woo_ready_elementor_tpl_id';
    public $key_two = 'woo_ready_elementor_menu_bedge';
    public $key_three = 'woo_ready_elementor_menu_bedge_color';
    public $key_four = 'woo_ready_elementor_menu_bedge_bgcolor';

    public function register()
    {

        add_action('admin_enqueue_scripts', [$this, 'add_admin_scripts']);
        add_action('wp_nav_menu_item_custom_fields', [$this, 'add_mega_menu_item_field'], 12, 3);
        add_action('wp_update_nav_menu_item', [$this, 'save_menu_item'], 10, 2);
    }



    public function add_admin_scripts($handle)
    {

        if ($handle == 'nav-menus.php') {

            wp_enqueue_style('shop-ready-admin-base');
            wp_enqueue_style('bvselect');
            wp_enqueue_script('bvselect');
            wp_enqueue_script('shop-ready-admin-menu');
        }
    }

    public function add_mega_menu_item_field($item_id, $item, $depth)
    {

        $template_id = esc_html(get_post_meta($item_id, $this->key_one, true));
        $bedge = esc_html(get_post_meta($item_id, $this->key_two, true));
        $color = esc_html(get_post_meta($item_id, $this->key_three, true));
        $bgcolor = esc_html(get_post_meta($item_id, $this->key_four, true));

        if ($depth == 0) {

            echo wp_kses_post(sprintf('<p> %s </p>', esc_html__('Select Elementor Template', 'shopready-elementor-addon')));

            echo
                sprintf(
                    '<select id="%s" class="woo-ready-selectbox" name="woo_ready_mega_menu[%s][%s]">',
                    esc_attr($item_id),
                    esc_attr($this->key_one),
                    esc_attr($item_id)
                )
            ;

            foreach (shop_ready_get_elementor_templates_arr() as $ky => $template) {
                $selected = $ky == $template_id ? 'selected' : false;
                echo
                    sprintf(
                        '<option value="%s" %s> %s </option>',
                        esc_attr($ky),
                        esc_html($selected),
                        esc_html($template)
                    )
                ;
            }

            echo wp_kses_post('</select>');
        }

        echo
            sprintf(
                '<p> <input name="woo_ready_mega_menu[%s][%s]" type="text" class="menu-name regular-text menu-item-textbox" value="%s" placeholder="%s" /> </p>',
                esc_attr($this->key_two),
                esc_attr($item_id),
                esc_html($bedge),
                esc_html__('Bedge', 'shopready-elementor-addon')
            )
        ;

        echo wp_kses_post(sprintf('<label> %s </label>', esc_html__('Bedge Color', 'shopready-elementor-addon')));
        echo
            sprintf(
                '<p><input name="woo_ready_mega_menu[%s][%s]" type="color" class="menu-name regular-text menu-item-textbox" value="%s" placeholder="%s" /> </p>',
                esc_attr($this->key_three),
                esc_attr($item_id),
                esc_attr($color),
                esc_html__('Bedge Color', 'shopready-elementor-addon')
            )
        ;

        echo wp_kses_post(sprintf('<label> %s </label>', esc_html__('Bedge BGColor', 'shopready-elementor-addon')));
        echo
            sprintf(
                '<p> <input name="woo_ready_mega_menu[%s][%s]" type="color" class="menu-name regular-text menu-item-textbox" value="%s" placeholder="%s" /> </p>',
                esc_attr($this->key_four),
                esc_attr($item_id),
                esc_attr($bgcolor),
                esc_html__('Bedge Background', 'shopready-elementor-addon')
            )
        ;
    }

    public function save_menu_item($menu_id, $menu_item_db_id)
    {

        if (isset($_POST['woo_ready_mega_menu'][$this->key_one][$menu_item_db_id])) {

            $sanitized_data = sanitize_text_field($_POST['woo_ready_mega_menu'][$this->key_one][$menu_item_db_id]);
            update_post_meta($menu_item_db_id, $this->key_one, $sanitized_data);

            $sanitized_data_two = sanitize_text_field($_POST['woo_ready_mega_menu'][$this->key_two][$menu_item_db_id]);
            update_post_meta($menu_item_db_id, $this->key_two, $sanitized_data_two);

            $sanitized_data_three = sanitize_text_field($_POST['woo_ready_mega_menu'][$this->key_three][$menu_item_db_id]);
            update_post_meta($menu_item_db_id, $this->key_three, $sanitized_data_three);

            $sanitized_data_four = sanitize_text_field($_POST['woo_ready_mega_menu'][$this->key_four][$menu_item_db_id]);
            update_post_meta($menu_item_db_id, $this->key_four, $sanitized_data_four);
        } else {

            delete_post_meta($menu_item_db_id, $this->key_one);
            delete_post_meta($menu_item_db_id, $this->key_two);
            delete_post_meta($menu_item_db_id, $this->key_three);
            delete_post_meta($menu_item_db_id, $this->key_four);
        }
    }
}