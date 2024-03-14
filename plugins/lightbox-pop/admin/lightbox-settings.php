<?php
if ( ! defined( 'ABSPATH' ) ) exit;
	// Load the options
$xyz_tinymce=get_option('xyz_tinymce');
if($xyz_tinymce==1)
{
    require( dirname( __FILE__ ) . '/tinymce_filters.php' );
}
if (isset($_POST['xyz_lbx_html']))
{
    if (
        ! isset( $_REQUEST['_wpnonce'] )
        || ! wp_verify_nonce( $_REQUEST['_wpnonce'],'add-setting' )
        ) {
            wp_nonce_ays( 'add-setting');
            exit;
            
        }
        
        $_POST=stripslashes_deep($_POST);
        $xyz_lbx_iframe=intval($_POST['xyz_lbx_iframe']);
        $xyz_lbx_repeat_interval_timing=intval($_POST['xyz_lbx_repeat_interval_timing']);
        $xyz_lbx_display_user=intval($_POST['xyz_lbx_display_user']);
        $xyz_lbx_html=stripslashes($_POST['xyz_lbx_html']);
        $xyz_lbx_showing_option="0,0,0";
        $xyz_lbx_display_position=intval($_POST['xyz_lbx_display_position']);
        $xyz_lbx_top=abs(intval($_POST['xyz_lbx_top']));
        $xyz_lbx_bottom=abs(intval($_POST['xyz_lbx_bottom']));
        $xyz_lbx_width=abs(intval($_POST['xyz_lbx_width']));
        $xyz_lbx_height=abs(intval($_POST['xyz_lbx_height']));
        $xyz_lbx_left=abs(intval($_POST['xyz_lbx_left']));
        
        $xyz_lbx_right=abs(intval($_POST['xyz_lbx_right']));
        $xyz_lbx_delay=abs(intval($_POST['xyz_lbx_delay']));
        $xyz_lbx_page_count=abs(intval($_POST['xyz_lbx_page_count']));
        $xyz_lbx_repeat_interval=abs(intval($_POST['xyz_lbx_repeat_interval']));
        $xyz_lbx_mode=sanitize_text_field($_POST['xyz_lbx_mode']);
        $xyz_lbx_z_index=intval($_POST['xyz_lbx_z_index']);
        $xyz_lbx_opacity=abs(intval($_POST['xyz_lbx_opacity']));
        $xyz_lbx_color=sanitize_text_field($_POST['xyz_lbx_color']);
        
        $xyz_lbx_bg_opacity=abs(intval($_POST['xyz_lbx_bg_opacity']));
        $xyz_lbx_bg_color=sanitize_text_field($_POST['xyz_lbx_bg_color']);
        $xyz_lbx_corner_radius=abs(intval($_POST['xyz_lbx_corner_radius']));
        $xyz_lbx_top_dim=sanitize_text_field($_POST['xyz_lbx_top_dim']);
        $xyz_lbx_left_dim=sanitize_text_field($_POST['xyz_lbx_left_dim']);
        $xyz_lbx_right_dim=sanitize_text_field($_POST['xyz_lbx_right_dim']);
        $xyz_lbx_bottom_dim=sanitize_text_field($_POST['xyz_lbx_bottom_dim']);
        $xyz_lbx_width_dim=sanitize_text_field($_POST['xyz_lbx_width_dim']);
        $xyz_lbx_height_dim=sanitize_text_field($_POST['xyz_lbx_height_dim']);
        $xyz_lbx_border_color=sanitize_text_field($_POST['xyz_lbx_border_color']);
        //$xyz_lbx_border_dim=$_POST['xyz_lbx_border_dim'];
        $xyz_lbx_border_width=abs(intval($_POST['xyz_lbx_border_width']));
        $xyz_lbx_page_option=intval($_POST['xyz_lbx_page_option']);// fo Placement mechanism
        $xyz_lbx_close_button_option=intval($_POST['xyz_lbx_close_button_option']);
        $xyz_lbx_positioning=intval($_POST['xyz_lbx_positioning']);
        $xyz_lbx_position_predefined=intval($_POST['xyz_lbx_position_predefined']);
        
        if($xyz_lbx_page_option==2)
        {
            $lbx_pgf=0;
            $lbx_pof=0;
            $lbx_hp=0;
            if(isset($_POST['xyz_lbx_pages']))
                $lbx_pgf=1;
                if(isset($_POST['xyz_lbx_posts']))
                    $lbx_pof=1;
                    if(isset($_POST['xyz_lbx_hp']))
                        $lbx_hp=1;
                        
                        $xyz_lbx_showing_option=$lbx_pgf.",".$lbx_pof.",".$lbx_hp;
                        
                        update_option('xyz_lbx_showing_option',$xyz_lbx_showing_option);
                        
        }
        
        $old_page_count=get_option('xyz_lbx_page_count');
        $old_repeat_interval=get_option('xyz_lbx_repeat_interval');
        if(isset($_POST['xyz_lbx_cookie_resett']))
        {
            ?>
	<script language="javascript">

 var cookie_date = new Date ( );  // current date & time
 cookie_date.setTime ( cookie_date.getTime() - 1 );

  document.cookie = "_xyz_lbx_pc=; expires=" + cookie_date.toGMTString()+ ";path=/";
  document.cookie = "_xyz_lbx_until=; expires=" + cookie_date.toGMTString()+ ";path=/";
</script>
<?php 	
}
		update_option('xyz_lbx_html',$xyz_lbx_html);
		update_option('xyz_lbx_top',$xyz_lbx_top);
		update_option('xyz_lbx_width',$xyz_lbx_width);
		update_option('xyz_lbx_right',$xyz_lbx_right);
		update_option('xyz_lbx_bottom',$xyz_lbx_bottom);
		update_option('xyz_lbx_height',$xyz_lbx_height);
		update_option('xyz_lbx_left',$xyz_lbx_left);
		update_option('xyz_lbx_delay',$xyz_lbx_delay);
		update_option('xyz_lbx_page_count',$xyz_lbx_page_count);
		update_option('xyz_lbx_repeat_interval',$xyz_lbx_repeat_interval);
		update_option('xyz_lbx_repeat_interval_timing',$xyz_lbx_repeat_interval_timing);
		update_option('xyz_lbx_display_user',$xyz_lbx_display_user);
		update_option('xyz_lbx_mode',$xyz_lbx_mode);
		update_option('xyz_lbx_z_index',$xyz_lbx_z_index);
		update_option('xyz_lbx_opacity',$xyz_lbx_opacity);
		update_option('xyz_lbx_color',$xyz_lbx_color);
		update_option('xyz_lbx_corner_radius',$xyz_lbx_corner_radius);
		update_option('xyz_lbx_top_dim',$xyz_lbx_top_dim);
		update_option('xyz_lbx_height_dim',$xyz_lbx_height_dim);	
		update_option('xyz_lbx_left_dim',$xyz_lbx_left_dim);
		update_option('xyz_lbx_bottom_dim',$xyz_lbx_bottom_dim);
		update_option('xyz_lbx_right_dim',$xyz_lbx_right_dim);

		update_option('xyz_lbx_width_dim',$xyz_lbx_width_dim);
		update_option('xyz_lbx_border_color',$xyz_lbx_border_color);

//update_option('xyz_lbx_border_dim',$xyz_lbx_border_dim);
		update_option('xyz_lbx_border_width',$xyz_lbx_border_width);
		update_option('xyz_lbx_bg_color',$xyz_lbx_bg_color);
		update_option('xyz_lbx_bg_opacity',$xyz_lbx_bg_opacity);
		update_option('xyz_lbx_page_option',$xyz_lbx_page_option);
		update_option('xyz_lbx_close_button_option',$xyz_lbx_close_button_option);
		update_option('xyz_lbx_iframe',$xyz_lbx_iframe);
		update_option('xyz_lbx_display_position',$xyz_lbx_display_position);
		update_option('xyz_lbx_positioning',$xyz_lbx_positioning);
		update_option('xyz_lbx_position_predefined',$xyz_lbx_position_predefined);
		
		?><br>
		
<div  class="system_notice_area_style1" id="system_notice_area">Settings updated successfully.<span id="system_notice_area_dismiss">Dismiss</span></div>
<?php
}


