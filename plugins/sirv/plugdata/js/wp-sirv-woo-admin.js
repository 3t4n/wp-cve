jQuery( function($){
  "use strict";

  $(document).ready( function(){


    $(window).on("update_woo_sirv_product_image", updateWooSirvProductImage);
    function updateWooSirvProductImage(){
      const imgPattern = "?thumbnail=266&image";
      let id = window.sirvProductID;
      let imgUrl = $($("#sirv_woo_product_image_" + id)[0]).val();
      const $img = $("#sirv-woo-product-image-container_"+ id +" .sirv-woo-product-image img");

      if(!!imgUrl){
        $img.attr('src', imgUrl + imgPattern);
      }

      window.sirvProductID = "";
    }

    $(".sirv-woo-delete-product-image").on('click', deleteProductImage);
    function deleteProductImage(){
      const id = $(this).attr('data-id');
      const productImagePlaceholder = $(this).attr('data-placeholder');
      const $img = $("#sirv-woo-product-image-container_"+ id +" .sirv-woo-product-image img");
      const $storage = $("#sirv_woo_product_image_" + id);

      $img.attr('src', productImagePlaceholder);
      $storage.val("");
    }


    $(window).on('update_woo_sirv_images', updateWooSirvImages);
    function updateWooSirvImages(){
      let id = window.sirvProductID;
      let data = getStorageData(id);
      let $ul = $('#sirv-woo-images_'+ id);

      if(!isEmpty(data.items)){
        let galleryHTML = getGalleryHtml(id, data);
        //unBindEvents();
        $ul.empty();
        $ul.append(galleryHTML);
        reCalcGalleryData(id);
        imageSortable(id);
      }

      window.sirvProductID = '';
    }


    function getGalleryHtml(id, data) {
      let documentFragment = $(document.createDocumentFragment());
      //let imgPattern = '?thumbnail=78&image';
      /* let action_tpl = '<ul class="actions">\n' +
        '<li><a href="#" class="delete sirv-delete-item tips" data-id="'+ id +'" data-tip="Delete image">Delete</a></li>\n' +
        '</ul >\n'; */

      $.each(data.items, function (index, item) {
        /* let caption = !!item.caption ? decodeURI(item.caption) : '';
        let liItem = '<li class="sirv-woo-gallery-item" data-order="' + item.order + '" data-type="' + item.type + '"data-provider="'+ item.provider +'" data-url-orig="' + item.url + '" data-view-id="'+ id +'" data-caption="'+ caption +'">\n' +
          '<div class="sirv-woo-gallery-item-img-wrap">\n' +
            '<img class="sirv-woo-gallery-item-img" src="' + item.url + imgPattern + '">\n' +
          '</div>\n' +
          '<input type="text" class="sirv-woo-gallery-item-caption" placeholder="Caption" value="'+ caption +'">'+
          action_tpl +
          '</li>\n'; */


        documentFragment.append(getGalleryLiItemHTML(id, item));
      });

      return documentFragment;
    }


    function getGalleryItemUrl(type, url, imgPattern){
      const modelPlaceholder = sirv_ajax_object.assets_path + '/model-plhldr.svg';
      let itemUrl = url;

      switch (type) {
        case 'model':
          itemUrl = modelPlaceholder;
          break;
        case 'online-video':
          itemUrl = url;
          break;

        default:
          itemUrl = url + imgPattern;
          break;
      }

      return itemUrl;
    }


    function getGalleryLiItemHTML(id, item, imgPattern="?thumbnail=78&image"){
      const noThumb = sirv_ajax_object.assets_path + "/no_thumb.png";

      const caption = !!item.caption ? decodeURI(item.caption) : '';
      const videoLink = !!item.videoLink ? item.videoLink : '';
      const videoId = !!item.videoID ? item.videoID : '';
      const url = !!item.url ? item.url : '';
      const imgUrl = !!url ? getGalleryItemUrl(item.type, url, imgPattern) : '';
      const deleteType = item.type == 'online-video' ? 'online video' : item.type;
      const itemId = !!item.itemId ? item.itemId : -1;
      const attachmentId = !!item.attachmentId ? item.attachmentId : -1;

      const liItem =
        `<li class="sirv-woo-gallery-item" data-order="${item.order}" data-type="${item.type}" data-provider="${item.provider}" data-url-orig="${url}" data-view-id="${id}" data-caption="${caption}" data-video-link="${videoLink}" data-video-id="${videoId}" data-item-id="${itemId}" data-attachment-id="${attachmentId}">
          <div class="sirv-woo-gallery-item-img-wrap">
            <img class="sirv-woo-gallery-item-img" src="${imgUrl || noThumb}">
          </div>
          <input type="text" class="sirv-woo-gallery-item-caption" placeholder="Caption" value="${caption}" />
          <ul class="actions">
            <li>
              <a href="#" class="delete sirv-delete-item tips" data-id="${id}" data-tip="Delete ${deleteType}">Delete</a>
            </li>
          </ul >
        </li>`;

        return liItem;
    }


    $('body').on('click', 'a.sirv-woo-add-online-videos', parseOnlineVideo);
    function parseOnlineVideo(e) {
      e.preventDefault();
      e.stopPropagation();

      let id = $(this).attr('data-id');

      let lines = $('#sirv-online-video-links_'+ id).val().replace(/\r\n/g, "\n").split(/\n/).filter(function(line){return !!line});
      if(!!lines){
        lines.forEach(function(link){
          let videoObj = getVideoObj(link);
          if(!isEmpty(videoObj)){
            renderOnlineVideo(id, videoObj);
            getVideoThumb(id, videoObj);
          }
        });

        $('#sirv-add-online-videos-container_' + id).hide();
        $('#sirv-online-video-links_'+ id).val('');
        $('#sirv_woo_gallery_container_' + id).on('click', 'a.sirv-woo-add-online-video', showOnlineVideosBlock);
      }
    }


    function renderOnlineVideo(id, data){
      let action_tpl = '<ul class="actions">\n' +
        '<li><a href="#" class="delete sirv-delete-item tips" data-id="'+ id +'" data-tip="Delete image">Delete</a></li>\n' +
        '</ul >\n';

      const $ul = $('#sirv-woo-images_' + id);
      //const order = $ul.length - 1;
      const videItem = {
        order: $ul.length - 1,
        type: "online-video",
        provider: data.provider,
        videoLink: data.link,
        videoID: data.videoID
      };

      /* $ul.append(
        '<li class="sirv-woo-gallery-item" data-order="' + order + '" data-type="online-video" data-provider="'+ data.provider +'" data-url-orig="" data-video-link="' + data.link +'" data-video-id="'+ data.videoID +'">\n' +
          '<span class="dashicons dashicons-format-video sirv-online-video-placeholder"></span>\n'+
          '<input type="text" class="sirv-woo-gallery-item-caption" placeholder="Caption" value="">'+
            action_tpl +
        '</li>\n'
      ); */
      $ul.append(getGalleryLiItemHTML(id, videItem));
    }


    function imageSortable(id=''){
      if(!!id){
        sortableBlock(id);
      }else{
        $.each($('.sirv-woo-images'), function(){
          let id = $(this).attr('data-id');
          sortableBlock(id);
        });
      }
    }


    function sortableBlock(id){
      $('#sirv-woo-images_' + id).sortable({
        cursor: 'move',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        forceHelperSize: false,
        helper: 'clone',
        opacity: 0.65,
        placeholder: "sirv-sortable-placeholder",
        stop: function (event, ui) {
          reCalcGalleryData(id);
        }
      });
      $('#sirv-woo-images_' + id).disableSelection();
    }


    function reCalcGalleryData(id){
      let items = [];
      $('#sirv-woo-images_'+id+' .sirv-woo-gallery-item').each(function (index) {
        $(this).attr('data-order', index);
        let item = {
          url  : $(this).attr('data-url-orig'),
          type : $(this).attr('data-type'),
          provider: $(this).attr('data-provider'),
          order: index,
          viewId: id,
          caption: encodeURI($(this).attr('data-caption'))
        }
        if(item.type == "online-video"){
          item.videoID = $(this).attr('data-video-id');
          item.videoLink = $(this).attr('data-video-link');
        }

        item.itemId = $(this).attr('data-item-id') || -1;
        item.attachmentId = $(this).attr('data-attachment-id') || -1;

        items.push(item);

      });

      let data = getStorageData(id);
      data.items = items;
      setStorageData(id, data);
      manageDeleteAllButtonState(id);

      if ($('#sirv-woo-gallery_' + id).hasClass('sirv-variation-container')) variationChanged($('#sirv-woo-gallery_' + id));
    }


    $('body').on('click', 'a.sirv-delete-item', deleteImage);
    function deleteImage(e){
      e.preventDefault();
      e.stopPropagation();

      let id = $(this).attr('data-id');

      $(this).closest('li.sirv-woo-gallery-item').remove();
      reCalcGalleryData(id);
    }


    $('.sirv-woo-delete-all').on('click', deleteAllImages);
    function deleteAllImages(){
      let id = $(this).attr('data-id');

      $(this).parent().siblings('.sirv-woo-images').empty();

      reCalcGalleryData(id);
    }


    function getStorageData(id, selector='#sirv_woo_gallery_data_'){
      return JSON.parse($(selector + id).val());
    }


    function setStorageData(id, data, selector='#sirv_woo_gallery_data_'){
      $(selector + id).val(JSON.stringify(data));
    }


    $('body').on('click', 'a.sirv-woo-add-online-video', showOnlineVideosBlock);
    function showOnlineVideosBlock(e) {
      e.preventDefault();
      e.stopPropagation();

      let id = $(this).attr('data-id');

      $('#sirv-add-online-videos-container_'+ id).slideDown();

      $('#sirv_woo_gallery_container_'+ id).off('click', 'a.sirv-woo-add-online-video', showOnlineVideosBlock);
    }


    $('body').on('click', 'a.sirv-woo-cancel-add-online-videos', cancelShowOnlineVideosBlock);
    function cancelShowOnlineVideosBlock(e) {
      e.preventDefault();
      e.stopPropagation();

      let id = $(this).attr('data-id');

      $('#sirv-add-online-videos-container_' + id).slideUp();
      $('#sirv-online-video-links_'+ id).val('');

      $('#sirv_woo_gallery_container_'+ id).on('click', 'a.sirv-woo-add-online-video', showOnlineVideosBlock);
    }


    function manageDeleteAllButtonState(id) {
      let $items = $('#sirv-woo-images_'+ id +' .sirv-woo-gallery-item');
      if($items.length > 5){
        $('#sirv-delete-all-images-container_'+id).show();
        $('#sirv-delete-all-images-container_' + id +' .sirv-woo-delete-all').on('click', deleteAllImages);
      }else{
        $('#sirv-delete-all-images-container_' + id).hide();
      }
    }


    function getVideoThumb(id, videoObj){
      switch (videoObj.provider) {
        case 'youtube':
          getYoutubeThumb(id, videoObj);
          break;
        case 'vimeo':
          getVimeoThumb(id, videoObj);
          break;

        default:
          break;
      }
    }


    function getVideoObj(url){
      let videoPatterns = [
        { provider: 'youtube', pattern: new RegExp('youtube\\.com.*(\\?v=|\\/embed\\/)(.{11})')},
        { provider: 'vimeo', pattern: new RegExp('vimeo\\.com.*?\\/(.*\\/)*(\\d*)')},
      ];

      let videoObj = {};

      videoPatterns.forEach(function(item){
        if(!!url.match(item.pattern)){
          videoObj.provider = item.provider;
          videoObj.videoID = url.match(item.pattern).pop();
          videoObj.link = url;
        }
      });

      return videoObj;
    }


    function getYoutubeThumb(id, data){
      let thumb =  'https://img.youtube.com/vi/' + data.videoID + '/0.jpg';
      setVideoThumb(id, data, thumb);
    }


    function getVimeoThumb(id, data){
      /* jQuery.getJSON('https://www.vimeo.com/api/v2/video/' + data.videoID + '.json?callback=?', function(response) {*/

      jQuery.getJSON('https://vimeo.com/api/oembed.json?url=https%3A//vimeo.com/' + data.videoID, {format: "json"})
      .done(function(response) {
        let thumb = response.thumbnail_url;
        //let thumb_with_play_button = response.thumbnail_url_with_play_button;

        setVideoThumb(id, data, thumb);
      })
      .fail(function(){
        setVideoThumb(id, data, null);
      });
    }


    function setVideoThumb(id, videoData, thumb){
      let $videoItem = $('#sirv-woo-gallery_'+ id +' .sirv-woo-gallery-item[data-video-id='+videoData.videoID+']');

      if(!!$videoItem){
        if(!!thumb){
          $videoItem.attr('data-url-orig', thumb);
          //$('.sirv-online-video-placeholder', $videoItem).remove();

          //$videoItem.append('<img class="sirv-woo-gallery-item-img" src="' + thumb + '">\n');
          $videoItem.find("img.sirv-woo-gallery-item-img").attr('src', thumb);
        }

        reCalcGalleryData(id);
      }
    }


    function isEmpty(obj){
      if(typeof obj =='object') return !!!Object.keys(obj).length;

      return !!!obj;
    }

    $('#woocommerce-product-data').on('woocommerce_variations_loaded', function () {
      updateVariation();
    });

    $('#variable_product_options').on('woocommerce_variations_added', function () {
      updateVariation();
    });


    function updateVariation(){
      $('.woocommerce_variation').each(function () {
        let $optionsBlock = $(this).find('.options:first');
        let $galleryBlock = $(this).find('.sirv-woo-gallery-container');

        let id = $galleryBlock.attr('data-id');

        $galleryBlock.insertBefore($optionsBlock);

        imageSortable(id);
      });
    }

    function variationChanged($el){
      $($el).closest('.woocommerce_variation').addClass('variation-needs-update');
      $('button.cancel-variation-changes, button.save-variation-changes').removeAttr('disabled');
      $('#variable_product_options').trigger('woocommerce_variations_input_changed');
    }


    $('body').on('input', '.sirv-woo-gallery-item-caption', inputCaption);
    function inputCaption(){
      $(this).closest('.sirv-woo-gallery-item').attr('data-caption', $(this).val());
        let id = $(this).closest('.sirv-woo-gallery-item').attr('data-view-id');
        reCalcGalleryData(id);
        imageSortable(id);
    }

    //---------------initialization---------------------
    imageSortable();

  }); //onready end
});
