<?php 

function CPIW_CommonQuery($pincode){

    global $wpdb;

    $tablename=$wpdb->prefix.'cpiw_pincode';

    $cntPincode = "SELECT count(*) as count FROM {$tablename} where pincode='".$pincode."'";

    return $cntPincode;

}

function CPIW_SavePincodeOption() {

    global $wpdb;

    $tablename=$wpdb->prefix.'cpiw_pincode';

    /* add codes */
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'cpiw_add_postcode') {

        if(!isset( $_POST['CPIW_add_pincode_field'] ) || !wp_verify_nonce( $_POST['CPIW_add_pincode_field'], 'CPIW_add_pincode_action' )) {
            
            echo 'Sorry, your nonce did not verify.';
            exit;

        } else {

            $cpiwpincode = sanitize_text_field( $_REQUEST['cpiwpincode']);
            $cpiwcity = sanitize_text_field( $_REQUEST['cpiwcity']);
            $cpiwstate = sanitize_text_field( $_REQUEST['cpiwstate']);
            $cpiwshipping = sanitize_text_field( $_REQUEST['cpiwshipping']);
            $cpiwddate = sanitize_text_field( $_REQUEST['cpiwddate']);
            

            if(isset($_POST['cpiwcod']) && $_POST['cpiwcod'] != '') {
            	$cpiwcod = sanitize_text_field($_POST['cpiwcod']);
            } else {
            	$cpiwcod = '0';
            }

            $PincodeCount = CPIW_CommonQuery($cpiwpincode);

            $pincode_record = $wpdb->get_results($PincodeCount, OBJECT);

            if($pincode_record[0]->count == 0) {

                if(!empty($cpiwpincode) && !empty($cpiwcity) && !empty($cpiwstate)  ) {

                    $data=array(
                        'pincode' 	     => $cpiwpincode,
                        'city' 	         => $cpiwcity, 
                        'state' 	     => $cpiwstate,
                        'ddate' 	     => $cpiwddate,
                        'ship_amount'    => $cpiwshipping,
                        'caseondilvery'	 => $cpiwcod
                    );

                    $wpdb->insert( $tablename, $data);
                    wp_redirect(admin_url('admin.php?page=my-add-pincode-submenu-page&add=success'));
                    exit;
                }
            } else {
                    wp_redirect(admin_url('admin.php?page=my-add-pincode-submenu-page&add=exists&cpiwpincode='.$cpiwpincode.'&cpiwcity='.$cpiwcity.'&cpiwstate='.$cpiwstate.'&cpiwddate='.$cpiwddate.'&cpiwshipping='.$cpiwshipping.'&cpiwcod='.$cpiwcod));
                    exit;
            }
        }
    }


    // update pincode
    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'cpiw_update_postcode') {

        if(!isset($_POST['CPIW_update_pincode_field']) || !wp_verify_nonce($_POST['CPIW_update_pincode_field'],'CPIW_update_pincode_action' )) {
            
            echo 'Sorry, your nonce did not verify.';
            exit;

        } else {

            $pincode_exists = 'false';
            $id = sanitize_text_field( $_REQUEST['pincodeid']);
            $pincode = sanitize_text_field( $_REQUEST['cpiwpincode']);
            $city = sanitize_text_field( $_REQUEST['cpiwcity']);
            $state = sanitize_text_field( $_REQUEST['cpiwstate']);
            $ddate = sanitize_text_field( $_REQUEST['cpiwddate']);
            $ship_amount = sanitize_text_field( $_REQUEST['cpiwshipping']);

            if(isset($_POST['cpiwcod']) && $_POST['cpiwcod'] != '') {
                $cod = sanitize_text_field($_POST['cpiwcod']);
            } else {
                $cod = '0';
            }

            $cntSQL = "SELECT * FROM {$tablename} where id='".$id."'";

            $cpiwrecord = $wpdb->get_results($cntSQL, OBJECT);

            $PincodeCount = CPIW_CommonQuery($pincode);

            $cpiwrecord_new = $wpdb->get_results($PincodeCount, OBJECT);

            $current_pincode = $cpiwrecord[0]->pincode;

            $count_new = $cpiwrecord_new[0]->count;

            if($pincode != $current_pincode) {
                if($count_new > 0 ) {
                    $pincode_exists = 'true';
                }
            }


            if($pincode_exists == 'false') {
                if(!empty($pincode) && !empty($city) && !empty($state) ) {
                    $data=array(
                        'pincode'        => $pincode,
                        'city'           => $city, 
                        'state'          => $state,
                        'ddate'          => $ddate,
                        'ship_amount'    => $ship_amount,
                        'caseondilvery'  => $cod,
                    );
                    $condition=array(
                        'id'=>$id
                    );

                    $wpdb->update($tablename, $data, $condition);
                    wp_redirect(admin_url('admin.php?page=my-add-pincode-submenu-page&action=pincode_edit&id='.$id.'&update=success'));
                    exit;
                }
            } else {
                wp_redirect(admin_url('admin.php?page=my-add-pincode-submenu-page&action=pincode_edit&id='.$id.'&update=exists'));
                exit;
            }
        }
    }



    //Import pincode
    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'cpiw_import_postcodes') {

        if(!isset( $_POST['CPIW_add_pincode_field'] ) || !wp_verify_nonce( $_POST['CPIW_add_pincode_field'], 'CPIW_add_pincode_action' )) {
            
            echo 'Sorry, your nonce did not verify.';
            exit;

        } else {

            if(isset($_POST['pincodeimport'])) {

                // File extension
                $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);

                // If file extension is 'csv'
                if(!empty($_FILES['import_file']['name']) && $extension == 'csv') {

                    $totalInserted = 0;
             
                    // Open file in read mode
                    $csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');
                    fgetcsv($csvFile); // Skipping header row

                    // Read file
                    while(($csvData = fgetcsv($csvFile)) !== FALSE) {
                        $csvData = array_map("sanitize_text_field", $csvData);

                        // Assign value to variables
                        $pincode     = trim($csvData[0]);
                        $city        = trim($csvData[1]);
                        $state       = trim($csvData[2]);
                        $ddate       = trim($csvData[3]);
                        $ship_amount = trim($csvData[4]);
                        $cod         = trim($csvData[5]);

                        $PincodeCount = CPIW_CommonQuery($pincode);

                        $record = $wpdb->get_results($PincodeCount, OBJECT);

                        if($record[0]->count == 0) {
                            // Check if variable is empty or not
                            if($pincode!= ""  &&  $city!=""  &&  $state!=""  &&  $ddate!= ""  &&  $ship_amount!= "") {
                                // Insert Record
                                $wpdb->insert($tablename, array(
                                   'pincode'        => $pincode,
                                   'city'           => $city,
                                   'state'          => $state,
                                   'ddate'          => $ddate,
                                   'ship_amount'    => $ship_amount,
                                   'caseondilvery'  => $cod
                                ));
                                if($wpdb->insert_id > 0) {
                                   $totalInserted++;
                                }
                            }
                        }
                    }
                    wp_redirect(admin_url('admin.php?page=my-add-pincode-submenu-page&import=success&records='.$totalInserted));
                    exit;
                } else {
                    wp_redirect(admin_url('admin.php?page=my-add-pincode-submenu-page&import=error'));
                    exit;
                }
            }
        }

    }


    //delete pincode
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "cpiw_delete") {
        if( wp_verify_nonce( $_GET['_wpnonce'], 'my_nonce' ) ) {
            $pincode = sanitize_text_field($_REQUEST['id']);
            $sql = "DELETE FROM $tablename WHERE id='".$pincode."'";
            $wpdb->query($sql);
            wp_redirect(admin_url('admin.php?page=my-list-pincode-submenu-page&delete=success'));
            exit;
        } else {
            echo 'Sorry, your nonce did not verify.';
            exit;
        }
    }
}

