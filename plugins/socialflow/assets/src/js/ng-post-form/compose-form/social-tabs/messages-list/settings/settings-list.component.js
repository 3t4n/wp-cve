export default {
	bindings: {
		social: '<',
		index: '<',
		settings: '<',
	},
	template: sfPostFormTmpls.advancedSettingsList,
	controller: function() {
		this.addItem = ( e ) => {
			e.preventDefault();

			this.settings.push( {
				duplicate: true
			} );
		}

		this.removeItem = ( item ) => {
			this.settings.splice( this.settings.indexOf( item ), 1 );
		}
	}
}