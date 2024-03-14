( function( wp ) {
    var registerPlugin = wp.plugins.registerPlugin;
	var PluginSidebar = wp.editPost.PluginSidebar;
	var PluginSidebarMoreMenuItem = wp.editPost.PluginSidebarMoreMenuItem;
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var TransparentEnable = wp.components.ToggleControl;
	var withSelect = wp.data.withSelect;
	var withDispatch = wp.data.withDispatch;
	var compose = wp.compose.compose;
	var __ = wp.i18n.__;

	var TransparentToggle = compose(
		withDispatch( function( dispatch ) {
            return {
				setMetaFieldValue: function( value ) {
					dispatch( 'core/editor' ).editPost(
						{ meta: { tteo2020_transparent_header: value } }
					);
				}
			}
        } ),
        withSelect( function( select ) {
			return {
				metaFieldValue: select( 'core/editor' )
					.getEditedPostAttribute( 'meta' )
					[ 'tteo2020_transparent_header' ],
				hasCoverTemplate: select( 'core/editor' ).getEditedPostAttribute( 'template' ) === 'templates/template-cover.php',
			}
		} )
    )( function( props ) {
        return el( TransparentEnable, {
            label: __( 'Transparent header', 'extra-options-for-twenty-twenty' ),
			checked: props.metaFieldValue,
			help: props.hasCoverTemplate ? __( 'It only works if the Cover Temaplate is not selected' , 'extra-options-for-twenty-twenty' ) : '',
            onChange: function( value ) {
                props.setMetaFieldValue( value );
            },
        } );
	} );
	
	sidebarArgs = {
		handle: 't2020-editor-sidebar',
		icon: 'admin-appearance',
		title: __( 'Twenty Twenty Extras', 'extra-options-for-twenty-twenty' ),
	}
	
    registerPlugin( sidebarArgs.handle, {
		render: function() {
			return el( Fragment, {},
				el( PluginSidebarMoreMenuItem,
					{
						target: sidebarArgs.handle,
						icon: sidebarArgs.icon,
					},
					sidebarArgs.title
				),
				el( PluginSidebar,
					{
						name: sidebarArgs.handle,
						icon: sidebarArgs.icon,
						title: sidebarArgs.title,
					},
					el( 'div',
						{ className: 't2020-transparent-header' },
						el( TransparentToggle )
					)
				)
			);
		}
	} );
	
} )( window.wp );