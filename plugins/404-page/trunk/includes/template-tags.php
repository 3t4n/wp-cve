<?php
// Copyright 2015 SEEDPROD LLC (email : john@seedprod.com, twitter : @seedprod)


add_shortcode( 'seed_s404f_title', 'seed_s404f_title' );
function seed_s404f_title($echo = true){

	global $seed_s404f;
	extract($seed_s404f);

	$output = '';
	if(!empty($seo_title)){
		$output = esc_html($seo_title);
	} else {
		$output = get_bloginfo( 'name', 'display' );
	}

	$output = apply_filters('seed_s404f_title', $output);

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}


add_shortcode( 'seed_s404f_viewport', 'seed_s404f_viewport' );
function seed_s404f_viewport($echo = true){
	$output = '';
	if(0 == 0){
		$output = '<meta name="viewport" content="width=device-width, initial-scale=1.0">'.PHP_EOL;
	}

	$output = apply_filters('seed_s404f_viewport', $output);

	if ( $echo ){
		echo $output;
	} else {
		return $output;
	}
}




add_shortcode( 'seed_s404f_customcss', 'seed_s404f_customcss' );
function seed_s404f_customcss($echo = true){
	global $seed_s404f;

	extract($seed_s404f);

	$output = '';
	if(!empty($custom_css)){
		$output = '<style type="text/css">'.$custom_css.'</style>';
	}

	$output = apply_filters('seed_s404f_customcss', $output);

	if ( $echo ){
		echo $output;
	} else {
		return $output;
	}
}

