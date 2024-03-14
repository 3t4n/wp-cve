jQuery( function( $ ) {

    /**
     * AJAX Request Queue
     *
     * - add()
     * - remove()
     * - run()
     * - stop()
     *
     * @since 1.0.0
     */
    var XPROajaxQueue = (function() {

        var requests = [];

        return {

            /**
             * Add AJAX request
             *
             * @since 1.0.0
             */
            add:  function(opt) {
                requests.push(opt);
            },

            /**
             * Remove AJAX request
             *
             * @since 1.0.0
             */
            remove:  function(opt) {
                if( jQuery.inArray(opt, requests) > -1 )
                    requests.splice($.inArray(opt, requests), 1);
            },

            /**
             * Run / Process AJAX request
             *
             * @since 1.0.0
             */
            run: function() {
                var self = this,
                    oriSuc;

                if( requests.length ) {
                    oriSuc = requests[0].complete;

                    requests[0].complete = function() {
                        if( typeof(oriSuc) === 'function' ) oriSuc();
                        requests.shift();
                        self.run.apply(self, []);
                    };

                    jQuery.ajax(requests[0]);

                } else {

                    self.tid = setTimeout(function() {
                        self.run.apply(self, []);
                    }, 1000);
                }
            },

            /**
             * Stop AJAX request
             *
             * @since 1.0.0
             */
            stop:  function() {

                requests = [];
                clearTimeout(this.tid);
            }
        };

    }());

    /**
     *	Lazy Load
     */
    jQuery(".xpro-template-screenshot img").lazyload({
        effect : "fadeIn",
        event : "sporty"
    });

    jQuery(window).bind("load", function() {
        var timeout = setTimeout(function() {
            jQuery(".xpro-template-screenshot img").trigger("sporty")
        }, 1000);
    });

    /**
     * Process of cloud templates - (download, remove & fetch)
     */
    XPROajaxQueue.run();

    jQuery('body').on('click', '.xpro-cloud-process', function (event) {
        event.preventDefault();

        var btn             	= jQuery(this),
            meta_id             = btn.find('.template-dat-meta-id').val() || '',
            meta_type           = btn.find('.template-dat-meta-type').val() || '',
            btn_template        = btn.parents('.xpro-template-block'),
            btn_template_image  = btn_template.find('.xpro-template-screenshot');
            btn_template_groups = btn_template.attr( 'data-groups' ) || '',
            btn_operation       = btn.attr('data-operation') || '',
            errorMessage        = XPROCloudTemplates.errorMessage,
            successMessage      = XPROCloudTemplates.successMessage,
            processAJAX         = true;
            form_nonce          = jQuery( '#xpro-cloud-templates-form' ).data( 'xpro-cloud-nonce' );

        //	add processing class
        if( meta_id !== 'undefined' ) {
            $('#' + meta_id ).addClass('xpro-template-processing');
        }

        //	remove error message if exist
        if( btn_template_image.find('.notice').length ) {
            btn_template_image.find('.notice').remove();
        }

        if( '' !== btn_operation ) {

            btn.find('i').addClass('xpro-reloading-iconfonts');

            switch( btn_operation ) {
                case 'fetch':
                    jQuery('.wp-filter').find('.xpro-cloud-process i').addClass('xpro-reloading-iconfonts');
                    btn.parents('.xpro-cloud-templates-not-found').find('.xpro-cloud-process i').show();
                    var dataAJAX = {
                        action: 'xpro_cloud_dat_file_fetch',
                        form_nonce:form_nonce,
                    };

                    break;

                case 'download':
                    var meta_dat_url   = btn.find('.template-dat-meta-dat_url').val() || '',
                        successMessage = XPROCloudTemplates.successMessageDownload,
                        dataAJAX       = {
                            action: 'xpro_cloud_dat_file',
                            dat_file: meta_dat_url,
                            dat_file_id: meta_id,
                            dat_file_type: meta_type,
                            form_nonce:form_nonce,
                        };

                    if( meta_dat_url === '' ) {
                        processAJAX = false;
                    }
                    break;

                case 'remove':
                    var meta_url_local = btn.find('.template-dat-meta-dat_url_local').val() || '',
                        successMessage = XPROCloudTemplates.successMessageRemove,
                        dataAJAX       = {
                            action: 'xpro_cloud_dat_file_remove',
                            dat_file_id: meta_id,
                            dat_file_type: meta_type,
                            dat_file_url_local: meta_url_local,
                            form_nonce:form_nonce,
                        };

                    if( meta_id === '' ) {
                        processAJAX = false;
                    }
                    break;
            }

            if( processAJAX ) {

                XPROajaxQueue.add({
                    url: XPROCloudTemplates.ajaxurl,
                    type: 'POST',
                    data: dataAJAX,
                    success: function(data){

                        /**
                         * Parse response
                         */
                        var status                 = ( data.hasOwnProperty('status') ) ? data.status : '';
                        var msg                    = ( data.hasOwnProperty('msg') ) ? data.msg : '';
                        var template_id            = ( data.hasOwnProperty('id') ) ? data.id : '';
                        var template_type          = ( data.hasOwnProperty('type') ) ? data.type : '';
                        var template_dat_url       = ( data.hasOwnProperty('dat_url') ) ? decodeURIComponent( data.dat_url ) : '';
                        var template_dat_url_local = ( data.hasOwnProperty('dat_url_local') ) ? decodeURIComponent( data.dat_url_local ) : '';

                        if( status === 'success' ) {

                            //	remove processing class
                            if( meta_id !== 'undefined' ) {
                                $('#' + meta_id ).removeClass('xpro-template-processing');
                            }

                            switch( btn_operation ) {
                                case 'remove':
                                    jQuery( window ).trigger( 'xpro-template-removed' );

                                    btn.removeClass('button-remove');
                                    btn.addClass('button-primary');
                                    btn.find('i').removeClass('xpro-reloading-iconfonts dashicons-no dashicons-update');
                                    btn.find('i').addClass('dashicons-yes');

                                    btn.find('.msg').html( XPROCloudTemplates.successMessageRemove );
                                    setTimeout(function() {

                                        btn_template.attr('data-is-downloaded', '');
                                        btn_template.removeClass( 'xpro-downloaded' );
                                        btn_template.removeClass( 'installed' );
                                        btn.attr('data-operation', 'download');

                                        var output = '<i class="dashicons dashicons-update"></i>'
                                            + '<input type="hidden" class="template-dat-meta-id" value="'+ template_id +'" />'
                                            + '<input type="hidden" class="template-dat-meta-type" value="'+ template_type +'" />'
                                            + '<input type="hidden" class="template-dat-meta-dat_url" value="'+ template_dat_url +'" />';

                                        btn.html( output );

                                    }, 1000);

                                    break;
                                case 'download':
                                    jQuery( window ).trigger( 'xpro-template-downloaded', [ template_dat_url_local, template_id ] );

                                    btn.removeClass('button-primary');
                                    btn.addClass('button-remove');
                                    btn.find('i').removeClass('xpro-reloading-iconfonts dashicons-no dashicons-update');
                                    btn.find('i').addClass('dashicons-yes');

                                    btn.find('.msg').html( XPROCloudTemplates.successMessageDownload );
                                    setTimeout(function() {

                                        btn.attr('data-operation', 'remove');
                                        btn_template.attr('data-is-downloaded', 'true');
                                        btn_template.addClass( 'xpro-downloaded' );
                                        btn_template.addClass( 'installed' );

                                        var output = '<i class="dashicons dashicons-no-alt"></i>'
                                            + '<input type="hidden" class="template-dat-meta-id" value="'+ template_id +'" />'
                                            + '<input type="hidden" class="template-dat-meta-type" value="'+ template_type +'" />'
                                            + '<input type="hidden" class="template-dat-meta-dat_url_local" value="'+ template_dat_url_local +'" />';

                                        var outputInstalled = '<span class="button button-sucess xpro-installed-btn">'
                                            + '<i class="dashicons dashicons-yes"></i>'
                                            + '</span>';

                                        btn.html( output );

                                    }, 1000);

                                    return;
                                    break;

                                case 'fetch':
                                    jQuery( window ).trigger( 'xpro-template-fetched' );

                                    btn.parents('.wp-filter').find('.xpro-cloud-process i').removeClass('xpro-reloading-iconfonts dashicons-no dashicons-update');
                                    btn.parents('.wp-filter').find('.xpro-cloud-process i').addClass('dashicons-yes');

                                    btn.parents('.wp-filter').find('.xpro-cloud-process .msg').html( XPROCloudTemplates.successMessageFetch );
                                    location.reload();

                                    break;
                            }

                        } else {

                            /**
                             * Something went wrong
                             */
                            if( '' !== msg ) {

                                btn.find('.msg').html( XPROCloudTemplates.errorMessageTryAgain );
                                btn.find('i').removeClass('xpro-reloading-iconfonts');

                                var message = '<div class="notice notice-error uct-notice is-dismissible"><p>' + msg + '	</p></div>';
                                btn_template_image.append( message );

                            } else {
                                btn.find('.msg').html( status );
                            }
                        }
                    }
                });
            }
        }

    });

} );
