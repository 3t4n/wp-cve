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

if ( ! class_exists( 'SP_WCS_Field_custom_group' ) ) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WCS_Field_custom_group extends SP_WCS_Fields {

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
			echo wp_kses_post( $this->field_before() );
			?>

	<div class="spf-cloneable-wrapper spf-data-wrapper ui-accordion ui-widget ui-helper-reset ui-sortable"
		data-title-number="" data-unique-id="sp_wcsp_shortcode_options"
		data-field-id="[wcsp_choose_category_parent_child]" data-max="0" data-min="0" role="tablist">
		<div class="spf-cloneable-item">
			<div class="spf-cloneable-helper"><i class="spf-cloneable-sort fa fa-arrows ui-sortable-handle"></i><i class="spf-cloneable-remove spf-confirm fa fa-times"
			data-confirm="Are you sure to delete this item?"></i></div>
			<h4 class="spf-cloneable-title ui-accordion-header ui-corner-top ui-state-default ui-sortable-handle   ui-accordion-header-collapsed ui-corner-all ui-accordion-icons"
			role="tab" id="ui-id-1" aria-controls="ui-id-2" aria-selected="false" aria-expanded="false" tabindex="-1"><span
			class="ui-accordion-header-icon ui-icon spf-cloneable-header-icon fa fa-angle-right"></span><span
			class="spf-cloneable-text"><span class="spf-cloneable-value">Electronics (76)</span></span></h4>
		</div>
		<div class="spf-cloneable-item">
			<div class="spf-cloneable-helper"><i class="spf-cloneable-sort fa fa-arrows ui-sortable-handle"></i><i class="spf-cloneable-remove spf-confirm fa fa-times"
			data-confirm="Are you sure to delete this item?"></i></div>
			<h4 class="spf-cloneable-title ui-accordion-header ui-corner-top ui-state-default ui-sortable-handle   ui-accordion-header-collapsed ui-corner-all ui-accordion-icons"
			role="tab" id="ui-id-1" aria-controls="ui-id-2" aria-selected="false" aria-expanded="false" tabindex="-1"><span
			class="ui-accordion-header-icon ui-icon spf-cloneable-header-icon fa fa-angle-right"></span><span
			class="spf-cloneable-text"><span class="spf-cloneable-value">Kitchen (60)</span></span></h4>
		</div>
		<div class="spf-cloneable-item">
			<div class="spf-cloneable-helper"><i class="spf-cloneable-sort fa fa-arrows"></i><i class="spf-cloneable-remove spf-confirm fa fa-times"
					data-confirm="Are you sure to delete this item?"></i></div>
			<h4 class="spf-cloneable-title ui-accordion-header ui-corner-top ui-state-default ui-accordion-icons ui-accordion-header-active ui-state-active"
				role="tab" id="ui-id-5" aria-controls="ui-id-6" aria-selected="true" aria-expanded="true" tabindex="0">
				<span class="ui-accordion-header-icon ui-icon spf-cloneable-header-icon fa fa-angle-down"></span><span
					class="spf-cloneable-text"><span class="spf-cloneable-value">Clothing (98)</span></span></h4>
			<div class="spf-cloneable-content ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content ui-accordion-content-active"
				style="" id="ui-id-6" aria-labelledby="ui-id-5" role="tabpanel" aria-hidden="false">
				<div class="spf-field spf-field-select sp_wcsp_categories">
					<div class="spf-title">
						<h4>Parent Category</h4>
					</div>
					<div class="spf-fieldset"><select
							name="sp_wcsp_shortcode_options[wcsp_choose_category_parent_child][2][wcsp_choose_category_parent]"
							style="width: 280px;" data-depend-id="wcsp_choose_category_parent"
							placeholder="Select Category(s)">
							<option value="">Clothing (98)</option>
						</select></div>
					<div class="clear"></div>
				</div>
				<div class="spf-field spf-field-select sp_wcsp_categories">
					<div class="spf-title">
						<h4>Child Category(s)</h4>
					</div>
					<div class="spf-fieldset"><select
							name="sp_wcsp_shortcode_options[wcsp_choose_category_parent_child][2][wcsp_choose_category_child][]"
							multiple="multiple" class="spf-chosen" data-placeholder="Select Child Category(s)"
							style="width: 280px; display: none;" data-depend-id="wcsp_choose_category_child"
							placeholder="Select Child Category(s)">
							<option value="">Blouses(9)</option>
						</select>
						<div class="chosen-container chosen-container-multi" title="" style="width: 100%;">
							<ul class="chosen-choices">
							<li class="search-choice"><span>Blouses</span><a class="search-choice-close" data-option-array-index="0"></a></li>
							<li class="search-choice"><span>Hoodies</span><a class="search-choice-close" data-option-array-index="0"></a></li>
							<li class="search-choice"><span>T-shirts</span><a class="search-choice-close" data-option-array-index="0"></a></li>
							<li class="search-choice"><span>Trousers</span><a class="search-choice-close" data-option-array-index="0"></a></li>
							<li class="search-choice"><span>Hats</span><a class="search-choice-close" data-option-array-index="0"></a></li>
							</ul>
							<div class="chosen-drop">
								<ul class="chosen-results"></ul>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	<a href="#" class="button button-primary spf-cloneable-add">Add New</a>

			<?php
				echo wp_kses_post( $this->field_after() );

		}

	}
}
