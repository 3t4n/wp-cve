<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

 if ( ! defined( 'ABSPATH' ) ) { exit; } $fs = freemius( $VARS['id'] ); $size = isset( $VARS['size'] ) ? $VARS['size'] : 80; ?>
<div class="fs-plugin-icon">
	<img src="<?php echo $fs->get_local_icon_url() ?>" width="<?php echo $size ?>" height="<?php echo $size ?>" />
</div>