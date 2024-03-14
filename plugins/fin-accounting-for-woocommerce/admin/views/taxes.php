<!-- Page Content -->
<div id="finapp" class="fin-container">
	<div class="fin-tabs">	
    <nav class="nav-tab-wrapper w100">
			<a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_dashboard" class="nav-tab"><?php 
_e( 'Dashboard', 'finpose' );
?></a>
      <?php 
?>
      <a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_spendings" class="nav-tab"><?php 
_e( 'Spendings', 'finpose' );
?></a>
      <a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_orders" class="nav-tab"><?php 
_e( 'Orders', 'finpose' );
?></a>
      <a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_taxes" class="nav-tab nav-tab-active"><?php 
_e( 'Taxes', 'finpose' );
?></a>
      <a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_accounts" class="nav-tab"><?php 
_e( 'Accounts', 'finpose' );
?></a>
      <a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_settings" class="nav-tab flr"><?php 
_e( 'Settings', 'finpose' );
?></a>
    </nav>
  </div>
	<div class="finrouter">
		<nav class="nav-tab-wrapper" v-if="tab=='list'">
			<router-link :to="{ name: 'home'}" @click.native="tab='home'" :class="'nav-tab' + (tab=='home'?' nav-tab-active':'')"> < <?php 
_e( 'Back', 'finpose' );
?></router-link>
			<!--<router-link :to="{ name: 'list'}" @click.native="tab='list'" :class="'nav-tab' + (tab=='list'?' nav-tab-active':'')"><?php 
_e( 'List', 'finpose' );
?></router-link>-->
    </nav>
		<div class="tab-content">
			<router-view ref="rw"></router-view>
			<a href="https://finpose.com/docs/taxes" target="_blank" style="font-size:13px;">
				<?php 
_e( 'Taxes Documentation', 'finpose' );
?>
				<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/external.svg" class="icon-xs"/>
			</a>
		</div>
	</div>
</div>
<!-- /#app -->

<template id="taxhome">
<div>
	<div class="fin-head">
		<div class="fin-head-left">
			<span><?php 
_e( 'Taxes', 'finpose' );
?></span>
			<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/arrow-right.svg" class="icon">
			<span><?php 
echo  $handler->selyear ;
?></span>
		</div>
		<div class="fin-head-right">
			<div class="fin-timeframe">
				<form method="post" style="width:145px;">
				<?php 
wp_nonce_field( 'finpost', 'nonce' );
?>
					<select name="year">
						<?php 
foreach ( $handler->getYears() as $yk => $yv ) {
    ?>
							<option value="<?php 
    echo  $yk ;
    ?>" <?php 
    echo  ( $yk == $handler->selyear ? 'selected' : '' ) ;
    ?>><?php 
    echo  $yv ;
    ?></option>
						<?php 
}
?>
					</select>
					<button class="button-go"><?php 
_e( 'Go', 'finpose' );
?></button>
				</form>
				<a class="button-go button-fin" @click="addTaxPaidModal">+ <?php 
_e( 'Add Tax Paid', 'finpose' );
?></a>
			</div>
		</div>
	</div>
	<div class="fin-content">
		<div class="taxes-container">
			<table class="fin-taxes-table" cellpadding="0" cellspacing="0" class="m1">
				<thead>
					<tr>
						<th class="tal"><?php 
echo  $handler->selyear ;
?></th>
						<th><?php 
_e( 'Payable', 'finpose' );
?> ({{currencySymbol}})</th>
						<th><?php 
_e( 'Receivable', 'finpose' );
?> ({{currencySymbol}})</th>
						<th><?php 
_e( 'Paid', 'finpose' );
?> ({{currencySymbol}})</th>
						<th><?php 
_e( 'Balance', 'finpose' );
?> ({{currencySymbol}})</th>
					</tr>
				</thead>
				<tbody>
					<?php 
foreach ( $handler->view['taxes'] as $mname => $mvals ) {
    ?>
						<tr>
						<td class="tal b"><?php 
    echo  $mname ;
    ?></td>
						<td><?php 
    echo  ( $mvals['payable'] > 0 ? '<a @click="listPayableTaxes(\'' . $mvals['msu'] . '\',\'' . $mvals['mse'] . '\')">' . $mvals['payable'] . '</a>' : 0 ) ;
    ?></td>
						<td><?php 
    echo  $mvals['receivable'] ;
    ?></td>
						<td><?php 
    echo  $mvals['paid'] ;
    ?></td>
						<td class="<?php 
    echo  ( $mvals['balance'] > 0 ? 'minus' : 'plus' ) ;
    ?> b"><?php 
    echo  $mvals['balance'] ;
    ?></td>
					</tr>
					<?php 
}
?>
				</tbody>
				<tfoot>
					<tr>
						<th class="tal b"><?php 
_e( 'Totals', 'finpose' );
?></th>
						<th><?php 
echo  $handler->view['totals']['payable'] ;
?></th>
						<th><?php 
echo  $handler->view['totals']['receivable'] ;
?></th>
						<th><?php 
echo  $handler->view['totals']['paid'] ;
?></th>
						<th class="<?php 
