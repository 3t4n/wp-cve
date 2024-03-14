jQuery( function($){

    var dmPackage = {

        request : function( action, args, callback ) {

            args = args !== '' && args instanceof jQuery ? window.ADS.serialize(args) : args;

            $.ajaxQueue( {
                url     : ajaxurl,
                data: { action: 'dm_action_package', dm_action: action, args: args },
                type    : 'POST',
                dataType: 'json',
                success : callback
            });
        },

        formRender : function ( response ) {

            var tmpl = $('#dm-form').html(),
                target = $('#package-form');
            if( response ) {

                if( response.hasOwnProperty( 'error' ) ) {
                    window.ADS.notify( response.error, 'danger' );
                } else {

                    if( response.hasOwnProperty( 'message' ) ) {
                        window.ADS.notify( response.message, 'success' );
                    }

                    if( response.deposit ) {
                        $('#package-count').find('.count').text(response.deposit);
                    }

                    target.html( window.ADS.objTotmpl( tmpl, response ) );
                    setTimeout( window.ADS.switchery( target ), 300 );
                }
            }
        },

        formRenderCount : function ( response ) {

            var target = $('#package-count');

            if( response ) {

                if( response.hasOwnProperty( 'error' ) ) {
                    window.ADS.notify( response.error, 'danger' );
                } else {

                    if( response.hasOwnProperty( 'message' ) ) {
                        window.ADS.notify( response.message, 'success' );
                    }

                    target.find('.count').text(response.quantity);
                }
            }
        },

        form : function () {

            this.request( 'page_package', '', this.formRender );
        },
        packageCount : function () {

            this.request( 'page_package_count', '', this.formRenderCount );
        },

        handler : function() {

            var $this = this;

            $(document).on( 'click', '.js-activate-package', function () {

                window.ADS.btnLock( $(this) );

                $this.request( 'save_page_package', $('#package-form'), $this.formRender );
            } );

        },

        init: function () {

            this.handler();
            this.form();
            this.packageCount();
        }
    };

    dmPackage.init();
} );