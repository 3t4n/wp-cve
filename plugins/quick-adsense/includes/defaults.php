<?php
/**
 * The default settings for the plugin.
 */
function quick_adsense_get_defaults() {
	$settings = [];

	$settings['max_ads_per_page'] = '3';

	$settings['enable_position_beginning_of_post'] = '1';
	$settings['ad_beginning_of_post']              = '1';
	$settings['enable_position_middle_of_post']    = '0';
	$settings['ad_middle_of_post']                 = '0';
	$settings['enable_position_end_of_post']       = '1';
	$settings['ad_end_of_post']                    = '0';

	$settings['enable_position_after_more_tag']   = '0';
	$settings['ad_after_more_tag']                = '0';
	$settings['enable_position_before_last_para'] = '0';
	$settings['ad_before_last_para']              = '0';

	for ( $i = 1; $i <= 3; $i++ ) {
		$settings[ 'enable_position_after_para_option_' . $i ]      = '0';
		$settings[ 'ad_after_para_option_' . $i ]                   = '0';
		$settings[ 'position_after_para_option_' . $i ]             = '1';
		$settings[ 'enable_jump_position_after_para_option_' . $i ] = '0';
	}

	for ( $i = 1; $i <= 1; $i++ ) {
		$settings[ 'enable_position_after_image_option_' . $i ]      = '0';
		$settings[ 'ad_after_image_option_' . $i ]                   = '0';
		$settings[ 'position_after_image_option_' . $i ]             = '1';
		$settings[ 'enable_jump_position_after_image_option_' . $i ] = '0';
	}

	$settings['enable_on_posts'] = '1';
	$settings['enable_on_pages'] = '1';

	$settings['enable_on_homepage']      = '0';
	$settings['enable_on_categories']    = '0';
	$settings['enable_on_archives']      = '0';
	$settings['enable_on_tags']          = '0';
	$settings['enable_all_possible_ads'] = '0';

	$settings['disable_widgets_on_homepage'] = '0';

	$settings['disable_for_loggedin_users'] = '0';

	$settings['enable_quicktag_buttons']             = '1';
	$settings['disable_randomads_quicktag_button']   = '0';
	$settings['disable_disablead_quicktag_buttons']  = '0';
	$settings['disable_positionad_quicktag_buttons'] = '0';

	$settings['onpost_enable_global_style'] = '0';
	$settings['onpost_global_alignment']    = '2';
	$settings['onpost_global_margin']       = '10';

	for ( $i = 1; $i <= 10; $i++ ) {
		$settings[ 'onpost_ad_' . $i . '_content' ]   = '';
		$settings[ 'onpost_ad_' . $i . '_alignment' ] = '2';
		$settings[ 'onpost_ad_' . $i . '_margin' ]    = '10';

		$settings[ 'widget_ad_' . $i . '_content' ] = '';
	}
	return $settings;
}

/**
 * Action to update the settings storage for previous versions of the plugin.
 */
