=== Cosmick Star Rating ===
Contributors: cosmick
Donate link: http://cosmicktechnologies.com/
Tags: 5 star, admin, five-star, post rating, Google Star Rating
Requires at least: 5.0.1
Tested up to: 6.1
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Google Organic Search Rich Snippets for Reviews and Rating. This plugin conforms to googles structured data algorithm. 
Easily capture customer reviews with a drop of a short code on any page. 
Google emphasis the importance of have a solid customer review database. 
Insert a single function into your themes header or footer and you rating will be displayed and google will automatically crawl and depict your average star ratings. 
Google will also pull the structured data per post level for each review. 

Features:
• Sidebar widget to display latest reviews
• Moderate submissions
• Minimal and extremely light weight
• Global review and individual page / post reviews

= How To use =

= Aggregate Rating =

Place `<?php csr_get_overall_rating(); ?>` in your templates for getting aggregate rating.

= Front End Rate Submission = 

Place `<?php csr_add_rating(); ?>` in your templates or `[csr-add-rating]` Shortcode in a page for Star rating Form.

= To get Single Review Rate =

If you need to get the rating for a particular review `<?php echo csr_get_rating( $post_id ); ?>`. If in a loop `$post_id` not needed. 

= To List All the Reviews - Front End =

You may call `http://yoursite.com/reviews`

== Installation ==

1. You can download and install the Cosmick Reviews plugin through the built-in WordPress plugin installer. Alternately, download the zip file and upload the '/cosmick-stars-rating/' folder to your '../wp-content/plugins/' folder.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. View the instructions page in your the WordPress backend to see detailed explanations and examples of how and where to use the shortcodes which enable reviews to be shown, submitted, or averaged.
4. Periodically check Google Search results to see your reviews being displayed. 


== Frequently Asked Questions ==

= How can I efficiently get my reviews to show up in google after installing this plugin? =

Once you are finished installing this plugin, you can log into your Google Webmaster Tools and use the data highlighter tool and view all the structured data that you markup on your site.

= How can I test to make sure reviews are going to be read correctly by google? =

You can test your homepage after installation by using the structured data testing tool provided by google. https://developers.google.com/structured-data/testing-tool/ . You can test any page that shows reviews, or the page that is displaying the total star rating reviews.

= How long does it take for my reviews to show up in google? =

That is the million dollar question. Google puts no timeline on how long it will take for your reviews and ratings to show in in SERPs. We have found that utilizing the structured data and highlighter tool provide in webmaster sites have shown up in results in 2-3 weeks, we have also seen it take months. Providing you use webmaster tools and the testing tool correctly that would be the best way to assure eventually your rating will show up in SERPs.

= Can I use CSS to style? =

Yes you can use your own style sheet and override css settings

== Screenshots ==

1. Leave Us a Review Front End
2. Overall Review
3. Review Listing - Admin
4. Add/Edit Review - Admin

== Changelog ==

= 1.2.2 =

- BUG: Conflict fixed - When updated with independent addons Importer and Gravity form feed
- ADDED: Featured image added to the reviews post.

= 1.2.1 =

- TWEAK: Schema changed from `Service` to `LocalBusiness`
- ADDED: Added csv import to reviews.
- ADDED: Gravity form feed to accept reviews.

= 1.1.0 =

- TWEAK: Changed rating schema to Services.
- TWEAK: Added Site info and Description to schema.
- BUG: Fixed schema error in aggregate rating.

= 1.0.16 =

- BUG: Fixed stars showing in all post types listing.

= 1.0.15 =

- BUG: Fixed stars showing in all columns on listing page.

= 1.0.14 =

- TWEAK: Rating form - Admin add star rating preselected to 5 star.
- TWEAK: Admin - Stars shown in star listing.

= 1.0.13 =

- TWEAK: Rating form - star rating preselected to 5 star.

= 1.0.12 =

- TWEAK: Rating form tweaks.
- TWEAK: Overall rating linked to reviews archive page.

= 1.0.11 =

- TWEAK: Star Color changed to appear as more golden.
- ADDED: Added shortcode `[csr-form]` to add review rating form.
- ADDED: Added shortcode `[csr-overall]` in your page/post for getting aggregate rating.

= 1.0.10 =

- TWEAK: Fixed bug with Star Rating form shortcode.

= 1.0.9 =

- TWEAK: Corrected display of stars on other post types.

= 1.0.8 =

- ADDED: Added shortcode `[csr-add-rating]` to add review rating form.
- ADDED: Review Rating Form Settings Page.

= 1.0.7 =

- ADDED: Added StyleSheet for basic aggregate alignment.

= 1.0.6 =

- TWEAK: Added `rateit` class to correct style.
- ADDED: Added function `csr_get_rating( $post_id = null )` to get individual post rating.

= 1.0.5 =

- ADDED: Plugin "View details" link.

= 1.0.4 =

- ADDED: Widget - Added setting to limit the words.
- TWEAK: Fixed star rating display after Admin publish it.
- TWEAK: Fixed average rating to show stars and review single page.

= 1.0.3 =

- TWEAK: Fixed update issue for the reviews from admin.
- TWEAK: Fixed star adding on single pages.
- ADDED: Feature to adapt rating from Old star rating to new environment.

== Upgrade Notice ==

= 1.0.10 =
Fixed bug with Star Rating form shortcode.