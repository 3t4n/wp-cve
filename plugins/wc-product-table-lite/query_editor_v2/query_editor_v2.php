<?php
// enqueue css and js
function wcpt_qv2_admin_enqueue()
{
  if (!isset($_GET['page']) || !in_array($_GET['page'], array('wcpt-edit', 'wcpt-settings')))
    return;

  wp_enqueue_style('wcpt_qv2', WCPT_PLUGIN_URL . 'query_editor_v2/query_editor_v2.css', [], WCPT_VERSION);
  wp_register_script('wcpt_qv2', WCPT_PLUGIN_URL . 'query_editor_v2/query_editor_v2.js', ['wcpt-controller'], WCPT_VERSION, true);
  wp_enqueue_script('wcpt_qv2');

  wp_localize_script(
    'wcpt_qv2',
    'wcpt_qv2_params',
    array(
      'apiUrl' => rest_url('wcpt_qv2/v1/'),
      'nonce' => wp_create_nonce('wp_rest'),
      'taxonomies' => wcpt_qv2_get_product_taxonomies(),
      'authors' => wcpt_qv2_get_product_authors(),
      'isPro' => defined('WCPT_PRO'),
    )
  );
}
add_action('admin_enqueue_scripts', 'wcpt_qv2_admin_enqueue');

// reset qv2
add_action('admin_init', 'wcpt_qv2_reset');
function wcpt_qv2_reset()
{
  if (
    isset($_GET['post_type']) && $_GET['post_type'] === 'wc_product_table' &&
    isset($_GET['page']) && $_GET['page'] === 'wcpt-edit' &&
    isset($_GET['post_id'])
    && isset($_GET['qv2']) && $_GET['qv2'] === "false"
  ) {
    // provide new ids to avoid conflicts
    if ($table_data = get_post_meta($_GET['post_id'], 'wcpt_data', true)) {
      $table_data = json_decode($table_data, true);
      $table_data['query_v2'] = false;
      update_post_meta($_GET['post_id'], 'wcpt_data', addslashes(json_encode($table_data)));
    }
  }
}

// transform
/* @TODO - conversions need to be simplified 
 * ✓ Query -> Query_v2 -> Shortcode
 * ✕ Query -> Query_v2 -> Query should not be required
 */
const WCPT_QV2_PARAMS = [
  "key_conversions" => [
    "category" => "categoryIds",
    "limit" => "limit",
    "orderby" => "orderBy",
    "order" => "order",
    "offset" => "offset",
    "paginate" => "paginate",

    'orderby_ignore_category' => 'orderByCategoryExcludeTermId',
    'orderby_focus_category' => 'orderByCategoryIncludeTermId',

    "orderby_attribute" => "orderByAttributeSlug",
    'orderby_ignore_attribute_term' => 'orderByAttributeExcludeTermId',
    'orderby_focus_attribute_term' => 'orderByAttributeIncludeTermId',
    "orderby_attribute_include_all" => "orderByAttributeIncludeAll",

    "orderby_taxonomy" => "orderByTaxonomySlug",
    'orderby_ignore_taxonomy_term' => 'orderByTaxonomyExcludeTermId',
    'orderby_focus_taxonomy_term' => 'orderByTaxonomyIncludeTermId',
    "orderby_taxonomy_include_all" => "orderByTaxonomyIncludeAll",

    "secondary_orderby" => "secondaryOrderby",
    "secondary_order" => "secondaryOrder",

    "meta_key" => "orderByCustomFieldName",
    "additional_query_args" => "additionalQueryArgs",
    "ids" => "ids",
    "skus" => "skus",

    "lazy_load" => "lazyLoad",
  ],
  "orderby_value_conversions" => [
    "menu_order" => "menuOrder",
    "rating" => "averageRating",
    "price-desc" => "price",
    "rand" => "random",
    "attribute" => "attributeAsText",
    "attribute_num" => "attributeAsNumber",
    "meta_value" => "customFieldAsText",
    "meta_value_num" => "customFieldAsNumber",
    "sku" => "skuAsText",
    "sku_num" => "skuAsNumber",
  ]
];

