<?php
/**
 * Represents the view for the administration dashboard.
 *
 * @package   JFSimpleComingSoon
 * @author    Jerome Fitzpatrick <jerome@jeromefitzpatrick.com>
 * @license   GPL-2.0+
 * @link      http://www.jeromefitzpatrick.com
 * @copyright 2013 Jerome Fitzpatrick
 * 
 */
?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	    <form method="post" action="options.php">
	        <?php
            // This prints out all hidden setting fields
		    settings_fields('jf_scs_group');	
		    do_settings_sections($this->plugin_slug);
			submit_button();
			?>
	    </form>
</div>
