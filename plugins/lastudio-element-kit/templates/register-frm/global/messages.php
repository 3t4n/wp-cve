<?php
/**
 * Registration messages
 */

$message = wp_cache_get( 'lakit-register-messages' );

if ( ! $message ) {
	return;
}

?>
<div class="lakit-register-message"><?php
	echo $message;
?></div>