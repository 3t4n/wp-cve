=== GP Social Share ===
Contributors: WestCoastDigital
Tags: social, share, svg
Requires at least: 5.5
Tested up to: 5.7.2
Stable tag: 2.2
Requires PHP: 7.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add social share icons to single posts within GeneratePress Theme

== Description ==

This plugin uses hooks append social share icons to your content.

It uses the if_single() WordPress hook to ensure only fires on all single posts or the included WooCommerce hooks.

Configured shared content:

Image = post featured Image - full url
Title = post title
Content = the first 40 words of the content
URL = the post permalink

== Social Media Channels ==
These are the social channels currently supported by the plugin
* Facebook
* Twitter
* Pinterest
* LinkedIn
* WhatsApp
* Email

== Installation ==

Ensure GeneratePress is your current active theme


1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Appearance->GP Social Share screen to add your own SVG code for the icons and choose your hook placement

== Frequently Asked Questions ==

= Can I use outside of hooks =

You can use the following shortcode
	[gp-social]

= Nothing happens when I activate the plugin =

Ensure you have the GeneratePress Theme active.

= Do I have to use the premium version of the GeneratePress Theme? =

No. This plugin works with the theme and does not require the premium plugin.

= What if the hook I want to use isnt in the option, want it in multiple locations or want to apply some condtional logic? =

You can use the following action to display the social share options whenever/wherever you like, just change out the_hook_you_require for the one you want to use

	add_action( 'the_hook_you_require','add_social_icons' );


= Can I display the amount of times my post has been shared? =

No. This plugin does not use any API's or receive any data from the shared content. It is intentionally built to be light weight.

= Can I change the default links? =

Yes. All the social media links are customisable with their own function.
* Facebook = gp_social_facebook_link
* Twitter = gp_social_twitter_link
* Google+ = gp_social_google_link
* Pinterest = gp_social_pinterest_link
* LinkedIn = gp_social_linkedin_link
* WhatsApp = gp_social_whatsapp_link

an example would look like this

	function gp_social_twitter_link() {

		$title = get_the_title();
		$url = urlencode( get_permalink() );

		$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6.066 9.645c.183 4.04-2.83 8.544-8.164 8.544-1.622 0-3.131-.476-4.402-1.291 1.524.18 3.045-.244 4.252-1.189-1.256-.023-2.317-.854-2.684-1.995.451.086.895.061 1.298-.049-1.381-.278-2.335-1.522-2.304-2.853.388.215.83.344 1.301.359-1.279-.855-1.641-2.544-.889-3.835 1.416 1.738 3.533 2.881 5.92 3.001-.419-1.796.944-3.527 2.799-3.527.825 0 1.572.349 2.096.907.654-.128 1.27-.368 1.824-.697-.215.671-.67 1.233-1.263 1.589.581-.07 1.135-.224 1.649-.453-.384.578-.87 1.084-1.433 1.489z"/></svg>';
		$link = '<a href="https://twitter.com/share?url=' . $url . '&text=' . $title . '" class="tw-share" title="' . __( 'Tweet this post!', 'gp-social' ) . '">' . $icon . '</a>';
		
		return $link;
	}

= Can I change the email body? =

Yes. Just add a function called gp_social_email_body which returns your body content.

= Can I use the media uploader to upload SVG icons? =

No. WordPress has SVG disabled by default due to potential security issues, the decision was made to support this and stick to inline SVG code.

== Screenshots ==
1. Icon SVG code options
2. Colour options
3. Settings

== Changelog ==
= 2.2 =
Fixed bug with excerpt breaking the twitter link by changeing get_the_excerpt to trimming html and removing readmore

= 2.0.1 =
Added support to customise on a post by post basis
Bug Fix Shortcode

= 2.0 =
Rebuilt backend to remove relying on third party plugins

= 1.3 =
Added Settings link to main plugin screen
Updated Meta Box
Removed Google+ Support
Added textarea to add custom email body
Added switch to disable post author in emailUpdate Meta Box
Added add_shortcode to the content share in Twitter to pull the content after the shortcodes have been parsed. Hopefully this fixed a bug with Toolset.


= 1.2 =
Updated settings page extensions
fixed wp_debug error

= 1.1.4 =
Fixed bug for incorrect twitter
Fixed deprecated get_the_author function
Fixed bug with undefined index when debug is active
Fixed potential bug with is_plugin_active('woocommerce/woocommerce.php') check and changed to class_exists( 'WooCommerce' )


= 1.1.3 =
Fixed bug where not pulling images when used in hook outside the loop

= 1.1.2 =
Fixed missing file

= 1.1.1 =
Added ability to choose WooCommerce hooks if WooCommerce is active

= 1.1.0 =
Added ability to change default links

