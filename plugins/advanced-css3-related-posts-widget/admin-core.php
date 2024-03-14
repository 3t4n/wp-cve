<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include 'the_globals.php';
$default_thumb = '';
$rpwpluginsurl = plugins_url( '', __FILE__ );


if(isset($_POST["action"]) && ($_POST["action"] == 'update'))

{
	
	//----------------------------------------------------get the values of array options 

	$rpw_show_thumbs = $_POST["rpw_show_thumbs"]; // Display thumbs or not?

	$rpw_thumbw = $_POST["rpw_thumbw"]; // Thumbnail thumb width

	$rpw_thumbh = $_POST["rpw_thumbh"]; // Thumbnail thumb height

	$rpw_posts_limit = $_POST["rpw_posts_limit"]; // How many posts to display?

	$rpw_show_excerpt = $_POST["rpw_show_excerpt"];

	$rpw_excerpt_length = $_POST["rpw_excerpt_length"];

	$rpw_use_css3_effects = $_POST["rpw_use_css3_effects"];

	$rpw_css3_shadow = $_POST["rpw_css3_shadow"];

	$rpw_css3_thumb_radius = $_POST["rpw_css3_thumb_radius"];

if (isset($_POST["default_thumb"])) {
    $default_thumb = $_POST["default_thumb"];
}else{
	$default_thumb = '';
}
	$rpw_Style = $_POST["rpw_Style"];
	
	$rpw_text_direction = $_POST["rpw_text_direction"];
	
	$rpw_image_direction = $_POST["rpw_image_direction"];
	

	//Validation//

	if($rpw_thumbw < 40) $rpw_thumbw = 40;

	if($rpw_thumbh< 40) $rpw_thumbh= 40;

	if($rpw_excerpt_length < 8){$rpw_excerpt_length = '8';}

	if($rpw_excerpt_length > 30){$rpw_excerpt_length = '30';}

	if($rpw_show_thumbs == ''){$rpw_show_thumbs = 'Yes';}

	if($rpw_thumbw == ''){$rpw_thumbw = '40';}

	if($rpw_thumbh == ''){$rpw_thumbh = '40';}

	if($rpw_posts_limit == ''){$rpw_posts_limit = '5';}

	if($rpw_use_css3_effects == ''){$rpw_use_css3_effects = 'None';}

	if($rpw_excerpt_length == ''){$rpw_excerpt_length = '8';}
	
	if($rpw_text_direction == ''){$rpw_text_direction = 'ltr';}
	
	if($rpw_image_direction == ''){$rpw_image_direction = 'left';}

	//-----------------------------------------------------Get general options array values

if($default_thumb == ''){
	$default_thumb = $rpwpluginsurl.'/images/noimage.png';
}

$rpw_related_posts_settings = 

Array (

		'rpw_show_thumbs' => $rpw_show_thumbs, // Display thumbs or not?

		'rpw_thumbw' => $rpw_thumbw, // Thumbnail thumb width

		'rpw_thumbh' => $rpw_thumbh, // Thumbnail thumb height

		'rpw_posts_limit' => $rpw_posts_limit, // How many posts to display?

		'rpw_show_excerpt' => $rpw_show_excerpt,

		'rpw_excerpt_length' => $rpw_excerpt_length,

		'rpw_use_css3_effects' => $rpw_use_css3_effects,

		'rpw_css3_shadow' => $rpw_css3_shadow,

		'rpw_css3_thumb_radius' => $rpw_css3_thumb_radius,

		'default_thumb' => $default_thumb, // Default thumbnail thumb
		
		'rpw_Style' => $rpw_Style,
		
		'rpw_image_direction' => $rpw_image_direction,
		
		'rpw_text_direction' => $rpw_text_direction
	);

	if ($rpw_related_posts_settings != '' ) {

	    update_option( 'rpw_settings' , $rpw_related_posts_settings );

	} else {

	    $deprecated = ' ';

	    $autoload = 'no';

	    add_option( 'rpw_settings', $rpw_related_posts_settings, $deprecated, $autoload );

	}

}else //no update action

