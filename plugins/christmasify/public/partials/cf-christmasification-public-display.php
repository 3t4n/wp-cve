<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://cyberfoxdigital.co.uk
 * @since      1.0.0
 *
 * @package    Cf_Christmasification
 * @subpackage Cf_Christmasification/public/partials
 */

$santa 					 = get_option('cf_christmasify_santa');
$snowflakes 		 = get_option('cf_christmasify_snowflakes');
$classy_snow 		 = get_option('cf_christmasify_classy_snow');
$snow_speed 		 = get_option('cf_christmasify_snow_speed');
$music 					 = get_option('cf_christmasify_music');
if(!empty($music)){
	$music = plugins_url( 'christmasify/public/mp3/' . $music);
}
$image_frame 		 = get_option('cf_christmasify_image_frame');
$font 					 = get_option('cf_christmasify_font');
	
?>
<script>
	window.addEventListener("load", function() {
		jQuery(document).ready(function($){
			$(document).christmasify({
	      snowflakes: <?php 	echo !empty($snowflakes) 		? (int)$snowflakes        : 0; ?>,
	      classy_snow: <?php 	echo !empty($classy_snow) 	? 'true' 								  : 'false'; ?>,
	      snow_speed: '<?php 	echo !empty($snow_speed) 	  ? $snow_speed             : 'medium'; ?>',
	      santa: <?php 				echo !empty($santa) 				? 'true' 								  : 'false'; ?>,
	      music: <?php 				echo !empty($music) 				? "'" . $music . "'" 		  : 'false'; ?>,
	      image_frame: <?php 	echo !empty($image_frame) 	? 'true' 								  : 'false'; ?>,
	      font: <?php 				echo !empty($font) 					? 'true' 								  : 'false'; ?>
			}); 
		});
	});
</script>
<?php

?>