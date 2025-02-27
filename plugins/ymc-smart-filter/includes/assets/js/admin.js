;
(function( $ ) {
    "use strict"

    $(document).on('ready', function () {

        // Path preloader image
        const pathPreloader = _smart_filter_object.path+"/includes/assets/images/preloader.svg";

        // Wrapper tab
        const container   = $('.ymc__container-settings .tab-panel');

        document.querySelectorAll('.ymc__container-settings .nav-tabs .link').forEach((el) => {

            el.addEventListener('click',function (e) {
                e.preventDefault();

                let hash = this.hash;

                //let text = $(this).find('.text').text();
                //$('.ymc__header .manage-dash .title').text(text);

                $(el).addClass('active').closest('.nav-item').siblings().find('.link').removeClass('active');

                document.querySelectorAll('.tab-content .tab-panel').forEach((el) => {

                    if(hash === '#'+el.getAttribute('id')) {
                        $(el).addClass('active').siblings().removeClass('active');
                    }

                });

            });

        });

        // CPT Event
        $(document).on('change','.ymc__container-settings #general #ymc-cpt-select',function (e) {

            let taxonomyWrp = $('#ymc-tax-checkboxes');
            let termWrp     = $('#ymc-terms');
            let choicesList = $('#selection-posts .choices-list');
            let valuesList  = $('#selection-posts .values-list');
            let cpts = '';

            $("#ymc-cpt-select :selected").map(function(i, el) {
                cpts +=$(el).val()+',';
            });

            const data = {
                'action': 'ymc_get_taxonomy',
                'nonce_code' : _smart_filter_object.nonce,
                'cpt' : cpts.replace(/,\s*$/, ""),
                'post_id' : $(this).data('postid')
            };

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: _smart_filter_object.ajax_url,
                data: data,
                beforeSend: function () {
                    container.addClass('loading').
                    prepend(`<img class="preloader" src="${pathPreloader}">`);
                },
                success: function (res) {

                    container.removeClass('loading').find('.preloader').remove();

                    let dataTax = (JSON.parse(res.data));

                    // Get Taxonomies
                    if(Object.keys(dataTax).length > 0) {

                        taxonomyWrp.html('');
                        termWrp.html('').closest('.wrapper-terms').addClass('hidden');

                        for (let key in dataTax) {

                            taxonomyWrp.append(`<div id="${key}" class="group-elements" draggable="true">
                            <input id="id-${key}" type="checkbox" name="ymc-taxonomy[]" value="${key}">
                            <label for="id-${key}">${dataTax[key]}</label>
                            </div>`);
                        }
                    }
                    else  {

                        taxonomyWrp.html('').append(`<span class="notice">No data for Post Type / Taxonomy</span>`);
                        termWrp.html('').closest('.wrapper-terms').addClass('hidden');
                    }

                    // Get posts
                    let dataPosts = (JSON.parse(res.lists_posts));

                    valuesList.empty();
                    choicesList.empty();

                    if(Object.keys(dataPosts).length > 0) {
                        for (let key in dataPosts) {
                            choicesList.append(dataPosts[key]);
                        }
                    }
                    else {
                        choicesList.html(`<li class="notice">No posts</li>`);
                    }
                },
                error: function (obj, err) {
                    console.log( obj, err );
                }
            });
        });

        // Taxonomy Event
        $(document).on('click','.ymc__container-settings #general #ymc-tax-checkboxes input[type="checkbox"]',function (e) {

            let termWrp = $('#ymc-terms');

            let val = '';

            if($(e.target).is(':checked')) {

                val = $(e.target).val();

                const data = {
                    'action': 'ymc_get_terms',
                    'nonce_code' : _smart_filter_object.nonce,
                    'taxonomy' : val
                };

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: _smart_filter_object.ajax_url,
                    data: data,
                    beforeSend: function () {
                        container.addClass('loading').
                        prepend(`<img class="preloader" src="${pathPreloader}">`);
                    },
                    success: function (res) {

                        container.removeClass('loading').find('.preloader').remove();

                        if($(e.target).closest('.ymc-tax-checkboxes').find('input[type="checkbox"]:checked').length > 0) {
                            $('.ymc__container-settings #general .wrapper-terms').removeClass('hidden');
                        } else {
                            $('.ymc__container-settings #general .wrapper-terms').addClass('hidden');
                        }

                        // Get Terms
                        if( res.data.terms.length ) {

                            let output = '';

                            output += `<article class="group-term item-${val}">
                                       <div class="item-inner all-categories">
                                       <input name='all-select' class='category-all' id='category-all-${val}' type='checkbox'>
                                       <label for='category-all-${val}' class='category-all-label'>All [ ${$(e.target).siblings('label').text()} ]</label></div>
                                       <div class="entry-terms">`;

                            res.data.terms.forEach((el) => {
                                output += `<div class='item-inner' 
                                data-termid='${el.term_id}' 
                                data-alignterm 
                                data-bg-term 
                                data-color-term 
                                data-custom-class 
                                data-color-icon 
                                data-class-icon 
                                data-status-term >
                                <input name="ymc-terms[]" class="category-list" id="category-id-${el.term_id}" type="checkbox" value="${el.term_id}">
                                <label for='category-id-${el.term_id}' class='category-list-label'><span class="name-term">${el.name}</span> (${el.count})</label>
                                <i class="far fa-cog choice-icon" title="Setting term"></i>
                                <span class="indicator-icon"></span>                                
                                </div>`;
                            });

                            output += `</div></article>`;

                            termWrp.append(output);

                            output = '';

                            sortTerms();

                            updateSortTerms();
                        }
                        else  {
                            termWrp.append(`<article class="group-term item-${val}">
                            <div class='item-inner notice-error'>No terms for taxonomy <b>${$(e.target).siblings('label').text()}</b></div></article>`);
                        }
                    },
                    error: function (obj, err) {
                        console.log( obj, err );
                    }
                });
            }
            else {
                termWrp.find('.item-'+$(e.target).val()).remove();
            }

        });

       // Reload Taxonomy
        $(document).on('click','.ymc__container-settings #general .tax-reload',function (e) {
            $('.ymc__container-settings #general #ymc-cpt-select').trigger('change')
        });

        // Set Cookie
        function setCookie(cname, cvalue, exdays) {
            let d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        // Get Cookie
        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for(let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) === 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        // Drag & Drop Sort Taxonomy
        function sortTaxonomy() {

            let taxListElement = document.querySelector('#ymc-tax-checkboxes');

            if( taxListElement ) {

                let taxElements = taxListElement.querySelectorAll('.group-elements');

                for (let tax of taxElements) {
                    tax.draggable = true;
                }

                taxListElement.addEventListener('dragstart', (evt) => {
                    evt.target.classList.add('selected');
                });

                taxListElement.addEventListener('dragend', (evt) => {
                    evt.target.classList.remove('selected');

                    let arrTax = [];

                    taxListElement.querySelectorAll('.group-elements').forEach((el) => {
                        arrTax.push(el.id);
                    });

                    let data = {
                        'action': 'ymc_tax_sort',
                        'nonce_code' : _smart_filter_object.nonce,
                        'tax_sort' : JSON.stringify(arrTax),
                        'post_id' : taxListElement.dataset.postid
                    };

                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: _smart_filter_object.ajax_url,
                        data: data,
                        success: function (res) {},
                        error: function (obj, err) {
                            console.log( obj, err );
                        }
                    });
                });

                let getNextElement = (cursorPosition, currentElement) => {
                    let currentElementCoord = currentElement.getBoundingClientRect();
                    let currentElementCenter = currentElementCoord.y + currentElementCoord.height / 2;
                    return (cursorPosition < currentElementCenter) ?
                        currentElement :
                        currentElement.nextElementSibling;
                };

                taxListElement.addEventListener('dragover', (evt) => {
                    evt.preventDefault();

                    const activeElement = taxListElement.querySelector(`.selected`);

                    const currentElement = evt.target;

                    const isMoveable = activeElement !== currentElement &&
                        currentElement.classList.contains('group-elements');

                    if (!isMoveable) {
                        return;
                    }

                    const nextElement = getNextElement(evt.clientY, currentElement);

                    if (
                        nextElement &&
                        activeElement === nextElement.previousElementSibling ||
                        activeElement === nextElement
                    ) {
                        return;
                    }

                    taxListElement.insertBefore(activeElement, nextElement);
                });

            }

        }
        sortTaxonomy();

        // Drag & Drop Sort Terms
        function updateSortTerms() {

            let arrTerms = [];

            document.querySelectorAll('#ymc-terms .item-inner:not(.all-categories)').forEach((el) => {
                arrTerms.push(el.children[0].value);
            });

            let data = {
                'action': 'ymc_term_sort',
                'nonce_code' : _smart_filter_object.nonce,
                'term_sort' : JSON.stringify(arrTerms),
                'post_id' : document.querySelector('#ymc-terms').dataset.postid
            };

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: _smart_filter_object.ajax_url,
                data: data,
                success: function (res) {},
                error: function (obj, err) {
                    console.log( obj, err );
                }
            });
        }

        // Drag & Drop selected posts
        function sortSelectedPosts() {

            let postListElement = document.querySelector('#selection-posts .include-posts');

            if( postListElement ) {

                let postElement = postListElement.querySelectorAll('li');

                for (let post of postElement) {
                    post.draggable = true;
                }

                postListElement.addEventListener('dragstart', (evt) => {
                    evt.target.classList.add('selected');
                });
                postListElement.addEventListener('dragend', (evt) => {
                    evt.target.classList.remove('selected');
                    $('.include-posts li:not(.selected)').removeClass('over');
                });

                postListElement.addEventListener('drag', (evt) => {
                    $('.include-posts li:not(.selected)').addClass('over');
                });

                let getNextElement = (cursorPosition, currentElement) => {
                    let currentElementCoord = currentElement.getBoundingClientRect();
                    let currentElementCenter = currentElementCoord.y + currentElementCoord.height / 2;
                    return (cursorPosition < currentElementCenter) ?
                        currentElement :
                        currentElement.nextElementSibling;
                };

                postListElement.addEventListener('dragover', (evt) => {
                    evt.preventDefault();

                    const activeElement = postListElement.querySelector(`.selected`);

                    const currentElement = evt.target.parentNode;

                    const isMoveable = activeElement !== currentElement;

                    if (!isMoveable) {
                        return;
                    }

                    const nextElement = getNextElement(evt.clientY, currentElement);

                    if ( nextElement && activeElement === nextElement.previousElementSibling || activeElement === nextElement )
                    {
                        return;
                    }

                    evt.target.parentNode.parentNode.insertBefore(activeElement, nextElement);
                });
            }
        }
        sortSelectedPosts();

        function sortTerms() {

            let termListElement = document.querySelector('#ymc-terms');

            if( termListElement ) {

                let termElements = termListElement.querySelectorAll('.item-inner:not(.all-categories)');

                for (let term of termElements) {
                    term.draggable = true;
                }

                termListElement.querySelectorAll('.entry-terms').forEach((el) => {

                    el.addEventListener('dragstart', (evt) => {
                        evt.target.classList.add('selected');
                    });

                    el.addEventListener('dragend', (evt) => {
                        evt.target.classList.remove('selected');
                        updateSortTerms();
                    });
                });

                let getNextElement = (cursorPosition, currentElement) => {
                    let currentElementCoord = currentElement.getBoundingClientRect();
                    let currentElementCenter = currentElementCoord.y + currentElementCoord.height / 2;
                    return (cursorPosition < currentElementCenter) ?
                        currentElement :
                        currentElement.nextElementSibling;
                };

                termListElement.querySelectorAll('.entry-terms').forEach((el) => {
                    el.addEventListener('dragover', (evt) => {
                        evt.preventDefault();

                        const activeElement = termListElement.querySelector(`.selected`);

                        const currentElement = evt.target;

                        const isMoveable = activeElement !== currentElement &&
                            currentElement.classList.contains('item-inner');

                        if (!isMoveable) {
                            return;
                        }

                        const nextElement = getNextElement(evt.clientY, currentElement);

                        if (
                            nextElement &&
                            activeElement === nextElement.previousElementSibling ||
                            activeElement === nextElement
                        ) {
                            return;
                        }

                        evt.target.parentNode.insertBefore(activeElement, nextElement);
                    });
                });
            }
        }
        sortTerms();

        // Updated settings Icons
        function updatedOptionsIcons(e) {

            let arrOptionsIcons = [];

            // If click on button Align (popup)
            if( e ) {
                let termAlign = $(e.target).closest('.toggle-align-icon').data('align');
                $(e.target).closest('.toggle-align-icon').addClass('selected').siblings().removeClass('selected');
                document.querySelector('#ymc-terms .entry-terms .open-popup').dataset.alignterm = termAlign;
            }

            document.querySelectorAll('#ymc-terms .entry-terms .item-inner').forEach((el) => {
                let termId = el.dataset.termid;
                let termAlign = el.dataset.alignterm;
                let colorIcon =  el.dataset.colorIcon;
                let classIcon = el.dataset.classIcon;
                arrOptionsIcons.push({
                    "termid" : termId,
                    "alignterm" : termAlign,
                    "coloricon" : colorIcon,
                    "classicon" : classIcon
                });
            });

            const data = {
                'action' : 'ymc_options_icons',
                'nonce_code' : _smart_filter_object.nonce,
                'post_id' : $('#ymc-cpt-select').data('postid'),
                'params'  : JSON.stringify(arrOptionsIcons)
            };

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: _smart_filter_object.ajax_url,
                data: data,
                beforeSend: function () {
                    container.addClass('loading').
                    prepend(`<img class="preloader" src="${pathPreloader}">`);
                },
                success: function (res) {

                    container.removeClass('loading').find('.preloader').remove();

                    // If click on button Align (popup)
                    if( e ) {
                        $(e.target).closest('.toggle-align-icon').find('.note').css({'opacity':'1'});
                        setTimeout(() => {
                            $(e.target).closest('.toggle-align-icon').find('.note').css({'opacity':'0'});
                        },1000);
                    }
                },
                error: function (obj, err) {
                    console.log( obj, err );
                }
            });
        }

        // Updated settings Terms
        function updatedOptionsTerms() {

            let optionsTerms = [];

            document.querySelectorAll('#ymc-terms .entry-terms .item-inner').forEach((el) => {
                optionsTerms.push({
                    "termid"  : el.dataset.termid,
                    "bg"      : el.dataset.bgTerm,
                    "color"   : el.dataset.colorTerm,
                    "class"   : el.dataset.customClass,
                    "status"  : el.dataset.statusTerm
                });
            });

            const data = {
                'action' : 'ymc_options_terms',
                'nonce_code' : _smart_filter_object.nonce,
                'post_id' : $('#ymc-cpt-select').data('postid'),
                'params'  : JSON.stringify(optionsTerms)
            };

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: _smart_filter_object.ajax_url,
                data: data,
                beforeSend: function () {
                    container.addClass('loading').
                    prepend(`<img class="preloader" src="${pathPreloader}">`);
                },
                success: function (res) {
                    container.removeClass('loading').find('.preloader').remove();

                    $('#ymc-terms .entry-terms .open-popup').removeClass('open-popup');

                    tb_remove();
                },
                error: function (obj, err) {
                    console.log( obj, err );
                }
            });

        }
        
        // Checked Selected Term
        function checkedSelectedTerm(e) {

            let elem = $(e.target);

            if( elem.is(':checked') ) {
                elem.attr('checked','checked').closest('.item-inner').attr('data-status-term','checked');
            }
            else {
                elem.removeAttr('checked').closest('.item-inner').attr('data-status-term','');
            }

            updatedOptionsTerms();
        }

        // Export Settings
        function exportSettings() {

            const data = {
                'action': 'ymc_export_settings',
                'nonce_code' : _smart_filter_object.nonce,
                'post_id' : $('#ymc-cpt-select').data('postid')
            };

            $.ajax({
                type: 'POST',
                dataType: 'binary',
                xhrFields: {
                    'responseType': 'blob'
                },
                url: _smart_filter_object.ajax_url,
                data: data,
                beforeSend: function () {
                    container.addClass('loading').
                    prepend(`<img class="preloader" src="${pathPreloader}">`);
                },
                success: function (res) {

                    container.removeClass('loading').find('.preloader').remove();

                    let fullYear = new Date().getFullYear();
                    let day = new Date().getDate();
                    let month = new Date().getMonth();
                    let hour = new Date().getHours();
                    let minutes = new Date().getMinutes();
                    let seconds = new Date().getSeconds();

                    let link = document.createElement('a');
                    let filename = 'ymc-export-'+day+'-'+month+'-'+fullYear+'-'+hour+':'+minutes+':'+seconds+'.json';

                    let url = window.URL.createObjectURL(res);
                    link.href = url;
                    link.download = filename;
                    link.click();
                    link.remove();
                    window.URL.revokeObjectURL(url);

                },
                error: function (obj, err) {
                    console.log( obj, err );
                }
            });
        }

        // Import Settings
        function importSettings() {

            let input = document.querySelector('.ymc__container-settings #tools input[type="file"]');
            let infoUploaded =  document.querySelector('.ymc__container-settings #tools .info-uploaded');
            let file = input.files[0];

            $(infoUploaded).empty();

            if( input.files.length > 0 ) {

                if( file.type === "application/json" && file.name.indexOf('ymc-export-') === 0 ) {

                    let reader = new FileReader();

                    reader.readAsText(file);

                    reader.onload = function() {

                        let data = {
                            'action': 'ymc_import_settings',
                            'nonce_code' : _smart_filter_object.nonce,
                            'post_id' : $('#ymc-cpt-select').data('postid'),
                            'params' : reader.result
                        };

                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: _smart_filter_object.ajax_url,
                            data: data,
                            beforeSend: function () {
                                container.addClass('loading').
                                prepend(`<img class="preloader" src="${pathPreloader}">`);
                            },
                            success: function (res) {
                                container.removeClass('loading').find('.preloader').remove();
                                $(infoUploaded).html(res.mesg + ' <a href="javascript:;" onclick="location.reload();">Reload page</a>');

                                if( res.status === 1 ) {
                                    $(infoUploaded).addClass('info-uploaded--seccess').removeClass('info-uploaded--error');
                                    input.value = '';
                                }
                                else {
                                    $(infoUploaded).removeClass('info-uploaded--seccess').addClass('info-uploaded--error');
                                }
                            },
                            error: function (obj, err) {
                                console.log( obj, err );
                            }
                        })
                    };

                    reader.onerror = function() {
                        console.error(reader.error);
                    };
                }
                else {
                    $(infoUploaded).html('Incorrect type file');
                    $(infoUploaded).removeClass('info-uploaded--seccess').addClass('info-uploaded--error');
                    throw new Error("Incorrect type file");
                }
            }


        }

        // Choices Posts
        $('.wrapper-selection .ymc-exclude-posts').on('click', function (e) {

            let listItems = $('.selection-posts .values .values-list');

            if($(e.target).prop('checked')) {
                listItems.removeClass('include-posts').addClass('exclude-posts');
            }
            else {
                listItems.removeClass('exclude-posts').addClass('include-posts');
            }
        });

        $(document).on('click','#selection-posts .choices-list .ymc-rel-item-add', function (e) {

            let postID = e.target.dataset.id;
            let titlePosts = e.target.innerText;
            e.target.classList.add('disabled');

            let valuesList = $('#selection-posts .values-list');
            let numberPosts = $('#selection-posts .number-selected-posts');
            valuesList.addClass('include-posts');

            valuesList.append(`<li><input type="hidden" name="ymc-choices-posts[]" value="${postID}">
					<span  class="ymc-rel-item" data-id="${postID}">${titlePosts}
                    <a href="#" class="ymc-icon-minus remove_item"></a>
                    </span></li>`);

            numberPosts.html(valuesList.find('li').length);

            sortSelectedPosts();
        });

        $(document).on('click','#selection-posts .values-list .remove_item', function (e) {
            e.preventDefault();

            let postID = $(e.target).closest('.ymc-rel-item').data('id');
            let numberPosts = $('#selection-posts .number-selected-posts');
            let valuesList = $('#selection-posts .values-list');

            $('#selection-posts .choices-list .ymc-rel-item-add').each(function (){
                if( postID === $(this).data('id')) {
                    $(this).removeClass('disabled');
                }
            });

            if( $(e.target).closest('.values-list').find('li').length - 1 === 0 ) {

                const data = {
                    'action': 'ymc_delete_choices_posts',
                    'nonce_code' : _smart_filter_object.nonce,
                    'post_id' : $('#ymc-cpt-select').data('postid')
                };

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: _smart_filter_object.ajax_url,
                    data: data,
                    beforeSend: function () {
                        container.addClass('loading').
                        prepend(`<img class="preloader" src="${pathPreloader}">`);
                    },
                    success: function (res) {
                        container.removeClass('loading').find('.preloader').remove();
                    },
                    error: function (obj, err) {
                        console.log( obj, err );
                    }
                });

            }

            $(e.target).closest('li').remove();

            numberPosts.html(valuesList.find('li').length);

        });

        // Search Posts in Choices Box
        $(document).on('input','#general .search-posts input[type="search"]', function (e) {

            let keyword = e.target.value.toLowerCase();

            let arrWords = [];

            if( keyword.length >= 3 ) {

                document.querySelectorAll('.selection-posts .choices-list li').forEach((el) => {

                    let text = $(el).find('.ymc-rel-item').text().toLowerCase();

                    if( text.includes(keyword) ) {
                        arrWords.push(el);
                    }

                    if( arrWords.length > 0 ) {
                        arrWords.forEach((elem) => {
                            elem.classList.add('result');
                        });
                        $('.selection-posts .choices-list li:not(.result)').hide();
                        $('.selection-posts .choices-list li.result').show();
                    }
                    else {
                        $('.selection-posts .choices-list li').hide();
                    }
                });
            }
            else {
                $('.selection-posts .choices-list li').removeClass('result').show();
            }
        });

        // Open Popup for Settings Term & Icons
        $(document).on('click','#general #ymc-terms .entry-terms .choice-icon', function (e) {

            $('#ymc-terms .entry-terms .item-inner').removeClass('open-popup');

            $(e.target).closest('.item-inner').addClass('open-popup');

            let nameTerm = $(e.target).siblings('.category-list-label').find('.name-term').text();
            let alignterm = e.target.closest('.item-inner').dataset.alignterm;
            let bgTerm = e.target.closest('.item-inner').dataset.bgTerm;
            let colorTerm = e.target.closest('.item-inner').dataset.colorTerm;
            let customClass = e.target.closest('.item-inner').dataset.customClass || '';
            let colorIcon = e.target.closest('.item-inner').dataset.colorIcon || '#3c434a';
            let newIcon = $(e.target).siblings('.indicator-icon').find('i').clone(true).css('color',colorIcon);

            // Run popup
            tb_show( '&#9998; &#91;'+nameTerm+'&#93;', '/?TB_inline&inlineId=ymc-icons-modal&width=740&height=768' );

            // Get elements in popup
            let iconCurrentColor = $('#TB_ajaxContent .ymc-icons-content .ymc-icon-color');
            let iconCurrentClass = $('#TB_ajaxContent .ymc-terms-content .terms-entry .ymc-term-class');
            let termCurrentBg = $('#TB_ajaxContent .ymc-terms-content .terms-entry .ymc-term-bg');
            let termCurrentColor = $('#TB_ajaxContent .ymc-terms-content .terms-entry .ymc-term-color');


            if( newIcon.length > 0 ) {
                $( '#TB_ajaxContent .ymc-icons-content .panel-setting .remove-link' ).show();
                $( '#TB_ajaxContent .ymc-icons-content .panel-setting .preview-icon' ).html(newIcon);
            }
            else {
                $( '#TB_ajaxContent .ymc-icons-content .panel-setting .remove-link' ).hide();
                $( '#TB_ajaxContent .ymc-icons-content .panel-setting .preview-icon' ).empty();
            }

            $('#TB_ajaxContent .ymc-icons-content .panel-setting .toggle-align-icon[data-align="'+alignterm+'"]').
                addClass('selected').siblings().removeClass('selected');

            // Set current settings
            termCurrentBg.wpColorPicker('color', bgTerm);
            termCurrentColor.wpColorPicker('color', colorTerm);
            iconCurrentColor.wpColorPicker('color', colorIcon);
            iconCurrentClass.val(customClass);

            // Change color icon
            let options = {
                change: function(event, ui){
                    // automattic.github.io/Iris
                    let previewIcon = $('#TB_ajaxContent .ymc-icons-content .panel-setting .preview-icon i');
                    previewIcon.css({'color':`${ui.color.toString()}`});
                },
            }
            iconCurrentColor.wpColorPicker(options);
        });

        // Add Icon
        $(document).on('click','#TB_ajaxContent .ymc-icons-content .icons-entry i', function (e) {

            let classIcon = $(e.target).attr('class');
            let selectedTerm = $('#ymc-terms .entry-terms .open-popup');
            let colorIcon = $('#TB_ajaxContent .ymc-icons-content .panel-setting .ymc-icon-color').val() || '#3c434a';
            let previewIcon = $('#TB_ajaxContent .ymc-icons-content .panel-setting .preview-icon');
            let removeBtn = $('#TB_ajaxContent .ymc-icons-content .panel-setting .remove-link');
            let termId = selectedTerm.data('termid');
            let iconHtml = `<i class="${classIcon}" style="color: ${colorIcon};"></i>
                                  <input name="ymc-terms-icons[${termId}]" type="hidden" value="${classIcon}">`;


            selectedTerm.attr('data-class-icon', classIcon)
            selectedTerm.find('.indicator-icon').html(iconHtml);

            previewIcon.html(`<i class="${classIcon}" style="color: ${colorIcon};"></i>`);

            removeBtn.show();

            $('#TB_ajaxContent .ymc-icons-content .icons-entry i').removeClass('result').show();
            $('#TB_ajaxContent .ymc-icons-content .panel-setting input[type="search"]').val('');

            //tb_remove();
        });

        // Remove icon
        $(document).on('click','#TB_ajaxContent .ymc-icons-content .remove-link', function (e) {

            $('#ymc-terms .entry-terms .open-popup .indicator-icon').
               empty().closest('.item-inner').attr('data-color-icon','').attr('data-class-icon','').removeClass('open-popup');

            updatedOptionsIcons();

            // If no icons for terms
            if ( $('#ymc-terms .entry-terms .indicator-icon').find('input').length === 0 ) {

                const data = {
                    'action': 'ymc_delete_choices_icons',
                    'nonce_code' : _smart_filter_object.nonce,
                    'post_id' : $('#ymc-cpt-select').data('postid')
                };

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: _smart_filter_object.ajax_url,
                    data: data,
                    beforeSend: function () {
                        container.addClass('loading').
                        prepend(`<img class="preloader" src="${pathPreloader}">`);
                    },
                    success: function (res) {
                        container.removeClass('loading').find('.preloader').remove();
                    },
                    error: function (obj, err) {
                        console.log( obj, err );
                    }
                });
            }

            tb_remove();
        });

        // Search Icon
        $(document).on('input','#TB_ajaxContent .ymc-icons-content input[type="search"]', function (e) {

            let keyword = e.target.value.toLowerCase();
            let arrIcons = [];

            if( keyword.length >= 3 ) {

                document.querySelectorAll('#TB_ajaxContent .ymc-icons-content .icons-entry i').forEach((el) => {

                    let nameClass = $(el).attr('class').replace(/[\s.-]/g, ' ');

                    if(nameClass.includes(keyword)) {
                        arrIcons.push(el);
                    }
                });

                if( arrIcons.length > 0 ) {
                    arrIcons.forEach((elem) => {
                        elem.classList.add('result');
                    });
                    $('#TB_ajaxContent .ymc-icons-content .icons-entry i:not(.result)').hide();
                    $('#TB_ajaxContent .ymc-icons-content .icons-entry i.result').show();
                }
                else {
                    $('#TB_ajaxContent .ymc-icons-content .icons-entry i').hide();
                }
            }
            else {
                $('#TB_ajaxContent .ymc-icons-content .icons-entry i').removeClass('result').show();
            }

        });

        // Set align icon for Terms
        $(document).on('click','#TB_ajaxContent .ymc-icons-content .panel-setting .align-icon .toggle-align-icon', function (e) {
            e.preventDefault();
            updatedOptionsIcons(e);
        });

        // Tabs popup settings Terms
        $(document).on('click','#TB_ajaxContent .tabs .tab .tab-inner', function (e) {

            let _self = $(e.target);
            let content = e.target.dataset.content;
            let iconContent = $('#TB_ajaxContent .ymc-icons-content');
            let termContent = $('#TB_ajaxContent .ymc-terms-content');

            _self.closest('.tab').addClass('active').siblings().removeClass('active');

            if( content === 'icon' ) {
                iconContent.addClass('ymc-visible').removeClass('ymc-hidden');
                termContent.addClass('ymc-hidden').removeClass('ymc-visible');
            }
            else {
                iconContent.addClass('ymc-hidden').removeClass('ymc-visible');
                termContent.addClass('ymc-visible').removeClass('ymc-hidden');
            }

        });

        // Save Settings Terms & Icons
        $(document).on('click','#TB_ajaxContent .btn-apply', function (e) {
            e.preventDefault();

            let postId = $('#ymc-cpt-select').data('postid');
            let bgTerm = $('#TB_ajaxContent .ymc-terms-content .ymc-term-bg').val();
            let colorTerm = $('#TB_ajaxContent .ymc-terms-content .ymc-term-color').val();
            let customClass = $('#TB_ajaxContent .ymc-terms-content .ymc-term-class').val();
            let colorIcon = $('#TB_ajaxContent .ymc-icons-content .panel-setting .ymc-icon-color').val();
            let selectedTerm = document.querySelector('#ymc-terms .entry-terms .open-popup');

            selectedTerm.dataset.bgTerm = bgTerm;
            selectedTerm.dataset.colorTerm = colorTerm;
            selectedTerm.dataset.customClass = customClass;
            selectedTerm.dataset.colorIcon = colorIcon;

            ( bgTerm || colorTerm ) ?
                selectedTerm.setAttribute('style',`background-color: ${bgTerm}; color: ${colorTerm}`) :
                selectedTerm.removeAttribute('style');


            document.querySelector('#ymc-terms .entry-terms .open-popup').dataset.colorIcon = colorIcon;
            $(selectedTerm).find('.indicator-icon i').attr('style',`color: ${colorIcon}`);

            updatedOptionsIcons();
            updatedOptionsTerms();

        });

        // Selected All Terms
        $(document).on('click','.ymc__container-settings #general #ymc-terms .all-categories input[type="checkbox"]',function (e) {

            let input = $(e.target);

            let checkbox = input.closest('.all-categories').siblings().find('input[type="checkbox"]');

            if( input.is(':checked') ) {

                if( ! checkbox.is(':checked') ) {
                    checkbox.prop( "checked", true );
                }
            }
            else  {
                checkbox.prop( "checked", false );
            }
        });

        // Updated list posts in choices box
        $(document).on('click','.ymc__container-settings #general #ymc-terms input[type="checkbox"]',function (e) {

            // Run updated terms options
            checkedSelectedTerm(e);

            let cpt = document.querySelector('#ymc-cpt-select').value;
            let arrTax = [];
            let arrTerms = [];
            let numberPosts = document.querySelector('#selection-posts .number-posts');
            let choicesPosts = document.querySelector('#selection-posts .list');

            // Terms
            document.querySelectorAll('#ymc-terms .item-inner:not(.all-categories)').forEach((el) => {
                let chbox = el.children[0];
                if( chbox.checked ) {
                    arrTerms.push(chbox.value);
                }
            });

            // Tax
            document.querySelectorAll('.wrapper-taxonomy .ymc-tax-checkboxes .group-elements').forEach((el) => {
                let chbox = el.children[0];
                if( chbox.checked ) {
                    arrTax.push(chbox.value);
                }
            });

            const data = {
                'action': 'ymc_updated_posts',
                'nonce_code' : _smart_filter_object.nonce,
                'cpt' : cpt,
                'tax' : JSON.stringify(arrTax),
                'terms' : JSON.stringify(arrTerms),
            };

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: _smart_filter_object.ajax_url,
                data: data,
                beforeSend: function () {
                    container.addClass('loading').
                    prepend(`<img class="preloader" src="${pathPreloader}">`);
                },
                success: function (res) {

                    container.removeClass('loading').find('.preloader').remove();

                    if( res.output ) {
                        choicesPosts.innerHTML = res.output;
                        numberPosts.innerHTML = res.found;
                    }
                    else {
                        choicesPosts.innerHTML = '';
                    }
                },
                error: function (obj, err) {
                    console.log( obj, err );
                }
            });
        });

        // Set checkbox All marked
        $('#general #ymc-terms .group-term').each(function () {
            let total = $(this).find('input[type="checkbox"]').length - 1;
            let totalChecked = $(this).find('input[checked]').length;
            if(total === totalChecked) {
                $(this).find('.all-categories input[type="checkbox"]').attr('checked','checked');
            }
        });

        // Toggle Filter Status
        $(document).on('click', '.ymc__container-settings .ymc-toggle-group .slider', function (e) {

            let input = $(e.target).siblings('input');

           // ( input.is(':checked') ) ? input.siblings('input[type="hidden"]').val('on') : input.siblings('input[type="hidden"]').val('off');

            if(input.is(':checked')) {
                input.siblings('input[type="hidden"]').val('on').closest('.form-group').find('.manage-filters').show();
            }
            else  {
                input.siblings('input[type="hidden"]').val('off').closest('.form-group').find('.manage-filters').hide();
            }

        });

        // Sort by Fields
        $('.appearance-section #ymc-order-post-by').change(function(e) {
            let metaSort = $(e.target).closest('.from-element').siblings('.from-element--meta-sort');
            let multipleSort = $(e.target).closest('.from-element').siblings('.from-element--multiple-sort');
            let orderSort = $(e.target).closest('.from-element').siblings('.from-element--order-sort');

            metaSort.hide();
            multipleSort.hide();
            orderSort.show();

            switch ( this.value ) {

                case 'meta_key' : metaSort.show();  break;

                case 'multiple_fields' : multipleSort.show(); orderSort.hide(); break;

            }
        });

        // Event handler Add Multiple Fields
        $('.appearance-section .from-element--multiple-sort .btnAddMultipleSort').click(function (e) {
            let length = $(e.target).closest('.from-element').find('.rows-options').length;
            let rowCloneHtml = $($(e.target).closest('.from-element').find('.rows-options')[length - 1]).clone(true);
            $(e.target).closest('.from-element').find('.ymc-btn').before(rowCloneHtml);

           let newItem = $($(e.target).closest('.from-element').find('.rows-options')[length]);
           newItem.find('.ymc-multiple-orderby').attr('name','ymc-multiple-sort['+length+'][orderby]');
           newItem.find('.ymc-multiple-order').attr('name','ymc-multiple-sort['+length+'][order]');

            $(this).siblings('.btnRemoveMultipleSort').show();
        });

        // Event handler Remove Multiple Fields
        $('.appearance-section .from-element--multiple-sort .btnRemoveMultipleSort').click(function (e) {
            let length = $(e.target).closest('.from-element').find('.rows-options').length;

            if( length > 1 ) {
                $($(e.target).closest('.from-element').find('.rows-options')[length - 1]).remove();
            }
            if( length - 1 === 1 ) {
                $(this).hide();
            }
        });

        // Set Style Preloader
        $(document).on('change', '#advanced #ymc-preloader-icon', function (e) {
            let preloaderURL = _smart_filter_object.path + "/includes/assets/images/" + $(this).val() + '.svg';
            $(this).closest('#ymc-preloader-icon').next('.preview-preloader').find('img').attr('src', preloaderURL);
        });

        // Apply Filters for Preloader Icon
        $(document).on('change', '#advanced #ymc-filter-preloader', function (e) {

            let filter = e.target.value;
            let filterRate = document.querySelector('#advanced .filter-rate');
            let filterCustom = document.querySelector('#advanced .filters-custom');
            let preview = document.querySelector('#advanced .preview-preloader img');
            let rate = document.querySelector('#advanced .range-wrapper input[type="range"]');

            if( filter !== 'custom_filters' && filter !== 'none' ) {
                preview.setAttribute('style', `filter: ${filter}(${rate.value})`);
                filterRate.classList.remove('ymc_hidden');
                filterCustom.classList.add('ymc_hidden');
            }
            else if( filter === 'none' ) {
                filterRate.classList.add('ymc_hidden');
                filterCustom.classList.add('ymc_hidden');
            }
            else {
                filterRate.classList.add('ymc_hidden');
                filterCustom.classList.remove('ymc_hidden');
            }
        });

        // Change Coefficient for Preloader Icon
        $(document).on('input', '#advanced #ymc-filter-rate', function (e) {
            let rate = e.target.value;
            let filter = document.querySelector('#advanced #ymc-filter-preloader');
            let preview = document.querySelector('#advanced .preview-preloader img');
            preview.setAttribute('style', `filter: ${filter.value}(${rate})`);
        });

        // Add custom filters for Preloader Icon
        $(document).on('input', '#advanced #ymc-filters-custom', function (e) {
            let filters = e.target.value;
            let preview = document.querySelector('#advanced .preview-preloader img');
            preview.setAttribute('style', filters);
        });

        // Export As JSON
        $(document).on('click', '.ymc__container-settings #tools .button-export', exportSettings);

        // Import JSON
        $(document).on('click', '.ymc__container-settings #tools .button-import', importSettings);

        // Custom Type Query
        $(document).on('change', '.ymc__container-settings #advanced .type-query .ymc-query-type', function (e) {

            let className = e.target.value;

            document.querySelectorAll('.ymc__container-settings #advanced .type-query-content').forEach((el) => {

                let _elem = $(el);
                ( _elem.hasClass(className) ) ? _elem.show() : _elem.hide();
            });
        });

        // Select Post Layout
        $(document).on('change', '.ymc__container-settings #layouts #ymc-post-layout', function (e) {

            let postLayout = e.target.value;
            let columnLayoutSection = $('.ymc__container-settings #layouts .column-layout__section');
            // Array Post Layouts for Breakpoints
            let arr_layouts_posts = [
                'post-layout1',
                'post-layout2',
                'post-custom-layout'
            ];

            ( arr_layouts_posts.includes(postLayout) ) ? columnLayoutSection.show()  :  columnLayoutSection.hide();

        });

        // Set Cookie for Tab
        $(".ymc__container-settings #ymcTab a").click(function(e) {
            let hashUrl = $(this).attr('href');
            setCookie("hashymc", hashUrl,30);
        });

        // Display selected tab
        if(getCookie("hashymc") !== '') {

            let hash = getCookie("hashymc");

            $('.ymc__container-settings .nav-tabs a[href="' + hash + '"]').
                addClass('active').
                closest('.nav-item').
                siblings().
                find('.link').
                removeClass('active');

            document.querySelectorAll('.tab-content .tab-panel').forEach((el) => {
                if(hash === '#'+el.getAttribute('id')) {
                    $(el).addClass('active').siblings().removeClass('active');
                }
            });
        }

        // Add Color Picker for all inputs
        $('.ymc-custom-color').wpColorPicker();

    });

}( jQuery ));