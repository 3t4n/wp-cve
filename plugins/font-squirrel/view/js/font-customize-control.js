(function( exports, $ ){
	$.widget( "custom.fontselectmenu", $.ui.selectmenu, {
		_renderItem: function( ul, item ) {
			var li = $( "<li>", { style: 'font-family:'+item.value } );

			if ( item.disabled ) {
				li.addClass( "ui-state-disabled" );
			}
			this._setText( li, item.label );

			return li.appendTo( ul );
		},

		_drawButton: function() {
			$.ui.selectmenu.prototype._drawButton.apply( this, arguments );
			this.buttonText.css('font-family', this.element.find( "option:selected" ).val());
		},
		
		_select: function(){
			$.ui.selectmenu.prototype._select.apply( this, arguments );
			this.buttonText.css('font-family', this._getSelectedItem().css('font-family'));
		}
	});

	var api = wp.customize;
	api.controlConstructor.font = api.Control.extend({
		ready: function() {
			var control = this,
				section = this.container.closest('.control-section').find('.accordion-section-title');

			section.on('click', function(){
				setTimeout(function(){
					control.container.find('select').fontselectmenu({
						change: function( event, ui ) {
							control.setting.set(ui.item.value);
						}
					});
				}, 100);
			});

		}
	});

})( wp, jQuery );
