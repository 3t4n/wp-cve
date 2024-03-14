<template>
	<tr
		v-if="consignment"
		class="dropp-consignment"
		:class="classes"
	>
		<td
			class="dropp-consignment__barcode"
			:title="consignment.dropp_order_id"
			v-html="barcode_html"
		> </td>
		<td class="dropp-consignment__status">
			<span v-show="!loading" v-html="status"></span>
			<loader v-show="loading"></loader>
		</td>
		<td class="dropp-consignment__created">{{created_at}}</td>
		<td class="dropp-consignment__updated">{{updated_at}}</td>
		<td
			class="dropp-consignment__actions"
		>
			<div
				:class="context_class"
				class="dropp-context-menu"
				v-if="consignment.dropp_order_id"
			>
				<div class="dropp-context-menu__main">
					<a
						class="dropp-context-menu__first"
						target="_blank"
						:href="download_url(consignment)"
						v-html="i18n.download"
					></a>
					<span
						class="dropp-context-menu__button"
						@click.prevent="toggle_context"
					>⚙️</span>
				</div>
				<div class="dropp-context-menu__dropdown">
					<context-pdf
						v-if="show_context"
						:consignment_id="consignment.id"
						class="dropp-context-menu__pdf"
					>
					</context-pdf>
					<hr>
					<ul class="dropp-context-menu__actions">
						<li>
							<a
							href="#"
							v-html="i18n.check_status"
							@click.prevent="check_status"
							></a>
						</li>
						<li>
							<a
								class="dropp-consignment__action"
								href="#"
								v-html="i18n.view_order"
								@click.prevent="view_order"
							></a>
						</li>
						<li v-if="is_initial">
							<a
								class="dropp-consignment__action dropp-consignment__action--cancel"
								href="#"
								v-html="i18n.cancel_order"
								@click.prevent="cancel_order"
							></a>
						</li>
					</ul>
				</div>
			</div>
		</td>
	</tr>
</template>


