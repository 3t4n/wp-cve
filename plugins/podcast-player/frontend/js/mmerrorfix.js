/* global MediaElementPlayer */
(function ( window ) {
	var init = MediaElementPlayer.prototype.init;
	MediaElementPlayer.prototype.init = function () {

		if ( this.node.classList.contains( 'pp-podcast-episode' ) ) {
			this.options.classPrefix = 'ppjs__';
		}
		init.call( this );
	};

})( window );