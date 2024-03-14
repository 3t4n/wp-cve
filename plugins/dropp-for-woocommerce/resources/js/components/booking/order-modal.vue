<template>
	<div class="dropp-order-modal" @click.prevent="close_modal">
		<div class="dropp-order-modal__inner"
				@click.prevent.stop="">
			<location
				:consignment="consignment"
				:location="location"
			></location>
		</div>
	</div>
</template>
<style lang="scss">
	.dropp-order-modal {
		position: fixed;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		background: rgba(0,0,0,0.6);
		display: flex;
		align-items: center;
		z-index: 1000000;
		overflow: auto;
		&__inner {
			background: white;
			padding: 0 12px;
			min-width: 16rem;
			max-width: 40rem;
			margin: 0 auto;
			width: 100%;
			height: auto;
		}
	}
</style>
<script>
	import Location from './location.vue';
	export default {
		props: ['consignment'],
		data: function() {
			return {
				location: this.consignment.location,
				loading: false,
			}
		},
		components: {
			location: Location,
		},
		methods: {
			close_modal: function() {
				this.$parent.modal_consignment = null;
			}
		}
	};
</script>
