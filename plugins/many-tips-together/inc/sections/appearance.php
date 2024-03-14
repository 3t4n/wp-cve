<?php
/**
 * Section Appearance config
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;

\Redux::set_section(
	$adtw_option,
	array(
		'title' => esc_html__( 'Appearance', 'mtt' ),
		'id'    => 'appearance',
        'icon' => 'el el-magic',
		#'desc'             => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/checkbox.html" target="_blank">https://devs.redux.io/core-fields/checkbox.html</a>',
        'subsection'       => false,
		'fields'           => array(
            array( ####### APPEARANCE
				'id'       => 'appearance-1',
				'type'     => 'section',
				'title'    => false,
				'indent'   => false, 
			),

			array( # Hide Help tabs
				'id'       => 'appearance_hide_help_tab',
				'type'     => 'switch',
				'title'    => esc_html__('Hide Help tabs', 'mtt'),
				#'desc' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__('Located at top right of the screen.','mtt'),
				'default'  => false
			),
			array( # Hide Screen Options tab
				'id'       => 'appearance_hide_screen_options_tab',
				'type'     => 'switch',
				'title'    => esc_html__('Hide Screen Options tabs', 'mtt'),
				'desc'     => esc_html__('Located at top right of the screen.','mtt'),
				'default'  => false
			),
			array( # Hide Help texts
				'id'       => 'appearance_help_texts_enable',
				'type'     => 'switch',
				'title'    => esc_html__( 'Expert Mode: Disable WordPress help texts', 'mtt' ),
				'desc'     => sprintf(
                    esc_html__( 'No explanations about custom fields, categories description, etc. CSS file copied from %s, by Scott Reilly. There this is set in a user basis, here in a role basis.', 'mtt' ),
                    '<a href="http://wordpress.org/extend/plugins/admin-expert-mode/" target="_blank">Admin Expert Mode</a>'
                ),
				'default'  => false
			),
			array( ## Hide Help roles
				'id'       => 'appearance_help_texts_roles',
				'type'     => 'select',
				'data'     => 'roles',
                'multi'    => true,
				'title'    => esc_html__( 'Hide the help texts from this roles.', 'mtt' ),
				'required' => array( 'appearance_help_texts_enable', '=', true ),
			),

            array( ####### HEADER / FOOTER
				'id'       => 'appearance-2',
				'type'     => 'section',
				'title'    => esc_html__( 'HEADER AND FOOTER', 'mtt' ),
				'indent'   => false, 
			),
			array( # Settings Notices enable
				'id'       => 'admin_notice_header_settings_enable',
				'type'     => 'switch',
				'title'    => esc_html__( 'Header: notice in the Settings sections', 'mtt' ),
				'desc'     => sprintf(
                    '%s <em>"%s"</em>',
                    esc_html__( 'Useful for displaying a notice for clients, like:', 'mtt' ),
                    esc_html__( 'Change this settings at your own risk...', 'mtt' ),
                ),
				'default'  => false
			),
			array( ## Settings Notices text
				'id'       => 'admin_notice_header_settings_text',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Message to display', 'mtt' ),
				'required' => array( 'admin_notice_header_settings_enable', '=', true ),
			),
			array( # All Pages Notices
				'id'       => 'admin_notice_header_allpages_enable',
				'type'     => 'switch',
				'title'    => esc_html__( 'Header: notice in all admin pages', 'mtt' ),
				'desc' => esc_html__( 'Useful for displaying a message to all users of the site.', 'mtt' ),
				'default'  => false
			),
			array( ## All Pages texts
				'id'       => 'admin_notice_header_allpages_text',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Message to display', 'mtt' ),
				'required' => array( 'admin_notice_header_allpages_enable', '=', true ),
			),
			array( ## All Pages roles
				'id'       => 'admin_notice_header_allpages_roles',
				'type'     => 'select',
				'data'     => 'roles',
                'multi'    => true,
				'title'    => esc_html__( 'Select roles to display the notice, leave empty to show to all roles.', 'mtt' ),
				'required' => array( 'admin_notice_header_allpages_enable', '=', true ),
			),
			array( # Footer Hide
				'id'       => 'admin_notice_footer_hide',
				'type'     => 'switch',
				'title'    => esc_html__( 'Footer: hide', 'mtt' ),
				'default'  => false
			),
			array( # Footer Message enable
				'id'       => 'admin_notice_footer_message_enable',
				'type'     => 'switch',
				'title'    => esc_html__( 'Footer: show only your message', 'mtt' ),
                'desc' => esc_html__( 'Remove all WordPress and other plugins messages, so your message is the only one there...', 'mtt' ),
				'default'  => false,
				'required' => array( 'admin_notice_footer_hide', '=', false ),
			),
			array( ## Footer Message left
				'id'       => 'admin_notice_footer_message_left',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Text to display on the left (html enabled)', 'mtt' ),
				'required' => array( 'admin_notice_footer_message_enable', '=', true ),
			),
			array( ## Footer Message right
				'id'       => 'admin_notice_footer_message_right',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Text to display on the right (html enabled)', 'mtt' ),
				'required' => array( 'admin_notice_footer_message_enable', '=', true ),
			),

            array( ####### CSS
				'id'       => 'appearance-3',
				'type'     => 'section',
				'title'    => esc_html__( 'CSS', 'mtt' ),
				'indent'   => false, 
			),
			array( # Code
				'id'       => 'admin_global_css',
				'type'     => 'ace_editor',
                'title' => esc_html__('Extra CSS', 'mtt'),
                'subtitle' => esc_html__('Styles applied in all Admin pages.', 'mtt')
                    . '<br>'
                    . esc_html__('Style tag not needed ', 'mtt')
                    . '(<code>&lt;style&gt;</code>)',
				'mode'     => 'css',
				'theme'    => 'monokai',
				'default'  => '',
                'options'  => array(
                    'minLines' => 12, 
                    'maxLines' => 40,
                    'fontSize' => 22
                )
			),
		),
	)
);
