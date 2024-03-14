<div class="quick_adsense_block">
	<div class="quick_adsense_block_labels">Quicktag</div>
	<div class="quick_adsense_block_controls">
		<p>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'<b>Show Quicktag Buttons on the HTML Edit Post SubPanel (Classic Editor)</b>',
					'quick_adsense_settings_enable_quicktag_buttons',
					'quick_adsense_settings[enable_quicktag_buttons]',
					quick_adsense_get_value( $args, 'enable_quicktag_buttons' ),
					null,
					'input',
					'margin: -1px 10px 0 0;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</p>
		<p>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'Hide <b>&lt;!--RndAds--&gt;</b> from Quicktag Buttons',
					'quick_adsense_settings_disable_randomads_quicktag_button',
					'quick_adsense_settings[disable_randomads_quicktag_button]',
					quick_adsense_get_value( $args, 'disable_randomads_quicktag_button' ),
					null,
					'input',
					'margin: -1px 10px 0 0;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</p>
		<p>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'Hide <b>&lt;!--NoAds--&gt;</b>, <b>&lt;!--OffDef--&gt;</b>, <b>&lt;!--OffWidget--&gt;</b> from Quicktag Buttons',
					'quick_adsense_settings_disable_disablead_quicktag_buttons',
					'quick_adsense_settings[disable_disablead_quicktag_buttons]',
					quick_adsense_get_value( $args, 'disable_disablead_quicktag_buttons' ),
					null,
					'input',
					'margin: -1px 10px 0 0;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</p>
		<p>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'Hide <b>&lt;!--OffBegin--&gt;</b>, <b>&lt;!--OffMiddle--&gt;</b>, <b>&lt;!--OffEnd--&gt;</b>, <b>&lt;!--OffAfMore--&gt;</b>, <b>&lt;!--OffBfLastPara--&gt;</b> from Quicktag Buttons',
					'quick_adsense_settings_disable_positionad_quicktag_buttons',
					'quick_adsense_settings[disable_positionad_quicktag_buttons]',
					quick_adsense_get_value( $args, 'disable_positionad_quicktag_buttons' ),
					null,
					'input',
					'margin: -1px 10px 0 0;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</p>
		<div class="clear"></div>
		Insert Ads into a post, on-the-fly:
		<ol>
			<li>Insert <b>&lt;!--Ads1--&gt;</b>, <b>&lt;!--Ads2--&gt;</b> etc. into a post to show the <b>Particular Ads</b> at specific location.</li>
			<li>Insert <b>&lt;!--RndAds--&gt;</b> (or more) into a post to show the <b>Random Ads</b> at specific location.</li>
		</ol>
		<div class="clear"></div>
		Disable Ads in a post, on-the-fly:
		<ol>
			<li>Insert <b>&lt;!--NoAds--&gt;</b> to disable all Ads in a post <i>(does not affect Ads on Sidebar)</i>.</li>
			<li>Insert <b>&lt;!--OffDef--&gt;</b> to disable the default positioned Ads, and use &lt;!--Ads1--&gt;, &lt;!--Ads2--&gt;, etc. to insert Ad <i>(does not affect Ads on Sidebar)</i>.</li>
			<li>Insert <b>&lt;!--OffWidget--&gt;</b> to disable all Ads on Sidebar.</li>
			<li>Insert <b>&lt;!--OffBegin--&gt;</b>, <b>&lt;!--OffMiddle--&gt;</b>, <b>&lt;!--OffEnd--&gt;</b> to <b>disable Ads at Beginning</b>, <b>Middle or End of Post</b>.</li>
			<li>Insert <b>&lt;!--OffAfMore--&gt;</b>, <b>&lt;!--OffBfLastPara--&gt;</b> to <b>disable Ads right after the &lt;!--more--&gt; tag</b>, or <b>right before the last Paragraph</b>.</li>
		</ol>
		<div class="clear"></div>
		<i>Tags can be inserted into a post via the additional Quicktag Buttons at the HTML Edit Post SubPanel.</i>
	</div>
	<div class="clear"></div>
</div>
