<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

if(defined('SITESEO_WL_ADMIN_HEADER') && SITESEO_WL_ADMIN_HEADER === false){
	//do nothing
	return;
}

//Notifications Center
function siteseo_advanced_appearance_notifications_option(){
	return siteseo_get_service('AdvancedOption')->getAppearanceNotification();
}
	
$class = '1' != siteseo_advanced_appearance_notifications_option() ? 'is-active' : ''; ?>

<div id="siteseo-notifications-center" class="siteseo-page-list <?php echo esc_attr($class); ?>">
<?php

do_action('siteseo_notifications_center_item');

function siteseo_advanced_appearance_universal_metabox_option(){
	return siteseo_get_service('AdvancedOption')->getAccessUniversalMetaboxGutenberg();
}

function siteseo_get_hidden_notices_usm_option(){
	return siteseo_get_service('NoticeOption')->getNoticeUSM();
}

if ('1' != siteseo_get_hidden_notices_usm_option() && siteseo_advanced_appearance_universal_metabox_option() !== '1') {
	$args = [
		'id'	 => 'notice-usm',
		'title'  => __('Enable our universal SEO metabox for the Block Editor', 'siteseo'),
		'desc'   => __('By default, our new SEO metabox is disabled for Gutenberg. Test it without further delay!', 'siteseo'),
		'impact' => [
			'info' => __('Wizard', 'siteseo'),
		],
		'link' => [
			'en'	   => admin_url('admin.php?page=siteseo-advanced#tab=tab_siteseo_advanced_appearance'),
			'title'	=> __('Activate it', 'siteseo'),
			'external' => false,
		],
		'icon'	   => 'dashicons-admin-tools',
		'deleteable' => true,
	];
	siteseo_notification($args);
}

function siteseo_get_hidden_notices_wizard_option(){
	return siteseo_get_service('NoticeOption')->getNoticeWizard();
}

if ('1' != siteseo_get_hidden_notices_wizard_option()) {
	$args = [
		'id'	 => 'notice-wizard',
		'title'  => __('Configure SiteSEO in a few minutes with our installation wizard', 'siteseo'),
		'desc'   => __('The best way to quickly setup SiteSEO on your site.', 'siteseo'),
		'impact' => [
			'info' => __('Wizard', 'siteseo'),
		],
		'link' => [
			'en'	   => admin_url('admin.php?page=siteseo-setup'),
			'title'	=> __('Start the wizard', 'siteseo'),
			'external' => true,
		],
		'icon'	   => 'dashicons-admin-tools',
		'deleteable' => true,
	];
	siteseo_notification($args);
}

//AMP
if (is_plugin_active('amp/amp.php')) {
	function siteseo_get_hidden_notices_amp_analytics_option()
	{
		return siteseo_get_service('NoticeOption')->getNoticeAMPAnalytics();
	}
	if ('1' != siteseo_get_hidden_notices_amp_analytics_option()) {
		$args = [
			'id'	 => 'notice-amp-analytics',
			'title'  => __('Use Google Analytics with AMP plugin', 'siteseo'),
			'desc'   => __('Your site is using the AMP official plugin. To track users with Google Analytics on AMP pages, please go to this settings page.', 'siteseo'),
			'impact' => [
				'info' => __('Medium impact', 'siteseo'),
			],
			'link' => [
				'en'	   => admin_url('admin.php?page=amp-options#analytics-options'),
				'title'	=> __('Fix this!', 'siteseo'),
				'external' => false,
			],
			'icon'	   => 'dashicons-chart-area',
			'deleteable' => true,
		];
		siteseo_notification($args);
	}
}

