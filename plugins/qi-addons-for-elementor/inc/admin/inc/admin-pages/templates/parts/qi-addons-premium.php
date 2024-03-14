<?php
	$button_text = apply_filters( 'qi_addons_for_elementor_filter_welcome_premium_box_link_text', esc_html__( 'Upgrade Now', 'qi-addons-for-elementor' ) );
	$button_link = apply_filters( 'qi_addons_for_elementor_filter_welcome_premium_box_link', 'https://qodeinteractive.com/pricing/' );
	$button_link = add_query_arg(
		array(
			'utm_source' => 'dash',
			'utm_medium' => 'qiaddons',
			'utm_campaign' => 'welcome',
		),
		$button_link
	);
	?>
<div class="qodef-section-box qodef-section-qi-addons-premium">
	<div class="qodef-section-box-image">
		<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_ADMIN_URL_PATH . '/inc/admin-pages/assets/img/qi-addons-premium.png' ); ?>" alt="<?php esc_attr_e( 'Free Demos', 'qi-addons-for-elementor' ); ?>" />
	</div>
	<div class="qodef-section-box-content">
		<h2>
			<?php esc_html_e( 'Qi Addons Premium', 'qi-addons-for-elementor' ); ?>
			<svg class="qodef-star" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="14px" viewBox="0 0 15 14"  xml:space="preserve">
				<g>
					<path fill="#FFFFFF" d="M15,5.362l-3.728,3.565L12.13,14l-4.615-2.377L2.87,14l0.887-5.072L0,5.362l5.178-0.753L7.515,0 l2.308,4.609L15,5.362z"/>
				</g>
			</svg>
		</h2>
		<p class="qodef-large"><?php esc_html_e( 'More addons, more power & flexibility', 'qi-addons-for-elementor' ); ?></p>
		<a class="qodef-btn qodef-btn-solid" href="<?php echo esc_url( $button_link ); ?>" target="_blank"><?php echo esc_html( $button_text ); ?></a>
	</div>
</div>
