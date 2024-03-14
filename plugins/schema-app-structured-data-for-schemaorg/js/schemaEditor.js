jQuery(document).ready(function ($) {
    
    // Capture click event to form POST request of data
    $('#extendSchema').click(function (e) {
        
        e.preventDefault();
        var data = { 
            resourceURI: $('#resourceURI').val(),
            resourceData: $('#resourceData').val() 
        }
        $.SchemaAppForm('https://app.schemaapp.com/importpost', data, 'POST').submit();

    });
    
    $.extend({
        SchemaAppForm: function (url, data, method) {
            if (method == null)
                method = 'POST';
            if (data == null)
                data = {};

            var form = $('<form>').attr({
                method: method,
                action: url,
                target: '_blank'
            }).css({
                display: 'none'
            });

            var addData = function (name, data) {
                if ($.isArray(data)) {
                    for (var i = 0; i < data.length; i++) {
                        var value = data[i];
                        addData(name + '[]', value);
                    }
                } else if (typeof data === 'object') {
                    for (var key in data) {
                        if (data.hasOwnProperty(key)) {
                            addData(name + '[' + key + ']', data[key]);
                        }
                    }
                } else if (data != null) {
                    form.append($('<input>').attr({
                        type: 'hidden',
                        name: String(name),
                        value: String(data)
                    }));
                }
            };

            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    addData(key, data[key]);
                }
            }
            
            return form.appendTo('body');
        }
    });


	$( '.MetaSchemaMarkup .Edit' ).click( function( e )
	{
		e.preventDefault();

		var ElmWrap = $( this ).closest( '.MetaSchemaMarkup' );

		$( this ).hide();
		ElmWrap.find( 'textarea' ).prop( 'disabled', false ).focus().val( '' );
		ElmWrap.find( '.Delete' ).show();
		$( '#MetaSchemaMarkupNote .default' ).hide();
		$( '#MetaSchemaMarkupNote .custom' ).show();
	});


	$( '.MetaSchemaMarkup .Delete' ).click( function( e )
	{
		e.preventDefault();

		var ElmWrap = $( this ).closest( '.MetaSchemaMarkup' );

		ElmWrap.find( 'textarea' ).val( '' ).blur();
		$( '#MetaSchemaMarkupNote .default' ).show();
		$( '#MetaSchemaMarkupNote .custom' ).hide();
	});


	$( '.MetaSchemaMarkup textarea' ).blur( function()
	{
		var Elm = $( this );
		var ElmWrap = $( this ).closest( '.MetaSchemaMarkup' );

		if ( ! Elm.prop( 'disabled' ) )
		{
			Elm.val( Elm.val().replace( '<script>', '' ) );
			Elm.val( Elm.val().replace( '<script type="application/ld+json">', '' ) );
			Elm.val( Elm.val().replace( "<script type='application/ld+json'>", '' ) );
			Elm.val( Elm.val().replace( '</script>', '' ) );

			ElmWrap.find( '.ErrorMessage' ).empty();
			Elm.closest( '.MetaSchemaMarkup' ).find( '.Updating' ).show();

			$.ajax
			({
				type: 'POST',
				url: ajaxurl,
				data: { 'action': 'HunchSchemaMarkupUpdate', 'Id': Elm.attr( 'data-id' ), 'Data': Elm.val() },
				dataType: 'json'
			})
			.done( function( Data )
			{
				if ( Data && Data.Status == 'Ok' )
				{
					if ( Data.Delete )
					{
						Elm.prop( 'disabled', true ).val( $( '#MetaSchemaMarkupDefault' ).val() );

						ElmWrap.find( '.Delete' ).hide();
						ElmWrap.find( '.Edit' ).show();
						$( '#MetaSchemaMarkupNote .default' ).show();
						$( '#MetaSchemaMarkupNote .custom' ).hide();
					}
				}
				else if ( Data && Data.Status == 'Error' )
				{
					ElmWrap.find( '.ErrorMessage' ).html( Data.Message );
				}
				else
				{
					ElmWrap.find( '.ErrorMessage' ).html( 'Request error, please try again later.' );
				}
			})
			.fail( function( jqXHR, textStatus, errorThrown )
			{
				ElmWrap.find( '.ErrorMessage' ).html( 'Connection error: ' + errorThrown );
			})
			.always( function( Data )
			{
				Elm.closest( '.MetaSchemaMarkup' ).find( '.Updating' ).hide();
			});
		}
	});

});