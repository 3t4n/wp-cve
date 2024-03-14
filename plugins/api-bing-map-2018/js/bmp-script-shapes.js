var bmpX = jQuery.noConflict();
var bmp_shapes_map = null;
var bmp_tools;
var bmp_drawingLayer;
var bmp_events = [];
var bmp_currentMode = '';

var bmp_newCircle = false;
var bmp_isEditCircle = false;
var isMouseDown = false;
//var bmp_shape_obj, bmp_shape_obj, bmp_shape_polygon;
var bmp_shape_obj = null;
var bmp_infobox;
var bmp_infobox_events = [];
var bmp_oData = [];

var bmp_shape = function( $type ) {
    this.type               = $type;
    this.name               = '';
    this.fillOpacity        = 0.4;
    this.infoType           = 'none';
    this.infoSimpleTitle    = '';
    this.infoSimpleDesc     = '';
    this.infoAdvanced       = '';
    this.shapeData          = [];
    this.action             = 'new';
    this.maplat             =  33.7243396617476;
    this.maplong            =  10.267542830964933;
    this.mapzoom            = 2;
    this.maptype            = 'r';
    this.fillColorHex       = '#fff000';
    this.radius             = 1;
    this.style              = {
                                color : 'red',
                                fillColor : 'rgba( 255, 255, 0, 0.4)',
                                strokeColor : '#000000',
                                strokeThickness : 1
                            };
    this.showSaved         = false;                           
}

var bmp_actions = function(){
    this.add    = false;
    this.save   = false;
    this.edit   = false;
    this.delete = false;
    this.pan    = false;
}

