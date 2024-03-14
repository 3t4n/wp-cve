jQuery.each(img_gallery_param_obj, function (index, value) {
    if (!isNaN(value)) {
        img_gallery_param_obj[index] = parseInt(value);
    }
});

function UXGallery_Thumbnails(id) {
    var _this = this;
    _this.body = jQuery('body');
    _this.container = jQuery('#' + id + '.view-thumbnails');
    _this.content = _this.container.parent();
    _this.element = _this.container.find('.element');
    _this.defaultBlockWidth = img_gallery_param_obj.uxgallery_thumb_image_width;
    _this.defaultBlockHeiight = img_gallery_param_obj.uxgallery_thumb_image_height;
    _this.ratingType = _this.content.data('rating-type');
    _this.likeContent = jQuery('.uxgallery_like_cont');
    _this.likeCountContainer = jQuery('.ux_like_count');
    _this.loadMoreBtn = _this.content.find('.load_more_button3');
    _this.loadingIcon = _this.content.find('.loading3');
    _this.documentReady = function () {
        galleryImgRatingCountsOptimize(_this.container, _this.ratingType);
    };
    _this.addEventListeners = function () {
        _this.loadMoreBtn.on('click', _this.loadMoreBtnClick);
    };
    _this.loadMoreBtnClick = function () {
        var thumbnailLoadNonce = jQuery(this).attr('data-thumbnail-nonce-value');
        if (parseInt(_this.content.find(".pagenum:last").val()) < parseInt(_this.container.find("#total").val())) {
            var pagenum = parseInt(_this.content.find(".pagenum:last").val()) + 1;
            var perpage = _this.content.attr('data-content-per-page');
            var galleryid = _this.content.attr('data-gallery-id');
            var thumbtext = img_gallery_param_obj.uxgallery_thumb_view_text;
            var pID = postID;
            var likeStyle = _this.ratingType;
            var ratingCount = img_gallery_param_obj.uxgallery_ht_thumb_rating_count;
            _this.getResult(pagenum, perpage, galleryid, thumbtext, pID, likeStyle, ratingCount, thumbnailLoadNonce);
        } else {
            _this.loadMoreBtn.hide();
        }
        return false;
    };
    _this.getResult = function (pagenum, perpage, galleryid, thumbtext, pID, likeStyle, ratingCount, thumbnailLoadNonce) {
        var data = {
            action: "uxgallery_ajax",
            task: 'load_image_thumbnail',
            page: pagenum,
            perpage: perpage,
            galleryid: galleryid,
            thumbtext: thumbtext,
            pID: pID,
            likeStyle: likeStyle,
            ratingCount: ratingCount,
            galleryImgThumbnailLoadNonce: thumbnailLoadNonce,
            view_style: jQuery("input[name='view_style']").val()
        };
        _this.loadingIcon.show();
        _this.loadMoreBtn.hide();
        jQuery.post(img_gallery_adminUrl, data, function (response) {
                if (response.success) {
                    var $objnewitems = jQuery(response.success);
                    _this.container.append($objnewitems);
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
                        if (img_gallery_param_obj.uxgallery_image_natural_size_thumbnail == 'natural') {
                            _this.naturalImageThumb();
                        }
                    }, 200);
                    jQuery('.view-fifth ').each(function () {
                        jQuery(this).hoverdir();
                    });


                } else {
                    alert("no");
                }
            }
            , "json");
    };
    _this.naturalImageThumb = function () {
        _this.container.find(".ux_big_li img").each(function (i, img) {
            var imgStr = jQuery(this).prop('naturalWidth') / jQuery(this).prop('naturalHeight');
            var elemStr = _this.defaultBlockWidth / _this.defaultBlockHeiight;
            if (imgStr <= elemStr) {
                jQuery(img).css({
                    position: "relative",
                    width: '100%',
                    top: '50%',
                    transform: 'translateY(-50%)'
                });
            } else {
                jQuery(img).css({
                    position: "relative",
                    height: '100%',
                    left: '50%',
                    transform: 'translateX(-50%)'
                });
            }
        });
    };
    _this.init = function () {
        _this.documentReady();
        _this.addEventListeners();
        jQuery(window).load(function () {
            if (img_gallery_param_obj.uxgallery_image_natural_size_thumbnail == 'natural') {
                _this.naturalImageThumb();
            }
        });
    };
    this.init();
}

var galleries = [];
jQuery(document).ready(function () {
    jQuery(".uxgallery.view-thumbnails").each(function (i) {
        var id = jQuery(this).attr('id');
        galleries[i] = new UXGallery_Thumbnails(id);
    });
});

