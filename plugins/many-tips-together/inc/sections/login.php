<?php
/**
 * Section Login and Logout config
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;

$adtw_hide_languages = empty(get_available_languages()) 
    ? [] : array(
        'id'       => 'loginpage_hide_languages',
        'type'     => 'switch',
        'title'    => esc_html__('Hide Languages dropdown', 'mtt'), 
        'default'  => false
    );

\Redux::set_section(
	$adtw_option,
	array(
		'title' => esc_html__( 'Login', 'mtt' ),
		'id'    => 'login',
        'icon' => 'el el-key',
		'class' => 'highlight',
        'fields' => [
            array( ####### REDIRECTION
				'id'       => 'login-1',
				'type'     => 'section',
				'title'    => false,
				'indent'   => false, 
			),
            array( # Login Redirect
                'id'       => 'login_redirect_enable',
                'type'     => 'switch',
                'title'	=> esc_html__('Redirect login', 'mtt'),
                'desc' 	=> esc_html__('The default behavior is being redirected to the Dashboard (index.php).', 'mtt'),
                'default'  => false
            ),
            array( ## Login Redirect URL
                'id'       => 'login_redirect_url',
                'type'     => 'text',
                'desc'    => esc_html__('URL to redirect to', 'mtt'), 
                'validate' => 'url',
                'required' => array( 'login_redirect_enable', '=', true ),
            ),
            array( # Logout Redirect
                'id'       => 'logout_redirect_enable',
                'type'     => 'switch',
                'title'	=> esc_html__('Redirect logout', 'mtt'),
                'desc' 	=> esc_html__('The default behavior is being redirected to the login page...', 'mtt'),
                'default'  => false
            ),
            array( ## Logout Redirect URL
                'id'       => 'logout_redirect_url',
                'type'     => 'text',
                'desc'    => esc_html__('URL to redirect to', 'mtt'), 
                'validate' => 'url',
                'required' => array( 'logout_redirect_enable', '=', true ),
            ),

            array( ####### LABELS 
				'id'       => 'login-6',
				'type'     => 'section',
				'title'    => esc_html__( 'LABELS', 'mtt' ),
				'indent'   => false, 
			),
            $adtw_hide_languages,
            array( # Hide Back to Site
                'id'       => 'loginpage_backsite_hide',
                'type'     => 'switch',
                'title' => sprintf( esc_html__('Hide link "Back to %s"', 'mtt'), get_bloginfo('name') ),
                'desc'=> esc_html__('You can use the logo for that.', 'mtt'), 
                'default'  => false
            ),
            array( # Hide Back to Site
                'id'       => 'loginpage_backsite_hide',
                'type'     => 'switch',
                'title' => sprintf( esc_html__('Hide link "Back to %s"', 'mtt'), get_bloginfo('name') ),
                'desc'=> esc_html__('You can use the logo for that.', 'mtt'), 
                'default'  => false
            ),
            array( # Hide Name and PW
                'id'       => 'loginpage_labels_hide',
                'type'     => 'switch',
                'title' => esc_html__('Hide name and password labels', 'mtt'),
                'default'  => false
            ),
            array( # Hide Lost PW
                'id'       => 'loginpage_pw_hide',
                'type'     => 'switch',
                'title' => esc_html__('Hide Lost Password link', 'mtt'),
                'default'  => false
            ),

            array( ####### LOGO
				'id'       => 'login-3',
				'type'     => 'section',
				'title'    => esc_html__( 'LOGO', 'mtt' ),
				'indent'   => false, 
			),
            array( # Logo URL
                'id'       => 'loginpage_logo_url',
                'type'     => 'text',
                'title'    => esc_html__('Link for the logo (full URL)', 'mtt'), 
                'desc'=> esc_html__('Link for the logo, default: http://wordpress.org', 'mtt'),
            ),
			array( # Logo Image
                'id'       => 'loginpage_logo_img',
				'type'     => 'media',
				'title'    => esc_html__('Logo image', 'mtt'),
                'desc'     => esc_html__('Select an image from your library or upload a new one', 'mtt'),
                'url'      => false,
				'preview'  => true,
			),
			array( # Logo Height
				'id'             => 'loginpage_logo_height',
				'type'           => 'dimensions',
				'units'          => 'px',
				'title'          => esc_html__('Logo height', 'mtt'), 
                'desc'           => esc_html__('Default: 84 - maximum value recomended:  300px', 'mtt'),
                'width'          => false,
			),

            array( ####### BOX
				'id'       => 'login-4',
				'type'     => 'section',
				'title'    => esc_html__( 'BOX', 'mtt' ),
				'indent'   => false, 
			),
			array( # BOX Width
				'id'             => 'loginpage_form_dimensions',
				'type'           => 'dimensions',
				'units'          => array( 'em', 'px', '%' ),
				'units_extended' => true,
                'title'=> esc_html__('Width', 'mtt'), 
                'desc'=> esc_html__('The logo width is limited by this one', 'mtt'), 
                'default'        => '0'
            ),
			array( # BOX Margin Top
				'id'             => 'loginpage_form_margintop',
				'type'           => 'dimensions',
				'units'          => 'px',
				'title'          => esc_html__('Margin Top', 'mtt'), 
				'width'          => false,
                'default'        => '0'
			),
			array( # BOX Rounded
				'id'             => 'loginpage_form_rounded',
				'type'           => 'dimensions',
				'units'          => 'px',
				'title'          => esc_html__('Rounded corners', 'mtt'), 
				'height'         => false,
                'default'        => '0'
			),
            array( # BOX Border
                'id'       => 'loginpage_form_border',
                'type'     => 'switch',
                'title' => esc_html__('Remove border', 'mtt'), 
                'default'  => false
            ),
			array( # BOX Color
				'id'          => 'loginpage_form_bg_color',
				'type'        => 'color',
				'title'       => esc_html__('Background color', 'mtt'), 
				'transparent' => false,
				'color_alpha' => true,
            ),
			array( # BOX Image
                'id'       => 'loginpage_form_bg_img',
				'type'     => 'media',
				'title'    =>  esc_html__('Background image', 'mtt'),
				'url'      => false,
				'preview'  => true,
			),

            array( ####### BACKGROUND
				'id'       => 'login-5',
				'type'     => 'section',
				'title'    => esc_html__( 'BACKGROUND', 'mtt' ),
				'indent'   => false, 
			),
			array( # BG Image
                'id'       => 'loginpage_body_img',
				'type'     => 'media',
				'title'    => esc_html__('Background image', 'mtt'),
				'url'      => false,
				'preview'  => true,
			),
			array( # BG Color
				'id'          => 'loginpage_body_color',
				'type'        => 'color',
				'title'       => esc_html__('Background color', 'mtt'),
				'transparent' => false,
				'color_alpha' => false,
            ),
			array( # BG Position
				'id'       => 'loginpage_body_position',
				'type'     => 'select',
				'title'    => esc_html__('Background position', 'mtt'), 
				'options'  => array(
                    'empty'         => '&nbsp;',
                    'left_top'      => 'left top', 
                    'left_center'   => 'left center', 
                    'left_bottom'   => 'left bottom', 
                    'right_top'     => 'right top', 
                    'right_center'  => 'right center', 
                    'right_bottom'  => 'right bottom', 
                    'center_top'    => 'center top', 
                    'center_center' => 'center center', 
                    'center_bottom' => 'center bottom'
                ),
			),
			array( # BG Repeat
				'id'       => 'loginpage_body_repeat',
				'type'     => 'select',
				'title'    => esc_html__('Background repeat', 'mtt'), 
				'options'  => array(
                    'empty'     => '&nbsp;',
                    'repeat'    => 'repeat',
                    'no-repeat' => 'no-repeat' 
                ),
			),
			array( # BG Scroll
				'id'       => 'loginpage_body_attachment',
				'type'     => 'select',
				'title'    => esc_html__('Background scroll', 'mtt'), 
				'options'  => array(
                    'empty'  => '&nbsp;',
                    'fixed'  => 'fixed', 
                    'scroll' => 'scroll', 
                ),
			),

            array( ####### ERRORS
				'id'       => 'login-2',
				'type'     => 'section',
				'title'    => esc_html__( 'ERRORS', 'mtt' ),
				'indent'   => false, 
			),
            array( # Error Message remove
                'id'       => 'loginpage_errors',
                'type'     => 'switch',
                'title'=> esc_html__('Remove error message', 'mtt'), 
                'desc'=> esc_html__('Don\'t reveal what\'s the mistake, user or password', 'mtt'), 
                'default'  => false
            ),
            array( # Error Message text
                'id'       => 'loginpage_errors_txt',
                'type'     => 'text',
                'placeholder' => esc_html__('Leave empty for no message', 'mtt'),
                'desc'=> esc_html__("Custom error message. Don't use html code.", 'mtt'), 
                'required' => array( 'loginpage_errors', '=', true ),
            ),
            array( # Error Shaking
                'id'       => 'loginpage_disable_shaking',
                'type'     => 'switch',
                'title' => esc_html__('Disable the login box shaking for the errors and other notices.', 'mtt'), 
                'default'  => false
            ),

            array( ####### CSS
				'id'       => 'login-7',
				'type'     => 'section',
				'title'    => esc_html__( 'CSS and JS', 'mtt' ),
				'indent'   => false, 
			),
            array( # Remove All CSS
                'id'       => 'loginpage_remove_css',
                'type'     => 'switch',
                'title' => esc_html__('Completely remove WordPress styles in Login page.', 'mtt'), 
                'desc' => 
                    esc_html__( 'You can look for inspiration at ', 'mtt' )
                    . '<a href="https://codepen.io/search/pens?q=wordpress+login" target="_blank">CodePen.io</a>. '
                    . esc_html__( 'Paste your full CSS bellow.', 'mtt' ),
                'default'  => false
            ),
			array( # Extra CSS
				'id'       => 'loginpage_extra_css',
				'type'     => 'ace_editor',
                'title' => esc_html__('Extra CSS', 'mtt'),
                'subtitle' => sprintf(
                    esc_html__('Style tag not needed %s', 'mtt'),
                    '(<code>&lt;style&gt;</code>)'
                ),
                'mode'     => 'css',
				'theme'    => 'monokai',
                'options'  => array(
                    'minLines' => 12, 
                    'maxLines' => 40,
                    'fontSize' => 22
                )
            ),
			array( # Extra JS
				'id'       => 'loginpage_extra_js',
				'type'     => 'ace_editor',
                'title' => esc_html__('Extra JS', 'mtt'),
                'subtitle' => sprintf(
                    esc_html__('Script tag not needed %s', 'mtt'),
                    '(<code>&lt;script&gt;</code>)'
                ),
                'mode'     => 'javascript',
				'theme'    => 'monokai',
                'options'  => array(
                    'minLines' => 12, 
                    'maxLines' => 40,
                    'fontSize' => 22
                )
            ),
			array( # Extra HTML
				'id'       => 'loginpage_extra_html',
				'type'     => 'ace_editor',
                'title' => esc_html__('Extra HTML', 'mtt'),
                'subtitle' => esc_html__('Injected inside the &lt;body&gt; tag', 'mtt') . '<br>' . esc_html__('Check some animated backgrouds I collected: ') . '<a href="https://gist.github.com/brasofilo/e759be47315754cdaa28c12fb666e9b8" target="_blank">Orbital</a> | <a href="https://gist.github.com/brasofilo/6ac829864aee9627926af24b63e9305e" target="_blank">Dolphin\'s Trigonometry</a>',
                'mode'     => 'html',
				'theme'    => 'monokai',
                'options'  => array(
                    'minLines' => 12, 
                    'maxLines' => 40,
                    'fontSize' => 22,
                    'fragmentContext' => 'body'
                )
            )
        ]    
    )
);