?>
<style type="text/css">
label{
cursor:default;


}
</style>
<script type="text/javascript">
 
  jQuery(document).ready(function() {
    jQuery('#lbxcolorpicker').hide();
    jQuery('#lbxcolorpicker').farbtastic("#xyz_lbx_color");
    jQuery("#xyz_lbx_color").click(function(){jQuery('#lbxcolorpicker').slideToggle();});
    jQuery('#lbxbordercolorpicker').hide();
    jQuery('#lbxbordercolorpicker').farbtastic("#xyz_lbx_border_color");
    jQuery("#xyz_lbx_border_color").click(function(){jQuery('#lbxbordercolorpicker').slideToggle();});
    
    jQuery('#lbxbgcolorpicker').hide();
    jQuery('#lbxbgcolorpicker').farbtastic("#xyz_lbx_bg_color");
    jQuery("#xyz_lbx_bg_color").click(function(){jQuery('#lbxbgcolorpicker').slideToggle();});
   
    
  });
  function bgchange()
  {
	  var v;
v=document.getElementById('xyz_lbx_page_option').value;
if(v==1)
{
	document.getElementById('automatic').style.display='block';
	document.getElementById('shopt').style.display='none';	
	document.getElementById('shortcode').style.display='none';		
}
if(v==2)
{
	document.getElementById('shopt').style.display='block';
	document.getElementById('shortcode').style.display='none';
	document.getElementById('automatic').style.display='none';	
}
if(v==3)

{
	document.getElementById('shortcode').style.display='block';	
	document.getElementById('shopt').style.display='none';	
	document.getElementById('automatic').style.display='none';
}
  }
  function cdcheck()
  {

	 var display_mech;
	 display_mech=document.getElementById('xyz_lbx_mode').value;
	 if(display_mech=="delay_only")
	 {
		 
		 document.getElementById('xyz_lbx_delaysec').style.display='';
		 document.getElementById('xyz_lbx_pages').style.display='none';
	 }
	 if(display_mech=="page_count_only")
	 {
		 
		 document.getElementById('xyz_lbx_delaysec').style.display='none';
		 document.getElementById('xyz_lbx_pages').style.display='';
	 }
	 if(display_mech=="both")
	 {
		 
		 document.getElementById('xyz_lbx_delaysec').style.display='';
		 document.getElementById('xyz_lbx_pages').style.display='';
	 }


  }

  function lbxdispcoord()
  {
	  var disp_position;
	 disp_position=document.getElementById('xyz_lbx_display_position').value;
	

	
if(disp_position==1)
{
	 document.getElementById('xyz_lbx_pos_left').style.display='';	
	 document.getElementById('xyz_lbx_pos_top').style.display='';
	 document.getElementById('xyz_lbx_pos_right').style.display='none';	
	 document.getElementById('xyz_lbx_pos_bottom').style.display='none';
}

if(disp_position==2)
{
	 document.getElementById('xyz_lbx_pos_left').style.display='none';	
	 document.getElementById('xyz_lbx_pos_top').style.display='';
	 document.getElementById('xyz_lbx_pos_right').style.display='';	
	 document.getElementById('xyz_lbx_pos_bottom').style.display='none';
}
if(disp_position==3)
{
	 document.getElementById('xyz_lbx_pos_left').style.display='';	
	 document.getElementById('xyz_lbx_pos_top').style.display='none';
	 document.getElementById('xyz_lbx_pos_right').style.display='none';	
	 document.getElementById('xyz_lbx_pos_bottom').style.display='';
}
if(disp_position==4)
{
	 document.getElementById('xyz_lbx_pos_left').style.display='none';	
	 document.getElementById('xyz_lbx_pos_top').style.display='none';
	 document.getElementById('xyz_lbx_pos_right').style.display='';	
	 document.getElementById('xyz_lbx_pos_bottom').style.display='';
}



	
  }
  function lbxdisplaydef()
  {
	  var disp_positioning;
	 disp_positioning=document.getElementById('xyz_lbx_positioning').value;
		
		 
			 document.getElementById('xyz_lbx_position').style.display='';
			 if(disp_positioning==1)
			 {
				 document.getElementById('xyz_lbx_predefined').style.display='none';
				 document.getElementById('xyz_lbx_display_pos').style.display=''; 
				 lbxdispcoord();
				 
			 }
			 else

			 {	

				 document.getElementById('xyz_lbx_predefined').style.display='';
				 document.getElementById('xyz_lbx_display_pos').style.display='none';
				 
				 document.getElementById('xyz_lbx_pos_left').style.display='none';	
				 document.getElementById('xyz_lbx_pos_top').style.display='none';
				 document.getElementById('xyz_lbx_pos_right').style.display='none';	
				 document.getElementById('xyz_lbx_pos_bottom').style.display='none';
				
				 document.getElementById('xyz_lbx_pos_width').style.display='';	
				 document.getElementById('xyz_lbx_pos_height').style.display='';
				
		 }
 
  }
