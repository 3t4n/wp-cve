<div class="hjqs-ln-group">
    <div class="hjqs-ln-inputs">
		<?php
		if ( ! isset( $field ) ) {
			return;
		}
		// Stocker les valeurs utilisées plusieurs fois dans des variables
		$field_type    = $field->get_type();
		$option_key    = $field->get_option_key();
		$value         = is_array( $field->get_value() ) ? $field->get_value() : esc_html( $field->get_value() );
		$default_value = $field->get_default_value();
		$choices       = $field->get_choices();
		$form_slug     = $field->get_form_slug();

		// Permet d'afficher le champ personnalisé
		$render_custom_field = function ( $key, $choice, $form_slug ) {
			$label = sprintf( '<span class="hjqs-ln-radio-label">%s</span>', is_array( $choice ) ? $choice['label'] : $choice );
			$input = '';
			if ( is_array( $choice ) ) {
				$key   = $form_slug . "[" . $choice['option_key'] . "]";
				$value = isset( get_option( $form_slug )[ $choice['option_key'] ] ) ? get_option( $form_slug )[ $choice['option_key'] ] : "";

				$input = sprintf( '<input
                    class="hjqs-ln-input regular-text hjqs-ln-input-bis" type="text"
                    name="%s"
                    id="%s"
                    value="%s"
                    placeholder="%s"
            >', $key, $key, esc_html( $value ), $choice['default_value'] ?? '' );
			}
			echo $label . $input;
		};

		switch ( $field_type ) {
			// TEXT
			case 'text' : ?>
                <input
                        class="hjqs-ln-input regular-text" type="text"
                        name="<?php echo $option_key ?>"
                        id="<?php echo $option_key ?>"
                        value="<?php echo $value ?>"
                        placeholder="<?php echo $default_value ?>"
                >
				<?php break;
			// TEXTAREA
			case 'textarea' : ?>
                <textarea
                        class="hjqs-ln-textarea"
                        rows="5"
                        cols="100"
                        name="<?php echo $option_key ?>"
                        id="<?php echo $option_key ?>"
                ><?php echo $value ?></textarea>
				<?php break;
			// SELECT
			case 'select' : ?>
                <select
                        class="hjqs-ln-select"
                        name="<?php echo $option_key ?>"
                        id="<?php echo $option_key ?>"
                >
					<?php foreach ( $choices as $key => $choice ) : ?>
						<?php $selected = ( $value === $key ) ? 'selected' : '' ?>
                        <option
                                value="<?php echo $key; ?>"
							<?php echo $selected ?>
                        ><?php echo $choice ?></option>
					<?php endforeach; ?>
                </select>
				<?php break;
			// DATALIST
			case 'datalist': ?>
                <input
                        type="search"
                        class="hjqs-ln-datalist regular-text"
                        list="<?php echo $option_key; ?>_list"
                        name="<?php echo $option_key; ?>"
                        id="<?php echo $option_key; ?>"
                        value="<?php echo $value; ?>"
                >
                <datalist
                        id="<?php echo $option_key ?>_list"
                >
                    <!--[if IE]><select><!--<![endif]-->
					<?php foreach ( $choices as $key => $choice ) : ?>
						<?php $selected = ( $value === $key ) ? 'selected' : '' ?>
                        <option
                                value="<?php echo $choice; ?>"
							<?php echo $selected ?>
                        ><?php echo $choice ?></option>
					<?php endforeach; ?>
                    <!--[if IE]></select><!--<![endif]-->
                </datalist>
				<?php break;
			// RADIO
			case 'radio': ?>
                <fieldset>
					<?php foreach ( $choices as $key => $choice ): ?>
						<?php $checked = ( slugify( $value ) == slugify( $key ) ) ? 'checked' : '' ?>
                        <label>
                            <input
                                    class="hjqs-ln-radio"
                                    type="radio"
                                    name="<?php echo $option_key ?>"
                                    id="<?php echo $option_key . '-' . slugify( $key ); ?>"
                                    value="<?php echo $key; ?>"
								<?php echo $checked; ?>
                            >
							<?php $render_custom_field( $key, $choice, $form_slug ); ?>
                        </label>
                        <br>
					<?php endforeach; ?>
                </fieldset>
				<?php break;
			// CHECKBOX
			case 'checkbox': ?>
                <fieldset>
					<?php foreach ( $choices as $key => $choice ): ?>
						<?php
						$checked = '';
						if ( is_array( $value ) ) {
							$checked = ( in_array( $key, $value ) ) ? 'checked' : '';
						}
						?>
                        <label>
                            <input
                                    class="hjqs-ln-checkbox"
                                    type="checkbox"
                                    name="<?php echo $option_key ?>[]"
                                    id="<?php echo $option_key . '-' . $key; ?>"
                                    value="<?php echo $key; ?>"
								<?php echo $checked; ?>
                            >
							<?php $render_custom_field( $key, $choice, $form_slug ); ?>
                        </label>
                        <br>
					<?php endforeach; ?>
                </fieldset>
				<?php break;
			default:
				break;
		} ?>
		<?php if ( $field->get_helper() ): ?>
            <p class="hjqs-ln-helper"><?php echo $field->get_helper(); ?></p>
		<?php endif; ?>
    </div>
    <div class="hjqs-ln-options">
        <code class="hjqs-shortcode"
              data-clipboard-text="%%<?php echo $field->get_option_key_copy(); ?>%%"
        >%%<?php echo $field->get_option_key_copy(); ?>%%</code>
    </div>
</div>

