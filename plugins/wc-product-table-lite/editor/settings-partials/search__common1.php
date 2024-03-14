<!-- <?php echo $field; ?> -->
<div
  class="wcpt-editor-row-option wcpt-toggle-options wcpt-search__field"
  wcpt-model-key="<?php echo $field; ?>"
  wcpt-controller="search_rules"
>

  <div class="wcpt-editor-light-heading wcpt-toggle-label">
    <?php echo $heading; ?> rules <?php wcpt_icon('chevron-down'); ?>
  </div>

    <?php 
      include( 'search__rules.php' );  

      if( $field == 'custom_field' ){
        echo '<div class="wcpt-editor-row-option"><a style="padding-left: 22px" href="'. admin_url( 'edit.php?post_type=wc_product_table&page=wcpt-settings&wcpt_refresh_custom_fields=true#search' ) .'">Refresh custom field list</a></div>';
      }
    ?>

    <div
      class="wcpt-editor-row-option" 
      wcpt-model-key="items"
    >
      <div
        class="wcpt-editor-row-option wcpt-toggle-options wcpt-search__field"
        wcpt-model-key="[]"
        wcpt-model-key-index="0"
        wcpt-row-template="search_term_rules"
        wcpt-controller="search_rules"
      >
        <div class="wcpt-editor-light-heading wcpt-toggle-label">
          <span wcpt-content-template="label"></span> <?php wcpt_icon('chevron-down'); ?>
        </div>

        <!-- <div class="wcpt-search__field-state wcpt-toggle-escape">
          <span class="wcpt-search__enabled"><?php wcpt_icon('check'); ?> Enabled</span>
          <span class="wcpt-search__disabled"><?php wcpt_icon('x'); ?>Disabled</span>
        </div> -->

        <input type="hidden" wcpt-model-key="item" />

        <!-- <div class="wcpt-editor-row-option">
          <label>
            <input type="checkbox" wcpt-model-key="enabled"> Enable search
          </label>
        </div> -->

        <!-- <div 
          class="wcpt-editor-row-option"
          wcpt-panel-condition="prop"
          wcpt-condition-prop="enabled"
          wcpt-condition-val="true"
        > -->

          <div class="wcpt-editor-row-option">
            <label>
              <input type="checkbox" wcpt-model-key="custom_rules_enabled"> Custom relevance rules
            </label>
          </div>

          <div 
            class="wcpt-editor-row-option wcpt-search-rules__custom-rules"
            wcpt-panel-condition="prop"
            wcpt-condition-prop="custom_rules_enabled"
            wcpt-condition-val="true"
          >
            <?php include( 'search__rules.php' );  ?>
          </div>

        <!-- </div> -->

      </div>

    </div>
  <!-- </div> -->

</div>