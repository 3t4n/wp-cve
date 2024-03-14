<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdupcpBackwardsCompatibility' ) ) {
/**
 * Class to handle transforming the plugin settings from the 
 * previous style (individual options) to the new one (options array)
 *
 * @since 5.0.0
 */
class ewdupcpBackwardsCompatibility {

	public function __construct() {

		$this->set_taxonomy_order();
		
		if ( empty( get_option( 'ewd-upcp-settings' ) ) and get_option( 'UPCP_Full_Version' ) ) { $this->run_backwards_compat(); }
		elseif ( ! get_option( 'ewd-upcp-permission-level' ) ) { update_option( 'ewd-upcp-permission-level', 1 ); }
		else {

			$timestamp = wp_next_scheduled( 'ewd_upcp_run_backwards_compat' );

			wp_unschedule_event( $timestamp, 'ewd_upcp_run_backwards_compat' );
		}
	}

	public function run_backwards_compat() {

		wp_schedule_single_event( time() + 240, 'ewd_upcp_run_backwards_compat' );

		if ( ! taxonomy_exists( EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY ) ) {

			return;
		}

		if ( get_transient( 'ewd-upcp-backwards-compat-running' ) ) { return; }

		set_transient( 'ewd-upcp-backwards-compat-running', true, 120 );

		if ( empty( get_option( 'ewd-upcp-transition-id' ) ) ) {

			$this->convert_product_page_tabs();

			$this->convert_tags();
			$this->convert_cats_and_sub_cats();
			$this->convert_custom_fields();
		}

		$this->convert_products();
		$this->convert_catalogs();

		$settings = array(
			'custom-css' 								=> get_option( 'EWD_UPCP_Custom_CSS' ),
			'color-scheme'								=> strtolower( get_option( 'UPCP_Color_Scheme' ) ),
			'disable-thumbnail-auto-adjust'				=> get_option( 'UPCP_Thumb_Auto_Adjust' ) == 'Yes' ? false : true,
			'currency-symbol'							=> get_option( 'UPCP_Currency_Symbol' ),
			'currency-symbol-location'					=> strtolower( get_option( 'UPCP_Currency_Symbol_Location' ) ),
			'product-links'								=> get_option( 'UPCP_Product_Links' ) == 'Same' ? true : false,
			'tag-logic'									=> strtolower( get_option( 'UPCP_Tag_Logic' ) ),
			'disable-price-filter'						=> get_option( 'UPCP_Price_Filter' ) == 'Yes' ? false : true,
			'disable-slider-filter-text-inputs'			=> get_option( 'UPCP_Slider_Filter_Inputs' ) == 'Yes' ? false : true,
			'sale-mode'									=> strtolower( get_option( 'UPCP_Sale_Mode' ) ),
			'details-read-more'							=> get_option( 'UPCP_Read_More' ) == 'Yes' ? true : false,
			'details-description-characters'			=> get_option( 'UPCP_Desc_Chars' ),
			'sidebar-layout'							=> strtolower( get_option( 'UPCP_Sidebar_Order' ) ),
			'apply-contents-filter'						=> get_option( 'UPCP_Apply_Contents_Filter' ) == 'Yes' ? true : false,
			'maintain-filtering'						=> get_option( 'UPCP_Maintain_Filtering' ) == 'Yes' ? true : false,
			'disable-product-page-price'				=> get_option( 'UPCP_Single_Page_Price' ) == 'Yes' ? false : true,
			'thumbnail-support'							=> get_option( 'UPCP_Thumbnail_Support' ) == 'Yes' ? true : false,
			'show-category-descriptions'				=> get_option( 'UPCP_Show_Category_Descriptions' ) == 'Yes' ? true : false,
			'show-catalog-information'					=> get_option( 'UPCP_Show_Catalogue_Information' ) == 'NameDescription' ? array( 'name', 'description' ) : (array) strtolower( get_option( 'UPCP_Show_Catalogue_Information' ) ),
			'search-content-filtering'					=> get_option( 'UPCP_Inner_Filter' ) == 'Yes' ? true : false,
			'clear-all-filtering'						=> get_option( 'UPCP_Clear_All' ) == 'Yes' ? true : false,
			'hide-empty-options-filtering'				=> get_option( 'UPCP_Hide_Empty_Options' ) == 'Yes' ? true : false,
			'extra-elements'							=> explode( ',', strtolower( get_option( 'UPCP_Extra_Elements' ) ) ),
			'display-category-image'					=> get_option( 'UPCP_Display_Category_Image' ) == 'No' ? array() : explode( ',', strtolower( get_option( 'UPCP_Display_Category_Image' ) ) ),
			'display-subcategory-image'					=> get_option( 'UPCP_Display_SubCategory_Image' ) == 'No' ? false: true,
			'overview-mode'								=> strtolower( get_option( 'UPCP_Overview_Mode' ) ),
			'breadcrumbs'								=> get_option( 'UPCP_Breadcrumbs' ) == 'Catalogue' ? array( 'catalog' ) : ( get_option( 'UPCP_Breadcrumbs' ) == 'Categories' ? array( 'catalog', 'categories' ) : ( get_option( 'UPCP_Breadcrumbs' ) == 'Subcategories' ? array( 'catalog', 'categories', 'subcategories' ) : array() ) ),
			'social-media-links'						=> explode( ',', strtolower( get_option( 'UPCP_Social_Media' ) ) ),
			'display-categories-in-product-thumbnail'	=> get_option( 'UPCP_Display_Categories_In_Thumbnails' ) == 'Yes' ? true : false,
			'display-tags-in-product-thumbnail'			=> get_option( 'UPCP_Display_Tags_In_Thumbnails' ) == 'Yes' ? true : false,

			'product-page'								=> get_option( 'UPCP_Custom_Product_Page' ) == 'No' ? 'default' : ( get_option( 'UPCP_Custom_Product_Page' ) == 'Yes' ? 'custom' : strtolower( get_option( 'UPCP_Custom_Product_Page' ) ) ),
			'product-comparison'						=> get_option( 'UPCP_Product_Comparison' ) == 'Yes' ? true : false,
			'product-inquiry-form'						=> get_option( 'UPCP_Product_Inquiry_Form' ) == 'Yes' ? true : false,
			'product-inquiry-cart'						=> get_option( 'UPCP_Product_Inquiry_Cart' ) == 'Yes' ? true : false,
			'product-inquiry-plugin'					=> strtolower( get_option( 'UPCP_Inquiry_Plugin' ) ),
			'product-inquiry-form-email'				=> get_option( 'UPCP_Inquiry_Form_Email' ),
			'product-reviews'							=> get_option( 'UPCP_Product_Reviews' ) == 'Yes' ? true : false,
			'catalog-display-reviews'					=> get_option( 'UPCP_Catalog_Display_Reviews' ) == 'Yes' ? true : false,
			'product-faqs'								=> get_option( 'UPCP_Product_FAQs' ) == 'Yes' ? true : false,
			'product-image-lightbox'					=> strtolower( get_option( 'UPCP_Lightbox' ) ),
			'lightbox-mode'								=> get_option( 'UPCP_Lightbox_Mode' ) == 'Yes' ? true : false,
			'disable-toggle-sidebar-on-mobile'			=> get_option( 'UPCP_Hidden_Drop_Down_Sidebar_On_Mobile' ) == 'Yes' ? false : true,
			'infinite-scroll'							=> get_option( 'UPCP_Infinite_Scroll' ) == 'Yes' ? true : false,
			'products-per-page'							=> get_option( 'UPCP_Products_Per_Page' ),
			'pagination-location'						=> strtolower( get_option( 'UPCP_Pagination_Location' ) ),
			'product-search'							=> $this->convert_product_search_options(),
			'product-sort'								=> is_array( get_option( 'UPCP_Product_Sort' ) ) ? array_map( 'strtolower', get_option( 'UPCP_Product_Sort' ) ) : array(),
			'disable-custom-field-conversion'			=> get_option( 'UPCP_CF_Conversion' ) == 'Yes' ? false : true,
			'related-products'							=> get_option( 'UPCP_Related_Products' ) == 'Auto' ? 'automatic' : strtolower( get_option( 'UPCP_Related_Products' ) ),
			'next-previous-products'					=> get_option( 'UPCP_Next_Previous' ) == 'Auto' ? 'automatic' : strtolower( get_option( 'UPCP_Next_Previous' ) ),
			'access-role'								=> get_option( 'UPCP_Access_Role' ),
			'hide-blank-custom-fields'					=> get_option( 'UPCP_Custom_Fields_Blank' ) == 'Yes' ? true : false,

			'woocommerce-sync'							=> get_option( 'UPCP_WooCommerce_Sync' ) == 'Yes' ? true : false,
			'woocommerce-disable-cart-count'			=> get_option( 'UPCP_WooCommerce_Show_Cart_Count' ) == 'Yes' ? false : true,
			'woocommerce-checkout'						=> get_option( 'UPCP_WooCommerce_Checkout' ) == 'Yes' ? true : false,
			'woocommerce-cart-page'						=> strtolower( get_option( 'UPCP_WooCommerce_Cart_Page' ) ),
			'woocommerce-product-page'					=> get_option( 'UPCP_WooCommerce_Product_Page' ) == 'Yes' ? true : false,
			'woocommerce-back-link'						=> get_option( 'UPCP_WooCommerce_Back_Link' ) == 'Yes' ? true : false,

			'pretty-permalinks'							=> get_option( 'UPCP_Pretty_Links' ) == 'Yes' ? true : false,
			'permalink-base'							=> get_option( 'UPCP_Permalink_Base' ),
			'xml-sitemap-url'							=> get_option( 'UPCP_XML_Sitemap_URL' ),
			'seo-plugin'								=> strtolower( get_option( 'UPCP_SEO_Option' ) ),
			'seo-integration'							=> strtolower( get_option( 'UPCP_SEO_Integration' ) ),
			'seo-title'									=> get_option( 'UPCP_SEO_Title' ),

			'label-categories'							=> get_option( 'UPCP_Categories_Label' ),
			'label-subcategories'						=> get_option( 'UPCP_SubCategories_Label' ),
			'label-tags'								=> get_option( 'UPCP_Tags_Label' ),
			'label-custom-fields'						=> get_option( 'UPCP_Custom_Fields_Label' ),
			'label-show-all'							=> get_option( 'UPCP_Show_All_Label' ),
			'label-details'								=> get_option( 'UPCP_Details_Label' ),
			'label-sort-by'								=> get_option( 'UPCP_Sort_By_Label' ),
			'label-price-ascending'						=> get_option( 'UPCP_Price_Ascending_Label' ),
			'label-price-descending'					=> get_option( 'UPCP_Price_Descending_Label' ),
			'label-name-ascending'						=> get_option( 'UPCP_Name_Ascending_Label' ),
			'label-name-descending'						=> get_option( 'UPCP_Name_Descending_Label' ),
			'label-product-name-search'					=> get_option( 'UPCP_Product_Name_Search_Label' ),
			'label-product-name-text'					=> get_option( 'UPCP_Product_Name_Text_Label' ),
			'label-back-to-catalog'						=> get_option( 'UPCP_Back_To_Catalogue_Label' ),
			'label-updating-results'					=> get_option( 'UPCP_Updating_Results_Label' ),
			'label-no-results-found'					=> get_option( 'UPCP_No_Results_Found_Label' ),
			'label-products-pagination'					=> get_option( 'UPCP_Products_Pagination_Label' ),
			'label-read-more'							=> get_option( 'UPCP_Read_More_Label' ),
			'label-product-details-tab'					=> get_option( 'UPCP_Product_Details_Label' ),
			'label-additional-info-tab'					=> get_option( 'UPCP_Additional_Info_Label' ),
			'label-contact-form-tab'					=> get_option( 'UPCP_Contact_Us_Label' ),
			'label-product-inquiry-form-title'			=> get_option( 'UPCP_Product_Inquiry_Form_Title_Label' ),
			'label-customer-reviews-tab'				=> get_option( 'UPCP_Customer_Reviews_Tab_Label' ),
			'label-related-products'					=> get_option( 'UPCP_Related_Products_Label' ),
			'label-next-product'						=> get_option( 'UPCP_Next_Product_Label' ),
			'label-previous-product'					=> get_option( 'UPCP_Previous_Product_Label' ),
			'label-pagination-of'						=> get_option( 'UPCP_Of_Pagination_Label' ),
			'label-compare'								=> get_option( 'UPCP_Compare_Label' ),
			'label-sale'								=> get_option( 'UPCP_Sale_Label' ),
			'label-side-by-side'						=> get_option( 'UPCP_Side_By_Side_Label' ),
			'label-inquire-button'						=> get_option( 'UPCP_Inquire_Button_Label' ),
			'label-add-to-cart-button'					=> get_option( 'UPCP_Add_To_Cart_Button_Label' ),
			'label-send-inquiry'						=> get_option( 'UPCP_Send_Inquiry_Label' ),
			'label-checkout'							=> get_option( 'UPCP_Checkout_Label' ),
			'label-empty-cart'							=> get_option( 'UPCP_Empty_Cart_Label' ),
			'label-cart-items'							=> get_option( 'UPCP_Cart_Items_Label' ),
			'label-product-page-category'				=> get_option( 'UPCP_Additional_Info_Category_Label' ),
			'label-product-page-subcategory'			=> get_option( 'UPCP_Additional_Info_SubCategory_Label' ),
			'label-product-page-tags'					=> get_option( 'UPCP_Additional_Info_Tags_Label' ),
			'label-price-filter'						=> get_option( 'UPCP_Price_Filter_Label' ),
			'label-product-inquiry-please-use'			=> get_option( 'UPCP_Product_Inquiry_Please_Use_Label' ),
			
			'styling-catalog-skin'								=> get_option( 'UPCP_Catalogue_Style' ) == 'None' ? 'default' : strtolower( get_option( 'UPCP_Catalogue_Style' ) ),
			'styling-category-heading-style'					=> strtolower( get_option( 'UPCP_Category_Heading_Style' ) ),
			'styling-compare-button-background-color'			=> get_option( 'UPCP_Compare_Button_Background_Color' ),
			'styling-compare-button-text-color'					=> get_option( 'UPCP_Compare_Button_Text_Color' ),
			'styling-compare-button-clicked-background-color'	=> get_option( 'UPCP_Compare_Button_Clicked_Background_Color' ),
			'styling-compare-button-clicked-text-color'			=> get_option( 'UPCP_Compare_Button_Clicked_Text_Color' ),
			'styling-compare-button-font-size'					=> get_option( 'UPCP_Compare_Button_Font_Size' ),
			'styling-sale-button-background-color'				=> get_option( 'UPCP_Sale_Button_Background_Color' ),
			'styling-sale-button-text-color'					=> get_option( 'UPCP_Sale_Button_Text_Color' ),
			'styling-sale-button-font-size'						=> get_option( 'UPCP_Sale_Button_Font_Size' ),
			'styling-details-icon-type'							=> get_option( 'UPCP_Details_Icon_Type' ),
			'styling-details-image'								=> get_option( 'UPCP_Details_Image' ),
			'styling-details-icon-font-color'					=> get_option( 'UPCP_Details_Icon_Color' ),
			'styling-details-icon-font-size'					=> get_option( 'UPCP_Details_Icon_Font_Size' ),
			'styling-details-icon-font'							=> get_option( 'UPCP_Details_Icon_Font_Selection' ),
			'styling-product-comparison-title-font-size'		=> get_option( 'UPCP_Product_Comparison_Title_Font_Size' ),
			'styling-product-comparison-title-font-color'		=> get_option( 'UPCP_Product_Comparison_Title_Font_Color' ),
			'styling-product-comparison-price-font-size'		=> get_option( 'UPCP_Product_Comparison_Price_Font_Size' ),
			'styling-product-comparison-price-font-color'		=> get_option( 'UPCP_Product_Comparison_Price_Font_Color' ),
			'styling-product-comparison-price-background-color'	=> get_option( 'UPCP_Product_Comparison_Price_Background_Color' ),

			'styling-thumbnail-view-image-height'				=> get_option( 'UPCP_Thumbnail_View_Image_Height' ),
			'styling-thumbnail-view-image-width'				=> get_option( 'UPCP_Thumbnail_View_Image_Width' ),
			'styling-thumbnail-view-image-holder-height'		=> get_option( 'UPCP_Thumbnail_View_Image_Holder_Height' ),
			'styling-thumbnail-view-image-holder-width'			=> get_option( 'UPCP_Thumbnail_View_Image_Holder_Width' ),
			'styling-thumbnail-view-image-border-color'			=> get_option( 'UPCP_Thumbnail_View_Image_Border_Color' ),
			'styling-thumbnail-view-box-width'					=> get_option( 'UPCP_Thumbnail_View_Box_Width' ),
			'styling-thumbnail-view-box-min-height'				=> get_option( 'UPCP_Thumbnail_View_Box_Min_Height' ),
			'styling-thumbnail-view-box-max-height'				=> get_option( 'UPCP_Thumbnail_View_Box_Max_Height' ),
			'styling-thumbnail-view-box-padding'				=> get_option( 'UPCP_Thumbnail_View_Box_Padding' ),
			'styling-thumbnail-view-box-margin'					=> get_option( 'UPCP_Thumbnail_View_Box_Margin' ),
			'styling-thumbnail-view-border-color'				=> get_option( 'UPCP_Thumbnail_View_Border_Color' ),
			'styling-thumbnail-view-title-font'					=> get_option( 'UPCP_Thumbnail_View_Title_Font' ),
			'styling-thumbnail-view-title-font-size'			=> get_option( 'UPCP_Thumbnail_View_Title_Font_Size' ),
			'styling-thumbnail-view-title-font-color'			=> get_option( 'UPCP_Thumbnail_View_Title_Color' ),
			'styling-thumbnail-view-price-font'					=> get_option( 'UPCP_Thumbnail_View_Price_Font' ),
			'styling-thumbnail-view-price-font-size'			=> get_option( 'UPCP_Thumbnail_View_Price_Font_Size' ),
			'styling-thumbnail-view-price-font-color'			=> get_option( 'UPCP_Thumbnail_View_Price_Color' ),
			'styling-thumbnail-view-background-color'			=> get_option( 'UPCP_Thumbnail_View_Background_Color' ),

			'styling-list-view-click-action'					=> strtolower( get_option( 'UPCP_List_View_Click_Action' ) ),
			'styling-list-view-image-height'					=> get_option( 'UPCP_List_View_Image_Height' ),
			'styling-list-view-image-width'						=> get_option( 'UPCP_List_View_Image_Width' ),
			'styling-list-view-image-holder-height'				=> get_option( 'UPCP_List_View_Image_Holder_Height' ),
			'styling-list-view-image-border-color'				=> get_option( 'UPCP_List_View_Image_Border_Color' ),
			'styling-list-view-image-background-color'			=> get_option( 'UPCP_List_View_Image_Background_Color' ),
			'styling-list-view-box-margin-left'					=> get_option( 'UPCP_List_View_Item_Margin_Left' ),
			'styling-list-view-box-margin-top'					=> get_option( 'UPCP_List_View_Item_Margin_Top' ),
			'styling-list-view-box-padding'						=> get_option( 'UPCP_List_View_Item_Padding' ),
			'styling-list-view-box-border-color'				=> get_option( 'UPCP_List_View_Item_Color' ),
			'styling-list-view-title-font'						=> get_option( 'UPCP_List_View_Title_Font' ),
			'styling-list-view-title-font-size'					=> get_option( 'UPCP_List_View_Title_Font_Size' ),
			'styling-list-view-title-font-color'				=> get_option( 'UPCP_List_View_Title_Color' ),
			'styling-list-view-price-font'						=> get_option( 'UPCP_List_View_Price_Font' ),
			'styling-list-view-price-font-size'					=> get_option( 'UPCP_List_View_Price_Font_Size' ),
			'styling-list-view-price-font-color'				=> get_option( 'UPCP_List_View_Price_Color' ),

			'styling-detail-view-image-height'					=> get_option( 'UPCP_Detail_View_Image_Height' ),
			'styling-detail-view-image-width'					=> get_option( 'UPCP_Detail_View_Image_Width' ),
			'styling-detail-view-image-holder-height'			=> get_option( 'UPCP_Detail_View_Image_Holder_Height' ),
			'styling-detail-view-image-holder-width'			=> get_option( 'UPCP_Detail_View_Image_Holder_Width' ),
			'styling-detail-view-image-border-color'			=> get_option( 'UPCP_Detail_View_Image_Border_Color' ),
			'styling-detail-view-image-background-color'		=> get_option( 'UPCP_Detail_View_Image_Background_Color' ),
			'styling-detail-view-box-width'						=> get_option( 'UPCP_Detail_View_Box_Width' ),
			'styling-detail-view-box-padding'					=> get_option( 'UPCP_Detail_View_Box_Padding' ),
			'styling-detail-view-box-margin'					=> get_option( 'UPCP_Detail_View_Box_Margin' ),
			'styling-detail-view-box-border-color'				=> get_option( 'UPCP_Detail_View_Border_Color' ),	
			'styling-detail-view-box-background-color'			=> get_option( 'UPCP_Detail_View_Background_Color' ),		
			'styling-detail-view-title-font'					=> get_option( 'UPCP_Detail_View_Title_Font' ),
			'styling-detail-view-title-font-size'				=> get_option( 'UPCP_Detail_View_Title_Font_Size' ),
			'styling-detail-view-title-font-color'				=> get_option( 'UPCP_Detail_View_Title_Color' ),
			'styling-detail-view-price-font'					=> get_option( 'UPCP_Detail_View_Price_Font' ),
			'styling-detail-view-price-font-size'				=> get_option( 'UPCP_Detail_View_Price_Font_Size' ),
			'styling-detail-view-price-font-color'				=> get_option( 'UPCP_Detail_View_Price_Color' ),

			'styling-sidebar-title-collapse'					=> get_option( 'UPCP_Sidebar_Title_Collapse' ) == 'yes' ? true : false,
			'styling-sidebar-subcategory-collapse'				=> get_option( 'UPCP_Sidebar_Subcat_Collapse' ) == 'yes' ? true : false,
			'styling-sidebar-start-collapsed'					=> get_option( 'UPCP_Sidebar_Start_Collapsed' ) == 'yes' ? true : false,
			'styling-sidebar-title-hover'						=> get_option( 'UPCP_Sidebar_Title_Hover' ),
			'styling-sidebar-checkbox-style'					=> get_option( 'UPCP_Sidebar_Checkbox_Style' ),
			'styling-sidebar-custom-fields-show-hide'			=> strtolower( get_option( 'UPCP_Custom_Fields_Show_Hide' ) ),
			'styling-sidebar-categories-control-type'			=> strtolower( get_option( 'UPCP_Categories_Control_Type' ) ),
			'styling-sidebar-subcategories-control-type'		=> strtolower( get_option( 'UPCP_SubCategories_Control_Type' ) ),
			'styling-sidebar-tags-control-type'					=> strtolower( get_option( 'UPCP_Tags_Control_Type' ) ),
			'styling-sidebar-items-order'						=> $this->convert_sidebar_items_order(),
			'styling-sidebar-header-font'						=> get_option( 'UPCP_Sidebar_Header_Font' ),
			'styling-sidebar-header-font-size'					=> get_option( 'UPCP_Sidebar_Header_Font_Size' ),
			'styling-sidebar-header-font-weight'				=> get_option( 'UPCP_Sidebar_Header_Font_Weight' ),
			'styling-sidebar-header-font-color'					=> get_option( 'UPCP_Sidebar_Header_Color' ),
			'styling-sidebar-subheader-font'					=> get_option( 'UPCP_Sidebar_Subheader_Font' ),
			'styling-sidebar-subheader-font-size'				=> get_option( 'UPCP_Sidebar_Subheader_Font_Size' ),
			'styling-sidebar-subheader-font-weight'				=> get_option( 'UPCP_Sidebar_Subheader_Font_Weight' ),
			'styling-sidebar-subheader-font-color'				=> get_option( 'UPCP_Sidebar_Subheader_Color' ),
			'styling-sidebar-checkbox-font'						=> get_option( 'UPCP_Sidebar_Checkbox_Font' ),
			'styling-sidebar-checkbox-font-size'				=> get_option( 'UPCP_Sidebar_Checkbox_Font_Size' ),
			'styling-sidebar-checkbox-font-weight'				=> get_option( 'UPCP_Sidebar_Checkbox_Font_Weight' ),
			'styling-sidebar-checkbox-font-color'				=> get_option( 'UPCP_Sidebar_Checkbox_Color' ),

			'styling-breadcrumbs-font'							=> get_option( 'UPCP_Breadcrumbs_Font_Family' ),
			'styling-breadcrumbs-font-size'						=> get_option( 'UPCP_Breadcrumbs_Font_Size' ),
			'styling-breadcrumbs-font-color'					=> get_option( 'UPCP_Breadcrumbs_Font_Color' ),
			'styling-breadcrumbs-font-hover-color'				=> get_option( 'UPCP_Breadcrumbs_Font_Hover_Color' ),

			'styling-pagination-font'							=> get_option( 'UPCP_Pagination_Font' ),
			'styling-pagination-border'							=> get_option( 'UPCP_Pagination_Border' ),
			'styling-pagination-shadow'							=> get_option( 'UPCP_Pagination_Shadow' ) == 'shadow' ? true : false,
			'styling-pagination-gradient'						=> get_option( 'UPCP_Pagination_Gradient' ) == 'gradient' ? true : false,

			'styling-product-page-grid-width'					=> get_option( 'UPCP_PP_Grid_Width' ),
			'styling-product-page-grid-height'					=> get_option( 'UPCP_PP_Grid_Height' ),
			'styling-product-page-top-bottom-padding'			=> get_option( 'UPCP_Top_Bottom_Padding' ),
			'styling-product-page-left-right-padding'			=> get_option( 'UPCP_Left_Right_Padding' )
		);

		add_option( 'ewd-upcp-review-ask-time', get_option( 'UPCP_Ask_Review_Date' ) );
		add_option( 'ewd-upcp-installation-time', get_option( 'UPCP_Install_Time' ) );

		update_option( 'ewd-upcp-permission-level', get_option( 'UPCP_Full_Version' ) == 'Yes' ? 2 : 1 );

		update_option( 'EWD_UPCP_Trial_Happening', get_option( 'UPCP_Trial_Happening' ) );
		
		update_option( 'ewd-upcp-settings', $settings );

		//End the upgrade process
		delete_option( 'ewd-upcp-transition-id' );

		$timestamp = wp_next_scheduled( 'ewd_upcp_run_backwards_compat' );

		wp_unschedule_event( $timestamp, 'ewd_upcp_run_backwards_compat' );
	}

