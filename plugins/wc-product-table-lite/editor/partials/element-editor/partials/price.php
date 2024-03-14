<!-- use default template -->
<div class="wcpt-editor-row-option" style="margin-top: 30px">
  <label>
    <input type="checkbox" wcpt-model-key="use_default_template">
    Use the default WooCommerce price template
  </label>
</div>

<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="use_default_template"
  wcpt-condition-val="false"
>
  <!-- regular / non-sale price template -->
  <div class="wcpt-editor-row-option">
    <label>
      Price template
    </label>
    <div
      wcpt-block-editor
      wcpt-be-add-element-partial="add-price-element"
      wcpt-model-key="template"
    ></div>
  </div>

  <!-- sale price template -->
  <div class="wcpt-editor-row-option">
    <label>
      Sale price template
    </label>
    <div
      wcpt-block-editor
      wcpt-be-add-element-partial="add-price-sale-element"
      wcpt-model-key="sale_template"
    ></div>
  </div>

  <!-- variable / grouped product price template -->
  <div class="wcpt-editor-row-option">
    <label>
      Variable / grouped product price template
    </label>
    <div
      wcpt-block-editor
      wcpt-be-add-element-partial="add-price-variable-element"
      wcpt-model-key="variable_template"
    ></div>
  </div>

  <!-- style -->
  <?php include( 'style/common.php' ); ?>

</div>

  <!-- variable price switch -->
  <div class="wcpt-editor-row-option">
    <?php wcpt_pro_checkbox('true', 'Switch price based on selected variation', 'variable_switch'); ?>
  </div>  

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
