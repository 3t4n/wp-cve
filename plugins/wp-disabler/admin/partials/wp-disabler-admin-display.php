<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin/partials
 */
	
function wpdisabler_admin_menu_design(){?>
	<div class="wrap">
        <h1>Wp Disabler</h1>
        <div id="wpdisabler-tabs-wrapper" class="nav-tab-wrapper">
            <a id="wpdisabler-tab-general" class="nav-tab nav-tab-active" href="#tab-general">General</a>
            <a id="wpdisabler-tab-style" class="nav-tab" href="#tab-style">Style</a>
            <a id="wpdisabler-tab-advanced" class="nav-tab" href="#tab-advanced">Advanced</a>
        </div>
        <form id="wpdisabler-form" method="post" action="#">

            <div id="tab-general" class="wpdisabler-tabcontent wpdisabler-tab-active">
            	<h4>Show Message On Disable</h4>
                <input type="radio" name="active" value="1"> Yes<br>
				<input type="radio" name="active" value="0"> No<br>
            </div>
            <div id="tab-style" class="wpdisabler-tabcontent">
                <div>Tab-style is here</div>
            </div>
            <div id="tab-advanced" class="wpdisabler-tabcontent">
                <div>Tab-advanced is here</div>
            </div>
            <?php submit_button(); ?>
        </form>
    </div>
<?php } ?>
