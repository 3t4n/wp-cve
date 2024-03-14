<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

add_action('admin_menu', 'fsi_admin_menu');

function fsi_admin_menu() {
    add_options_page('FullScreen Backgrounds', 'FullScreen Backgrounds', 'manage_options', 'fsi-fullScreen-backgrounds', 'fsi_fullScreen_backgrounds');
    add_action('admin_init', 'fsi_register_settings');
}

function fsi_register_settings() {
    register_setting('fsi_fullScreen_backgrounds_options', 'fsi_images');
    register_setting('fsi_fullScreen_backgrounds_options', 'fsi_random');
    register_setting('fsi_fullScreen_backgrounds_options', 'fsi_overlay');
    register_setting('fsi_fullScreen_backgrounds_options', 'fsi_opacity');
    register_setting('fsi_fullScreen_backgrounds_options', 'fsi_animation');
    register_setting('fsi_fullScreen_backgrounds_options', 'fsi_animation_delay');
    register_setting('fsi_fullScreen_backgrounds_options', 'fsi_animation_duration');
    register_setting('fsi_fullScreen_backgrounds_options', 'fsi_display_on');

    add_settings_section('fsi_fullscreen_backgrounds_general_options', 'General Options', 'fsi_fullscreen_backgrounds_general_options', 'fsi_fullscreen_backgrounds_general_options');
    
    add_settings_field('fsi_images', 'Select Background Images', 'fsi_images', 'fsi_fullscreen_backgrounds_general_options', 'fsi_fullscreen_backgrounds_general_options');
    add_settings_field('fsi_display_on', 'Display On', 'fsi_display_on', 'fsi_fullscreen_backgrounds_general_options', 'fsi_fullscreen_backgrounds_general_options');
    add_settings_field('fsi_random', 'Order Randomly', 'fsi_random', 'fsi_fullscreen_backgrounds_general_options', 'fsi_fullscreen_backgrounds_general_options');
    add_settings_field('fsi_overlay', 'Add Overlay', 'fsi_overlay', 'fsi_fullscreen_backgrounds_general_options', 'fsi_fullscreen_backgrounds_general_options');
    add_settings_field('fsi_opacity', 'Image Opacity', 'fsi_opacity', 'fsi_fullscreen_backgrounds_general_options', 'fsi_fullscreen_backgrounds_general_options');
    add_settings_field('fsi_animation', 'Enable Slideshow', 'fsi_animation', 'fsi_fullscreen_backgrounds_general_options', 'fsi_fullscreen_backgrounds_general_options');
    add_settings_field('fsi_animation_duration', 'Animation Duration', 'fsi_animation_duration', 'fsi_fullscreen_backgrounds_general_options', 'fsi_fullscreen_backgrounds_general_options');
    add_settings_field('fsi_animation_delay', 'Animation Delay', 'fsi_animation_delay', 'fsi_fullscreen_backgrounds_general_options', 'fsi_fullscreen_backgrounds_general_options');

}

function fsi_fullScreen_backgrounds_general_options() {
    echo '<p>' . _e("This section allow you to set up background images and change configurations.") . '</p>';
}
function fsi_display_on(){
    $display_on = array(
      'frontpage'  => 'FrontPage',
      'home'  => 'Blog Homepage',
      'pages'  => 'Pages',
      'posts'  => 'Posts',
      'category'  => 'Category Pages',
      'tag'  => 'Tag Pages',
	  'archive' => 'Archive Pages'
    );
    $fsi_display_on = get_option('fsi_display_on');
    if($fsi_display_on == false)
        $fsi_display_on = array();
    foreach ($display_on as $key => $value) {
        $checked = '';
        if(in_array($key, $fsi_display_on))
                $checked = ' checked ="checked" ';
        echo '<label><input id="fsi_display_on" name="fsi_display_on[]" type="checkbox" value="'.$key.'" ' . $checked . ' /> '.$value.'</label><br/>';
    }
    
}
function fsi_overlay() {
    echo '<label><input id="fsi_overlay1" name="fsi_overlay" type="radio" value="0" ' . checked(get_option("fsi_overlay"), '0', false ) . ' /> No</label><br />';
    echo '<label><input id="fsi_overlay2" name="fsi_overlay" type="radio" value="1" ' . checked(get_option("fsi_overlay"), '1', false ) . ' /> Yes</label>';
}
function fsi_random() {
    echo '<label><input id="fsi_random1" name="fsi_random" type="checkbox" value="1" ' . checked(get_option("fsi_random"), '1', false ) . ' /> Yes</label>';
}
function fsi_images() {
    $fsi_images = get_option("fsi_images");
    for($i=0; $i<FSI_IMAGES_ALLOWED; $i++)
        echo ($i+1).'. <input id="fsi_images_'.$i.'_field" name="fsi_images[]" type="text" value="' . $fsi_images[$i] . '" size=80 /> 
            <input id="fsi_images_'.$i.'" class="upload_buttons" type="button" value="Upload/Select" /><br/>';
    echo 'Set URL to the images. Images of dimension 1400x1100 or more are preferred';
}
function fsi_opacity() {
    echo '<input id="fsi_opacity" name="fsi_opacity" type="text" value="' . get_option("fsi_opacity") . '" />% Opacity in percentage. Valid value is from 1 to 100<br />';
}
function fsi_animation() {
    echo '<label><input id="fsi_animation" name="fsi_animation" type="checkbox" value="1" ' . checked(get_option("fsi_animation"), '1', false ) . ' /> Yes</label>';
}
function fsi_animation_duration() {
    echo '<input id="fsi_animation_duration" name="fsi_animation_duration" type="text" value="' . get_option("fsi_animation_duration") . '" size=25 /> miliseconds e.g. 4, 5, 6<br />';
}

