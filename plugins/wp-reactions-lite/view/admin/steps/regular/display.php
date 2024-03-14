<?php
use WP_Reactions\Lite\Helper;
?>
<div class="row half-divide">
	<?php
	Helper::getOptionBlock( 'animation-state' );
	Helper::getOptionBlock( 'emoji-size' );
	?>
</div>
<?php
Helper::getOptionBlock( 'live-counts' );
Helper::getOptionBlock( 'placement' );
?>

