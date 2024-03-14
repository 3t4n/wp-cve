<?php
/**
 * Registering WordPress shortcode for the plugin.
 *
 * @since 2.12.15
 * @package SWPTLS
 */

namespace SWPTLS;

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for registering shortcode.
 *
 * @since 2.12.15
 * @package SWPTLS
 */
class Settings {
	/**
	 * Return the table styles array.
	 *
	 * @return mixed
	 */
	public function table_styles_array(): array {
		$styles_array = [
			'default-style' => [
				'imgUrl'    => SWPTLS_BASE_URL . 'assets/public/images/TableStyle/default-style.png',
				'inputName' => 'tableStyle',
				'isPro'     => false,
				'isChecked' => true,
				'label'     => 'Default Style',
			],
			'style-1'       => [
				'imgUrl'    => SWPTLS_BASE_URL . 'assets/public/images/TableStyle/style-2.png',
				'inputName' => 'tableStyle',
				'isPro'     => true,
				'isChecked' => false,
				'label'     => 'Style 1',
			],
			'style-2'       => [
				'imgUrl'    => SWPTLS_BASE_URL . 'assets/public/images/TableStyle/style-3.png',
				'inputName' => 'tableStyle',
				'isPro'     => true,
				'isChecked' => false,
				'label'     => 'Style 2',
			],
			'style-3'       => [
				'imgUrl'    => SWPTLS_BASE_URL . 'assets/public/images/TableStyle/style-4.png',
				'inputName' => 'tableStyle',
				'isPro'     => true,
				'isChecked' => false,
				'label'     => 'Style 3',
			],
			'style-4'       => [
				'imgUrl'    => SWPTLS_BASE_URL . 'assets/public/images/TableStyle/style-1.png',
				'inputName' => 'tableStyle',
				'isPro'     => true,
				'isChecked' => false,
				'label'     => 'Style 4',
			],
			'style-5'       => [
				'imgUrl'    => SWPTLS_BASE_URL . 'assets/public/images/TableStyle/style-5.png',
				'inputName' => 'tableStyle',
				'isPro'     => true,
				'isChecked' => false,
				'label'     => 'Style 5',
			],
		];

		$styles_array = apply_filters( 'gswpts_table_styles', $styles_array );
		return $styles_array;
	}

	/**
	 * Returns the scroll height array.
	 *
	 * @return array
	 */
	public function scroll_height_array(): array {
		$scroll_heights = [
			'200'  => [
				'val'   => '200px',
				'isPro' => true,
			],
			'400'  => [
				'val'   => '400px',
				'isPro' => true,
			],
			'500'  => [
				'val'   => '500px',
				'isPro' => true,
			],
			'600'  => [
				'val'   => '600px',
				'isPro' => true,
			],
			'700'  => [
				'val'   => '700px',
				'isPro' => true,
			],
			'800'  => [
				'val'   => '800px',
				'isPro' => true,
			],
			'900'  => [
				'val'   => '900px',
				'isPro' => true,
			],
			'1000' => [
				'val'   => '1000px',
				'isPro' => true,
			],
		];

		$scroll_heights = apply_filters( 'gswpts_table_scorll_height', $scroll_heights );

		return $scroll_heights;
	}

	/**
	 * Returns the responsive style.
	 *
	 * @return array
	 */
	public function responsive_style() {
		$responsive_styles = [
			'default_style'  => [
				'val'   => 'Default Style',
				'isPro' => false,
			],
			'collapse_style' => [
				'val'   => 'Collapsible Style',
				'isPro' => true,
			],
			'scroll_style'   => [
				'val'   => 'Scrollable Style',
				'isPro' => true,
			],
		];

		$responsive_styles = apply_filters( 'gswpts_responsive_styles', $responsive_styles );

		return $responsive_styles;
	}

	/**
	 * Returns the link_support mode.
	 *
	 * @return array
	 */
	public function link_support() {
		$link_support = [
			'pretty_link'  => [
				'val'   => 'Pretty Link',
				'isPro' => true,
			],
			'smart_link' => [
				'val'   => 'Smart Link',
				'isPro' => true,
			],

		];

		$link_support = apply_filters( 'gswpts_link_support', $link_support );

		return $link_support;
	}

