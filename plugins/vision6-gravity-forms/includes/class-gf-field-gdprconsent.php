<?php

if ( ! class_exists( 'GFForms' ) ) {
	die();
}


class GF_Field_GDPRConsent extends GF_Field {

    public $type = 'gdprconsent';

    public function get_form_editor_field_title() {
        return esc_attr__( 'GDPR Consent', 'gravityformsvision6' );
    }

    /**
     * Set the GDPR Consent Summary field to the advanced fields
     *
     * @return array
     */
    public function get_form_editor_button() {
        return array(
            'group' => 'advanced_fields',
            'text'  => $this->get_form_editor_field_title(),
        );
    }

	public function get_form_editor_field_settings() {
		return array(
			'conditional_logic_field_setting',
			'error_message_setting',
            'label_setting',
			'admin_label_setting',
			'rules_setting',
			'visibility_setting',
			'css_class_setting',
		);
	}

	public function is_conditional_logic_supported() {
		return false;
	}

    /**
     * Returns the HTML tag for the field container.
     *
     * @param array $form The current Form object.
     * @return string
     */
    public function get_field_container_tag( $form ) {
        if ( GFCommon::is_legacy_markup_enabled( $form ) ) {
            return parent::get_field_container_tag( $form );
        }

        return 'fieldset';
    }

	public function get_field_input( $form, $value = '', $entry = null ) {

		$form_id         = absint( $form['id'] );
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();

		$id            = $this->id;
		$field_id      = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";
		$disabled_text = $is_form_editor ? 'disabled="disabled"' : '';


		// Get the current GDPR Consent Summary
        $consent_summary = rgar( $form, 'gdprConsentSummary' );
        if (!$consent_summary) {
            $consent_summary = esc_html__('I consent to ' . get_bloginfo('name') . ' collecting and using my information for marketing purposes in accordance with their Privacy Policy.');
        }


        // Show the single checkbox
        $tabindex = $this->get_tabindex();

        $tag = GFCommon::is_legacy_markup_enabled( $form_id ) ? 'li' : 'div';
        $choice_markup = "<{$tag} class='gchoice gchoice_{$id}'>
								<input class='gfield-choice-input' name='input_{$id}' type='checkbox' value='{$consent_summary}' id='choice_{$id}' {$tabindex} {$disabled_text} />
								<label for='choice_{$id}' id='label_{$id}'>{$consent_summary}</label>
							</{$tag}>";

        $tag = GFCommon::is_legacy_markup_enabled( $form_id ) ? 'ul' : 'div';
        return sprintf( "<div class='ginput_container ginput_container_checkbox'><{$tag} class='gfield_checkbox' id='%s'>%s</{$tag}></div>", esc_attr( $field_id ), $choice_markup );
	}

	public function get_first_input_id( $form ) {
		return '';
	}

	public function get_value_default() {
		return $this->is_form_editor() ? $this->defaultValue : GFCommon::replace_variables_prepopulate( $this->defaultValue );
	}

	public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {

		if ( is_array( $value ) ) {

			$items = '';

			foreach ( $value as $key => $item ) {
				if ( ! rgblank( $item ) ) {

				    // We use text formatting for all submissions
                    $items .= GFCommon::selection_display( $item, $this, $currency, $use_text ) . ', ';

				}
			}

			if ( empty( $items ) ) {
				return '';
			} else {
                return substr( $items, 0, strlen( $items ) - 2 ); // Removing last comma.
			}
		} else {
			return $value;
		}

	}

	public function allow_html() {
		return true;
	}
}

GF_Fields::register( new GF_Field_GDPRConsent() );


add_action( 'gform_editor_js_set_default_values', 'set_gf_field_gdprconsent_defaults' );
function set_gf_field_gdprconsent_defaults() {
    ?>
    case 'gdprconsent':
        field.label = <?php echo json_encode( esc_html__( 'GDPR Consent', 'gravityforms' ) ); ?>;
        break;
    <?php
}