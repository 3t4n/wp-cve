<?php

/**
 * The public & admin-facing shared functionality of the plugin.
 *
 * @link       https://thebrandiD.com
 * @since      2.0.0
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/includes
 */

/**
 * The public & admin-facing shared functionality of the plugin.
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/includes
 */

 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) { exit; }

class Social_Proof_Slider_Shared {

	/**
	 * The ID of this plugin.
	 *
	 * @since 		2.0.0
	 * @access 		private
	 * @var 		string 			$plugin_name 		The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 		2.0.0
	 * @access 		private
	 * @var 		string 			$version 			The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		2.0.0
	 * @param 		string 			$Now_Hiring 		The name of this plugin.
	 * @param 		string 			$version 			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Flushes widget cache
	 *
	 * @since 		2.0.0
	 * @access 		public
	 * @param 		int 		$post_id 		The post ID
	 * @return 		void
	 */
	public function flush_widget_cache( $post_id ) {

		if ( wp_is_post_revision( $post_id ) ) { return; }

		$post = get_post( $post_id );

		if ( 'socialproofslider' == $post->post_type ) {

			wp_cache_delete( $this->plugin_name, 'widget' );

		}

	} // flush_widget_cache()

	/**
	 * Returns a post object of 'socialproofslider' posts
	 *
	 * @param 	array 		$params 		An array of optional parameters
	 * @param 	string 		$src 			String to determine if gathering posts for a shortcode or widget
	 * @param 	bool 		$featimgs 		Display featured images
	 * @param 	bool 		$smartquotes 	Display smart quotes
	 * @param 	string 		$taxSlug 		Category/taxonomy slug to filter testimonials
	 * @param 	string 		$requestor		Which object is requesting the testimonials (default, block)
	 *
	 * @return 	string 		A string of HTML to output
	 */
	public function get_testimonials( $params, $src, $postLimit, $limitIDs, $featimgs, $smartquotes, $taxSlug, $requestor = 'default' ) {

		$return = '';

		// If we are showing a Block...
		if ( 'block' == $requestor ) {

			if ( ( ! empty( $postLimit ) ) && ( ! empty( $taxSlug ) ) ) {

				// Hide posts from this category.
				$args = $this->set_args( $params, $postLimit, '', $taxSlug );

			} else {

				if ( !empty($taxSlug) ) {
					// Limit by category.
					$args = $this->set_args( $params, "", "", $taxSlug );

				} else if ( !empty($limitIDs) ) {
					// Limit by post ID.
					if ( !empty($postLimit) ) {
						// Exclude
						$args 	= $this->set_args( $params, $postLimit, $limitIDs, "" );
					} else {
						// Include
						$args 	= $this->set_args( $params, "", $limitIDs, "" );
					}
				} else {
					$args 	= $this->set_args( $params, "", "", "" );
				}

			}

		} else {
			// Not a block. Display default.

			if ( !empty($taxSlug) ) {
				// Limit by category.
				$args = $this->set_args( $params, "", "", $taxSlug );

			} else if ( !empty($limitIDs) ) {
				// Limit by post ID.
				if ( !empty($postLimit) ) {
					// Exclude
					$args 	= $this->set_args( $params, $postLimit, $limitIDs, "" );
				} else {
					// Include
					$args 	= $this->set_args( $params, "", $limitIDs, "" );
				}
			} else {
				$args 	= $this->set_args( $params, "", "", "" );
			}

		}

		$query 	= new WP_Query( $args );

		if ( is_wp_error( $query ) ) {

			$return = 'ERROR';

		} else {

			// THE LOOP
			if ( $query->have_posts() ) {

				while ( $query->have_posts() ) : $query->the_post();

					// Get vars
					$postid = get_the_ID();
					$featimageclass = 'no-featured';
					if ( $featimgs == 1 ) {
						$showAllImages = true;
						if ( function_exists('has_post_thumbnail') && has_post_thumbnail( $postid ) ) {
							$featimageclass = 'featured-image';
						}
					} else {
						$showAllImages = false;
					}

					// Get meta values
					$meta = get_post_custom( $postid );

					// Set defaults
					$testimonialtext =
					$videoEmbedCode =
					$quoteTextStart =
					$quoteTextEnd = "";

					$hasVideo = false;

					// Vars
					$testimonialtext = $meta['socialproofslider_testimonial_text'][0];

					if ( $smartquotes === "true" || $smartquotes === "1" ) {

						$quoteTextStart = "“";
						$quoteTextEnd = "”";

						$testimonialtext = $quoteTextStart . $testimonialtext . $quoteTextEnd;

					}

					if ( ! empty( $testimonialtext ) ){

						// Determine if YouTube
						if ( strlen( strstr( $testimonialtext, "youtube.com/watch?v=" ) ) > 0 ) {
							// is YouTube video
							$hasVideo = true;
							$videoStr = explode('v=', $testimonialtext);
							$videoID = $videoStr[1];

							$videoEmbedCode .= "<div class='testimonial-video-container'>";
							$videoEmbedCode .= "<iframe src='https://www.youtube.com/embed/";
							$videoEmbedCode .= $videoID;
							$videoEmbedCode .= "' frameborder='0' allowfullscreen></iframe>";
							$videoEmbedCode .= "</div>";
						}

						// Determine if Vimeo
						if ( strlen( strstr( $testimonialtext, "vimeo.com/" ) ) > 0 ) {
							// is Vimeo video
							$hasVideo = true;
							$videoStr = explode('.com/', $testimonialtext);
							$videoID = $videoStr[1];

							$videoEmbedCode .= "<div class='testimonial-video-container'>";
							$videoEmbedCode .= "<iframe src='https://player.vimeo.com/video/";
							$videoEmbedCode .= $videoID;
							$videoEmbedCode .= "' frameborder='0' allowfullscreen></iframe>";
							$videoEmbedCode .= "</div>";
						}

					}

					// Create item
					$return .= '<div class="testimonial-item item-' . $postid . ' ' . $featimageclass . ' " >';

					$showThisFeaturedImage = true;

					if( $showAllImages ){

						// DETERMINE IF THUMB EXISTS
						if ( function_exists('has_post_thumbnail') && has_post_thumbnail( $postid ) ) {
							$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'social-proof-slider' );
							if ( !$thumb[0] ){
								// no src, do nothing
								$showThisFeaturedImage = false;
							} else {
								$theFeaturedImage = $thumb[0];
							}
						}else{
							$theFeaturedImage = '';
							$showThisFeaturedImage = false;
						}

						// OUTPUT THE IMAGE HTML
						if ( $showThisFeaturedImage ) {
							$return .= '<div class="testimonial-author-img-wrap">';
							$return .= '<div class="testimonial-author-img">';
							$return .= '<img src="' . $theFeaturedImage . '" class="client-image" />';
							$return .= '</div>';
							$return .= '</div>';
						}

					}

					$return .= '<div class="testimonial-text">';

					if ( $hasVideo ) {

						$return .= $videoEmbedCode;

					} else {

						$return .= '<div class="quote">';
						$return .= html_entity_decode( wpautop( $testimonialtext ) );
						$return .= '</div>';

					}

					$return .= '<div class="author">';
					$return .= '<div class="author-name">' . html_entity_decode( $meta['socialproofslider_testimonial_author_name'][0] ) . '</div>';
					$return .= '<div class="author-title">' . html_entity_decode( $meta['socialproofslider_testimonial_author_title'][0] ) . '</div>';
					$return .= '</div>';
					$return .= '</div>';

					$return .= '</div>';

				endwhile;

			} else {

				// No Testimonials Published

				$noTestimonialsText = __( 'No Testimonials published yet.', 'social-proof-slider' );

				$return .= '<p>'.$noTestimonialsText;

				if ( is_user_logged_in() ) {

					// Show a message to Admins to create a new testimonial
					$bloghome = get_bloginfo('url');
					$createNewText = __( 'Create one now', 'social-proof-slider' );
					$return .= '<br><a href="' . $bloghome . '/wp-admin/post-new.php?post_type=socialproofslider">'.$createNewText.'!</a>';

				}

				$return .= '</p>';

			}

			wp_reset_postdata();

			//$return = $query->posts;

		}

