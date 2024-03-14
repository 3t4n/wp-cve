=== SecurePay For WooCommerce ===
Contributors: SecurePay
Tags: payment gateway, payment platform, Malaysia, online banking, fpx
Requires at least: 5.4
Tested up to: 6.3
Requires PHP: 5.6.20
Stable tag: 1.0.18
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SecurePay payment platform plugin for WooCommerce. 

== Description ==

Now you can integrate major Malaysian payment channel in your WooCommerce shop.
> Use our premium short URL: bayar.my, jual.my, beli.my, niaga.my, invois.my, outlet.my, restoran.my and more. E.g: https://bayar.my/kedai-ali or https://kedai-ali.bayar.my or you can use your own domain name.

SecurePay payment platform plugin for WooCommerce.
> Our pricing is on per transaction. No startup fees &  monthly fees.  

SecurePay is secure. 
> We are certified with ISO/IEC 15408 (pending)

Install this plugin to enable online payment using online banking (for Malaysian banks only).

Please visit [signup page](https://www.securepay.my) or email registry@securepay.my to create a SecurePay account and start receiving payments.

Contact us through email hello@securepay.my if you have any questions or comments about this plugin.

Other Integrations:

- [SecurePay For GravityForms](https://wordpress.org/plugins/securepay-for-gravityforms/)
- [SecurePay For WPJobster](https://wordpress.org/plugins/securepay-for-wpjobster/)
- [SecurePay For WPForms](https://wordpress.org/plugins/securepay-for-wpforms/)
- [SecurePay For Restrict Content Pro](https://wordpress.org/plugins/securepay-for-restrictcontentpro)
- [SecurePay For Paid Memberships Pro](https://wordpress.org/plugins/securepay-for-paidmembershipspro)

== Installation ==

Make sure that you already have WooCommerce plugin installed and activated.

**Step 1:**

- Login to your *WordPress Dashboard*
- Go to **Plugins > Add New**
- Search **SecurePay**

**Step 2:**

- **Activate** the plugin through the 'Plugins' screen in WordPress.

**Step 3:**

- Go to **WooCommerce > Settings > Payments**
- Click **Manage**

**Step 4:**

- Fill in your **Token, Checksum Token, UID Token**. You can retrieve your credentials from your SecurePay account.
- Make sure the 'Enable this payment gateway' is ticked.
- Click **Save** to save changes.

Contact us through email hello@securepay.my if you have any questions or comments about this plugin.

== Changelog ==
= 1.0.18 (16-03-2022) =
- Updated jQuery.ajax(): ajax.complete() is deprecated in jQuery 3+, replaced with ajax.always() .

= 1.0.17 (10-08-2021) =
- Added test mode

= 1.0.16 (26-07-2021) =
- Updated Affin bank logo

= 1.0.15 (26-07-2021) =
- Fixed WC_Gateway_SecurePay::process_payment() -> wc_doing_it_wrong: replace update_post_meta "_payment_method_title, _payment_method" with $order->set_payment_method_title, $order->set_payment_method.
- Fixed WC_Gateway_SecurePay::process_payment() -> wp_doing_it_wrong: replace $order->data with $order->get_data().
- Added: support for cancel_url, timeout_url for gateway endpoint.
- Tested up to: 5.8

= 1.0.14 (11-03-2021) =
- Fixed WC_Gateway_SecurePay::process_payment() -> wc_doing_it_wrong: replace $order->id with $order->get_id().
- Fixed Setting page -> typo Sandbox Token Token.

= 1.0.13 (05-03-2021) =
- Fixed responsive bank image on safari iphone.

= 1.0.12 (22-01-2021) =
- Replace bank image.

= 1.0.11 (22-01-2021) =
- Fixed wording.

= 1.0.10 (22-01-2021) =
- Fixed missing max-height.
- Fixed bank image.

= 1.0.9 (21-01-2021) =
- Fixed checkout bank logo -> set css max-height = none

= 1.0.8 (21-01-2021) =
- Added "Use checkout bank logo" option.

= 1.0.7 (19-12-2020) =
- Fixed securepay-checkout.js -> backward-compatible with wp 5.5 jquery.

= 1.0.6 (09-12-2020) =
- Fixed bank list -> remove transient on shutdown .
- Fixed securepay-checkout.js -> only proceed if payment method is securepay.

= 1.0.5 (09-12-2020) =
- Fixed checkout button -> compatibility with wp 5.6
- Fixed securepay-checkout.js -> use self executing function to avoid conflict with other plugin.
- Added auto-updates option.

= 1.0.4 =
- Fixed undefined SECUREPAY constant.

= 1.0.3 =
- Fixed bank list css.

= 1.0.2 =
- Repo cleanup

= 1.0.1 =
- Code refactoring.
- Fixed callback order note

= 1.0.0 =
- Initial release.
