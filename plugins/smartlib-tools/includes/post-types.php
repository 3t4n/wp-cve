<?php

//add_action( 'init', 'smartlib_register_post_types' );


add_action( 'init', 'smartlib_register_post_types' );

add_action( 'init', 'smartlib_portfolio_taxonomy' );

function smartlib_register_post_types()
    {
        register_post_type('smartlib_portfolio',
            array(
                'labels' => array(
                    'name' => __('Portfolio items', 'smartlib'),
                    'singular_name' => __('Portfolio item', 'smartlib')
                ),
                'public' => true,
                'has_archive' => true,
                'supports'      => array( 'title', 'editor', 'thumbnail' ),
                'rewrite' => array('slug' => 'portfolio'),
            )
        );

        register_post_type('smartlib_team',
            array(
                'labels' => array(
                    'name' => __('Team Members', 'smartlib'),
                    'singular_name' => __('Team Member', 'smartlib')
                ),
                'public' => true,
                'supports'      => array( 'title', 'editor', 'thumbnail' ),
                'rewrite' => array('slug' => 'member'),
            )
        );



        register_post_type('smartlib_testimonial',
            array(
                'labels' => array(
                    'name' => __('Testimonials', 'smartlib'),
                    'singular_name' => __('Testimonials item', 'smartlib')
                ),
                'public' => true,
                'supports'      => array( 'title', 'editor', 'thumbnail' ),
            )
        );

        register_post_type('smartlib_faq',
            array(
                'labels' => array(
                    'name' => __('FAQ', 'smartlib'),
                    'singular_name' => __('FAQ item', 'smartlib')
                ),
                'public' => true,
                'has_archive' => false,
                'hierarchical'=> true,
                'rewrite' => array('slug' => 'faq'),

            )
        );
    }




function smartlib_portfolio_taxonomy() {
    register_taxonomy(
        'portfolio_category',
        'smartlib_portfolio',
        array(
            'label' => __( 'Portfolio Category' ),
            'rewrite' => array( 'slug' => 'category' ),
            'hierarchical' => true,
        )
    );

    register_taxonomy(
        'portfolio_skills',
        'smartlib_portfolio',
        array(
            'label' => __( 'Skills' ),
            'rewrite' => array( 'slug' => 'skill' ),
            'hierarchical' => false,
        )
    );
}

