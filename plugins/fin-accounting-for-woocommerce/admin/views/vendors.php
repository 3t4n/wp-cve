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
		<div class="tab-content">
			<router-view ref="rw"></router-view>
			<a href="https://finpose.com/docs/vendors" target="_blank" style="font-size:13px;">
				<?php 
_e( 'Vendors Documentation', 'finpose' );
?>
				<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/external.svg" class="icon-xs"/>
			</a>
		</div>
	</div>
</div>
<!-- /#app -->

<template id="vendors">
	<div>
		<div class="fin-head">
			<div class="fin-head-left">
				<span><?php 
_e( 'Vendors', 'finpose' );
?></span>
			</div>
		</div>
		<div class="fin-content">
			<div class="productfilter">
				<div class="pagefilters">
					<a class="fin-button fin-button-xs" @click="addVendorModal">+ <?php 
_e( 'Add New', 'finpose' );
?></a>
				</div>

				<div class="pageactions">
					<div class="fin-timeframe">
						<select @change="setPage" v-model="pager.page">
							<option v-for="n in pager.pages" :value="n"><?php 
_e( 'Page', 'finpose' );
?> {{n}}</option>
						</select>

						<select @change="setPerPage" v-model="pager.perpage">
							<option value="10">10</option>
							<option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
					</div>
				</div>
			</div>

			<div class="spendings-container">
				<div class="spendings-right">
					<table class="fin-table" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th><?php 
_e( 'Name', 'finpose' );
?></th>
								<th><?php 
_e( 'Total', 'finpose' );
?></th>
								<th><?php 
_e( 'Paid', 'finpose' );
?></th>
								<th><?php 
_e( 'Balance', 'finpose' );
?></th>
								<th class="tar"><?php 
_e( 'Actions', 'finpose' );
?></th>
							</tr>
						</thead>
						<tbody id="spending-rows">
								<tr v-for="(vendor, index) in vendors">
									<td>{{vendor.vname}}</td>
									<td>{{formatMoney(vendor.total)}}</td>
									<td>{{formatMoney(vendor.paid)}}</td>
									<td><span :class="vendor.unpaid>0?'minus':'plus'">{{formatMoney(vendor.unpaid)}}</span></td>
									<td class="tar">
										<a href="javascript:void(0);" @click="showPurchaseOrders(vendor)" title="<?php 
_e( 'Purchase Orders', 'finpose' );
?>"><img :src="finurl + 'assets/img/books.svg'"/></a>
										<a href="javascript:void(0);" @click="showPayments(vendor)" title="<?php 
_e( 'Payments', 'finpose' );
?>"><img :src="finurl + 'assets/img/acc-other.svg'"/></a>
										<a href="javascript:void(0);" @click="editVendor(vendor)" title="<?php 
_e( 'Edit Vendor', 'finpose' );
?>"><img :src="finurl + 'assets/img/pencil.svg'"/></a>
									</td>
								</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div id="addvendor" class="hidden">
			<div class="fin-modal">
				<div class="fin-modal-content">
					<h2 style="margin:16px 0px;"><?php 
_e( 'Add New Vendor', 'finpose' );
?></h2>
					<form id="form-addvendor" @submit.prevent="addVendor">
						<input type="hidden" name="process" value="addVendor">
						<input type="hidden" name="handler" value="vendors">
						<div class="flex">
							<div class="w100">
								<div class="pb1">
									<b><?php 
_e( 'Name', 'finpose' );
?></b>
									<input type="text" name="name" data-validate="required" maxlength="128">
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

		<div id="editvendor" class="hidden">
			<div class="fin-modal">
				<div class="fin-modal-content">
					<h2 style="margin:16px 0px;"><?php 
_e( 'Edit Vendor', 'finpose' );
?></h2>
					<form id="form-editvendor" @submit.prevent="submitEditVendor">
						<input type="hidden" name="process" value="editVendor">
						<input type="hidden" name="handler" value="vendors">
						<input type="hidden" name="vid" v-model="row.vid">
						<div class="flex">
							<div class="w100">
								<div class="pb1">
									<b><?php 