// -- edit
add_filter("wcpt_data", "wcpt_qv2_get_values_from_original_editor", 10, 2);
function wcpt_qv2_get_values_from_original_editor($table_data, $context)
{
  if ($context !== "edit") {
    return $table_data;
  }

  if (empty($table_data['query_v2'])) {

    $table_data['query_v2'] = array(
      "showProOptions" => true,
    );

    // do simple conversions
    foreach ($table_data['query'] as $key => $val) {
      if (isset(WCPT_QV2_PARAMS['key_conversions'][$key])) {
        $table_data['query_v2'][WCPT_QV2_PARAMS['key_conversions'][$key]] = $val;
      }
    }

    // skus
    if (!empty($table_data['query']['skus'])) {
      $table_data['query_v2']['skus'] = array_map("trim", explode(",", $table_data['query']['skus']));
    }

    // ids
    if (!empty($table_data['query']['ids'])) {
      $table_data['query_v2']['ids'] = array_map(function ($value) {
        return (int) trim($value);
      }, explode(",", $table_data['query']['ids']));
    }

    // stock status
    if (!empty($table_data['query']['hide_out_of_stock_items'])) {
      $table_data['query_v2']['stockStatus'] = array("instock", "onbackorder");
    }

    // order by
    // -- category
    // -- -- focus
    if (!empty($table_data['query']['orderby_focus_category'])) {
      $table_data['query_v2']['orderByCategoryIncludeTermId'] = wcpt_qv2_convert_slugs_string_to_ids_array($table_data['query']['orderby_focus_category'], 'product_cat');
    }
    // -- -- ignore
    if (!empty($table_data['query']['orderby_ignore_category'])) {
      $table_data['query_v2']['orderByCategoryExcludeTermId'] = wcpt_qv2_convert_slugs_string_to_ids_array($table_data['query']['orderby_ignore_category'], 'product_cat');
    }

    // -- attribute
    // -- attribute_num

    if (!empty($table_data['query']['orderby_attribute'])) {
      // -- -- focus
      if (!empty($table_data['query']['orderby_focus_attribute_term'])) {
        $table_data['query_v2']['orderByAttributeIncludeTermId'] = wcpt_qv2_convert_slugs_string_to_ids_array($table_data['query']['orderby_focus_attribute_term'], $table_data['query']['orderby_attribute']);
      }
      // -- -- ignore
      if (!empty($table_data['query']['orderby_ignore_attribute_term'])) {
        $table_data['query_v2']['orderByAttributeExcludeTermId'] = wcpt_qv2_convert_slugs_string_to_ids_array($table_data['query']['orderby_ignore_attribute_term'], $table_data['query']['orderby_attribute']);
      }
    }

    // -- taxonomy

    if (!empty($table_data['query']['orderby_taxonomy'])) {
      // -- -- focus
      if (!empty($table_data['query']['orderby_focus_taxonomy_term'])) {
        $table_data['query_v2']['orderByTaxonomyIncludeTermId'] = wcpt_qv2_convert_slugs_string_to_ids_array($table_data['query']['orderby_focus_taxonomy_term'], $table_data['query']['orderby_taxonomy']);
      }
      // -- -- ignore
      if (!empty($table_data['query']['orderby_ignore_taxonomy_term'])) {
        $table_data['query_v2']['orderByTaxonomyExcludeTermId'] = wcpt_qv2_convert_slugs_string_to_ids_array($table_data['query']['orderby_ignore_taxonomy_term'], $table_data['query']['orderby_taxonomy']);
      }
    }

    // -- convert orderby values
    foreach (WCPT_QV2_PARAMS["orderby_value_conversions"] as $originalVal => $convertedVal) {
      if ($table_data['query']['orderby'] === $originalVal) {
        $table_data['query_v2']['orderBy'] = $convertedVal;
      }
    }

    // -- price order
    if ($table_data['query']['orderby'] === "price") {
      $table_data['query_v2']['order'] = "ASC";
    } else if ($table_data["query"]["orderby"] === "price-desc") {
      $table_data['query_v2']['order'] = "DESC";
    }

  }

  return $table_data;
}

