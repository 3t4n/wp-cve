/**
 * The script for the admin menu.
 * @link              https://de.wordpress.org/plugins/cb-parallax
 * @since             0.1.0
 * @package           cb-parallax
 * @subpackage        cb-parallax/admin/menu/js
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
"use strict";
( function( $ ){

    function CbParallaxSettingsDisplay (){
        // localized values
        this.cb_Parallax = Cb_Parallax_Admin;
        this.canParallax = undefined !== this.cb_Parallax.image_options.can_parallax ? this.cb_Parallax.image_options.can_parallax : '0';
        // 'movable parts' :)
        this.parallaxEnabledSwitch = null;
        this.placeholderImage = null;
        this.backgroundImage = null;
        this.attachmentId = null;
        this.CheckBoxes = null;
        this.parallaxDirectionSelectBox = null;
        this.addMediaButton = null;
        this.removeMediaButton = null;
        // form and buttons
        this.settingsForm = null;
        this.submitButton = null;
        this.resetButton = null;
        // Image options
        this.allImageOptions = null;
        this.staticImageOptions = null;
        this.parallaxImageOptions = null;
        this.attachmentWidth = this.cb_Parallax.image_data.image_width !== undefined ? this.cb_Parallax.image_data.image_width : 0;
        this.attachmentHeight = this.cb_Parallax.image_data.image_height !== undefined ? this.cb_Parallax.image_data.image_height : 0;
        // Containers for different settings display (static / parallax options)
        this.verticalScrollDirectionContainer = null;
        this.horizontalScrollDirectionContainer = null;
        this.verticalAlignmentContainer = null;
        this.horizontalAlignmentContainer = null;

        this.direction = undefined !== this.cb_Parallax.image_options.cb_parallax_direction ? this.cb_Parallax.image_options.cb_parallax_direction : this.cb_Parallax.default_options.cb_parallax_direction;
    }

    CbParallaxSettingsDisplay.prototype = {

        constructor: function(){
            // localized values
            this.cb_Parallax = Cb_Parallax_Admin;
            this.canParallax = undefined !== this.cb_Parallax.image_options.can_parallax ? this.cb_Parallax.image_options.can_parallax : '0';
            // 'movable parts' :)
            this.parallaxEnabledSwitch = null;
            this.placeholderImage = null;
            this.backgroundImage = null;
            this.attachmentId = null;
            this.CheckBoxes = null;
            this.parallaxDirectionSelectBox = null;
            this.addMediaButton = null;
            this.removeMediaButton = null;
            // form and buttons
            this.settingsForm = null;
            this.submitButton = null;
            this.resetButton = null;
            // Image options
            this.allImageOptions = null;
            this.staticImageOptions = null;
            this.parallaxImageOptions = null;
            this.attachmentWidth = this.cb_Parallax.image_data.image_width !== undefined ? this.cb_Parallax.image_data.image_width : 0;
            this.attachmentHeight = this.cb_Parallax.image_data.image_height !== undefined ? this.cb_Parallax.image_data.image_height : 0;
            // Containers for different settings display (static / parallax options)
            this.verticalScrollDirectionContainer = null;
            this.horizontalScrollDirectionContainer = null;
            this.verticalAlignmentContainer = null;
            this.horizontalAlignmentContainer = null;

            this.direction = undefined !== this.cb_Parallax.image_options.cb_parallax_direction ? this.cb_Parallax.image_options.cb_parallax_direction : this.cb_Parallax.default_options.cb_parallax_direction;
        },

        init: function(){
            this.initColorpicker();
            this.initFancySelect();
            this.assembleSettingsContainers();
            this.localizeScript();
            this.initSettingsDisplay();
            this.addEvents();
            this.initContextualHelp();
        },

        assembleSettingsContainers: function(){

            this.allImageOptions = $( '#cb_parallax_parallax_enabled_container, #cb_parallax_background_color_container, #cb_parallax_direction_container, #cb_parallax_vertical_scroll_direction_container, #cb_parallax_horizontal_scroll_direction_container, #cb_parallax_horizontal_alignment_container, #cb_parallax_vertical_alignment_container, #cb_parallax_overlay_image_container, #cb_parallax_overlay_opacity_container, #cb_parallax_overlay_color_container, #cb_parallax_background_repeat_container, #cb_parallax_position_x_container, #cb_parallax_position_y_container, #cb_parallax_background_attachment_container' );

            this.parallaxImageOptions = $( '#cb_parallax_parallax_enabled_container, #cb_parallax_background_color_container, #cb_parallax_direction_container, #cb_parallax_vertical_scroll_direction_container, #cb_parallax_horizontal_scroll_direction_container, #cb_parallax_horizontal_alignment_container, #cb_parallax_vertical_alignment_container, #cb_parallax_overlay_image_container, #cb_parallax_overlay_opacity_container, #cb_parallax_overlay_color_container' );

            this.staticImageOptions = $( '#cb_parallax_parallax_enabled_container, #cb_parallax_background_color_container, #cb_parallax_background_repeat_container, #cb_parallax_position_x_container, #cb_parallax_position_y_container, #cb_parallax_background_attachment_container, #cb_parallax_overlay_image_container, #cb_parallax_overlay_opacity_container, #cb_parallax_overlay_color_container' );

            this.verticalScrollDirectionContainer = $( '#cb_parallax_vertical_scroll_direction_container' );
            this.horizontalScrollDirectionContainer = $( '#cb_parallax_horizontal_scroll_direction_container' );
            this.verticalAlignmentContainer = $( '#cb_parallax_vertical_alignment_container' );
            this.horizontalAlignmentContainer = $( '#cb_parallax_horizontal_alignment_container' );

            // objects
            this.parallaxEnabledSwitch = $( '.cb_parallax_parallax_enabled' );
            this.placeholderImage = $( '.cb_parallax_placeholder_image' );
            this.backgroundImage = $( '.cb_parallax_background_image' );
            this.attachmentId = $( ".cb_parallax_attachment_id" );
            this.CheckBoxes = $( '.cb-parallax-input-checkbox' );
            this.parallaxDirectionSelectBox = $( "[data-key='cb_parallax_direction']" );
            this.parallaxDirectionSelectBoxTrigger = $( "#cb_parallax_direction_container" ).find( 'div.trigger' );
            this.parallaxSettingsTitle = $( 'h3.parallax' );
            this.staticSettingsTitle = $( 'h3.static' );
            this.addMediaButton = $( '.cb-parallax-media-url' );
            this.removeMediaButton = $( ".cb-parallax-remove-media" );
            this.settingsForm = $( '#cb_parallax_settings_form' );
            this.submitButton = $( '#cb_parallax_form_submit' );
            this.resetButton = $( '#cb_parallax_form_reset' );
        },

        initColorpicker: function(){
            $( '.cb-parallax-color-picker' ).wpColorPicker();
        },

        initFancySelect: function(){
            $( '.cb-parallax-fancy-select' ).fancySelect();
        },

        localizeScript: function(){
            $( '<style>.cb-parallax-switch-input ~ .cb-parallax-switch-label:before{content:"' + this.cb_Parallax.strings.switches_text.Off + '";}</style>' ).appendTo( 'head' );
            $( '<style>.cb-parallax-switch-input:checked ~ .cb-parallax-switch-label:after{content:"' + this.cb_Parallax.strings.switches_text.On + '";}</style>' ).appendTo( 'head' );
        },

        // Events
        addEvents: function(){
            this.CheckBoxes.on( 'click', this.toggleCheckbox );
            this.parallaxDirectionSelectBoxTrigger.on( 'change', { context : this }, this.toggleParallaxEnabledCheckbox );
            this.parallaxEnabledSwitch.on( 'change', { context : this }, this.initSettingsDisplay );
            this.parallaxDirectionSelectBox.on( 'change.fs', { context : this }, this.toggleParallaxEnabledCheckbox );
            this.removeMediaButton.on( 'click', { context : this }, this.removeMedia );
            this.addMediaButton.on( 'click', { context : this }, this.addMedia );
            this.submitButton.on( 'click', { context : this }, this.cbparallaxSaveOptions );
            this.resetButton.on( 'click', { context : this }, this.cbparallaxResetOptions );
        },

        // Actions
        initSettingsDisplay: function( event ){
            var $this = this;
            if ( undefined !== event ){
                $this = event.data.context;
            }
            // Hide all options at first
            $this.allImageOptions.hide();
            $this.removeMediaButton.hide();
            $this.removeMediaButton.show();

            if('1' === $this.canParallax && $this.parallaxEnabledSwitch.prop( 'checked' )){
                $this.staticImageOptions.hide();
                $this.parallaxImageOptions.show();
            } else {
                $this.parallaxImageOptions.hide();
                $this.staticImageOptions.show();
            }

            $this.toggleParallaxEnabledCheckbox();
        },

        toggleParallaxEnabledCheckbox: function( event ){

            var $this = this;
            if ( undefined !== event ){
                event.preventDefault();
                $this = event.data.context;
            }

            $this.parallaxSettingsTitle.addClass( 'hidden' );
            $this.staticSettingsTitle.addClass( 'hidden' );

            $($this.parallaxDirectionSelectBox).trigger( 'change.$' );

            if ( '' !== $this.backgroundImage.attr( 'src' ) ){
                $this.parallaxSettingsTitle.removeClass( 'hidden' );

                if ( $this.parallaxEnabledSwitch.prop( 'checked' ) ){
                    if ( $this.parallaxDirectionSelectBoxTrigger.text() === 'horizontal' ){
                        $this.horizontalScrollDirectionContainer.show();
                        $this.verticalAlignmentContainer.show();
                        $this.verticalScrollDirectionContainer.hide();
                        $this.horizontalAlignmentContainer.hide();
                    } else {
                        $this.verticalScrollDirectionContainer.show();
                        $this.horizontalAlignmentContainer.show();
                        $this.horizontalScrollDirectionContainer.hide();
                        $this.verticalAlignmentContainer.hide();
                    }
                } else {
                    $this.verticalScrollDirectionContainer.hide();
                    $this.horizontalAlignmentContainer.hide();
                    $this.horizontalScrollDirectionContainer.hide();
                    $this.verticalAlignmentContainer.hide();
                }
            } else {
                $this.verticalScrollDirectionContainer.hide();
                $this.horizontalAlignmentContainer.hide();
                $this.horizontalScrollDirectionContainer.hide();
                $this.verticalAlignmentContainer.hide();
                $this.staticSettingsTitle.removeClass( 'hidden' );
            }
        },

        toggleCheckbox: function(){
            if ( '0' === $( this ).val() ){
                $( this ).val( '1' );
            } else {
                $( this ).val( '0' );
            }
        },

        // Image handling
        addMedia: function( event ){
            event.preventDefault();
            var $this = event.data.context;

            var cb_parallax_frame = wp.media( {
                className : "media-frame cb-parallax-frame",
                frame : "select",
                multiple : false,
                title : cbParallaxMediaFrame.title,
                library : { type : "image" },
                button : { text : cbParallaxMediaFrame.button }
            } );

            cb_parallax_frame.on( "select", function(){
                var media_attachment = cb_parallax_frame.state().get( "selection" ).first().toJSON();

                $this.attachmentId.val( media_attachment.id );
                $this.backgroundImage.attr( 'src', media_attachment.url );
                // Hide placeholder image
                $this.placeholderImage.hide();
                // Update the values that are held in hidden fields
                $( "[data-key='cb_parallax_background_image_url']" ).val( media_attachment.url );
                $( "[data-key='cb_parallax_background_image_url_hidden']" ).val( media_attachment.url );
                $( "[data-key='cb_parallax_attachment_id']" ).val( media_attachment.id );
                $this.addMediaButton.addClass( 'disabled' );
                // Set image dimensions
                $this.attachmentHeight = media_attachment.height;
                $this.attachmentWidth = media_attachment.width;
                // Update the user interface
                $this.initSettingsDisplay();
            } );
            // Opens the media frame.
            cb_parallax_frame.open();
        },

        removeMedia: function( event ){
            event.preventDefault();
            var $this = event.data.context;

            $this.backgroundImage.attr( 'src', '' );
            // Show placeholder image
            $this.placeholderImage.show();
            $this.attachmentId.val( '' );
            $this.attachmentHeight = 0;
            $this.attachmentWidth = 0;
            // Update the values that are held in hidden fields
            $( "[data-key='cb_parallax_background_image_url_hidden']" ).val( '' );
            $( "[data-key='cb_parallax_attachment_id']" ).val( '' );
            $this.addMediaButton.removeClass( 'disabled' );

            $this.initSettingsDisplay();
            return false;
        },

        // Options handling
        cbparallaxSaveOptions: function( event ){
            event.preventDefault();
            var $this = event.data.context;

            var input = {};
            // Retrieve values
            var dataForm = $this.settingsForm.data( 'form' );
            var optionKeys = 'image' === dataForm ? $this.cb_Parallax.option_keys.image : $this.cb_Parallax.option_keys.all;
            $.each( optionKeys, function(){
                input[this] = $( "[data-key='" + this + "']" ).val();
            } );

            var data = {
                action : 'cb_parallax_save_options',
                nonce : $this.settingsForm.data( 'nonce' ),
                input : input,
                post_id : $this.settingsForm.data( 'postid' )
            };

            $.post( ajaxurl, data, function( response ){
                if ( response.success === true ){
                    alert( $this.cb_Parallax.strings.save_options_ok );
                } else {
                    alert( $this.cb_Parallax.strings.save_options_error );
                }
            } );

            $this.initSettingsDisplay();
            return false;
        },

        cbparallaxResetOptions: function( event ){
            event.preventDefault();
            var $this = event.data.context;

            var data = {
                action : 'cb_parallax_reset_options',
                nonce : $this.settingsForm.data( 'nonce' ),
                post_id : $this.settingsForm.data( 'postid' )
            };

            if ( !confirm( $this.cb_Parallax.strings.reset_settings_confirmation) ){
                return false;
            }
            $.post( ajaxurl, data, function( response ){
                if ( response.success === true ){
                    $this.resetOptionsPageInputFields();
                    $( '' );
                    alert( $this.cb_Parallax.strings.reset_options_ok );
                } else {
                    alert( $this.cb_Parallax.strings.reset_options_error );
                }
            } );
        },

        resetOptionsPageInputFields: function(){
            var dataForm = this.settingsForm.data( 'form' );
            var optionKeys = 'image' === dataForm ? this.cb_Parallax.option_keys.image : this.cb_Parallax.option_keys.all;

            var $this = this;
            $.each( optionKeys, function(){
                var element = $( "[data-key=" + this + "]" );
                var value = $this.cb_Parallax.default_options[this];
                if ( 'checkbox' === element.attr( 'type' ) ){
                    $( element ).val( '0' ).prop( 'checked', '' );
                } else if ( 'text' === element.attr( 'type' ) ){
                    $( element ).wpColorPicker( 'color', 'transparent' );
                } else if ( 'select' === element.attr( 'type' ) ){
                    // Set the actual dropdown value
                    element.val( value );
                    // Retrieve Fancy Select 'dropdown'
                    var list = $( element ).parent().find( 'ul.options' );
                    var listElements = $( list ).find( 'li' );
                    $( listElements ).removeAttr( 'class' );
                    $( list ).find( $( "li[data-raw-value=" + this + "]" ) ).addClass( 'selected' );
                    // Set the trigger content (the part that is shown inside fancy select select box
                    $( element ).parent().find( '.trigger' ).html( value );
                } else {
                    $( element ).val( value );
                }
            } );
            // Reset the preview image
            this.backgroundImage.attr( 'src', '' );
            // Show placeholder image
            this.placeholderImage.show();
            // Reset the values that are held in hidden fields
            $( "[data-key='cb_parallax_background_image_url_hidden']" ).val( '' );
            $( "[data-key='cb_parallax_attachment_id']" ).val( '' );
        },

        // Help Tab
        initContextualHelp: function(){
            $( "#cb-parallax-help-tabs" ).tabs();
        }
    };

    $( document ).one( 'ready', function(){
        var cbParallaxSettingsDisplay = new CbParallaxSettingsDisplay();
        cbParallaxSettingsDisplay.init();
    } );

} )( jQuery );
