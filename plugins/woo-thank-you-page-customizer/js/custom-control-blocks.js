"use strict";
jQuery(document).ready(function ($) {
    $('#sub-accordion-section-woo_thank_you_page_design_general').append('<li class="woocommerce-thank-you-page-control-processing"></li>');
    /*sortable connect*/
    let start = -1;
    let move = false;
    WtypSortable();

    function WtypSortable() {
        $('.woocommerce-thank-you-page-container__block').sortable({
            items: '.woocommerce-thank-you-page-item',
            placeholder: 'woocommerce-thank-you-page-place-holder',
            cursor: 'move',
            connectWith: '.woocommerce-thank-you-page-container__block',
            start: function (event, ui) {
                start = $('.woocommerce-thank-you-page-container__block .woocommerce-thank-you-page-text_editor').index(ui.item);
            },
            over: function (event, ui) {
                // ui.item()
            },
            receive: function (event, ui) {
                move = true;
            },
            stop: function (event, ui) {
                let position = $('.woocommerce-thank-you-page-container__block .woocommerce-thank-you-page-text_editor').index(ui.item);
                WtypApplyChange(position);
            }
        }).disableSelection();
    }

    /*remove item*/
    $('body').on('click', '.woocommerce-thank-you-page-container__block .woocommerce-thank-you-page-remove', function (event) {
        event.stopPropagation();
        let position = $('.woocommerce-thank-you-page-text_editor').index($(this).parent());
        if ($(this).parent().data()['block_item'] === 'text_editor' ) {
            $(this).parent().remove();
        } else {
            $(this).parent().appendTo('.woocommerce-thank-you-page-components__block');
        }
        WtypApplyChange(position);
    });

    /*apply change when drag & drop items*/
    function WtypApplyChange(position) {
        let row = [],
            rows = $('.woocommerce-thank-you-page-container__row');
        for (let j = 0; j < rows.length; j++) {
            let block = [],
                blocks = rows.eq(j).find('.woocommerce-thank-you-page-container__block');
            for (let i = 0; i < blocks.length; i++) {
                let items = blocks.eq(i).find('.woocommerce-thank-you-page-item'),
                    item = [];
                items.map(function () {
                    item.push($(this).data()['block_item']);
                });
                block.push(item);
            }
            row.push(block);
        }
        //set text editor before set blocks
        let textEditor = JSON.parse(wp.customize('woo_thank_you_page_params[text_editor]').get());
        let blocks_old = JSON.parse(wp.customize('woo_thank_you_page_params[blocks]').get());
        if (position >= 0) {
            /*before*/
            /*after*/
            let textEditorVal = $('.woocommerce-thank-you-page-container .woocommerce-thank-you-page-text_editor');
            if (textEditor.length > textEditorVal.length) {
                textEditor.splice(position, 1);
            } else if (textEditor.length < textEditorVal.length) {
                textEditor.splice(position, 0, '');
            } else {

                if (start != position) {
                    let textStart = textEditor[start];
                    let textStop = textEditor[position];
                    if (move === true) {
                        if (start > position) {
                            textEditor.splice(start, 1);
                            textEditor.splice(position, 0, textStart);
                        } else if (start < position) {
                            textEditor.splice(start, 1);
                            textEditor.splice(position, 0, textStart);
                        }

                    } else {
                        if (start > position) {
                            textEditor.splice(start, 1);
                            textEditor.splice(position, 0, textStart);
                        } else {
                            if (start + 1 == position) {
                                textEditor.splice(start, 1, textStop);
                                textEditor.splice(position, 1, textStart);
                            } else {
                                textEditor.splice(start, 1);
                                textEditor.splice(position, 0, textStart);
                            }
                        }
                        let length = blocks_old.length;
                        if (length > 0) {
                            if (blocks_old[length - 1] !== '') {
                                row.push('');
                            }
                        } else {
                            row.push('');
                        }
                    }
                }
            }
        }
        start = -1;
        move = false;

        wp.customize('woo_thank_you_page_params[text_editor]').set(JSON.stringify(textEditor));
        wp.customize('woo_thank_you_page_params[blocks]').set(JSON.stringify(row));
    }

    /*add row*/
    $('body').on('click', '.woocommerce-thank-you-page-add-row', function () {
        let column_nums = $(this).data()['column_nums'];
        let container = $('.woocommerce-thank-you-page-container');
        container.append(woocommerce_thank_you_page_custom_control_blocks_params.rows[column_nums]);
        WtypSortable();
        // WtypDraggable();
    });
    ShowAvailableItems();

    function HideAvailableItems() {
        $('.woocommerce-thank-you-page-edit-block-add-item').removeClass('woocommerce-thank-you-page-edit-block-add-item-active');
        $('.woocommerce-thank-you-page-components-container').fadeOut(300);
        $('.woocommerce-thank-you-page-container__block').removeClass('woocommerce-thank-you-page-received-block');
    }

    function ShowAvailableItems() {
        $('body').on('click', '.woocommerce-thank-you-page-edit-block-add-item', function () {
            $('.woocommerce-thank-you-page-edit-block-add-item').removeClass('woocommerce-thank-you-page-edit-block-add-item-active');
            $(this).addClass('woocommerce-thank-you-page-edit-block-add-item-active');
            $(this).parent().parent().addClass('woocommerce-thank-you-page-received-block');

            $('.woocommerce-thank-you-page-components-container').fadeIn(300);
        })
    }

    AddItemToBlock();

    function AddItemToBlock() {
        $('.woocommerce-thank-you-page-components').on('click', '.woocommerce-thank-you-page-item', function () {
            let active_button = $('.woocommerce-thank-you-page-edit-block-add-item-active'),
                active_block = active_button.parent().parent(),
                blocks = $('.woocommerce-thank-you-page-container__block');
            // $(this).appendTo(active_block);
            $('.woocommerce-thank-you-page-item').removeClass('woocommerce-thank-you-page-latest-item');
            let current_block = blocks.index(active_block);
            let position = 0;
            for (let i = 0; i <= current_block; i++) {
                position += blocks.eq(i).find('.woocommerce-thank-you-page-text_editor').length;
            }
            //set text editor before set blocks
            let textEditor = JSON.parse(wp.customize('woo_thank_you_page_params[text_editor]').get());

            if ($(this).data()['block_item'] === 'text_editor') {
                textEditor.splice(position, 0, '');
                $(this).clone().addClass('woocommerce-thank-you-page-latest-item').insertBefore(active_button.parent());
            } else {
                $(this).insertBefore(active_button.parent());
            }
            let row = [],
                rows = $('.woocommerce-thank-you-page-container__row');
            for (let j = 0; j < rows.length; j++) {

                let block = [],
                    blocks = rows.eq(j).find('.woocommerce-thank-you-page-container__block');
                for (let i = 0; i < blocks.length; i++) {
                    let items = blocks.eq(i).find('.woocommerce-thank-you-page-item'),
                        item = [];
                    items.map(function () {
                        item.push($(this).data()['block_item']);
                    });
                    block.push(item);
                }
                row.push(block);
            }
            wp.customize('woo_thank_you_page_params[text_editor]').set(JSON.stringify(textEditor));
            wp.customize('woo_thank_you_page_params[blocks]').set(JSON.stringify(row));
            HideAvailableItems();
        })
    }

    $('body').on('click', '.woocommerce-thank-you-page-remove-row', function () {
        if (confirm('Remove this row?')) {
            let text_editor = $('.woocommerce-thank-you-page-container').find('.woocommerce-thank-you-page-text_editor');
            let remove_item = $(this).parent();
            let textEditor = JSON.parse(wp.customize('woo_thank_you_page_params[text_editor]').get());
            let remove_text_positions = [];
            remove_item.find('.woocommerce-thank-you-page-item').map(function () {
                if ($(this).data()['block_item'] !== 'text_editor') {
                    $(this).prependTo($('.woocommerce-thank-you-page-components__block'));
                }
                let pos = text_editor.index($(this));
                if (pos >= 0) {
                    remove_text_positions.push(pos);
                }
            });
            if (remove_text_positions.length) {
                for (let i = remove_text_positions.length - 1; i >= 0; i--) {
                    textEditor.splice(remove_text_positions[i], 1);
                }
            }
            remove_item.remove();
            let row = [],
                rows = $('.woocommerce-thank-you-page-container__row');
            for (let j = 0; j < rows.length; j++) {

                let block = [],
                    blocks = rows.eq(j).find('.woocommerce-thank-you-page-container__block');
                for (let i = 0; i < blocks.length; i++) {
                    let items = blocks.eq(i).find('.woocommerce-thank-you-page-item'),
                        item = [];
                    items.map(function () {
                        item.push($(this).data()['block_item']);
                    });
                    block.push(item);
                }
                row.push(block);
            }

            wp.customize('woo_thank_you_page_params[text_editor]').set(JSON.stringify(textEditor));
            wp.customize('woo_thank_you_page_params[blocks]').set(JSON.stringify(row));
        }
    });
    $('.woocommerce-thank-you-page-components-close').on('click', function () {
        HideAvailableItems();
    });
    $('.woocommerce-thank-you-page-components-overlay').on('click', function () {
        HideAvailableItems();
    })

});