<style lang="scss">

	.dropp-consignment {
		opacity: 1;
		transition: opacity 0.2s;
		&--loading {
			opacity: 0.5;
		}
		&:nth-of-type(2n) {
			background: darken(#FFF, 5%);
		}
		&--ready {
		}
		&--cancelled,
		&--error {
			background: #FEE;
			&:nth-of-type(2n) {
				background: #FCC;
			}
		}
		&--initial,
		&--transit,
		&--consignment,
		&--delivered {
			color: navy;
			background: #e6fdfe;
			&:nth-of-type(2n) {
				background: darken(#e6fdfe, 5%);
			}
		}
		&__actions {
			width: 12rem;
		}
		& &__action--cancel {
			color: #900;
		}
		& &__action--disabled {
			color: #999;
			opacity: 0.5;
			cursor: not-allowed;
		}
	}

	.dropp-context-menu {
		a {
			color: #0071a1;
			text-decoration: none;
		}
		position: relative;
		&__first {
			flex: 1 0 auto;
			padding: 0.5rem;
			border-radius: 5px;
			&:hover {
				background-color: #f1f1f1;
			}
		}
		&__button {
			border-left: 1px solid #0071a1;
			padding: 0.5rem;
			user-select: none;
			border-top-right-radius: 5px;
			border-bottom-right-radius: 5px;
			&:hover {
				background-color: #f1f1f1;
			}
		}

		&__main {
			background: #f3f5f6;
			border: 1px solid #0071a1;
			display: flex;
			border-radius: 5px;
		}
		&__dropdown {
			display: none;
			position: absolute;
			top: 100%;
			margin: 0;
			left: 0;
			right: 0;
			background: #fff;
			padding: 1rem;
			border: 1px solid #0071a1;
			border-top: none;
			border-bottom-left-radius: 5px;
			border-bottom-right-radius: 5px;
		}
		&--show {
			z-index: 3;
			.dropp-context-menu__dropdown {
				display: block;
			}
			.dropp-context-menu__main {
				border-bottom-left-radius: 0;
				border-bottom-right-radius: 0;
			}
		}
	}
</style>

<script>
	import ContextPdf from './context-pdf.vue';
	import Loader from './loader.vue';
	import time_ago from './time-ago.js';
	export default {
		data: function() {
			return {
				i18n: _dropp.i18n,
				loading: false,
				show_context: false,
			};
		},
		props: [ 'consignment' ],
		mounted: function() {
			if ( ! window._dropp_closers ) {
				window._dropp_closers = [];
			}
			window._dropp_closers.push( this.close_context );
		},
		computed: {
			classes: function() {
				let classes = [
					'dropp-consignment',
					'dropp-consignment-' + this.consignment.id,
					'dropp-consignment--' + this.consignment.status,
				];
				if ( this.loading ) {
					classes.push( 'dropp-consignment--loading' );
				}
				return classes.join( ' ' );
			},
			context_class: function() {
				return this.show_context ? 'dropp-context-menu--show' : '';
			},
			created_at: function() {
				return time_ago( this.consignment.created_at );
			},
			updated_at: function() {
				return time_ago( this.consignment.updated_at );
			},
			status: function() {
				return _dropp.status_list[ this.consignment.status ];
			},
			is_initial: function() {
				return this.consignment.dropp_order_id && this.consignment.status === 'initial';
			},
			barcode_html: function() {
				let consignment = this.consignment;
				let html = '';
				html += (consignment.test ? '[TEST] ' : '') + (consignment.barcode ? consignment.barcode : '');
				html += '<br>' + consignment.products.length + '&nbsp;';
				html += ( consignment.products.length === 1 ?  this.i18n.product : this.i18n.products );
				return html;
			}
		},
		methods: {
			close_context: function() {
				this.show_context = false;
			},
			toggle_context: function() {
				if ( this.show_context ) {
					this.show_context = false;
				} else {
					for (var i = 0; i < window._dropp_closers.length; i++) {
						window._dropp_closers[i]();
					}
					this.show_context = true;
				}
			},
			add_location: function() {
				//@TODO: Location selector.
				let vm = this;
				chooseDroppLocation()
					.then( function( location ) {
						location.order_item_id = vm.selected_shipping_item;
						// A location was picked. Save it.
						vm.locations.push( location );
					} )
					.catch( function( error ) {
						// Something went wrong.
						// @TODO.
						console.log( error );
					});
			},
			check_status: function() {
				if (this.loading) {
					return;
				}
				this.show_context = false;
				this.loading = true;
				jQuery.ajax( {
					url: _dropp.ajaxurl,
					method: 'get',
					data: {
						action: 'dropp_status_update',
						consignment_id: this.consignment.id,
					},
					success: this.success,
					error:   this.error,
				} );
			},
			view_order: function() {
				this.show_context = false;
				this.$parent.show_modal( this.consignment );
			},
			cancel_order: function() {
				if (this.loading) {
					return;
				}
				this.loading = true;
				jQuery.ajax( {
					url: _dropp.ajaxurl,
					method: 'get',
					data: {
						action:         'dropp_cancel',
						consignment_id: this.consignment.id,
						dropp_nonce:    _dropp.nonce,
					},
					success: this.success,
					error:   this.error,
				} );
			},
			success: function( data, textStatus, jqXHR ) {
				if ( data.status ) {
					this.response = data;
					if ( 'success' === data.status ) {
						this.consignment.status     = data.consignment.status;
						this.consignment.updated_at = data.consignment.updated_at;
					}
					else {
						alert( data.message );
					}
				} else {
					console.error( 'Invalid ajax response' );
				}
				let vm = this;
				setTimeout( function() {
					vm.loading = false;
				}, 500 );
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				console.log( jqXHR );
				console.log( textStatus );
				console.log( errorThrown );
				let vm = this;
				setTimeout( function() {
					vm.loading = false;
				}, 500 );
			},
			download_url: function( consignment ) {
				if ( ! consignment.dropp_order_id ) {
					return;
				}
				return _dropp.ajaxurl + '?action=dropp_pdf&consignment_id=' + consignment.id;
			},
		},
		components: {
			Loader,
			ContextPdf,
		},
	};
</script>
