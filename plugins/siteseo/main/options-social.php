<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Social
// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

function siteseo_social_accounts_jsonld_hook() {
	$siteseo_comma_array = [];
	
	$SocialOption = siteseo_get_service('SocialOption');
	$knowledgeTypeOption = $SocialOption->getSocialKnowledgeType();

	//If not enable (=none)
	if ('none' === $knowledgeTypeOption) {
		return;
	}

	if ('' != $SocialOption->getSocialAccountsFacebook()) {
		$getSocialAccountsFacebook = wp_json_encode($SocialOption->getSocialAccountsFacebook());
		array_push($siteseo_comma_array, $getSocialAccountsFacebook);
	}
	if ('' != $SocialOption->getSocialAccountsTwitter()) {
		$getSocialAccountsTwitter = wp_json_encode('https://twitter.com/' . $SocialOption->getSocialAccountsTwitter());
		array_push($siteseo_comma_array, $getSocialAccountsTwitter);
	}
	if ('' != $SocialOption->getSocialAccountsPinterest()) {
		$getSocialAccountsPinterest = wp_json_encode($SocialOption->getSocialAccountsPinterest());
		array_push($siteseo_comma_array, $getSocialAccountsPinterest);
	}
	if ('' != $SocialOption->getSocialAccountsInstagram()) {
		$getSocialAccountsInstagram = wp_json_encode($SocialOption->getSocialAccountsInstagram());
		array_push($siteseo_comma_array, $getSocialAccountsInstagram);
	}
	if ('' != $SocialOption->getSocialAccountsYoutube()) {
		$getSocialAccountsYoutube = wp_json_encode($SocialOption->getSocialAccountsYoutube());
		array_push($siteseo_comma_array, $getSocialAccountsYoutube);
	}
	if ('' != $SocialOption->getSocialAccountsLinkedin()) {
		$getSocialAccountsLinkedin = wp_json_encode($SocialOption->getSocialAccountsLinkedin());
		array_push($siteseo_comma_array, $getSocialAccountsLinkedin);
	}
	if ('' != $knowledgeTypeOption) {
		$siteseo_social_knowledge_type_option = wp_json_encode($knowledgeTypeOption);
	} else {
		$siteseo_social_knowledge_type_option = wp_json_encode('Organization');
	}
	if ('' != $SocialOption->getSocialKnowledgeName() && 'none' != $knowledgeTypeOption) {
		$siteseo_social_knowledge_name_option = wp_json_encode($SocialOption->getSocialKnowledgeName());
	} elseif ('none' != $knowledgeTypeOption) {
		$siteseo_social_knowledge_name_option = wp_json_encode(get_bloginfo('name'));
	}
	if ('' != $SocialOption->getSocialKnowledgeImage() && 'Organization' == $knowledgeTypeOption) {
		$siteseo_social_knowledge_img_option = wp_json_encode($SocialOption->getSocialKnowledgeImage());
	}
	if ('' != $SocialOption->getSocialKnowledgePhone()) {
		$getSocialKnowledgePhone = wp_json_encode($SocialOption->getSocialKnowledgePhone());
	}
	if ('' != $SocialOption->getSocialKnowledgeContactType()) {
		$getSocialKnowledgeContactType = wp_json_encode($SocialOption->getSocialKnowledgeContactType());
	}
	if ('' != $SocialOption->getSocialKnowledgeContactOption()) {
		$getSocialKnowledgeContactOption = wp_json_encode($SocialOption->getSocialKnowledgeContactOption());
	}

	$html = '<script type="application/ld+json">';
	$html .= '{"@context" : "' . siteseo_check_ssl() . 'schema.org","@type" : ' . $siteseo_social_knowledge_type_option . ',';
	if ('' != $SocialOption->getSocialKnowledgeImage() && 'Organization' == $knowledgeTypeOption) {
		$html .= '"logo": ' . $siteseo_social_knowledge_img_option . ',';
	}
	$html .= '"name" : ' . $siteseo_social_knowledge_name_option . ',"url" : ' . wp_json_encode(get_home_url());

	if ('Organization' == $knowledgeTypeOption
		&& '' != $SocialOption->getSocialKnowledgePhone()
		&& '' != $SocialOption->getSocialKnowledgeContactType()
		) {
		if ($getSocialKnowledgePhone && $getSocialKnowledgeContactType) {
			$html .= ',"contactPoint": [{
				"@type": "ContactPoint",
				"telephone": ' . $getSocialKnowledgePhone . ',';
			if ('' != $getSocialKnowledgeContactOption && 'None' != $getSocialKnowledgeContactOption) {
				$html .= '"contactOption": ' . $getSocialKnowledgeContactOption . ',';
			}
			$html .= '"contactType": ' . $getSocialKnowledgeContactType . '
			}]';
		}
	}

	if ('' != $SocialOption->getSocialAccountsFacebook() || '' != $SocialOption->getSocialAccountsTwitter() || '' != $SocialOption->getSocialAccountsPinterest() || '' != $SocialOption->getSocialAccountsInstagram() || '' != $SocialOption->getSocialAccountsYoutube() || '' != $SocialOption->getSocialAccountsLinkedin()) {
		$html .= ',"sameAs" : [';
		$siteseo_comma_count = count($siteseo_comma_array);
		for ($i = 0; $i < $siteseo_comma_count; ++$i) {
			$html .= $siteseo_comma_array[$i];
			if ($i < ($siteseo_comma_count - 1)) {
				$html .= ', ';
			}
		}
		$html .= ']';
	}
	$html .= '}';
	$html .= '</script>';
	$html .= "\n";

	$html = apply_filters('siteseo_schemas_organization_html', $html);
	echo wp_kses_post($html);
}
if (apply_filters('siteseo_old_social_accounts_jsonld_hook', ! function_exists('siteseo_get_service'))) {
	add_action('wp_head', 'siteseo_social_accounts_jsonld_hook', 1);
}

//Website Schema.org in JSON-LD - Sitelinks
if('1' == siteseo_get_service('TitleOption')->geNoSiteLinksSearchBox()){
	//do not display searchbox schema
} else {
	function siteseo_social_website_option() {
		
		$target = get_home_url() . '/?s={search_term_string}';
		$site_tile = !empty(siteseo_get_service('TitleOption')->getHomeSiteTitle()) ? siteseo_get_service('TitleOption')->getHomeSiteTitle() : get_bloginfo('name');
		$alt_site_title = !empty(siteseo_get_service('TitleOption')->getHomeSiteTitleAlt()) ? siteseo_get_service('TitleOption')->getHomeSiteTitleAlt() : get_bloginfo('name');
		$site_desc = !empty(siteseo_get_service('TitleOption')->getHomeDescriptionTitle()) ? siteseo_get_service('TitleOption')->getHomeDescriptionTitle() : get_bloginfo('description');

		$variables = null;
		$variables = apply_filters('siteseo_dyn_variables_fn', $variables);

		$siteseo_titles_template_variables_array 	= $variables['siteseo_titles_template_variables_array'];
		$siteseo_titles_template_replace_array 	= $variables['siteseo_titles_template_replace_array'];

		$site_tile = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $site_tile);
		$alt_site_title = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $alt_site_title);
		$site_desc = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $site_desc);

		$website_schema = [
			'@context' => siteseo_check_ssl() . 'schema.org',
			'@type' => 'WebSite',
			'name' => esc_html($site_tile),
			'alternateName' => esc_html($alt_site_title),
			'description' => esc_html($site_desc),
			'url' => get_home_url(),
			'potentialAction' => [
				'@type' => 'SearchAction',
				'target' => [
					'@type' => 'EntryPoint',
					'urlTemplate' => esc_js($target)
				],
				'query-input' => 'required name=search_term_string'
			],
		];

		$website_schema = apply_filters( 'siteseo_schemas_website', $website_schema );

		$jsonld = '<script type="application/ld+json">';
		$jsonld .= wp_json_encode($website_schema);
		$jsonld .= '</script>';
		$jsonld .= "\n";


		echo wp_kses($jsonld, ['script' => ['type' => true]]);
	}
	if (is_home() || is_front_page()) {
		add_action('wp_head', 'siteseo_social_website_option', 1);
	}
}