//DIVI SEO options conflict
$theme = wp_get_theme();
if ('Divi' == $theme->template || 'Divi' == $theme->parent_theme) {
	$divi_options = get_option('et_divi');
	if (! empty($divi_options)) {
		if (
			(array_key_exists('divi_seo_home_title', $divi_options) && 'on' == $divi_options['divi_seo_home_title']) ||
			(array_key_exists('divi_seo_home_description', $divi_options) && 'on' == $divi_options['divi_seo_home_description']) ||
			(array_key_exists('divi_seo_home_keywords', $divi_options) && 'on' == $divi_options['divi_seo_home_keywords']) ||
			(array_key_exists('divi_seo_home_canonical', $divi_options) && 'on' == $divi_options['divi_seo_home_canonical']) ||
			(array_key_exists('divi_seo_single_title', $divi_options) && 'on' == $divi_options['divi_seo_single_title']) ||
			(array_key_exists('divi_seo_single_description', $divi_options) && 'on' == $divi_options['divi_seo_single_description']) ||
			(array_key_exists('divi_seo_single_keywords', $divi_options) && 'on' == $divi_options['divi_seo_single_keywords']) ||
			(array_key_exists('divi_seo_single_canonical', $divi_options) && 'on' == $divi_options['divi_seo_single_canonical']) ||
			(array_key_exists('divi_seo_index_canonical', $divi_options) && 'on' == $divi_options['divi_seo_index_canonical']) ||
			(array_key_exists('divi_seo_index_description', $divi_options) && 'on' == $divi_options['divi_seo_index_description'])
		) {
			$args = [
				'id'	 => 'notice-divi-seo',
				'title'  => __('We noticed that some SEO DIVI options are enabled!', 'siteseo'),
				'desc'   => __('To avoid any SEO conflicts, please disable every SEO option from <strong>DIVI theme options page, SEO tab</strong>.', 'siteseo'),
				'impact' => [
					'high' => __('High impact', 'siteseo'),
				],
				'link' => [
					'en'	   => admin_url('admin.php?page=et_divi_options#seo-1'),
					'title'	=> __('Fix this!', 'siteseo'),
					'external' => false,
				],
				'icon'	   => 'dashicons-admin-plugins',
				'deleteable' => false,
			];
			siteseo_notification($args);
		}
	}
}

if (is_plugin_active('td-composer/td-composer.php')) {
	function siteseo_get_hidden_notices_tagdiv_option(){
		return siteseo_get_service('NoticeOption')->getNoticeTagDiv();
	}
	if ('1' != siteseo_get_hidden_notices_tagdiv_option()){
		$args = [
			'id'	 => 'notice-tagdiv',
			'title'  => __('TagDiv Composer plugin doesn\'t use <strong>add_theme_support(\'title-tag\');</strong>', 'siteseo'),
			'desc'   => __('Fix this compatibility issue to allow SiteSEO generates the correct meta titles.', 'siteseo'),
			'impact' => [
				'high' => __('High impact', 'siteseo'),
			],
			'link' => [
				'en'	   => SITESEO_DOCS.'fix-compatibility-issue-tagdiv-composer-plugin-newspaper-theme/?utm_source=plugin&utm_medium=wp-admin&utm_campaign=siteseo',
				'title'	=> __('Fix this!', 'siteseo'),
				'external' => true,
			],
			'icon'	   => 'dashicons-admin-customizer',
			'deleteable' => true,
		];
		siteseo_notification($args);
	}
}
if ('1' != get_theme_support('title-tag') && true !== wp_is_block_theme()){
	function siteseo_get_hidden_notices_title_tag_option(){
		return siteseo_get_service('NoticeOption')->getNoticeTitleTag();
	}
	if ('1' != siteseo_get_hidden_notices_title_tag_option()) {
		$args = [
			'id'	 => 'notice-title-tag',
			'title'  => __('Your theme doesn\'t use <strong>add_theme_support(\'title-tag\');</strong>', 'siteseo'),
			'desc'   => __('This error indicates that your theme uses a deprecated function to generate the title tag of your pages. SiteSEO will not be able to generate your custom title tags if this error is not fixed.', 'siteseo'),
			'impact' => [
				'high' => __('High impact', 'siteseo'),
			],
			'link' => [
				'en'	   => SITESEO_DOCS.'fixing-missing-add_theme_support-in-your-theme/?utm_source=plugin&utm_medium=wp-admin&utm_campaign=siteseo',
				'title'	=> __('Learn more', 'siteseo'),
				'external' => true,
			],
			'icon'	   => 'dashicons-admin-customizer',
			'deleteable' => false,
		];
		siteseo_notification($args);
	}
}

