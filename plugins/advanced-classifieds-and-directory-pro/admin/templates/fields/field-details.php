<?php

/**
 * Metabox: Field Details.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-require-js" data-script="custom-fields">
  <table id="acadp-field-details" class="acadp-form-table form-table widefat">
    <tbody>
      <tr class="acadp-form-group-id">
        <th scope="row">
          <label class="acadp-form-control-id">
            <?php esc_html_e( 'Field ID', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>
        </th>
        <td>
          <?php echo esc_html( $post->ID ); ?>
        </td>
      </tr>
      <tr class="acadp-form-group-type">
        <th scope="row">
          <label for="acadp-form-control-type">
            <?php esc_html_e( 'Field Type', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>
        </th>
        <td>
          <select name="type" id="acadp-form-control-type" class="acadp-form-control acadp-form-select widefat">
            <?php
            $types = acadp_get_custom_field_types();
            $selected = isset( $post_meta['type'] ) ? $post_meta['type'][0] : 'text';
        
            foreach ( $types as $key => $label ) {
              printf( 
                '<option value="%s"%s>%s</option>', 
                $key, 
                selected( $selected, $key, false ), 
                $label 
              );
            }
            ?>
          </select>
        </td>
      </tr>
      <tr class="acadp-form-group-instructions">
        <th scope="row">
          <label for="acadp-form-control-instructions">
            <?php esc_html_e( 'Field Instructions', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Instructions for users. Shown when submitting data', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <textarea name="instructions" id="acadp-form-control-instructions" class="acadp-form-control acadp-form-textarea widefat" rows="6"><?php 
            if ( isset( $post_meta['instructions'] ) ) echo esc_textarea( $post_meta['instructions'][0] ); 
          ?></textarea>
        </td>
      </tr>
      <tr class="acadp-form-group-required">
        <th scope="row">
          <label for="acadp-form-control-required">
            <?php esc_html_e( 'Required?', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>
        </th>
        <td>
          <?php $selected = isset( $post_meta['required'] ) ? $post_meta['required'][0] : 0; ?>

          <ul id="acadp-form-control-required" class="acadp-flex acadp-gap-2 acadp-items-center acadp-m-0 acadp-p-0 acadp-list-none">
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="required" class="acadp-form-control acadp-form-radio" value="1" <?php echo checked( $selected, 1, false ); ?>>
                <?php esc_html_e( 'Yes', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="required" class="acadp-form-control acadp-form-radio" value="0" <?php echo checked( $selected, 0, false ); ?>>
                <?php esc_html_e( 'No', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
          </ul>
        </td>
      </tr>
      <tr class="acadp-form-group-choices acadp-conditional-fields acadp-field-type-select acadp-field-type-checkbox acadp-field-type-radio" hidden>
        <th scope="row">
          <label for="acadp-form-control-choices">
            <?php esc_html_e( 'Choices', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>
          
          <p class="description">
            <?php esc_html_e( 'Enter each choice on a new line.', 'advanced-classifieds-and-directory-pro' ); ?><br /><br />
            <?php esc_html_e( 'Red', 'advanced-classifieds-and-directory-pro' ); ?><br />
            <?php esc_html_e( 'Blue', 'advanced-classifieds-and-directory-pro' ); ?><br /><br />
            <?php esc_html_e( 'red : Red', 'advanced-classifieds-and-directory-pro' ); ?><br />
            <?php esc_html_e( 'blue : Blue', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <textarea name="choices" id="acadp-form-control-choices" class="acadp-form-control acadp-form-textarea widefat" rows="8"><?php 
            if ( isset( $post_meta['choices'] ) ) echo esc_textarea( $post_meta['choices'][0] ); 
          ?></textarea>
        </td>
      </tr>
      <tr class="acadp-form-group-default_value acadp-conditional-fields acadp-field-type-text acadp-field-type-select acadp-field-type-radio acadp-field-type-number acadp-field-type-range acadp-field-type-url">
        <th scope="row">
          <label for="acadp-form-control-default_value">
            <?php esc_html_e( 'Default Value', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Appears when creating a new listing', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <input type="text" name="default_value" id="acadp-form-control-default_value" class="acadp-form-control acadp-form-input widefat" value="<?php if ( isset( $post_meta['default_value'] ) ) echo esc_attr( $post_meta['default_value'][0] ); ?>" />
        </td>
      </tr>
      <tr class="acadp-form-group-default_value_textarea acadp-conditional-fields acadp-field-type-textarea" hidden>
        <th scope="row">
          <label for="acadp-form-control-default_value_textarea">
            <?php esc_html_e( 'Default Value', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Appears when creating a new listing', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <textarea name="default_value_textarea" id="acadp-form-control-default_value_textarea" class="acadp-form-control acadp-form-textarea widefat" rows="6"><?php 
            if ( isset( $post_meta['default_value'] ) ) echo esc_textarea( $post_meta['default_value'][0] ); 
          ?></textarea>
        </td>
      </tr>
      <tr class="acadp-form-group-default_value_checkbox acadp-conditional-fields acadp-field-type-checkbox" hidden>
        <th scope="row">
          <label for="acadp-form-control-default_value_checkbox">
            <?php esc_html_e( 'Default Value', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Enter each default value on a new line', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <textarea name="default_value_checkbox" id="acadp-form-control-default_value_checkbox" class="acadp-form-control acadp-form-textarea widefat" rows="8"><?php 
            if ( isset( $post_meta['default_value'] ) ) echo esc_textarea( $post_meta['default_value'][0] ); 
          ?></textarea>
        </td>
      </tr>
      <tr class="acadp-form-group-min acadp-conditional-fields acadp-field-type-number acadp-field-type-range">
        <th scope="row">
          <label for="acadp-form-control-min">
            <?php esc_html_e( 'Minimum Value', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Specifies the minimum value allowed', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <input type="text" name="min" id="acadp-form-control-min" class="acadp-form-control acadp-form-input widefat" value="<?php if ( isset( $post_meta['min'] ) ) echo esc_attr( $post_meta['min'][0] ); ?>" />
        </td>
      </tr>
      <tr class="acadp-form-group-max acadp-conditional-fields acadp-field-type-number acadp-field-type-range">
        <th scope="row">
          <label for="acadp-form-control-max">
            <?php esc_html_e( 'Maximum Value', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Specifies the maximum value allowed', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <input type="text" name="max" id="acadp-form-control-max" class="acadp-form-control acadp-form-input widefat" value="<?php if ( isset( $post_meta['max'] ) ) echo esc_attr( $post_meta['max'][0] ); ?>" />
        </td>
      </tr>
      <tr class="acadp-form-group-step acadp-conditional-fields acadp-field-type-number acadp-field-type-range">
        <th scope="row">
          <label for="acadp-form-control-step">
            <?php esc_html_e( 'Step Value', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Specifies the legal number intervals', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <input type="text" name="step" id="acadp-form-control-step" class="acadp-form-control acadp-form-input widefat" value="<?php if ( isset( $post_meta['step'] ) ) echo esc_attr( $post_meta['step'][0] ); ?>" />
        </td>
      </tr>
      <tr class="acadp-form-group-default_value_datetime acadp-conditional-fields acadp-field-type-date acadp-field-type-datetime" hidden>
        <th scope="row">
          <label for="acadp-form-control-default_value_datetime">
            <?php esc_html_e( 'Default Value', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Appears when creating a new listing', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <ul id="acadp-form-control-default_value_datetime" class="acadp-flex acadp-gap-2 acadp-items-center">
            <li>
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="checkbox" name="default_value_datetime" class="acadp-form-control acadp-form-checkbox" value="1" <?php if ( isset( $post_meta['default_value'] ) ) checked( $post_meta['default_value'][0], 1 ); ?>>
                <?php esc_html_e( 'Set Current Date/Time', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
          </ul>
        </td>
      </tr>
      <tr class="acadp-form-group-allow_null acadp-conditional-fields acadp-field-type-select">
        <th scope="row">
          <label for="acadp-form-control-allow_null">
            <?php esc_html_e( 'Allow Null?', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php _e( 'If selected, the select list will begin with a null value titled <br /> "— Select an Option —"', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <?php $selected_allow_null = isset( $post_meta['allow_null'] ) ? $post_meta['allow_null'][0] : 1; ?>

          <ul id="acadp-form-control-allow_null" class="acadp-flex acadp-gap-2 acadp-items-center acadp-m-0 acadp-p-0 acadp-list-none">
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="allow_null" class="acadp-form-control acadp-form-radio" value="1" <?php echo checked( $selected_allow_null, 1, false ); ?>>
                <?php esc_html_e( 'Yes', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="allow_null" class="acadp-form-control acadp-form-radio" value="0" <?php echo checked( $selected_allow_null, 0, false ); ?>>
                <?php esc_html_e( 'No', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
          </ul>
        </td>
      </tr>
      <tr class="acadp-form-group-placeholder acadp-conditional-fields acadp-field-type-text acadp-field-type-textarea acadp-field-type-number acadp-field-type-date acadp-field-type-datetime acadp-field-type-url">
        <th scope="row">
          <label for="acadp-form-control-placeholder">
            <?php esc_html_e( 'Placeholder Text', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Appears within the input', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <input type="text" name="placeholder" id="acadp-form-control-placeholder" class="acadp-form-control acadp-form-input widefat" value="<?php if ( isset( $post_meta['placeholder'] ) ) echo esc_attr( $post_meta['placeholder'][0] ); ?>" />
        </td>
      </tr>
      <tr class="acadp-form-group-rows acadp-conditional-fields acadp-field-type-textarea" hidden>
        <th scope="row">
          <label for="acadp-form-control-rows">
            <?php esc_html_e( 'Rows', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Sets the textarea height', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <input type="text" name="rows" id="acadp-form-control-rows" class="acadp-form-control acadp-form-input widefat" placeholder="8" value="<?php if ( isset( $post_meta['rows'] ) ) echo esc_attr( $post_meta['rows'][0] ); ?>" />
        </td>
      </tr>
      <tr class="acadp-form-group-target acadp-conditional-fields acadp-field-type-url" hidden>
        <th scope="row">
          <label for="acadp-form-control-target">
            <?php esc_html_e( 'Open link in a new window?', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>
        </th>
        <td>
          <?php $selected_target = isset( $post_meta['target'] ) ? $post_meta['target'][0] : '_blank'; ?>

          <ul id="acadp-form-control-target" class="acadp-flex acadp-gap-2 acadp-items-center acadp-m-0 acadp-p-0 acadp-list-none">
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="target" class="acadp-form-control acadp-form-radio" value="_blank" <?php echo checked( $selected_target, '_blank', false ); ?>>
                <?php esc_html_e( 'Yes', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="target" class="acadp-form-control acadp-form-radio" value="_self" <?php echo checked( $selected_target, '_self', false ); ?>>
                <?php esc_html_e( 'No', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
          </ul>
        </td>
      </tr>
      <tr class="acadp-form-group-nofollow acadp-conditional-fields acadp-field-type-url" hidden>
        <th scope="row">
          <label for="acadp-form-control-nofollow">
            <?php esc_html_e( 'Use rel="nofollow" when displaying the link?', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>
        </th>
        <td>
          <?php $selected_nofollow = isset( $post_meta['nofollow'] ) ? $post_meta['nofollow'][0] : 1; ?>

          <ul id="acadp-form-control-nofollow" class="acadp-flex acadp-gap-2 acadp-items-center acadp-m-0 acadp-p-0 acadp-list-none">
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="nofollow" class="acadp-form-control acadp-form-radio" value="1" <?php echo checked( $selected_nofollow, 1, false ); ?>>
                <?php esc_html_e( 'Yes', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="nofollow" class="acadp-form-control acadp-form-radio" value="0" <?php echo checked( $selected_nofollow, 0, false ); ?>>
                <?php esc_html_e( 'No', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
          </ul>
        </td>
      </tr>
    </tbody>
  </table>
</div>