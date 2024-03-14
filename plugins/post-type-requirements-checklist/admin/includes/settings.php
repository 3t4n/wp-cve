<?php
class post_type_requirements_checklist_settings {
	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * Call $plugin_slug from public plugin class later.
	 *
	 * @since    1.0
	 * @var      string
	 */

	protected $plugin_slug = null;


	/**
	 * Instance of this class.
	 *
	 * @since    1.0
	 * @var      object
	 */
	protected static $instance = null;


	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0
	 */
	private function __construct() {
		$plugin = post_type_requirements_checklist::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		// Add settings page
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_print_styles', array( $this, 'is_settings_page' ) );
	}


	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}


	/**
	 * enqueue styles
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.0
	 */
	public function is_settings_page(){

		wp_enqueue_style('aptrc-settings-style', plugins_url( '../css/aptrc-settings.css', __FILE__ ) );

	} // end is_settings_page


	/**
	 * Registering the Sections, Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function admin_init() {
		$plugin = post_type_requirements_checklist::get_instance();
		$post_types = $plugin->supported_post_types();
		$defaults = array(
				// order defined by Parameters reference at http://codex.wordpress.org/Function_Reference/post_type_supports
				'title' => '',
				'editor' => '',
				'thumbnail' => '',
				'excerpt' => '',
				'categories' => '',
				'tags' => '',
				'customtaxonomies' => '',
				'customfields' => ''
			);

		foreach ( $post_types as $pt ) {
			$post_object = get_post_type_object( $pt );
			$section = $this->plugin_slug . '_' . $pt;
			if ( false == get_option( $section ) ) {
				add_option( $section, apply_filters( $section . '_default_settings', $defaults ) );
			}
			$args = array( $section, get_option( $section ) );


			// CONTENT REQUIREMENTS
			// section
			add_settings_section(
				$pt,
				__( 'Default Content Requirements', 'aptrc' ) .':',
				'',
				$section
			);

			// title field
			if ( post_type_supports( $pt, 'title' )) {
				add_settings_field(
					'title_check',
					__( 'Title', 'aptrc' ) . ':',
					array( $this, 'title_check_callback' ),
					$section,
					$pt,
					$args
				);
			}

			// wysiwyg editor
			if ( post_type_supports( $pt, 'editor' )) {
				add_settings_field(
					'editor_check',
					__( 'WYSIWYG Editor', 'aptrc' ) .':',
					array( $this, 'editor_check_callback' ),
					$section,
					$pt,
					$args
				);
			}

			// featured image
			if ( post_type_supports( $pt, 'thumbnail' )) {
				add_settings_field(
					'thumbnail_check',
					__( 'Featured Image', 'aptrc' ) .':',
					array( $this, 'thumbnail_check_callback' ),
					$section,
					$pt,
					$args
				);
			}

			// excerpt
			if ( post_type_supports( $pt, 'excerpt' ) ) {
				add_settings_field(
					'excerpt_check',
					__( 'Excerpt', 'aptrc' ) .':',
					array( $this, 'excerpt_check_callback' ),
					$section,
					$pt,
					$args
				);
			}


			// TAXONOMY REQUIREMENTS
			if ( 'page' != $pt) {  // pages don't have taxonomies
				$objtaxs = get_object_taxonomies( $pt );
				if ( !( $objtaxs == null ) ) {
					// section
					add_settings_section(
						'tax_' . $pt,
						'<hr>' . __( 'Taxonomy Requirements', 'aptrc' ) .':',
						'',
						$section
					);
					// taxonomy tip
					add_settings_field(
						'CatTagTip',
						'',
						array( $this, 'CatTagTip' ),
						$section,
						'tax_' . $pt,
						$args
					);
				}
			}

			// category
			if ( is_object_in_taxonomy( $pt, 'category' ) ) {
				add_settings_field(
					'categories_check',
					__( 'Categories', 'aptrc' ) .':',
					array( $this, 'categories_check_callback' ),
					$section,
					'tax_' . $pt,
					$args
				);
				// minimum
				add_settings_field(
					'categories_dropdown',
					'',
					array( $this, 'categories_dropdown_callback' ),
					$section,
					'tax_' . $pt,
					$args
				);
				// maximum
				add_settings_field(
					'categories_max_dropdown',
					'',
					array( $this, 'categories_max_dropdown_callback' ),
					$section,
					'tax_' . $pt,
					$args
				);
			}

			// tag
			if ( is_object_in_taxonomy( $pt, 'post_tag' ) ) {
				add_settings_field(
					'tags_check',
					__( 'Tags', 'aptrc' ) .':',
					array( $this, 'tags_check_callback' ),
					$section,
					'tax_' . $pt,
					$args
				);
				// minimum
				add_settings_field(
					'tags_dropdown',
					'',
					array( $this, 'tags_dropdown_callback' ),
					$section,
					'tax_' . $pt,
					$args
				);
				// maximum
				add_settings_field(
					'tags_max_dropdown',
					'',
					array( $this, 'tags_max_dropdown_callback' ),
					$section,
					'tax_' . $pt,
					$args
				);
			}	

			// CUSTOM TAXONOMIES
			// get all taxonomies in a post type
			$argums = array(
			    'public'   => true,
			    '_builtin' => false
			); 
			$outputs = 'names'; // or objects
			$operators = 'and'; // 'and' or 'or'
			$taxonomy_names = get_taxonomies( $argums, $outputs, $operators );
			$x = '1';
			foreach ( $taxonomy_names as $tn ) {

				// get that taxonomy's objects (so we can output the label later for plural name)
				$thingargums = array(
				  'name' => $tn
				);
				$thingoutputs = 'objects'; // or names
				$things = get_taxonomies( $thingargums, $thingoutputs ); 

				foreach ($things as $thing ) {

					if ( is_object_in_taxonomy( $pt, $tn ) ) {
						// categories are hierarchical
						if ( is_taxonomy_hierarchical( $tn ) ) {
							add_settings_field(
								'hierarchical_check_'.$x,
								$thing->label .' <span>(' . __('category', 'aptrc' ) .'):</span>',
								array( $this, 'hierarchical_check_callback_'.$x ),
								$section,
								'tax_' . $pt,
								$args
							);
							// minimum
							add_settings_field(
								'hierarchical_dropdown_'.$x,
								'',
								array( $this, 'hierarchical_dropdown_callback_'.$x ),
								$section,
								'tax_' . $pt,
								$args
							);
							// maximum
							add_settings_field(
								'hierarchical_max_dropdown_'.$x,
								'',
								array( $this, 'hierarchical_max_dropdown_callback_'.$x ),
								$section,
								'tax_' . $pt,
								$args
							);
						}

						// tags are flat
						else {
							add_settings_field(
								'flat_check'.$x,
								$thing->label .' <span>(' . __('tag', 'aptrc' ) .'):</span>',
								array( $this, 'flat_check_callback_'.$x ),
								$section,
								'tax_' . $pt,
								$args
							);
							// minimum
							add_settings_field(
								'flat_dropdown'.$x,
								'',
								array( $this, 'flat_dropdown_callback_'.$x ),
								$section,
								'tax_' . $pt,
								$args
							);
							// maximum
							add_settings_field(
								'flat_max_dropdown_'.$x,
								'',
								array( $this, 'flat_max_dropdown_callback_'.$x ),
								$section,
								'tax_' . $pt,
								$args
							);
						}

					}

				}

				$x++;	// advance				
			}



			// * @since 2.3
			// CUSTOM FIELD REQUIREMENTS
/*
			// get a random post from this post type (we're still in the post type loop)	
			$field_post_args = array(
			    'post_type' => $pt,  // look in our post type (from the loop)
			    'posts_per_page' => 1,  // get a random post (should have custom fields for our post type)
		    );

		    $id_ptrc_posts = get_posts( $field_post_args );
		    foreach ( $id_ptrc_posts as $post ) {
		    	
		        $custom_post_id = $post->ID;  // get our random post's ID			
				$getFieldsCustom = get_post_custom_values( $key = '', $post_id = $custom_post_id );  // get array of custom fields in this post type  * sort of *

				// trim key values that are internal to WP
				unset( $getFieldsCustom['_edit_lock'] );
				unset( $getFieldsCustom['_edit_last'] );
				// unset( $getFieldsCustom['_mini_post'] );
				unset( $getFieldsCustom['_thumbnail_id'] );
				
				if ( !( $getFieldsCustom == null ) ) {  // if our post type has any custom fields...

					// section
					add_settings_section(
						'fields_' . $pt,
						'<hr>' . __( 'Custom Field Requirements', 'aptrc' ) .':',
						'',
						$section
					);
					// custom fields tip
					add_settings_field(
						'FieldsTip',
						__( '', 'aptrc' ),
						array( $this, 'FieldsTip' ),
						$section,
						'fields_' . $pt,
						$args
					);

					foreach ( $getFieldsCustom as $ptrc_cf ) {

						add_settings_field(
							'fields' . $ptrc_cf,
							__( $ptrc_cf . ':', 'aptrc' ),
							array( $this, '' . $ptrc_cf ),
							$section,
							'fields_' . $pt,
							$args
						);

					}

				}
			}
*/

			// section
			add_settings_section(
				'3rdparty' . $pt,
				'<hr>' . __( '3rd Party Plugin Support', 'aptrc' ) .':',
				'',
				$section
			);

			// * @since 2.3
			// 3RD PARTY PLUGIN SUPPORT

			// WP SEO by Yoast
			if (class_exists('WPSEO_Utils')) {	
				// focus keyword
				add_settings_field(
					'yoastseo_focus_keyword',
					__( 'WordPress SEO by Yoast', 'aptrc' ) .':',
					array( $this, 'yoastseo_focus_keyword_callback' ),
					$section,
					'3rdparty' . $pt,
					$args
				);
				// meta description
				add_settings_field(
					'yoastseo_meta_description',
					'',
					array( $this, 'yoastseo_meta_description_callback' ),
					$section,
					'3rdparty' . $pt,
					$args
				);
			}



			// All In One SEO
			if (class_exists('All_in_One_SEO_Pack')) {	
				// title
				add_settings_field(
					'allinone_title',
					__( 'All In One SEO Pack', 'aptrc' ) .':',
					array( $this, 'allinone_title_callback' ),
					$section,
					'3rdparty' . $pt,
					$args
				);
				// description
				add_settings_field(
					'allinone_description',
					'',
					array( $this, 'allinone_description_callback' ),
					$section,
					'3rdparty' . $pt,
					$args
				);
				// keywords
				add_settings_field(
					'allinone_keywords',
					'',
					array( $this, 'allinone_keywords_callback' ),
					$section,
					'3rdparty' . $pt,
					$args
				);
			}


			register_setting(
				$section,
				$section
			);

		}

	} // end admin_init

