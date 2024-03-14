jQuery.noConflict();
(function ($) {
    var WRAPPER = $('#oxi-addons-preview-data').attr('template-wrapper');
    $(".oxi-addons-tabs-ul li:first").addClass("active");
    $(".oxi-addons-tabs-content-tabs:first").addClass("active");
    $(".oxi-addons-tabs-ul li").click(function () {
        if ($(this).hasClass('active')) {
            $(".oxi-addons-tabs-ul li").removeClass("active");
            var activeTab = $(this).attr("ref");
            $(activeTab).removeClass("active");
        } else {
            $(".oxi-addons-tabs-ul li").removeClass("active");
            $(this).toggleClass("active");
            $(".oxi-addons-tabs-content-tabs").removeClass("active");
            var activeTab = $(this).attr("ref");
            $(activeTab).addClass("active");
        }
    });
    $("#oxi-addons-setting-reload").click(function () {
        location.reload();
    });
    $(".oxi-head").click(function () {
        var self = $(this).parent();
        self.toggleClass("oxi-admin-head-d-none");
    });
    $(".shortcode-addons-templates-right-panel-heading").click(function () {
        var self = $(this).parent();
        self.toggleClass("oxi-admin-head-d-none");
    });
    $("#oxi-addons-list-data-modal-open").on("click", function () {
        $("#oxi-addons-list-data-modal").modal("show");
        $('#oxi-flip-template-modal-form').trigger("reset");
        $('#item-id').val('');
    });
    $("#oxi-addons-rearrange-data-modal-open").on("click", function () {
        $("#oxi-addons-list-rearrange-modal").modal("show");
    });

})(jQuery);
jQuery('.flip_box_admin_input_icon').iconpicker();
jQuery(".OXIAddonsElementsDeleteSubmit").submit(function () {
    var status = confirm("Do you Want to Deactive this Elements?");
    if (status == false) {
        return false;
    } else {
        return true;
    }
});
jQuery(".oxilab-style-absulate-delete-confirmation").submit(function () {
    var status = confirm("Do you want to Delete this Column Data? If deleted will never Restored.");
    if (status == false) {
        return false;
    } else {
        return true;
    }
});

jQuery(".oxi-addons-style-delete .btn.btn-danger").on("click", function () {
    var status = confirm("Do you want to Delete this Shortcode? Before delete kindly confirm that you don't use or already replaced this Shortcode. If deleted will never Restored.");
    if (status == false) {
        return false;
    } else {
        return true;
    }
});
jQuery(".btn btn-warning.oxi-addons-addons-style-btn-warning").on("click", function () {
    var status = confirm("Do you Want to Deactive This Layouts?");
    if (status == false) {
        return false;
    } else {
        return true;
    }
});

function oxiequalHeight(group) {
    tallest = 0;
    group.each(function () {
        thisHeight = jQuery(this).height();
        if (thisHeight > tallest) {
            tallest = thisHeight;
        }
    });
    group.height(tallest);
}
setTimeout(function () {
    oxiequalHeight(jQuery(".oxiequalHeight"));
}, 500);


setTimeout(function () {
    jQuery("<style type='text/css'>.oxi-addons-style-left-preview{background: " + jQuery("#shortcode-addons-2-0-preview").val() + "; } </style>").appendTo(".oxi-addons-style-left-preview");
}, 500);

oxiequalHeight(jQuery(".oxiaddonsoxiequalHeight"));

setTimeout(function () {
    if (jQuery(".table").hasClass("oxi_addons_table_data")) {
        jQuery(".oxi_addons_table_data").DataTable({
            "aLengthMenu": [[7, 25, 50, -1], [7, 25, 50, "All"]],
            "initComplete": function (settings, json) {
                jQuery(".oxi-addons-row.table-responsive").css("opacity", "1").animate({height: jQuery(".oxi-addons-row.table-responsive").get(0).scrollHeight}, 1000);
                ;
            }
        });
    }
}, 500);

jQuery("#shortcode-addons-2-0-color").on("change", function (e) {
    $input = jQuery(this);
    jQuery("<style type='text/css'>.oxi-addons-style-left-preview{background: " + $input.val() + "; } </style>").appendTo(".oxi-addons-style-left-preview");
    jQuery('#shortcode-addons-2-0-preview').val($input.val());
});

jQuery('.oxilab-vendor-color').each(function () {
    jQuery(this).minicolors({
        control: jQuery(this).attr('data-control') || 'hue',
        defaultValue: jQuery(this).attr('data-defaultValue') || '',
        format: jQuery(this).attr('data-format') || 'hex',
        keywords: jQuery(this).attr('data-keywords') || 'transparent' || 'initial' || 'inherit',
        inline: jQuery(this).attr('data-inline') === 'true',
        letterCase: jQuery(this).attr('data-letterCase') || 'lowercase',
        opacity: jQuery(this).attr('data-opacity'),
        position: jQuery(this).attr('data-position') || 'bottom left',
        swatches: jQuery(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
        change: function (value, opacity) {
            if (!value)
                return;
            if (opacity)
                value += ', ' + opacity;
            if (typeof console === 'object') {
                // console.log(value);
            }
        },
        theme: 'bootstrap'
    });
});


jQuery("#oxilab-flip-type").on("change", function () {
    jQuery(".oxilab-flip-box-flip").removeClass("oxilab-flip-box-flip-top-to-bottom");
    jQuery(".oxilab-flip-box-flip").removeClass("oxilab-flip-box-flip-left-to-right");
    jQuery(".oxilab-flip-box-flip").removeClass("oxilab-flip-box-flip-bottom-to-top");
    jQuery(".oxilab-flip-box-flip").removeClass("oxilab-flip-box-flip-right-to-left");
    jQuery(".oxilab-flip-box-flip").addClass(jQuery(this).val());
});
jQuery("#oxilab-flip-effects").on("change", function () {
    jQuery(".oxilab-flip-box-style-data").removeClass("easing_easeInOutExpo");
    jQuery(".oxilab-flip-box-style-data").removeClass("easing_easeInOutCirc");
    jQuery(".oxilab-flip-box-style-data").removeClass("easing_easeOutBack");
    jQuery(".oxilab-flip-box-style-data").addClass(jQuery(this).val());
});

jQuery(".oxilab-vendor-color").on("change", function () {
    var type = jQuery(this).attr('oxiexporttype');
    var exportid = jQuery(this).attr('oxiexportid');
    if (type !== '' && exportid !== '') {
        jQuery("<style type='text/css'> " + exportid + "{" + type + ": " + jQuery(this).val() + ";} </style>").appendTo("#oxi-addons-preview-data");
    }
});