=== Easy Auto SKU Generator for WooCommerce ===
Contributors: alexodiy, campusboy1987
Donate link: https://boosty.to/dan-zakirov/donate
Tags: sku generator, product sku, woocommerce sku, auto sku, add sku, sku woocommerce, woocommerce, SKU Variable Products, Variable Products, sku numbers, sku letters, sku slug, autoSKU, automatically generate SKUs, generate variation SKUs, SKU Settings
Requires at least: 4.8
Tested up to: 6.4
Stable tag: 1.2.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
A very simple plug-in of auto-generating SKU for those who are too lazy to fill in the article (SKU) of the product when it is created. Just activate the plugin and when creating a product the SKU will automatically generate.

> In the last update of the plugin, a new function was added - **Generation of SKU into separate categories**.

> **The new version of the plugin adds the function of mass generating SKU of all products at the same time. We will improve this option. In the future, not only mass generation will be available, but also generation for certain parameters (generation by attributes, by tags, by categories).**

== Settings plugin ==

Woocommerce &rarr; Settings &rarr; Products &rarr; SKU Settings

== Features: ==

1. Automatic generation of SKU when creating products.
2. If an SKU is already registered, generation will not occur again.
3. Automatic generation of SKU for variable products upon saving.
4. Ability to set the number of characters in the SKU.
5. Option to add a prefix before the SKU.
6. Choose SKU format (Numbers only, Letters only, Alphanumeric, Product Template-based).
7. Option to use the product ID in the SKU.
8. Disable/Enable SKU generation for variable products.
9. Special option "Use Previous Product" - generates a product considering the last published product. For instance, if the last published product has SKU 005234 and this option is enabled, the next item will have SKU 005235 (+1 from the previous published product).
10. Added and ready-to-use function: "Bulk generate SKU for all products."
11. Added and ready-to-use new function: "Bulk generate SKU by Category."
12. Option to allow duplicate SKUs in the online store.
13. Added "SKU suffix" option.
14. Added "Additional number" option.
15. Added "Format for Additional number" option.
16. Added "SKU suffix" option.
17. Added 2 formats for generating last numbers.
18. Added a setting for additional options in generating variant products.
19. Added a setting for the separator in variant products when generating SKUs.

