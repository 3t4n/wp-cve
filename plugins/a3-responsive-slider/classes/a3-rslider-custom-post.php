<?php
namespace A3Rev\RSlider;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Custom_Post
{
	
	public static function register_post_type() {
		
		// Register custom taxonomy
		register_taxonomy( 'slider_folder',
				array( 'a3_slider' ),
				array(
					'hierarchical' 			=> true,
					'update_count_callback' => '_update_post_term_count',
					'label' 				=> __( 'Folders', 'a3-responsive-slider' ),
					'labels' => array(
							'name' 				=> __( 'Folders', 'a3-responsive-slider' ),
							'singular_name' 	=> __( 'Folder', 'a3-responsive-slider' ),
							'search_items' 		=> __( 'Search Folders', 'a3-responsive-slider' ),
							'popular_items'		=> __( 'Popular Folders', 'a3-responsive-slider' ),
							'parent_item'		=> __( 'Parent Folder', 'a3-responsive-slider' ),
							'parent_item_colon'	=> __( 'Parent Folder:', 'a3-responsive-slider' ),
							'all_items' 		=> __( 'All Folders', 'a3-responsive-slider' ),
							'edit_item' 		=> __( 'Edit Folder', 'a3-responsive-slider' ),
							'update_item' 		=> __( 'Update Folder', 'a3-responsive-slider' ),
							'add_new_item' 		=> __( 'Add New Folder', 'a3-responsive-slider' ),
							'new_item_name' 	=> __( 'New Folder Name', 'a3-responsive-slider' )
						),
					'show_ui' 				=> true,
					'query_var' 			=> true,
					'rewrite' 				=> array( 'with_front' => false, 'hierarchical' => false ),
				)
		);
		
		// Register custom post type
		$labels_array = array('name' => __( 'All Sliders', 'a3-responsive-slider' ),
							  'singular_name' => __( 'Slider', 'a3-responsive-slider' ),
							  'menu_name' => __( 'Responsive Slider', 'a3-responsive-slider' ),
							  'all_items' => __( 'All Sliders', 'a3-responsive-slider' ),
							  'add_new' => __( 'Add New Slider', 'a3-responsive-slider' ),
							  'add_new_item' => __( 'Add New Slider', 'a3-responsive-slider' ),
							  'edit' => __( 'Edit', 'a3-responsive-slider' ),
							  'edit_item' => __( 'Edit Slider', 'a3-responsive-slider' ),
							  'new_item' => __( 'New Slider', 'a3-responsive-slider' ),
							  'view' => __( 'View', 'a3-responsive-slider' ),
							  'view_item' => __( 'View Slider', 'a3-responsive-slider' ),
							  'search_items' => __( 'Search Sliders', 'a3-responsive-slider' ),
							  'not_found' => __( 'No Sliders Found', 'a3-responsive-slider' ),
							  'not_found_in_trash' => __( 'No Sliders found in Trash', 'a3-responsive-slider' ),
							  'parent' => __( 'Parent', 'a3-responsive-slider' )
							 );
	
		$supports_array = array('title',
								'editor',
								/*'excerpt',*/
								/*'trackbacks',*/
								/*'custom-fields',*/
								/*'comments',*/
								/*'revisions',*/
								'thumbnail',
								/*'author',*/
								'page-attributes'
							   );
		
		register_post_type( 'a3_slider',
							array('description' => __( 'Sliders Custom Post Type', 'a3-responsive-slider' ),
								  'public' => false,
								  'show_ui' => true,
								  'show_in_menu' => true,
								  'capability_type' => 'post',
								  'hierarchical' => false,
								  'rewrite' => array('slug' => 'slider'),
								  'query_var' => true,
								  'has_archive' => false,
								  '_builtin' => false,
								  'supports' => $supports_array,
								  'labels' => $labels_array,
								 ));
		
		if ( get_option('a3rev_rslider_just_installed') == 'yes' ) {
			flush_rewrite_rules();
		}
	}
	
	/* START : Custom column for Slider post type */
	
