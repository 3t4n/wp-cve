<?php
    $eli_zoom_index = (int) $settings['zoom_index']['size'];
    $eli_center_lat = $eli_lat = (float) $settings['gps_lat'];
    $eli_center_lng = $eli_lng = (float) $settings['gps_lng']; 

    if(!empty($settings['center_gps_lat']) && !empty($settings['center_gps_lat']))
        $eli_center_lat = (float) $settings['center_gps_lat'];
        $eli_center_lng = (float) $settings['center_gps_lng'];

    $eli_enable_map_move_by_finger_mobile = false;
?>
<?php if ($settings['map_type'] == 'open_street'): ?>
    <div id="elementinvader_addons_for_elementor_<?php echo esc_html($this->get_id_int()); ?>" style="height:<?php echo $settings['map_height']['size'];?>px" data-zoom_index="<?php echo esc_attr($eli_zoom_index); ?>" class="sw_map_box elementor-custom-embed elementor-clickable"></div>
<?php endif; ?>
<?php if ($settings['map_type'] == 'google' && $settings['google_map_styes'] != ''): ?>
    <div id="elementinvader_addons_for_elementor_<?php echo esc_html($this->get_id_int()); ?>" data-zoom_index="<?php echo esc_attr($eli_zoom_index); ?>" class="sw_map_box elementor-custom-embed elementor-clickable"></div>
    <?php wp_enqueue_script('google-markerclusterers'); ?>
    <?php wp_enqueue_script('wlistings-custom-marker'); ?>
<?php endif; ?>

<?php 
if(!function_exists('eli_get_position_marker')) {
    function eli_get_position_marker($size) {
        $popupAnchor_x = 0; 
        $popupAnchor_y = -60;
        $iconAnchor_x = 23;
        $iconAnchor_y = 76;  

        if($size >=10 && $size <= 25){
            $popupAnchor_x = 0;$popupAnchor_y = ($size + 8)*-1;$iconAnchor_x = ($size/2);$iconAnchor_y = $size*2-5;
        } elseif($size >=26 && $size <= 40){
            $popupAnchor_x = 0;$popupAnchor_y = ($size + 12)*-1;$iconAnchor_x = ($size/2);$iconAnchor_y = $size*2-15;
        } elseif($size >=41 && $size <= 55){
            $popupAnchor_x = 0;$popupAnchor_y = ($size + 21)*-1;$iconAnchor_x = ($size/2);$iconAnchor_y = $size*2-18;
        } elseif($size >=56){
            $popupAnchor_x = 1;$popupAnchor_y = ($size + 40)*-1;$iconAnchor_x = ($size/2);$iconAnchor_y = $size*2-15;
        }

        return array(
            'popupAnchor_x'  => $popupAnchor_x,
            'popupAnchor_y'  => $popupAnchor_y,
            'iconAnchor_x'  => $iconAnchor_x,
            'iconAnchor_y'  => $iconAnchor_y,
        );

    }
}

?>
<style>
    #elementinvader_addons_for_elementor_<?php echo esc_html($this->get_id_int()); ?> .wl_marker-container {
        height: <?php echo esc_attr($this->_ch($settings['marker_size']['size'], 60));?>px !important;
        width: <?php echo esc_attr($this->_ch($settings['marker_size']['size'], 60));?>px !important;
    } 
</style>
 
