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
?>admin.php?page=fin_spendings" class="nav-tab nav-tab-active"><?php 
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
	<div class="fin-head">
		<div class="fin-head-left">
			<span><?php 
esc_html_e( 'Spendings', 'finpose' );
?></span>
		</div>
		<div class="fin-head-right">
			
		</div>
	</div>
	<div class="fin-content">

		<div class="fin-tabs-container">
			<div class="fin-tabs-left fx6">
				<div class="fin-tabs spending-tabs">
				<div :class="'fin-tab' + (type=='all'?' active':'')">
						<a @click="switchTab('all')"><?php 
esc_html_e( 'All', 'finpose' );
?></a>
					</div>
					<div :class="'fin-tab' + (type=='cost'?' active':'')">
						<a @click="switchTab('cost')"><?php 
esc_html_e( 'Costs', 'finpose' );
?></a>
					</div>
					<div :class="'fin-tab' + (type=='expense'?' active':'')">
						<a @click="switchTab('expense')"><?php 
esc_html_e( 'Expenses', 'finpose' );
?></a>
					</div>
					<div :class="'fin-tab' + (type=='acquisition'?' active':'')">
						<a @click="switchTab('acquisition')"><?php 
esc_html_e( 'Acquisition', 'finpose' );
?></a>
					</div>
				</div>
			</div>
			<div class="fin-tabs-right fx4 tar">
				<div class="fin-timeframe">
					<form id="form-datefilter" @submit.prevent="filterByDate">
						<input type="text" name="datestart" data-validate="date" class="datepicker datefilter" v-model="filters.datestart">
						<input type="text" name="dateend" data-validate="date" class="datepicker datefilter" v-model="filters.dateend">
						<button type="submit" class="button-go"><?php 
_e( 'Go', 'finpose' );
?></button>
					</form>
				</div>
			</div>
		</div>
		<div class="spendings-container">
			<div class="spendings-left">
				<h4><?php 
esc_html_e( 'Filter by Category', 'finpose' );
?></h4>
				<ul id="cats-left">
					<li v-for="(item, index) in leftcats" :class="(index==cat?'catactive':'')">
						<a @click="setCategory(index)">{{item.name}}</a> 
						<span v-if="index==cat"><a @click="editCategoryModal(index, item)"><img :src="finurl + 'assets/img/pencil.svg'"/></a></span>
					</li>
				</ul>
				<ul>
					<li><a @click="addCategoryModal">+ <?php 
esc_html_e( 'Add New Category', 'finpose' );
?></a></li>
				</ul>
			</div>
			<div class="spendings-right">
				<div class="productfilter">
					<div class="pageactions">
						<a class="fin-button fin-button-xs" @click="addSpendingModal">+ <?php 
esc_html_e( 'Add New', 'finpose' );
?></a>
						<a @click="exportCSV" class="fin-button fin-button-xs"><?php 
esc_attr_e( 'Export', 'finpose' );
?></a>
					</div>
					<div class="pagefilters" v-if="type=='all'">
						<select name="paidwith" v-model="filters.paidwith" @change="getSpendings">
							<option value="0"><?php 
esc_html_e( 'All Accounts', 'finpose' );
?></option>
							<?php 
foreach ( $handler->view['accounts'] as $acslug => $acc ) {
    ?>
								<option value="<?php 
    echo  $acslug ;
    ?>"><?php 
    echo  $acc['name'] ;
    ?></option>
							<?php 
}
?>
						</select>
						<select name="items" v-model="filters.pid" @change="getSpendings">
							<option value="0"><?php 
esc_html_e( 'All Items', 'finpose' );
?></option>
							<?php 
foreach ( $handler->view['products'] as $product ) {
    ?>
								<option value="<?php 
    echo  $product->get_id() ;
    ?>"><?php 
    echo  $product->get_name() ;
    ?></option>
							<?php 
}
?>
						</select>
					</div>
					
				</div>
				<table class="fin-table" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th><?php 
esc_html_e( 'Vendor', 'finpose' );
?></th>
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
							<th class="tar"><?php 
