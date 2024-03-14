<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

//
// Framework Settings
//
$settings            = array(
  'option_name'      => 'scroll_to_up_pro',
  'menu_title'       => SETTINGS_PAGE_TITLE,
  'menu_type'        => 'options', // menu, submenu, options, theme, etc.
  'menu_slug'        => 'scroll_to_up_pro',
  'show_search'      => true,
  'show_reset'       => true,
  'show_footer'      => true,
  'show_all_options' => true,
  'ajax_save'        => false,
  'sticky_header'    => true,
  'save_defaults'    => true,
  'framework_title'  => SETTINGS_PAGE_TITLE,
);



$image_arrows = array();
for($i = 1 ; $i <= 41 ; $i++){
  $image_arrows[$i] = plugin_dir_url(__FILE__).'../../../assets/arrows/'.$i.'.png';
}


//
// Framework Options
//
$options   = array();

$options[] = array(
  'name'   => 'overwiew',
  'title'  => 'Overview',
  'icon'   => 'fa fa-star',
  'fields' => array(
//*******************************

    array(
      'type' => 'heading',
      'content' => 'Basic Settings'
    ),
    array(
      'id'    => 'scroll_distance',
      'type'  => 'number',
      'title' => 'Scroll Distance',
      'default' => 300,
      'after' => '<i class="cs-text-muted">px</i>',
      'desc' => 'Distance from top/bottom before showing element (px)'
    ),
    array(
      'id'    => 'scroll_speed',
      'type'  => 'number',
      'title' => 'Scroll Speed',
      'default' => 300,
      'after' => '<i class="cs-text-muted">px</i>',
      'desc' => 'Speed back to top (ms)'
    ),
    array(
      'id'             => 'button_animation',
      'type'           => 'radio',
      'title'          => 'Button Animation',
      'options'        => array(
        'fade'    => 'Fade',
        'slide'   => 'Slide',
        'none'    => 'None'
      ),
      'default' => 'fade',
    ),
    array(
      'id'             => 'button_position',
      'type'           => 'radio',
      'title'          => 'Button Position',
      'desc'           => 'Select Scroll to up button position',
      'options'        => array(
        'bottom_right'    => 'Bottom right',
        'bottom_left'    => 'Bottom left',
        'vertically_middle_left'    => 'Vertically middle left',
        'vertically_middle_right'    => 'Vertically middle right',
      ),
      'attributes'   => array(
        'data-depend-id' => 'button_position_dep',
      ),
      'default' => 'bottom_right',
    ),
    array(
      'id'    => 'distance_left',
      'type'  => 'number',
      'title' => 'Distance from left',
      'default' => 20,
      'after' => '<i class="cs-text-muted">px</i>',
      'dependency'   => array( 'button_position_dep', 'any', 'bottom_left,vertically_middle_left' )
    ),
    array(
      'id'    => 'distance_right',
      'type'  => 'number',
      'title' => 'Distance from right',
      'default' => 20,
      'after' => '<i class="cs-text-muted">px</i>',
      'dependency'   => array( 'button_position_dep', 'any', 'bottom_right,vertically_middle_right' )
    ),
    array(
      'id'    => 'distance_bottom',
      'type'  => 'number',
      'title' => 'Distance from bottom',
      'default' => 20,
      'after' => '<i class="cs-text-muted">px</i>',
      'dependency'   => array( 'button_position_dep', 'any', 'bottom_right,bottom_left' )
    ),
    array(
      'id'             => 'scroll_to_up_method',
      'type'           => 'radio',
      'title'          => 'Select "Scroll To Up" Method',
      'options'        => array(
        'simple_txt'  => 'Simple Text',
        'fa_icon'     => 'FontAwesome Icon',
        'image_arrow' => 'Image Arrow',
        'own_image' => 'Your own Image'
      ),
      'default' => 'fa_icon',
    ),
    
    
/**
 * Simple Text 
 */
    array(
      'id' => 'simple_txt_label',
      'type' => 'text',
      'title' => 'Label',
      'default' => 'Scroll to Up',
      'desc' => 'Scroll To Up button Label',
      'dependency' => array( 'scroll_to_up_method_simple_txt', '==', 'true' )
    ),
    array(
      'id' => 'simple_txt_font_size',
      'type' => 'number',
      'title' => 'Font Size',
      'default' => '18',
      'after' => '<i class="cs-text-muted">px</i>',
      'dependency' => array( 'scroll_to_up_method_simple_txt', '==', 'true' )
    ),
    array(
      'id' => 'simple_txt_btn_border_radius',
      'type' => 'number',
      'title' => 'Button Border radius',
      'default' => '0',
      'after' => '<i class="cs-text-muted">px</i>',
      'dependency' => array( 'scroll_to_up_method_simple_txt', '==', 'true' )
    ),
    array(
      'id' => 'simple_txt_color',
      'type' => 'color_picker',
      'title' => 'Text Color',
      'default' => '#fff',
      'dependency' => array( 'scroll_to_up_method_simple_txt', '==', 'true' )
    ),
    array(
      'id' => 'simple_txt_bgcolor',
      'type' => 'color_picker',
      'title' => 'Backgrround color',
      'default' => '#555',
      'dependency' => array( 'scroll_to_up_method_simple_txt', '==', 'true' )
    ),
    array(
      'id' => 'simple_txt_hovercolor',
      'type' => 'color_picker',
      'title' => 'Hover Text color',
      'default' => '#fff',
      'dependency' => array( 'scroll_to_up_method_simple_txt', '==', 'true' )
    ),
    array(
      'id' => 'simple_txt_hover_bg_color',
      'type' => 'color_picker',
      'title' => 'Hover background color',
      'default' => '#999',
      'dependency' => array( 'scroll_to_up_method_simple_txt', '==', 'true' )
    ),

    
/**
 * Font Awesome 
 */
    array(
      'id' => 'fa_icon',
      'type' => 'icon',
      'title' => 'Icon',
      'default' => 'fa fa-angle-double-up',
      'dependency' => array( 'scroll_to_up_method_fa_icon', '==', 'true' )
    ),
    
    array(
      'id' => 'fa_icon_color',
      'type' => 'color_picker',
      'title' => 'Icon color',
      'default' => '#fff',
      'dependency' => array( 'scroll_to_up_method_fa_icon', '==', 'true' )
    ),
    array(
      'id' => 'fa_icon_icon_size',
      'type' => 'number',
      'title' => 'Icon size',
      'default' => '18',
      'after' => '<i class="cs-text-muted">px</i>',
      'dependency' => array( 'scroll_to_up_method_fa_icon', '==', 'true' )
    ),
    array(
      'id' => 'fa_icon_border_radius',
      'type' => 'number',
      'title' => 'Icon size',
      'default' => '0',
      'after' => '<i class="cs-text-muted">px</i>',
      'dependency' => array( 'scroll_to_up_method_fa_icon', '==', 'true' )
    ),
    array(
      'id' => 'fa_icon_bgcolor',
      'type' => 'color_picker',
      'title' => 'Background color',
      'default' => '#555',
      'dependency' => array( 'scroll_to_up_method_fa_icon', '==', 'true' )
    ),
    array(
      'id' => 'fa_icon_hover_color',
      'type' => 'color_picker',
      'title' => 'Hover icon color',
      'default' => '#fff',
      'dependency' => array( 'scroll_to_up_method_fa_icon', '==', 'true' )
    ),
    array(
      'id' => 'fa_icon_hover_bgcolor',
      'type' => 'color_picker',
      'title' => 'Hover Background color',
      'default' => '#999',
      'dependency' => array( 'scroll_to_up_method_fa_icon', '==', 'true' )
    ),
    
    
 
 /**
  * Upload background image
  */
    array(
      'id' => 'uploaded_image',
      'type' => 'upload',
      'title' => 'Hover icon color',
      'default' => plugin_dir_url(__FILE__).'../../../assets/arrows/up-128.png',
      'dependency' => array( 'scroll_to_up_method_own_image', '==', 'true' )
    ),
    array(
      'id' => 'uploaded_image_width',
      'type' => 'number',
      'title' => 'Width',
      'default' => '50',
      'after' => '<i class="cs-text-muted">px</i>',
      'desc'  => 'Image width',
      'dependency' => array( 'scroll_to_up_method_own_image', '==', 'true' )
    ),
    
    array(
      'id' => 'uploaded_image_height',
      'type' => 'number',
      'title' => 'Height',
      'default' => '50',
      'after' => '<i class="cs-text-muted">px</i>',
      'desc'  => 'Image Height',
      'dependency' => array( 'scroll_to_up_method_own_image', '==', 'true' )
    ),
    
    
    /**
     * Image arrow
     */ 
    array(
      'id'           => 'image_arrows',
      'type'         => 'image_select',
      'title'        => 'Image Select (Radio) with Default',
      'options'      => $image_arrows,
      'radio'        => true,
      'default'      => 'images_arrow_41',
  'dependency' => array( 'scroll_to_up_method_image_arrow', '==', 'true' )
    ),
    array(
      'id' => 'image_arrow_width',
      'type' => 'number',
      'title' => 'Width',
      'default' => '50',
      'after' => '<i class="cs-text-muted">px</i>',
      'desc'  => 'Image width',
      'dependency' => array( 'scroll_to_up_method_image_arrow', '==', 'true' )
    ),
    
    array(
      'id' => 'image_arrow_height',
      'type' => 'number',
      'title' => 'Height',
      'default' => '50',
      'after' => '<i class="cs-text-muted">px</i>',
      'desc'  => 'Image Height',
      'dependency' => array( 'scroll_to_up_method_image_arrow', '==', 'true' )
    ),
    
    
//*******************************
  )
);



  
  








CSF_Options::instance( $settings, $options );
