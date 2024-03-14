<?php

namespace Elementor;
defined('ABSPATH') || exit;

$uid = uniqid();

// check if the collapse enabled
$collapse	   = false;
$collapse_expand = '';

/**
 * 
 * Check weather the collapse enabled or not 
 * 
 */
if ($settings['shopengine_filter_view_mode']['desktop'] === 'collapse') {
	$collapse	   = true;
}
/**
 * 
 * Check weather the collapse expand enable or not 
 * 
 */
if (($settings['shopengine_filter_category_expand_collapse']['desktop'] === true || 
	isset($_GET['shopengine_filter_category'])) && 
	!empty($_GET["category_nonce"]) && 
	wp_verify_nonce( sanitize_text_field(wp_unslash($_GET["category_nonce"])), "category_filter") ) {
	$collapse_expand = 'open';
}

?>

<div class="shopengine-filter-single <?php echo esc_attr($collapse ? 'shopengine-collapse' : '') ?>">

	<?php
	/**
	 * 
	 * show filter title
	 * 
	 */
	if (isset($settings['shopengine_filter_category_title']['desktop'])) :
	?>
		<div class="shopengine-filter <?php echo esc_attr($collapse_expand) ?>">
			<h3 class="shopengine-product-filter-title">
				<?php
				echo esc_html($settings['shopengine_filter_category_title']['desktop']);
				if ($collapse) echo '<i class="eicon-chevron-right shopengine-collapse-icon"></i>';
				?>
			</h3>
		</div>
	<?php

	endif; // end of filter title 
	if ($collapse === true) {
		echo '<div class="shopengine-collapse-body ' . esc_attr($collapse_expand) . '">';
	}

	?>

	<ul class="shopengine-category-filter-list shopengine-filter-category">
		<?php
		/**
		 * 
		 * loop through list item
		 * 
		 */
		foreach ($product_categories as $category) :
			if (in_array($category->term_id, array_column($settings['shopengine_filter_except_category']['desktop'], 'id'))) {
				continue;
			}
		?>

			<li class="<?php if (!empty(get_term_children($category->term_id, 'product_cat'))) { echo 'shopengine-filter-category-has-child';} ?>">
				<div class="filter-input-group">
					<input class="shopengine-filter-categories shopengine-category-name-<?php echo esc_attr($category->slug); ?>" name="noNeed" type="checkbox" id="shopengine-filter-category-<?php echo esc_attr($uid . '-' . $category->term_id); ?>" <?php // checked($product_categories, $category->slug); ?> value="<?php echo esc_attr($category->slug); ?>" />
					<label class="shopengine-filter-category-label" for="shopengine-filter-category-<?php echo esc_attr($uid . '-' . $category->term_id); ?>">
						<span class="shopengine-checkbox-icon">
							<span>
								<?php render_icon($settings['shopengine_check_icon']['desktop'], ['aria-hidden' => 'true']); ?>
							</span>
						</span>
						<?php echo esc_html($category->name); ?>
					</label>
					<?php if (!empty(get_term_children($category->term_id, 'product_cat')) && $hierarchical === true) : ?>
						<div class="shopengine-filter-category-toggle" aria-expanded="false" data-target="#shopengine-filter-subcategory-<?php echo esc_attr($uid . '-' . $category->term_id); ?>">
							<span></span>
						</div>
					<?php endif; ?>
				</div>

				<?php if (!empty(get_term_children($category->term_id, 'product_cat')) && $hierarchical === true) : ?>

					<?php
					$sub_categories = get_terms('product_cat', [
						'orderby'		=> 'name',
						'order'			=> 'asc',
						'hide_empty'	=> false,
						'parent'		=> $category->term_id,
						'depth'      	=> 0,
					]);
					?>

					<ul class="shopengine-filter-category-subcategories" id="shopengine-filter-subcategory-<?php echo esc_attr($uid . '-' . $category->term_id); ?>">
						<?php foreach ($sub_categories as $child_category) : ?>
							<?php
							if (in_array($child_category->term_id, $settings['shopengine_filter_except_category']['desktop'])) {
								continue;
							}
							?>

							<li class="<?php if (!empty(get_term_children($child_category->term_id, 'product_cat'))) {
											echo 'shopengine-filter-category-has-child shopengine-filter-subcategory-has-child';
										} ?>">
								<div class="filter-input-group">
									<input class="shopengine-filter-categories shopengine-filter-subcategory shopengine-category-name-<?php echo esc_attr($child_category->slug); ?>" name="cat" type="checkbox" id="shopengine-filter-category-<?php echo esc_attr($uid . '-' . $child_category->term_id); ?>" value="<?php echo esc_attr($child_category->slug); ?>" />
									<label class="shopengine-filter-category-label" for="shopengine-filter-category-<?php echo esc_attr($uid . '-' . $child_category->term_id); ?>">
										<span class="shopengine-checkbox-icon">
											<span>
												<?php render_icon($settings['shopengine_check_icon']['desktop'], ['aria-hidden' => 'true']); ?>
											</span>
										</span>
										<?php echo esc_html($child_category->name); ?>
									</label>
									<?php if (!empty(get_term_children($child_category->term_id, 'product_cat')) && $hierarchical === true) : ?>
										<div class="shopengine-filter-category-toggle shopengine-filter-subcategory-toggle" aria-expanded="false" data-target="#shopengine-filter-thirdcategory-<?php echo esc_attr($uid . '-' . $child_category->term_id); ?>">
											<span></span>
										</div>
									<?php endif; ?>
								</div>

								<?php if (!empty(get_term_children($child_category->term_id, 'product_cat')) && $hierarchical === true) : ?>

									<?php
									$third_categories = get_terms('product_cat', [
										'orderby'		=> 'name',
										'order'			=> 'asc',
										'hide_empty'	=> false,
										'parent'		=> $child_category->term_id,
									]);
									?>

									<ul class="shopengine-filter-category-subcategories shopengine-filter-category-thirdcategories" id="shopengine-filter-thirdcategory-<?php echo esc_attr($uid . '-' . $child_category->term_id); ?>">

										<?php foreach ($third_categories as $grand_child_category) : ?>
											<?php
											if (in_array($grand_child_category->term_id, $settings['shopengine_filter_except_category']['desktop'])) {
												continue;
											}
											?>

											<li>
												<div class="filter-input-group">
													<input class="shopengine-filter-categories shopengine-filter-thirdcategory shopengine-category-name-<?php echo esc_attr($grand_child_category->slug); ?>" name="subcat" type="checkbox" id="shopengine-filter-category-<?php echo esc_attr($uid . '-' . $grand_child_category->term_id); ?>" value="<?php echo esc_attr($grand_child_category->slug); ?>" />
													<label class="shopengine-filter-category-label" for="shopengine-filter-category-<?php echo esc_attr($uid . '-' . $grand_child_category->term_id); ?>">
														<span class="shopengine-checkbox-icon">
															<span>
																<?php render_icon($settings['shopengine_check_icon']['desktop'], ['aria-hidden' => 'true']); ?>
															</span>
														</span>
														<?php echo esc_html($grand_child_category->name); ?>
													</label>
												</div>
											</li>

										<?php endforeach; ?>
									</ul>

								<?php endif; ?>

							</li>
						<?php endforeach; ?>
					</ul>

				<?php endif; ?>

			</li>
		<?php endforeach; ?>
	</ul>

	<?php if ($collapse) echo '</div>'; // end of collapse body container 
	?>

	<form action="" method="get" class="shopengine-filter" id="shopengine_category_form">
		<input type="hidden" id="shopengine_filter_category" name="shopengine_filter_category" />
		<input type="hidden" name="category_nonce" value="<?php echo esc_attr(wp_create_nonce("category_filter")) ?>">
	</form>

</div>