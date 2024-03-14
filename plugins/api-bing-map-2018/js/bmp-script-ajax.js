var bmpX = jQuery.noConflict();
bmpX( function( bmpX ){

    bmpX('#btn_delete_map').on( 'click', function(){
        var $action = bmpX('#bmp_map_action').val();
        var $map_id = bmpX('#bmp_map_id').val();
        sendBmpMapResponse( $map_id, $action, '' );
    });

    bmpX('.bmp-map-save-map').on( 'click', function(e){        
        e.preventDefault();
        bmpDataMap[0].action_type = 'save_map';
        bmp_save_map_ajax(bmpDataMap[0] );
        lChanged = false;
    })



    bmpX('#ajaxError .close').click( function ( e ){
        e.stopPropagation();
        bmpX(this).parent().hide();
    });

    //----------- add new map -----------------
    bmpX('#bmp_add_new_map').click( function(){
        var $map_title = bmpX('#new_map_input').val();

        if( $map_title.trim() !== '' ){
            if( $map_title.length > 20 )
                alert('Map name should be less than 20 characters');
            else{
                $map_title =  $map_title;
                sendBmpMapResponse( -1, 'new', $map_title );
            }
        }else{
           //empty map title
        }

    });    

    //========================================

    //------- add new pin -----------------------------
    bmpX('#bmp_save_new_pin, #bmp_save_and_new_pin').on('click', function(){

        var bmp_pin_obj =  bmp_get_pin_data();
       
        let allChecked = true;
        let pin_action = bmpX('#pin_action').val();
        let pin_id     = bmpX('#pin_action_id').val();
        let pinName    = bmpX('#bmp_new_pin_name').val();
        let save_and_new = ( this.id === 'bmp_save_and_new_pin' );
     
        if( bmp_pin_obj.icon_link !== '' ){ //check if the icon is from custom icons
            if( bmp_pin_obj.icon_link.indexOf('custom-icons') > -1 ){
                let tmp = bmp_pin_obj.icon_link.split('/');
                bmp_pin_obj.icon_link = tmp[ tmp.length - 1 ];              
            }
        }         

        if( bmp_pin_obj.pin_name.length === 0 ){
            bmpX('#bmp_new_pin_name').addClass('required_input');   
            allChecked = false;
        }
        if( bmp_pin_obj.pin_lat.length === 0){
            bmpX('#bmp_new_pin_lat').addClass('required_input'); 
            allChecked = false;
        }
        if( bmp_pin_obj.pin_long.length === 0 ){
            bmpX('#bmp_new_pin_long').addClass('required_input');     
            allChecked = false;
        }

        if( ! allChecked )
            return;

        if(  ( bmp_is_pin_duplicated( bmp_pin_obj.pin_name) && ( pin_action === 'new-pin' ) ) 
                || ( ( bmp_is_pin_edited_duplicated( pin_id, pinName ) ) && ( pin_action == 'edit-pin') )  ){   
            bmpX( window ).scrollTop(0);
            bmpX('#bmp_alert_pin_exists').show().addClass('shake_div');
            setTimeout( function(){
                bmpX('#bmp_alert_pin_exists').fadeOut().removeClass('shake_div');  
            }, 3000 );
            allChecked = false;
        }

        if(  allChecked ){         
 
           let data = {};    
           data = {                
                name            : bmp_pin_obj.pin_name,
                address         : bmp_pin_obj.pin_address,
                lat             : bmp_pin_obj.pin_lat,
                long            : bmp_pin_obj.pin_long,
                icon            : bmp_pin_obj.icon,
                pin_url         : bmp_pin_obj.icon_link,
                info_type       : bmp_pin_obj.pin_info.type,
                info_selected   : bmp_pin_obj.pin_info.radio,
                info_title      : bmp_pin_obj.pin_info.title,
                info_desc       : bmp_pin_obj.pin_info.desc,
                info_html       : bmp_pin_obj.pin_info.html,
                save_and_new    : save_and_new
            }   
                                        
            if( pin_action === 'new-pin'){
                data.action = 'new-pin';
                data = JSON.parse( JSON.stringify( data ) );                                           
                sendBmpNewPin( data );               

            }else if( pin_action === 'edit-pin'){
             
                let pin_id = bmpX('#pin_action_id').val();
                data.id = pin_id;
                data.action = 'edit-pin';
                data.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();
                let data_edited = {
                    pin : data,
                    action : 'edit-pin'
                }
                data_edited = JSON.parse( JSON.stringify( data_edited ) );                         
               
                bmp_pin_action( data_edited );                                            
            }
           
        }
        
    });   
    //========== end of new pin ======================
    
});

