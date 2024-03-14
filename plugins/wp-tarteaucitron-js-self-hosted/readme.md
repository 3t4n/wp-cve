# WP tarteaucitron.js Self Hosted
Contributors: rdorian
Donate link: https://paypal.me/riccidorian/
Tags: tarteaucitron.js, tarteaucitron, tarteaucitronjs, tarteaucitron js
Requires at least: 4.9
Tested up to: 5.9
Stable tag: trunk
Requires PHP: 5.6.31
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html



## Description

### Deprecated
As I don't have enough time to update this plugin like I would, I won't be supporting the update of this plugin.

If you liked this plugin you have 2 options:

1. Use the official [tarteaucitron.js](https://fr.wordpress.org/plugins/tarteaucitronjs/) plugin. It offer a free plan with 3 active services per website.
2. Integrate yourself the [source code](https://github.com/AmauriC/tarteaucitron.js/) of tarteaucitron.js directly in your website.
3. Update yourself the file from this plugin directly on your Wordpress website.
   This can be done by updating the folder `wp-content/plugins/wp-tarteaucitron-self-hosted/js/tarteaucitron` and replace the content with the latest version of tarteaucitron.js [source code](https://github.com/AmauriC/tarteaucitron.js/)

## Installation

### Deprecated
As I don't have enough time to update this plugin like I would, I won't be supporting the update of this plugin.

Please check the _details_ view.

## Frequently Asked Questions

### Is this plugin deprecated forever ? 
No this plugin isn't deprecated forever, it's just that for now I don't have enough time to maintain it and to update it like I want.

When I'll have the time to maintain it, I'll be back. But for now I don't want people to dislike this plugin or the amazing work done by [@AmauriC](https://github.com/AmauriC) and his plugin [tarteaucitron.js](https://tarteaucitron.io/)

The official plugin offer a free plan for up to 3 active services.

## Screenshots

### Deprecated
As I don't have enough time to update this plugin like I would, I won't be supporting the update of this plugin.

Please check the _details_ view.

## Changelog 
### 1.2.4
Deprecation of this plugin. 
Please don't use this plugin anymore as it won't be updated regularly.
If you want regular update, you can use the official plugin : [tarteaucitron.js](https://tarteaucitron.io/)

### 1.2.3
Added unistall.php that will clean the db when removing the plugin.

### 1.2.2
Add option to specify external CSS file

### 1.2.1
fixup to restore property useExternalCSS and useExternalJs

### 1.2.0 (04/2021)
Update to the last version (1.9.0) of [tarteaucitron.js](https://github.com/AmauriC/tarteaucitron.js)
Please check with the official documentation that all the service you are using are compatible, or update your configuration after the update.
This include the following changes:

* add services : Linkedin Insigh, Twitter Universal Website Tag, Xandr, Adobe Analytics, Clarity, Compteur.fr, Kameleoon, Matomo, statcounter.com, Verizon Dot Tag, Woopra, HelloAsso, OneSignal, Facebook (post), Instagram, Userlike, Arte.tv, Deezer, podCloud, SoundCloud
* Update service documentations
* Update backend CSS to bootstrap 5.0

### 1.1.0
Update to the last version of [tarteaucitron.js](https://github.com/AmauriC/tarteaucitron.js)
This include the following changes:

* add services : abtasty, contentsquare, leadforensics, google webfonts, emolytics
* Add languages: Bulgarian and Romanian
* Add the position "middle" for the banner
* Update some privacy uri
* Update some javascript function for services. e.g: recaptcha, vimeo, AT Internet, matomo, ...

Please check that all your service are working correctly.
If you can't find your service in the list of services, you'll need to add yourself the code in the textarea provided for this.

### 1.0.14
* Remove stat, to fix breaking call

### 1.0.13
* Added Option for anonymous statistics

### 1.0.12
* Corrected "UseExternalCss" option to load a custom alternatif css file from the default one if set to true.

### 1.0.11
* Update menu "Languages" to "Texts"

### 1.0.10
* Default value for initialisation script on the front end

### 1.0.9
* corrected bug on initialisation settings

### 1.0.8
* Added link to little documentation

### 1.0.7
* Corrected minor bugs

### 1.0.6 
* removed option use external css that caused css bug on the website (this option is coming back when I found a patch)
* Changed shortcode message

### 1.0.5
* Corrected little bugs in update 1.0.4
* If the banner stay even after the user accept, please save at least once the initisalisation option and remove all the cookies for the website in the browser

### 1.0.4
* Update to script release 1.2
* Updated services by adding new services (Hubspot, Twitter Widget Api)
* Added initialisation variable 'useExternalCss'
* Added Greeks translation

### 1.0.3
* Added ability to change the cookie name
* Updated Service page by adding new services (adform, adsense, GetQuanty, HotJar, Koban, Matomo)
* Updated roadmap
* Updated to last version of tarteaucitron.js

### 1.0.2
* corrected little bug with settings

### 1.0.1
* Updated roadmap

### 1.0.0
* Added feature to customize frontend texts.

### 0.3
* added matomo service
* added koban service
* Updated tarteaucitron.js
* added fb-video
* Updated roadmap
* corrected some texts

### 0.2
* Initialisation of tarteaucitron.js without writing javascript
* Possibility to customize the expiration time of the cookie
* Possibility to choose the language for the frontend

### 0.1.1
* Added default value for initialisation script

### 0.1
* Initialisation script with a text area to insert the code. (Without the <script> tag)
* Services script with a text area and checkboxes to activates the services needed.

## Upgrade Notice
There is nothing to do 