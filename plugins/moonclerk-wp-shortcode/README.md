== MoonClerk WP Shortcode

= About 

Accept recurring payments and one-time payments on your WordPress website by embedding MoonClerk payment forms into pages and posts via shortcodes.

You’ll need a MoonClerk account to use this plugin.

Once you’ve created a payment form in your MoonClerk account, copy the payment form’s short code. 

= Adding a Shortcode =

To add a payment form to your WordPress website using the shortcode: `[moonclerk]`

For example, `[moonclerk id="12cal5jxfte"]Buy Now[/moonclerk]` is the short code that will add the form with the ID 12cal5jxfte to the WordPress site.

Just copy and paste your shortcode into the WordPress text editor where your blog content goes.

= Help 

Plugin help page is located under "Tools" in the admin once it is activated.

= Developers 

`[moonclerk id="your_form_id" tab="true" class="your_class"]Link to form[/moonclerk]`

* The "id" attribute is for the MoonClerk form ID.
* The "tab" attribute should be set to true and opens the form in a new tab is the js fails.
* The "class" attribute let you define the HTML class attribute for the "div" tag containing the form.

== Installation 

1. Upload the wordpress-moonclerk-embeds plugin to your blog 
2. Activate it!
2. Start adding short codes to pages and posts.