function fsi_animation_delay() {
    echo '<input id="fsi_animation_delay" name="fsi_animation_delay" type="text" value="' . get_option("fsi_animation_delay") . '" size=25 /> seconds e.g. 4, 5<br />';
}

function fsi_admin_scripts() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_register_script('fsi-script', FSI_PLUGIN_URL.'/js/script.js', array('jquery','media-upload','thickbox'));
    wp_enqueue_script('fsi-script');
}

function fsi_admin_styles() {
    wp_enqueue_style('thickbox');
}

if (isset($_GET['page']) && $_GET['page'] == 'fsi-fullScreen-backgrounds') {
    add_action('admin_print_scripts', 'fsi_admin_scripts');
    add_action('admin_print_styles', 'fsi_admin_styles');
}

function fsi_fullScreen_backgrounds() {
    ?>
        <div id="dashboard-widgets" class="metabox-holder">
            <div id="post-body">
                <div id="dashboard-widgets-main-content">
                    <div class="postbox-container" id="main-container" style="width:75%;">
        <h2>Full Screen Background Image Slideshow Options</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('fsi_fullScreen_backgrounds_options');
            do_settings_sections('fsi_fullscreen_backgrounds_general_options');
            ?>
            <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
        </form>
    </div>
                    </div>
                    <div class="postbox-container" id="side-container" style="width:24%;">
                        <div id="right-sortables" class="meta-box-sortables ui-sortable">
                            <div id="ngg_meta_box" class="postbox ">                                
                                <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Need Support?</span></h3>
                                <div class="inside">
                                    <p>We will get back to within 24 hours. Have doubts? Try us!</p>
                                    <ul>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px; background:transparent url(http://wpdevsnippets.com/plugin-images/icon_wordpress_blue.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://wordpress.org/extend/plugins/full-screen-page-background-image-slideshow" target="_blank">Visit the WordPress plugin homepage</a>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px;line-height: 30px; background:transparent url(http://wpdevsnippets.com/plugin-images/icon_doc_find.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://wpdevsnippets.com/full-screen-page-background-image-slideshow-plugin" target="_blank"><strong>Visit the Documentation page</strong></a>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px;line-height: 30px; background:transparent url(http://wpdevsnippets.com/plugin-images/icon_person_help.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://wordpress.org/support/plugin/full-screen-page-background-image-slideshow" target="_blank">Report a bug or suggest a feature </a>
                                         </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="ngg_meta_box" class="postbox ">                                
                                <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Do you like this Plugin?</span></h3>
                                <div class="inside">
                                    <p>This plugin is developed with the support of <a href="http://wpdevsnippets.com" target="_blank">WPDevSnippets</a>. Please contribute some of your time to appreciate us and spread the word.</p>
                                    <ul>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://wordpress.org/extend/plugins/full-screen-page-background-image-slideshow" target="_blank">Give it a good rating on WordPress.org</a>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                             <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwpdevsnippets.com%2Ffull-screen-page-background-image-slideshow-plugin%2F&amp;width=450&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false&amp;appId=493527070687256" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                            <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://wpdevsnippets.com/full-screen-page-background-image-slideshow-plugin/" data-text="Cool Full Size Background Slideshow Plugin from WPDevSnippets.com" data-related="WPDevSnippets">Tweet</a>
                                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>                                         </li>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                            <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
                                            <div class="g-plusone" data-size="medium" data-href="http://wpdevsnippets.com/full-screen-page-background-image-slideshow-plugin"></div>                                         
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


?>