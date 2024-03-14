<script type="text/javascript">
    jQuery(document).ready(function ($) {
        //Token Input
        var multicities = <?php echo isset(jsjobs::$_data['filter']['city']) ? jsjobs::$_data['filter']['city'] : "''" ?>;
        getTokenInput(multicities);

    });
    //Token in put
    function getTokenInput(multicities) {
        var cityArray = '<?php echo admin_url("admin.php?page=jsjobs_city&action=jsjobtask&task=getaddressdatabycityname"); ?>';
        cityArray = cityArray+"&_wpnonce=<?php echo wp_create_nonce('address-data-by-cityname'); ?>";
        jQuery("#city").tokenInput(cityArray, {
            theme: "jsjobs",
            preventDuplicates: true,
            prePopulate: multicities,
            hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
            noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
            searchingText: "<?php echo __('Searching', 'js-jobs'); ?>"
        });
        jQuery("#jsjobs-input-city").attr("placeholder", " Type city:");
    }

    function closePopupJobManager() {
        closePopupForTemplate();
    }

    function closePopupJobHub() {
        closePopupForTemplate()
    }

    function closePopupForTemplate() {
        jQuery("div#"+common.theme_chk_prefix+"-popup").slideUp('slow');
        setTimeout(function () {
            jQuery("div#"+common.theme_chk_prefix+"-popup-background").hide();
            jQuery("#"+common.theme_chk_prefix+"-modal-ar-title").html('');
            jQuery("div#"+common.theme_chk_prefix+"-popup").css("display", "none");
            /*jQuery("span#popup_coverletter_title.coverletter").html('');
            jQuery("span#popup_coverletter_desc.coverletter").html('');*/
        }, 700);

    }
</script>
