<?php

$settings_structure = array(
    array(
        'id' => 'documentation',
        'name' => __( 'Documentation', 'custom-related-posts' ),
        'description' => __( 'With Custom Related Posts you have full control. Manually set the related posts when editing one and display these related posts using the shortcode, block or widget.', 'custom-related-posts' ),
        'documentation' => 'https://help.bootstrapped.ventures/category/159-getting-started',
        'icon' => 'support',
    ),
    array(
        'id' => 'general',
        'name' => __( 'General', 'custom-related-posts' ),
        'icon' => 'cog',
        'settings' => array(
            array(
                'id' => 'general_post_types',
                'name' => __( 'Post Types', 'custom-related-posts' ),
                'description' => __( 'Which post types do you want to enable the Related Posts for?', 'custom-related-posts' ),
                'type' => 'dropdownMultiselect',
                'optionsCallback' => function() { return get_post_types( '', 'names' ); },
                'default' => array( 'post', 'page' ),
            ),
            array(
                'id' => 'cache_relations',
                'name' => __( 'Cache Relations', 'custom-related-posts' ),
                'description' => __( 'In normal cases you want this enabled for speed improvements.', 'custom-related-posts' ),
                'type' => 'toggle',
                'default' => true,
            ),
        ),
    ),
    array(
        'id' => 'search',
        'name' => __( 'Search', 'custom-related-posts' ),
        'icon' => 'search',
        'description' => __( 'Settings to finetune the search for related posts in the backend.', 'custom-related-posts' ),
        'settings' => array(
            array(
                'id' => 'search_number_of_posts',
                'name' => __( 'Number of Posts', 'custom-related-posts' ),
                'type' => 'number',
                'default' => 15,
            ),
            array(
                'id' => 'search_post_status',
                'name' => __( 'Post Status', 'custom-related-posts' ),
                'type' => 'dropdown',
                'options' => array(
                    'any' => __('Any', 'custom-related-posts'),
                    'publish' => __('Published only', 'custom-related-posts'),
                ),
                'default' => 'any',
            ),
        ),
    ),
    array(
        'id' => 'relations',
        'name' => __( 'Relations', 'custom-related-posts' ),
        'icon' => 'link',
        'settings' => array(
            array(
                'id' => 'output_open_in_new_tab',
                'name' => __( 'Open in New Tab', 'custom-related-posts' ),
                'description' => __( 'Open links to related posts in a new tab.', 'custom-related-posts' ),
                'type' => 'toggle',
                'default' => false,
            ),
        ),
    ),
    array(
        'id' => 'template',
        'name' => __( 'Template', 'custom-related-posts' ),
        'icon' => 'brush',
        'subGroups' => array(
            array(
                'name' => __( 'Style', 'custom-related-posts' ),
                'settings' => array(
                    array(
                        'id' => 'template_container',
                        'name' => __( 'Container', 'custom-related-posts' ),
                        'type' => 'dropdown',
                        'options' => array(
                            'div' => __( 'Normal', 'custom-related-posts' ),
                            'ul' => __( 'Unordered List', 'custom-related-posts' ),
                            'ol' => __( 'Ordered List', 'custom-related-posts' ),
                        ),
                        'default' => 'ul',
                    ),
                    array(
                        'id' => 'template_image',
                        'name' => __( 'Image', 'custom-related-posts' ),
                        'type' => 'dropdown',
                        'options' => array(
                            'none' => __( "Don't show", 'custom-related-posts' ),
                            'above' => __( 'Above Text', 'custom-related-posts' ),
                            'below' => __( 'Below Text', 'custom-related-posts' ),
                            'left' => __( 'Floated Left', 'custom-related-posts' ),
                            'right' => __( 'Floated Right', 'custom-related-posts' ),
                        ),
                        'default' => 'none',
                    ),
                    array(
                        'id' => 'template_image_size',
                        'name' => __( 'Image Size', 'custom-related-posts' ),
                        'description' => __( 'Enter a thumbnail name or specific size.', 'custom-related-posts' ),
                        'documentation' => 'https://help.bootstrapped.ventures/article/163-changing-the-look-of-the-relations',
                        'type' => 'text',
                        'default' => '50x50',
                        'dependency' => array(
                            'id' => 'template_image',
                            'value' => 'none',
                            'type' => 'inverse',
                        ),
                    ),
                    array(
                        'id' => 'template_image_nopin',
                        'name' => __( 'Disable pinning of images', 'custom-related-posts' ),
                        'description' => __( 'Add the nopin attribute to the related posts images.', 'custom-related-posts' ),
                        'type' => 'toggle',
                        'default' => true,
                        'dependency' => array(
                            'id' => 'template_image',
                            'value' => 'none',
                            'type' => 'inverse',
                        ),
                    ),
                ),
            ),
            array(
                'name' => __( 'Advanced', 'custom-related-posts' ),
                'settings' => array(
                    array(
                        'id' => 'custom_code_public_css',
                        'name' => __( 'CSS', 'custom-related-posts' ),
                        'description' => __( 'This custom styling will be output on your website.', 'custom-related-posts' ),
                        'type' => 'code',
                        'code' => 'css',
                        'default' => '',
                    ),
                ),
            ),
        ),  
    ),
    array(
        'id' => 'import',
        'name' => __( 'Import Relations', 'custom-related-posts' ),
        'icon' => 'import',
        'settings' => array(
            array(
                'name' => __( 'Import from XML', 'custom-related-posts' ),
                'documentation' => 'https://help.bootstrapped.ventures/article/161-importing-relations-from-xml',
                'type' => 'button',
                'button' => __( 'Import XML', 'custom-related-posts' ),
                'link' => admin_url( 'options-general.php?page=crp_import_xml' ),
            ),
        ),
    ),
);