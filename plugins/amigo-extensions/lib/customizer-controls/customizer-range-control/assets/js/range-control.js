wp.customize.controlConstructor['amigo-extension-range-control'] = wp.customize.Control.extend({
	ready: function() {
		'use strict';
		var control = this,
			range = control.container.find( '.range-of-amigo-extension-range-control' ),
			output = control.container.find( '.value-of-amigo-extension-range-control' );
		range[0].oninput = function() {
			control.setting.set( this.value );
		}
		// reset range
		if ( control.params.default !== false ) {
			var reset = control.container.find( '.reset-amigo-extension-range-control' );
			reset[0].onclick = function() {
				control.setting.set( control.params.default );
			}
		}
	}
});
