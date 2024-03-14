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

	<div class="fin-head">
		<div class="fin-head-left">
			<span><?php 
esc_html_e( 'Inventory', 'finpose' );
?></span>
		</div>
		<div class="fin-head-right">
			
		</div>
	</div>
	<div class="fin-content">
		<div class="inventory-top">
			<div class="sales-figures">
				<div class="inventory-figure">
					<div class="sf-number">
						{{summary.lastupdate}}
					</div>
					<div class="sf-title">
						<?php 
_e( 'Last Update', 'finpose' );
?>
					</div>
				</div>
				<div class="inventory-figure">
					<div class="sf-number">
						{{currencySymbol}}
						{{summary.totalvalue}}
					</div>
					<div class="sf-title">
						<?php 
_e( 'Total Stock Value', 'finpose' );
?>
					</div>
				</div>
				<div class="inventory-figure">
					<div class="sf-number">
						{{summary.units}}
					</div>
					<div class="sf-title">
						<?php 
_e( 'Number of Items', 'finpose' );
?>
					</div>
				</div>
			</div>
		</div>

		<div class="productfilter">
			
			<div class="pagefilters">
				<select name="category" v-model="filters.category" @change="filterInventory">
					<option value="0"><?php 
esc_html_e( 'All Categories', 'finpose' );
?></option>
					<?php 
foreach ( $handler->view['cats'] as $cat ) {
    ?>
						<option value="<?php 
    echo  $cat->slug ;
    ?>"><?php 
    echo  $cat->name ;
    ?></option>
					<?php 
}
?>
				</select>
				<select name="type" v-model="filters.type" @change="filterInventory">
					<option value="0"><?php 
esc_html_e( 'All Items', 'finpose' );
?></option>
					<option value="simple"><?php 
esc_html_e( 'Simple', 'finpose' );
?></option>
					<option value="grouped"><?php 
esc_html_e( 'Grouped', 'finpose' );
?></option>
					<option value="variable"><?php 
esc_html_e( 'Variable', 'finpose' );
?></option>
				</select>
				<a href="javascript:;" @click="requiresSync" id="synclink">
					<?php 
esc_html_e( 'Need Sync', 'finpose' );
?>
				</a>
				<img id="cancelsync" src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/cross-white.svg" @click="cancelSync">
			</div>

			<div class="pageactions">
				<div class="fin-timeframe">
					<div class="invsearchwrap">
						<input type="text" id="invsearch" @input="searchItem" v-model="filters.term" placeholder="<?php 
esc_attr_e( 'Type to search', 'finpose' );
?>">
						<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/cross.svg" id="invsearchcancel" @click="cancelSearch">
					</div>
					
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
_e( 'Product Type', 'finpose' );
?></th>
					<th><?php 
_e( 'Product Name', 'finpose' );
?></th>
					<th><?php 
_e( 'Stock Level', 'finpose' );
?></th>
					<th><?php 
_e( 'Est. Runout Date', 'finpose' );
?></th>
					<th><?php 
_e( 'Stock Value', 'finpose' );
?> ({{currencySymbol}})</th>
					<th><?php 
_e( 'Avg. Unit Cost', 'finpose' );
?> ({{currencySymbol}})</th>
					<th><?php 
_e( 'Price', 'finpose' );
?> ({{currencySymbol}})</th>
					<th class="tar"><?php 
_e( 'Profit Margin', 'finpose' );
?> (%)</th>
					<th class="tar"><?php 
esc_html_e( 'Actions', 'finpose' );
?></th>
				</tr>
			</thead>
			<tbody id="spending-rows">
				<tr v-for="(inv, index) in inventory">
					<td>{{inv.type}}</td>
					<td><a :href="siteurl + '/wp-admin/post.php?post='+inv.pid+'&action=edit'" target="_blank">{{inv.name}}</a></td>
					<td>
						<div class="progress-bar" v-if="inv.finmeta.units">
							<div :class="'progress-bar-inner' + (inv.finmeta.units>100?' bgreen':' bblue') + (inv.finmeta.units<20?' bred':'')" :style="'flex: ' + (inv.finmeta.units>100?1:(inv.finmeta.units/100)) + ';'">
								{{inv.finmeta.units}}
							</div>
						</div>
						<span v-if="!inv.finmeta.units"><?php 
_e( 'Out of stock', 'finpose' );
?></span>
					</td>
					<td>{{inv.finmeta.rodate}}</td>
					<td>{{formatMoney(inv.finmeta.totalvalue)}}</td>
					<td>{{formatMoney(inv.finmeta.avgcost)}}</td>
					<td>{{inv.price}}</td>
					<td :class="'tar' + (inv.finmeta.margin>0?' plus':' minus')">{{round(inv.finmeta.margin)}}</td>
					<td class="tar">
						<a @click="importStockModal(inv)" v-if="inv.finmeta.import"><?php 
