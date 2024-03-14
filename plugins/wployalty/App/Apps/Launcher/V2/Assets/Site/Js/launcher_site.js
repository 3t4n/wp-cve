/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */
if (typeof (wll_jquery) == 'undefined') {
    wll_jquery = jQuery.noConflict();
}
wll = window.wll || {};
(function () {
})(wll)
wll.ajaxAction = function () {
    wll_jquery.ajax({
        type: "POST",
        url: wll_localize_data.ajax_url,
        data: {
            action: 'wll_open_launcher',
            wll_nonce: wll_localize_data.render_page_nonce,
        },
        dataType: "json",
        async: true,
        before: function () {
        },
        success: function (json) {
            console.log(json);
        }
    });
}
