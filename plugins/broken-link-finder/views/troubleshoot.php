<?php
/**
 * This is troubleshoot view component file.
 *
 * @package broken-link-finder/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
echo '
	<div class="moblc_divided_layout2">
            <div class="moblc_setting_layout">
            <h3 style="margin-left:2%">
            Frequenty Asked Questions
            </h3><hr>
    		<table class="moblc_help">
                <tbody>
                    <tr>
                        <td class="moblc_help_cell">
                            <div id="moblc_help_scan_title" class="moblc_title_panel" >
                                <div class="moblc_help_title"><u>How to check if scan/cron is started or not?</u></div>
                            </div>
                            <div id="moblc_help_desc_1" class="moblc_help_desc" style="display: none">
                                <input type="hidden" name="moblc_trobleshoot_index" value="' . esc_attr( get_site_option( 'moblc_troubleshoot_index' ) ) . '"/>
                                <ul>
                                    <li>Step 1:&emsp;Install and activate [wp-crontrol] plugin</li>
                                    <li>Step 2:&emsp;Click on Events and go to the Cron Events tab</b>. </li>
                                    <li>Step 3:&emsp;Search for moblc_scan_cron_hook in the search field.</li>
                                    <li>Step 4:&emsp;If cron is available then click on Run Now.</li>
                                    <li>Step 5:&emsp;If it is not available then create a new cron with the following details:<br>
                                        <table style="margin-top:4px;" class="moblc_table_border">
                                            <tr>
                                                <th class="moblc_table_border">Event type</th><td class="moblc_table_border">Standard Cron Event</td>
                                            </tr>
                                            <tr>
                                                <th class="moblc_table_border">Hook Name</th><td class="moblc_table_border">moblc_scan_cron_hook</td>
                                           </tr>
                                            <tr>
                                                <th class="moblc_table_border">Arguments</th><td class="moblc_table_border">(Leave Blank)</td>
                                            </tr>
                                            <tr>
                                                <th class="moblc_table_border">Next Run</th><td class="moblc_table_border">Now</td>
                                            </tr>
                                            <tr>
                                                <th class="moblc_table_border">Recurrence</th><td class="moblc_table_border">max_time(100s) or Every minute (every_minute)</td>
                                            </tr>
                                        </table>
                                    </li>
                                    <li>Step 6:&emsp;The scan should start resuming after 1 or 2 minutes.</li>
                                </ul>
                                If the above steps do not work then you need to create a Cron Job on your hosting provider.<br>
                                You can use the same data as mentioned in the above table to create a cron job.<br>
                            </div>
                        </td>
                    </tr>
                </tbody>
    		</table>
    	    </div>
    	</div>';
?>
<script>
	var moblc_scan_troubleshoot = jQuery('input[name=\"moblc_trobleshoot_index\"]').val();
	if (moblc_scan_troubleshoot !== null) {
		jQuery('#moblc_help_desc_' + moblc_scan_troubleshoot).css('display', 'block');
	}
</script>
