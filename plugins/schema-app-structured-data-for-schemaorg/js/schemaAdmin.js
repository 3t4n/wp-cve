
	function HunchSchemaMediaInsert( Input, Type )
	{
		var Frame;
		Type = Type || 'image';

		if ( undefined !== Frame )
		{
			Frame.open();

			return;
		}

		if ( typeof wp.media === 'undefined' )
		{ 
			return;
		}

		Frame = wp.media
		({
			frame: 'select',
			editing: true,
			multiple: false,
			library:
			{
				type: Type
			}
		})
		.on( 'select', function()
		{
			var Selection = Frame.state().get( 'selection' ).first().toJSON();

			Input.val( Selection.url ).change();
		});

		Frame.open();
	}


jQuery(document).ready(function ($) {

    //// 
    // Upload Publisher Logo Settings Page
    $('#publisher_image_button').click(function (e) {
        e.preventDefault();

		HunchSchemaMediaInsert( $( '#publisher_image' ) );
    });


	$( '#SchemaDefaultImageSelect' ).click( function( e )
	{
		e.preventDefault();

		HunchSchemaMediaInsert( $( '#SchemaDefaultImage' ) );
	});


    // Tab based navigation on settings page
    $( '.wrap .nav-tab-wrapper a' ).click( function( e )
    {
        e.preventDefault();

		var Elm = $( this );

		Elm.blur();

		$( '.wrap .nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
		$( '.wrap .nav-tab-wrapper a' ).eq( Elm.index() ).addClass( 'nav-tab-active' );

        $('.wrap section').hide();
        $('.wrap section').eq(Elm.index()).show();
    });

    // Switch to a specific tab on page load
    function schemaLoadSwitchTab() {
        var tabIndex = 1;                               // Default to Settings tab
        $( "section[id^='schema-app']" ).each(function (index) {
            if ($(this)[0].id === schemaData.tab) {
                tabIndex = index;
            }
        });
        $( "section[id^='schema-app']" ).hide();
        $( "section[id^='schema-app']" ).eq(tabIndex).show();
    };

    schemaLoadSwitchTab();

});