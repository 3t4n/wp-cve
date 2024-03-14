<?php

add_action('admin_enqueue_scripts', 'cg_options_tabcontent_v10' );

if(empty($_POST['chooseAction1'])){
    $_POST['chooseAction1'] = false;
}

if(!empty($_POST['changeSize']) OR !empty($_GET['reset_users_votes']) OR !empty($_GET['reset_users_votes2']) OR !empty($_GET['reset_votes']) OR !empty($_GET['reset_votes2']) OR !empty($_GET['reset_admin_votes']) OR !empty($_GET['reset_admin_votes2'])){
    require_once('v10-admin/options/change-options-and-sizes.php');
}


//------------------------------------------------------------
// ----------------------------------------------------------- Corrections and Improvements ----------------------------------------------------------
//------------------------------------------------------------

if (!empty($_GET['corrections_and_improvements']) OR !empty($_POST['corrections_and_improvements'])) {

    require_once('v10-admin/options/corrections-and-improvements.php');

}

//------------------------------------------------------------
// ----------------------------------------------------------- Change options of gallery ----------------------------------------------------------
//------------------------------------------------------------

if (!empty($_GET['edit_options']) OR !empty($_POST['edit_options']) OR !empty($_POST['changeSize']) OR !empty($_GET['reset_users_votes'])) {
//wp_enqueue_script( 'jquery.minicolors', plugins_url( '/js/color-picker/jquery.minicolors.js', __FILE__ ), array('jquery'), false, true );
//wp_enqueue_script( 'jquery.minicolors.min', plugins_url( '/js/color-picker/jquery.minicolors.min.js', __FILE__ ), array('jquery'), false, true );
//wp_enqueue_script( 'jquery_frontend_color_picker', plugins_url( '/js/color-picker/jquery_frontend_color_picker.js', __FILE__ ), array('jquery'), false, true );

    add_filter( 'mce_css', 'cg_plugin_mce_css_to_add' );

    require_once('v10-admin/options/edit-options.php');

}

//------------------------------------------------------------
// ----------------------------------------------------------- Change options of gallery ----------------------------------------------------------
//------------------------------------------------------------

if (!empty($_GET['show_votes']) && !empty($_GET['image_id']) && !empty($_GET['option_id'])){

    require_once('v10-admin/votes/show-votes.php');

}


//------------------------------------------------------------
// ----------------------------------------------------------- Rotate Image ----------------------------------------------------------
//------------------------------------------------------------


if (!empty($_GET['cg_image_rotate'])){


    require_once('v10-admin/gallery/rotate-image.php');
}

//------------------------------------------------------------
// ----------------------------------------------------------- Create an Upload Form ----------------------------------------------------------
//------------------------------------------------------------


if (!empty($_GET['define_upload'])) {

    add_filter( 'mce_css', 'cg_plugin_mce_css_to_add' );
    require_once('v10-admin/upload/create-upload.php');
}

//------------------------------------------------------------
// ----------------------------------------------------------- Create an User reg Form ----------------------------------------------------------
//------------------------------------------------------------


if (!empty($_GET['create_user_form'])) {

    add_filter( 'mce_css', 'cg_plugin_mce_css_to_add' );
    require_once('v10-admin/users/admin/registry/create-registry.php');
}

//------------------------------------------------------------
// ----------------------------------------------------------- WordPress users management ----------------------------------------------------------
//------------------------------------------------------------


if (!empty($_GET['users_management'])) {
    include('v10-admin/users/admin/users/management.php');

}


//------------------------------------------------------------
// ----------------------------------------------------------- Change email text for informing users ----------------------------------------------------------
//------------------------------------------------------------

if (!empty($_POST['inform_user'] ) OR !empty($_GET['inform_user'])) {

    require_once('v10-admin/email/change-text-inform-user.php');

}


//------------------------------------------------------------
// ----------------------------------------------------------- User per Email informieren oder nicht informieren Ã¤ndern/ SPEICHERN ----------------------------------------------------------
//------------------------------------------------------------

//if (@$_POST['submit'] == true AND @$_POST['informId'] == true) {

//require_once('v10/v10-admin/email/inform-user.php');

//}


//------------------------------------------------------------
// ----------------------------------------------------------- Reset informed for all pictures ----------------------------------------------------------
//------------------------------------------------------------

if(!empty($_POST['reset_all'])){
    require_once('v10-admin/gallery/reset_all.php');
}

//------------------------------------------------------------
// ----------------------------------------------------------- Edit certain galery ----------------------------------------------------------
//------------------------------------------------------------

if (!empty($_GET['edit_gallery'])) {

    //------------------------------------------------------------
    // ----------------------------------------------------------- Hochgeladene Bilder anzeigen oder nicht anzeigen Ã¤ndern und Comments Ã¤ndern oder Informieren oder Informierte reseten SPEICHERN ----------------------------------------------------------
    //------------------------------------------------------------

    if (!empty($_POST['submit']) AND !empty($_POST['changeGalery']) AND !empty($_POST['chooseAction1'])) {


        //echo "change";
        require_once('v10-admin/gallery/change-gallery/0_change-gallery.php');
        require_once('v10-admin/gallery/gallery.php');

    }

    //------------------------------------------------------------
    // ----------------------------------------------------------- Edit certain galery ----------------------------------------------------------
    //------------------------------------------------------------

    else{

        require_once('v10-admin/gallery/gallery.php');

    }


}



//------------------------------------------------------------
// ----------------------------------------------------------- Kommentare eines einzelnen Bildes anzeigen ----------------------------------------------------------
//------------------------------------------------------------

if(!empty($_GET['show_comments'])){
    require_once('v10-admin/gallery/show-comments.php');

}




?>