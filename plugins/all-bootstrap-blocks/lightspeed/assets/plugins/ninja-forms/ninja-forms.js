jQuery( document ).ready( function( $ ) {

    var add_validation_classes = Marionette.Object.extend( {
        initialize: function() {
            var channel = Backbone.Radio.channel( 'form' );
            this.listenTo( channel, 'render:view', this.add_listener );

            var channel = Backbone.Radio.channel( 'submit' );
            this.listenTo( channel, 'validate:field', this.add_field_classes );
            
            var channel = Backbone.Radio.channel( 'fields' );
            this.listenTo( channel, 'change:modelValue', this.add_field_classes );
        },

        add_listener: function( model ) 
        {
            var model = model;

            var interval = setInterval( function() {
                var form = $( model.el );

                if ( form.length ) {
                    clearInterval( interval );

                    var errors = form.find( 'nf-errors' );

                    var target = errors[0];
                    
                    var observer = new MutationObserver(function(mutations) {
                        var error = target.innerText.replace( ' ', '' );

                        if ( error ) {
                            form.addClass( 'areoi-nf-has-errors' );
                        } else {
                            form.removeClass( 'areoi-nf-has-errors' );
                        }
                    });
                    
                    observer.observe(target, {
                        attributes:    true,
                        childList:     true,
                        characterData: true
                    });
                }
            }, 500);
        },

        add_field_classes: function( model ) 
        {
            var form = $( '#nf-form-' + model.get( 'formID' ) + '-cont' ),
                field = form.find( '#nf-field-' + model.get( 'id' ) + '-container' );
            
            setTimeout( function() {
                if ( model.get('errors') && model.get('errors').length ) {
                    field.find( '.nf-element:not([type="checkbox"], [type="radio"])' ).addClass( 'is-invalid' ).removeClass( 'is-valid' );
                } else {
                    field.find( '.nf-element:not([type="checkbox"], [type="radio"])' ).addClass( 'is-valid' ).removeClass( 'is-invalid' );
                }
            }, 250);
        }
  
    });

    new add_validation_classes();
});