function wcpt_qv2_convert_slugs_string_to_ids_array($slugs, $taxonomy)
{
  $ids = array();
  $slugs = explode("\n", $slugs);
  foreach ($slugs as $slug) {
    $term = get_term_by('slug', $slug, $taxonomy);
    if ($term) {
      $ids[] = $term->term_id;
    }
  }
  return $ids;
}

// -- view

// -- -- send values from $table_data['query_v2'] to $table_data['query']
add_filter("wcpt_data", "wcpt_query_editor_v2_return_values_to_query", 10, 2);
function wcpt_query_editor_v2_return_values_to_query($table_data, $context)
{

  if (
    $context !== "view" ||
    empty($table_data['query_v2'])
  ) {
    return $table_data;
  }

  $flipped_key_conversions = array_flip(WCPT_QV2_PARAMS['key_conversions']);
  foreach ($table_data['query_v2'] as $key => $val) {
    if (isset($flipped_key_conversions[$key])) {
      $table_data['query'][$flipped_key_conversions[$key]] = $val;
    }
  }

  // order by
  if (!empty($table_data['query_v2']['orderBy'])) {

    // -- convert focus / ignore term slugs to ids
    switch ($table_data['query_v2']['orderBy']) {

      case 'category':
        // focus
        if (!empty($table_data['query_v2']['orderByCategoryIncludeTermId'])) {
          $table_data['query']['orderby_focus_category'] = wcpt_qv2_convert_ids_array_to_slugs_string($table_data['query_v2']['orderByCategoryIncludeTermId'], 'product_cat');
        }
        // ignore
        if (!empty($table_data['query_v2']['orderByCategoryExcludeTermId'])) {
          $table_data['query']['orderby_ignore_category'] = wcpt_qv2_convert_ids_array_to_slugs_string($table_data['query_v2']['orderByCategoryExcludeTermId'], 'product_cat');
        }
        break;

      case 'attributeAsNumber':
      case 'attributeAsText':

        if (!empty($table_data['query_v2']['orderByAttributeSlug'])) {

          // focus
          if (!empty($table_data['query_v2']['orderByAttributeIncludeTermId'])) {
            $table_data['query']['orderby_focus_attribute_term'] = wcpt_qv2_convert_ids_array_to_slugs_string($table_data['query_v2']['orderByAttributeIncludeTermId'], $table_data['query_v2']['orderByAttributeSlug']);
          }
          // ignore
          if (!empty($table_data['query_v2']['orderByAttributeExcludeTermId'])) {
            $table_data['query']['orderby_ignore_attribute_term'] = wcpt_qv2_convert_ids_array_to_slugs_string($table_data['query_v2']['orderByAttributeExcludeTermId'], $table_data['query_v2']['orderByAttributeSlug']);
          }
          break;
        }


      case 'taxonomy':

        if (!empty($table_data['query_v2']['orderByTaxonomySlug'])) {
          // focus
          if (!empty($table_data['query_v2']['orderByTaxonomyIncludeTermId'])) {

            $table_data['query']['orderby_focus_taxonomy_term'] = wcpt_qv2_convert_ids_array_to_slugs_string($table_data['query_v2']['orderByTaxonomyIncludeTermId'], $table_data['query_v2']['orderByTaxonomySlug']);
          }
          // ignore
          if (!empty($table_data['query_v2']['orderByTaxonomyExcludeTermId'])) {

            $table_data['query']['orderby_ignore_taxonomy_term'] = wcpt_qv2_convert_ids_array_to_slugs_string($table_data['query_v2']['orderByTaxonomyExcludeTermId'], $table_data['query_v2']['orderByTaxonomySlug']);
          }

        }

        break;
    }


    // -- convert orderby values
    $flipped_orderby_value_conversions = array_flip(WCPT_QV2_PARAMS["orderby_value_conversions"]);
    foreach ($flipped_orderby_value_conversions as $originalVal => $convertedVal) {
      if ($table_data['query_v2']['orderBy'] === $originalVal) {
        $table_data['query']['orderby'] = $convertedVal;
      }
    }

    // -- price order
    if ($table_data['query_v2']['orderBy'] === "price") {
      if (!empty($table_data['query_v2']['order'])) {
        if ($table_data['query_v2']['order'] === "ASC") {
          $table_data['query']['orderby'] = "price";
        } else if ($table_data['query_v2']['order'] === "DESC") {
          $table_data['query']['orderby'] = "price-desc";
        }
      }
    }

  }

  // posts per page
  $table_data['query']['limit'] = !empty($table_data['query_v2']['limit']) ? $table_data['query_v2']['limit'] : 10;

  return $table_data;
}

