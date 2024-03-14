( function() {
	'use strict';

	var dmtg = dmtg || {};

	function dmtgDomReady( fn ) {
		if ( typeof fn !== 'function' ) {
			return;
		}

		if ( 'interactive' === document.readyState || 'complete' === document.readyState ) {
			return fn();
		}

		document.addEventListener( 'DOMContentLoaded', fn, false );
	}

	document.addEventListener( 'alpine:init', function() {
		Alpine.data( 'tabs', function( value ) {
			return {
				tab: ( window.location.hash ? window.location.hash.substring( 1 ) : value ),

				tabLink: function( tab ) {
					return {
						':class'() {
							return ( ( this.tab === tab ) ? 'dmt-active' : '' );
						},
						'@click.prevent'() {
							this.tab = tab;
							window.location.hash = tab;
						}
					};
				},

				tabContent: function( tab ) {
					return {
						'x-show'() {
							return ( this.tab === tab );
						}
					};
				}
			};
		} );

		Alpine.data( 'position', function( value ) {
			return {
				pos: value,

				posInput: function( placement ) {
					return {
						'x-show'() {
							return this.pos.includes( placement );
						},
						'x-transition:enter.duration.400ms'() {}
					};
				}
			};
		} );

		Alpine.data( 'form', function() {
			return {
				loading: false,
				msg: '',
				msgSuccess: false,
				showInfo: false,
				openConfirm: false,

				init() {
					const state = this;

					state.$watch( 'loading', function( value ) {
						if ( value ) {
							state.msg = '';
							state.showInfo = false;
						}
					} );
				},

				btn() {
					return {
						':class'() {
							return this.loading && 'dmt-loading';
						}
					};
				},

				form: function( confirmSubmit = false ) {
					const state = this;

					return {
						'@submit.prevent'() {
							if ( confirmSubmit ) {
								state.openConfirm = true;
								return;
							}

							state.submit( this.$el );
						}
					};
				},

				submit( formEl, successCb = false ) {
					const state = this;

					const formData = new FormData( formEl );

					state.loading = true;
					fetch( ajaxurl, {
						method: 'POST',
						body: formData
					} ).then( function( res ) {
						return res.json();

					} ).then( function( json ) {
						const data = json.data;

						if ( json.success ) {
							state.msg = data.msg;
							if ( successCb ) {
								successCb();
							}
						} else {
							state.msg = data.msg;
						}

						state.msgSuccess = json.success;
						state.loading = false;

					} ).catch( function( e ) {
						state.msg = e;
						state.msgSuccess = false;

					} ).finally( function() {
						state.loading = false;
					} );
				},

				action: function( submit = false ) {
					const state = this;

					return {
						'@click.prevent'() {
							if ( submit ) {
								state.submit( this.$root );
							}

							state.openConfirm = false;
						}
					};
				},

				confirmModal: function() {
					return {
						'@click.outside'() {
							this.openConfirm = false;
						}
					};
				},

				resetAction: function() {
					const state = this;

					return {
						'@click.prevent'() {
							state.submit( this.$root, function() {
								state.showInfo = true;
							} );

							state.openConfirm = false;
						},

						':class'() {
							return state.loading && 'dmt-loading';
						}
					};
				},
			};
		} );

		Alpine.data( 'enable', function( value ) {
			return {
				enable: value,

				fields: function() {
					return {
						'x-show'() {
							return this.enable;
						},
						'x-transition:enter.duration.400ms'() {},
						'x-transition:leave.duration.200ms'() {}
					};
				}
			};
		} );

		Alpine.data( 'fixFlick', function( value ) {
			return {
				fixFlick: value,

				fields: function() {
					return {
						'x-show'() {
							return this.fixFlick;
						},
						'x-transition:enter.duration.400ms'() {},
						'x-transition:leave.duration.200ms'() {}
					};
				}
			};
		} );

		Alpine.data( 'override', function( value ) {
			return {
				override: value,

				fields: function() {
					return {
						'x-show'() {
							return this.override;
						},
						'x-transition:enter.duration.400ms'() {},
						'x-transition:leave.duration.200ms'() {}
					};
				}
			};
		} );
	} );

	dmtg.colorPicker = {
		init: function() {
			jQuery( '.dmt-color-picker' ).wpColorPicker();
		}
	};

	dmtgDomReady( function() {
		dmtg.colorPicker.init();
	} );

} )();
