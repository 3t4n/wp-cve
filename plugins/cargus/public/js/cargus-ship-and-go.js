jQuery( function ($) {

    // ###############
    let assets_path;
    let DEFAULT_COORDINATES;
    let KEY_MAPPING_VALUES;
    let data_endpoint;
    // ###############

    assets_path = ajax_var_ship_and_go.assetsPath;

    DEFAULT_COORDINATES = {
        latitude: 44.442137062756885,
        longitude: 26.09464970813823,
    };

    // Can be either your server endpoint or the exact file location.

    data_endpoint = ajax_var_ship_and_go.pudoPoints;
    // As an example, consider that Latitude and Longitude are mapped with 'location_lat' and 'location_lon' instead.

    KEY_MAPPING_VALUES = {
        Latitude: "location_lat",
        Longitude: "location_lon",
    };

    // If the object is not modified, just set this to false:

    KEY_MAPPING_VALUES = false;

    // Place an event on the button to open the widget.

    const openCargusMapButton = document.querySelector("#cargus-open-map-btn");
    $( document ).on( "click", '#cargus-open-map-btn', function(e) {
        e.preventDefault();
        openCargusMap();
    });

    $( document ).on( "click", '#shipgomap-modal', function(e) {
        if (e.target !== this)
            return;
        closeModal();
    });

    function createLoadingScreen(targetElement) {
        const modal = document.createElement("div");
        modal.className = "cargus-loading-screen-modal";
        const loadingCircle = document.createElement("div");
        loadingCircle.className = "cargus-loading-circle";
        modal.appendChild(loadingCircle);
        targetElement.appendChild(modal);
    }

    const showLoadingScreen = function () {
        const targetElement = document.getElementById("cg-map-widget-container");
        if (targetElement) {
            const mapElement = document.querySelector(".cargus-map-widget #map");
            const sidebarElement = document.querySelector(".cargus-map-widget .sidebar");
            sidebarElement.style.opacity = "1";
            mapElement.style.opacity = "0.4";
            createLoadingScreen(targetElement);
        }
    }

    const hideLoadingScreen = function () {
        const modal = document.querySelector(".cargus-loading-screen-modal");
        if (modal) {
            const mapElement = document.querySelector(".cargus-map-widget #map");
            const sidebarElement = document.querySelector(".cargus-map-widget .sidebar");
            sidebarElement.style.opacity = "1";
            mapElement.style.opacity = "1";
            modal.remove();
        }
    }

    function ChooseMarker(selectedPoint) {
        // CUSTOM CODE HERE to handle the ID
        showLoadingScreen();
        $.ajax({
            type: "POST",
            url: ajax_var_ship_and_go.url,
            cache: true,
            data: {
                action: 'cargus_get_location_id',
                location_id: selectedPoint.Id,
                location_name: selectedPoint.Name,
                location_service_cod: selectedPoint.ServiceCOD,
                location_accepted_payment_type: selectedPoint.AcceptedPaymentType,
                security: ajax_var_ship_and_go.nonce,
                },
            success: function( data ){
                checkAndRenderContinue( data, selectedPoint );
                hideLoadingScreen();
                closeModal();
            },
            error: function( data ) {
                checkAndRenderContinue( data, selectedPoint );
            }
        });

        return true;
    }

    function closeModal() {
        const modal = $('#shipgomap-modal');
        // $('#shipgomap-modal').modal('toggle');
        if (modal) {
            modal.fadeOut();
            modal.empty();
        }
        return true;
    }

    const WidgetVarParams = {
        assets_path,
        DEFAULT_COORDINATES,
        KEY_MAPPING_VALUES,
        data_endpoint,
    };

    const WidgetFnParams = {
        ChooseMarker,
        closeModal,
    };

    function openCargusMap() {
        const modal = $('#shipgomap-modal');
        modal.fadeIn();
        modal.css('display', 'flex');
        initializeCargus("shipgomap-modal", WidgetFnParams, WidgetVarParams);
    }

    // shipping methods vars.
    const a = 'input[name^="shipping_method"]',
        b = a + ':checked',
        c = 'cargus_ship_and_go';

    // Utility function that show or hide the locations map button.
    const showOpenMapButton = function (){
        const selected_point_info = $( 'tr.cargus-ship-and-go-selected-point' );
        const selected_point_btn  = $( 'tr.cargus-ship-and-go-select' );
        
        // check what shipping method is selected.
        if( $(b).val() == c || $( 'input[type="hidden"]#shipping_method_0_cargus_ship_and_go' ).length ) {
            // display the open map button.
            selected_point_btn.removeClass( 'd-none' );

            // check if a location was already selected.
            if( $( 'input[name="location_name"]' ).val() ) {
                const location_name                  = $( 'input[name="location_name"]' ).val();

                // create and display the location info.
                createSelectedPointInfoHTML( selected_point_info, location_name );
            }
        } else {
            // hide the selected point info.
            selected_point_info.addClass('d-none');
            // hide the select pin button and alert.
            selected_point_btn.addClass( 'd-none' );
        }
    }

    // show/hide the cart shipping calculator and address.
    const toggleShippingCalculator = function () {
        let shippingDestination = $('.woocommerce-shipping-destination');
        let shippingCalculator = $('.woocommerce-shipping-calculator');
        if( $(b).val() == c || $( 'input[type="hidden"]#shipping_method_0_cargus_ship_and_go' ).length ) {
            if ( shippingDestination.length ) {
                setTimeout(
                    function(){ 
                        shippingDestination.addClass('d-none');
                    },
                    100
                );
            }

            if ( shippingCalculator.length ) {
                setTimeout(
                    function(){
                        shippingCalculator.addClass('d-none');
                    },
                    100
                );
            }
        } else {
            if ( shippingDestination.length ) {
                setTimeout(
                    function(){
                        shippingDestination.removeClass('d-none');
                    },
                    100
                );
            }

            if ( shippingCalculator.length ) {
                setTimeout(
                    function(){
                        shippingCalculator.removeClass('d-none');
                    },
                    100
                );
            }
        }
    }

    // show/hide the checkout shipping fields switch.
    const toggleShippingFields = function () {
        let shippingFields = $('.woocommerce-shipping-fields');
        if( $(b).val() == c || $( 'input[type="hidden"]#shipping_method_0_cargus_ship_and_go' ).length ) {

            if ( shippingFields.length ) {
                setTimeout(
                    function(){
                        $( '#ship-to-different-address-checkbox' ).prop('checked', false);
                        shippingFields.addClass('d-none');
                    },
                    100
                );
            }

        } else {
            if ( shippingFields.length ) {
                setTimeout(
                    function(){
                        shippingFields.removeClass('d-none');
                    },
                    100
                );
            }
        }
    }

    // make payment gateway disabled depending on which shipping method is selected.
    const disablePaymentGateway = function ( acceptedPaymentType ) {
        let cod       = $( '#payment_method_cod' );
        let shipAndGo = $( '#payment_method_cargus_ship_and_go_payment' );
        
        if( $(b).val() == c || $( 'input[type="hidden"]#shipping_method_0_cargus_ship_and_go' ).length ) {
            // if ship and go is selected disable and uncheck cash on delivery payment gateway.
            if ( cod.length ) {
                setTimeout(
                    function(){
                        cod.prop( 'disabled', true );
                    },
                    100
                );

                setTimeout(
                    function(){
                        cod.prop( 'checked', false );
                    },
                    100
                );
            }

            if ( shipAndGo.length ) {
                if ( 'true' === acceptedPaymentType["Online"] || true === acceptedPaymentType["Online"] ) {
                    setTimeout(
                        function(){
                            shipAndGo.prop( 'disabled', false );
                        },
                        100
                    );
                } else {
                    setTimeout(
                        function(){
                            shipAndGo.prop( 'disabled', true );
                        },
                        100
                    );

                    setTimeout(
                        function(){
                            shipAndGo.prop( 'checked', false );
                        },
                        100
                    );
                }
            }
        } else {
            // if ship and go is not selected disable and uncheck "Ramburs la Ship and Go" payment gateway.
            if ( cod.length ) {
                setTimeout(
                    function(){
                        cod.prop( 'disabled', false );
                    },
                    100
                );
            }
            if ( shipAndGo.length ) {
                setTimeout(
                    function(){
                        shipAndGo.prop( 'disabled', true );
                    },
                    100
                );

                setTimeout(
                    function(){
                        shipAndGo.prop( 'checked', false );
                    },
                    100
                );
            }
        }
    }

    // run the above functions when the screen loads and when a certain woocommerce action is triggered.
    $( document ).ready( function(){
        showOpenMapButton();
        toggleShippingCalculator();
        if ( $( 'input[name="location_accepted_payment_type"]' ).length ) {
            if ( $( 'input[name="location_accepted_payment_type"]' ).val() != '' ) {
                disablePaymentGateway( JSON.parse( $( 'input[name="location_accepted_payment_type"]' ).val() ) );
            } else {
                disablePaymentGateway( [] );
            }
        }
        toggleShippingFields();

        $( document.body ).on( 'updated_shipping_method', function(){
            toggleShippingCalculator()
            showOpenMapButton();
        });

        $( document.body ).on( 'updated_wc_div', function() {
            toggleShippingCalculator()
            showOpenMapButton();
        });

        $( document.body ).on( 'updated_checkout', function(){
            showOpenMapButton();
            toggleShippingFields();
            if ( $( 'input[name="location_accepted_payment_type"]' ).length ) {
                if ( $( 'input[name="location_accepted_payment_type"]' ).val() != '' ) {
                    disablePaymentGateway( JSON.parse( $( 'input[name="location_accepted_payment_type"]' ).val() ) );
                } else {
                    disablePaymentGateway( [] );
                }
            }
        });
    } );

    // display notices for when selecting a location.
    const checkAndRenderContinue = function ( data, location ) {
        let selected_point_info = $( 'tr.cargus-ship-and-go-selected-point' );
        if ( !data.includes('ERROR') ) {
            // create and display the location info.
            createSelectedPointInfoHTML( selected_point_info, location.Name );

            // change the selected point accepted payment method html.
            changeSelectedPointAcceptedPayment( $( 'input[name="location_accepted_payment_type"]' ), JSON.stringify( location.AcceptedPaymentType ) );

            // disable or enable the payment gateway.
            disablePaymentGateway( location.AcceptedPaymentType );
        }
    }

    // create the selected point info.
    const createSelectedPointInfoHTML = function ( selectedElement, locationName ) {
        $(selectedElement).removeClass( 'd-none' );
        $(selectedElement).html( `
            <th>Locatia aleasa</th>
            <td>${locationName}</td>
        ` );
    }

    const changeSelectedPointAcceptedPayment = function ( selectedElement, acceptedPaymentType ) {
        $( selectedElement ).val( acceptedPaymentType );
    }

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});
