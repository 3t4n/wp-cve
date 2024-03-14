<?php
/**
 * WP SEO Support.
 *
 * @package User Activity Log
 */

/*
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ual_wpseo_handle_import' ) ) {
	/**
	 * Fires when import data from other SEO plugins.
	 *
	 * @param string $import Import.
	 */
	function ual_wpseo_handle_import( $import ) {

		$post_wpseo = filter_input( INPUT_POST, 'wpseo', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$imports    = array(
			'importheadspace'   => 'HeadSpace2',
			'importaioseo'      => 'All-in-One SEO',
			'importaioseoold'   => 'OLD All-in-One SEO',
			'importwoo'         => 'WooThemes SEO framework',
			'importrobotsmeta'  => 'Robots Meta (by Yoast)',
			'importrssfooter'   => 'RSS Footer (by Yoast)',
			'importbreadcrumbs' => 'Yoast Breadcrumbs',
		);
		if ( isset( $post_wpseo ) ) {
			$obj_type = 'Yoast SEO';
			$post_id  = '';
			$action   = 'Import';

			foreach ( $imports as $key => $name ) {
				if ( isset( $post_wpseo[ $key ] ) ) {
					$delete     = isset( $post_wpseo['deleteolddata'] ) ? ', and deleted old data' : '';
					$post_title = 'Imported settings from ' . $name . ' ' . $delete;
					ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
				}
			}
		}
	}
}

add_action( 'wpseo_handle_import', 'ual_wpseo_handle_import', 10, 1 );

