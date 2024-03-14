<?php

namespace XproElementorAddons\Control;

use Elementor\Base_Data_Control;

class Xpro_Elementor_Select extends Base_Data_Control {



	const TYPE = 'xpro-select';

	/**
	 * Set control type.
	 */
	public function get_type() {
		return self::TYPE;
	}

	public function enqueue() {
		wp_enqueue_script(
			'xpro-select',
			XPRO_ELEMENTOR_ADDONS_DIR_URL . 'inc/controls/assets/js/xpro-select.js',
			array( 'jquery-elementor-select2' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
		wp_localize_script(
			'xpro-select',
			'xpro_elementor_select_localize',
			array(
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'search_text' => esc_html__( 'Search', 'xpro-elementor-addons' ),
				'nonce'       => wp_create_nonce( 'xpro-select-nonce' ),
			)
		);
	}

	/**
	 * control field markup
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<# var controlUID = '<?php echo esc_attr( $control_uid ); ?>'; #>
		<# var currentID = elementor.panel.currentView.currentPageView.model.attributes.settings.attributes[data.name]; #>
		<div class="elementor-control-field">
			<# if ( data.label ) { #>
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{data.label
				}}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select id="<?php echo esc_attr( $control_uid ); ?>" {{ multiple }} class="xpro-select"
						data-setting="{{ data.name }}"></select>
			</div>
		</div>
		<#
		( function( $ ) {
		$( document.body ).trigger( 'xpro_elementor_select_init',{currentID:data.controlValue,data:data,controlUID:controlUID,multiple:data.multiple} );
		}( jQuery ) );
		#>
		<?php
	}

	/**
	 * Set default settings
	 */
	protected function get_default_settings() {
		return array(
			'multiple'    => false,
			'source_name' => 'post_type',
			'source_type' => 'post',
		);
	}
}
