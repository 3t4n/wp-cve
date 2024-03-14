<?php
/**
 * Show error messages
 *
 * This template can be overridden by copying it to yourtheme/masvideos/notices/error.php.
 *
 * HOWEVER, on occasion MasVideos will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package     MasVideos/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $messages ) {
	return;
}

?>
<ul class="masvideos-error" role="alert">
	<?php foreach ( $messages as $message ) : ?>
		<li>
			<?php
				echo masvideos_kses_notice( $message );
			?>
		</li>
	<?php endforeach; ?>
</ul>
