<br />

<span style='color: #f00; font-weight: bold;'>Not all functions described in the Help screens are available in the Lite edition of WP Wizard Cloak. Help screens also describe functionality found in the Professional edition of WP Wizard Cloak. </span>

<br /><br />

<b>Link Name & Slug</b>


<p>
In this section, enter a short, easy-to-remember Link Name so you can identify the link on the Manage Links page. It is not generally shown to visitors to your site.
</p>

<p>
The Link Slug appears at the end of the cloaked URL. To setup pretty URLs (<code>http://www.example.com/slug</code> instead of <code>http://www.example.com/?cloaked=slug</code>), navigate to the <b>Settings -> Permalinks</b> screen and choose <i>Day and name</i> for your permalink structure.
</p>


<b>Link Destination</b>
<p>
Choose the way you wish to redirect visitors. You can choose to send visitors with certain properties or certain countries to different destinations.
</p>

<p>
<i>Based on visitor&#039;s properties (advanced)</i>: Syntax for all rules except <code>IP Address</code> is regex - syntax is compatible with <code>preg_match</code> function - <a href='http://www.php.net/manual/en/reference.pcre.pattern.syntax.php' target='_blank'>docs <small>(offsite)</small></a>. Matching is not case-sensitive, for example regexp <code>MSIE</code> and <code>msie</code> are equal. <code>IP Address</code> is either single IP address or range defined as 2 IP
addresses split with dash (second one must be bigger), for example
<code>84.12.111.2 - 84.12.255.1</code>
</p>

<b>Advanced Option: Redirect Type</b>
<p>
WP Wizard Cloak allows you to choose which redirect type used for each Link. The default is a 301 redirect. This is the most common type of HTTP redirect.
</p>

<p>
<i>OTHER REDIRECT TYPES</i><br />

<i>302</i> - HTTP 302 redirect - <a href='http://en.wikipedia.org/wiki/HTTP_302' target='_blank'>wiki</a>.<br />
<i>307</i> - HTTP 307 redirect - <a href='http://en.wikipedia.org/wiki/HTTP_307#3xx_Redirection' target='_blank'>wiki</a>.<br />
<i>Meta Refresh</i> - Uses HTML meta refresh to redirect the visitor. HTML <code>&lt;title&gt;</code> tag is set to Link Name - <a href='http://en.wikipedia.org/wiki/Meta_refresh' target='_blank'>wiki</a>.<br />
<i>JavaScript</i> - Uses JavaScript to redirect the visitor. HTML <code>&lt;title&gt;</code> tag is set to Link Name.<br />
<i>IFrame</i> - Frames the destination URL. HTML <code>&lt;title&gt;</code> tag is set to Link Name. If the destination URL does not have a frame-breaking script installed, the destination URL will be displayed inside an IFrame and the browser address bar will show the Cloaked Link URL - <a href='http://en.wikipedia.org/wiki/HTML_element#Frames' target='_blank'>wiki</a>.
<br />
<i>ReferrerMask</i> - Will not redirect the visitor unless WP Wizard Cloak determines the referrer has been wiped successfully. Useful for hiding your referrer from dishonest merchants.<br />
</p>

<b>Advanced Option: Link Expiration</b>

<p>
Link Expires - check this box to make the Link stop working after a certain date. After the Link has expired, you can choose to show visitors a 404 Not Found error page (not recommended), or redirect them to a page saying the Link/offer has expired by specifying the URL of that page. You could even redirect the user to another WP Wizard Cloak-created Link by specifying the Link URL.
</p>

<b>Advanced Option: Auto-Match URLs</b>
<p>
Auto-Match these URLs - Automatically replace the following URLs in all WordPress Pages & Posts with the Link you are creating. Can be undone by removing the URLs specified here. Useful for automatically cloaking & tracking uncloaked URLs in your existing content.
</p>


<center>

	<h2 id="tutorial_video" class="toggler ">
	<span class="indicator">[+]</span> Click here to watch a video tutorial for this page.	</h2>
	<hr class="clear" />
	<input type="hidden" name="toggler-target-tutorial_video" value="0" />

	<div class="toggler-target-tutorial_video" style="display:none">

        <center style='margin: 12px;'>

				<object id="csSWF" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="640" height="398" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,115,0">
					<param name="src" value="http://www.wpwizardcloak.com/adminpanel/helpvideos/createcloakedlink/createcloakedlink_controller.swf"/>
					<param name="bgcolor" value="#1a1a1a"/>
					<param name="quality" value="best"/>
					<param name="allowScriptAccess" value="always"/>
					<param name="allowFullScreen" value="true"/>
					<param name="scale" value="showall"/>
					<param name="flashVars" value="autostart=false#&thumb=http://www.wpwizardcloak.com/adminpanel/helpvideos/createcloakedlink/FirstFrame.png&thumbscale=45&color=0x000000,0x000000"/>
					<embed name="csSWF" src="http://www.wpwizardcloak.com/adminpanel/helpvideos/createcloakedlink/createcloakedlink_controller.swf" width="640" height="398" bgcolor="#1a1a1a" quality="best" allowScriptAccess="always" allowFullScreen="true" scale="showall" flashVars="autostart=false&thumb=http://www.wpwizardcloak.com/adminpanel/helpvideos/createcloakedlink/FirstFrame.png&thumbscale=45&color=0x000000,0x000000" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>
				</object>

        </center>

	</div>

</center>


<!--<br /><br />
Create Link help file.<br />
views\admin\edit\index-help.php file.</br>-->



