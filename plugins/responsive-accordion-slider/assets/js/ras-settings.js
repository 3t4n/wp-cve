wp.RespAccordionSlider = 'undefined' === typeof( wp.RespAccordionSlider ) ? {} : wp.RespAccordionSlider;

(function( $, RespAccordionSlider ){

    var RespAccordionSliderSettings = Backbone.Model.extend({
    	initialize: function( args ){
            var model = this;
            $.each( args, function( att, value ){
                model.set( att, value );
            });

      		var view = new RespAccordionSlider.settings['view']({
      			model: this,
      			el: $( '#ras-accordion-slider-settings' )
      		});

      		this.set( 'view', view );

        },
    });

    var RespAccordionSliderSettingsView = Backbone.View.extend({

    	events: {
    		// Tabs specific events
    		'click .ras-tab':     'changeTab',
            'click .ras-tab > *': 'changeTabFromChild',

    		// Settings specific events
            'keyup input':         'updateModel',
            'keyup textarea':      'updateModel',
            'change input':        'updateModel',
            'change textarea':     'updateModel',
            'blur textarea':       'updateModel',
            'change select':       'updateModel',
        },

        initialize: function( args ) {

        	this.tabs          = this.$el.find( '.ras-tabs .ras-tab' );
        	this.tabContainers = this.$el.find( '.ras-tabs-content > div' );
        	this.sliders       = this.$el.find( '.img_ui_slider' );
        	this.colorPickers  = this.$el.find( '.ras-color' );
            this.customEditors = this.$el.find( '.accordion-slider-code-editor' );

        	// initialize 3rd party scripts
        	this.initSliders();
            this.initCustomCSS();
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

           
            // Update the model
            this.model.set( setting, value );
            
        },

        changeTab: function ( event ) {

        	var currentTab = jQuery( event.target ).data( 'tab' );

            if ( this.tabContainers.filter( '#' + currentTab ).length < 1 ) {
                return;
            }

    		this.tabs.removeClass( 'active-tab' );
    		this.tabContainers.removeClass( 'active-tab' );
    		jQuery( event.target ).addClass( 'active-tab' );
    		this.tabContainers.filter( '#' + currentTab ).addClass( 'active-tab' ).trigger( 'accordion-slider-current-tab' );

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
                    var input = jQuery( $slider ).parent().find( '.img_ui_slider-input' ),
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
        },

        initCustomCSS: function() {
            var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
            if ( this.customEditors.length > 0 ) {
                this.customEditors.each( function( $index, customEditorContainer ) {
                    var syntax          = $( customEditorContainer ).data( 'syntax' ),
                        id              = '#' + $( customEditorContainer ).find( '.accordion-slider-custom-editor-field' ).attr( 'id' ),
                        currentSettings = _.extend(
                            {},
                            editorSettings.codemirror,
                            {
                                mode: syntax,
                            }
                        );

                    var editor =  wp.codeEditor.initialize( $( id ), currentSettings );

                    $( customEditorContainer ).parents( '.accordion-slider-tab-content' ).on( 'accordion-slider-current-tab',function(){
                        editor.codemirror.refresh();
                    });
                });
            }
        }
       
    });

    RespAccordionSlider.settings = {
        'model' : RespAccordionSliderSettings,
        'view' : RespAccordionSliderSettingsView
    };

     //Copy shortcode functionality
     $('.copy-ras-shortcode').click(function (e) {
         e.preventDefault();
         var ras_shortcode = $(this).parent().find('input');
         ras_shortcode.focus();
         ras_shortcode.select();
         document.execCommand("copy");
         $(this).next('span').text('Shortcode Copied');

     });

}( jQuery, wp.RespAccordionSlider ))