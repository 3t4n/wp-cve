<h1>Short Codes</h1>
<div class="b24_section section-text">
<p>Shortcodes  add a booking widget to a post, page or sidebar. Paste the shortcode where you want to display the widget.  </p> 
    
<h3 class="b24_heading">Embedded Booking Page</h3>
<table width="900" cellspacing="0" cellpadding="2" border="1">
<tbody>
<tr>
<th width="50%">Description</th>
<th>Shortcode</th>
</tr>
<tr>
<td width="50%">Embedded booking page</td>
<td>[beds24-embed]</td>
</tr>
<tr>
<td width="50%">Embedded booking page opened from a widget</td>
<td>[beds24-landing]</td>
</tr>
</tbody>
</table>
<p>Use [beds24-landing] if you redirect to the booking page from a widget.</p>        
    
<h4>Embedded booking page for  a specific property</h4>
<p>To display a booking page for a specific property add the  property ID to the short code.</p>
<p>Examples:</p>
<p>[beds24-embed propid="1234"]<br>
[beds24-landing propid="13434"]
</p>
    
<h4>Embedded booking page for a specific room</h4>
<p>To display a booking page for a specificproperty add the  room ID to the short code.</p>
<p>Examples:</p>
<p>[beds24-embed roomid="1234"]<br>
[beds24-landing roomid="13434"]
</p>
    
<h4>Embedded booking page for multiple properties</h4>
<p>To display a multip property booking page add the owner ID to the short code.</p>
<p>Examples:</p>
<p>[beds24-embed ownerid="123"]<br>
[beds24-landing ownerid="123"]
</p>
    
</div>

<h3 class="b24_heading">Booking Widgets</h3>
<div class="b24_section section-text">
    
<h4>Booking Box, Booking Strip, Availability Calendar</h4>   
<p>Generate the short code with the Widget Desinger in Beds24 in SETTINGS > BOOKING ENGINE > BOOKING WIDGETS.</p> 
</div>
  
<h4>Booking Button, Booking Link</h4> 
<table width="900" cellspacing="0" cellpadding="2" border="1">
<tbody>
<tr>
<th width="50%">Description</th>
<th>Shortcode</th>
</tr>
<tr>
<td width="50%">Booking button</td>
<td>[beds24-button]</td>
</tr>
<tr>
<td width="50%">Booking link</td>
<td>[beds24-link]</td>
</tr>
</tbody>
</table>

    
<p>Customisation Options for button and link</p>
Note: The availabilty calendar, booking box and booking strip are customized directly in the Widget Designer in SETTINGS > BOOKING ENGINE > BOOKING WIDGETS.
<table>
<?php
$options = array();
$options['iframe'] = 'iframe';
$options['window'] = 'same window';
$options['new'] = 'new window';
?>
<tr valign="top">
<td style="width: 160px; padding: 5px 5px 7px 5px;">Target:</td>
<td style="padding: 5px 5px 7px 5px;">
<select name="beds24_target" id="beds24_target">
<?php foreach ($options as $key => $val) { ?>
<option value ="<?php echo $key; ?>" <?php if(get_option('beds24_target') == $key) echo "selected"; ?>><?php echo $val; ?></option>
<?php } ?>
</select>
</td>
<td style="padding: 5px 5px 7px 5px;">
<span style="font-style: italic; color: gray;"> The target to open the booking form. "iframe" will open the booking page as an iframe on the same page. If you want the widget to open an embedded booking page on your website choose "iframe" and add the url of the target page href="http://www.myurl.com" to the shortcode.</span>
</td>
</tr>
<tr valign="top">
<td style="padding: 5px 5px 7px 5px;">Text colour:</td>
<td style="padding: 5px 5px 7px 5px;">
<input type="text" name="beds24_color" value="<?php echo get_option('beds24_color'); ?>" class="my-color-field" />
</td>
    <td style="padding: 5px 5px 7px 5px;"> <span style="font-style: italic; color: gray;"></span>
</td>
</tr>
<tr valign="top">
<td style="padding: 5px 5px 7px 5px;">Background Colour:</td>
<td style="padding: 5px 5px 7px 5px;">
<input type="text" name="beds24_bgcolor" value="<?php echo get_option('beds24_bgcolor'); ?>" class="my-color-field" />
</td>
    <td style="padding: 5px 5px 7px 5px;"> <span style="font-style: italic; color: gray;"></span>
</td>
</tr>
<tr valign="top">
<td style="padding: 5px 5px 7px 5px;">Padding:</td>
<td style="padding: 5px 5px 7px 5px;">
<select name="beds24_padding" id="beds24_padding">
<?php for($i = 0; $i <= 100; $i += 1) { ?>
<option value ="<?php echo $i; ?>" <?php if(get_option('beds24_padding') == $i) echo "selected"; ?>><?php echo $i; ?>px</option>
<?php } ?>
</select></td>
<td style="padding: 5px 5px 7px 5px;"> <span style="font-style: italic; color: gray;"></span>
</td>
</tr>
<tr valign="top">
<td style="padding: 5px 5px 7px 5px;"></td>
<td colspan="2" style="padding: 5px 5px 7px 5px;">
</td>
</tr>
</table>

<p>Parameters for for [beds24-button] and [beds24-link]</p>
<p>If you use multiple widgets and want to style each of them individually you can do this using parameters. Change the values and enter the parameters into the shortcode. For a complete list of all parameters go to DOCUMENTATION.</p>
<p>Example:  [beds24-button href="http://www.myurl.com"] if you want a booking button opening you embedded booking page.</p>
<p>Example: [beds24-link roomid="6661"] will make a booking link for room 6661. </p>
<p>Example: [beds24-button text="Check Availability"] will change the text on the button to <i>Check Availability</i>. </p>