{

	$rpw_related_posts_settings = rpw_read_options();

}
?>

<style>

#rpw_admin_main {

text-align:left;

direction:ltr;

padding:10px;

margin: 10px;

background-color: #ffffff;

border:1px solid #EBDDE2;

display: relative;

overflow: auto;

}

.inner_block{

height: 370px;

display: inline;

min-width:770px;

}

#donate{

    background-color: #EEFFEE;

    border: 1px solid #66DD66;

    border-radius: 10px 10px 10px 10px;

    height: 58px;

    padding: 10px;

    margin: 15px;

    }
#rpwbox1{
    position:relative;
}
#rpwbox1:after{
       content:url(<?php echo $rpwpluginsurl; ?>/images/rpw-promo.png);
       display:block;
       position:absolute;
       top: 10px;
       right: 10px;
}
#rpwbox2{
    position:relative;
}
#rpwbox2:after{
       content:url(<?php echo $rpwpluginsurl; ?>/images/rpw-css3-promo.png);
       display:block;
       position:absolute;
       top: 10px;
       right: 10px;
}
</style>
<div id="rpw_admin_main">
<form name="rpwform" method="POST">
<script type="text/javascript">
function change_style_options(rpw_Style)
{
		var rpw_show_thumbs,rpw_thumbw,rpw_thumbh,rpw_posts_limit,rpw_show_excerpt,rpw_excerpt_length,rpw_use_css3_effects,rpw_css3_shadow,rpw_css3_thumb_radius,rpw_image_direction,rpw_text_direction;
	if(rpw_Style == 'Thumbs_Left'){
		rpw_show_thumbs = 'Yes';
		rpw_thumbw = '40';
		rpw_thumbh = '40';
		rpw_posts_limit = '7';
		rpw_show_excerpt = 'Yes';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'No';
		rpw_css3_shadow = 'None';
		rpw_css3_thumb_radius = 'None';
		rpw_image_direction = 'left';
		rpw_text_direction = 'ltr';
	}
	if(rpw_Style == 'Thumbs_Right'){
		rpw_show_thumbs = 'Yes';
		rpw_thumbw = '40';
		rpw_thumbh = '40';
		rpw_posts_limit = '7';
		rpw_show_excerpt = 'Yes';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'No';
		rpw_css3_shadow = 'None';
		rpw_css3_thumb_radius = 'None';
		rpw_image_direction = 'right';
		rpw_text_direction = 'rtl';
	}
	if(rpw_Style == 'Big_Thumbs'){
		rpw_show_thumbs = 'Yes';
		rpw_thumbw = '170';
		rpw_thumbh = '110';
		rpw_posts_limit = '7';
		rpw_show_excerpt = 'No';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'No';
		rpw_css3_shadow = 'None';
		rpw_css3_thumb_radius = 'None';
		rpw_image_direction = 'center';
		rpw_text_direction = 'center';
	}
	if(rpw_Style == 'Wide_Thumbs'){
		rpw_show_thumbs = 'Yes';
		rpw_thumbw = '230';
		rpw_thumbh = '70';
		rpw_posts_limit = '7';
		rpw_show_excerpt = 'No';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'No';
		rpw_css3_shadow = 'None';
		rpw_css3_thumb_radius = 'None';
		rpw_image_direction = 'center';
		rpw_text_direction = 'center';
	}
	if(rpw_Style == 'No_Thumbs'){
		rpw_show_thumbs = 'No';
		rpw_thumbw = '40';
		rpw_thumbh = '40';
		rpw_posts_limit = '7';
		rpw_show_excerpt = 'Yes';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'No';
		rpw_css3_shadow = 'None';
		rpw_css3_thumb_radius = 'None';
		rpw_image_direction = 'left';
		rpw_text_direction = 'ltr';
	}
	if(rpw_Style == 'Just_Thumbs'){
		rpw_show_thumbs = 'Yes';
		rpw_thumbw = '40';
		rpw_thumbh = '40';
		rpw_posts_limit = '9';
		rpw_show_excerpt = 'No';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'No';
		rpw_css3_shadow = 'None';
		rpw_css3_thumb_radius = 'None';
		rpw_image_direction = 'left';
		rpw_text_direction = 'center';
	}
		if(rpw_Style == 'CSS-Thumbs_Left'){
		rpw_show_thumbs = 'Yes';
		rpw_thumbw = '40';
		rpw_thumbh = '40';
		rpw_posts_limit = '7';
		rpw_show_excerpt = 'Yes';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'Yes';
		rpw_css3_shadow = 'None';
		rpw_css3_thumb_radius = '45';
		rpw_image_direction = 'left';
		rpw_text_direction = 'ltr';
	}
	if(rpw_Style == 'CSS-Thumbs_Right'){
		rpw_show_thumbs = 'Yes';
		rpw_thumbw = '40';
		rpw_thumbh = '40';
		rpw_posts_limit = '7';
		rpw_show_excerpt = 'Yes';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'Yes';
		rpw_css3_shadow = 'None';
		rpw_css3_thumb_radius = '45';
		rpw_image_direction = 'right';
		rpw_text_direction = 'rtl';
	}
	if(rpw_Style == 'CSS-Big_Thumbs'){
		rpw_show_thumbs = 'Yes';
		rpw_thumbw = '170';
		rpw_thumbh = '110';
		rpw_posts_limit = '7';
		rpw_show_excerpt = 'No';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'Yes';
		rpw_css3_shadow = '10';
		rpw_css3_thumb_radius = '10';
		rpw_image_direction = 'center';
		rpw_text_direction = 'center';
	}
	if(rpw_Style == 'CSS-Wide_Thumbs'){
		rpw_show_thumbs = 'Yes';
		rpw_thumbw = '230';
		rpw_thumbh = '70';
		rpw_posts_limit = '7';
		rpw_show_excerpt = 'No';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'Yes';
		rpw_css3_shadow = '10';
		rpw_css3_thumb_radius = '10';
		rpw_image_direction = 'center';
		rpw_text_direction = 'center';
	}
	if(rpw_Style == 'CSS-No_Thumbs'){
		rpw_show_thumbs = 'No';
		rpw_thumbw = '40';
		rpw_thumbh = '40';
		rpw_posts_limit = '7';
		rpw_show_excerpt = 'Yes';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'No';
		rpw_css3_shadow = 'None';
		rpw_css3_thumb_radius = 'None';
		rpw_image_direction = 'left';
		rpw_text_direction = 'ltr';
	}
	if(rpw_Style == 'CSS-Just_Thumbs'){
		rpw_show_thumbs = 'Yes';
		rpw_thumbw = '40';
		rpw_thumbh = '40';
		rpw_posts_limit = '9';
		rpw_show_excerpt = 'No';
		rpw_excerpt_length = '14';
		rpw_use_css3_effects = 'Yes';
		rpw_css3_shadow = '10';
		rpw_css3_thumb_radius = '45';
		rpw_image_direction = 'left';
		rpw_text_direction = 'center';
	}
	document.rpwform.rpw_show_thumbs.value = rpw_show_thumbs;
	document.rpwform.rpw_thumbw.value = rpw_thumbw;
	document.rpwform.rpw_thumbh.value = rpw_thumbh;
	document.rpwform.rpw_posts_limit.value = rpw_posts_limit;
	document.rpwform.rpw_show_excerpt.value = rpw_show_excerpt;
	document.rpwform.rpw_excerpt_length.value = rpw_excerpt_length;
	document.rpwform.rpw_use_css3_effects.value = rpw_use_css3_effects;
	document.rpwform.rpw_css3_shadow.value = rpw_css3_shadow;
	document.rpwform.rpw_css3_thumb_radius.value = rpw_css3_thumb_radius;
	document.rpwform.rpw_image_direction.value = rpw_image_direction;
	document.rpwform.rpw_text_direction .value = rpw_text_direction;
}
</script>

