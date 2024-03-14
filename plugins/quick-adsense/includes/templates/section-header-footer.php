<div id="quick_adsense_top_sections_wrapper">
	<div class="quick_adsense_block">
		<label>Header Embed Code</label>
		<div>
			<?php
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			// This textarea needs to allow output of scripts, iframes etc necessary to output ads and trackers.
			echo quickadsense_get_control(
				'textarea-big',
				'',
				'quick_adsense_settings_header_embed_code',
				'quick_adsense_settings[header_embed_code]',
				quick_adsense_get_value( $args, 'header_embed_code' )
			);
			// phpcs:enable
			?>
		</div>
		<div class="clear"></div>
		<label>Footer Embed Code</label>
		<div>
			<?php
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			// This textarea needs to allow output of scripts, iframes etc necessary to output ads and trackers.
			echo quickadsense_get_control(
				'textarea-big',
				'',
				'quick_adsense_settings_footer_embed_code',
				'quick_adsense_settings[footer_embed_code]',
				quick_adsense_get_value( $args, 'footer_embed_code' )
			);
			// phpcs:enable
			?>
		</div>
		<div class="clear"></div>
	</div>
</div>
