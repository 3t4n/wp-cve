<h2>Edit Cell Row</h2>

<!-- condition -->
<?php require( 'condition/outer.php' ); ?>

<!-- style -->
<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

    <span class="wcpt-toggle-label">
      Style for Row
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- margin-top -->
    <div class="wcpt-editor-row-option">
      <label>Gap above</label>
      <input type="text" wcpt-model-key="margin-top" class="wcpt-margin-input-force-full-width">
    </div>    

    <!-- margin-bottom -->
    <div class="wcpt-editor-row-option">
      <label>Gap below</label>
      <input type="text" wcpt-model-key="margin-bottom" class="wcpt-margin-input-force-full-width">
    </div>        

    <!-- padding -->
    <div class="wcpt-editor-row-option">
      <label>Padding</label>
      <input type="text" wcpt-model-key="padding-top" placeholder="top">
      <input type="text" wcpt-model-key="padding-right" placeholder="right">
      <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
      <input type="text" wcpt-model-key="padding-left" placeholder="left">
    </div>

    <!-- text-align -->
    <div class="wcpt-editor-row-option">
      <label>Text align</label>
      <select wcpt-model-key="text-align">
        <option value="">Auto</option>
        <option value="center">Center</option>
        <option value="left">Left</option>
        <option value="right">Right</option>
      </select>
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

    <!-- border-radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="border-radius" >
    </div>

  </div>

</div>

<div class="wcpt-editor-row-option">
  <label>Additional CSS Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>