if (is_plugin_active('swift-performance-lite/performance.php')){
	function siteseo_get_swift_performance_sitemap_option()	{
		return siteseo_get_service('NoticeOption')->getNoticeCacheSitemap();
	}
	if (siteseo_get_swift_performance_sitemap_option() === "1") {
		function siteseo_get_hidden_notices_swift_option()
		{
			return siteseo_get_service('NoticeOption')->getNoticeSwift();
		}
		$args = [
				'id'	 => 'notice-swift',
				'title'  => __('Your XML sitemap is cached!', 'siteseo'),
				'desc'   => __('Swift Performance is caching your XML sitemap. You must disable this option to prevent any compatibility issue (Swift Performance > Settings > Caching, General tab).', 'siteseo'),
				'impact' => [
					'high' => __('High impact', 'siteseo'),
				],
				'link' => [
					'en'	   => admin_url('tools.php?page=swift-performance'),
					'title'	=> __('Fix this!', 'siteseo'),
					'external' => false,
				],
				'icon'	   => 'dashicons-media-spreadsheet',
				'deleteable' => false,
			];
		siteseo_notification($args);
	}
}

$seo_plugins = [
	'wordpress-seo/wp-seo.php'					   => 'Yoast SEO',
	'wordpress-seo-premium/wp-seo-premium.php'	   => 'Yoast SEO Premium',
	'all-in-one-seo-pack/all_in_one_seo_pack.php'	=> 'All In One SEO',
	'autodescription/autodescription.php'			=> 'The SEO Framework',
	'squirrly-seo/squirrly.php'					  => 'Squirrly SEO',
	'seo-by-rank-math/rank-math.php'				 => 'Rank Math',
	'seo-ultimate/seo-ultimate.php'				  => 'SEO Ultimate',
	'wp-meta-seo/wp-meta-seo.php'					=> 'WP Meta SEO',
	'premium-seo-pack/plugin.php'					=> 'Premium SEO Pack',
	'wpseo/wpseo.php'								=> 'wpSEO',
	'platinum-seo-pack/platinum-seo-pack.php'		=> 'Platinum SEO Pack',
	'smartcrawl-seo/wpmu-dev-seo.php'				=> 'SmartCrawl',
	'seo-pressor/seo-pressor.php'					=> 'SeoPressor',
	'slim-seo/slim-seo.php'						  => 'Slim SEO'
];

foreach ($seo_plugins as $key => $value) {
	if (is_plugin_active($key)) {
		$args = [
			'id' => 'notice-seo-plugins',
			/* translators: %s name of a SEO plugin (eg: Yoast SEO) */
			'title'  => sprintf(__('We noticed that you use <strong>%s</strong> plugin.', 'siteseo'), $value),
			'desc'   => __('Do you want to migrate all your metadata to SiteSEO? Do not use multiple SEO plugins at once to avoid conflicts!', 'siteseo'),
			'impact' => [
				'high' => __('High impact', 'siteseo'),
			],
			'link' => [
				'en'	   => admin_url('admin.php?page=siteseo-import-export'),
				'title'	=> __('Migrate!', 'siteseo'),
				'external' => false,
			],
			'icon'	   => 'dashicons-admin-plugins',
			'deleteable' => false,
		];
		siteseo_notification($args);
	}
}
$indexing_plugins = [
	'indexnow/indexnow-url-submission.php'					   => 'IndexNow',
	'bing-webmaster-tools/bing-url-submission.php'			   => 'Bing Webmaster Url Submission',
	'fast-indexing-api/instant-indexing.php'					 => 'Instant Indexing',
];

foreach ($indexing_plugins as $key => $value) {
	if (is_plugin_active($key)) {
		$args = [
			'id' => 'notice-indexing-plugins',
			/* translators: %s name of a WP plugin (eg: IndexNow) */
			'title'  => sprintf(__('We noticed that you use <strong>%s</strong> plugin.', 'siteseo'), $value),
			'desc'   => __('To prevent any conflicts with our Indexing feature, please disable it.', 'siteseo'),
			'impact' => [
				'high' => __('High impact', 'siteseo'),
			],
			'link' => [
				'en'	   => admin_url('plugins.php'),
				'title'	=> __('Fix this!', 'siteseo'),
				'external' => false,
			],
			'icon'	   => 'dashicons-admin-plugins',
			'deleteable' => false,
		];
		siteseo_notification($args);
	}
}