	public function convert_sidebar_items_order() {

		$old_sidebar_items = is_array( get_option( 'UPCP_Sidebar_Items_Order' ) ) ? get_option( 'UPCP_Sidebar_Items_Order' ) : array();

		$new_sidebar_items = array();

		foreach ( $old_sidebar_items as $old_sidebar_item ) {

			if ( $old_sidebar_item == 'Product Sort' ) { $new_sidebar_items['sort'] = 'Product Sort'; }
			elseif ( $old_sidebar_item == 'Product Search' ) { $new_sidebar_items['search'] = 'Product Search'; }
			elseif ( $old_sidebar_item == 'Product Filter' ) { $new_sidebar_items['price_filter'] = 'Price Filter'; }
			elseif ( $old_sidebar_item == 'Categories' ) { $new_sidebar_items['categories'] = 'Categories'; }
			elseif ( $old_sidebar_item == 'Sub-Categories' ) { $new_sidebar_items['subcategories'] = 'Sub-Categories'; }
			elseif ( $old_sidebar_item == 'Tags' ) { $new_sidebar_items['tags'] = 'Tags'; }
			elseif ( $old_sidebar_item == 'Custom Fields' ) { $new_sidebar_items['custom_fields'] = 'Custom Fields'; }
		}

		$defaults = array( 
			'sort'			=> 'Product Sort', 
			'search'		=> 'Product Search', 
			'price_filter'	=> 'Price Filter', 
			'categories'	=> 'Categories', 
			'subcategories'	=> 'Sub-Categories', 
			'tags'			=> 'Tags', 
			'custom_fields' => 'Custom Fields'
		);

		$new_sidebar_items = array_merge( $new_sidebar_items, $defaults );

		return json_encode( $new_sidebar_items );
	}

