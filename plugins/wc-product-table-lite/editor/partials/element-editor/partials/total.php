<!-- note -->
<div class="wcpt-editor-row-option">
  <label class="wcpt-element-note">
    <p>
    This facility can only work with default woocommere pricing. It does not support pricing rules applied by 3rd party plugins such as bulk discounts, etc. 
    </p>

  </label>
</div>

<!-- include total in cart -->
<div class="wcpt-editor-row-option" style="margin-top: 30px">
  <label>
    <input type="checkbox" wcpt-model-key="include_total_in_cart">
    Include amount already in cart 
  </label>
</div>

<!-- output template -->
<div class="wcpt-editor-row-option">
  <label>
    Template for the output (default: {n})
    <small>Use {n} as placeholder</small>
  </label>
  <input type="text" wcpt-model-key="output_template" />  
</div>

<!-- no output template -->
<div class="wcpt-editor-row-option">
  <label>
    Template when there is no output (Eg: $0, -)
    <small>Leave blank to hide completely</small>
  </label>
  <input type="text" wcpt-model-key="no_output_template" />  
</div>

<!-- variable switch -->
<!-- <div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="variable_switch" />
    Switch total based on selected variation
  </label>
</div>   -->

<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <!-- style for element -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

    <span class="wcpt-toggle-label">
      Style for Element
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <?php require( 'style/common-props.php' ); ?>

  </div>

  <!-- style for empty -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id].wcpt-total--empty">

    <span class="wcpt-toggle-label">
      Style for 'Empty' Element
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Font size</label>
      <input type="text" wcpt-model-key="font-size" />
    </div>

    <!-- font color -->
    <div class="wcpt-editor-row-option">
      <label>Font color</label>
      <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
    </div>

    <!-- background color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
    </div>    

  </div>  
</div>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