//Enfold theme
$avia_options_enfold	   = get_option('avia_options_enfold');
$avia_options_enfold_child = get_option('avia_options_enfold_child');
$theme					 = wp_get_theme();
if ('enfold' == $theme->template || 'enfold' == $theme->parent_theme) {
	if ('plugin' != $avia_options_enfold['avia']['seo_robots'] || 'plugin' != $avia_options_enfold_child['avia']['seo_robots']) {
		function siteseo_get_hidden_notices_enfold_option()
		{
			return siteseo_get_service('NoticeOption')->getNoticeEnfold();
		}
		if ('1' != siteseo_get_hidden_notices_enfold_option()) {
			$args = [
				'id'	 => 'notice-enfold',
				'title'  => __('Enfold theme is not correctly setup for SEO!', 'siteseo'),
				'desc'   => __('You must disable "Meta tag robots" option from Enfold settings (SEO Support tab) to avoid any SEO issues.', 'siteseo'),
				'impact' => [
					'low' => __('High impact', 'siteseo'),
				],
				'link' => [
					'en'	   => admin_url('admin.php?avia_welcome=true&page=avia'),
					'title'	=> __('Fix this!', 'siteseo'),
					'external' => true,
				],
				'icon'	   => 'dashicons-admin-tools',
				'deleteable' => true,
			];
			siteseo_notification($args);
		}
	}
}
if(siteseo_get_empty_templates('cpt', 'title')){
	$args = [
		'id'	 => 'notice-cpt-empty-title',
		'title'  => __('Global meta title missing for several custom post types!', 'siteseo'),
		'desc'   => siteseo_get_empty_templates('cpt', 'title', false),
		'impact' => [
			'high' => __('High impact', 'siteseo'),
		],
		'link' => [
			'en'	   => admin_url('admin.php?page=siteseo-titles#tab=tab_siteseo_titles_single'),
			'title'	=> __('Fix this!', 'siteseo'),
			'external' => false,
		],
		'icon'	   => 'dashicons-editor-table',
		'deleteable' => false,
		'wrap'	   => false,
	];
	siteseo_notification($args);
}

if (siteseo_get_empty_templates('cpt', 'description')){
	$args = [
		'id'	 => 'notice-cpt-empty-desc',
		'title'  => __('Global meta description missing for several custom post types!', 'siteseo'),
		'desc'   => siteseo_get_empty_templates('cpt', 'description', false),
		'impact' => [
			'high' => __('High impact', 'siteseo'),
		],
		'link' => [
			'en'	   => admin_url('admin.php?page=siteseo-titles#tab=tab_siteseo_titles_single'),
			'title'	=> __('Fix this!', 'siteseo'),
			'external' => false,
		],
		'icon'	   => 'dashicons-editor-table',
		'deleteable' => false,
		'wrap'	   => false,
	];
	siteseo_notification($args);
}

if (siteseo_get_empty_templates('tax', 'title')){
	$args = [
		'id'	 => 'notice-tax-empty-title',
		'title'  => __('Global meta title missing for several taxonomies!', 'siteseo'),
		'desc'   => siteseo_get_empty_templates('tax', 'title', false),
		'impact' => [
			'high' => __('High impact', 'siteseo'),
		],
		'link' => [
			'en'	   => admin_url('admin.php?page=siteseo-titles#tab=tab_siteseo_titles_tax'),
			'title'	=> __('Fix this!', 'siteseo'),
			'external' => false,
		],
		'icon'	   => 'dashicons-editor-table',
		'deleteable' => false,
		'wrap'	   => false,
	];
	siteseo_notification($args);
}

if (siteseo_get_empty_templates('tax', 'description')) {
	$args = [
		'id'	 => 'notice-tax-empty-templates',
		'title'  => __('Global meta description missing for several taxonomies!', 'siteseo'),
		'desc'   => siteseo_get_empty_templates('tax', 'description', false),
		'impact' => [
			'high' => __('High impact', 'siteseo'),
		],
		'link' => [
			'en'	   => admin_url('admin.php?page=siteseo-titles#tab=tab_siteseo_titles_tax'),
			'title'	=> __('Fix this!', 'siteseo'),
			'external' => false,
		],
		'icon'	   => 'dashicons-editor-table',
		'deleteable' => false,
		'wrap'	   => false,
	];
	siteseo_notification($args);
}

