<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT NOTICE
*  Copyright 2016-2023 Arnan de Gans. All Rights Reserved.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

defined('ABSPATH') or die();

$blocklist = get_option('ajdg_spamblocker_domains');
$stats = get_option('ajdg_spamblocker_stats');
?>

<div id="dashboard-widgets-wrap">
	<div id="dashboard-widgets" class="metabox-holder">
		<div id="left-column" class="ajdg-postbox-container">

			<div class="ajdg-postbox">
				<h2 class="ajdg-postbox-title"><?php _e('Add a Domain', 'analytics-spam-blocker'); ?></h2>
				<div id="report-form" class="ajdg-postbox-content">
					<form name="report" id="post" method="post" action="tools.php?page=analytics-spam-blocker">
						<?php wp_nonce_field('asb_nonce_report','asb_nonce_report'); ?>

						<p><img src="<?php echo plugins_url('/images/icon-report.png', __FILE__); ?>" class="alignleft report-icon" /><?php _e('Please add any domains that you think are referral spam domains via this form. Adding a domain also sends a report to AJdG Solutions so others can benefit of your discovery. This will make Analytics Spam Blocker more effective with every update. When a domain gets reported a few times by different users it will be included in the stadard blocklist in the next update. Reports are an automated system.', 'analytics-spam-blocker'); ?></p>

						<p><label for="asb_report_domain"><strong><?php _e('Referral spam domain:', 'analytics-spam-blocker'); ?></strong><br /><input tabindex="1" name="asb_report_domain" type="text" class="search-input" style="width:100%;" value="" autocomplete="off" /><br /><em><?php _e('One domain at a time. Eg: example.com or abc.example.com.', 'analytics-spam-blocker'); ?></em></label></p>

						<p><label for="asb_report_votes"><strong><?php _e('How sure are you about the domain?', 'analytics-spam-blocker'); ?></strong><br /><table width="100%"><tbody>
				       	<tr>
					        <td width="25%">
					        	<center><input type="radio" tabindex="3" name="asb_report_votes" value="1" checked="1" /><br /><?php _e("I don't trust it", 'analytics-spam-blocker'); ?></center>
							</td>
					        <td width="25%">
					        	<center><input type="radio" tabindex="4" name="asb_report_votes" value="2" /><br /><?php _e('Not so sure', 'analytics-spam-blocker'); ?></center>
							</td>
					        <td width="25%">
					        	<center><input type="radio" tabindex="5" name="asb_report_votes" value="3" /><br /><?php _e('Fairly sure', 'analytics-spam-blocker'); ?></center>
							</td>
					        <td width="25%">
					        	<center><input type="radio" tabindex="6" name="asb_report_votes" value="4" /><br /><?php _e('Totally Spam', 'analytics-spam-blocker'); ?></center>
							</td>
						</tr></table></label></p>
						<p><label for="asb_report_username"><strong><?php _e('Your full name:', 'analytics-spam-blocker'); ?></strong><br /><input tabindex="7" name="asb_report_username" type="text" class="search-input" style="width:100%;" value="<?php echo $current_user->display_name;?>" autocomplete="off" /></label></p>
						<p><label for="asb_report_email"><strong><?php _e('Your email address:', 'analytics-spam-blocker'); ?></strong><br /><input tabindex="8" name="asb_report_email" type="text" class="search-input" style="width:100%;" value="<?php echo $current_user->user_email;?>" autocomplete="off" /></label></p>

						<p class="submit">
							<input tabindex="8" type="submit" name="asb_report_submit" class="button-primary" value="<?php _e('Send report and add domain to your custom blocklist', 'analytics-spam-blocker'); ?>" />
						</p>

						<p><strong><?php _e('Note:', 'analytics-spam-blocker'); ?></strong> <?php _e('No other information will be included with the report. All report information is deleted after about 1 year.', 'analytics-spam-blocker'); ?> <?php _e('This information is treated as confidential and is mandatory.', 'analytics-spam-blocker'); ?><br /><?php _e('Reporting the same domain multiple times will have no effect, only the first report will be taken into consideration!', 'analytics-spam-blocker'); ?></p>

					</form>
				</div>
			</div>

			<div class="ajdg-postbox">
				<h2 class="ajdg-postbox-title"><?php _e('Stats', 'analytics-spam-blocker'); ?></h2>
				<div id="stats" class="ajdg-postbox-content">
					<table width="100%">
						<tr>
							<td width="50%"><strong><?php _e('Reported Domains', 'analytics-spam-blocker'); ?><br /><div class="content_large"><?php echo $stats['reported_sites']; ?></div></strong></td>
							<td><strong><?php _e('All Reports', 'analytics-spam-blocker'); ?><br /><div class="content_large"><?php echo $stats['reports_submitted']; ?></div></strong></td>
						</tr>
						<tr>
							<td><strong><?php _e('You Reported', 'analytics-spam-blocker'); ?><br /><div class="content_large"><?php echo $stats['reports_user']; ?></div></strong></td>
							<td><strong><?php _e('Domains in your blocklist', 'analytics-spam-blocker'); ?><br /><div class="content_large"><?php echo $blocklist['domain_count']; ?></div></strong></td>
					</table>
				</div>
			</div>

			<div class="ajdg-postbox">
				<h2 class="ajdg-postbox-title"><?php _e('Spotting referrer spam in your analytics', 'analytics-spam-blocker'); ?></h2>
				<div id="stats" class="ajdg-postbox-content">
					<p><?php _e('Referrer spam, also called refferal spam, is usually easily spotted in Google Analytics and Matomo Analytics. Often these domains have stupid names with common words or numbers in them designed to make you curious why anyone would visit you from that site.', 'analytics-spam-blocker'); ?></p>

					<p><?php _e('For example domains such as: 1-best-seo.com or autoseo-expert.com or simple-share-buttons.com. These names are designed to make you lure you to visit the website.', 'analytics-spam-blocker'); ?></p>

					<p><strong><?php _e('Finding referrer spam:', 'analytics-spam-blocker'); ?></strong><br />
					<?php _e('In Matomo navigate to Acquisition -> Websites', 'analytics-spam-blocker'); ?><br />
					<?php _e('In Google Analytics navigate to Acquisition -> All Traffic -> Referrals', 'analytics-spam-blocker'); ?></p>
					<p><?php _e('Most, if not all, analytics platforms have a referral or incoming traffic overview. If this tab refers to which website your visitors came from, then you can expect to find referrer spam there.', 'analytics-spam-blocker'); ?></p>

					<p><strong><?php _e('What happens to reported domains?', 'analytics-spam-blocker'); ?></strong><br />
        			<?php _e('Analytics Spam Blocker has a unique system to report referral spam straight from your dashboard. This makes it really easy to report and get rid of referral spam domains. Simply enter the domain name, indicate how sure you are of the domain being spam and click report. After a few people report the domain it will be distributed to all users of Analytics Spam Blocker in the next update.', 'analytics-spam-blocker'); ?></p>
				</div>
			</div>

		</div>
		<div id="right-column" class="ajdg-postbox-container">

			<div class="ajdg-postbox">
				<h2 class="ajdg-postbox-title"><?php _e('Analytics Spam Blocker', 'analytics-spam-blocker'); ?></h2>
				<div id="stats" class="ajdg-postbox-content">

					<p><strong><?php _e('Get help with Analytics Spam Blocker', 'analytics-spam-blocker'); ?></strong></p>
					<p><?php _e('If you have any questions about using Analytics Spam Blocker please post it on the support forum. The quickest way is on the AJdG Solutions forum. Always happy to help!', 'analytics-spam-blocker'); ?></p>

					<p><a class="button-primary" href="https://ajdg.solutions/forums/forum/analytics-spam-blocker/?mtm_campaign=spamblocker" target="_blank" title="<?php _e('AJdG Solutions support forum', 'analytics-spam-blocker'); ?>"><?php _e('AJdG Solutions support forum', 'analytics-spam-blocker'); ?></a> <a class="button-secondary" href="https://wordpress.org/support/plugin/analytics-spam-blocker/" target="_blank" title="<?php _e('Forum on wordpress.org', 'analytics-spam-blocker'); ?>"><?php _e('Forum on wordpress.org', 'analytics-spam-blocker'); ?></a></p>

					<p><strong><?php _e('Support Analytics Spam Blocker', 'analytics-spam-blocker'); ?></strong></p>
					<p><?php _e('Consider writing a review or making a donation if you like the plugin or if you find the plugin useful. Thanks for your support!', 'analytics-spam-blocker'); ?></p>

					<p><a class="button-primary" href="https://www.arnan.me/donate.html?mtm_campaign=spamblocker" target="_blank" title="<?php _e('Support me with a token of thanks', 'analytics-spam-blocker'); ?>"><?php _e('Gift a token of thanks', 'analytics-spam-blocker'); ?></a> <a class="button-secondary" href="https://wordpress.org/support/plugin/analytics-spam-blocker/reviews?rate=5#postform" target="_blank" title="<?php _e('Write review on wordpress.org', 'analytics-spam-blocker'); ?>"><?php _e('Write review on wordpress.org', 'analytics-spam-blocker'); ?></a></p>


					<p><strong>More plugins and services</strong></p>
					<p>Check out these and more services in more details on my website. I also make more plugins. If you like Analytics Spam Blocker - Maybe you like some of those as well. Take a look at the <a href="https://ajdg.solutions/plugins/?mtm_campaign=spamblocker" target="_blank">plugins</a> and overall <a href="https://ajdg.solutions/pricing/?mtm_campaign=spamblocker" target="_blank">pricing</a> page for more.</p>

					<table width="100%">
						<tr>
							<td width="33%">
								<div class="ajdg-sales-widget" style="display: inline-block; margin-right:2%;">
									<a href="https://ajdg.solutions/product/adrotate-pro-single/?mtm_campaign=spamblocker" target="_blank"><div class="header"><img src="<?php echo plugins_url("/images/offers/monetize-your-site.jpg", __FILE__); ?>" alt="AdRotate Professional" width="228" height="120"></div></a>
									<a href="https://ajdg.solutions/product/adrotate-pro-single/?mtm_campaign=spamblocker" target="_blank"><div class="title">AdRotate Professional</div></a>
									<div class="sub_title">WordPress Plugin</div>
									<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/adrotate-pro-single/?mtm_campaign=spamblocker" target="_blank">Get a license</a></div>
									<hr>
									<div class="description"><?php _e('Run successful advertisement campaigns on your WordPress website within minutes.', 'analytics-spam-blocker'); ?></div>
								</div>
							</td>
							<td width="33%">
								<div class="ajdg-sales-widget" style="display: inline-block; margin-right:2%;">
									<a href="https://ajdg.solutions/product/wordpress-maintenance-and-updates/?mtm_campaign=spamblocker" target="_blank"><div class="header"><img src="<?php echo plugins_url("/images/offers/wordpress-maintenance.jpg", __FILE__); ?>" alt="WordPress Maintenance" width="228" height="120"></div></a>
									<a href="https://ajdg.solutions/product/wordpress-maintenance-and-updates/?mtm_campaign=spamblocker" target="_blank"><div class="title">WP Maintenance</div></a>
									<div class="sub_title">Professional service</div>
									<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/wordpress-maintenance-and-updates/?mtm_campaign=spamblocker" target="_blank">Order now</a></div>
									<hr>
									<div class="description"><?php _e('Get a checkup for your website and all the latest updates for WordPress and plugins.', 'analytics-spam-blocker'); ?></div>
								</div>
							</td>
							<td>
								<div class="ajdg-sales-widget" style="display: inline-block;">
									<a href="https://ajdg.solutions/plugins/?mtm_campaign=spamblocker" target="_blank"><div class="header"><img src="<?php echo plugins_url("/images/offers/more-plugins.jpg", __FILE__); ?>" alt="AJdG Solutions Plugins" width="228" height="120"></div></a>
									<a href="https://ajdg.solutions/plugins/?mtm_campaign=spamblocker" target="_blank"><div class="title"><?php _e('All my plugins', 'analytics-spam-blocker'); ?></div></a>
									<div class="sub_title"><?php _e('WordPress and ClassicPress', 'analytics-spam-blocker'); ?></div>
									<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/plugins/?mtm_campaign=spamblocker" target="_blank">View now</a></div>
									<hr>
									<div class="description"><?php _e('Plugins for WordPres, ClassicPress, WooCommerce, Classic Commerce and bbPress.', 'analytics-spam-blocker'); ?></div>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>

			<div class="ajdg-postbox">
				<h2 class="ajdg-postbox-title"><?php _e('News & Updates', 'analytics-spam-blocker'); ?></h2>
				<div id="support-news" class="ajdg-postbox-content">
					<p><a href="http://ajdg.solutions/feed/" target="_blank" title="Subscribe to the AJdG Solutions RSS feed!" class="button-primary"><i class="icn-rss"></i><?php _e('Subscribe via RSS feed', 'analytics-spam-blocker'); ?></a> <em><?php _e('No account required!', 'analytics-spam-blocker'); ?></em></p>

					<?php wp_widget_rss_output(array(
						'url' => array('http://ajdg.solutions/feed/'),
						'items' => 4,
						'show_summary' => 1,
						'show_author' => 0,
						'show_date' => 1)
					); ?>
				</div>
			</div>

		</div>
	</div>
</div>
