<?php wcpt_how_to_use_link( "https://wcproducttable.com/documentation/sorting-by-column-heading" ); ?>

<!-- sorting options -->
<div class="wcpt-editor-row-option">
  <label>Sort by</label>
  <select class="" wcpt-model-key="orderby" wcpt-initial-data="title">
    <option value="title"          >Title</option>
    <option value="price"          >Price</option>
    <option value="menu_order"      >Menu order</option>
    <option value="popularity"     >Popularity (sales)</option>
    <option value="rating"         >Rating</option>
    <option value="date"           >Date</option>

    <?php wcpt_pro_option('category', 'Category'); ?>
    <?php wcpt_pro_option('attribute', 'Attribute: as text'); ?>
    <?php wcpt_pro_option('attribute_num', 'Attribute: as number'); ?>
    <?php wcpt_pro_option('taxonomy', 'Taxonomy'); ?>          

    <option value="meta_value_num" >Custom field: as number</option>
    <option value="meta_value"     >Custom field: as text</option>
    <option value="id"             >Product ID</option>
    <option value="sku"            >SKU: as text</option>
    <option value="sku_num"        >SKU: as integer</option>
  </select>
</div>

<div 
  class="wcpt-editor-row-option" 
  wcpt-panel-condition="prop" 
  wcpt-condition-prop="orderby" 
  wcpt-condition-val="meta_value_num||meta_value"
>
  <label for="">Sort by custom field key</label>
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

<!-- style -->
<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion">

    <span class="wcpt-toggle-label">
      Style for Element
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <div class="wcpt-wrapper wcpt-editor-row-option" wcpt-model-key="[id]">

      <!-- font-size -->
      <div class="wcpt-editor-row-option">
        <label>Size</label>
        <input type="text" wcpt-model-key="font-size" style="margin-bottom: 0 !important;">
      </div>

      <!-- margin -->
      <div class="wcpt-editor-row-option">
        <label>Margin</label>
        <input type="text" wcpt-model-key="margin-top" placeholder="top">
        <input type="text" wcpt-model-key="margin-right" placeholder="right">
        <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom">
        <input type="text" wcpt-model-key="margin-left" placeholder="left">
      </div>

    </div>

    <div class="wcpt-wrapper wcpt-editor-row-option" wcpt-model-key="[id] > .wcpt-inactive">

      <!-- font-color -->
      <div class="wcpt-editor-row-option">
        <label>Color - inactive</label>
        <input type="text" wcpt-model-key="color" class="wcpt-color-picker" >
      </div>

    </div>

    <div class="wcpt-wrapper wcpt-editor-row-option" wcpt-model-key="[id] > .wcpt-active">

      <!-- font-color -->
      <div class="wcpt-editor-row-option">
        <label>Color - active</label>
        <input type="text" wcpt-model-key="color" class="wcpt-color-picker" >
      </div>

    </div>

  </div>

</div>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>