add_action( 'init',  'CPIW_SavePincodeOption' );


add_action( 'init',  'cpiw_SaveOption');
function cpiw_SaveOption(){
    if( current_user_can('administrator') ) {
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'cpiw_save_option') {
        if(!isset( $_POST['cpiw_meta_save_nounce'] ) || !wp_verify_nonce( $_POST['cpiw_meta_save_nounce'], 'cpiw_meta_save' ) ) {
        print 'Sorry, your nonce did not verify.';
            exit;
        } else {
        $isecheckbox = array(
          'cpiw_enable',
          'cpiw_dateshow',
          'cpiw_codshow',
          'cpiw_checkoutpincodevalidation',
          'cpiw_poupshow',
        );

        foreach ($isecheckbox as $key_isecheckbox => $value_isecheckbox) {
          if(!isset($_REQUEST['cpiw_comman'][$value_isecheckbox])){
            $_REQUEST['cpiw_comman'][$value_isecheckbox] ='no';
          }
        }

        foreach ($_REQUEST['cpiw_comman'] as $key_cpiw_comman => $value_cpiw_comman) {
          update_option($key_cpiw_comman, sanitize_text_field($value_cpiw_comman), 'yes');
        }   

        wp_redirect( admin_url( '/admin.php?page=pin-code&message=success' ) );
        exit;
        }
        }
    }   
}



