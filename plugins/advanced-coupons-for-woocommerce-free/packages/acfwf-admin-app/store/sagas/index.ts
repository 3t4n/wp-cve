// #region [Imports] ===================================================================================================

// Libraries
import { all } from 'redux-saga/effects';

// Sagas
import * as section from './section';
import * as setting from './setting';
import * as storeCreditsDashboard from './storeCreditsDashboard';
import * as storeCreditsCustomers from './storeCreditsCustomers';
import * as dashboardWidgets from './dashboardWidgets';
import * as adminNotices from './adminNotices';
import * as couponTemplates from './couponTemplates';

// #endregion [Imports]

// #region [Root Saga] =================================================================================================

export default function* rootSaga() {
  yield all([
    ...section.actionListener,
    ...setting.actionListener,
    ...storeCreditsCustomers.actionListener,
    ...storeCreditsDashboard.actionListener,
    ...dashboardWidgets.actionListener,
    ...adminNotices.actionListener,
    ...couponTemplates.actionListener,
  ]);
}

// #endregion [Root Saga]
