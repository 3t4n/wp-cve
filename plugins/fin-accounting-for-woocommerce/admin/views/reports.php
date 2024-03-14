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
?>admin.php?page=fin_taxes" class="nav-tab"><?php 
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
		<nav class="nav-tab-wrapper">
			<router-link :to="{ name: 'pl'}" @click.native="tab='pl'" :class="'nav-tab' + (tab=='pl'?' nav-tab-active':'')"> <?php 
_e( 'P/L Report', 'finpose' );
?></router-link>
			<router-link :to="{ name: 'balance'}" @click.native="tab='balance'" :class="'nav-tab' + (tab=='balance'?' nav-tab-active':'')"><?php 
_e( 'Balance Sheet', 'finpose' );
?></router-link>
    </nav>
		<div class="tab-content">
			<router-view ref="rw"></router-view>
			<a href="https://finpose.com/docs/reports" target="_blank" style="font-size:13px;">
				<?php 
_e( 'Reports Documentation', 'finpose' );
?>
				<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/external.svg" class="icon-xs"/>
			</a>
		</div>
	</div>
</div>
<!-- /#app -->

<template id="pl">
<div class="fin-container reports">

	<div class="fin-head">
		<div class="fin-head-left">
			<span><?php 
esc_html_e( 'P/L Report', 'finpose' );
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
				<form method="post">
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
			</div>
		</div>
	</div>
	<div class="fin-content">
		<div class="fin-report-wrapper">
			<a @click="exportCSV" class="fin-button report-export"><?php 
esc_attr_e( 'Export', 'finpose' );
?></a>
			<h2 class="tac"><?php 
echo  get_bloginfo( 'name' ) ;
?></h2>
			<div class="tac"><?php 
echo  get_bloginfo( 'url' ) ;
?></div>
			<h2 class="tac mt8"><?php 
esc_html_e( 'Profit / Loss Report', 'finpose' );
?></h2>
			<h2 class="tac"><?php 
echo  $handler->selyear ;
?></h2>

			<table class="plreport" cellpadding="0" cellspacing="0">
				<thead class="toprow">
					<tr>
						<th><?php 
echo  $handler->selyear ;
?></th>
						<?php 
foreach ( $handler->view['data'] as $mk => $mdata ) {
    ?>
							<th><?php 
    echo  $mdata['name'] ;
    ?></th>
						<?php 
}
?>
						<th><?php 
_e( 'Total', 'finpose' );
?></th>
					</tr>
				</thead>
				<thead class="subrow">
					<tr>
						<th colspan="14"><?php 
_e( 'INCOME', 'finpose' );
?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php 
_e( 'Sales', 'finpose' );
?></td>
						<?php 
$tot = 0;
foreach ( $handler->view['data'] as $mk => $mdata ) {
    ?>
							<td><?php 
    echo  ( $mdata['sales'] ? $mdata['sales'] : 0 ) ;
    ?></td>
						<?php 
    $tot += $mdata['sales'];
}
?>
						<td><?php 
echo  $tot ;
?></td>
					</tr>
					<tr>
						<td><?php 
_e( 'Shipping', 'finpose' );
?> <span class="red">(-)</span></td>
						<?php 
$tot = 0;
foreach ( $handler->view['data'] as $mk => $mdata ) {
    ?>
							<td><?php 
    echo  ( $mdata['salessh'] > 0 ? $mdata['salessh'] : '0' ) ;
    ?></td>
						<?php 
    $tot += $mdata['salessh'];
}
?>
						<td><?php 
echo  $tot ;
?></td>
					</tr>
					<tr>
						<td><span class="red">*</span> <?php 
_e( 'Cost of Goods Sold', 'finpose' );
?> <span class="red">(-)</span></td>
						<?php 
$tot = 0;
foreach ( $handler->view['data'] as $mk => $mdata ) {
    ?>
							<td><?php 
    echo  $mdata['cogs'] ;
    ?></td>
						<?php 
    $tot += $mdata['cogs'];
}
?>
						<td><?php 
echo  $tot ;
?></td>
					</tr>
					<tr class="finalrow">
						<td><b><?php 
