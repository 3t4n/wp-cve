<?php
/*
Plugin Name: Woo Product Dropdown Widget
Plugin URI: https://razorfrog.com/plugin-release-woocommerce-dropdown-widget/
Description: Display WooCommerce products by category in a dropdown menu widget
Version: 1.1.3
Author: Razorfrog Web Design
Author URI: https://razorfrog.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class rz_woo_category_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'rz_woo_category_widget', // Base ID
			__( 'WooCommerce Product Dropdown', 'text_domain' ), // Name
			array( 'description' => __( 'Display products from a specific product category as dropdown.', 'text_domain' ), ) // Args
		);
	}
	
	////////////////////////////////////////////////////////////////////////////////
	// Widget frontend
	////////////////////////////////////////////////////////////////////////////////

	public function widget( $args, $instance ) {
	
	    extract( $args );
	    echo $before_widget;        
	
		$title = $instance['title'];
		$show_posts = (is_numeric($instance['show_posts'])) ? $instance['show_posts'] : -1;
		
		$args = array( // defaults to all categories
				'post_type' => 'product', 
				'orderby' => $instance['sort'],
				'order' => $instance['order'],
				'numberposts' => $show_posts
			);
		$product_cat = "Products";
		
		if ($instance['cat_dropdown'] != 0) { // single product category
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'id',
					'terms'    => $instance['cat_dropdown'],
				),
			);
			$product_cat = get_cat_name($instance['cat_dropdown']);
		}
		
		if ($instance['sort'] == "price") { // order by price
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_price';
		} else if ($instance['sort'] == "sales") { // order by total sales
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = 'total_sales';
		}
		
		$posts = get_posts($args);
		
		if ($posts) {
			echo '<div class="'.$this->widget_options['classname'].'-content">';
					
			if ( ! empty( $instance['title'] ) ) {
				echo $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title; 
			}
			
			echo '<select class="category-posts-dropdown" onchange="location = this.value;" style="width:100%">';
			echo '<option value="">View '. $product_cat . '</option>';
			
			foreach ($posts as $post) {
				
				$additional = "";
			
				// If sorted by reviews, show (count)
				if (($instance['sort'] == "comment_count") && ($post->comment_count == 1)) { 
					$additional = " (".$post->comment_count." Review)";
				} else if (($instance['sort'] == "comment_count") && ($post->comment_count > 1)) {
					$additional = " (".$post->comment_count." Reviews)";
				}
			?>
				<option value="<?php echo get_permalink($post->ID);?>"><?php echo $post->post_title . $additional;?></option>
			<?php }
				
			echo '</select>';
		}
		
		echo '</div>';
	    echo $after_widget;  
	}
	
	////////////////////////////////////////////////////////////////////////////////
	// Widget backend - admin dashboard
	////////////////////////////////////////////////////////////////////////////////

	public function form( $instance ) {
	    $defaults = array(
	        'cat_dropdown' => '', // Set the default value for cat_dropdown
	        'title' => '',       // Set default values for other fields as needed
	        'sort' => 'title',   // Default sort value
	        'order' => 'ASC',    // Default order value
	        'show_posts' => -1,  // Default show_posts value
	    );
	    
		$instance = wp_parse_args( (array) $instance, $defaults );
		$cat_dropdown = isset( $instance['cat_dropdown'] ) ? $instance['cat_dropdown'] : '';
		
		$product_categories_dropdown = wp_dropdown_categories( array(
			'taxonomy' => 'product_cat',
			'orderby' => 'slug',
			'hierarchical' => true,
			'echo' => false,
			'show_option_all' => 'Select Category',
			'show_count' => true,
			'selected' => $instance['cat_dropdown'],
			'name' => $this->get_field_name('cat_dropdown'),
			'id' => $this->get_field_id('cat_dropdown'),
			'class' => 'widefat',
			'show_option_all' => 'All Products',
		)); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('cat_dropdown'); ?>"><?php _e('Category:'); ?></label>
			<?php echo $product_categories_dropdown;  ?>
		</p>	
		
		<p>
			<label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e('Sort by:'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('sort'); ?>" name="<?php echo $this->get_field_name('sort'); ?>">
				<?php
				echo '<option'. selected( $instance['sort'], 'title' ) .' value="title">Product Name</option>';
				echo '<option'. selected( $instance['sort'], 'menu_order' ) .' value="menu_order">Menu Order</option>';
				echo '<option'. selected( $instance['sort'], 'price' ) .' value="price">Price</option>';
				echo '<option'. selected( $instance['sort'], 'sales' ) .' value="sales">Total Sales</option>';
				echo '<option'. selected( $instance['sort'], 'date' ) .' value="date">Date Published</option>';
				echo '<option'. selected( $instance['sort'], 'modified' ) .' value="modified">Date Last Modified</option>';
				echo '<option'. selected( $instance['sort'], 'comment_count' ) .' value="comment_count">Reviews</option>';
				echo '<option'. selected( $instance['sort'], 'rand' ) .' value="rand">Random</option>';
				?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Sort Order:'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
				<?php
				echo '<option'. selected( $instance['order'], 'ASC' ) .' value="ASC">Ascending (A > Z)</option>';
				echo '<option'. selected( $instance['order'], 'DESC' ) .' value="DESC">Descending (Z > A)</option>';
				?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'show_posts' ); ?>">Number of Products (Defaults to All):</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'show_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_posts' ); ?>" value="<?php echo $instance['show_posts']; ?>" />
		</p><?php 
	}

	////////////////////////////////////////////////////////////////////////////////
	// Sanitize widget form values as they are saved
	////////////////////////////////////////////////////////////////////////////////
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = $new_instance['title'];
		$instance['show_posts'] = sanitize_text_field($new_instance['show_posts']);
		$instance['sort'] = sanitize_text_field($new_instance['sort']);
		$instance['order'] = sanitize_text_field($new_instance['order']);
		$instance['cat_dropdown'] = (int) $new_instance['cat_dropdown'];
		return $instance;
	}

}

add_action( 'widgets_init', 'register_rz_woo_dropdown_widget' );
function register_rz_woo_dropdown_widget() {;
	register_widget( 'rz_woo_category_widget' );
}

?>