== Required Plugins ==
* [WooCommerce](https://wordpress.org/plugins/woocommerce/)

The "Easy Auto SKU Generator for WooCommerce" plugin is fully dependent on the WooCommerce plugin and utilizes its API for SKU generation. This integration enables seamless SKU creation and management within your WooCommerce-powered online store.

===How the plugin works===

The essence of our plugin unfolds through its dual functionality, operating in two distinctive modes. Firstly, it facilitates seamless SKU generation during the creation and editing of products. Secondly, it offers a robust feature for bulk SKU generation, streamlining your workflow. This plugin harnesses the potential of the custom field "_sku" and dynamically populates this field based on the plugin's settings.

Each of these settings will be elaborated upon in the subsequent sections, providing you with a comprehensive understanding of how the plugin operates and optimizes your SKU management process.

At our core, we're here to simplify your plugin experience. Often, right after you install the plugin, you might want to dive into using it for creating or editing products. In this scenario, if you haven't made changes to the settings yet, the plugin will automatically generate SKUs based on random values.

However, if you're aiming for more precise SKU generation, we recommend saving your chosen settings right after installing the plugin. This action not only lets you establish a consistent character count but also solidifies your SKU structure.

===Plugin Option: Characters===

Introducing the transformative capabilities of the "Characters" configuration – your inaugural step towards SKU customization. This foundational setting empowers you to define the exact quantity of characters that will grace your SKUs. As a pivotal determinant, "Characters" enables you to strike the ideal balance between concise representation and informative tagging.

In certain instances, the character count might expand, particularly in light of the plugin's supplementary options that can introduce elements like product IDs or customized prefixes and suffixes. It's noteworthy that the "Characters" parameter stands at the core of generating a diverse array of SKU combinations, instilling each product with its own unique identity.

===Plugin Option: Prefix SKU===

With this capability, you have the freedom to insert a unique prefix that will be showcased at the outset of your SKU. It's like adding a personal touch to your product codes!

Imagine your SKU transforming into a distinct code, effortlessly carrying your brand's identity. For instance, you could enter something like "BN_" and watch it seamlessly blend with the generated SKU. Let's say your product code was "893267" – together, they create a powerful combination like "BN_893267."

This personalized touch not only adds a professional flair to your products but also makes managing and categorizing items a breeze.

===Plugin Option: Select SKU format===

Introducing the "Select SKU Format" option – your gateway to product code personalization. With four distinct choices at your fingertips, you're invited to infuse character into your SKUs. Choose between letters, numbers, a seamless fusion of both, or even embrace your product's unique essence through its slug, as illustrated by the example "your-product."

Imagine the possibilities: from the elegant simplicity of "KSZHGD" or "893267" to the captivating complexity of "7SZ4G2." And here's the twist – when you opt for the product slug format, the previously mentioned "Characters" setting takes a back seat.

===Plugin Option: Add product ID===

Introducing the "Add product ID" option – a game-changer in SKU personalization. When you choose to activate this feature, a product's unique ID seamlessly joins forces with its SKU, creating a dynamic duo of identification.

Here's the twist: if you opt for this integration, the previously set "Characters" count takes a backseat. The product's inherent ID length shapes the SKU's character count, ensuring a harmonious blend of precision and practicality.

Imagine the efficiency of SKU "893267" paired with its corresponding product ID. This option not only streamlines your inventory management but also offers a new level of traceability for each item.

===Plugin Option: Take previous product===

Embark on an innovative journey with the "Take previous product" option – an experimental feature tailored to cater to the unique needs of a select user base. This function takes into account the SKU of the last published product, seamlessly guiding the generation of the SKU for the next product in line.

Please note: As an experimental feature, "Take previous product" is specifically designed for individual use cases. It is important to highlight that this option applies solely during the creation or editing of individual products. It is not applicable to the bulk generation of SKUs.

===Plugin Option: Duplicate SKUs===

Unveil a new level of operational efficiency with the "Duplicate SKUs" feature – an astute solution crafted to streamline your processes and prevent inadvertent errors. This capability provides you the flexibility to assign identical SKUs, offering a practical approach to instances where the same SKU is applicable across multiple products.

By activating this feature, you open the door to a smoother operational landscape. Imagine a scenario where the same SKU effortlessly serves diverse products, saving you valuable time while minimizing the risk of oversights.

Embrace the power of "Duplicate SKUs" – a tool designed to harmonize practicality and productivity. Consider implementing this option, particularly when your inventory encompasses a multitude of items, and you're planning a bulk SKU generation.

===Plugin Option: SKU suffix===

Introducing the "SKU suffix" option – your personal touch to wrap up your SKU creation journey. This feature grants you the creative freedom to append a distinct suffix to the end of each SKU, infusing your products with an extra layer of identity.

Visualize this: your SKU blossoming into a unique code, like the elegant "BN_893267_SUF_." This extra touch brings an unmistakable mark to your products, making them stand out in the digital landscape.

It's important to note that currently, the "SKU suffix" option is available exclusively during bulk SKU generation. However, we're excited to share that its capabilities will soon extend to individual product creation and editing, offering even more versatility in your SKU customization journey.

===Plugin Option: Additional number===

Embark on a journey of SKU personalization, guided by the dynamic "Additional number" feature. This ingenious tool, currently operational during bulk SKU generation, introduces a numeric sequence at the end of your SKU codes, incrementing by +1 with each step.

Imagine this transformation: your SKU evolving into a sequence-rich identifier, such as "BN_893267-001" progressing to "BN_893267-002." This visual narrative imparts a distinctive identity to each product in your inventory.

However, the possibilities don't end there. By incorporating a leading zero (0), you unlock two distinct formatting pathways:

* For example: 008 → 009 → 0010 → 0011
* Alternatively: 008 → 009 → 010 → 011

As you navigate the potential of the "Additional number" feature, remember its seamless compatibility with "SKU Suffix." Merging an evolving numeric sequence with a signature suffix creates a potent formula for SKU codes that seamlessly embody your brand identity.

While "Additional number" currently thrives within the mass SKU generator, we're excited to share that its reach will soon extend to individual product creation and editing. This impending expansion allows you to infuse your SKUs with character and progression, regardless of scale.

===Plugin Option: Enable variant settings===

The "Enable Variant Settings" option opens up a world of additional possibilities for SKU customization. With this feature activated, you gain access to two more options:

* "Variable Product:" This empowers you to control the creation of variant SKUs. You can choose whether or not to generate SKUs for your variable products.
* "Variation Separator:" You have the freedom to define a separator character, such as "/", "", "|", "-", "--", ".", "&", "#", "$", "@", or even a special prefix like "var". This separator enhances the organization and structure of your SKU variations.

Currently, variant products are generated sequentially based on the main SKU, resulting in patterns like 893267-01, 893267-02, 893267-03. However, rest assured that we're diligently working to expand the functionality of variant SKU generation. Exciting changes are on the horizon, offering you even more versatile options for SKU customization.

==Bulk SKU Generation: Unleash Efficiency and Precision==

Experience the power of effortless SKU management with our Bulk SKU Generation feature. Now, you have two dynamic options at your disposal:

* **Bulk Generate SKU for All Products:** Seamlessly generate SKUs for your entire product catalog in one swift action.
* **Bulk Generate SKU by Category:** Tailor your SKU generation to specific categories, allowing for a more targeted approach.

Both variants of bulk SKU generation operate in harmony with your plugin settings. Simply configure your preferences, save the settings, and watch as the SKUs come to life. But there's more to explore!

As you engage the SKU generation process, you'll encounter the "Re-create existing SKUs?" option. When selected, this option recreates all SKUs, ensuring a comprehensive update. If left unselected, only missing SKUs will be generated, preserving existing codes.

In the "Bulk Generate SKU by Category" generator, you'll find a comprehensive selection of categories. This empowers you to fine-tune SKU generation for specific sets of products. For those seeking enhanced category options, here's a strategic approach:

1. Choose your settings.
2. Save your preferences.
3. Generate SKUs for one category.
4. Modify settings.
5. Generate SKUs for another category.

Unleash the potential of Bulk SKU Generation and streamline your inventory management with precision. For inquiries or to delve deeper, please reach out to our dedicated support form on our website.

== Great thanks ==

* Thanks for the help [KAGG Design](https://profiles.wordpress.org/kaggdesign/)
* Thanks for helping the developer [Artem Abramovich](https://profiles.wordpress.org/artabr/)
* For help [Telegram chat "WordPress & WooCommerce" and all participants](https://t.me/c_wordpress)
* For the best documentation in Russian by WordPress [Site wp-kama.ru](https://wp-kama.ru/)

== Translations ==

If you wish to help translate this plugin, you are most welcome!
To contribute, please visit [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/easy-woocommerce-auto-sku-generator/)

== Small Gifts, Big Impact: Support Plugin Growth with Your Donations ==

Thank you all for your incredible support – it truly fuels my motivation to continuously enhance our plugins! Every bit of encouragement and feedback means the world to me. Your insights help shape the future of our WordPress tools, driving them to be even more powerful and user-friendly.

Remember, a single review from you has an immense impact on the journey of our creations. Your words inspire me to refine and innovate, and I'm dedicated to delivering the best possible experiences through every line of code.

If you'd like to contribute further or show your appreciation, you can now do so through this new donation link: <a href="https://boosty.to/dan-zakirov/donate" target="_blank">Boosty Donation Link</a>. Your generosity will undoubtedly make a difference in pushing our WordPress projects to new heights.

Thank you for being a vital part of this incredible journey! 🚀🙌

== Elevate Your Experience: Paid User Support and Subscription ==

Due to the lack of available time for free plugin support, a decision has been made to introduce paid user support. Throughout my years of experience in web development, I have accumulated enough expertise to assist other users with various inquiries.

By subscribing to Dan Zakirov's blog on Boosty at <a href="https://boosty.to/dan-zakirov" target="_blank">boosty.to/dan-zakirov</a>, you'll not only gain access to paid support for the "Easy Auto SKU Generator for WooCommerce" plugin but also a comprehensive range of consultations related to WooCommerce. Over the years, I have amassed numerous ready-made solutions, a variety of other plugins, and WordPress templates covering different aspects of WooCommerce and beyond.

Additionally, the blog will feature interesting articles and reviews on different plugins. If you provide an original idea, I will strive to develop an entire plugin, and together we can work on advancing specific directions.

== Installation ==

This section describes how to install the plugin and get it working.

Install From WordPress Admin Panel:

1. Login to your WordPress Admin Area
2. Go to Plugins -> Add New
3. Type "**Easy Auto SKU Generator for WooCommerce**" into the Search and hit Enter.
4. Find this plugin Click "install now"
5. Activate The Plugin

Manual Installation:

1. Download the plugin from WordPress.org repository
2. On your WordPress admin dashboard, go to ‘Plugins -> Add New -> Upload Plugin’
3. Upload the downloaded plugin file and click ‘Install Now’
4. Activate ‘**Easy Auto SKU Generator for WooCommerce**’ from your Plugins page.

== Frequently Asked Questions ==

= Can I contribute to the improvement of the plugin? =

Sure! You can leave a request on the user [support forum](https://wordpress.org/support/plugin/easy-woocommerce-auto-sku-generator/). We will consider any proposal and teach any criticism.

= Bulk SKU generation for all products stops and does not work correctly - what should I do? =

Some users have problems with mass SKU generation. Today I can say with confidence that the plugin has been fixed in relation to this function, we decided this in [this thread on the user support forum](https://wordpress.org/support/topic/mass-creation-crashed/).

**So what if mass creation ceases?**

1. Be sure to update the plugin to the latest version

2. On the settings page in a browser, open DevTools and on the console tab check for errors. If you have any errors, be sure to let us know in the user support forum. If you see a 500 error, this is a server error, and you need to get your server logs to determine the error.

3. If you know how to do this, then look at the server logs, there are all the errors. Write us about the error on the [user support forum](https://wordpress.org/support/plugin/easy-woocommerce-auto-sku-generator/).

4. Contact the technical support service of your hosting provider and explain the cause of the problem. Find out where the server logs are located (if you do not know which log is stored). Ask to fix the problem, maybe they will fix it.

Only after we see the server logs, we can find out the reason for the script to work incorrectly. You can find an alternative to our plugin, but keep in mind that something on your server is not working correctly.

Be sure to write to the [user support forum](https://wordpress.org/support/plugin/easy-woocommerce-auto-sku-generator/) if the error has been fixed. There are other people who will use our plugin and need to be properly informed.

== Screenshots ==

1. Format SKU
2. SKU Settings
3. Where to find the settings?
4. Support forum and field SKU
5. SKU generation options
6. Bulk generate SKU for all products
7. Bulk generate SKU by Category
8. Bulk generate SKU by Category
9. The process of generating SKU of all products
10. Completion of the SKU generation process for all products
11. Future plans

== Upgrade Notice ==

The latest plugin update version 1.1.9 has the following changes:

* New readme
* Checked compatibility with WordPress 6.4
* Checked compatibility with WooCommerce 8.0
* Variant product separator is now available when editing and adding a product
* Fixed recreation of the existing SKU variant of a product.

Additional list of changes can be found here - https://wordpress.org/plugins/easy-woocommerce-auto-sku-generator/#developers.

== Changelog ==

= 1.2.0 =
* Added new information to plugin settings.

= 1.1.9 =
* When editing or creating a product, a suffix is now appended.
* When editing or creating a product, the number specified in the "Additional number" settings is now added.

= 1.1.8 =
* Added High-Performance Order (HPOS) support
* Tested with the latest version of WooCommerce

= 1.1.7 =
* Update JavaScript settings
* Update readme
* Added subscription

= 1.1.6 =
* Update readme
* New donate link

= 1.1.5 =
* Tested compatibility with WordPress 6.3
* Tested compatibility with WooCommerce 8.0
* New readme
* The delimiter is now available when editing and adding a product
* Fixed re-creation of already existing SKU of a variant product

= 1.1.4 =
* Variant SKU customizations are now hidden in a separate group
* Preparing for global plugin update has been implemented
* Added "SKU suffix" option
* Added "Additional number" option
* Added "Format "Additional number" option
* Added "SKU suffix" option
* Added 2 formats for generating last numbers

= 1.1.3 =
* Tested compatibility with WordPress 6.2
* Tested compatibility with WooCommerce 7.9
* Added a setting for additional options in generating variant products.
* Added a setting for the separator in variant products when generating SKU.

= 1.1.2 =
* Tested compatibility with WordPress 5.9
* Tested compatibility with WooCommerce 5.8.3
* Changed SKU generator progress indicator
* Add style generator SKU

= 1.1.1 =
* Added compatibility with the "Table Rate Shipping Method for WooCommerce by Flexible Shipping" plugin
* CSS class of the modal window is now unique. Added compatibility with other plugins

= 1.1.0 =
* Fixed bug with disabling SKU block

= 1.1.2 =
* Added new option "Allow identical SKUs"

= 1.0.8 =
* Fixed an error generating variant products
* Changed the order of execution of the variable products generator script
* Fixed getting a basic SKU in relation to variable products in the SKU generator

= 1.0.7 =
* Fixed bug with SKU generation by slug of product

= 1.0.6 =
* Tested WP version 5.8

= 1.0.5 =
* Tested WP version

= 1.0.4 =
* Correction of error with number 0

= 1.0.3 =
* The limitation on the generation of the minimum number of characters has been removed

= 1.0.2 =
* Rename function "ffxf_action_javascript"

= 1.0.1 =
* Plugin tested with WordPress version 5.5

= 1.0.0 =
* Tested WP 5.4

= 0.9.9 =
* Fixed a bug that was caused due to duplication of SKU

= 0.9.8 =
* Update notice

= 0.9.7 =
* Added new functions "Bulk generate SKU by Category"
* Bugs fixed with the function of the previous product
* Take previous product has become more convenient
* Update CSS
* Update JS

= 0.9.6 =
* Update CSS
* Preparation for the introduction of a new parameter - Generation of SKU into separate categories.

= 0.9.5 =
* Fix error in notice

= 0.9.4 =
* Test WordPress 5.3

= 0.9.3 =
* Fixed bug with mass generation

= 0.9.2 =
* Re-create online button is now always available
* New support forum notification added

= 0.9.1 =
* WooCommerce test 3.8.0
* Optimize code
* Add notice

= 0.9.0 =
* fix translate and add text-domain in generate SKU

= 0.8.9 =
* update CSS

= 0.8.8 =
* fix translate

= 0.8.7 =
* fix translate

= 0.8.7 =
* fix translate selector

= 0.8.6 =
* fix missing dependencies ffxf_slug_script.js

= 0.8.5 =
* Now, after installing the plugin, you can immediately generate products without saving the general settings.
* New POT file
* Fixed text domain of the translation plugin

= 0.8.4 =
* Added and ready to use a new function "Bulk generate SKU for all products"
* New function “Bulk generate SKU by Category” prepared for implementation
* New function “Bulk generate SKU by Attributes” prepared for implementation
* New function “Bulk generate SKU by product tags” prepared for implementation
* New interface added

= 0.8.3 =
* Changed the main banner so as not to infringe on Woocommerce copyright
* In the latest version of plugin 0.8.3, preparations were made for implementing a function that generates SKUs for all products bulk. 

= 0.8.2 =
* Fixed numerical values

= 0.8.2 =
* Test numerical values and fix error

= 0.8.1 =
* Test numerical values

= 0.8.1 =
* Test numerical values
* Added a new function for converting SKU numbers of previously published products
* New notification added in case of error or failure

= 0.8.0 =
* Fix error id SKU and all option

= 0.7.9 =
* New feature refinement - "Consider the previous product"

= 0.7.8 =
* Improvement of the function "Consider the previous product"
* Fixed bugs with zeros
* Using the new function, the article can now be rewritten
* New styles added

= 0.7.7 =
* Add new functions "Take into account the previous product" 

= 0.7.6 =
* Product ID is now at the end SKU

= 0.7.5 =
* Fix JS error

= 0.7.4 =
* Fixed script connection

= 0.7.3 =
* Optimized code

= 0.7.2 =
* Fix JS error

= 0.7.1 =
* Fix error slug SKU

= 0.7.0 =
* Add new settings - Product Slug Generation
* Add Re-Create SKU online
* Optimized code

= 0.6.0 =
* Optimized settings code

= 0.5.0 =
* Fixed problems with literal values

= 0.4.0 =
* Added settings in ‘Woocommerce &rarr; Settings &rarr; Products &rarr; SKU Settings’
* Added option - Number of characters in SKU
* Added option - Prefix before SKU
* Added option - SKU format (Only numbers, Only letters, Letters and numbers)
* Added option - Use product ID in SKU
* Added option - Disable / Enable generation of SKU in variable goods
* Update generation function sku

= 0.3.0 =
* Update generation function sku

= 0.2.0 =
* Release