// -- add shortcode attributes based on query_v2
add_filter('wcpt_before_parse_attributes', 'wcpt_qv2_inset_shortcode_attributes');
function wcpt_qv2_inset_shortcode_attributes($sc_attrs = [])
{
  $table_data = wcpt_get_table_data();

  $query_v2 =& $table_data['query_v2'];

  // -- exclude products by category
  if (!empty($query_v2['excludeCategoryIds'])) {
    if (empty($sc_attrs['exclude_category'])) {
      $sc_attrs['exclude_category'] = wcpt_qv2_convert_ids_array_to_slugs_string($query_v2['excludeCategoryIds'], 'product_cat', ", ");
    }
  }

  // -- group by category on devices
  if (!empty($query_v2['groupByCategoryOnDevices'])) {
    foreach ($query_v2['groupByCategoryOnDevices'] as $device) {
      if (empty($sc_attrs["{$device}_group_by_category"])) {
        $sc_attrs["{$device}_group_by_category"] = "true";
      }
    }
  }

  // -- infinite scroll on devices
  if (!empty($query_v2['infiniteScrollOnDevices'])) {
    foreach ($query_v2['infiniteScrollOnDevices'] as $device) {
      if (empty($sc_attrs["{$device}_infinite_scroll"])) {
        $sc_attrs["{$device}_infinite_scroll"] = "true";
      }
    }
  }

  // -- ids
  if (!empty($query_v2['ids'])) {
    if (empty($sc_attrs["ids"])) {
      $sc_attrs["ids"] = implode(",", $query_v2['ids']);
    }
  }

  // -- exclude ids
  if (!empty($query_v2['excludeIds'])) {
    if (empty($sc_attrs["exclude_ids"])) {
      $sc_attrs["exclude_ids"] = implode(",", $query_v2['excludeIds']);
    }
  }

  // -- skus
  if (!empty($query_v2['skus'])) {
    if (empty($sc_attrs["skus"])) {
      $sc_attrs["skus"] = implode(",", $query_v2['skus']);
    }
  }

  // -- exclude skus
  if (!empty($query_v2['excludeSkus'])) {
    if (empty($sc_attrs["excludeSkus"])) {
      $sc_attrs["exclude_skus"] = implode(",", $query_v2['excludeSkus']);
    }
  }

  // -- order by
  if (!empty($query_v2['orderBy'])) {
    if (empty($sc_attrs["orderBy"])) {
      if ($query_v2['orderBy'] === "enteredIds") {
        $sc_attrs["orderby"] = "ids";
      } else if ($query_v2['orderBy'] === "enteredSkus") {
        $sc_attrs["orderby"] = "skus";
      }
    }
  }

  // -- secondary orderby
  foreach (array("secondary_orderby" => "secondaryOrderBy", "secondary_order" => "secondaryOrder", "secondary_custom_field" => "secondaryOrderByCustomFieldName") as $sc_key => $qv2_key) {
    if (!empty($query_v2[$qv2_key])) {
      if (empty($sc_attrs[$sc_key])) {
        $sc_attrs[$sc_key] = $query_v2[$qv2_key];
      }
    }
  }

  // -- convert secondary orderby values
  if (!empty($query_v2["secondaryOrderBy"])) {
    foreach (WCPT_QV2_PARAMS["orderby_value_conversions"] as $normal_val => $qv2_val) {
      if ($table_data['query_v2']['secondaryOrderBy'] === $qv2_val) {
        if ($normal_val === "price-desc") {
          $sc_attrs['secondary_orderby'] = "price";
        } else {
          $sc_attrs['secondary_orderby'] = $normal_val;
        }
      }
    }
  }

  // -- multiple
  $arr = array(
    "include_hidden" => "includeHidden",
    "include_private" => "includePrivate",
    "min_price" => "minPrice",
    "max_price" => "maxPrice",
    "show_upsells" => "showUpsells",
    "show_cross_sells" => "showCrossSells",
    "show_related_products" => "showRelatedProducts",
    "product_variations" => "enableVariationTable",
    "variation_skus" => "variationSkus",
    "stock_status" => "stockStatus",
    "featured" => "showFeatured",
    "on_sale" => "showOnSale",
    "show_recently_viewed" => "showRecentlyViewed",
    "product_type" => "productType",
    "exclude_product_type" => "excludeProductType",
    "show_previous_orders" => "previouslyOrdered",
    "hide_previous_orders" => "excludePreviouslyOrdered",
    "category_required" => "categoryRequired",
    "category_required_message" => "categoryRequiredMessage",
    "attribute_required_message" => "attributeRequiredMessage",
    "filter_required" => "filterRequired",
    "filter_required_message" => "filterRequiredMessage",
    "use_default_search" => "useDefaultSearch",
    "search_orderby" => "searchOrderby",
    "instant_search" => "instantSearch",
    "instant_sort" => "instantSort",
    "author_id" => "authorId",
    "exclude_author_id" => "excludeAuthorId",
    "disable_ajax" => "disableAjax",
    "disable_url_update" => "disableUrlUpdate",
    "grouped_product_ids" => "groupedProductIds",
    "no_results_message" => "noResultsMessage",
    "additional_query_args" => "additionalQueryArgs",
    "lazy_load" => "lazyLoad",
    "dynamic_hide_filters" => "dynamicHideFilters",
    "dynamic_recount" => "dynamicRecount",
  );
  foreach ($arr as $sc_key => $qv2_key) {
    if (!empty($query_v2[$qv2_key])) {
      if (empty($sc_attrs[$sc_key])) {
        $sc_attrs[$sc_key] = $query_v2[$qv2_key] === true ? "true" : (is_array($query_v2[$qv2_key]) ? implode(",", $query_v2[$qv2_key]) : $query_v2[$qv2_key]);
      }
    }
  }

  // -- include products by tag
  if (!empty($query_v2['tagIds'])) {
    if (empty($sc_attrs['tags'])) {
      $sc_attrs['tags'] = wcpt_qv2_convert_ids_array_to_slugs_string($query_v2['tagIds'], 'product_tag', ", ");
    }
  }

  // -- exclude products by tag
  if (!empty($query_v2['excludeTagIds'])) {
    if (empty($sc_attrs['exclude_tags'])) {
      $sc_attrs['exclude_tags'] = wcpt_qv2_convert_ids_array_to_slugs_string($query_v2['excludeTagIds'], 'product_tag', ", ");
    }
  }

  // -- attribute rules

  // -- -- include
  if (!empty($query_v2['attributeRules'])) {
    if (empty($sc_attrs['attribute'])) {
      $sc_attrs['attribute'] = wcpt_qv2_convert_taxonomy_rules_to_string($query_v2['attributeRules']);
    }
  }

  // -- -- exclude
  if (!empty($query_v2['excludeAttributeRules'])) {
    if (empty($sc_attrs['exclude_attribute'])) {
      $sc_attrs['exclude_attribute'] = wcpt_qv2_convert_taxonomy_rules_to_string($query_v2['excludeAttributeRules']);
    }
  }

  // -- taxonomy rules

  // -- -- include
  if (!empty($query_v2['taxonomyRules'])) {
    if (empty($sc_attrs['taxonomy'])) {
      $sc_attrs['taxonomy'] = wcpt_qv2_convert_taxonomy_rules_to_string($query_v2['taxonomyRules']);
    }
  }

  // -- -- exclude
  if (!empty($query_v2['excludeTaxonomyRules'])) {
    if (empty($sc_attrs['exclude_taxonomy'])) {
      $sc_attrs['exclude_taxonomy'] = wcpt_qv2_convert_taxonomy_rules_to_string($query_v2['excludeTaxonomyRules']);
    }
  }

  // -- custom field rules
  if (!empty($query_v2['customFieldRules'])) {
    if (empty($sc_attrs['custom_field'])) {
      $sc_attrs['custom_field'] = wcpt_qv2_convert_custom_field_rules_to_string($query_v2['customFieldRules']);
    }
  }

  // -- attribute required
  if (!empty($query_v2['attributeRequired'])) {
    if (empty($sc_attrs['attribute_required'])) {
      $sc_attrs['attribute_required'] = !empty($query_v2['attributeRequiredSlugs']) ? implode(',', $query_v2['attributeRequiredSlugs']) : "true";
    }
  }

  return $sc_attrs;
}


