<?php

if (! defined('ABSPATH')) {
	exit;
}

function siteseo_get_docs_links(){
	$docs  = [];
	$utm   = '?utm_source=plugin&utm_medium=wp-admin-help-tab&utm_campaign=siteseo';
	$utm2  = '?utm_source=plugin&utm_medium=wizard&utm_campaign=siteseo';

	$docs = [
		'website'		  => SITESEO_WEBSITE . $utm,
		'subscribe'		=> SITESEO_WEBSITE.'subscribe/' . $utm,
		'blog'			 => SITESEO_WEBSITE.'blog/' . $utm,
		'downloads'		=> 'https://softaculous.com/clients?ca=siteseo',
		'support'		  => SITESEO_SUPPORT,
		'guides'		   => SITESEO_DOCS,
		'faq'			  => SITESEO_DOCS.'faq/',
		'get_started'	  => [
			'installation'		=> [__('Installation of SiteSEO', 'siteseo') => SITESEO_DOCS.'getting-started/' . $utm],
			'license'			 => [__('Activate your license key to receive automatic updates', 'siteseo') => SITESEO_DOCS.'getting-started/how-to-install-siteseo-pro/' . $utm . '#linking-license-with-the-plugin'],
			//'wizard'			  => [__('Configure SiteSEO in 5 minutes', 'siteseo') => 'https://www.youtube.com/@SiteSEOPlugin' . $utm],
			//'migration'		   => [__('Migrate your SEO metadata from other plugins', 'siteseo') => SITESEO_WEBSITE.'migrate-from/' . $utm],
			'sitemaps'			=> [__('Promote the exploration of your WordPress site by search engine robots', 'siteseo') => SITESEO_DOCS.'sitemap/generate-xml-sitemaps/' . $utm],
			//'content'			 => [__('Optimize content from A to Z with SiteSEO', 'siteseo') => SITESEO_DOCS.'tutorials/optimize-wordpress-posts-for-a-keyword/' . $utm],
			'analytics'		   => [__('Connect Google Analytics with your Website', 'siteseo') => SITESEO_DOCS.'analytics/connect-with-google-analytics/' . $utm],
			'search_console'	  => [__('Add your WordPress site to Google’s index', 'siteseo') => SITESEO_DOCS.'miscellaneous/add-your-site-to-google-search-console/' . $utm],
			'social'			  => [__('Optimize your click-through rate on social networks', 'siteseo') => SITESEO_DOCS.'manage-facebook-open-graph-and-twitter-cards-metas/' . $utm],
			'noindex'			  => [__('Prevent search engine to index search results', 'siteseo') => SITESEO_DOCS.'meta/prevent-search-engines-to-index-search-results/' . $utm],
		],
		'universal' => [
			'introduction' => SITESEO_WEBSITE.'features/page-builders/' . $utm,
		],
		'titles' => [
			'thumbnail' => 'https://support.google.com/programmable-search/answer/1626955?hl=en',
			'wrong_meta' => SITESEO_DOCS.'google-uses-the-wrong-meta-title-meta-description-in-search-results/' . $utm,
			'alt_title' => 'https://developers.google.com/search/docs/appearance/site-names#content-guidelines' . $utm,
		],
		'sitemaps' => [
			'error' => [
				'blank' => SITESEO_DOCS.'sitemap/xml-sitemap-shows-blank-page/' . $utm,
				'404'   => SITESEO_DOCS.'sitemap/xml-sitemap-returns-a-404-error/' . $utm,
				'html'  => SITESEO_DOCS.'sitemap/exclude-xml-and-xsl-files-from-caching-plugins/' . $utm,
			],
			'html'  => SITESEO_DOCS.'sitemap/enable-html-sitemap/' . $utm,
			'xml'   => SITESEO_DOCS.'sitemap/generate-xml-sitemaps/' . $utm,
			'image' => SITESEO_DOCS.'sitemap/enable-xml-image-sitemaps/' . $utm,
			'video' => SITESEO_DOCS.'sitemap/enable-video-xml-sitemap/' . $utm,
		],
		'social' => [
			'og' => SITESEO_DOCS.'manage-facebook-open-graph-and-twitter-cards-metas/' . $utm,
		],
		'analytics' => [
			'connect' => SITESEO_DOCS.'analytics/connect-with-google-analytics/' . $utm,
			//'custom_dimensions' => SITESEO_DOCS.'create-custom-dimension-google-analytics/' . $utm,
			'custom_tracking' => SITESEO_DOCS.'hooks/add-custom-tracking-code-with-user-consent/' . $utm,
			'consent_msg' => SITESEO_DOCS.'hooks/filter-user-consent-message/' . $utm,
			'gads' => SITESEO_DOCS.'analytics/how-to-find-your-google-ads-conversion-id/' . $utm,
			'gtm' => SITESEO_DOCS.'analytics/how-to-add-google-tag-manager/' . $utm,
			'ecommerce' => SITESEO_DOCS.'how-to-setup-google-enhanced-ecommerce/' . $utm,
			'events' => SITESEO_DOCS.'analytics/how-to-track-affiliates-file-download-external-and-outbound-link-using-google-analytics/' . $utm,
			'ga4_property' => 'https://support.google.com/analytics/answer/9539598?hl=en',
			'api' => [
				'analytics' => 'https://console.cloud.google.com/apis/library/analytics.googleapis.com?hl=en',
				'reporting' => 'https://console.cloud.google.com/apis/library/analyticsreporting.googleapis.com?hl=en',
				'data' => 'https://console.cloud.google.com/apis/library/analyticsdata.googleapis.com?hl=en'
			],
			'matomo' => [
				'on_premise' => SITESEO_DOCS.'analytics/how-to-use-matomo-on-premise-with-siteseo' . $utm,
				'token' => SITESEO_DOCS.'analytics/how-to-connect-matomo-analytics-with-your-wordpress-site/' . $utm,
			],
			'clarity' => [
				'project' => SITESEO_DOCS.'analytics/find-my-microsoft-clarity-project-id/' . $utm,
			]
		],
		'compatibility' => [
			'automatic' => SITESEO_DOCS.'generate-automatic-meta-description-from-page-builders/' . $utm,
		],
		'security' => [
			'metaboxe_seo' => SITESEO_DOCS.'hooks/filter-seo-metaboxe-call-by-post-type/' . $utm,
			'metaboxe_ca' => SITESEO_DOCS.'hooks/filter-content-analysis-metabox-call-by-post-type/' . $utm,
			'metaboxe_data_types' => SITESEO_DOCS.'hooks/filter-structured-data-types-metabox-call-by-post-type/' . $utm,
			'ga_widget' => SITESEO_DOCS.'hooks/filter-google-analytics-dashboard-widget-capability/' . $utm,
			'matomo_widget' => SITESEO_DOCS.'hooks/filter-matomo-analytics-dashboard-widget-capability/' . $utm
		],
		'google_preview' => [
			'authentification' => SITESEO_DOCS.'hooks/filter-google-snippet-preview-remote-request/' . $utm,
		],
		'bot' => SITESEO_DOCS.'detect-broken-links/' . $utm,
		'lb'  => [
			'eat' => SITESEO_DOCS.'optimizing-wordpress-sites-for-google-eat/' . $utm,
		],
		'robots' => [
			'file' => SITESEO_DOCS.'robots-txt-and-htaccess/edit-robots-txt-file/' . $utm,
		],
		'breadcrumbs' => [
			'sep' => SITESEO_DOCS.'hooks/filter-breadcrumbs-separator/' . $utm,
			'i18n' => SITESEO_DOCS.'translate-siteseo-options-with-wpml-polylang/' . $utm,
		],
		'redirects'   => [
			'enable' => SITESEO_DOCS.'redirections/' . $utm,
			'query'  => SITESEO_DOCS.'delete-your-404-errors-with-a-mysql-query/' . $utm,
			'regex'  => SITESEO_DOCS.'redirections/' . $utm . '#regular-expressions',
		],
		'schemas' => [
			'add'	 => SITESEO_DOCS.'tutorials/how-to-add-schema-to-wordpress-with-siteseo-1/' . $utm,
			'faq_acf' => SITESEO_DOCS.'create-an-automatic-faq-schema-with-acf-repeater-fields/' . $utm,
			'dynamic' => SITESEO_DOCS.'manage-titles-meta-descriptions/' . $utm,
			'variables' => SITESEO_DOCS.'hooks/filter-predefined-dynamic-variables-for-automatic-schemas/' . $utm,
			'custom_fields' => SITESEO_DOCS.'hooks/filter-custom-fields-list-in-schemas/' . $utm,
		],
		'page_speed' => [
			'cwv' => SITESEO_DOCS.'core-web-vitals-and-wordpress-seo/' . $utm,
			'api' => SITESEO_DOCS.'api-cli-dev/add-your-google-page-speed-insights-api-key-to-siteseo/' . $utm,
			'google' => 'https://console.cloud.google.com/apis/library/pagespeedonline.googleapis.com?hl=en',
		],
		'indexing_api' => [
			'google' => SITESEO_DOCS.'api-cli-dev/use-google-instant-indexing-api-with-siteseo-pro/' . $utm,
			'api' => 'https://console.cloud.google.com/apis/library/indexing.googleapis.com?hl=en',
		],
		'inspect_url' => [
			'google' => SITESEO_DOCS.'api-cli-dev/how-to-use-google-search-console-api-with-siteseo-pro/' . $utm,
		],
		'search_console_api' => [
			'google' => SITESEO_DOCS.'google-search-console-with-siteseo/' . $utm,
			'api' => 'https://console.cloud.google.com/apis/library/searchconsole.googleapis.com?hl=en',
		],
		'tools' => [
			'csv_import' => SITESEO_DOCS.'import-metadata-from-a-csv-file-with-siteseo-pro/' . $utm,
			'csv_export' => SITESEO_DOCS.'export-metadata-from-siteseo-to-a-csv-file/' . $utm,
		],
		'license' => [
			'account'		=> 'https://softaculous.com/clients?ca=siteseo' . $utm,
			'license_errors' => SITESEO_DOCS.'getting-started/how-to-install-siteseo-pro/' . $utm . '#linking-license-with-the-plugin',
			'license_define' => SITESEO_DOCS.'getting-started/how-to-install-siteseo-pro/' . $utm . '#add-my-license-key-to-wp-config-php',
		],
		'addons' => [
			'pro' => SITESEO_WEBSITE.'/pricing/' . $utm,
		],
	];

	$docs['external'] = [
		'facebook'	  => 'https://www.facebook.com/siteseo/' . $utm,
		'facebook_gr'   => 'https://www.facebook.com/groups/siteseo/' . $utm,
		'youtube'	   => 'https://www.youtube.com/@SiteSEOPlugin' . $utm,
		'twitter'	   => 'https://twitter.com/siteseo' . $utm,
	];

	return $docs;
}