_e( 'Sync', 'finpose' );
?></a>
						<a @click="addStockModal(inv.pid, inv.name)" v-if="!inv.finmeta.import"><img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/plus.svg" class="icon-xs"></a>
						<a @click="editorModal(inv)" v-if="inv.finmeta.units>0"><img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/pencil.svg" class="icon-xs"></a>
					</td>
				</tr>
			</tbody>
		</table>

		<a href="https://finpose.com/docs/inventory" target="_blank" style="font-size:13px;">
			<?php 
_e( 'Inventory Documentation', 'finpose' );
?>
			<img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/external.svg" class="icon-xs"/>
		</a>
	</div>

	<div id="addStock" class="hidden">
		<div id="fin-addstock" class="fin-modal">
			<div class="fin-modal-content">
				<h2 style="margin:16px 0px;"><?php 
esc_html_e( 'Add Inventory Items', 'finpose' );
?></h2>
				<form id="form-addstock" @submit.prevent="addStock">
					<input type="hidden" name="process" value="addStock">
					<input type="hidden" name="handler" value="inventory">
					<input type="hidden" name="pid" class="inputpid" value="">
					<input type="hidden" name="import" value="0">
					<div class="flex container-form">
						<div class="w40">
							<div class="pb1">
								<b><?php 
esc_html_e( 'Product Name', 'finpose' );
?></b><br>
								<label class="labelName"></label>
							</div>
							<div>
								<label>* <?php 
_e( 'This operation will set WooCommerce stock level to given number', 'finpose' );
?> <span class="newstocklevel"></span> <?php 
_e( 'for the product', 'finpose' );
?>. (<label class="labelName"></label>)</label>
							</div>
							<div>
								<b><?php 
esc_html_e( 'Vendor', 'finpose' );
?></b>
								<select name="vid">
									<option v-for="(v, i) in vendors" :value="v.vid">{{v.vname}}</option>
								</select>
							</div>
						</div>
						<div class="w50 pl1">
							<div>
								<div><b><?php 
esc_html_e( 'Number of Units', 'finpose' );
?></b><span class="placeholder flr"><?php 
_e( 'Required', 'finpose' );
?></span></div>
								<input type="number" class="form-control" name="units" maxlength="4" max="500" min="1">
							</div>
							<div>
								<div><b><?php 
esc_html_e( 'Cost of Single Unit', 'finpose' );
?></b><span class="placeholder flr">2154.68</span></div>
								<input type="text" name="unitcost" data-validate="money" @input="checkAllowed" @focus="flattenMoneyAdd" @blur="formatMoneyAdd">
							</div>
							<div>
								<div><b><?php 
esc_html_e( 'Total Tax Receivable', 'finpose' );
?></b><span class="placeholder flr">27.89</span></div>
								<input type="text" name="tr" data-validate="money" @input="checkAllowed" @focus="flattenMoneyAdd" @blur="formatMoneyAdd" value="0">
							</div>
							<div>
								<b><?php 
esc_html_e( 'Paid With', 'finpose' );
?></b>
								<select name="paidwith">
									<option v-for="(acc, slug) in accounts" :value="slug">{{acc.name}}</option>
								</select>
							</div>
							<div>
								<input type="checkbox" id="savecost" name="savecost" value="1">
								<label for="savecost"><?php 
esc_html_e( 'Save cost in spendings?', 'finpose' );
?></label>
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


	<div id="importStock" class="hidden">
		<div id="fin-importstock" class="fin-modal">
			<div class="fin-modal-content">
				<h2 style="margin:16px 0px;"><?php 
esc_html_e( 'Import Inventory Items', 'finpose' );
?></h2>
				<form id="form-importstock" @submit.prevent="importStock">
					<input type="hidden" name="process" value="addStock">
					<input type="hidden" name="handler" value="inventory">
					<input type="hidden" name="pid" v-model="row.pid">
					<input type="hidden" name="import" value="1">
					<div class="flex container-form">
						<div class="w40">
							<div class="pb1">
								<b><?php 
esc_html_e( 'Product Name', 'finpose' );
?></b><br>
								<label class="labelName">{{row.name}}</label>
							</div>
							<div>
								<p>* <?php 
_e( 'WooCommerce stock level is higher than Finpose stock level', 'finpose' );
?>.</p>
								<ul>
									<li><?php 
_e( 'WooCommerce stock level', 'finpose' );
?> : <span>{{row.wc_quantity}}</span></li>
									<li><?php 
_e( 'Finpose stock level', 'finpose' );
?> : <span>{{row.finmeta.units}}</span></li>
									<li>{{row.wc_quantity - row.finmeta.units}} <?php 
_e( 'units will be added in Finpose', 'finpose' );
?></li>
								</ul>
								<p><?php 