	public function convert_product_page_tabs() {

		$old_tabs = is_array( get_option( 'UPCP_Tabs_Array' ) ) ? get_option( 'UPCP_Tabs_Array' ) : array();

		$new_tabs = array();

		foreach ( $old_tabs as $old_tab ) {

			$new_tab = (object) array(
				'name'		=> $old_tab['Name'],
				'content'	=> $old_tab['Content']
			);

			$new_tabs[] = $new_tab;
		}

		update_option( 'ewd-upcp-product-page-tabs', $new_tabs );

		update_option( 'ewd-upcp-product-page-starting-tab', ( get_option( 'UPCP_Starting_Tab' ) == 'addtl-information' ? 'additional_information' : get_option( 'UPCP_Starting_Tab' ) ) );
	}

	public function convert_tags() {
		global $wpdb;

		$tags_table_name = $wpdb->prefix . 'UPCP_Tags';

		$old_tags = $wpdb->get_results( "SELECT * FROM $tags_table_name" );

		foreach ( $old_tags as $old_tag ) {

			$args = array(
				'description'	=> $old_tag->Tag_Description
			);

			$new_tag = wp_insert_term( $old_tag->Tag_Name, EWD_UPCP_PRODUCT_TAG_TAXONOMY, $args );

			if ( ! is_wp_error( $new_tag ) ) {

				update_term_meta( $new_tag['term_id'], 'old_tag_id', $old_tag->Tag_ID );
				update_term_meta( $new_tag['term_id'], 'group_id', $old_tag->Tag_Group_ID );
				update_term_meta( $new_tag['term_id'], 'order', $old_tag->Tag_Sidebar_Order );
				update_term_meta( $new_tag['term_id'], 'woocommerce_id', $old_tag->Tag_WC_ID );
			}
		}

		$tag_groups_table_name = $wpdb->prefix . 'UPCP_Tag_Groups';

		$old_tag_groups = $wpdb->get_results( "SELECT * FROM $tag_groups_table_name" );

		$tag_groups = array();

		foreach( $old_tag_groups as $old_tag_group ) {

			$new_tag_group = array(
				'id'			=> $old_tag_group->Tag_Group_ID,
				'name'			=> $old_tag_group->Tag_Group_Name,
				'description'	=> $old_tag_group->Tag_Group_Description,
				'display'		=> $old_tag_group->Display_Tag_Group
			);
		}

		update_option( 'ewd-upcp-tag-groups', $tag_groups );
	}

