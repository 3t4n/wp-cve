<?php
class BeRocket_terms_cond_popup_prevent_close_scroll {
    public $javascript = '';
    function __construct() {
        add_action('berocket_terms_cond_popup_created', array($this, 'popup_created'), 10, 4);
        add_action('wp_footer', array($this, 'footer'), 10000000);
    }
    function popup_created($popup_id_generated, $popup_id, $popup_page, $temp_popup_options) {
        $javascript = "
            jQuery('#br_popup_{$popup_id_generated}').on('br_popup-show_popup', BR_termscond_prevent_close_scroll_init);
        ";
        $this->javascript .= $javascript;
    }
    function footer() {
        echo '<script>
        function BR_termscond_prevent_close_scroll_check() {
            var this_obj = jQuery(this);
            var innerHeight = parseInt(this_obj.innerHeight());
            var scrollTop = parseInt(this_obj.scrollTop());
            var scrollHeight = parseInt(this_obj[0].scrollHeight);
            if(scrollTop + innerHeight >= scrollHeight - 20) {
                jQuery(this).parents("#br_popup").data("br_popup_main").br_popup().enable_close();
                this_obj.off("scroll", BR_termscond_prevent_close_scroll_check);
            }
        }
        function BR_termscond_prevent_close_scroll_init(event, popup) {
            jQuery(popup).br_popup().disable_close();
            jQuery("#br_popup .br_popup_inner").on("scroll", BR_termscond_prevent_close_scroll_check);
            jQuery("#br_popup .br_popup_inner").trigger("scroll");
        }
        '.$this->javascript.'</script>';
    }
}
new BeRocket_terms_cond_popup_prevent_close_scroll();
