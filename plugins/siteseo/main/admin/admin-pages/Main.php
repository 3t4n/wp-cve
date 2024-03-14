<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

// Set class property
$this->options = get_option('siteseo_option_name');
$current_tab   ='';
if (function_exists('siteseo_admin_header')) {
	siteseo_admin_header();
}

function siteseo_get_hidden_notices_get_started_option(){
	$options = get_option('siteseo_notices');
	if(!empty($options) && isset($options['notice-get-started'])) {
		return $options['notice-get-started'];
	}
}

function siteseo_dashboard_cards_order_option(){
	$options = get_option('siteseo_dashboard_option_name');
	if(! empty($options) && isset($options['cards_order'])){
		return $options['cards_order'];
	}
}
?>

<div id="siteseo-content" class="siteseo-option siteseo-main-page">
	<!--Get started-->
	<?php
if ('1' != siteseo_get_hidden_notices_get_started_option()) {
	if (function_exists('siteseo_get_toggle_white_label_option') && '1' == siteseo_get_toggle_white_label_option()) {
		//do nothing
	} else {
		?>
		
	<div id="notice-get-started-alert" class="siteseo-get-started siteseo-alert deleteable">
		<div class="siteseo-get-started-left">
			<p class="preheader"><?php esc_html_e('How-to get started', 'siteseo'); ?></p>

			<h2><?php esc_html_e('Welcome to SiteSEO!', 'siteseo'); ?></h2>

			<p><?php esc_html_e('Launch our installation wizard to quickly and easily configure the basic SEO settings for your site. Browse our video guides to go further. Can\'t find the answers to your questions? Open a ticket from your customer area. A happiness engineer will be happy to help you.', 'siteseo'); ?></p>

			<p class="siteseo-card-actions">
				<a href="<?php echo esc_url(admin_url('admin.php?page=siteseo-setup')); ?>" class="btn btnPrimary">
					<?php esc_html_e('Get started', 'siteseo'); ?>
				</a>
				<button type="button" name="notice-get-started" id="notice-get-started" class="btn btnTertiary" data-notice="notice-get-started">
					<?php esc_html_e('Dismiss','siteseo'); ?>
				</button>
			</p>
		</div>
		<div class="siteseo-get-started-right">
			<img class="siteseo-get-started-img" src="<?php echo esc_url(SITESEO_ASSETS_DIR) . '/img/seo-get-started.jpg'; ?>" alt=""/>
		</div>
	</div>

		<?php
	}
} ?>
	<div class="siteseo-dashboard-columns">
		<div class="siteseo-dashboard-column">
			<?php

				$cards = [
					'siteseo-page-list' => 'siteseo_features_list',
					'siteseo-notice-list' => 'siteseo_dashboard_notice_list',
					'notice-insights-alert' => 'siteseo_insights',
					'siteseo-news-panel' => 'siteseo_news_fn',
				];

				$order = siteseo_dashboard_cards_order_option();

				if (!empty($order)) {
					foreach($order as $key => $value) {
						if (isset($cards[$value])) {
							call_user_func($cards[$value]);
						}
					}
				} else {
					foreach($cards as $key => $value) {
						call_user_func($cards[$key]);
					}
				}				
			?>
			
		</div>
		<div class="siteseo-dashboard-column siteseo-dashboard-column-right">
		
			<div id="siteseo-intro" class="siteseo-intro">
				<h2><?php esc_html_e('Your SEO today', 'siteseo'); ?></h2>
				<p><?php esc_html_e('To do\'s, tips, and insights for your business', 'siteseo'); ?></p>
			</div>
		
			<?php
				if (defined('SITESEO_WL_ADMIN_HEADER') && SITESEO_WL_ADMIN_HEADER === false) {
					//do nothing
				} else {
					siteseo_tasks();
				}
			?>
			
			<div id="notice-docs-alert" class="siteseo-notice-card">
			<span class="dashicons dashicons-thumbs-up"></span>
				<h3><?php esc_html_e('You like SiteSEO? Please help us by rating us 5 stars!'); ?></h3>
				<p><?php esc_html_e('Support the development and improvement of the plugin by taking 15 seconds of your time to leave us a user review on the official WordPress plugins repository. Thank you!'); ?></p>
				<p class="siteseo-card-actions">
					<a class="btn btnSecondary" href="https://wordpress.org/support/view/plugin-reviews/siteseo?rate=5#postform" target="_blank"><?php esc_html_e('Rate us!'); ?></a>
				</p>
			</div>
			<div id="notice-support" class="siteseo-notice-card">
				<span class="dashicons dashicons-twitter"></span>
				<h3><?php esc_html_e('Let your followers know that you use siteseo to boost your website'); ?></h3>
				<p><?php esc_html_e("Supercharge your website's SEO with our powerful siteseo plugin! Boost your search rankings, drive more traffic, and increase conversions. Try it today and unlock the full potential of your website. #siteseo #plugin #SEO"); ?></p>
				<p class="siteseo-card-actions">
					<a id="tweet" class="btn btnSecondary" href="https://twitter.com/intent/tweet?text=Supercharge your website with our SEO plugin. Boost rankings, attract traffic, and dominate the competition. Elevate your SEO game! #SiteSEO #SEO #plugin" target="_blank"><?php esc_html_e('Tweet'); ?></a>
				</p>
			</div>
		</div>
	</div>

	<?php
	echo wp_kses_post($this->siteseo_feature_save()); ?>
