<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://thebrandiD.com
 * @since      2.0.0
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/public
 * @author     brandiD <tech@thebrandid.com>
 */
class Social_Proof_Slider_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'slick-css', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css', array(), '1.8.1', 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/social-proof-slider-public.css', array(), $this->version, 'all' );
		// wp_enqueue_style( 'slick-css', plugin_dir_url( __FILE__ ) . 'css/slick.css', array(), '1.6.0', 'all' );

		$fontawesome = 'fontawesome';
		$font_awesome = 'font-awesome';
		if ( wp_script_is( $font_awesome, 'enqueued' ) || wp_script_is( $fontawesome, 'enqueued' ) ) {
			return;
		} else {
			wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css', array(), 1.0 );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'slick-js', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js', array( 'jquery' ), '1.8.1', false );

	}

	/**
	 * Processes shortcode 'social-proof-slider'
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function spslider_shortcode( $atts = array() ) {

		ob_start();

		// Declare defaults
		$defaults['ids'] = '';
		$defaults['exclude'] = '';
		$defaults['category'] = '';

		$shared = new Social_Proof_Slider_Shared( $this->plugin_name, $this->version );

		// Get Shortcode Settings
		$sc_settings = $shared->get_shortcode_settings();

		// Get Attributes inside the manually-entered Shortcode
		$sc_atts = shortcode_atts( $defaults, $atts, 'social-proof-slider' );

		// Determine ORDERBY and ORDER args
		$sortbysetting = $sc_settings['sortby'];
		if ( $sortbysetting == "RAND" ) {
			$queryargs = array(
				'orderby' => 'rand',
			);
		} else {
			$queryargs = array(
				'order' => $sc_settings['sortby'],
				'orderby' => 'ID',
			);
		}

		$taxSlug = "";
		// Use category, if present
		if ( !empty( $sc_atts['category'] ) ) {
			$taxSlug = $sc_atts['category'];
		}

		$postLimiter = "";
		$limiterIDs = "";
		// Use Exclude/Include and IDs attributes, if present
		if ( !empty( $sc_atts['ids'] ) ) {

			$limiterIDs = $sc_atts['ids'];

			if ( !empty( $sc_atts['exclude'] ) ) {
				// EXCLUDING
				$postLimiter = $sc_atts['exclude'];
			} else {
				// INCLUDING
				$postLimiter = "";
			}

		}

		$showFeaturedImages = $sc_settings['showFeaturedImages'];

		$showImageBorder = $sc_settings['showImageBorder'];
		$imageBorderColor = $sc_settings['imageBorderColor'];
		$imageBorderThickness = $sc_settings['imageBorderThickness'];
		$imageBorderPadding = $sc_settings['imageBorderPadding'];

		$smartQuotes = $sc_settings['surroundWithQuotes'];

		$doPaddingOverride = $sc_settings['doPaddingOverride'];
		$contentPaddingTop = $sc_settings['contentPaddingTop'];
		$contentPaddingBottom = $sc_settings['contentPaddingBottom'];
		$featImgMarginTop = $sc_settings['featImgMarginTop'];
		$featImgMarginBottom = $sc_settings['featImgMarginBottom'];
		$textPaddingTop = $sc_settings['textPaddingTop'];
		$textPaddingBottom = $sc_settings['textPaddingBottom'];
		$quoteMarginBottom = $sc_settings['quoteMarginBottom'];
		$dotsMarginTop = $sc_settings['dotsMarginTop'];

		$contentPaddingStr = ''; // default
		$imgMarginStr = ''; // default
		$textPaddingStr = ''; // default
		$quoteMarginStr = ''; // default
		$dotsMarginStr = ''; // default

		if ( $doPaddingOverride == 'true' ) {
			$contentPaddingStr = "padding-top: ".$contentPaddingTop."px; padding-bottom: ".$contentPaddingBottom."px;";
			$imgMarginStr = "margin-top:".$featImgMarginTop."px; margin-bottom:".$featImgMarginBottom."px;";
			$textPaddingStr = "padding-top: ".$textPaddingTop."px; padding-bottom: ".$textPaddingBottom."px;";
			$quoteMarginStr = "margin-bottom: ".$quoteMarginBottom."px;";
			$dotsMarginStr = "margin-top: ".$dotsMarginTop."px;";
		}

		$alignStr = '';	// default
		if ( $sc_settings['doAutoHeight'] != 'true' ) {
			$alignStr = ' valign-' . $sc_settings['verticalalign'];
		}

		$items = '';
		$items = $shared->get_testimonials( $queryargs, 'shortcode', $postLimiter, $limiterIDs, $showFeaturedImages, $smartQuotes, $taxSlug );

		echo '<!-- // ********** SOCIAL PROOF SLIDER ********** // -->';
		echo '<section id="_socialproofslider-shortcode" class="widget widget__socialproofslider ' . $sc_settings['animationStyle'] . ' paddingoverride-'.$sc_settings['doPaddingOverride'].' ">';
		echo '<div class="widget-wrap">';
		echo '<div class="social-proof-slider-wrap ' . $sc_settings['textalign'] . $alignStr . '">';

		echo $items;

		echo '</div><!-- // .social-proof-slider-wrap // -->';

		//* Output styles
		echo '<style>'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap{ background-color:' . $sc_settings['bgcolor'] . '; '.$contentPaddingStr.' }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img { '.$imgMarginStr.' }'."\n";
		if ( $sc_settings['imageBorderRadius'] === 0 ){
			echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img img{ border-radius: 0; }'."\n";
		} else {
			echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img img{ border-radius:' . $sc_settings['imageBorderRadius'] . 'px; }'."\n";
		}

		if ( $showImageBorder == '1' ) {

			echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img img { border: ' . $imageBorderThickness . ' solid ' . $imageBorderColor . ' !important; padding: ' . $imageBorderPadding . ' }'."\n";

		}

		echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item .testimonial-text{ color:' . $sc_settings['textColor'] . '; '.$textPaddingStr.' }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item .testimonial-text .quote { '.$quoteMarginStr.' }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap .slick-arrow span { color:' . $sc_settings['arrowColor'] . '; }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap .slick-arrow:hover span{ color:' . $sc_settings['arrowHoverColor'] . '; }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap ul.slick-dots{ '.$dotsMarginStr.' }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap ul.slick-dots li button::before, #_socialproofslider-shortcode .slick-dots li.slick-active button:before { color:' . $sc_settings['dotsColor'] . ' }'."\n";
		echo '</style>'."\n";

		//* CUSTOM JS
		$prev_button = '';
		$next_button = '';
		$thisWidgetJS = '<script type="text/javascript">';
		$thisWidgetJS .= 'jQuery(document).ready(function($) {'."\n";
		$thisWidgetJS .= '	$("#_socialproofslider-shortcode .social-proof-slider-wrap").not(".slick-initialized").slick({'."\n";
		$thisWidgetJS .= '		autoplay: ' . $sc_settings['doAutoPlay'] . ','."\n";
		if ( $sc_settings['doAutoPlay'] == 'true' ) {
			$thisWidgetJS .= '		autoplaySpeed: ' . $sc_settings['autoplaySpeed'] . ','."\n";
		}
		$thisWidgetJS .= '		adaptiveHeight: '. $sc_settings['doAutoHeight'] . ','."\n";
		$thisWidgetJS .= '		fade: ' . $sc_settings['doFade'] . ','."\n";
		$thisWidgetJS .= '		arrows: ' . $sc_settings['showArrows'] . ','."\n";
		if ( $sc_settings['showArrows'] == 'true' ) {

			$prev_button = '<button type="button" class="slick-prev"><span class="fa ' . $sc_settings['arrowLeft'] . '"></span></button>';
			$next_button = '<button type="button" class="slick-next"><span class="fa ' . $sc_settings['arrowRight'] . '"></span></button>';

			$thisWidgetJS .= '		prevArrow: \'' . $prev_button . '\','."\n";
			$thisWidgetJS .= '		nextArrow: \'' . $next_button . '\','."\n";
		}
		$thisWidgetJS .= '		dots: ' . $sc_settings['showDots'] . ','."\n";
		$thisWidgetJS .= '		infinite: true,'."\n";
		// $thisWidgetJS .= '		adaptiveHeight: true'."\n";
		$thisWidgetJS .= '	});'."\n";
		// $thisWidgetJS .= 'console.log("I\'m loaded!");'."\n";
		$thisWidgetJS .= '});'."\n";
		$thisWidgetJS .= '</script>'."\n";
		echo  $thisWidgetJS;

		echo '</div><!-- // .widget-wrap // -->';
		echo '</section>';
		echo '<!-- // ********** // END SOCIAL PROOF SLIDER // ********** // -->';

		$output = ob_get_contents();

		ob_end_clean();

		return $output;

	} // spslider_shortcode()

	/**
	 * Processes shortcode 'spslider-manual'
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function spslider_manual_shortcode( $atts = array(), $content = null ) {

		ob_start();

		$shared = new Social_Proof_Slider_Shared( $this->plugin_name, $this->version );

		// Get Shortcode Settings
		$sc_settings = $shared->get_shortcode_settings();

		$showImageBorder = $sc_settings['showImageBorder'];
		$imageBorderColor = $sc_settings['imageBorderColor'];
		$imageBorderThickness = $sc_settings['imageBorderThickness'];
		$imageBorderPadding = $sc_settings['imageBorderPadding'];

		$smartQuotes = $sc_settings['surroundWithQuotes'];

		$doPaddingOverride = $sc_settings['doPaddingOverride'];
		$contentPaddingTop = $sc_settings['contentPaddingTop'];
		$contentPaddingBottom = $sc_settings['contentPaddingBottom'];
		$featImgMarginTop = $sc_settings['featImgMarginTop'];
		$featImgMarginBottom = $sc_settings['featImgMarginBottom'];
		$textPaddingTop = $sc_settings['textPaddingTop'];
		$textPaddingBottom = $sc_settings['textPaddingBottom'];
		$quoteMarginBottom = $sc_settings['quoteMarginBottom'];
		$dotsMarginTop = $sc_settings['dotsMarginTop'];

		$contentPaddingStr = ''; // default
		$imgMarginStr = ''; // default
		$textPaddingStr = ''; // default
		$quoteMarginStr = ''; // default
		$dotsMarginStr = ''; // default

		if ( $doPaddingOverride == 'true' ) {
			$contentPaddingStr = "padding-top: ".$contentPaddingTop."; padding-bottom: ".$contentPaddingBottom.";";
			$imgMarginStr = "margin-top:".$featImgMarginTop."; margin-bottom:".$featImgMarginBottom.";";
			$textPaddingStr = "padding-top: ".$textPaddingTop."; padding-bottom: ".$textPaddingBottom.";";
			$quoteMarginStr = "margin-bottom: ".$quoteMarginBottom.";";
			$dotsMarginStr = "margin-top: ".$dotsMarginTop.";";
		}

		$alignStr = '';	// default
		if ( $sc_settings['doAutoHeight'] != 'true' ) {
			$alignStr = ' valign-' . $sc_settings['verticalalign'];
		}

		echo '<!-- // ********** SOCIAL PROOF SLIDER ********** // -->';
		echo '<section id="_socialproofslider-shortcode" class="widget widget__socialproofslider ' . $sc_settings['animationStyle'] . ' paddingoverride-'.$sc_settings['doPaddingOverride'].' ">';
		echo '<div class="widget-wrap">';
		echo '<div class="social-proof-slider-wrap ' . $sc_settings['textalign'] . $alignStr . '">';

		// Get sub-items inside this shortcode's content
		$content = do_shortcode($content);
		echo $content;

		echo '</div><!-- // .social-proof-slider-wrap // -->';

		//* Output styles
		echo '<style>'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap{ background-color:' . $sc_settings['bgcolor'] . '; '.$contentPaddingStr.' }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img { '.$imgMarginStr.' }'."\n";
		if ( $sc_settings['imageBorderRadius'] === 0 ){
			echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img img{ border-radius: 0; }'."\n";
		} else {
			echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img img{ border-radius:' . $sc_settings['imageBorderRadius'] . '; }'."\n";
		}

		if ( $showImageBorder == '1' ) {

			echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img img { border: ' . $imageBorderThickness . ' solid ' . $imageBorderColor . ' !important; padding: ' . $imageBorderPadding . ' }'."\n";

		}

		echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item .testimonial-text{ color:' . $sc_settings['textColor'] . '; '.$textPaddingStr.' }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap .testimonial-item .testimonial-text .quote { '.$quoteMarginStr.' }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap .slick-arrow span { color:' . $sc_settings['arrowColor'] . '; }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap .slick-arrow:hover span{ color:' . $sc_settings['arrowHoverColor'] . '; }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap ul.slick-dots{ '.$dotsMarginStr.' }'."\n";
		echo '#_socialproofslider-shortcode .social-proof-slider-wrap ul.slick-dots li button::before, #_socialproofslider-shortcode .slick-dots li.slick-active button:before { color:' . $sc_settings['dotsColor'] . ' }'."\n";
		echo '</style>'."\n";

		//* CUSTOM JS
		$prev_button = '';
		$next_button = '';
		$thisWidgetJS = '<script type="text/javascript">';
		$thisWidgetJS .= 'jQuery(document).ready(function($) {'."\n";
		$thisWidgetJS .= '	$("#_socialproofslider-shortcode .social-proof-slider-wrap").not(".slick-initialized").slick({'."\n";
		$thisWidgetJS .= '		autoplay: ' . $sc_settings['doAutoPlay'] . ','."\n";
		if ( $sc_settings['doAutoPlay'] == 'true' ) {
			$thisWidgetJS .= '		autoplaySpeed: ' . $sc_settings['autoplaySpeed'] . ','."\n";
		}
		$thisWidgetJS .= '		adaptiveHeight: '. $sc_settings['doAutoHeight'] . ','."\n";
		$thisWidgetJS .= '		fade: ' . $sc_settings['doFade'] . ','."\n";
		$thisWidgetJS .= '		arrows: ' . $sc_settings['showArrows'] . ','."\n";
		if ( $sc_settings['showArrows'] == 'true' ) {

			$prev_button = '<button type="button" class="slick-prev"><span class="fa ' . $sc_settings['arrowLeft'] . '"></span></button>';
			$next_button = '<button type="button" class="slick-next"><span class="fa ' . $sc_settings['arrowRight'] . '"></span></button>';

			$thisWidgetJS .= '		prevArrow: \'' . $prev_button . '\','."\n";
			$thisWidgetJS .= '		nextArrow: \'' . $next_button . '\','."\n";
		}
		$thisWidgetJS .= '		dots: ' . $sc_settings['showDots'] . ','."\n";
		$thisWidgetJS .= '		infinite: true,'."\n";
		// $thisWidgetJS .= '		adaptiveHeight: true'."\n";
		$thisWidgetJS .= '	});'."\n";
		$thisWidgetJS .= '});'."\n";
		$thisWidgetJS .= '</script>'."\n";
		echo  $thisWidgetJS;

		echo '</div><!-- // .widget-wrap // -->';
		echo '</section>';
		echo '<!-- // ********** // END SOCIAL PROOF SLIDER // ********** // -->';

		$output = ob_get_contents();

		ob_end_clean();

		return $output;

	} // spslider_manual_shortcode()

	/**
	 * Processes shortcode 'spslider-item'
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function spslider_item_shortcode( $atts = array(), $content = null ) {

		ob_start();

		echo '<div class="testimonial-item">';

		echo $content;

		echo '</div>';

		$output = ob_get_contents();

		ob_end_clean();

		return $output;

	} // spslider_item_shortcode()

	/**
	 * Processes shortcode for Block
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function spslider_block_shortcode( $atts = array() ) {

		ob_start();

		$requestor = 'block';

		// Declare defaults.
		$defaults['blockalign'] = 'center';
		$defaults['sortpostsby'] = 'DESC';
		$defaults['filterposts'] = false;
		$defaults['filtershowhide'] = 'show';
		$defaults['filterby'] = 'postid';
		$defaults['postidorcatslug'] = '';
		$defaults['showfeaturedimages'] = false;
		$defaults['showimageborder'] = false;
		$defaults['showquotemarks'] = false;
		$defaults['autoplay'] = true;
		$defaults['displaytime'] = 3;
		$defaults['animationstyle'] = 'fade';
		$defaults['showarrows'] = false;
		$defaults['arrowstyle'] = 'angle';
		$defaults['showdots'] = false;
		$defaults['adaptiveheight'] = false;

		if ( !function_exists( 'getAttsOrDefault' ) ) {

			/* Function to assign default value if attr is empty
			 *
			 * string	$propName	name of the property to check
			 * return	$returnVal	return value
			 */
			function getAttsOrDefault( $propName, $atts = array(), $defaults = array() ) {

				$returnVal1 = ( array_key_exists($propName, $atts) && !empty( $atts[$propName] ) )
			         ? $atts[$propName]
			         : 'non-existent or empty in "atts"';

				$returnVal2 = ( array_key_exists($propName, $defaults) && !empty( $defaults[$propName] ) )
				     ? $defaults[$propName]
				     : 'non-existent or empty in "defaults"';

				// if ( ! empty( $atts[$propName] ) ) {
				// 	$returnVal = $atts[$propName];
				// }else {
				// 	$returnVal = $defaults[$propName];
				// }

				return " <br> " . $returnVal1 . " <br> " . $returnVal2;
			}

		}

		// Get block Text Alignment setting.
		$block_textalign = ( isset( $atts['textalign'] ) ) ? $atts['textalign'] : $defaults['blockalign'];

		// ---------------------------------------------------------------------

		// POST SETTINGS.

		// Get the dropdown menu setting for 'Sort Posts by'.
		$posts_sortby = ( isset( $atts['sortpostsby'] ) ) ? $atts['sortpostsby'] : $defaults['sortpostsby'];

		// Get the setting for 'Filter Posts by'.
		$posts_filterposts = ( isset( $atts['filterposts'] ) ) ? $atts['filterposts'] : $defaults['filterposts'];

		// Get the setting for 'Show or Hide These Testimonials'.
		$posts_filtershowhide = ( isset( $atts['filtershowhide'] ) ) ? $atts['filtershowhide'] : $defaults['filtershowhide'];

		// Get the setting for Filter by: 'Post ID' or 'Category'.
		$posts_filterby = ( isset( $atts['filterby'] ) ) ? $atts['filterby'] : $defaults['filterby'];

		// Get the setting for 'Post IDs'.
		$posts_postids = ( isset( $atts['postids'] ) ) ? $atts['postids'] : $defaults['postids'];

		// Get the setting for 'Category Slug'.
		$posts_catslug = ( isset( $atts['catslug'] ) ) ? $atts['catslug'] : $defaults['catslug'];

		// Get the setting for 'Show Featured Images'.
		$posts_showfeaturedimages = ( isset( $atts['showfeaturedimages'] ) ) ? 1 : $defaults['showfeaturedimages'];

		// Get the setting for 'Show Image Border'.
		$posts_showimageborder = ( isset( $atts['showimageborder'] ) ) ? $atts['showimageborder'] : $defaults['showimageborder'];

		// Get the setting for 'Image Border Radius'.
		$posts_imageborderradius = ( isset( $atts['imageborderradius'] ) ) ? $atts['imageborderradius'] : 0;

		// Get the setting for 'Image Border Size'.
		$posts_imagebordersize = ( isset( $atts['imagebordersize'] ) ) ? $atts['imagebordersize'] : 0;

		// Get the setting for 'Show Quote Marks'.
		$posts_showquotes = ( isset( $atts['showquotemarks'] ) ) ? $atts['showquotemarks'] : $defaults['showquotemarks'];

		// ---------------------------------------------------------------------

		// SLIDER SETTINGS.

		// Get setting for 'Autoplay'.
		$slider_autoplay = ( isset( $atts['autoplay'] ) ) ? $atts['autoplay'] : $defaults['autoplay'];

		// Get setting for 'Display Time'.
		$slider_displaytime = ( isset( $atts['displaytime'] ) ) ? $atts['displaytime'] : $defaults['displaytime'];

		// Get setting for 'Animation Style'.
		$slider_animationstyle = ( isset( $atts['animationstyle'] ) ) ? $atts['animationstyle'] : $defaults['animationstyle'];

		// Get setting for 'Show Arrows'.
		$slider_showarrows = ( isset( $atts['showarrows'] ) ) ? $atts['showarrows'] : $defaults['showarrows'];

		// Get setting for 'Arrow Style'.
		$slider_arrowstyle = ( isset( $atts['arrowstyle'] ) ) ? $atts['arrowstyle'] : $defaults['arrowstyle'];

		// Get setting for 'Show Dots'.
		$slider_showdots = ( isset( $atts['showdots'] ) ) ? $atts['showdots'] : $defaults['showdots'];

		// Get setting for 'Adaptive Height'.
		$slider_adaptiveheight = ( isset( $atts['adaptiveheight'] ) ) ? $atts['adaptiveheight'] : $defaults['adaptiveheight'];

		// Get setting for 'Vertical Align'.
		$slider_verticalalign = ( isset( $atts['verticalalign'] ) ) ? $atts['verticalalign'] : $defaults['verticalalign'];

		// ---------------------------------------------------------------------

		// MARGIN & PADDING SETTINGS.

		$paddingunit = $atts['paddingunit'];
		$paddingsync = $atts['paddingsync'];
		$contentPaddingStr = '';
		if ( $paddingsync === "true" || $paddingsync === "1" ) {
			$contentPaddingStr = 'padding: ' . $atts['padding'] . $paddingunit . ';';
		} else {
			$contentPaddingStr = 'padding-top: ' . $atts['paddingtop'] . $paddingunit . '; ';
			$contentPaddingStr .= 'padding-right: ' . $atts['paddingright'] . $paddingunit . '; ';
			$contentPaddingStr .= 'padding-bottom: ' . $atts['paddingbottom'] . $paddingunit . '; ';
			$contentPaddingStr .= 'padding-left: ' . $atts['paddingleft'] . $paddingunit . ';';
		}

		$padding_imageborderpadding = $atts['imageborderpadding'];
		if ( empty( $padding_imageborderpadding ) ) {
			$padding_imageborderpadding = 0;
		}
		/*
		Image Border Padding - Range: 0-50 | Default: 4px
		Image Margin Bottom - Range: 0-100 | Default: 20px
		Testimonial Text Container Margin Bottom - Range: 0-100 | Default: 30px
		Quote Text Margin Bottom - Range: 0-100 | Default: 30px
		Dots Margin Top - Range: 0-100 | Default: 10px
		*/

		// ---------------------------------------------------------------------

		// COLOR SETTINGS.

		$style_bgcolor = ( isset( $atts['bgcolor'] ) ) ? $atts['bgcolor'] : '';
		$style_testimonialcolor = ( isset( $atts['testimonialtextcolor'] ) ) ? $atts['testimonialtextcolor'] : '';
		$style_authornamecolor = ( isset( $atts['authornamecolor'] ) ) ? $atts['authornamecolor'] : '';
		$style_authortitlecolor = ( isset( $atts['authortitlecolor'] ) ) ? $atts['authortitlecolor'] : '';
		$style_arrowscolor = ( isset( $atts['arrowscolor'] ) ) ? $atts['arrowscolor'] : '';
		$style_arrowshovercolor = ( isset( $atts['arrowshovercolor'] ) ) ? $atts['arrowshovercolor'] : '';
		$style_dotscolor = ( isset( $atts['dotscolor'] ) ) ? $atts['dotscolor'] : '';
		$style_dotshovercolor = ( isset( $atts['dotshovercolor'] ) ) ? $atts['dotshovercolor'] : '';
		$style_imagebordercolor = ( isset( $atts['imagebordercolor'] ) ) ? $atts['imagebordercolor'] : '';

		$shared = new Social_Proof_Slider_Shared( $this->plugin_name, $this->version );

		// Get Shortcode Settings.
		$sc_settings = $shared->get_shortcode_settings();

		// Get Attributes inside the manually-entered Shortcode.
		$sc_atts = shortcode_atts( $defaults, $atts, 'social-proof-slider' );

		// Determine ORDERBY and ORDER args.
		$sortbysetting = $posts_sortby;
		if ( $sortbysetting == "RAND" ) {
			$queryargs = array(
				'orderby' => 'rand',
			);
		} else {
			$queryargs = array(
				'order' => $posts_sortby,
				'orderby' => 'ID',
			);
		}

		$posts_filter_ids = '';
		$posts_filter_taxslug = '';
		$posts_filter_hide = '';

		// Filter posts.
		if ( $posts_filterposts === "true" || $posts_filterposts === "1" ) {

			// Determine whether to show or hide.
			$filtershowhidesetting = $posts_filtershowhide;

			// Hide posts.
			if ( 'hide' === $filtershowhidesetting ) {
				$posts_filter_hide = '1';
			}

			// Show posts.
			if ( 'show' === $filtershowhidesetting ) {
				$posts_filter_hide = '';
			}

			// Determine whether to limit by Post ID or by Category Slug.
			$filterbysetting = $posts_filterby;

			// Use Post IDs.
			if ( $filterbysetting == "postid" ) {
				$posts_filter_ids = $posts_postids;
			}

			// Use category.
			if ( $filterbysetting == "cat" ) {
				$posts_filter_taxslug = $posts_catslug;
			}

		}

		$showImageBorder = $posts_showimageborder;
		$imageBorderColor = $style_imagebordercolor;
		$imageBorderThickness = $posts_imagebordersize;
		$imageBorderPadding = $padding_imageborderpadding;

		// $doPaddingOverride = $sc_settings['doPaddingOverride'];
		// $contentPaddingTop = $sc_settings['contentPaddingTop'];
		// $contentPaddingBottom = $sc_settings['contentPaddingBottom'];
		// $featImgMarginTop = $sc_settings['featImgMarginTop'];
		// $featImgMarginBottom = $sc_settings['featImgMarginBottom'];
		// $textPaddingTop = $sc_settings['textPaddingTop'];
		// $textPaddingBottom = $sc_settings['textPaddingBottom'];
		// $quoteMarginBottom = $sc_settings['quoteMarginBottom'];
		// $dotsMarginTop = $sc_settings['dotsMarginTop'];

		// $contentPaddingStr = 'padding: 50px;'; // default
		$imgMarginStr = ''; // default
		$textPaddingStr = ''; // default
		$quoteMarginStr = ''; // default
		$dotsMarginStr = ''; // default

		// if ( $doPaddingOverride == 'true' ) {
		// 	$contentPaddingStr = "padding-top: ".$contentPaddingTop."; padding-bottom: ".$contentPaddingBottom.";";
		// 	$imgMarginStr = "margin-top:".$featImgMarginTop."; margin-bottom:".$featImgMarginBottom.";";
		// 	$textPaddingStr = "padding-top: ".$textPaddingTop."; padding-bottom: ".$textPaddingBottom.";";
		// 	$quoteMarginStr = "margin-bottom: ".$quoteMarginBottom.";";
		// 	$dotsMarginStr = "margin-top: ".$dotsMarginTop.";";
		// }

		// Create 'items' object with all testimonials.
		$items = '';
		$items = $shared->get_testimonials( $queryargs, 'shortcode', $posts_filter_hide, $posts_filter_ids, $posts_showfeaturedimages, $posts_showquotes, $posts_filter_taxslug, $requestor );

		// Generate a Unique ID for this block.
		$uniqueID = uniqid('spslider_block_');

		// Build the data attr string for Slick settings.
		$slickData = "'{";

		// Define Autoplay setting.
		if ( $slider_autoplay === "true" || $slider_autoplay === "1" ) {
			$slickData .= '"autoplay":true';
			// Autoplay - Display Time
			if ( ! empty( $slider_displaytime ) ) {
				$displayTimeNumber = $slider_displaytime * 1000;
				$slickData .= ',"autoplaySpeed":'.$displayTimeNumber;
			}
		} else {
			$slickData .= '"autoplay":false';
		}

		$slickData .= ",";

		// Define the Animation Style.
		if ( $slider_animationstyle === "fade" ) {
			$slickData .= '"fade":true';
		} else {
			$slickData .= '"fade":false';
		}

		$slickData .= ",";

		// Show the Arrows.
		if ( $slider_showarrows === "true" || $slider_showarrows === "1" ) {
			$slickData .= '"arrows":true';
		} else {
			$slickData .= '"arrows":false';
		}

		$slickData .= ",";

		// Define the Arrows Style.
		$slickData .= '"prevArrow":"#'.$uniqueID.'-arrow-left",';
		$slickData .= '"nextArrow":"#'.$uniqueID.'-arrow-right"';

		$slickData .= ",";

		// Show the Dots.
		if ( $slider_showdots === "true" || $slider_showdots === "1" ) {
			$slickData .= '"dots":true';
		} else {
			$slickData .= '"dots":false';
		}

		$slickData .= ",";

		// Use the Adaptive Height setting.
		if ( $slider_adaptiveheight === "true" || $slider_adaptiveheight === "1" ) {
			$slickData .= '"adaptiveHeight":true';
		} else {
			$slickData .= '"adaptiveHeight":false';
		}

		// End Slick JS settings.
		$slickData .= "}'";

		// Assign text alignment.
		$textAlignStr = "align_" . $block_textalign;

		// Assign vertical alignment.
		$alignStr = "valign-".$atts['verticalalign'];

		// Start output of the Slider.
		echo '<!-- // ********** SOCIAL PROOF SLIDER ********** // -->';
		echo '<section id="' . $uniqueID . '" class="block wp-block-socialproofslider ' . $slider_animationstyle . ' ">';
		echo '<div class="widget-wrap">';
		if ( $slider_showarrows === "true" || $slider_showarrows === "1" ) {
			echo '<button type="button" id="'.$uniqueID.'-arrow-left" class="slick-prev"><span class="fa fa-' . $slider_arrowstyle . '-left"></span></button>';
		}
		echo '<div class="social-proof-slider-wrap ' . $textAlignStr . ' ' . $alignStr . '" data-slick='.$slickData.'>';
		echo $items;
		echo '</div><!-- // .social-proof-slider-wrap // -->';
		if ( $slider_showarrows === "true" || $slider_showarrows === "1" ) {
			echo '<button type="button" id="'.$uniqueID.'-arrow-right" class="slick-next"><span class="fa fa-' . $slider_arrowstyle . '-right"></span></button>';
		}

		// Output CSS styles.
		$uniqueID = '#' . $uniqueID;
		echo '<style>' . "\n";

		// Assign the BG Color and Container padding.
		echo $uniqueID . ' .social-proof-slider-wrap { background-color:' . $style_bgcolor . '; '.$contentPaddingStr.' }'."\n";

		// Assign the Arrows Color.
		if ( ! $style_arrowscolor ) {
			// Use default color.
			$style_arrowscolor = '#000';
		}
		echo $uniqueID . '.wp-block-socialproofslider .widget-wrap > button.slick-arrow { color:' . $style_arrowscolor . ' !important; }'."\n";

		// Assign Arrows Hover Color.
		if ( ! $style_arrowshovercolor ) {
			// Use default = opacity 50%.
			echo $uniqueID . '.wp-block-socialproofslider .widget-wrap > button.slick-arrow:hover span{ opacity: 0.5; }'."\n";
		} else {
			// Use selected color.
			echo $uniqueID . '.wp-block-socialproofslider .widget-wrap > button.slick-arrow:hover span{ color:' . $style_arrowshovercolor . '; }'."\n";
		}

		// Assign the Dots margin.
		echo $uniqueID . ' .social-proof-slider-wrap ul.slick-dots{ '.$dotsMarginStr.' }'."\n";

		// Assign the Dots Color.
		if ( ! $style_dotscolor ) {
			$style_dotscolor = '#000';
		}
		echo $uniqueID . '.wp-block-socialproofslider .social-proof-slider-wrap .slick-dots li button { color:' . $style_dotscolor . ' !important; }'."\n";

		// Assign the Dots Hover Color.
		if ( ! $style_dotshovercolor ) {
			// Use default = opacity 50%.
			echo $uniqueID . '.wp-block-socialproofslider .social-proof-slider-wrap .slick-dots li button:hover { opacity: 0.5 !important; }'."\n";
		} else {
			// Use selected color.
			echo $uniqueID . '.wp-block-socialproofslider .social-proof-slider-wrap .slick-dots li button:hover { color:' . $style_dotshovercolor . ' !important; }'."\n";
		}

		// Assign the Featured Image margin.
		echo $uniqueID . ' .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img { '.$imgMarginStr.' }'."\n";

		// Assign the Featured Image border radius.
		if ( empty( $posts_imageborderradius ) || ( $posts_imageborderradius === 0 ) || ( $posts_imageborderradius === false ) ) {
			echo $uniqueID . ' .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img img{ border-radius: 0; }'."\n";
		} else {
			echo $uniqueID . ' .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img img{ border-radius: ' . $posts_imageborderradius . '%; }'."\n";
		}

		// Show the Featured Image Border.
		if ( $showImageBorder === "true" || $showImageBorder === "1" ) {
			echo $uniqueID . ' .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img img { border: ' . $imageBorderThickness . 'px solid ' . $imageBorderColor . ' !important; padding: ' . $imageBorderPadding . 'px; }'."\n";
		}

		// Assign the Text padding and margin.
		echo $uniqueID . ' .social-proof-slider-wrap .testimonial-item .testimonial-text{ '.$textPaddingStr.' }'."\n";
		echo $uniqueID . ' .social-proof-slider-wrap .testimonial-item .testimonial-text .quote { '.$quoteMarginStr.' }'."\n";

		// Assign the Text Colors.
		if ( ! empty( $style_testimonialcolor ) ) {
			echo $uniqueID . ' .social-proof-slider-wrap .testimonial-item .testimonial-text .quote p { color:' . $style_testimonialcolor . '; }'."\n";
		}
		if ( ! empty( $style_authornamecolor ) ) {
			echo $uniqueID . ' .social-proof-slider-wrap .testimonial-item .testimonial-text .author > .author-name { color:' . $style_authornamecolor . '; }'."\n";
		}
		if ( ! empty( $style_authortitlecolor ) ) {
			echo $uniqueID . ' .social-proof-slider-wrap .testimonial-item .testimonial-text .author > .author-title { color:' . $style_authortitlecolor . '; }'."\n";
		}

		// End styles.
		echo '</style>'."\n";

		// End Slides wrapper.
		echo '</div><!-- // .widget-wrap // -->';

		// End Slider.
		echo '</section>';
		echo '<!-- // ********** // END SOCIAL PROOF SLIDER // ********** // -->';

		// Put contents into a var.
		$output = ob_get_contents();

		// Clear the memory.
		ob_end_clean();

		// Output everything.
		return $output;

	} // spslider_block_shortcode()

	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {

		add_shortcode( 'social-proof-slider', array( $this, 'spslider_shortcode' ) );
		add_shortcode( 'spslider-item', array( $this, 'spslider_item_shortcode' ) );
		add_shortcode( 'spslider-manual', array( $this, 'spslider_manual_shortcode' ) );
		add_shortcode( 'spslider-block', array( $this, 'spslider_block_shortcode' ) );

	} // register_shortcodes()

	/**
	 * Registers Gutenberg Block
	 *
	 * @since 	2.1.2
	 * @access 	public
	 */
	public function spslider_register_gutenberg_block() {

		// Register block script
		wp_register_script(
			'spslider-block',
			plugins_url('../blocks/dist/blocks.build.js', __FILE__),
			array('wp-blocks', 'wp-element', 'wp-editor')
		);

		// Register block's base CSS
		wp_register_style(
			'spslider-block-style',
			plugins_url( '../blocks/dist/blocks.style.build.css', __FILE__ ),
			array( 'wp-blocks' )
		);

		// Register block's editor CSS
		wp_register_style(
			'spslider-block-edit-style',
			plugins_url('../blocks/dist/blocks.editor.build.css', __FILE__),
			array( 'wp-edit-blocks' )
		);

		// Render callback
		function spslider_render_gutenberg_block($atts) {

			$settings = '';

			// Block settings
			if ( $atts['textalign'] ) {
				$settings .= 'textalign="' . $atts['textalign'] . '" ';
			}

			// Post settings
			$settings .= 'sortpostsby="' . $atts['sortpostsby'] . '" ';
			$settings .= 'filterposts="' . $atts['filterposts'] . '" ';
			$settings .= 'filtershowhide="' . $atts['filtershowhide'] . '" ';
			$settings .= 'filterby="' . $atts['filterby'] . '" ';
			$settings .= 'postids="' . $atts['postids'] . '" ';
			$settings .= 'catslug="' . $atts['catslug'] . '" ';
			$settings .= 'showfeaturedimages="' . $atts['showfeaturedimages'] . '" ';
			$settings .= 'showimageborder="' . $atts['showimageborder'] . '" ';
			$settings .= 'imageborderradius="' . $atts['imageborderradius'] . '" ';
			$settings .= 'imagebordersize="' . $atts['imagebordersize'] . '" ';
			$settings .= 'showquotemarks="' . $atts['showquotemarks'] . '" ';

			// Slider settings
			$settings .= 'autoplay="' . $atts['autoplay'] . '" ';
			$settings .= 'displaytime="' . $atts['displaytime'] . '" ';
			$settings .= 'animationstyle="' . $atts['animationstyle'] . '" ';
			$settings .= 'showarrows="' . $atts['showarrows'] . '" ';
			$settings .= 'arrowstyle="' . $atts['arrowstyle'] . '" ';
			$settings .= 'showdots="' . $atts['showdots'] . '" ';
			$settings .= 'adaptiveheight="' . $atts['adaptiveheight'] . '" ';
			$settings .= 'verticalalign="' . $atts['verticalalign'] . '" ';

			// Margin & Padding
			$settings .= 'paddingunit="' . $atts['paddingunit'] . '" ';
			$settings .= 'paddingsync="' . $atts['paddingsync'] . '" ';
			$settings .= 'padding="' . $atts['padding'] . '" ';
			$settings .= 'paddingtop="' . $atts['paddingtop'] . '" ';
			$settings .= 'paddingright="' . $atts['paddingright'] . '" ';
			$settings .= 'paddingbottom="' . $atts['paddingbottom'] . '" ';
			$settings .= 'paddingleft="' . $atts['paddingleft'] . '" ';
			$settings .= 'imageborderpadding="' . $atts['imageborderpadding'] . '" ';

			// Colors
			if ( $atts['bgcolor'] ) {
				$settings .= 'bgcolor="' . $atts['bgcolor'] . '" ';
			}

			if ( $atts['arrowscolor'] ) {
				$settings .= 'arrowscolor="' . $atts['arrowscolor'] . '" ';
			}

			if ( $atts['arrowshovercolor'] ) {
				$settings .= 'arrowshovercolor="' . $atts['arrowshovercolor'] . '" ';
			}

			if ( $atts['dotscolor'] ) {
				$settings .= 'dotscolor="' . $atts['dotscolor'] . '" ';
			}

			if ( $atts['dotshovercolor'] ) {
				$settings .= 'dotshovercolor="' . $atts['dotshovercolor'] . '" ';
			}

			if ( $atts['imagebordercolor'] ) {
				$settings .= 'imagebordercolor="' . $atts['imagebordercolor'] . '" ';
			}

			if ( $atts['testimonialtextcolor'] ) {
				$settings .= 'testimonialtextcolor="' . $atts['testimonialtextcolor'] . '" ';
			}

			if ( $atts['authornamecolor'] ) {
				$settings .= 'authornamecolor="' . $atts['authornamecolor'] . '" ';
			}

			if ( $atts['authortitlecolor'] ) {
				$settings .= 'authortitlecolor="' . $atts['authortitlecolor'] . '" ';
			}

			return '' . do_shortcode('[spslider-block ' . $settings . ']');

	 	}

		// Enqueue the Editor script
		register_block_type('social-proof-slider/main', array(
			'editor_script' => 'spslider-block',
			'editor_style' => 'spslider-block-edit-style',
			'render_callback' => 'spslider_render_gutenberg_block',
			'attributes' => [
				'textalign' => [
					'type' => 'string',
					'default' => ''
				],
				'sortpostsby' => [
					'type' => 'string',
					'default' => 'DESC'
				],
				'filterposts' => [
					'type' => 'boolean',
					'default' => false
				],
				'filtershowhide' => [
					'type' => 'string',
					'default' => 'show'
				],
				'filterby' => [
					'type' => 'string',
					'default' => 'postid'
				],
				'postids' => [
					'type' => 'string',
					'default' => ''
				],
				'catslug' => [
					'type' => 'string',
					'default' => ''
				],
				'showfeaturedimages' => [
					'type' => 'boolean',
					'default' => false
				],
				'showimageborder' => [
					'type' => 'boolean',
					'default' => false
				],
				'imageborderradius' => [
					'type' => 'number',
					'default' => 25
				],
				'imagebordersize' => [
					'type' => 'number',
					'default' => 5
				],
				'showquotemarks' => [
					'type' => 'boolean',
					'default' => false
				],
				'autoplay' => [
					'type' => 'boolean',
					'default' => true
				],
				'displaytime' => [
					'type' => 'number',
					'default' => 3
				],
				'animationstyle' => [
					'type' => 'string',
					'default' => 'fade'
				],
				'showarrows' => [
					'type' => 'boolean',
					'default' => false
				],
				'arrowstyle' => [
					'type' => 'string',
					'default' => 'angle'
				],
				'showdots' => [
					'type' => 'boolean',
					'default' => false
				],
				'adaptiveheight' => [
					'type' => 'boolean',
					'default' => false
				],
				'verticalalign' => [
					'type' => 'string',
					'default' => 'align_middle'
				],
				'paddingsync' => [
					'type' => 'boolean',
					'default' => true
				],
				'paddingunit' => [
					'type' => 'string',
					'default' => 'px'
				],
				'padding' => [
					'type' => 'number',
					'default' => 50
				],
				'paddingtop' => [
					'type' => 'number',
					'default' => 0
				],
				'paddingright' => [
					'type' => 'number',
					'default' => 0
				],
				'paddingbottom' => [
					'type' => 'number',
					'default' => 0
				],
				'paddingleft' => [
					'type' => 'number',
					'default' => 0
				],
				'imageborderpadding' => [
					'type' => 'number',
					'default' => 4
				],
				'bgcolor' => [
					'type' => 'string',
					'default' => ''
				],
				'arrowscolor' => [
					'type' => 'string',
					'default' => ''
				],
				'arrowshovercolor' => [
					'type' => 'string',
					'default' => ''
				],
				'dotscolor' => [
					'type' => 'string',
					'default' => ''
				],
				'dotshovercolor' => [
					'type' => 'string',
					'default' => ''
				],
				'imagebordercolor' => [
					'type' => 'string',
					'default' => ''
				],
				'testimonialtextcolor' => [
					'type' => 'string',
					'default' => ''
				],
				'authornamecolor' => [
					'type' => 'string',
					'default' => ''
				],
				'authortitlecolor' => [
					'type' => 'string',
					'default' => ''
				],
			]
		));

	}

}
