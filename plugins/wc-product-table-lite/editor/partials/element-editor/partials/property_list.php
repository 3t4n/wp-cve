<div class="wcpt-editor-row-option">
  <label class="wcpt-editor-options-heading">Property list items</label>
</div>

<!-- rows -->
<div
  class="wcpt-sortable wcpt-editor-row-option"
  wcpt-model-key="rows"
>
  <div
    class="wcpt-editor-row wcpt-editor-custom-label-setup"
    wcpt-controller="property_list_row"
    wcpt-model-key="[]"
    wcpt-model-key-index="0"
    wcpt-row-template="property_list_row"
    wcpt-initial-data="property_list_row"
  >

    <div class="wcpt-tabs">

      <!-- triggers -->
      <div class="wcpt-tab-triggers">
        <div class="wcpt-tab-trigger">
          Template
        </div>
        <div class="wcpt-tab-trigger">
          Condition
        </div>
      </div>

      <!-- content: template -->
      <div class="wcpt-tab-content">

        <div class="wcpt-editor-row-option">
          <label>Property name</label>
          <div
            wcpt-model-key="property_name"
            wcpt-block-editor=""
            wcpt-be-add-row="0"
          ></div>
        </div>

        <div class="wcpt-editor-row-option">
          <label>Property value</label>
          <div
            wcpt-model-key="property_value"
            wcpt-block-editor=""
            wcpt-be-add-row="0"
            wcpt-be-add-element-partial="add-property-value-element"
          ></div>
        </div>

      </div>

      <!-- content: condition -->
      <div class="wcpt-tab-content" wcpt-model-key="condition">
        <?php include( 'condition/inner.php' ); ?>
      </div>

    </div>

    <!-- corner options -->
    <?php wcpt_corner_options(); ?>

  </div>

  <button
    class="wcpt-button"
    wcpt-add-row-template="property_list_row"
  >
    Add a Row
  </button>

</div>

<!-- table layout -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="table_layout">
    Show in a table layout
  </label>
</div>

<!-- initial reveal -->
<div class="wcpt-editor-row-option">
  <label>Number of properties to reveal initially (default: 4)</label>
  <input type="number" wcpt-model-key="initial_reveal" min="0">
</div>

<!-- show more label -->
<div class="wcpt-editor-row-option">
  <label>Label for the 'Show more' button</label>
  <input type="text" wcpt-model-key="show_more_label" />
</div>

<!-- show less label -->
<div class="wcpt-editor-row-option">
  <label>Label for the 'Show less' button</label>
  <input type="text" wcpt-model-key="show_less_label" />
</div>

<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="table_layout"
  wcpt-condition-val="false"
>
  <!-- columns -->
  <div class="wcpt-editor-row-option">
    <label>
      Number of columns
    </label>
    <select wcpt-model-key="columns">
      <option value="1">1 column</option>
      <option value="2">2 columns</option>
      <option value="3">3 columns</option>
      <option value="4">4 columns</option>
      <option value="5">5 columns</option>
    </select>  
  </div>

  <!-- label above value -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="label_above_value_enabled" />
      Show property name and value in separate lines
    </label>
  </div>
</div>

