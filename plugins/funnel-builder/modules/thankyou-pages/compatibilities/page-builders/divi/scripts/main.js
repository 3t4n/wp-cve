require('./divi.js');
import WFTY_Order_Details from "./order-details";
import WFTY_Customer_Details from "./customer-details";


(function ($) {
    $(window).on('et_builder_api_ready', (event, API) => {
        API.registerModules(
            [
                WFTY_Order_Details, WFTY_Customer_Details
            ]
        );
    });
})(jQuery);