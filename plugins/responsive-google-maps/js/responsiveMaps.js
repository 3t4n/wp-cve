/*!
 * jQuery Responsive Maps Plug-In
 * version: 1.1.1
 * Requires jQuery v1.5 or later
 * Copyright (c) 2015 Ilja Zaglov | imbaa Kreativagentur | http://www.imbaa.de
 * Licensed under GPL
 */

var responsiveMap = function(mapID){


    $ = jQuery;


    var element = $('#'+mapID);

    var self = this;

    self.map = null;


    self.isTouchDevice = function () {
        return !!('ontouchstart' in window);
    };


    self.init = function() {

        
        var args = element.data();

        if(args.style == 'monochrome'){

            var styleArray = [
                {
                    featureType: "all",
                    stylers: [
                        { saturation: -100 }
                    ]
                },{
                    featureType: "road.arterial",
                    elementType: "geometry",
                    stylers: [

                        { saturation: 0 }
                    ]
                },{
                    featureType: "poi.business",
                    elementType: "labels",
                    stylers: [
                        { visibility: "off" }
                    ]
                }
            ];


        } else {

            var styleArray = [];

        }


        if(self.isTouchDevice()) {
            var draggable = false;
        } else {
            var draggable = args.draggable
        }



        if(args.maptype == 'satellite'){

            args.maptype = google.maps.MapTypeId.SATELLITE;

        } else if (args.maptype == 'road'){

            args.maptype = google.maps.MapTypeId.ROAD;

        }




        var mapOptions = {
            zoom: parseInt(args.zoom),
            center: new google.maps.LatLng(args.lat, args.lng),
            styles:styleArray,
            zoomControl: args.zoomcontrol,
            disableDoubleClickZoom: false,
            mapTypeControl: args.maptypecontrol,
            scaleControl:args.scalecontrol,
            scrollwheel: args.scrollwheel,
            panControl: args.pancontrol,
            streetViewControl: args.streetviewcontrol,
            draggable : draggable,
            overviewMapControl: args.overviewmapcontrol,
            overviewMapControlOptions: {
                opened: args.overviewmapcontrol
            },
            mapTypeId: args.maptype

        };

        self.map = new google.maps.Map(document.getElementById(mapID), mapOptions);


        if(args.infoWindowText != ''){

            self.infoWindow = new google.maps.InfoWindow();

            self.infoWindow.setOptions({
                content: "<div>"+args.infoWindowText+"</div>",
                position: new google.maps.LatLng(args.lat, args.lng)
            });
        }



        if(args.showmarker == true){



            if(args.icon == ''){


                self.marker = new google.maps.Marker({
                    position: new google.maps.LatLng(args.lat, args.lng),
                    map: self.map,
                    title: args.title,

                });

            } else {

                self.marker = new google.maps.Marker({
                    position: new google.maps.LatLng(args.lat, args.lng),
                    map: self.map,
                    title: args.title,
                    icon: args.icon

                });

            }

            if(args.infoWindowText != ''){


                google.maps.event.addListener(self.marker, 'click', function() {
                    self.infoWindow.open(self.map,self.marker);
                });

                if(args.autoopeninfowindow == true){

                    self.infoWindow.open(self.map,self.marker);

                }

            }




        } else {

            if(args.infoWindowText != ''){

                self.infoWindow.open(self.map);

            }

        }




        $(window).bind('resize',function(){

            self.map.setCenter(new google.maps.LatLng(args.lat, args.lng));

        });








        $(element).removeClass('loading');





    };

    self.init(mapID);

};


function responsive_map_load_google() {



    if (typeof google === 'undefined') {

        var script = document.createElement('script');

        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&key='+gmoptions.apikey+'&callback=responsive_map_initialize';

        document.body.appendChild(script);

    } else {

        responsive_map_initialize();

    }
}


function responsive_map_initialize(){
    $ = jQuery;

    $(document).ready(function(){


        $('.responsiveMap').each(function(){


            new responsiveMap($(this).attr('id'));


        });


    });
}

window.onload = responsive_map_load_google;