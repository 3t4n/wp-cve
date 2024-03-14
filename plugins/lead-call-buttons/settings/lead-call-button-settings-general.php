<?php

global $LCB_settings;
 
$LCB_settings[] = array( 
    'section_id'          => 'general',
    'section_title'       => '',
    'section_description' => 'Settings for Lead Call Buttons. Leave Link field blank to hide button.',
    'section_order'       => 5,
    'fields'              => array(
        array(
            'id'          => 'callnow-title',
            'title'       => 'Button Title',
            'desc'        => 'i.e., Call Now',
            'type'        => 'text',
            'std'         => ''
        ),      
        array(
            'id'          => 'callnow-icon',
            'title'       => 'Icon',
            'desc'        => 'Font Awesome Icon. i.e., &lt;i class="fa fa-phone"&gt;&lt;/i&gt;',
            'type'        => 'text',
            'std'         => ''
        ),      
        array(
            'id'          => 'callnow-number',
            'title'       => 'Link',
            'desc'        => 'i.e., tel: 123-456-7890, or http://absolute.com/link/, or /relative-link/',
            'type'        => 'text',
            'std'         => ''
        ),  
        array(
            'id'          => 'callnow-onclick',
            'class'       => 'section-end',
            'title'       => 'Onclick Code',
            'desc'        => 'i.e., goog_report_conversion (\'http://example.com/your-link\'), ga(‘send’, ‘event’, ‘Mobile Contact’, ‘Phone’, ‘Phone button clicked’);',
            'type'        => 'textarea',
            'std'         => ''
        ),      
        array(
            'id'          => 'schedule-title',
            'title'       => 'Button Title',
            'desc'        => '',
            'type'        => 'text',
            'std'         => ''
        ),      
        array(
            'id'          => 'schedule-icon',
            'title'       => 'Icon',
            'desc'        => '',
            'type'        => 'text',
            'std'         => ''
        ),      
        array(
            'id'          => 'schedule-link',
            'title'       => 'Link',
            'desc'        => '',
            'type'        => 'text',
            'std'         => ''
        ),  
        array(
            'id'          => 'schedule-onclick',
            'class'       => 'section-end',
            'title'       => 'Onclick Code',
            'desc'        => '',
            'type'        => 'textarea',
            'std'         => ''
        ),      
        array(
            'id'          => 'map-title',
            'title'       => 'Button Title',
            'desc'        => '',
            'type'        => 'text',
            'std'         => ''
        ),      
        array(
            'id'          => 'map-icon',
            'title'       => 'Icon',
            'desc'        => '',
            'type'        => 'text',
            'std'         => ''
        ),      
        array(
            'id'          => 'map-link',
            'title'       => 'Link',
            'desc'        => '',
            'type'        => 'text',
            'std'         => ''
        ),
        array(
            'id'          => 'map-onclick',
            'class'       => 'section-end',
            'title'       => 'Onclick Code',
            'desc'        => '',
            'type'        => 'textarea',
            'std'         => ''
        ),      
        array(
            'id'          => 'bg-color',
            'title'       => 'Button Background Color',
            'choices'     => array('Solid Color', 'Gradient'),
            'type'        => 'radio',
            'std'         => ''
        ),
        array(
            'id'          => 'bg-sl-color',
            'title'       => '',
            'desc'        => '',
            'type'        => 'color',
            'std'         => '',
            'placeholder' => 'Select Color'
        ),
        array(
            'id'          => 'bg-gd-color1',
            'title'       => '',
            'desc'        => '',
            'type'        => 'color',
            'std'         => '',
            'placeholder' => 'Select Color'
        ),
        array(
            'id'          => 'bg-gd-color2',
            'title'       => '',
            'desc'        => '',
            'type'        => 'color',
            'std'         => '',
            'placeholder' => 'Select Color'
        ),
        array(
            'id'          => 'text-color',
            'class'       => '',
            'title'       => 'Button Text Color',
            'desc'        => '',
            'type'        => 'color',
            'std'         => ''
        ),
        array(
            'id'          => 'btn-animation',
            'class'       => 'section-end',
            'title'       => 'Button Animation',
            'desc'        => '',
            'type'        => 'checkbox',
            'std'         => ''
        ),
        array(
            'id'          => 'custom-css',
            'title'       => 'Custom CSS',
            'desc'        => 'Add custom CSS here, or leave empty. Use !important if needed.',
            'type'        => 'textarea',
            'std'         => ''
        )
    )
);


?>