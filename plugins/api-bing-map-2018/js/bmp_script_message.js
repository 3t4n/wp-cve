var bmpX = jQuery.noConflict();
bmpX(function( bmpX ){
    bmpX.fn.bmp_message = function( $message, $type, $opts ){
        var boldStyle = " ";
        var $options = { 
                    fade : true,
                    fadetime : 5000,
                    bold : true
        };
        var typesOpt = [ 'success', 'danger', 'warning', 'info' ];
        if( typeof $message === 'undefined' )
            return this;
        if( (typeof $opts === 'undefined' ) || ( typeof $opts !== 'object' ) ){
            $opts = {};
        }

        bmpX.extend( $options, $opts );
        
        if( ( typeof $type === 'undefined') || ( ! typesOpt.includes( $type ) ) )
            $type = 'success';        
        
        if( ( typeof $fade === 'undefined' ) || ( ! typeof $fade !== 'boolean') )
            var $fade = true;

        if( $options.bold )
            boldStyle = " font-weight: bold; ";

        if( bmpX('#info_message').length > 0 )
            bmpX('#info_message').remove();

        var $element = '<div id="info_message" style="z-index: 10001; ' + boldStyle +'  width: 60%; position: absolute; left: 20%; text-align: center;"'+
                            'class="alert alert-' + $type +' alert-dismissible " role="alert">'+
                                $message.toString()+
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> ' +
                            '<span aria-hidden="true">&times;</span> ' +
                            '</button> ' +
                            '</div>';
        this.prepend( $element );   

        if( $options.fade )
            bmpX( '#info_message' ).fadeOut( $options.fadetime , function(){ bmpX(this).remove();});     
            
        return this;        
    }

    bmpX.fn.bmp_confirm = function( $options, $callback, $param1, $param2 ){
        let $opts = {
            yes : 'Yes',
            no  : 'No',
            message: '',
            title : 'Confirmation',
            bold  : true
        };

        bmpX.extend( $opts, $options );

        $bmp_modal =   bmpX( ' <div class="modal fade bmp-custom-modal" style="z-index: 111111111111;" data-backdrop="static" id="bmp_custom_modal" role="dialog"> '+
                        ' <div class="modal-dialog"> '+                
                        ' <div style="margin-top: 50%;" class="modal-content"> '+
                        ' <div class="modal-headline"></div> '+
                        ' <div class="modal-header"> '+                            
                    //    ' <button type="button" class="close" data-dismiss="modal">&times;</button> '+
                        ' <h3 class="modal-title"> ' + $opts.title.toString()  + ' </h3> '+                        
                        ' </div> '+
                        ' <div class="modal-body">'+                                                 
                        ' <p style="font-size: 1.3em;">'  +  $opts.message.toString() + '</p>' +
                        '</div> '+

                        ' <div class="modal-footer"> '+
                    
                        ' <button type="button" class="button button-secondary" id="bmp_custom_modal_no" > '+  $opts.no + ' </button> '+
                        ' <button type="button" id="bmp_custom_modal_yes" class="button button-primary"> '+  $opts.yes + ' </button> '+
                        ' </div> '+
                        ' </div> '+
        
                        ' </div> '+
                        ' </div> ');

        if( bmpX('#bmp_custom_modal').length > 0 ){
            bmpX('#bmp_custom_modal').remove(); 
        }

        this.append( $bmp_modal );

        bmpX( '#bmp_custom_modal_yes').on('click', function(){
            bmpX('#bmp_custom_modal').modal('hide');
            if( ( typeof $callback !== 'undefined' ) && ( typeof $param1 !== 'undefined' ) && ( typeof $param2 !== 'undefined') )
                $callback( $param1, $param2 );
            else if( ( typeof $callback !== 'undefined' ) && ( typeof $param1 !== 'undefined' ) )
                $callback( $param1 );
            else if ( typeof $callback !== 'undefined')
                $callback();
        });
        
        bmpX('#bmp_custom_modal_no').on('click', function(){
            bmpX('#bmp_custom_modal').modal('hide');
        })

        bmpX('#bmp_custom_modal').modal({
            show: true,
            backdrop: 'static'
        });
     
    }
});