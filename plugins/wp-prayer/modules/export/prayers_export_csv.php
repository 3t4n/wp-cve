<?php 
 /**
  * Export Prayers in CSV
  */
 
$user_arr = array();
$i=0;
$wpe1=__('Status',WPE_TEXT_DOMAIN);
$wpe2=__('Pray',WPE_TEXT_DOMAIN);
$wpe3=__('Name',WPE_TEXT_DOMAIN);
$wpe4=__('Email',WPE_TEXT_DOMAIN);
$wpe5=__('Date',WPE_TEXT_DOMAIN);
$user_arr[] = array($wpe1,$wpe2,$wpe3,$wpe4,$wpe5);

foreach ($export_table_records  as $key => $export_table_record) {
	$id = ++$i;
	$status=$export_table_record['prayer_status'];
	$message=$export_table_record['prayer_messages'];
	$author=$export_table_record['prayer_author_name'];
	$email=$export_table_record['prayer_author_email'];
	$ptime=$export_table_record['prayer_time'];
	$ptime=date_i18n(get_option('date_format'),strtotime( $ptime )).' '.date_i18n(get_option('time_format'),strtotime( $ptime ));
	if($status == "pending"){
		$status1 = __('pending',WPE_TEXT_DOMAIN);
		} elseif($status == "private"){
		$status1 = __('private',WPE_TEXT_DOMAIN);
		} elseif($status == "approved"){	
		$status1 = __('Approved',WPE_TEXT_DOMAIN);
		} elseif($status == "disapproved"){	
		$status1 = __('Disapproved',WPE_TEXT_DOMAIN);
		}
    $user_arr[] = array($status,$message,$author,$email,$ptime);
}

$data=serialize($user_arr);
$filename = 'wp-prayers-'.date('d-m-Y').'.csv';
$export_data = unserialize($data);

// file creation

$data=serialize($user_arr);
$filename = 'wp-prayers-'.date('d-m-Y').'.csv';
$export_data = unserialize($data);

header("Content-Description: File Transfer");
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=$filename"); 
$outstream = fopen("php://output",'w');
foreach ($export_data as $line){
	//ob_end_clean();
 	fputcsv($outstream, $line, ',', '"');
}

fclose($outstream);
exit;
