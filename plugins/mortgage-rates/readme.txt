=== Mortgage Rates ===
Tags: mortgage rates, mortgage rate, rates, mortgage, widget
Contributors: mlcalc
Requires at least: 2.0.1
Tested up to: 6.3.0
Stable tag: 1.3.11

Mortgage rates widget for your blog. Free.

== Description ==

[Mortgage Rates](https://www.mlcalc.com/mortgage-rates/ "Mortgage Rates Homepage") is a great value-add for any mortgage officer or real estate agent looking to provide good-looking, functional, valuable mortgage content to readers. The execution of this infobox is superb from the smooth sidebar integration, modal window trend and AJAX-based graphs that are incredibly visually appealing. It's a great little mortgage application that delivers a lot of value in a user-friendly and well-executed way.

The mortgage plugin uses external JavaScript to update mortgage rates from [https://www.mlcalc.com/mortgage-rates/](https://www.mlcalc.com/mortgage-rates/ "Mortgage Rates Source") served by CloudFlare CDN.

__Mortgage Rates Shortcode__

Use the shortcodes on different pages:

`[mlrates]`

specify default values:

`[mlrates state="NY" size="narrow"]`

[WordPress Mortgage Rates Plugin](https://www.mlcalc.com/free-widgets/mortgage-rates/wordpress.htm)

== Installation ==

1. Upload the `mortgage-rates` directory (including all files within) to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

The Mortgage Rates plugin can be used in one of two ways: if you are using widgets, just add the plugin to the sidebar through the `Appearance -> Widgets` menu in WordPress. If you aren't using widgets, add the following code: `<?php display_mlcalc_rates(); ?>` to your template where you would like the rates box to appear.

= Usage =

* If you are using widgets, just add the Mortgage Rates plugin to the sidebar through the `Appearance -> Widgets` menu in WordPress.
* Add the following code: `<?php display_mlcalc_rates(); ?>` to your template where you would like the calculator form to appear.
* Add the shortcode `[mlrates]` to your page or post and configure default parameters.

= Shortcode parameters =
* size = wide | narrow
* state = any US state here

= Shortcode examples =
`	
[mlrates]
[mlrates state="NY" size="narrow"]`

== Mortgage Rates Screenshots ==
1. The Mortgage Rates front-end, as it appears in your WordPress Sidebar.
2. The Trend Chart and Detailed Rates, as it appears when a visitor clicks the View Trend anchor.
3. The Mortgage Rates widget configuration dialog, as it appears within WordPress Administration Interface.

== Upgrade Notice ==

= 1.3.11 =
Tested with Wordpress 5.9.3.

= 1.3.10 =
CSS fix: Rates DOWN - is GREEN! Rates UP - is RED!

= 1.3.9 =
Increased font size.

= 1.3.8 =
CSS fix.

= 1.3.7 =
Widget HTML change.

= 1.3.6 =
Small tests and fixes.

= 1.3.5 =
Small fix.

= 1.3.4 =
Wordpress 5.6.1 tested.

= 1.3.3 =
Small tests and fixes.

= 1.3.2 =
Interface and widget-mode fixes.

= 1.3.1 =
Small fixes.

= 1.3 =
Now all images, CSS and JavaScript are bundled with the plugin and loaded from your own server.

== Frequently Asked Questions ==

= May I use your widget on my commercial website =
Yes. Absolutely.
