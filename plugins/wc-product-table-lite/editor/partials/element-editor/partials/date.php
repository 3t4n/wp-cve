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
      <option value="timestamp" >Timestamp (eg: <?php echo time(); ?>)</option>
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

<!-- Format -->
<div class="wcpt-editor-row-option">
  <label>
  Output date format
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
  <input type="text" wcpt-model-key="format" placeholder="j M, Y">
</div>

<!-- style -->
<?php include( 'style/common.php' ); ?>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
