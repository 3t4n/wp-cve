<?php

/**
 * Initialize live code editor customizer panel
 *
 * @link       http://www.ozanwp.com
 * @since      1.0.0
 *
 * @package    Live_Code_Editor
 * @subpackage Live_Code_Editor/includes
 * @author     Ozan Canakli <ozan@ozanwp.com>
 */

   
/**
 * Live Code Editor Panel
 *
 * @since    1.0.0
 */
$wp_customize->add_panel( 'live-code-editor', array(
    'title'               => __( 'Live Code Editor', 'live-css-js-code-editor' ),
    'description'       => __( 'Live code editor.', 'live-css-js-code-editor' ),
    'priority'          => 10001,
    'capability'        => 'edit_theme_options',
) );

/**
 * CSS Section
 *
 * @since    1.0.0
 */
$wp_customize->add_section( 'live-code-css-section', array(
    'title'             => __( 'CSS Code', 'live-css-js-code-editor' ),
    'panel'             => 'live-code-editor',
    'capability'        => 'edit_theme_options',
) );

/* CSS Settings */
$wp_customize->add_setting( 'live_code_css_field', array(
    'default'           => '',
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'transport'         => 'postMessage',
) );

/* CSS Controls */
$wp_customize->add_control( new Live_Code_Editor_Customizer_Control( $wp_customize, 'live_code_css_field', array(
    'label'             => __( 'CSS Code', 'live-css-js-code-editor' ),
    'description'       => __('CSS entered in the box below will be rendered within &lt;style&gt; tags.', 'live-css-js-code-editor'),
    'type'              => 'code',
    'mode'              => 'css',
    'section'           => 'live-code-css-section',
) ) );


/**
* JavaScript Section
*
* @since    1.0.0
*/
$wp_customize->add_section( 'live-code-js-section', array(
    'title'             => __( 'JavaScript Code', 'live-css-js-code-editor' ),
    'panel'             => 'live-code-editor',
    'capability'        => 'edit_theme_options',
) );

/* JavaScript Settings */
$wp_customize->add_setting( 'live_code_js_field', array(
    'default'           => '',
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'transport'         => 'postMessage',
) );

/* JavaScript Controls */
$wp_customize->add_control( new Live_Code_Editor_Customizer_Control( $wp_customize, 'live_code_js_field', array(
    'label'             => __( 'JavaScript Code', 'live-css-js-code-editor' ),
    'description'       => __('JavaScript entered in the box below will be rendered within &lt;script&gt; tags.', 'live-css-js-code-editor'),
    'type'              => 'code',
    'mode'              => 'javascript',
    'preview_button'    => true,
    'section'           => 'live-code-js-section',
) ) );


/**
* Header Section
*
* @since    1.0.0
*/
$wp_customize->add_section( 'live-code-header-section', array(
    'title'             => __( 'Header Code', 'live-css-js-code-editor' ),
    'panel'             => 'live-code-editor',
    'capability'        => 'edit_theme_options',
) );

/* Header Settings */
$wp_customize->add_setting( 'live_code_header_field', array(
    'default'           => '',
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'transport'         => 'postMessage',
) );

/* Header Controls */
$wp_customize->add_control( new Live_Code_Editor_Customizer_Control( $wp_customize, 'live_code_header_field', array(
    'label'             => __( 'Header Code', 'live-css-js-code-editor' ),
    'description'       => __('Code entered in the box below will be rendered inside the &lt;head&gt; tag.', 'live-css-js-code-editor'),
    'type'              => 'code',
    'preview_button'    => true,
    'section'           => 'live-code-header-section',
) ) );


/**
* Footer Section
*
* @since    1.0.0
*/
$wp_customize->add_section( 'live-code-footer-section', array(
    'title'             => __( 'Footer Code', 'live-css-js-code-editor' ),
    'panel'             => 'live-code-editor',
    'capability'        => 'edit_theme_options',
) );

/* Footer Settings */
$wp_customize->add_setting( 'live_code_footer_field', array(
    'default'           => '',
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'transport'         => 'postMessage',
) );

/* Footer Controls */
$wp_customize->add_control( new Live_Code_Editor_Customizer_Control( $wp_customize, 'live_code_footer_field', array(
    'label'             => __( 'Footer Code', 'live-css-js-code-editor' ),
    'description'       => __('Code entered in the box below will be rendered directly before the closing &lt;body&gt; tag.', 'live-css-js-code-editor'),
    'type'              => 'code',
    'preview_button'    => true,
    'section'           => 'live-code-footer-section',
) ) );

