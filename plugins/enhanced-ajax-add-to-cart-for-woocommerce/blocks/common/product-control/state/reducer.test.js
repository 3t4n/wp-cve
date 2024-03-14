/*
import orderReducer from './reducer';
import expectExport from 'expect';

// This file was brought from another project and will not work. It is for reference.

describe("order connector reducer", () => {
    let initialState = {
        orders: [],
        totalSteps: 0,
        stepSize: 5,
        inProgress: false,
        error: false,
        ordersCompleted: [],
    };

    it("returns the initial state correctly", () => {
        const reducer = orderReducer(undefined, {});
    
        expectExport(reducer).toEqual(initialState);
    });

    it("handles ORDERS_REQUESTED as expected", () => {
        const reducer = orderReducer(initialState, { type: "ORDERS_REQUESTED" });
    
        expect(reducer).toEqual({
            orders: [],
            inProgress: true,
            error: false,
            ordersCompleted: [],
            totalSteps: 0,
            stepSize: 5,
        });
    });

    it("handles ORDERS_RECEIVED as expected", () => {
        initialState.inProgress = true;
        const reducer = orderReducer(initialState, {
            type: "ORDERS_RECEIVED",
            payload: [ 1, 2, 3 ],
        });

        expect(reducer).toEqual({
            orders: [ 1, 2, 3 ],
            inProgress: true,
            error: false,
            ordersCompleted: [],
            totalSteps: 1,
            stepSize: 5,
        });
    });

    it("handles ORDER_PROCESSED as expected during execution", () => {
        initialState.orders = [ 1, 2, 3, 4, 5, 6 ];
        initialState.inProgress = true;
        initialState.totalSteps = 2;
        const reducer = orderReducer(initialState, {
            type: "ORDER_PROCESSED",
            payload: [ 1, 2, 3, 4, 5 ],
        });

        expect(reducer).toEqual({
            orders: [ 1, 2, 3, 4, 5, 6 ],
            inProgress: true,
            error: false,
            ordersCompleted: [ 1, 2, 3, 4, 5 ],
            totalSteps: 2,
            stepSize: 5,
        });
    });

    it("handles ORDER_PROCESSED as expected at end of execution", () => {
        initialState.orders = [ 1, 2, 3, 4, 5, 6, 7, 8 ];
        initialState.totalSteps = 2;
        initialState.inProgress = true;
        initialState.ordersCompleted = [ 1, 2, 3, 4, 5 ];
        const reducer = orderReducer(initialState, {
            type: "ORDER_PROCESSED",
            payload: [ 6, 7, 8 ],
        });

        expect(reducer).toEqual({
            orders: [ 1, 2, 3, 4, 5, 6, 7, 8 ],
            inProgress: false,
            error: false,
            ordersCompleted: [ 1, 2, 3, 4, 5, 6, 7, 8 ],
            totalSteps: 2,
            stepSize: 5,
        });
    });

})*/