<?php
/* No direct access */
if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );
    /* Colorize admin options*/
    function color_admin_theme() {if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();}
    $options_framework= array('_colorthemeadmin','_colorthemeadmintop','_colorthemeadminwptop','_colorthemeadminmain');
    ?>
<script type="text/javascript">
/***********************************************************
Function name: Custom
Description: Custom scripting
***********************************************************/
$EasyMore=jQuery.noConflict();
    function like_showHide(id){if ($EasyMore("#"+id+"").is(":hidden")) {$EasyMore("#"+id+"").slideDown('fast'); } else { $EasyMore("#"+id+"").slideUp('fast'); }}
    function like_hide(id){$EasyMore("#"+id+"").empty();}
    function like_remove(id){$EasyMore("#"+id+"").remove();}
    function like_hoverShow(id){$EasyMore("#"+id+"").css("display","inline");}
    function like_hoverHide(id){$EasyMore("#"+id+"").css("display","none"); }
</script>
<div class="wrap">
<h2><?php _e('Colorize Admin', 'colorize-admin'); ?>
   <a class="add-new-h2" target="_blank" href="https://wordpress.org/plugins/colorize-admin"><?php _e('Home of colorize', 'colorize-admin'); ?></a>
</h2>
<div id="dashboard-widgets-wrap">
<div id="dashboard-widgets" class="metabox-holder">
<div id="postbox-container-1" class="postbox-container">
<div id="normal-sortables" class="meta-box-sortables ui-sortable">
   <div id="dashboard_right_now" class="postbox ">
      <h2 class="hndle ui-sortable-handle" title="Click to toggle"   onclick="javascript:like_showHide('main-br');" style="cursor: pointer!important;"><span><span class="dashicons dashicons-image-filter"></span> &nbsp;<?php _e('Colorize Admin', 'colorize-admin'); ?></span> </h2>
      <div class="inside"  id="main-br">
         <div class="main">
            <form method="post" action="options.php" id="htmlForm">
            <?php wp_nonce_field('update-options'); ?>
            <script type="text/javascript"> jQuery('#setting-error-settings_updated').delay(1000).fadeOut(1000);</script>
            <label for="framework_logo">
            <?php _e('Select color theme:', 'colorize-admin'); ?>
            </label>&nbsp;&nbsp;
            <select name="_colorthemeadmin" size="1">
               <option value="default" <?php if (get_option( '_colorthemeadmin')=='default' ) echo 'selected="WP default"'; ?> > Wp default</option>
               <option value="red" <?php if (get_option( '_colorthemeadmin')=='red' ) echo 'selected="Red"'; ?> > Red</option>
               <option value="blue" <?php if (get_option( '_colorthemeadmin')=='blue' ) echo 'selected="Blue"'; ?> > Blue</option>
               <option value="navy" <?php if (get_option( '_colorthemeadmin')=='navy' ) echo 'selected="Navy"'; ?> > Navy</option>
               <option value="gray" <?php if (get_option( '_colorthemeadmin')=='gray' ) echo 'selected="Grey"'; ?> > Grey</option>
               <option value="gold" <?php if (get_option( '_colorthemeadmin')=='gold' ) echo 'selected="Gold"'; ?> > Gold</option>
               <option value="olive" <?php if (get_option( '_colorthemeadmin')=='olive' ) echo 'selected="Olive"'; ?> > Olive</option>
               <option value="peru" <?php if (get_option( '_colorthemeadmin')=='peru' ) echo 'selected="Peru"'; ?> > Peru</option>
               <option value="brown" <?php if (get_option( '_colorthemeadmin')=='brown' ) echo 'selected="Brown"'; ?> > Brown</option>
               <option value="orange" <?php if (get_option( '_colorthemeadmin')=='orange' ) echo 'selected="Orange"'; ?> > Orange</option>
               <option value="purple" <?php if (get_option( '_colorthemeadmin')=='purple' ) echo 'selected="Purple"'; ?> > Purple</option>
               <option value="indigo" <?php if (get_option( '_colorthemeadmin')=='indigo' ) echo 'selected="Indigo"'; ?> > Indigo</option>
               <option value="maroon" <?php if (get_option( '_colorthemeadmin')=='maroon' ) echo 'selected="Maroon"'; ?> > Maroon</option>
               <option value="crimson" <?php if (get_option( '_colorthemeadmin')=='crimson' ) echo 'selected="Crimson"'; ?> > Crimson</option>
               <option value="chocolate" <?php if (get_option( '_colorthemeadmin')=='chocolate' ) echo 'selected="Chocolate"'; ?> > Chocolate</option>
               <option value="deeppink" <?php if (get_option( '_colorthemeadmin')=='deeppink' ) echo 'selected="DeepPink"'; ?> >  DeepPink</option>
               <option value="firebrick" <?php if (get_option( '_colorthemeadmin')=='firebrick' ) echo 'selected="Fire Brick"'; ?> >  Fire Brick</option>
               <option value="darkcyan" <?php if (get_option( '_colorthemeadmin')=='darkcyan' ) echo 'selected="Dark Cyan"'; ?> >  Dark Cyan</option>
               <option value="darkorchid" <?php if (get_option( '_colorthemeadmin')=='darkorchid' ) echo 'selected="Dark Orchid"'; ?> > Dark Orchid</option>
               <option value="limegreen" <?php if (get_option( '_colorthemeadmin')=='limegreen' ) echo 'selected="Lime Green"'; ?> > Lime Green</option>
               <option value="springg" <?php if (get_option( '_colorthemeadmin')=='springg' ) echo 'selected="Spring Green"'; ?> > Spring Green</option>
               <option value="steelb" <?php if (get_option( '_colorthemeadmin')=='steelb' ) echo 'selected="Steel Blue"'; ?> > Steel Blue</option> 
               <option value="orangered" <?php if (get_option( '_colorthemeadmin')=='orangered' ) echo 'selected="Orange Red"'; ?> > Orange Red</option>
               <option value="goldenrod" <?php if (get_option( '_colorthemeadmin')=='goldenrod' ) echo 'selected="Golden Rod"'; ?> > Golden Rod</option>
               <option value="darkk" <?php if (get_option( '_colorthemeadmin')=='darkk' ) echo 'selected="Dark Khaki"'; ?> > Dark Khaki</option>
               <option value="forestgreen" <?php if (get_option( '_colorthemeadmin')=='forestgreen' ) echo 'selected="Forest Green"'; ?> > Forest Green</option>
               <option value="antiquewhite" <?php if (get_option( '_colorthemeadmin')=='antiquewhite' ) echo 'selected="Antique White"'; ?> > Antique White</option>
               <option value="ghostwhite" <?php if (get_option( '_colorthemeadmin')=='ghostwhite' ) echo 'selected="Ghost White"'; ?> > Ghost White</option>
               <option value="yellowgreen" <?php if (get_option( '_colorthemeadmin')=='yellowgreen' ) echo 'selected="Yellow Green"'; ?> > Yellow Green</option>
               <option value="rebeccapurple" <?php if (get_option( '_colorthemeadmin')=='rebeccapurple' ) echo 'selected="Rebecca Purple"'; ?> > Rebecca Purple</option>
               <option value="lightseagreen" <?php if (get_option( '_colorthemeadmin')=='lightseagreen' ) echo 'selected="Light Sea Green"'; ?> >Light Sea Green</option>
            </select>
            <br>
            <p>
               <?php _e('This is a simple plugin that will make your wp admin panel much more pleasant for work. Using specific colours you improve your work surrounding and simple and clean design of your wp admin panel.','colorize-admin') ?>
            </p>
            <hr>
            <p>
               <?php _e( 'Misc: ','colorize-admin' ); ?><br>
               <input class="widefat" name="_colorthemeadmintop" type="checkbox" value="off" <?php checked( get_option( '_colorthemeadmintop'), 'off' ); ?>>
               <label>
               <?php _e( 'Hide top admin Colorize menu.','colorize-admin' ); ?>
               </label>
               <br>
               <input class="widefat" name="_colorthemeadminmain" type="checkbox" value="off" <?php checked( get_option( '_colorthemeadminmain'), 'off' ); ?>>
               <label>
               <?php _e( 'Send Colorize Admin menu item to settings section.','colorize-admin' ); ?>
               </label>
               <br>
               <input class="widefat" name="_colorthemeadminwptop" type="checkbox" value="off" <?php checked( get_option( '_colorthemeadminwptop'), 'off' ); ?>>
               <label>
               <?php _e( 'Hide Wordpress logo.','colorize-admin' ); ?>
               </label>
            </p>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="<?php echo esc_attr( implode( ',', $options_framework ) ); ?>" />
                <br><p>
               <button type="submit" class="button-primary">
               <?php _e('Save Settings','colorize-admin') ?>
               </button>
               </form>
            </p>
         </div>
      </div>
   </div>
   <div id="dashboard_help" class="postbox " >
      <h2 class="hndle ui-sortable-handle" style="cursor: default!important;"><span class="dashicons dashicons-editor-help"></span><span><?php _e('Colorize Admin Help','colorize-admin') ?></span></h2>
      <div class="inside">
         <div class="main">
            <p>
            <h3 style="font-weight: bold;">
               <?php _e('How to use','colorize-admin') ?>
            </h3>
            <p>
               <?php _e('Using the addition is very easy. You just need to choose the colour you like and that\'s it.','colorize-admin') ?>
            </p>
            <h3 style="font-weight: bold;">
               <?php _e('Note','colorize-admin') ?>
            </h3>
            <p>
               <?php _e('We recommend not to use this plugin in combination with other similar plugins which change the admin themes. This plugin will most likely overwrite others or will be overwritten. You can\'t use two similar plugins at the same time.','colorize-admin') ?>
            </p>
            </p>
         </div>
      </div>
   </div>
</div>
</div>
</div>
</div>
</div>

    <?php }
