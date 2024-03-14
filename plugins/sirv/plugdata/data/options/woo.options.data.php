<?php
defined('ABSPATH') or die('No script kiddies please!');

if(!function_exists('sirv_convert_array_to_assoc_array')){
  function sirv_convert_array_to_assoc_array($arr){

    if( !isset($arr) || empty($arr) ) return array();

    $assoc_arr = array();

    foreach ($arr as $item) {
      $assoc_arr[$item] = $item;
    }

    return $assoc_arr;
  }
}

$profiles = sirv_convert_array_to_assoc_array( sirv_getProfilesList() );
$ttl = array(
  'Disable cache (suitable for testing only)' => 1,
  '15 minutes' => 15 * 60,
  '1 hour' => 60 * 60,
  '3 hours' => 3 * 60 * 60,
  '12 hours' => 12 * 60 * 60,
  '1 day (default setting, for typical use)' => 24 * 60 * 60,
  '7 days' => 7 * 24 * 60 * 60,
  '1 month' => 30 * 24 * 60 * 60,
);

$smv_options = array(
  'SIRV_WOO_IS_ENABLE' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_IS_ENABLE',
    'label' => 'Sirv Media Viewer',
    //'desc' => 'Some text here',
    'type' => 'radio',
    'func' => 'render_radio_option',
    'is_new_line' => true,
    'value' => '',
    'values' => array(
      array(
        'label' => 'Enable',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '2',
        ),
      ),
      array(
        'label' => 'Disable',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '1',
        ),
      ),
    ),
    'default' => '1',
    'default_type' => 'str',
    'show_status' => true,
    'enabled_value' => '2',
  )
);

$content_options = array(
  'SIRV_WOO_VIEW_FOLDER_STRUCTURE' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_VIEW_FOLDER_STRUCTURE',
    'label' => 'Product folders',
    'type' => 'input',
    'func' => 'render_text_option',
    'value' => '',
    'below_text' => 'Possible variables: {product-sku}, {product-id}',
    'default' => 'products/{product-sku}',
    'default_type' => 'str',
    'attrs' => array(
      'type' => 'text',
      'placeholder' => 'products/{product-sku}',
      'value' => ''
    ),
  ),
  'SIRV_WOO_VIEW_FOLDER_VARIATION_STRUCTURE' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_VIEW_FOLDER_VARIATION_STRUCTURE',
    'label' => 'Variation folders',
    'type' => 'input',
    'func' => 'render_text_option',
    'value' => '',
    'below_text' => 'Possible variables: {product-sku}, {product-id}, {variation-sku}, {variation-id}',
    'default' => 'products/{product-sku}-{variation-sku}',
    'default_type' => 'str',
    'attrs' => array(
      'type' => 'text',
      'placeholder' => 'products/{product-sku}-{variation-sku}',
      'value' => ''
    ),
  ),
/*   'SIRV_WOO_TTL' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_TTL',
    'label' => 'Cache TTL',
    'type' => 'input',
    'func' => 'render_text_option',
    'value' => '',
    'below_text' => 'Time (in minutes) after which the cache entry expires.',
    'default' => '1440',
    'attrs' => array(
      'type' => 'text',
      'placeholder' => 'If input 0 TTL will be disable',
      'value' => ''
    ),
  ), */
  'SIRV_WOO_TTL' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_TTL',
    'label' => 'Sirv content cache TTL',
    'select_title' => 'Choose TTL',
    'below_text' => 'Time when cached content expires. Choose a shorter duration if your images change frequently.',
    'type' => 'select',
    'func' => 'render_select_option',
    'select_id' => 'sirv-woo-product-ttl',
    'value' => '',
    'default' => 24 * 60 * 60,
    'default_type' => 'int',
    'render_empty_option' => false,
    'select_data' => $ttl,
    'attrs' => array(
      'type' => 'hidden',
      'id' => 'sirv-woo-product-ttl-val',
      'value' => '',
    ),
  ),
  'unreg_empty-view-cache-table' => array(
    'enabled_option' => true,
    'option_name' => 'empty-view-cache-table',
    'label' => 'Sirv content cache',
    'is_new_line' => true,
    'type' => 'custom',
    'func' => 'render_sirv_content_cache',
    'custom_type' => 'table',
    'value' => 'with_prods',
    'values' => array(
      array(
        'label' => 'Products with content',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'with_prods',
        ),
      ),
      array(
        'label' => 'Products without content',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'without_prods',
        ),
      )
    ),
    'button_val' => 'Empty cache',
    'button_class' => 'sirv-clear-view-cache-table',
    'data_provider' => 'sirv_get_view_cache_info',
  )
);

