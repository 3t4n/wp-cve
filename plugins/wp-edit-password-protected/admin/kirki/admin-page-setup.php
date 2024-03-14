<?php

/**
 *
 * @package wp Edit Password Protected 
 * @category admin page options
 * @author Noor Alam
 * @copyright Copyright (c) 2022, wptheme space
 * @license GPL V2
 * @since 1.1
 */

use Kirki\Util\Helper;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Do not proceed if Kirki does not exist.
if (!class_exists('kirki')) {
    return;
}

//old options 
// Default value 
$pp_admin_page = get_option('pp_admin_page');
$pp_page_options = (!empty($pp_admin_page)) ? $pp_admin_page : '';

// All options
$pp_page_featureimg = (isset($pp_page_options['pp_page_featureimg'])) ? $pp_page_options['pp_page_featureimg'] : 'hide';
$pp_page_head = (isset($pp_page_options['pp_page_head'])) ? $pp_page_options['pp_page_head'] : 'on';
$pp_massage_title = (isset($pp_page_options['pp_massage_title'])) ? $pp_page_options['pp_massage_title'] : __('This content is password protected for members only', 'wp-edit-password-protected');
$pp_title_tag = (isset($pp_page_options['pp_title_tag'])) ? $pp_page_options['pp_title_tag'] : 'h2';
$pp_massage_desc = (isset($pp_page_options['pp_massage_desc'])) ? $pp_page_options['pp_massage_desc'] : __('<p>This content is password protected for members only. <br> If you want to see this content please login. </p>', 'wp-edit-password-protected');
$pp_content_shortcode = (isset($pp_page_options['pp_content_shortcode'])) ? $pp_page_options['pp_content_shortcode'] : '';
$pp_page_comment = (isset($pp_page_options['pp_page_comment'])) ? $pp_page_options['pp_page_comment'] : 'hide';
$pp_login_link = (isset($pp_page_options['pp_login_link'])) ? $pp_page_options['pp_login_link'] : 'show';
$pp_login_link_url = (isset($pp_page_options['pp_login_link_url'])) ? $pp_page_options['pp_login_link_url'] : wp_login_url();
$pp_login_btn_text = (isset($pp_page_options['pp_login_btn_text'])) ? $pp_page_options['pp_login_btn_text'] : __('Login', 'wp-edit-password-protected');
$pp_login_btn_class = (isset($pp_page_options['pp_login_btn_class'])) ? $pp_page_options['pp_login_btn_class'] : 'btn button';
$pp_page_class = (isset($pp_page_options['pp_page_class'])) ? $pp_page_options['pp_page_class'] : ' ';
$pp_page_text_align = (isset($pp_page_options['pp_page_text_align'])) ? $pp_page_options['pp_page_text_align'] : 'text-center';

function wppass_adminpage_option_default($item = '')
{
    if ($item == 'on' || $item == 1) {
        return 'on';
    }
    return 'off';
}



/**
 * Add a panel.
 *
 */
new \Kirki\Panel(
    'wppass_adminpage_panel',
    [
        'priority'    => 10,
        'title'       => esc_html__('Admin Only Page', 'wp-edit-password-protected'),
        'description' => esc_html__('All settins for admin only page.', 'wp-edit-password-protected'),
    ]
);

/**
 * member only page default setup
 *
 */
new \Kirki\Section(
    'wpe_adpage_default',
    [
        'title'       => esc_html__('Default Settings', 'wp-edit-password-protected'),
        'description' => esc_html__('Password Protected form top text setup.', 'wp-edit-password-protected'),
        'panel'       => 'wppass_adminpage_panel',
        'priority'    => 160,
    ]
);
new \Kirki\Field\Select(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_page_fimg',
        'label'       => esc_html__('Page feature Image', 'wp-edit-password-protected'),
        'description' => esc_html__('The settings only work when the feature image is available.', 'wp-edit-password-protected'),
        'section'     => 'wpe_adpage_default',
        'default'     => $pp_page_featureimg,
        'choices'     => [
            'all' => __('Show for all visitor', 'wp-edit-password-protected'),
            'admin' => __('Show only login user', 'wp-edit-password-protected'),
            'hide' => __('Hide for all', 'wp-edit-password-protected')
        ],
    ]
);

new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_class',
        'label'   => __('Add page class. ', 'wp-edit-password-protected'),
        'description'    => __('You can add extra page class for add your won css style.', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_default',
        'transport'       => 'refresh',
        'default'         => $pp_page_class,
    ]
);
/**
 * member only page main setup
 *
 */