esc_html_e( 'Actions', 'finpose' );
?></th>
						</tr>
					</thead>
					<tbody id="spending-rows">
							<tr v-for="(spd, index) in spendings">
								<td>{{spd.vendorname}}</td>
								<td><span v-if="spd.attfile"><a :href="spd.attfile" target="_blank"><img :src="finurl + 'assets/img/attachment.svg'"/></a></span><span v-if="spd.notes.length>0"><span class="tooltip"><img :src="finurl + 'assets/img/note-text.svg'"/><span class="tooltiptext">{{spd.notes}}</span></span></span>{{spd.name}}</td>
								<td v-if="type=='all'">{{capitalizeFirstLetter(spd.type)}}</td>
								<td>{{categoryName(spd)}}</td>
								<td>{{categoryCode(spd)}}</td>
								<td>{{spd.pm ? spd.pm.name : ''}}</td>
								<td>{{spd.datepaid}}</td>
								<td class="tar">{{spd.amountFormatted}}</td>
								<td class="tar">{{spd.trFormatted}}</td>
								<td class="tar">
									<a href="javascript:void(0);" @click="displayUploader(index, spd)"><img :src="finurl + 'assets/img/upload.svg'"/></a>
									<a href="javascript:void(0);" @click="editSpending(index, spd)"><img :src="finurl + 'assets/img/pencil.svg'"/></a>
									<a href="javascript:void(0);" @click="deleteSpending(index, spd.coid)"><img :src="finurl + 'assets/img/cross.svg'"/></a>
								</td>
							</tr>
					</tbody>
					<tfoot>
						<tr>
							<th>Totals</th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th class="tar">{{totals.amount}}</th>
							<th class="tar">{{totals.tr}}</th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<a href="https://finpose.com/docs/spendings" target="_blank" style="font-size:13px;">
			<?php 
_e( 'Spendings Documentation', 'finpose' );
?>
			<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/external.svg" class="icon-xs"/>
		</a>
	</div>

	


	<div id="addcategory" class="hidden">
		<div class="fin-modal">
			<div class="fin-modal-content">
				<h2 style="margin:16px 0px;"><?php 
esc_html_e( 'Add New Category', 'finpose' );
?></h2>
				<form id="form-addcategory" @submit.prevent="addCategory">
					<input type="hidden" name="process" value="addSpendingCategory">
					<input type="hidden" name="handler" value="spendings">
					<div class="flex">
						<div class="w50">
							<div class="pb1">
								<b><?php 
esc_html_e( 'Type', 'finpose' );
?></b>
								<select name="type">
									<option value="cost"><?php 
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
esc_html_e( 'Name', 'finpose' );
?></b>
								<input type="text" name="name" data-validate="required" maxlength="128">
							</div>
							<div class="pb1">
								<div><b><?php 
esc_html_e( 'Journal Code', 'finpose' );
?></b><span class="placeholder flr"><?php 
_e( 'Optional', 'finpose' );
?></span></div>
								<input type="text" name="jcode" maxlength="32">
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

	<div id="editCategoryModal" class="hidden">
		<div class="fin-modal">
			<div class="fin-modal-content">
				<h2 style="margin:16px 0px;"><?php 
esc_html_e( 'Edit Category', 'finpose' );
?></h2>
				<form id="form-editcategory" @submit.prevent="editCategory">
					<input type="hidden" name="process" value="editCategory">
					<input type="hidden" name="handler" value="spendings">
					<input type="hidden" id="editkey" name="key" value="" v-model="catrow.slug">
					<input type="hidden" id="edittype" name="type" value="" v-model="catrow.type">
					<div class="flex">
						<div class="w50">
							<div class="pb1">
								<b><?php 
esc_html_e( 'Name', 'finpose' );
?></b>
								<input id="editname" type="text" name="name" data-validate="required" maxlength="128" v-model="catrow.name">
							</div>
							<div class="pb1">
								<div><b><?php 
esc_html_e( 'Journal Code', 'finpose' );
?></b><span class="placeholder flr">Optional</span></div>
								<input id="editjc" type="text" name="jcode" maxlength="32" v-model="catrow.jcode">
							</div>
							<hr>
							<div>
								<input type="submit" class="fin-button flr" value="<?php 
esc_attr_e( 'Save', 'finpose' );
?>">
							</div>
						</div>
						<div class="w50">
							<div class="w100 pb1 tac">
								<a href="#" @click="removeCategory(catrow.type, catrow.slug)" class="fin-button danger"><?php 
esc_attr_e( 'Delete Category', 'finpose' );
?></a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>


	<div id="addnew" class="hidden">
		<div class="fin-modal">
			<div class="fin-modal-content">
			<h2 style="margin:16px 0px;"><?php 
