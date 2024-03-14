<div class="wcpt-option-row">
  <div class="wcpt-option-label" style="width: 100%">
    <?php _e("Select products by category", 'wc-product-table'); ?>:
  </div>

  <div class="wcpt-category-options wcpt-hierarchy">
    <?php
    function wcpt_product_cat_options_walker($category)
    {
      $child_cats = get_terms('product_cat', array('parent' => $category->term_id, 'hide_empty' => 0));
      ?>
      <div class="wcpt-category">
        <label>
          <input type="checkbox" class="<?php echo $child_cats ? 'wcpt-hr-parent-term' : ''; ?>"
            wcpt-model-key="category[]" wcpt-controller="category" value="<?php echo $category->term_taxonomy_id; ?>" />
          <?php echo $category->name; ?>
        </label>
        <?php
        if ($child_cats) {
          ?>
          <i class="wcpt-toggle-sub-categories">
            <?php wcpt_icon('chevron-down'); ?>
          </i>
          <div class="wcpt-sub-categories wcpt-hr-child-terms-wrapper">
            <?php
            foreach ($child_cats as $child_cat) {
              wcpt_product_cat_options_walker($child_cat);
            }
            ?>
          </div>
          <?php
        }
        ?>
      </div>
      <?php
    }
    ;

    $product_cats = get_terms('product_cat', array('hide_empty' => 0));
    foreach ($product_cats as $category) {
      if ($category->parent) {
        continue;
      }
      wcpt_product_cat_options_walker($category);
    }
    ?>
  </div>
</div>

<!-- limit -->
<div class="wcpt-option-row">
  <div class="wcpt-option-label">
    <?php _e("Max products per page", 'wc-product-table'); ?>:
  </div>
  <div class="wcpt-input">
    <input class="wcpt-limit" wcpt-model-key="limit" type="number" min="-1" value="" />
  </div>
</div>

<!-- pagination -->
<div class="wcpt-option-row">
  <div class="wcpt-option-label">
    <?php _e("Pagination", 'wc-product-table'); ?>:
  </div>
  <div class="wcpt-input">
    <label>
      <input class="wcpt-paginate" wcpt-model-key="paginate" type="checkbox" value="on" />
      Enable
    </label>
  </div>
</div>

<!-- orderby -->
<div class="wcpt-option-row">
  <div class="wcpt-option-label">
    <?php _e("Initial orderby", 'wc-product-table'); ?>:
  </div>
  <div class="wcpt-input">
    <select class="wcpt-orderby" wcpt-model-key="orderby">
      <?php
      $orderby_options = array(
        'title' => __('Title', 'wc-product-table'),
        'date' => __('Date', 'wc-product-table'),
        'menu_order' => __('Menu order', 'wc-product-table'),
        'rating' => __('Average rating', 'wc-product-table'),
        'price' => __('Price: low to high ', 'wc-product-table'),
        'price-desc' => __('Price: high to low', 'wc-product-table'),
        'popularity' => __('Popularity (sales)', 'wc-product-table'),
        'rand' => __('Random', 'wc-product-table'),
      );
      foreach ($orderby_options as $option => $label) {
        ?>
        <option value="<?php echo $option ?>">
          <?php echo $label; ?>
        </option>
        <?php
      }
      ?>
      <?php wcpt_pro_option('category', 'Category'); ?>
      <?php wcpt_pro_option('attribute', 'Attribute: as text'); ?>
      <?php wcpt_pro_option('attribute_num', 'Attribute: as number'); ?>
      <?php wcpt_pro_option('taxonomy', 'Taxonomy'); ?>
      <?php wcpt_pro_option('meta_value', 'Custom field: as text'); ?>
      <?php wcpt_pro_option('meta_value_num', 'Custom field: as number'); ?>
      <?php wcpt_pro_option('id', 'Product ID'); ?>
      <?php wcpt_pro_option('sku', 'SKU: as text'); ?>
      <?php wcpt_pro_option('sku_num', 'SKU: as number'); ?>
    </select>
  </div>
</div>

<!-- orderby: meta key -->
<div class="wcpt-option-row" wcpt-panel-condition="prop" wcpt-condition-prop="orderby"
  wcpt-condition-val="meta_value_num||meta_value">
  <div class="wcpt-option-label">
    <?php _e("Custom field to orderby", 'wc-product-table'); ?>:
  </div>
  <div class="wcpt-input">
    <input wcpt-model-key="meta_key" type="text">
  </div>
