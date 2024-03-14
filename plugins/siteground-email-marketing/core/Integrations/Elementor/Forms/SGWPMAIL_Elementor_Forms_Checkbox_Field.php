<?php
namespace SG_Email_Marketing\Integrations\Elementor\Forms;

use ElementorPro\Plugin;
use Elementor\Controls_Manager;

/**
 * Elementor Form Field - SG Email Marketing Consent Checkbox
 *
 * Add a new "SG Email Marketing Consent Checkbox" field to Elementor form widget.
 *
 * @since 1.1.3
 */
class SGWPMAIL_Elementor_Forms_Checkbox_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base {

	/**
	 * Get field type.
	 *
	 * @since 1.1.3
	 *
	 * @return string Field type.
	 */
	public function get_type() {
		return 'sg-email-marketing-checkbox';
	}

	/**
	 * Retrieve SGWPMAIL Checkbox field label.
	 *
	 * @since 1.1.3
	 *
	 * @return string Field name.
	 */
	public function get_name() {
		return esc_html__( 'SG Email Marketing Checkbox', 'siteground-email-marketing' );
	}

	/**
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.3
	 *
	 * @param mixed $item Item to be rendered.
	 * @param mixed $item_index Index of the item to be rendered.
	 * @param mixed $form Form object.
	 *
	 * @return void
	 */
	public function render( $item, $item_index, $form ) {
		$label = '';
		$form->add_render_attribute( 'input' . $item_index, 'class', 'elementor-acceptance-field' );
		$form->add_render_attribute( 'input' . $item_index, 'type', 'checkbox', true );

		if ( ! empty( $item['sgwpmail_checkbox_text'] ) ) {
			$label = '<label for="' . $form->get_attribute_id( $item ) . '">' . $item['sgwpmail_checkbox_text'] . '</label>';
		}

		?>
		<div class="elementor-field-subgroup">
			<span class="elementor-field-option">
				<input <?php $form->print_render_attribute_string( 'input' . $item_index ); ?>>
				<?php echo $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
		</div>
		<?php
	}

	/**
	 * Updating the controls for the new field
	 *
	 * @since 1.1.3
	 *
	 * @param  Widget $widget Widget object.
	 *
	 * @return void
	 */
	public function update_controls( $widget ) {
		$elementor = Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = array(
			'sgwpmail_checkbox_text'      => array(
				'name'         => 'sgwpmail_checkbox_text',
				'label'        => esc_html__( 'Consent checkbox text', 'siteground-email-marketing' ),
				'type'         => Controls_Manager::TEXTAREA,
				'condition'    => array(
					'field_type' => $this->get_type(),
				),
				'ai' => array(
					'active' => false,
				),
				'default'      => 'Subscribe me to your newsletter',
				'tab'          => 'content',
				'inner_tab'    => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			),
		);

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );
		$widget->update_control( 'form_fields', $control_data );
	}


	/**
	 * Used to add a script to the Elementor editor preview.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'elementor/preview/init', array( $this, 'editor_preview_footer' ) );
	}

	/**
	 * Add a script to the footer of the editor preview screen.
	 *
	 * @since 1.1.3
	 *
	 * @return void
	 */
	public function editor_preview_footer() {
		add_action( 'wp_footer', array( $this, 'content_template_script' ) );
	}

	/**
	 * Add content template alternative, to display the field in Elemntor editor.
	 *
	 * @since 1.1.3
	 *
	 * @return void
	 */
	public function content_template_script() {
		?>
		<script>
		jQuery( document ).ready( () => {
			elementor.hooks.addFilter(
				'elementor_pro/forms/content_template/field/<?php echo $this->get_type(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>',
				function ( inputField, item, i ,settings ) {
					var itemClasses = _.escape(item.css_classes),
					required = '',
					label = '',
					checked = '';
					if (item.required) {
						required = 'required';
					}
					if (item.sgwpmail_checkbox_text) {
						label = '<label for="form_field_' + i + '">' + item.sgwpmail_checkbox_text + '</label>';
					}
					return '<div class="elementor-field-subgroup">' + '<span class="elementor-field-option"><input size="1" type="checkbox"' + checked + ' class="elementor-acceptance-field elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' > '  + label + '</span></div>';
				}, 10, 4
			);

		});
		</script>
		<?php
	}
}
