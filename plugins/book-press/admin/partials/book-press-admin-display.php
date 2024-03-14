<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wordpress.org/plugins/book-press
 * @since      1.0.0
 *
 * @package    Book_Press
 * @subpackage Book_Press/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
<h1 class="wp-heading-inline">Documentation</h1>


<p>Thank you for using BookPress.</p>

<p>You can find a comprehensive support guide here https://www.bookpress.net/docs/</p>

<h3>Help us Improve BookPress</h3>
<br>
<p>If you would like to make a small donation to help us further develop the plugin, please check out our PayPal link here:</p>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="RSXSRDQ7HANFQ" />
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1" />
</form>
</div>

<?php

if ( function_exists( 'book_press_fs' ) ){
  if ( book_press_fs()->is_not_paying() ) {
    	echo '<section><h3>'.__('Awesome Premium Features', 'bookpress').'</h3>';
      echo '<a href="'.book_press_fs()->get_upgrade_url().'">'.__('Upgrade Now!', 'bookpress') .'</a>';
      echo ' </section>';

    	echo '<br><section><h3>'.__('Have a license key?', 'bookpress').'</h3>';
      echo '<a href="'.site_url().'/wp-admin/admin.php?page=book-press-license">'.__('Activate Here', 'bookpress') .'</a>';
      echo ' </section>';

   }
}