<?php

use Baqend\SDK\Model\AssetFilter;

$url_example  = __( 'Make sure that you use absolute URLs starting with your domain, like %s/wp-includes/js/wp-emoji-release.min.js', 'baqend' );
$comment_out  = __( 'You can comment out single lines by using a semicolon (“;”)', 'baqend' );
$advanced     = __( '(Advanced, handle with care)', 'baqend' );
$logout_first = sprintf( __( '<strong>Please note:</strong> Speed Kit is deactivated as long as you are logged in. <a href="%s">Please log out from WordPress first.</a>' ), wp_logout_url() );

$speed_kit_list_example = sprintf( $url_example, substr( home_url( '', 'https' ), 8 ) );

$speed_kit_config_link     = 'https://www.baqend.com/speed-kit/latest/#SpeedKitConfig';
$dynamic_block_config_link = 'https://www.baqend.com/speed-kit/latest/#DynamicBlockConfig';
$regexp_link               = 'https://developer.mozilla.org/docs/Web/JavaScript/Guide/Regular_Expressions';

return [
    [
        'label' => __( 'Additional URLs to process', 'baqend' ),
        'slug'  => 'additional_urls',
        'type'  => 'textarea',
        'help'  => __( 'Here you can add additional URLs separated by new lines which will also be checked when collecting your blog contents.', 'baqend' ) . '<br/>' . sprintf( $url_example, home_url() ),
    ],
    [
        'label' => __( 'Additional files to process', 'baqend' ),
        'slug'  => 'additional_files',
        'type'  => 'text',
    ],
    [
        'label' => __( 'URLs which should be excluded', 'baqend' ),
        'slug'  => 'urls_to_exclude',
        'type'  => 'list',
        'help'  => __( 'When these URLs occur during the content collecting, they will not be uploaded to Baqend.', 'baqend' ) . '<br/>' . sprintf( $url_example, home_url() ),
    ],
    [
        'label'   => __( 'URL type to use on Baqend', 'baqend' ),
        'slug'    => 'destination_url_type',
        'type'    => 'choice',
        'options' => [
            'relative' => __( 'Relative URLs', 'baqend' ),
            'absolute' => __( 'Absolute URLs', 'baqend' ),
        ],
    ],
    [
        'label'   => __( 'Destination scheme', 'baqend' ),
        'slug'    => 'destination_scheme',
        'type'    => 'choice',
        'options' => [
            'https' => __( 'Secured (https://, recommended)', 'baqend' ),
            'http'  => __( 'Unsecured (http://)', 'baqend' ),
        ],
    ],
    [
        'label' => __( 'Destination host', 'baqend' ),
        'slug'  => 'destination_host',
        'type'  => 'text',
    ],

    [
        'label' => __( 'Working directory', 'baqend' ),
        'slug'  => 'temp_files_dir',
        'type'  => 'text',
        'help'  => __( 'This is the working directory in which all files are collected temporarily.', 'baqend' ),
    ],

    // Speed Kit settings
    [
        'label'          => __( 'Speed Kit Integration', 'baqend' ),
        'slug'           => 'speed_kit_enabled',
        'type'           => 'checkbox',
        'checkbox_label' => __( 'enable', 'baqend' ),
        'help'           => __( 'When checked, the <a href="https://www.baqend.com/speedkit.html" target="_blank">Baqend Speed Kit</a> will be automatically integrated into your WordPress website.', 'baqend' ) . '<br>' . $logout_first,
    ],
    [
        'label' => __( 'Enabled pages', 'baqend' ),
        'slug'  => 'enabled_pages',
        'type'  => 'pages',
        'help'  => __( 'Only the pages contained in this list will be handled by Speed Kit. If the list is empty, Speed Kit handles all pages.', 'baqend' ),
    ],
    [
        'label' => __( 'Enabled paths', 'baqend' ),
        'slug'  => 'enabled_paths',
        'type'  => 'list',
        'help'  => $advanced . '<br>' . __( 'Only pages with the prefixes in the list will be handled by Speed Kit.', 'baqend' ) . '<br/>' . $comment_out,
    ],
    [
        'label' => __( 'Whitelist URLs', 'baqend' ),
        'slug'  => 'speed_kit_whitelist',
        'type'  => 'list',
        'help'  => __( 'Only the websites given in this list will be handled by Speed Kit.', 'baqend' ) . '<br/>' . $speed_kit_list_example . '<br/>' . $comment_out,
    ],
    [
        'label' => __( 'Blacklist URLs', 'baqend' ),
        'slug'  => 'speed_kit_blacklist',
        'type'  => 'list',
        'help'  => __( 'All websites given in this list will be ignored by Speed Kit.', 'baqend' ) . '<br/>' . $speed_kit_list_example . '<br/>' . $comment_out,
    ],
    [
        'label' => __( 'Image optimization', 'baqend' ),
        'slug'  => 'image_optimization',
        'type'  => 'image_optimization',
    ],
    [
        'label' => __( 'Bypass cache on cookie', 'baqend' ),
        'slug'  => 'speed_kit_cookies',
        'type'  => 'list',
        'help'  => __( 'If a page contains one of the cookies given in this list, Speed Kit will ignore the given page. The cookies are given as prefixes.', 'baqend' ) . '<br/>' . $comment_out,
    ],
    [
        'label'    => __( 'Allowed content types', 'baqend' ),
        'slug'     => 'speed_kit_content_type',
        'type'     => 'checkboxes',
        'multiple' => true,
        'help'     => __( 'Only the given content types will be handled by Speed Kit.', 'baqend' ),
        'options'  => [
            AssetFilter::DOCUMENT => __( 'HTML documents <em>(recommended)</em>', 'baqend' ),
            AssetFilter::IMAGE    => __( 'Images <em>(recommended)</em>', 'baqend' ),
            AssetFilter::SCRIPT   => __( 'JavaScript files <em>(recommended)</em>', 'baqend' ),
            AssetFilter::STYLE    => __( 'Style sheets <em>(recommended)</em>', 'baqend' ),
            AssetFilter::FONT     => __( 'Fonts <em>(recommended)</em>', 'baqend' ),
            AssetFilter::TRACK    => __( 'Subtitle tracks <em>(recommended)</em>', 'baqend' ),
            AssetFilter::FEED     => __( 'News feeds', 'baqend' ),
            AssetFilter::AUDIO    => __( 'Audio files', 'baqend' ),
            AssetFilter::VIDEO    => __( 'Videos', 'baqend' ),
        ],
    ],
    [
        'label'   => __( 'Fetch origin interval', 'baqend' ),
        'slug'    => 'fetch_origin_interval',
        'type'    => 'choice',
        'help'    => __( 'An interval in which Speed Kit will call the original document, e.g., to receive its cookies.', 'baqend' ),
        'options' => [
            - 2  => __( 'automatically retrieve interval', 'baqend' ) . ' ' . __( '(recommended)', 'baqend' ),
            - 1  => __( 'never fetch from origin', 'baqend' ),
            0    => __( 'always fetch from origin', 'baqend' ),
            10   => __( '10 seconds', 'baqend' ),
            30   => __( 'half a minute', 'baqend' ),
            60   => __( 'one minute', 'baqend' ),
            300  => __( '5 minutes', 'baqend' ),
            600  => __( '10 minutes', 'baqend' ),
            1200 => __( '20 minutes', 'baqend' ),
            1800 => __( 'half an hour', 'baqend' ),
            3600 => __( 'one hour', 'baqend' ),
        ],
    ],
    [
        'label'   => __( 'Automatic update interval', 'baqend' ),
        'slug'    => 'speed_kit_update_interval',
        'type'    => 'choice',
        'help'    => __( 'Automatically triggers a revalidation of Speed Kit. Use this setting to react on changes over time that you are aware of – e.g. recommendation calculations.', 'baqend' ),
        'options' => [
            'off'        => __( 'off', 'baqend' ),
            'hourly'     => __( 'hourly', 'baqend' ),
            'twicedaily' => __( 'twice daily', 'baqend' ),
            'daily'      => __( 'daily', 'baqend' ),
        ],
    ],
    [
        'label' => __( 'Custom config', 'baqend' ),
        'slug'  => 'custom_config',
        'type'  => 'textarea',
        'help'  => sprintf(
            __( '(Advanced, handle with care) Provide a JSON with custom <a href="%s" target="_blank">Speed Kit Configuration</a> to apply on your WordPress blog. You can use <a href="%s" target="_blank">JavaScript regular expressions</a> in your JSON. Please note that any property you provide overrides previous config.', 'baqend' ),
            $speed_kit_config_link,
            $regexp_link
        ),
    ],
    [
        'label' => __( 'Use Metrics Snippet', 'baqend' ),
        'slug'  => 'metrics_enabled',
        'type'  => 'checkbox',
        'checkbox_label' => __( 'enable', 'baqend' ),
        'help'           => __( 'Please <a href="mailto:support@baqend.com">contact Baqend</a> to learn more about this metrics feature.', 'baqend' ) . '<br>',
    ],
    [
        'label' => __( 'Install Resource URL', 'baqend' ),
        'help'  => __( '(Advanced, handle with care) The URL of the install resource used to install and configure Speed Kit.', 'baqend' ),
        'slug'  => 'install_resource_url',
        'type'  => 'text',
    ],
    [
        'label' => __( 'Dynamic block config', 'baqend' ),
        'slug'  => 'dynamic_block_config',
        'type'  => 'textarea',
        'help'  => sprintf(
            __( '(Advanced, handle with care) A <a href="%s" target="_blank">dynamic block config</a> to apply on your WordPress blog.', 'baqend' ),
            $dynamic_block_config_link
        ),
    ],
    [
        'label' => __( 'Strip Query Params', 'baqend' ),
        'slug'  => 'strip_query_params',
        'type'  => 'list',
        'help'  => __( '(Advanced, handle with care) A set of rules to match against query params which should be stripped from requested URLs before they are fetched.', 'baqend' ),
    ],
    [
        'label'          => __( 'UserAgent detection', 'baqend' ),
        'slug'           => 'user_agent_detection',
        'type'           => 'checkbox',
        'checkbox_label' => __( 'enable', 'baqend' ),
        'help'           => __( 'If user agent detection is enabled, Speed Kit distinguishes all requests between mobile and desktop. Only activate this feature if you serve different assets for mobile and desktop users because it reduces your cache hit ratio.', 'baqend' ),
    ],
    [
        'label'   => __( 'Max Staleness', 'baqend' ),
        'slug'    => 'speed_kit_max_staleness',
        'type'    => 'choice',
        'help'    => __( '(Advanced, handle with care) The time interval in which the Bloom filter may be stale.', 'baqend' ),
        'options' => [
            1 * 15000  => __( 'quarter a minute', 'baqend' ),
            1 * 30000  => __( 'half a minute', 'baqend' ),
            1 * 60000  => __( 'one minute', 'baqend' ) . ' ' . __( '(recommended)', 'baqend' ),
            2 * 60000  => __( '2 minutes', 'baqend' ),
            5 * 60000  => __( '5 minutes', 'baqend' ),
            10 * 60000 => __( '10 minutes', 'baqend' ),
            30 * 60000 => __( 'half an hour', 'baqend' ),
            60 * 60000 => __( 'one hour', 'baqend' ),
        ],
    ],
    [
        'label' => __( 'App Domain', 'baqend' ),
        'help'  => __( '(Advanced, handle with care) The domain of the Baqend Speed Kit API.', 'baqend' ),
        'slug'  => 'speed_kit_app_domain',
        'type'  => 'text',
    ],
];
