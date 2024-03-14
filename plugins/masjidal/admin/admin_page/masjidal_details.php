<?php 
if(is_user_logged_in())
{
?>

<div class="tab">
  <button class="tablinks active" onclick="openCity(event, 'Details')"><?php esc_html_e( 'Masjid Settings', 'masjidal' );?></button>
 <button class="tablinks" onclick="openCity(event, 'label_text')">Label Text</button>
  <!-- <button class="tablinks" onclick="openCity(event, 'Cron')">Cron Scripts </button>
  <button class="tablinks" onclick="openCity(event, 'Endpoints')">Endpoints</button>-->
</div>
<?php 
$Save_label=sanitize_text_field($_POST['Save_label']);
$Save=sanitize_text_field($_POST['Save']);
 if($Save_label =='Save' || $Save =='Save'){ ?>
<div class="alert alert-success alert-dismissible" style="margin-top:18px;">
    <a href="#" class="close newclose" data-dismiss="alert" aria-label="close" title="close">×</a>
    
	<?php esc_html_e( 'Successful! save you data.', 'masjidal' );?>
</div>
<?php } ?>
<div id="Details" class="tabcontent">
  <div class="masjid_details">
<?php

   $masjid_id=sanitize_text_field($_POST['masjid_id']);
 $masjid_calendar_type=sanitize_text_field($_POST['masjid_calendar_type']);
 $masjid_calendar_layout=sanitize_text_field($_POST['masjid_calendar_layout']);
   $highlighted_color=sanitize_text_field($_POST['highlighted_color']);
    $jumuah3_time=sanitize_text_field($_POST['jumuah3_time']);
    $jumuah3time=sanitize_text_field($_POST['jumuah3time']);
    $khutbah_time1=sanitize_text_field($_POST['khutbah_time1']);
    $timeformat_24=sanitize_text_field($_POST['timeformat_24']);
    $iqamahChange=sanitize_text_field($_POST['iqamahChange']);
    $khutbah_time=sanitize_text_field($_POST['khutbah_time']);
    //$khutbah_label=sanitize_text_field($_POST['khutbah_label']);
   $highlighted_text_color=sanitize_text_field($_POST['highlighted_text_color']);
   $montly_pdf_url=sanitize_text_field($_POST['montly_pdf_url']);
   if(!empty($masjid_id) && !empty($masjid_calendar_type) && !empty($highlighted_color)){
   update_option('masjid_id',$masjid_id);
   if($masjid_calendar_type == 'Custom_url'){
	    update_option('montly_pdf_url',$montly_pdf_url);
   }
   update_option('masjid_calendar_type',$masjid_calendar_type);
   update_option('masjid_calendar_layout',$masjid_calendar_layout);
   update_option('highlighted_color',$highlighted_color);
   update_option('jumuah3_time',$jumuah3_time);
   update_option('jumuah3time',$jumuah3time);
   update_option('khutbah_time1',$khutbah_time1);
   update_option('timeformat_24',$timeformat_24);
   update_option('iqamahChange',$iqamahChange);
   update_option('khutbah_time',$khutbah_time);
   //update_option('khutbah_label',$khutbah_label);
   update_option('highlighted_text_color',$highlighted_text_color);
   ?>
    
   <?php
   }else{
	      ?>
  <!--  <div class="alert alert-danger  alert-dismissible" style="margin-top:18px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    <strong>Sorry!</strong> Please Enter Correct Value.
</div> -->
   <?php
   }
 $masjid_id=get_option('masjid_id');
   $masjid_calendar_type=get_option('masjid_calendar_type');
   $masjid_calendar_layout=get_option('masjid_calendar_layout');
  if(empty($masjid_calendar_type)){
	  $autocheckd='checked';
  }
  $highlighted_color=get_option('highlighted_color');
  $highlighted_text_color=get_option('highlighted_text_color');
  $montly_pdf_url=get_option('montly_pdf_url');
  $jumuah3_time=get_option('jumuah3_time');
  $jumuah3time=get_option('jumuah3time');
  $khutbah_time1=get_option('khutbah_time1');
  $timeformat_24=get_option('timeformat_24');
  $iqamahChange=get_option('iqamahChange');

  $khutbah_time=get_option('khutbah_time');
  //$khutbah_label=get_option('khutbah_label');
 if(empty($highlighted_color)){
	  $highlighted_color='#b3e5f3';
  }
  
if($masjid_calendar_type =='Custom_url'){
	$styel="display:block";
}else{
	$styel="display:none";
}

?>

<h3><?php esc_html_e( 'Masjid Details', 'masjidal' );?></h3>

<form method="post">
<div class="rest_filed">
<label><?php esc_html_e( 'Masjid ID :', 'masjidal' );?></label><input style="width: 16%;" type="text" name="masjid_id" id="masjid_id" value="<?php echo $masjid_id; ?>" required></div>
<div class="rest_filed">
<label style="width:24%;"><?php esc_html_e( 'Theme :', 'masjidal' );?> </label>
<!-- <div class="type_cal"><input type="radio" name="masjid_calendar_layout" id="masjid_calendar_layout" <?php  if($masjid_calendar_layout == 'Default'){ echo 'checked'; } ?> value="Default"><span><?php esc_html_e( 'Default', 'masjidal' );?></span></div>
<div class="type_cal"><input type="radio" name="masjid_calendar_layout" id="masjid_calendar_layout" <?php  if($masjid_calendar_layout == 'Layout1'){ echo 'checked'; } ?> value="Layout1"><span><?php esc_html_e( 'Layout 1', 'masjidal' );?></span></div> -->
<select name="masjid_calendar_layout" id="masjid_calendar_layout" style="width: 16%;">
<option value="Default" <?php  if($masjid_calendar_layout == 'Default'){ echo 'selected'; } ?>><?php esc_html_e( 'Default', 'masjidal' );?></option>
<option value="Layout1" <?php  if($masjid_calendar_layout == 'Layout1'){ echo 'selected'; } ?>><?php esc_html_e( 'Layout 1', 'masjidal' );?></option>
<option value="Layout2" <?php  if($masjid_calendar_layout == 'Layout2'){ echo 'selected'; } ?>><?php esc_html_e( 'Layout 2', 'masjidal' );?></option>
</select>
</div>

<div class="rest_filed">
<label style="width:100%;"><?php esc_html_e( 'Choose Monthly Calendar View :', 'masjidal' );?> </label>
<div class="type_cal first_div"><input type="radio" name="masjid_calendar_type" id="masjid_calendar_type" <?php  if($masjid_calendar_type == 'none'){ echo 'checked'; } ?> value="none"><span><?php esc_html_e( 'None', 'masjidal' );?></span></div>
<div class="type_cal first_div"><input type="radio" name="masjid_calendar_type" id="masjid_calendar_type" <?php  if($masjid_calendar_type == 'Custom_url'){ echo 'checked'; } ?> value="Custom_url"><span><?php esc_html_e( 'Custom', 'masjidal' );?></span></div>
<div class="type_cal"><input type="radio" name="masjid_calendar_type" id="masjid_calendar_type" <?php  if($masjid_calendar_type == 'v1'){ echo 'checked'; } ?> value="v1" <?php echo $autocheckd; ?>><img src="<?php echo  plugin_dir_url(dirname(dirname(__FILE__))) ?>/admin/img/CalendarWidget_1.jpg" alt="view1"></div>
<div class="type_cal"><input type="radio" name="masjid_calendar_type" id="masjid_calendar_type" <?php  if($masjid_calendar_type == 'v2'){ echo 'checked'; } ?> value="v2"><img src="<?php echo  plugin_dir_url(dirname(dirname(__FILE__))) ?>/admin/img/CalendarWidget_2.jpg" alt="view2"></div>
<div class="type_cal"><input type="radio" name="masjid_calendar_type" id="masjid_calendar_type" <?php  if($masjid_calendar_type == 'v3'){ echo 'checked'; } ?> value="v3"><img src="<?php echo  plugin_dir_url(dirname(dirname(__FILE__))) ?>/admin/img/CalendarWidget_3.jpg" alt="view3"></div>

</div>

<div style="<?php echo $styel; ?>" class="rest_filed montly_pdf_filed">
<label for="montly_pdf_url"><?php esc_html_e( 'Enter Monthly URL :', 'masjidal' );?></label>

  <input style="width: 60%;" id="montly_pdf_url" type="text" name="montly_pdf_url" value="<?php echo $montly_pdf_url;?>"></div>
  

  <div class="rest_filed">
  <label for="favcolor"><?php esc_html_e( "24-Hour Time Format :", 'masjidal' );?></label>

  <input type="checkbox" id="timeformat_24" name="timeformat_24" <?php  if($timeformat_24 == 'yes'){ echo 'checked'; } ?> value="yes"/>
 
</div>
<div class="rest_filed">
	<label for="favcolor"><?php esc_html_e( 'Hide Iqamah Change :', 'masjidal' );?></label>
	<input type="checkbox" id="iqamahChange" name="iqamahChange" <?php if($iqamahChange == 'yes'){ echo 'checked'; } ?> value="yes"/>
</div>
<div class="rest_filed">
	<label for="favcolor"><?php esc_html_e( "Khutbah Time (only if 1 Jumu'ah is offered) :", 'masjidal' );?></label>

	<input type="time" id="khutbahtimeInput" onchange="onkhutbahChange()" name="khutbah_time" value="<?php echo $khutbah_time; ?>"/>
	<input type="hidden" id="khutbah_time1"  name="khutbah_time1" value="<?php echo $khutbah_time1;?>"/>
</div>

<!--div class="rest_filed">
	<label for="favcolor"><?php esc_html_e( 'Jumuah 3 Time :', 'masjidal' );?></label>

	<input type="time" onchange="onTimeChange()" id="timeInput" name="jumuah3time" value="<?php echo $jumuah3time; ?>"/>
	<input type="hidden" id="jumuah3_time"  name="jumuah3_time" value="<?php echo $jumuah3_time;?>"/>
</div-->

<div class="rest_filed">
<label for="favcolor"><?php esc_html_e( 'Select Your Highlighted Color :', 'masjidal' );?></label>
  <input  type="color" id="favcolor" name="highlighted_color" value="<?php echo $highlighted_color;?>">   <strong><?php echo $highlighted_color;?></strong></div>
  <div class="rest_filed">
<label for="favcolor"><?php esc_html_e( 'Select Your Highlighted Text Color :', 'masjidal' );?></label>
  <input  type="color" id="favcolor" name="highlighted_text_color" value="<?php echo $highlighted_text_color;?>">   <strong><?php echo $highlighted_text_color;?></strong></div>
  <div class="rest_filed">
<input type="submit" value="<?php esc_html_e( 'Save', 'masjidal' );?>" name="Save" id="Save_info">    <strong class="info_s" style="margin-left: 10%; font-size: 15px;
">For more information, <a href="https://mymasjidal.com/knowledge-base/wordpress"><?php esc_html_e( 'Please click here', 'masjidal' );?></a> </strong></div>
</form>


</div>

</div>

<div id="label_text" class="tabcontent" style="display:none">
<?php 

 $Save_label=sanitize_text_field($_POST['Save_label']);

if($Save_label =='Save'){

 $starts_lable=sanitize_text_field($_POST['starts_lable']);
 $top_heading=sanitize_text_field($_POST['top_heading']);
$iqamah_lable=sanitize_text_field($_POST['iqamah_lable']);
$sunrise_lable=sanitize_text_field($_POST['sunrise_lable']);
$fajr_lable=sanitize_text_field($_POST['fajr_lable']);
$dhuhr_lable=sanitize_text_field($_POST['dhuhr_lable']);
$asr_lable=sanitize_text_field($_POST['asr_lable']);
$maghrib_lable=sanitize_text_field($_POST['maghrib_lable']);
$isha_lable=sanitize_text_field($_POST['isha_lable']);
 $jumuah_header=sanitize_text_field($_POST['jumuah_header']);
 $jumuah1_lable=sanitize_text_field($_POST['jumuah1_lable']);
$jumuah2_lable=sanitize_text_field($_POST['jumuah2_lable']);
$jumuah3_lable=sanitize_text_field($_POST['jumuah3_lable']);
$montly_text=sanitize_text_field($_POST['montly_text']);
$khutbah_label=sanitize_text_field($_POST['khutbah_label']);


 update_option('starts_lable',$starts_lable);
 update_option('top_heading',$top_heading);
 update_option('iqamah_lable',$iqamah_lable);
 update_option('sunrise_lable',$sunrise_lable);
 update_option('fajr_lable',$fajr_lable);
 update_option('dhuhr_lable',$dhuhr_lable);
 update_option('asr_lable',$asr_lable);
 update_option('maghrib_lable',$maghrib_lable);
 update_option('isha_lable',$isha_lable);
 update_option('jumuah_header',$jumuah_header);
 update_option('jumuah1_lable',$jumuah1_lable);
 update_option('jumuah2_lable',$jumuah2_lable);
 update_option('jumuah3_lable',$jumuah3_lable);
 update_option('montly_text',$montly_text);
 update_option('khutbah_label',$khutbah_label);
}


  $starts_lable= get_option('starts_lable');
  $top_heading= get_option('top_heading');
   $iqamah_lable=get_option('iqamah_lable');
   $sunrise_lable=get_option('sunrise_lable');
  $fajr_lable=get_option('fajr_lable');
  $dhuhr_lable=get_option('dhuhr_lable');
  $asr_lable=get_option('asr_lable');
 $maghrib_lable= get_option('maghrib_lable');
  $isha_lable=get_option('isha_lable');
 $jumuah_header= get_option('jumuah_header');
 $jumuah1_lable= get_option('jumuah1_lable');
 $jumuah2_lable= get_option('jumuah2_lable');
  $jumuah3_lable=get_option('jumuah3_lable');
  $montly_text=get_option('montly_text');
  $khutbah_label=get_option('khutbah_label');
?>
	<div class="masjid_details">
	<h3><?php esc_html_e( 'Change Label Text', 'masjidal' );?></h3>
		<form method="post">
		<div class="heading_filed"> 
      <div class="lable_filed"><label for="top_heading"><?php esc_html_e( 'Top Heading:', 'masjidal' );?></label><input type="text" name="top_heading" value="<?php echo $top_heading; ?>"></div>
		<div class="lable_filed"><label for="starts_lable"><?php esc_html_e( 'Starts:', 'masjidal' );?></label><input type="text" name="starts_lable" value="<?php echo $starts_lable; ?>"></div>
		 <div class="lable_filed"><label  for="iqamah_lable"><?php esc_html_e( 'Iqamah:', 'masjidal' );?></label><input type="text" name="iqamah_lable" value="<?php echo $iqamah_lable; ?>"> </div>
		 <div class="lable_filed"><label  for="sunrise_lable"><?php esc_html_e( 'Sunrise:', 'masjidal' );?></label><input type="text" name="sunrise_lable" value="<?php echo $sunrise_lable; ?>"> </div>
		 </div>
		<div class="heading_filed">  
		<div class="lable_filed"><label  for="fajr"><?php esc_html_e( 'Fajr:', 'masjidal' );?></label><input type="text" name="fajr_lable" value="<?php echo $fajr_lable; ?>"> </div>
		 <div class="lable_filed"><label  for="dhuhr"><?php esc_html_e( 'Dhuhr:', 'masjidal' );?></label><input type="text" name="dhuhr_lable" value="<?php echo $dhuhr_lable; ?>"> </div>
		 <div class="lable_filed"><label  for="asr"><?php esc_html_e( 'Asr:', 'masjidal' );?></label><input type="text" name="asr_lable" value="<?php echo $asr_lable; ?>"> </div>
		 <div class="lable_filed"><label  for="maghrib "><?php esc_html_e( 'Maghrib :', 'masjidal' );?></label><input type="text" name="maghrib_lable" value="<?php echo $maghrib_lable; ?>"> </div>
		 <div class="lable_filed"><label  for="Isha "><?php esc_html_e( 'Isha :', 'masjidal' );?></label><input type="text" name="isha_lable" value="<?php echo $isha_lable; ?>"> </div>
		</div>		
		<div class="heading_filed"> 
   
<div class="lable_filed"><label  for="khutbah_label "><?php esc_html_e( 'Khutbah  :', 'masjidal' );?></label><input type="text" name="khutbah_label" value="<?php echo $khutbah_label; ?>"> </div>

<div class="lable_filed"><label  for="jumuah1 "><?php esc_html_e( 'Jumuah Header  :', 'masjidal' );?></label><input type="text" name="jumuah_header" value="<?php echo $jumuah_header; ?>"> </div>

		<div class="lable_filed"><label  for="jumuah1 "><?php esc_html_e( 'Jumuah 1  :', 'masjidal' );?></label><input type="text" name="jumuah1_lable" value="<?php echo $jumuah1_lable; ?>"> </div>
		 <div class="lable_filed"><label for="jumuah2 "><?php esc_html_e( 'Jumuah 2  :', 'masjidal' );?></label><input type="text" name="jumuah2_lable" value="<?php echo $jumuah2_lable; ?>"> </div>
		 <div class="lable_filed"><label for="jumuah3  "><?php esc_html_e( 'Jumuah 3   :', 'masjidal' );?></label><input type="text" name="jumuah3_lable" value="<?php echo $jumuah3_lable; ?>"> </div>
     <div class="lable_filed"><label for="montly_text  "><?php esc_html_e( 'Monthly Url Text   :', 'masjidal' );?></label><input type="text" name="montly_text" value="<?php echo $montly_text; ?>"> </div>
	 </div>	
	 <div class="lable_filed">	<input type="submit" value="<?php esc_html_e( 'Save', 'masjidal' );?>" name="Save_label" id="Save_info"> </div>
		</form>
	</div>
</div>
<div id="Cron" class="tabcontent" style="display:none">

</div>
<div id="Endpoints" class="tabcontent" style="display:none">
 
</div>

<script>

function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
jQuery('input#masjid_calendar_type').click(function() {
	
   if(jQuery(this).is(':checked')) { 
 
     var val= jQuery(this).val();
	if(val == 'Custom_url'){
		jQuery(".rest_filed.montly_pdf_filed").show();
	}else{
		jQuery(".rest_filed.montly_pdf_filed").hide();
	}
   }
});

jQuery("a.newclose").click(function(){
	jQuery(".alert.alert-success.alert-dismissible").hide();
});



var inputEle = document.getElementById('timeInput');

function onTimeChange() {
  var timeSplit = inputEle.value.split(':'),
    hours,
    minutes,
    meridian;
	
  hours = timeSplit[0];
  minutes = timeSplit[1];
 // alert(hours);
  if (hours > 12) {
    meridian = 'PM';
    hours -= 12;
  } else if (hours < 12) {
    meridian = 'AM';
    if (hours == 0) {
      hours = 12;
    }
  } else {
    meridian = 'PM';
  }
if(hours ==null || minutes ==null || meridian ==null ){
    jQuery("#jumuah3_time").val("");
}else{
	
	jQuery("#jumuah3_time").val(hours + ':' + minutes + ' ' + meridian);
	
}
  
}

var inputEle1 = document.getElementById('khutbahtimeInput');

function onkhutbahChange() {
  var timeSplit = inputEle1.value.split(':'),
    hours,
    minutes,
    meridian;
  hours = timeSplit[0];
  minutes = timeSplit[1];
  if (hours > 12) {
    meridian = 'PM';
    hours -= 12;
  } else if (hours < 12) {
    meridian = 'AM';
    if (hours == 0) {
      hours = 12;
    }
  } else {
    meridian = 'PM';
  }
  //alert(hours + ':' + minutes + ' ' + meridian);
  if(hours ==null || minutes ==null || meridian ==null ){
	  jQuery("#khutbah_time1").val("");
  }else{
  jQuery("#khutbah_time1").val(hours + ':' + minutes + ' ' + meridian);
  }
}
</script>


<?php 
}
?>