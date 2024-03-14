<!-- attribute -->
<div class="wcpt-editor-row-option">
  <label>
    Attribute
  </label>

  <?php
    $attributes = wc_get_attribute_taxonomies();
    if( empty( $attributes ) ){
      echo '<div class="wcpt-notice">There are no WooCommerce attributes on this site!</div>';
      $hide_class = 'wcpt-hide';
    }
  ?>
  <select class="<?php echo empty( $attributes ) ? 'wcpt-hide' : '';  ?>" wcpt-model-key="attribute_name">
    <option value=""></option>
    <option value="_custom">*Custom attribute*</option>    
    <?php
      foreach( $attributes as $attribute ){
        ?>
        <option value="<?php echo $attribute->attribute_name; ?>">
          <?php echo $attribute->attribute_label; ?>
        </option>
        <?php
      }
    ?>
  </select>
</div>

<div
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="attribute_name"
  wcpt-condition-val="true"
>

  <div
    class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="attribute_name"
    wcpt-condition-val="_custom"
  >
    <!-- custom attribute name -->
    <div class="wcpt-editor-row-option">
      <label>Custom attribute name</label>
      <input type="text" wcpt-model-key="custom_attribute_name">
    </div>

    <!-- custom attribute name -->
    <div class="wcpt-editor-row-option">
      <label>
        <small>
          Note: Term relabel facility is only supported for <a href="https://docs.woocommerce.com/document/managing-product-taxonomies/#set-global-attributes" target="_blank">global attributes</a>
        </small>
      </label>
    </div>

  </div>

  <!-- terms in separate lines -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="separate_lines">
      Show multiple terms in separate lines
    </label>
  </div>  

  <div
    class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="separate_lines"
    wcpt-condition-val="false"
  >
    <!-- term separator -->
    <div class="wcpt-editor-row-option">
      <label>Separator between multiple terms</label>
      <div
        wcpt-model-key="separator"
        class="wcpt-separator-editor"
        wcpt-block-editor=""
        wcpt-be-add-row="0"
      ></div>
    </div>  
  </div>

  <!-- empty value relabel -->
  <div class="wcpt-editor-row-option">
    <label>Output when no terms</label>
    <div
      wcpt-model-key="empty_relabel"
      wcpt-block-editor=""
      wcpt-be-add-row="0"
    ></div>
  </div>  

  <div
    class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="attribute_name"
    wcpt-condition-val="!_custom"
  >

    <!-- exclude terms -->
    <div class="wcpt-editor-row-option">
      <label>
        Exclude terms by slug
        <small>Enter one term slug <em>per line</em></small>
      </label>
      <textarea wcpt-model-key="exclude_terms"></textarea>
    </div>

    <!-- link term to filter -->
    <div class="wcpt-editor-row-option">
      <label>
        Action on click:
      </label>
      <?php wcpt_pro_radio('', 'Do nothing', 'click_action'); ?>
      <?php wcpt_pro_radio('archive_redirect', 'Go to archive page', 'click_action'); ?>
      <?php wcpt_pro_radio('trigger_filter', 'Trigger matching filter in table navigation', 'click_action'); ?>
      <label
        wcpt-panel-condition="prop"
        wcpt-condition-prop="click_action"
        wcpt-condition-val="trigger_filter"      
      >
        <small>
          Note: This option requires you to have the corresponding navigation filter element setup in your table navigation section.
        </small>
      </label>  
    </div>

    <!-- relabel -->
    <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion">

      <span class="wcpt-toggle-label">
        Custom term labels <?php wcpt_pro_badge(); ?>
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <div class="wcpt-editor-loading" data-loading="terms" style="display: none;">
        <?php wcpt_icon('loader', 'wcpt-rotate'); ?> Loading ...
      </div>

      <div
        class="
          wcpt-editor-row-option
          <?php wcpt_pro_cover(); ?>
        "
        wcpt-model-key="relabels"
      >
        <div
          class="wcpt-editor-custom-label-setup"
          wcpt-controller="relabels"
          wcpt-model-key="[]"
          wcpt-model-key-index="0"
          wcpt-row-template="relabel_rule_term_column_element_2"
        >


          <div class="wcpt-tabs">

            <!-- triggers -->
            <div class="wcpt-tab-triggers">
              <div class="wcpt-tab-trigger" wcpt-content-template="term">
                Term name
              </div>
              <div class="wcpt-tab-trigger">
                Style
              </div>
            </div>

            <!-- content: term label -->
            <div class="wcpt-tab-content">
              <div class="wcpt-editor-row-option">
                <div
                  wcpt-model-key="label"
                  class="wcpt-term-relabel-editor"
                  wcpt-block-editor=""
                  wcpt-be-add-row="0"
                  wcpt-be-add-element-partial="add-term-element-col"
                ></div>
              </div>
            </div>

            <!-- content: term style -->
            <div class="wcpt-tab-content">

              <div class="wcpt-editor-row-option" wcpt-model-key="style">
                <div class="wcpt-editor-row-option" wcpt-model-key="[id]">

                  <!-- font color -->
                  <div class="wcpt-editor-row-option">
                    <label>Font color</label>
                    <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
                  </div>

                  <!-- background color -->
                  <div class="wcpt-editor-row-option">
                    <label>Background color</label>
                    <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
                  </div>

                  <!-- border color -->
                  <div class="wcpt-editor-row-option">
                    <label>Border color</label>
                    <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker">
                  </div>

                </div>
              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

  <!-- style -->  
  <?php include( 'style/parent-child.php' ); ?>

  <!-- condition -->
  <?php include( 'condition/outer.php' ); ?>

</div>

