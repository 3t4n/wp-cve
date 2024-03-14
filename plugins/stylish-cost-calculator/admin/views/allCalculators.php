<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$isSCCFreeVersion = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
wp_localize_script( 'scc-backend', 'pageAllCalculators', [ 'nonce' => wp_create_nonce( 'all-calculators-page' ) ] );
?>
<div class="row">
	<div class="scc_title_bar">All Calculator Forms</div>
</div>
<div class="row ms-1">
	<div class="row m-0 col-md-12 scc_container_forms">
		<!-- EACH ELEMENT -->
		<span id="text_no_calculator_" style="display: none;"><?php echo '<span>You did not add any calculator yet, you must add a calculator first. Click <a href="' . esc_url( admin_url( 'admin.php?page=scc-tabs' ) ) . '">here</a></span>'; ?></span>
		<?php

        foreach ( $forms as $f ) {
            if ( $f->urlStatsArray == null ) {
                $f->urlStatsArray = '{}';
            }

            if ( $f->created_at == '0000-00-00 00:00:00' ) {
                $createdAtText = '';
            } else {
                $createdAtText = 'Created At: ' . mysql2date( 'Y/m/d H:i:s', $f->created_at );
            }
            ?>
			<div id="scc_calculator_<?php echo intval( $f->id ); ?>" class="col-md-6 p-0">
				<div style="text-align: center;background-color:#fff; margin-bottom:30px; margin-right:30px; padding:30px;border-radius:10px">
					<h2 class="edith2" title="<?php echo esc_attr( $createdAtText ); ?>"><?php echo esc_attr( $f->formname ); ?></h2>
					<p class="custom-short-code-p-tag" style="background: #F8F9FF;padding:10px;border-radius: 6px;">
						Shortcode is <strong>[scc_calculator type='text' idvalue='<?php echo intval( $f->id ); ?>']</strong>
					</p>
					<a class="opt-secondary" style="padding:8px 25px;color:white;background:#314af3;text-decoration: none;font-weight:normal;" href="<?php echo esc_html( add_query_arg( 'id_form', $f->id ) ); ?>">Edit</a>
					<a class="opt-secondary" onclick="deleteSCC(<?php echo intval( $f->id ); ?>)" href="javascript:void(0)">Delete</a>
					<a class="opt-secondary" onclick="createDuplicate(<?php echo intval( $f->id ); ?>)" href="javascript:void(0)" >Duplicate</a>
					<a class="opt-secondary" onclick="downloadBackupFromDashboard(<?php echo intval( $f->id ); ?>)" href="javascript:void(0)">Export</a>
					<a class="opt-secondary" onclick="showUrlsPopup(<?php echo intval( $f->id ); ?>, this)" href="javascript:void(0)">URLs</a>
					<?php if ( ! $isSCCFreeVersion ) { ?>
					<a class="opt-secondary" href="<?php echo esc_url( admin_url( "admin.php?page=scc-quote-management-screen&id={$f->id}" ) ); ?>">View Quotes</a>
					<?php } ?>
					<a id="downloadAnchorElem"></a>
					<div style="margin-bottom: 70px;margin-left: 20px;" class="yesnoeditscc" id="yesnoeditscc_45"><span class="yesnoeditINN" id="yesnoeditINN_45">YES</span> | <span class="yesnoeditINN">NO</span></div>
					<script id="urlstats-<?php echo intval( $f->id ); ?>" type="text/json">
						<?php echo esc_attr( $f->urlStatsArray ); ?>
					</script>
				</div>	
			</div>
			<?php
        }
?>
		<!-- EACH ELEMENT -->
		<!--ADD NEW CALCULATOR ITEM BUTTON-->
		<div class="col-md-6 p-0">
                <div style="text-align: center;margin-bottom: 30px;background-color:transparent; margin-right:30px;padding:30px;padding-top:70px; border-radius:10px">
					<a class="scc_button" href="<?php echo esc_url( admin_url( 'admin.php?page=scc-tabs' ) ); ?>">Start New Calculator</a>
                </div>
			</div>
		<!--END ADD NEW CALCULATOR ITEM BUTTON-->
	</div>
