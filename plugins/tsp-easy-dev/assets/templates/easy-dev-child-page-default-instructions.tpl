<h5>Settings</h5>
<p>Settings are used strategically throughout the CMS for various implementations. For example, entering in Google adwords or analytics code automatically places the codes at the footer and header of page respectfully. After you supply setting values, no further action needs to take place.</p>
<hr width="100%">
<h5>Shortcode</h5>
<p>Changing the default shortcode options allows you to place <code>[{$plugin_name}]</code> shortcode tag into any post or page with the default options.</p>
<p>However, if you wish to add different options to the <code>[{$plugin_name}]</code> shortcode please use the following settings:</p>
<br>
<h6><u>Short Code Page Type</u></h6>
<ul style="list-style-type:square; padding-left: 30px;">
    <li>Page Type: <code>page_type="weekly_ad"</code>(Options: weekly_ad, deal_month, nrg[Natural Resource Guide])</li>
</ul>
<br>
<h6><u>Weekly Ad Short Codes</u></h6>
<ul style="list-style-type:square; padding-left: 30px;">
    <li>Ad Type: <code>ad_type="flipp"</code>(Options: flipp, pinch-zoom, pdf)</li>
</ul>
<br>
<h6><u>Natural Resource Guide (NRG) Short Codes</u></h6>
<ul style="list-style-type:square; padding-left: 30px;">
    <li>NRG Title: <code>title="Natural Resource Guide"</code></li>
    <li>NRG Headline: <code>headline="Some Headline"</code></li>
    <li>NRG Copy: <code>copy="Some information related to the natural resource guide."</code></li>
</ul>
<hr>
A shortcode with all the options will look like the following:<br><br>
<h6><u>Weekly Ad Short Codes</u></h6>
<code>[{$plugin_name} page_type="weekly_ad" weekly_ad_type="flipp"]</code><br>
<br>
<h6><u>Natural Resource Guide (NRG) Short Codes</u></h6>
<code>[{$plugin_name} page_type="nrg" title="Natural Resource Guide" headline="Some Headline" copy="Some information related to the natural resource guide."]</code>