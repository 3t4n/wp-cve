<?php
	/*
	* @Author 		Themepoints
	* Copyright: 	2016 Themepoints
	* Version : 2.0.0
	*/

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	/*===================================================================
		Register Custom Post Function
	=====================================================================*/
	function team_manager_free_custom_post_type(){
		$labels = array(
			'name'                  => _x( 'Team Showcase', 'Post Type General Name', 'team-manager-free' ),
			'singular_name'         => _x( 'Team Showcase', 'Post Type Singular Name', 'team-manager-free' ),
			'menu_name'             => __( 'Team Showcase', 'team-manager-free' ),
			'name_admin_bar'        => __( 'Team Manager', 'team-manager-free' ),
			'parent_item_colon'     => __( 'Parent Item:', 'team-manager-free' ),
			'all_items'             => __( 'All Team Members', 'team-manager-free' ),
			'add_new_item'          => __( 'Add New Member', 'team-manager-free' ),
			'add_new'               => __( 'Add New Member', 'team-manager-free' ),
			'new_item'              => __( 'New Member', 'team-manager-free' ),
			'edit_item'             => __( 'Edit Member', 'team-manager-free' ),
			'update_item'           => __( 'Update Member', 'team-manager-free' ),
			'view_item'             => __( 'View Member', 'team-manager-free' ),
			'search_items'          => __( 'Search Team Member', 'team-manager-free' ),
			'not_found'             => __( 'Not found', 'team-manager-free' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'team-manager-free' ),
			'featured_image'        => __( 'Team Member Image', 'team-manager-free' ),
			'set_featured_image'    => __( 'Set Team Member image', 'team-manager-free' ),
			'remove_featured_image' => __( 'Remove Team Member image', 'team-manager-free' ),
			'use_featured_image'    => __( 'Use as Team Member image', 'team-manager-free' ),
			'items_list'            => __( 'Items list', 'team-manager-free' ),
			'items_list_navigation' => __( 'Items list navigation', 'team-manager-free' ),
			'filter_items_list'     => __( 'Filter items list', 'team-manager-free' ),
		);
		$args = array(
			'label'                 => __( 'Post Type', 'team-manager-free' ),
			'description'           => __( 'Post Type Description', 'team-manager-free' ),
			'labels'                => $labels,
			'supports'              =>  array( 'title', 'editor', 'thumbnail',),
			'hierarchical'          => false,
			'public'                => true,
			'menu_icon' 			=> 'dashicons-admin-users',
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'team_mf', $args );
	}
	// end custom post type
	add_action('init', 'team_manager_free_custom_post_type');

	function team_manager_free_custom_post_taxonomies_reg() {
		$labels = array(
			'name'              => _x( 'Team Member Groups', 'taxonomy general name' ),
			'singular_name'     => _x( 'Team Group', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Groups' ),
			'all_items'         => __( 'All Groups' ),
			'parent_item'       => __( 'Parent Group' ),
			'parent_item_colon' => __( 'Parent Group:' ),
			'edit_item'         => __( 'Edit Team Group' ), 
			'update_item'       => __( 'Update Group' ),
			'add_new_item'      => __( 'Add New Team Group' ),
			'new_item_name'     => __( 'New Team Group' ),
			'menu_name'         => __( 'Team Groups' ),
		);
		$args = array(
			'labels' => $labels,
			'hierarchical' => true,
		);
		register_taxonomy( 'team_mfcategory', 'team_mf', $args );
	}
	add_action( 'init', 'team_manager_free_custom_post_taxonomies_reg', 0 );

	# Modify Member Title
	function team_manager_free_admin_enter_title( $input ) {
		global $post_type;
		if ( 'team_mf' == $post_type )
			return __( 'Enter Member Name', 'team-manager-free' );
		return $input;
	}
	add_filter( 'enter_title_here', 'team_manager_free_admin_enter_title' );

	# Team Image uploader custom notice
	function team_manager_free_custom_post_help($content){
		global $post_type,$post;
		if ($post_type == 'team_mf') {
			if(!has_post_thumbnail( $post->ID )){
			   $content .= '<p>'.__('Please upload square-cropped photos with a minimum dimension of 500px','team-manager-free').'</p>';
			}
		}
		return $content;
	}
	add_filter('admin_post_thumbnail_html','team_manager_free_custom_post_help');

	# Team Update Notice
	function team_manager_free_custom_post_updated_messages( $messages ) {
		global $post, $post_id;
		$messages['team_mf'] = array(
			1 => __('Team Showcase updated.', 'team-manager-free'),
			2 => $messages['post'][2],
			3 => $messages['post'][3],
			4 => __('Team Showcase updated.', 'team-manager-free'),
			5 => isset($_GET['revision']) ? sprintf( __('Team Showcase restored to revision from %s', 'team-manager-free'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __('Team Showcase published.', 'team-manager-free'),
			7 => __('Team Showcase saved.', 'team-manager-free'),
			8 => __('Team Showcase submitted.', 'team-manager-free'),
			9 => sprintf( __('Team Showcase scheduled for: <strong>%1$s</strong>.', 'team-manager-free'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )),
			10 => __('Team Showcase draft updated.', 'team-manager-free'),
		);
		return $messages;
	}
	add_filter( 'post_updated_messages', 'team_manager_free_custom_post_updated_messages' );	

	# Team Add Options page
	function team_manager_free_custom_post_add_submenu_items(){
		add_submenu_page('edit.php?post_type=team_mf', __('Create Shortcode', 'team-manager-free'), __('Create Shortcode', 'team-manager-free'), 'manage_options', 'post-new.php?post_type=team_mf_team');
	}
	add_action('admin_menu', 'team_manager_free_custom_post_add_submenu_items');


	# Team Shortcode post register
	function team_manager_free_custom_post_create_team_type() {
	// Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Team Shortcodes', 'Post Type General Name', 'team-manager-free' ),
			'singular_name'       => _x( 'Shortcode', 'Post Type Singular Name', 'team-manager-free' ),
			'menu_name'           => __( 'Shortcodes', 'team-manager-free' ),
			'parent_item_colon'   => __( 'Parent Shortcode', 'team-manager-free' ),
			'all_items'           => __( 'All Shortcodes', 'team-manager-free' ),
			'view_item'           => __( 'View Shortcode', 'team-manager-free' ),
			'add_new_item'        => __( 'Create New Team Shortcode', 'team-manager-free' ),
			'add_new'             => __( 'Add New Team Shortcode', 'team-manager-free' ),
			'edit_item'           => __( 'Edit Team Shortcode', 'team-manager-free' ),
			'update_item'         => __( 'Update Team Shortcode', 'team-manager-free' ),
			'search_items'        => __( 'Search Team Shortcode', 'team-manager-free' ),
			'not_found'           => __( 'Team Shortcode Not Found', 'team-manager-free' ),
			'not_found_in_trash'  => __( 'Team Shortcode Not found in Trash', 'team-manager-free' ),
		);

		// Set other options for Custom Post Type
		$args = array(
			'label'               => __( 'Shortcodes', 'team-manager-free' ),
			'description'         => __( 'Shortcode news and reviews', 'team-manager-free' ),
			'labels'              => $labels,
			'supports'            => array( 'title'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu' 		  => 'edit.php?post_type=team_mf',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);

		// Registering your Custom Post Type
		register_post_type( 'team_mf_team', $args );
	}

	add_action( 'init', 'team_manager_free_custom_post_create_team_type');	

	# Modify shortcode page title
	function team_manager_free_team_mf_team_admin_enter_title( $input ) {
		global $post_type;
		if ( 'team_mf_team' == $post_type )
			return __( 'Enter Shortcode Name For Identity', 'team-manager-free' );
		return $input;
	}
	add_filter( 'enter_title_here', 'team_manager_free_team_mf_team_admin_enter_title' );

	# Team updated notice
	function team_manager_free_custom_post_team_mf_team_updated_messages( $messages ) {
		global $post, $post_id;
		$messages['team_mf_team'] = array( 
			1 => __('Team Shortcode updated.', 'team-manager-free'),
			2 => $messages['post'][2],
			3 => $messages['post'][3],
			4 => __('Shortcode updated.', 'team-manager-free'),
			5 => isset($_GET['revision']) ? sprintf( __('Shortcode restored to revision from %s', 'team-manager-free'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __('Team Shortcode published.', 'team-manager-free'),
			7 => __('Team Shortcode saved.', 'team-manager-free'),
			8 => __('Team Shortcode submitted.', 'team-manager-free'),
			9 => sprintf( __('Shortcode scheduled for: <strong>%1$s</strong>.', 'team-manager-free'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )),
			10 => __('Shortcode draft updated.', 'team-manager-free'),
		);
		return $messages;
	}
	add_filter( 'post_updated_messages', 'team_manager_free_custom_post_team_mf_team_updated_messages' );

	# Add Team Meta Box
	function team_manager_free_custom_post_meta_box() {
		add_meta_box(
			'custom_meta_box', // $id
			'Member Personal Information', // $title
			'team_manager_free_custom_inner_custom_boxes', // $callback
			'team_mf', 
			'normal', 
			'high'
		); 
		add_meta_box(
			'custom_meta_box2', // $id
			'Member Social Profiles', // $title
			'team_manager_free_custom_inner_custom_boxess', // $callback
			'team_mf', 
			'normal'
		);
	}
	add_action('add_meta_boxes', 'team_manager_free_custom_post_meta_box');

	/*----------------------------------------------------------------------
		Content Options Meta Box 
	----------------------------------------------------------------------*/
	
	function team_manager_free_custom_inner_custom_boxes( $post ) {

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'team_manager_free_custom_inner_custom_boxes_noncename' );

		?>

		<div id="details_profiles_area">
			<div class="details_profiles_cols">
				<!-- Designation -->
				<p><label for="post_title_designation"><strong><?php _e('Designation', 'team-manager-free');?></strong></label></p>
				<input type="text" name="post_title_designation" placeholder="Designation" id="post_title_designation" value="<?php echo esc_attr( get_post_meta($post->ID, 'client_designation', true) ); ?>" />

				<!-- Address  -->
				<p><label for="client_address_input"><strong><?php _e('Address', 'team-manager-free');?></strong></label></p>
				<input type="text" name="client_address_input" placeholder="Winston Salem, NC" id="client_address_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'company_address', true) ); ?>" />

				<!-- Contact Number -->
				<p><label for="contact_number_input"><strong><?php _e('Contact Number', 'team-manager-free');?></strong></label></p>
				<input type="text" name="contact_number_input" placeholder="xxx-xxx-xxxx" id="contact_number_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'contact_number', true) ); ?>" />
			</div>
			<div class="details_profiles_cols">
				<!-- Contact Email -->
				<p><label for="contact_email_input"><strong><?php _e('Email', 'team-manager-free');?></strong></label></p>
				<input type="text" name="contact_email_input" placeholder="email@exapmle.com" id="contact_email_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'contact_email', true) ); ?>" />

				<!-- Description -->
				<p><label for="short_description_input"><strong><?php _e('Short Description (Max 140 characters)', 'team-manager-free');?></strong></label></p>
				<textarea name="short_description_input" id="short_description_input" cols="30" rows="4" maxlength="140"><?php echo esc_attr( get_post_meta($post->ID, 'client_shortdescription', true) ); ?></textarea>
			</div>
		</div>
		<?php
	}
	
	function team_manager_free_custom_inner_custom_boxess( $post ) { ?>
		<div id="details_profiles_area">
			<div class="team-backend-socialprofiles">

				<div class="single-team-social-icons">
					<!-- Facebook -->
					<p><label for="facebook_social_input"><strong><?php _e('Facebook', 'team-manager-free');?></strong></label></p>
					<input type="text" name="facebook_social_input" placeholder="https://example.com/username" id="facebook_social_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'social_facebook', true ) ); ?>" />
				</div>

				<div class="single-team-social-icons">
				<!-- Twitter -->
				<p><label for="twitter_social_input"><strong><?php _e('Twitter', 'team-manager-free');?></strong></label></p>
				<input type="text" name="twitter_social_input" placeholder="https://example.com/username" id="twitter_social_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'social_twitter', true ) ); ?>" />
				</div>
				<div class="single-team-social-icons">
				<!-- Google plus -->
				<p><label for="googleplus_social_input"><strong><?php _e('Google Plus', 'team-manager-free');?></strong></label></p>
				<input type="text" name="googleplus_social_input" placeholder="https://example.com/username" id="googleplus_social_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'social_googleplus', true ) ); ?>" />
				</div>
				<div class="single-team-social-icons">
				<!-- Instagram -->
				<p><label for="instagram_social_input"><strong><?php _e('Instagram', 'team-manager-free');?></strong></label></p>
				<input type="text" name="instagram_social_input" placeholder="https://example.com/username" id="instagram_social_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'social_instagram', true ) ); ?>" />
				</div>
				<div class="single-team-social-icons">
				<!-- Pinterest -->
				<p><label for="pinterest_social_input"><strong><?php _e('Pinterest', 'team-manager-free');?></strong></label></p>
				<input type="text" name="pinterest_social_input" placeholder="https://example.com/username" id="pinterest_social_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'social_pinterest', true ) ); ?>" />
				</div>
				<div class="single-team-social-icons">
				<!-- LinkedIn -->
				<p><label for="linkedIn_social_input"><strong><?php _e('LinkedIn', 'team-manager-free');?></strong></label></p>
				<input type="text" name="linkedIn_social_input" placeholder="https://example.com/username" id="linkedIn_social_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'social_linkedin', true ) ); ?>" />
				</div>
				<div class="single-team-social-icons">
				<!-- Dribbble -->
				<p><label for="dribbble_social_input"><strong><?php _e('Dribbble', 'team-manager-free');?></strong></label></p>
				<input type="text" name="dribbble_social_input" placeholder="https://example.com/username" id="dribbble_social_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'social_dribbble', true ) ); ?>" />
				</div>
				<div class="single-team-social-icons">
				<!-- Youtube -->
				<p><label for="youtube_social_input"><strong><?php _e('Youtube', 'team-manager-free');?></strong></label></p>
				<input type="text" name="youtube_social_input" placeholder="https://example.com/username" id="youtube_social_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'social_youtube', true ) ); ?>" />
				</div>
				<div class="single-team-social-icons">
				<!-- Youtube -->
				<p><label for="skype_social_input"><strong><?php _e('Skype', 'team-manager-free');?></strong></label></p>
				<input type="text" name="skype_social_input" placeholder="https://example.com/username" id="skype_social_input" value="<?php echo esc_attr( get_post_meta($post->ID, 'social_skype', true ) ); ?>" />
				</div>
			</div>
		</div>
		<?php
	}

	# Save Options Meta Box Function
	function team_manager_free_custom_inner_custom_boxes_save($post_id){

		if(isset($_POST['post_title_designation'])) {
			update_post_meta($post_id, 'client_designation', $_POST['post_title_designation']);
		}

		if(isset($_POST['short_description_input'])) {
			update_post_meta($post_id, 'client_shortdescription', $_POST['short_description_input']);
		}

		if(isset($_POST['client_address_input'])) {
			update_post_meta($post_id, 'company_address', $_POST['client_address_input']);
		}

		if(isset($_POST['contact_number_input'])) {
			update_post_meta($post_id, 'contact_number', $_POST['contact_number_input']);
		}

		if(isset($_POST['contact_email_input'])) {
			update_post_meta($post_id, 'contact_email', $_POST['contact_email_input']);
		}

		if(isset($_POST['facebook_social_input'])) {
			update_post_meta($post_id, 'social_facebook', $_POST['facebook_social_input']);
		}

		if(isset($_POST['twitter_social_input'])) {
			update_post_meta($post_id, 'social_twitter', $_POST['twitter_social_input']);
		}

		if(isset($_POST['googleplus_social_input'])) {
			update_post_meta($post_id, 'social_googleplus', $_POST['googleplus_social_input']);
		}

		if(isset($_POST['instagram_social_input'])) {
			update_post_meta($post_id, 'social_instagram', $_POST['instagram_social_input']);
		}

		if(isset($_POST['pinterest_social_input'])) {
			update_post_meta($post_id, 'social_pinterest', $_POST['pinterest_social_input']);
		}

		if(isset($_POST['linkedIn_social_input'])) {
			update_post_meta($post_id, 'social_linkedin', $_POST['linkedIn_social_input']);
		}

		if(isset($_POST['dribbble_social_input'])) {
			update_post_meta($post_id, 'social_dribbble', $_POST['dribbble_social_input']);
		}

		if(isset($_POST['youtube_social_input'])) {
			update_post_meta($post_id, 'social_youtube', $_POST['youtube_social_input']);
		}

		if(isset($_POST['skype_social_input'])) {
			update_post_meta($post_id, 'social_skype', $_POST['skype_social_input']);
		}
	}
	add_action('save_post', 'team_manager_free_custom_inner_custom_boxes_save');


	# Columns Declaration Function
	function team_manager_free_columns($team_manager_free_columns){

		$order='asc';
		if($_GET['order']=='asc') {
			$order='desc';
		}
		$team_manager_free_columns = array(
			"cb"                      => "<input type=\"checkbox\" />",
			"thumbnail"               => __('Image', 'team-manager-free'),
			"title"                   => __('Name', 'team-manager-free'),
			"client_shortdescription" => __('Short Description', 'team-manager-free'),
			"client_designation"      => __('Designation', 'team-manager-free'),
			"ktstcategories"          => __('Categories', 'team-manager-free'),
			"date"                    => __('Date', 'team-manager-free'),
		);
		return $team_manager_free_columns;
	}

	# testimonial Value Function
	function team_manager_free_columns_display($team_manager_free_columns, $post_id){

		global $post;
		$width = (int) 80;
		$height = (int) 80;

		if ( 'thumbnail' == $team_manager_free_columns ) {
			if ( has_post_thumbnail($post_id)) {
				$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
				$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
				echo $thumb;
			}
			else {
				echo __('None');
			}
		}

		if ( 'client_designation' == $team_manager_free_columns ) {
			echo esc_attr( get_post_meta($post_id, 'client_designation', true) );
		}
		if ( 'client_shortdescription' == $team_manager_free_columns ) {
			echo esc_attr( get_post_meta($post_id, 'client_shortdescription', true) );
		}
		if ( 'ktstcategories' == $team_manager_free_columns ) {
			$terms = get_the_terms( $post_id , 'team_mfcategory');
			$count = count( array( $terms ) );
			if ( $terms ) {
				$i = 0;
				foreach ( $terms as $term ) {
					if ( $i+1 != $count ) {
						echo ", ";
					}
					echo '<a href="'.admin_url( 'edit.php?post_type=team_mf&team_mfcategory='.$term->slug ).'">'.$term->name.'</a>';
					$i++;
				}
			}
		}
	}
	
	# Add manage_tmls_posts_columns Filter 
	add_filter("manage_team_mf_posts_columns", "team_manager_free_columns");
	add_action("manage_team_mf_posts_custom_column",  "team_manager_free_columns_display", 10, 2 );	

	function team_manager_free_add_shortcode_column( $columns ) {
		$order='asc';
		if($_GET['order']=='asc') {
			$order='desc';
		}
		$columns = array(
			"cb"        => "<input type=\"checkbox\" />",
			"title"     => __('Shortcode Name', 'team-manager-free'),
			"shortcode" => __('Shortcode', 'team-manager-free'),
			"date"      => __('Date', 'team-manager-free'),
		);
		return $columns;
	}
	add_filter( 'manage_team_mf_team_posts_columns' , 'team_manager_free_add_shortcode_column' );

	function team_manager_free_add_posts_shortcode_display( $column, $post_id ) {
		if ($column == 'shortcode'){
			?>
			<span><input style="background:#ddd" type="text" onClick="this.select();" value="[tmfshortcode <?php echo 'id=&quot;'.$post_id.'&quot;';?>]" /></span>
			<?php
		}
	}
	add_action( 'manage_team_mf_team_posts_custom_column' , 'team_manager_free_add_posts_shortcode_display', 10, 2 );

	# Register Post Meta Boxes
	function team_manager_free_add_metabox() {
		$screens = array('team_mf_team');
		foreach ($screens as $screen) {
			add_meta_box('team_manager_free_sectionid', __('Team Options', 'team-manager-free'),'single_team_manager_free_display', $screen,'normal','high');
		}
	} // end metabox boxes

	add_action('add_meta_boxes', 'team_manager_free_add_metabox');

	/*=====================================================================
	 * Renders the nonce and the textarea for the notice.
	 =======================================================================*/
	function single_team_manager_free_display( $post, $args ) {
        global $post;

		//get the saved meta as an arry
		$team_manager_free_category_select 			= get_post_meta( $post->ID, 'team_manager_free_category_select', true );
		if(empty($team_manager_free_category_select)){
			$team_manager_free_category_select = array();
		}
		$team_manager_free_post_themes 				= get_post_meta( $post->ID, 'team_manager_free_post_themes', true );
		$teamf_orderby 								= get_post_meta( $post->ID, 'teamf_orderby', true );
		$team_manager_free_imagesize 				= get_post_meta( $post->ID, 'team_manager_free_imagesize', true );
		$team_manager_free_post_column 				= get_post_meta( $post->ID, 'team_manager_free_post_column', true );
		$team_manager_free_margin_bottom 			= get_post_meta( $post->ID, 'team_manager_free_margin_bottom', true );
		$team_manager_free_margin_lfr 				= get_post_meta( $post->ID, 'team_manager_free_margin_lfr', true );
		$team_manager_free_img_height 				= get_post_meta( $post->ID, 'team_manager_free_img_height', true );
		$team_manager_free_social_target 			= get_post_meta( $post->ID, 'team_manager_free_social_target', true );
		$team_manager_free_text_alignment 			= get_post_meta( $post->ID, 'team_manager_free_text_alignment', true );
		$team_manager_free_biography_option 		= get_post_meta( $post->ID, 'team_manager_free_biography_option', true );
		$team_manager_free_header_font_size 		= get_post_meta( $post->ID, 'team_manager_free_header_font_size', true );
		$team_manager_name_font_weight 				= get_post_meta( $post->ID, 'team_manager_name_font_weight', true );
		$team_manager_name_font_style 				= get_post_meta( $post->ID, 'team_manager_name_font_style', true );
		$team_manager_free_designation_font_size 	= get_post_meta( $post->ID, 'team_manager_free_designation_font_size', true );
		$team_manager_free_header_font_color 		= get_post_meta( $post->ID, 'team_manager_free_header_font_color', true );
		$team_manager_free_name_hover_font_color 	= get_post_meta( $post->ID, 'team_manager_free_name_hover_font_color', true );
		$team_manager_name_font_case 				= get_post_meta( $post->ID, 'team_manager_name_font_case', true );
		$team_manager_free_designation_font_color 	= get_post_meta( $post->ID, 'team_manager_free_designation_font_color', true );
		$team_manager_desig_font_case 				= get_post_meta( $post->ID, 'team_manager_desig_font_case', true );
		$team_manager_free_biography_font_size 		= get_post_meta( $post->ID, 'team_manager_free_biography_font_size', true );
		$team_manager_free_overlay_bg_color 		= get_post_meta( $post->ID, 'team_manager_free_overlay_bg_color', true );
		$team_manager_free_biography_font_color 	= get_post_meta( $post->ID, 'team_manager_free_biography_font_color', true );
		$team_fbackground_color 					= get_post_meta( $post->ID, 'team_fbackground_color', true );
		$team_manager_free_socialicons_hide 		= get_post_meta( $post->ID, 'team_manager_free_socialicons_hide', true );
		$tmffree_social_font_size 					= get_post_meta( $post->ID, 'tmffree_social_font_size', true );
		$tmffree_social_icon_color 					= get_post_meta( $post->ID, 'tmffree_social_icon_color', true );
		$tmffree_social_hover_color 				= get_post_meta( $post->ID, 'tmffree_social_hover_color', true );
		$tmffree_social_bg_color 					= get_post_meta( $post->ID, 'tmffree_social_bg_color', true );
		$team_manager_free_popupbox_hide         	= get_post_meta( $post->ID, 'team_manager_free_popupbox_hide', true);
		$team_manager_free_popupbox_styles         	= get_post_meta( $post->ID, 'team_manager_free_popupbox_styles', true);
		$team_manager_free_popupbox_positions       = get_post_meta( $post->ID, 'team_manager_free_popupbox_positions', true);
		$team_popup_designatins_hide       			= get_post_meta( $post->ID, 'team_popup_designatins_hide', true);
		$team_popup_emails_hide       				= get_post_meta( $post->ID, 'team_popup_emails_hide', true);
		$team_popup_contacts_hide       			= get_post_meta( $post->ID, 'team_popup_contacts_hide', true);
		$team_popup_address_hide       				= get_post_meta( $post->ID, 'team_popup_address_hide', true);
		$nav_value 									= get_post_meta( $post->ID, 'nav_value', true );
	?>

	<div class="tupsetings post-grid-metabox">
		<!-- <div class="wrap"> -->
		<ul class="tab-nav">
			<li nav="1" class="nav1 <?php if($nav_value == 1){echo "active";}?>"><?php _e('Shortcodes','team-manager-free'); ?></li>
			<li nav="2" class="nav2 <?php if($nav_value == 2){echo "active";}?>"><?php _e('Team Query ','team-manager-free'); ?></li>
			<li nav="3" class="nav3 <?php if($nav_value == 3){echo "active";}?>"><?php _e('General Settings ','team-manager-free'); ?></li>
			<li nav="4" class="nav4 <?php if($nav_value == 4){echo "active";}?>"><?php _e('Popup box Settings','team-manager-free'); ?></li>
			<li nav="5" class="nav5 <?php if($nav_value == 5){echo "active";}?>"><?php _e('Social Settings','team-manager-free'); ?></li>
			<li nav="6" class="nav6 <?php if($nav_value == 6){echo "active";}?>"><?php _e('Support & Doc','team-manager-free'); ?></li>
		</ul> <!-- tab-nav end -->
		<?php 
			$getNavValue = "";
			if(!empty($nav_value)){ $getNavValue = $nav_value; } else { $getNavValue = 1; }
		?>
		<input type="hidden" name="nav_value" id="nav_value" value="<?php echo $getNavValue; ?>"> 

		<ul class="box">
			<!-- Tab 1 -->
			<li style="<?php if($nav_value == 1){echo "display: block;";} else{ echo "display: none;"; }?>" class="box1 tab-box <?php if($nav_value == 1){echo "active";}?>">
				<div class="option-box">
					<p class="option-title"><?php _e('Shortcode','team-manager-free'); ?></p>
					<p class="option-info"><?php _e('Copy this shortcode and paste on post, page or text widgets where you want to display Team Showcase.','team-manager-free'); ?></p>
					<textarea cols="50" rows="1" onClick="this.select();" >[tmfshortcode <?php echo 'id="'.$post->ID.'"';?>]</textarea>
					<br /><br />
					<p class="option-info"><?php _e('PHP Code:','team-manager-free'); ?></p>
					<p class="option-info"><?php _e('Use PHP code to your themes file to display Team Showcase.','team-manager-free'); ?></p>
					<textarea cols="50" rows="2" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[tmfshortcode id='; echo "'".$post->ID."']"; echo '"); ?>'; ?></textarea>  
				</div>
			</li>
			
			<!-- Tab 2  -->
			<li style="<?php if($nav_value == 2){echo "display: block;";} else{ echo "display: none;"; }?>" class="box2 tab-box <?php if($nav_value == 2){echo "active";}?>">
				<div class="wrap">
					<div class="option-box">
						<p class="option-title"><?php _e('Team Query','team-manager-free'); ?></p>
						<table class="form-table">
							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_category_select"><?php _e('Select Categories', 'team-manager-free');?></label>
								</th>
								<td style="vertical-align: middle;">
									<ul>			
										<?php
											$args = array( 
												'taxonomy'     => 'team_mfcategory',
												'orderby'      => 'name',
												'show_count'   => 1,
												'pad_counts'   => 1,
												'hierarchical' => 1,
												'echo'         => 0
											);

											$allthecats = get_categories( $args );

											foreach( $allthecats as $category ):
											    $cat_id = $category->cat_ID;
											    $checked = ( in_array($cat_id,(array)$team_manager_free_category_select)? ' checked="checked"': "" );
											        echo'<li id="cat-'.$cat_id.'"><input type="checkbox" name="team_manager_free_category_select[]" id="'.$cat_id.'" value="'.$cat_id.'"'.$checked.'> <label for="'.$cat_id.'">'.__( $category->cat_name, 'team-manager-free' ).'</label></li>';
											endforeach;
										?>
									</ul>
									<span class="team_manager_hint"><?php echo __('Categories Names only show when you publish team members under any categories. You can select multiple categories.', 'team-manager-free'); ?></span>
								</td>
							</tr><!-- End Testimonial Categories -->

							<tr valign="top">
								<th scope="row"><label style="padding-left:10px;" for="team_manager_free_post_themes"><?php echo __('Select Themes', 'team-manager-free'); ?></label></th>
								<td style="vertical-align:middle;">

								<select name="team_manager_free_post_themes" id="team_manager_free_post_themes" class="timezone_string">
									<option value="theme1" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme1' ); ?>><?php _e('Team Theme 1', 'team-manager-free');?></option>
									<option value="theme2" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme2' ); ?>><?php _e('Team Theme 2', 'team-manager-free');?></option>
									<option value="theme3" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme3' ); ?>><?php _e('Team Theme 3', 'team-manager-free');?></option>
									<option value="theme4" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme4' ); ?>><?php _e('Team Theme 4', 'team-manager-free');?></option>
									<option disabled value="theme5" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme5' ); ?>><?php _e('Team Theme 5 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme6" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme6' ); ?>><?php _e('Team Theme 6 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme7" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme7' ); ?>><?php _e('Team Theme 7 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme8" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme8' ); ?>><?php _e('Team Theme 8 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme9" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme9' ); ?>><?php _e('Team Theme 9 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme10" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme10' ); ?>><?php _e('Team Theme 10 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme11" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme11' ); ?>><?php _e('Team Theme 11 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme12" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme12' ); ?>><?php _e('Team Theme 12 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme13" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme13' ); ?>><?php _e('Team Theme 13 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme14" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme14' ); ?>><?php _e('Team Theme 14 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme15" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme15' ); ?>><?php _e('Team Theme 15 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme16" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme16' ); ?>><?php _e('Team Theme 16 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme17" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme17' ); ?>><?php _e('Team Theme 17 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme18" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme18' ); ?>><?php _e('Team Theme 18 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme19" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme19' ); ?>><?php _e('Team Theme 19 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme20" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme20' ); ?>><?php _e('Team Theme 20 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme21" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme21' ); ?>><?php _e('Team Theme 21 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme22" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme22' ); ?>><?php _e('Team Theme 22 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme23" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme23' ); ?>><?php _e('Team Theme 23 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme24" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme24' ); ?>><?php _e('Team Theme 24 (Only Pro)', 'team-manager-free');?></option>
									<option disabled value="theme25" <?php if ( isset ( $team_manager_free_post_themes ) ) selected( $team_manager_free_post_themes, 'theme25' ); ?>><?php _e('Team Theme 25 (Only Pro)', 'team-manager-free');?></option>
								</select>
								<span class="team_manager_hint"><?php echo __('Choose Team Showcase Style.', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row"><label style="padding-left:10px;" for="teamf_orderby"><?php echo __('Order Team Member', 'team-manager-free'); ?></label></th>
								<td style="vertical-align:middle;">
									<select name="teamf_orderby" id="teamf_orderby" class="timezone_string">
										<option value="date" <?php if ( isset ( $teamf_orderby ) ) selected( $teamf_orderby, 'date' ); ?>><?php _e('Publish Date', 'team-manager-free');?></option>
										<option value="menu_order" <?php if ( isset ( $teamf_orderby ) ) selected( $teamf_orderby, 'menu_order' ); ?>><?php _e('Menu Order', 'team-manager-free');?></option>
										<option value="rand" <?php if ( isset ( $teamf_orderby ) ) selected( $teamf_orderby, 'rand' ); ?>><?php _e('Random', 'team-manager-free');?></option>
									</select>
									<span class="team_manager_hint"><?php echo __('Order Team Member By (Date, Menu Order or Random Order).', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_imagesize"><?php echo __('Team Image Size', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<select name="team_manager_free_imagesize" id="team_manager_free_imagesize" class="timezone_string">
										<option value="1" <?php if ( isset ( $team_manager_free_imagesize ) ) selected( $team_manager_free_imagesize, '1' ); ?>><?php _e('Default Size', 'team-manager-free');?></option>
										<option value="2" <?php if ( isset ( $team_manager_free_imagesize ) ) selected( $team_manager_free_imagesize, '2' ); ?>><?php _e('Custom Size', 'team-manager-free');?></option>
									</select>
									<span class="team_manager_hint"><?php echo __('Choose Team Member Image Size Default or Custom Size.', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top" id="hide1" style="<?php if($team_manager_free_imagesize == 1){	echo "display:none;"; }?>">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_img_height"></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="number" name="team_manager_free_img_height" id="team_manager_free_img_height" maxlength="4" class="timezone_string" required value="<?php  if($team_manager_free_img_height !=''){echo $team_manager_free_img_height; }else{ echo '220';} ?>">px<br/>
									<span class="team_manager_hint"><?php echo __('Custom image height without (px). default image height: 220px.', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_post_column"><?php echo __('Team Member Column', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<select name="team_manager_free_post_column" id="team_manager_free_post_column" class="timezone_string">
										<option value="3" <?php if ( isset ( $team_manager_free_post_column ) ) selected( $team_manager_free_post_column, '3' ); ?>><?php _e('3 Column', 'team-manager-free');?></option>
										<option value="2" <?php if ( isset ( $team_manager_free_post_column ) ) selected( $team_manager_free_post_column, '2' ); ?>><?php _e('2 Column', 'team-manager-free');?></option>
										<option value="4" <?php if ( isset ( $team_manager_free_post_column ) ) selected( $team_manager_free_post_column, '4' ); ?>><?php _e('4 Column', 'team-manager-free');?></option>
										<option disabled value="5" <?php if ( isset ( $team_manager_free_post_column ) ) selected( $team_manager_free_post_column, '5' ); ?>><?php _e('5 Column (Only Pro)', 'team-manager-free');?></option>
										<option disabled value="6" <?php if ( isset ( $team_manager_free_post_column ) ) selected( $team_manager_free_post_column, '6' ); ?>><?php _e('6 Column (Only Pro)', 'team-manager-free');?></option>
									</select>
									<span class="team_manager_hint"><?php echo __('Select Team Member Column.', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_margin_bottom"><?php echo __('Column Margin Bottom', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="number" name="team_manager_free_margin_bottom" id="team_manager_free_margin_bottom" maxlength="4" class="timezone_string" value="<?php  if($team_manager_free_margin_bottom !=''){echo $team_manager_free_margin_bottom; }else{ echo '30';} ?>"> <span style="color: red;">Available Pro Version</span>
									<span class="team_manager_hint"><?php echo __('Choose Team Member Column Margin Bottom.', 'team-manager-free'); ?></span>
									
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_margin_lfr"><?php echo __('Column Margin Left/Right', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="number" name="team_manager_free_margin_lfr" id="team_manager_free_margin_lfr" maxlength="4" class="timezone_string" value="<?php  if($team_manager_free_margin_lfr !=''){echo $team_manager_free_margin_lfr; }else{ echo '15';} ?>"><span style="color: red;">Available Pro Version</span>
									<span class="team_manager_hint"><?php echo __('Choose Team Member Column Margin Left/Right.', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row"><label style="padding-left:10px;" for="team_manager_free_social_target"><?php echo __('Social Profile Link', 'team-manager-free'); ?></label></th>
								<td style="vertical-align:middle;">
									<select name="team_manager_free_social_target" id="team_manager_free_social_target" class="timezone_string">
										<option value="_self" <?php if ( isset ( $team_manager_free_social_target ) ) selected( $team_manager_free_social_target, '_self' ); ?>><?php _e('Same Page', 'team-manager-free');?></option>
										<option value="_blank" <?php if ( isset ( $team_manager_free_social_target ) ) selected( $team_manager_free_social_target, '_blank' ); ?>><?php _e('New Page', 'team-manager-free');?></option>
									</select>
									<span class="team_manager_hint"><?php echo __('Open Social Profile Link Same Page or New Page.', 'team-manager-free'); ?></span>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</li>

			<!-- Tab Three -->
			<li style="<?php if($nav_value == 3){echo "display: block;";} else{ echo "display: none;"; }?>" class="box3 tab-box <?php if($nav_value == 3){echo "active";}?>">
				<div class="wrap">
					<div class="option-box">
						<p class="option-title"><?php _e('General Settings','team-manager-free'); ?></p>
						<table class="form-table">

							<tr valign="top">
								<th scope="row">
									<label id="team_manager_section_title"><?php echo __('Name Settings :', 'team-manager-free'); ?></label>
								</th>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_header_font_size"><?php echo __('Name Font Size', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="number" name="team_manager_free_header_font_size" id="team_manager_free_header_font_size" maxlength="4" class="timezone_string" value="<?php  if($team_manager_free_header_font_size !=''){echo $team_manager_free_header_font_size; }else{ echo '20';} ?>">
									<span class="team_manager_hint"><?php echo __('Select team member name font size. default font size (20px)', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_name_font_style"><?php _e('Name Text Style', 'team-manager-free');?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="team_manager_name_font_style" id="team_manager_name_font_style" class="timezone_string">
										<option value="pro" <?php if ( isset ( $team_manager_name_font_style ) ) selected( $team_manager_name_font_style, 'pro' ); ?>><?php _e('Available Pro', 'team-manager-free');?></option>
										<option value="normal" <?php if ( isset ( $team_manager_name_font_style ) ) selected( $team_manager_name_font_style, 'normal' ); ?>><?php _e('Default', 'team-manager-free');?></option>
										<option value="italic" <?php if ( isset ( $team_manager_name_font_style ) ) selected( $team_manager_name_font_style, 'italic' ); ?>><?php _e('Italic', 'team-manager-free');?></option>
									</select><br>
									<span class="team_manager_hint"><?php echo __('Select Text Style.', 'team-manager-free'); ?></span>
								</td>
							</tr>
							<!-- End Name Text Transform -->
							
							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_name_font_weight"><?php _e('Name Font Weight', 'team-manager-free');?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="team_manager_name_font_weight" id="team_manager_name_font_weight" class="timezone_string">
										<option value="pro" <?php if ( isset ( $team_manager_name_font_weight ) ) selected( $team_manager_name_font_weight, 'pro' ); ?>><?php _e('Available Pro', 'team-manager-free');?></option>
										<option value="600" <?php if ( isset ( $team_manager_name_font_weight ) ) selected( $team_manager_name_font_weight, '600' ); ?>><?php _e('600', 'team-manager-free');?></option>
										<option value="700" <?php if ( isset ( $team_manager_name_font_weight ) ) selected( $team_manager_name_font_weight, '700' ); ?>><?php _e('700', 'team-manager-free');?></option>
										<option value="500" <?php if ( isset ( $team_manager_name_font_weight ) ) selected( $team_manager_name_font_weight, '500' ); ?>><?php _e('500', 'team-manager-free');?></option>
										<option value="400" <?php if ( isset ( $team_manager_name_font_weight ) ) selected( $team_manager_name_font_weight, '400' ); ?>><?php _e('400', 'team-manager-free');?></option>
									</select><br>
									<span class="team_manager_hint"><?php echo __('Select Name Font Weight.', 'team-manager-free'); ?></span>
								</td>
							</tr>
							<!-- End Name Text Transform -->

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_header_font_color"><?php echo __('Name Font Color', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="text" name="team_manager_free_header_font_color" id="team_manager_free_header_font_color" class="timezone_string" value="<?php  if($team_manager_free_header_font_color !=''){echo $team_manager_free_header_font_color; }else{ echo '#007acc';} ?>">
									<span class="team_manager_hint"><?php echo __('Choose team member name font Color. default font color:#007acc', 'team-manager-free'); ?></span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_name_hover_font_color"><?php echo __('Name Hover Font Color', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="text" name="team_manager_free_name_hover_font_color" id="team_manager_free_name_hover_font_color" class="timezone_string" value="<?php  if($team_manager_free_name_hover_font_color !=''){echo $team_manager_free_name_hover_font_color; }else{ echo '#333333';} ?>">
									<span class="team_manager_hint"><?php echo __('Choose team member name hover font Color.default font color:#333333', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_name_font_case"><?php echo __('Name Text Transform', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<select name="team_manager_name_font_case" id="team_manager_name_font_case" class="timezone_string">
										<option value="unset" <?php if ( isset ( $team_manager_name_font_case ) ) selected( $team_manager_name_font_case, 'unset' ); ?>><?php _e('Default', 'testimonial-review');?></option>
										<option value="capitalize" <?php if ( isset ( $team_manager_name_font_case ) ) selected( $team_manager_name_font_case, 'capitalize' ); ?>><?php _e('Capitilize', 'testimonial-review');?></option>
										<option value="lowercase" <?php if ( isset ( $team_manager_name_font_case ) ) selected( $team_manager_name_font_case, 'lowercase' ); ?>><?php _e('Lowercase', 'testimonial-review');?></option>
										<option value="uppercase" <?php if ( isset ( $team_manager_name_font_case ) ) selected( $team_manager_name_font_case, 'uppercase' ); ?>><?php _e('Uppercase', 'testimonial-review');?></option>
									</select>
									<span class="team_manager_hint"><?php echo __('Choose team member name text transform.default text-transform: Capitilize', 'team-manager-free'); ?></span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label id="team_manager_section_title"><?php echo __('Designation Settings :', 'team-manager-free'); ?></label>
								</th>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_designation_font_size"><?php echo __('Designation Font Size', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="number" name="team_manager_free_designation_font_size" id="team_manager_free_designation_font_size" maxlength="4" class="timezone_string" value="<?php  if($team_manager_free_designation_font_size !=''){echo $team_manager_free_designation_font_size; }else{ echo '15';} ?>">
									<span class="team_manager_hint"><?php echo __('Select Team member Designation Font Size. default font size (15px)', 'team-manager-free'); ?></span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_designation_font_color"><?php echo __('Designation Font Color', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="text" name="team_manager_free_designation_font_color" id="team_manager_free_designation_font_color" class="timezone_string" value="<?php  if($team_manager_free_designation_font_color !=''){echo $team_manager_free_designation_font_color; }else{ echo '#333333';} ?>">
									<span class="team_manager_hint"><?php echo __('Choose Team member Designation Font Color.default font color:#333333', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_desig_font_case"><?php echo __('Designation Text Transform', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<select name="team_manager_desig_font_case" id="team_manager_desig_font_case" class="timezone_string">
										<option value="unset" <?php if ( isset ( $team_manager_desig_font_case ) ) selected( $team_manager_desig_font_case, 'unset' ); ?>><?php _e('Default', 'testimonial-review');?></option>
										<option value="capitalize" <?php if ( isset ( $team_manager_desig_font_case ) ) selected( $team_manager_desig_font_case, 'capitalize' ); ?>><?php _e('Capitilize', 'testimonial-review');?></option>
										<option value="lowercase" <?php if ( isset ( $team_manager_desig_font_case ) ) selected( $team_manager_desig_font_case, 'lowercase' ); ?>><?php _e('Lowercase', 'testimonial-review');?></option>
										<option value="uppercase" <?php if ( isset ( $team_manager_desig_font_case ) ) selected( $team_manager_desig_font_case, 'uppercase' ); ?>><?php _e('Uppercase', 'testimonial-review');?></option>
									</select><span style="color: red;">Available Pro Version</span>
									<span class="team_manager_hint"><?php echo __('Choose team member designation text transform.default text-transform: Capitilize', 'team-manager-free'); ?></span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label id="team_manager_section_title"><?php echo __('Biography Settings :', 'team-manager-free'); ?></label>
								</th>
							</tr>

							<tr valign="top">
								<th scope="row"><label style="padding-left:10px;" for="team_manager_free_biography_option"><?php echo __('Short Biography', 'team-manager-free'); ?></label></th>
								<td style="vertical-align:middle;">
								<select class="timezone_string" name="team_manager_free_biography_option">
									<option value="block" <?php if($team_manager_free_biography_option=='block') echo "selected"; ?> ><?php _e('Show', 'team-manager-free'); ?></option>
									<option value="none" <?php if($team_manager_free_biography_option=='none') echo "selected"; ?> ><?php _e('Hide', 'team-manager-free'); ?></option>
								</select>
								<span class="team_manager_hint"><?php echo __('Show/Hide Team Member Short Biography.', 'team-manager-free'); ?></span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_biography_font_size"><?php echo __('Biography Font Size', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="number" name="team_manager_free_biography_font_size" id="team_manager_free_biography_font_size" maxlength="4" class="timezone_string" value="<?php  if($team_manager_free_biography_font_size !=''){echo $team_manager_free_biography_font_size; }else{ echo '15';} ?>">
									<span class="team_manager_hint"><?php echo __('Select Team member Biography Font Size. default font size (15px)', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row"><label style="padding-left:10px;" for="team_manager_free_text_alignment"><?php echo __('Biography Text Alignment', 'team-manager-free'); ?></label></th>
								<td style="vertical-align:middle;">
								<select class="timezone_string" name="team_manager_free_text_alignment">
									<option value="default" <?php if($team_manager_free_text_alignment=='default') echo "selected"; ?> ><?php _e('Available Pro', 'team-manager-free');?></option>
									<option value="left" disabled <?php if($team_manager_free_text_alignment=='left') echo "selected"; ?> ><?php _e('Left', 'team-manager-free');?></option>
									<option value="center" disabled <?php if($team_manager_free_text_alignment=='center') echo "selected"; ?> ><?php _e('Center', 'team-manager-free');?></option>
									<option value="right" disabled <?php if($team_manager_free_text_alignment=='right') echo "selected"; ?> ><?php _e('Right', 'team-manager-free');?></option>
								</select>
								<span class="team_manager_hint"><?php echo __('Team member all text Alignment.', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_biography_font_color"><?php echo __('Biography Font Color', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="text" name="team_manager_free_biography_font_color" id="team_manager_free_biography_font_color" class="timezone_string" value="<?php  if($team_manager_free_biography_font_color !=''){echo $team_manager_free_biography_font_color; }else{ echo '#000000';} ?>">
									<span class="team_manager_hint"><?php echo __('Choose Team member biography font color.default font color:#000000', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_manager_free_overlay_bg_color"><?php echo __('Overlay Background Color', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="text" name="team_manager_free_overlay_bg_color" id="team_manager_free_overlay_bg_color" class="timezone_string" value="<?php  if($team_manager_free_overlay_bg_color !=''){echo $team_manager_free_overlay_bg_color; }else{ echo '#c1c1c1';} ?>">
									<span class="team_manager_hint"><?php echo __('Choose Team member overlay background Color.default overaly color:#c1c1c1', 'team-manager-free'); ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label style="padding-left:10px;" for="team_fbackground_color"><?php echo __('Member Background Color', 'team-manager-free'); ?></label>
								</th>
								<td style="vertical-align:middle;">
									<input type="text" name="team_fbackground_color" id="team_fbackground_color" class="timezone_string" value="<?php  if($team_fbackground_color !=''){echo $team_fbackground_color; }else{ echo '#f8f8f8';} ?>">
									<span class="team_manager_hint"><?php echo __('Choose Team member Background Color.default background color:#f8f8f8', 'team-manager-free'); ?></span>
								</td>
							</tr>

						</table>
					</div>
				</div>
			</li>

			<!-- Tab Four -->
			<li style="<?php if($nav_value == 4){echo "display: block;";} else{ echo "display: none;"; }?>" class="box4 tab-box <?php if($nav_value == 4){echo "active";}?>">
				<div class="wrap">
						<div class="option-box">
							<p class="option-title"><?php _e('Popup Box Settings (Available Pro)','team-manager-free'); ?></p>
							<!-- <p class="prover_hints">Note: This features not available in free version. upgrade pro version to unlock all features.</p> -->
							<table class="form-table">

								<tr valign="top">
									<th scope="row">
										<label for="team_manager_free_popupbox_hide"><?php _e('Show/Hide Popup', 'team-manager-free');?></label>
									</th>
									<td style="vertical-align: middle;">
										<div class="switch-field">
											<input type="radio" id="popupbox_true" name="team_manager_free_popupbox_hide" value="1" <?php if ( $team_manager_free_popupbox_hide == '1' || $team_manager_free_popupbox_hide == '') echo 'checked'; ?>/>
											<label for="popupbox_true"><?php _e( 'Show', 'team-manager-free' ); ?></label>

											<input type="radio" id="popupbox_false" name="team_manager_free_popupbox_hide" value="0" <?php if ( $team_manager_free_popupbox_hide == '0' ) echo 'checked'; ?>/>
											<label for="popupbox_false" class="popupbox_false"><?php _e( 'Hide', 'team-manager-free' ); ?></label>
										</div><br>
										<span class="team_manager_hint"><?php echo __('Show/Hide popup details page.', 'team-manager-free'); ?></span>
									</td>
								</tr>
								<!-- End hide Popup details page -->

								<tr valign="top">
									<th scope="row">
										<label for="team_manager_free_popupbox_styles"><?php _e('Popup Style', 'team-manager-free');?></label>
									</th>
									<td style="vertical-align: middle;">
										<select name="team_manager_free_popupbox_styles" id="team_manager_free_popupbox_styles" class="timezone_string">
											<option value="1" <?php if ( isset ( $team_manager_free_popupbox_styles ) ) selected( $team_manager_free_popupbox_styles, '1' ); ?>><?php _e('Style 1', 'team-manager-free');?></option>
											<option value="2" <?php if ( isset ( $team_manager_free_popupbox_styles ) ) selected( $team_manager_free_popupbox_styles, '2' ); ?>><?php _e('Style 2', 'team-manager-free');?></option>
										</select><br>
										<span class="team_manager_hint"><?php echo __('Choose Team Member popup page style.', 'team-manager-free'); ?></span>
									</td>
								</tr>
								<!-- End Popup style -->

								<tr valign="top">
									<th scope="row">
										<label for="team_manager_free_popupbox_positions"><?php _e('Popup Position', 'team-manager-free');?></label>
									</th>
									<td style="vertical-align: middle;">
										<select name="team_manager_free_popupbox_positions" id="team_manager_free_popupbox_positions" class="timezone_string">
											<option value="1" <?php if ( isset ( $team_manager_free_popupbox_positions ) ) selected( $team_manager_free_popupbox_positions, '1' ); ?>><?php _e('Right', 'team-manager-free');?></option>
											<option value="2" <?php if ( isset ( $team_manager_free_popupbox_positions ) ) selected( $team_manager_free_popupbox_positions, '2' ); ?>><?php _e('Center', 'team-manager-free');?></option>
											<option value="3" <?php if ( isset ( $team_manager_free_popupbox_positions ) ) selected( $team_manager_free_popupbox_positions, '3' ); ?>><?php _e('Left', 'team-manager-free');?></option>
										</select><br>
										<span class="team_manager_hint"><?php echo __('Choose Team Member popup page position.', 'team-manager-free'); ?></span>
									</td>
								</tr>
								<!-- End popup page position -->

								<tr valign="top">
									<th scope="row">
										<label for="team_popup_designatins_hide"><?php _e('Show/Hide Designation', 'team-manager-free');?></label>
									</th>
									<td style="vertical-align: middle;">
										<select name="team_popup_designatins_hide" id="team_popup_designatins_hide" class="timezone_string">
											<option value="1" <?php if ( isset ( $team_popup_designatins_hide ) ) selected( $team_popup_designatins_hide, '1' ); ?>><?php _e('Show', 'team-manager-free');?></option>
											<option value="0" <?php if ( isset ( $team_popup_designatins_hide ) ) selected( $team_popup_designatins_hide, '0' ); ?>><?php _e('Hide', 'team-manager-free');?></option>
										</select><br>
										<span class="team_manager_hint"><?php echo __('Show/Hide Team Member Designation in popup page.', 'team-manager-free'); ?></span>
									</td>
								</tr>
								<!-- End hide designation popup page -->

								<tr valign="top">
									<th scope="row">
										<label for="team_popup_emails_hide"><?php _e('Show/Hide Email', 'team-manager-free');?></label>
									</th>
									<td style="vertical-align: middle;">
										<select name="team_popup_emails_hide" id="team_popup_emails_hide" class="timezone_string">
											<option value="1" <?php if ( isset ( $team_popup_emails_hide ) ) selected( $team_popup_emails_hide, '1' ); ?>><?php _e('Show', 'team-manager-free');?></option>
											<option value="0" <?php if ( isset ( $team_popup_emails_hide ) ) selected( $team_popup_emails_hide, '0' ); ?>><?php _e('Hide', 'team-manager-free');?></option>
										</select><br>
										<span class="team_manager_hint"><?php echo __('Show/Hide Team Member Email in popup page.', 'team-manager-free'); ?></span>
									</td>
								</tr>
								<!-- End hide email popup page -->

								<tr valign="top">
									<th scope="row">
										<label for="team_popup_contacts_hide"><?php _e('Show/Hide Contact', 'team-manager-free');?></label>
									</th>
									<td style="vertical-align: middle;">
										<select name="team_popup_contacts_hide" id="team_popup_contacts_hide" class="timezone_string">
											<option value="1" <?php if ( isset ( $team_popup_contacts_hide ) ) selected( $team_popup_contacts_hide, '1' ); ?>><?php _e('Show', 'team-manager-free');?></option>
											<option value="0" <?php if ( isset ( $team_popup_contacts_hide ) ) selected( $team_popup_contacts_hide, '0' ); ?>><?php _e('Hide', 'team-manager-free');?></option>
										</select><br>
										<span class="team_manager_hint"><?php echo __('Show/Hide Team Member Contact info in popup page.', 'team-manager-free'); ?></span>
									</td>
								</tr>
								<!-- End hide contact info popup page -->


								<tr valign="top">
									<th scope="row">
										<label for="team_popup_address_hide"><?php _e('Show/Hide Address', 'team-manager-free');?></label>
									</th>
									<td style="vertical-align: middle;">
										<select name="team_popup_address_hide" id="team_popup_address_hide" class="timezone_string">
											<option value="1" <?php if ( isset ( $team_popup_address_hide ) ) selected( $team_popup_address_hide, '1' ); ?>><?php _e('Show', 'team-manager-free');?></option>
											<option value="0" <?php if ( isset ( $team_popup_address_hide ) ) selected( $team_popup_address_hide, '0' ); ?>><?php _e('Hide', 'team-manager-free');?></option>
										</select><br>
										<span class="team_manager_hint"><?php echo __('Show/Hide Team Member Address info in popup page.', 'team-manager-free'); ?></span>
									</td>
								</tr>
								<!-- End hide Address popup page -->

							</table>
						</div>

				</div>
			</li>



			<!-- Tab Four -->
			<li style="<?php if($nav_value == 5){echo "display: block;";} else{ echo "display: none;"; }?>" class="box5 tab-box <?php if($nav_value == 5){echo "active";}?>">
				<div class="wrap">
					<div class="option-box">
						<p class="option-title"><?php _e('Social Icon Settings (Available Pro)','team-manager-free'); ?></p>

						<table class="form-table">
							<tr valign="top">
								<th scope="row"><label for="team_manager_free_socialicons_hide"><?php _e('Show/Hide Social', 'team-manager-free');?></label>
								</th>
								<td style="vertical-align: middle;">
									<div class="switch-field">
										<input type="radio" id="social_icons_true" name="team_manager_free_socialicons_hide" value="1" <?php if ( $team_manager_free_socialicons_hide == '1' || $team_manager_free_socialicons_hide == '') echo 'checked'; ?>/>
										<label for="social_icons_true"><?php _e( 'Show', 'team-manager-free' ); ?></label>

										<input type="radio" id="social_icons_false" name="team_manager_free_socialicons_hide" value="0" <?php if ( $team_manager_free_socialicons_hide == '0' ) echo 'checked'; ?>/>
										<label for="social_icons_false" class="social_icons_false"><?php _e( 'Hide', 'team-manager-free' ); ?></label>
									</div><br>
									<span class="team_manager_hint"><?php echo __('Show/Hide Social Icons on front page.', 'team-manager-free'); ?></span>
								</td>
							</tr>
							<!-- End Social Icons -->

							<tr valign="top">
								<th scope="row">
									<label for="tmffree_social_font_size"><?php _e('Icon Font Size', 'team-manager-free');?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="number" name="tmffree_social_font_size" id="tmffree_social_font_size" maxlength="4" class="timezone_string" value="<?php  if($tmffree_social_font_size !=''){echo $tmffree_social_font_size; }else{ echo '12';} ?>">
								</td>
							</tr>
							<!-- End Icon Font Size -->

							<tr valign="top">
								<th scope="row">
									<label for="tmffree_social_icon_color"><?php _e('Icon Color', 'team-manager-free');?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" class="jscolor" id="tmffree_social_icon_color" name="tmffree_social_icon_color" value="<?php if($tmffree_social_icon_color !=''){echo $tmffree_social_icon_color;} else{ echo "#000";} ?>">
								</td>
							</tr> <!-- End Social Icon Color -->

							<tr valign="top">
								<th scope="row">
									<label for="tmffree_social_hover_color"><?php _e('Icon Hover Color', 'team-manager-free');?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" class="jscolor" id="tmffree_social_hover_color" name="tmffree_social_hover_color" value="<?php if($tmffree_social_hover_color !=''){echo $tmffree_social_hover_color;} else{ echo "#dd3333";} ?>">
								</td>
							</tr> <!-- End Social Icon Color -->

							<tr valign="top">
								<th scope="row">
									<label for="tmffree_social_bg_color"><?php _e('Icon Bg Color', 'team-manager-free');?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" class="jscolor" id="tmffree_social_bg_color" name="tmffree_social_bg_color" value="<?php if($tmffree_social_bg_color !=''){echo $tmffree_social_bg_color;} else{ echo "#fff";} ?>">
								</td>
							</tr> <!-- End Social Bg Color -->

						</table>
					</div>
				</div>
			</li>

			
			<!-- Tab Five -->
			<li style="<?php if($nav_value == 6){echo "display: block;";} else{ echo "display: none;"; }?>" class="box6 tab-box <?php if($nav_value == 6){echo "active";}?>">
				<div class="wrap">
					<div class="option-box">
						<p class="option-title"><?php _e('Support & Documentation','team-manager-free'); ?></p>
						
						
						<div class="team-pro-features">
							<div class="help-support">
								<div class="support-items">
									<div class="support-title">
										<?php echo __('Need Support', 'team-manager-free'); ?>
									</div>
									<div class="support-details">
										<p><?php echo __('If you need any helps, please don\'t hesitate to post it on WordPress.org Support Forum or Themepoints Support Forum', 'team-manager-free'); ?></p>
									</div>
									<div class="support-link">
										<a target="_blank" href="https://wordpress.org/support/plugin/team-showcase" class="button-1">WordPress.org</a>
										<a target="_blank" href="https://themepoints.com/questions-answer" class="button-1">Themepoints.com</a>
									</div>
								</div>
								<div class="support-items">
									<div class="support-title">
										<?php echo __('Happy User', 'team-manager-free'); ?>
									</div>
									<div class="support-details">
										<p><?php echo __('If you are happy with the Team Showcase, say it on wordpress.org and give Team Showcase a nice review!', 'team-manager-free'); ?></p>
									</div>
									<div class="support-link">
										<a target="_blank" style="color: red;font-size: 20px;margin-left: 5px;" href="https://wordpress.org/support/plugin/team-showcase/reviews/">
											<div class="reviewteam">
													<span class="dashicons dashicons-star-filled"></span>
													<span class="dashicons dashicons-star-filled"></span>
													<span class="dashicons dashicons-star-filled"></span>
													<span class="dashicons dashicons-star-filled"></span>
													<span class="dashicons dashicons-star-filled"></span>
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</li>

		</ul>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function(jQuery){
			jQuery('#team_manager_free_header_font_color,#team_manager_free_biography_font_color,#team_manager_free_name_hover_font_color,#team_manager_free_designation_font_color,#team_manager_free_overlay_bg_color, #team_fbackground_color').wpColorPicker();
		});
	</script>
	<?php
	}		

		
	/**
	 * Saves the notice for the given post.
	 *
	 * @params	$post_id	The ID of the post that we're serializing
	 */
	function save_notice( $post_id ) {

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_category_select' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_category_select', $_POST['team_manager_free_category_select'] );
		} else {
            update_post_meta( $post_id, 'team_manager_free_category_select', 'unchecked');
        }

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_post_themes' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_post_themes', $_POST[ 'team_manager_free_post_themes' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'teamf_orderby' ] ) ) {
			update_post_meta( $post_id, 'teamf_orderby', $_POST[ 'teamf_orderby' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_imagesize' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_imagesize', $_POST[ 'team_manager_free_imagesize' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_post_column' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_post_column', $_POST[ 'team_manager_free_post_column' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_margin_bottom' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_margin_bottom', $_POST[ 'team_manager_free_margin_bottom' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_margin_lfr' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_margin_lfr', $_POST[ 'team_manager_free_margin_lfr' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_img_height' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_img_height', $_POST['team_manager_free_img_height'] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_text_alignment' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_text_alignment', $_POST[ 'team_manager_free_text_alignment' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_biography_option' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_biography_option', $_POST[ 'team_manager_free_biography_option' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_header_font_size' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_header_font_size', $_POST[ 'team_manager_free_header_font_size' ] );
		}

		#Checks for input and saves if needed
		if(isset($_POST['team_manager_name_font_weight'])) {
			update_post_meta($post_id, 'team_manager_name_font_weight', $_POST['team_manager_name_font_weight']);
		}

		#Checks for input and saves if needed
		if(isset($_POST['team_manager_name_font_style'])) {
			update_post_meta($post_id, 'team_manager_name_font_style', $_POST['team_manager_name_font_style']);
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_designation_font_size' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_designation_font_size', $_POST[ 'team_manager_free_designation_font_size' ] );
		}	

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_header_font_color' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_header_font_color', $_POST[ 'team_manager_free_header_font_color' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_name_hover_font_color' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_name_hover_font_color', $_POST[ 'team_manager_free_name_hover_font_color' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_name_font_case' ] ) ) {
			update_post_meta( $post_id, 'team_manager_name_font_case', $_POST[ 'team_manager_name_font_case' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_designation_font_color' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_designation_font_color', $_POST[ 'team_manager_free_designation_font_color' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_desig_font_case' ] ) ) {
			update_post_meta( $post_id, 'team_manager_desig_font_case', $_POST[ 'team_manager_desig_font_case' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_biography_font_size' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_biography_font_size', $_POST[ 'team_manager_free_biography_font_size' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_overlay_bg_color' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_overlay_bg_color', $_POST[ 'team_manager_free_overlay_bg_color' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_biography_font_color' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_biography_font_color', $_POST[ 'team_manager_free_biography_font_color' ] );
		}	

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_fbackground_color' ] ) ) {
			update_post_meta( $post_id, 'team_fbackground_color', $_POST[ 'team_fbackground_color' ] );
		}

		#Checks for input and saves if needed
		if(isset($_POST['team_manager_free_popupbox_hide'])) {
			update_post_meta($post_id, 'team_manager_free_popupbox_hide', $_POST['team_manager_free_popupbox_hide']);
		}

		#Checks for input and saves if needed
		if(isset($_POST['team_manager_free_popupbox_styles'])) {
			update_post_meta($post_id, 'team_manager_free_popupbox_styles', $_POST['team_manager_free_popupbox_styles']);
		}

		#Checks for input and saves if needed
		if(isset($_POST['team_manager_free_popupbox_positions'])) {
			update_post_meta($post_id, 'team_manager_free_popupbox_positions', $_POST['team_manager_free_popupbox_positions']);
		}

		#Checks for input and saves if needed
		if(isset($_POST['team_popup_designatins_hide'])) {
			update_post_meta($post_id, 'team_popup_designatins_hide', $_POST['team_popup_designatins_hide']);
		}

		#Checks for input and saves if needed
		if(isset($_POST['team_popup_emails_hide'])) {
			update_post_meta($post_id, 'team_popup_emails_hide', $_POST['team_popup_emails_hide']);
		}

		#Checks for input and saves if needed
		if(isset($_POST['team_popup_contacts_hide'])) {
			update_post_meta($post_id, 'team_popup_contacts_hide', $_POST['team_popup_contacts_hide']);
		}

		#Checks for input and saves if needed
		if(isset($_POST['team_popup_address_hide'])) {
			update_post_meta($post_id, 'team_popup_address_hide', $_POST['team_popup_address_hide']);
		}
		
		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_socialicons_hide' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_socialicons_hide', $_POST[ 'team_manager_free_socialicons_hide' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'tmffree_social_font_size' ] ) ) {
			update_post_meta( $post_id, 'tmffree_social_font_size', $_POST[ 'tmffree_social_font_size' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'tmffree_social_icon_color' ] ) ) {
			update_post_meta( $post_id, 'tmffree_social_icon_color', $_POST[ 'tmffree_social_icon_color' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'tmffree_social_hover_color' ] ) ) {
			update_post_meta( $post_id, 'tmffree_social_hover_color', $_POST[ 'tmffree_social_hover_color' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'tmffree_social_bg_color' ] ) ) {
			update_post_meta( $post_id, 'tmffree_social_bg_color', $_POST[ 'tmffree_social_bg_color' ] );
		}

		#Checks for input and saves if needed
		if( isset( $_POST[ 'team_manager_free_social_target' ] ) ) {
			update_post_meta( $post_id, 'team_manager_free_social_target', $_POST[ 'team_manager_free_social_target' ] );
		}

		#Value check and saves if needed
		if( isset( $_POST[ 'nav_value' ] ) ) {
			update_post_meta( $post_id, 'nav_value', $_POST['nav_value'] );
		} else {
			update_post_meta( $post_id, 'nav_value', 1 );
		}

	} // end save_notice
	add_action('save_post', 'save_notice');