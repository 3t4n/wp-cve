# Comment Moderation Role by WPBeginner

Tags: comments, comment moderation, roles, capabilities  
Contributors: smub, peterwilsoncc  
Requires at least: 5.1  
Tested up to: 6.0  
Requires PHP: 5.6  
Stable tag: 1.1.1  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a new comment moderator user role to your site.

## Description

### Comment Moderation Role by WPBeginner

This comment moderation plugin improves the comment moderation permissions on your WordPress site.

The new role "WPB Comment Moderator" is created on your site to allow you to give users access only to the comment moderation screen. Unlike the WordPress default settings, a comment moderator is not required to be able to author posts, so you can keep your content secure.

The Comment Moderator can approve, decline, or edit any comments on any post.

This plugin also allows your Authors and Contributors to moderate comments on the posts they wrote. That means your guest authors can continue interacting with their readers, without getting access to other articles or comments.

### How Do I Create a Comment Moderator User?

Once this plugin is activated, simply edit a user's profile and change their role to WPB Comment Moderator. This will grant them access only to the comment moderation screens within WordPress, keeping the rest of your site secure.

### Credits

Comment Moderation Role is created by the <a href="https://www.wpbeginner.com/" rel="friend">WPBeginner</a> team.

### What's Next?

To learn more about WordPress, you can visit <a href="https://www.wpbeginner.com/" rel="friend">WPBeginner</a> for tutorials on topics like:

* <a href="http://www.wpbeginner.com/wordpress-performance-speed/" rel="friend" title="Ultimate Guide to WordPress Speed and Performance">WordPress Speed and Performance</a>
* <a href="http://www.wpbeginner.com/wordpress-security/" rel="friend" title="Ultimate WordPress Security Guide">WordPress Security</a>
* <a href="http://www.wpbeginner.com/wordpress-seo/" rel="friend" title="Ultimate WordPress SEO Guide for Beginners">WordPress SEO</a>

...and many more <a href="http://www.wpbeginner.com/category/wp-tutorials/" rel="friend" title="WordPress Tutorials">WordPress tutorials</a>.

If you like our Comment Moderator Role plugin, then consider checking out our other projects:

* <a href="https://optinmonster.com/" rel="friend">OptinMonster</a> – Get More Email Subscribers with the most popular conversion optimization plugin for WordPress.
* <a href="https://wpforms.com/" rel="friend">WPForms</a> – #1 drag & drop online form builder for WordPress (trusted by 4 million sites).
* <a href="https://www.monsterinsights.com/" rel="friend">MonsterInsights</a> – See the Stats that Matter and Grow Your Business with Confidence. Best Google Analytics Plugin for WordPress.
* <a href="https://www.seedprod.com/" rel="friend">SeedProd</a> – Create beautiful landing pages with our powerful drag & drop landing page builder.
* <a href="https://wpmailsmtp.com/" rel="friend">WP Mail SMTP</a> – Improve email deliverability for your contact form with the most popular SMTP plugin for WordPress.
* <a href="https://rafflepress.com/" rel="friend">RafflePress</a> – Best WordPress giveaway and contest plugin to grow traffic and social followers.
* <a href="https://www.smashballoon.com/" rel="friend">Smash Balloon</a> – #1 social feeds plugin for WordPress - display social media content in WordPress without code.
* <a href="https://aioseo.com/" rel="friend">AIOSEO</a> – the original WordPress SEO plugin to help you rank higher in search results (trusted by over 2 million sites).
* <a href="https://www.pushengage.com/" rel="friend">PushEngage</a> – Connect with visitors after they leave your website with the leading web push notification plugin.
* <a href="https://trustpulse.com/" rel="friend">TrustPulse</a> – Add real-time social proof notifications to boost your store conversions by up to 15%.

Visit <a href="http://www.wpbeginner.com/" rel="friend">WPBeginner</a> to learn from our <a href="http://www.wpbeginner.com/category/wp-tutorials/" rel="friend">WordPress Tutorials</a> and find out about other <a href="http://www.wpbeginner.com/category/plugins/" rel="friend">best WordPress plugins</a>.

## Installation

1. Install the Comment Moderation Role plugin by uploading the `comment-moderation-role` directory to the `/wp-content/plugins/` directory. (See instructions on <a href="https://www.wpbeginner.com/beginners-guide/step-by-step-guide-to-install-a-wordpress-plugin-for-beginners/" rel="friend">how to install a WordPress plugin</a>.)
2. Activate Comment Moderation Role through the `Plugins` menu in WordPress.

[youtube https://www.youtube.com/watch?v=QXbrdVjWaME]


## Frequently Asked Questions

### How is this different from WordPress's moderate_comments permission?

In order to moderate all comments, WordPress requires the user have permission to both edit posts and moderate comments. This plugin removes the requirement for a user to have both permissions.

### Are there filters for developers?

Yes, the roles and capabilities used by this plugin can be modified by filters.

Filters should be run prior to the `plugins_loaded` hook running. As a result they can not be added to a theme's functions.php file.

The default moderator capability is Core's `moderate_comments`. This can be replaced with any string using the filter `wpb.comment_moderation_role.moderator_cap`.

Example:

	<?php
	add_filter(
		'wpb.comment_moderation_role.moderator_cap',
		function() { return 'wpb_moderate_comments'; }
	);

The default moderator role is displayed in the admin as `WPB Comment Moderator`. This can be replaced with any string using the filter `wpb.comment_moderation_role.moderator_role_name`.

Example:

	<?php
	add_filter(
		'wpb.comment_moderation_role.moderator_role_name',
		function() { return 'WPB Support Staff'; }
	);

The default moderator role's slug is `wpb_comment_moderator`. This can be replaced with any string using the filter `wpb.comment_moderation_role.moderator_role_slug`.

Example:

	<?php
	add_filter(
		'wpb.comment_moderation_role.moderator_role_slug',
		function() { return 'wpb_support_staff'; }
	);

## Changelog

### 1.1.1
* Misc: Internal documentation updates.
* Misc: The plugin is tested up to WordPress 6.0.

### 1.1.0
* Fix: Ensure replying to comments via the moderation screen links the comment to a post.
