<?php

namespace SiteSEO\Services;

if ( ! defined('ABSPATH')) {
	exit;
}

class I18nUniversalMetabox
{
	public function getTranslations() {

		return [
			'generic' => [
				'pixels' => __('pixels', 'siteseo'),
				'save' => __('Save', 'siteseo'),
				'save_settings' => __("Your settings have been saved.", "siteseo"),
				'yes' => __("Yes", "siteseo"),
				'good' => __("Good", "siteseo"),
				'expand' => __("Expand", "siteseo"),
				'close' => __("Close", "siteseo"),
				'title' => __("Title", "siteseo"),
				'twitter' => __("Twitter", "siteseo"),
				'maximum_limit' => __("maximum limit", "siteseo"),
				'choose_image' => __("Choose an image", "siteseo"),
				'opening_hours_morning' => __("Open in the morning?", "siteseo"),
				'opening_hours_afternoon' => __("Open in the afternoon?", "siteseo"),
				'thumbnail' => __("Thumbnail", "siteseo"),
				'x' => __("x", "siteseo"),
				'search_tag' => __("Search a tag", "siteseo"),
				'loading_data' => __("Loading your data", "siteseo")
			],
			'services' => [
				'social_meta_tags_title' => __("Social meta tags", "siteseo"),
				'twitter' => [
					'title' => __("Twitter Title", "siteseo"),
					'description' => __("Twitter Description", "siteseo"),
					'image' => __("Twitter Image", "siteseo"),
					'missing' => __(
						'Your %s is missing!',
						"siteseo"
					),
					'we_founded' => __("We found", "siteseo"),
					'we_founded_2' => __("in your content.", "siteseo"),
					'help_twitter_title' =>  __(
						"You should not use more than one twitter:title in your post content to avoid conflicts when sharing on social networks. Twitter will take the last twitter:title tag from your source code. Below, the list:",
						"siteseo"
					),
					'help_twitter_description' => __(
						"You should not use more than one twitter:description in your post content to avoid conflicts when sharing on social networks. Twitter will take the last twitter:description tag from your source code. Below, the list:",
						"siteseo"
					),
					'we_founded_tag' => __("We found a", "siteseo"),
					'we_founded_tag_2' => __("tag in your source code.", "siteseo"),
					'tag_empty' => __(
						'Your %s tag is empty!',
						"siteseo"
					),

				],
				'open_graph' => [
					'title' => __("Open Graph"),
					'description' => __("Description", "siteseo"),
					'image' => __("Image", "siteseo"),
					'url' => __("URL", "siteseo"),
					'site_name' => __("Site Name", "siteseo"),
					'missing' => __(
						'Your Open Graph %s is missing!',
						"siteseo"
					),
					'we_founded' => __("We found", "siteseo"),
					'we_founded_2' => __("in your content.", "siteseo"),
					'help_og_title' => __(
						"You should not use more than one og:title in your post content to avoid conflicts when sharing on social networks. Facebook will take the last og:title tag from your source code. Below, the list:",
						"siteseo"
					),
					'help_og_description' => __(
						"You should not use more than one og:description in your post content to avoid conflicts when sharing on social networks. Facebook will take the last og:description tag from your source code. Below, the list:",
						"siteseo"
					),
					'help_og_url' => __(
						"You should not use more than one og:url in your post content to avoid conflicts when sharing on social networks. Facebook will take the last og:url tag from your source code. Below, the list:",
						"siteseo"
					),
					'help_og_site_name' => __(
						"You should not use more than one og:site_name in your post content to avoid conflicts when sharing on social networks. Facebook will take the last og:site_name tag from your source code. Below, the list:",
						"siteseo"
					),
					'we_founded_tag' => __("We found an Open Graph", "siteseo"),
					'we_founded_tag_2' => __("tag in your source code.", "siteseo"),
					'tag_empty' => __(
						'Your Open Graph %s tag is empty!',
						"siteseo"
					)
				],
				'content_analysis' => [
					'meta_title' => [
						'title' => __("Meta title", "siteseo"),
						'no_meta_title' => __(
							"No custom title is set for this post. If the global meta title suits you, you can ignore this recommendation.",
							"siteseo"
						),
						'meta_title_found' => __(
							"Target keywords were found in the Meta Title.",
							"siteseo"
						),
						'meta_title_found_in' => __(
							'%s was found %s times.',
							"siteseo"
						),
						'empty_matches' => __(
							"None of your target keywords were found in the Meta Title.",
							"siteseo"
						),
						'too_long' => __("Your custom title is too long.", "siteseo"),
						'length' => __("The length of your title is correct.", "siteseo")

					],
					'meta_description' => [
						'title' => __("Meta description", "siteseo"),
						'no_meta_description' => __(
							"No custom meta description is set for this post. If the global meta description suits you, you can ignore this recommendation.",
							"siteseo"
						),
						'meta_description_found' => __(
							"Target keywords were found in the Meta description.",
							"siteseo"
						),
						'meta_description_found_in' => __(
							'%s was found %s times.',
							"siteseo"
						),
						'no_meta_description_found' => __(
							"None of your target keywords were found in the Meta description.",
							"siteseo"
						),
						'too_long' => __(
							"You custom meta description is too long.",
							"siteseo"
						),
						'length' => __(
							"The length of your meta description is correct",
							"siteseo"
						),
					],
					'meta_robots' => [
						'title' => __("Meta robots", "siteseo"),
						'empty_meta_google' => __(
							"is off. Google will probably display a sitelinks searchbox in search results.",
							"siteseo"
						),
						'empty_metas' => __(
							"We found no meta robots on this page. It means, your page is index,follow. Search engines will index it, and follow links. ",
							"siteseo"
						),
						'founded_multiple_metas' => __(
							'We found %s meta robots in your page. There is probably something wrong with your theme!',
							"siteseo"
						),
						'noindex_on' => __(
							"is on! Search engines can't index this page.",
							"siteseo"
						),
						'noindex_off' => __(
							"is off. Search engines will index this page.",
							"siteseo"
						),
						'nofollow_on' => __(
							"is on! Search engines can't follow your links on this page.",
							"siteseo"
						),
						'nofollow_off' => __(
							"is off. Search engines will follow links on this page.",
							"siteseo"
						),
						'noimageindex_on' => __(
							"is on! Google will not index your images on this page (but if someone makes a direct link to one of your image in this page, it will be indexed).",
							"siteseo"
						),
						'noimageindex_off' => __(
							"is off. Google will index the images on this page.",
							"siteseo"
						),
						'noarchive_on' => __(
							"is on! Search engines will not cache your page.",
							"siteseo"
						),
						'noarchive_off' => __(
							"is off. Search engines will probably cache your page.",
							"siteseo"
						),
						'nosnipet_on' => __(
							"is on! Search engines will not display a snippet of this page in search results.",
							"siteseo"
						),
						'nosnipet_off' => __(
							"is off. Search engines will display a snippet of this page in search results.",
							"siteseo"
						),
						'nositelinkssearchbox_on' => __(
							"is on! Google will not display a sitelinks searchbox in search results.",
							"siteseo"
						),
						'nositelinkssearchbox_off' => __(
							"is off. Google will probably display a sitelinks searchbox in search results.",
							"siteseo"
						)
					],
					'outbound_links' => [
						'title' => __("Outbound Links", "siteseo"),
						'description' => __(
							'Internet is built on the principle of hyperlink. It is therefore perfectly normal to make links between different websites. However, avoid making links to low quality sites, SPAM... If you are not sure about the quality of a site, add the attribute "nofollow" to your link.'
						),
						'no_outbound_links' => __(
							"This page doesn't have any outbound links.",
							"siteseo"
						),
						'outbound_links_count' => __(
							'We found %s outbound links in your page. Below, the list:',
							"siteseo"
						),
					],
					'words_counter' => [
						'title' => __("Words counter", "siteseo"),
						'no_content' => __("No content? Add a few more paragraphs!", "siteseo"),
						'description' => __(
							"Words counter is not a direct ranking factor. But, your content must be as qualitative as possible, with relevant and unique information. To fulfill these conditions, your article requires a minimum of paragraphs, so words.",
							"siteseo"
						),
						'unique_words' => __("unique words found.", "siteseo"),
						'good' => __(
							"Your content is composed of more than 300 words, which is the minimum for a post.",
							"siteseo"
						),
						'bad' => __(
							"Your content is too short. Add a few more paragraphs!",
							"siteseo"
						),
						'counter_words' => __("words found.", "siteseo"),
					],
					'old_post' => [
						'bad' => __("This post is a little old!", "siteseo"),
						'good' => __(
							"The last modified date of this article is less than 1 year. Cool!",
							"siteseo"
						),
						'description' => __(
							"Search engines love fresh content. Update regularly your articles without entirely rewriting your content and give them a boost in search rankings. SiteSEO takes care of the technical part.",
							"siteseo"
						),
						'title' => __("Last modified date", "siteseo"),
					],
					'headings' => [
						'head' => __(
							'Target keywords were found in Heading %s (H%s).',
							"siteseo"
						),
						'heading_hn' => __("Heading H%s", "siteseo"),
						'heading' => __("Heading", "siteseo"),
						'no_heading' => __(
							'No custom title is set for this post. If the global meta title suits you, you can ignore this recommendation.',
							"siteseo"
						),
						'no_heading_detail' =>__(
							'No Heading %s (H%s) found in your content. This is required for both SEO and Accessibility!',
							"siteseo"
						),
						'no_target_keywords_detail' => __(
							'None of your target keywords were found in Heading %s (H%s).',
							"siteseo"
						),
						'match' => __(
							'%s was found %s times.',
							"siteseo"
						),
						'count_h1' => __(
							'We found %s Heading 1 (H1) in your content.',
							"siteseo"
						),
						'count_h1_detail' => __(
							"You should not use more than one H1 heading in your post content. The rule is simple: only one H1 for each web page. It is better for both SEO and accessibility. Below, the list:",
							"siteseo"
						),
						'below_h1' => __("Below the list:", "siteseo"),
						'title' => __("Headings", "siteseo"),
					],
					'images' => [
						'bad' => __(
							"We could not find any image in your content. Content with media is a plus for your SEO.",
							"siteseo"
						),
						'good' => __(
							"All alternative tags are filled in. Good work!",
							"siteseo"
						),
						'no_alternative_text' => __(
							"No alternative text found for these images. Alt tags are important for both SEO and accessibility. Edit your images using the media library or your favorite page builder and fill in alternative text fields.",
							"siteseo"
						),
						'description_no_alternative_text' => __(
							"Note that we scan all your source code, it means, some missing alternative texts of images might be located in your header, sidebar or footer.",
							"siteseo"
						),
						'title' => __("Alternative texts of images", "siteseo"),
					],
					'internal_links' => [
						'description' => __(
							"Internal links are important for SEO and user experience. Always try to link your content together, with quality link anchors."
						),
						'no_internal_links' => __(
							"This page doesn't have any internal links from other content. Links from archive pages are not considered internal links due to lack of context.",
							"siteseo"
						),
						'internal_links_count' => __(
							'We found %s internal links in your page. Below, the list:',
							"siteseo"
						),
						'title' => __("Internal Links", "siteseo")
					],
					'kws_density' => [
						'no_match' => __(
							"We were unable to calculate the density of your keywords. You probably haven‘t added any content or your target keywords were not find in your post content.",
							"siteseo"
						),
						'match' => __(
							'%s was found %s times in your content, a keyword density of %s',
							"siteseo"
						),
						'description' => __(
							'Learn more about <a href="https://www.youtube.com/watch?v=Rk4qgQdp2UA" target="_blank">keywords stuffing</a>.',
							"siteseo"
						),
						'title' => __("Keywords density", "siteseo")
					],
					'kws_permalink' => [
						'no_apply' => __(
							"This is your homepage. This check doesn't apply here because there is no slug.",
							"siteseo"
						),
						'bad' => __(
							"You should add one of your target keyword in your permalink.",
							"siteseo"
						),
						'good' => __(
							"Cool, one of your target keyword is used in your permalink.",
							"siteseo"
						),
						'description' => __(
							'Learn more about <a href="https://www.youtube.com/watch?v=Rk4qgQdp2UA" target="_blank">keywords stuffing</a>.',
							"siteseo"
						),
						'title' =>__("Keywords in permalink", "siteseo")
					],
					'no_follow_links' => [
						'founded' => __(
							'We found %s links with nofollow attribute in your page. Do not overuse nofollow attribute in links. Below, the list:',
							"siteseo"
						),
						'no_founded' => __(
							"This page doesn't have any nofollow links.",
							"siteseo"
						),
						'title' =>__("NoFollow Links", "siteseo")
					]

				],
				'canonical_url' => [
					'title' => __("Canonical URL", "siteseo"),
					'head' => __(
						"A canonical URL is required by search engines to handle duplicate content."
					,'siteseo'),
					'no_canonical' => __(
						"This page doesn't have any canonical URL because your post is set to <strong>noindex</strong>. This is normal.",
						"siteseo"
					),
					'no_canonical_no_index' => __(
						"This page doesn't have any canonical URL.",
						"siteseo"
					),
					'canonicals_found' => __('We found %d canonical URL in your source code. Below, the list:', 'siteseo'),
					'canonicals_found_plural' => __('We found %d canonicals URLs in your source code. Below, the list:', 'siteseo'),
					'multiple_canonicals' => __(
						"You must fix this. Canonical URL duplication is bad for SEO.",
						"siteseo"
					),
					'duplicated' => __(
						"duplicated schema - x",
						"siteseo"
					),

				],
				'schemas' => [
					'title' => __("Structured Data Types (schemas)", "siteseo"),
					'no_schema' => __(
						"No schemas found in the source code of this page.",
						"siteseo"
					),
					'head' => __(
						"We found these schemas in the source code of this page:",
						"siteseo"
					),

				]
			],
			'constants' => [
				'tabs' => [
					'title_description_meta' => __("Titles & Metas", "siteseo"),
					'content_analysis' => __("Content Analysis", "siteseo"),
					'schemas' => __("Schemas", "siteseo")
				],
				'sub_tabs' => [
					'title_settings' => __("Title settings", "siteseo"),
					'social' => __("Social", "siteseo"),
					'advanced' => __("Advanced", "siteseo"),
					'redirection' => __("Redirection", "siteseo"),
					'google_news' => __("Google News", "siteseo"),
					'video_sitemap' => __("Video Sitemap", "siteseo"),
					'overview' => __("Overview", "siteseo"),
					'inspect_url' => __("Inspect with Google", "siteseo"),
					'internal_linking' => __("Internal Linking", "siteseo"),
					'schema_manual' => __("Manual", "siteseo"),
				]
			],
			'seo_bar' => [
				'title' => __('SiteSEO', 'siteseo'),
			],
			'forms' => [
				'maximum_limit' => __('maximum limit', 'siteseo'),
				'maximum_recommended_limit' => __('maximum recommended limit', 'siteseo'),
				'meta_title_description' => [
					'title' => __('Title', 'siteseo'),
					'tooltip_title' => __('Meta Title', 'siteseo'),
					'tooltip_description' => __("Titles are critical to give users a quick insight into the content of a result and why it’s relevant to their query. It's often the primary piece of information used to decide which result to click on, so it's important to use high-quality titles on your web pages.", 'siteseo'),
					'placeholder_title' => __('Enter your title', 'siteseo'),
					'meta_description' => __('Meta description', 'siteseo'),
					'tooltip_description_1' => __( "A meta description tag should generally inform and interest users with a short, relevant summary of what a particular page is about.", 'siteseo'),
					'tooltip_description_2' => __("They are like a pitch that convince the user that the page is exactly what they're looking for.", 'siteseo'),
					'tooltip_description_3' => __("There's no limit on how long a meta description can be, but the search result snippets are truncated as needed, typically to fit the device width.", 'siteseo'),
					'placeholder_description' => __('Enter your description', 'siteseo'),
					'generate_ai' => __('Generate meta with AI', 'siteseo')
				],
				'repeater_how_to' => [
					'title_step' => __(
						"The title of the step (required)",
						"siteseo"
					),
					'description_step' => __(
						"The text of your step (required)",
						"siteseo"
					),
					'remove_step' => __("Remove step", "siteseo"),
					'add_step' => __("Add step", "siteseo")
				],
				'repeater_negative_notes_review' => [
					'title' => __(
						"Your negative statement (required)",
						"siteseo"
					),
					'remove' => __("Remove note", "siteseo"),
					'add' => __("Add a statement", "siteseo"),
				],
				'repeater_positive_notes_review' => [
					'title' => __(
						"Your positive statement (required)",
						"siteseo"
					),
					'remove' => __("Remove note", "siteseo"),
					'add' => __("Add a statement", "siteseo"),
				],
			],
			'google_preview' => [
				'title'  => __('Google Snippet Preview', 'siteseo'),
				'tooltip_title' => __('Snippet Preview', 'siteseo'),
				'tooltip_description_1' => __('The Google preview is a simulation.', 'siteseo'),
				'tooltip_description_2' => __('There is no reliable preview because it depends on the screen resolution, the device used, the expression sought, and Google.', 'siteseo'),
				'tooltip_description_3' => __('There is not one snippet for one URL but several.', 'siteseo'),
				'tooltip_description_4' => __('All the data in this overview comes directly from your source code.', 'siteseo'),
				'tooltip_description_5' => __('This is what the crawlers will see.', 'siteseo'),
				'description' => __(
					"This is what your page will look like in Google search results. You have to publish your post to get the Google Snippet Preview. Note that Google may optionally display an image of your article.",
					"siteseo"
				),
				'mobile_title' => __("Mobile Preview", "siteseo")
			],
			'components' => [
				'repeated_faq' => [
					'empty_question' => __(
						"Empty Question",
						"siteseo"
					),
					'empty_answer' => __(
						"Empty Answer",
						"siteseo"
					),
					'question' => __(
						"Question (required)",
						"siteseo"
					),
					'answer' => __(
						"Answer (required)",
						"siteseo"
					),
					'remove' => __("Remove question", "siteseo"),
					'add' => __("Add question", "siteseo")
				],
			],
			'layouts' => [
				'meta_robot' => [
					'title' => __(
						"You cannot uncheck a parameter? This is normal, and it's most likely defined in the <a href='%s'>global settings of the plugin.</a>",
						"siteseo"
					),
					'robots_index_description' => __(
						"Do not display this page in search engine results / Sitemaps",
						"siteseo"
					),
					'robots_index_tooltip_title' => __('"noindex" robots meta tag', 'siteseo'),
					'robots_index_tooltip_description_1' => __(
						'By checking this option, you will add a meta robots tag with the value "noindex".',
						'siteseo'
					),
					'robots_index_tooltip_description_2' => __(
						'Search engines will not index this URL in the search results.',
						'siteseo'
					),
					'robots_follow_description' => __("Do not follow links for this page", "siteseo"),
					'robots_follow_tooltip_title' => __('"nofollow" robots meta tag', 'siteseo'),
					'robots_follow_tooltip_description_1' => __(
						'By checking this option, you will add a meta robots tag with the value "nofollow".',
						'siteseo'
					),
					'robots_follow_tooltip_description_2' => __(
						'Search engines will not follow links from this URL.',
						'siteseo'
					),
					'robots_archive_description' => __(
						"Do not display a 'Cached' link in the Google search results",
						"siteseo"
					),
					'robots_archive_tooltip_title' => __('"noarchive" robots meta tag', 'siteseo'),
					'robots_archive_tooltip_description_1' => __(
						'By checking this option, you will add a meta robots tag with the value "noarchive".',
						'siteseo'
					),
					'robots_snippet_description' =>__(
						"Do not display a description in search results for this page",
						"siteseo"
					),
					'robots_snippet_tooltip_title' => __('"nosnippet" robots meta tag', 'siteseo'),
					'robots_snippet_tooltip_description_1' => __(
						'By checking this option, you will add a meta robots tag with the value "nosnippet".',
						'siteseo'
					),
					'robots_imageindex_description' => __("Do not index images for this page", "siteseo"),
					'robots_imageindex_tooltip_title' => __('"noimageindex" robots meta tag', 'siteseo'),
					'robots_imageindex_tooltip_description_1' => __(
						'By checking this option, you will add a meta robots tag with the value "noimageindex".',
						'siteseo'
					),
					'robots_imageindex_tooltip_description_2' => __(
						'Note that your images can always be indexed if they are linked from other pages.',
						'siteseo'
					)
				],
				'inspect_url' => [
					'description' => __(
						"Inspect the current post URL with Google Search Console and get informations about your indexing, crawling, rich snippets and more.",
						"siteseo"
					),
					'verdict_unspecified' => [
						'title' => __("Unknown verdict", "siteseo"),
						'description' =>__(
							"The URL has been indexed, can appear in Google Search results, and no problems were found with any enhancements found in the page (structured data, linked AMP pages, and so on).",
							"siteseo"
						)
					],
					'pass' => [
						'title' => __("URL is on Google", "siteseo"),
						'description' => __(
							"The URL has been indexed, can appear in Google Search results, and no problems were found with any enhancements found in the page (structured data, linked AMP pages, and so on).",
							"siteseo"
						)
					],
					'partial' => [
						'title' => __("URL is on Google, but has issues", "siteseo"),
						'description' => __(
							"The URL has been indexed and can appear in Google Search results, but there are some problems that might prevent it from appearing with the enhancements that you applied to the page. This might mean a problem with an associated AMP page, or malformed structured data for a rich result (such as a recipe or job posting) on the page.",
							"siteseo"
						)
					],
					'fail' => [
						'title' => __(
							"URL is not on Google: Indexing errors",
							"siteseo"
						),
						'description' => __(
							"There was at least one critical error that prevented the URL from being indexed, and it cannot appear in Google Search until those issues are fixed.",
							"siteseo"
						)
					],
					'neutral' => [
						'title' => __("URL is not on Google", "siteseo"),
						'description' => __(
							"This URL won‘t appear in Google Search results, but we think that was your intention. Common reasons include that the page is password-protected or robots.txt protected, or blocked by a noindex directive.",
							"siteseo"
						)
					],
					'indexing_state_unspecified' => __("Unknown indexing status.", "siteseo"),
					'indexing_allowed' => __("Indexing allowed.", "siteseo"),
					'blocked_by_meta_tag' => __(
						"Indexing not allowed, 'noindex' detected in 'robots' meta tag.",
						"siteseo"
					),
					'blocked_by_http_header' => __(
						"Indexing not allowed, 'noindex' detected in 'X-Robots-Tag' http header.",
						"siteseo"
					),
					'blocked_by_robots_txt' => __(
						"Indexing not allowed, blocked to Googlebot with a robots.txt file.",
						"siteseo"
					),
					'page_fetch_state_unspecified' => __("Unknown fetch state.", "siteseo"),
					'successful' => __("Successful fetch.", "siteseo"),
					'soft_404' => __("Soft 404.", "siteseo"),
					'blocked_robots_txt' => __("Blocked by robots.txt.", "siteseo"),
					'not_found' => __("Not found (404).", "siteseo"),
					'access_denied' => __(
						"Blocked due to unauthorized request (401).",
						"siteseo"
					),
					'server_error' => __("Server error (5xx).", "siteseo"),
					'redirect_error' => __("Redirection error.", "siteseo"),
					'access_forbidden' => __("Blocked due to access forbidden (403).", "siteseo"),
					'blocked_4xx' => __(
						"Blocked due to other 4xx issue (not 403, 404).",
						"siteseo"
					),
					'internal_crawl_error' => __("Internal error.", "siteseo"),
					'invalid_url' => __("Invalid URL.", "siteseo"),
					'crawling_user_agent_unspecified' => __("Unknown user agent.", "siteseo"),
					'desktop' => __("Googlebot desktop", "siteseo"),
					'mobile' => __("Googlebot smartphone", "siteseo"),
					'robots_txt_state_unspecified' => __(
						"Unknown robots.txt state, typically because the page wasn‘t fetched or found, or because robots.txt itself couldn‘t be reached.",
						"siteseo"
					),
					'disallowed' => __("Crawl blocked by robots.txt.", "siteseo"),
					'mobile_verdict_unspecified_title' => __("No data available", "siteseo"),
					'mobile_verdict_unspecified_description' => __(
						"For some reason we couldn't retrieve the page or test its mobile-friendliness. Please wait a bit and try again.",
						"siteseo"
					),
					'mobile_pass_title' => __("Page is mobile friendly", "siteseo"),
					'mobile_pass_description' => __(
						"The page should probably work well on a mobile device.",
						"siteseo"
					),
					'mobile_fail_title' => __("Page is not mobile friendly", "siteseo"),
					'mobile_fail_description' => __(
						"The page won‘t work well on a mobile device because of a few issues.",
						"siteseo"
					),
					'rich_snippets_verdict_unspecified'=> __("No data available", "siteseo"),
					'rich_snippets_pass' => __("Your Rich Snippets are valid", "siteseo"),
					'rich_snippets_fail' => __("Your Rich Snippets are not valid", "siteseo"),
					'discovery' => __("Discovery", "siteseo"),
					'discovery_sitemap' => __("Sitemaps", "siteseo"),
					'discovery_referring_urls' => __("Referring page", "siteseo"),
					'crawl' => __("Crawl", "siteseo"),
					'crawl_last_crawl_time' => __("Last crawl", "siteseo"),
					'crawl_crawled_as' => __("Crawled as", "siteseo"),
					'crawl_allowed' => __("Crawl allowed?", "siteseo"),
					'crawl_page_fetch' => __("Page fetch", "siteseo"),
					'crawl_indexing' => __("Indexing allowed?", "siteseo"),
					'indexing_title' => __("Indexing", "siteseo"),
					'indexing_user_canonical' => __("User-declared canonical", "siteseo"),
					'indexing_google_canonical' => __("Google-selected canonical", "siteseo"),
					'enhancements_title' => __("Enhancements", "siteseo"),
					'enhancements_mobile' => __("Mobile Usability", "siteseo"),
					'enhancements_rich_snippets' => __("Rich Snippets detected", "siteseo"),
					'btn_inspect_url' => __("Inspect URL with Google", "siteseo"),
					'notice_empty_api_key' => __(
						"No data found, click Inspect URL button above.",
						"siteseo"
					),
					'btn_full_report' => __("View Full Report", "siteseo")
				],
				'video_sitemap' => [
					'btn_remove_video' => __(
						"Remove video",
						"siteseo"
					),
					'btn_add_video' => __("Add video", "siteseo")
				],
				'internal_linking' => [
					'description_1' => __(
						"Internal links are important for SEO and user experience. Always try to link your content together, with quality link anchors.",
						"siteseo"
					),
					'description_2' => __(
						"Here is a list of articles related to your content, sorted by relevance, that you should link to.",
						"siteseo"
					),
					'no_suggestions' =>  __("No suggestion of internal links.", "siteseo"),
					'copied' => __(
						"Link copied in the clipboard",
						"siteseo"
					),
					'copy_link' => __("Copy %s link", "siteseo"),
					'open_link' => __(
						"Open this link in a new window",
						"siteseo"
					),
					'edit_link' => __(
						"Edit this link in a new window",
						"siteseo"
					),
					'edit_link_aria' => __("Edit %s link", "siteseo")
				],
				'content_analysis' => [
					'description' => __(
						"Enter a few keywords for analysis to help you write optimized content.",
						"siteseo"
					),
					'description_2' => __(
						"Writing content for your users is the most important thing! If it doesn‘t feel natural, your visitors will leave your site, Google will know it and your ranking will be affected.",
						"siteseo"
					),
					'title_severity' => __('Degree of severity: %s', 'wp- siteseo'),
					'target_keywords' => __("Target keywords", "siteseo"),
					'target_keywords_tooltip_description' => __(
						"Separate target keywords with commas. Do not use spaces after the commas, unless you want to include them",
						"siteseo"
					),
					'target_keywords_multiple_usage' => __(
						'You should avoid using multiple times the same keyword for different pages. Try to consolidate your content into one single page.',
						"siteseo"
					),
					'target_keywords_placeholder' => __(
						"Enter your target keywords",
						"siteseo"
					),
					'btn_refresh_analysis' => __("Refresh analysis", "siteseo"),
					'help_target_keywords' => __(
						"To get the most accurate analysis, save your post first. We analyze all of your source code as a search engine would.",
						"siteseo"
					),
					'google_suggestions' => __("Google suggestions", "siteseo"),
					'google_suggestions_tooltip_description' => __(
						"Enter a keyword, or a phrase, to find the top 10 Google suggestions instantly. This is useful if you want to work with the long tail technique.",
						"siteseo"
					),
					'google_suggestions_placeholder' => __(
						"Get suggestions from Google",
						"siteseo"
					),
					'get_suggestions' => __("Get suggestions!", "siteseo"),
					'should_be_improved' =>  __("Should be improved", "siteseo"),
					'keyword_singular' => __("The keyword:", "siteseo"),
					'keyword_plural' => __("These keywords:", "siteseo"),
					'already_used_singular' => __("is already used %d time", "siteseo"),
					'already_used_plural' => __("is already used %d times", "siteseo"),
				],
				'schemas_manual' => [
					'description' => __('It is recommended to enter as many properties as possible to maximize the chances of getting a rich snippet in Google search results.', 'siteseo'),
					'remove' => __("Delete schema", "siteseo"),
					'add' => __("Add a schema", "siteseo"),
				],
				'social' => [
					'title' => __(
						"LinkedIn, Instagram, WhatsApp and Pinterest use the same social metadata as Facebook. Twitter does the same if no Twitter cards tags are defined below.",
						"siteseo"
					),
					'facebook_title' => __(
						"Ask Facebook to update its cache",
						"siteseo"
					),
					'twitter_title' => __(
						"Preview your Twitter card using the official validator",
						"siteseo"
					),
				],
				'social_preview' => [
					"facebook" => [
						"title" => __("Facebook Preview", "siteseo"),
						"description" => __(
							"This is what your post will look like in Facebook. You have to publish your post to get the Facebook Preview.",
							"siteseo"
						),
						"ratio" => __("Your image ratio is:", "siteseo"),
						"ratio_info" => __("The closer to 1.91 the better.", "siteseo"),
						'img_filesize' => __('Your filesize is: ', 'siteseo'),
						'filesize_is_too_large' => __('This is superior to 300KB. WhatsApp will not use your image.', 'siteseo'),
						"min_size" => __(
							"Minimun size for Facebook is <strong>200x200px</strong>. Please choose another image.",
							"siteseo"
						),
						"file_support" =>__(
							"File type not supported by Facebook. Please choose another image.",
							"siteseo"
						),
						"error_image" => __(
							"File error. Please choose another image.",
							"siteseo"
						),
						"choose_image" =>__("Please choose an image", "siteseo"),
					],
					"twitter" => [
						"title" => __("Twitter Preview", "siteseo"),
						"description" => __(
							"This is what your post will look like in Twitter. You have to publish your post to get the Twitter Preview.",
							"siteseo"
						),
						"ratio" => __("Your image ratio is:", "siteseo"),
						"ratio_info" =>__(
							"The closer to 1 the better (with large card, 2 is better).",
							"siteseo"
						),
						"min_size" => __(
							"Minimun size for Twitter is <strong>144x144px</strong>. Please choose another image.",
							"siteseo"
						),
						"file_support" => __(
							"File type not supported by Twitter. Please choose another image.",
							"siteseo"
						),
						"error_image" => __(
							"File error. Please choose another image.",
							"siteseo"
						),
						"choose_image" =>__("Please choose an image", "siteseo")

					]
				],
				"advanced" => [
					'title' => __("Meta robots settings", "siteseo"),
					'tooltip_canonical' => __(
						"Canonical URL",
						"siteseo"
					),
					'tooltip_canonical_description' => __(
						"A canonical URL is the URL of the page that Google thinks is most representative from a set of duplicate pages on your site.",
						"siteseo"
					),
					'tooltip_canonical_description_2' => __(
						"For example, if you have URLs for the same page (for example: example.com?dress=1234 and example.com/dresses/1234), Google chooses one as canonical.",
						"siteseo"
					),
					'tooltip_canonical_description_3' => __(
						"Note that the pages do not need to be absolutely identical; minor changes in sorting or filtering of list pages do not make the page unique (for example, sorting by price or filtering by item color). The canonical can be in a different domain than a duplicate.",
						"siteseo"
					)
				]
			]
		];

	}
}

