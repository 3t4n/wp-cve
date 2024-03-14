// #region [Imports] ===================================================================================================

// Libraries
import { createStore, combineReducers, applyMiddleware } from 'redux';
import createSagaMiddleware from 'redux-saga';

// Types
import { IStore } from '../types/store';

// Reducers
import sectionsReducer from './reducers/section';
import settingsReducer from './reducers/setting';
import pageReducer from './reducers/page';
import storeCreditsDashboardReducer from './reducers/storeCreditsDashboard';
import storeCreditsCustomersReducer from './reducers/storeCreditsCustomers';
import dashboardWidgetsReducer from './reducers/dashboardWidgets';
import adminNoticesReducer from './reducers/adminNotices';
import couponTemplatesReducer from './reducers/couponTemplates';

// Saga
import rootSaga from './sagas';

// #endregion [Imports]

// #region [Store] =====================================================================================================

/**
 * !Important
 * Comment this function out when releasing for production.
 */
const bindMiddleware = (middlewares: any[]) => {
  return applyMiddleware(...middlewares);
};

export default function initializeStore(initialState: IStore | undefined = undefined) {
  const sagaMiddleware = createSagaMiddleware();

  const store = createStore(
    combineReducers({
      sections: sectionsReducer,
      settingValues: settingsReducer,
      page: pageReducer,
      storeCreditsDashboard: storeCreditsDashboardReducer,
      storeCreditsCustomers: storeCreditsCustomersReducer,
      dashboardWidgets: dashboardWidgetsReducer,
      adminNotices: adminNoticesReducer,
      couponTemplates: couponTemplatesReducer,
    }),
    initialState as any, // Cast initialState to 'any' to bypass the type checking
    bindMiddleware([sagaMiddleware])
  );

  sagaMiddleware.run(rootSaga);

  return store;
}

// #endregion [Store]
