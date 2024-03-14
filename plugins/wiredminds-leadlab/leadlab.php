<?php
/*
Plugin Name: LeadLab by wiredminds 
Description: Wiredminds LeadLab Tracking-Code integration for WordPress
Plugin URI: https://github.com/wiredminds-gmbh/wordpress
Version: 1.3
Author: wiredminds GmbH
Author URI: http://www.wiredminds.de
*/

plugins_url( 'leadlab.php', __FILE__ );

if (false === version_compare(phpversion(), '5', '>=')) {
    trigger_error('WiredMinds for WordPress requires PHP 5 or greater.', E_USER_ERROR);
}

/**
 * Add menu link
 */
function wp_wm_add_links()
{
    if (function_exists('add_options_page')) {
        add_options_page('LeadLab by wiredminds', 'LeadLab by wiredminds', 'administrator', __FILE__, 'wp_wm_admin');
    }
}

/**
 * Create admin page
 */
function wp_wm_admin()
{
 
    add_option('wp_wm_custnum', '');
    
    if (!empty($_POST['action'])) {
        if ($_POST['action'] == 'save') {
            update_option('wp_wm_custnum', sanitize_text_field($_POST['wp_wm_custnum']));
            
        }
        
    }
    $wp_wm_custnum = sanitize_text_field(get_option('wp_wm_custnum'));
	
	
	 add_option('wp_wm_consent', '');
    
    if (!empty($_POST['action'])) {
        if ($_POST['action'] == 'save' && isset($_POST['wp_wm_consent']) && $_POST['wp_wm_consent'] == 1) {
            update_option('wp_wm_consent', (int)$_POST['wp_wm_consent']);
        } elseif ($_POST['action'] == 'save' && !isset($_POST['wp_wm_consent'])) {
			update_option('wp_wm_consent', 0);
		}
    }
    $wp_wm_consent = sanitize_text_field(get_option('wp_wm_consent'));
    
    
    ?>

    <div class="wrap">
        <h2><?php
            _e('Wiredminds LeadLab Tracking-Code Konfiguration');
            ?></h2>
        <div class="postbox-container" style="width: 600px;">
            <div class="metabox-holder">
                <div class="meta-box-sortables">
                    <form action="" method="post">
                        <div class="postbox">
                            <h3 class="hndle"><span>Konfiguration</span></h3>
                            <div class="inside"><?php
                                $error = 0;
                                if (strlen(get_option('wp_wm_custnum')) < 1) {
                                    $error++;
                                }
                                if ($error > 0) { ?>
                                    <p>
									<span style="color:red; font-weight:bold">
										Bitte Kundennummer eintragen.
									</span>
                                    </p>
                                <?php } ?>
                                <p>
                                    <label
                                        style="width:210px;text-align:right; float:left; display:block; line-height: 30px;"
                                        for="wp_wm_custnum">Kundennummer:</label>&nbsp;
                                    <input name="wp_wm_custnum" id="wp_wm_custnum" type="text" value="<?php
                                    echo $wp_wm_custnum;
                                    ?>" size="40"/>
                                </p>
                                
								
								<p>
								 <label
                                        style="width:210px;text-align:right; float:left; display:block; line-height: 30px;"
                                        for="wp_wm_consent">Tracking-Cookie Erweiterung:</label>&nbsp;
									<input name="wp_wm_consent" id="wp_wm_consent" type="checkbox" value=1 <?php
                                    echo empty($wp_wm_consent) ? '' : 'checked' ;
                                    ?> size="40"/>
								</p>
								
                            </div>
							
							
							
							
							
                        </div>
                        <div style="text-align:right">
                            <input type="hidden" name="action" value="save"/>
                            <input type="submit" class="button-primary" name="submit" value="<?php
                            _e('Speichern');
                            ?> &raquo;"/>
                        </div>
                    </form>
                    <hr/>   
        </div>
    </div>
    <?php
}

/**
 * Output pixelcode
 */
function wp_wm_pixel()
{   
    ob_start();
    $wp_wm_custnum = sanitize_text_field(get_option('wp_wm_custnum'));
	$wp_wm_consent = (int)get_option('wp_wm_consent');
    ob_end_clean();
    
    if (!empty($wp_wm_custnum)) {
        ?>
        
        <!-- wiredminds leadlab tracking V7 START -->   
 	<script type="text/javascript">
        (function(d,s){var l=d.createElement(s),e=d.getElementsByTagName(s)[0];
		l.async=true;l.type='text/javascript';
		l.src='https://c.leadlab.click/<?php echo $wp_wm_custnum;?>.js';
		e.parentNode.insertBefore(l,e);})(document,'script');
	</script>
	
	
        <!-- wiredminds leadlab tracking V7 END -->
        <?php
    }
	if (!empty($wp_wm_consent)) {
        ?>
        
        <!-- wiredminds leadlab consent START -->   
 	<script type="text/javascript">
    (function(d,s){var l=d.createElement(s),e=d.getElementsByTagName(s)[0];
    l.async=true;l.type='text/javascript';
    l.src='https://c.leadlab.click/consent.min.js';
    e.parentNode.insertBefore(l,e);})(document,'script');
</script>
	
	
        <!-- wiredminds leadlab consent V7 END -->
        <?php
    }
	
}

add_action('admin_menu', 'wp_wm_add_links');
add_action('wp_footer', 'wp_wm_pixel');
