<?php

add_action( 'admin_init', 'register_beds24_settings');

function register_beds24_settings() {
$all_options=beds24_all_options();
foreach($all_options as $opt){
	register_setting( 'beds24_options', $opt );
}

};

add_action('admin_menu', 'beds24_menu');
function beds24_menu()
{
add_options_page('Beds24 Settings', 'Beds24', 'administrator', 'beds24-admin-menu', 'beds24_admin_page');
}


//this makes the new control html
function beds24_admin_page()
{
$url = plugins_url();
?><div id="b24_container" class="b24_wrap">
<div id="b24_header">
<div class="b24_logo">
<h2>Beds24 Online Booking System</h2>
</div>
<a target="#">
<div class="b24_icon-option"></div>
</a>
<div class="clear"></div>
</div>

<form method="post" action="options.php">
<div id="b24_main">
<?php settings_fields( 'beds24_options' ); ?>
<?php do_settings_sections( 'beds24_options' ); ?>

<div id="b24_of-nav">
<ul>
<li id="pn_bookingpage_li"> <a class="pn-view-a" href="#pn_bookingpage" title="Setting">Settings </a></li>
    
<li id="pn_widgets_li"> <a class="pn-view-a" href="#pn_widgets" title="Short Codes">Short Codes</a></li>
    
<li id="pn_agency_li"> <a class="pn-view-a" href="#pn_agency" title="Agency">Multiple Properties</a></li>
<li id="pn_languages_li"> <a class="pn-view-a" href="#pn_languages" title="Languages">Languages</a></li>    
<li id="pn_documentation_li"> <a class="pn-view-a" href="#pn_documentation" title="Documentation">Parameters</a></li>
<li id="pn_about_li"> <a class="pn-view-a" href="#pn_about" title="About">About</a></li>
</ul>
</div>

<div id="b24_content">

<div class="b24_group" id="pn_bookingpage">
<?php include_once('plugin-bookingpage.php'); ?>
</div>

<div class="b24_group" id="pn_widgets">
<?php include_once('plugin-widgets.php'); ?>
</div>

<div id="pn_agency" class="b24_group" style="display: block;">
<?php include_once('plugin-agency.php'); ?>
</div>

<div class="b24_group" id="pn_languages">
<?php include_once('plugin-languages.php'); ?>
</div>

<div class="b24_group" id="pn_documentation">
<?php include_once('plugin-documentation.php'); ?>
</div>

<div class="b24_group" id="pn_about">
<?php include_once('plugin-about.php'); ?>
</div>



</div>

</div>

<div class="b24_save_bar_top" style ="clear: both; margin-left:190px;">
	<?php submit_button(); ?>
</div>

</form>
</div>

<div>
<?php include_once('plugin-adminfooter.php'); ?>
</div>

<?php
}


function beds24_all_options($type='all_options'){

$default_values= array(
"beds24_ownerid"=> '',
"beds24_propid"=> '',
"beds24_roomid"=> '',
"beds24_height"=> 1600,
"beds24_width"=> 800,
"beds24_numdisplayed"=> -1,
"beds24_hidecalendar"=> -1,
"beds24_hideheader"=> -1,
"beds24_hidefooter"=> -1,
"beds24_advancedays"=> 7,
"beds24_numnight"=> 1,
"beds24_numadult"=> 2,
"beds24_numchild"=> 0,
"beds24_custom"=> '',
"beds24_target"=> 'window',
"beds24_color"=> '#dddddd',
"beds24_bgcolor"=> '#444444',
"beds24_padding"=> 10,
"beds24_referer"=> 'wordpress',
"beds24_domain"=> 'https://www.beds24.com',
"beds24_layout"=> '0',
);

if($type=='default_values'){
	return $default_values;
}
else{
	return array_keys ($default_values);
}
}
