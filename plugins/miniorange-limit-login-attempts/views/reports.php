<div class="mo_lla_divided_layout">
<div class="mo_lla_setting_layout">
	<div class="molla-sub-tab-header"> 
				<div id="molla-login-tans" class="molla-sub-tab molla-sub-tab-active" onclick="molla_reports_tabs(this)">Login Transactions</div>
				<div id="molla-error-report" class="molla-sub-tab" onclick="molla_reports_tabs(this)">Error Report</div>
	</div>

	<div id="molla-login-tans-div" class="mo-lla-sub-tabs	mo-lla-sub-tabs-active">
	<div>
		<form name="f" method="post" action="" id="manualblockipform" >
			<input type="hidden" name="option" value="mo_lla_manual_clear" />
			<table>
				<tr>
					<td style="width: 100%">
						<h2>
							Login Transactions Report
						</h2>
					</td>
					<td>
						<input type="submit" class="button button-primary button-large mo_lla_button1" value="Clear Login Reports" />
					</td>
				</tr>
			</table>
			<br>
		</form>
	</div>
	<table id="login_reports" class="display" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>IP Address</th>
				<th>Username</th>
				<th>Status</th>
				<th>User Action</th>
				<th>TimeStamp</th>
			</tr>
		</thead>
		<tbody>
			<?php Mo_lla_showLoginTransactions($logintranscations); ?>
		</tbody>
	</table>
	</div>

	<div id="molla-error-report-div" class="mo-lla-sub-tabs">
		<div>
			<form name="f" method="post" action="" id="manualblockipforms" >
			<input type="hidden" name="option" value="mo_wpns_manual_errorclear" />
			<table>
				<tr>
					<td style="width: 100%">
						<h2>Error Report</h2>
					</td>
					<td>
						<input type="submit"" class="button button-primary button-large mo_lla_button1" value=" Clear Error Reports" />
					</td>
				</tr>
			</table>
			<br>
			</form>
		</div>
		<table id="error_reports" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>IP Address</th>
					<th>Username</th>
					<th>URL</th>
					<th>Error Type</th>
					<th>TimeStamp</th>
				</tr>
			</thead>
			<tbody>
				<?php Mo_lla_showErrorTransactions($errortranscations); ?>
			</tbody>
		</table>
	</div>
</div>
<script>
	jQuery(document).ready(function() {
		$("#login_reports").DataTable({
			"order": [[ 3, "desc" ]]
		});
		$("#error_reports").DataTable({
			"order": [[ 4, "desc" ]]
		});
	} );


	function molla_reports_tabs(component){
		const tabs = ['molla-login-tans','molla-error-report'];
	
		tabs.forEach(element => {
			if(component.id==element){
				jQuery('#'+element+'-div').show();
				jQuery('#'+element).addClass('molla-sub-tab-active');
			}
			else{
				jQuery('#'+element+'-div').hide();
				jQuery('#'+element).removeClass('molla-sub-tab-active');
			}
	});
}
</script>