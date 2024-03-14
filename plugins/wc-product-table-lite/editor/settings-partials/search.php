<div class="wcpt-toggle-options wcpt-search-rules" wcpt-model-key="search">

  <div class="wcpt-editor-light-heading wcpt-toggle-label">
    Search 
    <?php wcpt_pro_badge(); ?>
    <?php wcpt_icon('chevron-down'); ?>
  </div>

  <div class="<?php wcpt_pro_cover(); ?>">

    <div class="wcpt-editor-row-option">  
      <a class="wcpt-search__doc" href="https://wcproducttable.com/documentation/search" target="_blank">How to use →</a>
    </div>
    <!-- stopwords -->
    <div class="wcpt-editor-row-option">
      <label>
        Stopwords
        <small>
          These are generic words to be excluded during search to conserve server resource and increase result accuracy. They will be included during full keyword phrase search.<br>
          Comma separate the stopwords.
        </small>
      </label>
      <textarea wcpt-model-key="stopwords"></textarea>
    </div>

    <!-- replacements -->
    <div class="wcpt-editor-row-option">
      <label>
        Replacements
        <small>
          Correct common spelling mistakes and smartly replace keywords to increase result accuracy. Will not affect full keyword phrase search. <br>
          Enter one correction per line in this format: Correction: Incorrect 1 | Incorrect 2 ...
        </small>
      </label>
      <textarea wcpt-model-key="replacements"></textarea>
    </div>

    <!-- sort by relevance label -->
    <div class="wcpt-editor-row-option">
      <label>
        'Sort by relevance' label
        <small>For multiple translations enter one per line like this:</small>
        <small>
          Sort by relevance <br>
          en_US: Sort by relevance<br>
          fr_FR: Trier par pertinence <br>
        </small>
      </label>
      <textarea wcpt-model-key="relevance_label"></textarea>
    </div>

    <!-- override global settings -->
    <div class="wcpt-editor-row-option" wcpt-model-key="override_settings">

      <label>
        Search page override settings 
        <small>Note: First enable search page override from Archive Override facility</small>
        <small>Note: Use [wcpt_search] in a text widget to provide search bar with category selector</small>
      </label>    

      <div class="wcpt-editor-row-option">
        <label>Select target fields to search through:</label>
        <!-- target -->
        <?php foreach( array('Title', 'Content', 'Excerpt', 'SKU', 'Custom field', 'Category', 'Attribute', 'Tag') as $field ): ?>
        <?php $model_val = strtolower( str_replace(' ', '_', $field) ); ?>
        <?php 
          if( in_array( $field, array( 'Title', 'Content' ) ) ){
            ?>
            <label>
              <input 
                type="checkbox" 
                value="<?php echo $model_val; ?>" 
                wcpt-model-key="target[]" 
              />
              <?php 
              if( $field == 'excerpt' ){
                echo 'Short description';
              }else{
                echo $field; 
              }
              ?>
            </label>
            <?php
          }else{
            wcpt_pro_checkbox($model_val, $field, "target[]");
          }
        ?>

          <?php if( $model_val === 'custom_field' ): ?>
            <div
              class="wcpt-checkbox-selection-group"
              wcpt-panel-condition="prop"
              wcpt-condition-prop="target"
              wcpt-condition-val="custom_field"
            >
              <label>
                <small>Select custom fields to search through:</small>
              </label>
              <?php foreach( wcpt_get_product_custom_fields() as $meta_name ): ?>
                <label class="wcpt-editor-checkbox-label">
                  <input 
                    type="checkbox" 
                    wcpt-model-key="custom_fields[]" 
                    value="<?php echo esc_attr( $meta_name ); ?>"
                  />
                  <?php echo esc_attr( $meta_name ); ?>
                </label>
              <?php endforeach; ?>

              <!-- enter key -->
              <div class="wcpt-editor-row-option" style="margin-bottom: 20px; padding-left: 0;">
                <label>You can also enter custom field names one per line:</label>
                <textarea wcpt-model-key="custom_fields_textarea"></textarea>
              </div>

            </div>
          <?php endif; ?>

          <?php if( $model_val === 'attribute' ): ?>
            <div
              class="wcpt-checkbox-selection-group"
              wcpt-panel-condition="prop"
              wcpt-condition-prop="target"
              wcpt-condition-val="attribute"
            >
              <label>
                <small>Select attributes to search through:</small>
              </label>
              <?php foreach( wc_get_attribute_taxonomies() as $attribute ): ?>
                <label class="wcpt-editor-checkbox-label">
                  <input 
                    type="checkbox" 
                    wcpt-model-key="attributes[]" 
                    value="<?php echo esc_attr( $attribute->attribute_name ); ?>"
                  />
                  <?php echo esc_attr( $attribute->attribute_label ); ?>
                </label>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

        <?php endforeach; ?>
      </div>

      <!-- include variation skus -->
      <div 
        class="wcpt-editor-row-option"
        wcpt-panel-condition="prop"
        wcpt-condition-prop="target"
        wcpt-condition-val="sku"  
      >
        <label>  
          <input type="checkbox" wcpt-model-key="include_variation_skus" />
          Include variation SKUs in search
        </label>
      </div>

    </div>

    <!-- weightage rules -->
    <div class="wcpt-editor-row-option">
      <label>
        Weightage rules
        <small>Assign relative weights for keyword matches to different product properties</small>
      </label>
    </div>
    
    <?php 
      foreach( array( 'title', 'sku', 'category', 'attribute', 'tag', 'content', 'excerpt', 'custom_field' ) as $field ){
        $heading = str_replace( array('-', '_'), ' ', ucfirst( $field ) ); 
        if( $field === 'sku' ){
          $heading = 'SKU';
        }

        if( in_array( $field, array( 'attribute', 'custom_field' ) ) ){
          require 'search__common1.php';
        }else{
          require 'search__common2.php';
        }

      }
    ?>
  </div>
</div>
