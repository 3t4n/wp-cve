<?php

/*echo "<pre>";
print_r(debug_backtrace(3));
echo "</pre>";
die;*/

// PLUGIN VERSION CHECK HERE

contest_gal1ery_db_check();

global $wp_version;
$sanitize_textarea_field = ($wp_version<4.7) ? 'sanitize_text_field' : 'sanitize_textarea_field';

$cgProVersion = contest_gal1ery_key_check();
if(!$cgProVersion){
    $cgProFalse = 'cg-pro-false';
    $cgProFalseContainer = 'cg-pro-false-container';
    $cgProFalseText = '<span class="cg-pro-false-text">(PRO)</span>';
    $cgProVersionLink = "<a href='https://www.contest-gallery.com/pro-version/' class='cg-get-pro-link' target='_blank'>Get PRO</a><br/>";
}else{
    $cgProFalse = '';
    $cgProFalseText = '';
    $cgProFalseContainer = '';
    $cgProVersionLink = 'You are using PRO version';
}

// avoiding XSS script like for option_id && image_id
// see vulnerability report patchscan.com
//http://localhost/wp-admin/admin.php?page=contest-gallery%2Findex.php#users_management=true&option_id=a"><script>alert(2)</script>
//https://patchstack.com/database/report-preview/18b2d6c1-1bdf-4a1d-a7f4-fc056d466716
if(isset($_GET['option_id'])){
    $_GET['option_id'] = absint($_GET['option_id']);
}
if(isset($_GET['image_id'])){
    $_GET['image_id'] = absint($_GET['image_id']);
}

if(!empty($_POST['option_id'])){
    $_GET['option_id'] = absint($_POST['option_id']);
}

if(!empty($_POST['edit_gallery'])){
    $_GET['edit_gallery'] = $_POST['edit_gallery'];
}

/**###NORMAL###**/
// here is good, because $_GET['option_id'] must already exists here
cg_reset_to_normal_version_options_if_required();
/**###NORMAL-END###**/

// include always to get the contained ajax action later
include('index-hash-backend.php');

// if is not ajax it does not execute further not necessary processing
if(!empty($isAjaxCall)){

//------------------------------------------------------------
// ----------------------------------------------------------- Neue Galerie kreieren ----------------------------------------------------------
//------------------------------------------------------------

//wpmadd =(Damit keine neue Galerie kreiert wird wenn eine gerade kreiert wurde und bilder sofort hochgeladen wurden)
    if((!empty($_POST['cg_create']) OR !empty($_POST['cg_copy'])) AND !empty($_GET['edit_gallery']) AND empty($_GET['wpmadd'])){

        $dbVersion = intval(get_option( "p_cgal1ery_db_version" ));

        if(empty($dbVersion)){
            $dbVersion = 10;
        }

        //  $time_pre = microtime(true)*1000000;
        //   $time_pre = time();
        //   var_dump('time1');

        $uploadFolder = wp_upload_dir();
        if(!file_exists($uploadFolder['basedir'] . '/contest-gallery/cg-copying-gallery.txt')){
            require_once('v10/v10-admin/create-gallery.php');
        };

        // $time_post = microtime(true)*1000000;
        //   $time_post = time();
        //  $exec_time = ($time_post - $time_pre);
        //    var_dump('time');
        //var_dump($exec_time);

        if($dbVersion>=10){

            require_once('v10/v10-admin/gallery/gallery.php');

        }

        return;

    }


    global $wpdb;
    $tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";

    if(!empty($_GET['option_id'])){
        $gid = intval($_GET['option_id']);
        $dbVersion = intval($wpdb->get_var( "SELECT Version FROM $tablenameOptions WHERE id=$gid"));
    }



// ----------------------------------------------------------- Neue Galerie lÃ¶schen ----------------------------------------------------------
//------------------------------------------------------------
    if(!empty($_GET['option_id']) AND !empty($_POST['cg_delete_gallery'])){

        require_once('delete-gallery.php');
        require_once('main-menu.php');

        return;

    }


//------------------------------------------------------------
// ----------------------------------------------------------- AuswahlmenÃ¼ zum Anzeigen und Erstellen von Galerien ----------------------------------------------------------
//------------------------------------------------------------

    if(empty($_GET['option_id']) and empty($_POST['option_id'])){

//require('css/style.php');

        //add_action( 'plugins_loaded', 'contest_gal1ery_db_check' );
        require_once('main-menu.php');

        require_once('v10/v10-admin/export/controller.php');
        add_action('cg_remove_not_required_coded_csvs','cg_remove_not_required_coded_csvs');
        do_action('cg_remove_not_required_coded_csvs');

        return;

    }


    if($dbVersion>=10){

        require_once('v10/include-conditions-v10.php');
        return;
    }

    if(!empty($_GET['option_id']) && $dbVersion<10){
        require_once('prev10/prev10-admin/gallery/gallery.php');
        return;
    }



}