$order_options = array(
  'SIRV_WOO_SMV_CONTENT_ORDER' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_SMV_CONTENT_ORDER',
    'label' => 'Order of content',
    //'above_text' => 'Items ordered alphabetically. To display specific items first, choose them below:',
    //'below_text' => 'Drag items to reorder. Drag away to delete.<br>
//If item is not in gallery, it will be ignored.',
    'type' => 'custom',
    'func' => 'render_sirv_smv_order_content',
    'value' => '',
    'default' => json_encode(array()),
    'default_type' => 'json',
    'attrs' => array(
      'type' => 'hidden',
      'id' => 'sirv-woo-smv-content-order',
      'value' => '',
    ),
  ),
  'SIRV_WOO_CONTENT_ORDER' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_CONTENT_ORDER',
    'label' => 'Order of remaining content',
    'is_new_line' => true,
    'type' => 'radio',
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Sirv content first',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '1',
        ),
      ),
      array(
        'label' => 'WooCommerce content first',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '2',
        ),
      ),
      array(
        'label' => 'Sirv content only',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '3',
        ),
      ),
    ),
    'default' => '2',
    'default_type' => 'str',
    'show_status' => false,
  ),
  'SIRV_WOO_SHOW_VARIATIONS' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_SHOW_VARIATIONS',
    'label' => 'Variation images',
    'is_new_line' => true,
    'type' => 'radio',
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Main product and all variations',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '1',
        ),
      ),
      array(
        'label' => 'Main product and currently selected variation',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '3',
        ),
      ),
      array(
        'label' => 'Currently selected variation',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '2',
        ),
      )
    ),
    'default' => '2',
    'default_type' => 'str',
    'show_status' => false,
  ),
  'SIRV_WOO_SHOW_MAIN_VARIATION_IMAGE' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_SHOW_MAIN_VARIATION_IMAGE',
    'label' => 'Main variation image',
    'below_text' => "If variation has no image, show main product image.",
    'type' => 'radio',
    'is_new_line' => true,
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Show',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '1',
        ),
      ),
      array(
        'label' => 'Hide',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '2',
        ),
      ),
    ),
    'default' => '2',
    'default_type' => 'str',
    'show_status' => false,
  ),
  'SIRV_WOO_PIN' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_PIN',
    'label' => 'Pin gallery items',
    'above_text' => 'Always show thumbnail(s) beside scroller.',
    'type' => 'custom',
    'func' => 'render_pin_gallery',
    'value' => '',
    'attrs' => array(
      'type' => 'hidden',
      'id' => 'sirv-woo-pin-gallery',
      'value' => ''
    ),
    'default' => json_encode(array(
      'video' => 'no',
      'spin' => 'no',
      'model' => 'no',
      'image' => 'no',
      'image_template' => ''
    )),
    'default_type' => 'json',
  )
);

