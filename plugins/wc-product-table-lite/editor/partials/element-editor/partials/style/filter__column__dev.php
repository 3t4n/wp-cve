<div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion">

  <span class="wcpt-toggle-label">
    Style for Column
    <?php echo wcpt_icon('chevron-down'); ?>
  </span>

  <div 
    class="wcpt-editor-row-option"
    wcpt-model-key="[id].wcpt-options-column > .wcpt-options"
  >
    <!-- height -->
    <div class="wcpt-editor-row-option">
      <label>
        Height
        <small>Use can use 'auto' </small>
      </label>
      <input type="text" wcpt-model-key="height" />
    </div>

    <!-- width -->
    <div class="wcpt-editor-row-option">
      <label>Width</label>
      <input type="text" wcpt-model-key="width" />
    </div>

  </div>

  <div 
    class="wcpt-editor-row-option"
    wcpt-model-key="[id]"
  >
    <!-- border color -->
    <div class="wcpt-editor-row-option">
      <label>Border color</label>
      <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker">
    </div>

    <!-- background color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
    </div>

    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Font size</label>
      <input type="text" wcpt-model-key="font-size" />
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