_e( 'Name', 'finpose' );
?></b>
									<input type="text" name="name" data-validate="required" maxlength="128" v-model="row.vname">
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


	<template id="purchases">
		<div>
			<div class="fin-head">
				<div class="fin-head-left">
					<span><?php 
_e( 'Purchase Orders', 'finpose' );
?> - {{vendor.vname}}</span>
				</div>
			</div>
			<div class="fin-content">
				<div class="productfilter">
					<div class="pagefilters">
						<a class="fin-button fin-button-xs danger" @click="back"> < <?php 
_e( 'Back', 'finpose' );
?></a>
						<a class="fin-button fin-button-xs" @click="addPOModal">+ <?php 
_e( 'Add New', 'finpose' );
?></a>
					</div>

					<div class="pageactions">
						<div class="fin-timeframe">
							<select @change="setPage" v-model="pager.page">
								<option v-for="n in pager.pages" :value="n"><?php 
_e( 'Page', 'finpose' );
?> {{n}}</option>
							</select>

							<select @change="setPerPage" v-model="pager.perpage">
								<option value="10">10</option>
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="100">100</option>
							</select>
						</div>
					</div>
				</div>

				<div class="spendings-container">
					<div class="spendings-right">
						<table class="fin-table" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th><?php 
_e( 'ID', 'finpose' );
?></th>
									<th><?php 
_e( 'Title', 'finpose' );
?></th>
									<th><?php 
_e( 'Status', 'finpose' );
?></th>
									<th><?php 
_e( 'Due Date', 'finpose' );
?></th>
									<th class="tar"><?php 
_e( 'Amount', 'finpose' );
?></th>
									<th class="tar"><?php 
_e( 'Paid', 'finpose' );
?></th>
									<th class="tar"><?php 
_e( 'Remaining', 'finpose' );
?></th>
									<th class="tar"><?php 
_e( 'Notes', 'finpose' );
?></th>
									<th class="tar"><?php 
_e( 'Actions', 'finpose' );
?></th>
								</tr>
							</thead>
							<tbody id="spending-rows">
								<tr v-for="(po, index) in porders">
									<td>{{po.poid}}</td>
									<td>{{po.title}} <span v-if="po.attfile"><a :href="po.attfile" target="_blank"><img :src="finurl + 'assets/img/attachment.svg'"/></a></span></td>
									<td><span :class="po.status=='paid'?'plus':'minus'" @click="rotateStatus(po.poid, po.status)" style="cursor:pointer;text-decoration:underline">{{statusName(po.status)}}</span></td>
									<td>{{formatDate(po.timedue, 'yearday')}}</td>
									<td class="tar">{{formatMoney(po.amount)}}</td>
									<td class="tar">{{formatMoney(po.amount_paid)}}</td>
									<td class="tar">{{formatMoney(po.amount - po.amount_paid)}}</td>
									<td class="tar"><span class="tooltip" v-if="po.notes"><img :src="finurl + 'assets/img/note-text.svg'"/><span class="tooltiptext">{{po.notes}}</span></span></td>
									<td class="tar">
										<a href="javascript:void(0);" @click="addPaymentModal(po)" title="<?php 
_e( 'Add Payment', 'finpose' );
?>"><img :src="finurl + 'assets/img/acc-card.svg'"/></a>
										<a href="javascript:void(0);" @click="displayUploader(po)" title="<?php 
_e( 'Add File', 'finpose' );
?>"><img :src="finurl + 'assets/img/upload.svg'"/></a>
										<a href="javascript:void(0);" @click="editPOModal(po)" title="<?php 
_e( 'Edit Purchase Order', 'finpose' );
?>"><img :src="finurl + 'assets/img/pencil.svg'"/></a>
										<a href="javascript:void(0);" @click="deletePO(index, po)" title="<?php 