$design_options = array(
  'SIRV_WOO_MAX_HEIGHT' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_MAX_HEIGHT',
    'label' => 'Max height (px)',
    'type' => 'input',
    'func' => 'render_text_option',
    'value' => '',
    'default' => '',
    'default_type' => 'str',
    'attrs' => array(
      'type' => 'text',
      'placeholder' => 'auto',
      'value' => ''
    ),
  ),
  'SIRV_WOO_PRODUCTS_PROFILE' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_PRODUCTS_PROFILE',
    'label' => 'Product images profile',
    'select_title' => 'Choose profile',
    'below_text' => 'Apply one of <a target="_blank" href="https://my.sirv.com/#/profiles/">your profiles</a> for watermarks, text and other image customizations. Learn <a target="_blank" href="https://sirv.com/help/articles/dynamic-imaging/profiles/">about profiles</a>.',
    'type' => 'select',
    'func' => 'render_select_option',
    'select_id' => 'sirv-woo-product-profiles',
    'value' => '',
    'default' => '',
    'default_type' => 'str',
    'render_empty_option' => true,
    'select_data' => $profiles,
    'attrs' => array(
      'type' => 'hidden',
      'id' => 'sirv-woo-product-profiles-val',
      'value' => '',
    ),
  ),
  'SIRV_WOO_PRODUCTS_MOBILE_PROFILE' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_PRODUCTS_MOBILE_PROFILE',
    'label' => 'Mobile images profile',
    'select_title' => 'Choose profile',
    'below_text' => 'Apply one of <a target="_blank" href="https://my.sirv.com/#/profiles/">your profiles</a> for watermarks, text and other image customizations. Learn <a target="_blank" href="https://sirv.com/help/articles/dynamic-imaging/profiles/">about profiles</a>.',
    'type' => 'select',
    'func' => 'render_select_option',
    'select_id' => 'sirv-woo-product-mobile-profiles',
    'value' => '',
    'default' => '',
    'default_type' => 'str',
    'render_empty_option' => true,
    'select_data' => $profiles,
    'attrs' => array(
      'type' => 'hidden',
      'id' => 'sirv-woo-product-mobile-profiles-val',
      'name' => 'SIRV_WOO_PRODUCTS_MOBILE_PROFILE',
      'value' => '',
    ),
    ),
  'SIRV_WOO_MV_CUSTOM_OPTIONS' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_MV_CUSTOM_OPTIONS',
    'label' => 'Script options',
    //'desc' => 'Some text here',
    'type' => 'textarea',
    'func' => 'render_textarea_option',
    'value' => '',
    'above_text' => 'Go to the <a href="https://sirv.com/help/viewer/" target="_blank">Sirv Media Viewer designer</a> to create the perfect experience for your store. Paste code from the "Script" tab:',
    'below_desc' => 'Change the zoom, spin, video and thumbnail options with JavaScript. See <a href="https://sirv.com/help/articles/sirv-media-viewer/#options">list of options</a>.',
    'default' => '',
    'default_type' => 'str',
    'attrs' => array(
      'class' => 'sirv-font-monospace',
      'rows' => 6,
      'placeholder' => "Add custom js options for Media Viewer. e.g.
var SirvOptions = {
  zoom: {
    mode: 'deep'
  }
}",
    ),
  ),
  'SIRV_WOO_MV_CUSTOM_CSS' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_MV_CUSTOM_CSS',
    'label' => 'Gallery CSS',
    //'desc' => 'Some text here',
    'type' => 'textarea',
    'func' => 'render_textarea_option',
    'value' => '',
    'above_text' => 'Paste &lt;style&gt; code from the "Inline" tab:',
    'below_desc' => '',
    'default' => '',
    'default_type' => 'str',
    'attrs' => array(
      'class' => 'sirv-font-monospace',
      'rows' => 6,
      'placeholder' => "Change styles for thumbnails, icons, text e.g.
.smv-thumbnails .smv-item.smv-active .smv-selector {
    border-width:2px;
}",

    ),
  ),
  'SIRV_WOO_ZOOM_IS_ENABLE' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_ZOOM_IS_ENABLE',
    'label' => 'Image zoom',
    'is_new_line' => true,
    //'desc' => 'Some text here',
    'type' => 'radio',
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Enabled',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '1',
        ),
      ),
      array(
        'label' => 'Disabled',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '2',
        ),
      )
    ),
    'default' => '1',
    'default_type' => 'str',
    'show_status' => false,
    'enabled_value' => '1',
  ),
  'SIRV_WOO_MV_CONTAINER_CUSTOM_CSS' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_MV_CONTAINER_CUSTOM_CSS',
    'label' => 'Container CSS',
    //'desc' => 'Some text here',
    'type' => 'textarea',
    'func' => 'render_textarea_option',
    'value' => '',
    'placeholder' => 'Add styles to adjust size/position of Sirv Media Viewer container:

