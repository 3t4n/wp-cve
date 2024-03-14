<?php defined( 'ABSPATH' ) || exit; ?>
<div class="yay-swatches-modal-wrapper">
	<div class="yay-swatches-locked-modal">
	  <svg width="32" height="32" xmlns="http://www.w3.org/2000/svg">
		<path
		  d="M16 21.915v2.594a.5.5 0 0 0 1 0v-2.594a1.5 1.5 0 1 0-1 0zM9 14v-3.5a7.5 7.5 0 1 1 15 0V14c1.66.005 3 1.35 3 3.01v9.98A3.002 3.002 0 0 1 23.991 30H9.01A3.008 3.008 0 0 1 6 26.99v-9.98A3.002 3.002 0 0 1 9 14zm3 0v-3.5C12 8.01 14.015 6 16.5 6c2.48 0 4.5 2.015 4.5 4.5V14h-9z"
		  fill="#c2cdd4"
		/>
	  </svg>
	  <p><?php esc_html_e( 'This feature is available in YaySwatches Pro version', 'yay-swatches' ); ?></p>
	  <a
		href="https://yaycommerce.com/yayswatches-variation-swatches-for-woocommerce/"
		target="_blank"
		class="button button-primary"
	  ><?php esc_html_e( 'Unlock this feature', 'yay-swatches' ); ?></a>
	</div>
</div>
<style>
	.yay-swatches-section-locked {
  opacity: 0.3;
  pointer-events: none;
}
.yay-swatches-modal-wrapper {
  background-image: linear-gradient(180deg, #ffffff66, #ffffff);
  height: 100%;
  position: relative;
  width: 100%;
  z-index: 1000;
}
.yay-swatches-locked-modal {
  align-items: center;
  border-radius: 12px;
  box-shadow: 0px 3px 68px 0px rgba(95, 95, 95, 0.31);
  border-radius: 4px;
  background-color: #fff;
  display: flex;
  flex-direction: column;
  left: 50%;
  padding: 24px 0;
  position: absolute;
  top: 150px;
  transform: translate(-50%, -50%);
  width: 400px;
  z-index: 10;
}

.yay-swatches-locked-modal p {
  font-size: 14px;
  font-weight: bold;
}

.yay-swatches-locked-modal .button-primary {
  font-weight: bold;
}

.yay-swatches-overlay {
  height: 100%;
  left: 0;
  position: absolute;
  top: 0;
  width: 100%;
  z-index: 5;
}

.yay-swatches-product-custom-fixed-prices-simple .yay-swatches-fixed-price-checkbox-wrapper {
  align-items: center;
  display: flex;
  pointer-events: none;
}

.yay-swatches-product-custom-fixed-prices-simple .yay-swatches-fixed-price-checkbox-wrapper label {
  font-size: 16px;
  font-weight: bold;
  width: auto;
}

.yay-swatches-product-custom-fixed-prices-simple .yay-swatches-fixed-price-checkbox-wrapper input {
  visibility: hidden;
}

.yay-swatches-product-custom-fixed-prices-simple .checkbox-sub-text {
  display: block;
  margin: 0 0 32px 12px;
}

.yay-swatches-product-custom-fixed-prices-simple .yay-swatches-fixed-prices-input-wrapper {
  position: relative;
  height: 310px;
}

.yay-swatches-product-custom-fixed-prices-simple .yay-swatches-fixed-prices-input {
  box-shadow: inset 4px 0 #ffc106;
}

.yay-swatches-product-custom-fixed-prices-simple .slider {
  background-color: #6ea8d6;
  border-radius: 100px;
  cursor: pointer;
  height: 20px;
  margin-left: -20px;
  position: relative;
  transition: 0.4s;
  width: 44px;
}

.yay-swatches-product-custom-fixed-prices-simple .round {
  background-color: #2271b1;
  border-radius: 50%;
  height: 16px;
  left: 2px;
  position: absolute;
  top: 2px;
  transition: 0.4s;
  transform: translateX(24px);
  width: 16px;
}
</style>
