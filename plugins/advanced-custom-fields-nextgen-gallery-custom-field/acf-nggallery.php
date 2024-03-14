<?php
/*
Plugin Name: Advanced Custom Fields: NextGen Gallery Custom Field
Description: This plugin provides an extra field for the Advanced Custom Fields plugin to support the NextGEN Gallery plugin. This makes users able to link galleries to posts, pages and custom post types.
Version: 1.1.4
Requires at least: 3.0
Tested up to: 3.7.1
Author: Jeroen Reumkens
Author URI: http://www.jeroenreumkens.nl
License: GPLv2 or later
License URI:	 http://www.gnu.org/licenses/gpl-2.0.html
*/


	add_action('acf/register_fields', 'registerFields');


	/*
	*  registerFields()
	*
	*  Init custom field
	*
	*  @type	action
	*  @since	1.0.0
	*  @date	21-09-2013
	*
	*  @param	{none}
	*
	*  @return	{none}  - Sets warning for user in admin_notices action
	*/

	function registerFields()
	{
		require_once("nggallery-v4.php");
	}

?>