	/**
	 * Returns the display setting array.
	 *
	 * @return array
	 */
	public function display_settings_array(): array {
		$settings_array = [
			'table_title'          => [
				'feature_title' => __( 'Table Title', 'sheetstowptable' ),
				'feature_desc'  => __( 'Enable this to show the table title in <i>h3</i> tag above the table in your website front-end', 'sheetstowptable' ),
				'input_name'    => 'show_title',
				'checked'       => false,
				'type'          => 'checkbox',
				'show_tooltip'  => true,
			],
			'show_info_block'      => [
				'feature_title' => __( 'Show info block', 'sheetstowptable' ),
				'feature_desc'  => __( 'Show <i>Showing X to Y of Z entries</i>block below the table', 'sheetstowptable' ),
				'input_name'    => 'info_block',
				'checked'       => true,
				'type'          => 'checkbox',
				'show_tooltip'  => true,

			],
			'show_x_entries'       => [
				'feature_title' => __( 'Show X entries', 'sheetstowptable' ),
				'feature_desc'  => __( '<i>Show X entries</i> per page dropdown', 'sheetstowptable' ),
				'input_name'    => 'show_entries',
				'checked'       => true,
				'type'          => 'checkbox',
				'show_tooltip'  => true,

			],
			'swap_filters'         => [
				'feature_title' => __( 'Swap Filters', 'sheetstowptable' ),
				'feature_desc'  => __( 'Swap the places of <i> X entries</i> dropdown & search filter input', 'sheetstowptable' ),
				'input_name'    => 'swap_filter_inputs',
				'checked'       => false,
				'type'          => 'checkbox',
				'show_tooltip'  => true,

			],
			'swap_bottom_elements' => [
				'feature_title' => __( 'Swap Bottom Elements', 'sheetstowptable' ),
				'feature_desc'  => __( 'Swap the places of <i>Showing X to Y of Z entries</i> with table pagination filter', 'sheetstowptable' ),
				'input_name'    => 'swap_bottom_options',
				'checked'       => false,
				'type'          => 'checkbox',
				'show_tooltip'  => true,

			],
			'responsive_style'     => [
				'feature_title' => __( 'Responsive Style', 'sheetstowptable' ),
				'feature_desc'  => __( 'Allow the table to collapse or scroll on mobile and tablet screen.', 'sheetstowptable' ),
				'input_name'    => 'responsive_style',
				'is_pro'        => true,
				'type'          => 'select',
				'values'        => $this->responsive_style(),
				'default_text'  => 'Collapsible Table',
				'default_value' => 'default_style',
				'show_tooltip'  => true,

			],
			'link_support'     => [
				'feature_title' => __( 'Link Support', 'sheetstowptable' ),
				'feature_desc'  => __( 'Allow the table to collapse or scroll on mobile and tablet screen.', 'sheetstowptable' ),
				'input_name'    => 'link_support',
				'is_pro'        => true,
				'type'          => 'select',
				'values'        => $this->link_support(),
				'default_text'  => 'Pretty Link',
				'default_value' => 'pretty_link',
				'show_tooltip'  => true,

			],
			'rows_per_page'        => [
				'feature_title' => __( 'Rows per page', 'sheetstowptable' ),
				'feature_desc'  => __( 'This will show rows per page. The feature will allow you how many rows you want to show to your user by default.', 'sheetstowptable' ),
				'input_name'    => 'rows_per_page',
				'type'          => 'select',
				'values'        => $this->rows_per_page(),
				'default_text'  => 'Rows Per Page',
				'default_value' => 10,
				'show_tooltip'  => true,

			],
			'vertical_scrolling'   => [
				'feature_title' => __( 'Table Height', 'sheetstowptable' ),
				'feature_desc'  => __( 'Choose the height of the table to scroll vertically. Activating this feature will allow the table to behave as sticky header', 'sheetstowptable' ),
				'input_name'    => 'vertical_scrolling',
				'checked'       => false,
				'is_pro'        => true,
				'type'          => 'select',
				'values'        => $this->scroll_height_array(),
				'default_text'  => 'Choose Height',
				'default_value' => swptls()->helpers->is_pro_active() ? 'default' : null,
				'show_tooltip'  => false,
			],
			'cell_format'          => [
				'feature_title' => __( 'Format Cell', 'sheetstowptable' ),
				'feature_desc'  => __( 'Format the table cell as like google sheet cell formatting. Format your cell as Wrap OR Expanded style', 'sheetstowptable' ),
				'input_name'    => 'cell_format',
				'checked'       => false,
				'is_pro'        => true,
				'type'          => 'select',
				'values'        => $this->cell_formatting_array(),
				'default_text'  => 'Cell Format',
				'default_value' => swptls()->helpers->is_pro_active() ? 'expand' : null,
				'show_tooltip'  => true,

			],
			'redirection_type'     => [
				'feature_title' => __( 'Link Type', 'sheetstowptable' ),
				'feature_desc'  => __( 'Choose the redirection type of all the links in this table.', 'sheetstowptable' ),
				'input_name'    => 'redirection_type',
				'is_pro'        => true,
				'type'          => 'select',
				'values'        => $this->redirection_type_array(),
				'default_text'  => 'Redirection Type',
				'default_value' => swptls()->helpers->is_pro_active() ? '_self' : null,
				'show_tooltip'  => true,

			],
			'table_style'          => [
				'feature_title' => __( 'Table Style', 'sheetstowptable' ),
				'feature_desc'  => __( 'Choose your desired table style for this table. This will change the design & color of this table according to your selected table design', 'sheetstowptable' ),
				'input_name'    => 'table_style',
				'checked'       => false,
				'is_pro'        => true,
				'type'          => 'custom-type',
				'default_text'  => 'Choose Style',
				'show_tooltip'  => false,
				'icon_url'      => SWPTLS_BASE_URL . 'assets/public/icons/table_style.svg',
			],
			'import_styles'        => [
				'feature_title' => __( 'Import Sheet Styles', 'sheetstowptable' ),
				'feature_desc'  => __( 'Import cell backgorund color & cell font color from google sheet. If you activate this feature it will overrider <i>Table Style</i> setting', 'sheetstowptable' ),
				'input_name'    => 'import_styles',
				'is_pro'        => true,
				'type'          => 'checkbox',
				'checked'       => false,
				'show_tooltip'  => true,
			],
		];

		$settings_array = apply_filters( 'gswpts_display_settings_arr', $settings_array );

		return $settings_array;
	}

