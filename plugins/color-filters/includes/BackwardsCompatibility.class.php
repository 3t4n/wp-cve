<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewduwcfBackwardsCompatibility' ) ) {
/**
 * Class to handle transforming the plugin settings from the 
 * previous style (individual options) to the new one (options array)
 *
 * @since 3.0.0
 */
class ewduwcfBackwardsCompatibility {

	public function __construct() {
		
		if ( empty( get_option( 'ewd-uwcf-settings' ) ) and get_option( 'EWD_UWCF_Full_Version' ) ) { $this->run_backwards_compat(); }
		elseif ( ! get_option( 'ewd-uwcf-permission-level' ) ) { update_option( 'ewd-uwcf-permission-level', 1 ); }
	}

	public function run_backwards_compat() {

		$settings = array(
			'table-format' 								=> get_option( 'EWD_UWCF_Table_Format' ) == 'Yes' ? true : false,
			'allow-sorting'								=> get_option( 'EWD_UWCF_Allow_Sorting' ) == 'Yes' ? true : false,
			'wc-table-product-title-displayed'			=> get_option( 'EWD_UWCF_Product_Title_Displayed' ) == 'Yes' ? true : false,
			'wc-table-product-image-displayed'			=> get_option( 'EWD_UWCF_Product_Image_Displayed' ) == 'Yes' ? true : false,
			'wc-table-product-price-displayed'			=> get_option( 'EWD_UWCF_Product_Price_Displayed' ) == 'Yes' ? true : false,
			'wc-table-product-rating-displayed'			=> get_option( 'EWD_UWCF_Product_Rating_Displayed' ) == 'Yes' ? true : false,
			'wc-table-product-add_to_cart-displayed'	=> get_option( 'EWD_UWCF_Product_Add_To_Cart_Displayed' ) == 'Yes' ? true : false,
			'product-price-filtering'					=> get_option( 'EWD_UWCF_Product_Price_Enable' ) == 'Yes' ? true : false,
			'product-price-display'						=> get_option( 'EWD_UWCF_Product_Price_Display' ) == 'Yes' ? true : false,
			'color-filtering'							=> get_option( 'EWD_UWCF_Enable_Colors' ) == 'Yes' ? true : false,
			'color-filtering-disable-text'				=> get_option( 'EWD_UWCF_Color_Filters_Show_Text' ) == 'No' ? true : false,
			'color-filtering-disable-color'				=> get_option( 'EWD_UWCF_Color_Filters_Show_Color' ) == 'No' ? true : false,
			'color-filtering-hide-empty'				=> get_option( 'EWD_UWCF_Color_Filters_Hide_Empty' ) == 'Yes' ? true : false,
			'color-filtering-show-product-count'		=> get_option( 'EWD_UWCF_Color_Filters_Show_Product_Count' ) == 'Yes' ? true : false,
			'color-filtering-display'					=> strtolower( get_option( 'EWD_UWCF_Color_Filters_Display' ) ),
			'color-filtering-display-thumbnail-colors'	=> get_option( 'EWD_UWCF_Display_Thumbnail_Colors' ) == 'Yes' ? true : false,
			'color-filtering-product-page-display'		=> get_option( 'EWD_UWCF_Colors_Product_Page_Display' ) == 'Yes' ? true : false,
			'color-filtering-colors-for-variations'		=> get_option( 'EWD_UWCF_Colors_Used_For_Variations' ) == 'Yes' ? true : false,
			'size-filtering'							=> get_option( 'EWD_UWCF_Enable_Sizes' ) == 'Yes' ? true : false,
			'size-filtering-disable-text'				=> get_option( 'EWD_UWCF_Size_Filters_Show_Text' ) == 'No' ? true : false,
			'size-filtering-hide-empty'					=> get_option( 'EWD_UWCF_Size_Filters_Hide_Empty' ) == 'Yes' ? true : false,
			'size-filtering-show-product-count'			=> get_option( 'EWD_UWCF_Size_Filters_Show_Product_Count' ) == 'Yes' ? true : false,
			'size-filtering-display'					=> strtolower( get_option( 'EWD_UWCF_Size_Filters_Display' ) ),
			'size-filtering-display-thumbnail-sizes'	=> get_option( 'EWD_UWCF_Display_Thumbnail_Sizes' ) == 'Yes' ? true : false,
			'size-filtering-product-page-display'		=> get_option( 'EWD_UWCF_Sizes_Product_Page_Display' ) == 'Yes' ? true : false,
			'size-filtering-sizes-for-variations'		=> get_option( 'EWD_UWCF_Sizes_Used_For_Variations' ) == 'Yes' ? true : false,
			'category-filtering'						=> get_option( 'EWD_UWCF_Enable_Categories' ) == 'Yes' ? true : false,
			'category-filtering-disable-text'			=> get_option( 'EWD_UWCF_Category_Filters_Show_Text' ) == 'No' ? true : false,
			'category-filtering-hide-empty'				=> get_option( 'EWD_UWCF_Category_Filters_Hide_Empty' ) == 'Yes' ? true : false,
			'category-filtering-show-product-count'		=> get_option( 'EWD_UWCF_Category_Filters_Show_Product_Count' ) == 'Yes' ? true : false,
			'category-filtering-display'				=> strtolower( get_option( 'EWD_UWCF_Category_Filters_Display' ) ),
			'category-filtering-display-thumbnail-cats'	=> get_option( 'EWD_UWCF_Display_Thumbnail_Categories' ) == 'Yes' ? true : false,
			'tag-filtering'								=> get_option( 'EWD_UWCF_Enable_Tags' ) == 'Yes' ? true : false,
			'tag-filtering-disable-text'				=> get_option( 'EWD_UWCF_Tag_Filters_Show_Text' ) == 'No' ? true : false,
			'tag-filtering-hide-empty'					=> get_option( 'EWD_UWCF_Tag_Filters_Hide_Empty' ) == 'Yes' ? true : false,
			'tag-filtering-show-product-count'			=> get_option( 'EWD_UWCF_Tag_Filters_Show_Product_Count' ) == 'Yes' ? true : false,
			'tag-filtering-display'						=> strtolower( get_option( 'EWD_UWCF_Tag_Filters_Display' ) ),
			'tag-filtering-display-thumbnail-tags'		=> get_option( 'EWD_UWCF_Display_Thumbnail_Tags' ) == 'Yes' ? true : false,
			'text-search'								=> get_option( 'EWD_UWCF_Enable_Text_Search' ) == 'Yes' ? true : false,
			'text-search-display'						=> strtolower( get_option( 'EWD_UWCF_Product_Title_Filter_Type' ) ),
			'text-search-autocomplete'					=> get_option( 'EWD_UWCF_Enable_Autocomplete' ) == 'Yes' ? true : false,
			'price-filtering'							=> get_option( 'EWD_UWCF_Product_Price_Filtering' ) == 'Yes' ? true : false,
			'price-filtering-display'					=> strtolower( get_option( 'EWD_UWCF_Product_Price_Display' ) ),
			'ratings-filtering'							=> get_option( 'EWD_UWCF_Enable_Ratings_Filtering' ) == 'Yes' ? true : false,
			'ratings-filtering-display'					=> strtolower( get_option( 'EWD_UWCF_Product_Rating_Filter_Type' ) ),
			'ratings-filtering-ratings-type'			=> strtolower( get_option( 'EWD_UWCF_Ratings_Type' ) ),
			'instock-filtering'							=> get_option( 'EWD_UWCF_Enable_InStock_Filtering' ) == 'Yes' ? true : false,
			'onsale-filtering'							=> get_option( 'EWD_UWCF_Enable_OnSale_Filtering' ) == 'Yes' ? true : false,
			'access-role'								=> strtolower( get_option( 'EWD_UWCF_Access_Role' ) ),
			'reset-all-button'							=> get_option( 'EWD_UWCF_Reset_All_Button' ) == 'Yes' ? true : false,
			'label-color-filters'						=> get_option( 'EWD_UWCF_Color_Filters_Label' ),
			'label-show-all-color'						=> get_option( 'EWD_UWCF_Show_All_Colors_Label' ),
			'label-show-all-size'						=> get_option( 'EWD_UWCF_Show_All_Sizes_Label' ),
			'label-show-all-category'					=> get_option( 'EWD_UWCF_Show_All_Categories_Label' ),
			'label-show-all-tag'						=> get_option( 'EWD_UWCF_Show_All_Tags_Label' ),
			'label-show-all-attribute'					=> get_option( 'EWD_UWCF_Show_All_Attributes_Label' ),
			'label-rating'								=> get_option( 'EWD_UWCF_Rating_Label' ),
			'label-thumbnail-colors'					=> get_option( 'EWD_UWCF_Thumbnail_Colors_Label' ),
			'label-thumbnail-sizes'						=> get_option( 'EWD_UWCF_Thumbnail_Sizes_Label' ),
			'label-thumbnail-categories'				=> get_option( 'EWD_UWCF_Thumbnail_Categories_Label' ),
			'label-thumbnail-tags'						=> get_option( 'EWD_UWCF_Thumbnail_Tags_Label' ),
			'label-product-page-colors'					=> get_option( 'EWD_UWCF_Product_Page_Colors_Label' ),
			'label-product-page-sizes'					=> get_option( 'EWD_UWCF_Product_Page_Sizes_Label' ),
			'custom-css'								=> get_option( 'EWD_UWCF_Custom_CSS' ),
			'styling-color-filter-shape'				=> get_option( 'EWD_UWCF_Color_Filters_Color_Shape' ),
			'styling-color-icon-size'					=> get_option( 'EWD_UWCF_Color_Icon_Size' ),
			'styling-widget-font-color'					=> get_option( 'EWD_UWCF_Widget_Font_Color' ),
			'styling-widget-font-size'					=> get_option( 'EWD_UWCF_Widget_Font_Size' ),
			'styling-ratings-bar-fill-color'			=> get_option( 'EWD_UWCF_Ratings_Bar_Fill_Color' ),
			'styling-ratings-bar-empty-color'			=> get_option( 'EWD_UWCF_Ratings_Bar_Empty_Color' ),
			'styling-ratings-bar-handle-color'			=> get_option( 'EWD_UWCF_Ratings_Bar_Handle_Color' ),
			'styling-ratings-bar-text-color'			=> get_option( 'EWD_UWCF_Ratings_Bar_Text_Color' ),
			'styling-ratings-bar-font-size'				=> get_option( 'EWD_UWCF_Ratings_Bar_Font_Size' ),
			'styling-reset-all-button-background-color'	=> get_option( 'EWD_UWCF_Reset_All_Button_Background_Color' ),
			'styling-reset-all-button-text-color'		=> get_option( 'EWD_UWCF_Reset_All_Button_Text_Color' ),
			'styling-reset-all-button-hover-bg-color'	=> get_option( 'EWD_UWCF_Reset_All_Button_Hover_Background_Color' ),
			'styling-reset-all-button-hover-text-color'	=> get_option( 'EWD_UWCF_Reset_All_Button_Hover_Text_Color' ),
			'styling-reset-all-button-font-size'		=> get_option( 'EWD_UWCF_Reset_All_Button_Font_Size' ),
			'styling-shop-thumbnails-font-color'		=> get_option( 'EWD_UWCF_Shop_Thumbnails_Font_Color' ),
			'styling-shop-thumbnails-font-size'			=> get_option( 'EWD_UWCF_Shop_Thumbnails_Font_Size' ),
			'styling-shop-thumbnails-color-icon-size'	=> get_option( 'EWD_UWCF_Shop_Thumbnails_Color_Icon_Size' ),

		);

		foreach ( ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {
			$settings[ $attribute_taxonomy->attribute_name . '-filtering' ]					= get_option( 'EWD_UWCF_' . $attribute_taxonomy->attribute_name .  '_Enabled' ) == 'Yes' ? true : false;
			$settings[ $attribute_taxonomy->attribute_name . '-displayed' ]					= get_option( 'EWD_UWCF_' . $attribute_taxonomy->attribute_name .  '_Displayed' ) == 'Yes' ? true : false;
			$settings[ $attribute_taxonomy->attribute_name . '-disable-text' ]				= get_option( 'EWD_UWCF_' . $attribute_taxonomy->attribute_name .  '_Show_Text' ) == 'No' ? true : false;
			$settings[ $attribute_taxonomy->attribute_name . '-hide-empty' ]				= get_option( 'EWD_UWCF_' . $attribute_taxonomy->attribute_name .  '_Hide_Empty' ) == 'Yes' ? true : false;
			$settings[ $attribute_taxonomy->attribute_name . '-product-count' ]				= get_option( 'EWD_UWCF_' . $attribute_taxonomy->attribute_name .  '_Product_Count' ) == 'Yes' ? true : false;
			$settings[ $attribute_taxonomy->attribute_name . '-display' ]					= strtolower( get_option( 'EWD_UWCF_' . $attribute_taxonomy->attribute_name .  '_Display' ) );
			$settings[ $attribute_taxonomy->attribute_name . '-display-thumbnail-terms' ]	= get_option( 'EWD_UWCF_' . $attribute_taxonomy->attribute_name .  '_Thumbnail_Tags' ) == 'Yes' ? true : false;
		}

		add_option( 'ewd-uwcf-review-ask-time', get_option( 'EWD_UWCF_Ask_Review_Date' ) );
		add_option( 'ewd-uwcf-installation-time', get_option( 'EWD_UWCF_Install_Time' ) );

		update_option( 'ewd-uwcf-permission-level', get_option( 'EWD_UWCF_Full_Version' ) == 'Yes' ? 2 : 1 );
		
		update_option( 'ewd-uwcf-settings', $settings );
	}
}

}