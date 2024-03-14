=== EDD Hide Download ===
Contributors: easydigitaldownloads, am, cklosows, littlerchicken, zkawesome, smub
Tags: easy digital downloads, hide product, landing page, purchase funnel, ecommerce
Requires at least: 4.9
Tested up to: 6.1
Requires PHP: 5.3
Stable tag: 1.2.11.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hide the default Easy Digital Downloads product page from the user, and redirect them to a custom page.

== Description ==

This plugin requires [Easy Digital Downloads](https://wordpress.org/plugins/easy-digital-downloads/).

If you've created a custom landing page or purchase funnel, hide the default product page of an Easy Digital Downloads product from users, and redirect them to your funnel. This allows you to optimize your purchase funnel and increase revenue.

EDD Hide Download allows you to:
1. Hide a download so it doesn't appear on the custom post type archive page, anywhere where the [downloads] shortcode is being used, or any custom query on a page template
1. Prevent direct access to the download product page. The browser will redirect the user to the site's homepage.
1. Do a combination of hiding the download and preventing direct access to it

This plugin is extremely useful in the following situations:

1. You've created a product landing page and inserted a buy now button to your product. Since the landing page contains all the required product information, you can hide the product on the rest of your site and even prevent direct access to it.
1. You've added a product (eg support package) that shouldn't sit with your other products you have listed. In this case we can simply hide it from appearing with the other products and insert it where we'd like it to appear using the shortcode.

**Filter example**

Example filter of how you can change the redirect based on the download ID. Copy this function to your child theme's functions.php or custom plugin

    function sumobi_custom_edd_hide_download_redirect( $url ) {
    	// download has ID of 17
		if ( '17' == get_the_ID() ) {
			$url = 'http://easydigitaldownloads.com'; // redirect user to another external URL
		}

		// download has ID of 15
		if( '15' == get_the_ID() ) {
			$url = get_permalink( '8' ); // redirect to another download which has an ID of 8
		}

		// return our new URL
		return $url;
	}
	add_filter( 'edd_hide_download_redirect', 'sumobi_custom_edd_hide_download_redirect' );

Example filter of how you can globally change the redirect. Copy this function to your child theme's functions.php or custom plugin

    function sumobi_custom_edd_hide_download_redirect_url( $url ) {
		$url = get_permalink( '8' ); // redirect to another download, post or page

		return $url;
	}
	add_filter( 'edd_hide_download_redirect', 'sumobi_custom_edd_hide_download_redirect' );

**Get more with Easy Digital Downloads Pro**

[https://easydigitaldownloads.com/pricing/](https://easydigitaldownloads.com/pricing "View Plans")

== Installation ==

1. Unpack the entire contents of this plugin zip file into your `wp-content/plugins/` folder locally
1. Upload to your site
1. Navigate to `wp-admin/plugins.php` on your site (your WP Admin plugin page)
1. Activate this plugin

OR you can just install it with WordPress by going to Plugins >> Add New >> and type this plugin's name

After activation, a new "Hide Download" section will appear at the bottom of Easy Digital Download's Download Configuration metabox

== Screenshots ==

1. The new options added to the bottom of Easy Digital Download's Download Configuration metabox


== Changelog ==
= 1.2.11.1 =
* Fix: If the transient of hidden products was missing, specific queries could produce an infinite loop while trying to re-populate the transient.

= 1.2.11 =
* Improvement: Plugin translations are now handled by the WordPress repository.
* Fix: The hidden downloads property could be something other than an array and cause a PHP error.
* Dev: The minimum WordPress version has been updated to 4.9.

= 1.2.10 =
* Fix: Private downloads now respect the "hide" setting.
* New: Admins can now access hidden downloads via the REST API.
* Dev: Refactor how the plugin is loaded.
* Dev: All class properties are explicitly declared.
* Tweak: Updated plugin author name and URL.

= 1.2.9 =
* New: Added Danish translation.
* Fix: Frontend Submissions Integration: PHP notices when viewing a vendor's store.
* Tweak: Update plugin author name and URI to Sandhills Development, LLC.

= 1.2.8 =
* Fix: Do not hide downloads in API when user with edit_post capability is making API request.

= 1.2.7 =
* Fix: Fatal error if FES was not active.

= 1.2.6 =
* New: Compability with the Front End Submissions extension. When a download is hidden it will remain visible on the vendor's dashboard product page

= 1.2.5 =
* Fix: Plugin became deactivated when EDD was updated

= 1.2.4 =
* Fix: Hidden downloads not being hidden properly on some pages such as the custom post type archive pages

= 1.2.3 =
* Fix: Forums not being shown in bbPress
* Tweak: Moved the plugin's options to EDD's "download settings" metabox

= 1.2.2 =
* Fix: Fatal error when bbPress was not active. Added check for existance of bbPress.

= 1.2.1 =
* Fix: Compatibility with bbPress - props @nphaskins‎

= 1.2 =
* Fix: array merge for post__in - props @StephenCronin
* New: activation check for EDD
* Tweak: Improved localization function