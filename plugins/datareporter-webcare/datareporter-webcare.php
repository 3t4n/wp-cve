<?php
/*
Plugin Name: Datareporter Webcare
Plugin URI: https://wordpress.org/plugins/datareporter-webcare/
Description: WordPress module to embed DataReporter WebCare elements (imprint, privacy notice, cookie banner)
Author: LEMONTEC
Author URI: https://lemontec.at/
Version: 2.1.2
License: GPLv2 or later
Requires at least: 4.6
Requires PHP:      7.2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Update URI: https://wordpress.org/plugins/datareporter-webcare/
Text Domain: datareporter-webcare
*/

// VARS
$webcache_url = get_option('datareporter_webcache_url');
$client_id = get_option('datareporter_client_id');
$org_id = get_option('datareporter_organisation_id');
if(empty(get_option('datareporter_website_id')))
{
    $website_id = '';
}
else {
    $website_id = '/' . get_option('datareporter_website_id');
}

$iso_lang = substr( get_bloginfo ( 'language' ), 0, 2 );


// SCRIPTS
function datareporter_enqueue_script() {   
    global $client_id;
    global $org_id;
    global $webcache_url;
    global $iso_lang;
    global $website_id;
    
    if(empty($webcache_url))
    {
        wp_enqueue_style( 'style-datareporter', 'https://webcache-eu.datareporter.eu/c/'.$client_id.'/'.$org_id.$website_id.'/banner.css' );
        wp_enqueue_script( 'datareporter_script', 'https://webcache-eu.datareporter.eu/c/'.$client_id.'/'.$org_id.$website_id.'/banner.js?lang='.$iso_lang, array(), '1.0' ,true );
    }
    else {
        wp_enqueue_style( 'style-datareporter', $webcache_url . $client_id.'/'.$org_id.$website_id.'/banner.css' );
        wp_enqueue_script( 'datareporter_script', $webcache_url . $client_id.'/'.$org_id.$website_id.'/banner.js?lang='.$iso_lang, array(), '1.0', true );
    }
    
   wp_add_inline_script( 'datareporter_script', 'window.cookieconsent.initialise(dr_cookiebanner_options);' );
}
add_action('wp_enqueue_scripts', 'datareporter_enqueue_script');


/*** SHORTCODE ****/
function datareporter_shortcode( $atts ) {
    global $client_id;
    global $org_id;
    global $webcache_url;
    global $iso_lang;
    global $website_id;
    
    if($atts['type'] == 'imprint') {
        if(empty($webcache_url))
        {
            wp_enqueue_script( 'datareporter_script_imprint', 'https://webcache-eu.datareporter.eu/c/'.$client_id.'/'.$org_id.'/imprint_v2.js?lang='.$iso_lang, array(), '1.0' ,true );

        }
        else 
        {
            wp_enqueue_script( 'datareporter_script_imprint', $webcache_url . $client_id.'/'.$org_id.'/imprint_v2.js?lang='.$iso_lang, array(), '1.0' ,true );
        }
        $shortcode = '<div id="dr-imprint-div"></div>';
    }
    elseif($atts['type'] == 'privacynotice') {
        if(empty($webcache_url))
        {
            wp_enqueue_script( 'datareporter_script_privacynotice', 'https://webcache-eu.datareporter.eu/c/'.$client_id.'/'.$org_id.$website_id.'/privacynotice_v2.js?lang='.$iso_lang, array(), '1.0' ,true );
        }
        else 
        {
            wp_enqueue_script( 'datareporter_script_privacynotice', $webcache_url . $client_id.'/'.$org_id.$website_id.'/privacynotice_v2.js?lang='.$iso_lang, array(), '1.0' ,true );
        }
        $shortcode = '<div id="dr-privacynotice-div"></div>';
    }
    
    return $shortcode;
}
add_shortcode( 'datareporter', 'datareporter_shortcode' );

/*** BACKEND ****/

// create custom plugin settings menu
add_action('admin_menu', 'datareporter_create_menu');

function datareporter_create_menu() {
	//create new top-level menu
	add_menu_page('Datareporter Webcare Einstellungen', 'Datareporter Webcare', 'administrator', __FILE__, 'datareporter_plugin_settings_page' ,'dashicons-lock');

	//call register settings function
	add_action( 'admin_init', 'register_datareporter_settings' );
}


function register_datareporter_settings() {
	//register our settings
	register_setting( 'datareporter-settings-group', 'datareporter_client_id' );
	register_setting( 'datareporter-settings-group', 'datareporter_organisation_id' );
    register_setting( 'datareporter-settings-group', 'datareporter_webcache_url' );
    register_setting( 'datareporter-settings-group', 'datareporter_website_id' );
}

function datareporter_plugin_settings_page() {
?>
<div class="wrap">
<h1>Hier können Sie die Einstellungen von Datareporter Webcare vornehmen</h1>
<div style="background-color:#fff; padding:10px;">
    Bitte verwenden Sie folgenden Shortcodes um die automatisierte Ausgabe anwenden zu können.
    <ul>
        <li>
            <b>Impressum:</b> [datareporter type="imprint"]
        </li>
        <li>
            <b>Datenschutzerklärung:</b> [datareporter type="privacynotice"]
        </li>
    </ul>
    <p>
        Bei Fragen, schreiben Sie an <a href="mailto:office@datareporter.eu">office@datareporter.eu</a>
    </p>
</div>

<form method="post" action="options.php">
    <?php settings_fields( 'datareporter-settings-group' ); ?>
    <?php do_settings_sections( 'datareporter-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Client-Id</th>
        <td><input type="text" name="datareporter_client_id" value="<?php echo esc_attr( get_option('datareporter_client_id') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Organisation-Id</th>
        <td><input type="text" name="datareporter_organisation_id" value="<?php echo esc_attr( get_option('datareporter_organisation_id') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Website-Id</th>
        <td><input type="text" name="datareporter_website_id" value="<?php echo esc_attr( get_option('datareporter_website_id') ); ?>" />
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Webcache-Server</th>
        <td><input type="text" name="datareporter_webcache_url" value="<?php echo esc_attr( get_option('datareporter_webcache_url') ); ?>" placeholder="https://webcache-eu.datareporter.eu/c/" /><br> <br>(Standart: https://webcache-eu.datareporter.eu/c/ wenn die DataReporter-Suite nicht selbst gehostet wird).</td>
        </tr>
    </table>
    
    <?php submit_button(); ?>
</form>
</div>
<?php } ?>