<?php if ( !current_user_can('edit_plugins') )
       wp_die('<p>'.__('You do not have sufficient permissions to edit templates for this blog.').'</p>');
		$exclude_pages = get_option('exclude_pages');
		$pagearray = explode("," , $exclude_pages);
		$home_link = get_option('home_link');
		$custom_menu = get_option('custom_menu');
		$custom_menu_value = get_option('custom_menu_value');
		$include = get_option('include');
		$fadein = get_option('fadein');
		$fadeout = get_option('fadeout');
		$fadein1 = get_option('fadein1');
		$fadeout1 = get_option('fadeout1');
		$sort_by = get_option('sort_by');
		$sort_order = get_option('sort_order');
		$depth = get_option('depth');
		$custom_menu_include = get_option('custom_menu_include');
		$jddm_options =  get_option('jddm_options');
?>

<form  method="post">
<div class="jddm_wrapper wrap">
<div class="  jddm_options">
<?php

if( isset($_POST['action']) && $_POST['save']=='Save Changes' && $_GET['page']=='jquery_drop_down_menu' ){?>
<div id="message" class="updated fade">
 <p>Jquery Dropdown Menu settings are saved.</p>
</div>
<?php } ?>
<h2><?php echo __('Drop Down Menu Options'); ?></h2>
<div id="jddm-demo">
 <h3>Menu Demo:</h3>
 <?php jquery_drop_down_menu(); ?>
 <br />
 <br />
 <div>Please note, demo uses <a href="#" target="_blank">php template tag</a>.</div>
</div>
<h2 class="nav-tab-wrapper wp-clearfix">
 <?php

 $mode = $_GET['mode']==""?"general":$_GET['mode'];

?>
 <a href="options-general.php?page=jquery_drop_down_menu&mode=general#general" id="general" class="nav-tab <?php echo $mode=="general"?'nav-tab-active':'';?>">General
 Settings</a> <a href="options-general.php?page=jquery_drop_down_menu&mode=tags#tags" id="tags" class="nav-tab <?php echo $mode=="tags"?'nav-tab-active':'';?>">Tags
 Setting</a> <a href="options-general.php?page=jquery_drop_down_menu&mode=menueffect#menueffect" id="menueffect" class="nav-tab <?php echo $mode=="menueffect"?'nav-tab-active':'';?>">Menu
 Effect</a> <a href="options-general.php?page=jquery_drop_down_menu&mode=responsive#responsive" id="responsive" class="nav-tab <?php echo $mode=="responsive"?'nav-tab-active':'';?>">Resonsive
 Menu</a>  <a href="options-general.php?page=jquery_drop_down_menu&mode=customcss#customcss" id="customcss" class="nav-tab <?php echo $mode=="customcss"?'nav-tab-active':'';?>">Custom CSS and Effect</a> </h2>
<div class="general-setting <?php echo $mode=="general"?'tabshow':'tabhide';?>" >
 <div class="settings-section">
  <h3 id="general" class="section-title">General Setting</h3>
  <div class="jddm_input jddm_select clearfix">
   <label for="jddm_active_theme">Dropdown Menu Theme</label>
   <select name="jddm_options[jddm_theme]" id="jddm_theme">
    <?php  foreach($available_menustyle as $key=>$data)

	{?>
    <option value="<?php echo $data; ?>" <?php if($jddm_options['jddm_theme']==$data) echo "selected";?>><?php echo $key; ?></option>
    <?php } ?>
   </select>
   <small>Skin for the menu <a href="#" target="_blank" class="help-icon">(?)</a></small> </div>
  <div class="jddm_input jddm_checkbox clearfix">
   <label for="jddm_location_enabled">Enable Theme Location</label>
   <input type="checkbox"  <?php if($jddm_options['jddm_location_enabled']=="on") echo "checked";?> name="jddm_options[jddm_location_enabled]" id="jddm_location_enabled" value="on">
   <small>This option enables use of theme location. <a href="#" target="_blank" class="help-icon">(?)</a></small> </div>
  <div class="jddm_input jddm_select clearfix">
   <label for="jddm_location">Theme Location</label>
   <select name="jddm_options[jddm_location]" id="jddm_location">
    <?php 

  $menus = get_registered_nav_menus();

