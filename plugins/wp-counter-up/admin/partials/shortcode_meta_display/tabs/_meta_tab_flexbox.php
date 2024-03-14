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
        'desc'      => __( 'Sets the gap between the columns. Add your desired value with suitable unit. E.g. 15px or 1.5rem.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_flexbox_column_gap]',
        'id'        => 'lgx_flexbox_column_gap',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => '15px'
    )
);

$this->meta_form->text(
    array(
        'label'     => __( 'Row Gap', $this->plugin_name ),
        'desc'      => __( 'Sets the gap between the row. Add your desired value with suitable unit. E.g. 15px or 1.5rem', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_flexbox_row_gap]',
        'id'        => 'lgx_flexbox_row_gap',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => '15px'
    )
);

$this->meta_form->select(
    array(
        'label' => __( 'Item Horizontal Alignment', $this->plugin_name ),
        'desc' => __( 'Set flexible items horizontal alignment ( Justify ).<br> <span style="color: #e31919">Note: It helps to align row items horizontally. This applicable for only item, not inner content.</span>', $this->plugin_name ),
        'name' => 'post_meta_lgx_counter_generator[lgx_flexbox_justify_content]',
        'id' => 'lgx_flexbox_justify_content',
        'default'   => 'flex-start',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'options'   => array(
            'flex-start' => __( 'Left', $this->plugin_name ),
            'center' => __( ' Center', $this->plugin_name ),
            'flex-end' => __( 'Right', $this->plugin_name ),
        )
    )
);

$this->meta_form->select(
    array(
        'label' => __( ' Item Vertical Alignment', $this->plugin_name ),
        'desc' => __( 'Set flexible items vertical alignment ( Align).<br> <span style="color: #e31919">Note: It helps if the size of the flexible items are not equal. This applicable for only item, not inner content.</span>', $this->plugin_name ),
        'name' => 'post_meta_lgx_counter_generator[lgx_flexbox_align_items]',
        'id' => 'lgx_flexbox_align_items',
        'default'   => 'flex-start',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'options'   => array(
            'flex-start' => __( 'Top', $this->plugin_name ),
            'center' => __( 'Vertically Middle', $this->plugin_name ),
            'flex-end' => __( 'Bottom', $this->plugin_name ),
        )
    )
);



$this->meta_form->select(
    array(
        'label' => __( 'Flex Wrap', $this->plugin_name ),
        'desc' => __( 'Make the flexible items single or multi-line.', $this->plugin_name ),
        'name' => 'post_meta_lgx_counter_generator[lgx_flexbox_wrap]',
        'id' => 'lgx_flexbox_wrap',
        'default'   => 'wrap',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'options'   => array(
            'wrap' => __( 'Wrap', $this->plugin_name ),
            'wrap-reverse' => __( 'Wrap Reverse', $this->plugin_name ),
            'nowrap' => __( 'No wrap', $this->plugin_name ),
        )
    )
);



$this->meta_form->select(
    array(
        'label' => __( 'Flex Direction', $this->plugin_name ),
        'desc' => __( 'Set the direction of the flexible items.', $this->plugin_name ),
        'name' => 'post_meta_lgx_counter_generator[lgx_flexbox_direction]',
        'id' => 'lgx_flexbox_direction',
        'default'   => 'row',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'options'   => array(
            'row' => __( 'Row', $this->plugin_name ),
            'row-reverse' => __( 'Row Reverse', $this->plugin_name ),
            'column' => __( 'Column', $this->plugin_name ),
            'column-reverse' => __( 'Column Reverse', $this->plugin_name ),
        )
    )
);