</div>
<?php

function siteseo_dashboard_notice_list(){
?>
<div id="siteseo-notice-list" class="siteseo-notice-list siteseo-card">
	<div class="siteseo-card-title">
		<h2><?php esc_html_e('Notifications', 'siteseo'); ?>
		</h2>
		<div class="siteseo-drag-icon-container">
			<span class="dashicons dashicons-arrow-up-alt2"></span>
			<span class="dashicons dashicons-arrow-down-alt2"></span>
			<span class="dashicons dashicons-controls-play"></span>
		</div>
	</div>
	<?php
	include_once dirname(dirname(__FILE__)) . '/notifications-center.php';
	?>
</div>
<?php
}

function siteseo_features_list(){

?>
<div id="siteseo-page-list" class="siteseo-page-list siteseo-card">
	<div class="siteseo-card-title">
		<h2><?php esc_html_e('SiteSEO features management', 'siteseo'); ?>
		</h2>
		<div class="siteseo-drag-icon-container">
			<span class="dashicons dashicons-arrow-up-alt2"></span>
			<span class="dashicons dashicons-arrow-down-alt2"></span>
			<span class="dashicons dashicons-controls-play"></span>
		</div>
	</div>

	<?php
		$features = [
			'titles' => [
				'title'		 => esc_html__('Titles & Metas', 'siteseo'),
				'desc'		  => esc_html__('Manage all your titles & metas for post types, taxonomies, archives...', 'siteseo'),
				'btn_primary'   => admin_url('admin.php?page=siteseo-titles'),
				'filter'		=> 'siteseo_remove_feature_titles',
			],
			'xml-sitemap' => [
				'title'		 => esc_html__('XML & HTML Sitemaps', 'siteseo'),
				'desc'		  => esc_html__('Manage your XML - Image - Video - HTML Sitemap.', 'siteseo'),
				'btn_primary'   => admin_url('admin.php?page=siteseo-xml-sitemap'),
				'filter'		=> 'siteseo_remove_feature_xml_sitemap',
			],
			'social' => [
				'title'		 => esc_html__('Social Networks', 'siteseo'),
				'desc'		  => esc_html__('Open Graph, Twitter Card, Google Knowledge Graph and more...', 'siteseo'),
				'btn_primary'   => admin_url('admin.php?page=siteseo-social'),
				'filter'		=> 'siteseo_remove_feature_social',
			],
			'google-analytics' => [
				'title'		 => esc_html__('Analytics', 'siteseo'),
				'desc'		  => esc_html__('Track everything about your visitors with Google Analytics / Matomo / Microsoft Clarity.', 'siteseo'),
				'btn_primary'   => admin_url('admin.php?page=siteseo-google-analytics'),
				'filter'		=> 'siteseo_remove_feature_google_analytics',
			],
			'instant-indexing' => [
				'title'		 => esc_html__('Instant Indexing', 'siteseo'),
				'desc'		  => esc_html__('Ping Google & Bing to quickly index your content.', 'siteseo'),
				'btn_primary'   => admin_url('admin.php?page=siteseo-instant-indexing'),
				'filter'		=> 'siteseo_remove_feature_instant_indexing',
			],
			'advanced' => [
				'title'		 => esc_html__('Image SEO & Advanced settings', 'siteseo'),
				'desc'		  => esc_html__('Optimize your images for SEO. Configure advanced settings.', 'siteseo'),
				'btn_primary'   => admin_url('admin.php?page=siteseo-advanced'),
				'filter'		=> 'siteseo_remove_feature_advanced',
			],
		];

		$features = apply_filters('siteseo_features_list_before_tools', $features);

		$features['tools'] = [
			'title'		 => esc_html__('Tools', 'siteseo'),
			'desc'		  => esc_html__('Import/Export plugin settings from site to site.', 'siteseo'),
			'btn_primary'   => admin_url('admin.php?page=siteseo-import-export'),
			'filter'		=> 'siteseo_remove_feature_tools',
			'toggle'		=> false,
		];

		$features = apply_filters('siteseo_features_list_after_tools', $features);

		if (! empty($features)) { ?>
	<div class="siteseo-card-content">

		<?php foreach ($features as $key => $value) {
			if (isset($value['filter'])) {
				$siteseo_feature = apply_filters($value['filter'], true);
			}
			?>

			<div class="siteseo-cart-list inner">

				<?php
				if (true === $siteseo_feature) {
					$title = isset($value['title']) ? $value['title'] : null;
					$desc = isset($value['desc']) ? $value['desc'] : null;
					$btn_primary = isset($value['btn_primary']) ? $value['btn_primary'] : '';
					$toggle = isset($value['toggle']) ? $value['toggle'] : true;
				?>
				<a href="<?php echo esc_attr($btn_primary); ?>">
					<div class="siteseo-card-item">
						<h3><?php echo esc_html($title); ?></h3>
						<p><?php echo esc_html($desc); ?></p>
					</div>
				</a>
				<?php
					if (true === $toggle) {
						if ('1' == siteseo_get_toggle_option($key)) {
							$siteseo_get_toggle_option = '1';
						} else {
							$siteseo_get_toggle_option = '0';
						} ?>
						<span class="screen-reader-text"><?php printf(esc_html__('Toggle %s','siteseo'), esc_html($title)); ?></span>
						<input type="checkbox" name="toggle-<?php echo esc_attr($key); ?>" id="toggle-<?php echo esc_attr($key); ?>" class="toggle" data-toggle="<?php echo esc_attr($siteseo_get_toggle_option); ?>">
						<label for="toggle-<?php echo esc_attr($key); ?>"></label>
				<?php 
					}
				}
				?>

			</div>

			<?php
		} ?>
	</div>
	<?php }
	?>
</div>
<?php
	
}