	public function convert_cats_and_sub_cats() {
		global $wpdb;

		$categories_table_name = $wpdb->prefix . 'UPCP_Categories';

		$old_categories = $wpdb->get_results( "SELECT * FROM $categories_table_name" );

		foreach ( $old_categories as $old_category ) {

			$args = array(
				'description'	=> $old_category->Category_Description
			);

			$new_category = wp_insert_term( $old_category->Category_Name, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY, $args );

			if ( ! is_wp_error( $new_category ) ) {

				update_term_meta( $new_category['term_id'], 'old_category_id', $old_category->Category_ID );
				update_term_meta( $new_category['term_id'], 'image', $old_category->Category_Image );
				update_term_meta( $new_category['term_id'], 'order', $old_category->Category_Sidebar_Order );
				update_term_meta( $new_category['term_id'], 'woocommerce_id', $old_category->Category_WC_ID );
			}
		}

		$subcategories_table_name = $wpdb->prefix . 'UPCP_SubCategories';

		$old_subcategories = $wpdb->get_results( "SELECT * FROM $subcategories_table_name" );

		foreach ( $old_subcategories as $old_subcategory ) {

			$args = array(
				'hide_empty'	=> false,
				'meta_query'	=> array(
					array(
						'key'		=> 'old_category_id',
						'value'		=> $old_subcategory->Category_ID,
						'compare'	=> 'LIKE'
					)
				),
				'taxonomy'		=> EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY
			);

			$terms = get_terms( $args );

			$parent_category = ! empty( $terms) ? reset( $terms ) : (object) array( 'term_id' => 0 );

			$args = array(
				'description'	=> $old_subcategory->SubCategory_Description,
				'parent'		=> $parent_category->term_id
			);

			$new_category = wp_insert_term( $old_subcategory->SubCategory_Name, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY, $args );

			if ( ! is_wp_error( $new_category ) ) {

				update_term_meta( $new_category['term_id'], 'old_subcategory_id', $old_subcategory->SubCategory_ID );
				update_term_meta( $new_category['term_id'], 'image', $old_subcategory->SubCategory_Image );
				update_term_meta( $new_category['term_id'], 'order', $old_subcategory->SubCategory_Sidebar_Order );
				update_term_meta( $new_category['term_id'], 'woocommerce_id', $old_subcategory->SubCategory_WC_ID );
			}
		}
	}

