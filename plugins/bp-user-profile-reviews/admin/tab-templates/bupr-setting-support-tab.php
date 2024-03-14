<?php
/**
 * BuddyPress Member Review support tab.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="bupr-adming-setting">
	<div class="bupr-tab-header">
		<h3><?php esc_html_e( 'FAQ(s) ', 'bp-member-reviews' ); ?></h3>
		<input type="hidden" class="bupr-tab-active" value="support"/>
	</div>

	<div class="bupr-admin-settings-block">
		<div id="bupr-settings-tbl" class="bupr-table">
			<div class="bupr-admin-row border">
				<div class="bupr-admin-col-12">
				   <button class="bupr-accordion">
					<?php esc_html_e( 'How can we submit the review to a member profile by using this plugin?', 'bp-member-reviews' ); ?>
					</button>
					<div class="panel">
						<p>
							<?php esc_html_e( 'When visiting the "/members" section in the site, go for single profile view page, there you can see a menu namely, "Reviews" which will allow you to add a profile review only if you are a member.', 'bp-member-reviews' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="bupr-admin-row border">
				<div class="bupr-admin-col-12">
					<button class="bupr-accordion">
						<?php esc_html_e( 'How can we add more rating criteria for review form ?', 'bp-member-reviews' ); ?>
					</button>
					<div class="panel">
						<p>
							<?php esc_html_e( 'Just go to "Dashboard->Review->BP member review setting page" and click add criteria button to add more fields and click save setting button to update review settings.', 'bp-member-reviews' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="bupr-admin-row border">
				<div class="bupr-admin-col-12">
					<button class="bupr-accordion">
						<?php esc_html_e( 'What is the Top Members widget and how to use it ?', 'bp-member-reviews' ); ?>
					</button>
					<div class="panel">
						<p>
							<?php esc_html_e( 'Members Review widgets display list of members on site front-end . When you successfully activate BP Member profile review plugin, then you can see Members review widget in the widget section.', 'bp-member-reviews' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="bupr-admin-row border">
				<div class="bupr-admin-col-12">
					<button class="bupr-accordion">
						<?php esc_html_e( 'Can I use the review form on any other page?', 'bp-member-reviews' ); ?>
					</button>
					<div class="panel">
						<p><?php esc_html_e( 'Yes you can use the review form on other page, just copy shortcode from review setting page and paste it on the other page.', 'bp-member-reviews' ); ?></p>
					</div>
				</div>
			</div>
			<div class="bupr-admin-row border">
				<div class="bupr-admin-col-12">
					<button class="bupr-accordion">
						<?php esc_html_e( 'What to do if I unable/fail to publish shortcode page after update to WordPress 5.0?', 'bp-member-reviews' ); ?>
					</button>
					<div class="panel">
						<p><?php esc_html_e( 'Install the Classic Editor plugin. Then, in the right sidebar, you’ll see a link for “use classic editor”.', 'bp-member-reviews' ); ?></p>
					</div>
				</div>
			</div>
			<div class="bupr-admin-row border">
				<div class="bupr-admin-col-12">
					<button class="bupr-accordion">
						<?php esc_html_e( 'Where do I ask for support?', 'bp-member-reviews' ); ?>
					</button>
					<div class="panel">
						<p><?php
						 /* translators: %s: */
						echo sprintf( esc_html__( 'Please visit %1$s for any query related to plugin and BuddyPress.', 'bp-member-reviews' ), '<a href="http://wbcomdesigns.com/contact" rel="nofollow" target="_blank"> Wbcom Designs </a>' ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
