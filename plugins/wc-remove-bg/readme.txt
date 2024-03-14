=== Remove products background for WooCommerce ===
Contributors: avs2738
Tags: woocommerce,background,product images, remove background, change background
Requires at least: 4.2
Tested up to: 5.3.2
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://secure.wayforpay.com/payment/s7f497f68a340

Remove or change backgrounds of WooCommerce product images automatically.

== Description ==
*WooCommerce remove background* is a WordPress plugin, which allows automatically remove or change any background of woocommerce product images using API of [remove.bg](https://www.remove.bg/?aid=qzfprflpwxrcxmbm) service.

You can easily set transparent background, make the background to be any color you want or put your own image as the background. The only requirement for photos is to have your products on foreground, meaning that there should be clear distinction between foreground and background on your photo.

= Key features =
* automatically change/remove any background in product photos
* process either all products or only specified
* choose which image types to process (main image or gallery images)
* choose output image size
* back up and restore original images
* preview result before actual processing

= Supported images =
Any JPG or PNG image with up to 8 megabytes.  All photos that have a subject that is clearly meant to be the foreground are supported. For instance, most photos of products, persons, animals, cars and other objects work. If the image resolution is larger than 10 megapixels (e.g. 4000 × 2500 pixels or any other aspect ratio) it is resized to this maximum resolution.

= How it works? =
For background removal the plugin uses API of service [remove.bg](https://www.remove.bg/?aid=qzfprflpwxrcxmbm). The service allows 50 free requests which provide resulting images of 0.25 megapixels max (e.g. 625×400 pixels). If you like the result, need more requests or higher output resolution - you can obtain a paid subscription and enjoy the full resolution of outputted photos. Read more about pricing [here](https://www.remove.bg/pricing/?aid=qzfprflpwxrcxmbm).

= What if I am unsatisfied with the results? =
We recommend to test the result with a few products by providing their IDs in plugin settings. If you are not satisfied, you can easily restore original images. If you are ok with results, then you can proceed with removing/changing background of all products and obtaining paid subscription at [remove.bg](https://www.remove.bg/?aid=qzfprflpwxrcxmbm) service.

= How to use =
1. Sign up to <a target="_blank" href="https://www.remove.bg/?aid=qzfprflpwxrcxmbm">remove.bg</a> site by going <a target="_blank" href="https://www.remove.bg/users/sign_up/?aid=qzfprflpwxrcxmbm">here</a>. Skip this step if you have already signed up;
2. Sign in to your account at <a target="_blank" href="https://www.remove.bg/?aid=qzfprflpwxrcxmbm">remove.bg</a> by going <a target="_blank" href="https://www.remove.bg/users/sign_in/?aid=qzfprflpwxrcxmbm">here</a>;
3. Navigate to API key tab at your <a target="_blank" href="https://www.remove.bg/?aid=qzfprflpwxrcxmbm">remove.bg</a> profile by going <a target="_blank" href="https://www.remove.bg/profile#api-key/?aid=qzfprflpwxrcxmbm">here</a>;
4. Click the button SHOW and copy the revealed API-key.
5. Paste the API key into relevant plugin setting field and click Save Settings.
3. If you want to preview background removal for some product, enter its ID into Preview a product field and click Preview.
4. Choose desired plugin options.To see each option description, put mouse over option name.
5. Click Start Background removal to start processing products' images.
6. If you are not satisfied with result, click Restore Backup to restore your original images.

= Video demo =
https://www.youtube.com/watch?v=62fa_hWslEs&feature=youtu.be

= General Recommendations for photo shooting =
*Contrast*: Images taken under good lighting conditions and with a high contrast between foreground and background give better results.
*Alignment*: Make sure to upload images with the correct orientation. Results are best if they match gravity (i.e. the ground is at the bottom of the image).
*Prefer plain backgrounds*: Blurry and single-color backgrounds are easier to remove than sharp backgrounds with many details.
*Sharp foreground*: If the foreground is blurry it might get removed. If only the edges are blurry, they will still be blurry in the cutout (which may or may not be an issue).

= Support =
If you have questions or issues with using the plugin - email us: support@fresh-d.biz

= Feedback =
Feel free to leave us your feedback [here](https://fresh-d.biz/wocommerce-remove-background.html#feedback)

== Installation ==
1. Upload wc-remove-bg folder to the `/wp-content/plugins/` directory. Or in WordPress navigate to Plugins->Add new->Upload plugin, choose the wc-remove-bg.zip archive and click Install now
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to plugin settings via Remove BG item in WordPress side menu


== Frequently Asked Questions ==

= How it works? =
For background removal the plugin uses API of service [remove.bg](https://www.remove.bg/?aid=qzfprflpwxrcxmbm). The service allows 50 free requests which provide resulting images of 0.25 megapixels max (e.g. 625×400 pixels). If you like the result, need more requests or higher output resolution - you can obtain a paid subscription and enjoy the full resolution of outputted photos. Read more about pricing here [here](https://www.remove.bg/pricing/?aid=qzfprflpwxrcxmbm).

= What images are supported? =
You can upload any JPG or PNG image with up to 8 megabytes.  All photos that have a subject that is clearly meant to be the foreground are supported. For instance, most photos of products, persons, animals, cars and other objects work. If the image resolution is larger than 10 megapixels (e.g. 4000 × 2500 pixels or any other aspect ratio) it is resized to this maximum resolution.

= What if I am unsatisfied with the results? =
We recommend to test the result with a few products by providing their IDs in plugin settings. If you are not satisfied, you can easily restore original images. If you are ok with results, then you can proceed with removing/changing background of all products and obtaining paid subscription at [remove.bg](https://www.remove.bg/?aid=qzfprflpwxrcxmbm) service.

= Where can I get the ID of a product? =
1. Navigate to Products->All products section of side wordpress menu
2. Put mouse over desired product
3. In the appeared block the first item will be the product's ID, i.e. ID: 123.

Or

1. Edit desired product
2. In the browser URL address bar you will see link (i.e. http://your.site/wp-admin/post.php?post=123&action=edit),where the number after "post=" will be your product ID. In the exmaple it's 123.


== Screenshots ==

1. WooCommerce products remove background plugin admin interface
2. Original images
3. Processed images with new transparent background
4. Processed images with new custom image background
5. Processed images with new green background

== Changelog ==

= 1.0 =
* Initial release
= 1.1 =
* minor bug fix
= 1.2 =
* minor bug fix

== Upgrade Notice ==
