(function ($) {
    const {__, _x, _n, _nx} = wp.i18n;
    'use strict';

    $('#cancel-recurrence-po6').on("click", function (e) {
        e.preventDefault();
        let order = $(this).data('order');
        $.confirm({
            type: 'orange',
            typeAnimated: true,
            boxWidth: '30%',
            useBootstrap: false,
            title: __('Confirmation', 'plationline'),
            content: __('Are you sure you want to cancel recurrence for this transaction?', 'plationline'),
            buttons: {
                da: {
                    text: __('YES', 'plationline'),
                    action: function () {
                        $.ajax({
                            type: "POST",
                            url: po6.ajaxurl,
                            data: {
                                'action': 'cancel_recurrence',
                                'order': order
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                $('#po6-ajax-loading').show();
                            },
                            error: function (tXMLHttpRequest, textStatus, errorThrown) {
                                $.alert({
                                    type: 'red',
                                    typeAnimated: true,
                                    boxWidth: '30%',
                                    useBootstrap: false,
                                    title: __('Error', 'plationline'),
                                    content: errorThrown
                                });
                                $('#po6-ajax-loading').hide();
                            },
                            success: function (raspuns) {
                                $.alert({
                                    type: (raspuns.status === "success" ? 'green' : 'red'),
                                    typeAnimated: true,
                                    boxWidth: '30%',
                                    useBootstrap: false,
                                    title: __('Cancel recurrence response', 'plationline'),
                                    content: raspuns.message
                                });
                                $('#po6-ajax-loading').hide();
                            }
                        });
                    }
                },
                nu: {
                    text: __('NO', 'plationline'),
                }
            }
        });
    });

    $('#void-po6').on("click", function (e) {
        e.preventDefault();
        let order = $(this).data('order');
        $.confirm({
            type: 'orange',
            typeAnimated: true,
            boxWidth: '30%',
            useBootstrap: false,
            title: __('Confirmation', 'plationline'),
            content: __('Are you sure you want to void this transaction?', 'plationline'),
            buttons: {
                da: {
                    text: __('YES', 'plationline'),
                    action: function () {
                        $.ajax({
                            type: "POST",
                            url: po6.ajaxurl,
                            data: {
                                'action': 'void',
                                'order': order
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                $('#po6-ajax-loading').show();
                            },
                            error: function (tXMLHttpRequest, textStatus, errorThrown) {
                                $.alert({
                                    type: 'red',
                                    typeAnimated: true,
                                    boxWidth: '30%',
                                    useBootstrap: false,
                                    title: __('Error', 'plationline'),
                                    content: errorThrown
                                });
                                $('#po6-ajax-loading').hide();
                            },
                            success: function (raspuns) {
                                $.alert({
                                    type: (raspuns.status === "success" ? 'green' : 'red'),
                                    typeAnimated: true,
                                    boxWidth: '30%',
                                    useBootstrap: false,
                                    title: __('Void response', 'plationline'),
                                    content: raspuns.message
                                });
                                $('#po6-ajax-loading').hide();
                            }
                        });
                    }
                },
                nu: {
                    text: __('NO', 'plationline'),
                }
            }
        });
    });

    $('#settle-po6').on("click", function (e) {
        e.preventDefault();
        let order = $(this).data('order');
        $.confirm({
            type: 'orange',
            typeAnimated: true,
            boxWidth: '30%',
            useBootstrap: false,
            title: __('Confirmation', 'plationline'),
            content: __('Are you sure you want to settle this transaction?', 'plationline'),
            buttons: {
                da: {
                    text: __('YES', 'plationline'),
                    action: function () {
                        $.ajax({
                            type: "POST",
                            url: po6.ajaxurl,
                            data: {
                                'action': 'settle',
                                'order': order
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                $('#po6-ajax-loading').show();
                            },
                            error: function (tXMLHttpRequest, textStatus, errorThrown) {
                                $.alert({
                                    type: 'red',
                                    typeAnimated: true,
                                    boxWidth: '30%',
                                    useBootstrap: false,
                                    title: __('Error', 'plationline'),
                                    content: errorThrown
                                });
                                $('#po6-ajax-loading').hide();
                            },
                            success: function (raspuns) {
                                $.alert({
                                    type: (raspuns.status === "success" ? 'green' : 'red'),
                                    typeAnimated: true,
                                    boxWidth: '30%',
                                    useBootstrap: false,
                                    title: __('Settle response', 'plationline'),
                                    content: raspuns.message
                                });
                                $('#po6-ajax-loading').hide();
                            }
                        });
                    }
                },
                nu: {
                    text: __('NO', 'plationline'),
                }
            }
        });
    });

    $('#refund-po6').on("click", function (e) {
        e.preventDefault();
        let order = $(this).data('order');
        let amount = parseFloat($('#refund-po6-amount').val());
        $.confirm({
            type: 'orange',
            typeAnimated: true,
            boxWidth: '30%',
            useBootstrap: false,
            title: __('Confirmation', 'plationline'),
            content: __('Are you sure you want to refund selected amount?', 'plationline'),
            buttons: {
                da: {
                    text: __('YES', 'plationline'),
                    action: function () {
                        $.ajax({
                            type: "POST",
                            url: po6.ajaxurl,
                            data: {
                                'action': 'refund',
                                'order': order,
                                'amount': amount,
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                $('#po6-ajax-loading').show();
                            },
                            error: function (tXMLHttpRequest, textStatus, errorThrown) {
                                $.alert({
                                    type: 'red',
                                    typeAnimated: true,
                                    boxWidth: '30%',
                                    useBootstrap: false,
                                    title: __('Error', 'plationline'),
                                    content: errorThrown
                                });
                                $('#po6-ajax-loading').hide();
                            },
                            success: function (raspuns) {
                                $.alert({
                                    type: (raspuns.status === "success" ? 'green' : 'red'),
                                    typeAnimated: true,
                                    boxWidth: '30%',
                                    useBootstrap: false,
                                    title: __('Refund response', 'plationline'),
                                    content: raspuns.message
                                });
                                $('#po6-ajax-loading').hide();
                            }
                        });
                    }
                },
                nu: {
                    text: __('NO', 'plationline'),
                }
            }
        });
    });

    $('#settle-amount').on("click", function (e) {
        e.preventDefault();
        let order = $(this).data('order');
        let amount = parseFloat($('#settle-po6-amount').val());
        $.confirm({
            type: 'orange',
            typeAnimated: true,
            boxWidth: '30%',
            useBootstrap: false,
            title: __('Confirmation', 'plationline'),
            content: __('Are you sure you want to settle selected amount?', 'plationline'),
            buttons: {
                da: {
                    text: __('YES', 'plationline'),
                    action: function () {
                        $.ajax({
                            type: "POST",
                            url: po6.ajaxurl,
                            data: {
                                'action': 'settle_amount',
                                'order': order,
                                'amount': amount,
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                $('#po6-ajax-loading').show();
                            },
                            error: function (tXMLHttpRequest, textStatus, errorThrown) {
                                $.alert({
                                    type: 'red',
                                    typeAnimated: true,
                                    boxWidth: '30%',
                                    useBootstrap: false,
                                    title: __('Error', 'plationline'),
                                    content: errorThrown
                                });
                                $('#po6-ajax-loading').hide();
                            },
                            success: function (raspuns) {
                                $.alert({
                                    type: (raspuns.status === "success" ? 'green' : 'red'),
                                    typeAnimated: true,
                                    boxWidth: '30%',
                                    useBootstrap: false,
                                    title: __('Settle response', 'plationline'),
                                    content: raspuns.message
                                });
                                $('#po6-ajax-loading').hide();
                            }
                        });
                    }
                },
                nu: {
                    text: __('NO', 'plationline'),
                }
            }
        });
    });

    $('#query-po6').on("click", function (e) {
        e.preventDefault();
        let order = $(this).data('order');
        $.ajax({
            type: "POST",
            url: po6.ajaxurl,
            data: {
                'action': 'query',
                'order': order
            },
            dataType: 'json',
            beforeSend: function () {
                $('#po6-ajax-loading').show();
            },
            error: function (tXMLHttpRequest, textStatus, errorThrown) {
                $.alert({
                    type: 'red',
                    typeAnimated: true,
                    boxWidth: '30%',
                    useBootstrap: false,
                    title: __('Error', 'plationline'),
                    content: errorThrown
                });
                $('#po6-ajax-loading').hide();
            },
            success: function (raspuns) {
                $.alert({
                    type: (raspuns.status === "success" ? 'green' : 'red'),
                    typeAnimated: true,
                    boxWidth: '30%',
                    useBootstrap: false,
                    title: __('Query response', 'plationline'),
                    content: raspuns.message
                });
                $('#po6-ajax-loading').hide();
            }
        });
    });

})(jQuery);