// ---------- Handle Active/Deactive maps ------
function actionBmpMap( el , action ){
    var $map_id = bmpX( el ).attr('data-id');
    bmpX( '#bmp_map_action').val( action );
    bmpX( '#bmp_map_id').val( $map_id );
}

function bmp_is_pin_edited_duplicated( $id, $name ){
    var $i = 0;
    var $found = false;
    $name =  $name.toLowerCase().trim();
    while( $i < bmpAllPins.length && ! $found ){
        var objName = bmpAllPins[ $i ].pin_name.toLowerCase().trim();
        var objId = bmpAllPins[ $i ].id;
        if( ( parseInt(objId) !== parseInt( $id ) ) &&  ( $name == objName ) ){
            $found = true;
        }
        $i++;
    }    

    return $found;
}

function bmp_update_edited_pin( $pin_obj ){
    var $i = 0;
    var $found = false;
    while( $i < bmpAllPins.length && ! $found ){
        if( bmpAllPins[$i].id === $pin_obj.id ){
            bmpAllPins[$i] = $pin_obj;
            $found = true;
        }
        $i++;
    }
}

function bmp_is_pin_duplicated( $pin_name ){
    if( bmpAllPins === null)
        return false;

    $pin_name = $pin_name.toLowerCase().trim();
    var $pin_exists = false;
    var i = 0;
    while( i < bmpAllPins.length && ! $pin_exists ){
        if( bmpAllPins[i].pin_name.toLowerCase().trim() === $pin_name ){
            $pin_exists = true;
        }
        i++;
    }
    return $pin_exists;
}

function sendBmpMapView( $data, $modal, $callback ){
       
    var data_ajax = {
        action: 'bmp_map_actions',
        type: 'POST',
        data : $data,
        dataType: 'json',
        contentType: 'application/json'
    };

    bmpX.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data_ajax,
        beforeSend: function(){
            bmpX('.loaderImg').show();
        }, success: function( data ){
            if( data == 0 ){
                $modal.find('.modal-header').bmp_message( s_Error, 'danger');
            }else{
                if( $data.bmp_map_action == 'bmp_delete_map_view'){
                    bmp_removeMapView( $data.id );
                }else{
                    data = JSON.parse( data ); 

                    if( typeof data == 'object' && 'error' in data && data['error'] ){
                        alert( data['message']);
                        bmpX('.loaderImg').hide();
                        return;
                    }  
                    
                    let obj_view = data[0];                 
                    $modal.modal('hide');    
                    obj_view.map_shortcode = bmp_viewToShortcode( obj_view, bmpMapShortCode);                  
                    bmpMapViews.unshift( obj_view );
                    let options = {
                        fade : false
                    }
                    bmpX('#bmp_map_page_nav').bmp_message('<b>' + s_SavedScrollDownToShortcode +'<br/>'+ s_PasteItInYourPage +'</b>', 'success', options);
                }
                $callback();
            }
          
                        
        }, error: function( request, status, error ){
            bmpX('#ajaxError').show();  
            console.error( 'Request ' + request + ' - Status: ' + status + ' - Error: ' + error);               
        }, complete: function( response ){
            bmpX('.loaderImg').hide();
        }

    });
}

