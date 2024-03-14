/*
 * Sunny
 *
 * Automatically purge CloudFlare cache, including cache everything rules.
 *
 * @package   Sunny
 *
 * @author Typist Tech <sunny@typist.tech>
 * @copyright 2017 Typist Tech
 * @license GPL-2.0+
 *
 * @see https://www.typist.tech/projects/sunny
 * @see https://wordpress.org/plugins/sunny/
 */

jQuery(window).load(function () {
    resetResultArea();
    getResult();

    function resetResultArea() {
        jQuery('div#sunny_targets_debugger-result').replaceWith(
            '<div id="sunny_targets_debugger-result">' +
            '<div class="notice-info notice"><p class="row-title">Fetching data...</p></div>' +
            '</div>'
        );
    }

    function getResult() {
        jQuery.ajax({
            url: sunny_targets_debugger.route,
            method: 'GET',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', sunny_targets_debugger.nonce);
            }
        }).done(function (response) {
            jQuery('div#sunny_targets_debugger-result').replaceWith(
                '<div id="sunny_targets_debugger-result">' +
                '<table id="sunny_targets_debugger-table" class="widefat striped cache-status">' +
                '<thead>' +
                '<tr>' +
                '<th scope="col">Group</th>' +
                '<th scope="col">Urls</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody id="sunny_targets_debugger-result-body"></tbody>' +
                '</table>' +
                '</div>'
            );

            jQuery.map(response, function (values, index) {
                jQuery('tbody#sunny_targets_debugger-result-body').append(
                    "<tr id='" + index + "'>" +
                    "<td class='target-group'><strong class='row-title'>" + index + '</strong></td>' +
                    "<td class='target-urls'></td>" +
                    '</tr>'
                );

                jQuery.map(values, function (value) {
                    jQuery('#sunny_targets_debugger-result tr#' + index + '>td.target-urls').append(
                        value + '<br/>'
                    );
                });
            });

        }).fail(function (response) {
            jQuery('div#sunny_targets_debugger-result').replaceWith(
                '<div id="sunny_targets_debugger-result">' +
                '<div class="notice-error notice">' +
                '<p class="row-title">Error fetching data.</p>' +
                '<p>' +
                'Status: ' + response.status + ' ' + response.statusText + '<br/>' +
                'Code: <code>' + response.responseJSON.code + '</code><br/>' +
                'Message: <strong>' + response.responseJSON.message + '</strong><br/>' +
                '</p>' +
                '</div>' +
                '</div>'
            );
        });
    }
});
