export default {
	bindings: {
		social: '<',
		messages: '<',
		global: '=',
	},
	template: sfPostFormTmpls.messagesList,
	controller: function() {
		this.addItem = () => {
			let message = JSON.parse( JSON.stringify( this.messages[0] ) );
			message.duplicate = true;
			message.loaded    = false;
			message.settings  = [ [] ];
			for ( let field in message.fields ) {
                message.fields[ field ] = '';
			}
            message.showContent = true;
			this.messages.push( message );
		}

		this.removeItem = ( item ) => {
			this.messages.splice( this.messages.indexOf( item ), 1 );
		}

		this.loadComponent = ( tmplType, socialType ) => {
			if ( tmplType == socialType ) 
				return true;

			let def = [ 'facebook', 'linkedin', 'google_plus' ];

			if ( 'default' != tmplType ) 
				return false;

			if ( -1 !== def.indexOf( socialType ) ) 
				return true;

			return false;
		}

		this.setMessageClass = ( message , index) => {
			if(index) {
                message.showContent = true;
			}
			let cl = this.social.type;

			if ( ! this.global.globalSettings.compose_media ) 
				return cl;

			return cl;
		}
	}
}