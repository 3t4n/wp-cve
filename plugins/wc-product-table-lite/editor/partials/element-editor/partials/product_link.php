<!-- content -->
<div class="wcpt-editor-row-option">
  <label>
    Content
  </label>
  <div
    wcpt-block-editor
    wcpt-be-add-element-partial="add-product-link-element"
    wcpt-model-key="template"
  ></div>

  <label>
    <?php wcpt_general_placeholders__print_placeholders(); ?>
  </label>      
</div>

<!-- target -->
<div class="wcpt-editor-row-option">
  <label>Open on</label>
  <select wcpt-model-key="target">
    <option value="_self">Same page</option>
    <option value="_blank">New page</option>
  </select>
</div>

<!-- suffix -->
<div class="wcpt-editor-row-option <?php wcpt_pro_cover() ?>">
  <label>
    Link Suffix <?php wcpt_pro_badge(); ?>
  </label>
  <input type="text" wcpt-model-key="suffix" />
</div>

<!-- style -->
<?php include( 'style/common.php' ); ?>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
