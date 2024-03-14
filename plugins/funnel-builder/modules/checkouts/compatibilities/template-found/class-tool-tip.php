<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Tool_Tip {

	public function __construct() {
		add_action( 'wfacp_internal_css', [ $this, 'add_js' ] );
	}

	public function add_js() {
		?>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    tool_tip();
                    $(document.body).on('updated_checkout', function (e, v) {
                        tool_tip();
                    });

                    function tool_tip() {
                        if (typeof $.fn.tooltip != "function") {
                            return;
                        }
                        if ($('.dashicons').length > 0) {
                            $('.dashicons').tooltip({
                                content: function () {
                                    return $(this).prop('title');
                                }
                            });
                        }
                    }
                })(jQuery);
            });
        </script>
		<?php
	}
}

new WFACP_Tool_Tip();
