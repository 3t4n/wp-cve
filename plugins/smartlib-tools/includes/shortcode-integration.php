<?php

add_filter( 'su/data/shortcodes', 'smartlib_register_custom_shortcode' );

/**
 * Filter to modify original shortcodes data and add custom shortcodes
 *
 * @param array   $shortcodes Original plugin shortcodes
 * @return array Modified array
 */
function smartlib_register_custom_shortcode( $shortcodes ) {

    // Add new shortcode
    $shortcodes['icon_block'] = array(
        'name' => __( 'Icon Block', 'su' ),
        'type' => 'single',
        'group' => 'box',
        'atts' => array(
            'title' => array(
                'values' => array( ),
                'default' => __( 'Service title', 'su' ),
                'name' => __( 'Title', 'su' ),
                'desc' => __( 'Block name', 'su' )
            ),
            'icon' => array(
                'type' => 'icon',
                'default' => '',
                'name' => __( 'Icon', 'su' ),
                'desc' => __( 'You can upload custom icon for this box', 'su' )
            ),
            'icon_color' => array(
                'type' => 'color',
                'default' => '#333333',
                'name' => __( 'Icon color', 'su' ),
                'desc' => __( 'This color will be applied to the selected icon. Does not works with uploaded icons', 'su' )
            ),
            'align' =>array(
                'type' => 'select',
                // Available values
                'values' => array(
                    'left' => __( 'Align Left', 'smartlib' ),
                    'right' => __( 'Align Right', 'smartlib' )
                ),
                'default' => 'left',
                    'name' => __( 'Icon Align', 'smartlib' ),
                    // Attribute description

                ),
            'align_text' =>array(
                'type' => 'select',
                // Available values
                'values' => array(
                    'left' => __( 'Align Left', 'smartlib' ),
                    'right' => __( 'Align Right', 'smartlib' )
                ),
                'default' => 'left',
                'name' => __( 'Text Align', 'smartlib' ),
                // Attribute description

            ),

            'block_animation' =>array(
                'type' => 'select',
                // Available values
                'values' => array(
                    'no_animation' => __( 'No Animation', 'smartlib' ),
                    'fadeInLeft' => __( 'Fade In Left', 'smartlib' ),
                    'fadeInRight' => __( 'Fade In Right', 'smartlib' ),
                    'bounceIn' => __( 'Bounce In', 'smartlib' ),
                ),
                'default' => 'no_animation',
                'name' => __( 'Block Animation', 'smartlib' ),
                // Attribute description

            ),
            'animation_delay' => array(
                'type' => 'slider',
                'min' => 0.1,
                'max' => 2,
                'step' => 0.1,
                'default' => 0.3,
                'name' => __( 'Animation delay', 'su' ),
                'desc' => __( 'Specifies a delay for the start of a block animation', 'smartlib' )
            ),

            'size' => array(
                'type' => 'slider',
                'min' => 10,
                'max' => 128,
                'step' => 2,
                'default' => 32,
                'name' => __( 'Icon size', 'su' ),
                'desc' => __( 'Size of the uploaded icon in pixels', 'su' )
            ),
            'class' => array(
                'default' => '',
                'name' => __( 'Class', 'su' ),
                'desc' => __( 'Extra CSS class', 'su' )
            ),
            'box_content' => array(
                'default' => '',
                'name' => __( 'Box content', 'smartlib' ),

            )

        ),

        'desc' => __( 'Service box with title', 'su' ),
        'icon' => 'check-square-o',
        'function' => 'smartlib_icon_box_shortcode'
    );

    $shortcodes['column'] = array(
        'name' => __( 'Column', 'su' ),
        'type' => 'wrap',
        'group' => 'box',
        'atts' => array(
            'size' => array(
                'type' => 'select',
                'values' => array(
                    'col-md-12' => __( 'Full width', 'su' ),
                    'col-md-6' => __( 'One half', 'su' ),
                    'col-md-4' => __( 'One third', 'su' ),
                    'col-md-8' => __( 'Two third', 'su' ),
                    'col-md-3' => __( 'One fourth', 'su' ),
                    'col-md-9' => __( 'Three fourth', 'su' ),
                    'col-md-2' => __( 'One sixth', 'su' ),
                    'col-md-1' => __( 'One twelfth', 'su' ),
                ),
                'default' => 'col-md-6',
                'name' => __( 'Size', 'su' ),
                'desc' => __( 'Select column width. This width will be calculated depend page width', 'su' )
            ),
            'center' => array(
                'type' => 'bool',
                'default' => 'no',
                'name' => __( 'Centered', 'su' ),
                'desc' => __( 'Is this column centered on the page', 'su' )
            ),
            'class' => array(
                'default' => '',
                'name' => __( 'Class', 'su' ),
                'desc' => __( 'Extra CSS class', 'su' )
            )
        ),
        'content' => __( 'Column content', 'su' ),
        'desc' => __( 'Flexible and responsive columns', 'su' ),
        'note' => __( 'Did you know that you need to wrap columns with [row] shortcode?', 'su' ),
        'example' => 'columns',
        'icon' => 'columns',
        'function' => 'smartlib_column_shortcode'
    );
    // Return modified data
    $shortcodes['row'] = array(
        'name' => __( 'Row', 'su' ),
        'type' => 'wrap',
        'group' => 'box',
        'atts' => array(
            'class' => array(
                'default' => '',
                'name' => __( 'Class', 'su' ),
                'desc' => __( 'Extra CSS class', 'su' )
            )
        ),
        'content' => __( "[%prefix_column size=\"col-md-4\"]Content[/%prefix_column]\n[%prefix_column size=\"col-md-4\"]Content[/%prefix_column]\n[%prefix_column size=\"col-md-4\"]Content[/%prefix_column]", 'su' ),
        'desc' => __( 'Row for flexible columns', 'su' ),
        'icon' => 'columns',
        'function' => 'smartlib_row_shortcode'
    );

    // button
    $shortcodes['button_default'] = array(
        'name' => __( 'BootFrame button', 'su' ),
        'type' => 'single',
        'group' => 'content',
        'atts' => array(
            'url' => array(
                'values' => array( ),
                'default' => get_option( 'home' ),
                'name' => __( 'Link', 'su' ),
                'desc' => __( 'Button link', 'su' )
            ),
            'target' => array(
                'type' => 'select',
                'values' => array(
                    'self' => __( 'Same tab', 'su' ),
                    'blank' => __( 'New tab', 'su' )
                ),
                'default' => 'self',
                'name' => __( 'Target', 'su' ),
                'desc' => __( 'Button link target', 'su' )
            ),

            'type' => array(
                'type' => 'select',
                'values' => array(
                    'default' => __( 'Default'),
                    'primary' => __( 'Primary'),
                    'success' => __( 'Success'),
                    'info' => __( 'Info', 'su' ),
                    'warning' => __( 'Warning'),
                    'danger' => __( 'Danger'),

                ),
                'default' => 'default',
                'name' => __( 'Button Type', 'su' ),
                'desc' => __( 'Button styles based on Bootstrap classes', 'su' )
            ),



            'size' => array(
                'type' => 'select',
                'values' => array(
                    '' => __( 'Normal', 'su' ),
                    'btn-lg' => __( 'Large', 'su' ),
                    'btn-sm' => __( 'Small', 'su' ),
                    'btn-xs' => __( 'Mini', 'su' ),
                ),
                'default' => '',
                'name' => __( 'Button Size', 'su' ),
                'desc' => __( 'Button size based on Bootstrap classes', 'su' )
            ),


            'rel' => array(
                'default' => '',
                'name' => __( 'Rel attribute', 'su' ),
                'desc' => __( 'Here you can add value for the rel attribute.<br>Example values: <b%value>nofollow</b>, <b%value>lightbox</b>', 'su' )
            ),
            'title' => array(
                'default' => '',
                'name' => __( 'Title attribute', 'su' ),
                'desc' => __( 'Here you can add value for the title attribute', 'su' )
            ),
            'class' => array(
                'default' => '',
                'name' => __( 'Class', 'su' ),
                'desc' => __( 'Extra CSS class', 'su' )
            ),
            'button_text' => array(
        'default' => '',
        'name' => __( 'Button Text', 'smartlib' ),

    )
        ),
        'content' => __( 'Button text', 'su' ),
        'desc' => __( 'Styled button', 'su' ),
        'function' => 'smartlib_button_default_shortcode',
        'icon' => 'heart'
    );

    $shortcodes['single_icon'] = array(
        'name' => __( 'Single Icon', 'su' ),
        'type' => 'single',
        'group' => 'box',
        'atts' => array(

            'icon' => array(
                'type' => 'icon',
                'default' => '',
                'name' => __( 'Icon', 'su' ),
                'desc' => __( 'You can upload custom icon for this box', 'su' )
            ),
            'icon_color' => array(
                'type' => 'color',
                'default' => '#333333',
                'name' => __( 'Icon color', 'su' ),
                'desc' => __( 'This color will be applied to the selected icon. Does not works with uploaded icons', 'su' )
            ),


            'size' => array(
                'type' => 'slider',
                'min' => 10,
                'max' => 128,
                'step' => 2,
                'default' => 32,
                'name' => __( 'Icon size', 'su' ),
                'desc' => __( 'Size of the uploaded icon in pixels', 'su' )
            ),


        ),

        'desc' => __( 'Single Icon', 'su' ),
        'icon' => 'hand-o-right',
        'function' => 'smartlib_icon_single_shortcode'
    );
    return $shortcodes;
}


