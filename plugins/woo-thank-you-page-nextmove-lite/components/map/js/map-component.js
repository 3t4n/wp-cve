var xlwctyCore = {loadmap: false};
(function ($) {
    var xlwctyMap = {};
    var xlwctyMarker = {};

    function xlwctyDecodeString($string) {
        $string = xlwctyReplaceAll($string, '+', ' ');
        $string = decodeURIComponent($string);
        return $string;
    }

    function xlwctyReplaceAll(str, find, replace) {
        return str.replace(new RegExp(find.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g'), replace);
    }

    function infobubble_init() {
        $.getScript(
            xlwcty.infobubble_url, function (data, textStatus, jqxhr) {
                map_init();
            }
        );
    }

    window.map_init = function () {
        var infoBubble;

        if (typeof google === "undefined" && typeof xlwcty.settings.is_preview != "undefined" && xlwcty.settings.is_preview === 'yes') {
            $(".xlwcty-map-component").html("<div class='xlwcty_map_error_txt'>Google Map Api Key Not Found</div>");
            return;
        }
        if (typeof google === "undefined") {
            return true;
        }
        var geocoder = new google.maps.Geocoder();
        var styles = {
            "standard": [],
            "grey": [{"elementType": "geometry", "stylers": [{"color": "#f5f5f5"}]}, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#616161"}]
            }, {"elementType": "labels.text.stroke", "stylers": [{"color": "#f5f5f5"}]}, {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#bdbdbd"}]
            }, {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#eeeeee"}]}, {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#757575"}]
            }, {"featureType": "poi.park", "elementType": "geometry", "stylers": [{"color": "#e5e5e5"}]}, {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#9e9e9e"}]
            }, {"featureType": "road", "elementType": "geometry", "stylers": [{"color": "#ffffff"}]}, {
                "featureType": "road.arterial",
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#757575"}]
            }, {"featureType": "road.highway", "elementType": "geometry", "stylers": [{"color": "#dadada"}]}, {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#616161"}]
            }, {"featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"}]}, {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [{"color": "#e5e5e5"}]
            }, {"featureType": "transit.station", "elementType": "geometry", "stylers": [{"color": "#eeeeee"}]}, {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{"color": "#c9c9c9"}]
            }, {"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"}]}],
            "retro": [{"elementType": "geometry", "stylers": [{"color": "#ebe3cd"}]}, {"elementType": "labels.text.fill", "stylers": [{"color": "#523735"}]}, {
                "elementType": "labels.text.stroke",
                "stylers": [{"color": "#f5f1e6"}]
            }, {"featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{"color": "#c9b2a6"}]}, {
                "featureType": "administrative.land_parcel",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#dcd2be"}]
            }, {"featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [{"color": "#ae9e90"}]}, {
                "featureType": "landscape.natural",
                "elementType": "geometry",
                "stylers": [{"color": "#dfd2ae"}]
            }, {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]}, {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#93817c"}]
            }, {"featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{"color": "#a5b076"}]}, {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#447530"}]
            }, {"featureType": "road", "elementType": "geometry", "stylers": [{"color": "#f5f1e6"}]}, {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [{"color": "#fdfcf8"}]
            }, {"featureType": "road.highway", "elementType": "geometry", "stylers": [{"color": "#f8c967"}]}, {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#e9bc62"}]
            }, {"featureType": "road.highway.controlled_access", "elementType": "geometry", "stylers": [{"color": "#e98d58"}]}, {
                "featureType": "road.highway.controlled_access",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#db8555"}]
            }, {"featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{"color": "#806b63"}]}, {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [{"color": "#dfd2ae"}]
            }, {"featureType": "transit.line", "elementType": "labels.text.fill", "stylers": [{"color": "#8f7d77"}]}, {
                "featureType": "transit.line",
                "elementType": "labels.text.stroke",
                "stylers": [{"color": "#ebe3cd"}]
            }, {"featureType": "transit.station", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]}, {
                "featureType": "water",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#b9d3c2"}]
            }, {"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"color": "#92998d"}]}],
            "light": [{"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]}, {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
            }, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#ffffff"}, {"lightness": 17}]}, {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]
            }, {"featureType": "road.arterial", "elementType": "geometry", "stylers": [{"color": "#ffffff"}, {"lightness": 18}]}, {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [{"color": "#ffffff"}, {"lightness": 16}]
            }, {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]}, {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{"color": "#dedede"}, {"lightness": 21}]
            }, {"elementType": "labels.text.stroke", "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]}, {
                "elementType": "labels.text.fill",
                "stylers": [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
            }, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [{"color": "#f2f2f2"}, {"lightness": 19}]
            }, {"featureType": "administrative", "elementType": "geometry.fill", "stylers": [{"color": "#fefefe"}, {"lightness": 20}]}, {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
            }],
            "blue-essence": [{"featureType": "landscape.natural", "elementType": "geometry.fill", "stylers": [{"visibility": "on"}, {"color": "#e0efef"}]}, {
                "featureType": "poi",
                "elementType": "geometry.fill",
                "stylers": [{"visibility": "on"}, {"hue": "#1900ff"}, {"color": "#c0e8e8"}]
            }, {"featureType": "road", "elementType": "geometry", "stylers": [{"lightness": 100}, {"visibility": "simplified"}]}, {
                "featureType": "road",
                "elementType": "labels",
                "stylers": [{"visibility": "off"}]
            }, {"featureType": "transit.line", "elementType": "geometry", "stylers": [{"visibility": "on"}, {"lightness": 700}]}, {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{"color": "#7dcdcd"}]
            }],
            "facebook": [{"featureType": "water", "elementType": "all", "stylers": [{"color": "#3b5998"}]}, {
                "featureType": "administrative.province",
                "elementType": "all",
                "stylers": [{"visibility": "off"}]
            }, {"featureType": "all", "elementType": "all", "stylers": [{"hue": "#3b5998"}, {"saturation": -22}]}, {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [{"visibility": "on"}, {"color": "#f7f7f7"}, {"saturation": 10}, {"lightness": 76}]
            }, {"featureType": "landscape.natural", "elementType": "all", "stylers": [{"color": "#f7f7f7"}]}, {
                "featureType": "road.highway",
                "elementType": "all",
                "stylers": [{"color": "#8b9dc3"}]
            }, {"featureType": "administrative.country", "elementType": "geometry.stroke", "stylers": [{"visibility": "simplified"}, {"color": "#3b5998"}]}, {
                "featureType": "road.highway",
                "elementType": "all",
                "stylers": [{"visibility": "on"}, {"color": "#8b9dc3"}]
            }, {"featureType": "road.highway", "elementType": "all", "stylers": [{"visibility": "simplified"}, {"color": "#8b9dc3"}]}, {
                "featureType": "transit.line",
                "elementType": "all",
                "stylers": [{"invert_lightness": false}, {"color": "#ffffff"}, {"weight": 0.43}]
            }, {"featureType": "road.highway", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
                "featureType": "road.local",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#8b9dc3"}]
            }, {"featureType": "administrative", "elementType": "labels.icon", "stylers": [{"visibility": "on"}, {"color": "#3b5998"}]}],
            "muted-blue": [{"featureType": "all", "stylers": [{"saturation": 0}, {"hue": "#e7ecf0"}]}, {"featureType": "road", "stylers": [{"saturation": -70}]}, {
                "featureType": "transit",
                "stylers": [{"visibility": "off"}]
            }, {"featureType": "poi", "stylers": [{"visibility": "off"}]}, {"featureType": "water", "stylers": [{"visibility": "simplified"}, {"saturation": -60}]}],
            "mid-night": [{"featureType": "all", "elementType": "labels.text.fill", "stylers": [{"color": "#ffffff"}]}, {
                "featureType": "all",
                "elementType": "labels.text.stroke",
                "stylers": [{"color": "#000000"}, {"lightness": 13}]
            }, {"featureType": "administrative", "elementType": "geometry.fill", "stylers": [{"color": "#000000"}]}, {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#144b53"}, {"lightness": 14}, {"weight": 1.4}]
            }, {"featureType": "landscape", "elementType": "all", "stylers": [{"color": "#08304b"}]}, {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{"color": "#0c4152"}, {"lightness": 5}]
            }, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#000000"}]}, {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#0b434f"}, {"lightness": 25}]
            }, {"featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{"color": "#000000"}]}, {
                "featureType": "road.arterial",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#0b3d51"}, {"lightness": 16}]
            }, {"featureType": "road.local", "elementType": "geometry", "stylers": [{"color": "#000000"}]}, {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [{"color": "#146474"}]
            }, {"featureType": "water", "elementType": "all", "stylers": [{"color": "#021019"}]}]
        };
        if ($(".xlwcty-map-component").length > 0) {
            var mapIncrement = 0;
            $(".xlwcty-map-component").each(
                function () {
                    var mapData = {};
                    var $this = $(this);
                    mapData.address = xlwctyDecodeString($this.attr("data-address"));
                    mapData.icon = xlwctyDecodeString($this.attr("data-nm-icon"));
                    mapData.zoom = parseInt($this.attr("data-zoom-level"));
                    mapData.marker_text = xlwctyDecodeString($this.attr("data-marker-text"));
                    mapData.marker_text = "<div class='xlwctymarkertext'>" + mapData.marker_text + "</div>";
                    var style = $this.attr("data-style");

                    var style_data = [];
                    if (style != "" && styles.hasOwnProperty(style) == true) {
                        style_data = styles[style];
                    }
                    geocoder.geocode(
                        {'address': mapData.address}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                var latitude = results[0].geometry.location.lat();
                                var longitude = results[0].geometry.location.lng();
                                mapData.lat = latitude;
                                mapData.lng = longitude;

                                if (mapData.lat != '' && mapData.lng != '') {
                                    mapData.center = {lat: mapData.lat, lng: mapData.lng};
                                    var mapOptions = {
                                        center: mapData.center,
                                        zoom: mapData.zoom,
                                        styles: style_data
                                    };
                                    mapOptions = xlwcty_hooks.applyFilters("xlwcty_map_options", mapOptions);
                                    xlwctyMap[mapIncrement] = new google.maps.Map($this[0], mapOptions);
                                    var markerData = {
                                        map: xlwctyMap[mapIncrement],
                                        position: mapData.center,
                                        title: mapData.address,
                                        content: mapData.marker_text
                                    };
                                    if (mapData.icon != "") {
                                        markerData.icon = mapData.icon;
                                    }
                                    if (mapData.address != "") {
                                        markerData.address = mapData.address;
                                    }

                                    markerData = xlwcty_hooks.applyFilters("xlwcty_marker_data", markerData, xlwctyMap[mapIncrement]);
                                    var inforpopup = new InfoBubble(
                                        {
                                            minWidth: 200,
                                            maxHeight: 200,
                                            minHeight: 120,
                                            content: markerData.content,
                                            closeSrc: xlwcty.plugin_url + '/assets/img/close.png',
                                        }
                                    );
                                    xlwctyMarker[mapIncrement] = new google.maps.Marker(markerData);
                                    google.maps.event.addListener(
                                        xlwctyMarker[mapIncrement], 'click', function () {
                                            inforpopup.open(xlwctyMap[mapIncrement], xlwctyMarker[mapIncrement]);
                                            xlwctyMap[mapIncrement].panTo(xlwctyMarker[mapIncrement].getPosition());
                                            xlwctyMap[mapIncrement].setCenter(xlwctyMarker[mapIncrement].getPosition());
                                        }
                                    );
                                }
                            } else {
                                var errorMsg = '';
                                if (status == google.maps.GeocoderStatus.ERROR || status == google.maps.GeocoderStatus.UNKNOWN_ERROR) {
                                    errorMsg = xlwcty.map_errors.error;
                                } else if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                                    errorMsg = xlwcty.map_errors.over_limit;
                                } else if (status == google.maps.GeocoderStatus.REQUEST_DENIED) {
                                    errorMsg = xlwcty.map_errors.request_denied;
                                } else if (typeof xlwcty.settings.google_map_error_txt !== 'undefined') {
                                    errorMsg = xlwcty.map_errors.xlwcty.settings.google_map_error_txt;
                                }
                                $this.html("<div class='xlwcty_map_error_txt'>" + errorMsg + "</div>");
                            }
                        }
                    );
                    mapIncrement++;
                }
            );
        }
    };

    xlwctyCore.loadmap = function () {
        if (typeof google != "undefined" && google.hasOwnProperty("maps") === true && xlwcty_is_google_map_failed == false) {
            console.log("XLWCTY Log: google map already loaded");
            if (xlwcty.infobubble_url != "" && typeof InfoBubble !== 'function') {
                console.log("XLWCTY Log: loading infobubble script");
                infobubble_init();
            } else {
                map_init();
            }
        } else {
            if (xlwcty.google_map_key != "") {
                $.getScript(
                    "//maps.googleapis.com/maps/api/js?key=" + xlwcty.google_map_key, function (data, textStatus, jqxhr) {
                        console.log("XLWCTY Log: google map loaded");
                        if (jqxhr.status === 200) {
                            var old_google = google;
                            infobubble_init();
                        }
                    }
                );
            }
        }
    };

    xlwctyCore.loadmap();

})(jQuery);
