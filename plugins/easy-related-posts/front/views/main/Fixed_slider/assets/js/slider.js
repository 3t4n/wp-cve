function ERPSlider(pos, bkgClr, hghttrgr, trnsprnc, container, title) {
    this.w = jQuery(".metroW").width();
    this.position = pos;
    this.bkgColor = bkgClr;
    this.heightPercentageTrigger = hghttrgr;
    this.transparency = trnsprnc;
    this.headerHeight = jQuery('header').height();
    this.container = container;
    this.maxZ = jQuery().getMaxZ();
    this.title = title;
}

ERPSlider.prototype.sliderInitializer = function() {
    this.h = jQuery(".metroW").parent().height();
    this.container.css({
        "width": this.w + "px",
        "position": "fixed",
        "z-index": this.maxZ + 1000,
        "display": "none",
        "background-color": "transparent"
    });

    this.container.children('.container-fluid').css('z-index', this.maxZ + 2000);

    var titleHTML = '<h2 class="erpProTitle text-center" style="">' + this.title + '</h2>';
    var contOpenHTML = '<div class="erp_cont_open"></div>';
    var contCloseHTML = '<div class="erp_cont_close"></div>';

    switch (this.position) {
        case "left":
        case "right":
        case "top":
            this.container.css({
                "top": "0px"
            });
            this.transBkg(this.transparency, "top-trns");
            this.container.append(titleHTML);
            this.container.append(contOpenHTML);
            this.container.append(contCloseHTML);
            jQuery('.erp_cont_open').css('bottom', '10px');
            jQuery('.erp_cont_close').css('bottom', '10px');
            break;
        case "bottom":
            this.container.css({
                "bottom": "0px"
            });
            this.transBkg(this.transparency, "bottom-trns");
            this.container.prepend(contCloseHTML);
            this.container.prepend(contOpenHTML);
            this.container.prepend(titleHTML);
            this.container.children('.erp_cont_open').css('top', '0px');
            this.container.children('.erp_cont_close').css('top', '0px');
            break;
        default:
            break;
    }

    this.titleElem = this.container.children("h2");
};

ERPSlider.prototype.transBkg = function(opacity, pos) {
    this.container.append('<div class="trnsprntbkgrnd" style="background-color:'
            + this.bkgColor
            + ';" class="' + pos + '"></div>');

    jQuery(".trnsprntbkgrnd").css({"opacity": opacity});
};

ERPSlider.prototype.buttons = function() {
    /*this.container.prepend("<div class=\"erp_cont_close\" type=\"button\" ></div>");
     this.container.prepend("<div class=\"erp_cont_open\"  ></div>");*/

    closeButton = this.container.children('.erp_cont_close');
    openButton = this.container.children('.erp_cont_open');

    this.container.children(".container-fluid").wrapAll("<div class=\"containerWraper\"></div>");
    container = this.container;
    slider = this;

    closeButton.click(function() {
//		slider.hideAnim();
        container.children(".containerWraper").slideToggle("fast", function() {
            closeButton.hide(1);
            openButton.show(1);
            slider.closed = true;
        });
    });
    openButton.click(function() {
//		slider.showAnim();
        container.children(".containerWraper").slideToggle("fast", function() {
            openButton.hide(1);
            closeButton.show(1);
            slider.closed = false;
        });
    });
};

ERPSlider.prototype.showAnim = function() {
    switch (this.position) {
        case "left":
        case "right":
        case "top":
            this.container.slideDown("slow");
            break;
        case "bottom"://bottom
            this.container.slideDown("slow");
            break;
        default:
            break;
    }
};

ERPSlider.prototype.hideAnim = function() {
    switch (this.position) {
        case "left":
        case "right":
        case "top":
            this.container.slideUp("slow");
            break;
        case "bottom":
            this.container.slideUp("slow");
            break;
        default:
            break;
    }
};

ERPSlider.prototype.erpToggler = function() {
    this.container.children("h2").css("margin", "7px 0 7px 0");
    slider = this;
    jQuery(window)
            .scroll(
                    function() {
                        var y = jQuery(window).scrollTop();
                        var winHeight = jQuery(window).height();

                        if (y + (winHeight / 2) > ((slider.h + slider.headerHeight) * slider.heightPercentageTrigger)
                                || y == jQuery(document).height() - winHeight) {
                            slider.showAnim();
                        } else {
                            slider.hideAnim();
                        }
                    });
};
