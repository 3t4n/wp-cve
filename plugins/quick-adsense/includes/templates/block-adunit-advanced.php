<?php
$adunit_index = quick_adsense_get_value( $args, 'adunit_index' );
$location     = quick_adsense_get_value( $args, 'location' );
?>
<p class="quick_adsense_<?php echo esc_attr( $location ); ?>_adunits_device_controls">
	<b>Hide by Device Type:</b><br />
	<?php
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Mobile',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_hide_device_mobile',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_hide_device_mobile]',
			quick_adsense_get_value( $args, '_ad_' . $adunit_index . '_hide_device_mobile' ),
			null,
			'input',
			'margin: -1px 5px 0 0;'
		),
		quick_adsense_get_allowed_html()
	);
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Tablet',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_hide_device_tablet',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_hide_device_tablet]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_hide_device_tablet' ),
			null,
			'input',
			'margin: -1px 5px 0 15px;'
		),
		quick_adsense_get_allowed_html()
	);
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Desktop',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_hide_device_desktop',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_hide_device_desktop]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_hide_device_desktop' ),
			null,
			'input',
			'margin: -1px 5px 0 15px;'
		),
		quick_adsense_get_allowed_html()
	);
	?>
</p>
<p class="quick_adsense_<?php echo esc_attr( $location ); ?>_adunits_device_controls">
	<b>Hide by Visitor Source:</b><br />
	<?php
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Search Engine',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_hide_visitor_searchengine',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_hide_visitor_searchengine]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_hide_visitor_searchengine' ),
			null,
			'input',
			'margin: -1px 5px 0 0;'
		),
		quick_adsense_get_allowed_html()
	);
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Indirect',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_hide_visitor_indirect',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_hide_visitor_indirect]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_hide_visitor_indirect' ),
			null,
			'input',
			'margin: -1px 5px 0 15px;'
		),
		quick_adsense_get_allowed_html()
	);
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Direct',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_hide_visitor_direct',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_hide_visitor_direct]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_hide_visitor_direct' ),
			null,
			'input',
			'margin: -1px 5px 0 15px;'
		),
		quick_adsense_get_allowed_html()
	);
	?>
</p>
<p class="quick_adsense_<?php echo esc_attr( $location ); ?>_adunits_device_controls">
	<b>Hide by Visitor Type:</b><br />
	<?php
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Bots',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_hide_visitor_bot',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_hide_visitor_bot]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_hide_visitor_bot' ),
			null,
			'input',
			'margin: -1px 5px 0 0;'
		),
		quick_adsense_get_allowed_html()
	);
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Known Browser',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_hide_visitor_knownbrowser',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_hide_visitor_knownbrowser]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_hide_visitor_knownbrowser' ),
			null,
			'input',
			'margin: -1px 5px 0 15px;'
		),
		quick_adsense_get_allowed_html()
	);
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Unknown Browser',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_hide_visitor_unknownbrowser',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_hide_visitor_unknownbrowser]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_hide_visitor_unknownbrowser' ),
			null,
			'input',
			'margin: -1px 5px 0 15px;'
		),
		quick_adsense_get_allowed_html()
	);
	?>
	<br />
	<?php
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Guest',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_hide_visitor_guest',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_hide_visitor_guest]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_hide_visitor_guest' ),
			null,
			'input',
			'margin: -1px 5px 0 0;'
		),
		quick_adsense_get_allowed_html()
	);
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Logged-in',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_hide_visitor_loggedin',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_hide_visitor_loggedin]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_hide_visitor_loggedin' ),
			null,
			'input',
			'margin: -1px 5px 0 15px;'
		),
		quick_adsense_get_allowed_html()
	);
	?>
</p>
<p class="quick_adsense_<?php echo esc_attr( $location ); ?>_adunits_device_controls">
	<b>Limit by Visitor Countries:</b><br />
	<?php
	echo wp_kses(
		quickadsense_get_control(
			'multiselect',
			'',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_limit_visitor_country',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_limit_visitor_country][]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_limit_visitor_country' ),
			quick_adsense_get_countries()
		),
		quick_adsense_get_allowed_html()
	);
	?>
</p>
<p class="quick_adsense_<?php echo esc_attr( $location ); ?>_adunits_device_controls">
	<b>Ad Stats:</b><br />
	<?php
	echo wp_kses(
		quickadsense_get_control(
			'checkbox',
			'Enable Stats',
			'quick_adsense_settings_' . $location . '_ad_' . $adunit_index . '_enable_stats',
			'quick_adsense_settings[' . $location . '_ad_' . $adunit_index . '_enable_stats]',
			quick_adsense_get_value( $args, $location . '_ad_' . $adunit_index . '_enable_stats' ),
			null,
			'input',
			'margin: -1px 5px 0 0;'
		),
		quick_adsense_get_allowed_html()
	);
	?>
	<br />
	<input class="quick_adsense_<?php echo esc_attr( $location ); ?>_ad_show_stats input button-secondary" data-index="<?php echo esc_attr( $adunit_index ); ?>" type="button" value="View Stats" />&nbsp;
	<input class="quick_adsense_<?php echo esc_attr( $location ); ?>_ad_reset_stats input button-secondary right" data-index="<?php echo esc_attr( $adunit_index ); ?>" type="button" value="Reset Stats" />
</p>