= 1.0.9 =
Accidently left a var_dump output

= 1.0.8 =
Added switch to disable hooks so can use own and/or shortcode functions

= 1.0.7 =
Add prefix text support

= 1.0.6 =
Can now remove svg code from backend to remove from frontend rather than using css to hide unwanted social profiles

= 1.0.5 =
Fixed folders not uploaded for 1.0.4

= 1.0.4 =
Fixed bug with shortcode
Added colour styling support
Improved backend UI

= 1.0.3 =
Added shortcode support

= 1.0.2 =
Added support for custom email body

= 1.0.1 =
Wrapped functions in class for conflict support
Updated readme
Added WhatsApp support
Added hook option to display icons
Converted jQuery to vanilla JS

= 1.0 =
This version allows you to paste in your own SVG icon code

= 0.5 =
* Initial Build

== Upgrade Notice ==

= 1.1.3 =
Fixed bug where not pulling images when used in hook outside the loop

= 1.1.2 =
Fixed missing file

= 1.1.1 =
Added ability to choose WooCommerce hooks if WooCommerce is active

= 1.1.0 =
Added ability to change default links

= 1.0.9 =
Accidently left a var_dump output

= 1.0.8 =
Added switch to disable hooks so can use own and/or shortcode functions

= 1.0.7 =
Upgrade to be able to prefix text

= 1.0.6 =
Upgrade to be able to easily remove unwanted social share icons

= 1.0.3 =
Upgrade in enable shortcode support

= 1.0.2 =
Upgrade in order to be able to customise your email body text

= 1.0 =
Upgrade in order to use your own SVG icon code

== Customisations ==

If you want to find custom icons, I recommend you check out [https://iconmonstr.com](https://iconmonstr.com/)

To use Iconmonstr SVG code
1. Search for your required icon
1. Click on the icon you like
1. Ensure SVG is selected
1. Agree to the license conditions
1. Click on Embed
1. Ensure Inline is selected
1. Highlight the displayed SVG code
1. Copy and paste the code into the relevant icon section
1. Save Changes at the bottom of the page

You can add more sharing using the following function and modifying it as required


	function add_extra_icons($social_links) {

    	$title = get_the_title();
    	$url = urlencode( get_permalink() );
    	$excerpt = wp_trim_words( get_the_content(), 40 );
    	$thumbnail = get_the_post_thumbnail_url( 'full' );
    	$author_id=$post->post_author;
    	$author = get_the_author_meta( 'display_name' , $author_id );
		// Swap your svg code
    	$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M14.238 15.348c.085.084.085.221 0 .306-.465.462-1.194.687-2.231.687l-.008-.002-.008.002c-1.036 0-1.766-.225-2.231-.688-.085-.084-.085-.221 0-.305.084-.084.222-.084.307 0 .379.377 1.008.561 1.924.561l.008.002.008-.002c.915 0 1.544-.184 1.924-.561.085-.084.223-.084.307 0zm-3.44-2.418c0-.507-.414-.919-.922-.919-.509 0-.923.412-.923.919 0 .506.414.918.923.918.508.001.922-.411.922-.918zm13.202-.93c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12zm-5-.129c0-.851-.695-1.543-1.55-1.543-.417 0-.795.167-1.074.435-1.056-.695-2.485-1.137-4.066-1.194l.865-2.724 2.343.549-.003.034c0 .696.569 1.262 1.268 1.262.699 0 1.267-.566 1.267-1.262s-.568-1.262-1.267-1.262c-.537 0-.994.335-1.179.804l-2.525-.592c-.11-.027-.223.037-.257.145l-.965 3.038c-1.656.02-3.155.466-4.258 1.181-.277-.255-.644-.415-1.05-.415-.854.001-1.549.693-1.549 1.544 0 .566.311 1.056.768 1.325-.03.164-.05.331-.05.5 0 2.281 2.805 4.137 6.253 4.137s6.253-1.856 6.253-4.137c0-.16-.017-.317-.044-.472.486-.261.82-.766.82-1.353zm-4.872.141c-.509 0-.922.412-.922.919 0 .506.414.918.922.918s.922-.412.922-.918c0-.507-.413-.919-.922-.919z"/></svg>';
		$extra_icons = array(
			'<a href="https://reddit.com/submit?url={' . $url . '}&title={' . $title . '}" class="add-share" title="' . __( 'Add this post!', 'gp-social' ) . '">' . $icon . '</a>',
		);

		// combine the two arrays
		$social_links = array_merge($extra_icons, $social_links);
 
		return $social_links;
	}
	add_filter('add_social_icons', 'add_extra_icons');

You can add prefix text like this

	function prefix_text($content) {
		$content = 'Social Share This';
		return $content;
	}
	add_filter( 'add_social_prefix', 'prefix_text' );