//bmp_lazy_map_load();
bmpX( function( bmpX ){

    // ---------------- polylines ======================
    var modal_opts = {
        show: true,
        backdrop : 'static'
    }
   
    bmpX('#shapes_menu_line').on('click', function(){  
        
        bmpX('.bmp-modal-shape-line').modal( modal_opts ); 
      

        if( (bmp_shape_obj == null) || (bmp_shape_obj.action == 'new') )        
            bmp_shape_obj = new bmp_shape('line');
                  
        bmp_load_map( 'bmp_shapes_map_line', 'polyline');
    
        Microsoft.Maps.Events.addHandler( bmp_shapes_map, 'rightclick', function(e){
            let disable_panning = bmpX('#map_lines_panning').prop('checked');
            bmpX('#map_lines_panning').prop('checked', ! disable_panning  ).change();
            
            bmp_shapes_map.setOptions({
                disablePanning: disable_panning 
            });
        
        });
        
        if( (typeof bmp_shape_obj.action !== 'undefined') &&  (bmp_shape_obj.action == 'edit') ){
            bmp_setup_edit_line();
        }

        bmp_shapes_map.setOptions({
            disablePanning: ! bmpX('#map_lines_panning').prop('checked')
        });
                    
        bmp_setup_modal_line();

        if( bmp_shape_obj.action == 'edit' ){
            setTimeout( function(){
                bmp_setup_edit_line();                
            }, 500 );  
        }       
    });

    function bmp_setup_edit_line(){
        if( typeof bmp_tools === 'undefined' ){
            setTimeout( function(){
                bmp_setup_edit_line();
            }, 500);
        }else{
            bmp_tools.create(Microsoft.Maps.DrawingTools.ShapeType.polyline, function (s) {            
                s.setOptions(bmp_shape_obj.style);   
                bmp_currentShape = s;                                
            }); 

            bmp_tools.finish( function( s ){
                let line_locations = bmp_get_line_locations();            
                s.setLocations( line_locations );    
                bmp_drawingLayer.add( s );  
                bmp_infobox_shape_event();     
            });

           bmpX('#showSavedPolylines').prop('checked', false ).trigger('change');
        }
    }


    function bmp_setup_modal_line(){
        bmpX('#bmp_line_name').val( bmp_shape_obj.name );
        bmpX('#bmp_line_color').val( bmp_shape_obj.style.strokeColor );
        bmpX('#bmp_line_thickness').val( parseFloat( bmp_shape_obj.style.strokeThickness ));
        bmpX('#radio_bmp_line_' + bmp_shape_obj.infoType ).prop('checked', true );
        bmpX('#showSavedPolylines').prop('checked', bmp_shape_obj.showSaved ).trigger('change');
        bmpX('#showSavedShapesLin').prop('checked', false ).trigger('change');
    }

    bmpX('.bmp-modal-shape-line #cancel, .bmp-modal-shape-circle #cancel, .bmp-modal-shape-polygon #cancel').on('click', function(){
        bmp_shape_obj = null;
    });



    bmpX('#map_lines_panning').on('change', function(){
        bmp_shapes_map.setOptions({
            disablePanning: ! bmpX( this ).prop('checked')
        }); 
    });

    bmpX('#showSavedPolylines').on('change', function(){
        if( bmpX( this ).prop('checked') ){
            if( ! bmpX( '#showSavedShapesLin' ).prop('checked') )
                bmp_loadSavedShapes( ['line'] );
        }else{
            bmp_shapes_map.entities.clear();
            if( bmpX( '#showSavedShapesLin' ).prop('checked') ){
                bmp_loadSavedShapes( ['polygon', 'circle', 'line'] );
            }
        }
    });

    bmpX('#showSavedCircles').on('change', function(){
        if( bmpX( this ).prop('checked') ){
            if( ! bmpX( '#showSavedShapesCir' ).prop('checked') )
              bmp_loadSavedShapes( ['circle'] );
        }else{
            bmp_shapes_map.entities.clear();
            if( bmpX( '#showSavedShapesCir' ).prop('checked') ){
                bmp_loadSavedShapes( ['polygon', 'circle', 'line'] );
            }
        }

    });

    bmpX('#showSavedPolygons').on('change', function(){
        if( bmpX( this ).prop('checked') ){
            if( ! bmpX('#showSavedShapesPol').prop('checked') )
                bmp_loadSavedShapes( ['polygon'] );
        }else{            
            bmp_shapes_map.entities.clear();
            if( bmpX('#showSavedShapesPol').prop('checked') )
              bmp_loadSavedShapes( ['polygon', 'circle', 'line'] );
        }
    });

    bmpX('#showSavedShapesPol').on('change', function(){
        if( bmpX( this ).prop('checked') ){
            bmp_loadSavedShapes( ['polygon', 'circle', 'line'] );
        }else{
            bmp_shapes_map.entities.clear();
            if(  bmpX('#showSavedPolygons').prop('checked') ){
                bmp_loadSavedShapes( ['polygon'] );
            }
        }
    });

    bmpX('#showSavedShapesLin').on('change', function(){
        if( bmpX( this ).prop('checked') ){
            bmp_loadSavedShapes( ['polygon', 'circle', 'line'] );
        }else{
            bmp_shapes_map.entities.clear();
            if(  bmpX('#showSavedPolylines').prop('checked') ){
                bmp_loadSavedShapes( ['line'] );
            }
        }  
    });

    bmpX('#showSavedShapesCir').on('change', function(){
        if( bmpX( this ).prop('checked') ){
            bmp_loadSavedShapes( ['polygon', 'circle', 'line'] );
        }else{
            bmp_shapes_map.entities.clear();
            if(  bmpX('#showSavedCircles').prop('checked') ){
                bmp_loadSavedShapes( ['line'] );
            }
        }  
    });



    bmpX('input:radio[name=radio_bmp_pin_use]').on('change', function(){
        bmp_shape_obj.infoType = bmpX( this ).attr('data-value');
        bmp_infobox_shape_event();
    });

    bmpX('#bmp_line_thickness').on('input', function(){
        let this_val = parseFloat( bmpX( this ).val() );
        let color_val = bmpX('#bmp_line_color').val();
        if( isNaN( this_val ))
            return;

        if( this_val < 1 ){
            this_val = 1;
            bmpX( this ).val( this_val );
        }

        bmpX('#slider_val').text( this_val );
        bmp_shape_obj.style.strokeThickness = this_val;
        bmp_shape_obj.style.strokeColor = color_val;
        bmp_shape_obj.style.color = color_val;
        bmp_shape_obj.style.fillColor = color_val;        

        if( typeof bmp_currentShape !== 'undefined' ){
            bmp_currentShape.setOptions( bmp_shape_obj.style );
            bmpX('.btn-edit-line').trigger('click');
            bmpX('.btn-save-line').trigger('click');
        }
        bmp_shape_obj.style.strokeThickness = this_val;
    });

    bmpX('#bmp_line_color').on('change', function(){
        var this_val =  bmpX(this).val();
        bmp_edit_color( this_val );  
        bmp_shape_obj.strokeColor = this_val;   
    });

    bmpX('#bmp_line_name').on('input', function(){
        bmp_shape_obj.name = bmpX( this ).val().trim();
    });

    bmpX('.bmp-modal-shape-line #save').on('click', function(){
        bmpX('.btn-save-line').trigger('click');
        bmp_shape_obj.shapeData = bmp_load_polyline();
        
        if( bmp_shape_obj.name == '' ){
            bmpX('.bmp-modal-shape-line .modal-header').bmp_message(s_bmp_required_name, 'danger');
            bmpX('#bmp_line_name').focus();
            return;
        }else if( bmp_shape_obj.shapeData.length == 0 ){
            bmpX('.bmp-modal-shape-line .modal-header').bmp_message( s_bmp_no_polyline, 'danger');
            return;
        }
       
        bmp_send_ajax( bmp_shape_obj.action , '.bmp-modal-shape-line', bmp_SetNullShapeObj );         

    }); 
    
    bmpX('.bmp-modal-shape-line #saveAndNew').on('click', function(){

        bmpX('.btn-save-line').trigger('click');
        bmp_shape_obj.shapeData = bmp_load_polyline();
        
        if( bmp_shape_obj.name == '' ){
            bmpX('.bmp-modal-shape-line .modal-header').bmp_message(s_bmp_required_name, 'danger');
            bmpX('#bmp_line_name').focus();
            return;
        }else if( bmp_shape_obj.shapeData.length == 0 ){
            bmpX('.bmp-modal-shape-line .modal-header').bmp_message( s_bmp_no_polyline, 'danger');
            return;
        }
        function bmp_callback(){
            bmp_shape_obj = new bmp_shape('line');
            bmp_setup_modal_line();
            DeletePolyline();           
        }
       
        bmp_send_ajax( bmp_shape_obj.action , '', bmp_callback );
   
    }); 

    bmpX('.btn_bmp_pin_info_simple').on('click', function(){
         
        if( bmp_drawingLayer.getPrimitives().length == 0 ){
            bmpX('.bmp-modal-shape-' + bmp_shape_obj.type + ' .modal-header').bmp_message( s_bmp_no_shape_drawn, 'danger');
            return;
        }

        bmpX('.bmp-modal-infobox-simple').modal({
            show : true,
            backdrop : 'static'
        });
        bmpX('#bmp_infobox_title').val( bmp_decode_str( bmp_shape_obj.infoSimpleTitle ) );
        let info_desc = bmp_decode_str( bmp_shape_obj.infoSimpleDesc );
        info_desc =  info_desc.replace( /<\/br>/g, '\n');
        bmpX('#bmp_infobox_description').val( info_desc );
        bmpX('#bmp_simple_shape_type').val( bmp_shape_obj.type );      
    });

    bmpX('.btn_bmp_pin_info_advanced').on('click', function(){
        if(  bmp_drawingLayer.getPrimitives().length == 0 ){
            bmpX('.bmp-modal-shape-' + bmp_shape_obj.type + ' .modal-header').bmp_message( s_bmp_no_shape_drawn, 'danger');
            return;
        }
    
        bmpX('#bmp_adv_shape_type').val( bmp_shape_obj.type );     
        
        try {
            if( typeof tinyMCE.editors.bmp_infobox_editor_wp !== 'undefined'){

                bmpX('.bmp-modal-infobox-advanced').modal({
                    show : true,
                    backdrop : 'static'
                });    
    
                if( bmp_shape_obj.infoAdvanced.trim().length !== 0 )    
                    tinyMCE.editors.bmp_infobox_editor_wp.setContent( bmp_decode_str( bmp_shape_obj.infoAdvanced ));// bmp_shape_obj.infoAdvanced.replace(/\\/g, '' )); 
                else
                    tinyMCE.editors.bmp_infobox_editor_wp.setContent( '' );   
            }else{
                bmpX('.bmp-modal-infobox-error').modal('show');
            } 
        } catch (error) {
            bmpX('.bmp-modal-infobox-error').modal('show');
        }

    });

    bmpX('#bmp_btn_infobox_simple_save').on('click', function(){
        bmp_shape_obj.infoSimpleTitle = bmp_encode_str( bmpX('#bmp_infobox_title').val() );
        bmp_shape_obj.infoSimpleDesc  = bmp_encode_str( bmpX('#bmp_infobox_description').val() );
        bmpX('.bmp-modal-infobox-simple').modal('hide');

        bmp_infobox_shape_event();
    });

    function bmp_infobox_shape_event( ){
        if( (typeof bmp_currentShape !== 'undefined' ) && bmp_drawingLayer.getPrimitives().length > 0 ){
            bmp_infobox_events.forEach( function(item){
                Microsoft.Maps.Events.removeHandler( item );
            });
        //    Microsoft.Maps.Events.removeHandler( )
            bmp_infobox_events.push( Microsoft.Maps.Events.addHandler( bmp_currentShape, 'click', bmp_shape_clicked ) );
        //    bmp_shapes_map.entities.push( bmp_currentShape );
        }
    }

    bmpX('#bmp_btn_infobox_advanced_update').on('click', function(){
        bmpX('#bmp_infobox_editor_wp-tmce').trigger('click');
        bmp_shape_obj.infoAdvanced = bmp_encode_str( tinyMCE.editors.bmp_infobox_editor_wp.getContent() );  //{ format : 'html' }
        
        bmpX('.bmp-modal-infobox-advanced').modal('hide');  

        bmp_infobox_shape_event();
    })

    function bmp_load_polyline(){
        var result = [];
        if( bmp_drawingLayer.getPrimitives().length >0 ){
            bmp_primitives = bmp_drawingLayer.getPrimitives(); //array
            //we are intersted only in the first one at index 0
            if( Array.isArray( bmp_primitives ) && typeof bmp_primitives[0] !== 'undefined' ){
                //get coordinates
                let bmp_primitive = bmp_primitives[0];
                for( var j = 0; j< bmp_primitive.geometry.x.length; j++ ){
                    let point = new Microsoft.Maps.Location( bmp_primitive.geometry.y[j], bmp_primitive.geometry.x[j] );
                    result.push( point );
                }
            }

        }
        return result;
    }

    function bmp_edit_color( this_val ){
        bmp_shape_obj.style.strokeColor = this_val;
        bmp_shape_obj.style.color = this_val;
        bmp_shape_obj.style.fillColor = this_val;
        bmp_shape_obj.style.strokeThickness = parseFloat( bmpX('#bmp_line_thickness').val() );
        
        bmpX('.btn-edit-line').trigger('click');
        bmpX('.btn-save-line').trigger('click');        
    }

    bmpX('.btn-new-line').on('click', function(){
    //    bmp_shape_obj.style.strokeColor = bmpX('#bmp_line_color').val() 
    //    bmp_shape_obj.style.strokeThickness = parseFloat( bmpX('#bmp_line_thickness').val() );
    
        bmp_tools.create(Microsoft.Maps.DrawingTools.ShapeType.polyline, function (s) {
            s.setOptions(bmp_shape_obj.style);   
            bmp_currentShape = s;          
        });           
        
    });
    bmpX('.btn-save-line').on('click', function(){
        bmp_tools.finish( function( s ){
            s.setOptions( bmp_shape_obj.style );
            bmp_drawingLayer.add( s );       
        });
        bmp_infobox_shape_event();
    });

    bmpX('.btn-edit-line').on('click', function(){ 
        
        if( bmp_drawingLayer._primitives.length > 0 ){
            bmp_currentMode = 'edit';
            bmp_tools.edit( bmp_drawingLayer._primitives[0] );
            bmp_drawingLayer.clear();
        }
    });

    bmpX('.btn-delete-line').on('click', function(){
        let opt={
            yes : s_bmp_yes,
            no : s_bmp_no,
            message: s_bmp_delete_polyline,
            title : s_bmp_confirm_title
        }

        if( ( bmp_tools._disposables.length > 0) || ( bmp_drawingLayer.getPrimitives().length > 0) )
        bmpX('.bmp-modal-shape-line').bmp_confirm( opt, DeletePolyline );
          
    });


    function DeletePolyline(){
        bmp_tools.finish();
        bmp_tools._disposables = [];
        bmp_drawingLayer.clear();
    }  

    //========= end of polylines ===========================
    //--------- start of cirles ---------------------------

    bmpX('#shapes_menu_circle').on('click', function(){
        bmpX('.bmp-modal-shape-circle').modal( modal_opts );  
        if( bmp_shape_obj === null ){
            bmp_shape_obj = new bmp_shape('circle');
        }
        
        
        bmp_load_map( 'bmp_shapes_map_circle', 'circle' );

        bmp_setup_modal_circle();
        
        if(  bmp_shape_obj !== null && bmp_shape_obj.action === 'edit' ){
            setTimeout( function(){
                bmp_setup_edit_circle();                
            }, 500 );
        }        

    });

    function bmp_setup_modal_circle(){
        if( bmp_shape_obj.action == 'edit' ){
            bmp_shape_obj.fillColorHex = bmp_rgbaToHex( bmp_shape_obj.style.fillColor );  
        }

        bmpX('#bmp_circle_name').val( bmp_shape_obj.name );
        bmpX('#bmp_circle_color').val( bmp_shape_obj.style.strokeColor );
        bmpX('#bmp_circle_thickness').val( parseFloat( bmp_shape_obj.style.strokeThickness ) );
        bmpX('#radio_bmp_circle_' + bmp_shape_obj.infoType ).prop('checked', true ); 

        bmpX('#bmp_circle_fill_color').val( bmp_shape_obj.fillColorHex ); //bmp_rgbaToHex( 
        bmpX('#bmp_circle_stroke_color').val( bmp_shape_obj.style.strokeColor );
        bmpX('#bmp_circle_fill_opacity').val( bmp_shape_obj.fillOpacity );   
        bmpX('#bmp_circle_fill_opacity_val').text( bmp_shape_obj.fillOpacity );
        bmpX('#bmp_circle_radius').val( bmp_shape_obj.radius );
        bmpX('#showSavedCircles').prop( 'checked', bmp_shape_obj.showSaved ).trigger('change');
        bmpX( '#showSavedShapesCir' ).prop('checked', false ).trigger( 'change'); 

        UpdateCircleStyle();
    }

    function bmp_setup_edit_circle(){
        if( typeof bmp_tools === 'undefined' ){
            setTimeout( function(){
                bmp_setup_edit_circle(); //wait for map to load
            }, 500);
        }else{
            
            let lat = bmp_shape_obj.shapeData[0].latitude;
            let long = bmp_shape_obj.shapeData[0].longitude;
            let init_radius = bmp_shape_obj.shapeData[0].radius;   
     
            let location = new Microsoft.Maps.Location( lat, long );
            
            bmp_currentShape = new Microsoft.Maps.Polygon([ location, location, location ], bmp_shape_obj.style );
           

            //let  radius = Microsoft.Maps.SpatialMath.getDistanceTo(bmp_currentShape.metadata.center, e.location);

            //Calculate circle locations.
            var locs = Microsoft.Maps.SpatialMath.getRegularPolygon(location, init_radius, 100);

            //Update the circles location.
            bmp_currentShape.setLocations(locs);

            bmp_currentShape.metadata={
                type: 'circle',
                center: location,
                radius : init_radius
            }

            bmp_drawingLayer.add( bmp_currentShape );
            
            bmp_infobox_shape_event();  
            bmpX('#bmp_circle_radius').val( bmp_currentShape.metadata.radius );
            bmpX('#showSavedCircles').prop('checked', false ).trigger('change');
        }

    }

    
    bmpX('#bmp_circle_name').on('input', function(){
        bmp_shape_obj.name = bmpX( this ).val().trim();
    });

    bmpX( "#ul_pins_assigned, #ul_all_maps" ).sortable({
        connectWith: ".connectedSortable"
    }).disableSelection();
    
    bmpX('#bmp_circle_fill_color').on('input', function(){
        bmp_shape_obj.style.fillColor = bmp_hex2rgb( bmpX( this ).val(), bmp_shape_obj.fillOpacity );            
        UpdateCircleStyle();
    });


    bmpX('#bmp_circle_stroke_color').on('input', function(){
        bmp_shape_obj.style.strokeColor = bmpX( this ).val();       
        UpdateCircleStyle();
    });

    bmpX('#bmp_circle_fill_opacity').on('input', function(){
        bmp_shape_obj.fillOpacity   = parseFloat( bmpX( this ).val() ); 
        bmpX('#bmp_circle_fill_opacity_val').text( bmp_shape_obj.fillOpacity.toString() );  
        bmp_shape_obj.style.fillColor = bmp_hex2rgb( bmpX( '#bmp_circle_fill_color' ).val(), bmp_shape_obj.fillOpacity ); 
        UpdateCircleStyle();
    });

    bmpX('#bmp_circle_thickness').on('input', function(){
        bmp_shape_obj.style.strokeThickness = parseFloat( bmpX( this ).val() );
        UpdateCircleStyle();
    });

    bmpX('#bmp_circle_radius').on('keyup', function(){
        let radius = parseFloat( bmpX(this).val() );
        if( isNaN( radius ))
            return;
      
        if( bmp_drawingLayer &&  bmp_drawingLayer.getPrimitives().length > 0 && bmp_currentShape  ){
            if( radius > 1 ){
                var locs = Microsoft.Maps.SpatialMath.getRegularPolygon(bmp_currentShape.metadata.center, radius, 100);

                bmp_currentShape.metadata.radius = radius;

                //Update the circles location.
                bmp_currentShape.setLocations(locs);
                bmp_infobox_shape_event();  
            }
        }  
    });

    bmpX( "#ul_pins_assigned" ).sortable({
        receive: function( event, ui ) {
           let map_id   = bmpX( ui.item[0] ).data('mapid');  
           let shape_id = bmpX('#bmp_assign_shape_id').val();
           let action   = 'add_shape_to_map';

           bmp_assign_shape_map( action, shape_id, map_id, '' )
        }
    });

    bmpX( "#ul_all_maps" ).sortable({
        receive: function( event, ui ) {
            let map_id      = bmpX( ui.item[0] ).data('mapid');  
            let shape_id    = bmpX('#bmp_assign_shape_id').val();
            let action      = 'remove_shape_from_map';

            bmp_assign_shape_map( action, shape_id, map_id, '' )
        }
      });

    bmpX('.btn-delete-circle').on('click', function(){
        DeleteCircle();
        bmp_newCircle = false;
        bmpX('#bmp_circle_radius').val( 1 );
    });


    bmpX('.btn-edit-circle').on('click', function(){
        let $options = { 
            fade : true,
            fadetime : 10000,
            bold : true
        };
        if( bmp_drawingLayer &&  bmp_drawingLayer.getPrimitives().length > 0 && bmp_currentShape  ){
            bmpX('.bmp-modal-shape-circle .modal-header').bmp_message(s_edit_cirle_drag_drop, 'info', $options);
            EditCircleDrawing();
            bmp_newCircle = false;
        }
    });

    bmpX('.btn-new-circle').on('click', function(){  
        let $options = { 
            fade : true,
            fadetime : 10000,
            bold : true
        };
        bmpX('.bmp-modal-shape-circle .modal-header').bmp_message( s_bmp_new_circle_info , 'info', $options );
        bmp_new_circle_click();
    });

    bmpX('#bmp_modal_assign_shape').on('hidden.bs.modal', function(){
        bmp_makeBootstrapTable();
    });

    bmpX('#bmp_close_assign_shape').on('click', function(){
        bmp_makeBootstrapTable();
    });


    function bmp_new_circle_click(){
        bmp_newCircle = true; 

        Microsoft.Maps.Events.addHandler( bmp_shapes_map, 'mousedown', function(e){
            //lock map when dragging
            if( bmp_drawingLayer.getPrimitives().length === 0  && bmp_newCircle ){ 
                bmp_newCircle = true;
                bmp_shapes_map.setOptions({disablePanning: true });    
                     
                bmp_currentShape = new Microsoft.Maps.Polygon([e.location, e.location, e.location], bmp_shape_obj.style );

                bmp_currentShape.metadata={
                    type: 'circle',
                    center: e.location
                }                
                
                bmp_drawingLayer.add( bmp_currentShape );
             
                isMouseDown = true;
            }
        });  
        
        Microsoft.Maps.Events.addHandler( bmp_shapes_map, 'mousemove', function( e ){
            if( isMouseDown && ( bmp_isEditCircle || bmp_newCircle ) )
                scaleCircle( e );
        });

        Microsoft.Maps.Events.addHandler( bmp_shapes_map, 'mouseup', function( e ){
            if( bmp_isEditCircle && bmp_drawingLayer.getPrimitives().length > 0){
                scaleCircle( e ); 
            }

            bmp_shapes_map.setOptions({
                disablePanning : false
            });
            isMouseDown = false;

            bmpX('#bmp_circle_radius').val( bmp_currentShape.metadata.radius );
        });
    }

    function scaleCircle(e) {
        if (bmp_currentShape && bmp_currentShape.metadata && bmp_currentShape.metadata.type === 'circle') {
            //Calculate distance from circle center to mouse.
            var radius = Microsoft.Maps.SpatialMath.getDistanceTo(bmp_currentShape.metadata.center, e.location);

            //Calculate circle locations.
            var locs = Microsoft.Maps.SpatialMath.getRegularPolygon(bmp_currentShape.metadata.center, radius, 100);

            bmp_currentShape.metadata.radius = radius;

            //Update the circles location.
            bmp_currentShape.setLocations(locs);
            bmp_infobox_shape_event();
        }
    }

    function DeleteCircle(){
        let opt={
            yes : s_bmp_yes,
            no : s_bmp_no,
            message: s_bmp_delete_circle,
            title : s_bmp_confirm_title
        }
      
        if(  bmp_drawingLayer.getPrimitives().length > 0  )
            bmpX('.bmp-modal-shape-circle').bmp_confirm( opt, DeleteCircleFromMap );
    }
    function DeleteCircleFromMap(){
        bmp_drawingLayer.clear();
    }

    function UpdateCircleStyle(){
        if( bmp_drawingLayer &&  bmp_drawingLayer.getPrimitives().length > 0 && bmp_currentShape ){  //there is circle on the map
            bmp_currentShape.setOptions( bmp_shape_obj.style );           
        }
    }

    function EditCircleDrawing( e ){
        bmp_shapes_map.setOptions({
            disablePanning : true
        });
        if( bmp_drawingLayer &&  bmp_drawingLayer.getPrimitives().length > 0 && bmp_currentShape ){  //there is circle on the map
            var events = [];
            events.push( Microsoft.Maps.Events.addHandler( bmp_shapes_map, 'mousedown', function( e ){
                var circle = bmp_drawingLayer.getPrimitives()[0] || null;
              
                if( circle !== null ){
                    var distanceCenter = Microsoft.Maps.SpatialMath.getDistanceTo(e.location, circle.metadata.center);
                    var radius = Microsoft.Maps.SpatialMath.getDistanceTo(circle.metadata.center, circle.getLocations()[0]);  
                    //If the initial location is closer to the center of the circle, move it, otherwise scale it.
                    if (distanceCenter < (radius - distanceCenter)) {
                        events.push(Microsoft.Maps.Events.addHandler(bmp_shapes_map, 'mousemove', function (e) {
                            bmp_currentShape.metadata.center = e.location;

                            var locs = Microsoft.Maps.SpatialMath.getRegularPolygon( bmp_currentShape.metadata.center, radius, 100);
                            bmp_currentShape.setLocations(locs);
                        }));

                        events.push(Microsoft.Maps.Events.addHandler(bmp_shapes_map, 'mouseup', function (e) {
                           
                            bmp_currentShape.metadata.center = e.location;

                            var locs = Microsoft.Maps.SpatialMath.getRegularPolygon(bmp_currentShape.metadata.center, radius, 100);

                            bmp_currentShape.setLocations(locs);

                            //Unlock map panning.
                            bmp_shapes_map.setOptions({ disablePanning: false });

                            //Remove all events except the first one.        
                            for (var i = 0; i < events.length; i++) {
                                Microsoft.Maps.Events.removeHandler(events[i]);
                            }
                            
                            
                        }));

                    } else {
                        /*
                        events.push(Microsoft.Maps.Events.addHandler(bmp_shapes_map, 'mousemove', function (e) {
                            scaleCircle(e);
                        }));

                        events.push(Microsoft.Maps.Events.addHandler(bmp_shapes_map, 'mouseup', function (e) {
                           scaleCircle(e);

                            //Unlock map panning.
                            bmp_shapes_map.setOptions({ disablePanning: false });

                            //Remove all events except the first one.
                            
                            for (var i = 1; i < events.length; i++) {
                                Microsoft.Maps.Events.removeHandler(events[i]);
                            }
                            
                        }));
                        */
                    }
                }
            }));
        

        }
    }

    bmpX('.bmp-modal-shape-circle #save').on( 'click', function(){
        bmp_shape_obj.shapeData = [];
        if(  ( typeof bmp_currentShape !== 'undefined' ) && bmp_drawingLayer.getPrimitives().length > 0 ){
            let circle = bmp_drawingLayer.getPrimitives()[0];            
            bmp_shape_obj.shapeData.push( {
                latitude : circle.metadata.center.latitude,
                longitude : circle.metadata.center.longitude,
                radius : circle.metadata.radius,
                altitude : circle.metadata.center.altitude,
                altitudeReference : circle.metadata.center.altitudeReference
            });            
        }

        if( bmp_shape_obj.name == '' ){
            bmpX('.bmp-modal-shape-circle .modal-header').bmp_message( s_bmp_required_name , 'danger');
            bmpX('#bmp_circle_name').focus();
            return;
        }else if( bmp_shape_obj.shapeData.length == 0 ){
            bmpX('.bmp-modal-shape-circle .modal-header').bmp_message( s_bmp_no_circle, 'danger');
            return;
        }

        bmp_send_ajax( bmp_shape_obj.action, '.bmp-modal-shape-circle', bmp_SetNullShapeObj );
    });

    bmpX('.bmp-modal-shape-circle #saveAndNew').on( 'click', function(){
        bmp_shape_obj.shapeData = [];
        if(  ( typeof bmp_currentShape !== 'undefined' ) && bmp_drawingLayer.getPrimitives().length > 0 ){
            let circle = bmp_drawingLayer.getPrimitives()[0];            
            bmp_shape_obj.shapeData.push( {
                latitude : circle.metadata.center.latitude,
                longitude : circle.metadata.center.longitude,
                radius : circle.metadata.radius,
                altitude : circle.metadata.center.altitude,
                altitudeReference : circle.metadata.center.altitudeReference
            });            
        }

        if( bmp_shape_obj.name == '' ){
            bmpX('.bmp-modal-shape-circle .modal-header').bmp_message( s_bmp_required_name , 'danger');
            bmpX('#bmp_circle_name').focus();
            return;
        }else if( bmp_shape_obj.shapeData.length == 0 ){
            bmpX('.bmp-modal-shape-circle .modal-header').bmp_message( s_bmp_no_circle, 'danger');
            return;
        }

        function bmp_callback(){
            bmp_shape_obj = new bmp_shape('circle');
            bmp_setup_modal_circle();
            DeleteCircleFromMap();
        }

        bmp_send_ajax( bmp_shape_obj.action, '', bmp_callback );
    });




    //========== end of circle ======================
    //---------- start of polygon ------------------

    bmpX('#shapes_menu_polygon').on('click', function(){  

        if( (bmp_shape_obj == null) || (bmp_shape_obj.action == 'new') )        
            bmp_shape_obj = new bmp_shape('polygon');

        bmpX('.bmp-modal-shape-polygon').modal( modal_opts );  
        setTimeout( function(){
            bmp_load_map( 'bmp_shapes_map_polygon', 'polygon' );
        
            Microsoft.Maps.Events.addHandler( bmp_shapes_map, 'rightclick', function(e){
                let disable_panning = bmpX('#map_polygon_panning').prop('checked');
                bmpX('#map_polygon_panning').prop('checked', ! disable_panning  ).change();
                
                bmp_shapes_map.setOptions({
                    disablePanning: disable_panning 
                });
            
            });

            bmp_shapes_map.setOptions({
                disablePanning: ! bmpX('#map_polygon_panning').prop('checked')
            });

        }, 500 );
               
        bmp_setup_modal_polygon();


        if(  bmp_shape_obj !== null && bmp_shape_obj.action === 'edit' ){
            setTimeout( function(){
                bmp_setup_edit_polygon();                
            }, 500 );
        }     
   
    });

    function bmp_setup_modal_polygon(){
        if( bmp_shape_obj.action == 'edit' ){
            bmp_shape_obj.fillColorHex = bmp_rgbaToHex( bmp_shape_obj.style.fillColor );  
        }

        bmpX('#bmp_polygon_name').val( bmp_shape_obj.name );
        bmpX('#bmp_polygon_color').val( bmp_shape_obj.style.strokeColor );
        bmpX('#bmp_polygon_thickness').val( parseFloat( bmp_shape_obj.style.strokeThickness ) );
        bmpX('#radio_bmp_polygon_' + bmp_shape_obj.infoType ).prop('checked', true ); 

        bmpX('#bmp_polygon_fill_color').val( bmp_shape_obj.fillColorHex ); //bmp_rgbaToHex( 
        bmpX('#bmp_polygon_stroke_color').val( bmp_shape_obj.style.strokeColor );
        bmpX('#bmp_polygon_fill_opacity').val( bmp_shape_obj.fillOpacity );   
        bmpX('#bmp_polygon_fill_opacity_val').text( bmp_shape_obj.fillOpacity );
        bmpX('#showSavedPolygons').prop('checked', bmp_shape_obj.showSaved ).trigger('change');
        bmpX('#showSavedShapesPol').prop('checked', false ).trigger('change');        
    }

    bmpX('#map_polygon_panning').on('change', function(){
        let this_val = bmpX( this ).prop('checked');
        bmp_shapes_map.setOptions({
            disablePanning: !this_val
        });
    });

    bmpX('#bmp_polygon_name').on('input', function(){
        bmp_shape_obj.name = bmpX( this ).val().trim();
    });

    bmpX('#bmp_polygon_fill_color').on('input', function(){
        bmp_shape_obj.style.fillColor = bmp_hex2rgb( bmpX( this ).val(), bmp_shape_obj.fillOpacity );        
        UpdatePolygonStyle();
    });


    bmpX('#bmp_polygon_stroke_color').on('input', function(){
        bmp_shape_obj.style.strokeColor = bmpX( this ).val();    
        UpdatePolygonStyle();
    });

    bmpX('#bmp_polygon_fill_opacity').on('input', function(){
        bmp_shape_obj.fillOpacity   = parseFloat( bmpX( this ).val() ); 
        bmpX('#bmp_polygon_fill_opacity_val').text( bmp_shape_obj.fillOpacity.toString() );  
        bmp_shape_obj.style.fillColor = bmp_hex2rgb( bmpX( '#bmp_polygon_fill_color' ).val(), bmp_shape_obj.fillOpacity ); 
        UpdatePolygonStyle();
    });

    bmpX('#bmp_polygon_thickness').on('input', function(){
        bmp_shape_obj.style.strokeThickness = parseFloat( bmpX( this ).val() );
        UpdatePolygonStyle();
    });

    function UpdatePolygonStyle(){
        if( bmp_drawingLayer &&  bmp_drawingLayer.getPrimitives().length > 0 && bmp_currentShape ){  //there is polygon on the map
            bmp_currentShape.setOptions( bmp_shape_obj.style );           
        }
    }

    bmpX('.btn-new-polygon').on('click', function(){
        //    bmp_shape_obj.style.strokeColor = bmpX('#bmp_line_color').val() 
        //    bmp_shape_obj.style.strokeThickness = parseFloat( bmpX('#bmp_line_thickness').val() );
            bmp_tools.create(Microsoft.Maps.DrawingTools.ShapeType.polygon, function (s) {
                s.setOptions(bmp_shape_obj.style);   
                bmp_currentShape = s;          
            });           
    });

    bmpX('.btn-save-polygon').on('click', function(){
        bmp_tools.finish( function( s ){
            bmp_drawingLayer.add( s );  
            bmp_infobox_shape_event();     
        });
        
    });

    bmpX('.btn-edit-polygon').on('click', function(){ 
        
        if( bmp_drawingLayer.getPrimitives().length > 0 ){
            bmp_currentMode = 'edit';
            bmp_tools.edit( bmp_drawingLayer.getPrimitives()[0] );
            bmp_drawingLayer.clear();
        }
    });

    bmpX('.btn-delete-polygon').on('click', function(){
        let opt={
            yes : s_bmp_yes,
            no : s_bmp_no,
            message: s_bmp_delete_polygon,
            title : s_bmp_confirm_title
        }

        if( ( bmp_tools._disposables.length > 0) || ( bmp_drawingLayer.getPrimitives().length > 0) )
            bmpX('.bmp-modal-shape-polygon').bmp_confirm( opt, DeletePolygon );
          
    });


    function DeletePolygon(){
        bmp_tools.finish();
        bmp_tools._disposables = [];
        bmp_drawingLayer.clear();
    } 


    bmpX('.bmp-modal-shape-polygon #save').on('click', function(){
        bmp_shape_obj.shapeData = bmp_load_polygon();

        if( bmp_shape_obj.name == '' ){
            bmpX('.bmp-modal-shape-polygon .modal-header').bmp_message( s_bmp_required_name , 'danger');
            bmpX('#bmp_polygon_name').focus();
            return;
        }else if( bmp_shape_obj.shapeData.length == 0 ){
            bmpX('.bmp-modal-shape-polygon .modal-header').bmp_message( s_bmp_no_polygon, 'danger');
            return;
        }
        bmp_send_ajax( bmp_shape_obj.action, '.bmp-modal-shape-polygon', bmp_SetNullShapeObj );
    });

    bmpX('.bmp-modal-shape-polygon #saveAndNew').on('click', function(){
        bmp_shape_obj.shapeData = bmp_load_polygon();

        if( bmp_shape_obj.name == '' ){
            bmpX('.bmp-modal-shape-polygon .modal-header').bmp_message( s_bmp_required_name , 'danger');
            bmpX('#bmp_polygon_name').focus();
            return;
        }else if( bmp_shape_obj.shapeData.length == 0 ){
            bmpX('.bmp-modal-shape-polygon .modal-header').bmp_message( s_bmp_no_polygon, 'danger');
            return;
        }
        
        function $callback(){
            DeletePolygon();
            bmp_shape_obj = new bmp_shape('polygon');
            bmp_setup_modal_polygon();
        }

        bmp_send_ajax( bmp_shape_obj.action, '',  $callback );
    });

    function bmp_load_polygon(){
        var result = [];
        if( bmp_drawingLayer.getPrimitives().length >0 ){
            bmp_primitives = bmp_drawingLayer.getPrimitives(); //array
            //we are intersted only in the first one at index 0
            if( Array.isArray( bmp_primitives ) && typeof bmp_primitives[0] !== 'undefined' ){
                let i = 0;
                //get coordinates
                let bmp_primitive = bmp_primitives[0];
                while( i < bmp_primitive.geometry.rings[0].x.length ){
                    let point = {
                        long : bmp_primitive.geometry.rings[0].x[i],
                        lat : bmp_primitive.geometry.rings[0].y[i]
                    }
                    let loc = new Microsoft.Maps.Location( point.lat, point.long );
                    result.push( loc );
                    i++;
                }
            }

        }
        return result;
    }

    function bmp_setup_edit_polygon(){
        if( typeof bmp_tools === 'undefined' ){
            setTimeout( function(){
                bmp_setup_edit_polygon(); //wait for map to load
            }, 500);
        }else{
            
            var mapCenter = new Microsoft.Maps.Location( bmp_shape_obj.maplat, bmp_shape_obj.maplong );
            var points = [];
            if( bmp_shape_obj.shapeData.length > 0 ){
                if( typeof bmp_shape_obj.shapeData[0].lat !== 'undefined' ){ //old way, slower
                    for( var i = 0; i < bmp_shape_obj.shapeData.length; i++ ){
                        let newPoint = new Microsoft.Maps.Location( bmp_shape_obj.shapeData[i].lat, bmp_shape_obj.shapeData[i].long );                        
                        points.push( newPoint );
                    }
                }else{
                    points = bmp_shape_obj.shapeData; //new way, load polygon positions
                }                          
            }

            bmp_currentShape = new Microsoft.Maps.Polygon( points, bmp_shape_obj.style );

            bmp_currentShape.metadata={
                type: 'polygon',
                center: mapCenter
            }

            bmp_drawingLayer.add( bmp_currentShape );
            
            bmp_infobox_shape_event();  

            bmpX('#showSavedPolygons').prop('checked', false ).trigger('change');
        }

    }

    //================================================

    /* load table */
    bmp_shape_obj = new bmp_shape('');
    bmp_send_ajax( 'table', '', bmp_SetNullShapeObj );
    /*  ====== */

});