function sendBmpNewPin( $data, $action ){
    $data.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();

    var data_ajax = {
        action  :   'bmp_new_pin',
        type    :   'POST',
        data    :   $data,
        dataType:   'json',
        contentType : 'application/json'
    };


    
    bmpX.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data_ajax,
        beforeSend: function(){
            bmpX('.loaderImg').show();
        },
        success: function( data ){           
            data = JSON.parse( data );  

            try {
                let data_obj = data;           
                if( typeof data_obj == 'object' && 'error' in data_obj && data_obj['error'] ){
                    alert( data_obj['message'] );           
                    bmpX('.loaderImg').hide();
                    return;
                }
            } catch (error) {
                
            }
            

            if( (typeof data !== 'undefined') && data != '0' ){
                var elToPrepend = bmpX('#bmp_tbody_pins');  
                data.data_json = data_ajax.data.info_html;                                     
                BmpCreatePinRow( data, elToPrepend, 'prepend'  );
                bmpAllPins.unshift( data );   
                           
            }

            if( $data.action == 'new_pin' ){
                bmpX('#bmp_alert_pin_saved').show().delay(3000).fadeOut();                              
            }

            bmp_clear_pin_inputs('new');
            bmp_bing_map_pins.entities.clear();

            if( ! $data.save_and_new ){
                bmpX('#bmp_modal_new_edit_pin').modal( 'hide');
            }
            
        },
        error : function( request, status, error ){
            bmpX('#ajaxError').show();  
            console.error( 'Request ' + request + ' - Status: ' + status + ' - Error: ' + error +' Action: ' + $data.action );         
        },
        complete: function( response ){
            bmpX('.loaderImg').hide();   
        }
    })
}

function bmp_pin_action( $data ){
    $data.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();
    var data_ajax = {
        action  : 'bmp_pin_actions',
        type    : 'POST',
        data    :  $data,
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
           
            if( $data.action === 'disable-pin' && data == 1){
                bmp_update_pin_status( $data.pin_id, $data.status ? '1' : '0' );
            }else if( $data.action === 'delete-pin' && data == 1 ){  
                bmpX('#bmp_tbody_pins #pin_' + $data.pin_id ).remove();                
                bmp_delete_pin_obj( $data.pin_id );
            }else if( $data.action === 'edit-pin' ){
                if( ( typeof data === 'undefined') || ( data == '0') ){
                    bmpX('#bmp_cancel_edit_pin').trigger('click');  
                    return; 
                }                  

                data = JSON.parse(  data );            
                data.data_json = JSON.parse( JSON.stringify( $data.pin.info_html )); 

               if( data != false ){              
                    bmp_update_edited_pin( data );
                    bmp_update_row_pin( data ); 

               }
               bmpX('#bmp_cancel_edit_pin').trigger('click');     
            }             

        }, 
        error : function( request, status, error ){
            bmpX('#ajaxError').show();  
            console.error( 'Request ' + request + ' - Status: ' + status + ' - Error: ' + error +' Action: ' + $data.action );   
        },
        complete : function( response){
            bmpX('.loaderImg').hide();  
        }
    });
}

function bmp_update_pin_status( $pin_id, $new_status ){
    bmpX.each( bmpAllPins, function( i, item ){
        if( item.id == $pin_id ){
            item.active = $new_status;
        }
    });    
}
function bmp_delete_pin_obj ( $pin_id ){
    var foundId = '';
    bmpX.each( bmpAllPins, function( i, item ){
        if( item.id == $pin_id ){
            foundId = i;
        }
    });
    if( foundId !== '' )
        bmpAllPins.splice( parseInt(foundId) , 1 );  

}


function sendBmpMapResponse( $bmp_map_id, $bmp_map_action, $bmp_map_name ){

    var data = {
        bmp_map_action : $bmp_map_action,
        map_id         : $bmp_map_id,
        bmp_map_title  : $bmp_map_name,
        nonce_bing_map_pro : bmpX('#nonce_bing_map_pro').val()       
    }

    var data_ajax = {
        action: 'bmp_map_actions',
        type: 'POST',
        data : data,
        dataType: 'json',
        contentType: 'application/json'
    };

    bmpX.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data_ajax,
        beforeSend: function(){
            bmpX('.loaderImg').show();
        }, success: function( data ){

            data = JSON.parse( data );  

            if( typeof data == 'object' && 'error' in data && data['error'] ){
                alert( data['message']);
                bmpX('.loaderImg').hide();
                return;
            }    
                        
            if( $bmp_map_action == 'new'  && data ){
                bmp_append_new_map( data );                       
            }
            if( $bmp_map_action == 'delete' ){
                bmpX('#mapsTable #map_' + data ).remove();                 
            }

            if( $bmp_map_action == 'active'){
                
            }
                        
        }, error: function( request, status, error ){
            bmpX('#ajaxError').show();  
            console.error( 'Request ' + request + ' - Status: ' + status + ' - Error: ' + error);               
        }, complete: function( response ){
            bmpX('.loaderImg').hide();
        }

    });
}

