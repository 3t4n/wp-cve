<p>To correctly use this plugin you need to get two things - <b><u>Google Place ID</u></b> and <b><u>Google Places API key</u></b>. These are two different values, please do not confuse them.</p>

<p style="background: #eee; padding: 4px 10px; border-radius: 4px; border: 1px solid #ccc; font-size: 16px; font-weight: 600; text-align: center; line-height: 32px; color: #304cd3;">There is nothing complicated and the whole process will not take even 5 minutes,<br>and as a reward you receive a constantly updated and collected Google Rating and Reviews!</p>

<hr></hr>

<h3 id="place_id"><u>I. Google Place ID</u></h3>

<p><b>First of all, you need to find Google Place ID:</b> this is the identification of your Google Place (business). It should be like that: <b>ChIJ..</b>, for instance: ChIJ3TH9CwFZwokRIvNO1SP0WLg. If you know it, go to <a href="#fig_api_key">How to create Google API key</a></p>
<p><b>Please keep in mind: your Google Place must have a physical address</b>, because Google Place API, which is used in this plugin, works only with a phisical Google places, it's not possible to connect area or virtual place. Unfortunately, it's a limitation of Google, not specifically the plugin.</p>
<p>The standard way to find your Google Place ID is to go to <a href="https://developers.google.com/places/place-id" target="_blank">https://developers.google.com/places/place-id</a> and search for your company name. But sometimes it just doesn’t work.</p>

<h3 class="subheader">How To Find Any Google Place ID…</h3>

<div class="grw-flex-row">
    <div class="grw-flex-col3">
        <b>1</b> Search for your business on Google.
    </div>
    <div class="grw-flex-col7">
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_placeid_1.png'; ?>"></div>
    </div>
</div>

<div class="grw-flex-row">
    <div class="grw-flex-col3">
        <b>2</b> Inspect the “<b>Write a Review</b>” button. To do this in Firefox, right-click and choose “<b>Inspect Element</b>“. In Chrome, right-click and choose “<b>Inspect</b>“. (Most browsers follow a similar process.)
    </div>
    <div class="grw-flex-col7">
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_placeid_2.png'; ?>"></div>
    </div>
</div>

<div class="grw-flex-row">
    <div class="grw-flex-col3">
        <b>3</b> Find “<b>data-pid</b>” as shown above. (This part is a little tricky, but just look inside the <b>&lt;a&gt;</b> tag until you find <b>data-pid=</b>).
    </div>
    <div class="grw-flex-col7">
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_placeid_3.png'; ?>"></div>
    </div>
</div>

<div class="grw-flex-row">
    <div class="grw-flex-col3">
        <b>4</b> Copy the characters within the quotes (as shown above). You now have your google Places ID.  Paste this somewhere you can easily find it.
    </div>
</div>

<hr></hr>

<h3 id="fig_api_key"><u>II. Google Places API key</u></h3>

<div class="grw-flex-row">
    <div class="grw-flex-col">
        <b>1</b> Go to your <a href="https://console.developers.google.com/apis/dashboard?pli=1" target="_blank">Google Console</a> dashboard. If you new user agree Google terms:
    </div>
    <div class="grw-flex-col">
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_key_1.png'; ?>"></div>
    </div>
</div>

<div class="grw-flex-row">
    <div class="grw-flex-col">
        <b>2</b> Select your existing project or create new:
    </div>
    <div class="grw-flex-col">
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_key_2.png'; ?>"></div>
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_key_2_1.png'; ?>"></div>
    </div>
</div>

<div class="grw-flex-row">
    <div class="grw-flex-col">
        <b>3</b> Wait for creation and then click '<b>ENABLE APIS AND SERVICES</b>':
    </div>
    <div class="grw-flex-col">
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_key_3.png'; ?>"></div>
    </div>
</div>

<div class="grw-flex-row">
    <div class="grw-flex-col">
        <b>4</b> Type '<b>Places API</b>' in the search area, select the first result '<b>Places API</b>' and click '<b>ENABLE</b>' button:
    </div>
    <div class="grw-flex-col">
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_key_4.png'; ?>"></div>
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_key_4_1.png'; ?>"></div>
    </div>
</div>

<div class="grw-flex-row">
    <div class="grw-flex-col">
        <b>5</b> After enable the Places API, click '<b>Navigation menu</b>', select '<b>APIs & Services</b>' and  '<b>Credentials</b>':
    </div>
    <div class="grw-flex-col">
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_key_5.png'; ?>"></div>
    </div>
</div>

<div class="grw-flex-row">
    <div class="grw-flex-col">
        <b>6</b> On Credentials page click '<b>+ CREATE CREDENTIALS</b>' and select '<b>API key</b>':
    </div>
    <div class="grw-flex-col">
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_key_6.png'; ?>"></div>
    </div>
</div>

<div class="grw-flex-row">
    <div class="grw-flex-col">
        <b>7</b> After creation of API key you need to copy & paste it to the plugin's settings (<b>please do not restrict the key</b>, the plugin can't work with a restricted API key, it's a limitation of Google):
    </div>
    <div class="grw-flex-col">
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_key_7.png'; ?>"></div>
    </div>
</div>

<div class="grw-flex-row">
    <div class="grw-flex-col">
        <b>8</b> Now, you need to enable Google Billing. It's mandatory step to use the API key. <b>Don’t worry, <a href="https://developers.google.com/maps/billing-credits" target="_blank">Google is currently giving $200 free credit a month</b></a> and it should be enough to use the plugin for connecting several Google places without additional fees except this free $200 credits. Go to <a href="https://console.cloud.google.com/project/_/billing/enable" target="_blank">https://console.cloud.google.com/project/_/billing/enable</a>, select your recent created project and click '<b>CREATE BILLING ACCOUNT</b>':
    </div>
    <div class="grw-flex-col">
        <div class="box-img"><img src="<?php echo GRW_ASSETS_URL . 'img/google_key_8.png'; ?>"></div>
    </div>
</div>

<div class="grw-flex-row">
    <div class="grw-flex-col">
        <b>9</b> Agree Google terms, fill your contact information and start using your Google API key with the plugin.
    </div>
</div>

<p style="text-align:center"><b>Congratulation! Now you need to <a href="<?php echo admin_url('admin.php?page=grw-builder'); ?>" target="_blank">create your first widget and connect Google reviews</a>,<br>then you can show this widget in a sidebar or like a shortcode on the any page of your website.</b></p>
