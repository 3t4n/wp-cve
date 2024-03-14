<?php
/**
 * Section Dashboard config
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;

$url_wpse10 = ADTW_URL . '/assets/images/dashboard-widgets.jpg';

$extra_title = [];
$extra_content = [];
if (!is_multisite()) {
    $extra_title = [
        'id'       => 'dashboard-1',
        'type'     => 'section',
        'title'    => esc_html__( 'EXTRA WIDGETS', 'mtt' ),
        'indent'   => false, 
    ];
    $extra_content = [
        'id'       => 'dashboard_folder_size',
        'type'     => 'button_set',
        'title'    => esc_html__( 'List of directories and their sizes', 'mtt' ),
        'desc'    => esc_html__( 'only for admins', 'mtt' ),
        'multi'    => true,
        'options' => array(
            'root'    => esc_html__( 'Root folder', 'mtt' ),
            'content' => esc_html__( 'Folder wp-content', 'mtt' ),
        ),
        'hint'     => array(
            'title'   => '',
            'content' => "<br><div class='img-help'><img src='$url_wpse10' /></div>",
        )
    ];
}
\Redux::set_section(
	$adtw_option,
	array(
		'title'  => esc_html__( 'Dashboard', 'mtt' ),
		'id'     => 'dashboard',
        'icon'   => 'el el-dashboard',
        'fields' => [
            ####### REMOVE WIDGETS
            array(
				'id'       => 'dashboard-0',
				'type'     => 'section',
				'title'    => '',
				'indent'   => false, 
			),
            array(
                'id'       => 'dashboard_remove',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Remove widgets', 'mtt' ),
                'multi'    => true,
                'options' => array(
                    'quick_press'     => esc_html__( 'QuickPress', 'mtt' ),
                    'activity'        => esc_html__( 'Activity', 'mtt' ),
                    'right_now'       => esc_html__( 'At a Glance', 'mtt' ),
                    'primary'         => esc_html__( 'WordPress Blog', 'mtt' ),
                    'welcome'         => esc_html__( 'Welcome Panel', 'mtt' ),                
                    'site_health'     => esc_html__( 'Site Health', 'mtt' ),                
                ),
            ),
            
            ####### EXTRA WIDGETS
            $extra_title,
            $extra_content,

            ####### CREATE WIDGETS
            array(
				'id'       => 'dashboard-2',
				'type'     => 'section',
				'title'    => esc_html__( 'CUSTOM WIDGETS', 'mtt' ), 
				'indent'   => false, 
			),
            array(
                'id'       => 'dashboard_add_widgets',
                'type'     => 'switch',
                'title'    => esc_html__( 'Enable to create your widgets', 'mtt' ),
                'default'  => false
            ),
            array(
                'id'          => 'dashboard_custom_widgets',
				'type'        => 'repeater',
				'title'       => '',//__( 'ADD CUSTOM WIDGETS', 'mtt' ),
                'subtitle'    => sprintf(
                    '<small>%s<br>%s</small>',
                    esc_html__("Please save changes", 'mtt'),
                    esc_html__("after turning this option on", 'mtt'),
                ),
				'full_width'  => false,
				'item_name'   => 'Widget',
				'sortable'    => true,
				'active'      => false,
				'collapsible' => true,
                'required'    => array( 'dashboard_add_widgets', '=', true ),
				'fields'      => array(
					array(
						'id'          => 'dashboard_custom_widgets_title',
						'type'        => 'text',
						'placeholder' => esc_html__( 'Title', 'mtt' ),
					),
					array(
						'id'          => 'dashboard_custom_widgets_content',
						'type'        => 'text',
						'placeholder' => esc_html__( 'Content', 'mtt' ),
					),
                    array(
                        'id'       => 'dashboard_custom_widgets_roles',
                        'type'     => 'select',
                        'data'     => 'roles',
                        'multi'    => true,
                        'title'    => esc_html__('Show to roles','apc'), 
                        'subtitle' => esc_html__( 'Leave empty to show to all.', 'mtt' ),
                    ),
                    array(
                        'id'       => 'dashboard_custom_widgets_enable',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Enable this widget', 'mtt' ),
                        'default'  => false
                    ),
        
                ),
			),

        ]
	)
);