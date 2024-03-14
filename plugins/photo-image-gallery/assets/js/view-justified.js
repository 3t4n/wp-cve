jQuery.each(img_gallery_param_obj, function (index, value) {
    if (!isNaN(value)) {
        img_gallery_param_obj[index] = parseInt(value);
    }
});

function UXGallery_Justified(id) {
    var _this = this;
    _this.body = jQuery('body');
    _this.container = jQuery('#' + id + '.view-justified');
    _this.content = _this.container.parent();
    _this.defaultBlockWidth = img_gallery_param_obj.uxgallery_ht_view6_width;
    _this.ratingType = _this.content.data('rating-type');
    _this.likeContent = jQuery('.uxgallery_like_cont');
    _this.likeCountContainer = jQuery('.ux_like_count');
    _this.loadMoreBtn = _this.content.find('.load_more_button2');
    _this.loadingIcon = _this.content.find('.loading2');
    _this.documentReady = function () {
        jQuery(window).load(function () {
            jQuery('.justified-gallery a').each(function () {
                var img = jQuery(this).find('img');
                if (jQuery(this).find('img').attr('alt') == '') {
                    img.parent().find(".caption").css('display', 'none');
                }
            });
            galleryImgRatingCountsOptimize(_this.container, _this.ratingType);
        });
    };
    _this.addEventListeners = function () {
        _this.loadMoreBtn.on('click', _this.loadMoreBtnClick);
    };
    _this.loadMoreBtnClick = function () {
        var justifiedLoadNonce = jQuery(this).attr('data-justified-nonce-value');
        if (parseInt(_this.content.find(".pagenum:last").val()) < parseInt(_this.content.find("#total").val())) {
            var pagenum = parseInt(_this.content.find(".pagenum:last").val()) + 1;
            var perpage = _this.content.attr('data-content-per-page');
            var galleryid = _this.content.attr('data-gallery-id');
            var pID = postID;
            var likeStyle = _this.ratingType;
            var ratingCount = img_gallery_param_obj.uxgallery_ht_just_rating_count;
            _this.getResult(pagenum, perpage, galleryid, pID, likeStyle, ratingCount, justifiedLoadNonce);
        } else {
            _this.loadMoreBtn.hide();
        }
        return false;
    };
    _this.getResult = function (pagenum, perpage, galleryid, pID, likeStyle, ratingCount, justifiedLoadNonce) {
        var data = {
            action: "uxgallery_ajax",
            task: 'load_image_justified',
            page: pagenum,
            perpage: perpage,
            galleryid: galleryid,
            pID: pID,
            likeStyle: likeStyle,
            ratingCount: ratingCount,
            galleryImgJustifiedLoadNonce: justifiedLoadNonce
        };
        _this.loadingIcon.show();
        _this.loadMoreBtn.hide();
        jQuery.post(img_gallery_adminUrl, data, function (response) {
                if (response.success) {
                    var $objnewitems = jQuery(response.success);
                    _this.container.append($objnewitems);
                    setTimeout(function () {
                        _this.container.justifiedGallery();

                    }, 100);
                    _this.loadMoreBtn.show();
                    _this.loadingIcon.hide();
                    if (_this.content.find(".pagenum:last").val() == _this.content.find("#total").val()) {
                        _this.loadMoreBtn.hide();
                    }
                    jQuery('.ux_like_thumb').on("click tap", galleryImgRatingClick);
                    jQuery('.ux_dislike_thumb').on("click tap", galleryImgDislikeClick);
                    galleryImglightboxInit();
                    galleryImgRatingCountsOptimize(_this.container, _this.ratingType);
                    setTimeout(function () {
                        jQuery('.justified-gallery a').each(function () {
                            var img = jQuery(this).find('img');
                            if (jQuery(this).find('img').attr('alt') == '') {
                                img.parent().find(".caption").css('display', 'none');
                            }
                        });
                    }, 500);
                } else {
                    alert("no");
                }
            }
            , "json");
    };
    _this.init = function () {
        _this.container.justifiedGallery();
        _this.documentReady();
        _this.addEventListeners();
    };
    this.init();
}

var galleries = [];
jQuery(document).ready(function () {
    jQuery(".gallery-img-content").hide();
    jQuery(".mygallery.view-justified").each(function (i) {
        var id = jQuery(this).attr('id');
        galleries[i] = new UXGallery_Justified(id);
    });
    jQuery(".gallery-img-content").show();
});

