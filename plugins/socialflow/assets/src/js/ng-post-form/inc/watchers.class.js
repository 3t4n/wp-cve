export default class Watchers {
	constructor() {

	}

	toggle( watcher ) {
		if ( this.register( watcher ) )
			return;

		this[ watcher ] = ! this[ watcher ];
	}

	register( watcher ) {
		if ( this.hasOwnProperty( watcher ) ) 
			return false;

		this[ watcher ] = true;

		return true;
	}
}