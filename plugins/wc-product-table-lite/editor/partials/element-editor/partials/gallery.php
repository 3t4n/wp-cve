<!-- note -->
<!-- <div class="wcpt-editor-row-option">
  <label class="wcpt-element-note">
    <p>
      In case of variable products, please keep in mind, this element will only display images from the product's gallery. So if you want variation images to show up then please add them to your product's gallery.
    </p> 
  </label>
</div> -->

<!-- max images -->
<div class="wcpt-editor-row-option">
  <label>
    Maximum number of image thumbnails
  </label>
  <input type="number" wcpt-model-key="max_images" />  
</div>

<!-- see more label -->
<div class="wcpt-editor-row-option">
  <label>
    'See more' label
    <small>Placeholder: use {n} for remaining image count</small>
  </label>
  <input type="text" wcpt-model-key="see_more_label" />  
</div>

<!-- include featured -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="include_featured" />    
    Include featured image among thumbnails 
  </label>
</div>

<!-- offset zoom enabled -->
<div class="wcpt-editor-row-option">
  <?php wcpt_pro_checkbox(true, 'Show an additional offset zoomed image on hover', 'offset_zoom_enabled'); ?>
</div>

<!-- style -->
<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <!-- thumbnails -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-gallery__item-wrapper">

    <span class="wcpt-toggle-label">
      Style for Thumbnails
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- width -->
    <div class="wcpt-editor-row-option">
      <label>Width</label>
      <input type="text" wcpt-model-key="width" />
    </div>

    <!-- margin -->
    <div class="wcpt-editor-row-option">
      <label>Margin</label>
      <input type="text" wcpt-model-key="margin-top" placeholder="top">
      <input type="text" wcpt-model-key="margin-right" placeholder="right">
      <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom">
      <input type="text" wcpt-model-key="margin-left" placeholder="left">
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

    <!-- border-color on hover -->
    <div class="wcpt-editor-row-option">
      <label>Border color on hover</label>
      <input type="text" wcpt-model-key="border-color:hover" class="wcpt-color-picker" placeholder="color">
    </div>

    <!-- border-radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="border-radius" >
    </div>    
    
  </div>

  <!-- see more label -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-gallery__see-more-label">

    <span class="wcpt-toggle-label">
      Style for 'See More' label
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
    <div class="wcpt-editor-row-option">
      <label>Font family</label>
      <input type="text" wcpt-model-key="font-family" />
    </div>
    
  </div>

</div>

<!-- offset zoom image container style -->
<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="offset_zoom_enabled"
  wcpt-condition-val="true"
>
  <div wcpt-model-key="style">
    <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]--offset-zoom-image">

      <span class="wcpt-toggle-label">
        Style for Offset Zoom Image
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- max-width -->
      <div class="wcpt-editor-row-option">
        <label>
          Max width
          <small>Image width can be smaller but will never exceed this value</small>
        </label>
        <input type="text" wcpt-model-key="max-width" />
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

      <!-- background-color -->
      <div class="wcpt-editor-row-option">
        <label>Background color</label>
        <input type="text" wcpt-model-key="background-color" />
      </div>

      <!-- padding -->
      <div class="wcpt-editor-row-option">
        <label>Padding</label>
        <input type="text" wcpt-model-key="padding-top" placeholder="top">
        <input type="text" wcpt-model-key="padding-right" placeholder="right">
        <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
        <input type="text" wcpt-model-key="padding-left" placeholder="left">
      </div>

    </div>
  </div>
</div>

<!-- lightbox -->
<div class="wcpt-editor-row-option">
  <!-- <div wcpt-model-key="style"> -->
    <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion">

      <span class="wcpt-toggle-label">
        Style for LightBox
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <div class="wcpt-editor-row-option">
        <label>Color theme:</label>
        <label>
          <input value="black" type="radio" wcpt-model-key="lightbox_color_theme"> Black
        </label>
        <label>
          <input value="white" type="radio" wcpt-model-key="lightbox_color_theme"> White
        </label>
      </div>

    </div>
  <!-- </div> -->
</div>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
