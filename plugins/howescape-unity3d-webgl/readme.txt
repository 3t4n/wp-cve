=== HoweScape Unity3d WebGL ===
Contributors: pthowe
Author URL: http://howescape.com
Donate Link: https://www.paypal.com/donate?token=imKJoMW-DmXwgKQ1ONurIWF04g3U1lAwGfF4LuFX3Y_End94xjNzblDmW3FYqVmBXJX4JEiu6VjxSKxt
Tags: Unity3D
Requires at least: 4.0.0
Tested up to: 6.0.1

Stable tag: 11.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin to allow the inclusion of a Unity3d WebGL application. A short code is created which can displayed.

== Description ==

The Unity3d WebGL support creates a directory of files. 
This is not convent to load  on your WordPress web site. 
This plugin takes the contents of the "Build" directory ("Release" in earlier versions) from your game and places it 
inside the plugin. When compiling the game you need to select an output directory. This directory name becomes the name of the game.
In the initial version of the plugin for version 5.3.1 the plugin required "Builds_WebGL" to be the game name.
The short code created by the plugin takes parameters which allow selection of the game.
This plugin can then be referenced from with a short code. 
The parameters in the short code are the game name and the width and height.

ie. [hs_unity3d_web_gl_game src="Roll-A-Ball" height="500" width="600" u3dver="2020.3" buildtype="Production" ]

To extend the support for Unity3d to version 5.5.1 an additional parameter has been added. 
This parameter allows the specification of a version. The version support is 5.5.1 or the original version supported by the plugin. (ie. original version 5.3.1)
The Unity3d version 5.5.1. creates a directory  "Development". This is what I have uploaded in the included example. (ie. Roll-A-Ball-5_5_1-Release)
The short code  is now looks like the following example.

ie. [hs_unity3d_web_gl_game src="Roll-A-Ball" height="500" width="600" u3dver="5.5.1" buildtype="Production"]

In reviewing the latest verion of Unity3d I noticed that the file organization for the WebGL has been updated again. 
With this update there are now 7 supported version 5.3.1, 5.5.1, 5.6.0, 2017.4.0f1, 2018.4, 2019.4, 2020.3 and 2021.3.9f1. 
When using the newest version you would have a short code like the following.

ie. [hs_unity3d_web_gl_game src="Roll-a-ball" height="500" width="600" u3dver="2019.4" buildtype="Production"]

With the latest update to Wordpress 5.7 the format of the files has changed agaion. The WebGL build options include the choice of a "Development Build". 
While this options is not recommended for a published game, being able to use in the plugin would helpful. An additional parameter has been added to specify the build type.

ie. [hs_unity3d_web_gl_game src="Roll-a-ball" height="900" width="900" u3dver="2020.3" buildtype="Development"]

This feature has four supported values "Development", "Production", "Production.GZip", or "Production.Brotli".
The parameter is not necessary, if you are using a production build using GZip compression. The player settings Compression Format has 3 possible values.
* Development - No compression 
* Production - Same as Production.GZip
* Production.GZip - Same as Production
* Production.Brotli - Compressed with a different algorithm
These values allow you to take advantage of all the possible combinitation.

I have noticed that some users have had difficulty in getting the short code to work as desired. There are a couple of error 
messages which are displayed. The parameter "errorreport" has 2 supported values "Normal" and "Verbose". The default value is "Normal". 
If you have the parameter set to "Verbose" and an error message is displayed additional information may be displayed.

All other features should work as before. There are other features being considered please let me know if you desire anything functionality.

With the addition of the settings page it is now possible to place 
the release directory in a zip file. (ie. &lt;gamename&gt;-Release.zip) The default version of Unity selected if no version 
is 5.3.1 unless provided.
Once the file is uploaded the setting page for the plugin will allow extraction 
of the files into a location which the short code can locate. When naming games from version 5.5.1 and beyond you will need 
to include the version number. (ie. &lt;gamename&gt;-5_5_1-Release.zip) With this version place the 
files from the Development directory in the zip file. For the latest verion it is the same. (ie. &lt;gamename&gt;-5_6_0-Release.zip)
With the addition of the last 2 version the the version number is now shorter. 

In the process of doing the latest update I wanted to switch between the different games to verify that everything was working. 
To make this task simpler I added a short code which displays a list of the available games and allows the selection of a game. 
The major reason for the addition was game development. Since I thought it might be useful I have added it to the plugin. 