function bmp_lazy_map_load(){
    var script = document.createElement('script');
    script.setAttribute('type', 'text/javascript');
    script.setAttribute('src', "https://www.bing.com/api/maps/mapcontrol?key="+ bmp_api_key );
    document.getElementsByTagName('head')[0].append( script );
}

function bmp_load_map( $bmp_map, $bmp_type ){
    bmp_shapes_map = new Microsoft.Maps.Map(document.getElementById( $bmp_map ), {
        /* No need to set credentials if already passed in URL */
        center: new Microsoft.Maps.Location( parseFloat(bmp_shape_obj.maplat), parseFloat( bmp_shape_obj.maplong ) ),
        zoom: parseInt( bmp_shape_obj.mapzoom ),
        mapTypeId : bmp_shape_obj.maptype
    });
    Microsoft.Maps.loadModule(['Microsoft.Maps.DrawingTools', 'Microsoft.Maps.SpatialMath'], function () {
        //Create a layer for the drawn shapes.
        bmp_drawingLayer = new Microsoft.Maps.Layer();
        bmp_shapes_map.layers.insert(bmp_drawingLayer);

        //Create an instance of the DrawingTools class and bind it to the map.
        bmp_tools = new Microsoft.Maps.DrawingTools(bmp_shapes_map);
        
    });
   

    bmp_infobox = new Microsoft.Maps.Infobox(bmp_shapes_map.getCenter(), {
        visible: false
    });
    bmp_infobox.setMap( bmp_shapes_map );

    Microsoft.Maps.Events.addHandler( bmp_shapes_map, 'viewchangeend', function(){
        bmp_getMapInfo();
    });
    
}