</script>
<div class="tblight">
<?php  $xyz_lbx_top_dim=get_option('xyz_lbx_top_dim');
$xyz_lbx_left_dim=get_option('xyz_lbx_left_dim');
$xyz_lbx_right_dim=get_option('xyz_lbx_right_dim');
$xyz_lbx_bottom_dim=get_option('xyz_lbx_bottom_dim');
$xyz_lbx_height_dim=get_option('xyz_lbx_height_dim');
$xyz_lbx_width_dim=get_option('xyz_lbx_width_dim');
$xyz_lbx_close_button_option=get_option('xyz_lbx_close_button_option');
$xyz_lbx_cookie_resett=get_option('xyz_lbx_cookie_resett');
$xyz_lbx_iframe=get_option('xyz_lbx_iframe');

$xyz_lbx_display_position=get_option('xyz_lbx_display_position');
$xyz_lbx_positioning=get_option('xyz_lbx_positioning');
$xyz_lbx_position_predefined=get_option('xyz_lbx_position_predefined');

?>
<h2>Lightbox Settings</h2>
<form method="post" >
<?php wp_nonce_field( 'add-setting' );
 
$xyz_lbx_showing_option=get_option('xyz_lbx_showing_option');
$xyz_lbx_sh_arr=explode(",", $xyz_lbx_showing_option);
?>
<table  class="widefat" style="width:98%">
<tr valign="top" >
<td  scope="row" style="width: 50%" ><h3>  Content</h3></td>
<td></td>
</tr>