	/**
	 * Returns the sort and filter.
	 *
	 * @return array
	 */
	public function sort_and_filter_settings_array(): array {
		$settings_array = [
			'allow_sorting' => [
				'feature_title' => __( 'Allow Sorting', 'sheetstowptable' ),
				'feature_desc'  => __( 'Enable this feature to sort table data for frontend.', 'sheetstowptable' ),
				'input_name'    => 'sorting',
				'checked'       => true,
				'type'          => 'checkbox',
				'show_tooltip'  => true,
			],
			'search_bar'    => [
				'feature_title' => __( 'Search Bar', 'sheetstowptable' ),
				'feature_desc'  => __( 'Enable this feature to show a search bar in for the table. It will help user to search data in the table', 'sheetstowptable' ),
				'input_name'    => 'search_table',
				'checked'       => true,
				'type'          => 'checkbox',
				'show_tooltip'  => true,
			],
		];

		$settings_array = apply_filters( 'gswpts_sortfilter_settings_arr', $settings_array );

		return $settings_array;
	}

	/**
	 * Returns the row per page.
	 *
	 * @return array
	 */
	public function rows_per_page(): array {
		$rows_per_page = [
			'1'   => [
				'val'   => 1,
				'isPro' => false,
			],
			'5'   => [
				'val'   => 5,
				'isPro' => false,
			],
			'10'  => [
				'val'   => 10,
				'isPro' => false,
			],
			'15'  => [
				'val'   => 15,
				'isPro' => false,
			],
			'25'  => [
				'val'   => 25,
				'isPro' => true,
			],
			'50'  => [
				'val'   => 50,
				'isPro' => true,
			],
			'100' => [
				'val'   => 100,
				'isPro' => true,
			],
			'all' => [ //phpcs:ignore
				'val'   => 'All',
				'isPro' => true,
			],
		];

		$rows_per_page = apply_filters( 'gswpts_rows_per_page', $rows_per_page );

		return $rows_per_page;
	}

	/**
	 * Returns the Wrap Style and Expanded Style.
	 *
	 * @return array
	 */
	public function cell_formatting_array(): array {
		$cell_formats = [
			'wrap'   => [
				'val'   => 'Wrap Style',
				'isPro' => true,
			],
			'expand' => [
				'val'   => 'Expanded Style',
				'isPro' => true,
			],
		];

		$cell_formats = apply_filters( 'gswpts_cell_format', $cell_formats );

		return $cell_formats;
	}

	/**
	 * Returns the link type.
	 *
	 * @return mixed
	 */
	public function redirection_type_array(): array {
		$redirection_types = [
			'_blank' => [
				'val'   => 'Blank Type',
				'isPro' => true,
			],
			'_self'  => [
				'val'   => 'Self Type',
				'isPro' => true,
			],
		];

		$redirection_types = apply_filters( 'gswpts_redirection_types', $redirection_types );

		return $redirection_types;
	}
}
