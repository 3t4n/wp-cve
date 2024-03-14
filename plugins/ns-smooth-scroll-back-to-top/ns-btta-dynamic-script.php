<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function ns_btta_print_javascript_var()
{
    ?>
    <script>
		var speedScrool = <?php echo get_option('ns_btta_speed', '800'); ?>;
   </script>
    <?php
}
 
add_action ('wp_print_scripts', 'ns_btta_print_javascript_var');
?>