function bmp_get_line_locations($obj ){
    var location = [];   
    if( typeof $obj === 'undefined' )
        $obj = bmp_shape_obj.shapeData; 

    if( $obj.length > 0){
        if( typeof $obj[0].lat !== 'undefined'){ //old way
            for( var i = 0; i < $obj.length;  i++ ){
                let item = $obj[i];
                let loc = new Microsoft.Maps.Location( item.lat, item.long );
                location.push( loc );
            }
        }else{
            //already stored as locations, faster loading
            location = $obj;
        }
    }
    return location;
}

function bmp_loadSavedShapes($arr_shapes){
    bmp_shapes_map.entities.clear();
    let do_not_show_edited_obj = '';

    if( (typeof bmp_shape_obj.action !== 'undefined') && ( bmp_shape_obj.action == 'edit' ) )
        do_not_show_edited_obj = bmp_shape_obj.id;

    for( let k = 0; k< bmp_oData.length; k++  ){
        let item = bmp_oData[k];

        if( (do_not_show_edited_obj !== '') && ( do_not_show_edited_obj == item.id ) )
            continue;

        if( (item.type == 'line') && ($arr_shapes.indexOf( 'line' ) > -1) ){
            let positions = bmp_get_line_locations( item.shapeData );            
            let polyline = new Microsoft.Maps.Polyline( positions, item.style );
            bmp_shapes_map.entities.push( polyline );

        }else if( (item.type == 'circle') && ($arr_shapes.indexOf( 'circle' ) > -1 ) ){
            let lat = item.shapeData[0].latitude;
            let long = item.shapeData[0].longitude;
            let init_radius = item.shapeData[0].radius;   
     
            let location = new Microsoft.Maps.Location( lat, long );
            
            let circle = new Microsoft.Maps.Polygon([ location, location, location ], item.style );
           

            //let  radius = Microsoft.Maps.SpatialMath.getDistanceTo(bmp_currentShape.metadata.center, e.location);

            //Calculate circle locations.
            var locs = Microsoft.Maps.SpatialMath.getRegularPolygon(location, init_radius, 100);

            //Update the circles location.
            circle.setLocations(locs);

            bmp_shapes_map.entities.push( circle );

        }else if( (item.type == 'polygon') && ( $arr_shapes.indexOf( 'polygon') > -1 ) ){
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

            let polygon = new Microsoft.Maps.Polygon( points, item.style );
            bmp_shapes_map.entities.push( polygon );
        }
    };
}



