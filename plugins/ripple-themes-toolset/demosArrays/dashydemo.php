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
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/free/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/free/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/free/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/free/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/dashy/',
        'plugins' => array(
            array(
                'name'      => __( 'Dashy Free', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),

    'default' =>array(
        'title' => __( 'Default ', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'blog' ),/*Search keyword*/
        'categories' => array( 'blog' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/default/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/default/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/default/widgets.json'
        ),
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',

        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/default/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-pro/',
        'plugins' => array(
            array(
                'name'      => __( 'Default', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    'dark' =>array(
        'title' => __( 'Dashy Dark ', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'blog' ),/*Search keyword*/
        'categories' => array( 'blog' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/dark/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/dark/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/dark/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/dark/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-pro-dark/',
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',
        'plugins' => array(
            array(
                'name'      => __( 'Dashy Dark', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    'demo' =>array(
        'title' => __( 'Portfolio ', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'blog' ),/*Search keyword*/
        'categories' => array( 'blog' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/portfolio/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/portfolio/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/portfolio/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/portfolio/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-pro-portfolio/',
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',
        'plugins' => array(
            array(
                'name'      => __( 'Dashy Portfolio', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
   

    'grid-view' =>array(
        'title' => __( 'Grid View ', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'blog' ),/*Search keyword*/
        'categories' => array( 'blog' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/grid-view/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/grid-view/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/grid-view/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/grid-view/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-pro-gridview/',
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',
        'plugins' => array(
            array(
                'name'      => __( 'Grid View', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    'education' =>array(
        'title' => __( 'Education', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'education' ),/*Search keyword*/
        'categories' => array( 'education' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/education/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/education/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/education/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/education/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-pro-education/',
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',
        'plugins' => array(
            array(
                'name'      => __( 'Grid View', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    'masonry' =>array(
        'title' => __( 'Masonry View', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'blog' ),/*Search keyword*/
        'categories' => array( 'blog' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-pro-masonry/',
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',
        'plugins' => array(
            array(
                'name'      => __( 'Grid View', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    'news' =>array(
        'title' => __( 'News', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'news' ),/*Search keyword*/
        'categories' => array( 'news' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/news/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/news/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/news/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/news/screenshot.jpg',
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-pro-news/',
        'plugins' => array(
            array(
                'name'      => __( 'Grid View', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),

    'news' =>array(
        'title' => __( 'News', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'news' ),/*Search keyword*/
        'categories' => array( 'news' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/news/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/news/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/news/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/news/screenshot.jpg',
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-pro-news/',
        'plugins' => array(
            array(
                'name'      => __( 'News', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    'three-columns' =>array(
        'title' => __( 'Three Columns', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'blog' ),/*Search keyword*/
        'categories' => array( 'blog' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/three-columns/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/three-columns/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/three-columns/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/three-columns/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-pro-three-columns/',
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',
        'plugins' => array(
            array(
                'name'      => __( 'Three Columns', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    'dark-slider' =>array(
        'title' => __( 'Dark Slider', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'blog' ),/*Search keyword*/
        'categories' => array( 'blog' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/dark-slider/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/dark-slider/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/dark-slider/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/dark-slider/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-dark-slider/',
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',
        'plugins' => array(
            array(
                'name'      => __( 'Dark Slider', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    'without-boxshadow' =>array(
        'title' => __( 'Without Box Shadow', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'blog' ),/*Search keyword*/
        'categories' => array( 'blog' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/without-boxshadow/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/without-boxshadow/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/without-boxshadow/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/without-boxshadow/screenshot.jpg',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-pro-without-boxshadow/',
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',
        'plugins' => array(
            array(
                'name'      => __( 'Without Box Shadow', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    'rtl' =>array(
        'title' => __( 'RTL', 'ripplethemes-toolset' ),/*Title*/
        'is_pro' => true,/*Is Premium*/
        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
        'keywords' => array( 'blog' ),/*Search keyword*/
        'categories' => array( 'blog' ),/*Categories*/
        'template_url' => array(
            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl/content.json',
            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl/options.json',
            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl/widgets.json'
        ),
        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl/screenshot.jpg',
        'pro_url' => 'https://ripplethemes.com/downloads/dashy-pro/',
        'demo_url' => 'https://demo.ripplethemes.com/dashy-pro-rtl/',
        'plugins' => array(
            array(
                'name'      => __( 'Without Box Shadow', 'ripplethemes-toolset' ),
                'slug'      => 'gutentor',
            ),
        )
    ),
    



);