if ( ! function_exists( 'ual_wpseo_wpseo_import' ) ) {
	/**
	 * Fires when Export settings.
	 */
	function ual_wpseo_wpseo_import() {

		$opts     = filter_input( INPUT_POST, 'wpseo' );
		$obj_type = 'Yoast SEO';
		$post_id  = '';
		$action   = '';
		if ( filter_input( INPUT_POST, 'wpseo_export' ) ) {
			$post_title = 'Exported settings' . isset( $opts['include_taxonomy_meta'] ) ? ' , including taxonomy meta' : '';
		} elseif ( isset( $_FILES['settings_import_file']['name'] ) ) {
			$post_title = 'Importing settings from ' . sanitize_text_field( wp_unslash( $_FILES['settings_import_file']['name'] ) );
		}
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}

add_action( 'wpseo_import', 'ual_wpseo_wpseo_import' );


if ( ! function_exists( 'ual_wpseo_save_auth_code' ) ) {
	/**
	 * Fires when save authentication code.
	 */
	function ual_wpseo_save_auth_code() {
		$action     = 'save_auth_code';
		$obj_type   = 'Yoast SEO';
		$post_id    = '';
		$post_title = 'Save authentication code';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}

add_action( 'wp_ajax_wpseo_save_auth_code', 'ual_wpseo_save_auth_code' );

if ( ! function_exists( 'ualcheck_admin_referer' ) ) {
	/**
	 * Fires when Robots.txt update, htaccess file update, Import file in wpseo.
	 *
	 * @param string $action Action.
	 * @param string $result Result.
	 */
	function ualcheck_admin_referer( $action, $result ) {
		if ( 'wpseo-robotstxt' == $action || 'wpseo-htaccess' == $action || 'wpseo-import-file' == $action || 'wpseo_export' == $action ) {
			if ( 'wpseo-robotstxt' == $action ) {
				$post_title = 'Robots.txt file updated';
			}
			if ( 'wpseo-htaccess' == $action ) {
				$post_title = '.htaccess file updated';
			}
			if ( 'wpseo-import-file' == $action ) {
				$post_title = 'Import wpseo settings';
			}
			if ( 'wpseo_export' == $action ) {
				$post_title = 'Export wpseo settings';
			}
			$obj_type = 'Yoast SEO';
			$post_id  = '';
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}

add_action( 'check_admin_referer', 'ualcheck_admin_referer', 10, 2 );

add_action( 'updated_option', 'ual_wp_seo_updated_option', 10, 3 );

if ( ! function_exists( 'ual_wp_seo_updated_option' ) ) {
	/**
	 * Fires when WordPress seo settings updated.
	 *
	 * @param string $option Option.
	 * @param string $old_value Old Value.
	 * @param string $value Value.
	 */
	function ual_wp_seo_updated_option( $option, $old_value = null, $value = null ) {
		$old_value         = (array) $old_value;
		$seo_removed_array = array(
			'breadcrumbs-blog-remove',
			'hideeditbox-post',
			'hideeditbox-page',
			'hideeditbox-attachment',
			'hideeditbox-product',
			'hideeditbox-sol_portfolio',
			'hideeditbox-sol_project',
			'hideeditbox-tset',
			'hideeditbox-tax-category',
			'hideeditbox-tax-post_tag',
			'hideeditbox-tax-post_format',
			'hideeditbox-tax-product_cat',
			'hideeditbox-tax-product_tag',
			'hideeditbox-tax-product_shipping_class',
			'hideeditbox-tax-portfolio-category',
			'hideeditbox-tax-portfolio-tag',
			'hideeditbox-tax-sol_project_category',
		);
		foreach ( $seo_removed_array as $seo_removed_key ) {
			if ( isset( $old_value[ $seo_removed_key ] ) ) {
				unset( $old_value[ $seo_removed_key ] );
			}
		}
			$old_value       = apply_filters( 'ual_seo_update_array', $old_value, $option );
			$changed_options = array();
			$obj_type        = 'settings';
		if ( ual_is_option_group( $value ) ) {
			foreach ( ual_get_changed_keys( $old_value, $value ) as $field_key ) {
				if ( ! ual_is_key_ignored( $option, $field_key ) ) {
					$old_value_a = '';
					$new_value_a = '';
					if ( isset( $old_value[ $field_key ] ) && '' != $old_value[ $field_key ] ) {
						$old_value_a = (string) maybe_serialize( $old_value[ $field_key ] );
					}
					if ( isset( $value[ $field_key ] ) && '' != $value[ $field_key ] ) {
						$new_value_a = (string) maybe_serialize( $value[ $field_key ] );
					}
					if ( $old_value_a != $new_value_a ) {
						$changed_options[] = array(
							'title'         => ual_wpseo_settings_label( $option, $field_key ) ? ual_wpseo_settings_label( $option, $field_key ) : '',
							'parent_action' => $option,
							'action'        => $field_key,
							'object_type'   => $obj_type,
							'old_value'     => $old_value_a,
							'new_value'     => $new_value_a,
						);
					}
				}
			}
		} else {
			$changed_options[] = array(
				'title'       => ual_wpseo_settings_label( $option, '' ) ? ual_wpseo_settings_label( $option, '' ) : '',
				'action'      => $option,
				'object_type' => $obj_type,
				'old_value'   => (string) maybe_serialize( $old_value ),
				'new_value'   => (string) maybe_serialize( $value ),
			);
		}
		foreach ( $changed_options as $properties ) {
			if ( $properties['title'] ) {
				ual_get_activity_function( $properties['action'], $properties['object_type'], '', "'" . $properties['title'] . "' setting was updated" );

			}
		}
	}
}

if ( ! function_exists( 'ual_wpseo_settings_label' ) ) {
	/**
	 * Fires when WordPress seo settings label.
	 *
	 * @param string $option Option.
	 * @param string $field_key Field Key.
	 */
	function ual_wpseo_settings_label( $option, $field_key ) {

		$labels = array(

			'content_analysis_active'                => 'Readability analysis',
			'keyword_analysis_active'                => 'Keyword analysis',
			'enable_setting_pages'                   => 'Advanced settings pages',
			'onpage_indexability'                    => 'OnPage.org',
			'enable_admin_bar_menu'                  => 'Yoast SEO admin bar menu',
			'enable_cornerstone_content'             => 'Cornerstone content',
			'company_logo'                           => 'Company Logo',
			'company_name'                           => 'Company Name',
			'company_or_person'                      => 'Company or person',
			'person_name'                            => 'Person name',
			'website_name'                           => 'Website name',
			'alternate_website_name'                 => 'Alternate Website name',
			'site_type'                              => 'Site type',
			'has_multiple_authors'                   => 'Multiple author?',
			'environment_type'                       => 'Environment type',
			'wpseo_sitemap_cache_validator_global'   => 'Sitemap cache validator',

			'wpseo_dashboard'                        => 'Dashboard',
			'wpseo_titles'                           => 'Titles &amp; Metas',
			'wpseo_social'                           => 'Social',
			'wpseo_xml'                              => 'XML Sitemaps',
			'wpseo_permalinks'                       => 'Permalinks',
			'wpseo_internal-links'                   => 'Internal Links',
			'wpseo_advanced'                         => 'Advanced',
			'wpseo_rss'                              => 'RSS',
			'wpseo_import'                           => 'Import & Export',
			'wpseo_bulk-title-editor'                => 'Bulk Title Editor',
			'wpseo_bulk-description-editor'          => 'Bulk Description Editor',
			'wpseo_files'                            => 'Files',
			'wpseo_meta'                             => 'Content',
			'importheadspace'                        => 'HeadSpace2',
			'importaioseo'                           => 'All-in-One SEO',
			'importaioseoold'                        => 'OLD All-in-One SEO',
			'importwoo'                              => 'WooThemes SEO framework',
			'importrobotsmeta'                       => 'Robots Meta (by Yoast)',
			'importrssfooter'                        => 'RSS Footer (by Yoast)',
			'importbreadcrumbs'                      => 'Yoast Breadcrumbs',
			'yoast_tracking'                         => "Allow tracking of this WordPress install's anonymous data.",
			'disableadvanced_meta'                   => 'Disable the Advanced part of the WordPress SEO meta box',
			'alexaverify'                            => 'Alexa Verification ID',
			'msverify'                               => 'Bing Webmaster Tools',
			'googleverify'                           => 'Google Webmaster Tools',
			'pinterestverify'                        => 'Pinterest',
			'yandexverify'                           => 'Yandex Webmaster Tools',
			// wp-content/plugins/wordpress-seo/admin/pages/advanced.php.
			'breadcrumbs-enable'                     => 'Enable Breadcrumbs',
			'breadcrumbs-sep'                        => 'Separator between breadcrumbs',
			'breadcrumbs-home'                       => 'Anchor text for the Homepage',
			'breadcrumbs-prefix'                     => 'Prefix for the breadcrumb path',
			'breadcrumbs-archiveprefix'              => 'Prefix for Archive breadcrumbs',
			'breadcrumbs-searchprefix'               => 'Prefix for Search Page breadcrumbs',
			'breadcrumbs-404crumb'                   => 'Breadcrumb for 404 Page',
			'breadcrumbs-blog-remove'                => 'Remove Blog page from Breadcrumbs',
			'breadcrumbs-boldlast'                   => 'Bold the last page in the breadcrumb',
			'post_types-post-maintax'                => 'Taxonomy to show in breadcrumbs for post types',
			// wp-content/plugins/wordpress-seo/admin/pages/metas.php.
			'forcerewritetitle'                      => 'Force rewrite titles',
			'noindex-subpages-wpseo'                 => 'Noindex subpages of archives',
			'usemetakeywords'                        => 'Use meta keywords tag?',
			'noodp'                                  => 'Add noodp meta robots tag sitewide',
			'noydir'                                 => 'Add noydir meta robots tag sitewide',
			'hide-rsdlink'                           => 'Hide RSD Links',
			'hide-wlwmanifest'                       => 'Hide WLW Manifest Links',
			'hide-shortlink'                         => 'Hide Shortlink for posts',
			'hide-feedlinks'                         => 'Hide RSS Links',
			'disable-author'                         => 'Disable the author archives',
			'disable-date'                           => 'Disable the date-based archives',
			// wp-content/plugins/wordpress-seo/admin/pages/network.php.
			'access'                                 => 'Who should have access to the WordPress SEO settings',
			'defaultblog'                            => 'New blogs get the SEO settings from this blog',
			'restoreblog'                            => 'Blog ID',
			// wp-content/plugins/wordpress-seo/admin/pages/permalinks.php.
			'stripcategorybase'                      => 'Strip the category base (usually /category/) from the category URL.',
			'trailingslash'                          => "Enforce a trailing slash on all category and tag URL's",
			'cleanslugs'                             => 'Remove stop words from slugs.',
			'redirectattachment'                     => "Redirect attachment URL's to parent post URL.",
			'cleanreplytocom'                        => 'Remove the ?replytocom variables.',
			'cleanpermalinks'                        => "Redirect ugly URL's to clean permalinks. (Not recommended in many cases!)",
			'force_transport'                        => 'Force Transport',
			'cleanpermalink-googlesitesearch'        => "Prevent cleaning out Google Site Search URL's.",
			'cleanpermalink-googlecampaign'          => 'Prevent cleaning out Google Analytics Campaign & Google AdWords Parameters.',
			'cleanpermalink-extravars'               => 'Other variables not to clean',
			// wp-content/plugins/wordpress-seo/admin/pages/social.php.
			'opengraph'                              => 'Add Open Graph meta data',
			'facebook_site'                          => 'Facebook Page URL',
			'instagram_url'                          => 'Instagram URL',
			'linkedin_url'                           => 'LinkedIn URL',
			'myspace_url'                            => 'MySpace URL',
			'pinterest_url'                          => 'Pinterest URL',
			'youtube_url'                            => 'YouTube URL',
			'google_plus_url'                        => 'Google+ URL',
			'og_frontpage_image'                     => 'Image URL',
			'og_frontpage_desc'                      => 'Description',
			'og_frontpage_title'                     => 'Title',
			'og_default_image'                       => 'Image URL',
			'twitter'                                => 'Add Twitter card meta data',
			'twitter_site'                           => 'Site Twitter Username',
			'twitter_card_type'                      => 'The default card type to use',
			'googleplus'                             => 'Add Google+ specific post meta data (excluding author metadata)',
			'plus-publisher'                         => 'Google Publisher Page',
			'fbadminapp'                             => 'Facebook App ID',
			// wp-content/plugins/wordpress-seo/admin/pages/xml-sitemaps.php.
			'enablexmlsitemap'                       => 'Check this box to enable XML sitemap functionality.',
			'disable_author_sitemap'                 => 'Disable author/user sitemap',
			'disable_author_noposts'                 => 'Users with zero posts',
			'user_role-administrator-not_in_sitemap' => 'Filter specific user roles - Administrator',
			'user_role-editor-not_in_sitemap'        => 'Filter specific user roles - Editor',
			'user_role-author-not_in_sitemap'        => 'Filter specific user roles - Author',
			'user_role-contributor-not_in_sitemap'   => 'Filter specific user roles - Contributor',
			'user_role-subscriber-not_in_sitemap'    => 'Filter specific user roles - Subscriber',
			'xml_ping_yahoo'                         => 'Ping Yahoo!',
			'xml_ping_ask'                           => 'Ping Ask.com',
			'entries-per-page'                       => 'Max entries per sitemap page',
			'excluded-posts'                         => 'Posts to exclude',
			'post_types-post-not_in_sitemap'         => 'Post Types Posts (post)',
			'post_types-page-not_in_sitemap'         => 'Post Types Pages (page)',
			'post_types-attachment-not_in_sitemap'   => 'Post Types Media (attachment)',
			'taxonomies-category-not_in_sitemap'     => 'Taxonomies Categories (category)',
			'taxonomies-post_tag-not_in_sitemap'     => 'Taxonomies Tags (post_tag)',
			// Added manually.
			'rssbefore'                              => 'Content to put before each post in the feed',
			'rssafter'                               => 'Content to put after each post',
		);

		$prelabel     = array(
			'title-'        => 'Title template',
			'metadesc-'     => 'Meta description template',
			'metakey-'      => 'Meta keywords template',
			'noindex-'      => 'Meta Robots',
			'noauthorship-' => 'Authorship',
			'showdate-'     => 'Show date in snippet preview?',
			'hideeditbox-'  => 'WordPress SEO Meta Box',
			'bctitle-'      => 'Breadcrumbs Title',
			'post_types-'   => 'Post types',
			'taxonomies-'   => 'Taxonomies',
		);
		$option_label = '';
		if ( array_key_exists( $field_key, $labels ) ) {
			$option_label = $labels[ $field_key ];
		} else {
			foreach ( $prelabel as $key => $val ) {
				if ( 0 === strpos( $field_key, $key ) ) {
					$option_label = $val;
				}
			}
		}
		return $option_label;
	}
}

if ( ! function_exists( 'ual_is_option_group' ) ) {
	/**
	 * Fires when WordPress seo settings label.
	 *
	 * @param string $value Value.
	 */
	function ual_is_option_group( $value ) {
		if ( ! is_array( $value ) ) {
			return false;
		}

		if ( 0 === count( array_filter( array_keys( $value ), 'is_string' ) ) ) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'ual_is_key_ignored' ) ) {
	/**
	 * Fires when WordPress seo settings label.
	 *
	 * @param string $option_name Option Name.
	 * @param string $key Key.
	 */
	function ual_is_key_ignored( $option_name, $key ) {
		$ignored = array(
			'theme_mods' => array(
				'background_image_thumb',
				'header_image_data',
			),
		);

		if ( isset( $ignored[ $option_name ] ) ) {
			return in_array( $key, $ignored[ $option_name ], true );
		}

		return false;
	}
}

if ( ! function_exists( 'ual_get_changed_keys' ) ) {
	/**
	 * Fires when WordPress seo settings label.
	 *
	 * @param string $old_value Old Value.
	 * @param string $new_value New Value.
	 * @param bool   $deep Deep.
	 */
	function ual_get_changed_keys( $old_value, $new_value, $deep = false ) {
		if ( ! is_array( $old_value ) && ! is_array( $new_value ) ) {
			return array();
		}
		if ( ! is_array( $old_value ) ) {
			return array_keys( $new_value );
		}
		if ( ! is_array( $new_value ) ) {
			return array_keys( $old_value );
		}
		$diff            = array_udiff_assoc(
			$old_value,
			$new_value,
			function( $value1, $value2 ) {
				return maybe_serialize( $value1 ) !== maybe_serialize( $value2 );
			}
		);
		$result          = array_keys( $diff );
		$common_keys     = array_keys( array_intersect_key( $old_value, $new_value ) );
		$unique_keys_old = array_values( array_diff( array_keys( $old_value ), $common_keys ) );
		$unique_keys_new = array_values( array_diff( array_keys( $new_value ), $common_keys ) );
		$result          = array_merge( $result, $unique_keys_old, $unique_keys_new );
		$result          = array_filter(
			$result,
			function( $value ) {
				return (string) (int) $value !== (string) $value;
			}
		);
		$result          = array_values( array_unique( $result ) );
		if ( false === $deep ) {
			return $result;
		}
		$result = array_fill_keys( $result, null );
		foreach ( $result as $key => $val ) {
			if ( in_array( $key, $unique_keys_old, true ) ) {
				$result[ $key ] = false;
			} elseif ( in_array( $key, $unique_keys_new, true ) ) {
				$result[ $key ] = true;
			} elseif ( $deep ) {
				if ( is_array( $old_value[ $key ] ) && is_array( $new_value[ $key ] ) ) {
					$inner  = array();
					$parent = $key;
					$deep--;
					$changed = ual_get_changed_keys( $old_value[ $key ], $new_value[ $key ], $deep );
					foreach ( $changed as $child => $change ) {
						$inner[ $parent . '::' . $child ] = $change;
					}
					$result[ $key ] = 0;
					$result         = array_merge( $result, $inner );
				}
			}
		}
		return $result;
	}
}

if ( ! function_exists( 'ual_wpseo_postmeta' ) ) {
	/**
	 * Fires when post meta updated.
	 *
	 * @param int    $meta_id Meta ID.
	 * @param int    $object_id Object ID.
	 * @param string $meta_key Meta Key.
	 * @param string $meta_value Meta Value.
	 */
	function ual_wpseo_postmeta( $meta_id, $object_id, $meta_key, $meta_value ) {

			$obj_type = 'Yoast SEO';
			$hook     = 'updated_post_meta';
			$action   = 'Updated';

			$prefix = \WPSEO_Meta::$meta_prefix;

			\WPSEO_Metabox::translate_meta_boxes();

		if ( 0 !== strpos( $meta_key, $prefix ) ) {
			return;
		}

			$key = str_replace( $prefix, '', $meta_key );

		foreach ( \WPSEO_Meta::$meta_fields as $tab => $fields ) {
			if ( isset( $fields[ $key ] ) ) {
				$field = $fields[ $key ];
				break;
			}
		}

		if ( ! isset( $field, $field['title'], $tab ) || '' === $field['title'] ) {
			return;
		}

			$post            = get_post( $object_id );
			$post_type_label = get_post_type_labels( get_post_type_object( $post->post_type ) )->singular_name;

			$post_id    = $post->ID;
			$meta_title = $field['title'];
			$post_title = $action . ' ' . $meta_title . ' of ' . get_the_title( $post_id ) . ' ' . $post_type_label;
		if ( isset( $post_type_label ) ) {
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}

	}
}

add_action( 'added_post_meta', 'ual_wpseo_postmeta', 10, 4 );
add_action( 'updated_post_meta', 'ual_wpseo_postmeta', 10, 4 );
add_action( 'deleted_post_meta', 'ual_wpseo_postmeta', 10, 4 );
