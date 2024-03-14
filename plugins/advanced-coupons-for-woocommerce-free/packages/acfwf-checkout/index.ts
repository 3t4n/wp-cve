import accordionEvents from './accordion';
import storeCreditEvents from './storeCredits';
import checkoutGenericEvents from './checkoutUtils';

// Import CSS.
import '../shared/wc-block/index.scss';
import './index.scss';

jQuery(function ($) {
  checkoutGenericEvents();
  accordionEvents();
  storeCreditEvents();
});
