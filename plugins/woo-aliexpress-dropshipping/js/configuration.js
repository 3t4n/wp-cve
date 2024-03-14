(function (jQuery) {

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
        searchByKeyWord("", "en", 1)
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

  function getProductAliExpress(sku, url, callback) {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          var responseData = JSON.parse(xhr.response).data;

          if (responseData) {
            callback(responseData);
          }
        } else {
          handleProductLoadError(callback);
        }
      }
    };

    var requestData = {
      sku: sku,
      language: "",
      isBasicVariationsModuleUsedForModalDisplay: true,
      currency: "",
      store: "",
      fullProductUrl: url,
    };

    sendRequestToInternalApi(requestData, xhr, callback);
  }

  function handleProductLoadError(callback) {
    displayToast("Cannot load product details, please try again", "red");
    callback(null);
  }

  function sendRequestToInternalApi(requestData, xhr, callback) {
    xhr.open("POST", hostname + ":8002/getProductDEtailsFRomOurInternalApi", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(JSON.stringify(requestData));
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
          currency: a,
          store: document.location.origin,
          fullProductUrl: fullProductUrl
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
  function importProductGloballyBulk(e, t) {
    try {
      e &&
        ((currentSku = e),
          jQuery(this).attr("disabled", !0),
          jQuery(".importToS").each(function (e, t) {
            console.log("----- disabling"), jQuery(t).attr("disabled", !0);
          }),
          startLoading(),
          getProductDetailsFromServerBulk(e, t));
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
  function getSelectedLanguage() {
    return jQuery('input[name="language"]:checked')[0]
      ? jQuery('input[name="language"]:checked')[0].value
      : "en";
  }
  function getProductDetailsFromServerBulk(e) {
    var t = getSelectedLanguage(),
      a = jQuery('input[name="currency"]:checked')[0]
        ? jQuery('input[name="currency"]:checked')[0].value
        : "USD",
      r = new XMLHttpRequest();
    (r.onreadystatechange = function () {
      if (4 == this.readyState)
        if (200 === this.status) {
          if ((r = JSON.parse(this.response).data)) {
            var t = [];
            jQuery(".categories input:checked").each(function () {
              t.push(
                jQuery(this)
                  .attr("value")
                  .trim()
              );
            });
            var a = t;
            (waitingListProducts = []),
              jQuery(".importToS").each(function (e, t) {
                console.log("----- un - disabling"),
                  jQuery(t).attr("disabled", !1);
              }),
              stopLoading();
            let i = jQuery("#textToBeReplaced").val(),
              o = jQuery("#textToReplace").val(),
              n = r.title,
              l = r.description;
            i &&
              o &&
              ((n = r.title.replace(i, o)), (l = r.description.replace(i, o))),
              addToWaitingList({
                title: n,
                description: l,
                images: r.images,
                variations: prepareDataFormat(
                  r.variations,
                  r.currentPrice,
                  r.originalPrice,
                  r.totalAvailQuantity
                ),
                productUrl: r.productUrl,
                productCategoies: a,
                importSalePrice: !0,
                simpleSku: e.toString(),
                featured: !0,
                mainImage: r.mainImage
              });
          }
        } else
          try {
            var r = JSON.parse(this.response).data;
            jQuery(".importToS").each(function (e, t) {
              console.log("----- un - disabling"), jQuery(t).attr("disabled", !1);
            }),
              displayToast("Cannot insert product into shop " + r, "red"),
              stopLoading();
          } catch (e) {
            jQuery(".importToS").each(function (e, t) {
              console.log("----- un - disabling"), jQuery(t).attr("disabled", !1);
            }),
              displayToast("Cannot get product details, please try again", "red"),
              stopLoading();
          }
    }),
      r.open("POST", hostname + ":8002/getProductDEtailsFRomOurInternalApi", !0),
      r.setRequestHeader("Content-Type", "application/json"),
      r.send(
        JSON.stringify({
          sku: e,
          language: t,
          isBasicVariationsModuleUsedForModalDisplay: !1,
          currency: a,
          store: document.location.origin
        })
      );
  }
  function getHtmlDescription(e) {
    if (e) {
      var t = e.indexOf("window.adminAccountId");
      t > -1 && (e = e.substring(0, t));
    }
    (imagesFromDescription = jQuery("img")),
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
    prepareModal_aliexpress(), productId = jQuery(this).parents(".card").find("#sku")[0].innerText;
    productId ? importProductGlobally(productId) : displayToast("Cannot get product sku", "red");
    globalUrlProduct = jQuery(this).parents(".card").find("#productUrlByCard").attr("href");
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
      imagesFromDescription.each(function ( t, e) {
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
  function buildVariations() {
    var e = { variations: [], NameValueList: [] };
    jQuery("#table-attributes tr").each(function (t, a) {
      if (t) {
        e.NameValueList.find(function (e) {
          return (
            e.name == a.cells[0].textContent.toLowerCase().replace(/ /g, "-")
          );
        }) ||
          e.NameValueList.push({
            name: a.cells[0].textContent.toLowerCase().replace(/ /g, "-"),
            values: a.cells[1].textContent.split(","),
            variation: !0,
            visible: !0
          });
      }
    });
    var t = e.NameValueList.length;
    return (
      jQuery("#table-variations tr").each(function (a, r) {
        if (a && a < 100) {
          var i = [];
          e.NameValueList.forEach(function (e, t) {
            i.push({
              name: e.name.toLowerCase().replace(/ /g, "-"),
              value: r.cells[t + 1].textContent.trim(),
              image:
                r.cells[0] &&
                  r.cells[0].children &&
                  r.cells[0].children[0] &&
                  r.cells[0].children[0].currentSrc
                  ? r.cells[0].children[0].currentSrc
                  : ""
            });
          }),
            r.cells[t + 1].textContent &&
            e.variations.push({
              SKU: r.cells[t + 4].textContent,
              availQuantity: r.cells[t + 1].textContent || 1,
              salePrice: r.cells[t + 3].textContent,
              regularPrice: r.cells[t + 2].textContent,
              attributesVariations: i,
              weight:
                r.cells[t + 6].textContent || jQuery("#productWeight").val()
            });
        }
      }),
      e
    );
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
        <h4 class="modal-title">Product customization <span style="color:red" id="productType"></span> <span
                style="color:red" id="currencyReturned"> <span></h4>

        <button class="btn btn-danger" data-bs-dismiss="modal">&times;</button>
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
                        <div class="checkbox"><label><input id="showCategories" type="checkbox" name="remember"> &nbsp;
                                Show categories</label> </div>
                        <div class="form-group" id="categoriesContainer" style="margin-top:30px">
                            <div class="panel panel-default">
                                <div class="panel-heading">Select Categories</div>
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
                                <div class="checkbox"><label><input id="addSpecificToDesc" type="checkbox"
                                            name="remember"> &nbsp;Add Specifications to the description </label> </div>
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
                                                2.99) </small> </label> </div><label><input
                                            style="bottom: auto" id="isImportImageVariations" type="checkbox">&nbsp;
                                        Import images variations </label>
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
                                    <labe style="justify-content: center; font-weight: 800; margin-bottom: 20px" l>Import as
                                        Affiliate or simple preferences</label>
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
                    <div class="modal-footer"> <button type="button" class="btn btn-danger"
                            data-bs-dismiss="modal">Close</button> <button type="button" id="totoButton"
                            class="button-5">Import <small style="display:none; color:grey" id="asVariableAliex"> ( as
                                Variable ) </small><span id="loaderImporttoShop" style="display:none"></span></button>
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
          } else {
            displayToastWithColor('Variations at index ' + currentIndex + ' inserated successfully', "green");
          }
          insertVariationsInSets(variations, postId, currentIndex + 10);
        },
        error: function (error) {
          if (error.responseText) {
            displayToast(error.responseText, "red");
          } else {
            displayToast(error, "red");
          }
          insertVariationsInSets(variations, postId, currentIndex + 10);

        },
      });
    } else {
      stopLoading();
      jQuery(".lds-ring").remove();
    }
  }

  // jQuery(document).on("click", "#totoButton", function (e) {
  //   jQuery("#loaderImporttoShop").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'));
  //   jQuery("#loaderImporttoShop").show();
  //   startLoading();
  //   displayToast("Please note that the free version does not include the import of all product details.", "orange");

  //   // Collect your variations here (replace this with your actual data)


  // });




  jQuery(document).on("click", ".close-modal", function (e) {
    jQuery('.modal').css({ 'display': 'none' })
  });

  jQuery(document).on("click", "#totoButton", function (e) {
    jQuery("#loaderImporttoShop").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>')), jQuery("#loaderImporttoShop").show(), startLoading(), displayToastWithColor("Please note that the free version does not include the import of all product details.", "orange");
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
    jQuery("#isImportReviewsSingleImport").prop("checked") && (t = getReviews()), jQuery("#isImportProductDescriptionSingleImport").prop("checked") && (description = quill.root.innerHTML), jQuery("#isImportProductSpecificationSingleImport").prop("checked") && (i = getItemSpecificfromTableAliexpress(i));
    let isImportImageVariationsSingleImport = jQuery("#isImportImageVariationsSingleImport").prop("checked"),
      isFeatured = jQuery("#isFeaturedProduct").prop("checked"),
      y = jQuery("#isPublishProductSingleImport").prop("checked"),
      m = jQuery("#includeShippingCostIntoFinalPrice").prop("checked"),
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
          // variations: variations.variations && variations.variations.length ? variations.variations.slice(0, 4) : [],
          isFeatured: isFeatured,
          postStatus: y ? "publish" : "draft",
          shortDescription: shortDescription || "",
          productUrl: u,
          importVariationImages: isImportImageVariationsSingleImport,
          reviews: jQuery("#isImportReviewsSingleImport").prop("checked") ? t : [],
          tags: g,
          includeShippingCostIntoFinalPrice: m
        },
        success: function (e) {
          if (e && e.error && e.error_msg) {
            displayToast(e.error_msg, "red");
          } else if (e && !e.error && e.data) {
            displayToast(e.data, "green");
            displayToast("Start Loading variations by set of 10", "green");
            let postId = e.postId;
            insertVariationsInSets(variations.variations, postId, 0);
          }
          // stopLoading();
          // jQuery(".lds-ring").remove();

          if (e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage")) {
            setTimeout(function () {
              window.open("https://sharkdropship.com/aliexpress", "_blank");
            }, 4000); // 4e3 is equivalent to 4000 milliseconds (4 seconds)
          }
        },
        error: function (e) {
          stopLoading();
          if (e && e.responseText) {
            displayToast(e.responseText, "red");
          }
          jQuery(".lds-ring").remove();
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
  function restoreConfiguration() {
    let e = {};
    jQuery.ajax({
      url: wooshark_params.ajaxurl,
      type: "POST",
      dataType: "JSON",
      data: { 
        nonce: wooshark_params.nonce,
        action: "restoreConfiguration-alibay" },
      success: function (t) {
        let a, r, i, o, n;
        console.log("response---", t),
          t &&
            t._savedConfiguration_alibay &&
            t._savedConfiguration_alibay.commonConfiguration
            ? ((a = (e = t._savedConfiguration_alibay).commonConfiguration),
              (r = e.sinleUpdateConfiguration),
              (i = e.singleImportonfiguration),
              (o = e.bulkCategories),
              (n = e.savedFormula),
              a &&
              a.language &&
              (jQuery("[name=language][value=" + a.language + "]").attr(
                "checked",
                !0
              ),
                jQuery(
                  '<h4 style="font-weight:bold;"> Current Language: ' +
                  a.language +
                  "  </h4>"
                ).appendTo(".currencyDetails")),
              a &&
              a.currency &&
              (jQuery("[name=currency][value=" + a.currency + "]").attr(
                "checked",
                !0
              ),
                jQuery(
                  '<h4 style="font-weight:bold;"> Current currency: ' +
                  a.currency +
                  "  </h4>"
                ).appendTo(".currencyDetails")),
              r
                ? (jQuery("#applyPriceFormulaWhileUpdatingProduct").prop(
                  "checked",
                  !1
                ),
                  jQuery("#isVariationDisplayedValue").prop("checked", !1),
                  jQuery("#setVariationsToOutOfStock").prop("checked", !1),
                  jQuery("#updateSalePrice").prop("checked", !1),
                  jQuery("#updateRegularPrice").prop("checked", !1))
                : (jQuery("#applyPriceFormulaWhileUpdatingProduct").prop(
                  "checked",
                  !1
                ),
                  jQuery("#setVariationsToOutOfStock").prop("checked", !1),
                  jQuery("#updateSalePrice").prop("checked", !1),
                  jQuery("#updateRegularPrice").prop("checked", !1),
                  jQuery("#isVariationDisplayedValue").prop("checked", !1)),
              i
                ? (jQuery("#isImportReviewsSingleImport").prop(
                  "checked",
                  "true" == i.isImportReviewsSingleImport
                ),
                  jQuery("#isImportImageVariationsSingleImport").prop(
                    "checked",
                    "true" == i.isImportImageVariationsSingleImport
                  ),
                  jQuery("#isImportProductSpecificationSingleImport").prop(
                    "checked",
                    "true" == i.isImportProductSpecificationSingleImport
                  ),
                  jQuery("#isImportProductDescriptionSingleImport").prop(
                    "checked",
                    "true" == i.isImportProductDescriptionSingleImport
                  ),
                  jQuery("#isPublishProductSingleImport").prop(
                    "checked",
                    "true" == i.isPublishProductSingleImport
                  ),
                  jQuery("#applyPriceFormulawhileImporting").prop(
                    "checked",
                    "true" == i.applyPriceFormulawhileImporting
                  ),
                  jQuery("#isFeaturedProduct").prop(
                    "checked",
                    "true" == i.isFeaturedProduct
                  ),
                  jQuery("#includeShippingCostIntoFinalPrice").prop(
                    "checked",
                    "true" == i.includeShippingCostIntoFinalPrice
                  ),
                  jQuery("#isEnableAutomaticUpdateForAvailability").prop(
                    "checked",
                    "true" == i.isEnableAutomaticUpdateForAvailability
                  ),
                  jQuery("#enableAutomaticUpdates").prop(
                    "checked",
                    "true" == i.enableAutomaticUpdates
                  ),
                  jQuery("#applyPriceFormulaAutomaticUpdate").prop("checked", !1),
                  jQuery("#syncSalePrice").prop("checked", !1),
                  jQuery("#syncRegularPrice").prop("checked", !1),
                  jQuery("#syncStock").prop("checked", !1),
                  jQuery("#onlyPublishProductWillSync").prop("checked", !1),
                  jQuery("[name=destination][value=" + i.destination + "]").attr(
                    "checked",
                    !0
                  ),
                  jQuery("#textToBeReplaced").val(i.textToBeReplaced),
                  jQuery("#textToReplace").val(i.textToReplace))
                : (jQuery("#isImportReviewsSingleImport").prop("checked", !0),
                  jQuery("#isImportImageVariationsSingleImport").prop(
                    "checked",
                    !1
                  ),
                  jQuery("#isImportProductSpecificationSingleImport").prop(
                    "checked",
                    !0
                  ),
                  jQuery("#isImportProductDescriptionSingleImport").prop(
                    "checked",
                    !0
                  ),
                  jQuery("#isPublishProductSingleImport").prop("checked", !0),
                  jQuery("#applyPriceFormulawhileImporting").prop("checked", !0),
                  jQuery("#isFeaturedProduct").prop("checked", !1),
                  jQuery("#includeShippingCostIntoFinalPrice").prop(
                    "checked",
                    !1
                  ),
                  jQuery("#isEnableAutomaticUpdateForAvailability").prop(
                    "checked",
                    !1
                  ),
                  jQuery("#enableAutomaticUpdates").prop("checked", !1),
                  jQuery("#applyPriceFormulaAutomaticUpdate").prop("checked", !1),
                  jQuery("#syncRegularPrice").prop("checked", !1),
                  jQuery("#syncStock").prop("checked", !1),
                  jQuery("#syncSalePrice").prop("checked", !1),
                  jQuery("#onlyPublishProductWillSync").prop("checked", !1),
                  jQuery("[name=destination][value=US]").attr("checked", !0)),
              restoreFormula(n),
              getCategories(function (e) {
                savedCategories &&
                  savedCategories.length &&
                  (jQuery("#table-categories tbody").empty(),
                    savedCategories.forEach(function (e) {
                      jQuery("#table-categories tbody").append(
                        '<tr><td style="width:20%">' +
                        e.term_id +
                        '</td><td style="width:20%">' +
                        e.name +
                        '</td><td  style="width:20%">' +
                        e.count +
                        ' </td></td><td  style="width:40%"><button class="btn btn-primary" style="width:100%" id="updateProductOfThisCategory" categoryID="' +
                        e.term_id +
                        '"> Update Products of this category</button></td></tr>'
                      );
                    }),
                    o && o.length && savedCategories && savedCategories.length
                      ? (jQuery("#bulkCategories").empty(),
                        savedCategories.forEach(function (e, t) {
                          var a;
                          (a =
                            '<div class="checkbox"><label><input id="category' +
                            e.term_id +
                            '" type="checkbox" style="width:17px; height:17px" class="chk" value="' +
                            e.term_id +
                            ' "/>' +
                            e.name +
                            "</label>"),
                            jQuery("#bulkCategories").append(jQuery(a));
                        }),
                        o &&
                        o.length &&
                        o.forEach(function (e) {
                          jQuery("#category" + e).prop("checked", !0);
                        }))
                      : (jQuery("#bulkCategories").empty(),
                        savedCategories.forEach(function (e, t) {
                          var a;
                          (a =
                            '<div class="checkbox"><label><input id="category' +
                            e.term_id +
                            '" type="checkbox" style="width:17px; height:17px" class="chk" value="' +
                            e.term_id +
                            ' "/>' +
                            e.name +
                            "</label>"),
                            jQuery("#bulkCategories").append(jQuery(a));
                        })));
              }))
            : (getCategories(function (e) {
              savedCategories &&
                savedCategories.length &&
                (jQuery("#table-categories tbody").empty(),
                  savedCategories.forEach(function (e) {
                    jQuery("#table-categories tbody").append(
                      '<tr><td style="width:20%">' +
                      e.term_id +
                      '</td><td style="width:20%">' +
                      e.name +
                      '</td><td  style="width:20%">' +
                      e.count +
                      ' </td></td><td  style="width:40%"><button class="btn btn-primary" style="width:100%" id="updateProductOfThisCategory" categoryID="' +
                      e.term_id +
                      '"> Update Products of this category</button></td></tr>'
                    );
                  }),
                  o && o.length && savedCategories && savedCategories.length
                    ? (jQuery("#bulkCategories").empty(),
                      savedCategories.forEach(function (e, t) {
                        var a;
                        (a =
                          '<div class="checkbox"><label><input id="category' +
                          e.term_id +
                          '" type="checkbox" style="width:17px; height:17px" class="chk" value="' +
                          e.term_id +
                          ' "/>' +
                          e.name +
                          "</label>"),
                          jQuery("#bulkCategories").append(jQuery(a));
                      }),
                      o &&
                      o.length &&
                      o.forEach(function (e) {
                        jQuery("#category" + e).prop("checked", !0);
                      }))
                    : (jQuery("#bulkCategories").empty(),
                      savedCategories.forEach(function (e, t) {
                        var a;
                        (a =
                          '<div class="checkbox"><label><input id="category' +
                          e.term_id +
                          '" type="checkbox" style="width:17px; height:17px" class="chk" value="' +
                          e.term_id +
                          ' "/>' +
                          e.name +
                          "</label>"),
                          jQuery("#bulkCategories").append(jQuery(a));
                      })));
            }));
      },
      error: function (e) {
        displayToast(
          "Error while retrieving configuration from server, please erload your page"
        );
      },
      complete: function () { }
    });
  }
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
  }),
    jQuery(document).on("click", "#saveGlobalConfiguration", function (e) {
      let t = {};
      var a = {
        isImportReviewsSingleImport: jQuery("#isImportReviewsSingleImport").prop(
          "checked"
        ),
        isImportImageVariationsSingleImport: jQuery(
          "#isImportImageVariationsSingleImport"
        ).prop("checked"),
        isImportProductSpecificationSingleImport: jQuery(
          "#isImportProductSpecificationSingleImport"
        ).prop("checked"),
        isImportProductDescriptionSingleImport: jQuery(
          "#isImportProductDescriptionSingleImport"
        ).prop("checked"),
        isPublishProductSingleImport: jQuery(
          "#isPublishProductSingleImport"
        ).prop("checked"),
        applyPriceFormulawhileImporting: jQuery(
          "#applyPriceFormulawhileImporting"
        ).prop("checked"),
        isFeaturedProduct: jQuery("#isFeaturedProduct").prop("checked"),
        textToBeReplaced: jQuery("#textToBeReplaced").val(),
        textToReplace: jQuery("#textToReplace").val(),
        destination: jQuery('input[name="destination"]:checked').val()
      };
      let r = {
        language: getSelectedLanguage(),
        currency:
          jQuery('input[name="currency"]:checked') &&
            jQuery('input[name="currency"]:checked')[0]
            ? jQuery('input[name="currency"]:checked')[0].value
            : "USD"
      },
        i = {
          applyPriceFormulaWhileUpdatingProduct: jQuery(
            "#applyPriceFormulaWhileUpdatingProduct"
          ).prop("checked"),
          setVariationsToOutOfStock: jQuery("#setVariationsToOutOfStock").prop(
            "checked"
          ),
          updateSalePrice: jQuery("#updateSalePrice").prop("checked"),
          updateRegularPrice: jQuery("#updateRegularPrice").prop("checked"),
          isVariationDisplayedValue: jQuery("#isVariationDisplayedValue").prop(
            "checked"
          )
        };
      (t.commonConfiguration = r),
        (t.sinleUpdateConfiguration = i),
        (t.singleImportonfiguration = a),
        displayToast("save global configuration", "green");
      var o = [];
      jQuery(".chk:input:checked").each(function () {
        jQuery(this) && jQuery(this).val() && o.push(jQuery(this).val());
      }),
        (t.bulkCategories = o);
      //   displayToast("save categories", "green");
      var n = jQuery("#formula tbody tr"),
        l = [];
      n &&
        n.length &&
        n.each(function (e, t) {
          if (t && t.cells && t.cells.length > 3) {
            let e = jQuery(t.cells[0])
              .find("input")
              .val(),
              a = jQuery(t.cells[2])
                .find("input")
                .val(),
              r = jQuery(t.cells[3])
                .find("input")
                .val(),
              i = jQuery(t.cells[4])
                .find("input")
                .val();
            e &&
              a &&
              r &&
              l.push({ min: e, max: a, multiply: r || 1, addition: i || 0 });
          }
        }),
        (t.savedFormula = l),
      //   displayToast("save price markup formula"),
        jQuery.ajax({
          url: wooshark_params.ajaxurl,
          type: "POST",
          dataType: "JSON",
          data: {
            
            nonce: wooshark_params.nonce,
            action: "saveOptionsDB-alibay",
            isShippingCostEnabled: jQuery(
              "#includeShippingCostIntoFinalPrice"
            ).prop("checked")
              ? "Y"
              : "N",
            isEnableAutomaticUpdateForAvailability: jQuery(
              "#isEnableAutomaticUpdateForAvailability"
            ).prop("checked")
              ? "Y"
              : "N",
            priceFormulaIntervalls: l,
            _savedConfiguration: t,
            onlyPublishProductWillSync: jQuery(
              "#onlyPublishProductWillSync"
            ).prop("checked")
              ? "Y"
              : "N",
            enableAutomaticUpdates: jQuery("#enableAutomaticUpdates").prop(
              "checked"
            )
              ? "Y"
              : "N",
            applyPriceFormulaAutomaticUpdate: jQuery(
              "#applyPriceFormulaAutomaticUpdate"
            ).prop("checked")
              ? "Y"
              : "N",
            syncRegularPrice: jQuery("#syncRegularPrice").prop("checked")
              ? "Y"
              : "N",
            syncSalePrice: jQuery("#syncSalePrice").prop("checked") ? "Y" : "N",
            syncStock: jQuery("#syncStock").prop("checked") ? "Y" : "N"
          },
          success: function (e) {
            console.log("----saved formula--------", e);
          },
          error: function (e) { },
          complete: function () {
            document.location.reload(!0),
              // displayToast("Configuration saved successfully"),
              jQuery("#savedCorrectlySection").show();
          }
        });
    });
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
  jQuery(document).on("click", "#addToWaitingList", function (e) {
    (productId = jQuery(this)
      .parents(".card")
      .find("#sku")[0].innerText),
      productId
        ? importProductGloballyBulk(productId, !0)
        : displayToast("Cannot get product sku", "red");
  }),
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
    jQuery(document).on("click", "#importProductInWaitingListToShop", function (
      e
    ) {
      startLoading(),
        jQuery("#emptyWaitingListProduct").remove(),
        jQuery("#importProductInWaitingListToShop").remove(),
        _savedConfiguration || (_savedConfiguration = {});
      for (var t = 0; t < globalWaitingList.length; t++)
        !(function (e) {
          window.setTimeout(function () {
            jQuery("#isImportImageVariationsSingleImport").prop("checked");
            let t = jQuery("#isFeaturedProduct").prop("checked"),
              a = jQuery("#isPublishProductSingleImport").prop("checked"),
              r = jQuery("#includeShippingCostIntoFinalPrice").prop("checked");
            var i = {
              title: globalWaitingList[e].title,
              description: globalWaitingList[e].description,
              images: globalWaitingList[e].images,
              variations: globalWaitingList[e].variations.variations,
              prductUrl: globalWaitingList[e].productUrl,
              mainImage: globalWaitingList[e].mainImage,
              simpleSku: globalWaitingList[e].simpleSku,
              productType: "variable",
              attributes: globalWaitingList[e].variations.NameValueList,
              shortDescription: "",
              isFeatured: !0,
              postStatus: !0,
              postStatus: "publish"
            };
            jQuery.ajax({
              url: wooshark_params.ajaxurl,
              type: "POST",
              dataType: "JSON",
              data: {
                
                nonce: wooshark_params.nonce,
                action: "wooshark-insert-product-alibay",
                sku: i.simpleSku.toString(),
                title: i.title,
                description: i.description || "",
                productType: "variable",
                images: images.splice(1) || [],
                mainImage: i.mainImage,
                attributes: i.attributes && i.attributes.length ? i.attributes : [],
                variations: i.variations && i.variations.length ? i.variations.splice(1) : [],
                postStatus: a ? "publish" : "draft",
                shortDescription: i.shortDescription || "",
                productUrl: getPRoductUrlFRomSku(i.simpleSku),
                categories: _savedConfiguration
                  ? _savedConfiguration.bulkCategories
                  : [],
                isFeatured: t,
                importVariationImages: jQuery(
                  "#importImagesVariationsAliexpress"
                ).prop("checked"),
                includeShippingCostIntoFinalPrice: r
              },
              success: function (e) {
                e && e.error && e.error_msg && displayToast(e.error_msg, "red"),
                  e && !e.error && e.data && displayToast(e.data, "green");
              },
              error: function (e) {
                console.log("****err", e),
                  e &&
                  displayToast(
                    "error while inserting products, please retry",
                    "red"
                  );
              },
              complete: function () {
                console.log("SSMEerr"),
                  indexStopLoading++ ,
                  indexStopLoading == globalWaitingList.length &&
                  (stopLoading(), (globalWaitingList = []));
              }
            });
          }, 3e3 * e);
        })(t);
    }),
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
   
   

  function getVariations(e, t) {
    e && e.length ? (jQuery("#applyPriceFormula").show(), jQuery("#applyPriceFormulaRegularPrice").show(), jQuery("#importSalePricecheckbox").show(), jQuery("#applyCharmPricingConainer").show(), jQuery("#priceContainer").hide(), jQuery("#skuContainer").hide(), jQuery("#productWeightContainer").hide(), jQuery("#productType").text("Variable Product"), jQuery("#no-variations").hide(), e && e.length > 100 && displayToast("This product has more " + e.length + " variations, only the first 100 variations will be imported", "orange"), e.forEach(function (e) {
      let t = [];
      let titleAttribtues = jQuery("#customProductTitle").val();

      if (e && e.attributesVariations && e.attributesVariations.length) {
        var a = "";
        e.attributesVariations && e.attributesVariations.length && e.attributesVariations[0] && e.attributesVariations[0].name && e.attributesVariations[0].image ? (t = [e.attributesVariations[0].image],
          a = a + '<td><img height="50px" width="50px" src="' + e.attributesVariations[0].image + '"></td>') : e.attributesVariations && e.attributesVariations.length && e.attributesVariations[1] && e.attributesVariations[1].name && e.attributesVariations[1].image ? (a = a + '<td><img height="50px" width="50px" src="' + e.attributesVariations[1].image + '"></td>'
            , t = [e.attributesVariations[1].image]) : e.attributesVariations && e.attributesVariations.length && e.attributesVariations[2] && e.attributesVariations[2].name && e.attributesVariations[2].image ? (t = [e.attributesVariations[2].image], a = a + '<td><img height="50px" width="50px" src="' + e.attributesVariations[2].image + '"></td>') : a += "<td></td>"
          , e.attributesVariations.forEach(function (e, t) {
            titleAttribtues = titleAttribtues + " - " + e.name + " : " + e.value,
              a = a + '<td contenteditable name="' + e.name + '">' + e.value + "</td>"
          });
        var i = e.regularPrice || e.salePrice,
          r = e.salePrice || e.regularPrice;
        jQuery("#productWeight").val();
        a = a + "<td id='singleQuantity' contenteditable>" + e.availQuantity + "</td><td id='singleRegularPrice' contenteditable>" + i + "</td><td id='singleSalePrice' contenteditable>" + r + '</td><td id="singleAsin" contenteditable>' + e.SKU + '</td><td id="singleTitle" contenteditable>' + titleAttribtues + '</td><td><button id="removeVariation"style="background-color:red" class="button-5">X</button></td><td><button id="insertProductAsSimple" class="button-5" style="width:220px">Import As simple Product <span class="newLoaderSimple"></span> </button></td><td><button id="insertProductAsAffiliate" class="button-6" style="width:220px">Import As Affiliate Product <span class="newLoaderAffiliate"></span> </button></td><td id="singleImages" style="display:none">' + t + "</td>",
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


  jQuery(document).on("click", "#insertProductAsSimple", function (e) {
    jQuery(this).parents("tr").find(".newLoaderSimple").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>')),
      insertProductAsSingle({
        images: jQuery(this).parents("tr").find("#singleImages").text() ? jQuery(this).parents("tr").find("#singleImages").text().split(",") : [],
        isAffiliate: !1,
        asin: jQuery(this).parents("tr").find("#singleAsin").text(),
        regularPice: jQuery(this).parents("tr").find("#singleRegularPrice").text(),
        quantity: jQuery(this).parents("tr").find("#singleQuantity").text(),
        salePrice: jQuery(this).parents("tr").find("#singleSalePrice").text(),
        productUrl: globalUrlProduct,
        title: jQuery(this).parents("tr").find("#singleTitle").text()
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
    customVariations = buildVariationsForSingleImport(),
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
        includeShippingCostIntoFinalPrice: !1
      },
      success: function (e) {
        e && e.error && e.error_msg && displayToast(e.error_msg, "red"), e && !e.error && e.data && displayToast(e.data, "green"), stopLoading(), jQuery(".lds-ring").remove(), e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage") && setTimeout(function () {
          window.open("https://sharkdropship.com/aliexpress", "_blank")
        }, 4e3)
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
    customVariations = buildVariationsForSingleImport(), customVariations = getItemSpecificfromTableModal(customVariations);
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
        action: "wooshark-insert-product-alibay",
        sku: h.simpleSku.toString(),
        title: h.title,
        description: h.description || "",
        images: h.images || [],
        categories: h.productCategoies,
        regularPrice: h.originalPrice.toString(),
        salePrice: h.salePrice.toString(),
        quantity: h.totalAvailQuantity,
        productType: "simple",
        attributes: customVariations.NameValueList || [],
        variations: [],
        isFeatured: jQuery("#isFeatured")[0].checked,
        postStatus: jQuery("#isPublish")[0].checked ? "publish" : "draft",
        shortDescription: h.shortDescription || "",
        productUrl: h.productUrl,
        importVariationImages: !0,
        reviews: h.reviews,
        tags: h.tags,
        includeShippingCostIntoFinalPrice: !1
      },
      success: function (e) {
        e && e.error && e.error_msg && displayToast(e.error_msg, "red"), e && !e.error && e.data && displayToast(e.data, "green"), stopLoading(), jQuery(".lds-ring").remove(), e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage") && setTimeout(function () {
          window.open("https://sharkdropship.com/aliexpress", "_blank")
        }, 4e3)
      },
      error: function (e) {
        jQuery(".lds-ring").remove(), stopLoading(), e && e.responseText && displayToast(e.responseText, "red")
      }
    })
  }

  function buildVariationsForSingleImport() {
    return {
      variations: [],
      NameValueList: []
    }
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

    // $('#pagination-container').on('click', '.page-item-products', function(e) {
    e.preventDefault();

    // Get the clicked page number
    var page = jQuery(this).attr('data-page');

    // Load products for the selected page
    getAllProducts(page);
  });


  jQuery(document).on("click", ".page-link-all-type", function (e) {
    jQuery(".loaderImporttoShopProducts").show()

    // $('#pagination-container').on('click', '.page-item-products', function(e) {
    e.preventDefault();

    // Get the clicked page number
    var page = jQuery(this).attr('data-page');

    // Load products for the selected page
    loadProductsByType(page);
  });




  jQuery(document).on("click", ".page-link-aliexpress", function (e) {
    jQuery(".loaderImporttoShopProducts").show()

    // $('#pagination-container').on('click', '.page-item-products', function(e) {
    e.preventDefault();

    // Get the clicked page number
    var page = jQuery(this).attr('data-page');

    // Load products for the selected page
    loadAllProductsAliExpress(page);
  });


  jQuery(document).on("click", ".page-link-ebay", function (e) {
    jQuery(".loaderImporttoShopProducts").show()

    // $('#pagination-container').on('click', '.page-item-products', function(e) {
    e.preventDefault();

    // Get the clicked page number
    var page = jQuery(this).attr('data-page');

    // Load products for the selected page
    loadAllProductsEbay(page);
  });

  jQuery(document).on("click", ".page-link-amazon", function (e) {
    jQuery(".loaderImporttoShopProducts").show()

    // $('#pagination-container').on('click', '.page-item-products', function(e) {
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







})(jQuery);
