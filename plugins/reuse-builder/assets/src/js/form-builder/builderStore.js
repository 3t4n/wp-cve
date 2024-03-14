import { createStore, applyMiddleware, compose, combineReducers } from 'redux';
import thunk from 'redux-thunk';
// import { DevTools } from './form-builder-components/';
import * as reducers from './builder-modules/';

const reducer = combineReducers({ ...reducers });
const middleware = [thunk]; // add more middleware here
const createStoreWithMiddleware = compose(
  applyMiddleware(...middleware),
  // window.devToolsExtension ? window.devToolsExtension() : ''
)(createStore);

export default createStoreWithMiddleware(reducer);