if (! is_ssl()) {
	function siteseo_get_hidden_notices_ssl_option()
	{
		return siteseo_get_service('NoticeOption')->getNoticeSSL();
	}
	if ('1' != siteseo_get_hidden_notices_ssl_option()) {
		$args = [
			'id'	 => 'notice-ssl',
			'title'  => __('Your site doesn\'t use an SSL certificate!', 'siteseo'),
			'desc'   => __('Https is considered by Google as a positive signal for the ranking of your site. It also reassures your visitors for data security, and improves trust.', 'siteseo') . '</a>',
			'impact' => [
				'low' => __('Low impact', 'siteseo'),
			],
			'link' => [
				'en'	   => 'https://webmasters.googleblog.com/2014/08/https-as-ranking-signal.html',
				'title'	=> __('Learn more', 'siteseo'),
				'external' => true,
			],
			'icon'	   => 'dashicons-unlock',
			'deleteable' => true,
		];
		siteseo_notification($args);
	}
}

if (function_exists('extension_loaded') && ! extension_loaded('dom')) {
	$args = [
		'id'	 => 'notice-dom',
		'title'  => __('PHP module "DOM" is missing on your server.', 'siteseo'),
		'desc'   => __('This PHP module, installed by default with PHP, is required by many plugins including SiteSEO. Please contact your host as soon as possible to solve this.', 'siteseo'),
		'impact' => [
			'high' => __('High impact', 'siteseo'),
		],
		'link' => [
			'en'	   => SITESEO_DOCS.'get-started-siteseo/',
			'title'	=> __('Learn more', 'siteseo'),
			'external' => true,
		],
		'deleteable' => false,
	];
	siteseo_notification($args);
}

if (function_exists('extension_loaded') && ! extension_loaded('mbstring')) {
	$args = [
		'id'	 => 'notice-mbstring',
		'title'  => __('PHP module "mbstring" is missing on your server.', 'siteseo'),
		'desc'   => __('This PHP module, installed by default with PHP, is required by many plugins including SiteSEO. Please contact your host as soon as possible to solve this.', 'siteseo'),
		'impact' => [
			'high' => __('High impact', 'siteseo'),
		],
		'link' => [
			'en'	   => SITESEO_DOCS.'get-started-siteseo/',
			'title'	=> __('Learn more', 'siteseo'),
			'external' => true,
		],
		'deleteable' => false,
	];
	siteseo_notification($args);
}

function siteseo_get_hidden_notices_noindex_option(){
	return siteseo_get_service('NoticeOption')->getNoticeNoIndex();
}

if ('1' != siteseo_get_hidden_notices_noindex_option()) {
	if ('1' == siteseo_get_service('TitleOption')->getTitleNoIndex() || '1' != get_option('blog_public')) {
		$args = [
			'id'	 => 'notice-noindex',
			'title'  => __('Your site is not visible to Search Engines!', 'siteseo'),
			'desc'   => __('You have activated the blocking of the indexing of your site. If your site is under development, this is probably normal. Otherwise, check your settings. Delete this notification using the cross on the right if you are not concerned.', 'siteseo'),
			'impact' => [
				'high' => __('High impact', 'siteseo'),
			],
			'link' => [
				'en'	   => admin_url('options-reading.php'),
				'title'	=> __('Fix this!', 'siteseo'),
				'external' => false,
			],
			'icon'	   => 'dashicons-warning',
			'deleteable' => true,
		];
		siteseo_notification($args);
	}
}

if ('' == get_option('blogname')) {
	$args = [
		'id'	 => 'notice-title-empty',
		'title'  => __('Your site title is empty!', 'siteseo'),
		'desc'   => __('Your Site Title is used by WordPress, your theme and your plugins including SiteSEO. It is an essential component in the generation of title tags, but not only. Enter one!', 'siteseo'),
		'impact' => [
			'high' => __('High impact', 'siteseo'),
		],
		'link' => [
			'en'	   => admin_url('options-general.php'),
			'title'	=> __('Fix this!', 'siteseo'),
			'external' => false,
		],
		'deleteable' => false,
	];
	siteseo_notification($args);
}

if ('' == get_option('permalink_structure')) {
	$args = [
		'id'	 => 'notice-permalinks',
		'title'  => __('Your permalinks are not SEO Friendly! Enable pretty permalinks to fix this.', 'siteseo'),
		'desc'   => __('Why is this important? Showing only the summary of each article significantly reduces the theft of your content by third party sites. Not to mention, the increase in your traffic, your advertising revenue, conversions...', 'siteseo'),
		'impact' => [
			'high' => __('High impact', 'siteseo'),
		],
		'link' => [
			'en'	   => admin_url('options-permalink.php'),
			'title'	=> __('Fix this!', 'siteseo'),
			'external' => false,
		],
		'icon'	   => 'dashicons-admin-links',
		'deleteable' => false,
	];
	siteseo_notification($args);
}

