=== Responsive iframe ===
Contributors: PatrickPelayo
Tags: responsive, iframe,block
Requires at least: 5.4.2
Tested up to: 5.8.1
Stable tag: 1.2.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lets you place Iframes into your posts that will resize dynamically to maintain the same apperance.

== Description ==
A Responsive Iframe that will resize itself to its parent element.

= Instructions =

= Install =
The *plugin manager* is located on the *administration page*. From the *administration page* in the left side bar click *Plugins*. From the top of the page click *add new*.

Install the plugin thru the *plugin manager*, or upload it as a zip file into the *plugin manager*.

= Use =

* **Apply responsive iframe**
Create a post like you normally would and then click the + icon displayed to *Add block*. Find *Responsive iframes* and click it. 
By default your current website will be displayed in the iframe.

* **Block Settings**
Site Address - The url address of the website you want to iframe
Width -  This represents the actual width you want to display from the iframed website.
Height - This represents the actual height you want to display from the iframed website.											
Scrollbar - Displays a scroll bar if the height,and width are smaller than the iframe website.
Border - Dislays a default border around the iframe element.							   
Scale - This will change the max-width percentage of the iframe's parent element, it will allow the element to be scaled by size.	
Additional CSS - Any additional CSS will be applied to the iframe's parent element. Use this to provide some additional styling.

* **BreakPoints**
Available under advanced settings, and will let you specify the width of the site to display depending on the width of the device.
Multiple breakpoints can be used. The lowest BreakPoint Width will take effect over any higher BreakPoint Widths when the screen device is below or at its BreakPoint Width.
Example Breakpoint 1 250px, Breakpoint 2 350px. A device with a screen width of 250px will only trigger Breakpoint 1. ScreenWidth 251px, will only trigger BreakPoint 2.
Name - The name you wish to give the breakpoint
BreakPoint Width - At and below this width, this breakpoint is triggered.
Site Width - This is the width of the website to display when the breakpoint is triggered


== Screenshots ==
1. Example of New York Times in an iframe.
2. The iframe in the website, demonstrating from a mobile viewpoint.
3. The iframe in the website, demonstrating from a desktop viewpoint.

== Changelog ==

= 1.2.0 =
* Added to advanced settings the ability to add breakpoints to the iframe.

= 1.1.1 =
* Max Width setting now using px instead of REM.

= 1.1.0 =
* Adds to advanced settings an option to specify max-width of Iframe

= 1.0.1 =
* updates the readme, and fixes my website link in plugin.php

= 1.0 =
* first release
	
== Upgrade Notice ==

= 1.2.0
* Good update if you want more control on the iframe apperance for differant devices

= 1.1.0
* optional update

= 1.0.1 =
* optional update, only changes readme and fixes my website link in plugin.php

= 1.0.0 =
* first release
	
== Frequently Asked Questions ==

 = How do I access the block settings =
 
 In the page where you create a post, in the top right corner next to update/publish is a cog wheel that allows you to edit block settings.

 = In the editor I can't click on the iframe =
 
 Click directly underneath the iframe element, you want to select the block so you can edit it. 
 Top ToolBar on the page also has three horizontal lines that let you select your blocks in the editor.
 = What can I stylize without breaking the iframe =
 
 Leave the iframe element largly alone, focus css on the parent div element. 
 Do not modify the height on the div element.