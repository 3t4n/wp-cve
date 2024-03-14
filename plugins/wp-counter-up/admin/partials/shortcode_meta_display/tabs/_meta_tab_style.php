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
        'label'     => __( 'Item  Style', $this->plugin_name ),
        'desc'      => __( 'Select style effect for showcase item', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_hover_effect]',
        'id'        => 'lgx_item_hover_effect',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => 'none',
        'options'   => array(
            'none' => __( 'None', $this->plugin_name ),
            'gray_hover' => __( 'Grayscale On Hover', $this->plugin_name ),
            'gray_remove' => __( 'Grayscale Remove On Hover', $this->plugin_name ),
            'gray_always' => __( 'Grayscale Always', $this->plugin_name ),
            'box_shadow' => __( 'Hover Box Shadow', $this->plugin_name ),
            'box_shadow_always' => __( 'Box Shadow Always', $this->plugin_name ),
            'box_shadow_always2' => __( 'Box Shadow Always 2', $this->plugin_name )
        )
    )
);


$this->meta_form->select(
    array(
        'label'     => __( 'Hover Animation', $this->plugin_name ),
        'desc'      => __( 'Select hover animation for showcase logo image.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_hover_anim]',
        'status'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'id'        => 'lgx_item_hover_anim',
        'default'   => 'default',
        'options'   => array(
            'default'       => __( 'Default', $this->plugin_name ),
            'none'          => __( 'None', $this->plugin_name ),
            'scaleup'       => __( 'Scale Up', $this->plugin_name ),
            'bounce'        => __( 'Bounce', $this->plugin_name ),
            'flash'         => __( 'Flash', $this->plugin_name ),
            'pulse'         => __( 'Pulse', $this->plugin_name ),
            'rubberBand'    => __( 'Rubber Band', $this->plugin_name ),
            'shakeX'        => __( 'ShakeX', $this->plugin_name ),
            'shakeY'        => __( 'ShakeY', $this->plugin_name ),
            'headShake'     => __( 'Head Shake', $this->plugin_name ),
            'swing'         => __( 'Swing', $this->plugin_name ),
            'tada'          => __( 'Tada', $this->plugin_name ),
            'wobble'        => __( 'Wobble', $this->plugin_name ),
            'jello'         => __( 'Jello', $this->plugin_name ),
            'heartBeat'     => __( 'Heart Beat', $this->plugin_name ),
            'backInDown'    => __( 'Back In Down', $this->plugin_name ),
            'backInLeft'    => __( 'Back In Left', $this->plugin_name ),
            'backInRight'   => __( 'Back In Right', $this->plugin_name ),
            'backInUp'      => __( 'Back In Up ', $this->plugin_name ),
            'bounceIn'      => __( 'Bounce In ', $this->plugin_name ),
            'bounceInDown'  => __( 'Bounce In Down ', $this->plugin_name ),
            'bounceInLeft'  => __( 'Bounce In Left ', $this->plugin_name ),
            'bounceInRight' => __( 'Bounce In Right ', $this->plugin_name ),
            'bounceInUp'    => __( 'Bounce In Up ', $this->plugin_name ),
            'fadeIn'        => __( 'Fade In ', $this->plugin_name ),
            'fadeInDown'    => __( 'Fade In Down ', $this->plugin_name ),
            'fadeInDownBig' => __( 'Fade In Down Big ', $this->plugin_name ),
            'fadeInLeft'    => __( 'Fade In Left ', $this->plugin_name ),
            'fadeInLeftBig' => __( 'Fade In Left Big ', $this->plugin_name ),
            'fadeInRight'   => __( 'Fade In Right ', $this->plugin_name ),
            'fadeInRightBig'=> __( 'Fade In Right Big ', $this->plugin_name ),
            'fadeInUp'      => __( 'Fade In Up ', $this->plugin_name ),
            'fadeInUpBig'   => __( 'Fade In Up Big ', $this->plugin_name ),
            'fadeInTopLeft' => __( 'Fade In Top Left ', $this->plugin_name ),
            'fadeInTopRight'=> __( 'Fade In Top Right ', $this->plugin_name ),
            'fadeInBottomLeft'  => __( 'Fade In Bottom Left ', $this->plugin_name ),
            'fadeInBottomRight' => __( 'Fade In Bottom Right ', $this->plugin_name ),
            'flip'              => __( 'Flip', $this->plugin_name ),
            'flipInX'           => __( 'Flip InX', $this->plugin_name ),
            'lightSpeedInRight' => __( 'Light Speed In Right', $this->plugin_name ),
            'lightSpeedInLeft'  => __( 'Light Speed In Left', $this->plugin_name ),
            'rotateIn'          => __( 'Rotate In', $this->plugin_name ),
            'rotateInDownLeft'  => __( 'Rotate In Down Left', $this->plugin_name ),
            'rotateInDownRight' => __( 'Rotate In Down Right', $this->plugin_name ),
            'rotateInUpLeft'    => __( 'Rotate In Up Left', $this->plugin_name ),
            'rotateInUpRight'   => __( 'Rotate In Up Right', $this->plugin_name ),
            'hinge'             => __( 'Hinge', $this->plugin_name ),
            'jackInTheBox'      => __( 'Jack In TheBox', $this->plugin_name ),
            'rollIn'            => __( 'Roll In', $this->plugin_name ),
            'zoomIn'            => __( 'Zoom In', $this->plugin_name ),
            'zoomInDown'        => __( 'Zoom In Down', $this->plugin_name ),
            'zoomInLeft'        => __( 'Zoom In Left', $this->plugin_name ),
            'zoomInRight'       => __( 'Zoom In Right', $this->plugin_name ),
            'zoomInUp'          => __( 'Zoom In Up', $this->plugin_name ),
            'slideInDown'       => __( 'Slide In Down', $this->plugin_name ),
            'slideInLeft'       => __( 'Slide In Left', $this->plugin_name ),
            'slideInRight'      => __( 'Slide In Right', $this->plugin_name ),
            'slideInUp'         => __( 'Slide In Up', $this->plugin_name ),
        )
    )
);

$this->meta_form->select(
    array(
        'label'     => __( 'Floating Style', $this->plugin_name ),
        'desc'      => __( 'Select hover effect for showcase item', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_floating]',
        'id'        => 'lgx_item_floating',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => 'none',
        'options'   => array(
            'none' => __( 'None', $this->plugin_name ),    
            'sm' => __( 'Small', $this->plugin_name ),
            'lg' => __( 'Large', $this->plugin_name ),
        )
    )
);

/********************************************************************************/
$this->meta_form->header_spacer(
    array(
        'label'     => __( 'Item Title & Description Settings', $this->plugin_name ),
    )
);
/********************************************************************************/

$this->meta_form->buy_pro(
    array(
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'link' => 'https://logichunt.com/product/wordpress-logo-slider/',
    )
);


$this->meta_form->switch(
    array(
        'label' => __( 'Enable Item Title', $this->plugin_name ),
        'desc' => __( 'Show Title in your showcase.', $this->plugin_name ),
        'yes_label' => __( 'Show', $this->plugin_name ),
        'no_label' => __( 'Hide', $this->plugin_name ),
        'name' => 'post_meta_lgx_counter_generator[lgx_item_title_en]',
        'id' => 'lgx_item_title_en',
        'default' => 'yes'

    )
);


$this->meta_form->textTypo(
    array(
        'label'     => __( 'Item Title', $this->plugin_name ),
        'desc'      => __( 'Set Typography for Item Title.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_text_type_item_title]',
        'id'        => 'lgx_text_type_item_title',
        

        // Color
        'label_color'     => __( 'Font Color', $this->plugin_name ),
        'name_color'      => 'post_meta_lgx_counter_generator[lgx_item_title_color]',
        'id_color'        => 'lgx_item_title_color',
        'default_color'   => '#111111',

         // Size
        'label_size'     => __( 'Font Size', $this->plugin_name ),
        'name_size'      => 'post_meta_lgx_counter_generator[lgx_item_title_font_size]',
        'id_size'        => 'lgx_item_title_font_size',
        'default_size'   => '18px',
        'status_size'    => LGX_WCU_PLUGIN_META_FIELD_PRO,

        //Weight
        'label_weight'     => __( 'Font Weight', $this->plugin_name ),
        'name_weight'      => 'post_meta_lgx_counter_generator[lgx_item_title_font_weight]',
        'id_weight'        => 'lgx_item_title_font_weight',
        'default_weight'   => '600',
        'status_weight'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
    )
);



$this->meta_form->textMulti(
    array(
        'label'     => __( 'Title Margin', $this->plugin_name ),
        'desc'      => __( 'Set top & bottom margin for item title.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_text_type_item_title]',
        'id'        => 'lgx_text_type_item_title',

        'label_1'     => __( 'Top', $this->plugin_name ),
        'name_1'      => 'post_meta_lgx_counter_generator[lgx_item_top_margin_title]',
        'id_1'        => 'lgx_item_top_margin_title',
        'status_1'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_1'   => '5px',

        'label_2'     => __( 'Bottom', $this->plugin_name ),
        'name_2'      => 'post_meta_lgx_counter_generator[lgx_item_bottom_margin_title]',
        'id_2'        => 'lgx_item_bottom_margin_title',
        'status_2'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_2'   => '5px'    
    )
);



$this->meta_form->switch(
    array(
        'label' => __( 'Enable Description', $this->plugin_name ),
        'yes_label' => __( 'Show', $this->plugin_name ),
        'no_label' => __( 'Hide', $this->plugin_name ),
        'desc' => __( 'Show Description in your showcase.', $this->plugin_name ),
        'name' => 'post_meta_lgx_counter_generator[lgx_item_desc_en]',
        'id' => 'lgx_item_desc_en',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default' => 'no'
    )
);


$this->meta_form->textTypo(
    array(
        'label'     => __( 'Item Description', $this->plugin_name ),
        'desc'      => __( 'Set Typography for Item Title.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_text_type_item_desc]',
        'id'        => 'lgx_text_type_item_desc',
        

        // Color
        'label_color'     => __( 'Font Color', $this->plugin_name ),
        'name_color'      => 'post_meta_lgx_counter_generator[lgx_item_desc_color]',
        'id_color'        => 'lgx_item_desc_color',
        'default_color'   => '#555555',

         // Size
        'label_size'     => __( 'Font Size', $this->plugin_name ),
        'name_size'      => 'post_meta_lgx_counter_generator[lgx_item_desc_font_size]',
        'id_size'        => 'lgx_item_desc_font_size',
        'default_size'   => '14px',
        'status_size'    => LGX_WCU_PLUGIN_META_FIELD_PRO,

        //Weight
        'label_weight'     => __( 'Font Weight', $this->plugin_name ),
        'name_weight'      => 'post_meta_lgx_counter_generator[lgx_item_desc_font_weight]',
        'id_weight'        => 'lgx_item_desc_font_weight',
        'default_weight'   => '400',
        'status_weight'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
    )
);



$this->meta_form->textMulti(
    array(
        'label'     => __( 'Item Description Margin', $this->plugin_name ),
        'desc'      => __( 'Set top & bottom margin for item Description.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_multi_text_desc_margin]',
        'id'        => 'lgx_multi_text_desc_margin',

        'label_1'     => __( 'Top Margin', $this->plugin_name ),
        'name_1'      => 'post_meta_lgx_counter_generator[lgx_item_top_margin_desc]',
        'id_1'        => 'lgx_item_top_margin_desc',
        'status_1'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_1'   => '0px',

        'label_2'     => __( 'Bottom Margin', $this->plugin_name ),
        'name_2'      => 'post_meta_lgx_counter_generator[lgx_item_bottom_margin_desc]',
        'id_2'        => 'lgx_item_bottom_margin_desc',
        'status_2'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_2'   => '0px'    
    )
);

/********************************************************************************/
$this->meta_form->header_spacer(
    array(
        'label'     => __( 'Counter Value Settings', $this->plugin_name ),
    )
);
/********************************************************************************/



$this->meta_form->textTypo(
    array(
        'label'     => __( 'Counter Value', $this->plugin_name ),
        'desc'      => __( 'Set Typography for Item Counter Value.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_text_type_item_value]',
        'id'        => 'lgx_text_type_item_value',
        

        // Color
        'label_color'     => __( 'Font Color', $this->plugin_name ),
        'name_color'      => 'post_meta_lgx_counter_generator[lgx_item_value_color]',
        'id_color'        => 'lgx_item_value_color',
        'default_color'   => '#111111',

         // Size
        'label_size'     => __( 'Font Size', $this->plugin_name ),
        'name_size'      => 'post_meta_lgx_counter_generator[lgx_item_value_font_size]',
        'id_size'        => 'lgx_item_value_font_size',
        'default_size'   => '16px',
        'status_size'    => LGX_WCU_PLUGIN_META_FIELD_PRO,

        //Weight
        'label_weight'     => __( 'Font Weight', $this->plugin_name ),
        'name_weight'      => 'post_meta_lgx_counter_generator[lgx_item_value_font_weight]',
        'id_weight'        => 'lgx_item_value_font_weight',
        'default_weight'   => '600',
        'status_weight'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
    )
);

$this->meta_form->textMulti(
    array(
        'label'     => __( 'Counter Value Margin', $this->plugin_name ),
        'desc'      => __( 'Set top & bottom margin for item counter Value.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_text_type_item_value_margin]',
        'id'        => 'lgx_text_type_item_value_margin',

        'label_1'     => __( 'Top', $this->plugin_name ),
        'name_1'      => 'post_meta_lgx_counter_generator[lgx_item_top_margin_value]',
        'id_1'        => 'lgx_item_top_margin_value',
        'status_1'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_1'   => '0px',

        'label_2'     => __( 'Bottom', $this->plugin_name ),
        'name_2'      => 'post_meta_lgx_counter_generator[lgx_item_bottom_margin_value]',
        'id_2'        => 'lgx_item_bottom_margin_value',
        'status_2'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_2'   => '0px'    
    )
);

$this->meta_form->textMulti(
    array(
        'label'     => __( 'Counter Value Dimension', $this->plugin_name ),
        'desc'      => __( 'Set top & bottom margin for item counter Value. <br> <span style="color: #e31919">Note: If you enable border, this dimension  will be mandatory.</span>', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_text_type_item_value_dimension]',
        'id'        => 'dimension',

        'label_1'     => __( 'Width', $this->plugin_name ),
        'name_1'      => 'post_meta_lgx_counter_generator[lgx_value_width]',
        'id_1'        => 'lgx_value_width',
        'status_1'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_1'   => 'auto',

        'label_2'     => __( 'Height', $this->plugin_name ),
        'name_2'      => 'post_meta_lgx_counter_generator[lgx_value_height]',
        'id_2'        => 'lgx_value_height',
        'status_2'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_2'   => 'auto'    
    )
);



$this->meta_form->switch(
    array(
        'yes_label' => __( 'Enabled', $this->plugin_name ),
        'no_label' => __( 'Disabled', $this->plugin_name ),
        'label'     => __( 'Counter Value Border', $this->plugin_name ),
        'desc'      => __( 'Enable Border for Counter Value.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_value_border_color_en]',
        'id'        => 'lgx_value_border_color_en',
        'default'   => 'no'
    )
);

$this->meta_form->borderTypo(
    array(
        'label'     => __( 'Counter Value Border', $this->plugin_name ),
        'desc'      => __( 'Choose border style for icon image.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_value_border_color_group]',
        'id'        => 'lgx_value_border_color_group',

        'label_color'     => __( 'Color', $this->plugin_name ),
        'name_color'      => 'post_meta_lgx_counter_generator[lgx_value_border_color]',
        'id_color'        => 'lgx_value_border_color',
        'default_color'   => '#F9f9f9',

        'label_width'     => __( 'Width', $this->plugin_name ),
        'name_width'      => 'post_meta_lgx_counter_generator[lgx_value_border_width]',
        'id_width'        => 'lgx_value_border_width',
        'status_width'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_width'   => '1px',

        'label_radius'     => __( 'Radius', $this->plugin_name ),
        'desc_radius'      => __( 'Set Border Radius for showcase logo Image.', $this->plugin_name ),
        'name_radius'      => 'post_meta_lgx_counter_generator[lgx_value_border_radius]',
        'id_radius'        => 'lgx_value_border_radius',
        'status_radius'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_radius'   => '100px',

        'label_hColor'     => __( 'Hover Color', $this->plugin_name ),
        'name_hColor'      => 'post_meta_lgx_counter_generator[lgx_value_border_color_hover]',
        'id_hColor'        => 'lgx_value_border_color_hover',
        'status_hColor'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_hColor'   => '#F9f9f9',
    )
);

/********************************************************************************/
$this->meta_form->header_spacer(
    array(
        'label'     => __( 'Icon Image Settings', $this->plugin_name ),
    )
);
/********************************************************************************/

$this->meta_form->switch(
    array(
        'label' => __( 'Enable Icon', $this->plugin_name ),
        'yes_label' => __( 'Show', $this->plugin_name ),
        'no_label' => __( 'Hide', $this->plugin_name ),
        'desc' => __( 'Show item icon in your showcase.', $this->plugin_name ),
        'name' => 'post_meta_lgx_counter_generator[lgx_item_icon_en]',
        'id' => 'lgx_item_icon_en',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default' => 'yes'
    )
);

$this->meta_form->text(
    array(
        'label'     => __( 'Icon Padding', $this->plugin_name ),
        'desc'      => __( 'Add padding of the icon image. Default: 0px . You can add your suitable unit. E.g. 10px or 1rem.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_icon_padding]',
        'id'        => 'lgx_icon_padding',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => '0px'
    )
);



$this->meta_form->switch(
    array(
        'label'     => __( 'Icon Background Color', $this->plugin_name ),
        'yes_label' => __( 'Enabled', $this->plugin_name ),
        'no_label' => __( 'Disabled', $this->plugin_name ),
        'desc'      => __( 'Enable Background Color for all icon image.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_icon_bg_color_en]',
        'id'        => 'lgx_icon_bg_color_en',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => 'no'
    )
);


$this->meta_form->bgColorTypo(
    array(
        'label'     => __( 'Icon Image Background', $this->plugin_name ),
        'desc'      => __( 'Please select item background color.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_icon_bg_typo]',
        'id'        => 'lgx_icon_bg_typo',

        'label_color'     => __( 'BG Color', $this->plugin_name ),
        'name_color'      => 'post_meta_lgx_counter_generator[lgx_icon_bg_color]',
        'id_color'        => 'lgx_icon_bg_color',
        'status_color'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_color'   => '#f1f1f1',

        'label_hColor'     => __( 'Hover BG Color', $this->plugin_name ),
        'name_hColor'      => 'post_meta_lgx_counter_generator[lgx_icon_bg_color_hover]',
        'id_hColor'        => 'lgx_icon_bg_color_hover',
        'status_hColor'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_hColor'   => '#f1f1f1',

    )
);


$this->meta_form->switch(
    array(
        'yes_label' => __( 'Enabled', $this->plugin_name ),
        'no_label' => __( 'Disabled', $this->plugin_name ),
        'label'     => __( 'Icon Border', $this->plugin_name ),
        'desc'      => __( 'Enable Border for all Icon Image.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_img_border_color_en]',
        'id'        => 'lgx_img_border_color_en',
        'default'   => 'no'
    )
);

$this->meta_form->borderTypo(
    array(
        'label'     => __( 'Icon Border', $this->plugin_name ),
        'desc'      => __( 'Choose border style for icon image.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_img_border_color_group]',
        'id'        => 'lgx_img_border_color_group',

        'label_color'     => __( 'Color', $this->plugin_name ),
        'name_color'      => 'post_meta_lgx_counter_generator[lgx_img_border_color]',
        'id_color'        => 'lgx_img_border_color',
        'default_color'   => '#FF5151',

        'label_width'     => __( 'Width', $this->plugin_name ),
        'name_width'      => 'post_meta_lgx_counter_generator[lgx_img_border_width]',
        'id_width'        => 'lgx_img_border_width',
        'status_width'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_width'   => '1px',

        'label_radius'     => __( 'Radius', $this->plugin_name ),
        'desc_radius'      => __( 'Set Border Radius for showcase logo Image.', $this->plugin_name ),
        'name_radius'      => 'post_meta_lgx_counter_generator[lgx_img_border_radius]',
        'id_radius'        => 'lgx_img_border_radius',
        'status_radius'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_radius'   => '4px',

        'label_hColor'     => __( 'Hover Color', $this->plugin_name ),
        'name_hColor'      => 'post_meta_lgx_counter_generator[lgx_img_border_color_hover]',
        'id_hColor'        => 'lgx_img_border_color_hover',
        'status_hColor'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_hColor'   => '#FF9B6A',
    )
);



$this->meta_form->group2SelectText(
    array(
        'label'       => __( 'Icon Height', $this->plugin_name ),
        'desc'        => __( 'Set Height of the icon image. Default: 100% . You can add your desired height with suitable unit. E.g. 100px or 10rem.', $this->plugin_name ),
        'id'          => 'lgx_item_icon_dimension_height',
        'name'        => 'post_meta_lgx_counter_generator[lgx_item_icon_dimension_height]',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,

        'label_select' => 'Properties',
        'name_select' => 'post_meta_lgx_counter_generator[lgx_item_icon_property_height]',
        'id_select'   => 'lgx_item_icon_property_height',
        'default_select'   => 'max-height',
        'options_select'   => array(
            'max-height' => __( 'Max Height', $this->plugin_name ),
            'height'   => __( 'Height', $this->plugin_name ),
            'min-height' => __( 'Min Height', $this->plugin_name )
        ),

        'label_text' => 'Value',
        'name_text'  => 'post_meta_lgx_counter_generator[lgx_item_icon_height]',
        'id_text'    => 'lgx_item_icon_height',
        'default_text' => 'auto'
    )
);

$this->meta_form->group2SelectText(
    array(
        'label'       => __( 'Icon Width', $this->plugin_name ),
        'desc'        => __( 'Set Width of the icon image. Default: 100% . You can add your desired Width with suitable unit. E.g. 100px or 10rem.', $this->plugin_name ),
        'id'          => 'lgx_item_icon_dimension_width',
        'name'        => 'post_meta_lgx_counter_generator[lgx_item_icon_dimension_width]',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,

        'label_select' => 'Properties',
        'name_select' => 'post_meta_lgx_counter_generator[lgx_item_icon_property_width]',
        'id_select'   => 'lgx_item_icon_property_width',
        'default_select'   => 'max-width',
        'options_select'   => array(
            'max-width' => __( 'Max Width', $this->plugin_name ),
            'width'   => __( 'Width', $this->plugin_name ),
            'min-width' => __( 'Min Width', $this->plugin_name )
        ),

        'label_text' => 'Value',
        'name_text'  => 'post_meta_lgx_counter_generator[lgx_item_icon_width]',
        'id_text'    => 'lgx_item_icon_width',
        'default_text' => '100%'
    )
);


/********************************************************************************/
$this->meta_form->header_spacer(
    array(
        'label'     => __( 'Single Item Settings', $this->plugin_name ),
    )
);
/********************************************************************************/
$this->meta_form->buy_pro(
    array(
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'link' => 'https://logichunt.com/product/wordpress-counter-up/',
    )
);

$this->meta_form->select(
    array(
        'label'     => __( 'Item Info Align', $this->plugin_name ),
        'desc'      => __( 'Set Item Title and description Alignment.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_info_align]',
        'id'        => 'lgx_item_info_align',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => 'center',
        'options'   => array(
            'center' => __( 'Center', $this->plugin_name ),
            'left' => __( 'Left', $this->plugin_name ),
            'right' => __( 'Right', $this->plugin_name )
        )
    )
);


$this->meta_form->text(
    array(
        'label'     => __( 'Item Margin', $this->plugin_name ),
        'desc'      => __( 'Set single item margin with suitable unit. Also, you can use the shorthand margin property.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_margin]',
        'id'        => 'lgx_item_margin',
        'default'   => '0px'
    )
);


$this->meta_form->text(
    array(
        'label'     => __( 'Item padding', $this->plugin_name ),
        'desc'      => __( 'Set single item padding with suitable unit. Also, you can use the shorthand padding property.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_padding]',
        'id'        => 'lgx_item_padding',
        'status'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => '0px'
    )
);


$this->meta_form->switch(
    array(
        'label'     => __( 'Item Border', $this->plugin_name ),
        'yes_label' => __( 'Enabled', $this->plugin_name ),
        'no_label' => __( 'Disabled', $this->plugin_name ),
        'desc'      => __( 'Enable Border for all item.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_border_color_en]',
        'id'        => 'lgx_border_color_en',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => 'no'
    )
);


$this->meta_form->borderTypo(
    array(
        'label'     => __( 'Item Border', $this->plugin_name ),
        'desc'      => __( 'Choose border style for icon image.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_border_color_group]',
        'id'        => 'lgx_item_border_color_group',
        

        'label_color'     => __( 'Color', $this->plugin_name ),
        'name_color'      => 'post_meta_lgx_counter_generator[lgx_item_border_color]',
        'id_color'        => 'lgx_item_border_color',
        'default_color'   => '#FF5151',

        'label_width'     => __( 'Width', $this->plugin_name ),
        'name_width'      => 'post_meta_lgx_counter_generator[lgx_item_border_width]',
        'id_width'        => 'lgx_item_border_width',
        'status_width'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_width'   => '1px',

        'label_radius'     => __( 'Radius', $this->plugin_name ),
        'desc_radius'      => __( 'Set Border Radius for showcase logo Image.', $this->plugin_name ),
        'name_radius'      => 'post_meta_lgx_counter_generator[lgx_item_border_radius]',
        'id_radius'        => 'lgx_item_border_radius',
        'status_radius'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_radius'   => '4px',

        'label_hColor'     => __( 'Hover Color', $this->plugin_name ),
        'name_hColor'      => 'post_meta_lgx_counter_generator[lgx_item_border_color_hover]',
        'id_hColor'        => 'lgx_item_border_color_hover',
        'status_hColor'    => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_hColor'   => '#FF9B6A',
    )
);



$this->meta_form->switch(
    array(
        'label'     => __( 'Item Background Color', $this->plugin_name ),
        'yes_label' => __( 'Enabled', $this->plugin_name ),
        'no_label' => __( 'Disabled', $this->plugin_name ),
        'desc'      => __( 'Enable Background Color for all item.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_bg_color_en]',
        'id'        => 'lgx_item_bg_color_en',
        'status'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default'   => 'no'
    )
);


$this->meta_form->bgColorTypo(
    array(
        'label'     => __( 'Item Background', $this->plugin_name ),
        'desc'      => __( 'Please select item background color.', $this->plugin_name ),
        'name'      => 'post_meta_lgx_counter_generator[lgx_item_bg_typo]',
        'id'        => 'lgx_item_bg_typo',

        'label_color'     => __( 'BG Color', $this->plugin_name ),
        'name_color'      => 'post_meta_lgx_counter_generator[lgx_item_bg_color]',
        'id_color'        => 'lgx_item_bg_color',
        'status_color'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_color'   => '#f1f1f1',

        'label_hColor'     => __( 'Hover BG Color', $this->plugin_name ),
        'name_hColor'      => 'post_meta_lgx_counter_generator[lgx_item_bg_color_hover]',
        'id_hColor'        => 'lgx_item_bg_color_hover',
        'status_hColor'  => LGX_WCU_PLUGIN_META_FIELD_PRO,
        'default_hColor'   => '#f1f1f1',

    )
);
