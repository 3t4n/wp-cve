<?php
/**
 * File contains the broken link dashboard UI code.
 *
 * @package broken-link-finder/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '<div class="moblc_next_divided_layout"  id = "moblc_new_scan_div">
            <div class="moblc_setting_layout">';

echo '<div id="moblc_manual_scan_div">
                        <h2 class="moblc_tab-heading">Scan for deadlinks present on your WordPress site.</h2>
                        <p>Detect broken links, broken images, embed Youtube videos by simply clicking on the button below. It scans all the published custom posts and pages in WordPress. It also scans links in all the forum and comments.</p>
                        <br>
                        <input type="button" name="moblc_manual_scan" id="moblc_manual_scan" value="Start New Scan" class="moblc_button " />
                        <br>
                        </div>';
echo '<div id="moblc_stop_scan_div" class ="moblc_none">
                    <br>
                        <h2 id="progress_message" class="moblc_success_div"><img src="' . esc_url( $moblc_loader ) . '" height="30px" width="30px" class="moblc_loader_margin"></img>Your website is getting scanned for broken links.</h2>
                        <br>
                        <input type="button" name="moblc_stop_scan" id="moblc_stop_scan" value="Abort Scan" class="moblc_button_danger" />
                        </div>
        </div>
    </div>';
?>

<script>
	var progress_bar;
	var is_scanning = '<?php echo esc_js( $is_scanning ); ?>';
	var is_service_scanning = '<?php echo esc_js( $is_service_started ); ?>';
	jQuery('#moblc_scan_confirm_modal').show();
	if (is_scanning) {
		jQuery('#moblc_manual_scan').css('backgroundColor', '#2271b2');
		jQuery('#moblc_manual_scan').prop('disabled', true);
		jQuery('#moblc_manual_scan_div').css('display', 'none');
		jQuery('#moblc_stop_scan_div').css('display', 'block');
		moblc_status_progress();
		progress_bar = setInterval(moblc_status_progress, 5000);
	}

	jQuery('#moblc_stop_scan').click(function () {
		jQuery('#moblc_stop_scan').val('Aborting...');
		clearInterval(progress_bar);
		var data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_stop_scan',
			'nonce': '<?php echo esc_js( wp_create_nonce( 'moblc-link-nonce' ) ); ?>'
		};
		jQuery.post(ajaxurl, data, function (response) {
			if (response == 'ERROR')
				moblc_error_msg("Your query could not be submitted. Please try again.");
			else if (response == 'SUCCESS') {
				moblc_success_msg("Scan Aborted.");
				moblc_reset_scan_progress();
				moblc_hide_scan_status();
			}
			jQuery('#moblc_stop_scan').val('Abort Scan');
		});
	});
	jQuery('#moblc_manual_scan').click(function (e) {
		jQuery('#moblc_manual_scan').val("Starting Scan...");

		var data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_check_links_from_pages',
			'nonce': '<?php echo esc_js( wp_create_nonce( 'moblc-link-nonce' ) ); ?>'
		};
		moblc_ajax_call(data);

	});

	function moblc_ajax_call(data) {
		jQuery.post(ajaxurl, data, function (response) {
			if (response == 'Already Scanning') {
				moblc_error_msg('Already Scanning');
			} else if (response == 'ERROR')
				moblc_error_msg("Your query could not be submitted. Please try again.");
			else if (response == 'NO_DATA')
				moblc_error_msg("Your site do not have any published page to check for broken links");
			else if (response == 'DBConnectionIssue')
				moblc_error_msg("Unable to connect to database");
			else if (response == 'INVALID_HEADERS')
				moblc_error_msg("Invalid request has been made");
			else {
				moblc_success_msg('Scan Started');
				moblc_reset_scan_progress();
				jQuery('#moblc_stop_scan').show();
				jQuery('#moblc_manual_scan_div').hide();
				jQuery('#moblc_desc').hide();
				jQuery('#moblc_scan_desc').show();
				jQuery('#mo_progress').show();
				jQuery('#moblc_scan_confirm_modal').show();
				jQuery('#moblc_manual_scan').prop('disabled', true);
				jQuery('#moblc_stop_scan_div').css('display', 'block');
				jQuery('#moblc_service_manual_scan').prop('disabled', true);
				progress_bar = setInterval(moblc_status_progress, 5000);
				jQuery('#download_report').hide();
				jQuery('#moblc_view_report').hide();
			}
			jQuery('#moblc_manual_scan').val("Start Scan");
		});
	}

	function moblc_status_progress() {
		var data = {
			'action': 'moblc_broken_link_checker',
			'option': 'progress_bar',
			'nonce': '<?php echo esc_js( wp_create_nonce( 'moblc-link-nonce' ) ); ?>'
		};
		jQuery.post(ajaxurl, data, function (response) {

			if (response == 'ERROR')
				moblc_error_msg("Your query could not be submitted. Please try again.");
			else if (response == 'NO_DATA')
				moblc_error_msg("Scan has completed successfully.");
			else if(response == 'SCAN_COMPLETED') {
				moblc_scan_complete_display(response);
			} 
		});
	}

	function moblc_scan_complete_display(response) {
		moblc_success_msg('Scan Completed');
		jQuery("#progress_message").empty();
		clearInterval(progress_bar);
		if (response == "SCAN_COMPLETE")
			var scan_complete_msg = "Scan Completed. Please check the report for more details.";
		else if (response != null && response['btotal'] <= 0)
			var scan_complete_msg = "Congratulations !!! No Broken Links Found on your site.";
		else
			var scan_complete_msg = "Scan Completed. Please check the <a style='margin:0 5px' href='?page=moblc_report'> report </a> for more details.";

		jQuery("#progress_message").append(scan_complete_msg);
		jQuery("#moblc_stop_scan").hide();
	}

	function moblc_reset_scan_progress() {
		jQuery('#moblc_broken_link_count').html(0);
	}

	function moblc_hide_scan_status() {
		jQuery('#moblc_stop_scan_div').hide();
		jQuery('#moblc_manual_scan_div').show();
		jQuery('#moblc_manual_scan').attr('disabled', false);
	}
</script>