function bmp_send_ajax( $action, $modal, $callback ){
    bmp_shape_obj.action = $action;
    bmp_shape_obj.shapeData = JSON.stringify( bmp_shape_obj.shapeData );
    bmp_shape_obj.style =  JSON.stringify( bmp_shape_obj.style );
    bmp_shape_obj.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();
 
    var data_ajax = {
        action  : 'bmp_shape_actions',
        type    : 'POST',
        data    :  bmp_shape_obj,
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
            let data_obj = JSON.parse( data );
            if( typeof data_obj == 'object' && 
                'error' in data_obj && data_obj['error'] ){
                alert( data_obj['message']);
                bmpX('.loaderImg').hide();
                return;
            }  

            if( bmp_shape_obj.action === 'table' ){               
                data = JSON.parse( data  );  
             
                data.forEach( function( item ){
                    try{
                    item.shapeData = item.shapeData.replace(/\\/g, '');
                    item.shapeData = JSON.parse( item.shapeData );
                    item.style = item.style.replace(/\\/g, '' );
                    item.style = JSON.parse( item.style );
                    }catch( e ){
                        console.error( 'Error parsing ' + e.message );
                        console.error( 'Error on ' + item.name );
                    }
                });
                
                bmp_oData = data;               
                bmp_makeBootstrapTable();

            }else if( (bmp_shape_obj.action === 'new') || ( bmp_shape_obj.action == 'saveandnew') ){

                bmp_ProcessReturnObj( data , 'new');
          
            }else if( bmp_shape_obj.action === 'delete'){                
                bmpX('#bmp_table_shapes').bootstrapTable('removeByUniqueId', bmp_shape_obj.id );
                bmp_deleteDataObj( bmp_shape_obj.id );

            }else if( bmp_shape_obj.action === 'edit'){
                bmp_ProcessReturnObj( data , 'edit');
            }
        }, 
        error : function( request, status, error ){
            bmpX('#ajaxError').show();  
            console.error( 'Request ' + request + ' - Status: ' + status + ' - Error: ' + error +' Action: ' + $data.action );   
        },
        complete : function( response){
            bmpX('.loaderImg').hide();  
            if( $modal !== '' )
                bmpX( $modal ).modal('hide');

            if( typeof $callback !== 'undefined')
                $callback();
        }
    });

}