/**
 * Admin CSS Code Section
 *
 * @since    1.0.5
 */
$wp_customize->add_section( 'live-code-admin-css-section', array(
    'title'             => __( 'Admin CSS Code', 'live-css-js-code-editor' ),
    'panel'             => 'live-code-editor',
    'capability'        => 'edit_theme_options',
) );

/* Admin CSS code Settings */
$wp_customize->add_setting( 'live_code_admin_css_field', array(
    'default'           => '',
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'transport'         => 'postMessage',
) );

/* Admin CSS code Controls */
$wp_customize->add_control( new Live_Code_Editor_Customizer_Control( $wp_customize, 'live_code_admin_css_field', array(
    'label'             => __( 'Admin CSS Code', 'live-css-js-code-editor' ),
    'description'       => __('CSS entered in the box below will be rendered within &lt;style&gt; tags in <strong>WordPress Admin Dashboard.</strong>', 'live-css-js-code-editor'),
    'type'              => 'code',
    'mode'              => 'css',
    'section'           => 'live-code-admin-css-section',
) ) );

/**
* Admin JavaScript Section
*
* @since    1.0.0
*/
$wp_customize->add_section( 'live-code-admin-js-section', array(
    'title'             => __( 'Admin JavaScript Code', 'live-css-js-code-editor' ),
    'panel'             => 'live-code-editor',
    'capability'        => 'edit_theme_options',
) );

/* JavaScript Settings */
$wp_customize->add_setting( 'live_code_admin_js_field', array(
    'default'           => '',
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'transport'         => 'postMessage',
) );

/* JavaScript Controls */
$wp_customize->add_control( new Live_Code_Editor_Customizer_Control( $wp_customize, 'live_code_admin_js_field', array(
    'label'             => __( 'Admin JavaScript Code', 'live-css-js-code-editor' ),
    'description'       => __('JavaScript entered in the box below will be rendered within &lt;script&gt; tags in <strong>WordPress Admin Dashboard.</strong>', 'live-css-js-code-editor'),
    'type'              => 'code',
    'mode'              => 'javascript',
    'preview_button'    => false,
    'section'           => 'live-code-admin-js-section',
) ) );

/**
* Code Editor Color Scheme Section
*
* @since    1.0.0
*/
$wp_customize->add_section( 'live-code-theme-section', array(
    'title'             => __( 'Code Editor Color Scheme ', 'live-css-js-code-editor' ),
    'panel'             => 'live-code-editor',
    'capability'        => 'edit_theme_options',
) );

/* Code Editor Color Scheme Settings */
$wp_customize->add_setting( 'live_code_theme', array(
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'transport'         => 'postMessage',
    'default'           => 'chrome',
) );

/* Code Editor Color Scheme Controls */
$wp_customize->add_control( 'live_code_theme', array(
    'label'             => __( 'Code Editor Color Scheme', 'live-css-js-code-editor' ),
    'section'           => 'live-code-theme-section',
    'type'              => 'select',
    'choices'           => array(
        'chrome'             => __( 'Chrome', 'live-css-js-code-editor' ),
        'dreamweaver'        => __( 'Dreamweaver', 'live-css-js-code-editor' ),
        'eclipse'            => __( 'Eclipse', 'live-css-js-code-editor' ),
        'github'             => __( 'GitHub', 'live-css-js-code-editor' ),
        'solarized_light'    => __( 'Solarized Light', 'live-css-js-code-editor' ),
        'textmate'           => __( 'Text Mate', 'live-css-js-code-editor' ),
        'xcode'              => __( 'Xcode', 'live-css-js-code-editor' ),
        'kuroir'             => __( 'Kuroir', 'live-css-js-code-editor' ),
        'ambiance'           => __( 'Ambiance (Dark)', 'live-css-js-code-editor' ),
        'idle_fingers'       => __( 'idle Fingers (Dark)', 'live-css-js-code-editor' ),
        'mono_industrial'    => __( 'Mono Industrial (Dark)', 'live-css-js-code-editor' ),
        'monokai'            => __( 'Monokai (Dark)', 'live-css-js-code-editor' ),
        'pastel_on_dark'     => __( 'Pastel on Dark', 'live-css-js-code-editor' ),
        'solarized_dark'     => __( 'Solarized Dark', 'live-css-js-code-editor' ),
        'twilight'           => __( 'Twilight (Dark)', 'live-css-js-code-editor' ),
    ),
) );