//OG URL
function siteseo_social_facebook_og_url_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg()) {
		global $wp;

		$current_url = user_trailingslashit(home_url(add_query_arg([], $wp->request)));

		if (is_search()) {
			$siteseo_social_og_url = '<meta property="og:url" content="' . htmlspecialchars(urldecode(get_home_url() . '/search/' . get_search_query())) . '" />';
		} else {
			$siteseo_social_og_url = '<meta property="og:url" content="' . htmlspecialchars(urldecode($current_url), ENT_COMPAT, 'UTF-8') . '" />';
		}

		//Hook on post OG URL - 'siteseo_social_og_url'
		if (has_filter('siteseo_social_og_url')) {
			$siteseo_social_og_url = apply_filters('siteseo_social_og_url', $siteseo_social_og_url);
		}

		if ( ! is_404()) {
			echo wp_kses($siteseo_social_og_url, ['meta' => ['property' => true, 'content' => true]]) . "\n";
		}
	}
}
add_action('wp_head', 'siteseo_social_facebook_og_url_hook', 1);

//OG Site Name
function siteseo_social_facebook_og_site_name_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != get_bloginfo('name')) {
		$siteseo_social_og_site_name = '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />';

		//Hook on post OG site name - 'siteseo_social_og_site_name'
		if (has_filter('siteseo_social_og_site_name')) {
			$siteseo_social_og_site_name = apply_filters('siteseo_social_og_site_name', $siteseo_social_og_site_name);
		}

		if ( ! is_404()) {
			echo wp_kses($siteseo_social_og_site_name, ['meta' => ['property' => true, 'content' => true]]) . "\n";
		}
	}
}
add_action('wp_head', 'siteseo_social_facebook_og_site_name_hook', 1);

//OG Locale
function siteseo_social_facebook_og_locale_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg()) {
		$siteseo_social_og_locale = '<meta property="og:locale" content="' . get_locale() . '" />';

		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		//Polylang
		if (is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php')) {
			//@credits Polylang
			if (did_action('pll_init') && function_exists('PLL')) {
				$alternates = [];

				if (!empty(PLL()->model->get_languages_list())) {
					foreach (PLL()->model->get_languages_list() as $language) {
						$polylang = PLL()->links;
						if (isset(PLL()->curlang->slug) && PLL()->curlang->slug !== $language->slug && method_exists($polylang, 'get_translation_url') && PLL()->links->get_translation_url($language) && isset($language->facebook)) {
							$alternates[] = $language->facebook;
						}
					}

					// There is a risk that 2 languages have the same Facebook locale. So let's make sure to output each locale only once.
					$alternates = array_unique($alternates);

					foreach ($alternates as $lang) {
						$siteseo_social_og_locale .= "\n";
						$siteseo_social_og_locale .= '<meta property="og:locale:alternate" content="' . esc_attr($lang) . '" />';
					}
				}
			}
		}

		//WPML
		if (is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {

			if (get_post_type() && get_the_ID()) {
				$trid = apply_filters( 'wpml_element_trid', NULL, get_the_id(), 'post_'.get_post_type() );

				if (isset($trid)) {
					$translations = apply_filters( 'wpml_get_element_translations', NULL, $trid, 'post_'.get_post_type() );

					if (!empty($translations)) {
						foreach($translations as $lang => $object) {
							$elid = $object->element_id;

							if (isset($elid)) {
								$my_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $elid ) ;

								if (!is_wp_error( $my_post_language_details ) && !empty($my_post_language_details['locale']) && $my_post_language_details['different_language'] === true) {
									$siteseo_social_og_locale .= "\n";
									$siteseo_social_og_locale .= '<meta property="og:locale:alternate" content="' . $my_post_language_details['locale'] . '" />';
								}
							}
						}
					}
				}
			}
		}

		//Hook on post OG locale - 'siteseo_social_og_locale'
		if (has_filter('siteseo_social_og_locale')) {
			$siteseo_social_og_locale = apply_filters('siteseo_social_og_locale', $siteseo_social_og_locale);
		}

		if (isset($siteseo_social_og_locale) && '' != $siteseo_social_og_locale) {
			if ( ! is_404()) {
				echo wp_kses($siteseo_social_og_locale, ['meta' => ['property' => true, 'content' => true]]) . "\n";
			}
		}
	}
}
add_action('wp_head', 'siteseo_social_facebook_og_locale_hook', 1);

//OG Type
function siteseo_social_facebook_og_type_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg()) {
		if (is_home() || is_front_page()) {
			$siteseo_social_og_type = '<meta property="og:type" content="website" />';
		} elseif (is_singular('product') || is_singular('download')) {
			$siteseo_social_og_type = '<meta property="og:type" content="og:product" />';
		} elseif (is_singular()) {
			global $post;
			$siteseo_video_disabled	 	= get_post_meta($post->ID, '_siteseo_video_disabled', true);
			$siteseo_video	 				   = get_post_meta($post->ID, '_siteseo_video');

			if ( ! empty($siteseo_video[0][0]['url']) && '' == $siteseo_video_disabled) {
				$siteseo_social_og_type = '<meta property="og:type" content="video.other" />';
			} else {
				$siteseo_social_og_type = '<meta property="og:type" content="article" />';
			}
		} elseif (is_search() || is_archive() || is_404()) {
			$siteseo_social_og_type = '<meta property="og:type" content="object" />';
		}
		if (isset($siteseo_social_og_type)) {
			//Hook on post OG type - 'siteseo_social_og_type'
			if (has_filter('siteseo_social_og_type')) {
				$siteseo_social_og_type = apply_filters('siteseo_social_og_type', $siteseo_social_og_type);
			}
			if ( ! is_404()) {
				echo wp_kses($siteseo_social_og_type, ['meta' => ['property' => true, 'content' => true]]) . "\n";
			}
		}
	}
}
add_action('wp_head', 'siteseo_social_facebook_og_type_hook', 1);

