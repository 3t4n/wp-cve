<?php 
/**
 * @package Free Education Helper
 */

$free_education_theme = wp_get_theme();
if(($free_education_theme->get( 'TextDomain' ) == 'free-education') || ($free_education_theme->get( 'TextDomain' ) == 'education-pro') || ($free_education_theme->get( 'TextDomain' ) == 'free-education-pro')):

/**
 * Free Education Header Settings panel at Theme Customizer
 *
 */

add_action( 'customize_register', 'free_education_helper_header_settings_register' );

function free_education_helper_header_settings_register( $wp_customize ) {
    /**
     * Repeater field for top header items
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 
    	'free_education_top_header_items', 
    	array(
    		'capability'        => 'edit_theme_options',            
    		'default'           => json_encode(array(
    			array(
    				'ed_item_icon' => 'fa fa-phone',
    				'ed_item_text' => '',
    			)
    		)
    	),
    		'sanitize_callback' => 'free_education_sanitize_repeater'
    	)
    );

    $wp_customize->add_control( new Free_Education_Repeater_Controller(
    	$wp_customize, 
    	'free_education_top_header_items', 
    	array(
    		'label'           => __( 'Top Header Items', 'free-education-helper' ),
    		'section'         => 'free_education_top_header_section',
    		'settings'        => 'free_education_top_header_items',
    		'priority'        => 10,
    		'free_education_box_label'       => __( 'Single Item','free-education-helper' ),
    		'free_education_box_add_control' => __( 'Add Item','free-education-helper' )
    	),
    	array(
    		'ed_item_icon' => array(
    			'type'        => 'icon',
    			'label'       => __( 'Item Icon', 'free-education-helper' ),
    			'description' => __( 'Choose icon for single item from available lists.', 'free-education-helper' )
    		),
    		'ed_item_text' => array(
    			'type'        => 'text',
    			'label'       => __( 'Item Info', 'free-education-helper' ),
    			'description' => __( 'Enter short info for single item.', 'free-education-helper' )
    		)
    	)
    ) 
);



    /**
     * Repeater field for Inner header items
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 
    	'free_education_inner_header_items', 
    	array(
    		'capability'        => 'edit_theme_options',            
    		'default'           => json_encode(array(
    			array(
    				'ed_item_icon' => 'fa fa-phone',
    				'ed_item_text' => '',
    				'ed_item_text_1'=>''
    			)
    		)
    	),
    		'sanitize_callback' => 'free_education_sanitize_repeater'
    	)
    );

    $wp_customize->add_control( new Free_Education_Repeater_Controller(
    	$wp_customize, 
    	'free_education_inner_header_items', 
    	array(
    		'label'           => __( 'Inner Header Items', 'free-education-helper' ),
    		'section'         => 'free_education_inner_header_section',
    		'settings'        => 'free_education_inner_header_items',
    		'priority'        => 10,
    		'free_education_box_label'       => __( 'Single Item','free-education-helper' ),
    		'free_education_box_add_control' => __( 'Add Item','free-education-helper' )
    	),
    	array(
    		'ed_item_icon' => array(
    			'type'        => 'icon',
    			'label'       => __( 'Item Icon', 'free-education-helper' ),
    			'description' => __( 'Choose icon for single item from available lists.', 'free-education-helper' )
    		),
    		'ed_item_text' => array(
    			'type'        => 'text',
    			'label'       => __( 'Item title', 'free-education-helper' ),
    			'description' => __( 'Enter title for single item.', 'free-education-helper' )
    		),
    		'ed_item_text_1' => array(
    			'type'        => 'text',
    			'label'       => __( 'Item Info', 'free-education-helper' ),
    			'description' => __( 'Enter short info for single item.', 'free-education-helper' )
    		)
    	)
    ) 
);
}

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Social Icons Section
 *
 * @since 1.0.0
 */
add_action( 'customize_register', 'free_education_helper_social_settings_register' );

function free_education_helper_social_settings_register( $wp_customize ) {


 /**
     * Repeater field for social media icons
     *
     * @since 1.0.0
     */
 $wp_customize->add_setting( 
    'social_media_icons', 
    array(
        'sanitize_callback' => 'free_education_sanitize_repeater',
        'default' => json_encode(array(
            array(
                'ed_item_social_icon' => 'fa fa-facebook-f',
                'ed_item_url'         => '',
            )
        ))
    )
);

 $wp_customize->add_control( new Free_Education_Repeater_Controller(
    $wp_customize, 
    'social_media_icons', 
    array(
        'label'             => __( 'Social Media Icons', 'free-education-helper' ),
        'section'           => 'free_education_social_icons_section',
        'settings'          => 'social_media_icons',
        'priority'          => 60,
        'free_education_box_label'       => __( 'Social Media Icon','free-education-helper' ),
        'free_education_box_add_control' => __( 'Add Icon','free-education-helper' )
    ),
    array(
        'ed_item_social_icon' => array(
            'type'        => 'social_icon',
            'label'       => __( 'Social Media Logo', 'free-education-helper' ),
            'description' => __( 'Choose social media icon.', 'free-education-helper' )
        ),
        'ed_item_url' => array(
            'type'        => 'url',
            'label'       => __( 'Social Icon Url', 'free-education-helper' ),
            'description' => __( 'Enter social media url.', 'free-education-helper' )
        )
    )
) 
);  
}

/**
 * Free Education Frontpage Settings panel at Theme Customizer
 *
 */
add_action( 'customize_register', 'free_education_helper_frontpage_settings_register' );

function free_education_helper_frontpage_settings_register( $wp_customize ) {



    $wp_customize->add_setting( 
        'free_education_slider_items', 
        array(
            'capability'        => 'edit_theme_options',            
            'default'           => json_encode(array(
                array(
                    'ed_dropdown_pages' => 'Select Page',
                    'ed_item_text' => '',
                    'ed_item_url' => '',
                    'ed_item_text_1' =>'',
                    'ed_item_url_1' => ''
                )
            )
        ),
            'sanitize_callback' => 'free_education_sanitize_repeater'
        )
    );
    $wp_customize->add_control( new Free_Education_Repeater_Controller(
        $wp_customize, 
        'free_education_slider_items', 
        array(
            'label'           => __( 'Slider Items', 'free-education-helper' ),
            'section'         => 'free_education_frontpage_slider_section',
            'settings'        => 'free_education_slider_items',
            'priority'        => 60,
            'free_education_box_label'       => __( 'Slider Item','free-education-helper' ),
            'free_education_box_add_control' => __( 'Add Slider','free-education-helper' )
        ),
        array(
            'ed_dropdown_pages' => array(
               'type'        => 'dropdown-pages',
               'label'       => __('Select page for slider title and description with feature images','free-education-helper'), 
           ),
            'ed_item_text' => array(
                'type'        => 'text',
                'label'       => __( 'Button 1 Text', 'free-education-helper' ),
                'description' => __( 'Type Button Text', 'free-education-helper' )
            ),
            'ed_item_url' => array(
                'type'        => 'url',
                'label'       => __( 'Button 1 url', 'free-education-helper' ),
                'description' => __( 'Type Button Url', 'free-education-helper' )
            ),
            'ed_item_text_1' => array(
                'type'        => 'text',
                'label'       => __( 'Button 2 Text', 'free-education-helper' ),
                'description' => __( 'Type Vedio Text', 'free-education-helper' )
            ),
            'ed_item_url_1' => array(
                'type'        => 'url',
                'label'       => __( 'Button 2 url', 'free-education-helper' ),
                'description' => __( 'Use Link from youtube', 'free-education-helper' ),
            ),
        )
    ) 
);





/**
 * Repeater field for Enroll Enroll items
 *
 * @since 1.0.0
 */
$wp_customize->add_setting( 
    'free_education_enroll_skill_items', 
    array(
        'capability'        => 'edit_theme_options',            
        'default'           => json_encode(array(
            array(
                'ed_item_number' => '',
                'ed_item_text' => '',
                'ed_item_text_1' => '',
            )
        )
    ),
        'sanitize_callback' => 'free_education_sanitize_repeater'
    )
);

$wp_customize->add_control( new Free_Education_Repeater_Controller(
    $wp_customize, 
    'free_education_enroll_skill_items', 
    array(
        'label'           => __( 'Enroll items', 'free-education-helper' ),
        'section'         => 'free_education_frontpage_enroll_section',
        'settings'        => 'free_education_enroll_skill_items',
        'priority'        => 110,
        'free_education_box_label'       => __( 'Enroll Item','free-education-helper' ),
        'free_education_box_add_control' => __( 'Add Item','free-education-helper' )
    ),
    array(
        'ed_item_number' => array(
            'type'        => 'number',
            'label'       => __( 'Enroll percentage', 'free-education-helper' ),
            'description' => __( 'Enter Positive integer number(1,2,3,...)', 'free-education-helper' )
        ),
        'ed_item_text' => array(
            'type'        => 'text',
            'label'       => __( 'Enroll Count', 'free-education-helper' ),
            'description' => __( 'Enter Count text Eg:- 28k+', 'free-education-helper' )
        ),
        'ed_item_text_1' => array(
            'type'        => 'text',
            'label'       => __( 'Enroll title', 'free-education-helper' ),
            'description' => __( 'Enter Enroll title Eg:- Students', 'free-education-helper' )
        )
    )
) 
);
$free_education_theme = wp_get_theme();
if(($free_education_theme->get( 'TextDomain' ) == 'free-education')):

/**
* Repeater field for Frontpage Teacher section
*
* @since 1.0.0
*/
$wp_customize->add_setting( 
    'free_education_teacher_items', 
    array(
        'capability'        => 'edit_theme_options',            
        'default'           => json_encode(array(
            array(
                'ed_dropdown_teacher' => 'Select Teacher',
                'ed_item_text' => '',
                'ed_item_social_icon'=>'fa fa-facebook',
                'ed_item_social_icon_1'=>'fa fa-twitter',
                'ed_item_social_icon_2'=>'fa fa-linkedin',
                'ed_item_social_icon_3'=>'fa fa-behance',
                'ed_item_url' => '',
                'ed_item_url_1' => '',
                'ed_item_url_2' => '',
                'ed_item_url_3' => ''
            )
        )
    ),
        'sanitize_callback' => 'free_education_sanitize_repeater'
    )
);
$wp_customize->add_control( new Free_Education_Repeater_Controller(
    $wp_customize, 
    'free_education_teacher_items', 
    array(
        'label'           => __( 'Teacher Items', 'free-education-helper' ),
        'section'         => 'free_education_frontpage_teacher_section',
        'settings'        => 'free_education_teacher_items',
        'priority'        => 60,
        'free_education_box_label'       => __( 'Teacher Item','free-education-helper' ),
        'free_education_box_add_control' => __( 'Add Teacher','free-education-helper' )
    ),
    array(
        'ed_dropdown_teacher' => array(
         'type'        => 'dropdown-user-teacher',
         'label'       => __('Select User for Teacher name with feature images','free-education-helper'), 
     ),
        'ed_item_text' => array(
            'type'        => 'text',
            'label'       => __( 'Designation', 'free-education-helper' ),
            'description' => __( 'Eg:-Founder, Co-Founder, Developer', 'free-education-helper' )
        ),
        'ed_item_social_icon' => array(
            'type'        => 'social_icon',
            'label'       => __( 'Social Icon 1', 'free-education-helper' ),
        ),
        'ed_item_url' =>  array(
            'type'        => 'url',
            'label'       => __( 'Social Icon Url 1', 'free-education-helper' ),
        ),
        'ed_item_social_icon_1' => array(
            'type'        => 'social_icon',
            'label'       => __( 'Social Icon 2', 'free-education-helper' ),
        ),
        'ed_item_url_1' =>  array(
            'type'        => 'url',
            'label'       => __( 'Social Icon Url 2', 'free-education-helper' ),
        ),
        'ed_item_social_icon_2' => array(
            'type'        => 'social_icon',
            'label'       => __( 'Social Icon 3', 'free-education-helper' ),
        ),
        'ed_item_url_2' =>  array(
            'type'        => 'url',
            'label'       => __( 'Social Icon Url 3', 'free-education-helper' ),
        ),
        'ed_item_social_icon_3' => array(
            'type'        => 'social_icon',
            'label'       => __( 'Social Icon 4', 'free-education-helper' ),
        ),
        'ed_item_url_3' =>  array(
            'type'        => 'url',
            'label'       => __( 'Social Icon Url 4', 'free-education-helper' ),
        )
    )
) 
);

endif;
    /**
     * Repeater field for Counter items
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 
        'free_education_frontpage_counter_items', 
        array(
            'capability'        => 'edit_theme_options',            
            'default'           => json_encode(array(
                array(
                    'ed_item_icon' => 'fa fa-institution',
                    'ed_item_text' => '',
                    'ed_item_text_1',''
                )
            )
        ),
            'sanitize_callback' => 'free_education_sanitize_repeater'
        )
    );

    $wp_customize->add_control( new Free_Education_Repeater_Controller(
        $wp_customize, 
        'free_education_frontpage_counter_items', 
        array(
            'label'           => __( 'Inner Header Items', 'free-education-helper' ),
            'section'         => 'free_education_counter_section',
            'settings'        => 'free_education_frontpage_counter_items',
            'priority'        => 10,
            'free_education_box_label'       => __( 'Counter Item','free-education-helper' ),
            'free_education_box_add_control' => __( 'Add Item','free-education-helper' )
        ),
        array(
            'ed_item_icon' => array(
                'type'        => 'icon',
                'label'       => __( 'Counter Icon', 'free-education-helper' ),
                'description' => __( 'Choose icon for Counter from available lists.', 'free-education-helper' )
            ),
            'ed_item_text' => array(
                'type'        => 'text',
                'label'       => __( 'Counter text Number', 'free-education-helper' ),
                'description' => __( 'Enter  Text Number for  Counter.', 'free-education-helper' )
            ),
            'ed_item_text_1' => array(
                'type'        => 'text',
                'label'       => __( 'Counter Title', 'free-education-helper' ),
                'description' => __( 'Enter Title for Counter.', 'free-education-helper' )
            )
        )
    ) 
);
}


add_action( 'customize_register', 'free_education_helper_page_settings_register' );

function free_education_helper_page_settings_register( $wp_customize ) {
/**
 * Repeater field for Contact Address option
 *
 * @since 1.0.0
 */
$wp_customize->add_setting( 
    'free_education_contact_address_items', 
    array(
        'capability'        => 'edit_theme_options',            
        'default'           => json_encode(array(
            array(
                'ed_item_icon' => 'fa fa-map',
                'ed_item_text' => '',
                'ed_item_text_1'=>''
            )
        )
    ),
        'sanitize_callback' => 'free_education_sanitize_repeater'
    )
);

$wp_customize->add_control( new Free_Education_Repeater_Controller(
    $wp_customize, 
    'free_education_contact_address_items', 
    array(
        'label'           => __( 'Contact Address Items', 'free-education-helper' ),
        'section'         => 'free_education_contact_page_section',
        'settings'        => 'free_education_contact_address_items',
        'priority'        => 60,
        'free_education_box_label'       => __( 'Contact Address Item','free-education-helper' ),
        'free_education_box_add_control' => __( 'Add Contact Address','free-education-helper' )
    ),
    array(
        'ed_item_icon' => array(
            'type'        => 'icon',
            'label'       => __( 'Contact Address Icon', 'free-education-helper' ),
            'description' => __( 'Enter Counter Icon', 'free-education-helper' )
        ),  
        'ed_item_text' => array(
            'type'        => 'text',
            'label'       => __( 'Contact Address title', 'free-education-helper' )
        ),
        'ed_item_text_1' => array(
            'type'        => 'text',
            'label'       => __( 'Contact Address info', 'free-education-helper' )
        ),
    )
) 
);
}
endif;