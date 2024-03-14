<?php

/* UPDATE/INSERT VALUES */


if(isset($_POST['Entry_Field_Content']) AND empty($_POST['wp_user_meta_entries'])){

    $_POST = cg1l_sanitize_post($_POST);

    foreach($_POST['Entry_Field_Content'] as $id => $value){

        $wpdb->update(
            "$tablename_contest_gal1ery_create_user_entries",
            array('Field_Content' => sanitize_text_field(htmlentities($value, ENT_QUOTES))),
            array('id' => $id),
            array('%s'),
            array('%d')
        );

	}
}else{

    if(!empty($_POST['wp_user_meta_entries'])){
        $_POST = cg1l_sanitize_post($_POST);
        foreach ($_POST as $key => $value){
            if(strpos($key,'cg_custom_field_id_')!==false){
                update_user_meta( $wpUserId, $key, $value);
            }
        }
    }

}


/* UPDATE/INSERT VALUES --- END */


?>