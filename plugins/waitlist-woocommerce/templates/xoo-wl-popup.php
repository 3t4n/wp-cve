<?php
/**
 * The template is for popup design
 *
 * This template can be overridden by copying it to yourtheme/templates/waitlist-woocommerce/xoo-wl-popup.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/waitlist-for-woocommerce/
 * @version 2.4
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


?>

<div class="xoo-wl-popup">
	<div class="xoo-wl-opac"></div>
	<div class="xoo-wl-modal">
		<div class="xoo-wl-inmodal">
			<span class="xoo-wl-close xoo-wl-icon-cancel-circle"></span>
				<div class="xoo-wl-wrap">
					<div class="xoo-wl-sidebar"></div>
                    <div class="xoo-wl-srcont">
                    	<div class="xoo-wl-main">
	                    	<?php xoo_wl_helper()->get_template( 'xoo-wl-form.php' ); ?>
	                    </div>
	                </div>
                </div>
            </div>
        </div>
    </div>
</div>