add_shortcode( 'seed_s404f_head', 'seed_s404f_head' );
function seed_s404f_head($echo = true){
	require_once(SEED_S404F_PLUGIN_PATH.'lib/seed_s404f_lessc.inc.php');
	global $seed_s404f;

	extract($seed_s404f);

	$output = '';


	// Check if wp_head is enabled
	if(!empty($enable_wp_head_footer)){
		$output .= "<!-- wp_head() -->\n";
		ob_start();
		wp_enqueue_script('jquery');
		wp_head();

		$output = ob_get_clean();;
	}




	// Output Font Awesome
	$output .= "<!-- Font Awesome CSS -->".PHP_EOL;
	$output .='<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">'.PHP_EOL;

	// Boostrap and default Styles
	$output .= "<!-- Bootstrap and default Style -->\n";
	$output .= '<link rel="stylesheet" href="'.SEED_S404F_PLUGIN_URL.'themes/default/bootstrap/css/bootstrap.min.css">'."\n";

	$output .= apply_filters('seed_s404f_default_stylesheet','<link rel="stylesheet" href="'.SEED_S404F_PLUGIN_URL.'themes/default/style.css">'."\n");


	if(is_rtl()){
		$output .= '<link rel="stylesheet" href="'.SEED_S404F_PLUGIN_URL.'themes/default/rtl.css">'."\n";
	}


	// Calculated CSS
	$output .= '<!-- Calculated Styles -->'.PHP_EOL;
	$output .= '<style type="text/css">'.PHP_EOL;
	ob_start();



	?>

	<?php
	$button_font['color'] = $link_color;
	$text_color = "#ffffff";
	$headline_color = "#ffffff";
	?>

	/* Background Style */
	html{
		height:100%;
		<?php if ( !empty( $bg_image ) ): ;?>
			<?php if ( isset( $bg_cover ) && in_array( '1', $bg_cover ) ) : ?>
				background: <?php echo $bg_color;?> url('<?php echo $bg_image; ?>') no-repeat top center fixed;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
			<?php else: ?>
				background: <?php echo $bg_color;?> url('<?php echo $bg_image; ?>') <?php echo $bg_repeat;?> <?php echo $bg_position;?> <?php echo $bg_attahcment;?>;
			<?php endif ?>
		<?php else:
			if(!empty($bg_color)):
		?>
			background: <?php echo $bg_color;?>;
		<?php endif;endif; ?>


		<?php if(empty($bg_image) && !empty($bg_screenshot)): ;?>
			<?php $mshot = 'http://s.wordpress.com/mshots/v1/'. urlencode(home_url()) .'?w=1600'; ?>
			background: <?php echo $bg_color; ?> url('<?php echo $mshot ?>') <?php echo $bg_repeat ?> <?php echo $bg_position ?> <?php echo $bg_attahcment ?> ;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		<?php endif; ?>
	}

	.seed-csp4 body{
			background: transparent;
	}

    /* Text Styles */
	<?php if ( !empty( $text_font ) ):?>
		.seed-csp4 body{
			font-family: <?php echo SEED_S404F::get_font_family($text_font); ?>
		}

		.seed-csp4 h1, .seed-csp4 h2, .seed-csp4 h3, .seed-csp4 h4, .seed-csp4 h5, .seed-csp4 h6{
			font-family: <?php echo SEED_S404F::get_font_family($text_font); ?>
		}
	<?php endif;?>

	<?php if ( !empty( $text_color ) ) { ?>
		.seed-csp4 body{
			color:<?php echo $text_color;?>;
		}
	<?php } ?>

	<?php if ( !empty( $link_color ) ) { ?>
	<?php if ( empty( $headline_color ) ) { $headline_color = $link_color; }?>
	<?php } ?>


	<?php if ( !empty( $headline_color ) ) { ?>
		.seed-csp4 h1, .seed-csp4 h2, .seed-csp4 h3, .seed-csp4 h4, .seed-csp4 h5, .seed-csp4 h6{
			color:<?php echo $headline_color;?>;
		}
	<?php }?>


	<?php if ( !empty( $link_color ) ) { ?>
		.seed-csp4 a, .seed-csp4 a:visited, .seed-csp4 a:hover, .seed-csp4 a:active{
			color:<?php echo $link_color;?>;
		}


	<?php } ?>
    /* Link Styles */


    <?php if(!empty($button_font['color'])){ ?>
		.seed-csp4 a, .seed-csp4 a:visited, .seed-csp4 a:hover, .seed-csp4 a:active{
			color:<?php echo $button_font['color'];?>;
		}

		#goog-wm-sb, #wp-search-btn{
			background: <?php echo $button_font['color'];?>;
		}

		<?php

		$css = "

		   #s404f-socialprofiles a{
			color: {$button_font['color']};
		  }

		  .buttonBackground(@startColor, @endColor) {
		  // gradientBar will set the background to a pleasing blend of these, to support IE<=9
		  .gradientBar(@startColor, @endColor);
		  *background-color: @endColor; /* Darken IE7 buttons by default so they stand out more given they won't have borders */
		  .reset-filter();

		  // in these cases the gradient won't cover the background, so we override
		  &:hover, &:active, &.active, &.disabled, &[disabled] {
		    background-color: @endColor;
		    *background-color: darken(@endColor, 5%);
		  }

		  // IE 7 + 8 can't handle box-shadow to show active, so we darken a bit ourselves
		  &:active,
		  &.active {
		    background-color: darken(@endColor, 10%) e(\"\9\");
		  }
		}

		.reset-filter() {
		  filter: e(%(\"progid:DXImageTransform.Microsoft.gradient(enabled = false)\"));
		}

		.gradientBar(@primaryColor, @secondaryColor) {
		  #gradient > .vertical(@primaryColor, @secondaryColor);
		  border-color: @secondaryColor @secondaryColor darken(@secondaryColor, 15%);
		  border-color: rgba(0,0,0,.1) rgba(0,0,0,.1) fadein(rgba(0,0,0,.1), 15%);
		}

		#gradient {
			.vertical(@startColor: #555, @endColor: #333) {
		    background-color: mix(@startColor, @endColor, 60%);
		    background-image: -moz-linear-gradient(top, @startColor, @endColor); // FF 3.6+
		    background-image: -ms-linear-gradient(top, @startColor, @endColor); // IE10
		    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(@startColor), to(@endColor)); // Safari 4+, Chrome 2+
		    background-image: -webkit-linear-gradient(top, @startColor, @endColor); // Safari 5.1+, Chrome 10+
		    background-image: -o-linear-gradient(top, @startColor, @endColor); // Opera 11.10
		    background-image: linear-gradient(top, @startColor, @endColor); // The standard
		    background-repeat: repeat-x;
		    filter: e(%(\"progid:DXImageTransform.Microsoft.gradient(startColorstr='%d', endColorstr='%d', GradientType=0)\",@startColor,@endColor)); // IE9 and down
		  }
		}
		.lightordark (@c) when (lightness(@c) >= 65%) {
			color: black;
			text-shadow: 0 -1px 0 rgba(256, 256, 256, 0.3);
		}
		.lightordark (@c) when (lightness(@c) < 65%) {
			color: white;
			text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.3);
		}
		@btnColor: {$button_font['color']};
		@btnDarkColor: darken(@btnColor, 15%);
		#wp-search-btn, #goog-wm-sb, .seed-csp4 .btn-primary, .seed-csp4 .btn-primary:focus, .gform_button, #mc-embedded-subscribe, .mymail-wrapper .submit-button {
		  .lightordark (@btnColor);
		  .buttonBackground(@btnColor, @btnDarkColor);
		  border-color: darken(@btnColor, 0%);
		}


		#goog-wm-sb,.seed-csp4 .btn-primary:hover,.seed-csp4 .btn-primary:active {
		  .lightordark (@btnColor);
		  border-color: darken(@btnColor, 10%);
		}

		.seed-csp4 input[type='text']{
			border-color: @btnDarkColor @btnDarkColor darken(@btnDarkColor, 15%);
		}

		@hue: hue(@btnDarkColor);
		@saturation: saturation(@btnDarkColor);
		@lightness: lightness(@btnDarkColor);
		.seed-csp4 input[type='text']:focus {
			border-color: hsla(@hue, @saturation, @lightness, 0.8);
			webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075),0 0 8px hsla(@hue, @saturation, @lightness, 0.6);
			-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075),0 0 8px hsla(@hue, @saturation, @lightness, 0.6);
			box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075),0 0 8px hsla(@hue, @saturation, @lightness, 0.6);

		}

		";

	try{
		$less = new seed_s404f_lessc();
		$style = $less->parse($css);
		echo $style;

	} catch (Exception $e) {
		_e('An error has occured. Please make sure you have entered the Text Color correctly.','seedprod');
		die();
	}

		?>
    <?php }



	$output .= ob_get_clean();



	$output .= '</style>'.PHP_EOL;

	if(!empty($theme) && $theme != 'default' ){

		$output .= '<link rel="stylesheet" href="'.apply_filters('seed_s404f_themes_url',SEED_S404F_PLUGIN_URL).'style.css">'."\n";
	}


	// Include JS
	$output .= "<!-- JS -->".PHP_EOL;
	$include_url = trailingslashit(includes_url());

	//Include jQuery
	if(empty($enable_wp_head_footer)){
		$output .= '<script src="'.$include_url.'js/jquery/jquery.js"></script>'."\n";
	}
	$output .= '<script src="'.SEED_S404F_PLUGIN_URL.'themes/default/bootstrap/js/bootstrap.js"></script>'."\n";

	// Scripts
	$output .= "<!-- Scripts -->\n";
	$output .= '<script src="'.SEED_S404F_PLUGIN_URL.'themes/default/js/script.js"></script>'."\n";

	// Header Scripts
	if(!empty($header_scripts)){
		$output .= "<!-- Header Scripts -->\n";
		$output .= $header_scripts;
	}

	$output .= "<!-- Modernizr -->\n";
	$output .= '<script src="'.SEED_S404F_PLUGIN_URL.'themes/default/js/modernizr.min.js"></script>'."\n";

	$output = apply_filters('seed_s404f_head', $output);

	if ( $echo ){
		echo $output;
	} else {
		return $output;
	}
}

