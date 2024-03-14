<!-- Pagination container -->
<div wcpt-model-key="[container] .wcpt-pagination">

  <!-- margin-top -->
  <div class="wcpt-editor-option-row">
    <label>Gap from table</label>
    <input type="text" wcpt-model-key="margin-top" style="width: 100% !important;" />
  </div>

</div>

<!-- Pagination buttons -->
<div wcpt-model-key="[container] .wcpt-pagination > .page-numbers">

  <!-- font-size -->
  <div class="wcpt-editor-option-row">
    <label>Font size</label>
    <input type="text" wcpt-model-key="font-size" />
  </div>

  <!-- line-height -->
  <div class="wcpt-editor-option-row">
    <label>Line height</label>
    <input type="text" wcpt-model-key="line-height" placeholder="1.2em">
  </div>

  <!-- font color -->
  <div class="wcpt-editor-option-row">
    <label>Font color</label>
    <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
  </div>

  <!-- font color: hover -->
  <div class="wcpt-editor-option-row">
    <label>↳ on hover</label>
    <input type="text" wcpt-model-key="color:hover" placeholder="#000" class="wcpt-color-picker">
  </div>

  <!-- font color: selected -->
  <div class="wcpt-editor-option-row">
    <label>↳ on selected</label>
    <input type="text" wcpt-model-key="color:selected" placeholder="#000" class="wcpt-color-picker">
  </div>

  <!-- font-weight -->
  <div class="wcpt-editor-option-row">
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

  <!-- font-family -->
  <div class="wcpt-editor-option-row">
    <label>Font family</label>
    <input type="text" wcpt-model-key="font-family" />
  </div>

  <!-- background color -->
  <div class="wcpt-editor-option-row">
    <label>Background color</label>
    <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
  </div>

  <!-- background color:hover -->
  <div class="wcpt-editor-option-row">
    <label>↳ on hover</label>
    <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
  </div>

  <!-- background color:selected -->
  <div class="wcpt-editor-option-row">
    <label>↳ on select</label>
    <input type="text" wcpt-model-key="background-color:selected" class="wcpt-color-picker">
  </div>

  <!-- border -->
  <div class="wcpt-editor-option-row wcpt-borders-style">
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
  <div class="wcpt-editor-option-row">
    <label>↳ color on hover</label>
    <input type="text" wcpt-model-key="border-color:hover" class="wcpt-color-picker" style="width: 100%;">
  </div>

  <!-- border color:selected -->
  <div class="wcpt-editor-option-row">
    <label>↳ color on select</label>
    <input type="text" wcpt-model-key="border-color:selected" class="wcpt-color-picker" style="width: 100%;">
  </div>

  <!-- border-radius -->
  <div class="wcpt-editor-option-row">
    <label>Border radius</label>
    <input type="text" wcpt-model-key="border-radius" >
  </div>

  <!-- padding -->
  <div class="wcpt-editor-option-row">
    <label>Padding</label>
    <input type="text" wcpt-model-key="padding-top" placeholder="top">
    <input type="text" wcpt-model-key="padding-right" placeholder="right">
    <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="padding-left" placeholder="left">
  </div>

  <!-- margin -->
  <div class="wcpt-editor-option-row">
    <label>Margin</label>
    <input type="text" wcpt-model-key="margin-top" placeholder="top">
    <input type="text" wcpt-model-key="margin-right" placeholder="right">
    <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom">
    <input type="text" wcpt-model-key="margin-left" placeholder="left">
  </div>

</div>

<!-- Pagination arrows -->
<div wcpt-model-key="[container] .wcpt-pagination > .page-numbers.next .wcpt-icon, [container] .wcpt-pagination > .page-numbers.prev .wcpt-icon">

  <!-- font-size -->
  <div class="wcpt-editor-option-row">
    <label>Arrow size</label>
    <input type="text" wcpt-model-key="font-size" placeholder="24px" />
  </div>

  <!-- stroke-width -->
  <div class="wcpt-editor-option-row">
    <label>Arrow thickness</label>
    <input type="text" wcpt-model-key="stroke-width" placeholder="3px" />
  </div>

  <!-- color -->
  <div class="wcpt-editor-option-row">
    <label>Arrow color</label>
    <input type="text" wcpt-model-key="color" class="wcpt-color-picker" />
  </div>

</div>
