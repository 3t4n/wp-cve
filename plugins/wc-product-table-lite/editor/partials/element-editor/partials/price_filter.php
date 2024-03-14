<?php if( get_option( 'woocommerce_tax_display_shop' ) == 'incl' ): ?>
<span class="wcpt-notice">
  You have chosen to display tax inclusive prices in your shop <a href="<?php echo get_admin_url(); ?>admin.php?page=wc-settings&tab=tax" target="_blank">here</a>. Please note that the WooCommerce team has admitted some price filter inaccuracies when this settings is enabled. If such issues occur consider setting 'Display prices in the shop' to 'Excluding tax'.
</span>
<?php endif; ?>

<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="position"
  wcpt-condition-val="header"
>
  <!-- display type -->  
  <div class="wcpt-editor-row-option">
    <label>Display type</label>
    <select wcpt-model-key="display_type">
      <option value="dropdown">Dropdown</option>
      <option value="row">Row</option>
      <!-- <option value="column">Column</option> -->
    </select>
  </div>

  <!-- heading separate line -->
  <div
    class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="display_type"
    wcpt-condition-val="row"
  >
    <label>
      <input type="checkbox" wcpt-model-key="heading_separate_line" />
      Show heading in separate line above
    </label>
  </div>
</div>

<!-- heading -->
<div class="wcpt-editor-row-option">
  <label>Heading</label>
  <div
    wcpt-block-editor
    wcpt-be-add-element-partial="add-navigation-filter-heading-element"
    wcpt-model-key="heading"
    wcpt-be-add-row="0"
  ></div>
</div>

<!-- heading format upon option selection -->  
<?php require( 'heading_format__op_selected.php' ); ?>

<div class="wcpt-editor-row-option">
  <label>"Show any" option label</label>
  <div
    wcpt-model-key="show_all_label"
    wcpt-block-editor
  >
</div>

<!-- range options -->
<div class="wcpt-editor-row-option">
  <label class="wcpt-editor-options-heading">Filter options</label>
</div>

<!-- range options -->
<div
  class="wcpt-editor-row-option wcpt-range-options-rows-wrapper"
  wcpt-model-key="range_options"
>
  <div
    class="wcpt-editor-row wcpt-editor-range-options-row"
    wcpt-controller="range_options"
    wcpt-model-key="[]"
    wcpt-model-key-index="0"
    wcpt-row-template="price_range_row_2"
    wcpt-initial-data="price_range_row_2"
  >

    <!-- min -->
    <div class="wcpt-editor-row-option">
      <label>Min price</label>
      <input type="number" wcpt-model-key="min">
    </div>

    <!-- max -->
    <div class="wcpt-editor-row-option">
      <label>Max price</label>
      <input type="number" wcpt-model-key="max" type="number">
    </div>

    <!-- label -->
    <div class="wcpt-tabs">

      <!-- triggers -->
      <div class="wcpt-tab-triggers">
        <div class="wcpt-tab-trigger">
          Label
        </div>
        <div class="wcpt-tab-trigger" wcpt-can-disable>
          Custom clear label
        </div>
      </div>

      <!-- content: label -->
      <div class="wcpt-tab-content">
        <div class="wcpt-editor-row-option">
          <div
            wcpt-model-key="label"
            class="wcpt-term-relabel-editor"
            wcpt-block-editor=""
            wcpt-be-add-row="1"
          ></div>
        </div>
      </div>

      <!-- content: clear fitler label -->
      <div class="wcpt-tab-content">
        <div class="wcpt-editor-row-option">
          <input type="text" wcpt-model-key="clear_label" placeholder="[filter] : [option]">
        </div>
      </div>

    </div>

    <!-- corner options -->
    <?php wcpt_corner_options(); ?>

  </div>

  <button
    class="wcpt-button"
    wcpt-add-row-template="price_range_row_2"
  >
    Add an Option
  </button>

</div>

<!-- Custom 'Min-Max' enabled -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="custom_min_max_enabled">
    Enable custom 'Min' & 'Max' input options
    <small>Enable to get the <a href="https://wcproducttable.com/documentation/multi-range-slider-for-cf-price-filters" target="_blank">range slider</a> option as well</small>
  </label>
</div>

<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="custom_min_max_enabled"
  wcpt-condition-val="true"
>

  <!-- range_slider_enabled -->
  <div class="wcpt-editor-row-option">
    <?php wcpt_pro_checkbox(true, 'Enable range slider beneath min max input', "range_slider_enabled"); ?>
  </div>

  <!-- min valuee -->
  <div class="wcpt-editor-row-option">
    <label>
      Min permitted value
      <small>Leave empty to get min from db automatically</small>
    </label>
    <input type="number" wcpt-model-key="min">
  </div>

  <!-- max value -->
  <div class="wcpt-editor-row-option">
    <label>
      Max permitted value
      <small>Leave empty to get max from db automatically</small>      
    </label>
    <input type="number" wcpt-model-key="max">
  </div>

  <!-- step -->
  <div class="wcpt-editor-row-option">
    <label>Step for range slider</label>
    <input type="number" wcpt-model-key="step" placeholder="1" min="0">
  </div>

  <!-- non_numeric_is_zero -->
  <!-- <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="non_numeric_is_zero">
      Treat non-numeric values as 0 when getting min and max from db
      <small>Uncheck to completely ignore these non-numeric values when getting min and max from db</small>
    </label>
  </div>      -->

  <!-- 'Min' -->
  <div class="wcpt-editor-row-option"  >
    <label>Label for "Min" placeholder</label>
    <input type="text" wcpt-model-key="min_label">
  </div>

  <!-- 'Max' -->
  <div class="wcpt-editor-row-option">
    <label>Label for "Max" placeholder</label>
    <input type="text" wcpt-model-key="max_label">
  </div>

  <!-- 'to' -->
  <div class="wcpt-editor-row-option">
    <label>Label for "to" (between min & max)</label>
    <input type="text" wcpt-model-key="to_label">
  </div>

  <!-- 'Go' -->
  <div class="wcpt-editor-row-option">
    <label>Label for "Go" (in submit button)</label>
    <input type="text" wcpt-model-key="go_label">
  </div>

  <!-- min_max_clear_label -->
  <div class="wcpt-editor-row-option">
    <label>Custom clear label (default)</label>
    <input type="text" wcpt-model-key="min_max_clear_label" placeholder="[filter] : [min] - [max]">
  </div>

  <!-- no_min_clear_label -->
  <div class="wcpt-editor-row-option">
    <label>Custom clear label - when no minimum value entered</label>
    <input type="text" wcpt-model-key="no_min_clear_label" placeholder="[filter] : Upto [max]">
  </div>

  <!-- no_max_clear_label -->
  <div class="wcpt-editor-row-option">
    <label>Custom clear label - when no maximum value entered</label>
    <input type="text" wcpt-model-key="no_max_clear_label" placeholder="[filter] : [min]+">
  </div>

</div>

<!-- accordion always open -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="accordion_always_open"> Keep filter open by default if it is in sidebar
  </label>
</div>

<?php include('style/filter.php'); ?>