<tr valign="top" class="disable">
<td scope="row" colspan="1"><label>Show referrer URL based messages? </label></td><td>
<select class="xyz_lbx_width">
<option class="disable_option" >Yes </option>
<option class="disable_option" >No </option>
</select>
</td>
</tr>

<tr valign="top" >
<td colspan="2" >
<?php wp_editor(get_option('xyz_lbx_html'),'xyz_lbx_html');?></td>
</tr>

<tr valign="top" id="xyz_lbx_pos"><td scope="row" colspan="2"><h3> Position Settings</h3></td></tr>
<tr valign="top" id="xyz_lbx_position">
<td scope="row"><label for="xyz_lbx_positioning">Positioning</label></td>
<td><select  name="xyz_lbx_positioning"  class="xyz_lbx_width"  id="xyz_lbx_positioning"  onchange="lbxdisplaydef()">
<option value ="1" <?php if($xyz_lbx_positioning=='1') echo 'selected'; ?>>Manually edit</option>
<option value ="2" <?php if($xyz_lbx_positioning=='2') echo 'selected'; ?>>Predefined settings</option>

</select></td>
</tr>
<tr valign="top" id="xyz_lbx_display_pos">

<td scope="row" colspan="1"><label for="xyz_lbx_display_position"> Display Position </label></td><td>


<select name="xyz_lbx_display_position" id="xyz_lbx_display_position"  onchange="lbxdispcoord()" class="xyz_lbx_width">

<option value ="1" <?php if($xyz_lbx_display_position=='1') echo 'selected'; ?> >From  Top Left</option>

<option value ="2" <?php if($xyz_lbx_display_position=='2') echo 'selected'; ?> >From Top Right </option>
<option value ="3" <?php if($xyz_lbx_display_position=='3') echo 'selected'; ?> >From Bottom Left</option>
<option value ="4" <?php if($xyz_lbx_display_position=='4') echo 'selected'; ?> >From Bottom Right</option>
</select>
</td>

</tr>
<tr valign="top" id="xyz_lbx_predefined">

<td scope="row" colspan="1"><label for="xyz_lbx_position_predefined">Choose a predefined position </label>	</td><td>


<select name="xyz_lbx_position_predefined" id="xyz_lbx_position_predefined" class="xyz_lbx_width" >

<option value ="1" <?php if($xyz_lbx_position_predefined=='1') echo 'selected'; ?> >On top left corner </option>

<option value ="2" <?php if($xyz_lbx_position_predefined=='2') echo 'selected'; ?> >On left center </option>
<option value ="3" <?php if($xyz_lbx_position_predefined=='3') echo 'selected'; ?> >On bottom left cornor</option>


<option value ="4" <?php if($xyz_lbx_position_predefined=='4') echo 'selected'; ?> >On bottom center </option>

<option value ="5" <?php if($xyz_lbx_position_predefined=='5') echo 'selected'; ?> >On bottom right corner </option>
<option value ="6" <?php if($xyz_lbx_position_predefined=='6') echo 'selected'; ?> >On right center</option>
<option value ="7" <?php if($xyz_lbx_position_predefined=='7') echo 'selected'; ?> >On top right corner </option>

<option value ="8" <?php if($xyz_lbx_position_predefined=='8') echo 'selected'; ?> >On top center </option>
<option value ="9" <?php if($xyz_lbx_position_predefined=='9') echo 'selected'; ?> >Absolute center</option>
</select>
</td>

</tr>
<tr valign="top" id="xyz_lbx_pos_top">
<td scope="row"><label for="xyz_lbx_top"> Top coordinate</label></td>
<td><input name="xyz_lbx_top" type="text" id="xyz_lbx_top" class="xyz_lbx_width" value="<?php print(get_option('xyz_lbx_top')); ?>" size="40" /><select  name="xyz_lbx_top_dim"   id="xyz_lbx_top_dim" class="xyz_lbx_width" >
<option value ="px" <?php if($xyz_lbx_top_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_lbx_top_dim=='%') echo 'selected'; ?>>%</option>

