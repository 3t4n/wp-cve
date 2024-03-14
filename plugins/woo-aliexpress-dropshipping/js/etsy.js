(function (jQuery) {

	let isPremuim = false;
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
    function isNotConnected() {
      jQuery("#not-connected").show(), jQuery("#connected").hide();
    }
   
  
  
    let fullProductUrl = '';
  
  
   
  
    // jQuery(document).on("click", "#importProductToShopByUrl", function (e) {
    //   jQuery("#asVariableAliex").show();
    //   var t = jQuery("#productUrl").val();
    //   fullProductUrl = t;
    //   if (globalUrlProduct = t, t) {
    //     var a = getProductId(t);
    //     prepareModal(), a ? importProductGlobally(a) : displayToast("Cannot get product sku", "red")
    //   }
    // })
  
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
        restoreConfiguration()
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
  
  
    function handleProductLoadError(callback) {
      displayToast("Cannot load product details, please try again", "red");
      callback(null);
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
        const languageRadios = document.getElementsByName("language");

        // Initialize a variable to store the selected value
        let selectedLanguage = "";
        
        // Loop through the radio buttons to find the selected one
        for (let i = 0; i < languageRadios.length; i++) {
          if (languageRadios[i].checked) {
            selectedLanguage = languageRadios[i].value;
            break; // Exit the loop once a selected radio button is found
          }
        }
        return selectedLanguage;
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
    function getShippingCost(e) {
      var t = new XMLHttpRequest();
      jQuery("#table-shipping tbody").empty();
      let a = jQuery('input[name="destination"]:checked').val();
      (t.onreadystatechange = function () {
        if (4 == t.readyState && 200 === t.status) {
          let e = t.response,
            a = "";
          try {
            let t = JSON.parse(e).data;
            t &&
              t.length &&
              t.forEach(function (e, t) {
                (a = e.deliveryData || "information not availble"),
                  0 == t
                    ? jQuery("#table-shipping tbody").append(
                      '<tr><td style="width:24%" >  ' +
                      e.company +
                      '  </td><td  style="width:24%">' +
                      a +
                      '</td><td  style="width:24%" class="selectedshippingCostValue" >' +
                      e.cost.value +
                      e.cost.currency +
                      '</td><td  style="width:24%"> <input  name="selectedShippingCost" value=' +
                      t +
                      ' checked type="radio" /></td></tr>'
                    )
                    : jQuery("#table-shipping tbody").append(
                      '<tr><td style="width:24%" >  ' +
                      e.company +
                      '  </td><td  style="width:24%">' +
                      a +
                      '</td><td  style="width:24%" class="selectedshippingCostValue">' +
                      e.cost.value +
                      e.cost.currency +
                      '</td><td style="width:24%"> <input  name="selectedShippingCost" value=' +
                      t +
                      ' type="radio" /></td></tr>'
                    );
              });
          } catch (e) { }
        }
      }),
        t.open("POST", hostname + ":8002/getAliExpressShippingCost", !0),
        t.setRequestHeader("Content-Type", "application/json"),
        t.send(
          JSON.stringify({
            productId: e,
            currency: jQuery('input[name="currency"]:checked').val(),
            destination: a
          })
        );
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
        
        quill.setContents([]);
        quill.setContents(quill.clipboard.convert(t));

          // quill.setContents([]),
          // quill.clipboard.dangerouslyPasteHTML(0, t);
      } else {

        quill.setContents([]);
        quill.setContents(quill.clipboard.convert(htmlEditor));

        // quill.setContents([]), quill.clipboard.dangerouslyPasteHTML(0, htmlEditor);
      }
    }),
      jQuery(document).on("click", "#removeDescription", function (e) {
        jQuery("#removeDescription")[0].checked
          ? ((htmlEditor = quill.root.innerHTML), quill.setContents([]))
          : (
            quill.setContents([]),
            quill.setContents(quill.clipboard.convert(htmlEditor))

            // quill.setContents([]),
            // quill.clipboard.dangerouslyPasteHTML(0, htmlEditor)
            );
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
          : (
            // quill.setContents([]),
            // quill.clipboard.dangerouslyPasteHTML(0, htmlEditor)

            quill.setContents([]),
            quill.setContents(quill.clipboard.convert(htmlEditor))


            );
      }),
      jQuery(document).on("click", "#removeText", function (e) {
        jQuery("#removeText")[0].checked && jQuery("#descriptionContent").html("");
      })
  
    //   jQuery("#includeImageFromDescription")[0].checked && imagesFromDescription && imagesFromDescription.length && imagesFromDescription.each(function(t, e) {
    //     t < 10 && (jQuery('<div><button type="button" class="btn btn-primary" id="removeImage" ><i style="font-size:15px ; margin:5px">Remove Image</i></button><img  src=' + e.currentSrc + " /><div>").appendTo(jQuery("#galleryPicture")), images.push(e.currentSrc))
    // }
    // )
  
  
    // jQuery(document).on("click", "#includeImageFromDescription", function (e) {
    //   jQuery("#includeImageFromDescription")[0].checked &&
    //     imagesFromDescription.forEach(function (e, t) {
    //       t < 10 &&
    //         (jQuery(
    //           '<div><button type="button" class="btn btn-primary" id="removeImage" ><i style="font-size:15px ; margin:5px">Remove Image</i></button><img  src=' +
    //           e.currentSrc +
    //           " /><div>"
    //         ).appendTo(jQuery("#galleryPicture")),
    //           images.push(e.currentSrc));
    //     });
    // });
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
  
          },
        });
      } else {
        stopLoading();
        jQuery(".lds-ring").remove();
      }
    }
  
    
  
  
    // jQuery(document).on("click", ".close-modal", function (e) {
    //   jQuery('.modal').css({ 'display': 'none' })
    // });
  
  
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
          (t.bulkCategories = o),
          displayToast("save categories", "green");
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
          displayToast("save price markup formula"),
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
                displayToast("Configuration saved successfully"),
                jQuery("#savedCorrectlySection").show();
            }
          });
      });
    let productDetailsOldVariationsAndNewVariations = [];
   
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
    function searchProductsEtsy(e) {
      jQuery("#etsy-pagination").empty(),
        jQuery("#etsy-pagination").show(),
        jQuery("#etsy-product-search-container").empty();
      var t = jQuery('input[name="etsyLanguage"]:checked')[0]
        ? jQuery('input[name="etsyLanguage"]:checked')[0].value
        : "en";
      jQuery(".loader2").css({
        display: "block",
        position: "fixed",
        "z-index": 9999,
        top: "50px",
        right: "50px",
        "border-radius": "35px",
        "background-color": "red"
      }),
        searchByKeyWord_etsy(searchKeyword, t, e);
    }
    function searchByKeyWord_etsy(e, t, a) {
      let r = jQuery("#searchKeyword_etsy").val(),
        i = jQuery('input[name="sort"]:checked')[0]
          ? jQuery('input[name="sort"]:checked')[0].value
          : "",
        o = jQuery("#highQualityItems").prop("checked"),
        n = jQuery("#isFreeShipping").prop("checked"),
        l = jQuery("#isFastDelivery").prop("checked"),
        s = jQuery("#minPrice_etsy").val(),
        c = jQuery("#maxPrice_etsy").val(),
        d = getSelectedLanguage(),
        u = jQuery('input[name="currency"]:checked')[0]
          ? jQuery('input[name="currency"]:checked')[0].value
          : "";
      (xmlhttpEtsy = new XMLHttpRequest()),
        (xmlhttpEtsy.onreadystatechange = function () {
          if (4 == xmlhttpEtsy.readyState)
            if (200 === xmlhttpEtsy.status)
              try {
                (data = JSON.parse(xmlhttpEtsy.response).data), console.log(data);
                try {
                  var e = data;
                  if (
                    (e.forEach(function (e) {
                      jQuery(
                        '<div class="card text-center" style="flex: 1 1 20%; box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;margin:10px;border-radius: 10px; padding:10px; box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">  <div class="card-body"><h5 class="card-title" style="font-weight:700"> ' +
                        e.productTitle.substring(0, 70) +
                        '</h5><img src="' +
                        e.imageUrl +
                        '" width="150"  height="150"></img><div>Sale Price: <p class="card-text" style="color:red">' +
                        e.salePrice +
                        '</div></p>Sku: <p class="card-text" id="sku" ">' +
                        e.productId +
                        '</p><div><div><a  style="width:80%" id="importToShop_etsy" class="importToS btn btn-primary">Import to shop</a></div><div><a target="_blank" style="width:80%; margin-top:5px" href="' +
                        e.productUrl +
                        '" class="btn btn-primary">Product url</a></div></div></div></div>'
                      ).appendTo("#etsy-product-search-container");
                    }),
                      displayPAginationForSearchByKeyword_etsy(1e3, a),
                      jQuery(".loader2").css({ display: "none" }),
                      e && e.length)
                  )
                    getAlreadyImportedProducts(
                      e.map(function (e) {
                        return e.productId;
                      })
                    );
                } catch (e) {
                  displayToast("Empty result for this search keyword", "red"),
                    jQuery(".loader2").css({ display: "none" }),
                    displayPAginationForSearchByKeyword_etsy(1e3, a);
                }
              } catch (e) {
                jQuery(".loader2").css({ display: "none" }),
                  displayPAginationForSearchByKeyword_etsy(1e3, a);
              }
            else
              displayToast(
                "Error while getting results, please try again, if issue persist, please contact wooshark support ",
                "red"
              ),
                jQuery(".loader2").css({ display: "none" }),
                displayPAginationForSearchByKeyword_etsy(1e3, a);
        }),
        xmlhttpEtsy.open("POST", hostname + ":8002/searchEtsyProductNewApi", !0),
        xmlhttpEtsy.setRequestHeader("Content-Type", "application/json"),
        xmlhttpEtsy.send(
          JSON.stringify({
            searchKeyword: r,
            pageNo: a,
            language: d,
            sort: i,
            highQualityItems: o,
            currency: u,
            isFreeShipping: n,
            isFastDelivery: l,
            minPrice: s,
            maxPrice: c
          })
        );
    }
    function displayPAginationForSearchByKeyword_etsy(e, t) {
      var a = Math.round(e / 40);
      a > 17 && (a = 17);
      for (var r = 1; r < a; r++)
        r == t
          ? jQuery(
            ' <li style="color:red" id="page-' +
            r +
            '" class="etsy-page-item"><a style="color:red" class="page-link">' +
            r +
            "</a></li>"
          ).appendTo("#etsy-pagination")
          : jQuery(
            ' <li id="page-' +
            r +
            '" class="etsy-page-item"><a class="page-link">' +
            r +
            "</a></li>"
          ).appendTo("#etsy-pagination");
    }
    function setFormula_etsy(e) {
      if (
        ((price = e.replace(",", "")),
          (priceWithFormula = price),
          formsToSave && formsToSave.length)
      ) {
        var t = {};
        if (
          (formsToSave.forEach(function (e) {
            e.min < parseFloat(price) && e.max > parseFloat(price) && (t = e);
          }),
            t)
        ) {
          var a = t.multiply || 1,
            r = math.eval(a),
            i = t.addition || 0,
            o = math.eval(i);
          jQuery(".formulaContent").text(
            "Applied Formula = original price [*] (" + a + ") [+]" + i
          ),
            jQuery(".formulatexcontainer").show(),
            (priceWithFormula = parseFloat(price) * parseFloat(r) + parseFloat(o));
        }
      }
      priceWithFormula &&
        ((priceWithFormula = Number(priceWithFormula).toFixed(2)),
          jQuery("#customPrice").val(parseFloat(priceWithFormula)),
          (customPrice = parseFloat(priceWithFormula)));
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

 
  

    function getItemSpecific_etsy(attributes) {
      jQuery('#table-specific tbody tr').remove();
      jQuery('#table-specific thead tr').remove();
      if (attributes && attributes.length) {
        _.each(attributes, function (item) {
  
          var itemSpecific = '<td contenteditable>' + item.property_name + '</td>';
          var itemSpecificValues = '<td contenteditable>' + item.values + ' ' + getScale(item) + '</td>';
          jQuery('#table-specific tbody').append(jQuery('<tr>' + itemSpecific + itemSpecificValues + '<td><button id="removeAttribute" class="btn btn-danger">X</btton><td></tr>'))
        })
      }
      jQuery('#table-specific tr td[contenteditable]').css({
        'border': '1px solid #51a7e8',
        'box-shadow': 'inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)'
      });
  
  
      // jQuery("#table-specific tbody tr").remove(),
      //   jQuery("#table-specific thead tr").remove(),
      //   e &&
      //   e.length &&
      //   e.forEach(function (e) {
      //     var t = "<td contenteditable>" + e.property_name + "</td>",
      //       a = "<td contenteditable>" + e.values + " " + getScale(e) + "</td>";
      //     jQuery("#table-specific tbody").append(
      //       jQuery(
      //         "<tr>" +
      //         t +
      //         a +
      //         '<td><button id="removeAttribute" class="btn btn-danger">X</btton><td></tr>'
      //       )
      //     );
      //   }),
      //   jQuery("#table-specific tr td[contenteditable]").css({
      //     border: "1px solid #51a7e8",
      //     "box-shadow":
      //       "inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)"
      //   });
    }
    function getScale(e) {
      return e.scale_name || "";
    }
  
    function buildNameListValuesEtsy(variations) {
      var attribuesNamesAndValues = [];
      var attributes = jQuery('#j-product-info-sku').find('dt');
      var propertiesNameValues = [];
      _.each(variations, function (item) {
        _.each(item.property_values, function (element) {
          var variation = propertiesNameValues.find(function (property) {
            return element.property_name == property.name;
          })
          if (!variation) {
            propertiesNameValues.push({ name: element.property_name, value: [element.values[0]] })
          } else {
            if (variation.value.indexOf(element.values[0]) == -1) {
              variation.value.push(element.values[0]);
            }
          }
        });
  
        // for (var index = 0; index < 5; index++) {
  
  
  
  
  
        // console.log('propertiesNameValues', propertiesNameValues);
  
      });
      _.each(propertiesNameValues, function (item) {
        attribuesNamesAndValues.push({
          name: item.name,
          value: item.value
        });
      });
  
  
      return attribuesNamesAndValues;
  
    }
  
  
    function getAttributes_etsy(attributes) {
  
      jQuery('#table-attributes tbody tr').remove();
      jQuery('#table-variations thead tr').remove();
      jQuery('#table-variations tbody tr').remove();
      var tds = '';
      var tdsAttribites = '';
      if (attributes && attributes.length) {
        _.each(attributes, function (item) {
          if (item.name) {
            tds = '<td contenteditable>' + item.name + '</td>' + '<td><span contenteditable> ' + item.value + '</span></td>';
            tdsAttribites = tdsAttribites + '<td  name="' + item.name + '">' + item.name + '</td>';
          }
          jQuery('#table-attributes tbody').append(jQuery('<tr>' + tds + '<td><button id="removeAttribute" class="btn btn-danger">X</btton><td></tr>'))
        })

        jQuery("#table-variations thead").append(jQuery("<tr><td>Image</td>" + tdsAttribites + "<td style='font-weight: 800'>quantity</td><td style='font-weight: 800'>Price</td><td style='font-weight: 800'>Sale price</td><td style='font-weight: 800'>ASIN</td><td>Title</td><td style='font-weight: 800'>Remove</td><td><button disabled id='' class='button-5' style='width:220px'>Import As dropshipping  <span class='newLoaderAllSimple'></span></button></td><td><button disabled id='' class='button-6' style='width:220px;'>Import as affiliate <span class='newLoaderAllAffiliate'></span></button></td></tr>"))


      }
  
  
    }
    function buildNameListValues_etsy(e) {
      var t = [],
        a = (jQuery("#j-product-info-sku").find("dt"), []);
      return (
        e.forEach(function (e) {
          e.property_values.forEach(function (e) {
            var t = a.find(function (t) {
              return e.property_name == t.name;
            });
            t
              ? -1 == t.value.indexOf(e.values[0]) && t.value.push(e.values[0])
              : a.push({ name: e.property_name, value: [e.values[0]] });
          });
        }),
        a.forEach(function (e) {
          t.push({ name: e.name, value: e.value });
        }),
        t
      );
    }

    let globalTitle = '';
    function getVariations_etsy(variations) {
  
      if (variations && variations.length > 1) {
        jQuery('#applyCharmPricingConainer').show();
        jQuery('#applyPriceFormula').show();
        jQuery('#applyPriceFormulaRegularPrice').show();
        jQuery('#inlcudeSkuAttributecheckbox').show();
        jQuery('#importSalePricecheckbox').show();
        jQuery('#priceContainer').hide();
        jQuery('#skuContainer').hide();
        jQuery('#productWeightContainer').hide();
        jQuery('#productType').text('Variable Product');
        jQuery('#no-variations').hide();
        _.each(variations, function (item) {
          var tdsAttribites = '';
          let titleAttribtues = globalTitle;
          let singleImages = '';
          _.each(item.property_values, function (element, index) {
            if (element.property_name && index == 0) {
              if (element.image) {
                tdsAttribites = tdsAttribites + '<td imagePath="'+element.image+'"><img height="50px" width="50px" src="' + element.image + '"></td>';
                singleImages = element.image;
              } else {
                tdsAttribites = tdsAttribites + '<td></td>';
              }
            }
            
            titleAttribtues = titleAttribtues + " - " + element.property_name + " : " + element.values

            tdsAttribites = tdsAttribites + '<td contenteditable name="' + element.property_name + '">' + element.values + '</td>';
          })
          var price = 1;
          if (item.offerings[0].price.amount) {
            price = item.offerings[0].price.amount / (item.offerings[0].price.divisor || 1);
          } else {
            price = item.offerings[0].price.amount / (item.offerings[0].price.divisor || 1);
          }
          price = price.toFixed(2);
          let quantity = item.offerings[0].quantity;
          let sku = item.product_id;
          // var weight = jQuery('#productWeight').val();
          // var weight = '';
          // if (item.offerings[0].quantity > 0) {
          //   tdsAttribites = tdsAttribites + '<td contenteditable >' + item.offerings[0].quantity + '</td><td contenteditable>' + price + '</td><td contenteditable>' + price + '</td><td><button id="removeVariation" class="btn btn-danger">X</button></td><td contenteditable>' + item.offerings[0].offering_id + '</td><td contenteditable>' + weight + '</td><td style="display:none"></td>';
          //   jQuery('#table-variations tbody').append(jQuery('<tr>' + tdsAttribites + '</tr>'))
          //   jQuery('#table-variations tr td[contenteditable]').css({
          //     'border': '1px solid #51a7e8',
          //     'box-shadow': 'inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)'
          //   });
          // }
  

          // var quantitySold = variation.SellingStatus ? variation.SellingStatus.QuantitySold : 0;
          // var availableQuantity = variation.Quantity - quantitySold;
          // var sku = variation.SKU; 
          
    
          tdsAttribites =
          tdsAttribites +
              "<td id='singleQuantity' contenteditable>" + quantity + "</td><td id='singleRegularPrice' contenteditable>" + price + "</td><td id='singleSalePrice' contenteditable>" + price + '</td><td id="singleAsin" contenteditable>' + sku + '</td><td id="singleTitle" contenteditable>' + titleAttribtues + '</td><td><button id="removeVariation" style="background-color:red" class="button-5">X</button></td><td><button id="insertProductAsSimple" class="button-5" style="width:220px">Import As single product(Dropship) <span class="newLoaderSimple"></span> </button></td><td><button id="insertProductAsAffiliate" class="button-6" style="width:220px">Import As single product(Affiliate) <span class="newLoaderAffiliate"></span> </button></td><td id="singleImages" style="display:none">' + singleImages + "</td>"
    
            jQuery("#table-variations tbody").append(jQuery("<tr>" + tdsAttribites + "</tr>"));
            jQuery("#table-variations tr td[contenteditable]").css({
              border: "1px solid #51a7e8",
              "box-shadow": "inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)"
            })
    
            
    
          jQuery("#modal-container #table-variations tr td[contenteditable]").css({
            border: "1px solid #51a7e8",
            "box-shadow": "inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)",
          });

  
        });
        applyPriceFormulaDefault();
      } else {
        jQuery('#no-variations').show();
  
        // jQuery('#priceVariationNotification').hide();
        jQuery('#applyPriceFormula').hide();
        jQuery('#applyPriceFormulaRegularPrice').hide();
        jQuery('#inlcudeSkuAttributecheckbox').hide();
        jQuery('#importSalePricecheckbox').hide();
        jQuery('#priceContainer').show();
        jQuery('#skuContainer').show();
        jQuery('#productType').text('Simple Product');
  
      }
  
  
    
    }

    function getItemSpecificfromTableEtsy(e) {
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


    function fillTheFormEtsy(listingId) {
        let isEtsy = true;
        currentSku = listingId;
        
        if (listingId) {
          try {
            // Disable import buttons
            
            
            startLoading();
            getSelectedLanguage();
          
            
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
              if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                  const data = JSON.parse(xhr.response).data;
                  const productDescription = data.description;
                  const images = [];
                  
                  // Populate form fields
                  jQuery("#customProductTitle").val(data.title);
                  globalTitle = data.title;
                  data.images.forEach(function (image) {
                    images.push(image.url_570xN);
                  });
                  getImagesModal(images);
                  
                  const attributes = data.attributes;
                  const variations = data.variations;
                  const itemWeightAttribute = attributes.find(function (attr) {
                    return attr.property_name === "Item Weight";
                  });
                  const itemPrice = data.price;
                  const divisor = data.divisor;
                  const currencyCode = data.currencyCode;
                  if(currencyCode){
                    jQuery('#currencyCode').text(currencyCode);
                  }
                  if(data.shipping_profile && data.shipping_profile.origin_country_iso){

                    let originCountry = data.shipping_profile.origin_country_iso;
                    jQuery('#originCountry').text(originCountry);
                  }
                  let price = (itemPrice / (divisor || 1) ).toFixed(2);

                  // price = item.offerings[0].price.amount / (item.offerings[0].price.divisor || 1);


                  const itemQuantity = data.quantity;
                  const itemWeightUnitAttribute = attributes.find(function (attr) {
                    return attr.property_name === "Item Weight Unit";
                  });
                  
                  if (itemWeightAttribute && itemWeightUnitAttribute) {
                    weight = itemWeightAttribute + " " + itemWeightUnitAttribute || "";
                  }
                  
                  jQuery("#customPrice").val(price);
                  
                  // Populate saved categories
                  
                  
                  let textToReplace = jQuery("#textToBeReplaced").val();
                  let replacementText = jQuery("#textToReplace").val();
                  fillTags(data.title);
                  if (textToReplace && replacementText) {
                    let title = data.title;
                    
                    let description = data.description;
                    jQuery("#customProductTitle").val(title.replace(textToReplace, replacementText));
                    getHtmlDescription(description.replace(textToReplace, replacementText));
                  } else {
                    jQuery("#customProductTitle").val(data.title);
                    getHtmlDescription(productDescription);
                  }
                  
                  setFormula_etsy(price);
                  getItemSpecific_etsy(attributes);
                  
                  // Build attribute list
                  const attributeList = buildNameListValuesEtsy(variations.products);
                  getAttributes_etsy(attributeList);
                  getVariations_etsy(variations.products);
                  
                  loadCategories(data.tags);

                  if (savedCategories && savedCategories.length) {
                    savedCategories.forEach(function (category, index) {
                      const categoryItem =
                        '<div class="checkbox"><label><input type="checkbox" value="' +
                        category.term_id +
                        '"/>' +
                        category.name +
                        "</label>";
                      jQuery("#customProductCategory").append(jQuery(categoryItem));
                    });
                  }


                  jQuery("#simpleSku").val(listingId);
                  jQuery("#importModal_etsy").click();
                  stopLoading();
                  
                  // Enable import buttons
                 
                } catch (error) {
                  stopLoading();
                  
                  // Enable import buttons
                  jQuery(".importToS").each(function (index, element) {
                    console.log("----- Enabling");
                    jQuery(element).attr("disabled", false);
                  });
                }
              }else if(xhr.readyState === 4 &&  xhr.status === 488){
                stopLoading();
                const data = JSON.parse(xhr.response);
                displayToast(data.error, "red");
              }
            };
            
            xhr.open("POST", "https://wooshark.website:6006/importProductDetails", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.send(JSON.stringify({ listing_id: listingId, language: getSelectedLanguage() }));
          } catch (error) {
            
            
            displayToast(
              "Cannot retrieve product id, please try again. If the issue persists, please contact our support team",
              "red"
            );
            stopLoading();
          }
        } else {
         
          displayToast('Listing ID is invalid or missing', "red");
        }
      }
      

      jQuery('#importModal_etsy').click(function(){
        jQuery('#modal').modal('show'); // replace 'modalId' with the actual id of your modal
    });


    function applyPriceFormulaDefault_etsy() {
      var e = jQuery("#table-variations tbody tr"),
        t = jQuery("#table-variations thead tr")[0].cells.length - 7;
      e.each(function (e, a) {
        var r = calculateAppliedPrice(a.cells[t + 1].textContent);
        a.cells[t + 1].textContent = r.toFixed(2);
      }),
        e.each(function (e, a) {
          var r = calculateAppliedPrice(a.cells[t + 2].textContent);
          a.cells[t + 2].textContent = r.toFixed(2);
        });
    }
    // jQuery(".removeTag").on("click", function () {
    //   // Remove the parent div when the remove button is clicked
    //   jQuery(this).parent().remove();
    // });
    jQuery(document).on("click", ".close-modal", function (e) {
      jQuery('#etsyModal').remove();
    });


  //   jQuery('#etsyModal').on('hide.bs.modal', function () {
  //     // Clear the content of the modal
  //     jQuery(this).find('.modal-content').empty();
  //     // Alternatively, reset specific elements within the modal
  //     jQuery(this).find('input, textarea').val('');
  //     // If using forms, you might also want to reset them
  //     jQuery(this).find('form')[0].reset();
  // }); 
    function prepareModal_etsy() {
      (tagsProduct = []),
        jQuery(
          `
          <button type="button" id="importModal_etsy" style="display: none; position:relative" class="btn btn-primary btn-lg"
          data-bs-toggle="modal" data-bs-target="#etsyModal">Import To Shop</button>
  <div class="modal fade"  tabindex="-1"  style="margin-top: 4%;" id="etsyModal" role="dialog" >
  <div class="modal-dialog" style="max-width:70vw; width:70vw">
  <div class="modal-content"
      style="border-radius: 20px;box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;height:92vh">
      <div class="
  modal-header">
          <h4 class="modal-title">Product customization <span style="color:red" id="productType"></span> <span
                  style="color:red" id="currencyReturned"> </span> Shipped From: &nbsp; <span style="color:red" id="originCountry"></span>
                  Currency Code: <span style="color:red" id="currencyCode"></span></h4>

          <button class="btn btn-danger close-modal" data-bs-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" style="overflow: scroll;">
                  <ul id="tabs" class="nav nav-tabs">
                      <li class="nav-item active" > <a class="nav-link" data-bs-toggle="tab" data-bs-target="#home" role="tab"
                              data-toggle="tab" href="#home">General</a></li>
                      <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu1" role="tab" data-toggle="tab"
                              href="#menu1">Description</a></li>
                      <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu3" role="tab" data-toggle="tab"
                              href="#menu3">Gallery</a></li>
                      <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu4" role="tab" data-toggle="tab"
                              href="#menu4">Reviews</a></li>
                      <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu5" role="tab" data-toggle="tab"
                              href="#menu5">Variations</a></li>
                      <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu6" role="tab" data-toggle="tab"
                              href="#menu6">Specific attributes</a></li>
                      <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu7" role="tab" data-toggle="tab"
                              href="#menu7">Tags</a></li>
                  </ul>
                  <div class="tab-content">
                      <div id="home" class="tab-pane fade show active">





                      <div class="toggle-container" style="padding: 20px;    font-size: 2rem;margin-top: 25px; text-align: center;box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;"> 
                      <label> 
                        <input type="radio" name="productType" id="affiliateProduct" value="affiliate" > Affiliate Product 
                      </label> 
                      <label> 
                          <input type="radio" name="productType" value="dropshipping" checked id="dropshippingProduct"> Dropshipping Product 
                        </label> 
                      
              
                      
                      
                        
                      </div> 
        
                      <div class="form-group" style="margin-top:20px">
                      <label for="title">Product URL:</label> <input id="productCustomUrl" type="text" class="form-control">
                    </div>



                      <div class="form-group">
                      <h3 style="color: #c4b9b9; margin-top:10px" for="title">custom Title:</h3> <input
                          style="height:60px" id="customProductTitle" type="text"
                          placeholder="custom title, if empty original title will be displayed"
                          class="form-control" id="title">
                    </div>

                          <div class="form-group" id="priceContainer" style="display:none">

                         

                              <div class="form-group">
                                  <h3 style="color: #c4b9b9; margin-top: 20px;" for="price">Regular Price: <span
                                          style="color:red" id="formulaContent"><span></h3>
                              </div> <input style="width:97%" id="customPrice" type="number" class="form-control"
                                  id="price">
                              <div class="form-group">
                                  <h3 style="color: #c4b9b9" for="price">Sale Price: <span style="color:red"
                                          id="formulaContent"><span></h3>
                              </div> <input style="width:97%" id="customSalePrice" type="number" class="form-control"
                                  id="price">


                                 
                          </div>
                        
                          <div class="form-group" id="skuContainer" style="display:none">
                              <h3 style="color: #c4b9b9" for="title">Sku <small> (Optional) </small> </h3> <input
                                  style="width: 100%;padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-top: 6px; margin-bottom: 16px; resize: vertical;"
                                  type="text" placeholder="Sku attribute (optional)" class="form-control" id="simpleSku">
                          </div>
                         
                          <div class="form-group" style="margin-top: 30px;"> 
                              <h3 style="color: #c4b9b9" for="title"> Short Description <small> (Optional) </small> </h3>
                              <textarea id="shortDescription" class="form-control" rows="2" id="comment"
                                  placeholder="Short description"></textarea>
                          </div>
                          <div class="checkbox" style="margin-top: 30px;">
                              <label><input id="isPublish" type="checkbox" name="remember"> Publish
                                  (checked = publish | unchecked = draft)</label> </div>
                                  <div class="checkbox"><label><input id="isFeatured" type="checkbox" name="remember"> &nbsp;
                                  Featured product <small>Featuring products on your website is a great way to show your
                                      best selling or popular products from your store</small></label> </div>
  
                                      <h3 style="margin-top:10px; color: #c4b9b9"> Select and add categories from AliExpress </h3>
                                      <div id="shopCategories" style="height: 200px; overflow-y: scroll;"></div>

                                      
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
                          <div class="checkbox"> </div>
                          <div id="galleryPicture" style="overflow-y:scroll;height:500px"> </div>
                      </div>
                      <div id="menu4" class="tab-pane fade in">
                          <div id="customReviews" style="overflow-y:scroll;height:500px"><button class="btn btn-primary"
                                  id="addReview" style="width:100%;margin-top:10px"> Add Review</button>
                              <table id="table-reviews" class="table table-striped">
                                  <thead>
                                      <tr>
                                          <th>Review</th>
                                          <th>Username</th>
                                          <th>Date creation</th>
                                          <th>Rating</th>
                                          <th>Email</th>
                                          <th>Remove</th>
                                      </tr>
                                  </thead>
                                  <tbody></tbody>
                              </table>
                          </div>
                      </div>
                      <div id="menu5" class="tab-pane fade in">
                          <div id="no-variations"
                              style="text-align:center; display:none; padding:20px; margin:30px; background-color:beige">
                              <span style=" text-align:center">This is a simple product, no variations can be
                                  defined</span></div>
                          <h3 class="formulatexcontainer" for="price"
                              style="background-color:beige; padding:15px; margin:20px;  text-align:center"> <span
                                  class="formulaContent">No formula defined yet<span></h3>
                                  <button class="button-5"
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
                                                      2.99) </small> </label> </div><label><input disabled
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
                                              type="number" class="form-control"><button style="flex: 1 1  100px; margin: 5px"
                                              class="button-5" id="globalSalePrice"> Apply</button> </div>
                                      <div style="display:flex"> <input style="flex: 1 1  100px; width:50%;  margin: 5px"
                                              id="addShippingPriceValue" placeholder="Add shipping price" type="number"
                                              class="form-control"><button style="flex: 1 1  100px; margin: 5px"
                                              class="button-5" id="addShippingPrice"> Apply</button> </div>
                                  </div>
                                  <div style="flex: 1 1 50%; display:flex;">
                                      <div style="flex: 1 1 50%; display:flex; justify-content: center;">
                                          <labe style="justify-content: center; font-weight: 800; margin-bottom: 20px" l>
                                              Import as
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
                      <div id="menu6" class="tab-pane fade in"><button class="btn btn-primary" id="addSpecific"
                              style="width:100%"> Add specification</button>
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
                      <div id="menu7" class="tab-pane fade in"><label style="margin-top:10px"> Add Tag to
                              product</label><input id="tagInput" type="text" class="form-control" /><button
                              class="btn btn-primary" style="margin-top:10px" id="addTagToProduct" style="width:100%"> Add
                              tags</button>
                          <div id="tagInputDisplayed" style="color:red"></div>
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
                            data-bs-dismiss="modal">Close</button> <button type="button" id="confirmInsertProductIntoWoocommerce"
                            class="button-5 ">Import <small style="color:grey" id="asVariableAliex"> ( as
                                 Dropshipping product ) </small><span id="loaderImporttoShop" style="display:none"></span></button>
                    </div>
              </div>
          </div>`
  
        ).appendTo(jQuery("#modal-container"));
    }
  
    function fillTags(tagList) {
  
      var tags = tagList.split(' ');
  
      // Iterate through the tags and add them individually
      tags.forEach(function (tag) {
        // Trim any extra whitespace from the tag
        var trimmedTag = tag.trim();
        if(trimmedTag.length > 2){
          tagsProduct.push(tag);
          // Add the trimmed tag as a new div with a remove button
          jQuery("#tagInputDisplayed").append(
            jQuery(
              '<div style="width: fit-content;padding: 10px;background-color: #212148;border-radius: 10px;margin: 10px;">' +
              trimmedTag +
              '<button class="btn btn-danger removeTag">X</button></div>'
            )
          );
        }
        
      });
  
    }
  
    jQuery(document).on("click", ".removeTag", function (e) {
      // Remove the parent div when the remove button is clicked
      jQuery(this).parent().remove();
    });
  
    function buildVariations_etsy() {
      var e = { variations: [], NameValueList: [] };
      
      // Initialize an empty array to store variations
    var variations = [];
      jQuery("#table-attributes tbody tr ").each(function(t, a) {
        
       
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
  
    // function buildVariations_etsy() {
    //   var e = { variations: [], NameValueList: [] };
    //   jQuery("#table-attributes tr").each(function (t, a) {
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
    //             value: r.cells[t].textContent.trim(),
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
    //             availQuantity: r.cells[t].textContent || 1,
    //             salePrice: r.cells[t + 2].textContent,
    //             regularPrice: r.cells[t + 1].textContent,
    //             attributesVariations: i,
    //             weight:
    //               r.cells[t + 5].textContent || jQuery("#productWeight").val()
    //           });
    //       }
    //     }),
    //     e
    //   );
    // }
   
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
      }),
      jQuery(document).on("click", "#seachProductsButton_etsy", function (e) {
        searchProductsEtsy(1);
      })
    
      function getProductIdFromUrl(url) {
        // const match = url.match(/listing_id=(\d+)|\/(\d+)\//);
        // if (match) {
        //   // Check if the first group is present and not null
        //   if (match[1] && match[1] !== "null") {
        //     return match[1];
        //   }
        //   // Check if the second group is present and not null
        //   if (match[2] && match[2] !== "null") {
        //     return match[2];
        //   }
        // }
        // return null;

        var productIdMatch = url.match(/\/(\d+)(?:\?|\/|$)/);
      
        if (productIdMatch && productIdMatch[1]) {
          var productId = productIdMatch[1];
          return productId;
        } else {
          displayToast('Failed to get product id from url', 'red');
        }



      }
      
    
      

      jQuery(document).on("click", "#importProductToShopByUrl_etsy", function (e) {
        let t = jQuery("#productUrl_etsy").val();
        if (t) {
          fullProductUrl = t;
          globalUrlProduct = t;
          let e = t.indexOf("/listing/");
          if (e > -1) {
            let id = getProductIdFromUrl(t);
            prepareModal_etsy(), 
            fillTheFormEtsy(id);
          }
        }
      })
      jQuery(document).on("click", "#importProductToShopBySky_etsy", function (e) {
        var t = jQuery("#productSku_etsy").val();
        prepareModal_etsy(), fillTheFormEtsy(t);
      }),
      
     
     
      jQuery(document).on("click", "#confirmInsertProductIntoWoocommerce", function (e) {
        jQuery("#loaderImporttoShop").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'));
        jQuery("#loaderImporttoShop").show();
handleFreeVersion();  
        var t = [];
        let ee = "";
  
        var a = buildVariations_etsy(),
          r =
            jQuery("#customProductTitle").val() ||
            jQuery("head")
              .find("title")
              .text(),
          i = jQuery("#shortDescription").val() || "",
          o = jQuery("#customPrice").val() || "",
          n = jQuery("#customSalePrice").val() || "";
        jQuery("#simpleSku").val();
        let s = [];
        jQuery("#customProductCategory input:checked").each(function () {
          s.push(jQuery(this).attr("value"));
        });
        var l = a.NameValueList;
        let c =  "https://www.etsy.com/listing/" + currentSku;
          
  
        (t = getReviews()),
          // jQuery("#isImportProductDescriptionSingleImport").prop("checked") &&
          (e = quill.root.innerHTML),
  
          a = getItemSpecificfromTableEtsy(a),
        jQuery("#isImportImageVariationsSingleImport").prop("checked");
        let d = jQuery("#isFeaturedProduct").prop("checked"),
          u = jQuery("#isPublishProductSingleImport").prop("checked"),
          p = jQuery("#includeShippingCostIntoFinalPrice").prop("checked"),
          y = [];
        tagsProduct && tagsProduct.length && (y = tagsProduct);
         
        let productUrl = jQuery('#productCustomUrl').val();


        if(jQuery('#affiliateProduct').prop('checked')){
          jQuery.ajax({
            url: wooshark_params.ajaxurl,
            type: "POST",
            dataType: "JSON",
            data: {
              
                              nonce: wooshark_params.nonce,
              action: "theShark_alibay_insertProductInWoocommerceAffiliate",
              sku: currentSku.toString(),
              title: r,
              description: e || "",
              productType:
                    "external",
              images: images || [],
              categories: s,
              regularPrice: o.toString(),
              salePrice: n.toString(),
              quantity: 33,
              isFeatured: d,
              postStatus: u ? "publish" : "draft",
              shortDescription: i || "",
              productUrl: c,
              importVariationImages: !1,
              reviews: t,
              tags: y,
              includeShippingCostIntoFinalPrice: p,
              attributes: l && l.length ? l : [],
              remoteCategories: getSelectedCategories(),
              affiliateLink: jQuery('#productCustomUrl').val()


              // variations: a.variations && a.variations.length ? a.variations.splice(1) : []
            },
            success: function (e) {
              e && e.error && e.error_msg && displayToast(e.error_msg, "red"),
                e && !e.error && e.data && displayToast(e.data, "green"),
                stopLoading();
                let postId = e.postId;
                insertVariationsInSets(a.variations, postId, 0);
            },
            error: function (e) {
              console.log("****err", e),
                stopLoading(),
                e && e.responseText && displayToast(e.responseText, "red");
            }
          });

        }else{
          jQuery.ajax({
            url: wooshark_params.ajaxurl,
            type: "POST",
            dataType: "JSON",
            data: {
              
                              nonce: wooshark_params.nonce,
              action: "wooshark-insert-product-alibay",
              sku: currentSku.toString(),
              title: r,
              description: e || "",
              productType:
                a.variations && a.variations.length ? "variable" : "simple",
              images: images || [],
              categories: s,
              regularPrice: o.toString(),
              salePrice: n.toString(),
              quantity: 33,
              isFeatured: d,
              postStatus: u ? "publish" : "draft",
              shortDescription: i || "",
              productUrl: c,
              importVariationImages: !1,
              reviews: t,
              tags: y,
              includeShippingCostIntoFinalPrice: p,
              attributes: l && l.length ? l : [],
              remoteCategories: getSelectedCategories()

              // variations: a.variations && a.variations.length ? a.variations.splice(1) : []
            },
            success: function (e) {
              e && e.error && e.error_msg && displayToast(e.error_msg, "red"),
                e && !e.error && e.data && displayToast(e.data, "green"),
                stopLoading();
                let postId = e.postId;
                insertVariationsInSets(a.variations, postId, 0);
            },
            error: function (e) {
              console.log("****err", e),
                stopLoading(),
                e && e.responseText && displayToast(e.responseText, "red");
            }
          });
        }
        
        
       
        // } else displayToast("reached weekly import limit, you can upgrade or wait for the next week");
      });
  
  
    
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
  
    jQuery(document).on("click", "#btn-close", function (e) {
  
    });
  
  
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
          includeShippingCostIntoFinalPrice: !1,
          remoteCategories: getSelectedCategories()

        },
        success: function (e) {
          e && e.error && e.error_msg && displayToast(e.error_msg, "red"), e && !e.error && e.data && displayToast(e.data, "green"), stopLoading(), jQuery(".lds-ring").remove(), e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage") && setTimeout(function () {
            window.open("https://sharkdropship.com/wooshark-dropshipping/", "_blank")
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
          remoteCategories: getSelectedCategories()

        },
        success: function (e) {
    // insertVariationsInSets_ebay(e.variations, e.postId, 0);
          e && e.error && e.error_msg && displayToast(e.error_msg, "red"), e && !e.error && e.data && displayToast(e.data, "green"), stopLoading(), jQuery(".lds-ring").remove(), e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage") && setTimeout(function () {
            window.open("https://sharkdropship.com/wooshark-dropshipping/", "_blank")
          }, 4e3)
        },
        error: function (e) {
          jQuery(".lds-ring").remove(), stopLoading(), e && e.responseText && displayToast(e.responseText, "red")
        }
      })
    }


    // function insertProductAsSingle(e) {
    //   let t = jQuery("input[value=titleASIN]")[0].checked,
    //     a = jQuery("input[value=galleryASIN]")[0].checked;
    //   var i = [];
    //   jQuery("#customProductCategory input:checked").each(function () {
    //     i.push(jQuery(this).attr("value"))
    //   });
    //   var r = getReviews(),
    //     o = (jQuery("#customPrice").val(), window.location.href),
    //     n = o.indexOf("/dp/");
    //   n < 0 && (n = o.indexOf("/gp/")), n += 4;
    //   window.location.href;
    //   var l = jQuery("#customProductTitle").val();
    //   let s = [];
    //   tagsProduct && tagsProduct.length && (s = tagsProduct);
    //   var c = quill.root.innerHTML,
    //     u = i;
    //   customVariations = buildVariationsForSingleImport(), 
    //   customVariations = getItemSpecificfromTableModal(customVariations);
    //   var d = jQuery("#shortDescription").val(),
    //     p = jQuery("#isPublish")[0].checked,
    //     y = (e.asin, jQuery("#isFeatured")[0].checked);
    //   if (generalPreferences.importReviewsGeneral || (r = []), generalPreferences.importDescriptionGeneral || (c = ""), generalPreferences.textToReplace && generalPreferences.textToBeReplaced) {
    //     var m = new RegExp(generalPreferences.textToBeReplaced, "g");
    //     c = c.replace(m, generalPreferences.textToReplace)
    //   }
    //   var g = !0;
    //   generalPreferences.importSalePriceGeneral || (g = !1);
    //   jQuery("#customSalePrice").val();
    //   let h = {};
    //   h = {
    //     title: t && e.title ? e.title : l,
    //     currentPrice: e.readyState,
    //     originalPrice: e.regularPice,
    //     description: c,
    //     images: a && e.images && e.images.length ? e.images : images,
    //     totalAvailQuantity: e.quantity || 1,
    //     productUrl: globalUrlProduct,
    //     isPublish: p,
    //     productCategoies: u,
    //     productWeight: "",
    //     reviews: r,
    //     shortDescription: d,
    //     simpleSku: e.asin,
    //     importSalePrice: g,
    //     salePrice: e.salePrice,
    //     featured: y,
    //     tags: s,
    //     affiliateLink: e.isAffiliate ? e.productUrl : "",
    //     button_text: e.isAffiliate ? jQuery("#customBuyNow").val() : "",
    //     variations: customVariations
    //   }, jQuery.ajax({
    //     url: wooshark_params.ajaxurl,
    //     type: "POST",
    //     dataType: "JSON",
    //     data: {
    //       
    //                 nonce: wooshark_params.nonce,
    // action: "wooshark-insert-product-alibay",
    //       sku: h.simpleSku.toString(),
    //       title: h.title,
    //       description: h.description || "",
    //       images: h.images || [],
    //       categories: h.productCategoies,
    //       regularPrice: h.originalPrice.toString(),
    //       salePrice: h.salePrice.toString(),
    //       quantity: h.totalAvailQuantity,
    //       productType: "simple",
    //       attributes: customVariations.NameValueList || [],
    //       variations: [],
    //       isFeatured: jQuery("#isFeatured")[0].checked,
    //       postStatus: jQuery("#isPublish")[0].checked ? "publish" : "draft",
    //       shortDescription: h.shortDescription || "",
    //       productUrl: h.productUrl,
    //       importVariationImages: !0,
    //       reviews: h.reviews,
    //       tags: h.tags,
    //       includeShippingCostIntoFinalPrice: !1
    //     },
    //     success: function (e) {
    //       e && e.error && e.error_msg && displayToast(e.error_msg, "red"), e && !e.error && e.data && displayToast(e.data, "green"), stopLoading(), jQuery(".lds-ring").remove(), e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage") && setTimeout(function () {
    //         window.open("https://sharkdropship.com/wooshark-dropshipping/", "_blank")
    //       }, 4e3)
    //     },
    //     error: function (e) {
    //       jQuery(".lds-ring").remove(), stopLoading(), e && e.responseText && displayToast(e.responseText, "red")
    //     }
    //   })
    // }


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
      
      if(jQuery(row).find('#singleAsin').text() == 'undefined'){
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
  
  
    jQuery(document).on("click", "#ImportImagesFromGallery", function (event) {
      let galleryImages = jQuery("#galleryPicture img");
      let newContent = quill.root.innerHTML;
    
      jQuery(galleryImages).each(function (index, image) {
        newContent = newContent + "<div>" + image.outerHTML + "</div>";
      });

            quill.setContents([]);
            quill.setContents(quill.clipboard.convert(newContent));


    
      // quill.setContents([]);
      // quill.clipboard.dangerouslyPasteHTML(0, newContent);
    });
  


    function loadCategories(tags) {
      const container = document.getElementById('shopCategories');
      let names = new Set(); // To store unique names
  
      if(tags && tags.length > 0){
        tags.forEach(tag => {
          if (tag) {
            if (!names.has(tag)) {
              names.add(tag);
              const div = document.createElement('div');
              div.className = 'category-item-remote';
              div.innerHTML = `<input type="checkbox" class="category-checkbox-remote" value="${tag}">${tag}`;

              // div.innerHTML = `<input type="checkbox" class="category-checkbox-remote">${subGroup.name}`;
              container.appendChild(div);
            }
          }
          
    });
      }
      
  
        // // Add scrollable container
        // const categoryContainer = document.createElement('div');
        // categoryContainer.className = 'category-container-remote';
        // categoryContainer.appendChild(container);
        // document.body.appendChild(categoryContainer);
      
  
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
  
    if(isPremuim){
      jQuery('.freeAds').hide();
    }
    function handleFreeVersion(){
      if(!isPremuim){
        displayToastLonger("The free version of the software does not import all product details.", "orange");
      }
    }
  
  
  })(jQuery);
  