//Article Author / Article Publisher
function siteseo_social_facebook_og_author_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_get_service('SocialOption')->getSocialAccountsFacebook()) {
		if (is_singular() && ! is_home() && ! is_front_page()) {
			global $post;
			$siteseo_video_disabled = get_post_meta($post->ID, '_siteseo_video_disabled', true);
			$siteseo_video = get_post_meta($post->ID, '_siteseo_video');

			if ( ! empty($siteseo_video[0][0]['url']) && '' == $siteseo_video_disabled) {
				//do nothing
			} else {
				$siteseo_social_og_author = '<meta property="article:author" content="' . siteseo_get_service('SocialOption')->getSocialAccountsFacebook() . '" />';
				$siteseo_social_og_author .= "\n";
				$siteseo_social_og_author .= '<meta property="article:publisher" content="' . siteseo_get_service('SocialOption')->getSocialAccountsFacebook() . '" />';
			}
		}
		if (isset($siteseo_social_og_author)) {
			//Hook on post OG author - 'siteseo_social_og_author'
			if (has_filter('siteseo_social_og_author')) {
				$siteseo_social_og_author = apply_filters('siteseo_social_og_author', $siteseo_social_og_author);
			}
			echo wp_kses($siteseo_social_og_author, ['meta' => ['property' => true, 'content' => true]]) . "\n";
		}
		if (is_singular('post')) {
			// article:section
			if (get_post_meta($post->ID, '_siteseo_robots_primary_cat', true)) {
				$_siteseo_robots_primary_cat = get_post_meta($post->ID, '_siteseo_robots_primary_cat', true);

				if (isset($_siteseo_robots_primary_cat) && '' != $_siteseo_robots_primary_cat && 'none' != $_siteseo_robots_primary_cat) {
					if (null != $post->post_type && 'post' == $post->post_type) {
						$current_cat = get_category($_siteseo_robots_primary_cat);
					}
				} else {
					$current_cat = current(get_the_category($post));
				}
			} else {
				$current_cat = current(get_the_category($post));
			}
			if ($current_cat) {
				$siteseo_social_og_section = '';
				$siteseo_social_og_section .= '<meta property="article:section" content="' . esc_attr($current_cat->name) . '" />';
				$siteseo_social_og_section .= "\n";
				if (isset($siteseo_social_og_section)) {
					//Hook on post OG article:section - 'siteseo_social_og_section'
					if (has_filter('siteseo_social_og_section')) {
						$siteseo_social_og_section = apply_filters('siteseo_social_og_section', $siteseo_social_og_section);
					}
					echo wp_kses($siteseo_social_og_section, ['meta' => ['property' => true, 'content' => true]]);
				}
			}
			// article:tag
			if (function_exists('get_the_tags')) {
				$tags = get_the_tags();
				if ( ! empty($tags)) {
					$siteseo_social_og_tag = '';
					foreach ($tags as $tag) {
						$siteseo_social_og_tag .= '<meta property="article:tag" content="' . esc_attr($tag->name) . '" />';
						$siteseo_social_og_tag .= "\n";
					}
					if (isset($siteseo_social_og_tag)) {
						//Hook on post OG article:tag - 'siteseo_social_og_tag'
						if (has_filter('siteseo_social_og_tag')) {
							$siteseo_social_og_tag = apply_filters('siteseo_social_og_tag', $siteseo_social_og_tag);
						}
						echo wp_kses($siteseo_social_og_tag, ['meta' => ['property' => true, 'content' => true]]);
					}
				}
			}
		}
	}
}
add_action('wp_head', 'siteseo_social_facebook_og_author_hook', 1);

//Facebook Title
function siteseo_social_fb_title_post_option() {
	if (function_exists('is_shop') && is_shop()) {
		$_siteseo_social_fb_title = get_post_meta(get_option('woocommerce_shop_page_id'), '_siteseo_social_fb_title', true);
	} else {
		$_siteseo_social_fb_title = get_post_meta(get_the_ID(), '_siteseo_social_fb_title', true);
	}
	if ('' != $_siteseo_social_fb_title) {
		return $_siteseo_social_fb_title;
	}
}

function siteseo_social_fb_title_term_option() {
	$_siteseo_social_fb_title = get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_social_fb_title', true);
	if ('' != $_siteseo_social_fb_title) {
		return $_siteseo_social_fb_title;
	}
}

function siteseo_social_fb_title_home_option() {
	$page_id = get_option('page_for_posts');
	$_siteseo_social_fb_title = get_post_meta($page_id, '_siteseo_social_fb_title', true);
	if ( ! empty($_siteseo_social_fb_title)) {
		return $_siteseo_social_fb_title;
	}
}

function siteseo_social_fb_title_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg()) {
		//Init
		$siteseo_social_og_title ='';

		$variables = null;
		$variables = apply_filters('siteseo_dyn_variables_fn', $variables);

		$siteseo_titles_template_variables_array 	= $variables['siteseo_titles_template_variables_array'];
		$siteseo_titles_template_replace_array 	= $variables['siteseo_titles_template_replace_array'];

		if (is_home()) {
			if ('' != siteseo_social_fb_title_home_option()) {
				$siteseo_social_og_title .= '<meta property="og:title" content="' . siteseo_social_fb_title_home_option() . '" />';
				$siteseo_social_og_title .= "\n";
			} elseif (function_exists('siteseo_titles_the_title') && '' != siteseo_titles_the_title()) {
				$siteseo_social_og_title .= '<meta property="og:title" content="' . esc_attr(siteseo_titles_the_title()) . '" />';
				$siteseo_social_og_title .= "\n";
			}
		} elseif (is_tax() || is_category() || is_tag()) {
			if ('' != siteseo_social_fb_title_term_option()) {
				$siteseo_social_og_title .= '<meta property="og:title" content="' . siteseo_social_fb_title_term_option() . '" />';
				$siteseo_social_og_title .= "\n";
			} elseif (function_exists('siteseo_titles_the_title') && '' != siteseo_titles_the_title()) {
				$siteseo_social_og_title .= '<meta property="og:title" content="' . esc_attr(siteseo_titles_the_title()) . '" />';
				$siteseo_social_og_title .= "\n";
			} else {
				$siteseo_social_og_title .= '<meta property="og:title" content="' . single_term_title('', false) . ' - ' . get_bloginfo('name') . '" />';
				$siteseo_social_og_title .= "\n";
			}
		} elseif (is_singular() && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_social_fb_title_post_option()) {
			$siteseo_social_og_title .= '<meta property="og:title" content="' . siteseo_social_fb_title_post_option() . '" />';
			$siteseo_social_og_title .= "\n";
		} elseif (function_exists('is_shop') && is_shop() && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_social_fb_title_post_option()) {
			$siteseo_social_og_title .= '<meta property="og:title" content="' . siteseo_social_fb_title_post_option() . '" />';
			$siteseo_social_og_title .= "\n";
		} elseif ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && function_exists('siteseo_titles_the_title') && '' != siteseo_titles_the_title()) {
			$siteseo_social_og_title .= '<meta property="og:title" content="' . esc_attr(siteseo_titles_the_title()) . '" />';
			$siteseo_social_og_title .= "\n";
		} elseif ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != get_the_title()) {
			$siteseo_social_og_title .= '<meta property="og:title" content="' . the_title_attribute('echo=0') . '" />';
			$siteseo_social_og_title .= "\n";
		}

		//Apply dynamic variables
		preg_match_all('/%%_cf_(.*?)%%/', $siteseo_social_og_title, $matches); //custom fields

		if ( ! empty($matches)) {
			$siteseo_titles_cf_template_variables_array = [];
			$siteseo_titles_cf_template_replace_array   = [];

			foreach ($matches['0'] as $key => $value) {
				$siteseo_titles_cf_template_variables_array[] = $value;
			}

			foreach ($matches['1'] as $key => $value) {
				if (is_singular()) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_post_meta($post->ID, $value, true));
				} elseif (is_tax() || is_category() || is_tag()) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_term_meta(get_queried_object()->{'term_id'}, $value, true));
				}
			}
		}

		//Custom fields
		if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
			$siteseo_social_og_title = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_social_og_title);
		}

		$siteseo_social_og_title = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_social_og_title);

		//Hook on post OG title - 'siteseo_social_og_title'
		if (has_filter('siteseo_social_og_title')) {
			$siteseo_social_og_title = apply_filters('siteseo_social_og_title', $siteseo_social_og_title);
		}

		if (isset($siteseo_social_og_title) && '' != $siteseo_social_og_title) {
			if ( ! is_404()) {
				echo wp_kses($siteseo_social_og_title, ['meta' => ['property' => true, 'content' => true]]);
			}
		}
	}
}
add_action('wp_head', 'siteseo_social_fb_title_hook', 1);