function bmp_save_map_ajax( $map_obj ){
    $map_obj.map_title = $map_obj.map_title.replace(/\\/g, '' );
    $map_obj.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();
    var data_ajax = {
        action: 'bmp_save_map',
        type: 'POST',
        data: $map_obj,
        dataType: 'json', 
        contentType: 'application/json'
    }

    bmpX.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data_ajax,
        beforeSend: function(){
            bmpX('.loaderImg').show();
        }, 
        success: function(data){
            //should display a success/error message
            
            if( data != 1 ){
                try {
                    let data_obj = JSON.parse( data );               
                    if( typeof data_obj == 'object' && 'error' in data_obj && data_obj['error'] ){
                        bmpX('.bmp-edit-map-block .row:first').bmp_message( data_obj['message'], 'danger' );              
                        bmpX('.loaderImg').hide();
                        return;
                    }
                } catch (error) {
                    
                }
            }
            
            if( data == 1 )
                bmpX('.bmp-edit-map-block .row:first').bmp_message( s_MapSaved, 'success' );              
        },
        error: function( request, status, error ){
            bmpX('#ajaxError').show();
            console.error( 'Request ' + request + ' - Status: ' + status + ' - Error: ' + error);    
        },
        complete: function(){
            bmpX('.loaderImg').hide();
        }
    })
}

function bmp_update_row_pin( $pin_obj ){    
    bmpX('#pin_' + $pin_obj.id ).remove();
    $pin_row = bmpX('#bmp_tbody_pins' );
    BmpCreatePinRow( $pin_obj,  $pin_row );   
}

function bmp_append_new_map( $map ){
    var elements = [];
    var map_title = $map.title.replace(/\\/g, '' );
    var $row = bmpX("<tr id='map_" + $map.id+ "'></tr>");
    var $ckbActive = bmpX("<td> " +
                            "<div class='checkox'> " +
                                "<div class='toggle button button-primary  btn-xs' id='test' data-toggle='toggle' style='width: 49px; height: 22px;' >" +
                                    "<input type='checkbox' onchange='sendBmpMapResponse(" + $map.id +", \"active\", \"\" );' " +
                                        " checked id='new_map_status' data-size='mini' data-toggle='toggle'  />" +
                                    "<div class='toggle-group'>" +
                                        "<label class='button button-primary btn-xs toggle-on'> " + s_Yes +"</label>" +
                                        "<label class='button button-secondary btn-xs active toggle-off'> " + s_No +"</label>" +
                                        "<span class='toggle-handle button button-secondary btn-xs'></span>" +
                                    "</div>" +
                                "</div>" +        
                            "</div>" +
                        "</td>");
    var $title = bmpX("<td>" + map_title +"</td>");
    var $shortcode = bmpX("<td> <input type='text' readonly value='[bing-map-pro id=" + $map.id + "]' /> </td>");
    let $active_pins = bmpX("<td>0</td>");
    let $active_shapes = bmpX("<td>0</td>");
    var $actions  = bmpX("<td> " +
                        "<button type='button' data-id='" + $map['id'] + "' id='edit_bmp_map' onclick='BmpEditMap(this);' class='button btn-success edit-bmp-map' > <i class='fa fa-edit'> </i> </button> " +
                        "<button type='button' data-id='" + $map['id'] + "' id='delete_bmp_map' onclick='BmpDeleteMap(this)' class='button btn-danger delete-bmp-map' > <i class='fa fa-trash'> </i> </button>" +
                    "</td>");

    elements.push( $ckbActive, $title, $shortcode, $active_pins, $active_shapes, $actions );   
                
    bmpX( $row ).append( elements );
    bmpX( '#mapsTable tbody').prepend( $row );

   bmpX('#new_map_status').on( 'change', function(){
       if(! bmpX(this).prop('checked') ){
           bmpX( this ).parent().parent().removeClass('btn-primary').addClass('btn-default off');
       }else{
            bmpX( this ).parent().parent().removeClass('btn-default off').addClass('btn-primary');
       }       
   });
    

}



//=========== End of Active / Deactive maps =========