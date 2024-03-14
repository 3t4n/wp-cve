window.addEventListener("load", (event) => {
    if ( woo_data.wc_order_event_id && woo_data.order_total ) {
        fathom.trackGoal( woo_data.wc_order_event_id, woo_data.order_total );
    }
});