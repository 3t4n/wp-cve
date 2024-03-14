<?php

if ( ! empty( $instance['title'] ) ) {
	$title = apply_filters( 'widget_title', $instance['title'] );
}

if ( ! empty( $title ) ) {
	echo $before_title . $title . $after_title;
}

$thisWidgetID = $instance['thisWidgetID'];

$sortby = '';
if ( ! empty( $instance['sortby'] ) ) {
	$sortby = $instance['sortby'];
}

$autoplay = '';
$autoplayStr = '';
if ( ! empty( $instance['autoplay'] ) ) {
	// $autoplay = $instance['autoplay'];
	$autoplay = "true";
	$autoplayStr = "YES";
} else{
	$autoplay = "false";
	$autoplayStr = "NO";
}

$displaytime = '';
if ( ! empty( $instance['displaytime'] ) ) {
	$displaytime = $instance['displaytime'];
}

$animationstyle = '';
if ( ! empty( $instance['animationstyle'] ) ) {
	$animationstyle = $instance['animationstyle'];
}

$autoheight = '';
$autoheightStr = '';
if ( ! empty( $instance['autoheight'] ) ) {
	$autoheight = "true";
	$autoheightStr = "YES";
} else{
	$autoheight = "false";
	$autoheightStr = "NO";
}

$verticalalign = '';
if ( ! empty( $instance['verticalalign'] ) ) {
	$verticalalign = $instance['verticalalign'];
}

$paddingOverride = '';
$paddingOverrideStr = '';
if ( ! empty( $instance['paddingoverride'] ) ) {
	$paddingOverride = "true";
	$paddingOverrideStr = "YES";
} else{
	$paddingOverride = "false";
	$paddingOverrideStr = "NO";
}

$contentPaddingTop = '';
if ( ! empty( $instance['contentpaddingtop'] ) ) {
	$contentPaddingTop = $instance['contentpaddingtop'];
}

$contentPaddingBottom = '';
if ( ! empty( $instance['contentpaddingbottom'] ) ) {
	$contentPaddingBottom = $instance['contentpaddingbottom'];
}

$featImgMarginTop = '';
if ( ! empty( $instance['featimgmargintop'] ) ) {
	$featImgMarginTop = $instance['featimgmargintop'];
}

$featImgMarginBottom = '';
if ( ! empty( $instance['featimgmarginbottom'] ) ) {
	$featImgMarginBottom = $instance['featimgmarginbottom'];
}

$textPaddingTop = '';
if ( ! empty( $instance['textpaddingtop'] ) ) {
	$textPaddingTop = $instance['textpaddingtop'];
}

$textPaddingBottom = '';
if ( ! empty( $instance['textpaddingbottom'] ) ) {
	$textPaddingBottom = $instance['textpaddingbottom'];
}

$quoteMarginBottom = '';
if ( ! empty( $instance['quotemarginbottom'] ) ) {
	$quoteMarginBottom = $instance['quotemarginbottom'];
}

$dotsMarginTop = '';
if ( ! empty( $instance['dotsmargintop'] ) ) {
	$dotsMarginTop = $instance['dotsmargintop'];
}

$showfeaturedimg = '';
$showfeaturedimgStr = '';
if ( ! empty( $instance['showfeaturedimg'] ) ) {
	$showfeaturedimg = $instance['showfeaturedimg'];
	$showfeaturedimgStr = "YES";
} else{
	$showfeaturedimgStr = "NO";
}

$imgborderradius = '';
if ( !empty( $instance['imgborderradius'] ) ) {
	$imgborderradius = $instance['imgborderradius'];
} else {
	$imgborderradius = '0px';
}

$showimgborder = '';
$showimgborderStr = '';
if ( ! empty( $instance['showimgborder'] ) ) {
	$showimgborder = $instance['showimgborder'];
	$showimgborderStr = "YES";
} else{
	$showimgborderStr = "NO";
}

$imgbordercolor = '';
if ( ! empty( $instance['imgbordercolor'] ) ) {
	$imgbordercolor = $instance['imgbordercolor'];
}

$imgborderthickness = '';
if ( ! empty( $instance['imgborderthickness'] ) ) {
	$imgborderthickness = $instance['imgborderthickness'];
}

$imgborderpadding = '';
if ( ! empty( $instance['imgborderpadding'] ) ) {
	$imgborderpadding = $instance['imgborderpadding'];
}

$bgcolor = 'transparent';
if ( ! empty( $instance['bgcolor'] ) ) {
	$bgcolor = $instance['bgcolor'];
}

$surroundquotes = '';
$surroundquotesStr = '';
if ( ! empty( $instance['surroundquotes'] ) ) {
	$surroundquotes = $instance['surroundquotes'];
	$surroundquotesStr = "YES";
} else{
	$surroundquotesStr = "NO";
}

$textalign = '';
if ( ! empty( $instance['textalign'] ) ) {
	$textalign = $instance['textalign'];
}

$textcolor = '';
if ( ! empty( $instance['textcolor'] ) ) {
	$textcolor = $instance['textcolor'];
}