function bmp_ProcessReturnObj( $bmp_obj, $action ){
    $bmp_obj = JSON.parse( $bmp_obj );
    var lineimgsrc      = "<img src='" + bmpIconsUrl + 'shapes-line.png' + "' />";
    var circleimgsrc    = "<img src='" + bmpIconsUrl + 'shapes-circle.png' +"' />";
    var polimgsrc       = "<img src='" + bmpIconsUrl + 'shapes-polygon.png' + "' />";
  
    if( Array.isArray( $bmp_obj ) )
        $bmp_obj = $bmp_obj[0];
 
    try{
        $bmp_obj.shapeData =$bmp_obj.shapeData.replace(/\\/g, '' );
        $bmp_obj.shapeData = JSON.parse(  $bmp_obj.shapeData  ) ;
    
        $bmp_obj.style = $bmp_obj.style.replace(/\\/g, '');
        $bmp_obj.style = JSON.parse( $bmp_obj.style );  
    }catch(e){
        console.error( e.message );
    }    
    
    var shapeTypeImg = ''; 
    if( $bmp_obj.type  == 'line')
        shapeTypeImg= lineimgsrc
    else if( $bmp_obj.type == 'circle' )
        shapeTypeImg = circleimgsrc
    else
        shapeTypeImg= polimgsrc; 

    let obj = {
        'id' : $bmp_obj.id,
        'name' : $bmp_obj.name,
        'shapetype' : $bmp_obj.type,
        'infobox' : $bmp_obj.infoType,
        'shapetypeimg' : shapeTypeImg 
    }
  
    
    if( $action == 'new' ){
        bmp_oData.unshift( $bmp_obj );
        bmpX('#bmp_table_shapes').bootstrapTable('insertRow', {index : 0, row : obj});
    }else if( $action == 'edit'){
        bmp_updateDataObj( $bmp_obj );
        bmpX('#bmp_table_shapes').bootstrapTable('updateByUniqueId', {id : $bmp_obj.id, row : obj});
       
    }    

}

