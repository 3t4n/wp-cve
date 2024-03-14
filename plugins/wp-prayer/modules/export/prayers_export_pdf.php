<?php 
 /**
  * Export Prayers in PDF
  */

  $export_pages=array();

 	$export_table_style=' <style type="text/css">

 	  table.lxt-export-table{

 	  	width:100%;

 	  }

   	  table.lxt-export-table th{

   	  	background-color:#C1E1A6;

   	  }

   	  table.lxt-export-table .cell1{

   	  	width:15%;

   	  }

   	  table.lxt-export-table .cell2{

   	  	width:40%;

   	  }

   	  table.lxt-export-table .cell3{

   	  	width:15%;

   	  }

   	  table.lxt-export-table .cell4{

   	  	width:15%;

   	  }

   	  table.lxt-export-table .cell5{

   	  	width:15%;

   	  }

   </style>';


	$export_table_header='

		<table cellspacing="0" cellpadding="5" border="1" class="lxt-export-table">

		   	<tr>

		     	<th class="cell1">Status</th>
	
				<th class="cell2">Prayer</th>

				<th class="cell3">Name</th>

				<th class="cell4">Email</th>

				<th class="cell5">Date</th>

		    </tr>

	';

	$export_table_rows='';
	$i=0;
	foreach ($export_table_records as $key => $export_table_record) {

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
       $export_table_rows.="<tr><td>$status</td><td>$message</td><td>$author</td><td>$email</td><td>$ptime</td></tr>";

    }

    $export_table_footer='</table>';



 	$export_data_html=$export_table_style.$export_table_header.$export_table_rows.$export_table_footer;

 	$export_pages[]=$export_data_html;
    //echo $tcpdf_headline;
    //die('I am here in export');

	require_once(WPE_Model.'export/tcpdf/logix/config/wp_prayer_tcpdf_config.php');

	require_once(WPE_Model .'export/tcpdf/tcpdf.php');

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);
  

	// set document information

	$pdf->SetCreator(PDF_CREATOR);

	//ob_clean(); 

	//$pdf->SetAuthor('Go Prayer');



	//$pdf->SetTitle('Prayers Data');
	//$pdf->SetTitle($tcpdf_headline);



	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
	$pdf->SetHeaderData(null, 0, $tcpdf_headline, '');

	

	// set header and footer fonts

	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));



	// set default monospaced font

	//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $pdf->setFontSubsetting(false);

	// set margins

	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);



	// set auto page breaks

	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



	// set image scale factor

	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



	// add a page

	$pdf->AddPage();

    

    $p=0;

	foreach ($export_pages as $key => $export_page) {

		++$p;

		if($p>1) $pdf->AddPage();

		$pdf->writeHTML($export_page, true, false, true, false, '');



	}

	

	// add a page

	//$pdf->AddPage();

	// reset pointer to the last page
	$pdf->lastPage();



	//Close and output PDF document
	$pdf_file= 'wp-prayers-'.date('d-m-Y').'.pdf';

	$pdf->Output($pdf_file, 'D');