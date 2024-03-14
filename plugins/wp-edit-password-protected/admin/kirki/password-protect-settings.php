<?php

/**
 *
 * @package wp Edit Password Protected 
 * @category Password Protected page options
 * @author Noor Alam
 * @copyright Copyright (c) 2019, Ari Stathopoulos (@aristath)
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

// old Options 
// Default value 
$wp_edit_pass_option = get_option('pp_basic_settings');
$options = (!empty($wp_edit_pass_option)) ? $wp_edit_pass_option : '';
$text_select = (isset($options['text_select'])) ? $options['text_select'] : array('top' => 'top');

$form_top_text = (isset($options['form_top_text'])) ? $options['form_top_text'] : 'For more public resources check out our followed link.';
$form_bottom_text = (isset($options['form_bottom_text'])) ? $options['form_bottom_text'] : '';
$form_select_style = (isset($options['form_select'])) ? $options['form_select'] : 'four';
$social_select = (isset($options['social_select'])) ? $options['social_select'] : 'middle';
$social_position = (isset($options['social_position'])) ? $options['social_position'] : 'right';
$icon_style = (isset($options['icon_style'])) ? $options['icon_style'] : 'square';

$facebook_url = (isset($options['facebook_url'])) ? $options['facebook_url'] : 'https://facebook.com';
$twitter_url = (isset($options['twitter_url'])) ? $options['twitter_url'] : 'https://twitter.com';
$tumblr_url = (isset($options['tumblr_url'])) ? $options['tumblr_url'] : '';
$linkedin_url = (isset($options['linkedin_url'])) ? $options['linkedin_url'] : '';
$pinterest_url = (isset($options['pinterest_url'])) ? $options['pinterest_url'] : '';
$instagram_url = (isset($options['instagram_url'])) ? $options['instagram_url'] : '';
$youtube_url = (isset($options['youtube_url'])) ? $options['youtube_url'] : '';
$custom_url = (isset($options['custom_url'])) ? $options['custom_url'] : '';
$submit_btn_text = (isset($options['wp_btn_text'])) ? $options['wp_btn_text'] : __('Submit', 'wp-edit-password-protected');

//text position 
if (in_array("top", $text_select)) {
    $show_text_top = true;
} else {
    $show_text_top = false;
}
if (in_array("bottom", $text_select)) {
    $show_text_bottom = 1;
} else {
    $show_text_bottom = '';
}
//social icons show 
if (empty($social_select)) {
    $show_social_share = '';
} else {
    $show_social_share = 'top';
}




Kirki::add_config(
    'wppass_protected_config',
    [
        'option_type' => 'option',
        'capability'  => 'manage_options',
    ]
);

/**
 * Add a panel.
 *
 */
new \Kirki\Panel(
    'wppass_protected_panel',
    [
        'priority'    => 10,
        'title'       => esc_html__('Wp Edit Password Protected', 'wp-edit-password-protected'),
        'description' => esc_html__('All settins for wp password protected plguin.', 'wp-edit-password-protected'),
    ]
);

/**
 * Style setup
 *
 */
new \Kirki\Section(
    'wppass_protected_style',
    [
        'title'       => esc_html__('Premade Style', 'wp-edit-password-protected'),
        'description' => esc_html__('There are four style. Please select one style', 'wp-edit-password-protected'),
        'panel'       => 'wppass_protected_panel',
        'priority'    => 160,
    ]
);

new \Kirki\Field\Select(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_form_style',
        'label'       => esc_html__('Select Form Style', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_style',
        'default'     => 'four',
        'choices'     => [
            'one' => esc_html__('Style One', 'wp-edit-password-protected'),
            'two' => esc_html__('Style Two', 'wp-edit-password-protected'),
            'three' => esc_html__('Style Three', 'wp-edit-password-protected'),
            'four' => esc_html__('Style Four', 'wp-edit-password-protected'),
        ],
    ]
);
/**
 * Top text section Start
 *
 */
new \Kirki\Section(
    'wppass_protected_top_section',
    [
        'title'       => esc_html__('Top Text Setup', 'wp-edit-password-protected'),
        'description' => esc_html__('Password Protected form top text setup.', 'wp-edit-password-protected'),
        'panel'       => 'wppass_protected_panel',
        'priority'    => 160,
    ]
);

