var bmpX = jQuery.noConflict();
var bmp_changedValue = false;
var bmpIconsUrl = '';
var bmp_admin_show_map = null;
var bmp_bing_map = null;
var bmp_map_types_array = [];
var bmp_map_types_array2 = ['r', 'a', 'be2', 'wd', 'wl', 'cg'];
var lChanged = false;
var lLoaded = false;

bmpX( function( bmpX ){
    var elHref = ''; 
    bmpX('a').click( function(event ){
        elHref = this.href;        
        if( bmp_changedValue ){
            event.preventDefault();
            bmpX('.modal_settings_page').modal('show');
        }
        bmp_changedValue = false;
    });

    bmpX('.modal_settings_page_close').click( function(){
        bmpX( '.modal_settings_page' ).modal( 'hide');
    });    
    
    /*
    bmpX('.modal_settings_page_ok').click( function(){

    });
*/
    function BmpGoToPage(){
        window.location.href = elHref;
        bmpX( '.modal_settings_page' ).modal( 'hide');
    }

    bmpX('#btn_map_snap_view').on('click', function(){
        bmpX('#input_view_name').val('');
        bmpX('#input_view_lat').val( bmpX('#bmp_map_start_lat').val().trim() );
        bmpX('#input_view_long').val( bmpX('#bmp_map_start_long').val().trim() );
        bmpX('#input_view_zoom').val( bmpX('#bmp_map_zoom').val().trim() );
        bmpX('.bmp-modal-map-view').modal({
            show : true,
            backdrop : 'static'
        });

        bmpX('.bmp-modal-map-view').on('shown.bs.modal', function(){
            bmpX('#input_view_name').focus();
        });
    }); 

    bmpX('#save_map_view').on('click', function(){
        let name = bmpX('#input_view_name').val().trim();
        if( name == '' ){
            bmpX('.bmp-modal-map-view .modal-header').bmp_message( s_NameCannotBeEmpty, 'danger');
            bmpX('#input_view_name').focus();
            return;
        }else{
            let data = {};
            data.bmp_map_action = 'bmp_save_map_view';
            data.name = name.replace(/\W/g, '' );
            data.lat = bmpX('#input_view_lat').val().trim();
            data.long = bmpX('#input_view_long').val().trim();
            data.zoom = bmpX('#input_view_zoom').val().trim();
            data.map_id = bmpDataMap[0].id;  
            data.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();       
            
            sendBmpMapView( data, bmpX('.bmp-modal-map-view'), bmp_build_table_map_views );
        }
        
    });


    //-----------------------------New Map ---------------
    bmpX('.newMap').click( function(){
        bmpX('#newMapModal').modal({
            show: true,
            backdrop: 'static'
        });
        bmpX('#new_map_input').val('');
        bmpX('#newMapModal').on('shown.bs.modal', function(){            
            bmpX('#new_map_input').focus();
        });
        
    });

    bmpX('.edit-bmp-map').click( function(){
        BmpEditMap( this );
    });
    
    //==============================================
    //----------------------- map settings ---------
    bmpX('#bmp_map_active, #bmp_map_disable_mousewheel, #bmp_map_compact_nav, #bmp_map_toggle_fullscreen').on( 'change', function(){
        SetChanged( true );        
    });

    bmpX('.bmp-map-type').on('change', function(){
        bmpDataMap[0].map_type = parseInt( bmpX(this).val() ); 
        if( bmp_bing_map !== null ){
            bmp_admin_load_map();
        }else{
            RefreshMap();        
        }
    });

    bmpX('#center_map_to_location').on('click', function(){
        let lat = bmpX('#bmp_map_start_lat').val();
        let long = bmpX('#bmp_map_start_long').val();
 
        if( bmp_validLatAndLong( lat, long ) ){
            let newLoc =  new Microsoft.Maps.Location(lat, long);
            bmp_bing_map.setView({
                center : newLoc
            });
        }else{
            bmpX('#bmp_map_page_nav').bmp_message( s_invalidLatOrLong , 'danger');
        }
    });

    bmpX('#bmp_map_zoom').on('change', function(){
        bmpDataMap[0].map_zoom = parseInt( bmpX(this).val() ); 
        if( bmp_bing_map !== null ){
            bmp_bing_map.setView({
                zoom : bmpDataMap[0].map_zoom
            }); 
        }else{
            RefreshMap();        
        }
    });

    bmpX('#bmp_map_zoom').on('input', function(){
        bmpX('#display_zoom_val').text(  bmpX(this).val() );
    });


    bmpX('.bmp-modal-map-adv-settings #save_map_adv_settings').on('click', function(){
        bmpDataMap[0].disable_mousewheel = ( bmpX( '#bmp_map_disable_mousewheel' ).prop('checked') ) ? 1 : 0;
        bmpDataMap[0].lock_zoom = bmpX( '#bmp_map_lock_zoom' ).prop('checked') ? 1 : 0;
        bmpDataMap[0].disable_zoom = ( bmpX( '#bmp_map_disable_zoom' ).prop('checked') ) ? 1 : 0;
        bmpDataMap[0].compact_nav = parseInt( bmpX( '#bmp_map_compact_nav option:selected' ).val() );
        bmpDataMap[0].styling_enabled = ( bmpX( '#bmp_map_show_infobox_hover' ).prop('checked') ) ? 1 : 0;
        bmpDataMap[0].toggle_fullscreen = ( bmpX('#bmp_map_toggle_fullscreen').prop('checked') ) ? 1 : 0;
        bmpDataMap[0].cluster = bmpX( '#bmp_map_cluster option:selected').val();  
        
       
        var classInput = bmpX.trim( bmpX('#bmp_map_html_class').val() );
        if(! classInput.match(/['|"]/g) ){
            bmpX('#bmp_map_html_class').val( classInput );
            bmpDataMap[0].html_class = classInput;
            document.getElementById('bmp_admin_show_map').className = classInput;
        }else{
            bmpX(this).val('');           
        }
          

        if( bmp_bing_map !== null ) 
            bmp_admin_load_map();
        else 
            RefreshMap();                

        bmpX('.bmp-modal-map-adv-settings').modal('hide');
    });

    bmpX('#bmp_map_btn_adv_settings').on('click', function(){
        bmpX( '#bmp_map_disable_mousewheel' ).prop('checked', bmpDataMap[0].disable_mousewheel == 1 ).trigger('change');
        bmpX( '#bmp_map_lock_zoom' ).prop('checked', bmpDataMap[0].lock_zoom == 1 ).trigger('change');
        bmpX( '#bmp_map_disable_zoom' ).prop('checked', bmpDataMap[0].disable_zoom == 1 ).trigger('change');
        bmpX( '#bmp_map_compact_nav option[value="'+bmpDataMap[0].compact_nav+'"]' ).prop('selected', true );
        bmpX( '#bmp_map_show_infobox_hover' ).prop('checked', bmpDataMap[0].styling_enabled == 1 ).trigger('change');
        bmpX( '#bmp_map_toggle_fullscreen').prop('checked', bmpDataMap[0].toggle_fullscreen == 1 ).trigger('change')
        bmpX( '#bmp_map_cluster option[value="'+bmpDataMap[0].cluster+'"]').prop('selected', true );
        bmpX('#bmp_map_html_class').val( bmpDataMap[0].html_class );

        bmpX('.bmp-modal-map-adv-settings').modal({
            show: true,
            backdrop: 'static'
        });
    });


    bmpX('#bmp_map_width').on('input', function( e ){            
        changeMapSize( e, 'width');        
    });

    bmpX('#bmp_map_width_type').on('change', function( e ){            
        changeMapSize( e, 'width');        
    });

    bmpX('#bmp_map_height').on('input', function( e ){            
        changeMapSize( e, 'height');        
    });

    bmpX('#bmp_map_height_type').on('change', function( e ){            
        changeMapSize( e, 'height');        
    });

    bmpX('#bmp_map_title').on('input', function( e ){
        bmpDataMap[0].map_title = bmpX(this).val();               
    });

    bmpX('#bmp_map_active').on('change', function(){
        var active_map = ( bmpX(this).prop('checked') == true ) ? 1 : 0;
        bmpDataMap[0].map_active = active_map;
       
    });  

    bmpX('#bmp_map_pins_anchor').on('click', function(){
        if( ! lChanged ){
            bmpX('#bmp_page_action').val( 'bmp-add-map-pins' );
            bmpX('#bmp_map_form_action').trigger('submit');    
        }
    });
    bmpX('#bmp_edit_map_anchor').on('click', function(){   
        bmpX('#bmp_page_action').val( 'edit-map' );     
        bmpX('#bmp_map_form_action').trigger('submit');       
    });  
    bmpX('#bmp_map_shapes_anchor').on('click', function(){
        if( ! lChanged ){
            bmpX('#bmp_page_action').val( 'bmp-add-map-shapes' );
            bmpX('#bmp_map_form_action').trigger('submit');       
        }
    });  


    bmpX('[data-toggle="tooltip"]').tooltip({
        container : 'body'
    });  

    /* script for map pins */
    if( typeof $bmp_all_pins !== 'undefined' && typeof $bmp_map_pins !== 'undefined' ){
        $bmp_all_pins = JSON.parse( JSON.stringify( $bmp_all_pins ) );

        if( ($bmp_map_pins.length == 0) && ($bmp_all_pins.length == 0) ){
            let href_pins = bmpX('#menu_item_pins a').attr('href');
            let goToPinsPage = '<h5 style="margin-left:5px;">You have no pins created. Click <a href="'+href_pins+'">HERE</a> to create new pins!</h5>';
            bmpX('#tbl_map_all_pins tbody').append( goToPinsPage );
        }else{
            bmpX('#tbl_map_all_pins tbody').empty();   
        }

        if( ($bmp_map_pins.length == 0) && ( $bmp_all_pins.length > 0 ) ){
            let showInfoNoPins = '<h5 style="margin-left:5px;" id="map_pin_info">This map has no pins assigned. Click on the "All Pins" action to add.</h5>';
            bmpX('#tbl_map_added_pins tbody').append( showInfoNoPins ); 
        }
       
        bmpX.each( $bmp_all_pins, function( index, item){  
            var itemFound =  bmpPinIncluded( item );            
            if( itemFound != null ){   
                bmpCreatePinRow( itemFound, 'map_pin' );
                $bmp_all_pins[index] = itemFound;                
            }else{                
                bmpCreatePinRow( item, 'not_map_pin' );                  
            }
        });        
     

    }
    /* end of script for map pins */   
    
    RefreshMap();
    bmp_build_table_map_views();

    //check for changes
    bmpX( '#bmp_map_active, #bmp_map_title, #bmp_map_width, #bmp_map_height, #bmp_map_width_type, #bmp_map_height_type, \
        #bmp_map_start_lat, #bmp_map_start_long, #bmp_map_zoom, .bmp-map-type, #save_map_adv_settings, \
        #save_map_view ').on('change input paste', function(){
        lChanged = true;
    });

    bmpX('a').on('click', function(e){
        
        if( lChanged ){           
            e.preventDefault();      
            let el_anchor = bmpX( this );

            function confirm(){
                lChanged = false;            
                if( ( el_anchor.attr('id') == 'bmp_map_pins_anchor') || (el_anchor.attr('id') == 'bmp_map_shapes_anchor') )
                    el_anchor.trigger('click');
                else
                   window.location.href = bmpX( el_anchor ).attr('href');
            };

            let options = {
                message : '<b>' + s_ChangesRecorded + '</b> <br /> ' +
                          '<p><b>' + s_ContinueWithoutSaving + '</b></p>' +
                          '<p><b>' + s_AnyChangesWontBeSaved + '</b></p>'
            };
            
            bmpX('#wpwrap').bmp_confirm( options, confirm );
            
           
            return false;
        }
    });
    setTimeout( function(){
        lLoaded = true;
    }, 3000);
    
}); 

function bmpPinIncluded( $item ){
    var found = null;
    bmpX.each( $bmp_map_pins, function( index, item){      
        if( item.pin_id == $item.id ){
            $item.active = item.pin_active.toString();
            found = $item;  
            found.active = item.pin_active; 
            return;      
        }
    });  
    return found;
}

/* functions for map pins */
function bmpCreatePinRow( $map_pin, $pin_type ){
   
    var $tr = bmpX('<tr/>').attr('id', 'pin_tr_' + $map_pin.id );
    var $tdAction = '';
    var $tdActive = $pin_type !== 'map_pin';
    var $pinChecked = ( parseInt($map_pin.active) === 1 ) ? 'checked' : '';
    var $isPinActive =  parseInt( $map_pin.active ) === 1;  
    
    var $tdAddress, $tdName, $tdLatLong, $tdIcon;
    var $pin_id = 'active_pin_' + $map_pin.id;
    var $pin_tooltip = 'pin_tool_' + $map_pin.id;
    var $pin_name = $map_pin.pin_name.replace(/\\/g, '' );
    var $pin_address = $map_pin.pin_address.replace(/\\/g, '' );
    var $pin_title = $map_pin.pin_title.replace(/\\/g, '' );
    var $pin_desc = $map_pin.pin_desc.replace(/\\/g, '');
    var $icon_link = $map_pin.icon_link;
    var $pin_icon = $map_pin.icon;
    var $bmp_icon_src = '';
    var $bmp_pin_tooltip = '';

    $pin_title = bmp_encode_str( $pin_title );
    $pin_desc  = bmp_encode_str( $pin_desc );
    $pin_desc  = $pin_desc.replace(/bmp_nl/g, '<br/>');

    if( ($icon_link != '' ) && (! $icon_link.includes('http') ) )  // is custom icon from library
        $bmp_icon_src = $bmp_imgs_src + "images/icons/custom-icons/" + $icon_link;
    else if( $icon_link == '' ) // normal pin
        $bmp_icon_src = $bmp_imgs_src + "images/icons/default/pin-" + $pin_icon + ".png";
    else //http pin 
        $bmp_icon_src = $icon_link;

    if( $map_pin.pin_image_two == '' ){
        if( ( $pin_title != '' ) && ( $pin_desc != '' ) )
            $map_pin.pin_image_two = 'simple';
        else
            $map_pin.pin_image_two = 'none';
    }

    if(  $map_pin.pin_image_two  == 'simple'){
        $bmp_pin_tooltip =  $pin_title + "<hr/>" + $pin_desc;    
    }else if( $map_pin.pin_image_two  == 'advanced' ){
        $bmp_pin_tooltip =  $map_pin.data_json.replace(/'/g, '&#39');
        $bmp_pin_tooltip = $bmp_pin_tooltip.replace(/\"/g, '&#34');
        $bmp_pin_tooltip = $bmp_pin_tooltip.replace(/\\/g, '');
    }

    

    var iconImg = "<img style='cursor:pointer;'  src='" + $bmp_icon_src + "'" +
                  " id='"+ $pin_tooltip + "' data-toogle='tooltip' data-html='true'  data-placement='left' " +
                  " title='" + $bmp_pin_tooltip + "' width='28' height='28' " +
                  " />";


    if( $pin_type === 'map_pin')
        $tdAction = bmpX('<td><img style="cursor: pointer;" src="' + $bmp_imgs_src + 'images/icons/delete-row.png" onclick="bmpRemovePinFromMap('+ parseInt($map_pin.id) + ');" /> </td>');
    else
        $tdAction = bmpX('<td><img style="cursor: pointer;"  src="' + $bmp_imgs_src + 'images/icons/add-row.png" onclick="bmpAddPinToMap('+ parseInt($map_pin.id) + ');" /> </td>'); 
     
    if( $pin_type === 'map_pin')
        $tdActive = bmpX('<td><input id="'+$pin_id +'" ' + $pinChecked + ' type="checkbox" data-on="'+ s_Yes +'" data-off="'+ s_No +'"  data-toggle="toggle" data-size="mini" /> </td>');

    $tdName     = bmpX('<td>' + $pin_name + '</td>'); 
    $tdAddress  = bmpX('<td>' + $pin_address + '</td>'); 
    $tdLatLong  = bmpX('<td> ' + $map_pin.pin_lat + ' | ' + $map_pin.pin_long + '</td>');
    $tdIcon     = bmpX("<td>" + iconImg + "</td>");

    $tr.append( $tdAction ); 
    if( $pin_type === 'map_pin' ){ $tr.append( $tdActive );}
    $tr.append( $tdName ); $tr.append( $tdAddress ); $tr.append( $tdLatLong );
    $tr.append( $tdIcon );

    if( $pin_type === 'map_pin' ){      
        bmpX('#tbl_map_added_pins tbody').append( $tr );      

        if( $isPinActive )
            bmpX('#' + $pin_id).bootstrapToggle('on');
        else
            bmpX('#' + $pin_id).bootstrapToggle('off');
         
        bmpX('#' + $pin_id ).change( function(){

        var $data = {
            pin_id : parseInt( $map_pin.id),
            map_id : parseInt( $bmp_map_id ),
            action : 'disable_pin_from_map'
        }

        $data.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();

        bmp_pin_action( $data ); //ajax call               
        
        });
        
    }else{
        bmpX('#tbl_map_all_pins').append( $tr );
    }
        
    bmpX('#' + $pin_tooltip ).tooltip({
        container : 'body'
    });
  
}

function bmpRemovePinFromMap( $pin_id ){
    var $data = {
        pin_id : $pin_id,
        map_id : parseInt( $bmp_map_id ),
        action : 'remove_pin_from_map'
    }
    $data.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();

    bmp_pin_action( $data ); //ajax call

    bmpX('#tbl_map_added_pins #pin_tr_' + $pin_id ).remove();   
    bmpCreatePinRow( bmpGetPinMap( $pin_id ), 'not_map_pin' ); 

}
function bmpAddPinToMap( $pin_id ){
    var $data = {
        pin_id : $pin_id,
        map_id : parseInt( $bmp_map_id ),
        action : 'add_pin_to_map'
    };

    $data.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();
    
    if(   bmpX( '#tbl_map_added_pins #map_pin_info').length > 0 )    
        bmpX('#tbl_map_added_pins tbody').empty();



    bmp_pin_action( $data ); //ajax call
    bmpX( '#tbl_map_all_pins #pin_tr_' + $pin_id ).remove();
    var $data_pin = bmpGetPinMap( $pin_id );
    $data_pin.active = 1; //always add pin as active to the map - as a default is set to 1 in db
    bmpCreatePinRow( $data_pin , 'map_pin' ); 
}


function bmpGetPinMap( $pin_id ){
    var found = null;
    bmpX.each( $bmp_all_pins, function( index, item){      
        if( item.id == $pin_id ){           
            found = item;   
            return;      
        }
    });  
    return found;
}



/* end of functions for map pins */

function changeMapSize( e , size_type ){
    setTimeout( function(){
        if( size_type == 'width' ){
            e.preventDefault();
            bmpDataMap[0].map_width = parseInt( document.getElementById('bmp_map_width').value );
            var el = document.getElementById('bmp_map_width_type');
            var width_type_val = el.options[el.selectedIndex].textContent;
            bmpDataMap[0].map_width_type = width_type_val.trim();
            document.getElementById( 'bmp_admin_show_map').style.width = bmpDataMap[0].map_width + bmpDataMap[0].map_width_type;
        }else if( size_type == 'height'){
            e.preventDefault();
            bmpDataMap[0].map_height = parseInt( document.getElementById('bmp_map_height').value );
            var el = document.getElementById('bmp_map_height_type');
            var height_type_val = el.options[el.selectedIndex].textContent;
            bmpDataMap[0].map_height_type = height_type_val.trim();
            document.getElementById( 'bmp_admin_show_map').style.height = bmpDataMap[0].map_height + bmpDataMap[0].map_height_type; 
            if( bmpDataMap[0].bicycle == 1 ){
                //update store height
                bmp_updateStoreHeight();
            }
        }
    }, 1000 );
}



function SetChanged( changed ){
    changedValue = changed;

}

function BmpDeleteMap( el ){
    bmpX('#bmp_modal_map').modal('show');
    bmpX('#bmp_map_action').val( 'delete' );
    bmpX('#bmp_map_id').val( bmpX( el ).attr('data-id') );  
}

function bmp_pin_ckb(el, pin_id ){

    var lDisable = el.checked;
    var data = {
        action : 'disable-pin',
        status : lDisable,
        pin_id : pin_id 
    }

    data.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();

    bmp_pin_action( data );
}

function BmpDeletePin( el ){
    bmpX('#bmp_modal_del_pin').modal('show');
    var $pin_id = parseInt( bmpX(el).attr('data-id') );
    bmpX('#bmp_pin_hidden_pin_id').val( $pin_id  );
}
function BmpEditMap(el){
    var $action = 'edit-map';
    var $map_id = bmpX(el).attr('data-id');
    
    
    bmpX('#bmp_page_action').val( $action );
    bmpX('#bmp_page_map_id').val( $map_id );
    bmpX('#bmp_form_action').trigger('submit');
}


function RefreshMap(){
    
    if( bmpX('#bmp_admin_show_map').length > 0){ 
        var bmp_api_key     = bmpDataApiKey.trim();
        var mapRequest      = "https://www.bing.com/api/maps/mapcontrol?key="+ bmp_api_key + "&callback=bmp_admin_load_map";
        CallRestService( mapRequest, bmp_admin_load_map );
    }
   
}

function CallRestService(request) {
            
    var script = document.createElement("script");
    script.setAttribute("type", "text/javascript");
    script.setAttribute("src", request);
    document.body.appendChild(script);
    
}


function bmp_admin_load_map(){
    var bmp_data_map        = bmpDataMap[0];
    var bmp_nav_options     = [Microsoft.Maps.NavigationBarMode.default, Microsoft.Maps.NavigationBarMode.compact, Microsoft.Maps.NavigationBarMode.square ];
    bmp_admin_show_map      = document.getElementById('bmp_admin_show_map');
    bmp_map_types_array = [ 
        Microsoft.Maps.MapTypeId.street,
        Microsoft.Maps.MapTypeId.aerial,
        Microsoft.Maps.MapTypeId.birdseye,
        Microsoft.Maps.MapTypeId.canvasDark,
        Microsoft.Maps.MapTypeId.canvasLight,
        Microsoft.Maps.MapTypeId.grayscale
    ];   

    bmp_admin_show_map.style.width  = bmp_data_map.map_width + bmp_data_map.map_width_type;
    bmp_admin_show_map.style.height = bmp_data_map.map_height  + bmp_data_map.map_height_type;
    bmp_admin_show_map.className    = bmp_data_map.html_class;

    var bmp_map_type            =  null;
    var bmp_disable_mousewheel  = (  bmp_data_map.disable_mousewheel  == '1' ) ? true : false;
    var bmp_compact_nav         = bmp_nav_options[ bmp_data_map.compact_nav ]; 
    var bmp_map_zoom            =  parseInt( bmp_data_map.map_zoom ); 
    var bmp_map_lat             =  bmp_data_map.map_start_lat ;
    var bmp_map_long            =  bmp_data_map.map_start_long ;
    var bmp_map_disable_zooming = ( bmp_data_map.disable_zoom == '1' ) ? true : false ;
    var show_infobox_hover      = ( bmp_data_map.styling_enabled == '1' ) ? true : false ;
    var bmp_toggle_fullscreen   = ( bmp_data_map.toggle_fullscreen == '1' ) ? true : false ; 
    var bmpFullscreenIcon       =  bmpX('<img  id="bmp_map_fullscreen_icon" src="' + bmpFullscreenIconSrc + '"></img>');
    var clusterLayer            = null;
    var clusterPins             = [];
    var bmp_cluster_opt         = bmp_data_map.cluster;
    var bmp_lock_zoom           = bmp_data_map.lock_zoom;
    var bounds                  = null;


    switch( parseInt(bmp_data_map.map_type) ) {
        case 0 : bmp_map_type = Microsoft.Maps.MapTypeId.street; break;
        case 1 : bmp_map_type = Microsoft.Maps.MapTypeId.aerial; break;
        case 2 : bmp_map_type = Microsoft.Maps.MapTypeId.birdseye; break;
        case 3 : bmp_map_type = Microsoft.Maps.MapTypeId.canvasDark; break;
        case 4 : bmp_map_type = Microsoft.Maps.MapTypeId.canvasLight; break;
        case 5 : bmp_map_type = Microsoft.Maps.MapTypeId.grayscale; break;
        default: bmp_map_type = Microsoft.Maps.MapTypeId.street;
    }         

    var map_center = new Microsoft.Maps.Location( bmp_map_lat, bmp_map_long );

    bmp_bing_map = new Microsoft.Maps.Map( bmp_admin_show_map, {
        // No need to set credentials if already passed in URL 
        center: map_center,
        mapTypeId: bmp_map_type,
        disableScrollWheelZoom: bmp_disable_mousewheel,
        zoom: bmp_map_zoom,
        navigationBarMode: bmp_compact_nav,
        disableZooming : bmp_map_disable_zooming,            
    });  

    bounds = bmp_bing_map.getBounds();
    
    if( bmp_lock_zoom == 1 ){

        bmp_bing_map.setOptions({     
            maxBounds : bounds 
        });  
       
    }

    
    bmpX('#bmp_admin_show_map #bmp_map_fullscreen_icon').remove();
    bmpX('#bmp_admin_show_map').append( bmpFullscreenIcon );

    bmpX('#bmp_map_fullscreen_icon').on('click', function(){

    //    bmpX('#bmp_admin_show_map').toggleClass('bmp-admin-map-full-sceen');
      bmp_toggleFullScreen( 'bmp_admin_show_map' );

   });

    if ( bmp_fullScreenEnabled() ) {
        //Use an event to detect when entering/exiting full screen as user may use esc to exit full screen.
        bmp_addFullScreenEvent(function (e) {
        
            var mapContainer = document.getElementById( 'bmp_admin_show_map' );

            if ( bmp_isFullScreen()) {
                //Change the size of the map div so that it fills the screen.
                mapContainer.classList.remove('standardMap');
                mapContainer.classList.add('fullScreenMap');
                mapContainer.querySelector('#bmp_map_fullscreen_icon').src = bmpFullscreenIconSrc2;                
            } else {
                //Change the size of the map div back to its original size.
                mapContainer.classList.remove('fullScreenMap');
                mapContainer.classList.add('standardMap');
                mapContainer.querySelector('#bmp_map_fullscreen_icon').src = bmpFullscreenIconSrc;                
            }
        });
    } else {
        document.getElementById('fullScreenToggle').disabled = true;
    }

    if(bmp_toggle_fullscreen  )
        bmpX('#bmp_map_fullscreen_icon').show();    
    else
        bmpX('#bmp_map_fullscreen_icon').hide(); 

    
    if( bmpMapAllPins !== 'undefined'){
        bmp_bing_map.entities.clear();        

        bmpIconsUrl = bmpIconsSrc + 'images/icons/'; 
          

        for( var i = 0; i< bmpMapAllPins.length; i++ ){
            var pin_lat     = bmpMapAllPins[i].pin_lat;
            var pin_long    = bmpMapAllPins[i].pin_long;
            var pin_title   = bmpMapAllPins[i].pin_title;
            var pin_desc    = bmpMapAllPins[i].pin_desc;
            var pin_infobox_type = bmpMapAllPins[i].pin_image_two;
            var bmp_pin_html = bmpMapAllPins[i].data_json.replace(/\\/g, '' );
            
            pin_desc        = pin_desc.replace(/bmp_nl/g, '<br/>');
            pin_desc        = pin_desc.replace(/\\/g, '');
            pin_title       = pin_title.replace(/\\/g, '' );   
            
            var bmp_pin_img_src = bmpMapAllPins[i].icon_link;
            let lDefaultPin = false;
            if( bmp_pin_img_src.length > 0 ){
                if( ! bmp_pin_img_src.includes('http') )
                    bmp_pin_img_src   =  bmpIconsSrc + 'images/icons/custom-icons/' + bmp_pin_img_src;
            }else{
                if( bmpMapAllPins[i].icon == 0 )
                    lDefaultPin = true;
                else
                    bmp_pin_img_src = bmpIconsSrc + 'images/icons/default/pin-' + bmpMapAllPins[i].icon + '.png';
            }

            var bmp_new_pin = null;
            if( ! lDefaultPin )
                bmp_new_pin = new Microsoft.Maps.Pushpin( new Microsoft.Maps.Location(pin_lat, pin_long ),{
                    icon : bmp_pin_img_src
                });
            else 
                bmp_new_pin = new Microsoft.Maps.Pushpin( new Microsoft.Maps.Location(pin_lat, pin_long ) );

            if( pin_infobox_type == '' ){
                if( (pin_title == '') || (pin_desc == '') )
                    pin_infobox_type = 'none'
                else 
                    pin_infobox_type = 'simple';
            }

            bmp_new_pin.metadata = {
                title : pin_title,
                description : pin_desc,
                html : bmp_pin_html,
                pintype : pin_infobox_type,
                width : getBmpInfoboxWidth(),
                height: getBmpInfoboxHeight()
            };

            infobox = new Microsoft.Maps.Infobox(bmp_bing_map.getCenter(), {
                visible: false
            });

            infobox.setMap( bmp_bing_map );
                   
            if(  show_infobox_hover ){
                Microsoft.Maps.Events.addHandler(bmp_new_pin, 'mouseover', pushpinClicked ); 
            //    Microsoft.Maps.Events.addHandler(bmp_new_pin, 'mouseout', bmpInfoboxHideMe ); 
                Microsoft.Maps.Events.addHandler(bmp_new_pin, 'mousedown', pushpinClicked ); 
            }else{
                Microsoft.Maps.Events.addHandler(bmp_new_pin, 'click', pushpinClicked);
            }
            
            if( bmp_cluster_opt == -1 ){//none
                bmp_bing_map.entities.push( bmp_new_pin );
            }else{ //default
                clusterPins.push( bmp_new_pin );
            }
           
            
        }
    }

    if( bmp_cluster_opt == 0 ){ //default
        Microsoft.Maps.loadModule( 'Microsoft.Maps.Clustering', function(){
            clusterLayer = new Microsoft.Maps.ClusterLayer(clusterPins, {
                clusteredPinCallback : createCustomClusteredPin,
                gridSize : 210
            });
            bmp_bing_map.layers.insert( clusterLayer );
        }); 
    }

    if( bmpMapShapes ){
        bmpMapShapes.forEach( function( item ) {
            var shape_obj = null;
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
               
                //let  radius = Microsoft.Maps.SpatialMath.getDistanceTo(bmp_currentShape.metadata.center, e.location);
    
                //Calculate circle locations.
                Microsoft.Maps.loadModule( 'Microsoft.Maps.SpatialMath', function(){                  
                    var locs = Microsoft.Maps.SpatialMath.getRegularPolygon(location, init_radius, 100);
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

                infobox = new Microsoft.Maps.Infobox(bmp_bing_map.getCenter(), {
                    visible: false
                });
    
                infobox.setMap( bmp_bing_map );

                if(  show_infobox_hover ){
                    Microsoft.Maps.Events.addHandler(shape_obj, 'mouseover', pushpinClicked ); 
                //    Microsoft.Maps.Events.addHandler(bmp_new_pin, 'mouseout', bmpInfoboxHideMe ); 
                    Microsoft.Maps.Events.addHandler(shape_obj, 'mousedown', pushpinClicked ); 
                }else{
                    Microsoft.Maps.Events.addHandler(shape_obj, 'click', pushpinClicked);
                }

                bmp_bing_map.entities.push( shape_obj );
            }
        });
    }

    Microsoft.Maps.Events.addHandler( bmp_bing_map, 'viewchangeend', function(e){
        
        var zoomLevel = bmp_bing_map.getZoom();
        var map_lat   = bmp_bing_map.getCenter().latitude;
        var map_long  = bmp_bing_map.getCenter().longitude;
        document.getElementById('bmp_map_zoom').value           = zoomLevel;
        bmpDataMap[0].map_zoom = parseInt( zoomLevel );
        document.getElementById('display_zoom_val').textContent = zoomLevel;
        let map_lat_el = document.getElementById('bmp_map_start_lat');
        let map_long_el = document.getElementById('bmp_map_start_long');
        map_lat_el.value      = map_lat;
        map_long_el.value     = map_long;
       
        if( lLoaded ){         
            let event = new Event('change');
            map_lat_el.dispatchEvent( event );
            map_long_el.dispatchEvent( event );
        }

        bmpDataMap[0].map_start_lat     = map_lat;
        bmpDataMap[0].map_start_long    = map_long;       
        
    });
 
}


function bmp_encode_str( rawStr ){    
    rawStr =  rawStr.replace(/['|"|<|>|'\n]/g, function(i) {
        return '&#' + i.charCodeAt(0) + ';';
    }).replace(/\n/g, '<br/>'); 
    
    return rawStr;
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

function pushpinClicked(e) {
    //Make sure the infobox has metadata to display.
   
   infobox._customInfobox = false;
    if (e.target.metadata) {

        let location = e.location;
        let shape = e.target;
        if( shape instanceof Microsoft.Maps.Pushpin )
            location = shape.getLocation();

        //Set the infobox options with the metadata of the pushpin.
        infobox.changed.isPreviouslyInvoked = false;
        if( e.target.metadata.pintype === 'simple' ){ //simple
            
            if( ( e.target.metadata.title.length > 0 ) &&
                ( e.target.metadata.description.length > 0 ) ){                                   
                    infobox.setOptions({
                            customInfobox : false,
                            location: location,
                            title : e.target.metadata.title,
                            description: e.target.metadata.description,
                            visible: true,
                            htmlContent: false,
                            offset: {x:0, y:7, z:0}                          
                    });     
            }
        }else if( e.target.metadata.pintype === 'advanced' ){// advanced
            infobox.setOptions({
                customInfobox: true,
                location: location,
                htmlContent: '<div class="bmp_pin_info_wrapper" >' +   
                                '<div class="bmp_pin_info_container" >' +    
                                    '<div class="bmp_pin_info_header">   <div id="bmp_pin_info_close_img"> '+
                                        '<img onclick="bmpInfoboxHideMe(this);" src="'+ bmpIconsUrl +'bmp-infobox-close.svg" /> </div></div>' +
                                    
                                    '<div class="bmp_pin_info_body" style="width:' + e.target.metadata.width + 'px; height: '+ e.target.metadata.height+'px"  >' + e.target.metadata.html +  
                                '</div>' +                               
                                '</div>' +
                                '<div class="bmp_pin_info_down_arrow"></div>' +
                             '</div>',
                visible: true,
                offset: new Microsoft.Maps.Point( ( e.target.metadata.width / 2 ) * -1.027  , 7 )
            })
        }

    }
}

function bmpClearPinsFromMap(){
    if( typeof bmp_bing_map_pins.entities !== 'undefined' )
        bmp_bing_map_pins.entities.clear(); 
    var bmp_pin_info_container = document.getElementsByClassName('bmp_pin_info_wrapper'); 
    for( var i = 0; i < bmp_pin_info_container.length; i++ ){
        bmp_pin_info_container[i].style.display = 'none';
    }
}

function bmpInfoboxHideMe(el){
    infobox.setOptions({
        visible: false
    }); 
}
 

//map shapes 

function bmp_RunMapShapesPage(){
    bmp_makeBootstrapTableMapShapes();
    bmp_makeBootstrapTableAllShapes();    
}

function bmp_makeBootstrapTableMapShapes(){
    var bmp_table_map_added_shapes = bmpX('#tbl_map_added_shapes');
    var data = [];

    var lineimgsrc      = "<img src='" + bmpIconsUrl + 'shapes-line.png' + "' />";
    var circleimgsrc    = "<img src='" + bmpIconsUrl + 'shapes-circle.png' +"' />";
    var polimgsrc       = "<img src='" + bmpIconsUrl + 'shapes-polygon.png' + "' />";
    var deleterow       = "<img class='btn_remove_shape_from_map' src='" + bmpIconsUrl + 'delete-row.png' + "' />";

    $bmp_map_shapes.forEach( function ( item ){

        let shapeTypeImg = '';
        if( item.type  == 'line')
            shapeTypeImg = lineimgsrc
        else if( item.type == 'circle' )
            shapeTypeImg = circleimgsrc
        else
            shapeTypeImg = polimgsrc; 

        let obj = {
            id : item.id,
            action : deleterow,
            name   : item.name,
            type   : shapeTypeImg,
            infobox : item.infoType 
        };
        data.push( obj );
    });
    
    bmp_table_map_added_shapes.bootstrapTable('destroy').bootstrapTable({
        columns :[{
            title : 'id',
            field : 'id',
            visible : false,
        },{
            title : s_Action,
            field : 'action',
            visible : true,
            align : 'center',
        //    formatter : addrow,
            events : {
                'click .btn_remove_shape_from_map' : function(e, value, row ){
                    let map_id = bmpX('#bmp_page_map_id').val();
                    bmp_action_shape_to_map('remove_shape_from_map', row.id, map_id, row ) 
                }
            }
        },{
            title : s_Name,
            field : 'name',
            align : 'center',
            sortable : true
        },{
            title : s_Type,
            field : 'type',
            align : 'center',
            sortable : true
        },{
            title : s_Infobox,
            field : 'infobox',
            align : 'center',
            sortable : true     
        }
        ],
        data : data
    });

    if( ($bmp_map_shapes.length == 0) && ( $bmp_all_shapes.length > 0 ) ){
        let map_shape_text = '<h5 id="map_no_shapes_text"> This map has no Shapes assigned. Click on "All Shapes" action to add.</h5>';
        bmpX('#tbl_map_added_shapes tbody .no-records-found td').text('').append( map_shape_text );   
    }
}

function bmp_makeBootstrapTableAllShapes(){
    var bmp_table_map_all_shapes = bmpX('#tbl_map_all_shapes');
    var data = [];

    var lineimgsrc      = "<img src='" + bmpIconsUrl + 'shapes-line.png' + "' />";
    var circleimgsrc    = "<img src='" + bmpIconsUrl + 'shapes-circle.png' +"' />";
    var polimgsrc       = "<img src='" + bmpIconsUrl + 'shapes-polygon.png' + "' />";
    var addrow          = "<img class='btn_add_shape_to_map' src='" + bmpIconsUrl + 'add-row.png' + "' />";


    $bmp_all_shapes.forEach( function ( item ){

        let shapeTypeImg = '';
        if( item.type  == 'line')
            shapeTypeImg = lineimgsrc
        else if( item.type == 'circle' )
            shapeTypeImg = circleimgsrc
        else
            shapeTypeImg = polimgsrc; 

        let obj = {
            id : item.id,
            action : addrow,
            name   : item.name,
            type   : shapeTypeImg,
            infobox : item.infoType 
        };
        data.push( obj );
    });
    bmp_table_map_all_shapes.bootstrapTable('destroy').bootstrapTable({
        columns :[{
            title : 'id',
            field : 'id',
            visible : false,
        },{
            title : s_Action,
            field : 'action',
            visible : true,
            align : 'center',
            events : {
                'click .btn_add_shape_to_map' : function(e, value, row ){
                    let map_id = bmpX('#bmp_page_map_id').val();
                    bmp_action_shape_to_map('add_shape_to_map', row.id, map_id, row ); 
                }
            }
        },{
            title : s_Name,
            field : 'name',
            align : 'center',
            sortable : true
        },{
            title : s_Type,
            field : 'type',
            align : 'center',
            sortable : true
        },{
            title : s_Infobox,
            field : 'infobox',
            align : 'center',
            sortable : true     
        }
        ],
        data : data
    });

    if( ($bmp_all_shapes.length == 0) && ( $bmp_map_shapes.length == 0 ) ){        
        let shapes_href = bmpX('#menu_item_shapes a').attr('href');
        let goToShapes = '<h5> You have no Shapes created. Click <a href="'+ shapes_href+'">HERE</a> to create new Shapes!</h5>';
        bmpX('#tbl_map_all_shapes tbody .no-records-found td').text('').append( goToShapes );
    }
}

function bmp_action_shape_to_map( $action, $shape_id, $map_id, $row ){
    var bmp_obj = {};
    bmp_obj.action      = $action;
    bmp_obj.map_id      = $map_id;
    bmp_obj.shapeid     = $shape_id;
    bmp_obj.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();
 
    var data_ajax = {
        action  : 'bmp_shape_actions',
        type    : 'POST',
        data    :  bmp_obj,
        dataType : 'json',
        contentType : 'application/json'  
    };
  
    bmpX.ajax({
        type    : 'POST',
        url     : ajaxurl,
        data    : data_ajax,
        beforeSend : function(){
            bmpX('.loaderImg').show();
        },
        success : function( data ){

            if( data != 1 ){
                try {
                    let data_obj = JSON.parse( data );               
                    if( typeof data_obj == 'object' && 'error' in data_obj && data_obj['error'] ){
                        alert( data_obj['message'] );           
                        bmpX('.loaderImg').hide();
                        return;
                    }
                } catch (error) {
                    
                }
            }

            if( data == '1'){ //success

                if( $action == 'add_shape_to_map'){
                    bmpX('#tbl_map_all_shapes').bootstrapTable('removeByUniqueId', $row.id );  
                    bmp_updateShapesArrays( $action, $row.id );  
                    bmp_makeBootstrapTableMapShapes();
                }else{ //remove shape
                    bmpX('#tbl_map_added_shapes').bootstrapTable('removeByUniqueId', $row.id );  
                    bmp_updateShapesArrays( $action, $row.id );  
                    bmp_makeBootstrapTableAllShapes();
                }
            }else{
                console.error( 'Error occured');
            }

        }, 
        error : function( request, status, error ){
            bmpX('#ajaxError').show();  
            console.error( 'Request ' + request + ' - Status: ' + status + ' - Error: ' + error +' Action: ' + $data.action );   
            bmpX('.loaderImg').hide();  
        },
        complete : function( response){
            bmpX('.loaderImg').hide();  
        }
    });
}

function bmp_updateShapesArrays( $action, $id ){
    let indexObj = -1;

    if( $action == 'add_shape_to_map'){
        $bmp_all_shapes.forEach( function( item, index ){
            if(item.id == $id ){
                indexObj = index;
                return;
            }
        });
        if( indexObj != -1 ){
            let obj_shape = $bmp_all_shapes[ indexObj ];
            $bmp_map_shapes.unshift( obj_shape );
            $bmp_all_shapes.splice( indexObj, 1 );
        }
    }else{
        $bmp_map_shapes.forEach( function( item, index ){
            if( item.id == $id ){
                indexObj = index;
                return; 
            }            
        });

        if( indexObj != -1 ){
            let obj_shape = $bmp_map_shapes[ indexObj ];
            $bmp_all_shapes.unshift( obj_shape );
            $bmp_map_shapes.splice( indexObj, 1 );
        }
    }
}

function bmp_validLatAndLong( lat, long ){
    lat = parseInt( lat );
    long = parseInt( long );

    if( Number.isNaN( lat ) || Number.isNaN( long ) )
        return false;

    let result = (lat >= -90 ) && ( lat <= 90) && ( long >= -180 ) && (long <= 180 );

    return result;
}

function bmp_build_table_map_views(){
    if( typeof bmpMapViews === 'undefined')
        return;

    bmpX('#table_map_views').bootstrapTable( 'destroy').bootstrapTable({
        columns : [{
            title : s_ID,
            field : 'id',
            align : 'center'
        },{
            title : s_Shortcode,
            field : 'map_shortcode',
            align : 'center'    
        },{
            title : s_Name,
            field : 'shortcode',
            align : 'center'
        },{
            title : s_Latitude,
            field : 's_lat',
            align : 'center'
        },{
            title : s_Longitude,
            field : 's_long',
            align : 'center'
        },{
            title : s_Zoom,
            field : 's_zoom',
            align : 'center'
        },{
            title : s_Action,
            field : 'action',
            align : 'center',
            formatter : '<i class="fa fa-eye btn_view_view"></i> <span class="spacer"> </span> <i class="fa fa-trash btn_delete_shape"></i>',
            events : { 'click .btn_delete_shape' : function(e, value, row){
                let data = {};
                data.bmp_map_action = 'bmp_delete_map_view';
                data.id = row.id;
                data.map_id = bmpDataMap[0].id;  
                data.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();
                function confirm(){
                    sendBmpMapView( data, bmpX('.panel-heading'), bmp_build_table_map_views );
                };
                let options = {
                    message : '<b>Are you sure you want to delete this View?</b> <br /> ' +
                              '<i>If you have used this view in any posts or pages <br/>Make sure you remove it!</i><br/> <b> Default Map view will display instead </b>'
                };
                
                bmpX('#wpwrap').bmp_confirm( options, confirm );
                
            },
             'click .btn_view_view' : function( e, value, row ){
                let selItem = bmp_getMapView( row.id );
                if( selItem != null ){                    
                   
                    bmpX('#bmp_map_start_lat').val( selItem.s_lat );
                    bmpX('#bmp_map_start_long').val( selItem.s_long );                    
                   
                    bmpX('#display_zoom_val').text( selItem.s_zoom );
                    bmpDataMap[0].map_zoom = parseInt( selItem.s_zoom );

                    bmpX( window ).scrollTop(0);

                    setTimeout( function(){
                        let newLoc =  new Microsoft.Maps.Location(selItem.s_lat, selItem.s_long);
                        bmp_bing_map.setView({
                            center : newLoc,
                            zoom   : bmpDataMap[0].map_zoom
                        });
                    }, 600 );

                }
            }            
            }
        }
        ],
        data : bmpMapViews 
    });
}

function bmp_viewToShortcode( item, map_shortcode ){
    return '[bing-map-pro view="' + item.shortcode + '" viewid=' +item.id + ' id='+ item.map_id +']';
}

function bmp_removeMapView( id ){
    if( typeof bmpMapViews === 'undefined')
        return;

    for( let i = 0; i< bmpMapViews.length; i++ ){
        if( bmpMapViews[i].id == id ){
            bmpMapViews.splice( i, 1 );
            break;
        }
    }
}

function bmp_getMapView( id ){
    if( typeof bmpMapViews === 'undefined')
        return; 

    let item = null;
    for( let i = 0; i< bmpMapViews.length; i++ ){
        if( bmpMapViews[i].id == id ){
            item = bmpMapViews[i];
            break;
        }
    }
    return item;
    
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
