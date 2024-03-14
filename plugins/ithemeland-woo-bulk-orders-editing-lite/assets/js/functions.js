"use strict";

var wobelOpenFullScreenIcon = '<i class="wobel-icon-enlarge"></i>';
var wobelCloseFullScreenIcon = '<i class="wobel-icon-shrink"></i>';

function openFullscreen() {
    if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
        document.documentElement.webkitRequestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
        document.documentElement.msRequestFullscreen();
    }
}

function wobelDataTableFixSize() {
    jQuery('#wobel-main').css({
        top: jQuery('#wpadminbar').height() + 'px',
        "padding-left": (jQuery('#adminmenu:visible').length) ? jQuery('#adminmenu').width() + 'px' : 0
    });

    jQuery('#wobel-loading').css({
        top: jQuery('#wpadminbar').height() + 'px',
    });

    let height = parseInt(jQuery(window).height()) - parseInt(jQuery('#wobel-header').height() + 85);

    jQuery('.wobel-table').css({
        "max-height": height + 'px'
    });
}

function exitFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    }
}

function wobelFullscreenHandler() {
    if (!document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
        jQuery('#wobel-full-screen').html(wobelOpenFullScreenIcon).attr('title', 'Full screen');
        jQuery('#adminmenuback, #adminmenuwrap').show();
        jQuery('#wpcontent, #wpfooter').css({ "margin-left": "160px" });
    } else {
        jQuery('#wobel-full-screen').html(wobelCloseFullScreenIcon).attr('title', 'Exit Full screen');
        jQuery('#adminmenuback, #adminmenuwrap').hide();
        jQuery('#wpcontent, #wpfooter').css({ "margin-left": 0 });
    }

    wobelDataTableFixSize();
}

function wobelOpenTab(item) {
    let wobelTabItem = item;
    let wobelParentContent = wobelTabItem.closest(".wobel-tabs-list");
    let wobelParentContentID = wobelParentContent.attr("data-content-id");
    let wobelDataBox = wobelTabItem.attr("data-content");
    wobelParentContent.find("li a.selected").removeClass("selected");
    if (wobelTabItem.closest('.wobel-sub-tab').length > 0) {
        wobelTabItem.closest('li.wobel-has-sub-tab').find('a').first().addClass("selected");
    } else {
        wobelTabItem.addClass("selected");
    }

    if (item.closest('.wobel-tabs-list').attr('data-content-id') && item.closest('.wobel-tabs-list').attr('data-content-id') == 'wobel-main-tabs-contents') {
        jQuery('.wobel-tabs-list[data-content-id="wobel-main-tabs-contents"] li[data-depend] a').not('.wobel-tab-item').addClass('disabled');
        jQuery('.wobel-tabs-list[data-content-id="wobel-main-tabs-contents"] li[data-depend="' + wobelDataBox + '"] a').removeClass('disabled');
    }

    jQuery("#" + wobelParentContentID).children("div.selected").removeClass("selected");
    jQuery("#" + wobelParentContentID + " div[data-content=" + wobelDataBox + "]").addClass("selected");

    if (item.attr("data-type") === "main-tab") {
        wobelFilterFormClose();
    }
}

function wobelFixModalHeight(modal) {
    if (!modal.attr('data-height-fixed') || modal.attr('data-height-fixed') != 'true') {
        let footerHeight = 0;
        let contentHeight = modal.find(".wobel-modal-content").height();
        let titleHeight = modal.find(".wobel-modal-title").height();
        if (modal.find(".wobel-modal-footer").length > 0) {
            footerHeight = modal.find(".wobel-modal-footer").height();
        }

        let modalMargin = parseInt((parseInt(jQuery('body').height()) * 20) / 100);
        let bodyHeight = (modal.find(".wobel-modal-body-content").length) ? parseInt(modal.find(".wobel-modal-body-content").height() + 30) : contentHeight;
        let bodyMaxHeight = parseInt(jQuery('body').height()) - (titleHeight + footerHeight + modalMargin);
        if (modal.find('.wobel-modal-top-search').length > 0) {
            bodyHeight += parseInt(modal.find('.wobel-modal-top-search').height() + 30);
            bodyMaxHeight -= parseInt(modal.find('.wobel-modal-top-search').height());
        }

        modal.find(".wobel-modal-content").css({
            "height": parseInt(titleHeight + footerHeight + bodyHeight) + 'px'
        });
        modal.find(".wobel-modal-body").css({
            "height": parseInt(bodyHeight) + 'px',
            'max-height': parseInt(bodyMaxHeight) + 'px'
        });
        modal.find(".wobel-modal-box").css({
            "height": parseInt(titleHeight + footerHeight + bodyHeight) + 'px'
        });
        modal.attr('data-height-fixed', 'true');
    }
}

function wobelOpenFloatSideModal(targetId) {
    let modal = jQuery(targetId);
    modal.fadeIn(20);
    modal.find(".wobel-float-side-modal-box").animate({
        right: 0
    }, 180);
}

function wobelCloseFloatSideModal() {
    // fix conflict with "Woo Invoice Pro" plugin
    jQuery('body').removeClass('_winvoice-modal-open');
    jQuery('._winvoice-modal-backdrop').remove();

    jQuery('.wobel-float-side-modal-box').animate({
        right: "-80%"
    }, 180);
    jQuery('.wobel-float-side-modal').fadeOut(200);
}

function wobelCloseModal() {
    // fix conflict with "Woo Invoice Pro" plugin
    jQuery('body').removeClass('_winvoice-modal-open');
    jQuery('._winvoice-modal-backdrop').remove();

    let lastModalOpened = jQuery('#wobel-last-modal-opened');
    let modal = jQuery(lastModalOpened.val());
    if (lastModalOpened.val() !== '') {
        modal.find(' .wobel-modal-box').fadeOut();
        modal.fadeOut();
        lastModalOpened.val('');
    } else {
        let lastModal = jQuery('.wobel-modal:visible').last();
        lastModal.find('.wobel-modal-box').fadeOut();
        lastModal.fadeOut();
    }

    setTimeout(function () {
        modal.find('.wobel-modal-box').css({
            height: 'auto',
            "max-height": '80%'
        });
        modal.find('.wobel-modal-body').css({
            height: 'auto',
            "max-height": '90%'
        });
        modal.find('.wobel-modal-content').css({
            height: 'auto',
            "max-height": '92%'
        });
    }, 400);
}

function wobelOpenModal(targetId) {
    let modal = jQuery(targetId);
    modal.fadeIn();
    modal.find(".wobel-modal-box").fadeIn();
    jQuery("#wobel-last-modal-opened").val(jQuery(this).attr("data-target"));

    // set height for modal body
    setTimeout(function () {
        wobelFixModalHeight(modal);
    }, 150)
}