<?php
    $custom_js = "";

    if ($settings['map_type'] == 'open_street'):

        $custom_js .= "
        jQuery(document).ready(function($) {

            var eli_geocoder;
            var eli_map;
            var eli_markers = [];
            var eli_clustererOptions;
            var eli_infowindow;

            var eli_clusters ='';
            var eli_jpopup_customOptions =
            {
            'maxWidth': 'initial',
            'width': 'initial',
            'className' : 'popupCustom'
            }
            if(eli_clusters=='')
                eli_clusters = L.markerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: false, zoomToBoundsOnClick: true});

                eli_map = L.map('elementinvader_addons_for_elementor_" . $this->get_id_int() . "', {
                    center: [" . esc_html($eli_lat) . "," . esc_html($eli_lng) . "],
                    zoom: " . $eli_zoom_index . ",
                    scrollWheelZoom: false,
                    ";
        if (!$eli_enable_map_move_by_finger_mobile)
            $custom_js .= "dragging: !L.Browser.mobile,";
        else
            $custom_js .= "dragging: true,";
        $custom_js .= "tap: !L.Browser.mobile
                });     
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
                }).addTo(eli_map);
                ";

        if(!empty($settings['openstreet_map_styes']) && $settings['openstreet_map_styes'] =='custom' && isset($settings['conf_custom_map_style_self']) && !empty($settings['conf_custom_map_style_self'])):
        $custom_js .= "   
                    var sw_style_open_street_ini = '" . $settings['conf_custom_map_style_self'] . "';
                    var positron = L.tileLayer(sw_style_open_street_ini).addTo(eli_map);
                ";
        elseif(!empty($settings['openstreet_map_styes']) && $settings['openstreet_map_styes'] !='custom'):
        $custom_js .= "   
                    var sw_style_open_street_ini = '" . $settings['openstreet_map_styes'] . "';
                    var positron = L.tileLayer(sw_style_open_street_ini).addTo(eli_map);
                ";
        endif;

        $font_icon = $this->generate_icon($settings['marker_icon']);

        $custom_js .= "var innerMarker = '<div class=\"wl_marker-container\"><div class=\"front wl_face\"><i class=\"fa fa-home\"></i></div><div class=\"wl_marker-card\"><div class=\"wl_marker-arrow\"></div></div></div>';";
        if ($font_icon) {
            $custom_js .= "innerMarker = '<div class=\"wl_marker-container\"><div class=\"front wl_face\">".$font_icon."</div><div class=\"wl_marker-card\"><div class=\"wl_marker-arrow\"></div></div></div>';";
        }

        $offset_w = '';
        if(!empty($settings['section_map_infobox_width']['size']))
            $offset_w = $settings['section_map_infobox_width']['size'];
        
            if($settings['enable_default_map_markers'] != 'yes'){
                $custom_js .= "
                var marker = L.marker(
                    [" . esc_html($eli_lat) . ", " . esc_html($eli_lng) . "],
                    ";
                    if(!empty($settings['marker_icon_image']['url'])){
                        $marker_size = wp_get_attachment_image_src( $settings['marker_icon_image']['id'], 'full' );    
                        $custom_js .= "{icon: L.icon({ iconUrl: '".$settings['marker_icon_image']['url']."',iconSize: [".(($marker_size) ? $marker_size[1]: '').", ".(($marker_size) ? $marker_size[2]: '')."],popupAnchor: [0, -".((($marker_size) ? $marker_size[2]: 2)/2)."]})}";
                    }
                    else
                        $custom_js .= " {icon: L.divIcon({
                                html: innerMarker,
                                className: 'open_steet_map_marker',
                                iconSize: [0, 0],
                                popupAnchor: [".eli_get_position_marker($this->_ch($settings['marker_size']['size'], 60))['popupAnchor_x'].", ".eli_get_position_marker($this->_ch($settings['marker_size']['size'], 60))['popupAnchor_y']."],
                                iconAnchor: [".eli_get_position_marker($this->_ch($settings['marker_size']['size'], 60))['iconAnchor_x'].", ".eli_get_position_marker($this->_ch($settings['marker_size']['size'], 60))['iconAnchor_y']."],
                            })
                        }";  
                    $custom_js .= "  
                );";
            } else {
                $custom_js .= "  
                    var marker = L.marker( [" . esc_html($eli_lat) . ", " . esc_html($eli_lng) . "])
                ";
            }

            $custom_js .= "
                eli_clusters.addLayer(marker);
                eli_markers.push(marker);
                eli_map.addLayer(eli_clusters);

                marker.bindPopup('<div class=\"eli_infobox\"><h2 class=\"eli_infobox_title\">".$this->_js($settings['section_content_title'])."</h2><div class=\"eli_infobox_text\">".$this->_js($settings['section_content_text'])."</div></div>', eli_jpopup_customOptions)";

                if($settings['show_bydefault'])        
                    $custom_js .= ".openPopup();";
                else
                    $custom_js .= ";";

                $custom_js .= "  
                    $.get('https://nominatim.openstreetmap.org/search?format=json&q=" . $settings['address'] . "', function(data){
                        if(typeof data[0] !='undefined'){
                            marker.setLatLng([data[0].lat, data[0].lon]).update(); 
                            eli_map.panTo(new L.LatLng(data[0].lat, data[0].lon));
                        }
                    })";
                    
                    if($settings['show_bydefault'])        
                        $custom_js .= ".success(function(){marker.openPopup();});";
                    else
                        $custom_js .= ";";

                    if(!empty($settings['markers'])) foreach ($settings['markers'] as $key => $marker) {
                        $custom_js .= "var innerMarker = '<div class=\"wl_marker-container\"><div class=\"front wl_face\"><i class=\"" . esc_html($marker['marker_icon']) . "\"></i></div><div class=\"wl_marker-card\"><div class=\"wl_marker-arrow\"></div></div></div>';";
                   
                        if($settings['enable_default_map_markers'] != 'yes'){
                            $custom_js .= "
                            var marker = L.marker(
                                [" . esc_html($marker['gps_lat']) . ", " . esc_html($marker['gps_lng']) . "],
                                ";
                                if(!empty($marker['marker_icon_image']['url'])){
                                    $marker_size = wp_get_attachment_image_src( $marker['marker_icon_image']['id'], 'full' );    
                                    $custom_js .= "{icon: L.icon({ iconUrl: '".$marker['marker_icon_image']['url']."',iconSize: [".$marker_size[1].", ".$marker_size[2]."],popupAnchor: [0, -".($marker_size[2]/2)."]})}";
                                }
                                else
                                    $custom_js .= " {icon: L.divIcon({
                                            html: innerMarker,
                                            className: 'open_steet_map_marker',
                                            iconSize: [0, 0],
                                            popupAnchor: [".eli_get_position_marker($this->_ch($settings['marker_size']['size'], 60))['popupAnchor_x'].", ".eli_get_position_marker($this->_ch($settings['marker_size']['size'], 60))['popupAnchor_y']."],
                                            iconAnchor: [".eli_get_position_marker($this->_ch($settings['marker_size']['size'], 60))['iconAnchor_x'].", ".eli_get_position_marker($this->_ch($settings['marker_size']['size'], 60))['iconAnchor_y']."],
                                        })
                                    }";  
                                $custom_js .= "  
                            );";
                        } else {
                            $custom_js .= "  
                                var marker = L.marker( [" . esc_html($marker['gps_lat']) . ", " . esc_html($marker['gps_lng']) . "])
                            ";
                        }

                        if($settings['conf_custom_map_custer_disable'] == 'yes') {
                            $custom_js .= "  
                                eli_markers.push(marker);
                                eli_map.addLayer(marker);
                            ";
                        } else {
                            $custom_js .= "  
                                eli_clusters.addLayer(marker);
                                eli_markers.push(marker);
                                eli_map.addLayer(eli_clusters);
                            ";
                        }

                        $custom_js .= "  
                            marker.bindPopup('<div class=\"eli_infobox\"><h2 class=\"eli_infobox_title\">".$this->_js($marker['title'])."</h2><div class=\"eli_infobox_text\">".$this->_js($marker['text'])."</div></div>', eli_jpopup_customOptions);";
                    }

                    if($settings['conf_custom_map_auto_center'] == 'yes'):
                    $custom_js .= "
                            /* set center */
                            if(eli_markers.length && eli_markers.length > 1){
                                var limits_center = [];
                                for (var i in eli_markers) {
                                    var latLngs = [ eli_markers[i].getLatLng() ];
                                    limits_center.push(latLngs)
                                };
                                var bounds = L.latLngBounds(limits_center);
                                eli_map.fitBounds(bounds);
                            }
                        ";
                    endif;

                $custom_js .= " 
                })
            ";
    else:
        if ($settings['google_map_styes'] == '' && empty($settings['markers'])) {
            printf(
                    '<div class="elementor-custom-embed"><iframe  class="sw_map_box elementor-clickable" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=%1$s&amp;t=m&amp;z=%2$d&amp;output=embed&amp;iwloc=near" title="%3$s" aria-label="%3$s"></iframe></div>',
                    rawurlencode($settings['address']),
                    absint($eli_zoom_index),
                    esc_attr($settings['address'])
            );
        } else {


            $custom_js .= "
        jQuery(document).ready(function($) {

            var eli_geocoder;
            var eli_map;
            var myLatlng = {lat: " . esc_html($eli_center_lat) . ", lng: " . esc_html($eli_center_lng) . "};
            
            eli_geocoder = new google.maps.Geocoder();
            eli_map = new google.maps.Map(document.getElementById('elementinvader_addons_for_elementor_" . esc_html($this->get_id_int()) . "'), {
              zoom: " . $eli_zoom_index . ",
              center: myLatlng,";

            if (!empty($settings['google_map_styes']) && $settings['google_map_styes'] != 'custom')
                $custom_js .= "styles: " . trim($settings['google_map_styes'], ';');

            $custom_js .= "   
            });
            ";

            
            $custom_js .= "      
            // Add marker on location
                var contentString ='<div class=\"eli_infobox\"><h2 class=\"eli_infobox_title\">".$this->_js($settings['section_content_title'])."</h2><div class=\"eli_infobox_text\">".$this->_js($settings['section_content_text'])."</div></div>';

                var infowindow = new google.maps.InfoWindow({
                  content: 'Loading...',
                });

              ";

            if (empty($settings['address']) && !empty($settings['gps_lat']) && !empty($settings['gps_lng']))
                $custom_js .= "      
                // Add marker on location
                  var marker = new google.maps.Marker({
                    map: eli_map,
                    position: {lat: " . esc_html($eli_lat) . ", lng: " . esc_html($eli_lng) . "},
                        ";
            
            if(!empty($settings['marker_icon_image']['url']))
                $custom_js .= "icon: '".$settings['marker_icon_image']['url']."'";
                
            $custom_js .= "   });";

            if (!empty($settings['address'])) {
                $custom_js .= "
                var eli_geocoder = new google.maps.Geocoder();
                eli_geocoder.geocode({
                    'address': '" . $settings['address'] . "'
                }, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {

                  // Center map on location
                  eli_map.setCenter(results[0].geometry.location);

                  // Add marker on location
                  var marker = new google.maps.Marker({
                    map: eli_map,
                    position: results[0].geometry.location,
                        ";
                    if(!empty($settings['marker_icon_image']['url']))
                        $custom_js .= "icon: '".$settings['marker_icon_image']['url']."'";

                    $custom_js .= "   });
                } else {
                  alert('" . esc_html__('Geocode was not successful for the following reason', 'elementinvader-addons-for-elementor') . ": ' + status);
                }
            });

            ";
            }
            $custom_js .= "  
                if(typeof marker != 'undefined')
                    marker.addListener('click', function(){
                      infowindow.setContent(contentString);
                      infowindow.open(eli_map, marker);
                    });        
            ";

            if(!empty($settings['markers'])) foreach ($settings['markers'] as $key => $marker) {
                if (!empty($marker['gps_lat']) && !empty($marker['gps_lng']))
                    $custom_js .= "      
                    // Add marker on location
                    var marker = new google.maps.Marker({
                        map: eli_map,
                        position: {lat: " . esc_html($marker['gps_lat']) . ", lng: " . esc_html($marker['gps_lng']) . "},
                            ";
                    
                    if(!empty($marker['marker_icon_image']['url']))
                        $custom_js .= "icon: '".$marker['marker_icon_image']['url']."'";
                            
                    $custom_js .= "   });
                    
                    marker.addListener('click', function(){
                        infowindow.setContent('<div class=\"eli_infobox\"><h2 class=\"eli_infobox_title\">".$this->_js($marker['title'])."</h2><div class=\"eli_infobox_text\">".$this->_js($marker['text'])."</div></div>');
                        infowindow.open(eli_map, marker);
                    });       
                    
                ";
            }
         
        if($settings['show_bydefault'])        
            $custom_js .= "infowindow.open(eli_map, marker);";
        
        $custom_js .= "  
                });
            ";   
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
            wp_deregister_script('maps-google-api-js');
            wp_deregister_script('el_wl_maps-google-api-js');
            wp_enqueue_script('el_wl_maps-google-api-js', $protocol . "://maps.google.com/maps/api/js?libraries=places,geometry&amp;key=" . $settings['google_map_key'] . "", array('jquery'));
        }
    endif;

    echo '<script>' . ($custom_js) . '</script>';

    if (isset($is_edit_mode)) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        ?>
        <script src="<?php echo esc_html($protocol); ?>://maps.google.com/maps/api/js?libraries=places%2Cgeometry&amp;key=<?php echo esc_attr($settings['google_map_key']); ?>&amp;ver=5.6" id="el_wl_maps-google-api-js-js"></script>
        <?php
    } else {
        //wp_add_inline_script( 'wlistings-main', $custom_js );
    }
?>