_e( 'Alternatively you can decrease WooCommerce stock level by visiting product page', 'finpose' );
?></p>
							</div>
							<div>
								<b><?php 
esc_html_e( 'Vendor', 'finpose' );
?></b>
								<select name="vid">
									<option v-for="(v, i) in vendors" :value="v.vid">{{v.vname}}</option>
								</select>
							</div>
						</div>
						<div class="w50 pl1">
							<div>
								<div><b><?php 
esc_html_e( 'Number of Units', 'finpose' );
?></b><span class="placeholder flr"><?php 
_e( 'Required', 'finpose' );
?></span></div>
								<input type="number" class="form-control" name="units" maxlength="4" max="500" min="1" :value="(row.wc_quantity - row.finmeta.units)" readonly="true">
							</div>
							<div>
								<div><b><?php 
esc_html_e( 'Cost of Single Unit', 'finpose' );
?></b><span class="placeholder flr">2154.68</span></div>
								<input type="text" name="unitcost" data-validate="money" @input="checkAllowed" @focus="flattenMoneyAdd" @blur="formatMoneyAdd">
							</div>
							<div>
								<div><b><?php 
esc_html_e( 'Total Tax Receivable', 'finpose' );
?></b><span class="placeholder flr">27.89</span></div>
								<input type="text" name="tr" data-validate="money" @input="checkAllowed" @focus="flattenMoneyAdd" @blur="formatMoneyAdd" value="0">
							</div>
							<div>
								<b><?php 
esc_html_e( 'Paid With', 'finpose' );
?></b>
								<select name="paidwith">
									<option v-for="(acc, slug) in accounts" :value="slug">{{acc.name}}</option>
								</select>
							</div>
							<div>
								<input type="checkbox" id="savecost" name="savecost" value="1">
								<label for="savecost"><?php 
esc_html_e( 'Save cost in spendings?', 'finpose' );
?></label>
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


	<div id="itemEditor" class="hidden">
		<div id="fin-itemeditor" class="fin-modal">
			<div class="fin-modal-content">
				<div class="editorhead">
					<div class="iiname">
						<h2>{{row.name}}</h2>
					</div>
					<div class="iipager">
						<select @change="setEditorPage" v-model="editorPager.page">
							<option v-for="n in editorPager.pages" :value="n"><?php 
_e( 'Page', 'finpose' );
?> {{n}}</option>
						</select>
					</div>
				</div>
				<table class="fin-table">
					<thead>
						<tr>
							<th><?php 
_e( 'Vendor', 'finpose' );
?></th>
							<th><?php 
_e( 'SKU', 'finpose' );
?></th>
							<th><?php 
_e( 'Date Added', 'finpose' );
?></th>
							<th><?php 
_e( 'Unit Cost', 'finpose' );
?> ({{currencySymbol}})</th>
							<th><?php 
_e( 'Is Sold?', 'finpose' );
?></th>
							<th><?php 
_e( 'Date Sold', 'finpose' );
?></th>
							<th><?php 
_e( 'Days in Stock', 'finpose' );
?></th>
							<th><?php 
_e( 'Sale Price', 'finpose' );
?> ({{currencySymbol}})</th>
							<th><?php 
_e( 'Actions', 'finpose' );
?></th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(item, index) in editor.items" :class="item.is_sold=='1'?'sold':''">
							<td>{{vendorsObj[item.vid]}}</td>
							<td>{{editor.product.sku}}</td>
							<td>{{item.date_added}}</td>
							<td>{{item.cost}}</td>
							<td>{{item.is_sold=='1'?'<?php 
_e( 'Yes', 'finpose' );
?>':'<?php 
_e( 'No', 'finpose' );
?>'}}</td>
							<td>{{item.is_sold=='1'?item.date_sold:''}}</td>
							<td>{{item.is_sold=='1'?item.days_in_stock + '<?php 
_e( 'days', 'finpose' );
?>':''}}</td>
							<td>{{item.is_sold=='1'?item.soldprice:''}}</td>
							<td>
								<a @click="removeInventoryUnit(item.iid, item.pid)">Delete</a>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="sales-figures">
					<div class="inventory-figure">
						<div class="sf-number">
							{{editor.product.summary.sold}}
						</div>
						<div class="sf-title">
							<?php 
_e( 'Sold Items', 'finpose' );
?>
						</div>
					</div>
					<div class="inventory-figure">
						<div class="sf-number">
							{{editor.product.summary.unsold}}
						</div>
						<div class="sf-title">
							<?php 
_e( 'Unsold Items', 'finpose' );
?>
						</div>
					</div>
					<div class="inventory-figure">
						<div class="sf-number">
							{{editorPager.total}}
						</div>
						<div class="sf-title">
							<?php 
_e( 'Total Number of Items', 'finpose' );
?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