function wobelReInitColorPicker() {
    if (jQuery('.wobel-color-picker').length > 0) {
        jQuery('.wobel-color-picker').wpColorPicker();
    }
    if (jQuery('.wobel-color-picker-field').length > 0) {
        jQuery('.wobel-color-picker-field').wpColorPicker();
    }
}

function wobelReInitDatePicker() {
    if (jQuery.fn.datetimepicker) {
        jQuery('.wobel-datepicker-with-dash').datetimepicker('destroy');
        jQuery('.wobel-datepicker').datetimepicker('destroy');
        jQuery('.wobel-timepicker').datetimepicker('destroy');
        jQuery('.wobel-datetimepicker').datetimepicker('destroy');

        jQuery('.wobel-datepicker').datetimepicker({
            timepicker: false,
            format: 'Y/m/d',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wobel-datepicker-with-dash').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wobel-timepicker').datetimepicker({
            datepicker: false,
            format: 'H:i',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wobel-datetimepicker').datetimepicker({
            format: 'Y/m/d H:i',
            scrollMonth: false,
            scrollInput: false
        });
    }

}

function wobelPaginationLoadingStart() {
    jQuery('.wobel-pagination-loading').show();
}

function wobelPaginationLoadingEnd() {
    jQuery('.wobel-pagination-loading').hide();
}

function wobelLoadingStart() {
    jQuery('#wobel-loading').removeClass('wobel-loading-error').removeClass('wobel-loading-success').text('Loading ...').slideDown(300);
}

function wobelLoadingSuccess(message = 'Success !') {
    jQuery('#wobel-loading').removeClass('wobel-loading-error').addClass('wobel-loading-success').text(message).delay(1500).slideUp(200);
}

function wobelLoadingError(message = 'Error !') {
    jQuery('#wobel-loading').removeClass('wobel-loading-success').addClass('wobel-loading-error').text(message).delay(1500).slideUp(200);
}

function wobelSetColorPickerTitle() {
    jQuery('.wobel-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.wobel-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wobelSetTipsyTooltip();
    });
}

function wobelFilterFormClose() {
    if (jQuery('#wobel-filter-form-content').attr('data-visibility') === 'visible') {
        jQuery('.wobel-filter-form-icon').addClass('wobel-icon-chevron-down').removeClass('wobel-icon-chevron-up');
        jQuery('#wobel-filter-form-content').slideUp(200).attr('data-visibility', 'hidden');
    }
}

function wobelSetTipsyTooltip() {
    jQuery('[title]').tipsy({
        html: true,
        arrowWidth: 10, //arrow css border-width * 2, default is 5 * 2
        attr: 'data-tipsy',
        cls: null,
        duration: 150,
        offset: 7,
        position: 'top-center',
        trigger: 'hover',
        onShow: null,
        onHide: null
    });
}

function wobelCheckUndoRedoStatus(reverted, history) {
    if (reverted) {
        wobelEnableRedo();
    } else {
        wobelDisableRedo();
    }
    if (history) {
        wobelEnableUndo();
    } else {
        wobelDisableUndo();
    }
}

function wobelDisableUndo() {
    jQuery('#wobel-bulk-edit-undo').attr('disabled', 'disabled');
}

function wobelEnableUndo() {
    jQuery('#wobel-bulk-edit-undo').prop('disabled', false);
}

function wobelDisableRedo() {
    jQuery('#wobel-bulk-edit-redo').attr('disabled', 'disabled');
}

function wobelEnableRedo() {
    jQuery('#wobel-bulk-edit-redo').prop('disabled', false);
}

function wobelHideSelectionTools() {
    jQuery('.wobel-bulk-edit-form-selection-tools').hide();
    jQuery('#wobel-bulk-edit-trash-restore').hide();
}

function wobelShowSelectionTools() {
    jQuery('.wobel-bulk-edit-form-selection-tools').show();
    jQuery('#wobel-bulk-edit-trash-restore').show();
}

function wobelSetColorPickerTitle() {
    jQuery('.wobel-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.wobel-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wobelSetTipsyTooltip();
    });
}

function wobelColumnManagerAddField(fieldName, fieldLabel, action) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wobel_column_manager_add_field',
            field_name: fieldName,
            field_label: fieldLabel,
            field_action: action
        },
        success: function (response) {
            jQuery('.wobel-box-loading').hide();
            jQuery('.wobel-column-manager-added-fields[data-action=' + action + '] .items').append(response);
            fieldName.forEach(function (name) {
                jQuery('.wobel-column-manager-available-fields[data-action=' + action + '] input:checkbox[data-name=' + name + ']').prop('checked', false).closest('li').attr('data-added', 'true').hide();
            });
            wobelReInitColorPicker();
            jQuery('.wobel-column-manager-check-all-fields-btn[data-action=' + action + '] input:checkbox').prop('checked', false);
            jQuery('.wobel-column-manager-check-all-fields-btn[data-action=' + action + '] span').removeClass('selected').text('Select All');
            setTimeout(function () {
                wobelSetColorPickerTitle();
            }, 250);
        },
        error: function () {
        }
    })
}

function wobelAddMetaKeysManual(meta_key_name) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wobel_add_meta_keys_manual',
            meta_key_name: meta_key_name,
        },
        success: function (response) {
            jQuery('#wobel-meta-fields-items').append(response);
            wobelLoadingSuccess();
        },
        error: function () {
            wobelLoadingError();
        }
    })
}

function wobelAddACFMetaField(field_name, field_label, field_type) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wobel_add_acf_meta_field',
            field_name: field_name,
            field_label: field_label,
            field_type: field_type
        },
        success: function (response) {
            jQuery('#wobel-meta-fields-items').append(response);
            wobelLoadingSuccess();
        },
        error: function () {
            wobelLoadingError();
        }
    })
}

