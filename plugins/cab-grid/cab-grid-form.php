<?php
//settings_fields( 'cabGrid-settings-group' );
$cabGridPlaces = get_option( 'cabGrid_Places' );
$cabGridOptions = get_option( 'cabGrid_Options' ); 
$cabGridMsg="";
	$cabGridCurrency=$cabGridOptions['currency'];
	if($cabGridCurrency==''){
		$cabGridCurrency="$";
	}
	//$cabGridCSS=$cabGridOptions['css'];
	$cabGridMessage=$cabGridOptions['message'];
	if($cabGridMessage!=''){
		$cabGridMessage='<p class="cabGridMessage">'.nl2br($cabGridMessage).'</p>';
	}
	$cabGridPoweredBy=$cabGridOptions['poweredBy'];
	$cabGridPoweredByClass='cabGridTM';
	if($cabGridPoweredBy=='n' && !cabGridBot()){
		$cabGridPoweredByClass='cabGridOff';
	}
	
$cabGridSelect="";
$cabGridPlaceCounter=0;
if(is_array($cabGridPlaces)){
	foreach($cabGridPlaces as $cabGridPlace) {
		if(trim($cabGridPlace)!=""){
			$cabGridPlaceCounter++;
			$cabGridSelect.='<option value="'.$cabGridPlaceCounter.'">'.$cabGridPlace.'</option>';
		}
	} 
}
if ($cabGridPlaceCounter==0) {
	$cabGridAdminUrl=admin_url ( '/admin.php?page=cab-grid%2Fcab-grid-admin.php');
	$cabGridMsg="<h3 class='cabGridErrorMsg'>".__("At present, there are no destinations for this calculator.","cab-grid")." <a href='".$cabGridAdminUrl."'>".__("(Admin: please add at least two areas in the Wordpress admin.)","cab-grid")."</a></h3>";
}
$cabGridForm='
<div class="cabGrid" id="cabGrid-'.$cabGridInstance.'">'.$cabGridMsg.'
<form class="cabGridForm">
	<ul>
		<li class="cabGridPickCont"><label for="from">'.__('From','cab-grid').'</label>
			<select name="from" title="Pick up" data-placeholder="'.__('Pick up from','cab-grid').'..." class="cabGridPickSelect">
				<option value="" disabled selected>'.__('Pick up from','cab-grid').'</option>'.$cabGridSelect.'
			</select>
		</li>
		<li class="cabGridDropCont"><label for="to">'.__('To','cab-grid').'</label>
			<select name="to" title="Drop off" data-placeholder="&#8592; '.__('First choose pick up','cab-grid').'" data-placeholderb="'.__('Drop off at','cab-grid').'..." disabled="disabled" class="disabled cabGridDropSelect">
				<option value="" disabled selected>&#8592; '.__('First choose pick up','cab-grid').'</option>'.$cabGridSelect.'
			</select>
		</li>
		<li class="cabGridButtCont"><input type="submit" value="'.__('Get Price','cab-grid').'" name="getPrice" disabled="disabled" class="disabled cabGridButton" /></li>
	</ul>
</form>
<div class="cabGridOff cabGridPrice"><!--'.$cabGridCurrency.'--><span class="cabGridPriceContainer">'.__('No price available for this journey','cab-grid').'</span>'.$cabGridMessage.'</div>
<div class="'.$cabGridPoweredByClass.'">'.__('Powered by','cab-grid').' <a href="https://cabgrid.com" title="Wordpress '.__('Taxi quote and booking system','cab-grid').'">CabGrid Wordpress '.__('Taxi Price Calculator Plugin','cab-grid').'</a></div>
</div>
';
?>