if ('0' == get_option('rss_use_excerpt')) {
	function siteseo_get_hidden_notices_rss_use_excerpt_option()
	{
		return siteseo_get_service('NoticeOption')->getNoticeRSSUseExcerpt();
	}
	if ('1' != siteseo_get_hidden_notices_rss_use_excerpt_option()) {
		$args = [
			'id'	 => 'notice-rss-use-excerpt',
			'title'  => __('Your RSS feed shows full text!', 'siteseo'),
			'desc'   => __('Why is this important? Showing only the summary of each article significantly reduces the theft of your content by third party sites. Not to mention, the increase in your traffic, your advertising revenue, conversions...', 'siteseo'),
			'impact' => [
				'medium' => __('Medium impact', 'siteseo'),
			],
			'link' => [
				'en'	   => admin_url('options-reading.php'),
				'title'	=> __('Fix this!', 'siteseo'),
				'external' => false,
			],
			'icon'	   => 'dashicons-rss',
			'deleteable' => true,
		];
		siteseo_notification($args);
	}
}

function siteseo_ga_enable_option(){
	return siteseo_get_service('GoogleAnalyticsOption')->getEnableOption();
}

function siteseo_ga4_option(){
	return siteseo_get_service('GoogleAnalyticsOption')->getGA4();
}

if('' === siteseo_ga4_option() && '1' === siteseo_ga_enable_option()){
	function siteseo_get_hidden_notices_analytics_option(){
		return siteseo_get_service('NoticeOption')->getNoticeGAIds();
	}
	
	if ('1' != siteseo_get_hidden_notices_analytics_option()){
		$args = [
			'id'	 => 'notice-ga-ids',
			'title'  => __('You have activated Google Analytics tracking without adding identifiers!', 'siteseo'),
			'desc'   => __('Google Analytics will not track your visitors until you finish the configuration.', 'siteseo'),
			'impact' => [
				'medium' => __('Medium impact', 'siteseo'),
			],
			'link' => [
				'en'	   => admin_url('admin.php?page=siteseo-google-analytics'),
				'title'	=> __('Fix this!', 'siteseo'),
				'external' => false,
			],
			'icon'	   => 'dashicons-chart-area',
			'deleteable' => true,
		];
		siteseo_notification($args);
	}
}

if ('1' == get_option('page_comments')) {
	function siteseo_get_hidden_notices_divide_comments_option()
	{
		return siteseo_get_service('NoticeOption')->getNoticeDivideComments();
	}
	if ('1' != siteseo_get_hidden_notices_divide_comments_option()) {
		$args = [
			'id'	 => 'notice-divide-comments',
			'title'  => __('Break comments into pages is ON!', 'siteseo'),
			'desc'   => __('Enabling this option will create duplicate content for each article beyond x comments. This can have a disastrous effect by creating a large number of poor quality pages, and slowing the Google bot unnecessarily, so your ranking in search results.', 'siteseo'),
			'impact' => [
				'high' => __('High impact', 'siteseo'),
			],
			'link' => [
				'en'	   => admin_url('options-discussion.php'),
				'title'	=> __('Disable this!', 'siteseo'),
				'external' => false,
			],
			'icon'	   => 'dashicons-admin-comments',
			'deleteable' => true,
		];
		siteseo_notification($args);
	}
}

if (get_option('posts_per_page') < '16') {
	function siteseo_get_hidden_notices_posts_number_option()
	{
		return siteseo_get_service('NoticeOption')->getNoticePostsNumber();
	}
	if ('1' != siteseo_get_hidden_notices_posts_number_option()) {
		$args = [
			'id'	 => 'notice-posts-number',
			'title'  => __('Display more posts per page on homepage and archives', 'siteseo'),
			'desc'   => __('To reduce the number pages search engines have to crawl to find all your articles, it is recommended displaying more posts per page. This should not be a problem for your users. Using mobile, we prefer to scroll down rather than clicking on next page links.', 'siteseo'),
			'impact' => [
				'medium' => __('Medium impact', 'siteseo'),
			],
			'link' => [
				'en'	   => admin_url('options-reading.php'),
				'title'	=> __('Fix this!', 'siteseo'),
				'external' => false,
			],
			'deleteable' => true,
		];
		siteseo_notification($args);
	}
}

