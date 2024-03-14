<?php
/**
 * Creates options and outputs admin menu and options page
 */


// Include & setup custom metabox and fields
add_filter( 'ntg_settings_builder', 'gsc_admin_settings' );
function gsc_admin_settings( $admin_pages ) {
    
    $prefix = 'genesis_comment_form_args_'; // start with an underscore to hide fields from custom fields list
    
    
	$admin_pages[] = array(
            'settings' => array(
                'page_id'          => 'gsc-settings',
                'menu_ops'         => array(
                    'submenu' => array(
                        'parent_slug' => 'genesis',
                        'page_title'  => __('Simple Comments', 'gsc'),
                        'menu_title'  => __('Simple Comments', 'gsc'),
                        'capability'  => 'manage_options',
                    )
                ),
                'page_ops'         => array(
                    
                ),
                'settings_field'   => GSC_SETTINGS_FIELD,
                'default_settings' => array(
                        'title_wrap'                                            => '<h3>%s</h3>',
                        'genesis_title_comments'				=> __( 'Comments', 'genesis' ),
			'genesis_no_comments_text'				=> '',
			'genesis_comments_closed_text'				=> '',
			'genesis_title_pings'					=> __( 'Trackbacks', 'genesis' ),
			'genesis_no_pings_text'					=> '',

                            'genesis_comment_list_args_avatar_size'             => 48,

                        'comment_author_says_text'                              => __( 'says', 'genesis' ),
			'genesis_comment_awaiting_moderation'			=> __( 'Your comment is awaiting moderation.', 'genesis' ),

                            'genesis_comment_form_args_fields_aria_display'     => TRUE,
                            'genesis_comment_form_args_fields_author_display'    => TRUE,
                            'genesis_comment_form_args_fields_author_label'	=> __( 'Name', 'genesis' ),
                            'genesis_comment_form_args_fields_email_display'	=> TRUE,
                            'genesis_comment_form_args_fields_email_label'	=> __( 'Email', 'genesis' ),
                            'genesis_comment_form_args_fields_url_display'	=> TRUE,
                            'genesis_comment_form_args_fields_url_label'	=> __( 'Website', 'genesis' ),

                            'genesis_comment_form_args_title_reply'             => __( 'Speak Your Mind', 'genesis' ),
                            'genesis_comment_form_args_comment_notes_before'	=> '',
                            'genesis_comment_form_args_comment_notes_after'	=> '',
                            'genesis_comment_form_args_label_submit'            => __( 'Post Comment' )
                ),
                
            ),
            'sanatize' => array(
                'no_html'   => array(
                    'genesis_title_comments',
                    'genesis_title_pings',
                    'genesis_comment_list_args_avatar_size',
                    'genesis_comment_form_args_fields_aria_display',
                    'genesis_comment_form_args_fields_author_display',
                    'genesis_comment_form_args_fields_email_display',
                    'genesis_comment_form_args_fields_url_display',
                    'genesis_comment_form_args_label_submit'
                ),
                'safe_html' => array(
                    'title_wrap', 
                    'genesis_no_comments_text',
                    'genesis_comments_closed_text',
                    'genesis_no_pings_text',
                    'comment_author_says_text',
                    'genesis_comment_awaiting_moderation',
                    'genesis_comment_form_args_fields_author_label',
                    'genesis_comment_form_args_fields_email_label',
                    'genesis_comment_form_args_fields_url_label',
                    'genesis_comment_form_args_title_reply',
                    'genesis_comment_form_args_comment_notes_before',
                    'genesis_comment_form_args_comment_notes_after'
                )
            ),
            'help'       => array(
                
            ),
            'meta_boxes' => array(
                
                'id'         => 'gsc_settings',
                'title'      => 'Genesis Simple Comments Settings',
                'context'    => 'main',
                'priority'   => 'high',/**/
                'show_names' => true, // Show field names on the left
                'fields'     => array(
                    
                    array(
                        'name' => __( 'Default Settings', 'gsc' ),
                        'desc' => '',
                        'type' => 'title'
                    ),
                    array(
                        'name' => __("Title Wrap:", 'gsc'),
                        'desc' => __( 'This is the html tag used around the Comment Title and Pings Title.  Make sure you keep the <tag>%s</tag> format for the wrap to work correctly.', 'gsc' ),
                        'id'   => 'title_wrap',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __("Comment Title:", 'gsc'),
                        'desc' => '',
                        'id'   => 'genesis_title_comments',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __("No Comments Text:", 'gsc'),
                        'desc' => '',
                        'id'   => 'genesis_no_comments_text',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __("Comments Closed Text:", 'gsc'),
                        'desc' => '',
                        'id'   => 'genesis_comments_closed_text',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __("Pings Title:", 'gsc'),
                        'desc' => '',
                        'id'   => 'genesis_title_pings',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __("Avatar Size:", 'gsc'),
                        'desc' => '',
                        'id'   => 'genesis_comment_list_args_avatar_size',
                        'type' => 'text_small'
                    ),
                    array(
                        'name' => '',
                        'desc' => '',
                        'type' => 'title'
                    ),
                    array(
                        'name' => __("Author Says Text:", 'gsc'),
                        'desc' => '',
                        'id'   => 'comment_author_says_text',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __("Comment Awaiting Moderation Text:", 'gsc'),
                        'desc' => '',
                        'id'   => 'genesis_comment_awaiting_moderation',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __( 'Form Fields', 'gsc' ),
                        'desc' => '',
                        'type' => 'title'
                    ),
                    array(
                        'name' => __("Comment Awaiting Moderation Text:", 'gsc'),
                        'desc' => '',
                        'id'   => 'genesis_comment_awaiting_moderation',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __("Enable Aria Require True Attribute?", 'gsc'),
                        'desc' => __( 'This is enabled by default and adds an attribute to the required comment fields that adds a layout of accesibility for visually impaired site visitors.  This attribute is not technically valid XHTML but works in all browsers. Unless you need 100% valid markup at the expense of accesability, leave this option enabled.', 'gsc' ),
                        'id'   => $prefix . 'fields_aria_display',
                        'type' => 'checkbox'
                    ),
                    array(
                        'name' => __("Display Author Field?", 'gsc'),
                        'desc' => '',
                        'id'   => $prefix . 'fields_author_display',
                        'type' => 'checkbox'
                    ),
                    array(
                        'name' => __("Author Label:", 'gsc'),
                        'desc' => '',
                        'id'   => $prefix . 'fields_author_label',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __("Display Email Field?", 'gsc'),
                        'desc' => '',
                        'id'   => $prefix . 'fields_email_display',
                        'type' => 'checkbox'
                    ),
                    array(
                        'name' => __("Email Label:", 'gsc'),
                        'desc' => '',
                        'id'   => $prefix . 'fields_email_label',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __("Display URL Field?", 'gsc'),
                        'desc' => '',
                        'id'   => $prefix . 'fields_url_display',
                        'type' => 'checkbox'
                    ),
                    array(
                        'name' => __("URL Label:", 'gsc'),
                        'desc' => '',
                        'id'   => $prefix . 'fields_url_label',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __("Reply Label:", 'gsc'),
                        'desc' => '',
                        'id'   => $prefix . 'title_reply',
                        'type' => 'text'
                    ),
                    array(
                        'name' => __("Notes Before Comment:", 'gsc'),
                        'desc' => '',
                        'id'   => $prefix . 'comment_notes_before',
                        'type' => 'textarea'
                    ),
                    array(
                        'name' => __("Notes After Comment:", 'gsc'),
                        'desc' => '',
                        'id'   => $prefix . 'comment_notes_after',
                        'type' => 'textarea'
                    ),
                    array(
                        'name' => __("Submit Button Label:", 'gsc'),
                        'desc' => '',
                        'id'   => $prefix . 'label_submit',
                        'type' => 'text'
                    )
                ))
	);
	
	return $admin_pages;
}