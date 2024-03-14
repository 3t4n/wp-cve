<div class="icon32" id="icon-themes"><br></div>
<div id="moonclerk-wp-page" class="wrap">

  <h2>Instructions</h2>

  <p>Accept recurring payments and one-time payments on your WordPress website by embedding MoonClerk payment forms
    into pages and posts via shortcodes.</p>

  <p>You’ll need a MoonClerk account to use this plugin.</p>

  <p>Once you’ve created a payment form in your MoonClerk account, copy the payment form’s short code.</p>

  <p><img src="<?php echo plugin_dir_url(__FILE__); ?>/screenshot-1.png" alt="Moonclerk.com use page"/></p>

  <h2>Adding a Shortcode</h2>

  <p>To add a payment form to your WordPress website using the shortcode: <code>[moonclerk]</code></p>

  <p>For example, <code>[moonclerk id="12cal5jxfte"]Buy Now[/moonclerk]</code> is the short code that will add the form with the ID 12cal5jxfte for the WordPress site.</p>

  <p>Just copy and paste your shortcode into the WordPress text editor where your blog content goes.</p>

  <p><img src="<?php echo plugin_dir_url(__FILE__); ?>/screenshot-2.png" alt="Moonclerk.com use page"/></p>

  <h2>Developers</h2>

  <p>To add a payment form to your WordPress website using its shortcode:</p>

  <p><code>[moonclerk id="your_form_id" tab="true" class="your_class"]Link to form[/moonclerk]</code></p>

  <ol>
    <li>The "id" attribute is for the MoonClerk form ID.</li>
    <li>The "tab" attribute should be set to true and opens the form in a new tab is the js fails.</li>
    <li>The "class" attribute let you define the HTML class attribute for the "div" tag containing the form.</li>
  </ol>

</div>