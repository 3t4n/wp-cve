jQuery( function($){

    var dmLicense = {

        request : function( action, args, callback ) {

            args = args !== '' && args instanceof jQuery ? window.ADS.serialize(args) : args;

            $.ajaxQueue( {
                url     : ajaxurl,
                data: { action: 'dm_action_license', dm_action: action, args: args },
                type    : 'POST',
                dataType: 'json',
                success : callback
            });
        },

        formRender : function ( response ) {

            var tmpl = $('#dm-license-form').html(),
                target = $('#license-form');
            if( response ) {

                if( response.hasOwnProperty( 'error' ) ) {
                    window.ADS.notify( response.error, 'danger' );
                } else {

                    if( response.hasOwnProperty( 'message' ) ) {
						setTimeout( location.reload(), 3000 );
                        window.ADS.notify( response.message, 'success' );
						
                    }

                    target.html( window.ADS.objTotmpl( tmpl, response ) );
                    setTimeout( window.ADS.switchery( target ), 300 );
					
                }
            }
        },

        form : function () {

            this.request( 'page_license', '', this.formRender );
        },

        handler : function() {

            var $this = this;

            $(document).on( 'click', '.js-activate', function () {

                window.ADS.btnLock( $(this) );

                $this.request( 'save_page_license', $('#license-form'), $this.formRender );
            } );

        },

        init: function () {

            this.handler();
            this.form();
        }
    };

    dmLicense.init();
} );