//Facebook Desc
function siteseo_social_fb_desc_post_option() {
	if (function_exists('is_shop') && is_shop()) {
		$_siteseo_social_fb_desc = get_post_meta(get_option('woocommerce_shop_page_id'), '_siteseo_social_fb_desc', true);
	} else {
		$_siteseo_social_fb_desc = get_post_meta(get_the_ID(), '_siteseo_social_fb_desc', true);
	}
	if ('' != $_siteseo_social_fb_desc) {
		return $_siteseo_social_fb_desc;
	}
}

function siteseo_social_fb_desc_term_option() {
	$_siteseo_social_fb_desc = get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_social_fb_desc', true);
	if ('' != $_siteseo_social_fb_desc) {
		return $_siteseo_social_fb_desc;
	}
}

function siteseo_social_fb_desc_home_option() {
	$page_id = get_option('page_for_posts');
	$_siteseo_social_fb_desc = get_post_meta($page_id, '_siteseo_social_fb_desc', true);
	if ( ! empty($_siteseo_social_fb_desc)) {
		return $_siteseo_social_fb_desc;
	}
}

function siteseo_social_fb_desc_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && ! is_search()) {
		if (function_exists('wc_memberships_is_post_content_restricted') && wc_memberships_is_post_content_restricted()) {
			return false;
		}
		global $post;
		//Init
		$siteseo_social_og_desc ='';

		$variables = null;
		$variables = apply_filters('siteseo_dyn_variables_fn', $variables);

		$siteseo_titles_template_variables_array 	= $variables['siteseo_titles_template_variables_array'];
		$siteseo_titles_template_replace_array 	= $variables['siteseo_titles_template_replace_array'];

		//Excerpt length
		$siteseo_excerpt_length = 50;
		$siteseo_excerpt_length = apply_filters('siteseo_excerpt_length', $siteseo_excerpt_length);
		setup_postdata($post);
		if (is_home()) {

			if ('' != siteseo_social_fb_desc_home_option()) {
				$siteseo_social_og_desc .= '<meta property="og:description" content="' . siteseo_social_fb_desc_home_option() . '" />';
				$siteseo_social_og_desc .= "\n";
			} elseif (function_exists('siteseo_titles_the_description_content') && '' != siteseo_titles_the_description_content()) {
				$siteseo_social_og_desc .= '<meta property="og:description" content="' . siteseo_titles_the_description_content() . '" />';
				$siteseo_social_og_desc .= "\n";
			}
		} elseif (is_tax() || is_category() || is_tag()) {
			if ('' != siteseo_social_fb_desc_term_option()) {
				$siteseo_social_og_desc .= '<meta property="og:description" content="' . siteseo_social_fb_desc_term_option() . '" />';
				$siteseo_social_og_desc .= "\n";
			} elseif (function_exists('siteseo_titles_the_description_content') && '' != siteseo_titles_the_description_content()) {
				$siteseo_social_og_desc .= '<meta property="og:description" content="' . siteseo_titles_the_description_content() . '" />';
				$siteseo_social_og_desc .= "\n";
			} elseif ('' != term_description()) {
				$siteseo_social_og_desc .= '<meta property="og:description" content="' . wp_trim_words(stripslashes_deep(wp_filter_nohtml_kses(term_description())), $siteseo_excerpt_length) . ' - ' . get_bloginfo('name') . '" />';
				$siteseo_social_og_desc .= "\n";
			}
		} elseif (is_singular() && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_social_fb_desc_post_option()) {

			$siteseo_social_og_desc .= '<meta property="og:description" content="' . siteseo_social_fb_desc_post_option() . '" />';
			$siteseo_social_og_desc .= "\n";
		} elseif (function_exists('is_shop') && is_shop() && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_social_fb_desc_post_option()) {
			$siteseo_social_og_desc .= '<meta property="og:description" content="' . siteseo_social_fb_desc_post_option() . '" />';
			$siteseo_social_og_desc .= "\n";
		} elseif ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && function_exists('siteseo_titles_the_description_content') && '' != siteseo_titles_the_description_content()) {
			$siteseo_social_og_desc .= '<meta property="og:description" content="' . siteseo_titles_the_description_content() . '" />';
			$siteseo_social_og_desc .= "\n";
		} elseif ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != get_the_excerpt()) {
			$siteseo_social_og_desc .= '<meta property="og:description" content="' . wp_trim_words(esc_attr(stripslashes_deep(wp_filter_nohtml_kses(get_the_excerpt()))), $siteseo_excerpt_length) . '" />';
			$siteseo_social_og_desc .= "\n";
		}

		//Apply dynamic variables
		preg_match_all('/%%_cf_(.*?)%%/', $siteseo_social_og_desc, $matches); //custom fields

		if ( ! empty($matches)) {
			$siteseo_titles_cf_template_variables_array = [];
			$siteseo_titles_cf_template_replace_array   = [];

			foreach ($matches['0'] as $key => $value) {
				$siteseo_titles_cf_template_variables_array[] = $value;
			}

			foreach ($matches['1'] as $key => $value) {
				if (is_singular()) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_post_meta($post->ID, $value, true));
				} elseif (is_tax() || is_category() || is_tag()) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_term_meta(get_queried_object()->{'term_id'}, $value, true));
				}
			}
		}

		//Custom fields
		if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
			$siteseo_social_og_desc = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_social_og_desc);
		}

		$siteseo_social_og_desc = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_social_og_desc);

		//Hook on post OG description - 'siteseo_social_og_desc'
		if (has_filter('siteseo_social_og_desc')) {
			$siteseo_social_og_desc = apply_filters('siteseo_social_og_desc', $siteseo_social_og_desc);
		}
		if (isset($siteseo_social_og_desc) && '' != $siteseo_social_og_desc) {
			if ( ! is_404()) {
				echo wp_kses($siteseo_social_og_desc, ['meta' => ['property' => true, 'content' => true]]);
			}
		}
	}
}
add_action('wp_head', 'siteseo_social_fb_desc_hook', 1);

//Facebook Thumbnail
function siteseo_social_fb_img_post_option() {
	if (function_exists('is_shop') && is_shop()) {
		$_siteseo_social_fb_img = get_post_meta(get_option('woocommerce_shop_page_id'), '_siteseo_social_fb_img', true);
	} else {
		$_siteseo_social_fb_img = get_post_meta(get_the_ID(), '_siteseo_social_fb_img', true);
	}

	if ('' != $_siteseo_social_fb_img) {
		return $_siteseo_social_fb_img;
	}
}

function siteseo_social_fb_img_term_option() {
	$_siteseo_social_fb_img = get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_social_fb_img', true);
	if ('' != $_siteseo_social_fb_img) {
		return $_siteseo_social_fb_img;
	}
}