esc_html_e( 'Add New Spending', 'finpose' );
?></h2>
			<form id="form-addnewspending" @submit.prevent="addNewSpending">
				
				<input type="hidden" name="process" value="addSpending">
				<input type="hidden" name="handler" value="spendings">
				
				<div class="flex">
					<div class="w50">
						<div class="flex container-form">
							<div class="w90">
								<div>
									<b><?php 
esc_html_e( 'Name', 'finpose' );
?></b>
									<input type="text" name="name" data-validate="required" maxlength="128">
								</div>
								<div>
									<b><?php 
esc_html_e( 'Vendor', 'finpose' );
?></b>
									<select name="vid">
										<option value="0"></option>
										<option v-for="(v, i) in vendors" :value="v.vid">{{v.vname}}</option>
									</select>
								</div>
								<div>
									<b><?php 
esc_html_e( 'Type', 'finpose' );
?></b>
									<select name="type" @change="setCatlist">
										<option value="cost" selected><?php 
esc_html_e( 'Cost', 'finpose' );
?></option>
										<option value="expense"><?php 
esc_html_e( 'Expense', 'finpose' );
?></option>
										<option value="acquisition"><?php 
esc_html_e( 'Acquisition', 'finpose' );
?></option>
									</select>
								</div>
								<div>
									<b><?php 
esc_html_e( 'Category', 'finpose' );
?></b>
									<select name="cat" id="cat-select-add">
										<option v-for="(item, index) in catlist" :value="index">{{item.name}}</option>
									</select>
								</div>
								
								<div>
									<b><?php 
esc_html_e( 'Notes', 'finpose' );
?></b>
									<input type="text" name="notes">
								</div>
							</div>
						</div>
					</div>
					<div class="w50">
						<div class="flex container-form">
							<div class="w90">
								<div>
									<div><b><?php 
esc_html_e( 'Amount', 'finpose' );
?></b><span class="placeholder flr">2154.68</span></div>
									<input type="text" name="amount" data-validate="money" @input="checkAllowed" @focus="flattenAdd" @blur="formatAdd">
								</div>
								<div>
									<div><b><?php 
esc_html_e( 'Tax Receivable', 'finpose' );
?></b><span class="placeholder flr">27.89</span></div>
									<input type="text" name="tr" data-validate="money" @input="checkAllowed" @focus="flattenAdd" @blur="formatAdd" value="0">
								</div>
								<div>
									<b><?php 
esc_html_e( 'Paid With', 'finpose' );
?></b>
									<select name="paidwith">
										<?php 
foreach ( $handler->view['accounts'] as $acslug => $acc ) {
    ?>
											<option value="<?php 
    echo  $acslug ;
    ?>"><?php 
    echo  $acc['name'] ;
    ?></option>
										<?php 
}
?>
									</select>
								</div>
								<div>
									<div><b><?php 
esc_html_e( 'Date Paid', 'finpose' );
?></b><span class="placeholder flr">2019-06-25</span></div>
									<input type="text" name="datepaid" data-validate="date" class="datepicker">
								</div>
								<div>
									<div><b><?php 
esc_html_e( 'Items', 'finpose' );
?></b></div>
									<select name="items">
										<option value="0"><?php 
esc_html_e( 'All Items', 'finpose' );
?></option>
										<?php 
foreach ( $handler->view['products'] as $product ) {
    ?>
											<option value="<?php 
    echo  $product->get_id() ;
    ?>"><?php 
    echo  $product->get_name() ;
    ?></option>
										<?php 
}
?>
									</select>
								</div>
								
								<hr>
								<div>
									<input type="submit" class="fin-button flr" value="<?php 
esc_attr_e( 'Save', 'finpose' );
?>">
								</div>
							</div>
						</div>
					</div>

				</div>
			</form>
		</div>
		</div>
	</div>


	<div id="editSpendingModal" class="hidden">
		<div class="fin-modal">
			<div class="fin-modal-content">
			<h2 style="margin:16px 0px;"><?php 
esc_html_e( 'Edit Spending', 'finpose' );
?></h2>
			<form id="form-editspending" @submit.prevent="updateSpending">
				<input type="hidden" name="process" value="editSpending">
				<input type="hidden" name="handler" value="spendings">
				<input type="hidden" id="editkey" name="key" value="" v-model="row.coid">
				<input type="hidden" name="attfile" v-model="row.attfile">
				<div class="flex">
					<div class="w50">
						<div class="flex container-form">
							<div class="w90">
								<div>
									<b><?php 
