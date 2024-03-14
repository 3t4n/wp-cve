<div id="template_quran">

<div id="quran_main">
<?php
if(get_option('quran_template') == "enable"){
?>
<style>
#template_quran{
margin-top:80px;
}
</style>
	<div class="h-g_template"></div>

	<div class="h-d_template"></div>

	<div class="top_template"></div>

	<div class="left_template"></div>

	<div class="right_template"></div>

	<div class="bottom_template"></div>

	<div class="b-g_template"></div>

	<div class="b-d_template"></div>
<?php
}
if(get_option('quran_template') == "disabled"){
echo "<style>
	#quran_main {
    width: 95%;
	}
	</style>";

}
?>
<div id="kb_select_quran">
<form  class="aya1">

<select name="sourate" id="change_sura" method="post" class="aya2">

<option data-image="<?php echo plugin_dir_url(__FILE__); ?>/icon_quran.png" disabled="disabled" selected="selected"><?php echo get_option('quran_changesuratxt');?></option>				

<?php

	global $wpdb;

	$req_sourate = $wpdb->get_results( 

	"

	SELECT nom,nom_id,url

	FROM quran

	ORDER BY nom_id

	"

);


if(isset($_POST['cheikh_quran'])){



}


	foreach ( $req_sourate as $sourate ) 

	{

		$sourate->nom = ltrim($sourate->nom, "0");

		$name_sourate = strtolower($sourate->nom);

		$name_sourate = explode(".", $name_sourate);



		echo '<option data-image="'.plugin_dir_url(__FILE__).'Allah.png" value="?sourate='.trim($name_sourate[1]).'-' . $sourate->nom_id . '"';
	if(isset($sura)){
		if($sura == $sourate->nom_id){ echo ' selected="selected">';}			
	}	


	else{

	echo '>';}	

		echo ''.ucwords(strtolower($sourate->nom)).'</option>';

	}
	

?>

</select>

</form>
</div>
<div id="kb_select_language">
		<form  id="select_languages" method="get">

		<select name="select_language" id="select_language" claa="icon_lang_quran">

			<option data-image="<?php echo plugin_dir_url(__FILE__);?>icon_world.png" disabled="disabled" selected="selected"><?php echo get_option('quran_changelangtxt');?></option>				

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/us.png" value="english" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'english'):echo 'selected="selected"';endif;}?>>English</option>			

			<option  data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/fr.png"  value="francais" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'francais'):echo 'selected="selected"';endif;}?>>Français</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/de.png" value="german" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'german'):echo 'selected="selected"';endif;}?>>German</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/nl.png" value="dutch" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'dutch'):echo 'selected="selected"';endif;}?>>Dutch</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/ru.png" value="russian" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'russian'):echo 'selected="selected"';endif;}?>>Russian</option>	

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/al.png" value="albanian" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'albanian'):echo 'selected="selected"';endif;}?>>Albanian</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/az.png" value="azerbaijani" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'azerbaijani'):echo 'selected="selected"';endif;}?>>Azerbaijani</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/bd.png" value="bengali" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'bengali'):echo 'selected="selected"';endif;}?>>Bengali</option>			

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/bg.png" value="bulgarian" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'bulgarian'):echo 'selected="selected"';endif;}?>>Bulgarian</option>	

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/ba.png" value="bosnian" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'bosnian'):echo 'selected="selected"';endif;}?>>Bosnian</option>				

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/cn.png" value="chinese" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'chinese'):echo 'selected="selected"';endif;}?>>Chinese</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/cz.png" value="czech" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'czech'):echo 'selected="selected"';endif;}?>>Czech</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/id.png" value="indonesian" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'indonesian'):echo 'selected="selected"';endif;}?>>Indonesian</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/it.png" value="italian" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'italian'):echo 'selected="selected"';endif;}?>>Italian</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/iq.png" value="kurdish" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'kurdish'):echo 'selected="selected"';endif;}?>>Kurdish</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/my.png" value="malay" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'malay'):echo 'selected="selected"';endif;}?>>Malay</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/no.png" value="norwegian" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'norwegian'):echo 'selected="selected"';endif;}?>>Norwegian</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/pt.png" value="portuguese" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'portuguese'):echo 'selected="selected"';endif;}?>>Portuguese</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/ro.png" value="romanian" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'romanian'):echo 'selected="selected"';endif;}?>>Romanian</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/so.png" value="somali" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'somali'):echo 'selected="selected"';endif;}?>>Somali</option>

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/es.png" value="spanish" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'spanish'):echo 'selected="selected"';endif;}?>>Spanish</option>	

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/se.png" value="swedish" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'swedish'):echo 'selected="selected"';endif;}?>>Swedish</option>	

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/tr.png" value="turkish" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'turkish'):echo 'selected="selected"';endif;}?>>Turkish</option>	

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/pk.png" value="urdu" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'urdu'):echo 'selected="selected"';endif;}?>>Urdu</option>	

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/in.png" value="hindi" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'hindi'):echo 'selected="selected"';endif;}?>>Hindi</option>			
			
			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/ir.png" value="persian" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'persian'):echo 'selected="selected"';endif;}?>>Persian</option>			

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/ta.png" value="tajik" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'tajik'):echo 'selected="selected"';endif;}?>>Tajik</option>			

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/in.png" value="tamil" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'tamil'):echo 'selected="selected"';endif;}?>>Tamil</option>			

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/th.png" value="thai" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'thai'):echo 'selected="selected"';endif;}?>>Thai</option>		

			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/uz.png" value="uzbek" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'uzbek'):echo 'selected="selected"';endif;}?>>Uzbek</option>			
	
			<option data-image="<?php echo plugin_dir_url(__FILE__); ?>images/country/am.png" value="amharic" <?php if(isset($_GET['lang_quran'])){if($_GET['lang_quran'] == 'amharic'):echo 'selected="selected"';endif;}?>>Amharic</option>			

		</select>

		</form>

</div>


<?php

	init_quran();



	if(isset($_GET['sourate']) && isset($_GET['lang'])){

			preg_match("/[0-9]{1,3}$/", $_GET['sourate'], $matches);

			$sura = $matches[0];
			?>
			<script>
			jQuery(document).ready(function(e) {
				var UrlPrevSourate = "?sourate=<?php echo $_GET['sourate'];echo "&lang=".$_GET['lang']."";?>";
				history.pushState({ path: this.path }, '', ''+UrlPrevSourate+'');
			});
			</script>
			<div id="result">
			<?php
			showSura($sura,$_GET['lang']);

	}

	else{

		   $lang = get_option('quran_languages');
		?>
			<script>
			var UrlPrevSourate = "?sourate=al-fatiha-1"	;
			history.pushState({ path: this.path }, '', ''+UrlPrevSourate+'&lang=<?=$lang;?>');
			</script>
			<div id="result">
		<?php
			
		showSura(1,$lang);
	}

?>

</div>	

</div>
</div>
