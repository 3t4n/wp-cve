<!-- custom_field -->
<div class="wcpt-editor-row-option">
  <label>Custom field name</label>
  <input type="text" wcpt-model-key="field_name">
</div>

<!-- custom field generator: wordpress / ACF -->
<div class="wcpt-editor-row-option">
  <label>Custom field is managed by:</label>
  <label>
    <input value="" type="radio" wcpt-model-key="manager"> WordPress (default)
  </label>
  <?php wcpt_pro_radio('acf', 'Advanced Custom Fields (ACF)', 'manager'); ?>
</div>

<!-- display_as -->
<div 
  class="wcpt-editor-row-option" 
  wcpt-panel-condition="prop" 
  wcpt-condition-prop="manager" 
  wcpt-condition-val="false"
>

  <div class="wcpt-editor-row-option">
    <label>Display custom field value as <?php wcpt_pro_badge(); ?></label>
    <div class="<?php wcpt_pro_cover(); ?>">
      <select wcpt-model-key="display_as">
        <?php
          $options = array(
            'text'       => 'Text (default)',
            'html'       => 'HTML (also parses shortcodes)',
            'link'       => 'Website link',
            'pdf_link'   => 'PDF / downloadable file link',
            'phone_link' => 'Phone number link',
            'email_link' => 'Email address link',
            'image'      => 'Image',
          );
          foreach( $options as $val => $label ){
            echo '<option value="'. $val .'">'. $label .'</option>';
          }
        ?>
      </select>
    </div>
  </div>

  <!-- target -->
  <div 
    class="wcpt-editor-row-option" 
    wcpt-panel-condition="prop" 
    wcpt-condition-prop="display_as" 
    wcpt-condition-val="link"
  >
    <label>Open custom field link on</label>
    <select wcpt-model-key="link_target">
      <?php
        $options = array(
          '_self'  => 'Same page',
          '_blank' => 'New page',
        );

        foreach( $options as $val => $label ){
          echo "<option value='$val' >$label</option>";
        }
      ?>
    </select>
  </div>

  <div 
    class="wcpt-editor-row-option" 
    wcpt-panel-condition="prop" 
    wcpt-condition-prop="display_as" 
    wcpt-condition-val="image"
  >

    <!-- img val type -->
    <div class="wcpt-editor-row-option">
      <label>Custom field value contains:</label>
      <label>
        <input value="url" type="radio" wcpt-model-key="img_val_type"> Image URL
      </label>
      <label>
        <input value="id" type="radio" wcpt-model-key="img_val_type"> Media image ID
      </label>
    </div>

    <!-- size -->
    <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="img_val_type" wcpt-condition-val="id">
      <label>Select media image size</label>
      <select wcpt-model-key="media_img_size">
        <?php
          foreach( get_intermediate_image_sizes() as $image_size ){
            echo "<option value='" . $image_size . "'>". ucfirst( str_replace( '_', ' ', $image_size ) ) ."</option>";
          }
        ?>
      </select>
    </div>

    <!-- max-width -->
    <div class="wcpt-editor-row-option">
      <label>Max width of image (px)</label>
      <input type="number" wcpt-model-key="img_max_width" />
    </div>

  </div>

  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="display_as" wcpt-condition-val="pdf_link">

    <!-- pdf val type -->
    <div class="wcpt-editor-row-option">
      <label>Custom field value contains:</label>
      <label>
        <input value="url" type="radio" wcpt-model-key="pdf_val_type"> File URL
      </label>
      <label>
        <input value="id" type="radio" wcpt-model-key="pdf_val_type"> Media file ID
      </label>
    </div>

  </div>

  <!-- label: cf -->
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="display_as" wcpt-condition-val="pdf_link">
    <label>
      Label
      <small>
        Placeholders: [cf_value]
      </small>
    </label>
    <div
      wcpt-model-key="pdf_link_label"
      wcpt-block-editor
      wcpt-be-add-row="0"
    ></div>
  </div>

</div>

<!-- empty value relabel -->
<div class="wcpt-editor-row-option">
  <label>Output when no custom field value exists</label>
  <div
    wcpt-model-key="empty_relabel"
    wcpt-block-editor
    wcpt-be-add-row="0"
  ></div>
</div>

<!-- variable switch -->
<div 
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop" 
  wcpt-condition-prop="manager" 
  wcpt-condition-val="!acf"
>
  <?php wcpt_pro_checkbox('true', 'Switch custom field based on selected variation', 'variable_switch'); ?>
  <label><small>Only plain custom field values (non-ACF) are supported here</small></label>
</div>  

<!-- style -->
<?php include( 'style/common.php' ); ?>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