	public function convert_custom_fields() {
		global $wpdb;

		$custom_fields_table_name = $wpdb->prefix . 'UPCP_Custom_Fields';

		$old_fields = $wpdb->get_results( "SELECT * FROM $custom_fields_table_name ORDER BY Field_Sidebar_Order ASC" );

		$new_fields = array();

		foreach ( $old_fields as $old_field ) {

			$new_field = array(
				'id'					=> $old_field->Field_ID,
				'name'					=> $old_field->Field_Name,
				'slug'					=> $old_field->Field_Slug,
				'type'					=> $old_field->Field_Type == 'mediumint' ? 'number' : $old_field->Field_Type,
				'options'				=> $old_field->Field_Values,
				'displays'				=> $old_field->Field_Displays == 'none' ? array() : ( $old_field->Field_Displays == 'both' ? array( 'thumbnail', 'list', 'detail' ) : ( $old_field->Field_Displays == 'thumbs' ? array( 'thumbnail' ) : ( $old_field->Field_Displays == 'list' ? array( 'list' ) : array( 'detail' ) ) ) ),
				'filter_control_type'	=> strtolower( $old_field->Field_Control_Type ),
				'searchable'			=> $old_field->Field_Searchable == 'Yes' ? true : false,
				'tabbed_display'		=> $old_field->Field_Display_Tabbed == 'Yes' ? true : false,
				'comparison_display'	=> $old_field->Field_Display_Comparison == 'Yes' ? true : false,
				'woocommerce_id'		=> $old_field->Field_WC_ID
			);

			$new_fields[] = (object) $new_field;
		}

		update_option( 'ewd-upcp-custom-fields', $new_fields );
	}

