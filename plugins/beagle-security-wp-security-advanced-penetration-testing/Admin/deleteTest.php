<?php

//GPL license

include sanitize_file_name('gplLicense.php');

/*delete the application only after the verification*/
function Beagle_WP_delete_Test() {

  global $wpdb;
  global $deleteID;
  $Beagle_WP_scan_table = $wpdb->prefix."beagleScanData";
  $result =$wpdb->get_results($wpdb->prepare("SELECT * FROM $Beagle_WP_scan_table "));
  foreach ( $result as $print ){
    $deleteID = $print->id;
  }
  try {
    $updateData = $wpdb->query($wpdb->prepare("DELETE FROM $Beagle_WP_scan_table WHERE id=%d", $deleteID));
    echo json_encode($updateData);
    exit;
  }
  catch(Exception $e) {
  }
}
