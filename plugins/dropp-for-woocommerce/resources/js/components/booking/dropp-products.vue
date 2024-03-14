<template>
	<div class="dropp-products">
		<h3 v-html="i18n.products"></h3>
		<div class="dropp-products__product"
			v-for="product in products"
			:key="product.sku"
		>
			<label>
				<div v-if="editable" class="dropp-products__quantity-wrapper">
					<input type="checkbox" v-model="product.checked">
					<input
						class="dropp-products__quantity"
						type="number"
						step="1"
						min="0"
						:max="product.quantity"
						v-model.number="product._quantity"
					>
				</div>
				<span v-else>
					{{product._quantity}}
				</span>
				<span v-html="'&times; ' + product.name"></span>
				<span v-html="product.weight * product._quantity + ' Kg'"></span>
			</label>
		</div>
	</div>
</template>

<style lang="scss">
	.dropp-products {
		h3 {
			margin-top: 0;
			margin-bottom: 0.5rem;
		}
		&__quantity {
			width: 5rem;
			text-align: right;
		}
		&__quantity-wrapper {
			display: inline-block;
		}
	}
</style>

<script>
	export default {
		data: function() {
			return {
				i18n: _dropp.i18n,
			};
		},
		props: ['products', 'editable'],
	}
</script>