function siteseo_insights(){
	global $current_tab;
	do_action('siteseo_dashboard_insights', $current_tab);
}

function siteseo_news_fn(){
		
	if (defined('SITESEO_WL_ADMIN_HEADER') && SITESEO_WL_ADMIN_HEADER === false) {
		//do nothing
	} else {

			//News Center
			function siteseo_advanced_appearance_news_option() {
				$options = get_option('siteseo_advanced_option_name');
				if( ! empty($options) && isset($options['appearance_news'])) {
					return $options['appearance_news'];
				}
			}

			$class = '1' != siteseo_advanced_appearance_news_option() ? 'is-active' : '';
		?>

		<div id="siteseo-news-panel"
			class="siteseo-card <?php echo esc_attr($class); ?>"
			style="display: none">
			<div class="siteseo-card-title">
				<h2><?php esc_html_e('Latest News from SiteSEO Blog', 'siteseo'); ?></h2>

				<div>					
					<div class="siteseo-drag-icon-container">
						<span class="dashicons dashicons-arrow-up-alt2"></span>
						<span class="dashicons dashicons-arrow-down-alt2"></span>
						<span class="dashicons dashicons-controls-play"></span>
						<span class="siteseo-item-toggle-options"></span>
						<div class="siteseo-card-popover">
							<?php
								$options = get_option('siteseo_dashboard_option_name');
								$value   = isset($options['news_max_items']) ? esc_attr($options['news_max_items']) : 5;
							?>

							<p>
								<label for="siteseo_dashboard_option_name[news_max_items]">
									<?php esc_html_e('How many items would you like to display?', 'siteseo'); ?>
								</label>
								<input id="news_max_items" name="siteseo_dashboard_option_name[news_max_items]" type="number" step="1"
									min="1" max="5" value="<?php echo esc_attr($value); ?>">
							</p>

							<button id="siteseo-news-items" type="submit" class="btn btnSecondary">
								<?php esc_html_e('Save', 'siteseo'); ?>
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="siteseo-card-content">
				<?php
				include_once ABSPATH . WPINC . '/feed.php';

				// Get a SimplePie feed object from the specified feed source.

				$wplang = get_locale();

				$rss = fetch_feed(SITESEO_WEBSITE.'feed');

				$maxitems = 0;

				if ( ! is_wp_error($rss)) { // Checks that the object is created correctly
					// Figure out how many total items there are, but limit it to 5.
					$maxitems = $rss->get_item_quantity($value);

					// Build an array of all the items, starting with element 0 (first element).
					$rss_items = $rss->get_items(0, $maxitems);
				}
				?>

				<ul class="siteseo-list-items" role="menu">
					<?php if (0 == $maxitems) { ?>
					<li class="siteseo-item has-action">
						<?php esc_html_e('No items', 'siteseo'); ?>
					</li>
					<?php } else { ?>
					<?php // Loop through each feed item and display each item as a hyperlink.?>
					<?php foreach ($rss_items as $item) { ?>
					<li class="siteseo-item has-action siteseo-item-inner">
						<a href="<?php echo esc_url($item->get_permalink()); ?>"
							target="_blank" class="siteseo-item-inner"
							title="<?php printf(esc_html__('Learn more about %s in a new tab', 'siteseo'), esc_html($item->get_title())); ?>">
							<p class="siteseo-item-date"><?php echo esc_html($item->get_date('M Y')); ?>
							</p>

							<h3 class="siteseo-item-title">
								<?php echo esc_html($item->get_title()); ?><span class="dashicons dashicons-external"></span>
							</h3>
							<p class="siteseo-item-content"><?php echo esc_html($item->get_description()); ?>
							</p>
						</a>
					</li>
					<?php } ?>
					<?php } ?>
				</ul>
			</div>
			<div class="siteseo-card-footer">
				<a href="<?php echo esc_url(SITESEO_WEBSITE.'blog'); ?>" target="_blank"><?php esc_html_e('All news', 'siteseo'); ?></a>
				<span class="dashicons dashicons-external"></span>
			</div>
		</div>
<?php }

}

