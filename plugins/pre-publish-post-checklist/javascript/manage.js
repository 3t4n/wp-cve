//;
//(function ($) {
//    var deafultListData = [];
//    var editing = false;
//
//    /**
//     * Update a publish checklist setting
//     *
//     * @function updateSetting
//     * @param {String} key
//     * @param {String} value
//     */
//    function updateSetting (key, value) {
//        return new Promise (function (resolve, reject) {
//            // make ajax request to server
//            var data = {
//                'action': 'pc_ajax_update_settings',
//                'key': key,
//                'value': value
//            };
//
//            $.post(ajaxurl, data, function(response) {
//                resolve(response);
//            });
//        });
//    }
//
//    /**
//     * Return a promise for the array of items
//     *
//     * @function fetchListItems
//     * @param [listId]
//     */
//    function fetchListItems (listId) {
//        return new Promise(function (resolve, reject) {
//            var data = {
//                'action': 'pc_ajax_list_item'
//            };
//
//            $.ajax({
//                method: 'get',
//                url: ajaxurl,
//                dataType: "json",
//                data: data,
//                success: function (data, status, xhr) {
//                    resolve(data);
//                },
//                error: function () {
//                    reject();
//                }
//            });
//        });
//    }
//
//    /**
//     * Removes an item from a list
//     *
//     * @param itemId
//     * @returns {Promise}
//     */
//    function deleteChecklistItem (itemId) {
//        return new Promise(function (resolve, reject) {
//            var data = {
//                'action': 'pc_delete_list_item',
//                'itemId': itemId
//            };
//
//            $.ajax({
//                method: 'post',
//                url: ajaxurl,
//                dataType: "json",
//                data: data,
//                success: function (data, status, xhr) {
//                    resolve(data);
//                },
//                error: function () {
//                    reject();
//                }
//            });
//        });
//    }
//
//    function newChecklistItem (text) {
//        var el = $('<tr class="pc-checklist-item is-not-editing"><td><p class="is-not-editing">' + text + '</p><input type="text" class="is-editing list-item__description"/></td><td><button class="is-editing">Save</button><button class="button is-not-editing js-remove">Remove</button></td></tr>');
//
//        // fire off an ajax requests
//        // make ajax request to server
//        var data = {
//            'action': 'pc_ajax_list_item',
//            text: text
//        };
//
//        $.post(ajaxurl, data, function(response) {
//            debugger;
//        });
//
//        // if the user is editing an item...
//        if (editing === true) {
//            el.insertBefore('.pc-edit-list tbody tr:last-child');
//        } else {
//            $('.pc-edit-list tbody').prepend(el);
//        }
//    }
//
//    function startEditingExistingItem (e) {
//        var $checklistItem = $(e.currentTarget).closest('.pc-checklist-item');
//        var $input = $checklistItem.find('input[type=text]');
//        var text = $(e.currentTarget).text();
//
//        $input.val(text);
//        $checklistItem.removeClass('is-not-editing').addClass('is-editing');
//        $input.focus();
//
//
//
//
//
//        console.log('editing');
//        console.log(text);
//    }
//    $(document).on('click', '.pc-checklist-item p', startEditingExistingItem);
//
//
//    function newItemView () {
//        //var el = $('<tr><td><input class="list-item__description" type="text" placeholder="Item Description"/></td><td class="list-item__save"><p>Add</p></td></tr>');
//        var el = $('<tr class="pc-item--new"><td><input class="list-item__description" type="text" placeholder="New item description"/></td><td class="list-item__save"><button class="button button-primary">Save Item</button></td></tr>');
//
//        $('.pc-edit-list tbody').append(el);
//
//        editing = true;
//
//        el.find('input').focus();
//
//        el.find('button').one('click', function () {
//            var val = el.find('input').val().trim();
//
//            if (val.length > 0) {
//                console.log('create me');
//                newChecklistItem(val);
//                el.remove();
//                editing = false;
//            } else {
//                el.find('input').focus()
//            }
//        });
//
//        el.find('input').on('keydown', function (e) {
//            var val = el.find('input').val().trim();
//
//            if(e.keyCode === 13) {
//                if (val.length > 0) {
//                    newChecklistItem(el.find('input').val());
//                    el.remove();
//                    newItemView();
//                    editing = true;
//                } else {
//                    el.find('input').focus()
//                }
//            }
//        });
//    }
//
//    $(function () {
//        $('input[type=radio]').on('change', function (e) {
//            var val = $(this).val();
//            updateSetting('pc_on_publish', val).then(function () {
//                // setting save success
//                $('.save-widget').addClass('is-saved');
//                setTimeout(function () {
//                    $('.save-widget').removeClass('is-saved');
//                }, 2000);
//            });
//        });
//
//        $('.js-add-checklist-item').on('click', function () {
//            console.log('adding item');
//
//            if ($('.pc-edit-list .pc-item--new').length === 0) {
//                newItemView();
//            } else {
//                $('.pc-edit-list .pc-item--new input').focus()
//            }
//        });
//
//        $(document).on('click', '.js-remove', function () {
//            // get the id and remove it
//        });
//
//        fetchListItems().then(function (data) {
//            deafultListData = data;
//            data.forEach(function (item) {
//                var el = $('<tr class="pc-checklist-item is-not-editing" data-checklist-item="' + item.id + '"><td><p class="is-not-editing">' + item.description + '</p><input type="text" class="is-editing list-item__description"/></td><td><button class="button button-primary pc-full-width is-editing">Save</button><button class="button is-not-editing  js-remove">Remove</button></td></tr>');
//                $('.pc-edit-list').append(el);
//
//            });
//        }, function () {
//
//        });
//    });
//
//})(jQuery);