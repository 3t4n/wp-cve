function YrmLink() {

    this.id;
}

YrmLink.prototype = new YrmMore();
YrmLink.constructor = YrmLink;

YrmLink.prototype.init = function () {

    var id = this.id;
    if(typeof readMoreArgs[id] == 'undefined') {
        console.log('Invalid Data');
        return;
    }

    var data = readMoreArgs[id];
	this.initialMutator(data);

    this.setData('readMoreData', data);
    this.setData('id', id);
    this.setStyles();
    this.livePreview();

    var duration = parseInt(data['animation-duration']);
    var hideAfterClick = data['hide-button-after-click'];

    jQuery('.yrm-toggle-expand-'+id).each(function () {
        var position = -1;
        var initialScroll = -1;
        var toggleContentId = jQuery(this).attr('data-rel');
        setTimeout(function () {
            jQuery("#" + toggleContentId).removeClass('yrm-hide').addClass('yrm-content-hidden');
        }, 0)

        jQuery(this).unbind('click').bind('click', function (e) {
            e.preventDefault();
            var link = '';

            if(data['link-button-url']) {
                link = data['link-button-url'];
            }
            if(data['shortcodeurl']) {
                link = data['shortcodeurl'];
            }

            var isConfirmed = true;

            if(data['link-button-confirm']) {
                isConfirmed = confirm(data['link-button-confirm-text']);
            }

            if(isConfirmed) {
                if(data['link-button-new-tab']) {
                    window.open(link)
                }
                else {
                    window.location = link;
                }
            }
        })
    });
};