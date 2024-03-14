<?php
if (!defined('WPINC')) {
    die;
}


$this->meta_form->buy_pro(
    array(
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'link' => 'https://logichunt.com/product/wordpress-counter-up/',
    )
);


$this->meta_form->select(
    array(
        'label'     => __( 'Single Item Content Order', $this->plugin_name ),
        'desc'      => __( 'Select content layout order to arrange content.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_content_order]',
        'id'        => 'lgx_item_content_order',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => 'i_n_t_d',
        'options'   => array(
            'i_n_t_d' => __( 'Icon - Number - Title - Desc', $this->plugin_name ),
            'i_t_n_d' => __( 'Icon - Title - Number - Desc', $this->plugin_name ),
            't_i_n_d' => __( 'Title - Icon - Number - Desc', $this->plugin_name ),
            't_n_i_d' => __( 'Title - Number - Icon - Desc', $this->plugin_name ),
            'n_i_t_d' => __( 'Number - Icon - Title - Desc', $this->plugin_name ),
            'n_t_i_d' => __( 'Number - Title - Icon - Desc', $this->plugin_name ),
            'd_n_i_t' => __( 'Desc - Number - Icon - Title', $this->plugin_name ),
            'd_i_n_t' => __( 'Desc - Icon- Number - Title', $this->plugin_name ),
            'd_n_t_i' => __( 'Desc - Number - Title - Icon', $this->plugin_name ),
            'l_i_t_n_d' => __( 'Left Icon - Number - Title - Desc', $this->plugin_name ),
            'r_i_t_n_d' => __( 'Right Icon - Number - Title - Desc', $this->plugin_name ),
        )
    )
);

$this->meta_form->number(
    array(
        'label'     => __( 'Counter Duration', $this->plugin_name ),
        'desc'      => __( 'The total duration of the count up animation.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_counter_duration]',
        'id'        => 'lgx_counter_duration',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => 2000
    )
);

$this->meta_form->number(
    array(
        'label'     => __( 'Counter Delay', $this->plugin_name ),
        'desc'      => __( 'The delay in milliseconds per number count up.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_counter_delay]',
        'id'        => 'lgx_counter_delay',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => 16
    )
);


$this->meta_form->group2SelectText(
    array(
        'label'       => __( 'Single Item Height', $this->plugin_name ),
        'desc'        => __( 'Set Height of the counter single item . Default: auto . You can add your desired height with suitable unit. E.g. 100px or 10rem.', $this->plugin_name ),
        'id'          => 'lgx_item_single_dimension_height',
        'name'        => 'post_meta_lgx_counter_generator[lgx_item_single_dimension_height]',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,

        'label_select' => 'Properties',
        'name_select' => 'post_meta_lgx_counter_generator[lgx_item_single_property_height]',
        'id_select'   => 'lgx_item_single_property_height',
        'default_select'   => 'height',
        'options_select'   => array(
            'max-height' => __( 'Max Height', $this->plugin_name ),
            'height'   => __( 'Height', $this->plugin_name ),
            'min-height' => __( 'Min Height', $this->plugin_name )
        ),

        'label_text' => 'Value',
        'name_text'  => 'post_meta_lgx_counter_generator[lgx_item_single_height]',
        'id_text'    => 'lgx_item_single_height',
        'default_text' => 'auto'
    )
);


/********************************************************************************/
$this->meta_form->header_spacer(
    array(
        'label'     => __( 'Query Settings', $this->plugin_name ),
    )
);
/********************************************************************************/


$this->meta_form->buy_pro(
    array(
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'link' => 'https://logichunt.com/product/wordpress-counter-up/',
    )
);



/**
 *
 * Grab Logo Category for lgx_logo_item_category
 *
 */
$lgx_counter_taxo = 'lgxcountercat';
$lgx_counter_terms = get_terms(
    array(
        'taxonomy' => $lgx_counter_taxo,
        'orderby'  => 'id',
        'hide_empty'=> true,
    )

); // Get all terms of a taxonomy

$lgx_counter_term_array = array(
    'all' => 'All'
);
if ($lgx_counter_terms && !is_wp_error($lgx_counter_terms)) {

    foreach ($lgx_counter_terms as $term) {
        $lgx_counter_term_array[$term->term_id] = $term->name;
    }

}


$this->meta_form->select(
    array(
        'label'     => __( 'Select Counter Category', $this->plugin_name ),
        'desc'      => __( 'Filter item by category.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_from_category]',
        'id'        => 'lgx_from_category',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => 'all',
        'options'   => $lgx_counter_term_array
    )
);

$this->meta_form->number(
    array(
        'label'     => __( 'Item Limit', $this->plugin_name ),
        'desc'      => __( 'Number of total counter item to show. Default: 0 ( all ).', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_limit]',
        'id'        => 'lgx_item_limit',
        'default'   => 0
    )
);

$this->meta_form->select(
    array(
        'label'     => __( 'Order By', $this->plugin_name ),
        'desc'      => __( 'Sort retrieved items by parameter.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_sort_order_by]',
        'id'        => 'lgx_item_sort_order_by',
        'default'   => 'menu_order',
        'options'   => array(
            'menu_order' => __( 'Drag & Drop', $this->plugin_name ),
            'title' => __( 'Title', $this->plugin_name ),
            'date' => __( 'Date', $this->plugin_name ),
            'rand' => __( 'Random', $this->plugin_name )
        )
    )
);


$this->meta_form->select(
    array(
        'label'     => __( 'Order', $this->plugin_name ),
        'desc'      => __( 'Designates the ascending or descending order of the "orderby" parameter.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_sort_order]',
        'id'        => 'lgx_item_sort_order',
        'default'   => 'ASC',
        'options'   => array(
            'ASC' => __( 'Ascending ', $this->plugin_name ),
            'DESC' => __( 'Descending  ', $this->plugin_name )
        )
    )
); 