</div>

<!-- orderby: category -->
<div class="wcpt-option-row" wcpt-panel-condition="prop" wcpt-condition-prop="orderby" wcpt-condition-val="category"
  style="border-left: 4px solid #dedede; margin-left: 20px;">
  <div class="wcpt-option-row">
    <div class="wcpt-option-label" style="vertical-align: middle;">
      Ignore categories:
      <br>
      <small>Optional. Enter one category slug per line</small>
    </div>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_ignore_category"></textarea>
    </div>
  </div>

  <div class="wcpt-option-row">
    <div class="wcpt-option-label" style="vertical-align: middle;">
      Focus categories:
      <br>
      <small>Optional. Enter one category slug per line</small>
    </div>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_focus_category"></textarea>
    </div>
  </div>
</div>

<!-- orderby: attribute -->
<div class="wcpt-option-row" wcpt-panel-condition="prop" wcpt-condition-prop="orderby"
  wcpt-condition-val="attribute||attribute_num" style="border-left: 4px solid #dedede; margin-left: 20px;">
  <div class="wcpt-option-row">
    <div class="wcpt-option-label">Orderby attribute:</div>
    <div class="wcpt-input">
      <select wcpt-model-key="orderby_attribute">
        <option value="">Select an attribute here</option>
        <?php
        foreach (wc_get_attribute_taxonomies() as $attribute) {
          ?>
          <option value="pa_<?php echo $attribute->attribute_name; ?>">
            <?php echo $attribute->attribute_label; ?>
          </option>
          <?php
        }
        ?>
      </select>
    </div>
  </div>

  <div class="wcpt-option-row">
    <div class="wcpt-option-label" style="vertical-align: middle;">
      Ignore terms:
      <br>
      <small>Optional. Enter one attribute term slug per line</small>
    </div>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_ignore_attribute_term"></textarea>
    </div>
  </div>

  <div class="wcpt-option-row">
    <div class="wcpt-option-label" style="vertical-align: middle;">
      Focus terms:
      <br>
      <small>Optional. Enter one attribute term slug per line</small>
    </div>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_focus_attribute_term"></textarea>
    </div>
  </div>

  <div class="wcpt-option-row">
    <div class="wcpt-option-label">
      Include all:
      <br>
      <small>Show products that don't have the attribute, after sorted products</small>
    </div>
    <div class="wcpt-input" style="vertical-align:top;">
      <label>
        <input wcpt-model-key="orderby_attribute_include_all" type="checkbox" value="on" />
        Enable
      </label>
    </div>
  </div>

</div>

<!-- orderby: taxonomy -->
<div class="wcpt-option-row" wcpt-panel-condition="prop" wcpt-condition-prop="orderby" wcpt-condition-val="taxonomy"
  style="border-left: 4px solid #dedede; margin-left: 20px;">
  <div class="wcpt-option-row">
    <div class="wcpt-option-label">Orderby taxonomy:</div>
    <div class="wcpt-input">
      <select wcpt-model-key="orderby_taxonomy">
        <option value="">Select a taxonomy here</option>
        <?php
        $taxonomies = get_taxonomies(
          array(
            'public' => true,
            '_builtin' => false,
            'object_type' => array('product'),
          ),
          'objects'
        );

        foreach ($taxonomies as $taxonomy) {
          if (
            in_array($taxonomy->name, array('product_cat', 'product_shipping_class')) ||
            'pa_' == substr($taxonomy->name, 0, 3)
          ) {
            continue;
          }
          ?>
          <option value="<?php echo $taxonomy->name; ?>">
            <?php echo $taxonomy->label; ?>
          </option>
          <?php
        }
        ?>
      </select>
    </div>
  </div>

  <div class="wcpt-option-row">
    <div class="wcpt-option-label" style="vertical-align: middle;">
      Ignore terms:
      <br>
      <small>Optional. Enter one taxonomy term slug per line</small>
    </div>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_ignore_taxonomy_term"></textarea>
    </div>
  </div>

  <div class="wcpt-option-row">
    <div class="wcpt-option-label" style="vertical-align: middle;">
      Focus terms:
      <br>
      <small>Optional. Enter one taxonomy term slug per line</small>
    </div>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_focus_taxonomy_term"></textarea>
    </div>
  </div>

  <div class="wcpt-option-row">
    <div class="wcpt-option-label">
      Include all:
      <br>
      <small>Show products that don't have the taxonomy, after sorted products</small>
    </div>
    <div class="wcpt-input" style="vertical-align:top;">
      <label>
        <input wcpt-model-key="orderby_taxonomy_include_all" type="checkbox" value="on" />
        Enable
      </label>
    </div>
  </div>

