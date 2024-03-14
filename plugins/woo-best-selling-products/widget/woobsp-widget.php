<?php
/*
*
* Best Selling Products Widget
*
*/

if (!defined('ABSPATH')) {
	exit;
}

class WOOBSP_Best_Selling_Products_Widget extends WP_Widget
{
	function __construct()
	{
		parent::__construct(
			'woobsp_best_selling_products',
			__('Best Selling Products for WooCommerce Widget', 'woo-best-selling-products'),
			array(
				'description' => __('List best selling products.', 'woo-best-selling-products')
			)
		);
	}

	function form($woobsp_instance)
	{
		$cat = $woobsp_instance['cat'];
		$catradio = $woobsp_instance['catradio'];
		$posts = $woobsp_instance['posts'];
		$thumbs = $woobsp_instance['thumbs'];
		$stars = $woobsp_instance['stars'];

		if (isset($woobsp_instance['title'])) {
			$title = $woobsp_instance['title'];
		} else {
			$title = __('Best Selling Products', 'woo-best-selling-products');
		}
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'woo-best-selling-products'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<input type="radio" name="<?php echo $this->get_field_name('catradio'); ?>" value="include" <?php echo ($catradio == 'include') ? 'checked' : ''; ?>> <?php _e('Include Categories', 'woo-best-selling-products'); ?>
			<input type="radio" name="<?php echo $this->get_field_name('catradio'); ?>" value="exclude" <?php echo ($catradio == 'exclude') ? 'checked' : ''; ?>> <?php _e('Exclude Categories', 'woo-best-selling-products'); ?>
		</p>
		<p>
			<small><?php _e('Hold down Ctrl/Cmd to select multiple categories.', 'woo-best-selling-products'); ?></small>
			<select class='widefat' id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>[]" multiple>

				<option value='' <?php echo ($cat == '') ? 'selected' : ''; ?>><?php _e('All Categories', 'woo-best-selling-products'); ?></option>

				<?php
				$cat_args1 = array(
					'taxonomy' => 'product_cat',
					'orderby' => 'name',
					'hierarchical' => 1,
					'hide_empty' => 0,
				);
				$all_categories = get_categories($cat_args1);
				foreach ($all_categories as $top_category) {
					if ($top_category->category_parent == 0) {
						$category_id = $top_category->term_id;
						$category_slug = $top_category->slug;
						$category_name = $top_category->name;
						$category_count = $top_category->count;

				?>
						<option value='<?php echo $category_slug ?>' <?php echo (in_array($category_slug, $cat)) ? 'selected' : ''; ?>><?php echo $category_name . ' (' . $category_count . ')' ?></option>
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
							foreach ($sub_cats as $sub_category) {
								$subcategory_id = $sub_category->term_id;
								$subcategory_slug = $sub_category->slug;
								$subcategory_name = $sub_category->name;
								$subcategory_count = $sub_category->count;
						?>
								<option value='<?php echo $subcategory_slug ?>' <?php echo (in_array($subcategory_slug, $cat)) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;' . $subcategory_name . ' (' . $subcategory_count . ')' ?></option>
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
									foreach ($sub3_cats as $sub_category) {
										$subcategory_id = $sub_category->term_id;
										$subcategory_slug = $sub_category->slug;
										$subcategory_name = $sub_category->name;
										$subcategory_count = $sub_category->count;
								?>
										<option value='<?php echo $subcategory_slug ?>' <?php echo (in_array($subcategory_slug, $cat)) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name . ' (' . $subcategory_count . ')' ?></option>
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
											foreach ($sub4_cats as $sub_category) {
												$subcategory_id = $sub_category->term_id;
												$subcategory_slug = $sub_category->slug;
												$subcategory_name = $sub_category->name;
												$subcategory_count = $sub_category->count;
										?>
												<option value='<?php echo $subcategory_slug ?>' <?php echo (in_array($subcategory_slug, $cat)) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name . ' (' . $subcategory_count . ')' ?></option>
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
													foreach ($sub5_cats as $sub_category) {
														$subcategory_id = $sub_category->term_id;
														$subcategory_slug = $sub_category->slug;
														$subcategory_name = $sub_category->name;
														$subcategory_count = $sub_category->count;
												?>
														<option value='<?php echo $subcategory_slug ?>' <?php echo (in_array($subcategory_slug, $cat)) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name . ' (' . $subcategory_count . ')' ?></option>
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
															foreach ($sub6_cats as $sub_category) {
																$subcategory_id = $sub_category->term_id;
																$subcategory_slug = $sub_category->slug;
																$subcategory_name = $sub_category->name;
																$subcategory_count = $sub_category->count;
														?>
																<option value='<?php echo $subcategory_slug ?>' <?php echo (in_array($subcategory_slug, $cat)) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name . ' (' . $subcategory_count . ')' ?></option>
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
																	foreach ($sub7_cats as $sub_category) {
																		$subcategory_id = $sub_category->term_id;
																		$subcategory_slug = $sub_category->slug;
																		$subcategory_name = $sub_category->name;
																		$subcategory_count = $sub_category->count;
																?>
																		<option value='<?php echo $subcategory_slug ?>' <?php echo (in_array($subcategory_slug, $cat)) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name . ' (' . $subcategory_count . ')' ?></option>
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
																			foreach ($sub8_cats as $sub_category) {
																				$subcategory_id = $sub_category->term_id;
																				$subcategory_slug = $sub_category->slug;
																				$subcategory_name = $sub_category->name;
																				$subcategory_count = $sub_category->count;
																		?>
																				<option value='<?php echo $subcategory_slug ?>' <?php echo (in_array($subcategory_slug, $cat)) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name . ' (' . $subcategory_count . ')' ?></option>
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
																					foreach ($sub9_cats as $sub_category) {
																						$subcategory_id = $sub_category->term_id;
																						$subcategory_slug = $sub_category->slug;
																						$subcategory_name = $sub_category->name;
																						$subcategory_count = $sub_category->count;
																				?>
																						<option value='<?php echo $subcategory_slug ?>' <?php echo (in_array($subcategory_slug, $cat)) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name . ' (' . $subcategory_count . ')' ?></option>
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
																							foreach ($sub10_cats as $sub_category) {
																								$subcategory_id = $sub_category->term_id;
																								$subcategory_slug = $sub_category->slug;
																								$subcategory_name = $sub_category->name;
																								$subcategory_count = $sub_category->count;
																						?>
																								<option value='<?php echo $subcategory_slug ?>' <?php echo (in_array($subcategory_slug, $cat)) ? 'selected' : ''; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $subcategory_name . ' (' . $subcategory_count . ')' ?></option>
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
			<label for="<?php echo $this->get_field_id('posts'); ?>"><?php _e('Products Shown', 'woo-best-selling-products'); ?></label>
			<br /><small><?php _e('Enter -1 to show all products.', 'woo-best-selling-products'); ?></small>
			<input class="widefat" type="number" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo empty($posts) ? '3' : esc_attr($posts); ?>">
		</p>
		<p>
			<input id="<?php echo $this->get_field_id('thumbs'); ?>" name="<?php echo $this->get_field_name('thumbs'); ?>" type="checkbox" value="1" <?php checked($thumbs, 1); ?> />
			<label for="<?php echo $this->get_field_id('thumbs'); ?>"><?php _e('Show product thumbnails?', 'woo-best-selling-products'); ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id('stars'); ?>" name="<?php echo $this->get_field_name('stars'); ?>" type="checkbox" value="1" <?php checked($stars, 1); ?> />
			<label for="<?php echo $this->get_field_id('stars'); ?>"><?php _e('Show product star ratings?', 'woo-best-selling-products'); ?></label>
		</p>

	<?php
	}

	function update($new_woobsp_instance, $old_woobsp_instance)
	{

		$woobsp_instance = $old_woobsp_instance;
		$woobsp_instance['title'] = strip_tags($new_woobsp_instance['title']);
		$woobsp_instance['cat'] = $new_woobsp_instance['cat'];
		$woobsp_instance['catradio'] = strip_tags($new_woobsp_instance['catradio']);
		$woobsp_instance['posts'] = strip_tags($new_woobsp_instance['posts']);
		$woobsp_instance['thumbs'] = isset($new_woobsp_instance['thumbs']) ? 1 : false;
		$woobsp_instance['stars'] = isset($new_woobsp_instance['stars']) ? 1 : false;



		return $woobsp_instance;
	}

	function widget($args, $woobsp_instance)
	{
		$title = apply_filters('widget_title', $woobsp_instance['title']);

		echo $args['before_widget'];
		if (!empty($title)) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		$defaults = array(
			'cat' => '',
			'posts' => 3,
			'thumbs' => '',
			'stars' => ''
		);
		if (empty($woobsp_instance['posts'])) {
			$woobsp_instance['posts'] = $defaults['posts'];
		}

	?>
		<ul class="woobsp_bestselling_list <?php echo ($woobsp_instance['thumbs'] == '1' || $woobsp_instance['thumbs'] == 'yes') ? '' : 'woobsp_nothumb' ?>">
			<?php
			$catradio_val = '';
			if ($woobsp_instance['catradio'] == 'include') {
				$catradio_val = 'IN';
			} else if ($woobsp_instance['catradio'] == 'exclude') {
				$catradio_val = 'NOT IN';
			}
			$arggs = array(
				'post_type' => 'product',
				'posts_per_page' => $woobsp_instance['posts'],
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => $woobsp_instance['cat'],
						'operator' => $catradio_val,
					)
				),
				'meta_key' => 'total_sales',
				'orderby' => 'meta_value_num',
				'thumbs' => $woobsp_instance['thumbs'],
				'stars' => $woobsp_instance['stars']
			);

			$loop = new WP_Query($arggs);
			while ($loop->have_posts()) :
				$loop->the_post();
				include_once('functions/woobsp-func-list.php');
				echo woobsp_bestselling_list($loop->post, $woobsp_instance['thumbs'], $woobsp_instance['stars']);
			endwhile;
			wp_reset_query();
			?>
		</ul>
<?php
		echo $args['after_widget'];
	}
}

function woobsp_best_selling_products_widget()
{
	register_widget('WOOBSP_Best_Selling_Products_Widget');
}

add_action('widgets_init', 'woobsp_best_selling_products_widget');