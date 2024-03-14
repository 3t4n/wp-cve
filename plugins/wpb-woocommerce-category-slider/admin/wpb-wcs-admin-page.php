<?php

/**
 * WPB WooCommerce Category Slider Plugin
 *
 * Template file for admin page
 *
 * Author: WpBean
 */

wp_enqueue_style( 'wpb-wcs-admin');
wp_enqueue_script( 'jquery' );

$wpb_wcs_plugin_data = get_plugin_data( WPB_WCS_PLUGIN_DIR_FILE );
$version = $wpb_wcs_plugin_data['Version'];

?>

<div class="wrap wpb-about-wrap">
	<h2 class="nav-tab-wrapper">
		<a href="#wpb_wcs_welcome" class="nav-tab" id="wpb_wcs_welcome-tab">Welcome</a>
		<a href="#wpb_wcs_how_top_use" class="nav-tab" id="wpb_wcs_how_top_use-tab">How to use</a>
		<a href="#wpb_wcs_shortcode" class="nav-tab" id="wpb_wcs_shortcode-tab">ShortCodes</a>
		<a href="#wpb_wcs_shortcode_parameters" class="nav-tab" id="wpb_wcs_shortcode_parameters-tab">ShortCode Parameters</a>
		<a href="#wpb_wcs_pro" class="nav-tab" id="wpb_wcs_shortcode_parameters-tab">Pro Version</a>
	</h2>
	<div class="metabox-holder">
		<div id="wpb_wcs_welcome" class="group">
			<h1><?php esc_html_e( 'WPB WooCommerce Category Slider - ', WPB_WCS_TEXTDOMAIN ) ;?><?php echo esc_html( $version ) ?></h1>
			<div class="wpb-about-text">
				<?php esc_html_e( 'This plugin helps you to show the WooCommerce product categories slider in different style with different contents. It allow you to add custom icons to each product category and show the icons in slider. It support couple of different content type for the category slider.', WPB_WCS_TEXTDOMAIN );?>
			</div>
			<div class="wpb_plugin_btns">
				<a class="wpb_button wpb_button_lg wpb_button_success" href="https://wpbean.com/downloads/wpb-woocommerce-category-slider-pro/" target="_blank"><i class="dashicons dashicons-star-filled"></i> Go Premium!</a>
				<a class="wpb_button wpb_button_lg wpb_button_warning" href="http://docs.wpbean.com/docs/wpb-woocommerce-category-slider/installing/" target="_blank"><i class="dashicons dashicons-format-aside"></i> Documentation</a>
			</div>
		</div>
		<div id="wpb_wcs_how_top_use" class="group">
			<h3><?php esc_html_e( 'How to use:', WPB_WCS_TEXTDOMAIN );?></h3>
			<ol>
				<li>Install it as a regular WordPress plugin</li>
				<li>Before install the plugin make sure you have WooCommerce plugin installed and active.</li>
				<li>Using this plugin’s ShortCode to show the products category slider on your site.</li>
			</ol>
		</div>
		<div id="wpb_wcs_shortcode" class="group">
			<h3><?php esc_html_e( 'ShortCode:', WPB_WCS_TEXTDOMAIN );?></h3>
			<ol>
				<li><b>Default Use</b><input type="text" value="[wpb-woo-category-slider]"></li>
				<li><b>Slider with informations, like category description and category page link button.</b><input type="text" value='[wpb-woo-category-slider content_type="with_info" items="4" need_description="on"]'></li>
				<li><b>Slider with category image, description and category page link button.</b><input type="text" value='[wpb-woo-category-slider content_type="with_image" items="4" need_description="on"]'></li>
				<li><b>Slider with category image and child categories.</b><input type="text" value='[wpb-woo-category-slider content_type="with_image" items="4" need_child_cat="on"]'></li>
				<li><b>Slider with category icon and description.</b><input type="text" value='[wpb-woo-category-slider content_type="with_icon" items="4" need_description="on"]'></li>
				<li><b>Slider with category icon and child categories.</b><input type="text" value='[wpb-woo-category-slider content_type="with_icon" items="4" need_child_cat="on"]'></li>
				<li><b>Slider with exclude categories.</b><input type="text" value='[wpb-woo-category-slider exclude="25,26,27"]'></li>
				<li><b>Slider with include categories.</b><input type="text" value='[wpb-woo-category-slider include="25,26,27"]'></li>
			</ol>
		</div>
		<div id="wpb_wcs_shortcode_parameters" class="group">
			<h3><?php esc_html_e( 'ShortCode Parameters:', WPB_WCS_TEXTDOMAIN );?></h3>
			<ol>
				<li><b>content_type</b>Slider content type. Accepted values: plain_text, with_info, with_image, with_icon. Default value: plain_text</li>
				<li><b>need_description</b>Need category description in slider content. Accepted values: on, off. Default value: off</li>
				<li><b>need_child_cat</b>Need child/sub categories on slider content. Accepted values: on, off. Default value: off</li>
				<li><b>need_cat_count</b>Need product count with category. Accepted values: on, off. Default value: on</li>
				<li><b>need_btn</b>Need category page link button. Accepted values: on, off. Default value: off</li>
				<li><b>btn_text</b>Category page button text. Accepted values: Any text. Default value: Shop Now</li>
				<li><b>pagination</b>Slider pagination. Accepted values: true, false. Default value: true</li>
				<li><b>navigation</b>Slider navigation. Accepted values: true, false. Default value: true</li>
				<li><b>autoplay</b>Slider autoplay.  Accepted values: true, false. Default value: true</li>
				<li><b>loop</b>Slider loop.  Accepted values: true, false. Default value: true</li>
				<li><b>items</b>Number of columns in slider.  Accepted values: Any number. Default value: 4</li>
				<li><b>desktopsmall</b>Number of columns in slider for small desktop screen. Accepted values: Any number. Default value: 3</li>
				<li><b>tablet</b>Number of columns in slider for tab screen. Accepted values: Any number. Default value: 2</li>
				<li><b>mobile</b>Number of columns in slider for mobile screen. Accepted values: Any number. Default value: 1</li>

				<li><b>order_by</b>Category order by. Accepted values: id, count, name, slug, none. Default value: name</li>
				<li><b>order</b>Category order. Accepted values: ASC, DESC. Default value: ASC</li>
				<li><b>hide_empty</b>Hide empty categories to show. Accepted values: on, off. Default value: off</li>
				<li><b>number</b>Number of categories to show. Accepted values: any number. Default value: empty, If empty it will show all the categories.</li>
				<li><b>exclude</b>Categories to exclude. Accepted values: comma seperated categories ids. Default value: empty.</li>
				<li><b>include</b>Categories to include. Accepted values: comma seperated categories ids. Default value: empty.</li>
			</ol>
		</div>
		<div id="wpb_wcs_pro" class="group">
			<h3><?php esc_html_e( 'Pro Version Features:', WPB_WCS_TEXTDOMAIN );?></h3>
			<ol>
				<li>Show the categories slider with couple of different CSS3 hover effects.</li>
				<li>Show any of your custom taxonomy or category instate of the product category in slider.</li>
				<li>Category icon picker with couple of different icon packs.</li>
				<li>Category Slider with Category Image Background, Icons, Sub Categories, button etc.</li>
				<li>Super easy ShortCode builder for generating customized slider ShortCodes.</li>
				<li>Priority support to our <a href="https://wpbean.com/support/" target="_blank" rel="noopener">support forum</a>.</li>
				<li>Free installation if you need.</li>
				<li>Free CSS customization if you need.</li>
				<li>Tested with most of the popular premium WordPress themes.</li>
			</ol>
			<div class="wpb_plugin_btns">
				<a class="wpb_button wpb_button_lg wpb_button_success" href="https://wpbean.com/downloads/wpb-woocommerce-category-slider-pro/" target="_blank"><i class="dashicons dashicons-star-filled"></i> Go Premium!</a>
				<a class="wpb_button wpb_button_lg wpb_button_warning" href="http://docs.wpbean.com/docs/wpb-woocommerce-category-slider/installing/" target="_blank"><i class="dashicons dashicons-format-aside"></i> Documentation</a>
			</div>
		</div>
	</div>	