		return $return;

	} // get_testimonials()


	/**
	 * Sets the args array for a WP_Query call
	 *
	 * @param 	array 		$params 		Array of shortcode parameters
	 * @param 	string 		$postLimit 		A string for include/exclude posts
	 * @param 	string 		$limitIDs 		Array of IDs to include/exclude
	 * @param 	string 		$taxSlug 		Category/taxonomy slug to limit testimonials
	 *
	 * @return 	array 						An array of parameters for WP_Query
	 */
	private function set_args( $params, $postLimit, $limitIDs, $taxSlug ) {

		// If the parameters are empty, exit
		if ( empty( $params ) ) { return; }

		// Setup defaults
		$limitByCat = false;
		$limitedPosts = false;

		// Limit by category.
		if ( ! empty ( $taxSlug ) ) {

			// Create array of the Category slugs
			$catArray = explode(',', $taxSlug);

			// Hide posts from this category.
			if ( ! empty( $postLimit ) ) {

				$limitByCat = true;

				$category_array = array(
				    'tax_query' => array(
				        array(
				            'taxonomy' => 'category',
				            'field' => 'slug',
				            'terms' => $catArray,
							'operator' => 'NOT IN'
				        )
				    )
				);

			} else {
				// Show only the posts from this category.

				$limitByCat = true;

				$category_array = array(
				    'tax_query' => array(
				        array(
				            'taxonomy' => 'category',
				            'field' => 'slug',
				            'terms' => $catArray
				        )
				    )
				);

			}

		}

		// Setting a list of IDs in the shortcode will override the 'Sort By' setting.
		// This enables users to put the IDs in the exact order they want them to appear.
		if ( !empty ( $limitIDs ) ) {

			$limitedPosts = true;

			// Create array of IDs
			$idArray = explode(',', $limitIDs);

			if ( !empty( $postLimit ) ) {
				// Excluding (don't show) these posts from the 'idArray' list

				$inclexcl_array = array(
					'post__not_in' => $idArray,
					'orderby' => 'post__in'
				);

			} else {
				// Including (only show) these posts from the 'idArray' list

				$inclexcl_array = array(
					'post__in' => $idArray,
					'orderby' => 'post__in'
				);

			}

		}

		// Create array for query arguments
		$args = array();

		// Specify default arguments
		$args['no_found_rows']			= true;
		$args['post_status'] 			= 'publish';
		$args['post_type'] 				= 'socialproofslider';
		$args['update_post_term_cache'] = false;
		$args['posts_per_page'] = -1;

		// If limiting by category
		if ( $limitByCat ) {
			$args = array_merge( $args, $category_array );
		}

		// If limiting posts by ID
		if ( $limitedPosts ){
			$args = array_merge( $args, $inclexcl_array );
		}

		// If there are no parameters
		if ( empty( $params ) ) { return $args; }

		// Parse all the arguments and format for query
		$args = wp_parse_args( $params, $args );

		// Return the query
		return $args;

	} // set_args()

	/**
	 * Get the Shortcode Settings saved in the settings page
	 *
	 * @return 	array 				An array of settings
	 */
	public function get_shortcode_settings() {

		$args = array();

		// GET SHORTCODE SETTINGS
		$args['sortby'] = get_option('social_proof_slider_sortby');
		$doAutoPlay = get_option('social_proof_slider_autoplay');
		$args['doAutoPlay'] = '';
		$args['autoplaySpeed'] = get_option('social_proof_slider_displaytime');
		$animationStyle = $args['animationStyle'] = get_option('social_proof_slider_animationstyle');
		$doAutoHeight = get_option('social_proof_slider_autoheight');
		$args['doAutoHeight'] = '';
		$verticalalign = get_option('social_proof_slider_verticalalign');
		$args['verticalalign'] = '';
		$doPaddingOverride = get_option('social_proof_slider_paddingoverride');
		$args['doPaddingOverride'] = '';
		$args['contentPaddingTop'] = get_option('social_proof_slider_contentpaddingtop');
		$args['contentPaddingBottom'] = get_option('social_proof_slider_contentpaddingbottom');
		$args['featImgMarginTop'] = get_option('social_proof_slider_featimgmargintop');
		$args['featImgMarginBottom'] = get_option('social_proof_slider_featimgmarginbottom');
		$args['textPaddingTop'] = get_option('social_proof_slider_textpaddingtop');
		$args['textPaddingBottom'] = get_option('social_proof_slider_textpaddingbottom');
		$args['quoteMarginBottom'] = get_option('social_proof_slider_quotemarginbottom');
		$args['dotsMarginTop'] = get_option('social_proof_slider_dotsmargintop');
		$args['doFade'] = '';
		$showFeaturedImages = get_option('social_proof_slider_showfeaturedimg');
		$args['showFeaturedImages'] = '';
		$args['featuredImageClass'] = '';
		$args['imageBorderRadius'] = get_option('social_proof_slider_imgborderradius');
		$showImageBorder = get_option('social_proof_slider_showimgborder');
		$args['showImageBorder'] = '';
		$imgBorderColor = get_option('social_proof_slider_imgbordercolor');
		$args['imageBorderColor'] = get_option('social_proof_slider_imgbordercolor');
		$args['imageBorderThickness'] = get_option('social_proof_slider_imgborderthickness');
		$args['imageBorderPadding'] = get_option('social_proof_slider_imgborderpadding');
		$bgcolor = get_option('social_proof_slider_bgcolor');
		$args['bgcolor'] = '';
		$surroundWithQuotes = get_option('social_proof_slider_surroundquotes');
		$args['surroundWithQuotes'] = '';
		$textalign = get_option('social_proof_slider_textalign');
		$args['textalign'] = '';
		$args['textColor'] = get_option('social_proof_slider_textcolor');
		$showArrows = get_option('social_proof_slider_showarrows');
		$args['showArrows'] = '';
		$arrowIconType = get_option('social_proof_slider_arrowiconstyle');
		$args['arrowLeft'] = '';
		$args['arrowRight'] = '';
		$args['arrowColor'] = get_option('social_proof_slider_arrowcolor');
		$args['arrowHoverColor'] = get_option('social_proof_slider_arrowhovercolor');
		$showDots = get_option('social_proof_slider_showdots');
		$args['showDots'] = '';
		$args['dotsColor'] = get_option('social_proof_slider_dotscolor');

		$setting_do_autoplay = 'true';  // default
		if ( empty( $doAutoPlay ) || $doAutoPlay == '' ){
			$setting_do_autoplay = 'false';
		}
		$args['doAutoPlay'] = $setting_do_autoplay;

		$setting_do_autoheight = 'true';  // default
		if ( empty( $doAutoHeight ) || $doAutoHeight == '' ){
			$setting_do_autoheight = 'false';
		}
		$args['doAutoHeight'] = $setting_do_autoheight;

		$setting_vertical_align = "align_top";  // default
		if ( !empty( $verticalalign ) || $verticalalign != '' ){
			$setting_vertical_align = $verticalalign;
		}
		$args['verticalalign'] = $setting_vertical_align;

		$setting_do_paddingoverride = 'false';  // default
		if ( $doPaddingOverride ){
			$setting_do_paddingoverride = 'true';
		}
		$args['doPaddingOverride'] = $setting_do_paddingoverride;

		$setting_doFade = 'true';  // default
		if ( $animationStyle == 'slide' || empty( $animationStyle ) || $animationStyle == '' ){
			$setting_doFade = 'false';
		}
		$args['doFade'] = $setting_doFade;

		$showAllFeaturedImages = true;  // default
		$featimageclass = "featured-image ";
		if ( empty( $showFeaturedImages ) || $showFeaturedImages == '' ){
			$showAllFeaturedImages = false;
			$featimageclass = "no-featured ";
		}
		$args['showFeaturedImages'] = $showAllFeaturedImages;
		$args['featuredImageClass'] = $featimageclass;

		$bgColorSetting = 'transparent';
		if ( !empty( $bgcolor ) || $bgcolor != '' ){
			$bgColorSetting = $bgcolor;
		}
		$args['bgcolor'] = $bgColorSetting;

		$showImageBorder = true;  // default
		if ( empty( $showImageBorder ) || $showImageBorder == '' ){
			$showImageBorder = false;
		}
		$args['showImageBorder'] = $showImageBorder;

		// $imgBorderColor = '';
		// if ( !empty( $imgBorderColor ) || $imgBorderColor != '' ){
		// 	$imgBorderColor = $imgBorderColor;
		// }
		// $args['imageBorderColor'] = $imgBorderColor;

		$surroundWithQuotesSetting = false;  // default
		if ( (int)$surroundWithQuotes == 1 ){
			$surroundWithQuotesSetting = true;
		}
		$args['surroundWithQuotes'] = $surroundWithQuotesSetting;

		$setting_text_align = "align_center";  // default
		if ( !empty( $textalign ) || $textalign != '' ){
			$setting_text_align = $textalign;
		}
		$args['textalign'] = $setting_text_align;

		$setting_showArrows = 'true';  // default
		if ( empty( $showArrows ) || $showArrows == '' ){
			$setting_showArrows = 'false';
		}
		$args['showArrows'] = $setting_showArrows;

		$icon_left = "";
		$icon_right = "";

		if ( $showArrows === '1' ){

			$icon_left = "fa-angle-left";	// default
			$icon_right = "fa-angle-right";		// default

			if ( $arrowIconType == "style_zero" ){
				$icon_left = "fa-angle-left";
				$icon_right = "fa-angle-right";
			} else if ( $arrowIconType == "style_one" ){
				$icon_left = "fa-angle-double-left";
				$icon_right = "fa-angle-double-right";
			} else if ( $arrowIconType == "style_two" ){
				$icon_left = "fa-arrow-circle-left";
				$icon_right = "fa-arrow-circle-right";
			} else if ( $arrowIconType == "style_three" ){
				$icon_left = "fa-arrow-circle-o-left";
				$icon_right = "fa-arrow-circle-o-right";
			} else if ( $arrowIconType == "style_four" ){
				$icon_left = "fa-arrow-left";
				$icon_right = "fa-arrow-right";
			} else if ( $arrowIconType == "style_five" ){
				$icon_left = "fa-caret-left";
				$icon_right = "fa-caret-right";
			} else if ( $arrowIconType == "style_six" ){
				$icon_left = "fa-caret-square-o-left";
				$icon_right = "fa-caret-square-o-right";
			} else if ( $arrowIconType == "style_seven" ){
				$icon_left = "fa-chevron-circle-left";
				$icon_right = "fa-chevron-circle-right";
			} else if ( $arrowIconType == "style_eight" ){
				$icon_left = "fa-chevron-left";
				$icon_right = "fa-chevron-right";
			}
		}

		$args['arrowLeft'] = $icon_left;
		$args['arrowRight'] = $icon_right;

		$setting_showDots = 'true';  // show Dots by default
		if ( empty( $showDots ) || $showDots == '' ){
			$setting_showDots = 'false';
		}
		$args['showDots'] = $setting_showDots;

		return $args;

	}

	/**
	 * Registers widgets with WordPress
	 *
	 * @since 		2.0.0
	 * @access 		public
	 */
	public function widgets_init() {

		register_widget( 'social_proof_slider_widget' );

	} // widgets_init()

} // class
