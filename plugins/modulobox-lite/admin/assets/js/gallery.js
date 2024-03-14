/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

( function( $ ) {

	var media = wp.media;

	// wrap the render() function to append controls
	media.view.Settings.Gallery = media.view.Settings.Gallery.extend( {

		render: function(e) {

			var $el = this.$el;

			media.view.Settings.prototype.render.apply( this, arguments );

			// append the type template and update the settings.
			$el.append( media.template( 'mobx-gallery-settings' ) );

			// wp.media does not support input number type
			// So manually update input number fields
			var attributes = this.model.attributes || {};
			var row_height = parseInt( attributes.mobx_row_height );
			var spacing    = parseInt( attributes.mobx_spacing );

			$el.find( '[data-setting="mobx_row_height"]' ).val( row_height >= 0 ? row_height : 220 );
			$el.find( '[data-setting="mobx_spacing"]' ).val( spacing >= 0 ? spacing : 4 );

			// hide the Columns setting
			var columnSetting = $el.find( 'select[name=columns]' ).closest( 'label.setting' );
			columnSetting.hide();

			return this;

		}

	} );

} )( jQuery );
