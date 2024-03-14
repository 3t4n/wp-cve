<?php if( isset( $data ) && is_array( $data ) ) : ?>
  <div class="secondline_import_field_row secondline_import_field_row-<?php echo esc_attr( $data[ 'type' ] ); ?>"
       <?php if( isset( $data[ 'conditional' ] ) ) : ?>
       data-secondline-conditional-display="<?php echo esc_attr( $data[ 'conditional' ] ); ?>"
       <?php endif; ?>
  >
    <?php if( $data[ 'type' ] === 'checkbox' ) : ?>
      <input type="hidden" name="<?php echo esc_attr( $data[ 'name' ] ) ?>" value="<?php echo esc_attr( $data[ 'value_unchecked' ] ); ?>"/>
      <label>
        <input type="checkbox"
               name="<?php echo esc_attr( $data[ 'name' ] ) ?>"
               value="<?php echo esc_attr( $data[ 'value_checked' ] ); ?>"
              <?php echo checked( ( $data['value'] ?? null ), $data[ 'value_checked' ],false ); ?>
        />
        <?php echo esc_attr( $data[ 'label' ] ); ?>
      </label>
    <?php else : ?>
      <label><?php echo esc_attr ($data[ 'label' ]); ?></label>
      <?php if( $data[ 'type' ] === 'select' || $data[ 'type' ] === 'multiple_select' ) : ?>
        <select
            <?php if( $data[ 'type' ] === 'multiple_select' ) : ?>
            name="<?php echo esc_attr( $data[ 'name' ] ) ?>[]"
            multiple="multiple"
            <?php else : ?>
            name="<?php echo esc_attr($data[ 'name' ]) ?>"
            <?php endif; ?>
            <?php echo isset( $data[ 'class' ] ) ? 'class="' . esc_attr( $data[ 'class' ] ) . '"' : '' ?>
        >
          <?php foreach( $data[ 'options'] as $option_key => $option_value ) : ?>
            <?php if( is_array( $option_value ) ) : ?>
              <option value="<?php echo esc_attr( $option_key ); ?>" <?php echo ( isset( $data[ 'value' ] ) ? podcast_importer_secondline_utility_selected( $data[ 'value' ], $option_key, false ) : '' ) ?>
                  <?php foreach( $option_value as $k => $v ) :
                          if( $k === 'label' )
                            continue;

                          echo esc_attr($k) . '="' . esc_attr( $v ) . '"';
                        endforeach;
                  ?>
                ><?php echo esc_attr($option_value[ 'label' ]); ?></option>
            <?php else : ?>
              <option value="<?php echo esc_attr( $option_key ); ?>" <?php echo ( isset( $data[ 'value' ] ) ? selected($data[ 'value' ], $option_key, false ) : '' ) ?>
                    ><?php echo esc_attr($option_value); ?></option>
            <?php endif;?>
          <?php endforeach; ?>
        </select>

      <?php elseif( $data[ 'type' ] === 'wp_dropdown_users' ) : ?>

        <?php
        wp_dropdown_users( [
          'name' => $data[ 'name' ],
          'selected' => ( $data['value'] ?? '' )
        ] );
        ?>

      <?php elseif( $data[ 'type' ] === 'media_image_id' ) : ?>
        <?php $attachment_id = $data['value'] ?? ''; ?>
        <div class="secondline_import_field_media_image_handler">
          <input type="hidden" name="<?php echo esc_attr( $data[ 'name' ] ) ?>" value="<?php echo esc_attr( $attachment_id ) ?>"/>
          <?php if( $attachment_id !== '' ) : ?>
            <img src="<?php echo wp_get_attachment_image_src( $attachment_id )[ 0 ]; ?>"/>
          <?php endif; ?>
          <span class="button button-primary"><?php echo esc_html__( 'Set Image', 'podcast-importer-secondline' ); ?></span>
          <span class="button button-secondary button-delete" <?php echo $attachment_id === '' ? 'style="display:none;"' : ''; ?>>
            <?php echo esc_html__( 'Remove Image', 'podcast-importer-secondline' ); ?>
          </span>
        </div>
      <?php else : ?>
        <input type="<?php echo esc_attr( $data[ 'type' ] ) ?>"
               name="<?php echo esc_attr( $data[ 'name' ] ) ?>"
               value="<?php echo isset( $data[ 'value' ] ) ? esc_attr( $data[ 'value' ] ) : '' ?>"
          <?php echo isset( $data[ 'required' ] ) && $data[ 'required' ] ? 'required="required"' : '' ?>
          <?php echo isset( $data[ 'placeholder' ] ) ? 'placeholder="' . $data[ 'placeholder' ] . '"' : '' ?>
        />
      <?php endif; ?>

    <?php endif; ?>

    <?php if( isset( $data[ 'description' ] )  ) : ?>
      <div class="secondline_import_field_description"><?php echo esc_html($data[ 'description' ]); ?></div>
    <?php endif; ?>
  </div>
<?php else : ?>
  <p>_form-field.php has not been called correctly, missing $data.</p>
<?php endif; ?>