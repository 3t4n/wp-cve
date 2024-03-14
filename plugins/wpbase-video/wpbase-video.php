<?php
/*
  Plugin Name: Wordpress Video Tube Plugin
  Plugin URI: http://www.baseapp.com
  Description: Video hosting plugin used to create wordpress based video sites quickly and easily. Upload videos from any site or integrate with bulk importers.  
  Author: Vikrant Datta
  Version: 2.1.1
  Author URI: http://www.baseapp.com/
 */
 

 
 
include_once (dirname(__FILE__) . '/controller/controller.php');
include_once (plugin_dir_path(__FILE__) . '/model/class.Videoba.php');

function call() {
    $vid = new Videoba();
    if ($_GET['page'] == 'ba-settings') {
        ?> <div class="icon32" id="icon-edit">
        </div><h1><?php _e( 'Settings Page','Video Plugin' );?></h1> <?php
        $vid->vidSettings();
    } else if (isset($_POST['editpost']))           //Update button pressed
        $vid->manualSubmit1();


    else if ($_GET['page'] == 'ba-submit') {     //Adding new video
        $vid->manualSubmit1();
    } else if ($_GET['page'] == 'Video') {

        if ($_GET['mode'] == 'del') {           //Delete button pressed
            $post_tmp_del = get_post($_GET['id']);
            global $current_user;
            $user_id = $post_tmp_del->post_author;
            $current_auth = $current_user->ID;

            if (($current_auth == $user_id) || ($current_user->caps['administrator'] == '1')) {
                wp_delete_post($_GET['id'], TRUE);
                $vid->show_main();
            } else {
                ?>
                <div class="wrap"><h2>&nbsp</h2>                <!-- Success message -->
                    <div class="updated" id="message" style="background-color: rgb(255, 251, 204);">
                        <p><strong>Illegal Delete request.</strong>

                    </div>
                </div><?php
            }
        } else
        if ($_GET['mode'] == 'edit') {      //Edit  button pressed
            include_once (plugin_dir_path(__FILE__) . '/views/editform.php');       //printing edit form
        } else {
            $vid->show_main();
        }
    }
}
?>