new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_show_top_text',
        'label'       => esc_html__('Top Text', 'wp-edit-password-protected'),
        'description' => esc_html__('You can show or hide top text section.', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_top_section',
        'default'     => 'on',
        'choices'     => [
            'on' => __('show', 'wp-edit-password-protected'),
            'off' =>  __('Hide', 'wp-edit-password-protected'),
        ],
    ]
);

new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_top_text_align',
        'label'       => esc_html__('Text Position', 'wp-edit-password-protected'),
        'description' => esc_html__('Set text position.', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_top_section',
        'default'     => 'center',
        'choices'     => [
            'left' => '<i class="dashicons dashicons-editor-alignleft"></i>',
            'center' => '<i class="dashicons dashicons-editor-aligncenter"></i>',
            'right' => '<i class="dashicons dashicons-editor-alignright"></i>',
        ],
    ]
);

new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_top_header',
        'label'           => esc_html__('Top Header', 'wp-edit-password-protected'),
        'description'     => esc_html__('Enter Password form top header text', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_top_section',
        'transport'       => 'refresh',
        'default'         => esc_html__('This content is password protected for members only', 'wp-edit-password-protected'),
    ]
);

new \Kirki\Field\Textarea(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_top_content',
        'label'       => esc_html__('Password Form top massage.', 'wp-edit-password-protected'),
        'description' => esc_html__('Display the text top of the form', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_top_section',
        'default'         => $form_top_text,
    ]
);


new \Kirki\Section(
    'wppass_protected_bottom_section',
    [
        'title'       => esc_html__('Bottom Text Setup', 'wp-edit-password-protected'),
        'description' => esc_html__('Password Protected form Bottom text setup.', 'wp-edit-password-protected'),
        'panel'       => 'wppass_protected_panel',
        'priority'    => 165,
    ]
);

new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_show_bottom_text',
        'label'       => esc_html__('Bottom Text', 'wp-edit-password-protected'),
        'description' => esc_html__('You can show or hide bottom text section.', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_bottom_section',
        'default'     => 'off',
        'choices'     => [
            'on' => __('show', 'wp-edit-password-protected'),
            'off' =>  __('Hide', 'wp-edit-password-protected'),
        ],
    ]
);

new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_bottom_text_align',
        'label'       => esc_html__('Text Position', 'wp-edit-password-protected'),
        'description' => esc_html__('Select Bottom text position.', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_bottom_section',
        'default'     => 'left',
        'choices'     => [
            'left' => '<i class="dashicons dashicons-editor-alignleft"></i>',
            'center' => '<i class="dashicons dashicons-editor-aligncenter"></i>',
            'right' => '<i class="dashicons dashicons-editor-alignright"></i>',
        ],
    ]
);

new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_bottom_header',
        'label'           => esc_html__('Bottom Header', 'wp-edit-password-protected'),
        'description'     => esc_html__('Enter Password form bottom header text', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_bottom_section',
        'transport'       => 'refresh',
        'default'         => esc_html__('This content is password protected for members only', 'wp-edit-password-protected'),
    ]
);

new \Kirki\Field\Textarea(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_bottom_content',
        'label'       => esc_html__('Password Form bottom massage.', 'wp-edit-password-protected'),
        'description' => esc_html__('Display the text bottom of the form', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_bottom_section',
        'default'         => esc_html__('This is our description. We are know about our issue', 'wp-edit-password-protected'),
    ]
);

new \Kirki\Section(
    'wppass_protected_form',
    [
        'title'       => esc_html__('Form Setup', 'wp-edit-password-protected'),
        'description' => esc_html__('Password Protected form setup.', 'wp-edit-password-protected'),
        'panel'       => 'wppass_protected_panel',
        'priority'    => 170,
    ]
);