function bmp_updateDataObj( $bmp_obj ){
    for( var i = 0; i < bmp_oData.length; i++ ){
        let item = bmp_oData[i];
        if( item.id == $bmp_obj.id )
            bmp_oData[i] = $bmp_obj;
    }
}

function bmp_deleteDataObj( $id ){
    for( var i = 0; i < bmp_oData.length; i++ ){
        let item = bmp_oData[i];
        if( item.id == $id ){
            bmp_oData.splice( i, 1 );
            i = bmp_oData.length;
        }
    }
}

function bmp_makeBootstrapTable(){
    var bmp_table = bmpX('#bmp_table_shapes');
    var data = [];

    var lineimgsrc      = "<img data-toggle='tooltip' data-placement='right' title='Line Shape' src='" + bmpIconsUrl + 'shapes-line.png' + "' />";
    var circleimgsrc    = "<img data-toggle='tooltip' data-placement='right' title='Circle Shape' src='" + bmpIconsUrl + 'shapes-circle.png' +"' />";
    var polimgsrc       = "<img data-toggle='tooltip' data-placement='right' title='Polygone Shape' src='" + bmpIconsUrl + 'shapes-polygon.png' + "' />";   

    bmp_oData.forEach( function( item ){
        let shapeTypeImg = '';
        let infotype = s_none;
        if( item.type  == 'line')
            shapeTypeImg = lineimgsrc
        else if( item.type == 'circle' )
            shapeTypeImg = circleimgsrc
        else
            shapeTypeImg = polimgsrc; 

        if( item.infoType == 'simple')
            infotype = s_simple;
        else if( item.infoType == 'advanced')
            infotype = s_advanced;

        let obj = {
            'id' : item.id,
            'name' : item.name,
            'shapetype' : item.type,
            'infobox' : infotype,
            'shapetypeimg' : shapeTypeImg
        }
        if( typeof bmpDataShapeMaps[ item.id ] !== 'undefined' )
            obj.assigned = bmpDataShapeMaps[ item.id ].length;
        else
            obj.assigned = '0';
        
        data.push( obj );
    });
 
    bmp_table.bootstrapTable('destroy').bootstrapTable({
        columns :[{
            title : 'id',
            field : 'id',
            visible : false,
           
        },{
            title : s_Name,
            field : 'name',
            sortable : true,
            uniqueid : true
        },{
            title : s_Shape_Type,
            field : 'shapetype',
            sortable : true,
            align : 'center',
            visible : false,
        },{
            title : s_Shape,
            field : 'shapetypeimg',
            sortable : true,
            align : 'center'
        },{
            title : s_Infobox_Type,
            field : 'infobox',
            sortable : true,
            align : 'center'
        },{
            title : s_bmp_used_on_map,
            field : 'assigned',
            sortable : false,
            align : 'center'
        },{
            title : s_Action,
            field : 'action',
            align : 'center',
            formatter :             
                        '<button data-toggle="tooltip" data-placement="bottom" title="'+s_bmp_assign_map+'" class="button btn_assign_shape"><i class="fa fa-map-marked"></i> <span class="spacer"> </span></button> ' +
                        '<button data-toggle="tooltip" data-placement="bottom" title="'+s_bmp_edit+'" class="button btn_edit_shape"><i class="fa fa-edit"></i> <span class="spacer"> </span></button> ' +
                        '<button data-toggle="tooltip" data-placement="bottom" title="'+s_bmp_delete+'" class="button btn_delete_shape"><i class="fa fa-trash"></i> </button>',
            events : {
                'click .btn_edit_shape' : function(e, value, row){

                    bmp_shape_obj = bmp_get_shape_obj( row.id );
                                        
                    bmp_shape_obj.action = 'edit';
                    if( row.shapetype == 'line' ){
                        bmpX( '#shapes_menu_line').trigger('click');
                    }else if( row.shapetype == 'circle'){
                        bmpX( '#shapes_menu_circle').trigger('click');
                    }else if( row.shapetype == 'polygon'){
                        bmpX( '#shapes_menu_polygon').trigger('click');
                    }
                                       
                },
                'click .btn_delete_shape' : function( e, value, row ){
                    let opt = {
                        message : '<strong> <i class="fa fa-trash"></i>' +  s_bmp_delete_shape + '<br/> <i class="fa fa-trash"></i>' + s_bmp_delete_ref_mess + '</strong>'
                    }
                    
                    bmpX('.bmp-shapes-main-panel').bmp_confirm( opt, bmp_delete_shape, row.id );                
                },
                'click .btn_assign_shape' : function( e, value, row ){
                   
                    let row_obj_maps = bmpDataShapeMaps[ row.id ] || [];
                    bmpX('#assign_shape_name').text( row.name );
               
                    let assigned = [];
                    let all_maps = [];
                 
                    for( let index = 0; index < bmpDataMaps.length; index++ ){
                        let map = bmpDataMaps[ index ];
                        let el_ul = bmpX('<li></li>').addClass('ui-state-default').text( map.map_title ).attr('data-mapid', map.id );
                        if( row_obj_maps.indexOf( map.id )  > -1 )                         
                           assigned.push( el_ul );  
                        else                          
                           all_maps.push( el_ul );                        
                    };

                    bmpX('#ul_pins_assigned').empty().append( assigned );
                    bmpX('#ul_all_maps').empty().append( all_maps );  


                    bmpX('#bmp_modal_assign_shape').modal({
                        show : true,
                        backdrop : 'static'
                    });

                    bmpX('#bmp_assign_shape_id').val( row.id );
                }
            }
        }

        ], 
        data : data,
        search : true,
        onPostBody : function(){
            bmpX('[data-toggle="tooltip"]').tooltip({
                container : 'body'
            });           
        }
    });
}

