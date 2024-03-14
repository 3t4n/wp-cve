<div class="wcpt-editor-row-option <?php wcpt_pro_cover(); ?>">
  <label>
  Relabel rules 
  <?php wcpt_pro_badge(); ?>
  <small>
    By default the actual stock quantity will be printed.<br>
    Optionally, you can assign custom labels to stock in specific ranges. <br>
    Enter one rule per line with the format <em>min - max : label</em><br>
    Use the placeholder [stock] if you want to print the actual stock quantity for the range.<br> 
    To give a range an infinite upper limit use '+' next to min limit (see last rule below).<br>
    0 - 9: [stock]<br>
    10 - 49: 10+<br>
    50 - 99: 50+<br>
    100+: 100+
  </small>
  <small>
    Negative range can also be used for backorder. Eg:<br>
    -1000 - -1: on backorder
  </small>

  </label>
  <textarea wcpt-model-key="range_labels"></textarea>
</div>

<!-- variable switch -->
<div class="wcpt-editor-row-option">
  <?php wcpt_pro_checkbox('true', 'Switch stock based on selected variation', 'variable_switch'); ?>
</div>  

<!-- style -->
<?php include( 'style/common.php' ); ?>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
