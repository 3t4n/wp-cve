<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Popup Anything on click
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div id="paoc_basic_tabs" class="paoc-vtab-cnt paoc_basic_tabs paoc-clearfix">

	<h3 class="paoc-basic-heading">Compare <span class="popupaoc-blue">"Popup Anything - A Marketing Popup"</span> Basic VS Pro</h3>
	
	<div class="poac-deal-offer-wrap">
		<div class="poac-deal-offer"> 
			<div class="poac-inn-deal-offer">
				<h3 class="poac-inn-deal-hedding"><span>Buy PopupAnything Pro</span> today and unlock all the powerful features.</h3>
				<h4 class="poac-inn-deal-sub-hedding"><span style="color:red;">Extra Bonus: </span>You will get <span>extra best discount</span> on the regular price using this coupon code.</h4>
			</div>
			<div class="poac-inn-deal-offer-btn">
				<div class="poac-inn-deal-code"><span>EPSEXTRA</span></div>
				<a href="<?php echo esc_url(POPUPAOC_PLUGIN_BUNDLE_LINK); ?>"  target="_blank" class="popupaoc-sf-btn popupaoc-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Get Essential Bundle Now</a>
				<em class="risk-free-guarantee"><span class="heading">Risk-Free Guarantee </span> - We offer a <span>30-day money back guarantee on all purchases</span>. If you are not happy with your purchases, we will refund your purchase. No questions asked!</em>
			</div>
		</div>
	</div>
	

	<table class="wpos-plugin-pricing-table">
		<colgroup></colgroup>
		<colgroup></colgroup>
		<colgroup></colgroup>
		<thead>
			<tr>
				<th></th>
				<th>
					<h2>Free</h2>
				</th>
				<th>
					<h2 class="wpos-epb">Premium</h2>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>Create Unlimited Popups  <span>Create and manage as many popups as you want.</span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Customize Popup <span>Customize the look and feel of the popup.</span></th>
				<td>Limited</td>
				<td>More Options</td>
			</tr>
			<tr>
				<th>Popup Goal <span class="subtext">Set popup goal as you want.</span></th>
				<td>Announcement Popup</td>
				<td>Announcement, Collect Lead, Target URL, Phone Calls Popup</td>
			</tr>
			<tr>
				<th>Popup Type <span class="subtext">Set popup type as you want.</span></th>
				<td>Modal Popup</td>
				<td>Modal, Bar, Push Notification, Slide In Popup</td>
			</tr>
			<tr>
				<th>Popup Designs <span class="subtext">You can choose popup design.</span></th>
				<td>1 Design</td>
				<td>10 Designs</td>
			</tr>
			<tr>
				<th>Cookie Expiry Time <span class="subtext">Set cookie expiry time for popup as you want.</span></th>
				<td>Days</td>
				<td>Days, Hours, Minutes</td>
			</tr>
			<tr>
				<th>WP Templating Features <span class="subtext">You can modify plugin html/designs in your current theme.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>			
			<tr>
				<th>Page Load <span class="subtext">Display popup on page load.</span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Simple Link <span class="subtext">Display popup on simple link click.</span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Image Click <span class="subtext">Display popup on image click.</span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Button Click <span class="subtext">Display popup on button click</span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Inactivity <span class="subtext">Display popup on any inactivity.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Scroll Down <span class="subtext">Display popup on scroll down of page.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Scroll Up <span class="subtext">Display popup on scroll up of page.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Exit Intent <span class="subtext">Display popup when the cursor moves outside the upper page boundary.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>HTML Click <span class="subtext">Display popup on custom click.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Full Screen Popup <span class="subtext">Set full screen popup on the screen.</span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Popup Effects <span class="subtext">Set animation effects for popup.</span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Popup Positions <span class="subtext">Set popup 9 positions on the screen.</span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Popup Width <span class="subtext">Set popup width.</span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Popup Height <span class="subtext">Set popup height.</span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Popup Image <span class="subtext">Set popup image.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Popup Background Color <span class="subtext">Set background color for popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Popup Overlay Image <span class="subtext">Set overlay image for popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Popup Overlay Color <span class="subtext">Set overlay color for popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Heading Settings <span class="subtext">Set heading and sub heading settings for popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Content Settings <span class="subtext">Set content, secondary content, etc settings for popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Show for <span class="subtext">Choose popup visibility for users.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Display On <span class="subtext">Select device on which popup will be display.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Adblocker Popup <span class="subtext">Popup will be displaye when browser is blocking ads.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Impression or Clicks Data <span class="subtext">Store popup impressions or clicks data in database. </span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Schedule Popup <span class="subtext">Set schedule popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Form Fields <span class="subtext">Set collect lead form fields for popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Social Profile <span class="subtext">Set social icons for popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Notification <span class="subtext">Set email notification for popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Integration <span class="subtext">Set mailchimp integration for collext lead popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Inline Popup <span class="subtext">Now you can embed popup inline to any post or page content.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Referrer Popup <span class="subtext">Set Referrer Popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>UTM Popup <span class="subtext">Set UTM a URL parameter based popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Cookie Popup <span class="subtext">Set cookie based popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Google Analytics <span class="subtext">Set google analytics settings for popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>A/B Testing <span class="subtext">Set A/B testing for popup.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Automatic Update <span>Get automatic  plugin updates </span></th>
				<td>Lifetime</td>
				<td>Lifetime</td>
			</tr>
			<tr>
				<th>Support <span>Get support for plugin</span></th>
				<td>Limited</td>
				<td>1 Year</td>
			</tr>
		</tbody>
	</table>
	<div class="poac-deal-offer-wrap">
		<div class="poac-deal-offer"> 
			<div class="poac-inn-deal-offer">
				<h3 class="poac-inn-deal-hedding"><span>Buy PopupAnything Pro</span> today and unlock all the powerful features.</h3>
				<h4 class="poac-inn-deal-sub-hedding"><span style="color:red;">Extra Bonus: </span>You will get <span>extra best discount</span> on the regular price using this coupon code.</h4>
			</div>
			<div class="poac-inn-deal-offer-btn">
				<div class="poac-inn-deal-code"><span>EPSEXTRA</span></div>
				<a href="<?php echo esc_url(POPUPAOC_PLUGIN_BUNDLE_LINK); ?>"  target="_blank" class="popupaoc-sf-btn popupaoc-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Get Essential Bundle Now</a>
				<em class="risk-free-guarantee"><span class="heading">Risk-Free Guarantee </span> - We offer a <span>30-day money back guarantee on all purchases</span>. If you are not happy with your purchases, we will refund your purchase. No questions asked!</em>
			</div>
		</div>
	</div>
</div>