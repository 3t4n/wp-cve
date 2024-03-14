=== Advanced Custom Fields: NextGen Gallery Custom Field ===
Contributors: Jeroen Reumkens
Tags: Advanced Custom Fields, ACF, NextGEN Gallery, NGGallery
Requires at least: 3.0
Tested up to: 3.7.1
Stable tag: trunk
Author: Jeroen Reumkens
Author URI: http://www.jeroenreumkens.nl
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin provides an extra field for the Advanced Custom Fields plugin to support the NextGEN Gallery plugin.

== Description ==

Welcome to the Advanced Custom Fields NextGEN Gallery Custom Field plugin page. As the name suggests, this script is an extension for the [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) WordPress plugin.
This script works **only** in ACF version 4.0.0. and up. Version 3 is currently not supported.

Besides that this script can be either used as a WP plugin or as a theme include.

This plugin provides an extra field for the Advanced Custom Fields plugin to support the NextGEN Gallery plugin. This makes users able to link galleries to posts, pages and custom post types.

= Compatibility =

This add-on will work with version 4 and up of the ACF plugin.

= Issues? =
If you discover any bugs or have any feature requests, I would like to refer you to the [Github Repository](https://github.com/JeroenReumkens/acf-nggallery) of this script.

== Installation ==

This add-on can be treated as both a WP plugin and a theme include.

= Plugin =
1. Copy the 'acf-nggallery' folder into your plugins folder
2. Activate the plugin via the Plugins admin page

= Include =
1.	Copy the 'acf-nggallery' folder into your theme folder (can use sub folders). You can place the folder anywhere inside the 'wp-content' directory
2.	Edit your functions.php file and add the code below (Make sure the path is correct to include the nggallery-v4.php file)

`
add_action('acf/register_fields', 'my_register_fields');

function my_register_fields()
{
	include_once('acf-nggallery/nggallery-v4.php');
}
`

== Screenshots ==
1. This is the field in de ACF admin panel.
2. NGGallery object returned in template.
3. Images array returned in template.

== Changelog ==
= 1.1.4 =
* Changed output to null instead of string "null" when no gallery is selected (https://github.com/JeroenReumkens/acf-nggallery/issues/1)

= 1.1.3 =
* Added compatibility for Wordpress 3.7.1

= 1.1.2 =
* Added screenshots

= 1.1.1 =
* Readme.txt fix.

= 1.1.0 =
* Added return type 'NGGallery id' to return only the id of the gallery.

= 1.0.1 =
* Changed answers for multiple or single gallery from 'Yes' and 'No' to 'Multiple galleries' and 'Only one'.
* Fixed Wordpress readme.txt short description to match Wordpress requirements.

= 1.0.0 =
* Initial Release.