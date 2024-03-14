<?php
	defined('ABSPATH') || exit; // Exit if accessed directly
	
	$onsale = [
		'on_sale' => esc_html__('On Sale', 'shopengine-gutenberg-addon'),
		'regular_price' => esc_html__('Regular Price', 'shopengine-gutenberg-addon')
	];

	$name		= 'shopengine_filter_onsale';
	$collapse	= false;

	if( $settings['shopengine_filter_view_mode']['desktop'] === 'collapse' ) {
		$collapse	= true;
	}
	
	$collapse_expand	= '';

	if((isset($_GET[$name]) || 
		$settings['shopengine_filter_onsale_expand_collapse']['desktop'] === true) && 
		!empty($_GET["onsale_nonce"]) && 
		wp_verify_nonce( sanitize_text_field( wp_unslash($_GET["onsale_nonce"]) ), "onsale_filter" )) {
		$collapse_expand	= 'open';
	}
?>

<div class="shopengine-filter-single <?php echo esc_attr( $collapse ? 'shopengine-collapse' : '' ) ?>">
	<div class="shopengine-filter <?php echo esc_attr( $collapse_expand ) ?>">
		<?php if(isset($settings['shopengine_filter_onsale_title'])) : ?>
			<h3 class="shopengine-product-filter-title">
				<?php
					echo esc_html($settings['shopengine_filter_onsale_title']['desktop']);
					echo $collapse ? '<i class="eicon-chevron-right shopengine-collapse-icon"></i>' : '';
				?>
			</h3>
		<?php endif;?>
	</div>

	<?php
	if ($collapse) {
		echo wp_kses(
			'<div class="shopengine-collapse-body ' . esc_attr($collapse_expand) . '">',
			array('div' => array('class' => '*'))
		);
	}
	?>

		<?php foreach ($onsale as $key => $value): ?>
			<?php
				$id		= 'xs-filter-onsale-'.$key;
			?>
			<div class="filter-input-group">
				<input
					class="shopengine-filter-onsale <?php echo esc_attr($name.'-'.$key) ?>"
					name="<?php echo esc_attr($name) ?>"
					type="checkbox"
					id="<?php echo esc_attr($id) ?>"
					value="<?php echo esc_attr($key); ?>"
				/>
				<label class="shopengine-filter-onsale-label" for="<?php echo esc_attr($id) ?>">
					<span class="shopengine-checkbox-icon">
						<span>
							<?php render_icon($settings['shopengine_check_icon']['desktop'], ['aria-hidden' => 'true']); ?>
						</span>
					</span>
					<?php echo esc_html($value ); ?>
				</label>
			</div>
		<?php endforeach; ?>

	<?php echo $collapse ? '</div>' : '';?>

	<form action="" method="get" class="shopengine-filter" id="shopengine_onsale_form">
		<input type="hidden" name="<?php echo esc_attr( $name ) ?>" class="shopengine-filter-onsale-value">
		<input type="hidden" name="onsale_nonce" value="<?php echo esc_attr(wp_create_nonce("onsale_filter")) ?>">
	</form>
	
</div>
