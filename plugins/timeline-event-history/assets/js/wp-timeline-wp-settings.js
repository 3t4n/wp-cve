wp.TimelineWP = 'undefined' === typeof( wp.TimelineWP ) ? {} : wp.TimelineWP;

(function( $, TimelineWP ){

    var TimelineWPSettings = Backbone.Model.extend({
    	initialize: function( args ){
            var model = this;
            $.each( args, function( att, value ){
                model.set( att, value );
            });

      		var view = new TimelineWP.settings['view']({
      			model: this,
      			el: $( '#timeline-wp-settings' )
      		});

      		this.set( 'view', view );

        },
    });

    var TimelineWPSettingsView = Backbone.View.extend({

    	events: {
    		// Tabs specific events
    		'click .timeline-wp-tab':     'changeTab',
            'click .timeline-wp-tab > *': 'changeTabFromChild',

    		// Settings specific events
            'keyup input':         'updateModel',
            'keyup textarea':      'updateModel',
            'change input':        'updateModel',
            'change textarea':     'updateModel',
            'blur textarea':       'updateModel',
            'change select':       'updateModel',
        },

        initialize: function( args ) {

        	this.tabs          = this.$el.find( '.timeline-wp-tabs .timeline-wp-tab' );
        	this.tabContainers = this.$el.find( '.timeline-wp-tabs-content > div' );
        	this.sliders       = this.$el.find( '.timeline-wp-ui-slider' );
        	this.colorPickers  = this.$el.find( '.timeline-wp-color' );

        	// initialize 3rd party scripts
        	this.initSliders();
        	this.initColorPickers();

        },

        updateModel: function( event ) {
            // return;
        	var value, setting;

        	// Check if the target has a data-field. If not, it's not a model value we want to store
            if ( undefined === event.target.dataset.setting ) {
                return;
            }

            setting = event.target.dataset.setting;

            // Update the model's value, depending on the input type
            if ( event.target.type == 'checkbox' ) {
                value = ( event.target.checked ? event.target.value : 0 );
            } else {
                value = event.target.value;
            }

            console.log( setting );
            console.log( value );
            console.log( this.model );
            // Update the model
            return;
            this.model.set( setting, value );
            console.log( "changed" );
            console.log( this.model );

        },

        changeTab: function ( event ) {

        	var currentTab = jQuery( event.target ).data( 'tab' );

            if ( this.tabContainers.filter( '#' + currentTab ).length < 1 ) {
                return;
            }

    		this.tabs.removeClass( 'active-tab' );
    		this.tabContainers.removeClass( 'active-tab' );
    		jQuery( event.target ).addClass( 'active-tab' );
    		this.tabContainers.filter( '#' + currentTab ).addClass( 'active-tab' );

        },

        changeTabFromChild: function ( event ) {

            var currentTab = jQuery( event.target ).parent().data( 'tab' );

            if ( this.tabContainers.filter( '#' + currentTab ).length < 1 ) {
                return;
            }

            this.tabs.removeClass( 'active-tab' );
            this.tabContainers.removeClass( 'active-tab' );
            jQuery( event.target ).parent().addClass( 'active-tab' );
            this.tabContainers.filter( '#' + currentTab ).addClass( 'active-tab' );

        },

        initSliders: function() {

        	if ( this.sliders.length > 0 ) {
    			this.sliders.each( function( $index, $slider ) {
                    var input = jQuery( $slider ).parent().find( '.timeline-wp-ui-slider-input' ),
                        max = input.data( 'max' ),
                        min = input.data( 'min' ),
                        step = input.data( 'step' ),
                        value = parseInt( input.val(), 10 );

                    jQuery( $slider ).slider({
                        value: value,
                        min: min,
                        max: max,
                        step: step,
                        range: 'min',
                        slide: function( event, ui ) {
                            input.val( ui.value ).trigger( 'change' );
                        }
                    });
                });
    		}

        },

        initColorPickers: function() {
        	if ( this.colorPickers.length > 0 ) {
                this.colorPickers.each( function( $index, colorPicker ) {
                	//@todo: we need to find a solution to trigger a change event on input.
                    jQuery( colorPicker ).wpColorPicker();
                });
            }
        }
       
    });

    TimelineWP.settings = {
        'model' : TimelineWPSettings,
        'view' : TimelineWPSettingsView
    };

    // Copy shortcode functionality
    // $('#copy-timeline-wp-shortcode').click(function (e) {
    //     e.preventDefault();
    //     var gallery_shortcode = $(this).parent().find('input');
    //     gallery_shortcode.focus();
    //     gallery_shortcode.select();
    //     document.execCommand("copy");
    //     $(this).next('span').text('Shortcode copied');

    // });

}( jQuery, wp.TimelineWP ))

