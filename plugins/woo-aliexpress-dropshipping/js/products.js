(function (jQuery) {

    var quill,
        bulkCategories,
        // hostname = "https://wooshark.website",
        hostname = "https://thesharkdropship.com",

        imagesFromDescription = [],
        items = "",
        globalClientWebsite = "",
        globalClientKey = "",
        globalClientSecretKey = "",
        formsToSave = "",
        savedCategories = [],
        generalPreferences = items
            ? items.generalPreferences
            : {
                importSalePriceGeneral: !1,
                importDescriptionGeneral: !0,
                importReviewsGeneral: !0,
                importVariationsGeneral: !0,
                reviewsPerPage: 10,
                setMaximimProductStock: 0,
                importShippingCost: !1
            },
        images = [];
    const MAXIMUM_IMPORT = 60;
    function getImages(e) {
        return e;
    }
    function getItemSpecificfromTable(e, t) {
        var a = t,
            r = e.NameValueList.map(function (e) {
                return e.name;
            });
        return (
            a &&
            a.length &&
            a.forEach(function (t, a) {
                -1 == r.indexOf(t.attrName) &&
                    e.NameValueList.push({
                        name: t.attrName || "-",
                        visible: !0,
                        variation: !1,
                        value: [t.attrValue]
                    });
            }),
            e
        );
    }
    function getDescription(e, t) {
        fetch(
            "https://cors-anywhere.herokuapp.com/" +
            ("https://aeproductsourcesite.alicdn.com/product/description/pc/v2/en_EN/desc.htm?productId=" +
                e +
                "&key=Hf26e350fe48d45d3be4a05ec8e1ac9d2y.zip&token=4cc39c331004aa3153fe1623ffdc10c4")
        )
            .then(e => e.text())
            .then(e => {
                console.log("contents", response), t(e);
            })
            .catch(e => {
                t(!1);
            });
    }
    function getProductId(e) {
        var t = e.indexOf(".html");
        return e.substring(0, t).match(/\d+/)[0];
    }
    jQuery(document).on("click", "#goToExtension", function (e) {
        window.open("https://sharkdropship.com/aliexpress");
    }),
        jQuery(document).on("click", "#close-1", function (e) {
            jQuery("#section-1").hide();
        }),
        jQuery(document).on("click", "#close-2", function (e) {
            jQuery("#section-2").hide();
        });
    var currentSku = "";
    function importProductGlobally(e, t) {
        try {
            e &&
                ((currentSku = e),
                    jQuery(this).attr("disabled", !0),
                    jQuery(".importToS").each(function (e, t) {
                        console.log("----- disabling"), jQuery(t).attr("disabled", !0);
                    }),
                    startLoading(),
                    getProductDetailsFromServer(e, t));
        } catch (e) {
            jQuery(".importToS").each(function (e, t) {
                console.log("----- un - disabling 2"), jQuery(t).attr("disabled", !1);
            }),
                displayToast(
                    "cannot retrieve product id, please try again, if the issue persists, please contact wooebayimporter@gmail.com",
                    "red"
                ),
                stopLoading();
        }
    }
    function searchProducts(e) {
        jQuery("#pagination").empty(),
            jQuery("#pagination").show(),
            jQuery("#product-search-container").empty();
        var t = getSelectedLanguage();
        jQuery(".loader2").css({
            display: "block",
            position: "fixed",
            "z-index": 9999,
            top: "50px",
            right: "50px",
            "border-radius": "35px",
            "background-color": "red"
        }),
            searchByKeyWord(searchKeyword, t, e);
    }
    function searchByKeyWord(e, t, a) {
        let r = jQuery("#searchKeyword").val(),
            i = jQuery('input[name="sort"]:checked')[0]
                ? jQuery('input[name="sort"]:checked')[0].value
                : "",
            o = jQuery("#highQualityItems").prop("checked"),
            n = jQuery("#isFreeShipping").prop("checked"),
            l = jQuery("#isFastDelivery").prop("checked"),
            s = jQuery("#minPrice").val(),
            c = jQuery("#maxPrice").val(),
            d = getSelectedLanguage(),
            u = jQuery('input[name="currency"]:checked')[0]
                ? jQuery('input[name="currency"]:checked')[0].value
                : "";
        (xmlhttpAliExpress = new XMLHttpRequest()),
            (xmlhttpAliExpress.onreadystatechange = function () {
                if (4 == xmlhttpAliExpress.readyState)
                    if (200 === xmlhttpAliExpress.status)
                        try {
                            (data = JSON.parse(xmlhttpAliExpress.response).data),
                                console.log(data);
                            try {
                                var e = JSON.parse(data),
                                    t = e.result.products;
                                if (
                                    (t.forEach(function (e) {
                                        jQuery(
                                            '<div class="card text-center" style="flex: 1 1 20%; margin:10px;border-radius: 10px; padding:10px; box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">  <div class="card-body"><h5 class="card-title" style="font-weight:700"> ' +
                                            e.productTitle.substring(0, 70) +
                                            '</h5><img src="' +
                                            e.imageUrl +
                                            '" width="150"  height="150"></img><div>Sale Price: <p class="card-text" style="color:red">' +
                                            e.salePrice +
                                            '</div></p>Sku: <p class="card-text" id="sku" ">' +
                                            e.productId +
                                            '</p><div><div><a  style="width:80%; box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;" id="importToShop" class="importToS btn btn-primary">Import to shop</a></div><div><a id="productUrlByCard" target="_blank" style="width:80%; margin-top:5px" href="' +
                                            e.productUrl +
                                            '" class="btn btn-primary">Product url</a></div></div></div></div>'
                                        ).appendTo("#product-search-container");
                                    }),
                                        displayPAginationForSearchByKeyword(e.result.totalResults, a),
                                        jQuery(".loader2").css({ display: "none" }),
                                        t && t.length)
                                )
                                    getAlreadyImportedProducts(
                                        t.map(function (e) {
                                            return e.productId;
                                        })
                                    );
                            } catch (e) {
                                displayToast("Empty result for this search keyword", "red"),
                                    jQuery(".loader2").css({ display: "none" }),
                                    displayPAginationForSearchByKeyword(1e3, a);
                            }
                        } catch (e) {
                            jQuery(".loader2").css({ display: "none" }),
                                displayPAginationForSearchByKeyword(1e3, a);
                        }
                    else
                        displayToast(
                            "Error while getting results, please try again, if issue persist, please contact wooshark support ",
                            "red"
                        ),
                            jQuery(".loader2").css({ display: "none" }),
                            displayPAginationForSearchByKeyword(1e3, a);
            }),
            xmlhttpAliExpress.open(
                "POST",
                hostname + ":8002/searchAliExpressProductNewApi",
                !0
            ),
            xmlhttpAliExpress.setRequestHeader("Content-Type", "application/json"),
            xmlhttpAliExpress.send(
                JSON.stringify({
                    searchKeyword: r,
                    pageNo: a,
                    language: d,
                    sort: i,
                    highQualityItems: o,
                    currency: u,
                    isFreeShipping: n,
                    isFastDelivery: l,
                    priceMinMax: { min: s, max: c }
                })
            );
    }
    function save_options(e, t, a, r) { }
    function loadOrders() { }
    function getProductCountDraft() {
        jQuery.ajax({
            url: wooshark_params.ajaxurl,
            type: "POST",
            dataType: "JSON",
            data: { 
                nonce: wooshark_params.nonce,
                action: "getProductsCountDraft-alibay" },
            success: function (e) {
                let t = e;
                jQuery('.nav-item a[id="pills-draft-tab"]').html(
                    'Out of stock products <span class="badge badge-light">' + t + "</span>"
                );
            },
            error: function (e) {
                displayToast(e.responseText, "red"),
                    stopLoading();
            },
            complete: function () {
                stopLoading();
            }
        });
    }
    // function getProductsCount() {
    //     jQuery.ajax({
    //         url: wooshark_params.ajaxurl,
    //         type: "POST",
    //         dataType: "JSON",
    //         data: { action: "getProductsCount-alibay" },
    //         success: function (e) {
    //             console.log("----response", e),
    //                 displayPaginationSection((totalproductsCounts = e), 1);
    //         },
    //         error: function (e) {
    //             console.log("****err", e),
    //                 displayToast(e.responseText, "red"),
    //                 stopLoading();
    //         },
    //         complete: function () {
    //             console.log("SSMEerr"), stopLoading();
    //         }
    //     });
    // }


    function displayToastWithColor(e, t, a) {
        jQuery.toast({
            text: e,
            textColor: "black",
            icon: "red" == t ? "error" : "success",
            hideAfter: 8000,

            stack: 10,
            textAlign: "left",
            position: a ? "top-right" : "bottom-right",
            loaderBg: t
        });
    }
    function displayToast(e, t, a) {
        jQuery.toast({
            text: e,
            textColor: "black",
            hideAfter: 7e3,
            icon: "red" == t ? "error" : "success",
            stack: 5,
            textAlign: "left",
            position: a ? "top-right" : "bottom-right"
        });
    }
    function isNotConnected() {
        jQuery("#not-connected").show(), jQuery("#connected").hide();
    }
    jQuery(document).on("click", ".page-item", function (e) {
        jQuery("#product-pagination").empty(),
            jQuery("#product-pagination").show(),
            jQuery(".loader2").css({
                display: "block",
                position: "fixed",
                "z-index": 9999,
                top: "50px",
                right: "50px",
                "border-radius": "35px",
                "background-color": "green"
            });
        var t = 1;
        try {
            (t = parseInt(jQuery(this)[0].innerText)),
                displayPaginationSection(totalproductsCounts, t),
                searchProducts(t);
        } catch (e) {
            (t = 1),
                displayToast(
                    "error while index selection, please contact theShark, wooebayimporter@gmail.com",
                    "red"
                ),
                jQuery(".loader2").css({ display: "none" });
        }
    }),
        jQuery(document).on("click", ".etsy-page-item", function (e) {
            var t = 1;
            try {
                t = parseInt(jQuery(this)[0].innerText);
            } catch (e) {
                (t = 1),
                    displayToast(
                        "error while index selection, please contact theShark, wooebayimporter@gmail.com",
                        "red"
                    );
            }
            searchProductsEtsy(t);
        }),
        jQuery(document).on("click", "#seachProductsButton", function (e) {
            searchProducts(1);
        }),
        jQuery(document).on("click", "#discoverFeatures", function (e) {
            jQuery("#discoverFeatureContent").is(":hidden")
                ? jQuery("#discoverFeatureContent").show()
                : jQuery("#discoverFeatureContent").hide();
        }),
        jQuery(document).on("click", "#displayConnectToStore", function (e) {
            jQuery("#connect-to-store").is(":hidden")
                ? jQuery("#connect-to-store").show()
                : jQuery("#connect-to-store").hide();
        });

    let fullProductUrl = '';


    jQuery(document).on("click", "#importProductToShopByUrl", function (e) {
        var t = jQuery("#productUrl").val();
        fullProductUrl = t;
        globalUrlProduct = t;
        if (t) {
            var a = getProductId(t);
            prepareModal_aliexpress(),
                a
                    ? importProductGlobally(a)
                    : displayToast("Cannot get product sku", "red");
        }
    })

    // jQuery(document).on("click", "#importProductToShopByUrl", function (e) {
    //   jQuery("#asVariableAliex").show();
    //   var t = jQuery("#productUrl").val();
    //   fullProductUrl = t;
    //   if (globalUrlProduct = t, t) {
    //     var a = getProductId(t);
    //     prepareModal(), a ? importProductGlobally(a) : displayToast("Cannot get product sku", "red")
    //   }
    // })





    currentProductId = "";
    jQuery(document).on("click", "#insert-product-reviews", function (e) {
        currentProductId = jQuery(this).parents("tr")[0].cells[2].innerText;
        prepareReviewModal();
    });



    // jQuery('#pills-tab').on('shown.bs.tab', function (e) {
    //   if (e.target.id === 'pills-connect-products') {
    //     // Code to run when the "pills-products" tab is selected
    //     jQuery(".loaderImporttoShopProducts").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'))
    //     jQuery(".loaderImporttoShopProducts").show()


    // Add your custom code here
    //   }
    // });



    var index = 0;
    jQuery(document).on("click", "#addReview", function (e) {
        console.log("hndle ui-sortable-handle", jQuery(".wp-heading-inline")),
            e.preventDefault(),
            jQuery("#table-reviews tbody").append(
                '<tr><td style="width:65%"  contenteditable> <div id="editorReview' +
                index +
                '"> </div> </td><td contenteditable style="width:10%"> test@test.com </td></td><td contenteditable style="width:10%">' +
                getUsername() +
                '</td><td contenteditable style="width:10%">' +
                new Date().toISOString().slice(0, 10) +
                '</td></td><td style="width:5%"><input style="width:100%" type="number" min="1" max="5" value="5"></td><td><button class="btn btn-danger" id="removeReview">X</button></td></tr>'
            ),
            jQuery("#table-reviews tr td[contenteditable]").css({
                border: "1px solid #51a7e8",
                "box-shadow":
                    "inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)"
            });
    });
    let totalproductsCounts = 1;





    function startLoading(e) {
        e || (e = "loader2"),
            jQuery("." + e).css({
                display: "block",
                position: "fixed",
                "z-index": 9999,
                top: "50px",
                right: "50px",
                "border-radius": "35px",
                "background-color": "black"
            });

    }
    function stopLoading(e) {
        e || (e = "loader2"), jQuery("." + e).css({ display: "none" });
    }

    let currencyCode = 'USD';
    function getProductAliExpress(sku, url, callback) {
        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var responseData = JSON.parse(xhr.response);

                    if (responseData) {
                        callback(responseData.data);
                    }
                    jQuery('.update-product-button').find('.lds-ring').remove();

                } else {
                    var responseData = JSON.parse(xhr.response);
                    handleProductLoadError(responseData);
                    jQuery('.update-product-button').find('.lds-ring').remove();

                }
            }
        };

        var requestData = {

            currency: currencyCode && currencyCode != 'undefined' ? currencyCode : getCurrencyFromUrl(url),
            fullProductUrl: splitUrlUntilHtml(url),
            sku: sku,
            language: "",
            isBasicVariationsModuleUsedForModalDisplay: true,
            store: "",
        };

        sendRequestToInternalApi(requestData, xhr, callback);
    }
    

    function handleProductLoadError(responseData) {
        displayToast("Cannot load product details " + responseData.data, "red");
        callback(null);
    }

    function sendRequestToInternalApi(requestData, xhr, callback) {
        xhr.open("POST", hostname + ":8002/getProductDEtailsFRomOurInternalApi", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify(requestData));
    }



    function getUsername() {
        var e = names[Math.floor(Math.random() * names.length)],
            t = names.indexOf(e);
        return names.splice(t, 1), e;
    }



    copiedObject = "";




    jQuery(document).on("click", ".removeTag", function (e) {
        // Remove the parent div when the remove button is clicked
        jQuery(this).parent().remove();
    });




    jQuery(document).on("click", "#btn-close", function (e) {

    });






    let selectedFilters = {
        suppliers: [],
        product_types: []
    };



    loadProductsByFilters(1, selectedFilters); // Pass selectedFilters

    // jQuery(document).on("click", ".product-button", function (e) {
    //     jQuery(this).toggleClass('selected');
    //     var productType = jQuery(this).data('product-type');
    //     var productSupplier = jQuery(this).data('product-supplier');
    //     loadProductByTypeAndSupplier(productType, productSupplier, 1)
    // });

    // 


    // jQuery(document).on("click", "#aliexpress-button", function (e) {
    //     // jQuery(this).toggleClass('selected');
    //     loadAllProducts();
    // });
    //  jQuery(document).on("click", "#ebay-button", function (e) {
    //     loadAllProductsEbay();
    // });


    // jQuery(document).on("click", "#amazon-button", function (e) {
    //     loadAllProductsAmazon();
    // });

    // jQuery(document).on("click", "#etsy-button", function (e) {
    //     loadAllProductsEtsy();
    // });


    // Example of usage:
    // Assuming you have buttons with IDs "simple-button," "variable-button," and "external-button"
    // jQuery("#simple-button").click(function () {
    //     loadProductsByType("simple", 1); // Replace "simple" with the desired product type
    // });

    // jQuery("#variable-button").click(function () {
    //     loadProductsByType("variable", 1); // Replace "variable" with the desired product type
    // });

    // jQuery("#external-button").click(function () {
    //     loadProductsByType("external", 1); // Replace "external" with the desired product type
    // });


    // Initialize an empty selectedFilters object

    // Function to update the selected filters based on user interaction
    function updateSelectedFilters() {
        selectedFilters.suppliers = [];
        selectedFilters.product_types = [];

        // Loop through filter buttons and check if they are selected
        jQuery('.filter-button').each(function () {
            if (jQuery(this).hasClass('selected')) {
                var filterType = jQuery(this).data('filter');

                // Check if it's a supplier or product type filter
                if (filterType.startsWith('supplier-')) {
                    selectedFilters.suppliers.push(filterType.replace('supplier-', ''));
                } else if (filterType.startsWith('product-type-')) {
                    selectedFilters.product_types.push(filterType.replace('product-type-', ''));
                }
            }
        });
    }



    // Function to load products based on selected filters
    function loadProductsByFilters(page) {
        // Update selected filters
        updateSelectedFilters();
        showLoadingSpinner();
        // Make the AJAX request with selected filters
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                nonce: wooshark_params.nonce,
                action: 'get_aliexpress_products_alibay',
                page: page, // Change page number as needed
                suppliers: selectedFilters.suppliers, // Pass the selected suppliers
                product_types: selectedFilters.product_types, // Pass the selected product types,
            },
            success: function (response) {
                jQuery(".total_products").html('<span class="badge badge-pill badge-primary">' + response.total_products + '</span>')
                displayAliExpressProductsIntoTable(response);
                jQuery(".loaderImporttoShopProducts").remove();
                hideLoadingSpinner();
            },
            error: function (error) {
                console.error(error);
                jQuery(".loaderImporttoShopProducts").remove();
                hideLoadingSpinner();
            }
        });
    }

    // Event handler for filter button clicks
    jQuery('.filter-button').click(function () {
        // Toggle the "selected" class for the clicked filter button
        jQuery(this).toggleClass('selected');
        // Load products based on selected filters
        loadProductsByFilters(1); // Change page number as needed
    });




    // Click event handler for filter buttons
    // jQuery(document).on("click", ".filter-button", function (e) {
    //     var filter = jQuery(this).data('filter');
    //     jQuery(this).toggleClass('selected');

    //     // Toggle the filter
    //     toggleFilter(filter);

    //     // Perform actions based on selected filters (e.g., load products)
    //     loadAllProducts(1, selectedFilters); // Pass selectedFilters
    // });

    // Initialize an empty array to store selected filters

    // // Function to toggle filters in the selectedFilters array
    // function toggleFilter(filter) {
    //     var index = selectedFilters.indexOf(filter);
    //     if (index === -1) {
    //         // Filter not found, add it
    //         selectedFilters.push(filter);
    //     } else {
    //         // Filter found, remove it
    //         selectedFilters.splice(index, 1);
    //     }
    // }







    jQuery(document).on("click", "#searchBySku", function (e) {
        showLoadingSpinner();
        // jQuery(".loaderImporttoShopProducts").show()

        let t = jQuery("#skusearchValue").val();
        t
            ? jQuery.ajax({
                url: wooshark_params.ajaxurl,
                type: "POST",
                dataType: "JSON",
                data: {                 nonce: wooshark_params.nonce,
                    action: "search-product-by-sku-alibay", searchSkuValue: t },
                success: function (response) {
                    displayAllProductsIntoTable(response);
                    // jQuery(".loaderImporttoShopProducts").remove()
                    hideLoadingSpinner();

                },
                error: function (e) {
                    // jQuery(".loaderImporttoShopProducts").remove()
                    hideLoadingSpinner();

                },
                complete: function () {
                    // jQuery(".loaderImporttoShopProducts").remove()
                    hideLoadingSpinner();
                }
            })
            : loadProductsByFilters(1);
    });




    // Show the loading spinner
    function showLoadingSpinner() {
        jQuery('#loading-spinner').css('display', 'block');
        jQuery('.spinner').css('display', 'block');

    }

    // Hide the loading spinner
    function hideLoadingSpinner() {
        jQuery('#loading-spinner').css('display', 'none');
    }






    // display products 



    // function displayAllProductsIntoTableByType(response) {
    //     // jQuery(".loaderImporttoShopProducts").hide()
    //     // success: function(response) {
    //     // Handle the response data (e.g., display products)
    //     console.log(response);

    //     // Example: Display products in a table

    //     var totalPages = response.total_pages; // Get the total number of pages


    //     createProductTable(response);

    //     // Add pagination controls
    //     var pagination = jQuery('<ul class="pagination">');
    //     for (var i = 1; i <= totalPages; i++) {
    //         var listItem = jQuery('<li class="page-item-products-all-type"><a class="page-link page-link-all-type" href="#" data-page="' + i + '">' + i + '</a></li>');
    //         pagination.append(listItem);
    //     }
    //     jQuery('#pagination-container').empty().append(pagination);
    //     // },
    // }

    function displayAllProductsIntoTable(response) {
        // jQuery(".loaderImporttoShopProducts").hide()
        // success: function(response) {
        // Handle the response data (e.g., display products)
        console.log(response);

        // Example: Display products in a table

        var totalPages = response.total_pages; // Get the total number of pages


        createProductTable(response);

        // Add pagination controls
        var pagination = jQuery('<ul class="pagination">');
        for (var i = 1; i <= totalPages; i++) {
            var listItem = jQuery('<li class="page-item-products-all"><a class="page-link page-link-all" href="#" data-page="' + i + '">' + i + '</a></li>');
            pagination.append(listItem);
        }
        jQuery('#pagination-container').empty().append(pagination);
        // },
    }


    function displayAliExpressProductsIntoTable(response) {



        var totalPages = response.total_pages; // Get the total number of pages


        createProductTable(response);


        // Add pagination controls
        var pagination = jQuery('<ul class="pagination">');
        for (var i = 1; i <= totalPages; i++) {
            var listItem = jQuery('<li class="page-item-products-aliexpress"><a class="page-link page-link-aliexpress" href="#" data-page="' + i + '">' + i + '</a></li>');
            pagination.append(listItem);
        }
        jQuery('#pagination-container').empty().append(pagination);

    }

    jQuery(document).on("click", ".page-link-aliexpress", function (e) {
        jQuery(".loaderImporttoShopProducts").show()

        // $('#pagination-container').on('click', '.page-item-products', function(e) {
        e.preventDefault();

        // Get the clicked page number
        var page = jQuery(this).attr('data-page');

        // Load products for the selected page
        loadProductsByFilters(page, selectedFilters); // Pass selectedFilters

    });

    restoreConfiguration();


    function getEbaySiteId(url) {
        // Define a mapping from domain to site ID
            const siteIdMap = {
              'ebay.com': 0	,
              'ebay.ca': 2	,
              'ebay.co.uk': 3	,
              'ebay.com.au': 15	,
              'ebay.at': 16	,
              'ebay.be': 23	, // Assuming .be is for eBay Belgium in French
              'ebay.fr': 71,
              'ebay.de': 77,
              'ebay.it': 101	,
              'ebay.nl': 146	,
              'ebay.es': 186	,
              'ebay.ch': 193	,
              'ebay.com.hk': 201	,
              'ebay.ie': 205	,
              'ebay.com.my': 207	,
              'cafr.ebay.ca': 210	, // Assuming cafr.ebay.ca is for eBay Canada in French
              'ebay.ph': 211	,
              'ebay.pl': 212,
              'ebay.com.sg': 216	,
              // ... add other mappings as needed
            };
          
          
          
      
        try {
          // Create a URL object from the input URL
          const urlObj = new URL(url);
          // Extract the hostname from the URL
          const domain = urlObj.hostname.replace('www.', '');

          // Check if the domain is in the map
          if (domain in siteIdMap) {
            // Return the corresponding site ID
            return siteIdMap[domain];
          } else {
            // If the domain is not found, return a default value or throw an error
            return 0;

          }
        } catch (error) {
          // Handle any potential errors, such as invalid URL
         return 0;
        }
      
    }


    function getEbayProductDetails(productId,linkUrl, callback) {
        var selectedLanguageInput = "EBAY-US";
        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.response);
                    jQuery('.update-product-button').find('.lds-ring').remove();


                    callback(response);
                } else {
                    jQuery('.update-product-button').find('.lds-ring').remove();


                    var errorMessage =
                        "Error during eBay API call. Status: " + xhr.status;
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse && errorResponse.error[0].LongMessage) {
                            errorMessage += "\nMessage: " + errorResponse.error[0].LongMessage + " please try again";
                        }
                    } catch (error) {
                        // Parsing the response as JSON failed
                        errorMessage += "\nFailed to parse error response.";
                    }
                    console.error(errorMessage); // Log the error for debugging
                    displayToast(
                        errorMessage, "red"
                    );
                    callback(null);
                }
            }
        };

        xhr.open("POST", baseUrl + ":8008/getEbayVariationsNewApi", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.send(JSON.stringify({ productId: productId, globlId: selectedLanguageInput, siteId:getEbaySiteId(linkUrl), isPlugin: true }));
    }



    let baseUrl = "https://thesharkdropship.com";





    // function enableDisableConfirmUpdateButton() {
    //     jQuery('#regularPriceInput, #salePriceInput, #stockInput').on('input', function () {
    //         jQuery('#confirmUpdate').prop('disabled', !anyOptionFilled());
    //     });
    // }

    function anyOptionFilled() {
        var inputs = jQuery('#regularPriceInput, #salePriceInput, #stockInput');
        for (var i = 0; i < inputs.length; i++) {
            if (jQuery(inputs[i]).val()) {
                return true;
            }
        }
        return false;
    }



    function anyOptionFilled() {
        return (
            jQuery("#regularPriceInput").val().trim() !== "" ||
            jQuery("#salePriceInput").val().trim() !== "" ||
            jQuery("#stockInput").val().trim() !== ""
        );
    }

    let currentUpdatedProductId = '';

    function displayCommonDetailsForVariableProducts(productId) {
        updateType = 'variations';

        // Make an AJAX request to fetch WooCommerce variations for the current product
        jQuery.ajax({
            url: wooshark_params.ajaxurl,
            type: 'POST',
            dataType: 'JSON',
            data: {
                nonce: wooshark_params.nonce,

                action: 'load_woocommerce_variations_alibay', // Add an action to handle loading WooCommerce variations
                product_id: productId
            },
            success: function (response) {
                if ((response && response.data && response.data.variations) || (response.data && response.data.message && response.data.message == 'Variations not found.')) {
                    // Store WooCommerce variations in the array
                    woocommerceVariations = response.data.variations;

                    // Display common variations (based on SKU) in the modal
                    displayCommonVariations();
                }
            },
            error: function (error) {
                // Handle any errors if the loading fails
            }
        });
    }





    var woocommerceVariations = []; // Array to store WooCommerce variations


    function displayUpdateModal(productType, sku, productId, productNotFound) {
        // Define the inline template for the modal
        currentUpdatedProductId = sku;

        var modalTemplate = `
        <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProductModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" style="max-width:70vw; width:70vw">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="updateProductModalLabel">Update Product: ID: <span style="color:red"> ${productId} </span> Type: <span style="color:red"> ${productType} </span> SKU: <span style="color:red"> ${sku} </span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
      `;


        modalTemplate += `
                <div class="formulaContent" style=" width:80%; padding: 10px; text-align: center; color: #a3b5e3; font-size: 2rem;">  </div>
                <div class="variations-section" style="display:none; width:80%;margin-top:10px; padding: 10px; text-align: center; color: #a3b5e3; font-size: 2rem;"><div class="notification-variations" > </div> <button style="display:none" class="btn btn-primary display-uncommon-variations"> Load Missing variations</button>  </div>

                <div class="form-group">
                <div class="form-check" style="margin-top:8px">
                    <input type="checkbox" class="form-check-input"  checked id="updateRegularPrice">
                    <label class="form-check-label" for="updateRegularPrice">Update Regular Price</label>
                </div>
                <div class="form-check" style="margin-top:8px">
                    <input type="checkbox" class="form-check-input" checked id="updateSalePrice">
                    <label class="form-check-label" for="updateSalePrice">Update Sale Price</label>
                </div>
                <div class="form-check" style="margin-top:8px">
                    <input type="checkbox" class="form-check-input" checked id="updateStock">
                    <label class="form-check-label" for="updateStock">Update Stock</label>
                </div>
                </div>
            `;



        if (productType === 'simple') {
            // Display content for simple product type
            // Display content for variable product type (table with 3 columns)

            modalTemplate += `
        
              
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Sku</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Sale Price</th>
                            <th>Action</th> <!-- Add a new column for the delete button -->
              
                          </tr>
                        </thead>
                        <tbody id="variableProductTableBody">
                          <!-- Add rows for variable product data here -->
                        </tbody>
                      </table>
                    `;

        } else if (productType === 'external') {
            // if (productNotFound) {
            //     modalTemplate += `
            //     <div class="mb-3">
            //      'Product Not Found on supplier website, you can remove the product, or change its url
            //     </div>
            //   `;
            // } else {
                jQuery('#confirmUpdate').hide();
            modalTemplate += `
                <div class="mb-3">
                 Product still available in supplier website
                </div>
              `;
            // }
            // Display content for external product type

        } else if (productType === 'variable') {

            // Display content for variable product type (table with 3 columns)
            modalTemplate += `
        
        <!-- Regular Price Input -->
  <div style="dislay:flex; margin-top:10px">
    <input type="text" style="width: 30%; display: inline;" id="regularPriceInput" class="form-control" placeholder="Enter Regular Price">
    <button id="updateRegularPriceButton" style="width: 30%; display: inline; margin: 0;" class="btn btn-primary">Set All Regular Price</button>
  </div>
  
  <!-- Sale Price Input -->
  <div style="dislay:flex; margin-top:10px">
    <input type="text"  style="width: 30%; display: inline;"  id="salePriceInput" class="form-control" placeholder="Enter Sale Price">
    <button id="updateSalePriceButton" style="width: 30%; display: inline; margin: 0;" class="btn btn-primary">Set All Sale Price</button>
  </div>
  
  <!-- Stock Input -->
  <div style="dislay:flex; margin-top:10px">
    <input type="text"  style="width: 30%; display: inline;"  id="stockInput" class="form-control" placeholder="Enter Stock Quantity">
    <button id="updateStockButton" style="width: 30%; display: inline; margin: 0;" class="btn btn-primary">Set All Stock</button>
  </div>
  
  
  
          <table class="table">
            <thead>
              <tr>
                <th>Sku</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Sale Price</th>
                <th>Action</th> <!-- Add a new column for the delete button -->
  
              </tr>
            </thead>
            <tbody id="variableProductTableBody">
              <!-- Add rows for variable product data here -->
            </tbody>
          </table>
        `;
        }

        // Complete the modal template
        modalTemplate += `
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmUpdate">Confirm <span class="loaderconfirmUpdate" style="display:none"></span></button>
              </div>
            </div>
          </div>
        </div>
      `;

        // Append the modal template to the document
        jQuery('body').append(modalTemplate);

        // Initialize the Bootstrap modal
        var modal = new bootstrap.Modal(document.getElementById('updateProductModal'));
        modal.show();
        if (productType === 'simple') {
            displayCommonDetailsForSimpleProducts()
            jQuery(".formulaContent").text(formulaContent);

        } else if (productType === 'variable') {
            displayCommonDetailsForVariableProducts(productId);
            jQuery(".formulaContent").text(formulaContent);

        }else if(productType == 'external'){
            jQuery('#confirmUpdate').hide();
        }
    }

    jQuery(document).on("click", ".close-modal", function (e) {
        var modal = new bootstrap.Modal(document.getElementById('updateProductModal'));
        modal.hide();
    });



    jQuery(document).on('hidden.bs.modal', '#updateProductModal', function () {
        jQuery(this).remove()
        // Clear the input fields and reset the checkboxes
        jQuery("#regularPriceInput").val("");
        jQuery("#salePriceInput").val("");
        jQuery("#stockInput").val("");
        jQuery("#updateRegularPrice, #updateSalePrice, #updateStock").prop("checked", true);

        jQuery("#variableProductTableBody").empty();

        // You can also reset any other modal-related data or state here
    });



    function handleDeleteButtonClick(event) {
        event.preventDefault();
        // Find the closest row to the delete button
        var row = jQuery(this).closest('tr');
        // Remove the row from the table
        row.remove();
    }


    // Wait for the DOM to be ready
    // Your existing code for adding table rows

    // jQuery("#updateRegularPriceButton").on("click", function () {
    jQuery(document).on('click', '#updateRegularPriceButton', function () {

        var regularPriceValue = jQuery("#regularPriceInput").val();
        if (regularPriceValue !== "") {
            jQuery("#variableProductTableBody tr").each(function () {
                jQuery(this).find("td:nth-child(3) input").val(regularPriceValue);
            });
        }
    });

    // Function to update sale price for all rows
    // jQuery("#updateSalePriceButton").on("click", function () {
    jQuery(document).on('click', '#updateSalePriceButton', function () {

        var salePriceValue = jQuery("#salePriceInput").val();
        if (salePriceValue !== "") {
            jQuery("#variableProductTableBody tr").each(function () {
                jQuery(this).find("td:nth-child(4) input").val(salePriceValue);
            });
        }
    });

    // Function to update stock for all rows
    // jQuery("#updateStockButton").on("click", function () {
    jQuery(document).on('click', '#updateStockButton', function () {

        var stockValue = jQuery("#stockInput").val();
        if (stockValue !== "") {
            jQuery("#variableProductTableBody tr").each(function () {
                jQuery(this).find("td:nth-child(2) input").val(stockValue);
            });
        }
    });



    // Attach click event handler to delete buttons
    // jQuery(document).on('click', '.delete-button', handleDeleteButtonClick);

    function createProductTable(response) {
        var productsTable = jQuery('<table class="table">');
        productsTable.append('<thead><tr><th>Image</th><th>SKU</th><th>ID</th><th>Status</th><th>Supplier</th><th>Link</th><th>Edit Product url</th><th>Edit Product in WordPress</th><th>Preview Product</th><th>Sync product </th><th>Delete </th><th>Product Type</th><th>Last updated date</th></tr></thead>');
        var tbody = jQuery('<tbody>');

        if (response.products.length > 0) {
            jQuery.each(response.products, function (index, product) {
                var productRow = jQuery('<tr>');
                productRow.append('<td><img src="' + product.image + '" alt="' + product.title + '" width="50" height="50"></td>');
                productRow.append('<td id="product-sku">' + product.sku + '</td>');
                productRow.append('<td id="product-id">' + product.id + '</td>');

                if (product.status === 'draft') {
                    productRow.append('<td><span class="badge badge-secondary">' + product.status + '</span></td>');
                } else if (product.status === 'publish') {
                    productRow.append('<td><span class="badge badge-success">' + product.status + '</span></td>');
                } else {
                    productRow.append('<td>' + product.status + '</td>');
                }


                let supplietName = '';
                if (product.productUrl.includes('aliexpress.')) {
                    supplietName = 'AliExpress'; // Change the class for 'simple' product type
                    productRow.append('<td data-supplier-na,e="' + supplietName + '"><span class="badge badge-success">' + supplietName + '</span></td>');

                } else if (product.productUrl.includes('ebay.')) {

                    supplietName = 'eBay'; // Change the class for 'simple' product type
                    productRow.append('<td data-supplier-na,e="' + supplietName + '"><span class="badge badge-secondary">' + supplietName + '</span></td>');

                } else if (product.productUrl.includes('etsy.')) {

                    supplietName = 'Etsy'; // Change the class for 'simple' product type
                    productRow.append('<td data-supplier-na,e="' + supplietName + '"><span class="badge badge-warning">' + supplietName + '</span></td>');

                }
                else if (product.productUrl.includes('amazon.')) {

                    supplietName = 'Amazon'; // Change the class for 'simple' product type
                    productRow.append('<td data-supplier-na,e="' + supplietName + '"><span class="badge badge-danger">' + supplietName + '</span></td>');

                }else{
                    supplietName = 'unknown'
                    productRow.append('<td data-supplier-na,e="' + supplietName + '"><span class="badge badge-danger">' + supplietName + '</span></td>');

                }



                var productTypeClass = '';
                if (product.productType === 'simple') {
                    productTypeClass = 'badge-primary'; // Change the class for 'simple' product type
                } else if (product.productType === 'variable') {
                    productTypeClass = 'badge-warning'; // Change the class for 'variable' product type
                } else if (product.productType === 'external') {
                    productTypeClass = 'badge-success'; // Change the class for 'grouped' product type
                }

                productRow.append('<td data-currency-code="'+product.selectedCurrency+'" data-product-url="' + product.productUrl + '"><a target="_blank" class="btn btn-primary" href="' + product.productUrl + '">Original url</a></td>');
                productRow.append('<td data-product-id = ' + product.id + ' data-product-url="' + product.productUrl + '"><button class="update-url-button btn btn-info" data-product-id="' + product.id + '" data-toggle="modal" data-target="#updateUrlModal">Update URL</button></td>');

                productRow.append('<td><a target=”_blank” class="btn btn-primary" href="' + product.permalink + '" target="_blank"> Edit in wordpress </a></td>');
                productRow.append('<td><a target=”_blank” class="btn btn-primary" href="' + product.permalink_preview + '" target="_blank"> preview Product</a></td>');
                // productRow.append('<td><button class="update-product-button btn btn-primary" data-product-id="' + product.id + '">Update <span class="loaderconfirmUpdateProductList" style="display:none"></span></button></td>');
                productRow.append('<td><button class="update-product-button btn ' + productTypeClass + '" data-product-id="' + product.id + '">Update (' + product.productType + ') <span class="loaderconfirmUpdateProductList" style="display:none"></span></button></td>');

                // productRow.append('<td><button class="set-to-draft btn btn-primary" data-product-id="' + product.id + '">Set to draft  <div class="loadeSetToDraft" style="display:none"></div> </button></td>');
                productRow.append('<td><button class="delete-product btn btn-danger" data-product-id="' + product.id + '">Delete  <div class="deleteProductLoader" style="display:none"></div> </button></td>');


                productRow.append('<td data-product-type="' + product.productType + '"><span  class="badge ' + productTypeClass + '">' + product.productType + ' (' + product.variationCount + ')' + '</span></td>');

                productRow.append('<td><span class="last-updated-date"> ' + product.lastUpdatedDate + '</span></td>');


                tbody.append(productRow);
                productsTable.append(tbody);
                jQuery('#products-wooshark').empty().append(productsTable);

            });
        } else {
            jQuery('table.table tr').empty();

            tbody.append('<tr><td colspan="7">No products found.</td></tr>');
        }
    }





    jQuery(document).on("click", ".delete-product", function (e) {


        var deleteButton = jQuery(this);

        // Display loader
        deleteButton.find('.deleteProductLoader').html('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>');
        deleteButton.find('.deleteProductLoader').show();

        var productId = deleteButton.data('product-id');

        // jQuery('.deleteProductLoader').find('.lds-ring').remove();

        // // Add the loader to the clicked button
        // var loader = '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>';
        // jQuery(this).find('.deleteProductLoader').append(loader);
        // jQuery(this).find('.deleteProductLoader').show();


        var productId = jQuery(this).data('product-id');

        // Send an AJAX request to set the product status to "draft"
        // function deleteProduct(productId) {
        // You can make an AJAX request to the server to delete the product
        // Example AJAX request
        jQuery.ajax({
            url: wooshark_params.ajaxurl,
            type: 'POST',
            dataType: 'JSON',
            data: {
                nonce: wooshark_params.nonce,

                action: 'delete_product_callback_alibay',
                product_id: productId
            },
            success: function (response) {
                jQuery('.lds-ring').remove();
                displayToast('Product deleted successfully.', 'green')
                //remove current row

                deleteButton.closest('tr').remove();
            },
            error: function (error) {
                jQuery('.lds-ring').remove();
                displayToast('Failed to delete product.', 'red')

            }
        });
        // }

    });

    // Click event handler for "Set to Draft" buttons
    jQuery(document).on("click", ".set-to-draft", function (e) {



        jQuery('.loadeSetToDraft').find('.lds-ring').remove();

        // Add the loader to the clicked button
        var loader = '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>';
        jQuery(this).find('.loadeSetToDraft').append(loader);
        jQuery(this).find('.loadeSetToDraft').show();


        var productId = jQuery(this).data('product-id');

        // Send an AJAX request to set the product status to "draft"
        jQuery.ajax({
            url: wooshark_params.ajaxurl,
            type: 'POST',
            dataType: 'JSON',
            data: {
                nonce: wooshark_params.nonce,

                action: 'set_product_to_draft_callback_alibay',
                product_id: productId
            },
            success: function (response) {
                jQuery('.loadeSetToDraft div.lds-ring').remove();

                if (response.success) {
                    // Update the product status in the table
                    var productStatusCell = jQuery('#product-status-' + productId);
                    productStatusCell.empty().append('<span class="badge badge-secondary">draft</span>');
                } else {
                    // Handle the error if needed
                    console.error('Error setting product to draft:', response.message);
                }
            },
            error: function (xhr, status, error) {
                jQuery('.loadeSetToDraft').find('.lds-ring').remove();

                // Handle AJAX error if needed
                console.error('AJAX error:', error);
            }
        });
    });




    // Add an event listener for the "Confirm" button
    jQuery(document).on('click', '#confirmUpdate', function () {
        jQuery(".loaderconfirmUpdate").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'))
        jQuery(".loaderconfirmUpdate").show()

        // Check the status of the checkboxes
        var updateRegularPrice = jQuery('#updateRegularPrice').prop('checked');
        var updateSalePrice = jQuery('#updateSalePrice').prop('checked');
        var updateStock = jQuery('#updateStock').prop('checked');

        // Create an array to store the variations to be updated
        var variationsToUpdate = [];

        // Loop through the rows in the variable product table
        jQuery('#variableProductTableBody tr').each(function () {
            var row = jQuery(this);
            var sku = row.find('input:eq(0)').val();
            var quantity = row.find('input:eq(1)').val();
            var price = row.find('input:eq(2)').val();
            var salePrice = row.find('input:eq(3)').val();

            // Create an object to represent the variation
            var variation = {
                sku: sku

            };

            // Check if the "Update Sale Price" checkbox is selected
            if (updateSalePrice) {
                variation.salePrice = salePrice;
            }

            // Check if the "Update Regular Price" checkbox is selected
            if (updateRegularPrice) {
                variation.regularPrice = price;
            }

            // Check if the "Update Stock" checkbox is selected
            if (updateStock) {
                variation.quantity = quantity;
            }

            // Add the variation to the array if any field needs updating
            if (
                updateRegularPrice ||
                updateSalePrice ||
                updateStock
            ) {
                variationsToUpdate.push(variation);
            }
        });

        // Send an AJAX request to update the variations on WooCommerce
        jQuery.ajax({
            url: wooshark_params.ajaxurl,
            type: 'POST',
            dataType: 'JSON',
            data: {
                nonce: wooshark_params.nonce,

                action: 'update_variations_on_woocommerce_alibay',
                variations: variationsToUpdate,
                parentId: currentUpdatedProductId,
                updateType: updateType,
            },
            success: function (response) {
                if (response.success) {
                    // alert('Variations updated successfully.');
                    displayToast('Variations updated successfully.', 'green')
                    stopLoadingSpinner();

                    // Close the modal or perform any other action
                } else {
                    if(response && response.data && response.data.message){

                        displayToast('Failed to update variations.' + response.data.message, 'red');

                    }else{
                        displayToast('Failed to update variations.', 'red');
                    }

                    // alert('Failed to update variations.');
                    stopLoadingSpinner();

                }
            },
            error: function () {
                alert('AJAX request failed.');
                displayToast('Failed to update variations.', 'red');

            },
        });
    });
    function splitUrlUntilHtml(url) {
        // Use a regular expression to match everything until the ".html" part
        const match = url.match(/^(.*?\.html)/);
    
        
        if (match && match[1]) {
            return match[1].replace('aliexpress.us/item/', 'aliexpress.com/item/')
        } else {
          displayToast("Cannot load product details, please try again", "red");
           return url;
        }
    }
  

    // function fetchDataFromEbay(url, callback) {
    //     jQuery.ajax({
    //         url: wooshark_params.ajaxurl,
    //         type: 'POST',
    //         dataType: 'json',
    //         data: {
    //             action: 'fetchemote_alibay', // WordPress AJAX action name,
    //             url: url
    //         },
    //         success: function (response) {
    //             if (response.success) {
    //                 // Handle the parsed data here
    //                 var message = response.data.message;
    //                 var statusCode = response.data.status_code;

    //                 // Check if the product exists or not based on the status code
    //                 if (statusCode === '488') {
    //                     callback(true)
    //                     // Handle the case where the product does not exist
    //                 } else if (statusCode === '200') {
    //                     callback(false)

    //                     // Handle the case where the product exists
    //                 } else {
    //                     callback(false)
    //                 }
    //                 jQuery('.update-product-button').find('.lds-ring').remove();

    //             } else {
    //                 console.error('Error:', response.data.message);
    //                 jQuery('.update-product-button').find('.lds-ring').remove();

    //             }
    //         },
    //         error: function (error) {
    //             console.error('AJAX Error:', error);
    //             Query('.update-product-button').find('.lds-ring').remove();

    //         }
    //     });
    // }


    // function parseHTML(htmlString) {
    //     // Create a temporary DOM element to parse the HTML
    //     var tempDiv = document.createElement('div');
    //     tempDiv.innerHTML = htmlString;

    //     // Use jQuery or JavaScript DOM methods to extract data from the HTML
    //     var productTitle = jQuery(tempDiv).find('.product-title').text(); // Example: If there's a class "product-title" containing the title

    //     // Return the parsed data as an object or perform further processing
    //     return {
    //         title: productTitle
    //         // Add more properties as needed
    //     };
    // }

    // function getProductIdFromEbayUrl(ebayUrl) {
    //     try {
    //       // Remove any trailing slashes and query parameters
    //       const cleanedUrl = ebayUrl.replace(/[?#].*$/, '');

    //       // Split the URL by slashes
    //       const urlParts = cleanedUrl.split('/');

    //       // Find the last part that is a numeric value (the product ID)
    //       for (let i = urlParts.length - 1; i >= 0; i--) {
    //         const part = urlParts[i];
    //         if (/^\d+$/.test(part)) {
    //           return part;
    //         }
    //       }

    //       // If no numeric part is found, return an empty string
    //       displayToast('Failed to get product id from url', 'red');

    //     } catch (error) {
    //      displayToast('Failed to get product id from url, please contact support', 'red');
    //       return '';
    //     }
    //   }





    // function getProductIdFRomUrl_ebay(ebayUrl) {
    //     var productIdMatch = ebayUrl.match(/\/(\d+)(?:$|\/)/);

    //     // var productIdMatch = ebayUrl.match(/\/(\d+)\?/);

    //     if (productIdMatch && productIdMatch[1]) {
    //         var productId = productIdMatch[1];
    //         return productId;
    //     } else {
    //         return '';
    //     }


    // }

    function getProductIdFromEbayUrl(ebayUrl) {
        // Use a regular expression to extract the product ID from the URL
        var productIdMatch = ebayUrl.match(/\/(\d+)(?:\?|\/|$)/);

        if (productIdMatch && productIdMatch[1]) {
            var productId = productIdMatch[1];
            return productId;
        } else {
            displayToast('Failed to get product id from url', 'red');
        }
    }

    // Test with the provided eBay URLs



    function stopLoadingSpinner() {
        jQuery('.lds-ring').remove();
    }


    function getProductIdFromUrl(e) {
        var t = e.indexOf(".html");
        return e.substring(0, t).match(/\d+/)[0];

    }


    function getCurrencyFromUrl(url) {

        const countryToCurrency = {
            us: "USD",  // United States - U.S. Dollar
            pt: "EUR",  // Portugal - Euro
            uk: "GBP",  // United Kingdom - British Pound
            fr: "EUR",  // France - Euro
            de: "EUR",  // Germany - Euro
            es: "EUR",  // Spain - Euro
            it: "EUR",  // Italy - Euro
            ru: "RUB",  // Russia - Russian Ruble
            br: "BRL",  // Brazil - Brazilian Real
            au: "AUD",  // Australia - Australian Dollar
            ca: "CAD",  // Canada - Canadian Dollar
            jp: "JPY",  // Japan - Japanese Yen
            cn: "CNY",  // China - Chinese Yuan
            in: "INR",  // India - Indian Rupee
            mx: "MXN",  // Mexico - Mexican Peso
            ar: "ARS",  // Argentina - Argentine Peso
            ch: "CHF",  // Switzerland - Swiss Franc
            se: "SEK",  // Sweden - Swedish Krona
            no: "NOK",  // Norway - Norwegian Krone
            dk: "DKK",  // Denmark - Danish Krone
            nz: "NZD",  // New Zealand - New Zealand Dollar
            sg: "SGD",  // Singapore - Singapore Dollar
            my: "MYR",  // Malaysia - Malaysian Ringgit
            th: "THB",  // Thailand - Thai Baht
            id: "IDR",  // Indonesia - Indonesian Rupiah
            tr: "TRY",  // Turkey - Turkish Lira
            za: "ZAR",  // South Africa - South African Rand
            sa: "SAR",  // Saudi Arabia - Saudi Riyal
            ae: "AED",  // United Arab Emirates - UAE Dirham
            hk: "HKD",  // Hong Kong - Hong Kong Dollar
            tw: "TWD",  // Taiwan - New Taiwan Dollar
            kr: "KRW",  // South Korea - South Korean Won
            sg: "SGD",  // Singapore - Singapore Dollar
            ph: "PHP",  // Philippines - Philippine Peso
            vn: "VND",  // Vietnam - Vietnamese Dong
            pl: "PLN",  // Poland - Polish Złoty
            ua: "UAH",  // Ukraine - Ukrainian Hryvnia
            kz: "KZT",  // Kazakhstan - Kazakhstani Tenge
            co: "COP",  // Colombia - Colombian Peso
            pe: "PEN",  // Peru - Peruvian Nuevo Sol
            ve: "VES",  // Venezuela - Venezuelan Bolívar
            cl: "CLP",  // Chile - Chilean Peso
            ec: "USD",  // Ecuador - U.S. Dollar
            bo: "BOB",  // Bolivia - Bolivian Boliviano
            py: "PYG",  // Paraguay - Paraguayan Guarani
            uy: "UYU",  // Uruguay - Uruguayan Peso
            do: "DOP",  // Dominican Republic - Dominican Peso
            gt: "GTQ",  // Guatemala - Guatemalan Quetzal
            hn: "HNL",  // Honduras - Honduran Lempira
            ni: "NIO",  // Nicaragua - Nicaraguan Córdoba
            cr: "CRC",  // Costa Rica - Costa Rican Colón
            sv: "USD",  // El Salvador - U.S. Dollar
            pa: "USD",  // Panama - U.S. Dollar
            bz: "BZD",  // Belize - Belize Dollar
            jm: "JMD",  // Jamaica - Jamaican Dollar
            tt: "TTD",  // Trinidad and Tobago - Trinidad and Tobago Dollar
            bs: "BSD",  // The Bahamas - Bahamian Dollar
            bb: "BBD",  // Barbados - Barbadian Dollar
            gd: "XCD",  // Grenada - East Caribbean Dollar
            ag: "XCD",  // Antigua and Barbuda - East Caribbean Dollar
            lc: "XCD",  // Saint Lucia - East Caribbean Dollar
            vc: "XCD",  // Saint Vincent and the Grenadines - East Caribbean Dollar
            kn: "XCD",  // Saint Kitts and Nevis - East Caribbean Dollar
            dm: "XCD",  // Dominica - East Caribbean Dollar
            cu: "CUP",  // Cuba - Cuban Peso
            ht: "HTG",  // Haiti - Haitian Gourde
            gp: "EUR",  // Guadeloupe - Euro
            mq: "EUR",  // Martinique - Euro
            re: "EUR",  // Réunion - Euro
            gf: "EUR",  // French Guiana - Euro
            yt: "EUR",  // Mayotte - Euro
            pf: "XPF",  // French Polynesia - CFP Franc
            nc: "XPF",  // New Caledonia - CFP Franc
            wf: "XPF",  // Wallis and Futuna - CFP Franc
            pf: "XPF"  // French Southern and Antarctic Lands - CFP Franc
            // Add more country codes and currencies as needed
        };

        // Add more country codes and currencies as needed


        // Extract the country code from the URL
        const countryMatch = url.match(/https:\/\/([a-z]{2})\./i);
        if (countryMatch && countryMatch[1]) {
            const countryCode = countryMatch[1].toLowerCase();

            // Check if the country code exists in the mapping
            if (countryToCurrency.hasOwnProperty(countryCode)) {
                return countryToCurrency[countryCode];

            } else {
                return 'USD'
            }
        } else {
            return 'USD'
        }
    }

    var variationDetails = [];




    jQuery(document).on('click', '.update-product-button', function () {
        variationDetails = [];
        let button = jQuery(this);
        let clickedRow = button.closest('tr');
        // let sku = clickedRow.find('#product-sku').text();
        let productId = clickedRow.find('#product-id').text();
        let productType = clickedRow.find('td[data-product-type]').data('product-type');
        let linkUrl = clickedRow.find('td[data-product-url]').data('product-url');
        let productCurrencyCode = clickedRow.find('td[data-currency-code]').data('currency-code');
        if(productCurrencyCode){
            currencyCode = productCurrencyCode;
        }

        button.find('.lds-ring').remove();
        button.append('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>');
        let sku = "";

        
        if (linkUrl.includes('.ebay.')) {
            sku = getProductIdFromEbayUrl(linkUrl);

            getEbayProductDetails(sku,linkUrl, function (response) {
                if (!response) {
                    displayToast('Product update not supported yet, we are working on that');
                    return;
                }

                if (response.variations && response.variations.Variation) {
                    response.variations.Variation.forEach(function (variation) {
                        const sku = variation.SKU;
                        const quantitySold = variation.SellingStatus ? variation.SellingStatus.QuantitySold : 0;
                        const availableQuantity = variation.Quantity - quantitySold;
                        const quantity = availableQuantity;
                        const price = variation.StartPrice.Value;

                        const variationData = {
                            sku: sku,
                            quantity: quantity,
                            price: savedFormula && savedFormula.length ? calculateAppliedPrice(price) : price,
                            salePrice: savedFormula && savedFormula.length ? calculateAppliedPrice(price) : price
                        };

                        variationDetails.push(variationData);
                    });
                } else if (!response.variations && response.title) {
                    const variationData = {
                        sku: productId,
                        quantity: response.quantity,
                        price: savedFormula && savedFormula.length ? calculateAppliedPrice(response.currentPrice) : response.currentPrice,
                        salePrice: savedFormula && savedFormula.length ? calculateAppliedPrice(response.salePrice) : response.salePrice
                    };

                    variationDetails.push(variationData);
                } else {
                    displayToast('Product update not supported yet, we are working on that');
                    return;
                }

                // Display the modal with common variations
                displayUpdateModal(productType, sku, productId, variationDetails);
            });
        }
        else if (linkUrl.includes('.aliexpress.')) {
            sku = getProductIdFromUrl(linkUrl);

            getProductAliExpress(sku, linkUrl, function (jsonData) {
                if (jsonData) {

                    var skuPriceList = jsonData.skuModule.skuPriceList;
                    if (skuPriceList && skuPriceList.length > 0) {
                        skuPriceList.forEach(function (skuPriceItem) {
                            var skuVal = skuPriceItem.skuVal;
                            var skuAmount = skuVal.skuAmount;

                            var sku = skuPriceItem.skuIdStr;
                            var quantity = skuVal.availQuantity;
                            var price = skuAmount.value;
                            let salePrice = skuVal.skuActivityAmount ? skuVal.skuActivityAmount.value : skuAmount.value;
                            var variationData = {
                                sku: sku,
                                quantity: quantity,
                                price: savedFormula && savedFormula.length ? calculateAppliedPrice(price) : price,
                                salePrice: savedFormula && savedFormula.length ? calculateAppliedPrice(salePrice) : salePrice
                            };

                            variationDetails.push(variationData);
                        });
                    }

                    // Display the modal with common variations
                    displayUpdateModal(productType, sku, productId, variationDetails);
                }
            });
        } else
            if (linkUrl.includes('.etsy.')) {
                sku = getProductIdFromUrlEtsy(linkUrl);

                getEtsyProductDetails(sku, function (data) {
                    if (!data) {
                        displayToast('Error Etsy TYPE 2001, please contact support team');
                        return;
                    }

                    const products = data.variations && data.variations.products;

                    if (products && products.length > 1) {
                        products.forEach(function (product) {
                            const sku = product.product_id;
                            let price = 1;

                            if (product.offerings && product.offerings.length > 0) {
                                const offering = product.offerings[0];
                                if (offering.price.amount) {
                                    price = (offering.price.amount / (offering.price.divisor || 1)).toFixed(2);
                                }
                            }

                            const quantity = product.offerings && product.offerings[0] ? product.offerings[0].quantity : 0;

                            const variationData = {
                                sku: sku.toString(),
                                quantity: quantity,
                                price: savedFormula && savedFormula.length ? calculateAppliedPrice(price) : price,
                                salePrice: savedFormula && savedFormula.length ? calculateAppliedPrice(price) : price,
                            };

                            variationDetails.push(variationData);
                        });
                    } else if (products.length === 1 && data.title) {
                        const itemPrice = data.price || 0;
                        const divisor = data.divisor || 1;
                        const price = (itemPrice / divisor).toFixed(2);

                        const variationData = {
                            sku: productId,
                            quantity: data.quantity || 0,
                            price: savedFormula && savedFormula.length ? calculateAppliedPrice(price) : price,
                            salePrice: savedFormula && savedFormula.length ? calculateAppliedPrice(price) : price
                        };

                        variationDetails.push(variationData);
                    } else {
                        displayToast('Product update not supported yet, we are working on that');
                        return;
                    }

                    // Display the modal with common variations
                    displayUpdateModal(productType, sku, productId, variationDetails);
                });
            }
            else {
                displayToast('Product update not supported yet, we are working on that');
                stopLoadingSpinner();

            }
    });

    let formulaContent = '';
    function calculateAppliedPrice(originalPrice) {
        if(!originalPrice){
            return ;
        }
        try {
            var price = originalPrice.toString().replace(",", "");

            // Check if there are formsToSave and if any form applies
            if (savedFormula && savedFormula.length) {
                var matchingForm = savedFormula.find(function (form) {
                    return form.min <= parseFloat(price) && form.max >= parseFloat(price);
                });

                // If a matching form is found, apply the formula
                if (matchingForm && matchingForm.min && matchingForm.max) {
                    var multiplyFactor = matchingForm.multiply || 1;
                    var additionValue = matchingForm.addition || 0;
                    // if(savedFormula && savedFormula.length){

                    formulaContent = "Applied Formula = original price increased by (" + multiplyFactor + " % )  [+] " + additionValue
                    // }
                    // Calculate the applied price using the formula
                    var increasedPrice = parseFloat(price) + (parseFloat(price) * multiplyFactor) / 100 + parseFloat(additionValue);

                    // Update a text element with the applied formula information
                    // jQuery(".formulaContent").text("Applied Formula = original price increased by (" + multiplyFactor + " % )  [+] " + additionValue);

                    // Update the price variable with the calculated value
                    price = increasedPrice;
                }
            }
            return price ? parseFloat(Number(price).toFixed(2)) : parseFloat(price);

        } catch (e) {
            displayToast('Error while applying formula, please contact support team');
            return originalPrice;
        }
        // Remove commas from the input and store it in a variable


    }

    function getProductIdFromUrlEtsy(url) {

        const match = url.match(/\/listing\/(\d+)/);
        if (match && match[1]) {
            return match[1];
        }
        else {
            match = url.match(/listing_id=(\d+)|\/(\d+)\//);
            if (match) {
                // Check if the first group is present and not null
                if (match[1] && match[1] !== "null") {
                    return match[1];
                }
                // Check if the second group is present and not null
                if (match[2] && match[2] !== "null") {
                    return match[2];
                }
            }

        }



        return null;
    }
    let savedFormula;

    function restoreConfiguration() {
        jQuery.ajax({
            url: wooshark_params.ajaxurl,
            type: "POST",
            dataType: "JSON",
            data: { 
                nonce: wooshark_params.nonce,
                action: "restoreConfiguration-alibay" },
            success: function (response) {

                if (response && response._savedConfiguration_alibay && response._savedConfiguration_alibay.savedFormula) {
                    savedFormula = response._savedConfiguration_alibay.savedFormula;
                    //   restoreFormula(savedFormula);

                }
            },
            error: function (error) {
                displayToast("Error while retrieving configuration from server, please reload your page");
            },
            complete: function () { }
        });
    }





    function getEtsyProductDetails(listingId, callback) {


        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.response).data;

                    const variations = data.variations;

                    const itemPrice = data.price;
                    const itemQuantity = data.quantity;


                    callback(data);

                } catch (error) {
                    stopLoadingSpinner();
                    displayToast(
                        "Cannot retrieve product id, please try again. If the issue persists, please contact our support team",
                        "red"
                    );
                }
            } else if (xhr.readyState === 4 && xhr.status === 488) {
                const data = JSON.parse(xhr.response);
                stopLoadingSpinner();
                displayToast(data.error, "red");
            }
        };

        xhr.open("POST", "https://wooshark.website:6006/importProductDetails", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify({ listing_id: listingId, language: "en" }));


    }

    var uncommonVariations = [];
    function displayCommonVariations() {
        var commonVariations = [];
        var noCommonVariations = 0;
        // Array to store uncommon variations

        stopLoadingSpinner();

        if (woocommerceVariations && woocommerceVariations.length) {
            for (var i = 0; i < woocommerceVariations.length; i++) {
                var woocommerceVariation = woocommerceVariations[i];

                var isCommon = false; // Flag to check if the variation is common

                for (var j = 0; j < variationDetails.length; j++) {
                    var remoteVariation = variationDetails[j];

                    // handle the extra sub on the single product import
                    if (woocommerceVariation.sku && woocommerceVariation.sku.includes('sub')) {
                        remoteVariation.sku = remoteVariation.sku + 'sub';
                    }

                    if (woocommerceVariation.sku === remoteVariation.sku) {
                        commonVariations.push(remoteVariation);
                        isCommon = true;
                        break;
                    }
                }

                // If the variation is not common, add it to the uncommonVariations array
                if (!isCommon) {
                    uncommonVariations.push(woocommerceVariation);
                }
            }
        }

        noCommonVariations = variationDetails.length - commonVariations.length;

        // Display common variations in the modal (you can customize this part)
        var variableProductTableBody = jQuery('#variableProductTableBody');
        variableProductTableBody.empty();

        if (commonVariations.length > 0) {
            commonVariations.forEach(function (variation) {
                var row = `
                    <tr>
                        <td><input disabled type="text" class="form-control" value="${variation.sku || ''}"></td>
                        <td><input type="text" class="form-control" value="${variation.quantity}"></td>
                        <td><input type="text" class="form-control" value="${variation.price}"></td>
                        <td><input type="text" class="form-control" value="${variation.price || ''}"></td>
                    </tr>
                `;

                variableProductTableBody.append(row);
            });
        } else {
            // No common variations found, display an error message in the modal
            var errorMessage = `
                <tr>
                    <td colspan="5">No common variations found. </td>
                </tr>
            `;

            variableProductTableBody.append(errorMessage);
        }

        // Now, you can use the uncommonVariations array for your purposes.
        // For example, you can iterate through it to do something with the uncommon variations.
        uncommonVariations.forEach(function (uncommonVariation) {
            // Do something with the uncommon variation.
            // You can add it to another array or process it as needed.
        });

        // jQuery('.notification-variations').text(` ${noCommonVariations} variations are missing on this product`);
        jQuery('.variations-section').show();
        jQuery('#updateProductModal').modal('show');
    }



    let updateType = 'variations';
    function displayCommonDetailsForSimpleProducts() {
        updateType = 'simple';

        var commonVariations = [];
        stopLoadingSpinner();
        // Compare WooCommerce variations with variations received from the remote server based on SKU
        // for (var i = 0; i < woocommerceVariations.length; i++) {
        //     var woocommerceVariation = woocommerceVariations[i];

        for (var j = 0; j < variationDetails.length; j++) {
            var remoteVariation = variationDetails[j];
            // handle the extra sub on the single product import
            // if (woocommerceVariation.sku && woocommerceVariation.sku.includes('sub')) {
            //     remoteVariation.sku =  remoteVariation.sku + 'sub';
            // }

            // if (woocommerceVariation.sku === remoteVariation.sku) {

            commonVariations.push(remoteVariation);
            break;
            // }
        }
        // }

        // Display common variations in the modal (you can customize this part)
        var variableProductTableBody = jQuery('#variableProductTableBody');
        variableProductTableBody.empty();

        if (commonVariations.length > 0) {
            commonVariations.forEach(function (variation) {
                var row = `
                    <tr>
                        <td><input disabled type="text" class="form-control" value="${variation.sku || ''}"></td>
                        <td><input type="text" class="form-control" value="${variation.quantity}"></td>
                        <td><input type="text" class="form-control" value="${variation.price}"></td>
                        <td><input type="text" class="form-control" value="${variation.price || ''}"></td>
                    </tr>
                `;

                variableProductTableBody.append(row);
            });
        } else {
            // No common variations found, display an error message in the modal
            var errorMessage = `
                <tr>
                    <td colspan="5">No common variations found.</td>
                </tr>
            `;

            variableProductTableBody.append(errorMessage);
        }


        // commonVariations.forEach(function (variation) {
        //     var row = `
        //         <tr>
        //             <td><input disabled type="text" class="form-control" value="${variation.sku.replace('sub', '') || ''}"></td>
        //             <td><input type="text" class="form-control" value="${variation.max_qty}"></td>
        //             <td><input type="text" class="form-control" value="${variation.display_regular_price}"></td>
        //             <td><input type="text" class="form-control" value="${variation.display_price || ''}"></td>
        //             <td><button class="delete-button btn btn-danger">Delete</button></td>
        //         </tr>
        //     `;

        //     variableProductTableBody.append(row);
        // });


        // enableDisableConfirmUpdateButton();

        jQuery('#updateProductModal').modal('show');
    }

    jQuery(document).on('click', '.display-uncommon-variations', function () {

        var variableProductTableBody = jQuery('#variableProductTableBody');
        variableProductTableBody.empty();

        if (uncommonVariations.length > 0) {
            uncommonVariations.forEach(function (variation) {
                var row = `
                <tr>
                    <td><input disabled type="text" class="form-control" value="${variation.sku || ''}"></td>
                    <td><input type="text" class="form-control" value="${variation.quantity}"></td>
                    <td><input type="text" class="form-control" value="${variation.price}"></td>
                    <td><input type="text" class="form-control" value="${variation.price || ''}"></td>
                    <td><button class="insert-to-product btn btn-success" data-variation='${JSON.stringify(variation)}'>Insert to Product</button></td>
                </tr>
            `;

                variableProductTableBody.append(row);
            });
        }

    });

    jQuery(document).on('click', '.update-url-button', function () {

        let modalTemplate = `
        
        <div class="modal fade" id="updateUrlModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateUrlModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:50vw; max-width:50vw; margin-top:40px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateUrlModalLabel">Update title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <input type="text" id="newProductUrl" class="form-control" placeholder="Enter new URL">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="saveUrlButton">Confirm</button>
      </div>
    </div>
  </div>
  </div>


        `;
        jQuery('body').append(modalTemplate);
        var modal = new bootstrap.Modal(document.getElementById('updateUrlModal'));
        modal.show();


        var productUrl = jQuery(this).parent('td').data('product-url');
        let product_id = jQuery(this).data('product-id');
        // Fetch and display current URL
        // ...
        jQuery('#newProductUrl').val(productUrl);
        jQuery('#saveUrlButton').off('click').on('click', function () {
            showLoadingSpinner();
            var newUrl = jQuery('#newProductUrl').val();
            jQuery.ajax({
                url: wooshark_params.ajaxurl,
                type: "POST",
                dataType: "JSON",
                data: { 
                    nonce: wooshark_params.nonce,
                    action: "updateProductUrlAlibay", productUrl: jQuery('#newProductUrl').val(), product_id: product_id },
                success: function (e) {
                    displayToast('Product url updated successfully.', 'green')
                },
                error: function (e) {
                    displayToast('Failed to update product url.', 'red')
                },
                complete: function () {
                    hideLoadingSpinner();
                }
            });
        });
    });


})(jQuery);
