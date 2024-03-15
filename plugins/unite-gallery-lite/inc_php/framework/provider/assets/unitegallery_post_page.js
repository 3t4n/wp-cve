function UniteGalleryWPPostPage(){
	
	function trace(str){
		console.log(str);
	}
	
	var t = this;
	
	
	
	/**
	 * add media dialog item
	 */
	function addMediaDialogItem(){
		
		var media = wp.media;

		// Wrap the render() function to append controls.
		media.view.Settings.Gallery = media.view.Settings.Gallery.extend({
			render: function() {
				var $el = this.$el;
				
				media.view.Settings.prototype.render.apply( this, arguments );
				
				// Append the type template and update the settings.
				$el.append( media.template( 'jetpack-gallery-settings' ) );
				
				media.gallery.defaults.type = 'default'; // lil hack that lets media know there's a type attribute.
				this.update.apply( this, ['type'] );

				// Hide the Columns setting for all types except Default
				$el.find( 'select[name=type]' ).on( 'change', function () {
					var columnSetting = $el.find( 'select[name=columns]' ).closest( 'label.setting' );

					if ( 'default' === jQuery( this ).val() || 'thumbnails' === jQuery( this ).val() ) {
						columnSetting.show();
					} else {
						columnSetting.hide();
					}
				} ).change();
				
				return this;
			}
		});
		
		
		/*
        jQuery(document).on( 'click', '.insert-media', function( event ) {
            var workflow = wp.media.editor.get();
            var options = workflow.options;
            
            trace(wp.media.view.l10n);
            trace(workflow);
            
            //var NewMenuItem = new wp.media.view.RouterItem( _.extend( options, { text: 'New Item!' } ) );
            //workflow.menu.view.views.set( '.media-menu', NewMenuItem, _.extend( options, { add: true } ) );
            
            //trace(workflow.menu.view.views);
            
        });
        */
		
	}
	
	
	/**
	 * init the post page
	 */
	this.init = function(){
		
		//addMediaDialogItem();
		
	}
	
}


jQuery(document).ready(function(){
	
	var objUGPostPage = new UniteGalleryWPPostPage();
	objUGPostPage.init();
});

