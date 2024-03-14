(function($){
    'use strict';

    function rmlAdminManager() {
        this.init();
    }
    rmlAdminManager.prototype = {
        $dismissibles: $('.ocsrmldimissible'),
        init: function () {
            var that = this;

            this.$dismissibles.find('.notice-dismiss').on('click', function () {
                var notice = $(this).parent().data('notice');
                that.dismiss(notice);
            });
        },
        dismiss: function (notice) {
            var data = {
                action: 'ocs-rml-dismiss-notice',
                security: rmlAdminManagerParams.nonce,
                notice: notice,
            };

            $.post(rmlAdminManagerParams.ajax_url, data, function(response) {
                var success = response.success || false;
            });
        }
    }

    $(document).ready(function() {
        var ocsRmlAdminManager = new rmlAdminManager();
    });
}(jQuery));