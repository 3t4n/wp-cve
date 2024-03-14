<?php
defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

global $typenow;
global $pagenow;

function siteseo_redirections_value($siteseo_redirections_value) {
	if ('' != $siteseo_redirections_value) {
		return $siteseo_redirections_value;
	}
}

$data_attr			 = [];
$data_attr['data_tax'] = '';
$data_attr['termId']   = '';

if ('post-new.php' == $pagenow || 'post.php' == $pagenow) {
	$data_attr['current_id'] = get_the_id();
	$data_attr['origin']	 = 'post';
	$data_attr['title']	  = get_the_title($data_attr['current_id']);
} elseif ('term.php' == $pagenow || 'edit-tags.php' == $pagenow) {
	global $tag;
	$data_attr['current_id'] = $tag->term_id;
	$data_attr['termId']	 = $tag->term_id;
	$data_attr['origin']	 = 'term';
	$data_attr['data_tax']   = $tag->taxonomy;
	$data_attr['title']	  = $tag->name;
}

$data_attr['isHomeId'] = get_option('page_on_front');
if ('0' === $data_attr['isHomeId']) {
	$data_attr['isHomeId'] = '';
}

if ('term.php' == $pagenow || 'edit-tags.php' == $pagenow) { ?>
<tr id="term-siteseo" class="form-field">
	<th scope="row">
		<h2>
			<?php esc_html_e('SiteSEO', 'siteseo'); ?>
		</h2>
	</th>
	<td>
		<div id="siteseo_cpt">
			<div class="inside">
<?php } ?>
				<div id="siteseo-tabs"
					data-home-id="<?php echo esc_attr($data_attr['isHomeId']); ?>"
					data-term-id="<?php echo esc_attr($data_attr['termId']); ?>"
					data_id="<?php echo esc_attr($data_attr['current_id']); ?>"
					data_origin="<?php echo esc_attr($data_attr['origin']); ?>"
					data_tax="<?php echo esc_attr($data_attr['data_tax']); ?>">
					
					<?php 
					
					if(!empty($siteseo->display_ca_metaboxe)){
						$seo_tabs['content-analysis']	= '<li><a href="#siteseo-ca-tabs">' . __('Content Analysis', 'siteseo') . '</a></li>';
					}
					
					if ('siteseo_404' != $typenow) {
						$seo_tabs['title-tab']	= '<li><a href="#tabs-1">' . __('Titles settings', 'siteseo') . '</a></li>';
						$seo_tabs['social-tab']   = '<li><a href="#tabs-2">' . __('Social', 'siteseo') . '</a></li>';
						$seo_tabs['advanced-tab'] = '<li><a href="#tabs-3">' . __('Advanced', 'siteseo') . '<span id="siteseo-advanced-alert"></span></a></li>';
					}
					$seo_tabs['redirect-tab'] = '<li><a href="#tabs-4">' . __('Redirection', 'siteseo') . '</a></li>';

		$seo_tabs = apply_filters('siteseo_metabox_seo_tabs', $seo_tabs, $typenow, $pagenow);

		if ( ! empty($seo_tabs)) { ?>
					<ul>
						<?php foreach ($seo_tabs as $tab) {
			echo wp_kses_post($tab);
		} ?>
					</ul>
					<?php }
					if ('siteseo_404' != $typenow) {
						if (array_key_exists('title-tab', $seo_tabs)) { ?>
					<div id="tabs-1">
						<?php if (is_plugin_active('woocommerce/woocommerce.php') && function_exists('wc_get_page_id')) {
							$shop_page_id = wc_get_page_id('shop');
							if ('post-new.php' == $pagenow || 'post.php' == $pagenow) {
								if ($post && absint($shop_page_id) === absint($post->ID)) { ?>
						<p class="notice notice-info">
							<?php printf(wp_kses_post(__('This is your <strong>Shop page</strong>. Go to <a href="%s"><strong>SEO > Titles & Metas > Archives > Products</strong></a> to edit your title and meta description.', 'siteseo')), esc_url(admin_url('admin.php?page=siteseo-titles'))); ?>
						</p>
						<?php }
							}
						} ?>
						<div class="box-left">
							<?php do_action('siteseo_titles_title_tab_before', $pagenow); ?>
							<p>
								<label for="siteseo_titles_title_meta">
									<?php
									esc_html_e('Title', 'siteseo');
									echo wp_kses_post(siteseo_tooltip(__('Meta title', 'siteseo'), __('Titles are critical to give users a quick insight into the content of a result and why it’s relevant to their query. It\'s often the primary piece of information used to decide which result to click on, so it\'s important to use high-quality titles on your web pages.', 'siteseo'), esc_html('<title>My super title</title>')));
								?>
								</label>
								<input id="siteseo_titles_title_meta" type="text" name="siteseo_titles_title"
									class="components-text-control__input"
									placeholder="<?php esc_html_e('Enter your title', 'siteseo'); ?>"
									aria-label="<?php esc_html_e('Title', 'siteseo'); ?>"
									value="<?php echo esc_attr($siteseo_titles_title); ?>" />
							</p>
							<div class="siteseo-progress">
								<div id="siteseo_titles_title_counters_progress" class="siteseo-progress-bar"
									role="progressbar" style="width: 1%;" aria-valuenow="1" aria-valuemin="0"
									aria-valuemax="100">1%</div>
							</div>
							<div class="wrap-siteseo-counters">
								<div id="siteseo_titles_title_pixel"></div>
								<strong><?php esc_html_e(' / 568 pixels - ', 'siteseo'); ?></strong>
								<div id="siteseo_titles_title_counters"></div>
								<?php esc_html_e(' (maximum recommended limit)', 'siteseo'); ?>
							</div>

							<div class="wrap-tags">
								<?php if ('term.php' == $pagenow || 'edit-tags.php' == $pagenow) { ?>
								<button type="button" class="<?php echo esc_attr(siteseo_btn_secondary_classes()); ?> tag-title" id="siteseo-tag-single-title" data-tag="%%term_title%%"><span
										class="dashicons dashicons-insert"></span><?php esc_html_e('Term Title', 'siteseo'); ?></button>
								<?php } else { ?>
								<button type="button" class="<?php echo esc_attr(siteseo_btn_secondary_classes()); ?> tag-title" id="siteseo-tag-single-title" data-tag="%%post_title%%"><span
										class="dashicons dashicons-insert"></span><?php esc_html_e('Post Title', 'siteseo'); ?></button>
								<?php } ?>
								<button type="button" class="<?php echo esc_attr(siteseo_btn_secondary_classes()); ?> tag-title" id="siteseo-tag-single-site-title" data-tag="%%sitetitle%%">
									<span class="dashicons dashicons-insert"></span><?php esc_html_e('Site Title', 'siteseo'); ?></button>
								<button type="button" class="<?php echo esc_attr(siteseo_btn_secondary_classes()); ?> tag-title" id="siteseo-tag-single-sep" data-tag="%%sep%%"><span
										class="dashicons dashicons-insert"></span><?php esc_html_e('Separator', 'siteseo'); ?></button>

								<?php siteseo_render_dyn_variables('tag-title'); ?>
							</div>
							<p>
								<label for="siteseo_titles_desc_meta">
									<?php
								esc_html_e('Meta description', 'siteseo');
								echo wp_kses_post(siteseo_tooltip(__('Meta description', 'siteseo'), __('A meta description tag should generally inform and interest users with a short, relevant summary of what a particular page is about. <br>They are like a pitch that convince the user that the page is exactly what they\'re looking for. <br>There\'s no limit on how long a meta description can be, but the search result snippets are truncated as needed, typically to fit the device width.', 'siteseo'), esc_html('<meta name="description" content="my super meta description" />')));
							?>
								</label>
								<textarea id="siteseo_titles_desc_meta" rows="4" name="siteseo_titles_desc"
									class="components-text-control__input"
									placeholder="<?php esc_html_e('Enter your meta description', 'siteseo'); ?>"
									aria-label="<?php esc_html_e('Meta description', 'siteseo'); ?>"><?php echo esc_html($siteseo_titles_desc); ?></textarea>
							</p>
							<div class="siteseo-progress">
								<div id="siteseo_titles_desc_counters_progress" class="siteseo-progress-bar"
									role="progressbar" style="width: 1%;" aria-valuenow="1" aria-valuemin="0"
									aria-valuemax="100">1%</div>
							</div>
							<div class="wrap-siteseo-counters">
								<div id="siteseo_titles_desc_pixel"></div>
								<strong><?php esc_html_e(' / 940 pixels - ', 'siteseo'); ?></strong>
								<div id="siteseo_titles_desc_counters"></div>
								<?php esc_html_e(' (maximum recommended limit)', 'siteseo'); ?>
							</div>
							<div class="wrap-tags">
								<?php if ('term.php' == $pagenow || 'edit-tags.php' == $pagenow) { ?>
								<button type="button" class="<?php echo esc_attr(siteseo_btn_secondary_classes()); ?> tag-title" id="siteseo-tag-single-excerpt" data-tag="%%_category_description%%">
									<span class="dashicons dashicons-insert"></span><?php esc_html_e('Category / term description', 'siteseo'); ?>
								</button>
								<?php } else { ?>
								<button type="button" class="<?php echo esc_attr(siteseo_btn_secondary_classes()); ?> tag-title" id="siteseo-tag-single-excerpt" data-tag="%%post_excerpt%%">
									<span class="dashicons dashicons-insert"></span><?php esc_html_e('Post Excerpt', 'siteseo'); ?>
								</button>
								<?php }
						siteseo_render_dyn_variables('tag-description');
				?>
							</div>
						</div>

						<?php
					$toggle_preview = 1;
					$toggle_preview = apply_filters('siteseo_toggle_mobile_preview', $toggle_preview);
				?>

						<div class="box-right">
							<div class="google-snippet-preview mobile-preview">
								<h3>
									<?php
								esc_html_e('Google Snippet Preview', 'siteseo');
								echo wp_kses_post(siteseo_tooltip(__('Snippet Preview', 'siteseo'), __('The Google preview is a simulation. <br>There is no reliable preview because it depends on the screen resolution, the device used, the expression sought, and Google. <br>There is not one snippet for one URL but several. <br>All the data in this overview comes directly from your source code. <br>This is what the crawlers will see.', 'siteseo'), null));
							?>
								</h3>
								<p><?php esc_html_e('This is what your page will look like in Google search results. You have to publish your post to get the Google Snippet Preview. Note that Google may optionally display an image of your article.', 'siteseo'); ?>
								</p>
								<div class="wrap-toggle-preview">
									<p>
										<span class="dashicons dashicons-smartphone"></span>
										<?php esc_html_e('Mobile Preview', 'siteseo'); ?>
										<input type="checkbox" name="toggle-preview" id="toggle-preview" class="toggle"
											data-toggle="<?php echo esc_attr($toggle_preview); ?>">
										<label for="toggle-preview"></label>
									</p>
								</div>
								<?php
				global $tag;

				$gp_title	   = '';
				$gp_permalink   = '';
				$alt_site_title = !empty(siteseo_get_service('TitleOption')->getHomeSiteTitleAlt()) ? siteseo_get_service('TitleOption')->getHomeSiteTitleAlt() : get_bloginfo('name');

				if (get_the_title()) {
					$gp_title	   = '<div class="snippet-title-default" style="display:none">' . get_the_title() . ' - ' . get_bloginfo('name') . '</div>';
					$gp_permalink   = '<div class="snippet-permalink"><span class="snippet-sitename">' . $alt_site_title . '</span>' . htmlspecialchars(urldecode(get_permalink())) . '</div>';
				} elseif ($tag) {
					if (false === is_wp_error(get_term_link($tag))) {
						$gp_title	   = '<div class="snippet-title-default" style="display:none">' . $tag->name . ' - ' . get_bloginfo('name') . '</div>';
						$gp_permalink   = '<div class="snippet-permalink"><span class="snippet-sitename">' . $alt_site_title . '</span>' . htmlspecialchars(urldecode(get_term_link($tag))) . '</div>';
					}
				}

				$siteicon = '<div class="snippet-favicon"><img aria-hidden="true" height="18" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABs0lEQVR4AWL4//8/RRjO8Iucx+noO0MWUDo16FYABMGP6ZfUcRnWtm27jVPbtm3bttuH2t3eFPcY9pLz7NxiLjCyVd87pKnHyqXyxtCs8APd0rnyxiu4qSeA3QEDrAwBDrT1s1Rc/OrjLZwqVmOSu6+Lamcpp2KKMA9PH1BYXMe1mUP5qotvXTywsOEEYHXxrY+3cqk6TMkYpNr2FeoY3KIr0RPtn9wQ2unlA+GMkRw6+9TFw4YTwDUzx/JVvARj9KaedXRO8P5B1Du2S32smzqUrcKGEyA+uAgQjKX7zf0boWHGfn71jIKj2689gxp7OAGShNcBUmLMPVjZuiKcA2vuWHHDCQxMCz629kXAIU4ApY15QwggAFbfOP9DhgBJ+nWVJ1AZAfICAj1pAlY6hCADZnveQf7bQIwzVONGJonhLIlS9gr5mFg44Xd+4S3XHoGNPdJl1INIwKyEgHckEhgTe1bGiFY9GSFBYUwLh1IkiJUbY407E7syBSFxKTszEoiE/YdrgCEayDmtaJwCI9uu8TKMuZSVfSa4BpGgzvomBR/INhLGzrqDotp01ZR8pn/1L0JN9d9XNyx0AAAAAElFTkSuQmCC" width="18" alt="favicon"></div>';
				if (get_site_icon_url(32)) {
					$siteicon = '<div class="snippet-favicon"><img aria-hidden="true" height="18" src="' . get_site_icon_url(32) . '" width="18" alt="favicon"/></div>';
				}	
				 ?>

								<div class="wrap-snippet">
									<div class="wrap-m-icon-permalink"><?php echo wp_kses_post($siteicon . $gp_permalink);?></div>
									<div class="snippet-title"></div>
									<div class="snippet-title-custom" style="display:none"></div>

									<div class="wrap-snippet-mobile">
										<div class="wrap-meta-desc">
											<?php
											echo wp_kses_post($gp_title);

											if ('post-new.php' == $pagenow || 'post.php' == $pagenow) {
												echo wp_kses_post(siteseo_display_date_snippet());
											} ?>

											<div class="snippet-description">...</div>
											<div class="snippet-description-custom" style="display:none"></div>
											<div class="snippet-description-default" style="display:none"></div>
										</div>
										<div class="wrap-post-thumb"><?php the_post_thumbnail('full', ['class' => 'snippet-post-thumb']); ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }
						if (array_key_exists('advanced-tab', $seo_tabs)) { ?>
					<div id="tabs-3">
						<span class="siteseo-section"><?php esc_html_e('Meta robots settings', 'siteseo'); ?></span>
						<p class="description">
							<?php $url = admin_url('admin.php?page=siteseo-titles#tab=tab_siteseo_titles_single');
								/* translators: %s: link to plugin settings page */
								printf(wp_kses_post(__('You cannot uncheck a parameter? This is normal, and it‘s most likely defined in the <a href="%s">global settings of the plugin.</a>', 'siteseo')), esc_url($url));
							?>
						</p>
						<p>
							<label for="siteseo_robots_index_meta">
								<input type="checkbox" name="siteseo_robots_index" id="siteseo_robots_index_meta"
									value="yes" <?php echo checked($siteseo_robots_index, 'yes', false); ?>
								<?php echo esc_html($disabled['robots_index']); ?>/>
								<?php
									echo wp_kses_post(__('Do not display this page in search engine results / Sitemaps <strong>(noindex)</strong>', 'siteseo'));
									echo wp_kses_post(siteseo_tooltip(__('"noindex" robots meta tag', 'siteseo'), __('By checking this option, you will add a meta robots tag with the value "noindex". <br>Search engines will not index this URL in the search results.', 'siteseo'), esc_html('<meta name="robots" content="noindex" />')));
								?>
							</label>
						</p>
						<p>
							<label for="siteseo_robots_follow_meta">
								<input type="checkbox" name="siteseo_robots_follow" id="siteseo_robots_follow_meta"
									value="yes" <?php echo checked($siteseo_robots_follow, 'yes', false); ?>
								<?php echo esc_html($disabled['robots_follow']); ?>/>
								<?php
									echo wp_kses_post(__('Do not follow links for this page <strong>(nofollow)</strong>', 'siteseo'));
									echo wp_kses_post(siteseo_tooltip(__('"nofollow" robots meta tag', 'siteseo'), __('By checking this option, you will add a meta robots tag with the value "nofollow". <br>Search engines will not follow links from this URL.', 'siteseo'), esc_html('<meta name="robots" content="nofollow" />')));
								?>
							</label>
						</p>
						<p>
							<label for="siteseo_robots_imageindex_meta">
								<input type="checkbox" name="siteseo_robots_imageindex"
									id="siteseo_robots_imageindex_meta" value="yes" <?php echo checked($siteseo_robots_imageindex, 'yes', false); ?>
								<?php echo esc_html($disabled['imageindex']); ?>/>
								<?php echo wp_kses_post(__('Do not index images for this page <strong>(noimageindex)</strong>', 'siteseo')); ?>
								<?php echo wp_kses_post(siteseo_tooltip(__('"noimageindex" robots meta tag', 'siteseo'), __('By checking this option, you will add a meta robots tag with the value "noimageindex". <br> Note that your images can always be indexed if they are linked from other pages.', 'siteseo'), esc_html('<meta name="robots" content="noimageindex" />'))); ?>
							</label>
						</p>
						<p>
							<label for="siteseo_robots_archive_meta">
								<input type="checkbox" name="siteseo_robots_archive" id="siteseo_robots_archive_meta"
									value="yes" <?php echo checked($siteseo_robots_archive, 'yes', false); ?>
								<?php echo esc_html($disabled['archive']); ?>/>
								<?php echo wp_kses_post(__('Do not display a "Cached" link in the Google search results <strong>(noarchive)</strong>', 'siteseo')); ?>
								<?php echo wp_kses_post(siteseo_tooltip(__('"noarchive" robots meta tag', 'siteseo'), __('By checking this option, you will add a meta robots tag with the value "noarchive".', 'siteseo'), esc_html('<meta name="robots" content="noarchive" />'))); ?>
							</label>
						</p>
						<p>
							<label for="siteseo_robots_snippet_meta">
								<input type="checkbox" name="siteseo_robots_snippet" id="siteseo_robots_snippet_meta"
									value="yes" <?php echo checked($siteseo_robots_snippet, 'yes', false); ?>
								<?php echo esc_html($disabled['snippet']); ?>/>
								<?php echo wp_kses_post(__('Do not display a description in search results for this page <strong>(nosnippet)</strong>', 'siteseo')); ?>
								<?php echo wp_kses_post(siteseo_tooltip(__('"nosnippet" robots meta tag', 'siteseo'), __('By checking this option, you will add a meta robots tag with the value "nosnippet".', 'siteseo'), esc_html('<meta name="robots" content="nosnippet" />'))); ?>
							</label>
						</p>
						<p>
							<label for="siteseo_robots_canonical_meta">
								<?php
									esc_html_e('Canonical URL', 'siteseo');
									echo wp_kses_post(siteseo_tooltip(__('Canonical URL', 'siteseo'), __('A canonical URL is the URL of the page that Google thinks is most representative from a set of duplicate pages on your site. <br>For example, if you have URLs for the same page (for example: example.com?dress=1234 and example.com/dresses/1234), Google chooses one as canonical. <br>Note that the pages do not need to be absolutely identical; minor changes in sorting or filtering of list pages do not make the page unique (for example, sorting by price or filtering by item color). The canonical can be in a different domain than a duplicate.', 'siteseo'), esc_html('<link rel="canonical" href="https://www.example.com/my-post-url/" />')));
								?>
							</label>
							<input id="siteseo_robots_canonical_meta" type="text" name="siteseo_robots_canonical"
								class="components-text-control__input"
								placeholder="<?php esc_html_e('Default value: ', 'siteseo') . htmlspecialchars(urldecode(get_permalink())); ?>"
								aria-label="<?php esc_html_e('Canonical URL', 'siteseo'); ?>"
								value="<?php echo esc_attr($siteseo_robots_canonical); ?>" />
						</p>

						<?php if (('post' == $typenow || 'product' == $typenow) && ('post.php' == $pagenow || 'post-new.php' == $pagenow)) { ?>
						<p>
							<label for="siteseo_robots_primary_cat"><?php esc_html_e('Select a primary category', 'siteseo'); ?></label>
							<span class="description"><?php esc_html_e('Set the category that gets used in the %category% permalink and in our breadcrumbs if you have multiple categories.', 'siteseo'); ?>
						</p>
						<select id="siteseo_robots_primary_cat" name="siteseo_robots_primary_cat">

							<?php $cats = get_categories();

					if ('product' == $typenow) {
						$cats = get_the_terms($post, 'product_cat');
					}
					if ( ! empty($cats)) { ?>
							<option <?php echo selected('none', $siteseo_robots_primary_cat, false); ?>
								value="none"><?php esc_html_e('None (will disable this feature)', 'siteseo'); ?>
							</option>
							<?php foreach ($cats as $category) { ?>
							<option <?php echo selected($category->term_id, $siteseo_robots_primary_cat, false); ?>
								value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->name); ?>
							</option>
							<?php }
						} ?>
						</select>
						</p>
						<?php }
							do_action('siteseo_titles_title_tab_after', $pagenow, $data_attr);
						?>
					</div>
					<?php }
						if (array_key_exists('social-tab', $seo_tabs)) { ?>
					<div id="tabs-2">
						<div class="box-left">
							<p class="description-alt desc-fb">
								<svg width="24" height="24" viewBox="0 0 24 24" role="img" aria-hidden="true"
									focusable="false">
									<path
										d="M12 15.8c-3.7 0-6.8-3-6.8-6.8s3-6.8 6.8-6.8c3.7 0 6.8 3 6.8 6.8s-3.1 6.8-6.8 6.8zm0-12C9.1 3.8 6.8 6.1 6.8 9s2.4 5.2 5.2 5.2c2.9 0 5.2-2.4 5.2-5.2S14.9 3.8 12 3.8zM8 17.5h8V19H8zM10 20.5h4V22h-4z">
									</path>
								</svg>
								<?php esc_html_e('LinkedIn, Instagram, WhatsApp and Pinterest use the same social metadata as Facebook. Twitter does the same if no Twitter cards tags are defined below.', 'siteseo'); ?>
							</p>
							<p>
								<span class="dashicons dashicons-facebook-alt"></span>
							</p>
							<p>
								<span class="dashicons dashicons-external"></span>
								<a href="https://developers.facebook.com/tools/debug/sharing/?q=<?php echo esc_attr(get_permalink(get_the_id())); ?>"
									target="_blank">
									<?php esc_html_e('Ask Facebook to update its cache', 'siteseo'); ?>
								</a>
							</p>
							<p>
								<label for="siteseo_social_fb_title_meta"><?php esc_html_e('Facebook Title', 'siteseo'); ?></label>
								<input id="siteseo_social_fb_title_meta" type="text" name="siteseo_social_fb_title"
									class="components-text-control__input"
									placeholder="<?php esc_html_e('Enter your Facebook title', 'siteseo'); ?>"
									aria-label="<?php esc_html_e('Facebook Title', 'siteseo'); ?>"
									value="<?php echo esc_attr($siteseo_social_fb_title); ?>" />
							</p>
							<p>
								<label for="siteseo_social_fb_desc_meta"><?php esc_html_e('Facebook description', 'siteseo'); ?></label>
								<textarea id="siteseo_social_fb_desc_meta" name="siteseo_social_fb_desc"
									class="components-text-control__input"
									placeholder="<?php esc_html_e('Enter your Facebook description', 'siteseo'); ?>"
									aria-label="<?php esc_html_e('Facebook description', 'siteseo'); ?>"><?php echo esc_html($siteseo_social_fb_desc); ?></textarea>
							</p>
							<p>
								<label for="siteseo_social_fb_img_meta">
									<?php esc_html_e('Facebook Thumbnail', 'siteseo'); ?>
								</label>
								<input id="siteseo_social_fb_img_meta" type="text" name="siteseo_social_fb_img"
									class="components-text-control__input siteseo_social_fb_img_meta"
									placeholder="<?php esc_html_e('Select your default thumbnail', 'siteseo'); ?>"
									aria-label="<?php esc_html_e('Facebook Thumbnail', 'siteseo'); ?>"
									value="<?php echo esc_attr($siteseo_social_fb_img); ?>" />
							</p>
							<p class="description">
								<?php esc_html_e('Minimum size: 200x200px, ideal ratio 1.91:1, 8Mb max. (eg: 1640x856px or 3280x1712px for retina screens)', 'siteseo'); ?>
							</p>
							<p>
								<input type="hidden" name="siteseo_social_fb_img_attachment_id" id="siteseo_social_fb_img_attachment_id" class="siteseo_social_fb_img_attachment_id" value="<?php echo esc_html($siteseo_social_fb_img_attachment_id); ?>">
								<input type="hidden" name="siteseo_social_fb_img_width" id="siteseo_social_fb_img_width" class="siteseo_social_fb_img_width" value="<?php echo esc_html($siteseo_social_fb_img_width); ?>">
								<input type="hidden" name="siteseo_social_fb_img_height" id="siteseo_social_fb_img_height" class="siteseo_social_fb_img_height" value="<?php echo esc_html($siteseo_social_fb_img_height); ?>">

								<input id="siteseo_social_fb_img_upload"
									class="<?php echo esc_attr(siteseo_btn_secondary_classes()); ?>"
									type="button"
									value="<?php esc_html_e('Upload an Image', 'siteseo'); ?>" />
							</p>
						</div>
						<div class="box-right">
							<div class="facebook-snippet-preview">
								<h3>
									<?php esc_html_e('Facebook Preview', 'siteseo'); ?>
								</h3>
								<?php if ('1' == siteseo_get_toggle_option('social')) { ?>
								<p>
									<?php esc_html_e('This is what your post will look like in Facebook. You have to publish your post to get the Facebook Preview.', 'siteseo'); ?>
								</p>
								<?php } else { ?>
								<p class="notice notice-error">
									<?php esc_html_e('The Social Networks feature is disabled. Still seing informations from the FB Preview? You probably have social tags added by your theme or a plugin.', 'siteseo'); ?>
								</p>
								<?php } ?>
								<div class="facebook-snippet-box">
									<div class="snippet-fb-img-alert alert1" style="display:none">
										<p class="notice notice-error"><?php esc_html_e('File type not supported by Facebook. Please choose another image.', 'siteseo'); ?>
										</p>
									</div>
									<div class="snippet-fb-img-alert alert2" style="display:none">
										<p class="notice notice-error"><?php echo wp_kses_post(__('Minimum size for Facebook is <strong>200x200px</strong>. Please choose another image.', 'siteseo')); ?>
										</p>
									</div>
									<div class="snippet-fb-img-alert alert3" style="display:none">
										<p class="notice notice-error"><?php esc_html_e('File error. Please choose another image.', 'siteseo'); ?>
										</p>
									</div>
									<div class="snippet-fb-img-alert alert4" style="display:none">
										<p class="notice notice-info"><?php esc_html_e('Your image ratio is: ', 'siteseo'); ?><span></span>.
											<?php esc_html_e('The closer to 1.91 the better.', 'siteseo'); ?>
										</p>
									</div>
									<div class="snippet-fb-img-alert alert5" style="display:none">
										<p class="notice notice-error"><?php esc_html_e('File URL is not valid.', 'siteseo'); ?>
										</p>
									</div>
									<div class="snippet-fb-img-alert alert6" style="display:none">
										<p class="notice notice-warning"><?php esc_html_e('Your filesize is: ', 'siteseo'); ?><span></span>
											<?php esc_html_e('This is superior to 300KB. WhatsApp will not use your image.', 'siteseo'); ?>
										</p>
									</div>
									<div class="snippet-fb-img"><img src="" width="524" height="274" alt=""
											aria-label="" /><span class="siteseo_social_fb_img_upload"></span></div>
									<div class="snippet-fb-img-custom" style="display:none"><img src="" width="524"
											height="274" alt="" aria-label="" /><span class="siteseo_social_fb_img_upload"></span></div>
									<div class="snippet-fb-img-default" style="display:none"><img src="" width="524"
											height="274" alt="" aria-label="" /><span class="siteseo_social_fb_img_upload"></span></div>
									<div class="facebook-snippet-text">
										<div class="snippet-meta">
											<div class="snippet-fb-url"></div>
											<div class="fb-sep">|</div>
											<div class="fb-by"><?php esc_html_e('By ', 'siteseo'); ?>
											</div>
											<div class="snippet-fb-site-name"></div>
										</div>
										<div class="title-desc">
											<div class="snippet-fb-title"></div>
											<div class="snippet-fb-title-custom" style="display:none"></div>
											<?php global $tag;
											if (get_the_title()) { ?>
											<div class="snippet-fb-title-default" style="display:none"><?php the_title(); ?> -
												<?php bloginfo('name'); ?>
											</div>
											<?php } elseif ($tag) { ?>
											<div class="snippet-fb-title-default" style="display:none"><?php echo esc_html($tag->name); ?> -
												<?php bloginfo('name'); ?>
											</div>
											<?php } ?>
											<div class="snippet-fb-description">...</div>
											<div class="snippet-fb-description-custom" style="display:none"></div>
											<div class="snippet-fb-description-default" style="display:none"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clear"></div>
						<div class="box-left">
							<p>
								<span class="dashicons dashicons-twitter"></span>
							</p>

							<p>
								<span class="dashicons dashicons-external"></span>
								<a href="https://cards-dev.twitter.com/validator" target="_blank">
									<?php esc_html_e('Preview your Twitter card using the official validator', 'siteseo'); ?>
								</a>
							</p>
							<p>
								<label for="siteseo_social_twitter_title_meta"><?php esc_html_e('Twitter Title', 'siteseo'); ?></label>
								<input id="siteseo_social_twitter_title_meta" type="text"
									class="components-text-control__input" name="siteseo_social_twitter_title"
									placeholder="<?php esc_html_e('Enter your Twitter title', 'siteseo'); ?>"
									aria-label="<?php esc_html_e('Twitter Title', 'siteseo'); ?>"
									value="<?php echo esc_attr($siteseo_social_twitter_title); ?>" />
							</p>
							<p>
								<label for="siteseo_social_twitter_desc_meta"><?php esc_html_e('Twitter description', 'siteseo'); ?></label>
								<textarea id="siteseo_social_twitter_desc_meta" name="siteseo_social_twitter_desc"
									class="components-text-control__input"
									placeholder="<?php esc_html_e('Enter your Twitter description', 'siteseo'); ?>"
									aria-label="<?php esc_html_e('Twitter description', 'siteseo'); ?>"><?php echo esc_html($siteseo_social_twitter_desc); ?></textarea>
							</p>
							<p>
								<label for="siteseo_social_twitter_img_meta"><?php esc_html_e('Twitter Thumbnail', 'siteseo'); ?></label>
								<input id="siteseo_social_twitter_img_meta" type="text"
									class="components-text-control__input siteseo_social_twitter_img_meta" name="siteseo_social_twitter_img"
									placeholder="<?php esc_html_e('Select your default thumbnail', 'siteseo'); ?>"
									value="<?php echo esc_attr($siteseo_social_twitter_img); ?>" />
							</p>
							<p class="description">
								<?php esc_html_e('Minimum size: 144x144px (300x157px with large card enabled), ideal ratio 1:1 (2:1 with large card), 5Mb max.', 'siteseo'); ?>
							</p>
							<p>
								<input type="hidden" name="siteseo_social_twitter_img_attachment_id" id="siteseo_social_twitter_img_attachment_id" class="siteseo_social_twitter_img_attachment_id" value="<?php echo esc_html($siteseo_social_twitter_img_attachment_id); ?>">
								<input type="hidden" name="siteseo_social_twitter_img_width" id="siteseo_social_twitter_img_width" class="siteseo_social_twitter_img_width" value="<?php echo esc_html($siteseo_social_twitter_img_width); ?>">
								<input type="hidden" name="siteseo_social_twitter_img_height" id="siteseo_social_twitter_img_height" class="siteseo_social_twitter_img_height" value="<?php echo esc_html($siteseo_social_twitter_img_height); ?>">

								<input id="siteseo_social_twitter_img_upload"
									class="<?php echo esc_attr(siteseo_btn_secondary_classes()); ?>"
									type="button"
									aria-label="<?php esc_html_e('Twitter Thumbnail', 'siteseo'); ?>"
									value="<?php esc_html_e('Upload an Image', 'siteseo'); ?>" />
							</p>
						</div>
						<div class="box-right">
							<div class="twitter-snippet-preview">
								<h3><?php esc_html_e('Twitter Preview', 'siteseo'); ?>
								</h3>
								<?php if ('1' == siteseo_get_toggle_option('social')) { ?>
								<p><?php esc_html_e('This is what your post will look like in Twitter. You have to publish your post to get the Twitter Preview.', 'siteseo'); ?>
								</p>
								<?php } else { ?>
								<p class="notice notice-error"><?php esc_html_e('The Social Networks feature is disabled. Still seing informations from the Twitter Preview? You probably have social tags added by your theme or a plugin.', 'siteseo'); ?>
								</p>
								<?php } ?>
								<div class="twitter-snippet-box">
									<div class="snippet-twitter-img-alert alert1" style="display:none">
										<p class="notice notice-error"><?php esc_html_e('File type not supported by Twitter. Please choose another image.', 'siteseo'); ?>
										</p>
									</div>
									<div class="snippet-twitter-img-alert alert2" style="display:none">
										<p class="notice notice-error"><?php echo wp_kses_post(__('Minimum size for Twitter is <strong>144x144px</strong>. Please choose another image.', 'siteseo')); ?>
										</p>
									</div>
									<div class="snippet-twitter-img-alert alert3" style="display:none">
										<p class="notice notice-error"><?php esc_html_e('File error. Please choose another image.', 'siteseo'); ?>
										</p>
									</div>
									<div class="snippet-twitter-img-alert alert4" style="display:none">
										<p class="notice notice-info"><?php esc_html_e('Your image ratio is: ', 'siteseo'); ?><span></span>.
											<?php esc_html_e('The closer to 1 the better (with large card, 2 is better).', 'siteseo'); ?>
										</p>
									</div>
									<div class="snippet-twitter-img-alert alert5" style="display:none">
										<p class="notice notice-error"><?php esc_html_e('File URL is not valid.', 'siteseo'); ?>
										</p>
									</div>
									<div class="snippet-twitter-img"><img src="" width="524" height="274" alt=""
											aria-label="" /><span class="siteseo_social_twitter_img_upload"></span></div>
									<div class="snippet-twitter-img-custom" style="display:none"><img src="" width="600"
											height="314" alt="" aria-label="" /><span class="siteseo_social_twitter_img_upload"></span></div>
									<div class="snippet-twitter-img-default" style="display:none"><img src=""
											width="600" height="314" alt="" aria-label="" /><span class="siteseo_social_twitter_img_upload"></span></div>

									<div class="twitter-snippet-text">
										<div class="title-desc">
											<div class="snippet-twitter-title"></div>
											<div class="snippet-twitter-title-custom" style="display:none"></div>
											<?php global $tag;
											if (get_the_title()) { ?>
											<div class="snippet-twitter-title-default" style="display:none"><?php the_title(); ?> -
												<?php bloginfo('name'); ?>
											</div>
											<?php } elseif ($tag) { ?>
											<div class="snippet-twitter-title-default" style="display:none"><?php echo esc_html($tag->name); ?> -
												<?php bloginfo('name'); ?>
											</div>
											<?php } ?>
											<div class="snippet-twitter-description">...</div>
											<div class="snippet-twitter-description-custom" style="display:none"></div>
											<div class="snippet-twitter-description-default" style="display:none"></div>
										</div>
										<div class="snippet-meta">
											<div class="snippet-twitter-url"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }
					}

					if (array_key_exists('redirect-tab', $seo_tabs)) {
						$docs = siteseo_get_docs_links();
						?>
					<div id="tabs-4">
						<p>
							<label for="siteseo_redirections_enabled_meta" id="siteseo_redirections_enabled">
								<input type="checkbox" name="siteseo_redirections_enabled"
									id="siteseo_redirections_enabled_meta" value="yes" <?php echo checked($siteseo_redirections_enabled, 'yes', false); ?>
								/>
								<?php esc_html_e('Enable redirection?', 'siteseo'); ?>
							</label>
						</p>
						<?php if ('siteseo_404' == $typenow) { ?>
							<p>
								<label for="siteseo_redirections_enabled_regex_meta" id="siteseo_redirections_enabled_regex">
									<input type="checkbox" name="siteseo_redirections_enabled_regex"
										id="siteseo_redirections_enabled_regex_meta" value="yes" <?php echo checked($siteseo_redirections_enabled_regex, 'yes', false); ?>
									/>
									<?php esc_html_e('Regex?', 'siteseo'); ?>
								</label>
							</p>
							<p class="description">
								<a href="<?php echo esc_url($docs['redirects']['regex']); ?>" class="siteseo-help" target="_blank"><?php esc_html_e('Learn how to use regular expressions', 'siteseo'); ?></a>
								<span class="siteseo-help dashicons dashicons-external"></span>
							</p>
						<?php } ?>
						<p>
							<label for="siteseo_redirections_logged_status"><?php esc_html_e('Select a login status: ', 'siteseo'); ?></label>

							<select id="siteseo_redirections_logged_status" name="siteseo_redirections_logged_status">
								<option <?php echo selected('both', $siteseo_redirections_logged_status); ?>
									value="both"><?php esc_html_e('All', 'siteseo'); ?>
								</option>
								<option <?php echo selected('only_logged_in', $siteseo_redirections_logged_status); ?>
									value="only_logged_in"><?php esc_html_e('Only Logged In', 'siteseo'); ?>
								</option>
								<option <?php echo selected('only_not_logged_in', $siteseo_redirections_logged_status); ?>
									value="only_not_logged_in"><?php esc_html_e('Only Not Logged In', 'siteseo'); ?>
								</option>
							</select>
						</p>
						<p>

							<label for="siteseo_redirections_type"><?php esc_html_e('Select a redirection type: ', 'siteseo'); ?></label>

							<select id="siteseo_redirections_type" name="siteseo_redirections_type">
								<option <?php echo selected('301', $siteseo_redirections_type, false); ?>
									value="301"><?php esc_html_e('301 Moved Permanently', 'siteseo'); ?>
								</option>
								<option <?php echo selected('302', $siteseo_redirections_type, false); ?>
									value="302"><?php esc_html_e('302 Found / Moved Temporarily', 'siteseo'); ?>
								</option>
								<option <?php echo selected('307', $siteseo_redirections_type, false); ?>
									value="307"><?php esc_html_e('307 Moved Temporarily', 'siteseo'); ?>
								</option>
								<?php if ('siteseo_404' == $typenow) { ?>
									<option <?php echo selected('410', $siteseo_redirections_type, false); ?>
										value="410"><?php esc_html_e('410 Gone', 'siteseo'); ?>
									</option>
									<option <?php echo selected('451', $siteseo_redirections_type, false); ?>
										value="451"><?php esc_html_e('451 Unavailable For Legal Reasons', 'siteseo'); ?>
									</option>
								<?php } ?>
							</select>
						</p>
						<p>
							<label for="siteseo_redirections_value_meta"><?php esc_html_e('URL redirection', 'siteseo'); ?></label>
							<input id="siteseo_redirections_value_meta" type="text" name="siteseo_redirections_value"
								class="components-text-control__input js-siteseo_redirections_value_meta"
								placeholder="<?php esc_html_e('Enter your new URL in absolute (eg: https://www.example.com/)', 'siteseo'); ?>"
								aria-label="<?php esc_html_e('URL redirection', 'siteseo'); ?>"
								value="<?php echo esc_attr($siteseo_redirections_value); ?>" />

						</p>

						<script>
							document.addEventListener('DOMContentLoaded', function(){

								var cache = {};
								jQuery( ".js-siteseo_redirections_value_meta" ).autocomplete({
									source: async function( request, response ) {
										var term = request.term;
										if ( term in cache ) {
											response( cache[ term ] );
											return;
										}

										const dataResponse = await fetch("<?php echo esc_url(rest_url()); ?>siteseo/v1/search-url?url=" + term)
										const data = await dataResponse.json();

										cache[ term ] = data.map(item => {
											return {
												label: item.post_title + " (" + item.guid + ")",
												value: item.guid
											}
										});
										response( cache[term] );
									},

									minLength: 3,
								});

							})
						</script>
						<?php if ('siteseo_404' == $typenow) { ?>
						<p>
							<label for="siteseo_redirections_param_meta"><?php esc_html_e('Query parameters', 'siteseo'); ?></label>
							<select name="siteseo_redirections_param">
								<option <?php echo selected('exact_match', $siteseo_redirections_param, false); ?>
									value="exact_match"><?php esc_html_e('Exactly match all parameters', 'siteseo'); ?>
								</option>
								<option <?php echo selected('without_param', $siteseo_redirections_param, false); ?>
									value="without_param"><?php esc_html_e('Exclude all parameters', 'siteseo'); ?>
								</option>
								<option <?php echo selected('with_ignored_param', $siteseo_redirections_param, false); ?>
									value="with_ignored_param"><?php esc_html_e('Exclude all parameters and pass them to the redirection', 'siteseo'); ?>
								</option>
							</select>
						</p>
						<?php } ?>
						<p>
							<?php if ('yes' == $siteseo_redirections_enabled) {
						$status_code = ['410', '451'];
						if ('' != $siteseo_redirections_value || in_array($siteseo_redirections_type, $status_code)) {
							if ('post-new.php' == $pagenow || 'post.php' == $pagenow) {
								if ('siteseo_404' == $typenow) {

									$parse_url = wp_parse_url(get_home_url());

									$home_url = get_home_url();
									if ( ! empty($parse_url['scheme']) && ! empty($parse_url['host'])) {
										$home_url = $parse_url['scheme'] . '://' . $parse_url['host'];
									}

									$href = $home_url . '/' . get_the_title();
								} else {
									$href = get_the_permalink();
								}
							} elseif ('term.php' == $pagenow) {
								$href = get_term_link($term);
							} else {
								$href = get_the_permalink();
							} ?>
							<a href="<?php echo esc_url($href); ?>"
								id="siteseo_redirections_value_default"
								class="<?php echo esc_attr(siteseo_btn_secondary_classes()); ?>"
								target="_blank">
								<?php esc_html_e('Test your URL', 'siteseo'); ?>
							</a>
							<?php
						}
					}

					if ('siteseo_404' === $typenow) {
						$docs = siteseo_get_docs_links(); ?>
							<a href="<?php echo esc_url($docs['redirects']['enable']); ?>"
								target="_blank" class="siteseo-help siteseo-doc">
								<?php esc_html_e('Need help with your redirections? Read our guide', 'siteseo'); ?>
							</a>
							<span class="siteseo-help dashicons dashicons-external"></span>
						<?php } ?>
						</p>
					</div>
					<?php }	
					
					if(!empty($siteseo->display_ca_metaboxe)){
						echo '<div id="siteseo-ca-tabs">';
							siteseo_content_analysis($post);
						echo '</div>';
					}
					
					do_action('siteseo_seo_metabox_after_content', $typenow, $pagenow, $data_attr, $seo_tabs);
					?>
				</div>

<?php
if ('term.php' == $pagenow || 'edit-tags.php' == $pagenow) { ?>
			</div>
		</div>
	</td>
</tr>
<?php } ?>
<input type="hidden" id="seo_tabs" name="seo_tabs"
	value="<?php echo esc_html(wp_json_encode(array_keys($seo_tabs))); ?>">
