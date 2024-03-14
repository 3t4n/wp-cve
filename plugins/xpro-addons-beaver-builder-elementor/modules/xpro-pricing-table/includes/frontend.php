<?php

global $wp_embed;
$link_nofollow = ( 'yes' === $settings->pricing_link_nofollow ) ? ' rel="nofollow"' : '';

?>

<?php if ( ! empty( $settings->pricing_link ) ) { ?>
<a href="<?php echo esc_url( $settings->pricing_link ); ?>" target="<?php echo esc_attr( $settings->pricing_link_target ); ?>" <?php echo esc_attr( $link_nofollow ); ?>>
	<?php } ?>

<div class="xpro-pricing-item featured">
	<div class="xpro-pricing-item-inner">

		<!-- badge -->
		<?php if ( ! empty( $settings->badge ) ) { ?>
		<span class="xpro-price-item-badge xpro-pricing-badge-<?php echo esc_attr( $settings->pricing_position_badge ); ?>"><?php echo esc_attr( $settings->badge ); ?></span>
		<?php } ?>

	<!-- title after title -->
<?php if ( 'before_title' === $settings->pricing_title_position ) { ?>
		<?php if ( ! empty( $settings->pricing_title ) ) { ?>
			<<?php echo esc_attr( $settings->pricing_title_tag ); ?> class="xpro-pricing-item-title"><?php echo esc_attr( $settings->pricing_title ); ?></<?php echo esc_attr( $settings->pricing_title_tag ); ?>>
		<?php } ?>
<?php } ?>

		<!-- icon -->
		<?php if ( 'pricing_icon' === $settings->pricing_media_type ) { ?>
			<span class="xpro-pricing-item-icon"><i class="<?php echo esc_attr( $settings->pricing_icon ); ?>"></i></span>
		<?php } ?>

		<?php if ( 'pricing_image' === $settings->pricing_media_type && ! empty( $settings->pricing_image_src ) ) { ?>
			<div class="xpro-pricing-media">
				<img src="<?php echo esc_url( $settings->pricing_image_src ); ?>" alt="image">
			</div>
		<?php } ?>

		<!-- title before title -->
<?php if ( 'after_title' === $settings->pricing_title_position ) { ?>
	<?php if ( ! empty( $settings->pricing_title ) ) { ?>
	<<?php echo esc_attr( $settings->pricing_title_tag ); ?> class="xpro-pricing-item-title"><?php echo esc_attr( $settings->pricing_title ); ?></<?php echo esc_attr( $settings->pricing_title_tag ); ?>>
<?php } ?>
<?php } ?>

	<!-- price tag after featured -->
<?php if ( 'before_featured' === $settings->pricing_position ) { ?>

	<!-- price tag after featured -->
	<div class="xpro-pricing-price-box">
		<div class="xpro-pricing-price-tag">
			<!-- currency -->
			<?php if ( ! empty( $settings->pricing_currency ) ) { ?>
				<span class="xpro-pricing-currency"><?php echo esc_attr( $settings->pricing_currency ); ?></span>
			<?php } ?>

			<!-- price -->
			<?php if ( ! empty( $settings->pricing_price ) ) { ?>
				<span class="xpro-pricing-price"><?php echo esc_attr( $settings->pricing_price ); ?></span>
			<?php } ?>
		</div>

		<!-- period -->
		<?php if ( ! empty( $settings->pricing_period ) ) { ?>
			<span class="xpro-pricing-price-period"><?php echo esc_attr( $settings->pricing_period ); ?></span>
		<?php } ?>
	</div>

<?php } ?>

		<!-- text -->
	<?php
	if ( 'show' === $settings->pricing_text_badge ) {
		if ( ! empty( $settings->pricing_text ) ) {
			?>
					<div class="xpro-pricing-description-wrapper">
						<span class="xpro-pricing-item-text"><?php echo wpautop( $wp_embed->autoembed( $settings->pricing_text ) ); ?></span>
					</div>
			<?php
		}
	}
	?>

	<!-- btn before feature -->