_e( 'Gross Profit', 'finpose' );
?></b></td>
						<?php 
$tot = 0;
foreach ( $handler->view['data'] as $mk => $mdata ) {
    ?>
							<td class="<?php 
    echo  ( $mdata['gross'] > 0 ? 'plus' : 'minus' ) ;
    ?>"><?php 
    echo  $mdata['gross'] ;
    ?></td>
						<?php 
    $tot += $mdata['gross'];
}
?>
						<td class="<?php 
echo  ( $tot > 0 ? 'plus' : 'minus' ) ;
?>"><?php 
echo  $tot ;
?></td>
					</tr>
				<tbody>
				<thead class="subrow">
					<tr>
						<th colspan="14"><?php 
_e( 'SPENDINGS', 'finpose' );
?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
foreach ( $handler->view['categories'] as $catkey => $cat ) {
    ?>
						<tr class="<?php 
    echo  ( $catkey == 'inventory' ? 'rowdisabled' : '' ) ;
    ?>">
							<td><?php 
    echo  ( $catkey == 'inventory' ? '**' : '' ) ;
    ?> <?php 
    echo  $cat['name'] ;
    ?></td>
							<?php 
    $tot = 0;
    foreach ( $handler->view['data'] as $mk => $mdata ) {
        ?>
								<td><?php 
        echo  ( isset( $mdata['spendings'][$catkey] ) ? $mdata['spendings'][$catkey] : 0 ) ;
        ?></td>
							<?php 
        $tot += ( isset( $mdata['spendings'][$catkey] ) ? $mdata['spendings'][$catkey] : 0 );
    }
    ?>
							<td><?php 
    echo  $tot ;
    ?></td>
						</tr>
					<?php 
}
?>
					<tr class="finalrow">
						<td><b><?php 
_e( 'Total Spendings', 'finpose' );
?></b></td>
						<?php 
$tot = 0;
foreach ( $handler->view['data'] as $mk => $mdata ) {
    ?>
							<td><?php 
    echo  $mdata['sptotal'] ;
    ?></td>
						<?php 
    $tot += $mdata['sptotal'];
}
?>
						<td><?php 
echo  $tot ;
?></td>
					</tr>
				</tbody>
				<tbody class="subrow">
					<tr>
						<td><b><?php 
_e( 'NET PROFIT BEFORE TAXES', 'finpose' );
?></b></td>
						<?php 
$tot = 0;
foreach ( $handler->view['data'] as $mk => $mdata ) {
    ?>
							<td><?php 
    echo  $mdata['ebitda'] ;
    ?></td>
						<?php 
    $tot += $mdata['ebitda'];
}
?>
						<td><?php 
echo  $tot ;
?></td>
					</tr>
				</tbody>
				<tbody class="subrow">
					<tr>
						<td><b><?php 
_e( 'TAXES', 'finpose' );
?></b> <span class="red">(-)</span></td>
						<?php 
$tot = 0;
foreach ( $handler->view['data'] as $mk => $mdata ) {
    ?>
							<td><?php 
    echo  $mdata['taxes'] ;
    ?></td>
						<?php 
    $tot += $mdata['taxes'];
}
?>
						<td><?php 
echo  $tot ;
?></td>
					</tr>
				</tbody>
				<tbody class="subrow">
					<tr>
						<td><b><?php 
_e( 'NET PROFIT', 'finpose' );
?></b></td>
						<?php 
$tot = 0;
foreach ( $handler->view['data'] as $mk => $mdata ) {
    ?>
							<td><?php 
    echo  $mdata['netprofit'] ;
    ?></td>
						<?php 
    $tot += $mdata['netprofit'];
}
?>
						<td><?php 
echo  $tot ;
?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<span>* <?php 
esc_html_e( 'COGS data will start to show up from the date Finpose is installed and a sale is made.', 'finpose' );
?></span><br>
		<span>** <?php 
esc_html_e( "Inventory spendings just shown for reference. It's not included in P/L calculation. For items sold, cost is shown on COGS row.", 'finpose' );
?></span>
	</div>

</div>
</template>

<template id="balance">
<div class="fin-container reports">

<div class="fin-head">
		<div class="fin-head-left">
			<span><?php 
