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
?>admin.php?page=fin_orders" class="nav-tab nav-tab-active"><?php 
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
	<div class="fin-head">
		<div class="fin-head-left">
			<span><?php 
esc_html_e( 'Orders', 'finpose' );
?></span>
			<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/arrow-right.svg" class="icon">
			<span><?php 
esc_html_e( 'Filter & Export', 'finpose' );
?></span>
		</div>
		<div class="fin-head-right">
			<div class="fin-timeframe">
				<button @click="exportCSV" id="export" class="fin-button flr"><?php 
esc_attr_e( 'Export', 'finpose' );
?></button>
			</div>
		</div>
	</div>

	<div class="fin-content">
		<?php 

if ( isset( $summary ) ) {
    ?>
			<div class="sales-figures">
				<div class="sales-figure">
					<div class="sf-number">
					<?php 
    echo  esc_html( $handler->view['info']['qty'] ) ;
    ?>
					</div>
					<div class="sf-title">
						<?php 
    _e( 'Items sold', 'finpose' );
    ?>
					</div>
				</div>
				<div class="sales-figure">
					<div class="sf-number">
					{{currencySymbol}}<?php 
    echo  esc_html( $handler->view['info']['total'] ) ;
    ?>
					</div>
					<div class="sf-title">
						<?php 
    _e( 'Total', 'finpose' );
    ?>
					</div>
				</div>
				<div class="sales-figure">
					<div class="sf-number">
					{{currencySymbol}}<?php 
    echo  esc_html( $handler->view['info']['avg'] ) ;
    ?>
					</div>
					<div class="sf-title">
						<?php 
    _e( 'Avg. Order Value', 'finpose' );
    ?>
					</div>
				</div>
				<div class="sales-figure">
					<div class="sf-number">
						<?php 
    echo  esc_html( $handler->view['info']['avgtime'] ) ;
    ?>
					</div>
					<div class="sf-title">
						<?php 
    _e( 'Average Time for Sale', 'finpose' );
    ?>
					</div>
				</div>
				<div class="sales-figure">
					<div class="sf-number">
					{{currencySymbol}}<?php 
    echo  esc_html( $handler->view['info']['pl'] ) ;
    ?>
					</div>
					<div class="sf-title">
						<?php 
    _e( 'Profit / Loss', 'finpose' );
    ?>
					</div>
				</div>
			</div>
		<?php 
}

?>

		<div class="orders-container">
			<div class="orders-left">
				<form id="form-filter" @submit.prevent="filterOrders">
				
					<div class="orders-menu">
						<div class="om-heading"><?php 
_e( 'Order', 'finpose' );
?></div>
						<div class="om-sub">
							<div class="om-sub-left"><?php 
_e( 'Status', 'finpose' );
?></div>
							<div class="om-sub-right">
								<select name="status" v-model="filters.status">
									<option value="all"><?php 
_e( 'All', 'finpose' );
?></option>
									<option :key="i" :value="i" v-for="(st,i) in statuses">{{st}}</option>
								</select>
							</div>
						</div>
						<div class="om-sub">
							<div class="om-sub-left"><?php 
_e( 'Total', 'finpose' );
?></div>
							<div class="om-sub-right">
								<select name="totalthan" v-model="filters.totalthan">
									<option value="greater"><?php 
_e( 'Greater than', 'finpose' );
?></option>
									<option value="lower"><?php 
_e( 'Lower than', 'finpose' );
?></option>
								</select>
							</div>
						</div>
						<div class="om-sub">
							<div class="om-sub-left"></div>
							<div class="om-sub-right"><input type="number" name="total" v-model="filters.total"></div>
						</div>
						<div class="om-heading"><?php 
_e( 'Date', 'finpose' );
?></div>
						<div class="om-sub">
							<div class="om-sub-left"><?php 
_e( 'Type', 'finpose' );
?></div>
							<div class="om-sub-right">
								<select name="datetype" v-model="filters.datetype">
									<option value="date_created"><?php 
_e( 'Date created', 'finpose' );
?></option>
									<option value="date_paid"><?php 
_e( 'Date paid', 'finpose' );
?></option>
									<option value="date_invoice"><?php 
_e( 'Invoice date', 'finpose' );
?> (WC Invoices & Packing Slips)</option>
								</select>
							</div>
						</div>
						<div class="om-sub">
							<div class="om-sub-left"><?php 
_e( 'Start', 'finpose' );
?></div>
							<div class="om-sub-right"><input type="text" id="datestart" name="datestart" data-validate="date" class="datepicker" v-model="filters.datestart"></div>
						</div>
						<div class="om-sub">
							<div class="om-sub-left"><?php 
_e( 'End', 'finpose' );
?></div>
							<div class="om-sub-right"><input type="text" id="dateend" name="dateend" data-validate="date" class="datepicker" v-model="filters.dateend"></div>
						</div>
						<div class="om-heading"><?php 
