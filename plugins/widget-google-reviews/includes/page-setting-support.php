<div class="grw-flex-row">
    <div class="grw-flex-col">
        <div class="grw-support-question">
            <h3>I connected and displayed Google reviews, but after a while these are not updated, why?</h3>
            <p>First of all, please check that you have created and saved in the plugin's settings your own Google API key, it's not a Place ID. If you didn't create it in Google console, your connected reviews are not updated automatically daily. Please read and done this 5 minutes instruction <a href="<?php echo admin_url('admin.php?page=grw-support&grw_tab=fig#fig_api_key'); ?>">how you can create your Google API key</a> and then save created API key to the plugin's settings.</p>
        </div>
    </div>
    <div class="grw-flex-col">
        <div class="grw-support-question">
            <h3>I can't connect my Google Place.</h3>
            <p>Please check that you correctly found the Place ID of your Google business. It should look like <b>ChIJ</b>3TH9CwFZwokRI... This instruction helps to find any Place ID regardless of whether it has a physical address or it is an area: <a href="<?php echo admin_url('admin.php?page=grw-support&grw_tab=fig#place_id'); ?>">how to find Place ID of any Google business</a></p>
        </div>
    </div>
</div>
<div class="grw-flex-row">
    <div class="grw-flex-col">
        <div class="grw-support-question">
            <h3>Why I see only 5 Google reviews?</h3>
            <p>The plugin uses the Google Places API to get your reviews. The API only returns the 5 most helpful reviews. When Google changes the 5 most helpful the plugin will automatically add the new one to your database. Thus slowly building up a database of reviews. It's a limitation of Google, not specifically the plugin.</p>
            <p>The plugin can only download what Google returns in their Places API. It is usually the 5 Most Helpful (not newest) reviews. You can check what the API returns by entering your Place ID and Goolge API key in this url:</p>
            <code>https://maps.googleapis.com/maps/api/place/details/json?placeid=YOUR_PLACE_ID&key=YOUR_GOOGLE_API_KEY</code>
            <p>However, if you got new reviews for your Google place, but the plugin does not show these, it means that Google didn't include it to 5 most helpful and the plugin just can't get this. It's a limitation of Google, not the plugin.</p>
            <p>Also, please check that the 'Refresh' option is enable in the widget. It will call the Google API once in three days (to avoid a Google Billing and keeps the API key is free) to check the new most helpful reviews.</p>
        </div>
    </div>
    <div class="grw-flex-col">
        <div class="grw-support-question">
            <h3>I have some error messages about the Google API key.</h3>
            <p>Please make sure that your correctly created the Google Places API key with <b>Places API library and without any restrictions (IP or Referrer)</b>. It should look like <b>AIzaS</b>yB3k4oWDJPF... On the <b>Settings</b> tab there is a detailed instruction and video tutorial how to create the free and correct Google Places API key.</p>
        </div>
    </div>
</div>
<div class="grw-flex-row">
    <div class="grw-flex-col">
        <div class="grw-support-question">
            <h3>Is the plugin compatible with the latest version of PHP? I saw warnings about the compatibility with PHP 7 in the checker plugin.</h3>
            <p>Yes, the plugin is absolutely compatible with PHP 7 and by the way, we are using PHP 7 on the demo site.</p>
            <p>The warnings come from the code which is needed for compatible with old versions of PHP (&lt; 5.0) which is sometimes found in some users and without this code, the plugin will not work.</p>
            <p>The problem is that the plugin which you’re using to test compatibility with PHP 7 cannot understand that this code is not used under PHP 7 or greater. The compatibility plugin just search some keywords which deprecated in the latest version PHP and show warnings about it (without understanding that this is not used).</p>
        </div>
    </div>
    <div class="grw-flex-col">
        <div class="grw-support-question">
            <h3>If you still need support</h3>
            <p>You can contact us directly by email <a href="mailto:support@richplugins.com">support@richplugins.com</a> and would be great and save us a lot of time if each request to the support will contain the following data:</p>
            <ul>
                <li><b>1.</b> Clear and understandable description of the issue;</li>
                <li><b>2.</b> Direct links to your reviews on: Google map;</li>
                <li><b>3.</b> Link to the page of your site where the plugin installed;</li>
                <li><b>4.</b> Better if you attach a screenshot(s) (or screencast) how you determine the issue;</li>
                <li><b>5. The most important:</b> please always copy & paste the DEBUG INFORMATION from the <b>Advance</b> tab.</li>
            </ul>
        </div>
    </div>
</div>