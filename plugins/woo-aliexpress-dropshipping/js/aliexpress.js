(function (jQuery) {

	let isPremuim = false;
  var quill,
    hostname = "https://thesharkdropship.com",

    imagesFromDescription = [],
    items = "",

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


  function getProductId(e) {
    var t = e.indexOf(".html");
    if(t < 0) {
      // displayToast("Cannot get product id, please check you introduce a valid AliExpress url");
      return '';
    }
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
          countryCode: getSelectedCountryCode(),
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
  function getProductsCount() {
    jQuery.ajax({
      url: wooshark_params.ajaxurl,
      type: "POST",
      dataType: "JSON",
      data: { 
                        nonce: wooshark_params.nonce,
        action: "getProductsCount-alibay" },
      success: function (e) {
        console.log("----response", e),
          displayPaginationSection((totalproductsCounts = e), 1);
      },
      error: function (e) {
        console.log("****err", e),
          displayToast(e.responseText, "red"),
          stopLoading();
      },
      complete: function () {
        console.log("SSMEerr"), stopLoading();
      }
    });
  }


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


  function displayToastLonger(e, t, a) {
    jQuery.toast({
      text: e,
      textColor: "black",
      hideAfter: 10000,
      icon: "error",
      stack: 5,
      textAlign: "left",
      position: "bottom-right",
      loaderBg: "orange"
    });
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

  jQuery(document).on("click", "#apply-connect-automatic", function (e) {
    console.log(
      store_url +
      endpoint +
      "?app_name=" +
      params.app_name +
      "&scope=" +
      params.scope +
      "&user_id=" +
      params.user_id +
      "&return_url=" +
      params.return_url +
      "&callback_url=" +
      params.callback_url
    );
  }),
    jQuery(document).on("click", "#importProductToShopBySky", function (e) {
      var t = jQuery("#productSku").val();
      prepareModal_aliexpress(),
        t
          ? importProductGlobally(t)
          : displayToast("Cannot get product sku", "red");
    }),
    jQuery(document).ready(function () {
      jQuery('.nav-item a[id="pills-advanced-tab"]').html(
        jQuery('.nav-item a[id="pills-advanced-tab"]').text() +
        '<span   class="badge badge-light"> <i class="fas fa-star"></i> </span>'
      ),
        jQuery("#searchKeyword").val(""),
        restoreConfiguration(),
        getProductsCount(),
        searchByKeyWord("", "en", 1);

            // jQuery("[name=language][value=" + commonConfiguration.language + "]").attr("checked", true);
            // jQuery('<h4 style="font-weight:bold;"> Current Language: ' + commonConfiguration.language + '  </h4>').appendTo(".currencyDetails");
          

            // jQuery('<h4 style="font-weight:bold;"> Current currency: ' + getSelectedCurrency() + '  </h4>').appendTo(".currencyDetails");
          
        


    });


  var isAuthorizedUser = !1,
    currentProductId = "";
  jQuery(document).on("click", "#insert-product-reviews", function (e) {
    currentProductId = jQuery(this).parents("tr")[0].cells[2].innerText;
    prepareReviewModal();
  });



  jQuery('#pills-tab').on('shown.bs.tab', function (e) {
    if (e.target.id === 'pills-connect-products') {
      // Code to run when the "pills-products" tab is selected
      jQuery(".loaderImporttoShopProducts").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'))
      jQuery(".loaderImporttoShopProducts").show()

      getAllProducts(1);
      // Add your custom code here
    }
  });


  jQuery(".modal").on("hidden.bs.modal", function (e) {
    jQuery(this).removeData();
  });
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
  function displayPAginationForSearchByKeyword(e, t) {
    var a = Math.round(e / 40);
    a > 17 && (a = 17);
    for (var r = 1; r < a; r++)
      r == t
        ? jQuery(
          ' <li style="color:red" id="page-' +
          r +
          '" class="page-item"><a style="color:red" class="page-link">' +
          r +
          "</a></li>"
        ).appendTo("#pagination")
        : jQuery(
          ' <li id="page-' +
          r +
          '" class="page-item"><a class="page-link">' +
          r +
          "</a></li>"
        ).appendTo("#pagination");
  }
  function displayPaginationSection(e, t) {
    // let a = Math.ceil(e / 100);
    // a > 20 && (a = 20);
    // for (var r = 1; r < a + 1; r++)
    //   r == t
    //     ? jQuery(
    //       ' <li style="color:red" id="product-page-' +
    //       r +
    //       '" class="product-page-item"><a style="color:red" class="page-link">' +
    //       r +
    //       "</a></li>"
    //     ).appendTo("#product-pagination")
    //     : jQuery(
    //       ' <li id="product-page-' +
    //       r +
    //       '" class="product-page-item"><a class="page-link">' +
    //       r +
    //       "</a></li>"
    //     ).appendTo("#product-pagination");

    jQuery('#productsCount').html(' <span class="badge badge-light">' + e + '</span>')


    // jQuery('.nav-item a[id="pills-connect-products"]').html(
    //   'products <span class="badge badge-light">' + e + "</span>"
    // );
  }



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
  function prepareDataFormat(e, t, a, r) {
    if (
      e &&
      e.variations &&
      e.NameValueList &&
      e.variations.length &&
      e.NameValueList.length
    )
      return (
        e.NameValueList.forEach(function (e) {
          e.name && (e.name = e.name.toLowerCase().replace(/ /g, "-")),
            (e.values = e.value);
        }),
        e.variations.forEach(function (e) {
          e.attributesVariations &&
            e.attributesVariations.forEach(function (e) {
              e.name && (e.name = e.name.toLowerCase().replace(/ /g, "-"));
            }),
            e.regularPrice &&
            jQuery("#applyPriceFormulawhileImporting").prop("checked") &&
            (e.regularPrice = calculateAppliedPrice(e.regularPrice)),
            e.salePrice &&
            jQuery("#applyPriceFormulawhileImporting").prop("checked") &&
            (e.salePrice = calculateAppliedPrice(e.salePrice)),
            (e.availQuantity = parseInt(e.availQuantity)),
            (e.identifier = "");
        }),
        e
      );
    if (e && e.variations && e.variations.length && 1 == e.variations.length) {
      return {
        NameValueList: [
          { name: "color", values: ["Standard"], variation: !0, visible: !0 }
        ],
        variations: [
          {
            SKU: e.variations[0].SKU,
            regularPrice: e.variations[0].regularPrice,
            salePrice: e.variations[0].salePrice,
            availQuantity: e.variations[0].availQuantity,
            attributesVariations: [{ name: "color", value: "Standard" }]
          }
        ]
      };
    }
  }

  // function getProductAliExpress(sku, url, callback) {
  //   var xhr = new XMLHttpRequest();

  //   xhr.onreadystatechange = function () {
  //     if (xhr.readyState === 4) {
  //       if (xhr.status === 200) {
  //         var responseData = JSON.parse(xhr.response).data;

  //         if (responseData) {
  //           callback(responseData);
  //         }
  //       } else {
  //         var responseData = JSON.parse(xhr.response).data;

  //         handleProductLoadError(responseData);
  //       }
  //     }
  //   };

  //   var requestData = {
  //     sku: sku,
  //     language: "",
  //     isBasicVariationsModuleUsedForModalDisplay: true,
  //     currency: "",
  //     store: "",
  //     fullProductUrl: splitUrlUntilHtml(url),
  //   };

  //   sendRequestToInternalApi(requestData, xhr, callback);
  // }

  // function handleProductLoadError(data) {
  //   displayToast("Cannot load product details, please try again", "red");
  //   callback(null);
  // }

  // function sendRequestToInternalApi(requestData, xhr, callback) {
  //   xhr.open("POST", hostname + ":8002/getProductDEtailsFRomOurInternalApi", true);
  //   xhr.setRequestHeader("Content-Type", "application/json");
  //   xhr.send(JSON.stringify(requestData));
  // }


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

  function getSelectedCurrency() {
    // Get all radio buttons with the name 'currency'
    var currencies = document.getElementsByName('currency');

    for (var i = 0; i < currencies.length; i++) {
        if (currencies[i].checked) {
            // If a radio button is checked, display its value
            // alert("Selected Currency: " + currencies[i].value);
            return currencies[i].value; // Or handle the value as needed
        }
    }

    return 'USD'; // No currency selected
}

  function getCurrencyFromUrl(url) {

    const aliexpressDomainToCurrency = {
      us: "USD",  // United States - U.S. Dollar
      fr: "EUR",  // France - Euro
      de: "EUR",  // Germany - Euro
      es: "EUR",  // Spain - Euro
      it: "EUR",  // Italy - Euro
      ru: "RUB",  // Russia - Russian Ruble
      pt: "EUR", // Portugal - Euro,
      ja: "JPY",  // Japan - Japanese Yen
      nl: "EUR",  // Netherlands - Euro
      ar: "USD",

      uk: "GBP",  // United Kingdom - British Pound
      br: "BRL",  // Brazil - Brazilian Real
      au: "AUD",  // Australia - Australian Dollar
      ca: "CAD",  // Canada - Canadian Dollar
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
      pf: "XPF",  // French Southern and Antarctic Lands - CFP Franc,
      // Add more country codes and currencies as needed
    };

    // Add more country codes and currencies as needed


    // Extract the country code from the URL
    const countryMatch = url.match(/https:\/\/([a-z]{2})\./i);
    if (countryMatch && countryMatch[1]) {
      const countryCode = countryMatch[1].toLowerCase();

      // Check if the country code exists in the mapping
      if (aliexpressDomainToCurrency.hasOwnProperty(countryCode)) {
        return aliexpressDomainToCurrency[countryCode];

      } else {
        return 'USD'
      }
    } else {
      return 'USD'
    }
  }




  function getProductDetailsFromServer(e) {
    var t = getSelectedLanguage(),
      a = jQuery('input[name="currency"]:checked')[0]
        ? jQuery('input[name="currency"]:checked')[0].value
        : "USD",
      r = new XMLHttpRequest();
    (r.onreadystatechange = function () {
      if (4 == this.readyState)
        if (200 === this.status) {
          if ((a = JSON.parse(this.response).data)) {
            var t = [];
            jQuery(".categories input:checked").each(function () {
              t.push(
                jQuery(this)
                  .attr("value")
                  .trim()
              );
            });
            (waitingListProducts = []),
              jQuery(".importToS").each(function (e, t) {
                // console.log("----- un - disabling"),
                jQuery(t).attr("disabled", !1);
              }),
              jQuery("#importModal").click(),
              stopLoading(),
              fillTheForm(a, e);
          }
        } else {
          var a = JSON.parse(this.response).data;
          jQuery(".importToS").each(function (e, t) {
            jQuery(t).attr("disabled", !1);
          }),
            displayToast("Cannot load product details, please try again, if the issue persist please use the chrome extnesion or contact our support team", "red"),
            stopLoading();
        }
    }),
      r.open("POST", hostname + ":8002/getProductDEtailsFRomOurInternalApi", !0),
      r.setRequestHeader("Content-Type", "application/json"),
      r.send(
        JSON.stringify({
          sku: e,
          language: t,
          isBasicVariationsModuleUsedForModalDisplay: !0,
          currency: getSelectedCurrency(),
          store: document.location.origin,
          fullProductUrl: splitUrlUntilHtml(fullProductUrl)
        })
      );
  }
  function getCategories(e) {
    jQuery.ajax({
      url: wooshark_params.ajaxurl,
      type: "POST",
      dataType: "JSON",
      data: { 
                        nonce: wooshark_params.nonce,
        action: "get_categories-alibay" },
      success: function (t) {
        console.log("----response", t), (savedCategories = t), e();
      },
      error: function (t) {
        console.log("****err", t),
          displayToast(t.responseText, "red"),
          stopLoading(),
          e();
      },
      complete: function () {
        console.log("SSMEerr"), stopLoading(), e();
      }
    });
  }
  function getCreationDate(e) {
    e = dates[Math.floor(Math.random() * dates.length)];
    var t = dates.indexOf(e);
    return dates.splice(t, 1), e;
  }
  function getUsername() {
    var e = names[Math.floor(Math.random() * names.length)],
      t = names.indexOf(e);
    return names.splice(t, 1), e;
  }
  jQuery(document).on("click", "#select-category", function (e) {
    jQuery(".categories").is(":hidden")
      ? (jQuery(".categories").show(),
        getCategories(function () {
          console.log("-----");
        }))
      : jQuery(".categories").hide();
  });
  var names = [
    "Craig Piro",
    "Cindi Mcfarlin",
    "Maximilien Chopin",
    "Alfonso Villapol",
    "Gayla Tincher",
    "Lelah Pelosi",
    "Kholmatzhon Daniarov",
    "Klemens Totleben",
    "Émeric Figuier",
    "Joseph Garreau",
    "Moriya Masanobu",
    "Fernand Aveline",
    "Germain Beaumont",
    "Finn Junkermann",
    "Benoît Cortot",
    "Kawano Tanyu",
    "Gérald Noir",
    "Lisabeth Brennen",
    "Jaqueline Phipps",
    "Roderick Roth",
    "Adella Tarry",
    "Rudolf Kirsch",
    "Fritz Filippi",
    "Gérald Courbet",
    "Dastan Nurbolatev",
    "Oscar Álvarez",
    "Devon Huntoon",
    "Marlen Akhmetov",
    "Cassey Odle",
    "Patty Balser",
    "Néo Lortie",
    "Dieter Krist",
    "Speranzio Bartolone",
    "Iside Casaletto",
    "Durante Sciara",
    "Ildefonso Sollami",
    "Xose Mendez",
    "Vladimiro De Angelo",
    "Gianmaria De Sario",
    "Anacleto Adornetto",
    "Sigmund Bruckmann",
    "Valtena Amodei",
    "Liberatore Accordino",
    "Alfredo Lamanna",
    "Kemberly Roza",
    "Lluciano Marcos",
    "Fukumoto Shusake",
    "Branda Goshorn",
    "Isadora Heer",
    "Micael Montes",
    "Derrick Sclafani",
    "Thibault Silvestre",
    "Wendelin Jonas",
    "Coleen Dragon",
    "Ted Basye",
    "Emmanuel Gillie",
    "Lorean Soni",
    "Reiko Jeanlouis",
    "Olevia Lauder",
    "Savannah Brotherton",
    "Franchesca Schwebach",
    "Chae Jiang",
    "Jaimee Harter",
    "Windy Milnes",
    "Takako Ream",
    "Zoraida Swick",
    "Mammie Aguiniga",
    "Wendi Raver",
    "Clarita Pursell",
    "Diedra Spath",
    "Tandy Hoyte",
    "Lanie Edwin",
    "Marchelle Dowden",
    "Susann Masson",
    "Jannette Wilmes",
    "Lakisha Mullenix",
    "Shanda Gatling",
    "Kathi Okamura",
    "Ellie Julius",
    "Demarcus Mcmullen",
    "Major Woodrum",
    "Alpha Um",
    "Prudence Rodden",
    "Shante Dezern",
    "Emma Carra",
    "Starr Lheureux",
    "Verline Cordon",
    "Carla Poole",
    "Alisa Watts",
    "Maariya Kramer",
    "Aamir Boyd",
    "Antonio Levine",
    "Della Drew",
    "Miriam Perry",
    "Sarina Santos",
    "Armaan Ellison",
    "Graham Rankin",
    "Aasiyah Haney",
    "Debbie Tanner",
    "Yuvraj Wolf",
    "Eleri Barnes",
    "Ira Forster",
    "Gage Edmonds",
    "Nour Hartman",
    "Niam Mullins",
    "Mahi Reid",
    "Winston Hyde",
    "Rosalie Robertson",
    "Samirah Hood",
    "Bonnie Montes",
    "Aliya Fernandez",
    "Renesmae Knapp",
    "Enrique Lutz",
    "Korey Wu",
    "Andrea Xiong",
    "Daanyal Shepard",
    "Efan Wharton"
  ];
  function insertReviewsIntoWordpress(e, t) {
    startLoading(),
      jQuery.ajax({
        url: wooshark_params.ajaxurl,
        type: "POST",
        dataType: "JSON",
        data: {
          
                          nonce: wooshark_params.nonce,
          action: "insert-reviews-to-productRM-alibay",
          post_id: t,
          reviews: e
        },
        success: function (e) {
          e && !e.error && e.insertedSuccessfully && e.insertedSuccessfully.length
            ? displayToast(
              e.insertedSuccessfully.length +
              " reviews are imported successfully",
              "black"
            )
            : displayToast("Error while uploading reviews.", "red"),
            stopLoading(),
            jQuery("#table-reviews tbody").empty();
        },
        error: function (e) {
          console.log("****err", e),
            stopLoading(),
            e && e.responseText && displayToast(e.responseText, "red");
        }
      });
  }
  jQuery(document).on("click", "#confirmReviewInsertion", function (e) {
    e.preventDefault();
    var t = getCreatedReviews();
    (postId = currentProductId),
      console.log("---------reviews", t),
      console.log("---------postId", postId),
      postId
        ? insertReviewsIntoWordpress(t, postId)
        : displayToast("cannot get post id, please contact wooshark", "red");
  });
  var dates = [
    "2020-10-26",
    "2021-1-1",
    "2018-11-15",
    "2018-11-6",
    "2021-01-7",
    "2021-1-13",
    "2021-2-12",
    "2021-1-17",
    "2018-2-19",
    "2021-3-16",
    "2021-1-14",
    "2018-2-25",
    "2021-3-5",
    "2018-1-18",
    "2021-2-22",
    "2018-1-11",
    "2018-12-12",
    "2018-11-8",
    "2021-1-2",
    "2021-01-13",
    "2021-05-19",
    "2021-04-29",
    "2021-06-12",
    "2021-07-01",
    "2021-06-23",
    "2021-05-24",
    "2018-10-29",
    "2021-3-3",
    "2021-1-7",
    "2018-10-27",
    "2021-2-17",
    "2021-05-24",
    "2021-06-06",
    "2021-06-19",
    "2021-06-22",
    "2021-06-13",
    "2021-05-13",
    "2021-07-01",
    "2021-04-25",
    "2021-04-04",
    "2021-05-05",
    "2021-05-19",
    "2021-06-01",
    "2021-05-27",
    "2021-03-27",
    "2021-04-01",
    "2021-05-30",
    "2021-06-04"
  ];
  function getCreatedReviews() {
    var e = jQuery("#table-reviews tbody tr"),
      t = [];
    return (
      e.each(function (e, a) {
        t.push({
          review: a.cells[0].innerHTML || "-",
          rating:
            jQuery(a)
              .find("input")
              .val() || 5,
          datecreation: a.cells[2].outerText,
          username: a.cells[1].outerText || "unknown",
          email:
            a.cells[4].outerText &&
              !a.cells[4].outerText.includes("emailNotVisible@unknown.com")
              ? a.cells[4].outerText
              : "emailNotVisible@unknown.com"
        });
      }),
      t
    );
  }
  jQuery(document).on("click", "#removeReview", function (e) {
    let t = jQuery(this)
      .parents("tr")
      .index();
    jQuery(this)
      .parents("tr")
      .remove(),
      quillsArray.splice(t, 1);
  })

  var quillsArray = [];
  function handleServerResponseReviews(e) {
    200 === e
      ? (displayToast("Reviews imported successfully", "black"),
        jQuery(".loader2").css({ display: "none" }))
      : (displayToast("Error while inserting the product", "red"),
        jQuery(".loader2").css({ display: "none" }));
  }

  function getSelectedLanguage() {
    return jQuery('input[name="language"]:checked')[0]
      ? jQuery('input[name="language"]:checked')[0].value
      : "en";
  }

  function getSelectedCountryCode(){
    return jQuery('input[name="language"]:checked')[0]
      ? jQuery('input[name="language"]:checked')[0].value
      : "www";
  }

  function getHtmlDescription(e) {
    if (e) {
      var t = e.indexOf("window.adminAccountId");
      t > -1 && (e = e.substring(0, t));
    }
    (imagesFromDescription = jQuery(e).find("img")),
      jQuery("#descriptionContent").html(e);
    quill = new Quill("#editorDescription", {
      modules: {
        toolbar: [
          ["bold", "italic", "underline", "strike"],
          ["blockquote", "code-block"],
          [{ header: 1 }, { header: 2 }],
          [{ list: "ordered" }, { list: "bullet" }],
          [{ script: "sub" }, { script: "super" }],
          [{ indent: "-1" }, { indent: "+1" }],
          [{ direction: "rtl" }],
          [{ size: ["small", !1, "large", "huge"] }],
          [{ header: [1, 2, 3, 4, 5, 6, !1] }],
          [{ color: [] }, { background: [] }],
          [{ font: [] }],
          [{ align: [] }],
          ["clean"]
        ]
      },
      theme: "snow"
    });
  }


  // jQuery(document).on("click", "#importToShop", function (e) {
  //   prepareModal_aliexpress(),
  //     (productId = jQuery(this)
  //       .parents(".card")
  //       .find("#sku")[0].innerText),
  //     productId
  //       ? importProductGlobally(productId)
  //       : displayToast("Cannot get product sku", "red");
  // });

  jQuery(document).on("click", "#importToShop", function (e) {
    globalUrlProduct = jQuery(this).parents(".card").find("#productUrlByCard").attr("href");
    fullProductUrl = globalUrlProduct;
    prepareModal_aliexpress(), productId = jQuery(this).parents(".card").find("#sku")[0].innerText;
    productId ? importProductGlobally(productId) : displayToast("Cannot get product sku", "red");
    
    console.log('----*****', globalUrlProduct);
  });


  let currentProductModalDisplayed = "",
    currentPageReviews = 1;

  function fillTheForm(e, t) {
    if (
      ((isEtsy = !1),
        (currentProductModalDisplayed = t),
        (getReviewsFromHtml(t, 1), (currentPageReviews = 1)),
        getImagesModal(e.imageModule.imagePathList),
        getItemSpecific(e.specsModule.props),
        e && e.skuModule)
    ) {



      var a = e.skuModule.skuPriceList,
        r = { attributes: [], variations: [], NameValueList: [] };
      let t =
        a && a[0] && a[0].skuVal && a[0].skuVal.skuAmount
          ? a[0].skuVal.skuAmount.currency
          : "";
      t && jQuery("#currencyReturned").text(t),
        a.forEach(function (t, i) {
          if (t.skuPropIds)
            r.variations.push({
              SKU: t.skuIdStr,
              availQuantity: t.skuVal.availQuantity,
              // salePrice: t.skuVal.actSkuMultiCurrencyCalPrice,
              // regularPrice: t.skuVal.skuMultiCurrencyCalPrice,
              salePrice: t.skuVal.actSkuMultiCurrencyCalPrice || (t.skuVal.skuActivityAmount ? t.skuVal.skuActivityAmount.value : ''),
              regularPrice: t.skuVal.skuMultiCurrencyCalPrice || (t.skuVal.skuAmount ? t.skuVal.skuAmount.value : ''),
              attributesVariations: getAttributesVariations(
                t.skuPropIds,
                e.skuModule.productSKUPropertyList
              )
            });
          else if (t.skuVal && t.skuVal.skuCalPrice && 1 == a.length) {
            let e = [
              {
                skuPropertyName: "color",
                skuPropertyValues: [
                  {
                    propertyValueDisplayName: "as image",
                    propertyValueName: "as image",
                    skuPropertyImagePath: ""
                  }
                ]
              }
            ];
            r.variations.push({
              SKU: t.skuId,
              availQuantity: t.skuVal.availQuantity,
              salePrice: t.skuVal.actSkuMultiCurrencyCalPrice || (t.skuVal.skuActivityAmount ? t.skuVal.skuActivityAmount.value : ''),
              regularPrice: t.skuVal.skuMultiCurrencyCalPrice || (t.skuVal.skuAmount ? t.skuVal.skuAmount.value : ''),
              attributesVariations: fakeGetAttributesVariations(e)
            }),
              (r.NameValueList = buildNameListValues(e));
          }
        }),
        a &&
        a[0] &&
        e.skuModule &&
        e.skuModule.productSKUPropertyList &&
        (r.NameValueList = buildNameListValues(
          e.skuModule.productSKUPropertyList
        )),
        getAttributes(r),
        getVariations(r.variations),
        jQuery("#customProductCategory").empty(),
        savedCategories &&
        savedCategories.length &&
        savedCategories.forEach(function (e, t) {
          (items =
            '<div class="checkbox"><label><input type="checkbox" value="' +
            e.term_id +
            '"/>' +
            e.name +
            "</label>"),
            jQuery("#customProductCategory").append(jQuery(items));
        });
      let i = jQuery("#textToBeReplaced").val(),
        o = jQuery("#textToReplace").val();
      fillTags(e.title);
      if (e && e.shopCategoryComponen && e.shopCategoryComponen.productGroupsResult && e.shopCategoryComponen.productGroupsResult.groups && e.shopCategoryComponen.productGroupsResult.groups.length) {
        loadCategories(e.shopCategoryComponen.productGroupsResult.groups);
      }
      let shipTo='', deliveryDate='', shippingFee='', shipFrom='';
      if(e.webGeneralFreightCalculateComponent && e.webGeneralFreightCalculateComponent.bizData){
        shipTo = e.webGeneralFreightCalculateComponent.bizData.shipToCode;
        deliveryDate = e.webGeneralFreightCalculateComponent.bizData.deliveryDate;
        shippingFee = e.webGeneralFreightCalculateComponent.bizData.shippingFee;
        shipFrom = e.webGeneralFreightCalculateComponent.bizData.shipFrom;
      }
      globalTitle = e.title;
      if (i && o) {
        let t = e.title,
          a = e.description;
        jQuery("#customProductTitle").val(t.replace(i, o)),
          getHtmlDescription(a.replace(i, o));
      } else
        jQuery("#customProductTitle").val(e.title),
          getHtmlDescription(e.description);
    }
  }
  function getImagesModal(e) {
    (images = e),
      e.forEach(function (e) {
        jQuery(
          '<div><button type="button" class="btn btn-primary" id="removeImage" ><i style="font-size:15px ; margin:5px">Remove Image</i></button><img  style="width:100%" src=' +
          e +
          " /><div>"
        ).appendTo(jQuery("#galleryPicture"));
      });
  }
  function getVariationsIsChecked() {
    return jQuery("#isVariationDisplayedValue").prop("checked");
  }
  function getAttributesVariations(e, t) {
    for (var a = [], r = e.split(","), i = 0; i < r.length; i++)
      t.forEach(function (e) {
        e.skuPropertyValues.forEach(function (t) {
          r[i] == t.propertyValueId &&
            a.push({
              name: e.skuPropertyName,
              value: getVariationsIsChecked()
                ? t.propertyValueDisplayName
                : t.propertyValueName,
              image: t.skuPropertyImagePath
            });
        });
      });
    return a;
  }
  function fakeGetAttributesVariations(e) {
    var t = [];
    return (
      e.forEach(function (e) {
        e.skuPropertyValues.forEach(function (a) {
          t.push({
            name: e.skuPropertyName,
            value: getVariationsIsChecked()
              ? a.propertyValueDisplayName
              : a.propertyValueName,
            image: a.skuPropertyImagePath
          });
        });
      }),
      t
    );
  }
  function buildNameListValues(e) {
    var t = [];
    return (
      e.forEach(function (e, a) {
        var r = getAttrValues(e);
        r && r.length && t.push({ name: e.skuPropertyName, value: r });
      }),
      t
    );
  }
  function getAttrValues(e) {
    var t = [];
    return (
      e.skuPropertyValues.forEach(function (e) {
        e.propertyValueDisplayName && getVariationsIsChecked()
          ? t.push(e.propertyValueDisplayName)
          : t.push(e.propertyValueName);
      }),
      console.log("values", t),
      t
    );
  }
  function getItemSpecific(e) {
    jQuery("#table-specific tbody tr").remove(),
      jQuery("#table-specific thead tr").remove(),
      e &&
      e.length &&
      e.forEach(function (e) {
        var t = "<td contenteditable>" + e.attrName + "</td>",
          a = "<td contenteditable>" + e.attrValue + "</td>";
        jQuery("#table-specific tbody").append(
          jQuery(
            "<tr>" +
            t +
            a +
            '<td><button id="removeAttribute" class="btn btn-danger">X</btton><td></tr>'
          )
        );
      }),
      jQuery("#table-specific tr td[contenteditable]").css({
        border: "1px solid #51a7e8",
        "box-shadow":
          "inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)"
      });
  }
  function applyPriceFormulaDefault() {
    var e = jQuery("#table-variations tbody tr"),
      t = jQuery("#table-variations thead tr")[0].cells.length - 8;
    e.each(function (e, a) {
      var r = calculateAppliedPrice(a.cells[t + 1].textContent);
      a.cells[t + 1].textContent = r.toFixed(2);
    }),
      e.each(function (e, a) {
        var r = calculateAppliedPrice(a.cells[t + 2].textContent);
        a.cells[t + 2].textContent = r.toFixed(2);
      });
  }
  function calculateAppliedPrice(e) {
    var t = (e = e.replace(",", ""));
    if (formsToSave && formsToSave.length) {
      var a = {};
      if (
        (formsToSave.forEach(function (t) {
          t.min <= parseFloat(e) && t.max >= parseFloat(e) && (a = t);
        }),
          a && a.min && a.max)
      ) {
        var r = a.multiply || 1,
          i = math.eval(r),
          o = a.addition || 0,
          n = math.eval(o);
        jQuery(".formulaContent").text(
          "Applied Formula = original price increased by (" + r + " % )  [+] " + o
        ),
          (t =
            parseFloat(e) +
            (parseFloat(e) * parseFloat(i)) / 100 +
            parseFloat(n));
      }
    }
    return t ? ((t = Number(t).toFixed(2)), parseFloat(t)) : parseFloat(t);
  }
  jQuery(document).on("click", "#removePicture", function (e) {
    if (jQuery("#removePicture")[0].checked) {
      htmlEditor = quill.root.innerHTML;
      var t = htmlEditor.replace(/<img[^>]*>/g, "");
      (t = t.replace(/<a[^>]*>/g, "")),
        quill.setContents([]),
        quill.clipboard.dangerouslyPasteHTML(0, t);
    } else quill.setContents([]), quill.clipboard.dangerouslyPasteHTML(0, htmlEditor);
  }),
    jQuery(document).on("click", "#removeDescription", function (e) {
      jQuery("#removeDescription")[0].checked
        ? ((htmlEditor = quill.root.innerHTML), quill.setContents([]))
        : (quill.setContents([]),
          quill.clipboard.dangerouslyPasteHTML(0, htmlEditor));
    }),
    jQuery(document).on("click", "#removeVariations", function (e) {
      if (jQuery("#table-attributes tr").length > 2) {
        var t = jQuery(this).parents("tr")[0].cells[0].innerText;
        jQuery(this)
          .parents("tr")
          .remove(),
          jQuery("#table-variations tr").each(function () {
            var e = attributesNamesArray.indexOf(t) + 1;
            e > -1
              ? jQuery(this)
                .find("td:eq(" + e + ")")
                .remove()
              : displayToast(
                "cannot remove this attribute, the name does not match, please contact wooshark: reference- issue with removing an attributes",
                "red"
              );
          });
      } else displayToast("need at least one attribute to insert this product");
    }),
    jQuery(document).on("click", "#removeAttribute", function (e) {
      jQuery(this)
        .parents("tr")
        .remove();
    }),
    jQuery(document).on("click", "#removeVariation", function (e) {
      jQuery(this)
        .parents("tr")
        .remove();
    }),
    jQuery(document).on("click", "#removeImage", function (e) {
      var t = jQuery(this)
        .parent()
        .find("img")
        .attr("src"),
        a = images.indexOf(t);
      a > -1 && images.splice(a, 1),
        jQuery("#galleryPicture").empty(),
        images.forEach(function (e) {
          jQuery(
            '<div><button type="button" class="btn btn-primary" id="removeImage" ><i style="font-size:15px ; margin:5px">Remove Image</i></button><img  src=' +
            e +
            " /><div>"
          ).appendTo(jQuery("#galleryPicture"));
        });
    }),
    jQuery(document).on("click", "#removeDescription", function (e) {
      jQuery("#removeDescription")[0].checked
        ? ((htmlEditor = quill.root.innerHTML), quill.setContents([]))
        : (quill.setContents([]),
          quill.clipboard.dangerouslyPasteHTML(0, htmlEditor));
    }),
    jQuery(document).on("click", "#removeText", function (e) {
      jQuery("#removeText")[0].checked && jQuery("#descriptionContent").html("");
    })

  //   jQuery("#includeImageFromDescription")[0].checked && imagesFromDescription && imagesFromDescription.length && imagesFromDescription.each(function(t, e) {
  //     t < 10 && (jQuery('<div><button type="button" class="btn btn-primary" id="removeImage" ><i style="font-size:15px ; margin:5px">Remove Image</i></button><img  src=' + e.currentSrc + " /><div>").appendTo(jQuery("#galleryPicture")), images.push(e.currentSrc))
  // }
  // )


  jQuery(document).on("click", "#includeImageFromDescription", function (e) {
    jQuery("#includeImageFromDescription")[0].checked &&
      imagesFromDescription.each(function (t, e) {
        t < 10 &&
          (jQuery(
            '<div><button type="button" class="btn btn-primary" id="removeImage" ><i style="font-size:15px ; margin:5px">Remove Image</i></button><img  src=' +
            e.currentSrc +
            " /><div>"
          ).appendTo(jQuery("#galleryPicture")),
            images.push(e.currentSrc));
      });
  });
  var copiedObject = "";
  jQuery(document).on("click", "#applyCharmPricing99", function (e) {
    var t = jQuery("#applyCharmPricing99")[0].checked,
      a = jQuery("#table-variations tbody tr");
    copiedObject || (copiedObject = a.clone());
    var i = jQuery("#table-variations thead tr")[0].cells.length - 8;
    t ? (a.each(function (e, t) {
      t.cells[i + 1].textContent = Math.ceil(t.cells[i + 1].textContent).toFixed(2) - .01
    }), a.each(function (e, t) {
      t.cells[i + 2].textContent = Math.ceil(t.cells[i + 2].textContent).toFixed(2) - .01
    })) : (a.each(function (e, t) {
      t.cells[i + 1].textContent = copiedObject[e].cells[i + 1].textContent
    }), a.each(function (e, t) {
      t.cells[i + 2].textContent = copiedObject[e].cells[i + 2].textContent
    }))
  })
  copiedObject = "";
  jQuery(document).on("click", "#applyCharmPricing", function (e) {
    var t = jQuery("#applyCharmPricing")[0].checked,
      a = jQuery("#table-variations tbody tr");
    copiedObject || (copiedObject = a.clone());
    var i = jQuery("#table-variations thead tr")[0].cells.length - 8;
    t ? (a.each(function (e, t) {
      t.cells[i + 1].textContent = Math.ceil(t.cells[i + 1].textContent).toFixed(2)
    }), a.each(function (e, t) {
      t.cells[i + 2].textContent = Math.ceil(t.cells[i + 2].textContent).toFixed(2)
    })) : (a.each(function (e, t) {
      t.cells[i + 1].textContent = copiedObject[e].cells[i + 1].textContent
    }), a.each(function (e, t) {
      t.cells[i + 2].textContent = copiedObject[e].cells[i + 2].textContent
    }))
  })
    ,
    jQuery(document).on("click", "#applyPriceFormulaRegularPrice", function (e) {
      if (jQuery("#applyPriceFormulaRegularPrice")[0].checked) {
        var t = jQuery("#table-variations tbody tr"),
          a = jQuery("#table-variations thead tr")[0].cells.length - 8;
        t.each(function (e, t) {
          t.cells[a + 1].textContent = calculateAppliedPrice(
            t.cells[a + 1].textContent
          );
        }),
          jQuery("#applyPriceFormulaRegularPrice").prop("disabled", !0);
      }
    }),
    jQuery(document).on("click", "#globalRegularPrice", function (e) {
      jQuery("#globalRegularPriceValue").val();
      if (jQuery("#globalRegularPriceValue").val()) {
        var t = jQuery("#table-variations tbody tr"),
          a = jQuery("#table-variations thead tr")[0].cells.length - 8;
        t.each(function (e, t) {
          t.cells[a + 1].textContent = jQuery("#globalRegularPriceValue").val();
        });
      }
    }),
    jQuery(document).on("click", "#globalSalePrice", function (e) {
      if (jQuery("#globalSalePriceValue").val()) {
        var t = jQuery("#table-variations tbody tr"),
          a = jQuery("#table-variations thead tr")[0].cells.length - 8;
        t.each(function (e, t) {
          t.cells[a + 2].textContent = jQuery("#globalSalePriceValue").val();
        });
      }
    }),
    jQuery(document).on("click", "#displayAdvancedVariations", function (e) {
      jQuery("#table-attributes").show();
    }),
    jQuery(document).on("click", "#addShippingPrice", function (e) {
      if (jQuery("#addShippingPriceValue").val()) {
        var t = jQuery("#table-variations tbody tr"),
          a = jQuery("#table-variations thead tr")[0].cells.length - 8;
        t.each(function (e, t) {
          t.cells[a + 2].textContent =
            parseFloat(t.cells[a + 2].textContent) +
            parseFloat(jQuery("#addShippingPriceValue").val());
        }),
          (t = jQuery("#table-variations tbody tr")).each(function (e, t) {
            t.cells[a + 1].textContent =
              parseFloat(t.cells[a + 1].textContent) +
              parseFloat(jQuery("#addShippingPriceValue").val());
          });
      }
    });
  let tagsProduct = [];
  function getReviews() {
    var e = jQuery("#customReviews tr"),
      t = [];
    return e && e.length
      ? (e.each(function (e, a) {
        e &&
          t.push({
            review: a.cells[0].innerHTML || "-",
            rating:
              jQuery(a)
                .find("input")
                .val() || 5,
            datecreation: a.cells[2].outerText,
            username: a.cells[1].outerText || "unknown",
            email: "test@test.com"
          });
      }),
        t)
      : [];
  }
  function resetTheForm() {
    jQuery("#customProductTitle").val(""),
      jQuery("#shortDescription").val(""),
      jQuery("#customPrice").val(""),
      jQuery("#customSalePrice").val(""),
      jQuery("#simpleSku").val(""),
      jQuery("#customProductCategory input:checked").each(function () {
        jQuery(this).prop("checked", !0);
      }),
      jQuery("#table-attributes tr").remove(),
      jQuery("#customProductCategory").empty(),
      jQuery("#galleryPicture").empty(),
      jQuery("#table-variations tr").remove();
  }
  function getPRoductUrlFRomSku(e) {
    var t = "";
    if (e) {
      var a = getSelectedLanguage();
      t =
        "en" == a
          ? "https://aliexpress.com/item/" + e + ".html"
          : "https://" + a + ".aliexpress.com/item/" + e + ".html";
    }
    return t;
  }
  let globalUrlProduct = "";

  let u = globalUrlProduct;


  jQuery(document).on("click", "#addTagToProduct", function (e) {
    let t = jQuery("#tagInput").val();
    t &&
      (tagsProduct.push(t),
        jQuery("#tagInput").val(""),

        jQuery("#tagInputDisplayed").append(jQuery("<div style='width: fit-content;padding: 10px;background-color: #212148;border-radius: 10px;margin: 10px;'>" + t +
          '<button class="btn btn-danger removeTag">X</button></div> ')));
  }),
    jQuery(document).on("click", "#addSpecific", function (e) {
      jQuery("#table-specific tbody").append(
        '<tr><td style="width:50%" contenteditable>    </td><td contenteditable style="width:50%"></td><td><button id="removeAttribute" class="btn btn-danger">X</btton></td></tr>'
      ),
        jQuery("#table-specific tr td[contenteditable]").css({
          border: "1px solid #51a7e8",
          "box-shadow":
            "inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)"
        });
    });
  let isEtsy = !1;
  // function buildVariations() {
  //   var e = { variations: [], NameValueList: [] };
  //   jQuery("#table-attributes tbody tr").each(function (t, a) {
  //     if (t) {
  //       e.NameValueList.find(function (e) {
  //         return (
  //           e.name == a.cells[0].textContent.toLowerCase().replace(/ /g, "-")
  //         );
  //       }) ||
  //         e.NameValueList.push({
  //           name: a.cells[0].textContent.toLowerCase().replace(/ /g, "-"),
  //           values: a.cells[1].textContent.split(","),
  //           variation: !0,
  //           visible: !0
  //         });
  //     }
  //   });
  //   var t = e.NameValueList.length;
  //   return (
  //     jQuery("#table-variations tr").each(function (a, r) {
  //       if (a && a < 100) {
  //         var i = [];
  //         e.NameValueList.forEach(function (e, t) {
  //           i.push({
  //             name: e.name.toLowerCase().replace(/ /g, "-"),
  //             value: r.cells[t + 1].textContent.trim(),
  //             image:
  //               r.cells[0] &&
  //                 r.cells[0].children &&
  //                 r.cells[0].children[0] &&
  //                 r.cells[0].children[0].currentSrc
  //                 ? r.cells[0].children[0].currentSrc
  //                 : ""
  //           });
  //         }),
  //           r.cells[t + 1].textContent &&
  //           e.variations.push({
  //             SKU: r.cells[t + 4].textContent,
  //             availQuantity: r.cells[t + 1].textContent || 1,
  //             salePrice: r.cells[t + 3].textContent,
  //             regularPrice: r.cells[t + 2].textContent,
  //             attributesVariations: i,
  //             weight:
  //               r.cells[t + 6].textContent || jQuery("#productWeight").val()
  //           });
  //       }
  //     }),
  //     e
  //   );
  // }
  function buildVariations() {
    var e = { variations: [], NameValueList: [] };

    // Initialize an empty array to store variations
    var variations = [];
    jQuery("#table-attributes tbody tr ").each(function (t, a) {


      e.NameValueList.push({
        name: a.cells[0].textContent
          .toLowerCase()
          .replace(/ /g, "-")
          .replace("'", "-"),
        values: a.cells[1].textContent.split(","),
        variation: !0,
        visible: !0
      });
    });
    // Get all the table rows using jQuery
    jQuery("#table-variations tr").each(function (index, row) {
      // Skip the first row (header row)
      if (index === 0) {
        return;
      }

      var variation = {
        SKU: jQuery(row).find('#singleAsin').text(), // Assuming SKU is in a cell with id 'singleAsin'
        availQuantity: parseInt(jQuery(row).find('#singleQuantity').text()), // Assuming availableQuantity is in the second column
        regularPrice: parseFloat(jQuery(row).find('#singleRegularPrice').text()), // Assuming regularPrice is in a cell with id 'singleRegularPrice'
        salePrice: parseFloat(jQuery(row).find('#singleSalePrice').text()), // Assuming salePrice is in a cell with id 'singleSalePrice'
        attributesVariations: []
      };

      // Iterate through the columns with 'name' attribute (starting from the fifth column)
      jQuery(row).find('td[name]').each(function () {
        var columnName = jQuery(this).attr('name');
        var columnValue = jQuery(this).text();
        var attribute = {
          name: columnName.toLowerCase().replace(/ /g, "-").replace("'", "-"),
          value: columnValue,
          image: jQuery(this).parent('tr').find('td[imagePath]').attr('imagePath')
        };
        variation.attributesVariations.push(attribute);
      });

      // Add the variation to the array
      e.variations.push(variation);

    });


    // Now, the 'variations' array contains all the variations with the specified properties
    console.log(variations);
    return e;

  }
  function getItemSpecificfromTableAliexpress(e) {
    var t = jQuery("#table-specific tbody tr"),
      a = e.NameValueList.map(function (e) {
        return e.name;
      });
    return (
      t &&
      t.length &&
      t.each(function (t, r) {
        -1 == a.indexOf(r.cells[0].textContent) &&
          e.NameValueList.push({
            name: r.cells[0].textContent || "-",
            visible: !0,
            variation: !1,
            values: [r.cells[1].textContent]
          });
      }),
      e
    );
  }

  jQuery(document).on("click", ".close-modal", function (e) {
		jQuery('#aliexpressModal').remove();
	  });

  function prepareModal_aliexpress() {
    tagsProduct = [], jQuery("#myModal").remove(),
      jQuery(
        ` <button type="button" id="importModal" style="display: none; position:relative" class="btn btn-primary btn-lg"
          data-bs-toggle="modal" data-bs-target="#aliexpressModal">Import To Shop</button>
  <div class="modal fade"  tabindex="-1"  style="margin-top: 4%;" id="aliexpressModal" role="dialog" >
  <div class="modal-dialog" style="max-width:70vw; width:70vw">
  <div class="modal-content"
      style="border-radius: 20px;box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;height:92vh">
      <div class="modal-header">
          <h4 class="modal-title">Product customization <span style="color:red" id="productType"></span>  - Currency Code: <span
                  style="color:red" id="currencyReturned"> <span></h4>

          <button class="btn btn-danger close-modal" data-bs-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" style="overflow: scroll;">
                  <ul id="tabs" class="nav nav-tabs">
                      <li class="nav-item"> <button role="tab" data-bs-toggle="tab" aria-selected="false"
                              data-bs-toggle="tab" data-bs-target="#home" class="nav-link active">General</a></li>
                      <li class="nav-item"> <button role="tab" data-bs-toggle="tab" aria-selected="false"
                              data-bs-toggle="tab" data-bs-target="#menu1" class="nav-link">Description</a></li>
                      <li class="nav-item"> <button role="tab" data-bs-toggle="tab" aria-selected="false"
                              data-bs-toggle="tab" data-bs-target="#menu3" class="nav-link">Gallery</a></li>
                      <li class="nav-item"> <button role="tab" data-bs-toggle="tab" aria-selected="false"
                              data-bs-toggle="tab" data-bs-target="#menu4" class="nav-link">Reviews</a></li>
                      <li class="nav-item"> <button role="tab" data-bs-toggle="tab" aria-selected="true"
                              data-bs-toggle="tab" data-bs-target="#menu5" class="nav-link">Product Variations</a></li>
                      <li class="nav-item"> <button role="tab" data-bs-toggle="tab" aria-selected="false"
                              data-bs-toggle="tab" data-bs-target="#menu6" class="nav-link">Product Attributes</a></li>
                      <li class="nav-item"> <button role="tab" data-bs-toggle="tab" aria-selected="false"
                              data-bs-toggle="tab" data-bs-target="#menu7" class="nav-link">Tags</a></li>
                  </ul>
                  </ul>
                  <div class="tab-content">
                      <div id="home" class="tab-pane fade show active">
                          <div class="form-group" id="priceContainer" style="display:none">
                              <div class="form-group">
                                  <h3 style="color: #c4b9b9; margin-top: 20px;" for="price">Regular Price: <span
                                          style="color:red" id="formulaContent"><span></h3>
                              </div> <input style="width:97%" id="customPrice" type="number" class="form-control"
                                  id="price">
                              <div class="form-group">
                                  <h3 style="color: #c4b9b9; margin-top: 20px;" for="price">Sale Price: <span
                                          style="color:red" id="formulaContent"><span></h3>
                              </div> <input style="width:97%" id="customSalePrice" type="number" class="form-control"
                                  id="price"><button id="setFormulaAliexpress" class="btn btn-primary"
                                  style="width:100%; margin-top:5px"> Set Formula</button>
                          </div>
                          <div class="form-group">
                              <h3 style="color: #c4b9b9" for="title">custom Title:</h3> <input style="height:60px"
                                  id="customProductTitle" type="text"
                                  placeholder="custom title, if empty original title will be displayed"
                                  class="form-control" id="title">
                          </div>
                          <div class="form-group" id="skuContainer" style="display:none">
                              <h3 style="color: #c4b9b9" for="title">Sku <small> (Optional) </small> </h3> <input
                                  style="width: 100%;padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-top: 6px; margin-bottom: 16px; resize: vertical;"
                                  type="text" placeholder="Sku attribute (optional)" class="form-control" id="simpleSku">
                          </div>
                          <div class="form-group" id="productWeightContainer">
                              <h3 style="color: #c4b9b9" for="title">Product weight <small> (Optional) </small> </h3> <input
                                  id="productWeight" type="text" placeholder="product weight" class="form-control"
                                  id="title">
                          </div>
                          <div class="form-group">
                              <h3 style="color: #c4b9b9" for="title"> Short Description <small> (Optional) </small> </h3>
                              <textarea id="shortDescription" class="form-control" rows="2" id="comment"
                                  placeholder="Short description"></textarea>
                          </div>
                          <div class="checkbox" style="margin-top: 30px;"><label><input id="isPublish" type="checkbox"
                                      name="remember"> &nbsp; Publish (checked = publish | unchecked = draft)</label>
                          </div>
                          <div class="checkbox"><label><input id="isFeatured" type="checkbox" name="remember"> &nbsp;
                                  Featured product <small>Featuring products on your website is a great way to show your
                                      best selling or popular products from your store</small></label> </div>
                          <div class="checkbox"> </div>

                          <h3 style="margin-top:10px; color: #c4b9b9"> Select and create categories from AliExpress </h3>
                          <div id="shopCategories" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;height: 200px; overflow-y: scroll;"></div>

                          <div class="form-group" id="categoriesContainer" style="margin-top:30px">
                              <div class="panel panel-default">
                              <h3 style="margin-top:10px; color: #c4b9b9"> Select Categories from your shop </h3>
                              <div id="customProductCategory"
                                      style="height:150px;     border: 1px solid grey;padding: 10px; overflow-y:scroll"
                                      class="panel-body"> </div>
                              </div>
                          </div>
                      </div>
                      <div id="menu1" class="tab-pane fade in">
                          <div class="form-group">
                              <div class="checkbox">
                                  <div class="checkbox"><label><input id="ImportImagesFromGallery" type="checkbox"
                                              name="remember"> &nbsp;Add gallery images to the description </label> </div>
                                  <div class="checkbox">
                                  
                                              </div>
                                  <label><input id="removePicture" type="checkbox" name="remember"> &nbsp; Remove Pictures
                                  </label>
                              </div>
                              <div class="checkbox"><label><input id="removeDescription" type="checkbox" name="remember">
                                      &nbsp; Remove description </label> </div>
                              <div id="editorDescription">
                                  <div id="descriptionContent"> </div>
                              </div>
                          </div>
                      </div>
                      <div id="menu3" class="tab-pane fade in">
                          <div class="checkbox"><label><input id="includeImageFromDescription" type="checkbox"
                                      name="remember"> &nbsp; Include Pictures from description </label> </div>
                          <div id="galleryPicture" style="overflow-y:scroll;height:500px"> </div>
                      </div>
                      <div id="menu4" class="tab-pane fade in">
                          <div id="customReviews" style="overflow-y:scroll;height:500px"><button class="button-5"
                                  id="addReview" style="background-color:orange; width:100%;margin-top:10px"> Add
                                  Review</button>
                              <table id="table-reviews" class="table table-striped">
                                  <thead>
                                      <tr>
                                          <th>Review</th> 
                                          ' <th>Username</th>
                                          <th>Date creation</th>
                                          <th>Rating</th>
                                          <th>Remove</th>
                                      </tr>
                                  </thead>
                                  <tbody></tbody>
                              </table>
                          </div>
                      </div>
                      <div id="menu5" class="tab-pane fade ">
                          <div id="no-variations"
                              style="text-align:center; display:none; padding:20px; margin:30px; background-color:beige">
                              <span style=" text-align:center">This is a simple product, no variations can be
                                  defined</span></div>
                          <h3 class="formulatexcontainer" for="price"
                              style="background-color:beige; padding:15px; margin:20px;  text-align:center"> <span
                                  class="formulaContent">No formula defined yet<span></h3><button class="button-5"
                              style="margin-left: 35%; width:350px" id="openAdvancedSettings">Open Advanced
                              settings</button>
                          <div id="advancedVariationsCapa" style="display:none">
                              <div style="flex: 1 1 50%;">
                                  <div style="flex: 1 1 50%; display:flex; justify-content: center;">
                                      <labe style="justify-content: center; font-weight: 800; margin-y: 20px" l>Advanced
                                          Setting</label>
                                  </div>
                                  <div class="checkbox" id="applyCharmPricingConainer" style="display:none">
                                      <div class="checkbox"><label><input id="applyCharmPricing" type="checkbox"
                                                  name="remember"> &nbsp;Apply charm pricing 00 <small>( Example 2.34 ==>
                                                  3.00) </small></label> </div>
                                      <div class="checkbox"><label><input id="applyCharmPricing99" type="checkbox"
                                                  name="remember"> &nbsp; Apply charm pricing 99 <small>(Example 2.34 ==>
                                                  2.99) </small> </label> </div>
                                  </div>
                                  <div style="display:flex"> <input style="flex: 1 1  100px; width:50%;  margin: 5px"
                                          id="globalRegularPriceValue"
                                          placeholder="Apply Regular price for all variations" type="number"
                                          class="form-control"><button style="flex: 1 1  100px; margin: 5px"
                                          class="button-5" id="globalRegularPrice"> Apply</button> </div>
                                  <div style="display:flex"> <input style="flex: 1 1  100px; width:50%;  margin: 5px"
                                          id="globalSalePriceValue" placeholder="Apply Sale price for all variations"
                                          type="number" class="form-control"><button style="flex: 1 1  100px; margin: 5px" class="button-5" id="globalSalePrice"> Apply</button> </div>
                                  <div style="display:flex"> <input style="flex: 1 1  100px; width:50%;  margin: 5px"
                                          id="addShippingPriceValue" placeholder="Add shipping price" type="number"
                                          class="form-control"><button style="flex: 1 1  100px; margin: 5px"
                                          class="button-5" id="addShippingPrice"> Apply</button> </div>
                              </div>
                              <div style="flex: 1 1 50%; display:flex;">
                                  <div style="flex: 1 1 50%; display:flex; justify-content: center;">
                                      <labe style="justify-content: center; font-weight: 800; margin-bottom: 20px" >Import preferences</label>
                                  </div>
                                  <div><label>Title</label>
                                      <div></div><input type="radio" id="titleASIN" name="titleASIN" checked
                                          value="titleASIN"><span style="display:inline" for="titleASIN">&nbsp; Generic
                                          title + attribute values example (color, size, etc...) </span><br><input
                                          type="radio" id="titleTab" name="titleASIN" value="titleTab"><span
                                          style="display:inline" for="titleTab">&nbsp; Generic title </span><br><label
                                          style="margin-top:10px">Galley</label>
                                      <div></div><input type="radio" id="galleryASIN" name="galleryASIN" checked
                                          value="galleryASIN"><span style="display:inline" for="galleryASIN">&nbsp; Select
                                          variation image</span><br><input type="radio" id="galleryTab" name="galleryASIN"
                                          value="galleryTab"><span style="display:inline" for="galleryTab">&nbsp; Select
                                          Gallery tab images</span><br><label style="margin-top:10px">Affiliate </label>
                                      <div></div>
                                      <div style="display:flex"> <input
                                              style="flex:1 1  100px; width:50%;  margin-top:10px" id="affiliateLinkUrl"
                                              placeholder="Affiiate link example https://s.click.aliexpress.com/e/_DnkqFCF"
                                              type="text" class="form-control"> </div>
                                  </div>
                              </div>
                          </div>
                          <div style="height:400px; overflow-y:scroll">
                              <table id="table-variations" style="margin-top:20px" class="table table-striped">
                                  <thead></thead>
                                  <tbody></tbody>
                              </table>
                          </div><button id="displayAdvancedVariations" style="width:100%" class="btn btn-primary"> Edit
                              Attributes </button><small> <u> Note: </u> Any modification of the attributes values on the
                              variations table (such as color and size, etc..) need to be reflected on the attribute table
                              below (click edit Attributes). the value must be available on the list of possible values on
                              the table below. use a semi colon to add a new value</small>
                          <table id="table-attributes" style="display:none; margin-top:20px" class="table table-striped">
                              <thead>
                                  <tr>
                                      <th>name</th>
                                      <th>values</th>
                                      <th>Remove this from all variations</th>
                                  </tr>
                              </thead>
                              <tbody></tbody>
                          </table>
                      </div>
                      <div id="menu6" class="tab-pane fade in"><button class="button-5" id="addSpecific"
                              style="width:100%"> Additional data</button>
                          <table id="table-specific" style="margin-top:20px" class="table table-striped">
                              <thead>
                                  <tr>
                                      <th>property</th>
                                      <th>values</th>
                                      <th>Remove</th>
                                  </tr>
                              </thead>
                              <tbody></tbody>
                          </table>
                      </div>
                      <div id="menu7" class="tab-pane fade in" style="margin-top:10px">
                           <label> Add Tag to product</label><input
                              id="tagInput" type="text" class="form-control" /><button class="button-5"
                              id="addTagToProduct" style="width:100%"> Add tags</button>
                          <div id="tagInputDisplayed" style="color:white"></div>
                      </div>
                      <div id="advanced" class="tab-pane fade in">
                          <div class="form-group" style="margin-top:5px">
                              <h3 style="color: #c4b9b9" for="title"> Tags <small> (Optional) </small> </h3> <textarea
                                  id="tags" class="form-control" rows="2" id="comment"
                                  placeholder="Place tags separated by commas"></textarea>
                          </div>
                          <div style="margin-top:5px">
                              <h3 style="color: #c4b9b9" for="title"> Sale price (Optional) </small> </h3> <input
                                  style="width:97%" id="salePrice" type="number" class="form-control" id="price">
                          </div>
                          <div style="margin-top:5px">
                              <h3 style="color: #c4b9b9" for="title"> Sale start date </small> </h3> <input
                                  id="saleStartDate" type="date" class="form-control" id="price">
                          </div>
                          <div style="margin-top:5px">
                              <h3 style="color: #c4b9b9" for="title"> Sale end date </small> </h3> <input id="saleEndDate"
                                  type="date" class="form-control" id="price">
                          </div>
                      </div>
                      <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal"
                              data-bs-dismiss="modal">Close</button> <button type="button" id="totoButton"
                              class="button-5">Import <small style="color:grey" id="asVariableAliex"> ( as
                                  Dropshipping product ) </small><span id="loaderImporttoShop" style="display:none"></span></button>
                      </div>
                  </div>
              </div>
          </div>
        `).appendTo(jQuery("#modal-container"))
  }
  function restoreFormula(e) {
    if (e) {
      formsToSave = e;
      try {
        e &&
          e.length &&
          (jQuery("#formula tbody tr").remove(),
            e.forEach(function (e) {
              e &&
                e.min &&
                e.max &&
                e.multiply &&
                jQuery("#formula tbody").append(
                  '<tr><th style="width:15%"> <input class="custom-form-control" name="min" placeholder="Min price" value="' +
                  e.min +
                  '"></th><th style="width:2%">-</th><th style="width:15%"><input class="custom-form-control" name="max" placeholder="Max price" value="' +
                  e.max +
                  '"></th><th style="width:16%"><div style="display:flex"><button style="flex: 1 1 10%;border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" class="btn btn-light"> Increase by  </button><input value="' +
                  e.multiply +
                  '" style="flex: 1 1 78%; border: 1px solid #ccc;" class="multiply custom-form-control" type="number" name="multiply" placeholder="Increase percentage"><button style="flex: 1 1 10%;border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" class="btn btn-secondary">  <i class="fa fa-percent fa-2x"></i> </button></div></th><th style="width:15%"><div style="display:flex"><button style="flex: 1 1 10%;border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" class="btn btn-light">  <i class="fa fa-plus"></i> </button><input value="' +
                  e.addition +
                  '" style="flex: 1 1 90%; border: 1px solid #ccc;" class="addition custom-form-control" type="number" name="addition" placeholder="Add number"></div></th><th style="width:3%"><button id="removeFormulaLine" style="border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" class="btn btn-danger">  <i class="fa fa-trash"></i> </button></th></tr>'
                );
            }));
      } catch (e) {
        displayToast(
          "Error while restoring formula, please contact wooshark support subject error while restoring formula"
        );
      }
    }
  }


  function insertVariationsInSets(variations, postId, currentIndex) {
    var variationsSet = variations.slice(currentIndex, currentIndex + 10);
    if (variationsSet.length > 0) {
      jQuery.ajax({
        url: wooshark_params.ajaxurl,
        type: "POST",
        dataType: "JSON",
        data: {
          
                          nonce: wooshark_params.nonce,
          action: "theShark-alibay-insertVariations",
          variations: variationsSet,
          postId: postId
        },
        success: function (response) {
          if (response.error) {
            displayToastWithColor('Error while inserting variations index ' + currentIndex + ' ' + response.error_msg, "red");
            insertVariationsInSets(variations, postId, currentIndex + 10);

          } else {
            displayToastWithColor('Variations at index ' + currentIndex + ' inserated successfully', "green");
            insertVariationsInSets(variations, postId, currentIndex + 10);

          }
        },
        error: function (error) {
          if (error.responseText) {
            displayToast(error.responseText, "red");
          } else {
            displayToast(error, "red");
          }
          // insertVariationsInSets(variations, postId, currentIndex + 10);

        },
      });
    } else {
      stopLoading();
      jQuery(".lds-ring").remove();
    }
  }



function handleImageErrorLoading(e){
  if( e.imageUploadErrors  && e.imageUploadErrors.errors
    && e.imageUploadErrors.errors.length){
      displayToast(e.imageUploadErrors.errors[0], "red"); 
      displayToastLonger("To fix image loading Error, you can add the following line ---- define('ALLOW_UNFILTERED_UPLOADS', true);  --- to your wp-config.php file ", "red"); 
    }
}


  jQuery(document).on("click", "#totoButton", function (e) {
    jQuery("#loaderImporttoShop").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>')), jQuery("#loaderImporttoShop").show(), startLoading(); 
    handleFreeVersion();
    var t = [];
    let description = "";
    var variations = buildVariations(),
      title = jQuery("#customProductTitle").val() || jQuery("head").find("title").text(),
      shortDescription = jQuery("#shortDescription").val() || "",
      customPrice = jQuery("#customPrice").val() || "",
      salePrice = jQuery("#customSalePrice").val() || "";
    jQuery("#simpleSku").val();
    let categories = [];
    jQuery("#customProductCategory input:checked").each(function () {
      categories.push(jQuery(this).attr("value"))
    });
    var nameValueList = variations.NameValueList;
    let u = globalUrlProduct;
    t = getReviews(),
      description = quill.root.innerHTML
    variations = getItemSpecificfromTableAliexpress(variations);
    y = jQuery("#isPublish").prop("checked"),
      g = [];
    tagsProduct && tagsProduct.length && (g = tagsProduct),

      jQuery.ajax({
        url: wooshark_params.ajaxurl,
        type: "POST",
        dataType: "JSON",
        data: {
          
                          nonce: wooshark_params.nonce,
          action: "wooshark-insert-product-alibay",
          sku: currentSku.toString(),
          title: title,
          description: description || "",
          productType: "variable",
          images: images || [],
          categories: categories,
          regularPrice: customPrice.toString(),
          salePrice: salePrice.toString(),
          quantity: 1,
          attributes: nameValueList && nameValueList.length ? nameValueList.slice(0, 4) : [],
          isFeatured: jQuery("#isFeatured")[0].checked ? true : false,
          postStatus: jQuery("#isPublish")[0].checked ? "publish" : "draft",
          shortDescription: shortDescription || "",
          productUrl: u,
          reviews: t,
          tags: g,
          remoteCategories: getSelectedCategories(),
          selectedCurrency: getSelectedCurrency()
          // includeShippingCostIntoFinalPrice: true
          // importVariationImages: isImportImageVariationsSingleImport,
          // variations: variations.variations && variations.variations.length ? variations.variations.slice(0, 4) : [],

        },
        success: function (e) {
          if (e && e.error && e.error_msg) {
            displayToast(e.error_msg, "red");
            jQuery(".lds-ring").remove();
            stopLoading();

          } else if (e && !e.error && e.data) {
            displayToast(e.data, "green");
            displayToast("Start Loading variations by set of 10", "green");
            let postId = e.postId;
            insertVariationsInSets(variations.variations, postId, 0);
          }
          handleImageErrorLoading(e);
          
          // stopLoading();
          // jQuery(".lds-ring").remove();

          if (e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage")) {
            setTimeout(function () {
              window.open("https://sharkdropship.com/wooshark-dropshipping/", "_blank");
            }, 4000); // 4e3 is equivalent to 4000 milliseconds (4 seconds)
          }
        },
        error: function (e) {
          stopLoading();
          jQuery(".lds-ring").remove();

          if (e && e.responseText) {
            displayToast(e.responseText, "red");
          }
        }

      })
  })



  jQuery(document).on("click", "#resetFormula", function (e) { }),
    jQuery(document).on("click", "#addInterval", function (e) {
      jQuery("#formula tbody").append(
        '<tr><th style="width:15%"> <input class="custom-form-control" name="min" placeholder="Min price"></th><th style="width:2%">-</th><th style="width:15%"><input class="custom-form-control" name="max" placeholder="Max price"></th><th style="width:16%"><div style="display:flex"><button style="flex: 1 1 10%;border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" class="btn btn-light"> Increase by  </button><input style="flex: 1 1 78%; border: 1px solid #ccc;" class="multiply custom-form-control" type="number" name="multiply" placeholder="Increase percentage"><button style="flex: 1 1 10%;border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" class="btn btn-secondary">  <i class="fa fa-percent fa-2x"></i> </button></div></th><th style="width:15%"><div style="display:flex"><button style="flex: 1 1 10%;border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" class="btn btn-light">  <i class="fa fa-plus"></i> </button><input style="flex: 1 1 90%; border: 1px solid #ccc;" class="addition custom-form-control" type="number" name="addition" placeholder="Add number"></div></th><th style="width:3%"><button id="removeFormulaLine" style="border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" class="btn btn-danger">  <i class="fa fa-trash"></i> </button></th></tr>'
      );
    });
  let _savedConfiguration = {};

  function handleError(e) {
    stopLoading(),
      e && e.error && e.error_msg && displayToast(e.error_msg, "red"),
      e && !e.error && e.data && displayToast(e.data, "green");
  }
  function startLoadingText() {
    jQuery(
      '<h3  id="loading-variation" style="color:green;">  Loading .... </h3>'
    ).appendTo(".log-sync-product");
  }
  function stopLoadingText() {
    jQuery("#loading-variation").remove();
  }
  jQuery(document).on("click", "#removeFormulaLine", function (e) {
    jQuery(this)
      .parents("tr")
      .remove();
  })




  function loadCategories(groups) {
    const container = document.getElementById('shopCategories');
    let names = new Set(); // To store unique names

    if (groups && groups.length) {
      groups.forEach(group => {
        if (group.subGroupList && group.subGroupList.length) {
          group.subGroupList.forEach(subGroup => {
            if (subGroup) {
              if (!names.has(subGroup.name)) {
                names.add(subGroup.name);
                const div = document.createElement('div');
                div.className = 'category-item-remote';
                div.innerHTML = `<input type="checkbox" class="category-checkbox-remote" value="${subGroup.name}">${subGroup.name}`;

                // div.innerHTML = `<input type="checkbox" class="category-checkbox-remote">${subGroup.name}`;
                container.appendChild(div);
              }
            }

          });
        }else if(group.name){
          if (!names.has(group.name)) {
            names.add(group.name);
            const div = document.createElement('div');
            div.className = 'category-item-remote';
            div.innerHTML = `<input type="checkbox" class="category-checkbox-remote" value="${group.name}">${group.name}`;

            // div.innerHTML = `<input type="checkbox" class="category-checkbox-remote">${subGroup.name}`;
            container.appendChild(div);
          }
        }

      });

      // // Add scrollable container
      // const categoryContainer = document.createElement('div');
      // categoryContainer.className = 'category-container-remote';
      // categoryContainer.appendChild(container);
      // document.body.appendChild(categoryContainer);
    }

  }

  function getSelectedCategories() {
    const checkboxes = document.querySelectorAll('.category-checkbox-remote');
    const selectedCategories = [];

    checkboxes.forEach(checkbox => {
      if (checkbox.checked) {
        selectedCategories.push(checkbox.value);
      }
    });

    return selectedCategories;

    // You can also process the selected categories as needed
  }



  let productDetailsOldVariationsAndNewVariations = [];
  function logStartGettingPRoductDetails(e, t) {
    e || jQuery(".log-sync-product").empty(),
      e ||
      (jQuery(
        '<h3 style="color:green;"> ID: ' +
        t +
        " 1-  Getting existing Product variations .... </h3>"
      ).appendTo(".log-sync-product"),
        startLoadingText());
  }
  function logGettingNewProductVariations(e, t) {
    e ||
      (jQuery(
        '<h3 style="color:green;"> ID: ' +
        currentProductId +
        " 2- " +
        t +
        " Variations are loaded </h3>"
      ).appendTo(".log-sync-product"),
        jQuery(
          '<h3 style="color:green;"> ID: ' +
          currentProductId +
          " 3-  Getting new product variations ...</h3>"
        ).appendTo(".log-sync-product"),
        startLoadingText());
  }
  let variationsNotFound = 0;
  // jQuery(document).on("click", "#addToWaitingList", function (e) {
  //   (productId = jQuery(this)
  //     .parents(".card")
  //     .find("#sku")[0].innerText),
  //     productId
  //       ? importProductGloballyBulk(productId, !0)
  //       : displayToast("Cannot get product sku", "red");
  // }),
  jQuery(document).on("click", "#emptyWaitingListProduct", function (e) {
    jQuery("#emptyWaitingListProduct").remove(),
      jQuery("#importProductInWaitingListToShop").remove(),
      (globalWaitingList = []);
  });
  var globalWaitingList = [];
  function addToWaitingList(e) {
    globalWaitingList.push(e),
      jQuery("#importProductInWaitingListToShop").remove(),
      jQuery("#emptyWaitingListProduct").remove(),
      jQuery(
        '<button type="button" id="importProductInWaitingListToShop" style="position:fixed; border-raduis:0px; right: 1%; bottom: 60px; width:15%;z-index:9999" class="waitingListClass btn btn-primary btn-lg"><i class="fa fa-envelope fa-3px"> Import waiting List <span badge badge-primary>' +
        globalWaitingList.length +
        "</span></i></button>"
      ).appendTo(jQuery("html")),
      jQuery(
        '<button type="button" id="emptyWaitingListProduct" style=" position:fixed; border-raduis:0px; bottom: 10px; right: 1%;  width:15%;z-index:9999" class="waitingListClass btn btn-danger btn-lg"><i class="fa fa-trash-o fa-3px">  Reset Waiting list </span></i></button>'
      ).appendTo(jQuery("html"));
  }
  function removeProductFromWP(e) {
    e &&
      (startLoading(),
        jQuery.ajax({
          url: wooshark_params.ajaxurl,
          type: "POST",
          dataType: "JSON",
          data: { 
                            nonce: wooshark_params.nonce,
            action: "remove-product-from-wp-alibay", post_id: e },
          success: function (e) {
            e && e.error && e.error_msg && displayToast(e.error_msg, "red"),
              e && !e.error && e.data && displayToast(e.data, "green");
          },
          error: function (e) {
            console.log("****err", e),
              displayToast(e.responseText, "red"),
              stopLoading();
          },
          complete: function () {
            console.log("SSMEerr"), stopLoading();
          }
        }));
  }
  (indexStopLoading = 0),

    jQuery(document).on("click", "#set-product-to-draft", function (e) {
      removeProductFromWP(jQuery(this).attr("idOfPRoductToRemove"));
    }),
    jQuery(document).on("click", "#remove-product-from-draft", function (e) {
      removeProductFromWP(jQuery(this).attr("idOfPRoductToRemove"));
    }),
    jQuery(document).on("click", "#remove-product-from-wp", function (e) {
      removeProductFromWP(jQuery(this).attr("idOfPRoductToRemove"));
    }),
    jQuery(document).on("click", "#importAllProductOnThisPage", function (e) {
      let t = jQuery("#product-search-container .card");
      startLoading("loader3");
      for (var a = 0; a < t.length; a++)
        !(function (e) {
          window.setTimeout(function () {
            console.log("------------------11");
            let a = jQuery(t[e]).find("#sku")[0].innerText;
            a
              ? importProductGloballyBulk(a, !0)
              : displayToast("Cannot get product sku", "red");
          }, 3e3 * e);
        })(a);
    });
  var _isAuthorized_alibay = !1;
  function getReviewsFromHtml(e, t) {


    e &&
      ((xmlhttp = new XMLHttpRequest()),
        (xmlhttp.onreadystatechange = function () {
          if (4 == xmlhttp.readyState && 200 === xmlhttp.status)
            try {
              data = JSON.parse(xmlhttp.response).data;
              if ((jQuery("#table-reviews tbody").empty(), data && data.length)) {
                var e = "";
                jQuery("#loadMoreReviews").show(),
                  jQuery("#setRealRandomName").show(),
                  jQuery("#Load100Reviews").show(),
                  stopLoading(),
                  data.forEach(function (t) {
                    (e =
                      '<tr><td id="review" contenteditable>' +
                      t.review +
                      '</td><td id="username" contenteditable>' +
                      getUsername() +
                      '</td><td id="datecreation" contenteditable>' +
                      getCreationDate() +
                      '</td><td id="rating"><input type="number" min="1" max="5" value="5"></input></td><td id="email" contenteditable> emailNotVisible@unknown.com</td><td><button class="btn btn-danger" id="removeReview">X</button></td></tr></tr>'),
                      jQuery("#table-reviews tbody").append(e);
                  }),
                  jQuery("#table-reviews tr td[contenteditable]").css({
                    border: "1px solid #51a7e8",
                    "box-shadow":
                      "inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)"
                  });
              } else
                stopLoading(),
                  displayToast(
                    "No reviews for this sku using the preselected criteria"
                  );
            } catch (e) {
              stopLoading();
            }
        }),
        xmlhttp.open(
          "POST",
          hostname + ":8002/getReviewsFeomAliExpressOfficialApiBulk",
          !0
        ),
        xmlhttp.setRequestHeader("Content-Type", "application/json"),
        xmlhttp.send(JSON.stringify({ productId: e, multiplierIndex: 3, startIndex: 1, pageNo: t })));



    // e &&
    //   ((xmlhttp = new XMLHttpRequest()),
    //     (xmlhttp.onreadystatechange = function () {
    //       if (4 == xmlhttp.readyState && 200 === xmlhttp.status)
    //         try {
    //           data = JSON.parse(xmlhttp.response).data;
    //           if ((jQuery("#table-reviews tbody").empty(), data && data.length)) {
    //             var e = "";
    //             jQuery("#loadMoreReviews").show(),
    //               jQuery("#setRealRandomName").show(),
    //               jQuery("#Load100Reviews").show(),
    //               stopLoading(),
    //               data.forEach(function (t) {
    //                 (e =
    //                   '<tr><td id="review" contenteditable>' +
    //                   t.review +
    //                   '</td><td id="username" contenteditable>' +
    //                   getUsername() +
    //                   '</td><td id="datecreation" contenteditable>' +
    //                   getCreationDate() +
    //                   '</td><td id="rating"><input type="number" min="1" max="5" value="5"></input></td><td id="email" contenteditable> emailNotVisible@unknown.com (you can change this)</td><td><button class="btn btn-danger" id="removeReview">X</button></td></tr></tr>'),
    //                   jQuery("#table-reviews tbody").append(e);
    //               }),
    //               jQuery("#table-reviews tr td[contenteditable]").css({
    //                 border: "1px solid #51a7e8",
    //                 "box-shadow":
    //                   "inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)"
    //               });
    //           } else
    //             stopLoading(),
    //               displayToast(
    //                 "No reviews for this sku using the preselected criteria"
    //               );
    //         } catch (e) {
    //           stopLoading();
    //         }
    //     }),
    //     xmlhttp.open(
    //       "POST",
    //       hostname + ":8002/getReviewsFeomAliExpressOfficialApi",
    //       !0
    //     ),
    //     xmlhttp.setRequestHeader("Content-Type", "application/json"),
    //     xmlhttp.send(JSON.stringify({ productId: e, pageNo: t })));
  }
  function getAlreadyImportedProducts(e) {
    jQuery.ajax({
      url: wooshark_params.ajaxurl,
      type: "POST",
      dataType: "JSON",
      data: { 
                        nonce: wooshark_params.nonce,
        action: "get-already-imported-products-alibay", listOfSkus: e },
      success: function (e) {
        let t = e;
        t && t.length && displayAlreadyImportedIcon(t),
          console.log("****response", e);
      },
      error: function (e) {
        e.responseText
          ? (console.log("****err", e), stopLoading())
          : (console.log("****err", e),
            displayToast("Error while getting list of products", "red"),
            stopLoading());
      },
      complete: function () {
        console.log("SSMEerr"), stopLoading();
      }
    });
  }
  function displayAlreadyImportedIcon(e) {
    if (e && e.length) {
      let a = e.map(function (e) {
        return e.sku;
      }),
        r = jQuery("#product-search-container .card");
      for (var t = 0; t < r.length; t++) {
        let e = jQuery(r[t]).find("#sku")[0].innerText;
        if (a.indexOf(e) > -1) {
          jQuery(
            '<div><a  style="width:80%; margin-top:5px" id="alreadyImported" class=" btn btn-warning">Already imported</a></div>'
          ).appendTo(jQuery(r[t]));
        }
      }
    }
  }




  function getScale(e) {
    return e.scale_name || "";
  }










  function fillTags(tagList) {

    var tags = tagList.split(' ');

    // Iterate through the tags and add them individually
    tags.forEach(function (tag) {
      // Trim any extra whitespace from the tag
      var trimmedTag = tag.trim();
      tagsProduct.push(tag);
      // Add the trimmed tag as a new div with a remove button
      jQuery("#tagInputDisplayed").append(
        jQuery(
          '<div style="width: fit-content;padding: 10px;background-color: #212148;border-radius: 10px;margin: 10px;">' +
          trimmedTag +
          '<button class="btn btn-danger removeTag">X</button></div>'
        )
      );
    });

  }

  jQuery(document).on("click", ".removeTag", function (e) {
    // Remove the parent div when the remove button is clicked
    jQuery(this).parent().remove();
  });

  function restoreConfiguration() {
    let configurationData = {};
    jQuery.ajax({
      url: wooshark_params.ajaxurl,
      type: "POST",
      dataType: "JSON",
      data: { 
                        nonce: wooshark_params.nonce,
        action: "restoreConfiguration-alibay" },
      success: function (response) {
        let commonConfiguration, singleUpdateConfiguration, singleImportConfiguration, bulkCategories, savedFormula;


        if (response && response._savedConfiguration_alibay && response._savedConfiguration_alibay.commonConfiguration) {
          configurationData = response._savedConfiguration_alibay;
          commonConfiguration = configurationData.commonConfiguration;
          singleUpdateConfiguration = configurationData.sinleUpdateConfiguration;
          singleImportConfiguration = configurationData.singleImportConfiguration;
          bulkCategories = configurationData.bulkCategories;
          savedFormula = configurationData.savedFormula;

          


          jQuery("#applyPriceFormulawhileImporting").prop("checked", true);


          restoreFormula(savedFormula);

          getCategories(function (categories) {
            if (savedCategories && savedCategories.length) {
              jQuery("#table-categories tbody").empty();
              savedCategories.forEach(function (category) {
                jQuery("#table-categories tbody").append('<tr><td style="width:20%">' + category.term_id + '</td><td style="width:20%">' + category.name + '</td><td  style="width:20%">' + category.count + ' </td></td><td  style="width:40%"><button class="btn btn-primary" style="width:100%" id="updateProductOfThisCategory" categoryID="' + category.term_id + '"> Update Products of this category</button></td></tr>');
              });

              if (bulkCategories && bulkCategories.length && savedCategories && savedCategories.length) {
                jQuery("#bulkCategories").empty();
                savedCategories.forEach(function (category, index) {
                  var checkboxHTML = '<div class="checkbox"><label><input id="category' + category.term_id + '" type="checkbox" style="width:17px; height:17px" class="chk" value="' + category.term_id + ' "/>' + category.name + "</label>";
                  jQuery("#bulkCategories").append(jQuery(checkboxHTML));
                });
                bulkCategories.forEach(function (categoryID) {
                  jQuery("#category" + categoryID).prop("checked", true);
                });
              }
            } else {
              getCategories(function (categories) {
                if (savedCategories && savedCategories.length) {
                  jQuery("#table-categories tbody").empty();
                  savedCategories.forEach(function (category) {
                    jQuery("#table-categories tbody").append('<tr><td style="width:20%">' + category.term_id + '</td><td style="width:20%">' + category.name + '</td><td  style="width:20%">' + category.count + ' </td></td><td  style="width:40%"><button class="btn btn-primary" style="width:100%" id="updateProductOfThisCategory" categoryID="' + category.term_id + '"> Update Products of this category</button></td></tr>');
                  });

                  if (bulkCategories && bulkCategories.length && savedCategories && savedCategories.length) {
                    jQuery("#bulkCategories").empty();
                    savedCategories.forEach(function (category, index) {
                      var checkboxHTML = '<div class="checkbox"><label><input id="category' + category.term_id + '" type="checkbox" style="width:17px; height:17px" class="chk" value="' + category.term_id + ' "/>' + category.name + "</label>";
                      jQuery("#bulkCategories").append(jQuery(checkboxHTML));
                    });
                    bulkCategories.forEach(function (categoryID) {
                      jQuery("#category" + categoryID).prop("checked", true);
                    });
                  }
                }
              });
            }
          });
        } else {
          getCategories(function (categories) {
            savedCategories && savedCategories.length && (jQuery("#table-categories tbody").empty(), savedCategories.forEach(function (category) {
              jQuery("#table-categories tbody").append('<tr><td style="width:20%">' + category.term_id + '</td><td style="width:20%">' + category.name + '</td><td  style="width:20%">' + category.count + ' </td></td><td  style="width:40%"><button class="btn btn-primary" style="width:100%" id="updateProductOfThisCategory" categoryID="' + category.term_id + '"> Update Products of this category</button></td></tr>');
            }), bulkCategories && bulkCategories.length && savedCategories && savedCategories.length ? (jQuery("#bulkCategories").empty(), savedCategories.forEach(function (category, index) {
              var checkboxHTML = '<div class="checkbox"><label><input id="category' + category.term_id + '" type="checkbox" style="width:17px; height:17px" class="chk" value="' + category.term_id + ' "/>' + category.name + "</label>";
              jQuery("#bulkCategories").append(jQuery(checkboxHTML));
            }), bulkCategories.forEach(function (categoryID) {
              jQuery("#category" + categoryID).prop("checked", true);
            })) : (jQuery("#bulkCategories").empty(), savedCategories.forEach(function (category, index) {
              var checkboxHTML = '<div class="checkbox"><label><input id="category' + category.term_id + '" type="checkbox" style="width:17px; height:17px" class="chk" value="' + category.term_id + ' "/>' + category.name + "</label>";
              jQuery("#bulkCategories").append(jQuery(checkboxHTML));
            })));
          });
        }
      },
      error: function (error) {
        displayToast("Error while retrieving configuration from server, please reload your page");
      },
      complete: function () { }
    });
  }



  jQuery(document).on("click", "#loadMoreReviews", function (e) {
    getReviewsFromHtml(currentProductModalDisplayed, ++currentPageReviews);
  }),
    jQuery(document).on("click", "#searchCategoryByNameInput", function (e) {
      let t = jQuery("#searchCategoryByNameInput").val();
      t &&
        jQuery.ajax({
          url: wooshark_params.ajaxurl,
          type: "POST",
          dataType: "JSON",
          data: {
            
                            nonce: wooshark_params.nonce,
            action: "search-category-by-name-alibay",
            searchCategoryByNameInput: t
          },
          success: function (e) {
            console.log("response----", e);
          },
          error: function (e) {
            e.responseText
              ? (console.log("****err", e),
                displayToast(e.responseText, "red"),
                stopLoading())
              : (console.log("****err", e),
                displayToast("Error while getting list of products", "red"),
                stopLoading());
          },
          complete: function () {
            console.log("SSMEerr"), stopLoading();
          }
        });
    }),
    jQuery(document).on("click", "#searchCategories", function (e) {
      jQuery("#customProductCategory input:not(:checked)").each(function () {
        jQuery(this)
          .parent()
          .remove();
      });
      let t = jQuery("#categorySearchKeyword").val();
      t
        ? savedCategories &&
        savedCategories.length &&
        savedCategories.forEach(function (e, a) {
          e &&
            e.name &&
            e.name.includes(t) &&
            ((items =
              '<div class="checkbox"><label><input type="checkbox" value="' +
              e.term_id +
              '"/>' +
              e.name +
              "</label>"),
              jQuery("#customProductCategory").append(jQuery(items)));
        })
        : savedCategories &&
        savedCategories.length &&
        savedCategories.forEach(function (e, t) {
          (items =
            '<div class="checkbox"><label><input type="checkbox" value="' +
            e.term_id +
            '"/>' +
            e.name +
            "</label>"),
            jQuery("#customProductCategory").append(jQuery(items));
        });
    })

  let globalTitle = "";

  function getVariations(e, t) {
    e && e.length ? (jQuery("#applyPriceFormula").show(), jQuery("#applyPriceFormulaRegularPrice").show(), jQuery("#importSalePricecheckbox").show(), jQuery("#applyCharmPricingConainer").show(), jQuery("#priceContainer").hide(), jQuery("#skuContainer").hide(), jQuery("#productWeightContainer").hide(), jQuery("#productType").text("Variable Product"), jQuery("#no-variations").hide(), e 
    && e.length > 100,
    // && displayToast("This product has more " + e.length + " variations, only the first 100 variations will be imported", "orange"), 
    e.forEach(function (e) {
      let t = [];
      let titleAttribtues = globalTitle;

      if (e && e.attributesVariations && e.attributesVariations.length) {
        var a = "";
        e.attributesVariations && e.attributesVariations.length && e.attributesVariations[0] && e.attributesVariations[0].name && e.attributesVariations[0].image ? (t = [e.attributesVariations[0].image],
          a = a + '<td imagePath="'+e.attributesVariations[0].image+'"><img height="50px" width="50px" src="' + e.attributesVariations[0].image + '"></td>') : e.attributesVariations && e.attributesVariations.length && e.attributesVariations[1] && e.attributesVariations[1].name && e.attributesVariations[1].image ? (a = a + '<td><img height="50px" width="50px" src="' + e.attributesVariations[1].image + '"></td>'
            , t = [e.attributesVariations[1].image]) : e.attributesVariations && e.attributesVariations.length && e.attributesVariations[2] && e.attributesVariations[2].name && e.attributesVariations[2].image ? (t = [e.attributesVariations[2].image], a = a + '<td><img height="50px" width="50px" src="' + e.attributesVariations[2].image + '"></td>') : a += "<td></td>"
          , e.attributesVariations.forEach(function (e, t) {
            titleAttribtues = titleAttribtues + " - " + e.name + " : " + e.value,
              a = a + '<td contenteditable name="' + e.name + '">' + e.value + "</td>"
          });
        var i = e.regularPrice || e.salePrice,
          r = e.salePrice || e.regularPrice;
        jQuery("#productWeight").val();
        a = a + "<td id='singleQuantity' contenteditable>" + e.availQuantity + "</td><td id='singleRegularPrice' contenteditable>" + i + "</td><td id='singleSalePrice' contenteditable>" + r + '</td><td id="singleAsin">' + e.SKU + '</td><td id="singleTitle" contenteditable>' + titleAttribtues + '</td><td><button id="removeVariation"style="background-color:red" class="button-5">X</button></td><td><button id="insertProductAsSimple" class="button-5" style="width:220px">Import As single(Dropship) <span class="newLoaderSimple"></span> </button></td><td><button id="insertProductAsAffiliate" class="button-6" style="width:220px">Import As single (Affiliate) <span class="newLoaderAffiliate"></span> </button></td><td id="singleImages" style="display:none">' + t + "</td>",
          jQuery("#table-variations tbody").append(jQuery("<tr>" + a + "</tr>")),
          jQuery("#table-variations tr td[contenteditable]").css({
            border: "1px solid #51a7e8",
            "box-shadow": "inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)"
          })
      }
    }), applyPriceFormulaDefault()) : (jQuery("#simpleSku").val(t), jQuery('[href="#menu5"]').closest("li").hide(), jQuery("#no-variations").show(), jQuery("#applyPriceFormula").hide(), jQuery("#applyPriceFormulaRegularPrice").hide(), jQuery("#importSalePricecheckbox").hide(), jQuery("#applyCharmPricingConainer").hide(), jQuery("#priceContainer").show(), jQuery("#skuContainer").show(), jQuery("#productType").text("Simple Product"))
  }

  function getAttributes(e) {
    jQuery("#table-attributes tbody tr").remove(), jQuery("#table-variations thead tr").remove(), jQuery("#table-variations tbody tr").remove();
    var t = e.NameValueList;
    attributesNamesArray = t.map(function (e) {
      return e.name
    });
    var a = "",
      i = "";
    t && t.length && (t.forEach(function (e) {
      e.name &&
        (a = "<td>" + e.name + '</td><td style="width:50%" contenteditable><span> ' + e.value + "</span></td>", i = i + '<td  name="' + e.name + '">' + e.name + "</td>"),
        jQuery("#table-attributes tbody").append(jQuery("<tr>" + a + '<td><button id="removeVariations" class="btn btn-danger">X</btton><td></tr>'))
    }),
      jQuery("#table-variations thead").append(jQuery("<tr><td>Image</td>" + i + "<td style='font-weight: 800'>quantity</td><td style='font-weight: 800'>Price</td><td style='font-weight: 800'>Sale price</td><td style='font-weight: 800'>ASIN</td><td>Title</td><td style='font-weight: 800'>Remove</td><td><button disabled id='' class='button-5' style='width:220px'>Import As dropshipping  <span class='newLoaderAllSimple'></span></button></td><td><button disabled id='' class='button-6' style='width:220px;'>Import as affiliate <span class='newLoaderAllAffiliate'></span></button></td></tr>")))
  }


  // jQuery(document).on("click", "#insertProductAsSimple", function (e) {
  //   jQuery(this).parents("tr").find(".newLoaderSimple").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>')),
  //     insertProductAsSingle({
  //       images: jQuery(this).parents("tr").find("#singleImages").text() ? jQuery(this).parents("tr").find("#singleImages").text().split(",") : [],
  //       isAffiliate: !1,
  //       asin: jQuery(this).parents("tr").find("#singleAsin").text(),
  //       regularPice: jQuery(this).parents("tr").find("#singleRegularPrice").text(),
  //       quantity: jQuery(this).parents("tr").find("#singleQuantity").text(),
  //       salePrice: jQuery(this).parents("tr").find("#singleSalePrice").text(),
  //       productUrl: globalUrlProduct,
  //       title: jQuery(this).parents("tr").find("#singleTitle").text()
  //     })
  // });

  jQuery(document).on("click", "#insertProductAsSimple", function (e) {
    jQuery(this).parents("tr").find(".newLoaderSimple").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'));
    insertProductAsSingle({
      images: jQuery(this).parents("tr").find("#singleImages").text() ? jQuery(this).parents("tr").find("#singleImages").text().split(",") : [],
      isAffiliate: !1,
      asin: jQuery(this).parents("tr").find("#singleAsin").text(),
      regularPice: jQuery(this).parents("tr").find("#singleRegularPrice").text(),
      quantity: jQuery(this).parents("tr").find("#singleQuantity").text(),
      salePrice: jQuery(this).parents("tr").find("#singleSalePrice").text(),
      productUrl: globalUrlProduct,
      title: jQuery(this).parents("tr").find("#singleTitle").text(),
      variations: buildVariationsForSingleImport(jQuery(this).parents("tr"))
    })
  });


  function getItemSpecificfromTableModal(e) {
    var t = {
      variations: [],
      NameValueList: []
    };
    jQuery('#table-specific tr').each(function (index, element) {
      t.NameValueList.push({
        name: element.cells[0].textContent.toLowerCase().replace(/ /g, "-"),
        values: element.cells[1].textContent.split(","),
        variation: false,
        visible: true
      })
    });

    return jQuery("#table-attributes tr").each(function (e, a) {
      e && t.NameValueList.push({
        name: a.cells[0].textContent.toLowerCase().replace(/ /g, "-"),
        values: a.cells[1].textContent.split(","),
        variation: !0,
        visible: !0
      })
    }), t
  }



  function insertProductAsAffiliate(e) {
    let t = jQuery("input[value=titleASIN]")[0].checked,
      a = jQuery("input[value=galleryASIN]")[0].checked;
    var i = [];
    jQuery("#customProductCategory input:checked").each(function () {
      i.push(jQuery(this).attr("value"))
    });
    var r = getReviews(),
      o = (jQuery("#customPrice").val(), window.location.href),
      n = o.indexOf("/dp/");
    n < 0 && (n = o.indexOf("/gp/")), n += 4;
    window.location.href;
    var l = jQuery("#customProductTitle").val();
    let s = [];
    tagsProduct && tagsProduct.length && (s = tagsProduct);
    var c = quill.root.innerHTML,
      u = i;
    customVariations = buildVariationsForSingleImportAffil(),
      customVariations = getItemSpecificfromTableModal(customVariations);
    var d = jQuery("#shortDescription").val(),
      p = jQuery("#isPublish")[0].checked,
      y = (e.asin, jQuery("#isFeatured")[0].checked);
    if (generalPreferences.importReviewsGeneral || (r = []), generalPreferences.importDescriptionGeneral || (c = ""), generalPreferences.textToReplace && generalPreferences.textToBeReplaced) {
      var m = new RegExp(generalPreferences.textToBeReplaced, "g");
      c = c.replace(m, generalPreferences.textToReplace)
    }
    var g = !0;
    generalPreferences.importSalePriceGeneral || (g = !1);
    jQuery("#customSalePrice").val();
    let h = {};
    handleFreeVersion();

    h = {
      title: t && e.title ? e.title : l,
      currentPrice: e.readyState,
      originalPrice: e.regularPice,
      description: c,
      images: a && e.images && e.images.length ? e.images : images,
      totalAvailQuantity: e.quantity || 1,
      productUrl: globalUrlProduct,
      isPublish: p,
      productCategoies: u,
      productWeight: "",
      reviews: r,
      shortDescription: d,
      simpleSku: e.asin,
      importSalePrice: g,
      salePrice: e.salePrice,
      featured: y,
      tags: s,
      affiliateLink: e.isAffiliate ? e.productUrl : "",
      button_text: e.isAffiliate ? jQuery("#customBuyNow").val() : "",
      variations: customVariations
    }, jQuery.ajax({
      url: wooshark_params.ajaxurl,
      type: "POST",
      dataType: "JSON",
      data: {
        
                        nonce: wooshark_params.nonce,
        action: "theShark_alibay_insertProductInWoocommerceAffiliate",
        sku: h.simpleSku.toString(),
        title: h.title,
        description: h.description || "",
        images: h.images || [],
        categories: h.productCategoies,
        regularPrice: h.originalPrice.toString(),
        salePrice: h.salePrice.toString(),
        // quantity: h.totalAvailQuantity,
        productType: "external",
        attributes: customVariations.NameValueList || [],
        variations: [],
        isFeatured: jQuery("#isFeatured")[0].checked,
        postStatus: jQuery("#isPublish")[0].checked ? "publish" : "draft",
        shortDescription: h.shortDescription || "",
        productUrl: h.productUrl,
        importVariationImages: !0,
        reviews: h.reviews,
        tags: h.tags,
        affiliateLink: e.isAffiliate ? e.productUrl : "",
        remoteCategories: getSelectedCategories(),
        selectedCurrency: getSelectedCurrency()


        // includeShippingCostIntoFinalPrice: !1
      },
      success: function (e) {
        e && e.error && e.error_msg && displayToast(e.error_msg, "red"), 
        e && !e.error && e.data && displayToast(e.data, "green"),   stopLoading(), 
        jQuery(".lds-ring").remove(), 
        e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage")
        && setTimeout(function () {
          window.open("https://sharkdropship.com/wooshark-dropshipping/", "_blank")
        }, 4e3)

       handleImageErrorLoading(e);
      },
      error: function (e) {
        jQuery(".lds-ring").remove(), stopLoading(), e && e.responseText && displayToast(e.responseText, "red")
      }
    })
  }


 


  function insertProductAsSingle(e) {
    let t = jQuery("input[value=titleASIN]")[0].checked,
      a = jQuery("input[value=galleryASIN]")[0].checked;
    var i = [];
    jQuery("#customProductCategory input:checked").each(function () {
      i.push(jQuery(this).attr("value"))
    });
    var r = getReviews(),
      o = (jQuery("#customPrice").val(), window.location.href),
      n = o.indexOf("/dp/");
    n < 0 && (n = o.indexOf("/gp/")), n += 4;
    window.location.href;
    var l = jQuery("#customProductTitle").val();
    let s = [];
    tagsProduct && tagsProduct.length && (s = tagsProduct);
    var c = quill.root.innerHTML,
      u = i;
    customVariations = getItemSpecificfromTableModal(e.variations);
    var d = jQuery("#shortDescription").val(),
      p = jQuery("#isPublish")[0].checked,
      y = (e.asin, jQuery("#isFeatured")[0].checked);
    if (generalPreferences.importReviewsGeneral || (r = []), generalPreferences.importDescriptionGeneral || (c = ""), generalPreferences.textToReplace && generalPreferences.textToBeReplaced) {
      var m = new RegExp(generalPreferences.textToBeReplaced, "g");
      c = c.replace(m, generalPreferences.textToReplace)
    }
    var g = !0;
    generalPreferences.importSalePriceGeneral || (g = !1);
    jQuery("#customSalePrice").val();
    let h = {};
   
    handleFreeVersion(); 
    h = {
      title: t && e.title ? e.title : l,
      currentPrice: e.readyState,
      originalPrice: e.regularPice,
      description: c,
      images: a && e.images && e.images.length ? e.images : images,
      totalAvailQuantity: e.quantity || 1,
      productUrl: globalUrlProduct,
      isPublish: p,
      productCategoies: u,
      productWeight: "",
      reviews: r,
      shortDescription: d,
      simpleSku: e.asin,
      importSalePrice: g,
      salePrice: e.salePrice,
      featured: y,
      tags: s,


    }, jQuery.ajax({
      url: wooshark_params.ajaxurl,
      type: "POST",
      dataType: "JSON",
      data: {
        
                        nonce: wooshark_params.nonce,
        action: "wooshark-insert-product-alibay",
        sku: h.simpleSku.toString(),
        title: h.title,
        description: h.description || "",
        images: h.images || [],
        categories: h.productCategoies,
        regularPrice: h.originalPrice.toString(),
        salePrice: h.salePrice.toString(),
        quantity: h.totalAvailQuantity,
        productType: "variable",
        attributes: e.variations.NameValueList || [],
        // variations: [],
        isFeatured: jQuery("#isFeatured")[0].checked,
        postStatus: jQuery("#isPublish")[0].checked ? "publish" : "draft",
        shortDescription: h.shortDescription || "",
        productUrl: h.productUrl,
        importVariationImages: !0,
        reviews: h.reviews,
        tags: h.tags,
        variations: e.variations.variations,
        includeShippingCostIntoFinalPrice: !1,
        remoteCategories: getSelectedCategories(),
        selectedCurrency: getSelectedCurrency()

      },
      success: function (e) {
        // insertVariationsInSets_ebay(e.variations, e.postId, 0);
        e && e.error && e.error_msg && displayToast(e.error_msg, "red"), e 
        && !e.error && e.data && displayToast(e.data, "green"), stopLoading(), jQuery(".lds-ring").remove(), 
     
        e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage") && setTimeout(function () {
          window.open("https://sharkdropship.com/wooshark-dropshipping/", "_blank")
        }, 4e3)

        handleImageErrorLoading(e);


      },
      error: function (e) {
        jQuery(".lds-ring").remove(), stopLoading(), e && e.responseText && displayToast(e.responseText, "red")
      }
    })
  }

  function buildVariationsForSingleImportAffil() {
    return {
      variations: [],
      NameValueList: []
    }
  }

  function buildVariationsForSingleImport(row) {
    var e = {
      variations: [],
      NameValueList: [],
    };

    if (jQuery(row).find('#singleAsin').text() == 'undefined') {
      jQuery('.lds-ring').remove();
      displayToast('Cannot Insert the product because of missing / undefined sku reference, please fill the column sku', 'red');
      return;
    }
    // Build variation from the provided row
    var variation = {
      SKU: jQuery(row).find('#singleAsin').text() ? `${jQuery(row).find('#singleAsin').text()}sub` : '',
      availQuantity: parseInt(jQuery(row).find('#singleQuantity').text()),
      regularPrice: parseFloat(jQuery(row).find('#singleRegularPrice').text()),
      salePrice: parseFloat(jQuery(row).find('#singleSalePrice').text()),
      attributesVariations: [],
    };

    // Iterate through the columns with 'name' attribute (starting from the fifth column)
    jQuery(row)
      .find('td[name]')
      .each(function () {
        var columnName = jQuery(this).attr('name');
        var columnValue = jQuery(this).text();
        var attribute = {
          name: columnName.toLowerCase().replace(/ /g, '-').replace("'", '-'),
          value: columnValue,
          image: jQuery(this).parent().find('td[imagePath]').attr('imagePath'), // Use parent() to find imagePath in the same row
        };
        variation.attributesVariations.push(attribute);

        // Build or update NameValueList based on the 'name' attribute
        var nameExists = false;
        for (var i = 0; i < e.NameValueList.length; i++) {
          if (e.NameValueList[i].name === attribute.name) {
            e.NameValueList[i].values.push(attribute.value);
            nameExists = true;
            break;
          }
        }
        if (!nameExists) {
          e.NameValueList.push({
            name: attribute.name,
            values: [attribute.value],
            variation: true,
            visible: true,
          });
        }
      });

    // Add the variation to the array
    e.variations.push(variation);

    // Now, 'e' contains the variations and NameValueList based on the provided row
    console.log(e);
    return e;
  }

  jQuery(document).on("click", "#insertProductAsAffiliate", function (e) {
    let t = jQuery("#affiliateLinkUrl").val();
    globalUrlProduct = t;
    if (t.includes("https")) {
      jQuery(this).parents("tr").find(".newLoaderAffiliate").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>')),
        insertProductAsAffiliate({
          images: jQuery(this).parents("tr").find("#singleImages").text() ? jQuery(this).parents("tr").find("#singleImages").text().split(",") : [],
          isAffiliate: !0,
          asin: jQuery(this).parents("tr").find("#singleAsin").text(),
          regularPice: jQuery(this).parents("tr").find("#singleRegularPrice").text(),
          quantity: jQuery(this).parents("tr").find("#singleQuantity").text(),
          salePrice: jQuery(this).parents("tr").find("#singleSalePrice").text(),
          productUrl: t,
          title: jQuery(this).parents("tr").find("#singleTitle").text()
        })
    } else displayToast("Please open the advanced settings and fill the affiliate link", "red")
  })

  jQuery(document).on("click", "#openAdvancedSettings", function (e) {
    jQuery("#advancedVariationsCapa").toggle("slow"), jQuery("#advancedVariationsCapa").css({
      display: "flex"
    })
  })




  // 

  jQuery(document).on("click", "#aliexpress-button", function (e) {
    loadAllProductsAliExpress();
  });

  // Example of usage:
  // Assuming you have buttons with IDs "simple-button," "variable-button," and "external-button"
  jQuery("#simple-button").click(function () {
    loadProductsByType("simple", 1); // Replace "simple" with the desired product type
  });

  jQuery("#variable-button").click(function () {
    loadProductsByType("variable", 1); // Replace "variable" with the desired product type
  });

  jQuery("#external-button").click(function () {
    loadProductsByType("external", 1); // Replace "external" with the desired product type
  });





  jQuery(document).on("click", "#ebay-button", function (e) {
    loadAllProductsEbay();
  });


  jQuery(document).on("click", "#amazon-button", function (e) {
    loadAllProductsAmazon();
  });


  jQuery(document).on("click", "#searchBySku", function (e) {
    jQuery(".loaderImporttoShopProducts").show()

    let t = jQuery("#skusearchValue").val();
    t
      ? jQuery.ajax({
        url: wooshark_params.ajaxurl,
        type: "POST",
        dataType: "JSON",
        data: { 
                          nonce: wooshark_params.nonce,
          action: "search-product-by-sku-alibay", searchSkuValue: t },
        success: function (response) {
          displayAllProductsIntoTable(response);
          jQuery(".loaderImporttoShopProducts").hide()

        },
        error: function (e) {
          jQuery(".loaderImporttoShopProducts").hide()

        },
        complete: function () {
          jQuery(".loaderImporttoShopProducts").hide()
        }
      })
      : getAllProducts(1);
  });




 


  function prepareReviewModal() {
    let tempalte = jQuery(`
      <div class="modal fade" id="myModal" style="margin-top: 4%;">
      <div class="modal-dialog" style="max-width:75vw; width:75vw">
      <div class="modal-content">
      <div class="modal-header" style="display:flex;     justify-content: space-between;      ">
        <h5 class="modal-title" id="exampleModalLabel">Add Review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Table to enter review details -->
        <table class="table">
          <thead>
            <tr>
              <th>Review</th>
              <th>Username</th>
              <th>Date</th>
              <th>Email</th>
              <th>Rating</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><textarea class="form-control" rows="3"></textarea></td>
              <td><input type="text" class="form-control"></td>
              <td><input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"></td>
              <td><input type="email" class="form-control"></td>
              <td><input type="number" class="form-control" min="1" max="5"></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save Review</button>
      </div>
    </div>
  </div>
  </div>
  `).appendTo(jQuery("#modal-container"))

  }

  // get products

  function getAllProducts(page) {

    jQuery.ajax({
      url: '/wp-admin/admin-ajax.php',
      type: 'POST',
      data: {
        
                        nonce: wooshark_params.nonce,
        action: 'get_products-alibay',
        page: page, // Change page number as needed
      },
      success: function (response) {
        // Handle the response data (e.g., display products)
        // console.log(response);
        displayAllProductsIntoTable(response)
        jQuery(".loaderImporttoShopProducts").hide()

      },
      error: function (error) {
        console.error(error);
        jQuery(".loaderImporttoShopProducts").hide()

      }
    });



  }




  function loadProductsByType(productType, page) {
    // Show loading indicator
    jQuery(".loaderImporttoShopProducts").show();

    // Send an AJAX request to the backend PHP script
    jQuery.ajax({
      url: '/wp-admin/admin-ajax.php',
      type: 'POST',
      data: {
        
                        nonce: wooshark_params.nonce,
        action: 'get_products_by_type_alibay', // Use the appropriate backend action
        product_type: productType, // Pass the product type you want to retrieve
        page: page, // Change page number as needed
      },
      success: function (response) {
        displayAllProductsIntoTableByType(response);
        jQuery(".loaderImporttoShopProducts").hide();
      },
      error: function (error) {
        console.error(error);
        jQuery(".loaderImporttoShopProducts").hide();
      }
    });
  }








  function loadAllProductsAliExpress(page) {
    // jQuery(".loaderImporttoShopProducts").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'))
    jQuery(".loaderImporttoShopProducts").show()

    // Send an AJAX request to the backend PHP script
    jQuery.ajax({
      url: '/wp-admin/admin-ajax.php',
      type: 'POST',
      data: {
        
                        nonce: wooshark_params.nonce,
        action: 'get_aliexpress_products_alibay',
        page: page, // Change page number as needed
      },
      success: function (response) {
        displayAliExpressProductsIntoTable(response);
        jQuery(".loaderImporttoShopProducts").hide()

      },
      error: function (error) {
        console.error(error);
        jQuery(".loaderImporttoShopProducts").hide()

      }
    });
  }



  function loadAllProductsEbay(page) {
    // jQuery(".loaderImporttoShopProducts").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'))
    jQuery(".loaderImporttoShopProducts").show()
    // e.preventDefault();

    // Send an AJAX request to the backend PHP script
    jQuery.ajax({
      url: '/wp-admin/admin-ajax.php',
      type: 'POST',
      data: {
        
                        nonce: wooshark_params.nonce,
        action: 'get_aliexpress_products_alibay_ebay',
        page: page, // Change page number as needed
      },
      success: function (response) {
        displayEbayProductsIntoTable(response);
        jQuery(".loaderImporttoShopProducts").hide()

      },
      error: function (error) {
        console.error(error);
        jQuery(".loaderImporttoShopProducts").hide()

      }
    });
  }





  function loadAllProductsAmazon(page) {
    // jQuery(".loaderImporttoShopProducts").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'))
    jQuery(".loaderImporttoShopProducts").show()
    // e.preventDefault();

    // Send an AJAX request to the backend PHP script
    jQuery.ajax({
      url: '/wp-admin/admin-ajax.php',
      type: 'POST',
      data: {
        
                        nonce: wooshark_params.nonce,
        action: 'get_aliexpress_products_alibay_amazon',
        page: page, // Change page number as needed
      },
      success: function (response) {
        displayAmazonProductsIntoTable(response);
        jQuery(".loaderImporttoShopProducts").hide()

      },
      error: function (error) {
        console.error(error);
        jQuery(".loaderImporttoShopProducts").hide()

      }
    });
  }




  // display products 



  function displayAllProductsIntoTableByType(response) {
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
      var listItem = jQuery('<li class="page-item-products-all-type"><a class="page-link page-link-all-type" href="#" data-page="' + i + '">' + i + '</a></li>');
      pagination.append(listItem);
    }
    jQuery('#pagination-container').empty().append(pagination);
    // },
  }

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
      var listItem = jQuery('<li class="page-item-products-aliexpress"><a class="page-link page-link-aliexpress" href="#" data-page="' + i + '">' + i + '</a></li>');
      pagination.append(listItem);
    }
    jQuery('#pagination-container').empty().append(pagination);

  }






  function displayEbayProductsIntoTable(response) {
    var totalPages = response.total_pages; // Get the total number of pages


    createProductTable(response);

    // Add pagination controls
    var pagination = jQuery('<ul class="pagination">');
    for (var i = 1; i <= totalPages; i++) {
      var listItem = jQuery('<li class="page-item-products-ebay"><a class="page-link page-link-ebay" href="#" data-page="' + i + '">' + i + '</a></li>');
      pagination.append(listItem);
    }
    jQuery('#pagination-container').empty().append(pagination);

  }

  function displayAmazonProductsIntoTable(response) {
    var totalPages = response.total_pages; // Get the total number of pages


    createProductTable(response);

    jQuery('#products-wooshark').empty().append(productsTable);

    // Add pagination controls
    var pagination = jQuery('<ul class="pagination">');
    for (var i = 1; i <= totalPages; i++) {
      var listItem = jQuery('<li class="page-item-products-amazon"><a class="page-link page-link-amazon" href="#" data-page="' + i + '">' + i + '</a></li>');
      pagination.append(listItem);
    }
    jQuery('#pagination-container').empty().append(pagination);

  }




  // handle pagination 


  jQuery(document).on("click", ".page-link-all", function (e) {
    jQuery(".loaderImporttoShopProducts").show()

    // jQuery('#pagination-container').on('click', '.page-item-products', function(e) {
    e.preventDefault();

    // Get the clicked page number
    var page = jQuery(this).attr('data-page');

    // Load products for the selected page
    getAllProducts(page);
  });


  jQuery(document).on("click", ".page-link-all-type", function (e) {
    jQuery(".loaderImporttoShopProducts").show()

    // jQuery('#pagination-container').on('click', '.page-item-products', function(e) {
    e.preventDefault();

    // Get the clicked page number
    var page = jQuery(this).attr('data-page');

    // Load products for the selected page
    loadProductsByType(page);
  });




  jQuery(document).on("click", ".page-link-aliexpress", function (e) {
    jQuery(".loaderImporttoShopProducts").show()

    // jQuery('#pagination-container').on('click', '.page-item-products', function(e) {
    e.preventDefault();

    // Get the clicked page number
    var page = jQuery(this).attr('data-page');

    // Load products for the selected page
    loadAllProductsAliExpress(page);
  });


  jQuery(document).on("click", ".page-link-ebay", function (e) {
    jQuery(".loaderImporttoShopProducts").show()

    // jQuery('#pagination-container').on('click', '.page-item-products', function(e) {
    e.preventDefault();

    // Get the clicked page number
    var page = jQuery(this).attr('data-page');

    // Load products for the selected page
    loadAllProductsEbay(page);
  });

  jQuery(document).on("click", ".page-link-amazon", function (e) {
    jQuery(".loaderImporttoShopProducts").show()

    // jQuery('#pagination-container').on('click', '.page-item-products', function(e) {
    e.preventDefault();

    // Get the clicked page number
    var page = jQuery(this).attr('data-page');

    // Load products for the selected page
    loadAllProductsAmazon(page);
  });






  function getEbayProductDetails(productId, callback) {
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
            if (errorResponse && errorResponse.message) {
              errorMessage += "\nMessage: " + errorResponse.message;
            }
          } catch (error) {
            // Parsing the response as JSON failed
            errorMessage += "\nFailed to parse error response.";
          }
          console.error(errorMessage); // Log the error for debugging
          displayToast(
            "Error during eBay API call. Please contact wooebayimporter@gmail.com with subject: eBay API call failed"
          );
          callback(null);
        }
      }
    };

    xhr.open("POST", baseUrl + ":8008/getEbayVariationsNewApi", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.send(JSON.stringify({ productId: productId, globlId: selectedLanguageInput, isPlugin: true }));
  }



  let baseUrl = "https://thesharkdropship.com";



  //   jQuery(document).on('click', '.update-product-button', function () {
  //     // jQuery(".loaderconfirmUpdateProductList").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'))
  //     // jQuery(".loaderconfirmUpdateProductList").show()


  //     jQuery('.update-product-button').find('.lds-ring').remove();

  //     // Add the loader to the clicked button
  //     var loader = '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>';
  //     jQuery(this).append(loader);


  //     var clickedRow = jQuery(this).closest('tr');
  //     var linkCell = clickedRow.find('td:eq(4)'); // Adjust the index (4) to match the "Link" column position
  //     var linkUrl = linkCell.find('a').attr('href');

  //     // Get the SKU from the SKU cell of the clicked row
  //     // var sku = clickedRow.find('#product-sku').text();
  //     var sku = clickedRow.find('#product-sku').text();

  //     var productType = jQuery(this).closest('tr').find('.product-type-badge').text();
  //     var productType = "variable";



  //     var productSupplier = jQuery(this).data('product-supplier');

  //     if (linkUrl.includes('.ebay.')) {
  //       let variationDetails = [];
  //       getEbayProductDetails(sku, function (response) {
  //         if (response && response.variations && response.variations.Variation) {
  //           response.variations.Variation.forEach(function (variation) {
  //             var sku = variation.SKU; // SKU
  //             var quantity = Number(variation.QuantitySold) ? Number(variation.QuantitySold) - Number(variation.SellingStatus.QuantitySold) : Number(variation.SellingStatus.QuantitySold);// Quantity
  //             var price = variation.StartPrice.Value; // Price
  //             // Assuming "Sale Price" is not present in the data, you can add it if available

  //             var variationData = {
  //               sku: sku,
  //               quantity: quantity,
  //               price: price
  //               // Add salePrice if available
  //             };

  //             variationDetails.push(variationData);
  //           });


  //           displayUpdateModal(productType, sku);
  //           var variableProductTableBody = jQuery('#variableProductTableBody');

  //           // Clear any existing rows
  //           variableProductTableBody.empty();

  //           // Populate the table with variation data
  //           variationDetails.forEach(function (variation) {
  //             // var deleteButton = '<button class="delete-button btn btn-danger">Delete</button>';

  //             var row = `
  //         <tr>
  //         <td><input type="text" class="form-control" value="${variation.sku || ''}"></td>
  //         <td><input type="text" class="form-control" value="${variation.quantity}"></td>
  //         <td><input type="text" class="form-control" value="${variation.price}"></td>
  //         <td><input type="text" class="form-control" value="${variation.price || ''}"></td>
  //         <td><button class="delete-button btn btn-danger">deleteButton</button></td>

  //       </tr>
  // `;


  //             variableProductTableBody.append(row);
  //           });


  //           jQuery("#regularPriceInput, #salePriceInput, #stockInput").on("input", function () {
  //             // Enable/disable the "Confirm Update" button
  //             jQuery("#confirmUpdate").prop("disabled", !anyOptionFilled());
  //           });


  //           // Open the modal
  //           jQuery('#updateProductModal').modal('show');



  //         }
  //       });
  //     } else if (linkUrl.includes('.aliexpress.')) {

  //       getProductAliExpress(sku, linkUrl, function (jsonData) {
  //         if (jsonData) {

  //           var variationDetails = [];

  //           // Extract variation details from the JSON
  //           var skuPriceList = jsonData.skuModule.skuPriceList;
  //           if (skuPriceList && skuPriceList.length > 0) {
  //             skuPriceList.forEach(function (skuPriceItem) {
  //               var skuVal = skuPriceItem.skuVal;
  //               var skuAmount = skuVal.skuAmount;

  //               var sku = skuPriceItem.skuIdStr;
  //               var quantity = skuVal.availQuantity;
  //               var price = skuAmount.value; // Assuming you want the numeric value

  //               // Create the variation data object
  //               var variationData = {
  //                 sku: sku,
  //                 quantity: quantity,
  //                 price: price
  //                 // You can add salePrice here if available
  //               };

  //               // Push the variation data to the array
  //               variationDetails.push(variationData);
  //             });
  //           }

  //           displayUpdateModal(productType, sku);
  //           var variableProductTableBody = jQuery('#variableProductTableBody');

  //           // Clear any existing rows
  //           variableProductTableBody.empty();

  //           // Populate the table with variation data
  //           variationDetails.forEach(function (variation) {
  //             // var deleteButton = '<button class="delete-button btn btn-danger">Delete</button>';

  //             var row = `
  //               <tr>
  //               <td><input type="text" class="form-control" value="${variation.sku || ''}"></td>
  //               <td><input type="text" class="form-control" value="${variation.quantity}"></td>
  //               <td><input type="text" class="form-control" value="${variation.price}"></td>
  //               <td><input type="text" class="form-control" value="${variation.price || ''}"></td>
  //               <td><button class="delete-button btn btn-danger">deleteButton</button></td>

  //             </tr>
  //       `;


  //             variableProductTableBody.append(row);
  //           });


  //           jQuery("#regularPriceInput, #salePriceInput, #stockInput").on("input", function () {
  //             // Enable/disable the "Confirm Update" button
  //             jQuery("#confirmUpdate").prop("disabled", !anyOptionFilled());
  //           });


  //           // Open the modal
  //           jQuery('#updateProductModal').modal('show');





  //           console.log(variationDetails);


  //         }
  //       });

  //     }


  //   });


  //   function anyOptionFilled() {
  //     return (
  //       jQuery("#regularPriceInput").val().trim() !== "" ||
  //       jQuery("#salePriceInput").val().trim() !== "" ||
  //       jQuery("#stockInput").val().trim() !== ""
  //     );
  //   }



  //   function displayUpdateModal(productType, sku) {
  //     // Define the inline template for the modal
  //     var modalTemplate = `
  //       <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProductModalLabel" aria-hidden="true">
  //         <div class="modal-dialog modal-dialog-centered" style="max-width:70vw; width:70vw">
  //           <div class="modal-content">
  //             <div class="modal-header">
  //               <h5 class="modal-title" id="updateProductModalLabel">Update Product: <span style="color:red"> ${sku} </span> </h5>
  //               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  //             </div>
  //             <div class="modal-body">
  //     `;


  //     modalTemplate += `
  //   <div class="form-group">
  //     <div class="form-check">
  //       <input type="checkbox" class="form-check-input"  checked id="updateRegularPrice">
  //       <label class="form-check-label" for="updateRegularPrice">Update Regular Price</label>
  //     </div>
  //     <div class="form-check">
  //       <input type="checkbox" class="form-check-input" checked id="updateSalePrice">
  //       <label class="form-check-label" for="updateSalePrice">Update Sale Price</label>
  //     </div>
  //     <div class="form-check">
  //       <input type="checkbox" class="form-check-input" checked id="updateStock">
  //       <label class="form-check-label" for="updateStock">Update Stock</label>
  //     </div>
  //   </div>
  // `;



  //     if (productType === 'simple') {
  //       // Display content for simple product type
  //       modalTemplate += `
  //         <div class="mb-3">
  //           <label for="currentStock" class="form-label">Current Stock:</label>
  //           <input type="text" class="form-control" id="currentStock">
  //         </div>
  //         <div class="mb-3">
  //           <label for="currenPrice" class="form-label">Current Price:</label>
  //           <input type="text" class="form-control" id="currenPrice">
  //         </div>
  //         <div class="mb-3">
  //           <label for="currenSalePrice" class="form-label">Current Sale Price:</label>
  //           <input type="text" class="form-control" id="currenSalePrice">
  //         </div>
  //       `;
  //     } else if (productType === 'external') {
  //       // Display content for external product type
  //       modalTemplate += `
  //         <div class="mb-3">
  //           <label for="newProductUrl" class="form-label">New Quantity:</label>
  //           <input type="text" class="form-control" id="newProductUrl">
  //         </div>
  //       `;
  //     } else if (productType === 'variable') {
  //       // Display content for variable product type (table with 3 columns)
  //       modalTemplate += `

  //       <!-- Regular Price Input -->
  // <div style="dislay:flex">
  //   <input type="text" style="width: 30%; display: inline;" id="regularPriceInput" class="form-control" placeholder="Enter Regular Price">
  //   <button id="updateRegularPriceButton" style="width: 30%; display: inline;" class="btn btn-primary">Set All Regular Price</button>
  // </div>

  // <!-- Sale Price Input -->
  // <div style="dislay:flex">
  //   <input type="text"  style="width: 30%; display: inline;"  id="salePriceInput" class="form-control" placeholder="Enter Sale Price">
  //   <button id="updateSalePriceButton" style="width: 30%; display: inline;" class="btn btn-primary">Set All Sale Price</button>
  // </div>

  // <!-- Stock Input -->
  // <div style="dislay:flex">
  //   <input type="text"  style="width: 30%; display: inline;"  id="stockInput" class="form-control" placeholder="Enter Stock Quantity">
  //   <button id="updateStockButton" style="width: 30%; display: inline;" class="btn btn-primary">Set All Stock</button>
  // </div>



  //         <table class="table">
  //           <thead>
  //             <tr>
  //               <th>Sku</th>
  //               <th>Stock</th>
  //               <th>Price</th>
  //               <th>Sale Price</th>
  //               <th>Action</th> <!-- Add a new column for the delete button -->

  //             </tr>
  //           </thead>
  //           <tbody id="variableProductTableBody">
  //             <!-- Add rows for variable product data here -->
  //           </tbody>
  //         </table>
  //       `;
  //     }

  //     // Complete the modal template
  //     modalTemplate += `
  //             </div>
  //             <div class="modal-footer">
  //               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  //               <button type="button" class="btn btn-primary" id="confirmUpdate">Confirm <span class="loaderconfirmUpdate" style="display:none"></span></button>
  //             </div>
  //           </div>
  //         </div>
  //       </div>
  //     `;

  //     // Append the modal template to the document
  //     jQuery('body').append(modalTemplate);

  //     // Initialize the Bootstrap modal
  //     var modal = new bootstrap.Modal(document.getElementById('updateProductModal'));
  //     modal.show();
  //   }


  //   jQuery(document).on('hidden.bs.modal', '#updateProductModal', function () {
  //     // Clear the input fields and reset the checkboxes
  //     jQuery("#regularPriceInput").val("");
  //     jQuery("#salePriceInput").val("");
  //     jQuery("#stockInput").val("");
  //     jQuery("#updateRegularPrice, #updateSalePrice, #updateStock").prop("checked", true);

  //     jQuery("#variableProductTableBody").empty();

  //     // You can also reset any other modal-related data or state here
  //   });



  //   function handleDeleteButtonClick(event) {
  //     event.preventDefault();
  //     // Find the closest row to the delete button
  //     var row = jQuery(this).closest('tr');
  //     // Remove the row from the table
  //     row.remove();
  //   }


  //   // Wait for the DOM to be ready
  //   // Your existing code for adding table rows

  //   // jQuery("#updateRegularPriceButton").on("click", function () {
  //   jQuery(document).on('click', '#updateRegularPriceButton', function () {

  //     var regularPriceValue = jQuery("#regularPriceInput").val();
  //     if (regularPriceValue !== "") {
  //       jQuery("#variableProductTableBody tr").each(function () {
  //         jQuery(this).find("td:nth-child(3) input").val(regularPriceValue);
  //       });
  //     }
  //   });

  //   // Function to update sale price for all rows
  //   // jQuery("#updateSalePriceButton").on("click", function () {
  //   jQuery(document).on('click', '#updateSalePriceButton', function () {

  //     var salePriceValue = jQuery("#salePriceInput").val();
  //     if (salePriceValue !== "") {
  //       jQuery("#variableProductTableBody tr").each(function () {
  //         jQuery(this).find("td:nth-child(4) input").val(salePriceValue);
  //       });
  //     }
  //   });

  //   // Function to update stock for all rows
  //   // jQuery("#updateStockButton").on("click", function () {
  //   jQuery(document).on('click', '#updateStockButton', function () {

  //     var stockValue = jQuery("#stockInput").val();
  //     if (stockValue !== "") {
  //       jQuery("#variableProductTableBody tr").each(function () {
  //         jQuery(this).find("td:nth-child(2) input").val(stockValue);
  //       });
  //     }
  //   });



  //   // Attach click event handler to delete buttons
  //   jQuery(document).on('click', '.delete-button', handleDeleteButtonClick);

  //   function createProductTable(response) {
  //     var productsTable = jQuery('<table class="table">');
  //     productsTable.append('<thead><tr><th>Image</th><th>SKU</th><th>ID</th><th>Title</th><th>Link</th><th>Update Status </th><th>Edit Product in WordPress</th><th>Preview Product</th><th>Product Type</th><th>Last updated date</th></tr></thead>');
  //     var tbody = jQuery('<tbody>');

  //     if (response.products.length > 0) {
  //       jQuery.each(response.products, function (index, product) {
  //         var productRow = jQuery('<tr>');
  //         productRow.append('<td><img src="' + product.image + '" alt="' + product.title + '" width="50" height="50"></td>');
  //         productRow.append('<td id="product-sku">' + product.sku + '</td>');
  //         productRow.append('<td>' + product.id + '</td>');
  //         if (product.status === 'draft') {
  //           productRow.append('<td><span class="badge badge-secondary">' + product.status + '</span></td>');
  //         } else if (product.status === 'publish') {
  //           productRow.append('<td><span class="badge badge-success">' + product.status + '</span></td>');
  //         } else {
  //           productRow.append('<td>' + product.status + '</td>');
  //         }
  //                 productRow.append('<td><a target=”_blank” class="btn btn-primary" href="' + product.productUrl + '" target="_blank">Original url</a></td>');
  //         productRow.append('<td><a target=”_blank” class="btn btn-primary" href="' + product.permalink + '" target="_blank"> Edit in wordpress </a></td>');
  //         productRow.append('<td><a target=”_blank” class="btn btn-primary" href="' + product.permalink_preview + '" target="_blank"> preview Product</a></td>');

  //         productRow.append('<td><button class="update-product-button btn btn-primary" data-product-id="' + product.id + '">Update <span class="loaderconfirmUpdateProductList" style="display:none"></span></button></td>');
  //         var productTypeClass = '';
  //         if (product.productType === 'simple') {
  //           productTypeClass = 'badge-primary'; // Change the class for 'simple' product type
  //         } else if (product.productType === 'variable') {
  //           productTypeClass = 'badge-warning'; // Change the class for 'variable' product type
  //         } else if (product.productType === 'external') {
  //           productTypeClass = 'badge-success'; // Change the class for 'grouped' product type
  //         }
  //         productRow.append('<td><span class="badge ' + productTypeClass + '">' + product.productType + '</span></td>');

  //         productRow.append('<td><span class="last-updated-date"> ' + product.lastUpdatedDate + '</span></td>');


  //         tbody.append(productRow);
  //         productsTable.append(tbody);
  //         jQuery('#products-wooshark').empty().append(productsTable);

  //       });
  //     } else {
  //       jQuery('table.table tr').empty();

  //       tbody.append('<tr><td colspan="7">No products found.</td></tr>');
  //     }
  //   }






  //   // Add an event listener for the "Confirm" button
  //   jQuery(document).on('click', '#confirmUpdate', function () {
  //     jQuery(".loaderconfirmUpdate").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'))
  //     jQuery(".loaderconfirmUpdate").show()

  //     // Check the status of the checkboxes
  //     var updateRegularPrice = jQuery('#updateRegularPrice').prop('checked');
  //     var updateSalePrice = jQuery('#updateSalePrice').prop('checked');
  //     var updateStock = jQuery('#updateStock').prop('checked');

  //     // Create an array to store the variations to be updated
  //     var variationsToUpdate = [];

  //     // Loop through the rows in the variable product table
  //     jQuery('#variableProductTableBody tr').each(function () {
  //       var row = jQuery(this);
  //       var sku = row.find('input:eq(0)').val();
  //       var quantity = row.find('input:eq(1)').val();
  //       var price = row.find('input:eq(2)').val();
  //       var salePrice = row.find('input:eq(3)').val();

  //       // Create an object to represent the variation
  //       var variation = {
  //         sku: sku,
  //         quantity: quantity,
  //         price: price,
  //       };

  //       // Check if the "Update Sale Price" checkbox is selected
  //       if (updateSalePrice) {
  //         variation.salePrice = salePrice;
  //       }

  //       // Check if the "Update Regular Price" checkbox is selected
  //       if (updateRegularPrice) {
  //         variation.regularPrice = price;
  //       }

  //       // Check if the "Update Stock" checkbox is selected
  //       if (updateStock) {
  //         variation.stock = quantity;
  //       }

  //       // Add the variation to the array if any field needs updating
  //       if (
  //         updateRegularPrice ||
  //         updateSalePrice ||
  //         updateStock
  //       ) {
  //         variationsToUpdate.push(variation);
  //       }
  //     });

  //     // Send an AJAX request to update the variations on WooCommerce
  //     jQuery.ajax({
  //       url: wooshark_params.ajaxurl,
  //       type: 'POST',
  //       dataType: 'JSON',
  //       data: {
  //         
  //                 nonce: wooshark_params.nonce,
  // action: 'update_variations_on_woocommerce_alibay',
  //         variations: variationsToUpdate,
  //       },
  //       success: function (response) {
  //         if (response.success) {
  //           // alert('Variations updated successfully.');
  //           displayToast('Variations updated successfully.', 'green')
  //           jQuery(".loaderconfirmUpdate").hide()

  //           // Close the modal or perform any other action
  //         } else {
  //           displayToast('Failed to update variations.', 'red');

  //           // alert('Failed to update variations.');
  //           jQuery(".loaderconfirmUpdate").hide()

  //         }
  //       },
  //       error: function () {
  //         alert('AJAX request failed.');
  //         displayToast('Failed to update variations.', 'red');

  //       },
  //     });
  //   });

  jQuery(document).on("click", "#ImportImagesFromGallery", function (event) {
    let galleryImages = jQuery("#galleryPicture img");
    let newContent = quill.root.innerHTML;

    jQuery(galleryImages).each(function (index, image) {
      newContent = newContent + "<div>" + image.outerHTML + " /></div>";
    });

    quill.setContents([]);
    quill.clipboard.dangerouslyPasteHTML(0, newContent);
  });

  // jQuery(document).on("click", "#addSpecificToDesc", function (event) {
  //   if (jQuery("#addSpecificToDesc")[0].checked) {
  //     let specificAttributes = getItemSpecificfromTableAliexpress();
  //     let newContent = quill.root.innerHTML;

  //     if (specificAttributes && specificAttributes.NameValueList) {
  //       specificAttributes.NameValueList.forEach(function (attribute) {
  //         newContent = newContent + "<div>" + attribute.name + " " + attribute.value + "</div>";
  //       });

  //       quill.setContents([]);
  //       quill.clipboard.dangerouslyPasteHTML(0, newContent);
  //     }
  //   }
  // });


  if(isPremuim){
    jQuery('.freeAds').hide();
  }
  function handleFreeVersion(){
    if(!isPremuim){
      displayToastLonger("The free version of the software does not import all product details.", "orange");
    }
  }


})(jQuery);