/**
 * Heading2 shortcode function
 *
 * @param array   $atts    Shortcode attributes
 * @param string  $content Shortcode content
 * @return string Shortcode markup
 */
function smartlib_icon_box_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( array(
        'title'       => __( 'Service title', 'su' ),
        'icon'        => plugins_url( 'assets/images/service.png', SU_PLUGIN_FILE ),
        'icon_color'  => '#333',
        'size'        => 32,
        'align'        => 'left',
        'align_text'        => 'left',
        'class'       => '',
        'block_animation' => 'fadeInLeft',
        'animation_delay' => '0.3',
        'box_content'=> '',
    ), $atts );
    // RTL
    $rtl = ( is_rtl() ) ? 'right' : 'left';

    // Built-in icon

    $animation_attributes = '';

    $class = 'media feature';

    if($atts['block_animation'] !='no_animation'){
        $animation_attributes ='data-os-animation="'.$atts['block_animation'].'" data-os-animation-delay="'.$atts['animation_delay'].'s"';
        $class = 'media feature smartlib-animate-object';
    }


    $box_content = '<div class="'.$class.'" '.$animation_attributes.'>';
    if ( strpos( $atts['icon'], 'icon:' ) !== false ) {
        $atts['icon'] = '<a href="#" class="pull-'.$atts['align'].' smartlib-icon smartlib-icon-large"><i class="fa fa-' . trim( str_replace( 'icon:', '', $atts['icon'] ) ) . '" style="font-size:' . $atts['size'] . 'px;color:' . $atts['icon_color'] . '"></i></a>';
        su_query_asset( 'css', 'font-awesome' );
    }
    // Uploaded icon
    else {
        $atts['icon'] = '<a href="#" class="pull-'.$atts['align'].' smartlib-icon smartlib-icon-large"><img src="' . $atts['icon'] . '" width="' . $atts['size'] . '" height="' . $atts['size'] . '" alt="' . $atts['title'] . '" /></a>';
    }

    $box_content .= $atts['icon'] .'<div class="media-body text-'.$atts['align_text'].'"><h4 class="media-heading">' . su_scattr( $atts['title'] ) . '</h4>' . $atts['box_content'] . '</div></div>';

    return $box_content;
}



