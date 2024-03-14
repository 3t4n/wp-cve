jQuery(function($){

    $(document).ready(function(){

        /*-----------------global variables---------------*/
        let contentData = {};
        let scrollStack = [];
        let prev = -1;
        let maxFileSize;
        let maxFilesCount;
        let sirvFileSizeLimit;
        let profileTimer;
        let emptyFolder = false;
        let noResults = false;
        let searchFrom = 0;
        let scrollSegmentLen = 100;
        let isInDirSearch = false;
        let files = [];
        let realRequestSize = 0;
        //let imgGallery = false;
        window.shGalleryFlag = true;
        window.sirvViewerPath = '/';
        /*-------------global variables END---------------*/

        //code for drag area
        function onDrugEnterV(e) {
            e.stopPropagation();
            e.preventDefault();
            $('.sirv-drop-wrapper').show();
        }


        function onDrugEnterH(e) {
            e.stopPropagation();
            e.preventDefault();
            $('.sirv-drop-wrapper').show();
        }


        function onDrugOverH(e) {
            e.stopPropagation();
            e.preventDefault();
            $('.sirv-drop-wrapper').hide();
        }


        function onDropH(e) {
            $('.sirv-drop-wrapper').hide();
            e.stopPropagation();
            e.preventDefault();
            //let files;

            /* if (e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.items) {
                const items = e.originalEvent.dataTransfer.items;
                console.log('items');
                console.log(items);

                //files = prepareFiles(items);
                prepareFiles(items);
                //console.log(files);
            }else{
                files = e.originalEvent.dataTransfer.files;
            }

            //console.log(files);

            modernUploadImages(files); */
            /* files = e.originalEvent.dataTransfer.files;
            console.log(files); */

            const filesPromise = getDroppedOrSelectedFiles(e.originalEvent);
            filesPromise.then(files =>{
                modernUploadImages(files);
            });

        }


        function getDroppedOrSelectedFiles(event) {
            const dataTransfer = event.dataTransfer;
            if (dataTransfer && dataTransfer.items) {
                return getDataTransferFiles(dataTransfer).then((fileList) => {
                    return Promise.resolve(fileList);
                });
            }

            const files = [];
            const dragDropFileList = dataTransfer && dataTransfer.files;
            const inputFieldFileList = event.target && event.target.files;
            const fileList = dragDropFileList || inputFieldFileList || [];
            // convert the FileList to a simple array of File objects
            for (let i = 0; i < fileList.length; i++) {
                files.push(packageFile(fileList[i]));
            }
            return Promise.resolve(files);
        }


        const DEFAULT_FILES_TO_IGNORE = [
          ".DS_Store", // OSX indexing file
          "Thumbs.db", // Windows indexing file
        ];

        // map of common (mostly media types) mime types to use when the browser does not supply the mime type
        const EXTENSION_TO_MIME_TYPE_MAP = {
            avi: "video/avi",
            gif: "image/gif",
            ico: "image/x-icon",
            jpeg: "image/jpeg",
            jpg: "image/jpeg",
            mkv: "video/x-matroska",
            mov: "video/quicktime",
            mp4: "video/mp4",
            pdf: "application/pdf",
            png: "image/png",
            zip: "application/zip",
        };


        function shouldIgnoreFile(file) {
            return DEFAULT_FILES_TO_IGNORE.indexOf(file.name) >= 0;
        }


        function copyString(aString) {
            return ` ${aString}`.slice(1);
        }


        function traverseDirectory(entry) {
            const reader = entry.createReader();
            // Resolved when the entire directory is traversed
            return new Promise((resolveDirectory) => {
                const iterationAttempts = [];
                const errorHandler = () => {};
                function readEntries() {
                // According to the FileSystem API spec, readEntries() must be called until
                // it calls the callback with an empty array.
                reader.readEntries((batchEntries) => {
                    if (!batchEntries.length) {
                    // Done iterating this particular directory
                    resolveDirectory(Promise.all(iterationAttempts));
                    } else {
                    // Add a list of promises for each directory entry.  If the entry is itself
                    // a directory, then that promise won't resolve until it is fully traversed.
                    iterationAttempts.push(
                        Promise.all(
                        batchEntries.map((batchEntry) => {
                            if (batchEntry.isDirectory) {
                            return traverseDirectory(batchEntry);
                            }
                            return Promise.resolve(batchEntry);
                        })
                        )
                    );
                    // Try calling readEntries() again for the same dir, according to spec
                    readEntries();
                    }
                }, errorHandler);
                }
                // initial call to recursive entry reader function
                readEntries();
            });
        }

        // package the file in an object that includes the fullPath from the file entry
        // that would otherwise be lost
        function packageFile(file, entry) {
            //webkitRelativePath
            let fileTypeOverride = "";
            // handle some browsers sometimes missing mime types for dropped files
            const hasExtension = file.name && file.name.lastIndexOf(".") !== -1;
            if (hasExtension && !file.type) {
                const fileExtension = (file.name || "").split(".").pop();
                fileTypeOverride = EXTENSION_TO_MIME_TYPE_MAP[fileExtension];
            }
            return {
                fileObject: file, // provide access to the raw File object (required for uploading)
                //fullPath: entry ? copyString(entry.fullPath) : file.name,
                fullPath: getFilePath(file, entry),
                lastModified: file.lastModified,
                lastModifiedDate: file.lastModifiedDate,
                name: file.name,
                size: file.size,
                type: file.type ? file.type : fileTypeOverride,
                webkitRelativePath: file.webkitRelativePath,
            };
        }


        function getFilePath(file, entry){
            if(!!entry) return copyString(entry.fullPath);

            if(!!file.webkitRelativePath){
                return file.webkitRelativePath;
            }

            return file.name;
        }

        function getFile(entry) {
            return new Promise((resolve) => {
                entry.file((file) => {
                resolve(packageFile(file, entry));
                });
            });
            }

            function handleFilePromises(promises, fileList) {
            return Promise.all(promises).then((files) => {
                files.forEach((file) => {
                if (!shouldIgnoreFile(file)) {
                    fileList.push(file);
                }
                });
                return fileList;
            });
        }


        function getDataTransferFiles(dataTransfer) {
            const dataTransferFiles = [];
            const folderPromises = [];
            const filePromises = [];

            [].slice.call(dataTransfer.items).forEach((listItem) => {
                if (typeof listItem.webkitGetAsEntry === "function") {
                const entry = listItem.webkitGetAsEntry();

                if (entry) {
                    if (entry.isDirectory) {
                    folderPromises.push(traverseDirectory(entry));
                    } else {
                    filePromises.push(getFile(entry));
                    }
                }
                } else {
                dataTransferFiles.push(listItem);
                }
            });
            if (folderPromises.length) {
                const flatten = (array) =>
                array.reduce(
                    (a, b) => a.concat(Array.isArray(b) ? flatten(b) : b),
                    []
                );
                return Promise.all(folderPromises).then((fileEntries) => {
                const flattenedEntries = flatten(fileEntries);
                // collect async promises to convert each fileEntry into a File object
                flattenedEntries.forEach((fileEntry) => {
                    filePromises.push(getFile(fileEntry));
                });
                return handleFilePromises(filePromises, dataTransferFiles);
                });
            } else if (filePromises.length) {
                return handleFilePromises(filePromises, dataTransferFiles);
            }
            return Promise.resolve(dataTransferFiles);
        }


        function prepareFiles(items){
            let files = [];

            for (let i = 0; i < items.length; i++) {
                let item = items[i].webkitGetAsEntry();

                if(item){
                    //files = files.concat(parseFiles(item));
                    parseFiles(item, files);
                }
            }
            return files;
        }


        function parseFiles(item){
            if (item.isFile){
                    files.push(item);
                }else if(item.isDirectory){
                    let directoryReader = item.createReader();
                    directoryReader.readEntries((entries) => {
                        for (let i = 0; i < entries.length; i++) {
                            const entry = entries[i];
                            //console.log(entry);
                            files = files.concat(parseFiles(entry));
                        }
                    });
                }else{
                    //add popup message
                    console.error("Unsupported file system entry specified.");
                    return false;
                }

                console.log(files);
        }


        function bindDrugEvents(bind=true){
            if(bind){
                $("#drag-upload-area").on('dragenter dragover', onDrugEnterV);
                $('.sirv-drop-handler').on('dragenter dragover', onDrugEnterH);
                $('.sirv-drop-handler').on('dragleave', onDrugOverH);
                $('.sirv-drop-handler').on('drop', onDropH);
            }else{
                $("#drag-upload-area").off('dragenter dragover', onDrugEnterV);
                $('.sirv-drop-handler').off('dragenter dragover', onDrugEnterH);
                $('.sirv-drop-handler').off('dragleave', onDrugOverH);
                $('.sirv-drop-handler').off('drop', onDropH);
            }
        }


        $('.sirv-items-container').on('scroll', loadOnScroll);

        function loadOnScroll() {
            if ((this.scrollHeight - $(this).scrollTop() - $(this).offset().top - $(this).height()) <= 0) {
                unbindEvents();
                renderView();
                restoreSelections(false);
                bindEvents();
            }
        }


        function toolbarFixed() {
            let $toolbar = $('.toolbar-container');
            let $itemContainer = $('.sirv-items-container');

            if ($(this).scrollTop() > $toolbar.height()) {
                if(!$toolbar.hasClass('sub-toolbar--fixed')){
                    $toolbar.addClass('sub-toolbar--fixed');
                    $itemContainer.addClass('items-container-toolbar--fixed');
                    reCalcSearchMenuPosition();
                }
            } else {
                $toolbar.removeClass('sub-toolbar--fixed');
                $itemContainer.removeClass('items-container-toolbar--fixed');
                reCalcSearchMenuPosition();
            }

        }



        function searchLoadOnScroll(){
            if ((this.scrollHeight - $(this).scrollTop() - $(this).offset().top - $(this).height()) <= 0) {
                dir = isInDirSearch ? getCurrentDir() : '';
                globalSearch(searchFrom, true, dir);
                $('.sirv-items-container').off('scroll', searchLoadOnScroll);
            }
        }



        function manageContent(dt){
            let data = $.extend(true, {}, dt);
            let stack = [];
            let commonData = {
                sirv_url: data.sirv_url,
                current_dir : data.current_dir,
                continuation: data.continuation,
                fullImgLen: data.content.images.length
            };
            let dataObj = {
                orders: ['dirs', 'spins', 'images', 'videos', 'models'],
                dirs: {
                    len: data.content.dirs.length,
                    data: data.content.dirs,
                    func: renderDirs
                },
                spins: {
                    len: data.content.spins.length,
                    data: data.content.spins,
                    func: renderSpins
                },
                images: {
                    len: data.content.images.length,
                    data: data.content.images,
                    func: renderImages
                },
                videos: {
                    len: data.content.videos.length,
                    data: data.content.videos,
                    func: renderVideos
                },
                models: {
                    len: data.content.models.length,
                    data: data.content.models,
                    func: renderModels
                }
            };

            let dataLen = dataObj.dirs.len + dataObj.spins.len + dataObj.images.len + dataObj.videos.len + dataObj.models.len;
            let stackItemsCount = dataLen > 0 ? Math.ceil(dataLen / scrollSegmentLen) : 0;

            function getDataSplice(data, count) {
                return data.splice(0, count);
            }

            if(stackItemsCount > 0){
                for(let i = 1; i <= stackItemsCount; i++){
                    let count = scrollSegmentLen;
                    let item = [];
                    for(let cItem of dataObj.orders){
                        let cItemLen = dataObj[cItem]['len'];
                        if(cItemLen > 0){
                            let rest = count - cItemLen >= 0 ? 0 : Math.abs(count - cItemLen);
                            dataObj[cItem]['len'] = rest;
                            let dataCount = rest > 0 ? 100 : cItemLen;
                            item.push(stackFunc(commonData, getDataSplice(dataObj[cItem]['data'], dataCount), dataObj[cItem]['func']));

                            count = count - cItemLen > 0 ? count - cItemLen : 0;
                            if(count == 0) break;
                        }else continue;

                    }
                    stack.push(item);
                }
            }
            return stack;
        }


        function getStackItem(){
            let stackItem = [];

            if (scrollStack.length > 0) stackItem = scrollStack.shift();

            return stackItem;
        }


        function stackFunc(commonData, data, funcName){
            return function(){
                funcName(commonData, data);
            }
        }


        function isEmptyContentData(data){
            let dirsLen = data.content.dirs.length;
            let spinsLen = data.content.spins.length;
            let imagesLen = data.content.images.length;
            let videosLen = data.content.videos.length;
            let modelsLen = data.content.models.length;
            if((dirsLen + spinsLen + imagesLen + videosLen + modelsLen) == 0) return true;
            return false;
        }


        function hasHiddenFilesData(data){
            const audioLen = data.content.audio.length;
            const filesLen = data.content.files.length;
            return audioLen + filesLen > 0;
        }


        function renderEmptyFolder(){
            let html = '';
            if(noResults){
                html = $('.sirv-images').append('<div class="sirv-empty-dir"><span class="sirv-empty-folder-txt" style="font-size: 20px;">No results</span></div>');
            }else{
                html = $('<div class="sirv-empty-dir">' +
                            '<h2>This folder is empty</h2>'+
                            '<div><i class="fa fa-cloud-upload" aria-hidden="true" style="font-size: 56px;"></i></div>'+
                            '<span class="sirv-empty-folder-txt">Drag and drop images here to upload.</span>'+
                        '</div>');
            }

            hideItemsTitle();
            $('.sirv-empty-folder-container').addClass('sirv-latest-block');
            $('.sirv-empty-folder-container').append(html);
        }


        function getLatestUsesBlock(data){
            let block = '';
            let dataObj = {
                dirs: {len: data.content.dirs.length},
                spins: {len: data.content.spins.length},
                images: {len: data.content.images.length},
                videos: {len: data.content.videos.length},
                models: {len: data.content.models.length},
            };

            for(let item in dataObj){
                if(dataObj[item].len > 0){
                    block = item;
                }
            }
            return block;
        }

        function fixLatestUsesBlock(data){
            let block = getLatestUsesBlock(data);
            let selector = '';
            switch (block) {
                case 'dirs':
                    selector = '.sirv-dirs';
                    break;
                case 'spins':
                    selector = '.sirv-spins';
                    break;
                case 'images':
                    selector = '.sirv-images';
                    break;
                case 'videos':
                    selector = '.sirv-videos';
                    break;
                case 'models':
                    selector = '.sirv-models';
                    break;
                default:
                    break;
            }
            $('.sirv-latest-block').removeClass('sirv-latest-block');
            if(!!selector) $(selector).addClass('sirv-latest-block');
        }


        function renderView() {
            let renderItem = getStackItem();
            if(renderItem.length > 0){
                for(let i = 0; i < renderItem.length; i++){
                    renderItem[i]();
                }
            }else{
                if(emptyFolder) renderEmptyFolder();
                $('.sirv-items-container').off('scroll', loadOnScroll);
            }

        }


        function clearOnScrollLoadingParams(){
            $('.sirv-items-container').on('scroll', loadOnScroll);
            emptyFolder = false;
            hideItemsTitle();
        }

        function hideItemsTitle(){
            $('.sirv-dirs-title').hide();
            $('.sirv-spins-title').hide();
            $('.sirv-images-title').hide();
            $('.sirv-videos-title').hide();
            $('.sirv-models-title').hide();

        }

        function renderDirs(commonData, dirs){
            if (dirs.length > 0){
                $('.sirv-folders-count').html(dirs.length);
                $('.sirv-dirs-title').show();
                let documentFragment = $(document.createDocumentFragment());

                for (let i = 0; i < dirs.length; i++) {
                    let dir = dirs[i];
                    dir.dirname = commonData.current_dir;
                    let dt = getItemData('dir', commonData.sirv_url, dir, 'g_content');
                    let elemBlock = getItemBlock(dt);
                    documentFragment.append(elemBlock);
                }
                $('#dirs').append(documentFragment);
            } else{
                $('.sirv-folders-count').html(dirs.length);
                $('.sirv-dirs-title').hide();
            }
        }


        function formatDate(date, type='short'){
            let d = new Date(date);
            //let monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let monthNamesShort = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            let formatedDate = monthNamesShort[d.getMonth()] + ' ' + d.getUTCDate() + ', ' + d.getFullYear();
            if(type == 'long'){
                formatedDate += ' ' + d.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
            }

            return formatedDate;
        }


        function formatVideoDuration(duration) {
            if (!!duration) return new Date(1000 * duration).toISOString().substr(11, 8);

            return 'No data';
        }


        function renderImages(commonData, images){
            if (images.length > 0){
                $('.sirv-images-count').html(commonData.fullImgLen);
                $('.sirv-images-title').show();
                let documentFragment = $(document.createDocumentFragment());

                for (let i = 0; i< images.length; i++) {
                    let image = images[i];

                    image.dirname = commonData.current_dir;
                    let dt = getItemData('image', commonData.sirv_url, image, 'g_content');
                    let imgElemBlock = getItemBlock(dt);
                    documentFragment.append(imgElemBlock);
                    loadImage(imgElemBlock, getItemParams('image', 128));
                }
                $('#images').append(documentFragment);
            }
        }


        function renderSpins(commonData, spins) {
            if(spins.length > 0){
                $('.sirv-spins-count').html(spins.length);
                $('.sirv-spins-title').show();
                for (let i = 0; i < spins.length; i++) {
                    let spin = spins[i];


                    spin.dirname = commonData.current_dir;
                    let dt = getItemData('spin', commonData.sirv_url, spin, 'g_content');
                    let spinElemBlock = getItemBlock(dt);
                    spinElemBlock.appendTo('#spins');
                    loadImage(spinElemBlock, getItemParams('spin', 128));
                }
            }
        }

        function renderVideos(commonData, videos){
            if (videos.length > 0) {
                $('.sirv-videos-count').html(videos.length);
                $('.sirv-videos-title').show();
                for (let i = 0; i < videos.length; i++) {
                    let video = videos[i];

                    video.dirname = commonData.current_dir;
                    let dt = getItemData('video', commonData.sirv_url, video, 'g_content');

                    let videoElemBlock = getItemBlock(dt);
                    videoElemBlock.appendTo('#videos');
                    loadImage(videoElemBlock, getItemParams('video', 128));
                }
            }
        }


        function renderModels(commonData, models){
            if (models.length > 0) {
                $('.sirv-models-count').html(models.length);
                $('.sirv-models-title').show();
                for (let i = 0; i < models.length; i++) {
                    let model = models[i];

                    model.dirname = commonData.current_dir;
                    let dt = getItemData('model', commonData.sirv_url, model, 'g_content');

                    let modelElemBlock = getItemBlock(dt);
                    modelElemBlock.appendTo('#models');
                    //loadImage(modelElemBlock, getItemParams('model', 128));
                }
            }
        }


        function renderSearch(data){
            const emptyResult = "No files matching your criteria";

            if(data.total > 0){
                $('.sirv-search-message').hide();
                $('.sirv-search-title').show();
                let documentFragment = $(document.createDocumentFragment());
                $('.sirv-search-total-found').html(data.total);

                for (let hit of data.hits) {
                    let type = getSearchItemType(hit._source);
                    let dt = getItemData(type, data.sirv_url, hit._source, 's_content');
                    let block = getItemBlock(dt, 's_content');
                    documentFragment.append(block);
                    if (type !== 'dir') loadImage(block, getItemParams(type, 128));
                }

                $('#search-items').append(documentFragment);
            }else{
                if(!!data.error){
                    showSearchMsg(`Error: ${data.message}`);
                }else{
                    //empty results
                    $('.sirv-search-title').hide();
                    //$('.sirv-search-message').show();
                    showSearchMsg(emptyResult);
                }
            }
        }


        function showSearchMsg(msg){
            const $searchItem = $('.sirv-search-message');

            $searchItem.html(msg);
            $searchItem.show();
        }


        function getItemData(type, sirv_url, data, source){
            let dt = {};
            dt.type = type;
            dt.contentType = data.contentType || '';
            dt.mtime = data.mtime;
            dt.meta = data.meta;

            if (type !== 'dir') dt.size = getFormatedFileSize( data.size * 1 );

            if(source == 's_content'){
                dt.basename = data.basename;
                dt.dirname = data.dirname;
                dt.filename = data.filename;
                dt.imageUrl = encodeURI(sirv_url + data.filename);
                dt.fullImageUrl = 'https://' + dt.imageUrl;
            }else{
                dt.basename = data.filename;
                dt.dirname = data.dirname;
                dt.filename = data.dirname == '/' ? data.dirname + data.filename : data.dirname +'/'+ data.filename;
                dt.imageUrl = encodeURI(sirv_url + dt.filename);
                dt.fullImageUrl = 'https://' + dt.imageUrl;
            }

            return dt;
        }


        function getItemBlock(data, receiveContentType='g_content'){
            let bcImg = "width:128px; height:128px; background-image: url(" + getItemPlaceHolder(data.type) + "); background-position: 50% 50%; background-repeat: no-repeat; background-size: contain;";
            let selectionButton = (data.type != 'dir') ? '<div class="sirv-item-selection dashicons"></div>' : '';
            let menuButton = (data.type != 'dir') ? '<div class="sirv-item-menu-actions dashicons"></div>' : '';
            let titlePath = receiveContentType == 's_content' ? 'title="'+ data.filename +'" ' : '';
            let dir = data.dirname == '/' ? data.dirname : data.dirname + '/';
            let itemMeta = getItemMeta(data);
            let imgWidth = !!itemMeta.width ? ' data-width="' + itemMeta.width +'" ' : '';
            let imgHeight = !!itemMeta.height ? ' data-height="' + itemMeta.height +'" ' : '';

            let sirvItem = $(
                `<div class="sirv-item">
                    <div
                        class="sirv-item-body"
                        ${titlePath}
                        data-item-id="${md5('//'+ data.imageUrl)}"
                        data-item-type="${data.type}"
                        data-item-sirv-path="${encodeURIComponent(data.filename)}"
                        data-dir="${dir}"
                        data-item-title="${escapeHtml(data.basename)}"
                        data-content-type="${data.contentType}">
                            ${selectionButton}
                            ${menuButton}
                            <div class="sirv-item-icon" style="${bcImg}" data-item-url="${data.fullImageUrl}"></div>
                            <div class="sirv-item-desc">
                                <div class="sirv-item-name-container sirv-overflow-ellipsis sirv-no-select-text" title="${data.basename}">${data.basename}</div>
                                <div class="sirv-item-meta-container sirv-overflow-ellipsis sirv-no-select-text" title="${itemMeta.title}" ${imgWidth} ${imgHeight}>${itemMeta.main}</div>
                            </div>
                    </div>
                </div>`
            );

            return sirvItem;
        }


        function getItemMeta(data){
            let meta = {title: '', main: ''};

            let shortDate = formatDate(data.mtime);
            let longDate = formatDate(data.mtime, 'long');
            let size = !!data.size ? ' - '+ data.size : '';

            meta.title = longDate + size;

            switch (data.type) {
                case 'dir':
                    meta.main = shortDate;
                    break;
                case 'spin':
                    meta.main = shortDate;
                    break;
                case 'image':
                    if (!!data.meta.width && data.meta.height){
                        meta.main = data.meta.width + ' x ' + data.meta.height;
                        meta.width = data.meta.width;
                        meta.height = data.meta.height;
                    }else{
                        meta.main = "No data";
                    }
                    break;
                case 'video':
                    meta.main = formatVideoDuration(data.meta.duration);
                    break;

                default:
                    break;
            }
            return meta;
        }


        function getItemPlaceHolder(type){
            let placeholder = '';

            switch (type) {
                case 'dir':
                    placeholder = sirv_ajax_object.assets_path + '/folder.svg';
                    break;
                case 'spin':
                    placeholder = sirv_ajax_object.assets_path + '/spin-plhldr.svg';
                    break;
                case 'image':
                    placeholder = sirv_ajax_object.assets_path + '/img-plhldr.svg';
                    break;
                case 'video':
                    placeholder = sirv_ajax_object.assets_path + '/video-plhldr.svg';
                    break;
                case 'model':
                    placeholder = sirv_ajax_object.assets_path + '/model-plhldr.svg';

                default:
                    break;
            }

            return placeholder;
        }


        function getItemParams(type, size, delimiter='?'){
            let isRetina = window.devicePixelRatio > 1 ? true : false;
            let params = '';
            let dSize = size * 2;
            switch (type) {
                case 'dir':
                    break;
                case 'spin':
                    params = isRetina == true
                        ? delimiter + 'image&w='+ dSize +'&h='+ dSize +'&canvas.width='+ dSize +'&canvas.height='+ dSize +'&scale.option=fit'
                        : delimiter + 'image&w=' + size + '&h=' + size + '&canvas.width=' + size + '&canvas.height=' + size +'&scale.option=fit';
                    break;
                case 'image':
                    params = isRetina == true
                        ? delimiter + 'w=' + dSize + '&h=' + dSize + '&q=60&scale.option=noup'
                        : delimiter + 'w=' + size + '&h=' + size + '&q=60&scale.option=noup';
                    break;
                case 'video':
                    params = isRetina == true ? delimiter + 'thumbnail=' + size + '&q=60&scale.option=noup' : delimiter + 'thumbnail=' + size + '&q=60&scale.option=noup';
                    break;

                default:
                    break;
            }

            return params;
        }


        function getSearchItemType(item){
            let type = '';
            if(!!item.isDirectory) type = 'dir';
            if(item.extension == '.spin') type = 'spin';
            if(!!item.contentType && item.contentType.match(/image\/.*/ig)) type = 'image';
            if(!!item.contentType && item.contentType.match(/video\/.*/ig)) type = 'video';
            if(!!item.contentType && item.contentType.match(/model\/.*/ig)) type = 'model';

            return type;
        }


        function showSearchResults(show, continuosSearch=false){
            if(show){
                if(!$('.sirv-search').hasClass('sirv-search-v')){
                    $('.sirv-search').addClass('sirv-search-v sirv-latest-block');
                    $('.sirv-empty-folder-container').hide();
                    $('.sirv-dirs').hide();
                    $('.sirv-images').hide();
                    $('.sirv-spins').hide();
                    $('.sirv-videos').hide();
                    $('.sirv-models').hide();
                    $('.breadcrumb').hide();
                }else{
                    if(!continuosSearch){
                        unbindEvents();
                        $('#search-items').empty();
                        //hack to show results at top if use search few times
                        $('.sirv-items-container').scrollTop(0);


                        //bindEvents();
                    }
                }
            }else{
                unbindEvents();
                $('#search-items').empty();
                //bindEvents();
                $('.sirv-search').removeClass('sirv-search-v sirv-latest-block');
                $('.sirv-empty-folder-container').show();
                $('.sirv-dirs').show();
                $('.sirv-images').show();
                $('.sirv-spins').show();
                $('.sirv-videos').show();
                $('.sirv-models').show();
                $('.breadcrumb').show();
                $('.sirv-search-for').hide();
                $('.sirv-search-for').empty();
            }
        }


        function alphanumCase(a, b) {
            function chunkify(t) {
            let tz = new Array();
            let x = 0, y = -1, n = 0, i, j;

            while (i = (j = t.charAt(x++)).charCodeAt(0)) {
                let m = (i == 46 || (i >=48 && i <= 57));
                if (m !== n) {
                tz[++y] = "";
                n = m;
                }
                tz[y] += j;
            }
            return tz;
            }

            let aa = chunkify(a.toLowerCase());
            let bb = chunkify(b.toLowerCase());

            for (x = 0; aa[x] && bb[x]; x++) {
            if (aa[x] !== bb[x]) {
                let c = Number(aa[x]), d = Number(bb[x]);
                if (c == aa[x] && d == bb[x]) {
                return c - d;
                } else return (aa[x] > bb[x]) ? 1 : -1;
            }
            }
            return aa.length - bb.length;
        }


        function loadImage(elem, imgParams) {
            let $imgElem = $('.sirv-item-icon', elem);
            let src = $imgElem.attr('data-item-url');

            src = src.replaceAll('(', '%28')
                .replaceAll(')', '%29')
                .replaceAll('#', '%23')
                .replaceAll('?', '%3F')
                .replaceAll("'", '%27');

            let newImg = new Image();
            let attemptsToLoadImg = 2;

            function load(imgElem, newImage, src) {
                newImage.onload = function () {
                    imgElem.css('background-image', 'url(' + newImage.src + ')');
                }

                newImage.src = src;

                newImage.onerror = function () {
                    if (attemptsToLoadImg > 0) {
                        setTimeout(function () { load($imgElem, newImage, src); }, 2000);
                        attemptsToLoadImg--;
                    }
                }
            }

            load($imgElem, newImg, src + imgParams);
        }


        function eraseView(){

            unbindEvents();
            $('#dirs').empty();
            $('#images').empty();
            $('.sirv-empty-dir').remove();
            $('#spins').empty();
            $('#videos').empty();
            $('#models').empty();
            $('.breadcrumb').empty();
            $('.sirv-folders-title, .sirv-spins-title, .sirv-images-title, .sirv-videos-title, .sirv-models-title').hide();
        }


        function renderBreadcrambs(currentDir){
            if(currentDir != "/"){
                $('<li><span class="breadcrumb-text">You are here: </span><a href="#" class="sirv-breadcramb-link" data-item-sirv-path="/">Home</a></li>').appendTo('.breadcrumb');
                let dirs = currentDir.split('/').slice(1);
                let temp_dir = "";
                for(let i=0; i < dirs.length; i++){
                    temp_dir += "/" + dirs[i];
                    if(i+1 == dirs.length){
                        $('<li><span>' + dirs[i] + '</span></li>').appendTo('.breadcrumb');
                    }else{
                        $('<li><a href="#" class="sirv-breadcramb-link" data-item-sirv-path="' + encodeURIComponent(temp_dir) + '">' + dirs[i] + '</a></li>').appendTo('.breadcrumb');
                    }
                }
            }else{
                $('<li><span class="breadcrumb-text">You are here: </span>Home</li>').appendTo('.breadcrumb')
            }
        }


        function setCurrentDir(currentDir){
            let cDir = currentDir == '/' ? currentDir : currentDir.substr(1) + '/';
            $('#filesToUpload').attr('data-current-folder', cDir);
            $('.sirv-drop-to-folder').text(currentDir);
        }


        function getCurrentDir(){
            let currentDir = $('#filesToUpload').attr('data-current-folder');
            let dir = currentDir == '/' ? currentDir : '/' + currentDir.substring(0, currentDir.length -1);

            return dir;
        }


        function searchOnEnter(e){
            if(e.keyCode == 13)
            {
                globalSearch();
            }
        }

        function searchOnButtonPress(e){
            e.preventDefault();
            //e.stopPropagation();

            globalSearch();
        }


        function wideSearchField(e){
            $(this).removeClass('sirv-search-narrow').addClass('sirv-search-wide');
            $('.sirv-search-cancel').removeClass('narrow').addClass('wide');
        }


        function narrowSearchField(){
            if($(this).val() == ""){
                $(this).removeClass('sirv-search-wide').addClass('sirv-search-narrow');
                $('.sirv-search-cancel').removeClass('wide').addClass('narrow');
                hideSearchMenu();
            }
        }


        function globalSearch(from = 0, continuosSearch=false, dir=''){
            let query = $('#sirv-search-field').val();
            let queryMsg = (getCurrentDir() == '/' || !!dir) ? '' : " in entire account";
            let dirMsg = !!dir ? " in folder '" + dir +"'" : '';

            if(!!!query) return;

            if(!!!dir) isInDirSearch = false;

            hideSearchMenu();


            let ajaxData = {
                url: sirv_ajax_object.ajaxurl,
                data: {
                    action: 'sirv_get_search_data',
                    _ajax_nonce: sirv_ajax_object.ajaxnonce,
                    search_query: query,
                    from: from,
                    dir: dir,
                },
                type: 'POST',
                dataType: 'json',
            }
            //sendAjaxRequest(AjaxData, processingOverlay=false, showingArea=false, isDebug=false, doneFn=false, beforeSendFn=false, errorFn=false)
            sendAjaxRequest(ajaxData, processingOverlay = '.loading-ajax', showingArea = false, isdebug = false,
                doneFn = function (data) {
                    if(data){
                        $('.sirv-search-for').text("Results for '" + query + "'" + queryMsg + dirMsg);
                        if (from == 0) if ($('.sirv-items-container').scrollTop() > 0) $('.sirv-items-container').scrollTop(0);
                        if(data.isContinuation){
                            $('.sirv-items-container').on('scroll', searchLoadOnScroll);
                            searchFrom = data.from;
                        }else{
                            $('.sirv-items-container').off('scroll', searchLoadOnScroll);
                            searchFrom = 0;
                        }

                        //console.log(data);
                        unbindEvents();
                        showSearchResults(true, continuosSearch);
                        renderSearch(data);
                        restoreSelections(false);
                        bindEvents();
                        patchMediaBar();
                    }
                },
                beforeSendFn = function(){
                    $('.breadcrumb').hide();
                    $('.sirv-search-for').show();
                    $('.sirv-search-for').text("Searching for '" + query + "'" + queryMsg + dirMsg);
                }
            );
        }


        function cancelSearch(){
            $('#sirv-search-field').val('');
            $('#sirv-search-field').removeClass('sirv-search-wide').addClass('sirv-search-narrow');
            $('.sirv-search-cancel').removeClass('wide').addClass('narrow');
            hideSearchMenu();
            showSearchResults(false);
            restoreSelections(false);
            bindEvents();
        }

        function cancelSearchLight(){
            $search = $('#sirv-search-field');
            if(!!$search.val){
                $search.val('');
                $search.removeClass('sirv-search-wide').addClass('sirv-search-narrow');
                $('.sirv-search-cancel').removeClass('wide').addClass('narrow');
                hideSearchMenu();
            }
        }


        function onChangeSearchInput(){
            if( $(this).val() !== '' && getCurrentDir() !== '/'){
                showSearchMenu();
            }else{
                hideSearchMenu();
            }
        }


        function showSearchMenu(e){
            $searchField = $('#sirv-search-field');
            let offset = getElOffset($searchField[0]);

            $menu = $('.sirv-search-dropdown');
            $menu.css({'width': '300px', 'max-width' : '300px' });
            $menu.css({'top': offset.top, 'left': offset.left });
            $menu.show();
        }


        function reCalcSearchMenuPosition(){
            $menu = $('.sirv-search-dropdown');

            if($menu.is(":visible")){
                $searchField = $('#sirv-search-field');
                let offset = getElOffset($searchField[0]);
                $menu.css({'top': offset.top, 'left': offset.left });
            }
        }


        function hideSearchMenu(){
            $menu = $('.sirv-search-dropdown');
            $menu.hide();
        }


        function searchInDir(){
            isInDirSearch = true;

            globalSearch(0, false, getCurrentDir());
        }


        function getElOffset(el) {
            const rect = el.getBoundingClientRect();
            return {
                //left: rect.left + window.scrollX,
                left: rect.left,
                //top: rect.top + window.scrollY
                top: rect.top + rect.height,
            };
        }


        function rightClickContextMenu(e) {
            e.stopPropagation();
            e.preventDefault();

            deactivateActionMenu();

            if(!!$(this).attr('data-item-type')){
                let type = $(this).attr('data-item-type');
                let position = $(this).attr("data-menu-position") || false;
                renderActionMenu(e, type, $(this), position);
            }else{
                renderActionMenu(e, 'global', $(this));
            }
        }


        function clickActionMenu(e){
            e.preventDefault();
            e.stopPropagation();

            let $item = $(this).parent();
            let type = $item.attr('data-item-type');

            renderActionMenu(e, type, $item);
        }

        function renderActionMenu(e, type, $item, position=false){
            let $menu = $('.sirv-dropdown');
            let top = parseInt(e.pageY);
            let left = parseInt(e.pageX);

            if (!!type && type !== 'uploadButton') {
                let url = $('.sirv-item-icon', $item).attr('data-item-url') || '';
                let itemSirvPath = $item.attr('data-item-sirv-path');
                let title = $item.attr("data-item-title");

                url = url.replace('#', '%23').replace('?', '%3F');

                $menu.attr('data-item-url', url);
                $menu.attr('data-item-sirv-path', itemSirvPath);
                $menu.attr('data-item-type', type);
                $menu.attr('data-item-title', title);
            }

            let items = [
                { id: 'newfolder', class: 'sirv-menu-item-new-folder', icon: "fa fa-plus", group: 1, type: ['global'], text: "New folder"},
                { id:'opentab', class: 'sirv-menu-item-open-new-tab', icon: "fa fa-external-link", group: 1, type: ['image', 'video', 'spin'], text: "Open in new tab"},
                { id: 'copylink', class: 'sirv-menu-item-copy-link', icon: "fa fa-clipboard", group: 1, type: ['image', 'video', 'spin', 'model'], text: "Copy link"},
                { id: 'uploadfiles', class: 'sirv-menu-item-upload-files', icon: "fa fa-upload", group: 2, type: ['global', 'uploadButton'], text: "Upload files"},
                { id: 'uploaddirs', class: 'sirv-menu-item-upload-dirs', icon: "fa fa-upload", group: 2, type: ['global', 'uploadButton'], text: "Upload folders"},
                { id: 'duplicate', class: 'sirv-menu-item-duplicate', icon: "fa fa-copy", group: 2, type: ['image', 'video', 'spin', 'model'], text: "Duplicate"},
                { id: 'rename', class: 'sirv-menu-item-rename', icon: "fa fa-pencil", group: 2, type: ['image', 'video', 'spin', 'dir',, 'model'], text: "Rename"},
                { id: 'delete', class: 'sirv-menu-item-delete', icon: "fa fa-trash-o", group: 2, type: ['image', 'video', 'spin', 'dir', 'model'], text: "Delete"},
                { id: 'download', class: 'sirv-menu-item-download', icon: "fa fa-download", group: 3, type: ['image', 'video', 'spin', 'model'], text: "Download"},
            ];

            let divider = '<div class="sirv-dropdown-divider"></div>';
            let documentFragment = $(document.createDocumentFragment());
            let group = 0;

            for(let item of items){
                if ($.inArray(type, item.type) !== -1){
                    if(group === 0) group = item.group;
                    if(item.group !== group){
                        group = item.group;
                        documentFragment.append(divider);
                    }

                    let menuItem = $('<a class="sirv-dropdown-item '+ item.class +'" href="#">\n' +
                                        '<i class= "'+ item.icon +'"></i>\n'+
                                        '<span>'+ item.text +'</span>\n'+
                                    '</a>\n'
                    );
                documentFragment.append(menuItem);
                }else continue;
            }

            if(documentFragment.children().length > 0){
                $menu.empty().append(documentFragment);
                bindActionMenuEvents();
                $menu.addClass('sirv-menu--active');
                $menu.css({ 'display': 'block' });
                let offset = !!position ? calcMenuPosition($menu, $item, position) : calcElementOffset(e, $menu);
                $menu.css({ 'top': offset.top, 'left': offset.left });
            }

        }


        function calcElementOffset(e, elem){
            let cTop = parseInt(e.clientY);
            let cLeft = parseInt(e.clientX);
            let wHeigth = window.innerHeight;
            let wWidth = window.innerWidth;
            let elemBounds = elem[0].getBoundingClientRect();
            let cBottom = cTop + Math.ceil(elemBounds.height);
            let cRight = cLeft + Math.ceil(elemBounds.width);

            let top = cBottom > wHeigth ? cTop - ((cBottom - wHeigth) + 2) : cTop;
            let left = cRight > wWidth ? cLeft - ((cRight - wWidth) + 2) : cLeft;

            return {top: top, left: left};
        }


        function calcMenuPosition(menuElem, elem, position){
            let offset = {top: 0, left: 0};
            let menuBoundRect = menuElem[0].getBoundingClientRect();
            let elemBoundsRect = elem[0].getBoundingClientRect();
            switch (position) {
                case 'top':
                    offset.top = elemBoundsRect.top - menuBoundRect.height - 2;
                    offset.left = elemBoundsRect.left;
                    break;
                case 'right':
                    offset.top = elemBoundsRect.top;
                    offset.left = elemBoundsRect.right + 2;
                    break;
                case 'bottom':
                    offset.top = elemBoundsRect.bottom + 2;
                    offset.left = elemBoundsRect.left;
                    break;
                case 'left':
                    offset.top = elemBoundsRect.top;
                    offset.left = elemBoundsRect.left - menuBoundRect.width - 2;
                    break;
            }

            return offset;
        }


        $(document).on('click', deactivateActionMenu);
        function deactivateActionMenu(isClearParams=true){
            let $menu = $('.sirv-dropdown');

            unBindActionMenuEvents();

            $menu.empty();
            if (isClearParams) clearMenuData($menu);

            $menu.removeClass('sirv-menu--active');
            $menu.css({ 'display': 'none'});
        }


        function menuCopyItemLink(e){
            e.preventDefault();
            e.stopPropagation();

            let $menu = $('.sirv-dropdown');
            let fName = basename($menu.attr('data-item-url'));

            copyToClipboard($menu.attr('data-item-url'));
            deactivateActionMenu();

            /* toastr.options = {
                closeButton: true,
                debug: false,
                newestOnTop: false,
                progressBar: true,
                positionClass: "toast-bottom-left",
                preventDuplicates: false,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                timeOut: "5000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
            }; */
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr.options.preventDuplicates = true;
            toastr.options.positionClass = "toast-bottom-left";
            toastr.info(`Link copied to clipboard for "${decodeURI(fName)}"`);
        }

        function menuDeleteItem(e){
            e.preventDefault();
            e.stopPropagation();

            let isClearParams = true;

            let $menu = $('.sirv-dropdown');
            let type = $menu.attr('data-item-type');
            let delLink = $menu.attr("data-item-sirv-path");

            if(type == 'dir'){
                //get content for folder that want to delete to check if it's empty
                getContentFromSirv(delLink, false, deleteEmptyFolder);
                isClearParams = false;
            }else{
                deleteSelectedImages(delLink);
            }

            deactivateActionMenu(isClearParams);
        }


        function deleteEmptyFolder(data){
            const isEmptyMedia = isEmptyContentData(data);
            const hasHiddenFiles = hasHiddenFilesData(data);

            if (isEmptyMedia && !hasHiddenFiles){
                const $menu = $('.sirv-dropdown');
                const delLink = $menu.attr("data-item-sirv-path");

                deleteSelectedImages(delLink);
            } else{
                let msg = '';

                if(!isEmptyMedia && !hasHiddenFiles){
                    msg = 'Folder cannot be deleted because it contains files. Delete the folder contents first.'
                }

                if(isEmptyMedia && hasHiddenFiles){
                    msg = 'Folder cannot be deleted because it contains files that can\'t be shown here. Please use my.sirv.com to manage this folder.';
                }

                if(!isEmptyMedia && hasHiddenFiles){
                    msg = 'Folder cannot be deleted, because includes hidden files that cannot be deleted here. Please use my.sirv.com to manage folder.';
                }

                toastr.warning(msg, '', {preventDuplicates: true, timeOut: 10000, positionClass: "toast-top-center"});
            }
        }


        function menuDownloadItem(e){
            e.preventDefault();
            e.stopPropagation();

            let $menu = $('.sirv-dropdown');
            let url = $menu.attr('data-item-url');
            let fName = basename(url);


            let a = document.createElement('a');
            a.setAttribute('href', url + '?dl&format=original&quality=0');
            a.setAttribute('download', fName);

            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            deactivateActionMenu();
        }


        function menuOpenInNewTab(e){
            e.preventDefault();
            e.stopPropagation();


            let $menu = $('.sirv-dropdown');
            let url = $menu.attr('data-item-url');

            window.open(url, '_blank');

            deactivateActionMenu();
        }


        function menuNewFolder(e){
            e.preventDefault();
            e.stopPropagation();

            createFolder();

            deactivateActionMenu();
        }


        function menuUploadFiles(e){
            e.preventDefault();
            e.stopPropagation();

            $fileUploadInput = $(".sirvFilesToUpload");

            $fileUploadInput.removeAttr("webkitdirectory");
            $fileUploadInput.trigger("click");

            deactivateActionMenu();
        }


        //webkitdirectory;
        function menuUploadFolders(e) {
            e.preventDefault();
            e.stopPropagation();

            $fileUploadInput = $(".sirvFilesToUpload");

            $fileUploadInput.attr("webkitdirectory", "");
            $fileUploadInput.trigger("click");

            deactivateActionMenu();
        }


        function menuDuplicateFile(e){
            e.preventDefault();
            e.stopPropagation();

            let $menu = $('.sirv-dropdown');
            const filePath = $menu.attr('data-item-sirv-path');
            const decodedFilePath = decodeURIComponent(filePath);


            let type = $menu.attr('data-item-type');

            let basePath = basepath(decodedFilePath);
            let ext = getExt(filePath);
            let baseNameWithoutExt = basenameWithoutExt(decodedFilePath);
            let searchPattern = new RegExp(baseNameWithoutExt +"\\s\\(copy(?:\\s\\d)*?\\)\\." + ext, 'i');

            let countCopies = searchFileCopies(type, searchPattern);

            let copyNum = countCopies > 0 ? ' ' + (countCopies) : '';
            let copyPattern = ' (copy'+ copyNum +').';
            let copyPath = encodeURIComponent(basePath + baseNameWithoutExt + copyPattern + ext);

            duplicateFile(filePath, copyPath);

            deactivateActionMenu();
        }


        function menuRenameItem(e){
            e.preventDefault();
            e.stopPropagation();

            let $menu = $('.sirv-dropdown');
            let title = htmlDecode($menu.attr("data-item-title"));
            const itemType = $menu.attr('data-item-type');
            const fileExt = itemType === "dir" ? '' : getExt(title);
            const filePath = $menu.attr('data-item-sirv-path');
            const decodeFilePath = decodeURIComponent($menu.attr('data-item-sirv-path'));
            deactivateActionMenu();

            title = !!fileExt ? basenameWithoutExt(title) : title;

            const newFileName = window.prompt("Enter new file name:", title);

            if (!!newFileName) {
                let basePath = basepath(decodeFilePath);
                let ext = itemType == 'dir' ? '' : '.' + fileExt;
                let newFilePath = encodeURIComponent(basePath + newFileName + ext);

                renameFile(filePath, newFilePath);
            }
        }


        function copyToClipboard(text) {
            var dummy = document.createElement("textarea");
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
        }


        function duplicateFile(filePath, copyFilePath){
            let ajaxData = {
                url: sirv_ajax_object.ajaxurl,
                data: {
                    action: 'sirv_copy_file',
                    _ajax_nonce: sirv_ajax_object.ajaxnonce,
                    filePath: filePath,
                    copyPath: copyFilePath,
                },
                type: 'POST',
                dataType: 'json',
            }
            //sendAjaxRequest(AjaxData, processingOverlay=false, showingArea=false, isDebug=false, doneFn=false, beforeSendFn=false, errorFn=false)
            sendAjaxRequest(ajaxData, processingOverlay = '.loading-ajax', showingArea = false, isdebug = false,
                doneFunc = function (data) {
                    if(!!data){
                        if(data.duplicated){
                            toastr.success(`File has been successfuly duplicated`, "", {preventDuplicates: true, timeOut: 4000, positionClass: "toast-top-center"});
                            getContentFromSirv(window.sirvGetPath);
                        }else{
                            //console.log('file was not duplicated');
                        }
                    }
                },
                beforeSendFunc = false,
                errorFn = function(jqXHR, status, error){
                    $(".loading-ajax").hide();

                    console.error("Error during ajax request: " + error);
                    console.error("Status: " + status);
                    console.error(jqXHR.responseText);

                    toastr.error(`Ajax error: ${error}`, "", {preventDuplicates: true, timeOut: 4000, positionClass: "toast-top-center"});
                }
            );
        }


        function renameFile(filename, newFilename) {
                let ajaxData = {
                    url: sirv_ajax_object.ajaxurl,
                    data: {
                        action: "sirv_rename_file",
                        _ajax_nonce: sirv_ajax_object.ajaxnonce,
                        filePath: filename,
                        newFilePath: newFilename,
                    },
                    type: "POST",
                    dataType: "json",
                };
                sendAjaxRequest(ajaxData, processingOverlay = '.loading-ajax', showingArea = false, isdebug = false,
                    doneFunc = function (data) {
                        if(!!data){
                            if(data.renamed){
                                getContentFromSirv(window.sirvGetPath);
                            }else{
                                console.log('File was not renamed');
                            }
                        }
                    },
                    beforeSendFunc = false,
                    errorFn = function(jqXHR, status, error){
                        $(".loading-ajax").hide();

                        console.error("Error during ajax request: " + error);
                        console.error("Status: " + status);
                        console.error(jqXHR.responseText);

                        toastr.error(`Ajax error: ${error}`, "", {preventDuplicates: true, timeOut: 4000, positionClass: "toast-top-center"});
                    }
                );
        }


        function searchFileCopies(type, searchPattern){
            let types = {image: 'images', spin: 'spins', video: 'videos'};

            items = contentData.content[types[type]];
            let countMatches = 0;

            for(item of items){
                if(item.filename.match(searchPattern)) countMatches++;
            }

            return countMatches;

        }


        function decodeItemPath(itemPath){

        }


        function encodeItemPath(itemPath){
            return encodeURIComponent(decodeURI(itemPath)).replace(/%2F/gi, '/');
        }


        function clearMenuData($menu){
            $menu.attr('data-item-url', '');
            $menu.attr('data-item-sirv-path', '');
            $menu.attr('data-item-title', '');
        }


        function bindActionMenuEvents(){
            $('.sirv-menu-item-copy-link').on('click', menuCopyItemLink);
            $('.sirv-menu-item-delete').on('click', menuDeleteItem);
            $('.sirv-menu-item-download').on('click', menuDownloadItem);
            $('.sirv-menu-item-open-new-tab').on('click', menuOpenInNewTab);
            $('.sirv-menu-item-new-folder').on('click', menuNewFolder);
            $('.sirv-menu-item-upload-files').on('click', menuUploadFiles);
            $(".sirv-menu-item-upload-dirs").on("click", menuUploadFolders);
            $('.sirv-menu-item-duplicate').on('click', menuDuplicateFile);
            $('.sirv-menu-item-rename').on('click', menuRenameItem);
        }


        function unBindActionMenuEvents() {
            $('.sirv-menu-item-copy-link').off('click', menuCopyItemLink);
            $('.sirv-menu-item-delete').off('click', menuDeleteItem);
            $('.sirv-menu-item-download').off('click', menuDownloadItem);
            $('.sirv-menu-item-open-new-tab').off('click', menuOpenInNewTab);
            $('.sirv-menu-item-new-folder').off('click', menuNewFolder);
            $('.sirv-menu-item-upload-files').off('click', menuUploadFiles);
            $(".sirv-menu-item-upload-dirs").off("click", menuUploadFolders);
            $('.sirv-menu-item-duplicate').off('click', menuDuplicateFile);
            $('.sirv-menu-item-rename').off('click', menuRenameItem);
        }


        function bindEvents(){
            $('.sirv-items-container').on('contextmenu', rightClickContextMenu);
            $('.sirv-item-menu-actions').on('click', clickActionMenu);
            $('.sirv-item-body').on('contextmenu', rightClickContextMenu);
            $('.sirv-items-container').on('scroll', toolbarFixed);
            $('#sirv-search-field').on('focus', wideSearchField);
            $('#sirv-search-field').on("focusout", narrowSearchField);
            $('#sirv-search-field').on('keyup', searchOnEnter);
            $("#sirv-search-button").on("click", searchOnButtonPress);
            $('.sirv-search-cancel').on('click', cancelSearch);
            $('#sirv-search-field').on('input', onChangeSearchInput);
            $(window).on('resize', reCalcSearchMenuPosition);
            $('.sirv-search-in-dir').on('click', searchInDir);
            $('.sirv-breadcramb-link').on('click', beforeGetContent);
            $('.sirv-item-body[data-item-type=dir]').on('click', beforeGetContent);
            $('.sirv-item-body:not([data-item-type=dir])').on('click', function(event){selectImages(event, $(this))});
            $('.insert').on('click', insert);
            $('.sirv-create-gallery').on('click', createGallery);
            $('.clear-selection').on('click', clearSelection);
            $('.delete-selected-images').on('click', function(){deleteSelectedImages('')});
            $('.create-folder').on('click', createFolder);
            $(".fileinput-button").on("click", rightClickContextMenu);
            //$('.sirvFilesToUpload').on('change', function(event){modernUploadImages(event.target.files);});
            $('.sirvFilesToUpload').on('change', loadFilesFromSelect);
            $('.sirv-gallery-type').on('change', manageOptionsStates);
            $('#gallery-thumbs-position').on('change', manageThumbPosition);
            $('.set-featured-image').on('click', setFeaturedImage);
            $('.sirv-woo-add-images').on('click', addWooSirvImages);
            $(".sirv-woo-set-product-image").on("click", setWooProductImage);
            $('.nav-tab-wrapper > a').on('click', function(e){changeTab(e, $(this));});
            $('input[id=gallery-width]').on('input', onChangeWidthInputRI);
            $("input[name=sirv-image-link-type]").on("click", manageOptionLink);
            $("input[name=sirv-model-autorotate]").on("click", manageModelAutorotate);

            bindDrugEvents(true);
            //bindActionMenuEvents();
        };


        function unbindEvents(){
            $('.sirv-items-container').off('contextmenu', rightClickContextMenu);
            $('.sirv-item-body').off('contextmenu', rightClickContextMenu);
            $('.sirv-item-menu-actions').off('click', clickActionMenu);
            $('.sirv-items-container').off('scroll', toolbarFixed);
            $('#sirv-search-field').off('focus', wideSearchField);
            $('#sirv-search-field').off("focusout", narrowSearchField);
            $('#sirv-search-field').off('keyup', searchOnEnter);
            $("#sirv-search-button").off("click", searchOnButtonPress);
            $('.sirv-search-cancel').off('click', cancelSearch);
            $('#sirv-search-field').off('input', onChangeSearchInput);
            $(window).off('resize', reCalcSearchMenuPosition);
            $('.sirv-search-in-dir').off('click', searchInDir);
            $('.insert').off('click', insert);
            $('.sirv-create-gallery').off('click', createGallery);
            $('.sirv-breadcramb-link').off('click', beforeGetContent);
            $('.sirv-item-body[data-item-type=dir]').off('click', beforeGetContent);
            $('.sirv-item-body:not([data-item-type=dir])').off('click');
            $('.clear-selection').off('click');
            $('.delete-selected-images').off('click');
            $('.create-folder').off('click');
            $('#filesToUpload').off('change');
            $('#gallery-flag').off('click');
            $('#gallery-zoom-flag').off('click');
            $('.sirv-gallery-type').off('change');
            $('.set-featured-image').off('click');
            $('.sirv-woo-add-images').off('click', addWooSirvImages);
            $(".sirv-woo-set-product-image").off("click", setWooProductImage);
            $('input[id=gallery-width]').off('input');
            $("input[name=sirv-image-link-type]").off("click", manageOptionLink);
            $("input[name=sirv-model-autorotate]").off("click", manageModelAutorotate);

            bindDrugEvents(false);
            //unBindActionMenuEvents();
        }


        window.sirvGetPath = function(){
            if(window.sirvViewerPath){
                return window.sirvViewerPath;
            }
            return '/';
        }


        function beforeGetContent() {
            let dataLink = $(this).attr('data-item-sirv-path');
            window.sirvViewerPath = dataLink;
            getContentFromSirv(dataLink);
        }


        window.getContentFromSirv = function(path, isRender=true, unRenderFunc=false, continuation=''){
            path = ( !!path ) ? path : '/';

            //clean searh field on update content
            /* if($('#sirv-search-field').val() !== ''){
                $('#sirv-search-field').val('');
                $('#sirv-search-field').removeClass('sirv-search-wide').addClass('sirv-search-narrow');
            } */
            cancelSearchLight();

            let ajaxData = {
                url: sirv_ajax_object.ajaxurl,
                data: {
                    action: "sirv_get_content",
                    _ajax_nonce: sirv_ajax_object.ajaxnonce,
                    path: path,
                    continuation: continuation,
                },
                type: "POST",
                dataType: "json",
            };

            $('.sirv-empty-dir').remove();

            sendAjaxRequest(ajaxData, processingOverlay='.loading-ajax', showingArea=false, isdebug=false, function(data){
                if(data){
                    //console.log(data);
                    if(isRender){
                        clearOnScrollLoadingParams();
                        contentData = data;
                        scrollStack = manageContent(data);
                        emptyFolder = isEmptyContentData(data);
                        fixLatestUsesBlock(data);


                        eraseView();
                        showSearchResults(false);
                        renderBreadcrambs(data.current_dir);
                        setCurrentDir(data.current_dir);
                        renderView();
                        restoreSelections(false);
                        bindEvents();
                        patchMediaBar();
                    }else{
                        unRenderFunc(data);
                    }

                }
            });

        }


        function patchMediaBar(){

            if($('#chrome_fix', top.document).length <= 0){
                $('head', top.document).append($('<style id="chrome_fix">.media-frame.hide-toolbar .media-frame-toolbar {display: none;}</style>'));
            }
        }


        //create folder
        function createFolder(){
            let newFolderName = window.prompt("Enter folder name:", "");

            if (!!newFolderName) {
                let ajaxData = {
                    url: sirv_ajax_object.ajaxurl,
                    type: 'POST',
                    dataType: "json",
                    data: {
                        action:  'sirv_add_folder',
                        _ajax_nonce: sirv_ajax_object.ajaxnonce,
                        current_dir:  $('#filesToUpload').attr('data-current-folder'),
                        new_dir:  newFolderName
                    },
                }

                sendAjaxRequest(ajaxData, processingOverlay='.loading-ajax', showingArea=false, isdebug=false, function(response){
                    if(!!response){
                        if(!!response.isNewDirCreated){
                            getContentFromSirv(window.sirvGetPath);
                        }else{
                            console.log('Folder did not create.');
                        }
                    }
                });
            }
        }

        function filesSumSize(files){
            let sumSize = 0;

            $.each(files, function(index, value){
                sumSize += value.size;
            });

            return sumSize;
        }


        function loadFilesFromSelect(event){
            event.stopPropagation();
            event.preventDefault();

            const filesPromise = getDroppedOrSelectedFiles(event);
            filesPromise.then((files) => {
                modernUploadImages(files);
            });
        }


        //upload images
        let uploadTimer;

        window['modernUploadImages'] = function(files){
            let groupedImages = groupedFiles(files, maxFileSize, maxFilesCount, sirvFileSizeLimit);
            let countFiles = files.length;

            let currentDir = htmlDecode($('#filesToUpload').attr('data-current-folder'));

            //clear progress bar data before start new upload
            $('.sirv-progress-bar').css('width', '0');
            $('.sirv-progress-text').html('');
            $('.sirv-progress-text').html('<span class="sirv-ajax-gif-animation sirv-no-lmargin"></span>processing...');

            //clear list of files
            let input = $("#filesToUpload");
            input.replaceWith(input.val('').clone(true));

            if(countFiles > 0){
                $('.sirv-upload-ajax').show();
                uploadTimer = window.setInterval(getUploadingStatus, 2500);

                uploadByPart(groupedImages, currentDir, countFiles);
                //$('.sirv-empty-dir').remove();
            }

        }


        function uploadByPart(groupedImages, currentDir, countFiles){
            if(groupedImages['partArray'].length !== 0){
                let imagePaths = [];
                let imagePart = groupedImages['partArray'].shift();
                let data = new FormData();

                data.append('action', 'sirv_upload_files');
                data.append("_ajax_nonce", sirv_ajax_object.ajaxnonce);
                data.append('current_dir', currentDir);
                data.append('totalFiles', countFiles);

                $.each(imagePart, function(index, fileItem){

                    data.append(index, fileItem.fileObject);
                    //imagePaths.push({name: fileItem.name, path: fileItem.fullPath});
                    imagePaths.push(fileItem.fullPath);
                });

                data.append("imagePaths", JSON.stringify(imagePaths));

                let ajaxData = {
                    url: sirv_ajax_object.ajaxurl,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    data: data
                }
                sendAjaxRequest(ajaxData, processingOverlay=false, showingArea=false, isdebug=false,
                    doneFn=function(response){
                        uploadByPart(groupedImages, currentDir, countFiles);
                    },
                    beforeFn=false,
                    errorFn=function(jqXHR, status, error){
                        toastr.error(`Ajax error: ${error}`, '', {preventDuplicates: true, timeOut: 4000, positionClass: "toast-top-center"});
                        $(".sirv-upload-ajax").hide();
                        getContentFromSirv(window.sirvGetPath);
                    });
            }else{
                if(groupedImages['overSizedImages'].length !== 0){
                    uploadImagesByChunk(groupedImages['overSizedImages'], currentDir, countFiles);
                }else{
                    $('.sirv-upload-ajax').hide();
                    getContentFromSirv(window.sirvGetPath);
                }
            }
        }


        function uploadImagesByChunk(overSizedImages, currentDir){
            let totalOverSizedFiles = overSizedImages.length;
            for(let index = 0; index < totalOverSizedFiles; index++){
                let fileItem = overSizedImages[index];
                let reader = new FileReader();
                uploadImageByChunk(fileItem, 0, reader, 1, totalOverSizedFiles, currentDir);
            }
        }


        function uploadImageByChunk(fileItem, start, reader, partNum, totalOverSizedFiles, currentDir){
            const errorLimitPattern = new RegExp(/bytes exceeds the limit of/im);

            let file = fileItem.fileObject;
            let maxSliceSize = 2 * 1024 * 1024;
            let sliceSize = getSliceSize(maxFileSize, maxSliceSize);
            let nextSlice = start + sliceSize + 1;
            let blob = file.slice(start, nextSlice);

            let totalSlices = countSlices(file.size, sliceSize);

            reader.onloadend = function( event ) {
                if ( event.target.readyState !== FileReader.DONE ) {
                    return;
                }
                let data = new FormData();
                data.append('action', 'sirv_upload_file_by_chanks');
                data.append("_ajax_nonce", sirv_ajax_object.ajaxnonce);
                data.append("partFileName", file.name);
                data.append('partFilePath', fileItem.fullPath);
                data.append('totalParts', totalSlices);
                data.append('totalFiles', totalOverSizedFiles);
                data.append('partNum', partNum);
                data.append('currentDir', currentDir);
                data.append('binPart', event.target.result);

                const requestContentLenght = getContentLength(data);

                let ajaxData = {
                                url: sirv_ajax_object.ajaxurl,
                                type: 'POST',
                                contentType: false,
                                processData: false,
                                data: data
                }

                sendAjaxRequest(ajaxData, processingOverlay=false, showingArea=false, isdebug=false,
                    doneFn=function(response){

                        if(!isJsonString(response)){
                            if (errorLimitPattern.test(response)) {
                                realRequestSize = realRequestSize == 0 ? requestContentLenght : realRequestSize;
                                uploadImageByChunk(fileItem, 0, reader, 1, totalOverSizedFiles, currentDir);
                            }else{
                                realRequestSize = 0;
                                $(".sirv-upload-ajax").hide();

                                console.error("Error during ajax request: " + response);
                                toastr.error(`Ajax error: ${response}`, "", {preventDuplicates: true, timeOut: 4000, positionClass: "toast-top-center"});
                            }
                        }else{
                            if ( nextSlice < file.size ) {
                                uploadImageByChunk(fileItem, nextSlice, reader, partNum + 1, totalOverSizedFiles, currentDir);
                            }

                            let json_obj = JSON.parse(response);
                            if(json_obj.hasOwnProperty('stop') && json_obj.stop == true){
                                $('.sirv-upload-ajax').hide();
                                getContentFromSirv(window.sirvGetPath);
                                realRequestSize = 0;
                            }
                        }
                    },
                    beforeSendFunc=false,
                    errorFn = function(jqXHR, status, error){
                        if(jqXHR.status == 400 && errorLimitPattern.test(jqXHR.responseText)){
                            realRequestSize = realRequestSize == 0 ? requestContentLenght : realRequestSize;
                            uploadImageByChunk(fileItem, 0, reader, 1, totalOverSizedFiles, currentDir);
                        }else{
                            realRequestSize = 0;
                            $(".sirv-upload-ajax").hide();

                            console.error("Error during ajax request: " + error);
                            console.error("Status: " + status);
                            console.error(jqXHR.responseText);

                            toastr.error(`Ajax error: ${error}`, "", {preventDuplicates: true, timeOut: 4000, positionClass: "toast-top-center"});
                            getContentFromSirv(window.sirvGetPath);
                        }
                    }
                );
            };

            reader.readAsDataURL( blob );
        }


        function getSliceSize(maxFileSize, maxSliceSize){
            let sliceSize = maxSliceSize > maxFileSize ? maxFileSize : maxSliceSize;

            if(realRequestSize > 0){
                const delta = realRequestSize - sliceSize;

                sliceSize = delta > 0 ? sliceSize - delta : sliceSize;
            }

            //minus few kb on additional request data
            sliceSize -= 2 * 1024;

            return sliceSize;
        }


        function isJsonString(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }


        function getContentLength(formData) {
            const formDataEntries = [...formData.entries()];

            const contentLength = formDataEntries.reduce((acc, [key, value]) => {
                if (typeof value === "string") {
                    //console.log(`${key} => ${value.length}`);
                    return acc + value.length;
                }
                if (typeof value === "object") {
                    //console.log(`${key} => ${value.size}`);
                    return acc + value.size;
                }

                return +acc;
            }, 0);

            return contentLength;
        }


        function countSlices(fileSize, sliceSize){
            let nextSlice = 0;
            let count = 0;
            while(nextSlice < fileSize){
                count += 1;
                nextSlice += sliceSize + 1;
            }

            return count;
        }


        function groupedFiles(files, maxFileSize, maxFiles, sirvFileSizeLimit){
            let partArray = [];
            let overSizedImages = [];
            let sumFileSize = 0;
            let filesCount = 0;
            let part = 0;

            partArray.push([]);

            for(let i = 0; i<files.length; i++){
                let file = files[i];
                sumFileSize += file.size;
                filesCount += 1;
                if((sumFileSize >= maxFileSize && filesCount > maxFiles) || filesCount > maxFiles || sumFileSize >= maxFileSize){
                    if (file.size < maxFileSize){
                        sumFileSize = file.size;
                        filesCount = 1;
                        part += 1;
                        partArray.push([]);
                    }else{
                        overSizedImages.push(file);
                        continue;
                    }
                }

                partArray[part].push(file);
            }

            partArray = removeEmptyArrItems(partArray);

            return {partArray: partArray, overSizedImages: overSizedImages};
        }


        function removeEmptyArrItems(dataArr){
            for(let index=0; index<dataArr.length; index++){
                if (dataArr[index].length === 0) dataArr.splice(index, 1);
            }

            return dataArr;
        }


        //FirstImageUploadDelay - delay before first image will be uploaded. Need if loading big image and getUploadingStatus() will not get status info during uploading first image
        let FirstImageUploadDelay = 50;
        function getUploadingStatus(){
            let data = {
                        action: 'sirv_get_image_uploading_status',
                        sirv_get_image_uploading_status: true
            }
            let ajaxData = {
                            url: sirv_ajax_object.ajaxurl,
                            type: 'POST',
                            data: data
            }
            sendAjaxRequest(ajaxData, processingOverlay=false, showingArea=false, isdebug=false,
                doneFn=function(response){
                let json_obj = JSON.parse(response);
                if(json_obj.processedImage!== null || json_obj.count !== null){
                    $('.sirv-progress-bar').css('width', json_obj.percent + '%');
                    $('.sirv-progress-text').html(json_obj.percent + '%' + ' ('+ json_obj.processedImage +' of '+ json_obj.count +')');

                    if (json_obj.percent == 100) {
                        window.clearInterval(uploadTimer);
                    }
                }else{
                    if(json_obj.isPartFileUploading){
                        $('.sirv-progress-text').html('<span class="sirv-traffic-loading-ico sirv-no-lmargin"></span>processing upload big files by chunks...');
                    }else{
                        //$('.sirv-progress-text').html('processing...');
                        if(FirstImageUploadDelay == 0){
                            window.clearInterval(uploadTimer);
                            FirstImageUploadDelay = 50;
                        }
                        FirstImageUploadDelay--;
                    }
                }
            });
        }


        function searchImages() {
            let querySearch = $('#sirv-search-field').val();
            let data = JSON.parse(JSON.stringify(contentData));

            let isSearchActive = false;
            let currentDir = data.current_dir !== '/' ? data.current_dir : '';


            function searchItems(inSearch, key, querySearch) {
                let searchedItems = [];
                for (let i = 0; i < inSearch.length; i++) {
                    let cleanedName = (inSearch[i][key]).replace(currentDir, '');
                    if ((cleanedName.toLowerCase().indexOf(querySearch.toLowerCase())) !== -1) {
                        //searchedItems.push({ [key]: inSearch[i][key] });
                        searchedItems.push(inSearch[i]);
                    }
                }

                //hack if one item. In usual way contents also consist key with currentDir.
                //if (searchedItems.length > 0 && key == "Key" && isValueExists(inSearch)) searchedItems.push({ [key]: currentDir });

                return searchedItems;
            }


            function isValueExists(object) {
                for (let prop in object) {
                    if (object[prop] == currentDir) return true;
                }

                return false;
            }

            /* if (!querySearch) {

            } */

            if (data.content.images.length > 0) {
                isSearchActive = true;

                data.content.images = searchItems(data.content.images, 'filename', querySearch);
            }

            if (data.content.dirs.length > 0) {
                isSearchActive = true;

                data.content.dirs = searchItems(data.content.dirs, 'filename', querySearch);
            }

            if (data.content.spins.length > 0) {
                isSearchActive = true;

                data.content.spins = searchItems(data.content.spins, 'filename', querySearch);
            }

            if (data.content.videos.length > 0) {
                isSearchActive = true;

                data.content.videos = searchItems(data.content.videos, 'filename', querySearch);
            }

            if (isSearchActive) {
                eraseView();
                renderBreadcrambs(data.current_dir);
                setCurrentDir(data.current_dir);
                scrollStack = manageContent(data);
                noResults = isEmptyContentData(data);
                emptyFolder = isEmptyContentData(data);
                hideItemsTitle();
                renderView();
                restoreSelections(false);
                bindEvents();
                patchMediaBar();
            }
        }


        function basename(path,prefix='/') {
            path = path.split(prefix);

            return path[ path.length - 1 ];
        }


        function basepath(path, prefix='/'){
            let pathParts = path.split(prefix);

            let basePath = pathParts.slice(0, pathParts.length - 1).join(prefix)

            basePath = basePath === '/' ? basePath : `${basePath}/`;

            return basePath;
        }


        function getExt(filePath) {
            return filePath.substr((~-filePath.lastIndexOf(".") >>> 0) + 2);
        }


        function basenameWithoutExt(filePath) {
            let fileName = basename(filePath);
            let ext = '.' + getExt(fileName);

            return fileName.replace(ext, '');
        }


        function encodedFilename(path){
            fileName = basename(path);
            filePath = path.replace(fileName, '');

            return filePath + encodeURIComponent(fileName);
        }


        function escapeXMLChars(path){
            return path.replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&apos;');
        }


        function escapeHtml(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };

            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }


        function htmlDecode(input) {
            var doc = new DOMParser().parseFromString(input, "text/html");
            return doc.documentElement.textContent;
        }


        function deleteSelectedImages(file=''){
            let filenamesArray = [];

            if(!!file){
                filenamesArray.push(file);
            }else{
                let selectedImages = $('.selected-miniature-img');
                    $.each(selectedImages, function (index, value) {
                        //filenamesArray.push( escapeXMLChars($(value).attr('data-dir') + decodeURI(basename($(value).attr('data-item-url')))) );
                        //filenamesArray.push( escapeXMLChars($(value).attr('data-dir') + basename($(value).attr('data-item-url'))) );
                        filenamesArray.push( $(value).attr('data-item-sirv-path') );
                    });
            }

            let data = {
                action: "sirv_delete_files",
                _ajax_nonce: sirv_ajax_object.ajaxnonce,
                filenames: filenamesArray,
                };

            let ajaxData = {
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: data
            }

            sendAjaxRequest(ajaxData, processingOverlay='.loading-ajax', showingArea=false, isdebug=false, function(response){
                if(response.delete > 0){
                    toastr.success(getDeleteFilesMsg(response.delete), '', {preventDuplicates: true, timeOut: 2000, positionClass: "toast-top-center"});
                }

                if(response.undelete > 0){
                    toastr.error(getDeleteFilesMsg(response.undelete, ' not'), '', {preventDuplicates: true, timeOut: 2000, positionClass: "toast-top-center"});
                }
                getContentFromSirv(window.sirvGetPath);
                if(!!!file) clearSelection();
            },
            null,
            function(jqXHR, status, error){
                toastr.error(`Ajax error: ${error}`, '', {preventDuplicates: true, timeOut: 4000, positionClass: "toast-top-center"});
            }
            );
        }


        function getDeleteFilesMsg(count, not=''){
            return count == 1
                ? `${count} file has${not} been deleted`
                : `${count} files have${not} been deleted`;
        }


        function getItemSrc(type, url, size){
            const noImagesItems = ['model', 'audio', 'file'];

            return $.inArray(type, noImagesItems) != -1 ? getItemPlaceHolder(type) : url + getItemParams(type, size);
        }


        function selectImages(event, $obj) {

            function addMiniatures($obj) {
                let data = {
                    id: $obj.attr('data-item-id'),
                    url: $('.sirv-item-icon', $obj).attr('data-item-url'),
                    dir: $obj.attr('data-dir'),
                    itemSirvPath: $obj.attr('data-item-sirv-path'),
                    type: $obj.attr('data-item-type'),
                    width: $('.sirv-item-meta-container', $obj).attr('data-width') || 0,
                    height: $('.sirv-item-meta-container', $obj).attr('data-height') || 0,
                }

                $('.selected-miniatures-container').append(
                    '<li class="selected-miniature">'+
                        '<img class="selected-miniature-img" data-item-id="' + data.id +
                        '" data-item-url="' + data.url + '" data-item-type="' + data.type +
                        '" data-dir="' + data.dir + '"' +
                        '" data-item-sirv-path="' + data.itemSirvPath + '"' +
                        ' data-caption="" src="' + getItemSrc(data.type, data.url, 40) +'"' +
                        ' data-width="'+ data.width +'"'+' data-height="'+ data.height +'"'+
                    ' /></li>\n');
            }

            function removeMiniatures($obj) {
                $($('img[data-item-id=' + $obj.attr('data-item-id') + ']').closest('li.selected-miniature')).remove();
            }

            let curr = -1;

            if (event.ctrlKey) {
                event.preventDefault();
            }

            if (event.shiftKey) {
                event.preventDefault();

                curr = $('.sirv-item-body:not([data-item-type=dir]').index($obj);
                if (prev > -1) {
                    let miniaturesArray = [];
                    $('.selected-miniature-img').each(function () {
                        miniaturesArray.push($(this).attr('data-item-id'));
                    });
                    $('.sirv-item-body:not([data-item-type=dir]').slice(Math.min(prev, curr), 1 + Math.max(prev, curr)).each(function () {
                        if ($.inArray($(this).attr('data-item-id'), miniaturesArray) == -1) {
                            $(this).addClass('sirv-item-body--selected');
                            addMiniatures($(this));
                        }
                    });
                }
            } else {
                curr = prev = $('.sirv-item-body:not([data-item-type=dir]').index($obj);

                if ($obj.hasClass('sirv-item-body--selected')) {
                    $obj.removeClass('sirv-item-body--selected');
                    //$obj.closest('li').removeClass('selected');
                    removeMiniatures($obj);

                } else {
                    $obj.addClass('sirv-item-body--selected');
                    //$obj.closest('li').addClass('selected');
                    addMiniatures($obj);
                }
            }

            if ($('.selected-miniature-img').length > 0) {
                $('.selection-content').addClass('items-selected');
                $('.count').text($('.selected-miniature-img').length + " selected");
            } else $('.selection-content').removeClass('items-selected');
        };


        function restoreSelections(isAddImages){

            $('.sirv-item-body--selected').removeClass('sirv-item-body--selected');

            if(isAddImages){
                $('.selected-miniatures-container').empty();

                if($('.gallery-img').length > 0){
                    let galleryItems = $('.gallery-img');

                    $.each(galleryItems, function(index, value){
                        $('.selected-miniatures-container').append('<li class="selected-miniature"><img class="selected-miniature-img" data-item-id="'+ $(this).attr('data-item-id') +
                            '" data-item-url="'+ $(this).attr('data-item-url') +'" data-item-type="'+ $(this).attr('data-item-type') + '"'+
                            '  data-caption="'+ escapeHtml($(this).parent().siblings('span').children().val()) +'"'+
                            '  src="'+ getItemSrc($(this).attr('data-item-type'), $(this).attr('data-item-url') , 40) +'"' +' /></li>\n');
                    });
                }
            }

            if($('.selected-miniature-img').length > 0){
                let selectedImages = $('.selected-miniature-img');
                $('.count').text(selectedImages.length + " selected");

                if($('.selection-content').not('.items-selected')){
                    $('.selection-content').addClass('items-selected');
                }

                $.each(selectedImages, function(index, value){
                    $('.sirv-item-body[data-item-id="' + $(value).attr('data-item-id') + '"]').addClass('sirv-item-body--selected');
                });
            }else{
                $('.selection-content').removeClass('items-selected');
            }
        }


        function clearSelection(){

            $(".selected-miniatures-container").empty();
            $('.sirv-item-body--selected').removeClass('sirv-item-body--selected');
            $('.selection-content').removeClass('items-selected');
            $('.count').text($('.selected-miniature-img').length + " selected");
        }


        function insert(){

                let html = '';
                let $gallery = $('.sirv-gallery-type[value=gallery-flag]');
                let $zoom = $('.sirv-gallery-type[value=gallery-zoom-flag]');
                let $spin = $('.sirv-gallery-type[value=360-spin]');
                let $video = $('.sirv-gallery-type[value=video]');
                let $model = $('.sirv-gallery-type[value=model]');
                let $staticImage = $('.sirv-gallery-type[value=static-image]');
                let $responsiveImage = $('.sirv-gallery-type[value=responsive-image]');

                let isResponsive = $responsiveImage.is(':checked');
                let isStatic = $staticImage.is(':checked');
                let id = '';

                let srImagesAttr = {
                    'isResponsive': isResponsive,
                    'isLazyLoading': '',
                };

                if($gallery.is(':checked') || $zoom.is(':checked') || $spin.is(':checked') || $video.is(':checked') || $model.is(':checked') ){
                    $('.loading-ajax').show();

                    window['sirvIsChangedShortcode'] = true;

                    if($('.insert').hasClass('edit-gallery')){
                        id = parseInt($('.insert').attr('data-shortcode-id'));
                        save_shorcode_to_db('sirv_update_sc', id);
                    }else{
                        id = save_shorcode_to_db('sirv_save_shortcode_in_db');
                        html = '[sirv-gallery id='+ id +']';
                    }

                }else{

                    let isLazyLoading = $('#responsive-lazy-loading').is(":checked");
                    let linkType = $('input[name=sirv-image-link-type]:checked').val();

                    let imagesObj = {
                        srcs: $('.gallery-img'),
                        align: $('#gallery-align').val() == '' ? '' : 'align' + $('#gallery-align').val().replace('sirv-', ''),
                        profile: $('#gallery-profile').val() == false ? '' : $('#gallery-profile').val(),
                        width: isNaN(Number($('#gallery-width').val())) ? '' : Math.abs(Number($('#gallery-width').val())),
                        linkType: linkType,
                        customLink: linkType == 'url' ? $('#sirv-image-custom-link').val() :  '',
                        isBlankWindow: (linkType == 'large' || linkType == 'url') ? $('#sirv-image-link-blank-window').is(':checked') : false,
                        isLazyLoading: isLazyLoading,
                        //networkType: $('input[name=sirv-cdn]:checked').val(),
                        isAltCaption: $('#responsive-static-caption-as-alt').is(":checked"),
                        isResponsive: isResponsive,
                        isStatic: isStatic

                    };

                    srImagesAttr.isLazyLoading = isResponsive ? isLazyLoading : false;

                    html = getImagesHtml(imagesObj);
                }
            if(window.isSirvGutenberg && window.isSirvGutenberg == true){
                window.sirvHTML = html;
                generateGutenbergData(getShortcodeData(true), id, srImagesAttr);
            }else if(window.isSirvElementor && window.isSirvElementor == true){
                let jsonStr = JSON.stringify(getElementorData(getShortcodeData(true), id, srImagesAttr));
                //getElementorData(getShortcodeData(), id, srImagesAttr);

                let ifr = $('iframe#elementor-preview-iframe')[0];

                window.updateElementorSirvControl(jsonStr, false);
                window.isSirvElementor = false;

                setTimeout(function(){window.runEvent(ifr.contentWindow.document, 'updateSh');}, 1000);
            }else{
                if(typeof window.parent.send_to_editor === 'function'){
                    //some strange issue with firefox. If return empty string, than shortcode html block will broken. So return string only if not empty.
                    if(html != '') window.parent.send_to_editor(html);

                    //hack to show visualisation of shortcode or responsive images
                    if (!!window.parent.switchEditors) {
                        window.parent.switchEditors.go("content", "html");
                        window.parent.switchEditors.go("content", "tmce");
                    }
                }
            }

            $('.loading-ajax').hide();
            bPopup.close();
        }

        function parseUrl(url){
            let urlObj = document.createElement('a');
            urlObj.href = url;

            return urlObj;
        }


        function getSirvCdnUrl(url){
            if (!!sirv_ajax_object.sirv_cdn_url){
                urlInfo = parseUrl(url);
                url = 'https://' + sirv_ajax_object.sirv_cdn_url + urlInfo.pathname;
            }

            return url;
        }


        function getImagesHtml(data){
            let imagesHTML = '';
            let placehodler_grey = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAAAAAA6fptVAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAKSURBVAgdY3gPAADxAPAXl1qaAAAAAElFTkSuQmCC";
            let placeholder_grey_params = '?q=1&w=10&colorize.color=efefef';

            $.each(data.srcs, function(index, value){
                        let figure = document.createElement('figure');
                        let imgTag = document.createElement('img');
                        let imgSrc = getSirvCdnUrl($(value).attr('data-item-url'));
                        let title = $(this).parent().siblings('span').children().val();
                        let linkTag = '';
                        let img_width = $(this).attr('data-width');
                        let img_height = $(this).attr('data-height');

                        figure.classList.add('sirv-flx');
                        figure.classList.add('sirv-img-container');

                        if(data.align) figure.classList.add(data.align);
                        if(data.isResponsive){
                            imgTag.classList.add('Sirv');
                            if(img_width !== '0') imgTag.width = img_width;
                            if(img_height !== '0') imgTag.height = img_height;
                            if(data.width) figure.style["width"] = data.width + 'px';
                        }
                        imgTag.classList.add('sirv-img-container__img');
                        if(data.isStatic){
                            imgTag.src = imgSrc + generateOptionsUriStr({'profile': data.profile, 'w': data.width});
                            let size = calcImgSize(img_width, img_height, data.width);
                            imgTag.width = size.width;
                            imgTag.height = size.height;
                        }else{
                            if(!data.isLazyLoading) imgTag.setAttribute('data-options', 'autostart: created;');
                            imgTag.setAttribute('data-src', imgSrc + generateOptionsUriStr({'profile': data.profile}));
                            //imgTag.src = placehodler_grey;
                            imgTag.src = imgSrc + placeholder_grey_params;
                        }

                        imgTag.alt = title;
                        //imgTag.title = title;

                        /* linkType: linkType,
                        customLink: linkType == 'url' ? $('#sirv-image-custom-link').val() :  '',
                        isBlankWindow: */

                        if(data.linkType == 'large' || data.linkType == 'url'){
                            linkTag = document.createElement('a');
                            linkTag.classList.add('sirv-img-container__link');
                            if(data.linkType == 'large'){
                                linkTag.href = data.profile == '' ? imgSrc : imgSrc + generateOptionsUriStr({'profile': data.profile});
                            }else{
                                linkTag.href = data.customLink;
                            }

                            if(data.isBlankWindow){
                                linkTag.setAttribute('target', '_blank');
                            }

                            linkTag.appendChild(imgTag);
                            figure.appendChild(linkTag);
                        }else{
                            figure.appendChild(imgTag);
                        }

                        if(!data.isAltCaption && title){
                            let figCaption = document.createElement('figcaption');
                            figCaption.classList.add('sirv-img-container__cap');
                            //figCaption.textContent = title;
                            figCaption.innerHTML = removeNotAllowedHTMLTags(title);
                            figure.appendChild(figCaption);
                        }

                        imagesHTML += figure.outerHTML;
                    });
            return imagesHTML;
        }


        function calcImgSize(orig_width, orig_height, width){
            let size = {width: orig_width, height: orig_height};

            if(!!width){
                size.width = width;
                size.height = +width * calcProportion(orig_width, orig_height);
            }

            return size;
        }


        function calcProportion(width, height){
            return +height/+width;
        }


        function getElementorData(data, id, srImagesAttr={}){
            let images = data.images;
            let count = data.images.length;
            let type = id !== ''
                ? window.getShortcodeType(data)
                : srImagesAttr.isResponsive
                    ? 'Responsive images'
                    : 'Static images';
            let thumbParams = generateOptionsUriStr({profile: data.profile, thumbnail: 40, image: true});

            let tmpObj = {shortcode: {}, images: {}};

            let thumbImages = [];

            images.forEach( function(item, index) {
                let url = item.type == 'model' ? getItemPlaceHolder('model') : item.url + thumbParams;
                thumbImages.push(url);
            });

            if(id){
                tmpObj.shortcode.id = id;
                tmpObj.shortcode.count = count;
                tmpObj.shortcode.type = type;
                tmpObj.shortcode.images = thumbImages.slice(0, 4);

            }else{
                let width = isNaN(Number(data.width)) ? '' : Math.abs(data.width);
                let imgParams = !srImagesAttr.isResponsive && width
                    ? generateOptionsUriStr({profile: data.profile, w: data.width})
                    : generateOptionsUriStr({profile: data.profile});
                let profileParams = generateOptionsUriStr({profile: data.profile});
                let align = getAlign(data.align);
                let imagesData = [];

                tmpObj.images.full = {};

                tmpObj.images.thumbs = thumbImages;
                tmpObj.images.full.width = width;
                tmpObj.images.full.align = align;
                tmpObj.images.full.linkType = data.link_type;
                tmpObj.images.full.customLink = data.custom_link;
                tmpObj.images.full.isBlankWindow = data.is_blank_window;
                tmpObj.images.full.profile = data.profile;
                tmpObj.images.full.type = type;
                tmpObj.images.full.count = count;
                tmpObj.images.full.isResponsive = srImagesAttr.isResponsive;
                tmpObj.images.full.isLazyLoading = srImagesAttr.isLazyLoading;
                tmpObj.images.full.isAltCaption = data.isAltCaption;

                images.forEach( function(image, index) {
                    //imagesData.push(image.url + imgParams);
                    imagesData.push({
                        'origUrl': image.url + profileParams,
                        'modUrl' : image.url + imgParams,
                        'caption': image.caption,
                        'img_width' : image.image_width,
                        'img_height' : image.image_height,
                    });
                });
                tmpObj.images.full.imagesData = imagesData;


            }


            return tmpObj;

        }


        function getProfilesNames(){
            let profiles = [];
            $('#gallery-profile option:not([disabled=""])').each(function(index, el){
                if($(this).val() !== '') profiles.push($(this).val());
            });

            return profiles;
        }


        function generateGutenbergData(data, id, srImagesAttr={}){
            let images = data.images;
            let count = data.images.length;
            let shType = window.getShortcodeType(data);
            let width = ( !!!data.width || isNaN(data.width)) ? '' : Math.abs(data.width);
            let imgParams = generateOptionsUriStr({profile: data.profile});
            //let thumbParams = generateOptionsUriStr({profile: data.profile, thumbnail: 150, image: true});
            let imgThumbParams = generateOptionsUriStr({profile: data.profile, thumbnail: 150});
            let spinThumbParams = generateOptionsUriStr({profile: data.profile, image: true, w: 150, h: 150, 'canvas.width': 150, 'canvas.height': 150, 'scale.option': 'fit'});
            let tmpImages = [];
            let tmpImagesJson = [];
            let tmpSrImages = [];
            let tmpCount = count > 4 ? 4 : count;
            let align = '';


            if(id){
                for(let i = 0; i < tmpCount; i++){
                    let params = data.images[i].type == 'spin' ? spinThumbParams : imgThumbParams;
                    tmpImages.push({ 'src': data.images[i].url + params} );
                    let jsonItem = data.images[i].type == 'model' ? getItemPlaceHolder('model') : data.images[i].url + params;
                    tmpImagesJson.push(jsonItem);
                }
                align = data.align;
            }

            if(!id && images.length > 0){
                align = getAlign(data.align);

                for(let i = 0; i < images.length; i++){
                    tmpSrImages.push({
                        'src': data.images[i].url + imgParams,
                        'thumb': data.images[i].url + imgThumbParams,
                        'original': data.images[i].url,
                        'link': data.images[i].url + imgParams,
                        'alt': data.images[i].caption,
                        'width' : data.images[i].image_width,
                        'height' : data.images[i].image_height,
                    });
                }
            }

            window.sirvShObj = {
                sirvId: id,
                sirvType: shType,
                sirvCount: count,
                sirvImages: tmpImages,
                sirvImagesJson: JSON.stringify(tmpImagesJson),
                sirvSrImages: tmpSrImages,
                sirvAlign: align,
                sirvWidth: width,
                sirvIsResponsive: '' + srImagesAttr.isResponsive,
                sirvIsLazyLoading: '' + srImagesAttr.isLazyLoading,
                sirvIsLink: '' + data.custom_link == 'large',
                sirvLinkType: '' + data.link_type,
                sirvCustomLink: '' + data.custom_link,
                sirvIsBlankWindow: '' + data.is_blank_window,
                sirvIsAltCaption: '' + data.isAltCaption,
                sirvProfile: data.profile,
                sirvProfiles: JSON.stringify(getProfilesNames()),
            }
        }


        function getAlign(sirvAlign){
            align = '';
            switch (sirvAlign) {
                case 'sirv-left':
                    align = 'alignleft';
                    break;
                case 'sirv-center':
                    align = 'aligncenter';
                    break;
                case 'sirv-right':
                    align = 'alignright';
                    break;
                default:
                    align = '';
                    break;
            }

            return align;
        }


        function generateOptionsUriStr(optObject){
            let uriStr = '';
            let isFirst = true;
            $.each(optObject, function(key, element){
                if(element){
                    let delimiter = isFirst ? '?' : '&';
                    let pair = key == 'image' ? key : key + '=' + element;
                    uriStr += delimiter + pair;
                    isFirst = false;
                }
            });

            return uriStr;
        }


        function setFeaturedImage(){
            if($('.selected-miniature-img').length > 0){
                let selectedImage = $('.selected-miniature-img');
                let inputAnchor = $('#sirv-add-featured-image').attr('data-input-anchor');

                $(inputAnchor).val($(selectedImage).attr('data-item-url'));

                bPopup.close();
            }
        }


        function setWooProductImage(){

            let id = window.sirvProductID;

            if ($('.selected-miniature-img').length > 0) {
                $firstSelectedImage = $(".selected-miniature-img")[0];

                let productImage = $($firstSelectedImage).attr("data-item-url");

                let $storage = $("#sirv_woo_product_image_" + id);

                $storage.val(productImage);

                window.runEvent(window, 'update_woo_sirv_product_image');
            }

            bPopup.close();
        }


        function addWooSirvImages(){
            let items = [];

            id = window.sirvProductID;

            if ($('.selected-miniature-img').length > 0) {
                let selectedImages = $('.selected-miniature-img');
                $.each(selectedImages, function(index, img){
                    let url = $(img).attr('data-item-url');
                    let type = $(img).attr('data-item-type');
                    items.push({url: url, type: type, provider: 'sirv', order: index});
                });

                let $storage = $('#sirv_woo_gallery_data_'+ id);
                let data = JSON.parse($storage.val());

                data.items = data.items.concat(items);

                $storage.val(JSON.stringify(data));

                window.runEvent(window, 'update_woo_sirv_images');
            }

            bPopup.close();
        }


        function createGallery(){
            //showSearchResults(false);
            $('.selection-content').hide();
            $('.gallery-creation-content').show();
            imageSortable();

            if($('.selected-miniature-img').length > 0){
                let selectedImages = $('.selected-miniature-img');
                let documentFragment = $(document.createDocumentFragment());
                let addItem = $('<li class="gallery-item"><div class="sirv-add-item-wrapper sirv-no-select-text"><span class="dashicons dashicons-plus-alt2 sirv-add-icon"></span><span>Add items</span></div></li>\n');

                documentFragment.append(addItem);

                $.each(selectedImages, function(index, value){
                    const type = $(value).attr("data-item-type");
                    const url = $(value).attr("data-item-url");

                    let elemBlock = $('<li class="gallery-item"><div><div><a class="delete-image delete-image-icon" href="#" title="Remove"></a>'+
                        '<img class="gallery-img" src="' + getItemSrc(type, url, 150) +'"'+
                            ' data-item-id="'+ $(value).attr('data-item-id') +'"'+
                            'data-item-order="'+ index +'"'+
                            'data-item-url="'+ $(value).attr('data-item-url') +
                            '" data-item-type="'+ $(value).attr('data-item-type') +'" alt=""'+
                            ' title="' + basename($(value).attr('data-item-url')) + '"' +
                            'data-width="'+ $(value).attr('data-width') +'" '+
                            'data-height="'+ $(value).attr('data-height') +'">'+
                            '</div><span><input type="text" placeholder="Text caption.."'+
                            ' data-setting="caption" class="image-caption" value="'+ escapeHtml($(value).attr('data-caption')) +'" /></span></div></li>\n');
                    documentFragment.append(elemBlock);
                });

                $('.gallery-container').append(documentFragment);


                //bind events
                $('.delete-image').on('click', removeFromGalleryView);
                $('.select-images').on('click', function(){selectMoreImages(false)});
                $('.sirv-add-item-wrapper').on('click', function(){selectMoreImages(false)});
            }

            manageOptionsStates();

        }


        function removeFromGalleryView(){
            $(this).closest('li.gallery-item').remove();
            manageOptionsStates();
        }

        function clearGalleryView(){
            $('.gallery-container').empty();
        }


        function selectMoreImages(isEditGallery){
            $('.create-gallery>span').text('Add items');
            $('.gallery-creation-content').hide();
            $('.selection-content').show();
            restoreSelections(true);
            if(isEditGallery){
                //getData();
                getContentFromSirv();
            }
            clearGalleryView();
            $('.delete-image').off('click');
            $('.select-images').off('click');
            $('.sirv-add-item-wrapper').off('click');
        }

        function imageSortable(){

            function reCalcOrder(){
                $('.gallery-img').each(function(index){
                    $(this).attr('data-item-order', index);
                });
            }

            $( ".gallery-container" ).sortable({
                /*  revert: true,
                cursor: "move",
                scroll: false, */
                items: "> li:not(:first)",
                cursor: 'move',
                scrollSensitivity: 40,
                //forcePlaceholderSize: true,
                //forceHelperSize: false,
                //helper: 'clone',
                opacity: 0.65,
                scroll: false,
                //placeholder: "sirv-sortable-placeholder",
                stop: function( event, ui ) {
                    reCalcOrder();
                }
            });
        }



        function getShortcodeData(isHTMLBuilder=false){

            function getEmbededAsValue(value){
                let $gallery = $('.sirv-gallery-type[value=gallery-flag]'),
                    $zoom = $('.sirv-gallery-type[value=gallery-zoom-flag]'),
                    $spin = $('.sirv-gallery-type[value=360-spin]'),
                    $video = $('.sirv-gallery-type[value=video]'),
                    $model = $('.sirv-gallery-type[value=model]');
                switch(value){
                    case 'gallery-flag':
                        return ($gallery.is(':checked') || $zoom.is(':checked') || $spin.is(':checked') || $video.is(':checked') || $model.is(':checked')) ? true : false;
                        break;
                    case 'gallery-zoom-flag':
                        return $zoom.is(':checked') ? true : false;
                        break;
                }
            }

            let sirvGalleryType = getSirvType($('.gallery-img'));

            let shortcode_data = {}
            let tmp_data_options = {'zgallery_data_options':{}, 'spin_options':{}, 'global_options':{}, 'diff_options': {}};

            /* let isResponsive = $('.sirv-gallery-type[value=responsive-image]').is(':checked');
            let isStatic = $('.sirv-gallery-type[value=static-image]').is(':checked'); */

            //base DB params
            shortcode_data['width'] = $('#gallery-width').val();
            shortcode_data['thumbs_height'] = $('#gallery-thumbs-height').val();
            shortcode_data['gallery_styles'] = $('#gallery-styles').val();
            shortcode_data['align'] = $('#gallery-align').val();
            shortcode_data['profile'] = $('#gallery-profile').val();
            shortcode_data['use_as_gallery'] = getEmbededAsValue('gallery-flag');
            shortcode_data['use_sirv_zoom'] = getEmbededAsValue('gallery-zoom-flag');
            shortcode_data['show_caption'] = $('#gallery-show-caption').is(":checked");
            shortcode_data['isAltCaption'] = ($('input[name=sirv-alt-caption]:checked').val() == 'true');
            //backward compability, param do not use anymore v6.6.1
            shortcode_data['link_image'] = false;

            if(isHTMLBuilder){
                shortcode_data['link_type'] = $('input[name=sirv-image-link-type]:checked').val();
                shortcode_data["custom_link"] = $("#sirv-image-custom-link").val();
                shortcode_data['is_blank_window'] = $('#sirv-image-link-blank-window').is(":checked");
            }

            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('#gallery-thumbs-position').attr('data-option-name'), $('#gallery-thumbs-position').val());
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-thumb-shape]:checked').attr('data-option-name'), $('input[name=sirv-thumb-shape]:checked').val());
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-mousewheel-zoom]:checked').attr('data-option-name'), $('input[name=sirv-mousewheel-zoom]:checked').val());
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-fullscreen-only]:checked').attr('data-option-name'), $('input[name=sirv-fullscreen-only]:checked').val());
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-context-menu]:checked').attr('data-option-name'), $('input[name=sirv-context-menu]:checked').val());

            //video options
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-video-autoplay]:checked').attr('data-option-name'), $('input[name=sirv-video-autoplay]:checked').val());
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-video-loop]:checked').attr('data-option-name'), $('input[name=sirv-video-loop]:checked').val());
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-video-controls]:checked').attr('data-option-name'), $('input[name=sirv-video-controls]:checked').val());

            //spin options
            setDataOptionPair(tmp_data_options['spin_options'], $('input#spin-height').attr('data-option-name'), $('input#spin-height').val());
            setDataOptionPair(tmp_data_options['spin_options'], $('input[name=sirv-spin-autospin]:checked').attr('data-option-name'), $('input[name=sirv-spin-autospin]:checked').val());;
            setDataOptionPair(tmp_data_options['spin_options'], $('#sirv-spinrotation-duration').attr('data-option-name'), $('#sirv-spinrotation-duration').val());

            //model options
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-model-hint-finger]:checked').attr('data-option-name'), $('input[name=sirv-model-hint-finger]:checked').val());
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-model-autorotate]:checked').attr('data-option-name'), $('input[name=sirv-model-autorotate]:checked').val());
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-model-autorotate-speed]').attr('data-option-name'), $('input[name=sirv-model-autorotate-speed]').val());
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-model-autorotate-delay]').attr('data-option-name'), $('input[name=sirv-model-autorotate-delay]').val());
            setDataOptionPair(tmp_data_options['zgallery_data_options'], $('input[name=sirv-model-shadow-slider]').attr('data-option-name'), $('input[name=sirv-model-shadow-slider]').val());


            //global options
            setDataOptionPair(tmp_data_options['global_options'], 'sirvGalleryType', sirvGalleryType);
            setDataOptionPair(tmp_data_options['global_options'], 'shortcodeName', $('#shortcode-name').val());

            shortcode_data['shortcode_options'] = tmp_data_options;

            let images = []
            $('.gallery-img:visible').each(function(){
                let tmp = {};
                let url = $(this).attr('data-item-url');
                //tmp['url'] = tmp_url.replace(/http(?:s)*:/, '');
                tmp['url'] = getSirvCdnUrl(url);
                tmp['order'] = $(this).attr('data-item-order');
                tmp['caption'] = removeNotAllowedHTMLTags($(this).parent().siblings('span').children().val());
                tmp['type'] = $(this).attr('data-item-type');
                tmp['image_width'] = $(this).attr('data-width');
                tmp['image_height'] = $(this).attr('data-height');
                /* $.ajax({
                    url:  url + "?info",
                    type: 'GET',
                    dataType: 'json',
                    async: false
                }).done(function(imageData){
                    tmp['image_width'] = imageData.width;
                    tmp['image_height'] = imageData.height;
                }).fail(function(jqXHR, status, error){
                    tmp['image_width'] = 0;
                    tmp['image_height'] = 0;
                }); */
                images.push(tmp);
            });

            shortcode_data['images'] = images;

            return shortcode_data;
        }

        function setDataOptionPair(obj, key, value){
            obj[key] = value;
        }


        function removeNotAllowedHTMLTags(str){
            let pattern = /<(?!\/?(em|strong|b|i|br|a)(?=>|\s?.*>))\/?.*?>/ig;
            return str.replace(pattern, '');
        }


        function save_shorcode_to_db(action, row_id){

            row_id = row_id || -1;
            let id;
            let data = {
                        action: action,
                        shortcode_data: getShortcodeData()
            };

            if (row_id != -1) {
                data['row_id'] = row_id;
            };

            let ajaxData = {
                            url: sirv_ajax_object.ajaxurl,
                            type: 'POST',
                            async: false,
                            data: data
            };

            //processingOverlay='.loading-ajax'
            sendAjaxRequest(ajaxData, processingOverlay = '.loading-ajax', showingArea=false, isdebug=false, doneFn=function(response){
                id = response;
            });

            return id;
        }


        window['sirvEditGallery'] = function(id){
            $('.selection-content').hide();
            $('.gallery-creation-content').show();
            $('.edit-gallery>span').text('Save');
            $('.insert>span').text('Update');
            $('.select-images>span').text('Add items');
            $('.sirv-gallery-type[value=static-image]').attr('disabled', true);
            $('.sirv-gallery-type[value=responsive-image]').attr('disabled', true);
            imageSortable();

            //let id = window.top.sirv_sc_id;
            let data = {
                        action: 'sirv_get_row_by_id',
                        row_id: id
            }
            let ajaxData = {
                            url: ajaxurl,
                            type: 'POST',
                            data: data,
                            dataType: 'json'
            }

            sendAjaxRequest(ajaxData, processingOverlay='.loading-ajax', showingArea=false, isdebug=false, doneFn=function(response){
                profileTimer = window.setInterval(function(){setSelectedProfile('#gallery-profile', response);}, 100);

                $('#gallery-width').val(response['width']);
                $('#gallery-thumbs-height').val(response['thumbs_height']);
                $('#gallery-styles').val(response['gallery_styles']);
                $("#gallery-align").val(response['align']);

                if(typeof response.shortcode_options == 'object' && Object.keys(response.shortcode_options).length > 0){
                    $('#gallery-thumbs-position').val((response['shortcode_options']['zgallery_data_options']['thumbnails']));

                    let thumbShape = response['shortcode_options']['zgallery_data_options']['squareThumbnails'];
                    $('input[name=sirv-thumb-shape][value='+ thumbShape +']').prop('checked', true);

                    let mousewheelZoom = response['shortcode_options']['zgallery_data_options']['zoom-on-wheel'];
                    $('input[name=sirv-mousewheel-zoom][value='+ mousewheelZoom +']').prop('checked', true);

                    let fullscreenOnly = response['shortcode_options']['zgallery_data_options']['fullscreen-only'];
                    $('input[name=sirv-fullscreen-only][value='+ fullscreenOnly +']').prop('checked', true);

                    let contextMenu = response['shortcode_options']['zgallery_data_options']['contextmenu'];
                    $('input[name=sirv-context-menu][value='+ contextMenu +']').prop('checked', true);

                    let videoAutoplay = response['shortcode_options']['zgallery_data_options']['videoAutoplay'];
                    $('input[name=sirv-video-autoplay][value=' + videoAutoplay + ']').prop('checked', true);

                    let videoLoop = response['shortcode_options']['zgallery_data_options']['videoLoop'];
                    $('input[name=sirv-video-loop][value=' + videoLoop + ']').prop('checked', true);

                    let videoControls = response['shortcode_options']['zgallery_data_options']['videoControls'];
                    $('input[name=sirv-video-controls][value=' + videoControls + ']').prop('checked', true);

                    let spinHeight = response['shortcode_options']['spin_options']['spinHeight'];
                    $('input#spin-height').val(spinHeight);

                    let autospin = response['shortcode_options']['spin_options']['autospin'];
                    $('input[name=sirv-spin-autospin][value=' + autospin + ']').prop('checked', true);

                    let autospinSpeed = response['shortcode_options']['spin_options']['autospinSpeed'];
                    $('input[data-option-name=autospinSpeed]').val(autospinSpeed);

                    let shortcodeName = response['shortcode_options']['global_options']['shortcodeName'] || '';
                    $('#shortcode-name').val(shortcodeName);

                    let modelHintFinger = response['shortcode_options']['zgallery_data_options']['modelHintFinger'];
                    $('input[name=sirv-model-hint-finger][value=' + modelHintFinger + ']').prop('checked', true);

                    let modelAutorotate = response['shortcode_options']['zgallery_data_options']['modelAutorotate'];
                    $('input[name=sirv-model-autorotate][value=' + modelAutorotate + ']').prop('checked', true);

                    let modelAutorotateSpeed = response['shortcode_options']['zgallery_data_options']['modelAutorotateSpeed'];
                    $('input[name=sirv-model-autorotate-speed]').val(modelAutorotateSpeed);

                    let modelAutorotateDelay = response['shortcode_options']['zgallery_data_options']['modelAutorotateDelay'];
                    $('input[name=sirv-model-autorotate-delay]').val(modelAutorotateDelay);

                    let modelShadowSlider = response['shortcode_options']['zgallery_data_options']['modelShadowSlider'];
                    $('input[name=sirv-model-shadow-slider]').val(modelShadowSlider);
                    $("#sirv-shadow-slider-value").text(modelShadowSlider);

                    if(JSON.parse(response['use_sirv_zoom']) == true){
                        $('.sirv-gallery-type[value=gallery-zoom-flag]').prop('checked', true);
                    }else{
                        $('.sirv-gallery-type[value=gallery-flag]').prop('checked', true);
                    }
                }

                $('#gallery-flag').prop('checked', JSON.parse(response['use_as_gallery']));
                $('#gallery-zoom-flag').prop('checked', JSON.parse(response['use_sirv_zoom']));
                $('#gallery-link-img').prop('checked', JSON.parse(response['link_image']));
                $('#gallery-show-caption').prop('checked', JSON.parse(response['show_caption']));

                let images = response['images'];
                let documentFragment = $(document.createDocumentFragment());
                let addItem = $('<li class="gallery-item"><div class="sirv-add-item-wrapper sirv-no-select-text"><span class="dashicons dashicons-plus-alt2 sirv-add-icon"></span><span>Add items</span></div></li>\n');

                documentFragment.append(addItem);

                for(let i = 0; i < images.length; i++){
                    let caption = stripslashes(images[i]['caption']);

                    images[i]['url'] = unescaped(images[i]['url']);


                    let elemBlock = $('<li class="gallery-item"><div><div><a class="delete-image delete-image-icon" href="#" title="Remove"></a>'+
                        '<img class="gallery-img" src="'+ getItemSrc(images[i]['type'], images[i]['url'], 150) +'"'+
                            ' data-item-id="' + md5((images[i]['url']).replace('https:', '')) +'"'+
                            'data-item-order="'+ images[i]['order'] +'"'+
                            'data-item-url="'+ images[i]['url'] +
                            '" data-item-type="'+ images[i]['type'] +'" alt=""'+
                            'title="' + basename(images[i]['url']) +'"></div>'+
                            '<span><input type="text" placeholder="Text caption..."'+
                            ' data-setting="caption" class="image-caption" value="'+ caption +'" /></span></div></li>\n');
                    documentFragment.append(elemBlock);
                }

                $('.gallery-container').append(documentFragment);

                manageOptionsStates();
                manageThumbPosition();

                //bind events
                $('.delete-image').on('click', removeFromGalleryView);
                $('.select-images').on('click', function(){selectMoreImages(true)});
                $('.sirv-add-item-wrapper').on('click', function(){selectMoreImages(true)});
                $('.insert').on('click', insert);
                $('.sirv-gallery-type').on('change', manageOptionsStates);
                $('#gallery-thumbs-position').on('change', manageThumbPosition);
                $("input[name=sirv-model-autorotate]").on("click", manageModelAutorotate);
            });
        } //end sirvEditGallery


        function manageThumbPosition(){
            let selectedItem = $( "#gallery-thumbs-position option:selected" ).val();
            switch (selectedItem) {
                case 'left':
                case 'right':
                    $('.sirv-thumb-hw-text').html('Thumbnail width');
                    break;
                case 'bottom':
                    $('.sirv-thumb-hw-text').html('Thumbnail height');
                    break;
            }
        }


        function setSelectedProfile(selector, response){
            let profile = response['profile'] == " " ? "" : response['profile'];
            if($(selector + ' option').length > 0){
                $(selector).val(profile);
                window.clearInterval(profileTimer);
            }
        }


        function stripslashes(str) {
            str = str.replace(/\\'/g,'\'');
            str = str.replace(/\\"/g,'&quot;');
            str = str.replace(/\\0/g,'\0');
            str = str.replace(/\\\\/g,'\\');
            return str;
        }


        function unescaped(escapedStr){
            return escapedStr.replace(/\\?\\(\'|\")/g, '$1');
        }


        function getSirvType(gallery){
            let itemsTypes = [];
            let count = gallery.length;
            let type = 'empty';

            $.each(gallery, function (index, item) {
                itemsTypes.push($(item).attr('data-item-type'));
            });

            if(count == 0){
                type = 'empty';
            }

            if(count == 1){
                if(itemsTypes[0] == 'spin') type = 'spin';
                if(itemsTypes[0] == 'video') type = 'video';
                if(itemsTypes[0] == 'model') type = 'model';
                if(itemsTypes[0] == 'image') type = 'image';
            }

            if(count > 1){
                if (countItem(itemsTypes, 'image') > 0){
                    if (countItem(itemsTypes, 'image') == count){type = 'image'} else{type='gallery'};
                }
                //if (countItem(itemsTypes, 'spin') > 0 || countItem(itemsTypes, 'video') > 0) type = 'gallery';
                if (countItem(itemsTypes, 'spin') > 0) type = 'gallery';
                if (countItem(itemsTypes, 'video') > 0){
                    if (countItem(itemsTypes, 'video') == count){type = 'video'} else{type='gallery'};
                }
                if (countItem(itemsTypes, 'model') > 0){
                    if (countItem(itemsTypes, 'model') == count){type = 'model'} else{type='gallery'};
                }
            }
            return type;
        }


        function countItem(data, type){
            return data.filter(item => item == type).length;
        }

        function manageGalleryType(sirvGalleryType){
            switch (sirvGalleryType) {
                case 'empty':
                    break;
                case 'image':
                    $('.sirv-gallery-type').prop('disabled', false);
                    if ($('.sirv-gallery-type[value=360-spin]').is(':checked') || $('.sirv-gallery-type[value=video]').is(':checked')){
                        $('.sirv-gallery-type[value=responsive-image]').prop('checked', true);
                    }
                    manageElement($('#360-spin').parent(), 'hide');
                    manageElement($('#video').parent(), 'hide');
                    manageElement($("#model").parent(), "hide");
                    break;
                case 'spin':
                    $('.sirv-gallery-type').prop('disabled', true);
                    manageElement($('#360-spin').parent(), 'show');
                    manageElement($('#video').parent(), 'hide');
                    manageElement($("#model").parent(), "hide");
                    $('.sirv-gallery-type[value=360-spin]').prop('disabled', false);
                    $('.sirv-gallery-type[value=360-spin]').prop('checked', true);
                    break;
                case 'video':
                    $('.sirv-gallery-type').prop('disabled', true);
                    manageElement($('#360-spin').parent(), 'hide');
                    manageElement($('#model').parent(), 'hide');
                    manageElement($('#video').parent(), 'show');
                    $('.sirv-gallery-type[value=video]').prop('disabled', false);
                    $('.sirv-gallery-type[value=video]').prop('checked', true);
                    break;
                case 'model':
                    $('.sirv-gallery-type').prop('disabled', true);
                    manageElement($('#360-spin').parent(), 'hide');
                    manageElement($('#video').parent(), 'hide');
                    manageElement($('#model').parent(), 'show');
                    $('.sirv-gallery-type[value=model]').prop('disabled', false);
                    $('.sirv-gallery-type[value=model]').prop('checked', true);
                    break;
                case 'gallery':
                    $('.sirv-gallery-type').prop('disabled', true);
                    manageElement($('#360-spin').parent(), 'hide');
                    manageElement($('#video').parent(), 'hide');
                    manageElement($("#model").parent(), "hide");
                    $('.sirv-gallery-type[value=gallery-zoom-flag]').prop('disabled', false);
                    $('.sirv-gallery-type[value=gallery-flag]').prop('disabled', false);
                    if ($('.sirv-gallery-type[value=static-image]').is(':checked') ||
                        $('.sirv-gallery-type[value=responsive-image]').is(':checked') ||
                        $('.sirv-gallery-type[value=360-spin]').is(':checked') ||
                        $('.sirv-gallery-type[value=video]').is(':checked') ||
                        $('.sirv-gallery-type[value=model]').is(':checked')
                    ){
                        $('.sirv-gallery-type[value=gallery-zoom-flag]').prop('checked', true);
                    }
                    break;

                default:
                    break;
            }
        }


        function isType(type){
            let isType = false;
            $items = $(".gallery-img");
            $.each($items, function(){
                if($(this).attr('data-item-type') === type){
                    isType = true;
                }
            });

            return isType;
        }


        function manageOptionsStates(){

            if(window.isShortcodesPage !== null && window.isShortcodesPage == true){
                manageElement($('#responsive-image').parent(), 'hide');
                manageElement($('#static-image').parent(), 'hide');
                if(shGalleryFlag){
                    $('.sirv-gallery-type[value=gallery-zoom-flag]').prop('checked', true);
                    shGalleryFlag = false;
                }
            }

            let galleryLength = $('.gallery-img').length;

            let sirvGalleryType = getSirvType($('.gallery-img'));
            manageGalleryType(sirvGalleryType);

            if(galleryLength === 0){
                let isEditGallery = $('.insert').hasClass('edit-gallery') ? true : false;
                selectMoreImages(isEditGallery);

            }else if(galleryLength === 1){
                $('.gallery-zoom-flag-text').text('Zoom image');
                manageElement($('#gallery-flag').parent(), 'hide');

            }else if(galleryLength > 1){
                $('.gallery-zoom-flag-text').text('Zoom gallery');
                manageElement($('#gallery-flag').parent(), 'show');
            }

            if($('.insert').hasClass('edit-gallery')){
                $('.sirv-gallery-type[value=static-image]').attr('disabled', true);
                $('.sirv-gallery-type[value=responsive-image]').attr('disabled', true);
            }

            //-----------------managing options depends on selected type------------------------------
            if($('.sirv-gallery-type[value=gallery-flag]').is(':checked')){
                imgGallery = true;
                $('#gallery-styles').removeAttr("disabled");
                $('#gallery-align').removeAttr('disabled');

                let galleryType = ['gallery'];

                if(isType('spin')) galleryType.push('spin');
                if(isType('video')) galleryType.push('video');
                if(isType('model')) galleryType.push('model');
                manageOptionsByType(galleryType);

            }else if($('.sirv-gallery-type[value=gallery-zoom-flag]').is(':checked')){
                $('#gallery-styles').removeAttr("disabled");
                $('#gallery-align').removeAttr('disabled');


                let galleryType = ['zoom'];

                if (isType('spin')) galleryType.push('spin');
                if (isType("video")) galleryType.push("video");
                if (isType("model")) galleryType.push("model");
                manageOptionsByType(galleryType);

                if(galleryLength == 1){
                    manageElement($('#gallery-thumbs-height').parent(), 'hide');
                    manageElement($('#gallery-thumbs-position').parent(), 'hide');
                }

            }else if($('.sirv-gallery-type[value=static-image]').is(':checked')){
                /* $('#gallery-zoom-flag').attr('disabled', false)
                $('#gallery-zoom-flag').attr('checked', false); */
                $('#gallery-align').removeAttr('disabled');

                manageOptionsByType('static', galleryLength);

            }else if($('.sirv-gallery-type[value=responsive-image]').is(':checked')){
                /* $('#gallery-zoom-flag').attr('disabled', false)
                $('#gallery-zoom-flag').attr('checked', false); */
                if($('#gallery-width').val() == '') $('#gallery-align').attr('disabled', true);

                manageOptionsByType('responsive', galleryLength);

            }else if($('.sirv-gallery-type[value=360-spin]').is(':checked')){
                /* $('#gallery-zoom-flag').attr('disabled', true)
                $('#gallery-zoom-flag').attr('checked', false); */
                $('#gallery-styles').attr('disabled', true);
                $('#gallery-align').removeAttr('disabled');

                manageOptionsByType('spin');
            } else if ($('.sirv-gallery-type[value=video]').is(':checked')){
                manageOptionsByType('video');
            } else if ($('.sirv-gallery-type[value=model]').is(':checked')){
                manageOptionsByType('model');
            }
        }

        //change align disabled on input on Responsive images
        function onChangeWidthInputRI(){
            if($('.sirv-gallery-type[value=responsive-image]').is(':checked')){
                if($('#gallery-width').val() == ''){
                    $('#gallery-align').attr('disabled', true);
                }else{
                    $('#gallery-align').removeAttr('disabled');
                }
            }
        }


        function inArray(val, arr) {
            return arr.indexOf(val) !== -1;
        }


        //hide or show options by type
        function manageOptionsByType(types, galleryLength=null){
            if(typeof types === "string") types = [types];
            $('[data-option-type]').filter(function(){
                //change text on width field depends on type
                if(inArray('static', types)){$('#gallery-width').attr('placeholder', 'original');}else{$('#gallery-width').attr('placeholder', 'auto');}
                if(inArray('responsive', types)){$('.sirv-label-width').html("Max width (px)");}else{$('.sirv-label-width').html("Width (px)");};

                if(inArray('responsive', types) || inArray('static', types)){
                    if(!!galleryLength && galleryLength > 1){
                        $("input[name=sirv-image-link-type][value=url]").prop('disabled', true);
                        $("input[name=sirv-image-link-type][value=none]").prop('checked', true);
                        manageOptionLink();
                    }else{
                        $("input[name=sirv-image-link-type][value=url]").prop('disabled', false);
                    }
                }

                if(inArray('gallery', types) || inArray('zoom', types) || inArray('model', types)){
                    manageModelAutorotate();
                }

                let attrText = $(this).attr('data-option-type');
                let typeStr = types.join('|');
                let regex = `(${typeStr})`;
                let pattern = new RegExp(regex, "i");
                if(attrText.search(pattern) !== -1){
                    $(this).show();
                }else{
                    $(this).hide();
                }

                //hide thumb option if zoom image
                if($('.gallery-zoom-flag-text').text() == 'Zoom image') manageElement($('.sirv-thumb-shape'), 'hide');
            });
        }

        //hide or show element
        function manageElement($selector, action){
            switch (action) {
                case 'hide':
                    $selector.hide();
                    break;
                case 'show':
                    $selector.show();
                    break;
            }
        }


        function manageOptionLink(){
            //let state = $(this).val();
            let state = $('input[name=sirv-image-link-type]:checked').val();
            let $customUrl = $('#sirv-image-custom-link');
            let $blankWindowWrap = $('#sirv-image-link-blank-window').parent();
            if(state == 'url' || state == 'large'){
                $blankWindowWrap.show();
                if(state == 'url'){
                    $customUrl.show();
                }else{
                    $customUrl.hide();
                }
            }else{
                $blankWindowWrap.hide();
                $customUrl.hide();
            }
        }


        function manageModelAutorotate(){
            const isActive = $("input[name=sirv-model-autorotate]:checked").val() === "true";
            const $autorotateBlock = $(".sirv-model-autorotate-block");

            if(isActive){
                $autorotateBlock.show();
            }else{
                $autorotateBlock.hide();
            }
        }


        function setProfiles(){
            let data = {
                        action: 'sirv_get_profiles'
            }
            let ajaxData = {
                            url: ajaxurl,
                            type: 'POST',
                            data: data
            }

            sendAjaxRequest(ajaxData, processingOverlay='.loading-ajax', showingArea=false, isdebug=false, doneFn=function(response){
                $('#gallery-profile').empty();
                $('#gallery-profile').append($(response));
            });
        }


        function getPhpFilesLimitations(){
            let data = {
                        action: 'sirv_get_php_ini_data',
                        sirv_get_php_ini_data: true

            };


            let ajaxData = {
                            url: ajaxurl,
                            type: 'POST',
                            data: data
            }

            sendAjaxRequest(ajaxData, processingOverlay=false, showingArea=false, isdebug=false, function(response){
                let json_obj = JSON.parse(response);
                let tmpMaxPostSize = getPhpMaxPostSizeInBytes(json_obj.post_max_size);
                let tmpMaxFileSize = getPhpMaxPostSizeInBytes(json_obj.max_file_size);

                maxFilesCount = json_obj.max_file_uploads;
                maxFileSize = tmpMaxPostSize <= tmpMaxFileSize ? tmpMaxPostSize : tmpMaxFileSize;
                sirvFileSizeLimit = json_obj.sirv_file_size_limit;
            });

        }


        function getPhpMaxPostSizeInBytes(sizeParam){
            let size = parseInt(sizeParam.substr(0, sizeParam.length - 1));
            let sizeCapacity = sizeParam.substr(-1).toUpperCase();

            switch (sizeCapacity) {
                case 'G':
                    size *= 1024;
                case 'M':
                    size *= 1024;
                case 'K':
                    size *= 1024;
                    break;
            }

            return size;
        }


        function getFormatedFileSize(bytes) {
            let negativeFlag = false;
            let position = 0;
            let units = [" Bytes", " KB", " MB", " GB", " TB"];

            bytes = parseInt(bytes);

            if (bytes < 0) {
                bytes = Math.abs(bytes);
                negativeFlag = true;
            }

            while (bytes >= 1000 && (bytes / 1000) >= 1) {
                bytes /= 1000;
                position++;
            }

            if (negativeFlag) bytes *= -1;

            bytes = bytes % 1 == 0 ? bytes : bytes.toFixed(2);

            return bytes + units[position];

        }


        function sendAjaxRequest(AjaxData, processingOverlay=false, showingArea=false, isDebug=false, doneFn=false, beforeSendFn=false, errorFn=false){
            let isprocessingOverlay = typeof processingOverlay !== 'undefined' ? processingOverlay : false;
            let isShowingArea = typeof showingArea !== 'undefined' ? showingArea : false;

            AjaxData['beforeSend'] = function(){
                if(isprocessingOverlay){
                    $(processingOverlay).show();
                }
                if(typeof beforeSendFn == 'function') beforeSendFn();
            }

            $.ajax(AjaxData).done(
                function(response){
                    if(isDebug) console.log(response);
                    if(isShowingArea){
                        $(showingArea).html('');
                        $(showingArea).html(response);
                    }
                    if(isprocessingOverlay) $(processingOverlay).hide();
                    if(typeof doneFn == 'function') doneFn(response);
                }

            ).fail(function(jqXHR, status, error){
                    if (typeof errorFn == "function"){
                        errorFn(jqXHR, status, error);
                    }else{
                        console.error("Error during ajax request: " + error);
                        console.error("Status: " + status);
                        if (isShowingArea) {
                            $(showingArea).html("");
                            $(showingArea).html(error);
                        }

                        if (isprocessingOverlay) {
                            $(processingOverlay).hide();
                        }
                    }
                }
            );
        }


        function changeTab(e, $object){
            $('.sirv-tab-content').removeClass('sirv-tab-content-active');
            $('.nav-tab-wrapper > a').removeClass('nav-tab-active');
            $('.sirv-tab-content'+$object.attr('href')).addClass('sirv-tab-content-active');
            $object.addClass('nav-tab-active').trigger("blur");
            if(typeof e !== 'undefined') e.preventDefault();
        }

        // Initialization
        patchMediaBar();
        getPhpFilesLimitations();

        if($('.sirv-items-container').length > 0) getContentFromSirv();

    });
});