_e( 'Delete Purchase Order', 'finpose' );
?>"><img :src="finurl + 'assets/img/cross.svg'"/></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>


			</div>

			<div id="addporder" class="hidden">
				<div class="fin-modal">
					<div class="fin-modal-content">
						<h2 style="margin:16px 0px;"><?php 
_e( 'Add Purchase Order', 'finpose' );
?></h2>
						<form id="form-addporder" @submit.prevent="addPurchaseOrder">
							<input type="hidden" name="process" value="addPurchaseOrder">
							<input type="hidden" name="handler" value="vendors">
							<input type="hidden" name="vid" :value="vendor.vid">
							<div class="flex">
								<div class="w48">
									<div class="pb1">
										<b><?php 
_e( 'Title', 'finpose' );
?></b>
										<input type="text" name="title" data-validate="required" maxlength="128">
									</div>
									<div class="pb1">
										<b><?php 
_e( 'Spending Type', 'finpose' );
?></b>
										<select name="stype" @change="setCatlist" v-model="form.stype">
											<option value="cost" selected><?php 
_e( 'Cost', 'finpose' );
?></option>
											<option value="expense"><?php 
_e( 'Expense', 'finpose' );
?></option>
											<option value="acquisition"><?php 
_e( 'Acquisition', 'finpose' );
?></option>
										</select>
									</div>
									<div class="pb1">
										<b><?php 
_e( 'Spending Category', 'finpose' );
?></b>
										<select name="scat" id="cat-select-add">
											<option v-for="(item, index) in catlist" :value="index">{{item.name}}</option>
										</select>
									</div>
								</div>
								<div class="w48">
									<div class="pb1">
										<b><?php 
_e( 'Due Date', 'finpose' );
?></b>
										<input type="text" name="datedue" data-validate="date" class="datepicker">
									</div>
									<div class="pb1">
										<div><b><?php 
_e( 'Amount', 'finpose' );
?></b><span class="placeholder flr">2154.68</span></div>
										<input type="text" name="amount" data-validate="money" @input="checkAllowed" @focus="flattenAdd" @blur="formatAdd">
									</div>
									<div class="pb1">
										<b><?php 
_e( 'Notes', 'finpose' );
?></b>
										<input type="text" name="notes" maxlength="512">
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


			<div id="editporder" class="hidden">
				<div class="fin-modal">
					<div class="fin-modal-content">
						<h2 style="margin:16px 0px;"><?php 
_e( 'Edit Purchase Order', 'finpose' );
?></h2>
						<form id="form-editporder" @submit.prevent="editPurchaseOrder">
							<input type="hidden" name="process" value="editPurchaseOrder">
							<input type="hidden" name="handler" value="vendors">
							<input type="hidden" name="poid" :value="form.poid">
							<input type="hidden" name="vid" :value="form.vid">
							<div class="flex">
								<div class="w48">
									<div class="pb1">
										<b><?php 
_e( 'Title', 'finpose' );
?></b>
										<input type="text" name="title" data-validate="required" maxlength="128" v-model="form.title">
									</div>
									<div class="pb1">
										<b><?php 
_e( 'Spending Type', 'finpose' );
?></b>
										<select name="stype" @change="setCatlist" v-model="form.stype">
											<option value="cost" selected><?php 
_e( 'Cost', 'finpose' );
?></option>
											<option value="expense"><?php 
_e( 'Expense', 'finpose' );
?></option>
											<option value="acquisition"><?php 
_e( 'Acquisition', 'finpose' );
?></option>
										</select>
									</div>
									<div class="pb1">
										<b><?php 
_e( 'Spending Category', 'finpose' );
?></b>
										<select name="scat" id="cat-select-add" v-model="form.scat">
											<option v-for="(item, index) in catlist" :value="index">{{item.name}}</option>
										</select>
									</div>
								</div>
								<div class="w48">
									<div class="pb1">
										<b><?php 