if ('1' != siteseo_get_service('SitemapOption')->isEnabled()) {
	$args = [
		'id'	 => 'notice-xml-sitemaps',
		'title'  => __('You don\'t have an XML Sitemap!', 'siteseo'),
		'desc'   => __('XML Sitemaps are useful to facilitate the crawling of your content by search engine robots. Indirectly, this can benefit your ranking by reducing the crawl bugdet.', 'siteseo'),
		'impact' => [
			'medium' => __('Medium impact', 'siteseo'),
		],
		'link' => [
			'en'	   => admin_url('admin.php?page=siteseo-xml-sitemap'),
			'title'	=> __('Fix this!', 'siteseo'),
			'external' => false,
		],
		'icon'	   => 'dashicons-warning',
		'deleteable' => false,
	];
	siteseo_notification($args);
}

function siteseo_get_hidden_notices_google_business_option(){
	return siteseo_get_service('NoticeOption')->getNoticeGoogleBusiness();
}

if ('1' != siteseo_get_hidden_notices_google_business_option()){
	$args = [
		'id'	 => 'notice-google-business',
		'title'  => __('Do you have a Google My Business page? It\'s free!', 'siteseo'),
		'desc'   => __('Local Business websites should have a My Business page to improve visibility in search results. Click on the cross on the right to delete this notification if you are not concerned.', 'siteseo'),
		'impact' => [
			'high' => __('High impact', 'siteseo'),
		],
		'link' => [
			'en'	   => 'https://www.google.com/business/go/',
			'title'	=> __('Create your page now!', 'siteseo'),
			'external' => true,
		],
		'deleteable' => true,
	];
	siteseo_notification($args);
}

function siteseo_get_hidden_notices_search_console_option(){
	return siteseo_get_service('NoticeOption')->getNoticeSearchConsole();
}

function siteseo_get_google_site_verification_option(){
	return siteseo_get_service('AdvancedOption')->getAdvancedGoogleVerification();
}

if ('1' != siteseo_get_hidden_notices_search_console_option() && '' == siteseo_get_google_site_verification_option()) {
	$args = [
		'id'	 => 'notice-search-console',
		'title'  => __('Add your site to Google. It\'s free!', 'siteseo'),
		'desc'   => __('Is your brand new site online? So reference it as quickly as possible on Google to get your first visitors via Google Search Console. Already the case? Click on the cross on the right to remove this alert.', 'siteseo'),
		'impact' => [
			'high' => __('High impact', 'siteseo'),
		],
		'link' => [
			'en'	   => 'https://www.google.com/webmasters/tools/home',
			'title'	=> __('Add your site to Search Console!', 'siteseo'),
			'external' => true,
		],
		'deleteable' => true,
	];
	siteseo_notification($args);
}

if (! is_plugin_active('siteseo-pro/siteseo-pro.php')) {
	
	function siteseo_get_hidden_notices_go_pro_option(){
		return siteseo_get_service('NoticeOption')->getNoticeGoPro();
	}
	
	if ('1' != siteseo_get_hidden_notices_go_pro_option() && '' == siteseo_get_hidden_notices_go_pro_option()) {
		$args = [
			'id'	 => 'notice-go-pro',
			'title'  => __('Take your SEO to the next level with SiteSEO PRO!', 'siteseo'),
			'desc'   => __('The PRO version of SiteSEO allows you to easily manage your structured data (schemas), add a breadcrumb optimized for SEO and accessibility, improve SEO for WooCommerce, gain productivity with our import / export tool from a CSV of your metadata and so much more.', 'siteseo'),
			'impact' => [
				'info' => __('PRO', 'siteseo'),
			],
			'link' => [
				'en'	   => SITESEO_WEBSITE.'?utm_source=plugin&utm_medium=notification&utm_campaign=dashboard',
				'title'	=> __('Upgrade now!', 'siteseo'),
				'external' => true,
			],
			'deleteable' => true,
		];
		siteseo_notification($args);
	}
}
?>
	</div>
	<!--#siteseo-notifications-center-->
<?php