	public static function column_sql_orderby( $vars ) {
		if ( isset( $vars['post_type'] ) && 'a3_slider' == $vars['post_type'] && isset( $vars['orderby'] ) ) {
		
			switch ( $vars['orderby'] ) :
				case 'slider_skin' :
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_a3_slider_template',
							'orderby' => 'meta_value'
						)
					);
					break;
				default :
					$vars = array_merge(
						$vars,
						array(
							'orderby' => 'date menu_order title',
							'order'	  => 'DESC'
						)
					);
				endswitch;
			
		}
			
		return $vars;
	}
	
	public static function sortable_column_load() {
    	add_filter( 'request', array( __CLASS__, 'column_sql_orderby' ) );
	}
	
	public static function cats_restrict_manage_posts_print_terms( $taxonomy, $parent = 0, $level = 0 ){
		$prefix = str_repeat( '&nbsp;&nbsp;&nbsp;' , $level );
		$terms = get_terms( $taxonomy, array( 'parent' => $parent, 'hide_empty' => false ) );
		if ( !( $terms instanceof \WP_Error ) && !empty( $terms ) ) {
			foreach ( $terms as $term ){
				echo '<option value="'. $term->slug . '"', ( isset($_GET[$term->taxonomy]) && $_GET[$term->taxonomy] == $term->slug) ? ' selected="selected"' : '','>' . $prefix . $term->name .' (' . $term->count . ')</option>';
				self::cats_restrict_manage_posts_print_terms( $taxonomy, $term->term_id, $level+1 );
			}
		}
	}
	
	public static function cats_restrict_manage_posts() {
		global $typenow;
		if ( $typenow == 'a3_slider' ) {
			$filters = array( 'slider_folder' );

			foreach ( $filters as $tax_slug ) {
				// output html for taxonomy dropdown filter
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>" . __( 'Show all folders', 'a3-responsive-slider' ) . "</option>";
					self::cats_restrict_manage_posts_print_terms( $tax_slug );
				
				$the_query = new \WP_Query( array(
					'posts_per_page'	=> 1,
					'post_type'			=> 'a3_slider',
					'post_status'		=> array( 'publish', 'pending', 'draft' ),
					'tax_query'		=> array( 
						array(
							'taxonomy' => 'slider_folder',
	        				'field' => 'id',
							'terms' => get_terms( 'slider_folder', array( 'fields' => 'ids' ) ),
							'operator' => 'NOT IN'
						) ),
				) );
				wp_reset_postdata();
				if ( isset( $_GET['slider_folder'] ) && $_GET['slider_folder'] == '0' ) {
					echo "<option value='0' selected='selected'>" . __( 'Uncategorized', 'a3-responsive-slider' ) . " (".$the_query->found_posts.")</option>";	
				} else {
					echo "<option value='0'>" . __( 'Uncategorized', 'a3-responsive-slider' ) . " (".$the_query->found_posts.")</option>";
				}
				echo "</select>";
			}
			
			// output html for skins dropdown filter
			$slider_templates = Functions::slider_templates();
			echo "<select name='slider_skin' id='dropdown_slider_skin' class='postform'>";
			echo "<option value=''>" . __( 'Show all skins', 'a3-responsive-slider' ) . "</option>";
			foreach ( $slider_templates as $key => $val ) {
				if  ( $key == 'template-mobile' ) continue;
				$the_query = new \WP_Query( array(
					'posts_per_page'	=> 1,
					'post_type'			=> 'a3_slider',
					'post_status'		=> array( 'publish', 'pending', 'draft' ),
					'meta_query'		=> array( 
						array(
							'key'		=> '_a3_slider_template',
							'value'		=> $key,
						) ),
				) );
				wp_reset_postdata();
			?>
            	<option value="<?php echo esc_attr( $key ); ?>" <?php if ( isset( $_GET['slider_skin'] ) ) selected( sanitize_text_field( $_GET['slider_skin'] ), $key ); ?> ><?php echo $val; ?> (<?php echo $the_query->found_posts ; ?>)</option>
            <?php
			}
			echo "</select>";
		}
	}
	
	public static function slider_filters_query( $query ) {
		global $typenow, $wp_query;

	    if ( $typenow == 'a3_slider' ) {

	    	if ( isset( $_GET['slider_skin'] ) && trim( $_GET['slider_skin'] ) != '' ) {
		    	// Subtypes
				if ( ! isset( $query->query_vars['meta_query'] ) ) {
					$query->query_vars['meta_query'] = array( 
						array(
						'key'		=> '_a3_slider_template',
						'value'		=> trim( sanitize_text_field( $_GET['slider_skin'] ) ),
						)
					);
				}
			}
			// Categories
	        if ( isset( $_GET['slider_folder'] ) && $_GET['slider_folder'] == '0' ) {
	        	$query->query_vars['tax_query'][] = array(
	        		'taxonomy' => 'slider_folder',
	        		'field' => 'id',
					'terms' => get_terms( 'slider_folder', array( 'fields' => 'ids' ) ),
					'operator' => 'NOT IN'
	        	);
	        }
		}
		return $query;
	}
	
	public static function edit_sortable_columns( $columns ) {
		$columns['slider_skin'] = 'slider_skin';
	
		return $columns;
	}
	
	public static function edit_columns( $columns ) {
		$columns = array();

		$columns['cb'] 				= '<input type="checkbox" />';
		$columns['image'] 			= __( 'Slider Thumbnail', 'a3-responsive-slider' );
		$columns['title'] 			= __( 'Name', 'a3-responsive-slider' );
		$columns['slider_skin'] 	= __( 'Skin', 'a3-responsive-slider' );
		$columns['cats'] 			= __( 'Folders', 'a3-responsive-slider' );
		$columns['date'] 			= __( 'Date', 'a3-responsive-slider' );
		$columns['count_images'] 	= __( 'Slides ', 'a3-responsive-slider' );
	
		return $columns;
	}
	
	public static function custom_columns( $column ) {
		global $post;
		
		$slider_id = get_post_meta( $post->ID, '_a3_slider_id' , true );
		$num_images = 0;
		if ( $slider_id > 0 ) $num_images = Data::count_images_in_slider( $slider_id );
		
		switch ( $column ) {
			case 'image':
				if ( $num_images > 0 ) {
					$thumb_data = Data::get_first_image_slider( $slider_id );
					if ( $thumb_data->is_video == 1 )
						echo '<img class="a3-slider-thumbnail" src="http://img.youtube.com/vi/'. esc_attr( $thumb_data->video_url ).'/default.jpg" />';	
					else
						echo '<img class="a3-slider-thumbnail" src="'. esc_url( $thumb_data->img_url ).'" />';	
				} else {
					echo '<span class="a3-slider-no-thumbnail"></span>';
				}
                break;
			case 'slider_skin':
				$slider_template = get_post_meta( $post->ID, '_a3_slider_template' , true );
				$slider_template_name = Functions::get_slider_template( $slider_template );
				echo $slider_template_name;
				echo '<div class="hidden" style="display:none" id="a3_slider_skin_bulk_inline_'.$post->ID.'"><div class="a3_slider_skin_value">'.esc_attr( $slider_template ).'</div></div>';
				break;
			case "cats" :
				$terms = get_the_terms( $post->ID, 'slider_folder' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$slider_folders = array();
					foreach ( $terms as $term ) {
						$slider_folders[] = "<a href='?post_type=a3_slider&amp;slider_folder={$term->slug}'> " . esc_html( $term->name ) . "</a>";
					}
					echo join( ', ', $slider_folders );
				} else {
					echo '–';	
				}
				break;
			case 'count_images':
				echo $num_images;
				break;
		}
	}
	/* END : Custom column for Room post type */
	
	public static function show_own_edit_slider_page( $post ) {
		echo '<style>#poststuff {display:none}</style>';
		echo Admin\Slider_Edit::admin_screen_add_edit( $post );
	}
	
	public static function post_row_actions( $actions, $post ) {
		$post_type = get_post_type( $post->ID );
		if ( $post_type == 'a3_slider' ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style('thickbox');
		
			$slider_id = get_post_meta( $post->ID, '_a3_slider_id' , true );
			if ( ! empty( $slider_id ) ) {
				$a3_slider_preview = wp_create_nonce("a3-slider-preview");
				$additional_action = array(
					'a3_slider_preview' => '<a class="preview-data thickbox" href="' . admin_url( 'admin-ajax.php', 'relative' ) . '?KeepThis=true&view=list&slider_id=' . $slider_id . '&action=a3_slider_preview&security=' . $a3_slider_preview . '&height=500" title="' . $post->post_title . ' - ' .esc_attr( __( 'Slider Preview', 'a3-responsive-slider' ) ) . '">' . __( 'Preview', 'a3-responsive-slider' ) . '</a>'
					);
				$actions = array_merge( $additional_action, $actions );
			}
		}
		
		return $actions;
	}
}