<input type="hidden" value="update" name="action">
<div class="">
	<h2>Related Posts Widget Options (<font color="#008000">premium</font>):</h2>
</div>
<div class="simpleTabs">
<ul class="simpleTabsNavigation">
    <li><a href="#">Classic layouts</a></li>
	<li><a href="#">Modern layouts</a></li>
    <li><a href="#">Advanced Options</a></li>
    <li><a href="#">About</a></li>
</ul>
<div class="simpleTabsContent" style="height: 401px; border: 1px solid #E9E9E9; padding: 4px">
<div id="rpwbox1">
	&nbsp;<table border="0" width="40%">
		<tr>
<td align="center"><?php
if (isset($rpw_related_posts_settings['rpw_Style'])) {
    $rpw_Style = $rpw_related_posts_settings['rpw_Style'];
    // Use $rpw_Style here
} else {
    // Handle the case where 'rpw_Style' is not defined in the array
    $rpw_Style = 'default'; // Provide a default value or handle it accordingly
}	 ?>
		<?php $checkvalue = ''; if($rpw_Style == 'Thumbs_Left'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('Thumbs_Left');" type="radio" name="rpw_Style" value="Thumbs_Left" <?php echo $checkvalue ?>>Thumbs Left</td>
			<td align="center">
			<?php $checkvalue = ''; if($rpw_Style == 'Thumbs_Right'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('Thumbs_Right');" type="radio" name="rpw_Style" value="Thumbs_Right" <?php echo $checkvalue ?>>Thumbs Right</td>
			<td align="center">
			<?php $checkvalue = ''; if($rpw_Style == 'Big_Thumbs'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('Big_Thumbs');" type="radio" name="rpw_Style" value="Big_Thumbs" <?php echo $checkvalue ?>>Big Thumbs</td>
		</tr>
		<tr>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/thumbsleft.png" width="50" height="86"></td>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/thmbsright.png" width="50" height="86"></td>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/bigthumbs.png" width="49" height="109"></td>
		</tr>
		<tr>
			<td align="center">
			&nbsp;</td>
			<td align="center">
			&nbsp;</td>
			<td align="center">
			&nbsp;</td>
		</tr>
		<tr>
			<td align="center">
			<?php $checkvalue = ''; if($rpw_Style == 'Wide_Thumbs'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('Wide_Thumbs');" type="radio" name="rpw_Style" value="Wide_Thumbs" <?php echo $checkvalue ?>>Wide Thumbs</td>
			<td align="center">
			<?php $checkvalue = ''; if($rpw_Style == 'No_Thumbs'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('No_Thumbs');" type="radio" name="rpw_Style" value="No_Thumbs" <?php echo $checkvalue ?>>No Thumbs</td>
			<td align="center">
			<?php $checkvalue = ''; if($rpw_Style == 'Just_Thumbs'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('Just_Thumbs');" type="radio" name="rpw_Style" value="Just_Thumbs" <?php echo $checkvalue ?>>Just Thumbs</td>
		</tr>
		<tr>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/widethumbs.png" width="48" height="88"></td>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/justdetails.png" width="49" height="54"></td>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/thumblist.png" width="25" height="109"></td>
		</tr>
		<tr>
			<td align="center">
			&nbsp;</td>
			<td align="center">
			&nbsp;</td>
			<td align="center">
			&nbsp;</td>
		</tr>
	</table>
	</div>
</div>
<div class="simpleTabsContent" style="height: 401px; border: 1px solid #E9E9E9; padding: 4px">
<div id="rpwbox2">
	&nbsp;<table border="0" width="40%">
		<tr>
			<td align="center"><?php
if (isset($rpw_related_posts_settings['rpw_Style'])) {
    $rpw_Style = $rpw_related_posts_settings['rpw_Style'];
    // Use $rpw_Style here
} else {
    // Handle the case where 'rpw_Style' is not defined in the array
    $rpw_Style = 'default'; // Provide a default value or handle it accordingly
} ?>
			<?php $checkvalue = ''; if($rpw_Style == 'CSS-Thumbs_Left'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('CSS-Thumbs_Left');" type="radio" name="rpw_Style" value="CSS-Thumbs_Left" <?php echo $checkvalue ?>>CSS3 Thumbs Left</td>
			<td align="center">
			<?php $checkvalue = ''; if($rpw_Style == 'CSS-Thumbs_Right'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('CSS-Thumbs_Right');" type="radio" name="rpw_Style" value="CSS-Thumbs_Right" <?php echo $checkvalue ?>>CSS3 Thumbs Right</td>
			<td align="center">
			<?php $checkvalue = ''; if($rpw_Style == 'CSS-Big_Thumbs'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('CSS-Big_Thumbs');" type="radio" name="rpw_Style" value="CSS-Big_Thumbs" <?php echo $checkvalue ?>>CSS3 Big Thumbs</td>
		</tr>
		<tr>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/css3-thumbsleft.png" width="50" height="86"></td>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/css3-thmbsright.png" width="50" height="86"></td>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/css3-bigthumbs.png" width="49" height="109"></td>
		</tr>
		<tr>
			<td align="center">
			&nbsp;</td>
			<td align="center">
			&nbsp;</td>
			<td align="center">
			&nbsp;</td>
		</tr>
		<tr>
			<td align="center">
			<?php $checkvalue = ''; if($rpw_Style == 'CSS-Wide_Thumbs'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('CSS-Wide_Thumbs');" type="radio" name="rpw_Style" value="CSS-Wide_Thumbs" <?php echo $checkvalue ?>>CSS3 Wide Thumbs</td>
			<td align="center">
			<?php $checkvalue = ''; if($rpw_Style == 'CSS-No_Thumbs'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('CSS-No_Thumbs');" type="radio" name="rpw_Style" value="CSS-No_Thumbs" <?php echo $checkvalue ?>>CSS3 No Thumbs</td>
			<td align="center">
			<?php $checkvalue = ''; if($rpw_Style == 'CSS-Just_Thumbs'){ $checkvalue = 'checked';}?>
			<input onclick="change_style_options('CSS-Just_Thumbs');" type="radio" name="rpw_Style" value="CSS-Just_Thumbs" <?php echo $checkvalue ?>>CSS3 Just Thumbs</td>
		</tr>
		<tr>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/css3-widethumbs.png" width="48" height="88"></td>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/css3-justdetails.png" width="49" height="54"></td>
			<td align="center">
			<img border="0" src="<?php echo $rpwpluginsurl; ?>/images/css3-thumblist.png" width="25" height="109"></td>
		</tr>
		<tr>
			<td align="center">
			&nbsp;</td>
			<td align="center">
			&nbsp;</td>
			<td align="center">
			&nbsp;</td>
		</tr>
	</table>
	</div>
</div>
<div class="simpleTabsContent" style="height: 467px; border: 1px solid #E9E9E9; padding: 4px" id="layer1">

		<table border="0" width="480" height="461" cellspacing="0" cellpadding="0">

			<tr>

				<td width="480" colspan="4" height="45">
	<h2>Related Posts Widget Options (<font color="#008000">premium</font>): </h2></td>

			</tr>

			<tr>

				<td width="480" colspan="4"><font color="#C47500"><b>This options help you to make the best 

	view.</b></font></td>

			</tr>

			<tr>

				<td width="177" colspan="2">Show thumbnails</td>

				<td width="178"><select size="1" name="rpw_show_thumbs">

				<?php 

				if ($rpw_related_posts_settings['rpw_show_thumbs'] == 'Yes')

					{

						echo '<option selected>Yes</option>';

						echo '<option>No</option>';

					}

					else

					{

						echo '<option>Yes</option>';

						echo '<option selected>No</option>';

					}

				?>

				</select></td>

				<td width="125">&nbsp;</td>

			</tr>

			<tr>

				<td width="113">Thumbnail width</td>

				<td title="Best Width For this Template" width="64"><?php echo "<img title='Best Width For this Template' src='$rpwpluginsurl/images/what.gif' align='center' />";?></td>

				<td width="178">

				<input type="text" name="rpw_thumbw" size="12" value="<?php echo $rpw_related_posts_settings['rpw_thumbw']; ?>"> 
				px</td>

				<td width="125">

				<font color="#008000">Best value: 40</font></td>

			</tr>

			<tr>

				<td width="113">Thumbnail height</td>

				<td title="Best height For this Template" width="64"><?php echo "<img title='Best height For this Template' src='$rpwpluginsurl/images/what.gif' align='center' />";?></td>

				<td width="178">

				<input type="text" name="rpw_thumbh" size="12" value="<?php echo $rpw_related_posts_settings['rpw_thumbh']; ?>"> 
				px</td>

				<td width="125">

				<font color="#008000">Best value: 40</font></td>

			</tr>

			<tr>

				<td width="177" colspan="2">Posts limit</td>

				<td width="178"><select size="1" name="rpw_posts_limit">

				<?php for($i=1;$i<=9;$i++)

				{

					if ($i == $rpw_related_posts_settings['rpw_posts_limit'])

						echo '<option selected>'. $i .'</option>';

					else

						echo '<option>'. $i .'</option>';

				}

				?>

				</select></td>

				<td width="125">

				<font color="#008000">Default: 7</font></td>

			</tr>

			<tr>

				<td width="177" colspan="2">Show Excerpt</td>

				<td width="178"><select size="1" name="rpw_show_excerpt">
				<?php

				$choice = '';

				$rpw_show_excerpt_temp = $rpw_related_posts_settings['rpw_show_excerpt']; ?>

				<?php if ($rpw_show_excerpt_temp == 'Yes'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="Yes">Yes</option>

				<?php if ($rpw_show_excerpt_temp == 'No'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="No">No</option>

				</select></td>

				<td width="125">

				&nbsp;</td>

			</tr>

			<tr>

				<td width="177" colspan="2">Excerpt length in words</td>

				<td width="178">

				<input type="text" name="rpw_excerpt_length" size="12" value="<?php echo $rpw_related_posts_settings['rpw_excerpt_length']; ?>">words</td>

				<td width="125">

				<font color="#008000">Best value: 14</font></td>

			</tr>

			<tr>

				<td width="177" colspan="2">Use CSS3 Effects</td>

				<td width="178">

				<select size="1" name="rpw_use_css3_effects">
				<option selected value="Yes">Yes</option>
				<option value="No">No</option>
				</select></td>

				<td width="125">

				&nbsp;</td>

			</tr>

			<tr>

				<td width="177" colspan="2">CSS3 (shadow) effect</td>

				<td width="178"><select size="1" name="rpw_css3_shadow">

				<?php 

				$choice = '';

				$css3_temp = $rpw_related_posts_settings['rpw_css3_shadow']; ?>

				<?php if ($css3_temp == 'None'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="None">None</option>

				<?php if ($css3_temp == '5'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="5">shadow 5px small</option>

				<?php if ($css3_temp == '10'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="10">shadow 10px medium</option>

				<?php if ($css3_temp == '15'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="15">shadow 15px big</option>

				</select></td>

				<td width="125">

				&nbsp;</td>

			</tr>

			<tr>

				<td width="177" colspan="2">CSS3 (radius) effect</td>

				<td width="178"><select size="1" name="rpw_css3_thumb_radius">

				<?php

				$choice = '';

				$css3_temp = $rpw_related_posts_settings['rpw_css3_thumb_radius']; ?>

				<?php if ($css3_temp == 'None'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="None">None</option>

				<?php if ($css3_temp == '10'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="10">small radius 10px</option>

				<?php if ($css3_temp == '20'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="20">medium radius 20px</option>

				<?php if ($css3_temp == '45'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="45">rounded radius</option>

				</select></td>

				<td width="125">

				&nbsp;</td>

			</tr>

			<tr>

				<td width="177" colspan="2">Image direction</td>

				<td width="178"><select size="1" name="rpw_image_direction">

				<?php

				$choice = '';

				$rpw_image_dir_temp = $rpw_related_posts_settings['rpw_image_direction']; ?>

				<?php if ($rpw_image_dir_temp == 'left'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="left">Left</option>
				
				<?php if ($rpw_image_dir_temp == 'center'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="center">Center</option>

				<?php if ($rpw_image_dir_temp == 'right'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="right">Right</option>

				</select></td>

				<td width="125">

				&nbsp;</td>

			</tr>

			<tr>

				<td width="177" colspan="2">Text direction</td>

				<td width="178"><select size="1" name="rpw_text_direction">

				<?php

				$choice = '';

				$rpw_text_direction_temp = $rpw_related_posts_settings['rpw_text_direction']; ?>

				<?php if ($rpw_text_direction_temp == 'ltr'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="ltr">Left To Right</option>
				
				<?php if ($rpw_text_direction_temp == 'center'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="center">Center</option>

				<?php if ($rpw_text_direction_temp == 'rtl'){$choice = 'selected';}else{$choice = '';} ?>

				<option <?php echo $choice ?> value="rtl">Right To Left</option>

				</select></td>

				<td width="125">

				&nbsp;</td>

			</tr>

			<tr>

				<td width="177" colspan="2">&nbsp;</td>

				<td width="178">&nbsp;</td>

				<td width="125">

				&nbsp;</td>

			</tr>

			<tr>

				<td width="177" colspan="2">&nbsp;</td>

				<td width="178">&nbsp;</td>

				<td width="125">

				&nbsp;</td>

			</tr>

			<tr>

				<td width="480" colspan="4">&nbsp;</td>

			</tr>

		</table></div>
<!-- new tab -->
<div class="simpleTabsContent" style="border: 1px solid #E9E9E9; padding: 4px">
	<h3>Description:</h3>
	<p>Here is wonderful widget for displaying links to related posts beneath 
	each of your wordpress blog posts. The related articles are chosen from 
	other posts in that same tag. With this plugin many of your readers will 
	remain on your site for longer periods of time when they see related posts 
	of interest.<img width="450" border="0" src="<?php echo $rpwpluginsurl; ?>/images/widget-recent-portfolios.png" align="right"></p>
	<p>Our plugin displaying related posts in a very great way as a sidebar 
	widget to help visitors staying longer on your site. You can use this plugin 
	to increasing the page rank of your internal posts to improve your SEO score 
	and increase the internal links priority in google webmaster tools</p>
	<h3>Features:</h3>
	<ul>
		<li>More than 12 attractive styles to to match your needs</li>
		<li>Multiple instances of the same widget</li>
		<li>Title, Excerpt &amp; thumbnails</li>
		<li>Display related post titles</li>
		<li>Option to display the post thumbnails or not</li>
		<li>Ability to control the size of post thumbnails</li>
		<li>Option to show post text excerpts</li>
		<li>Option to control the length of the post excerpt</li>
		<li>Option to choose the number of related posts to show</li>
		<li>Option to control the shadow effect on images</li>
		<li>Option to control the radius of the images</li>
		<li>Ability to set float &amp; direction of post thumbnails and text too</li>
		<li>Widget shown only on single post pages</li>
	</ul>
		<p style="text-align: center">
	&nbsp;</p>
	</div>
<!-- /new tab -->
</div><!-- simple tabs div end -->		
		



<div>
	<p align="right">
	&nbsp;&nbsp; <input type="submit" value="     Save Settings     " name="B4">&nbsp;&nbsp;</p>
</div>

	</li>

</form></div>

<p>&nbsp;</p>