function siteseo_social_fb_img_product_cat_option() {
	if ( is_tax('product_cat') ){
		global $wp_query;
		$cat = $wp_query->get_queried_object();
		$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
		$image = wp_get_attachment_url( $thumbnail_id );
		if ( $image ) {
			return $image;
		}
	}
}

function siteseo_social_fb_img_home_option() {
	$page_id = get_option('page_for_posts');
	$_siteseo_social_fb_img = get_post_meta($page_id, '_siteseo_social_fb_img', true);
	if ( ! empty($_siteseo_social_fb_img)) {
		return $_siteseo_social_fb_img;
	} elseif (has_post_thumbnail($page_id)) {
		return get_the_post_thumbnail_url($page_id);
	}
}

function siteseo_thumbnail_in_content() {
	//Get post content
	$siteseo_get_the_content = get_post_field('post_content', get_the_ID());

	if ('' != $siteseo_get_the_content) {
		//DomDocument
		$dom			= new domDocument();
		$internalErrors = libxml_use_internal_errors(true);

		if (function_exists('mb_convert_encoding')) {
			$dom->loadHTML(mb_convert_encoding($siteseo_get_the_content, 'HTML-ENTITIES', 'UTF-8'));
		} else {
			$dom->loadHTML('<?xml encoding="utf-8" ?>' . $siteseo_get_the_content);
		}

		$dom->preserveWhiteSpace = false;
		if ('' != $dom->getElementsByTagName('img')) {
			$images = $dom->getElementsByTagName('img');
		}
		if (isset($images) && ! empty($images)) {
			if ($images->length >= 1) {
				foreach ($images as $img) {
					$url = $img->getAttribute('src');
					//Exclude Base64 img
					if (false === strpos($url, 'data:image/')) {
						if (true === siteseo_is_absolute($url)) {
							//do nothing
						} else {
							$url = get_home_url() . $url;
						}
						//cleaning url
						$url = htmlspecialchars(esc_attr(wp_filter_nohtml_kses($url)));

						//remove query strings
						$parse_url = wp_parse_url($url);

						if ( ! empty($parse_url['scheme']) && ! empty($parse_url['host']) && ! empty($parse_url['path'])) {
							return $parse_url['scheme'] . '://' . $parse_url['host'] . $parse_url['path'];
						} else {
							return $url;
						}
					}
				}
			}
		}
		libxml_use_internal_errors($internalErrors);
	}
}

function siteseo_social_fb_img_size_from_url($url, $post_id = null) {
	if (!function_exists('attachment_url_to_postid')) {
		return;
	}

	$stop_attachment_url_to_postid = apply_filters( 'siteseo_stop_attachment_url_to_postid', false );

	if ($post_id) {
		$post_id = get_post_thumbnail_id($post_id);
	} elseif ($stop_attachment_url_to_postid === false) {
		$post_id 			= attachment_url_to_postid($url);

		//If cropped image
		if (0 != $post_id) {
			$dir  = wp_upload_dir();
			$path = $url;
			if (0 === strpos($path, $dir['baseurl'] . '/')) {
				$path = substr($path, strlen($dir['baseurl'] . '/'));
			}

			if (preg_match('/^(.*)(\-\d*x\d*)(\.\w{1,})/i', $path, $matches)) {
				$url	 = $dir['baseurl'] . '/' . $matches[1] . $matches[3];
				$post_id = attachment_url_to_postid($url);
			}
		}
	}

	$image_src = wp_get_attachment_image_src($post_id, 'full');

	//OG:IMAGE
	$siteseo_social_og_img = '';
	$siteseo_social_og_img .= '<meta property="og:image" content="' . esc_attr($url) . '" />';
	$siteseo_social_og_img .= "\n";

	//OG:IMAGE:SECURE_URL IF SSL
	if (is_ssl()) {
		$siteseo_social_og_img .= '<meta property="og:image:secure_url" content="' . esc_attr($url) . '" />';
		$siteseo_social_og_img .= "\n";
	}

	//OG:IMAGE:WIDTH + OG:IMAGE:HEIGHT
	if ( ! empty($image_src)) {
		$siteseo_social_og_img .= '<meta property="og:image:width" content="' . esc_attr($image_src[1]) . '" />';
		$siteseo_social_og_img .= "\n";
		$siteseo_social_og_img .= '<meta property="og:image:height" content="' . esc_attr($image_src[2]) . '" />';
		$siteseo_social_og_img .= "\n";
	}

	//OG:IMAGE:ALT
	if ('' != get_post_meta($post_id, '_wp_attachment_image_alt', true)) {
		$siteseo_social_og_img .= '<meta property="og:image:alt" content="' . esc_attr(get_post_meta($post_id, '_wp_attachment_image_alt', true)) . '" />';
		$siteseo_social_og_img .= "\n";
	}

	return $siteseo_social_og_img;
}

function siteseo_social_fb_img_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg()) {
		//Init
		global $post;
		$siteseo_social_og_thumb ='';

		if (is_home() && '' != siteseo_social_fb_img_home_option() && 'page' == get_option('show_on_front')) {

			$siteseo_social_og_thumb .= siteseo_social_fb_img_size_from_url(siteseo_social_fb_img_home_option());

		} elseif ((is_singular() || (function_exists('is_shop') && is_shop())) && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_social_fb_img_post_option()) {//Custom OG:IMAGE from SEO metabox
			$siteseo_social_og_thumb .= siteseo_get_service('FacebookImageOptionMeta')->getMetasBy('id');

		} elseif ((is_singular() || (function_exists('is_shop') && is_shop())) && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '1' == siteseo_get_service('SocialOption')->getSocialFacebookImgDefault() && '' != siteseo_get_service('SocialOption')->getSocialFacebookImg()) {//If "Apply this image to all your og:image tag" ON
			$siteseo_social_og_thumb .= siteseo_get_service('FacebookImageOptionMeta')->getMetasBy('id');

		} elseif ((is_singular() || (function_exists('is_shop') && is_shop())) && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && has_post_thumbnail()) {//If post thumbnail

			$siteseo_social_og_thumb .= siteseo_social_fb_img_size_from_url(get_the_post_thumbnail_url($post, 'full'), $post->ID);

		} elseif ((is_singular() || (function_exists('is_shop') && is_shop())) && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_thumbnail_in_content()) {//First image of post content

			$siteseo_social_og_thumb .= siteseo_social_fb_img_size_from_url(siteseo_thumbnail_in_content());

		} elseif ((is_tax() || is_category() || is_tag()) && '' != siteseo_social_fb_img_term_option()) {//Custom OG:IMAGE for term from SEO metabox

			$siteseo_social_og_thumb .= siteseo_social_fb_img_size_from_url(siteseo_social_fb_img_term_option());

		} elseif (is_tax('product_cat') && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && siteseo_social_fb_img_product_cat_option() !='') {//If product category thumbnail

			$siteseo_social_og_thumb .= siteseo_social_fb_img_size_from_url(siteseo_social_fb_img_product_cat_option());

		} elseif (is_post_type_archive() && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_get_service('SocialOption')->getSocialFacebookImgCPT()) {//Default OG:IMAGE from global settings

			$siteseo_social_og_thumb .= siteseo_social_fb_img_size_from_url(siteseo_get_service('SocialOption')->getSocialFacebookImgCPT());

		} elseif ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_get_service('SocialOption')->getSocialFacebookImg()) {//Default OG:IMAGE from global settings

			$siteseo_social_og_thumb .= siteseo_social_fb_img_size_from_url(siteseo_get_service('SocialOption')->getSocialFacebookImg());

		} elseif (!empty(get_option('site_icon'))) { //Site icon

			$site_icon = wp_get_attachment_url(get_option('site_icon'));

			$siteseo_social_og_thumb .= siteseo_social_fb_img_size_from_url($site_icon);

		}

		//Hook on post OG thumbnail - 'siteseo_social_og_thumb'
		if (has_filter('siteseo_social_og_thumb')) {
			$siteseo_social_og_thumb = apply_filters('siteseo_social_og_thumb', $siteseo_social_og_thumb);
		}
		if (isset($siteseo_social_og_thumb) && '' != $siteseo_social_og_thumb) {
			if ( ! is_404()) {
				echo wp_kses($siteseo_social_og_thumb, ['meta' => ['property' => true, 'content' => true]]);
			}
		}
	}
}
add_action('wp_head', 'siteseo_social_fb_img_hook', 1);

