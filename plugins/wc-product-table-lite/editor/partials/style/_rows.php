<!-- background-color -->
<div wcpt-model-key="[container] tr.wcpt-<?php echo $sequence; ?> > .wcpt-cell">
  <div class="wcpt-editor-option-row">
    <label>Background color</label>
    <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
  </div>
</div>

<!-- background-color on hover -->
<div wcpt-model-key="[container] tr.wcpt-<?php echo $sequence; ?>:hover > .wcpt-cell">
  <div class="wcpt-editor-option-row">
    <label>↳ on hover</label>
    <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
  </div>
</div>

<!-- font-color -->
<div wcpt-model-key="[container] tr.wcpt-<?php echo $sequence; ?> > .wcpt-cell">
  <div class="wcpt-editor-option-row">
    <label>Font color</label>
    <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
  </div>
</div>
