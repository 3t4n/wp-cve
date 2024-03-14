<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_FB_Reviews
 * @subpackage WP_FB_Reviews/admin/partials
 */
 
     // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
?>
<div class="">
<h1></h1>
<div class="wrap" id="wp_rev_maindiv">

	<img class="wprev_headerimg" src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png?id='.$this->_token.time(); ?>">
<?php 
include("tabmenu.php");
?>
<div class="welcomecontainer wpfbr_margin10 w3-row-padding w3-section w3-stretch">

<div class="w3-col s12 m6 w3-container ">
<div class="welcomediv w3-white w3-border w3-border-light-gray2 w3-round-small">
	<h3>Welcome! </h3>
	<p>Thank you for being an awesome WP Review Slider customer! If you have trouble, please don't hesitate to contact me. </p>
	<h3>Getting Started: </h3>
	<p>1) Use the Facebook and/or Twitter Page to Download your reviews from different sites and save them to your database. (The <a href="https://wpreviewslider.com/" target="_blank">Pro version</a> can download revies from 85+ sites!)</p>
	<p>2) Once downloaded, all the reviews should show up on the "Review List" page of the plugin. </p>
	<p>3) Create a Review Slider or Grid for your site on the "Templates" page. By default the review template will show all your reviews, you can use the filters to only show the reviews you want. </p>
	
	If you have any trouble please check the <a href="https://wordpress.org/support/plugin/wp-tripadvisor-review-slider/" target="_blank">Support Forum</a> first. If you want to contact me privately you can use the form on my website <a href="https://wpreviewslider.com/contact/">here</a>. I'm always happy to help!	</p>
	<p>Thanks!<br>Josh<br>Developer/Creator </p>

</div>
</div>
<div class="w3-col s12 m6 welcomediv w3-container ">
<div class="welcomediv w3-white w3-border w3-border-light-gray2 w3-round-small">
<a id="provimg" href="https://wpreviewslider.com/" target="_blank"><img class="wprev_wpproimg" src="<?php echo plugin_dir_url( __FILE__ ) . 'sitelogo4.png?v='.$this->version; ?>"></a>
	<h3>Pro Version Features! </h3>
	<ul style="list-style-type: circle;margin-left: 20px;">
	<li>Personal support from the developer! I'll even help set it up!</li>
	<li>Download new reviews automatically. </li>
	<li>Also get reviews from Google, Facebook, and 90+ other sites!</li>
	<li>Show reviews in a Grid, Rows, Slider, Masonry, with endless scroll and different pagination options!</li>
	<li>Hide certain reviews from displaying.</li>
	<li>Review submission form with a review gate to catch low reviews before they get to social media!</li>
	<li>Manually add reviews to your database even upload a CSV file.</li>
	<li>Access 11 Review Template styles and even create a child theme.</li>
	<li>Lots of cool badges, floats, and pop-ins!</li>
	<li>Advanced slider controls like: Autoplay, slide animation, timing, hide navigation arrows and dots, adjust slider height and more.</li>
	<li>Tons of filters like review length, source page, rating, date, keywords, tags, or even individually choose which reviews you want to display.</li>
	<li>Automatically create Google schema review snippet markup!</li>
	<li>See all features <b><a href="https://wpreviewslider.com/features/" target="_blank">here</a></b>. Plus get access to all new features I add in the future!</li>
</ul>

</div>
</div>

</div>

<div id="reviewdiv" class="welcomecontainer wpfbr_margin10 w3-row-padding w3-section w3-stretch wpfbr_margin10">
<div class="w3-col s12 m12 w3-container ">
<div class="welcomediv w3-white w3-border w3-border-light-gray2 w3-round-small">
<h5>As a thank you for trying my free plugin, here's a special promo code to <b>save 15%</b> when you go Pro!</h5>
<code>WPPRO15</code>

<h5>Some feedback from over 10k+ happy Pro customers:</h5>

