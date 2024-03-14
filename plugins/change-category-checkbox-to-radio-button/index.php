<?php 
/*
 * Plugin Name: Change Category Checkbox to Radio Button
 * Description: Easy Change WordPress Category Checkbox into Radio Button
 * Version: 1.1
 * Author: Md Abul Bashar
 * Author URI: http://www.codingbank.com
 */

/*Change category type check box to radio button in WordPress function.*/

function cb_change_cat_type(){
  echo '<script type="text/javascript">jQuery("#categorychecklist input, #categorychecklist-pop input, .cat-checklist input").each(function(){this.type="radio"});</script>';
}
add_action( 'admin_footer', 'cb_change_cat_type' );

?>