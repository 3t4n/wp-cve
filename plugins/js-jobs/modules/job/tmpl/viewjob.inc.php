<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

function checkLinks($name) {
    $print = false;
    $configname = $name;
    
    $config_array = jsjobs::$_data['config'];
    if (!(JSJOBSincluder::getObjectClass('user')->isguest())) {
        if ($config_array["$configname"] == 1) {
            $print = true;
        }
    }
    return $print;
}
?>
<script >
    jQuery(document).ready(function ($) {
        $("div#jsjob-popup-background,img#popup_cross").click(function () {
            closePopup();
        });
    });

    function closePopup() {
        jQuery("div#jsjob-search-popup,div#jsjobs-listpopup").slideUp('slow');
        setTimeout(function () {
            jQuery("div#jsjob-popup-background").hide();
        }, 700);
    }
    var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
    var map = null;
</script>
<?php

$mapfield = null;
if(!empty(jsjobs::$_data[2]))
foreach(jsjobs::$_data[2] AS $key => $value){
    $value = (array) $value;
    if(in_array('map', $value)){
        $mapfield = $key;
        break;
    }
}
if($mapfield):
    $mapfield = jsjobs::$_data[2][$mapfield];
    if($mapfield->published == 1){ ?>
        <style>
            div#map{
                width: 100%;
                height: 100%;
            }    
            div#map_container{
                height:<?php echo esc_attr(jsjobs::$_configuration['mapheight']) . 'px'; ?>;
                width:100%;
            }
        </style>
        <?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
        <input type="hidden" id="longitude" name="longitude" value="<?php echo esc_attr(jsjobs::$_data[0]->longitude); ?>"/>
        <input type="hidden" id="latitude" name="latitude" value="<?php echo esc_attr(jsjobs::$_data[0]->latitude); ?>"/>
        <?php wp_enqueue_script('jsjobs-repaptcha-scripti','https://maps.googleapis.com/maps/api/js?key='.jsjobs::$_configuration['google_map_api_key']); ?>
        <script >
            var bound = new google.maps.LatLngBounds();
            function loadMap() {
                var default_latitude = document.getElementById('latitude').value;
                var default_longitude = document.getElementById('longitude').value;
                default_latitude = default_latitude.split(',');
				if(default_latitude == '' || default_longitude == '' ){
					return;
				}
                if(default_latitude instanceof Array){
                    default_longitude = default_longitude.split(',');
                    var latlng = new google.maps.LatLng(default_latitude[0], default_longitude[0]);
                    zoom = 10;
                    var myOptions = {
                        zoom: zoom,
                        center: latlng,
                        scrollwheel: false,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    map = new google.maps.Map(document.getElementById("map_container"), myOptions);
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

                google.maps.event.addListener(map, "click", function (e) {
                    var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                    geocoder = new google.maps.Geocoder();
                    geocoder.geocode({'latLng': latLng}, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {

                        } else {
                            alert("<?php echo __("Geocode was not successful for the following reason", "js-jobs"); ?>: " + status);
                        }
                    });
                });
            }
            function addMarker(latlang){
                var marker = new google.maps.Marker({
                    position: latlang,
                    map: map,
                    draggable: false,
                });
                marker.setMap(map);
                bound.extend(marker.getPosition());
                map.fitBounds(bound);
            }
        </script>
    <?php } ?>
<?php endif; ?>
<script >
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
    window.onload = function () {
        if (document.getElementById('jobseeker_fb_comments') != null) {
            var myFrame = document.getElementById('jobseeker_fb_comments');
            if (myFrame != null)
                myFrame.src = 'http://www.facebook.com/plugins/comments.php?href=' + location.href;
        }
        if (document.getElementById('employer_fb_comments') != null) {
            var myFrame = document.getElementById('employer_fb_comments');
            if (myFrame != null)
                myFrame.src = 'http://www.facebook.com/plugins/comments.php?href=' + location.href;
        }
    }

    jQuery(document).ready(function ($) {
        jQuery("a.btn").click(function () {
            jQuery("a.btn").removeClass('blue');
            jQuery(this).toggleClass('blue');
        });
        /*job apply link start*/
        <?php if($mapfield) { ?>
            loadMap();
        <?php } ?>
        if (document.getElementById('jobseeker_fb_comments') != null) {
            var myFrame = document.getElementById('jobseeker_fb_comments');
            if (myFrame != null)
                myFrame.src = 'http://www.facebook.com/plugins/comments.php?href=' + location.href;
        }
        if (document.getElementById('employer_fb_comments') != null) {
            var myFrame = document.getElementById('employer_fb_comments');
            if (myFrame != null)
                myFrame.src = 'http://www.facebook.com/plugins/comments.php?href=' + location.href;
        }
    });
</script>
