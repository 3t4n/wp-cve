<?php

if (current_user_can('activate_plugins')) {

defined( 'ABSPATH' ) or die( 'Salem aleykoum!' );

    wp_register_script('quran_admin_color',plugin_dir_url( __FILE__ ).'js/jscolor/jscolor.js');	

	wp_enqueue_script('quran_admin_color');


	if(isset($_POST['template_quran_update'])){

		if(!wp_verify_nonce($_POST['template_quran_noncename'], 'tplquran')){

			die('token non valide');

		}


		foreach($_POST['option'] as $name => $val){

			$value = sanitize_text_field($val);
			
			if(empty($value)){

				delete_option($name);

			}else{

				update_option($name, $value);

			}



		}

			?>

			<div id="message" class="updated fade">

			<p>Thème sauvegardé!</p>

			</div>

			<?php

	}

?>

<style>

input:checked ~ img  {

    border: 2px solid #00A0D2;

}
#thadminquran{width: auto !important;}
.border_tpl_quran{float:left}
#borderColorQuran{display: none;}
#bloc_admin_quran{background:#ffffff;padding:20px;color:#7a7a7a;}
#bloc_admin_quran th{color:#7a7a7a;padding:20px;}
#bloc_admin_quran tr:nth-child(even) {background: #F8F8F8}
#bloc_admin_qurantr:nth-child(odd) {background: #FFF}
.viewfont{font-size:30px;font-weight:400;color:#000000;font-family:<?php echo get_option('quran_arabicfont');?>}
.wordspacing{word-spacing:<?php echo get_option('quran_wordspacing');?>px;}
@font-face {
    font-family: "noorehira";
    src: url('<?php echo plugin_dir_url(__FILE__); ?>/font/noorehira.ttf');
}
@font-face {
    font-family: "uthmanic";
    src: url('<?php echo plugin_dir_url(__FILE__); ?>/font/uthmanic.otf');
}
@font-face {
    font-family: "goldenlotus";
    src: url('<?php echo plugin_dir_url(__FILE__); ?>/font/goldenlotus.ttf');
}
@font-face {
    font-family: "swer_quran";
    src: url('<?php echo plugin_dir_url(__FILE__); ?>/font/swer_quran.ttf');
}
@font-face {
    font-family: 'quran';
    src: url('<?php echo plugin_dir_url(__FILE__); ?>/font/quran.woff2') format('woff2');
    font-display: swap;
  }
</style>
<script>
	function viewfont(font)
		{
			jQuery('.viewfont').css("font-family", font.value);

		}
		function wordspacing(px)
		{
			jQuery('.wordspacing').css("word-spacing", px.value+"px");

		}		
</script>
<div class="wrap" id="bloc_admin_quran">

<h3>Quran Text Multilanguage Options</h3>



<form method="post" action="">



<?php settings_fields( 'quran-options' ); ?>


<table class="form-table">


<tr valign="top" id="displaytemplateQuran">

<th scope="row" id="thadminquran">Display the template</th>

<td>
   <label><input type="radio" id="background_enable" onclick="backDisabled();" name="option[quran_template]" <?php if (get_option('quran_template') == "enable") {echo 'checked="checked"';} ?> value="enable">Enable</label>
   <label><input type="radio" name="option[quran_template]" onclick="backDisabled();" id="background_disabled" <?php if (get_option('quran_template') == "disabled") {echo 'checked="checked"';} ?> value="disabled">Disabled</label><br>
</td>

</tr>
<tr valign="top" id="borderColorQuran">

<th scope="row" id="thadminquran">Choose border Color</th>

<td>
<input name="option[border_quran_color]" id="text_quran_title" class="color" value="<?php echo get_option('border_quran_color'); ?>" />
</td>

</tr>

<tr valign="top">

<th scope="row" id="thadminquran">Choose the cheikh for the versets</th>

<td>

<select name="option[quran_recitator]" id="quran_recitator">

<option disabled="disabled">Choose the cheikh for the versets</option>

<option value="ElGhamidi" <?php if (get_option('quran_recitator') == "ElGhamidi") {echo 'selected="selected"';} ?>>Saad El Galmidi</option>

<option value="Soudais" <?php if (get_option('quran_recitator') == "Soudais") {echo 'selected="selected"';} ?>>Abderrahman Al Soudais</option>

<option value="Basfar" <?php if (get_option('quran_recitator') == "Basfar") {echo 'selected="selected"';} ?>>Abdallah Ali Basfar</option>

<option value="Alafasy" <?php if (get_option('quran_recitator') == "Alafasy") {echo 'selected="selected"';} ?>>Alafasy</option>

<option value="Al-Hussary" <?php if (get_option('quran_recitator') == "Al-Hussary") {echo 'selected="selected"';} ?>>Al-Hussary</option>

<option value="Al-Ajmy" <?php if (get_option('quran_recitator') == "Al-Ajmy") {echo 'selected="selected"';} ?>>Al-Ajmy</option>
</select>
</td>

</tr>

<tr valign="top">

<th scope="row" id="thadminquran">Choose font for arabic text</th>

<td>

<select name="option[quran_arabicfont]" id="quran_arabicfont" onchange="viewfont(this);">

<option disabled="disabled">Choose font for arabic text</option>

<option value="noorehira" <?php if (get_option('quran_arabicfont') == "noorehira") {echo 'selected="selected"';} ?>>noorehira Regular</option>

<option value="uthmanic" <?php if (get_option('quran_arabicfont') == "uthmanic") {echo 'selected="selected"';} ?>>Uthmanic Hafs</option>

<option value="goldenlotus" <?php if (get_option('quran_arabicfont') == "goldenlotus") {echo 'selected="selected"';} ?>>Golden Lotus</option>

<option value="swer_quran" <?php if (get_option('quran_arabicfont') == "swer_quran") {echo 'selected="selected"';} ?>>Mcs Swer Al_Quran 2</option>

<option value="quran" <?php if (get_option('quran_arabicfont') == "quran") {echo 'selected="selected"';} ?>>Quran v3</option>
</select>
<span class="viewfont">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</span>
</td>

</tr>
<tr valign="top">

<th scope="row" id="thadminquran">Word Spacing for arabic text</th>

<td>

<select name="option[quran_wordspacing]" id="quran_wordspacing" onchange="wordspacing(this);">

<option disabled="disabled">Word Spacing for arabic text</option>
<option value="0" <?php if (get_option('quran_wordspacing') == "0") {echo 'selected="selected"';} ?>>0 px</option>
<option value="1" <?php if (get_option('quran_wordspacing') == "1") {echo 'selected="selected"';} ?>>1 px</option>
<option value="2" <?php if (get_option('quran_wordspacing') == "2") {echo 'selected="selected"';} ?>>2 px</option>
<option value="3" <?php if (get_option('quran_wordspacing') == "3") {echo 'selected="selected"';} ?>>3 px</option>
<option value="4" <?php if (get_option('quran_wordspacing') == "4") {echo 'selected="selected"';} ?>>4 px</option>
<option value="5" <?php if (get_option('quran_wordspacing') == "5") {echo 'selected="selected"';} ?>>5 px</option>
<option value="6" <?php if (get_option('quran_wordspacing') == "6") {echo 'selected="selected"';} ?>>6 px</option>
<option value="7" <?php if (get_option('quran_wordspacing') == "7") {echo 'selected="selected"';} ?>>7 px</option>
<option value="8" <?php if (get_option('quran_wordspacing') == "8") {echo 'selected="selected"';} ?>>8 px</option>
<option value="9" <?php if (get_option('quran_wordspacing') == "9") {echo 'selected="selected"';} ?>>9 px</option>
<option value="10" <?php if (get_option('quran_wordspacing') == "10") {echo 'selected="selected"';} ?>>10 px</option>

</select>
<span class="viewfont wordspacing">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</span>
</td>

</tr>
<tr valign="top">



<th scope="row" id="thadminquran">language default</th>



<td>

			<select name="option[quran_languages]" id="quran_languages">

			<option value="arabe"<?php if (get_option('quran_languages') == "arabe"){echo 'selected="selected"';}?>>Arabe</option>				

			<option value="english"<?php if (get_option('quran_languages') == "english"){echo 'selected="selected"';}?>>English</option>			

			<option value="francais"<?php if (get_option('quran_languages') == "francais"){echo 'selected="selected"';}?>>Français</option>

			<option value="german"<?php if (get_option('quran_languages') == "german"){echo 'selected="selected"';}?>>German</option>

			<option value="dutch"<?php if (get_option('quran_languages') == "dutch"){echo 'selected="selected"';}?>>Dutch</option>

			<option value="russian"<?php if (get_option('quran_languages') == "russian"){echo 'selected="selected"';}?>>Russian</option>	

			<option value="albanian"<?php if (get_option('quran_languages') == "albanian"){echo 'selected="selected"';}?>>Albanian</option>

			<option value="azerbaijani"<?php if (get_option('quran_languages') == "azerbaijani"){echo 'selected="selected"';}?>>Azerbaijani</option>

			<option value="bengali"<?php if (get_option('quran_languages') == "bengali"){echo 'selected="selected"';}?>>Bengali</option>			

			<option value="bulgarian"<?php if (get_option('quran_languages') == "bulgarian"){echo 'selected="selected"';}?>>Bulgarian</option>	

			<option value="bosnian"<?php if (get_option('quran_languages') == "bosnian"){echo 'selected="selected"';}?>>Bosnian</option>		

			<option value="chinese"<?php if (get_option('quran_languages') == "chinese"){echo 'selected="selected"';}?>>Chinese</option>

			<option value="czech"<?php if (get_option('quran_languages') == "czech"){echo 'selected="selected"';}?>>Czech</option>

			<option value="indonesian"<?php if (get_option('quran_languages') == "indonesian"){echo 'selected="selected"';}?>>Indonesian</option>

			<option value="italian"<?php if (get_option('quran_languages') == "italian"){echo 'selected="selected"';}?>>Italian</option>

			<option value="kurdish"<?php if (get_option('quran_languages') == "kurdish"){echo 'selected="selected"';}?>>Kurdish</option>

			<option value="malay"<?php if (get_option('quran_languages') == "malay"){echo 'selected="selected"';}?>>Malay</option>

			<option value="norwegian"<?php if (get_option('quran_languages') == "norwegian"){echo 'selected="selected"';}?>>Norwegian</option>

			<option value="portuguese"<?php if (get_option('quran_languages') == "portuguese"){echo 'selected="selected"';}?>>Portuguese</option>

			<option value="romanian"<?php if (get_option('quran_languages') == "romanian"){echo 'selected="selected"';}?>>Romanian</option>

			<option value="somali"<?php if (get_option('quran_languages') == "somali"){echo 'selected="selected"';}?>>Somali</option>

			<option value="spanish"<?php if (get_option('quran_languages') == "spanish"){echo 'selected="selected"';}?>>Spanish</option>	

			<option value="swedish"<?php if (get_option('quran_languages') == "swedish"){echo 'selected="selected"';}?>>Swedish</option>	

			<option value="turkish"<?php if (get_option('quran_languages') == "turkish"){echo 'selected="selected"';}?>>Turkish</option>				
			
			<option value="urdu"<?php if (get_option('quran_languages') == "urdu"){echo 'selected="selected"';}?>>Urdu</option>				
			
			<option value="hindi"<?php if (get_option('quran_languages') == "hindi"){echo 'selected="selected"';}?>>Hindi</option>				
				
			<option value="persian"<?php if (get_option('quran_languages') == "persian"){echo 'selected="selected"';}?>>Persian</option>				
			
			<option value="thai"<?php if (get_option('quran_languages') == "thai"){echo 'selected="selected"';}?>>Thai</option>				

			<option value="uzbek"<?php if (get_option('quran_languages') == "uzbek"){echo 'selected="selected"';}?>>Uzbek</option>				

			</select>

</td>

</tr>

<tr valign="top">
<th scope="row" id="thadminquran">Change sura text </th>
<td>
   <label><input type="text" name="option[quran_changesuratxt]" value="<?php echo get_option('quran_changesuratxt'); ?>"></label>
</td>
</tr>

<tr valign="top">
<th scope="row" id="thadminquran">Change language text </th>
<td>
   <label><input type="text" name="option[quran_changelangtxt]" value="<?php echo get_option('quran_changelangtxt'); ?>"></label>
</td>
</tr>

<tr valign="top">
<th scope="row" id="thadminquran">Change recitator text </th>
<td>
   <label><input type="text" name="option[quran_changerecitatortxt]" value="<?php echo get_option('quran_changerecitatortxt'); ?>"></label>
</td>
</tr>


<tr valign="top">

<th scope="row" id="thadminquran">Color title</th>
<td>
Text : <input name="option[text_quran_title]" id="text_quran_title" class="color" value="<?php echo get_option('text_quran_title'); ?>" />
Background : <input name="option[background_quran_title]" id="background_quran_title" class="color" value="<?php echo get_option('background_quran_title'); ?>" />

</td>

</tr>



<tr valign="top">

<th scope="row" id="thadminquran">Color number</th>

<td>Num :<input name="option[color_quran_number]" id="color_quran_number" class="color" value="<?php echo get_option('color_quran_number'); ?>" />

Background : <input name="option[background_quran_number]" id="background_quran_number" class="color" value="<?php echo get_option('background_quran_number'); ?>" />

</td>

</tr>



<tr valign="top">

<th scope="row" id="thadminquran">Color translate</th>

<td>Text : <input name="option[text_quran_trans]" id="text_quran_trans" class="color" value="<?php echo get_option('text_quran_trans'); ?>" />

Background : <input name="option[background_quran_trans]" id="background_quran_trans" class="color" value="<?php echo get_option('background_quran_trans'); ?>" />

</td>

</tr>



<tr valign="top">

<th scope="row" id="thadminquran">Color arabic</th>

<td>Text : <input name="option[text_quran_arabic]" id="text_quran_arabic" class="color" value="<?php echo get_option('text_quran_arabic'); ?>" />

Background : <input name="option[background_quran_arabic]" id="background_quran_arabic" class="color" value="<?php echo get_option('background_quran_arabic'); ?>" />

</td>

</tr>

<tr valign="top">

<th scope="row" id="thadminquran">Custum CSS</th>

<td>without the tag &lt;style&gt;...&lt;/style&gt;<button id="quran_custum_css"> Click Here</button>
<p><textarea  name="option[quran_custum_css]" id="areacsscustum" style="width: 500px; height: 150px;display:none">
<?php echo get_option('quran_custum_css'); ?>
</textarea></p>

</td>

</tr>

<div style="margin-left:15px;float:left;">

<label>

  <input type="radio"  name="option[template_quran]" value="template-1" style="display:none"/>

  <img src="<?php echo plugin_dir_url( __FILE__ );?>images/template-1.jpg" class="border_tpl_quran">

</label>

</div>

<div style="margin-left:15px;float:left;">

<label>

  <input type="radio"  name="option[template_quran]" value="template-2" style="display:none"/>

  <img src="<?php echo plugin_dir_url( __FILE__ );?>images/template-2.jpg" class="border_tpl_quran">

</label>

</div>

<div style="margin-left:15px;float:left;">

<label>

  <input type="radio"  name="option[template_quran]" value="template-3" style="display:none"/>

  <img src="<?php echo plugin_dir_url( __FILE__ );?>images/template-3.jpg" class="border_tpl_quran">

</label>

</div>

<div style="margin-left:15px;float:left;">

<label>

  <input type="radio"  name="option[template_quran]" value="template-4" style="display:none"/>

  <img src="<?php echo plugin_dir_url( __FILE__ );?>images/template-4.jpg" class="border_tpl_quran">

</label>

</div>

<div style="margin-left:15px;float:left;">

<label>

  <input type="radio"  name="option[template_quran]" value="template-5" style="display:none"/>

  <img src="<?php echo plugin_dir_url( __FILE__ );?>images/template-5.jpg" class="border_tpl_quran">

</label>

</div>

</table>

<script>

function backDisabled(){
jQuery(function($) {

if($('#background_disabled').is(':checked')){
	$('#borderColorQuran').show();
	$('#displaytemplateQuran').css('background', '#ffffff');
}
if($('#background_enable').is(':checked')) {
	$('#borderColorQuran').hide();
	$('#displaytemplateQuran').css('background', '#F8F8F8');
}
});
}
jQuery(document).ready(function($){

if($('#background_disabled').is(':checked')){
	$('#borderColorQuran').show();
	$('#displaytemplateQuran').css('background', '#ffffff');
}

$("#quran_custum_css").click(function(){
        $("#areacsscustum").toggle();
        return false;
    });



	$( "input[name='submit']").val("Save")

});

</script>


<div id="button_quran_submit">

<div style="float:right">

</div>


		<input  type="hidden" name="template_quran_noncename" value="<?= wp_create_nonce('tplquran');?>">

		<p class="submit"> 

		<input type="submit" name="template_quran_update" class="button-primary autowidth" value="Save">

		</p>

</form>
 <fieldset style="border: 1px solid #DDDBDB;padding: 15px;" id="button_quran_submit">
  <legend>You can help me in 2 ways</legend>
 <p>1- Make du'a for me to go to hajj.</p>
 <p>2- By making a donation to help me pay the server.</p>
 <p>Barak'Allah oufikoum<span style="float:right"><a href="http://gp-codex.fr/forums" target="_blank">Free support</a></span></p>
 
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="LTVQQZDXPLHU8">
<input type="image" src="https://www.paypalobjects.com/en_US/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>

 </fieldset>
</div>

</div>

<?php
}
?>