	public function convert_products() {
		global $wpdb;

		$args = array(
			'hide_empty'	=> false,
			'taxonomy'		=> EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY
		);

		$categories = get_terms( $args );

		foreach ( $categories as $category ) {

			$category->old_category_id = get_term_meta( $category->term_id, 'old_category_id', true );
			$category->old_subcategory_id = get_term_meta( $category->term_id, 'old_subcategory_id', true );
		}

		$args = array(
			'hide_empty'	=> false,
			'taxonomy'		=> EWD_UPCP_PRODUCT_TAG_TAXONOMY
		);

		$tags = get_terms( $args );

		foreach ( $tags as $tag ) {

			$tag->old_tag_id = get_term_meta( $tag->term_id, 'old_tag_id', true );
		}

		$custom_fields = get_option( 'ewd-upcp-custom-fields' );

		$products_table_name = $wpdb->prefix . 'UPCP_Items';

		$old_products = $wpdb->get_results( "SELECT * FROM $products_table_name ORDER BY Item_ID" );

		$starting_item_id = empty( get_option( 'ewd-upcp-transition-id' ) ) ? 0 : intval( get_option( 'ewd-upcp-transition-id' ) );

		foreach ( $old_products as $old_product ) {

			if ( $old_product->Item_ID <= $starting_item_id ) { continue; }

			set_transient( 'ewd-upcp-backwards-compat-running', true, 10 );

			$args = array(
				'post_date'		=> $old_product->Item_Date_Created,
				'post_content'	=> ! empty( $old_product->Item_Description ) ? $old_product->Item_Description : '',
				'post_title'	=> $old_product->Item_Name,
				'post_type'		=> EWD_UPCP_PRODUCT_POST_TYPE,
				'post_name'		=> ! empty( $old_product->Item_Slug ) ? $old_product->Item_Slug : sanitize_title( $old_product->Item_Name ),
				'post_status'	=> 'publish'
			);

			$post_id = wp_insert_post( $args );

			update_option( 'ewd-upcp-transition-id', $old_product->Item_ID );

			if ( $post_id ) {

				update_post_meta( $post_id, 'old_product_id', $old_product->Item_ID );
				update_post_meta( $post_id, 'price', $old_product->Item_Price );
				update_post_meta( $post_id, 'sale_price', $old_product->Item_Sale_Price );
				update_post_meta( $post_id, 'sale_mode', $old_product->Item_Sale_Mode == 'Yes' ? true : false );
				update_post_meta( $post_id, 'link', $old_product->Item_Link );
				update_post_meta( $post_id, 'views', $old_product->Item_Views );
				update_post_meta( $post_id, 'display', $old_product->Item_Display_Status == 'Show' ? true : false );
				update_post_meta( $post_id, 'related_products', explode( ',', $old_product->Item_Related_Products ) );
				update_post_meta( $post_id, 'next_product', substr( $old_product->Item_Next_Previous, 0, strpos( $old_product->Item_Next_Previous, ',' ) ) );
				update_post_meta( $post_id, 'previous_product', substr( $old_product->Item_Next_Previous, strpos( $old_product->Item_Next_Previous, ',' ) + 1 ) );
				update_post_meta( $post_id, 'order', $old_product->Item_Category_Product_Order );
				update_post_meta( $post_id, 'woocommerce_id', $old_product->Item_WC_ID );

				update_post_meta( $post_id, '_yoast_wpseo_metadesc', $old_product->Item_SEO_Description );
				update_post_meta( $post_id, '_yoast_wpseo_title', $old_product->Item_Name );

				// Main Image
				$thumbnail_id = attachment_url_to_postid( $old_product->Item_Photo_URL );

				if ( $thumbnail_id ) {

					set_post_thumbnail( $post_id, $thumbnail_id );
				}
				else {
					// see: https://wordpress.stackexchange.com/questions/158491/is-it-possible-set-a-featured-image-with-external-image-url
					update_post_meta( $post_id, 'external_image', true );
					update_post_meta( $post_id, 'external_image_url', $old_product->Item_Photo_URL );
				}

				// Categories
				foreach ( $categories as $category ) {

					if ( $category->old_category_id != $old_product->Category_ID ) { continue; }

					wp_set_object_terms( $post_id, intval( $category->term_id ), EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY );

					$wpdb->update(
    					$wpdb->term_relationships,
    					array( 
    						'term_order'	=> $old_product->Item_Category_Product_Order 
    					),
    					array(
    						'object_id'			=> $post_id,
    						'term_taxonomy_id'	=> $category->term_id
    					)
    				);
    					
				}

				foreach ( $categories as $category ) {

					if ( $category->old_subcategory_id != $old_product->SubCategory_ID ) { continue; }

					wp_set_object_terms( $post_id, intval( $category->term_id ), EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY, true );
				}

				// Tags
				$product_tags_table_name = $wpdb->prefix . 'UPCP_Tagged_Items';

				$old_product_tags = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $product_tags_table_name WHERE Item_ID=%d", $old_product->Item_ID ) );

				foreach ( $old_product_tags as $old_product_tag ) { 

					foreach ( $tags as $tag ) {

						if ( $tag->old_tag_id != $old_product_tag->Tag_ID ) { continue; } 

						wp_set_object_terms( $post_id, intval( $tag->term_id ), EWD_UPCP_PRODUCT_TAG_TAXONOMY, true );
					}
				}

				// Custom Fields
				$uploads_dir_array = wp_upload_dir();

				$uploads_dir = $uploads_dir_array['basedir'] . '/upcp-product-file-uploads/';

				$custom_fields_meta_table_name = $wpdb->prefix . 'UPCP_Fields_Meta';

				foreach ( $custom_fields as $custom_field ) {

					$product_meta_value = $wpdb->get_var( $wpdb->prepare( "SELECT Meta_Value FROM $custom_fields_meta_table_name WHERE Item_ID=%d AND Field_ID=%d", $old_product->Item_ID, $custom_field->id ) );

					if ( $custom_field->type == 'file' and ! empty( $product_meta_value ) ) {

						$product_meta_value = $uploads_dir . $product_meta_value; 
					}
					elseif ( $custom_field->type == 'checkbox' ) {

						$product_meta_value = ! empty( $product_meta_value ) ? explode( ',', $product_meta_value ) : null;
					}

					update_post_meta( $post_id, 'custom_field_' . $custom_field->id, $product_meta_value ); 
				}

				// Additional Images
				$item_images_table_name = $wpdb->prefix . 'UPCP_Item_Images';

				$old_item_images = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $item_images_table_name WHERE Item_ID=%d ORDER BY Item_Image_Order", $old_product->Item_ID ) );

				$product_images = array();

				foreach ( $old_item_images as $old_item_image ) {

					$product_image = (object) array(
						'url'			=> $old_item_image->Item_Image_URL,
						'description'	=> $old_item_image->Item_Image_Description
					);

					$product_images[] = $product_image;
				}

				update_post_meta( $post_id, 'product_images', $product_images );

				// Videos
				$item_videos_table_name = $wpdb->prefix . 'UPCP_Videos';

				$old_item_videos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $item_videos_table_name WHERE Item_ID=%d ORDER BY Item_Video_Order", $old_product->Item_ID ) );

				$product_videos = array();

				foreach ( $old_item_videos as $old_item_video ) {

					$product_video = (object) array(
						'url'		=> $old_item_video->Item_Video_URL,
						'type'		=> $old_item_video->Item_Video_Type
					);

					$product_videos[] = $product_video;
				}

				update_post_meta( $post_id, 'product_videos', $product_videos );
			}
		}
	}