function wobelCheckFilterFormChanges() {
    let isChanged = false;
    jQuery('#wobel-filter-form-content [data-field="value"]').each(function () {
        if (jQuery.isArray(jQuery(this).val())) {
            if (jQuery(this).val().length > 0) {
                isChanged = true;
            }
        } else {
            if (jQuery(this).val()) {
                isChanged = true;
            }
        }
    });
    jQuery('#wobel-filter-form-content [data-field="from"]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });
    jQuery('#wobel-filter-form-content [data-field="to"]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });

    jQuery('#filter-form-changed').val(isChanged);

    if (isChanged === true) {
        jQuery('#wobel-bulk-edit-reset-filter').show();
    } else {
        jQuery('.wobel-top-nav-status-filter a[data-status="all"]').addClass('active');
    }
}

function wobelGetCheckedItem() {
    let itemIds;
    let itemsChecked = jQuery("input.wobel-check-item:checkbox:checked");
    if (itemsChecked.length > 0) {
        itemIds = itemsChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }

    return itemIds;
}

function wobelGetTableCount(countPerPage, currentPage, total) {
    currentPage = (currentPage) ? currentPage : 1;
    let showingTo = parseInt(currentPage * countPerPage);
    let showingFrom = (total > 0) ? parseInt(showingTo - countPerPage) + 1 : 0;
    showingTo = (showingTo < total) ? showingTo : total;
    return "Showing " + showingFrom + " to " + showingTo + " of " + total + " entries";
}

function wobelGetOrdersChecked() {
    let orderIds = [];
    let ordersChecked = jQuery("input.wobel-check-item:checkbox:checked");
    if (ordersChecked.length > 0) {
        orderIds = ordersChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }
    return orderIds;
}

function wobelReloadOrders(edited_ids = [], current_page = wobelGetCurrentPage()) {
    let data = wobelGetCurrentFilterData();
    wobelOrdersFilter(data, 'pro_search', edited_ids, current_page);
}

function wobelReloadRows(orders, statuses) {
    let currentStatus = (jQuery('#wobel-filter-form-product-status').val());

    jQuery('tr').removeClass('wobel-item-edited').find('.wobel-check-item').prop('checked', false);
    if (Object.keys(orders).length > 0) {
        jQuery.each(orders, function (key, val) {
            if (statuses[key] === currentStatus || (!currentStatus && statuses[key] !== 'trash')) {
                jQuery('#wobel-items-list').find('tr[data-item-id="' + key + '"]').replaceWith(val);
                jQuery('tr[data-item-id="' + key + '"]').addClass('wobel-item-edited').find('.wobel-check-item').prop('checked', true);
            } else {
                jQuery('#wobel-items-list').find('tr[data-item-id="' + key + '"]').remove();
            }
        });
        wobelShowSelectionTools();
    } else {
        wobelHideSelectionTools();
    }
}

function wobelCheckResetFilterButton() {
    if (jQuery('#wobel-bulk-edit-filter-tabs-contents [data-field="value"]').length > 0) {
        jQuery('#wobel-bulk-edit-filter-tabs-contents [data-field="value"]').each(function () {
            if (jQuery(this).val() != '') {
                jQuery('.wobel-reset-filter-form').closest('li').show();
                return true;
            }
        });
    }
}

function wobelOrdersFilter(data, action, edited_ids = null, page = wobelGetCurrentPage()) {
    // clear selected orders in export tab
    jQuery('#wobel-export-items-selected').html('');

    if (action === 'pagination') {
        wobelPaginationLoadingStart();
    } else {
        wobelLoadingStart();
    }
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_orders_filter',
            filter_data: data,
            current_page: page,
            search_action: action,
        },
        success: function (response) {
            if (response.success) {
                wobelLoadingSuccess();
                wobelSetOrdersList(response, edited_ids)
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelOrdersFilter(data, action, edited_ids = null, page = wobelGetCurrentPage()) {
    // clear selected orders in export tab
    jQuery('#wobel-export-items-selected').html('');

    if (action === 'pagination') {
        wobelPaginationLoadingStart();
    } else {
        wobelLoadingStart();
    }
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_orders_filter',
            filter_data: data,
            current_page: page,
            search_action: action,
        },
        success: function (response) {
            if (response.success) {
                wobelLoadingSuccess();
                wobelSetOrdersList(response, edited_ids)
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelSetOrdersList(response, edited_ids = null) {
    jQuery('#wobel-items-table').html(response.orders_list);
    jQuery('.wobel-items-pagination').html(response.pagination);
    jQuery('.wobel-items-count').html(wobelGetTableCount(jQuery('#wobel-quick-per-page').val(), wobelGetCurrentPage(), response.orders_count));
    wobelSetStatusFilter(response.status_filters)

    wobelReInitDatePicker();
    wobelReInitColorPicker();

    if (edited_ids && edited_ids.length > 0) {
        jQuery('tr').removeClass('wobel-item-edited');
        edited_ids.forEach(function (orderID) {
            jQuery('tr[data-item-id=' + orderID + ']').addClass('wobel-item-edited');
            jQuery('input[value=' + orderID + ']').prop('checked', true);
        });
        wobelShowSelectionTools();
    } else {
        wobelHideSelectionTools();
    }

    wobelSetTipsyTooltip();
    setTimeout(function () {
        let maxHeightScrollWrapper = jQuery('.scroll-wrapper > .scroll-content').css('max-height');
        jQuery('.scroll-wrapper > .scroll-content').css({
            'max-height': (parseInt(maxHeightScrollWrapper) + 5)
        });

        let actionColumn = jQuery('td.wobel-action-column');
        if (actionColumn.length > 0) {
            actionColumn.each(function () {
                jQuery(this).css({
                    "min-width": (parseInt(jQuery(this).find('a').length) * 45)
                })
            });
        }
    }, 500);
}

function wobelGetOrderData(orderID) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_get_order_data',
            order_id: orderID
        },
        success: function (response) {
            if (response.success) {
                wobelSetOrderDataBulkEditForm(response.order_data);
            } else {

            }
        },
        error: function () {

        }
    });
}

function wobelGetOrderChecked() {
    let orderIds = [];
    let ordersChecked = jQuery("input.wobel-check-item:checkbox:checked");
    if (ordersChecked.length > 0) {
        orderIds = ordersChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }
    return orderIds;
}

function wobelDeleteOrder(orderIDs, deleteType) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_delete_orders',
            order_ids: orderIDs,
            delete_type: deleteType,
            filter_data: wobelGetCurrentFilterData(),
        },
        success: function (response) {
            if (response.success) {
                wobelReloadOrders();
                wobelHideSelectionTools();
                wobelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wobel-history-items tbody').html(response.history_items);
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelRestoreOrder(orderIds) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_untrash_orders',
            order_ids: orderIds,
        },
        success: function (response) {
            if (response.success) {
                wobelReloadOrders();
                wobelHideSelectionTools();
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelEmptyTrash() {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_empty_trash',
        },
        success: function (response) {
            if (response.success) {
                wobelReloadOrders();
                wobelHideSelectionTools();
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelDuplicateOrder(orderIDs, duplicateNumber) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_duplicate_order',
            order_ids: orderIDs,
            duplicate_number: duplicateNumber
        },
        success: function (response) {
            if (response.success) {
                wobelReloadOrders([], wobelGetCurrentPage());
                wobelCloseModal();
                wobelHideSelectionTools();
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelCreateNewOrder(count = 1) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_create_new_order',
            count: count
        },
        success: function (response) {
            if (response.success) {
                wobelReloadOrders(response.order_ids, 1);
                wobelCloseModal();
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelSaveColumnProfile(presetKey, items, type) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_save_column_profile',
            preset_key: presetKey,
            items: items,
            type: type
        },
        success: function (response) {
            if (response.success) {
                wobelLoadingSuccess();
                location.href = location.href.replace(location.hash, "");
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelLoadFilterProfile(presetKey) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_load_filter_profile',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wobelResetFilterForm();
                setTimeout(function () {
                    setFilterValues(response);
                }, 500);
                wobelLoadingSuccess();
                wobelSetOrdersList(response);
                wobelCloseModal();
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelDeleteFilterProfile(presetKey) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_delete_filter_profile',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wobelLoadingSuccess();
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelFilterProfileChangeUseAlways(presetKey) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_filter_profile_change_use_always',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wobelLoadingSuccess();
            } else {
                wobelLoadingError()
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelGetCurrentFilterData() {
    return (jQuery('#wobel-quick-search-text').val()) ? wobelGetQuickSearchData() : wobelGetProSearchData()
}

function wobelResetQuickSearchForm() {
    jQuery('.wobel-top-nav-filters-search input').val('');
    jQuery('.wobel-top-nav-filters-search select').prop('selectedIndex', 0);
    jQuery('#wobel-quick-search-reset').hide();
}

function wobelResetFilterForm() {
    jQuery('#wobel-float-side-modal-filter input').val('');
    jQuery('#wobel-float-side-modal-filter textarea').val('');
    jQuery('#wobel-float-side-modal-filter select').prop('selectedIndex', 0);
    jQuery('#wobel-float-side-modal-filter .wobel-select2').val(null).trigger('change');
    jQuery('#wobel-float-side-modal-filter .wobel-select2-products').val(null).trigger('change');
    jQuery('#wobel-float-side-modal-filter .wobel-select2-tags').val(null).trigger('change');
    jQuery('#wobel-float-side-modal-filter .wobel-select2-categories').val(null).trigger('change');
    jQuery('#wobel-float-side-modal-filter .wobel-select2-taxonomies').val(null).trigger('change');
    jQuery('.wobel-bulk-edit-status-filter-item').removeClass('active');
    jQuery('.wobel-bulk-edit-status-filter-item[data-status="all"]').addClass('active');
}

function wobelResetFilters() {
    wobelResetFilterForm();
    wobelResetQuickSearchForm();
    jQuery(".wobel-filter-profiles-items tr").removeClass("wobel-filter-profile-loaded");
    jQuery('input.wobel-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
    jQuery("#wobel-bulk-edit-reset-filter").hide();
    jQuery('#wobel-bulk-edit-reset-filter').hide();

    jQuery('.wobel-reset-filter-form').closest('li').hide();

    setTimeout(function () {
        if (window.location.search !== '?page=wobel') {
            wobelClearFilterDataWithRedirect()
        } else {
            let data = wobelGetCurrentFilterData();
            wobelFilterProfileChangeUseAlways("default");
            wobelOrdersFilter(data, "pro_search");
        }
    }, 250);
}

function wobelClearFilterDataWithRedirect() {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_clear_filter_data',
        },
        success: function (response) {
            window.location.search = '?page=wobel';
        },
        error: function () {
        }
    });
}

function wobelChangeCountPerPage(countPerPage) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_change_count_per_page',
            count_per_page: countPerPage,
        },
        success: function (response) {
            if (response.success) {
                wobelReloadOrders([], 1);
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelAddNewFileItem() {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_add_new_file_item',
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wobel-modal-select-files .wobel-inline-select-files').prepend(response.file_item);
                wobelSetTipsyTooltip();
            }
        },
        error: function () {

        }
    });
}

function wobelGetOrderFiles(orderID) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_get_order_files',
            order_id: orderID,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wobel-modal-select-files .wobel-inline-select-files').html(response.files);
                wobelSetTipsyTooltip();
            } else {
                jQuery('#wobel-modal-select-files .wobel-inline-select-files').html('');
            }
        },
        error: function () {
            jQuery('#wobel-modal-select-files .wobel-inline-select-files').html('');
        }
    });
}

