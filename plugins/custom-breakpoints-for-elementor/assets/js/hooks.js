;(function($){

$(document).ready(function() {

    if ( ! ('jltma_custom_bp_data' in window) ) return;

    if ( ! $('#custom_breakpoints_page').length ) return;
        
    new Vue({

        el: '#custom_breakpoints_page',

        data: {
            show_pro_message: false,
            disable_add_breakpoint: false,
            default_devices: window.jltma_custom_bp_data.default_devices,
            breakpoints: []
        },

        computed: {

            total_custom_breakpoints() {
                return this.breakpoints.filter( function( breakpoint ) {
                    return ! this.in_array( breakpoint.key, this.default_devices );
                }.bind(this) ).length;
            },

            sorted_breakpoints() {

                var _this = this;

                var breakpoints = this.breakpoints.map(function( breakpoint, index ) {

                    if ( 'max' in breakpoint ) breakpoint.max = Number( breakpoint.max );

                    return breakpoint;

                });

                var breakpoints = breakpoints.sort(function( prev, next ) {
                    
                    if ( next.key == 'desktop' ) return -1;
                    if ( prev.max < next.max ) return -1;

                    return 1;

                });

                var breakpoints = breakpoints.map(function( breakpoint, index ) {
                    
                    var prev_breakpoint = breakpoints[ index - 1 ];

                    breakpoint.min = prev_breakpoint ? prev_breakpoint.max + 1 : 0;

                    if ( breakpoint.max > 0 && breakpoint.max <= breakpoint.min ) breakpoint.max = breakpoint.min + 1;

                    return breakpoint;

                });

                return breakpoints;

            }

        },

        mounted() {

            this.isPro = !! jltma_custom_bp_data.is_pro;

            this.breakpoints = window.jltma_custom_bp_data.breakpoints.map(function( breakpoint, index ) {
                breakpoint.isRecent = false;
                return breakpoint;
            });

            this.form_submits();

        },

        methods: {

            in_array( item, list ) {

                return list.indexOf( item ) > -1;

            },

            breakpoint_limit_checker() {

                if ( this.isPro ) return false; // there is no limit;

                if ( this.total_custom_breakpoints > 1 ) {
                    this.show_pro_message = true;
                    this.disable_add_breakpoint = true;
                    return true
                }

                this.show_pro_message = false;
                this.disable_add_breakpoint = false;

                return false;

            },

            input_focused( device ) {

                this.breakpoints.forEach(function(_dev) {
                    this.$set( _dev, 'isRecent', false );
                }.bind(this));

                this.$set( device, 'isRecent', true );

            },
            
            add_breakpoint() {

                var _this = this;

                if ( this.breakpoint_limit_checker() ) return;

                this.breakpoints.forEach(function(_dev) {
                    _this.$set( _dev, 'isRecent', false );
                });

                var data = {
                    key: Math.random().toString(36).substr(2, 9),
                    name: 'Test',
                    min: 0,
                    max: 0,
                    isDraft: true,
                    isRecent: true
                }

                this.$set( this.breakpoints, this.breakpoints.length, data );

            },

            remove_breakpoint( deviceKey ) {

                var index = this.breakpoints.findIndex(function(_dev) {
                    return _dev.key == deviceKey;
                });

                this.breakpoints.splice( index, 1 );

                if ( this.breakpoint_limit_checker() );

            },

            breakpoint_update( event, breakpoint ) {

                breakpoint.max = Number( event.target.value );

            },

            get_form_data() {

                return this.breakpoints.filter( function( breakpoint ) {
                    
                    return ! this.in_array( breakpoint.key, this.default_devices );

                }.bind(this)).map( function( breakpoint ) {

                    return {
                        label: breakpoint.name,
                        default_value: breakpoint.max,
                        direction: 'max'
                    }

                }.bind(this));

            },

            form_submits() {

                this.form_submit_import_breakpoints();

                this.form_submit_reset_form();

                this.form_submit_save_breakpoints();

            },

            form_submit_import_breakpoints() {

                jQuery("#elementor_settings_import_form").on( 'submit', function( evt ) {

                    evt.preventDefault();

                    var formData = new FormData(jQuery(this)[0]);

                    jQuery.ajax({
                        url: masteraddons.ajaxurl,
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        async: true,
                        cache: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        processData: false,
                        success: function (response) {
                            if ( response == 'ok' )  {
                                jQuery('#elementor_import_success').slideDown();
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        }
                    });

                    return false;

                });

            },

            form_submit_reset_form() {

                jQuery("#elementor_settings_reset_form").on( 'submit', function( evt ) {
                    
                    evt.preventDefault();

                    var formData = new FormData(jQuery(this)[0]), reset_form = $('#reset_form').val();

                    jQuery.ajax({
                        url: masteraddons.ajaxurl,
                        type: 'POST',
                        data: {
                            'security': reset_form,
                            action: 'jltma_mcb_reset_settings'
                        },
                        dataType: 'json',
                        async: true,
                        cache: false,
                        success: function ( response ) {
                            if ( response == 'ok' ) {
                                jQuery('#reset_success').slideDown();
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        }
                    });

                    return false;

                });

            },

            form_submit_save_breakpoints() {

                var _this = this;

                jQuery("#jlmta-cbp-form").on( 'submit', function(e) {

                    e.preventDefault();

                    var form = $(this);
                    
                    form.addClass('loading');

                    $.ajax({
                        url: masteraddons.ajaxurl,
                        method: 'POST',
                        data: {
                            form_fields: _this.get_form_data(),
                            'security': $('#breakpoints_form').val(),
                            action: 'jltma_mcb_save_settings'
                        },
                        success : function( data ) {
                            form.prepend( '<div class="updated"><p>Saved Breakpoints</p></div>' );
                            setTimeout(function() {
                                form.removeClass('loading');
                                form.find('.updated').remove();
                            }, 700 );
                        },
                        error : function(error){
                            console.log('failed', error);
                        }
                    });

                });

            }

        },

    });

});

})(jQuery);