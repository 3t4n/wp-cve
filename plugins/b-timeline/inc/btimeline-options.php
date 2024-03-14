<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.

//
// Metabox of the PAGE
// Set a unique slug-like ID
//
$prefix = '_bptimeline_';

//
// Create a metabox
//
CSF::createMetabox( $prefix, array(
  'title'        => 'B-Timeline Options',
  'post_type'    => 'btimeline',
  'show_restore' => true,
) );

//
// Create a section
//
CSF::createSection( $prefix, array(
  'title'  => 'Timeline Settings',
  'icon'   => 'fas fa-cog',
  'fields' => array(

    // Fields
    array(
      'id'    => 'timeline_type',
      'title' => 'Timeline Type',
      'type'  => 'button_set',
      'subtitle'=> 'Determines the structure of the Timeline.',
      'desc'  => 'Choose the Style of the Timeline.',
      'options'    => array(
        'vertical'  => 'Vertical',
        'horizontal' => 'Horizontal',
      ),
      'default'    => 'vertical'
    ),
    array(
      'id'    => 'date_location',
      'title' => 'Label / Date Location',
      'type'  => 'button_set',
      'subtitle'=> 'Sets the location of the timeline dates. Top and bottom are only used in horizontal position. Also left and right are only used vertical position',
      'desc'  => 'Choose location of the timeline dates / Labels.',
      'options'    => array(
        'bottom'=> 'Bottom',
        'top'=> 'Top',
        'left'=> 'Left',
        'right'=> 'Right'
      ),
      'default'    => 'right',
    ),
    array(
      'id'      => 'item_datas',
      'type'    => 'group',
      'title'   => __('Timeline Data ', 'b-timeline'),
      'subtitle'=> __('Input Your Timeline Data', 'b-timeline'),
      'button_title' => 'Add New Data',
      'fields'    => array(
        array(
          'id'    => 'date_label',
          'title' => 'Label',
          'type'  => 'text',
          'desc'  => 'Use Year, Month, Day etc Name As Label. Ex:- ( \'2020\', \'Jan\', \'sun\' )',
          'default' => 'Jan'
        ),
        array(
          'id'    => 'item_details',
          'title' => 'Details',
          'type'  => 'wp_editor',
          'desc'  => 'Write / Input Details About Story Here',
          'tinymce'       => true,
          'quicktags'     => true,
          'media_buttons' => true,
          'height'        => '100px',
        ),
      )
    ), // End of Timeline Data
    array(
      'id'    => 'start_item',
      'title' => 'Startup Index',
      'type'  => 'spinner',
      'subtitle'=> 'When using the timeline in horizontal mode, define which item the timeline should start at.',
      'desc'  => 'Choose Startup Item of the Timeline. Input Number ',
      'default'    => '0',
      'dependency' => array(
        array( 'timeline_type', '==', 'horizontal'),
      ),
    ),
    array(
      'id'    => 'move_item',
      'title' => 'Move Item',
      'type'  => 'spinner',
      'subtitle'=> 'When using the timeline in horizontal mode, define how many items to move when clicking a navigation button.',
      'desc'  => 'Choose Move Item of the Timeline.',
      'default'    => '1',
      'dependency' => array(
        array( 'timeline_type', '==', 'horizontal'),
      ),
    ),
    array(
      'id'    => 'visible_items',
      'type' => 'spinner',
      'title'   => 'Visible Items',
      'subtitle'=> 'If using the timeline in horizontal mode, define how many items are visible in the viewport',
      'desc'   => 'Choose Display items to show',
      'default' => '3',
      'dependency' => array(
        array( 'timeline_type', '==', 'horizontal'),
      ),
    ),
    array(
      'id'    => 'vertica_trigger',
      'type'     => 'spinner',
      'title'   => 'Vertical Trigger',
      'subtitle' => 'When using the timeline in vertical mode, define the distance from the bottom of the screen, in percent or pixels, that the items slide into view',
      'desc'   => 'Choose distance from the bottom of the screen,',
      'unit'   => '%',
      'default' => '15',
      'dependency' => array(
        array( 'timeline_type', '==', 'vertical'),
      ),

    ),
    array(
      'id'    => 'rtl_mode',
      'type' => 'switcher',
      'title'   => 'RTL Mode',
      'subtitle' => 'When using the timeline in horizontal mode, RTL defines whether the timeline should start from the right. This overrides the startIndex setting.',
      'desc'   => 'Do you want activate it ?',
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default' => false,
      'dependency' => array(
        array( 'timeline_type', '==', 'horizontal'),
      ),
    ),

  // Typography and Style
  array(
    'type'    => 'notice',
    'style'   => 'success',
    'content' => 'Style & Typography Options : ',
    'class' => 'tm_option_title',
  ),
  array(
    'id'    => 'bar_bg_color',
    'type'  => 'color',
    'title'  => 'Bar Background',
    'subtitle'=> 'Set Timeline Bar Background Color',
    'desc'   => 'Choose Bar Background Color',
    'default'=> '#dddddd',
  ),
  array(
    'id'    => 'bar_dot_color',
    'type'  => 'color',
    'title'  => 'Bar Dot Color',
    'subtitle'=> 'Set Timeline Bar Dot Color',
    'desc'   => 'Choose Dot Color',
    'default'=> '#ddd',
  ),
  array(
    'id'    => 'item_bg',
    'type'  => 'color',
    'title'  => 'Item Background',
    'subtitle'=> 'Set Timeline Item Background Color',
    'desc'   => 'Choose Background Color',
    'default'=> '#ffffff'
  ),
  array(
    'id'    => 'item_color',
    'type'  => 'color',
    'title'  => 'Item Color',
    'subtitle'=> 'Set Timeline Item Content / Text Color',
    'desc'   => 'Choose Font Color',
    'default'=> '#333333'
  ),
  array(
    'id'    => 'item_fontWeight',
    'type'  => 'button_set',
    'title'  => 'Font Weight',
    'subtitle'=> 'Set Item Font Weight',
    'desc'   => 'Choose Font Weight',
    'options'    => array(
      'normal'=> 'Normal',
      'bold'=> 'Bold',
    ),
    'default'    => 'normal',
  ),
  array(
    'id'    => 'item_fontStyle',
    'type'  => 'button_set',
    'title'  => 'Font Style',
    'subtitle'=> 'Set Content Font Style',
    'desc'   => 'Choose Font Style',
    'options'    => array(
      'normal'=> 'Normal',
      'italic'=> 'Italic',
    ),
    'default'    => 'normal',
  ),
  array(
    'id'    => 'item_fontSize',
    'type'  => 'spinner',
    'title'  => 'Font-Size',
    'subtitle'=> 'Set Content Font-Size',
    'desc'   => 'Choose Font Size',
    'unit'   => 'PX',
    'default'=> '14'
  ),
  array(
    'id'    => 'item_border_size',
    'type'  => 'spinner',
    'title'  => 'Item Border',
    'subtitle'=> 'Set Timeline Item Border Size',
    'desc'   => 'Choose Border Size',
    'unit'  => 'PX',
    'default'=> '1',
  ),
  array(
    'id'    => 'item_border_color',
    'type'  => 'color',
    'title'  => 'Item Border Color',
    'subtitle'=> 'Set Timeline Item Border Color',
    'desc'   => 'Choose Border Color',
    'default'=> '#cccccc',
  ),
  array(
    'id'    => 'label_fontSize',
    'type'  => 'spinner',
    'title'  => 'label / Title Font-Size',
    'subtitle'=> 'Set Label Font-Size',
    'desc'   => 'Choose Label Font Size',
    'unit'   => 'PX',
    'default'=> '16'
  ),
  array(
    'id'    => 'lebel_fontWeight',
    'type'  => 'button_set',
    'title'  => 'Font Weight',
    'subtitle'=> 'Set label / Title Font Weight',
    'desc'   => 'Choose Font Weight',
    'options'    => array(
      'normal'=> 'Normal',
      'bold'=> 'Bold',
    ),
    'default'    => 'normal',
  ),
  array(
    'id'    => 'label_fontStyle',
    'type'  => 'button_set',
    'title'  => 'Font Style',
    'subtitle'=> 'Set label / Title Font Style',
    'desc'   => 'Choose Font Style',
    'options'    => array(
      'normal'=> 'Normal',
      'italic'=> 'Italic',
    ),
    'default'    => 'normal',
  ),
  array(
    'id'    => 'label_color',
    'type'  => 'color',
    'title'  => 'label / Title Color',
    'subtitle'=> 'Set Label Font Color',
    'desc'   => 'Choose Label Font Color',
    'default'=> '#222222'
  ),





  )

) );

