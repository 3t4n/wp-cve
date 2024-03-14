<!-- label -->
<div class="wcpt-editor-row-option">
  <label>Button label</label>
  <div wcpt-block-editor wcpt-be-add-element-partial="add-navigation-filter-heading-element" wcpt-model-key="label"
    wcpt-be-add-row="0"></div>
</div>

<!-- file name -->
<div class="wcpt-editor-row-option">
  <label>File name</label>
  <input type="text" wcpt-model-key="file_name" placeholder="products">
</div>

<!-- include all products -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="include_all_products">
    Include products from other pages of the table
    <small>Depending on total number of products in the table, including products from all pages can affect performance
      and delay download.</small>
  </label>
</div>

<!-- columns -->
<div class="wcpt-editor-row-option">

  <!-- option rows -->
  <div class="wcpt-label-options-rows-wrapper wcpt-sortable" wcpt-model-key="columns">
    <div class="wcpt-editor-row wcpt-editor-custom-label-setup" wcpt-controller="download_csv_column"
      wcpt-model-key="[]" wcpt-model-key-index="0" wcpt-initial-data="csv_columns" wcpt-row-template="csv_columns">

      <!-- column heading -->
      <div class="wcpt-editor-row-option">
        <label>Column heading</label>
        <input type="text" wcpt-model-key="column_heading" placeholder="Please enter CSV column heading">
      </div>

      <!-- property -->
      <div class="wcpt-editor-row-option">
        <label>Property</label>
        <select wcpt-model-key="property">
          <option value="">Please select a property</option>
          <option value="title">Title</option>
          <option value="regular_price">Regular price</option>
          <option value="sale_price">Sale price</option>
          <option value="is_on_sale">Is on sale (Yes / No)</option>
          <option value="highest_price">Highest price (variable product)</option>
          <option value="lowest_price">Lowest price (variable product)</option>
          <option value="sku">SKU</option>
          <option value="id">Product ID</option>
          <option value="meta">Custom field / Meta data</option>
          <option value="attribute">Attribute</option>
          <option value="category">Category</option>
          <option value="average_rating">Average rating</option>
          <option value="rating_count">Rating count</option>
          <option value="availability">Availability</option>
          <option value="short_description">Short description</option>
          <option value="content">Content</option>
          <option value="weight">Weight</option>
          <option value="dimensions">Dimensions</option>
          <option value="length">Length</option>
          <option value="width">Width</option>
          <option value="height">Height</option>
          <option value="product_image_url">Product image URL</option>
          <option value="permalink">Product page URL</option>
          <option value="stock_quantity">Stock</option>
          <option value="tags">Tags</option>
          <option value="taxonomy">Taxonomy</option>
          <option value="type">Product type</option>
          <option value="product_url">External / Affiliate product URL</option>
          <option value="date_created">Date created</option>
          <option value="custom_data">* Custom data *</option>
        </select>
      </div>

      <!-- meta -->
      <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="property"
        wcpt-condition-val="meta">
        <label>Custom field name / meta key</label>
        <input type="text" wcpt-model-key="meta_key">
      </div>

      <!-- attribute -->
      <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="property"
        wcpt-condition-val="attribute">
        <div class="wcpt-editor-row-option">
          <label>
            Attribute
          </label>

          <?php
          $attributes = wc_get_attribute_taxonomies();
          if (empty($attributes)) {
            echo '<div class="wcpt-notice">There are no WooCommerce attributes on this site!</div>';
            $hide_class = 'wcpt-hide';
          }
          ?>
          <select class="<?php echo empty($attributes) ? 'wcpt-hide' : ''; ?>" wcpt-model-key="attribute_name">
            <option value=""></option>
            <option value="_custom">*Custom attribute*</option>
            <?php
            foreach ($attributes as $attribute) {
              ?>
              <option value="<?php echo $attribute->attribute_name; ?>">
                <?php echo $attribute->attribute_label; ?>
              </option>
              <?php
            }
            ?>
          </select>

        </div>

        <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="attribute_name"
          wcpt-condition-val="_custom">
          <!-- custom attribute name -->
          <div class="wcpt-editor-row-option">
            <label>Custom attribute name</label>
            <input type="text" wcpt-model-key="custom_attribute_name">
          </div>
        </div>
      </div>


      <!-- taxonomy -->
      <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="property"
        wcpt-condition-val="taxonomy">

        <div class="wcpt-editor-row-option">
          <label>Taxonomy</label>
          <?php
          $taxonomies = get_taxonomies(
            array(
              'public' => true,
              '_builtin' => false,
              'object_type' => array('product'),
            ),
            'objects'
          );

          foreach ($taxonomies as $taxonomy => $obj) {
            if (
              in_array($taxonomy, array('product_cat', 'product_shipping_class')) ||
              'pa_' == substr($taxonomy, 0, 3)
            ) {
              unset($taxonomies[$taxonomy]);
            }
          }

          if (empty($taxonomies)) {
            echo '<div class="wcpt-notice">There are no WooCommerce attributes on this site!</div>';
            $hide_class = 'wcpt-hide';
          }
          ?>
          <select class="<?php echo empty($taxonomies) ? 'wcpt-hide' : ''; ?>" wcpt-model-key="taxonomy">
            <option value=""></option>
            <?php
            foreach ($taxonomies as $taxonomy => $obj) {
              ?>
              <option value="<?php echo $taxonomy; ?>">
                <?php echo $obj->labels->name; ?>
              </option>
              <?php
            }
            ?>
          </select>
        </div>

      </div>


      <!-- strip tags -->
      <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="property"
        wcpt-condition-val="short_description||content">

        <div class="wcpt-editor-row-option">
          <label>
            <input type="checkbox" wcpt-model-key="strip_tags">
            Remove HTML tags
          </label>
        </div>

      </div>

      <!-- custom data callback -->
      <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="property"
        wcpt-condition-val="custom_data">
        <div class="wcpt-editor-row-option">
          <label>
            Custom callback function name
            <small>Args: $product_id, $table_id</small>
          </label>
          <input type="text" wcpt-model-key="custom_callback">
        </div>

      </div>

      <!-- corner options -->
      <?php wcpt_corner_options(); ?>

    </div>

    <button class="wcpt-button" wcpt-add-row-template="csv_columns">
      Add CSV column
    </button>

  </div>
</div>


<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <!-- style button (general) -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

    <span class="wcpt-toggle-label">
      Style for 'Download CSV' button
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <?php require('style/common-props.php'); ?>

  </div>

</div>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>