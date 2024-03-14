<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_style('jsjob-ratingstyle', JSJOBS_PLUGIN_URL . 'includes/css/jsjobsrating.css');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$default_longitude = jsjobs::$_configuration['default_longitude'];
$default_latitude = jsjobs::$_configuration['default_latitude'];
$mapfield = null;
$mapfield2 = null;

if(isset(jsjobs::$_data[2])){
    foreach(jsjobs::$_data[2] AS $key => $value){
        $value = (array) $value;
        if(in_array('map', $value)){
            $mapfield = $key;
            break;
        }
    }
}

if(isset(jsjobs::$_data['fields'])){
    foreach(jsjobs::$_data['fields'] AS $key => $value){
        $value = (array) $value;
        if(in_array('map', $value)){
            $mapfield2 = $key;
            break;
        }
    }
}
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
if(isset(jsjobs::$_data[2][$mapfield]) && jsjobs::$_data[2][$mapfield]->published == 1){ 
   wp_enqueue_script( 'mapAPI','https://maps.googleapis.com/maps/api/js?key='.esc_attr(jsjobs::$_configuration['google_map_api_key']));
}elseif($mapfield2 != null) {
   wp_enqueue_script( 'mapAPI','https://maps.googleapis.com/maps/api/js?key='.esc_attr(jsjobs::$_configuration['google_map_api_key']));
} 
   wp_enqueue_script( 'mapAPI','https://maps.googleapis.com/maps/api/js?key='.esc_attr(jsjobs::$_configuration['google_map_api_key']));
?>
<script >
    var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')); ?>";
    jQuery(document).ready(function ($) {
        $("div#jsjob-popup-background,img#popup_cross").click(function () {
            closePopup();
        });
    });

    function closePopup() {
        jQuery("div#jsjob-search-popup,div#jsjobs-listpopup").slideUp('slow');
        setTimeout(function () {
            jQuery("div#jsjob-popup-background").hide();
            jQuery("div#jsjobs-popup-background").hide();
        }, 700);
    }

    function showPopup() {
        jQuery("div#jsjob-popup-background").show();
        jQuery("div#jsjob-search-popup").slideDown('slow');
    }

    function loadMap1() {
        var default_latitude = document.getElementById('latitude1').value;
        var default_longitude = document.getElementById('longitude1').value;
        default_latitude = default_latitude.split(',');
        if(default_latitude instanceof Array){
            default_longitude = default_longitude.split(',');
            var latlng = new google.maps.LatLng(default_latitude[0], default_longitude[0]);
            zoom = 4;
            var myOptions = {
                zoom: zoom,
                center: latlng,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map_container1"), myOptions);
            for (i = 0; i < default_latitude.length; i++) {
                var latlng = new google.maps.LatLng(default_latitude[i], default_longitude[i]);
                addMarker(latlng);
            }                            
        }else{
            var latlng = new google.maps.LatLng(default_latitude, default_longitude);
            zoom = 10;
            var myOptions = {
                zoom: zoom,
                center: latlng,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map_container"), myOptions);
            addMarker(latlng);
        }

    }
    
    function addMarker(latlang){
        var marker = new google.maps.Marker({
            position: latlang,
            map: map,
            draggable: false,
        });
        marker.setMap(map);
    }



    function loadMap() {
        var default_latitude = '<?php echo esc_attr($default_latitude); ?>';
        var default_longitude = '<?php echo esc_attr($default_longitude); ?>';

        var latitude = document.getElementById('latitude').value;
        var longitude = document.getElementById('longitude').value;

        if (latitude != '' && longitude != '') {
            default_latitude = latitude;
            default_longitude = longitude;
        }
        var latlng = new google.maps.LatLng(default_latitude, default_longitude);
        zoom = 10;
        var myOptions = {
            zoom: zoom,
            center: latlng,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_container"), myOptions);
        var lastmarker = new google.maps.Marker({
            postiion: latlng,
            map: map,
        });

        google.maps.event.addListener(map, "click", function (e) {
            var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': latLng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (lastmarker != '')
                        lastmarker.setMap(null);
                    var marker = new google.maps.Marker({
                        position: results[0].geometry.location,
                        map: map,
                    });
                    marker.setMap(map);
                    lastmarker = marker;
                    document.getElementById('latitude').value = marker.position.lat();
                    document.getElementById('longitude').value = marker.position.lng();

                } else {
                    alert("<?php echo __("Geocode was not successful for the following reason", "js-jobs"); ?>"+": " + status);
                }
            });
        });
    }

    function resetpopupform(){
        var form = jQuery('form#job_form');
        form.find("input[type=text], input[type=email], input[type=password], textarea").val("");
        form.find('input:checkbox').removeAttr('checked');
        form.find('select').prop('selectedIndex', 0);
        form.find('input[type="radio"]').prop('checked', false);

        jQuery(".jsjob-multiselect").val('').trigger('chosen:updated');
        jQuery('input#city').val('');
        jQuery('input#city').tokenInput("clear");
        form.submit();

        //jQuery('form#adminForm').append('<input type="hidden" name="popresetbtn" value="true">');
    }

</script>
<style>
    div#jsjobs-hide{display: none;width: 100%;}
    div#map{width: 100%;height: 100%;}
    div#map_container,div#map_container1{
        height:<?php echo esc_attr(jsjobs::$_configuration['mapheight']).'px'; ?>;
        width:100%;
    }
</style>
<script >
    jQuery(document).ready(function ($) {
        $(".jsjob-multiselect").chosen({
            placeholder_text_multiple: "<?php echo __('Select some options', 'js-jobs'); ?>"
        });

        //Token Input
        var multicities = <?php echo isset(jsjobs::$_data['filter']['city']) ? jsjobs::$_data['filter']['city'] : "''" ?>;
        getTokenInput(multicities);

        //Validation
        $.validate();
    });
    function checkmapcooridnate() {
        var latitude = document.getElementById('latitude').value;
        var longitude = document.getElementById('longitude').value;
        var radius = document.getElementById('radius').value;
        if (latitude != '' && longitude != '') {
            if (radius != '') {
                this.form.submit();
            } else {
                alert("<?php echo __("Please enter the coordinate radius", "js-jobs"); ?>");
                return false;
            }
        }
    }
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
        jQuery("#jsjobs-input-city").attr("placeholder", " <?php echo __("Type city","js-jobs");?>:");
    }



</script>
