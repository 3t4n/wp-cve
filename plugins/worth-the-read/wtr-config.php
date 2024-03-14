<?php

if ( ! class_exists( 'Redux' ) ) {
    return;
}

// This is your option name where all the Redux data is stored.
$opt_name = "wtr_settings";

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name'             => $opt_name,
    'disable_tracking'     => true,
    // This is where your data is stored in the database and also becomes your global variable name.
    'display_name'         => __( 'Worth The Read', 'worth-the-read' ),
    // Name that appears at the top of your panel
    'display_version'      => '1.14.2',
    // Version that appears at the top of your panel
    'menu_type'            => 'menu',
    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu'       => true,
    // Show the sections below the admin menu item or not
    'menu_title'           => __( 'Worth The Read', 'worth-the-read' ),
    'page_title'           => __( 'Worth The Read', 'worth-the-read' ),
    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_api_key'       => '',
    // Set it you want google fonts to update weekly. A google_api_key value is required.
    'google_update_weekly' => false,
    // Must be defined to add google fonts to the typography module
    'async_typography'     => true,
    // Use a asynchronous font on the front end or font string
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar'            => false,
    // Show the panel pages on the admin bar
    'admin_bar_icon'       => 'dashicons-portfolio',
    // Choose an icon for the admin bar menu
    'admin_bar_priority'   => 50,
    // Choose an priority for the admin bar menu
    'global_variable'      => '',
    // Set a different name for your global variable other than the opt_name
    'dev_mode'             => false,
    // Show the time the page took to load, etc
    'update_notice'        => true,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer'           => true,
    // Enable basic customizer support
    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

    // OPTIONAL -> Give you extra features
    'page_priority'        => 99,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent'          => 'themes.php',
    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions'     => 'manage_options',
    // Permissions needed to access the options panel.
    'menu_icon'            => 'dashicons-text',
    // Specify a custom URL to an icon
    'last_tab'             => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon'            => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug'            => 'wtr_options',
    // Page slug used to denote the panel
    'save_defaults'        => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show'         => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark'         => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export'   => true,
    // Shows the Import/Export panel when not used as a field.

    // CAREFUL -> These options are for advanced use only
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag'           => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database'             => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!

    'use_cdn'              => true,
    // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

    //'compiler'             => true,

    // HINTS
    'hints'                => array(
        'icon'          => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color'    => 'lightgray',
        'icon_size'     => 'normal',
        'tip_style'     => array(
            'color'   => 'light',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
        ),
        'tip_position'  => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect'    => array(
            'show' => array(
                'effect'   => 'fade',
                'duration' => '200',
                'event'    => 'mouseover',
            ),
            'hide' => array(
                'effect'   => 'fade',
                'duration' => '500',
                'event'    => 'click mouseleave',
            ),
        ),
    )
);

// ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
/*
$args['admin_bar_links'][] = array(
    'id'    => 'redux-docs',
    'href'  => 'http://docs.reduxframework.com/',
    'title' => __( 'Documentation', 'worth-the-read' ),
);

$args['admin_bar_links'][] = array(
    //'id'    => 'redux-support',
    'href'  => 'https://github.com/ReduxFramework/redux-framework/issues',
    'title' => __( 'Support', 'worth-the-read' ),
);

$args['admin_bar_links'][] = array(
    'id'    => 'redux-extensions',
    'href'  => 'reduxframework.com/extensions',
    'title' => __( 'Extensions', 'worth-the-read' ),
);
*/

// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
/*
$args['share_icons'][] = array(
    'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
    'title' => 'Visit us on GitHub',
    'icon'  => 'el el-github'
    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
);
*/

// Panel Intro text -> before the form
/*
if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
    if ( ! empty( $args['global_variable'] ) ) {
        $v = $args['global_variable'];
    } else {
        $v = str_replace( '-', '_', $args['opt_name'] );
    }
    $args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'worth-the-read' ), $v );
} else {
    $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'worth-the-read' );
}

// Add content after the form.
$args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'worth-the-read' );
*/

Redux::setArgs( $opt_name, $args );

/*
 * ---> END ARGUMENTS
 */

