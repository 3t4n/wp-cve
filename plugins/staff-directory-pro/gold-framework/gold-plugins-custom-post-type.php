<?php 
/* 
This file is part of A Gold Plugin

Gold Plugins are free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

A Gold Plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with a Gold Plugin.  If not, see <http://www.gnu.org/licenses/>.
*/
if (!class_exists('GoldPlugins_StaffDirectory_CustomPostType')):

	class GoldPlugins_StaffDirectory_CustomPostType
	{
		var $customFields = false;
		var $customPostTypeName = 'custompost';
		var $customPostTypeSingular = 'customPost';
		var $customPostTypePlural = 'customPosts';
		var $prefix = '_ikcf_';
		
		function __construct($postType, $customFields = false, $removeDefaultCustomFields = false, $forceClassicEditor = false)
		{
			$this->postType = $postType;
			$this->setupCustomPostType($postType);
			
			if ($customFields)
			{
				$this->customFields = $customFields;
				$this->setupCustomFields($this->customFields);			
			}
			
			if ($forceClassicEditor)
			{
				add_filter('use_block_editor_for_post_type', array($this, 'disable_gutenberg'), 10, 2);
			}				
		}		
		
		function clean_title($str)
		{
			$str = str_replace(' ', '-', $str);
			$str = sanitize_title($str);
			return $str;
		}
	
		
		function setupCustomPostType($postType)
		{
			$singular = ucwords($postType['name']);
			$plural = isset($postType['plural']) ? ucwords($postType['plural']) : $singular . 's';
			$exclude_from_search = isset($postType['exclude_from_search']) ? $postType['exclude_from_search'] : false;

			$this->customPostTypeName = $this->clean_title($singular);
			$this->customPostTypeSingular = $singular;
			$this->customPostTypePlural = $plural;
			
			if ($this->customPostTypeName != 'post' && $this->customPostTypeName != 'page')
			{		
				$labels = array
				(
					'name' => _x($plural, 'post type general name'),
					'singular_name' => _x($singular, 'post type singular name'),
					'add_new' => _x('Add New ' . $singular, strtolower($singular)),
					'add_new_item' => __('Add New ' . $singular),
					'edit_item' => __('Edit ' . $singular),
					'new_item' => __('New ' . $singular),
					'view_item' => __('View ' . $singular),
					'search_items' => __('Search ' . $plural),
					'not_found' =>  __('No ' . strtolower($plural) . ' found'),
					'not_found_in_trash' => __('No ' . strtolower($plural) . ' found in Trash'), 
					'parent_item_colon' => '',
					'featured_image' => __('Staff Member Photo', 'company-directory'),
					'set_featured_image' => __('Select a photo', 'company-directory'),
					'remove_featured_image' => __('Remove photo', 'company-directory'),
				);
				
				$args = array(
					'labels' => $labels,
					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true, 
					'exclude_from_search' => $exclude_from_search,
					'query_var' => true,
					'rewrite' => array( 'slug' => $postType['slug'], 'with_front' => (strlen($postType['slug'])>0) ? false : true),
					'capability_type' => 'post',
					'hierarchical' => false,
					//'show_in_menu' => 'staff_dir-settings',
					'show_in_menu' => true,
					'supports' => array('title','editor','author','thumbnail','excerpt','comments','custom-fields','page-attributes'),
					'menu_icon' => 'dashicons-id-alt',
					'show_in_rest' => true,
				);
				$this->customPostTypeArgs = $args;
		
				// register hooks
				add_action( 'init', array( &$this, 'registerPostTypes' ), 0 );
			}
		}

		function registerPostTypes()
		{
		  register_post_type($this->customPostTypeName,$this->customPostTypeArgs);
		}
		
		function setupCustomFields($fields)
		{
			$this->customFields = array();
			foreach ($fields as $f)
			{
				$this->customFields[] = array
				(
					"name"			=> $f['name'],
					"title"			=> $f['title'],
					"default"		=> isset($f['default']) ? $f['default'] : '',
					"placeholder"	=> isset($f['placeholder']) ? $f['placeholder'] : '',
					"description"	=> isset($f['description']) ? $f['description'] : '',
					"options"		=> isset($f['options']) ? $f['options'] : '',
					"type"			=> isset($f['type']) ? $f['type'] : "text",
					"scope"			=>	array( $this->customPostTypeName ),
					"capability"	=> "edit_posts"
				);
			}
			// register hooks
			add_action( 'admin_menu', array( &$this, 'createCustomFields' ) );
			add_action( 'save_post', array( &$this, 'saveCustomFields' ), 1, 2 );
		}
			
		/**
		* Create the new Custom Fields meta box
		*/
		function createCustomFields() 
		{
			if ( function_exists( 'add_meta_box' ) ) 
			{
				//add_meta_box( 'my-custom-fields', 'Custom Fields', array( &$this, 'displayCustomFields' ), 'page', 'normal', 'high' );
				//add_meta_box( 'my-custom-fields', 'Custom Fields', array( &$this, 'displayCustomFields' ), 'post', 'normal', 'high' );
				add_meta_box( 'my-custom-fields'.md5(serialize($this->customFields)), $this->customPostTypeSingular . ' Information', array( &$this, 'displayCustomFields' ), $this->customPostTypeName, 'normal', 'high' );//RWG
			}
		}

		/**
		* Display the new Custom Fields meta box
		*/
		function displayCustomFields() {
			global $post;
			do_action( 'gp_cpt_meta_box_start_' . $this->customPostTypeName, $post, $this->postType );
			?>
			<div class="form-wrap">
				<?php
				wp_nonce_field( 'my-custom-fields', 'my-custom-fields_wpnonce', false, true );
				foreach ( $this->customFields as $customField ) {
					// Check scope
					$scope = $customField[ 'scope' ];
					$output = false;
					foreach ( $scope as $scopeItem ) {
						switch ( $scopeItem ) {
							case "post": {
								// Output on any post screen
								if ( basename( $_SERVER['SCRIPT_FILENAME'] )=="post-new.php" || $post->post_type=="post" )
									$output = true;
								break;
							}
							case "page": {
								// Output on any page screen
								if ( basename( $_SERVER['SCRIPT_FILENAME'] )=="page-new.php" || $post->post_type=="page" )
									$output = true;
								break;
							}
							default:{//RWG
								if ($post->post_type==$scopeItem )
									$output = true;
								break;
							}
						}
						if ( $output ) break;
					}
					// Check capability
					if ( !current_user_can( $customField['capability'], $post->ID ) )
						$output = false;
					// Output if allowed
					if ( $output ) { ?>
						<div class="form-field form-required">
							<?php
							$current_value = get_post_meta( $post->ID, $this->prefix . $customField['name'], true );
							$placeholder = !empty($customField['placeholder'])
											 ? $customField['placeholder'] : 
											 "";
							$default_value = !empty($customField['default'])
											 ? $customField['default'] : 
											 "";
							$value = !empty($current_value)
									 ? $current_value
									 : $default_value;
									 
							switch ( $customField[ 'type' ] ) {
								case "select":
								{
									// Select / Dropdown Menu
									if ( !empty($customField['options']) ) {
										echo '<label for="' . $this->prefix . $customField[ 'name' ] .'" style="display:block; margin-bottom: 4px;"><b>' . $customField[ 'title' ] . '</b></label>';
										echo '<select name="' . $this->prefix . $customField['name'] . '" style="min-width:300px;">';
										foreach($customField['options'] as $option_index => $option) {
											if ( empty($option['value']) && empty($option['label']) ) {
												continue;
											}
											$selected = ( empty($current_value) && ($option_index == $customField['default']) )
														|| ($current_value ==  $option['value'])
														? 'selected="selected"'
														: '';
											printf('<option value="%s" %s>%s</option>', htmlentities($option['value']), $selected, htmlentities($option['label']));
										}
										echo '</select>';
									}
									break;
								}
								case "radio":
								{
									// Radio / Multiple Choice
									if ( !empty($customField['options']) ) {
										echo '<label for="' . $this->prefix . $customField[ 'name' ] .'" style="display:block; margin-bottom: 0px;"><b>' . $customField[ 'title' ] . '</b></label>';
										if ( $customField[ 'description' ] ) {
											echo '<p>' . $customField[ 'description' ] . '</p>';
										}
										foreach($customField['options'] as $option_index => $option) {
											if ( empty($option['value']) && empty($option['label']) ) {
												continue;
											}
											$checked = ( empty($current_value) && ($option_index == $customField['default']) )
													   || ($current_value ==  $option['value'])
														? 'checked="checked"'
														: '';
											printf('<label style="font-style:normal; display: block; margin-bottom: 4px;"><input type="radio" name="%s" value="%s" %s /> %s</label>', $this->prefix . $customField['name'], htmlentities($option['value']), $checked, htmlentities($option['label']));
										}
									}
									break;
								}
								case "checkbox":
								{
									// Checkbox
									echo '<label for="' . $this->prefix . $customField[ 'name' ] .'" style="display:inline;">';
									echo '<input type="hidden" name="' . $this->prefix . $customField['name'] . '" value="no" style="display:none" />';
									echo '<input type="checkbox" name="' . $this->prefix . $customField['name'] . '" id="' . $this->prefix . $customField['name'] . '" value="yes"';
									if ( $current_value == "yes"									
										 || ( empty($current_value) && ($default_value == "yes") )
									) {
										echo ' checked="checked"';
									}
									echo ' style="width: auto;" />';
									echo '&nbsp;<b>' . htmlentities($customField[ 'title' ]) . '</b></label>';
									break;
								}
								case "textarea":
								{
									// Text area
									echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
									echo '<textarea name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" columns="30" rows="3" placeholder="' . htmlspecialchars( $placeholder ) . '">' . htmlspecialchars( $value ) . '</textarea>';
									break;
								}
								case "email":
								{
									// HTML5 email field
									echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
									echo '<input type="email" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( $value ) . '" placeholder="' . htmlspecialchars( $placeholder ) . '" />';
									break;
								}
								case "phone":
								{
									// HTML5 tel field
									echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
									echo '<input type="tel" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( $value ) . '" placeholder="' . htmlspecialchars( $placeholder ) . '" />';
									break;
								}
								case "date":
								{
									// HTML5 date field
									echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
									echo '<input type="date" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( $value ) . '" placeholder="' . htmlspecialchars( $placeholder ) . '" />';
									break;
								}
								default:
								case "text":
								{
									// Plain text field
									echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
									echo '<input type="text" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( $value ) . '" placeholder="' . htmlspecialchars( $placeholder ) . '" />';
									break;
								}
							}
							?>
							<?php if ( !in_array($customField['type'], array('radio')) ): ?>
								<?php if ( $customField[ 'description' ] ) { echo '<p>' . $customField[ 'description' ] . '</p>'; } ?>
							<?php endif; ?>
						</div>
					<?php
					}
				} ?>
			</div>
			<?php
			do_action( 'gp_cpt_meta_box_end_' . $this->customPostTypeName, $post, $this->postType );			
		}

		/**
		* Save the new Custom Fields values
		*/
		function saveCustomFields( $post_id, $post )
		{
			if ( ! isset($_POST[ 'my-custom-fields_wpnonce' ]) ) {
				return;
			}
			if ( isset($_POST[ 'my-custom-fields_wpnonce' ]) && !wp_verify_nonce( $_POST[ 'my-custom-fields_wpnonce' ], 'my-custom-fields' ) ) {
				return;
			}
			if ( !current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			// handle the case when the custom post is quick edited
			// otherwise all custom meta fields are cleared out
			if ( isset($_POST['_inline_edit']) || isset($_REQUEST['bulk_edit']) ) {
				  return;
			}
			foreach ( $this->customFields as $customField ) {
				if ( current_user_can( $customField['capability'], $post_id ) ) {
					if ( isset( $_POST[ $this->prefix . $customField['name'] ] ) && trim( $_POST[ $this->prefix . $customField['name'] ] ) ) {
						if ( 'textarea' == $customField['type'] ) {
							$new_val = sanitize_textarea_field( $_POST[ $this->prefix . $customField['name'] ]);							
						}
						else {
							$new_val = sanitize_text_field( $_POST[ $this->prefix . $customField['name'] ]);														
						}
						update_post_meta( $post_id, $this->prefix . $customField[ 'name' ], $new_val );
					}
				}
			}
		}

		function disable_gutenberg($current_status, $post_type)
		{
			if ($post_type === $this->customPostTypeName) {
				return false;
			}
			return $current_status;
		}	
	}
endif; // class_exists
?>