echo  ( $handler->view['totals']['balance'] > 0 ? 'minus' : 'plus' ) ;
?> b"><?php 
echo  $handler->view['totals']['balance'] ;
?></th>
					</tr>
				</tfoot>
			</table>
		</div>

	</div>



	<div id="addtaxpaid" class="hidden">
		<div id="fin-transfer" class="fin-modal">
			<div class="fin-modal-content">
				<h2 style="margin:16px 0px;"><?php 
esc_html_e( 'Add New Category', 'finpose' );
?></h2>
				<form id="form-addtaxpaid" @submit.prevent="addTaxPaid">
					<input type="hidden" name="process" value="addTaxPaid">
					<input type="hidden" name="handler" value="taxes">
					
					<div class="flex">
						<div class="w50">
							<div class="pb1">
								<div><b><?php 
esc_html_e( 'Date Paid', 'finpose' );
?></b><span class="placeholder flr">2019-06-25</span></div>
								<input type="text" name="datepaid" data-validate="date" class="datepicker">
							</div>
							<div class="pb1">
								<div><b><?php 
esc_html_e( 'Amount', 'finpose' );
?></b><span class="placeholder flr">2178.14</span></div>
								<input type="text" name="amount" data-validate="money" @input="checkAllowed" @focus="flattenMoneyAdd" @blur="formatMoneyAdd">
							</div>
							<div class="pb1">
								<div><b><?php 
esc_html_e( 'Notes', 'finpose' );
?></b><span class="placeholder flr"><?php 
_e( 'Optional', 'finpose' );
?></span></div>
								<input type="text" name="notes">
							</div>
							<div class="pb1">
								<div><b><?php 
esc_html_e( 'Payment ID', 'finpose' );
?></b><span class="placeholder flr"><?php 
_e( 'Optional', 'finpose' );
?></span></div>
								<input type="text" name="payid" maxlength="128">
							</div>
							<hr>
							<div>
								<input type="submit" class="fin-button flr" value="<?php 
esc_attr_e( 'Save', 'finpose' );
?>">
							</div>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
</template>

<template id="taxlist">
		<div>
			<div class="fin-head">
				<div class="fin-head-left">
					<span><?php 
_e( 'Taxes Payable', 'finpose' );
?></span>
					<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/arrow-right.svg" class="icon">
					<span>{{title}}</span>
				</div>
				<div class="fin-head-right">
					<div class="fin-timeframe">
						<button @click="exportCSV" id="export" class="fin-button flr"><?php 
esc_attr_e( 'Export', 'finpose' );
?></button>
					</div>
				</div>
			</div>
			
			<table class="fin-table" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th><?php 
esc_html_e( 'Order ID', 'finpose' );
?></th>
							<th><?php 
esc_html_e( 'Invoice No', 'finpose' );
?></th>
							<th><?php 
esc_html_e( 'Date Paid', 'finpose' );
?></th>
							<th><?php 
esc_html_e( 'Tax Class', 'finpose' );
?></th>
							<th><?php 
esc_html_e( 'Tax Rate', 'finpose' );
?> %</th>
							<th class="tar"><?php 
esc_html_e( 'Net Price', 'finpose' );
?> (<?php 
esc_html_e( 'Calculated', 'finpose' );
?>)</th>
							<th class="tar"><?php 
esc_html_e( 'Tax Total', 'finpose' );
?></th>
							<th class="tar"><?php 
esc_html_e( 'Ship. Tax', 'finpose' );
?></th>
							<th class="tar"><?php 
esc_html_e( 'Compound', 'finpose' );
?></th>
						</tr>
					</thead>
					<tbody>
							<tr v-for="(tr, index) in tlist">
								<td><a :href="tr.ourl" target="_blank">#{{tr.oid}}</a></td>
								<td>{{tr.invoice_number}}</td>
								<td>{{tr.odate}}</td>
								<td>{{tr.rate_code}}</td>
								<td>{{tr.tax_rate}}</td>
								<td class="tar">{{tr.net_price}}</td>
								<td class="tar">{{tr.tax_total}}</td>
								<td class="tar">{{tr.ship_total}}</td>
								<td class="tar">{{tr.compound?'Yes':'No'}}</td>
							</tr>
					</tbody>
					<tfoot>
						<tr>
							<th>Totals</th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th class="tar">{{totals.net_price}}</th>
							<th class="tar">{{totals.tax}}</th>
							<th class="tar">{{totals.ship}}</th>
							<th class="tar"></th>
						</tr>
					</tfoot>
				</table>
				<table class="tax-summary" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
								<th colspan="2"><b><?php 
_e( 'Summary', 'finpose' );
?></b></th>
						</tr>
						<tr>
							<th class="tal"><?php 
_e( 'Tax Rate', 'finpose' );
?></th>
							<th class="tal"><?php 
_e( 'Total', 'finpose' );
?></th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(val, key) in summary">
								<td>{{key}}%</td>
								<td>{{val.toFixed(2)}}</td>
						</tr>
					</tbody>
				</table>
		</div>
</template>