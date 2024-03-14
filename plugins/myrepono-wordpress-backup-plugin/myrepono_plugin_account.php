<?php
/**
 * @package myRepono
 * @version 2.0.12
 */
/*
Copyright 2016 ionix Limited (email: support@myRepono.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ((defined('WP_MYREPONO_PLUGIN')) && (function_exists('is_admin')) && (is_admin())) {

} else {

	print 'myRepono WordPress Backup Plugin can not load.';
	exit;

}


function myrepono_plugin_account_func() {

	global $myrepono;

	$output = myrepono_plugin_init('account');

	if (!isset($myrepono['tmp']['critical'])) {
		$myrepono['tmp']['critical'] = array();
	}

	if ($output===false) {

		$response = '';
		$icon_url = $myrepono['plugin']['url'].'img/icons';

		if ((isset($myrepono['account']['email'])) && (isset($myrepono['account']['balance'])) && (isset($myrepono['account']['currency']))) {

			$icon_url = $myrepono['plugin']['url'].'img/icons';

			$account_email = $myrepono['account']['email'];
			$account_balance = $myrepono['account']['balance'];
			if (!is_numeric($account_balance)) {
				$account_balance = "0.00";
			}

			$account_balance_warning = '0';
			if ($account_balance<=0) {
				$account_balance_warning = '2';
			} elseif ($account_balance<2.5) {
				$account_balance_warning = '1';
			}

			if (isset($myrepono['account']['balance-warning'])) {
				$account_balance_warning = $myrepono['account']['balance-warning'];
			}

			if ($myrepono['account']['currency']=='GBP') {
				$account_balance = '&pound;'.$account_balance.' GBP';
				$account_currency = 'GBP (British Pounds Sterling)';
			} elseif ($myrepono['account']['currency']=='EUR') {
				$account_balance = '&euro;'.$account_balance.' EUR';
				$account_currency = 'EUR (Euros)';
			} else {
				$account_balance = '$'.$account_balance.' USD';
				$account_currency = 'USD (US Dollars)';
			}

			if ($account_balance_warning=='2') {

				$account_balance_warning = <<<END
<div class="myrepono_error_small"><img src="$icon_url/exclamation.png" width="14" height="14" alt="Error" title="Error" /><span><b>Your account balance has been exhausted!</b>  No further backups will be processed, and all stored backups will be removed.  Please <a href="https://myRepono.com/my/billing/topup/" target="new"><b>top-up your account balance</b></a> as soon as possible.</span></div>
END;

			} elseif ($account_balance_warning=='1') {

				$account_balance_warning = <<<END
<div class="myrepono_error_small"><img src="$icon_url/error.png" width="14" height="14" alt="Error" title="Error" /><span><b>Your account balance is running low!</b>  To avoid disruption to your backup processing and stored backups, please <a href="https://myRepono.com/my/billing/topup/" target="new"><b>top-up your account balance</b></a> as soon as possible.</span></div>
END;

			} else {

				$account_balance_warning = '';

			}

			$output_left = '';


			$response = myrepono_response('success', $myrepono['tmp']['success'], $response);
			$response = myrepono_response('error', $myrepono['tmp']['error'], $response);
			$response = myrepono_response('critical', $myrepono['tmp']['critical'], $response);

			$output = <<<END

$response

<br class="clear" />

<div id="col-container">

	<div id="col-right" style="width:40%;">
		<div class="col-wrap">

			<table class="widefat">
			<thead>
				<tr>
					<th scope="col">Billing Reports</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>If you would like to review a report of your account balance transactions, please proceed to the 'Billing' -&gt; 'Reports' section of your myRepono.com account.</p>
						<p><a href="https://myRepono.com/my/billing/reports/" target="new" class="button-secondary">View Billing Reports</a></p>
					</td>
				</tr>
			</tbody>
			</table>
			<br />
			<table class="widefat">
			<thead>
				<tr>
					<th scope="col">Automatic Top-Up Payments</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>If you would like to automate your top-up payments then the automatic top-up payments feature enables you to schedule top-up payments to be billed automatically, either when your balance reaches a certain level or on a recurring basis.  To set-up automatic top-up payments please proceed to the 'Billing' -&gt; 'Automatic Top-Up Payments' section of your myRepono.com account.</p>
						<p><a href="https://myRepono.com/my/billing/autotopup/" target="new" class="button-secondary">Set-Up Automatic Top-Up Payments</a></p>
					</td>
				</tr>
			</tbody>
			</table>

		</div>
	</div>

	<div id="col-left" style="width:60%;">
		<div class="col-wrap">

			<h3>Your Account</h3>

			<table class="form-table">

			<tr valign="top">
				<th scope="row" width="40%">myRepono Account: </th>
				<td width="60%"><b>$account_email</b></td>
			</tr>

			<tr valign="top">
				<th scope="row">Account Balance: </th>
				<td><b>$account_balance</b>$account_balance_warning</td>
			</tr>

			<tr valign="top">
				<th scope="row">Account Currency: </th>
				<td><b>$account_currency</b></td>
			</tr>

			</table>
			<br />

			<p><a href="https://myRepono.com/my/" target="new" class="button-secondary">Log-In to myRepono.com</a>&nbsp; <a href="https://myRepono.com/my/billing/topup/" target="new" class="button-secondary">Top-Up Account Balance</a></p>

			<br />

			$output_left


			<h3></h3>
		</div>
	</div>
</div>

END;

		} else {

			$output = <<<END

<br class="clear" />

<div id="col-container">
	<div class="col-wrap">

		<b>Account data is not currently available, please check back in a few minutes.</b>

	</div>
</div>

END;

		}
	}

	myrepono_plugin_output('account', $output);

}


?>