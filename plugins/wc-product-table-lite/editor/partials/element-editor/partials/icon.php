<div class="wcpt-editor-row-option">
  <label>Icon name</label>
  <?php wcpt_print_icon_dopdown('name'); ?>
</div>

<div class="wcpt-editor-row-option">
  <label>
    HTML title attribute
    <small>Optional text that shows up on mouse hover</small>
  </label>
  <input type="text" wcpt-model-key="title" />
</div>

<div class="wcpt-editor-row-style-options" wcpt-model-key="style" style="margin-top: 25px;">

  <div class="wcpt-wrapper" wcpt-model-key="[id]">

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
      <label>Stroke thickness</label>
      <input type="text" wcpt-model-key="stroke-width">
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