<?php
/*
*
* Products By Category Widget
*
*/

if (!defined('ABSPATH')) {
	exit;
}

class WCPBC_Product_By_Category_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'wcpbc_products_by_category',
			__('Display Products by Category for WooCommerce', 'woo-products-by-category') ,
			array(
				'description' => __('List all products by a specific store category.', 'woo-products-by-category')
			));
		}

		function form($instance) {
			$cat = (isset($instance[ 'cat' ])) ? $instance['cat'] : '';
			$posts = (isset($instance[ 'posts' ])) ? $instance['posts'] : '';
			$orderby = (isset($instance[ 'orderby' ])) ? $instance['orderby'] : '';
			$order = (isset($instance[ 'order' ])) ? $instance['order'] : '';
			$thumbs = (isset($instance[ 'thumbs' ])) ? $instance['thumbs'] : '';
			$hidden_p = (isset($instance[ 'hidden_p' ])) ? $instance['hidden_p'] : '';
			$oos_p = (isset($instance[ 'oos_p' ])) ? $instance['oos_p'] : '';

			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = __( 'Products By Category', 'woo-products-by-category' );
			}
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'woo-products-by-category' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>

				<label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e( 'Category', 'woo-products-by-category' ) ?></label>
				<select class='widefat' id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>">

					<?php
					$cat_args1 = array(
						'taxonomy' => 'product_cat',
						'orderby' => 'name',
						'hierarchical' => 1,
						'hide_empty' => 0,
						);
					$all_categories = get_categories($cat_args1);
					foreach($all_categories as $top_category) {
						if ($top_category->category_parent == 0) {
							$category_id = $top_category->term_id;
							$category_slug = $top_category->slug;
							$category_name = $top_category->name;
							$category_count = $top_category->count;

							?>
							<option value='<?php echo $category_slug ?>' <?php echo ($cat == $category_slug) ? 'selected' : ''; ?>><?php echo $category_name.' ('.$category_count.')' ?></option>
							<?php
							$cat_args2 = array(
								'taxonomy' => 'product_cat',
								'child_of' => 0,
								'parent' => $category_id,
								'orderby' => 'name',
								'hide_empty' => 0,
								'hierarchical' => 1
								);
							$sub_cats = get_categories($cat_args2);
							if ($sub_cats) {
								foreach($sub_cats as $sub_category) {
									$subcategory_id = $sub_category->term_id;
									$subcategory_slug = $sub_category->slug;
									$subcategory_name = $sub_category->name;
									$subcategory_count = $sub_category->count;
									?>
									<option value='<?php echo $subcategory_slug ?>' <?php echo ($cat == $subcategory_slug) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;' . $subcategory_name.' ('.$subcategory_count.')' ?></option>
									<?php
									$cat_args3 = array(
										'taxonomy' => 'product_cat',
										'child_of' => 0,
										'parent' => $subcategory_id,
										'orderby' => 'name',
										'hide_empty' => 0,
										'hierarchical' => 1
										);
									$sub3_cats = get_categories($cat_args3);
									if ($sub3_cats) {
										foreach($sub3_cats as $sub_category) {
											$subcategory_id = $sub_category->term_id;
											$subcategory_slug = $sub_category->slug;
											$subcategory_name = $sub_category->name;
											$subcategory_count = $sub_category->count;
											?>
											<option value='<?php echo $subcategory_slug ?>' <?php echo ($cat == $subcategory_slug) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name.' ('.$subcategory_count.')' ?></option>
											<?php
											$cat_args4 = array(
												'taxonomy' => 'product_cat',
												'child_of' => 0,
												'parent' => $subcategory_id,
												'orderby' => 'name',
												'hide_empty' => 0,
												'hierarchical' => 1
												);
											$sub4_cats = get_categories($cat_args4);
											if ($sub4_cats) {
												foreach($sub4_cats as $sub_category) {
													$subcategory_id = $sub_category->term_id;
													$subcategory_slug = $sub_category->slug;
													$subcategory_name = $sub_category->name;
													$subcategory_count = $sub_category->count;
													?>
													<option value='<?php echo $subcategory_slug ?>' <?php echo ($cat == $subcategory_slug) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name.' ('.$subcategory_count.')' ?></option>
													<?php
													$cat_args5 = array(
														'taxonomy' => 'product_cat',
														'child_of' => 0,
														'parent' => $subcategory_id,
														'orderby' => 'name',
														'hide_empty' => 0,
														'hierarchical' => 1
														);
													$sub5_cats = get_categories($cat_args5);
													if ($sub5_cats) {
														foreach($sub5_cats as $sub_category) {
															$subcategory_id = $sub_category->term_id;
															$subcategory_slug = $sub_category->slug;
															$subcategory_name = $sub_category->name;
															$subcategory_count = $sub_category->count;
															?>
															<option value='<?php echo $subcategory_slug ?>' <?php echo ($cat == $subcategory_slug) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name.' ('.$subcategory_count.')' ?></option>
															<?php
															$cat_args6 = array(
																'taxonomy' => 'product_cat',
																'child_of' => 0,
																'parent' => $subcategory_id,
																'orderby' => 'name',
																'hide_empty' => 0,
																'hierarchical' => 1
																);
															$sub6_cats = get_categories($cat_args6);
															if ($sub6_cats) {
																foreach($sub6_cats as $sub_category) {
																	$subcategory_id = $sub_category->term_id;
																	$subcategory_slug = $sub_category->slug;
																	$subcategory_name = $sub_category->name;
																	$subcategory_count = $sub_category->count;
																	?>
																	<option value='<?php echo $subcategory_slug ?>' <?php echo ($cat == $subcategory_slug) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name.' ('.$subcategory_count.')' ?></option>
																	<?php
																	$cat_args7 = array(
																		'taxonomy' => 'product_cat',
																		'child_of' => 0,
																		'parent' => $subcategory_id,
																		'orderby' => 'name',
																		'hide_empty' => 0,
																		'hierarchical' => 1
																		);
																	$sub7_cats = get_categories($cat_args7);
																	if ($sub7_cats) {
																		foreach($sub7_cats as $sub_category) {
																			$subcategory_id = $sub_category->term_id;
																			$subcategory_slug = $sub_category->slug;
																			$subcategory_name = $sub_category->name;
																			$subcategory_count = $sub_category->count;
																			?>
																			<option value='<?php echo $subcategory_slug ?>' <?php echo ($cat == $subcategory_slug) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name.' ('.$subcategory_count.')' ?></option>
																			<?php
																			$cat_args8 = array(
																				'taxonomy' => 'product_cat',
																				'child_of' => 0,
																				'parent' => $subcategory_id,
																				'orderby' => 'name',
																				'hide_empty' => 0,
																				'hierarchical' => 1
																				);
																			$sub8_cats = get_categories($cat_args8);
																			if ($sub8_cats) {
																				foreach($sub8_cats as $sub_category) {
																					$subcategory_id = $sub_category->term_id;
																					$subcategory_slug = $sub_category->slug;
																					$subcategory_name = $sub_category->name;
																					$subcategory_count = $sub_category->count;
																					?>
																					<option value='<?php echo $subcategory_slug ?>' <?php echo ($cat == $subcategory_slug) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name.' ('.$subcategory_count.')' ?></option>
																					<?php
																					$cat_args9 = array(
																						'taxonomy' => 'product_cat',
																						'child_of' => 0,
																						'parent' => $subcategory_id,
																						'orderby' => 'name',
																						'hide_empty' => 0,
																						'hierarchical' => 1
																						);
																					$sub9_cats = get_categories($cat_args9);
																					if ($sub9_cats) {
																						foreach($sub9_cats as $sub_category) {
																							$subcategory_id = $sub_category->term_id;
																							$subcategory_slug = $sub_category->slug;
																							$subcategory_name = $sub_category->name;
																							$subcategory_count = $sub_category->count;
																							?>
																							<option value='<?php echo $subcategory_slug ?>' <?php echo ($cat == $subcategory_slug) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name.' ('.$subcategory_count.')' ?></option>
																							<?php
																							$cat_args10 = array(
																								'taxonomy' => 'product_cat',
																								'child_of' => 0,
																								'parent' => $subcategory_id,
																								'orderby' => 'name',
																								'hide_empty' => 0,
																								'hierarchical' => 1
																								);
																							$sub10_cats = get_categories($cat_args10);
																							if ($sub10_cats) {
																								foreach($sub10_cats as $sub_category) {
																									$subcategory_id = $sub_category->term_id;
																									$subcategory_slug = $sub_category->slug;
																									$subcategory_name = $sub_category->name;
																									$subcategory_count = $sub_category->count;
																									?>
																									<option value='<?php echo $subcategory_slug ?>' <?php echo ($cat == $subcategory_slug) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name.' ('.$subcategory_count.')' ?></option>
																									<?php
																								}
																							}
																						}
																					}
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
					?>
				</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>"><?php _e( 'Products Shown', 'woo-products-by-category' ) ?></label>
			<br/><small><?php _e( 'Leave blank to show all products.', 'woo-products-by-category' ) ?></small>
			<input class="widefat" type="number" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo esc_attr($posts); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Order By', 'woo-products-by-category' ) ?></label>
			<select class='widefat' id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
				<option value='post_title' <?php echo ($orderby == 'post_title') ? 'selected' : ''; ?>><?php _e( 'Product Name', 'woo-products-by-category' ) ?></option>
				<option value='id' <?php echo ($orderby == 'id') ? 'selected' : ''; ?>><?php _e( 'Product ID', 'woo-products-by-category' ) ?></option>
				<option value='date' <?php echo ($orderby == 'date') ? 'selected' : ''; ?>><?php _e( 'Date Published', 'woo-products-by-category' ) ?></option>
				<option value='modified' <?php echo ($orderby == 'modified') ? 'selected' : ''; ?>><?php _e( 'Last Modified', 'woo-products-by-category' ) ?></option>
				<option value='rand' <?php echo ($orderby == 'rand') ? 'selected' : ''; ?>><?php _e( 'Random', 'woo-products-by-category' ) ?></option>
				<option value='none' <?php echo ($orderby == 'none') ? 'selected' : ''; ?>><?php _e( 'None', 'woo-products-by-category' ) ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e( 'Order', 'woo-products-by-category' ) ?></label>
			<select class='widefat' id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
				<option value='ASC' <?php echo ($order == 'ASC') ? 'selected' : ''; ?>><?php _e( 'Ascending (A to Z)', 'woo-products-by-category' ) ?></option>
				<option value='DESC' <?php echo ($order == 'DESC') ? 'selected' : ''; ?>><?php _e( 'Descending (Z to A)', 'woo-products-by-category' ) ?></option>
			</select>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'thumbs' ); ?>" name="<?php echo $this->get_field_name( 'thumbs' ); ?>" type="checkbox" value="1" <?php checked( $thumbs, 1 ); ?> />
			<label for="<?php echo $this->get_field_id( 'thumbs' ); ?>"><?php _e( 'Show product thumbnails?', 'woo-products-by-category' ) ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'hidden_p' ); ?>" name="<?php echo $this->get_field_name( 'hidden_p' ); ?>" type="checkbox" value="1" <?php checked( $hidden_p, 1 ); ?> />
			<label for="<?php echo $this->get_field_id( 'hidden_p' ); ?>"><?php _e( 'Show Hidden products?', 'woo-products-by-category' ) ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'oos_p' ); ?>" name="<?php echo $this->get_field_name( 'oos_p' ); ?>" type="checkbox" value="1" <?php checked( $oos_p, 1 ); ?> />
			<label for="<?php echo $this->get_field_id( 'oos_p' ); ?>"><?php _e( 'Show Out Of Stock products?', 'woo-products-by-category' ) ?></label>
		</p>

		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['cat'] = strip_tags($new_instance['cat']);
		$instance['posts'] = strip_tags($new_instance['posts']);
		$instance['orderby'] = strip_tags($new_instance['orderby']);
		$instance['order'] = strip_tags($new_instance['order']);
		$instance['thumbs'] = isset( $new_instance['thumbs'] ) ? 1 : false;
		$instance['hidden_p'] = isset( $new_instance['hidden_p'] ) ? 1 : false;
		$instance['oos_p'] = isset( $new_instance['oos_p'] ) ? 1 : false;

		return $instance;
	}

	function widget($args, $instance) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( !empty( $title )) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		$defaults = array(
			'cat' => '',
			'posts' => '-1',
			'orderby' => 'name',
			'order' => 'ASC',
			'thumbs' => '',
			'hidden_p' => '',
			'oos_p' => '',
		);
		if (empty($instance['posts'])) {
			$instance['posts'] = $defaults['posts'];
		}

		if (empty($instance['orderby'])) {
			$instance['orderby'] = $defaults['orderby'];
		}

		if (empty($instance['order'])) {
			$instance['order'] = $defaults['order'];
		}

		?>
		<ul class="productsbycat_list productsbycat_<?php echo $instance['cat']; ?>">
			<?php
			$arggs = array(
				'post_type' => 'product',
				'posts_per_page' => $instance['posts'],
				'product_cat' => $instance['cat'],
				'orderby' => $instance['orderby'],
				'order' => $instance['order'],
			);
			$loop = new WP_Query($arggs);
			$show_hidden = ($instance['hidden_p'] == '1') ? true : false;
			$show_oos = ($instance['oos_p'] == '1') ? true : false;

			while ($loop->have_posts()):
				$loop->the_post();
				global $product;
				$show_hidden_product = true;
				$show_oos_product = true;

				if ( $show_hidden ) {
					if ( ! $product->is_visible()) {
						$show_hidden_product = true;
					}
				} else {
					if ( ! $product->is_visible()) {
						$show_hidden_product = false;
					}
				}

				if ( $show_oos ) {
					if ( ! $product->managing_stock() && ! $product->is_in_stock()) {
						$show_oos_product = true;
					}
				} else {
					if ( ! $product->managing_stock() && ! $product->is_in_stock()) {
						$show_oos_product = false;
					}
				}

				$output = '';
				if ($show_hidden_product && $show_oos_product) {
					$output .= '<li class="wcpbc-product">';
					$output .= '<a href="'.get_permalink($loop->post->ID).'" title="'.esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID).'">';
					if ($instance['thumbs'] == '1') {
						if (has_post_thumbnail($loop->post->ID)) {
							$output .= get_the_post_thumbnail($loop->post->ID, 'thumbnail');
						} else {
							$output .= '<img src="'.wc_placeholder_img_src('thumbnail').'" alt="'. __('Placeholder', 'woo-products-by-category') .'" width="38" height="38" />';
						}
					}
					$output .= '<span class="product-title">'.$loop->post->post_title.'</span>';
					$output .= '</a>';
					$output .= '</li>';
				}
				echo $output;

			endwhile;
			wp_reset_query();
			?>
		</ul>
		<?php
		echo $args['after_widget'];
	}
}

function product_by_category_widget() {
	register_widget('WCPBC_Product_By_Category_Widget');
}

add_action('widgets_init', 'product_by_category_widget');
?>