add_shortcode( 'seed_s404f_footer', 'seed_s404f_footer' );
function seed_s404f_footer($echo = true){
	global $seed_s404f, $seed_s404f_post_result;

	extract($seed_s404f);


	$output = '';


	// WP Footer

	if(!empty($enable_wp_head_footer)){
		$output .= "<!-- wp_footer() -->\n";
		ob_start();
		wp_footer();
		$output= ob_get_clean();
		$output .= "<script>\n";
		$output .= 'jQuery(\'link[href*="'.get_stylesheet_directory_uri().'"]\').remove();';
		$output .= "</script>\n";
	}



		if(!empty($bg_cover)){
			if(!empty($bg_image)){


			$output .= '
				<style>
				html {
				height: 100%;
				}
				body
				{
				height:100%;
				overflow: scroll;
				-webkit-overflow-scrolling: touch;
				}
				</style>
				';
		}
	}





	// Footer Scripts
	if(!empty($footer_scripts)){
		$output .= "<!-- Footer Scripts -->\n";
		$output .= $footer_scripts;
	}


	$output = apply_filters('seed_s404f_footer', $output);

	if ( $echo ){
		echo $output;
	} else {
		return $output;
	}
}


add_shortcode( 'seed_s404f_logo', 'seed_s404f_logo' );
function seed_s404f_logo($echo = true){
	global $seed_s404f;

	extract($seed_s404f);

	$output = '';

	if(!empty($logo['url'])){
		$output .= "<img id='s404f-logo' src='".esc_attr($logo)."'>";
	}

	$output = apply_filters('seed_s404f_logo', $output);

	if ( $echo )
		echo $output;
	else {
		return $output;
	}
}

