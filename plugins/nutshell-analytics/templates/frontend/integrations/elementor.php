<?php
// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN - see https://app.getguru.com/card/T585Kydc

/**************************************************
 * Name: Elementor
 * Description: Automatically track submissions from Elementor forms
 *************************************************/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<!-- MCFX Integration: Elementor -->
<script type="text/javascript" data-registered="mcfx-plugin" >
    document.addEventListener('readystatechange', function(event) {
        if (event.target.readyState === 'complete') {
            if (
                /* global mcfx */
                'undefined' !== typeof mcfx
                /* global jQuery */
                && 'undefined' !== typeof jQuery
            ) {
                const eles = document.querySelectorAll('.elementor-form');
                eles.forEach(
                    (ele) => {
                        jQuery(ele).on(
                            'submit_success',
                            (e) => {
                                mcfx(
                                    (tracker) => {
                                        tracker.capture(e.target);
                                    }
                                );
                            }
                        );
                    }
                );
            }
        }
    });
</script>

<?php // IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN - see https://app.getguru.com/card/T585Kydc ?>
