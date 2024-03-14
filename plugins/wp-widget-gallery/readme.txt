=== WP Widget Gallery ===
Contributors: crea8xion
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=33HE6UC8VRNRU&lc=PH&item_name=Charity%20Thermometer&item_number=charterm¤cy_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: widget, sidebar, gallery, sidebar gallery, wp-widget, widget gallery
Requires at least: 3.5
Tested up to: 3.9.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



== Description ==

This WordPress plugin allows user to create a gallery for widgets. This plugin also has the ability to display it on page of your choice. 

Any errors or bugs you find please use the support forum.

If you find this plugin helpful, we appreciate if you give us your review. 

== Installation ==

1. Upload wp-widget-gallery folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==   

= How to change display for small devices using jquery? =

In your theme script add the following script :

//Just use the necessary number to make your condition right.

//Sample : For IPAD and Android tablets / Portrait

if( jQuery(window).width() < 767 ){
    jQuery('.wpwidget-slideshow').attr('data-cycle-carousel-visible', 2)
}

//Sample : For mobile phones / Portrait

if( jQuery(window).width() < 567 ){
    jQuery('.wpwidget-slideshow').attr('data-cycle-carousel-visible', 1)
}

= How to hide prev/next button on small devices =

if( jQuery(window).width() < 767 ){
    jQuery('.wpwidget-button').css({display:'none'})
}

if( jQuery(window).width() < 567 ){
    jQuery('.wpwidget-button').css({display:'none'})
}



== Screenshots ==

1. This is the screenshot for the admin of the wp-widget gallery plugin.

== Changelog ==

= 1.5.3 =
* Update on prettyPhoto XSS exploit - reported by WP.

= 1.5.2 =
* Change lightbox plugin.
* Add share buttons to twitter and facebook.
* Fix upload button if widget is active. Currently you need to refresh the page in order for the upload button to work. 

= 1.5.1 =
* Fix conflict script on set featured image for post and pages. 

= 1.5 =
* Add option to activate widget carousel.

= 1.4 =
* Update header generated error.

= 1.3 =
* Update WP-Widget wp-media upload.

= 1.2 =
* Update WP-Widget frontend image gallery.

= 1.1 =
* Update WP-Widget Media upload.

= 1.0 =
* Initial plugin version

== Upgrade notice ==

= 1.5.3 =
* Update on prettyPhoto XSS exploit - reported by WP.

= 1.5.2 =
* Important update for lightbox function. 

= 1.5.1 =
* Fix conflict script on set featured image for post and pages. 

= 1.5 =
* Add option to activate widget carousel.

= 1.4 =
* Update error on activation and widget initiation.

= 1.2 =
* Update WP-Widget frontend image gallery.

= 1.1 =
* Update WP-Widget Media upload.

== Arbitrary section 1 ==

= 1.5.1 =
* Fix conflict script on set featured image for post and pages. 

= 1.5 =
* Add option to activate widget carousel.

= 1.4 =
* Update error on activation and widget initiation.

= 1.3 =
* Small thumb as suggested by Ross Dawson. Added lightbox and masonry for small thumb.