foreach ( $menus as $location => $description ) {

?>
    <option <?php if($jddm_options['jddm_location']==$location) echo "selected";?> value="<?php echo $location;?>"><?php echo $description;?></option>
    <?php 

}

?>
   </select>
   <small>This option will place dropdown menu automatically to the theme location. <a href="http://metinsaylan.com/docs/dropdown-menu-widget-help/#theme-location" target="_blank" class="help-icon">(?)</a></small> </div>
 </div>
 <div id="tabs-footer" class="clearfix">
  <p class="submit">
   <input type="submit" name="save" id="save" class="button button-primary" value="Save Changes">
  </p>
  <input type="submit" name="reset" id="reset" class="button" value="Reset Options">
 </div>
</div>
<div class="tag-setting <?php echo $mode=="tags"?'tabshow':'tabhide';?>">
<div class="settings-section">
 <h3 id="general" class="section-title">Tags Setting</h3>
 <div class="jddm_paragraph clearfix"> Settings here only effect menus inserted
  with <strong>template tag</strong> : <code>&lt;?php jquery_drop_down_menu();
  ?&gt;</code> or [jquery_drop_down_menu] . Widget settings are NOT affected
  by these settings. <a href="#" target="_blank" class="help-icon">(?)</a></div>
 <div class="jddm_input jddm_select clearfix">
  <label for="jddm_type">Menu Type</label>
  <select name="jddm_options[menutupe]" id="menutupe">
   <?php  foreach($available_codemenutype as $key=>$data)

	{?>
   <option value="<?php echo $key; ?>" <?php if($jddm_options['menutupe']==$key) echo "selected";?>><?php echo $data; ?></option>
   <?php } ?>
  </select>
  <small>Dropdown Menu Type <a href="#" target="_blank" class="help-icon">(?)</a></small> </div>
 <div class="jddm_input jddm_select clearfix">
  <label for="jddm_active_theme">Home</label>
  <input type="checkbox" name="home_link"  value="1" <?php if($home_link == "1"){ echo 'checked="checked"'; } ?>/>
 </div>
 <div class="jddm_input jddm_checkbox clearfix">
  <label for="jddm_location_enabled">Include custom menu </label>
  <input type="checkbox"  onclick="displayController(this)"  name="custom_menu_include"  value="1" <?php if($custom_menu_include == "1"){ echo 'checked="checked"'; } ?>/>
  <div id="custommenuid" <?php if (!$custom_menu_include){ echo 'style="display: none;"'; }?>>
   <input type="radio"  name="custom_menu"  value="1"<?php if($custom_menu == "1"){ echo 'checked="checked"'; } ?>/>
   Before Dynamic Menu &nbsp; <br />
   <input type="radio"  name="custom_menu"  value="2"<?php if($custom_menu == "2"){ echo 'checked="checked"'; } ?>/>
   After Dynamic Menu &nbsp;<br />
   <textarea  name="custom_menu_value" style="font-size:10px"  cols="100" rows="10" ><?php echo stripslashes($custom_menu_value); ?></textarea>
  </div>
 </div>
 <div class="jddm_input jddm_select clearfix">
  <label for="jddm_active_theme">Exclude Pages</label>
  <table width="98%" cellpadding="10" cellspacing="0" border="0">
   <?php $pages = get_pages(); 



			$count=1;



  foreach ($pages as $pagg) {



 if($count%5==1)



 { 



 echo "<tr>";



 }



   if



   (



 in_array("$pagg->ID", $pagearray)) {



  $checked= "checked='checked'"; }



   else



   {



   $checked= "";



   }



  



 







  	$option = '<td><input type="checkbox" '. $checked .' name="pageexclude[]" value="'.$pagg->ID.'" style="margin:0px">';



	$option .= '&nbsp;'.$pagg->post_title;



	$option .= '</td><td width="10px"></td>';



	echo 	$option;



	



	if($count%5==0)



    { 



      echo "</tr><tr><td height='10px'></td></tr>";



     $count=0;



    }



 



     $count++;



	}



	?>
  </table>
 </div>
 <div class="jddm_input jddm_select clearfix">
  <label for="jddm_active_theme">Menu Level</label>
  <input type="textbox" name="depth"  id="depth" size="4"  value="<?php echo $depth;?>"/>
  <br />
  (integer) This parameter controls how many levels in the hierarchy of pages
  are to be included . The default value is 0 (display all pages, including all
  sub-pages). <br />
  <br />
  * 0 - Pages and sub-pages displayed in hierarchical (indented) form (Default).<br />
  * -1 - Pages in sub-pages displayed in flat (no indent) form.<br />
  * 1 - Show only top level Pages <br />
  * 2 - Value of 2 (or greater) specifies the depth (or level) to descend in
  displaying Pages. . &nbsp; </div>