ie. [hs_unity3d_web_gl_gamepage]

If you use this short code with just the plugin you will have three games. Four versions of the  Roll-A-Ball sample game and the space-shooter sample game. 
There are 2 ways games can be added, one is making them part of the plugin. The second is as a zip file which gets uploaded to the media directory. 
Once uploaded the uploaded game zip file can be expanded into the plugin from the settings page. There is also a delete option on the settings page. 
It can remove any game from the plugin. The page now displays the short code with the parameters you selected. The goal of this display is to assist people in getting a short 
code which they can put on there page and display the game. 

As the results of a question which was asked, "How can I get information from the browser into the Unity Game?" I have added a short code which adds javascript variables to the page. 
The short code is:

ie. [hs_unity3d_current_user]

This adds the following to the HTML page:

ie. <script>var HS_CURRENT_USER_ID=5;var HS_CURRENT_USER_NAME="gameplayer";</script>

It is then possible to add code to the Unity Game to extract this information and make it part of the unity game. The 2020 version of roll a ball game displays the user which is logged into the wordpress site.

== Installation == 

<h4>From your WordPress dashboard</h4>
<ol>
<li>Visit 'Plugins > Add New'</li>
<li>Search for 'HoweScape Unity3d WebGL'</li>
<li>Activate HoweScape Unity3d WebGL from your Plugins page. </li>
</ol>

<h4>From WordPress.org</h4>
<ol>
<li>Download HoweScape Unity3d WebGL.</li>
<li>Upload the 'HoweScape Unity3d WebGL' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)</li>
<li>Activate HoweScape Unity3d WebGL from your Plugins page. </li>
</ol>

== Frequently Asked Questions ==

= Q: Can I use this plugin with my own Unity3d game? =

A: Yes Take the release directory from Unity3d Build directory and place in the plugin directory. Prefix the release directory with the name of the game ending in "-Release" The game name then is used in the short code.

= Q: Can there be multiple games in the plugin? =

A: Yes. Each game is in its own &lt;gamename&gt;-Release directory

There are currently three games in the delivered plugin.

= Q: Can the games be placed outside the plugin? =

A: Yes, In the media directory

The settings page allows games to be extracted into the plugin.

= Q: How do I move the ball? =

A: The arrow keys allow movement of the ball to collect the cubes.

= Q: How to play space Shooter. =

A: Arrow keys move ship. 

Mouse button fires gun. Mouse needs to be in window.

= Q: My game does not work I get an error message. =

A: "An error occured running the Unity content on this page."

If you add the errorreport parameter with the value of "Verbose" it will display additional information with the error message.

"The error was: uncaught exception: incorrect header check"

When I compiled my Unity3d game I used "Builds_WebGL" as the directory. This seems to be a requirement of the plugin.

= Q: For version 5.5.1 and 5.6.0 the game name restriction. =

A: With these versions the restriction on build directory has been removed. The names needs to not contain spaces.

= Q: How is the game name specified in 2018.4 and 2019.4? =

A: The directory selected for the output.

= Q: How is the game name specified in 2018.4 and 2019.4? =

A: The directory selected for the output.

= Q: I can not find the release directory? =

A: For a game to be added you must add it to the plugin or create a zip file with the contents of the "build" directory.

= Q: How do I name the zip file? =

A: <GameName>_<gameVersion>-Release.zip

= Q: Does the plugin support a High Score list? =

A: No, I removed that feature, seemed to not fit.

= Q: What happens when I upload a game multiple times in the same day. =

A: The file name receives a dash and a number to represent the number of times uploaded. This information is now included in the list of uploaded files. The number is removed when the game is expaneded.

= Q: What code is required extract browser information into Unity3D. =

A: WebGL: Interacting with browser scripting

https://docs.unity3d.com/2020.2/Documentation/Manual/webgl-interactingwithbrowserscripting.html

This requires adding the directory "Plugins" in the "Assets" directory. The file added in the directory is &lt;filename&gt;.jslib. There is also the C-Sharpe file which calls the functions in the jslib file.

= Q: Do I need to expanded the sample games? =

A: No, they are delivered expanded

= Q: How do I get information from the browser into the game =

A: A short code has been added to place the logged in User ID and User Name in javascript variables. This information can then be accessed from the Unity3D game.

[hs_unity3d_current_user]

Review screen captures 5, 6 and 7.

