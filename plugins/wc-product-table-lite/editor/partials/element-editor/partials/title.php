<div class="wcpt-editor-row-option">
  <label>Link:</label>
  <label><input type="radio" wcpt-model-key="link" value="" /> None </label>
  <label><input type="radio" wcpt-model-key="link" value="product_page" /> Product page </label>
  <?php wcpt_pro_radio('custom_field', 'Custom field', 'link'); ?>
</div>

<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="link"
  wcpt-condition-val="custom_field"
>
  <label>
    Custom field name
  </label>
  <input type="text" wcpt-model-key="custom_field" />
</div>

<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="link"
  wcpt-condition-val="custom_field"
>
  <label>
    <input type="checkbox" wcpt-model-key="custom_field_default_product_page" />
    Link to product page if custom field has no value
  </label>
</div>

<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="link"
  wcpt-condition-val="product_page||custom_field"
>
  <label>
    <input type="checkbox" wcpt-model-key="target_new_page" />
    Open the link on a new page
  </label>
</div>

<!-- HTML tag -->
<div class="wcpt-editor-row-option">
  <label>HTML tag <?php wcpt_pro_badge(); ?></label>
  <div class="<?php wcpt_pro_cover(); ?>">
    <select wcpt-model-key="html_tag">
      <?php
        $options = array(
          'span'=> 'span',
          'h1'  => 'H1',
          'h2'  => 'H2',
          'h3'  => 'H3',
          'h4'  => 'H4',
        );
        foreach( $options as $val => $label ){
          echo '<option value="'. $val .'">'. $label .'</option>';
        }
      ?>
    </select>
    <!-- <label>
      <small>
        <?php echo esc_html( "<span> wrapper won't be applied over <a> tag" ); ?>
      </small>
    </label> -->
  </div>
</div>

<!-- style -->
<?php include( 'style/common.php' ); ?>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
