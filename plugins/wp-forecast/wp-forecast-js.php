<script type="text/javascript">
	
<?php
/**
 * This toggles the new window field.
 *
 * @package wp-forecast
 */

?>
	 
function nwfields_update()
{
	obja=document.getElementById('d_c_accuweather');
	objb=document.getElementById('d_c_aw_newwindow');
	objb.disabled = (obja.checked == false);
}

<?php
// this toggles the visitorlocation / location fields.
?>
	 
function vlocfields_update()
{
	obja=document.getElementById('location');
	objb=document.getElementById('visitorlocation');
	obja.readOnly = (objb.checked == true);
}


<?php
// this is the javascript code for the ckeck/uncheck all forecast boxes.
?>
   function check(fname)
	{
	  if (fname=="day") {
	sammelvalue=document.woptions.alldays.checked;
	document.woptions.day1.checked=sammelvalue; 
	document.woptions.day2.checked=sammelvalue; 
	document.woptions.day3.checked=sammelvalue; 
	document.woptions.day4.checked=sammelvalue; 
	document.woptions.day5.checked=sammelvalue; 
	document.woptions.day6.checked=sammelvalue; 
	document.woptions.day7.checked=sammelvalue; 
	document.woptions.day8.checked=sammelvalue; 
	document.woptions.day9.checked=sammelvalue;
	  } else {
	sammelvalue=document.woptions.allnight.checked;
	document.woptions.night1.checked=sammelvalue; 
	document.woptions.night2.checked=sammelvalue; 
	document.woptions.night3.checked=sammelvalue; 
	document.woptions.night4.checked=sammelvalue; 
	document.woptions.night5.checked=sammelvalue; 
	document.woptions.night6.checked=sammelvalue; 
	document.woptions.night7.checked=sammelvalue; 
	document.woptions.night8.checked=sammelvalue; 
	document.woptions.night9.checked=sammelvalue;
	  }
	  return !sammelvalue;
	}
<?php
 // this is the javascript code for switching between weather providers.
?>
   function apifields(service)
	{
	  
	  if (service=="openweathermap" || service=="openweathermap3") {
		document.woptions.apikey1.disabled=false;
		if (typeof(document.woptions.allnight) != "undefined"){
			document.woptions.allnight.disabled=false;
			document.woptions.night1.disabled=false;
			document.woptions.night2.disabled=false;
			document.woptions.night3.disabled=false;
			document.woptions.night4.disabled=false;
			document.woptions.night5.disabled=false;
			document.woptions.night6.disabled=false;
			document.woptions.night7.disabled=false;
			document.woptions.night8.disabled=true;
			document.woptions.night9.disabled=true;
			document.woptions.day8.disabled=true;
			document.woptions.day9.disabled=true;
			document.woptions.d_d_wind.disabled=false;
			document.woptions.d_n_wind.disabled=false;	
			document.woptions.d_d_wgusts.disabled=false;	
			document.woptions.d_n_wgusts.disabled=false;
			document.woptions.d_c_precipe.disabled=false;
			document.woptions.d_d_precipe.disabled=false;	
			document.woptions.d_n_precipe.disabled=false;
		}
	  }
	  
	  if (service=="openmeteo" ) {
		document.woptions.apikey1.disabled=true;
		if (typeof(document.woptions.allnight) != "undefined"){
			document.woptions.allnight.disabled=true;
			document.woptions.night1.disabled=true;
			document.woptions.night2.disabled=true;
			document.woptions.night3.disabled=true;
			document.woptions.night4.disabled=true;
			document.woptions.night5.disabled=true;
			document.woptions.night6.disabled=true;
			document.woptions.night7.disabled=true;
			document.woptions.night8.disabled=true;
			document.woptions.night9.disabled=true;
			document.woptions.day7.disabled=true;
			document.woptions.day8.disabled=true;
			document.woptions.day9.disabled=true;
			document.woptions.d_d_wind.disabled=false;
			document.woptions.d_n_wind.disabled=false;	
			document.woptions.d_d_wgusts.disabled=false;	
			document.woptions.d_n_wgusts.disabled=false;
			document.woptions.d_c_precipe.disabled=false;
			document.woptions.d_d_precipe.disabled=false;	
			document.woptions.d_n_precipe.disabled=false;
	}
	  }
	  
	  return 0;
	}   
<?php
// this toggles the pulldown fields.
?>
	 
function pdfields_update()
{
	obja=document.getElementById('pdforecast');
	objb=document.getElementById('pdfirstday');
	objb.disabled = (obja.checked == false);
}
</script>