esc_html_e( 'Balance Sheet', 'finpose' );
?></span>
			<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/arrow-right.svg" class="icon">
			<span>{{yearloaded}}</span>
			<a @click="exportCSV" class="fin-button"><?php 
esc_attr_e( 'Export', 'finpose' );
?></a>
		</div>
		<div class="fin-head-right">
			<div class="fin-timeframe">
				<form method="post">
					<select name="year" v-model="selyear">
						<option v-for="n in 10" :value="(thisyear - n)+1" :key="n">{{(thisyear - n)+1}}</option>
					</select>
					<button class="button-go" @click="getBalanceSheet"><?php 
_e( 'Go', 'finpose' );
?></button>
				</form>
			</div>
		</div>
	</div>

	<div class="fin-content">
		<div>
			<table class="fin-table balancesheet" cellpadding="0" cellspacing="0">
				<tbody>
					<tr class="head">
						<td>{{yearloaded}}</td>
						<td></td>
						<td></td>
					</tr>
					<tr class="head">
						<td><?php 
_e( 'Assets', 'finpose' );
?></td>
						<td><?php 
_e( 'Amount', 'finpose' );
?></td>
						<td><?php 
_e( 'Description', 'finpose' );
?></td>
					</tr>
					<tr>
						<td><?php 
_e( 'Cash', 'finpose' );
?></td>
						<td>{{parseFloat(bdata.cash).toFixed(2)}}</td>
						<td><?php 
_e( 'Completed Orders Total', 'finpose' );
?></td>
					</tr>
					<tr>
						<td><?php 
_e( 'Accounts Receivable', 'finpose' );
?></td>
						<td>{{parseFloat(bdata.acc_receive).toFixed(2)}}</td>
						<td><?php 
_e( 'Pending Orders Total', 'finpose' );
?></td>
					</tr>
					<tr>
						<td><?php 
_e( 'Tax Receivable', 'finpose' );
?></td>
						<td>{{parseFloat(bdata.tax_receive).toFixed(2)}}</td>
						<td></td>
					</tr>
					<tr>
						<td><?php 
_e( 'Inventories', 'finpose' );
?></td>
						<td>{{parseFloat(bdata.inventory).toFixed(2)}}</td>
						<td></td>
					</tr>
					<tr class="sum">
						<td><?php 
_e( 'Total Assets', 'finpose' );
?></td>
						<td>{{parseFloat(bdata.assets).toFixed(2)}}</td>
						<td></td>
					</tr>
					<tr class="head">
						<td><?php 
_e( 'Liabilites', 'finpose' );
?></td>
						<td><?php 
_e( 'Amount', 'finpose' );
?></td>
						<td><?php 
_e( 'Description', 'finpose' );
?></td>
					</tr>
					<tr>
						<td><?php 
_e( 'Accrued Expenses', 'finpose' );
?></td>
						<td>{{parseFloat(bdata.expenses).toFixed(2)}}</td>
						<td><?php 
_e( 'All spendings (incl. inventory)', 'finpose' );
?></td>
					</tr>
					<tr>
						<td><?php 
_e( 'Accounts Payable', 'finpose' );
?></td>
						<td>{{parseFloat(bdata.vendor_balance).toFixed(2)}}</td>
						<td><?php 
_e( 'Vendor Balances', 'finpose' );
?></td>
					</tr>
					<tr>
						<td><?php 
_e( 'Tax Payable', 'finpose' );
?></td>
						<td>{{parseFloat(bdata.tax_pay).toFixed(2)}}</td>
						<td><?php 
_e( 'Order Taxes', 'finpose' );
?> + <?php 
_e( 'Shipping Taxes', 'finpose' );
?></td>
					</tr>
					<tr class="sum">
						<td><?php 
_e( 'Total Liabilites', 'finpose' );
?></td>
						<td>{{parseFloat(bdata.liabilities).toFixed(2)}}</td>
						<td></td>
					</tr>
					<tr class="sum">
						<td><?php 
_e( 'Total Equity', 'finpose' );
?></td>
						<td>{{parseFloat(bdata.equity).toFixed(2)}}</td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>



</div>
</template>