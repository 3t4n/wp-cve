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



$this->meta_form->text(
    array(
        'label'     => __( 'Column Gap', $this->plugin_name ),
        'desc'      => __( 'Sets the gap between the columns. Add your desired value with suitable unit. E.g. 15px or 1.5rem', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_grid_column_gap]',
        'id'        => 'lgx_grid_column_gap',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => '15px'
    )
);

$this->meta_form->text(
    array(
        'label'     => __( 'Row Gap', $this->plugin_name ),
        'desc'      => __( 'Sets the gap between the row. Add your desired value with suitable unit. E.g. 15px or 1.5rem', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_grid_row_gap]',
        'id'        => 'lgx_grid_row_gap',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => '15px'
    )
);