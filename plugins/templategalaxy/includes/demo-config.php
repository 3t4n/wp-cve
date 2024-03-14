<?php
function templategalaxy_demo_importer_get_templates_lists($theme_slug)
{
    switch ($theme_slug):
        case "blogpoet":
            $demo_templates_lists = array(
                'blogpoet' => array(
                    'title' => esc_html__('Blogpoet', 'templategalaxy'),/*Title*/
                    'is_pro' => false,  /*Premium*/
                    'type' => 'free',
                    'author' => esc_html__('WebsiteinWP', 'templategalaxy'),    /*Author Name*/
                    'keywords' => array('Blogpoet', 'templategalaxy'),  /*Search keyword*/
                    'categories' => array('free'), /*Categories*/
                    'template_url' => array(
                        'content' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/blogpoet/free/1/content.json',
                        'options' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/blogpoet/free/1/options.json',
                        'widgets' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/blogpoet/free/1/widgets.json'
                    ),
                    'screenshot_url' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/blogpoet/free/1/screenshot.png',
                    'demo_url' => 'https://demos.websiteinwp.com/blogpoet/',
                    'plugins' => ''
                ),
                'blogpoet-pro' => array(
                    'title' => esc_html__('Blogpoet Pro', 'templategalaxy'),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'type' => 'premium',
                    'author' => esc_html__('WebsiteinWP', 'templategalaxy'),    /*Author Name*/
                    'keywords' => array('Blogpoet pro', 'multipurpose'),  /*Search keyword*/
                    'template_url' => array(
                        'content' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/blogpoet/pro/1/content.json',
                        'options' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/blogpoet/pro/1/options.json',
                        'widgets' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/blogpoet/pro/1/widgets.json'
                    ),
                    'screenshot_url' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/blogpoet/pro/1/screenshot.png',
                    'demo_url' => 'https://demos.websiteinwp.com/blogpoet-pro/',
                    'plugins' => ''
                ),
            );
            break;
        case "mediator":
            $demo_templates_lists = array(
                'mediator' => array(
                    'title' => esc_html__('Mediator', 'templategalaxy'),/*Title*/
                    'is_pro' => false,  /*Premium*/
                    'type' => 'free',
                    'author' => esc_html__('WebsiteinWP', 'templategalaxy'),    /*Author Name*/
                    'keywords' => array('mediator', 'templategalaxy'),  /*Search keyword*/
                    'categories' => array('free'), /*Categories*/
                    'template_url' => array(
                        'content' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/mediator/free/1/content.json',
                        'options' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/mediator/free/1/options.json',
                        'widgets' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/mediator/free/1/widgets.json'
                    ),
                    'screenshot_url' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/mediator/free/1/screenshot.png',
                    'demo_url' => 'https://demos.websiteinwp.com/mediator/',
                    'plugins' => ''
                ),
                'mediator-pro' => array(
                    'title' => esc_html__('Mediator Pro', 'templategalaxy'),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'type' => 'premium',
                    'author' => esc_html__('WebsiteinWP', 'templategalaxy'),    /*Author Name*/
                    'keywords' => array('mediator pro', 'multipurpose'),  /*Search keyword*/
                    'template_url' => array(
                        'content' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/mediator/pro/1/content.json',
                        'options' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/mediator/pro/1/options.json',
                        'widgets' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/mediator/pro/1/widgets.json'
                    ),
                    'screenshot_url' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/mediator/pro/1/screenshot.png',
                    'demo_url' => 'https://demos.websiteinwp.com/mediator-pro/',
                    'plugins' => ''
                ),
            );
            break;
        case "gazettepress":
            $demo_templates_lists = array(
                'gazettepress' => array(
                    'title' => esc_html__('GazettePress', 'templategalaxy'),/*Title*/
                    'is_pro' => false,  /*Premium*/
                    'type' => 'free',
                    'author' => esc_html__('WebsiteinWP', 'templategalaxy'),    /*Author Name*/
                    'keywords' => array('gazettepress', 'templategalaxy'),  /*Search keyword*/
                    'categories' => array('free'), /*Categories*/
                    'template_url' => array(
                        'content' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/free/1/content.json',
                        'options' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/free/1/options.json',
                        'widgets' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/free/1/widgets.json'
                    ),
                    'screenshot_url' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/free/1/screenshot.png',
                    'demo_url' => 'https://demos.websiteinwp.com/gazettepress/',
                    'plugins' => ''
                ),
                'gazettepress-pro' => array(
                    'title' => esc_html__('GazettePress Pro', 'templategalaxy'),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'type' => 'premium',
                    'author' => esc_html__('WebsiteinWP', 'templategalaxy'),    /*Author Name*/
                    'keywords' => array('gazettepress pro', 'multipurpose'),  /*Search keyword*/
                    'template_url' => array(
                        'content' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/1/content.json',
                        'options' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/1/options.json',
                        'widgets' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/1/widgets.json'
                    ),
                    'screenshot_url' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/1/screenshot.png',
                    'demo_url' => 'https://demos.websiteinwp.com/gazettepress-pro/',
                    'plugins' => ''
                ),
                'gazettepress-pro-2' => array(
                    'title' => esc_html__('GazettePress Pro 2', 'templategalaxy'),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'type' => 'premium',
                    'author' => esc_html__('WebsiteinWP', 'templategalaxy'),    /*Author Name*/
                    'keywords' => array('gazettepress pro', 'multipurpose'),  /*Search keyword*/
                    'template_url' => array(
                        'content' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/2/content.json',
                        'options' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/2/options.json',
                        'widgets' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/2/widgets.json'
                    ),
                    'screenshot_url' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/2/screenshot.png',
                    'demo_url' => 'https://demos.websiteinwp.com/gazettepress-pro-2/',
                    'plugins' => ''
                ),
                'gazettepress-pro-3' => array(
                    'title' => esc_html__('GazettePress Pro 3', 'templategalaxy'),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'type' => 'premium',
                    'author' => esc_html__('WebsiteinWP', 'templategalaxy'),    /*Author Name*/
                    'keywords' => array('gazettepress pro', 'multipurpose'),  /*Search keyword*/
                    'template_url' => array(
                        'content' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/3/content.json',
                        'options' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/3/options.json',
                        'widgets' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/1/widgets.json'
                    ),
                    'screenshot_url' => TEMPLATEGALAXY_IMPORTER_SETUP_TEMPLATE_URL . '/gazettepress/pro/3/screenshot.png',
                    'demo_url' => 'https://demos.websiteinwp.com/gazettepress-pro-3/',
                    'plugins' => ''
                )
            );
            break;
        default:
            $demo_templates_lists = array();
    endswitch;

    return $demo_templates_lists;
}
