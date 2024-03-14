<!-- product link -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="product_link_enabled" />
    Link SKU to the product's page
  </label>
</div>

<!-- product link: new page -->
<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="product_link_enabled"
  wcpt-condition-val="true"  
>
  <label>
    <input type="checkbox" wcpt-model-key="target_new_page" />
    Open the product link on a new page  
  </label>
</div>

<!-- variable switch -->
<div class="wcpt-editor-row-option">
  <?php wcpt_pro_checkbox('true', 'Switch SKU based on selected variation', 'variable_switch'); ?>
</div>  

<!-- style -->
<?php include( 'style/common.php' ); ?>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
