var tbthemes_demo_content_import_running = false;
var tbthemes_demo_content_iframe_running = false;
window.onbeforeunload = function() {
    if ( tbthemes_demo_content_import_running ) {
        return tbthemes_demo_content_params.confirm_leave;
    }
};

// -------------------------------------------------------------------------------
var tbthemes_demo_content_current_item = window.tbthemes_demo_content_current_item || {};

(function ( $ ) {

    var tbthemes_demo_content_params = tbthemes_demo_content_params || window.tbthemes_demo_content_params;

    var $document = $( document );

    /**
     * Function that loads the Mustache template
     */
    var repeaterTemplate = _.memoize(function () {
        console.log('repeaterTemplate start');
        var compiled,
            options = {
                evaluate: /<#([\s\S]+?)#>/g,
                interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
                escape: /\{\{([^\}]+?)\}\}(?!\})/g,
                variable: 'data'
            };

        return function (data, tplId ) {
            if ( typeof tplId === "undefined" ) {
                tplId = '#template-demo-content--preview';
            }
            compiled = _.template(jQuery( tplId ).html(), null, options);
            return compiled(data);
        };
    });


    String.prototype.format = function() {
        var newStr = this, i = 0;
        while (/%s/.test(newStr)) {
            newStr = newStr.replace("%s", arguments[i++]);
        }
        return newStr;
    };

    var template = repeaterTemplate();

    var TbthemesDemoContent  = {

        loading: function() {
            tbthemes_demo_content_import_running = true;
            tbthemes_demo_content_iframe_running = null;
            $( '#tbthemes_demo_content_iframe_running' ).remove();
            var frame = $( '<iframe id="tbthemes_demo_content_iframe_running" style="display: none;"></iframe>' );
            frame.appendTo('body');
            var doc;
            // Thanks http://jsfiddle.net/KSXkS/1/
            try { // simply checking may throw in ie8 under ssl or mismatched protocol
                doc = frame[0].contentDocument ? frame[0].contentDocument : frame[0].document;
            } catch(err) {
                doc = frame[0].document;
            }
            doc.open();
            // doc.close();
        },

        end_loading: function() {
             $( '#tbthemes_demo_content_iframe_running' ).remove();
            tbthemes_demo_content_import_running = false;
        },

        loading_step: function( $element ) {
            $element.removeClass( 'demo-contents--waiting demo-contents--running' );
            $element.addClass( 'demo-contents--running' );
        },
        completed_step: function( $element, event_trigger ) {
            $element.removeClass( 'demo-contents--running demo-contents--waiting' ).addClass( 'demo-contents--completed' );
            if ( typeof event_trigger !== "undefined" ) {
                $document.trigger( event_trigger );
            }
        },

        ajax: function( doing, complete_cb, fail_cb ) {
            console.log( 'Being....', doing );
            $.ajax( {
                url: tbthemes_demo_content_params.ajaxurl,
                data: {
                    action: 'demo_content__import',
                    doing: doing,
                    current_item: tbthemes_demo_content_current_item,
                    theme: '', // Import demo for theme ?
                    version: '' // Current demo version ?
                },
                type: 'GET',
                dataType: 'json',
                success: function( res ) {
                    console.log( res );
                    if ( typeof complete_cb === 'function' ) {
                        complete_cb( res );
                    }
                    console.log( 'Completed: '+ doing, res );
                    $document.trigger( 'demo_contents_'+doing+'_completed' );
                },
                fail: function( res ) {
                    if ( typeof fail_cb === 'function' ) {
                        fail_cb( res );
                    }
                    console.log( 'Failed: '+ doing, res );
                    $document.trigger( 'demo_contents_'+doing+'_failed' );
                    $document.trigger( 'demo_contents_ajax_failed', [ doing ] );
                },
                error: function (xhr, ajaxOptions, thrownError) {
                   console.log(xhr.status);
                   console.log(xhr.responseText);
                   console.log(thrownError);
                }
            } )
        },

        import_users: function() {
            var step =  $( '.demo-contents-import-users' );
            var that = this;
            that.loading_step( step );
            this.ajax( 'import_users', function(){
                that.completed_step( step );
            } );
        },
        import_categories: function() {
            var step =  $( '.demo-contents-import-categories' );
            var that = this;
            that.loading_step( step );
            this.ajax(  'import_categories', function(){
                that.completed_step( step );
            } );
        },
        import_tags: function() {
            var step =  $( '.demo-contents-import-tags' );
            var that = this;
            that.loading_step( step );
            this.ajax(  'import_tags', function(){
                that.completed_step( step );
            } );
        },
        import_taxs: function() {
            var step =  $( '.demo-contents-import-taxs' );
            var that = this;
            that.loading_step( step );
            this.ajax(  'import_taxs', function(){
                that.completed_step( step );
            } );
        },
        import_posts: function() {
            var step =  $( '.demo-contents-import-posts' );
            var that = this;
            that.loading_step( step );
            this.ajax( 'import_posts', function(){
                that.completed_step( step );
            } );
        },
        import_theme_options: function() {
            var step =  $( '.demo-contents-import-theme-options' );
            var that = this;
            that.loading_step( step );
            this.ajax( 'import_theme_options', function(){
                that.completed_step( step );
            } );
        },
        import_widgets: function() {
            var step =  $( '.demo-contents-import-widgets' );
            var that = this;
            that.loading_step( step );
            this.ajax( 'import_widgets', function(){
                that.completed_step( step );
            } );
        },
        import_customize: function() {
            var step =  $( '.demo-contents-import-customize' );
            var that = this;
            that.loading_step( step );
            this.ajax( 'import_customize', function (){
                that.completed_step( step );
            } );
        },
        toggle_collapse: function() {
            $document .on( 'click', '.demo-contents-collapse-sidebar', function( e ){
                $( '#tbthemes-demo-content--preview' ).toggleClass('preview-collapse');
            } );
        },
        done: function() {
            console.log( 'All done' );
            this.end_loading();
            $( '.demo-contents--import-now' ).replaceWith( '<a href="'+tbthemes_demo_content_params.home+'" class="button button-primary">'+tbthemes_demo_content_params.btn_done_label+'</a>' );
        },
        failed: function() {
            console.log( 'Import failed' );
            $( '.demo-contents--import-now' ).replaceWith( '<span class="button button-secondary">'+tbthemes_demo_content_params.failed_msg+'</span>' );
        },
        preview: function() {
            var that = this;
            $document .on( 'click', '.demo-content-themes-listing .theme', function( e ) {

                e.preventDefault();

                var t               = $( this );
                var btn             = $( '.demo-content-import-button', t );
                var name            = btn.attr( 'data-name' ) || '';
                var slug            = btn.attr( 'data-slug' ) || '';
                var tier            = btn.attr( 'data-tier' ) || 'free';
                var theme           = btn.attr( 'data-theme' ) || '';
                var demo_url        = btn.attr( 'data-demo-url' ) || '';
                var product_url     = btn.attr( 'data-product-url' ) || '';
                var cont            = btn.closest('.theme');
                var screenshot_url  = $( '.theme-screenshot img', cont ).attr( 'src' );

                $( '#tbthemes-demo-content--preview' ).remove();

                if ( tier === 'pro' ) {
                    if ( product_url !== '' ) {
                        window.open(product_url, '_blank');
                        return;
                    }
                    if ( demo_url === '' ) {
                        return;
                    }
                }

                tbthemes_demo_content_current_item =  {
                    name: name,
                    slug: slug,
                    tier: tier,
                    theme: theme,
                    demo_url: demo_url,
                    screenshot_url: screenshot_url
                };

                var previewHtml = template( tbthemes_demo_content_current_item );
                $( 'body' ).append( previewHtml );
                $( 'body' ).addClass( 'demo-content-body-viewing' );

                $document.trigger( 'demo_content_preview_opened' );
                console.log('preview method end');

            } );

            $document.on( 'click', '.demo-contents-close', function( e ) {
                e.preventDefault();
                if ( tbthemes_demo_content_import_running ) {
                    var c = confirm( tbthemes_demo_content_params.confirm_leave ) ;
                    if ( c ) {
                        tbthemes_demo_content_import_running = false;
                        $( this ).closest('#tbthemes-demo-content--preview').remove();
                        $( 'body' ).removeClass( 'demo-content-body-viewing' );
                    }
                } else {
                    $( this ).closest('#tbthemes-demo-content--preview').remove();
                    $( 'body' ).removeClass( 'demo-content-body-viewing' );
                }

            } );

        },
        checking_resources: function() {
            console.log('checking_resources start');
            var that = this;
            var button = $( '.demo-contents--import-now' );
            button.html( tbthemes_demo_content_params.checking_resource );
            button.addClass( 'updating-message' );
            button.addClass( 'disabled' );
            that.ajax( 'checking_resources', function( res ) {
                if ( res.success ) {
                    button.removeClass( 'disabled' );
                    button.removeClass( 'updating-message' );
                    button.html( tbthemes_demo_content_params.import_now );
                } else {
                    $( '.demo-contents--activate-notice.resources-not-found' ).show().removeClass( 'demo-contents-hide' );
                    $( '.demo-contents--activate-notice.resources-not-found .demo-contents--msg' ).addClass('not-found-data').show().html( res.data );
                    $( '.demo-contents-import-progress' ).hide();
                    var text = tbthemes_demo_content_params.import_now;
                    button.replaceWith( '<a href="#" class="demo-contents--no-data-btn button button-secondary disabled disable">'+text+'</a>' );
                }
            } );
            console.log('checking_resources end');
        },
        init: function() {
            var that = this;

            that.preview();
            that.toggle_collapse();

            $document.on( 'demo_contents_ready', function() {
                $( '.demo-contents--activate-notice.resources-not-found ').slideUp(200).addClass( 'content-demos-hide' );
                that.loading();
                that.import_users();
            } );
            
            $document.on( 'demo_contents_import_users_completed', function() {
                that.import_categories();
            } );
            
            $document.on( 'demo_contents_import_categories_completed', function() {
                that.import_tags();
            } );
            
            $document.on( 'demo_contents_import_tags_completed', function() {
                that.import_taxs();
            } );
            
            $document.on( 'demo_contents_import_taxs_completed', function() {
                that.import_posts();
            } );
            
            $document.on( 'demo_contents_import_posts_completed', function() {
                that.import_theme_options();
            } );
            
            $document.on( 'demo_contents_import_theme_options_completed', function() {
                that.import_widgets();
            } );
            
            $document.on( 'demo_contents_import_widgets_completed', function() {
                that.import_customize();
            } );
            
            $document.on( 'demo_contents_import_customize_completed', function() {
                that.done();
            } );
            
            $document.on( 'demo_contents_ajax_failed', function() {
                that.failed();
            } );

            // Toggle Heading
            $document.on( 'click', '.demo-contents--step', function( e ){
                e.preventDefault();
                $( '.demo-contents--child-steps', $( this ) ).toggleClass( 'demo-contents--show' );
            } );

            // Import now click
            $document.on( 'click', '.demo-contents--import-now', function( e ) {
                e.preventDefault();
                if ( ! $( this ).hasClass( 'updating-message' ) ) {
                    $( this ).addClass( 'updating-message' );
                    $( this ).html( tbthemes_demo_content_params.importing );
                    $document.trigger( 'demo_contents_ready' );
                }
            } );

            $document.on( 'demo_content_preview_opened', function() {
                //  that.loading();
                that.checking_resources();
            } );

            // Custom upload demo file
            var Media = wp.media({
                title: wp.media.view.l10n.addMedia,
                multiple: false,
               // library:
            });
            
            that.uploading_file = false;
            $document.on( 'click', '.demo-contents--upload-xml', function(e) {
                e.preventDefault();
                Media.open();
                that.uploading_file = 'xml';
            } );

            $document.on( 'click', '.demo-contents--upload-json', function(e) {
                e.preventDefault();
                Media.open();
                that.uploading_file = 'json';
            } );

            var check_upload = function(){
                if ( typeof  tbthemes_demo_content_current_item.xml_id !== "undefined"
                    &&typeof  tbthemes_demo_content_current_item.json_id !== "undefined"
                    && tbthemes_demo_content_current_item.xml_id
                    && tbthemes_demo_content_current_item.json_id
                ) {
                    $( '.demo-contents-import-progress' ).show();
                    $( '.demo-contents--no-data-btn' ).replaceWith( '<a href="#" class="demo-contents--import-now button button-primary">' + tbthemes_demo_content_params.import_now + '</a>' );
                }
            };

            Media.on('select', function () {
                var attachment = Media.state().get('selection').first().toJSON();
                var id = attachment.id;
                var file_name = attachment.filename;
                var ext = file_name.split('.').pop();
                if (that.uploading_file == 'xml') {
                    if (ext.toLowerCase() == 'xml') {
                        tbthemes_demo_content_current_item.xml_id = id;
                        $('.demo-contents--upload-xml').html(file_name);
                        check_upload();
                    }
                }

                if (that.uploading_file == 'json') {
                    if (ext.toLowerCase() == 'txt' || ext.toLowerCase() == 'json') {
                        tbthemes_demo_content_current_item.json_id = id;
                        $('.demo-contents--upload-json').html(file_name);
                        check_upload();
                    }
                }

            });

            // END Custom upload demo file

        }
    };

    $.fn.tbtDemoContent = function() {
        TbthemesDemoContent.init();
    };


}( jQuery ));

jQuery( document ).ready( function( $ ){
    $( document ).tbtDemoContent();
});