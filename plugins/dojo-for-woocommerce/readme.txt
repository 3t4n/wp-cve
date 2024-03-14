=== Dojo for WooCommerce ===

Contributors: dojo, alexanderkaltchev
Tags: dojo, payments, checkout, credit card
Requires at least: 5.0
Tested up to: 6.3
Stable tag: 2.0.0
Requires PHP: 7.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Extends WooCommerce, allowing you to take payments via Dojo.


== Description ==

The Dojo plugin is available for WooCommerce, allowing you to take payments via Dojo.
The plugin is integrated with the Dojo Online Checkout page. Which uses Dojo’s secure payment form to take payment and then redirects your customer back to your site for order confirmation.

The Dojo plugin has a much lower PCI compliance level and has fewer technical requirements for your web server.

We are here if you have any questions please email devsupport@dojo.tech


= Support =

<a href="mailto:devsupport@dojo.tech">devsupport@dojo.tech</a>


== Installation ==

Instructional Video: https://www.youtube.com/watch?v=0kagHLoYx0w&t

1. Login into the admin area of your WordPress website.

2. Go to "Plugins" -> "Add New".

3. Type "Dojo" into the search box.

4. The plugin should appear in the search results.

5. Click the "Install Now" button.

6. Wait while the plugin is retrieved.

7. Click the "Activate" link associated with "Dojo for WooCommerce" plugin.

**How to generate your API key**

1. Log into the Dojo developer portal or select ‘Developer portal’ under ‘Account’ in the sidebar.

2. If you haven’t used your developer zone before, you may need to activate it. Follow the instructions to get access to test mode and API information.

3. Generate an API key for Select ‘API keys’ in the side bar and click ‘Create new key.’ Give it a name that references your website and click ‘Create key’

4. Click the key to copy it to your clipboard

5. Paste the key into the ‘Secret API key’ field in Wordpress

**How to generate your webhook endpoint**

1. Log into the Dojo developer portal or select ‘Developer portal’ under ‘Account’ in the sidebar.

2. If you haven’t used your developer zone before, you may need to activate it. Follow the instructions to get access to your full sandbox and API information.

3. Select ‘Webhooks’ from the sidebar and click ‘Add endpoint’

4. Enter the endpoint URL. This is your website’s URL followed by /?wx-api=wc_dojo e.g. https://yoursite.com/?wc-api=wc_dojo

5. Click ‘Select events’ and check ‘Status update.’ Click ‘Add events’

6. On the next page, click ‘Add endpoint.’

7. On the webhooks page, click the secret value to copy it to your clipboard

8. Paste the secret value into the ‘Webhook secret’ field in Wordpress.


== Activation and configuration of the Dojo Checkout payment method ==

1. Go to "WooCommerce" -> "Settings" -> "Payments".

2. Click the "Manage" button for the "Dojo Checkout" payment method.

3. Check the "Enable Dojo Checkout" checkbox.

4. Enter your "Secret API key".

5. Enter your "Webhook secret".

6. Optionally, set the rest of the settings as per your needs.

7. Click the "Save changes" button.
