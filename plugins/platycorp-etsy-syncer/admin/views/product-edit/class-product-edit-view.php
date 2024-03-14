<?php
namespace platy\etsy\admin;

use platy\etsy\EtsyDataService;
use platy\etsy\NoCurrentShopException;
use platy\etsy\NoSuchPostMetaException;
use platy\etsy\EtsySyncerException;
use platy\etsy\EtsySyncer;
use platy\etsy\logs\PlatySyncerLogger;

class EtsyProductEditView{

    public function sync_to_etsy($post_id) {
        if(@$_REQUEST['edit-page-etsy-sync'] !== "true") {
            return;
        }

        if(empty($_REQUEST['etsy-template-id'])) {
            set_transient('platy_etsy_error_transient', "No Etsy template chosen", 5);
            return;
        }
        
        try {
            $syncer = new EtsySyncer();
            $syncer->sync_product($post_id, $_REQUEST['etsy-template-id']);
        }catch(EtsySyncerException $e) {
            set_transient('platy_etsy_error_transient', "Etsy Error: " . $e->getMessage(), 5);
            return;
        }

        set_transient('platy_etsy_success_transient', "Successfully synced to Etsy", 5);

 
    }

    public function render_update_and_sync_button($post) {

        // Show only for published pages.
        if ( ! $post || 'product' !== $post->post_type ) {
            return;
        }
        $etsy_sync_html = "";

        $pid = $post->ID;

        $data = EtsyDataService::get_instance();
        if(!$data->is_valid()) {
            return;
        }

        $templates = $data->get_templates();

        $shop_id = $data->get_current_shop_id();
        $selected_tid = -1;
        try {
            $selected_tid = $data->get_post_meta($pid, "template_id", $shop_id);
        }catch(NoSuchPostMetaException $e) {

        }

        if(\count($templates) == 1) {
            foreach($templates as $t) {
                $selected_tid = $t['id'];
            }
        }

        $etsy_sync_html .= "<div style='margin-bottom: 10px'>";
        $etsy_sync_html .= "<label style='margin-right: 10px' for='etsy-template-select'>Etsy Template</label>";
        $etsy_sync_html .= "<select id='etsy-template-select' name='etsy-template-id'>";
        foreach($templates as $template) {
            $tid = $template['id'];
            $tname = $template['name'];
            $is_selected = $tid == $selected_tid;
            $selected = $is_selected ? "selected" : "";
            $etsy_sync_html .= "<option value='$tid' $selected>$tname</option>";
        }
        $etsy_sync_html .= "<select/>";
        $etsy_sync_html .= "</div>";
        $etsy_sync_html .= '<div id="update-sync-to-etsy-action" style="overflow: auto; margin-bottom: 5px;">';
        $etsy_sync_html .= '<div id="publishing-action" style="float: left;">';
        $etsy_sync_html .= '<input type="submit" accesskey="p" tabindex="5" value="Update & Etsy Sync" class="button-primary" id="update-and-etsy-sync-button" name="publish">';
        $etsy_sync_html .= '</div>';
        $etsy_sync_html .= '</div>';

        ob_start(); ?>
            <script>window.addEventListener('load', function() {
                    jQuery('form#post').submit(function(event) {
                        var btnClicked = event.originalEvent.submitter;
                        if(btnClicked.id != "update-and-etsy-sync-button") {
                            return true;
                        }
                        
                        var input = jQuery("<input>")
                            .attr("type", "hidden")
                            .attr("name", "edit-page-etsy-sync").val("true");
                        jQuery('form#post').append(input);
                        return true;
                    });
                });
            </script>
        <?php 

        try {
            $shop_id = $data->get_current_shop_id();
            $etsy_item = PlatySyncerLogger::get_instance()->get_etsy_item_data($pid, $shop_id);
            $etsy_listing_id = $etsy_item['etsy_id'];
            $etsy_link = "https://www.etsy.com/listing/$etsy_listing_id/";
    
            include PLATY_SYNCER_ETSY_DIR_PATH . "admin/views/platy-syncer-etsy-logo.php";    
        }catch(EtsySyncerException $e) {

        }

        $etsy_sync_html .= ob_get_clean();
        $etsy_sync_html .= "<hr/>";

        echo $etsy_sync_html;


    }

}