= Q: What compression options are supported, as buildtype =

A: There are 3 different compression methods currently supported in Unity 2020.3.
 * Development : no compression
 * Production : GZip
 * Production.Gzip : GZip
 * Production.Brotli : Brotli
 
= Q: Should games work with all browser = 

A: If you look at the browser developer tools, when a game does not load you might see errors with loading the compressed game files. Using Development build type might address issue. Changes to webserver configuration can also address this issue.

= Q: What Unity version should I use? = 

A: Changes to the output files is usually done in major releases. If using 2020.3.1 or 2020.3.11 you would use 2020.3.

= Q: Error message when creating page with short code "Updating failed. The response is not a valid JSON response." =

A: This may be caused by the incorrect character used in the short code to enclode the string. Use a Double quote or a single quote. When using a word processor they sometimes use a different character at each end of the quote.

= Q: Error message "HTTP Response Header "Content-Type" configured incorrectly on the server for file ... should be "application/wasm". Startup time performance will suffer." displayed and game does not play.

A:  The online documentation "WebGL: Server configuration code samples" has a sample of the .htaccess file which should be created and inserted into the zip file with the files from the build directory. https://docs.unity3d.com/2021.1/Documentation/Manual/webgl-server-configuration-code-samples.html

== Screenshots ==

01. Screen capture of Roll-A-Ball game from unity3d.com
02. Screen capture of Roll-A-Ball game with updates to colors
03. Screen capture of Space-Shooter game from unity3d.com tutorials
04. Screen capture of error message caused by building to incorrect directory
05. Screen capture of code to get javascript value from browser into C sharpe code
06. Screen capture of code which declares variables which will be connected to UI elements
07. Screen capture of code which updates variables with function call to get values


== Changelog ==

= 2.3.6 =

* Updated Readme
* Updated to Unity3D 2021.3.9f1 
* Tested in Wordpress 6.0.1

= 2.3.5 =

* Updated Readme to ensure good quality
* Update to Unity3D 2020.3 browser code 
* Updated support for different compression options.

= 2.3.3 = 

* Added Settings link to end of plugin description. 
* Updated settings form to handle no selection error. 

= 2.3.0 = 

* Added new shortcode [hs_unity3d_current_user] 
* Added support for Unity3d 2020.3.2f1 
* Corrected error in 5.3.1 game support 

= 2.2.1 = 

* Corrected support files for 2019.4 
* Consolidated graphic files from different versions, hoping to make plugin smaller. 

= 2.0.1 = 

* Added support for 2018.4 
* Added Support for 2019.4 

= 1.1.0 = 

* Updated tested version of WordPress to 5.2.2. Required minor updates to support changes in PHP version and WordPress 

= 1.0.1 = 

* Restructured the application to use the Class model 
* Updated the shortcode hs_unity3d_web_gl_gamepage to allow selection of the short code values from drop down lists. 
This allows you to test a short code configuration with different values. 

= 0.3.1 = 

* Added support for Unity3d 5.5.1. This requires adding a parameter to the Short code. 
* This parameter is not required for Web GL games which have a .htaccess file 
* Example of parameter u3dver=5.5.1 
* At this time there is no other values which are supported. 
* The template data directory was also added to support version 5.5.1.  

= 0.1.1 = 

* Added Settings page. This provides three groups of information. 
 * The first group is a list of the Unity3d games in the plugin. 
 * The second lists is of Unity3d games expanded into the plugin.
 * The third is a list of zip files in the media directory which the plugin recoginise as Unity3d gamems 
* The extract button takes the identified zip file and expands the game into a subdirectory. 
* Updated processing to to include search to include expanded games. 

= 0.2 = 

* Update file Calling to use recommended 
* Removed "Created with unity" link from plugin 

= 0.1 = 

* Original Release 


== Upgrade Notice ==

Fifth upgrade added support for 2021.3.9f1, tested in Wordpress 6.0.1

Fourth upgrade updated support for 2020.3, added additional build types, Added  

Third update to add support for 5.6.0. Also added short code for list of games.

Second update to add settings page and support extract of game from media zip file.

First update to correct file location references. Removed "Created with Unity" link.

Being initial release there is no notice

== Arbitrary section ==

== A brief Markdown Example ==

[hs_unity3d_web_gl_game src="Roll-A-Ball" height="500" width="600" u3dver="2020.3" buildtype="Production" errorreport="Normal"]