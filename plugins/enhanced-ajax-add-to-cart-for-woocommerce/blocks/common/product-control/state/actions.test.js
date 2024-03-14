
/*
// This file was brought from another project and will not work. It is for reference.
import '@babel/polyfill'
import configureMockStore from 'redux-mock-store';
import thunk from 'redux-thunk';
import promiseMiddleware from 'redux-promise-middleware';
import { connectStartNew, processOrders, requestOrders  } from './actions';
import expectExport from 'expect';
import { italic } from 'ansi-colors';

const middlewares = [thunk];
const mockStore = configureMockStore(middlewares);

describe("Order Actions", () => {
    let store;
    beforeEach(() => {
        store = mockStore({
            orders: [],
            inProgress: false,
            error: false,
            ordersCompleted: [],
        });
    });

    describe("requestOrders action creator", () => {
        it(" isnt working", () => {
            expect(true).toBe(true);
        });
    });
});
*/