<?php

class B2bkingcore_Admin{

	function __construct() {

		// How to use notices
		add_action( 'admin_notices', array($this, 'b2bking_groups_howto') );
		add_action( 'admin_notices', array($this, 'b2bking_quotefields_howto') );
		add_action( 'admin_notices', array($this, 'b2bking_conversations_howto') );
		add_action( 'admin_notices', array($this, 'b2bking_offers_howto') );
		add_action( 'admin_notices', array($this, 'b2bking_rules_howto') );
		add_action( 'admin_notices', array($this, 'b2bking_roles_howto') );
		add_action( 'admin_notices', array($this, 'b2bking_fields_howto') );
		add_action( 'admin_notices', array($this, 'b2bking_customers_howto') );

		// Require WooCommerce notification
		add_action( 'admin_notices', array($this, 'b2bking_plugin_dependencies') );
		// Load admin notice resources (enables notification dismissal)
		add_action( 'admin_enqueue_scripts', array($this, 'load_global_admin_notice_resource') ); 


		add_action( 'plugins_loaded', function(){
			if ( class_exists( 'woocommerce' ) && !class_exists('B2bking')) {

				function b2bking_limit_groups_number_text( $title ){
					global $post;
					if (get_post_type($post) === 'b2bking_group'){
						// get number of groups
						$groups = get_posts(array( 
							'post_type' => 'b2bking_group',
							'post_status'=>'publish',
							'numberposts' => -1,
							'fields' => 'ids',
						));
						if (count($groups) > 1){
							$title = esc_html__('GROUP CANNOT BE CREATED. Core version is limited to 2 groups.','b2bking');
						}
					}
				    return $title;
				}
				add_filter( 'enter_title_here', 'b2bking_limit_groups_number_text' );

				// first time plugin runs, update rules list
				if (get_option('b2bking_first_time_rules_database', 'yes') === 'yes'){
					$this->b2bking_calculate_rule_numbers_database();
					update_option('b2bking_first_time_rules_database', 'no');
				}

				/* Customer Groups */
				// Register new post type, Customer Groups: b2bking_group
				add_action( 'init', array($this, 'b2bking_register_post_type_customer_groups'), 0 );
				// Add metaboxes to groups
				add_action( 'add_meta_boxes', array($this, 'b2bking_groups_metaboxes') );
				// Save groups
				add_action('save_post', array($this, 'b2bking_save_groups_metaboxes'), 10, 1);
				// Add custom columns to groups admin menu
				add_filter( 'manage_b2bking_group_posts_columns', array($this, 'b2bking_add_columns_group_menu') );
				// Add groups custom columns data
				add_action( 'manage_b2bking_group_posts_custom_column' , array($this, 'b2bking_columns_group_data'), 10, 2 );

				/* Dynamic Rules */
				// Register new post type, Dynamic Rule: b2bking_rule
				add_action( 'init', array($this, 'b2bking_register_post_type_dynamic_rules'), 0 );
				// Add metaboxes to dynamic rules
				add_action( 'add_meta_boxes', array($this, 'b2bking_rules_metaboxes') );
				// Save metaboxes
				add_action('save_post', array($this, 'b2bking_save_rules_metaboxes'), 10, 1);
				// Add custom columns to dynamic rules in admin menu
				add_filter( 'manage_b2bking_rule_posts_columns', array($this, 'b2bking_add_columns_rule_menu') );
				// Add dynamic rules custom columns data
				add_action( 'manage_b2bking_rule_posts_custom_column' , array($this, 'b2bking_columns_rule_data'), 10, 2 );

				/* Registration Roles */
				// Register new post type, Custom Registration Roles: b2bking_custom_role
				add_action( 'init', array($this, 'b2bking_register_post_type_custom_role'), 0 );
				// Add metaboxes to custom roles
				add_action( 'add_meta_boxes', array($this, 'b2bking_custom_role_metaboxes') );
				// Save custom roles
				add_action('save_post', array($this, 'b2bking_save_custom_role_metaboxes'), 10, 1);
				// Add custom columns to groups admin menu
				add_filter( 'manage_b2bking_custom_role_posts_columns', array($this, 'b2bking_add_columns_custom_role_menu') );
				// Add groups custom columns data
				add_action( 'manage_b2bking_custom_role_posts_custom_column' , array($this, 'b2bking_columns_custom_role_data'), 10, 2 );
				

				/* Custom User Meta */
				// Show the new user meta in New User, User Profile and Edit
				add_action( 'user_new_form', array($this, 'b2bking_show_user_meta_profile') );
				add_action( 'show_user_profile', array($this, 'b2bking_show_user_meta_profile') );
				add_action( 'edit_user_profile', array($this, 'b2bking_show_user_meta_profile') );
				// Save the new user meta (Update or Create)
				add_action( 'personal_options_update', array($this, 'b2bking_save_user_meta_customer_group') );
				add_action( 'edit_user_profile_update', array($this, 'b2bking_save_user_meta_customer_group') );
				add_action( 'user_register', array($this, 'b2bking_save_user_meta_customer_group') );
				// Add columns to Users Table
				add_filter( 'manage_users_columns',  array($this, 'b2bking_add_columns_user_table') );
				// Retrieve group column content (user meta) in the Users Table
				add_filter( 'manage_users_custom_column', array($this, 'b2bking_retrieve_group_column_contents_users_table'), 10, 3 );

				/* Custom Category Meta (Category visibility: groups and users)	*/
				// Enable visibility settings in Add Category
				add_action( 'product_cat_add_form_fields', array($this, 'b2bking_enable_visibility_settings_add_category'), 10, 2 );
				// Enable visibility settings in Edit Category
				add_action('product_cat_edit_form_fields', array($this, 'b2bking_enable_visibility_settings_edit_category'), 10, 1);
				// Save category visibility meta settings
				add_action('edited_product_cat', array($this, 'b2bking_save_category_visibility_meta_settings'), 10, 1);
				add_action('create_product_cat', array($this, 'b2bking_save_category_visibility_meta_settings'), 10, 1);

				/* Custom Product Meta	*/
				// Add Visibility Metabox to Products
				add_action( 'add_meta_boxes', array($this, 'b2bking_product_visibility_metabox') );
				// Save Visibility Product Meta
				add_action('save_post', array($this, 'b2bking_product_visibility_meta_update'), 10, 1);

				/* Order Meta Custom Fields */
				// Add custom registration fields to billing order
				add_filter( 'woocommerce_order_get_formatted_billing_address', array($this, 'b2bking_admin_order_meta_billing'), 10, 3 );  	
				
				// Additional B2BKing Panel in Product Page
				add_filter( 'woocommerce_product_data_tabs', array( $this, 'b2bking_additional_panel_in_product_page' ) );
				add_action( 'woocommerce_product_data_panels', array( $this, 'b2bking_additional_panel_in_product_page_content' ) );
				// On post save, save product info panel
				add_action('save_post', array($this, 'b2bking_additional_panel_product_save'), 10, 1);

				/* Additional Product Data Tab for Fixed Price and Tiered Price */
				// simple
				add_action( 'woocommerce_product_options_pricing', array($this, 'additional_product_pricing_option_fields'), 99 );
				add_action( 'woocommerce_process_product_meta', array($this, 'b2bking_individual_product_pricing_data_save') );
				// variation
				add_action( 'woocommerce_variation_options_pricing', array($this, 'additional_variation_pricing_option_fields'), 99, 3 );
				add_action( 'woocommerce_save_product_variation', array($this, 'save_variation_settings_fields'), 10, 2 );

				// Add b2bking header bar in B2BKING post types
				add_action('in_admin_header', array($this,'b2bking_show_header_bar_b2bking_posts'));

				
				/* Load resources */
				// Load global admin styles
				add_action( 'admin_enqueue_scripts', array($this, 'load_global_admin_resources') ); 
				// Only load scripts and styles in this specific admin page
				add_action( 'admin_enqueue_scripts', array($this, 'load_admin_resources') );
				// Only load scripts and styles in Customers page
				add_action( 'admin_enqueue_scripts', array($this, 'load_customers_resources') );  
				// Only load scripts and styles in Dashboard page
			//	add_action( 'admin_enqueue_scripts', array($this, 'load_dashboard_resources') );  

				/* Settings */
				// Registers settings
				add_action( 'admin_init', array( $this, 'b2bking_settings_init' ) );
				// Renders settings 
				add_action( 'admin_menu', array( $this, 'b2bking_settings_page' ) ); 

			}
		});
	}


	// Register new post type: Custom Registration Role (b2bking_custom_role)
	function b2bking_register_post_type_custom_role() {
		// Build labels and arguments
	    $labels = array(
	        'name'                  => esc_html__( 'Custom Registration Role', 'b2bking' ),
	        'singular_name'         => esc_html__( 'Registration Role', 'b2bking' ),
	        'all_items'             => esc_html__( 'Registration Roles', 'b2bking' ),
	        'menu_name'             => esc_html__( 'Registration Roles', 'b2bking' ),
	        'add_new'               => esc_html__( 'Add New', 'b2bking' ),
	        'add_new_item'          => esc_html__( 'Add new registration role', 'b2bking' ),
	        'edit'                  => esc_html__( 'Edit', 'b2bking' ),
	        'edit_item'             => esc_html__( 'Edit role', 'b2bking' ),
	        'new_item'              => esc_html__( 'New role', 'b2bking' ),
	        'view_item'             => esc_html__( 'View role', 'b2bking' ),
	        'view_items'            => esc_html__( 'View roles', 'b2bking' ),
	        'search_items'          => esc_html__( 'Search roles', 'b2bking' ),
	        'not_found'             => esc_html__( 'No roles found', 'b2bking' ),
	        'not_found_in_trash'    => esc_html__( 'No roles found in trash', 'b2bking' ),
	        'parent'                => esc_html__( 'Parent role', 'b2bking' ),
	        'featured_image'        => esc_html__( 'Role image', 'b2bking' ),
	        'set_featured_image'    => esc_html__( 'Set role image', 'b2bking' ),
	        'remove_featured_image' => esc_html__( 'Remove role image', 'b2bking' ),
	        'use_featured_image'    => esc_html__( 'Use as role image', 'b2bking' ),
	        'insert_into_item'      => esc_html__( 'Insert into role', 'b2bking' ),
	        'uploaded_to_this_item' => esc_html__( 'Uploaded to this role', 'b2bking' ),
	        'filter_items_list'     => esc_html__( 'Filter roles', 'b2bking' ),
	        'items_list_navigation' => esc_html__( 'Roles navigation', 'b2bking' ),
	        'items_list'            => esc_html__( 'Roles list', 'b2bking' )
	    );
	    $args = array(
	        'label'                 => esc_html__( 'Custom Registration Role', 'b2bking' ),
	        'description'           => esc_html__( 'This is where you can create new custom registration roles', 'b2bking' ),
	        'labels'                => $labels,
	        'supports'              => array( 'title' ),
	        'hierarchical'          => false,
	        'public'                => false,
	        'show_ui'               => true,
	        'show_in_menu'          => 'b2bkingcore',
	        'menu_position'         => 125,
	        'show_in_admin_bar'     => true,
	        'show_in_nav_menus'     => false,
	        'can_export'            => true,
	        'has_archive'           => false,
	        'exclude_from_search'   => true,
	        'publicly_queryable'    => false,
	        'capability_type'       => 'product',
	        'map_meta_cap'          => true
	    );

	// Actually register the post type
	register_post_type( 'b2bking_custom_role', $args );
	}

	// Add Registration Role Metaboxes
	function b2bking_custom_role_metaboxes($post_type) {
	    $post_types = array('b2bking_custom_role');     //limit meta box to certain post types
       	if ( in_array( $post_type, $post_types ) ) {
       		add_meta_box(
       		    'b2bking_custom_role_settings_metabox'
       		    ,esc_html__( 'Registration Role Settings', 'b2bking' )
       		    ,array( $this, 'b2bking_custom_role_settings_metabox_content' )
       		    ,$post_type
       		    ,'advanced'
       		    ,'high'
       		);
	    }
	}


	function b2bking_custom_role_settings_metabox_content(){
		global $post;
		?>
		<div class="b2bking_custom_role_settings_metabox_container">
			<div class="b2bking_custom_role_settings_metabox_container_element">
				<div class="b2bking_custom_role_settings_metabox_container_element_title">
					<svg class="b2bking_custom_role_settings_metabox_container_element_title_icon" xmlns="http://www.w3.org/2000/svg" width="33" height="35" fill="none" viewBox="0 0 33 35">
					  <path fill="#C4C4C4" fill-rule="evenodd" d="M22.211 3.395v5.07c3.495 1.914 5.729 5.664 5.729 9.88 0 6.16-5.115 11.157-11.423 11.157-6.303 0-11.417-4.994-11.417-11.156 0-4.354 2.202-8.092 5.867-9.93V3.39C4.575 5.495.314 11.36.314 18.346c0 8.745 7.256 15.831 16.201 15.831 8.948 0 16.206-7.086 16.206-15.831a15.804 15.804 0 00-10.51-14.95z" clip-rule="evenodd"/>
					  <path fill="#C4C4C4" fill-rule="evenodd" d="M16.413 16.906c1.693 0 3.073-1.036 3.073-2.32V2.32c0-1.282-1.38-2.32-3.073-2.32-1.696 0-3.073 1.038-3.073 2.32v12.266c0 1.284 1.377 2.32 3.073 2.32z" clip-rule="evenodd"/>
					</svg>
					<?php esc_html_e('Status', 'b2bking'); ?>
				</div>
				<div class="b2bking_custom_role_settings_metabox_container_element_checkbox_container">
					<div class="b2bking_custom_role_settings_metabox_container_element_checkbox_name">
						<?php esc_html_e('Enabled','b2bking'); ?>
					</div>
					<input type="checkbox" value="1" class="b2bking_custom_role_settings_metabox_container_element_checkbox" name="b2bking_custom_role_settings_metabox_container_element_checkbox" <?php checked(1,intval(get_post_meta($post->ID,'b2bking_custom_role_status',true)),true); ?>>
				</div>
			</div>
			<div class="b2bking_custom_role_settings_metabox_container_element">
				<div class="b2bking_custom_role_settings_metabox_container_element_title">
					<svg class="b2bking_custom_role_settings_metabox_container_element_title_icon" xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="none" viewBox="0 0 37 37">
					  <path fill="#C4C4C4" d="M36.82 19.735l-2.181-3.464 1.293-3.886a1.183 1.183 0 00-.524-1.39L31.88 8.945l-.649-4.047a1.181 1.181 0 00-.379-.692 1.167 1.167 0 00-.727-.295l-4.07-.16L23.61.47a1.164 1.164 0 00-1.434-.355L18.5 1.875 14.822.113a1.163 1.163 0 00-1.435.357l-2.442 3.281-4.07.16c-.269.011-.526.115-.727.295-.202.18-.335.424-.378.691l-.65 4.048-3.528 2.048a1.183 1.183 0 00-.525 1.39L2.36 16.27.18 19.735a1.183 1.183 0 00.18 1.477l2.939 2.837-.33 4.085c-.022.27.049.54.202.763.153.224.377.387.636.463l3.912 1.134 1.592 3.774c.105.25.293.456.531.582.239.127.513.166.777.11l3.992-.823 3.15 2.597c.213.175.476.266.739.266s.524-.091.74-.266l3.15-2.597 3.99.824a1.163 1.163 0 001.309-.693l1.592-3.774 3.912-1.134c.259-.076.484-.24.637-.463.152-.223.224-.493.202-.763l-.33-4.085 2.938-2.837a1.18 1.18 0 00.18-1.477zm-9.882-5.653l-8.171 12.323c-.309.46-.787.768-1.262.768-.474 0-1.003-.268-1.34-.609l-6-6.136a1.09 1.09 0 010-1.517l1.48-1.518a1.043 1.043 0 011.142-.23c.127.054.242.132.34.23l3.903 3.992 6.438-9.71a1.045 1.045 0 01.669-.449 1.033 1.033 0 01.786.166l1.736 1.2a1.09 1.09 0 01.279 1.49z"/>
					</svg>
					<?php esc_html_e('Approval required', 'b2bking'); ?>
				</div>
				<select class="b2bking_custom_role_settings_metabox_container_element_select" name="b2bking_custom_role_settings_metabox_container_element_select">
					<option value="automatic" <?php selected('automatic', get_post_meta($post->ID,'b2bking_custom_role_approval',true), true); ?>><?php esc_html_e('No (automatic approval)', 'b2bking'); ?></option>
					<option value="manual" <?php selected('manual', get_post_meta($post->ID,'b2bking_custom_role_approval',true), true); ?>><?php esc_html_e('Yes (manual approval)', 'b2bking'); ?></option>
				</select>
			</div>
		</div>
		<div class="b2bking_custom_role_approval_sort_container">
			<div class="b2bking_custom_role_approval_sort_container_element">
				<div class="b2bking_custom_role_approval_sort_container_element_select_container">
					<div class="b2bking_user_settings_container_column_title_role">
						<svg class="b2bking_user_settings_container_column_title_icon_right" xmlns="http://www.w3.org/2000/svg" width="31" height="29" fill="none" viewBox="0 0 31 29">
						  <path fill="#C4C4C4" d="M15.5 6.792V.625H.083v27.75h30.834V6.792H15.5zm-9.25 18.5H3.167v-3.084H6.25v3.084zm0-6.167H3.167v-3.083H6.25v3.083zm0-6.167H3.167V9.875H6.25v3.083zm0-6.166H3.167V3.708H6.25v3.084zm6.167 18.5H9.333v-3.084h3.084v3.084zm0-6.167H9.333v-3.083h3.084v3.083zm0-6.167H9.333V9.875h3.084v3.083zm0-6.166H9.333V3.708h3.084v3.084zm15.416 18.5H15.5v-3.084h3.083v-3.083H15.5v-3.083h3.083v-3.084H15.5V9.875h12.333v15.417zM24.75 12.958h-3.083v3.084h3.083v-3.084zm0 6.167h-3.083v3.083h3.083v-3.083z"></path>
						</svg>
						<?php esc_html_e('Automatic Approval to Group','b2bking'); ?>	    				
					</div>
					<select class="b2bking_automatic_approval_customer_group_select" name="b2bking_automatic_approval_customer_group_select">
						<?php $selected = get_post_meta($post->ID,'b2bking_custom_role_automatic_approval_group',true); ?>
						<option value="none" <?php selected('none', $selected, true); ?>><?php esc_html_e('None (B2C)', 'b2bking'); ?></option>
						<?php
						// display all customer groups
						$groups = get_posts( array( 'post_type' => 'b2bking_group','post_status'=>'publish','numberposts' => -1) );
						foreach ($groups as $group){
							echo '<option value="group_'.esc_attr($group->ID).'" '.selected('group_'.$group->ID,$selected,false).'>'.esc_html($group->post_title).'</option>';
						}

						// IF SALESKING ACTIVATED, INTEGRATE WITH SALESKING BY SHOWING OPTIONS
						if (defined('SALESKING_DIR')){
							// display all sales agents groups
							$groups = get_posts( array( 'post_type' => 'salesking_group','post_status'=>'publish','numberposts' => -1) );
							if (!empty($groups)){
								?>
								<optgroup label="<?php esc_html_e('Sales Agent Groups', 'b2bking'); ?>">
									<?php
									foreach ($groups as $group){
										echo '<option value="saleskinggroup_'.esc_attr($group->ID).'" '.selected('saleskinggroup_'.$group->ID,$selected,false).'>'.esc_html($group->post_title).'</option>';
									}
								?>
								</optgroup>
								<?php
							}
							
						}
						?>
					</select>
				</div>
			</div>
			<div class="b2bking_custom_role_approval_sort_container_element">
				<div class="b2bking_custom_field_settings_metabox_top_column_sort_title">
					<svg class="b2bking_custom_field_settings_metabox_top_column_sort_title_icon" xmlns="http://www.w3.org/2000/svg" width="30" height="28" fill="none" viewBox="0 0 30 28">
					  <path fill="#C4C4C4" d="M6.167 27.75H0v-3.083h6.167v-1.542H3.083A3.083 3.083 0 010 20.042V18.5a3.092 3.092 0 013.083-3.083h3.084A3.083 3.083 0 019.25 18.5v6.167a3.073 3.073 0 01-3.083 3.083zm0-9.25H3.083v1.542h3.084V18.5zM3.083 0h3.084A3.083 3.083 0 019.25 3.083V9.25a3.073 3.073 0 01-3.083 3.083H3.083A3.083 3.083 0 010 9.25V3.083A3.092 3.092 0 013.083 0zm0 9.25h3.084V3.083H3.083V9.25zm10.792-6.167h15.417v3.084H13.875V3.083zm0 21.584v-3.084h15.417v3.084H13.875zm0-12.334h15.417v3.084H13.875v-3.084z"/>
					</svg>
					<?php esc_html_e('Non-Selectable','b2bking'); ?>
				</div>
				<div class="b2bking_custom_role_settings_metabox_container_element_checkbox_container b2bking_custom_role_settings_metabox_container_element_checkbox_nonselectable">
					<div class="b2bking_custom_role_settings_metabox_container_element_checkbox_name">
						<?php esc_html_e('This option is a "please select" placeholder','b2bking'); ?>
					</div>
					<input type="checkbox" value="1" class="b2bking_custom_role_settings_metabox_container_element_checkbox" name="b2bking_custom_role_settings_metabox_container_element_checkbox_nonselectable" <?php checked(1,intval(get_post_meta($post->ID,'b2bking_non_selectable',true)),true); ?>>
				</div>
			</div>
		</div>
		<br />

		<!-- Information panel -->
		<div class="b2bking_custom_role_settings_metabox_information_box">
			<svg class="b2bking_custom_role_settings_metabox_information_box_icon" xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 36 36">
			  <path fill="#358BBB" d="M18 0C8.06 0 0 8.06 0 18s8.06 18 18 18 18-8.06 18-18S27.94 0 18 0zm0 28.446a1.607 1.607 0 110-3.213 1.607 1.607 0 010 3.213zm2.527-8.819a1.941 1.941 0 00-1.241 1.8v.912a.322.322 0 01-.322.322h-1.928a.322.322 0 01-.322-.322v-.864c0-.928.27-1.844.8-2.607a4.49 4.49 0 012.093-1.643c1.366-.527 2.25-1.672 2.25-2.921 0-1.772-1.732-3.215-3.857-3.215s-3.857 1.443-3.857 3.215v.305a.322.322 0 01-.322.321h-1.928a.322.322 0 01-.322-.321v-.305c0-1.58.691-3.054 1.945-4.15C14.721 9.095 16.312 8.517 18 8.517c1.688 0 3.279.582 4.484 1.635 1.253 1.097 1.945 2.572 1.945 4.15 0 2.323-1.531 4.412-3.902 5.324z"/>
			</svg>
			<?php esc_html_e('In this panel, you can control registration role settings.','b2bking'); ?>
		</div>		

		<?php
	}

	// Save Custom Registration Role Metabox 
	function b2bking_save_custom_role_metaboxes($post_id){
		if (isset($_POST['_inline_edit'])){
			return;
		}
		if (isset($_REQUEST['bulk_edit'])){
		    return;
		}

		$postobj = get_post($post_id);
		if ( $postobj->post_status === 'trash' ) {
	        return;
	    }
	    if (isset($_GET['action'])) {
	    	if ($_GET['action'] === 'untrash'){
	    		return;
	    	}
	    }

		if (get_post_type($post_id) === 'b2bking_custom_role'){
			$status = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_role_settings_metabox_container_element_checkbox'));
			$approval = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_role_settings_metabox_container_element_select'));
			$automatic_approval_group = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_automatic_approval_customer_group_select'));
			$selectable = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_role_settings_metabox_container_element_checkbox_nonselectable'));
			
			if ($status !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_role_status', $status);
			}

			if ($approval !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_role_approval', $approval);
			}

			if ($automatic_approval_group !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_role_automatic_approval_group', $automatic_approval_group);
			}

			if ($selectable !== NULL){
				update_post_meta( $post_id, 'b2bking_non_selectable', $selectable);
			}

		}
	}

	// Add custom columns to custom role menu
	function b2bking_add_columns_custom_role_menu($columns) {

		$columns_initial = $columns;

		// rename title
		$columns = array(
			'title' => esc_html__( 'Registration role name', 'b2bking' ),
			'b2bking_approval' => esc_html__( 'User Approval', 'b2bking' ),
			'b2bking_automatic_approval_group' => esc_html__( 'Automatic Approval Group', 'b2bking' ),
			'b2bking_sort' => esc_html__( 'Sort Order (Drag & Drop)', 'b2bking' ).' <span class="dashicons dashicons-editor-help sort_order_tip"></span>',
			'b2bking_status' => esc_html__( 'Enabled', 'b2bking' ),
		);

		$columns = array_slice($columns_initial, 0, 1, true) + $columns;


	    return $columns;
	}

	// Add custom role custom columns data
	function b2bking_columns_custom_role_data( $column, $post_id ) {
	    switch ( $column ) {

	        case 'b2bking_approval' :
	        	$approval = get_post_meta($post_id,'b2bking_custom_role_approval',true);

	            echo '<span class="b2bking_custom_role_column_approval_'.esc_attr($approval).'">'.esc_html(ucfirst($approval)).'</span>';;
	            break;

	        case 'b2bking_automatic_approval_group' :
	        	$approval = get_post_meta($post_id,'b2bking_custom_role_approval',true);
	        	$approval_group = get_post_meta($post_id,'b2bking_custom_role_automatic_approval_group',true);
	        	if ($approval_group === NULL || $approval_group === 'none' || $approval === 'manual' || $approval_group === ''){
	        		$approval_group = '-';
	        	} else {
	        		$approval_group = get_the_title(explode('_',$approval_group)[1]);
	        	}
	            echo '<span class="b2bking_custom_role_column_approval_'.esc_attr($approval).'">'.esc_html(ucfirst($approval_group)).'</span>';;
	            break;

	        case 'b2bking_sort' :

	            ?>
	            <span class="dashicons dashicons-move"></span>
	            <?php
	            break;

	         case 'b2bking_status' :
        		$status = get_post_meta($post_id,'b2bking_custom_role_status', true);
        		if (intval($status) === 1){
        			$status = 'enabled';
        		} else {
        			$status = 'disabled';
        		}

        	    ?>
        	    <input type="checkbox" class="b2bking_switch_input" id="b2bking_switch_field_<?php echo esc_attr($post_id);?>" <?php
        	    if ($status === 'enabled'){
        	    	echo 'checked';
        	    }
        		?>/><label class="b2bking_switch_label" for="b2bking_switch_field_<?php echo esc_attr($post_id);?>">Toggle</label>
        	    <?php
	            break;


	    }
	}


	// Register new post type: Custom Registration Field (b2bking_custom_field)
	function b2bking_register_post_type_custom_field() {
		// Build labels and arguments
	    $labels = array(
	        'name'                  => esc_html__( 'Custom Registration Field', 'b2bking' ),
	        'singular_name'         => esc_html__( 'Registration Field', 'b2bking' ),
	        'all_items'             => esc_html__( 'Registration Fields', 'b2bking' ),
	        'menu_name'             => esc_html__( 'Registration Fields', 'b2bking' ),
	        'add_new'               => esc_html__( 'Add New', 'b2bking' ),
	        'add_new_item'          => esc_html__( 'Add new field', 'b2bking' ),
	        'edit'                  => esc_html__( 'Edit', 'b2bking' ),
	        'edit_item'             => esc_html__( 'Edit field', 'b2bking' ),
	        'new_item'              => esc_html__( 'New field', 'b2bking' ),
	        'view_item'             => esc_html__( 'View field', 'b2bking' ),
	        'view_items'            => esc_html__( 'View fields', 'b2bking' ),
	        'search_items'          => esc_html__( 'Search fields', 'b2bking' ),
	        'not_found'             => esc_html__( 'No fields found', 'b2bking' ),
	        'not_found_in_trash'    => esc_html__( 'No fields found in trash', 'b2bking' ),
	        'parent'                => esc_html__( 'Parent field', 'b2bking' ),
	        'featured_image'        => esc_html__( 'Field image', 'b2bking' ),
	        'set_featured_image'    => esc_html__( 'Set field image', 'b2bking' ),
	        'remove_featured_image' => esc_html__( 'Remove field image', 'b2bking' ),
	        'use_featured_image'    => esc_html__( 'Use as field image', 'b2bking' ),
	        'insert_into_item'      => esc_html__( 'Insert into field', 'b2bking' ),
	        'uploaded_to_this_item' => esc_html__( 'Uploaded to this field', 'b2bking' ),
	        'filter_items_list'     => esc_html__( 'Filter fields', 'b2bking' ),
	        'items_list_navigation' => esc_html__( 'Fields navigation', 'b2bking' ),
	        'items_list'            => esc_html__( 'Fields list', 'b2bking' )
	    );
	    $args = array(
	        'label'                 => esc_html__( 'Custom Registration Field', 'b2bking' ),
	        'description'           => esc_html__( 'This is where you can create new custom registration fields', 'b2bking' ),
	        'labels'                => $labels,
	        'supports'              => array( 'title' ),
	        'hierarchical'          => false,
	        'public'                => false,
	        'show_ui'               => true,
	        'show_in_menu'          => 'b2bkingcore',
	        'menu_position'         => 124,
	        'show_in_admin_bar'     => true,
	        'show_in_nav_menus'     => false,
	        'can_export'            => true,
	        'has_archive'           => false,
	        'exclude_from_search'   => true,
	        'publicly_queryable'    => false,
	        'capability_type'       => 'product',
	        'map_meta_cap'          => true
	    );

	// Actually register the post type
	register_post_type( 'b2bking_custom_field', $args );
	}

	// Add Registration Custom Field Metaboxes
	function b2bking_custom_field_metaboxes($post_type) {
	    $post_types = array('b2bking_custom_field');     //limit meta box to certain post types
       	if ( in_array( $post_type, $post_types ) ) {
       		add_meta_box(
       		    'b2bking_custom_field_settings_metabox'
       		    ,esc_html__( 'Registration Field Settings', 'b2bking' )
       		    ,array( $this, 'b2bking_custom_field_settings_metabox_content' )
       		    ,$post_type
       		    ,'advanced'
       		    ,'high'
       		);
       		add_meta_box(
       		    'b2bking_custom_field_billing_connection_metabox'
       		    ,esc_html__( 'Billing Options', 'b2bking' )
       		    ,array( $this, 'b2bking_custom_field_billing_connection_metabox_content' )
       		    ,$post_type
       		    ,'advanced'
       		    ,'low'
       		);
	    }
	}

	function b2bking_custom_field_settings_metabox_content(){
		global $post;
		?>
		<div class="b2bking_custom_field_settings_metabox_container">
			<div class="b2bking_custom_field_settings_metabox_top">
				<div class="b2bking_custom_field_settings_metabox_top_column">
					<div class="b2bking_custom_field_settings_metabox_top_column_status">
						<div class="b2bking_custom_field_settings_metabox_top_column_status_title">
							<svg class="b2bking_custom_field_settings_metabox_top_column_status_title_icon" xmlns="http://www.w3.org/2000/svg" width="33" height="35" fill="none" viewBox="0 0 33 35">
							  <path fill="#C4C4C4" fill-rule="evenodd" d="M22.211 3.395v5.07c3.495 1.914 5.729 5.664 5.729 9.88 0 6.16-5.115 11.157-11.423 11.157-6.303 0-11.417-4.994-11.417-11.156 0-4.354 2.202-8.092 5.867-9.93V3.39C4.575 5.495.314 11.36.314 18.346c0 8.745 7.256 15.831 16.201 15.831 8.948 0 16.206-7.086 16.206-15.831a15.804 15.804 0 00-10.51-14.95z" clip-rule="evenodd"/>
							  <path fill="#C4C4C4" fill-rule="evenodd" d="M16.413 16.906c1.693 0 3.073-1.036 3.073-2.32V2.32c0-1.282-1.38-2.32-3.073-2.32-1.696 0-3.073 1.038-3.073 2.32v12.266c0 1.284 1.377 2.32 3.073 2.32z" clip-rule="evenodd"/>
							</svg>
							<?php esc_html_e('Status','b2bking'); ?>
						</div>
						<div class="b2bking_custom_field_settings_metabox_top_column_status_checkbox_container">
							<div class="b2bking_custom_field_settings_metabox_top_column_status_checkbox_name">
								<?php esc_html_e('Enabled','b2bking'); ?>
							</div>
							<input type="checkbox" value="1" class="b2bking_custom_field_settings_metabox_top_column_status_checkbox_input" name="b2bking_custom_field_settings_metabox_top_column_status_checkbox_input" <?php checked(1, intval(get_post_meta($post->ID, 'b2bking_custom_field_status', true)), true); ?>>
						</div>
					</div>
					<div class="b2bking_custom_field_settings_metabox_top_column_registration_role">
						<div class="b2bking_custom_field_settings_metabox_top_column_registration_role_title">
							<svg class="b2bking_custom_field_settings_metabox_top_column_registration_role_title_icon" xmlns="http://www.w3.org/2000/svg" width="28" height="31" fill="none" viewBox="0 0 28 31">
							  <path fill="#C4C4C4" d="M24.667 0H3.083A3.083 3.083 0 000 3.083v20.042a3.083 3.083 0 003.083 3.083H9.25l4.625 4.625 4.625-4.625h6.167a3.083 3.083 0 003.083-3.083V3.083A3.083 3.083 0 0024.667 0zM13.875 4.625c2.663 0 4.625 1.961 4.625 4.625 0 2.664-1.962 4.625-4.625 4.625-2.66 0-4.625-1.961-4.625-4.625 0-2.664 1.964-4.625 4.625-4.625zM6.44 21.583c.86-2.656 3.847-4.625 7.435-4.625 3.587 0 6.577 1.969 7.436 4.625H6.44z"/>
							</svg>
							<?php esc_html_e('Registration Role','b2bking'); ?>
						</div>
						<select class="b2bking_custom_field_settings_metabox_top_column_registration_role_select" name="b2bking_custom_field_settings_metabox_top_column_registration_role_select">
							<option value="allroles" <?php selected('allroles', get_post_meta($post->ID, 'b2bking_custom_field_registration_role', true), true); ?>><?php esc_html_e('All Roles','b2bking'); ?></option>
							<option value="multipleroles" <?php selected('multipleroles', get_post_meta($post->ID, 'b2bking_custom_field_registration_role', true), true); ?>><?php esc_html_e('Select Multiple Roles','b2bking'); ?></option>
							<?php 
								$registration_roles = get_posts([
							    		'post_type' => 'b2bking_custom_role',
							    	  	'post_status' => 'publish',
							    	  	'numberposts' => -1,
							    ]);
							    foreach ($registration_roles as $role){
							    	echo '<option value="role_'.$role->ID.'" '.selected('role_'.$role->ID, get_post_meta($post->ID, 'b2bking_custom_field_registration_role', true), false).'>'.esc_html($role->post_title).'</option>'; 
							    }
							?>
						</select>

					</div>
					<div id="b2bking_select_multiple_roles_selector" class="b2bking_custom_field_settings_metabox_top_column_registration_role">
						<div class="b2bking_custom_field_settings_metabox_top_column_registration_role_title">
							<svg class="b2bking_custom_field_settings_metabox_top_column_registration_role_title_icon" xmlns="http://www.w3.org/2000/svg" width="28" height="31" fill="none" viewBox="0 0 28 31">
							  <path fill="#C4C4C4" d="M24.667 0H3.083A3.083 3.083 0 000 3.083v20.042a3.083 3.083 0 003.083 3.083H9.25l4.625 4.625 4.625-4.625h6.167a3.083 3.083 0 003.083-3.083V3.083A3.083 3.083 0 0024.667 0zM13.875 4.625c2.663 0 4.625 1.961 4.625 4.625 0 2.664-1.962 4.625-4.625 4.625-2.66 0-4.625-1.961-4.625-4.625 0-2.664 1.964-4.625 4.625-4.625zM6.44 21.583c.86-2.656 3.847-4.625 7.435-4.625 3.587 0 6.577 1.969 7.436 4.625H6.44z"/>
							</svg>
							<?php esc_html_e('Select Multiple Roles','b2bking'); ?>
						</div>
						<select class="b2bking_custom_field_settings_metabox_top_column_registration_role_select" name="b2bking_custom_field_settings_metabox_top_column_registration_role_select_multiple_roles[]" multiple>
							<?php
							// if page not "Add new", get selected options
							$selected_options = array();
							if( get_current_screen()->action !== 'add'){
					        	$selected_options_string = get_post_meta($post->ID, 'b2bking_custom_field_multiple_roles', true);
					        	$selected_options = explode(',', $selected_options_string);
					        }

				        	$registration_roles = get_posts([
				            		'post_type' => 'b2bking_custom_role',
				            	  	'post_status' => 'publish',
				            	  	'numberposts' => -1,
				            ]);
				            foreach ($registration_roles as $role){
				            	$is_selected = 'no';
				            	foreach ($selected_options as $selected_option){
										if ($selected_option === ('role_'.$role->ID )){
											$is_selected = 'yes';
										}
									}
				            	echo '<option value="role_'.$role->ID.'" '.selected('yes',$is_selected, true).'>'.esc_html($role->post_title).'</option>'; 
				            }
					        ?>
						</select>

					</div>
				</div>
				<div class="b2bking_custom_field_settings_metabox_top_column">
					<div class="b2bking_custom_field_settings_metabox_top_column_required">
						<div class="b2bking_custom_field_settings_metabox_top_column_required_title">
							<svg class="b2bking_custom_field_settings_metabox_top_column_required_title_icon" xmlns="http://www.w3.org/2000/svg" width="33" height="33" fill="none" viewBox="0 0 33 33">
							  <path fill="#C4C4C4" d="M16.188 0C7.248 0 0 7.248 0 16.188c0 8.939 7.248 16.187 16.188 16.187 8.939 0 16.187-7.248 16.187-16.188C32.375 7.248 25.127 0 16.187 0zM15.03 8.383c0-.16.13-.29.29-.29h1.734c.159 0 .289.13.289.29v9.828a.29.29 0 01-.29.289H15.32a.29.29 0 01-.289-.29V8.384zm1.156 15.898a1.734 1.734 0 010-3.468 1.735 1.735 0 010 3.468z"/>
							</svg>
							<?php esc_html_e('Required','b2bking'); ?>
						</div>
						<div class="b2bking_custom_field_settings_metabox_top_column_status_checkbox_container">
							<div class="b2bking_custom_field_settings_metabox_top_column_status_checkbox_name">
								<?php esc_html_e('Required','b2bking'); ?>
							</div>
							<input type="checkbox" value="1" class="b2bking_custom_field_settings_metabox_top_column_required_checkbox_input" name="b2bking_custom_field_settings_metabox_top_column_required_checkbox_input" <?php checked(1, intval(get_post_meta($post->ID, 'b2bking_custom_field_required', true)), true); ?>>
						</div>
					</div>
					<div class="b2bking_custom_field_settings_metabox_top_column_sort">
						<div class="b2bking_custom_field_settings_metabox_top_column_sort_title">
							<svg class="b2bking_custom_field_settings_metabox_top_column_sort_title_icon" xmlns="http://www.w3.org/2000/svg" width="30" height="28" fill="none" viewBox="0 0 30 28">
							  <path fill="#C4C4C4" d="M6.167 27.75H0v-3.083h6.167v-1.542H3.083A3.083 3.083 0 010 20.042V18.5a3.092 3.092 0 013.083-3.083h3.084A3.083 3.083 0 019.25 18.5v6.167a3.073 3.073 0 01-3.083 3.083zm0-9.25H3.083v1.542h3.084V18.5zM3.083 0h3.084A3.083 3.083 0 019.25 3.083V9.25a3.073 3.073 0 01-3.083 3.083H3.083A3.083 3.083 0 010 9.25V3.083A3.092 3.092 0 013.083 0zm0 9.25h3.084V3.083H3.083V9.25zm10.792-6.167h15.417v3.084H13.875V3.083zm0 21.584v-3.084h15.417v3.084H13.875zm0-12.334h15.417v3.084H13.875v-3.084z"/>
							</svg>
							<?php esc_html_e('Sort Order','b2bking'); ?>
						</div>
						<input type="number" min="1" max="1000" name="b2bking_custom_field_settings_metabox_top_column_sort_input" class="b2bking_custom_field_settings_metabox_top_column_sort_text" placeholder="<?php esc_html_e('Enter sort number here...', 'b2bking'); ?>" value="<?php echo esc_attr(get_post_meta($post->ID, 'b2bking_custom_field_sort_number', true)); ?>">
					</div>
					<div class="b2bking_custom_field_settings_metabox_top_column_editable">
						<div class="b2bking_custom_field_settings_metabox_top_column_editable_title">
							<svg class="b2bking_custom_field_settings_metabox_top_column_editable_title_icon" xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="none" viewBox="0 0 37 37">
							  <path fill="#C4C4C4" d="M29.273 3.102l4.625 4.625-3.526 3.527-4.625-4.625 3.526-3.527zM12.333 24.667h4.625l11.235-11.235-4.626-4.624-11.234 11.234v4.625z"/>
							  <path fill="#C4C4C4" d="M29.292 29.292H12.577c-.04 0-.082.015-.122.015-.05 0-.102-.014-.154-.015H7.708V7.708h10.556l3.084-3.083H7.707a3.085 3.085 0 00-3.083 3.083v21.584a3.085 3.085 0 003.083 3.083h21.584a3.083 3.083 0 003.083-3.083V15.928l-3.083 3.084v10.28z"/>
							</svg>
							<?php esc_html_e('Editable Post-Registration','b2bking'); ?>
						</div>
						<div class="b2bking_custom_field_settings_metabox_top_column_editable_checkbox_container">
							<div class="b2bking_custom_field_settings_metabox_top_column_status_checkbox_name">
								<?php esc_html_e('Users will be able to edit this field','b2bking'); ?>
							</div>
							<input type="checkbox" value="1" class="b2bking_custom_field_settings_metabox_top_column_editable_checkbox_input" name="b2bking_custom_field_settings_metabox_top_column_editable_checkbox_input" <?php checked(1, intval(get_post_meta($post->ID, 'b2bking_custom_field_editable', true)), true); ?>>
						</div>
					</div>
				</div>
			</div>
			<div class="b2bking_custom_field_settings_metabox_bottom">
				<div class="b2bking_custom_field_settings_metabox_bottom_field_type">
					<div class="b2bking_custom_field_settings_metabox_bottom_field_type_title">
						<svg class="b2bking_custom_field_settings_metabox_bottom_field_type_title_icon" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 28 28">
						  <path fill="#C4C4C4" d="M24.667 0H3.083A3.083 3.083 0 000 3.083v21.584a3.083 3.083 0 003.083 3.083h21.584a3.083 3.083 0 003.083-3.083V3.083A3.083 3.083 0 0024.667 0zM4.625 4.625h7.708v7.708H4.625V4.625zm6.938 20.042a3.854 3.854 0 110-7.709 3.854 3.854 0 010 7.709zm4.624-9.25l4.625-7.709 4.625 7.709h-9.25z"/>
						</svg>
						<?php esc_html_e('Field Type','b2bking'); ?>
					</div>
					<select class="b2bking_custom_field_settings_metabox_bottom_field_type_select" name="b2bking_custom_field_settings_metabox_bottom_field_type_select">
						<?php $field_type_post_meta = get_post_meta($post->ID, 'b2bking_custom_field_field_type', true); ?>

						<option value="text" <?php selected('text', $field_type_post_meta, true);?>><?php esc_html_e('Text','b2bking'); ?></option>
						<option value="textarea" <?php selected('textarea', $field_type_post_meta, true);?>><?php esc_html_e('Textarea','b2bking'); ?></option>
						<option value="number" <?php selected('number', $field_type_post_meta, true);?>><?php esc_html_e('Number','b2bking'); ?></option>
						<option value="tel" <?php selected('tel', $field_type_post_meta, true);?>><?php esc_html_e('Telephone','b2bking'); ?></option>
						<option value="select" <?php selected('select', $field_type_post_meta, true);?>><?php esc_html_e('Select','b2bking'); ?></option>
						<option value="checkbox" <?php selected('checkbox', $field_type_post_meta, true);?>><?php esc_html_e('Checkboxes (check all that apply)','b2bking'); ?></option>
						<option value="email" <?php selected('email', $field_type_post_meta, true);?>><?php esc_html_e('Email','b2bking'); ?></option>
						<option value="file" <?php selected('file', $field_type_post_meta, true);?>><?php esc_html_e('File Upload (supported: jpg, jpeg, png, txt, pdf, doc, docx)','b2bking'); ?></option>
						<option value="date" <?php selected('date', $field_type_post_meta, true);?>><?php esc_html_e('Date','b2bking'); ?></option>
					</select>	
				</div>
				<div class="b2bking_custom_field_settings_metabox_bottom_label_and_placeholder_container">
					<div class="b2bking_custom_field_settings_metabox_bottom_field_label">
						<div class="b2bking_custom_field_settings_metabox_bottom_field_label_title">
							<svg class="b2bking_custom_field_settings_metabox_bottom_field_label_title_icon" xmlns="http://www.w3.org/2000/svg" width="31" height="30" fill="none" viewBox="0 0 31 30">
							  <path fill="#C4C4C4" d="M29.155 14.366c.61.61.915 1.327.915 2.148 0 .822-.305 1.514-.915 2.078L18.662 29.155a3.065 3.065 0 01-2.148.845c-.821 0-1.514-.282-2.077-.845L.915 15.634C.305 15.024 0 14.319 0 13.52V2.958C0 2.16.293 1.468.88.88 1.467.293 2.183 0 3.028 0h10.493c.845 0 1.55.282 2.113.845l13.52 13.521zM5.246 7.465a2.27 2.27 0 001.62-.634c.446-.423.67-.95.67-1.585 0-.633-.224-1.173-.67-1.62a2.206 2.206 0 00-1.62-.668c-.633 0-1.161.223-1.584.669a2.27 2.27 0 00-.634 1.62c0 .633.211 1.161.634 1.584.423.423.95.634 1.584.634z"/>
							</svg>
							<?php esc_html_e('Field Label','b2bking'); ?>
						</div>
						<div class="b2bking_custom_field_settings_metabox_bottom_field_label_input_container">
							<input type="text" name="b2bking_custom_field_field_label_input" class="b2bking_custom_field_settings_metabox_top_column_sort_text" placeholder="<?php esc_html_e('Enter the field label here...', 'b2bking'); ?>" value="<?php echo esc_attr(get_post_meta($post->ID, 'b2bking_custom_field_field_label', true)); ?>">
						</div>
					</div>
					<div class="b2bking_custom_field_settings_metabox_bottom_field_placeholder">
						<div class="b2bking_custom_field_settings_metabox_bottom_field_placeholder_title">
							<svg class="b2bking_custom_field_settings_metabox_bottom_field_placeholder_title_icon" xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="none" viewBox="0 0 37 37">
							  <path fill="#C4C4C4" d="M30.833 31.833H6.167a3.084 3.084 0 01-3.084-3.083v-18.5a3.083 3.083 0 013.084-3.083h24.666a3.084 3.084 0 013.084 3.083v18.5a3.084 3.084 0 01-3.084 3.083zM6.167 10.25v18.5h24.666v-18.5H6.167zm3.083 4.625h18.5v3.083H9.25v-3.083zm0 6.167h15.417v3.083H9.25v-3.083z"/>
							</svg>
							<?php esc_html_e('Placeholder Text','b2bking'); ?>
						</div>
						<input type="text" name="b2bking_custom_field_field_placeholder_input" class="b2bking_custom_field_settings_metabox_top_column_sort_text" placeholder="<?php esc_html_e('Enter the placeholder text here...', 'b2bking'); ?>" value="<?php echo esc_attr(get_post_meta($post->ID, 'b2bking_custom_field_field_placeholder', true)); ?>">
					</div>
				</div>
				<div class="b2bking_custom_field_settings_metabox_bottom_user_choices">
					<div class="b2bking_custom_field_settings_metabox_bottom_user_choices_title">
						<svg class="b2bking_custom_field_settings_metabox_bottom_user_choices_title_icon" xmlns="http://www.w3.org/2000/svg" width="34" height="34" fill="none" viewBox="0 0 34 34">
						  <path fill="#C4C4C4" d="M10.625 7.438a3.189 3.189 0 11-6.377-.003 3.189 3.189 0 016.377.003z"/>
						  <path fill="#C4C4C4" d="M7.438 0C3.4 0 0 3.4 0 7.438c0 4.037 3.4 7.437 7.438 7.437 4.037 0 7.437-3.4 7.437-7.438C14.875 3.4 11.475 0 7.437 0zm0 12.75c-2.975 0-5.313-2.338-5.313-5.313 0-2.974 2.338-5.312 5.313-5.312 2.974 0 5.312 2.338 5.312 5.313 0 2.974-2.338 5.312-5.313 5.312zM7.438 17C3.4 17 0 20.4 0 24.438c0 4.037 3.4 7.437 7.438 7.437 4.037 0 7.437-3.4 7.437-7.438 0-4.037-3.4-7.437-7.438-7.437zm0 12.75c-2.975 0-5.313-2.337-5.313-5.313 0-2.975 2.338-5.312 5.313-5.312 2.974 0 5.312 2.337 5.312 5.313 0 2.975-2.338 5.312-5.313 5.312zM17 4.25h17v6.375H17V4.25zM17 21.25h17v6.375H17V21.25z"/>
						</svg>
						<?php esc_html_e('User Choices (Options)','b2bking'); ?>
					</div>
					<input type="text" class="b2bking_custom_field_settings_metabox_top_column_sort_text" name="b2bking_custom_field_user_choices_input" placeholder="<?php esc_html_e('Please enter options separated by commas. Example: “Apples, Oranges, Pears”.', 'b2bking'); ?>" value="<?php echo esc_attr(get_post_meta($post->ID, 'b2bking_custom_field_user_choices', true)); ?>">
				</div>
			</div>	
		</div>
		<?php
	}

	// Billing Options Metabox
	function b2bking_custom_field_billing_connection_metabox_content(){
		?>
		<div class="b2bking_custom_field_billing_connection_metabox_container">
			<div class="b2bking_custom_field_billing_connection_metabox_title">
				<svg class="b2bking_custom_field_billing_connection_metabox_title_icon" xmlns="http://www.w3.org/2000/svg" width="28" height="37" fill="none" viewBox="0 0 28 37">
				  <g clip-path="url(#clip0)">
				    <path fill="#C4C4C4" d="M27.384 7.588L20.273.506A1.747 1.747 0 0019.038 0h-.443v9.25h9.297v-.44c0-.456-.181-.897-.508-1.222zM16.27 9.828V0H1.743C.777 0 0 .773 0 1.734v33.532C0 36.226.777 37 1.743 37H26.15c.966 0 1.743-.773 1.743-1.734V11.563h-9.878c-.959 0-1.744-.781-1.744-1.735zM4.65 5.203c0-.32.26-.578.58-.578h5.812a.58.58 0 01.58.578V6.36a.58.58 0 01-.58.579H5.23a.58.58 0 01-.581-.579V5.203zm0 5.781V9.828c0-.32.26-.578.58-.578h5.812a.58.58 0 01.58.578v1.156a.58.58 0 01-.58.579H5.23a.58.58 0 01-.581-.579zm10.46 19.07v1.743a.58.58 0 01-.582.578h-1.162a.58.58 0 01-.581-.578V30.04a4.172 4.172 0 01-2.279-.82.577.577 0 01-.041-.877l.853-.81c.202-.19.5-.2.736-.053.281.175.6.269.931.269h2.042c.472 0 .857-.428.857-.953 0-.43-.262-.809-.637-.92l-3.268-.976c-1.35-.403-2.294-1.692-2.294-3.135 0-1.772 1.384-3.212 3.1-3.257v-1.743c0-.32.26-.578.58-.578h1.162a.58.58 0 01.582.578v1.755c.82.042 1.617.326 2.278.82a.577.577 0 01.042.877l-.854.81c-.201.191-.5.2-.736.053a1.749 1.749 0 00-.93-.268h-2.043c-.472 0-.857.427-.857.953 0 .43.262.808.637.92l3.269.975c1.35.403 2.294 1.693 2.294 3.136 0 1.773-1.384 3.211-3.1 3.257z"/>
				  </g>
				  <defs>
				    <clipPath id="clip0">
				      <path fill="#fff" d="M0 0h27.892v37H0z"/>
				    </clipPath>
				  </defs>
				</svg>
				<?php esc_html_e('WooCommerce Billing Field Connection', 'b2bking'); ?>
			</div>
			<select id="b2bking_custom_field_billing_connection_metabox_select" class="b2bking_custom_field_billing_connection_metabox_select" name="b2bking_custom_field_billing_connection_metabox_select">
				<?php 
				global $post;
				$selected_value = get_post_meta($post->ID, 'b2bking_custom_field_billing_connection', true);
				?>
				<option value="none" <?php selected('none', $selected_value, true); ?>><?php esc_html_e('None', 'b2bking'); ?></option>
				<option value="billing_first_name" <?php selected('billing_first_name', $selected_value, true); ?>><?php esc_html_e('First Name', 'b2bking'); ?></option>
				<option value="billing_last_name" <?php selected('billing_last_name', $selected_value, true); ?>><?php esc_html_e('Last Name', 'b2bking'); ?></option>
				<option value="billing_company" <?php selected('billing_company', $selected_value, true); ?>><?php esc_html_e('Company Name', 'b2bking'); ?></option>
				<option value="billing_countrystate" <?php selected('billing_countrystate', $selected_value, true); ?>><?php esc_html_e('Country + State (Recommended)', 'b2bking'); ?></option>
				<option value="billing_country" <?php selected('billing_country', $selected_value, true); ?>><?php esc_html_e('Country / Region', 'b2bking'); ?></option>
				<option value="billing_state" <?php selected('billing_state', $selected_value, true); ?>><?php esc_html_e('State / County', 'b2bking'); ?></option>
				<option value="billing_address_1" <?php selected('billing_address_1', $selected_value, true); ?>><?php esc_html_e('Street Address', 'b2bking'); ?></option>
				<option value="billing_address_2" <?php selected('billing_address_2', $selected_value, true); ?>><?php esc_html_e('Address Line 2', 'b2bking'); ?></option>
				<option value="billing_city" <?php selected('billing_city', $selected_value, true); ?>><?php esc_html_e('Town / City', 'b2bking'); ?></option>
				<option value="billing_postcode" <?php selected('billing_postcode', $selected_value, true); ?>><?php esc_html_e('Postcode / ZIP', 'b2bking'); ?></option>
				<option value="billing_phone" <?php selected('billing_phone', $selected_value, true); ?>><?php esc_html_e('Phone Number', 'b2bking'); ?></option>
				<option value="billing_vat" <?php selected('billing_vat', $selected_value, true); ?>><?php esc_html_e('VAT ID', 'b2bking'); ?></option>
				<option value="custom_mapping" <?php selected('custom_mapping', $selected_value, true); ?>><?php esc_html_e('Custom User Meta Key Mapping', 'b2bking'); ?></option>
			</select>

			<div class="b2bking_add_to_billing_container">
				<div class="b2bking_add_to_billing_container_element">
					<div class="b2bking_add_to_billing_container_element_title">
						<svg class="b2bking_add_to_billing_container_element_title_icon" xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="none" viewBox="0 0 37 37">
						  <path fill="#C4C4C4" d="M20.346 25.902H9.248a1.85 1.85 0 000 3.7h11.098a1.85 1.85 0 000-3.7zm-7.399-11.098h3.7a1.85 1.85 0 000-3.699h-3.7a1.85 1.85 0 000 3.7zm22.196 3.7h-5.549V1.857A1.85 1.85 0 0026.82.247L21.27 3.43 15.723.248a1.85 1.85 0 00-1.85 0L8.323 3.429 2.774.248A1.85 1.85 0 000 1.857v29.594A5.549 5.549 0 005.549 37h25.895a5.549 5.549 0 005.549-5.549V20.353a1.85 1.85 0 00-1.85-1.85zM5.549 33.3a1.85 1.85 0 01-1.85-1.85V5.057l3.7 2.108a1.998 1.998 0 001.85 0l5.548-3.18 5.549 3.18a1.997 1.997 0 001.85 0l3.699-2.108V31.45a5.55 5.55 0 00.333 1.85H5.548zm27.744-1.85a1.85 1.85 0 01-3.699 0v-9.248h3.7v9.248zM20.346 18.504H9.248a1.85 1.85 0 100 3.699h11.098a1.85 1.85 0 100-3.7z"/>
						</svg>
						<?php esc_html_e('Add custom field to billing', 'b2bking'); ?>
					</div>
					<div class="b2bking_add_to_billing_container_element_checkbox_container">
						<div class="b2bking_add_to_billing_container_element_checkbox_name">
							<?php esc_html_e('Enabled','b2bking'); ?>
						</div>
						<input type="checkbox" value="1" class="b2bking_add_to_billing_container_element_checkbox_input" name="b2bking_custom_field_add_to_billing_checkbox" <?php checked(1, intval(get_post_meta($post->ID, 'b2bking_custom_field_add_to_billing', true)), true); ?>>
					</div>
				</div>
				<div class="b2bking_add_to_billing_container_element">
					<div class="b2bking_add_to_billing_container_element_title">
						<svg class="b2bking_add_to_billing_container_element_title_icon" xmlns="http://www.w3.org/2000/svg" width="33" height="33" fill="none" viewBox="0 0 33 33">
						  <path fill="#C4C4C4" d="M16.188 0C7.248 0 0 7.248 0 16.188c0 8.939 7.248 16.187 16.188 16.187 8.939 0 16.187-7.248 16.187-16.188C32.375 7.248 25.127 0 16.187 0zM15.03 8.383c0-.16.13-.29.29-.29h1.734c.159 0 .289.13.289.29v9.828a.29.29 0 01-.29.289H15.32a.29.29 0 01-.289-.29V8.384zm1.156 15.898a1.734 1.734 0 010-3.468 1.735 1.735 0 010 3.468z"/>
						</svg>
						<?php esc_html_e('Make field required in billing', 'b2bking'); ?>
					</div>
					<div class="b2bking_add_to_billing_container_element_checkbox_container">
						<div class="b2bking_add_to_billing_container_element_checkbox_name">
							<?php esc_html_e('Required in Billing','b2bking'); ?>
						</div>
						<input type="checkbox" value="1" class="b2bking_add_to_billing_container_element_checkbox_input" name="b2bking_custom_field_required_billing_checkbox" <?php checked(1, intval(get_post_meta($post->ID, 'b2bking_custom_field_required_billing', true)), true); ?>>
					</div>
				</div>
				<div class="b2bking_add_to_billing_container_element">
					<div class="b2bking_add_to_billing_container_element_title">
						<svg class="b2bking_add_to_billing_container_element_title_icon" xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="none" viewBox="0 0 37 37">
						  <path fill="#C4C4C4" d="M18.355 22.547a4.047 4.047 0 004.032-4.399l-4.384 4.383c.116.01.234.016.352.016zM31.752 5.982L30.207 4.44a.29.29 0 00-.409 0l-3.95 3.951c-2.179-1.114-4.628-1.67-7.348-1.67-6.945 0-12.126 3.617-15.544 10.85a2.18 2.18 0 000 1.861c1.365 2.877 3.01 5.183 4.934 6.918l-3.823 3.82a.29.29 0 000 .41l1.543 1.542a.289.289 0 00.408 0l25.733-25.73a.29.29 0 000-.41zM11.996 18.5a6.36 6.36 0 019.354-5.61l-1.757 1.756a4.05 4.05 0 00-5.091 5.092l-1.757 1.757a6.327 6.327 0 01-.749-2.995z"/>
						  <path fill="#C4C4C4" d="M34.044 17.568c-1.271-2.679-2.785-4.863-4.541-6.553l-5.208 5.209a6.361 6.361 0 01-8.216 8.215l-4.418 4.418c2.05.948 4.33 1.422 6.839 1.422 6.945 0 12.126-3.616 15.544-10.85a2.178 2.178 0 000-1.861z"/>
						</svg>
						<?php esc_html_e('Don’t show this field in registration', 'b2bking'); ?>
					</div>
					<div class="b2bking_add_to_billing_container_element_checkbox_container">
						<div class="b2bking_add_to_billing_container_element_checkbox_name">
							<?php esc_html_e('Billing-exclusive Field','b2bking'); ?>
						</div>
						<input type="checkbox" value="1" class="b2bking_add_to_billing_container_element_checkbox_input" name="b2bking_custom_field_billing_exclusive_checkbox" <?php checked(1, intval(get_post_meta($post->ID, 'b2bking_custom_field_billing_exclusive', true)), true); ?>>
					</div>
				</div>
			</div>

			<div class="b2bking_custom_mapping_container">
				<input class="b2bking_custom_field_mapping_input" type="text" name="b2bking_custom_field_mapping" placeholder="<?php esc_attr_e('Enter your custom user meta key here (e.g. "billing_cnpj", "vat_number", etc.)', 'b2bking'); ?>" value="<?php echo esc_attr(get_post_meta($post->ID,'b2bking_custom_field_mapping', true)); ?>">
			</div>


			<div class="b2bking_VAT_container">
				<div class="b2bking_VAT_container_column">
					<div class="b2bking_VAT_container_column_title">
						<svg class="b2bking_VAT_container_column_title_icon" xmlns="http://www.w3.org/2000/svg" width="35" height="40" fill="none" viewBox="0 0 35 40">
						  <path fill="#C4C4C4" fill-rule="evenodd" d="M29.074 9.895h-4.14V7.292h3.38c.865 0 1.563-.853 1.563-1.902V1.902C29.877.849 29.179 0 28.314 0h-9.229c-.865 0-1.56.851-1.56 1.902V5.39c0 1.051.695 1.902 1.56 1.902h3.446v2.603H5.85c-.925 0-1.675.74-1.675 1.648L0 32.835v6.633h35v-6.633l-4.248-21.292a1.642 1.642 0 00-.493-1.167 1.687 1.687 0 00-1.185-.481zM10.01 29.64H7.43v-2.583h2.578v2.583zm-2.58-4.934v-2.583h2.577v2.583H7.43zm7.58 4.97h-2.577v-2.619h2.578v2.62zm0-5.01h-2.617V22.16h2.618v2.507zm5.001 5.006h-2.618v-2.617h2.618v2.617zm-2.618-5.005v-2.544h2.618v2.544h-2.618zm2.618-4.934H7.431v-4.934h12.58v4.934zm7.461 0h-5.045v-2.516h5.045v2.516z" clip-rule="evenodd"/>
						</svg>
						<?php esc_html_e('Enable Automatic VIES Validation for VAT', 'b2bking'); ?>
					</div>
					<div class="b2bking_VAT_container_VIES_validation_checkbox_container">
						<div class="b2bking_VAT_container_VIES_validation_checkbox_name">
							<?php esc_html_e('Enabled','b2bking'); ?>
						</div>
						<input type="checkbox" value="1" class="b2bking_VAT_container_VIES_validation_checkbox_input" name="b2bking_VAT_container_VIES_validation_checkbox_input" <?php checked(1, intval(get_post_meta($post->ID, 'b2bking_custom_field_VAT_VIES_validation', true)), true); ?>>
					</div>
				</div>
				<div class="b2bking_VAT_container_column">
					<div class="b2bking_VAT_container_column_title">
						<svg class="b2bking_VAT_container_column_title_icon" xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="none" viewBox="0 0 37 37">
						  <path fill="#C4C4C4" d="M18.5 0C8.283 0 0 8.283 0 18.5S8.283 37 18.5 37 37 28.717 37 18.5 28.717 0 18.5 0zm-4.586 5.014c.673.363 1.47.706 2.39 1.03a8.21 8.21 0 002.74.486c.669-.001 1.337-.168 1.904-.584.55-.33 1.082-.592 1.788-.524.725.079 1.488.328 2.254.37a15.995 15.995 0 012.799 1.942c-.44.026-.908.072-1.4.137-.492.065-.971.161-1.438.29-.466.13-.894.3-1.282.507-.389.207-.674.466-.856.776-.285.467-.485.862-.602 1.186-.24.671-.183 1.743-.738 2.235a1.175 1.175 0 00-.291.272.584.584 0 00-.098.408c.013.168.098.408.253.719.078.181.142.402.194.66.492 0 1.002-.075 1.438-.388l2.566.233c.662-.746 1.434-.758 2.098 0 .207.207.427.519.66.934l-1.088.738c-.259-.078-.57-.233-.932-.467a6.565 6.565 0 01-.545-.35c-1.034-.502-3.059.083-4.197.156-.149.297-.268.588-.621.66-.061.193-.002.399-.079.584-.492.777-.66 1.593-.505 2.448.285 1.348.984 2.021 2.098 2.021h.428c.544 0 .927.026 1.147.078.22.051.33.09.33.117-.13.31-.174.556-.136.738.129.582.549 1.022.505 1.652-.04.8-.292 1.49-.018 2.293.311.779.703 1.625.99 2.429a.753.753 0 00.525.427c.467.078 1.05-.233 1.75-.932.518-.57.816-1.193.893-1.866.103-.597.523-1.126.661-1.75v-.504c.13-.26.24-.512.33-.758.13-.358.144-.814.175-1.224.407-.408.805-.771 1.088-1.283.182-.311.234-.582.156-.816-.025-.051-.09-.104-.194-.155l-.584-.233c.01-.326.61-.278.894-.234l1.399-.855a15.356 15.356 0 01-1.03 5.111 13.573 13.573 0 01-2.817 4.45 13.983 13.983 0 01-5.967 3.887c-2.319.777-4.709.932-7.17.466.424-.749.668-1.592 1.166-2.333 0-.388.058-.719.175-.99.495-1.145 1.302-1.443 2.235-2.332.942-.982.933-2.142.971-3.556-.012-.896-1.444-1.446-2.099-1.944-1.517-1.022-2.48-2.526-4.644-2.08-.773.08-.96.23-1.536-.251l-.233-.117.02-.078.098-.194c.232-.243-.098-.548-.41-.448-.064.013-.135.02-.213.02-.07-.347-.303-.67-.35-1.05.363.286.675.5.934.643.259.142.48.24.66.291.182.078.337.104.467.078.285-.052.446-.337.485-.856a9.538 9.538 0 00-.058-1.787.874.874 0 00.155-.312c.274-1.416 1-1.105 2.1-1.515.18-.103.219-.233.115-.389 0-.025-.005-.038-.018-.038-.013 0-.02-.014-.02-.04.598-.3.95-.915 1.321-1.476-.316-.501-.807-.92-1.36-1.205-.297-.366-1.461-.142-1.71-.739a1.508 1.508 0 01-.467-.117c-1.049-.68-1.492-1.869-2.623-2.35a6.621 6.621 0 00-1.34.019 13.822 13.822 0 014.314-2.371zm-9.445 10.96c.233.389.519.739.855 1.05 1.749 1.606 3.392 1.946 5.635 2.759.104.077.246.207.428.389.244.185.451.407.7.583 0 .13-.02.31-.058.544-.04.233-.046.608-.02 1.127.075 1.442 1.264 2.583 1.593 3.964-.292 1.79-.296 3.548-.505 5.324a13.939 13.939 0 01-4.644-3.109 14.199 14.199 0 01-3.09-4.702 14.826 14.826 0 01-.991-3.946 15.17 15.17 0 01.097-3.983z"/>
						</svg>
						<?php esc_html_e('What countries can see this field (multiple select)', 'b2bking'); ?>
					</div>
					<select class="b2bking_VAT_container_countries_select" name="b2bking_VAT_container_countries_select[]" multiple>
						<?php
						// if page not "Add new", get selected options
						$selected_options = array();
						if( get_current_screen()->action !== 'add'){
				        	$selected_options_string = get_post_meta($post->ID, 'b2bking_custom_field_VAT_countries', true);
				        	$selected_options = explode(',', $selected_options_string);
				        }
				        // get countries list
				        $countries_object = new WC_Countries;
				        $countries_list = $countries_object -> get_countries();
				        $countries_list_eu = array('AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE');
						?>
						<optgroup label="<?php esc_html_e('EU Countries', 'b2bking'); ?>">
							<?php
							foreach($countries_list_eu as $eu_country){
								$country_is_selected = 'no';
								foreach ($selected_options as $selected_option){
									if ($selected_option === $eu_country){
										$country_is_selected = 'yes';
									}
								}
								?>
								<option value="<?php echo esc_attr($eu_country); ?>" <?php selected('yes',$country_is_selected,true); ?>><?php echo esc_html($countries_list[$eu_country]);?></option>
								<?php
								unset ($countries_list[$eu_country]);
							}
							?>
						</optgroup>
						<optgroup label="<?php esc_html_e('All Other Countries', 'b2bking'); ?>">
							<?php
							foreach($countries_list as $index => $country){
								$country_is_selected = 'no';
								foreach ($selected_options as $selected_option){
									if ($selected_option === $index){
										$country_is_selected = 'yes';
									}
								}
								?>
								<option value="<?php echo esc_attr($index); ?>" <?php selected('yes',$country_is_selected,true); ?>><?php echo esc_html($country);?></option>
								<?php
							}
							?>
						</optgroup>
					</select>
				</div>
			</div>

			<!-- Information panel -->
			<div class="b2bking_billing_connection_information_box">
				<svg class="b2bking_group_payment_shipping_information_box_icon" xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 36 36">
				  <path fill="#358BBB" d="M18 0C8.06 0 0 8.06 0 18s8.06 18 18 18 18-8.06 18-18S27.94 0 18 0zm0 28.446a1.607 1.607 0 110-3.213 1.607 1.607 0 010 3.213zm2.527-8.819a1.941 1.941 0 00-1.241 1.8v.912a.322.322 0 01-.322.322h-1.928a.322.322 0 01-.322-.322v-.864c0-.928.27-1.844.8-2.607a4.49 4.49 0 012.093-1.643c1.366-.527 2.25-1.672 2.25-2.921 0-1.772-1.732-3.215-3.857-3.215s-3.857 1.443-3.857 3.215v.305a.322.322 0 01-.322.321h-1.928a.322.322 0 01-.322-.321v-.305c0-1.58.691-3.054 1.945-4.15C14.721 9.095 16.312 8.517 18 8.517c1.688 0 3.279.582 4.484 1.635 1.253 1.097 1.945 2.572 1.945 4.15 0 2.323-1.531 4.412-3.902 5.324z"/>
				</svg>
				<?php esc_html_e('Billing field connection means that this data will automatically show up in the user’s billing details in WooCommerce, after registration.','b2bking'); ?>
			</div>
		</div>
		<?php
	}

	// Save Custom Registration Field Metabox 
	function b2bking_save_custom_field_metaboxes($post_id){
			if (isset($_POST['_inline_edit'])){
				return;
			}
			if (isset($_REQUEST['bulk_edit'])){
			    return;
			}


			$postobj = get_post($post_id);
			if ( $postobj->post_status === 'trash' ) {
		        return;
		    }
		    if (isset($_GET['action'])) {
		    	if ($_GET['action'] === 'untrash'){
		    		return;
		    	}
		    }
		if (get_post_type($post_id) === 'b2bking_custom_field'){

			$status = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_settings_metabox_top_column_status_checkbox_input'));
			$required = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_settings_metabox_top_column_required_checkbox_input'));
			$sort_number = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_settings_metabox_top_column_sort_input'));
			$registration_role = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_settings_metabox_top_column_registration_role_select'));
			if (isset($_POST['b2bking_custom_field_settings_metabox_top_column_registration_role_select_multiple_roles'])){
				$registration_role_multiple = array_map( 'sanitize_text_field', $_POST['b2bking_custom_field_settings_metabox_top_column_registration_role_select_multiple_roles'] );
			} else {
				$registration_role_multiple = NULL;
			}
			$editable = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_settings_metabox_top_column_editable_checkbox_input'));
			$field_type = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_settings_metabox_bottom_field_type_select'));
			$field_label = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_field_label_input')); 
			$placeholder_text = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_field_placeholder_input')); 
			$user_choices = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_user_choices_input'));
			$billing_connection = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_billing_connection_metabox_select'));
			$add_to_billing = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_add_to_billing_checkbox'));
			$billing_required = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_required_billing_checkbox'));
			$billing_exclusive = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_billing_exclusive_checkbox'));
			$vat_vies_validation = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_VAT_container_VIES_validation_checkbox_input'));
			$custom_field_mapping = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_custom_field_mapping'));
			if ($custom_field_mapping !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_mapping', $custom_field_mapping);
			}

			if (isset($_POST['b2bking_VAT_container_countries_select'])){
				$vat_available_countries = array_map( 'sanitize_text_field', $_POST['b2bking_VAT_container_countries_select'] );
			} else {
				$vat_available_countries = NULL;
			}

			if ($status !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_status', $status);
			}
			if ($required !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_required', $required);
			}
			if ($sort_number !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_sort_number', $sort_number);
			}
			if ($registration_role !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_registration_role', $registration_role);
			}
			if ($editable !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_editable', $editable);
			}
			if ($field_type !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_field_type', $field_type);
			}
			if ($field_label !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_field_label', $field_label);
			}
			if ($placeholder_text !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_field_placeholder', $placeholder_text);
			}
			if ($user_choices !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_user_choices', $user_choices);
			}
			if ($billing_connection !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_billing_connection', $billing_connection);
			}
			if ($add_to_billing !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_add_to_billing', $add_to_billing);
			}
			if ($billing_required !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_required_billing', $billing_required);
			}
			if ($billing_exclusive !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_billing_exclusive', $billing_exclusive);
			}
			if ($vat_vies_validation !== NULL){
				update_post_meta( $post_id, 'b2bking_custom_field_VAT_VIES_validation', $vat_vies_validation);
			}
			if ($vat_available_countries !== NULL){
				$countries_string = '';
				foreach ($vat_available_countries as $country){
					$countries_string .= sanitize_text_field ($country).',';
				}
				// remove last comma
				$countries_string = substr($countries_string, 0, -1);
				update_post_meta( $post_id, 'b2bking_custom_field_VAT_countries', $countries_string);
			}

			if ($registration_role_multiple !== NULL){
				$roles_string = '';
				foreach ($registration_role_multiple as $role){
					$roles_string .= sanitize_text_field ($role).',';
				}
				// remove last comma
				$roles_string = substr($roles_string, 0, -1);
				update_post_meta( $post_id, 'b2bking_custom_field_multiple_roles', $roles_string);
			}
		}
	}

	// Add custom columns to custom field menu
	function b2bking_add_columns_custom_field_menu($columns) {

		$columns_initial = $columns;

		// rename title
		$columns = array(
			'title' => esc_html__( 'Title', 'b2bking' ),
			'b2bking_registration_role' => esc_html__( 'Registration Role', 'b2bking' ),
			'b2bking_field_label' => esc_html__( 'Field Label', 'b2bking' ),
			'b2bking_field_type' => esc_html__( 'Field Type', 'b2bking' ),
			'b2bking_required' => esc_html__( 'Required', 'b2bking' ),
			'b2bking_status' => esc_html__( 'Status', 'b2bking' ),
		);

		$columns = array_slice($columns_initial, 0, 1, true) + $columns;


	    return $columns;
	}

	// Add custom field custom columns data
	function b2bking_columns_custom_field_data( $column, $post_id ) {
		
	    switch ( $column ) {

	        case 'b2bking_registration_role' :
	        	$registration_role = get_post_meta($post_id,'b2bking_custom_field_registration_role',true);
	        	if ($registration_role === 'allroles'){
	        		$registration_role = esc_html__('All Roles','b2bking');
	        	} else if ($registration_role === 'multipleroles'){
	        		$registration_role = esc_html__('Multiple Roles','b2bking');
	        	} else {
	        		$regrole = explode('_',$registration_role);
	        		if(isset($regrole[1])){
	        			$registration_role = get_the_title(intval($regrole[1]));
	        		} else {
	        			$registration_role = '-';
	        		}
	        	}

	            echo '<strong>'.esc_html($registration_role).'</strong>';
	            break;

	        case 'b2bking_field_label' :
	        	$field_label = get_post_meta($post_id,'b2bking_custom_field_field_label', true);

	            echo esc_html($field_label);
	            break;

	        case 'b2bking_field_type' :
	        	$field_type = get_post_meta($post_id,'b2bking_custom_field_field_type', true);

	            echo ucfirst(esc_html($field_type));
	            break;

	        case 'b2bking_required' :
	        	$required = get_post_meta($post_id,'b2bking_custom_field_required', true);
	        	if (intval($required) === 1){
	        		$required = 'Yes';
	        	} else {
	        		$required = 'No';
	        	}

	            echo esc_html($required);
	            break;

	        case 'b2bking_status' :
	        	$status = get_post_meta($post_id,'b2bking_custom_field_status', true);
	        	if (intval($status) === 1){
	        		$status = 'enabled';
	        	} else {
	        		$status = 'disabled';
	        	}

	            echo '<span class="b2bking_custom_role_column_status_'.esc_attr($status).'">'.esc_html(ucfirst($status)).'</span>';
	            break;
	    }
	    
	}



	// Register new post type: Customer Groups (b2bking_group)
	function b2bking_register_post_type_customer_groups() {
		// Build labels and arguments
	    $labels = array(
	        'name'                  => esc_html__( 'B2B Groups', 'b2bking' ),
	        'singular_name'         => esc_html__( 'Group', 'b2bking' ),
	        'all_items'             => esc_html__( 'Groups', 'b2bking' ),
	        'menu_name'             => esc_html__( 'Groups', 'b2bking' ),
	        'add_new'               => esc_html__( 'Create new group (Maximum 2 in Core)', 'b2bking' ),
	        'add_new_item'          => esc_html__( 'Create new customer group', 'b2bking' ),
	        'edit'                  => esc_html__( 'Edit', 'b2bking' ),
	        'edit_item'             => esc_html__( 'Edit group', 'b2bking' ),
	        'new_item'              => esc_html__( 'New group', 'b2bking' ),
	        'view_item'             => esc_html__( 'View group', 'b2bking' ),
	        'view_items'            => esc_html__( 'View groups', 'b2bking' ),
	        'search_items'          => esc_html__( 'Search groups', 'b2bking' ),
	        'not_found'             => esc_html__( 'No groups found', 'b2bking' ),
	        'not_found_in_trash'    => esc_html__( 'No groups found in trash', 'b2bking' ),
	        'parent'                => esc_html__( 'Parent group', 'b2bking' ),
	        'featured_image'        => esc_html__( 'Group image', 'b2bking' ),
	        'set_featured_image'    => esc_html__( 'Set group image', 'b2bking' ),
	        'remove_featured_image' => esc_html__( 'Remove group image', 'b2bking' ),
	        'use_featured_image'    => esc_html__( 'Use as group image', 'b2bking' ),
	        'insert_into_item'      => esc_html__( 'Insert into group', 'b2bking' ),
	        'uploaded_to_this_item' => esc_html__( 'Uploaded to this group', 'b2bking' ),
	        'filter_items_list'     => esc_html__( 'Filter groups', 'b2bking' ),
	        'items_list_navigation' => esc_html__( 'Groups navigation', 'b2bking' ),
	        'items_list'            => esc_html__( 'Groups list', 'b2bking' )
	    );
	    $args = array(
	        'label'                 => esc_html__( 'Group', 'b2bking' ),
	        'description'           => esc_html__( 'This is where you can create new customer groups', 'b2bking' ),
	        'labels'                => $labels,
	        'supports'              => array( 'title' ),
	        'hierarchical'          => false,
	        'public'                => false,
	        'show_ui'               => true,
	        'show_in_menu'          => false,
	        'menu_position'         => 105,
	        'show_in_admin_bar'     => true,
	        'show_in_nav_menus'     => false,
	        'can_export'            => true,
	        'has_archive'           => false,
	        'exclude_from_search'   => true,
	        'publicly_queryable'    => false,
	        'capability_type'       => 'product',
	        'map_meta_cap'          => true
	    );

	// Actually register the post type
	register_post_type( 'b2bking_group', $args );
	}

	// Add Groups Metaboxes
	function b2bking_groups_metaboxes($post_type) {
	    $post_types = array('b2bking_group');     //limit meta box to certain post types
       	if ( in_array( $post_type, $post_types ) ) {
       		add_meta_box(
       		    'b2bking_group_payment_shipping_metabox'
       		    ,esc_html__( 'Shipping and Payment Methods', 'b2bking' )
       		    ,array( $this, 'b2bking_group_payment_shipping_metabox_content' )
       		    ,$post_type
       		    ,'advanced'
       		    ,'high'
       		);

       		if( get_current_screen()->action !== 'add'){
	           add_meta_box(
	               'b2bking_group_users_metabox'
	               ,esc_html__( 'Users in this group', 'b2bking' )
	               ,array( $this, 'b2bking_group_users_metabox_content' )
	               ,$post_type
	               ,'advanced'
	               ,'low'
	           );
	           add_meta_box(
	               'b2bking_group_rules_metabox'
	               ,esc_html__( 'Dynamic rules applied to this group', 'b2bking' )
	               ,array( $this, 'b2bking_group_rules_metabox_content' )
	               ,$post_type
	               ,'advanced'
	               ,'low'
	           );
	       }
	    }
	}

	// Group Payment and Shipping Methods Metabox content
	function b2bking_group_payment_shipping_metabox_content(){
		?>
		<div class="b2bking_group_payment_shipping_methods_container">
			<div class="b2bking_group_payment_shipping_methods_container_element">

				<div class="b2bking_group_payment_shipping_methods_container_element_title">
					<svg class="b2bking_group_payment_shipping_methods_container_element_title_icon_shipping" xmlns="http://www.w3.org/2000/svg" width="37" height="26" fill="none" viewBox="0 0 37 26">
					  <path fill="#C4C4C4" d="M31.114 6.5h-4.205V3.25c0-1.788-1.514-3.25-3.363-3.25H3.364C1.514 0 0 1.462 0 3.25v14.625c0 1.788 1.514 3.25 3.364 3.25C3.364 23.823 5.617 26 8.409 26s5.045-2.177 5.045-4.875h10.091c0 2.698 2.254 4.875 5.046 4.875 2.792 0 5.045-2.177 5.045-4.875h1.682c.925 0 1.682-.731 1.682-1.625v-5.411c0-.699-.236-1.382-.673-1.95L32.46 7.15a1.726 1.726 0 00-1.345-.65zM8.409 22.75c-.925 0-1.682-.731-1.682-1.625S7.484 19.5 8.41 19.5c.925 0 1.682.731 1.682 1.625s-.757 1.625-1.682 1.625zM31.114 8.937L34.41 13h-7.5V8.937h4.204zM28.59 22.75c-.925 0-1.682-.731-1.682-1.625s.757-1.625 1.682-1.625c.925 0 1.682.731 1.682 1.625s-.757 1.625-1.682 1.625z"></path>
					</svg>
					<?php esc_html_e('Shipping Methods', 'b2bking'); ?>
				</div>

				<?php
				global $post; // current group

				$add_group_check = '';
				// if current screen is Add / Create new customer group, check all methods by default
				if( get_current_screen()->action === 'add'){
		        	$add_group_check = 'checked="checked"';
		        }


		        if (apply_filters('b2bking_use_zone_shipping_control', true)){
        			// list all shipping methods
        			$shipping_methods = array();
        			$zone_names = array();

        			$delivery_zones = WC_Shipping_Zones::get_zones();
        	        foreach ($delivery_zones as $key => $the_zone) {
        	            foreach ($the_zone['shipping_methods'] as $value) {
        	                array_push($shipping_methods, $value);
        	                array_push($zone_names, $the_zone['zone_name']);
        	            }
        	        }

        	        // add UPS exception
        			$shipping_methods_extra = WC()->shipping->get_shipping_methods();
        			foreach ($shipping_methods_extra as $shipping_method){
        				if ($shipping_method->id === 'wf_shipping_ups'){
        					array_push($shipping_methods, $shipping_method);
        					array_push($zone_names, 'UPS');
        				}
        			}

        	        $zone = 0;
        			foreach ($shipping_methods as $shipping_method){
        				if( $shipping_method->enabled === 'yes' ){

        					if (!metadata_exists('post', $post->ID, 'b2bking_group_shipping_method_'.esc_attr($shipping_method->id).esc_attr($shipping_method->instance_id))){
        						$checkedval = 1;
        					} else {
        						$checkedval = intval(get_post_meta($post->ID, 'b2bking_group_shipping_method_'.esc_attr($shipping_method->id).esc_attr($shipping_method->instance_id), true));
        					}


        					?>
        					<div class="b2bking_group_payment_shipping_methods_container_element_method">
        						<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
        							<?php echo esc_html($shipping_method->title).' ('.esc_html($zone_names[$zone]).')'; ?>
        						</div>
        						<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_group_shipping_method_<?php echo esc_attr($shipping_method->id).esc_attr($shipping_method->instance_id); ?>" name="b2bking_group_shipping_method_<?php echo esc_attr($shipping_method->id).esc_attr($shipping_method->instance_id); ?>" <?php checked(1, $checkedval, true); echo esc_attr($add_group_check); ?>>
        					</div>
        					<?php
        				}
        				$zone++;
        	
        			}
		        } else {
		        	// older shipping mechanism here, for cases where needed

		        	// list all shipping methods
		        	$shipping_methods = WC()->shipping->get_shipping_methods();

		        	foreach ($shipping_methods as $shipping_method){
		        		?>
		        		<div class="b2bking_group_payment_shipping_methods_container_element_method">
		        			<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
		        				<?php echo esc_html($shipping_method->method_title); ?>
		        			</div>
		        			<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_group_shipping_method_<?php echo esc_attr($shipping_method->id); ?>" name="b2bking_group_shipping_method_<?php echo esc_attr($shipping_method->id); ?>" <?php checked(1, intval(get_post_meta($post->ID, 'b2bking_group_shipping_method_'.esc_attr($shipping_method->id), true)), true); echo esc_attr($add_group_check); ?>>
		        		</div>
		        		<?php
		        	
		        	}
		        }

				
				?>

			</div>
			<div class="b2bking_group_payment_shipping_methods_container_element">
				<div class="b2bking_group_payment_shipping_methods_container_element_title">
					<svg class="b2bking_group_payment_shipping_methods_container_element_title_icon_payment" xmlns="http://www.w3.org/2000/svg" width="37" height="30" fill="none" viewBox="0 0 37 30">
					  <path fill="#C4C4C4" d="M33.3 0H3.7A3.672 3.672 0 00.018 3.7L0 25.9c0 2.053 1.647 3.7 3.7 3.7h29.6c2.053 0 3.7-1.647 3.7-3.7V3.7C37 1.646 35.353 0 33.3 0zm0 25.9H3.7V14.8h29.6v11.1zm0-18.5H3.7V3.7h29.6v3.7z"/>
					</svg>
					<?php esc_html_e('Payment Methods', 'b2bking'); ?>
				</div>

				<?php
				// list all payment methods
				$payment_methods = WC()->payment_gateways->payment_gateways();

				foreach ($payment_methods as $payment_method){
					if( $payment_method->enabled === 'yes' ){

						if (!metadata_exists('post', $post->ID, 'b2bking_group_payment_method_'.esc_attr($payment_method->id))){
							$checkedval = 1;
						} else {
							$checkedval = intval(get_post_meta($post->ID, 'b2bking_group_payment_method_'.esc_attr($payment_method->id), true));
						}

						?>
						<div class="b2bking_group_payment_shipping_methods_container_element_method">
							<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
								<?php echo esc_html($payment_method->title); ?>
							</div>
							<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_group_payment_method_<?php echo esc_attr($payment_method->id); ?>" name="b2bking_group_payment_method_<?php echo esc_attr($payment_method->id); ?>" <?php checked(1, $checkedval, true); echo esc_attr($add_group_check); ?>>
						</div>
						<?php
					}
				
				}
				?>
			</div>
		</div>

		<br /><br />

		<!-- Information panel -->
		<div class="b2bking_group_payment_shipping_information_box">
			<svg class="b2bking_group_payment_shipping_information_box_icon" xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 36 36">
			  <path fill="#358BBB" d="M18 0C8.06 0 0 8.06 0 18s8.06 18 18 18 18-8.06 18-18S27.94 0 18 0zm0 28.446a1.607 1.607 0 110-3.213 1.607 1.607 0 010 3.213zm2.527-8.819a1.941 1.941 0 00-1.241 1.8v.912a.322.322 0 01-.322.322h-1.928a.322.322 0 01-.322-.322v-.864c0-.928.27-1.844.8-2.607a4.49 4.49 0 012.093-1.643c1.366-.527 2.25-1.672 2.25-2.921 0-1.772-1.732-3.215-3.857-3.215s-3.857 1.443-3.857 3.215v.305a.322.322 0 01-.322.321h-1.928a.322.322 0 01-.322-.321v-.305c0-1.58.691-3.054 1.945-4.15C14.721 9.095 16.312 8.517 18 8.517c1.688 0 3.279.582 4.484 1.635 1.253 1.097 1.945 2.572 1.945 4.15 0 2.323-1.531 4.412-3.902 5.324z"/>
			</svg>
			<?php esc_html_e('In this panel, you can enable and disable shipping and payment methods for users in this customer group.','b2bking'); ?>
		</div>

		<?php
	}

	// Group Users Metabox Content
	function b2bking_group_users_metabox_content(){
		?>
		<div id="b2bking_metabox_product_categories_wrapper">
			<div id="b2bking_metabox_product_categories_wrapper_content">
				<div class="b2bking_metabox_product_categories_wrapper_content_line">
					<?php
					global $post;
					// get all users in the group
					$users = get_users(array(
							    'meta_key'     => 'b2bking_customergroup',
							    'meta_value'   => $post->ID,
							    'fields' => array('ID', 'user_login'),

							));
					foreach ($users as $user){
						// don't show subaccounts
						$account_type = get_user_meta($user->ID, 'b2bking_account_type', true);
						if ($account_type !== 'subaccount'){
							echo '
							<a href="'.esc_attr(get_edit_user_link($user->ID)).'" class="b2bking_metabox_product_categories_wrapper_content_category_user_link"><div class="b2bking_metabox_product_categories_wrapper_content_category_user">
								'.esc_html($user->user_login).'
							</div></a>
							';
						}
					}
					if (empty($users)){
						esc_html_e('There are no users in this group','b2bking');
					}
					?>
				</div>
			</div>
		</div>

		<?php
	}

	
	// Group Rules Metabox Content
	function b2bking_group_rules_metabox_content(){
		global $post;

		// Get all Dynamic Rules applicable to the group
		$group_rules = get_posts([
	    		'post_type' => 'b2bking_rule',
	    	  	'post_status' => 'publish',
	    	  	'numberposts' => -1,
	    	  	'meta_query'=> array(
	                'relation' => 'AND',
	                array(
	                        'key' => 'b2bking_rule_who',
	                        'value' => 'group_'.$post->ID
	                    )
	            )
	    	]);

	    if (empty($group_rules)){
			esc_html_e('There are no dynamic rules applicable to this group','b2bking');
		} else {
			?>

		    <table class="wp-list-table widefat fixed striped posts">
		    	<thead>
			    	<tr>
			    		<th scope="col" id="title" class="manage-column column-title column-primary">
			    			<span><?php esc_html_e('Name','b2bking'); ?></span>
			    		</th>
			    		<th scope="col" id="b2bking_what" class="manage-column column-b2bking_what"><?php esc_html_e('Type','b2bking'); ?></th>
			    		<th scope="col" id="b2bking_howmuch" class="manage-column column-b2bking_howmuch"><?php esc_html_e('How much','b2bking'); ?></th>
			    		<th scope="col" id="b2bking_conditions" class="manage-column column-b2bking_conditions"><?php esc_html_e('Conditions apply','b2bking'); ?></th>
			    		<th scope="col" id="b2bking_applies" class="manage-column column-b2bking_applies"><?php esc_html_e('Applies to','b2bking'); ?></th>
			    	</tr>
		    	</thead>
		    	<tbody id="the-list">
		    		<?php

		    			foreach ($group_rules as $rule){
		    				$rule_type = get_post_meta($rule->ID, 'b2bking_rule_what', true);
		    				$rule_name = $rule_type;
		    				$howmuch = get_post_meta($rule->ID, 'b2bking_rule_howmuch', true);
		    				$applies = get_post_meta($rule->ID, 'b2bking_rule_applies', true);
		    				$applies = explode ('_',$applies);
		    				switch ($applies[0]) {
		    					case 'cart':
		    					$applies = esc_html__('Cart Total','b2bking');
		    					break;

		    					case 'category':
		    					$applies = esc_html__('Category: ','b2bking').get_term( $applies[1] )->name;
		    					break;

		    					case 'product':
		    					$applies = esc_html__('Product: ','b2bking').get_the_title(intval($applies[1]));
		    					break;
		    				}
		    				$conditions = get_post_meta($rule->ID, 'b2bking_rule_conditions', true);
		    				if (empty($conditions)) {
		    					$conditions = 'no';
		    				} else {
		    					$conditions = 'yes';
		    				}
		    				$quantity_value = get_post_meta($rule->ID, 'b2bking_rule_quantity_value', true);
		    				$currency_symbol = get_woocommerce_currency_symbol();
		    				     	if (!empty($howmuch) && $rule_type !== 'free_shipping' && $rule_type !== 'hidden_price' && $rule_type !== 'tax_exemption' && $rule_type !== 'tax_exemption_user') {
		    				     		if ($rule_type === 'discount_percentage' || $rule_type === 'add_tax_percentage'){
		    				     			$howmuch = $howmuch.'%';
		    				     		} else if ($rule_type === 'discount_amount' || $rule_type === 'fixed_price' || $rule_type === 'add_tax_amount'){
		    				     			$howmuch = $currency_symbol.$howmuch;
		    				     		} else if ($rule_type === 'minimum_order' || $rule_type === 'maximum_order'){
		    				     			if ($quantity_value === 'value'){
	    					     				$howmuch = $currency_symbol.$howmuch;
	    					     			} else if ($quantity_value === 'quantity'){
	    					     				$howmuch = $howmuch.' '.esc_html__('pieces','b2bking');
	    					     			}
	    					     		} else if ($rule_type === 'required_multiple'){
	    					     			$howmuch = $howmuch.' '.esc_html__('pieces','b2bking');
	    					     		} else {
	    					     			echo esc_html($howmuch);
	    					     		}
		    				     	} else {
		    				     		echo '-';
		    				     	}
		    				switch ( $rule_name ){
		    					case 'discount_amount':
		    					$rule_name = esc_html__('Discount Amount','b2bking');
		    					break;

		    					case 'discount_percentage':
		    					$rule_name = esc_html__('Discount Percentage','b2bking');
		    					break;

		    					case 'fixed_price':
		    					$rule_name = esc_html__('Fixed Price','b2bking');
		    					break;

		    					case 'hidden_price':
		    					$rule_name = esc_html__('Hidden Price','b2bking');
		    					break;

		    					case 'free_shipping':
		    					$rule_name = esc_html__('Free Shipping','b2bking');
		    					break;

		    					case 'minimum_order':
		    					$rule_name = esc_html__('Minimum Order','b2bking');
		    					break;

		    					case 'maximum_order':
		    					$rule_name = esc_html__('Maximum Order','b2bking');
		    					break;

		    					case 'required_multiple':
		    					$rule_name = esc_html__('Required Multiple','b2bking');
		    					break;

		    					case 'tax_exemption_user':
		    					$what = esc_html__('Tax Exemption','b2bking');
		    					break;

		    					case 'tax_exemption':
		    					$rule_name = esc_html__('Zero Tax Product','b2bking');
		    					break;

		    					case 'add_tax_percentage':
		    					$rule_name = esc_html__('Add Tax / Fee (Percentage)','b2bking');
		    					break;

		    					case 'add_tax_amount':
		    					$rule_name = esc_html__('Add Tax / Fee (Amount)','b2bking');
		    					break;

		    					case 'replace_prices_quote':
		    					$rule_name = esc_html__('Replace Cart with Quote System','b2bking');
		    					break;

		    					case 'set_currency_symbol':
		    					$rule_name = esc_html__('Set Currency Symbol','b2bking');
		    					break;

		    					case 'payment_method_minimum_order':
		    					$rule_name = esc_html__('Payment Method Minimum Order','b2bking');
		    					break;

		    					
		    				}
		    				?>
				    	    <tr>
				    	    	<td class="title column-title has-row-actions column-primary page-title">
				    	    	    <strong>
				    	    	    	<a class="row-title" href="<?php echo admin_url('/post.php?post='.$rule->ID.'&action=edit');?>">
				    	    	    	<?php 
				    	    	    		if (!empty($rule->post_title)){
				    	    	    			echo esc_html($rule->post_title);
				    	    	    		} else {
				    	    	    			esc_html_e('(no title)','b2bking');
				    	    	    		} 
				    	    	    	?>
				    	    			</a>
				    	    	</strong>
			    	    	   </td>

			    	    	   <td class="b2bking_what column-b2bking_what">
			    	    	   		<span class="b2bking_dynamic_rule_column_text_<?php echo esc_attr($rule_type);?>"><?php echo esc_html($rule_name); ?></span>
			    	    	   </td>
			    	    	   <td class="b2bking_howmuch column-b2bking_howmuch"><?php echo esc_html($howmuch); ?></td>
			    	    	   <td class="b2bking_conditions column-b2bking_conditions"><?php echo esc_html($conditions); ?></td>
			    	    	   <td class="b2bking_applies column-b2bking_applies">
			    	    	   <strong><?php echo esc_html($applies); ?></strong>
			    	    	   </td>		
			    	    	</tr>

		    				<?php
		    			}
		    		?>
		    	</tbody>
		    </table>

			<?php
		}
	}

	// Save Groups Metabox Content
	function b2bking_save_groups_metaboxes($post_id){
		if (isset($_POST['_inline_edit'])){
			return;
		}
		if (isset($_REQUEST['bulk_edit'])){
		    return;
		}
		$postobj = get_post($post_id);
		if ( $postobj->post_status === 'trash' ) {
	        return;
	    }
	    if (isset($_GET['action'])) {
	    	if ($_GET['action'] === 'untrash'){
	    		return;
	    	}
	    }

		$p = get_post($post_id);

		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX) || ($p->post_status === 'auto-draft')) { 
			return;
		}

		if (get_post_type($post_id) === 'b2bking_group'){

			if (apply_filters('b2bking_use_wp_roles', false)){
				/** 
				* WP Roles Support
				* add_role adds role if it does not exist
				* if it does exist, change its display name to title
				*/

				// clean auto unpublished roles
				$roles = get_option( 'wp_user_roles' );
				if (is_array($roles)){
					foreach ($roles as $index=>$role){
						$rolepostid = explode('_', $index);
						if (isset($rolepostid[2])){
							$rolepostid = $rolepostid[2];
							if (get_post_status($rolepostid) !== 'publish'){
								// delete role
								remove_role('b2bking_role_'.$rolepostid);
							}
						}
					}
				}
				
				if (add_role('b2bking_role_'.$post_id, sanitize_text_field(get_the_title($post_id)), array( 'read' => true)) === null){
					global $wpdb;
					$prefix = $wpdb->prefix;
					
					$val = get_option( 'wp_user_roles' );
					$val['b2bking_role_'.$post_id]['name'] = sanitize_text_field(get_the_title($post_id));
					update_option( 'wp_user_roles', $val );

					if (get_option($prefix.'user_roles', 0) !== 0){
						$val = get_option( $prefix.'user_roles' );
						$val['b2bking_role_'.$post_id]['name'] = sanitize_text_field(get_the_title($post_id));
						update_option( $prefix.'user_roles', $val );
					}
					
				};
			}


			// Save Payment methods and Shipping Methods
			if (apply_filters('b2bking_use_zone_shipping_control', true)){
				$shipping_methods = array();

				$delivery_zones = WC_Shipping_Zones::get_zones();
		        foreach ($delivery_zones as $key => $the_zone) {
		            foreach ($the_zone['shipping_methods'] as $value) {
		                array_push($shipping_methods, $value);
		            }
		        }

	            // add UPS exception
				$shipping_methods_extra = WC()->shipping->get_shipping_methods();
				foreach ($shipping_methods_extra as $shipping_method){
					if ($shipping_method->id === 'wf_shipping_ups'){
						array_push($shipping_methods, $shipping_method);
					}
				}

				foreach ($shipping_methods as $shipping_method){
					$method = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_group_shipping_method_'.$shipping_method->id.$shipping_method->instance_id));
					if ($method !== NULL ){
						update_post_meta( $post_id, 'b2bking_group_shipping_method_'.$shipping_method->id.$shipping_method->instance_id, $method);
					}
				}
			} else {
				// older shipping mechanism here for cases where needed

				$shipping_methods = WC()->shipping->get_shipping_methods();

				foreach ($shipping_methods as $shipping_method){
					$method = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_group_shipping_method_'.$shipping_method->id));
					if ($method !== NULL ){
						update_post_meta( $post_id, 'b2bking_group_shipping_method_'.$shipping_method->id, $method);
					}
				}
			}

			$payment_methods = WC()->payment_gateways->payment_gateways();

			foreach ($payment_methods as $payment_method){
				$method = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_group_payment_method_'.$payment_method->id));
				if ($method !== NULL ){
					update_post_meta( $post_id, 'b2bking_group_payment_method_'.$payment_method->id, $method);
				}
			}
		}

		// get number of groups
		$groups = get_posts(array( 
			'post_type' => 'b2bking_group',
			'post_status'=>'publish',
			'numberposts' => -1,
			'fields' => 'ids',
		));
		if (count($groups) > 2){
			//wp_delete_post($post_id);
			wp_redirect(admin_url('edit.php?post_type=b2bking_group'));
		}

	}


	// Register new post type: Dynamic Rules (b2bking_rule)
	function b2bking_register_post_type_dynamic_rules() {
		// Build labels and arguments
	    $labels = array(
	        'name'                  => esc_html__( 'Dynamic Rules', 'b2bking' ),
	        'singular_name'         => esc_html__( 'Rule', 'b2bking' ),
	        'all_items'             => esc_html__( 'Rules', 'b2bking' ),
	        'menu_name'             => esc_html__( 'Rules', 'b2bking' ),
	        'add_new'               => esc_html__( 'Create new rule', 'b2bking' ),
	        'add_new_item'          => esc_html__( 'Create new rule', 'b2bking' ),
	        'edit'                  => esc_html__( 'Edit', 'b2bking' ),
	        'edit_item'             => esc_html__( 'Edit rule', 'b2bking' ),
	        'new_item'              => esc_html__( 'New rule', 'b2bking' ),
	        'view_item'             => esc_html__( 'View rule', 'b2bking' ),
	        'view_items'            => esc_html__( 'View rules', 'b2bking' ),
	        'search_items'          => esc_html__( 'Search rules', 'b2bking' ),
	        'not_found'             => esc_html__( 'No rules found', 'b2bking' ),
	        'not_found_in_trash'    => esc_html__( 'No rules found in trash', 'b2bking' ),
	        'parent'                => esc_html__( 'Parent rule', 'b2bking' ),
	        'featured_image'        => esc_html__( 'Rule image', 'b2bking' ),
	        'set_featured_image'    => esc_html__( 'Set rule image', 'b2bking' ),
	        'remove_featured_image' => esc_html__( 'Remove rule image', 'b2bking' ),
	        'use_featured_image'    => esc_html__( 'Use as rule image', 'b2bking' ),
	        'insert_into_item'      => esc_html__( 'Insert into rule', 'b2bking' ),
	        'uploaded_to_this_item' => esc_html__( 'Uploaded to this rule', 'b2bking' ),
	        'filter_items_list'     => esc_html__( 'Filter rules', 'b2bking' ),
	        'items_list_navigation' => esc_html__( 'Rules navigation', 'b2bking' ),
	        'items_list'            => esc_html__( 'Dynamic rules list', 'b2bking' )
	    );
	    $args = array(
	        'label'                 => esc_html__( 'Dynamic Rules', 'b2bking' ),
	        'description'           => esc_html__( 'This is where you can create dynamic rules', 'b2bking' ),
	        'labels'                => $labels,
	        'supports'              => array( 'title' ),
	        'hierarchical'          => false,
	        'public'                => false,
	        'show_ui'               => true,
	        'show_in_menu'          => 'b2bkingcore',
	        'menu_position'         => 123,
	        'show_in_admin_bar'     => true,
	        'show_in_nav_menus'     => false,
	        'can_export'            => true,
	        'has_archive'           => false,
	        'exclude_from_search'   => true,
	        'publicly_queryable'    => false,
	        'capability_type'       => 'product',
	        'map_meta_cap'          => true
	    );

	// Actually register the post type
	register_post_type( 'b2bking_rule', $args );
	}

	// Add Rule Details Metabox to Rules
	function b2bking_rules_metaboxes($post_type) {
	    $post_types = array('b2bking_rule');     //limit meta box to certain post types
       	if ( in_array( $post_type, $post_types ) ) {
	           add_meta_box(
	               'b2bking_rule_details_metabox'
	               ,esc_html__( 'Rule Details', 'b2bking' )
	               ,array( $this, 'b2bking_rule_details_metabox_content' )
	               ,$post_type
	               ,'advanced'
	               ,'high'
	           );
	       }
	}
	
	// Rule Details Metabox Content
	function b2bking_rule_details_metabox_content(){
		global $post;
		?>
		<div class="b2bking_dynamic_rule_metabox_content_container">
			<div class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Rule type:','b2bking'); ?></div>
				<select id="b2bking_rule_select_what" name="b2bking_rule_select_what">
					<?php
					// if page not "Add new", get selected
					$selected = '';
					if( get_current_screen()->action !== 'add'){
			        	$selected = esc_html(get_post_meta($post->ID, 'b2bking_rule_what', true));
			        }
					?>
					<optgroup label="<?php esc_attr_e('Discounts & Pricing', 'b2bking'); ?>"> 
						<option value="discount_amount" <?php selected('discount_amount',$selected,true); ?>><?php esc_html_e('Discount Amount','b2bking'); ?></option>
						<option value="discount_percentage" <?php selected('discount_percentage',$selected,true); ?>><?php esc_html_e('Discount Percentage','b2bking'); ?></option>
						<option disabled value="raise_price" <?php selected('raise_price',$selected,true); ?>><?php esc_html_e('Raise Price (Percentage) (Premium)','b2bking'); ?></option>
						<option disabled value="bogo_discount" <?php selected('bogo_discount',$selected,true); ?>><?php esc_html_e('Buy X Get 1 Free (Premium)','b2bking'); ?></option>
						<option disabled value="fixed_price" <?php selected('fixed_price',$selected,true); ?>><?php esc_html_e('Fixed Price (Premium)','b2bking'); ?></option>
						<option disabled value="hidden_price" <?php selected('hidden_price',$selected,true); ?>><?php esc_html_e('Hidden Price (Premium)','b2bking'); ?></option>
						<option disabled value="tiered_price" <?php selected('tiered_price',$selected,true); ?>><?php esc_html_e('Tiered Price (Premium)','b2bking'); ?></option>
					</optgroup>
					<optgroup label="<?php esc_attr_e('Order Rules', 'b2bking'); ?>"> 
						<option disabled value="free_shipping" <?php selected('free_shipping',$selected,true); ?>><?php esc_html_e('Free Shipping (Premium)','b2bking'); ?></option>
						<option disabled value="minimum_order" <?php selected('minimum_order',$selected,true); ?>><?php esc_html_e('Minimum Order (Premium)','b2bking'); ?></option>
						<option disabled value="maximum_order" <?php selected('maximum_order',$selected,true); ?>><?php esc_html_e('Maximum Order (Premium)','b2bking'); ?></option>
						<option disabled value="required_multiple" <?php selected('required_multiple',$selected,true); ?>><?php esc_html_e('Required Multiple (Quantity Step) (Premium)','b2bking'); ?></option>
						<option disabled value="unpurchasable" <?php selected('unpurchasable',$selected,true); ?>><?php esc_html_e('Non-Purchasable (Premium)','b2bking'); ?></option>
					<optgroup label="<?php esc_attr_e('Taxes', 'b2bking'); ?>"> 
						<option disabled value="tax_exemption_user" <?php selected('tax_exemption_user',$selected,true); ?>><?php esc_html_e('Tax Exemption (Premium)','b2bking'); ?></option>
						<option disabled value="tax_exemption" <?php selected('tax_exemption',$selected,true); ?>><?php esc_html_e('Zero Tax Product (Premium)','b2bking'); ?></option>
						<option disabled value="add_tax_percentage" <?php selected('add_tax_percentage',$selected,true); ?>><?php esc_html_e('Add Tax / Fee (Percentage) (Premium)','b2bking'); ?></option>
						<option disabled value="add_tax_amount" <?php selected('add_tax_amount',$selected,true); ?>><?php esc_html_e('Add Tax / Fee (Amount) (Premium)','b2bking'); ?></option>
					</optgroup>
					<optgroup label="<?php esc_attr_e('Advanced Rules', 'b2bking'); ?>"> 
						<option disabled value="replace_prices_quote" <?php selected('replace_prices_quote',$selected,true); ?>><?php esc_html_e('Replace Cart with Quote System (Premium)','b2bking'); ?></option>
						<option disabled value="replace_prices_quote" <?php selected('replace_prices_quote',$selected,true); ?>><?php esc_html_e('Quotes on Specific Products (Premium)','b2bking'); ?></option>
						<option disabled value="set_currency_symbol" <?php selected('set_currency_symbol',$selected,true); ?>><?php esc_html_e('Set Currency (Premium)','b2bking'); ?></option>
						<option disabled value="payment_method_minmax_order" <?php selected('payment_method_minmax_order',$selected,true); ?>><?php esc_html_e('Payment Method Min / Max Order (Premium)','b2bking'); ?></option>
						<option disabled value="payment_method_discount" <?php selected('payment_method_discount',$selected,true); ?>><?php esc_html_e('Payment Method Discount / Surcharge (Premium)','b2bking'); ?></option>
						<option disabled value="rename_purchase_order" <?php selected('rename_purchase_order',$selected,true); ?>><?php esc_html_e('Rename Payment Method (Premium)','b2bking'); ?></option>
					</optgroup>
					
				</select>
			</div>
			<div class="b2bking_rule_select_container" id="b2bking_container_applies">
				<div class="b2bking_rule_label"><?php esc_html_e('Applies to:','b2bking'); ?></div>
				
				<select id="b2bking_rule_select_applies" name="b2bking_rule_select_applies">
					<?php
					// if page not "Add new", get selected
					$selected = '';
					if( get_current_screen()->action !== 'add'){
			        	$selected = esc_html(get_post_meta($post->ID, 'b2bking_rule_applies', true));
			        	$rule_replaced = esc_html(get_post_meta($post->ID, 'b2bking_rule_replaced', true));
			        	if ($rule_replaced === 'yes' && $selected === 'multiple_options'){
			        		$selected = 'replace_ids';
			        	}
			        }
					?>
					<optgroup label="<?php esc_attr_e('Cart', 'b2bking'); ?>" id="b2bking_cart_total_optgroup" >
						<option value="cart_total" <?php selected('cart_total',$selected,true); ?>><?php esc_html_e('Cart Total ( / all products)','b2bking'); ?></option>
							<option value="multiple_options" <?php selected('multiple_options',$selected,true); ?>><?php esc_html_e('Select multiple products & categories','b2bking'); ?></option>
							<?php
							if (intval(get_option( 'b2bking_replace_product_selector_setting', 0 )) === 1){
								?>
								<option value="replace_ids" <?php selected('replace_ids',$selected,true); ?>><?php esc_html_e('Product or Variation ID(s)','b2bking'); ?></option>
								<?php
							}
							?>
					</optgroup>
					<optgroup label="<?php esc_attr_e('Product Categories', 'b2bking'); ?>">
						<?php
						// Get all categories
						$categories = get_terms( array( 'taxonomy' => 'product_cat',	) );
						foreach ($categories as $category){
							echo '<option value="category_'.esc_attr($category->term_id).'" '.selected('category_'.$category->term_id, $selected,false).'>'.esc_html($category->name).'</option>';
						}
						?>
					</optgroup>
					<?php 
					if (intval(get_option( 'b2bking_replace_product_selector_setting', 0 )) === 0){
					?>
					<optgroup label="<?php esc_attr_e('Products (individual)', 'b2bking'); ?>">
						<?php
						// Get all products
						$products = get_posts(array( 
							'post_type' => 'product',
							'post_status'=>'publish',
							'numberposts' => -1,
							'fields' => 'ids',
						));

						foreach ($products as $product){
							// skip 'Offer' product
							$offer_id = intval(get_option('b2bking_offer_product_id_setting', 0));
							if (intval($product) !== $offer_id && intval($product) !== 3225464){ //3225464 is deprecated
								$productobj = wc_get_product($product);
								echo '<option value="product_'.esc_attr($product).'" '.selected('product_'.$product,$selected,false).'>'.esc_html($productobj->get_name()).'</option>';
							}
						}
						?>
					</optgroup>
					<optgroup label="<?php esc_attr_e('Products (Individual Variations)', 'b2bking'); ?>">
						<?php
						// Get all products
						$products = get_posts(array( 
							'post_type' => 'product_variation',
							'post_status'=>'publish',
							'numberposts' => -1,
							'fields' => 'ids',
						));

						foreach ($products as $product){
							// skip 'Offer' product
							$productobj = wc_get_product($product);
							echo '<option value="product_'.esc_attr($product).'" '.selected('product_'.$product,$selected,false).'>'.esc_html($productobj->get_name()).'</option>';
						}
						?>
					</optgroup>
					<?php }?>
					<optgroup label="<?php esc_attr_e('Tax Options', 'b2bking'); ?>">
						<option id="b2bking_one_time" value="one_time" <?php selected('one_time',$selected,true); ?>><?php esc_html_e('One Time Fee / Tax','b2bking'); ?></option>
					</optgroup>
				</select>
			</div>
			<div class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('For who:','b2bking'); ?></div>
				<select id="b2bking_rule_select_who" name="b2bking_rule_select_who">
					<?php
					// if page not "Add new", get selected
					$selected = '';
					if( get_current_screen()->action !== 'add'){
			        	$selected = esc_html(get_post_meta($post->ID, 'b2bking_rule_who', true));
			        }
					?>
					<optgroup label="<?php esc_attr_e('Everyone', 'b2bking'); ?>">
						<option value="everyone_registered" <?php selected('everyone_registered',$selected,true); ?>><?php esc_html_e('All registered users','b2bking'); ?></option>
						<option value="everyone_registered_b2b" <?php selected('everyone_registered_b2b',$selected,true); ?>><?php esc_html_e('All registered B2B users','b2bking'); ?></option>
						<option value="everyone_registered_b2c" <?php selected('everyone_registered_b2c',$selected,true); ?>><?php esc_html_e('All registered B2C users','b2bking'); ?></option>
						<option value="user_0" <?php selected('user_0',$selected,true); ?>><?php esc_html_e('All guest users (logged out)','b2bking'); ?></option>
						<option value="multiple_options" <?php selected('multiple_options',$selected,true); ?>><?php esc_html_e('Select multiple options','b2bking'); ?></option>

					</optgroup>
					<optgroup label="<?php esc_attr_e('B2B Groups', 'b2bking'); ?>">
						<?php
						// Get all groups
						$groups = get_posts( array( 'post_type' => 'b2bking_group','post_status'=>'publish','numberposts' => -1) );
						foreach ($groups as $group){
							echo '<option value="group_'.esc_attr($group->ID).'" '.selected('group_'.$group->ID,$selected,false).'>'.esc_html($group->post_title).'</option>';
						}
						?>
					</optgroup>
					<?php if (intval(get_option( 'b2bking_hide_users_dynamic_rules_setting', 0 )) === 0){ ?>
					<optgroup label="<?php esc_attr_e('Users (individual)', 'b2bking'); ?>">
						<?php 
							// if B2B/B2C Hybrid, show only B2B users
							if(get_option( 'b2bking_plugin_status_setting', 'b2b' ) === 'hybrid'){
								$users = get_users(array(
								    'meta_key'     => 'b2bking_b2buser',
								    'meta_value'   => 'yes',
								    'fields'=> array('ID', 'user_login'),
								));

							} else {
								$users = get_users(array(
								    'fields'=> array('ID', 'user_login'),
								));
							}

							foreach ($users as $user){
								// do not show subaccounts
								$account_type = get_user_meta($user->ID, 'b2bking_account_type', true);
								if ($account_type !== 'subaccount'){
									echo '<option value="user_'.esc_attr($user->ID).'" '.selected('user_'.$user->ID,$selected,false).'>'.esc_html($user->user_login).'</option>';
								}
							}
						?>
					</optgroup>
					<?php } ?>
				</select>
			</div>
			<div id="b2bking_container_countries" class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Countries (multiple select):','b2bking'); ?></div>
				<select id="b2bking_rule_select_countries" name="b2bking_rule_select_countries[]" multiple>
					<?php
					// if page not "Add new", get selected options
					$selected_options = array();
					if( get_current_screen()->action !== 'add'){
			        	$selected_options_string = get_post_meta($post->ID, 'b2bking_rule_countries', true);
			        	$selected_options = explode(',', $selected_options_string);
			        }
			        // get countries list
			        $countries_object = new WC_Countries;
			        $countries_list = $countries_object -> get_countries();
			        $countries_list_eu = array('AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE');
					?>
					<optgroup label="<?php esc_html_e('EU Countries', 'b2bking'); ?>">
						<?php
						foreach($countries_list_eu as $eu_country){
							$country_is_selected = 'no';
							foreach ($selected_options as $selected_option){
								if ($selected_option === $eu_country){
									$country_is_selected = 'yes';
								}
							}
							?>
							<option value="<?php echo esc_attr($eu_country); ?>" <?php selected('yes',$country_is_selected,true); ?>><?php echo esc_html($countries_list[$eu_country]);?></option>
							<?php
							unset ($countries_list[$eu_country]);
						}
						?>
					</optgroup>
					<optgroup label="<?php esc_html_e('All Other Countries', 'b2bking'); ?>">
						<?php
						foreach($countries_list as $index => $country){
							$country_is_selected = 'no';
							foreach ($selected_options as $selected_option){
								if ($selected_option === $index){
									$country_is_selected = 'yes';
								}
							}
							?>
							<option value="<?php echo esc_attr($index); ?>" <?php selected('yes',$country_is_selected,true); ?>><?php echo esc_html($country);?></option>
							<?php
						}
						?>
					</optgroup>
				</select>
			</div>
			<div id="b2bking_container_requires"  class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Requires:','b2bking'); ?></div>
				<select id="b2bking_rule_select_requires" name="b2bking_rule_select_requires" >
					<?php
					// if page not "Add new", get selected
					$selected = '';
					if( get_current_screen()->action !== 'add'){
			        	$selected = esc_html(get_post_meta($post->ID, 'b2bking_rule_requires', true));
			        }
					?>
					<option value="nothing" <?php selected('nothing',$selected,true); ?>><?php esc_html_e('Nothing','b2bking'); ?></option>
					<option value="validated_vat" <?php selected('validated_vat',$selected,true); ?>><?php esc_html_e('VIES-Validated VAT ID','b2bking'); ?></option>
				</select>
			</div>
			<div id="b2bking_container_showtax"  class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Pay TAX in Cart:','b2bking'); ?></div>
				<select id="b2bking_rule_select_showtax" name="b2bking_rule_select_showtax" >
					<?php
					// if page not "Add new", get selected
					$selected = '';
					if( get_current_screen()->action !== 'add'){
			        	$selected = esc_html(get_post_meta($post->ID, 'b2bking_rule_showtax', true));
			        }
					?>
					<option value="no" <?php selected('no',$selected,true); ?>><?php esc_html_e('No','b2bking'); ?></option>
					<option value="yes" <?php selected('yes',$selected,true); ?>><?php esc_html_e('Yes','b2bking'); ?></option>
					<option value="display_only" <?php selected('display_only',$selected,true); ?>><?php esc_html_e('Display Only (Withholding Tax)','b2bking'); ?></option>
				</select>
			</div>
			<div id="b2bking_container_quantity_value" class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Quantity/Value:','b2bking'); ?></div>
				<select id="b2bking_rule_select_quantity_value" name="b2bking_rule_select_quantity_value">
					<?php
					// if page not "Add new", get selected
					$selected = '';
					if( get_current_screen()->action !== 'add'){
			        	$selected = esc_html(get_post_meta($post->ID, 'b2bking_rule_quantity_value', true));
			        }
					?>
					<option value="quantity" <?php selected('quantity',$selected,true); ?>><?php esc_html_e('Quantity','b2bking'); ?></option>
					<option value="value" <?php selected('value',$selected,true); ?>><?php esc_html_e('Value','b2bking'); ?></option>
				</select>
			</div>
			<div id="b2bking_container_paymentmethods" class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Payment Method','b2bking'); ?></div>
				<select name="b2bking_rule_paymentmethod" id="b2bking_rule_paymentmethod">
					<?php
						$selected_method = get_post_meta($post->ID,'b2bking_rule_paymentmethod', true);
						// list all payment methods
						$payment_methods = WC()->payment_gateways->payment_gateways();
						foreach ($payment_methods as $payment_method){
							echo '<option value="'.$payment_method->id.'" '.selected($payment_method->id,$selected_method,false).'>'.$payment_method->title.'</option>';
						}
					?>
				</select>
			</div>
			<div id="b2bking_container_howmuch" class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('How much:','b2bking'); ?></div>
				<input type="number" min="0.001" step="0.00001" name="b2bking_rule_select_howmuch" id="b2bking_rule_select_howmuch" value="<?php echo esc_attr(get_post_meta($post->ID, 'b2bking_rule_howmuch', true)); ?>">
			</div>
			<div id="b2bking_container_currency" class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Currency symbol','b2bking'); ?></div>
				<select name="b2bking_rule_currency" id="b2bking_rule_currency">
					<?php
					$symbols = get_woocommerce_currency_symbols();
					$selected_symbol = get_post_meta($post->ID,'b2bking_rule_currency', true);
					foreach ($symbols as $symbolletters=>$symbol){
						echo '<option value="'.$symbolletters.'" '.selected($symbolletters,$selected_symbol,false).'>'.$symbolletters.' -> '.$symbol.'</option>';
					}
					?>
				</select>
			</div>
			
			<div id="b2bking_container_discountname" class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Disc. name (optional):','b2bking'); ?></div>
				<input type="text" name="b2bking_rule_select_discountname" id="b2bking_rule_select_discountname" value="<?php echo esc_attr(get_post_meta($post->ID, 'b2bking_rule_discountname', true)); ?>">
			</div>
			<div id="b2bking_container_taxname" class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Tax/Fee name:','b2bking'); ?></div>
				<input type="text" name="b2bking_rule_select_taxname" id="b2bking_rule_select_taxname" value="<?php echo esc_attr(get_post_meta($post->ID, 'b2bking_rule_taxname', true)); ?>">
			</div>
			<div id="b2bking_container_tax_shipping" class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Include shipping cost:','b2bking'); ?></div>
				<select id="b2bking_rule_select_tax_shipping" name="b2bking_rule_select_tax_shipping">
					<?php
					// if page not "Add new", get selected
					$selected = '';
					if( get_current_screen()->action !== 'add'){
			        	$selected = esc_html(get_post_meta($post->ID, 'b2bking_rule_tax_shipping', true));
			        }
					?>
					<option value="no" <?php selected('no',$selected,true); ?>><?php esc_html_e('No','b2bking'); ?></option>
					<option value="yes" <?php selected('yes',$selected,true); ?>><?php esc_html_e('Yes','b2bking'); ?></option>
				</select>
			</div>
			<div id="b2bking_container_tax_shipping_rate" class="b2bking_rule_select_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Shipping tax rate (%):','b2bking'); ?></div>
				<input type="text" name="b2bking_rule_select_tax_shipping_rate" value="<?php echo esc_attr(get_post_meta($post->ID, 'b2bking_rule_tax_shipping_rate', true));?>">
			</div>

			

			<br /><br />
			<?php
			if (intval(get_option( 'b2bking_replace_product_selector_setting', 0 )) === 1){
				?>
			<div id="b2bking_rule_select_applies_replaced_container" >
				<div class="b2bking_rule_label"><?php esc_html_e('Product or Variation ID(s) (comma-separated):','b2bking'); ?></div>
				<?php
				$replaced_content = get_post_meta($post->ID,'b2bking_rule_applies_multiple_options', true);
				$replaced_content_array = explode(',', $replaced_content);
				$replaced_content_string = '';
				foreach ($replaced_content_array as $element){
					$replaced_content_string.= substr($element, 8).',';
				}
				// remove last comma
				$replaced_content_string = substr($replaced_content_string, 0, -1);
				?>
				<input type="text" id="b2bking_rule_select_applies_replaced" name="b2bking_rule_select_applies_replaced" value="<?php echo esc_attr($replaced_content_string);?>">
			</div>
				<?php
			}
			?>
			
			<div id="b2bking_select_multiple_product_categories_selector" >
				<div class="b2bking_select_multiple_products_categories_title">
					<?php esc_html_e('Select multiple products and categories','b2bking'); ?>
				</div>
				<select class="b2bking_select_multiple_product_categories_selector_select" name="b2bking_select_multiple_product_categories_selector_select[]" multiple>
					<?php
					// if page not "Add new", get selected options
					$selected_options = array();
					if( get_current_screen()->action !== 'add'){
			        	$selected_options_string = get_post_meta($post->ID, 'b2bking_rule_applies_multiple_options', true);
			        	$selected_options = explode(',', $selected_options_string);
			        }
			        ?>
			        <optgroup label="<?php esc_attr_e('Product Categories', 'b2bking'); ?>">
			        	<?php
			        	// Get all categories
			        	$categories = get_terms( array( 'taxonomy' => 'product_cat',	) );
			        	foreach ($categories as $category){
    		            	$is_selected = 'no';
    		            	foreach ($selected_options as $selected_option){
								if ($selected_option === ('category_'.$category->term_id )){
									$is_selected = 'yes';
								}
							}
			        		echo '<option value="category_'.esc_attr($category->term_id).'" '.selected('yes',$is_selected, true).'>'.esc_html($category->name).'</option>';
			        	}
			        	?>
			        </optgroup>
			        <?php 
			        if (intval(get_option( 'b2bking_replace_product_selector_setting', 0 )) === 0){
			        ?>
			        <optgroup label="<?php esc_attr_e('Products (individual)', 'b2bking'); ?>">
			        	<?php
			        	// Get all products
			        	$products = get_posts( array(
			        		'post_type' => 'product',
			        		'post_status'=>'publish',
			        		'numberposts' => -1,
			        		'fields' => 'ids',
			        	));

			        	foreach ($products as $product){
    		            	$is_selected = 'no';
    		            	foreach ($selected_options as $selected_option){
								if ($selected_option === ('product_'.trim($product) )){
									$is_selected = 'yes';
								}
							}
			        		// skip 'Offer' products
			        		$offer_id = intval(get_option('b2bking_offer_product_id_setting', 0));
			        		if (intval($product) !== $offer_id && intval($product) !== 3225464){ //3225464 is deprecated
			        			$productobj = wc_get_product($product);
			        			echo '<option value="product_'.esc_attr($product).'" '.selected('yes',$is_selected, true).'>'.esc_html($productobj->get_name()).'</option>';
			        		}
			        	}
			        	?>
			        </optgroup>
			        <optgroup label="<?php esc_attr_e('Products (individual variations)', 'b2bking'); ?>">
			        	<?php
			        	// Get all products
			        	$products = get_posts( array( 
			        		'post_type' => 'product_variation',
			        		'post_status'=>'publish',
			        		'numberposts' => -1,
			        		'fields' => 'ids'
			        	));

			        	foreach ($products as $product){
    		            	$is_selected = 'no';
    		            	foreach ($selected_options as $selected_option){
								if ($selected_option === ('product_'.$product )){
									$is_selected = 'yes';
								}
							}
							$productobj = wc_get_product($product);
			        		echo '<option value="product_'.esc_attr($product).'" '.selected('yes',$is_selected, true).'>'.esc_html($productobj->get_name()).'</option>';
			        		}
			        	?>
			        </optgroup>
			        <?php } ?>
				</select>

			</div>
		

			<div id="b2bking_select_multiple_users_selector" >
				<div class="b2bking_select_multiple_products_categories_title">
					<?php esc_html_e('Select multiple options','b2bking'); ?>
				</div>
				<select class="b2bking_select_multiple_product_categories_selector_select" name="b2bking_select_multiple_users_selector_select[]" multiple>
					<?php
					// if page not "Add new", get selected options
					$selected_options = array();
					if( get_current_screen()->action !== 'add'){
			        	$selected_options_string = get_post_meta($post->ID, 'b2bking_rule_who_multiple_options', true);
			        	$selected_options = explode(',', $selected_options_string);
			        }
					?>
					<optgroup label="<?php esc_attr_e('Everyone', 'b2bking'); ?>">
						<?php
		            	$is_selected_everyone_registered = 'no';
		            	$is_selected_everyone_registered_b2b = 'no';
		            	$is_selected_everyone_registered_b2c = 'no';
		            	$is_selected_guests = 'no';
		            	foreach ($selected_options as $selected_option){
							if ($selected_option === ('everyone_registered')){
								$is_selected_everyone_registered = 'yes';
							}
							if ($selected_option === ('everyone_registered_b2b')){
								$is_selected_everyone_registered_b2b = 'yes';
							}
							if ($selected_option === ('everyone_registered_b2c')){
								$is_selected_everyone_registered_b2c = 'yes';
							}
							if ($selected_option === ('user_0')){
								$is_selected_guests = 'yes';
							}
						}
						?>
						<option value="everyone_registered" <?php selected('yes',$is_selected_everyone_registered,true); ?>><?php esc_html_e('All registered users','b2bking'); ?></option>
						<option value="everyone_registered_b2b" <?php selected('yes',$is_selected_everyone_registered_b2b,true); ?>><?php esc_html_e('All registered B2B users','b2bking'); ?></option>
						<option value="everyone_registered_b2c" <?php selected('yes',$is_selected_everyone_registered_b2c,true); ?>><?php esc_html_e('All registered B2C users','b2bking'); ?></option>
						<option value="user_0" <?php selected('yes',$is_selected_guests,true); ?>><?php esc_html_e('All guest users (logged out)','b2bking'); ?></option>

					</optgroup>
					<optgroup label="<?php esc_attr_e('B2B Groups', 'b2bking'); ?>">
						<?php
						// Get all groups
						$groups = get_posts( array( 'post_type' => 'b2bking_group','post_status'=>'publish','numberposts' => -1) );
						foreach ($groups as $group){
    		            	$is_selected = 'no';
    		            	foreach ($selected_options as $selected_option){
								if ($selected_option === ('group_'.$group->ID )){
									$is_selected = 'yes';
								}
							}
							echo '<option value="group_'.esc_attr($group->ID).'" '.selected('yes',$is_selected,false).'>'.esc_html($group->post_title).'</option>';
						}
						?>
					</optgroup>
					<?php if (intval(get_option( 'b2bking_hide_users_dynamic_rules_setting', 0 )) === 0){ ?>
					<optgroup label="<?php esc_attr_e('Users (individual)', 'b2bking'); ?>">
						<?php 
							// if B2B/B2C Hybrid, show only B2B users
							if(get_option( 'b2bking_plugin_status_setting', 'b2b' ) === 'hybrid'){
								$users = get_users(array(
								    'meta_key'     => 'b2bking_b2buser',
								    'meta_value'   => 'yes',
								    'fields'=> array('ID', 'user_login'),
								));
							} else {
								$users = get_users(array(
								    'fields'=> array('ID', 'user_login'),
								));
							}
							foreach ($users as $user){
	    		            	$is_selected = 'no';
	    		            	foreach ($selected_options as $selected_option){
									if ($selected_option === ('user_'.$user->ID )){
										$is_selected = 'yes';
									}
								}
								// do not show subaccounts
								$account_type = get_user_meta($user->ID, 'b2bking_account_type', true);
								if ($account_type !== 'subaccount'){
									echo '<option value="user_'.esc_attr($user->ID).'" '.selected('yes',$is_selected,false).'>'.esc_html($user->user_login).'</option>';
								}
							}
						?>
					</optgroup>
					<?php } ?>
				</select>

			</div>

		
			<div class="b2bking_rule_label_discount"><?php esc_html_e('Additional options:','b2bking'); ?></div>
			<div class="b2bking_dynamic_rule_discount_show_everywhere_checkbox_container">
				<div class="b2bking_dynamic_rule_discount_show_everywhere_checkbox_name">
					<?php esc_html_e('Discounted Price Becomes "Sale Price" (Recommended!)','b2bking'); ?>
				</div>
				<input type="checkbox" value="1" id="b2bking_dynamic_rule_discount_show_everywhere_checkbox_input" name="b2bking_dynamic_rule_discount_show_everywhere_checkbox_input" <?php checked(1,get_post_meta($post->ID,'b2bking_rule_discount_show_everywhere',true),true); ?>>
			</div>
			<!-- Information panel -->
			<div class="b2bking_discount_options_information_box">
				<svg class="b2bking_group_payment_shipping_information_box_icon" xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 36 36">
				  <path fill="#358BBB" d="M18 0C8.06 0 0 8.06 0 18s8.06 18 18 18 18-8.06 18-18S27.94 0 18 0zm0 28.446a1.607 1.607 0 110-3.213 1.607 1.607 0 010 3.213zm2.527-8.819a1.941 1.941 0 00-1.241 1.8v.912a.322.322 0 01-.322.322h-1.928a.322.322 0 01-.322-.322v-.864c0-.928.27-1.844.8-2.607a4.49 4.49 0 012.093-1.643c1.366-.527 2.25-1.672 2.25-2.921 0-1.772-1.732-3.215-3.857-3.215s-3.857 1.443-3.857 3.215v.305a.322.322 0 01-.322.321h-1.928a.322.322 0 01-.322-.321v-.305c0-1.58.691-3.054 1.945-4.15C14.721 9.095 16.312 8.517 18 8.517c1.688 0 3.279.582 4.484 1.635 1.253 1.097 1.945 2.572 1.945 4.15 0 2.323-1.531 4.412-3.902 5.324z"/>
				</svg>
				<?php 
					esc_html_e('Check this box to set the discounted price as the sale price. Disable it to show discounts in cart subtotal. Incompatible with Value Conditions. ','b2bking');
					echo '&nbsp;<a target="_blank" href="https://woocommerce-b2b-plugin.com/docs/improved-discounts-show-discounts-as-sale-price/" class="b2bking_information_box_link">'.esc_html__(' Click here for documentation.','b2bking').'</a>';

				?>
			</div>
			<br />
			<div class="b2bking_rule_select_container" id="b2bking_rule_select_conditions_container">
				<div class="b2bking_rule_label"><?php esc_html_e('Conditions (all must apply)','b2bking'); ?></div>
				<input type="text" name="b2bking_rule_select_conditions" id="b2bking_rule_select_conditions" value="<?php echo esc_attr(get_post_meta($post->ID, 'b2bking_rule_conditions', true)); ?>">
				<div id="b2bking_condition_number_1" class="b2bking_rule_condition_container">
					<select class="b2bking_dynamic_rule_condition_name b2bking_condition_identifier_1">
						<option value="cart_total_quantity"><?php esc_html_e('Cart Total Quantity','b2bking'); ?></option>
						<option value="cart_total_value"><?php esc_html_e('Cart Total Value','b2bking'); ?></option>
						<option value="category_product_quantity"><?php esc_html_e('Category Product Quantity','b2bking'); ?></option>
						<option value="category_product_value"><?php esc_html_e('Category Product Value','b2bking'); ?></option>
						<option value="product_quantity"><?php esc_html_e('Product Quantity','b2bking'); ?></option>
						<option value="product_value"><?php esc_html_e('Product Value','b2bking'); ?></option>
					</select>
					<select class="b2bking_dynamic_rule_condition_operator b2bking_condition_identifier_1">
						<option value="greater"><?php esc_html_e('greater (>)','b2bking'); ?></option>
						<option value="equal"><?php esc_html_e('equal (=)','b2bking'); ?></option>
						<option value="smaller"><?php esc_html_e('smaller (<)','b2bking'); ?></option>
					</select>
					<input type="number" step="0.00001" class="b2bking_dynamic_rule_condition_number b2bking_condition_identifier_1" placeholder="<?php esc_attr_e('Enter the quantity/value','b2bking');?>">
					<button type="button" class="b2bking_dynamic_rule_condition_add_button b2bking_condition_identifier_1"><?php esc_html_e('Add Condition', 'b2bking'); ?></button>
				</div>
			</div>
			


			<br /><br />

			<?php 
			// if tags are enabled in settings, add a tags field
			if (intval(get_option('b2bking_enabletags_setting', 0)) === 1){
				?>
				<br />
				<div class="b2bking_rule_select_container" id="b2bking_rule_select_container_tags">
				<div class="b2bking_rule_label"><?php esc_html_e('Rule applies to these tags (comma-separated). If empty, field will be ignored.','b2bking'); ?></div>
				<input type="text" name="b2bking_rule_select_tags" id="b2bking_rule_select_tags" value="<?php echo esc_attr(get_post_meta($post->ID, 'b2bking_rule_tags', true)); ?>">
				<?php
			}
			?>
		</div>
		<?php
	}

	public static function b2bking_calculate_rule_numbers_database(){
		$pmmu_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'payment_method_minimum_order'
                ),
            )
            ]);
		if (!empty($pmmu_rules)){
			// build an array of users and groups that have fixed price rules that apply to them
			update_option('b2bking_have_pmmu_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($pmmu_rules as $rule){
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				$rule_ids_string .= $rule->ID.',';
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_pmmu_rules_list', $have_rules_string);
			update_option('b2bking_have_pmmu_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_pmmu_rules', 'no');
		}



		$fixed_price_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'fixed_price'
                ),
            )
            ]);
		if (!empty($fixed_price_rules)){
			// build an array of users and groups that have fixed price rules that apply to them
			update_option('b2bking_have_fixed_price_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($fixed_price_rules as $rule){
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				$rule_ids_string .= $rule->ID.',';
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_fixed_price_rules_list', $have_rules_string);
			update_option('b2bking_have_fixed_price_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_fixed_price_rules', 'no');
		}


		$free_shipping_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'free_shipping'
                ),
            )
            ]);

		if (!empty($free_shipping_rules)){
			// build an array of users and groups that have rules that apply to them
			update_option('b2bking_have_free_shipping_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($free_shipping_rules as $rule){
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				$rule_ids_string .= $rule->ID.',';
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_free_shipping_rules_list', $have_rules_string);
			update_option('b2bking_have_free_shipping_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_free_shipping_rules', 'no');
		}


		$minmax_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                'relation' => 'OR',
                array(
                    'key' => 'b2bking_rule_what',
                    'value' => 'minimum_order'
                ),
                array(
                    'key' => 'b2bking_rule_what',
                    'value' => 'maximum_order'
                ),
            ),
            ]);

		if (!empty($minmax_rules)){
			// build an array of users and groups that have rules that apply to them
			update_option('b2bking_have_minmax_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($minmax_rules as $rule){
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				$rule_ids_string .= $rule->ID.',';
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_minmax_rules_list', $have_rules_string);
			update_option('b2bking_have_minmax_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_minmax_rules', 'no');
		}

		$required_multiple_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'required_multiple'
                ),
            )
            ]);

		if (!empty($required_multiple_rules)){
			// build an array of users and groups that have rules that apply to them
			update_option('b2bking_have_required_multiple_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($required_multiple_rules as $rule){
				$rule_ids_string .= $rule->ID.',';
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_required_multiple_rules_list', $have_rules_string);
			update_option('b2bking_have_required_multiple_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_required_multiple_rules', 'no');
		}


		$tax_exemption_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'tax_exemption'
                ),
            )
            ]);

		if (!empty($tax_exemption_rules)){
			// build an array of users and groups that have rules that apply to them
			update_option('b2bking_have_tax_exemption_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($tax_exemption_rules as $rule){
				$rule_ids_string = $rule->ID.',';
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_tax_exemption_rules_list', $have_rules_string);
			update_option('b2bking_have_tax_exemption_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_tax_exemption_rules', 'no');
		}

		$tax_exemption_user_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'tax_exemption_user'
                ),
            )
            ]);

		if (!empty($tax_exemption_user_rules)){
			// build an array of users and groups that have rules that apply to them
			update_option('b2bking_have_tax_exemption_user_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($tax_exemption_user_rules as $rule){
				$rule_ids_string .= $rule->ID.',';
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_tax_exemption_user_rules_list', $have_rules_string);
			update_option('b2bking_have_tax_exemption_user_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_tax_exemption_user_rules', 'no');
		}

		$currency_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'set_currency_symbol'
                ),
            )
            ]);

		if (!empty($currency_rules)){
			// build an array of users and groups that have rules that apply to them
			update_option('b2bking_have_currency_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($currency_rules as $rule){
				$rule_ids_string .= $rule->ID.',';
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_currency_rules_list', $have_rules_string);
			update_option('b2bking_have_currency_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_currency_rules_list', 'no');
		}


		$add_tax_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                'relation' => 'OR',
                array(
                    'key' => 'b2bking_rule_what',
                    'value' => 'add_tax_percentage'
                ),
                array(
                    'key' => 'b2bking_rule_what',
                    'value' => 'add_tax_amount'
                ),
            ),
            ]);

		if (!empty($add_tax_rules)){
			// build an array of users and groups that have rules that apply to them
			update_option('b2bking_have_add_tax_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($add_tax_rules as $rule){
				$rule_ids_string .= $rule->ID.',';
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_add_tax_rules_list', $have_rules_string);
			update_option('b2bking_have_add_tax_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_add_tax_rules', 'no');
		}

		$hidden_price_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'hidden_price'
                ),
            )
            ]);

		if (!empty($hidden_price_rules)){
			// build an array of users and groups that have rules that apply to them
			update_option('b2bking_have_hidden_price_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($hidden_price_rules as $rule){
				$rule_ids_string .= $rule->ID.',';
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_hidden_price_rules_list', $have_rules_string);
			update_option('b2bking_have_hidden_price_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_hidden_price_rules', 'no');
		}


		$discount_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'discount_percentage'
                    ),
                    array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'discount_amount'
                    ),
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'b2bking_rule_discount_show_everywhere',
                        'value' => '0'
                    ),
                    array(
                        'key' => 'b2bking_rule_discount_show_everywhere',
                        'value' => ''
                    ),
                    array(
                        'key' => 'b2bking_rule_discount_show_everywhere',
                        'compare' => 'NOT EXISTS'
                    ),
                ),
            )
            ]);

		if (!empty($discount_rules)){
			// build an array of users and groups that have rules that apply to them
			update_option('b2bking_have_discount_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($discount_rules as $rule){
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				$rule_ids_string .= $rule->ID.',';
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_discount_rules_list', $have_rules_string);
			update_option('b2bking_have_discount_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_discount_rules', 'no');
		}


		$discount_everywhere_rules = get_posts([
    		'post_type' => 'b2bking_rule',
    	  	'post_status' => 'publish',
    	  	'numberposts' => -1,
    	  	'meta_query'=> array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'discount_percentage'
                    ),
                    array(
                        'key' => 'b2bking_rule_what',
                        'value' => 'discount_amount'
                    ),
                ),
                array(
                        'key' => 'b2bking_rule_discount_show_everywhere',
                        'value' => '1'
                ),
            )
            ]);

		if (!empty($discount_everywhere_rules)){
			// build an array of users and groups that have rules that apply to them
			update_option('b2bking_have_discount_everywhere_rules', 'yes');
			$have_rules_array = array();
			$rule_ids_string = '';
			foreach ($discount_everywhere_rules as $rule){
				$rule_who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
				$rule_ids_string .= $rule->ID.',';
				if ($rule_who === 'multiple_options'){
					$rule_who_multiple = get_post_meta($rule->ID, 'b2bking_rule_who_multiple_options', true);
					$rule_who_multiple_elements = explode(',',$rule_who_multiple);
					foreach ($rule_who_multiple_elements as $rule_who_element){
						array_push($have_rules_array, $rule_who_element);
					}
				} else {
					array_push($have_rules_array, $rule_who);
				}
			}
			$have_rules_array = array_filter(array_unique($have_rules_array));
			$have_rules_string = '';
			foreach ($have_rules_array as $have_rules_element){
				$have_rules_string .= $have_rules_element.',';
			}
			// remove last comma
			$have_rules_string = substr($have_rules_string, 0, -1);
			$rule_ids_string = substr($rule_ids_string, 0, -1);
			update_option('b2bking_have_discount_everywhere_rules_list', $have_rules_string);
			update_option('b2bking_have_discount_everywhere_rules_list_ids', $rule_ids_string);
		} else {
			update_option('b2bking_have_discount_everywhere_rules', 'no');
		}

	}

	// Save Rules Metabox Content
	function b2bking_save_rules_metaboxes($post_id){
		if (isset($_POST['_inline_edit'])){
			return;
		}
		if (isset($_REQUEST['bulk_edit'])){
		    return;
		}
		$postobj = get_post($post_id);
		if ( $postobj->post_status === 'trash' ) {
	        return;
	    }
	    if (isset($_GET['action'])) {
	    	if ($_GET['action'] === 'untrash'){
	    		return;
	    	}
	    }
		// clear cache when saving products
		if (get_post_type($post_id) === 'product'){
			// set that rules have changed so that pricing cache can be updated
			update_option('b2bking_dynamic_rules_have_changed', 'yes');

			// delete all b2bking transients
			global $wpdb;
			$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%transient_b2bking%'" );
			foreach( $plugin_options as $option ) {
			    delete_option( $option->option_name );
			}
		}
		if (get_post_type($post_id) === 'b2bking_rule'){

			// set that rules have changed so that pricing cache can be updated
			update_option('b2bking_dynamic_rules_have_changed', 'yes');

			// delete all b2bking transients
			global $wpdb;
			$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%transient_b2bking%'" );
			foreach( $plugin_options as $option ) {
			    delete_option( $option->option_name );
			}

			$rule_what = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_what'));
			$rule_applies = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_applies'));
			$rule_who = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_who'));
			$rule_quantity_value = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_quantity_value'));
			$rule_tax_shipping = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_tax_shipping'));
			$rule_tax_shipping_rate = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_tax_shipping_rate'));
			$rule_howmuch = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_howmuch'));
			$rule_currency = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_currency'));
			$rule_paymentmethod = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_paymentmethod'));
			$rule_taxname = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_taxname'));
			$rule_discountname = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_discountname'));
			$rule_conditions = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_conditions'));
			$rule_tags = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_tags'));
			$rule_discount_show_everywhere = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_dynamic_rule_discount_show_everywhere_checkbox_input'));
			
			if (isset($_POST['b2bking_rule_select_countries'])){
				$rule_countries = array_map( 'sanitize_text_field', $_POST['b2bking_rule_select_countries'] );

			} else {
				$rule_countries = NULL;
			}

			if (isset($_POST['b2bking_select_multiple_product_categories_selector_select'])){
				$rule_applies_multiple_options = array_map( 'sanitize_text_field', $_POST['b2bking_select_multiple_product_categories_selector_select'] );
			} else {
				$rule_applies_multiple_options = NULL;
			}

			if (isset($_POST['b2bking_select_multiple_users_selector_select'])){
				$rule_who_multiple_options = array_map( 'sanitize_text_field', $_POST['b2bking_select_multiple_users_selector_select'] );
			} else {
				$rule_who_multiple_options = NULL;
			}

			$rule_requires = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_requires'));
			$rule_showtax = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_showtax'));

			if ($rule_what !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_what', $rule_what);
			}
			if ($rule_currency !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_currency', $rule_currency);
			}
			if ($rule_paymentmethod !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_paymentmethod', $rule_paymentmethod);
			}
			if ($rule_applies !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_applies', $rule_applies);
			}
			if ($rule_who !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_who', $rule_who);
			}
			if ($rule_quantity_value !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_quantity_value', $rule_quantity_value);
			}
			if ($rule_howmuch !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_howmuch', $rule_howmuch);
			}
			if ($rule_taxname !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_taxname', $rule_taxname);
			}
			if ($rule_tax_shipping !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_tax_shipping', $rule_tax_shipping);
			}
			if ($rule_tax_shipping_rate !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_tax_shipping_rate', $rule_tax_shipping_rate);
			}
			if ($rule_discountname !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_discountname', $rule_discountname);
			}
			if ($rule_conditions !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_conditions', $rule_conditions);
			}
			if ($rule_tags !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_tags', $rule_tags);
			}
			if ($rule_discount_show_everywhere !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_discount_show_everywhere', $rule_discount_show_everywhere);
			}

			
			if ($rule_countries !== NULL){
				$countries_string = '';
				foreach ($rule_countries as $country){
					$countries_string .= sanitize_text_field ($country).',';
				}
				// remove last comma
				$countries_string = substr($countries_string, 0, -1);
				update_post_meta( $post_id, 'b2bking_rule_countries', $countries_string);
			}
			if ($rule_requires !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_requires', $rule_requires);
			}
			if ($rule_showtax !== NULL){
				update_post_meta( $post_id, 'b2bking_rule_showtax', $rule_showtax);
			}

			if ($rule_applies_multiple_options !== NULL){
				$options_string = '';
				foreach ($rule_applies_multiple_options as $option){
					$options_string .= sanitize_text_field ($option).',';
				}
				// remove last comma
				$options_string = substr($options_string, 0, -1);
				update_post_meta( $post_id, 'b2bking_rule_applies_multiple_options', $options_string);
			}

			if ($rule_who_multiple_options !== NULL){
				$options_string = '';
				foreach ($rule_who_multiple_options as $option){
					$options_string .= sanitize_text_field ($option).',';
				}
				// remove last comma
				$options_string = substr($options_string, 0, -1);
				update_post_meta( $post_id, 'b2bking_rule_who_multiple_options', $options_string);
			}

			$rule_replaced =  sanitize_text_field(filter_input(INPUT_POST, 'b2bking_rule_select_applies_replaced')); 
			$rule_replaced_array = explode(',',$rule_replaced);
			$rule_replaced_string = '';
			foreach ($rule_replaced_array as $element){
				$rule_replaced_string.= 'product_'.trim($element).',';
			}
			// remove last comma
			$rule_replaced_string = substr($rule_replaced_string, 0, -1);

			// if rule applies is product & variation IDS, set applies as b2bking_rule_select_applies_replaced
			if ($rule_applies === 'replace_ids'){
				if ($rule_replaced !== NULL){
					update_post_meta( $post_id, 'b2bking_rule_applies', 'multiple_options');
					update_post_meta( $post_id, 'b2bking_rule_applies_multiple_options', $rule_replaced_string);
					update_post_meta( $post_id, 'b2bking_rule_replaced', 'yes');
				}
			} else {
				update_post_meta( $post_id, 'b2bking_rule_replaced', 'no');
			}

			// calculate the number of rules for each rule and set them as an option, to improve speed
			$this->b2bking_calculate_rule_numbers_database();
		}
	}

	// Register new post type: Offers (b2bking_offer)
	function b2bking_register_post_type_offer() {
		// Build labels and arguments
	    $labels = array(
	        'name'                  => esc_html__( 'Offers', 'b2bking' ),
	        'singular_name'         => esc_html__( 'Offer', 'b2bking' ),
	        'all_items'             => esc_html__( 'Offers', 'b2bking' ),
	        'menu_name'             => esc_html__( 'Offers', 'b2bking' ),
	        'add_new'               => esc_html__( 'Make offer', 'b2bking' ),
	        'add_new_item'          => esc_html__( 'Make new offer', 'b2bking' ),
	        'edit'                  => esc_html__( 'Edit', 'b2bking' ),
	        'edit_item'             => esc_html__( 'Edit offer', 'b2bking' ),
	        'new_item'              => esc_html__( 'New offer', 'b2bking' ),
	        'view_item'             => esc_html__( 'View offer', 'b2bking' ),
	        'view_items'            => esc_html__( 'View offers', 'b2bking' ),
	        'search_items'          => esc_html__( 'Search offers', 'b2bking' ),
	        'not_found'             => esc_html__( 'No offers found', 'b2bking' ),
	        'not_found_in_trash'    => esc_html__( 'No offers found in trash', 'b2bking' ),
	        'parent'                => esc_html__( 'Parent offer', 'b2bking' ),
	        'featured_image'        => esc_html__( 'Offer image', 'b2bking' ),
	        'set_featured_image'    => esc_html__( 'Set offer image', 'b2bking' ),
	        'remove_featured_image' => esc_html__( 'Remove offer image', 'b2bking' ),
	        'use_featured_image'    => esc_html__( 'Use as offer image', 'b2bking' ),
	        'insert_into_item'      => esc_html__( 'Insert into offer', 'b2bking' ),
	        'uploaded_to_this_item' => esc_html__( 'Uploaded to this offer', 'b2bking' ),
	        'filter_items_list'     => esc_html__( 'Filter offers', 'b2bking' ),
	        'items_list_navigation' => esc_html__( 'Offers navigation', 'b2bking' ),
	        'items_list'            => esc_html__( 'Offers list', 'b2bking' )
	    );
	    $args = array(
	        'label'                 => esc_html__( 'Offer', 'b2bking' ),
	        'description'           => esc_html__( 'This is where you can make new offers', 'b2bking' ),
	        'labels'                => $labels,
	        'supports'              => array('title'),
	        'hierarchical'          => false,
	        'public'                => false,
	        'publicly_queryable' 	=> false,
	        'show_ui'               => true,
	        'show_in_menu'          => 'b2bkingcore',
	        'menu_position'         => 102,
	        'show_in_admin_bar'     => true,
	        'can_export'            => true,
	        'has_archive'           => false,
	        'exclude_from_search'   =>  true,
	        'capability_type'       => 'product',
	    );


	// Actually register the post type
	register_post_type( 'b2bking_offer', $args );
	}

	// Add Offer Details Metabox to Offers
	function b2bking_offers_metaboxes($post_type) {
	    $post_types = array('b2bking_offer');     //limit meta box to certain post types
       	if ( in_array( $post_type, $post_types ) ) {
	           add_meta_box(
	               'b2bking_offer_access_metabox'
	               ,esc_html__( 'Offer Access', 'b2bking' )
	               ,array( $this, 'b2bking_offer_access_metabox_content' )
	               ,$post_type
	               ,'advanced'
	               ,'high'
	           );
	           add_meta_box(
	               'b2bking_offer_details_metabox'
	               ,esc_html__( 'Offer Details', 'b2bking' )
	               ,array( $this, 'b2bking_offer_details_metabox_content' )
	               ,$post_type
	               ,'advanced'
	               ,'high'
	           );
	           add_meta_box(
	               'b2bking_offer_customtext_metabox'
	               ,esc_html__( 'Offer Custom Text (optional)', 'b2bking' )
	               ,array( $this, 'b2bking_offer_customtext_metabox_content' )
	               ,$post_type
	               ,'advanced'
	               ,'high'
	           );
	       }
	}

	// Offer Details Metabox Content
	function b2bking_offer_details_metabox_content(){
		global $post;
		?>
		<textarea id="b2bking_admin_offer_textarea" name="b2bking_admin_offer_textarea"><?php 
				// If current page is not Add New, retrieve textarea content
		        if( get_current_screen()->action !== 'add'){
		        	echo esc_html(get_post_meta($post->ID, 'b2bking_offer_details', true));
		        }
		?></textarea>
		<div id="b2bking_offer_number_1" class="b2bking_offer_line_number">
			<div class="b2bking_offer_input_container">
				<?php esc_html_e('Item name:','b2bking'); ?>
				<br />
				<?php
				if (intval(get_option( 'b2bking_offers_product_selector_setting', 0 )) === 1){
					?>
					<select class="b2bking_offer_product_selector b2bking_offer_item_name">
						<?php
						// if page not "Add new", get selected
						$selected = '';
						if( get_current_screen()->action !== 'add'){
				        	$selected = esc_html(get_post_meta($post->ID, 'b2bking_rule_applies', true));
				        }
						?>
						<optgroup label="<?php esc_attr_e('Products (individual)', 'b2bking'); ?>">
							<?php
							// Get all products
							$products = get_posts( array(
								'post_type' => 'product',
								'post_status'=>'publish', 
								'numberposts' => -1,
								'fields' => 'ids',
							));

							foreach ($products as $product){
								// skip 'Offer' product
								$offer_id = intval(get_option('b2bking_offer_product_id_setting', 0));
								if (intval($product) !== $offer_id && intval($product) !== 3225464){ //3225464 is deprecated
									$productobj = wc_get_product($product);
									echo '<option value="product_'.esc_attr($product).'" '.selected('product_'.$product,$selected,false).'>'.esc_html($productobj->get_name()).'</option>';
								}
							}
							?>
						</optgroup>
						<optgroup label="<?php esc_attr_e('Products (Individual Variations)', 'b2bking'); ?>">
							<?php
							// Get all products
							$products = get_posts(array(
								'post_type' => 'product_variation',
								'post_status'=>'publish',
								'numberposts' => -1,
								'fields' => 'ids',
							));

							foreach ($products as $product){
								$productobj = wc_get_product($product);
								echo '<option value="product_'.esc_attr($product).'" '.selected('product_'.$product,$selected,false).'>'.esc_html($productobj->get_name()).'</option>';
							}
							?>
						</optgroup>
					</select>
				<?php } else { ?>
				<input type="text" class="b2bking_offer_text_input b2bking_offer_item_name" placeholder="<?php esc_attr_e('Enter the item name','b2bking'); ?>">
			<?php } ?>
			</div>
			<div class="b2bking_offer_input_container">
				<?php esc_html_e('Item quantity:','b2bking'); ?>
				<br />
				<input type="number" min="0" class="b2bking_offer_text_input b2bking_offer_item_quantity" placeholder="<?php esc_attr_e('Enter the quantity','b2bking'); ?>">
			</div>
			<div class="b2bking_offer_input_container">
				<?php esc_html_e('Unit price:','b2bking'); ?>
				<br />
				<input type="number" step="0.0001" min="0" class="b2bking_offer_text_input b2bking_offer_item_price" placeholder="<?php esc_attr_e('Enter the unit price','b2bking'); ?>"> 
			</div>
			<div class="b2bking_offer_input_container">
				<?php esc_html_e('Item subtotal:','b2bking'); ?>
				<br />
				<div class="b2bking_item_subtotal"><?php echo get_woocommerce_currency_symbol();?>0</div>
			</div>
			<div class="b2bking_offer_input_container">
				<br />
				<button type="button" class="button-primary button b2bking_offer_add_item_button"><?php esc_html_e('Add new item','b2bking'); ?></button>
			</div> 
			<br /><br />
		</div>



		<br /><hr>
		<div id="b2bking_offer_total_text">
			<?php 
			esc_html_e('Offer Total: ','b2bking'); 
			?>
			<div id="b2bking_offer_total_text_number">
				<?php echo get_woocommerce_currency_symbol();?>0
			</div>
		</div>

		<?php
	}

	function b2bking_offer_customtext_metabox_content(){
		global $post;
		?>
		<div class="b2bking_offers_metabox_padding">
			<div class="b2bking_group_visibility_container_content_title">
				<svg class="b2bking_offers_metabox_icon" xmlns="http://www.w3.org/2000/svg" width="39" height="39" fill="none" viewBox="0 0 39 39">
				  <path fill="#C4C4C4" fill-rule="evenodd" d="M29.25 2.438H9.75a4.875 4.875 0 00-4.875 4.874v24.375a4.875 4.875 0 004.875 4.875h19.5a4.875 4.875 0 004.875-4.874V7.313a4.875 4.875 0 00-4.875-4.875zM12.187 9.75a1.219 1.219 0 000 2.438h14.626a1.219 1.219 0 000-2.438H12.188zm-1.218 6.094a1.219 1.219 0 011.219-1.219h14.624a1.219 1.219 0 010 2.438H12.188a1.219 1.219 0 01-1.218-1.22zm1.219 3.656a1.219 1.219 0 000 2.438h14.624a1.219 1.219 0 000-2.438H12.188zm0 4.875a1.219 1.219 0 000 2.438H19.5a1.219 1.219 0 000-2.438h-7.313z" clip-rule="evenodd"/>
				</svg>
				<?php esc_html_e('Additional custom text to display for this offer','b2bking');?>
			</div>
			<textarea name="b2bking_offer_customtext" id="b2bking_offer_customtext_textarea"><?php 
					if (get_current_screen()->action !== 'add'){
			            echo get_post_meta($post->ID, 'b2bking_offer_customtext_textarea', true);
			        } 
		        ?></textarea>
		</div>
		<?php

	}

	// Save Offers Metabox Content
	function b2bking_save_offers_metaboxes($post_id){
		if (isset($_POST['_inline_edit'])){
			if (wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')){
			    return;
			}
		}
		if (get_post_type($post_id) === 'b2bking_offer'){
			$offer_details = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_admin_offer_textarea'));
			if ($offer_details !== NULL){
				update_post_meta( $post_id, 'b2bking_offer_details', $offer_details);
			}

			// Get all groups
			$groups = get_posts([
			  'post_type' => 'b2bking_group',
			  'post_status' => 'publish',
			  'numberposts' => -1
			]);

			// For each group option, save user's choice as post meta
			foreach ($groups as $group){
				$meta_input = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_group_'.$group->ID));
				if($meta_input !== NULL){
					update_post_meta($post_id, 'b2bking_group_'.$group->ID, sanitize_text_field($meta_input));
				}
			}

			// Save user visibility
			$meta_user_visibility = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_category_users_textarea'));
			if ($meta_user_visibility !== NULL){
				// get current users list
				$currentuserstextarea = esc_html(get_post_meta($post_id, 'b2bking_category_users_textarea', true));
				$currentusersarray = explode(',', $currentuserstextarea);
				// delete all individual user meta
				foreach ($currentusersarray as $user){
					delete_post_meta( $post_id, 'b2bking_user_'.trim($user));
				}
				// get new users list
				$newusertextarea = $meta_user_visibility;
				$newusersarray = explode(',', $newusertextarea);
				// set new user meta
				foreach ($newusersarray as $newuser){
					update_post_meta( $post_id, 'b2bking_user_'.sanitize_text_field(trim($newuser)), 1);
				}
				// Update users textarea
				update_post_meta($post_id, 'b2bking_category_users_textarea', sanitize_text_field($meta_user_visibility));
			}

			// Save user visibility
			$offer_custom_text = sanitize_textarea_field(filter_input(INPUT_POST, 'b2bking_offer_customtext'));
			if ($offer_custom_text !== NULL){
				update_post_meta($post_id, 'b2bking_offer_customtext_textarea', sanitize_textarea_field($offer_custom_text));
			}			
		}
	}

	// Offer Access Metabox Content
	function b2bking_offer_access_metabox_content(){
		if ( ! current_user_can( 'manage_woocommerce' ) ) { return; }
	    ?>
	    <div class="b2bking_group_visibility_container">
	    	<div class="b2bking_group_visibility_container_top">
	    		<?php esc_html_e( 'Group Visibility (B2BKing)', 'b2bking' ); ?>
	    	</div>
	    	<div class="b2bking_group_visibility_container_content">
	    		<div class="b2bking_group_visibility_container_content_title">
	    			<svg class="b2bking_group_visibility_container_content_title_icon" xmlns="http://www.w3.org/2000/svg" width="31" height="29" fill="none" viewBox="0 0 31 29">
					<path fill="#C4C4C4" d="M15.5 6.792V.625H.083v27.75h30.834V6.792H15.5zm-9.25 18.5H3.167v-3.084H6.25v3.084zm0-6.167H3.167v-3.083H6.25v3.083zm0-6.167H3.167V9.875H6.25v3.083zm0-6.166H3.167V3.708H6.25v3.084zm6.167 18.5H9.333v-3.084h3.084v3.084zm0-6.167H9.333v-3.083h3.084v3.083zm0-6.167H9.333V9.875h3.084v3.083zm0-6.166H9.333V3.708h3.084v3.084zm15.416 18.5H15.5v-3.084h3.083v-3.083H15.5v-3.083h3.083v-3.084H15.5V9.875h12.333v15.417zM24.75 12.958h-3.083v3.084h3.083v-3.084zm0 6.167h-3.083v3.083h3.083v-3.083z"></path>
					</svg>
					<?php esc_html_e( 'Groups who can see this offer', 'b2bking' ); ?>
	    		</div>
            	<?php
	            	$groups = get_posts([
	            	  'post_type' => 'b2bking_group',
	            	  'post_status' => 'publish',
	            	  'numberposts' => -1
	            	]);
	            	foreach ($groups as $group){
	            		$checked = '';
		            		// If current page is not Add New 
		            		if( get_current_screen()->action !== 'add'){
			            		global $post;
			            		$check = intval(get_post_meta($post->ID, 'b2bking_group_'.$group->ID, true));
			            		if ($check === 1){
			            			$checked = 'checked="checked"';
			            		}	
			            	}  
	            		?>
	            		<div class="b2bking_group_visibility_container_content_checkbox">
	            			<div class="b2bking_group_visibility_container_content_checkbox_name">
	            				<?php echo esc_html($group->post_title); ?>
	            			</div>
	            			<input type="hidden" name="b2bking_group_<?php echo esc_attr($group->ID);?>" value="0">
	            			<input type="checkbox" value="1" class="b2bking_group_visibility_container_content_checkbox_input" name="b2bking_group_<?php echo esc_attr($group->ID);?>" id="b2bking_group_<?php echo esc_attr($group->ID);?>" value="1" <?php echo $checked;?> />
	            		</div>
	            		<?php
	            	}
	            ?>
	    	</div>
	    </div>

	    <div class="b2bking_group_visibility_container">
	    	<div class="b2bking_group_visibility_container_top">
	    		<?php esc_html_e( 'User Visibility (B2BKing)', 'b2bking' ); ?>
	    	</div>
	    	<div class="b2bking_group_visibility_container_content">
	    		<div class="b2bking_group_visibility_container_content_title">
					<svg class="b2bking_user_visibility_container_content_title_icon" xmlns="http://www.w3.org/2000/svg" width="31" height="23" fill="none" viewBox="0 0 31 23">
					  <path fill="#C4C4C4" d="M9.333 11.58c3.076 0 5.396-2.32 5.396-5.396C14.73 3.11 12.41.79 9.333.79c-3.075 0-5.396 2.32-5.396 5.395 0 3.076 2.32 5.396 5.396 5.396zm1.542 1.462H7.792c-4.25 0-7.709 3.458-7.709 7.708v1.542h18.5V20.75c0-4.25-3.458-7.708-7.708-7.708zm17.412-7.258l-6.63 6.616-1.991-1.992-2.18 2.18 4.171 4.17 8.806-8.791-2.176-2.183z"/>
					</svg>
					<?php esc_html_e( 'Users who can see this offer (comma-separated)', 'b2bking' ); ?>
	    		</div>
	    		<textarea name="b2bking_category_users_textarea" id="b2bking_category_users_textarea"><?php 
		            		// If current page is not Add New 
		            		if( get_current_screen()->action !== 'add'){
			            		global $post;
			            		echo get_post_meta($post->ID, 'b2bking_category_users_textarea', true);
			            	}  
	            			?></textarea>
            	<div class="b2bking_category_users_textarea_buttons_container">
            		<?php wp_dropdown_users($args = array('id' => 'b2bking_all_users_dropdown', 'show' => 'user_login')); ?><button type="button" class="button" id="b2bking_category_add_user"><?php esc_html_e('Add user','b2bking'); ?></button>
            	</div>

	    	</div>
	    </div>
	    <?php
	}

	// Register new post type: User Inquiries (b2bking_conversation)
	function b2bking_register_post_type_conversation() {
		// Build labels and arguments
	    $labels = array(
	        'name'                  => esc_html__( 'Conversations', 'b2bking' ),
	        'singular_name'         => esc_html__( 'Conversation', 'b2bking' ),
	        'all_items'             => esc_html__( 'Conversations', 'b2bking' ),
	        'menu_name'             => esc_html__( 'Conversations', 'b2bking' ),
	        'add_new'               => esc_html__( 'Start Conversation', 'b2bking' ),
	        'add_new_item'          => esc_html__( 'Start new conversation', 'b2bking' ),
	        'edit'                  => esc_html__( 'Edit', 'b2bking' ),
	        'edit_item'             => esc_html__( 'Edit conversation', 'b2bking' ),
	        'new_item'              => esc_html__( 'New conversation', 'b2bking' ),
	        'view_item'             => esc_html__( 'View conversation', 'b2bking' ),
	        'view_items'            => esc_html__( 'View conversations', 'b2bking' ),
	        'search_items'          => esc_html__( 'Search conversations', 'b2bking' ),
	        'not_found'             => esc_html__( 'No conversations found', 'b2bking' ),
	        'not_found_in_trash'    => esc_html__( 'No conversations found in trash', 'b2bking' ),
	        'parent'                => esc_html__( 'Parent conversation', 'b2bking' ),
	        'featured_image'        => esc_html__( 'Conversation image', 'b2bking' ),
	        'set_featured_image'    => esc_html__( 'Set conversation image', 'b2bking' ),
	        'remove_featured_image' => esc_html__( 'Remove conversation image', 'b2bking' ),
	        'use_featured_image'    => esc_html__( 'Use as conversation image', 'b2bking' ),
	        'insert_into_item'      => esc_html__( 'Insert into conversation', 'b2bking' ),
	        'uploaded_to_this_item' => esc_html__( 'Uploaded to this conversation', 'b2bking' ),
	        'filter_items_list'     => esc_html__( 'Filter conversations', 'b2bking' ),
	        'items_list_navigation' => esc_html__( 'Conversations navigation', 'b2bking' ),
	        'items_list'            => esc_html__( 'Conversations list', 'b2bking' )
	    );
	    $args = array(
	        'label'                 => esc_html__( 'Conversation', 'b2bking' ),
	        'description'           => esc_html__( 'This is where you can create new conversations', 'b2bking' ),
	        'labels'                => $labels,
	        'supports'              => array('title'),
	        'hierarchical'          => false,
	        'public'                => false,
	        'publicly_queryable' 	=> false,
	        'show_ui'               => true,
	        'show_in_menu'          => 'b2bkingcore',
	        'menu_position'         => 100,
	        'show_in_admin_bar'     => true,
	        'can_export'            => true,
	        'has_archive'           => false,
	        'exclude_from_search'   =>  true,
	        'capability_type'       => 'product',
	    );


	// Actually register the post type
	register_post_type( 'b2bking_conversation', $args );
	}

	// Add Conversation Details Metabox to Conversations
	function b2bking_conversations_metaboxes($post_type) {
	    $post_types = array('b2bking_conversation');     //limit meta box to certain post types
       	if ( in_array( $post_type, $post_types ) ) {
	           add_meta_box(
	               'b2bking_conversation_details_metabox'
	               ,esc_html__( 'Conversation Details', 'b2bking' )
	               ,array( $this, 'b2bking_conversation_details_metabox_content' )
	               ,$post_type
	               ,'advanced'
	               ,'high'
	           );
	           add_meta_box(
	               'b2bking_conversation_messaging_metabox'
	               ,esc_html__( 'Messages', 'b2bking' )
	               ,array( $this, 'b2bking_conversation_messaging_metabox_content' )
	               ,$post_type
	               ,'advanced'
	               ,'high'
	           );
	       }
	}

	// Conversation Details Metabox Content
	function b2bking_conversation_details_metabox_content(){

		// If current page is ADD New Conversation
		if(get_current_screen()->action === 'add'){
			?>
			<div id="b2bking_conversation_details_wrapper">
				<div id="b2bking_conversation_user_container">
					<?php esc_html_e('User: ','b2bking'); ?>
					<?php wp_dropdown_users(array('id'=>'b2bking_conversation_user_input', 'name'=>'b2bking_conversation_user_input', 'show' => 'user_login' )); ?>
				</div>
				<div id="b2bking_conversation_user_status_container">
					<?php esc_html_e('Status: ','b2bking'); ?>
					<select id="b2bking_conversation_status_select" name="b2bking_conversation_status_select">
						<option value="new" selected><?php esc_html_e('New', 'b2bking');?></option>
						<option value="open"><?php esc_html_e('Open', 'b2bking');?></option>
						<option value="resolved"><?php esc_html_e('Resolved', 'b2bking');?></option>
					</select>
				</div>
			</div>
			<?php
		} else {
			// just display user
			global $post;
			$user = get_post_meta( $post->ID, 'b2bking_conversation_user', true );
			echo '
			<div id="b2bking_conversation_details_wrapper">
			<div id="b2bking_conversation_user_container">'.esc_html__('User: ', 'b2bking').'&nbsp;<strong>'.esc_html($user).'</strong></div>';

			// display status after check
			$status = get_post_meta( $post->ID, 'b2bking_conversation_status', true );
			?>
				<div id="b2bking_conversation_user_status_container">
					<?php esc_html_e('Status: ','b2bking'); ?>
					<select id="b2bking_conversation_status_select" name="b2bking_conversation_status_select">
						<option value="new" <?php selected('new', $status, true); ?>><?php esc_html_e('New', 'b2bking');?></option>
						<option value="open" <?php selected('open', $status, true); ?>><?php esc_html_e('Open', 'b2bking');?></option>
						<option value="resolved" <?php selected('resolved', $status, true); ?>><?php esc_html_e('Resolved', 'b2bking');?></option>
					</select>
				</div>
			</div>
			<?php
		}
	}

	// Conversation Details Metabox Content
	function b2bking_conversation_messaging_metabox_content(){

		// If current page is ADD New Conversation
		if(get_current_screen()->action === 'add'){
			?>
			<textarea name="b2bking_conversation_start_message" id="b2bking_conversation_start_message" required></textarea>
			<?php
		} else {
			// Display Conversation
			// get number of messages
			global $post;
			$nr_messages = get_post_meta ($post->ID, 'b2bking_conversation_messages_number', true);
			
			?>
			<div id="b2bking_conversation_messages_container">
				<?php	
				// loop through and display messages
				for ($i = 1; $i <= $nr_messages; $i++) {
				    // get message details
				    $message = get_post_meta ($post->ID, 'b2bking_conversation_message_'.$i, true);
				    $author = get_post_meta ($post->ID, 'b2bking_conversation_message_'.$i.'_author', true);
				    $time = get_post_meta ($post->ID, 'b2bking_conversation_message_'.$i.'_time', true);
				    // check if message author is self
				    if (wp_get_current_user()->user_login === $author){
				    	$self = ' b2bking_conversation_message_self';
				    } else {
				    	$self = '';
				    }
				    // build time string
					    // if today
					    if((time()-$time) < 86400){
					    	// show time
					    	$timestring = date_i18n( 'h:i A', $time+(get_option('gmt_offset')*3600) );
					    } else if ((time()-$time) < 172800){
					    // if yesterday
					    	$timestring = 'Yesterday at '.date_i18n( 'h:i A', $time+(get_option('gmt_offset')*3600) );
					    } else {
					    // date
					    	$timestring = date_i18n( get_option('date_format'), $time+(get_option('gmt_offset')*3600) ); 
					    }
				    ?>
				    <div class="b2bking_conversation_message <?php echo esc_attr($self); ?>">
				    	<?php echo wp_kses( $message, array( 'br' => true ) ); ?>
				    	<div class="b2bking_conversation_message_time">
				    		<?php echo esc_html($author).' - '; ?>
				    		<?php echo esc_html($timestring); ?>
				    	</div>
				    </div>
				    <?php
				}
				?>
			</div>
			<textarea name="b2bking_conversation_admin_new_message" id="b2bking_conversation_admin_new_message"></textarea><br />
			<button type="submit" class="button button-primary button-large"><?php esc_html_e('Send message'); ?></button>

			<?php
		}
		
	}

	// Save User Metabox Content
	function b2bking_save_conversations_metaboxes($post_id){
		if (isset($_POST['_inline_edit'])){
			if (wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')){
			    return;
			}
		}
		if (get_post_type($post_id) === 'b2bking_conversation'){
			$meta_user = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_conversation_user_input'));
			if ($meta_user !== NULL && trim($meta_user) !== ''){
				// meta user is user ID . Get user login
				$user_login = get_user_by('id', $meta_user)->user_login;
				update_post_meta( $post_id, 'b2bking_conversation_user', sanitize_text_field($user_login));
			}

			$meta_status = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_conversation_status_select'));
			if ($meta_status !== NULL ){
				update_post_meta( $post_id, 'b2bking_conversation_status', sanitize_text_field($meta_status));
			}

			$meta_conversation_start_message = sanitize_textarea_field(filter_input(INPUT_POST, 'b2bking_conversation_start_message'));
			if ($meta_conversation_start_message !== NULL && trim($meta_conversation_start_message) !== ''){
				update_post_meta( $post_id, 'b2bking_conversation_message_1', sanitize_textarea_field($meta_conversation_start_message));
				update_post_meta( $post_id, 'b2bking_conversation_message_1_author', wp_get_current_user()->user_login );
				update_post_meta( $post_id, 'b2bking_conversation_message_1_time', time() );
				update_post_meta( $post_id, 'b2bking_conversation_messages_number', 1);
				update_post_meta( $post_id, 'b2bking_conversation_type', 'message');

				// send email notification
				do_action( 'b2bking_new_message', get_user_by('id', $meta_user)->user_email, $meta_conversation_start_message, get_current_user_id(), $post_id );
			}

			$meta_admin_new_message = sanitize_textarea_field(filter_input(INPUT_POST, 'b2bking_conversation_admin_new_message'));
			if ($meta_admin_new_message !== NULL && trim($meta_admin_new_message) !== ''){
				$nr_messages = get_post_meta ($post_id, 'b2bking_conversation_messages_number', true);
				$current_message_nr = $nr_messages+1;

				update_post_meta( $post_id, 'b2bking_conversation_message_'.$current_message_nr, sanitize_textarea_field($meta_admin_new_message));
				update_post_meta( $post_id, 'b2bking_conversation_messages_number', $current_message_nr);
				update_post_meta( $post_id, 'b2bking_conversation_message_'.$current_message_nr.'_author', wp_get_current_user()->user_login );
				update_post_meta( $post_id, 'b2bking_conversation_message_'.$current_message_nr.'_time', time() );

				// if status is new, change to open
				$status = get_post_meta ($post_id, 'b2bking_conversation_status', true);
				if ($status === 'new'){
					update_post_meta( $post_id, 'b2bking_conversation_status', 'open');
				}

				// send email notification if it's been at least 10 minutes since the previous message
				$previous_message_time = intval(get_post_meta($post_id, 'b2bking_conversation_message_'.$nr_messages.'_time',true ));
				if ((time()-$previous_message_time) > 600){
					do_action( 'b2bking_new_message', get_user_by('login', get_post_meta($post_id, 'b2bking_conversation_user', true))->user_email, $meta_admin_new_message , get_current_user_id(), $post_id );
				}
			}
		}
	}

	// Add custom columns to offer menu
	function b2bking_add_columns_offer_menu($columns) {

		$columns_initial = $columns;

		// rename title
		$columns = array(
			'title' => esc_html__( 'Offer name', 'b2bking' ),
			'b2bking_offer_price' => esc_html__( 'Offer price', 'b2bking' ),
			'b2bking_offer_number_items' => esc_html__( 'Number of items', 'b2bking' ),
		);

		$columns = array_slice($columns_initial, 0, 1, true) + $columns;


	    return $columns;
	}

	// Add offer custom columns data
	function b2bking_columns_offer_data( $column, $post_id ) {
		// Get offer
		$offer_details = get_post_meta($post_id,'b2bking_offer_details',true);
		$offer_elements = explode('|',$offer_details);
		$currency_symbol = get_woocommerce_currency_symbol();

	    switch ( $column ) {

	        case 'b2bking_offer_price' :
	        	$price = 0;
	        	foreach ($offer_elements as $element){
	        		$element_array = explode(';',$element);
	        		if(isset($element_array[1]) && isset($element_array[2])){
	        			$price += $element_array[1]*$element_array[2];
	        		}
	        	}

	            echo '<strong>'.wc_price(esc_html($price)).'</strong>';
	            break;

            case 'b2bking_offer_number_items' :

                echo '<strong>'.esc_html(count($offer_elements)).'</strong>';
                break;

	    }
	}

	function b2bking_hide_offer_post($query) {
		$offer_id = intval(get_option('b2bking_offer_product_id_setting', 0));
        $query->query_vars['post__not_in'] = array($offer_id, 3225464); //3225464 is deprecated
	}		


	// Add custom columns to Groups menu
	function b2bking_add_columns_group_menu($columns) {

		$columns_initial = $columns;

		// rename title
		$columns = array(
			'title' => esc_html__( 'Group name', 'b2bking' ),
			'b2bking_user_number' => esc_html__( 'Number of users', 'b2bking' ),
		);

		$columns = array_slice($columns_initial, 0, 1, true) + $columns;


	    return $columns;
	}

	// Add groups custom columns data
	function b2bking_columns_group_data( $column, $post_id ) {
	    switch ( $column ) {

	        case 'b2bking_user_number' :
	        	$users = get_users(array(
				    'meta_key'     => 'b2bking_customergroup',
				    'meta_value'   => $post_id,
				    'fields' => 'ids',
				));	

	            echo '<strong>'.esc_html(count($users)).'</strong>';
	            break;

	    }
	}


	// Add custom columns to Dynamic Rules menu
	function b2bking_add_columns_rule_menu($columns) {

		$columns_initial = $columns;

		// rename title
		$columns = array(
			'title' => esc_html__( 'Name', 'b2bking' ),
			'b2bking_what' => esc_html__( 'Type', 'b2bking' ),
			'b2bking_howmuch' => esc_html__( 'How much', 'b2bking' ),
		//	'b2bking_conditions' => esc_html__( 'Conditions apply', 'b2bking' ),
			'b2bking_applies' => esc_html__( 'Applies to', 'b2bking' ),
			'b2bking_who' => esc_html__( 'For who', 'b2bking' ),
			'b2bking_status' => esc_html__( 'Enabled', 'b2bking' ),

		);

		$columns = array_slice($columns_initial, 0, 1, true) + $columns;


	    return $columns;
	}

	// Add dynamic rule custom columns data
	function b2bking_columns_rule_data( $column, $post_id ) {
	    switch ( $column ) {

	       case 'b2bking_status' :
	       		$status = get_post_status($post_id);
    	       	if ($status === 'publish'){
    	       		$status = 'enabled';
    	       	} else {
    	       		$status = 'disabled';
    	       	}

    	           ?>
    	           <input type="checkbox" class="b2bking_switch_input" id="b2bking_switch_field_<?php echo esc_attr($post_id);?>" <?php
    	           if ($status === 'enabled'){
    	           	echo 'checked';
    	           }
    	       	?>/><label class="b2bking_switch_label" for="b2bking_switch_field_<?php echo esc_attr($post_id);?>">Toggle</label>
	           <?php
	           break;

	        case 'b2bking_what' :
	        	$what = get_post_meta($post_id, 'b2bking_rule_what', true);
	        	$class = $what;
	        	switch ( $what ){
	        		case 'discount_amount':
	        		$what = esc_html__('Discount Amount','b2bking');
	        		break;

	        		case 'discount_percentage':
	        		$what = esc_html__('Discount Percentage','b2bking');
	        		break;

	        		case 'fixed_price':
	        		$what = esc_html__('Fixed Price','b2bking');
	        		break;

	        		case 'hidden_price':
	        		$what = esc_html__('Hidden Price','b2bking');
	        		break;

	        		case 'free_shipping':
	        		$what = esc_html__('Free Shipping','b2bking');
	        		break;

	        		case 'minimum_order':
	        		$what = esc_html__('Minimum Order','b2bking');
	        		break;

	        		case 'maximum_order':
	        		$what = esc_html__('Maximum Order','b2bking');
	        		break;

	        		case 'required_multiple':
	        		$what = esc_html__('Required Multiple','b2bking');
	        		break;

	        		case 'tax_exemption_user':
	        		$what = esc_html__('Tax Exemption','b2bking');
	        		break;

	        		case 'tax_exemption':
	        		$what = esc_html__('Zero Tax Product','b2bking');
	        		break;

	        		case 'add_tax_percentage':
	        		$what = esc_html__('Add Tax / Fee (Percentage)','b2bking');
	        		break;

	        		case 'add_tax_amount':
	        		$what = esc_html__('Add Tax / Fee (Amount)','b2bking');
	        		break;

	        		case 'replace_prices_quote':
	        		$what = esc_html__('Replace Cart with Quote System','b2bking');
	        		break;

	        		case 'set_currency_symbol':
	        		$what = esc_html__('Set Currency Symbol','b2bking');
	        		break;

	        		case 'payment_method_minimum_order':
	        		$what = esc_html__('Payment Method Minimum Order','b2bking');
	        		break;
	        	}
	            echo '<span class="b2bking_dynamic_rule_column_text_'.esc_attr($class).'">'.esc_html($what).'</span>';
	            break;

	        case 'b2bking_howmuch':
	        	$howmuch = get_post_meta($post_id, 'b2bking_rule_howmuch', true);
	        	$what = get_post_meta($post_id, 'b2bking_rule_what', true);
	        	$quantity_value = get_post_meta($post_id, 'b2bking_rule_quantity_value', true);
	        	if (!empty($howmuch) && $what !== 'free_shipping' && $what !== 'hidden_price' && $what !== 'tax_exemption' && $what !== 'tax_exemption_user') {
	        		if ($what === 'discount_percentage' || $what === 'add_tax_percentage'){
	        			echo esc_html($howmuch).'%';
	        		} else if ($what === 'discount_amount' || $what === 'fixed_price' || $what === 'add_tax_amount'){
	        			echo wc_price(esc_html($howmuch));
	        		} else if ($what === 'minimum_order' || $what === 'maximum_order'){
	        			if ($quantity_value === 'value'){
	   	     				echo wc_price(esc_html($howmuch));
	   	     			} else if ($quantity_value === 'quantity'){
	   	     				echo esc_html($howmuch).' '.esc_html__('pieces','b2bking');
	   	     			}
	   	     		} else if ($what === 'required_multiple'){
	   	     			echo esc_html($howmuch).' '.esc_html__('pieces','b2bking');
	   	     		} else {
	   	     			echo esc_html($howmuch);
	   	     		}
	        	} else {
	        		echo '-';
	        	}
	        	break;

	        case 'b2bking_conditions':
	        	$conditions = get_post_meta( $post_id , 'b2bking_rule_conditions' , true );
	        	if (empty($conditions)){
	        		esc_html_e('no','b2bking');
	        	} else {
	        		echo '<strong>'.esc_html__('yes', 'b2bking').'</strong>';
	        	}
	        	break;

	        case 'b2bking_applies' :
	            $applies = get_post_meta( $post_id , 'b2bking_rule_applies' , true );
	            if ($applies !== '' && $applies !== NULL){
		            $applies = explode ('_',$applies);
		            switch ($applies[0]) {
		            	case 'cart':
		            	$applies = esc_html__("Cart Total",'b2bking');
		            	break;

		            	case 'multiple':
		            	$applies = esc_html__("Multiple Options",'b2bking');
		            	break;

		            	case 'category':
		            	$applies = esc_html__('Category: ','b2bking').get_term( $applies[1] )->name;
		            	break;

		            	case 'product':
		            	$applies = esc_html__('Product: ','b2bking').get_the_title(intval($applies[1]));
		            	break;

		            	case 'one':
		            	$applies = esc_html__('One-Time (Unique) ','b2bking');
		            	break;
		            }

		            $what = get_post_meta($post_id, 'b2bking_rule_what', true);
		            if ($what === 'tax_exemption_user'){
		            	$applies = '-';
		            }
		            if (is_array($applies)){
		            	$applies = '-';
		            }
	            	echo '<strong>'.esc_html($applies).'</strong>';
	           	 	break;
	           	}

	        case 'b2bking_who' :
	            $who = get_post_meta( $post_id , 'b2bking_rule_who' , true );
	            if ($who !== '' && $who !==NULL){
		            $who = explode ('_',$who);
		            switch ($who[0]){
		            	case 'everyone':
		            	
		            	if (isset($who[2])){
			            	if ($who[2] === 'b2b'){
			            		$who = esc_html__('All registered B2B users','b2bking');
			            	} else if ($who[2] === 'b2c'){
			            		$who = esc_html__('All registered B2C users','b2bking');
			            	}
		            	} else {
		            		$who = esc_html__('All registered users','b2bking');
		            	}
		            	break;

		            	case 'user':
		            	
		            	if (intval($who[1]) !== 0){
		            		// if registered user, get user login
		            		$user = get_user_by('id', $who[1]);
		            		$who = $user->user_login;
		            	} else {
		            		// if guest user
		            		$who = esc_html__('All guest users','b2bking');
		            	}
		            	break;

		            	case 'group':
		            	$group = get_the_title($who[1]);
		            	$who = $group;
		            	break;

		            	case 'multiple':
		            	$who = esc_html__('Multiple Options', 'b2bking');
		            	break;
		            }
		            if (is_array($who)){
		            	$who = '';
		            }
		            echo esc_html($who);
		            break;
		        }
	    }
	}


	// Add custom columns to Conversations menu
	function b2bking_add_columns_conversation_menu($columns) {

		$columns_initial = $columns;

		// rename title
		$columns = array(
			'b2bking_user' => esc_html__( 'User', 'b2bking' ),
			'title' => esc_html__( 'Conversation', 'b2bking' ),
			'b2bking_type' => esc_html__( 'Type', 'b2bking' ),
			'b2bking_status' => esc_html__( 'Status', 'b2bking' ),
			'b2bking_lastreplydate' => esc_html__( 'Date of last reply', 'b2bking' ),
		);

		$columns = array_slice($columns_initial, 0, 1, true) + $columns;


	    return $columns;
	}

	/*
	* Functions dealing with custom user meta data START
	*/
	function b2bking_show_user_meta_profile($user){
		if (isset($user->ID)){
		?>
			<input type="hidden" id="b2bking_admin_user_id" value="<?php echo esc_attr($user->ID);?>">
		    <h3><?php esc_html_e("B2B User Settings (B2BKing)", "b2bking"); ?></h3>
		    
		    <?php
		    // Only show B2B Enabled and User customer group if user account is not in approval process
		    // Also don't show for subaccounts

		    // check this is not "new panel"
		    if (isset($user->ID)){
		    	$account_type = get_user_meta($user->ID, 'b2bking_account_type', true);
		    	if ($account_type === 'subaccount'){
		    		esc_html_e('This account is a subaccount. Its parent account is: ', 'b2bking');
		    		$parent_account = get_user_meta($user->ID, 'b2bking_account_parent', true);
		    		$parent_user = get_user_by('id', $parent_account);
		    		echo esc_html($parent_user->user_login);
		    	}

		    	$user_approved = get_user_meta($user->ID, 'b2bking_account_approved', true);
		    } else {
		    	$user_approved = 'newuser';
		    	$account_type =  'newuser';
		    	$user = (object) [
		    	    "ID" => "-2",
		    	];
		    }
		   
		    if ($user_approved !== 'no' && $account_type !== 'subaccount'){
		    	?>
		    	<div class="b2bking_user_shipping_payment_methods_container">
		    		<div class="b2bking_user_shipping_payment_methods_container_top">
		    			<div class="b2bking_user_shipping_payment_methods_container_top_title">
		    				<?php esc_html_e('User Settings','b2bking'); ?>
		    			</div>		
		    		</div>
		    		<div class="b2bking_user_settings_container">
		    			<div class="b2bking_user_settings_container_column">
		    				<div class="b2bking_user_settings_container_column_title">
		    					<svg class="b2bking_user_settings_container_column_title_icon_right" xmlns="http://www.w3.org/2000/svg" width="31" height="29" fill="none" viewBox="0 0 31 29">
		    					  <path fill="#C4C4C4" d="M15.5 6.792V.625H.083v27.75h30.834V6.792H15.5zm-9.25 18.5H3.167v-3.084H6.25v3.084zm0-6.167H3.167v-3.083H6.25v3.083zm0-6.167H3.167V9.875H6.25v3.083zm0-6.166H3.167V3.708H6.25v3.084zm6.167 18.5H9.333v-3.084h3.084v3.084zm0-6.167H9.333v-3.083h3.084v3.083zm0-6.167H9.333V9.875h3.084v3.083zm0-6.166H9.333V3.708h3.084v3.084zm15.416 18.5H15.5v-3.084h3.083v-3.083H15.5v-3.083h3.083v-3.084H15.5V9.875h12.333v15.417zM24.75 12.958h-3.083v3.084h3.083v-3.084zm0 6.167h-3.083v3.083h3.083v-3.083z"/>
		    					</svg>
		    					<?php esc_html_e('Customer Group','b2bking'); ?>
		    				</div>
		    				<select name="b2bking_customergroup" id="b2bking_customergroup" class="b2bking_user_settings_select">
		    					<?php
		    						$user_is_b2b = get_the_author_meta( 'b2bking_b2buser', $user->ID );
		    						if ($user_is_b2b === 'yes'){
		    							// do nothing
		    						} else {
		    							$user_is_b2b = 'no';
		    						}

		    					?>
		    					<optgroup label="<?php esc_html_e('B2C Group', 'b2bking'); ?>">
		    						<?php echo '<option value="b2cuser" '.selected('no', $user_is_b2b, false).'>'.esc_html__('B2C Users', 'b2bking').'</option>'; ?>
		    					</optgroup>
		    					<optgroup label="<?php esc_html_e('B2B Groups', 'b2bking'); ?>">
				    				<?php 
				    					$posts = get_posts([
				    					  'post_type' => 'b2bking_group',
				    					  'post_status' => 'publish',
				    					  'numberposts' => -1
				    					]);
				    					foreach ($posts as $post){
				    						if ($user_is_b2b === 'yes'){
				    							// if user is b2b, select the b2b group
				    							echo '<option value="'.esc_attr($post->ID).'" '.selected($post->ID, get_the_author_meta( 'b2bking_customergroup', $user->ID ),false).'>'.esc_html($post->post_title).'</option>';
				    						} else {
				    							// if user is b2c, dont select the b2b group
				    							echo '<option value="'.esc_attr($post->ID).'" >'.esc_html($post->post_title).'</option>';
				    						}
				    					}
				    				?>
		    					</optgroup>
		    				</select>
		    			</div>
		    		</div>

		    		<!-- Information panel -->
		    		<div class="b2bking_user_settings_information_box">
		    			<svg class="b2bking_group_payment_shipping_information_box_icon" xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 36 36">
		    			  <path fill="#358BBB" d="M18 0C8.06 0 0 8.06 0 18s8.06 18 18 18 18-8.06 18-18S27.94 0 18 0zm0 28.446a1.607 1.607 0 110-3.213 1.607 1.607 0 010 3.213zm2.527-8.819a1.941 1.941 0 00-1.241 1.8v.912a.322.322 0 01-.322.322h-1.928a.322.322 0 01-.322-.322v-.864c0-.928.27-1.844.8-2.607a4.49 4.49 0 012.093-1.643c1.366-.527 2.25-1.672 2.25-2.921 0-1.772-1.732-3.215-3.857-3.215s-3.857 1.443-3.857 3.215v.305a.322.322 0 01-.322.321h-1.928a.322.322 0 01-.322-.321v-.305c0-1.58.691-3.054 1.945-4.15C14.721 9.095 16.312 8.517 18 8.517c1.688 0 3.279.582 4.484 1.635 1.253 1.097 1.945 2.572 1.945 4.15 0 2.323-1.531 4.412-3.902 5.324z"/>
		    			</svg>
		    			<?php esc_html_e('If you are running a hybrid B2B / B2C shop, here you can set users as B2B or B2C and control their group.','b2bking'); ?>
		    		</div>
				</div>
					        	
			<br /><br />
			<?php

			}

			if ($user_approved !== 'no' && $account_type !== 'subaccount'){
			?>
		    <!-- User-specific shipping and payment methods -->
    	    <div class="b2bking_user_shipping_payment_methods_container">
    	    	<div class="b2bking_user_shipping_payment_methods_container_top">
    	    		<div class="b2bking_user_shipping_payment_methods_container_top_title">
    	    			<?php esc_html_e('Shipping and Payment Methods','b2bking'); ?>
    	    		</div>		
    	    	</div>
    	    	<div class="b2bking_user_shipping_payment_methods_container_content">
    	    		<div class="b2bking_user_shipping_payment_methods_container_content_override">
    	    			<div class="b2bking_user_shipping_payment_methods_container_content_override_title">
    	    				<svg class="b2bking_user_shipping_payment_methods_container_content_override_title_icon" xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="none" viewBox="0 0 37 37">
    	    				  <path fill="#C4C4C4" d="M24.667 13.875c9.25 0 9.25 6.167 9.25 6.167v3.083h-9.25v-3.083s0-2.606-1.773-4.934a8.125 8.125 0 00-.925-1.017c.801-.123 1.68-.216 2.698-.216zm-12.334 3.083c5.396 0 6.074 2.405 6.167 3.084H6.167c.092-.679.77-3.084 6.166-3.084zm0-3.083c-9.25 0-9.25 6.167-9.25 6.167v3.083h18.5v-3.083s0-6.167-9.25-6.167zm1.542 12.333v3.084h9.25v-3.084l4.625 4.625-4.625 4.625v-3.083h-9.25v3.083L9.25 30.833l4.625-4.625zM12.333 4.625c.848 0 1.542.694 1.542 1.542 0 .848-.694 1.541-1.542 1.541a1.546 1.546 0 01-1.541-1.541c0-.848.693-1.542 1.541-1.542zm0-3.083a4.619 4.619 0 00-4.625 4.625 4.619 4.619 0 004.625 4.625 4.619 4.619 0 004.625-4.625 4.619 4.619 0 00-4.625-4.625zm12.334 0a4.619 4.619 0 00-4.625 4.625 4.619 4.619 0 004.625 4.625 4.619 4.619 0 004.625-4.625 4.619 4.619 0 00-4.625-4.625z"/>
    	    				</svg>
    	    				<?php esc_html_e('Set Shipping and Payment Methods','b2bking'); ?>
    	    			</div>
    	    			<select class="b2bking_user_shipping_payment_methods_container_content_override_select" name="b2bking_user_shipping_payment_methods_override" id="b2bking_user_shipping_payment_methods_override">
    	    				<option value="default" <?php selected('default', get_user_meta($user->ID, 'b2bking_user_shipping_payment_methods_override', true), true); ?>> <?php esc_html_e('Follow group rules (default / automatic)','b2bking'); ?></option>
    	    				<option value="manual" <?php selected('manual', get_user_meta($user->ID, 'b2bking_user_shipping_payment_methods_override', true), true); ?>><?php esc_html_e('Manual setting (override group settings)','b2bking'); ?></option>
    	    			</select>
    	    		</div>
    	    		<div class="b2bking_user_payment_shipping_methods_container">
    	    			<div class="b2bking_group_payment_shipping_methods_container_element">
    	    				<div class="b2bking_group_payment_shipping_methods_container_element_title">
    	    					<svg class="b2bking_group_payment_shipping_methods_container_element_title_icon_shipping" xmlns="http://www.w3.org/2000/svg" width="37" height="26" fill="none" viewBox="0 0 37 26">
    	    					  <path fill="#C4C4C4" d="M31.114 6.5h-4.205V3.25c0-1.788-1.514-3.25-3.363-3.25H3.364C1.514 0 0 1.462 0 3.25v14.625c0 1.788 1.514 3.25 3.364 3.25C3.364 23.823 5.617 26 8.409 26s5.045-2.177 5.045-4.875h10.091c0 2.698 2.254 4.875 5.046 4.875 2.792 0 5.045-2.177 5.045-4.875h1.682c.925 0 1.682-.731 1.682-1.625v-5.411c0-.699-.236-1.382-.673-1.95L32.46 7.15a1.726 1.726 0 00-1.345-.65zM8.409 22.75c-.925 0-1.682-.731-1.682-1.625S7.484 19.5 8.41 19.5c.925 0 1.682.731 1.682 1.625s-.757 1.625-1.682 1.625zM31.114 8.937L34.41 13h-7.5V8.937h4.204zM28.59 22.75c-.925 0-1.682-.731-1.682-1.625s.757-1.625 1.682-1.625c.925 0 1.682.731 1.682 1.625s-.757 1.625-1.682 1.625z"></path>
    	    					</svg>
    	    					<?php esc_html_e('Shipping Methods', 'b2bking'); ?>
    	    				</div>

    	    				<?php

    	    				$add_group_check = '';
    	    				// if current screen is Add / Create new user, check all methods by default
    	    				if( get_current_screen()->action === 'add'){
    	    		        	$add_group_check = 'checked="checked"';
    	    		        }

    	    		        if (apply_filters('b2bking_use_zone_shipping_control', true)){

    		    				// list all shipping methods
        						$shipping_methods = array();
        						$zone_names = array();
        						$zone = 0;

        						$delivery_zones = WC_Shipping_Zones::get_zones();
        				        foreach ($delivery_zones as $key => $the_zone) {
        				            foreach ($the_zone['shipping_methods'] as $value) {
        				                array_push($shipping_methods, $value);
        				                array_push($zone_names, $the_zone['zone_name']);
        				            }
        				        }

    		                    // add UPS exception
    		               		$shipping_methods_extra = WC()->shipping->get_shipping_methods();
    		               		foreach ($shipping_methods_extra as $shipping_method){
    		               			if ($shipping_method->id === 'wf_shipping_ups'){
    		               				array_push($shipping_methods, $shipping_method);
    		               				array_push($zone_names, 'UPS');
    		               			}
    		               		}

    		    				foreach ($shipping_methods as $shipping_method){
    		    					if( $shipping_method->enabled === 'yes' ){

    		    						if (!metadata_exists('user', $user->ID, 'b2bking_user_shipping_method_'.esc_attr($shipping_method->id).esc_attr($shipping_method->instance_id))){
    		    							$checkedval = 1;
    		    						} else {
    		    							$checkedval = intval(get_user_meta($user->ID, 'b2bking_user_shipping_method_'.esc_attr($shipping_method->id.$shipping_method->instance_id), true));
    		    						}

    			    					?>
    			    					<div class="b2bking_group_payment_shipping_methods_container_element_method">
    			    						<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
    			    							<?php echo esc_html($shipping_method->title).' ('.esc_html($zone_names[$zone]).')'; ?>
    			    						</div>
    			    						<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_user_shipping_method_<?php echo esc_attr($shipping_method->id.$shipping_method->instance_id); ?>" name="b2bking_user_shipping_method_<?php echo esc_attr($shipping_method->id.$shipping_method->instance_id); ?>" <?php checked(1, $checkedval, true); echo esc_attr($add_group_check); ?>>
    			    					</div>
    			    					<?php
    		    					}
    		    					$zone++;
    		    				}

    		    			} else {
    		    				// older shipping mechanism here for cases where needed

    		    				// list all shipping methods
    		    				$shipping_methods = WC()->shipping->get_shipping_methods();

    		    				foreach ($shipping_methods as $shipping_method){
    		    					?>
    		    					<div class="b2bking_group_payment_shipping_methods_container_element_method">
    		    						<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
    		    							<?php echo esc_html($shipping_method->method_title); ?>
    		    						</div>
    		    						<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_user_shipping_method_<?php echo esc_attr($shipping_method->id); ?>" name="b2bking_user_shipping_method_<?php echo esc_attr($shipping_method->id); ?>" <?php checked(1, intval(get_user_meta($user->ID, 'b2bking_user_shipping_method_'.esc_attr($shipping_method->id), true)), true); echo esc_attr($add_group_check); ?>>
    		    					</div>
    		    					<?php
    		    				
    		    				}
    		    			}
    	    				?>

    	    			</div>
    	    			<div class="b2bking_group_payment_shipping_methods_container_element">
    	    				<div class="b2bking_group_payment_shipping_methods_container_element_title">
    	    					<svg class="b2bking_group_payment_shipping_methods_container_element_title_icon_payment" xmlns="http://www.w3.org/2000/svg" width="37" height="30" fill="none" viewBox="0 0 37 30">
    	    					  <path fill="#C4C4C4" d="M33.3 0H3.7A3.672 3.672 0 00.018 3.7L0 25.9c0 2.053 1.647 3.7 3.7 3.7h29.6c2.053 0 3.7-1.647 3.7-3.7V3.7C37 1.646 35.353 0 33.3 0zm0 25.9H3.7V14.8h29.6v11.1zm0-18.5H3.7V3.7h29.6v3.7z"/>
    	    					</svg>
    	    					<?php esc_html_e('Payment Methods', 'b2bking'); ?>
    	    				</div>

    	    				<?php
    	    				// list all payment methods
    	    				$payment_methods = WC()->payment_gateways->payment_gateways();

    	    				foreach ($payment_methods as $payment_method){
    	    					if( $payment_method->enabled === 'yes' ){

    	    						if (!metadata_exists('user', $user->ID, 'b2bking_user_payment_method_'.esc_attr($payment_method->id))){
    	    							$checkedval = 1;
    	    						} else {
    	    							$checkedval = intval(get_user_meta($user->ID, 'b2bking_user_payment_method_'.esc_attr($payment_method->id), true));
    	    						}

    		    					?>
    		    					<div class="b2bking_group_payment_shipping_methods_container_element_method">
    		    						<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
    		    							<?php echo esc_html($payment_method->title); ?>
    		    						</div>
    		    						<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_user_payment_method_<?php echo esc_attr($payment_method->id); ?>" name="b2bking_user_payment_method_<?php echo esc_attr($payment_method->id); ?>" <?php checked(1, intval(get_user_meta($user->ID, 'b2bking_user_payment_method_'.esc_attr($payment_method->id), true)), true); echo esc_attr($add_group_check); ?>>
    		    					</div>
    		    					<?php
    		    				}
    	    				}
    	    				?>

    	    			</div>
    	    		</div>

    	    		<!-- Information panel -->
    	    		<div class="b2bking_group_payment_shipping_information_box">
    	    			<svg class="b2bking_group_payment_shipping_information_box_icon" xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 36 36">
    	    			  <path fill="#358BBB" d="M18 0C8.06 0 0 8.06 0 18s8.06 18 18 18 18-8.06 18-18S27.94 0 18 0zm0 28.446a1.607 1.607 0 110-3.213 1.607 1.607 0 010 3.213zm2.527-8.819a1.941 1.941 0 00-1.241 1.8v.912a.322.322 0 01-.322.322h-1.928a.322.322 0 01-.322-.322v-.864c0-.928.27-1.844.8-2.607a4.49 4.49 0 012.093-1.643c1.366-.527 2.25-1.672 2.25-2.921 0-1.772-1.732-3.215-3.857-3.215s-3.857 1.443-3.857 3.215v.305a.322.322 0 01-.322.321h-1.928a.322.322 0 01-.322-.321v-.305c0-1.58.691-3.054 1.945-4.15C14.721 9.095 16.312 8.517 18 8.517c1.688 0 3.279.582 4.484 1.635 1.253 1.097 1.945 2.572 1.945 4.15 0 2.323-1.531 4.412-3.902 5.324z"/>
    	    			</svg>
    	    			<?php esc_html_e('In this panel, you can enable and disable shipping and payment methods for this specific user.','b2bking'); ?>
    	    		</div>
    	    	</div>
    	    </div>
		    <br /><br />
		    <?php
			}
			
		    // show all custom user data gathered on registration (registration role + fields) 
		    $custom_fields = get_user_meta($user->ID, 'b2bking_custom_fields_string', true);

		    // let's add to this list all editable fields the user does not already have
    		// Get all enabled editable fields
    		$custom_fields_editable = get_posts([
    			    		'post_type' => 'b2bking_custom_field',
    			    	  	'post_status' => 'publish',
    			    	  	'numberposts' => -1,
    			    	  	'meta_key' => 'b2bking_custom_field_sort_number',
    		    	  	    'orderby' => 'meta_value_num',
    		    	  	    'order' => 'ASC',
    		    	  	    'fields' => 'ids',
    			    	  	'meta_query'=> array(
    			    	  		'relation' => 'AND',
    			                array(
    		                        'key' => 'b2bking_custom_field_status',
    		                        'value' => 1
    			                ),
    			                array(
    		                        'key' => 'b2bking_custom_field_editable',
    		                        'value' => 1
    			                ),
    		            	)
    			    	]);

    		$custom_fields_array_exploded = explode(',', $custom_fields);

    		$editable_added = array();
    		foreach ($custom_fields_editable as $editable_field){
    			if (!in_array($editable_field, $custom_fields_array_exploded)){
    				$custom_fields .= $editable_field.',';
    				array_push($editable_added,$editable_field);
    			}
    		}

		    ?>
		    <input type="hidden" id="b2bking_admin_user_fields_string" value="<?php echo esc_attr($custom_fields);?>">
		    <?php
		    $registration_role = get_user_meta($user->ID, 'b2bking_registration_role', true);
		    $account_approved = get_user_meta($user->ID, 'b2bking_account_approved', true);

		    // show this panel if user 1) has custom fields OR 2) manual user approval is needed OR 3) there is a chosen registration role
		    if((trim($custom_fields) !== '' && $custom_fields !== NULL) || ($registration_role !== NULL && $registration_role !== '' && $registration_role !== false) || ($account_approved === 'no') ){

		    	?>
		    	
		    	<div id="b2bking_registration_data_container" class="b2bking_user_shipping_payment_methods_container">
		    		<div class="b2bking_user_shipping_payment_methods_container_top">
		    			<div class="b2bking_user_shipping_payment_methods_container_top_title">
		    				<?php esc_html_e('User Registration Data','b2bking'); ?>
		    			</div>		
		    		</div>

		    		<?php

				    // if there are custom fields or registration role, show 'Data collected at registration' (there may be no fields, only a need for approval)
		    		if((trim($custom_fields) !== '' && $custom_fields !== NULL) || ($registration_role !== NULL && $registration_role !== '')){
		    			// show header
		    			?>
		    			<div class="b2bking_user_registration_user_data_container">
		    				<div class="b2bking_user_registration_user_data_container_title">
		    					<svg class="b2bking_user_registration_user_data_container_title_icon" xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="none" viewBox="0 0 35 35">
		    					  <path fill="#C4C4C4" d="M29.531 0H3.281A3.29 3.29 0 000 3.281V31.72A3.29 3.29 0 003.281 35h26.25a3.29 3.29 0 003.282-3.281V3.28A3.29 3.29 0 0029.53 0zm-1.093 30.625H4.375V4.375h24.063v26.25zM8.75 15.312h15.313V17.5H8.75v-2.188zm0 4.376h15.313v2.187H8.75v-2.188zm0 4.375h15.313v2.187H8.75v-2.188zm0-13.125h15.313v2.187H8.75v-2.188z"/>
		    					</svg>
		    					<?php esc_html_e('Data Collected at Registration','b2bking'); ?>
		    				</div>

		    				<?php
		    				if ($registration_role !== NULL && $registration_role !== '' && $registration_role !== false){
		    					$role_name = get_the_title(explode('_',$registration_role)[1]);
		    					?>
		    					<div class="b2bking_user_registration_user_data_container_element">
		    						<div class="b2bking_user_registration_user_data_container_element_label">
		    							<?php esc_html_e('Registration role','b2bking'); ?>
		    						</div>
		    						<input type="text" class="b2bking_user_registration_user_data_container_element_text" value="<?php echo esc_attr($role_name); ?>" readonly>
		    					</div>
		    					<?php
		    				}

		    				if((trim($custom_fields) !== '' && $custom_fields !== NULL)){
		    					$custom_fields_array = explode(',', $custom_fields);
		    					foreach ($custom_fields_array as $field){
		    						if ($field !== '' && $field !== NULL){
		    							// get field data
		    							$field_value = get_user_meta($user->ID, 'b2bking_custom_field_'.$field, true);
		    							$field_label = get_post_meta($field, 'b2bking_custom_field_field_label', true);

		    							$field_type = get_post_meta($field, 'b2bking_custom_field_field_type', true);
		    							$field_billing_connection = get_post_meta($field, 'b2bking_custom_field_billing_connection', true);

		    							// display checkboxes
		    							if ($field_value !== '' && $field_value !== NULL && $field_type === 'checkbox'){

		    								?>
		    								<div class="b2bking_user_registration_user_data_container_element">
		    									<div class="b2bking_user_registration_user_data_container_element_label">
		    										<?php echo esc_html($field_label); ?>
		    									</div>
		    								<?php

	    									$select_options = get_post_meta($field, 'b2bking_custom_field_user_choices', true);
	    									$select_options = explode(',', $select_options);
	    									$i = 1;
	    									foreach ($select_options as $option){
	    										// get field and check if set
	    										$field_value_second = get_user_meta($user->ID, 'b2bking_custom_field_'.$field.'_option_'.$i, true);

	    										if ($field_value_second !== NULL && $field_value_second !== ''){
	    											// field is set, display it
	    											?>
	    											<input name="b2bking_custom_field_<?php echo esc_attr($field);?>" type="text" class="b2bking_user_registration_user_data_container_element_text" value="<?php echo esc_attr($field_value_second); ?>" >
	    											<?php
	    										}
	    										$i++;
	    									}
	    									?>
	    									</div>
	    									<?php
		    							}
		    							// display other fields
		    							if (($field_value !== '' && $field_value !== NULL && $field_type !== 'checkbox') || in_array($field, $editable_added)){
		    								?>
		    								<div class="b2bking_user_registration_user_data_container_element">
		    									<div class="b2bking_user_registration_user_data_container_element_label">
		    										<?php echo esc_html($field_label); ?>
		    									</div>
		    									<?php

		    									if ($field_type !== 'textarea' && $field_type !== 'file'){
		    										?>
		    										<input name="b2bking_custom_field_<?php echo esc_attr($field);?>" type="text" class="b2bking_user_registration_user_data_container_element_text" value="<?php echo esc_attr($field_value); ?>" >
		    										<?php
		    									} else if ($field_type === 'textarea'){
		    										?>
		    										<textarea name="b2bking_custom_field_<?php echo esc_attr($field);?>" class="b2bking_user_registration_user_data_container_element_textarea" ><?php echo esc_html($field_value); ?></textarea>
		    										<?php

		    									} else if ($field_type === 'file'){
		    										if (!is_wp_error($field_value)){
		    										?>
		    										<button class="b2bking_user_registration_user_data_container_element_download" value="<?php echo esc_attr($field_value); ?>" type="button">
		    											<svg class="b2bking_user_registration_user_data_container_element_download_icon" xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="none" viewBox="0 0 37 37">
		    											  <path fill="#fff" d="M22.547 25.52h-2.678v-8.754a.29.29 0 00-.289-.29h-2.168a.29.29 0 00-.289.29v8.755h-2.67a.288.288 0 00-.227.466l4.046 5.12a.289.289 0 00.456 0l4.046-5.12a.288.288 0 00-.227-.466z"/>
		    											  <path fill="#fff" d="M29.318 13.25c-1.655-4.365-5.871-7.469-10.81-7.469-4.94 0-9.157 3.1-10.812 7.465a7.23 7.23 0 00-5.383 6.988 7.224 7.224 0 007.222 7.227h1.45a.29.29 0 00.288-.29v-2.167a.29.29 0 00-.289-.29H9.535a4.454 4.454 0 01-3.215-1.361 4.478 4.478 0 01-1.261-3.274c.032-.954.357-1.85.946-2.605a4.528 4.528 0 012.389-1.58l1.37-.357.501-1.322a8.874 8.874 0 013.183-4.094 8.748 8.748 0 015.06-1.597c1.824 0 3.573.553 5.058 1.597a8.88 8.88 0 013.183 4.094l.499 1.319 1.366.36a4.5 4.5 0 013.327 4.34 4.455 4.455 0 01-1.311 3.17 4.446 4.446 0 01-3.165 1.31h-1.45a.29.29 0 00-.288.29v2.168c0 .159.13.289.289.289h1.449a7.224 7.224 0 007.223-7.227c0-3.35-2.28-6.168-5.37-6.984z"/>
		    											</svg>
		    											<?php esc_html_e('Download file','b2bking'); ?>
		    										</button>
		    										<?php
		    										} else {
		    											// error
		    											?>
		    											<input type="text" class="b2bking_user_registration_user_data_container_element_text" value="<?php esc_html_e('The file did not upload correctly','b2bking'); ?>" readonly>
		    											<?php
		    										}
		    									}

		    									?>
		    								</div>
		    								<?php
		    								// if field is billing_countrystate (country + state combined), show state as well
		    								if ($field_billing_connection === 'billing_countrystate'){
		    									$state_label = esc_html__('State', 'b2bking');
		    									$state_value = get_user_meta($user->ID, 'billing_state', true);
		    									if ($state_value !== NULL && $state_value !== ''){
			    									?>
			    									<div class="b2bking_user_registration_user_data_container_element">
			    										<div class="b2bking_user_registration_user_data_container_element_label">
			    											<?php echo esc_html($state_label); ?>
			    										</div>
			    										<input type="text" class="b2bking_user_registration_user_data_container_element_text" value="<?php echo esc_attr($state_value); ?>" >
			    									</div>
			    									<?php
		    									}
		    								}
		    								?>
		    								<?php
		    							}
		    						} 
		    					}
							}

							if ($account_approved === 'no'){
								?>
								<div class="b2bking_user_registration_user_data_container_element">
									<div class="b2bking_user_registration_user_data_container_element_label">
										<?php esc_html_e('Registration Approval','b2bking'); ?>
									</div>
									<div class="b2bking_user_registration_user_data_container_element_approval">
										<select class="b2bking_user_registration_user_data_container_element_select_group">
											<?php
											$groups = get_posts([
											  'post_type' => 'b2bking_group',
											  'post_status' => 'publish',
											  'numberposts' => -1
											]);
											$automatic_approval_default = get_user_meta($user->ID, 'b2bking_default_approval_manual', true);

											foreach ($groups as $group){
												echo '<option value="'.esc_attr($group->ID).'">'.esc_html($group->post_title).'</option>';
											}
											if (empty($groups)){
												echo '<option value="nogroup">'.esc_html__('No group is set up. Please create a customer group', 'b2bking').'</option>';
											}

											// IF SALESKING ACTIVATED, INTEGRATE WITH SALESKING BY SHOWING OPTIONS
											if (defined('SALESKING_DIR')){
												// display all sales agents groups
												$groups = get_posts( array( 'post_type' => 'salesking_group','post_status'=>'publish','numberposts' => -1) );
												if (!empty($groups)){
													?>
													<optgroup label="<?php esc_html_e('Sales Agent Groups', 'b2bking'); ?>">
														<?php
														foreach ($groups as $group){
															echo '<option value="saleskinggroup_'.esc_attr($group->ID).'" '.selected('saleskinggroup_'.$group->ID,$automatic_approval_default,false).'>'.esc_html($group->post_title).'</option>';
														}
													?>
													</optgroup>
													<?php
												}
											}
											?>
										</select>
										<div class="b2bking_user_registration_user_data_container_element_approval_buttons_container">
											<input type="hidden" value="<?php echo esc_attr($user->ID); ?>" id="b2bking_user_registration_data_id">
											<button type="button" class="b2bking_user_registration_user_data_container_element_approval_button_approve">
												<svg class="b2bking_user_registration_user_data_container_element_approval_button_approve_icon" xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="none" viewBox="0 0 35 35">
												  <path fill="#fff" d="M17.5 0C7.85 0 0 7.85 0 17.5S7.85 35 17.5 35 35 27.15 35 17.5 27.15 0 17.5 0zm9.108 11.635L15.3 25.096a1.346 1.346 0 01-1.01.48h-.022a1.345 1.345 0 01-1-.445L8.42 19.746a1.346 1.346 0 112-1.8l3.811 4.234L24.546 9.903a1.346 1.346 0 012.062 1.732z"/>
												</svg>
												<?php esc_html_e('Approve user','b2bking'); ?>
											</button>
											<button type="button" class="b2bking_user_registration_user_data_container_element_approval_button_reject">
												<svg class="b2bking_user_registration_user_data_container_element_approval_button_reject_icon" xmlns="http://www.w3.org/2000/svg" width="29" height="29" fill="none" viewBox="0 0 29 29">
												  <path fill="#fff" d="M9.008 2.648h-.29a.29.29 0 00.29-.289v.29h10.984v-.29c0 .16.13.29.29.29h-.29V5.25h2.602V2.36A2.315 2.315 0 0020.28.046H8.72a2.315 2.315 0 00-2.313 2.312V5.25h2.602V2.648zm18.21 2.602H1.782c-.64 0-1.156.517-1.156 1.156v1.157c0 .158.13.289.29.289h2.181l.893 18.897a2.314 2.314 0 002.309 2.204h16.404a2.31 2.31 0 002.309-2.204l.893-18.897h2.182a.29.29 0 00.289-.29V6.407c0-.64-.517-1.156-1.156-1.156zm-4.794 21.102H6.576l-.874-18.5h17.596l-.874 18.5z"/>
												</svg>
												<?php esc_html_e('Reject and delete user','b2bking'); ?>
											</button>
										</div>
									</div>
								</div>
								<?php
							} else {
								// set up button for "Update registration fields"
								?>
							
								<?php
							}
		    				?>
		    				</div>
		    			</div>
		    			<?php
		    		}
					?>
				</div>
			<?php
			}
		    ?>
		<?php 
		}

	}

	function b2bking_save_user_meta_customer_group($user_id ){
		if ( !current_user_can( 'edit_user', $user_id ) ) { 
		    return false; 
		}

		// delete all b2bking transients
		// Must clear transients and rules cache when user group is changed because now new rules may apply.
		global $wpdb;
		$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%transient_b2bking%'" );
		foreach( $plugin_options as $option ) {
		    delete_option( $option->option_name );
		}

		if (isset($_POST['b2bking_customergroup'])){
			$customer_group = sanitize_text_field($_POST['b2bking_customergroup']);
			if ($customer_group === 'b2cuser'){
				update_user_meta( $user_id, 'b2bking_b2buser', 'no');
				update_user_meta( $user_id, 'b2bking_customergroup', 'no');
			} else {
				update_user_meta( $user_id, 'b2bking_customergroup', $customer_group);
				update_user_meta( $user_id, 'b2bking_b2buser', 'yes');
			}

			// remove existing roles of b2bking, and add new role
			$groups = get_posts([
			  'post_type' => 'b2bking_group',
			  'post_status' => 'publish',
			  'numberposts' => -1,
			  'fields' => 'ids',
			]);


			if (apply_filters('b2bking_use_wp_roles', false)){

				$user_obj = new WP_User($user_id);
				$user_obj->remove_role('b2bking_role_b2cuser');
				foreach ($groups as $group){
					$user_obj->remove_role('b2bking_role_'.$group);
				}
				$user_obj->add_role('b2bking_role_'.$customer_group);

				if (apply_filters('b2bking_use_wp_roles_only_b2b', false)){
					$user_obj->set_role('b2bking_role_'.$customer_group);
				}
			}
		}



		// Save Payment methods and Shipping Methods
		$user_override = filter_input(INPUT_POST, 'b2bking_user_shipping_payment_methods_override'); 
		if ($user_override !== NULL){
			update_user_meta( $user_id, 'b2bking_user_shipping_payment_methods_override', $user_override);
		}

		$shipping_methods = WC()->shipping->get_shipping_methods();

		foreach ($shipping_methods as $shipping_method){
			$method = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_user_shipping_method_'.$shipping_method->id));
			if ($method !== NULL){
				update_user_meta( $user_id, 'b2bking_user_shipping_method_'.$shipping_method->id, $method);
			}
		}

		$payment_methods = WC()->payment_gateways->payment_gateways();

		foreach ($payment_methods as $payment_method){
			$method = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_user_payment_method_'.$payment_method->id));
			if ($method !== NULL){
				update_user_meta( $user_id, 'b2bking_user_payment_method_'.$payment_method->id, $method);
			}
		}

	}
	function b2bking_add_columns_user_table ($columns){
	    $columns['b2bking_customergroup'] = esc_html__('Customer Group','b2bking');
	    $columns['b2bking_approval'] = esc_html__('Pending Approval','b2bking');
		return $columns;
	}
	function b2bking_retrieve_group_column_contents_users_table( $val, $column_name, $user_id ) {
	    switch ($column_name) {
	        case 'b2bking_customergroup' :

	        	// first check if subaccount. If subaccount, user is equivalent with parent
	        	$account_type = get_user_meta($user_id, 'b2bking_account_type', true);
	        	if ($account_type === 'subaccount'){
	        		// get parent
	        		$is_subaccount = 'yes';
	        		$parent_account_id = get_user_meta ($user_id, 'b2bking_account_parent', true);
	        		$user_id = $parent_account_id;
	        	} else {
	        		$is_subaccount = 'no';
	        	}


	        	$user_is_b2b = get_the_author_meta( 'b2bking_b2buser', $user_id );
	        	if ($user_is_b2b === 'yes'){
	        		// do nothing
	        	} else {
	        		$user_is_b2b = 'no';
	        	}
	        	if ($user_is_b2b === 'yes'){
	        		if ($is_subaccount === 'yes'){
	            		return esc_html__('Subaccount of ','b2bking').esc_html(get_the_title(get_the_author_meta( 'b2bking_customergroup', $user_id )));
	            	} else {
	            		return esc_html(get_the_title(get_the_author_meta( 'b2bking_customergroup', $user_id )));
	            	}
	            } else {
	            	return esc_html__('B2C Users', 'b2bking');
	            }
	        case 'b2bking_approval' :
	        	$account_approved = get_user_meta($user_id, 'b2bking_account_approved', true );
	        	if ($account_approved === 'no'){
	        		return '<span class="b2bking_users_column_waiting_approval">'.esc_html__('Waiting approval', 'b2bking').'</span>';
	        	} else {
	        		return esc_html__('No', 'b2bking');
	        	}
			default:
	    }
	    return $val;
	}
	/*
	* Functions dealing with custom user meta data END
	*/

	/*
	* Functions dealing with custom category meta data START
	*/
	// Enable visibility settings in Add Category
	function b2bking_enable_visibility_settings_add_category(){
	    if ( ! current_user_can( 'manage_woocommerce' ) ) { return; }
	    ?>
	    <div class="b2bking_group_visibility_container">
	    	<div class="b2bking_group_visibility_container_top">
	    		<?php esc_html_e( 'Group Visibility (B2BKing)', 'b2bking' ); ?>
	    	</div>
	    	<div class="b2bking_group_visibility_container_content">
	    		<div class="b2bking_group_visibility_container_content_title">
	    			<svg class="b2bking_group_visibility_container_content_title_icon" xmlns="http://www.w3.org/2000/svg" width="31" height="29" fill="none" viewBox="0 0 31 29">
					<path fill="#C4C4C4" d="M15.5 6.792V.625H.083v27.75h30.834V6.792H15.5zm-9.25 18.5H3.167v-3.084H6.25v3.084zm0-6.167H3.167v-3.083H6.25v3.083zm0-6.167H3.167V9.875H6.25v3.083zm0-6.166H3.167V3.708H6.25v3.084zm6.167 18.5H9.333v-3.084h3.084v3.084zm0-6.167H9.333v-3.083h3.084v3.083zm0-6.167H9.333V9.875h3.084v3.083zm0-6.166H9.333V3.708h3.084v3.084zm15.416 18.5H15.5v-3.084h3.083v-3.083H15.5v-3.083h3.083v-3.084H15.5V9.875h12.333v15.417zM24.75 12.958h-3.083v3.084h3.083v-3.084zm0 6.167h-3.083v3.083h3.083v-3.083z"></path>
					</svg>
					<?php esc_html_e( 'Groups who can see this category (PREMIUM)', 'b2bking' ); ?>
	    		</div>
	    		<!-- Add B2C and Guest Users group -->
	    		<hr>
	    		<div class="b2bking_user_registration_user_data_container_element_label"><?php esc_html_e('B2C Groups', 'b2bking'); ?></div>
	    		<div class="b2bking_group_visibility_container_content_checkbox">
	    			<div class="b2bking_group_visibility_container_content_checkbox_name">
	    				<?php esc_html_e('Guest Users (Logged Out)', 'b2bking'); ?>
	    			</div>
	    			<input type="hidden" name="b2bking_group_0" value="0">
	    			<input disabled type="checkbox" value="1" class="b2bking_group_visibility_container_content_checkbox_input" name="b2bking_group_0" id="b2bking_group_0" value="1" />
	    		</div>
	    		<div class="b2bking_group_visibility_container_content_checkbox">
	    			<div class="b2bking_group_visibility_container_content_checkbox_name">
	    				<?php esc_html_e('B2C Users', 'b2bking'); ?>
	    			</div>
	    			<input type="hidden" name="b2bking_group_b2c" value="0">
	    			<input disabled type="checkbox" value="1" class="b2bking_group_visibility_container_content_checkbox_input" name="b2bking_group_b2c" id="b2bking_group_b2c" value="1" />
	    		</div>
	    		<br>
	    		<div class="b2bking_user_registration_user_data_container_element_label"><?php esc_html_e('B2B Groups', 'b2bking'); ?></div>
            	<?php
	            	$groups = get_posts([
	            	  'post_type' => 'b2bking_group',
	            	  'post_status' => 'publish',
	            	  'numberposts' => -1
	            	]);
	            	foreach ($groups as $group){
	            		?>
	            		<div class="b2bking_group_visibility_container_content_checkbox">
	            			<div class="b2bking_group_visibility_container_content_checkbox_name">
	            				<?php echo esc_html($group->post_title); ?>
	            			</div>
	            			<input type="hidden" name="b2bking_group_<?php echo esc_attr($group->ID);?>" value="0">
	            			<input disabled type="checkbox" value="1" class="b2bking_group_visibility_container_content_checkbox_input" name="b2bking_group_<?php echo esc_attr($group->ID);?>" id="b2bking_group_<?php echo esc_attr($group->ID);?>" value="1" />
	            		</div>
	            		<?php
	            	}
	            ?>
	    	</div>
	    </div>

	    <div class="b2bking_group_visibility_container">
	    	<div class="b2bking_group_visibility_container_top">
	    		<?php esc_html_e( 'User Visibility (B2BKing)', 'b2bking' ); ?>
	    	</div>
	    	<div class="b2bking_group_visibility_container_content">
	    		<div class="b2bking_group_visibility_container_content_title">
					<svg class="b2bking_user_visibility_container_content_title_icon" xmlns="http://www.w3.org/2000/svg" width="31" height="23" fill="none" viewBox="0 0 31 23">
					  <path fill="#C4C4C4" d="M9.333 11.58c3.076 0 5.396-2.32 5.396-5.396C14.73 3.11 12.41.79 9.333.79c-3.075 0-5.396 2.32-5.396 5.395 0 3.076 2.32 5.396 5.396 5.396zm1.542 1.462H7.792c-4.25 0-7.709 3.458-7.709 7.708v1.542h18.5V20.75c0-4.25-3.458-7.708-7.708-7.708zm17.412-7.258l-6.63 6.616-1.991-1.992-2.18 2.18 4.171 4.17 8.806-8.791-2.176-2.183z"/>
					</svg>
					<?php esc_html_e( 'Users who can see this category (PREMIUM)', 'b2bking' ); ?>
	    		</div>
	    		<textarea disabled name="b2bking_category_users_textarea" id="b2bking_category_users_textarea"></textarea>
            
	    	</div>
	    </div>
	    
	    <?php
	}

	// Enable visibility settings in Edit Category
	function b2bking_enable_visibility_settings_edit_category($term) {
	
	    //getting term ID
	    $term_id = $term->term_id;
	    ?>
        <tr class="form-field">
            <th scope="row" valign="top">
            	<label><?php esc_html_e( 'Group Visibility (B2BKing)', 'b2bking' ); ?></label>
            </th>
            <td>
    		    <div class="b2bking_group_visibility_container">
    		    	<div class="b2bking_group_visibility_container_top">
    		    		<?php esc_html_e( 'Group Visibility (B2BKing)', 'b2bking' ); ?>
    		    	</div>
    		    	<div class="b2bking_group_visibility_container_content">
    		    		<div class="b2bking_group_visibility_container_content_title">
    		    			<svg class="b2bking_group_visibility_container_content_title_icon" xmlns="http://www.w3.org/2000/svg" width="31" height="29" fill="none" viewBox="0 0 31 29">
    						<path fill="#C4C4C4" d="M15.5 6.792V.625H.083v27.75h30.834V6.792H15.5zm-9.25 18.5H3.167v-3.084H6.25v3.084zm0-6.167H3.167v-3.083H6.25v3.083zm0-6.167H3.167V9.875H6.25v3.083zm0-6.166H3.167V3.708H6.25v3.084zm6.167 18.5H9.333v-3.084h3.084v3.084zm0-6.167H9.333v-3.083h3.084v3.083zm0-6.167H9.333V9.875h3.084v3.083zm0-6.166H9.333V3.708h3.084v3.084zm15.416 18.5H15.5v-3.084h3.083v-3.083H15.5v-3.083h3.083v-3.084H15.5V9.875h12.333v15.417zM24.75 12.958h-3.083v3.084h3.083v-3.084zm0 6.167h-3.083v3.083h3.083v-3.083z"></path>
    						</svg>
    						<?php esc_html_e( 'Groups who can see this category (PREMIUM)', 'b2bking' ); ?>
    		    		</div>
    		    		<!-- Add B2C and Guest Users group -->
    		    		<hr>
    		    		<div class="b2bking_user_registration_user_data_container_element_label"><?php esc_html_e('B2C Groups', 'b2bking'); ?></div>
    		    		<div class="b2bking_group_visibility_container_content_checkbox">
    		    			<div class="b2bking_group_visibility_container_content_checkbox_name">
    		    				<?php esc_html_e('Guest Users (Logged Out)', 'b2bking'); ?>
    		    			</div>
    		    			<input type="hidden" name="b2bking_group_0" value="0">
    		    			<?php $metaval = esc_html(get_term_meta($term_id, 'b2bking_group_0', true)); ?>
    		    			<input disabled type="checkbox" value="1" class="b2bking_group_visibility_container_content_checkbox_input" name="b2bking_group_0" id="b2bking_group_0" value="1" />
    		    		</div>
    		    		<div class="b2bking_group_visibility_container_content_checkbox">
    		    			<div class="b2bking_group_visibility_container_content_checkbox_name">
    		    				<?php esc_html_e('B2C Users', 'b2bking'); ?>
    		    			</div>
    		    			<input type="hidden" name="b2bking_group_b2c" value="0">
    		    			<?php $metaval = esc_html(get_term_meta($term_id, 'b2bking_group_b2c', true)); ?>
    		    			<input disabled type="checkbox" value="1" class="b2bking_group_visibility_container_content_checkbox_input" name="b2bking_group_b2c" id="b2bking_group_b2c" value="1"  />
    		    		</div>
    		    		<br>
    		    		<div class="b2bking_user_registration_user_data_container_element_label"><?php esc_html_e('B2B Groups', 'b2bking'); ?></div>
    	            	<?php
    		            	$groups = get_posts([
    		            	  'post_type' => 'b2bking_group',
    		            	  'post_status' => 'publish',
    		            	  'numberposts' => -1
    		            	]);
    		            	foreach ($groups as $group){
    		            		// retrieve the existing value(s) for this meta field.
    		            		$metaval = esc_html(get_term_meta($term_id, 'b2bking_group_'.$group->ID, true));

    		            		?>
    		            		<div class="b2bking_group_visibility_container_content_checkbox">
    		            			<div class="b2bking_group_visibility_container_content_checkbox_name">
    		            				<?php echo esc_html($group->post_title); ?>
    		            			</div>
    		            			<input type="hidden" name="b2bking_group_<?php echo esc_attr($group->ID);?>" value="0">
    		            			<input disabled type="checkbox" value="1" class="b2bking_group_visibility_container_content_checkbox_input" name="b2bking_group_<?php echo esc_attr($group->ID);?>" id="b2bking_group_<?php echo esc_attr($group->ID);?>" value="1"  />
    		            		</div>
    		            		<?php
    		            	}
    		            ?>
    		    	</div>
    		    </div>

	        </td>
	    </tr>

	    <tr class="form-field">
            <th scope="row" valign="top">
            	<label><?php esc_html_e( 'User Visibility (B2BKing Premium)', 'b2bking' ); ?></label>
            </th>

            <td>
	            <div class="b2bking_group_visibility_container">
			    	<div class="b2bking_group_visibility_container_top">
			    		<?php esc_html_e( 'User Visibility (B2BKing)', 'b2bking' ); ?>
			    	</div>
			    	<div class="b2bking_group_visibility_container_content">
			    		<div class="b2bking_group_visibility_container_content_title">
							<svg class="b2bking_user_visibility_container_content_title_icon" xmlns="http://www.w3.org/2000/svg" width="31" height="23" fill="none" viewBox="0 0 31 23">
							  <path fill="#C4C4C4" d="M9.333 11.58c3.076 0 5.396-2.32 5.396-5.396C14.73 3.11 12.41.79 9.333.79c-3.075 0-5.396 2.32-5.396 5.395 0 3.076 2.32 5.396 5.396 5.396zm1.542 1.462H7.792c-4.25 0-7.709 3.458-7.709 7.708v1.542h18.5V20.75c0-4.25-3.458-7.708-7.708-7.708zm17.412-7.258l-6.63 6.616-1.991-1.992-2.18 2.18 4.171 4.17 8.806-8.791-2.176-2.183z"/>
							</svg>
							<?php esc_html_e( 'Users who can see this category (PREMIUM)', 'b2bking' ); ?>
			    		</div>
			    		<textarea disabled name="b2bking_category_users_textarea" id="b2bking_category_users_textarea"><?php echo esc_html(get_term_meta($term_id, 'b2bking_category_users_textarea', true)); ?></textarea>


			    	</div>
			    </div>
       
	        </td>
	    </tr>
      	<?php
    }

	// Save category visibility meta settings
	function b2bking_save_category_visibility_meta_settings ($term_id) {

		// Save b2c group visibility
		$meta_input = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_group_b2c'));
		if($meta_input !== NULL){
			update_term_meta($term_id, 'b2bking_group_b2c', sanitize_text_field($meta_input));
		}

		// Save guest group visibility
		$meta_input = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_group_0'));
		if($meta_input !== NULL){
			update_term_meta($term_id, 'b2bking_group_0', sanitize_text_field($meta_input));
		}

		// Save groups visibility
		$groups = get_posts([
		  'post_type' => 'b2bking_group',
		  'post_status' => 'publish',
		  'numberposts' => -1
		]);
		foreach ($groups as $group){
			$meta_input = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_group_'.$group->ID));
			if($meta_input !== NULL){
				update_term_meta($term_id, 'b2bking_group_'.$group->ID, sanitize_text_field($meta_input));
			}
		}

		// Save users visibility
		$meta_users_visibility = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_category_users_textarea'));
		if ($meta_users_visibility !== NULL){
			update_term_meta($term_id, 'b2bking_category_users_textarea', sanitize_text_field($meta_users_visibility));
		}

		// clear cache and transients
		// clear cache when saving products

		// set that rules have changed so that pricing cache can be updated
		update_option('b2bking_dynamic_rules_have_changed', 'yes');

		// delete all b2bking transients
		global $wpdb;
		$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%transient_b2bking%'" );
		foreach( $plugin_options as $option ) {
		    delete_option( $option->option_name );
		}
		
	}
	/*
	* Functions dealing with custom category meta data END
	*/
		
	/*
	* Functions dealing with custom product meta data START
	*/
	// Add Product Visibility Metabox
	function b2bking_product_visibility_metabox($post_type) {
	    $post_types = array('product');     //limit meta box to certain post types
       	if ( in_array( $post_type, $post_types ) ) {
	           add_meta_box(
	               'b2bking_product_visibility_metabox'
	               ,esc_html__( 'Product Visibility (B2BKing)', 'b2bking' )
	               ,array( $this, 'b2bking_product_visibility_metabox_content' )
	               ,$post_type
	               ,'advanced'
	               ,'high'
	           );
	           if(get_current_screen()->action !== 'add'){
		           add_meta_box(
		               'b2bking_product_dynamic_rules_metabox'
		               ,esc_html__( 'Dynamic Rules (B2BKing)', 'b2bking' )
		               ,array( $this, 'b2bking_product_dynamic_rules_metabox_content' )
		               ,$post_type
		               ,'advanced'
		               ,'high'
		           );
		       }
	       }
	}
	// Product Dynamic Rules Metabox Content
	function b2bking_product_dynamic_rules_metabox_content(){
		global $post;

		// Get all Dynamic Rules applicable to the product
		$product_rules = get_posts([
	    		'post_type' => 'b2bking_rule',
	    	  	'post_status' => 'publish',
	    	  	'numberposts' => -1,
	    	  	'meta_query'=> array(
	                'relation' => 'AND',
	                array(
	                        'key' => 'b2bking_rule_applies',
	                        'value' => 'product_'.$post->ID
	                    )
	            )
	    	]);

	    if (empty($product_rules)){
			esc_html_e('There are no dynamic rules applicable to this product','b2bking');
			echo '<br /><br />';
		} else {
			?>

		    <table class="wp-list-table widefat fixed striped posts">
		    	<thead>
			    	<tr>
			    		<th scope="col" id="title" class="manage-column column-title column-primary">
			    			<span><?php esc_html_e('Name','b2bking'); ?></span>
			    		</th>
			    		<th scope="col" id="b2bking_what" class="manage-column column-b2bking_what"><?php esc_html_e('Type','b2bking'); ?></th>
			    		<th scope="col" id="b2bking_howmuch" class="manage-column column-b2bking_howmuch"><?php esc_html_e('How much','b2bking'); ?></th>
			    		<th scope="col" id="b2bking_conditions" class="manage-column column-b2bking_conditions"><?php esc_html_e('Conditions apply','b2bking'); ?></th>
			    		<th scope="col" id="b2bking_applies" class="manage-column column-b2bking_who"><?php esc_html_e('Who','b2bking'); ?></th>
			    	</tr>
		    	</thead>
		    	<tbody id="the-list">
		    		<?php

		    			foreach ($product_rules as $rule){
		    				$rule_type = get_post_meta($rule->ID, 'b2bking_rule_what', true);
		    				$rule_name = $rule_type;
		    				$howmuch = get_post_meta($rule->ID, 'b2bking_rule_howmuch', true);
		    				$who = get_post_meta($rule->ID, 'b2bking_rule_who', true);
		    				$who = explode ('_',$who);
		    				switch ($who[0]){
				            	case 'everyone':
				            	
				            	if (isset($who[2])){
					            	if ($who[2] === 'b2b'){
					            		$who = esc_html__('All registered B2B users','b2bking');
					            	} else if ($who[2] === 'b2c'){
					            		$who = esc_html__('All registered B2C users','b2bking');
					            	}
				            	} else {
				            		$who = esc_html__('All registered users','b2bking');
				            	}
				            	break;

				            	case 'user':
				            	
				            	if (intval($who[1]) !== 0){
				            		// if registered user, get user login
				            		$user = get_user_by('id', $who[1]);
				            		$who = $user->user_login;
				            	} else {
				            		// if guest user
				            		$who = esc_html__('All guest users','b2bking');
				            	}
				            	break;

				            	case 'group':
				            	$group = get_the_title($who[1]);
				            	$who = $group;
				            	break;

				            	case 'multiple':
				            	$who = esc_html__('Multiple Options', 'b2bking');
				            	break;
		    				}
		    				$conditions = get_post_meta($rule->ID, 'b2bking_rule_conditions', true);
		    				if (empty($conditions)) {
		    					$conditions = 'no';
		    				} else {
		    					$conditions = 'yes';
		    				}
		    				$quantity_value = get_post_meta($rule->ID, 'b2bking_rule_quantity_value', true);
		    				$currency_symbol = get_woocommerce_currency_symbol();
		    				     	if (!empty($howmuch) && $rule_type !== 'free_shipping' && $rule_type !== 'hidden_price' && $rule_type !== 'tax_exemption' && $rule_type !== 'tax_exemption_user') {
		    				     		if ($rule_type === 'discount_percentage' || $rule_type === 'add_tax_percentage'){
		    				     			$howmuch = $howmuch.'%';
		    				     		} else if ($rule_type === 'discount_amount' || $rule_type === 'fixed_price' || $rule_type === 'add_tax_amount'){
		    				     			$howmuch = $currency_symbol.$howmuch;
		    				     		} else if ($rule_type === 'minimum_order' || $rule_type === 'maximum_order'){
		    				     			if ($quantity_value === 'value'){
	    					     				$howmuch = $currency_symbol.$howmuch;
	    					     			} else if ($quantity_value === 'quantity'){
	    					     				$howmuch = $howmuch.' '.esc_html__('pieces','b2bking');
	    					     			}
	    					     		} else if ($rule_type === 'required_multiple'){
	    					     			$howmuch = $howmuch.' '.esc_html__('pieces','b2bking');
	    					     		} else {
	    					     			echo esc_html($howmuch);
	    					     		}
		    				     	} else {
		    				     		echo '-';
		    				     	}
		    				switch ( $rule_name ){
		    					case 'discount_amount':
		    					$rule_name = esc_html__('Discount Amount','b2bking');
		    					break;

		    					case 'discount_percentage':
		    					$rule_name = esc_html__('Discount Percentage','b2bking');
		    					break;

		    					case 'fixed_price':
		    					$rule_name = esc_html__('Fixed Price','b2bking');
		    					break;

		    					case 'hidden_price':
		    					$rule_name = esc_html__('Hidden Price','b2bking');
		    					break;

		    					case 'free_shipping':
		    					$rule_name = esc_html__('Free Shipping','b2bking');
		    					break;

		    					case 'minimum_order':
		    					$rule_name = esc_html__('Minimum Order','b2bking');
		    					break;

		    					case 'maximum_order':
		    					$rule_name = esc_html__('Maximum Order','b2bking');
		    					break;

		    					case 'required_multiple':
		    					$rule_name = esc_html__('Required Multiple','b2bking');
		    					break;

		    					case 'tax_exemption_user':
		    					$what = esc_html__('Tax Exemption','b2bking');
		    					break;

		    					case 'tax_exemption':
		    					$rule_name = esc_html__('Zero Tax Product','b2bking');
		    					break;

		    					case 'add_tax_percentage':
		    					$rule_name = esc_html__('Add Tax / Fee (Percentage)','b2bking');
		    					break;

		    					case 'add_tax_amount':
		    					$rule_name = esc_html__('Add Tax / Fee (Amount)','b2bking');
		    					break;
		    				}
		    				?>
				    	    <tr>
				    	    	<td class="title column-title has-row-actions column-primary page-title">
				    	    	    <strong>
				    	    	    	<a class="row-title" href="<?php echo admin_url('/post.php?post='.$rule->ID.'&action=edit');?>">
				    	    	    	<?php 
				    	    	    		if (!empty($rule->post_title)){
				    	    	    			echo esc_html($rule->post_title);
				    	    	    		} else {
				    	    	    			esc_html_e('(no title)','b2bking');
				    	    	    		} 
				    	    	    	?>
				    	    			</a>
				    	    	</strong>
			    	    	   </td>

			    	    	   <td class="b2bking_what column-b2bking_what">
			    	    	   		<span class="b2bking_dynamic_rule_column_text_<?php echo esc_attr($rule_type);?>"><?php echo esc_html($rule_name); ?></span>
			    	    	   </td>
			    	    	   <td class="b2bking_howmuch column-b2bking_howmuch"><?php echo esc_html($howmuch); ?></td>
			    	    	   <td class="b2bking_conditions column-b2bking_conditions"><?php echo esc_html($conditions); ?></td>
			    	    	   <td class="b2bking_applies column-b2bking_who">
			    	    	   <strong><?php echo esc_html($who); ?></strong>
			    	    	   </td>		
			    	    	</tr>

		    				<?php
		    			}

		    		?>

		    	</tbody>
		    </table><br />

			<?php
		}
		echo '
		    <a href="'.admin_url('/edit.php?post_type=b2bking_rule').'" class="page-title-action">'.esc_html__('Manage Dynamic Rules','b2bking').'</a>';

		echo '
		    <a href="'.admin_url('/post-new.php?post_type=b2bking_rule').'" class="page-title-action">'.esc_html__('Add Rule','b2bking').'</a>';

	}
	// Product Visibility Metabox Content
	function b2bking_product_visibility_metabox_content(){
		?>
		<div class="b2bking_upgrade_premium_settings_second">
				<i class="mdi mdi-rocket"></i>
				<?php
				esc_html_e('Get B2BKing Premium to Unlock Product Visibility','b2bking');
				echo '<br />';
				?>
				<button type="button" class="b2bking_upgrade_modal_trigger b2bking_upgrade_button_global"><?php esc_html_e('Upgrade Now!','b2bking');?></button>
			</div>
       </div>
       <?php
	}

	// Update product visibility meta data
	function b2bking_product_visibility_meta_update($post_id){
		if (isset($_POST['_inline_edit'])){
			if (wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')){
			    return;
			}
		}
		if (get_post_type($post_id) === 'product'){
			// Save product visibility override (default vs manual)
			$meta_product_visibility_override = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_product_visibility_override'));
			if ($meta_product_visibility_override !== NULL){
				update_post_meta( $post_id, 'b2bking_product_visibility_override', sanitize_text_field($meta_product_visibility_override));
			}

			// Save B2C group
			$meta_input = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_group_b2c'));
			if($meta_input !== NULL){
				update_post_meta($post_id, 'b2bking_group_b2c', sanitize_text_field($meta_input));
			}

			// Save Guest group
			$meta_input = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_group_0'));
			if($meta_input !== NULL){
				update_post_meta($post_id, 'b2bking_group_0', sanitize_text_field($meta_input));
			}

			// Get all groups
			$groups = get_posts([
			  'post_type' => 'b2bking_group',
			  'post_status' => 'publish',
			  'numberposts' => -1
			]);

			// For each group option, save user's choice as post meta
			foreach ($groups as $group){
				$meta_input = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_group_'.$group->ID));
				if($meta_input !== NULL){
					update_post_meta($post_id, 'b2bking_group_'.$group->ID, sanitize_text_field($meta_input));
				}
			}

			// Save user visibility
			$meta_user_visibility = sanitize_text_field(filter_input(INPUT_POST, 'b2bking_category_users_textarea'));
			if ($meta_user_visibility !== NULL){
				// get current users list
				$currentuserstextarea = esc_html(get_post_meta($post_id, 'b2bking_category_users_textarea', true));
				$currentusersarray = explode(',', $currentuserstextarea);
				// delete all individual user meta
				foreach ($currentusersarray as $user){
					delete_post_meta( $post_id, 'b2bking_user_'.trim($user));
				}
				// get new users list
				$newusertextarea = $meta_user_visibility;
				$newusersarray = explode(',', $newusertextarea);
				// set new user meta
				foreach ($newusersarray as $newuser){
					update_post_meta( $post_id, 'b2bking_user_'.sanitize_text_field(trim($newuser)), 1);
				}
				// Update users textarea
				update_post_meta($post_id, 'b2bking_category_users_textarea', sanitize_text_field($meta_user_visibility));
			}
		}
	}
	/*
	* Functions dealing with custom product meta data END
	*/

	function b2bking_individual_product_pricing_data_save( $post_id ){
		$groups = get_posts( array( 'post_type' => 'b2bking_group','post_status'=>'publish','numberposts' => -1) );
		$somethingchanged = 'no';
		foreach ($groups as $group){
	        $number_field = sanitize_text_field($_POST['b2bking_regular_product_price_group_'.$group->ID]);
	        if (isset($_POST['b2bking_regular_product_price_group_'.$group->ID])) {
	            update_post_meta($post_id, 'b2bking_regular_product_price_group_'.$group->ID, esc_attr($number_field));
	            $somethingchanged = 'yes';
	        }
	        $number_field = sanitize_text_field($_POST['b2bking_sale_product_price_group_'.$group->ID]);
	        if (isset($_POST['b2bking_sale_product_price_group_'.$group->ID])) {
	            update_post_meta($post_id, 'b2bking_sale_product_price_group_'.$group->ID, esc_attr($number_field));
	            $somethingchanged = 'yes';
	        }

	        
	    }


	    if ($somethingchanged === 'yes'){
	    	// set that rules have changed so that pricing cache can be updated
	    	update_option('b2bking_dynamic_rules_have_changed', 'yes');

	    	// delete all b2bking transients
	    	global $wpdb;
	    	$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%transient_b2bking%'" );
	    	foreach( $plugin_options as $option ) {
	    	    delete_option( $option->option_name );
	    	}
	    }
	}

	function save_variation_settings_fields( $post_id ){

		$somethingchanged = 'no';
		$groups = get_posts( array( 'post_type' => 'b2bking_group','post_status'=>'publish','numberposts' => -1) );
		foreach ($groups as $group){
	        $number_field = sanitize_text_field($_POST['b2bking_regular_product_price_group_'.$group->ID.'_'.$post_id]);
	        if (isset($_POST['b2bking_regular_product_price_group_'.$group->ID.'_'.$post_id])) {
	            update_post_meta($post_id, 'b2bking_regular_product_price_group_'.$group->ID, esc_attr($number_field));
	        	$somethingchanged = 'yes';
	        }
	        $number_field = sanitize_text_field($_POST['b2bking_sale_product_price_group_'.$group->ID.'_'.$post_id]);
	        if (isset($_POST['b2bking_sale_product_price_group_'.$group->ID.'_'.$post_id])) {
	            update_post_meta($post_id, 'b2bking_sale_product_price_group_'.$group->ID, esc_attr($number_field));
	        	$somethingchanged = 'yes';
	        }
	    }

	  
	    if ($somethingchanged === 'yes'){
	    	// set that rules have changed so that pricing cache can be updated
	    	update_option('b2bking_dynamic_rules_have_changed', 'yes');

	    	// delete all b2bking transients
	    	global $wpdb;
	    	$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%transient_b2bking%'" );
	    	foreach( $plugin_options as $option ) {
	    	    delete_option( $option->option_name );
	    	}
	    }
	}

	function b2bking_additional_panel_in_product_page($tabs){
		$tabs['b2bking'] = array(
           'label'         => 'B2BKing', 
           'target'        => 'b2bking_product', 
           'class'         => array( 'b2bking_tab', 'show_if_simple', 'show_if_variable' ), 
           'priority'      => 15,
       );
       return $tabs;
	}
	function b2bking_additional_panel_in_product_page_content( $show_pointer_info ){
		?>
		<div id='b2bking_product' class='panel woocommerce_options_panel'>
			<div class="options_group">
				<div class="b2bking_upgrade_premium_settings">
					<i class="mdi mdi-rocket"></i>
					<?php
					esc_html_e('Upgrade to B2BKing Premium to Unlock','b2bking');
					echo '<br />';
					esc_html_e('Tiered Pricing + Min / Max / Step + Info Table','b2bking');
					?>
					<br>
					<button type="button" class="b2bking_upgrade_modal_trigger b2bking_upgrade_button_global"><?php esc_html_e('Upgrade Now!','b2bking');?></button>

				</div>
	       </div>
	   </div>
	   <?php
	}

	function b2bking_additional_panel_product_save($post_id){

		// save custom info table data for all groups
		$groups = get_posts( array( 'post_type' => 'b2bking_group','post_status'=>'publish','numberposts' => -1) );
		foreach ($groups as $group){
			$customrowsstring = '';
			if (isset($_POST['b2bking_group_'.$group->ID.'_customrows_label'])){
				$customrows_label = array_map( 'sanitize_text_field', $_POST['b2bking_group_'.$group->ID.'_customrows_label'] );
			} else {
				$customrows_label = 'notarray';
			}	

			if (isset($_POST['b2bking_group_'.$group->ID.'_customrows_text'])){
				$customrows_text = array_map( 'sanitize_text_field', $_POST['b2bking_group_'.$group->ID.'_customrows_text'] );
			} else {
				$customrows_text = 'notarray';
			}
			if (is_array($customrows_label) && is_array($customrows_text)){
				foreach ($customrows_label as $index=>$tier){
					if (!empty($customrows_label[$index]) && !empty($customrows_text[$index])){
						$customrowsstring.=$customrows_label[$index].':'.$customrows_text[$index].';';
					}
				}
			}
			update_post_meta($post_id, 'b2bking_product_customrows_group_'.$group->ID, $customrowsstring);
		}

		// Save data for B2C as well
		$customrowsstring = '';
		if (isset($_POST['b2bking_group_b2c_customrows_label'])){
			$customrows_label = array_map( 'sanitize_text_field', $_POST['b2bking_group_b2c_customrows_label'] );
		} else {
			$customrows_label = 'notarray';
		}	

		if (isset($_POST['b2bking_group_b2c_customrows_text'])){
			$customrows_text = array_map( 'sanitize_text_field', $_POST['b2bking_group_b2c_customrows_text'] );
		} else {
			$customrows_text = 'notarray';
		}

		if (is_array($customrows_label) && is_array($customrows_text)){
			foreach ($customrows_label as $index=>$tier){
				if (!empty($customrows_label[$index]) && !empty($customrows_text[$index])){
					$customrowsstring.=$customrows_label[$index].':'.$customrows_text[$index].';';
				}
			}
		}
		update_post_meta($post_id, 'b2bking_product_customrows_group_b2c', $customrowsstring);

	}

	function additional_product_pricing_option_fields() {

	    global $post;

	    // Show Tiered Prices for B2C
	    ?>
    	<p class="form-field b2bking_tiered_pricing">
    		<label for="b2bking_tiered_pricing"><?php echo esc_html__( 'Price Tiers', 'b2bking' ).' ('.get_woocommerce_currency_symbol().')'; ?></label>
			<span class="wrap b2bking_product_wrap b2bking_upgrade_modal_trigger">
				<?php esc_html_e('Upgrade to B2BKing Premium to Unlock Price Tiers','b2bking'); ?>
	    	</span>
    	</p>
    	<?php

	    // End Show Tiered Prices for B2C

	   	echo '</div><div class="options_group pricing show_if_simple show_if_external show_if_composite">';
	    echo '<br>';

	    $groups = get_posts( array( 'post_type' => 'b2bking_group','post_status'=>'publish','numberposts' => -1) );
	    foreach ($groups as $group){
	    	woocommerce_wp_text_input(
	    	    array(
	    	      'id' => 'b2bking_regular_product_price_group_'.esc_attr($group->ID),
	    	      'label' => esc_html($group->post_title).' '.esc_html__('Regular Price', 'b2bking'),
	    	      'placeholder' => '',
	    	      'description' => esc_html__( 'Enter the regular price for this B2BKing group here.', 'woocommerce' ),
	    	      'type' => 'number',
	    	      'custom_attributes' => array(
	    	         'step' => 'any',
	    	         'min' => '0.0001'
	    	      )
	    	    )
	    	  );
	    	woocommerce_wp_text_input(
	    	    array(
	    	      'id' => 'b2bking_sale_product_price_group_'.esc_attr($group->ID),
	    	      'label' => esc_html($group->post_title).' '.esc_html__('Sale Price', 'b2bking'),
	    	      'placeholder' => '',
	    	      'description' => esc_html__( 'Enter the sale price for this B2BKing group here.', 'woocommerce' ),
	    	      'type' => 'number',
	    	      'custom_attributes' => array(
	    	         'step' => 'any',
	    	         'min' => '0.0001'
	    	      )
	    	    )
	    	);

	    	// add fields for Tiered Pricing
	    	?>
	    	<p class="form-field b2bking_tiered_pricing">
	    		<label for="b2bking_tiered_pricing"><?php echo esc_html($group->post_title).esc_html__( ' Price Tiers', 'b2bking' ); ?></label>
	    		
    			<span class="wrap b2bking_product_wrap b2bking_upgrade_modal_trigger">
					<?php esc_html_e('Upgrade to B2BKing Premium to Unlock Price Tiers','b2bking'); ?>
		    	</span>
	    	</p>
	    	<?php
	    }
	}

	function additional_variation_pricing_option_fields( $loop, $variation_data, $variation ) {

	    global $post;

	    // Show Tiered Prices for B2C
	    ?>
	    <p class="form-field form-row b2bking_tiered_pricing_variation">
			<span class="wrap b2bking_product_wrap_variation b2bking_upgrade_modal_trigger">
				<?php esc_html_e('Upgrade to B2BKing Premium to Unlock Price Tiers','b2bking'); ?>
	    	</span>
		</p>
    	<?php

	    // End Show Tiered Prices for B2C

	    echo '<br>';

	    $groups = get_posts( array( 'post_type' => 'b2bking_group','post_status'=>'publish','numberposts' => -1) );
	    foreach ($groups as $group){
	    	woocommerce_wp_text_input(
	    	    array(
	    	      'id' => 'b2bking_regular_product_price_group_'.esc_attr($group->ID).'_'.esc_attr($variation->ID),
	    	      'value' => get_post_meta($variation->ID,'b2bking_regular_product_price_group_'.$group->ID, true),
	    	      'wrapper_class' => 'form-row form-row-first',
	    	      'label' => esc_html($group->post_title).' '.esc_html__('Regular price', 'b2bking').' ('.get_woocommerce_currency_symbol().')',
	    	      'placeholder' => '',
	    	      'description' => esc_html__( 'Enter the regular price for this B2BKing group here.', 'woocommerce' ),
	    	      'type' => 'number',
	    	      'desc_tip'      => true,
	    	      'custom_attributes' => array(
	    	         'step' => 'any',
	    	         'min' => '0.0001'
	    	      )
	    	    )
	    	);
	    	woocommerce_wp_text_input(
	    	    array(
	    	      'id' => 'b2bking_sale_product_price_group_'.esc_attr($group->ID).'_'.esc_attr($variation->ID),
	    	      'value' => get_post_meta($variation->ID,'b2bking_sale_product_price_group_'.$group->ID, true),
	    	      'wrapper_class' => 'form-row form-row-last',
	    	      'label' => esc_html($group->post_title).' '.esc_html__('Sale price', 'b2bking').' ('.get_woocommerce_currency_symbol().')',
	    	      'placeholder' => '',
	    	      'description' => esc_html__( 'Enter the sale price for this B2BKing group here.', 'woocommerce' ),
	    	      'type' => 'number',
	    	      'desc_tip'      => true,
	    	      'custom_attributes' => array(
	    	         'step' => 'any',
	    	         'min' => '0.0001'
	    	      )
	    	    )
	    	  );
	    		?>
				<p class="form-field form-row b2bking_tiered_pricing_variation">
    				<span class="wrap b2bking_product_wrap_variation b2bking_upgrade_modal_trigger">
    					<?php esc_html_e('Upgrade to B2BKing Premium to Unlock Price Tiers','b2bking'); ?>
    		    	</span>
	    		</p>
 	    	
    	    	<?php
	    }
	}

	
	function b2bking_settings_page() {
		// Admin Menu Settings 
		$page_title = 'B2BKing Core';
		$menu_title = 'B2BKing';
		$capability = 'manage_woocommerce';
		$slug = 'b2bkingcore';
		$callback = array( $this, 'b2bking_settings_page_content' );

		$iconurl = plugins_url('../includes/assets/images/b2bking-icon.svg', __FILE__);
		$position = 57;
		add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $iconurl, $position );

		// Add "Dashboard" submenu page
		add_submenu_page(
	        'b2bkingcore',
	        'Dashboard', //page title
	        'Dashboard', //menu title
	        'manage_woocommerce', //capability,
	        'b2bking_dashboard',//menu slug
	        array( $this, 'b2bking_dashboard_page_content' ), //callback function
	    	0
	    );

		// Add "Settings" submenu page
		add_submenu_page(
	        'b2bkingcore',
	        'Settings', //page title
	        'Settings', //menu title
	        'manage_woocommerce', //capability,
	        'b2bkingcore',//menu slug
	        '', //callback function
	    	1	
	    );

	    // Add "Customers" submenu page
		add_submenu_page(
	        'b2bkingcore',
	        'Customers', //page title
	        'Customers', //menu title
	        'manage_woocommerce', //capability,
	        'b2bking_customers',//menu slug
	        array( $this, 'b2bking_customers_page_content' ), //callback function
	    	2
	    );

	    // Add "Groups" submenu page
		add_submenu_page(
	        'b2bkingcore',
	        'Groups', //page title
	        'Groups', //menu title
	        'manage_woocommerce', //capability,
	        'b2bking_groups', //menu slug
	        array( $this, 'b2bking_groups_page_content' ), //callback function
	    	2
	    );

        // Add "Reports" submenu page
    	add_submenu_page(
            'b2bkingcore',
            esc_html__('Reports','b2bking'), //page title
            esc_html__('Reports','b2bking').'<span class="b2bking-menu-new">&nbsp;NEW!</span>', //menu title
            apply_filters('b2bking_backend_capability_needed', 'manage_woocommerce'), //capability,
            'b2bking_reports',//menu slug
            array( $this, 'b2bking_reports_page_content' ), //callback function
        	4
        );


	    // Add "B2C Users Group" submenu page
		add_submenu_page(
	        '',
	        'B2C Users', //page title
	        'B2C Users', //menu title
	        'manage_woocommerce', //capability,
	        'b2bking_b2c_users', //menu slug
	        array( $this, 'b2bking_b2c_users_page_content' ), //callback function
	    	1
	    );

	    // Add "B2C Users Group" submenu page
		add_submenu_page(
	        '',
	        'Logged Out Users', //page title
	        'Logged Out Users', //menu title
	        'manage_woocommerce', //capability,
	        'b2bking_logged_out_users', //menu slug
	        array( $this, 'b2bking_logged_out_users_page_content' ), //callback function
	    	1
	    );

		
		add_submenu_page(
	        'b2bkingcore',
	        'Order Form', //page title
	        'Order Form', //menu title
	        'manage_woocommerce', //capability,
	        'b2bking_orderform',//menu slug
	        array( $this, 'b2bking_orderform_page_content' ), //callback function
	    	7
	    );


    	add_submenu_page(
            'b2bkingcore',
            'Tiered Pricing', //page title
            'Tiered Pricing', //menu title
            'manage_woocommerce', //capability,
            'b2bking_tieredpricing',//menu slug
            array( $this, 'b2bking_tieredpricing_page_content' ), //callback function
        	8
        );

        	

    	add_submenu_page(
            'b2bkingcore',
            'Custom Fields', //page title
            'Custom Fields', //menu title
            'manage_woocommerce', //capability,
            'b2bking_businessregistration',//menu slug
            array( $this, 'b2bking_businessregistration_page_content' ), //callback function
        	9
        );

    	
    	add_submenu_page(
            'b2bkingcore',
            'PRO Features', //page title
            'PRO Features', //menu title
            'manage_woocommerce', //capability,
            'b2bking_profeatures',//menu slug
            array( $this, 'b2bking_profeatures_page_content' ), //callback function
        	10
        );


		// Build plugin file path relative to plugins folder
		$absolutefilepath = dirname(plugins_url('', __FILE__),1);
		$pluginsurllength = strlen(plugins_url())+1;
		$relativepath = substr($absolutefilepath, $pluginsurllength);

		// Add the action links
		add_filter('plugin_action_links_'.$relativepath.'/b2bking.php', array($this, 'b2bking_action_links') );

		global $submenu;

		$submenu['b2bkingcore']['upgradeb2bking'] = array( '<b style="color:#fff">Upgrade Now</b><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 14 14" style="position: relative;left: 6px;top: 1px;">
		  <g clip-path="url(#a)">
		    <path fill="#fff" d="M9.634 4.593a.782.782 0 1 0 .001 1.564.782.782 0 0 0 0-1.564Z"/>
		    <path fill="#fff" d="M13.988 1.467v-.003a.598.598 0 0 0-.444-.448c-.873-.213-2.246.014-3.768.623-1.534.615-2.972 1.524-3.943 2.497-.311.31-.6.64-.866.99-.654-.028-1.23.065-1.716.277-1.691.744-2.174 2.65-2.303 3.431a.732.732 0 0 0 .797.85h.003l1.885-.206.007.067c.024.23.126.444.29.607l.92.92a.998.998 0 0 0 .606.29l.063.007-.205 1.882v.004a.732.732 0 0 0 .843.797c.786-.126 2.69-.603 3.433-2.304.21-.482.303-1.056.277-1.71.352-.264.684-.553.994-.865.98-.974 1.891-2.4 2.5-3.912.606-1.504.834-2.888.627-3.794ZM10.85 6.59a1.722 1.722 0 1 1 0-2.434 1.708 1.708 0 0 1 0 2.434Z"/>
		    <path fill="#fff" d="M4.744 11.322a.469.469 0 0 0-.322.078 34.72 34.72 0 0 1-.565.378c-.384.25-.844-.189-.616-.586l.356-.616a.469.469 0 0 0-.444-.73c-.395.05-.762.23-1.044.512-.107.108-.433.434-.608 1.678-.05.357-.082.716-.095 1.077a.469.469 0 0 0 .469.48h.012c.36-.012.72-.043 1.078-.093 1.244-.176 1.57-.502 1.678-.61.283-.281.462-.65.51-1.046a.469.469 0 0 0-.409-.522Z"/>
		  </g>
		  <defs>
		    <clipPath id="a">
		      <path fill="#fff" d="M0 0h15v15H0z"/>
		    </clipPath>
		  </defs>
		</svg>', 'manage_options' , 'https://woocommerce-b2b-plugin.com/pricing' ); 

		$submenu['b2bkingcore']['upgradeb2bking'][4] = 'b2bking-upgrade-to-premium';

		$submenu['b2bkingcore'][7][4] = 'b2bking-upgrade-menu';
		$submenu['b2bkingcore'][8][4] = 'b2bking-upgrade-menu';
		$submenu['b2bkingcore'][9][4] = 'b2bking-upgrade-menu';
		$submenu['b2bkingcore'][10][4] = 'b2bking-upgrade-menu';

	}
	
	function b2bking_action_links( $links ) {
		// Build and escape the URL.
		$url = esc_url( add_query_arg('page', 'b2bkingcore', get_admin_url() . 'admin.php') );

		// Create the link.
		$settings_link = '<a href='.esc_attr($url).'>' . esc_html__( 'Settings', 'b2bking' ) . '</a>';
		$docs_link = '<a href="https://woocommerce-b2b-plugin.com/docs/" target="_blank">' . esc_html__( 'Docs', 'b2bking' ) . '</a>';

		// Create the link.
		$upgrade_link = '<a href="https://woocommerce-b2b-plugin.com/pricing" style="color:#8D00B1" target="_blank">' . esc_html__( 'Get B2BKing Pro for 40% Off', 'b2bking' ) . '</a>';
		
		// Adds the link to the end of the array.
		array_unshift($links,	$upgrade_link );
		array_unshift($links,	$docs_link );
		array_unshift($links,	$settings_link );
		return $links;
	}

	
	function b2bking_settings_init(){
		require_once ( B2BKINGCORE_DIR . 'admin/class-b2bking-settings.php' );
		$settings = new B2bkingcore_Settings;
		$settings-> register_all_settings();

		// if a POST variable exists indicating the user saved settings, flush permalinks
		if (isset($_POST['b2bking_plugin_status_setting'])){
			require_once ( B2BKINGCORE_DIR . 'public/class-b2bking-public.php' );
			$publicobj = new B2bkingcore_Public;
			$this->b2bking_register_post_type_customer_groups();
			$this->b2bking_register_post_type_dynamic_rules();
			$this->b2bking_register_post_type_custom_role();

			flush_rewrite_rules();

		}
	}




	function b2bking_settings_page_content() {
		require_once ( B2BKINGCORE_DIR . 'admin/class-b2bking-settings.php' );
		$settings = new B2bkingcore_Settings;
		$settings-> render_settings_page_content();
	}

	function b2bking_offers_page_content(){
		?>
		<div class="b2bking_upgrade_premium">
			<i class="mdi mdi-rocket"></i>
			<?php
			esc_html_e('Upgrade to B2BKing Premium to Unlock','b2bking');
			echo '<br />';
			esc_html_e('Offers / Product Bundles','b2bking');
			?>
		</div>
		<?php
	}

	function b2bking_fields_page_content(){
		?>
		<div class="b2bking_upgrade_premium">
			<i class="mdi mdi-rocket"></i>
			<?php
			esc_html_e('Upgrade to B2BKing Premium to Unlock','b2bking');
			echo '<br />';
			esc_html_e('Custom Registration Fields','b2bking');
			?>
		</div>
		<?php
	}

	function b2bking_conversations_page_content(){
		?>
		<div class="b2bking_upgrade_premium">
			<i class="mdi mdi-rocket"></i>
			<?php
			esc_html_e('Upgrade to B2BKing Premium to Unlock','b2bking');
			echo '<br />';
			esc_html_e('Messaging & Conversations','b2bking');
			?>
		</div>
		<?php
	}

	function b2bking_tools_page_content(){
		?>
		<div class="b2bking_upgrade_premium">
			<i class="mdi mdi-rocket"></i>
			<?php
			esc_html_e('Upgrade to B2BKing Premium to Unlock','b2bking');
			echo '<br />';
			esc_html_e('Tools','b2bking');
			?>
		</div>
		<?php
	}

	public static function b2bking_display_modules_cards($type){
		// title, description, documentation link, image
		$integration_modules = array(
			array('title' => 'Wholesale & B2B - B2BKing Integration', 'description' => 'Adds wholesale prices, B2B, minimums, quote requests, etc. Plugin integration with B2BKing.', 'documentation_url'=> 'https://woocommerce-multivendor.com/docs/wholesale-b2b-b2bking-integration/', 'image' => plugins_url('../includes/assets/images/module-b2bking.png', __FILE__), 'slug' => 'b2bkingintegration', 'cardbottom' => 'Requires the B2BKing Pro plugin installed.'),
		);

		$pro_modules = array(
			array('title' => 'Private Store', 'description' => 'Hide prices, products, or the entire shop from logged out users.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/guest-access-restriction-hide-prices-hide-the-website-replace-prices-with-quote-request/', 'image' => plugins_url('../includes/assets/images/module-private2.png', __FILE__)),
			array('title' => 'Subaccounts', 'description' => 'Companies can create multiple buyer accounts for their employees and staff.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/multiple-buyers-per-account-subaccounts/', 'image' => plugins_url('../includes/assets/images/module-subaccounts.png', __FILE__)),
			array('title' => 'Conversations', 'description' => 'Enable inquiries, messaging and notifications to stay in contact with B2B customers.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/conversations/', 'image' => plugins_url('../includes/assets/images/module-conversations3.png', __FILE__)),
			array('title' => 'Invoice Gateway', 'description' => 'Allows customers to checkout without immediate payment, choosing an invoice option.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/invoice-payment-gateway/', 'image' => plugins_url('../includes/assets/images/module-invoice.png', __FILE__)),
			array('title' => 'Different Prices', 'description' => 'Configure different prices for each product, for each customer or customer group.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/woocommerce-wholesale-prices-the-definitive-guide/', 'image' => plugins_url('../includes/assets/images/module-prices2.png', __FILE__)),
			array('title' => 'Bulk Order Form', 'description' => 'Advanced order form with AJAX search, 3 stunning themes, filters and more.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/order-form-themes-styling/', 'image' => plugins_url('../includes/assets/images/module-orderform.png', __FILE__)),
			array('title' => 'Quick CSV Orders', 'description' => 'Customers can order in bulk by uploading a CSV file with SKUs and quantities.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/quick-orders-via-csv-upload/', 'image' => plugins_url('../includes/assets/images/module-csvorder2.png', __FILE__)),
			array('title' => 'Tiered Pricing', 'description' => 'Lower prices per unit as quantity ordered increases. Animated pricing table.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/conversations/', 'image' => plugins_url('../includes/assets/images/module-prices.png', __FILE__)),
			array('title' => 'Quote Requests', 'description' => 'Customers can request custom quotes and receive offers at special prices.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/registered-user-access-restriction-replace-prices-with-request-a-quote/', 'image' => plugins_url('../includes/assets/images/module-document.png', __FILE__)),
			array('title' => 'Custom Fields', 'description' => 'Configure custom registration and billing fields with 9+ field types.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/extended-registration-and-custom-fields/', 'image' => plugins_url('../includes/assets/images/module-fields.png', __FILE__)),
			array('title' => 'Separate B2B & B2C Stock', 'description' => 'Keep inventory quantities separate, hide stock for B2C, or always in stock for B2B.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/separate-stock-b2b-b2c-users-wholesale-woocommerce/', 'image' => plugins_url('../includes/assets/images/module-stock.png', __FILE__)),
			array('title' => 'PDF Offers', 'description' => 'Send offers / quotes as PDFs branded with your company logo + email offers.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/offers-2/', 'image' => plugins_url('../includes/assets/images/module-pdf.png', __FILE__)),
			array('title' => 'Min / Max / Step Quantities', 'description' => 'Configure required min, max, or multiple quantities for ordering products.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/quantity-rules-min-max-step-on-product-page/', 'image' => plugins_url('../includes/assets/images/module-minmax.png', __FILE__)),
			array('title' => 'Tax Exemptions', 'description' => 'Exempt B2B buyers from tax. Show B2B prices ex tax, B2C inc tax. Configure by country.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/how-to-display-prices-excluding-tax-for-b2b-users-and-including-tax-for-b2c-users/', 'image' => plugins_url('../includes/assets/images/module-tax.png', __FILE__)),
			array('title' => 'Content Restriction', 'description' => 'Show different content to different users, or restrict specific areas.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/shortcode-to-restrict-content-to-b2b-b2c-user-groups-specific-users/', 'image' => plugins_url('../includes/assets/images/module-content.png', __FILE__)),
			array('title' => 'BOGO Discounts', 'description' => 'Incentivize purchases with "buy one get one free" discounts.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/bogo-discounts-buy-x-get-one-free/', 'image' => plugins_url('../includes/assets/images/module-bogo.png', __FILE__)),
			array('title' => 'Show Both B2B & B2C Price', 'description' => 'Show both the wholesale and retail price to B2B buyers, or show RRP.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/how-to-display-rrp-recommended-retail-price-to-b2b-users/', 'image' => plugins_url('../includes/assets/images/module-b2bb2c3.png', __FILE__)),
			array('title' => 'Color Scheme Customizer', 'description' => 'Configure plugin frontend color scheme for main design elements and buttons.', 'documentation_url'=> '#', 'image' => plugins_url('../includes/assets/images/module-color.png', __FILE__)),
			array('title' => 'Product Visibility', 'description' => 'Control who can see specific products, by user or customer group, by product or product category.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/faq-product-visibility-is-not-working-how-to-set-up-product-visibility/', 'image' => plugins_url('../includes/assets/images/module-visibility3.png', __FILE__)),
			array('title' => 'Reports by User or Group', 'description' => 'Access admin reports for B2B customer groups, or for individual B2B customers.', 'documentation_url'=> 'hhttps://woocommerce-b2b-plugin.com/docs/reports/', 'image' => plugins_url('../includes/assets/images/module-reports.png', __FILE__)),
			array('title' => 'Purchase Lists', 'description' => 'B2B customers can save purchase lists / requisition lists. Lists can be downloaded as CSV files.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/purchase-lists-wish-lists-requisition-lists/', 'image' => plugins_url('../includes/assets/images/module-purchaselist.png', __FILE__)),
			array('title' => 'Group Changes', 'description' => 'Customers change groups automatically based on total spent, or the previous month\'s order value.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/automatic-group-changes-based-on-total-purchase-value/', 'image' => plugins_url('../includes/assets/images/module-group2.png', __FILE__)),
			array('title' => 'Purchase Order Gateway', 'description' => 'Allows users to order and enter their P.O. number during checkout.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/purchase-order-gateway/', 'image' => plugins_url('../includes/assets/images/module-po.png', __FILE__)),
			array('title' => 'Dynamic Rules - 20+ Types', 'description' => 'Complex rules system for discounts, hidden or fixed prices, free shipping, etc.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/dynamic-rules-discount-free-shipping-fixed-price-hidden-price-minimum-order-maximum-order-tax-exemption-required-multiple-add-tax-fee/', 'image' => plugins_url('../includes/assets/images/module-rules1.png', __FILE__)),
			array('title' => 'Coupons for B2B', 'description' => 'Restrict coupon usage, disable coupons for B2B , or set different coupon values for B2B & B2C.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/restrict-coupons-for-b2b-b2c-users/', 'image' => plugins_url('../includes/assets/images/module-coupon.png', __FILE__)),
			array('title' => 'VAT VIES Validation', 'description' => 'For EU buyers, verify VAT numbers automatically through the official VIES system.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/vat-and-vies-validation/', 'image' => plugins_url('../includes/assets/images/module-vat.png', __FILE__)),
			array('title' => 'Payment Limits', 'description' => 'Configure minimum or maximum limits for payment methods (e.g. min $1000+ for bank transfers).', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/payment-method-min-max-order-dynamic-rule/', 'image' => plugins_url('../includes/assets/images/module-paymentlimit.png', __FILE__)),
			array('title' => 'Taxes & Fees', 'description' => 'Configure custom taxes and fees by user, product, group, category, product value, etc. in a flexible way.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/dynamic-rules-discount-free-shipping-fixed-price-hidden-price-minimum-order-maximum-order-tax-exemption-required-multiple-add-tax-fee/', 'image' => plugins_url('../includes/assets/images/module-taxes.png', __FILE__)),
			array('title' => 'Currency Rules', 'description' => 'Set currency by user or group, allowing support for buyers in different markets.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/change-currency-for-users-groups/', 'image' => plugins_url('../includes/assets/images/module-currency.png', __FILE__)),
			array('title' => 'Quote Form', 'description' => 'Configure custom quote request form fields with 9+ field types including file uploads.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/add-custom-fields-to-quote-requests/', 'image' => plugins_url('../includes/assets/images/module-quotefields.png', __FILE__)),
			array('title' => 'Payment Discounts & Surcharges', 'description' => 'Configure discounts or surchages when paying with specific methods.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/payment-method-discounts-and-surcharges/', 'image' => plugins_url('../includes/assets/images/module-method.png', __FILE__)),
			array('title' => 'CSV Import & Export Tools', 'description' => 'Bulk import & export pricing data with built-in tools and integrations.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/how-to-bulk-import-export-product-prices/', 'image' => plugins_url('../includes/assets/images/module-import.png', __FILE__)),
			array('title' => 'Product Info Table', 'description' => 'Through the info table, you can show any data such as MSRP, weight, shipping, etc.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/custom-information-table-in-product-page/', 'image' => plugins_url('../includes/assets/images/module-productinfo.png', __FILE__)),
			array('title' => 'Company Approval', 'description' => 'Businesses can require approval for staff orders, reviewing and approving each order.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/company-order-approval/', 'image' => plugins_url('../includes/assets/images/module-company.png', __FILE__)),
			array('title' => 'Company Line of Credit', 'description' => 'Extend a line of credit to your B2B customers and set up a credit limit.', 'documentation_url'=> 'https://kingsplugins.com/woocommerce-wholesale/b2bking-company-credit/', 'image' => plugins_url('../includes/assets/images/module-credit4.png', __FILE__)),
			array('title' => 'White Label', 'description' => 'White label the plugin backend to add your company or customer logo and details.', 'documentation_url'=> 'https://woocommerce-b2b-plugin.com/docs/white-label-woocommerce-b2b-wholesale-solution/', 'image' => plugins_url('../includes/assets/images/module-document2.png', __FILE__)),
			array('title' => 'And much more...', 'description' => 'There are dozens of more features and integrations. <a href="https://webwizards.ticksy.com/submit/#100016894">Get in touch with us</a> for questions.', 'documentation_url'=> 'https://webwizards.ticksy.com/submit/#100016894', 'image' => plugins_url('../includes/assets/images/module-question2.png', __FILE__)),


		);


		if ($type === 'integrations'){
			$modules = $integration_modules;
		} else if ($type === 'pro'){
			$modules = $pro_modules;
		}

		foreach ($modules as $module){
			?>
			<div class="plugin-card">
				<div class="plugin-card-top">
					<div class="name column-name">
						<h3>
							<a href="#" class="thickbox open-plugin-details-modal">
							<?php echo esc_html($module['title']); ?>
							<img src="<?php echo esc_url($module['image']); ?>" class="plugin-icon" alt="">
							</a>
						</h3>
					</div>
					<div class="action-links">
						<ul class="plugin-action-buttons"><li>
							<div class="b2bking-checkbox-switch b2bkingproswitch b2bking_upgrade_modal_trigger">
							   <input type="checkbox" value="1" name="status" class="b2bking-input-checkbox">
							   
							   <div class="b2bking-checkbox-animate b2bking-pro-checkbox">
							      <span class="b2bking-checkbox-off">PRO</span>
							   </div>
							</div>
						</li></ul></div>
					<div class="desc column-description">
						<p><?php 
							echo wp_kses( $module['description'], array( 'a'     => array(
						        'href' => array()
						    ) ) );
						;?></p>
					</div>
				</div>
					<div class="plugin-card-bottom">
						<div class="column-downloaded">
							<a target="_blank" href="<?php echo esc_url($module['documentation_url']);?>"><?php esc_html_e('View Documentation','b2bking');?></a>				
						</div>
						
						<div class="column-compatibility">
							<span class="compatibility-compatible"><strong><?php esc_html_e('Unlock now','b2bking');?></strong><?php esc_html_e(' with a Premium License','b2bking');?></span>				
						</div>
						
					</div>		
			</div>
			<?php
		}
	}

	public static function b2bking_profeatures_page_content(){
		echo self::get_header_bar();

		?>
		<div class="wrap plugin-install-tab-featured b2bking_pro_modules_container">
			<div class="b2bking_core_modules_header">
				<div>
					<h1 class="wp-heading-inline"><strong><?php esc_html_e('Pro Features','b2bking');?></strong>
						<span class="dashicons dashicons-lock b2bking_modules_lock"></span>
					</h1>
					<a href="#" class="b2bkingproswitch b2bking_upgrade_modal_trigger"><strong><?php esc_html_e('(Unlock all with a Premium License! - 35% OFF TODAY)','b2bking');?></strong></a>
				</div>
			</div>
			
			<form class="plugin-filter" method="post">			
				<div class="wp-list-table widefat plugin-install">
					<div id="the-list" class="b2bking_pro_plugin_cards">
						<?php 
						self::b2bking_display_modules_cards('pro');
						?>
					</div>
				</div>			
			</form>
		</div>

		<?php
	}

	public static function b2bking_reports_page_content(){

		echo self::get_header_bar();

		// preloader if not in ajax - in ajax preloader is added via JS for smoother animations
		if (!wp_doing_ajax()){
			?>
			<div class="b2bkingpreloader">
			    <img class="b2bking_loader_icon_button" src="<?php echo esc_attr(plugins_url('../includes/assets/images/loaderpagegold5.svg', __FILE__));?>">
			</div>
			<?php
		}

		?>
		<div id="b2bking_dashboard_wrapper">
		    <div class="b2bking_dashboard_page_wrapper b2bking_reports_page_wrapper">
		        <div class="container-fluid">
		            <div class="row">
		                <div class="col-12">
		                    <div class="card card-hover">
		                        <div class="card-body">
		                            <div class="d-md-flex align-items-center">
		                                <div>
		                                    <h3 class="card-title"><?php esc_html_e('Sales Reports','b2bking');?></h3>
		                                    <h5 class="card-subtitle"><?php esc_html_e('Total Sales Value','b2bking');?></h5>
		                                </div>
		                                <div class="ml-auto d-flex no-block align-items-center">
		                                    <ul class="list-inline font-12 dl m-r-15 m-b-0 b2bking_reports_chart_info">
		                                        <li class="list-inline-item text-primary"><i class="mdi mdi-checkbox-blank-circle"></i> <?php esc_html_e('Gross Sales','b2bking');?></li>
		                                        <li class="list-inline-item text-cyan"><i class="mdi mdi-checkbox-blank-circle"></i> <?php esc_html_e('Net Sales','b2bking');?></li>
		                                        <li class="list-inline-item text-info"><i class="mdi mdi-checkbox-blank-circle"></i> <?php esc_html_e('Number of Orders','b2bking');?></li>
		                                        
		                                    </ul>
		                                    <div class="b2bking_reports_topright_container">
			                                    <div class="dl b2bking_reports_topright">
			                                        <select id="b2bking_reports_days_select" class="custom-select">
			                                        	<?php
			                                        	if(get_option( 'b2bking_plugin_status_setting', 'b2b' ) === 'hybrid'){
			                                        		?>
			                                        		<option value="all"><?php esc_html_e('All Customers (B2B + B2C)','b2bking');?></option>
			                                        		<?php
			                                        	}
			                                        	?>
			                                            <option value="b2b"><?php 

			                                            if(get_option( 'b2bking_plugin_status_setting', 'b2b' ) === 'hybrid'){

			                                            	esc_html_e('B2B Customers','b2bking');

			                                            } else {

			                                            	esc_html_e('All B2B Customers','b2bking');

			                                            }

			                                        	?></option>
			                                            <?php
			                                            if(get_option( 'b2bking_plugin_status_setting', 'b2b' ) === 'hybrid'){
			                                            	?>
			                                            	<option value="b2c"><?php esc_html_e('B2C Customers','b2bking');?></option>
			                                            	<?php
			                                            }
			                                            ?>
			                                            
			                                            <optgroup label="<?php esc_html_e('Groups', 'b2bking'); ?>">
			                                            	<?php
			                                            	$groups = get_posts( array( 'post_type' => 'b2bking_group','post_status'=>'publish','numberposts' => -1) );
			                                            	foreach ($groups as $group){
			                                            		?>
			                                            		<option disabled value="<?php echo 'group_'.esc_attr($group->ID);?>"><?php echo esc_html(get_the_title($group->ID)).' (Premium)'; ?></option>
			                                            		<?php
			                                            	}
			                                            	?>
			                                            </optgroup>
			                                            
			                                            <optgroup label="<?php esc_html_e('Individual Customers (B2B)', 'b2bking'); ?>">

				                                            <?php

				                                            $b2bcustomers = get_users(array(
		                                	    			    'meta_key'     => 'b2bking_b2buser',
		                                	    			    'meta_value'   => 'yes',
		                                	    			    'meta_compare' => '=',
		                                	    			));
				                                            foreach ($b2bcustomers as $b2bcus){
				                                            	?>
		                                	                    <option value="user_<?php echo esc_attr( $b2bcus->ID ); ?>" disabled><?php
		                                		                    echo apply_filters('b2bking_reports_customer_display_name_filter', $b2bcus->display_name. ' (Premium)', $b2bcus)
		                                	                    ?></option>
				                                            	<?php
				                                            }
				                                            ?>
				                                        </optgroup>	
			                                        </select>
			                                        <div class="b2bking_reports_fromto">
				                                        <div class="b2bking_reports_fromto_text"><?php esc_html_e('From:','b2bking'); ?></div>
				                                        <input type="date" id="b2bking_reports_date_input_from" class="b2bking_reports_date_input b2bking_reports_date_input_from">
				                                    </div>
				                                    <div class="b2bking_reports_fromto">
				                                        <div class="b2bking_reports_fromto_text"><?php esc_html_e('To:','b2bking'); ?></div>
				                                        <input type="date" class="b2bking_reports_date_input b2bking_reports_date_input_to">
				                                    </div>	
			                                    </div>
			                                    <div id="b2bking_reports_quick_links">
			                                    	<div class="b2bking_reports_linktext"><?php esc_html_e('Quick Select:','b2bking'); ?></div>
			                                    	<a id="b2bking_reports_link_thismonth" hreflang="thismonth" class="b2bking_reports_link"><?php esc_html_e('This Month','b2bking'); ?></a>
			                                    	<a hreflang="lastmonth" class="b2bking_reports_link"><?php esc_html_e('Last Month','b2bking'); ?></a>
			                                    	<a hreflang="thisyear" class="b2bking_reports_link"><?php esc_html_e('This Year','b2bking'); ?></a>
			                                    	<a hreflang="lastyear" class="b2bking_reports_link"><?php esc_html_e('Last Year','b2bking'); ?></a>
			                                    </div>
			                                </div>


		                                </div>
		                            </div>
		                            <div class="row">
		                                <!-- column -->
		                                <div class="col-lg-3">
		                                    <h1 class="b2bking_total_b2b_sales_today m-b-0 m-t-30"><?php echo 0 ?></h1>
		                                    <h6 class="font-light text-muted"><?php esc_html_e('Sales','b2bking');?></h6>
		                                    <h3 class="b2bking_number_orders_today m-t-30 m-b-0"><?php echo 0; ?></h3>
		                                    <h6 class="font-light text-muted"><?php esc_html_e('Orders','b2bking');?></h6>
		                                    <a id="b2bking_export_report_button" class="btn btn-info m-t-20 p-15 p-l-25 p-r-25 m-b-20" href="javascript:void(0)"><?php esc_html_e('Export Report File','b2bking');?>&nbsp;&nbsp;&nbsp;&nbsp;<i class="mdi mdi-lock"></i></a>
		                                </div>
		                                <!-- column -->
		                                <img class="b2bking_reports_icon_loader" src="<?php echo esc_attr(plugins_url('../includes/assets/images/loaderpagegold5.svg', __FILE__));?>">
		                                <div class="col-lg-9">
		                                    <div class="campaign ct-charts"></div>
		                                </div>
		                                <div class="col-lg-3">
		                                </div>
		                                <div class="col-lg-9">
		                                    <div class="campaign2 ct-charts"></div>
		                                </div>
		                                <!-- column -->
		                            </div>
		                        </div>
		                        <!-- ============================================================== -->
		                        <!-- Info Box -->
		                        <!-- ============================================================== -->
		                        <div class="card-body border-top">
		                            <div class="row m-b-0" id="b2bking_reports_first_row">
		                            	<!-- col -->
		                            	<div class="col-lg-3 col-md-6">
		                            	    <div class="d-flex align-items-center">
		                            	        <div class="m-r-10"><span class="text-orange display-5"><i class="mdi mdi-cart"></i></span></div>
		                            	        <div><span><?php esc_html_e('Gross Sales','b2bking');?></span>
		                            	            <h3 class="b2bking_reports_gross_sales font-medium m-b-0"><?php echo 0; ?></h3>
		                            	        </div>
		                            	    </div>
		                            	</div>
		                                <!-- col -->
		                                <div class="col-lg-3 col-md-6">
		                                    <div class="d-flex align-items-center">
		                                        <div class="m-r-10"><span class="text-cyan display-5"><i class="mdi mdi-cart-outline"></i></i></span></div>
		                                        <div><span><?php esc_html_e('Net Sales','b2bking');?></span>
		                                            <h3 class="b2bking_reports_net_sales font-medium m-b-0">
		                                            	<?php echo 0; ?>
		                                           	</h3>
		                                        </div>
		                                    </div>
		                                </div>
		                                <!-- col -->
		                                <div class="col-lg-3 col-md-6">
		                                    <div class="d-flex align-items-center">
		                                        <div class="m-r-10"><span class="text-info display-5"><i class="mdi mdi-package-variant"></i></span></div>
		                                        <div><span><?php esc_html_e('Orders Placed','b2bking');?></span>
		                                            <h3 class="b2bking_reports_number_orders font-medium m-b-0"><?php echo 0; ?></h3>
		                                        </div>
		                                    </div>
		                                </div>
		                                
		                                <!-- col -->
		                                <div class="col-lg-3 col-md-6">
		                                    <div class="d-flex align-items-center">
		                                        <div class="m-r-10"><span class="text-primary display-5"><i class="mdi mdi-tag-multiple"></i></i></span></div>
		                                        <div><span><?php esc_html_e('Items Purchased','b2bking');?></span>
		                                            <h3 class="b2bking_reports_items_purchased font-medium m-b-0"><?php echo 0; ?></h3>
		                                        </div>
		                                    </div>
		                                </div>
		                                <!-- col -->
		                            </div>
		                            <div class="row m-b-0" id="b2bking_reports_second_row">
		                            	<!-- col -->
		                            	<div class="col-lg-3 col-md-6">
		                            	    <div class="d-flex align-items-center">
		                            	        <div class="m-r-10"><span class="text-orange display-5"><i class="mdi mdi-shopping"></i></span></div>
		                            	        <div><span><?php esc_html_e('Average Order Value','b2bking');?></span>
		                            	            <h3 class="b2bking_reports_average_order_value font-medium m-b-0"><?php echo 0; ?></h3>
		                            	        </div>
		                            	    </div>
		                            	</div>
		                                <!-- col -->
		                                <div class="col-lg-3 col-md-6">
		                                    <div class="d-flex align-items-center">
		                                        <div class="m-r-10"><span class="text-cyan display-5"><i class="mdi mdi-credit-card-off"></i></i></span></div>
		                                        <div><span><?php esc_html_e('Refund Amount','b2bking');?></span>
		                                            <h3 class="b2bking_reports_refund_amount font-medium m-b-0">
		                                            	<?php echo 0; ?>
		                                           	</h3>
		                                        </div>
		                                    </div>
		                                </div>
		                                <!-- col -->
		                                <div class="col-lg-3 col-md-6">
		                                    <div class="d-flex align-items-center">
		                                        <div class="m-r-10"><span class="text-info display-5"><i class="mdi mdi-ticket-percent"></i></span></div>
		                                        <div><span><?php esc_html_e('Coupons Used','b2bking');?></span>
		                                            <h3 class="b2bking_reports_coupons_amount font-medium m-b-0"><?php echo 0; ?></h3>
		                                        </div>
		                                    </div>
		                                </div>
		                                
		                                <!-- col -->
		                                <div class="col-lg-3 col-md-6">
		                                    <div class="d-flex align-items-center">
		                                        <div class="m-r-10"><span class="text-primary display-5"><i class="mdi mdi-truck-delivery"></i></i></span></div>
		                                        <div><span><?php esc_html_e('Shipping Charges','b2bking');?></span>
		                                            <h3 class="b2bking_reports_shipping_charges font-medium m-b-0"><?php echo 0; ?></h3>
		                                        </div>
		                                    </div>
		                                </div>
		                                <!-- col -->
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            <div class="row">
		            	<!--
                        <div class="col-sm-6 col-lg-6" style="display:none">
                            <div class="card card-hover">
                                <div class="card-body">
    	                            <h4 class="card-title b2bking_reports_top_customers"><?php // esc_html_e('Top customers (selected period)','b2bking'); ?></h4>
    	                            <div class="table-responsive">
    	                                <table class="table v-middle">
    	                                    <thead>
    	                                        <tr>
    	                                            <th class="border-top-0"><?php // esc_html_e('Customer','b2bking'); ?></th>
    	                                            <th class="border-top-0"><?php // esc_html_e('Group','b2bking'); ?></th>
    	                                            <th class="border-top-0"><?php // esc_html_e('Total Spent','b2bking'); ?></th>
    	                                            <th class="border-top-0"></th>
    	                                        </tr>
    	                                    </thead>
    	                                    <tbody>
    	                                    	<tr>
	                                    		    <td>
	                                    		        <div class="d-flex align-items-center">
	                                    		            <div class="m-r-10"></div>
	                                    		            <div class="">
	                                    		                <h4 class="m-b-0 font-16">Jim Smith</h4><span>Johnsons LLC</span></div>
	                                    		        </div>
	                                    		    </td>
	                                    		    <td>test new</td>
	                                    		    <td>14 January</td>
	                                    		    <td class="font-medium">
	                                    		    	<div class="product-action ml-auto m-b-5 align-self-end">
	                                    		    		<a href="#b2bking_registration_data_container">
	                                    		    	    	<button class="btn btn-secondary"><?php // esc_html_e('Profile','b2bking'); ?></button>
	                                    		    	    </a>
                                    		    	    	<a href="#b2bking_registration_data_container">
                                    		    	        	<button class="btn btn-success"><?php // esc_html_e('Orders','b2bking'); ?></button>
                                    		    	        </a>
	                                    		    	</div>
	                                    		    </td>
	                                    		</tr>
	                                    		
    	                                    </tbody>
    	                                </table>
    	                            </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6" style="display:none">
                            <div class="card card-hover">
                                <div class="card-body">
    	                            <h4 class="card-title b2bking_reports_top_customers"><?php // esc_html_e('Top products (selected period)','b2bking'); ?></h4>
    	                            <div class="table-responsive">
    	                                <table class="table v-middle">
    	                                    <thead>
    	                                        <tr>
    	                                            <th class="border-top-0"><?php // esc_html_e('Product','b2bking'); ?></th>
    	                                            <th class="border-top-0"><?php // esc_html_e('Quantity Sold','b2bking'); ?></th>
    	                                            <th class="border-top-0"><?php // esc_html_e('Total Spent','b2bking'); ?></th>
    	                                            <th class="border-top-0"></th>
    	                                        </tr>
    	                                    </thead>
    	                                    <tbody>
    	                                    	<tr>
	                                    		    <td>
	                                    		        <div class="d-flex align-items-center">
	                                    		            <div class="m-r-10"></div>
	                                    		            <div class="">
	                                    		                <h4 class="m-b-0 font-16">Jim Smith</h4></div>
	                                    		        </div>
	                                    		    </td>
	                                    		    <td>test new</td>
	                                    		    <td>14 January</td>
	                                    		    <td class="font-medium">
	                                    		    	<div class="product-action ml-auto m-b-5 align-self-end">
	                                    		    		<a href="#b2bking_registration_data_container">
	                                    		    	    	<button class="btn btn-secondary"><?php // esc_html_e('Profile','b2bking'); ?></button>
	                                    		    	    </a>
                                    		    	    	<a href="#b2bking_registration_data_container">
                                    		    	        	<button class="btn btn-success"><?php // esc_html_e('Orders','b2bking'); ?></button>
                                    		    	        </a>
	                                    		    	</div>
	                                    		    </td>
	                                    		</tr>
	                                    		
    	                                    </tbody>
    	                                </table>
    	                            </div>
                                </div>
                            </div>
                        </div>
                    	-->
                    </div>

		        </div>
		    </div>
		</div>
		<table id="b2bking_admin_reports_export_table" style="display:none">
	        <thead>
	            <tr>
	                <th><?php esc_html_e('Date','b2bking'); ?></th>
	                <th><?php esc_html_e('Gross Sales','b2bking'); ?></th>
	                <th><?php esc_html_e('Net Sales','b2bking'); ?></th>
	                <th><?php esc_html_e('Number of Orders','b2bking'); ?></th>
	                <th><?php esc_html_e('Number of Items','b2bking'); ?></th>
	                <th><?php esc_html_e('Refund Amount','b2bking'); ?></th>
	                <th><?php esc_html_e('Worth of Coupons','b2bking'); ?></th>
	                <th><?php esc_html_e('Shipping Charges','b2bking'); ?></th>
	            </tr>
	        </thead>
	        <tbody>
	        	
	        </tbody>
	    </table>
		<?php

	}

	public static function b2bking_groups_page_content(){

		echo self::get_header_bar();

		?>
		<div id="b2bking_admin_groups_main_container">
			<div class="b2bking_admin_groups_main_title">
				<?php esc_html_e('Groups', 'b2bking'); ?>
			</div>
			<div class="b2bking_admin_groups_main_container_main_row">
				<div class="b2bking_admin_groups_main_container_main_row_left">
					<div class="b2bking_admin_groups_main_container_main_row_title">
						<?php esc_html_e('Business Groups','b2bking'); ?>
					</div>
					<div class="b2bking_admin_groups_main_container_main_row_subtitle">
						<?php esc_html_e('Create Groups + Control Payment and Shipping Methods','b2bking'); ?>
					</div>
					<a href="<?php echo admin_url( 'edit.php?post_type=b2bking_group'); ?>" class="b2bking_admin_groups_box_link">
						<div class="b2bking_admin_groups_main_container_main_row_left_box">
							<svg class="b2bking_admin_groups_main_container_main_row_left_box_icon" xmlns="http://www.w3.org/2000/svg" width="73" height="66" fill="#CED8E2" viewBox="0 0 73 66">
							  <path d="M29.2 47.667V44H3.686L3.65 58.667c0 4.07 3.249 7.333 7.3 7.333h51.1c4.052 0 7.3-3.263 7.3-7.333V44H43.8v3.667H29.2zm36.5-33H51.063V7.333L43.764 0h-14.6l-7.3 7.333v7.334H7.3c-4.015 0-7.3 3.3-7.3 7.333v11c0 4.07 3.248 7.333 7.3 7.333h21.9V33h14.6v7.333h21.9c4.015 0 7.3-3.3 7.3-7.333V22c0-4.033-3.285-7.333-7.3-7.333zm-21.9 0H29.2V7.333h14.6v7.334z"/>
							</svg>
							<div class="b2bking_admin_groups_main_container_main_row_box_text">
								<?php esc_html_e('Business (B2B) Groups','b2bking'); ?>
							</div>
						</div>
					</a>
				</div>
				<div class="b2bking_admin_groups_main_container_main_row_right">
					<div class="b2bking_admin_groups_main_container_main_row_title">
						<?php esc_html_e('B2C Groups','b2bking'); ?>
					</div>
					<div class="b2bking_admin_groups_main_container_main_row_subtitle">
						<?php esc_html_e('Control Payment and Shipping Methods','b2bking'); ?>
					</div>
					<div class="b2bking_admin_groups_main_container_main_row_right_boxes">
						<a href="<?php echo admin_url( 'admin.php?page=b2bking_b2c_users'); ?>" class="b2bking_admin_groups_box_link">
							<div class="b2bking_admin_groups_main_container_main_row_right_box b2bking_admin_groups_main_container_main_row_right_box_first">
								<svg class="b2bking_admin_groups_main_container_main_row_right_box_icon_first" xmlns="http://www.w3.org/2000/svg" width="49" height="61" fill="#62666A" viewBox="0 0 49 61">
								  <path d="M42.87 61a6.145 6.145 0 004.335-1.787A6.085 6.085 0 0049 54.9V6.1c0-1.618-.646-3.17-1.795-4.313A6.145 6.145 0 0042.87 0H6.13a6.145 6.145 0 00-4.335 1.787A6.085 6.085 0 000 6.1v48.8c0 1.618.646 3.17 1.795 4.313A6.145 6.145 0 006.13 61h36.74zM15.324 9.15h18.389v6.1H15.324v-6.1zm16.09 19.063a6.876 6.876 0 01-2.027 4.845 6.944 6.944 0 01-4.869 2.02c-3.785 0-6.895-3.096-6.895-6.866 0-3.77 3.11-6.862 6.895-6.862a6.94 6.94 0 014.868 2.018 6.873 6.873 0 012.028 4.845zm-20.687 21.16c0-5.075 6.215-10.293 13.791-10.293 7.577 0 13.792 5.218 13.792 10.293v1.718H10.727v-1.718z"/>
								</svg>
								<div class="b2bking_admin_groups_main_container_main_row_right_box_text b2bking_admin_groups_main_container_main_row_right_box_first_text">
									<?php esc_html_e('B2C Users','b2bking'); ?>
								</div>
							</div>
						</a>
						<a href="<?php echo admin_url( 'admin.php?page=b2bking_logged_out_users'); ?>" class="b2bking_admin_groups_box_link">
							<div class="b2bking_admin_groups_main_container_main_row_right_box b2bking_admin_groups_main_container_main_row_right_box_second">
								<svg class="b2bking_admin_groups_main_container_main_row_right_box_icon_second" xmlns="http://www.w3.org/2000/svg" width="49" height="61" fill="#62666A" viewBox="0 0 49 65">
								  <path d="M44.153 44.37l-9.696-6.41 3.655-6.816a11.01 11.01 0 001.301-5.187v-10.79c0-4.023-1.571-7.88-4.368-10.725A14.788 14.788 0 0024.5 0a14.788 14.788 0 00-10.545 4.442 15.299 15.299 0 00-4.368 10.725v10.79a11.011 11.011 0 001.3 5.187l3.656 6.816-9.696 6.41a10.727 10.727 0 00-3.562 3.914A10.942 10.942 0 000 53.454V65h49V53.453a10.943 10.943 0 00-1.285-5.17 10.728 10.728 0 00-3.562-3.913zm.586 16.297H4.261v-7.214a6.565 6.565 0 01.771-3.102 6.437 6.437 0 012.137-2.348l13.004-8.596-5.545-10.338a6.609 6.609 0 01-.78-3.112v-10.79c0-2.873 1.122-5.629 3.12-7.66A10.563 10.563 0 0124.5 4.332c2.825 0 5.535 1.142 7.532 3.173a10.927 10.927 0 013.12 7.66v10.79c0 1.088-.269 2.158-.78 3.113l-5.545 10.338 13.004 8.596a6.437 6.437 0 012.137 2.349c.508.952.773 2.018.771 3.101v7.214z"/>
								</svg>
								<div class="b2bking_admin_groups_main_container_main_row_right_box_text b2bking_admin_groups_main_container_main_row_right_box_second_text">
									<?php esc_html_e('Logged Out Users','b2bking'); ?>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public static function b2bking_b2c_users_page_content(){

		echo self::get_header_bar();

		?>
		<!-- User-specific shipping and payment methods -->
		<div class="b2bking_user_shipping_payment_methods_container b2bking_special_group_container">
			<div class="b2bking_above_top_title_button">
				<div class="b2bking_above_top_title_button_left">
					<?php esc_html_e('B2C Users','b2bking'); ?>
				</div>
				<div class="b2bking_above_top_title_button_right">
					<a href="<?php echo admin_url( 'admin.php?page=b2bking_groups'); ?>">
						<button type="button" class="b2bking_above_top_title_button_right_button">
							<?php esc_html_e('←  Go Back','b2bking'); ?>
						</button>
					</a>
				</div>
			</div>
			<div class="b2bking_user_shipping_payment_methods_container_top">
				<div class="b2bking_user_shipping_payment_methods_container_top_title">
					<?php esc_html_e('B2C Users Shipping and Payment Methods','b2bking'); ?>
				</div>		
			</div>
			<div class="b2bking_user_shipping_payment_methods_container_content">
				<div class="b2bking_user_payment_shipping_methods_container">
					<div class="b2bking_group_payment_shipping_methods_container_element">
						<div class="b2bking_group_payment_shipping_methods_container_element_title">
							<svg class="b2bking_group_payment_shipping_methods_container_element_title_icon_shipping" xmlns="http://www.w3.org/2000/svg" width="37" height="26" fill="none" viewBox="0 0 37 26">
							  <path fill="#C4C4C4" d="M31.114 6.5h-4.205V3.25c0-1.788-1.514-3.25-3.363-3.25H3.364C1.514 0 0 1.462 0 3.25v14.625c0 1.788 1.514 3.25 3.364 3.25C3.364 23.823 5.617 26 8.409 26s5.045-2.177 5.045-4.875h10.091c0 2.698 2.254 4.875 5.046 4.875 2.792 0 5.045-2.177 5.045-4.875h1.682c.925 0 1.682-.731 1.682-1.625v-5.411c0-.699-.236-1.382-.673-1.95L32.46 7.15a1.726 1.726 0 00-1.345-.65zM8.409 22.75c-.925 0-1.682-.731-1.682-1.625S7.484 19.5 8.41 19.5c.925 0 1.682.731 1.682 1.625s-.757 1.625-1.682 1.625zM31.114 8.937L34.41 13h-7.5V8.937h4.204zM28.59 22.75c-.925 0-1.682-.731-1.682-1.625s.757-1.625 1.682-1.625c.925 0 1.682.731 1.682 1.625s-.757 1.625-1.682 1.625z"></path>
							</svg>
							<?php esc_html_e('Shipping Methods', 'b2bking'); ?>
						</div>

						<?php

						if (apply_filters('b2bking_use_zone_shipping_control', true)){

							// list all shipping methods
							$shipping_methods = array();
							$zone_names = array();
							$zone = 0;
							$delivery_zones = WC_Shipping_Zones::get_zones();
					        foreach ($delivery_zones as $key => $the_zone) {
					            foreach ($the_zone['shipping_methods'] as $value) {
					                array_push($shipping_methods, $value);
					                array_push($zone_names, $the_zone['zone_name']);
					            }
					        }

			                // add UPS exception
			        		$shipping_methods_extra = WC()->shipping->get_shipping_methods();
			        		foreach ($shipping_methods_extra as $shipping_method){
			        			if ($shipping_method->id === 'wf_shipping_ups'){
			        				array_push($shipping_methods, $shipping_method);
			        				array_push($zone_names, 'UPS');
			        			}
			        		}

							foreach ($shipping_methods as $shipping_method){
								if( $shipping_method->enabled === 'yes' ){
									// check if there is an option set in the database. If not, create it and set it checked
									$option = get_option('b2bking_b2c_users_shipping_method_'.$shipping_method->id.$shipping_method->instance_id, 3);
									if(intval($option) === 3){
									    update_option('b2bking_b2c_users_shipping_method_'.$shipping_method->id.$shipping_method->instance_id, 1);
									}
									?>
									<div class="b2bking_group_payment_shipping_methods_container_element_method">
										<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
											<?php echo esc_html($shipping_method->title).' ('.esc_html($zone_names[$zone]).')'; ?>
										</div>
										<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_b2c_users_shipping_method_<?php echo esc_attr($shipping_method->id.$shipping_method->instance_id); ?>" name="b2bking_b2c_users_shipping_method_<?php echo esc_attr($shipping_method->id.$shipping_method->instance_id); ?>" <?php checked(1, get_option('b2bking_b2c_users_shipping_method_'.$shipping_method->id.$shipping_method->instance_id), true);  ?>>
									</div>
									<?php
								}
								$zone++;
					
							}

						} else {
							// use older mechanism here for cases where needed
							$shipping_methods = WC()->shipping->get_shipping_methods();

							foreach ($shipping_methods as $shipping_method){
								// check if there is an option set in the database. If not, create it and set it checked
								$option = get_option('b2bking_b2c_users_shipping_method_'.$shipping_method->id, 3);
								if(intval($option) === 3){
								    update_option('b2bking_b2c_users_shipping_method_'.$shipping_method->id, 1);
								}
								?>
								<div class="b2bking_group_payment_shipping_methods_container_element_method">
									<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
										<?php echo esc_html($shipping_method->method_title); ?>
									</div>
									<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_b2c_users_shipping_method_<?php echo esc_attr($shipping_method->id); ?>" name="b2bking_b2c_users_shipping_method_<?php echo esc_attr($shipping_method->id); ?>" <?php checked(1, get_option('b2bking_b2c_users_shipping_method_'.$shipping_method->id), true);  ?>>
								</div>
								<?php
							
							}
						}
						?>

					</div>
					<div class="b2bking_group_payment_shipping_methods_container_element">
						<div class="b2bking_group_payment_shipping_methods_container_element_title">
							<svg class="b2bking_group_payment_shipping_methods_container_element_title_icon_payment" xmlns="http://www.w3.org/2000/svg" width="37" height="30" fill="none" viewBox="0 0 37 30">
							  <path fill="#C4C4C4" d="M33.3 0H3.7A3.672 3.672 0 00.018 3.7L0 25.9c0 2.053 1.647 3.7 3.7 3.7h29.6c2.053 0 3.7-1.647 3.7-3.7V3.7C37 1.646 35.353 0 33.3 0zm0 25.9H3.7V14.8h29.6v11.1zm0-18.5H3.7V3.7h29.6v3.7z"/>
							</svg>
							<?php esc_html_e('Payment Methods', 'b2bking'); ?>
						</div>

						<?php
						// list all payment methods
						$payment_methods = WC()->payment_gateways->payment_gateways();

						foreach ($payment_methods as $payment_method){
							if( $payment_method->enabled === 'yes' ){
								// check if there is an option set in the database. If not, create it and set it checked
								$option = get_option('b2bking_b2c_users_payment_method_'.$payment_method->id, 3);
								if(intval($option) === 3){
								    update_option('b2bking_b2c_users_payment_method_'.$payment_method->id, 1);
								}
								?>
								<div class="b2bking_group_payment_shipping_methods_container_element_method">
									<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
										<?php echo esc_html($payment_method->title); ?>
									</div>
									<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_b2c_users_payment_method_<?php echo esc_attr($payment_method->id); ?>" name="b2bking_b2c_users_payment_method_<?php echo esc_attr($payment_method->id); ?>" <?php checked(1, get_option('b2bking_b2c_users_payment_method_'.$payment_method->id), true); ?>>
								</div>
								<?php
							}
						}
						?>

					</div>
				</div>

				<!-- Information panel -->
				<div class="b2bking_group_payment_shipping_information_box">
					<svg class="b2bking_group_payment_shipping_information_box_icon" xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 36 36">
					  <path fill="#358BBB" d="M18 0C8.06 0 0 8.06 0 18s8.06 18 18 18 18-8.06 18-18S27.94 0 18 0zm0 28.446a1.607 1.607 0 110-3.213 1.607 1.607 0 010 3.213zm2.527-8.819a1.941 1.941 0 00-1.241 1.8v.912a.322.322 0 01-.322.322h-1.928a.322.322 0 01-.322-.322v-.864c0-.928.27-1.844.8-2.607a4.49 4.49 0 012.093-1.643c1.366-.527 2.25-1.672 2.25-2.921 0-1.772-1.732-3.215-3.857-3.215s-3.857 1.443-3.857 3.215v.305a.322.322 0 01-.322.321h-1.928a.322.322 0 01-.322-.321v-.305c0-1.58.691-3.054 1.945-4.15C14.721 9.095 16.312 8.517 18 8.517c1.688 0 3.279.582 4.484 1.635 1.253 1.097 1.945 2.572 1.945 4.15 0 2.323-1.531 4.412-3.902 5.324z"/>
					</svg>
					<?php esc_html_e('In this panel, you can enable and disable shipping and payment methods for B2C users.','b2bking'); ?>
				</div>
			</div>
		</div>
		<button type="button" class="button-primary b2bking_b2c_special_group_container_save_settings_button">
			<?php esc_html_e('Save Settings', 'b2bking'); ?>
		</button>

		<?php
	}

	public static function b2bking_logged_out_users_page_content(){

		echo self::get_header_bar();

		?>
		<!-- User-specific shipping and payment methods -->
		<div class="b2bking_user_shipping_payment_methods_container b2bking_special_group_container">
			<div class="b2bking_above_top_title_button">
				<div class="b2bking_above_top_title_button_left">
					<?php esc_html_e('Logged Out Users','b2bking'); ?>
				</div>
				<div class="b2bking_above_top_title_button_right">
					<a href="<?php echo admin_url( 'admin.php?page=b2bking_groups'); ?>">
						<button type="button" class="b2bking_above_top_title_button_right_button">
							<?php esc_html_e('←  Go Back','b2bking'); ?>
						</button>
					</a>
				</div>
			</div>
			<div class="b2bking_user_shipping_payment_methods_container_top">
				<div class="b2bking_user_shipping_payment_methods_container_top_title">
					<?php esc_html_e('Logged Out Users Shipping and Payment Methods','b2bking'); ?>
				</div>		
			</div>
			<div class="b2bking_user_shipping_payment_methods_container_content">
				<div class="b2bking_user_payment_shipping_methods_container">
					<div class="b2bking_group_payment_shipping_methods_container_element">
						<div class="b2bking_group_payment_shipping_methods_container_element_title">
							<svg class="b2bking_group_payment_shipping_methods_container_element_title_icon_shipping" xmlns="http://www.w3.org/2000/svg" width="37" height="26" fill="none" viewBox="0 0 37 26">
							  <path fill="#C4C4C4" d="M31.114 6.5h-4.205V3.25c0-1.788-1.514-3.25-3.363-3.25H3.364C1.514 0 0 1.462 0 3.25v14.625c0 1.788 1.514 3.25 3.364 3.25C3.364 23.823 5.617 26 8.409 26s5.045-2.177 5.045-4.875h10.091c0 2.698 2.254 4.875 5.046 4.875 2.792 0 5.045-2.177 5.045-4.875h1.682c.925 0 1.682-.731 1.682-1.625v-5.411c0-.699-.236-1.382-.673-1.95L32.46 7.15a1.726 1.726 0 00-1.345-.65zM8.409 22.75c-.925 0-1.682-.731-1.682-1.625S7.484 19.5 8.41 19.5c.925 0 1.682.731 1.682 1.625s-.757 1.625-1.682 1.625zM31.114 8.937L34.41 13h-7.5V8.937h4.204zM28.59 22.75c-.925 0-1.682-.731-1.682-1.625s.757-1.625 1.682-1.625c.925 0 1.682.731 1.682 1.625s-.757 1.625-1.682 1.625z"></path>
							</svg>
							<?php esc_html_e('Shipping Methods', 'b2bking'); ?>
						</div>

						<?php

						if (apply_filters('b2bking_use_zone_shipping_control', true)){

							// list all shipping methods
							$shipping_methods = array();
							$zone_names = array();
							$zone = 0;

							$delivery_zones = WC_Shipping_Zones::get_zones();
					        foreach ($delivery_zones as $key => $the_zone) {
					            foreach ($the_zone['shipping_methods'] as $value) {
					                array_push($shipping_methods, $value);
					                array_push($zone_names, $the_zone['zone_name']);
					            }
					        }

			                // add UPS exception
			        		$shipping_methods_extra = WC()->shipping->get_shipping_methods();
			        		foreach ($shipping_methods_extra as $shipping_method){
			        			if ($shipping_method->id === 'wf_shipping_ups'){
			        				array_push($shipping_methods, $shipping_method);
			        				array_push($zone_names, 'UPS');
			        			}
			        		}

							foreach ($shipping_methods as $shipping_method){
								if( $shipping_method->enabled === 'yes' ){
									// check if there is an option set in the database. If not, create it and set it checked
									$option = get_option('b2bking_logged_out_users_shipping_method_'.$shipping_method->id.$shipping_method->instance_id, 3);
									if(intval($option) === 3){
									    update_option('b2bking_logged_out_users_shipping_method_'.$shipping_method->id.$shipping_method->instance_id, 1);
									}
									?>
									<div class="b2bking_group_payment_shipping_methods_container_element_method">
										<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
											<?php echo esc_html($shipping_method->title).' ('.esc_html($zone_names[$zone]).')'; ?>
										</div>
										<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_logged_out_users_shipping_method_<?php echo esc_attr($shipping_method->id.$shipping_method->instance_id); ?>" name="b2bking_logged_out_users_shipping_method_<?php echo esc_attr($shipping_method->id.$shipping_method->instance_id); ?>" <?php checked(1, get_option('b2bking_logged_out_users_shipping_method_'.$shipping_method->id.$shipping_method->instance_id), true);  ?>>
									</div>
									<?php
								}
								$zone++;
							}

						} else {
							// older mechnaism here for cases where needed
							// list all shipping methods
							$shipping_methods = WC()->shipping->get_shipping_methods();

							foreach ($shipping_methods as $shipping_method){
								// check if there is an option set in the database. If not, create it and set it checked
								$option = get_option('b2bking_logged_out_users_shipping_method_'.$shipping_method->id, 3);
								if(intval($option) === 3){
								    update_option('b2bking_logged_out_users_shipping_method_'.$shipping_method->id, 1);
								}
								?>
								<div class="b2bking_group_payment_shipping_methods_container_element_method">
									<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
										<?php echo esc_html($shipping_method->method_title); ?>
									</div>
									<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_logged_out_users_shipping_method_<?php echo esc_attr($shipping_method->id); ?>" name="b2bking_logged_out_users_shipping_method_<?php echo esc_attr($shipping_method->id); ?>" <?php checked(1, get_option('b2bking_logged_out_users_shipping_method_'.$shipping_method->id), true);  ?>>
								</div>
								<?php
							
							}
						}
						?>

					</div>
					<div class="b2bking_group_payment_shipping_methods_container_element">
						<div class="b2bking_group_payment_shipping_methods_container_element_title">
							<svg class="b2bking_group_payment_shipping_methods_container_element_title_icon_payment" xmlns="http://www.w3.org/2000/svg" width="37" height="30" fill="none" viewBox="0 0 37 30">
							  <path fill="#C4C4C4" d="M33.3 0H3.7A3.672 3.672 0 00.018 3.7L0 25.9c0 2.053 1.647 3.7 3.7 3.7h29.6c2.053 0 3.7-1.647 3.7-3.7V3.7C37 1.646 35.353 0 33.3 0zm0 25.9H3.7V14.8h29.6v11.1zm0-18.5H3.7V3.7h29.6v3.7z"/>
							</svg>
							<?php esc_html_e('Payment Methods', 'b2bking'); ?>
						</div>

						<?php
						// list all payment methods
						$payment_methods = WC()->payment_gateways->payment_gateways();

						foreach ($payment_methods as $payment_method){
							if( $payment_method->enabled === 'yes' ){
								// check if there is an option set in the database. If not, create it and set it checked
								$option = get_option('b2bking_logged_out_users_payment_method_'.$payment_method->id, 3);
								if(intval($option) === 3){
								    update_option('b2bking_logged_out_users_payment_method_'.$payment_method->id, 1);
								}
								?>
								<div class="b2bking_group_payment_shipping_methods_container_element_method">
									<div class="b2bking_group_payment_shipping_methods_container_element_method_name">
										<?php echo esc_html($payment_method->title); ?>
									</div>
									<input type="checkbox" value="1" class="b2bking_group_payment_shipping_methods_container_element_method_checkbox" id="b2bking_logged_out_users_payment_method_<?php echo esc_attr($payment_method->id); ?>" name="b2bking_logged_out_users_payment_method_<?php echo esc_attr($payment_method->id); ?>" <?php checked(1, get_option('b2bking_logged_out_users_payment_method_'.$payment_method->id), true); ?>>
								</div>
								<?php
							}
						}
						?>

					</div>
				</div>

				<!-- Information panel -->
				<div class="b2bking_group_payment_shipping_information_box">
					<svg class="b2bking_group_payment_shipping_information_box_icon" xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 36 36">
					  <path fill="#358BBB" d="M18 0C8.06 0 0 8.06 0 18s8.06 18 18 18 18-8.06 18-18S27.94 0 18 0zm0 28.446a1.607 1.607 0 110-3.213 1.607 1.607 0 010 3.213zm2.527-8.819a1.941 1.941 0 00-1.241 1.8v.912a.322.322 0 01-.322.322h-1.928a.322.322 0 01-.322-.322v-.864c0-.928.27-1.844.8-2.607a4.49 4.49 0 012.093-1.643c1.366-.527 2.25-1.672 2.25-2.921 0-1.772-1.732-3.215-3.857-3.215s-3.857 1.443-3.857 3.215v.305a.322.322 0 01-.322.321h-1.928a.322.322 0 01-.322-.321v-.305c0-1.58.691-3.054 1.945-4.15C14.721 9.095 16.312 8.517 18 8.517c1.688 0 3.279.582 4.484 1.635 1.253 1.097 1.945 2.572 1.945 4.15 0 2.323-1.531 4.412-3.902 5.324z"/>
					</svg>
					<?php esc_html_e('In this panel, you can enable and disable shipping and payment methods for Logged Out users.','b2bking'); ?>
				</div>
			</div>
		</div>
		<button type="button" class="button-primary b2bking_logged_out_special_group_container_save_settings_button">
			<?php esc_html_e('Save Settings', 'b2bking'); ?>
		</button>

		<?php
	}

	public static function get_header_bar(){
		
		?>
		<div id="b2bking_admin_header_bar">
			<div id="b2bking_admin_header_bar_left">
				<img style="width:112px;margin-left:5px;position: relative;top: 1.5px;" src="<?php echo plugins_url('../includes/assets/images/logo.png', __FILE__); ?>">
				<div id="b2bking_admin_header_version2"><?php echo B2BKINGCORE_VERSION; ?></div>
			</div>

			<div id="b2bking_admin_header_bar_right">
				<?php
				$supportlink = 'https://wordpress.org/support/plugin/b2bking-wholesale-for-woocommerce/';
				$sitelink = 'https://woocommerce-b2b-plugin.com';
				?>
				<a class="b2bking_admin_header_right_element" href="https://woocommerce-b2b-plugin.com/docs" target="_blank"><span class="dashicons <?php echo apply_filters('b2bking_header_documentation_dashicon','dashicons-edit-page');?> b2bking_header_icon"></span><?php esc_html_e('Documentation', 'b2bking');?></a>
				<a class="b2bking_admin_header_right_element" href="<?php echo esc_attr($supportlink);?>" target="_blank"><span class="dashicons dashicons-universal-access-alt b2bking_header_icon"></span><?php esc_html_e('Support', 'b2bking');?></a>
				<a class="b2bking_admin_header_right_element b2bking_upgrade_top_button" href="<?php echo esc_attr($sitelink);?>" target="_blank"><span class="dashicons dashicons-superhero b2bking_header_icon"></span><?php esc_html_e('Upgrade to Premium', 'b2bking');?></a>
				
			</div>
		</div>
		<?php
	}

	function b2bking_show_header_bar_b2bking_posts(){
		global $post;
		if (isset($post->ID)){
			$post_type = get_post_type($post->ID);
			if (substr($post_type,0,7) === 'b2bking'){
				echo self::get_header_bar();
			}
		}
	}

	public static function b2bking_orderform_page_content(){

		echo self::get_header_bar();
		?>

		<div id="b2bking_orderform_container">
			<h1 class="b2bking_page_title_upgrade"><?php esc_html_e('Supercharge Your Wholesale Experience!','b2bking');?></h1>
			<h3 class="b2bking_page_subtitle_upgrade"><?php esc_html_e('Upgrade to B2BKing Premium to unlock a powerful order form, tiered pricing, quote requests, business registration, tax exemptions, invoice payments, and so much more.','b2bking');?></h3>
			<div class="b2bking_upgrade_container_box"></div>

			<a href="https://demobk2.wpbay.co/wholesale-order-form/" target="_blank"><button class="b2bking_demo_feature_button"><span class="dashicons dashicons-paperclip"></span><?php esc_html_e('View Live Demo','b2bking');?></button></a>
			<a href="https://woocommerce-b2b-plugin.com/woocommerce-wholesale-order-form-bulk-order-plugin/" target="_blank"><button class="b2bking_demo_feature_button_white"><span class="dashicons dashicons-welcome-learn-more"></span><?php esc_html_e('Learn More','b2bking');?></button></a>

			<div class="b2bking_gifcontainer">
				<img class="b2bking_gif" src="https://kingsplugins.com/wp-content/uploads/2023/06/orderformcrop.gif">
			</div>

		</div>
		<?php
	}

	public static function b2bking_tieredpricing_page_content(){

		echo self::get_header_bar();
		?>

		<div id="b2bking_orderform_container">
			<h1 class="b2bking_page_title_upgrade"><?php esc_html_e('Supercharge Your Wholesale Pricing!','b2bking');?></h1>
			<h3 class="b2bking_page_subtitle_upgrade"><?php esc_html_e('Upgrade to B2BKing Premium to unlock a powerful order form, tiered pricing, quote requests, business registration, tax exemptions, invoice payments, and so much more.','b2bking');?></h3>
			<div class="b2bking_upgrade_container_box"></div>

			<a href="https://demobk2.wpbay.co/product/henry-backpack/" target="_blank"><button class="b2bking_demo_feature_button"><span class="dashicons dashicons-paperclip"></span><?php esc_html_e('View Live Demo','b2bking');?></button></a>
			<a href="https://woocommerce-b2b-plugin.com/docs/b2bking-tiered-pricing-setup-auto-generated-tiered-pricing-table/" target="_blank"><button class="b2bking_demo_feature_button_white"><span class="dashicons dashicons-welcome-learn-more"></span><?php esc_html_e('Learn More','b2bking');?></button></a>

			<div class="b2bking_gifcontainer">
				<img class="b2bking_gif2" src="https://kingsplugins.com/wp-content/uploads/2023/06/tieredanimationcrop.gif">
			</div>
		</div>
		<?php
	}

	public static function b2bking_businessregistration_page_content(){

		echo self::get_header_bar();
		?>

		<div id="b2bking_orderform_container">
			<h1 class="b2bking_page_title_upgrade"><?php esc_html_e('Supercharge Business Customer Registration!','b2bking');?></h1>
			<h3 class="b2bking_page_subtitle_upgrade"><?php esc_html_e('Upgrade to B2BKing Premium to unlock a powerful order form, tiered pricing, quote requests, business registration, tax exemptions, invoice payments, and so much more.','b2bking');?></h3>
			<div class="b2bking_upgrade_container_box"></div>

			<a href="https://demobk2.wpbay.co/business-registration/" target="_blank"><button class="b2bking_demo_feature_button"><span class="dashicons dashicons-paperclip"></span><?php esc_html_e('View Live Demo','b2bking');?></button></a>
			<a href="https://woocommerce-b2b-plugin.com/docs/extended-registration-and-custom-fields/" target="_blank"><button class="b2bking_demo_feature_button_white"><span class="dashicons dashicons-welcome-learn-more"></span><?php esc_html_e('Learn More','b2bking');?></button></a>

			<div class="b2bking_gifcontainer">
				<img class="b2bking_gif" src="https://kingsplugins.com/wp-content/uploads/2023/06/frame7.gif">
			</div>
		</div>
		<?php
	}

	public static function b2bking_customers_page_content(){

		echo self::get_header_bar();


		// if more than 500 users, get only the first 500.
		if (intval(get_option('b2bking_customers_panel_ajax_setting', 0)) !== 1){
			// get all WooCommerce customers
			if(get_option( 'b2bking_plugin_status_setting', 'b2b' ) === 'hybrid'){
				$args = array(
					'meta_key'     => 'b2bking_b2buser',
					'meta_value'   => 'yes',
				    'role'   		=> 'customer',
				    'fields'=> array('ID', 'display_name'),
				);
				$users = get_users( $args );

				$users_not_approved = get_users(array(
				    'meta_key'     => 'b2bking_account_approved',
				    'meta_value'   => 'no',
				    'role'    => 'customer',
				    'fields' => array('ID', 'display_name'),
				));

				$users = array_merge($users, $users_not_approved);
				
			} else {
				$args = array(
				    'role'    => 'customer',
				    'fields'=> array('ID', 'display_name'),
				);

				$users = get_users( $args );
			}
		} else {
			$users = array();
		}
		

		?>
		<h1 class="b2bking_page_title"><?php esc_html_e('B2B Customers','b2bking');?></h1>
		<div id="b2bking_admin_customers_table_container">
			<table id="b2bking_admin_customers_table">
			        <thead>
			            <tr>
			                <th><?php esc_html_e('Name','b2bking'); ?></th>
			                <th><?php esc_html_e('Company Name','b2bking'); ?></th>
			                <th><?php esc_html_e('Customer Group','b2bking'); ?></th>
			                <th><?php esc_html_e('Account Type','b2bking'); ?></th>
			                <th><?php esc_html_e('Approval','b2bking'); ?></th>
			                <th><?php esc_html_e('Total Spent','b2bking'); ?></th>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php

			        	foreach ( $users as $user ) {

			        		$user_id = $user->ID;
			        		$original_user_id = $user_id;
			        		$username = $user->display_name;

			        		// first check if subaccount. If subaccount, user is equivalent with parent
			        		$account_type = get_user_meta($user_id, 'b2bking_account_type', true);
			        		if ($account_type === 'subaccount'){
			        			// get parent
			        			$parent_account_id = get_user_meta ($user_id, 'b2bking_account_parent', true);
			        			$user_id = $parent_account_id;
			        			$account_type = esc_html__('Subaccount','b2bking');
			        		} else {
			        			$account_type = esc_html__('Main business account','b2bking');
			        		}

			        		$company_name = get_user_meta($user_id, 'billing_company', true);
			        		if (empty($company_name)){
			        			$company_name = '-';
			        		}

			        		$b2b_enabled = get_user_meta($user_id, 'b2bking_b2buser', true);
			        		if ($b2b_enabled === 'yes'){
			        			$b2b_enabled = 'Business';
			        		} else {
			        			$b2b_enabled = 'Consumer';
			        			$account_type = '-';
			        		}

			        		$group_name = get_the_title(get_user_meta($user_id, 'b2bking_customergroup', true));
			        		if (empty($group_name)){
			        			$group_name = '-';
			        			if ($b2b_enabled !== 'yes'){
			        				$group_name = 'B2C Users';
			        			}
			        		}

			        		$approval = get_user_meta($user_id, 'b2bking_account_approved', true);
			        		if (empty($approval)){
			        			$approval = '-';
			        		} else if ($approval === 'no'){
			        			$approval = esc_html__('Waiting Approval','b2bking');
			        		}

			        		$customer = new WC_Customer($user_id);
			        		$total_spent = $customer->get_total_spent();

			        		echo
			        		'<tr>
			        		    <td><a href="'.esc_attr(get_edit_user_link($original_user_id)).'">'.esc_html( $username ).'</a></td>
			        		    <td>'.esc_html( $company_name ).'</td>
			        		    <td>'.esc_html( $group_name ).'</td>
			        		    <td>'.esc_html( $account_type ).'</td>
			        		    <td>'.esc_html( $approval ).'</td>
			        		    <td data-order="'.$total_spent.'">'.wc_price( $total_spent ).'</td>

			        		</tr>';
			        	}

			        	?>
			           
			        </tbody>
			        <tfoot>
			            <tr>
			                <th><?php esc_html_e('Name','b2bking'); ?></th>
			                <th><?php esc_html_e('Company Name','b2bking'); ?></th>
			                <th><?php esc_html_e('Customer Group','b2bking'); ?></th>
			                <th><?php esc_html_e('Account Type','b2bking'); ?></th>
			                <th><?php esc_html_e('Approval','b2bking'); ?></th>
			                <th><?php esc_html_e('Total Spent','b2bking'); ?></th>
			            </tr>
			        </tfoot>
			    </table>
			</div>
		<?php
	}

	public static function b2bking_dashboard_page_content(){

		echo self::get_header_bar();

		// preloader if not in ajax - in ajax preloader is added via JS for smoother animations
		if (!wp_doing_ajax()){
			?>
			<div class="b2bkingpreloader">
			    <img class="b2bking_loader_icon_button" src="<?php echo esc_attr(plugins_url('../includes/assets/images/loaderpagegold5.svg', __FILE__));?>">
			</div>
			<?php
		}

		$data = self::b2bking_get_dashboard_data();	

		// Send data to JS
		$translation_array = array(
			'days_sales_b2b' => apply_filters('b2bking_dashboard_days_sales_b2b', $data['days_sales_array']),
			'days_sales_b2c' => apply_filters('b2bking_dashboard_days_sales_b2c', $data['days_sales_b2c_array']),
			'hours_sales_b2b' => array_values($data['hours_sales_array']),
			'hours_sales_b2c' => array_values($data['hours_sales_b2c_array']),
			'b2bking_demo' => apply_filters('b2bking_is_dashboard_demo', 0),
			'currency_symbol' => get_woocommerce_currency_symbol(),
		);

		wp_localize_script( 'b2bking_global_admin_script', 'b2bking_dashboard', $translation_array );

		?>

		<div id="b2bking_dashboard_wrapper">
		    <div class="b2bking_dashboard_page_wrapper">
		        <div class="container-fluid">
		            <div class="row">
		                <div class="col-12">
		                    <div class="card card-hover">
		                        <div class="card-body">
		                            <div class="d-md-flex align-items-center">
		                                <div>
		                                    <h4 class="card-title"><?php esc_html_e('B2B Sales Summary','b2bking'); do_action('b2bking_dashboard_after_sales_summary');?></h4>
		                                    <h5 class="card-subtitle"><?php esc_html_e('Total Sales Value','b2bking');?></h5>
		                                </div>
		                                <div class="ml-auto d-flex no-block align-items-center">
		                                    <ul class="list-inline font-12 dl m-r-15 m-b-0">
		                                        <li class="list-inline-item text-info"><i class="mdi mdi-checkbox-blank-circle"></i> <?php esc_html_e('B2B Sales','b2bking');?></li>
		                                        <?php
		                                        if (get_option( 'b2bking_plugin_status_setting', 'b2b' ) === 'hybrid'){
		                                        	?>
		                                        	<li class="list-inline-item text-primary"><i class="mdi mdi-checkbox-blank-circle"></i> <?php esc_html_e('B2C Sales','b2bking');?></li>
		                                        <?php
		                                        }
		                                        ?>
		                                    </ul>
		                                    <div class="dl">
		                                        <select id="b2bking_dashboard_days_select" class="custom-select">
		                                            <option value="0" selected><?php esc_html_e('Today','b2bking');?></option>
		                                            <option value="1"><?php esc_html_e('Last 7 Days','b2bking');?></option>
		                                            <option value="2"><?php esc_html_e('Last 31 Days','b2bking');?></option>
		                                        </select>
		                                        <div id="b2bking_refresh_data_container">
		                                        	<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 13 13">
		                                        	  <g clip-path="url(#a)">
		                                        	    <path fill="#9E9E9E" d="M2.112 4.55C2.845 2.844 4.55 1.625 6.5 1.625c2.438 0 4.387 1.788 4.794 4.063h1.625C12.512 2.518 9.83 0 6.5 0 4.062 0 1.95 1.3.894 3.331L0 2.438v3.25h3.25L2.112 4.55ZM13 7.313H9.669l1.218 1.137a4.844 4.844 0 0 1-4.468 2.925c-2.356 0-4.388-1.787-4.794-4.063H0C.406 10.482 3.169 13 6.419 13c2.437 0 4.55-1.381 5.687-3.331l.894.893v-3.25Z"/>
		                                        	  </g>
		                                        	  <defs>
		                                        	    <clipPath id="a">
		                                        	      <path fill="#fff" d="M0 0h13v13H0z"/>
		                                        	    </clipPath>
		                                        	  </defs>
		                                        	</svg>
		                                        	<span class="b2bking_refresh_data_text"><?php esc_html_e('Refresh Data','b2bking'); ?></span>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="row">
		                                <!-- column -->
		                                <div class="col-lg-3">
		                                    <h1 class="b2bking_total_b2b_sales_today m-b-0 m-t-30"><?php echo apply_filters('b2bking_dashboard_total_sales_today',wc_price($data['total_b2b_sales_today'])); ?></h1>
		                                    <h1 class="b2bking_total_b2b_sales_seven_days m-b-0 m-t-30"><?php echo wc_price($data['total_b2b_sales_seven_days']); ?></h1>
		                                    <h1 class="b2bking_total_b2b_sales_thirtyone_days m-b-0 m-t-30"><?php echo wc_price($data['total_b2b_sales_thirtyone_days']); ?></h1>
		                                    <h6 class="font-light text-muted"><?php esc_html_e('Gross Sales','b2bking');?></h6>
		                                    <h3 class="b2bking_number_orders_today m-t-30 m-b-0"><?php echo apply_filters('b2bking_dashboard_gross_sales',esc_html($data['number_b2b_sales_today'])); ?></h3>
		                                    <h3 class="b2bking_number_orders_seven m-t-30 m-b-0"><?php echo esc_html($data['number_b2b_sales_seven_days']); ?></h3>
		                                    <h3 class="b2bking_number_orders_thirtyone m-t-30 m-b-0"><?php echo esc_html($data['number_b2b_sales_thirtyone_days']); ?></h3>
		                                    <h6 class="font-light text-muted"><?php esc_html_e('Orders','b2bking');?></h6>
		                                    <a id="b2bking_dashboard_blue_button" class="btn btn-info m-t-20 p-15 p-l-25 p-r-25 m-b-20" href="javascript:void(0)"></a>
		                                </div>
		                                <!-- column -->
		                                <div class="col-lg-9">
		                                    <div class="campaign ct-charts"></div>
		                                </div>
		                                <!-- column -->
		                            </div>
		                        </div>
		                        <!-- ============================================================== -->
		                        <!-- Info Box -->
		                        <!-- ============================================================== -->
		                        <div class="card-body border-top">
		                            <div class="row m-b-0">
		                                <!-- col -->
		                                <div class="col-lg-3 col-md-6">
		                                    <div class="d-flex align-items-center">
		                                        <div class="m-r-10"><span class="text-orange display-5"><i class="mdi mdi-cart"></i></span></div>
		                                        <div><span><?php esc_html_e('Total Sales','b2bking');?></span>
		                                            <h3 class="b2bking_total_b2b_sales_today font-medium m-b-0">
		                                            	<?php echo apply_filters('b2bking_dashboard_total_sales_today',wc_price($data['total_b2b_sales_today'])); ?>
		                                           	</h3>
		                                           	<h3 class="b2bking_total_b2b_sales_seven_days font-medium m-b-0">
	                                           	 		<?php echo wc_price($data['total_b2b_sales_seven_days']); ?>
	                                           		</h3>
		                                           	<h3 class="b2bking_total_b2b_sales_thirtyone_days font-medium m-b-0">
	                                           	 		<?php echo wc_price($data['total_b2b_sales_thirtyone_days']); ?>
	                                           		</h3>
		                                        </div>
		                                    </div>
		                                </div>
		                                <!-- col -->
		                                <!-- col -->
		                                <div class="col-lg-3 col-md-6">
		                                    <div class="d-flex align-items-center">
		                                        <div class="m-r-10"><span class="text-cyan display-5"><i class="mdi mdi-package"></i></span></div>
		                                        <div><span><?php esc_html_e('Orders Nr.','b2bking');?></span>
		                                            <h3 class="b2bking_number_orders_today font-medium m-b-0"><?php echo apply_filters('b2bking_dashboard_gross_sales',esc_html($data['number_b2b_sales_today'])); ?></h3>
		                                            <h3 class=" b2bking_number_orders_seven font-medium m-b-0"><?php echo esc_html($data['number_b2b_sales_seven_days']); ?></h3>
		                                            <h3 class="b2bking_number_orders_thirtyone font-medium m-b-0"><?php echo esc_html($data['number_b2b_sales_thirtyone_days']); ?></h3>
		                                        </div>
		                                    </div>
		                                </div>
		                                <!-- col -->
		                                <!-- col -->
		                                <div class="col-lg-3 col-md-6">
		                                    <div class="d-flex align-items-center">
		                                        <div class="m-r-10"><span class="text-info display-5"><i class="mdi mdi-account-star"></i></span></div>
		                                        <div><span><?php esc_html_e('Customers Nr.','b2bking');?></span>
		                                            <h3 class="b2bking_number_customers_today font-medium m-b-0"><?php echo apply_filters('b2bking_dashboard_customer_nr',esc_html($data['customers_b2b_sales_today'])); ?></h3>
		                                            <h3 class="b2bking_number_customers_seven font-medium m-b-0"><?php echo esc_html($data['customers_b2b_sales_seven_days']); ?></h3>
		                                            <h3 class="b2bking_number_customers_thirtyone font-medium m-b-0"><?php echo esc_html($data['customers_b2b_sales_thirtyone_days']); ?></h3>
		                                        </div>
		                                    </div>
		                                </div>
		                                <!-- col -->
		                                <!-- col -->
		                                <div class="col-lg-3 col-md-6">
		                                    <div class="d-flex align-items-center">
		                                        <div class="m-r-10"><span class="text-primary display-5"><i class="mdi mdi-currency-usd"></i></span></div>
		                                        <div><span><?php esc_html_e('Net Earnings','b2bking');?></span>
		                                            <h3 class="b2bking_net_earnings_today font-medium m-b-0"><?php echo apply_filters('b2bking_dashboard_net_earnings', wc_price($data['total_b2b_sales_today']-$data['tax_b2b_sales_today'])); ?></h3>
		                                            <h3 class="b2bking_net_earnings_seven font-medium m-b-0"><?php echo wc_price($data['total_b2b_sales_seven_days']-$data['tax_b2b_sales_seven_days']); ?></h3>
		                                            <h3 class="b2bking_net_earnings_thirtyone font-medium m-b-0"><?php echo wc_price($data['total_b2b_sales_thirtyone_days']-$data['tax_b2b_sales_thirtyone_days']); ?></h3>
		                                        </div>
		                                    </div>
		                                </div>
		                                <!-- col -->
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            <div class="row">
		                <div class="col-sm-12 col-lg-8">
		                    <div class="card card-hover">
		                        <div class="card-body">
		                        	<?php
			                        	// get all users that need approval
			                        	$users_not_approved = get_users(array(
			                        		'meta_key'     => 'b2bking_account_approved',
			                        		'meta_value'   => 'no',
			                        		'orderby'	   => 'user_registered',
			                        		'order'		   => 'DESC'
			                        	));
			                        	$reg_count = count($users_not_approved);

			                        	if ($reg_count === 0){
			                        		echo '<h1>'.esc_html__('Nothing here...', 'b2bking').'<br />'.esc_html__('No registrations need approval!', 'b2bking').'</h1>';
			                        	} else {

			                        	?>
			                            <h4 class="card-title"><?php echo $reg_count; esc_html_e(' New Registrations - Approval Needed' ,'b2bking'); ?></h4>
			                            <div class="table-responsive">
			                                <table class="table v-middle">
			                                    <thead>
			                                        <tr>
			                                            <th class="border-top-0"><?php esc_html_e('Name and Email','b2bking'); ?></th>
			                                            <th class="border-top-0"><?php esc_html_e('Reg. Role','b2bking'); ?></th>
			                                            <th class="border-top-0"><?php esc_html_e('Reg. Date','b2bking'); ?></th>
			                                            <th class="border-top-0"><?php esc_html_e('Approval','b2bking'); ?></th>
			                                        </tr>
			                                    </thead>
			                                    <tbody>
			                                    	<?php
			                                    	$i=1;
			                                    	foreach ($users_not_approved as $user){
			                                    		// get role string
			                                    		$user_role = get_user_meta($user->ID, 'b2bking_registration_role', true);
			                                    		if (isset(explode('_',$user_role)[1])){
			                                    			$user_role_id = explode('_',$user_role)[1];
			                                    		} else {
			                                    			$user_role_id = 0;
			                                    		}
			                                    		$user_role_name = get_the_title($user_role_id);

			                                    		?>
			                                    		<tr>
			                                    		    <td>
			                                    		        <div class="d-flex align-items-center">
			                                    		            <div class="m-r-10"><img src="<?php echo apply_filters('b2bking_dashboard_user_icon_img', plugins_url('assets/dashboard/usersicons/d'.$i.'.jpg', __FILE__), $i);?>" alt="user" class="rounded-circle" width="45" /></div>
			                                    		            <div class="">
			                                    		                <h4 class="m-b-0 font-16"><?php echo esc_html($user->first_name.' '.$user->last_name); ?></h4><span><?php echo esc_html($user->user_email); ?></span></div>
			                                    		        </div>
			                                    		    </td>
			                                    		    <td><?php echo esc_html($user_role_name); ?></td>
			                                    		    <td><?php echo esc_html(date( "d/m/Y", strtotime( $user->user_registered ) ));?></td>
			                                    		    <td class="font-medium">
			                                    		    	<div class="product-action ml-auto m-b-5 align-self-end">
			                                    		    		<a href="<?php echo esc_attr(get_edit_user_link($user->ID).'#b2bking_registration_data_container'); ?>">
			                                    		    	    <button class="btn btn-success"><?php esc_html_e('Review','b2bking'); ?></button></a>
			                                    		    	   
			                                    		    	</div>
			                                    		    </td>
			                                    		</tr>
			                                    		<?php
			                                    		$i++;
			                                    		if ($i===4){
			                                    			$i = 1;
			                                    		}
			                                    	}
			                                    	?>
			                                        
			                                    </tbody>
			                                </table>
			                            </div>
			                            <?php
			                        }
			                        ?>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-sm-12 col-lg-4">
		                	<div class="card card-hover bg-info b2bking_upgrade_modal_trigger">
		                	    <div class="card-body">
		                	        <h4 class="card-title text-white op-5"><?php esc_html_e('Upgrade to B2BKing Premium','b2bking');?></h4>
		                	        <h3 class="text-white">
		                	    	<?php esc_html_e('Messages', 'b2bking'); ?>
		                	        </h3>
		                	        <i class="mdi mdi-email b2bking_mail_icon"></i>
		                	    </div>
		                	</div>
	                        <a href="<?php echo admin_url('/edit.php?post_type=shop_order'); ?>">
		                        <div class="card card-hover bg-orange">
		                            <div class="card-body">
		                                <h4 class="card-title text-white op-5"><?php esc_html_e('You have','b2bking');?></h4>
		                                <h3 class="text-white">
		                                	<?php

		                                	$total = wc_processing_order_count();

		                                	echo esc_html($total);
		                                	esc_html_e(' New Orders','b2bking');
		                                	?>
		                                </h3>
		                                <i class="mdi mdi-shopping b2bking_mail_icon"></i>
		                            </div>
		                        </div>
		                    </a>
	                    </div>
		            </div>
		        </div>
		    </div>
		</div>
		<?php
	}

	public static function b2bking_get_dashboard_data(){
		$data = array();

		$dashboarddata = get_transient('webwizards_dashboard_data_cache');
		if ($dashboarddata){
			$data = $dashboarddata;

			// check cache time - clear every 12 hours
			$time = intval(get_transient('webwizards_dashboard_data_cache_time'));
			if (($time-time()) > apply_filters('b2bking_cache_time_setting', 1600)){
				// clear cache
				delete_transient('webwizards_dashboard_data_cache');
				delete_transient('webwizards_dashboard_data_cache_time');
				$dashboarddata = false;
				$data = array();
			}
		}

		if (!$dashboarddata){

			// get all orders in past 31 days for calculations
			global $wpdb;

			if (apply_filters('b2bking_dashboard_set_timezone', true)){
				$timezone = get_option('timezone_string');
				if (empty($timezone) || $timezone === null){
					$timezone = 'UTC';
				}
			}
			
			$date_to = date('Y-m-d H:i:s');
			$date_from = date('Y-m-d');
			$args = array(
				'status' => apply_filters('b2bking_reports_statuses', array('wc-on-hold','wc-pending','wc-processing', 'wc-completed') ),
			    'date_created' => $date_from,
			    'limit' => -1,
			    'type' => 'shop_order',

			);
			$orders_today = wc_get_orders( $args );


			$date_from = date('Y-m-d', strtotime('-7 days'));

			$args = array(
				'status' => apply_filters('b2bking_reports_statuses', array('wc-on-hold','wc-pending','wc-processing', 'wc-completed') ),
			    'date_created' => '>='.$date_from,
			    'limit' => -1,
			    'type' => 'shop_order',

			);
			$orders_seven_days = wc_get_orders( $args );


			$date_from = date('Y-m-d', strtotime('-31 days'));

			
	        $args = array(
	        	'status' => apply_filters('b2bking_reports_statuses', array('wc-on-hold','wc-pending','wc-processing', 'wc-completed') ),
	            'date_created' => '>='.$date_from,
	            'limit' => -1,
	            'type' => 'shop_order',

	        );
	        $orders_thirtyone_days = wc_get_orders( $args );

			// if b2bking is in b2b mode, ignore whether user is B2B
			$plugin_status = get_option( 'b2bking_plugin_status_setting', 'b2b' );

			// total b2b sales
			$total_b2b_sales_today = 0;
			$total_b2b_sales_seven_days = 0;
			$total_b2b_sales_thirtyone_days = 0;

			// total tax
			$tax_b2b_sales_today = 0;
			$tax_b2b_sales_seven_days = 0;
			$tax_b2b_sales_thirtyone_days = 0;

			// nr of orders
			$number_b2b_sales_today = 0;
			$number_b2b_sales_seven_days = 0;
			$number_b2b_sales_thirtyone_days = 0;

			// nr of unique customers
			$customers_b2b_sales_today = 0;
			$customers_b2b_sales_seven_days = 0;
			$customers_b2b_sales_thirtyone_days = 0;

			//calculate today
			$array_of_customers_ids = array();
			foreach ($orders_today as $order){
				$order_user_id = $order->get_customer_id();

				if ($plugin_status === 'b2b'){
					$total_b2b_sales_today += $order->get_total();
					$tax_b2b_sales_today += $order->get_total_tax();
					$number_b2b_sales_today++;
					array_push($array_of_customers_ids, $order_user_id);

				} else {
					if (get_user_meta($order_user_id,'b2bking_b2buser', true) === 'yes'){
						$total_b2b_sales_today += $order->get_total();
						$tax_b2b_sales_today += $order->get_total_tax();
						$number_b2b_sales_today++;
						array_push($array_of_customers_ids, $order_user_id);
					}
				}
			}
			$customers_b2b_sales_today=count(array_unique($array_of_customers_ids));

			//calculate seven days
			$array_of_customers_ids = array();
			foreach ($orders_seven_days as $order){
				$order_user_id = $order->get_customer_id();

				if ($plugin_status === 'b2b'){
					$total_b2b_sales_seven_days += $order->get_total();
					$tax_b2b_sales_seven_days += $order->get_total_tax();
					$number_b2b_sales_seven_days++;
					array_push($array_of_customers_ids, $order_user_id);
				} else {
					// check user
					if (get_user_meta($order_user_id,'b2bking_b2buser', true) === 'yes'){
						$total_b2b_sales_seven_days += $order->get_total();
						$tax_b2b_sales_seven_days += $order->get_total_tax();
						$number_b2b_sales_seven_days++;
						array_push($array_of_customers_ids, $order_user_id);
					}
				}
			}
			$customers_b2b_sales_seven_days=count(array_unique($array_of_customers_ids));

			//calculate thirtyone days
			$array_of_customers_ids = array();
			foreach ($orders_thirtyone_days as $order){
				$order_user_id = $order->get_customer_id();

				if ($plugin_status === 'b2b'){
					$total_b2b_sales_thirtyone_days += $order->get_total();
					$tax_b2b_sales_thirtyone_days += $order->get_total_tax();
					$number_b2b_sales_thirtyone_days++;
					array_push($array_of_customers_ids, $order_user_id);
				} else {
					if (get_user_meta($order_user_id,'b2bking_b2buser', true) === 'yes'){
						$total_b2b_sales_thirtyone_days += $order->get_total();
						$tax_b2b_sales_thirtyone_days += $order->get_total_tax();
						$number_b2b_sales_thirtyone_days++;
						array_push($array_of_customers_ids, $order_user_id);
					}
				}
			}
			$customers_b2b_sales_thirtyone_days=count(array_unique($array_of_customers_ids));

			// get each day in the past 31 days and form an array with day and total sales
			$i=1;
			$days_sales_array = array();
			$days_sales_b2c_array = array();
			$hours_sales_b2c_array = $hours_sales_array = array(
				'00' => 0,
				'01' => 0,
				'02' => 0,
				'03' => 0,
				'04' => 0,
				'05' => 0,
				'06' => 0,
				'07' => 0,
				'08' => 0,
				'09' => 0,
				'10' => 0,
				'11' => 0,
				'12' => 0,
				'13' => 0,
				'14' => 0,
				'15' => 0,
				'16' => 0,
				'17' => 0,
				'18' => 0,
				'19' => 0,
				'20' => 0,
				'21' => 0,
				'22' => 0,
				'23' => 0,
			);

			while ($i<32){
				$date_from = $date_to = date('Y-m-d', strtotime('-'.($i-1).' days'));

				$args = array(
					'status' => apply_filters('b2bking_reports_statuses', array('wc-on-hold','wc-pending','wc-processing', 'wc-completed') ),
				    'date_created' => $date_from,
				    'limit' => -1,
				    'type' => 'shop_order',
				);
				$orders_day = wc_get_orders( $args );

				//calculate totals
				$sales_total = 0;
				$sales_total_b2c = 0;
				foreach ($orders_day as $order){
					$order_user_id = $order->get_customer_id();

					if ($plugin_status === 'b2b'){
						$sales_total += $order->get_total();
					} else {
						// check user
						if (get_user_meta($order_user_id,'b2bking_b2buser', true) === 'yes'){
							$sales_total += $order->get_total();
						} else {
							$sales_total_b2c += $order->get_total();
						}
					}
				}

				// if first day, get this by hour
				if ($i===1){
					$date_to = date('Y-m-d H:i:s');
					$date_from = date('Y-m-d');

					$args = array(
						'status' => apply_filters('b2bking_reports_statuses', array('wc-on-hold','wc-pending','wc-processing', 'wc-completed') ),
					    'date_created' => '>='.$date_from,
					    'limit' => -1,
					    'type' => 'shop_order',

					);
					$orders_seven_days = wc_get_orders( $args );

					foreach ($orders_day as $order){
						// get hour of the order
						$date = $order->get_date_created();
						$hour = explode(':',explode('T', $date)[1])[0];

						if ($plugin_status === 'b2b'){
							$hours_sales_array[$hour] += $order->get_total();
						} else {
							// check user
							if (get_user_meta($order_user_id,'b2bking_b2buser', true) === 'yes'){
								$hours_sales_array[$hour] += $order->get_total();
							} else {
								$hours_sales_b2c_array[$hour] += $order->get_total();
							}
						}
					}
				}

				array_push ($days_sales_array, $sales_total);
				array_push ($days_sales_b2c_array, $sales_total_b2c);
				$i++;
			}

			$data['days_sales_array'] = $days_sales_array;
			$data['days_sales_b2c_array'] = $days_sales_b2c_array;
			$data['hours_sales_array'] = $hours_sales_array;
			$data['hours_sales_b2c_array'] = $hours_sales_b2c_array;
			$data['total_b2b_sales_today'] = $total_b2b_sales_today;
			$data['total_b2b_sales_seven_days'] = $total_b2b_sales_seven_days;
			$data['total_b2b_sales_thirtyone_days'] = $total_b2b_sales_thirtyone_days;
			$data['number_b2b_sales_today'] = $number_b2b_sales_today;
			$data['number_b2b_sales_seven_days'] = $number_b2b_sales_seven_days;
			$data['number_b2b_sales_thirtyone_days'] = $number_b2b_sales_thirtyone_days;
			$data['customers_b2b_sales_today'] = $customers_b2b_sales_today;
			$data['customers_b2b_sales_seven_days'] = $customers_b2b_sales_seven_days;
			$data['customers_b2b_sales_thirtyone_days'] = $customers_b2b_sales_thirtyone_days;
			$data['tax_b2b_sales_today'] = $tax_b2b_sales_today;
			$data['tax_b2b_sales_seven_days'] = $tax_b2b_sales_seven_days;
			$data['tax_b2b_sales_thirtyone_days'] = $tax_b2b_sales_thirtyone_days;

			set_transient('webwizards_dashboard_data_cache', $data);
			set_transient('webwizards_dashboard_data_cache_time', time());
		}
		
		
		return $data;
	}


	function b2bking_admin_order_meta_billing( $address, $raw_address, $order ){
		$custom_fields = get_posts([
			    		'post_type' => 'b2bking_custom_field',
			    	  	'post_status' => 'publish',
			    	  	'numberposts' => -1,
			    	  	'meta_key' => 'b2bking_custom_field_sort_number',
		    	  	    'orderby' => 'meta_value_num',
		    	  	    'order' => 'ASC',
			    	  	'meta_query'=> array(
			    	  		'relation' => 'AND',
			                array(
		                        'key' => 'b2bking_custom_field_status',
		                        'value' => 1
			                ),
			                array(
		                        'key' => 'b2bking_custom_field_add_to_billing',
		                        'value' => 1
			                ),
		            	)
			    	]);

		foreach ($custom_fields as $custom_field){
			$label = get_post_meta($custom_field->ID,'b2bking_custom_field_field_label', true);
			$fieldvalue = get_post_meta($order->get_id(), 'b2bking_custom_field_'.$custom_field->ID, true);
			if (!empty($fieldvalue)){
				$address .= '<br>'.esc_html($label).': '.esc_html($fieldvalue);
			}
		}
  
	    return $address;
	}

	function load_global_admin_notice_resource(){
		wp_enqueue_script( 'b2bking_global_admin_notice_script', plugins_url('assets/js/adminnotice.js', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION, $in_footer =true);

		// Send data to JS
		$data_js = array(
			'security'  => wp_create_nonce( 'b2bking_notice_security_nonce' ),
		);
		wp_localize_script( 'b2bking_global_admin_notice_script', 'b2bking_notice', $data_js );
		
	}

	function load_global_admin_resources( $hook ){

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');

		wp_enqueue_style('select2', plugins_url('../includes/assets/lib/select2/select2.min.css', __FILE__) );
		wp_enqueue_script('select2', plugins_url('../includes/assets/lib/select2/select2.min.js', __FILE__), array('jquery') );

		wp_enqueue_style ( 'b2bking_global_admin_style', plugins_url('assets/css/adminglobal.css', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION);
		wp_enqueue_script( 'b2bking_global_admin_script', plugins_url('assets/js/adminglobal.js', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION, $in_footer =true);

		if ( class_exists( 'woocommerce' ) ) {
			$symbol = get_woocommerce_currency_symbol();
			$offerslink = apply_filters('b2bking_offers_link',  rtrim(get_permalink( wc_get_page_id( 'myaccount' ) ),'/').'/'.get_option('b2bking_offers_endpoint_setting','offers'));
		} else {
			$offerslink = $symbol = '';
		}

		if (isset($_GET['post_type'])){
			$type = sanitize_text_field($_GET['post_type']);
		} else {
			$type = '';
		}

		$post_type = '';
		if (isset($_GET['post'])){
			$post_type = get_post_type(sanitize_text_field($_GET['post'] ));
		}

		if ($hook === 'b2bking_page_b2bking_dashboard' || $hook === 'b2bking_page_b2bking_reports'){
			wp_enqueue_style( 'b2bking_admin_dashboard', plugins_url('assets/dashboard/cssjs/dashboardstyle.min.css', __FILE__));
		}

		if (substr( $hook, 0, 7 ) === "b2bking" || substr( $hook, 0, 18 ) === "admin_page_b2bking" || substr($type, 0, 7) === 'b2bking' || substr($post_type, 0, 7) === 'b2bking' || $hook === 'toplevel_page_b2bkingcore'){
			wp_enqueue_script('dataTables', plugins_url('../includes/assets/lib/dataTables/jquery.dataTables.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
			wp_enqueue_style( 'dataTables', plugins_url('../includes/assets/lib/dataTables/jquery.dataTables.min.css', __FILE__));

			wp_enqueue_script('dataTablesButtons', plugins_url('../includes/assets/lib/dataTables/dataTables.buttons.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
			wp_enqueue_script('dataTablesButtonsHTML', plugins_url('../includes/assets/lib/dataTables/buttons.html5.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);

			wp_enqueue_script('dataTablesButtonsPrint', plugins_url('../includes/assets/lib/dataTables/buttons.print.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
			wp_enqueue_script('dataTablesButtonsColvis', plugins_url('../includes/assets/lib/dataTables/buttons.colVis.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);

			wp_enqueue_script('jszip', plugins_url('../includes/assets/lib/dataTables/jszip.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);

			wp_enqueue_script('pdfmake', plugins_url('../includes/assets/lib/pdfmake/pdfmake.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
			wp_enqueue_script('vfsfonts', plugins_url('../includes/assets/lib/pdfmake/vfs_fonts.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);

			// Dashboard
			wp_enqueue_style ('chartist', plugins_url('assets/dashboard/chartist/chartist.min.css', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION);
			wp_enqueue_script('chartist', plugins_url('assets/dashboard/chartist/chartist.min.js', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION, $in_footer =true);
			wp_enqueue_script('chartist-plugin-tooltip', plugins_url('assets/dashboard/chartist/chartist-plugin-tooltip.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
		}

		$do_not_load = false;
		// do not load on backend order page to solve some conflicts
		if ($hook === 'post.php' && isset($_GET['post'])){
			if (get_post_type($_GET['post']) === 'shop_order'){
				// do not load
				$do_not_load = true;
			}
		}
		if (isset($_GET['page'])){
			if ($_GET['page'] === 'wc-orders'){
				$do_not_load = true;
			}
		}
		if (!$do_not_load){
			wp_enqueue_script('sweetalert2', plugins_url('../includes/assets/lib/sweetalert/sweetalert2.all.min.js', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION );
		}

		wp_enqueue_script('popper', plugins_url('../includes/assets/lib/popper/popper.min.js', __FILE__) );
		wp_enqueue_script('tippy', plugins_url('../includes/assets/lib/popper/tippy.min.js', __FILE__) );


		$pageslug = '';
		if (isset($_GET['page'])){
			$pageslug = sanitize_text_field($_GET['page']);
		} else if (isset($_GET['post_type'])){
			$pageslug = sanitize_text_field($_GET['post_type']);
		} else if (isset($_GET['post'])){
			$pageslug = sanitize_text_field($_GET['post']);
		}

		// Send data to JS
		$translation_array = array(
				'admin_url' => get_admin_url(),
				'pageslug'	=> $pageslug,
				'security'  => wp_create_nonce( 'b2bking_security_nonce' ),
			    'currency_symbol' => $symbol,
			    'yes_confirm' => esc_html__('Yes, I confirm','b2bking'),
			    'are_you_sure_approve' => esc_html__('Are you sure you want to approve this user?', 'b2bking'),
			    'are_you_sure_reject' => esc_html__('Are you sure you want to REJECT and DELETE this user? This is irreversible.', 'b2bking'),
			    'are_you_sure_set_users' => esc_html__('Are you sure you want to move ALL users to this group?', 'b2bking'),
			    'are_you_sure_deactivate' => esc_html__('Are you sure you want to DEACTIVATE this user? The user will no longer be approved and they will be unable to login.', 'b2bking'),
			    'are_you_sure_set_categories' => esc_html__('Are you sure you want to set ALL categories?', 'b2bking'),
			    'are_you_sure_set_subaccounts' => esc_html__('Are you sure you want to set these users as subaccounts of the parent?', 'b2bking'),
			    'are_you_sure_set_subaccounts_regular' => esc_html__('Are you sure you want to set these users as regular accounts and no longer subaccounts?', 'b2bking'),
			    'are_you_sure_update_user' => esc_html__('Are you sure you want to update this user\'s data?','b2bking'),
			    'user_has_been_updated' => esc_html__('User data has been updated','b2bking'),
			    'user_has_been_updated_vat_failed' => esc_html__('VAT VIES validation failed. Please check the VAT number you entered, or disable VIES validation in B2BKing > Registration Fields > VAT. Other fields have been successfully updated.','b2bking'),
			    'categories_have_been_set' => esc_html__('All categories have been set successfully.','b2bking'),
			    'subaccounts_have_been_set' => esc_html__('All subaccounts have been set','b2bking'),
			    'feedback_sent' => esc_html__('Thank you. The feedback was sent successfully.', 'b2bking'),
			    'username_already_list' => esc_html__('Username already in the list!', 'b2bking'),
			    'add_user' => esc_html__('Add user', 'b2bking'),
			    'cart_total_quantity' => esc_html__('Cart Total Quantity', 'b2bking'),
			    'cart_total_value' => esc_html__('Cart Total Value', 'b2bking'),
			    'category_product_quantity' => esc_html__('Category Product Quantity', 'b2bking'),
			    'category_product_value' => esc_html__('Category Product Value', 'b2bking'),
			    'product_quantity' => esc_html__('Product Quantity', 'b2bking'),
			    'product_value' => esc_html__('Product Value', 'b2bking'),
			    'greater' => esc_html__('greater (>)', 'b2bking'),
			    'equal' => esc_html__('equal (=)', 'b2bking'),
			    'smaller' => esc_html__('smaller (<)', 'b2bking'),
			    'delete' => esc_html__('Delete', 'b2bking'),
			    'enter_quantity_value' => esc_html__('Enter the quantity/value', 'b2bking'),
			    'add_condition' => esc_html__('Add Condition' ,'b2bking'),
			    'conditions_apply_cumulatively' => esc_html__('Conditions must apply cumulatively.' ,'b2bking'),
			    'conditions_multiselect' => esc_html__('Each category must meet all category conditions + cart total conditions. Each product must meet all product conditions + cart total conditions.' ,'b2bking'),
			    'purchase_lists_language_option' => get_option('b2bking_purchase_lists_language_setting','english'),
			    'replace_product_selector' => intval(get_option( 'b2bking_replace_product_selector_setting', 0 )),
			    'b2bking_customers_panel_ajax_setting' => intval(get_option('b2bking_customers_panel_ajax_setting', 0)),
			    'b2bking_plugin_status_setting' => get_option( 'b2bking_plugin_status_setting', 'b2b' ),
			    'min_quantity_text' => esc_html__('Min. Quantity','b2bking'),
			    'final_price_text' => esc_html__('Final Price', 'b2bking'),
			    'label_text' => esc_html__('Label', 'b2bking'),
			    'text_text' => esc_html__('Text', 'b2bking'),
			    'datatables_folder' => plugins_url('../includes/assets/lib/dataTables/i18n/', __FILE__),
			    'print' => esc_html__('Print', 'b2bking'), 
			    'edit_columns' => esc_html__('Edit Columns', 'b2bking'), 
			    'purchase_lists_language_option' => get_option('b2bking_purchase_lists_language_setting','english'),
			    'group_rules_link' => admin_url( 'edit.php?post_type=b2bking_grule'),
			    'dynamic_rules_link' => admin_url( 'edit.php?post_type=b2bking_rule'),
			    'conversations_link' => admin_url( 'edit.php?post_type=b2bking_conversation'),
			    'offers_link' => admin_url( 'edit.php?post_type=b2bking_offer'),
			    'roles_link' => admin_url( 'edit.php?post_type=b2bking_custom_role'),
			    'fields_link' => admin_url( 'edit.php?post_type=b2bking_custom_field'),
			    'b2bgroups_link' => admin_url( 'edit.php?post_type=b2bking_group'),
			    'goback_text' => esc_html__('Go back', 'b2bking'),
			    'new_offer_link'	=> admin_url('/post-new.php?post_type=b2bking_offer'),
			    'group_rules_text' => esc_html__('Set up group rules (optional)', 'b2bking'),
			    'quote_fields_link'	=> admin_url('/edit.php?post_type=b2bking_quote_field'),
			    'view_quote_fields' => esc_html__('Go to Quote Fields', 'b2bking'),
			    'offer_details' => esc_html__('Offer details', 'b2bking'),
			    'offer_custom_text' => esc_html__('Additional info', 'b2bking'),
			    'item_name' => esc_html__('Item', 'b2bking'),
			    'item_quantity' => esc_html__('Quantity', 'b2bking'),
			    'unit_price' => esc_html__('Unit price', 'b2bking'),
			    'item_subtotal' => esc_html__('Subtotal', 'b2bking'),
			    'offer_total' => esc_html__('Total', 'b2bking'),
			    'offers_logo' => get_option('b2bking_offers_logo_setting',''),
			    'offers_images_setting' => get_option('b2bking_offers_product_image_setting', 0),
			    'offers_endpoint_link' => $offerslink,
			    'offer_go_to'	=> esc_html__('-> Go to Offers', 'b2bking'),
			    'email_offer_confirm' => esc_html__('This offer will be emailed to ALL users that have visibility. That includes all groups you selected, all users, and all email addresses entered. Make sure to save the offer first if you made changes to it! Are you sure you want to proceed?', 'b2bking'),
			    'email_has_been_sent' => esc_html__('The offer has been emailed successfully.', 'b2bking'),
			    'value_conditions_error' => esc_html__('Value conditions (Cart Total Value, Product Value, Category Value) are not compatible with the "Discount becomes sale price" checkbox. Please remove value conditions, or uncheck the checkbox.','b2bking'),
			    'download_go_to_file' => intval(apply_filters('b2bking_download_file_go_to', 0)),
			    'pdf_download_lang' => apply_filters('b2bking_pdf_downloads_language', 'english'),
			    'caches_have_cleared' => esc_html__('All caches have been cleared', 'b2bking'),
			    'caches_are_clearing' => esc_html__('Caches are clearing...', 'b2bking'),
			    'loaderurl' => plugins_url('../includes/assets/images/loaderpagegold5.svg', __FILE__),
			    'ajax_pages_load' => apply_filters('b2bking_ajax_pages_load', 'enabled'), // disable ajax backend page load via snippets
			    'dashboardstyleurl' => plugins_url('assets/dashboard/cssjs/dashboardstyle.min.css', __FILE__),
			    'inlineeditpostjsurl' => admin_url('js/inline-edit-post.js'),
			    'commonjsurl' => plugins_url('assets/js/common.js', __FILE__),
			    'groupspage' => admin_url( 'admin.php?page=b2bking_groups'),
			    'saving'	=> esc_html__('Saving...','b2bking'),
			    'settings_saved' => esc_html__('Settings Saved','b2bking'),
			    'users_have_been_moved' => esc_html__('All users have been moved to your chosen group','b2bking'),
			    'registration_form_shortcodes_text' => esc_html__('Registration Form Shortcodes','b2bking'),
			    'bulkorder_form_shortcodes_text' => esc_html__('Order Form Shortcodes','b2bking'),
			    'sort_order_help_tip' => esc_html__('Drag & drop fields to arrange them in the order you would like them displayed on the frontend.','b2bking'),
			    'form_preview_help_tip' => esc_html__('This is a preview of what the form may look like on the frontend.','b2bking'),
			    'click_to_copy' => esc_html__('Click to Copy','b2bking'),
			    'quick_edit' => esc_html__('Quick Edit','b2bking'),
			    'save_edit' => esc_html__('Save Edit','b2bking'),
			    'copied' => esc_html__('Copied!','b2bking'),
			    'select_all' => esc_html__('Select All','b2bking'),
			    'unselect' => esc_html__('Unselect','b2bking'),
			    'enabled' => esc_html__('Enabled','b2bking'),
			    'disabled' => esc_html__('Disabled','b2bking'),
			    'select_export_format' => esc_html__('Choose export format','b2bking'),
			    'report_downloaded' => esc_html__('Report downloaded','b2bking'),
			    'please_select_an_option' => esc_html__('Please select an option!','b2bking'),
			);

		// generate HTML for toolbar
		ob_start();
		$active_number = get_option('b2bking_posts_per_page_backend_setting', 20);
		?>
		<div class="b2bking_post_toolbar">
			<div class="b2bking_toolbar_selected_count b2bking_toolbar_selected_inactive">
				<span class="b2bking_toolbar_selected_count_number">3</span>
				<span class="b2bking_toolbar_selected_count_text"><?php esc_html_e('selected','b2bking');?></span>
			</div>
			<div id="b2bking_toolbar_settings_tab" class="b2bking_toolbar_settings_tab_inactive">
				<ul class="b2bking_toolbar_settings_list">
					<li><span class="b2bking_show_per_page"><?php esc_html_e('SHOW','b2bking');?></span></li>
					<li class="b2bking_show_per_page_number <?php if ($active_number == 20){echo 'b2bking_active_page_number';}?>">20</li>
					<li class="b2bking_show_per_page_number <?php if ($active_number == 50){echo 'b2bking_active_page_number';}?>">50</li>
					<li class="b2bking_show_per_page_number <?php if ($active_number == 100){echo 'b2bking_active_page_number';}?>">100</li>
				</ul>
			</div>
			<div class="b2bking_toolbar_select b2bking_select">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 20 20">
				  <path fill="#A0A0A0" d="M17.08 4.69a1.875 1.875 0 0 1 1.253 1.768v8.334a3.542 3.542 0 0 1-3.541 3.541H6.458A1.875 1.875 0 0 1 4.69 17.08l1.748.003h8.355a2.291 2.291 0 0 0 2.291-2.291V6.458l-.003-.042V4.689Zm-2.708-3.023a1.875 1.875 0 0 1 1.875 1.875v10.83a1.875 1.875 0 0 1-1.875 1.875H3.542a1.875 1.875 0 0 1-1.875-1.874V3.542a1.875 1.875 0 0 1 1.875-1.875h10.83Zm-3.147 4.558-3.242 3.24-.816-1.09a.625.625 0 1 0-1 .75l1.25 1.667a.626.626 0 0 0 .941.066l3.75-3.75a.625.625 0 0 0-.883-.883Z"/>
				</svg>
				<span class="b2bking_toolbar_select_text"><?php esc_html_e('Select All','b2bking');?></span>
			</div>
			<div class="b2bking_toolbar_enable_disable b2bking_toolbar_enable b2bking_toolbar_inactive">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
				  <path fill="#A0A0A0" d="M17 7H7a5 5 0 1 0 0 10h10a5 5 0 1 0 0-10Zm0 8a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
				</svg>
				<span class="b2bking_toolbar_select_text"><?php esc_html_e('Enable','b2bking');?></span>
			</div>
			<div class="b2bking_toolbar_enable_disable b2bking_toolbar_disable b2bking_toolbar_inactive">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
				  <path fill="#A0A0A0" d="M17 6H7c-3.31 0-6 2.69-6 6s2.69 6 6 6h10c3.31 0 6-2.69 6-6s-2.69-6-6-6Zm0 10H7c-2.21 0-4-1.79-4-4s1.79-4 4-4h10c2.21 0 4 1.79 4 4s-1.79 4-4 4ZM7 9c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3Z"/>
				</svg>
				<span class="b2bking_toolbar_select_text"><?php esc_html_e('Disable','b2bking');?></span>
			</div>
			<div id="b2bking_toolbar_settings">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 20 20" style="pointer-events: none">
				  <g clip-path="url(#a)">
				    <path stroke="#AEAEAE" stroke-linecap="square" stroke-linejoin="round" d="m7.925.667-.114.582-.439 2.131A7.36 7.36 0 0 0 5.46 4.477L3.316 3.76l-.576-.177-.299.513L.965 6.564l-.298.516.437.383 1.659 1.41c-.06.37-.138.734-.138 1.122 0 .388.078.753.138 1.122l-1.659 1.41-.437.382.298.515 1.476 2.467.299.516.576-.18 2.144-.716c.575.45 1.21.829 1.912 1.097l.439 2.13.114.583h4.148l.116-.582.438-2.131a7.368 7.368 0 0 0 1.912-1.097l2.144.716.576.18.3-.515 1.474-2.468.3-.515-.438-.382-1.659-1.411c.061-.37.137-.733.137-1.123 0-.386-.076-.752-.137-1.121l1.659-1.41.438-.383-.3-.515-1.474-2.467-.3-.514-.576.178-2.144.716a7.361 7.361 0 0 0-1.912-1.097l-.438-2.13-.116-.583H7.925Z" clip-rule="evenodd"/>
				    <path stroke="#AEAEAE" stroke-linecap="square" stroke-linejoin="round" d="M12.667 9.993a2.667 2.667 0 1 1-5.334 0 2.667 2.667 0 0 1 5.334 0Z" clip-rule="evenodd"/>
				  </g>
				  <defs>
				    <clipPath id="a">
				      <path fill="#fff" d="M0 0h20v20H0z"/>
				    </clipPath>
				  </defs>
				</svg>
			</div>
			

		</div>
		<?php
		$toolbar = ob_get_clean();
		$translation_array['toolbarhtml'] = $toolbar;
		// end HTML for toolbar

		// generate HTML for upgrade modal
		ob_start();

		?>
		<div id="b2bkingcore_pro_upgrade_modal_container">
			<div id="b2bkingcore_pro_upgrade_modal">
				<div id="b2bkingcore_pro_upgrade_modal_image_container">
					<img class="b2bkingcore_pro_upgrade_modal_img" src="<?php echo esc_attr(plugins_url('../includes/assets/images/upgrade-pro-optimized.png', __FILE__));?>">
				</div>
				<div id="b2bkingcore_pro_upgrade_header">
					<h2>
						<?php esc_html_e('Unlock 137+ Pro Features','b2bking');?>
					</h2>
				</div>
				<div class="b2bkingcore_upgrade_header_description">
					<?php esc_html_e('with a ','b2bking');?><strong><?php esc_html_e('Premium License','b2bking');?></strong>
				</div>
				<div class="b2bkingcore_upgrade_header_small_description">
					<?php esc_html_e('Start growing your business with B2BKing’s powerful features for B2B buyers. Join 14,000+ active stores. 100% money back guarantee.','b2bking');?> 
				</div>
				<div class="b2bkingcore_modal_bottom_half">
					<a href="https://woocommerce-b2b-plugin.com/pricing" target="_blank"><button type="button" id="b2bkingcore_modal_upgrade_now_button"><?php esc_html_e('UPGRADE NOW','b2bking');?></button></a>
				</div>
			</div>
		</div>
		<?php


		$upgrade = ob_get_clean();
		$translation_array['upgradehtml'] = $upgrade;

		// upgrade html end

		// generate HTML for searchbar
		ob_start();

		?>
		<div class="b2bking_post_searchbar">
			<input type="text" class="b2bking_searchbar_input" placeholder="<?php esc_html_e('Search items...','b2bking');?>" value="<?php 
			if (isset($_GET['s'])){
				echo esc_html($_GET['s']);
			}

		?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="none" viewBox="0 0 19 19">
			  <path fill="#A4A4A4" d="m17.219 16.38-4.484-4.485a6.542 6.542 0 1 0-.84.84l4.484 4.484.84-.84ZM2.375 7.718a5.344 5.344 0 1 1 10.688 0 5.344 5.344 0 0 1-10.688 0Z"/>
			</svg>
		</div>
		<?php
		if (isset($_GET['s'])){
			if (!empty($_GET['s'])){
				// show clear button
				?>
				<div class="b2bking_post_searchbar_clear">
					<span class="b2bking_post_searchbar_clear_text"><?php esc_html_e('Clear','b2bking');?></span>
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 20 20">
					  <path fill="#919191" d="M10 1.667A8.326 8.326 0 0 1 18.333 10 8.326 8.326 0 0 1 10 18.333 8.326 8.326 0 0 1 1.667 10 8.326 8.326 0 0 1 10 1.667Zm2.992 4.166L10 8.825 7.008 5.833 5.833 7.008 8.825 10l-2.992 2.992 1.175 1.175L10 11.175l2.992 2.992 1.175-1.175L11.175 10l2.992-2.992-1.175-1.175Z"/>
					</svg>
				</div>
				<?php
			}
		}

		$searchbar = ob_get_clean();
		$translation_array['searchbarhtml'] = $searchbar;
		// end HTML for toolbar	

		if (isset($_GET['post'])){
			$translation_array['current_post_type'] = get_post_type(sanitize_text_field($_GET['post'] ));
		}
		if (isset($_GET['post'])){
			$translation_array['current_action'] = sanitize_text_field($_GET['action'] );
		}

		wp_localize_script( 'b2bking_global_admin_script', 'b2bking', $translation_array );

		if ($hook === 'b2bking_page_b2bking_tools'){
			wp_enqueue_script('semantic', plugins_url('../includes/assets/lib/semantic/semantic.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
			wp_enqueue_style( 'semantic', plugins_url('../includes/assets/lib/semantic/semantic.min.css', __FILE__));
			wp_enqueue_style ( 'b2bking_admin_style', plugins_url('assets/css/adminstyle.css', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION);
			wp_enqueue_script( 'b2bking_admin_script', plugins_url('assets/js/admin.js', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION, $in_footer =true);
		}

		if (substr( $hook, 0, 7 ) === "b2bking" || substr( $hook, 0, 18 ) === "admin_page_b2bking" || substr($type, 0, 7) === 'b2bking' || substr($post_type, 0, 7) === 'b2bking' || $hook === 'toplevel_page_b2bkingcore'){


			$data = self::b2bking_get_dashboard_data();	

			// Send data to JS
			$translation_array = array(
				'days_sales_b2b' => apply_filters('b2bking_dashboard_days_sales_b2b', $data['days_sales_array']),
				'days_sales_b2c' => apply_filters('b2bking_dashboard_days_sales_b2c', $data['days_sales_b2c_array']),
				'hours_sales_b2b' => array_values($data['hours_sales_array']),
				'hours_sales_b2c' => array_values($data['hours_sales_b2c_array']),
				'b2bking_demo' => apply_filters('b2bking_is_dashboard_demo', 0),
				'currency_symbol' => get_woocommerce_currency_symbol(),
			);

			wp_localize_script( 'b2bking_global_admin_script', 'b2bking_dashboard', $translation_array );

		}

	}
	
	function load_admin_resources($hook) {
		// Load only on this specific plugin admin
		if($hook != 'toplevel_page_b2bkingcore') {
			return;
		}
		
		wp_enqueue_script('jquery');

		wp_enqueue_script('semantic', plugins_url('../includes/assets/lib/semantic/semantic.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
		wp_enqueue_style( 'semantic', plugins_url('../includes/assets/lib/semantic/semantic.min.css', __FILE__));

		wp_enqueue_style ( 'b2bking_admin_style', plugins_url('assets/css/adminstyle.css', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION);
		wp_enqueue_script( 'b2bking_admin_script', plugins_url('assets/js/admin.js', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION, $in_footer =true);

		wp_enqueue_style( 'b2bking_style', plugins_url('../includes/assets/css/style.css', __FILE__)); 

	}

	function load_customers_resources($hook){
		// Load only in the customers page
		if($hook != 'b2bking_page_b2bking_customers') {
			return;
		}

		wp_enqueue_script('dataTables', plugins_url('../includes/assets/lib/dataTables/jquery.dataTables.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
		wp_enqueue_style( 'dataTables', plugins_url('../includes/assets/lib/dataTables/jquery.dataTables.min.css', __FILE__));

		wp_enqueue_script('dataTablesButtons', plugins_url('../includes/assets/lib/dataTables/dataTables.buttons.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
		wp_enqueue_script('dataTablesButtonsHTML', plugins_url('../includes/assets/lib/dataTables/buttons.html5.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
		wp_enqueue_script('dataTablesButtonsPrint', plugins_url('../includes/assets/lib/dataTables/buttons.print.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
		wp_enqueue_script('dataTablesButtonsColvis', plugins_url('../includes/assets/lib/dataTables/buttons.colVis.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);

		wp_enqueue_script('jszip', plugins_url('../includes/assets/lib/dataTables/jszip.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
		wp_enqueue_script('pdfmake', plugins_url('../includes/assets/lib/pdfmake/pdfmake.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
		wp_enqueue_script('vfsfonts', plugins_url('../includes/assets/lib/pdfmake/vfs_fonts.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);
	}

	function load_dashboard_resources($hook){
		// Load only in the customers page
		if($hook !== 'b2bking_page_b2bking_dashboard' && $hook !== 'b2bking_page_b2bking_conversations' && $hook !== 'b2bking_page_b2bking_offers' && $hook !== 'b2bking_page_b2bking_fields' && $hook !== 'b2bking_page_b2bking_tools') {
			return;
		}

		wp_enqueue_script('jquery');

		wp_enqueue_style( 'chartist', plugins_url('assets/dashboard/chartist/chartist.min.css', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION);
		wp_enqueue_script('chartist', plugins_url('assets/dashboard/chartist/chartist.min.js', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION, $in_footer =true);
		wp_enqueue_script('chartist-plugin-tooltip', plugins_url('assets/dashboard/chartist/chartist-plugin-tooltip.min.js', __FILE__), $deps = array(), $ver = false, $in_footer =true);

		wp_enqueue_style( 'b2bking_admin_dashboard', plugins_url('assets/dashboard/cssjs/dashboardstyle.min.css', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION);
		wp_enqueue_script('b2bking_admin_dashboard', plugins_url('assets/dashboard/cssjs/dashboard.js', __FILE__), $deps = array(), $ver = B2BKINGCORE_VERSION, $in_footer =true);
	}


	function b2bking_plugin_dependencies() {
		if ( ! class_exists( 'woocommerce' ) ) {
			// if notice has not already been dismissed once by the current user
			if (intval(get_user_meta(get_current_user_id(),'b2bking_dismiss_activate_woocommerce_notice', true)) !== 1){
	    		?>
	    	    <div class="b2bking_activate_woocommerce_notice notice notice-warning is-dismissible">
	    	        <p><?php esc_html_e( 'Warning: The plugin "B2BKing" requires WooCommerce to be installed and activated.', 'b2bking' ); ?></p>
	    	    </div>
    	    	<?php
    	    }
		}
	}

	public function b2bking_onboarding_notification() {
		if ( class_exists( 'woocommerce' ) && defined('B2BKINGCORE_DIR') ) {
			// if notice has not already been dismissed once by the current user
			if ( 1 !== intval( get_user_meta( get_current_user_id(), 'b2bking_dismiss_onboarding_notice', true ) ) ) {
				?>
				<div class="b2bking_dismiss_onboarding_notice notice notice-info is-dismissible">
					<p><?php echo esc_html__( 'B2BKing is ready to go! ', 'b2bking' ) . esc_html__( 'Go to ', 'b2bking' ) . '<a id="b2bking_dismiss_onboarding_link" href="' . esc_attr( admin_url( 'admin.php?page=b2bking' ) ) . '">' . esc_html__( 'Settings', 'b2bking' ) . '</a>' . esc_html__( ' to configure the plugin. ','b2bking' ).' '.esc_html__( 'Here is the ','b2bking').'<a href="https://woocommerce-b2b-plugin.com/docs/set-up-woocommerce-wholesale-store-step-by-step-guide/">'.esc_html__('Initial Configuration', 'b2bking').'</a> '.esc_html__('guide.', 'b2bking');?></p>
				</div>
				<?php
			}
		}
	}


		function b2bking_quotefields_howto() {
			global $current_screen;
		    if( 'b2bking_quote_field' != $current_screen->post_type ){
			    return;
		    }

			// if notice has not already been dismissed once by the current user
			if (intval(get_user_meta(get_current_user_id(),'b2bking_dismiss_quotefields_howto_notice', true)) !== 1){
	    		?>
	    	    <div class="b2bking_quotefields_howto_notice notice notice-info is-dismissible">
	    	        <p><?php esc_html_e( 'There are 3 default quote fields the plugin uses: Name, Email, Message. Here you can add additional, custom fields, to be shown beside the default ones.', 'b2bking' ); ?></p>
	    	    </div>
		    	<?php
		    }
			
		}

		function b2bking_groups_howto() {
			global $current_screen;
		    if( 'b2bking_group' != $current_screen->post_type ){
			    return;
		    }

			// if notice has not already been dismissed once by the current user
			if (intval(get_user_meta(get_current_user_id(),'b2bking_dismiss_groups_howto_notice', true)) !== 1){
	    		?>
	    	    <div class="b2bking_groups_howto_notice notice notice-info is-dismissible">
	    	        <p><?php esc_html_e( 'B2B groups help you organize and manage your business customers. Create, edit, or delete groups based on your store\'s needs. To add a user to a group, go to the user\'s profile and scroll down to \'B2B User Settings\'.', 'b2bking' ); ?></p>
	    	    </div>
		    	<?php
		    }
		}

		function b2bking_conversations_howto() {
			global $current_screen;
		    if( 'b2bking_conversation' != $current_screen->post_type ){
			    return;
		    }

			// if notice has not already been dismissed once by the current user
			if (intval(get_user_meta(get_current_user_id(),'b2bking_dismiss_conversations_howto_notice', true)) !== 1){
	    		?>
	    	    <div class="b2bking_conversations_howto_notice notice notice-info is-dismissible">
	    	        <p><?php esc_html_e( 'Conversations allow you to communicate with your customers, respond to quote requests, ask or receive questions, clarify matters, queries, etc. Customers can also initiate conversations.', 'b2bking' ); ?></p>
	    	    </div>
		    	<?php
		    }
		}

		function b2bking_offers_howto() {
			global $current_screen;
		    if( 'b2bking_offer' != $current_screen->post_type ){
			    return;
		    }

			// if notice has not already been dismissed once by the current user
			if (intval(get_user_meta(get_current_user_id(),'b2bking_dismiss_offers_howto_notice', true)) !== 1){
	    		?>
	    	    <div class="b2bking_offers_howto_notice notice notice-info is-dismissible">
	    	        <p><?php esc_html_e( 'Offers allow you to sell packages (bundles) of products in any quantities and at any price to specific customers or groups. You can use offers to create special deals, offer discounts, or sell packages.', 'b2bking' ); ?></p>
	    	    </div>
		    	<?php
		    }
		}

		function b2bking_rules_howto() {
			global $current_screen;
		    if( 'b2bking_rule' != $current_screen->post_type ){
			    return;
		    }

			// if notice has not already been dismissed once by the current user
			if (intval(get_user_meta(get_current_user_id(),'b2bking_dismiss_rules_howto_notice', true)) !== 1){
	    		?>
	    	    <div class="b2bking_rules_howto_notice notice notice-info is-dismissible">
	    	        <p><?php esc_html_e( 'Rules allow you to apply settings or requirements to specific users, groups or products. With rules you can offer discounts, hide prices, set minimum order quantities, tax-exempt users, and much more. ', 'b2bking' ); ?></p>
	    	    </div>
		    	<?php
		    }
		}

		function b2bking_roles_howto() {
			global $current_screen;
		    if( 'b2bking_custom_role' != $current_screen->post_type ){
			    return;
		    }

			// if notice has not already been dismissed once by the current user
			if (intval(get_user_meta(get_current_user_id(),'b2bking_dismiss_roles_howto_notice', true)) !== 1){
	    		?>
	    	    <div class="b2bking_roles_howto_notice notice notice-info is-dismissible">
	    	        <p><?php esc_html_e( 'Registration roles are the dropdown options users must choose from during registration. Create, edit, or delete roles as needed.', 'b2bking' ); ?>&nbsp;<a href="https://woocommerce-b2b-plugin.com/docs/difference-between-registration-roles-and-groups/"><?php esc_html_e('Roles are public, whereas groups are private.','b2bking');?></a></p>
	    	    </div>
		    	<?php
		    }
		}

		function b2bking_fields_howto() {
			global $current_screen;
		    if( 'b2bking_custom_field' != $current_screen->post_type ){
			    return;
		    }

			// if notice has not already been dismissed once by the current user
			if (intval(get_user_meta(get_current_user_id(),'b2bking_dismiss_fields_howto_notice', true)) !== 1){
	    		?>
	    	    <div class="b2bking_fields_howto_notice notice notice-info is-dismissible">
	    	        <p><?php esc_html_e( 'Registration fields are custom fields that you can configure and use to collect more info from your customers. They are visible to users in registration, my account, or checkout.', 'b2bking' ); ?></p>
	    	    </div>
		    	<?php
		    }
		}

		function b2bking_customers_howto() {
			global $current_screen;
		    if( 'b2bking_page_b2bking_customers' != $current_screen->id ){
			    return;
		    }

			// if notice has not already been dismissed once by the current user
			if (intval(get_user_meta(get_current_user_id(),'b2bking_dismiss_customers_howto_notice', true)) !== 1){
	    		?>
	    		<br>
	    	    <div class="b2bking_customers_howto_notice notice notice-info is-dismissible">
	    	        <p><?php esc_html_e( 'This panel shows an overview of all business customers in the site. Here you will see only users that have the "customer" role and are part of a Business group. For all customers, you can go to WooCommerce -> Customers.', 'b2bking' ); ?></p>
	    	    </div>
		    	<?php
		    }
			
		}	


}