</div>
<div class="modal df-scc-modal fade in" role="dialog" id="show-calc-urls-container" style="padding-right: 15px;"></div>
<style>
	.scc-urls-table {
		border: 2px solid #314af3;
	}
	.scc-urls-table thead {
		background-color: #314af3;
	}
	.scc-urls-table tr th {
		color: #f8f9ff;
	}
	.scc-urls-table tr td:nth-child(1n) {    
		border-right: 2px solid #314af3;

	}

	.scc-urls-table tr th,
	.scc-urls-table tr td {
		padding: 5px;
		margin: 5px;
	}
	.scc-urls-table tr td:nth-child(2n) {
		text-align: center;
	}
</style>
<script>
	jQuery(document).ready(function() {
		checkNoCalculator()
		jQuery('.edith2').tooltip()
	})
	/**
	 * *Checks if there are calculator or not to show the message
	 */
	function checkNoCalculator() {
		let calculators = jQuery('div[id^="scc_calculator_"]')
		let message = jQuery("#text_no_calculator_")
		if (calculators.length == 0) {
			message.css("display", "")
		} else {
			message.css("display", "none")
		}
	}

	function showUrlsPopup(calcId, $this) {
		return
	}

	function deleteSCC(calculator_id) {

		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#314AF3',
			cancelButtonColor: '#FF2F00',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				showLoadingChanges()
				jQuery.ajax({
					url: ajaxurl,
					data: {
						action: 'sccCalculatorOp',
						op: "del",
						id_form: calculator_id,
						nonce: pageAllCalculators.nonce
					},
					success: function(data) {
						var response = JSON.parse(data);
						if (response.passed == true) {
							jQuery("#scc_calculator_" + calculator_id).remove()
							checkNoCalculator()
							showSweet(true, "The calculator form was deleted.")
						} else {
							showSweet(false, "An error occurred. Please refresh the page and try again")
						}
					}
				})
			}
		})
	}

	/**
	 * *To duplicate calculator
	 */
	function createDuplicate(calId) {
		return
	}

	/**
	 * *To backup form with calculator id
	 */
	function downloadBackupFromDashboard(calId) {
		return
	}

	function showSweet(respuesta, message) {
		if (respuesta) {
			Swal.fire({
				toast: true,
				title: message,
				icon: "success",
				showConfirmButton: false,
				background: 'white',
				timer: 3000,
				position: 'top-end',
			})
		} else {
			Swal.fire({
				toast: true,
				title: message,
				icon: "error",
				showConfirmButton: false,
				background: 'white',
				timer: 3000,
				position: 'top-end',
			})
		}
	}
</script>

<script type="text/html" id="tmpl-show-calc-urls">
	<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
		<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation" style="max-width:80%; max-height:90%;min-height:50%;min-width:25%">
			<button class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" data-dismiss="modal"><svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
					<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z">
					</path>
				</svg></button>
			<div class="df-scc-euiModal__flex">
				<div class="df-scc-euiModalHeader">
					<div class="df-scc-euiModalHeader__title">{{data.title}}</div>
				</div>
				<div class="df-scc-euiModalBody">
					<div class="df-scc-euiModalBody__overflow">
						<div class="df-scc-euiText df-scc-euiText--medium">
							<div id="table-data-container">
								<# if (Object.keys(data.rows).length) { #>
								<table class="scc-urls-table" style="">
									<thead>
										<tr>
											<th>URL</th>
											<th>Visits</th>
										</tr>
									</thead>
									<tbody>
										<# Object.entries(data.rows).forEach(e => { #>
											<tr>
												<td><a target="_blank" style="white-space: nowrap" href={{ e[0] }}>{{ e[0] }}</a></td>
												<td>{{ e[1] }}</td>
											</tr>
										<# }) #>
									</tbody>
								</table>
								<# } else { #>
									<p style="text-align: center; color: #fff; background-color: #314af3;">No links found</p>
								<# } #>
							</div>
							<p class="trn text-danger" style="display:none;">There has been an error. Try again</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
