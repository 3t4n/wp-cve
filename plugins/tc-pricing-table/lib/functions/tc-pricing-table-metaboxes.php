<?php
add_action( 'cmb2_admin_init', 'tc_pricing_table_metaboxes' );
function tc_pricing_table_metaboxes() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = '_tc_';

    /**
     * Initiate the metabox
     */
    $tc_pricing= new_cmb2_box( array(
        'id'            => 'tc-pricing-table-meta',
        'title'         => __('Pricing Tables', 'tc-pricing-table' ),
        'object_types'  => array('tcpricingtable'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );
 $group_field_id = $tc_pricing->add_field( array(
    'id'          => $prefix .'tablemeta',
    'type'        => 'group',
    'description' => __( 'Generates reusable form entries', 'tc-pricing-table' ),
    'options'     => array(
        'group_title'   => __( 'Table Plan {#}', 'tc-pricing-table' ),
        'add_button'    => __( 'Add New Plan', 'tc-pricing-table' ),
        'remove_button' => __( 'Remove Table', 'tc-pricing-table' ),
        'sortable'      => true, // beta
        // 'closed'     => true, // true to have the groups closed by default
    ),
) );

// Id's for group's fields only need to be unique for the group. Prefix is not needed.
$tc_pricing->add_group_field( $group_field_id,array(
    'name' => 'Plan Header',
    'desc' => '',
    'type' => 'title',
    'id'   => 'plan_header',


) );

$tc_pricing->add_group_field( $group_field_id, array(
  'id'              => 'plan_title',
  'name'            => 'Plan Title',
  'type'            => 'text',
  'attributes' => array('placeholder' => 'Basic'),
  'row_classes' => 'tcpt_left tcpt_smiddle tcpt_level tcpt_input',

));

$tc_pricing->add_group_field( $group_field_id, array(
  'id'              => 'sub_title',
  'name'            => 'Sub Title',
  'attributes' => array('placeholder' => 'Available in the Pro'),
  'type'            => 'text',
  'row_classes' => 'tcpt_right tcpt_smiddle tcpt_level tcpt_input',

));
$tc_pricing->add_group_field( $group_field_id,array(
            'id'              => 'plan_currency',
            'name'            => 'Currency'.'<a class="tc_tooltip" title="'.__( 'Currency Symbols - $,£', 'tc-pricing-table' ).'"><span class="tc-help dashicons dashicons-editor-help"></span></a>',
            'type'            => 'text',
            'default'         => '$',
            'attributes' => array('placeholder' => '$'),
            'row_classes' => 'tcpt_left tcpt_small tcpt_level tcpt_inputs',
 ));

$tc_pricing->add_group_field( $group_field_id,array(
            'id'              => 'package_price',
            'name'            => 'Price',
            'type'            => 'text',
            'default'         => '7',
            'attributes' => array('placeholder' => '13'),
           'attributes' => array(
                  'type' => 'number',
                  'step'=>"any",
              ),

           'row_classes' => 'tcpt_left tcpt_small tcpt_level tcpt_inputs',
 ));

  $tc_pricing->add_group_field( $group_field_id, array(
              'id'              => 'pricing_per',
              'name'            => 'Duration',
              'type'            => 'text',
              'default'         => 'Month',
              'attributes' => array('placeholder' => 'Month'),
              'row_classes' => 'tcpt_left tcpt_small tcpt_level tcpt_inputs',
          ));

    $tc_pricing->add_group_field( $group_field_id,array(
              'name' => 'Plan Features'.' <a class="tc_tooltip" title="'.__( 'Add features and separate by comma (,)', 'tc-pricing-table' ).'"><span class="tc-help dashicons dashicons-editor-help"></span></a>',
              'desc' => 'Add features and separate by comma (,).Please follow the examples',
              'type' => 'textarea',
              'attributes' => array('placeholder' => 'eg. 512MB Memory, 1 Core Processor, 20GB SSD Disk, 1TB Transfere'),
              'id'   => 'tcpt_features',
              'row_classes' => 'tcpt-textarea',
          ) );


    $tc_pricing->add_group_field( $group_field_id,array(
        'name' => 'Button',
        'desc' => 'Action Button',
        'type' => 'title',
        'id'   => 'action_button'
    ) );
          $tc_pricing->add_group_field( $group_field_id,array(
              'id'              => 'action_link',
              'name'            => 'Action Link',
              'type'            => 'text_url',
              'default'         => 'http://www.themescode.com',
              'attributes' => array('placeholder' => 'http://www.themescode.com'),
              'row_classes' => 'tcpt_left tcpt_smiddle tcpt_level tcpt_input',
            ));

            $tc_pricing->add_group_field( $group_field_id,array(
              'id'              => 'action_button',
              'name'            => 'Button Text',
              'type'            => 'text',
              'attributes' => array('placeholder' => 'Sign Up'),
              'default'         => 'Sign Up',
              'row_classes' => 'tcpt_left tcpt_smiddle tcpt_level tcpt_input',
            ));
          $tc_pricing->add_group_field( $group_field_id, array(
         'id'      => 'plan_hbg_color',
         'name'    => 'Header bg'.' <a class="tc_tooltip" title="'.__( 'Header Background Color', 'tc-pricing-table' ).'"><span class="tc-help dashicons dashicons-editor-help"></span></a>',
         'type'    => 'colorpicker',
         'default' => '#3498db',
          'row_classes' => 'tcpt_left tcpt_smiddle tcpt_levelc tcpt_input_color',
          ));

           $tc_pricing->add_group_field( $group_field_id, array(
          'id'      => 'plan_h_color',
          'name'    => 'Header Text'.' <a class="tc_tooltip" title="'.__( 'Header Text Color', 'tc-pricing-table' ).'"><span class="tc-help dashicons dashicons-editor-help"></span></a>',
          'type'    => 'colorpicker',
          'default' => '#ffffff',
           'row_classes' => 'tcpt_left tcpt_smiddle tcpt_level tcpt_input_color',
        ));

        $tc_pricing->add_group_field( $group_field_id, array(
       'id'      => 'plan_bg_color',
       'name'    => 'Body bg'.' <a class="tc_tooltip" title="'.__( 'Body Background Color', 'tc-pricing-table' ).'"><span class="tc-help dashicons dashicons-editor-help"></span></a>',
       'type'    => 'colorpicker',
       'default' => '#fdfdfd',
       'row_classes' => 'tcpt_left tcpt_small tcpt_level tcpt_input_color',
        ));

         $tc_pricing->add_group_field( $group_field_id, array(
        'id'      => 'plan_color',
        'name'    => 'Body Color'.' <a class="tc_tooltip" title="'.__( 'Body Color', 'tc-pricing-table' ).'"><span class="tc-help dashicons dashicons-editor-help"></span></a>',
        'type'    => 'colorpicker',
        'default' => '#000',
         'row_classes' => 'tcpt_left tcpt_small tcpt_level tcpt_input_color',
      ));
      $tc_pricing->add_group_field( $group_field_id, array(
     'id'      => 'plan_button_bg_color',
     'name'    => 'Button bg'.' <a class="tc_tooltip" title="'.__( 'Button Background Color', 'tc-pricing-table' ).'"><span class="tc-help dashicons dashicons-editor-help"></span></a>',
     'type'    => 'colorpicker',
     'default' => '#3498db',
     'row_classes' => 'tcpt_left tcpt_small tcpt_level tcpt_input_color',
      ));

       $tc_pricing->add_group_field( $group_field_id, array(
      'id'      => 'plan_button_color',
      'name'    => 'Button Color'.' <a class="tc_tooltip" title="'.__( 'Button Text Color', 'tc-pricing-table' ).'"><span class="tc-help dashicons dashicons-editor-help"></span></a>',
      'type'    => 'colorpicker',
      'default' => '#FFF',
       'row_classes' => 'tcpt_left tcpt_small tcpt_level tcpt_input_color',
    ));


// PRO version

$tcpt_pro_group = new_cmb2_box( array(
    'id' => $prefix . 'pro_metabox',
    'title' => '<span><strong>PRO Version is Best for You.</strong></span>',
    'object_types' => array( 'tcpricingtable' ),
    'context' => 'side',
    'priority' => 'low',
    'row_classes' => 'de_hundred de_heading',
));

    $tcpt_pro_group->add_field( array(
        'name' => '',
            'desc' => '<div>
             <ul class="pro-features">
<li><span class="dashicons dashicons-arrow-right-alt"></span> 10 different nice looking flat themes.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span> 5 different column in a row. 2,3,4,5,6 columns available in a row. </li>
<li><span class="dashicons dashicons-arrow-right-alt"></span> column box can be Rectangle and round shape.</li>
  <li><span class="dashicons dashicons-arrow-right-alt"></span> Unlimited pricing tables, columns and rows.</li>
    <li><span class="dashicons dashicons-arrow-right-alt"></span></i> Advanced settings for individual table.</li>
       <li><span class="dashicons dashicons-arrow-right-alt"></span></i> Each table style,column,theme will be different.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> Unlimited tables with unlimited rows and Columns can be created.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> Column border normal and hover Color is changeable.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> Border can be hide or show.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> Unlimited package features can be added .put a comma to separate them.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> Column title , features, background normal and hover color is change able.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> Action buttons background and text color is changeable.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> Column title,subtitle ,price,features font size can be managed.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> Features texts can be highlighted with changeable color.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> Mark any column to make it highlited/featured  with ribbon.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> 4 ribbons styles and the background color , text color are changeable.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> Font Awesome icon support before features text.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> Optimized coding standard,lightweight plugin.</li>
  <li><span class="dashicons dashicons-arrow-right-alt"></span></i> Margin/space between columns can be enabled/disabled.</li>
<li><span class="dashicons dashicons-arrow-right-alt"></span></i> And many more....</li>
  </ul>
  <br/><br/>
             <a class="tc-button tc-btn-red"
               target="_blank" href="https://www.themescode.com/items/tc-pricing-table-pro/">Go Pro !</a><br/>
               <span class="cupon-btn">
            <strong></strong> <br> <br> <strong> </strong></span></div>
                ',

            'id'   => $prefix . 'pro_desc',
            'type' => 'title',
            'row_classes' => 'de_hundred de_info de_info_side',
    ));

 $tcpt_pro_group= new_cmb2_box( array(
        'id' => $prefix . 'pro_metabox',
        'title' => '<span><strong>Learn WordPress.</strong></span>',
        'object_types' => array( 'tcpricingtable' ),
        'context' => 'side',
        'priority' => 'low',
        'row_classes' => 'de_hundred de_heading',
    ));

    $tcpt_pro_group->add_field( array(
        'name' => '',
            'desc' => '',

            'id'   => $prefix . 'wpbrim_learn_wp',
            'type' => 'title',
            'row_classes' => 'de_hundred de_info de_info_side',
    ));

    // Video Tutorials

    $tcpt_video_group = new_cmb2_box( array(
        'id' => $prefix . 'tc_video_metabox',
        'title' => '<span style="font-weight:bold;">'.__( 'Video Tutorials', 'team-members' ).'</span>',
        'object_types' => array( 'tcpricingtable' ),
        'context' => 'side',
        'priority' => 'low',
        'row_classes' => 'de_hundred de_heading',
    ));

        $tcpt_video_group->add_field( array(
            'name' => '',
                'desc' => 'Watch wpbrim Online Courses on Youtube and brush up your wordpress skills. Ready ?

                   <p><a class="tc-button tc-btn-orange" href="https://goo.gl/XJ7e4g" target="_blank">Watch Video Tutorials</a></p>',
                'id'   => $prefix . 'tc_video__desc',
                'type' => 'title',
                'row_classes' => 'de_hundred de_info de_info_side',
        ));


}
 ?>
