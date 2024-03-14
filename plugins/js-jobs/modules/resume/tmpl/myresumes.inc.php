<?php 
if (!defined('ABSPATH')) die('Restricted Access'); 
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".myresume-complete-status").each(function(){
            var per = jQuery( this ).attr("data-per");
            jQuery(this).find(".js-mr-rp").attr("data-progress", per);
        });
    });
</script>