</select></td>
</tr>
<tr valign="top" id="xyz_lbx_pos_left">
<td scope="row"><label for="xyz_lbx_left"> Left coordinate</label></td>
<td><input name="xyz_lbx_left" type="text" id="xyz_lbx_left"  value="<?php print(get_option('xyz_lbx_left')); ?>" size="40" class="xyz_lbx_width" /><select  name="xyz_lbx_left_dim" class="xyz_lbx_width"  id="xyz_lbx_left_dim" >
<option value ="px" <?php if($xyz_lbx_left_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_lbx_left_dim=='%') echo 'selected'; ?>>%</option>

</select></td>
</tr>

<tr valign="top" id="xyz_lbx_pos_right">
<td scope="row"><label for="xyz_lbx_right"> Right coordinate</label></td>
<td><input name="xyz_lbx_right" type="text" id="xyz_lbx_right" class="xyz_lbx_width" value="<?php print(get_option('xyz_lbx_right')); ?>" size="40" /><select  name="xyz_lbx_right_dim"   id="xyz_lbx_right_dim" class="xyz_lbx_width">
<option value ="px" <?php if($xyz_lbx_right_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_lbx_right_dim=='%') echo 'selected'; ?>>%</option>

</select></td>
</tr>
<tr valign="top" id="xyz_lbx_pos_bottom">
<td scope="row"><label for="xyz_lbx_bottom"> Bottom coordinate</label></td>
<td><input name="xyz_lbx_bottom" type="text" id="xyz_lbx_bottom" class="xyz_lbx_width" value="<?php print(get_option('xyz_lbx_bottom')); ?>" size="40" /><select  name="xyz_lbx_bottom_dim" class="xyz_lbx_width"  id="xyz_lbx_bottom_dim" >
<option value ="px" <?php if($xyz_lbx_bottom_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_lbx_bottom_dim=='%') echo 'selected'; ?>>%</option>

</select></td>
</tr>
<tr valign="top" id="xyz_lbx_pos_width">
<td scope="row"><label for="xyz_lbx_width"> Width</label></td>
<td><input name="xyz_lbx_width" type="text" id="xyz_lbx_width" class="xyz_lbx_width" value="<?php print(get_option('xyz_lbx_width')); ?>" size="40" /><select  name="xyz_lbx_width_dim"  class="xyz_lbx_width"  id="xyz_lbx_width_dim" >
<option value ="px" <?php if($xyz_lbx_width_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_lbx_width_dim=='%') echo 'selected'; ?>>%</option>

</select>
</td>
</tr>
<tr valign="top" id="xyz_lbx_pos_height">
<td scope="row"><label for="xyz_lbx_height"> Height</label></td>
<td><input name="xyz_lbx_height" type="text" id="xyz_lbx_height"  value="<?php print(get_option('xyz_lbx_height')); ?>" size="40" class="xyz_lbx_width" /><select  name="xyz_lbx_height_dim"   id="xyz_lbx_height_dim"  class="xyz_lbx_width">
<option value ="px" <?php if($xyz_lbx_height_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_lbx_height_dim=='%') echo 'selected'; ?>>%</option>

</select></td>
</tr>
<tr class="disable" valign="top"><td scope="row" colspan="2" ><h3> Effect settings</h3></td></tr>

<tr class="disable" valign="top">
<td scope="row"><label> Fade In And Fade Out Effect</label></td>
<td>
<div>
<input class="checked_false" type="radio"><label>Yes</label>
<input class="checked_false" type="radio"><label>No</label>
<br>
</div>
</td>
</tr>

<?php
$xyz_lbx_mode=get_option('xyz_lbx_mode');
$xyz_lbx_page_option=get_option('xyz_lbx_page_option');
$xyz_lbx_repeat_interval_timing=get_option('xyz_lbx_repeat_interval_timing');
$xyz_lbx_display_user=get_option('xyz_lbx_display_user');
?>
<tr valign="top"><td scope="row" colspan="2"><h3> Display Logic Settings</h3></td></tr>

<tr valign="top" class="disable" >
<td scope="row"><label>Display control cookie name </label></td>
<td><input readonly type="text" class="xyz_lbx_width"/>
</td>
</tr>

<tr valign="top">
<td scope="row"><label for="xyz_lbx_mode"> Display logic </label></td>
<td><select  name="xyz_lbx_mode"   id="xyz_lbx_mode"  onchange="cdcheck()" class="xyz_lbx_width">
<option value ="delay_only" <?php if($xyz_lbx_mode=='delay_only') echo 'selected'; ?>>Based on delay </option>
<option value ="page_count_only" <?php if($xyz_lbx_mode=='page_count_only') echo 'selected'; ?>>Based on  number of pages browsed </option>
<option value ="both" <?php if($xyz_lbx_mode=='both') echo 'selected'; ?>>Based on both </option>
</select></td>
</tr>
<tr valign="top" id="xyz_lbx_delaysec">
<td scope="row"><label for="xyz_lbx_delay"> Delay in seconds before popup appears </label></td>
<td><input name="xyz_lbx_delay" type="text" id="xyz_lbx_delay"  class="xyz_lbx_width" value="<?php print(get_option('xyz_lbx_delay')); ?>" size="40" /> sec</td>
</tr>