function siteseo_social_facebook_link_ownership_id_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_get_service('SocialOption')->searchOptionByKey('social_facebook_link_ownership_id')) {
		$siteseo_social_link_ownership_id = '<meta property="fb:pages" content="' . siteseo_get_service('SocialOption')->searchOptionByKey('social_facebook_link_ownership_id') . '" />';

		echo wp_kses($siteseo_social_link_ownership_id, ['meta' => ['property' => true, 'content' => true]]) . "\n";
	}
}
add_action('wp_head', 'siteseo_social_facebook_link_ownership_id_hook', 1);

function siteseo_social_facebook_admin_id_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_get_service('SocialOption')->searchOptionByKey('social_facebook_admin_id')) {
		$siteseo_social_admin_id = '<meta property="fb:admins" content="' . siteseo_get_service('SocialOption')->searchOptionByKey('social_facebook_admin_id') . '" />';

		if ( ! is_404()) {
			echo wp_kses($siteseo_social_admin_id, ['meta' => ['property' => true, 'content' => true]]) . "\n";
		}
	}
}
add_action('wp_head', 'siteseo_social_facebook_admin_id_hook', 1);

function siteseo_social_facebook_app_id_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_get_service('SocialOption')->searchOptionByKey('social_facebook_app_id')) {
		$siteseo_social_app_id = '<meta property="fb:app_id" content="' . siteseo_get_service('SocialOption')->searchOptionByKey('social_facebook_app_id') . '" />';

		if ( ! is_404()) {
			echo wp_kses($siteseo_social_app_id, ['meta' => ['property' => true, 'content' => true]]) . "\n";
		}
	}
}
add_action('wp_head', 'siteseo_social_facebook_app_id_hook', 1);

//Twitter Summary Card
function siteseo_social_twitter_card_summary_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialTwitterCard()) {
		if ('large' == siteseo_get_service('SocialOption')->getSocialTwitterImgSize()) {
			$siteseo_social_twitter_card_summary = '<meta name="twitter:card" content="summary_large_image">';
		} else {
			$siteseo_social_twitter_card_summary = '<meta name="twitter:card" content="summary" />';
		}
		//Hook on post Twitter card summary - 'siteseo_social_twitter_card_summary'
		if (has_filter('siteseo_social_twitter_card_summary')) {
			$siteseo_social_twitter_card_summary = apply_filters('siteseo_social_twitter_card_summary', $siteseo_social_twitter_card_summary);
		}

		if ( ! is_404()) {
			echo wp_kses($siteseo_social_twitter_card_summary, ['meta' => ['name' => true, 'content' => true]]) . "\n";
		}
	}
}
add_action('wp_head', 'siteseo_social_twitter_card_summary_hook', 1);

//Twitter Site
function siteseo_social_twitter_card_site_hook() {
	if ('1' == siteseo_get_service('SocialOption')->getSocialTwitterCard() && '' != siteseo_get_service('SocialOption')->getSocialAccountsTwitter()) {
		$siteseo_social_twitter_card_site = '<meta name="twitter:site" content="' . siteseo_get_service('SocialOption')->getSocialAccountsTwitter() . '" />';

		//Hook on post Twitter card site - 'siteseo_social_twitter_card_site'
		if (has_filter('siteseo_social_twitter_card_site')) {
			$siteseo_social_twitter_card_site = apply_filters('siteseo_social_twitter_card_site', $siteseo_social_twitter_card_site);
		}

		if ( ! is_404()) {
			echo wp_kses($siteseo_social_twitter_card_site, ['meta' => ['name' => true, 'content' => true]]) . "\n";
		}
	}
}
add_action('wp_head', 'siteseo_social_twitter_card_site_hook', 1);

//Twitter Creator
function siteseo_social_twitter_card_creator_hook() {
	//Init
	$siteseo_social_twitter_card_creator ='';

	if ('1' == siteseo_get_service('SocialOption')->getSocialTwitterCard() && get_the_author_meta('twitter')) {
		$siteseo_social_twitter_card_creator .= '<meta name="twitter:creator" content="@' . get_the_author_meta('twitter') . '" />';
	} elseif ('1' == siteseo_get_service('SocialOption')->getSocialTwitterCard() && '' != siteseo_get_service('SocialOption')->getSocialAccountsTwitter()) {
		$siteseo_social_twitter_card_creator .= '<meta name="twitter:creator" content="' . siteseo_get_service('SocialOption')->getSocialAccountsTwitter() . '" />';
	}
	//Hook on post Twitter card creator - 'siteseo_social_twitter_card_creator'
	if (has_filter('siteseo_social_twitter_card_creator')) {
		$siteseo_social_twitter_card_creator = apply_filters('siteseo_social_twitter_card_creator', $siteseo_social_twitter_card_creator);
	}
	if (isset($siteseo_social_twitter_card_creator) && '' != $siteseo_social_twitter_card_creator) {
		if ( ! is_404()) {
			echo wp_kses($siteseo_social_twitter_card_creator, ['meta' => ['name' => true, 'content' => true]]) . "\n";
		}
	}
}
add_action('wp_head', 'siteseo_social_twitter_card_creator_hook', 1);

//Twitter Title
function siteseo_social_twitter_title_post_option() {
	if (function_exists('is_shop') && is_shop()) {
		$_siteseo_social_twitter_title = get_post_meta(get_option('woocommerce_shop_page_id'), '_siteseo_social_twitter_title', true);
	} else {
		$_siteseo_social_twitter_title = get_post_meta(get_the_ID(), '_siteseo_social_twitter_title', true);
	}
	if ('' != $_siteseo_social_twitter_title) {
		return $_siteseo_social_twitter_title;
	}
}

function siteseo_social_twitter_title_term_option() {
	$_siteseo_social_twitter_title = get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_social_twitter_title', true);
	if ('' != $_siteseo_social_twitter_title) {
		return $_siteseo_social_twitter_title;
	}
}

function siteseo_social_twitter_title_home_option() {
	$page_id = get_option('page_for_posts');
	$_siteseo_social_twitter_title = get_post_meta($page_id, '_siteseo_social_twitter_title', true);
	if ( ! empty($_siteseo_social_twitter_title)) {
		return $_siteseo_social_twitter_title;
	}
}

