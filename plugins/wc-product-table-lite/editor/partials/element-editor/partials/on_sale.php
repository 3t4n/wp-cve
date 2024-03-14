<div class="wcpt-editor-row-option">
  <label>
    Template
    <small>Use [price_diff] (eg: $44) or [percent_diff] (eg: 10% ) in text element</small>
  </label>
  <div
    wcpt-block-editor=""
    wcpt-be-add-element-partial="add-on-sale-element"
    wcpt-model-key="template"
  ></div>
</div>

<div class="wcpt-editor-row-option">
  <label>
    Number of decimals digits allowed
  </label>
  <input type="number" wcpt-model-key="precision" placeholder="0" min="0" />  
</div>

<!-- variable price switch -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="variable_switch" /> 
    Switch discount based on selected variation
  </label>
</div>  

<!-- style -->
<?php include( 'style/common.php' ); ?>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
