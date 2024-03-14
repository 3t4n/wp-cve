<?php

//nd_spt_send_message
function nd_spt_send_message($nd_spt_sport,$nd_spt_players,$nd_spt_date,$nd_spt_time_start,$nd_spt_time_end,$nd_spt_occasionn,$nd_spt_booking_form_name,$nd_spt_booking_form_surname,$nd_spt_booking_form_email,$nd_spt_booking_form_phone,$nd_spt_booking_form_requests,$nd_spt_order_type,$nd_spt_order_status,$nd_spt_deposit,$nd_spt_tx,$nd_spt_currency){


	//occasions
	$nd_spt_occasions = get_option('nd_spt_occasions');
	if ( $nd_spt_occasions == '' ) {
    	$nd_spt_occasion = __('Not Set','nd-sports-booking');
    }else { 
        $nd_spt_occasions_array = explode(',', $nd_spt_occasions );
        $nd_spt_occasion = $nd_spt_occasions_array[$nd_spt_occasionn];
    }
    

	//START MAIL TO ADMIN
	$message = '
	<html>
	<head>
	  <title>'.__('New Sport Booking','nd-sports-booking').'</title>
	</head>
	<body>
	  <p>'.__('Hi, you received a new booking on your site, here all details','nd-sports-booking').' :</p>
	  
	  <p><strong>'.__('MAIN INFORMATIONS','nd-sports-booking').' :</strong></p>
	  <p>'.__('Sport','nd-sports-booking').' : '.get_the_title($nd_spt_sport).'</p>
	  <p>'.__('Players','nd-sports-booking').' : '.$nd_spt_players.'</p>
	  <p>'.__('Date','nd-sports-booking').' : '.$nd_spt_date.'</p>
	  <p>'.__('Time Start','nd-sports-booking').' : '.$nd_spt_time_start.'</p>
	  <p>'.__('Time End','nd-sports-booking').' : '.$nd_spt_time_end.'</p>
	  <p>'.__('Service','nd-sports-booking').' : '.$nd_spt_occasion.'</p><br/>
	  
	  <p><strong>'.__('USER INFORMATIONS','nd-sports-booking').' :</strong></p>
	  <p>'.__('Name','nd-sports-booking').' : '.$nd_spt_booking_form_name.'</p>
	  <p>'.__('Surname','nd-sports-booking').' : '.$nd_spt_booking_form_surname.'</p>
	  <p>'.__('Phone','nd-sports-booking').' : '.$nd_spt_booking_form_phone.'</p>
	  <p>'.__('Email','nd-sports-booking').' : '.$nd_spt_booking_form_email.'</p>
	  <p>'.__('Message','nd-sports-booking').' : '.$nd_spt_booking_form_requests.'</p><br/>

	  <p><strong>'.__('BOOKING INFORMATIONS','nd-sports-booking').' :</strong></p>
	  <p>'.__('Booking Type','nd-sports-booking').' : '.$nd_spt_order_type.'</p>
	  <p>'.__('Booking Status','nd-sports-booking').' : '.$nd_spt_order_status.'</p>

	  <p><strong>'.__('DEPOSIT INFORMATIONS','nd-sports-booking').' :</strong></p>
	  <p>'.__('Amount','nd-sports-booking').' : '.$nd_spt_deposit.' '.$nd_spt_currency.'</p>
	  <p>'.__('Transaction ID','nd-sports-booking').' : '.$nd_spt_tx.'</p>

	</body>
	</html>
	';

	$to = get_option('admin_email');
	$nd_spt_email = get_option('admin_email');
	$nd_spt_name = get_bloginfo( 'name' );
	$subject = __('New Sport Booking','nd-sports-booking');
	$headers = array('Content-Type: text/html; charset=UTF-8','From: '.$nd_spt_name.' <'.$nd_spt_email.'>');
	wp_mail( $to, $subject, $message, $headers );
	//END MAIL TO ADMIN








	//START MAIL TO CUSTOMER
	$message = '
	<html>
	<head>
	  <title>'.__('Your Sport Booking','nd-sports-booking').'</title>
	</head>
	<body>
	  <p>'.__('Hi, below your booking details','nd-sports-booking').' :</p>
	  
	  <p><strong>'.__('MAIN INFORMATIONS','nd-sports-booking').' :</strong></p>
	  <p>'.__('Sport','nd-sports-booking').' : '.get_the_title($nd_spt_sport).'</p>
	  <p>'.__('Players','nd-sports-booking').' : '.$nd_spt_players.'</p>
	  <p>'.__('Date','nd-sports-booking').' : '.$nd_spt_date.'</p>
	  <p>'.__('Time Start','nd-sports-booking').' : '.$nd_spt_time_start.'</p>
	  <p>'.__('Time End','nd-sports-booking').' : '.$nd_spt_time_end.'</p>
	  <p>'.__('Occasion','nd-sports-booking').' : '.$nd_spt_occasion.'</p><br/>
	  
	  <p><strong>'.__('USER INFORMATIONS','nd-sports-booking').' :</strong></p>
	  <p>'.__('Name','nd-sports-booking').' : '.$nd_spt_booking_form_name.'</p>
	  <p>'.__('Surname','nd-sports-booking').' : '.$nd_spt_booking_form_surname.'</p>
	  <p>'.__('Phone','nd-sports-booking').' : '.$nd_spt_booking_form_phone.'</p>
	  <p>'.__('Email','nd-sports-booking').' : '.$nd_spt_booking_form_email.'</p>
	  <p>'.__('Message','nd-sports-booking').' : '.$nd_spt_booking_form_requests.'</p><br/>

	  <p><strong>'.__('BOOKING INFORMATIONS','nd-sports-booking').' :</strong></p>
	  <p>'.__('Booking Type','nd-sports-booking').' : '.$nd_spt_order_type.'</p>
	  <p>'.__('Booking Status','nd-sports-booking').' : '.$nd_spt_order_status.'</p>

	  <p><strong>'.__('DEPOSIT INFORMATIONS','nd-sports-booking').' :</strong></p>
	  <p>'.__('Amount','nd-sports-booking').' : '.$nd_spt_deposit.' '.$nd_spt_currency.'</p>
	  <p>'.__('Transaction ID','nd-sports-booking').' : '.$nd_spt_tx.'</p>

	</body>
	</html>
	';

	$to = $nd_spt_booking_form_email;
	$nd_spt_email = get_option('admin_email');
	$nd_spt_name = get_bloginfo( 'name' );
	$subject = __('Your Sport Booking','nd-sports-booking');
	$headers = array('Content-Type: text/html; charset=UTF-8','From: '.$nd_spt_name.' <'.$nd_spt_email.'>');
	wp_mail( $to, $subject, $message, $headers );
	//END MAIL TO CUSTOMER









}
add_action('nd_spt_reservation_added_in_db','nd_spt_send_message',10,16);