width: 49%;
float: left;',
    'above_text' => 'Add styles to fix any rendering issues from 3rd party CSS',
    'default' => '',
    'default_type' => 'str',
    'attrs' => array(
      'class' => 'sirv-font-monospace',
      'rows' => 5,
      'placeholder' => 'Add styles to adjust size/position of Sirv Media Viewer container:

width: 49%;
float: left;',
    ),
  ),
  'SIRV_WOO_CONTAINER_CLASSES' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_CONTAINER_CLASSES',
    'label' => 'Container classes',
    'above_text' => 'Apply styling from a previous class, if you wish:',
    'below_text' => 'You can enter the classes that your gallery\'s container used before you switched to Sirv Media Viewer.',
    'type' => 'input',
    'func' => 'render_text_option',
    'value' => '',
    'default' => '',
    'default_type' => 'str',
    'attrs' => array(
      'type' => 'text',
      'placeholder' => '',
      'value' => ''
    ),
  ),
  'SIRV_WOO_MV_SKELETON' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_MV_SKELETON',
    'label' => 'Gallery placeholder',
    'is_new_line' => true,
    'below_text' => 'Show gallery skeleton while images are loading.',
    'type' => 'radio',
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Enable',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '1',
        ),
      ),
      array(
        'label' => 'Disable',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '2',
        ),
      )
    ),
    'default' => '1',
    'default_type' => 'str',
    'show_status' => false,
    'enabled_value' => '1',
  ),
);