<?php if ( 'before_feature' === $settings->pricing_button_position ) { ?>
	<div class="xpro-pricing-btn-wrapper">
	<?php if ( ! empty( $settings->pricing_button ) ) { ?>
		<a href="<?php echo esc_url( $settings->pricing_link ); ?>" class="xpro-pricing-item-btn" target="<?php echo esc_attr( $settings->pricing_link_target ); ?>" <?php echo esc_attr( $link_nofollow ); ?>><?php echo esc_attr( $settings->pricing_button ); ?></a>
	<?php } ?>
	</div>
<?php } ?>

	<!-- feature -->
	<div class="xpro-pricing-features">
		<?php if ( ! empty( $settings->pricing_features_title ) ) { ?>
		<<?php echo esc_attr( $settings->pricing_features_title_tag ); ?> class="xpro-pricing-features-title"><?php echo esc_attr( $settings->pricing_features_title ); ?></<?php echo esc_attr( $settings->pricing_title_tag ); ?>>
		<?php } ?>
		<ul class="xpro-pricing-features-list">
			<?php
			$pricing_features_form_counts = count( $settings->pricing_features_form );
			for ( $i = 0; $i < $pricing_features_form_counts; $i++ ) {
				$item = $settings->pricing_features_form[ $i ];
				?>

				<li class="<?php echo esc_attr( $item->pricing_feature_status ); ?>">
					<?php if ( $item->pricing_feature_icon ) { ?>
					<span class="xpro-pricing-feature-icon "><i class="<?php echo esc_attr( $item->pricing_feature_icon ); ?>"></i></span>
					<?php } ?>
					<span class="xpro-pricing-feature-title"><?php echo esc_attr( $item->pricing_feature_list_title ); ?>
						<?php if ( ! empty( $item->pricing_tooltip_text ) ) { ?>
					<i class="fas fa-exclamation xpro-pricing-tooltip-toggle ">
						<span class="xpro-pricing-tooltip"><?php echo esc_attr( $item->pricing_tooltip_text ); ?></span>
					</i>
					<?php } ?>

					</span>
				</li>

		<?php } ?>
		</ul>
	</div>

	<!-- Separator after -->
<?php if ( 'show' === $settings->pricing_display_separator ) { ?>
	<div class="xpro-pricing-item-separator"></div>
<?php } ?>

	<!-- price tag before featured -->
<?php if ( 'after_featured' === $settings->pricing_position ) { ?>
	<div class="xpro-pricing-price-box">
		<div class="xpro-pricing-price-tag">
			<!-- currency -->
			<?php if ( ! empty( $settings->pricing_currency ) ) { ?>
				<span class="xpro-pricing-currency"><?php echo esc_attr( $settings->pricing_currency ); ?></span>
			<?php } ?>

			<!-- price -->
			<?php if ( ! empty( $settings->pricing_price ) ) { ?>
				<span class="xpro-pricing-price"><?php echo esc_attr( $settings->pricing_price ); ?></span>
			<?php } ?>
		</div>

		<!-- period -->
		<?php if ( ! empty( $settings->pricing_period ) ) { ?>
			<span class="xpro-pricing-price-period"><?php echo esc_attr( $settings->pricing_period ); ?></span>
		<?php } ?>
	</div>
<?php } ?>

		<!-- btn after feature -->
<?php if ( 'after_feature' === $settings->pricing_button_position ) { ?>
	<div class="xpro-pricing-btn-wrapper">
	<?php if ( ! empty( $settings->pricing_button ) ) { ?>
		<a href="<?php echo esc_url( $settings->pricing_link ); ?>" class="xpro-pricing-item-btn" target="<?php echo esc_attr( $settings->pricing_link_target ); ?>" <?php echo esc_attr( $link_nofollow ); ?>><?php echo esc_attr( $settings->pricing_button ); ?></a>
	<?php } ?>
	</div>

<?php } ?>
	</div>
</div>


<?php if ( ! empty( $settings->pricing_link ) ) { ?>
	</a>
<?php } ?>
