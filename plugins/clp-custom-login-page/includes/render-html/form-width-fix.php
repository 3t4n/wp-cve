<?php 
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

ob_start(); ?>

<script>
    (function() {
        const content = document.querySelector('.clp-content');
        const formContainer = document.querySelector('.clp-form-container');

        const fixWidth = () => {
            if ( formContainer.offsetWidth > content.offsetWidth ) {
                content.style.justifyContent = 'flex-start';
            } else {
                content.style.justifyContent = null;
            }
        }

        fixWidth();
        window.addEventListener('resize', fixWidth)
    })()
</script>

<?php
$html = ob_get_clean();