new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_form_label',
        'label'           => esc_html__('Password Placeholder', 'wp-edit-password-protected'),
        'description'     => esc_html__('Edit or change your password protected label text', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_form',
        'transport'       => 'refresh',
        'default'         => esc_html__('Password', 'wp-edit-password-protected'),
    ]
);
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_form_btn_text',
        'label'           => esc_html__('Password Form Button Text', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_form',
        'transport'       => 'refresh',
        'default'         => esc_html__('Submit Now', 'wp-edit-password-protected'),
    ]
);
new \Kirki\Field\Text(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_form_errortext',
        'label'           => esc_html__('Enter Form Error Message', 'wp-edit-password-protected'),
        'description'           => esc_html__('The error message will show when the visitor inputs the wrong password.', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_form',
        'transport'       => 'refresh',
        'default'         => esc_html__('The password you have entered is invalid', 'wp-edit-password-protected'),
    ]
);
new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_error_text_position',
        'label'       => esc_html__('Error Text Position', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_form',
        'default'     => 'top',
        'choices'     => [
            'top' => __('Top', 'wp-edit-password-protected'),
            'bottom' =>  __('Bottom', 'wp-edit-password-protected'),
        ],
    ]
);
// social section
new \Kirki\Section(
    'wppass_protected_form_social',
    [
        'title'       => esc_html__('Social Setup', 'wp-edit-password-protected'),
        'description' => esc_html__('Password Protected social setup.', 'wp-edit-password-protected'),
        'panel'       => 'wppass_protected_panel',
        'priority'    => 175,
    ]
);
new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_show_social',
        'label'       => esc_html__('Social Icons', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_form_social',
        'default'     => 'on',
        'choices'     => [
            'on' => __('show', 'wp-edit-password-protected'),
            'off' =>  __('Hide', 'wp-edit-password-protected'),
        ],
    ]
);
new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_icons_vposition',
        'label'       => esc_html__('Social Icons Vertical Position', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_form_social',
        'default'     => 'top',
        'choices'     => [
            'top' => __('Top', 'wp-edit-password-protected'),
            'middle' => __('Middle', 'wp-edit-password-protected'),
            'bottom' =>  __('Bottom', 'wp-edit-password-protected'),
        ],
    ]
);
new \Kirki\Field\Radio_Buttonset(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_icons_alignment',
        'label'       => esc_html__('Social Icons Alignment', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_form_social',
        'default'     => 'center',
        'choices'     => [
            'left' => '<i class="dashicons dashicons-editor-alignleft"></i>',
            'center' => '<i class="dashicons dashicons-editor-aligncenter"></i>',
            'right' => '<i class="dashicons dashicons-editor-alignright"></i>',
        ],
    ]
);
new \Kirki\Field\Select(
    [
        'option_type' => 'option',
        'settings'    => 'wppasspro_icons_style',
        'label'       => esc_html__('Icons Style', 'wp-edit-password-protected'),
        'section'     => 'wppass_protected_form_social',
        'default'     => 'square',
        'choices'     => [
            'square' => __('Square icon', 'wp-edit-password-protected'),
            'circle' => __('Round icon', 'wp-edit-password-protected'),
            'quarter' => __('Semi round icon', 'wp-edit-password-protected')
        ],
    ]
);
new \Kirki\Field\URL(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_link_facebook',
        'label'           => esc_html__('Facebook url', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_form_social',
        'default'         => 'https://facebook.com',
    ]
);
new \Kirki\Field\URL(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_link_twitter',
        'label'           => esc_html__('Facebook url', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_form_social',
        'default'         => 'https://twitter.com',
    ]
);
new \Kirki\Field\URL(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_link_youtube',
        'label'           => esc_html__('Youtube url', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_form_social',
        'default'         => '',
    ]
);
new \Kirki\Field\URL(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_link_instagram',
        'label'           => esc_html__('Instagram url', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_form_social',
        'default'         => '',
    ]
);
new \Kirki\Field\URL(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_link_linkedin',
        'label'           => esc_html__('Linkedin url', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_form_social',
        'default'         => '',
    ]
);
new \Kirki\Field\URL(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_link_pinterest',
        'label'           => esc_html__('Pinterest url', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_form_social',
        'default'         => '',
    ]
);
new \Kirki\Field\URL(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_link_tumblr',
        'label'           => esc_html__('Tumblr url', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_form_social',
        'default'         => '',
    ]
);
new \Kirki\Field\URL(
    [
        'option_type' => 'option',
        'settings'        => 'wppasspro_link_custom',
        'label'           => esc_html__('Custom url', 'wp-edit-password-protected'),
        'section'         => 'wppass_protected_form_social',
        'default'         => '',
    ]
);
