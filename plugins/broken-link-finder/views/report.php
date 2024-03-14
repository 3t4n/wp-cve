<?php
/**
 * This is report view component view file.
 *
 * @package broken-link-finder/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="moblc_next_divided_layout">
	<div class="nav-tab-wrapper moblc_nav-tab-wrapper">
		<div id="moblc_scan_report_sub_tabs" class="moblc_nav-tab nav-tab" >Scan
			Report
		</div>
	</div>
</div>

<div id="moblc_scan_report_div" style="display: block;">
	<?php
	if ( isset( $moblc_broken_pages ) && count( $moblc_broken_pages ) > 0 ) {
		echo '<div class="moblc_next_divided_layout">
        <div class="moblc_setting_layout">';
		?>
	<div class="moblc-heading" id="moblc-show-broken-pages">
		<div style="display:flex">
			<div style="position:relative;margin-right:8px">
				<span class="dashicons dashicons-admin-site"></span>
				<small id="moblc_broken_pages_count" class="moblc_otification_tip"><?php echo esc_html( count( $moblc_broken_pages ) ); ?></small>
			</div>
			Broken Pages<small class="moblc_note">&nbsp(Please click recheck to rescan the page)</small>
		</div>
		<span id="moblc-show-broken-pages-dashicons" class="dashicons dashicons-arrow-up-alt2"></span>
	</div>
	<div class="moblc-broken-pages" id="moblc-broken-pages" style="display: none;">
		<div class="moblc-broken-pages-show">
			<div class="moblc_table_head">
				<div class="moblc_justify_space_between">
					<div style="max-width:500px;"><strong>Page/Post</strong>
					</div>
					<div>
						<strong>Status</strong>
					</div>
				</div>
			</div>
			<?php
			foreach ( $moblc_broken_pages as $page_data ) {
				if ( empty( $page_data->link ) ) {
					return;
				}
				?>
				<div id="<?php echo esc_attr( $page_data->id ); ?>" class="moblc_broken_page_row">
					<div class="moblc_justify_space_between">
						<div style="max-width:500px;color:#2271b1">
							<strong><?php echo esc_html( $page_data->link ); ?></strong>
						</div>
						<div class="moblc_red">
							<strong><?php echo esc_html( $page_data->status_code ); ?></strong>
						</div>
					</div>
					<div style="padding-top:10px">
							<a title="<?php echo esc_attr( $pages_tooltip['recheck'] ); ?>"
							onclick="moblc_check_page(this,'<?php echo esc_js( $page_data->link ); ?>','<?php echo esc_js( $page_data->id ); ?>')">Recheck</a>
							|
							<a title="<?php echo esc_attr( $pages_tooltip['ignore'] ); ?>"
							onclick="moblc_ignore_page(this,'<?php echo esc_js( $page_data->link ); ?>','<?php echo esc_js( $page_data->id ); ?>')"
							style="color:#b32d2e">Ignore</a>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
<?php } ?>
<div class="moblc_next_divided_layout">
	<div class="moblc_setting_layout">
		<div class="moblc-heading" >
			<div style="display:flex" id="moblc-show-broken-links">
					<div style="position:relative;margin-right:8px">
						<span class="dashicons dashicons-admin-links"></span>
						<small id="moblc_broken_links_count"
							class="moblc_otification_tip"><?php echo esc_html( count( $moblc_broken_links ) ); ?></small>
					</div>
				<?php echo esc_html( $broken_links_title ); ?>
				<small class="moblc_note">&nbsp(Broken Links found on the site.)</small>
			</div>
			<div class="moblc_status_filter" id="moblc_status_filter">
				<input type="radio" class="moblc_status_title" id="3xx" name="status"><label for="3xx" class="moblc_status_code <?php echo get_site_option( 'moblc_show_3xx' ) ? 'moblc_status_code_active' : ''; ?>">Redirected links (<?php echo esc_html( $total_3xx ); ?>)</label>&nbsp;&nbsp;|
				<input type="radio" class="moblc_status_title" id="4xx" name="status"><label for="4xx" class="moblc_status_code <?php echo get_site_option( 'moblc_show_4xx' ) ? 'moblc_status_code_active' : ''; ?>">4xx (<?php echo esc_html( $total_4xx ); ?>)</label>&nbsp;&nbsp;|
				<input type="radio" class="moblc_status_title" id="5xx" name="status"><label for="5xx" class="moblc_status_code <?php echo get_site_option( 'moblc_show_5xx' ) ? 'moblc_status_code_active' : ''; ?>">5xx (<?php echo esc_html( $total_5xx ); ?>)</label>&nbsp;&nbsp;|
				<input type="radio" class="moblc_status_title" id="others" name="status"><label for="others" class="moblc_status_code <?php echo get_site_option( 'moblc_show_others' ) ? 'moblc_status_code_active' : ''; ?>">Others (<?php echo esc_html( $total_others ); ?>)</label>&emsp;
			</div>
		</div>
		<div id="moblc-broken-links">
		<?php
			echo '	<table id="report" class="display" cellspacing="0" width="100%">
<thead>
<tr>
<th class="moblc_th">Link</th>
<th class="moblc_th">Page Title</th>	
<th class="moblc_th">Status</th>	
</tr>
</thead>
<tbody>';

		if ( $moblc_broken_links ) {
			foreach ( $moblc_broken_links as $link_data ) {
				if ( empty( $link_data->link ) ) {
					return;
				}

				$query = new WP_Query(
					array(
						'post_type' => 'page',
						'title'     => $link_data->page_title,

					)
				);

				$post_data = $query->post;


				if ( is_null( $post_data ) ) {
					$post_data = $query->post;
				}

				$edit_id = $link_data->id;
				?>
					<tr id="<?php echo esc_attr( $link_data->id ); ?>" class="moblc_tr" onmouseover="showControls(this)"
						onmouseout="hideControls(this)">
						<td class="moblc_td moblc_td moblc_width1">
							<div class="moblc_report_link">
								<strong style="color:#2271b1"><?php echo esc_html( $link_data->link ); ?></strong>
								<div style="min-height:30px">
									<div id="<?php echo 'moblc_report_controls_' . esc_attr( $link_data->id ); ?>"
											class="moblc_link_controls" hidden>
											<a title="<?php echo esc_attr( $links_tooltip['edit'] ); ?>"
											onclick="moblc_edit_blc_controls('<?php echo esc_js( $link_data->id ); ?>','<?php echo esc_js( $link_data->link ); ?>')">Edit
												URL</a> |
											<a title="<?php echo esc_attr( $links_tooltip['unlink'] ); ?>"
											id='<?php echo esc_attr( $link_data->id ); ?>' onclick="moblc_unlink_link(event)"
											style="color:#b32d2e">Unlink</a> |
											<a title="<?php echo esc_attr( $links_tooltip['unbroken'] ); ?>"
											onclick="moblc_not_broken_blc('<?php echo esc_js( $link_data->id ); ?>','<?php echo esc_js( $link_data->link ); ?>')">Not
												Broken</a> |
											<a title="<?php echo esc_attr( $links_tooltip['remove'] ); ?>"
											onclick="moblc_dismiss_blc('<?php echo esc_js( $link_data->id ); ?>','<?php echo esc_js( $link_data->link ); ?>')">Remove</a>
											|
											<a title="<?php echo esc_attr( $links_tooltip['recheck'] ); ?>"
											onclick="moblc_recheck_blc(this,'<?php echo esc_js( $link_data->id ); ?>','<?php echo esc_js( $link_data->link ); ?>')">Recheck</a>
									<?php
									echo "<div id='moblc_edit_broken_link_" . esc_attr( $edit_id ) . "' class='moblc_edit_broken_link' hidden>   
    <form id='moblc_edit_blc' action='' onSubmit = 'return false;' >
	<br>
    <input type='text' id='moblc_edited_blc_" . esc_attr( $edit_id ) . "' class='moblc_edit-blc-field' required autofocus value='" . esc_attr( $link_data->link ) . "'/>
    <input type='hidden' id='moblc_edit_blc_" . esc_attr( $edit_id ) . "' value='" . esc_attr( $link_data->link ) . "'/>
    <input onclick='moblc_edit_btn(event)' id='" . esc_attr( $edit_id ) . "' class='moblc_edit_blc_btn moblc_save-btn' type='button' value='Save link'/> 
    <input id='page_title_" . esc_attr( $edit_id ) . "' type='hidden' value='" . esc_attr( $link_data->page_title ) . "'/>
    <input onclick='moblc_discard_btn(event)' id='" . esc_attr( $edit_id ) . "' class='moblc_edit_blc_btn moblc_discard-btn' type='button' value='Cancel' />
    </form>      
    </div>"
									?>
									</div>
								</div>
							</div>
						</th>
						<td class="moblc_td moblc_td_center moblc_width2">
							<strong><?php echo esc_html( $link_data->page_title ); ?></strong>
							<div style="height:30px">
									<div id="<?php echo 'moblc_report_1_controls_' . esc_attr( $link_data->id ); ?>"
										class="moblc_link_controls" hidden>
										<?php if ( $post_data && ! is_null( $post_data->ID ) ) { ?>
											<a title="<?php echo esc_attr( $posts_tooltip['edit'] ); ?>"
											href=<?php echo 'post.php?post=' . esc_attr( $post_data->ID ) . '&action=edit'; ?> target='_blank'>Edit</a> |
											<a title="<?php echo esc_attr( $posts_tooltip['view'] ); ?>"
											href=<?php echo esc_url( get_permalink( $post_data->ID ) ); ?> target='_blank'>View</a>
											<?php
										} else {
											echo esc_html( ' Can\'t edit ' ) . '|' . esc_html( ' Can\'t View ' );
										}
										?>
									</div>
							</div>
						</th>
						<td class="moblc_td moblc_td_center moblc_width3">
							<?php if ( 'LINK_TO_BE_CHECKED' === $link_data->status_code || intval( $link_data->status_code ) !== 0 && ( '200' === $link_data->status_code || intval( $link_data->status_code ) < 400 ) ) { ?>
							<strong id="<?php echo 'moblc_response_' . esc_attr( $link_data->id ); ?>"
									style="color:green"><?php echo esc_html( 'LINK_TO_BE_CHECKED' === $link_data->status_code ) ? '<img src="' . esc_url( $moblc_loader ) . '" height="20px" width="20px" class="moblc_loader_margin"></img>' : esc_html( $link_data->status_code ); ?></strong>
						</th>
						<?php } else { ?>
							<strong id="<?php echo 'moblc_response_' . esc_attr( $link_data->id ); ?>"
									class="moblc_red"><?php echo esc_html( $link_data->status_code ); ?></strong></th>
						<?php } ?>
					</tr>
					<?php
			}
		}

			echo '	</tbody>
        </table>';
		?>
			<div class="moblc_report_footer">
				<?php
				if ( $size > 0 ) {
					?>
					<div>
							<form name="moblc_download_report_form" id="moblc_download_report_form" method="post">
								<input type="hidden" name="option" value="moblc_download_report_csv"/>
								<input type="hidden" name="nonce"
									value="<?php echo esc_attr( wp_create_nonce( 'DownloadReportNonce' ) ); ?>"/>
								<input type="submit" name="download" id="moblc_download" class="button button-primary"
									value="Download Report in CSV"/>
							</form>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<script>

function moblc_edit_blc_controls(id, link) {
		jQuery('#moblc_edit_broken_link_' + id).toggle();
	}


	function moblc_edit_blc(e, action) {
		var post_id = e.target.id;
		const page_title = jQuery('#page_title_' + e.target.id).val();
		var link = jQuery('#moblc_edit_blc_' + e.target.id).val();
		var edited_link;
		if (action == 'edit')
			edited_link = jQuery('#moblc_edited_blc_' + e.target.id).val();
		else
			var edited_link = "#";

		var data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_edit_link',
			'nonce': '<?php echo esc_js( wp_create_nonce( 'moblc-edit-link-nonce' ) ); ?>',
			'link_text': link,
			'edited_link_text': edited_link,
			'page_title': page_title
		};

		jQuery.post(ajaxurl, data, function (response) {
			if (response == 'BROKEN') {
				if (action == 'edit') {
					moblc_error_msg("Entered Link is Broken");
					setTimeout(function () {
						location.reload();
					}, 3000);
				}
				jQuery('#moblc_edit-controls-' + e.target.id).css("visibility", "visible");
			} else if (response == 'ERROR') {
				moblc_error_msg("can't edit.");
				jQuery('#moblc_edit-controls-' + e.target.id).css("visibility", "visible");
			} else if (response == 'SUCCESS') {
				moblc_success_msg("Link updated successfully");
				setTimeout(function () {
					location.reload();
				}, 3000);
			}

		});
	}

	function moblc_ignore_page(e, link, id) {

		e.innerHTML = "<strong>Removing..</strong>";

		const data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_ignore_page',
			'nonce': '<?php echo esc_js( wp_create_nonce( 'moblc-ignore-nonce' ) ); ?>',
			'link_text': link,
			'link_id': id
		};

		jQuery.post(ajaxurl, data, function (response) {
			if (response == 'SUCCESS') {
				moblc_success_msg("Page removed from queue");
				jQuery('#' + id).hide();
				document.getElementById('moblc_broken_pages_count').innerHTML = document.getElementById('moblc_broken_pages_count').innerHTML - 1;
			} else {
				e.innerHTML = "Ignore";
				moblc_error_msg("Error while removing !");
			}
		});
	}

	function moblc_check_page(e, link, id) {

		e.innerHTML = "<strong>Rechecking..</strong>";

		const data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_check_page',
			'nonce': '<?php echo esc_js( wp_create_nonce( 'moblc-check-page-nonce' ) ); ?>',
			'link_text': link,
			'link_id': id
		};

		jQuery.post(ajaxurl, data, function (response) {
			if (response == 'SUCCESS') {
				moblc_success_msg("Page will be scanned !");
				jQuery('#' + id).hide();
				location.reload();
			} else if (response == 'NO_LINK_IN_PAGE') {
				moblc_error_msg("No Links are present on this page.");
				jQuery('#' + id).hide();
				document.getElementById('moblc_broken_pages_count').innerHTML = document.getElementById('moblc_broken_pages_count').innerHTML - 1;
			} else {
				e.innerHTML = "Recheck";
				moblc_error_msg("Error while scanning !");
			}
		});
	}

	function moblc_recheck_blc(e, id, link) {
		e.innerHTML = "<strong>Rechecking..</strong>";

		const data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_recheck_link',
			'nonce': '<?php echo esc_js( wp_create_nonce( 'moblc-recheck-link-nonce' ) ); ?>',
			'link_text': link,
			'link_id': id
		};

		jQuery.post(ajaxurl, data, function (response) {
			if (response == 'ERROR') {
				moblc_error_msg("can't recheck.");
			} else {
				e.innerHTML = "Recheck";
				jQuery('#moblc_response_' + id).html(response);
			}
		});
	}

	function moblc_dismiss_blc(id, link) {

		const data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_dismiss_link',
			'nonce': '<?php echo esc_js( wp_create_nonce( 'moblc-dismiss-link-nonce' ) ); ?>',
			'link_text': link,
			'link_id': id
		};

		jQuery.post(ajaxurl, data, function (response) {
			if (response == 'ERROR') {
				moblc_error_msg("Unable to remove broken link from the report.");
			} else if (response == 'SUCCESS') {
				moblc_success_msg("Removed broken link from the report.");
				jQuery('#' + id).hide();
				document.getElementById('moblc_broken_links_count').innerHTML = document.getElementById('moblc_broken_links_count').innerHTML - 1;
			}
		});
	}

	function moblc_not_broken_blc(id, link) {

		const data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_not_broken_link',
			'nonce': '<?php echo esc_js( wp_create_nonce( 'moblc-not-broken-link-nonce' ) ); ?>',
			'link_text': link,
			'link_id': id,
		};

		jQuery.post(ajaxurl, data, function (response) {
			if (response == 'ERROR') {
				moblc_error_msg("can't dismiss.");
			} else if (response == 'SUCCESS') {
				moblc_success_msg("Marked as not broken");
				jQuery('#moblc_response_' + id).html('Marked as Not Broken');
			}
		});
	}

	function moblc_isValidURL(str) {
		var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
			'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
			'((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
			'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
			'(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
			'(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator

		return !!pattern.test(str);
	}


	function moblc_filter_blc(status_300, status_400, status_500, status_others) {

		const data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_filter_link',
			'nonce': '<?php echo esc_js( wp_create_nonce( 'moblc-filter-link-nonce' ) ); ?>',
			'status': {status_300, status_400, status_500, status_others},
		};

		jQuery.post(ajaxurl, data, function (response) {
			if (response == 'ERROR') {
				moblc_error_msg("can't dismiss.");
			} else if (response == 'SUCCESS') {
				window.location.reload();
			}
		});
	}

	function moblc_update_status(ids, recheck_interval) {
		const moblc_ids = ids;
		const data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_update_status',
			'nonce': '<?php echo esc_js( wp_create_nonce( 'moblc_update_status-nonce' ) ); ?>',
			'moblc_ids': moblc_ids
		};

		jQuery.post(ajaxurl, data, function (response) {
			if (response == 'ERROR') {
				moblc_error_msg("can't dismiss.");
			} else {
				let clear = true;
				if (response)
					response.forEach((link) => {
						if (link.status_code !== 'LINK_TO_BE_CHECKED') {
							jQuery('#moblc_response_' + link.id).html(link.status_code);
						} else {
							clear = false;
						}
					})
				if (clear) {
					clearInterval(recheck_interval);
				}
			}
		});
	}

	jQuery(document).ready(function () {
		jQuery("#report").DataTable({
			"order": [[1, "desc"]],
			"drawCallback": function (settings) {
				var recheck_interval = setInterval(() => {
					const ids = [];
					$("table > tbody > tr").each(function () {
						if ($(this).attr('id')) {
							ids.push($(this).attr('id'));
						}
					});
					if (ids.length != 0) {
						moblc_update_status(ids, recheck_interval);
					} else {
						clearInterval(recheck_interval);
					}
				}, 5000);
			}
		});

		jQuery("#report-pages").DataTable({
			"order": [[1, "desc"]]
		});

		jQuery('.moblc_edit_link').click(function (e) {
			e.preventDefault();
			jQuery('#moblc_edit-controls-' + e.target.id).css("visibility", "hidden");
			jQuery('#moblc_edit_broken_link_' + e.target.id).css("visibility", "visible");
			jQuery('#moblc_edit_broken_link_' + e.target.id).css("height", "auto");
		});

		jQuery("#moblc-show-broken-pages").click(() => {
			jQuery("#moblc-broken-pages").slideToggle();
			jQuery("#moblc-show-broken-pages-dashicons").toggleClass('dashicons-arrow-up-alt2').toggleClass('dashicons-arrow-down-alt2');
		});

			jQuery("#moblc-show-broken-links").click(() => {
			jQuery("#moblc-broken-links").slideToggle();

		});	
	});

	function moblc_discard_btn(e) {
		jQuery('#moblc_edit-controls-' + e.target.id).css("visibility", "visible");
		jQuery('#moblc_edit_broken_link_' + e.target.id).css("visibility", "hidden");
		jQuery('#moblc_edit_broken_link_' + e.target.id).css("height", "0");
		location.reload();
	}

	function moblc_edit_btn(e) {
		e.preventDefault();
		$edited_link = jQuery('#moblc_edited_blc_' + e.target.id).val();
		if (moblc_isValidURL($edited_link)) {
			moblc_edit_blc(e, 'edit');
			jQuery('#moblc_edit_broken_link_' + e.target.id).css("visibility", "hidden");
			jQuery('#moblc_edit_broken_link_' + e.target.id).css("height", "0");
		} else {
			moblc_error_msg("Enter Valid URL");
		}
	}

	function moblc_unlink_link(e) {
		e.preventDefault();
		moblc_edit_blc(e, 'unlink')
	}

	function showControls(e) {
		const moblc_id = e.id;
		jQuery('#moblc_report_controls_' + moblc_id).show();
		jQuery('#moblc_report_1_controls_' + moblc_id).show();
	}

	function hideControls(e) {
		const moblc_id = e.id;

		if (jQuery('#moblc_edit_broken_link_' + moblc_id).is(":hidden")) {
			jQuery('#moblc_report_controls_' + moblc_id).hide();
			jQuery('#moblc_report_1_controls_' + moblc_id).hide();
		}

	}

	jQuery(document).ready(function () {
			jQuery("#redirection_report").DataTable({
				"order": [[1, "desc"]]
			});
			jQuery("#moblc_status_filter").change((e) => {
				e.preventDefault();
				const status_300 = jQuery("#3xx").is(":checked");
				const status_400 = jQuery("#4xx").is(":checked");
				const status_500 = jQuery("#5xx").is(":checked");
				const status_others = jQuery("#others").is(":checked");	
			moblc_filter_blc(status_300, status_400, status_500, status_others);
		})

		jQuery("#moblc_status_filter").val('<?php echo get_site_option( 'moblc_filter_link_status' ) !== null ? esc_js( get_site_option( 'moblc_filter_link_status' ) ) : 'all'; ?>')

	});

	function moblc_redirected_link_details() {
		jQuery('#moblc_redirected_link_button').hide();
		jQuery('#moblc_redirection_links').show();

	}
</script>



<script type="text/javascript">
	var tab = localStorage.getItem("moblc_last_tab");
	if (tab !== null) {
		document.getElementById(tab).click();
	}
</script>
