import refundOrderAsStoreCreditsEvents from './refundOrderAsStoreCredits';
import applyStoreCreditsEvents from './applyStoreCredits';
import registerRefundStoreCreditsDiscountEvents from './refundStoreCreditsDiscount';

jQuery(document).ready(function ($) {
  refundOrderAsStoreCreditsEvents();
  applyStoreCreditsEvents();
  registerRefundStoreCreditsDiscountEvents();
});
