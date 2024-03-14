var styleid = jQuery('#style-id').val();
jQuery("#flip-col").on("change", function () {
    jQuery(".oxilab-flip-box-padding-" + styleid + "").removeClass("oxilab-flip-box-col-1");
    jQuery(".oxilab-flip-box-padding-" + styleid + "").removeClass("oxilab-flip-box-col-2");
    jQuery(".oxilab-flip-box-padding-" + styleid + "").removeClass("oxilab-flip-box-col-3");
    jQuery(".oxilab-flip-box-padding-" + styleid + "").removeClass("oxilab-flip-box-col-4");
    jQuery(".oxilab-flip-box-padding-" + styleid + "").removeClass("oxilab-flip-box-col-5");
    jQuery(".oxilab-flip-box-padding-" + styleid + "").removeClass("oxilab-flip-box-col-6");
    jQuery(".oxilab-flip-box-padding-" + styleid + "").addClass(jQuery("#flip-col").val());
});
jQuery("#flip-width").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + "{max-width: " + jQuery(this).val() + "px; } </style>").appendTo("#oxi-addons-preview-data");
    var intvalue = parseInt(jQuery(this).val(), 10);
    var intvalue2 = parseInt(jQuery("#flip-height").val(), 10);
    var value = (intvalue2 / intvalue) * 100;
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + ":after{padding-bottom: " + value + "%; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#flip-height").on("change", function () {
    var intvalue = parseInt(jQuery("#flip-width").val(), 10);
    var intvalue2 = parseInt(jQuery(this).val(), 10);
    var value = (intvalue2 / intvalue) * 100;
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + ":after{padding-bottom: " + value + "%; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#flip-border-radius").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-front{border-radius: " + jQuery(this).val() + "%;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + "{border-radius: " + jQuery(this).val() + "%;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + " img{border-radius: " + jQuery(this).val() + "%;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-back{border-radius: " + jQuery(this).val() + "%;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "{border-radius: " + jQuery(this).val() + "%;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#margin-top").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-padding-" + styleid + "{padding: " + jQuery(this).val() + "px " + jQuery("#margin-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#margin-left").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-padding-" + styleid + "{padding: " + jQuery("#margin-top").val() + "px " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#flip-open-tabs-yes").on("change", function () {
    var data = "<strong>Link Open</strong> will works after saved data";
    jQuery.bootstrapGrowl(data, {});
});
jQuery("#flip-open-tabs-no").on("change", function () {
    var data = "<strong>Link Open</strong> will works after saved data";
    jQuery.bootstrapGrowl(data, {});
});
jQuery("#oxilab-animation").on("change", function () {
    var data = "<strong>Animation</strong> will works after saved data";
    jQuery.bootstrapGrowl(data, {});
});
jQuery("#animation-duration").on("change", function () {
    var data = "<strong>Animation Duration</strong> will works after saved data";
    jQuery.bootstrapGrowl(data, {});
});
jQuery("#flip-boxshow-color").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-front{box-shadow: " + jQuery("#flip-boxshow-horizontal").val() + "px " + jQuery("#flip-boxshow-vertical").val() + "px " + jQuery("#flip-boxshow-blur").val() + "px " + jQuery("#flip-boxshow-spread").val() + "px " + jQuery(this).val() + ";} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-back{box-shadow: " + jQuery("#flip-boxshow-horizontal").val() + "px " + jQuery("#flip-boxshow-vertical").val() + "px " + jQuery("#flip-boxshow-blur").val() + "px " + jQuery("#flip-boxshow-spread").val() + "px " + jQuery(this).val() + ";} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#flip-boxshow-horizontal").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-front{box-shadow: " + jQuery(this).val() + "px " + jQuery("#flip-boxshow-vertical").val() + "px " + jQuery("#flip-boxshow-blur").val() + "px " + jQuery("#flip-boxshow-spread").val() + "px " + jQuery("#flip-boxshow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-back{box-shadow: " + jQuery(this).val() + "px " + jQuery("#flip-boxshow-vertical").val() + "px " + jQuery("#flip-boxshow-blur").val() + "px " + jQuery("#flip-boxshow-spread").val() + "px " + jQuery("#flip-boxshow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#flip-boxshow-vertical").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-front{box-shadow: " + jQuery("#flip-boxshow-horizontal").val() + "px " + jQuery(this).val() + "px " + jQuery("#flip-boxshow-blur").val() + "px " + jQuery("#flip-boxshow-spread").val() + "px " + jQuery("#flip-boxshow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-back{box-shadow: " + jQuery("#flip-boxshow-horizontal").val() + "px " + jQuery(this).val() + "px " + jQuery("#flip-boxshow-blur").val() + "px " + jQuery("#flip-boxshow-spread").val() + "px " + jQuery("#flip-boxshow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#flip-boxshow-blur").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-front{box-shadow: " + jQuery("#flip-boxshow-horizontal").val() + "px " + jQuery("#flip-boxshow-vertical").val() + "px " + jQuery(this).val() + "px " + jQuery("#flip-boxshow-spread").val() + "px " + jQuery("#flip-boxshow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-back{box-shadow: " + jQuery("#flip-boxshow-horizontal").val() + "px " + jQuery("#flip-boxshow-vertical").val() + "px " + jQuery(this).val() + "px " + jQuery("#flip-boxshow-spread").val() + "px " + jQuery("#flip-boxshow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#flip-boxshow-spread").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-front{box-shadow: " + jQuery("#flip-boxshow-horizontal").val() + "px " + jQuery("#flip-boxshow-vertical").val() + "px " + jQuery("#flip-boxshow-blur").val() + "px " + jQuery(this).val() + "px " + jQuery("#flip-boxshow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-body-" + styleid + " .oxilab-flip-box-back{box-shadow: " + jQuery("#flip-boxshow-horizontal").val() + "px " + jQuery("#flip-boxshow-vertical").val() + "px " + jQuery("#flip-boxshow-blur").val() + "px " + jQuery(this).val() + "px " + jQuery("#flip-boxshow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#front-padding-top").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + "{top: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + "{left: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + "{right: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + "{bottom: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#front-margin-top-bottom").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + " img{top: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + " img{left: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + " img{right: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + " img{bottom: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
    var intvalue = parseInt(jQuery(this).val(), 10);
    var value = intvalue * 2;
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + " img{width: calc(100% - " + value + "px)} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + " img{height: calc(100% - " + value + "px)} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#flip-col-border-size").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + "{border-width: " + jQuery(this).val() + "px; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#flip-col-border-style").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-" + styleid + "{border-style: " + jQuery(this).val() + "; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-border-padding-top").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data   .oxilab-flip-box-back-" + styleid + "{top: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data   .oxilab-flip-box-back-" + styleid + "{left: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data   .oxilab-flip-box-back-" + styleid + "{right: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
    jQuery("<style type='text/css'> #oxi-addons-preview-data   .oxilab-flip-box-back-" + styleid + "{bottom: " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-padding-top").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data{padding: " + jQuery(this).val() + "px " + jQuery("#backend-padding-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-padding-left").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data{padding: " + jQuery("#backend-padding-top").val() + "px " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#flip-backend-border-size").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "{border-width: " + jQuery(this).val() + "px; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#flip-backend-border-style").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "{border-style: " + jQuery(this).val() + "; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-heading-size").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-heading{font-size: " + jQuery(this).val() + "px; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery('#backend-heading-family').change(function () {
    var font = jQuery(this).val().replace(/\+/g, ' ');
    font = font.split(':');
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-heading{ font-family:" + font[0] + ";} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-heading-style").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-heading{font-style: " + jQuery(this).val() + "; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-heading-weight").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-heading{font-weight: " + jQuery(this).val() + "; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-heading-text-align").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-heading{text-align: " + jQuery(this).val() + "; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-heading-padding-top").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-heading{padding: " + jQuery(this).val() + "px " + jQuery("#backend-heading-padding-right").val() + "px " + jQuery("#backend-heading-padding-bottom").val() + "px " + jQuery("#backend-heading-padding-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-heading-padding-bottom").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-heading{padding: " + jQuery("#backend-heading-padding-top").val() + "px " + jQuery("#backend-heading-padding-right").val() + "px " + jQuery(this).val() + "px " + jQuery("#backend-heading-padding-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-heading-padding-right").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-heading{padding: " + jQuery("#backend-heading-padding-top").val() + "px " + jQuery(this).val() + "px " + jQuery("#backend-heading-padding-bottom").val() + "px " + jQuery("#backend-heading-padding-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-heading-padding-left").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-heading{padding: " + jQuery("#backend-heading-padding-top").val() + "px " + jQuery("#backend-heading-padding-right").val() + "px " + jQuery("#backend-heading-padding-bottom").val() + "px " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-title-border-width").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-heading .oxilab-span{width: " + jQuery(this).val() + "px; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-title-border-height").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-heading .oxilab-span{height: " + jQuery(this).val() + "px; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-info-size").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-info{font-size: " + jQuery(this).val() + "px; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery('#backend-info-family').change(function () {
    var font = jQuery(this).val().replace(/\+/g, ' ');
    font = font.split(':');
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-info{ font-family:" + font[0] + ";} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-info-style").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-info{font-style: " + jQuery(this).val() + "; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-info-weight").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-info{font-weight: " + jQuery(this).val() + "; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-info-text-align").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-info{text-align: " + jQuery(this).val() + "; } </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-info-padding-top").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-info{padding: " + jQuery(this).val() + "px " + jQuery("#backend-info-padding-right").val() + "px " + jQuery("#backend-info-padding-bottom").val() + "px " + jQuery("#backend-info-padding-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-info-padding-bottom").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-info{padding: " + jQuery("#backend-info-padding-top").val() + "px " + jQuery("#backend-info-padding-right").val() + "px " + jQuery(this).val() + "px " + jQuery("#backend-info-padding-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-info-padding-right").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-info{padding: " + jQuery("#backend-info-padding-top").val() + "px " + jQuery(this).val() + "px " + jQuery("#backend-info-padding-bottom").val() + "px " + jQuery("#backend-info-padding-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});
jQuery("#backend-info-padding-left").on("change", function () {
    jQuery("<style type='text/css'> #oxi-addons-preview-data  .oxilab-flip-box-back-" + styleid + "-data .oxilab-info{padding: " + jQuery("#backend-info-padding-top").val() + "px " + jQuery("#backend-info-padding-right").val() + "px " + jQuery("#backend-info-padding-bottom").val() + "px " + jQuery(this).val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
});