$showarrows = '';
$showarrowsStr = '';
if ( ! empty( $instance['showarrows'] ) ) {
	// $showarrows = $instance['showarrows'];
	$showarrows = "true";
	$showarrowsStr = "YES";
} else{
	$showarrows = "false";
	$showarrowsStr = "NO";
}

$arrowiconstyle = '';
if ( ! empty( $instance['arrowiconstyle'] ) ) {
	$arrowiconstyle = $instance['arrowiconstyle'];
}

$arrow_left = "";
$arrow_right = "";

if ( $showarrowsStr == 'YES' ){
		$arrow_left = "fa-angle-left";	// default
		$arrow_right = "fa-angle-right";		// default

		if ( $arrowiconstyle == "style_zero" ){
			$arrow_left = "fa-angle-left";
			$arrow_right = "fa-angle-right";
		} else if ( $arrowiconstyle == "style_one" ){
			$arrow_left = "fa-angle-double-left";
			$arrow_right = "fa-angle-double-right";
		} else if ( $arrowiconstyle == "style_two" ){
			$arrow_left = "fa-arrow-circle-left";
			$arrow_right = "fa-arrow-circle-right";
		} else if ( $arrowiconstyle == "style_three" ){
			$arrow_left = "fa-arrow-circle-o-left";
			$arrow_right = "fa-arrow-circle-o-right";
		} else if ( $arrowiconstyle == "style_four" ){
			$arrow_left = "fa-arrow-left";
			$arrow_right = "fa-arrow-right";
		} else if ( $arrowiconstyle == "style_five" ){
			$arrow_left = "fa-caret-left";
			$arrow_right = "fa-caret-right";
		} else if ( $arrowiconstyle == "style_six" ){
			$arrow_left = "fa-caret-square-o-left";
			$arrow_right = "fa-caret-square-o-right";
		} else if ( $arrowiconstyle == "style_seven" ){
			$arrow_left = "fa-chevron-circle-left";
			$arrow_right = "fa-chevron-circle-right";
		} else if ( $arrowiconstyle == "style_eight" ){
			$arrow_left = "fa-chevron-left";
			$arrow_right = "fa-chevron-right";
		}
	}

$arrowcolor = '';
if ( ! empty( $instance['arrowcolor'] ) ) {
	$arrowcolor = $instance['arrowcolor'];
}

$arrowhovercolor = '';
if ( ! empty( $instance['arrowhovercolor'] ) ) {
	$arrowhovercolor = $instance['arrowhovercolor'];
}

$showdots = '';
$showdotsStr = '';
if ( ! empty( $instance['showdots'] ) ) {
	// $showdots = $instance['showdots'];
	$showdots = "true";
	$showdotsStr = "YES";
} else{
	$showdots = "false";
	$showdotsStr = "NO";
}

$dotscolor = '';
if ( ! empty( $instance['dotscolor'] ) ) {
	$dotscolor = $instance['dotscolor'];
}

$excinc = '';
$excincStr = '';
if ( ! empty( $instance['excinc'] ) ) {
	$excinc = $instance['excinc'];
	if ( $instance['excinc'] == 'cat' ) {
		$excincStr = 'CATEGORY';
	} else if ( $instance['excinc'] == 'in' ) {
		$excincStr = 'INCLUDE';
	} else if ( $instance['excinc'] == 'ex' ) {
		$excincStr = 'EXCLUDE';
	}
}

$excincIDs = '';
if ( ! empty( $instance['excincIDs'] ) ) {
	$excincIDs = $instance['excincIDs'];
}

$categorySlug = '';
if ( ! empty( $instance['catSlug'] ) ) {
	$categorySlug = $instance['catSlug'];
}

ob_start();

$shared = new Social_Proof_Slider_Shared( $this->plugin_name, $this->version );

// Determine ORDERBY and ORDER args
if ( $sortby == "RAND" || $sortby == "rand" ) {
	$queryargs = array(
		'orderby' => 'rand',
	);
} else {
	$queryargs = array(
		'order' => $sortby,
		'orderby' => 'ID',
	);
}

// Set defaults
$postLimiter = '';
$limiterIDs = '';
$taxSlug = '';

// Use Exclude/Include and IDs attributes, if present
if ( !empty( $excincIDs ) ) {

	$limiterIDs = $excincIDs;

	if ( $instance['excinc'] == 'ex' ) {
		// EXCLUDING
		$postLimiter = $instance['excinc'];
	} else {
		// INCLUDING
		$postLimiter = '';
	}

}

// Use category, if present
if ( !empty( $categorySlug ) ) {
	$taxSlug = $instance['catSlug'];
}

$paddingStr = '';	// default
if ( $paddingOverrideStr == 'YES' ) {
	$paddingStr = 'style="padding-top: '.$contentPaddingTop.'px; padding-bottom: '.$contentPaddingBottom.'px;"';
}


$alignStr = '';	// default
if ( $autoheightStr == 'NO' ) {
	$alignStr = ' valign-' . $verticalalign;
}