new \Kirki\Section(
    'wpe_adpage_main',
    [
        'title'       => esc_html__('Main Settings', 'wp-edit-password-protected'),
        'description' => esc_html__('Password Protected form top text setup.', 'wp-edit-password-protected'),
        'panel'       => 'wppass_adminpage_panel',
    ]
);
new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wpe_adpage_mode',
        'label'       => esc_html__('Settings Mode', 'wp-edit-password-protected'),
        'description' => esc_html__('Select login mode for login area setup or select main mode main page setup.', 'wp-edit-password-protected'),
        'section'     => 'wpe_adpage_main',
        'default'     => 'login',
        'choices'     => [
            'login' => __('Logout Mode', 'wp-edit-password-protected'),
            'main' => __('Login Mode', 'wp-edit-password-protected'),
        ],
    ]
);
new \Kirki\Field\Select(
    [
        'option_type' => 'option',
        'settings'    => 'wpe_adpage_style',
        'label'   => __('Login Page Style', 'wp-edit-password-protected'),
        'description'    => __('Select login page style.', 'wp-edit-password-protected'),
        'section'     => 'wpe_adpage_main',
        'default'     => 's1',
        'choices'     => [
            's1' => __('Style One', 'wp-edit-password-protected'),
            's2' => __('Style Two', 'wp-edit-password-protected'),
        ],
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
        ],
    ]
);
new \Kirki\Field\Select(
    [
        'option_type' => 'option',
        'settings'    => 'wpe_adpage_text_align',
        'label'   => __('Text Position', 'wp-edit-password-protected'),
        'section'     => 'wpe_adpage_main',
        'default'     => 'center',
        'choices'     => [
            'left' => __('Left', 'wp-edit-password-protected'),
            'center' => __('Center', 'wp-edit-password-protected'),
            'right' => __('Right', 'wp-edit-password-protected'),
        ],
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
        ],
    ]
);
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_infotitle',
        'label'           => esc_html__('Login Page Info Title', 'wp-edit-password-protected'),
        'description'    => __('The info title show when visitor not login.', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'transport'       => 'refresh',
        'default'     => $pp_massage_title,
        'placeholder' =>  __('The page only for login user', 'wp-edit-password-protected'),
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
        ],
    ]
);
new \Kirki\Field\Select(
    [
        'option_type' => 'option',
        'settings'    => 'wpe_adpage_titletag',
        'label'   => __('Title Tag', 'wp-edit-password-protected'),
        'description'    => __('Select title tag with your theme desing.', 'wp-edit-password-protected'),
        'section'     => 'wpe_adpage_main',
        'default'     => $pp_title_tag,
        'choices'     => [
            'h1' => __('h1', 'wp-edit-password-protected'),
            'h2' => __('h2', 'wp-edit-password-protected'),
            'h3' => __('h3', 'wp-edit-password-protected'),
            'h4' => __('h4', 'wp-edit-password-protected'),
        ],
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
        ],
    ]
);
new \Kirki\Field\Textarea(
    [
        'option_type' => 'option',
        'settings'    => 'wpe_adpage_text',
        'label'   => __('Login Page Description', 'wp-edit-password-protected'),
        'description'    => __('The description show when visitor not login.', 'wp-edit-password-protected'),
        'section'     => 'wpe_adpage_main',
        'default'         => $pp_massage_desc,
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
        ]
    ]
);
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_shortcode',
        'label'   => __('Shortcode', 'wp-edit-password-protected'),
        'description'    => __('You can use shortcode for display shotcode content. Exemple: login form shotcode', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'transport'       => 'refresh',
        'default'         => $pp_content_shortcode,
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
        ],
    ]
);
new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wpe_adpage_login_mode',
        'label'       => esc_html__('Login type', 'wp-edit-password-protected'),
        'description' => esc_html__('You can select show login form or login link', 'wp-edit-password-protected'),
        'section'     => 'wpe_adpage_main',
        'default'     => 'form',
        'choices'     => [
            'form' => __('Login Form', 'wp-edit-password-protected'),
            'link' => __('Login Link', 'wp-edit-password-protected'),
        ],
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
        ],
    ]
);
new \Kirki\Field\URL(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_login_url',
        'label'   => __('Login link url', 'wp-edit-password-protected'),
        'description'    => __('You can set default WordPress login link or use custom login link with http or https', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'default'         => $pp_login_link_url,
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
            [
                'setting'  => 'wpe_adpage_login_mode',
                'operator' => '==',
                'value'    => 'link',
            ],
        ],
    ]
);
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_btntext',
        'label'   => __('Login button text', 'wp-edit-password-protected'),
        'description'    => __('You can change login button text.', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'transport'       => 'refresh',
        'default'         => $pp_login_btn_text,
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
            [
                'setting'  => 'wpe_adpage_login_mode',
                'operator' => '==',
                'value'    => 'link',
            ],
        ],
    ]
);
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_btnclass',
        'label'   => __('Login button class', 'wp-edit-password-protected'),
        'description'    => __('You can add or edit login button class.', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'transport'       => 'refresh',
        'default'         => $pp_login_btn_class,
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
            [
                'setting'  => 'wpe_adpage_login_mode',
                'operator' => '==',
                'value'    => 'link',
            ],
        ],
    ]
);

