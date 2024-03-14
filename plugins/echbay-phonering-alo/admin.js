function EPA_build_dynamic_style() { var str = ""; var grid_count = 2; if ( jQuery("#messenger_url").val() != "" ||
jQuery("#messenger_full_url").val() != ""
) { str += ".echbay-sms-messenger div.phonering-alo-messenger{display: block;}"; grid_count++; } if (jQuery("#zalo_url").val() != "") {
str += ".echbay-sms-messenger div.phonering-alo-zalo{display: block;}"; grid_count++; } var mobile_pading_grid = ""; if (jQuery("#mobile_grid").is(":checked")) {
mobile_pading_grid += "body{padding-bottom: 45px;}"; } grid_count = (100 / grid_count).toFixed(6) * 1; str += "@media only screen and (max-width:588px) {.style-for-mgrid-1 div{flex-basis: " + grid_count + "%; max-width: " + grid_count + "%;} " + mobile_pading_grid + " }"; str += EPA_set_custom_color( "header_bg", [ ".echbay-sms-messenger div.phonering-alo-alo a", ".echbay-phone-number a", ".echbay-phone-number a span", ".phonering-alo-phone.phonering-alo-green .phonering-alo-ph-img-circle", ].join(",") ); str += EPA_set_custom_color( "sms_bg", ".echbay-sms-messenger div.phonering-alo-sms a" ); str += EPA_set_custom_color( "messenger_bg", ".echbay-sms-messenger div.phonering-alo-messenger a" ); var a = jQuery("#mobile_width").val() * 1;
if (a > 0) { str += "@media only screen and (min-width:" + a + "px) {.echbay-alo-phone, .echbay-sms-messenger{display: none;} }"; } jQuery(".epa-exemple-preview-css").html("<style>" + str + "</style>"); jQuery("#dynamic_style").val(str);
return str; } function EPA_set_custom_color(k, cl) { var a = jQuery("#" + k).val() || "";
if ( a != "" && typeof arr_default_settings[k] != "undefined" && a != arr_default_settings[k] ) { return cl + "{background-color: " + a + ";}"; } return ""; } function EPA_set_default_color(k) { if ( typeof arr_default_settings[k] != "undefined" && arr_default_settings[k] != "" ) { jQuery("#" + k).val(arr_default_settings[k]);
EPA_build_dynamic_style(); } } (function () { /* * for event change checkbox before submit */ jQuery('.epa-table input[type="checkbox"]') .change(function () { var a = jQuery(this).attr("data-for") || ""; if (a != "") { console.log(a); var v = 0; if (jQuery(this).is(":checked")) { v = 1; } jQuery('.epa-table input[data-k="' + a + '"]').val(v); } }) .each(function () { var a = jQuery(this).attr("data-for") || ""; if (a != "") { jQuery(this).trigger("change"); } }); jQuery(".each-to-selected").each(function () { var a = jQuery(this).attr("data-value") || ""; if (a != "" && jQuery('option[value="' + a + '"]', this).length > 0) { jQuery(this).val(a); } }); jQuery("#html_template").change(function () {
var a = jQuery(this).val() || ""; console.log(a); var ids = [ "#tr_messenger_url",
"#tr_messenger_full_url",
"#tr_zalo_url",
"#tr_sms_bg",
"#tr_messenger_bg",
"#tr_mobile_grid",
]; jQuery(ids.join(",")).hide(); if (a == "list_icon" || a == "list_hover_icon") { jQuery(ids.join(",")).show(); if (a == "list_icon") { jQuery("#tr_mobile_grid").show();
} } jQuery("#EPA_preview_template").attr({
"data-template": a, }); EPA_build_dynamic_style(); }); jQuery("#html_template").trigger("change");
jQuery("#widget_position").change(function () {
var a = jQuery(this).val() || ""; console.log(a); jQuery("#EPA_preview_template").attr({
"data-position": a, }); }); jQuery("#widget_position").trigger("change");
jQuery("#messenger_url, #zalo_url").change(function () {
var a = jQuery.trim(jQuery(this).val() || ""); console.log(a); if (a != "") { a = a.split("/"); if (a[a.length - 1] == "") { a = a[a.length - 2]; } else { a = a[a.length - 1]; } /* var _id = jQuery(this).attr("id") || ""; if (_id == "messenger_url") { a = "https://m.me/" + a;
} else if (_id == "zalo_url") { a = "https://zalo.me/" + a;
} */ jQuery(this).val(a); } EPA_build_dynamic_style(); }); jQuery("#messenger_full_url").change(function () {
var a = jQuery.trim(jQuery(this).val() || ""); console.log(a); if (a != "" && a.split("//").length == 1) {
jQuery(this).val("").focus(); alert( "If you want custom URL for messenger! Please enter full URL to here..." ); } }); jQuery("#header_bg, #sms_bg, #messenger_bg").change(function () {
EPA_build_dynamic_style(); }); EPA_build_dynamic_style(); jQuery('input[type="color"]').each(function () { jQuery(this).after( " <span onclick=\"return EPA_set_default_color('" + jQuery(this).attr("id") + "');\" class='cur a'>Default</span>" ); }); })(); 