</div>
<div id="tabs-footer" class="clearfix">
<p class="submit">
 <input type="submit" name="save" id="save" class="button button-primary" value="Save Changes">
 <input type="hidden" name="action" value="save">
</p>
<form method="post">
 <input type="submit" name="reset" id="reset" class="button" value="Reset Options">
 <input type="hidden" name="action" value="reset">
</form>
</div>
</div>
<div class="menueffect-setting <?php echo $mode=="menueffect"?'tabshow':'tabhide';?>">
 <h3 id="general" class="section-title">Menu Effect</h3>
 <div class="settings-section">
  <div class="jddm_input jddm_checkbox clearfix">
   <label for="shailan_dm_effects">Enable dropdown effects</label>
   <input type="checkbox" name="include" id="donotinclude"   value="1" <?php if($include == "1"){ echo 'checked="checked"'; } ?>/>
   <small>If checked sub menus will use effects below <a href="#" target="_blank" class="help-icon">(?)</a></small> </div>
  <div class="jddm_input jddm_select clearfix">
   <label for="shailan_dm_effect">Effect</label>
   <select name="jddm_options[effecttype]" id="jddm_effecttype" >
    <option selected="selected" value="fade" <?php if($jddm_options['effecttype']=="fade") echo "selected";?>>Fade
    In/Out</option>
    <option value="toggle" <?php if($jddm_options['effecttype']=="toggle") echo "selected";?>>Slide
    Up/Down</option>
   </select>
   <small>Select effect you want to use <a href="#" target="_blank" class="help-icon">(?)</a></small> </div>
  <div class="jddm_input jddm_select clearfix">
   <label for="shailan_dm_effect_speed">Effect Speed</label>
   <select name="jddm_options[speed]"  id="jddm_speed">
    <option value="400" <?php if($jddm_options['speed']=="400") echo "selected";?>>Normal</option>
    <option <?php if($jddm_options['speed']=="fast") echo "selected";?> value="fast">Fast</option>
    <option value="slow" <?php if($jddm_options['speed']=="slow") echo "selected";?>>Slow</option>
   </select>
   <small>Select effect speed <a href="#" target="_blank" class="help-icon">(?)</a></small> </div>
  <div class="jddm_input jddm_select clearfix">
   <label for="shailan_dm_effect_delay">Effect delay</label>
   <select name="jddm_options[jddm_delay]" id="jddm_delay">
    <option selected="selected" value="100"  <?php if($jddm_options['jddm_delay']=="100") echo "selected";?>>100</option>
    <option value="200" <?php if($jddm_options['jddm_delay']=="200") echo "selected";?>>200</option>
    <option value="300" <?php if($jddm_options['jddm_delay']=="300") echo "selected";?>>300</option>
    <option value="400" <?php if($jddm_options['jddm_delay']=="400") echo "selected";?>>400</option>
    <option value="500" <?php if($jddm_options['jddm_delay']=="500") echo "selected";?>>500</option>
    <option value="600" <?php if($jddm_options['jddm_delay']=="600") echo "selected";?>>600</option>
    <option  value="700" <?php if($jddm_options['jddm_delay']=="700") echo "selected";?>>700</option>
   </select>
   <small>Select effect delay (uses hoverIntent) <a href="#" target="_blank" class="help-icon">(?)</a></small> </div>
  <div class="jddm_input jddm_select clearfix">
   <label for="shailan_dm_effect_delay">Page Sorting</label>
   <select name="jddm_options[sorting]" id="sorting">
    <option  value="ID"  <?php if($jddm_options['sorting']=="ID") echo "selected";?>>Page
    ID</option>
    <option  value="post_title"  <?php if($jddm_options['sorting']=="post_title") echo "selected";?>>Alphabetically</option>
    <option  value="menu_order"  <?php if($jddm_options['sorting']=="menu_order") echo "selected";?>> Page
    Order</option>
    <option  value="post_date"  <?php if($jddm_options['sorting']=="post_date") echo "selected";?>>Creation
    Time</option>
    <option  value="post_modified"  <?php if($jddm_options['sorting']=="post_modified") echo "selected";?>>Last
    Modified. </option>
    <option  value="post_name"  <?php if($jddm_options['sorting']=="post_name") echo "selected";?>>Post
    slug</option>
   </select>
  </div>
  <div class="jddm_input jddm_select clearfix">
   <label for="shailan_dm_effect_delay">Order</label>
   <select name="jddm_options[sortingorder]" id="sorting">
    <option  value="ASC"  <?php if($jddm_options['sortingorder']=="ASC") echo "selected";?>>ASC</option>
    <option  value="DESC"  <?php if($jddm_options['sortingorder']=="DESC") echo "selected";?>>DESC</option>
   </select>
  </div>
 </div>
 <div id="tabs-footer" class="clearfix">
  <p class="submit">
   <input type="submit" name="save" id="save" class="button button-primary" value="Save Changes">
  </p>
  <input type="submit" name="reset" id="reset" class="button" value="Reset Options">
 </div>