// TITLE
	public function title_check_callback( $args ) {
		$output = $args[0].'[title_check]';
		$value  = isset( $args[1]['title_check'] ) ? $args[1]['title_check'] : '';

		$checkhtml = '<input type="checkbox" id="title_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="title_check"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} // end 

// EDITOR
	public function editor_check_callback( $args ) {
		$output = $args[0].'[editor_check]';
		$value  = isset( $args[1]['editor_check'] ) ? $args[1]['editor_check'] : '';

		$checkhtml = '<input type="checkbox" id="editor_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="editor_check"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} // end 

// FEATURED IMAGE
	public function thumbnail_check_callback( $args ) {
		$output = $args[0].'[thumbnail_check]';
		$value  = isset( $args[1]['thumbnail_check'] ) ? $args[1]['thumbnail_check'] : '';

		$checkhtml = '<input type="checkbox" id="thumbnail_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="thumbnail_check"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} // end 

// EXCERPT
	public function excerpt_check_callback( $args ) {
		$output = $args[0].'[excerpt_check]';
		$value  = isset( $args[1]['excerpt_check'] ) ? $args[1]['excerpt_check'] : '';

		$checkhtml = '<input type="checkbox" id="excerpt_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="excerpt_check"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} // end 

	public function CatTagTip( $args ) {
		$html = '<div id="toggle"><p>' . __( 'As a general rule, a post should have a maximum of 3 categories and no more than 30 combined Categories, Tags and Custom Taxonomies - both for SEO value and to keep post creation/load time to a minimum.  Requirements Checklist allows for more only to accommodate large sites with previously added taxonomies.', 'aptrc' ) . '</p></div>';
	    echo $html;
	}

// CATEGORIES
	public function categories_check_callback( $args ) {
		$output = $args[0].'[categories_check]';
		$value  = isset( $args[1]['categories_check'] ) ? $args[1]['categories_check'] : '';

		$checkhtml = '<input type="checkbox" id="categories_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="categories_check"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	}

		public function categories_dropdown_callback( $args ) {
			$output = $args[0].'[categories_dropdown]';
			$value  = isset( $args[1]['categories_dropdown'] ) ? $args[1]['categories_dropdown'] : '';

			$html = '<select id="categories_dropdown" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="categories_dropdown"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';	     
	    	echo $html;
		} 

		public function categories_max_dropdown_callback( $args ) {
			$output = $args[0].'[categories_max_dropdown]';
			$value  = isset( $args[1]['categories_max_dropdown'] ) ? $args[1]['categories_max_dropdown'] : '';

			$html = '<select id="categories_max_dropdown" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="categories_max_dropdown"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';	
	    	echo $html;
		} // end

// TAGS
	public function tags_check_callback( $args ) {
		$output = $args[0].'[tags_check]';
		$value  = isset( $args[1]['tags_check'] ) ? $args[1]['tags_check'] : '';

		$checkhtml = '<input type="checkbox" id="tags_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="tags_check"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} // end tags_check_callback

		public function tags_dropdown_callback( $args ) {
			$output = $args[0].'[tags_dropdown]';
			$value  = isset( $args[1]['tags_dropdown'] ) ? $args[1]['tags_dropdown'] : '';

			$html = '<select id="tags_dropdown" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
	        $html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="tags_dropdown"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';	     
	    	echo $html;
		} //

		public function tags_max_dropdown_callback( $args ) {
			$output = $args[0].'[tags_max_dropdown]';
			$value  = isset( $args[1]['tags_max_dropdown'] ) ? $args[1]['tags_max_dropdown'] : '';

			$html = '<select id="tags_max_dropdown" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
	        $html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	        $html .= '<option value="7"' . selected( 7, $value, false) . '>7</option>';
	        $html .= '<option value="10"' . selected( 10, $value, false) . '>10</option>';
	        $html .= '<option value="15"' . selected( 15, $value, false) . '>15</option>';
	        $html .= '<option value="25"' . selected( 25, $value, false) . '>25</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="tags_max_dropdown"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';	       
	    	echo $html;
		} // end 

	
// CUSTOM TAX 1
	public function hierarchical_check_callback_1( $args ) {
		$output = $args[0].'[hierarchical_check_1]';
		$value  = isset( $args[1]['hierarchical_check_1'] ) ? $args[1]['hierarchical_check_1'] : '';

		$checkhtml = '<input type="checkbox" id="hierarchical_check_1" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="hierarchical_check_1"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	}

		public function hierarchical_dropdown_callback_1( $args ) {
			$output = $args[0].'[hierarchical_dropdown_1]';
			$value  = isset( $args[1]['hierarchical_dropdown_1'] ) ? $args[1]['hierarchical_dropdown_1'] : '';

			$html = '<select id="hierarchical_dropdown_1" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="hierarchical_dropdown_1"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';		     
	    	echo $html;
		} 

		public function hierarchical_max_dropdown_callback_1( $args ) {
			$output = $args[0].'[hierarchical_max_dropdown_1]';
			$value  = isset( $args[1]['hierarchical_max_dropdown_1'] ) ? $args[1]['hierarchical_max_dropdown_1'] : '';

			$html = '<select id="hierarchical_max_dropdown_1" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="hierarchical_max_dropdown_1"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';  
	    	echo $html;
		} // end

	public function flat_check_callback_1( $args ) {
		$output = $args[0].'[flat_check_1]';
		$value  = isset( $args[1]['flat_check_1'] ) ? $args[1]['flat_check_1'] : '';

		$checkhtml = '<input type="checkbox" id="flat_check_1" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="flat_check_1"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} 

		public function flat_dropdown_callback_1( $args ) {
			$output = $args[0].'[flat_dropdown_1]';
			$value  = isset( $args[1]['flat_dropdown_1'] ) ? $args[1]['flat_dropdown_1'] : '';

			$html = '<select id="flat_dropdown_1" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
        	$html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="flat_dropdown_1"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';	     
	    	echo $html;
		} // 

		public function flat_max_dropdown_callback_1( $args ) {
			$output = $args[0].'[flat_max_dropdown_1]';
			$value  = isset( $args[1]['flat_max_dropdown_1'] ) ? $args[1]['flat_max_dropdown_1'] : '';

			$html = '<select id="flat_max_dropdown_1" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
	        $html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	        $html .= '<option value="7"' . selected( 7, $value, false) . '>7</option>';
	        $html .= '<option value="10"' . selected( 10, $value, false) . '>10</option>';
	        $html .= '<option value="15"' . selected( 15, $value, false) . '>15</option>';
	        $html .= '<option value="25"' . selected( 25, $value, false) . '>25</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="flat_max_dropdown_1"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';	    
	    	echo $html;
		} // end 

// CUSTOM TAX 2
	public function hierarchical_check_callback_2( $args ) {
		$output = $args[0].'[hierarchical_check_2]';
		$value  = isset( $args[1]['hierarchical_check_2'] ) ? $args[1]['hierarchical_check_2'] : '';

		$checkhtml = '<input type="checkbox" id="hierarchical_check_2" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="hierarchical_check_2"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} 

		public function hierarchical_dropdown_callback_2( $args ) {
			$output = $args[0].'[hierarchical_dropdown_2]';
			$value  = isset( $args[1]['hierarchical_dropdown_2'] ) ? $args[1]['hierarchical_dropdown_2'] : '';

			$html = '<select id="hierarchical_dropdown_2" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="hierarchical_dropdown_2"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';	     
	    	echo $html;
		} 

		public function hierarchical_max_dropdown_callback_2( $args ) {
			$output = $args[0].'[hierarchical_max_dropdown_2]';
			$value  = isset( $args[1]['hierarchical_max_dropdown_2'] ) ? $args[1]['hierarchical_max_dropdown_2'] : '';

			$html = '<select id="hierarchical_max_dropdown_2" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="hierarchical_max_dropdown_2"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';	
	    	echo $html;
		} // end

	public function flat_check_callback_2( $args ) {
		$output = $args[0].'[flat_check_2]';
		$value  = isset( $args[1]['flat_check_2'] ) ? $args[1]['flat_check_2'] : '';

		$checkhtml = '<input type="checkbox" id="flat_check_2" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="flat_check_2"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} 

		public function flat_dropdown_callback_2( $args ) {
			$output = $args[0].'[flat_dropdown_2]';
			$value  = isset( $args[1]['flat_dropdown_2'] ) ? $args[1]['flat_dropdown_2'] : '';

			$html = '<select id="flat_dropdown_2" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
        	$html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="flat_dropdown_2"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';	     
	    	echo $html;
		} //

		public function flat_max_dropdown_callback_2( $args ) {
			$output = $args[0].'[flat_max_dropdown_2]';
			$value  = isset( $args[1]['flat_max_dropdown_2'] ) ? $args[1]['flat_max_dropdown_2'] : '';

			$html = '<select id="flat_max_dropdown_2" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
	        $html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	        $html .= '<option value="7"' . selected( 7, $value, false) . '>7</option>';
	        $html .= '<option value="10"' . selected( 10, $value, false) . '>10</option>';
	        $html .= '<option value="15"' . selected( 15, $value, false) . '>15</option>';
	        $html .= '<option value="25"' . selected( 25, $value, false) . '>25</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="flat_max_dropdown_2"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';     
	    	echo $html;
		} // end 

// CUSTOM TAX 3
	public function hierarchical_check_callback_3( $args ) {
		$output = $args[0].'[hierarchical_check_3]';
		$value  = isset( $args[1]['hierarchical_check_3'] ) ? $args[1]['hierarchical_check_3'] : '';

		$checkhtml = '<input type="checkbox" id="hierarchical_check_3" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="hierarchical_check_3"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} 

		public function hierarchical_dropdown_callback_3( $args ) {
			$output = $args[0].'[hierarchical_dropdown_3]';
			$value  = isset( $args[1]['hierarchical_dropdown_3'] ) ? $args[1]['hierarchical_dropdown_3'] : '';

			$html = '<select id="hierarchical_dropdown_3" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="hierarchical_dropdown_3"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';	     
	    	echo $html;
		} 

		public function hierarchical_max_dropdown_callback_3( $args ) {
			$output = $args[0].'[hierarchical_max_dropdown_3]';
			$value  = isset( $args[1]['hierarchical_max_dropdown_3'] ) ? $args[1]['hierarchical_max_dropdown_3'] : '';

			$html = '<select id="hierarchical_max_dropdown_3" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="hierarchical_max_dropdown_3"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';	  
	    	echo $html;
		} // end

	public function flat_check_callback_3( $args ) {
		$output = $args[0].'[flat_check_3]';
		$value  = isset( $args[1]['flat_check_3'] ) ? $args[1]['flat_check_3'] : '';

		$checkhtml = '<input type="checkbox" id="flat_check_3" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="flat_check_3"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} 

		public function flat_dropdown_callback_3( $args ) {
			$output = $args[0].'[flat_dropdown_3]';
			$value  = isset( $args[1]['flat_dropdown_3'] ) ? $args[1]['flat_dropdown_3'] : '';

			$html = '<select id="flat_dropdown_3" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
        	$html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="flat_dropdown_3"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';	     
	    	echo $html;
		} // 

		public function flat_max_dropdown_callback_3( $args ) {
			$output = $args[0].'[flat_max_dropdown_3]';
			$value  = isset( $args[1]['flat_max_dropdown_3'] ) ? $args[1]['flat_max_dropdown_3'] : '';

			$html = '<select id="flat_max_dropdown_3" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
	        $html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	        $html .= '<option value="7"' . selected( 7, $value, false) . '>7</option>';
	        $html .= '<option value="10"' . selected( 10, $value, false) . '>10</option>';
	        $html .= '<option value="15"' . selected( 15, $value, false) . '>15</option>';
	        $html .= '<option value="25"' . selected( 25, $value, false) . '>25</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="flat_max_dropdown_3"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';	   
	    	echo $html;
		} // end 

// CUSTOM TAX 4
	public function hierarchical_check_callback_4( $args ) {
		$output = $args[0].'[hierarchical_check_4]';
		$value  = isset( $args[1]['hierarchical_check_4'] ) ? $args[1]['hierarchical_check_4'] : '';

		$checkhtml = '<input type="checkbox" id="hierarchical_check_4" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="hierarchical_check_4"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} 

		public function hierarchical_dropdown_callback_4( $args ) {
			$output = $args[0].'[hierarchical_dropdown_4]';
			$value  = isset( $args[1]['hierarchical_dropdown_4'] ) ? $args[1]['hierarchical_dropdown_4'] : '';

			$html = '<select id="hierarchical_dropdown_4" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="hierarchical_dropdown_4"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';	     
	    	echo $html;
		} 

		public function hierarchical_max_dropdown_callback_4( $args ) {
			$output = $args[0].'[hierarchical_max_dropdown_4]';
			$value  = isset( $args[1]['hierarchical_max_dropdown_4'] ) ? $args[1]['hierarchical_max_dropdown_4'] : '';

			$html = '<select id="hierarchical_max_dropdown_4" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="hierarchical_max_dropdown_4"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';   
	    	echo $html;
		} // end

	public function flat_check_callback_4( $args ) {
		$output = $args[0].'[flat_check_4]';
		$value  = isset( $args[1]['flat_check_4'] ) ? $args[1]['flat_check_4'] : '';

		$checkhtml = '<input type="checkbox" id="flat_check_4" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="flat_check_4"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} 

		public function flat_dropdown_callback_4( $args ) {
			$output = $args[0].'[flat_dropdown_4]';
			$value  = isset( $args[1]['flat_dropdown_4'] ) ? $args[1]['flat_dropdown_4'] : '';

			$html = '<select id="flat_dropdown_3" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
        	$html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="flat_dropdown_4"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';	     
	    	echo $html;
		} // 

		public function flat_max_dropdown_callback_4( $args ) {
			$output = $args[0].'[flat_max_dropdown_4]';
			$value  = isset( $args[1]['flat_max_dropdown_4'] ) ? $args[1]['flat_max_dropdown_4'] : '';

			$html = '<select id="flat_max_dropdown_4" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
	        $html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	        $html .= '<option value="7"' . selected( 7, $value, false) . '>7</option>';
	        $html .= '<option value="10"' . selected( 10, $value, false) . '>10</option>';
	        $html .= '<option value="15"' . selected( 15, $value, false) . '>15</option>';
	        $html .= '<option value="25"' . selected( 25, $value, false) . '>25</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="flat_max_dropdown_4"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';	    
	    	echo $html;
		} // end 

// CUSTOM TAX 5
	public function hierarchical_check_callback_5( $args ) {
		$output = $args[0].'[hierarchical_check_5]';
		$value  = isset( $args[1]['hierarchical_check_5'] ) ? $args[1]['hierarchical_check_5'] : '';

		$checkhtml = '<input type="checkbox" id="hierarchical_check_5" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="hierarchical_check_5"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} 

		public function hierarchical_dropdown_callback_5( $args ) {
			$output = $args[0].'[hierarchical_dropdown_5]';
			$value  = isset( $args[1]['hierarchical_dropdown_5'] ) ? $args[1]['hierarchical_dropdown_5'] : '';

			$html = '<select id="hierarchical_dropdown_5" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="hierarchical_dropdown_5"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';	     
	    	echo $html;
		} 

		public function hierarchical_max_dropdown_callback_5( $args ) {
			$output = $args[0].'[hierarchical_max_dropdown_5]';
			$value  = isset( $args[1]['hierarchical_max_dropdown_5'] ) ? $args[1]['hierarchical_max_dropdown_5'] : '';

			$html = '<select id="hierarchical_max_dropdown_5" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="hierarchical_max_dropdown_5"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';	  
	    	echo $html;
		} // end

	public function flat_check_callback_5( $args ) {
		$output = $args[0].'[flat_check_5]';
		$value  = isset( $args[1]['flat_check_5'] ) ? $args[1]['flat_check_5'] : '';

		$checkhtml = '<input type="checkbox" id="flat_check_5" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="flat_check_5"> ' . __( 'require', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} 

		public function flat_dropdown_callback_5( $args ) {
			$output = $args[0].'[flat_dropdown_5]';
			$value  = isset( $args[1]['flat_dropdown_5'] ) ? $args[1]['flat_dropdown_5'] : '';

			$html = '<select id="flat_dropdown_5" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
        	$html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown" for="flat_dropdown_5"> ' . __( 'minimum required to publish', 'aptrc' ) . '</label>';	     
	    	echo $html;
		} // 

		public function flat_max_dropdown_callback_5( $args ) {
			$output = $args[0].'[flat_max_dropdown_5]';
			$value  = isset( $args[1]['flat_max_dropdown_callback_5'] ) ? $args[1]['flat_max_dropdown_callback_5'] : '';

			$html = '<select id="flat_max_dropdown_5" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
	        $html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	        $html .= '<option value="7"' . selected( 7, $value, false) . '>7</option>';
	        $html .= '<option value="10"' . selected( 10, $value, false) . '>10</option>';
	        $html .= '<option value="15"' . selected( 15, $value, false) . '>15</option>';
	        $html .= '<option value="25"' . selected( 25, $value, false) . '>25</option>';
	        $html .= '<option value="1000"' . selected( 1000, $value, false) . '>&#8734;</option>';
	    	$html .= '</select>';
	    	$html .= '<label class="dropdown max" for="flat_max_dropdown_5"> ' . __( 'maximum allowed', 'aptrc' ) . '</label>';		     
	    	echo $html;
		} // end 

	public function FieldsTip( $args ) {
		$html = '<div id="toggle"><p>' . __( 'Due to the way 3rd party plugins handle per post requirements and conditional logic for custom fields, requirements should be set from those plugins\' settings pages.  For custom fields that don\'t use logic controlled by a 3rd party plugin, or for hard-coded custom fields, requirements can be set by entering the field slug in the space provided.
			', 'aptrc' ) . '</p></div>';
	    echo $html;
	}

// WORDPRESS SEO BY YOAST
	public function yoastseo_focus_keyword_callback( $args ) {
		$output = $args[0].'[yoastseo_focus_keyword]';
		$value  = isset( $args[1]['yoastseo_focus_keyword'] ) ? $args[1]['yoastseo_focus_keyword'] : '';

		$checkhtml = '<input type="checkbox" id="yoastseo_focus_keyword" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="yoastseo_focus_keyword"> ' . __( 'require Focus Keyword', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} // end 

	public function yoastseo_meta_description_callback( $args ) {
		$output = $args[0].'[yoastseo_meta_description]';
		$value  = isset( $args[1]['yoastseo_meta_description'] ) ? $args[1]['yoastseo_meta_description'] : '';

		$checkhtml = '<input type="checkbox" id="yoastseo_meta_description" class="check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label class="check" for="yoastseo_meta_description"> ' . __( 'require Meta Description', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} // end 

// All In One SEO Pack
	public function allinone_title_callback( $args ) {
		$output = $args[0].'[allinone_title]';
		$value  = isset( $args[1]['allinone_title'] ) ? $args[1]['allinone_title'] : '';

		$checkhtml = '<input type="checkbox" id="allinone_title" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="allinone_title"> ' . __( 'require Title', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} // end 

	public function allinone_description_callback( $args ) {
		$output = $args[0].'[allinone_description]';
		$value  = isset( $args[1]['allinone_description'] ) ? $args[1]['allinone_description'] : '';

		$checkhtml = '<input type="checkbox" id="allinone_description" class="check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label class="check" for="allinone_description"> ' . __( 'require Description', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} // end 

	public function allinone_keywords_callback( $args ) {
		$output = $args[0].'[allinone_keywords]';
		$value  = isset( $args[1]['allinone_keywords'] ) ? $args[1]['allinone_keywords'] : '';

		$checkhtml = '<input type="checkbox" id="allinone_keywords" class="check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label class="check" for="allinone_keywords"> ' . __( 'require Keywords', 'aptrc' ) . '</label>';
		echo $checkhtml;
	} // end 

}
post_type_requirements_checklist_settings::get_instance();
