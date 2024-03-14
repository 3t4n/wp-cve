<!-- display type -->
<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="position"
  wcpt-condition-val="header"
>
  <label>Display type</label>
  <select wcpt-model-key="display_type">
    <option value="dropdown">Dropdown</option>
    <option value="row">Row</option>
  </select>
</div>

<!-- heading -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="display_type" wcpt-condition-val="row">
  <label>Heading</label>
  <input type="text" wcpt-model-key="heading">
</div>

<div class="wcpt-editor-row-option">
  <label class="wcpt-editor-options-heading">Sort Options</label>
</div>

<!-- options -->
<div class="wcpt-editor-row-option">

  <!-- option rows -->
  <div
    class="wcpt-label-options-rows-wrapper wcpt-sortable"
    wcpt-model-key="dropdown_options"
  >
    <div
      class="wcpt-editor-row wcpt-editor-custom-label-setup"
      wcpt-controller="custom_labels"
      wcpt-model-key="[]"
      wcpt-model-key-index="0"
      wcpt-initial-data="sortby_option"
      wcpt-row-template="sortby_option"
    >

      <!-- label -->
      <div class="wcpt-editor-row-option">
        <label>Label</label>
        <input type="text" wcpt-model-key="label">
      </div>

      <!-- Orderby -->
      <div class="wcpt-editor-row-option">
        <label>Order by</label>
        <select wcpt-model-key="orderby">
          <option value="title">Title</option>
          <option value="price">Price low to high</option>
          <option value="price-desc">Price high to low</option>
          <option value="menu_order">Menu order</option>
          <option value="popularity">Popularity (sales)</option>
          <option value="rating">Average rating</option>
          <option value="rand">Random</option>
          <option value="date">Date</option>

          <?php wcpt_pro_option('category', 'Category'); ?>
          <?php wcpt_pro_option('attribute', 'Attribute: as text'); ?>
          <?php wcpt_pro_option('attribute_num', 'Attribute: as number'); ?>
          <?php wcpt_pro_option('taxonomy', 'Taxonomy'); ?>          
          <?php wcpt_pro_option('meta_value_num', 'Custom field: as number'); ?>
          <?php wcpt_pro_option('meta_value', 'Custom field: as text'); ?>
          <?php wcpt_pro_option('id', 'Product ID'); ?>
          <?php wcpt_pro_option('sku', 'SKU: as text'); ?>
          <?php wcpt_pro_option('sku_num', 'SKU: as integer'); ?>
        </select>
      </div>

      <!-- meta key -->
      <div
        class="wcpt-editor-row-option"
        wcpt-panel-condition="prop"
        wcpt-condition-prop="orderby"
        wcpt-condition-val="meta_value_num||meta_value"
      >
        <label>Custom field name</label>
        <input type="text" wcpt-model-key="meta_key">
      </div>

      <!-- orderby: category -->
      <div
        class="wcpt-editor-row-option"
        wcpt-panel-condition="prop"
        wcpt-condition-prop="orderby"
        wcpt-condition-val="category"
      >
        <div class="wcpt-editor-row-option">
          <label>
            Ignore categories
            <small>Optional. Enter one category slug per line</small>
          </label>
          <div class="wcpt-input">
            <textarea wcpt-model-key="orderby_ignore_category"></textarea>
          </div>
        </div>

        <div class="wcpt-editor-row-option">
          <label>
            Focus categories
            <small>Optional. Enter one category slug per line</small>
          </label>
          <div class="wcpt-input">
            <textarea wcpt-model-key="orderby_focus_category"></textarea>
          </div>
        </div>  
      </div>

      <!-- orderby: attribute -->
      <div
        class="wcpt-editor-row-option"
        wcpt-panel-condition="prop"
        wcpt-condition-prop="orderby"
        wcpt-condition-val="attribute||attribute_num"
      >
        <div class="wcpt-editor-row-option">
          <label>Orderby attribute</label>
          <div class="wcpt-input">
            <select wcpt-model-key="orderby_attribute">
            <option value="">Select an attribute here</option>
            <?php
            foreach( wc_get_attribute_taxonomies() as $attribute ){
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

        <div class="wcpt-editor-row-option">
          <label>
            Ignore attribute terms
            <br>
            <small>Optional. Enter one attribute term slug per line</small>
          </label>
          <div class="wcpt-input">
            <textarea wcpt-model-key="orderby_ignore_attribute_term"></textarea>
          </div>
        </div>

        <div class="wcpt-editor-row-option">
          <label>
            Focus attribute terms
            <br>
            <small>Optional. Enter one attribute term slug per line</small>
          </label>
          <div class="wcpt-input">
            <textarea wcpt-model-key="orderby_focus_attribute_term"></textarea>
          </div>
        </div>  

        <div class="wcpt-editor-row-option">
          <label>
            <input 
              wcpt-model-key="orderby_attribute_include_all" 
              type="checkbox" 
              value="on" 
            />
            Include all
            <small>Show products that don't have the attribute, after sorted products</small>
          </label>
        </div>

      </div>

      <!-- orderby: taxonomy -->
      <div
        class="wcpt-editor-row-option"
        wcpt-panel-condition="prop"
        wcpt-condition-prop="orderby"
        wcpt-condition-val="taxonomy"
      >
        <div class="wcpt-editor-row-option">
          <label>Orderby taxonomy</label>
          <div class="wcpt-input">
            <select wcpt-model-key="orderby_taxonomy">
            <option value="">Select a taxonomy here</option>
            <?php
            $taxonomies = get_taxonomies(
              array(
                'public'=> true,
                '_builtin'=> false,
                'object_type'=> array('product'),
              ),
              'objects'
            );

            foreach( $taxonomies as $taxonomy ){
              if(
                in_array( $taxonomy->name, array( 'product_cat', 'product_shipping_class' ) ) ||
                'pa_' == substr( $taxonomy->name, 0, 3 )
              ){
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

        <div class="wcpt-editor-row-option">
          <label>
            Ignore taxonomy terms
            <br>
            <small>Optional. Enter one taxonomy term slug per line</small>
          </label>
          <div class="wcpt-input">
            <textarea wcpt-model-key="orderby_ignore_taxonomy_term"></textarea>
          </div>
        </div>

        <div class="wcpt-editor-row-option">
          <label>
            Focus taxonomy terms
            <br>
            <small>Optional. Enter one taxonomy term slug per line</small>
          </label>
          <div class="wcpt-input">
            <textarea wcpt-model-key="orderby_focus_taxonomy_term"></textarea>
          </div>
        </div>  

        <div class="wcpt-editor-row-option">
          <label>
            <input 
              wcpt-model-key="orderby_taxonomy_include_all" 
              type="checkbox" 
              value="on" 
            />
            Include all
            <small>Show products that don't have the taxonomy, after sorted products</small>
          </label>
        </div>

      </div>      

      <!-- order -->
      <div
        class="wcpt-editor-row-option"
        wcpt-panel-condition="prop"
        wcpt-condition-prop="orderby"
        wcpt-condition-val="meta_value_num||meta_value||title||menu_order||id||sku||sku_num||date||category||attribute||attribute_num||taxonomy"
      >
        <label>Order</label>
        <select wcpt-model-key="order">
          <option value="ASC">Low to high</option>
          <option value="DESC">High to low</option>
        </select>
      </div>

      <!-- corner options -->
      <?php wcpt_corner_options(); ?>

    </div>

    <button
      class="wcpt-button"
      wcpt-add-row-template="sortby_option"
    >
      Add an Option
    </button>

  </div>
</div>

<!-- accordion always open -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="accordion_always_open"> Keep filter open by default if it is in sidebar
  </label>
</div>

<?php include('style/filter.php'); ?>
