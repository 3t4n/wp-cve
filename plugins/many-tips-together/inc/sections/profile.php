<?php
/**
 * Section Users config
 * 
 * @Users alias @Profile
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;

\Redux::set_section(
	$adtw_option,
	array(
		'title' => esc_html__( 'Users', 'mtt' ),
        'id'    => 'profile',
        'icon' => 'el el-group',
        'fields' => [
            
            array( ####### USERS
				'id'       => 'profile-1',
				'type'     => 'section',
				'title'    => false,
				'indent'   => false, 
			),
            array( # Filter Users
                'id'       => 'users_live_filter',
                'type'     => 'switch',
                'title' => esc_html__( 'Filter users by keyword', 'mtt' ),
                'default'  => false,
            ),
            array( # Filter Users
                'id'       => 'users_id_column',
                'type'     => 'switch',
                'title' => esc_html__( 'Add ID column', 'mtt' ),
                'default'  => false,
            ),
            
            array( ####### PROFILE
				'id'       => 'profile-2',
				'type'     => 'section',
				'title'    => esc_html__( 'PROFILE', 'mtt' ),
				'indent'   => false, 
			),
            array( # Hide Titles
                'id'       => 'profile_h2_titles',
                'type'     => 'switch',
                'title' => esc_html__( 'Hide Titles', 'mtt' ),
                'desc' => esc_html__( 'Hides the titles: "Personal Options", "Name", "Contact Info" and "About Yourself"', 'mtt' ),
                'default'  => false,
            ),
            array( # PERSONAL OPTIONS
				'id'       => 'profile_personal_options',
				'type'     => 'button_set',
				'title'    => esc_html__('Hide from Personal Options', 'mtt'),
				'multi'    => true,
				'options'  => [
                    'user-rich-editing-wrap' => esc_html('Visual Editor', 'mtt'),
                    'user-syntax-highlighting-wrap' => esc_html('Syntax Highlight', 'mtt'),
                    'user-admin-color-wrap' => esc_html('Admin Color', 'mtt'),
                    'user-comment-shortcuts-wrap' => esc_html('Keyboard Shortcuts', 'mtt'),
                    'user-admin-bar-front-wrap' => esc_html('Show Toolbar', 'mtt'),
                    'user-language-wrap' => esc_html('Language', 'mtt')
                ],
				'default'  => [],
			),
            array( # NAME
				'id'       => 'profile_name',
				'type'     => 'button_set',
				'title'    => esc_html__('Hide from Name', 'mtt'),
				'multi'    => true,
				'options'  => [
                    'user-user-login-wrap' => esc_html('Username', 'mtt'),
                    'user-first-name-wrap' => esc_html('First Name', 'mtt'),
                    'user-last-name-wrap' => esc_html('Last Name', 'mtt'),
                    'user-nickname-wrap' => esc_html('Nickname', 'mtt'),
                    'user-display-name-wrap' => esc_html('Display Name', 'mtt'),
                ],
				'default'  => [],
			),
            array( # CONTACT INFO
				'id'       => 'profile_contact_info',
				'type'     => 'button_set',
				'title'    => esc_html__('Hide from Contact Info', 'mtt'),
				'multi'    => true,
				'options'  => [
                    'user-email-wrap' => esc_html('Email', 'mtt'),
                    'user-url-wrap' => esc_html('Website', 'mtt'),
                ],
				'default'  => [],
			),
            array( # Add Social Profiles
                'id'       => 'profile_social',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Add social profile fields to users', 'mtt' ),
                'multi'    => true,
                'options' => ADTW()->getSocials(),
            ),
            array( # ABOUT YOURSELF
				'id'       => 'profile_about_yourself',
				'type'     => 'button_set',
				'title'    => esc_html__('Hide from About Yourself', 'mtt'),
				'multi'    => true,
				'options'  => [
                    'user-description-wrap' => esc_html('Bio', 'mtt'),
                    'user-profile-picture' => esc_html('Profile Picture', 'mtt'),
                ],
				'default'  => [],
			),
            array( # Aplication Passwords
                'id'       => 'profile_app_pw',
                'type'     => 'switch',
                'title' => esc_html__( 'Hide Application Password', 'mtt' ),
                'default'  => false,
            ),
			array( # CSS
				'id'       => 'profile_css',
				'type'     => 'ace_editor',
                'title' => esc_html__('Extra CSS', 'mtt'),
                'subtitle' => sprintf(
                    esc_html__('Style tag not needed %s', 'mtt'),
                    '(<code>&lt;style&gt;</code>)'
                ),
                'mode'     => 'css',
				'theme'    => 'monokai',
				'default'  => '',
                'options'  => array(
                    'minLines' => 12, 
                    'maxLines' => 40,
                    'fontSize' => 22
                )
            )
        ]
	)
);