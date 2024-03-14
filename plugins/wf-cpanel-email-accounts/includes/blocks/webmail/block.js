const { __ } = wp.i18n;
const el = wp.element.createElement;
const { registerBlockType } = wp.blocks;
const { useBlockProps } = wp.blockEditor;
//var createElement = wp.element.createElement;
let title = __( 'cPanel® Webmail', 'cpanel-email-accounts' );
registerBlockType( 'wf-cpanel-email-accounts/webmail', {
	edit: function( props ) { return el( 'button', { className: props.className }, title ); },
	save: function( props ) { return el( 'button', { className: props.className }, title ); }
} );