<tr valign="top" id="xyz_lbx_pages">
<td scope="row"><label for="xyz_lbx_page_count">Number of pages to browse before popup appears</label></td>
<td><input name="xyz_lbx_page_count" type="text" id="xyz_lbx_page_count" class="xyz_lbx_width"  value="<?php print(get_option('xyz_lbx_page_count')); ?>" size="40" /> pages</td>
</tr>
<tr valign="top">
<td scope="row"><label for="xyz_lbx_repeat_interval"> Repeat interval for a user </label></td>
<td ><input name="xyz_lbx_repeat_interval" type="text" id="xyz_lbx_repeat_interval" class="xyz_lbx_width"  value="<?php print(get_option('xyz_lbx_repeat_interval')); ?>" size="40" /> 

<select name="xyz_lbx_repeat_interval_timing" id="xyz_lbx_repeat_interval_timing" class="xyz_lbx_width" >
<option value ="1" <?php if($xyz_lbx_repeat_interval_timing=='1') echo 'selected'; ?> >Hrs </option>

<option value ="2" <?php if($xyz_lbx_repeat_interval_timing=='2') echo 'selected'; ?> >Minutes </option>
</select>
</td>

</tr>
<tr class="disable" valign="top" >
<td scope="row"><label>Maximum number of times that the popup should display (0 for unlimited display)</label></td>
<td><input readonly type="text" class="xyz_lbx_width"/> </td>
</tr>

<tr class="disable" valign="top">
<td scope="row"><label>Reset counter ?(Currently popup has shown 'X' times)</label></td>
<td><input class="checked_false" type="checkbox"/>
</td>
</tr>

<tr valign="top" >
<td scope="row"><label for="lbx_display_user">Do not show popup for logged in users ?</label></td>
<td><select name="xyz_lbx_display_user" id="xyz_lbx_display_user" class="xyz_lbx_width">
<option value ="1" <?php if($xyz_lbx_display_user=='1') echo 'selected'; ?>>Yes</option>
<option value ="0" <?php if($xyz_lbx_display_user=='0') echo 'selected'; ?>>No</option>
</select></td>
</tr>

<tr class="disable" valign="top" >
<td scope="row"><label>Should popup expire ?</label></td>
<td>
<div>
<input class="checked_false" type="radio"/><label>Yes</label>
<input class="checked_false" type="radio"/><label>No</label>
</div></td>
</tr>

<tr class="disable" valign="top">
<td scope="row"><label>Display trigger </label></td>
<td>
<select>
<option class="disable_option" >On load</option>
<option class="disable_option" >On click</option>
</select></td>
</tr>
<tr valign="top">
<td scope="row" colspan="1"><label for="xyz_lbx_bgimage_option">Display as iframe </label></td><td>
<select name="xyz_lbx_iframe" id="xyz_lbx_iframe" class="xyz_lbx_width" >
<option value ="1" <?php if($xyz_lbx_iframe=='1') echo 'selected'; ?> >Yes </option>
<option value ="0" <?php if($xyz_lbx_iframe=='0') echo 'selected'; ?> >No </option>
</select>
</td>
</tr>

<tr class="disable" valign="top" >
<td scope="row" colspan="1"><label> If content is larger than window?   </label></td>
<td><select class="xyz_lbx_width">
<option class="disable_option" >Hide content </option>
<option class="disable_option" >Show scrollbar </option>
</select>
</td>
</tr>
<tr valign="top" class="disable">
<td scope="row" colspan="1"><label>Target display devices</label></td>
<td><select class="xyz_lbx_width">
<option class="disable_option" >Desktop only</option>
<option class="disable_option" >Both desktop and mobile </option>
</select>
</td>
</tr>

<tr valign="top" id="xyz_lbx_close">
<td scope="row" colspan="1"><label for="xyz_lbx_bgimage_option"> Close button option </label></td><td>
<select name="xyz_lbx_close_button_option" id="xyz_lbx_close_button_option"  class="xyz_lbx_width" >
<option value ="1" <?php if($xyz_lbx_close_button_option=='1') echo 'selected'; ?> >Yes </option>
<option value ="0" <?php if($xyz_lbx_close_button_option=='0') echo 'selected'; ?> >No </option>
</select>
</td>
</tr>

<tr valign="top" class="disable"><td scope="row" colspan="2"><h3> Popup Closing settings</h3></td></tr>
<tr valign="top" class="disable">
<td scope="row" colspan="1"><label> Close mode </label></td><td>
<select class="xyz_lbx_width">
<option class="disable_option" >When user  clicks overlay </option>
<option class="disable_option" >When user clicks close button</option>
</select>
</td>
</tr>