add_shortcode( 'seed_s404f_headline', 'seed_s404f_headline' );
function seed_s404f_headline($echo = true){
	global $seed_s404f;

	extract($seed_s404f);

	$output = '';

	if(!empty($headline)){
		$output .= '<h1 id="s404f-headline">'.$headline.'</h1>';
	}

	$output = apply_filters('seed_s404f_headline', $output);

	if ( $echo )
		echo $output;
	else {
		return $output;
	}
}

add_shortcode( 'seed_s404f_description', 'seed_s404f_description' );
function seed_s404f_description($echo = true){
	global $seed_s404f,$seed_s404f_post_result;

	extract($seed_s404f);

	$is_post = false;
	if(!empty($seed_s404f_post_result['status']) && $seed_s404f_post_result['status'] == '200'){
		$is_post = true;
	}

	$output = '';

	if(!empty($description) && $is_post === false){
		$content = $description;
		if(!empty($enable_wp_head_footer)){
			$content = apply_filters('the_content', $content);
			//if(isset($GLOBALS['wp_embed'])){
			//	$content = $GLOBALS['wp_embed']->autoembed($content);
			//}
			//$content = do_shortcode(shortcode_unautop(wpautop(convert_chars(wptexturize($content)))));
		}else{
			if(isset($GLOBALS['wp_embed'])){
				$content = $GLOBALS['wp_embed']->autoembed($content);
			}
			$content = do_shortcode(shortcode_unautop(wpautop(convert_chars(wptexturize($content)))));
		}
		$output .= '<div id="s404f-description">'.$content.'</div>';
	}

	$output = apply_filters('seed_s404f_description', $output);

	if ( $echo )
		echo $output;
	else {
		return $output;
	}
}

add_shortcode( 'seed_s404f_searchform', 'seed_s404f_searchform' );
function seed_s404f_searchform($echo = true){

	global $seed_s404f;
	extract($seed_s404f);

	$output = "";


	if(!empty($search_form)){
		$home_url = esc_url( home_url( '/' ) );
		$output = "<div id='s404f-searchform'>";
		$output .= "
		<form role='search' method='get' id='searchform' class='searchform' action='$home_url'>
		<div>
		<input type='text' value='".get_search_query() ."' name='s' id='s' />
		<input type='submit' id='wp-search-btn' value='Search' />
		</div>
		</form>
		";
		$output .= "</div>";

	}

	$output = apply_filters('seed_s404f_searchform', $output);

	if ( $echo )
	echo $output;
	else {
		return $output;
	}
}



add_shortcode( 'seed_s404f_socialprofiles', 'seed_s404f_socialprofiles' );
function seed_s404f_socialprofiles($echo = true){
	global $seed_s404f;

	extract($seed_s404f);

	$output = '';

	$output .= '<div id="s404f-socialprofiles">';
	if(!empty($twitter_url)){
		$output .= '<a href="'.$twitter_url.'" target="_blank"><i class="fa fa-twitter fa-2x"></i></a>';
	}
	if(!empty($facebook_url)){
		$output .= '<a href="'.$facebook_url.'" target="_blank"><i class="fa fa-facebook fa-2x"></i></a>';
	}

	$output .= '</div>';


	$output = apply_filters('seed_s404f_socialprofiles', $output);

	if ( $echo )
		echo $output;
	else {
		return $output;
	}
}

add_shortcode( 'seed_s404f_credit', 'seed_s404f_credit' );
function seed_s404f_credit($echo = true){
	global $seed_s404f;
	extract($seed_s404f);


	$output = '';
	if(!empty($footer_credit)){
	$output .= '<div id="s404f-credit">';

	$output .= 'Powered by <a href="https://www.seedprod.com/wordpress-404-page-pro/">SeedProd</a>';
	$output .= '</div>';

	}

	$output = apply_filters('seed_s404f_credit', $output);

	if ( $echo )
		echo $output;
	else {
		return $output;
	}
}
