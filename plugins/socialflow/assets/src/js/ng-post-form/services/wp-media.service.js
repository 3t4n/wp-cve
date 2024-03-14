
export default class WPMediaFrameService {
	/* @ngInject */
	constructor() {
		this._wpMediaFrame = this._initWPMediaFrame();
		this._handlers = {};
	}

	_initWPMediaFrame() {
		let frame = wp.media({
			multiple: false  // Set to true to allow multiple files to be selected
		});

		frame.on( 'select', this._onSelectInFrame.bind( this ) );

		return frame;
	}

	_onSelectInFrame( e ) {
		if ( !this._handlers ) 
			return;

		let handlers = this._handlers;

		let data = this._wpMediaFrame.state().get('selection').first().toJSON();

		if ( handlers.request ) {
			handlers.request( data.id )
				.then( ( response ) => {
					if ( handlers.success ) 
						handlers.success( response );
					
					this._clearTempData();
				});
		}

		if ( handlers.simpleHandler ) {
			handlers.simpleHandler( data );
		}
	}
	
	setAjaxHandler( handlers ) {
		if ( 'function' != typeof handlers.request )  {
			throw new Error( 'WPMediaFrameService.setAjaxHandler() - request is not a function' );
			return this;
		}

		if ( 'function' != typeof handlers.success ) {
			delete handlers.success;
		}

		this._handlers = handlers;

		return this; // for using this.open()
	}
	setHandler( handler ) {

		if ( 'function' != typeof handler )  {
			throw new Error( 'WPMediaFrameService.setHandler() - handler is not a function' );
			return this;
		}
		if(!this._handlers){
			this._handlers = {};
		}
		this._handlers.simpleHandler = handler;

		return this;
	}

	open() {
		this._wpMediaFrame.open();
	}

	_clearTempData() {
		this._handlers = null;
	}	
}