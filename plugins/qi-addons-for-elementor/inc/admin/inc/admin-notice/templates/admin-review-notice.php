<div class="qodef-admin-review-notice notice is-dismissible notice-info">
	<div class="qodef-admin-notice-inner">
		<h3 class="qodef-admin-notice-title">
			<?php echo esc_html__( 'Enjoying Qi Addons?', 'qi-addons-for-elementor' ); ?>
		</h3>
		<p class="qodef-admin-notice-description">
			<?php echo esc_html__( 'Hi there! It’s now been more than a week since you got your copy of Qi Addons for Elementor. We hope our plugin is proving helpful in your mission to create your beautiful new website. If you can spare a few moments, please let us know what you think of Qi Addons by leaving a rating. We’d love to hear what you think!', 'qi-addons-for-elementor' ); ?>
		</p>
		<p class="qodef-admin-notice-submit">
			<a href="https://wordpress.org/support/plugin/qi-addons-for-elementor/reviews/?filter=5#new-post" target="_blank" rel="noopener noreferrer" class="qodef-review-button qodef-review--review-action qodef-btn qodef-btn-solid"><?php echo esc_html__('Yes, you deserve it', 'qi-addons-for-elementor'); ?></a>
			<a href="#" class="qodef-review-button qodef-review--maybe-later-action qodef-btn qodef-btn-outlined"><?php echo esc_html__('No, maybe later', 'qi-addons-for-elementor'); ?></a>
			<a href="#" class="qodef-review-button qodef-review--already-reviewed-action qodef-btn qodef-btn-outlined"><?php echo esc_html__('I already did', 'qi-addons-for-elementor'); ?></a>
		</p>
		<a class="qodef-admin-notice-dismiss">
			<svg x="0px" y="0px" width="11px" height="11px" viewBox="0 0 11 11" enable-background="new 0 0 11 11" xml:space="preserve">
				<g>
					<path d="M0.288,9.678L4.419,5.5L0.288,1.32c-0.376-0.344-0.384-0.696-0.022-1.057c0.359-0.359,0.71-0.352,1.055,0.024L5.5,4.419
						l4.179-4.132c0.346-0.376,0.696-0.383,1.058-0.024c0.359,0.36,0.352,0.713-0.024,1.057L6.58,5.5l4.132,4.179
						c0.376,0.346,0.384,0.697,0.024,1.057c-0.361,0.36-0.712,0.353-1.058-0.023L5.5,6.58L1.32,10.711
						c-0.345,0.376-0.696,0.384-1.055,0.023C-0.097,10.375-0.088,10.024,0.288,9.678z"></path>
				</g>
			</svg>
		</a>
		<?php wp_nonce_field( 'qi-addons-for-elementor-review-notice-nonce', 'qi-addons-for-elementor-review-notice-nonce' ); ?>
	</div>
</div>
