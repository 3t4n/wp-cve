<!-- custom field -->
<div class="wcpt-editor-row-option">
  <label>Custom field name</label>
  <input type="text" wcpt-model-key="field_name">
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

<!-- custom field generator: wordpress / ACF -->
<div class="wcpt-editor-row-option">
  <label>Custom field is managed by</label>
  <label>
    <input value="" type="radio" wcpt-model-key="manager"> WordPress (default)
  </label>
  <?php wcpt_pro_radio('acf', 'Advanced Custom Fields (ACF)', 'manager'); ?>
</div>


<div 
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="manager"
  wcpt-condition-val="acf"  
>

<!-- ACF field type -->
  <div
    class="wcpt-editor-row-option"  
  >
    <label>ACF field type</label>
    <label>
      <input value="basic" type="radio" wcpt-model-key="acf_field_type"> Basic (Text, Number) 
    </label>
    <label>
      <input value="choice" type="radio" wcpt-model-key="acf_field_type"> Choice (Select, Radio) 
    </label>  
  </div>

  <!-- ACF options -->
  <div 
    class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="acf_field_type"
    wcpt-condition-val="choice"  
  >
    <label>
      ACF field choices
      <small>Copy paste what you entered in the 'Choices' option in your ACF field settings to add those same options to this filter</small>
    </label>
    <textarea wcpt-model-key="acf_choices"></textarea>
  </div>
</div>

<!-- only show if manager:ACF is not selected -->
<div 
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="manager"
  wcpt-condition-val="!acf"  