</div>
<div class="responsive-setting <?php echo $mode=="responsive"?'tabshow':'tabhide';?>">
 <h3 id="general" class="section-title">Resonsive Menu</h3>
 <div class="settings-section">
  <div class="jddm_input jddm_checkbox clearfix">
   <label for="shailan_dm_effects">Enable Resonsive Menu</label>
   <input type="checkbox" name="jddm_options[responivennable]" id="responivennable"   value="1" <?php if($jddm_options['responivennable'] == "1"){ echo 'checked="checked"'; } ?>/>
   <small>If checked resposive menu will use show <a href="#" target="_blank" class="help-icon">(?)</a></small> </div>
 </div>
 <div id="tabs-footer" class="clearfix">
  <p class="submit">
   <input type="submit" name="save" id="save" class="button button-primary" value="Save Changes">
  </p>
  <input type="submit" name="reset" id="reset" class="button" value="Reset Options">
 </div>
</div>


<div class="customcss-setting <?php echo $mode=="customcss"?'tabshow':'tabhide';?>">
 <h3 id="general" class="section-title"> Custom CSS/ Colors</h3>
 <div class="settings-section">
  <div class="jddm_input jddm_checkbox clearfix">
   <label for="shailan_dm_effects">Custom CSS</label>
   <textarea name="jddm_options[customcss]" type="textarea" id="customcss" cols="" rows=""> <?php echo $jddm_options['customcss'];  ?></textarea>
   <small>If checked resposive menu will use show <a href="#" target="_blank" class="help-icon">(?)</a></small> </div>
 </div>
 <div id="tabs-footer" class="clearfix">
  <p class="submit">
   <input type="submit" name="save" id="save" class="button button-primary" value="Save Changes">
  </p>
  <input type="submit" name="reset" id="reset" class="button" value="Reset Options">
 </div>
</div>

</div>
</div>
</form>
<script>

function displayController(chk){

	if(chk.checked){

	document.getElementById('custommenuid').style.display='';

	}

	else{

	document.getElementById('custommenuid').style.display='none';

	}

}

</script>
