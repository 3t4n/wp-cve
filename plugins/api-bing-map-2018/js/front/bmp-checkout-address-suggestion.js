function loadBmpMapSuggestion(){
  
    jQuery( function( bmp ){  

        var bmp_ck_elements = {
                country     : '#billing_country',
                address_one : '#billing_address_1',
                address_two : '#billing_address_2',
                city        : '#billing_city',
                state       : '#billing_state',
                postcode    : '#billing_postcode'
        };

        var bmp_sh_elements = {
                country     : '#shipping_country',
                address_one : '#shipping_address_1',
                address_two : '#shipping_address_2',
                city        : '#shipping_city',
                state       : '#shipping_state',
                postcode    : '#shipping_postcode'
        }


        let billing_address_1 = document.getElementById('billing_address_1');
        let shipping_address_1 = document.getElementById('shipping_address_1');

        if( billing_address_1 !== null ){
            let parent_billing_address_1 = billing_address_1.parentNode;          
            parent_billing_address_1.id = 'billing_address_1_container';           
        }

        if( shipping_address_1 !== null ){
            let parent_shipping_address_1 = shipping_address_1.parentNode;          
            parent_shipping_address_1.id = 'shipping_address_1_container';        
        }

        bingMapsReady();

        bmp_removeBingStyle();
        
        //--------------------------------------------------------------

        function bingMapsReady() {
            Microsoft.Maps.loadModule("Microsoft.Maps.AutoSuggest", {
                callback: onLoad,
                errorCallback: logError                       
            });
        
            function onLoad() {
                var options = { 
                    maxResults: 5,
                    placeSuggestions: false,
                    showBingLogo : false,
                    useMapView : false,
                    autoDetectLocation : false,
                    addressSuggestions : true ,
                    countryCode : bmp_restrict_suggest || '', 
                    countryFilter : bmp_restrict_suggest || '' 
                };
           
                initAutosuggestControl(options, "billing_address_1", "billing_address_1_container", 0);         
                initAutosuggestControl(options, "shipping_address_1", "shipping_address_1_container", 1);
            }
        }
        
        function initAutosuggestControl(
            options,
            suggestionBoxId,
            suggestionContainerId,
            type
        ) {
            var manager = new Microsoft.Maps.AutosuggestManager(options);
         
            manager.attachAutosuggest(
                "#" + suggestionBoxId,
                "#" + suggestionContainerId,
                selectedSuggestion
            );        
        
            function selectedSuggestion(suggestionResult) {
              
                if( typeof suggestionResult.address === 'undefined')
                    return;                               

                let country_short   = suggestionResult.address.countryRegionISO2 || '';   
                let address_one     = suggestionResult.address.addressLine || '';
                let city            = suggestionResult.address.locality || '';
                let postcode        = suggestionResult.address.postalCode || '';
                let admin_district  = suggestionResult.address.adminDistrict || suggestionResult.address.locality || '';

              
                let bmp_elements = bmp_ck_elements;

                if( type == 1 )
                    bmp_elements = bmp_sh_elements;
                
           
                if( country_short !== '' )                 
                    bmp( bmp_elements.country ).val( country_short ).trigger('change');  

                if( address_one !== '' )
                    bmp( bmp_elements.address_one ).val( address_one );  
                                        
                if( city !== '' )
                    bmp( bmp_elements.city ).val( city );

                if( postcode !== '')
                    bmp( bmp_elements.postcode ).val( postcode );
                
                if( admin_district !== '' ){
                    if( bmp( bmp_elements.state ).length > 0 ){                           
                    
                        bmp(bmp_elements.state + ' option').filter(function() {
                            return bmp(this).text() == admin_district;
                        }).prop("selected", true).trigger('change');
                    }
                }                    
               
                bmp_removeBingStyle();                    
                
            }
        }
                
        function logError(message) {
          
        }       

        function bmp_removeBingStyle(){
            setTimeout( function(){
                
                try {                   
                    bmp('#billing_address_1, #shipping_address_1').removeAttr('style');
                    bmp('head').append("<style> .MicrosoftMap ul{ list-style: none !important; } .MicrosoftMap ul li{ border-bottom: 1px solid #2ea3f2 } .MicrosoftMap .as_container .suggestLink{ padding: 8px 12px 0px 12px; } </style>");

                } catch (error) {
                    
                }
             
            }, 100);
        }
        
    }); 

}
