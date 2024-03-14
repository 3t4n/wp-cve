export default {
	bindings: {
		index: '<',
		setting: '<',
		remove: '&',
		social: '<',
		messageIndex: '<',
	},
	template: sfPostFormTmpls.advancedSetting,
	controller: function( fieldService, cacheService ) {
		let data = cacheService.get( 'advancedSetting' );
		this.socialdata = cacheService.get('socialData');
		this.loaded = cacheService.get('loaded_mess_'+this.social.type);

        this.socialMustSend = false;
        for (var i = 0; i < this.socialdata.length; i++) {
            if (
                this.social.type === this.socialdata[i].type
                &&
                typeof this.socialdata[i].messages
                &&
                typeof this.socialdata[i].messages[0] != 'undefined'
                &&
                typeof this.socialdata[i].messages[0] != 'undefined'
                &&
                typeof this.socialdata[i].messages[0].setting != 'undefined'
                &&
                typeof this.socialdata[i].messages[0].setting != 'undefined'
                &&
                typeof this.socialdata[i].messages[0].setting[0] != 'undefined'
				&&
                typeof this.socialdata[i].messages[0].setting[0]['must_send'] != 'undefined'
            ) {
                this.socialMustSend = this.socialdata[i].messages[0].setting[0]['must_send'];
                break;
            }
        }
        this.must_send = 0;
		formatSelectVals.call( this );
		if ( this.index > 0 )
			this.setting.duplicate = true;

		this.getConstantData = ( key ) => {
			return data.const[ key ];
		}

		this.getFieldName = ( name ) => {
			return fieldService.getSettingName( this.social, this.messageIndex, this.index, name );
		}
		this.getPublishOptions = () => {
			return this.getConstantOptions( 'publish_option', filterPublishOptions);

		}
		this.getConstantOptions = ( key, filter ) => {
			let options = this.getConstantData( key );
			let duplicate = this.getConstantData('duplicated');
			let output  = [];

			if ( filter )
				options = filter.call( this, options );
			for ( key in options ) {
				if(this.loaded  && key !== 'schedule') {
					continue;
				} else if(this.loaded && key === 'schedule') {
                    this.setting.publish_option.key = 'schedule';
				}
				output.push({
					key: key,
					value: options[ key ]
				})
			}
			if(!this.loaded)
				cacheService.set(true,'loaded_mess_'+this.social.type);
			return output;
		}
		
		this.toggleMustSend = () => {

            this.must_send = +!this.must_send
		}

		function filterPublishOptions( options ) {
			let duplicated = data.const.duplicated;

			if ( !this.setting.duplicate )
				return options;

			let output = {};

			duplicated.forEach( ( val ) => {
				output[ val ] = options[ val ];
			});

			return output;
		}

		function formatSelectVals() {
			for ( let key in data.defaults ) {
				let def   = data.defaults[ key ];
				let value = this.setting[ key ];
				if ( angular.isObject( value ) && value.key )
					continue;

				if ( !value )
					value = def;

				if (key)
				this.setting[ key ] = {
					key: value
				}
			}
		}
        this.must_send = (this.socialMustSend === false) ? +this.setting.must_send.key : +this.socialMustSend;
	}
}