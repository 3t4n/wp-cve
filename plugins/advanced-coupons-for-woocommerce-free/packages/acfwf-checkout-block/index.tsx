// Import scripts.
import initACFWFShared from '../shared';
import registerCouponSummaryPlugin from '../shared/plugins/CouponSummaryPlugin';
import registeNoticesPlugin from '../shared/plugins/NoticesPlugin';
import { initStoreCreditForm } from './store-credit';

// Import CSS.
import './index.scss';
import '../shared/components/index.scss';
import '../shared/wc-block/index.scss';

// Initiate scripts.
initACFWFShared(); // Shared Components.
registeNoticesPlugin();
registerCouponSummaryPlugin();
initStoreCreditForm();
