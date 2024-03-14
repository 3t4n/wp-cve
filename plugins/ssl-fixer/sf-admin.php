<?php
add_action('admin_menu', 'sf_admin_menu');

function sf_admin_menu () {
	add_plugins_page('SSL Fixer', 'SSL Fixer', 'manage_options', 'ssl-fixer.php', 'ssl_fixer_admin_page');
}

function ssl_fixer_admin_page () { //The whole admin page
	?>
	<div class="wrap">
	<h2><?php _e( 'Welcome To SSL Fixer', 'ssl-fixer' );?></h2>

    <p><?php _e( 'SSL Fixer makes a few changes to the database in order to fix any insecure links. Effectively fixing the HTTPS redirection and mixed content problems in one click. 
    Precisely speaking it does two things:', 'ssl-fixer' );?></p>
    
    <ul style='list-style: disc;text-indent: 50px'>
        <li><?php _e( 'Modify any insecure links from your wp-config.php file, such as WP_DEFINE() home and siteurl.', 'ssl-fixer' );?></li>
        <li><?php _e( 'Convert all your database\'s HTTP links into HTTPS ones, making the requests secure.', 'ssl-fixer' );?></li>
    </ul>  

    <br><br>
    <p><?php _e( 'To fix your SSL, please click the button below.', 'ssl-fixer' );?></p>

    <form method="post">
    <?php wp_nonce_field('sslfixer_nonce_action');?>
    <input type="submit" class="button-primary" name="fixme" value="<?php _e( 'Fix SSL', 'ssl-fixer' );?>" style="padding-left: 20px;padding-right: 20px">
    </form>

    <br><br>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" style="float:right">
    <input type="hidden" name="cmd" value="_s-xclick" />
    <input type="hidden" name="hosted_button_id" value="525SG7ZGDBRVY" />
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
    <img alt="" border="0" src="https://www.paypal.com/en_AR/i/scr/pixel.gif" width="1" height="1" />
    </form>
	</div>
	<?php
}
?>