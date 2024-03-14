<?php
/**
 * General search option page
 *
 * @author  YITH
 * @package YITH/Search/Options
 * @version 2.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


$general_tab = array(
	'general' => array(
		'section_general_settings'           => array(
			'name' => _x( 'Autocomplete', 'Admin section title', 'yith-woocommerce-ajax-search' ),
			'type' => 'title',
			'id'   => 'ywcas_general_options_settings',
		),
		'enable_autocomplete'                => array(
			'id'        => 'yith_wcas_enable_autocomplete',
			'name'      => _x( 'Enable autocomplete feature in search field', 'Admin option label', 'yith-woocommerce-ajax-search' ),
			'desc'      => _x( 'Enable to display a list of suggested queries automatically generated based on what the user types in the search form.', 'Admin option description', 'yith-woocommerce-ajax-search' ),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'yes',
		),
		'min_chars'                          => array(
			'id'        => 'yith_wcas_min_chars',
			'name'      => _x( 'Minimum number of characters to trigger autocomplete', 'Admin option label', 'yith-woocommerce-ajax-search' ),
			'desc'      => '',
			'type'      => 'yith-field',
			'yith-type' => 'number',
			'min'       => 1,
			'max'       => 5,
			'step'      => 1,
			'default'   => '3',
			'deps'      => array(
				'id'    => 'yith_wcas_enable_autocomplete',
				'value' => 'yes',
			),
		),
		'section_end_search_settings'        => array(
			'type' => 'sectionend',
			'id'   => 'ywcas_general_options_settings_end',
		),
		'section_trending'             => array(
			'name'        => _x( 'Suggested/Trending searches', 'Admin section label', 'yith-woocommerce-ajax-search' ),
			'type'        => 'title',
			'id'          => 'ywcas_section_trending_settings',
		),
		'trending_searches_source'     => array(
			'id'        => 'yith_wcas_trending_searches_source',
			'name'      => _x( 'Searches to suggest', 'Admin option label', 'yith-woocommerce-ajax-search' ),
			'desc'      => '',
			'type'      => 'yith-field',
			'yith-type' => 'select',
			'class'     => 'wc-enhanced-select',
			'options'   => array(
				'custom'  => _x( 'Specific keywords', 'Admin select option', 'yith-woocommerce-ajax-search' ),
				'popular' => _x( 'Popular searches', 'Admin select option', 'yith-woocommerce-ajax-search' ),

			),
			'default'   => 'popular',
			'is_option_disabled' => true,
			'option_tags'        => array( 'premium' ),
		),
		'trending_searches_keywords'   => array(
			'id'                => 'yith_wcas_trending_searches_keywords',
			'name'              => _x( 'Keywords', 'Admin option label', 'yith-woocommerce-ajax-search' ),
			'desc'              => __('Enter the keywords, comma separated, for the suggested searches.', 'yith-woocommerce-ajax-search' ),
			'type'              => 'yith-field',
			'yith-type'         => 'text',
			'custom_attributes' => array(
				'data-deps'       => 'yith_wcas_trending_searches_source',
				'data-deps_value' => 'custom',
				'placeholder'     => _x( 'E.g. iPhone 11, Air Max 1, Vintage dress with buttons', 'Admin option placeholder', 'yith-woocommerce-ajax-search' ),
			),
			'is_option_disabled' => true,
			'option_tags'        => array( 'premium' ),
		),
		'section_end_section_trending' => array(
			'type' => 'sectionend',
			'id'   => 'ywcas_section_trending_settings_end',
		),
		'section_search_fuzzy'               => array(
			'name'        => _x( 'Fuzzy strings & synonyms', 'Admin section label', 'yith-woocommerce-ajax-search' ),
			'type'        => 'title',
			'id'          => 'ywcas_search_fuzzy_settings',
		),
		'enable_search_fuzzy'                => array(
			'id'        => 'yith_wcas_enable_search_fuzzy',
			'name'      => _x( 'Enable fuzzy strings matching', 'Admin option label', 'yith-woocommerce-ajax-search' ),
			'desc'      => sprintf(
			// translators: placeholders are HTML tags.
				_x(
					'Enable to help users find relevant results even when the search terms are misspelled.%s
Example: “skrit” instead of “skirt”.',
					'Admin option description.sprintf',
					'yith-woocommerce-ajax-search'
				),
				'<br>'
			),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'no',
		),
		'fuzzy_level'                        => array(
			'id'                => 'yith_wcas_fuzzy_level',
			'name'              => _x( 'Fuzzy matching level', 'Admin option label', 'yith-woocommerce-ajax-search' ),
			// translators: placeholders are HTML tags.
			'desc'              => sprintf(
				// translators: Admin option description. Placeholders are HTML tags.
				_x( 'Choose the fuzzy matching level. %1$sNote: a 100%% level can produce a significant number of false positives.%1$sSuggested value: 50%%', 'Admin option description. Placeholders are HTML tags', 'yith-woocommerce-ajax-search' ),
				'<br/>'
			),
			'type'              => 'yith-field',
			'yith-type'         => 'ywcas-slider',
			'min'               => 0,
			'max'               => 100,
			'step'              => 25,
			'default'           => 50,
			'custom_attributes' => array(
				'data-deps'       => 'yith_wcas_enable_search_fuzzy',
				'data-deps_value' => 'yes',
			),

		),
		'synonymous'                         => array(
			'id'                => 'yith_wcas_synonymous',
			'name'              => _x( 'Synonyms', 'Admin option label', 'yith-woocommerce-ajax-search' ),
			'desc'              => sprintf(
			// translators: placeholders are HTML tags.
				_x(
					'Each field can include a list of synonymous words or concepts to help users easily find products with different name types. Use single words or phrases separated by commas.',
					'Admin option description. Placeholders are HTML tags',
					'yith-woocommerce-ajax-search'
				),
				'<strong>',
				'</strong>',
				'<br>'
			),
			'type'              => 'yith-field',
			'yith-type'         => 'ywcas-synonymous',
			'placeholder'       => _x( 'E.g. Pregnancy, Maternity, Nursing, Motherhood, Postpartum', 'Admin option placeholder', 'yith-woocommerce-ajax-search' ),
			'custom_attributes' => array(
				'data-deps'       => 'yith_wcas_enable_search_fuzzy',
				'data-deps_value' => 'yes',
			),
			'is_option_disabled' => true,
			'option_tags'        => array( 'premium' ),
		),
		'disable_fuzzy_message'              => array(
			'type'      => 'yith-field',
			'yith-type' => 'html',
			'html'      => ywcas_get_disable_field(),
		),
		'section_end_search_fuzzy'           => array(
			'type' => 'sectionend',
			'id'   => 'ywcas_search_history_settings_end',
		),
	),
);

/**
 * APPLY_FILTERS: ywcas_general_options_tab
 *
 * This filter allow to manage the general options tab
 *
 * @param array $general_tab List of options.
 *
 * @return array
 */
return apply_filters( 'ywcas_general_options_tab', $general_tab );