$cat_options = array(
  "SIRV_WOO_CAT_IS_ENABLE" => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_CAT_IS_ENABLE',
    'label' => 'Category page effects',
    //'below_text' => 'Show hover, zoom or slider effect.',
    'is_new_line' => true,
    'type' => 'radio',
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Enabled',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'enabled',
        ),
      ),
      array(
        'label' => 'Disabled',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'disabled',
        ),
      ),
    ),
    'default' => 'disabled',
    'default_type' => 'str',
    'show_status' => true,
    'enabled_value' => 'enabled',
  ),
  'SIRV_WOO_CAT_PROFILE' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_CAT_PROFILE',
    'label' => 'Category images profile',
    'select_title' => 'Choose profile',
    'below_text' => 'Apply one of <a target="_blank" href="https://my.sirv.com/#/profiles/">your profiles</a> for watermarks, text and other image customizations. Learn <a target="_blank" href="https://sirv.com/help/articles/dynamic-imaging/profiles/">about profiles</a>.',
    'type' => 'select',
    'func' => 'render_select_option',
    'select_id' => 'sirv-woo-category-profiles',
    'value' => '',
    'default' => '',
    'default_type' => 'str',
    'render_empty_option' => true,
    'select_data' => $profiles,
    'attrs' => array(
      'type' => 'hidden',
      'id' => 'sirv-woo-category-profiles-val',
      'value' => '',
    ),
  ),
  "SIRV_WOO_CAT_ITEMS" => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_CAT_ITEMS',
    'label' => 'Items to show',
    'is_new_line' => true,
    /* 'below_text' => 'Show gallery skeleton while images are loading.', */
    'type' => 'radio',
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => '1',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '1',
        ),
      ),
      array(
        'label' => '2',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '2',
        ),
      ),
      array(
        'label' => '3',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '3',
        ),
      ),
      array(
        'label' => '4',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '4',
        ),
      ),
      array(
        'label' => 'All items',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => '1000',
        ),
      ),
    ),
    'default' => '1',
    'default_type' => 'str',
    'show_status' => false,
    'enabled_value' => '1',
  ),
  "SIRV_WOO_CAT_CONTENT" => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_CAT_CONTENT',
    'id_selector' => 'sirv_woo_cat_content_id',
    'label' => 'Content',
    'is_new_line' => true,
    /* 'above_text' => 'Always show thumbnail(s) beside scroller.', */
    'type' => 'checkbox_group',
    'func' => 'render_checkbox_group_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Images',
        'check_data_type' => 'checked',
        'data_type' => 'json',
        'data_key' => 'image',
        'attrs' => array(
          'type' => 'checkbox',
          'value' => 'yes',
          'name' => 'sirv-woo-cat-content-images',
        ),
      ),
      array(
        'label' => 'Videos',
        'check_data_type' => 'checked',
        'data_type' => 'json',
        'data_key' => 'video',
        'attrs' => array(
          'type' => 'checkbox',
          'value' => 'yes',
          'name' => 'sirv-woo-cat-content-videos',
        ),
      ),
      array(
        'label' => 'Spins',
        'check_data_type' => 'checked',
        'data_type' => 'json',
        'data_key' => 'spin',
        'attrs' => array(
          'type' => 'checkbox',
          'value' => 'yes',
          'name' => 'sirv-woo-cat-content-spins',
        ),
      ),
    ),
    'attrs' => array(
      'type' => 'hidden',
      'id' => 'sirv-woo-cat-content-hidden',
      'value' => ''
    ),
    'default' => json_encode(array(
      'video' => 'yes',
      'spin' => 'yes',
      'image' => 'yes',
    )),
    'default_type' => 'json',
  ),
  "SIRV_WOO_CAT_SOURCE" => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_CAT_SOURCE',
    'id_selector' => 'sirv_woo_cat_source_id',
    'label' => 'Source',
    'is_new_line' => true,
    /* 'below_text' => 'Show gallery skeleton while images are loading.', */
    'type' => 'radio',
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Sirv first',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'sirv_first',
        ),
      ),
      array(
        'label' => 'Sirv only',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'sirv_only',
        ),
      ),
      array(
        'label' => 'Woocommerce first',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'wc_first',
        ),
      ),
      array(
        'label' => 'Woocommerce only',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'wc_only',
        ),
      ),
    ),
    'default' => 'wc_first',
    'default_type' => 'str',
    'show_status' => false,
    'enabled_value' => '',
  ),
  "SIRV_WOO_CAT_SHOWING_METHOD" => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_CAT_SHOWING_METHOD',
    'id_selector' => 'sirv_woo_cat_showing_method_id',
    'label' => 'Spin image',
    'is_new_line' => true,
    /* 'below_text' => 'Show gallery skeleton while images are loading.', */
    'type' => 'radio',
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Static image',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'static',
        ),
      ),
      array(
        'label' => 'Animated image',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'animated',
        ),
      ),
    ),
    'default' => 'static',
    'default_type' => 'str',
    'show_status' => false,
    'enabled_value' => '',
  ),
  "SIRV_WOO_CAT_SWAP_METHOD" => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_CAT_SWAP_METHOD',
    'id_selector' => 'sirv_woo_cat_swap_method_id',
    'label' => 'Swap method',
    'is_new_line' => true,
    /* 'above_text' => 'Always show thumbnail(s) beside scroller.', */
    'type' => 'checkbox_group',
    'func' => 'render_checkbox_group_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Arrows',
        'check_data_type' => 'checked',
        'data_type' => 'json',
        'data_key' => 'arrows',
        'attrs' => array(
          'type' => 'checkbox',
          'value' => 'yes',
          'name' => 'sirv_woo_cat_swap_method_arrows',
        ),
      ),
      array(
        'label' => 'Bullets',
        'check_data_type' => 'checked',
        'data_type' => 'json',
        'data_key' => 'bullets',
        'attrs' => array(
          'type' => 'checkbox',
          'value' => 'yes',
          'name' => 'sirv_woo_cat_swap_method_bullets',
        ),
      ),
    ),
    'attrs' => array(
      'type' => 'hidden',
      'id' => 'sirv-woo-cat-swap-method-hidden',
      'value' => ''
    ),
    'default' => json_encode(array(
      'arrows' => 'no',
      'bullets' => 'yes',
    )),
    'default_type' => 'json',
  ),
  "SIRV_WOO_CAT_ZOOM_ON_HOVER" => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_CAT_ZOOM_ON_HOVER',
    'id_selector' => 'sirv_woo_cat_zoom_on_hover_id',
    'label' => 'Zoom on hover',
    'is_new_line' => true,
    /* 'below_text' => 'Show gallery skeleton while images are loading.', */
    'type' => 'radio',
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Yes',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'yes',
        ),
      ),
      array(
        'label' => 'No',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'no',
        ),
      ),
    ),
    'default' => 'no',
    'default_type' => 'str',
    'show_status' => false,
    'enabled_value' => 'yes',
  ),

);


