<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

function siteseo_sanitize_options_fields($input){

	$siteseo_sanitize_fields = [
		'siteseo_social_facebook_img_attachment_id',
		'siteseo_social_facebook_img_attachment_width',
		'siteseo_social_facebook_img_attachment_height',
		'titles_home_site_title',
		'titles_home_site_title_alt',
		'titles_home_site_desc',
		'titles_archives_author_title',
		'siteseo_titles_archives_author_desc',
		'titles_archives_date_title',
		'titles_archives_date_desc',
		'titles_archives_search_title',
		'titles_archives_search_desc',
		'titles_archives_404_title',
		'titles_archives_404_desc',
		'xml_sitemap_html_exclude',
		'social_knowledge_name',
		'social_knowledge_img',
		'social_knowledge_phone',
		'social_accounts_facebook',
		'social_accounts_twitter',
		'social_accounts_pinterest',
		'social_accounts_instagram',
		'social_accounts_youtube',
		'social_accounts_linkedin',
		'social_facebook_link_ownership_id',
		'social_facebook_admin_id',
		'social_facebook_app_id',
		'google_analytics_ga4',
		'google_analytics_download_tracking',
		'google_analytics_opt_out_msg',
		'google_analytics_opt_out_msg_ok',
		'google_analytics_opt_out_msg_close',
		'google_analytics_opt_out_msg_edit',
		'google_analytics_other_tracking',
		'google_analytics_other_tracking_body',
		'google_analytics_optimize',
		'google_analytics_ads',
		'google_analytics_cross_domain',
		'google_analytics_matomo_id',
		'google_analytics_matomo_site_id',
		'google_analytics_matomo_cross_domain_sites',
		'google_analytics_cb_backdrop_bg',
		'google_analytics_cb_exp_date',
		'google_analytics_cb_bg',
		'google_analytics_cb_txt_col',
		'google_analytics_cb_lk_col',
		'google_analytics_cb_btn_bg',
		'google_analytics_cb_btn_col',
		'google_analytics_cb_btn_bg_hov',
		'google_analytics_cb_btn_col_hov',
		'google_analytics_cb_btn_sec_bg',
		'google_analytics_cb_btn_sec_col',
		'google_analytics_cb_btn_sec_bg_hov',
		'google_analytics_cb_btn_sec_col_hov',
		'google_analytics_cb_width',
		'instant_indexing_bing_api_key',
		'instant_indexing_manual_batch',
		'google_analytics_clarity_project_id',
		'google_analytics_matomo_widget_auth_token',
		//'instant_indexing_google_api_key',
	];

	$siteseo_esc_attr = [
		'titles_sep',
	];

	$siteseo_sanitize_site_verification = [
		'advanced_google',
		'advanced_bing',
		'advanced_pinterest',
		'advanced_yandex',
	];

	$newOptions = ['siteseo_social_facebook_img_attachment_id', 'siteseo_social_facebook_img_height', 'siteseo_social_facebook_img_width'];

	foreach ($newOptions as $key => $value) {
		if(!isset($input[$value]) && isset($_POST[$value])){
			$input[$value] = $_POST[$value];
		}
	}

	foreach ($siteseo_sanitize_fields as $value) {
		if ( ! empty($input['google_analytics_matomo_widget_auth_token']) && 'google_analytics_matomo_widget_auth_token' == $value) {
			$options = get_option('siteseo_google_analytics_option_name');

			$token = isset($options['google_analytics_matomo_widget_auth_token']) ? $options['google_analytics_matomo_widget_auth_token'] : null;

			$input[$value] = $input[$value] ==='xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' ? $token : sanitize_text_field($input[$value]);
		} elseif ( ! empty($input['google_analytics_opt_out_msg']) && 'google_analytics_opt_out_msg' == $value) {
			$args = [
					'strong' => true,
					'em'	 => true,
					'br'	 => true,
					'a'	  => [
						'href'   => true,
						'target' => true,
					],
			];
			$input[$value] = wp_kses($input[$value], $args);
		} elseif (( ! empty($input['google_analytics_other_tracking']) && 'google_analytics_other_tracking' == $value) || ( ! empty($input['google_analytics_other_tracking_body']) && 'google_analytics_other_tracking_body' == $value) || ( ! empty($input['google_analytics_other_tracking_footer']) && 'google_analytics_other_tracking_footer' == $value)) {
			$input[$value] = $input[$value]; //No sanitization for this field
		} elseif ( ! empty($input['instant_indexing_manual_batch']) && 'instant_indexing_manual_batch' == $value) {
			$input[$value] = sanitize_textarea_field($input[$value]);
		} elseif ( ! empty($input[$value])) {
			$input[$value] = sanitize_text_field($input[$value]);
		}
	}

	foreach ($siteseo_esc_attr as $value) {
		if ( ! empty($input[$value])) {
			$input[$value] = esc_attr($input[$value]);
		}
	}

	foreach ($siteseo_sanitize_site_verification as $value) {
		if ( ! empty($input[$value])) {
			if (preg_match('#content=\'([^"]+)\'#', $input[$value], $m)) {
				$input[$value] = esc_attr($m[1]);
			} elseif (preg_match('#content="([^"]+)"#', $input[$value], $m)) {
				$input[$value] = esc_attr($m[1]);
			} else {
				$input[$value] = esc_attr($input[$value]);
			}
		}
	}

	return $input;

}

