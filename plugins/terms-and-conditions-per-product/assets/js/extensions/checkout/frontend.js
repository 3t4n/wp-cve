class HandleProductsTerms {
	constructor(term, allTerms) {
		this.term = term;
		this.allTerms = allTerms;
		this.label = this.term.querySelector(".woocommerce-form__label");
		this.checkboxInput = this.term.querySelector(
			".woocommerce-form__input-checkbox"
		);
		this.placeOrderButton = document.querySelector(
			".wc-block-checkout__main .wc-block-components-checkout-place-order-button"
		);
		this.mustReadTerms = document.querySelector(".extra-terms-must-read");

		this.termLink = this.term.querySelector("a");
		this.isAllTermsChecked = false;
		this.init();
	}

	init() {
		this.handleCheckbox();
		this.handleLink();
		this.handleOrderButtonVisibility();
	}

	handleOrderButtonVisibility(hide = true) {
		if(hide) {
			this.placeOrderButton.disabled = true;
			this.placeOrderButton.classList.add("terms-not-checked");
		} else {
			this.placeOrderButton.disabled = false;
			if(this.placeOrderButton.classList.contains("terms-not-checked")) {
				this.placeOrderButton.classList.remove("terms-not-checked");
			}
		}
	}

	checkAllTerms() {
		const mappedTerms = Array.from(this.allTerms).filter((term) => {
			const checkboxInput = term.querySelector(
				".woocommerce-form__input-checkbox"
			);
			return checkboxInput.checked;
		});
		return mappedTerms;
	}

	handleCheckbox() {
		this.label.addEventListener("click", (e) => {
			const isChecked = this.checkboxInput.checked;
			const name = this.checkboxInput.name;
			const getTerms = sessionStorage.getItem("extraTerms");
			const clickedTerms = getTerms ? JSON.parse(getTerms) : [];
			const checkedTerms = this.checkAllTerms();

			if(this.mustReadTerms) {
				if(clickedTerms.includes(name)) {
					this.checkboxInput.disabled = false;
					this.checkboxInput.checked = isChecked;
					if(checkedTerms.length >= this.allTerms.length) {
						this.handleOrderButtonVisibility(false);
					} else {
						this.handleOrderButtonVisibility(true);
					}
				} else {
					this.checkboxInput.checked = false;
				}
			} else {
				this.checkboxInput.checked = isChecked;
				if(checkedTerms.length >= this.allTerms.length) {
					this.handleOrderButtonVisibility(false);
				} else {
					this.handleOrderButtonVisibility(true);
				}
			}
		});
	}

	handleLink() {
		this.termLink.addEventListener("click", (e) => {
			const getTerms = sessionStorage.getItem("extraTerms");
			const clickedTerms = getTerms ? JSON.parse(getTerms) : [];
			if(!clickedTerms.includes(this.checkboxInput.name)) {
				clickedTerms.push(this.checkboxInput.name);
				sessionStorage.setItem("extraTerms", JSON.stringify(clickedTerms));
			}
		});
	}
}

window.addEventListener("load", () => {
	const extraTerms = document.querySelectorAll(
		".wc-block-checkout__main .extra-terms"
	);
	const placeOrderButton = document.querySelector(
		".wc-block-checkout__main .wc-block-components-checkout-place-order-button"
	);

	// Bailout if no order button exists
	if(placeOrderButton === undefined || placeOrderButton === null) {
		return;
	}

	extraTerms.forEach((term) => {
		new HandleProductsTerms(term, extraTerms);
	});
	let toolTipWrapper = document.createElement("div");
	toolTipWrapper.classList.add("extra-terms-place-order-tooltip-wrapper");
	let toolTip = document.createElement("p");
	toolTip.classList.add("extra-terms-place-order-tooltip");
	toolTip.innerHTML = tacppChBlock.notCheckedNotice;
	toolTipWrapper.appendChild(toolTip);
	placeOrderButton.appendChild(toolTipWrapper);
});
