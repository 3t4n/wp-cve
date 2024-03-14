<?php

if(get_option('arc_publication_id') && get_option('arc_api_key') && get_option('redirect_page')){

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
	  if(isset($_POST['beehiiv_submit'])){
	  	// for beehiiv integration

	  	$usable_email = sanitize_email($_POST['beehiiv_email']);
	  	$beehiiv_pubid = get_option('arc_publication_id');
	  	$beehiiv_apikey = get_option('arc_api_key');


	  	$url = 'https://api.beehiiv.com/v2/publications/' . $beehiiv_pubid . '/subscriptions';


	  	$send_email = array(
	  		'email' => $usable_email,
	  		'send_welcome_email' => true
	  	);
	  	$body = json_encode($send_email);


	  	$headers = array(
	  		'Content-Type' => 'application/json',
	  		'Authorization' => esc_attr($beehiiv_apikey)
	  	);

	  	$args = array(
			'body'        => $body,
			'timeout'     => '30',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
			'cookies'     => array(),
		);

	  	$response = wp_remote_post( esc_url($url), $args );
	  	$response_body = json_decode($response['body']);
	  	$response_status = $response_body->data->status;

	  	if($response_status == 400 || $response_status == 404 || $response_status == 429 || $response_status == 500){
	  		$message = '<html><body><p>Hello!</p><p>This email is to notify you that a website visitor tried to subscribe to your Beehiiv newsletter from your website, but there was an error and the subscriber wasn\'t added to your subscribers\' list.</p><p>The user\'s email is: ' . esc_attr($usable_email) . '.</p><p>You will likely want to add this user manually to your current list of subscribers or double check that they have already been added to ensure they receive your newsletter. You can do this by <a href="https://app.beehiiv.com/login">loggin in</a> to your Beehiiv account directly.</p><p>Additionally, you will want to check the settings on your website in the Archiiv plugin to ensure the publication ID and API key are set correctly, which is likely causing this error.</p><p>Happy Trails!</p><div style="height: 50px;"></div><p><em>This email is sent to you courtesy of <a href="https://arcbound.com">Arcbound</a></em>.</p></body></html>';
	  	}elseif($response_status != 'validating' || $response_status == 'pending'){
	  		$message = '<html><body><p>Hello!</p><p>This email is to notify you that a website visitor tried to subscribe to your Beehiiv newsletter from your website, but there was an error and the subscriber wasn\'t added to your subscribers\'list.</p><p>The user\'s email is: ' . esc_attr($usable_email) . '.</p><p>You will likely want to add this user manually to your current list of subscribers to ensure they receive your newsletter. You can do this by <a href="https://app.beehiiv.com/login">loggin in</a> to your Beehiiv account directly.</p><p>For reference, the status error was<br> <em>status: ' . esc_attr($response_status) . '</em></p><p>Happy Trails!</p><div style="height: 50px;"></div><p><em>This email is sent to you courtesy of <a href="https://arcbound.com">Arcbound</a></em>.</p></body></html>';
	  	}else{
	  		$message = '<html><body><p>Hello!</p><p>This email is to notify you that you have a new subscriber to your Beehiiv Newsletter.</p><p>The user\'s email is: ' . $usable_email . '.</p><p>To see a full list of your current subscribers, please <a href="https://app.beehiiv.com/login">log in</a> to your Beehiiv account directly.</p><p>Happy Trails!</p><div style="height: 50px;"></div><p><em>This email is sent to you courtesy of <a href="https://arcbound.com">Arcbound</a></em>.</p></body></html>';
	  	}

		$email_headers = array('Content-Type: text/html; charset=UTF-8');

		function archiiv_send_email(){
			global $message;
			global $email_headers;
			$mailed = wp_mail(sanitize_email(get_option('admin_email')), 'New Beehiiv Newsletter Submission from Website', $message, $email_headers);
		}
		add_action( 'plugins_loaded', 'archiiv_send_email' );

		$clean_url = esc_attr(get_option('redirect_page'));

		header('Location:' . $clean_url);

		exit();



	  }
	}

	function beehiiv_newsletter(){
	  ob_start();
	  
	  ?>

	  <style type="text/css">
	  	.beehiiv-form-connection{display: flex; justify-content: center; flex-wrap: wrap; width: 600px; max-width: 100%; margin: 0 auto; grid-gap: 5px;}
	  	.beehiiv-form-connection .beehiiv-email-label{width: 100%; display: block;}
	  	.beehiiv-form-connection .beehiiv-field-1{width: calc(100% - 141px); min-width: 300px; border: 1px solid #ddd; background: white; padding: 7px 5px; font-size: 16px; font-family: inherit; height: 35px; border-radius: 5px;}
	  	.beehiiv-form-connection .beehiiv-submit-button{height:35px; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; background: #dc3444; border: none; color: white; padding: 5px 20px; border-radius: 5px;}
	  </style>

	  <?php
	  $form = '<form class="beehiiv-form-connection" method="post" action="'. esc_attr($_SERVER['REQUEST_URI']).'"><label class="beehiiv-email-label" for="beehiiv-email">Email*</label><input class="beehiiv-field-1" type="email" placeholder="Enter Your Email" required name="beehiiv_email" id="beehiiv-email"><input class="beehiiv-submit-button" type="submit" value="Subscribe" name="beehiiv_submit"></form>';
	  echo $form;

	  return ob_get_clean(); 
	}
	add_shortcode('beehiiv_newsletter', 'beehiiv_newsletter');

}else{ // if the settings aren't set
	function beehiiv_newsletter(){
	  ob_start();
	  
	  if(current_user_can('administrator')){
	  	echo '<div style="background: #bbb; font-size: 13px;"><em>This is only visible to administrators:</em> <br><br> Please check your settings in the Archiiv pluing settings page to ensure this is working properly. Each input should be filled in with the correct information.</div>';
	  }else{
	  	echo '<span style="visibility: hidden;">Please check admin setting to display form corretly.</span>';
	  }

	  return ob_get_clean(); 
	}
	add_shortcode('beehiiv_newsletter', 'beehiiv_newsletter');
}

?>