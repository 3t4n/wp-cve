<?php
/*
 * Plugin Name:         Simple Map No Api
 * Plugin URI:          https://github.com/syedshaon/simpleMapNoApi
 * Description:         Simple Google map that doesn't require google map api.
 * Version:             1.6
 * Author:              Syed Mashiur Rahman
 * Author URI:          https://www.mashi-zone.com/
 * Text Domain:         simple-map-no-api
 * Requires at least:   5.2
 * Requires PHP:        7.2
 * License:             GPL v3 or later
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.en.html
 */

if( !function_exists( 'add_action' ) ){
	die( "Hi there! I'm just a plugin, not much I can do when called directly." );
}
function smna_option()
{
    add_menu_page('Simple Map No Api', 'Map Options', 'administrator', 'smna_option', 'smna_adjustments', 'dashicons-location-alt', 99);

};
add_action('admin_menu', 'smna_option');

function smna_add_settings()
{
    register_setting('smna_gmap_setting', 'smna_gmap_latitude');
    register_setting('smna_gmap_setting', 'smna_gmap_longitude');
    register_setting('smna_gmap_setting', 'smna_gmap_width');
    register_setting('smna_gmap_setting', 'smna_gmap_height');
    register_setting('smna_gmap_setting', 'smna_gmap_title');
    register_setting('smna_gmap_setting', 'smna_gmap_address');
}

add_action('init', 'smna_add_settings');

function smna_adjustments()
{
    ?>
<div class="wrap">
    <h1>Google Map Adjustments</h1>
    <form action="options.php" method="post">
        <?php
        settings_fields('smna_gmap_setting');
        do_settings_sections('smna_gmap_setting') ?>
        <table class="form-table">
            <tr valing="top">
                <th scope="row">Latitude : </th>
                <td>
                    <input placeholder="Location's latitude here like: 40.6773988" class="regular-text"  type="text" name="smna_gmap_latitude"
                        value="<?php echo esc_attr(get_option('smna_gmap_latitude')); ?>">
                        <span>Required!</span>
                </td>
            </tr>
            <tr valing="top">
                <th scope="row">Longitude : </th>
                <td>
                    <input placeholder="Location's longitude here like: -85.6134087" class="regular-text" type="text" name="smna_gmap_longitude"
                        value="<?php echo esc_attr(get_option('smna_gmap_longitude')); ?>">
                        <span>Required!</span>
                </td>
            </tr>
            <tr valing="top">
                <th scope="row">Width : </th>
                <td>
                    <input placeholder="Desired width of your map, default 100%" class="regular-text"  type="text" name="smna_gmap_width"
                        value="<?php echo esc_attr(get_option('smna_gmap_width')); ?>">
                        <span>Optional</span>
                </td>
            </tr>
            <tr valing="top">
                <th scope="row">Height : </th>
                <td>
                    <input placeholder="Desired width of your map, default 400px" class="regular-text"  type="text" name="smna_gmap_height"
                        value="<?php echo esc_attr(get_option('smna_gmap_height')); ?>"> <span>Optional</span>
                </td>
            </tr>
            <tr valing="top">
                <th scope="row">Location Title : </th>
                <td>
                    <input placeholder="Desired Title of your map." class="regular-text" type="text" maxlength="20" name="smna_gmap_title"
                        value="<?php echo esc_attr(get_option('smna_gmap_title')); ?>">
                        <span>Optional, 20 Character limit</span>
                </td>
            </tr>
            <tr valing="top">
                <th scope="row">Location Address : </th>
                <td>
                    <input placeholder="Address of your location." class="regular-text" type="text" maxlength="25"  name="smna_gmap_address"
                        value="<?php
                                if(get_option('smna_gmap_address')){ 
                                 echo esc_attr(get_option('smna_gmap_address'));
                                }
                                ?>">
                        <span>Optional, 25 Character limit</span>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>    
    <br>
    <h2>Instructions:</h2>
    <p>After putting required information in the above form put the shortcode <span style="color:red; font-weight:bold;">[simpleMapNoApi]</span>  in pages where you want the map to show up. Done!</p>
    <p>For Video Instruction please visit this <a href="https://youtu.be/bQwq5WKh0y0">Youtube link</a></p>
    <p>Or, visit this  <a href="https://github.com/syedshaon/simpleMapNoApi"> github page</a> for detailed instruction.</p>   

</div>
<?php };
?>
<?php
function smna_createSimpleMap(){
    ob_start();?>
    <?php
        if(get_option('smna_gmap_latitude')){
        
            $latitude =get_option('smna_gmap_latitude');
        }else{
            $latitude = 37.320605;
        }
        if(get_option('smna_gmap_longitude')){
        
            $longitude =get_option('smna_gmap_longitude');
        }else{
            $longitude = -121.992445;
        }        
        if(get_option('smna_gmap_height')){
        
            $getHeight =(int) filter_var(get_option('smna_gmap_height'), FILTER_SANITIZE_NUMBER_INT);
            $height = $getHeight."px";
        }else{
            $height = "400px";
        }
        if(get_option('smna_gmap_width')){
            $width = get_option('smna_gmap_width');
        }else{
            $width = "100%";
        }
    ?>
    <div id="simpleMap" style="overflow:hidden; width:<?php echo $width; ?>; margin:0 auto; position: relative;">
        <div class="fluid-width-video-wrapper" style="padding-top:1px !important;">
            <iframe 
                src="https://maps.google.com/maps?q=<?php echo $latitude; ?>,<?php echo $longitude; ?>&hl=en;z=14&amp;output=embed"
                width="100%"
                frameborder="0"
                title="Our Location in google map"
                style="height:<?php echo $height; ?>; width:100%;  padding:0 !important;"
                allowfullscreen=""
                >
            </iframe>
        </div>
        <?php if(get_option('smna_gmap_title')){ ?>
        <div class="simpleMap-info" 
            style="position: absolute;
                    top: 11px;
                    left: 11px;
                    z-index: 333;
                    text-align:left;
                    background-color: #fff;
                    padding: 5px 0 0 5px;
                    width:225px;
                    ">
            <span style="color: #000; font-size:1.2rem; font-weight: bold; text-shadow: 2px 5px 10px rgba(0,0,0,0.65);">
            <?php echo substr(get_option('smna_gmap_title'), 0, 20); ?>
            </span> <br>
            <span style="font-size:.9rem; line-height:1;"><?php echo substr(get_option('smna_gmap_address'), 0, 25); ?></span>
        </div>
        <?php } ?>
    </div>
    <?php $content = ob_get_clean();
    return $content;
}
add_shortcode('simpleMapNoApi', 'smna_createSimpleMap');