	public function convert_catalogs() {
		global $wpdb;

		$catalogs_table_name = $wpdb->prefix . 'UPCP_Catalogues';

		$old_catalogs = $wpdb->get_results( "SELECT * FROM $catalogs_table_name" );

		foreach ( $old_catalogs as $old_catalog ) {

			$args = array(
				'post_date'		=> $old_catalog->Catalogue_Date_Created,
				'post_title'	=> $old_catalog->Catalogue_Name,
				'post_content'	=> $old_catalog->Catalogue_Description,
				'post_type'		=> EWD_UPCP_CATALOG_POST_TYPE,
				'post_status'	=> 'publish'
			);

			$post_id = wp_insert_post( $args );

			if ( $post_id ) { 

				update_post_meta( $post_id, 'old_catalog_id', $old_catalog->Catalogue_ID );
				update_post_meta( $post_id, 'layout', $old_catalog->Catalogue_Layout_Format );
				update_post_meta( $post_id, 'custom_css', $old_catalog->Catalogue_Custom_CSS );
				update_post_meta( $post_id, 'item_count', $old_catalog->Catalogue_Item_Count );

				$catalog_items_table_name = $wpdb->prefix . 'UPCP_Catalogue_Items';

				$old_catalog_items = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $catalog_items_table_name WHERE Catalogue_ID=%d ORDER BY Position", $old_catalog->Catalogue_ID ) );

				$catalog_items = array();

				foreach ( $old_catalog_items as $old_catalog_item ) {

					if ( $old_catalog_item->Item_ID ) {
						
						$args = array(
							'post_type'		=> EWD_UPCP_PRODUCT_POST_TYPE,
							'meta_query' 	=> array(
								array(
									'key'		=> 'old_product_id',
									'value'		=> $old_catalog_item->Item_ID
								)
							)
						);

						$query = new WP_Query( $args );

						$product_post = $query->posts[0];

						if ( ! empty( $product_post->ID ) ) {

							$catalog_item = (object) array(
								'type'	=> 'product',
								'id'	=> $product_post->ID
							);
						}
					}
					else {

						$args = array(
							'taxonomy'		=> EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY,
							'hide_empty'	=> false,
							'meta_query' 	=> array(
								array(
									'key'		=> 'old_category_id',
									'value'		=> $old_catalog_item->Category_ID
								)
							)
						);

						$category = get_terms( $args )[0];

						$catalog_item = (object) array(
							'type'	=> 'category',
							'id'	=> $category->term_id
						);
					}

					$catalog_items[] = $catalog_item;
				}

				update_post_meta( $post_id, 'items', $catalog_items );
			}
		}
	}

