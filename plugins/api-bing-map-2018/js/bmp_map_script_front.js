// WP_BINGMAPPRO 07-12-2019

function bmp_loadMapScenario( bmp_map_id, bmp_map_pin, bmp_map, bmp_infobox, bmp_shapes, bmp_params ) { 
   
    // var bmp_map_arr = ['street', 'aerial', 'birdseye', 'canvasDark', 'canvasLight', 'grayscale'];
    var bmp_nav_options     = [Microsoft.Maps.NavigationBarMode.default, Microsoft.Maps.NavigationBarMode.compact, Microsoft.Maps.NavigationBarMode.square ];
    var bmp_map_lat     = bmp_map.map_start_lat;
    var bmp_map_long    = bmp_map.map_start_long;
    var bmp_map_type    = parseInt( bmp_map.map_type );
    var bmp_bing_map_type = null;
    var bmp_map_zoom    = parseInt( bmp_map.map_zoom );
    var bmp_compact_nav =  bmp_nav_options[ bmp_map.compact_nav ];
    var bmp_disable_mousewheel  = (parseInt( bmp_map.disable_mousewheel ) === 1) ? true : false;
    var bmp_map_disable_zooming = ( parseInt( bmp_map.disable_zoom ) === 1 ) ? true : false;
    var show_infobox_hover      = ( parseInt( bmp_map.styling_enabled ) === 1 ) ? true : false ;
    var bmp_adv_infobox_width   = getBmpInfoboxWidth();
    var bmp_adv_infobox_height  = getBmpInfoboxHeight(); 
    var bmpIconsUrl             = bmp_icon_src + 'images/icons/';
    var randId                  = Math.random();
    var clusterPins             = [];
    var bmp_cluster_opt         = bmp_map.cluster;
    var bmp_lock_zoom           = bmp_map.lock_zoom;
    var bmp_map_view_bounds     = null;

    if( bmp_params.length > 0){
        bmp_params = bmp_params[0];
        if( (typeof bmp_params.s_lat !== 'undefined') && ( typeof bmp_params.s_long !== 'undefined') && ( typeof bmp_params.s_zoom !== 'undefined') ){
            if( bmp_params.s_lat.trim() !== '' )
                bmp_map_lat = bmp_params.s_lat.trim();

            if( bmp_params.s_long.trim() !== '' )
                bmp_map_long = bmp_params.s_long.trim();
            
            if( bmp_params.s_zoom.trim() !== '' ){
                let new_zoom = parseInt( bmp_params.s_zoom );
                if( (new_zoom > 0 ) && ( new_zoom < 21 ) )
                    bmp_map_zoom = new_zoom;
            }
        }
    }

    if ( bmp_fullScreenEnabled() ) {
        //Use an event to detect when entering/exiting full screen as user may use esc to exit full screen.
        bmp_addFullScreenEvent(function (e) {
           
            var mapContainer = document.getElementById( bmp_map_id.id );

            if ( bmp_isFullScreen() ) {
                //Change the size of the map div so that it fills the screen.
                mapContainer.classList.remove('standardMap');
                mapContainer.classList.add('fullScreenMap');
                mapContainer.querySelector('.bmp_map_fullscreen_icon_class ').src = bmpFullscreenIconSrc2;  
            } else {
                //Change the size of the map div back to its original size.
                mapContainer.classList.remove('fullScreenMap');
                mapContainer.classList.add('standardMap');
                mapContainer.querySelector('.bmp_map_fullscreen_icon_class ').src = bmpFullscreenIconSrc;  
            }
        });
    } else {
        document.getElementById('fullScreenToggle').disabled = true;
    }

    switch( bmp_map_type ) {
        case 0 : bmp_bing_map_type = Microsoft.Maps.MapTypeId.street; break;
        case 1 : bmp_bing_map_type = Microsoft.Maps.MapTypeId.aerial; break;    
        case 2 : bmp_bing_map_type = Microsoft.Maps.MapTypeId.birdseye; break;
        case 3 : bmp_bing_map_type = Microsoft.Maps.MapTypeId.canvasDark; break;
        case 4 : bmp_bing_map_type = Microsoft.Maps.MapTypeId.canvasLight; break;
        case 5 : bmp_bing_map_type = Microsoft.Maps.MapTypeId.grayscale;break;
        default : bmp_bing_map_type = Microsoft.Maps.MapTypeId.street; break;
    }
    
    var bmp_center = new Microsoft.Maps.Location( bmp_map_lat, bmp_map_long );

    var bmp_wp_map = new Microsoft.Maps.Map( bmp_map_id , {
        center : bmp_center,
        mapTypeId :             bmp_bing_map_type,
        disableScrollWheelZoom: bmp_disable_mousewheel,
        zoom:                   bmp_map_zoom,
        navigationBarMode:      bmp_compact_nav,
        disableZooming :        bmp_map_disable_zooming   
    });  

    bmp_map_view_bounds = bmp_wp_map.getBounds();

    if( bmp_lock_zoom == 1 ){
        bmp_wp_map.setOptions({
            maxBounds : bmp_map_view_bounds
        })
    }
  
    bmp_infobox = new Microsoft.Maps.Infobox( bmp_center, {
        visible : false
    });

    bmp_infobox.setMap( bmp_wp_map );

    var bmp_pin_title, bmp_pin_desc, bmp_pin_lat, bmp_pin_long, bmp_pin_img_src, bmp_custom_pin,
        bmp_pin_img_src, bmp_is_default_icon, bmp_pin_infobox_type, bmp_pin_html;
    for( var i = 0; i < bmp_map_pin.length; i++ ){
        bmp_pin_title = bmp_map_pin[i].pin_title;
        bmp_pin_title = bmp_pin_title.replace(/\\/g, '');        
        bmp_pin_desc  = bmp_map_pin[i].pin_desc;
        bmp_pin_desc  = bmp_pin_desc.replace(/\\/g, '');
        bmp_pin_desc  = bmp_pin_desc.replace(/bmp_nl/g, '<br/>');
        bmp_pin_lat   = bmp_map_pin[i].pin_lat;
        bmp_pin_long  = bmp_map_pin[i].pin_long; 
        bmp_pin_img_src = bmp_map_pin[i].icon_link; 
        bmp_is_default_icon = false;  
        bmp_pin_infobox_type = bmp_map_pin[i].pin_image_two;
        bmp_pin_html = bmp_map_pin[i].data_json.replace(/\\/g, '' );
        
        if( bmp_pin_img_src != '' ){
            if( ! bmp_pin_img_src.includes('http') )
                bmp_pin_img_src   =  bmp_icon_src + 'images/icons/custom-icons/' + bmp_pin_img_src;
        }else{
            if( bmp_map_pin[i].icon == '0' )  //default icon
                bmp_is_default_icon = true;
            else
                bmp_pin_img_src = bmp_icon_src + 'images/icons/default/pin-' + bmp_map_pin[i].icon  + '.png';
        }


        if( ! bmp_is_default_icon ){
            bmp_custom_pin = new Microsoft.Maps.Pushpin( new Microsoft.Maps.Location( bmp_pin_lat, bmp_pin_long), {
                icon: bmp_pin_img_src
            });
        }else
            bmp_custom_pin = new Microsoft.Maps.Pushpin( new Microsoft.Maps.Location( bmp_pin_lat, bmp_pin_long), {});    
        
        //check versions, and new added advanced pin infobox
        if( bmp_pin_infobox_type == '' ){
            if( (bmp_pin_title == '') || (bmp_pin_desc == '') )
                bmp_pin_infobox_type = 'none'
            else 
                bmp_pin_infobox_type = 'simple';
        }

        bmp_custom_pin.metadata = {
            title : bmp_pin_title,
            description : bmp_pin_desc,
            html : bmp_pin_html,
            pintype : bmp_pin_infobox_type,
            width : bmp_adv_infobox_width,
            height: bmp_adv_infobox_height
        };

        if(  show_infobox_hover ){
            if( window.innerWidth > 1024 )
                Microsoft.Maps.Events.addHandler(bmp_custom_pin, 'mouseover', bmp_pushpinClicked );          
            else
                Microsoft.Maps.Events.addHandler(bmp_custom_pin, 'click', bmp_pushpinClicked);
        
        }else{
            Microsoft.Maps.Events.addHandler(bmp_custom_pin, 'click', bmp_pushpinClicked);
        }

        

        if( bmp_cluster_opt == -1 ){//none
            bmp_wp_map.entities.push( bmp_custom_pin );
        }else{ //default
            clusterPins.push( bmp_custom_pin );
        }
       
        
    };

    bmp_shapes.forEach( function( item ){
        var shape_obj = null;

        try{
            item.shapeData = item.shapeData.replace(/\\/g, '');
            item.shapeData = JSON.parse( item.shapeData );
            item.style = item.style.replace(/\\/g, '' );
            item.style = JSON.parse( item.style );
        }catch( e ){
            console.error( 'Error parsing ' + e.message );
            console.error( 'Error on ' + item.name );
        //    continue;
        }

        if( item.type == 'line'){

            var points = [];
            if( item.shapeData.length > 0 ){
                if( typeof item.shapeData[0].lat !== 'undefined' ){ //old way, slower
                    for( var i = 0; i < item.shapeData.length; i++ ){
                        let newPoint = new Microsoft.Maps.Location( item.shapeData[i].lat, item.shapeData[i].long );                        
                        points.push( newPoint );
                    }
                }else{
                    points = item.shapeData; //new way, load polygon positions
                }                          
            } 
            shape_obj = new Microsoft.Maps.Polyline( points, item.style );             
           
        }else if( item.type == 'circle'){
            let lat = item.shapeData[0].latitude;
            let long = item.shapeData[0].longitude;
            let init_radius = item.shapeData[0].radius;   
     
            let location = new Microsoft.Maps.Location( lat, long );
            
            shape_obj = new Microsoft.Maps.Polygon([ location, location, location ], item.style );           

            //Calculate circle locations.
            Microsoft.Maps.loadModule( 'Microsoft.Maps.SpatialMath', function(){            
                var locs = Microsoft.Maps.SpatialMath.getRegularPolygon(location, init_radius, 200 );
                shape_obj.setLocations(locs);               
            });

        }else if( item.type == 'polygon'){            

            var points = [];
            if( item.shapeData.length > 0 ){
                if( typeof item.shapeData[0].lat !== 'undefined' ){ //old way, slower
                    for( var i = 0; i < item.shapeData.length; i++ ){
                        let newPoint = new Microsoft.Maps.Location( item.shapeData[i].lat, item.shapeData[i].long );                        
                        points.push( newPoint );
                    }
                }else{
                    points = item.shapeData; //new way, load polygon positions
                }                          
            }

            shape_obj = new Microsoft.Maps.Polygon( points, item.style );
        }

        if( item.type == 'line' || item.type =='circle' || item.type == 'polygon'){
            shape_obj.metadata = {
                title : item.infoSimpleTitle,
                description : bmp_decode_str( item.infoSimpleDesc ), 
                html : bmp_decode_str ( item.infoAdvanced),
                pintype : item.infoType,
                width : getBmpInfoboxWidth(),
                height: getBmpInfoboxHeight() 
            }            

            infobox = new Microsoft.Maps.Infobox(bmp_wp_map.getCenter(), {
                visible: false
            });

            infobox.setMap( bmp_wp_map );

            if(  show_infobox_hover ){
                Microsoft.Maps.Events.addHandler(shape_obj, 'mouseover', bmp_pushpinClicked ); 
            //    Microsoft.Maps.Events.addHandler(bmp_new_pin, 'mouseout', bmpInfoboxHideMe ); 
                Microsoft.Maps.Events.addHandler(shape_obj, 'mousedown', bmp_pushpinClicked ); 
            }else{
                Microsoft.Maps.Events.addHandler(shape_obj, 'click', bmp_pushpinClicked);
            }

            bmp_wp_map.entities.push( shape_obj );
        }  
        

    });

    if( bmp_cluster_opt == 0 ){ //default
        Microsoft.Maps.loadModule( 'Microsoft.Maps.Clustering', function(){
            clusterLayer = new Microsoft.Maps.ClusterLayer(clusterPins, {
                clusteredPinCallback : createCustomClusteredPin,
                gridSize : 210
            });
            bmp_wp_map.layers.insert( clusterLayer );
        }); 
    }

    function bmp_pushpinClicked(e) {
        //Make sure the infobox has metadata to display.
        bmp_infobox._customInfobox = false;
    
        if (e.target.metadata) {
            //Set the infobox options with the metadata of the pushpin.
            let location = e.location;
            let shape = e.target;
            if( shape instanceof Microsoft.Maps.Pushpin )
                location = shape.getLocation();
            
            bmp_infobox.changed.isPreviouslyInvoked = false;
            if( e.target.metadata.pintype === 'simple' ){ //simple
                
                if( ( e.target.metadata.title.length > 0 ) &&
                    ( e.target.metadata.description.length > 0 ) ){                                   
                        bmp_infobox.setOptions({
                                customInfobox : false,
                                location: location,
                                title : e.target.metadata.title,
                                description: e.target.metadata.description,
                                visible: true,
                                htmlContent: false,
                                offset: {x:0, y:0, z:0}                          
                        });     
                }
            }else if( e.target.metadata.pintype === 'advanced' ){// advanced
                bmp_infobox.setOptions({
                    customInfobox: true,
                    location: location,
                    htmlContent: '<div class="bmp_pin_info_wrapper" >' +   
                                    '<div class="bmp_pin_info_container" >' +    
                                        '<div class="bmp_pin_info_header">   <div id="bmp_pin_info_close_img_'+randId+'"> '+
                                            '<img class="bmp-infobox-close-svg"  src="'+ bmpIconsUrl +'bmp-infobox-close.svg" /> </div></div>' +
                                        
                                        '<div class="bmp_pin_info_body" style="width:' + e.target.metadata.width + 'px; height: '+ e.target.metadata.height+'px"  >' + e.target.metadata.html +  
                                    '</div>' +                               
                                    '</div>' +
                                    '<div class="bmp_pin_info_down_arrow"></div>' +
                                 '</div>',
                    visible: true,
                    offset: new Microsoft.Maps.Point( ( e.target.metadata.width / 2 ) * -1.05 , 12 )
                });

                document.getElementById('bmp_pin_info_close_img_'+randId ).addEventListener('click', function(){
                    bmp_infobox.setOptions({
                        visible: false
                    });   
                });               
            }    
        }


    }

    
    function getBmpInfoboxWidth(){
        let window_width = window.innerWidth;
        let tablet_width = 1024;
        let mobile_width = 640;
    
        if( window_width <= mobile_width )
            return bmpPinSizes.bmp_pin_mobile_width;
        else if( window_width <= tablet_width )
            return bmpPinSizes.bmp_pin_tablet_width;
        else
            return bmpPinSizes.bmp_pin_desktop_width;
    }
    
    function getBmpInfoboxHeight(){
        let window_height = window.innerHeight;
        let tablet_height = 868;
        let mobile_height = 580;
    
        if( window_height <= mobile_height )
            return bmpPinSizes.bmp_pin_mobile_height;
        else if( window_height <= tablet_height )
            return bmpPinSizes.bmp_pin_tablet_height;
        else
            return bmpPinSizes.bmp_pin_desktop_height;
    }

    function bmpInfoboxHideMe(){
        bmp_infobox.setOptions({
            visible: false
        }); 
    }

    function bmp_decode_str( rawStr ){
        rawStr = rawStr.replace( /&#(\d+);/g, function( match, dec){
            if( dec == 10 )
                return '</br>';
            else
                return String.fromCharCode( dec );
        }).replace(/<br\/>/g, '\n');
        return rawStr;
    }

    function createCustomClusteredPin(cluster) {

        //Define variables for minimum cluster radius, and how wide the outline area of the circle should be.
    
        var minRadius = 20;
    
        var outlineWidth = 10;
    
    
    
    //Get the number of pushpins in the cluster
    
        var clusterSize = cluster.containedPushpins.length;
    
    
    
    //Calculate the radius of the cluster based on the number of pushpins in the cluster, using a logarithmic scale.
    
        var radius = Math.log(clusterSize) / Math.log(10) * 5 + minRadius;
    
    
    
    //Default cluster color is red.
    
        var fillColor = 'rgba(20, 180, 20, 0.5)'; 
    
    
    
        if (clusterSize > 10) {
    
            //Make the cluster green if there are less than 10 pushpins in it.
    
            fillColor = 'rgba(255, 40, 40, 0.5)';           
    
        } else if (clusterSize > 100) {
    
            //Make the cluster yellow if there are 10 to 99 pushpins in it.
    
            fillColor = 'rgba(255, 210, 40, 0.5)';
    
        }
    
    
    
        //Create an SVG string of two circles, one on top of the other, with the specified radius and color.
    
        var svg = ['<svg xmlns="http://www.w3.org/2000/svg" width="', (radius * 2), '" height="', (radius * 2), '">',
    
        '<circle cx="', radius, '" cy="', radius, '" r="', radius, '" fill="', fillColor, '"/>',
    
        '<circle cx="', radius, '" cy="', radius, '" r="', radius - outlineWidth, '" fill="', fillColor, '"/>',
    
        '</svg>'];
    
    
    
        //Customize the clustered pushpin using the generated SVG and anchor on its center.
    
        cluster.setOptions({
    
            icon: svg.join(''),
    
            anchor: new Microsoft.Maps.Point(radius, radius),
    
            textOffset: new Microsoft.Maps.Point(0, radius - 8) //Subtract 8 to compensate for height of text.
    
        });
    
    }



}