_e( 'Due Date', 'finpose' );
?></b>
										<input type="text" name="datedue" data-validate="date" class="datepicker" v-model="format_date">
									</div>
									<div class="pb1">
										<div><b><?php 
_e( 'Amount', 'finpose' );
?></b><span class="placeholder flr">2154.68</span></div>
										<input type="text" name="amount" data-validate="money" @input="checkAllowed" @focus="flattenAdd" @blur="formatAdd" v-model="form.amount">
									</div>
									<div class="pb1">
										<b><?php 
_e( 'Notes', 'finpose' );
?></b>
										<input type="text" name="notes" maxlength="512" v-model="form.notes">
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



			<div id="addpayment" class="hidden">
				<div class="fin-modal">
					<div class="fin-modal-content">
						<h2 style="margin:16px 0px;"><?php 
_e( 'Add Payment', 'finpose' );
?></h2>
						<form id="form-addpayment" @submit.prevent="addPayment">
							<input type="hidden" name="process" value="addSpending">
							<input type="hidden" name="handler" value="spendings">
							<input type="hidden" name="vid" :value="form.vid">
							<input type="hidden" name="poid" :value="form.poid">
							<div class="flex">
								<div class="w48">
									<div class="pb1">
										<b><?php 
_e( 'Purchase Order', 'finpose' );
?></b>
										<input type="text" name="name" maxlength="512" v-model="form.title">
									</div>
									<div class="pb1">
										<b><?php 
_e( 'Spending Type', 'finpose' );
?></b>
										<select name="type" @change="setCatlist" v-model="form.stype">
											<option value="cost" selected><?php 
_e( 'Cost', 'finpose' );
?></option>
											<option value="expense"><?php 
_e( 'Expense', 'finpose' );
?></option>
											<option value="acquisition"><?php 
_e( 'Acquisition', 'finpose' );
?></option>
										</select>
									</div>
									<div class="pb1">
										<b><?php 
_e( 'Spending Category', 'finpose' );
?></b>
										<select name="cat" id="cat-select-add" v-model="form.scat">
											<option v-for="(item, index) in catlist" :value="index">{{item.name}}</option>
										</select>
									</div>
									<div class="pb1">
										<b><?php 
_e( 'Paid With', 'finpose' );
?></b>
										<select name="paidwith" v-model="form.paidwith" data-validate="selected">
											<option :value="i" v-for="(a,i) in accounts">{{a.name}}</option>
										</select>
									</div>
									<hr>
									<div><?php 
_e( 'Payment record will be saved in spendings. If you want to modify later, it will be accessible over spendings page.', 'finpose' );
?></div>
								</div>
								<div class="w48">
									<div class="pb1">
										<b><?php 
_e( 'Date Paid', 'finpose' );
?></b>
										<input type="text" name="datepaid" data-validate="date" class="datepicker">
									</div>
									<div class="pb1">
										<div><b><?php 
_e( 'Amount Paid', 'finpose' );
?></b><span class="placeholder flr">2154.68</span></div>
										<input type="text" name="amount" data-validate="money" @input="checkAllowed" @focus="flattenAdd" @blur="formatAdd" v-model="form.balance">
									</div>
									<div class="pb1">
										<div><b><?php 
_e( 'Tax Receivable', 'finpose' );
?></b><span class="placeholder flr">2154.68</span></div>
										<input type="text" name="tr" data-validate="money" @input="checkAllowed" @focus="flattenAdd" @blur="formatAdd" value="0.00">
									</div>
									<div class="pb1">
										<b><?php 
_e( 'Notes', 'finpose' );
?></b>
										<input type="text" name="notes" maxlength="512">
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


			<div id="attachmentModal" class="hidden">
				<div class="fin-modal">
					<div class="fin-modal-content">
					<h2 style="margin:16px 0px;"><?php 
esc_html_e( 'Attach File to Purchase Order', 'finpose' );
?></h2>
					<form id="form-attach" @submit.prevent="uploadAttachment">
						<?php 
