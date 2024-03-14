<div id="finapp" class="fin-container dash">
  <div class="fin-tabs">
    <nav class="nav-tab-wrapper w100">
			<a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_dashboard" class="nav-tab nav-tab-active"><?php 
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
_e( 'Dashboard', 'finpose' );
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
			</div>
		</div>
	</div>
	<div class="fin-content">
    <div class="insights-wrapper">
      <h3><?php 
_e( 'Insights', 'finpose' );
?><span class="flr"><?php 
_e( 'Currency', 'finpose' );
?> : {{currencySymbol}}</span></h3>
      <div class="insights">
        <div class="inscol">
          <div class="institle"><?php 
_e( 'Orders', 'finpose' );
?></div>
          <div class="insnum">{{info.numorders}}</div>
          <div class="insnote"></div>
        </div>
        <div class="inscol">
          <div class="institle"><?php 
_e( 'Items Sold', 'finpose' );
?></div>
          <div class="insnum">{{info.qty}}</div>
          <div class="insnote"></div>
        </div>
        <div class="inscol">
          <div class="institle"><?php 
_e( 'Avg. Order Value', 'finpose' );
?></div>
          <div class="insnum">{{info.avg}}</div>
          <div class="insnote"><?php 
_e( 'Total Revenue', 'finpose' );
?> / <?php 
_e( 'Orders', 'finpose' );
?></div>
        </div>
        <div class="inscol">
          <div class="institle"><?php 
_e( 'Avg. Time for Order', 'finpose' );
?></div>
          <div class="insnum">{{info.avgtime}}</div>
          <div class="insnote"><?php 
_e( 'Days', 'finpose' );
?> ({{info.days}}) / <?php 
_e( 'Orders', 'finpose' );
?></div>
        </div>
        <div class="inscol">
          <div class="institle"><?php 
_e( 'Spendings per Order', 'finpose' );
?></div>
          <div class="insnum">{{info.spo}}</div>
          <div class="insnote"><?php 
_e( 'Spendings', 'finpose' );
?> / <?php 
_e( 'Orders', 'finpose' );
?></div>
        </div>
        <div class="inscol">
          <div class="institle"><?php 
_e( 'Profit per Day', 'finpose' );
?></div>
          <div class="insnum">{{info.ppd}}</div>
          <div class="insnote"><?php 
_e( 'Net Profit', 'finpose' );
?> / <?php 
_e( 'Days', 'finpose' );
?> ({{info.days}})</div>
        </div>
        <div class="inscol">
          <div class="institle"><?php 
_e( 'Total Revenue', 'finpose' );
?></div>
          <div class="insnum">{{info.total}}</div>
          <div class="insnote"><?php 
_e( 'incl. Tax & Shipping', 'finpose' );
?></div>
        </div>
      </div>
    </div>
    <div class="tar pt2">
			<a href="https://wordpress.org/plugins/fin-accounting-for-woocommerce/#reviews" target="_blank" style="font-size:13px;">
        <?php 
_e( 'Help us improve Finpose by sharing your feedback', 'finpose' );
?>
        <img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/external.svg" class="icon-xs"/>
      </a>
		</div>

    <div class="profit-wrapper mt16">
      <h3><?php 
_e( 'Total Revenue vs. Net Profit', 'finpose' );
?></h3>
      <div class="sales-figures">
        <div class="sales-figure cogs">
          <div class="sf-number">
            {{info.cogs}}
          </div>
          <div class="sf-title"><?php 
_e( 'Cost of Goods Sold', 'finpose' );
?></div>
          <span class="note"><?php 
_e( 'Inventory Costs', 'finpose' );
?></span>
        </div>
        <div class="sales-figure spendings">
          <div class="sf-number">
            {{info.stotal}}
          </div>
          <div class="sf-title"><?php 
_e( 'Spendings', 'finpose' );
?></div>
          <span class="note"><?php 