function siteseo_social_twitter_title_hook() {
	//If Twitter cards enable
	if ('1' == siteseo_get_service('SocialOption')->getSocialTwitterCard()) {
		//Init
		$siteseo_social_twitter_card_title ='';

		$variables = null;
		$variables = apply_filters('siteseo_dyn_variables_fn', $variables);

		$siteseo_titles_template_variables_array 	= $variables['siteseo_titles_template_variables_array'];
		$siteseo_titles_template_replace_array 	= $variables['siteseo_titles_template_replace_array'];

		if (is_home()) {//Home
			if ('' != siteseo_social_twitter_title_home_option()) {
				$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . siteseo_social_twitter_title_home_option() . '" />';
			} elseif ('1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg() && '' != siteseo_social_fb_title_home_option()) {
				$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . siteseo_social_fb_title_home_option() . '" />';
			} elseif (function_exists('siteseo_titles_the_title') && '' != siteseo_titles_the_title()) {
				$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . esc_attr(siteseo_titles_the_title()) . '" />';
			}
		} elseif (is_tax() || is_category() || is_tag()) {//Term archive
			if ('' != siteseo_social_twitter_title_term_option()) {
				$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . siteseo_social_twitter_title_term_option() . '" />';
			} elseif ('1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg() && '' != siteseo_social_fb_title_term_option()) {
				$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . siteseo_social_fb_title_term_option() . '" />';
			} elseif (function_exists('siteseo_titles_the_title') && '' != siteseo_titles_the_title()) {
				$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . esc_attr(siteseo_titles_the_title()) . '" />';
			} else {
				$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . single_term_title('', false) . ' - ' . get_bloginfo('name') . '" />';
			}
		} elseif (is_singular() && '' != siteseo_social_twitter_title_post_option()) {//Single
			$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . siteseo_social_twitter_title_post_option() . '" />';
		} elseif (is_singular() && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg() && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_social_fb_title_post_option()) {
			$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . siteseo_social_fb_title_post_option() . '" />';
		} elseif (function_exists('is_shop') && is_shop() && '' != siteseo_social_twitter_title_post_option()) {//Single
			$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . siteseo_social_twitter_title_post_option() . '" />';
		} elseif (function_exists('is_shop') && is_shop() && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg() && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_social_fb_title_post_option()) {
			$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . siteseo_social_fb_title_post_option() . '" />';
		} elseif (function_exists('siteseo_titles_the_title') && '' != siteseo_titles_the_title()) {
			$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . esc_attr(siteseo_titles_the_title()) . '" />';
		} elseif ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg() && function_exists('siteseo_titles_the_title') && '' != siteseo_titles_the_title()) {
			$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . esc_attr(siteseo_titles_the_title()) . '" />';
		} elseif ('' != get_the_title()) {
			$siteseo_social_twitter_card_title .= '<meta name="twitter:title" content="' . the_title_attribute('echo=0') . '" />';
		}

		//Apply dynamic variables
		preg_match_all('/%%_cf_(.*?)%%/', $siteseo_social_twitter_card_title, $matches); //custom fields

		if ( ! empty($matches)) {
			$siteseo_titles_cf_template_variables_array = [];
			$siteseo_titles_cf_template_replace_array   = [];

			foreach ($matches['0'] as $key => $value) {
				$siteseo_titles_cf_template_variables_array[] = $value;
			}

			foreach ($matches['1'] as $key => $value) {
				if (is_singular()) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_post_meta($post->ID, $value, true));
				} elseif (is_tax() || is_category() || is_tag()) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_term_meta(get_queried_object()->{'term_id'}, $value, true));
				}
			}
		}

		//Custom fields
		if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
			$siteseo_social_twitter_card_title = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_social_twitter_card_title);
		}

		$siteseo_social_twitter_card_title = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_social_twitter_card_title);

		//Hook on post Twitter card title - 'siteseo_social_twitter_card_title'
		if (has_filter('siteseo_social_twitter_card_title')) {
			$siteseo_social_twitter_card_title = apply_filters('siteseo_social_twitter_card_title', $siteseo_social_twitter_card_title);
		}
		if (isset($siteseo_social_twitter_card_title) && '' != $siteseo_social_twitter_card_title) {
			if ( ! is_404()) {
				echo wp_kses($siteseo_social_twitter_card_title, ['meta' => ['name' => true, 'content' => true]]) . "\n";
			}
		}
	}
}
add_action('wp_head', 'siteseo_social_twitter_title_hook', 1);

//Twitter Desc
function siteseo_social_twitter_desc_post_option() {
	if (function_exists('is_shop') && is_shop()) {
		$_siteseo_social_twitter_desc = get_post_meta(get_option('woocommerce_shop_page_id'), '_siteseo_social_twitter_desc', true);
	} else {
		$_siteseo_social_twitter_desc = get_post_meta(get_the_ID(), '_siteseo_social_twitter_desc', true);
	}
	if ('' != $_siteseo_social_twitter_desc) {
		return $_siteseo_social_twitter_desc;
	}
}

function siteseo_social_twitter_desc_term_option() {
	$_siteseo_social_twitter_desc = get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_social_twitter_desc', true);
	if ('' != $_siteseo_social_twitter_desc) {
		return $_siteseo_social_twitter_desc;
	}
}

function siteseo_social_twitter_desc_home_option() {
	$page_id = get_option('page_for_posts');
	$_siteseo_social_twitter_desc = get_post_meta($page_id, '_siteseo_social_twitter_desc', true);
	if ( ! empty($_siteseo_social_twitter_desc)) {
		return $_siteseo_social_twitter_desc;
	}
}

