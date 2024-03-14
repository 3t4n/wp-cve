var IsraelPostMap = {
    map: null,
    activeInfo: null,
    infoWindows: {},
    markers: {},

    init: function () {
        var _this = this;
        $j(document).on('click', '.close-infoBox', function (event) {
            _this.closeInfobox();
            event.preventDefault();
        }.bind(this));

        $j( "body" ).on( 'click', '.selectspot', function (e) {
            e.preventDefault();
            var spotId = $j(this).data('shopid');
            _this.selectSpot(_this.markers[spotId].json);
        });

        var searchInput = $j('#pac-input');
        $j(window).trigger('resize');

        this.drawMap();
    },

    drawMap: function(){
        this.map = new google.maps.Map(document.getElementById('israelpost-map'), {
            center: {lat: 32.063940, lng: 34.837801},
            zoom: 12
        });
        var map = this.map;
        var searchContainer = document.getElementById('israelpost-autocompelete');
        var input = (document.getElementById('pac-input'));
        var legend = (document.getElementById('legend'));
        this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(legend);
        this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(searchContainer);

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', this.map);

        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29)
        });
		
		autocomplete.addListener('place_changed', function () {
            marker.setVisible(false);
            var place = autocomplete.getPlace();

            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }
            marker.setIcon(({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
        });
    },

    pinMarkers: function (spots) {
        spots = spots || {};
        var _this = this,
            infoboxOptions,
            spot;

        //loop trough shops
        for (i in spots) {
            spot = spots[i];
            infoboxOptions = {
                content: '<div><a href="#" class="close close-infoBox"></a>' +
                '<h3>' + spot.name + '</h3>' +
                '<p>' + spot.street + ' ' + spot.house +
                '<br />' + spot.city +
                '<br />' + spot.remarks +
                '</p><ul class="hours">' + _this.generateHours(spot) + '</ul>' +
                '<a href="#" data-shopid="' + spot.n_code + '" class="selectspot">' + Translator.translate('Select') + ' ' + spot.type + ' &raquo;</a></div>',
                disableAutoPan: false,
                maxWidth: 0,
                pixelOffset: new google.maps.Size(0, -10),
                zIndex: null,
                boxStyle: {
                    width: "235px"
                },
                closeBoxURL: "",
                infoBoxClearance: new google.maps.Size(20, 20),
                isHidden: false,
                pane: "floatPane",
                enableEventPropagation: true
            };
            this.infoWindows[spot.n_code] = new InfoBox(infoboxOptions);

            var icon = IsraelPostCommon.getConfig('redDotPath');
            if (spot.type == 'חנות') {
                var icon = IsraelPostCommon.getConfig('grnDotPath');
            }
            //google maps marker
            this.markers[spot.n_code] = new google.maps.Marker({
                position: new google.maps.LatLng(spot.latitude, spot.longitude),
                map: this.map,
                icon: icon,
                shape: null,
                zIndex: 1,
                json: spot
            });
            google.maps.event.addListener(this.markers[spot.n_code], 'click', (function (marker) {
                return function () {
                    _this.clickSpot(marker.json.n_code);
                }.bind(this)
            }.bind(this))(this.markers[spot.n_code]));
        }

        google.maps.event.addListenerOnce(_this.map, 'idle', function () {
            google.maps.event.trigger(_this.map, 'resize');
        });
    },

    clickSpot: function (spotid) {
        //move map to center of this marker
        this.map.panTo(this.markers[spotid].getPosition());
        //open the infobubble
        if (this.activeInfo != null) {
            this.infoWindows[this.activeInfo].close();
        }
        this.infoWindows[spotid].open(this.map, this.markers[spotid]);
        //active marker is this one
        this.activeInfo = spotid;
    },

    generateHours: function (json) {
        var hoursoutput = '';
        return hoursoutput;
    },

    selectSpot: function (spot) {
        var html = this.spotTemplate = '<strong>' + Translator.translate('Branch name') + ':</strong> '+ spot.name +' <br/>'
            + '<strong>' + Translator.translate('Branch address') + ':</strong> '+ spot.street +' '+ spot.house +', '+ spot.city +' <br/>'
            + '<strong>' + Translator.translate('Operating hours') + ':</strong> '+ spot.remarks
        IsraelPost.saveSpotInfo(spot)
        IsraelPost.renderSpotInfo(html);
        IsraelPost.renderSpotId(spot.n_code);
        IsraelPost.closeModal();
    },

    closeInfobox: function () {
        if (this.activeInfo != null && this.infoWindows[this.activeInfo])
            this.infoWindows[this.activeInfo].close();

        this.activeInfo = null;
    },

    resize: function () {
        google.maps.event.trigger(this.map, "resize");
    }
};