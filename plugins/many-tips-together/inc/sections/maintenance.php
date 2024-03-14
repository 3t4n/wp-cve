<?php
/**
 * Section Maintenance Mode config
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;

\Redux::set_section(
	$adtw_option,
	array(
		'title' => esc_html__( 'Maintenance Mode', 'mtt' ),
        'id'    => 'maintenance',
        'icon' => 'el el-lock-alt',
        'fields' => [
            array( ####### ENABLE
                'id'       => 'maintenance_mode_enable',
                'type'     => 'switch',
                'title' => esc_html__( 'Enable maintenance mode', 'mtt' ),
                'desc'   => esc_html__( 'Block the site to visitors and the dashboard to non administrators.', 'mtt' ),
                'default'  => false,
                'hint'     => array(
					'title'   => '',
					'content' => ADTW()->renderHintImg('maintenance.jpg'),
				),
            ),
            array( # Only Backend
                'id'       => 'maintenance_mode_backend',
                'type'     => 'switch',
                'title'    => esc_html__( 'Only block backend access', 'mtt' ),
                'desc'     => esc_html__( 'Frontend will be visible to everyone', 'mtt' ),
                'default'  => false,
                'required' => array( 'maintenance_mode_enable', '=', true ),
            ),
			array( # Minimum Role
				'id'       => 'maintenance_mode_level',
				'type'     => 'select',
				'data'     => 'roles',
				'title'    => esc_html__( 'Minimum level for access', 'mtt' ),
                'desc'     => esc_html__( 'Select who can see the backend; leave empty to block everybody ', 'mtt' ),
				'required' => array( 'maintenance_mode_enable', '=', true ),
			),
            array( # Title
                'id'       => 'maintenance_mode_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Browser Title &lt;title&gt;', 'mtt' ),
                'required' => array( 'maintenance_mode_enable', '=', true ),
                'placeholder' => sprintf( __( '%s &#8212; WordPress' ), get_bloginfo( 'name' ) )
            ),
            array( # Line 0
                'id'       => 'maintenance_mode_line0',
                'type'     => 'text',
                'title'    => esc_html__( 'Text for the first line', 'mtt' ),
                'required' => array( 'maintenance_mode_enable', '=', true ),
                'placeholder' => esc_html__( 'Site in maintenance', 'mtt' )
            ),
            array( # Line 1
                'id'       => 'maintenance_mode_line1',
                'type'     => 'text',
                'title'    => esc_html__( 'Text for the second line', 'mtt' ),
                'required' => array( 'maintenance_mode_enable', '=', true ),
                'placeholder' => get_bloginfo( 'name' )
            ),
            array( # Line 2
                'id'       => 'maintenance_mode_line2',
                'type'     => 'text',
                'title'    => esc_html__( 'Link for the third line', 'mtt' ),
                'validate' => 'url',
                'required' => array( 'maintenance_mode_enable', '=', true ),
                'placeholder' => get_bloginfo( 'url' )
            ),
			array( # Page BG color
                'id'          => 'maintenance_mode_bg_color',
                'type'        => 'color',
                'title'       => esc_html__('Background color', 'mtt'),
                'transparent' => false,
                'color_alpha' => false,
                'required' => array( 'maintenance_mode_enable', '=', true ),
			),
			array( # Page BG Image
                'id'       => 'maintenance_mode_html_img',
				'type'     => 'media',
				'title'    => esc_html__( 'Page background image', 'mtt' ),
                'desc'     => esc_html__( 'Use a pattern or a big image', 'mtt' ),
                'url'      => false,
				'preview'  => true,
                'required' => array( 'maintenance_mode_enable', '=', true ),
			),
			array( # Box BG Image
                'id'       => 'maintenance_mode_body_img',
				'type'     => 'media',
				'title'    => esc_html__( 'Box background image', 'mtt' ),
                'desc'     => esc_html__( 'Use a pattern or a big image', 'mtt' ),
                'url'      => false,
				'preview'  => true,
                'required' => array( 'maintenance_mode_enable', '=', true ),
			),
			array( # CSS
                'id'       => 'maintenance_mode_extra_css',
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
                ),
                'required' => array( 'maintenance_mode_enable', '=', true ),
                )
        ]
    )
);