<tr valign="top" class="disable">
<td scope="row" colspan="1"><label> Auto close after timeout </label></td><td>
<select class="xyz_lbx_width">
<option class="disable_option" >No </option>
<option class="disable_option" >Yes </option>
</select>
</td>
</tr>

<tr valign="top" class="disable">
<td scope="row"><label>Don't show again element (Must be id of  element to indicate don't show again in  html content, not applicable in case of iframe display)</label></td>
<td><input readonly type="text" class="xyz_lbx_width"/> eg : #dontshow </td>
</tr>
<tr valign="top" class="disable">
<td scope="row"><label>Don't show again time period (in days)</label></td>
<td><input readonly type="text" class="xyz_lbx_width" />  </td>
</tr>

<tr valign="top" class="disable"><td scope="row" colspan="2"><h3> Javascript Callback Settings</h3></td></tr>
<tr valign="top" class="disable">
<td scope="row"><label>Callback on popup display</label></td>
<td><textarea readonly ></textarea> </td>
</tr>
<tr valign="top" class="disable">
<td scope="row"><label>Callback on popup hide</label></td>
<td><textarea readonly ></textarea> </td>
</tr>

<tr valign="top"><td scope="row" colspan="2"><h3> Style Settings</h3></td></tr>
<tr valign="top">
<td scope="row"><label for="xyz_lbx_z_index"> Z index</label></td>
<td><input name="xyz_lbx_z_index" type="text" id="xyz_lbx_z_index" class="xyz_lbx_width"  value="<?php print(get_option('xyz_lbx_z_index')); ?>" size="40" /> </td>
</tr>

<tr valign="top" class="disable">
<td scope="row" colspan="1"><label> Overlay image option </label></td>
<td><select>
<option class="disable_option" >Yes </option>
<option class="disable_option" >No </option>
</select>
</td>
</tr>
<tr valign="top" id="xyz_lbx_overopa">
<td scope="row"><label for="xyz_lbx_opacity"> Overlay opacity(0-100)</label></td>
<td><input name="xyz_lbx_opacity" type="text" id="xyz_lbx_opacity"  class="xyz_lbx_width" value="<?php print(get_option('xyz_lbx_opacity')); ?>" size="40" /> </td>
</tr>
<tr valign="top" id="xyz_lbx_overcl">
<td scope="row"><label for="xyz_lbx_color"> Overlay color</label></td>
<td><input name="xyz_lbx_color" type="text" id="xyz_lbx_color" class="xyz_lbx_width"  value="<?php print(get_option('xyz_lbx_color')); ?>" size="40" /> <div id="lbxcolorpicker"></div> </td>
</tr>
<tr valign="top" class="disable">
<td scope="row" colspan="1"><label> Background image option </label></td>
<td><select>
<option class="disable_option" >Yes </option>
<option class="disable_option" >No </option>
</select>
</td>
</tr>

<tr valign="top" id="lbx_backgrnd_overopa">
<td scope="row"><label for="lbx_background_opacity"> Background opacity(10-100)</label></td>
<td><input name="xyz_lbx_bg_opacity" type="text" id="xyz_lbx_bg_opacity" class="xyz_lbx_width" value="<?php print(get_option('xyz_lbx_bg_opacity')); ?>" size="40" /> </td>
</tr>

<tr valign="top" >
<td scope="row"><label for="xyz_lbx_bg_color"> Background color</label></td>
<td><input name="xyz_lbx_bg_color" type="text" id="xyz_lbx_bg_color" class="xyz_lbx_width" value="<?php print(get_option('xyz_lbx_bg_color')); ?>" size="40" /> <div id="lbxbgcolorpicker"></div> </td>
</tr>
<tr valign="top">
<td scope="row"><label for="xyz_lbx_border_color"> Border color</label></td>
<td><input name="xyz_lbx_border_color" type="text" id="xyz_lbx_border_color"  class="xyz_lbx_width" value="<?php print(get_option('xyz_lbx_border_color')); ?>" size="40" /> <div id="lbxbordercolorpicker"></div> </td>
</tr>
<tr valign="top">
<td scope="row"><label for="xyz_lbx_border_width">  Border  width </label></td>
<td><input name="xyz_lbx_border_width" type="text" id="xyz_lbx_border_width" class="xyz_lbx_width"  value="<?php print(get_option('xyz_lbx_border_width')); ?>" size="40" /> 

<!-- <select  name="xyz_lbx_top_dim"   id="xyz_lbx_top_dim" >
<option value ="px" <?php //if($xyz_lbx_border_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php //if($xyz_lbx_border_dim=='%') echo 'selected'; ?>>%</option>
</select>-->
px
 </td>
