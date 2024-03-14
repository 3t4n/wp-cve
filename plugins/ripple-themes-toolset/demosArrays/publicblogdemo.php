<?php
$demoo=array(
    'free' =>array(
        'title' => __( 'Free ', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => false,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'free' ),/*Search keyword*/
        'categories' => array( 'free' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/free/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/free/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/free/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/free/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/public-blog/',
        'plugins' => array(
            array(
                'name'      => __( 'Public Blog Free', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    'demo-1' =>array(
        'title' => __( 'Demo 1 ', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'pro' ),/*Search keyword*/
        'categories' => array( 'pro' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-1/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-1/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-1/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-1/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/public-blog-pro/',
        'pro_url' => 'https://ripplethemes.com/downloads/public-blog-pro/',

        'plugins' => array(
            array(
                'name'      => __( 'Public Blog Pro', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    'demo-2' =>array(
        'title' => __( 'Demo 2 ', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'demo-2' ),/*Search keyword*/
        'categories' => array( 'pro' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-2/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-2/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-2/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-2/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/public-blog-pro-ad/',
        'plugins' => array(
            array(
                'name'      => __( 'Public Blog Demo-2', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        ),
        'pro_url' => 'https://ripplethemes.com/downloads/public-blog-pro/',

    ),
    'demo-3' =>array(
        'title' => __( 'Demo 3 ', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'demo-3' ),/*Search keyword*/
        'categories' => array( 'pro' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-3/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-3/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-3/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/demo-3/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/public-blog-pro-story/',
        'plugins' => array(
            array(
                'name'      => __( 'Public Blog', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        ),
        'pro_url' => 'https://ripplethemes.com/downloads/public-blog-pro/',

    ),
    'masonary' =>array(
        'title' => __( 'Masonary ', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'masonary' ),/*Search keyword*/
        'categories' => array( 'pro' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/masonary/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/masonary/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/masonary/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/masonary/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/public-pro-masonry/',
        'plugins' => array(
            array(
                'name'      => __( 'Public Blog masonary', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        ),
        'pro_url' => 'https://ripplethemes.com/downloads/public-blog-pro/',

    ),
    'sidebar' =>array(
        'title' => __( 'Sidebar ', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'sidebar' ),/*Search keyword*/
        'categories' => array( 'pro' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/sidebar/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/sidebar/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/sidebar/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/sidebar/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/public-blog-pro-sidebar/',
        'plugins' => array(
            array(
                'name'      => __( 'Public Blog sidebar', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        ),
        'pro_url' => 'https://ripplethemes.com/downloads/public-blog-pro/',

    ),
    'no-slider' =>array(
        'title' => __( 'Trending/ no-slider ', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'no-slider' ),/*Search keyword*/
        'categories' => array( 'pro' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/no-slider/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/no-slider/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/no-slider/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$dir_name.'/no-slider/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/public-blog-pro-noslider/',
        'plugins' => array(
            array(
                'name'      => __( 'Public Blog no-slider', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        ),
        'pro_url' => 'https://ripplethemes.com/downloads/public-blog-pro/',

    ),
    



);