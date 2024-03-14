SirvOptions = {};

SirvOptions['zoom'] = {};
SirvOptions['captions'] = {};
SirvOptions['image_captions'] = {};

SirvOptions['zoom']['onchange'] = function () {};

SirvOptions['zoom']['onready'] = function (instance) {
    let $id = instance.placeholder.getAttribute('data-id');
    jQuery('#' + $id + ' .sirv-caption.sirv-zoom-caption').hide();
    let $spins = JSON.parse(jQuery('#' + $id).attr('data-spins'));
    SirvOptions['captions'][$id] = JSON.parse(jQuery('#' + $id).attr('data-captions'));
    SirvOptions['image_captions'][$id] = JSON.parse(jQuery('#' + $id).attr('data-image-captions'));

    let $thumbsContainer = jQuery('#' + $id + ' .sirv-thumbs-box ul');

    let tHeight = jQuery('#' + $id).attr('data-thumbnails-height');

    for (let $i in $spins) {
        let img = document.createElement('img');
        let paramSymbol = $spins[$i].indexOf("?") !== -1 ? '&' : '?';
        //img.src = $spins[$i] + paramSymbol + 'thumbnail=' + jQuery('#' + $id).attr('data-thumbnails-height') + '&image&scale.option=noup';
        img.src = $spins[$i] + paramSymbol + 'image&w=' + tHeight + '&h=' + tHeight + '&canvas.width=' + tHeight + '&canvas.height=' + tHeight + '&scale.option=fit';
        img.setAttribute('data-item-id', $i);
        let thumb = instance.thumbnails.addItem(img);
        thumb.setAttribute('data-item', 'spin');

        //move spins to their real places
        let insertPos = parseInt($i) === 0 ? $i : $i - 1;
        let $posEl = jQuery('#' + $id + ' .sirv-thumbs-box li:eq( ' + insertPos + ' )');
        let $elem = jQuery('#' + $id + ' [data-item=spin] > [data-item-id=' + $i + ']').parent();
        if (parseInt($i) === 0) {
            $elem.prependTo($thumbsContainer);
        } else {
            $elem.insertAfter($posEl);
        }
    }
    if (instance.thumbnails !== null) instance.thumbnails.reflow();

    if ('0' in $spins) {
        jQuery('#' + $id + ' .sirv-gallery-item').addClass('sirv-hidden');
        jQuery('#' + $id + ' .sirv-thumbs-box li').removeClass('sirv-thumb-selected');
        jQuery('#' + $id + ' .sirv-gallery-item[data-item-id=0]').removeClass('sirv-hidden');
        jQuery('#' + $id + ' .sirv-thumbs-box li:eq( 0 )').addClass('sirv-thumb-selected');
        jQuery('#' + $id + ' .sirv-caption.sirv-zoom-caption').html(SirvOptions['captions'][$id][0]);
    }

    jQuery('#' + $id + ' .sirv-caption.sirv-zoom-caption').show();


    initSirvGallerySelectors($id);
};

function initSirvGallerySelectors($id) {
    SirvOptions['captions'][$id] = JSON.parse(jQuery('#' + $id).attr('data-captions'));
    SirvOptions['image_captions'][$id] = JSON.parse(jQuery('#' + $id).attr('data-image-captions'));
    jQuery('#' + $id + ' #sirv-thumbs-box-' + $id + ' img').on('click', function () {
        //jQuery('.sirv-thumbnails img').on('click',function(){
        if (typeof (jQuery(this).attr('data-item-id')) == 'undefined') {
            jQuery(this).attr('data-item-id', 'sirv-zoom');
            jQuery(this).attr('data-caption-id', jQuery(this).closest('li').index());
        }
        jQuery('#' + $id + ' .sirv-gallery-item').addClass('sirv-hidden');
        jQuery('#' + $id + ' .sirv-thumbs-box li').removeClass('sirv-thumb-selected');
        jQuery('#' + $id + ' .sirv-gallery-item[data-item-id=' + jQuery(this).attr('data-item-id') + ']').removeClass('sirv-hidden');

        if (jQuery(this).attr('data-caption-id') != null) {
            jQuery('#' + $id + ' .sirv-caption.sirv-zoom-caption').html(SirvOptions['image_captions'][$id][jQuery(this).attr('data-caption-id')]);
        } else {
            jQuery('#' + $id + ' .sirv-caption.sirv-zoom-caption').html(SirvOptions['captions'][$id][jQuery(this).attr('data-item-id')]);
        }

        jQuery(this).closest('li').addClass('sirv-thumb-selected');
        if (jQuery('.no-sirv-zoom').length >= 1 && !jQuery('#' + $id + '.no-sirv-zoom .sirv-gallery-item[data-item-id=' + jQuery(this).attr('data-item-id') + '] img').hasClass('Sirv')) {
            jQuery('#' + $id + ' .sirv-gallery-item[data-item-id=' + jQuery(this).attr('data-item-id') + '] img').addClass('Sirv');
            Sirv.start();
        }

    });
};

jQuery(document).ready(function () {
    jQuery('.sirv-gallery.no-sirv-zoom').each(function () {
        initSirvGallerySelectors(jQuery(this).attr('id'));
    });
});
