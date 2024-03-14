<?php

/**
 * WPB Woocommerce Custom Tab Manager by WpBean
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('wpb_woocommerce_custom_tab_manager') ):

	class wpb_woocommerce_custom_tab_manager {

		public function __construct(){

			add_action( 'init', array( $this, 'wpb_wtm_tab_post_type' ), 0 );
			add_filter( 'post_row_actions', array( $this, 'wpb_wctm_row_actions' ), 10, 2 );
			add_action( 'admin_head-post-new.php', array( $this, 'wpb_wctm_add_edit_tab_style' ) );
			add_action( 'admin_head-post.php', array( $this, 'wpb_wctm_add_edit_tab_style' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wpb_wctm_admin_style' ) );
			add_filter( 'manage_edit-wpb_wtm_tab_columns', array( $this, 'wpb_wctm_columns_head_for_tab' ), 10 );
			add_filter( 'manage_wpb_wtm_tab_posts_custom_column', array( $this, 'wpb_wctm_columns_content_for_tab' ), 2, 10 );
			add_filter( 'woocommerce_product_tabs', array($this, 'wpb_wctm_processing_dynamic_tabs'), 98 );


			add_action( 'admin_enqueue_scripts', array( $this, 'wpb_wctm_quick_edit_admin_enqueue_scripts' ) );
			add_action( 'save_post', array( $this, 'wpb_wctm_save_quick_edit_tab_meta' ) );
			add_action( 'quick_edit_custom_box', array( $this, 'wpb_wctm_display_custom_quickedit_tab' ), 10, 2 );

			add_action( 'add_meta_boxes', array( $this, 'wpb_wctm_pro_version_features_add' ) );
		}


		/**
		 * Tab Post Type
		 */

		public function wpb_wtm_tab_post_type() {

			$labels = array(
				'name'                  => esc_html_x( 'Tabs', 'Post Type General Name', 'wpb-woocommerce-tab-manager' ),
				'singular_name'         => esc_html_x( 'Tab', 'Post Type Singular Name', 'wpb-woocommerce-tab-manager' ),
				'menu_name'             => esc_html( 'Tab', 'wpb-woocommerce-tab-manager' ),
				'name_admin_bar'        => esc_html( 'Woo Tab', 'wpb-woocommerce-tab-manager' ),
				'archives'              => esc_html( 'Tab Archives', 'wpb-woocommerce-tab-manager' ),
				'parent_item_colon'     => esc_html( 'Parent Tab:', 'wpb-woocommerce-tab-manager' ),
				'all_items'             => esc_html( 'All Tabs', 'wpb-woocommerce-tab-manager' ),
				'add_new_item'          => esc_html( 'Add New Tab', 'wpb-woocommerce-tab-manager' ),
				'add_new'               => esc_html( 'Add New Tab', 'wpb-woocommerce-tab-manager' ),
				'new_item'              => esc_html( 'New Tab', 'wpb-woocommerce-tab-manager' ),
				'edit_item'             => esc_html( 'Edit Tab', 'wpb-woocommerce-tab-manager' ),
				'update_item'           => esc_html( 'Update Tab', 'wpb-woocommerce-tab-manager' ),
				'view_item'             => esc_html( 'View Tab', 'wpb-woocommerce-tab-manager' ),
				'search_items'          => esc_html( 'Search Tab', 'wpb-woocommerce-tab-manager' ),
				'not_found'             => esc_html( 'Not found', 'wpb-woocommerce-tab-manager' ),
				'not_found_in_trash'    => esc_html( 'Not found in Trash', 'wpb-woocommerce-tab-manager' ),
				'insert_into_item'      => esc_html( 'Insert into tab', 'wpb-woocommerce-tab-manager' ),
				'uploaded_to_this_item' => esc_html( 'Uploaded to this tab', 'wpb-woocommerce-tab-manager' ),
				'items_list'            => esc_html( 'Tabs list', 'wpb-woocommerce-tab-manager' ),
				'items_list_navigation' => esc_html( 'Tabs list navigation', 'wpb-woocommerce-tab-manager' ),
				'filter_items_list'     => esc_html( 'Filter tabs list', 'wpb-woocommerce-tab-manager' ),
			);
			$args = array(
				'label'                 => esc_html( 'Tab', 'wpb-woocommerce-tab-manager' ),
				'description'           => esc_html( 'WooCommerce Tabs Post Type', 'wpb-woocommerce-tab-manager' ),
				'labels'                => $labels,
				'supports'              => array( 'title', 'editor', ),
				'hierarchical'          => false,
				'public'                => false,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 80,
				'menu_icon'             => 'dashicons-screenoptions',
				'show_in_admin_bar'     => false,
				'show_in_nav_menus'     => false,
				'can_export'            => true,
				'has_archive'           => false,		
				'exclude_from_search'   => true,
				'publicly_queryable'    => true,
				'capability_type'       => 'page',
			);
			register_post_type( 'wpb_wtm_tab', $args );

		}


		/**
		 * Remove preview link form the tab post type row
		 */

		public	function wpb_wctm_row_actions( $actions, $post ) {

			if( $post->post_type == 'wpb_wtm_tab' ){
		   		unset( $actions['view'] );
			}

		    return $actions;
		}
		

		/**
		 * Add or Edit Tab page Style
		 */
		
		public function wpb_wctm_add_edit_tab_style() {

		    global $post_type;
		    $post_types = array( 'wpb_wtm_tab' );

		    if( in_array( $post_type, $post_types ) )
		    echo '<style>#post-preview, #view-post-btn{display: none;}</style>';
		
		}


		/**
		 * Admin Style
		 */
		
		public function wpb_wctm_admin_style() {

		    $screen = get_current_screen();

		    if( $screen->id == 'edit-wpb_wtm_tab' ){
		    	wp_enqueue_style('wpb_wctm_admin_style', plugins_url('../admin/assets/css/main.css', __FILE__),'','1.0', false);
		    }
		
		}



		/**
		 * Adding Custom columns Head in Tab post type
		 */

		public function wpb_wctm_columns_head_for_tab( $columns ) {

			$new_columns = array(
				'title' 		=> esc_html( 'Tab Name', 'wpb-woocommerce-custom-tab-manager' ),
				'priority' 		=> esc_html( 'Tab Priority', 'wpb-woocommerce-custom-tab-manager' ),
				'visibility' 	=> esc_html( 'Tab Visibility', 'wpb-woocommerce-custom-tab-manager' ),
			);

			unset($columns['date']);

			return array_merge($columns, $new_columns);
		}
		

		/**
		 * Adding Custom columns Content in Tab post type
		 */

		public function wpb_wctm_columns_content_for_tab ( $column, $post_id ) {

			global $post;

			switch( $column ) {

				case 'priority' :

					$priority = get_post_meta( $post_id, 'wpb_wctm_priority', true );

					if ( !empty( $priority ) )
						printf( esc_html( '%s' ), $priority );

					break;

				case 'visibility' :

					$visibility = get_post_meta( $post_id, 'wpb_wctm_active_tab', true );

					if ( !empty( $visibility ) ) {
						echo '<span data-wpb-wctm-visibility-show="true" class="wpb-wctm-icon tips wpb-wctm-visibility-show"></span>';
					}else {
						echo '<span data-wpb-wctm-visibility-show="false" class="wpb-wctm-icon tips wpb-wctm-visibility-hide"></span>';
					}
					
					break;	

				default :
					break;
			}

		}
		

		/**
		 * Adding Quick Edit feilds
		 */
		
		public function wpb_wctm_display_custom_quickedit_tab( $column_name, $post_type ) {

		    static $printNonce = TRUE;
		    if ( $printNonce ) {
		        $printNonce = FALSE;
		        wp_nonce_field( plugin_basename( __FILE__ ), 'wpb_wtm_tab_edit_nonce' );
		    }

		    ?>
				<fieldset class="inline-edit-col-right inline-edit-wpb_wtm_tab">
					<div class="inline-edit-col column-<?php echo $column_name; ?>">
					<label class="inline-edit-group">
						<?php
							switch ( $column_name ) {
								case 'priority':
									?><span class="title"><?php _e( 'Priority','wpb-woocommerce-custom-tab-manager' ); ?></span><input style="line-height: initial;" type="number" name="wpb_wctm_priority" /><?php
									break;
								case 'visibility':
									?><span class="title"><?php _e( 'Visibility','wpb-woocommerce-custom-tab-manager' ); ?></span><input type="checkbox" name="wpb_wctm_active_tab" /><?php
									break;
							}
						?>
					</label>
					</div>
				</fieldset>
		    <?php
		    
		}


		/**
		 * Saving Quick Edit feilds
		 */

		public function wpb_wctm_save_quick_edit_tab_meta( $post_id ) {

		    $slug = 'wpb_wtm_tab';

		    if( get_post_type($post_id) !== 'wpb_wtm_tab' ){
		    	return;
		    }

		    if ( $_POST && $slug !== $_POST['post_type'] ) {
		        return;
		    }

		    if ( !current_user_can( 'edit_post', $post_id ) ) {
		        return;
		    }

		    $_POST += array("{$slug}_edit_nonce" => '');

		    if ( !wp_verify_nonce( $_POST["{$slug}_edit_nonce"], plugin_basename( __FILE__ ) ) ) {
		        return;
		    }

		    if ( isset( $_REQUEST['wpb_wctm_priority'] ) ) {

		        update_post_meta( $post_id, 'wpb_wctm_priority', $_REQUEST['wpb_wctm_priority'] );

		    }


		    # checkboxes are submitted if checked, absent if not

		    if ( isset( $_REQUEST['wpb_wctm_active_tab'] ) ) {

		        update_post_meta($post_id, 'wpb_wctm_active_tab', TRUE);

		    }else {

		        update_post_meta($post_id, 'wpb_wctm_active_tab', FALSE);

		    }

		}


		/**
		 * Adding Quick Edit admin scripts
		 */

		public function wpb_wctm_quick_edit_admin_enqueue_scripts( $hook ) {

			if ( 'edit.php' === $hook && isset( $_GET['post_type'] ) && 'wpb_wtm_tab' === $_GET['post_type'] ) {

				wp_enqueue_script( 'wpb_wctm_admin_script', plugins_url('../admin/assets/js/admin_edit.js', __FILE__), false, null, true );

			}

		}


		/**
		 * Getting the global tabs form post type
		 */
		
		public function wpb_wctm_get_tabs() {

			$args = array (
				'post_type'      	=>  'wpb_wtm_tab'  ,
				'post_status'    	=>  'publish',
				'posts_per_page' 	=>  -1,
				'suppress_filters'  =>  false,
				'meta_query' 		=> array(
					array(
						'key'     => 'wpb_wctm_active_tab',
						'value'   => '1',
					),
				)	
			);

			/* WPML Support */
			if ( class_exists('SitePress') ) {
				$args[ 'suppress_filters' ] = 0;
			}

			$q_tabs = 	get_posts( $args );
			$tabs 	=	array();

			foreach ($q_tabs as $tab){

				$attr_tab = array();
				$attr_tab['title']                  	=   $tab->post_title;
				$attr_tab['priority']               	=   get_post_meta($tab->ID, 'wpb_wctm_priority', true);
				$attr_tab['id']                     	=   $tab->ID;
				$tabs[$tab->post_title.'_'.$tab->ID] 	=   $attr_tab;

			}
			return $tabs;

		}


		/**
		 * preparing the global tabs which we get form the post type
		 */
		
		public function wpb_wctm_processing_dynamic_tabs ( $tabs ) {

	        $wpb_tabs   =   $this->wpb_wctm_get_tabs();

	        foreach ( $wpb_tabs as $tab ){

	            $tabs[$tab["id"]] = array(
	            	'title'		=>	__( $tab['title'], 'wpb-woocommerce-tab-manager' ),
	                'priority' 	=>	$tab['priority'] + 30,
	                'callback' 	=>	array ( $this, 'wpb_wctm_the_content_tabs' )
	            );

	        }

	       return $tabs;
	    }


	    /**
	     * Getting the tab content
	     */
	    
	    public function wpb_wctm_the_content_tabs ( $id, $tab ) {

			$content_post 	= get_post($id);
			$content 		= $content_post->post_content;
			$content 		= apply_filters('the_content', $content);
			$content 		= str_replace(']]>', ']]&gt;', $content);
			echo $content;

	    }


	    /**
	     * PRO Version Features
	     */

	    public function wpb_wctm_pro_version_features(){
		    ?>
		    <div class="wpb_wctm_settings_content">
				<ul>
					<li>Product specific custom Tabs.</li>
					<li>Tab icon and subtitle support.</li>
					<li>Enable or disable default WooCommerce tabs.</li>
					<li>Feature for product specific customize default WooCommerce Tabs.</li>
					<li>Feature for converting tabs to accordions.</li>
					<li>Five different themes for tabs.</li>
					<li>Advance settings for tab style management.</li>
					<li>Quality support.</li>
					<li>Free Installation (If needed).</li>
					<li>Video Documentation.</li>
				</ul>
				<a class="wpb_get_pro_btn" href="http://bit.ly/1VYKvqV" target="_blank">Get The Pro Version</a>
			</div>
		    <?php 
		}

		/**
	     * PRO Version Videos
	     */

	    public function wpb_wctm_pro_version_videos(){
		    ?>
		    <div style="--aspect-ratio: 16/9;">
		    <iframe width="640" height="360" src="https://www.youtube.com/embed/H7TYekj2AAg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
			<br>
			<div style="--aspect-ratio: 16/9;">
		    <iframe width="640" height="360" src="https://www.youtube.com/embed/drpheibAGPI" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		    <?php 
		}

		/**
		 * Adding meta box for PRO features
		 */
		
		public function wpb_wctm_pro_version_features_add(){
		    add_meta_box( 'wpb-wctm-pro-features', esc_html( 'PRO Version Features', 'wpb-woocommerce-custom-tab-manager' ), array( $this, 'wpb_wctm_pro_version_features' ), 'wpb_wtm_tab', 'side', 'default' );
		    add_meta_box( 'wpb-wctm-pro-videos', esc_html( 'PRO Version Video Demonstration', 'wpb-woocommerce-custom-tab-manager' ), array( $this, 'wpb_wctm_pro_version_videos' ), 'wpb_wtm_tab', 'normal', 'default' );
		}
	}

endif;

new wpb_woocommerce_custom_tab_manager();