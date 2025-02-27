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
?>admin.php?page=fin_accounts" class="nav-tab nav-tab-active"><?php 
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
_e( 'Accounts', 'finpose' );
?></span>
		</div>
		<div class="fin-head-right">
			<div class="fin-timeframe">
				<form id="form-datefilter" @submit.prevent="filterByDate">
					<input type="text" name="datestart" data-validate="date" class="datepicker datefilter" v-model="filters.datestart">
					<input type="text" name="dateend" data-validate="date" class="datepicker datefilter" v-model="filters.dateend">
					<button type="submit" class="button-go"><?php 
_e( 'Go', 'finpose' );
?></button>
				</form>
				<a class="button-go button-fin" @click="transferModal">+ <?php 
_e( 'Transfer Between Accounts', 'finpose' );
?></a>
			</div>
		</div>
	</div>
	<div class="fin-content">
		<div class="flex-row">
			<div class="accounts-left">
				<div class="accounts-items">
					<div :class="'accounts-item' + (filters.account==acc.slug?' active':'')" v-for="(acc, index) in accounts">
						<div class="acc-icon">
							<img :src="finurl + 'assets/img/acc-' + acc.type + '.svg'" class="gwtype">
						</div>
						<div class="acc-info">
							<div class="h4 name">
								<a @click="setAccount(acc.slug, acc.name)">
									{{acc.name}}
								</a>
								<a @click="editAccountModal(acc)">
									<img :src="finurl + 'assets/img/pencil.svg'" class="accounts-rename-icon">
								</a>
							</div>
							<div :class="'h4 balance ' + (acc.balance>0?'plus':'minus')">{{currencySymbol}} {{acc.balance}}</div>
						</div>
					</div>
					<div class="accounts-item addnew">
						<div class="acc-icon">
							<img :src="finurl + 'assets/img/acc-add.svg'">
						</div>
						<div class="acc-info">
							<a @click="addNewModal"><?php 
_e( 'Add new account', 'finpose' );
?></a>
						</div>
					</div>
				</div>
			</div>
			<div class="accounts-right">
				<div class="productfilter">
					<div class="pageactions">
						<h3>{{accname}} <?php 
_e( 'Transactions', 'finpose' );
?></h3>
					</div>
					<div class="pagefilters">
						<a @click="exportCSV" class="fin-button fin-button-xs"><?php 
_e( 'Export', 'finpose' );
?></a>
					</div>
				</div>
				<div class="accounts-table-container">
					<table class="fin-table" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th><?php 
_e( 'Date', 'finpose' );
?></th>
								<th><?php 
_e( 'Type', 'finpose' );
?></th>
								<th><?php 
_e( 'Account', 'finpose' );
?></th>
								<th class="tar"><?php 
_e( 'Amount', 'finpose' );
?> ({{currencySymbol}})</th>
								<th><?php 
_e( 'Notes', 'finpose' );
?></th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(txn, index) in txns">
								<td>{{txn.date}}</td>
								<td>{{txn.type}}</td>
								<td>{{txn.account}}</td>
								<td :class="'tar ' + txn.cls">{{txn.cls=='minus'?'-':''}} {{txn.amount}}</td>
								<td>{{txn.notes}}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<a href="https://finpose.com/docs/accounts" target="_blank" style="font-size:13px;">
			<?php 
_e( 'Accounts Documentation', 'finpose' );
?>
			<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/external.svg" class="icon-xs"/>
		</a>
	</div>


	<div id="transferModal" class="hidden">
		<div id="fin-transfer" class="fin-modal">
			<div class="fin-modal-content">
			<h2 style="margin:16px 0px;"><?php 
esc_html_e( 'Transfer Between Accounts', 'finpose' );
?></h2>
			<form id="form-transfer" @submit.prevent="transfer">
				<input type="hidden" name="process" value="transfer">
				<input type="hidden" name="handler" value="accounts">
				<div class="flex">
					<div class="w50">
						<div class="flex container-form">
							<div class="w70">
								<b><?php 
esc_html_e( 'Account From', 'finpose' );
?></b>
							</div>
							<div class="w70 pb1">
								<select name="tfrom">
									<option :value="acc.slug" v-for="(acc, i) in accounts">{{acc.name}}</option>
								</select>
							</div>
							<div class="w70">
								<b><?php 
esc_html_e( 'Account To', 'finpose' );
?></b>
							</div>
							<div class="w70 pb1">
								<select name="tto">
									<option :value="acc.slug" v-for="(acc, i) in accounts">{{acc.name}}</option>
								</select>
							</div>
							<div class="w70">
								<b><?php 
esc_html_e( 'Amount', 'finpose' );
?></b>
								<div class="placeholder">2154.68</div>
							</div>
							<div class="w70 pb1">
								<input type="text" name="amount"  data-validate="money" @input="checkAllowed" @focus="flattenXfer" @blur="formatXfer">
							</div>
						</div>
					</div>
					<div class="w50">
						<div class="flex container-form">
							<div class="w70">
								<b><?php 
esc_html_e( 'Date Transfer', 'finpose' );
?></b>
								<div class="placeholder">2019-06-25</div>
							</div>
							<div class="w70 pb1">
								<input type="text" name="datetransfer" data-validate="date" class="datepicker">
							</div>
							<div class="w70">
								<b><?php 
esc_html_e( 'Notes', 'finpose' );
?></b>
							</div>
							<div class="w70 pb1">
								<input type="text" name="notes">
							</div>
							<div class="w70 pb1">
								<hr>
							</div>
							<div class="w70 pb1">
								<input type="submit" class="fin-button" value="<?php 