function wcpt_qv2_convert_custom_field_rules_to_string($array = array())
{
  $result = [];

  foreach ($array as $item) {
    if (
      empty($item['keyName']) ||
      empty($item['operator'])
    ) {
      continue;
    }
    $keyName = $item['keyName'];
    $operator = $item['operator'];
    $values = !empty($item['values']) ? implode(', ', $item['values']) : "";

    switch ($operator) {
      case 'IN':
        $result[] = "$keyName: $values";
        break;
      case 'NOT IN':
        $result[] = "$keyName: *NOT IN* $values";
        break;
      case 'BETWEEN':
        if (
          !empty($item['minValue']) &&
          !empty($item['maxValue'])
        ) {
          $result[] = "$keyName: *BETWEEN* {$item['minValue']}, {$item['maxValue']}";
        }
        break;
      case 'LIKE':
        $result[] = "$keyName: *LIKE* $values";
        break;
      case 'EXISTS':
        $result[] = "$keyName: *EXISTS*";
        break;
      case 'NOT EXISTS':
        $result[] = "$keyName: *NOT EXISTS*";
        break;
      case 'NOT EMPTY':
        $result[] = "$keyName: *NOT EMPTY*";
        break;
    }
  }

  return implode(' | ', $result);
}

function wcpt_qv2_convert_taxonomy_rules_to_string($rules)
{
  $taxonomy_strings = array();

  foreach ($rules as $query) {
    $term_names = array();
    foreach ($query['termIds'] as $term_id) {
      $term = get_term_by('id', $term_id, $query['taxonomySlug']);
      if ($term) {
        $term_names[] = $term->name;
      }
    }
    $taxonomy_strings[] = $query['taxonomySlug'] . ': ' . implode(', ', $term_names);
  }

  return implode(' | ', $taxonomy_strings);
}

