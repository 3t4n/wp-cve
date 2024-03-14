<div class="quick_adsense_block">
	<div class="quick_adsense_block_labels">Adsense</div>
	<div class="quick_adsense_block_controls">
		Place up to
		<?php
		$max_ads_count = [];
		for ( $i = 0; $i <= 10; $i++ ) {
			$max_ads_count[] = [
				'text'  => $i,
				'value' => $i,
			];
		}
		echo wp_kses(
			quickadsense_get_control(
				'select',
				'',
				'quick_adsense_settings_max_ads_per_page',
				'quick_adsense_settings[max_ads_per_page]',
				quick_adsense_get_value( $args, 'max_ads_per_page' ),
				$max_ads_count,
				'input',
				'margin: -2px 10px 0 40px;'
			),
			quick_adsense_get_allowed_html()
		);
		?>
		Ads on a page
	</div>
	<div class="clear"></div>
</div>
