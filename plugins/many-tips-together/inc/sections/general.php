<?php
/**
 * Section General Settings config
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;

\Redux::set_section(
	$adtw_option,
	array(
		'title' => esc_html__( 'General Settings', 'mtt' ),
        'id'    => 'general',
        'icon' => 'el el-adjust-alt',
        'fields' => [
            
            array( ####### GENERAL
				'id'       => 'general-2',
				'type'     => 'section',
				'title'    => false, //__( 'FRONTEND', 'mtt' ),
				'indent'   => false, 
			),
            array( # Link Manager
                'id'       => 'admin_menus_enable_link_manager',
                'type'     => 'switch',
                'title'    => esc_html__('Enable Link Manager', 'mtt'),
                'desc' => esc_html__( 'The link manager was disabled by default in WordPress 3.5, this will bring it back.', 'mtt' ) 
                    . '&nbsp;'
                    . ADTW()->makeTipCredit( 
                        esc_html__('Read_about()', 'mtt'), 
                        'https://www.wpbeginner.com/news/blogrolls-to-be-removed-in-wordpress-3-5-here-is-how-to-keep-them/' 
                    ),
                'default'  => false,
                'hint'     => array(
					'title'   => '',
					'content' => ADTW()->renderHintImg('general-link-manager.jpg'),
				),
            ),
            array( # Email verification
                'id'       => 'email_verification_disable',
                'type'     => 'switch',
                'title'    => esc_html__( 'Disable the admin email verification', 'mtt' ),
                'default'  => false,
                'hint'     => array(
					'title'   => '',
					'content' => ADTW()->renderHintImg('general-email-verify.png'),
				),
            ),
            array( # Settings Notices enable
                'id'       => 'wpseo_blog_public_enable',
                'type'     => 'switch',
                'title'    => esc_html__( 'Warn about Blocked Blog', 'mtt' ),
                'desc'     => esc_html__( 'Add admin notice alerting that Settings &gt;&gt; Reading &gt;&gt; Search Engine Visibility is disabled. Shown on Settings/Tools/Plugins.', 'mtt' ),
                'default'  => false
            ),
            array( # Remove WP creator
                'id'       => 'wpdisable_version_full',
                'type'     => 'switch',
                'title'    => esc_html__( 'Eliminate WP version in &lt;head&gt;', 'mtt' ),
                'desc'     => '<strike><code>&lt;meta name="generator" content="WordPress 6.1.1" /&gt;</code></strike>',
                'default'  => false
            ),
            array( # Remove WP version number
                'id'       => 'wpdisable_version_number',
                'type'     => 'switch',
                'title'    => esc_html__( 'Eliminate only WP version in &lt;head&gt;', 'mtt' ),
                'desc'     => '<code>&lt;meta name="generator" content="WordPress <strike>6.1.1</strike>" /&gt;</code>',
                'default'  => false
            ),
            array( # Disable scripts versioning
                'id'       => 'wpdisable_scripts_versioning',
                'type'     => 'switch',
                'title' => esc_html__( 'Remove versioning from scripts and styles', 'mtt' ),
                'desc' => esc_html__( 'Disables the suffix ?ver=NUMBER that WP appends to enqueued styles and scripts.', 'mtt') 
                    . esc_html__('Tip via:', 'mtt' )
                    . ADTW()->makeTipCredit( 
                        'WordPress Answers', 
                        'http://wordpress.stackexchange.com/a/96325/12615' 
                    ),
                'default'  => false
            ),

            array( ####### UPDATES
				'id'       => 'general-3',
				'type'     => 'section',
				'title'    => esc_html__( 'UPDATES', 'mtt' ),
				'indent'   => false, 
			),
            array( # Block upgrade notices for non-admins
                'id'       => 'wpblock_update_wp',
                'type'     => 'switch',
                'title' => esc_html__( 'Block WordPress upgrade notice for non-admins', 'mtt' ),
                'desc' =>  esc_html__( 'Do not bug non-admins, please!', 'mtt' ) . ' '
                    . esc_html__( 'Tip via:', 'mtt' ) . ' '
                    . ADTW()->makeTipCredit( 
                        'WordPress Answers', 
                        'http://wordpress.stackexchange.com/q/13000/12615' 
                    ),
                'default'  => false
            ),
            array( # Block upgrade notices for all
                'id'       => 'wpblock_update_wp_all',
                'type'     => 'switch',
                'title'    => esc_html__( 'Block WordPress upgrade notice for everyone', 'mtt' ),
                'default'  => false
            ),
            array( # Redirect About Page
                'id'       => 'wpblock_update_screen',
                'type'     => 'switch',
                'title' => esc_html__( 'Redirect About page after upgrading.', 'mtt' ),
                'desc' => esc_html__( 'After upgrading WordPress, the default behavior is redirecting to the About page. This option changes that to going back to the very Upgrade screen.', 'mtt' ),
                'default'  => false
            ),

            array( ####### PRIVACY
				'id'       => 'general-4',
				'type'     => 'section',
				'title'    => esc_html__( 'PRIVACY and RESTRICTIONS', 'mtt' ),
				'indent'   => false, 
			),
            array( # Disable Self Ping
                'id'       => 'wpdisable_selfping',
                'type'     => 'switch',
                'title' => esc_html__( 'Disable Self Ping', 'mtt' ),
                'desc' => esc_html__( 'Prevents WordPress from sending pings/trackbacks to your own site.', 'mtt' ),
                'default'  => false
            ),
            array( # Redirect unauthorized
                'id'       => 'wpdisable_redirect_disallow',
                'type'     => 'switch',
                'title' => esc_html__( 'Redirect unauthorized attempts.', 'mtt' ),
                'desc' => sprintf( 
                    esc_html__( 'If the user tries to access an admin page directly via URL, e.g.: /wp-admin/plugins.php, instead of showing "you do not have persmissions", the browser is redirected to the frontend. Tip via: %s', 'mtt' ), 
                    ADTW()->makeTipCredit( 
                        'WordPress Answers', 
                        'http://wordpress.stackexchange.com/q/57206/12615'
                    )
                ),
                'default'  => false
            ),

            array( ####### RSS
				'id'       => 'general-6',
				'type'     => 'section',
				'title'    => esc_html__( 'RSS', 'mtt' ),
				'indent'   => false, 
			),
            array( # RSS delay
                'id'       => 'wprss_delay_publish_enable',
                'type'     => 'switch',
                'title'   => esc_html__( 'Delay RSS feed update', 'mtt' ),
                'desc'   => sprintf( 
                    esc_html__( 'This can give you time to make corrections after publishing a post, delaying the update in your RSS feed. Or you can make your content web exclusive for a larger period. Tip via: %s', 'mtt' ), 
                    ADTW()->makeTipCredit( 
                        'WordPress Answers', 
                        'http://wordpress.stackexchange.com/a/1896/12615'
                    )
                ),
                'default'  => false
            ),
            array( ## RSS time
                'id'       => 'wprss_delay_publish_time',
                'type'     => 'text',
                'title'    => esc_html__( 'Number of delay', 'mtt' ),
                'validate' => 'numeric',
                'default'  => '0',
                'required' => array( 'wprss_delay_publish_enable', '=', true ),
            ),
			array( ## RSS period 
				'id'       => 'wprss_delay_publish_period',
				'type'     => 'select',
				'data'     => array(
                    'MINUTE' => esc_html__( 'MINUTE', 'mtt' ),
                    'HOUR'   => esc_html__( 'HOUR', 'mtt' ),
                    'DAY'    => esc_html__( 'DAY', 'mtt' ),
                    'WEEK'   => esc_html__( 'WEEK', 'mtt' ),
                    'MONTH'  => esc_html__( 'MONTH', 'mtt' ),
                    'YEAR'   => esc_html__( 'YEAR', 'mtt' )
                ),
                'multi'    => false,
				'title'    => esc_html__( 'Period of delay', 'mtt' ),
				'required' => array( 'wprss_delay_publish_enable', '=', true ),
			),

            array( ####### AUTOCORRECTS
				'id'       => 'general-7',
				'type'     => 'section',
				'title'    => esc_html__( 'AUTOCORRECTS', 'mtt' ),
				'indent'   => false, 
			),
            array( # Disable WP Texturize
                'id'       => 'wpdisable_texturize_all',
                'type'     => 'switch',
                'title' => esc_html__( 'Disable WP Texturize everywhere', 'mtt' ),
                'desc' => esc_html__( '[Texturize is the] transformations of quotes into smart quotes, apostrophes, dashes, ellipses, the trademark symbol, and the multiplication symbol.', 'mtt' )
                . sprintf(
                    ' %s <a href="https://developer.wordpress.org/reference/functions/wptexturize/">%s.</a>',
                    esc_html__( 'See', 'mtt'),
                    esc_html__( 'documentation', 'mtt'),
                ),
                'default'  => false
            ),
            array( # Disable WP Texturize selectively
                'id'       => 'wpdisable_texturize_some',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Disable WP Texturize selectively', 'mtt' ),
                'subtitle' => esc_html__('The most common to disable are the first three...'),
                'multi'    => true,
                'options' => array(
                    'the_title' => esc_html__('Title', 'mtt'),
                    'the_excerpt' => esc_html__('Excerpt', 'mtt'),
                    'the_content' => esc_html__('Content', 'mtt'),
                    'comment_text' => esc_html__('Comment', 'mtt'),
                    'bloginfo' => esc_html__('Blog Info', 'mtt'),
                    'document_title' => esc_html__('Document Title', 'mtt'),
                    'link_description' => esc_html__('Link Description', 'mtt'),
                    'link_name' => esc_html__('Link Name', 'mtt'),
                    'link_notes' => esc_html__('Link Notes', 'mtt'),
                    'nav_menu_description' => esc_html__('Menu Description', 'mtt'),
                    'term_description' => esc_html__('Term Description', 'mtt'),
                    'term_name' => esc_html__('Term Name', 'mtt'),
                    'the_excerpt_embed' => esc_html__('Excerpt Embed', 'mtt'),
                    'the_post_thumbnail_caption' => esc_html__('Post Thumbnail Caption', 'mtt'),
                    'widget_text_content' => esc_html__('Widget Text Content', 'mtt'),
                    'widget_title' => esc_html__('Widget Title', 'mtt'),
                    'wp_title' => esc_html__('WP Title', 'mtt'),
                ),
				'required' => array( 'wpdisable_texturize_all', '=', false ),
            ),
            array( # Disable Auto P
                'id'       => 'wpdisable_autop',
                'type'     => 'button_set',
                'title' => esc_html__( 'Disable Auto P', 'mtt' ),
                'subtitle' => esc_html__( 'Prevents WordPress from replacing double line breaks with paragraph elements &lt;p&gt;', 'mtt' ),
                'multi'  => true,
                'options' => array(
                    'the_content' => esc_html__('Content', 'mtt'),
                    'the_excerpt' => esc_html__('Excerpt', 'mtt'),
                    'comment_text' => esc_html__('Comment', 'mtt'),
                    'term_description' => esc_html__('Term Description', 'mtt'),
                    'widget_text_content' => esc_html__('Widget Text Content', 'mtt'),
                    'the_excerpt_embed' => esc_html__('Excerpt Embed', 'mtt'),
                )
            ),
            array( # Remove WP from admin title
                'id'       => 'wpdisable_wptitle',
                'type'     => 'switch',
                'title' => esc_html__( 'Remove WordPress from admin page titles', 'mtt' ),
                'desc' => sprintf( 
                    esc_html__( 'The browser title has "- WordPress" appended to it. This will remove it. Tip via: %s', 'mtt' ), 
                    ADTW()->makeTipCredit( 
                        'WordPress Answers', 
                        'http://wordpress.stackexchange.com/a/17034/12615'
                    )
                ),
                'default'  => false
            ),
        ]
	)
);