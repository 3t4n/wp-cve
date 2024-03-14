<?php
/*  
	Copyright 2007-2021  John Havlik  (email : john.havlik@mtekk.us)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once(dirname(__FILE__) . '/includes/block_direct_access.php');
use mtekk\adminKit\{adminKit, form, message, setting};
class bcn_multidim_admin extends adminKit
{
	protected $version = '2.7.1';
	protected $access_level = 'manage_options';
	protected $unique_prefix = 'bcn';
	protected $identifier = 'breadcrumb-navxt';
	/**
	 * Administrative interface class default constructor
	 * 
	 * @param bcn_breadcrumb_trail $breadcrumb_trail a breadcrumb trail object
	 */
	public function __construct($basename)
	{
		//We set the plugin basename here
		$this->plugin_basename = $basename;
		//Want to run the parent's constructor
		parent::__construct($basename);
		//Can't use admin_page as our function is not comptible with mtekk_adminKit::admin_page
		add_action('bcn_after_settings_tab_extensions', array($this, 'multidim_admin'));
		//Need adminKit's wp_loaded routines to run, have to add this as we do not call the parent constructor
		add_action('wp_loaded', array($this, 'wp_loaded'));
		add_action('admin_init', array($this, 'init'));
	}
	public function init()
	{
		//Have to apply the filter to get good defaults in
		$this->opt = adminKit::settings_to_opts(apply_filters($this->unique_prefix . '_settings_init', $this->settings));
		//Synchronize up our settings with the database as we're done modifying them now
		$this->opt = $this->parse_args($this->get_option($this->unique_prefix . '_options'), $this->opt);
	}
	public function add_page()
	{
		//Blank on purpose to prevent extra menu items
	}
	/**
	 * Makes sure the current user can manage options to proceed
	 */
	function security()
	{
		//If the user can not manage options we will die on them
		if(!current_user_can($this->access_level))
		{
			wp_die(__('Insufficient privileges to proceed.', 'breadcrumb-navxt'));
		}
	}
	/**
	 * Admin page settings
	 */
	public function multidim_admin($settings)
	{
		//Map our options so mtekk_adminKit can work it's magic
		$this->settings =& $settings;
		?>
		<h3><?php _e('Multidimension Extensions', 'breadcrumb-navxt-multidimension-extensions'); ?></h3>
		<table class="form-table">
			<?php
				$this->form->input_check(
					$this->settings['bhome_display_children'],
						__('Display children of the home breadcrumb when on the front page.', 'breadcrumb-navxt-multidimension-extensions'),
						false,
						__('Followed when displaying children of an item in the 2nd dimension of a multidimensional breadcrumb trail.', 'breadcrumb-navxt-multidimension-extensions'));
			?>
		</table>
		<?php
	}
}