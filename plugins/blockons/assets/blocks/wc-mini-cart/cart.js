/**
 * Functionality to show/hide the Blockons WC Mini Cart
 * Little JS hack to display the Cart amount and Mini Cart in the Cart Icon Block
 * Also localizes wcCartObj for edit.js
 *
 * FREE
 */
document.addEventListener("DOMContentLoaded", function () {
	const blockonsCartItems = document.querySelectorAll(
		".blockons-wc-mini-cart-block-icon"
	);

	if (blockonsCartItems) {
		blockonsCartItems.forEach((item) => {
			const cartItem = document.querySelector(".blockons-cart-amnt");
			if (cartItem) item.appendChild(cartItem.cloneNode(true));
		});
	}

	const blockonsDropDownCarts = document.querySelectorAll(
		".wp-block-blockons-wc-mini-cart.cart-dropdown"
	);

	if (blockonsDropDownCarts) {
		blockonsDropDownCarts.forEach((item) => {
			const miniCartParent = document.querySelector(
				`.${item.classList[0]} .blockons-wc-mini-cart-inner`
			);

			if (miniCartParent) {
				const miniCart = document.querySelector(".blockons-mini-crt");
				if (miniCart) miniCartParent.appendChild(miniCart.cloneNode(true));
			}
		});
	}
});
