<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdulbBackwardsCompatibility' ) ) {
/**
 * Class to handle transforming the plugin settings from the 
 * previous style (individual options) to the new one (options array)
 *
 * @since 2.0.0
 */
class ewdulbBackwardsCompatibility {

	public function __construct() {
		
		if ( empty( get_option( 'ulb-settings' ) ) and get_option( 'EWD_ULB_Full_Version' ) ) { $this->run_backwards_compat(); }

		update_option( 'ulb-permission-level', 2 );
	}

	public function run_backwards_compat() {
		
		$settings = array(
			'custom-css' 								=> get_option( 'EWD_ULB_Custom_CSS' ),
			'add-lightbox' 								=> get_option( 'EWD_ULB_Add_Lightbox' ) ? get_option( 'EWD_ULB_Add_Lightbox' ) : array(),
			'image-class-list' 							=> get_option( 'EWD_ULB_Image_Class_List' ),
			'image-selector-list' 						=> get_option( 'EWD_ULB_Image_Selector_List' ),
			'transition-effect'							=> get_option( 'EWD_ULB_Transition_Effect' ),
			'transition-speed'							=> get_option( 'EWD_ULB_Transition_Speed' ),
			'background-close'							=> get_option( 'EWD_ULB_Background_Close' ) == 'true' ? true : false,
			'gallery-loop'								=> get_option( 'EWD_ULB_Gallery_Loop' ) == 'true' ? true : false,
			'mousewheel-navigation'						=> get_option( 'EWD_ULB_Mousewheel_Navigation' ) == 'true' ? true : false,
			'curtain-slide'								=> get_option( 'EWD_ULB_Curtain_Slide' ) == 'true' ? true : false,
			'overlay-text-source'						=> get_option( 'EWD_ULB_Overlay_Text_Source' ),
			'disable-other-lightboxes'					=> get_option( 'EWD_ULB_Disable_Other_Lightboxes' ) == 'Yes' ? true : false,
			'show-thumbnails'							=> get_option( 'EWD_ULB_Show_Thumbnails' ),
			'show-thumbnail-toggle'						=> get_option( 'EWD_ULB_Show_Thumbnail_Toggle' ) == 'true' ? true : false,
			'show-overlay-text'							=> get_option( 'EWD_ULB_Show_Overlay_Text' ) == 'true' ? true : false,
			'start-autoplay'							=> get_option( 'EWD_ULB_Start_Autoplay' ) == 'true' ? true : false,
			'autoplay-interval'							=> get_option( 'EWD_ULB_Autoplay_Interval' ),
			'show-progress-bar'							=> get_option( 'EWD_ULB_Show_Progress_Bar' ) == 'true' ? true : false,
			'mobile-hide-elements'						=> get_option( 'EWD_ULB_Hide_On_Mobile' ) ? get_option( 'EWD_ULB_Hide_On_Mobile' ) : array(),
			'min-height'								=> get_option( 'EWD_ULB_Min_Height' ),
			'min-width'									=> get_option( 'EWD_ULB_Min_Width' ),
			'transition-type'							=> get_option( 'EWD_ULB_Transition_Type' ),
			'top-right-controls'						=> get_option( 'EWD_ULB_Top_Right_Controls' ) ? get_option( 'EWD_ULB_Top_Right_Controls' ) : array(),
			'top-left-controls'							=> get_option( 'EWD_ULB_Top_Left_Controls' ) ? get_option( 'EWD_ULB_Top_Left_Controls' ) : array(),
			'bottom-right-controls'						=> get_option( 'EWD_ULB_Bottom_Right_Controls' ) ? get_option( 'EWD_ULB_Bottom_Right_Controls' ) : array(),
			'bottom-left-controls'						=> get_option( 'EWD_ULB_Bottom_Left_Controls' ) ? get_option( 'EWD_ULB_Bottom_Left_Controls' ) : array(),
			'arrow'										=> get_option( 'EWD_ULB_Arrow' ),
			'icon-set'									=> get_option( 'EWD_ULB_Icon_Set' ),
			'styling-title-font'						=> get_option( 'EWD_ULB_Styling_Title_Font' ),
			'styling-title-font-size'					=> get_option( 'EWD_ULB_Styling_Title_Font_Size' ),
			'styling-title-font-color'					=> get_option( 'EWD_ULB_Styling_Title_Font_Color' ),
			'styling-description-font'					=> get_option( 'EWD_ULB_Styling_Description_Font' ),
			'styling-description-font-size'				=> get_option( 'EWD_ULB_Styling_Description_Font_Size' ),
			'styling-description-font-color'			=> get_option( 'EWD_ULB_Styling_Description_Font_Color' ),			
			'styling-arrow-size'						=> get_option( 'EWD_ULB_Styling_Arrow_Size' ),
			'styling-arrow-color'						=> get_option( 'EWD_ULB_Styling_Arrow_Color' ),
			'styling-arrow-background-color'			=> get_option( 'EWD_ULB_Styling_Arrow_Background_Color' ),
			'styling-arrow-background-opacity'			=> get_option( 'EWD_ULB_Styling_Arrow_Background_Opacity' ),
			'styling-arrow-background-hover-opacity'	=> get_option( 'EWD_ULB_Styling_Arrow_Size' ),
			'styling-icon-size'							=> get_option( 'EWD_ULB_Styling_Icon_Size' ),
			'styling-icon-color'						=> get_option( 'EWD_ULB_Styling_Icon_Color' ),
			'styling-background-overlay-color'			=> get_option( 'EWD_ULB_Styling_Background_Overlay_Color' ),
			'styling-background-overlay-opacity'		=> get_option( 'EWD_ULB_Styling_Background_Overlay_Opacity' ),
			'styling-toolbar-color'						=> get_option( 'EWD_ULB_Styling_Toolbar_Color' ),
			'styling-toolbar-opacity'					=> get_option( 'EWD_ULB_Styling_Toolbar_Opacity' ),
			'styling-image-overlay-color'				=> get_option( 'EWD_ULB_Styling_Image_Overlay_Color' ),
			'styling-image-overlay-opacity'				=> get_option( 'EWD_ULB_Styling_Image_Overlay_Opacity' ),
			'styling-thumbnail-bar-color'				=> get_option( 'EWD_ULB_Styling_Thumbnail_Bar_Color' ),
			'styling-thumbnail-bar-opacity'				=> get_option( 'EWD_ULB_Styling_Thumbnail_Bar_Opacity' ),
			'styling-thumbnail-scroll-arrow-color'		=> get_option( 'EWD_ULB_Styling_Thumbnail_Scroll_Arrow_Color' ),
			'styling-thumbnail-active-border-color'		=> get_option( 'EWD_ULB_Styling_Active_Thumbnail_Border_Color' ),
		);

		add_option( 'ulb-review-ask-time', get_option( 'EWD_ULB_Ask_Review_Date' ) );
		add_option( 'ulb-installation-time', get_option( 'EWD_ULB_Install_Time' ) );

		//$All_Controls = array('exit', 'autoplay', 'zoom', 'zoom_out', 'slide_counter', 'download', 'fullscreen', 'fullsize');
		
		update_option( 'ulb-settings', $settings );
	}
}

}