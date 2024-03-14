<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

add_action( 'plugins_loaded', create_function( '', 'global $ngg3DFluxSliderSettings; $ngg3DFluxSliderSettings = new ngg3DFluxSliderSettings();' ) );

class ngg3DFluxSliderSettings {

    function __construct() {
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('admin_init', array(&$this,'register_plugin_settings'));
    }
    function admin_menu () {
        add_options_page('NextGen 3D Slider', 'NextGen 3D Slider', 'manage_options', 'ngg-3dfluxslider', array(&$this,'admin_output'));
    }

    function register_plugin_settings() {
        register_setting('ng_3dfluxslider_options', 'ng_3dfluxslider_transitions');
        register_setting('ng_3dfluxslider_options', 'ng_3dfluxslider_caption');
        register_setting('ng_3dfluxslider_options', 'ng_3dfluxslider_controls');
        register_setting('ng_3dfluxslider_options', 'ng_3dfluxslider_pagination');
        register_setting('ng_3dfluxslider_options', 'ng_3dfluxslider_delay');
        add_settings_section('ng_3dfluxslider_general_options', 'General Options', array(&$this,'ng_3dfluxslider_general_options_code'), 'ng_3dfluxslider_general_options');
        add_settings_field('ng_3dfluxslider_transitions', 'Transitions', array(&$this,'ng_3dfluxslider_transitions_code'), 'ng_3dfluxslider_general_options', 'ng_3dfluxslider_general_options');
        add_settings_field('ng_3dfluxslider_caption', 'Show Caption', array(&$this,'ng_3dfluxslider_caption_code'), 'ng_3dfluxslider_general_options', 'ng_3dfluxslider_general_options');
        add_settings_field('ng_3dfluxslider_controls', 'Next/Back Controls', array(&$this,'ng_3dfluxslider_controls_code'), 'ng_3dfluxslider_general_options', 'ng_3dfluxslider_general_options');
        add_settings_field('ng_3dfluxslider_pagination', 'Pagination', array(&$this,'ng_3dfluxslider_pagination_code'), 'ng_3dfluxslider_general_options', 'ng_3dfluxslider_general_options');
        add_settings_field('ng_3dfluxslider_delay', 'Delay between transitions', array(&$this,'ng_3dfluxslider_delay_code'), 'ng_3dfluxslider_general_options', 'ng_3dfluxslider_general_options');

    }

    function ng_3dfluxslider_general_options_code() {
        echo '<p>' . _e("This section allow you to configure NextGen 3D Flux Slider Options") . '</p>';
    }

    function ng_3dfluxslider_caption_code() {
        echo 'Whether or not to show a caption bar. Captions are the title attribute of images<br/>';
        echo '<label><input id="ng_3dfluxslider_caption1" name="ng_3dfluxslider_caption" type="radio" value="0" ' . checked(get_option("ng_3dfluxslider_caption"), 0, false ) . ' /> No,</label><br />';
        echo '<label><input id="ng_3dfluxslider_caption2" name="ng_3dfluxslider_caption" type="radio" value="1" ' . checked( get_option("ng_3dfluxslider_caption"), 1, false ) . ' /> Yes, please</label><br />';
    }

    function ng_3dfluxslider_controls_code() {
        echo 'Whether or not to show a next/prev controls<br/>';
        echo '<label><input id="ng_3dfluxslider_controls1" name="ng_3dfluxslider_controls" type="radio" value="0" ' . checked(get_option("ng_3dfluxslider_controls"), 0, false ) . ' /> No Controls</label><br />';
        echo '<label><input id="ng_3dfluxslider_controls2" name="ng_3dfluxslider_controls" type="radio" value="1" ' . checked( get_option("ng_3dfluxslider_controls"), 1, false ) . ' /> Default Controls</label><br />';
    }
    function ng_3dfluxslider_delay_code() {
        echo 'The number of seconds to wait between image transitions e.g. 5, 6<br/>';
        echo '<input id="ng_3dfluxslider_delay" name="ng_3dfluxslider_delay" type="text" value="' . get_option("ng_3dfluxslider_delay") . '" /> <br/>';
    }

