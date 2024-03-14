<?php defined('ABSPATH') || exit; ?>

<div class="woocommerce-tabs wc-tabs-wrapper">
	<ul class="tabs wc-tabs" role="tablist">
		<?php if( isset( $product_tabs['description'] ) ): 
			$key = "description";
			?>
			<li class="<?php echo esc_attr($key); ?>_tab" id="tab-title-<?php echo esc_attr($key); ?>" role="tab" aria-controls="tab-<?php echo esc_attr($key); ?>">
				<a href="#tab-<?php echo esc_attr($key); ?>">
					<?php echo wp_kses_post(apply_filters('woocommerce_product_' . $key . '_tab_title', $product_tabs['description']['title'], $key)); ?>
				</a>
			</li>
			<li class="additional_information_tab active" id="tab-title-additional_information" role="tab" aria-controls="tab-additional_information">
				<a href="#tab-additional_information">
					<?php esc_html_e('Additional information', 'shopengine-gutenberg-addon'); ?>
				</a>
			</li>
			<li class="reviews_tab active" id="tab-title-reviews" role="tab" aria-controls="tab-reviews">
				<a href="#tab-reviews">
					<?php esc_html_e('Reviews (0)', 'shopengine-gutenberg-addon') ?>
				</a>
			</li>

		<?php endif; ?>
	</ul>
	<?php if( isset( $product_tabs['description'] ) ) : 
		$key = "description";
		?>
		<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr($key); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr($key); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr($key); ?>">
			<?php
			if (isset($product_tabs['description']['callback'])) {
				if ('woocommerce_product_description_tab' == $product_tabs['description']['callback']) {
			?>
			<?php esc_html_e('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.', 'shopengine-gutenberg-addon'); ?>
			 
			<?php } else {
					call_user_func($product_tabs['description']['callback'], $key, $product_tabs['description']);
				}
			}
			?>
		</div>
	<?php endif; ?>
	<!-- Additional Information -->
	<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--additional_information panel entry-content wc-tab" id="tab-additional_information" role="tabpanel" aria-labelledby="tab-title-additional_information" >

		<h2> <?php esc_html_e('Additional information', 'shopengine-gutenberg-addon'); ?> </h2>

		<table class="woocommerce-product-attributes shop_attributes">
			<tbody>
				<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--attribute_pa_color">
					<th class="woocommerce-product-attributes-item__label"> <?php esc_html_e('Color', 'shopengine-gutenberg-addon'); ?>  </th>
					<td class="woocommerce-product-attributes-item__value">
						<p> <?php esc_html_e('Red', 'shopengine-gutenberg-addon'); ?> </p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--reviews panel entry-content wc-tab" id="tab-reviews" role="tabpanel" aria-labelledby="tab-title-reviews" >
		<div id="reviews" class="woocommerce-Reviews">
			<div id="comments">
				<h2 class="woocommerce-Reviews-title">
					<?php esc_html_e('Reviews', 'shopengine-gutenberg-addon'); ?>
				</h2>

				<p class="woocommerce-noreviews"> <?php esc_html_e('There are no reviews yet.', 'shopengine-gutenberg-addon'); ?> </p>
			</div>

			<div id="review_form_wrapper">
				<div id="review_form">
					<div id="respond" class="comment-respond">
						<form action=" <?php echo esc_url( site_url('wp-comments-post.php') ); ?>" method="post" id="commentform" class="comment-form" novalidate="">
							<div class="comment-form-rating"><label for="rating"> 
								<?php esc_html_e('Your rating', 'shopengine-gutenberg-addon'); ?> &nbsp;
								<span class="required">*</span></label>
								<p class="stars"> 
									<span> 
										<a class="star-1" href="#"><?php esc_html_e('1', 'shopengine-gutenberg-addon'); ?></a>
										<a class="star-2" href="#"><?php esc_html_e('2', 'shopengine-gutenberg-addon'); ?></a>
										<a class="star-3" href="#"><?php esc_html_e('3', 'shopengine-gutenberg-addon'); ?></a>
										<a class="star-4" href="#"><?php esc_html_e('4', 'shopengine-gutenberg-addon'); ?></a>
										<a class="star-5" href="#"><?php esc_html_e('5', 'shopengine-gutenberg-addon'); ?></a>
									</span>
								</p>
								<select name="rating" id="rating" required="" style="display: none;">
									<option value=""> <?php esc_html_e(' Rate…', 'shopengine-gutenberg-addon'); ?></option>
									<option value="5"> <?php esc_html_e(' Perfect', 'shopengine-gutenberg-addon'); ?> </option>
									<option value="4"> <?php esc_html_e(' Good', 'shopengine-gutenberg-addon'); ?> </option>
									<option value="3"> <?php esc_html_e(' Average', 'shopengine-gutenberg-addon'); ?> </option>
									<option value="2"> <?php esc_html_e(' Not that bad', 'shopengine-gutenberg-addon'); ?> </option>
									<option value="1"> <?php esc_html_e(' Very poor', 'shopengine-gutenberg-addon'); ?> </option>
								</select>
							</div>
							<p class="comment-form-comment">
								<label for="comment">  <?php esc_html_e('Your review', 'shopengine-gutenberg-addon'); ?>  &nbsp;<span class="required">*</span></label>
								<textarea id="comment" name="comment" cols="45" rows="8" required=""></textarea>
							</p>
							<p class="form-submit wp-block-button"><input name="submit" type="submit" id="submit" class="submit wp-block-button__link" value="Submit">
								<input type="hidden" name="comment_post_ID" value="34" id="comment_post_ID">
								<input type="hidden" name="comment_parent" id="comment_parent" value="0">
							</p>
							<input type="hidden" id="_wp_unfiltered_html_comment_disabled" name="_wp_unfiltered_html_comment" value="9e8791c299">
							<script>
								(function() {
									if (window === window.parent) {
										document.getElementById('_wp_unfiltered_html_comment_disabled').name = '_wp_unfiltered_html_comment';
									}
								})();
							</script>
						</form>
					</div><!-- #respond -->
				</div>
			</div>

			<div class="clear"></div>
		</div>
	</div>

	<?php do_action('woocommerce_product_after_tabs'); ?>
</div>