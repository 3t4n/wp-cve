=== Plugin Name ===

Contributors: neilgee
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=neil%40wpbeaches%2ecom&lc=AU&item_name=WP%20Beaches&item_number=Plugins&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: modals, pop ups, windows, bootstrap
Requires at least: 3.8
Tested up to: 5.2
Stable tag: 1.3.2
Plugin Name: Bootstrap Modals
Plugin URI: http://wpbeaches.com
Description: Using Bootstrap Modals in WordPress
Author: Neil Gee
Version: 1.3.2
Author URI:http://wpbeaches.com
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

This plugin adds Bootstrap Modal functionality to WordPress. All you need to do is add the Modal HTML mark up code.


== Description ==

This plugin adds Bootstrap v3 Modal functionality to WordPress.

It adds just the Bootstrap Javascript Plugin for Modals and associated CSS.

This does not bring in any other Bootstrap javascript or CSS functionality.

There is sample HTML mark up code in the readme.txt for a selector and modal target element.

Options to override the default CSS modal styling and also use a shortcode.

Option to disable Bootstrap JS/CSS files in case of conflict.

== Installation ==

This section describes how to install the plugin:

1. Upload the `bootstrap-modals` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

Here is a simple HTML Modal MarkUp
<code>
<!-- Button trigger modal -->
<a class="btn btn-primary btn-lg" href="#myModal1" data-toggle="modal">Launch demo modal</a>

<!-- Modal -->
<div id="myModal1" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">My Title in a Modal Window</h4>
			</div>
			<div class="modal-body">This is the body of a modal...</div>
			<div class="modal-footer">This is the footer of a modal...</div>
			</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</code>

== Usage ==

Use either the Bootstrap API markup or Javascript to trigger the modal windows, this can be found here: http://getbootstrap.com/javascript/#modals

There is also further usage information here: http://coolestguidesontheplanet.com/bootstrap/modal.php

Mark up needs to be directly applied to post/page or widget area or via a shortcode.

Options to override the default CSS styling.

Here is a simple HTML Modal MarkUp
<code>
<!-- Button trigger modal -->
<a class="btn btn-primary btn-lg" href="#myModal1" data-toggle="modal">Launch demo modal</a>

<!-- Modal -->
<div id="myModal1" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">My Title in a Modal Window</h4>
			</div>
			<div class="modal-body">This is the body of a modal...</div>
			<div class="modal-footer">This is the footer of a modal...</div>
			</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</code>

You can change the modal size by adding an extra CSS class to the <strong>.modal-dialog</strong> div;
<pre>modal-lg</pre> or <pre>modal-sm</pre> for large and small respectively.

Since version 1.0.2 extra CSS is included to set the close button to a state similar to Bootstrap install, to override the default CSS for the close button use a CSS selector .modal-dialog .close { } in your CSS styles.

== Changelog ==

= 1.3.2
* 17/11/17
* Update wp-color-picker to 2.1.2 to be compatible with WP 4.9

= 1.3.1.1 =
* 15th September 2017
- Fix undefined notice
- Compatible with WP 4.9

= 1.3.1 =
* 8th September 2017
- Added option to disable plugins Bootstrap Files - handy if theme is already bootstrap.
- Updated with Bootstrap 3.3.7
- Updated CSS styles for custom CSS options in plugin settings, more specific selectors.
- Option for alignment of header area of modal.

= 1.3.0 =
* 21st July 2016
- Added options page in WP Admin Dashboard > Settings > Bootstrap Modal
- Manual mark up tab
- CSS style options enable the style of the CSS to be overidden
- Shortcode now available - Shortcode tab - explains how the shortcode works


= 1.2.1 =
* 6th September 2015 - Updated with Bootstrap 3.3.5  - CSS change on close button to make circular and position offset off model

= 1.2.0 =
* 19th April 2015 - Updated with Bootstrap 3.3.4 - Fixes for a few significant bugs in the Modal plugin.

= 1.1.0 =
* 22nd January 2015 - Updated with Bootstrap 3.3.2 - no functional changes for modals.

= 1.0.2 =
* 13th November 2014 - Updated with Bootstrap 3.3.1 - https://github.com/twbs/bootstrap/releases/tag/v3.3.1 - Included extra CSS for .close class used on Modal close button.

= 1.0.1 =
* 27th June 2014 - Updated with Bootstrap 3.2.0 - https://github.com/twbs/bootstrap/releases/tag/v3.2.0

= 1.0.0 =
* Initial release.
