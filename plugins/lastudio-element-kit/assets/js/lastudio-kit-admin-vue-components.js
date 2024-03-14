'use strict';

Vue.component( 'lastudio-kit-general-settings', {

	template: '#lastudio-kit-dashboard-lastudio-kit-general-settings',

	data: function() {
		return {
			pageOptions: window.LaStudioKitSettingsConfig.settingsData,
			preparedOptions: {},
			savingStatus: false,
			ajaxSaveHandler: null,
		};
	},

	watch: {
		pageOptions: {
			handler( options ) {
				let prepared = {};

				for ( let option in options ) {

					if ( options.hasOwnProperty( option ) ) {
						prepared[ option ] = options[option]['value'];
					}
				}

				this.preparedOptions = prepared;
				this.saveOptions();
			},
			deep: true
		}
	},

	methods: {

		updateSetting: function ( value, setting ){
			this.preparedOptions[setting] = value;
			this.saveOptions();
		},

		saveOptions: function() {

			var self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				headers: {
					'X-WP-Nonce': wpApiSettings.nonce,
				},
				url: window.LaStudioKitSettingsConfig.settingsApiUrl,
				dataType: 'json',
				data: self.preparedOptions,
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== self.ajaxSaveHandler ) {
						self.ajaxSaveHandler.abort();
					}
				},
				success: function( responce, textStatus, jqXHR ) {
					self.savingStatus = false;

					if ( 'success' === responce.status ) {
						self.$CXNotice.add( {
							message: responce.message,
							type: 'success',
							duration: 3000,
						} );
					}

					if ( 'error' === responce.status ) {
						self.$CXNotice.add( {
							message: responce.message,
							type: 'error',
							duration: 3000,
						} );
					}
				}
			} );
		},
	}
} );

Vue.component( 'lastudio-kit-integrations-settings', {

	template: '#lastudio-kit-dashboard-lastudio-kit-integrations-settings',

	data: function() {
		return {
			pageOptions: window.LaStudioKitSettingsConfig.settingsData,
			preparedOptions: {},
			savingStatus: false,
			ajaxSaveHandler: null,
		};
	},

	watch: {
		pageOptions: {
			handler( options ) {
				let prepared = {};

				for ( let option in options ) {

					if ( options.hasOwnProperty( option ) ) {
						prepared[ option ] = options[option]['value'];
					}
				}

				this.preparedOptions = prepared;

				this.saveOptions();
			},
			deep: true
		}
	},

	methods: {

		saveOptions: function() {

			var self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				headers: {
					'X-WP-Nonce': wpApiSettings.nonce,
				},
				url: window.LaStudioKitSettingsConfig.settingsApiUrl,
				dataType: 'json',
				data: self.preparedOptions,
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== self.ajaxSaveHandler ) {
						self.ajaxSaveHandler.abort();
					}
				},
				success: function( responce, textStatus, jqXHR ) {
					self.savingStatus = false;

					if ( 'success' === responce.status ) {
						self.$CXNotice.add( {
							message: responce.message,
							type: 'success',
							duration: 3000,
						} );
					}

					if ( 'error' === responce.status ) {
						self.$CXNotice.add( {
							message: responce.message,
							type: 'error',
							duration: 3000,
						} );
					}
				}
			} );
		},
	}
} );

Vue.component( 'lastudio-kit-avaliable-addons', {

	template: '#lastudio-kit-dashboard-lastudio-kit-avaliable-addons',

	data: function() {
		return {
			pageOptions: window.LaStudioKitSettingsConfig.settingsData,
			preparedOptions: {},
			savingStatus: false,
			ajaxSaveHandler: null,
			shouldReload: false,
		};
	},

	watch: {
		pageOptions: {
			handler( options ) {
				let prepared = {};

				for ( let option in options ) {

					if ( options.hasOwnProperty( option ) ) {
						prepared[ option ] = options[option]['value'];
					}
				}
				this.preparedOptions = prepared;
				this.saveOptions();
			},
			deep: true
		}
	},

	methods: {

		updateSetting: function ( value, settings, root_key, model ){
			if( ['portfolio_content_type', 'event_content_type', 'album_content_type'].includes(settings) ){
				this.shouldReload = true;
			}
			model[settings] = value;
			this.preparedOptions[root_key] = model;
			this.saveOptions();
		},

		saveOptions: function() {

			var self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				headers: {
					'X-WP-Nonce': wpApiSettings.nonce,
				},
				url: window.LaStudioKitSettingsConfig.settingsApiUrl,
				dataType: 'json',
				data: self.preparedOptions,
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== self.ajaxSaveHandler ) {
						self.ajaxSaveHandler.abort();
					}
				},
				success: function( responce, textStatus, jqXHR ) {
					self.savingStatus = false;

					if ( 'success' === responce.status ) {
						self.$CXNotice.add( {
							message: responce.message,
							type: 'success',
							duration: 3000,
						} );

						if(self.shouldReload){
							window.location.reload();
						}
					}

					if ( 'error' === responce.status ) {
						self.$CXNotice.add( {
							message: responce.message,
							type: 'error',
							duration: 3000,
						} );
					}
				}
			} );
		},
	}
} );

