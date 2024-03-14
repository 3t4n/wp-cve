=== QR code MeCard/vCard generator ===
Contributors: stasionok, kubalskiy
Telegram: https://t.me/stasionok
Donate link: Ethereum 0x1b7722bd9899fD10D145D773F5373460E11f97A6
Tags: mecard, vcard, qrcode, qr code, shortcode, widget
Requires at least: 5.0
Tested up to: 6.4.1
Stable tag: 1.6.6
Requires PHP: 7.3
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Share your contact information such as emails, phone number and much more through QR code with WordPress using shortcode, widget or by direct link.

== Description ==

Plugin Generate QR code in vCard or MeCard format with your contact information.

Share your contact information such as emails and phone numbers and much more through QR code with WordPress using shortcode, widget or everywhere else by direct link.

That plugin use MeCard format and vCard version 3 format as most compatible and frequently used.

You can read detailed information about vCard [here](https://wikipedia.org/wiki/VCard "Wikipedia about vCard"), and about MeCard [here](https://en.wikipedia.org/wiki/MeCard_(QR_code) "Wikipedia about MeCard").

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/wp-qrcode-me-v-card` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use plugin`s created post type to create and manage QR code card.

== Frequently Asked Questions ==

= Why I can`t read QR code sometimes =

That happens if you disable margin around code or set it too small. In this case your QR code reader can`t detect QR code borders.

= Why my device do not see all MeCard/vCard filled fields =

Different devices and different software may not work with some fields which are set in the code standard. But all of them should work with basic fields as phone, email, url, name and address.

= Why QR code not works when logo placed in QR code center =

QR code has additional information which help read damaged code. When you place logo you actually make code "damaged". And if you can`t read QR code after logo placed just make logo smaller or/and correction level higher

== How to increase photo size in QR code vcf file =

You can override qr-code thumbnail with following code in your theme functions.php file
```add_action( 'after_setup_theme', 'change_qr_code_photo_size' );
function change_qr_code_photo_size() {
	// Override the image size
	add_image_size( 'qr-code-photo', 600, 600 );
}```


== Screenshots ==

1. Add new QR code personal contact information card and setting up result
2. Featured image with QR code created after saving new card. And regenerate after each update
3. In saved QR code cards list you can view QR code, get QR code shortcode or copy direct link to image
4. On widgets page you can select which of saved card to show at selected widget area.

== Changelog ==

= 1.0 =
* Basic functionality released.

= 1.1 - 2020-01-01 =
Add optional logo on center of QR code image.

= 1.1.1 - 2020-01-08 =
Fix missing static content

= 1.2 - 2020-02-03 =
Fix remove logo image issue
Fix permanent url for QR code
New permalink behaviour - now create it by request

= 1.3 - 2020-03-03 =
Fix generate vCard
Fix zero margin
Fix clear logo in form
New add ability to save or open QE code by click

= 1.3.1 - 2020-03-03 =
Fix filename on open vCard on click

= 1.4.0 - 2020-08-26 =
Fix error on php8.0 (update vendor)
Fix some phone encoding issue (only vCard)
Add photo field in card
Add class field in card

= 1.4.1 - 2021-08-27 =
Fix widget error on wp 5.8
Fix photo field
Update translate
Make photo binary type on vcf download (in that type phones see photo)
Add direct link to vcf file in qr-codes list

= 1.4.2 - 2021-10-01 =
Fix fields description, change comma to semicolon

= 1.5.0 - 2021-10-09 =
Add support for multi TEL, EMAIL and ADR fields
Fix minor issues
Update translate

= 1.5.1 - 2021-10-29 =
Add support for multi URL field
Update translate

= 1.5.2 - 2021-12-10 =
Add color picker for select foreground and/or background color for qr-code
Fix Create permanent

= 1.5.3 - 2021-02-26 =
Fix avada theme conflict (color picker)
Fix minor issues

= 1.5.4 - 2022-06-16 =
Fix undefined color issue

= 1.5.5 - 2022-11-04 =
Fix color picker issue
Check WP 6.0.1 support

= 1.5.6 - 2023-03-28 =
Check WP 6.2.0 support

= 1.6.0 - 2023-07-26 =
Check WP 6.3.0 support
Allow set vcf file name for download
Fix vCard on broken photo
Fix missing translate
Fix minor issues

= 1.6.1 - 2023-07-27 =
Fix medium severity vulnerability

= 1.6.2 - 2023-09-08 =
Update select2 version to fix some specific issue

= 1.6.3 - 2023-09-19 =
Fix vcf file line ending to fix MS People crash
Fix translate issue
Fix WS codex issues
Improve security

= 1.6.4 - 2023-11-15 =
Add new thumb size for qr-code photo
Check WP 6.4.1 support

= 1.6.5 - 2024-02-19 =
Check WP 6.4.3 support
Fix multi-version select2 usage issue

= 1.6.6 - 2024-02-20 =
Hotfix translate issue
