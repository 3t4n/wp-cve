jQuery(function($){

    window['sirvIsChangedShortcode'] = false;

    let itemsPerPage = localStorage.getItem("shItemsPerPage") || 30;

    window['sirvGetShortcodesData'] = function(offset, itemsOnPage, doneFunc){
        $.post(ajaxurl, {
        action: 'sirv_get_shortcodes_data',
        shortcodes_page: parseInt(offset),
        itemsPerPage: parseInt(itemsOnPage),
        beforeSend: function(){
            $('.loading-ajax').show();
        },
        }).done(function(data){
            //debug
            //console.log(data);
            itemsPerPage = itemsOnPage;

            data = JSON.parse(data);
            doneFunc(data, offset);
            if(window.isSirvGutenberg && window.isSirvGutenberg == true){
                checkAndRestoreSelection();
            }

            $('.loading-ajax').hide();

        }).fail(function(qXHR, status, error){
            console.log(status, error);
            $('.loading-ajax').hide();
        });
    }


    function checkAndRestoreSelection(){
        let data = $('.sirv-pagination').attr('data-selected');

        if(data !== '' && isJsonString(data)){
            shObj = JSON.parse(data);
            if(shObj.id){
                $('.sirv-choose-shortcode[data-sh-id='+ shObj.id +']').addClass('sirv-selected-shortcode');
            }
        }

    }


    function generateShortcodeByType(data, type){
        let $template = '';
        let imageSrc = '';

        if(type == 'tableRow'){
            imageSrc = data.images.length > 0 ? data['images'][0]['url'] : '';
            let itemType = data["images"][0]["type"];
            let curImgPlaceholder = getPlaceholder(itemType);
            let timestamp = data.timestamp != null ? data.timestamp : 'no data';
            let shName = '';

            if(!!data.shortcode_options && !!data.shortcode_options.global_options){
                shName = data.shortcode_options.global_options.shortcodeName || '';
                shName = escapeHTML(shName);
            }

            $template = $('<tr id="'+ data.id +'">' +
                '<td class="t-cb"><input type="checkbox"></td>'+
                '<td class="t-id">'+ data.id +'</td>'+
                '<td class="t-pv"><img class="t-pv-img" data-original="'+ imageSrc +'" src="'+ curImgPlaceholder +'"></td>'+
                '<td class="t-sh-name">'+ shName +'</td>' +
                '<td class="t-name">'+
                    '<b>'+ getShortcodeType(data) +'</b><br>'+
                    '<a class="sirv-edit-shortcode" href="#" data-shortcode-id="'+ data.id +'">Edit</a> | '+
                    '<a href="#" class="sirv-duplicate-shortcode" data-shortcode-id="'+ data.id +'" title="Duplicate shortcode">Duplicate shortcode</a>'+
                '</td>'+
                '<td class="t-count">'+ data.images.length +'</td>'+
                '<td class="t-date">'+ timestamp +'</td>'+
                '<td class="t-sc">[sirv-gallery id='+ data.id +']</td>'+
            '</tr>');
        } else if(type == 'chooseView'){
            let imagesCount = data.images.length > 0 ? data.images.length <= 6 ? data.images.length : 6 : 0;
            let imagesTemplate = '';
            for(let i=0; i < imagesCount; i++){
                imageSrc = data['images'][i]['url'];
                let itemType = data['images'][i]['type'];
                let curImgPlaceholder = getPlaceholder(itemType);

                imagesTemplate += `<img class="sirv-sh-view" data-type="${itemType}" data-original="${imageSrc}" src="${curImgPlaceholder}">`;
            }

            $template = $('<div class="sirv-choose-shortcode" data-sh-id="'+ data.id +'" data-sh-type="'+ getShortcodeType(data) +'" data-sh-count="'+ data.images.length +'"><div class="sirv-choose-shortcode-title">'+ getShortcodeType(data) +'</div>'+ imagesTemplate +'</div>');
        }

        return $template;
    }


    function getPlaceholder(itemType){
        const imPlaceholder = sirv_ajax_object.assets_path + '/img-plhldr.svg';
        const spinPlaceholder = sirv_ajax_object.assets_path + '/spin-plhldr.svg';
        const videoPlaceholder = sirv_ajax_object.assets_path + '/video-plhldr.svg';
        const modelPlaceholder = sirv_ajax_object.assets_path + '/model-plhldr.svg';

        let placeholder = imPlaceholder;

        switch (itemType) {
            case 'image':
                break;
            case 'spin':
                placeholder = spinPlaceholder;
                break;
            case 'video':
                placeholder = videoPlaceholder;
                break;
            case 'model':
                placeholder = modelPlaceholder;
            default:
                break;
        }
        return placeholder;
    }

    function getItemType(itemUrl){
        const videoRegExp = /(mp4|mpg|mpeg|mov|qt|webm|avi|mp2|mpe|mpv|ogg|m4p|m4v|wmv|flw|swf|avchd)/gi;
        const imgRegExp = /(jpg|jpeg|png|gif|svg|webp|bmp)/gi;

        const modelRegExp = /(glb|gltf)/gi;

        let type = '';
        let itemExt = getFileNameExt(itemUrl);

        if (itemExt == 'spin') {
            type = 'spin';
        } else if (itemExt.match(imgRegExp)) {
            type = 'image';
        } else if (itemExt.match(videoRegExp)) {
            type = 'video';
        } else if(itemExt.match(modelRegExp)){
            type = 'model';
        }

        return type;
    }

    function escapeHTML(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }


    window['renderShortcodesByType'] = function(data, curPage){
        let type = window.sirvShType !== undefined ? window.sirvShType : '';
        let appendSelector = window.sirvShSelector !== undefined ? window.sirvShSelector : '';
        let imageSelector = window.sirvImgSelector !== undefined ? window.sirvImgSelector : '';

        let count = Math.ceil(parseInt(data.count)/itemsPerPage);
        let shortcodes = data.shortcodes;
        let documentFragment = $(document.createDocumentFragment());

        if(curPage > count) curPage = count;

        if(shortcodes.length > 0){
            for(let i=0; i < shortcodes.length; i++){
                let item = shortcodes[i];
                let genItem = generateShortcodeByType(item, type);
                documentFragment.append(genItem);
                let imgElem = $(imageSelector, genItem);
                loadImage(imgElem);
            }
            unBindEvents();
            $('.sirv-select-all').prop('checked', false);
            $(appendSelector).empty();
            $(appendSelector).append(documentFragment);
        }else{
            if(appendSelector == '.sirv-choose-wrapper'){
                $(appendSelector).empty();
                $(appendSelector).append('<div class="sirv-no-shortcodes sirv-empty-dir">No shortcodes found. <a href="'+ 'admin.php?page=' + sirv_ajax_object.plugin_subdir_path + 'shortcodes-view.php' +'" target="_blanc">Create shortcode</a></div>');
            }
        }

        renderPagination('.sirv-pagination', count, curPage);
        bindEvents();
    }


    function bindEvents(){
        $('.sirv-pagination-button').on('click', goToPage);
        $('.sirv-edit-shortcode').on('click', editShortcode);
        $('.sirv-duplicate-shortcode').on('click', duplicateShortcode);
        $('.sirv-choose-shortcode').on('click', selectShortcode);
        $('.sirv-insert-shortcode-button').on('click', addShortcodesToPost);
    }


    function unBindEvents(){
        $('.sirv-pagination-button').off('click', goToPage);
        $('.sirv-edit-shortcode').off('click', editShortcode);
        $('.sirv-duplicate-shortcode').off('click', duplicateShortcode);
        $('.sirv-choose-shortcode').off('click', selectShortcode);
        $('.sirv-insert-shortcode-button').off('click', addShortcodesToPost);
    }


    function goToPage(event){
        let page = parseInt(event.currentTarget.getAttribute('data-page'));
        sirvGetShortcodesData(page, itemsPerPage, renderShortcodesByType);
    }


    function editShortcode(event){
        event.preventDefault();
        let id = parseInt(event.currentTarget.getAttribute('data-shortcode-id'));
        window['bPopup'] = $('.sirv-modal').bPopup({
                        position: ['auto', 'auto'],
                        loadUrl: modal_object.media_add_url,
                        onClose: function(){
                            window.isShortcodesPage = false;
                            if(sirvIsChangedShortcode === true){
                                let curPage = parseInt($('.sirv-cur-page').attr('data-page'));
                                sirvGetShortcodesData(curPage, itemsPerPage, renderShortcodesByType);
                                window.sirvIsChangedShortcode = false;
                            }
                        },
                        loadCallback: function(){
                            window.isShortcodesPage = true;
                            $('.insert').addClass('edit-gallery');
                            $('.insert').attr('data-shortcode-id', id);
                            window.sirvEditGallery(id);
                        }
                    });
    }


    function addShortcode(event){
        event.preventDefault();

        window['bPopup'] = $('.sirv-modal').bPopup({
            position: ['auto', 'auto'],
            loadUrl: modal_object.media_add_url,
            onClose: function(){
                window.isShortcodesPage = false;
                window.shGalleryFlag = true;
                if(sirvIsChangedShortcode === true){
                    window.sirvIsChangedShortcode = false;
                }

                let curPage = parseInt($('.sirv-cur-page').attr('data-page')) || 1;
                sirvGetShortcodesData(curPage, itemsPerPage, renderShortcodesByType);

            },
            loadCallback: function(){
                $('.loading-ajax').show();
                $('.insert>span').text('Save shortcode');
                window.isShortcodesPage = true;
                getContentFromSirv(window.sirvGetPath());
            }
        });

    }


    function duplicateShortcode(event){
        event.preventDefault();

        let id = parseInt(event.currentTarget.getAttribute('data-shortcode-id'));

        $.post(ajaxurl, {
        action: 'sirv_duplicate_shortcodes_data',
        shortcode_id: id,
        beforeSend: function(){
            $('.loading-ajax').show();
        }
        }).done(function(response){
            //debug
            //console.log(response);

            let curPage = parseInt($('.sirv-cur-page').attr('data-page'));
            sirvGetShortcodesData(curPage, itemsPerPage, renderShortcodesByType);
            $('.loading-ajax').hide();

        }).fail(function(qXHR, status, error){
            console.log(status, error);
            $('.loading-ajax').hide();
        });



    }


    function deleteShortcodes(){
        let checkedIds = [];
        $.each($('.sirv-shortcodes-data input[type=checkbox]:checked').closest('tr'), function(index, item){
            checkedIds.push(parseInt($(item).attr('id')));
        });

        if (checkedIds.length == 0) return false;

        $.post(ajaxurl, {
        action: 'sirv_delete_shortcodes',
        shortcode_ids: JSON.stringify(checkedIds),
        beforeSend: function(){
            $('.loading-ajax').show();
        }
        }).done(function(response){
            //debug
            //console.log(response);

            let curPage = parseInt($('.sirv-cur-page').attr('data-page')) || 1;
            $('.sirv-shortcodes-data').empty();
            sirvGetShortcodesData(curPage, itemsPerPage, renderShortcodesByType);
            $('.loading-ajax').hide();

        }).fail(function(qXHR, status, error){
            console.log(status, error);
            $('.loading-ajax').hide();
        });


    }


    function selectAllShortcodes(){
        let isChecked = $(this).prop('checked');
        if(isChecked){
            $('.sirv-shortcodes-data input[type=checkbox]').prop('checked', true);
        }else{
            $('.sirv-shortcodes-data input[type=checkbox]').prop('checked', false);
        }
    }


    function addShortcodesToPost(){

        let selShorcodes = $('.sirv-selected-shortcode');
        let html = '';
        let ids = [];

        $.each(selShorcodes, function(index, element){
            html += '[sirv-gallery id='+ $(element).attr('data-sh-id') +']&nbsp;';
            ids.push($(element).attr('data-sh-id'));
        });

        if(window.isSirvGutenberg && window.isSirvGutenberg == true){
                let selectedShData = $('.sirv-pagination').attr('data-selected');
                generateGutenbergData(selectedShData);
            }else if(window.isSirvElementor && window.isSirvElementor == true){
                let selectedShData = $('.sirv-pagination').attr('data-selected');
                let ifr = $('iframe#elementor-preview-iframe')[0];

                window.updateElementorSirvControl(toElementorJSONstr(selectedShData), false);
                window.isSirvElementor = false;

                setTimeout(function(){runEvent(ifr.contentWindow.document, 'updateSh');}, 1000);
            }else{
                if(typeof window.parent.send_to_editor === 'function'){
                    //some strange issue with firefox. If return empty string, than shortcode html block will broken. So return string only if not empty.
                    if(html != '') window.parent.send_to_editor(html);

                    //hack to show visualisation of shortcode or responsive images
                    window.parent.switchEditors.go("content", "html");
                    window.parent.switchEditors.go("content", "tmce");
                }
            }
        bPopup.close();

    }

    window.runEvent = function(selector, eventName){
        let event = new Event(eventName);


        $(selector).each(function(index, element){
            $(this)[index].dispatchEvent(event);
        });
    }


    function toElementorJSONstr(jsonStr){
        let elementorJsonStr = '';
        let elementorObj = {};
        let tmpObj = {shortcode: {}, images: {}};

        if(jsonStr !== '' && isJsonString(jsonStr)){
            elementorObj = JSON.parse(jsonStr);
            tmpObj.shortcode.id = elementorObj.id;
            tmpObj.shortcode.count = elementorObj.count;
            tmpObj.shortcode.type = elementorObj.shType;
            tmpObj.shortcode.images = elementorObj.jsonImages;

            elementorJsonStr = JSON.stringify(tmpObj);
        }

        return elementorJsonStr;
    }


    function generateGutenbergData(data){
        let shObj = {};

        if(data !== '' && isJsonString(data)){
            shObj = JSON.parse(data);

            window.sirvShObj = {
                sirvId: shObj.id,
                sirvType: shObj.shType,
                sirvCount: shObj.count,
                sirvImages: shObj.images,
                sirvImagesJson: JSON.stringify(shObj.jsonImages),
            }
        }else{
            window.sirvShObj = {};
        }
    }


    function isJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
        }


    function selectShortcode(){
        let selectedElem = $(this);
        let selectedData = getSelectedData(selectedElem);

        if(selectedElem.hasClass('sirv-selected-shortcode')){
            selectedElem.removeClass('sirv-selected-shortcode');
            $('.sirv-pagination').attr('data-selected', '');
        }else{

            if( (window.isSirvGutenberg && window.isSirvGutenberg == true) || (window.isSirvElementor && window.isSirvElementor == true) ){
                $.each($('.sirv-selected-shortcode'), function(index, elem){
                    $(elem).removeClass('sirv-selected-shortcode');
                });
                $('.sirv-pagination').attr('data-selected', '');
            }

            selectedElem.addClass('sirv-selected-shortcode');
            $('.sirv-pagination').attr('data-selected', selectedData);
        }


        let isSelectedShortcode = !!$('.sirv-selected-shortcode').length;

        if(isSelectedShortcode){
            $('.sirv-insert-shortcode-wrapper').show();
            $('.sirv-insert-shortcode-wrapper').css('display', 'flex');
        }else $('.sirv-insert-shortcode-wrapper').hide();
    }


    function getSelectedData(elem){
        let id = $(elem).attr('data-sh-id');
        let count = $(elem).attr('data-sh-count');
        let shType = $(elem).attr('data-sh-type');

        let images = $('.sirv-sh-view', elem);

        let imgParams = '?thumbnail=120&image';
        let tmpImages = [];
        let tmpImagesJson = [];
        let tmpCount = count > 4 ? 4 : count;

        for(let i = 0; i < tmpCount; i++){
            let itemUrl = $(images[i]).attr("data-original") + imgParams;
            jsonItem = $(images[i]).attr('data-type') == 'model' ? getPlaceholder('model') : itemUrl;

            tmpImages.push({ 'src': itemUrl});
            tmpImagesJson.push(jsonItem);
        }

        return JSON.stringify({
            id: id,
            count: count,
            shType: shType,
            images: tmpImages,
            jsonImages: tmpImagesJson
        });
    }


    function calcPagination(pagesCount, curPage){
        let pagData = [];

        for (let i = curPage, limit = 3; i >= 1 && limit > 0; i--, limit--) {
            pagData.unshift(i);
        }
        if (pagesCount - (pagesCount - curPage) > 3) pagData.unshift(1);

        for (let i = curPage + 1, limit = 2; i <= pagesCount && limit > 0; i++, limit--) {
            pagData.push(i);
        }
        if (pagesCount - curPage >= 3) pagData.push(pagesCount);


        return pagData;
    }


    function renderPagination(selector, pagesCount, curPage) {
        let pagData = calcPagination(pagesCount, curPage);
        let documentFragment = $(document.createDocumentFragment());
        let previousPageNum = 1;
        for(let i=0; i<pagData.length; i++){
            let isDisabled = pagData[i] == curPage ? 'disabled': '';
            let eventClass = pagData[i] == curPage ? "sirv-cur-page" : "sirv-pagination-button";
            let threeDots = pagData[i] - previousPageNum > 1 && (i == 1 || i == pagData.length - 1) ? '<b class="three-dots"> ... </b>' : "";
            previousPageNum = pagData[i];
            let item = $(threeDots + '<button class="'+ eventClass +' button button-primary" data-page="' + pagData[i] + '" '+ isDisabled +'>' + pagData[i] + '</button>');
            documentFragment.append(item);
        }

        $(selector).empty();
        $(selector).append(documentFragment);
    }


    function getItemParams(itemUrl){
        let imgParams = '?w=70&h=70&canvas.width=70';
        let spinParams ='?image&w=70&h=70&canvas.width=70&canvas.height=70&scale.option=fit';
        let videoParams = '?image&thumbnail=70';
        let itemParams = '';

        switch (getItemType(itemUrl)) {
            case 'image':
                itemParams = imgParams;
                break;
            case 'spin':
                itemParams = spinParams;
                break;
            case 'video':
                itemParams = videoParams;
                break;
            default:
                itemParams = spinParams;
                break;
        }
        return itemParams;
    }


    function loadImage(elem){
        let attemptsToLoadImg = 2;
        let $itemObj = $(elem);
        //let src = $itemObj.attr('data-original');
        //let itemParams = getItemParams(src);
        //let newImage = new Image();

        function load(imgElem, newImage, src){
            newImage.onload = function(){
                imgElem.attr('src', newImage.src);
            }

            newImage.src = src;

            newImage.onerror = function(){
                if(attemptsToLoadImg > 0 ){
                    setTimeout(function () { load(elem, newImage, src + getItemParams(src));}, 2000);
                    attemptsToLoadImg --;
                }
            }
        }

        for(let i=0; i<$itemObj.length; i++){
            let src = $($itemObj[i]).attr('data-original');
            let newImg = new Image();
            let itemParams = getItemParams(src);
            load($($itemObj[i]), newImg, src + itemParams);
        }
    }


    window['getShortcodeType'] = function(data){
        let isSingleImage = data.images.length == 1 ? true : false;

        if (!!data.shortcode_options.global_options && !!data.shortcode_options.global_options.sirvGalleryType) {
            let type = data.shortcode_options.global_options.sirvGalleryType;
            let gType = type;
            if(type == 'image' || type == 'gallery'){
                if(data.use_as_gallery){
                    if(data.use_sirv_zoom) {
                        gType = isSingleImage == true ? 'Zoom image' : 'Zoom gallery'
                    }else{
                        gType = 'Image gallery';
                    }
                }
            }
            return gType.charAt(0).toUpperCase() + gType.slice(1);
        }

        let shType = '';
        let isSpin = false;
        let isVideo = false;
        let vPattern = /\.(mp4|mpg|mpeg|mov|qt|webm|avi|mp2|mpe|mpv|ogg|m4p|m4v|wmv|flw|swf|avchd)/;
        let vFormats = ['mp4', 'mpg', 'mpeg', 'mov', 'qt', 'webm', 'avi', 'mp2', 'mpe', 'mpv', 'ogg', 'm4p', 'm4v', 'wmv', 'flw', 'swf', 'avchd'];

        if (data.images.length == 1){

            if(getFileNameExt(data['images'][0]['url']) == 'spin'){
                shType = 'Spin';
                isSpin = true;
            }
            if (vFormats.indexOf(getFileNameExt(data['images'][0]['url'])) != -1){
                shType = 'Video';
                isVideo = true;
            }

        }

        if((!isSpin || !isVideo) && String(data.use_as_gallery) == 'true' && String(data.use_sirv_zoom) == 'false') shType = 'Image gallery';
        if((!isSpin || !isVideo) && String(data.use_as_gallery) == 'true' && String(data.use_sirv_zoom) == 'true') shType = isSingleImage == true ? 'Zoom image' : 'Zoom gallery';

        return shType;

    }


    function getFileNameExt(filename){

        if(filename) return filename.substr((~-filename.lastIndexOf(".") >>> 0) + 2);

        return '';
    }


    function moveAjaxOverlay(ajaxSelector, prependToSelector){
        let $ajaxOverlay = $(ajaxSelector);
        $(ajaxSelector).remove();
        $(prependToSelector).prepend($ajaxOverlay);
    }


    function setItemsOnPage(){
        const itemsOnPage = parseInt($(this).attr("data-page-items"));
        localStorage.setItem("shItemsPerPage", itemsOnPage);

        sirvGetShortcodesData(1, itemsOnPage, renderShortcodesByType);

        manageItemsOnPageButtonState(itemsOnPage);
    }


    function manageItemsOnPageButtonState(itemsPerPage){
        $('.sirv-shp-results-per-page').each(function(){

            if(parseInt($(this).attr("data-page-items")) == itemsPerPage) $(this).prop('disabled', true);
            else $(this).prop("disabled", false);
        });
    }


        //-----------------------initialization-----------------------
    $(document).ready(function(){
            moveAjaxOverlay('.loading-ajax', '#wpcontent');
            //manageItemsOnPageButtonState(itemsPerPage);
            //getShortcodesData(1);
            //sirvGetShortcodesData(1);
            window.sirvImgSelector = jQuery('.sirv-list-container').attr('data-image-selector');
            window.sirvShType = $('.sirv-list-container').attr('data-sh-type');
            window.sirvShSelector = $('.sirv-list-container').attr('data-sh-selector');

            manageItemsOnPageButtonState(itemsPerPage);
            sirvGetShortcodesData(1, itemsPerPage, renderShortcodesByType);


            $('.sirv-add-shortcode').on('click', addShortcode);
            $('.sirv-delete-selected').on('click', deleteShortcodes);
            $('.sirv-select-all').on('click', selectAllShortcodes);
            $('.sirv-shp-results-per-page').on('click', setItemsOnPage);
        //}

    }); // dom ready end

}); //closure end
