<?php
/**
 * Section Admin Menu config
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;



\Redux::set_section(
	$adtw_option,
	array(
		'title' => esc_html__( 'Admin Menu', 'mtt' ),
		'id'    => 'adminmenu',
        'icon' => 'el el-arrow-left',
        'fields' => [
            array( # Remove Items
                'id'       => 'admin_menus_remove',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Remove Menu Items', 'mtt' ),
                'subtitle'     => esc_html__( 'Select items to remove.', 'mtt' ),
                'desc' => sprintf(
                    esc_html__( 'This removes the menus for all users.%1$s It doesn\'t prevent access to the actual url address of the item.%1$s For a better fine tuning use %2$s. To really block the access use a plugin like %3$s or check this %4$s.', 'mtt' ),
                    '<br>',
                    '<a target="_blank" href="http://wordpress.org/plugins/adminimize/">Adminimize</a>',
                    '<a target="_blank" href="http://wordpress.org/plugins/members/">Members</a>',
                    '<a target="_blank" href="http://stackoverflow.com/q/23568456/1287812">Snippet</a>'
                ),
                'multi'    => true,
                'options' => array_values(ADTW()->getMenus()),
            ),
            array( # Remove Submenu Items
                'id'       => 'admin_submenus_remove',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Remove Submenu Items', 'mtt' ),
                'multi'    => true,
                'options' => ADTW()->getSubMenus(),
                'validate_callback' => [ADTW(), 'validate_submenus']
            ),
			array( # Rename Posts
				'id'    => 'posts_rename_enable',
				'type'  => 'switch',
				'title' => esc_html__( 'Rename Posts', 'mtt' ),
                'desc'  => esc_html__( 'Change the label of the default Post type instead of creating a new custom post type  called News or Blog.', 'mtt') 
                        . '<br>' 
                        . esc_html__('Tip via:', 'mtt' )
                        . '<a href="http://wordpress.stackexchange.com/a/9224/12615" target="_blank">WordPress Answers</a>',
				'default'  => false,
			),
            array( ## Rename posts to  
				'id'          => 'posts_rename_name',
				'type'        => 'text',
				'title'       => '',
				'desc'    => esc_html__('Rename "Post" to:', 'mtt'),
				'placeholder' => esc_html__('Enter new name', 'mtt'),
				/*'data'        => array( 
                    'name' => esc_html__('Name', 'mtt'),
                    'singular_name' => esc_html__('Singular Name', 'mtt'), 
                    'add_new' => esc_html__('Add New', 'mtt'), 
                    'edit_item' => esc_html__('Edit Posts', 'mtt'), 
                    'view_item' => esc_html__('View Posts', 'mtt'), 
                    'search_items' => esc_html__('Search Posts', 'mtt'), 
                    'not_found' => esc_html__('No Posts found', 'mtt'), 
                    'not_found_in_trash' => esc_html__('No Posts found in trash', 'mtt'), 
                ),*/
				'required' => array( 'posts_rename_enable', '=', true ),
			),
			array( # Status Bubbles
				'id'       => 'admin_menus_bubbles',
				'type'     => 'switch',
				'title'    => esc_html__( 'Post Status Bubbles', 'mtt' ),
                'desc' => sprintf(
                    esc_html__( 'Tip via: %s', 'mtt' ), 
                    '<a href="http://wordpress.stackexchange.com/a/95058/12615" target="_blank">WordPress Answers</a>'
                ),
				'default'  => false,
                'hint'     => array(
					'title'   => '',
					'content' => ADTW()->renderHintImg('adminmenus-bubbles.jpg', 'mini'),
				),
			),
            array( ## Bubbles cpts 
                'id'       => 'admin_menus_bubbles_cpts',
                'type'     => 'button_set',
                'title' => '', 
                'desc'=> 'Show bubles on the following post types',
                'multi'    => true,
                'options' => ADTW()->getCPTs(),
				'required' => array( 'admin_menus_bubbles', '=', true ),
            ),
            array( ## Bubbles status 
                'id'       => 'admin_menus_bubbles_status',
                'type'     => 'button_set',
                'title' => '', 
                'desc'=> 'Show bubles on the following status',
                'multi'    => false,
                'options' => ADTW()->getStatus(),
                'default' => 'none',
				'required' => array( 'admin_menus_bubbles', '=', true ),
            ),
			array( # Sort Settings
				'id'       => 'admin_menus_sort_wordpress',
				'type'     => 'switch',
				'title'    => esc_html__( 'Sort Settings', 'mtt' ),
				'desc'    => esc_html__( 'sort items in Settings menu [A-Z]', 'mtt' ),
				'default'  => false,
                'hint'     => array(
					'title'   => '',
					'content' => ADTW()->renderHintImg('adminmenus-sorting.jpg', 'small'),
				),
			),
			array( # Sort Settings by Plugins
				'id'       => 'admin_menus_sort_plugins',
				'type'     => 'switch',
				'title'    => esc_html__( 'Sort Settings by Plugins', 'mtt' ),
				'desc'    => esc_html__( 'Plugins submenu items and WordPress submenu items are shown separated, see screenshot above', 'mtt' ),
				'default'  => false
			),
        ]
	)
);