_e( 'Payment', 'finpose' );
?></div>
						<div class="om-sub">
							<div class="om-sub-left"><?php 
_e( 'Method', 'finpose' );
?></div>
							<div class="om-sub-right">
								<select name="gateway" v-model="filters.gateway">
									<option></option>
									<?php 
foreach ( $handler->view['gwlist'] as $gwid => $gwname ) {
    ?>
										<option value="<?php 
    echo  $gwid ;
    ?>"><?php 
    echo  $gwname ;
    ?></option>
									<?php 
}
?>
								</select>
							</div>
						</div>
						<div class="om-sub">
							<div class="om-sub-left"><?php 
_e( 'Currency', 'finpose' );
?></div>
							<div class="om-sub-right">
								<select name="gateway" v-model="filters.currency">
									<option value="">All</option>
									<option :value="i" :key="i" v-for="(cr,i) in currencies">{{cr}}</option>
								</select>
							</div>
						</div>

						<div class="om-heading"><?php 
_e( 'Customer', 'finpose' );
?></div>
						<div class="om-sub">
							<div class="om-sub-left"><?php 
_e( 'Email', 'finpose' );
?></div>
							<div class="om-sub-right">
								<input type="text" name="customeremail" v-model="filters.customeremail">
							</div>
						</div>
						<div class="om-sub">
							<div class="om-sub-left"><?php 
_e( 'First Name', 'finpose' );
?></div>
							<div class="om-sub-right">
								<input type="text" name="customerfname" v-model="filters.customerfname">
							</div>
						</div>
						<div class="om-sub">
							<div class="om-sub-left"><?php 
_e( 'Last Name', 'finpose' );
?></div>
							<div class="om-sub-right">
								<input type="text" name="customerlname" v-model="filters.customerlname">
							</div>
						</div>


						<div class="om-sub">
							<div class="om-sub-left"></div>
							<div class="om-sub-right"><input type="submit" class="fin-button flr" value="<?php 
esc_attr_e( 'Filter', 'finpose' );
?>"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="orders-right">
				<div class="orders-content">
					<table class="fin-table fin-table-thin fin-table-export" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>ID</th>
								<th><?php 
_e( 'Date created', 'finpose' );
?></th>
								<th v-if="add_wcpdf>0"><?php 
_e( 'Invoice No', 'finpose' );
?></th>
								<th v-if="add_wcpdf>0"><?php 
_e( 'Invoice Date', 'finpose' );
?></th>
								<th><?php 
_e( 'Status', 'finpose' );
?></th>
								<th><?php 
_e( 'Account', 'finpose' );
?></th>
								<th><?php 
_e( 'Customer', 'finpose' );
?></th>
								<th><?php 
_e( 'Country', 'finpose' );
?></th>
								<th class="tar"><?php 
_e( 'Tax', 'finpose' );
?></th>
								<th class="tar"><?php 
_e( 'Shipping', 'finpose' );
?></th>
								<th class="tar"><?php 
_e( 'Shipping Tax', 'finpose' );
?></th>
								<th class="tar"><?php 
_e( 'Subtotal', 'finpose' );
?></th>
								<th class="tar"><?php 
_e( 'Total', 'finpose' );
?></th>
								<th class="tar"><?php 
_e( 'Currency', 'finpose' );
?></th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(order, index) in orders">
								<td><a :href="order.url" target="_blank">#{{order.id}}</a></td>
								<td>{{order.date}}</td>
								<td v-if="add_wcpdf>0">{{order.wcpdf_number}}</td>
								<td v-if="add_wcpdf>0">{{order.wcpdf_date}}</td>
								<td>{{printStatus(order.status)}}</td>
								<td>{{order.pm}}</td>
								<td>{{order.cus}}</td>
								<td>{{order.geo}}</td>
								<td class="tar">{{formatMoney(order.tax)}}</td>
								<td class="tar">{{formatMoney(order.shipamount)}}</td>
								<td class="tar">{{formatMoney(order.shiptax)}}</td>
								<td class="tar">{{formatMoney(order.st)}}</td>
								<td class="tar">{{formatMoney(order.total)}}</td>
								<td class="tar">{{order.currency}}</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th class="tal b"><?php 
_e( 'Totals', 'finpose' );
?></th>
								<th colspan="2" v-if="add_wcpdf>0"></th>
								<th colspan="5">
								<th>{{formatMoney(totals.tax)}}</th>
								<th>{{formatMoney(totals.shipamount)}}</th>
								<th>{{formatMoney(totals.shiptax)}}</th>
								<th>{{formatMoney(totals.st)}}</th>
								<th class="b">{{formatMoney(totals.total)}}</th>
								<th></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>

		<a href="https://finpose.com/docs/orders" target="_blank" style="font-size:13px;">
			<?php 
_e( 'Orders Documentation', 'finpose' );
?>
			<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/external.svg" class="icon-xs"/>
		</a>								
	</div>

</div>
