<div class="blog-carousel-item">
	<?php if ($props['add_product_link'] == 'on'): ?>
		<a class='wpt-wc-product-link' href="<?php echo get_the_permalink($blog); ?>"></a>
	<?php endif?>

	<article>
		<div class="wpt-post-thumbnail">
			<img src="<?php echo $featured_image; ?>">
		</div>

		<div class="text">
			<h4>
				<?php echo $blog->post_title; ?>
			</h4>

			<?php if ($props['show_product_excerpt'] == 'on'): ?>
				<p class="wpt-wc-product-excerpt">
					<?php echo $blog->post_excerpt; ?>
				</p>
			<?php endif?>

		</div>
	</article>
</div>