	public function convert_product_search_options() {

		$product_search_options = array();

		if ( strpos( strtolower( get_option( 'UPCP_Product_Search' ) ), 'name' ) !== false ) { $product_search_options[] = 'name'; }
		if ( strpos( strtolower( get_option( 'UPCP_Product_Search' ) ), 'desc' ) !== false ) { $product_search_options[] = 'description'; }
		if ( strpos( strtolower( get_option( 'UPCP_Product_Search' ) ), 'cust' ) !== false ) { $product_search_options[] = 'custom_fields'; }

		return $product_search_options;
	}

	public function set_taxonomy_order() {

		if ( ! taxonomy_exists( EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY ) ) { 

			wp_schedule_single_event( time() + 240, 'ewd_upcp_run_backwards_compat' );

			return; 
		}

		$args = array(
			'hide_empty'	=> false,
			'taxonomy'		=> EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY,
			'orderby'		=> 'count'
		);

		$categories = get_terms( $args );

		foreach ( $categories as $category ) {

			if ( ! empty( get_term_meta( $category->term_id, 'order', true ) ) ) { continue; }

			update_term_meta( $category->term_id, 'order', 9999 );
		}

		$args = array(
			'hide_empty'	=> false,
			'taxonomy'		=> EWD_UPCP_PRODUCT_TAG_TAXONOMY,
			'orderby'		=> 'count'
		);

		$tags = get_terms( $args );

		foreach ( $tags as $tag ) {

			if ( ! empty( get_term_meta( $tag->term_id, 'order', true ) ) ) { continue; }
			
			update_term_meta( $tag->term_id, 'order', 9999 );
		}
	}
}

}