wp_nonce_field( 'finpost', 'nonce' );
?>
						<input type="hidden" name="process" value="attachFile">
						<input type="hidden" name="handler" value="vendors">
						<input type="hidden" id="attkey" name="poid" v-model="form.poid">
						<div class="flex">
							<div class="w50">
								<div class="flex container-form">
									<div class="w90">
										<div>
											<b><?php 
esc_html_e( 'Title', 'finpose' );
?></b>
											<span>{{form.title}}</span>
										</div>
										<div>
											<b><?php 
esc_html_e( 'Choose file', 'finpose' );
?></b>
											<input type="file" id="upfile" name="file" data-validate="required">
										</div>
										<div>
											<input type="submit" class="fin-button flr" value="<?php 
esc_attr_e( 'Save', 'finpose' );
?>">
										</div>
									</div>
								</div>
							</div>
							<div class="w50">
								<div class="flex container-form">
									<div class="w90">
										<?php 
esc_html_e( 'This operation will override any existing attachments for this purchase order.', 'finpose' );
?>
									</div>
								</div>
							</div>

						</div>
					</form>
				</div>
				</div>
			</div>


		</div>
	</template>

	<template id="payments">
		<div>
		<div class="fin-head">
				<div class="fin-head-left">
					<span><?php 
_e( 'Payments', 'finpose' );
?> - {{vendor.vname}}</span>
				</div>
			</div>
			<div class="fin-content">
				<div class="productfilter">
					<div class="pagefilters">
						<a class="fin-button fin-button-xs danger" @click="back"> < <?php 
_e( 'Back', 'finpose' );
?></a>
					</div>

					<div class="pageactions">
						<div class="fin-timeframe">
							<select @change="setPage" v-model="pager.page">
								<option v-for="n in pager.pages" :value="n"><?php 
_e( 'Page', 'finpose' );
?> {{n}}</option>
							</select>

							<select @change="setPerPage" v-model="pager.perpage">
								<option value="10">10</option>
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="100">100</option>
							</select>
						</div>
					</div>
				</div>

			<table class="fin-table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th><?php 
esc_html_e( 'Name', 'finpose' );
?></th>
						<th v-if="type=='all'"><?php 
esc_html_e( 'Type', 'finpose' );
?></th>
						<th><?php 
esc_html_e( 'Category', 'finpose' );
?></th>
						<th><?php 
esc_html_e( 'Journal Code', 'finpose' );
?></th>
						<th><?php 
esc_html_e( 'Paid With', 'finpose' );
?></th>
						<th><?php 
esc_html_e( 'Date Paid', 'finpose' );
?></th>
						<th class="tar"><?php 
esc_html_e( 'Amount', 'finpose' );
?> ({{currencySymbol}})</th>
						<th class="tar"><?php 
esc_html_e( 'Tax Receivable', 'finpose' );
?> ({{currencySymbol}})</th>
					</tr>
				</thead>
				<tbody id="spending-rows">
						<tr v-for="(spd, index) in payments">
							<td><span v-if="spd.attfile"><a :href="spd.attfile" target="_blank"><img :src="finurl + 'assets/img/attachment.svg'"/></a></span><span v-if="spd.notes.length>0"><span class="tooltip"><img :src="finurl + 'assets/img/note-text.svg'"/><span class="tooltiptext">{{spd.notes}}</span></span></span>{{spd.name}}</td>
							<td v-if="type=='all'">{{capitalizeFirstLetter(spd.type)}}</td>
							<td>{{categoryName(spd)}}</td>
							<td>{{categoryCode(spd)}}</td>
							<td>{{spd.pm ? spd.pm.name : ''}}</td>
							<td>{{spd.datepaid}}</td>
							<td class="tar">{{spd.amount}}</td>
							<td class="tar">{{spd.tr}}</td>
						</tr>
				</tbody>
				<tfoot>
					<tr>
						<th>Totals</th>
						<th v-if="type=='all'"></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th class="tar">{{totals.amount}}</th>
						<th class="tar">{{totals.tr}}</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</template>
