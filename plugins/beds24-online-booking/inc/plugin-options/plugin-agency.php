<h1>Multiple Properties</h1>
<h2>Multiple Property booking page</h2>
<p>To display a multiple property booking page enter you owner ID in the "Booking Page" section of the plugin setting. Make sure you leave the property field blank. </p>
<h2>Multiple Property booking widgets</h2>
<p>You can generate the short codes for multiple properties directly in the widget designer in the Beds24 control panel in SETTING >BOOKING  ENGINE >BOOKING WIDGETS setting the selector for "Property" = All. Copy the short code from there and paste it where you want to display the widget.</p>

<p>If you want to allow guests to search by certain criteria:</p>

<ul style="list-style-type: disc; margin-left: 30px;">
<li>first create the search criteria in SETTINGS > BOOKING ENGINE >MULTI BOOKING PAGE > SEARCH CRITERIA
then go to SETTINGS > BOOKING ENGINE >MULTI BOOKING PAGE  PROPERTY SETTINGS and assign the applicable criteria to each property</li>
<li>Once you have set and assigned your search criteria you can go to SETTINGS > BOOKING ENGINE > BOOKING WIDGETS. Set PROPERTY = ALL and design your widget, ensuring that you change SEARCH LABEL to show the search criteria in your booking widget.</li>
<li>Generate the code for the widget and paste it into the source code (Set the Wordpress editor to "Text") where you want to display the widget. </li>
</ul>


<h2>Posts</h2>
<div class="b24_section section-text">
<p>The plugin can use posts or XML to display availabilty for multiple hotels or lodgings and allows search by the search criteria defined in thSETTINGS > BOOKING ENGINE >MULTI BOOKING PAGE  SEARCH CRITERIA section of Beds24.</p>
<p><strong>1.1 Agency Searchbox </strong></p>
<p>You can embed a searchbox into your Wordpress site. The searchbox will return your live availability. The results can be shown on the same or a different page or in a pop up.</p>
<p>Use shortcodes to add a searchbox in a post, page or sidebar.</p>
<table width="900" cellspacing="0" cellpadding="2" border="1">
<tbody>
<tr>
<td>1</td>
<td width="50%">searchbox displaying the search results on the same page</td>
<td>[beds24-searchbox]</td>
</tr>
<tr>
<td></td>
<td width="50%">insert this code where you want the search results to display</td>
<td>[beds24-searchresult display="none"]</td>
</tr>
</tbody>
</table>
<br>
<br>
<table width="900" cellspacing="0" cellpadding="2" border="1">
<tbody>
<tr>
<td>2</td>
<td width="50%">searchbox displaying the search results on a defined page page. If you want to show the results on another page add the url.</td>
<td>[beds24-searchbox href="http://mywebsite/wordpress/?page_id=xy"]</td>
</tr>
<tr>
<td></td>
<td width="50%">insert this code where you want the search results to display</td>
<td>[beds24-searchresult];</td>
</tr>
</tbody>
</table>
<p><strong>1.2 Search Criteria</strong></p>
<p>To set up the agency searchbox with your search criteria you need to modify the file /plugins/beds24-online-booking/theme-files/beds24-searchbox.php to use the search criteria you set up in Beds24 SETTINGS > BOOKING ENGINE >MULTI BOOKING PAGE > SEARCH CRITERIA. There are comments in the file about the naming requirements for the select and checkbox elements. If you need assistance please contact us. </p>
<p><strong>2.1 XML Display</strong></p>
<p>By default the plugin uses the file plugins/beds24-online-booking/theme-files/ beds24-prop-xml.php to display the property results using the information returned by the search. This file can be customised to change the display of the information.</p>
<strong>2.2 Post Display</strong>
<br>
<p>As an alternative you can display the results using Wordpress posts for each property. Custom fields are used to connect posts to your Beds24 account.</p>
<ol>
<li>Add a custom field </li>
<li>As "Name" enter the phase: propid </li>
<li>As value enter the Beds24 property Id number for this lodging. You will find this in the Beds24 control panel at SETTINGS > PROPERTIES > DESCRPTION.</li>
<li>To add a booking widget to the post add a widget shortcode e.g. [beds24-button]</li>
</ol>
<p>The plugin will use the file plugins/beds24-online-booking/theme-files/ beds24-prop-post.php to display the property results using the information returned by the search. This file can be customised to change the display of the information. If the propid is not found in the custom field the XML Display will be used for that property. </p>
</div> 