<!-- style: other -->
<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <!-- rows -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion">
    <span class="wcpt-toggle-label">
      Style for Rows
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- odd row background color -->
    <div class="wcpt-editor-row-option" wcpt-model-key="[id].wcpt-property-list--table-layout .wcpt-pl-inner .wcpt-pl-row:nth-child(odd)">
      <label>Odd row background color</label>
      <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
    </div>

    <!-- even row background color -->
    <div class="wcpt-editor-row-option" wcpt-model-key="[id].wcpt-property-list--table-layout .wcpt-pl-inner .wcpt-pl-row:nth-child(even)">
      <label>Even row background color</label>
      <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
    </div>
  </div>

  <!-- prop name -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-property-name">

    <span class="wcpt-toggle-label">
      Style for Property Names
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- width -->
    <div class="wcpt-editor-row-option">
      <label>Width</label>
      <input type="text" wcpt-model-key="width" />
    </div>

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

    <!-- font-weight -->
    <div class="wcpt-editor-row-option">
      <label>Font weight</label>
      <select wcpt-model-key="font-weight">
        <option value="normal">Normal</option>
        <option value="bold">Bold</option>
        <option value="200">Light</option>
      </select>
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

  <!-- value -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-property-value">

    <span class="wcpt-toggle-label">
      Style for Property Values
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- width -->
    <div class="wcpt-editor-row-option">
      <label>Width</label>
      <input type="text" wcpt-model-key="width" />
    </div>

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

    <!-- font-weight -->
    <div class="wcpt-editor-row-option">
      <label>Font weight</label>
      <select wcpt-model-key="font-weight">
        <option value="normal">Normal</option>
        <option value="bold">Bold</option>
        <option value="200">Light</option>
      </select>
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

  <!-- trigger text -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-property-name .wcpt-media-image-wrapper">

    <span class="wcpt-toggle-label">
      Style for Media Image element in name (if any)
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- max-width -->
    <div class="wcpt-editor-row-option">
      <label>Max width</label>
      <input type="text" wcpt-model-key="max-width" />
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

  <!-- trigger text -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-tg-trigger">

    <span class="wcpt-toggle-label">
      Style for Toggle Button - Text
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

    <!-- font-weight -->
    <div class="wcpt-editor-row-option">
      <label>Font weight</label>
      <select wcpt-model-key="font-weight">
        <option value="normal">Normal</option>
        <option value="bold">Bold</option>
        <option value="200">Light</option>
      </select>
    </div>

    <!-- background -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
    </div>

    <!-- padding -->
    <div class="wcpt-editor-row-option">
      <label>Padding</label>
      <input type="text" wcpt-model-key="padding-top" placeholder="top">
      <input type="text" wcpt-model-key="padding-right" placeholder="right">
      <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
      <input type="text" wcpt-model-key="padding-left" placeholder="left">
    </div>

    <!-- margin top -->
    <div class="wcpt-editor-row-option">
      <label>Gap from list</label>
      <input type="text" wcpt-model-key="margin-top" style="width: 100% !important;">
    </div>

  </div>

  <!-- trigger icon -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-tg-trigger .wcpt-icon">

    <span class="wcpt-toggle-label">
    Style for Toggle Button - Icon
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Size</label>
      <input type="text" wcpt-model-key="font-size">
    </div>

    <!-- font-color -->
    <div class="wcpt-editor-row-option">
      <label>Stroke color</label>
      <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker" >
    </div>

    <!-- fill -->
    <div class="wcpt-editor-row-option">
      <label>Fill color</label>
      <input type="text" wcpt-model-key="fill" class="wcpt-color-picker" >
    </div>

    <!-- stroke-width -->
    <div class="wcpt-editor-row-option">
      <label>Thickness</label>
      <input type="text" wcpt-model-key="stroke-width">
    </div>

  </div>

  <!-- entire element -->
  <div 
    class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" 
    wcpt-model-key="[id]"
  >

    <span class="wcpt-toggle-label">
      Style for Container
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

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

    <!-- background -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
    </div>    

    <!-- border -->
    <div class="wcpt-editor-row-option wcpt-borders-style">
      <label>Border</label>
      <input type="text" wcpt-model-key="border-width" placeholder="width">
      <select wcpt-model-key="border-style">
        <option value="solid">Solid</option>
        <option value="dashed">Dashed</option>
        <option value="dotted">Dotted</option>
        <option value="none">None</option>
      </select>
      <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
    </div>

    <!-- border-radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius (corner roundness in px)</label>
      <input type="number" wcpt-model-key="border-radius">
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
  <label>Additional CSS Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