<div class="w3_wprs-row">
							<div class="w3_wprs-col s4">
							  <style>.wprevpro_t1_DIV_2::after{ border-top: 30px solid #fdfdfd; }.wprevpro_t1_DIV_1 {margin: 5px;}a {
    text-decoration: none;
}</style>
							  <div class="w3_wprs-col">
							  <div class="wprevpro_t1_DIV_1">	
							  <div class="wprevpro_t1_DIV_2 wprev_preview_bg1 wprev_preview_bradius" style="border-radius: 0px; background: rgb(253, 253, 253);"><p class="wprevpro_t1_P_3 wprev_preview_tcolor1" style="color: rgb(85, 85, 85);"><span class="wprevpro_star_imgs"><img src="https://ljapps.com/wp-content/plugins/wp-review-slider-pro-premium/public/partials/imgs/stars_5_yellow.png" alt="">&nbsp;&nbsp;</span>Great for my site! Good choice of styles & formats, easy to use. Show cases our 5* (others if required) reviews from Facebook & Google+ easy to update. Good all round plugin.</p>									</div><span class="wprevpro_t1_A_8"><img src="https://s3-us-west-2.amazonaws.com/freemius/plugins//reviews/c8174af85095ea546c03cddd103abfd2.jpg" alt="thumb" class="wprevpro_t1_IMG_4"></span> <span class="wprevpro_t1_SPAN_5 wprev_preview_tcolor2" style="color: rgb(85, 85, 85);">Antony Bowers<br>Director, <a href="https://www.sweetfantasies.co.uk" target="_blank">Sweet Fantasies Cakes </a></span>								
							  </div>								
							  </div>
							</div>
							<div class="w3_wprs-col s4">
							  <div class="w3_wprs-col">							
							  <div class="wprevpro_t1_DIV_1">									
							  <div class="wprevpro_t1_DIV_2 wprev_preview_bg1 wprev_preview_bradius" style="border-radius: 0px; background: rgb(253, 253, 253);">										<p class="wprevpro_t1_P_3 wprev_preview_tcolor1" style="color: rgb(85, 85, 85);">											<span class="wprevpro_star_imgs"><img src="https://ljapps.com/wp-content/plugins/wp-review-slider-pro-premium/public/partials/imgs/stars_5_yellow.png" alt="">&nbsp;&nbsp;</span>Great product, great support! Love this product and the support received has been amazing and fast.</p>									</div><span class="wprevpro_t1_A_8"><img src="https://wpreviewslider.com/wp-content/uploads/wprevslider/avatars/1633774408_188.jpg" alt="thumb" class="wprevpro_t1_IMG_4"></span> <span class="wprevpro_t1_SPAN_5 wprev_preview_tcolor2" style="color: rgb(85, 85, 85);">Russ Kemp<br>Owner, <a href="https://www.russkempphotography.com" target="_blank">Russ Kemp Photography </a></span>								</div>								
							  </div>
							</div>
						  <div class="w3_wprs-col s4">
							  <div class="w3_wprs-col">							
							  <div class="wprevpro_t1_DIV_1">
							  <div class="wprevpro_t1_DIV_2 wprev_preview_bg1 wprev_preview_bradius" style="border-radius: 0px; background: rgb(253, 253, 253);">										<p class="wprevpro_t1_P_3 wprev_preview_tcolor1" style="color: rgb(85, 85, 85);">											<span class="wprevpro_star_imgs"><img src="https://ljapps.com/wp-content/plugins/wp-review-slider-pro-premium/public/partials/imgs/stars_5_yellow.png" alt="">&nbsp;&nbsp;</span><b>Wow this thing really works!</b> I’m really happy with this plug-in. It’s doing exactly what it supposed to do. I even need a little bit of help and got it quickly. Highly recommend!		</p>									</div><span class="wprevpro_t1_A_8"><img src="https://wpreviewslider.com/wp-content/uploads/wprevslider/avatars/1649464747_442.jpg" alt="thumb" class="wprevpro_t1_IMG_4"></span> <span class="wprevpro_t1_SPAN_5 wprev_preview_tcolor2" style="color: rgb(85, 85, 85);">Andrea Barnes<br>Developer, <a href="https://websitessandiego.com" target="_blank">Websites San Diego </a> </span>								</div>								
							  </div>
						  </div>
					</div>
<br>
<a href="https://wpreviewslider.com/pricing/#customerfeedback" target="_blank" class="w3-button w3-round w3-border w3-blue w3-margin-bottom w3-margin-top"><?php _e('Read More Pro Version Feedback Here', 'wp-tripadvisor-review-slider'); ?></a>

</div>

	</div>
	</div>
	
	
</div>
	</div>