</tr>

<tr valign="top" id="xyz_lbx_rad">
<td scope="row"><label for="xyz_lbx_corner_radius">  Border  radius </label></td>
<td><input name="xyz_lbx_corner_radius" type="text" id="xyz_lbx_corner_radius"   class="xyz_lbx_width" value="<?php print(get_option('xyz_lbx_corner_radius')); ?>" size="40" /> px </td>
</tr>

<tr valign="top" class="disable">
<td scope="row"><label> Border Shadow</label></td>
<td>
<div>
<input class="checked_false" type="radio"><label>Yes</label>
<input class="checked_false" type="radio"><label>No</label>
</div>
</td>
</tr>

<tr valign="top"><td scope="row" colspan="2"><h3> Placement Settings</h3></td></tr>


<tr valign="top" id="pgopt" ><td scope="row"><label for="xyz_lbx_page_option"> Placement mechanism </label></td>
<td>
<select name="xyz_lbx_page_option" id="xyz_lbx_page_option" onchange="bgchange()" class="xyz_lbx_width" >
<option value ="1" <?php if($xyz_lbx_page_option=='1') echo 'selected'; ?> >Automatic </option>
<option value ="2" <?php if($xyz_lbx_page_option=='2') echo 'selected'; ?> >Specific Pages</option>
<option value ="3" <?php if($xyz_lbx_page_option=='3') echo 'selected'; ?> >Manual </option>
</select></td></tr>


<tr valign="top" ><td scope="row" ></td><td>
<span  id="automatic" >Popup will load in all pages</span>
<span  id="shopt" >
<input name="xyz_lbx_pages" value="<?php echo $xyz_lbx_sh_arr[0];?>"<?php if($xyz_lbx_sh_arr[0]==1){?> checked="checked"<?php } ?> type="checkbox"> On Pages 
<input name="xyz_lbx_posts" value="<?php echo $xyz_lbx_sh_arr[1];?>"<?php if($xyz_lbx_sh_arr[1]==1){?> checked="checked"<?php }?>  type="checkbox"> On Posts
<input name="xyz_lbx_hp" value="<?php echo $xyz_lbx_sh_arr[2];?>"<?php if($xyz_lbx_sh_arr[2]==1){?> checked="checked"<?php }?>  type="checkbox"> On Home page 
</span>
<span  id="shortcode" >Use this short code in your pages - [xyz_lbx_default_code]</span>
</td>
</tr>



<!--  <tr valign="top" id="automatic"  style="display: none"><td scope="row" ></td><td >(Popup will load in all pages)</td>
</tr>
<tr valign="top" id="shortcode"  style="display: none"><td scope="row"></td><td>Use this short code in your pages - [xyz_lbx_default_code]</td>
</tr>-->


<tr valign="top">
<td scope="row"><label for="xyz_lcookie_resett"><h3>Reset cookies ? </h3>
 </label></td>
<td><input name="xyz_lbx_cookie_resett" type="checkbox" id="xyz_lbx_cookie_resett"   <?php  echo 'checked'; ?> /> 
(This is just for your testing purpose. If you want to see a popup  immediately after you make changes in any settings, you have to reset the cookies.)
 </td>
</tr>
<tr>
<td scope="row"> </td>
<td><br>
<input type="submit"  class="submit_lbx" value=" Update Settings " /></td>
</tr>

</table>


</form>

</div>

<div id="xyz_premium_only_info">
	<label>Only available in Premium version</label>
</div>

<script type="text/javascript">
bgchange();
cdcheck();
lbxdisplaydef();

    jQuery(".disable").click(function(e){
    	premiumClick(e);
    	jQuery(".checked_false").prop("checked", false);
    });
   
function premiumClick(e){
	var left=e.pageX-170;
	var top=e.pageY-30;
		
	jQuery("#xyz_premium_only_info").css({"left" : left + "px", "top" : top + "px"});
    jQuery("#xyz_premium_only_info").show();
    jQuery("#xyz_premium_only_info").fadeIn( "slow", function() {
	window.tooltip = setTimeout(function(){ jQuery("#xyz_premium_only_info").hide(); }, 2000);
    });
	return false;
}

</script>
<style>
#xyz_premium_only_info
{
display:none;
font-family:sans-serif;
font-size:13px;
text-align: center;
border-radius: 5px;
float: left;
background-color: rgb(51, 51, 51);
color: white; 
width: 98px; 
padding: 10px 20px;
position:absolute;
z-index:1000;
}
.disable select,.disable input,.disable input[type=radio]
{
cursor: not-allowed;
}
.disable{
	opacity:0.4;
	cursor:not-allowed;
}
.disable_option
{
display:none;
}
</style>
