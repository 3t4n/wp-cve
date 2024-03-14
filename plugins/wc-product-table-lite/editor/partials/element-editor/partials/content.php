<!-- clear all label -->
<div class="wcpt-editor-row-option">
  <label>
    Limit word count
    <small>Note: Using this will strip HTML from the content.</small>
  </label>
  <input type="number" wcpt-model-key="limit" />
</div>

<!-- truncation symbol -->
<div class="wcpt-editor-row-option">
  <label>
    Truncation symbol (…)
  </label>
  <label>
    <input type="radio" wcpt-model-key="truncation_symbol" value="">  
    Keep it    
  </label>  
  <label>
    <input type="radio" wcpt-model-key="truncation_symbol" value="hide">  
    Hide it
  </label>
  <label>
    <input type="radio" wcpt-model-key="truncation_symbol" value="custom">  
    Enter custom symbol
  </label>
</div>

<!-- truncation symbol: custom -->
<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="truncation_symbol"
  wcpt-condition-val="custom"
>
  <label>
    Enter custom truncation symbol 
  </label>
  <input type="text" wcpt-model-key="custom_truncation_symbol" />
</div>

<!-- enable toggle -->
<div class="wcpt-editor-row-option">
  <?php
    wcpt_pro_checkbox(true, 'Enable toggle (show more / less)', "toggle_enabled");
  ?>
  <!-- <label>  
    <small>Note: Using this will strip HTML from the content.</small>
  </label>   -->
</div>

<!-- toggle labels -->
<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="toggle_enabled"
  wcpt-condition-val="true"
>
  <!-- show more label -->
  <div class="wcpt-editor-row-option">
    <label>Show more label</label>
    <div
      wcpt-block-editor
      wcpt-model-key="show_more_label"
      wcpt-be-add-row="0"
    ></div>
  </div>

  <!-- show less label -->
  <div class="wcpt-editor-row-option">
    <label>Show less label</label>
    <div
      wcpt-block-editor
      wcpt-model-key="show_less_label"
      wcpt-be-add-row="0"
    ></div>
  </div>
</div>

<!-- enable toggle -->
<div class="wcpt-editor-row-option">
  <label> 
    Action on shortcodes in content
  </label>  
  <label>
    <input type="radio" wcpt-model-key="shortcode_action" value="">  
    Process once at end of table creation (efficient, default)
  </label>  
  <label>
    <input type="radio" wcpt-model-key="shortcode_action" value="strip">  
    Remove all shortcodes from the content before printing
  </label>
  <label>
    <input type="radio" wcpt-model-key="shortcode_action" value="process">  
    Process under individual product context 
  </label>
</div>

<!-- 'Read more' label -->
<div
  class="wcpt-editor-row-option <?php wcpt_pro_cover(); ?>"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="toggle_enabled"
  wcpt-condition-val="false"
>
  <label>
    'Read more' label <?php wcpt_pro_badge(); ?>
    <small>Leave empty to hide 'read more' link</small>
  </label>
  <div
    wcpt-model-key="read_more_label"
    wcpt-block-editor=""
    wcpt-be-add-row="0"
  ></div>
</div>

<!-- style -->
<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

    <span class="wcpt-toggle-label">
      Style for Element
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Font size</label>
      <input type="text" wcpt-model-key="font-size" />
    </div>

    <!-- line-height -->
    <div class="wcpt-editor-row-option">
      <label>Line height</label>
      <input type="text" wcpt-model-key="line-height" placeholder="1.2em">
    </div>

    <!-- font color -->
    <div class="wcpt-editor-row-option">
      <label>Font color</label>
      <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
    </div>

    <!-- font-family -->
    <div class="wcpt-editor-row-option">
      <label>Font family</label>
      <input type="text" wcpt-model-key="font-family" />
    </div>

    <!-- width -->
    <div class="wcpt-editor-row-option">
      <label>Width</label>
      <input type="text" wcpt-model-key="width" />
    </div>

    <!-- max-width -->
    <div class="wcpt-editor-row-option">
      <label>Max width</label>
      <input type="text" wcpt-model-key="max-width" />
    </div>    

    <!-- padding -->
    <div class="wcpt-editor-row-option">
      <label>Padding</label>
      <input type="text" wcpt-model-key="padding-top" placeholder="top">
      <input type="text" wcpt-model-key="padding-right" placeholder="right">
      <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
      <input type="text" wcpt-model-key="padding-left" placeholder="left">
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

</div>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>


<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