</div>

<!-- order -->
<div class="wcpt-option-row" wcpt-panel-condition="prop" wcpt-condition-prop="orderby"
  wcpt-condition-val="meta_value_num||meta_value||title||menu_order||id||sku||sku_num||date||category||attribute||attribute_num||taxonomy||post_content">
  <div class="wcpt-option-label">
    <?php _e("Initial order", 'wc-product-table'); ?>:
  </div>
  <div class="wcpt-input">
    <select class="wcpt-order" wcpt-model-key="order">
      <?php
      $order_options = array(
        'ASC' => __('Ascending', 'wc-product-table'),
        'DESC' => __('Descending', 'wc-product-table'),
      );
      foreach ($order_options as $option => $label) {
        ?>
        <option value="<?php echo $option ?>">
          <?php echo $label; ?>
        </option>
        <?php
      }
      ?>
    </select>
  </div>
</div>

<!-- hide out of stock items -->
<div class="wcpt-option-row">
  <div class="wcpt-option-label">
    <?php _e("Hide out of stock items", 'wc-product-table'); ?>:
  </div>
  <div class="wcpt-input">
    <label>
      <?php
      $disabled = get_option('woocommerce_hide_out_of_stock_items', 'no') == 'yes' ? 'disabled="disabled" checked="checked"' : '';
      ?>
      <input wcpt-model-key="hide_out_of_stock_items" type="checkbox" value="on" <?php echo $disabled; ?> />
      Enable
      <?php if ($disabled): ?>
        <span class="wcpt-hide-out-of-stock-option-note">
          To enable this option, please uncheck 'Out of stock visibility'
          <a href="<?php echo get_admin_url(); ?>admin.php?page=wc-settings&tab=products&section=inventory"
            target="_blank">here</a>.
        </span>
      <?php endif; ?>
    </label>
  </div>
</div>

<!-- ids -->
<div class="wcpt-option-row">
  <div class="wcpt-option-label">
    <?php _e("Select products by ID", 'wc-product-table'); ?>:
  </div>
  <div class="wcpt-input">
    <textarea wcpt-model-key="ids"
      placeholder="<?php _e("Enter comma separated product IDs", 'wc-product-table'); ?>"></textarea>
    </select>
  </div>
</div>

<!-- skus -->
<div class="wcpt-option-row">
  <div class="wcpt-option-label">
    <?php _e("Select products by SKU", 'wc-product-table'); ?>:
  </div>
  <div class="wcpt-input">
    <textarea wcpt-model-key="skus"
      placeholder="<?php _e("Enter comma separated product SKUs", 'wc-product-table'); ?>"></textarea>
    </select>
  </div>
</div>

<!-- additional query args -->
<div class="wcpt-option-row">
  <div class="wcpt-option-label">
    <?php _e("Additional query args", 'wc-product-table'); ?>:
  </div>
  <div class="wcpt-input" style="position: relative;">
    <input class="wcpt-query-args-additional" wcpt-model-key="additional_query_args" type="text" />

    <span class="wcpt-additional-query-args-info wcpt-toggle wcpt-toggle-off" style="z-index: 1;">

      <span class="wcpt-toggle-trigger wcpt-noselect">
        <?php wcpt_icon('chevron-down', 'wcpt-toggle-is-off'); ?>
        <?php wcpt_icon('chevron-up', 'wcpt-toggle-is-on'); ?>
        INFO
      </span>

      <span class="wcpt-toggle-tray">
        <p>
          This feature is for wordpress developers only. It accepts <a
            href="https://developer.wordpress.org/reference/classes/wp_query/" target="_blank">WP Query</a> args in
          parameterized form. For example: posts_per_page=10&orderby=title
        </p>
        <p>
          If you need to filter products by category, taxonomy, tags, attributes or custom fields, you can use <a
            href="https://wcproducttable.com/documentation/shortcode-attribute" target="_blank">shortcode attributes</a>
          included in the plugin like [product_table id="123" category="cat 1, cat 2"]. Some of the shortcode attributes
          are <a href="https://pro.wcproducttable.com/" target="_blank">WCPT PRO</a> exclusive.
        </p>
      </span>

    </span>

  </div>
</div>