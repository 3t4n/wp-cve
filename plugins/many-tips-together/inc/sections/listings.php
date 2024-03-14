<?php
/**
 * Section Listings config
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;

\Redux::set_section(
	$adtw_option,
	array(
		'title' => esc_html__( 'Listings', 'mtt' ),
        'id'    => 'listings',
        'icon' => 'el el-th-list',
        'fields' => [
            
            array( ####### LISTINGS
				'id'       => 'listings-1',
				'type'     => 'section',
				'title'    => false,
				'indent'   => false, 
			),
            array( # Empty Trash
                'id'       => 'listings_empty_trash_button',
                'type'     => 'switch',
                'title'    => esc_html__( 'ALL TYPES: Empty Trash button', 'mtt' ),
                'desc'    => esc_html__( 'nowadays, we need 3 clicks to empty the trash,<br>this button will do it with 1 click', 'mtt' ),
                'default'  => false,
                'hint'     => array(
					'title'   => '',
					'content' => "<br>".ADTW()->renderHintImg('trash-button.png'),
                )
            ),
            array( # Duplicate posts / Remove revisions
                'id'       => 'listings_duplicate_del_revisions',
                'type'     => 'switch',
                'title'    => esc_html__( 'ALL TYPES: Duplicate Post and Delete Revisions', 'mtt' ),
                'default'  => false,
                'hint'     => array(
					'title'   => '',
					'content' => ADTW()->renderHintImg('postlisting-duplicate-revision.jpg'),
				),
            ),
            array( # Enable Category Count
                'id'       => 'listings_enable_category_count',
                'type'     => 'switch',
                'title'    => esc_html__( 'POSTS: Category count', 'mtt' ),
                'desc' => sprintf( 
                    esc_html__( 'Inspired by: %s', 'mtt' ), 
                    ADTW()->makeTipCredit( 
                        'Stack Overflow', 
                        'http://stackoverflow.com/a/15845723/1287812' 
                    ) 
                ),
                'default'  => false,
                'hint'     => array(
					'title'   => '',
					'content' => ADTW()->renderHintImg('postlisting-category-count.jpg'),
				),
            ),

            array( ####### ROW ACTIONS
				'id'       => 'listings-4',
				'type'     => 'section',
				'title'    => esc_html__( 'ROW ACTIONS IN POSTS, PAGES, MEDIA, PLUGINS, USERS', 'mtt' ) ,
				'indent'   => false, 
			),
            array( # Hide Row Actions
                'id'       => 'listings_remove_row_actions_everywhere',
                'type'     => 'switch',
                'title'    => esc_html__( 'EVERYWHERE: option to hide row actions', 'mtt' ),
                'desc' => esc_html__( 'Available on Screen Options of each page', 'mtt' ),
                'default'  => false,
                'hint'     => array(
					'title'   => '',
                    'tip_position'  => [
                        'at' => 'top left',
                        'my' => 'bottom right',
                    ],
					'content' => "<br>".ADTW()->renderHintImg('row-actions.jpg'),
                )
            ),
            
            
            array( ####### CUSTOM COLUMNS
				'id'       => 'listings-2',
				'type'     => 'section',
				'title'    => esc_html__( 'CUSTOM COLUMNS', 'mtt' ),
				'indent'   => false, 
			),
            array( # Taxonomies ID Column
                'id'       => 'wptaxonomy_columns',
                'type'     => 'switch',
                'title' => esc_html__( 'TAXONOMIES: ID column', 'mtt' ),
                'desc' => sprintf( 
                    esc_html__( 'Show column ID in taxonomies screens. Tip via: %s', 'mtt' ), 
                    ADTW()->makeTipCredit( 
                        'WordPress Answers', 
                        'http://wordpress.stackexchange.com/q/77532/12615' 
                    )
                ),
                'default'  => false
            ),
            array( # CPTs ID Columns
                'id'       => 'listings_enable_id_column',
                'type'     => 'switch',
                'title'    => esc_html__( 'ALL TYPES: ID column', 'mtt' ),
                'default'  => false,
            ),
			array( # Title Column Width
				'id'             => 'listings_title_column_width',
				'type'           => 'dimensions',
				'units'          => array( 'em', 'px', '%' ),
				'units_extended' => false,
                'height'   => false,
                'title'    => esc_html__( 'ALL TYPES: width of the Title column', 'mtt' ),
                'desc'     => esc_html__( 'Sometimes the Title column gets shrinked by other columns, you may change this here.', 'mtt' ),
            ),
            array( # Thumbnail Column
                'id'       => 'listings_enable_thumb_column',
                'type'     => 'switch',
                'title'    => esc_html__( 'ALL TYPES: Thumbnail column', 'mtt' ),
                'desc' => esc_html__( 'Shows the featured image or, if not set, the first attached.', 'mtt' ),
                'default'  => false,
            ),
			array( ## Thumbnail dimensions
                'id'       => 'listings_enable_thumb_proportion',
				'type'           => 'dimensions',
				'units'          => null,
				'units_extended' => false,
                'height'   => false,
				'title'    => esc_html__( 'Proportion of the thumbnails', 'mtt' ),
                'desc' => esc_html__( 'Used for width and height. The scale is proportional, this value is used for the bigger side.', 'mtt' ),
                'required' => array( 'listings_enable_thumb_column', '=', true ),
			),
			array( ## Thumbnail Column width
                'id'       => 'listings_enable_thumb_width',
				'type'           => 'dimensions',
				'units'          => array( 'em', 'px', '%' ),
				'units_extended' => false,
                'height'   => false,
				'title'    => esc_html__( 'Width of the column', 'mtt' ),
                'desc' => esc_html__( 'Depending on the proportion you may need this. Use px, em or %, i.e. 200px, 50%', 'mtt' ),
                'required' => array( 'listings_enable_thumb_column', '=', true ),
			),
			array( ## Thumbnails Attachments count
                'id'       => 'listings_enable_thumb_count',
				'type'     => 'switch',
				'title'    => esc_html__( 'Show total number of attachments', 'mtt' ),
                'desc'     => esc_html__( 'If greater than 1.', 'mtt' ),
                'default'  => false,
                'required' => array( 'listings_enable_thumb_column', '=', true ),
                'hint'     => array(
					'title'   => '',
					'content' => ADTW()->renderHintImg('postlisting-attach-column.jpg'),
				),
			),
            
            array( ####### COLORS
				'id'       => 'listings-3',
				'type'     => 'section',
				'title'    => esc_html__( 'COLORS FOR TYPES OF CONTENT', 'mtt' ) ,
				'indent'   => false, 
			),
			array( # Drafts color
				'id'          => 'listings_status_draft',
				'type'        => 'color',
				'title'       => esc_html__('POSTS-PAGES Draft color', 'mtt'),
				'transparent' => false,
				'color_alpha' => false,
			),
			array( # Pendings color
				'id'          => 'listings_status_pending',
				'type'        => 'color',
				'title'       => esc_html__('POSTS-PAGES Pending color', 'mtt'),
				'transparent' => false,
				'color_alpha' => false,
			),
			array( # Futures color
				'id'          => 'listings_status_future',
				'type'        => 'color',
				'title'       => esc_html__('POSTS-PAGES Future color', 'mtt'),
				'transparent' => false,
				'color_alpha' => false,
			),
			array( # Privates Color
				'id'          => 'listings_status_private',
				'type'        => 'color',
				'title'       => esc_html__('POSTS-PAGES Private color', 'mtt'),
				'transparent' => false,
				'color_alpha' => false,
			),
			array( # Passwordeds Color
				'id'          => 'listings_status_password',
				'type'        => 'color',
				'title'       => esc_html__('POSTS-PAGES Password Protected color', 'mtt'),
				'transparent' => false,
				'color_alpha' => false,
			),
			array( # Others Color
				'id'          => 'listings_status_others',
				'type'        => 'color',
				'title'       => esc_html__('POSTS-PAGES Other Author\'s color', 'mtt'),
				'transparent' => false,
				'color_alpha' => false,
			),
        ]
	)
);