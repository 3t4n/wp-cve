<!-- output format -->
<div class="wcpt-editor-row-option">
  <label>
    Template <?php wcpt_pro_badge(); ?>
  </label>
  <div
    class="<?php wcpt_pro_cover(); ?>"
    wcpt-block-editor=""
    wcpt-be-add-element-partial="add-rating-element"
    wcpt-model-key="template"
  ></div>
</div>

<!-- output when no rating -->
<div class="wcpt-editor-row-option">
  <label>
    Content when product is not rated
  </label>
  <div
    wcpt-block-editor=""
    wcpt-be-add-element-partial="add-common-element"
    wcpt-model-key="not_rated"
  ></div>
</div>

<!-- rating source -->
<div class="wcpt-editor-row-option">
  <label>
    Rating source
  </label>
  <select wcpt-model-key="rating_source">
    <option value="woocommerce">WooCommerce</option>
    <?php wcpt_pro_option('custom_field', 'Custom field'); ?>
  </select>
</div>

<!-- rating source custom fields -->
<div 
  class="wcpt-editor-row-option" 
  wcpt-panel-condition="prop" 
  wcpt-condition-prop="rating_source" 
  wcpt-condition-val="custom_field"
>
  <!-- rating number -->
  <div class="wcpt-editor-row-option">
    <label>
      'Rating number' custom field source (required)
    </label>
    <input type="text" wcpt-model-key="rating_number_custom_field" />    
  </div>

  <!-- rating stars -->
  <div class="wcpt-editor-row-option">
    <label>
      'Review count' custom field source (optional)
    </label>
    <input type="text" wcpt-model-key="review_count_custom_field" />    
  </div>
</div>

<!-- style -->
<?php include( 'style/common.php' ); ?>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