function wcpt_qv2_convert_ids_array_to_slugs_string($ids, $taxonomy, $delimiter = "\n")
{
  $slugs = array();
  foreach ($ids as $id) {
    $term = get_term_by('id', $id, $taxonomy);
    if ($term) {
      $slugs[] = $term->slug;
    }
  }
  return implode($delimiter, $slugs);
}

function wctp_qv2_tax_query_append_args($existing_args, $rules, $relationship)
{

  $new_tax_query = [];

  foreach ($rules as $item) {
    if ($item['action'] == 'exclude' && $item['matchStrictness'] == 'ALL') {
      $nested_tax_query = ['relation' => 'AND'];
      foreach ($item['termIds'] as $term_id) {
        $nested_tax_query[] = [
          'taxonomy' => $item['taxonomySlug'],
          'field' => 'term_id',
          'terms' => [$term_id],
          'operator' => 'NOT IN',
        ];
      }
      $new_tax_query[] = $nested_tax_query;
    } else {
      $operator = ($item['action'] == 'include') ? (($item['matchStrictness'] == 'ALL') ? 'AND' : 'IN') : 'NOT IN';
      $new_tax_query[] = [
        'taxonomy' => $item['taxonomySlug'],
        'field' => 'term_id',
        'terms' => $item['termIds'],
        'operator' => $operator,
        'include_children' => false,
      ];
    }
  }

  // Encapsulate the new tax query with its own relationship
  if (!empty($new_tax_query)) {
    $new_tax_query = [
      'relation' => $relationship,
      $new_tax_query
    ];
  }

  // Merge the new tax query with the existing one
  if (!empty($existing_args['tax_query'])) {
    $existing_args['tax_query'][] = $new_tax_query; // Append the new tax query as a single element
  } else {
    $existing_args['tax_query'] = array($new_tax_query);
  }

  return $existing_args;
}

