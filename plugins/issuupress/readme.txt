=== issuupress ===
Contributors: pixeline 
Donate link: http://goo.gl/7L2ua
Tags: issuu,pdf,catalog,shortcode
Requires at least: 2.9.2
Tested up to: 4.4
Stable tag: trunk

Displays your Issuu-hosted catalog of PDF files in your wordpress posts/pages using a shortcode.

== Description ==

<a href="http://issuu.com" target="_blank">Issuu.com</a> is a great place to host your PDF magazines, but you'd rather keep your visitors on your site then send them over, right?

Issuupress fetches (via the Issuu API) a list of all your PDFs hosted on issuu.com and allows you to display that list on your blog via a simple shortcode.

You can optionally restrict the list by tag, and control the issuu viewer via shortcode attributes.

You will need credentials to access the issuu API: login to issuu and access <a href="http://issuu.com/services/api/" target="_blank" title="issuu api">http://issuu.com/services/api/</a> to find your own API key and key secret.

Please <a href="http://wordpress.org/extend/plugins/issuupress/">rate the plugin</a> if you like it.
Thanks, 
<a href="http://www.pixeline.be">pixeline</a>

= Usage = 
Simply put the `[issuupress]` shortcode where you would like the catalog to be. Add attributes to customize it.

Example: 
`[issuupress viewer="mini" titlebar="false" vmode="" ctitle="Pick a PDF file to read" height="480" bgcolor="FFFFFF"]` 

To customize its appearance, use your theme's CSS style.css file.

== Installation ==

1. Extract the zip file 
2. Drop the contents in the wp-content/plugins/ directory of your WordPress installation 
3. Activate the Plugin from Plugins page.
4. Go to Settings > IssuuPress, enter your API key and API secret, set the cache value.
5. Use the shortcode wherever you see fit. To customize its appearance, use your theme's CSS style.css file.

= Example = 
`[issuupress viewer="mini" titlebar="false" vmode="" ctitle="Pick a PDF file to read" height="480" bgcolor="FFFFFF"]` 

= Options =

- **viewer="mini"** : Possible values: "no","mini","presentation" or "window". Default: "mini".
- **titlebar="false"** : Displays the PDF's titlebar. Possible values: "true", "false". Default: "false".
- **vmode=""** : Displays pages next to each other, or underneath each other ("single"). Possible values: "single", "". Default: "".
- **ctitle=""** : Title to print on top of the list of pdf files. Default: "Pick a PDF file to read"
- **height="480"** : Controls the viewer 's height dimension. In pixels. Default: "480".
- **bgcolor="FFFFFF"** : Controls the viewer background color. In hexadecimal. Default :"FFFFFF".
- **img="false"** : Set this to a number will display the thumbnail of each pdf at the provided width (ex: img="120" will display the thumbnail at the width of 120px).

== Changelog ==

= 1.3.2 = 
- Fixed issue with links leading to 404
- Tested against wordpress 4.4


= 1.3.1 = 
- Fixed issue if your website is using SSL (https protocol)
- Tested against wordpress 4.2

= 1.3.0 = 
- Enhancement: added the option to only fetch public/private or both documents from your Issuu account.
- removed Tag support, since Issuu removed it.
- Enhancement: added the option to set the display order.
- Fixed "Notice: has_cap"
- Fixed issues related to the cache file  not being writable
- Fix: smarter automatic setup on plugin initial configuration
- Fix: use "https" url instead of http to remove security warning on SSL websites.
- Improvement: add "&debug" to the settings page url to display a Debug box with useful information (or simply click on the "View Debug information" link in the Settings screen.

= 1.2.5 = 
- removed causes for php notices.

= 1.2.4 =
- Improved error reporting
- Improved the Rebuild Cache process
- Thanks to Adam Lazzarato for bug reporting and beta testing °-)

= 1.2.3 =
- Fixed warnings appearing if no document was found.

= 1.2.2 =
- Annoying warning happening on some installations after updating the plugin. Hopefully this should sort it out.

= 1.2.1 =
- woops. A few quirks got left behind.

= 1.2.0 =
- New option: show a message when no pdf is returned.
- New shortcode attribute "img" that can be used to display pdf thumbnails (suggested by Adam Lazzarato). Set the img to the desired width (ex: img="120" will display the thumbnail at the width of 120px).
- Removed an incoherence that could make it that no pdf would be displayed if no tag was provided. Sorry about that.

= 1.1.1. =
- Fixed the tag not actually filtering anything (duh!).

= 1.1.0. = 
- Added many additional attributes to the shortcode to allow you to customize the viewer
- Added admin option to manually refresh the cache.

= 1.0.1. = 
Fixed a lot of initalisation bugs. Sorry about that.

= 1.0.0. = 
Initial release.

== Screenshots ==
1. Mockup of the Issuu viewer with the list of pdfs underneath, fetched via the Issuu API.
2. The Settings screen.
