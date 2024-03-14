<!-- notice -->
<div class="wcpt-editor-row-option">
  <small>The 'Apply' button is not <em>necessary</em> for filtering. If you do not add this button, filtering will take place automatically whenever user changes filter options.</small>
</div>

<!-- apply label -->
<div class="wcpt-editor-row-option">
  <label>
    Apply label
    <small>Leave empty to hide 'Apply' button</small>  
  </label>
  <div
    wcpt-model-key="apply_label"
    wcpt-block-editor=""
    wcpt-be-add-row="0"
  ></div>
</div>

<!-- reset label -->
<div class="wcpt-editor-row-option">
  <label>
    Reset label
    <small>Leave empty to hide 'Reset' button</small>
  </label>
  <div
    wcpt-model-key="reset_label"
    wcpt-block-editor=""
    wcpt-be-add-row="0"
  ></div>
</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <!-- style for apply -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-apply">

    <span class="wcpt-toggle-label">
      Style for Apply
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <?php require( 'style/common-props.php' ); ?>

  </div>

  <!-- style for reset -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-reset">

    <span class="wcpt-toggle-label">
      Style for Reset
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <?php require( 'style/common-props.php' ); ?>

  </div>

</div>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>
