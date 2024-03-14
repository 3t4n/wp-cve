( function() {
	function slplSetLoaderClass( id, name ) {
		var el = document.getElementById( id );
		if ( el.classList ) {
			el.classList.add( name );
		} else {
			el.className += " " + name;
		}
		console.log('Loader closed.');
	}
	function slplHideLoader() {
		if ( slplPreLoader.showingLoader ) {
			slplPreLoader.showingLoader = false;
			if ( typeof slplResourceComplete === 'function' && slplResourceComplete() < 95 ) {
				setTimeout( function() {
						slplSetLoaderClass( 'sl-preloader', 'sl-pl-loaded' );
					},
					100
				);
			} else {
				slplSetLoaderClass( 'sl-preloader', 'sl-pl-loaded' );
			}
			setTimeout( function() {
					document.getElementById( 'sl-preloader' ).remove();
				},
				1300
			);
		}
	}
	window.addEventListener( 'load', function() {
		console.log('Page loaded.');
		slplPreLoader.pageLoaded = true;
		if ( 0 == slplPreLoader.minShowTime ) {
			slplHideLoader();
		}
	});
	function slplSetCloseButton() {
		if ( slplPreLoader.showingLoader ) {
			var cButton = document.getElementById( 'sl-pl-close-button' );
			cButton.style.display = "block";
			cButton.addEventListener( 'click', function( e ) {
				slplHideLoader();
			});
		}
	}
	if( slplPreLoader.showCloseButton > 0 ) {
		setTimeout( function() {
				slplSetCloseButton();
			},
			slplPreLoader.showCloseButton
		);
	}
	if ( slplPreLoader.maxShowTime > 0 ) {
		setTimeout( function() {
				slplHideLoader();
			},
			slplPreLoader.maxShowTime
		);
	}
	function slplSetMinShowTime() {
		if ( slplPreLoader.pageLoaded ) {
			slplHideLoader();
		} else {
			slplPreLoader.minShowTime = 0;
		}
	}
	if ( slplPreLoader.minShowTime > 0 ) {
		setTimeout( function() {
				slplSetMinShowTime();
			},
			slplPreLoader.minShowTime
		);
	}
} ) ();