<div class="quick_adsense_block">
	<div class="quick_adsense_block_labels">Appearance</div>
	<div class="quick_adsense_block_controls">
	<p>
		<span>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'<b id="quick_adsense_settings_enable_on_posts_label">Posts</b>',
					'quick_adsense_settings_enable_on_posts',
					'quick_adsense_settings[enable_on_posts]',
					quick_adsense_get_value( $args, 'enable_on_posts' ),
					null,
					'input',
					'margin: -1px 10px 0 0;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</span>
		<span>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'<b id="quick_adsense_settings_enable_on_pages_label">Pages</b>',
					'quick_adsense_settings_enable_on_pages',
					'quick_adsense_settings[enable_on_pages]',
					quick_adsense_get_value( $args, 'enable_on_pages' ),
					null,
					'input',
					'margin: -1px 10px 0 15px;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</span>
	</p>
	<p>
		<span>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'<b id="quick_adsense_settings_enable_on_homepage_label">Homepage</b>',
					'quick_adsense_settings_enable_on_homepage',
					'quick_adsense_settings[enable_on_homepage]',
					quick_adsense_get_value( $args, 'enable_on_homepage' ),
					null,
					'input',
					'margin: -1px 10px 0 0;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</span>
		<span>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'<b id="quick_adsense_settings_enable_on_categories_label">Categories</b>',
					'quick_adsense_settings_enable_on_categories',
					'quick_adsense_settings[enable_on_categories]',
					quick_adsense_get_value( $args, 'enable_on_categories' ),
					null,
					'input',
					'margin: -1px 10px 0 15px;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</span>
		<span>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'<b id="quick_adsense_settings_enable_on_archives_label">Archives</b>',
					'quick_adsense_settings_enable_on_archives',
					'quick_adsense_settings[enable_on_archives]',
					quick_adsense_get_value( $args, 'enable_on_archives' ),
					null,
					'input',
					'margin: -1px 10px 0 15px;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</span>
		<span>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'<b id="quick_adsense_settings_enable_on_tags_label">Tags</b>',
					'quick_adsense_settings_enable_on_tags',
					'quick_adsense_settings[enable_on_tags]',
					quick_adsense_get_value( $args, 'enable_on_tags' ),
					null,
					'input',
					'margin: -1px 10px 0 15px;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</span>
		<span>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'<b id="quick_adsense_settings_enable_all_possible_ads_label">Place all possible Ads on these pages</b>',
					'quick_adsense_settings_enable_all_possible_ads',
					'quick_adsense_settings[enable_all_possible_ads]',
					quick_adsense_get_value( $args, 'enable_all_possible_ads' ),
					null,
					'input',
					'margin: -1px 10px 0 35px;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</span>
	</p>
	<p>
		<span>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'<b id="quick_adsense_settings_disable_widgets_on_homepage_label">Disable AdsWidget on Homepage</b>',
					'quick_adsense_settings_disable_widgets_on_homepage',
					'quick_adsense_settings[disable_widgets_on_homepage]',
					quick_adsense_get_value( $args, 'disable_widgets_on_homepage' ),
					null,
					'input',
					'margin: -1px 10px 0 0;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</span>
	</p>
	<p>
		<span>
			<?php
			echo wp_kses(
				quickadsense_get_control(
					'checkbox',
					'<b id="quick_adsense_settings_disable_for_loggedin_users_label">Hide Ads when user is logged in to WordPress</b>',
					'quick_adsense_settings_disable_for_loggedin_users',
					'quick_adsense_settings[disable_for_loggedin_users]',
					quick_adsense_get_value( $args, 'disable_for_loggedin_users' ),
					null,
					'input',
					'margin: -1px 10px 0 0;'
				),
				quick_adsense_get_allowed_html()
			);
			?>
		</span>
	</p>
	</div>
	<div class="clear"></div>
</div>