$items = $shared->get_testimonials( $queryargs, 'widget', $postLimiter, $limiterIDs, $showfeaturedimg, $surroundquotes, $taxSlug );	// get_testimonials( $params, $src, $featimgs )

echo '<!-- // ********** SOCIAL PROOF SLIDER ********** // -->'."\n";
echo '<section id="' . $thisWidgetID . '" class="widget _socialproofslider-widget widget__socialproofslider ' . $animationstyle . ' ">'."\n";
echo '<div class="widget-wrap">'."\n";
echo '<div class="social-proof-slider-wrap ' . $textalign . $alignStr . '" '.$paddingStr.'>'."\n";

echo $items."\n";

echo '</div><!-- // .social-proof-slider-wrap // -->'."\n";
echo '</div><!-- // .widget-wrap // -->'."\n";
echo '</section>'."\n";

//* Output styles
echo '<style>'."\n";
echo '#' . $thisWidgetID . ' .social-proof-slider-wrap{ background-color:' . $bgcolor . '; '.$paddingStr.' }'."\n";

if ( $paddingOverrideStr == 'YES' ) {

	echo '#' . $thisWidgetID . ' .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img-wrap .testimonial-author-img { margin-top: ' . $featImgMarginTop . 'px; margin-bottom: ' . $featImgMarginBottom .'px }'."\n";
	echo '#' . $thisWidgetID . ' .social-proof-slider-wrap .testimonial-item .testimonial-text { padding-top: ' . $textPaddingTop . 'px; padding-bottom: ' . $textPaddingBottom .'px }'."\n";
	echo '#' . $thisWidgetID . ' .social-proof-slider-wrap .testimonial-item .testimonial-text .quote { margin-bottom: ' . $quoteMarginBottom . 'px; }'."\n";
	echo '#' . $thisWidgetID . ' .social-proof-slider-wrap ul.slick-dots { margin-top: ' . $dotsMarginTop . 'px; }'."\n";

}

echo '#' . $thisWidgetID . ' .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img img{ border-radius:' . $imgborderradius . 'px; }'."\n";

if ( $showimgborderStr == 'YES' ) {

	echo '#' . $thisWidgetID . ' .social-proof-slider-wrap .testimonial-item.featured-image .testimonial-author-img img{ border: ' . $imgborderthickness . 'px solid ' . $imgbordercolor . ' !important; padding: ' . $imgborderpadding . 'px }'."\n";

}

echo '#' . $thisWidgetID . ' .testimonial-item .testimonial-text{ color:' . $textcolor . '; }'."\n";
echo '#' . $thisWidgetID . ' .slick-arrow span { color:' . $arrowcolor . '; }'."\n";
echo '#' . $thisWidgetID . ' .slick-arrow:hover span{ color:' . $arrowhovercolor . '; }'."\n";
echo '#' . $thisWidgetID . ' .slick-dots li button::before, #' . $thisWidgetID . ' .slick-dots li.slick-active button:before { color:' . $dotscolor . ' }'."\n";
echo '</style>'."\n";

//* CUSTOM JS
$prev_button = '';
$next_button = '';
$thisWidgetJS = '<script type="text/javascript">'."\n";
$thisWidgetJS .= 'jQuery(document).ready(function($) {'."\n";
$thisWidgetJS .= '	$("#' . $thisWidgetID . ' .social-proof-slider-wrap").not(".slick-initialized").slick({'."\n";
$thisWidgetJS .= '		autoplay: ' . $autoplay . ','."\n";
if ( $autoplay == 'true' ) {
	$thisWidgetJS .= '		autoplaySpeed: ' . $displaytime . ','."\n";
}
$doFade = 'false';
if ( $animationstyle == 'fade' ) {
	$doFade = 'true';
}
$thisWidgetJS .= '		fade: ' . $doFade . ','."\n";
$thisWidgetJS .= '		adaptiveHeight: ' . $autoheight . ','."\n";
$thisWidgetJS .= '		arrows: ' . $showarrows . ','."\n";
if ( $showarrows == 'true' ) {

	$prev_button = '<button type="button" class="slick-prev"><span class="fa ' . $arrow_left . '"></span></button>';
	$next_button = '<button type="button" class="slick-next"><span class="fa ' . $arrow_right . '"></span></button>';

	$thisWidgetJS .= '		prevArrow: \'' . $prev_button . '\','."\n";
	$thisWidgetJS .= '		nextArrow: \'' . $next_button . '\','."\n";
}
$thisWidgetJS .= '		dots: ' . $showdots . ','."\n";
$thisWidgetJS .= '		infinite: true'."\n";
$thisWidgetJS .= '	});'."\n";
$thisWidgetJS .= '});'."\n";
$thisWidgetJS .= '</script>'."\n";
echo  $thisWidgetJS;
echo '<!-- // ********** // END SOCIAL PROOF SLIDER // ********** // -->'."\n";

$output = ob_get_contents();

ob_end_clean();

echo $output;