function changedTabs(item) {
    let change = false;
    let tab = jQuery('nav.wobel-tabs-navbar a[data-content=' + item.closest('.wobel-tab-content-item').attr('data-content') + ']');
    item.closest('.wobel-tab-content-item').find('[data-field=operator]').each(function () {
        if (jQuery(this).val() === 'text_remove_duplicate') {
            change = true;
            return false;
        }
    });
    item.closest('.wobel-tab-content-item').find('[data-field=value]').each(function () {
        if (jQuery(this).val()) {
            change = true;
            return false;
        }
    });
    if (change === true) {
        tab.addClass('wobel-tab-changed');
    } else {
        tab.removeClass('wobel-tab-changed');
    }
}

function wobelGetQuickSearchData() {
    return {
        search_type: 'quick_search',
        quick_search_text: jQuery('#wobel-quick-search-text').val(),
        quick_search_field: jQuery('#wobel-quick-search-field').val(),
        quick_search_operator: jQuery('#wobel-quick-search-operator').val(),
    };
}

function wobelSortByColumn(columnName, sortType) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_sort_by_column',
            filter_data: wobelGetCurrentFilterData(),
            column_name: columnName,
            sort_type: sortType,
        },
        success: function (response) {
            if (response.success) {
                wobelLoadingSuccess();
                wobelSetOrdersList(response)
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelColumnManagerFieldsGetForEdit(presetKey) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_column_manager_get_fields_for_edit',
            preset_key: presetKey
        },
        success: function (response) {
            jQuery('#wobel-modal-column-manager-edit-preset .wobel-box-loading').hide();
            jQuery('.wobel-column-manager-added-fields[data-action=edit] .items').html(response.html);
            setTimeout(function () {
                wobelSetColorPickerTitle();
            }, 250);
            jQuery('.wobel-column-manager-available-fields[data-action=edit] li').each(function () {
                if (jQuery.inArray(jQuery(this).attr('data-name'), response.fields.split(',')) !== -1) {
                    jQuery(this).attr('data-added', 'true').hide();
                } else {
                    jQuery(this).attr('data-added', 'false').show();
                }
            });
            jQuery('.wobel-color-picker').wpColorPicker();
        },
    })
}

function wobelAddMetaKeysByOrderID(orderID) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wobel_add_meta_keys_by_order_id',
            order_id: orderID,
        },
        success: function (response) {
            jQuery('#wobel-meta-fields-items').append(response);
            wobelLoadingSuccess();
        },
        error: function () {
            wobelLoadingError();
        }
    })
}

function wobelHistoryUndo() {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_history_undo',
        },
        success: function (response) {
            if (response.success) {
                wobelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wobel-history-items tbody').html(response.history_items);
                wobelReloadOrders(response.order_ids);
            }
        },
        error: function () {

        }
    });
}

function wobelHistoryRedo() {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_history_redo',
        },
        success: function (response) {
            if (response.success) {
                wobelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wobel-history-items tbody').html(response.history_items);
                wobelReloadOrders(response.order_ids);
            }
        },
        error: function () {

        }
    });
}