_e( 'excl. Inventory Costs', 'finpose' );
?></span>
        </div>
        <div class="sales-figure taxes">
          <div class="sf-number">
            {{info.taxes}}
          </div>
          <div class="sf-title"><?php 
_e( 'Taxes', 'finpose' );
?></div>
          <span class="note"><?php 
_e( 'Taxes Payable', 'finpose' );
?> - <?php 
_e( 'Taxes Receivable', 'finpose' );
?></span>
        </div>
        <div :class="'sales-figure '+(info.profit>0?'profit':'loss')">
          <div class="sf-number">
            {{info.profit}}
          </div>
          <div class="sf-title"><?php 
_e( 'Net Profit', 'finpose' );
?></div>
          <span class="note"><?php 
_e( 'Revenue', 'finpose' );
?> - (<?php 
_e( 'COGS', 'finpose' );
?> + <?php 
_e( 'Spendings', 'finpose' );
?> + <?php 
_e( 'Tax', 'finpose' );
?>)</span>
        </div>
        <div :class="'sales-figure '+(info.profit>0?'profit':'loss')">
          <div class="sf-number">
            {{info.margin}}%
          </div>
          <div class="sf-title">
            <?php 
_e( 'Profit Margin', 'finpose' );
?>
          </div>
          <span class="note"><?php 
_e( 'Net Profit', 'finpose' );
?> / <?php 
_e( 'Revenue', 'finpose' );
?> * 100</span>
        </div>
      </div>
    </div>

    <div class="dashboxes mt16">
      <h3 class="m0"><?php 
_e( 'Order Breakdowns', 'finpose' );
?></h3>
      <div class="flex-row">
        <div class="card fx33">
          <h4><?php 
_e( 'Top Countries', 'finpose' );
?></h4>
          <div class="flex-row listline" v-for="(gv, gk) in info.geo">
            <span>{{gk}}</span>
            <span>{{currencySymbol}}{{gv}}</span>
          </div>
        </div>
        <div class="card fx33">
          <h4><?php 
_e( 'Bestsellers', 'finpose' );
?></h4>
          <div class="flex-row listline" v-for="(bsv, bsk) in info.bs">
            <span>{{bsk}}</span>
            <span>{{bsv}}</span>
          </div>
        </div>
        <div class="card fx33">
          <h4><?php 
_e( 'Payment Methods', 'finpose' );
?></h4>
          <div class="flex-row listline" v-for="(pmv, pmk) in info.pm">
            <span>{{pmk}}</span>
            <span>{{currencySymbol}}{{pmv}}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="dashboxes">
      <h3 class="m0"><?php 
_e( 'Spendings', 'finpose' );
?></h3>
      <div class="flex-row">
        <div class="card fx33">
          <h4><?php 
_e( 'Top Spending Categories', 'finpose' );
?></h4>
          <div class="flex-row listline" v-for="(catv, catk) in chartcats">
            <span>{{catk}}</span>
            <span>{{currencySymbol}}{{catv}}</span>
          </div>
        </div>
        <div class="card fx33">
          <h4><?php 
_e( 'Spendings by Type', 'finpose' );
?></h4>
          <div class="p1" style="padding:2rem;height:267px;">
						<canvas id="spendings-pie-chart"></canvas>
					</div>
        </div>
        <div class="card fx33">
          <h4><?php 
_e( 'Spendings by Payment Method', 'finpose' );
?></h4>
          <div class="p1" style="padding:2rem;height:267px;">
						<canvas id="spendings-bar-chart"></canvas>
					</div>
        </div>
      </div>
    </div>


  </div>
  <a href="https://finpose.com/docs/dashboard" target="_blank" style="font-size:13px;">
    <?php 
_e( 'Dashboard Documentation', 'finpose' );
?>
    <img src="<?php 
echo  FINPOSE_BASE_URL ;
?>assets/img/external.svg" class="icon-xs"/>
  </a>
</div>