// (function (jQuery) {

// jQuery(document).on("click", "#insertProductAsAffiliate", function (e) {
//     let t = jQuery("#affiliateLinkUrl").val();
//     globalUrlProduct = t; 
//     if (t.includes("https")) {
//       jQuery(this).parents("tr").find(".newLoaderAffiliate").append(jQuery('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>')), 
//       insertProductAsAffiliate({
//         images: jQuery(this).parents("tr").find("#singleImages").text() ? jQuery(this).parents("tr").find("#singleImages").text().split(",") : [],
//         isAffiliate: !0,
//         asin: jQuery(this).parents("tr").find("#singleAsin").text(),
//         regularPice: jQuery(this).parents("tr").find("#singleRegularPrice").text(),
//         quantity: jQuery(this).parents("tr").find("#singleQuantity").text(),
//         salePrice: jQuery(this).parents("tr").find("#singleSalePrice").text(),
//         productUrl: t,
//         title: jQuery(this).parents("tr").find("#singleTitle").text()
//       })
//     } else displayToast("Please open the advanced settings and fill the affiliate link", "red")
//   })

// })(jQuery);
  

// function insertProductAsAffiliate(e) {
//     let t = jQuery("input[value=titleASIN]")[0].checked,
//       a = jQuery("input[value=galleryASIN]")[0].checked;
//     var i = [];
//     jQuery("#customProductCategory input:checked").each(function () {
//       i.push(jQuery(this).attr("value"))
//     });
//     var r = getReviews(),
//       o = (jQuery("#customPrice").val(), window.location.href),
//       n = o.indexOf("/dp/");
//     n < 0 && (n = o.indexOf("/gp/")), n += 4;
//     window.location.href;
//     var l = jQuery("#customProductTitle").val();
//     let s = [];
//     tagsProduct && tagsProduct.length && (s = tagsProduct);
//     var c = quill.root.innerHTML,
//       u = i;
//     customVariations = buildVariationsForSingleImport(), 
//     customVariations = getItemSpecificfromTableModalEbay(customVariations);
//     var d = jQuery("#shortDescription").val(),
//       p = jQuery("#isPublish")[0].checked,
//       y = (e.asin, jQuery("#isFeatured")[0].checked);
//     if (generalPreferences.importReviewsGeneral || (r = []), generalPreferences.importDescriptionGeneral || (c = ""), generalPreferences.textToReplace && generalPreferences.textToBeReplaced) {
//       var m = new RegExp(generalPreferences.textToBeReplaced, "g");
//       c = c.replace(m, generalPreferences.textToReplace)
//     }
//     var g = !0;
//     generalPreferences.importSalePriceGeneral || (g = !1);
//     jQuery("#customSalePrice").val();
//     let h = {};
//     h = {
//       title: t && e.title ? e.title : l,
//       currentPrice: e.readyState,
//       originalPrice: e.regularPice,
//       description: c,
//       images: a && e.images && e.images.length ? e.images : images,
//       totalAvailQuantity: e.quantity || 1,
//       productUrl: globalUrlProduct,
//       isPublish: p,
//       productCategoies: u,
//       productWeight: "",
//       reviews: r,
//       shortDescription: d,
//       simpleSku: e.asin,
//       importSalePrice: g,
//       salePrice: e.salePrice,
//       featured: y,
//       tags: s,
//       affiliateLink: e.isAffiliate ? e.productUrl : "",
//       button_text: e.isAffiliate ? jQuery("#customBuyNow").val() : "",
//       variations: customVariations
//     }, jQuery.ajax({
//       url: wooshark_params.ajaxurl,
//       type: "POST",
//       dataType: "JSON",
//       data: {
//         action: "theShark_alibay_insertProductInWoocommerceAffiliate",
//         sku: h.simpleSku.toString(),
//         title: h.title,
//         description: h.description || "",
//         images: h.images || [],
//         categories: h.productCategoies,
//         regularPrice: h.originalPrice.toString(),
//         salePrice: h.salePrice.toString(),
//         // quantity: h.totalAvailQuantity,
//         productType: "external",
//         attributes: customVariations.NameValueList || [],
//         variations: [],
//         isFeatured: jQuery("#isFeatured")[0].checked,
//         postStatus: jQuery("#isPublish")[0].checked ? "publish" : "draft",
//         shortDescription: h.shortDescription || "",
//         productUrl: h.productUrl,
//         importVariationImages: !0,
//         reviews: h.reviews,
//         tags: h.tags,
//         affiliateLink: e.isAffiliate ? e.productUrl : "",
//         includeShippingCostIntoFinalPrice: !1
//       },
//       success: function (e) {
//         e && e.error && e.error_msg && displayToast(e.error_msg, "red"), e && !e.error && e.data && displayToast(e.data, "green"), stopLoading(), jQuery(".lds-ring").remove(), e && e.error && e.error_msg && e.error_msg.includes("you have reached the permitted usage") && setTimeout(function () {
//           window.open("https://sharkdropship.com/aliexpress", "_blank")
//         }, 4e3)
//       },
//       error: function (e) {
//         jQuery(".lds-ring").remove(), stopLoading(), e && e.responseText && displayToast(e.responseText, "red")
//       }
//     })
//   }

//   function getReviews() {
//     var e = jQuery("#customReviews tbody tr"),
//       t = [];
//     return e && e.length
//       ? (e.each(function(e, a) {
//           e &&
//             t.push({
//               review: a.cells[0].innerHTML || "-",
//               rating:
//                 jQuery(a)
//                   .find("input")
//                   .val() || 5,
//               datecreation: a.cells[2].outerText,
//               username: a.cells[1].outerText || "unknown",
//               email: "test@test.com"
//             });
//         }),
//         t)
//       : [];
//   }

//   function buildVariationsForSingleImport() {
//     return {
//       variations: [],
//       NameValueList: []
//     }
//   }