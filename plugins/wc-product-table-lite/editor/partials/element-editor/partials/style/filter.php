<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="position"
  wcpt-condition-val="header"
>
  <div class="wcpt-editor-row-option" wcpt-model-key="style">

    <!-- Dropdown Heading -->
    <div 
      class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" 
      wcpt-model-key=".wcpt-navigation:not(.wcpt-left-sidebar) [id].wcpt-dropdown.wcpt-filter > .wcpt-filter-heading"
    >

      <span class="wcpt-toggle-label">
        Style: Dropdown heading
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- font-size -->
      <div class="wcpt-editor-row-option">
        <label>Font size</label>
        <input type="text" wcpt-model-key="font-size" />
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

      <!-- font color -->
      <div class="wcpt-editor-row-option">
        <label>Font color</label>
        <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
      </div>

      <!-- font color:hover -->
      <div class="wcpt-editor-row-option">
        <label>↳ on hover</label>
        <input type="text" wcpt-model-key="color:hover" placeholder="#000" class="wcpt-color-picker">
      </div>

      <!-- background color -->
      <div class="wcpt-editor-row-option">
        <label>Background color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

      <!-- background color on hover -->
      <div class="wcpt-editor-row-option">
        <label>↳ on hover</label>
        <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
      </div>

      <!-- border color -->
      <div class="wcpt-editor-row-option">
        <label>Border color</label>
        <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker">
      </div>

      <!-- border color on hover -->
      <div class="wcpt-editor-row-option">
        <label>↳ color on hover</label>
        <input type="text" wcpt-model-key="border-color:hover" class="wcpt-color-picker">
      </div>

      <!-- border radius -->
      <div class="wcpt-editor-row-option">
        <label>Border radius</label>
        <input type="text" wcpt-model-key="border-radius" />
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

    <!-- Dropdown Menu -->
    <div 
      class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" 
      wcpt-model-key=".wcpt-navigation:not(.wcpt-left-sidebar) [id].wcpt-dropdown.wcpt-filter > .wcpt-dropdown-menu"
    >

      <span class="wcpt-toggle-label">
        Style: Dropdown menu
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- font-size -->
      <div class="wcpt-editor-row-option">
        <label>Font size</label>
        <input type="text" wcpt-model-key="font-size" />
      </div>

      <!-- font-weight -->
      <div class="wcpt-editor-row-option">
        <label>Font weight</label>
        <select wcpt-model-key="font-weight">
          <option value=""></option>    
          <option value="normal">Normal</option>
          <option value="bold">Bold</option>
          <option value="light">Light</option>
          <option value="100">100</option>
          <option value="200">200</option>
          <option value="300">300</option>
          <option value="400">400</option>
          <option value="500">500</option>
          <option value="600">600</option>
          <option value="700">700</option>
          <option value="800">800</option>
          <option value="900">900</option>
        </select>
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

      <!-- border radius -->
      <div class="wcpt-editor-row-option">
        <label>Border radius</label>
        <input type="text" wcpt-model-key="border-radius" />
      </div>

      <!-- width -->
      <div class="wcpt-editor-row-option">
        <label>Width</label>
        <input type="text" wcpt-model-key="width" />
      </div>

      <!-- max-height -->
      <div class="wcpt-editor-row-option">
        <label>Max height</label>
        <input type="text" wcpt-model-key="max-height" />
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


    <!-- Row Heading -->
    <div 
      class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" 
      wcpt-model-key=".wcpt-navigation:not(.wcpt-left-sidebar) [id].wcpt-options-row.wcpt-filter > .wcpt-filter-heading"
    >

      <span class="wcpt-toggle-label">
        Style: Row heading
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- font-size -->
      <div class="wcpt-editor-row-option">
        <label>Font size</label>
        <input type="text" wcpt-model-key="font-size" />
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

      <!-- font color -->
      <div class="wcpt-editor-row-option">
        <label>Font color</label>
        <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
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

    <!-- Row Options -->
    <div 
      class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" 
      wcpt-model-key=".wcpt-navigation:not(.wcpt-left-sidebar) [id].wcpt-options-row.wcpt-filter > .wcpt-options > .wcpt-option"
    >

      <span class="wcpt-toggle-label">
        Style: Row options
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- font-size -->
      <div class="wcpt-editor-row-option">
        <label>Font size</label>
        <input type="text" wcpt-model-key="font-size" />
      </div>

      <!-- font-weight -->
      <div class="wcpt-editor-row-option">
        <label>Font weight</label>
        <select wcpt-model-key="font-weight">
          <option value=""></option>    
          <option value="normal">Normal</option>
          <option value="bold">Bold</option>
          <option value="light">Light</option>
          <option value="100">100</option>
          <option value="200">200</option>
          <option value="300">300</option>
          <option value="400">400</option>
          <option value="500">500</option>
          <option value="600">600</option>
          <option value="700">700</option>
          <option value="800">800</option>
          <option value="900">900</option>
        </select>
      </div>

      <!-- font color -->
      <div class="wcpt-editor-row-option">
        <label>Font color</label>
        <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
      </div>

      <!-- font color:hover -->
      <div class="wcpt-editor-row-option">
        <label>↳ on hover</label>
        <input type="text" wcpt-model-key="color:hover" placeholder="#000" class="wcpt-color-picker">
      </div>
      
      <!-- font color:selected -->
      <div class="wcpt-editor-row-option">
        <label>↳ on selected</label>
        <input type="text" wcpt-model-key="color:selected" placeholder="#000" class="wcpt-color-picker">
      </div>            

      <!-- background color -->
      <div class="wcpt-editor-row-option">
        <label>Background color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

      <!-- background color:hover -->
      <div class="wcpt-editor-row-option">
        <label>↳ on hover</label>
        <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
      </div>

      <!-- background color:selected -->
      <div class="wcpt-editor-row-option">
        <label>↳ on selected</label>
        <input type="text" wcpt-model-key="background-color:selected" class="wcpt-color-picker">
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

      <!-- border color:hover -->
      <div class="wcpt-editor-row-option">
        <label>↳ color on hover</label>
        <input type="text" wcpt-model-key="border-color:hover" class="wcpt-color-picker">
      </div>

      <!-- border color:selected -->
      <div class="wcpt-editor-row-option">
        <label>↳ color on selected</label>
        <input type="text" wcpt-model-key="border-color:selected" class="wcpt-color-picker">
      </div>

      <!-- border radius -->
      <div class="wcpt-editor-row-option">
        <label>Border radius</label>
        <input type="text" wcpt-model-key="border-radius" />
      </div>

      <!-- width -->
      <div class="wcpt-editor-row-option">
        <label>Width</label>
        <input type="text" wcpt-model-key="width" />
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
</div>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>
