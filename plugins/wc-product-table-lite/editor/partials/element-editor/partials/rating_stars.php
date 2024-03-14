<!-- highlight color range -->
<div class="wcpt-editor-row-option <?php wcpt_pro_cover(); ?>">
  <div class="wcpt-editor-row-option">
    <label>
      Highlight color range (optional)
      <small>
        Enter in following format:<br>
        0 - 1.99: #EF9A9A<br>
        2 - 2.99: #FFA726<br>
        3 - 3.99: #cddc39<br>
        4 - 4.99: #8BC34A<br>
        5: #388E3C
      </small>
    </label>
    <textarea 
      style="height: 120px" 
      wcpt-model-key="highlight_color_range"
    ></textarea>
  </div>
</div>

<!-- style -->
<div class="wcpt-editor-row-style-options wcpt-editor-row-option" wcpt-model-key="style">
  <div wcpt-model-key="[id]">
    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Size</label>
      <input type="text" wcpt-model-key="font-size" placeholder="16px">
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

  <div class="wcpt-editor-row-option" wcpt-model-key="[id] .wcpt-star:not(.wcpt-star-empty) > svg:first-child">
    <!-- font-color -->
    <div class="wcpt-editor-row-option">
      <label>Highlight color (default)</label>
      <input type="text" wcpt-model-key="color" class="wcpt-color-picker" >
    </div>
  </div>

  <div class="wcpt-editor-row-option" wcpt-model-key="[id] .wcpt-star:not(.wcpt-star-full) > svg:last-child">
    <!-- font-color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="color" class="wcpt-color-picker" >
    </div>
  </div>
</div>