esc_html_e( 'Name', 'finpose' );
?></b>
									<input type="text" name="name" data-validate="required" maxlength="128" v-model="row.name">
								</div>
								<div>
									<b><?php 
esc_html_e( 'Vendor', 'finpose' );
?></b>
									<select name="vid" v-model="row.vid">
										<option value="0"></option>
										<option v-for="(v, i) in vendors" :value="v.vid">{{v.vname}}</option>
									</select>
								</div>
								<div>
									<b><?php 
esc_html_e( 'Type', 'finpose' );
?></b>
									<select name="type" @change="setCatlist" v-model="row.type">
										<option value="cost">Cost</option>
										<option value="expense">Expense</option>
										<option value="acquisition">Acquisition</option>
									</select>
								</div>
								<div>
									<b><?php 
esc_html_e( 'Category', 'finpose' );
?></b>
									<select name="cat" id="cat-select-edit" v-model="row.cat">
										<option v-for="(item, index) in catlist" :value="index">{{item.name}}</option>
									</select>
								</div>
								<div>
									<b><?php 
esc_html_e( 'Notes', 'finpose' );
?></b>
									<input type="text" name="notes" v-model="row.notes">
								</div>
							</div>
						</div>
					</div>
					<div class="w50">
						<div class="flex container-form">
							<div class="w90">
								<div>
									<div><b><?php 
esc_html_e( 'Amount', 'finpose' );
?></b><span class="placeholder flr">2154.68</span></div>
									<input type="text" name="amount" data-validate="money" @input="checkAllowed" @focus="flattenEdit('amountFormatted')" @blur="formatEdit('amountFormatted')" v-model="row.amountFormatted" />
								</div>
								<div>
									<div><b><?php 
esc_html_e( 'Tax Receivable', 'finpose' );
?></b><span class="placeholder flr">27.89</span></div>
									<input type="text" name="tr" data-validate="money" @input="checkAllowed" @focus="flattenEdit('trFormatted')" @blur="formatEdit('trFormatted')" v-model="row.trFormatted" />
								</div>
								<div>
									<b><?php 
esc_html_e( 'Paid With', 'finpose' );
?></b>
									<select name="paidwith" v-model="row.paidwith">
										<?php 
foreach ( $handler->view['accounts'] as $acslug => $acc ) {
    ?>
											<option value="<?php 
    echo  $acslug ;
    ?>"><?php 
    echo  $acc['name'] ;
    ?></option>
										<?php 
}
?>
									</select>
								</div>
								<div>
									<div><b><?php 
esc_html_e( 'Date Paid', 'finpose' );
?></b><span class="placeholder flr">2019-06-25</span></div>
									<input type="text" name="datepick" data-validate="date" class="datepicker" v-model="row.datepick">
								</div>
								<div>
									<div><b><?php 
esc_html_e( 'Items', 'finpose' );
?></b></div>
									<select name="items">
										<option value="0"><?php 
esc_html_e( 'All Items', 'finpose' );
?></option>
										<?php 
foreach ( $handler->view['products'] as $product ) {
    ?>
											<option value="<?php 
    echo  $product->get_id() ;
    ?>"><?php 
    echo  $product->get_name() ;
    ?></option>
										<?php 
}
?>
									</select>
								</div>
								
								<hr>
								<div>
									<input type="submit" class="fin-button flr" value="<?php 
esc_attr_e( 'Save', 'finpose' );
?>">
								</div>
							</div>
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
esc_html_e( 'Attach File to Spending', 'finpose' );
?></h2>
			<form id="form-attach" @submit.prevent="uploadAttachment">
				<?php 
wp_nonce_field( 'finpost', 'nonce' );
?>
				<input type="hidden" name="process" value="attachFile">
				<input type="hidden" name="handler" value="spendings">
				<input type="hidden" id="attkey" name="key" v-model="row.coid">
				<div class="flex">
					<div class="w50">
						<div class="flex container-form">
							<div class="w90">
								<div>
									<b><?php 
esc_html_e( 'Name', 'finpose' );
?></b>
									<span>{{row.name}}</span>
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
esc_html_e( 'This operation will override any existing attachments for this spending.', 'finpose' );
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


