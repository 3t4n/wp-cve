<!-- heading -->
<div class="wcpt-editor-row-option">
  <label>Heading</label>
  <div
    wcpt-block-editor
    wcpt-model-key="heading"
  ></div>
</div>

<!-- display type -->
<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="position"
  wcpt-condition-val="header"
>
  <label>Display type</label>
  <select wcpt-model-key="display_type">
    <option value="dropdown">Dropdown</option>
    <option value="row">Row</option>
  </select>
</div>

<!-- label -->
<div class="wcpt-editor-row-option">
  <label>'Only show favorites' label</label>
  <div
    wcpt-model-key="favorites_label"
    wcpt-block-editor
    wcpt-be-add-row="0"
  ></div>
</div>

<!-- enable 'view all' link -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="view_all_enabled"> Show 'view all favorites' link
  </label>
</div>

<!-- 'view all' label -->
<div class="wcpt-editor-row-option">
  <label>'View all favorites' label</label>
  <div
    wcpt-model-key="view_all_label"
    wcpt-block-editor
    wcpt-be-add-row="0"
  ></div>
</div>


<!-- accordion always open -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="accordion_always_open"> Keep filter open by default if it is in sidebar
  </label>
</div>

<?php include('style/filter.php'); ?>