function wobelHistoryFilter(filters = null) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_history_filter',
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                wobelLoadingSuccess();
                if (response.history_items) {
                    jQuery('.wobel-history-items tbody').html(response.history_items);
                } else {
                    jQuery('.wobel-history-items tbody').html("<td colspan='4'><span>Not Found!</span></td>");
                }
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelHistoryChangePage(page = 1, filters = null) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_history_change_page',
            page: page,
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                wobelLoadingSuccess();
                if (response.history_items) {
                    jQuery('.wobel-history-items tbody').html(response.history_items);
                    jQuery('.wobel-history-pagination-container').html(response.history_pagination);
                } else {
                    jQuery('.wobel-history-items tbody').html("<td colspan='4'><span>" + wobelTranslate.notFound + "</span></td>");
                }
                jQuery('.wobel-history-pagination-loading').hide();
            } else {
                jQuery('.wobel-history-pagination-loading').hide();
            }
        },
        error: function () {
            jQuery('.wobel-history-pagination-loading').hide();
        }
    });
}

function wobelGetCurrentPage() {
    return jQuery('.wobel-top-nav-filters .wobel-top-nav-filters-paginate a.current').attr('data-index');
}

function wobelGetDefaultFilterProfileOrders() {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_get_default_filter_profile_orders',
        },
        success: function (response) {
            if (response.success) {
                setTimeout(function () {
                    setFilterValues(response);
                }, 500);
                wobelSetOrdersList(response)
            }
        },
        error: function () {
        }
    });
}

function setFilterValues(response) {
    let filterData = response.filter_data;
    if (filterData) {
        jQuery('.wobel-top-nav-status-filter a').removeClass('active');
        jQuery.each(filterData, function (key, values) {
            switch (key) {
                case 'order_status':
                    if (values.value[0]) {
                        jQuery('.wobel-top-nav-status-filter a[data-status="' + values.value[0] + '"]').addClass('active');
                        jQuery('#wobel-filter-form-order-status').val(values.value).change();
                    } else {
                        jQuery('.wobel-top-nav-status-filter a[data-status="all"]').addClass('active');
                    }
                    break;
                case 'products':
                    if (values.value && values.value.length > 0) {
                        values.value.forEach(function (key) {
                            if (response.products[key]) {
                                jQuery('#wobel-filter-form-order-products').append("<option value='" + key + "' selected='selected'>" + response.products[key] + "</option>");
                            }
                        });
                    }
                    break;
                case 'taxonomies':
                    if (jQuery.isArray(values)) {
                        values.forEach(function (val) {
                            if (val.operator) {
                                jQuery('#wobel-float-side-modal-filter .wobel-form-group[data-name="' + val.taxonomy + '"]').find('[data-field=operator]').val(val.operator).change();
                            }
                            if (val.value) {
                                jQuery('#wobel-float-side-modal-filter .wobel-form-group[data-name="' + val.taxonomy + '"]').find('[data-field=value]').val(val.value).change();
                            }
                        });
                    }
                    break;
                default:
                    if (values instanceof Object) {
                        if (values.operator) {
                            jQuery('#wobel-float-side-modal-filter .wobel-form-group[data-name="' + key + '"]').find('[data-field=operator]').val(values.operator).change();
                        }
                        if (values.value) {
                            jQuery('#wobel-float-side-modal-filter .wobel-form-group[data-name="' + key + '"]').find('[data-field=value]').val(values.value).change();
                        }
                        if (values.from) {
                            jQuery('#wobel-float-side-modal-filter .wobel-form-group[data-name="' + key + '"]').find('[data-field=from]').val(values.from).change();
                        }
                        if (values.to) {
                            jQuery('#wobel-float-side-modal-filter .wobel-form-group[data-name="' + key + '"]').find('[data-field=to]').val(values.to);
                        }
                    } else {
                        jQuery('#wobel-float-side-modal-filter .wobel-form-group[data-name="' + key + '"]').find('[data-field=value]').val(values).change();
                    }
                    break;
            }
        });

        wobelCheckFilterFormChanges();
        wobelCheckResetFilterButton();
    }
}

function checkedCurrentCategory(id, categoryIds) {
    categoryIds.forEach(function (value) {
        jQuery(id + ' input[value=' + value + ']').prop('checked', 'checked');
    });
}

