<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

wp_enqueue_script('jquery-ui-datepicker');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', JSJOBS_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');?>
<style>
.ui-datepicker{
    float: left;
}
</style>
<?php

$config = jsjobs::$_configuration;
if ($config['date_format'] == 'm/d/Y' || $config['date_format'] == 'd/m/y' || $config['date_format'] == 'm/d/y' || $config['date_format'] == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
$dateformat = $config['date_format'];
$firstdash = jsjobslib::jsjobs_strpos($dateformat, $dash, 0);
$firstvalue = jsjobslib::jsjobs_substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = jsjobslib::jsjobs_strpos($dateformat, $dash, $firstdash);
$secondvalue = jsjobslib::jsjobs_substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = jsjobslib::jsjobs_substr($dateformat, $seconddash, jsjobslib::jsjobs_strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
$js_scriptdateformat = jsjobslib::jsjobs_str_replace('Y', 'yy', $js_scriptdateformat);
?>
<script >
    function removeLogo(id) {
        var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'company', task: 'deletecompanylogo', companyid: id, wpnoncecheck:common.wp_jm_nonce}, function (data) {
            if (data) {
                jQuery("img#comp_logo").css("display", "none");
                jQuery("span.remove-file").css("display", "none");
                jQuery("form#jsjobs-form").append('<input type="hidden" name="company_logo_deleted">');
            } else {
                jQuery("div.logo-container").append('<span style="color:Red;"><?php echo __("Error Deleting Logo", "js-jobs"); ?>');
            }
        });
    }
    jQuery(document).ready(function ($) {
        $('.custom_date').datepicker({dateFormat: '<?php echo esc_js($js_scriptdateformat); ?>'});
        $.validate();
        //Token Input
        var multicities = <?php echo isset(jsjobs::$_data[0]->multicity) ? jsjobs::$_data[0]->multicity : "''" ?>;
        getTokenInput(multicities);
    });
    function checkUrl(obj) {
        if (!obj.value.match(/^http[s]?\:\/\//))
            obj.value = 'http://' + obj.value;
    }
    function validate_url() {
        var value = jQuery("#url").val();
        if (typeof value != 'undefined') {
            if (value != '' && value != 'http://' ) {
                if (value.match(/^(http|https|ftp)\:\/\/\w+([\.\-]\w+)*\.\w{2,4}(\:\d+)*([\/\.\-\?\&\%\#]\w+)*\/?$/i) ||
                        value.match(/^mailto\:\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w{2,4}$/i))
                {
                    return true;
                }
                else {
                    jQuery("#url").addClass("invalid");
                    alert("<?php echo __("Enter Correct Company Site", "js-jobs"); ?>");
                    return false;
                }
            }
        }
        return true;
    }
    jQuery("body").delegate("#logo", "click", function(e){
        jQuery("input#logo").change(function(){
            var srcimage = jQuery('img.rs_photo');
            readURL(this, srcimage);
        });
    });
    function readURL(input, srcimage) {
        if (input.files && input.files[0]) {
            var fileext = input.files[0].name.split('.').pop();
            var filesize = (input.files[0].size / 1024);
            var allowedsize = <?php echo JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('company_logofilezize'); ?>;
            var allowedExt = "<?php echo JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type'); ?>";
            allowedExt = allowedExt.split(',');
            if (jQuery.inArray(fileext, allowedExt) != - 1){
                if (allowedsize > filesize){
                    //New Library
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        jQuery('#rs_photo').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                } else{
                    jQuery('input#logo').replaceWith(jQuery('input#logo').val('').clone(true));
                    alert("<?php echo __("File size is greater then allowed file size", "js-jobs"); ?>");
                }
            } else{
                jQuery('input#logo').replaceWith(jQuery('input#logo').val('').clone(true));
                alert("<?php echo __("File ext. is mismatched", "js-jobs"); ?>");
            }
        }
    }
    var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
    function getTokenInput(multicities) {
        var cityArray = '<?php echo admin_url("admin.php?page=jsjobs_city&action=jsjobtask&task=getaddressdatabycityname"); ?>';
        cityArray = cityArray+"&_wpnonce=<?php echo wp_create_nonce('address-data-by-cityname'); ?>";
        var city = jQuery("#cityforedit").val();
        if (city != "") {
            jQuery("#city").tokenInput(cityArray, {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo __("Type In A Search Term", "js-jobs"); ?>",
                noResultsText: "<?php echo __("No Results", "js-jobs"); ?>",
                searchingText: "<?php echo __("Searching", "js-jobs"); ?>",
                // tokenLimit: 1,
                prePopulate: multicities,
                <?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                if ($newtyped_cities == 1) { ?>
                    onResult: function (item) {
                        if (jQuery.isEmptyObject(item)) {
                            return [{id: 0, name: jQuery("tester").text()}];
                        } else {
                            //add the item at the top of the dropdown
                            item.unshift({id: 0, name: jQuery("tester").text()});
                            return item;
                        }
                    },
                    onAdd: function (item) {
                        if (item.id > 0) {
                            return;
                        }
                        if (item.name.search(",") == -1) {
                                var input = jQuery("tester").text();
                            alert("<?php echo __("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", "js-jobs"); ?>");
                            jQuery("#city").tokenInput("remove", item);
                            return false;
                        } else {
                            var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
                            var location_data =  jQuery("tester").text();
                                //alert(new_loction_lat);
                                var n_latitude;
                                var n_longitude;
                                var geocoder =  new google.maps.Geocoder();
                                geocoder.geocode( { 'address': location_data}, function(results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        n_latitude = results[0].geometry.location.lat();
                                        n_longitude = results[0].geometry.location.lng();
                                    } else {
                                        alert("<?php echo __('Something got wrong','js-jobs');?>:"+status);
                                    }
                                });
                                setTimeout(function(){ // timout is required to make sure that lat lang has value.
                                    jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: location_data,latitude:n_latitude ,longitude:n_longitude ,wpnoncecheck:common.wp_jm_nonce}, function(data){
                                        if (data){
                                            try {
                                                var value = jQuery.parseJSON(data);
                                                jQuery('#city').tokenInput('remove', item);
                                                jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                            }
                                            catch (err) {
                                                jQuery("#city").tokenInput("remove", item);
                                                alert(data);
                                            }
                                        }
                                    });
                                },1500);
                        }
                    }
                <?php } ?>
            });
        } else {
            jQuery("#city").tokenInput(cityArray, {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo __("Type In A Search Term", "js-jobs"); ?>",
                noResultsText: "<?php echo __("No Results", "js-jobs"); ?>",
                searchingText: "<?php echo  __("Searching", "js-jobs"); ?>",
                // tokenLimit: 1,
                <?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                if ($newtyped_cities == 1) { ?>

                    onResult: function (item) {
                        if (jQuery.isEmptyObject(item)) {
                            return [{id: 0, name: jQuery("tester").text()}];
                        } else {
                            //add the item at the top of the dropdown
                            item.unshift({id: 0, name: jQuery("tester").text()});
                            return item;
                        }
                    },
                    onAdd: function (item) {
                        if (item.id > 0) {
                            return;
                        }
                        if (item.name.search(",") == -1) {
                            var input = jQuery("tester").text();
                            alert("<?php echo __("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", "js-jobs"); ?>");
                            jQuery("#city").tokenInput("remove", item);
                            return false;
                        } else {
                            var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
                            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text(), wpnoncecheck:common.wp_jm_nonce}, function (data) {
                                if (data) {
                                    try {
                                        var value = jQuery.parseJSON(data);
                                        jQuery('#city').tokenInput('remove', item);
                                        jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                    }
                                    catch (err) {
                                        jQuery("#city").tokenInput("remove", item);
                                        alert(data);
                                    }
                                }
                            });
                        }
                    }
            <?php } ?>
            });
        }
    }
</script>