function bmp_toggleFullScreen( $container_id ) {    
    var mapContainer = document.getElementById( $container_id );

    if (bmp_isFullScreen()) {
        //Is fullscreen, exit.
        var closeFullScreenFn = document.cancelFullScreen
                    || document.webkitCancelFullScreen
                    || document.mozCancelFullScreen
                    || document.msExitFullscreen;

        closeFullScreenFn.call(document);            
    } else {
        //Make map full screen.
        var openFullScreenFn = mapContainer.requestFullScreen
                    || mapContainer.webkitRequestFullScreen
                    || mapContainer.mozRequestFullScreen
                    || mapContainer.msRequestFullscreen;

        openFullScreenFn.call(mapContainer);
    }
}

function bmp_addFullScreenEvent(callback) {
    var changeEventName;

    if (document.cancelFullScreen) {
        changeEventName = 'fullscreenchange'
    } else if (document.webkitCancelFullScreen) {
        changeEventName = 'webkitfullscreenchange'
    } else if (document.mozCancelFullScreen) {
        changeEventName = 'mozfullscreenchange'
    } else if (document.msExitFullscreen) {
        changeEventName = 'MSFullscreenChange'
    } 
    
    if (changeEventName) {
        document.addEventListener(changeEventName, callback);
    }
}

function bmp_isFullScreen() {
    return !(!document.fullscreenElement &&
        !document.msFullscreenElement &&
        !document.mozFullScreenElement &&
        !document.webkitFullscreenElement);
}

//Determines if fullscreen can be requested of not.
function bmp_fullScreenEnabled() {
    return document.fullscreenEnabled ||
       document.msFullscreenEnabled ||
       document.mozFullScreenEnabled ||
       document.webkitFullscreenEnabled;
}