function siteseo_tasks(){
	
	if (defined('SITESEO_WL_ADMIN_HEADER') && SITESEO_WL_ADMIN_HEADER === false) {
		//do nothing
	} else {

		function siteseo_get_hidden_notices_tasks_option(){
			$options = get_option('siteseo_notices');
			if(!empty($options) && isset($options['notice-tasks'])){
				return $options['notice-tasks'];
			}
		}

		if ('1' != siteseo_get_hidden_notices_tasks_option()) {
	?>

	<div id="notice-tasks-alert" class="siteseo-card">
		<div class="siteseo-card-title">
			<h2><?php esc_html_e('Get ready to improve your SEO', 'siteseo'); ?>
			</h2>

			<span class="siteseo-item-toggle-options"></span>
			<div class="siteseo-card-popover">
				<?php
					$options = get_option('siteseo_dashboard_option_name');
					$value   = isset($options['hide_tasks']) ? esc_attr($options['hide_tasks']) : 5;
				?>

				<button id="notice-tasks" name="notice-tasks" data-notice="notice-tasks" type="submit" class="btn btnSecondary">
					<?php esc_html_e('Hide this', 'siteseo'); ?>
				</button>
			</div>
		</div>
		<div class="siteseo-card-content">
			<?php
				/**
				 * Check if XML sitemaps feature is correctly enabled by the user
				 *
				 * @since 1.0.0
				 * author Softaculous
				 */
				function siteseo_tasks_sitemaps(){
					$options = get_option('siteseo_xml_sitemap_option_name');
					if (isset($options['xml_sitemap_general_enable']) && ('1' === siteseo_get_toggle_option('xml-sitemap'))) {
						return 'done';
					}
					
					return;
				}

				/**
				 * Check if Social Networds feature is correctly enabled by the user
				 *
				 * @since 1.0.0
				 * author Softaculous
				 *
				 */
				function siteseo_tasks_social_networks(){
					$options = get_option('siteseo_social_option_name');
					
					if (isset($options['social_facebook_og']) && ('1' === siteseo_get_toggle_option('social'))) {
						return 'done';
					}
					return;
				}
				
				$tasks = [
					[
						'done' => siteseo_tasks_sitemaps(),
						'link' => admin_url('admin.php?page=siteseo-xml-sitemap'),
						'label' => esc_html__('Generate XML sitemaps', 'siteseo'),
					],
					[
						'done' => siteseo_tasks_social_networks(),
						'link' => admin_url('admin.php?page=siteseo-social'),
						'label' => esc_html__('Be social', 'siteseo'),
					]
				];

				$tasks = apply_filters('siteseo_dashboard_tasks', $tasks);
			?>

			<ul class="siteseo-list-items" role="menu">
				<?php foreach($tasks as $key => $task) { ?>
					<li class="siteseo-item has-action siteseo-item-inner <?php if (empty($task['done'])) { echo 'is-active'; }; ?>">
						<a href="<?php echo esc_url($task['link']); ?>" class="siteseo-item-inner check <?php echo esc_attr($task['done']); ?>" data-index="<?php echo esc_attr($key + 1); ?>">
							<?php echo esc_html($task['label']); ?>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php
		}
	}
	
}