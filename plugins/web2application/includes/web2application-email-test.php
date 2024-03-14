<?php

$all_users = get_users();
$emails = array();
foreach ($all_users as $user) {
	$email = esc_html($user->user_email);
	array_push($emails, $email);
}

echo "<pre>";
print_r($emails);
echo "</pre>";

$name = 'Test User';
$email = 'testuser@gmail.com';
$message = 'Demo Message';

//php mailer variables
$to = 'kevinsabinay1991@gmail.com';
$subject = "Demo Subject";
$headers = 'From: '. $email . "\r\n" .
'Reply-To: ' . $email . "\r\n";

//Here put your Validation and send mail
$sent = wp_mail($to, $subject, strip_tags($message), $headers);
if($sent) {
}
else  {
}