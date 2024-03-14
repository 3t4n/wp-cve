<div class="wcpt-editor-row-option <?php wcpt_pro_cover(); ?>">
  <!-- custom field condition -->
  <div class="wcpt-editor-row-option">
      <select wcpt-model-key="action">
        <option value="show">Show element only if ALL conditions are met</option>
        <option value="hide">Hide element only if ALL conditions are met</option>
        <option value="show_any">Show element if ANY condition is met</option>
        <option value="hide_any">Hide element if ANY condition is met</option>
      </select>
  </div>

  <!-- custom field condition -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="custom_field_enabled">
      Add custom field condition
    </label>
  </div>

  <div
    class="wcpt-editor-row-option wcpt-editor-row-option--inset-options"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="custom_field_enabled"
    wcpt-condition-val="true"
  >

    <div class="wcpt-editor-row-option">
      <label>
        Custom field name
      </label>
      <input type="text" wcpt-model-key="custom_field">
    </div>

    <div class="wcpt-editor-row-option">
      <label>
        Permitted values to meet condition
        <small>Any value will do: <i>leave empty</i></small>
        <small>Single permitted value: <i>value 1</i></small>
        <small>Multiple permitted values: <i>value 1 || value 2 || value 3</i></small>
        <small>Range of permitted numeric values: <i>150 - 600</i></small>
        <small>No value should be set: <i>-</i></small>
      </label>
      <input type="text" wcpt-model-key="custom_field_value">
    </div>

  </div>

  <!-- attribute condition -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="attribute_enabled">
      Add attribute condition
    </label>
  </div>

  <div
    class="wcpt-editor-row-option wcpt-editor-row-option--inset-options"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="attribute_enabled"
    wcpt-condition-val="true"
  >

    <div class="wcpt-editor-row-option">
      <label>Attribute</label>
      <?php
        $attributes = wc_get_attribute_taxonomies();
        if( empty( $attributes ) ){
          echo '<div class="wcpt-notice">There are no WooCommerce attributes on this site!</div>';
          $hide_class = 'wcpt-hide';
        }
      ?>
      <select class="<?php echo empty( $attributes ) ? 'wcpt-hide' : '';  ?>" wcpt-model-key="attribute">
        <option value=""></option>
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

    <div class="wcpt-editor-row-option">
      <label>
        Term slugs
        <small>Any term will do: <i>leave empty</i></small>
        <small>Single permitted term: <i>term-1</i></small>
        <small>Multiple permitted terms: <i>term-1 || term-2 || term-3</i></small>
        <small>No term should be associated: <i>-</i></small>
      </label>
      <input type="text" wcpt-model-key="attribute_term">
    </div>

  </div>

  <!-- category condition -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="category_enabled">
      Add category condition
    </label>
  </div>

  <div
    class="wcpt-editor-row-option wcpt-editor-row-option--inset-options"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="category_enabled"
    wcpt-condition-val="true"
  >

    <div class="wcpt-editor-row-option">
      <label>
        Category slugs
        <small>
          Enter multiple possible categories with || separator: <br/>
          <i>category-1 || category-2 || category-3</i>
        </small>      
      </label>
      <input type="text" wcpt-model-key="category"/>
    </div>

  </div>

  <!-- price condition -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="price_enabled">
      Add price condition
    </label>

    <div
      class="wcpt-editor-row-option wcpt-editor-row-option--inset-options"
      wcpt-panel-condition="prop"
      wcpt-condition-prop="price_enabled"
      wcpt-condition-val="true"
    >

      <div class="wcpt-editor-row-option">
        <label>
          Acceptable price / price-range
          <small>
            Does not apply to variable products currently.
            Range from 150 to 600: <i>150 - 600</i><br>
            For less than 150: <i>0 - 150</i><br>
            For greater than 150: <i>150 - 10000</i><br>
          </small>
        </label>
        <input type="text" wcpt-model-key="price">
      </div>

    </div>
  </div>

  <!-- stock condition -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="stock_enabled">
      Add stock condition
    </label>

    <div
      class="wcpt-editor-row-option wcpt-editor-row-option--inset-options"
      wcpt-panel-condition="prop"
      wcpt-condition-prop="stock_enabled"
      wcpt-condition-val="true"
    >

      <div class="wcpt-editor-row-option">
        <label>
          Acceptable stock quantity / stock range
          <small>Stock range: <i>4 - 8</i></small>
          <small>Negative stock range (backorder): <i>-4 - 10</i></small>
          <small>Multiple permitted options: <i>4 || 8 || 12</i></small>
          <small>In stock: <i>instock</i></small>
          <small>Out of stock: <i>outofstock</i></small>
          <small>On backorder: <i>onbackorder</i></small>
        </label>
        <input type="text" wcpt-model-key="stock">
      </div>

    </div>
  </div>

  <!-- product type condition -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="product_type_enabled">
      Add product type condition
    </label>

    <div
      class="wcpt-editor-row-option wcpt-editor-row-option--inset-options"
      wcpt-panel-condition="prop"
      wcpt-condition-prop="product_type_enabled"
      wcpt-condition-val="true"
    >
      <div class="wcpt-editor-row-option">
        <label>
          Product types:
        </label>
        <?php
          foreach( wc_get_product_types() as $product_type => $label ){
            ?>
            <label class="wcpt-editor-checkbox-label">
              <input type="checkbox" value="<?php echo strtolower( $product_type ); ?>" wcpt-model-key="product_type[]">
              <?php echo str_replace( ' product', '', $label ); ?>
            </label>
            <?php
          }
        ?>
      </div>

    </div>
  </div>

  <!-- store timings condition -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="store_timings_enabled">
      Add store timings condition
    </label>

    <div
      class="wcpt-editor-row-option wcpt-editor-row-option--inset-options"
      wcpt-panel-condition="prop"
      wcpt-condition-prop="store_timings_enabled"
      wcpt-condition-val="true"
    >

      <div class="wcpt-editor-row-option">
        <label>
          Select the shop's timezone
        </label>   
        <select wcpt-model-key="timezone">
          <?php echo wp_timezone_choice( 'UTC+0', get_user_locale() ); ?>
        </select>
      </div>      

      <div class="wcpt-editor-row-option">
        <label>
          <small>
              Enter only one day-timings rule per line<br>
              Multiple timeslots for the same day are separated by "|"<br>
              You can also use "[open_all_day]" and "[closed_all_day]"<br>
              To target specific dates enter in the format: "month date, year: timings" <br>
              <br>
              monday: 1000 - 1400 | 1600 - 2000<br>
              tuesday: 1000 - 1400 | 1600 - 2000<br>
              wednesday: 1000 - 1400 | 1600 - 2000<br>
              thursday: 1000 - 1400 | 1600 - 2000<br>
              friday: 1000 - 1400 | 1600 - 2000<br><br>
              saturday: [open_all_day]<br>
              sunday: [closed_all_day]<br><br>
              December 31, 2000: [closed_all_day]<br>
              January 1, 2001: [open_all_day]<br>
              January 2, 2001: 1000 - 1400 | 1600 - 2000<br>
          </small>
        </label>   
        <textarea wcpt-model-key="store_timings" style="height: 200px; width: 100%;"></textarea>
      </div>

    </div>

  </div>

  <!-- user role -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="user_role_enabled">
      Add user role condition
    </label>

    <div
      class="wcpt-editor-row-option wcpt-editor-row-option--inset-options"
      wcpt-panel-condition="prop"
      wcpt-condition-prop="user_role_enabled"
      wcpt-condition-val="true"
    >
      <div class="wcpt-editor-row-option">
        <label>
          User roles:
        </label>
        <?php
          global $wp_roles;
          $user_roles = array('_visitor' => 'Guest') + $wp_roles->get_names();

          foreach( $user_roles as $role => $label ){
            ?>
            <label class="wcpt-editor-checkbox-label">
              <input type="checkbox" value="<?php echo strtolower( $role ); ?>" wcpt-model-key="user_role[]">
              <?php echo $label; ?>
            </label>
            <?php
          }
        ?>
      </div>

    </div>
  </div>

</div>