Vue.component( 'lastudio-kit-fonts-manager', {

	template: '#lastudio-kit-dashboard-lastudio-kit-fonts-manager',

	data: function() {
		return {
			preparedOptions: {},
			savingStatus: false,
			ajaxSaveHandler: null,
			customFonts: window.LaStudioKitSettingsConfig.settingsData.custom_fonts.value
		};
	},

	computed: {
		fieldsList: function() {
			var result = [];
			for ( var i = 0; i < this.customFonts.length; i++ ) {
				result.push( {
					title: this.customFonts[ i ].title || '',
					name: this.customFonts[ i ].name || '',
					type: this.customFonts[ i ].type || '',
					url: this.customFonts[ i ].url || '',
					variations: this.customFonts[ i ].variations || [],
				} );
			}
			return result;
		},
	},

	methods: {
		onInput: function ( value ){
			this.preparedOptions = {
				custom_fonts: value
			};
		},
		saveOptions: function() {

			var self = this;

			self.savingStatus = true;


			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				headers: {
					'X-WP-Nonce': wpApiSettings.nonce,
				},
				url: window.LaStudioKitSettingsConfig.settingsApiUrl,
				dataType: 'json',
				data: JSON.stringify(self.preparedOptions),
				contentType: 'application/json',
				beforeSend: function( jqXHR, ajaxSettings ) {
					if ( null !== self.ajaxSaveHandler ) {
						self.ajaxSaveHandler.abort();
					}
				},
				success: function( responce, textStatus, jqXHR ) {
					self.savingStatus = false;

					if ( 'success' === responce.status ) {
						self.$CXNotice.add( {
							message: responce.message,
							type: 'success',
							duration: 3000,
						} );
					}

					if ( 'error' === responce.status ) {
						self.$CXNotice.add( {
							message: responce.message,
							type: 'error',
							duration: 3000,
						} );
					}
				}
			} );
		},
	}
} );

Vue.component( 'lastudio-kit-swatches', {

	template: '#lastudio-kit-dashboard-lastudio-kit-swatches',

	data: function() {
		return {
			pageOptions: window.LaStudioKitSettingsConfig.settingsData,
			preparedOptions: {},
			savingStatus: false,
			ajaxSaveHandler: null,
		};
	},

	watch: {
		pageOptions: {
			handler( options ) {
				let prepared = {};

				for ( let option in options ) {

					if ( options.hasOwnProperty( option ) ) {
						prepared[ option ] = options[option]['value'];
					}
				}

				this.preparedOptions = prepared;

				this.saveOptions();
			},
			deep: true
		}
	},

	methods: {

		saveOptions: function() {

			var self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				headers: {
					'X-WP-Nonce': wpApiSettings.nonce,
				},
				url: window.LaStudioKitSettingsConfig.settingsApiUrl,
				dataType: 'json',
				data: self.preparedOptions,
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== self.ajaxSaveHandler ) {
						self.ajaxSaveHandler.abort();
					}
				},
				success: function( responce, textStatus, jqXHR ) {
					self.savingStatus = false;

					if ( 'success' === responce.status ) {
						self.$CXNotice.add( {
							message: responce.message,
							type: 'success',
							duration: 3000,
						} );
					}

					if ( 'error' === responce.status ) {
						self.$CXNotice.add( {
							message: responce.message,
							type: 'error',
							duration: 3000,
						} );
					}
				}
			} );
		},
	}
} );