function wobelSaveFilterPreset(data, presetName) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_save_filter_preset',
            filter_data: data,
            preset_name: presetName
        },
        success: function (response) {
            if (response.success) {
                wobelLoadingSuccess();
                jQuery('#wobel-float-side-modal-filter-profiles').find('tbody').append(response.new_item);
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelResetBulkEditForm() {
    jQuery('#wobel-float-side-modal-bulk-edit input').val('').change();
    jQuery('#wobel-float-side-modal-bulk-edit select').prop('selectedIndex', 0).change();
    jQuery('#wobel-float-side-modal-bulk-edit textarea').val('');
    jQuery('#wobel-float-side-modal-bulk-edit .wobel-select2').val(null).trigger('change');
}

function wobelGetProSearchData() {
    let data;
    let taxonomies = [];
    let custom_fields = [];

    jQuery('.wobel-form-group select[data-type=taxonomy]').each(function () {
        if (jQuery(this).val() !== null) {
            taxonomies.push({
                taxonomy: jQuery(this).attr('data-taxonomy'),
                operator: jQuery(this).closest('.wobel-form-group').find('select[data-field=operator]').val(),
                value: jQuery(this).val()
            });
        }
    });

    jQuery('.wobel-tab-content-item[data-content=filter_custom_fields] .wobel-form-group').each(function () {
        let fieldName = jQuery(this).attr('data-name');
        if (jQuery(this).find('input').length === 2) {
            let dataFieldType;
            let values = jQuery(this).find('input').map(function () {
                dataFieldType = jQuery(this).attr('data-field-type');
                if (jQuery(this).val()) {
                    return jQuery(this).val()
                }
            }).get();
            custom_fields.push({
                type: 'from-to-' + dataFieldType,
                taxonomy: fieldName,
                value: values
            });
        } else if (jQuery(this).find('input[data-field=value]').length === 1) {
            if (jQuery(this).find('input[data-field=value]').val() != null) {
                custom_fields.push({
                    type: 'text',
                    taxonomy: fieldName,
                    operator: jQuery(this).find('select[data-field=operator]').val(),
                    value: jQuery(this).find('input[data-field=value]').val()
                });
            }
        } else if (jQuery(this).find('select[data-field=value]').length === 1) {
            if (jQuery(this).find('select[data-field=value]').val() != null) {
                custom_fields.push({
                    type: 'select',
                    taxonomy: fieldName,
                    value: jQuery(this).find('select[data-field=value]').val()
                });
            }
        }
    });

    data = {
        search_type: 'pro_search',
        order_ids: {
            operator: jQuery('#wobel-filter-form-order-ids-operator').val(),
            value: jQuery('#wobel-filter-form-order-ids').val(),
        },
        post_date: {
            from: jQuery('#wobel-filter-form-order-created-date-from').val(),
            to: jQuery('#wobel-filter-form-order-created-date-to').val(),
        },
        date_modified: {
            from: jQuery('#wobel-filter-form-order-modified-date-from').val(),
            to: jQuery('#wobel-filter-form-order-modified-date-to').val(),
        },
        date_paid: {
            from: jQuery('#wobel-filter-form-order-paid-date-from').val(),
            to: jQuery('#wobel-filter-form-order-paid-date-to').val(),
        },
        customer_ip_address: {
            operator: jQuery('#wobel-filter-form-order-customer-ip-address-operator').val(),
            value: jQuery('#wobel-filter-form-order-customer-ip-address').val(),
        },
        order_status: {
            value: jQuery('#wobel-filter-form-order-status').val(),
        },
        billing_address_1: {
            operator: jQuery('#wobel-filter-form-order-billing-address-1-operator').val(),
            value: jQuery('#wobel-filter-form-order-billing-address-1').val(),
        },
        billing_address_2: {
            operator: jQuery('#wobel-filter-form-order-billing-address-2-operator').val(),
            value: jQuery('#wobel-filter-form-order-billing-address-2').val(),
        },
        billing_city: {
            operator: jQuery('#wobel-filter-form-order-billing-city-operator').val(),
            value: jQuery('#wobel-filter-form-order-billing-city').val(),
        },
        billing_company: {
            operator: jQuery('#wobel-filter-form-order-billing-company-operator').val(),
            value: jQuery('#wobel-filter-form-order-billing-company').val(),
        },
        billing_country: {
            value: jQuery('#wobel-filter-form-order-billing-country').val(),
        },
        billing_state: {
            value: (jQuery('select.wobel-filter-form-order-billing-state').val()) ? jQuery('select.wobel-filter-form-order-billing-state').val() : jQuery('input.wobel-filter-form-order-billing-state').val()
        },
        billing_email: {
            operator: jQuery('#wobel-filter-form-order-billing-email-operator').val(),
            value: jQuery('#wobel-filter-form-order-billing-email').val(),
        },
        billing_phone: {
            operator: jQuery('#wobel-filter-form-order-billing-phone-operator').val(),
            value: jQuery('#wobel-filter-form-order-billing-phone').val(),
        },
        billing_first_name: {
            operator: jQuery('#wobel-filter-form-order-billing-first-name-operator').val(),
            value: jQuery('#wobel-filter-form-order-billing-first-name').val(),
        },
        billing_last_name: {
            operator: jQuery('#wobel-filter-form-order-billing-last-name-operator').val(),
            value: jQuery('#wobel-filter-form-order-billing-last-name').val(),
        },
        billing_postcode: {
            operator: jQuery('#wobel-filter-form-order-billing-postcode-operator').val(),
            value: jQuery('#wobel-filter-form-order-billing-postcode').val(),
        },
        shipping_address_1: {
            operator: jQuery('#wobel-filter-form-order-shipping-address-1-operator').val(),
            value: jQuery('#wobel-filter-form-order-shipping-address-1').val(),
        },
        shipping_address_2: {
            operator: jQuery('#wobel-filter-form-order-shipping-address-2-operator').val(),
            value: jQuery('#wobel-filter-form-order-shipping-address-2').val(),
        },
        shipping_city: {
            operator: jQuery('#wobel-filter-form-order-shipping-city-operator').val(),
            value: jQuery('#wobel-filter-form-order-shipping-city').val(),
        },
        shipping_company: {
            operator: jQuery('#wobel-filter-form-order-shipping-company-operator').val(),
            value: jQuery('#wobel-filter-form-order-shipping-company').val(),
        },
        shipping_country: {
            value: jQuery('#wobel-filter-form-order-shipping-country').val(),
        },
        shipping_state: {
            value: (jQuery('select.wobel-filter-form-order-shipping-state').val()) ? jQuery('select.wobel-filter-form-order-shipping-state').val() : jQuery('input.wobel-filter-form-order-shipping-state').val(),
        },
        shipping_first_name: {
            operator: jQuery('#wobel-filter-form-order-shipping-first-name-operator').val(),
            value: jQuery('#wobel-filter-form-order-shipping-first-name').val(),
        },
        shipping_last_name: {
            operator: jQuery('#wobel-filter-form-order-shipping-last-name-operator').val(),
            value: jQuery('#wobel-filter-form-order-shipping-last-name').val(),
        },
        shipping_postcode: {
            operator: jQuery('#wobel-filter-form-order-shipping-postcode-operator').val(),
            value: jQuery('#wobel-filter-form-order-shipping-postcode').val(),
        },
        order_currency: {
            value: jQuery('#wobel-filter-form-order-currency').val(),
        },
        order_discount: {
            from: jQuery('#wobel-filter-form-order-discount-from').val(),
            to: jQuery('#wobel-filter-form-order-discount-to').val(),
        },
        order_discount_tax: {
            from: jQuery('#wobel-filter-form-order-discount-tax-from').val(),
            to: jQuery('#wobel-filter-form-order-discount-tax-to').val(),
        },
        order_total: {
            from: jQuery('#wobel-filter-form-order-total-from').val(),
            to: jQuery('#wobel-filter-form-order-total-to').val(),
        },
        products: {
            operator: jQuery('#wobel-filter-form-order-products-operator').val(),
            value: jQuery('#wobel-filter-form-order-products').val(),
        },
        created_via: {
            value: jQuery('#wobel-filter-form-order-create-via').val(),
        },
        payment_method: {
            value: jQuery('#wobel-filter-form-order-payment-method').val(),
        },
        shipping_tax: {
            value: jQuery('#wobel-filter-form-order-shipping-tax').val(),
        },
        _order_tax: {
            value: jQuery('#wobel-filter-form-order-tax').val(),
        },
        order_shipping: {
            value: jQuery('#wobel-filter-form-order-shipping').val(),
        },
        _recorded_coupon_usage_counts: {
            value: jQuery('#wobel-filter-form-order-recorder-coupon-usage-counts').val(),
        },
        _order_stock_reduced: {
            value: jQuery('#wobel-filter-form-order-stock-reduced').val(),
        },
        prices_include_tax: {
            value: jQuery('#wobel-filter-form-order-prices-index-tax').val(),
        },
        _recorded_sales: {
            value: jQuery('#wobel-filter-form-order-recorded-sales').val(),
        },
        custom_fields: custom_fields,
        taxonomies: taxonomies,
    };
    return data;
}

function wobelOrderEdit(orderIds, orderData) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_order_edit',
            order_ids: orderIds,
            order_data: orderData,
            filter_data: wobelGetCurrentFilterData(),
            current_page: wobelGetCurrentPage(),
        },
        success: function (response) {
            if (response.success) {
                wobelReloadRows(response.orders, response.order_statuses);
                wobelSetStatusFilter(response.status_filters);
                wobelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wobel-history-items tbody').html(response.history_items);
                wobelReInitDatePicker();
                wobelReInitColorPicker();
                let wobelTextEditors = jQuery('input[name="wobel-editors[]"]');
                if (wobelTextEditors.length > 0) {
                    wobelTextEditors.each(function () {
                        tinymce.execCommand('mceRemoveEditor', false, jQuery(this).val());
                        tinymce.execCommand('mceAddEditor', false, jQuery(this).val());
                    })
                }
                wobelLoadingSuccess();
            } else {
                wobelLoadingError();
            }
        },
        error: function () {
            wobelLoadingError();
        }
    });
}

