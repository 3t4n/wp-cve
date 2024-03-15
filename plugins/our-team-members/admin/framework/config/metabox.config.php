<?php
if (! defined ( 'ABSPATH' )) {
	die ();
} // Cannot access pages directly.




/**
 * METABOX OPTIONS
 */

global $wpb_otm_metabox;
$wpb_otm_metabox = array ();




/**
 * Add Meta Box For Team Member
 */

$wpb_otm_metabox [] = array (
	'id' 			=> '_wpb_team_members_options',
	'title' 		=> esc_html__( 'Team member options', 'our-team-members' ),
	'post_type' 	=> 'wpb_team_members',
	'context' 		=> 'normal',
	'priority' 		=> 'default',
	'sections' 		=> array (			
		array (
			'name' 		=> '_wpb_team_members_options_fields',
			'fields' 	=> array (
				array (
					'id' 	          => 'designation',
					'type' 	          => 'text',
					'title'           => esc_html__( 'Designation', 'our-team-members' ),
				),
				array (
					'id' 	          => 'location',
					'type' 	          => 'text',
					'title'           => esc_html__( 'Location', 'our-team-members' ),
				),
			    array(
					'id'              => 'social_icons',
					'type'            => 'group',
					'title'           => esc_html__('Social Icons', 'our-team-members'),
					'button_title'    => esc_html__('Add New', 'our-team-members'),
					'accordion_title' => esc_html__('Add New Social Network', 'our-team-members'),
					'fields'          => array(
				        array(
							'id'      => 'icon',
							'type'    => 'icon',
							'title'   => esc_html__('Select an Icon', 'our-team-members')
				        ),
				        array(
							'id'      => 'url',
							'type'    => 'text',
							'title'   => esc_html__('Social Network URL', 'our-team-members')
				        ),
			      	),
			      	'default'     	  => array(
				        array(
				          'icon'      => 'fa fa-twitter',
				          'url'       => '#'
				        ),
				        array(
				          'icon'      => 'fa fa-facebook',
				          'url'       => '#'
				        ),
				        array(
				          'icon'      => 'fa fa-linkedin',
				          'url'       => '#'
				        )       
			      	)
			    ),
			    array(
					'id'              => 'skills',
					'type'            => 'group',
					'title'           => esc_html__('Skills', 'our-team-members'),
					'button_title'    => esc_html__('Add New', 'our-team-members'),
					'accordion_title' => esc_html__('Add New Skill', 'our-team-members'),
					'fields'          => array(
				        array(
							'id'      => 'skill',
							'type'    => 'text',
							'title'   => esc_html__('Skill', 'our-team-members')
				        ),
				        array(
							'id'      => 'skill_value',
							'type'    => 'number',
							'title'   => esc_html__('Social Network skill Value', 'our-team-members')
				        ),
			      	),
			      	'default'     	  => array(
				        array(
							'skill'       => 'PHP',
							'skill_value' => '80'
				        )      
			      	)
			    ),				
			) 
		)	 
	) 
);

/**
 * Add Meta Box to show ShortCode 
 */

$wpb_otm_metabox [] = array (
	'id' 			=> '_wpb_otm_doc_help',
	'title' 		=> esc_html__( 'Documentation & Support', 'our-team-members' ),
	'post_type' 	=> 'wpb_team_members',
	'context' 		=> 'normal',
	'priority' 		=> 'default',
	'sections' 		=> array (			
		array (
			'name' 		=> '_wpb_otm_doc_help_field',
			'fields' 	=> array (
				array(
				  'type'    => 'content',
				  'content' => esc_html__( 'For details documentation, Go to our documentation site and follow step by step instructions. For any kind of technical issues, Contact us through our support forum.', 'our-team-members' ) . '<br><br><a class="button" href="http://docs.wpbean.com/docs/wpb-our-team-member-free-version/installing/" target="_blank">Documentation</a>  <a class="button" href="https://wpbean.com/support/" target="_blank">Support Forum</a>',
				),
			) 
		)	 
	) 
);



/**
 * Add Meta Box For Team Member ShortCode Generator
 */
