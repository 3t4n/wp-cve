<?php
//admin interface

// create custom plugin settings menu
add_action('admin_menu', 'cabGrid_create_menu');


function cabGrid_create_menu() {
	
	$cabGridRole="manage_options";	//administrator
	
	//create new top-level menu
	add_menu_page('Cab Grid', 'Cab Grid', $cabGridRole, __FILE__, 'cabGrid_settings_page',plugin_dir_url( __FILE__ ) . 'admin-menu-icon.png' );

	//call register settings function
	add_action( 'admin_init', 'cabGrid_settings' );
}
function cabGrid_settings() {
	$args = array(
		'type' => 'number', 	//doesn't really have an impact - only in WP REST API
		'sanitize_callback' => 'cabGrid_sanitize_options',
		'default' => NULL,
		);
	//register our settings
	register_setting( 'cabGrid-settings-group', 'cabGrid_Prices', $args );
	$args['type'] = "string"; //doesn't really have an impact - only in WP REST API
	register_setting( 'cabGrid-settings-group', 'cabGrid_Places', $args );
	$args['sanitize_callback'] = "cabGrid_sanitize_options_loose"; //change santiation to allow for new lines etc...
	register_setting( 'cabGrid-settings-group', 'cabGrid_Options', $args );
}



function cabGrid_settings_page() {
	$cabGridDomain=get_option("siteurl");$cg=(3*(123%9))-(123%8);
	$cabGridDomain=str_replace("https://","",$cabGridDomain);
	$cabGridDomain=str_replace("http://","",$cabGridDomain);
	$cabGridDomain=str_replace("www.","",$cabGridDomain);
?>
<style>
	.cabGrid .off {display:none;}
	.cabGrid .left {text-align:left;}
	.cabGrid #cabGrid-header {}
	.cabGrid #cabGrid-header > div {display:inline-block;width:47%;padding:1%;vertical-align:top;}
	.cabGrid #cabGrid-header div ul {list-style:disc;margin-left: 2%;}
	.cabGrid #cabGrid-tabs {margin:20px 0 0 0;padding-left: 2vw;padding-right: 2vw;}
	.cabGrid #cabGrid-tabs ul {list-style:none;margin:0;padding:0;border-bottom:1px solid #aaa;text-transform:uppercase;}
	.cabGrid #cabGrid-tabs ul li {display:inline;padding:10px 10px 2px 10px;cursor:pointer;margin-right:10px;background-color:#999;color:#fff;}
	.cabGrid #cabGrid-tabs ul li.cgExtr {background-color: #ffffff;color: #AAA9C5;float: right;border-top: 1px solid #ccc;padding-top: 3px;border-left: 1px solid #d6d6d6;border-right: 1px solid #aaa;}
	.cabGrid #cabGrid-tabs ul li.active {background-color:#fffff8;color:#444;}
	.cabGrid #cabGrid-tabs ul li.cgExtr.active {background-color:#ccf;color:#444;margin-top:-6px;}
	.cabGrid form.cabGridForm {margin: 0 2vw;background-color: #fffff8;padding: 1vw 2vw;}
	.cabGrid .cabGrid-tab-body {position:relative;}
	.cabGrid ul.list {list-style:inside;margin-left:30px;}
	#cabGrid-CGT-Areas input {width: calc(100% - 330px); max-width: 375px;}
	.cabGrid textarea {width: calc(100% - 330px);}
	.cabGrid table.grid {width:100%;border-collapse:collapse;border:1px solid #d7d7e9;}
	.cabGrid table.grid th {font-weight:bold;padding:4px;position: sticky;top: 30px;left:160px;z-index: 8999;}
	.cabGrid table.grid tbody th {z-index: 8888;}
	@media only screen and (max-width: 960px) {
		.cabGrid table.grid th {left:36px;}
	}
	.cabGrid table.grid th.from,.from {background-color:#bbb;}
	.cabGrid table.grid th.to,.to {background-color:#fff;color:#888;}
	.cabGrid table.grid th span {display:inline-block;width:50%;padding:4px 0;text-align:left;}
	.cabGrid table.grid th span.to {float:right;}
	.cabGrid table.grid tr:hover {background-color:rgba(222,222,222,0.3);}
	.cabGrid table.grid tr:hover th {color:#888;background-color:rgb(222,222,222);}
	.cabGrid table.grid tr td {text-align:center;}
	.cabGrid table.grid tr td:hover {background-color:rgba(192,192,192,0.3);}
	.cabGrid table.grid input {max-width:5em;margin:4px 1px;}
	.cabGrid table.grid tr td.cabGridAdminPriceCell {}
	.cabGrid table.grid tr td.cabGridAdminPriceCell div {position:relative;}
	.cabGrid table.grid tr td.cabGridAdminPriceCell div > span {position: absolute;top: 4px;left: calc(50% - 2.5em);color: #a0a0a0;}
	.cabGrid table.grid tr td.cabGridAdminPriceCell div > input {padding-left: 1em;padding-right:0;}
	.cabGrid .cabGridWarning {font-weight:bold;color:red;}
	.cabGrid .cabGridAttention {font-weight:bold;border:2px solid #ccccef;border-radius:4px;padding:2%;margin:2%;text-align:center;font-size:110%;background-color:#fffff0;color:#6D6DFF;}
	.cabGrid .cabGridAttention b {font-size:125%;color:#ff6d6d;}
	.cabGrid .cabGridPromo {width: 30%;max-width: 350px;/* float: right; *//* clear: right; */position: absolute;right: 10px; top:20px;background-color:#faea00;box-shadow:0px 0px 10px 1px rgba(0,0,0,0.2);}
	.cabGrid .cabGridPromo iframe {width:100%;height:345px;}
	.cabGrid .cabGridPromo img {width:100%;}
	.cabGrid .promoPad {padding-right:35%;}
	.cabGrid .cabGridReview {text-align: center;}
	.cabGrid p.submit {text-align: center;}
	.cabGrid .cabGridHeader {position:relative;background-color:rgb(250,234,0);margin:0;width:100%;padding:0;}
	.cabGrid .cabGridHeader img {max-width: 100%;height: 10vh;width: auto;min-width: 25vw;object-fit: contain;object-position: left center;}
	.cabGrid .cabGridHeader .cabGridVersion {position:absolute;top: 1vw;right:1vw;color:#789;font-size: 14px;}
	
	@media only screen and ( max-width: 767px ) {
		.cabGrid .cabGridPromo {position:static;width:100%;float: none;clear: both;height:225px;margin:0 auto;max-width:100%;padding:0;}
		.cabGrid .cabGridPromo iframe {height:225px;}
		.cabGrid .promoPad {padding-right:0;}
	}
</style>
<div class="cabGrid admin Xwrap">
<div class="cabGridHeader"><img src="<?php echo plugins_url( 'cab-grid-block.jpg', __FILE__  );?>" width="620" height="150" alt="Cab Grid Block"><span class="cabGridVersion"><?php echo _cabGridVersion;?></span></div>
<?php if ( isset($_REQUEST['settings-updated']) ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Cab Grid Options saved!', 'cabGrid' ); ?></strong></p></div>
<?php endif; ?>
<div id="cabGrid-header">
	<div>
		<h3><?php _e('Quick Start','cab-grid');?></h3>
		<ol>
			<li><?php _e('Specify all of the areas/places your want to cover in the AREAS tab.','cab-grid');?></li>
			<li><?php _e('SAVE CHANGES','cab-grid');?></li>
			<li><?php _e('Enter prices for journeys between these areas in the PRICES tab.','cab-grid');?></li>
			<li><?php _e('SAVE CHANGES','cab-grid');?></li>
			<li><?php _e('Place on your site via one of these methods:','cab-grid');?>
				<ul><li><?php _e('Insert the "Cab Grid: Simple Calculator" block in your page editor','cab-grid');?></li>
					<li><?php _e('Type the shortcode <b>[cabGrid]</b> where you want to display the calculator.','cab-grid');?></li>
					<li><a href="widgets.php"><?php _e('Drag the Cab Grid Widget to your sidebar area.','cab-grid');?></a></li>
				</ul>
			</li>
		</ol>
	</div>
	<div>
		<h3><?php _e('Want more?... Upgrade!','cab-grid');?></h3>
		<p><?php _e('This is the basic version of Cab Grid.','cab-grid');?> <?php _e('For advanced features consider upgrading to','cab-grid');?> <a href="http://get.cabgrid.com/basic.php?d=<?php echo $cabGridDomain; ?>" title="Find out more about Cab Grid Pro">Cab Grid Pro</a> <?php _e('or','cab-grid');?> <a class="taximapregistration" href="#" title="Map based taxi price calculator" target="_blank">TaxiMap</a>.</p>
		<p><strong>Cab Grid Pro</strong> <?php _e('provides the following additional features','cab-grid');?>:
			<ul>
				<li><?php _e('Price variation for different vehicles','cab-grid');?></li>
				<li><?php _e('Unlimited places/areas','cab-grid');?></li>
				<li><?php _e('Integrated booking form','cab-grid');?></li>
				<li><?php _e('Take credit card payments via Paypal','cab-grid');?> <?php _e('and','cab-grid');?>/<?php _e('or','cab-grid');?> Stripe</li>
				<li><a href="https://cabgrid.com/about-cab-grid/cab-grid-pro-features/?f=cgb-wpa" target="_blank"><?php _e('More','cab-grid');?>...</a></li>
			</ul>
		</p>
	</div>
</div>
<div id="cabGrid-tabs">
	<ul>
		<li class="active" id="CGT-Areas"><?php _e('Areas','cab-grid');?></li>
		<li id="CGT-Prices"><?php _e('Prices','cab-grid');?></li>
		<li id="CGT-Options"><?php _e('Options','cab-grid');?></li>
		<!--<li class="cgExtr" id="CGT-App"><?php _e('Build Your Mobile App','cab-grid');?></li>-->
		<li class="cgExtr" id="CGT-Hire"><?php _e('Hire Us','cab-grid');?></li>
		<li class="cgExtr" id="CGT-TaxiMap">TaxiMap</li>
		<li class="cgExtr" id="CGT-GoPro"><?php _e('Go Pro','cab-grid');?></li>
	</ul>
</div>
	<form method="post" action="options.php" class="cabGridForm"> 
		<?php settings_fields( 'cabGrid-settings-group' ); ?>
		
		<!-- OPTIONS -->
		<div id="cabGrid-CGT-Options" class="off cabGrid-tab-body">
			
			<?php $cabGridOptions = get_option( 'cabGrid_Options' ); 
					$cabGridCurrency=$cabGridOptions['currency'];
					if($cabGridCurrency==''){
						$cabGridCurrency="$";
					}
					$cabGridCSS=$cabGridOptions['css'];
					if($cabGridCSS==''){
						$cabGridCSS=".cabGrid {}\r\n.cabGrid div.cabGridPrice {}\r\n.cabGrid p.cabGridMessage {}";
					}
					$cabGridMessage=$cabGridOptions['message'];
					if($cabGridMessage==''){
						$cabGridMessage= __('Please call to book this journey','cab-grid');
					}
					$cabGridPoweredBy=$cabGridOptions['poweredBy'];
					if($cabGridPoweredBy==''){
						$cabGridPoweredBy="y";
					}
					$cabGridCurrencyPlacement=(isset($cabGridOptions['currencyPlacement'])) ? $cabGridOptions['currencyPlacement'] : "before";
			?>
			<p><?php _e('Please select additional options below','cab-grid');?>.</p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e('Currency','cab-grid');?> (<?php _e('Symbol','cab-grid');?>):</th>
					<td>
						<input style="width:2em;font-size:200%;padding:10px;line-height:1;margin-right: 10px;" type="text" name="cabGrid_Options[currency]" value="<?php echo esc_attr($cabGridCurrency); ?>" />
						<span class="placement"><?php _e("Placement:",'cab-grid');?>
							<input type="radio" name="cabGrid_Options[currencyPlacement]" value="before" <?php echo ($cabGridCurrencyPlacement=="before")? "checked" : "";?>><?php _e("Before Price",'cab-grid');?> | 
							<input type="radio" name="cabGrid_Options[currencyPlacement]" value="after" <?php echo ($cabGridCurrencyPlacement=="after")? "checked" : "";?>><?php _e("After Price",'cab-grid');?>
						</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Styling','cab-grid');?> (CSS):</th>
					<td><textarea name="cabGrid_Options[css]" rows="10" cols="30"><?php echo $cabGridCSS; ?></textarea></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Message','cab-grid');?> (<?php _e('shown with price','cab-grid');?>):</th>
					<td><textarea name="cabGrid_Options[message]" rows="10" cols="30"><?php echo $cabGridMessage; ?></textarea></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Show','cab-grid');?> '<i><?php _e('Powered by','cab-grid');?> Cab Grid</i>':</th>
					<td>
						<input type="radio" name="cabGrid_Options[poweredBy]" value="y" <?php if($cabGridPoweredBy=="y"){echo 'checked';}?>><?php _e('Yes','cab-grid');?> | 
						<input type="radio" name="cabGrid_Options[poweredBy]" value="n" <?php if($cabGridPoweredBy=="n"){echo 'checked';}?>><?php _e('No','cab-grid');?>						
					</td>
				</tr>
			</table>
			<div id="cabGrid-Promo-Options" class="cabGridPromo"><iframe src="https://get.cabgrid.com/offer.php?d=<?php echo $cabGridDomain; ?>&nonce=<?php echo time(); ?>"></iframe></div>
		</div>
		
		
		<!-- PLACES -->
			<div id="cabGrid-CGT-Areas" class="cabGrid-tab-body">
				
				<?php $cabGridPlaces = get_option( 'cabGrid_Places' ); ?>
				<p class="promoPad"><?php _e('Please enter the names of places or areas your travel between.','cab-grid');?></p>
				<p class="cabGridWarning promoPad"><?php _e('WARNING: If you edit these after entering prices, you will lose prices associated with the old area label','cab-grid');?>. <!-- (<?php _e('Get CabGrid Pro to solve this and much more','cab-grid');?>) --></p>
			<table class="form-table">
				<?php for($i=1;$i<=$cg;$i++){?>
				<tr valign="top">
					<th scope="row"><?php _e('Area','cab-grid');?> <?php echo $i;?>:</th>
					<td><input type="text" name="cabGrid_Places[place<?php echo $i;?>]" value="<?php echo esc_attr(cabGridArr($cabGridPlaces,'place',$i)); ?>" /></td>
				</tr>
				<?php }?>
			</table>
			<div id="cabGrid-Promo-Places" class="cabGridPromo"><iframe src="https://get.cabgrid.com/offer.php?d=<?php echo $cabGridDomain; ?>&nonce=<?php echo time(); ?>"></iframe></div>
		</div>
		
		<div id="cabGrid-CGT-Prices" class="off cabGrid-tab-body">
			<!-- PRICES -->
			<p><?php _e('Please enter prices for journeys between these places (hover your mouse over the text field for details).','cab-grid');?><p>
			<p><?php _e('Note: Apart from journeys remaining within a single area, each two places can have both FROM and TO prices. These may often be the same, but you are able to specify different prices if the need arises.','cab-grid');?>
				<br /><?php _e('i.e. The price for a journey starting <i>from</i> place1 going <i>to</i> place2 might be different to the price starting <i>from</i> place2 <i>to</i> place1.','cab-grid');?></p>
			<?php $cabGridPrices = get_option( 'cabGrid_Prices' ); ?>
			<table class="grid">
				<thead>
					<tr>
						<th style="padding:0;z-index:9999;"><span class="from">&nbsp;&#8681; <?php _e('From','cab-grid');?></span><span class="to">&nbsp;<?php _e('To','cab-grid');?> &#8680;</span></th>
						<?php
							$cols=1;
							for($i=1;$i<=$cg;$i++){
								$to=cabGridArr($cabGridPlaces,'place',$i);
								if($to!=""){
									$cols=$i;
						?>
									<th class="to"><?php echo $to; ?></th>
						<?php 	} 
							} ?>
					</tr>
				</thead>
				<tbody>
				<?php 
					$rows=0;
					for($i=1;$i<=$cg;$i++){
						$cabGridFrom=cabGridArr($cabGridPlaces,'place',$i);
					
					if($cabGridFrom!=""){
						$rows=$i;
				?>
					<tr>
						<th scope="row" class="left from"><?php echo $cabGridFrom; ?></th>
						<?php for($j=1;$j<=$cols;$j++){
							$cabGridTo=cabGridArr($cabGridPlaces,'place',$j);
						?>
							<td class="cabGridAdminPriceCell"><div><span><?php echo $cabGridCurrency;?></span><input title="<?php _e('Price for journey from','cab-grid');?> <?php echo $cabGridFrom;?> <?php _e('to','cab-grid');?> <?php echo $cabGridTo;?>" type="number" step="any" name="cabGrid_Prices[<?php echo $cabGridFrom;?>-<?php echo $cabGridTo;?>]" value="<?php echo esc_attr(cabGridArr($cabGridPrices,$cabGridFrom."-".$cabGridTo,"")); ?>" /></div></td>
						<?php }?>
					</tr>
				<?php }	?>
				<?php } 
					if($rows<1){
				?>
					<tr><td colspan="<?php echo $rows;?>"><h3 style="text-align: center;color:red;"><?php _e('Please add some destinations in the AREAS tab first!','cab-grid');?></h3></td></tr>
				<?php }	?>
				</tbody>
			</table>
		</div>
		<div id="cabGrid-CGT-GoPro" class="off cabGrid-tab-body">
			<div id="cabGrid-Promo-GoPro" class="cabGridPromo"><iframe src="https://get.cabgrid.com/offer.php?d=<?php echo $cabGridDomain; ?>&nonce=<?php echo time(); ?>"></iframe></div>
			<h3><?php _e('Want more?... Upgrade!','cab-grid');?></h3>
			<p class="promoPad"><?php _e('This is the basic version of Cab Grid.','cab-grid');?> <?php _e('For advanced features consider upgrading to','cab-grid');?> <a href="http://get.cabgrid.com/basic.php?f=cgb-wpa&d=<?php echo $cabGridDomain; ?>" title="Find out more about Cab Grid Pro">Cab Grid Pro</a>.</p>
			<p class="promoPad"><strong>Cab Grid Pro</strong> <?php _e('provides the following additional features','cab-grid');?>:
				<ul class="list">
					<li><?php _e('Price variation for different vehicles','cab-grid');?></li>
					<li><?php _e('Unlimited places/areas','cab-grid');?></li>
					<li><?php _e('Integrated booking form','cab-grid');?></li>
					<li><?php _e('Take credit card payments via Paypal and/or Stripe','cab-grid');?></li>
					<li><a href="https://cabgrid.com/about-cab-grid/cab-grid-pro-features/" target="_blank"><?php _e('More','cab-grid');?></a>...</li>
				</ul>
			</p>
			<h3><a href="http://get.cabgrid.com/basic.php?f=cgb-wpa&d=<?php echo $cabGridDomain; ?>&upgrade=1" target="_blank"><?php _e('Click here to upgrade now','cab-grid');?></a></h3>
			<p><a href="<?php echo plugin_dir_url( __FILE__ )."cab-grid-compatibility.php"; ?>" target="_blank"><?php _e('Check compatibility','cab-grid');?>...</a></p>
		</div>
		<div id="cabGrid-CGT-TaxiMap" class="off cabGrid-tab-body">
			<h3>TaxiMap</h3>
			<p><?php _e('TaxiMap is our more advanced taxi fare price calculator. It leverages Google Maps to calculate prices based on distance. It is highly configurable and offers many advanced features, such as:','cab-grid');?>
				<ul class="list">
					<li><?php _e('Set price variations for time of day, vehicle, zone, and more','cab-grid');?></li>
					<li><?php _e('Easily integrates with your website via dedicated Wordpress plugin or simple code paste','cab-grid');?></li>
					<li><?php _e('Integrated booking process','cab-grid');?></li>
					<li><?php _e('Process credit card payments for taxi bookings','cab-grid');?></li>
					<li><?php _e('Send and receive SMS text message notifications about bookings on your mobile phone','cab-grid');?></li>
				</ul>
			</p>
			<h3><a href="#" class="taximapregistration" target="_blank"><?php _e('Click here to learn more about TaxiMap and sign up for free','cab-grid');?></a></h3>	
		</div>
		<div id="cabGrid-CGT-Hire" class="off cabGrid-tab-body">
			<h3><?php _e('Hire us to customise your web site','cab-grid');?></h3>
			<p><?php _e('We are web developers and designers with expertise in Wordpress.','cab-grid');?></p>
			<p><?php _e('We can help with any aspect of your website or business IT.','cab-grid');?></p>
			<p><a href="https://cabgrid.com/hire-us/" target="_blank"><?php _e('Click here to find out about our Cab Grid packages and request a quote','cab-grid');?></a>...</p>
		</div>
		<div id="cabGrid-CGT-App" class="off cabGrid-tab-body">
			<h3>iOS &amp; Android <?php _e('App Development','cab-grid');?></h3>
			<p><?php _e('We can build a mobile app for your business! It might not be as expensive as you think. Our apps work on both Apple and Android based devices and offer these features:','cab-grid');?>
				<ul class="list">
					<li><?php _e('Send Push notifications','cab-grid');?></li>
					<li><?php _e('Take bookings via app','cab-grid');?></li>
					<li><?php _e('Bookings added to user\'s calendar','cab-grid');?></li>
					<li><?php _e('Loyalty scheme - reward your customers','cab-grid');?></li>
					<li><?php _e('Social media integration','cab-grid');?></li>
				</ul>
			</p>
			<h3><a href="http://mobile-taxi-app.com/" target="_blank"><?php _e('Visit Mobile Taxi App for more info','cab-grid');?></a></h3>
		</div>
	<?php submit_button(); ?>
	</form>
	
	
	<p class="cabGridAttention"><?php _e('To display the plugin on a page/post, just add the following short-code where you want it to appear<br />(for more info, see plug-in read-me file or visit <a href="http://cabgrid.com/installation-configuration/?f=cgb-wpa" target="_blank">cabgrid.com</a>)','cab-grid');?><br /><b>[cabGrid]</b></p>
	<p class="cabGridReview"><a target="_blank" href="https://login.wordpress.org/?redirect_to=https%3A%2F%2Fwordpress.org%2Fsupport%2Fplugin%2Fcab-grid%2Freviews%2F">Please consider leaving us a favourable review</a> or <a target="_blank" href="https://cabgrid.com/support">contacting us if you experience a problem</a>.<p>
</div>
<script>
jQuery("#cabGrid-tabs ul li").click(function(){
	jQuery("#cabGrid-tabs ul li").removeClass("active");
	jQuery(".cabGrid-tab-body").hide();
	var cabGridTab=jQuery(this).prop("id");
	jQuery("#cabGrid-"+cabGridTab).show();
	jQuery(this).addClass("active")
	jQuery(".taximapregistration").on("click", function(e){
		var tmlink=jQuery(this)[0];
		tmlink.href="https://register.taximap.co.uk/go.asp?t="+Date.now()+"&d="+location.hostname;
		
	});
	jQuery(".taximapregistration").on("mouseleave", function(e){
		var tmlink=jQuery(this)[0];
		tmlink.href="#";
	});
});
</script>
<?php }


//function wporg_setting_callback_function() {
//$setting = esc_attr( get_option( 'wporg_setting_name' ) );
//echo "<input type='text' name='wporg_setting_name' value='$setting' />";
//}

?>