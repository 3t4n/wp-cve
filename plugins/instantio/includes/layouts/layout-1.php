<?php
defined( 'ABSPATH' ) || exit;
 
 ob_start(); 
 ?>
 	<div class="ins-fixed-toogle"> <?php echo do_action('ins_cart_toggle'); ?></div>
 <?php
 echo ob_get_clean();
?>