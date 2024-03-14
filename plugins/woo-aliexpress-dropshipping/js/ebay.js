(function (jQuery) {

	var items = "";
	let baseUrl = "https://thesharkdropship.com";
	let savedCategories;
	let formsToSave;
	var imagesFromDescription = [];

	let isPremuim = false;


	let savedFormula;
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
				let commonConfiguration, singleUpdateConfiguration, singleImportConfiguration, bulkCategories;


				if (response && response._savedConfiguration_alibay && response._savedConfiguration_alibay.commonConfiguration) {
					configurationData = response._savedConfiguration_alibay;
					commonConfiguration = configurationData.commonConfiguration;
					singleUpdateConfiguration = configurationData.sinleUpdateConfiguration;
					singleImportConfiguration = configurationData.singleImportConfiguration;
					bulkCategories = configurationData.bulkCategories;
					savedFormula = configurationData.savedFormula;

					if (commonConfiguration) {
						if (commonConfiguration.language) {
							jQuery("[name=language][value=" + commonConfiguration.language + "]").attr("checked", true);
							jQuery('<h4 style="font-weight:bold;"> Current Language: ' + commonConfiguration.language + '  </h4>').appendTo(".currencyDetails");
						}

						if (commonConfiguration.currency) {
							jQuery("[name=currency][value=" + commonConfiguration.currency + "]").attr("checked", true);
							jQuery('<h4 style="font-weight:bold;"> Current currency: ' + commonConfiguration.currency + '  </h4>').appendTo(".currencyDetails");
						}
					}


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


	//   restoreFormula();



	jQuery(document).on("click", "#ebayimportToShopBulk_ebay", function (e) {
		// if (
		//   "false" != localStorage.getItem("_isAuthorized_alibay") &&
		//   null != localStorage.getItem("_isAuthorized_alibay")
		// ) {
		globalUrlProduct = jQuery(this).parents(".card").find("#productUrlByCard").attr("href");

		prepareModal();
		var t = "";
		try {
			(t = jQuery(this)
				.parents(".card")
				.find("#sku")[0].innerText),
				console.log("------", t),
				t && buildEbayProduct(t);

		} catch (e) {
			jQuery(".ebayImportToS").each(function (e, t) {
				console.log("----- un - disabling 2"), jQuery(t).attr("disabled", !1);
			}),
				displayToast(
					"cannot retrieve product id, please try again, if the issue persists, please contact wooebayimporter@gmail.com",
					"red"
				);
		}
		// } else displayToast("please activate your account", "red");
	}),
		jQuery(document).on("click", "#seacheBayProductsButton", function (e) {
			searcheBayProducts(1);
		});

	let generalPreferences = {
		importSalePriceGeneral: true,
		importDescriptionGeneral: true,
		importReviewsGeneral: true,
		importVariationsGeneral: true,
		reviewsPerPage: 10,
		setMaximimProductStock: 0,
	}


	var isNotDraw = !1;
	function getAlreadyImportedProducts_ebay(e) {
		jQuery.ajax({
			url: wooshark_params.ajaxurl,
			type: "POST",
			dataType: "JSON",
			data: {
				
				                nonce: wooshark_params.nonce,

				action: "alibay-get-already-imported-products_for_ebay",
				listOfSkus: e
			},
			success: function (e) {
				let t = e;
				t && t.length && displayAlreadyImportedIcon_ebay(t),
					console.log("****response", e);
			},
			error: function (e) {
				e.responseText, console.log("****err", e), stopLoading();
			},
			complete: function () {
				console.log("SSMEerr"), stopLoading();
			}
		});
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

	function displayPAginationForSearchByKeyword_ebay(e, t) {
		jQuery("#ebay-pagination .ebay-page-item").remove();
		jQuery("#ebay-pagination").show();

		var a = Math.round(e / 40);
		a > 17 && (a = 17);
		for (var i = 1; i < a; i++)
			i == t
				? jQuery(
					' <li style="color:red" id="page-' +
					i +
					'" class="ebay-page-item"><a style="color:red" class="page-link">' +
					i +
					"</a></li>"
				).appendTo("#ebay-pagination")
				: jQuery(
					' <li id="page-' +
					i +
					'" class="ebay-page-item"><a class="page-link">' +
					i +
					"</a></li>"
				).appendTo("#ebay-pagination");
	}
	function displayAlreadyImportedIcon_ebay(e) {
		if (e && e.length) {
			let a = e.map(function (e) {
				return e.sku;
			}),
				i = jQuery("#ebay-product-search-container .card");
			for (var t = 0; t < i.length; t++) {
				let e = jQuery(i[t]).find("#sku")[0].innerText;
				if (a.indexOf(e) > -1) {
					jQuery(
						'<div><a  style="width:80%; margin-top:5px" id="alreadyImported" class=" btn btn-warning">Already imported</a></div>'
					).appendTo(jQuery(i[t]));
				}
			}
		}
	}
	function searcheBayProducts(e) {
		var t = jQuery('input[name="ebayLanguage"]:checked')[0]
			? jQuery('input[name="ebayLanguage"]:checked')[0].value
			: "EBAY-US";

		jQuery("#ebay-product-search-container").empty();
		let a = jQuery("#searchKeyword_ebay").val();
		if (!a) {
			displayToast("Search keyword cannot be empty", "red");
			return;
		}
		let i = jQuery('input[name="sort"]:checked')[0]
			? jQuery('input[name="sort"]:checked')[0].value
			: "",
			r = jQuery("#minPrice").val(),
			o = jQuery("#maxPrice").val(),
			n = jQuery("#isFreeShipping")[0].checked;

		a
			? ((xmlhttp = new XMLHttpRequest()),
				(xmlhttp.onreadystatechange = function () {
					if (4 == xmlhttp.readyState && 200 === xmlhttp.status)
						try {
							let a = JSON.parse(xmlhttp.response);
							data = a.data;
							let i = parseInt(a.totalResults);
							console.log(data);
							try {
								var t = data;
								if (
									(t.forEach(function (e) {
										jQuery(
											'<div class="card text-center" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset; flex: 1 1 20%; margin:10px; padding:10px">  <div class="card-body"><h5 class="card-title"> ' +
											e.title.substring(0, 70) +
											'</h5><img src="' +
											e.image +
											'" width="150"  height="150"></img><div>Price: <p class="card-text" ">' +
											e.price +
											'</div></p>Sku: <p class="card-text" id="sku" ">' +
											e.id +
											"</p><div><div> <h1>Shipping cost</h1>" +
											e.shippingInfo +
											"</div><div> <h1>Seller name</h1>" +
											e.sellerInfo +
											"</div><div> <h1>Location</h1>" +
											e.location +
											'</div><div><a  style="width:80%; box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;" id="ebayimportToShopBulk_ebay" class="ebayImportToS btn btn-primary">Import to shop</a></div>' +
											'<div data-product-url="' + e.productURl + '"><a id="productUrlByCard" target="_blank" style="width:80%; margin-top:5px" href="' +
											e.productURl +
											'" class="btn btn-primary">Product url</a></div>' +
											// '<div data-product-url="' + e.productURl + '"><a target="_blank" style="width:80%; margin-top:5px" href="' +
											// e.productURl +
											// '" class="btn btn-primary" style="background-color:#d242f4 !important" >Add to waiting list</a></div>'+
											'</div></div></div>'
										).appendTo("#ebay-product-search-container");
									}),
										displayPAginationForSearchByKeyword_ebay(i, e),
										jQuery(".loader2").css({ display: "none" }),
										t && t.length)
								)
									getAlreadyImportedProducts_ebay(
										t.map(function (e) {
											return e.id;
										})
									);
							} catch (e) {
								console.log("------ error ", e),
									displayToast("Empty result for this search keyword", "red");
							}
						} catch (e) { }
				}),
				xmlhttp.open("POST", baseUrl + ":8002/searchEbayProductsByName", !0),
				xmlhttp.setRequestHeader("Content-Type", "application/json"),
				xmlhttp.send(
					JSON.stringify({
						searchKeyword: a,
						globalId: t,
						pageNo: e,
						isFreeShipping: n,
						minPrice: r,
						maxPrice: o,
						sortOrder: i,
						searchBySellername: jQuery('#searchBySellername').val() ? jQuery('#searchBySellername').val() : "",

					})
				))
			: displayToast("Search keyword cannot be empty", "red");
	}
	function importeBayProducts(e) {

		var t = new XMLHttpRequest();
		t.onreadystatechange = function () {
			handleServerResponseebay(t, !0);
		};
		t.open("POST", baseUrl + ":8002/wordpress", !0),
			t.setRequestHeader("Content-Type", "application/json"),
			t.send(
				JSON.stringify({
					aliExpressProduct: e,
					isPluginWordpress: !0,
					isVariationImage: !1,
					isPublish: !0,
					clientWebsite: website,
					clientKey: key_client,
					clientSecretKey: sec_client,
				})
			);

	}
	function handleServerResponseebay(e, t) {
		if (4 == e.readyState) {
			jQuery(".loader2").css({ display: "none" });
			var a = e.status;
			if (200 === a) {
				if (e.response)
					try {
						(i = JSON.parse(e.response)) && i.data;
						displayToast("Product imported successfully", "green"),
							incrementAllowedImport(),
							jQuery(".loader2").css({ display: "none" });
					} catch (e) {
						displayToast("exception during import", "red"),
							jQuery(".loader2").css({ display: "none" });
					}
			} else if (0 == a)
				displayToast(
					"Error establishing connection to server This can be caused by 1- Firewall block or filtering 2- An installed browser extension is mucking things",
					"red"
				),
					jQuery(".loader2").css({ display: "none" }),
					jQuery(".ebayImportToS").each(function (e, t) {
						console.log("----- un - disabling"), jQuery(t).attr("disabled", !1);
					});
			else if (500 == a)
				displayToast(
					"The server encountered an unexpected condition which prevented it from fulfilling the request, please try again or inform us by email wooebayimporter@gmail.com",
					"red"
				),
					jQuery(".loader2").css({ display: "none" }),
					jQuery(".ebayImportToS").each(function (e, t) {
						console.log("----- un - disabling"), jQuery(t).attr("disabled", !1);
					});
			else if (413 == a)
				displayToast(
					"The server is refusing to process a request because the request entity is larger than the server is willing or able to process. The server MAY close the connection to prevent the client from continuing the request.",
					"red"
				),
					jQuery(".loader2").css({ display: "none" }),
					jQuery(".ebayImportToS").each(function (e, t) {
						console.log("----- un - disabling"), jQuery(t).attr("disabled", !1);
					});
			else if (504 == a)
				displayToast(
					"Gateway Timeout Error, the server, acting as a gateway, timed out waiting for another server to respond",
					"red"
				),
					jQuery(".loader2").css({ display: "none" }),
					jQuery(".ebayImportToS").each(function (e, t) {
						console.log("----- un - disabling"), jQuery(t).attr("disabled", !1);
					});
			else if (
				(jQuery(".loader2").css({ display: "none" }),
					jQuery(".ebayImportToS").each(function (e, t) {
						console.log("----- un - disabling"), jQuery(t).attr("disabled", !1);
					}),
					e.response)
			)
				try {
					var i;
					displayToast((i = JSON.parse(e.response)) ? i.data : "", "red");
				} catch (e) {
					displayToast("error", "red");
				}
			jQuery(".loader2").css({ display: "none" }),
				jQuery(".ebayImportToS").each(function (e, t) {
					console.log("----- un - disabling"), jQuery(t).attr("disabled", !1);
				});
		}
	}
	function getCurrentTotalImportItemsValuesebay() {
		var e = localStorage.getItem("totalImportItemseBay");
		return e ? parseInt(e) : 1;
	}
	function incrementAllowedImport() {
		var e = getCurrentTotalImportItemsValuesebay() + 1;
		localStorage.setItem("totalImportItemseBay", e),
			jQuery("#remaining").text(
				"Imported products: " + localStorage.getItem("totalImportItemseBay") || 1
			);
	}
	function getEbayProductDetailsByApi(e, t) {

		let siteId = getEbaySiteId(globalUrlProduct);

		var a = jQuery('input[name="ebayLanguage"]:checked')[0]
			? jQuery('input[name="ebayLanguage"]:checked')[0].value
			: "EBAY-US",
			i = new XMLHttpRequest();
		(i.onreadystatechange = function () {
			// if (4 == i.readyState)
			//   if (200 === i.status) {
			if (4 == i.readyState && 200 === i.status) {

				var e = JSON.parse(i.response);
				e.variations, e.currentPrice, e.description, e.type;
				t(e);
			} else if (4 == i.readyState && 522 === i.status) {

				//   let e = JSON.parse(a.response).data;

				var data = JSON.parse(i.response);
				displayToast(
					data.error[0].ShortMessage, "red"
				);

				stopLoading();
			}

			else if (4 == i.readyState && 200 !== i.status) {

				//   let e = JSON.parse(a.response).data;

				var data = JSON.parse(i.response);
				displayToast(
					"Error while getting product details", "red"
				);
				stopLoading();

			}
		}),
			i.open("POST", baseUrl + ":8008/getEbayVariationsNewApi", !0),
			i.setRequestHeader("Content-Type", "application/json"),
			i.send(JSON.stringify({ productId: e, globlId: a, isPlugin: true, siteId: siteId }));
	}
	function getSalePrice(e) {
		jQuery("#customPrice").val(e);
	}
	function getRegularPrice(e) {
		jQuery("#customSalePrice").val(e);
	}
	function getQuantity(e) {
		jQuery("#quantityEbay").val(e);
	}
	function getSku(e) {
		if (e) {
			e.Value;
			jQuery("#simpleSku");
		}
	}
	// function calculateAppliedPrice(e) {
	// 	var t = (e = e.toString().replace(",", ""));
	// 	if (formsToSave && formsToSave.length) {
	// 		var a = {};
	// 		if (
	// 			(formsToSave.forEach(function (t) {
	// 				t.min < parseFloat(e) && t.max > parseFloat(e) && (a = t);
	// 			}),
	// 				a)
	// 		) {
	// 			var i = a.multiply || 1,
	// 				r = math.eval(i),
	// 				o = a.addition || 0,
	// 				n = math.eval(o);
	// 			jQuery(".formulaContent").text(
	// 				"Applied Formula = original price [*] (" + i + ") [+]" + o
	// 			),
	// 				jQuery(".formulatexcontainer").show(),
	// 				(t =
	// 					parseFloat(e) +
	// 					(parseFloat(e) * parseFloat(r)) / 100 +
	// 					parseFloat(n));
	// 		}
	// 	}
	// 	return t ? ((t = Number(t).toFixed(2)), parseFloat(t)) : parseFloat(t);
	// }

	function calculateAppliedPrice(originalPrice) {
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


	function applyPriceFormulaDefault_ebay() {
		var e = jQuery("#table-variations tbody tr"),
			t =
				jQuery("#modal-container_ebay #table-variations thead tr")[0].cells
					.length - 8;
		e.each(function (e, a) {
			a.cells[t + 1].textContent = calculateAppliedPrice(
				a.cells[t + 1].textContent
			);
		}),
			e.each(function (e, a) {
				a.cells[t + 2].textContent = calculateAppliedPrice(
					a.cells[t + 2].textContent
				);
			});
	}
	function getHtmlDescription_ebay(e) {
		try{

			imagesFromDescription = jQuery(e).find("img");
		}catch(e){
			console.log('error while getting images from description', e);
		}
		(descriptionContentFromUrl = e),
			jQuery("#modal-container_ebay #descriptionContent").html(
				descriptionContentFromUrl
			);
		quill = new Quill("#editor", {
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
					["clean"],

				],

			},
			theme: "snow"
		});
		const t = Quill.import("formats/image"),
			a = Quill.import("parchment");
		quill &&
			quill.root &&
			quill.root.addEventListener("click", e => {
				let i = a.find(e.target);
				i instanceof t && quill.setSelection(i.offset(quill.scroll), 1, "user");
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
	  

	let globalTitle = "";
	function getVariations(variations, variationPictures, identifier) {
		if (variations && variations.length) {
			variations.forEach(function (variation) {
				var variationHtml = "";
				var attributeHtml = "";
				let singleImages = "";
				let titleAttribtues = globalTitle;
				let imageCell = "";

				attributeHtml = "";
				variation &&
					variation.VariationSpecifics &&
					variation.VariationSpecifics.NameValueList &&
					variation.VariationSpecifics.NameValueList.forEach(function (attribute, index) {
						if (variationPictures) {
							var pictureSets = variationPictures[0].VariationSpecificPictureSet;
							pictureSets.forEach(function (pictureSet) {
								if (pictureSet.VariationSpecificValue == attribute.Value[0] && !attributeHtml) {
									attributeHtml =
										'<td imagePath="' + pictureSet.PictureURL[0] + '"><img height="50px" width="50px" src="' +
										pictureSet.PictureURL[0] +
										'"></td>';
								}
								singleImages = pictureSet.PictureURL[0];
							});


						}

						// attributeHtml +=
						//   "<td>" + attribute.Name.toLowerCase().replace(/ /g, "-") + "</td>";
						if (!attributeHtml) {
							attributeHtml = '<td></td>';
						}
						attributeHtml = attributeHtml +
							'<td name="' + attribute.Name + '">' + attribute.Value + "</td>";



						titleAttribtues = titleAttribtues + " - " + attribute.Name + " : " + attribute.Value
					});

				if (!attributeHtml) {
					attributeHtml = '<td></td>';
				}

				var quantitySold = variation.SellingStatus ? variation.SellingStatus.QuantitySold : 0;
				var availableQuantity = variation.Quantity - quantitySold;
				var sku = variation.SKU;


				attributeHtml =
					attributeHtml +
					"<td id='singleQuantity' contenteditable>" + availableQuantity + "</td><td id='singleRegularPrice' contenteditable>" + variation.StartPrice.Value + "</td><td id='singleSalePrice' contenteditable>" + variation.StartPrice.Value + '</td><td id="singleAsin" contenteditable>' + sku + '</td><td id="singleTitle" contenteditable>' + titleAttribtues + '</td><td><button id="removeVariation" style="background-color:red" class="button-5">X</button></td><td><button id="insertProductAsSimpleEbay" class="button-5" style="width:220px" title="Import As Single (dropshipping) Tooltip Text">Import As Single (Dropshipping) <span class="newLoaderSimple"></span> </button></td><td><button id="insertProductAsAffiliateEbay" class="button-6" style="width:220px">Import As Single (Affiliate) <span class="newLoaderAffiliate"></span> </button></td><td id="singleImages" style="display:none">' + singleImages + "</td>"

				jQuery("#table-variations tbody").append(jQuery("<tr>" + attributeHtml + "</tr>"));
				jQuery("#table-variations tr td[contenteditable]").css({
					border: "1px solid #51a7e8",
					"box-shadow": "inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)"
				})



				jQuery("#modal-container_ebay #table-variations tr td[contenteditable]").css({
					border: "1px solid #51a7e8",
					"box-shadow": "inset 0 1px 2px rgba(0,0,0,0.075), 0 0 5px rgba(81,167,232,0.5)",
				});


			});

			applyPriceFormulaDefault_ebay();
		}
	}

	function getItemSpecific_ebay(e) {
		e &&
			e.NameValueList &&
			e.NameValueList.length &&
			(jQuery("#modal-container_ebay #table-specific tbody tr").remove(),
				jQuery("#modal-container_ebay #table-specific thead tr").remove(),
				e.NameValueList.forEach(function (e) {
					e.outerText;
					var t = "<td contenteditable>" + e.Name + "</td>",
						a = "<td contenteditable>" + e.Value + "</td>";
					jQuery("#modal-container_ebay #table-specific tbody").append(
						jQuery(
							"<tr>" +
							t +
							a +
							'<td><button id="removeAttribute" class="btn btn-danger">X</btton><td></tr>'
						)
					);
				}));
	}
	function getAttributes_ebay(e) {
		if (e) {
			jQuery("#modal-container_ebay #table-attributes tbody tr").remove(),
				jQuery("#modal-container_ebay #table-variations thead tr").remove(),
				jQuery("#modal-container_ebay #table-variations tbody tr").remove();
			var t = e.NameValueList,
				a = "",
				i = "";
			t &&
				t.length &&
				t.forEach(function (e) {
					e.Name &&
						((a =
							"<td>" +
							e.Name +
							"</td><td><span > " +
							e.Value +
							"</span></td>"),
							(i =
								i +
								'<td  name="' +
								e.Name +
								'">' +
								e.Name +
								"</td>")),



						jQuery("#modal-container_ebay #table-attributes tbody").append(
							jQuery(
								"<tr>" +
								a +
								'<td><button id="removeAttribute" class="btn btn-danger">X</btton><td></tr>'
							)
						);
				});
			jQuery("#table-variations thead").append(jQuery("<tr><td>Image</td>" + i + "<td style='font-weight: 800'>quantity</td><td style='font-weight: 800'>Price</td><td style='font-weight: 800'>Sale price</td><td style='font-weight: 800'>ASIN</td><td>Title</td><td style='font-weight: 800'>Remove</td><td><button disabled id='' class='button-5' style='width:220px'>Import As dropshipping  <span class='newLoaderAllSimple'></span></button></td><td><button disabled id='' class='button-6' style='width:220px;'>Import as affiliate <span class='newLoaderAllAffiliate'></span></button></td></tr>"))

		}
	}
	function getTitle(e) {
		jQuery("#modal-container_ebay #customProductTitle").val(e);
	}

	function fillTags(tagList) {

		var tags = tagList.split(' ');

		// Iterate through the tags and add them individually
		tags.forEach(function (tag) {
			// Trim any extra whitespace from the tag
			var trimmedTag = tag.trim();
			if (tag.length > 3) {
				tagsProduct.push(tag);
				// Add the trimmed tag as a new div with a remove button
				jQuery("#tagInputDisplayed").append(
					jQuery(
						'<div class="singleTag" style="width: fit-content;padding: 10px;background-color: #212148;border-radius: 10px;margin: 10px;">' +
						trimmedTag +
						'<button class="btn btn-danger removeTag" >X</button></div>'
					)
				);
			}

		});

	}

	function getTags() {
		var tagsArray = [];

		// Select the HTML elements that contain the tags (adjust the selector as needed)
		var tagElements = jQuery('.singleTag'); // Replace '.tag' with the appropriate CSS selector

		// Loop through the selected elements and extract the tag text
		tagElements.each(function (index, tagElement) {
			var tagText = tagElement.textContent.trim(); // Get the text content and trim any leading/trailing spaces
			tagsArray.push(tagText); // Add the tag text to the array
		});
		console.log('tags', tagsArray);
		return tagsArray;
	}


	function fillTheFormEbay(e, t, a) {
		jQuery(".loader").remove();
		jQuery("head")
			.find("title")
			.text();
		let i = jQuery("#textToBeReplaced").val(),
			r = jQuery("#textToReplace").val();
		var o = new RegExp(i, "g");
		globalTitle = e.title;
		let n = e.title,
			l = e.description;
		i && r && ((n = e.title.replace(o, r)), (l = e.description.replace(o, r))),
			jQuery("#modal-container_ebay #simpleQuantity").val(e.quantity),
			getTitle(n),
			getHtmlDescription_ebay(l),
			getImages_ebay(e.images),
			getItemSpecific_ebay(e.specifications),
			fillTags(e.title),
			loadCategories([e.PrimaryCategoryName].concat(getTags()));
		(currentProductId = t),
			jQuery("#modal-container_ebay #customProductCategory").empty(),
			savedCategories &&
			savedCategories.length &&
			savedCategories.forEach(function (e, t) {
				(items =
					'<div class="checkbox"><label><input type="checkbox" value="' +
					e.term_id +
					'"/>' +
					e.name +
					"</label>"),
					jQuery("#modal-container_ebay #customProductCategory").append(
						jQuery(items)
					);
			}),
			"simple" == e.type
				? (getSalePrice(calculateAppliedPrice(e.currentPrice)),
					getRegularPrice(calculateAppliedPrice(e.currentPrice)),
					getQuantity(e.quantity),
					getSku(e.productId),
					jQuery("#modal-container_ebay #simpleSku").val(t),
					// jQuery('#modal-container_ebay [href="#menu5"]')
					//   .closest("li")
					//   .hide(),
					jQuery("#modal-container_ebay #no-variations").show(),
					jQuery("#modal-container_ebay #applyPriceFormula").hide(),
					jQuery("#modal-container_ebay #applyPriceFormulaRegularPrice").hide(),
					jQuery("#modal-container_ebay #importSalePricecheckbox").hide(),
					jQuery("#modal-container_ebay #applyCharmPricingConainer_ebay").hide(),
					jQuery("#modal-container_ebay #priceContainer").show(),
					jQuery("#modal-container_ebay #skuContainer").show(),
					jQuery("#modal-container_ebay #productType").text("Simple Product"))
				: e &&
				e.variations &&
				e.variations.Variation &&
				(getAttributes_ebay(e.variations.VariationSpecificsSet),
					getVariations(e.variations.Variation, e.variations.Pictures, a),
					jQuery("#modal-container_ebay #applyPriceFormula").show(),
					jQuery("#modal-container_ebay #applyPriceFormulaRegularPrice").show(),
					jQuery("#modal-container_ebay #importSalePricecheckbox").show(),
					jQuery("#modal-container_ebay #applyCharmPricingConainer_ebay").show(),
					jQuery("#modal-container_ebay #priceContainer").hide(),
					jQuery("#modal-container_ebay #skuContainer").hide(),
					jQuery("#modal-container_ebay #productWeightContainer").hide(),
					jQuery("#modal-container_ebay #productType").text("Variable Product"),
					jQuery("#modal-container_ebay #no-variations").hide(),
					e &&
					e.variations &&
					e.variations.length > 100
					);
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


	function insertVariationsInSets_ebay(variations, postId, currentIndex) {
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
						displayToastWithColor('Variations at index ' + currentIndex + ' imported successfully', "green");
					}
					if (variations && variations.length) {
						insertVariationsInSets_ebay(variations, postId, currentIndex + 10);
					}
				},
				error: function (error) {

					displayToast(error, "red");
					if (variations && variations.length) {
						insertVariationsInSets_ebay(variations, postId, currentIndex + 10);
					}

				},
			});
		} else {
			stopLoading();
			jQuery(".lds-ring").remove();
		}
	}

	jQuery(document).on("click", ".close-modal", function (e) {
		jQuery('#myModaleBay').remove();
	});

	// function toggleCampaignIdInput() {
	// 	var isAffiliateProductSelected = jQuery('#affiliateProduct').prop('checked');
	// 	jQuery('#campaignIdContainer').css({ 'display': isAffiliateProductSelected ? 'block' : 'none' });
	// }


	// jQuery(document).on("click", "#affiliateProduct", toggleCampaignIdInput)
	// jQuery(document).on("click", "#dropshippingProduct", toggleCampaignIdInput)

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



	function prepareModal() {
		jQuery("#myModaleBay").remove(),
			jQuery("#importModaleBay").remove(),
			(tagsProduct = []),
			jQuery(
				`
		  <button type="button" id="importModaleBay" style="display: none; position:relative" class="btn btn-primary btn-lg"
		  data-bs-toggle="modal" data-bs-target="#myModaleBay">Import To Shop</button>
	  <div class="modal" tabindex="-1" id="myModaleBay" role="dialog" data-backdrop="false">
		  <div class="modal-dialog" style="max-width:70vw; width:70vw">
			  <div class="modal-content"
				  style="border-radius: 20px;box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;height:92vh">
				  <div class="
			  modal-header">
					  <h4 class="modal-title">Product customization <span style="color:red" id="productType"></span> <span
							  style="color:red" id="currencyReturned"> <span></h4>
	  
					  <button class="btn btn-danger close-modal" data-bs-dismiss="modal">&times;</button>
				  </div>
				  <div class="modal-body" style="overflow: scroll;">
					  <ul class="nav">
      
      
                          <li class="nav-item active"><a role="presentation" data-bs-target="#home" class="nav-link "
                                  role="tab" data-bs-toggle="tab" href="#home">General</a></li>
                          <li class="nav-item"><a data-bs-toggle="tab" role="presentation" data-bs-target="#menu1"
                                  class="nav-link" role="tab" data-bs-toggle="tab" href="#menu1">Description</a></li>
                          <li class="nav-item"><a data-bs-toggle="tab" role="presentation" data-bs-target="#menu3"
                                  class="nav-link" role="tab" data-bs-toggle="tab" href="#menu3">Gallery</a></li>
                          <li class="nav-item"><a data-bs-toggle="tab" role="presentation" data-bs-target="#menu5"
                                  class="nav-link" role="tab" data-bs-toggle="tab" href="#menu5">Variations</a></li>
                          <li class="nav-item"><a data-bs-toggle="tab" role="presentation" data-bs-target="#menu6"
                                  class="nav-link" role="tab" data-bs-toggle="tab" href="#menu6">Specific attributes</a>
                          </li>
                          <li class="nav-item"><a data-bs-toggle="tab" role="presentation" data-bs-target="#menu7"
                                  class="nav-link" role="tab" data-bs-toggle="tab" href="#menu7">Tags</a></li>
                          <li class="nav-item"><a data-bs-toggle="tab" role="presentation" data-bs-target="#menu4"
                                  class="nav-link" role="tab" data-bs-toggle="tab" href="#menu4">Reviews</a></li>
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
							<label for="title">Product URL:</label> <input id="productCustomUrl" type="text" class="form-control" placeholder="Product URL">
						</div>

                              <div class="form-group" id="priceContainer" style="display:none">
							  
                                  <div class="form-group" style="margin-top:20px">
                                      <h5 style="color:#af9e9e" for="price">Regular Price: <span style="color:red"
                                              id="formulaContent"><span></h5>
                                  </div><input style="display:none" id="simpleQuantity" /> <input style="width:97%"
                                      id="customPrice" type="number" class="form-control" id="price">
      
      
                                  <div class="form-group" style="margin-top:20px">
                                      <h5 style="color:#af9e9e" for="price">Sale Price: <span style="color:red"
                                              id="formulaContent"><span></h5>
                                  </div>
                                  <input style="width:97%; height:50px" id="customSalePrice" type="number"
                                      class="form-control" id="price">
      
                              </div>
                              <div class="form-group" style="margin-top:10px">
                                  <h5 style="color:#af9e9e" for="title">Custom Title:</h5> <input
                                      style="margin-top:10px; height:60px" id="customProductTitle" type="text"
                                      placeholder="custom title, if empty original title will be displayed"
                                      class="form-control" id="title">
                              </div>
                              <div class="form-group" id="skuContainer" style="display:none; margin-top:20px">
                                  <h5 style="color:#af9e9e" for="title">Sku <small> (Optional) </small> </h5> <input
                                      style="width: 100%;padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-top: 6px; margin-bottom: 16px; resize: vertical;"
                                      type="text" placeholder="Sku attribute (optional)" class="form-control" id="simpleSku">
                              </div>
      
                              <div class="form-group" style="margin-top:10px">
                                  <h5 style="color:#af9e9e" for="title"> Short Description <small> (Optional) </small> </h5>
                                  <textarea id="shortDescription" class="form-control" rows="2" id="comment"
                                      placeholder="Short description"></textarea>
                              </div>
                              <div style="margin-top:20px" class="checkbox"><label><input id="isPublish" type="checkbox" name="remember"> Publish
                                      (checked =
                                      publish | unchecked = draft)</label> </div>
                              <div class="checkbox"><label><input id="isFeatured" type="checkbox" name="remember"> Featured
                                      product
                                      <small>Featuring products on your website is a great way to show your best selling or
                                          popular
                                          products from your store</small></label> </div>

										  <h3 style="margin-top:10px; color: #c4b9b9"> Select and add categories from AliExpress </h3>
										  <div id="shopCategories" style="box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;height: 200px; overflow-y: scroll;"></div>

										  
                              <div class="form-group" id="categoriesContainer" style="box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;margin-top: 38px;">
                                  <div class="panel panel-default">
                                      <div class="panel-heading">Select Categories</div>
                                      <div id="customProductCategory" style="height:150px; overflow-y:scroll"
                                          class="panel-body">
                                      </div>
                                  </div>
                              </div>
                          </div>
						  <div id="menu1" class="tab-pane fade in">
                          <div class="form-group">
                              <div class="checkbox">

							  <div class="checkbox"><label><input id="addAttributeToDescription" type="checkbox"
							  name="remember"> &nbsp;Include Specification to description </label> </div>


                                  <div class="checkbox"><label><input id="ImportImagesFromGallery" type="checkbox"
                                              name="remember"> &nbsp;Add Include images to the description </label> </div>
                                  <div class="checkbox">
                                  
                                              </div>
                                  <label><input id="removePicture" type="checkbox" name="remember"> &nbsp; Remove Pictures
                                  </label>
                              </div>
                              <div class="checkbox"><label><input id="removeDescription" type="checkbox" name="remember">
                                      &nbsp; Remove description </label> </div>
                              <div id="editor">
                                  <div id="descriptionContent"> </div>
                              </div>
                          </div>
                      </div>
					  <div id="menu3" class="tab-pane fade in">
							<div class="checkbox"><label><input id="includeImageFromDescription" type="checkbox"
										name="remember"> &nbsp; Include Pictures from description </label> </div>
							<div id="galleryPicture" style="overflow-y:scroll;height:500px"> </div>
						</div>
                          <div id="menu4" class="tab-pane fade" role="tabpanel">
                              <div id="customReviews" style="overflow-y:scroll;height:500px"><button class="btn btn-primary"
                                      id="addReview" style="width:100%;margin-top:10px"> Add Review</button>
                                  <table id="table-reviews" class="table table-striped">
                                      <thead>
                                          <tr>
                                              <th>Review</th>
                                              <th>Username</th>
                                              <th>Date creation</th>
                                              <th>Rating</th>
                                              <th>Remove</th>
                                          </tr>
                                      </thead>
                                      <tbody></tbody>
                                  </table>
                              </div>
                          </div>
      
      
      
      
                          <div id="menu5" class="tab-pane fade show">
                              <div id="no-variations"
                                  style="text-align:center; display:none; padding:20px; margin:30px; background-color:beige">
                                  <span style=" text-align:center">This is a simple product, no variations can be
                                      defined</span></div>
                              <h3 class="formulatexcontainer" for="price"
                                  style="background-color:beige; padding:15px; margin:20px;  text-align:center"> <span
                                      class="formulaContent">No formula defined yet<span></h3>
									  <button class="button-5"
                                  style="margin-left: 35%; width:350px" id="openAdvancedSettingsEbay">Open Advanced
                                  settings</button>
                              <div id="advancedVariationsCapa" style="display:none">
                                  <div style="flex: 1 1 50%;">
                                      <div style="flex: 1 1 50%; display:flex; justify-content: center;">
                                          <labe style="justify-content: center; font-weight: 800; margin-y: 20px" l>Advanced
                                              Setting</label>
                                      </div>
                                      <div class="checkbox" id="applyCharmPricingConainer_ebay" style="display:none">
                                          <div class="checkbox"><label><input id="applyCharmPricing_ebay" type="checkbox"
                                                      name="remember"> &nbsp;Apply charm pricing 00 <small>( Example 2.34 ==>
                                                      3.00) </small></label> </div>
                                          <div class="checkbox"><label><input id="applyCharmPricing99_ebay" type="checkbox"
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
      
      
      
      
      
                          <div id="menu6" class="tab-pane fade" role="tabpanel"><button class="btn btn-primary"
                                  id="addSpecific" style="width:100%"> Add specification</button>
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
                          <div id="menu7" class="tab-pane fade" role="tabpanel"><label> Add Tag to product</label><input
                                  id="tagInput" type="text" class="form-control" /><button class="btn btn-primary"
                                  id="addTagToProduct_ebay" style="width:100%">
                                  Add tags</button>
                              <div id="tagInputDisplayed" style="color:white"></div>
                          </div>
                          <div id="advanced" class="tab-pane fade" role="tabpanel">
                              <div class="form-group" style="margin-top:5px">
                                  <h5 style="color:#af9e9e" for="title"> Tags <small> (Optional) </small> </h5> <textarea
                                      id="tags" class="form-control" rows="2" id="comment"
                                      placeholder="Place tags separated by commas"></textarea>
                              </div>
                              <div style="margin-top:5px">
                                  <h5 style="color:#af9e9e" for="title"> Sale price (Optional) </small> </h5> <input
                                      style="width:97%" id="salePrice" type="number" class="form-control" id="price">
                              </div>
                              <div style="margin-top:5px">
                                  <h5 style="color:#af9e9e" for="title"> Sale start date </small> </h5> <input
                                      id="saleStartDate" type="date" class="form-control" id="price">
                              </div>
                              <div style="margin-top:5px">
                                  <h5 style="color:#af9e9e" for="title"> Sale end date </small> </h5> <input id="saleEndDate"
                                      type="date" class="form-control" id="price">
                              </div>
                          </div>
						  <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal"
						  data-bs-dismiss="modal">Close</button> <button type="button" id="totoButton"
						  class="button-5 ">Import <small style="color:grey" id="asVariableAliex"> ( as
							   Dropshipping product) </small><span id="loaderImporttoShop" style="display:none"></span></button>
				  </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
          `

			).appendTo(jQuery("#modal-container_ebay"));

		jQuery(".nav-link").click(function (e) {
			e.preventDefault(); // Prevent the default tab navigation behavior
			// You can add your own tab switching logic here if needed
		});

	}
	jQuery(document).on("click", ".ebay-page-item", function (e) {
		var t = 1;
		try {
			t = parseInt(jQuery(this)[0].innerText);
		} catch (e) {
			(t = 1),
				displayToast(
					"error while index selection, please contact wooshark, wooebayimporter@gmail.com",
					"red"
				);
		}

		searcheBayProducts(t);
	});
	let currentEbayProductUrl = "";
	function getREviewsFRomEbayOfficialApi(e) {
		var t = new XMLHttpRequest();
		(t.onreadystatechange = function () {
			if (4 == t.readyState && 200 === t.status) {
				var e = JSON.parse(t.response);
				console.log("------ddd---", e);
			}
		}),
			t.open("POST", baseUrl + ":8002/getREviewsFRomEbayOfficialApi", !0),
			t.setRequestHeader("Content-Type", "application/json"),
			t.send(JSON.stringify({ productId: e }));
	}
	function getVariationsFomEbAyLocalServer(e) {
		jQuery.ajax({
			url: wooshark_params.ajaxurl,
			type: "POST",
			dataType: "JSON",
			data: { 
				                nonce: wooshark_params.nonce,

				action: "alibay-get_variations_from_ebay", productUrl: e },
			success: function (e) {
				console.log("----response", e);
			},
			error: function (e) {
				console.log("****err", e);
			},
			complete: function () { }
		});
	}
	function buildEbayProduct(e) {
		// alreadyImportedProduct < 1e4
		(jQuery(".loader2").css({
			display: "block",
			position: "fixed",
			"z-index": 9999,
			top: "50px",
			right: "50px",
			"border-radius": "35px",
			"background-color": "black"
		}),
			getEbayProductDetailsByApi(e, function (t) {
				globalUrlProduct = t.productUrl;
				jQuery('#productCustomUrl').val(t.productUrl);
				
				t && t.title
					? (jQuery("#importModaleBay").click(),
						stopLoading(),
						fillTheFormEbay(t, e),
						(currentEbayProductUrl = t.productUrl))
					: t && t.data && t.data[0] && t.data[0].ErrorCode
						? (displayToast(
							"Product id not valid, please make sure you introduce a valid product id",
							"red"
						),
							jQuery(".loader2").css({ display: "none" }))
						: (jQuery(".importToS").each(function (e, t) {
							console.log("----- un - disabling"),
								jQuery(t).attr("disabled", !1);
						}),
							jQuery(".loader2").css({ display: "none" }),
							displayToast("cannot get product deails", "red"));
			}))
		// : displayToast("please activate your account", "red");
	}

	function getImages_ebay(e) {
		e &&
			e.length &&
			((images = e),
				jQuery("#modal-container_ebay #galleryPicture").empty(),
				images.forEach(function (e) {
					jQuery(
						'<div><button type="button" class="btn btn-primary" id="removeImage_ebay" ><i style="font-size:15px ; margin:5px">Remove Image</i></button><img  style="100%" src=' +
						e +
						" /><div>"
					).appendTo(jQuery("#modal-container_ebay #galleryPicture"));
				}));
	}
	function getCurrentTotalImportItemsValues() {
		var e = localStorage.getItem("totalImportItems");
		return e ? parseInt(e) : 1;
	}
	jQuery(document).on("click", "#goToExtension", function (e) {
		window.open("https://sharkdropship.com/eBay");
	}),
		jQuery(document).on("click", "#close-1", function (e) {
			jQuery("#section-1").hide();
		}),
		jQuery(document).on("click", "#close-2", function (e) {
			jQuery("#section-2").hide();
		}),
		// jQuery(document).on("click", "#importToShopBulk_ebay", function (e) {
		// 	// "false" != localStorage.getItem("_isAuthorized_alibay") &&
		// 	// null != localStorage.getItem("_isAuthorized_alibay")
		// 	((productId = jQuery(this)
		// 		.parents(".card")
		// 		.find("#sku")[0].innerText),
		// 		productId
		// 			? importProductGlobally(productId)
		// 			: displayToast("Cannot get product sku", "red"))
		// 	// : displayToast("please activate your account", "red");
		// }),
		jQuery(document).on("click", ".product-page-item", function (e) {
			jQuery("#products-wooshark").empty(),
				jQuery("#products-wooshark").show(),
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
				let e = jQuery(this)[0];
				e && (t = parseInt(e.innerText)),
					getAllProducts(t),
					jQuery(".product-page-item").empty(),
					displayPaginationSection(totalproductsCounts, t);
			} catch (e) {
				(t = 1),
					displayToast(
						"error while index selection, please contact wooshark, wooebayimporter@gmail.com",
						"red"
					),
					jQuery(".loader2").css({ display: "none" });
			}
		})


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


	jQuery(document).on("click", ".ebay-page-item", function (e) {
		var t = 1;
		try {
			t = parseInt(jQuery(this)[0].innerText);
		} catch (e) {
			(t = 1),
				displayToast(
					"error while index selection, please contact wooshark, wooebayimporter@gmail.com",
					"red"
				);
		}
		searcheBayProducts(t);
	});
	var index = 0;
	jQuery(document).on("click", "#importProductToShopByUrl_ebay", function (e) {
		var t = jQuery("#productUrl_ebay").val();
		globalUrlProduct = t;
		if (t) {
			prepareModal();
			var a = getProductIdFRomUrl_ebay(t);
			getEpidFromUrl(t),
				a ? buildEbayProduct(a) : displayToast("Cannot get product sku", "red");
		} else displayToast("please make sure you have introduce a valid url, if issue persist, please contact wooshark", "red");
	}),
		jQuery(document).on("click", "#importProductToShopBySky_ebay", function (e) {
			prepareModal(), (waitingListProducts = []);
			var t = jQuery("#productSku_ebay").val();
			t ? buildEbayProduct(t) : displayToast("Cannot get product sku", "red");
		});
	let ebAppId = "";
	function getAccountDetails(e, t) {
		var a = new XMLHttpRequest();
		(a.onreadystatechange = function () {
			if (4 == a.readyState && 200 === a.status) {
				let e = JSON.parse(a.response).data;
				ebAppId = e;
			}
		}),
			a.open("POST", baseUrl + ":8002/getAccountDetails", !0),
			a.setRequestHeader("Content-Type", "application/json"),
			a.send(JSON.stringify({ licenseValue: e, alreadyImportedProduct: t }));
	}
	function displayToast(e, t) {
		jQuery.toast({
			text: e,
			bgColor: "grey",
			textColor: t,
			hideAfter: 5e3,
			stack: 5,
			textAlign: "left",
			position: "bottom-right"
		});
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
	jQuery(document).ready(function () {
		jQuery("#searchKeyword_ebay").val("shoes");
		searcheBayProducts(1);
		restoreConfiguration();

	});
	let alreadyImportedProduct = 0;
	jQuery(document).on("click", "#insert-product-reviews", function (e) {
		currentProductId = jQuery(this).parents("tr")[0].cells[2].innerText;
	}),
		jQuery(document).on("click", "#removeVariation_ebay", function (e) {
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
		jQuery(document).on("click", "#removeImage_ebay", function (e) {
			var t = jQuery(this).parent()[0].children[2].currentSrc,
				a = images.indexOf(t);
			a > -1 && images.splice(a, 1),
				jQuery("#galleryPicture").empty(),
				images.forEach(function (e) {
					jQuery(
						'<div><button type="button" class="btn btn-primary" id="removeImage_ebay" ><i style="font-size:15px ; margin:5px">Remove Image</i></button><button type="button" class="btn btn-info" id="editImageAliexpress" ><i style="font-size:15px ; margin:5px; margin:5px" disabled>Edit image (new feature | testing phase) </i></button><img  style="100%" src=' +
						e +
						" /><div>"
					).appendTo(jQuery("#galleryPicture"));
				});
		}),
		jQuery(document).on("click", "#removeText", function (e) {
			jQuery("#removeText")[0].checked && jQuery("#descriptionContent").html("");
		}),

		jQuery(document).on("click", "#removePicture", function (e) {
			if (jQuery("#removePicture")[0].checked) {
				htmlEditor = quill.root.innerHTML;
				var t = htmlEditor.replace(/<img[^>]*>/g, "");
				(t = t.replace(/<a[^>]*>/g, "")),

				 	quill.setContents([]);
                    var delta =  quill.clipboard.convert(t);
                    quill.setContents(delta);


					// quill.setContents([]),
					// quill.clipboard.dangerouslyPasteHTML(0, t);
			} else{
					quill.setContents([]);
                    var delta =  quill.clipboard.convert(htmlEditor);
                    quill.setContents(delta);
				}
			
			//  quill.setContents([]), quill.clipboard.dangerouslyPasteHTML(0, htmlEditor);
		}),

		jQuery(document).on("click", "#removeDescription", function (e) {
			jQuery("#removeDescription")[0].checked
				? ((htmlEditor = quill.root.innerHTML), quill.setContents([]))
				: (
					quill.setContents([]),
                    quill.setContents(quill.clipboard.convert(htmlEditor))
					);
		}),


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
	jQuery(document).on("click", "#applyCharmPricing99_ebay", function (e) {
		var t = jQuery("#applyCharmPricing99_ebay")[0].checked,
			a = jQuery("#table-variations tbody tr");
		copiedObject || (copiedObject = a.clone());
		var i = jQuery("#table-variations thead tr")[0].cells.length - 8;
		t
			? (a.each(function (e, t) {
				t.cells[i + 1].textContent =
					Math.ceil(t.cells[i + 1].textContent).toFixed(2) - 0.01;
			}),
				a.each(function (e, t) {
					t.cells[i + 2].textContent =
						Math.ceil(t.cells[i + 2].textContent).toFixed(2) - 0.01;
				}))
			: (a.each(function (e, t) {
				t.cells[i + 1].textContent = copiedObject[e].cells[i + 1].textContent;
			}),
				a.each(function (e, t) {
					t.cells[i + 2].textContent = copiedObject[e].cells[i + 2].textContent;
				}));
	});
	copiedObject = "";
	function setFormulaAliexpress_ebay(e) {
		var t = e.skuVal.skuMultiCurrencyCalPrice,
			a = e.skuVal.actSkuMultiCurrencyCalPrice;
		if (
			((availQuantitySingleProduct = e.skuVal.availQuantity),
				generalPreferences.setMaximimProductStock &&
				0 != generalPreferences.setMaximimProductStock &&
				(availQuantitySingleProduct = generalPreferences.setMaximimProductStock),
				formsToSave && formsToSave.length)
		) {
			var i = {};
			formsToSave.forEach(function (e) {
				if (e.min < parseFloat(t) && e.max > parseFloat(t)) {
					var r = (i = e).multiply || 1,
						o = math.eval(r),
						n = i.addition || 0,
						l = math.eval(n);
					jQuery(".formulaContent").text(
						"Applied Formula = original price [*] (" + r + ") [+]" + n
					),
						jQuery(".formulatexcontainer").show(),
						(t = parseFloat(t) * parseFloat(o) + parseFloat(l));
				}
				if (e.min < parseFloat(a) && e.max > parseFloat(a)) {
					(r = (i = e).multiply || 1),
						(o = math.eval(r)),
						(n = i.addition || 0),
						(l = math.eval(n));
					a = parseFloat(a) * parseFloat(o) + parseFloat(l);
				}
			});
		}
		t &&
			(generalPreferences.importShippingCost && (t = parseFloat(t)),
				(t = Number(t).toFixed(2)),
				jQuery("#customPrice").val(parseFloat(t))),
			a &&
			(generalPreferences.importShippingCost && (a = parseFloat(a)),
				(a = Number(a).toFixed(2)),
				jQuery("#customSalePrice").val(parseFloat(a)));
	}
	function buildVariations_ebay() {
		var e = { variations: [], NameValueList: [] };

		// Initialize an empty array to store variations
		var variations = [];
		jQuery("#table-attributes tbody tr").each(function (t, a) {


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
		// jQuery("#table-attributes tr").each(function(t, a) {
		//   t &&
		//     e.NameValueList.push({
		//       name: a.cells[0].textContent
		//         .toLowerCase()
		//         .replace(/ /g, "-")
		//         .replace("'", "-"),
		//       values: a.cells[1].textContent.split(","),
		//       variation: !0,
		//       visible: !0
		//     });
		// });
		// var t = e.NameValueList.length;
		// return (
		//   jQuery("#table-variations tr").each(function(a, i) {
		//     // if (a && a < 100) {
		//       var r = [];
		//       e.NameValueList.forEach(function(e, t) {
		//         let a = jQuery(
		//           "#table-variations tr td:contains(" + e.name.trim() + ")"
		//         ).index();
		//         a < 0 && (a = t + 1)
		//         if(i.cells[0] &&
		//           i.cells[0].children &&
		//           i.cells[0].children[0] &&
		//           i.cells[0].children[0].currentSrc){
		//           r.push({
		//             name: e.name
		//               .toLowerCase()
		//               .replace(/ /g, "-")
		//               .replace("'", "-"),
		//             value: i.cells[a].textContent,
		//             image:
		//               i.cells[0] &&
		//               i.cells[0].children &&
		//               i.cells[0].children[0] &&
		//               i.cells[0].children[0].currentSrc
		//                 ? i.cells[0].children[0].currentSrc
		//                 : ""
		//           });
		//         }else{
		//           r.push({
		//             name: e.name
		//               .toLowerCase()
		//               .replace(/ /g, "-")
		//               .replace("'", "-"),
		//             value: i.cells[a].textContent
		//           });

		//         }

		//       }),
		//         i.cells[t + 1].textContent &&
		//           e.variations.push({
		//             SKU: i.cells[t + 4] ? i.cells[t + 4].textContent : '',
		//             availQuantity: i.cells[t + 1] ? i.cells[t + 1].textContent : 1,
		//             salePrice: i.cells[t + 3] ? i.cells[t + 3].textContent : '',
		//             regularPrice: i.cells[t + 2] ? i.cells[t + 2].textContent : '',
		//             attributesVariations: r,
		//             weight:
		//             i.cells[t + 6] ?  i.cells[t + 6].textContent : jQuery("#productWeight").val()
		//           });

		//     // }
		//   }),
		//   e
		// );
	}
	jQuery(document).on("click", "#applyCharmPricing_ebay", function (e) {
		var t = jQuery("#applyCharmPricing_ebay")[0].checked,
			a = jQuery("#table-variations tbody tr");
		copiedObject || (copiedObject = a.clone());
		var i = jQuery("#table-variations thead tr")[0].cells.length - 8;
		t
			? (a.each(function (e, t) {
				t.cells[i + 1].textContent = Math.ceil(
					t.cells[i + 1].textContent
				).toFixed(2);
			}),
				a.each(function (e, t) {
					t.cells[i + 2].textContent = Math.ceil(
						t.cells[i + 2].textContent
					).toFixed(2);
				}))
			: (a.each(function (e, t) {
				t.cells[i + 1].textContent = copiedObject[e].cells[i + 1].textContent;
			}),
				a.each(function (e, t) {
					t.cells[i + 2].textContent = copiedObject[e].cells[i + 2].textContent;
				}));
	}),
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
					t.cells[a + 1].textContent = jQuery(
						"#globalRegularPriceValue"
					).val();
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
		}),
		jQuery(document).on("click", "#totoButton", function (e) {
			jQuery("#loaderImporttoShop").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'));
			jQuery("#loaderImporttoShop").show();
			handleFreeVersion();
			// displayToast('Please note that the free version does not include the import of all product details', "orange");

			startLoading();
			var t = [];
			let a = "";
			var i = buildVariations_ebay(),
				r =
					jQuery("#customProductTitle").val() ||
					jQuery("head")
						.find("title")
						.text(),
				o = jQuery("#shortDescription").val() || "",
				n = jQuery("#customPrice").val() || "",
				l = jQuery("#customSalePrice").val() || "";
			jQuery("#simpleSku").val();
			let s = [];
			jQuery("#customProductCategory input:checked").each(function () {
				s.push(jQuery(this).attr("value"));
			});
			let variations = i.variations;
			var c = jQuery("#isFeatured")[0].checked,
				d = (i = getItemSpecificfromTableAliexpress_ebay(i)).NameValueList;
			let u = jQuery('#productCustomUrl').val();

			jQuery('input[name="categoryChoice"]:checked')[0] &&
				jQuery('input[name="categoryChoice"]:checked')[0].value;
			let p = !1;

			t = getReviews_ebay(),
				a = quill.root.innerHTML
			// i = getItemSpecificfromTableModalEbay(i);
			var y = (p = jQuery("#isPublish")[0].checked)
				? "publish"
				: "draft";
			let m = [];


			if(jQuery('#affiliateProduct').prop('checked')){

				tagsProduct && tagsProduct.length && (m = tagsProduct),
				jQuery.ajax({
					url: wooshark_params.ajaxurl,
					type: "POST",
					dataType: "JSON",
					data: {
						
						                nonce: wooshark_params.nonce,

						action: "theShark_alibay_insertProductInWoocommerceAffiliate",
						sku: i.variations && i.variations.length ? getProductIdFRomUrl_ebay(globalUrlProduct) : jQuery("#simpleSku").val(),
						title: r,
						description: quill.root.innerHTML || "",
						productType:
							 "external",
						images:
							images,
						tags: getTags(),
						categories: s,
						regularPrice: n.toString(),
						salePrice: l.toString(),
						quantity: jQuery("#simpleQuantity").val(),
						attributes: d,
						isFeatured: jQuery("#isFeatured")[0].checked ? true : false,
						postStatus: jQuery("#isPublish")[0].checked ? "publish" : "draft",
						shortDescription: jQuery("#shortDescription").val() || "",
						productUrl: u,
						//   $importVariationImages: jQuery("#importVariationImages_ebay").prop("checked"),
						reviews: t,
						remoteCategories: getSelectedCategories(),
						affiliateLink: u ? u : e.isAffiliate ? e.productUrl : "",


						// variations: i.variations && i.variations.length ? i.variations.splice(0, 1) : []
					},

					success: function (e) {
						if (e && e.error && e.error_msg) {
							displayToast(e.error_msg, "red");
							stopLoading();
							jQuery(".lds-ring").remove();
						} else if (e && !e.error && e.data) {
							displayToast(e.data, "green");

							if (variations.length) {
								displayToast("Start Loading variations by set of 10", "green");
								let postId = e.postId;
								insertVariationsInSets_ebay(variations, postId, 0);
							} else {
								stopLoading();
								jQuery(".lds-ring").remove();
							}

						}
						

						if (e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage")) {
							setTimeout(function () {
								window.open("https://sharkdropship.com/wooshark-dropshipping/", "_blank");
							}, 4000); // 4e3 is equivalent to 4000 milliseconds (4 seconds)
						}
					},


					error: function (e) {
						stopLoading();
						jQuery(".lds-ring").remove();
					}
				});

			}else{
				tagsProduct && tagsProduct.length && (m = tagsProduct),
				jQuery.ajax({
					url: wooshark_params.ajaxurl,
					type: "POST",
					dataType: "JSON",
					data: {
						
						                nonce: wooshark_params.nonce,

						action: "wooshark-insert-product-alibay",
						sku: i.variations && i.variations.length ? getProductIdFRomUrl_ebay(globalUrlProduct) : jQuery("#simpleSku").val(),
						title: r,
						description: quill.root.innerHTML || "",
						productType:
							i.variations && i.variations.length ? 'variable' : "simple",
						images:
							images,
						tags: getTags(),
						categories: s,
						regularPrice: n.toString(),
						salePrice: l.toString(),
						quantity: jQuery("#simpleQuantity").val(),
						attributes: d,
						isFeatured: jQuery("#isFeatured")[0].checked ? true : false,
						postStatus: jQuery("#isPublish")[0].checked ? "publish" : "draft",
						shortDescription: jQuery("#shortDescription").val() || "",
						productUrl: u,
						//   $importVariationImages: jQuery("#importVariationImages_ebay").prop("checked"),
						reviews: t,
						remoteCategories: getSelectedCategories()

						// variations: i.variations && i.variations.length ? i.variations.splice(0, 1) : []
					},

					success: function (e) {
						if (e && e.error && e.error_msg) {
							displayToast(e.error_msg, "red");
							stopLoading();
							jQuery(".lds-ring").remove();
						} else if (e && !e.error && e.data) {
							displayToast(e.data, "green");

							if (variations.length) {
								displayToast("Start Loading variations by set of 10", "green");
								let postId = e.postId;
								insertVariationsInSets_ebay(variations, postId, 0);
							} else {
								stopLoading();
								jQuery(".lds-ring").remove();
							}

						}
						// stopLoading();
						// jQuery(".lds-ring").remove();

						if (e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage")) {
							setTimeout(function () {
								window.open("https://sharkdropship.com/wooshark-dropshipping/", "_blank");
							}, 4000); // 4e3 is equivalent to 4000 milliseconds (4 seconds)
						}
					},


					// success: function(e) {
					//   e && e.error && e.error_msg && displayToast(e.error_msg, "red"),
					//     e && !e.error && e.data && displayToast(e.data, "green"),
					//     stopLoading();
					// },
					error: function (e) {
						// console.log("****err", e),
						stopLoading();
						jQuery(".lds-ring").remove();
						// e && e.responseText && displayToast(e.responseText, "red");
					}
				});
			}

			

			// }else{
			//   displayToast('reached weekly import limit, you can upgrade or wait for the next week')
			// }

		}),
		jQuery(document).on("click", "#resetFormula", function (e) {
			localStorage.setItem("formsToSave", "");
		});
	let _saveEbayConfiguration = {};
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
	],
		dates = [
			"2023-10-26",
			"2023-1-1",
			"2023-11-15",
			"2023-11-6",
			"2023-01-7",
			"2023-1-13",
			"2023-2-12",
			"2023-1-17",
			"2023-2-19",
			"2023-3-16",
			"2023-1-14",
			"2023-2-25",
			"2023-3-5",
			"2023-1-18",
			"2023-2-22",
			"2023-1-11",
			"2023-12-12",
			"2023-11-8",
			"2023-1-2",
			"2023-01-13",
			"2023-05-19",
			"2023-04-29",
			"2023-06-12",
			"2023-07-01",
			"2023-06-23",
			"2023-05-24",
			"2023-10-29",
			"2023-3-3",
			"2023-1-7",
			"2023-10-27",
			"2023-2-17",
			"2023-05-24",
			"2023-06-06",
			"2023-06-19",
			"2023-06-22",
			"2023-06-13",
			"2023-05-13",
			"2023-07-01",
			"2023-04-25",
			"2023-04-04",
			"2023-05-05",
			"2023-05-19",
			"2023-06-01",
			"2023-05-27",
			"2023-03-27",
			"2023-04-01",
			"2023-05-30",
			"2023-06-04"
		];
	function getItemSpecificfromTableAliexpress_ebay(e) {
		var t = jQuery("#table-specific tbody tr"),
			a = e.NameValueList.map(function (e) {
				return e.name;
			});
		return (
			t &&
			t.length &&
			t.each(function (t, i) {
				-1 == a.indexOf(i.cells[0].textContent) &&
					e.NameValueList.push({
						name: i.cells[0].textContent || "-",
						visible: !0,
						variation: !1,
						values: [i.cells[1].textContent]
					});
			}),
			e
		);
	}
	function stopAutomaticUpdate() {
		jQuery.ajax({
			url: wooshark_params.ajaxurl,
			type: "POST",
			dataType: "JSON",
			data: { 
				                nonce: wooshark_params.nonce,

				action: "alibay-stop_automatic_update_for_ebay" },
			success: function (e) {
				console.log("----stop automatic updates-------", e);
			},
			error: function (e) { },
			complete: function () { }
		});
	}
	function getSelectedLanguage_ebay() {
		return jQuery('input[name="ebayLanguage"]:checked')[0]
			? jQuery('input[name="ebayLanguage"]:checked')[0].value
			: "EBAY_US";
	}

	function getEbaySiteId(url) {
		// Define a mapping from domain to site ID
		const siteIdMap = {
			'ebay.com': 0,
			'ebay.ca': 2,
			'ebay.co.uk': 3,
			'ebay.com.au': 15,
			'ebay.at': 16,
			'ebay.be': 23, // Assuming .be is for eBay Belgium in French
			'ebay.fr': 71,
			'ebay.de': 77,
			'ebay.it': 101,
			'ebay.nl': 146,
			'ebay.es': 186,
			'ebay.ch': 193,
			'ebay.com.hk': 201,
			'ebay.ie': 205,
			'ebay.com.my': 207,
			'cafr.ebay.ca': 210, // Assuming cafr.ebay.ca is for eBay Canada in French
			'ebay.ph': 211,
			'ebay.pl': 212,
			'ebay.com.sg': 216,
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



	function getProductIdFRomUrl_ebay(ebayUrl) {
		// Use a regular expression to extract the product ID from the URL
		var productIdMatch = ebayUrl.match(/\/(\d+)(?:\?|\/|$)/);

		if (productIdMatch && productIdMatch[1]) {
			var productId = productIdMatch[1];
			return productId;
		} else {
			displayToast('Failed to get product id from url', 'red');
		}
	}



	// function getProductIdFRomUrl_ebay(ebayUrl) {
	// //   let t = "";
	// //   if (e && e.includes("?") && e.includes(".ebay.")) {
	// //     let a = e.indexOf("?");
	// //     if (a > -1) return (t = e.substring(a - 12, a)), console.log(t), t;
	// //   } else
	// //     e &&
	// //       !e.includes("?") &&
	// //       e.includes(".ebay.") &&
	// //       (t = e.substring(e.length - 12, e.length));
	// //   return "";


	// var productIdMatch = ebayUrl.match(/\/(\d+)\?/);

	// if (productIdMatch && productIdMatch[1]) {
	//   var productId = productIdMatch[1];
	//   return productId;
	// } else {
	//   return '';
	// }


	// }
	function getEpidFromUrl(e) {
		let t = "";
		if (e && e.includes("epid=")) {
			let a = e.indexOf("epid=");
			if (a > -1)
				return (t = e.substring(a + 4, a + 13)), console.log("ePid", t), t;
		}
		return "";
	}
	function getReviews_ebay() {
		var e = jQuery("#customReviews tbody tr"),
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
	function getReviews() {
		var e = jQuery("#customReviews tbody tr"),
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
	function insertReviewsIntoWordpress_ebay(e, t) {


		startLoading(),
			jQuery.ajax({
				url: wooshark_params.ajaxurl,
				type: "POST",
				dataType: "JSON",
				data: {
					
					                nonce: wooshark_params.nonce,

					action: "alibay-insert-reviews-to-productRM_for_ebay",
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

	jQuery(document).on("click", ".removeTag", function (e) {

		jQuery(this).parent().remove();
	});

	function startLoadingText() {
		jQuery(
			'<h5  id="loading-variation" style="color:green;">  Loading .... </h5>'
		).appendTo(".log-sync-product");
	}
	jQuery(document).on("click", "#addTagToProduct_ebay", function (e) {
		let t = jQuery("#tagInput").val();
		t &&
			(tagsProduct.push(t),
				jQuery("#tagInput").val(""),

				jQuery("#tagInputDisplayed").append(jQuery("<div class='singleTag' style='width: fit-content;padding: 10px;background-color: #212148;border-radius: 10px;margin: 10px;'>" + t +
					'<button class="btn btn-danger removeTag">X</button></div> ')));
	})
	let totalUpdatedProducts = 0;
	function stopLoadingText() {
		jQuery("#loading-variation").remove();
	}
	// function buildProduct_ebay(e) {
	// 	var t = e.title,
	// 		a = e.description,
	// 		i = [],
	// 		r =
	// 			(prepareSpecifications_ebay(
	// 				(i = e.variations
	// 					? prepareeBayVariations(e.variations)
	// 					: { variations: [], NameValueList: [] }),
	// 				e.specifications
	// 			),
	// 				e.currentPrice),
	// 		o = e.currentPrice,
	// 		n = e.productUrl,
	// 		l = e.quantity,
	// 		s = [];
	// 	e.images && e.images.length > 11 && (s = e.images.slice(0, 10));
	// 	var c = s && s.length ? s : e.images,
	// 		d = [];
	// 	jQuery(".categories input:checked").each(function () {
	// 		d.push(
	// 			jQuery(this)
	// 				.attr("value")
	// 				.trim()
	// 		);
	// 	});
	// 	var u = d,
	// 		p =
	// 			!(!jQuery("#isFeatured") || !jQuery("#isFeatured")[0]) &&
	// 			jQuery("#isFeatured")[0].checked;
	// 	addToWaitingList_ebay({
	// 		variations: i,
	// 		currentPrice: r,
	// 		originalPrice: o,
	// 		title: t,
	// 		description: a,
	// 		productUrl: n,
	// 		productId: e.productId,
	// 		productCategoies: u,
	// 		shortDescription: "",
	// 		importSalePrice: !0,
	// 		totalAvailQuantity: l || 1,
	// 		images: c,
	// 		simpleSku: e.productId,
	// 		featured: p
	// 	});
	// }
	jQuery(document).on("click", "#addToWaitingList_ebay", function (e) {
		displayToast("Premuim feature, you can upgrade to use this feature");
	})

	var globalWaitingList = [];
	function addToWaitingList_ebay(e) {
		globalWaitingList.push(e),
			jQuery("#importProductInWaitingListToShop_ebay").remove(),
			jQuery("#emptyWaitingListProduct_ebay").remove(),
			jQuery(
				'<button type="button" id="importProductInWaitingListToShop_ebay" style="position:fixed; border-raduis:0px; right: 1%; bottom: 60px; width:15%;z-index:9999" class="waitingListClass btn btn-primary btn-lg"><i class="fa fa-envelope fa-3px"> Import waiting List <span badge badge-primary>' +
				globalWaitingList.length +
				"</span></i></button>"
			).appendTo(jQuery("html")),
			jQuery(
				'<button type="button" id="emptyWaitingListProduct_ebay" style=" position:fixed; border-raduis:0px; bottom: 10px; right: 1%;  width:15%;z-index:9999" class="waitingListClass btn btn-danger btn-lg"><i class="fa fa-trash-o fa-3px">  Reset Waiting list </span></i></button>'
			).appendTo(jQuery("html"));
	}
	let globalTitiToto;

	let globalUrlProduct = '';

	function titiTotoActiv(e, t) {
		if (e) {
			var a = new XMLHttpRequest();
			(a.onreadystatechange = function () {
				if (4 == a.readyState)
					if (200 === a.status)
						try {
							let t = JSON.parse(a.response).data;
							(_isAuthorized_alibay = !0),
								localStorage.setItem(
									"_isAuthorized_alibay",
									_isAuthorized_alibay
								),
								displayToast(t, "green"),
								localStorage.setItem("licenseValue", e),
								jQuery("#licenseValue").val(e);
						} catch (e) {
							displayToast("Error parsing response json parse", "red");
						}
					else {
						(_isAuthorized_alibay = !1),
							localStorage.setItem("_isAuthorized_alibay", !1);
						try {
							let e = JSON.parse(a.response).data;
							console.log(e), displayToast(e, "red");
						} catch (e) {
							displayToast("cannot validate your account", "red");
						}
						localStorage.setItem("licenseValue", e),
							jQuery("#licenseValue").val(e);
					}
			}),
				a.open("POST", baseUrl + ":8002/getActiveHostAliexpressEbayAmazon", !0),
				a.setRequestHeader("Content-Type", "application/json"),
				a.send(
					JSON.stringify({
						clientWebsite: t,
						activationCode: e,
						clientKey: "clientKey",
						clientSecretKey: "clientSecretKey"
					})
				);
		} else displayToast("please introduce a license value");
	}
	function removeProductFromWP_ebay(e) {
		e &&
			(startLoading(),
				jQuery.ajax({
					url: wooshark_params.ajaxurl,
					type: "POST",
					dataType: "JSON",
					data: { 
						                nonce: wooshark_params.nonce,

						action: "alibay-remove-product-from-wp-for-ebay", post_id: e },
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
	jQuery(document).on("click", "#emptyWaitingListProduct_ebay", function (e) {
		jQuery("#emptyWaitingListProduct_ebay").remove(),
			jQuery("#importProductInWaitingListToShop_ebay").remove(),
			(globalWaitingList = []);
	}),
		(indexStopLoading = 0)







	function insertProductAsSingleEbay(e) {
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
		customVariations = getItemSpecificfromTableModalEbay(e.variations);
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

	function buildVariationsForSingleImportEbay(row) {
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
	// function buildVariationsForSingleImportEbay(row) {
	//   var e = {
	//     variations: [],
	//     NameValueList: [],
	//   };

	//   // Build variation from the provided row
	//   var variation = {
	//     SKU: jQuery(row).find('#singleAsin').text(),
	//     availQuantity: parseInt(jQuery(row).find('#singleQuantity').text()),
	//     regularPrice: parseFloat(jQuery(row).find('#singleRegularPrice').text()),
	//     salePrice: parseFloat(jQuery(row).find('#singleSalePrice').text()),
	//     attributesVariations: [],
	//   };

	//   // Iterate through the columns with 'name' attribute (starting from the fifth column)
	//   jQuery(row)
	//     .find('td[name]')
	//     .each(function () {
	//       var columnName = jQuery(this).attr('name');
	//       var columnValue = jQuery(this).text();
	//       var attribute = {
	//         name: columnName.toLowerCase().replace(/ /g, '-').replace("'", '-'),
	//         value: columnValue,
	//         image: jQuery(this).parent().find('td[imagePath]').attr('imagePath'), // Use parent() to find imagePath in the same row
	//       };
	//       variation.attributesVariations.push(attribute);

	//       // Build or update NameValueList based on the 'name' attribute
	//       var nameExists = false;
	//       for (var i = 0; i < e.NameValueList.length; i++) {
	//         if (e.NameValueList[i].name === attribute.name) {
	//           e.NameValueList[i].values.push(attribute.value);
	//           nameExists = true;
	//           break;
	//         }
	//       }
	//       if (!nameExists) {
	//         e.NameValueList.push({
	//           name: attribute.name,
	//           values: [attribute.value],
	//           variation: true,
	//           visible: true,
	//         });
	//       }
	//     });

	//   // Add the variation to the array
	//   e.variations.push(variation);

	//   // Now, 'e' contains the variations and NameValueList based on the provided row
	//   console.log(e);
	//   return e;
	// }




	jQuery(document).on("click", "#insertProductAsAffiliateEbay", function (e) {
		let t = jQuery("#affiliateLinkUrl").val();
		globalUrlProduct = t;
		if (t.includes("https")) {
			jQuery(this).parents("tr").find(".newLoaderAffiliate").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>')),
				insertProductAsAffiliateEbay({
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

	jQuery(document).on("click", "#openAdvancedSettingsEbay", function (e) {
		jQuery("#advancedVariationsCapa").toggle("slow"), jQuery("#advancedVariationsCapa").css({
			display: "flex"
		})
	})



	jQuery(document).on("click", "#insertProductAsSimpleEbay", function (e) {
		jQuery(this).parents("tr").find(".newLoaderSimple").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'));
		insertProductAsSingleEbay({
			images: jQuery(this).parents("tr").find("#singleImages").text() ? jQuery(this).parents("tr").find("#singleImages").text().split(",") : [],
			isAffiliate: !1,
			asin: jQuery(this).parents("tr").find("#singleAsin").text(),
			regularPice: jQuery(this).parents("tr").find("#singleRegularPrice").text(),
			quantity: jQuery(this).parents("tr").find("#singleQuantity").text(),
			salePrice: jQuery(this).parents("tr").find("#singleSalePrice").text(),
			productUrl: globalUrlProduct,
			title: jQuery(this).parents("tr").find("#singleTitle").text(),
			variations: buildVariationsForSingleImportEbay(jQuery(this).parents("tr"))
		})
	});

	function getItemSpecificfromTableModalEbay(e) {
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


	function insertProductAsAffiliateEbay(e) {

		if (e.asin == 'undefined') {
			// if(jQuery(row).find('#singleAsin').text() == 'undefined'){
			jQuery('.lds-ring').remove();
			displayToast('Cannot Insert the product because of missing / undefined sku reference, please fill the column sku', 'red');
			return;
			// }
		}
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
		customVariations = e.variations;
		customVariations = getItemSpecificfromTableModalEbay(customVariations);
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

	jQuery(document).on("click", "#importAllProductsToShop", function (e) {
		let productUrls = [];

		jQuery('#ebay-product-search-container .card').each(function () {
			let productUrl = jQuery(this).find('[data-product-url]').attr('data-product-url');
			productUrls.push(productUrl);
		});

		// Now productUrls array contains all the product URLs
		// Handle the array as needed (e.g., send to a server, process, etc.)
		let ids = [];
		productUrls.map(function (productUrl) {
			if (ids.length < 21) {
				ids.push(getProductIdFRomUrl_ebay(productUrl));
			}
		});
		let siteId = getEbaySiteId(productUrls[0]);


		getEbayDEtailsInBulk(ids, siteId);
		// jQuery.ajax({

	});


	function prepareSpecificationsBulkd(globalvariation, specifications) {
		if (specifications && specifications.NameValueList) {
			_.each(specifications.NameValueList, function (item) {
				globalvariation.NameValueList.push({
					name: item.Name,
					visible: true,
					variation: false,
					value: item.Value
				})
			})
		}
	}


	function prepareVariationsBulk(variations) {
		var globalvariation = {
			variations: [],
			NameValueList: []
		}
		_.each(variations.VariationSpecificsSet.NameValueList, function (item, index) {
			// if (index) {
			globalvariation.NameValueList.push({
				name: item.Name,
				value: item.Value,
				variation: true,
				visible: true
			})
			// }
		})

		_.each(variations.Variation, function (item, indexTrs) {
			if (indexTrs && indexTrs < 100) {
				var attributesVariations = [];
				_.each(item.VariationSpecifics.NameValueList, function (element, index) {
					// if(element.name.include('color') )
					attributesVariations.push({
						name: element.Name,
						value: element.Value[0],
						image: "",
					})
				});
				globalvariation.variations.push({
					identifier: item.SKU,
					SKU: item.SKU,
					availQuantity: item.Quantity,
					salePrice: calculateAppliedPrice(item.StartPrice.Value.toString()),
					regularPrice: calculateAppliedPrice(item.StartPrice.Value.toString()),
					attributesVariations: attributesVariations,
					weight: ''
				})
			}
		})
		return globalvariation;
	}


	function AddToWaitingListBulk(response) {
		var title = response.title;
		var description = response.description;
		var variations = [];
		if (response.variations) {
			variations = prepareVariationsBulk(response.variations);
		} else {
			variations = {
				variations: [],
				NameValueList: []
			}
		}
		var itemSpec = prepareSpecificationsBulkd(variations, response.specifications);

		var salePrice = response.currentPrice;
		var regularPrice = response.currentPrice;
		var productUrl = response.productUrl;
		var availQuantity = response.quantity;
		var images = response.images;
		var productId = response.productId;
		var weight = '';
		var ebayProduct = {
			variations: variations,
			currentPrice: savedFormula ? calculateAppliedPrice(salePrice) : salePrice,
			originalPrice: savedFormula ? calculateAppliedPrice(regularPrice) : regularPrice,
			title: title,
			description: description,
			productUrl: productUrl,
			reviews: [],
			// weight: weight.toString(),
			productId: productId,
			productCategoies: [],
			shortDescription: '',
			// importSalePrice: importSalePrice ? true : false,
			totalAvailQuantity: availQuantity || 1,
			images: images,
			simpleSku: productId,
			featured: importFeaturedProduct ? true : false
		}

		console.log('ebayProduct', ebayProduct);
	}

	function getEbayDEtailsInBulk(productIds, siteId) {
		if (!siteId) {
			siteId = 0;
		}

		var ebayLanguageInput = jQuery('input[name="ebayLanguage"]:checked')[0];
		var ebayLanguage = ebayLanguageInput ? ebayLanguageInput.value : "EBAY-US";

		var request = new XMLHttpRequest();
		request.onreadystatechange = function () {
			if (request.readyState == 4) {
				if (request.status === 200) {
					var response = JSON.parse(request.response);
					AddToWaitingListBulk(response.data[0]);

				} else if (request.status === 522) {
					var data = JSON.parse(request.response);
					displayToast(data.error[0].ShortMessage, "red");
					stopLoading();
				} else {
					var data = JSON.parse(request.response);
					displayToast("Error while getting product details", "red");
					stopLoading();
				}
			}
		};


		// let productIds = req.body.productIds;
		// let globalId = req.body.globalId || 'EBAY-US';

		request.open("POST", baseUrl + ":8002/getEbayInBulk", true);
		request.setRequestHeader("Content-Type", "application/json");
		request.send(JSON.stringify({ productIds: productIds, globlId: ebayLanguage, isPlugin: true, siteId: siteId }));
	}





	jQuery(document).on("click", "#seachbyStoreButton", function (e) {

		let storeName = 'mom5boy';
		let searchKeyword = 'shoes';
		let url = 'https://www.ebay.com/sch/i.html?_dkr=1&_ssn=' + storeName + '&_oac=1&_nkw=' + searchKeyword;
		jQuery.ajax({
			url: wooshark_params.ajaxurl,
			type: "POST",
			dataType: "JSON",
			data: {
				
				                nonce: wooshark_params.nonce,

				action: "search_product_by_store_alibay",
				ebay_url: url
			},
			success: function (response) {

				// jQuery('#ebay-product-search-container').html(response.data);

			},
			error: function (e) {
				e.responseText, console.log("****err", e), stopLoading();
			},
			complete: function () {
				console.log("SSMEerr"), stopLoading();
			}
		});
	});


	jQuery(document).on("click", "#remove-product-from-draft_ebay", function (e) {
		removeProductFromWP_ebay(jQuery(this).attr("idOfPRoductToRemove"));
	});


	jQuery(document).on("click", "#addAttributeToDescription", function (event) {
		let specifications = jQuery("#table-specific");
		specifications.find('tr').each(function () {
			jQuery(this).find('th:last-child, td:last-child').remove();
		});
		let newContent = quill.root.innerHTML + '<div>' + specifications[0].outerHTML + '</div>';


					quill.setContents([]);
                    var delta =  quill.clipboard.convert(newContent);
                    quill.setContents(delta);


		// quill.setContents([]);
		// quill.clipboard.dangerouslyPasteHTML(0, newContent);
	});


	jQuery(document).on("click", "#ImportImagesFromGallery", function (event) {
		let galleryImages = jQuery("#galleryPicture img");
		let newContent = quill.root.innerHTML;

		jQuery(galleryImages).each(function (index, image) {
			newContent = newContent + "<div>" + image.outerHTML + "</div>";
		});

 					quill.setContents([]);
                    var delta =  quill.clipboard.convert(newContent);
                    quill.setContents(delta);


		// quill.setContents([]);
		// quill.clipboard.dangerouslyPasteHTML(0, newContent);
	});



	function loadCategories(tags) {
		const container = document.getElementById('shopCategories');
		let names = new Set(); // To store unique names

		if (tags && tags.length > 0) {
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
