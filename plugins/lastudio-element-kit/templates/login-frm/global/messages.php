<?php
/**
 * Login messages
 */

$message = wp_cache_get( 'lakit-login-messages' );

if ( ! $message ) {
	return;
}

?>
<div class="lakit-login-message"><?php
	echo $message;
?></div>