add_action(
	'init',
	function() {
		$settings = get_option( 'quick_adsense_settings' );
		if ( isset( $settings ) && is_array( $settings ) ) {
			// Existing 2.1+ User.
			return;
		} else {
			// New User OR V2.0 User OR V1.X User.
			$quick_adsense_2_settings = get_option( 'quick_adsense_2_options' );
			if ( isset( $quick_adsense_2_settings ) && is_array( $quick_adsense_2_settings ) && ( count( $quick_adsense_2_settings ) > 1 ) ) {
				// V2.0 User.
				$settings = quick_adsense_get_defaults();

				$settings['max_ads_per_page'] = quick_adsense_get_value( $quick_adsense_2_settings, 'AdsDisp', $settings['max_ads_per_page'] );

				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'BegnAds', false ) ) {
					$settings['enable_position_beginning_of_post'] = '1';
				}
				$settings['ad_beginning_of_post'] = quick_adsense_get_value( $quick_adsense_2_settings, 'BegnRnd', $settings['ad_beginning_of_post'] );
				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'MiddAds', false ) ) {
					$settings['enable_position_middle_of_post'] = '1';
				}
				$settings['ad_middle_of_post'] = quick_adsense_get_value( $quick_adsense_2_settings, 'MiddRnd', $settings['ad_middle_of_post'] );
				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'EndiAds', false ) ) {
					$settings['enable_position_end_of_post'] = '1';
				}
				$settings['ad_end_of_post'] = quick_adsense_get_value( $quick_adsense_2_settings, 'EndiRnd', $settings['ad_end_of_post'] );

				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'MoreAds', false ) ) {
					$settings['enable_position_after_more_tag'] = '1';
				}
				$settings['ad_after_more_tag'] = quick_adsense_get_value( $quick_adsense_2_settings, 'MoreRnd', $settings['ad_after_more_tag'] );

				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'LapaAds', false ) ) {
					$settings['enable_position_before_last_para'] = '1';
				}
				$settings['ad_before_last_para'] = quick_adsense_get_value( $quick_adsense_2_settings, 'LapaRnd', $settings['ad_before_last_para'] );

				for ( $i = 1; $i <= 3; $i++ ) {
					if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'Par' . $i . 'Ads', false ) ) {
						$settings[ 'enable_position_after_para_option_' . $i ] = '1';
					}
					if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'Par' . $i . 'Rnd', false ) ) {
						$settings[ 'ad_after_para_option_' . $i ] = '1';
					}
					if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'Par' . $i . 'Nup', false ) ) {
						$settings[ 'position_after_para_option_' . $i ] = '1';
					}
					if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'Par' . $i . 'Con', false ) ) {
						$settings[ 'enable_jump_position_after_para_option_' . $i ] = '1';
					}
				}

				for ( $i = 1; $i <= 1; $i++ ) {
					if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'Img' . $i . 'Ads', false ) ) {
						$settings[ 'enable_position_after_image_option_' . $i ] = '1';
					}
					if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'Img' . $i . 'Rnd', false ) ) {
						$settings[ 'ad_after_image_option_' . $i ] = '1';
					}
					if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'Img' . $i . 'Nup', false ) ) {
						$settings[ 'position_after_image_option_' . $i ] = '1';
					}
					if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'Img' . $i . 'Con', false ) ) {
						$settings[ 'enable_jump_position_after_image_option_' . $i ] = '1';
					}
				}

				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'AppPost', false ) ) {
					$settings['enable_on_posts'] = '1';
				}
				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'AppPage', false ) ) {
					$settings['enable_on_pages'] = '1';
				}

				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'AppHome', false ) ) {
					$settings['enable_on_homepage'] = '1';
				}
				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'AppCate', false ) ) {
					$settings['enable_on_categories'] = '1';
				}
				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'AppArch', false ) ) {
					$settings['enable_on_archives'] = '1';
				}
				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'AppTags', false ) ) {
					$settings['enable_on_tags'] = '1';
				}
				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'AppMaxA', false ) ) {
					$settings['enable_all_possible_ads'] = '1';
				}

				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'AppSide', false ) ) {
					$settings['disable_widgets_on_homepage'] = '1';
				}

				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'AppLogg', false ) ) {
					$settings['disable_for_loggedin_users'] = '1';
				}

				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'QckTags', false ) ) {
					$settings['enable_quicktag_buttons'] = '1';
				}
				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'QckRnds', false ) ) {
					$settings['disable_randomads_quicktag_button'] = '1';
				}
				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'QckOffs', false ) ) {
					$settings['disable_disablead_quicktag_buttons'] = '1';
				}
				if ( false !== quick_adsense_get_value( $quick_adsense_2_settings, 'QckOfPs', false ) ) {
					$settings['disable_positionad_quicktag_buttons'] = '1';
				}

				for ( $i = 1; $i <= 10; $i++ ) {
					$settings[ 'onpost_ad_' . $i . '_content' ]   = quick_adsense_get_value( $quick_adsense_2_settings, 'AdsCode' . $i, $settings[ 'onpost_ad_' . $i . '_content' ] );
					$settings[ 'onpost_ad_' . $i . '_alignment' ] = quick_adsense_get_value( $quick_adsense_2_settings, 'AdsAdsAlignMargin' . $i, $settings[ 'onpost_ad_' . $i . '_alignment' ] );
					$settings[ 'onpost_ad_' . $i . '_margin' ]    = quick_adsense_get_value( $quick_adsense_2_settings, 'AdsMargin' . $i, $settings[ 'onpost_ad_' . $i . '_margin' ] );

					$settings[ 'widget_ad_' . $i . '_content' ] = quick_adsense_get_value( $quick_adsense_2_settings, 'WidCode' . $i, $settings[ 'widget_ad_' . $i . '_content' ] );
				}
				update_option( 'quick_adsense_settings', $settings );
				update_option( 'quick_adsense_2_options_bak', $quick_adsense_2_settings );
				delete_option( 'quick_adsense_2_options' );
			} else {
				// New User or V1.X User.
				$quick_adsense_1_ads_disp = get_option( 'AdsDisp' );
				if ( isset( $quick_adsense_1_ads_disp ) && in_array( $quick_adsense_1_ads_disp, [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10' ], true ) ) {
					// V1.X User.
					$settings                     = quick_adsense_get_defaults();
					$settings['max_ads_per_page'] = get_option( 'AdsDisp', $settings['max_ads_per_page'] );

					if ( false !== get_option( 'BegnAds' ) ) {
						$settings['enable_position_beginning_of_post'] = '1';
					}
					$settings['ad_beginning_of_post'] = get_option( 'BegnRnd', $settings['ad_beginning_of_post'] );
					if ( false !== get_option( 'MiddAds' ) ) {
						$settings['enable_position_middle_of_post'] = '1';
					}
					$settings['ad_middle_of_post'] = get_option( 'MiddRnd', $settings['ad_middle_of_post'] );
					if ( false !== get_option( 'EndiAds' ) ) {
						$settings['enable_position_end_of_post'] = '1';
					}
					$settings['ad_end_of_post'] = get_option( 'EndiRnd', $settings['ad_end_of_post'] );

					if ( false !== get_option( 'MoreAds' ) ) {
						$settings['enable_position_after_more_tag'] = '1';
					}
					$settings['ad_after_more_tag'] = get_option( 'MoreRnd', $settings['ad_after_more_tag'] );
					if ( false !== get_option( 'LapaAds' ) ) {
						$settings['enable_position_before_last_para'] = '1';
					}
					$settings['ad_before_last_para'] = get_option( 'LapaRnd', $settings['ad_before_last_para'] );

					for ( $i = 1; $i <= 3; $i++ ) {
						$settings[ 'enable_position_after_para_option_' . $i ]      = get_option( 'Par' . $i . 'Ads', $settings[ 'enable_position_after_para_option_' . $i ] );
						$settings[ 'ad_after_para_option_' . $i ]                   = get_option( 'Par' . $i . 'Rnd', $settings[ 'ad_after_para_option_' . $i ] );
						$settings[ 'position_after_para_option_' . $i ]             = get_option( 'Par' . $i . 'Nup', $settings[ 'position_after_para_option_' . $i ] );
						$settings[ 'enable_jump_position_after_para_option_' . $i ] = get_option( 'Par' . $i . 'Con', $settings[ 'enable_jump_position_after_para_option_' . $i ] );
					}

					for ( $i = 1; $i <= 1; $i++ ) {
						$settings[ 'enable_position_after_image_option_' . $i ]      = get_option( 'Img' . $i . 'Ads', $settings[ 'enable_position_after_image_option_' . $i ] );
						$settings[ 'ad_after_image_option_' . $i ]                   = get_option( 'Img' . $i . 'Rnd', $settings[ 'ad_after_image_option_' . $i ] );
						$settings[ 'position_after_image_option_' . $i ]             = get_option( 'Img' . $i . 'Nup', $settings[ 'position_after_image_option_' . $i ] );
						$settings[ 'enable_jump_position_after_image_option_' . $i ] = get_option( 'Img' . $i . 'Con', $settings[ 'enable_jump_position_after_image_option_' . $i ] );
					}

					if ( false !== get_option( 'AppPost' ) ) {
						$settings['enable_on_posts'] = '1';
					}
					if ( false !== get_option( 'AppPage' ) ) {
						$settings['enable_on_pages'] = '1';
					}

					if ( false !== get_option( 'AppHome' ) ) {
						$settings['enable_on_homepage'] = '1';
					}
					if ( false !== get_option( 'AppCate' ) ) {
						$settings['enable_on_categories'] = '1';
					}
					if ( false !== get_option( 'AppArch' ) ) {
						$settings['enable_on_archives'] = '1';
					}
					if ( false !== get_option( 'AppTags' ) ) {
						$settings['enable_on_tags'] = '1';
					}
					if ( false !== get_option( 'AppMaxA' ) ) {
						$settings['enable_all_possible_ads'] = '1';
					}

					if ( false !== get_option( 'AppSide' ) ) {
						$settings['disable_widgets_on_homepage'] = '1';
					}

					if ( false !== get_option( 'AppLogg' ) ) {
						$settings['disable_for_loggedin_users'] = '1';
					}

					if ( false !== get_option( 'QckTags' ) ) {
						$settings['enable_quicktag_buttons'] = '1';
					}
					if ( false !== get_option( 'QckRnds' ) ) {
						$settings['disable_randomads_quicktag_button'] = '1';
					}
					if ( false !== get_option( 'QckOffs' ) ) {
						$settings['disable_disablead_quicktag_buttons'] = '1';
					}
					if ( false !== get_option( 'QckOfPs' ) ) {
						$settings['disable_positionad_quicktag_buttons'] = '1';
					}

					for ( $i = 1; $i <= 10; $i++ ) {
						$settings[ 'onpost_ad_' . $i . '_content' ]   = get_option( 'AdsCode' . $i, $settings[ 'onpost_ad_' . $i . '_content' ] );
						$settings[ 'onpost_ad_' . $i . '_alignment' ] = get_option( 'AdsAlign' . $i, $settings[ 'onpost_ad_' . $i . '_alignment' ] );
						$settings[ 'onpost_ad_' . $i . '_margin' ]    = get_option( 'AdsMargin' . $i, $settings[ 'onpost_ad_' . $i . '_margin' ] );

						$settings[ 'widget_ad_' . $i . '_content' ] = get_option( 'WidCode' . $i, $settings[ 'widget_ad_' . $i . '_content' ] );
					}

					update_option( 'quick_adsense_settings', $settings );
					delete_option( 'AdsDisp' );
				} else {
					// New User.
					update_option( 'quick_adsense_settings', quick_adsense_get_defaults() );
				}
			}
		}
	}
);