$wpb_otm_metabox [] = array (
	'id' 			=> '_wpb_team_members_shortcode',
	'title' 		=> esc_html__( 'Team member plugin shortcode', 'our-team-members' ),
	'post_type' 	=> 'wpb_otm_shortcode',
	'context' 		=> 'normal',
	'priority' 		=> 'default',
	'sections' 		=> array (			
		array (
			'name' 		=> '_wpb_team_members_shortcode_fields',
			'fields' 	=> array (

				array(
					'title'   		=> esc_html__('Skin', 'our-team-members'),
					'id' 			=> 'skin',	
					'type' 			=> 'select',  
					'options' 		=> array(    
						'default' 	=> esc_html__( 'Default', 'our-team-members' ),
						'one' 		=> esc_html__( 'One', 'our-team-members' ),
						'two' 		=> esc_html__( 'Two', 'our-team-members' ),
						'three' 	=> esc_html__( 'Three', 'our-team-members' ),
						'four' 		=> esc_html__( 'Four', 'our-team-members' ),
					),
					'default' 		=> 'default',
					'desc'			=> esc_html__('Select a skin. Each different skin allows you to show the team members in different style. ', 'our-team-members'),
				),
            	array(
                    'title' 		=> esc_html__('Number of team member to show', 'our-team-members'),
                    'id' 			=> 'number_of_member',
                    'type' 			=> 'number',
                    'default'       => '4',
                    'desc' 			=> esc_html__('Default 4.', 'our-team-members')
                ),
            	array(
					'title'   		=> esc_html__('Columns', 'our-team-members'),
					'id' 			=> 'column',	
					'type' 			=> 'select',  
					'options' 		=> array(    
						'6' 		=> esc_html__( '6 Columns', 'our-team-members' ),
						'4' 		=> esc_html__( '4 Columns', 'our-team-members' ),
						'3' 		=> esc_html__( '3 Columns', 'our-team-members' ),
						'2' 		=> esc_html__( '2 Columns', 'our-team-members' ),
						'1' 		=> esc_html__( '1 Columns', 'our-team-members' ),
					),
					'default' 		=> '4', 
					'desc'			=> esc_html__('Default 4 columns.', 'our-team-members'),
				),		
            	array(
					'title'   		=> esc_html__('Order', 'our-team-members'),
					'id' 			=> 'order',	
					'type' 			=> 'select',  
					'options' 		=> array(    
						'DESC'		=> esc_html__( 'Descending', 'our-team-members' ),
						'ASC' 		=> esc_html__( 'Ascending', 'our-team-members' )
					),
					'default' 		=> 'DESC', 
					'desc'			=> esc_html__('Default descending.', 'our-team-members'),
				),
            	array(
					'title'   		=> esc_html__('Order By', 'our-team-members'),
					'id' 			=> 'orderby',	
					'type' 			=> 'select',  
					'options' 		=> array(    
						'date'      		=> esc_html__( 'Date', 'xt-hope-cpt-shortcode' ),
						'menu_order'		=> esc_html__( 'Menu Order', 'xt-hope-cpt-shortcode' ),
						'title'     		=> esc_html__( 'Title', 'xt-hope-cpt-shortcode' ),
						'wpb_otm_last_word' => esc_html__( 'Title Last Word', 'xt-hope-cpt-shortcode' ),
						'ID'        		=> esc_html__( 'ID', 'xt-hope-cpt-shortcode' ),
						'modified'  		=> esc_html__( 'Last modified', 'xt-hope-cpt-shortcode' ) ,
						'rand'      		=> esc_html__( 'Random', 'xt-hope-cpt-shortcode' )
					),
					'default' 		=> 'date', 
					'desc'			=> esc_html__('Default date.', 'our-team-members'),
				),
				array(
					'title'  		=> esc_html__('Specific Category', 'our-team-members'),
					'id' 		   	=> 'member_categories',
					'type'        	=> 'text',
					'desc'  		=> esc_html__( 'You can put comma separated team member\'s category id for showing those specific members only.', 'our-team-members' ),
				),
				array(
                    'title'     	=> esc_html__( 'Extra class', 'our-team-members' ),
					'id'      		=> 'x_class',
                    'type'        	=> 'text',
                    'desc'  		=> esc_html__( 'Extra CSS class.', 'our-team-members' ),
                ),

			) 
		)	 
	) 
);



/**
 * Add Meta Box to show ShortCode 
 */

$wpb_otm_metabox [] = array (
	'id' 			=> '_wpb_show_team_members_shortcode',
	'title' 		=> esc_html__( 'Shortcode to use', 'our-team-members' ),
	'post_type' 	=> 'wpb_otm_shortcode',
	'context' 		=> 'normal',
	'priority' 		=> 'high',
	'sections' 		=> array (			
		array (
			'name' 		=> '_wpb_team_members_show_shortcode_field',
			'fields' 	=> array (
            	array(
                    'title' 		=> esc_html__('The Shortcode', 'our-team-members'),
                    'id' 			=> 'wpb_otm_shortcode',
                    'type' 			=> 'shortcode',
                    'desc' 			=> esc_html__('Use this shortcode to your page to show the team members.', 'our-team-members'),
                    'class'			=> 'widefat wpb-otm-the-shortcode',
                    'attributes'    => array(
					    'readonly'      => 'readonly',
					),
                ),
                array(
				  'type'    => 'heading',
				  'content' => esc_html__( 'Still, confused how to use this ShortCode and show the team members?', 'our-team-members' ),
				),
				array(
				  'type'    => 'content',
				  'content' => esc_html__( 'Go to our documentation site and follow step by step instructions. or Contact us through our support forum.', 'our-team-members' ) . '<br><br><a class="button" href="http://docs.wpbean.com/docs/wpb-our-team-member-free-version/installing/" target="_blank">Documentation</a>  <a class="button" href="https://wpbean.com/support/" target="_blank">Support Forum</a>',
				),
			) 
		)	 
	) 
);