// API endpoint to fetch product taxonomy terms
add_action('rest_api_init', function () {
  register_rest_route(
    'wcpt_qv2/v1',
    '/terms/(?P<taxonomy_slug>[a-zA-Z0-9_-]+)',
    array (
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'wcpt_qv2_get_taxonomy_terms_with_children',
      'args' => array (
        'taxonomy_slug' => array (
          'validate_callback' => function ($param, $request, $key) {
            return !empty ($param);
          }
        ),
      ),
      'permission_callback' => function (WP_REST_Request $request) {

        if (isset ($_SERVER['HTTP_X_WP_NONCE']) && wp_verify_nonce($_SERVER['HTTP_X_WP_NONCE'], 'wp_rest')) {
          return true;
        }

        return new WP_Error('forbidden', 'You do not have permission to access this resource.', array ('status' => 403));

      }
    )
  );
});
// -- callback to return terms
function wcpt_qv2_get_taxonomy_terms_with_children($request)
{
  $taxonomy_slug = $request['taxonomy_slug'];
  $terms = get_terms(
    array(
      'taxonomy' => $taxonomy_slug,
      'hide_empty' => false,
      'parent' => 0 // Get only top-level terms initially
    )
  );

  if (is_wp_error($terms)) {
    return $terms;
  }

  $formatted_terms = array();
  foreach ($terms as $term) {
    $formatted_terms[] = wcpt_qv2_format_term_with_children($term, $taxonomy_slug);
  }

  return new WP_REST_Response($formatted_terms, 200);
}

// -- helper to format terms
function wcpt_qv2_format_term_with_children($term, $taxonomy_slug)
{
  $children_terms = get_terms(
    array(
      'taxonomy' => $taxonomy_slug,
      'hide_empty' => false,
      'parent' => $term->term_id
    )
  );

  $formatted_term = array(
    'label' => $term->name,
    'id' => $term->term_id,
    'slug' => $term->slug,
  );

  if (!empty($children_terms)) {
    $children = array();
    foreach ($children_terms as $child) {
      $children[] = wcpt_qv2_format_term_with_children($child, $taxonomy_slug); // Recursively find children
    }

    // Add the 'children' key only if there are child terms
    $formatted_term['children'] = $children;
  }

  return $formatted_term;
}


// get product taxonomies
function wcpt_qv2_get_product_taxonomies()
{
  $taxonomies = get_taxonomies(array('object_type' => array('product')), 'objects');
  $taxonomy_data = array();

  foreach ($taxonomies as $taxonomy) {
    $type = 'custom';
    $label = $taxonomy->label;
    if ($taxonomy->name === 'product_cat') {
      $type = 'category';
    } elseif ($taxonomy->name === 'product_tag') {
      $type = 'tag';
    } elseif (strpos($taxonomy->name, 'pa_') !== false) {
      $type = 'attribute';
      $label = preg_replace('/^Product\s+/', '', $taxonomy->label);
    } elseif ($taxonomy->name === 'product_type') {
      continue;
    }

    $taxonomy_data[] = array(
      'label' => $label,
      'slug' => $taxonomy->name,
      'terms' => null,
      'type' => $type,
    );
  }

  if (!in_array("product_cat", array_column($taxonomy_data, 'slug'))) {
    $taxonomy_data[] = array(
      'label' => 'Product category',
      'slug' => 'product_cat',
      'terms' => null,
      'type' => 'category',
    );
  }

  return $taxonomy_data;
}

// get product authors
function wcpt_qv2_get_product_authors()
{
  $args = array(
    'fields' => array('ID', 'display_name'), // Adjust this to get the fields you need
    'orderby' => 'display_name',
    'order' => 'ASC',
  );

  $users = get_users($args);

  $user_list = [];
  foreach ($users as $user) {
    if (user_can($user->ID, 'edit_products')) { // Check if the user can edit products
      $user_list[] = [
        'value' => $user->ID,
        'label' => $user->display_name
      ];
    }
  }

  return $user_list;
}