esc_attr_e( 'Save', 'finpose' );
?>">
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		</div>
	</div>


	<div id="addNewModal" class="hidden">
		<div id="fin-add-custom" class="fin-modal">
			<div class="fin-modal-content">
			<h2 style="margin:16px 0px;"><?php 
esc_html_e( 'Add New Account', 'finpose' );
?></h2>
			<form id="form-addaccount" @submit.prevent="addnewAccount">
				<input type="hidden" name="process" value="addAccount">
				<input type="hidden" name="handler" value="accounts">
				<div class="flex">
					<div class="w50">
						<div class="flex container-form">
							<div class="w70">
								<b><?php 
esc_html_e( 'Source', 'finpose' );
?></b>
							</div>
							<div class="w70">
								<select name="source" @change="sourceChange">
									<option value="new"><?php 
_e( 'New', 'finpose' );
?></option>
									<option value="restore"><?php 
_e( 'Restore', 'finpose' );
?></option>
								</select>
							</div>
						</div>
					</div>
					<div class="w50">
						<div class="flex container-form">
							<div v-if="addSource=='new'" class="w100">
								<div class="w70">
									<b><?php 
esc_html_e( 'Account Type', 'finpose' );
?></b>
								</div>
								<div class="w70 pb1">
									<select name="type" @change="selectAccountType">
										<option value="gateway"><?php 
_e( 'Payment gateway', 'finpose' );
?></option>
										<option value="card"><?php 
_e( 'Credit card', 'finpose' );
?></option>
										<option value="bank"><?php 
_e( 'Bank account', 'finpose' );
?></option>
										<option value="other"><?php 
_e( 'Other', 'finpose' );
?></option>
									</select>
								</div>
								<div v-if="show.feeFields">
									<div class="w70">
										<b><?php 
esc_html_e( 'Gateway', 'finpose' );
?></b>
									</div>
									<div class="w70 pb1">
										<select name="gwslug" class="w100">
											<option v-for="(name, slug) in gwlist" :value="slug">{{name}}</option>
										</select>
									</div>
									<div class="w70">
										<b><?php 
esc_html_e( 'Transaction Fee (%)', 'finpose' );
?></b>
									</div>
									<div class="w70 pb1">
										<select name="fee">
											<option v-for="n in 100" :value="((0.1) * (n-1)).toFixed(1)">{{((0.1) * (n-1)).toFixed(1)}} %</option>
										</select>
									</div>
								</div>
								<div v-else>
									<div class="w70">
										<b><?php 
esc_html_e( 'Account Name', 'finpose' );
?></b>
									</div>
									<div class="w70 pb1">
										<input type="text" name="name" data-validate="name">
									</div>
								</div>
							</div>
							<div v-if="addSource=='restore'" class="w100">
								<div class="w70">
									<b><?php 
esc_html_e( 'Choose Account', 'finpose' );
?></b>
								</div>
								<div class="w70">
									<select name="restoreslug" class="w100">
										<option v-for="(acc, slug) in removedAccounts" :value="slug">{{acc.name}}</option>
									</select>
								</div>
							</div>
							<div class="w70 pb1">
								<input type="submit" class="fin-button" value="<?php 
esc_attr_e( 'Save', 'finpose' );
?>">
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		</div>
	</div>


	<div id="editAccountModal" class="hidden">
		<div id="fin-transfer" class="fin-modal">
			<div class="fin-modal-content">
			<h2 style="margin:16px 0px;"><?php 
esc_html_e( 'Edit Account', 'finpose' );
?></h2>
			<form id="form-editaccount" @submit.prevent="editAccount">
				<input type="hidden" name="process" value="editAccount">
				<input type="hidden" name="handler" value="accounts">
				<input type="hidden" id="acckey" name="key" v-model="row.slug">
				<div class="flex">
					<div class="w50">
						<div class="flex container-form">
							<div class="w70">
								<b><?php 
esc_html_e( 'Account Type', 'finpose' );
?></b>
							</div>
							<div class="w70 pb1">
								<select name="type" v-model="row.type" @change="selectAccountType">
									<option value="gateway" :disabled="row.type!=='gateway'"><?php 
_e( 'Payment gateway', 'finpose' );
?></option>
									<option value="card" :disabled="row.type==='gateway'"><?php 
_e( 'Credit card', 'finpose' );
?></option>
									<option value="bank" :disabled="row.type==='gateway'"><?php 
_e( 'Bank account', 'finpose' );
?></option>
									<option value="other" :disabled="row.type==='gateway'"><?php 
_e( 'Other', 'finpose' );
?></option>
								</select>
							</div>
							<div class="w70" v-if="row.type==='gateway'">
								<b><?php 
esc_html_e( 'Transaction Fee (%)', 'finpose' );
?></b>
							</div>
							<div class="w70 pb1" v-if="row.type==='gateway'">
								<select name="fee" v-model="row.fee">
									<option v-for="n in 100" :value="((0.1) * (n-1)).toFixed(1)">{{((0.1) * (n-1)).toFixed(1)}} %</option>
								</select>
							</div>
							<div class="w70">
								<b><?php 
esc_html_e( 'Account Name', 'finpose' );
?></b>
							</div>
							<div class="w70 pb1">
								<input id="accnametext" type="text" name="name" data-validate="name" v-model="row.name">
							</div>
							<div class="w70 pb1">
								<input type="submit" class="fin-button" value="<?php 
esc_attr_e( 'Save', 'finpose' );
?>">
							</div>
						</div>
					</div>
					<div class="w50">
						<div class="w100 pb1 tac">
							<a class="fin-button danger" @click="deleteAccount"><?php 
esc_attr_e( 'Delete Account', 'finpose' );
?></a>
						</div>
					</div>
				</div>
			</form>
		</div>
		</div>
	</div>
</div>
