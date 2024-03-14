<div id="quick_adsense_block_bottom" class="quick_adsense_block" style="margin: 30px 0 0;">
	<div class="quick_adsense_block_labels" style="width: auto;">
		<span>Adsense Codes - Ads on Post Body</span>
	</div>
	<div class="clear"></div>
	<p>Paste up to 10 Ads codes on Post Body as assigned above. Ads codes provided must not be identical, repeated codes may result the Ads not being display correctly. Ads will never displays more than once in a page.</p>
</div>

<div id="quick_adsense_onpost_content_controls_wrapper">
	<div id="quick_adsense_onpost_content_global_controls_wrapper">
		<p class="quick_adsense_onpost_adunits_styling_controls">
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'',
					'quick_adsense_settings_onpost_enable_global_style',
					'quick_adsense_settings[onpost_enable_global_style]',
					quick_adsense_get_value( $args, 'onpost_enable_global_style' ),
					null,
					'input',
					'margin: -3px 10px 0 0;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
			<span>Use for all</span>
			<wbr />Alignment
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'select',
					'',
					'quick_adsense_settings_onpost_global_alignment',
					'quick_adsense_settings[onpost_global_alignment]',
					quick_adsense_get_value( $args, 'onpost_global_alignment' ),
					quick_adsense_get_value( $args, 'alignment_options' ),
					'input',
					'margin: -6px 20px 0 10px; width: 73px;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
			<wbr />margin
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'number',
					'',
					'quick_adsense_settings_onpost_global_margin',
					'quick_adsense_settings[onpost_global_margin]',
					quick_adsense_get_value( $args, 'onpost_global_margin' ),
					null,
					'input',
					'margin: -1px 10px 0 10px; width: 62px;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
			px
		</p>
	</div>

	<div id="quick_adsense_onpost_content_adunits_wrapper">
		<div id="quick_adsense_onpost_content_adunits_initial_wrapper">
			<?php
			for ( $i = 1; $i <= 3; $i++ ) {
				$args['adunit_index'] = $i;
				// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
				// Contains a textarea which need to allow output of scripts, iframes etc necessary to output ads and trackers.
				echo quick_adsense_load_file( 'templates/adunit-onpost.php', $args );
				// phpcs:enable
			}
			?>
		</div>
		<div id="quick_adsense_onpost_content_adunits_all_wrapper" style="display: none;">
			<?php
			for ( $i = 4; $i <= 10; $i++ ) {
				$args['adunit_index'] = $i;
				// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
				// Contains a textarea which need to allow output of scripts, iframes etc necessary to output ads and trackers.
				echo quick_adsense_load_file( 'templates/adunit-onpost.php', $args );
				// phpcs:enable
			}
			?>
		</div>
		<a id="quick_adsense_onpost_content_adunits_showall_button" class="input button-secondary">
			<span class="dashicons dashicons-arrow-down"></span> <b>Show All</b>
		</a>
	</div>
</div>
