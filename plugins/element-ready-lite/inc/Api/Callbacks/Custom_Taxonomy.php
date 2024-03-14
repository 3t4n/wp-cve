<?php 

namespace Element_Ready\Api\Callbacks;

abstract Class Custom_Taxonomy {
    public $taxonomies = [];
    public function init( $type, $singular_label, $plural_label, $post_types, $settings = array() ){

            $default_settings = array(
                
            'labels' => array(
                'name'                  => $plural_label,
                'singular_name'         => $singular_label,
                'add_new_item'          => esc_html__('New '.$singular_label.' Name', 'element-ready-lite'),
                'new_item_name'         => esc_html__('Add New '.$singular_label, 'element-ready-lite'),
                'edit_item'             => esc_html__('Edit '.$singular_label, 'element-ready-lite'),
                'update_item'           => esc_html__('Update '.$singular_label, 'element-ready-lite'),
                'add_or_remove_items'   => esc_html__('Add or remove '.strtolower($plural_label), 'element-ready-lite'),
                'search_items'          => esc_html__('Search '.$plural_label, 'element-ready-lite'),
                'popular_items'         => esc_html__('Popular '.$plural_label, 'element-ready-lite'),
                'all_items'             => esc_html__('All '.$plural_label, 'element-ready-lite'),
                'parent_item'           => esc_html__('Parent '.$singular_label, 'element-ready-lite'),
                'choose_from_most_used' => esc_html__('Choose from the most used '.strtolower($plural_label), 'element-ready-lite'),
                'parent_item_colon'     => esc_html__('Parent '.$singular_label, 'element-ready-lite'),
                'menu_name'             => $singular_label
            ),

            'public'            => true,
            'show_in_nav_menus' => true,
            'show_admin_column' => false,
            'hierarchical'      => true,
            'show_tagcloud'     => false,
            'show_ui'           => true,
            'rewrite'           => array(
            'slug'              => sanitize_title_with_dashes($plural_label)
            )
        );

        $this->taxonomies[$type]['post_types'] = $post_types;
        $this->taxonomies[$type]['args']       = array_merge($default_settings, $settings);
    }

    public function register_taxonomy(){

        foreach($this->taxonomies as $key => $value) {
            register_taxonomy($key, $value['post_types'], $value['args']);
        }

    }
}