function bmp_assign_shape_map( $action, $shape_id, $map_id, $row ){
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
      
            if( data == '1'){ //success
               
                if( $action == 'add_shape_to_map'){
                    if( typeof bmpDataShapeMaps[ $shape_id ] !== 'undefined' )
                        bmpDataShapeMaps[ $shape_id ].push( $map_id.toString() );
                    else{
                        bmpDataShapeMaps[ $shape_id ] = [ $map_id.toString() ];
                    }

                }else{ //remove shape
                 
                    let index_map = bmpDataShapeMaps[ $shape_id ].indexOf( $map_id.toString() );
                    if( index_map > - 1){
                        bmpDataShapeMaps[ $shape_id ].splice( index_map, 1 );
                    }
                }
            }else{
                console.error( 'Error occured');
                alert( 'Could not perform the action');
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

function bmp_delete_shape( $id ){
    bmp_shape_obj = bmp_get_shape_obj( $id );
    if( bmp_get_shape_obj !== null )
        bmp_send_ajax( 'delete', '', bmp_SetNullShapeObj );
}

function bmp_get_shape_obj( $id ){
    var obj_sh = null;

    bmp_oData.forEach( function( item ){
        if( item.id == $id ){
            obj_sh = item;
            return;
        }
    });

    return obj_sh;
}

function bmp_hex2rgb(hex, opacity) {
    var h=hex.replace('#', '');
    h =  h.match(new RegExp('(.{'+h.length/3+'})', 'g'));

    for(var i=0; i<h.length; i++)
        h[i] = parseInt(h[i].length==1? h[i]+h[i]:h[i], 16);

    if (typeof opacity != 'undefined')  h.push(opacity);

    return 'rgba('+h.join(',')+')';
}

function bmp_trim (str) {
    return str.replace(/^\s+|\s+$/gm,'');
  }
  
function bmp_rgbaToHex (rgba) {
    var parts = rgba.substring(rgba.indexOf("(")).split(","),
        r = parseInt(bmp_trim(parts[0].substring(1)), 10),
        g = parseInt(bmp_trim(parts[1]), 10),
        b = parseInt(bmp_trim(parts[2]), 10);
        r = r.toString( 16 );
        g = g.toString( 16 );
        b = b.toString( 16 );
        if( r == '0')
        r += '0';
        if( g == '0')
        g += '0';
        if( b == '0')
        b += '0';

    var result = '#' + r + g + b;
    
return result;
}

function bmp_shape_clicked(e) {
    //Make sure the infobox has metadata to display.
   bmp_infobox._customInfobox = false;
    if (bmp_shape_obj ) {
        //Set the infobox options with the metadata of the pushpin.
        bmp_infobox.changed.isPreviouslyInvoked = false;
        if( bmp_shape_obj.infoType == 'simple' ){ //simple
            
            if( ( bmp_shape_obj.infoSimpleTitle.length > 0 ) &&
                ( bmp_shape_obj.infoSimpleDesc.length > 0 ) ){                                   
                    bmp_infobox.setOptions({
                            customInfobox : false,
                            location: e.location,
                            title : bmp_decode_str( bmp_shape_obj.infoSimpleTitle ),
                            description: bmp_decode_str( bmp_shape_obj.infoSimpleDesc ),
                            visible: true,
                            htmlContent: false,
                            offset: {x:0, y:7, z:0}                          
                    });  
            }
        }else if( bmp_shape_obj.infoType == 'advanced' ){// advanced
            let width = getBmpInfoboxWidth();
            let height = getBmpInfoboxHeight();
            bmp_infobox.setOptions({
                customInfobox: true,
                location: e.location,
                htmlContent: '<div class="bmp_pin_info_wrapper" >' +   
                                '<div class="bmp_pin_info_container" >' +    
                                    '<div class="bmp_pin_info_header">   <div id="bmp_pin_info_close_img"> '+
                                        '<img onclick="bmpHideShapeInfobox(this);" src="'+ bmpIconsUrl +'bmp-infobox-close.svg" /> </div></div>' +
                                    
                                    '<div class="bmp_pin_info_body" style="width:' + width+ 'px; height: '+ height+'px"  >' + bmp_decode_str( bmp_shape_obj.infoAdvanced ) +
                                '</div>' +                               
                                '</div>' +
                                '<div class="bmp_pin_info_down_arrow"></div>' +
                             '</div>',
                visible: true,
                offset: new Microsoft.Maps.Point( ( width / 2 ) * -1.027  , 7 )
            })
        }

    }
}

function bmpHideShapeInfobox(el){
    bmp_infobox.setOptions({
        visible: false
    }); 
}

function bmp_getMapInfo(){
    if( bmp_shapes_map ){       
        bmp_shape_obj.maplat = bmp_shapes_map.getCenter().latitude;
        bmp_shape_obj.maplong = bmp_shapes_map.getCenter().longitude;
        bmp_shape_obj.mapzoom = bmp_shapes_map.getZoom();
        bmp_shape_obj.maptype = bmp_shapes_map.getMapTypeId();        
    }
}

function bmp_setupmaps(){
    bmp_shapes_map = new Microsoft.Maps.Map(document.getElementById( 'bmp_shapes_map_line' ), {
        /* No need to set credentials if already passed in URL */
        center: new Microsoft.Maps.Location(  33,44, -33,33  ),
        zoom: 5,
        mapTypeId : 'r'
    });
    Microsoft.Maps.loadModule(['Microsoft.Maps.DrawingTools', 'Microsoft.Maps.SpatialMath'], function () {
        //Create a layer for the drawn shapes.
        bmp_drawingLayer = new Microsoft.Maps.Layer();
        bmp_shapes_map.layers.insert(bmp_drawingLayer);

        //Create an instance of the DrawingTools class and bind it to the map.
        bmp_tools = new Microsoft.Maps.DrawingTools(bmp_shapes_map);
        
    });

}

function bmp_SetNullShapeObj(){
    bmp_shape_obj = null;
}