function wobelSetStatusFilter(statusFilters) {
    jQuery('.wobel-top-nav-status-filter').html(statusFilters);

    jQuery('.wobel-bulk-edit-status-filter-item').removeClass('active');
    let statusFilter = (jQuery('#wobel-filter-form-order-status').val()) ? jQuery('#wobel-filter-form-order-status').val() : 'all';
    if (jQuery.isArray(statusFilter)) {
        statusFilter.forEach(function (val) {
            jQuery('.wobel-bulk-edit-status-filter-item[data-status="' + val + '"]').addClass('active');
        });
    } else {
        let activeItem = jQuery('.wobel-bulk-edit-status-filter-item[data-status="' + statusFilter + '"]');
        activeItem.addClass('active');
        jQuery('.wobel-status-filter-selected-name').text(' - ' + activeItem.text())
    }
}

function wobelGetTaxonomyParentSelectBox(taxonomy) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_get_taxonomy_parent_select_box',
            taxonomy: taxonomy,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wobel-new-order-taxonomy-parent').html(response.options);
            }
        },
        error: function () {
        }
    });
}

function wobelGetOrderBilling(orderId) {
    wobGetOrderById(orderId, wobelSetOrderFieldsToBilling);
}

function wobelSetOrderFieldsToBilling(order) {
    if (order) {
        let element = jQuery('#wobel-modal-order-billing');
        element.find('[data-order-field="customer-user-id"]').attr('data-customer-id', order.customer_user_id);
        element.find('[data-order-field="first-name"]').val(order.billing_first_name);
        element.find('[data-order-field="last-name"]').val(order.billing_last_name);
        element.find('[data-order-field="address-1"]').val(order.billing_address_1);
        element.find('[data-order-field="address-2"]').val(order.billing_address_2);
        element.find('[data-order-field="city"]').val(order.billing_city);
        element.find('[data-order-field="phone"]').val(order.billing_phone);
        element.find('[data-order-field="email"]').val(order.billing_email);
        element.find('[data-order-field="postcode"]').val(order.billing_postcode);
        element.find('[data-order-field="company"]').val(order.billing_company);
        element.find('[data-order-field="transaction-id"]').val(order.transaction_id);
        element.find('[data-order-field="country"]').val(order.billing_country).change();
        element.find('[data-order-field="payment-method"]').val(order.payment_method).change();
        element.find('[data-order-field="state"]').val(order.billing_state).change();
    }
}

function wobelGetOrderShipping(orderId) {
    wobGetOrderById(orderId, wobelSetOrderFieldsToShipping);
}

function wobelSetOrderFieldsToShipping(order) {
    if (order) {
        let element = jQuery('#wobel-modal-order-shipping');
        element.find('[data-order-field="customer-user-id"]').attr('data-customer-id', order.customer_user_id);
        element.find('[data-order-field="first-name"]').val(order.shipping_first_name);
        element.find('[data-order-field="last-name"]').val(order.shipping_last_name);
        element.find('[data-order-field="address-1"]').val(order.shipping_address_1);
        element.find('[data-order-field="address-2"]').val(order.shipping_address_2);
        element.find('[data-order-field="city"]').val(order.shipping_city);
        element.find('[data-order-field="postcode"]').val(order.shipping_postcode);
        element.find('[data-order-field="company"]').val(order.shipping_company);
        element.find('[data-order-field="customer-note"]').val(order.customer_note);
        element.find('[data-order-field="country"]').val(order.shipping_country).change();
        element.find('[data-order-field="state"]').val(order.shipping_state).change();
    }
}

function wobelGetOrderDetails(orderId) {
    wobGetOrderById(orderId, wobelSetOrderFieldsToDetails);
}

function wobelSetOrderFieldsToDetails(order) {
    let element = jQuery('#wobel-modal-order-details');
    // clear form
    element.find('[data-order-field="status"]').text('');
    element.find('[data-order-field="billing-address-index"]').html('');
    element.find('[data-order-field="shipping-address-index"]').html('');
    element.find('[data-order-field="billing-email"]').html('');
    element.find('[data-order-field="billing-phone"]').html('');
    element.find('[data-order-field="payment-via"]').html('');
    element.find('[data-order-field="shipping-method"]').html('');
    element.find('.wobel-order-details-items tbody').html('');

    if (order) {
        // set values
        element.find('[data-order-field="status"]').text(wobelGetOrderStatusName(order.order_status));
        element.find('[data-order-field="billing-address-index"]').html(order.billing_address_index);
        element.find('[data-order-field="shipping-address-index"]').html(order.shipping_address_index);
        element.find('[data-order-field="billing-email"]').html(order.billing_email);
        element.find('[data-order-field="billing-phone"]').html(order.billing_phone);
        element.find('[data-order-field="payment-via"]').html(order.payment_method_title);
        element.find('[data-order-field="shipping-method"]').html(order.shipping_method);
        if (order.order_items_array.length > 0) {
            order.order_items_array.forEach(function (item) {
                element.find('.wobel-order-details-items tbody').append("<tr><td><a target='_blank' href='" + item.product_link + "'>" + item.product_name + "</a></td><td>" + item.quantity + "</td><td>" + item.tax + " " + item.currency + "</td><td>" + item.total + " " + item.currency + "</td></tr>")
            });
        }
    }
}

function wobGetOrderById(orderId, handler) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_get_order_details',
            order_id: orderId,
        },
        success: function (response) {
            if (response.success) {
                handler(response.order);
            }
        },
        error: function () {
        }
    });
}

function wobelGetOrderStatusName(orderStatus) {
    let status;
    switch (orderStatus) {
        case 'wc-cancelled':
            status = 'Cancelled';
            break;
        case 'wc-pending':
            status = 'Pending payment';
            break;
        case 'wc-completed':
            status = 'Completed';
            break;
        case 'wc-processing':
            status = 'Processing';
            break;
        case 'wc-on-hold':
            status = 'On hold';
            break;
        case 'wc-refunded':
            status = 'Refunded';
            break;
        case 'wc-failed':
            status = 'Failed';
            break;
    }

    return status;
}

function wobelClearInputs(element) {
    element.find('input').val('');
    element.find('textarea').val('');
    element.find('select option:first').prop('selected', true);
}

function wobelLoadCustomerDetails(customerId, target) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_get_customer_billing_address',
            customer_id: customerId,
        },
        success: function (response) {
            if (response.success) {
                wobelSetCustomerDetails(response.billing_address, target);
            }
        },
        error: function () {
        }
    });
}

