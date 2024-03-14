jQuery(document).ready(
    function($) {
        var $parent = $('.notice-iworks-rate');
        $('.iworks-rate-button, .notice-dismiss', $parent).on('click', function(e) {
            var data = {
                action: 'iworks_rate_button',
                plugin_id: $parent.data('id'),
                button: $(this).data('action'),
                _wpnonce: $parent.data('nonce')
            };
            if ('get-help' === $(this).data('action')) {
                return true;
            }
            $.post(
                $parent.data('ajax-url'),
                data,
                function(response) {
                    $parent.detach();
                }
            );
            return true;
        });
    }
);