$admin_layout_options = array(
  'SIRV_WOO_SHOW_MAIN_IMAGE' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_SHOW_MAIN_IMAGE',
    'label' => 'Sirv product image block',
    'below_text' => 'Block shown in right column of WooCommerce product admin, to set a Sirv image as the Featured image.',
    'type' => 'radio',
    'is_new_line' => true,
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Show',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'show',
        ),
      ),
      array(
        'label' => 'Hide',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'hide',
        ),
      ),
    ),
    'default' => 'show',
    'default_type' => 'str',
    'show_status' => false,
    'enabled_value' => 'show',
  ),
  'SIRV_WOO_SHOW_SIRV_GALLERY' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_SHOW_SIRV_GALLERY',
    'label' => 'Sirv gallery block',
    'below_text' => 'Block shown on right side of WooCommerce product admin, to add extra assets to the media gallery and product variations.',
    'type' => 'radio',
    'is_new_line' => true,
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Show',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'show',
        ),
      ),
      array(
        'label' => 'Hide',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'hide',
        ),
      ),
    ),
    'default' => 'show',
    'default_type' => 'str',
    'show_status' => false,
    'enabled_value' => 'show',
  ),
  'SIRV_WOO_SHOW_ADD_MEDIA_BUTTON' => array(
    'enabled_option' => true,
    'option_name' => 'SIRV_WOO_SHOW_ADD_MEDIA_BUTTON',
    'label' => 'Sirv Add Media',
    'below_text' => 'Button shown above product description/summary. Permits the addition of Sirv images, zooms, spins, videos, models or galleries.',
    'type' => 'radio',
    'is_new_line' => true,
    'func' => 'render_radio_option',
    'value' => '',
    'values' => array(
      array(
        'label' => 'Show',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'show',
        ),
      ),
      array(
        'label' => 'Hide',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'hide',
        ),
      ),
    ),
    'default' => 'show',
    'default_type' => 'str',
    'show_status' => false,
    'enabled_value' => 'show',
  ),
);

$tools_options = array(
  'unreg_migrate_woo_additional_images' => array(
    'enabled_option' => true,
    'option_name' => 'migrate_woo_additional_images',
    'label' => 'WooCommerce Additional Variation Images',
    'description' => 'If you use the WooCommerce Additional Variation Images plugin, you can migrate images from that plugin into Sirv. You don\'t need that plugin if you use Sirv.',
    'is_new_line' => true,
    'type' => 'custom',
    'func' => 'render_migrate_woo_additional_images',
    'custom_type' => 'table',
    'value' => '',
  ),
);


$options = array(
  "SMV" => array(
    "title" => 'Sirv Media Viewer for WooCommerce',
    "description" => 'Image zoom, 360 spin and product videos to make your products look glorious. Replaces your existing media gallery with <a target="_blank" href="https://sirv.com/help/articles/sirv-media-viewer/">Sirv Media Gallery</a> on your product pages.',
    "id" => 'woo-sirv-media-viewer',
    "show_save_button" => true,
    "options" => $smv_options
  ),
  "CONTENT" => array(
    "title" => 'Content from Sirv',
    "description" => 'Easily add images, videos and 360 spins to your gallery - simply upload them to your Sirv account, in folders named after your product SKUs or IDs.

Upload files at <a href="https://my.sirv.com/" target="_blank">my.sirv.com</a> or by <a href="https://my.sirv.com/#/account/settings/api" target="_blank">FTP</a>.',
    "id" => 'woo-content',
    "show_save_button" => true,
    "options" => $content_options
  ),
  "ORDER" => array(
    "title" => 'Order of content',
    "description" => 'Choose which items to show and what order thumbnails should appear in.',
    "id" => 'woo-order',
    "show_save_button" => true,
    "options" => $order_options
  ),
  "CATEGORIES" => array(
    "title" => "Category page images<sup><span style=\"color: orange;\">beta</span></sup></h3>",
    "description" => "Image settings for category listings and search results pages.",
    "id" => "woo-categories",
    "show_save_button" => true,
    "options" => $cat_options
  ),
  "DESIGN" => array(
    "title" => 'Design and experience',
    "description" => 'Go to the <a href="https://sirv.com/help/viewer/" target="_blank">Sirv Media Viewer designer</a> to create the perfect experience for your store. ',
    "id" => 'woo-design',
    "show_save_button" => true,
    "options" => $design_options
  ),
  "ADMIN_LAYOUT" => array(
    "title" => 'Admin layout',
    "description" => 'Choose which Sirv blocks to show in WordPress admin.',
    "id" => 'woo-admin-layout',
    "show_save_button" => true,
    "options" => $admin_layout_options
  ),
  "TOOLS" => array(
    "title" => 'Additional tools',
    "description" => 'Tools for automation and image management.',
    "id" => 'woo-tools',
    "show_save_button" => false,
    "options" => $tools_options
  ),
);

return $options;

?>
