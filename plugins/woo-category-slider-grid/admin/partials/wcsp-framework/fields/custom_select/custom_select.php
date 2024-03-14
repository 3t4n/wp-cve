<?php
/**
 * Framework shortcode field file.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials/section/settings
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WCS_Field_custom_select' ) ) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WCS_Field_custom_select extends SP_WCS_Fields {

		/**
		 * Field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			echo wp_kses_post( $this->field_before() );?>

		<div class="chosen-container chosen-container-multi wcsp-chosen-container-free" title="" style="width: 100%;">
			<ul class="chosen-choices">
				<li class="search-field">
					<input class="chosen-search-input default" type="text" autocomplete="off" value="Select Category(s)" style="width: 132.656px;">
				</li>
			</ul>
			<div class="wcsp-chosen-drop" style="display:none;">
			<ul class="chosen-results">
				<li class="active-result">Clothes</li>
				<li class="active-result">-Hoodie (1)</li>
				<li class="active-result">-Jacket (2)</li>
				<li class="active-result">--Coton (1)</li>
				<li class="active-result">--Leather (2)</li>
				<li class="active-result">---Foreign (1)</li>
				<li class="active-result">---local (2)</li>
				<li class="active-result">-Jeans (3)</li>
				<li class="active-result">-Sweater (4)</li>	
				<li class="active-result">Accessories</li>
				<li class="active-result">-Belt (1)</li>
				<li class="active-result">-Charger (2)</li>
				<li class="active-result">-Cover (3)</li>
			</ul>
			</div>
		</div> 

			<?php
				echo wp_kses_post( $this->field_after() );

		}

	}
}
