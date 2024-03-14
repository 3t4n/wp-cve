<?php
/**
 * Settings sidebar.
 *
 * @var string $pro_url           .
 * @var bool   $show_pro_features .
 */
?>
<div class="oct-metabox">
	<h3 class="oct-metabox-title"><?php _e( 'Get UPS Live Rates PRO!', 'flexible-shipping-ups' ); ?></h3>

	<ul>
		<li><?php _e( 'Handling Fees', 'flexible-shipping-ups' ); ?></li>
		<li><?php _e( 'Delivery Dates', 'flexible-shipping-ups' ); ?></li>
		<li><?php _e( 'Box Packing', 'flexible-shipping-ups' ); ?></li>
		<li><?php _e( 'Access Points Select', 'flexible-shipping-ups' ); ?></li>
		<li><?php _e( 'Flat Rate for Access Points', 'flexible-shipping-ups' ); ?></li>
		<li><?php _e( 'Multicurrency Support', 'flexible-shipping-ups' ); ?></li>
		<li><?php _e( 'Simple Rate Support', 'flexible-shipping-ups' ); ?></li>
	</ul>

	<?php if ( $show_pro_features ) : ?>
		<p class="promo">
			<label>
				<input type="checkbox" class="js--pro-feature-enable"/>
				<?php _e( 'Show functions available in PRO version', 'flexible-shipping-ups' ); ?>
			</label>
		</p>
	<?php endif; ?>

	<div>
		<a class="oct-metabox-btn" href="<?php echo esc_url( $pro_url ); ?>" target="_blank"><?php _e( 'Upgrade Now', 'flexible-shipping-ups' ); ?></a>
	</div>
</div>
