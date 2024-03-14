(function ($) {
    $(document).ready(function () {
        // code here
    });

    // receive post messages
    window.addEventListener('message', function (e) {
        if (e.data) {
            console.log("message received on bridge", e.data);
            let data = JSON.parse(e.data);

            if (data.oliverPHR) {
                oliverPRHDistinctEvents( data );
            }
        }
    }, false);

    // Send a message to the parent
    function oliverPRHSendMessage(msg) {
        window.parent.postMessage(msg, '*');
        console.log("message sent by bridge", msg);
    };

    function oliverPRHDistinctEvents(data) {
        let event = data.oliverPHR.event;

        if (event) {
            switch (event) {
                case 'iFrameDOMReady':
                    oliverPRHComponentScenarioData(data);
                    break;

                case 'optionSelected':
                    oliverPRHOptionSelected(data);
                    break;

                case 'variationOptionSelected':
                    oliverPRHVariationOptionSelected(data);
                    break;

                case 'componentItemQuantity':
                    oliverPRHComponentItemQuantity(data);
                    break;

                default:
                    break;
            }
        }
    }

    // HTML Rendering IframeDOM
    function oliverPRHOptionSelected( event ) {
        let selected_item = event.data.selected_item;
        let selected_option = event.data.selected_option;

        $( `select#${ selected_item }` ).val( selected_option ).change();
    }

    function oliverPRHVariationOptionSelected( event ) {
        let options_name  = event.data.options_name;
        let options_value = event.data.options_value;

        $( `select[name='${options_name}']` ).val( options_value ).change();
    }

    function oliverPRHComponentItemQuantity( event ) {
        let component_item_name  = event.data.component_item_name;
        let component_item_qty = event.data.component_item_qty;

        $( `input[name='${component_item_name}']` ).val( component_item_qty );
    }

    // Send a message to the parent when extension finsished
    function oliverPRHComponentScenarioData(event) {
        let composite_settings = $('div.cart.composite_data').data('composite_settings');
        let scenario_data       = $('div.cart.composite_data').data('scenario_data');

        let jsonMsg = {
            oliverPHRHTML: {
                "event": "oliverComponentScenarioData"
            },
            data: {
                "composite_settings":   composite_settings,
                "scenario_data":        scenario_data,
            }
        }

        oliverPRHSendMessage(JSON.stringify(jsonMsg));
    }

})(jQuery)