</div>

<div class="clear"></div>

<div class="wpb_wpbean_socials">
	<h4><?php esc_html_e( 'For getting updates of our plugins, features update, WordPress new trend, New web technology etc. Follows Us.', WPB_WCS_TEXTDOMAIN );?></h4>
	<a href="https://twitter.com/wpbean" title="Follow us on Twitter" class="wpb_twitter" target="_blank"><?php esc_html_e( 'Follow Us On Twitter', WPB_WCS_TEXTDOMAIN );?></a>
	<a href="https://plus.google.com/u/0/+WpBean/posts" title="Follow us on Google+" class="wpb_googleplus" target="_blank"><?php esc_html_e( 'Follow Us On Google Plus', WPB_WCS_TEXTDOMAIN );?></a>
	<a href="https://www.facebook.com/wpbean" title="Follow us on Facebook" class="wpb_facebook" target="_blank"><?php esc_html_e( 'Like Us On FaceBook', WPB_WCS_TEXTDOMAIN );?></a>
	<a href="https://www.youtube.com/user/wpbean/videos" title="Follow us on Youtube" class="wpb_youtube" target="_blank"><?php esc_html_e( 'Subscribe Us on YouTube', WPB_WCS_TEXTDOMAIN );?></a>
	<a href="https://wpbean.com/support/" title="Get Support" class="wpb_support" target="_blank"><?php esc_html_e( 'Get Support', WPB_WCS_TEXTDOMAIN );?></a>
</div>

<script>
    jQuery(document).ready(function($) {

        // Switches option sections
        $('.group').hide();
        var activetab = '';
        if (typeof(localStorage) != 'undefined' ) {
            activetab = localStorage.getItem("activetab");
        }
        if (activetab != '' && $(activetab).length ) {
            $(activetab).fadeIn();
        } else {
            $('.group:first').fadeIn();
        }
        $('.group .collapsed').each(function(){
            $(this).find('input:checked').parent().parent().parent().nextAll().each(
            function(){
                if ($(this).hasClass('last')) {
                    $(this).removeClass('hidden');
                    return false;
                }
                $(this).filter('.hidden').removeClass('hidden');
            });
        });

        if (activetab != '' && $(activetab + '-tab').length ) {
            $(activetab + '-tab').addClass('nav-tab-active');
        }
        else {
            $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
        }
        $('.nav-tab-wrapper a').click(function(evt) {
            $('.nav-tab-wrapper a').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active').blur();
            var clicked_group = $(this).attr('href');
            if (typeof(localStorage) != 'undefined' ) {
                localStorage.setItem("activetab", $(this).attr('href'));
            }
            $('.group').hide();
            $(clicked_group).fadeIn();
            evt.preventDefault();
        });

        $(".wpb-about-wrap input[type='text']").on("click", function () {
		   $(this).select();
		});
	});
</script>