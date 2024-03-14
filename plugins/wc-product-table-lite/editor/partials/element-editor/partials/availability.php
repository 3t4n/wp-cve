<!-- out of stock -->
<div class="wcpt-editor-row-option">
  <label>"Out of stock" message</label>
  <div
    wcpt-model-key="out_of_stock_message"
    wcpt-be-add-row="0"
    wcpt-block-editor
  ></div>
</div>

<!-- single product left -->
<div class="wcpt-editor-row-option">
  <label>"Only 1 product left in stock" message</label>
  <div
    wcpt-model-key="single_stock_message"
    wcpt-be-add-row="0"
    wcpt-block-editor
  ></div>
</div>

<!-- low stock threshold -->
<div class="wcpt-editor-row-option">
  <label>
    Low stock threshold number
    <small>
    Leave empty to use product's settings or 0 to ignore low stock condition
    </small>
  </label>
  <input type="number" wcpt-model-key="low_stock_threshold" min="0" />
</div>

<!-- low stock -->
<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="low_stock_threshold"
  wcpt-condition-val="!0"
>
  <label>
    "Low stock left" message
    <small>Use the [stock] shortcode</small>
  </label>
  <div
    wcpt-model-key="low_stock_message"
    wcpt-block-editor
    wcpt-be-add-row="0"
  ></div>
</div>

<!-- in stock -->
<div class="wcpt-editor-row-option">
  <label>
    In stock message - quantity is <em>not</em> managed
  </label>
  <div
    wcpt-model-key="in_stock_message"
    wcpt-block-editor
    wcpt-be-add-row="0"
  ></div>
</div>

<!-- in stock managed -->
<div class="wcpt-editor-row-option">
  <label>
    In stock message - quantity is managed
    <small>Use placeholder: [stock]</small>
  </label>
  <div
    wcpt-model-key="in_stock_managed_message"
    wcpt-block-editor
    wcpt-be-add-row="0"
  ></div>
</div>

<!-- on backorder -->
<div class="wcpt-editor-row-option">
  <label>
    On backorder message - notice required and negative stock
    <small>Also shows up when stock is not managed</small>
  </label>
  <div
    wcpt-model-key="on_backorder_message"
    wcpt-block-editor
    wcpt-be-add-row="0"    
  ></div>
</div>

<!-- on backorder, notice required -->
<div class="wcpt-editor-row-option">
  <label>
    On backorder message - notice required and positive stock 
    <small>Use placeholder: [stock]</small>
  </label>
  <div
    wcpt-model-key="on_backorder_managed_message"
    wcpt-block-editor
    wcpt-be-add-row="0"
  ></div>
</div>

<!-- variable message switch -->
<div class="wcpt-editor-row-option">
  <?php wcpt_pro_checkbox('true', 'Switch availability based on selected variation', 'variable_switch'); ?>
</div>  

<!-- style -->
<?php include( 'style/common.php' ); ?>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
