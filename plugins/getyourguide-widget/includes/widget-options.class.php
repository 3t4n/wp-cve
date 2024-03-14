<?php

class GetYourGuide_Widget_Options {
	const OPTION_NUMBER_OF_ITEMS = 'number_of_items';
	const OPTION_QUERY = 'query';
    const OPTION_CAMPAIGN_PARAM = 'campaign_param';

	public static function get_default_options() {
		return [
			self::OPTION_NUMBER_OF_ITEMS => 10,
			self::OPTION_QUERY           => __( 'Paris', 'getyourguide-widget' ),
            self::OPTION_CAMPAIGN_PARAM  => '',
		];
	}
}