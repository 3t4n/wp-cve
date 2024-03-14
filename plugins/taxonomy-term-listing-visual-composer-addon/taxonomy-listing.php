<?php
/**
 * Taxonomy term list add-on
 *
 * @package WordPress
 * @subpackage Visual_Composer
 */

// If check `Taxonomy_Term_Addon` class exists OR not.
if ( ! class_exists( 'Taxonomy_Term_Addon' ) ) {

	/**
	 * Declare Taxonomy_Term_Addon class
	 */
	class Taxonomy_Term_Addon {

		/**
		 * Instance.
		 *
		 * @var $instance
		 */
		private static $_instance = null;

		/**
		 * Constructor for the taxonomy term listing. Loads options and hooks in the init method.
		 */
		public function __construct() {
			// Visual composer before init.
			add_action( 'vc_before_init', array( $this, 'termlisting' ) );
			// Add new field on visual composer.
			vc_add_shortcode_param( 'Taxonomy_Names', array( $this, 'taxonomy_name_settings_field' ) );
			vc_add_shortcode_param( 'include_child_category', array( $this, 'include_child_settings_field' ) );
			vc_add_shortcode_param( 'count_display', array( $this, 'count_display_settings_field' ) );
			vc_add_shortcode_param( 'Hide_empty', array( $this, 'hide_empty_settings_field' ) );
			vc_add_shortcode_param( 'specific_subcategory', array( $this, 'specific_subcategory_settings_field' ) );
			vc_add_shortcode_param( 'include_parent_category', array( $this, 'include_parent_category_settings_field' ) );
			// Register taxonomy shortcode.
			add_shortcode( 'taxonomy_term', array( $this, 'display_term_listing' ) );
			// Get taxonomy term ID.
			add_action( 'wp_ajax_get_taxonomy_term_id', array( $this, 'get_taxonomy_term_id' ) );
			add_action( 'wp_ajax_nopriv_get_taxonomy_term_id', array( $this, 'get_taxonomy_term_id' ) );
		}

		/**
		 * Standard singleton pattern.
		 *
		 * @return Returns the current plugin instance.
		 */
		public static function _instance() {
			if ( is_null( self::$_instance ) || ! ( self::$_instance instanceof self ) ) {
				self::$_instance = new self;
			}
			return self::$_instance;
		}

		/**
		 * Mapping term listing fields.
		 */
		public function termlisting() {
			vc_map(
				array(
					'name' => __( 'Taxonomy Term Listing', 'taxonomy-term-listing-visual-composer-addon' ),
					'base' => 'taxonomy_term',
					'icon' => plugin_dir_url( __FILE__ ) . 'images/icon-taxonomy-listing.png',
					'class' => '',
					'category' => 'Content',
					'params' => array(
						array(
							'type' => 'Taxonomy_Names',
							'holder' => 'div',
							'class' => '',
							'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
							'param_name' => 'taxonomy_names',
							'value' => '',
							'description' => __( 'Select desired taxonomy name', 'taxonomy-term-listing-visual-composer-addon' ),
						),
						array(
							'type' => 'dropdown',
							'class' => '',
							'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
							'param_name' => 'order',
							'value' => array(
								'Ascending' => 'ASC',
								'Descending' => 'DESC',
							),
							'description' => '',
						),
						array(
							'type' => 'include_child_category',
							'class' => '',
							'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
							'param_name' => 'include_subcategory',
							'value' => '',
							'description' => '',
							'admin_label' => 'false',
						),
						array(
							'type' => 'count_display',
							'class' => '',
							'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
							'param_name' => 'count',
							'value' => '',
							'description' => '',
							'admin_label' => 'false',
						),
						array(
							'type' => 'Hide_empty',
							'class' => '',
							'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
							'param_name' => 'hide_empty',
							'value' => '',
							'description' => '',
							'admin_label' => 'false',
						),
						array(
							'type' => 'specific_subcategory',
							'class' => '',
							'heading' => __( 'Enter Parent term Id', 'taxonomy-term-listing-visual-composer-addon' ),
							'param_name' => 'specific_subcategory',
							'value' => '',
							'description' => __( 'include any specific subcategory', 'taxonomy-term-listing-visual-composer-addon' ),
							'admin_label' => 'false',
						),
						array(
							'type' => 'include_parent_category',
							'class' => '',
							'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
							'param_name' => 'include_parent_category',
							'value' => '',
							'description' => __( 'include parent category name if specific category is selected', 'taxonomy-term-listing-visual-composer-addon' ),
							'admin_label' => 'false',
						),
						array(
							'type' => 'textfield',
							'class' => '',
							'heading' => __( 'Extra Class Name', 'taxonomy-term-listing-visual-composer-addon' ),
							'param_name' => 'extra_class_name',
							'value' => '',
							'description' => __( 'For styling any particular element','taxonomy-term-listing-visual-composer-addon' ),
							'admin_label' => 'false',
						),
					),
				)
			);
		}

		/**
		 * Register new setting fields.
		 *
		 * @param  array  $settings Component setting.
		 * @param  string $value    Field value.
		 * @return HTML|string
		 */
		public function taxonomy_name_settings_field( $settings, $value ) {
			$data = '<div class="taxonomy_name_list">' . '<select name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-input wpb-select">';
			$data .= '<option value="">' . __( 'Select Taxonomy', 'taxonomy-term-listing-visual-composer-addon' ) . '</option>';
			// Get post types.
			$post_types = get_post_types(
				array(
					'public' => true
				)
			);
			// Get taxonomies object by post type.
			foreach ( $post_types as $key => $post_type_name ) {
				$taxonomy_names = get_object_taxonomies( $post_type_name );
				foreach ( $taxonomy_names as $taxonomy_name ) {
					$data .= '<option value="' . $taxonomy_name . '"' . ( ( $taxonomy_name == $value ) ? 'selected' : '' ) . '>' . $taxonomy_name . '</option>';   
				}
			}
			$data .= '</select>' . '</div>';
			// End.
			?>
			<script>
				( function( $ ) {
					jQuery('.taxonomy_name_list select').change(function() {
						var taxonomyValue = {
							action: "get_taxonomy_term_id",
							postdata: jQuery('.taxonomy_name_list select').val()
						}
						jQuery.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", taxonomyValue, function( response ) {
							jQuery('.parent_id_list select').empty().append(response);        
						} ); 
					});
					if ( jQuery('.taxonomy_name_list select').val() != "" ) {
						var taxonomyValue1 = {
							action: "get_taxonomy_term_id",
							postdata_selected: jQuery('.taxonomy_name_list select').val(),
							postdata_termselected: jQuery('.parent_id_list select').val()
						}
						jQuery.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", taxonomyValue1, function( response ) {
							jQuery('.parent_id_list select').empty().append(response);  
						} ); 
					}
					
					jQuery('.vc_wrapper-param-type-include_parent_category').hide();

				} )( jQuery );
			</script>
			<?php
			return $data;
		}

		/**
		 * Get child fields.
		 *
		 * @param  array  $settings Component setting.
		 * @param  string $value    Field value.
		 * @return HTML|string
		 */
		public function include_child_settings_field( $settings, $value ) {
			$include_child_categories = '<div class="include-child"><input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value checkbox" type="checkbox" value="' . ( ( $value != "" ) ? $value : 1 ) . '" ' . ( $value == 1 ? checked : '' ) . '' . ( ( $value == "" ) ? checked : '' ) . ' >' . __( 'include Subcategory','taxonomy-term-listing-visual-composer-addon' ) . '</div>'; //phpcs:ignore
			?>
			<script>
				( function( $ ) {
					jQuery( 'input[name="include_subcategory"]' ).on( 'change', function() {
						this.value = this.checked ? 1 : 0 ;
					});
				} )( jQuery );
			</script>
			<?php
			return $include_child_categories;
		}

		/**
		 * Display count setting fields.
		 *
		 * @param  array  $settings Component setting.
		 * @param  string $value    Field value.
		 * @return HTML|string
		 */
		public function count_display_settings_field( $settings, $value ) {
			$include_count_display = '<div class="include-count"><input name="'. esc_attr( $settings['param_name'] ) .'" class="wpb_vc_param_value checkbox" type="checkbox" value="' . ( ( $value != "" ) ? $value : 1 ) . '" ' . ( $value == 1 ? checked : '' ) . '' . ( ( $value == "" ) ? checked : '' ) . ' >'  .__( 'show count', 'taxonomy-term-listing-visual-composer-addon' ) . '</div>'; //phpcs:ignore
			?>
			<script>
				( function( $ ) {
					jQuery( 'input[name="count"]' ).on( 'change', function() {
						this.value = this.checked ? 1 : 0 ;
					});
				} )( jQuery );
			</script>
			<?php
			return $include_count_display;
		}

		/**
		 * Hide empty fields.
		 *
		 * @param  array  $settings Component setting.
		 * @param  string $value    Field value.
		 * @return HTML|string
		 */
		public function hide_empty_settings_field( $settings, $value ){
			$hide_empty_cat = '<div class="hide_empty_main"><input name="'. esc_attr( $settings['param_name'] ) .'" class="wpb_vc_param_value checkbox" type="checkbox" value="' . ( ( $value != "" ) ? $value : 1 ) . '" ' . ( $value == 1 ? checked : '' ) . '' . ( ( $value == "" ) ? checked : '' ) . ' >' . __( 'Hide Empty Category', 'taxonomy-term-listing-visual-composer-addon' ) . '</div>'; //phpcs:ignore
			?>
			<script>
				( function( $ ) {
					jQuery( 'input[name="hide_empty"]' ).on( 'change', function() {
						this.value = this.checked ? 1 : 0 ;
					});
				} )( jQuery );
			</script>
			<?php
			return $hide_empty_cat;
		}

		/**
		 * Specific subcategory fields.
		 *
		 * @param  array  $settings Component setting.
		 * @param  string $value    Field value.
		 * @return HTML|string
		 */
		public function specific_subcategory_settings_field( $settings, $value ) {
			$specific_cat = '<div class="parent_id_list">' . '<select name="'. esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-input wpb-select">';
			$specific_cat .= '<option value="' . $value . '">' . __( 'Select Taxonomy first', 'taxonomy-term-listing-visual-composer-addon' ) . '</option>';
			$specific_cat .= '</select>' . '</div>'; ?>
			<script>
				( function( $ ){
					if ( jQuery('.parent_id_list select').val() != "" ) {

						jQuery('.vc_wrapper-param-type-include_parent_category').show();
					}
					jQuery( 'select[name="specific_subcategory"]' ).on( 'change', function() {
						if (jQuery(this).val() != ''){
							jQuery('.vc_wrapper-param-type-include_parent_category').show();
						}else{
							jQuery('.vc_wrapper-param-type-include_parent_category').hide();
						}
					} )
				} )( jQuery );
			</script>
			<?php
			return $specific_cat;
		}


		/**
		 * Include parent category name while selecting specific category.
		 *
		 * @param  array  $settings Component setting.
		 * @param  string $value    Field value.
		 * @return HTML|string
		 */
		public function include_parent_category_settings_field( $settings, $value ) {
			$include_parent_cat = '<div class="ttlvca_include_parent_main"> <input name="'. esc_attr( $settings['param_name'] ) .'" class="wpb_vc_param_value checkbox" type="checkbox" value="' . ( ( $value != "" ) ? $value : 1 ) . '" ' . ( $value == 1 ? checked : '' ) . '' . ( ( $value == "" ) ? checked : '' ) . ' >' . __( 'Include Parent Category', 'taxonomy-term-listing-visual-composer-addon' ) . '</div>'; //phpcs:ignore
			?>
			<script>
				( function( $ ) {
					jQuery( 'input[name="include_parent_category"]' ).on( 'change', function() {
						this.value = this.checked ? 1 : 0 ;
					});
				} )( jQuery );
			</script>
			<?php
			return $include_parent_cat;
		}


		/**
		 * Display term listing.
		 *
		 * @param  array $atts Component attribute.
		 * @return [type]       [description]
		 */
		public function display_term_listing( $atts ) {
			$specific_subcategory = isset( $atts['specific_subcategory'] ) && $atts['specific_subcategory'] != '' ? $atts['specific_subcategory'] : 0;
			$order_attr = ( isset( $atts['order'] ) ? $atts['order'] : 'ASC' );
			$taxonomy_names_attr = ( isset( $atts['taxonomy_names'] ) ? $atts['taxonomy_names'] : NULL );
			$class = ( isset( $atts['extra_class_name'] ) ? "class = '" . $atts['extra_class_name'] . "'" : '' );
				$arguments = array(
					'hide_empty' => $atts['hide_empty'],
					'order' => $order_attr,
					'parent' => 0,
				);
				$response = '';
				$response = '<div class="vc_taxonomy_listing">';
				$response .= '<ul ' . $class . '>';
				if ( ( isset( $atts['specific_subcategory'] ) && $atts['specific_subcategory'] != '' ) || $atts['include_subcategory'] == 1 ) {
					if ( isset( $atts['specific_subcategory'] ) && $atts['specific_subcategory'] != '' ) {
						if ( isset ( $atts['include_parent_category'] ) && $atts['include_parent_category'] == 1 ) {
							$specific_cat_obj = get_term_by('id', $specific_subcategory, $taxonomy_names_attr);
							$response .= '<li class="vc_taxonomy_specific_cat_title"><a href="' . get_term_link( $specific_cat_obj->term_id ) . '">' . $specific_cat_obj->name . '</a>';
							$response .= '<ul class="vc_taxonomy_parent_cat">';
						}
						$arguments = array(
							'hide_empty' => $atts['hide_empty'],
							'order' => $order_attr,
							'parent' => $specific_subcategory,
						);
					}
					$term = get_terms( $taxonomy_names_attr, $arguments );
					foreach ( $term as $terms ) {
						$response .= '<li><a href="' . get_term_link( $terms->term_id ) . '">' . $terms->name . (  $atts['count'] == 1 ? '(' . $terms->count . ')' : '' ) . '</a>';
						if ( isset( $atts['specific_subcategory'] ) && $atts['specific_subcategory'] != '' ? ( $terms->parent != 0 ) : ( $terms->parent == 0 ) ) {
							$arg_inner = array(
								'hide_empty' => $atts['hide_empty'],
								'order' => $order_attr,
								'parent' => $terms->term_id,
							);
							$child_terms = get_terms( $taxonomy_names_attr, $arg_inner );
							if ( ! empty( $child_terms ) ) :
								$response .= '<ul class="vc_taxonomy_sub_cat_list">';
								foreach ( $child_terms as $child_term ) {
									$response .= '<li><a href="' . get_term_link( $child_term->term_id ) . '">' . $child_term->name.( $atts['count'] == 1 ? '(' . $child_term->count . ')' : '') . '</a>'; // phpcs:ignore
									if ( $child_term->parent != 0 ) { // phpcs:ignore
										$arg_inner_child = array(
											'hide_empty' => $atts['hide_empty'],
											'order' => $order_attr,
											'parent' => $child_term->term_id
										);
										$inner_child_terms = get_terms( $taxonomy_names_attr, $arg_inner_child );
										if ( ! empty( $inner_child_terms ) ) :
											$response .= '<ul class="vc_taxonomy_sub_cat_list vc_taxonomy_nested_list">';
											foreach ( $inner_child_terms as $inner_child_term ) {
												$response .= '<li><a href="' . get_term_link( $inner_child_term->term_id ) . '">' . $inner_child_term->name.( $atts['count'] == 1 ? '(' . $inner_child_term->count . ')' : '' ) . '</a></li>'; // phpcs:ignore
												}
											$response .= '</ul>';
										endif;
									}
									else {
										$response .= '</li>';  // 2 tier category
									}
								}
								$response .= '</ul></li>';
							else :
								$response .= '</li>';  // 1 tier category
							endif;
						}
					}
					if ( isset( $atts['specific_subcategory']) && $atts['specific_subcategory'] != '' && isset( $atts['include_parent_category'] ) && $atts['include_parent_category'] == 1 ) {
								$response .= '</ul></li>';
					}
				} else {
					$term = get_terms( $taxonomy_names_attr, $arguments );
					foreach ( $term as $terms ) {
						$response .= '<li><a href="' . get_term_link( $terms->term_id ) . '">' . $terms->name . ( $atts['count'] == 1 ? '(' . $terms->count . ')' : '') . '</a></li>';
					}
				}
				$response .= '</ul>';
				$response .= '</div>';
				return $response;
			}

		/**
		 * Ajax call for selection of parent term id.
		 */
		public function get_taxonomy_term_id() {
			global $wpdb;
			if ( isset( $_POST['postdata'] ) ) {
				$tax_name = sanitize_text_field( $_POST['postdata'] );
			} elseif ( isset( $_POST['postdata_selected'] ) ) {
				$tax_name = sanitize_text_field( $_POST['postdata_selected'] );
				$term_val = sanitize_text_field( $_POST['postdata_termselected'] );
			}
			$str = '';
			if ( ! empty( $tax_name ) ) {
				$arg = array(
					'taxonomy' => $tax_name
				);
				$terms = get_categories( $arg );
				if ( isset( $_POST['postdata'] ) || isset( $_POST['postdata_termselected'] ) ) {
					$str .= '<option value="">Select Term</option>';
				}
				foreach ( $terms as $term ) {
					if ( $term->parent == 0 ) {
						$str .= '<option value="' . $term->term_id . '" ' . ( $term->term_id == $term_val ? selected : '' ) . '>' . $term->name . '</option>';
					}
				}
			}
			echo $str;
			exit();
		}
	}
}
