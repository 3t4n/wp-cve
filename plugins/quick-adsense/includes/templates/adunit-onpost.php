<?php $adunit_index = quick_adsense_get_value( $args, 'adunit_index' ); ?>
<div id="quick_adsense_onpost_adunits_control_<?php echo esc_attr( $adunit_index ); ?>" class="quick_adsense_onpost_adunits_control_wrapper">
	<div class="quick_adsense_onpost_adunits_label">Ads<?php echo esc_attr( $adunit_index ); ?></div>
	<div class="quick_adsense_onpost_adunits_control">
		<?php
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		// This textarea needs to allow output of scripts, iframes etc necessary to output ads and trackers.
		echo quickadsense_get_control(
			'textarea',
			'',
			'quick_adsense_settings_onpost_ad_' . $adunit_index . '_content',
			'quick_adsense_settings[onpost_ad_' . $adunit_index . '_content]',
			quick_adsense_get_value( $args, 'onpost_ad_' . $adunit_index . '_content' ),
			null,
			'input',
			'display: block; margin: 0 0 10px 0',
			'Enter Code'
		);
		// phpcs:enable
		?>
		<p class="quick_adsense_onpost_adunits_styling_controls">
			Alignment
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'select',
					'',
					'quick_adsense_settings_onpost_ad_' . $adunit_index . '_alignment',
					'quick_adsense_settings[onpost_ad_' . $adunit_index . '_alignment]',
					quick_adsense_get_value( $args, 'onpost_ad_' . $adunit_index . '_alignment' ),
					quick_adsense_get_value( $args, 'alignment_options' ),
					'input',
					'margin: -2px 20px 0 10px;'
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
					'quick_adsense_settings_onpost_ad_' . $adunit_index . '_margin',
					'quick_adsense_settings[onpost_ad_' . $adunit_index . '_margin]',
					quick_adsense_get_value( $args, 'onpost_ad_' . $adunit_index . '_margin' ),
					null,
					'input',
					'margin: -2px 10px 0 10px; width: 52px;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
			px
		</p>
		<?php
		quick_adsense_load_file( 'templates/block-adunit-advanced.php', $args, true );
		?>
	</div>
	<div class="clear"></div>
</div>