/*
 * ---> START HELP TABS
 */

/*
$tabs = array(
    array(
        'id'      => 'redux-help-tab-1',
        'title'   => __( 'Theme Information 1', 'worth-the-read' ),
        'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'worth-the-read' )
    ),
    array(
        'id'      => 'redux-help-tab-2',
        'title'   => __( 'Theme Information 2', 'worth-the-read' ),
        'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'worth-the-read' )
    )
);
Redux::setHelpTab( $opt_name, $tabs );

// Set the help sidebar
$content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'worth-the-read' );
Redux::setHelpSidebar( $opt_name, $content );
*/

/*
 * <--- END HELP TABS
 */


/*
 *
 * ---> START SECTIONS
 *
 */

/*

    As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


 */

// -> START Reading Progress
Redux::setSection( $opt_name, array(
    'title' => __( 'Reading Progress', 'worth-the-read' ),
    'id'    => 'progress',
    'desc'  => __( 'Displays a reading progress bar indicator showing the user how far scrolled through the current post they are.', 'worth-the-read' ),
    'icon'  => 'el el-bookmark-empty'
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Functionality', 'worth-the-read' ),
    'id'         => 'progress-functionality',
    'subsection' => true,
    'desc'       => __( 'How the progress bar works.', 'worth-the-read' ),
    'fields'     => array(
        array(
            'id'       => 'progress-display',
            'type'     => 'button_set',
            'title'    => __( 'Display On', 'worth-the-read' ),
            'subtitle' => __( 'You can disable these and display only on specific pages or posts below.', 'worth-the-read' ),
            'multi'    => true,
            'options'  => array(
                'post' => 'Posts',
                'page' => 'Pages',
                'home' => 'Home Page'
            ),
            'default'  => array('posts')
        ),
        array(
            'id'       => 'progress-cpts',
            'type'     => 'button_set',
            'multi'    => true,
            'title'    => __( 'Custom Post Types', 'worth-the-read' ),
            'subtitle' => __( 'You can show the progress bar on custom post types, which are added by your theme and/or plugins.', 'worth-the-read' ),
            'desc' => __( 'Important: The progress bar will only display on pages that make use of the WordPress function the_content(), regardless of the post type. For instance, a bbPress forum post does not use the_content() and thus will not display a progress bar. Note: if there are no options displayed here, that means your custom post types were not detected by WTR, in which case you can use the freeform field below to manually enter them.', 'worth-the-read' ),
            'data'     => 'post_types',
            'args'     => array(
                            'public' => true, 
                            '_builtin' => false,
                            'exclude_from_search' => true
                        ),
        ),
        array(
            'id'       => 'progress-cpts-manual',
            'type'     => 'text',
            'title'    => __( 'Custom Post Types', 'worth-the-read' ),
            'subtitle' => __( 'Manually enter the slug of your custom post types if they were not detected above, in a comma-separated list.', 'worth-the-read' ),
            'desc'     => __( 'Example: "events, game_reviews, another_cpt_slug, as_many_as_you_want" (do not include the quotes)', 'worth-the-read' ),
            'default'  => '',
        ),
        array(
            'id'       => 'progress-posts-manual',
            'type'     => 'select',
            'multi'    => true,
            'data'     => 'posts',
            'args'     => array(
                            'posts_per_page' => -1,
                            'orderby'        => 'title',
                            'order'          => 'ASC',
                       ),
            'title'    => __( 'Specific Posts', 'worth-the-read' ),
            'subtitle' => __( 'Manually select posts to display the progress bar on.', 'worth-the-read' ),
            'default'  => '',
        ),
        array(
            'id'       => 'progress-pages-manual',
            'type'     => 'select',
            'multi'    => true,
            'data'     => 'pages',
            'args'     => array(
                            'posts_per_page' => -1,
                            'orderby'        => 'title',
                            'order'          => 'ASC',
                       ),
            'title'    => __( 'Specific Pages', 'worth-the-read' ),
            'subtitle' => __( 'Manually select pages to display the progress bar on.', 'worth-the-read' ),
            'default'  => '',
        ),
        array(
            'id'       => 'progress-comments',
            'type'     => 'switch',
            'title'    => __( 'Include Comments', 'worth-the-read' ),
            'subtitle' => __( 'The post comments should be included in the progress bar length', 'worth-the-read' ),
            'default'  => false,
        ),
        array(
            'id'       => 'progress-placement',
            'type'     => 'image_select',
            'title'    => __( 'Placement', 'worth-the-read' ),
            //Must provide key => value(array:title|img) pairs for radio options
            'options'  => array(
                'top' => array(
                    'alt' => 'Top',
                    'img' => ReduxFramework::$_url . 'assets/img/top.png'
                ),
                'bottom' => array(
                    'alt' => 'Bottom',
                    'img' => ReduxFramework::$_url . 'assets/img/bottom.png'
                ),
                'left' => array(
                    'alt' => 'Left',
                    'img' => ReduxFramework::$_url . 'assets/img/left.png'
                ),
                'right' => array(
                    'alt' => 'Right',
                    'img' => ReduxFramework::$_url . 'assets/img/right.png'
                )
            ),
            'default'  => 'top'
        ),
        array(
            'id'            => 'progress-offset',
            'type'          => 'slider',
            'title'          => __( 'Offset', 'worth-the-read' ),
            'subtitle'       => __( 'The progress bar can be offset from the Placement edge specified above', 'worth-the-read' ),
            'desc'           => __( 'This is handy for fixed headers and menus that you don\'t want covered up', 'worth-the-read' ),
            'default'       => 0,
            'min'           => 0,
            'step'          => 1,
            'max'           => 500,
            'display_value' => 'text'
        ),
        array(
            'id'       => 'progress-rtl',
            'type'     => 'switch',
            'title'    => __( 'RTL', 'worth-the-read' ),
            'subtitle' => __( 'Progress bar will move from right to left as the user scrolls down the page.', 'worth-the-read' ),
            'default'  => false,
            'required' => array('progress-placement', 'equals', array('top','bottom'))
        ),
        array(
            'id'       => 'progress-fixed-opacity',
            'type'     => 'switch',
            'title'    => __( 'Fixed Opacity', 'worth-the-read' ),
            'subtitle' => __( 'Always use the Muted Opacity - opacity will not change on scroll', 'worth-the-read' ),
            'default'  => false,
        ),
        array(
            'id'       => 'progress-non-touch',
            'type'     => 'switch',
            'title'    => __( 'Non-Touch Devices', 'worth-the-read' ),
            'subtitle' => __( 'Display on standard desktops and laptops', 'worth-the-read' ),
            'default'  => true,
        ),
        array(
            'id'       => 'progress-touch',
            'type'     => 'switch',
            'title'    => __( 'Touch Devices', 'worth-the-read' ),
            'subtitle' => __( 'Display on touch screen devices like phones and tablets', 'worth-the-read' ),
            'default'  => false,
        ),
        array(
            'id'       => 'progress-placement-touch',
            'type'     => 'image_select',
            'title'    => __( 'Touch Placement', 'worth-the-read' ),
            'subtitle'       => __( 'You can have different placement for touch devices.', 'worth-the-read' ),
            //Must provide key => value(array:title|img) pairs for radio options
            'options'  => array(
                'top' => array(
                    'alt' => 'Top',
                    'img' => ReduxFramework::$_url . 'assets/img/top.png'
                ),
                'bottom' => array(
                    'alt' => 'Bottom',
                    'img' => ReduxFramework::$_url . 'assets/img/bottom.png'
                ),
                'left' => array(
                    'alt' => 'Left',
                    'img' => ReduxFramework::$_url . 'assets/img/left.png'
                ),
                'right' => array(
                    'alt' => 'Right',
                    'img' => ReduxFramework::$_url . 'assets/img/right.png'
                )
            ),
            'default'  => 'top',
            'required' => array('progress-touch', 'equals', '1' )
        ),
        array(
            'id'            => 'progress-offset-touch',
            'type'          => 'slider',
            'title'          => __( 'Touch Offset', 'worth-the-read' ),
            'subtitle'       => __( 'You can have a different offset for touch devices.', 'worth-the-read' ),
            'default'       => 0,
            'min'           => 0,
            'step'          => 1,
            'max'           => 500,
            'display_value' => 'text',
            'required' => array('progress-touch', 'equals', '1' )
        ),
        array(
            'id'            => 'content-offset',
            'type'          => 'slider',
            'title'          => __( 'Content Offset', 'worth-the-read' ),
            'subtitle'       => __( 'You can offset where the progress bar thinks the content begins. This is handy if you have a large image at the beginning of your content that you want the progress bar to ignore, for instance.', 'worth-the-read' ),
            'desc'           => __( 'Please note: this is in relation to the content, not the entire page. The positioning of your actual content is already taken into account during the progress bar calculation. Setting this above 0 will apply additional offset.', 'worth-the-read' ),
            'default'       => 0,
            'min'           => 0,
            'step'          => 1,
            'max'           => 4000,
            'display_value' => 'text'
        ),
        array(
            'id'       => 'progress-debug',
            'type'     => 'switch',
            'title'    => __( 'Debug Mode', 'worth-the-read' ),
            'subtitle' => __( 'This will show JavaScript messages in the browser console as well as on-screen PHP messages on the front-end of your site.', 'worth-the-read' ),
            'default'  => false,
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Style', 'worth-the-read' ),
    'id'         => 'progress-style',
    'subsection' => true,
    'desc'       => __( 'How the progress bar looks.', 'worth-the-read' ),
    'fields'     => array(
        array(
            'id'            => 'progress-thickness',
            'type'          => 'slider',
            'title'          => __( 'Thickness', 'worth-the-read' ),
            'default'       => 5,
            'min'           => 1,
            'step'          => 1,
            'max'           => 500,
            'display_value' => 'text'
        ),
        array(
            'id'       => 'progress-foreground',
            'type'     => 'color',
            //'output'   => array( '.site-title' ),
            'title'    => __( 'Foreground', 'worth-the-read' ),
            'subtitle' => __( 'The part that moves on scroll', 'worth-the-read' ),
            'default'  => '#f44813',
        ),
        array(
            'id'            => 'progress-foreground-opacity',
            'type'          => 'slider',
            'title'          => __( 'Foreground Opacity', 'worth-the-read' ),
            'default'       => 0.5,
            'min'           => 0,
            'step'          => 0.01,
            'max'           => 1,
            'resolution'    => 0.01,
            'display_value' => 'label'
        ),
        array(
            'id'       => 'progress-background',
            'type'     => 'color',
            //'output'   => array( '.site-title' ),
            'title'    => __( 'Background', 'worth-the-read' ),
            'subtitle' => __( 'Stationary. Does not apply when Transparent Background is on', 'worth-the-read' ),
            'default'  => '#FFFFFF',
        ),
        array(
            'id'       => 'progress-comments-background',
            'type'     => 'color',
            //'output'   => array( '.site-title' ),
            'title'    => __( 'Comments Background', 'worth-the-read' ),
            'subtitle' => __( 'Only applies if Include Comments is on.', 'worth-the-read' ),
            'default'  => '#ffcece',
        ),
        array(
            'id'       => 'progress-transparent-background',
            'type'     => 'switch',
            'title'    => __( 'Transparent Background', 'worth-the-read' ),
            'subtitle' => __( 'Only the foreground (scrolling bar) will appear', 'worth-the-read' ),
            'default'  => false,
        ),
        array(
            'id'       => 'progress-shadow',
            'type'     => 'switch',
            'title'    => __( 'Shadow', 'worth-the-read' ),
            'subtitle' => __( 'Should the progress bar have a shadow effect.', 'worth-the-read' ),
            'default'  => true,
        ),
        array(
            'id'            => 'progress-muted-opacity',
            'type'          => 'slider',
            'title'         => __( 'Muted Opacity', 'worth-the-read' ),
            'subtitle'      => __( 'Bar opacity while idle (not scrolling)', 'worth-the-read' ),
            'hint'          => array(
                                'title'   => 'Tip',
                                'content' => '.50 seems to work pretty well here'
                            ),
            'default'       => 0.5,
            'min'           => 0,
            'step'          => 0.01,
            'max'           => 1,
            'resolution'    => 0.01,
            'display_value' => 'label'
        ),
        array(
            'id'       => 'progress-muted-foreground',
            'type'     => 'color',
            'title'    => __( 'Muted Foreground', 'worth-the-read' ),
            'subtitle' => __( "Foreground color while idle (not scrolling)", 'worth-the-read' ),
            'default'  => '#f44813',
        ),
        array(
            'id'       => 'progress-end-foreground',
            'type'     => 'color',
            'title'    => __( 'End Foreground', 'worth-the-read' ),
            'subtitle' => __( "Foreground color when end of article is reached", 'worth-the-read' ),
            'default'  => '#f44813',
        ),
    )
) );


// -> START Time Commitment
Redux::setSection( $opt_name, array(
    'title' => __( 'Time Commitment', 'worth-the-read' ),
    'id'    => 'time',
    'desc'  => __( 'A text label at the beginning of the post/page informing the user how long it will take them to read it, assuming a 200wpm pace.', 'worth-the-read' ),
    'icon'  => 'el el-time'
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Functionality', 'worth-the-read' ),
    'id'         => 'time-functionality',
    'subsection' => true,
    'desc'       => __( 'How the time commitment label works.', 'worth-the-read' ),
    'fields'     => array(
        array(
            'id'       => 'time-display',
            'type'     => 'button_set',
            'title'    => __( 'Display On', 'worth-the-read' ),
            'subtitle' => __( 'You can display this on single posts, single pages, and archive pages (anywhere posts are displayed in a loop, including your homepage).', 'worth-the-read' ),
            'multi'    => true,
            'options'  => array(
                'post' => 'Posts',
                'page' => 'Pages',
                'archives' => 'Archives'
            ),
            'default'  => array('post')
        ),
        array(
            'id'       => 'time-cpts',
            'type'     => 'button_set',
            'multi'    => true,
            'title'    => __( 'Custom Post Types', 'worth-the-read' ),
            'subtitle' => __( 'You can show the time commitment label on custom post types, which are added by your theme and/or plugins.', 'worth-the-read' ),
            'desc' => __( 'Important: The time label will only display on pages that make use of the WordPress function the_content(), regardless of the post type. For instance, a bbPress forum post does not use the_content() and thus will not display a time label. Note: if there are no options displayed here, that means your custom post types were not detected by WTR, in which case you can use the freeform field below to manually enter them.', 'worth-the-read' ),
            'data'     => 'post_types',
            'args'     => array(
                            'public' => true, 
                            '_builtin' => false,
                            'exclude_from_search' => true
                        ),
        ),
        array(
            'id'       => 'time-cpts-manual',
            'type'     => 'text',
            'title'    => __( 'Custom Post Types', 'worth-the-read' ),
            'subtitle' => __( 'Manually enter the slug of your custom post types if they were not detected above, in a comma-separated list.', 'worth-the-read' ),
            'desc'     => __( 'Example: "events, game_reviews, another_cpt_slug, as_many_as_you_want" (do not include the quotes)', 'worth-the-read' ),
            'default'  => '',
        ),
        array(
            'id'       => 'time-posts-manual',
            'type'     => 'select',
            'multi'    => true,
            'data'     => 'posts',
            'args'     => array(
                            'posts_per_page' => -1,
                            'orderby'        => 'title',
                            'order'          => 'ASC',
                       ),
            'title'    => __( 'Specific Posts', 'worth-the-read' ),
            'subtitle' => __( 'Manually select posts to display the reading time on.', 'worth-the-read' ),
            'default'  => '',
        ),
        array(
            'id'       => 'time-pages-manual',
            'type'     => 'select',
            'multi'    => true,
            'data'     => 'pages',
            'args'     => array(
                            'posts_per_page' => -1,
                            'orderby'        => 'title',
                            'order'          => 'ASC',
                       ),
            'title'    => __( 'Specific Pages', 'worth-the-read' ),
            'subtitle' => __( 'Manually select pages to display the reading time on.', 'worth-the-read' ),
            'default'  => '',
        ),
        array(
            'id'       => 'time-placement',
            'type'     => 'radio',
            'title'    => __( 'Placement', 'worth-the-read' ),
            'subtitle' => __( 'Only used where specified to display via the options above. If there is nothing selected for Display On or Custom Post Types, the only way to display the time commitment label is by using the shortcode.', 'worth-the-read' ),
            'desc' => __( 'Or you can use this shortcode: <b style="color:#05c134;">[wtr-time]</b>', 'worth-the-read'),
            'options'  => array(
                'before-title' => 'Before Title',
                'after-title' => 'After Title',
                'before-content' => 'Before Content'
            ),
            'default'  => 'after-title'
        ),
        array(
            'id'            => 'time-wpm',
            'type'          => 'slider',
            'title'          => __( 'Words Per Minute', 'worth-the-read' ),
            'subtitle'       => __( 'Average English words per minute is 200. This will vary for other languages, so you can change it here.', 'worth-the-read' ),
            'default'       => 200,
            'min'           => 1,
            'step'          => 1,
            'max'           => 500,
            'display_value' => 'text'
        ),
        array(
            'id'            => 'time-ppm',
            'type'          => 'slider',
            'title'          => __( 'Pictures Per Minute', 'worth-the-read' ),
            'subtitle'       => __( 'Set this to 0 if you do not want to take pictures into account when calculating reading time.', 'worth-the-read' ),
            'default'       => 5,
            'min'           => 0,
            'step'          => 1,
            'max'           => 20,
            'display_value' => 'text'
        ),
        array(
            'id'       => 'time-format',
            'type'     => 'text',
            'title'    => __( 'Format', 'worth-the-read' ),
            'subtitle' => __( 'Use # as a placeholder for the number', 'worth-the-read' ),
            'desc'     => __( 'Example: "# min read" becomes "12 min read"', 'worth-the-read' ),
            'default'  => '# min read',
        ),
        array(
            'id'       => 'time-format-singular',
            'type'     => 'text',
            'title'    => __( 'Singular Format', 'worth-the-read' ),
            'subtitle' => __( 'Optionally specify a different singular format', 'worth-the-read' ),
            'desc'     => __( 'This will only be used if the read time is 1 minute', 'worth-the-read' ),
            'default'  => '',
        ),
        array(
            'id'       => 'time-method',
            'type'     => 'radio',
            'title'    => __( 'Count Method', 'worth-the-read' ),
            'subtitle' => __( 'There are two ways of counting total words, and you can select which way you prefer to use to calculate total time commitment.', 'worth-the-read' ),
            'desc' => __( 'Both methods strip html tags and count only the pure text on the page', 'worth-the-read'),
            'options'  => array(
                'word-count' => 'str_word_count (good for latin languages)',
                'space' => 'count spaces (good for non-latin/cyrillic languages)'
            ),
            'default'  => 'word-count'
        ),
        array(
            'id'       => 'time-block-level',
            'type'     => 'switch',
            'title'    => __( 'Block-Level', 'worth-the-read' ),
            'subtitle' => __( 'Do not float the label next to anything (make it its own line)', 'worth-the-read' ),
            'default'  => false,
        )
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Style', 'worth-the-read' ),
    'id'         => 'time-style',
    'subsection' => true,
    'desc'       => __( 'How the time commitment label looks.', 'worth-the-read' ),
    'fields'     => array(
        array(
            'id'       => 'time-typography',
            'type'     => 'typography',
            'title'    => __( 'Font', 'worth-the-read' ),
            'subtitle' => __( 'Leave unselected to use theme defaults', 'worth-the-read' ),
            'google'   => true,
            'output'   => array('.wtr-time-wrap'),
            'default'  => array(
                'color'       => '#CCCCCC',
                'font-size'   => '16px',
            ),
        ),
        array(
            'id'       => 'time-css',
            'type'     => 'ace_editor',
            'title'    => __( 'Custom CSS', 'worth-the-read' ),
            'mode'     => 'css',
            'theme'    => 'monokai',
            'default'  => "
.wtr-time-wrap{ 
    /* wraps the entire label */
    margin: 0 10px;

}
.wtr-time-number{ 
    /* applies only to the number */
    
}"
        ),
    )
) );

/*
 * <--- END SECTIONS
 */


?>