>

  <!-- compare -->
  <div class="wcpt-editor-row-option">
    <label>Option type</label>
    <label>
      <input type="radio" wcpt-model-key="compare" name="compare" value="IN" />
      Exact
      <small>
        Each filter option will be a specific number or text
      </small> 
    </label>
    <label>
      <input type="radio" wcpt-model-key="compare" name="compare" value="BETWEEN" />
      Ranges
      <small>
        Each filter option will be a range. Eg: 0 - 10, 11 - 20
      </small>
    </label>
  </div>

  <!-- field value type -- exact match -->
  <div
    class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="compare"
    wcpt-condition-val="IN"
  >
    <div class="wcpt-editor-row-option">
      <label>
      Order the options as
      </label>
      <select wcpt-model-key="field_type__exact_match">
        <option value="NUMERIC">Number</option>
        <option value="CHAR">Text</option>
      </select>
    </div>

    <div class="wcpt-editor-row-option">
      <label>Order of the options</label>
      <select wcpt-model-key="order__exact_match">
        <option value="ASC">Ascending</option>
        <option value="DESC">Descending</option>
      </select>
    </div>
  </div>

  <!-- field value type -- range -->
  <div
    class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="compare"
    wcpt-condition-val="BETWEEN"
  >
    <div
      class="wcpt-editor-row-option"
      wcpt-panel-condition="prop"
      wcpt-condition-prop="range_slider_enabled"
      wcpt-condition-val="false"
    >
      <label>Field value type</label>
      <select wcpt-model-key="field_type">
        <option value="NUMERIC">Numeric</option>
        <option value="DECIMAL">Decimal</option>
        <option value="DATE">Date</option>
        <option value="TIME">Time</option>
        <option value="DATETIME">Datetime</option>
        <option value="CHAR">Char</option>
      </select>
    </div>
  </div>

  <!-- options -->
  <div class="wcpt-editor-row-option">
    <label class="wcpt-editor-options-heading">
      Filter options
      <small
        wcpt-panel-condition="prop"
        wcpt-condition-prop="compare"
        wcpt-condition-val="IN"
      >
        Leave this empty to let the plugin auto generate the filter options.
        <br>
        Or you can manually create the options if you need to show custom labels.
      </small>
      <small
        wcpt-panel-condition="prop"
        wcpt-condition-prop="compare"
        wcpt-condition-val="BETWEEN"
      >
      Use the 'Add an Option' button below to create range options for this filter
      </small>
    </label>
  </div>

  <!-- exact options -->
  <div class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="compare"
    wcpt-condition-val="IN"
  >

    <div
      class="wcpt-label-options-rows-wrapper wcpt-sortable"
      wcpt-model-key="manual_options"
    >
      <div
        class="wcpt-editor-row wcpt-editor-custom-label-setup"
        wcpt-controller="manual_options"
        wcpt-model-key="[]"
        wcpt-model-key-index="0"
        wcpt-row-template="cf_manual_option_2"
        wcpt-initial-data="custom_field_filter_manual_option"
      >

        <!-- value -->
        <div class="wcpt-editor-row-option">
          <label>Custom field value</label>
          <input type="text" wcpt-model-key="value" />
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
        wcpt-add-row-template="cf_manual_option_2"
      >
        Add an Option
      </button>

    </div>

    <div class="wcpt-editor-row-option">
      <label>
        Clear label template
        <small>
          This is the template that will be used by the 'Clear Filters' element
          <br>
          Use placeholders: [custom_field] [selected_value]
        </small>
      </label>
      <input type="text" placeholder="[custom_field]: [selected_value]" wcpt-model-key="clear_label_template" />
    </div>

  </div>

  <!-- range options -->
  <div
    class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="compare"
    wcpt-condition-val="BETWEEN"
  >
    <div
      class="wcpt-label-options-rows-wrapper wcpt-sortable"
      wcpt-model-key="range_options"
    >
      <div
        class="wcpt-editor-row wcpt-editor-custom-label-setup"
        wcpt-controller="range_options"
        wcpt-model-key="[]"
        wcpt-model-key-index="0"
        wcpt-row-template="cf_range_option_2"
        wcpt-initial-data="custom_field_filter_range_option"
      >

        <!-- min value -->
        <div class="wcpt-editor-row-option">
          <label>Min value</label>
          <input type="number" wcpt-model-key="min_value" />
        </div>

        <!-- max value -->
        <div class="wcpt-editor-row-option">
          <label>Max value</label>
          <input type="number" wcpt-model-key="max_value" />
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
        wcpt-add-row-template="cf_range_option_2"
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

      <!-- non_numeric_value_treatment -->
      <!-- <div class="wcpt-editor-row-option">
        <label>
          Treatment of non-numeric values
        </label>

        <label>
          <input type="radio" wcpt-model-key="non_numeric_value_treatment" value="convert_to_number">
          Convert to numeric value (+14C: 14, 15mA: 15, X: 0)
        </label>      

        <label>
          <input type="radio" wcpt-model-key="non_numeric_value_treatment" value="exclude">
          Ignore and exclude from the collected set of values
        </label>      

        <label>
          <input type="radio" wcpt-model-key="non_numeric_value_treatment" value="convert_to_zero">
          Treat as 0
        </label>
      </div>          -->

      <!-- ignore_values -->
      <!-- <div class="wcpt-editor-row-option"  >
        <label>
          Specifc value to ignore
          <small>Note: Use the pound symbol (|) to separate the values</small>
        </label>
        <input type="text" wcpt-model-key="ignore_values">
      </div> -->

      <!-- empty_value_treatment -->
      <!-- <div class="wcpt-editor-row-option">
        <label>
          Treatment of empty value
        </label>

        <label>
          <input type="radio" wcpt-model-key="empty_value_treatment" value="convert_to_zero">
          Treat as 0
        </label>      

        <label>
          <input type="radio" wcpt-model-key="empty_value_treatment" value="exclude">
          Ignore and exclude from the collected set of values
        </label>
      </div>    -->

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

  </div>

</div>

<!-- accordion always open -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="accordion_always_open"> Keep filter open by default if it is in sidebar
  </label>
</div>

<!-- enable search -->
<div class="wcpt-editor-row-option">
  <?php wcpt_pro_checkbox('true', 'Enable search box for the filter options', 'search_enabled'); ?>
</div>

<!-- search placeholder -->
<div 
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="search_enabled"
  wcpt-condition-val="true"
>
  <label>Placeholder for the search input box</label>
  <input type="text" wcpt-model-key="search_placeholder">
</div>

<?php include('style/filter.php'); ?>