function smartlib_icon_single_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( array(

        'icon'        => plugins_url( 'assets/images/service.png', SU_PLUGIN_FILE ),
        'icon_color'  => '#333',
        'size'        => 15,

        'box_content'=> '',
    ), $atts );
    // RTL
    $rtl = ( is_rtl() ) ? 'right' : 'left';


    $box_content = '';
    if ( strpos( $atts['icon'], 'icon:' ) !== false ) {
        $atts['icon'] = '<span><i class="fa fa-' . trim( str_replace( 'icon:', '', $atts['icon'] ) ) . '" style="font-size:' . $atts['size'] . 'px;color:' . $atts['icon_color'] . '"></i></span>';
        su_query_asset( 'css', 'font-awesome' );
    }
    // Uploaded icon
    else {
        $atts['icon'] = '<span><img src="' . $atts['icon'] . '" width="' . $atts['size'] . '" height="' . $atts['size'] . '" alt="' . $atts['title'] . '" /></span>';
    }

    $box_content .= $atts['icon'];

    return $box_content;
}

function smartlib_button_default_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( array(
        'url' => '#',
        'target' => '',
        'type' => 'default',
        'size' => '',
        'rel' => '',
        'title' => '',
        'class' =>'',
        'button_text'=> '',
    ), $atts );
    // RTL
    $rtl = ( is_rtl() ) ? 'right' : 'left';

    $title_attr = strlen($atts['title']>0)?' title="'.$atts['title'].'"':'';
    $rel_attr = strlen($atts['rel']>0)?' rel="'.$atts['rel'].'"':'';
    $target_attr = strlen($atts['target']>0)?' target="'.$atts['target'].'"':'';


    $button = '<a href="'.$atts['url'].'" class="btn btn-'.$atts['type']. ' '.$atts['size'].'" '.$title_attr.$rel_attr.$target_attr. '>'.$atts['button_text'].'</a>';


    return $button;
}

function smartlib_column_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(array(
        'size' => 'col-md-6',

        'last' => null,
        'class' => ''
    ), $atts);


    return '<div class="'. $atts['size'].'">' . su_do_shortcode($content, 'c') . '</div>';

}

function smartlib_row_shortcode($atts, $content = null){
    $atts = shortcode_atts( array( 'class' => '' ), $atts );
    return '<div class="row' . su_ecssc( $atts ) . '">' . su_do_shortcode( $content, 'r' ) . '</div>';
}