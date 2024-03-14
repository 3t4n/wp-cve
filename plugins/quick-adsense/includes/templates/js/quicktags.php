<?php foreach ( $args['active_ads'] as $adtive_ad_index ) { ?>
	QTags.addButton("quick_adsense_quicktag_onpost_ad_<?php echo esc_js( $adtive_ad_index ); ?>", "Ads<?php echo esc_js( $adtive_ad_index ); ?>", "\n<!--Ads<?php echo esc_js( $adtive_ad_index ); ?>-->\n", "", "", "Ads<?php echo esc_js( $adtive_ad_index ); ?>", 201);
<?php } ?>
<?php if ( $args['enable_randomads'] ) { ?>
	QTags.addButton("quick_adsense_quicktag_randomads", "RndAds", "\n<!--RndAds-->\n", "", "", "Random Ads", 201);
<?php } ?>
<?php if ( $args['enable_disableads'] ) { ?>
	QTags.addButton("quick_adsense_quicktag_noads", "NoAds", "\n<!--NoAds-->\n", "", "", "No Ads", 201);
	QTags.addButton("quick_adsense_quicktag_offdef", "OffDef", "\n<!--OffDef-->\n", "", "", "No Def", 201);
	QTags.addButton("quick_adsense_quicktag_offwidget", "OffWidget", "\n<!--OffWidget-->\n", "", "", "No AdWidgets", 201);
<?php } ?>
<?php if ( $args['enable_positionads'] ) { ?>
	QTags.addButton("quick_adsense_quicktag_offbegin", "OffBegin", "\n<!--OffBegin-->\n", "", "", "Disable Beginning of Post Ads", 201);
	QTags.addButton("quick_adsense_quicktag_offmiddle", "OffMiddle", "\n<!--OffMiddle-->\n", "", "", "Disable Middle of Post Ads", 201);
	QTags.addButton("quick_adsense_quicktag_offend", "OffEnd", "\n<!--OffEnd-->\n", "", "", "Disable End of Post Ads", 201);
	QTags.addButton("quick_adsense_quicktag_offafmore", "OffAfMore", "\n<!--OffAfMore-->\n", "", "", "OffAfMore", 201);
	QTags.addButton("quick_adsense_quicktag_offbflastpara", "OffBfLastPara", "\n<!--OffBfLastPara-->\n", "", "", "OffBfLastPara", 201);
<?php } ?>
