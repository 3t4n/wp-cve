<div
  class="wcpt-column-settings wcpt-toggle-column-expand"
  wcpt-controller="column_settings"
  wcpt-model-key="[]"
  wcpt-model-key-index="0"
  wcpt-row-template="column_settings_<?php echo $device; ?>"
  wcpt-initial-data="column_settings"
>

  <!-- <div class="wcpt-column-toggle-capture"></div> -->

  <?php ob_start(); ?>
    <i class="wcpt-editor-row-expand" wcpt-expand title="Expand">
      <?php wcpt_icon('maximize-2'); ?>
    </i>
    <i class="wcpt-editor-row-contract" wcpt-expand title="Contract">
      <?php wcpt_icon('minimize-2'); ?>
    </i>
  <?php wcpt_corner_options(array('prepend'=> ob_get_clean())); ?>

  <!-- column index -->
  <span class="wcpt-column-setttings__top-left">
    <span class="wcpt-column-device-icon-container"></span>
    <span class="wcpt-column-count">0</span> 
    <input 
      class="wcpt-column-name wcpt-diw--disabled"
      type="text" 
      wcpt-model-key="name" 
      placeholder="Column name"
    />
  </span>

  <!-- heading -->
  <div class="wcpt-tabs" wcpt-model-key="heading">
    <div class="wcpt-tab-triggers">
      <div class="wcpt-tab-trigger">
        Column Heading
      </div>
      <div class="wcpt-tab-trigger">
        Heading Design
      </div>
    </div>

    <!-- heading editor -->
    <div class="wcpt-tab-content">
      <div class="wcpt-block-editor wcpt-column-heading-editor" wcpt-model-key="content"></div>
    </div>

    <!-- design options -->
    <div class="wcpt-tab-content">
      <?php include ('element-editor/partials/column-heading-style.php'); ?>
    </div>

  </div>

  <!-- template -->
  <div class="wcpt-tabs" wcpt-model-key="cell">
    <div class="wcpt-tab-triggers">
      <div class="wcpt-tab-trigger">
        Column Cell
      </div>
      <div class="wcpt-tab-trigger">
        Cell Design
      </div>
    </div>

    <!-- template editor -->
    <div class="wcpt-tab-content">
      <div class="wcpt-block-editor wcpt-column-template-editor" wcpt-model-key="template"></div>
    </div>

    <!-- design options -->
    <div class="wcpt-tab-content">
      <?php include ('element-editor/partials/column-cell-style.php'); ?>
    </div>

  </div>

</div>

<button
  class="wcpt-button"
  wcpt-add-row-template="column_settings_<?php echo $device; ?>"
>
  <?php wcpt_icon('plus-circle'); ?>
  <span>Add a <?php echo $device; ?> column</span>
</button>