    function ng_3dfluxslider_transitions_code() {
        $saved_trans = get_option("ng_3dfluxslider_transitions");
        $trans2d = array('bars','blinds','blocks','blocks2','concentric','slide','warp','zip');
        $trans3d = array('bars3d'=>'3D Bars','blinds3d'=>'3D Blinds','cube'=>'Cube','tiles3d'=>'3D Tiles','turn'=>'Turn');
        $i=1;
        echo '<div style="width:200px;float:left"><strong>2D Transitions</strong><br/>';
        foreach($trans2d as $trans){
            echo '<label><input id="ng_3dfluxslider_transitions'.$i++.'" name="ng_3dfluxslider_transitions[]" type="checkbox" value="'.$trans.'" ';
            if(in_array($trans,$saved_trans))
                    echo 'checked="checked" ';
            echo ' /> '.ucfirst($trans).'</label><br />';
        }
        echo '</div>';

        echo '<div style="width:200px;float:left"><strong>3D Transitions</strong><br/>';
        foreach($trans3d as $trans=>$title){
            echo '<label><input id="ng_3dfluxslider_transitions'.$i++.'" name="ng_3dfluxslider_transitions[]" type="checkbox" value="'.$trans.'" ';
            if(in_array($trans,$saved_trans))
                    echo 'checked="checked" ';
            echo ' /> '.$title.'</label><br />';
        }
        echo '</div>';
}

    function ng_3dfluxslider_pagination_code() {
        echo 'Whether or not to show a pagination control for manually selecting the image to show<br/>';
        echo '<label><input id="ng_3dfluxslider_pagination_code1" name="ng_3dfluxslider_pagination" type="radio" value="0" ' . checked(get_option("ng_3dfluxslider_pagination"), 0, false ) . ' /> No Pagination</label><br />';
        echo '<label><input id="ng_3dfluxslider_pagination_code2" name="ng_3dfluxslider_pagination" type="radio" value="1" ' . checked( get_option("ng_3dfluxslider_pagination"), 1, false ) . ' /> Bullet Pagination</label><br />';
    }

    function admin_output() {
        ?>
        <div id="dashboard-widgets" class="metabox-holder">
            <div id="post-body">
                <div id="dashboard-widgets-main-content">
                    <div class="postbox-container" id="main-container" style="width:75%;">
                        <h2>NextGen 3D and 2D Animated Flux Slider Options</h2>
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('ng_3dfluxslider_options');
                            do_settings_sections('ng_3dfluxslider_general_options');
                            ?>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
                        </form>
                    </div>
                    <div class="postbox-container" id="side-container" style="width:24%;">
                        <div id="right-sortables" class="meta-box-sortables ui-sortable">
                            <div id="ngg_meta_box" class="postbox ">
                                <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Need Support ?</span></h3>
                                <div class="inside">
                                    <p>Any kind of contribution would be highly appreciated. Thanks!</p>
                                    <ul>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px; background:transparent url(http://wpdevsnippets.com/plugin-images/icon_wordpress_blue.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://wordpress.org/extend/plugins/nextgen-3d-flux-slider-template/" target="_blank">Visit the WordPress plugin homepage</a>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px;line-height: 30px; background:transparent url(http://wpdevsnippets.com/plugin-images/icon_doc_find.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://wpdevsnippets.com/nextgen-3d-2d-animated-flux-slider-template/" target="_blank"><strong>Visit the Documentation page</strong></a>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px;line-height: 30px; background:transparent url(http://wpdevsnippets.com/plugin-images/icon_person_help.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://wordpress.org/support/plugin/nextgen-3d-flux-slider-template" target="_blank">Report a bug </a>
                                         </li>
                                    </ul>
                                    </div>
                                </div>
                            </div>
                            <div id="ngg_meta_box" class="postbox ">                                
                                <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Do you like this Plugin?</span></h3>
                                <div class="inside">
                                    <p>This plugin is developed with the support of <a href="http://wpdevsnippets.com" target="_blank">WPDevSnippets</a>. Please contribute some of your time to appreciate us and spread the word.</p>
                                    <ul>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://wordpress.org/extend/plugins/nextgen-3d-flux-slider-template/" target="_blank"><strong>Give it a good rating on WordPress.org</strong></a>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                             <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2Fwpdevsnippets&amp;send=false&amp;layout=button_count&amp;width=150&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=recommend&amp;height=21&amp;appId=493527070687256" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:21px;" allowTransparency="true"></iframe>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                            <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://wpdevsnippets.com/nextgen-3d-2d-animated-flux-slider-template/" data-text="Beautiful and Customizable 3D Animated CSS Slider Plugin from WPDevSnippets.com" data-related="WPDevSnippets">Tweet</a>
                                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>                                         </li>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                            <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
                                            <div class="g-plusone" data-size="medium" data-href="http://wpdevsnippets.com"></div>                                         
                                         </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="ngg_meta_box" class="postbox ">
                                
                                <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Appreciate by Cash?</span></h3>
                                <div class="inside">
                                    <p>This plug-in consumed several of our precious hours, if you use and like it, please donate a token of your appreciation!</p>
                                    <div class="social" style="text-align:center;margin:15px 0 10px 0;">
                                        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DG2R3ZVH44FE4" target="_blank">
                                        <img src="http://wpdevsnippets.com/plugin-images/btn_donate.gif" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }

}


?>