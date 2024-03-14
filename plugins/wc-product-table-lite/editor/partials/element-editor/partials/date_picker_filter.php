<div class="wcpt-editor-row-option">
  <label>
    Source for date
  </label>  
  <label>
    <input value="publish_date" type="radio" wcpt-model-key="date_source"> Product publish date
  </label>
  <?php wcpt_pro_radio('wordpress_custom_field', 'WordPress custom field', 'date_source'); ?>
  <?php wcpt_pro_radio('acf_custom_field', 'ACF custom field', 'date_source'); ?>
</div>

<!-- wordpress custom field -->
<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="date_source"
  wcpt-condition-val="wordpress_custom_field"
>
  <div class="wcpt-editor-row-option">
    <label>
      Custom field name
    </label>
    <input type="text" wcpt-model-key="custom_field_name">
  </div>

  <div class="wcpt-editor-row-option">
    <label>
      Custom field value type
      <small>Must be a database sortable value type</small>
    </label>
    <select wcpt-model-key="custom_field_type">
      <option value="numeric"   >Timestamp (eg: <?php echo time(); ?>)</option>
      <option value="date"      >Date (format: YYYY-MM-DD)</option>
      <option value="datetime"  >Datetime (format: YYYY-MM-DD HH:MI:SS)</option>
    </select>
  </div>  
</div>

<!-- acf custom field -->
<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="date_source"
  wcpt-condition-val="acf_custom_field"
>
  <div class="wcpt-editor-row-option">
    <label>
      ACF field name
    </label>
    <input type="text" wcpt-model-key="acf_field_name">
  </div>

  <div class="wcpt-editor-row-option">
    <label>
      ACF field type
      <small>Only the following database sortable field types will work</small>
    </label>
    <select wcpt-model-key="acf_field_type">
      <option value="date_picker"      >Date picker</option>
      <option value="datetime_picker"  >Datetime picker</option>
    </select>
  </div>    
</div>

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
    </select>
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

<!-- input field labels -->
<div 
  class="wcpt-editor-row-option wcpt-editor-row-option--parent" 
  wcpt-model-key="filter_option_labels"
>
  <label>Input field labels</label>

  <!-- start date -->
  <div class="wcpt-editor-row-option">
    <label>Start date</label>
    <input type="text" wcpt-model-key="start_date" placeholder="Events from date:">
  </div>

  <!-- end date -->
  <div class="wcpt-editor-row-option">
    <label>End date</label>
    <input type="text" wcpt-model-key="end_date" placeholder="Events before date:">
  </div>

  <!-- apply -->
  <div class="wcpt-editor-row-option">
    <label>Apply</label>
    <input type="text" wcpt-model-key="apply" placeholder="Apply">
  </div>  

  <!-- reset -->
  <div class="wcpt-editor-row-option">
    <label>Reset</label>
    <input type="text" wcpt-model-key="reset" placeholder="Reset">
  </div>    
</div>


<!-- clear filter labels -->
<div 
  class="wcpt-editor-row-option wcpt-editor-row-option--parent" 
  wcpt-model-key="clear_filter_labels"
>
  <label>Clear filter labels</label>

  <!-- start date clear filter -->
  <div class="wcpt-editor-row-option">
    <label>
      Start date
      <small>
        Use the placeholder {date} to print the date
      </small>
    </label>
    <input type="text" wcpt-model-key="start_date" placeholder="From date: {date}">
  </div>

  <!-- end date clear filter -->
  <div class="wcpt-editor-row-option">
    <label>
      End date
      <small>
        Use the placeholder {date} to print the date
      </small>
    </label>
    <input type="text" wcpt-model-key="end_date" placeholder="Until date: {date}">
  </div>

  <!-- Format -->
  <div class="wcpt-editor-row-option">
    <label>
    Date format
      <small>
        <div style="display: table;">
          <div style="display: table-row">
            <div style="display: table-cell; width: 75px;">
              Format
            </div>
            <div style="display: table-cell">
              Date
            </div>
          </div>

          <?php 
          foreach(array(
            'F j, Y',
            'M j, Y',
            'j F, Y',
            'j M, Y',
            'm/d/Y',
            'd/m/Y',
          ) as $format){
            ?>
            <div style="display: table-row">
              <div style="display: table-cell">
                <?php echo $format ?>
              </div>
              <div style="display: table-cell">
              <?php echo date($format) ?>
              </div>
            </div>
            <?php
          }        
          ?>
        </div>
      </small>
    </label>
    <input type="text" wcpt-model-key="date_format" placeholder="j M, Y">
  </div>

</div>

<!-- accordion always open -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="accordion_always_open"> Keep filter open by default if it is in sidebar
  </label>
</div>

<?php include('style/filter.php'); ?>