// Login form settings 
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_form_head',
        'label'           => esc_html__('Login Form Header', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'transport'       => 'refresh',
        'default'         => esc_html__('Login Form', 'wp-edit-password-protected'),
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
            [
                'setting'  => 'wpe_adpage_login_mode',
                'operator' => '==',
                'value'    => 'form',
            ],
        ],
    ]
);
// Login form settings 
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_user_placeholder',
        'label'           => esc_html__('Form Username Placeholder', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'transport'       => 'refresh',
        'default'         => esc_html__('username', 'wp-edit-password-protected'),
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
            [
                'setting'  => 'wpe_adpage_login_mode',
                'operator' => '==',
                'value'    => 'form',
            ],
        ],
    ]
);
// Login form settings 
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_password_placeholder',
        'label'           => esc_html__('Form Password Placeholder', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'transport'       => 'refresh',
        'default'         => esc_html__('Password', 'wp-edit-password-protected'),
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
            [
                'setting'  => 'wpe_adpage_login_mode',
                'operator' => '==',
                'value'    => 'form',
            ],
        ],
    ]
);
new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wpe_adpage_form_remember',
        'label'       => esc_html__('Remember me ', 'wp-edit-password-protected'),
        'description' => esc_html__('You can show or hide remember me checkbox', 'wp-edit-password-protected'),
        'section'     => 'wpe_adpage_main',
        'default'     => 'on',
        'choices'     => [
            'on' => __('show', 'wp-edit-password-protected'),
            'off' =>  __('Hide', 'wp-edit-password-protected'),
        ],
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
            [
                'setting'  => 'wpe_adpage_login_mode',
                'operator' => '==',
                'value'    => 'form',
            ],
        ],
    ]
);

new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_remember_text',
        'label'           => esc_html__('Form Password Placeholder', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'transport'       => 'refresh',
        'default'         => esc_html__('Remember Me', 'wp-edit-password-protected'),
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
            [
                'setting'  => 'wpe_adpage_login_mode',
                'operator' => '==',
                'value'    => 'form',
            ],
            [
                'setting'  => 'wpe_adpage_form_remember',
                'operator' => '==',
                'value'    => 'on',
            ],
        ],
    ]
);
// Login form settings 
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_formbtn_text',
        'label'           => esc_html__('Form Submit Button Text', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'transport'       => 'refresh',
        'default'         => esc_html__('Login', 'wp-edit-password-protected'),
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
            [
                'setting'  => 'wpe_adpage_login_mode',
                'operator' => '==',
                'value'    => 'form',
            ],
        ],
    ]
);
// Login form settings 
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_wrongpassword',
        'label'           => esc_html__('Wrong Password Error Text', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'transport'       => 'refresh',
        'default'         => esc_html__('The password you entered is incorrect, Please try again.', 'wp-edit-password-protected'),
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
            [
                'setting'  => 'wpe_adpage_login_mode',
                'operator' => '==',
                'value'    => 'form',
            ],
        ],
    ]
);
// Login form settings 
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wpe_adpage_errorlogin',
        'label'           => esc_html__('Wrong Username And Password Error Text', 'wp-edit-password-protected'),
        'section'         => 'wpe_adpage_main',
        'transport'       => 'refresh',
        'default'         => esc_html__('Please enter both username and password.', 'wp-edit-password-protected'),
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'login',
            ],
            [
                'setting'  => 'wpe_adpage_login_mode',
                'operator' => '==',
                'value'    => 'form',
            ],
        ],
    ]
);
//main page settings
new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wpe_adpage_width',
        'label'   => __('Page Width', 'wp-edit-password-protected'),
        'description'    => __('You may set page standard container or full width.', 'wp-edit-password-protected'),
        'section'     => 'wpe_adpage_main',
        'default'     => 'standard',
        'choices'     => [
            'standard' => __('Container', 'wp-edit-password-protected'),
            'full' => __('Full Width', 'wp-edit-password-protected'),
        ],
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'main',
            ],
        ],
    ]
);
new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wpe_adpage_header_show',
        'label' => __('Show Page Header', 'ephemeris'),
        'section'     => 'wpe_adpage_main',
        'default'     => wppass_adminpage_option_default($pp_page_head),
        'choices'     => [
            'on' => __('show', 'wp-edit-password-protected'),
            'off' =>  __('Hide', 'wp-edit-password-protected'),
        ],
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'main',
            ],
        ],
    ]
);
new \Kirki\Field\Checkbox_Switch(
    [
        'option_type' => 'option',
        'settings'    => 'wpe_adpage_comment',
        'label' => __('Show comment', 'wp-edit-password-protected'),
        'description' => __('Comment only show when a comment is available and enabled comment option.', 'wp-edit-password-protected'),
        'section'     => 'wpe_adpage_main',
        'transport'   => 'refresh',
        'default'     => '',
        'active_callback' => [
            [
                'setting'  => 'wpe_adpage_mode',
                'operator' => '==',
                'value'    => 'main',
            ],
        ],
    ]
);
