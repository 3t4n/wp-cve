=== zipaddr-jp ===
Contributors: ta_terunuma
Donate link: https://zipaddr2.com/wordpress/
Tags: zipaddr, zip, address, plugin, ajax, cross-domain
Requires at least: 3.7
Tested up to: 6.4
Stable tag: 1.38
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==
Firstly zipaddr has two sites of zipaddr.com and zipaddr2.com in a service provider.
In zipaddr.com, free site, the other are pay sites.
This plugin makes Wordpress and zipaddr work together.
Specifically, generate the following javascript statement on one line.
&lt;script src="https://zipaddr.com/js/zipaddrx.js"   charset="UTF-8"&gt;&lt;/script&gt; or
&lt;script src="https://zipaddr2.com/js/zipaddr3.js"  charset="UTF-8"&gt;&lt;/script&gt; or
&lt;script src="https://zipaddr2.com/js/zipaddr30.js" charset="UTF-8"&gt;&lt;/script&gt;

= In Japanese: =
主な動作手順は次のようになります。
1.起動設定後に郵便番号の入力を待ちます。
2.Wordpress側は郵便番号検索リクエストをzipaddr側に送ります。
3.zipaddrでは郵便番号からDBを検索して都道府県、市区町村、地域、データをjsonp形式で返します。
4.Wordpress側にはガイダンスで検索の途中データが表示されます。
5.最終的にWordpress側画面の都道府県、市区町村、地域、の各欄にデータが埋め込まれます。
zipaddr-jpの詳細説明は、https://zipaddr2.com/wordpress/を参照して下さい。

= In English: =
The main operating procedure is as follows.
1.Wait for the zip code to be entered after the startup setting.
2.Wordpress sends a zip code search request to zipaddr.
3.zipaddr search DB from a zip code and return jsonp form for prefecture, a city, an address.
4.On the Wordpress side, the data in the middle of the search is displayed by the guidance.
5.Wordpress bury each data in an address column on the Wordpress side.

== Installation ==

1. Upload `zipaddr-jp` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==



== Screenshots ==

1. https://zipaddr2.com/wordpress/popup.png

== Changelog ==

= 1.38 =
We changed the prefix from D. to ZP. for compatibility.
We supported Booking Package.

= 1.37 =
We supported Forminator.

= 1.36 =
We supported TieredWorks.

= 1.35 =
We made the footer display optional.

= 1.34 =
We supported Mailfrom.

= 1.33 =
We have defined the default settings for the new form tool.

= 1.32 =
We supported Snow Monkey Forms and MW WP Form.

= 1.31 =
We supported Contact Form 7 and Mailfrom Pro.

= 1.30 =
We have re-supported Welcart.

= 1.29 =
We fixed a bug associated with WooCommerce.

= 1.28 =
We have added an extended version of "zipaddra.js".

= 1.27 =
We have changed it to automatically determine WooCommerce.

= 1.26 =
We supported Visual Form Builder.

= 1.25 =
We supported WPForms.

= 1.24 =
The setting options have been reviewed.

= 1.23 =
We supported Ninja Forms.

= 1.22 =
We performed the reshuffling of some servers.

= 1.21 =
We performed a full-scale review of the coding.

= 1.20 =
We performed V-UP correspondence of welcart.

= 1.19 =
We added a function to insert a module in unconditionally.

= 1.18 =
We made module unification and a setting function of placeholder.

= 1.17 =
We made a module of Welcart fixed zipaddrx.js

= 1.16 =
We revised a Bug.

= 1.15 =
We supported Welcart management screen.

= 1.14 =
We enabled the designation of the zip code delimiter.

= 1.13 =
We changed offer for speedup and guidance output seven columns to the standard

= 1.12 =
We supported Trust Form and Bug fixed.

= 1.11 =
We expanded a link of HELP.

= 1.10 =
We added the function to set an identifier for system expansion.

= 1.9 =
We added the focus function after the zip code input.

= 1.8 =
We added the font-size change function of the guidance screen.

= 1.7 =
We added the mode which managed the zip code book in your company site.

= 1.0.6 =
We added the indication point change function of the guidance screen.

= 1.0.5 =
We revised an SSL-related bug.

= 1.0.4 =
We enlarged target coverage.

= 1.0.3 =
We changed the storage place of the configuration file.

= 1.0.2 =
It added a setting function of the operation environment.

= 1.0.1 =
It register an initial version.

== Upgrade notice ==