function wobelSetCustomerDetails(billingAddress, target) {
    let element = jQuery(target);
    if (element) {
        element.find('.wobel-customer-details-items span').text('');
        if (billingAddress.billing_address_1) {
            element.find('[data-customer-field="address-1"]').text(billingAddress.billing_address_1 + ', ');
        }
        if (billingAddress.billing_address_2) {
            element.find('[data-customer-field="address-2"]').text(billingAddress.billing_address_2 + ', ');
        }
        if (billingAddress.billing_city) {
            element.find('[data-customer-field="city"]').text(billingAddress.billing_city + ', ');
        }

        element.find('[data-customer-field="country"]').text(billingAddress.billing_country_name);
        element.find('[data-customer-field="phone"]').text(billingAddress.billing_phone);
        element.find('[data-customer-field="email"]').text(billingAddress.billing_email);
    }
}

function wobelLoadCustomerBillingAddress(customerId, target) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_get_customer_billing_address',
            customer_id: customerId,
        },
        success: function (response) {
            if (response.success) {
                wobelSetCustomerBillingAddress(response.billing_address, target);
            }
        },
        error: function () {
        }
    });
}

function wobelLoadCustomerShippingAddress(customerId, target) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_get_customer_shipping_address',
            customer_id: customerId,
        },
        success: function (response) {
            if (response.success) {
                wobelSetCustomerShippingAddress(response.shipping_address, target);
            }
        },
        error: function () {
        }
    });
}

function wobelSetCustomerBillingAddress(billingAddress, target) {
    let element = jQuery(target);
    if (element) {
        element.find('[data-order-field="first-name"]').val(billingAddress.billing_first_name);
        element.find('[data-order-field="last-name"]').val(billingAddress.billing_last_name);
        element.find('[data-order-field="address-1"]').val(billingAddress.billing_address_1);
        element.find('[data-order-field="address-2"]').val(billingAddress.billing_address_2);
        element.find('[data-order-field="city"]').val(billingAddress.billing_city);
        element.find('[data-order-field="phone"]').val(billingAddress.billing_phone);
        element.find('[data-order-field="email"]').val(billingAddress.billing_email);
        element.find('[data-order-field="postcode"]').val(billingAddress.billing_postcode);
        element.find('[data-order-field="company"]').val(billingAddress.billing_company);
        element.find('[data-order-field="country"]').val(billingAddress.billing_country).change();
        element.find('[data-order-field="state"]').val(billingAddress.billing_state).change();
    }
}

function wobelSetCustomerShippingAddress(shippingAddress, target) {
    let element = jQuery(target);
    if (element) {
        element.find('[data-order-field="first-name"]').val(shippingAddress.shipping_first_name);
        element.find('[data-order-field="last-name"]').val(shippingAddress.shipping_last_name);
        element.find('[data-order-field="address-1"]').val(shippingAddress.shipping_address_1);
        element.find('[data-order-field="address-2"]').val(shippingAddress.shipping_address_2);
        element.find('[data-order-field="city"]').val(shippingAddress.shipping_city);
        element.find('[data-order-field="postcode"]').val(shippingAddress.shipping_postcode);
        element.find('[data-order-field="company"]').val(shippingAddress.shipping_company);
        element.find('[data-order-field="country"]').val(shippingAddress.shipping_country).change();
        element.find('[data-order-field="state"]').val(shippingAddress.shipping_state).change();
    }
}

function wobelGetProducts() {
    let query;
    jQuery(".wobel-select2-products").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WOBEL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wobel_get_products",
                    search: params.term,
                };
                return query;
            },
        },
        minimumInputLength: 1
    });
}

function wobelGetTaxonomies() {
    let query;
    jQuery(".wobel-select2-taxonomies").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WOBEL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wobel_get_taxonomies",
                    search: params.term,
                };
                return query;
            },
        },
        minimumInputLength: 1
    });
}

function wobelGetTags() {
    let query;
    jQuery(".wobel-select2-tags").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WOBEL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wobel_get_tags",
                    search: params.term,
                };
                return query;
            },
        },
        minimumInputLength: 1
    });
}

function wobelGetCategories() {
    let query;
    jQuery(".wobel-select2-categories").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WOBEL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wobel_get_categories",
                    search: params.term,
                };
                return query;
            },
        },
        minimumInputLength: 1
    });
}

function wobelGetOrderNotes(orderId) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_get_order_notes',
            order_id: orderId,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wobel-modal-order-notes-items').html(response.order_notes);
            }
        },
        error: function () {
        }
    });
}

function wobelAddOrderNote(orderId, orderData) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_add_order_note',
            order_id: orderId,
            order_data: orderData
        },
        success: function (response) {
            jQuery('#wobel-modal-order-notes-content').val('');
            jQuery('#wobel-modal-order-notes-type').val('private').change();
            jQuery('#wobel-modal-order-notes .wobel-modal-body').scrollTop(0);
            wobelLoadingSuccess();
            jQuery('#wobel-modal-order-notes-items').html(response.order_notes);
        },
        error: function () {
            wobelLoadingError('Error !');
        }
    });
}

function wobelDeleteOrderNote(noteId) {
    wobelLoadingStart();
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_delete_order_note',
            note_id: noteId
        },
        success: function () {
            jQuery('#wobel-modal-order-notes .delete-note[data-note-id="' + noteId + '"]').closest('.wobel-order-note-item').remove();
            wobelLoadingSuccess();
        },
        error: function () {
            wobelLoadingError('Error !');
        }
    });
}

function wobelGetAddress(orderId, field) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_get_order_address',
            order_id: orderId,
            field: field
        },
        success: function (response) {
            jQuery('input.wobel-modal-order-address-text').val(response.address);
            jQuery('div.wobel-modal-order-address-text').html(response.address);
        },
        error: function () {
        }
    });
}

function wobelGetOrderItems(orderId) {
    jQuery.ajax({
        url: WOBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wobel_get_order_items',
            order_id: orderId
        },
        success: function (response) {
            jQuery('#wobel-modal-order-items').attr('data-height-fixed', 'false');
            if (response.order_items.length > 0) {
                let i = 1;
                response.order_items.forEach(function (item) {
                    jQuery('#wobel-modal-order-items .wobel-order-items-table tbody').append("<tr><td><a target='_blank' href='" + item.product_link + "'>" + item.product_name + "</a></td><td>" + item.quantity + "</td></tr>")

                    if (i == response.order_items.length) {
                        wobelFixModalHeight(jQuery('#wobel-modal-order-items'));
                    }

                    i++;
                });
            } else {
                jQuery('#wobel-modal-order-items .wobel-order-items-table tbody').append("<tr><td class='wobel-red-text'>There is not any order item</td></tr>").ready(function () {
                    wobelFixModalHeight(jQuery('#wobel-modal-order-items'));
                });
            }
            jQuery('.wobel-order-items-loading').hide();
        },
        error: function () {
            jQuery('.wobel-order-items-loading').hide();
        }
    });
}