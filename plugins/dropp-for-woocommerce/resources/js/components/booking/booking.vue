<template>
	<div class="dropp-booking">
		<div class="dropp-consignments" v-show="display_consignments">
			<h2 class="dropp-consignments__title" v-html="i18n.booked_consignments"></h2>
			<table class="dropp-consignments__table">
				<thead>
					<th v-html="i18n.barcode"></th>
					<th v-html="i18n.status"></th>
					<th v-html="i18n.created"></th>
					<th v-html="i18n.updated"></th>
					<th v-html="i18n.actions" class="dropp-consignment__actions"></th>
				</thead>
				<tbody>
					<consignmentrow
						v-for="consignment in consignment_container.consignments"
						:consignment="consignment"
						:key="consignment.id"
					></consignmentrow>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
		</div>
		<div class="dropp-toggle-locations" v-show="display_consignments" :class="toggle_classes">
			<a
				class="dropp-toggle-locations__create"
				@click.prevent="toggle_locations"
			>Show/hide booking form</a>
		</div>
		<div class="dropp-locations" v-show="display_locations">
			<location
				v-for="location in locations"
				:location="location"
				:key="location.id"
				:consignment_container="consignment_container"
			>
			</location>

			<div class="dropp-locations__add-location" v-show="shipping_items.length">
				<select
					class="dropp-locations__add-dropdown"
					v-model="selected_shipping_item"
					v-if="selected_shipping_item.length > 1"
				>
					<option
						v-for="shipping_item in shipping_items"
						:key="shipping_item.id"
						:value="shipping_item.id"
						v-html="shipping_item.label"
					>
					</option>
				</select>
				<button
					class="dropp-locations__add-button"
					@click.prevent="add_location"
					v-html="i18n.add_location"
				>
				</button><button
					v-for="special, shipping_method in special_locations"
					:key="shipping_method"
					class="dropp-locations__add-button"
					@click.prevent="add_special_delivery( special.location )"
					v-html="special.label"
				>
				</button>
			</div>
		</div>
		<ordermodal v-if="modal_consignment" :consignment="modal_consignment"></ordermodal>
	</div>
</template>


<style lang="scss">
	.dropp-booking {

		button,
		[type="submit"] {
			background: #0071a1;
			border-radius: 3px;
			outline: none;
			padding: 0.5rem 1rem;
			border: 1px solid #0071a1;
			color: white;
			transition: background-color 0.2s, border-color 0.2s, color 0.1s;

			&:focus {
				box-shadow: 0 0 0 1px #fff, 0 0 0 3px #007cba;
			}
			&:active {
				background-color: #fff;
				color: #000;
			}
			&:hover {
				background-color: #e6fdfe;
				color: #000;
			}
			&:disabled {
				opacity: 0.4;
			}
		}

		a {
			cursor: pointer;
			&:focus,
			&:hover {
				text-decoration: underline;
			}
		}
	}
	.dropp-toggle-locations {
		padding-left: 12px;
		padding-right: 12px;
		padding-bottom: 1rem;
		margin-left: -12px;
		margin-right: -12px;
	}
	.dropp-consignments {
		margin-bottom: 1rem;
		th {
			text-align: left;
		}
		th, td {
			padding: 2px 4px;
			&:first-of-type {
				padding-left: 12px;
			}
			&:last-of-type {
				padding-right: 12px;
			}
		}
		&__table {
			width: 100%;
			border-spacing: 0;
			margin-left: -12px;
			margin-right: -12px;
			width: calc(100% + 24px);
		}
		#woocommerce-order-dropp-booking &__title {
			font-size: 1.5rem;
			font-weight: 700;
			padding: 0;
		}
	}
	.dropp-locations {
		&__add-location {
			margin-top: 1rem;
		}
		&__add-button {
			margin-right: 0.5rem;
		}
	}
</style>

<script>
	import Location from './location.vue';
	import OrderModal from './order-modal.vue';
	import ConsignmentRow from './consignment-row.vue';
	export default {
		data: function() {
			return {
				i18n: _dropp.i18n,
				locations: _dropp.locations,
				special_locations: _dropp.special_locations,
				shipping_items: _dropp.shipping_items,
				selected_shipping_item: false,
				consignment_container: {
					consignments: _dropp.consignments
				},
				display_locations: true,
				modal_consignment: null,
			};
		},
		created: function() {
			if ( this.consignment_container.consignments.length ) {
				this.display_locations = false;
			}

			if ( this.shipping_items.length ) {
				this.selected_shipping_item = this.shipping_items[0].id;
			}

			var res = jQuery.ajax(
				{
					url:      _dropp.dropplocationsurl,
					dataType: "script",
					// success:  dropp_handler.success,
					// error:    dropp_handler.error,
					timeout:  3000,
				}
			);
		},
		computed: {
			display_consignments: function() {
				return this.consignment_container.consignments.length;
			},
			toggle_classes: function() {
				let classes = [];
				if ( this.display_locations ) {
					classes.push( 'dropp-toggle-locations--active' );
				}
				return classes.join(' ');
			}
		},
		methods: {
			toggle_locations: function() {
				this.display_locations = ! this.display_locations;
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
			add_special_delivery: function( raw_location ) {
				let location = {
					id: raw_location.id,
					name: raw_location.name,
					barcode: raw_location.barcode,
				};
				location.order_item_id = this.selected_shipping_item;
				this.locations.push( location );
			},
			show_modal: function( consignment ) {
				this.modal_consignment = consignment;
			},
		},
		components: {
			location: Location,
			consignmentrow: ConsignmentRow,
			ordermodal: OrderModal,
		}
	};
</script>