function siteseo_social_twitter_desc_hook() {
	//If Twitter cards enable
	if ('1' == siteseo_get_service('SocialOption')->getSocialTwitterCard() && ! is_search()) {
		if (function_exists('wc_memberships_is_post_content_restricted') && wc_memberships_is_post_content_restricted()) {
			return false;
		}
		global $post;
		setup_postdata($post);

		//Init
		$siteseo_social_twitter_card_desc ='';

		$variables = null;
		$variables = apply_filters('siteseo_dyn_variables_fn', $variables);

		$siteseo_titles_template_variables_array 	= $variables['siteseo_titles_template_variables_array'];
		$siteseo_titles_template_replace_array 	= $variables['siteseo_titles_template_replace_array'];

		//Excerpt length
		$siteseo_excerpt_length = 50;
		$siteseo_excerpt_length = apply_filters('siteseo_excerpt_length', $siteseo_excerpt_length);

		if (is_home()) {//Home
			if ('' != siteseo_social_twitter_desc_home_option()) {
				$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_social_twitter_desc_home_option() . '" />';
			} elseif ('' != siteseo_social_fb_desc_home_option() && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg()) {
				$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_social_fb_desc_home_option() . '" />';
			} elseif (function_exists('siteseo_titles_the_description_content') && '' != siteseo_titles_the_description_content()) {
				$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_titles_the_description_content() . '" />';
			}
		} elseif (is_tax() || is_category() || is_tag()) {//Term archive
			if ('' != siteseo_social_twitter_desc_term_option()) {
				$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_social_twitter_desc_term_option() . '" />';
			} elseif ('' != siteseo_social_fb_desc_term_option() && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg()) {
				$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_social_fb_desc_term_option() . '" />';
			} elseif (function_exists('siteseo_titles_the_description_content') && '' != siteseo_titles_the_description_content()) {
				$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_titles_the_description_content() . '" />';
			} elseif ('' != term_description()) {
				$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . wp_trim_words(stripslashes_deep(wp_filter_nohtml_kses(term_description())), $siteseo_excerpt_length) . ' - ' . get_bloginfo('name') . '" />';
			}
		} elseif (is_singular() && '' != siteseo_social_twitter_desc_post_option()) {//Single
			$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_social_twitter_desc_post_option() . '" />';
		} elseif (is_singular() && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_social_fb_desc_post_option() && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg()) {
			$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_social_fb_desc_post_option() . '" />';
		} elseif (function_exists('is_shop') && is_shop() && '' != siteseo_social_twitter_desc_post_option()) {//Single
			$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_social_twitter_desc_post_option() . '" />';
		} elseif (function_exists('is_shop') && is_shop() && '1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && '' != siteseo_social_fb_desc_post_option() && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg()) {
			$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_social_fb_desc_post_option() . '" />';
		} elseif (function_exists('siteseo_titles_the_description_content') && '' != siteseo_titles_the_description_content()) {
			$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_titles_the_description_content() . '" />';
		} elseif ('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg() && function_exists('siteseo_titles_the_description_content') && '' != siteseo_titles_the_description_content() && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg()) {
			$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . siteseo_titles_the_description_content() . '" />';
		} elseif ('' != get_the_excerpt()) {
			$siteseo_social_twitter_card_desc .= '<meta name="twitter:description" content="' . wp_trim_words(esc_attr(stripslashes_deep(wp_filter_nohtml_kses(get_the_excerpt()))), $siteseo_excerpt_length) . '" />';
		}

		//Apply dynamic variables
		preg_match_all('/%%_cf_(.*?)%%/', $siteseo_social_twitter_card_desc, $matches); //custom fields

		if ( ! empty($matches)) {
			$siteseo_titles_cf_template_variables_array = [];
			$siteseo_titles_cf_template_replace_array   = [];

			foreach ($matches['0'] as $key => $value) {
				$siteseo_titles_cf_template_variables_array[] = $value;
			}

			foreach ($matches['1'] as $key => $value) {
				if (is_singular()) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_post_meta($post->ID, $value, true));
				} elseif (is_tax() || is_category() || is_tag()) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_term_meta(get_queried_object()->{'term_id'}, $value, true));
				}
			}
		}

		//Custom fields
		if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
			$siteseo_social_twitter_card_desc = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_social_twitter_card_desc);
		}

		$siteseo_social_twitter_card_desc = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_social_twitter_card_desc);

		//Hook on post Twitter card description - 'siteseo_social_twitter_card_desc'
		if (has_filter('siteseo_social_twitter_card_desc')) {
			$siteseo_social_twitter_card_desc = apply_filters('siteseo_social_twitter_card_desc', $siteseo_social_twitter_card_desc);
		}
		if (isset($siteseo_social_twitter_card_desc) && '' != $siteseo_social_twitter_card_desc) {
			if ( ! is_404()) {
				echo wp_kses($siteseo_social_twitter_card_desc, ['meta' => ['name' => true, 'content' => true]]) . "\n";
			}
		}
	}
}
add_action('wp_head', 'siteseo_social_twitter_desc_hook', 1);

//Twitter Thumbnail
function siteseo_social_twitter_img_post_option() {
	if (function_exists('is_shop') && is_shop()) {
		$_siteseo_social_twitter_img = get_post_meta(get_option('woocommerce_shop_page_id'), '_siteseo_social_twitter_img', true);
	} else {
		$_siteseo_social_twitter_img = get_post_meta(get_the_ID(), '_siteseo_social_twitter_img', true);
	}
	if ('' != $_siteseo_social_twitter_img) {
		return $_siteseo_social_twitter_img;
	}
}

function siteseo_social_twitter_img_term_option() {
	$_siteseo_social_twitter_img = get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_social_twitter_img', true);
	if ('' != $_siteseo_social_twitter_img) {
		return $_siteseo_social_twitter_img;
	}
}

function siteseo_social_twitter_img_home_option() {
	
	$page_id = get_option('page_for_posts');
	$_siteseo_social_twitter_img = get_post_meta($page_id, '_siteseo_social_twitter_img', true);
	
	if(!empty($_siteseo_social_twitter_img)) {
		return $_siteseo_social_twitter_img;
	}elseif(has_post_thumbnail($page_id)) {
		return get_the_post_thumbnail_url($page_id);
	}
	
}

function siteseo_social_twitter_img_hook() {
	
	if ('1' == siteseo_get_service('SocialOption')->getSocialTwitterCard()) {
		//Init
		global $post;
		$url ='';
		$siteseo_social_twitter_card_thumb = '';

		if (is_home() && '' != siteseo_social_twitter_img_home_option() && 'page' == get_option('show_on_front')) {
			$url = siteseo_social_twitter_img_home_option();
		} elseif (is_home() && '' != siteseo_social_fb_img_home_option() && 'page' == get_option('show_on_front') && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg()) {
			$url = siteseo_social_fb_img_home_option();
		} elseif ('' != siteseo_social_twitter_img_post_option() && (is_singular() || (function_exists('is_shop') && is_shop()))) {//Single
			$url = siteseo_social_twitter_img_post_option();
		} elseif ('' != siteseo_social_fb_img_post_option() && (is_singular() || (function_exists('is_shop') && is_shop())) && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg()) {
			$url = siteseo_social_fb_img_post_option();
		} elseif (has_post_thumbnail() && (is_singular() || (function_exists('is_shop') && is_shop()))) {
			$url = get_the_post_thumbnail_url($post, 'large');
		} elseif ('' != siteseo_thumbnail_in_content() && (is_singular() || (function_exists('is_shop') && is_shop()))) {
			$url = siteseo_thumbnail_in_content();
		} elseif ((is_tax() || is_category() || is_tag()) && '' != siteseo_social_twitter_img_term_option()) {//Term archive
			$url = siteseo_social_twitter_img_term_option();
		} elseif ((is_tax() || is_category() || is_tag()) && '' != siteseo_social_fb_img_term_option() && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg()) {
			$url = siteseo_social_fb_img_term_option();
		} elseif (is_tax('product_cat') && siteseo_social_fb_img_product_cat_option() !='') {//If product category thumbnail
			$url = siteseo_social_fb_img_product_cat_option();
		} elseif ('' != siteseo_get_service('SocialOption')->getSocialTwitterImg()) {//Default Twitter
			$url = siteseo_get_service('SocialOption')->getSocialTwitterImg();
		} elseif ('' != siteseo_get_service('SocialOption')->getSocialFacebookImg() && '1' == siteseo_get_service('SocialOption')->getSocialTwitterCardOg()) {//Default Facebook
			$url = siteseo_get_service('SocialOption')->getSocialFacebookImg();
		}

		if (!empty($url)) {
			$siteseo_social_twitter_card_thumb = '<meta name="twitter:image" content="' . esc_attr($url) . '" />';
		}

		//Hook on post Twitter card thumbnail - 'siteseo_social_twitter_card_thumb'
		if (has_filter('siteseo_social_twitter_card_thumb')) {
			$siteseo_social_twitter_card_thumb = apply_filters('siteseo_social_twitter_card_thumb', $siteseo_social_twitter_card_thumb);
		}
		if (isset($siteseo_social_twitter_card_thumb) && '' != $siteseo_social_twitter_card_thumb) {
			if ( ! is_404()) {
				echo wp_kses($siteseo_social_twitter_card_thumb, ['meta' => ['name' => true, 'content' => true]]) . "\n";
			}
		}
	}
}

add_action('wp_head', 'siteseo_social_twitter_img_hook', 1);
