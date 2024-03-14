<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script('jquery-ui-datepicker' );
wp_enqueue_editor();
wp_enqueue_style('wppm-bootstrap-css');
wp_enqueue_style('wppm-jquery-ui');
wp_enqueue_style('wppm-public-css');
wp_enqueue_style('wppm-admin-css');
wp_enqueue_style('wppm-modal-css');
wp_enqueue_style('wppm-flatpickr-css');
wp_enqueue_style('wppm-select2-css');
wp_enqueue_style('wppm-gpopover-css');
wp_enqueue_style('wppm-dragula-css');
wp_enqueue_script('wppm-admin');
wp_enqueue_script('wppm-public');
wp_enqueue_script('wppm-modal');
wp_enqueue_script('wppm-flatpickr-js');
wp_enqueue_script('wppm-select2-js');
wp_enqueue_script('wppm-gpopover-js');
wp_enqueue_script('wppm-dragula-js');
$task_list_view = get_option('wppm_default_task_list_view');
?>
<div class="wppm_bootstrap">
  <div id="wppm_task_container"></div>
  <div id="wppm_alert_success" class="alert alert-success wppm_alert" style="display:none;" role="alert">
    <img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/success.svg'); ?>" alt="success"> <span class="wppm_alert_text"></span>
  </div>
  <div id="wppm_alert_error" class="alert alert-danger wppm_alert" style="display:none;" role="alert">
    <img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/warning-triangle.svg'); ?>" alt="warning-triangle"> <span class="wppm_alert_text"></span>
  </div>
</div>
<!-- Pop-up snippet start -->
<div id="wppm_popup_background" style="display:none;"></div>
<div id="wppm_popup_container" style="display:none;">
  <div class="wppm_bootstrap">
    <div class="row">
      <div id="wppm_popup" class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div id="wppm_popup_title" class="row" ><h3><?php echo esc_html_e('Modal Title','taskbuilder');?></h3></div>
        <div id="wppm_popup_body" class="row"><?php echo esc_html_e('I am body!','taskbuilder');?></div>
        <div id="wppm_popup_footer" class="row">
          <button type="button" class="btn wppm_popup_close"><?php echo esc_html_e('Close','taskbuilder');?></button>
          <button type="button" class="btn wppm_popup_action"><?php echo esc_html_e('Save Changes','taskbuilder');?></button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Pop-up snippet end -->
<?php
add_action('wp_footer', 'wppm_page_inline_script', 999999999999999999);
do_action('wppm_after_task_shortcode_loaded');
if(!function_exists('wppm_page_inline_script')) {
  function wppm_page_inline_script() { ?>
    <script type="text/javascript">
      jQuery( document ).ready( function( jQuery ) {
        <?php if(is_user_logged_in()){ 
                if(get_option('wppm_default_task_list_view')==1){ ?>
                  wppm_get_task_list();
                <?php } else{ ?>
                  wppm_view_task_search_filter();
                <?php }
              } else{ ?>
                wppm_task_sign_in();
        <?php }?>
      });

      function wppm_get_task_list(page_no){
        jQuery('#wppm_task_container').show();
        jQuery('#wppm_task_container').html(wppm_admin.loading_html);
        var dataform=new FormData(jQuery('#wppm_task_list_frm')[0]);
        <?php echo do_action('wppm_task_list_dataform');?>
        dataform.append("page_no", page_no);
        dataform.append("action", 'wppm_get_task_list');
        jQuery.ajax( {
          url: wppm_admin.ajax_url,
          type: 'POST',
          data: dataform,
          processData: false,
          contentType: false
        }) 
        .done(function( response ) {
          jQuery('#wppm_task_container').html(response);
        });
      }

      function wppm_add_new_task(proj_id){
        jQuery('#wppm_task_container').show();
        jQuery('#wppm_task_container').html(wppm_admin.loading_html);
        var dataform=new FormData(jQuery('#wppm_task_list_frm')[0]);
        <?php echo do_action('wppm_add_new_task_dataform');?>
        dataform.append("proj_id", proj_id);
        dataform.append("action", 'wppm_add_new_task');
        jQuery.ajax( {
          url: wppm_admin.ajax_url,
          type: 'POST',
          data: dataform,
          processData: false,
          contentType: false
        }) 
        .done(function( response ) {
          jQuery('#wppm_task_container').html(response);
        });
      }

      function wppm_apply_task_filter(){
        wppm_task_filter = jQuery('#wppm_task_filter').find(":selected").val();
        jQuery('#wppm_task_container').html(wppm_admin.loading_html);
        var dataform=new FormData(jQuery('#wppm_task_list_frm')[0]);
        <?php echo do_action('wppm_apply_task_filter_dataform');?>
          dataform.append("wppm_task_filter", wppm_task_filter);
          dataform.append("action", 'wppm_get_task_list');
          jQuery.ajax( {
            url: wppm_admin.ajax_url,
            type: 'POST',
            data: dataform,
            processData: false,
            contentType: false
          }) 
          .done(function( response ) {
            jQuery('#wppm_task_container').html(response);
          });
      }
      function wppm_tl_reset_filter(){
        jQuery('#wppm_task_container').html(wppm_admin.loading_html);
        var dataform=new FormData(jQuery('#wppm_task_list_frm')[0]);
        dataform.append("action", 'wppm_get_task_list');
        dataform.append("wppm_task_filter", 'all');
        dataform.append("task_search", '');
        dataform.append("sort_by", 'task_name');
        dataform.append("order", 'ASC');
        <?php echo do_action('wppm_tl_reset_filter_dataform');?>
        jQuery.ajax( {
          url: wppm_admin.ajax_url,
          type: 'POST',
          data: dataform,
          processData: false,
          contentType: false
        }) 
        .done(function( response ) {
          jQuery('#wppm_task_container').html(response);
        });
      }

      function wppm_task_search_filter(){
        var task_search = jQuery("#wppm_task_search_filter").val();
        jQuery('#wppm_task_container').show();
        jQuery('#wppm_task_container').html(wppm_admin.loading_html);
        var dataform=new FormData(jQuery('#wppm_task_list_frm')[0]);
        <?php echo do_action('wppm_task_search_filter_dataform');?>
        dataform.append("action", 'wppm_get_task_list');
        dataform.append("task_search", task_search);
        jQuery.ajax( {
          url: wppm_admin.ajax_url,
          type: 'POST',
          data: dataform,
          processData: false,
          contentType: false
        }) 
        .done(function( response ) {
          jQuery('#wppm_task_container').html(response);
        });
      }

      function wppm_display_grid_view(){
        var task_search = jQuery("#wppm_view_task_search_filter").val();
        jQuery('#wppm_task_container').show();
        jQuery('#wppm_task_container').html(wppm_admin.loading_html);
        var dataform=new FormData(jQuery('#wppm_view_project_task')[0]);
        <?php echo do_action('wppm_display_grid_view_dataform');?>
        dataform.append("action", 'wppm_get_task_list_card_view');
        dataform.append("task_search", task_search);
        jQuery.ajax( {
          url: wppm_admin.ajax_url,
          type: 'POST',
          data: dataform,
          processData: false,
          contentType: false
        }) 
        .done(function( response ) {
          jQuery('#wppm_task_container').html(response);
        });
      }

      function wppm_sort_up_task_list(sort_by,order){
        jQuery('#wppm_task_container').show();
        jQuery('#wppm_task_container').html(wppm_admin.loading_html);
        var dataform=new FormData(jQuery('#wppm_task_list_frm')[0]);
        <?php echo do_action('wppm_sort_up_task_list_dataform');?>
        dataform.append("action", 'wppm_get_task_list');
        dataform.append("sort_by", sort_by);
        dataform.append("order", order);
        jQuery.ajax( {
          url: wppm_admin.ajax_url,
          type: 'POST',
          data: dataform,
          processData: false,
          contentType: false
        }) 
        .done(function( response ) {
          jQuery('#wppm_task_container').html(response);
        });
      }

      function wppm_view_task_search_filter(page_no){
        var task_search = jQuery("#wppm_view_task_search_filter").val();
        if(typeof task_search == "undefined"){
          var task_search ="";
        }else{
          var task_search =jQuery("#wppm_view_task_search_filter").val();
        }
        jQuery('#wppm_task_container').show();
        jQuery('#wppm_task_container').html(wppm_admin.loading_html);
        var dataform=new FormData(jQuery('#wppm_view_project_task')[0]);
        <?php echo do_action('wppm_view_task_search_filter_dataform');?>
        dataform.append("action", 'wppm_get_task_list_card_view');
        dataform.append("task_search", task_search);
        dataform.append("page_no",page_no);
        jQuery.ajax( {
          url: wppm_admin.ajax_url,
          type: 'POST',
          data: dataform,
          processData: false,
          contentType: false
        }) 
        .done(function( response ) {
          jQuery('#wppm_task_container').html(response);
        });
      }

      function wppm_apply_task_filter_grid_view(){
        wppm_task_filter = jQuery('#wppm_task_filter').find(":selected").val();
        jQuery('#wppm_task_container').html(wppm_admin.loading_html);
        var dataform=new FormData(jQuery('#wppm_view_project_task')[0]);
        <?php echo do_action('wppm_apply_task_filter_grid_view_dataform');?>
        dataform.append("action", 'wppm_view_project_tasks');
        dataform.append("wppm_task_filter", wppm_task_filter);
        jQuery.ajax( {
          url: wppm_admin.ajax_url,
          type: 'POST',
          data: dataform,
          processData: false,
          contentType: false
        }) 
        .done(function( response ) {
          jQuery('#wppm_task_container').html(response);
        });
      }

      function wppm_tl_reset_grid_view_filter(){
        jQuery('#wppm_task_container').html(wppm_admin.loading_html);
        var dataform=new FormData(jQuery('#wppm_view_project_task')[0]);
        <?php echo do_action('wppm_tl_reset_grid_view_filter_dataform');?>
        dataform.append("action", 'wppm_view_project_tasks');
        dataform.append("task_search", "");
        dataform.append("wppm_task_filter", "all");
        dataform.append("sort_by", "task_name");
        dataform.append("order", "ASC");
        jQuery.ajax( {
          url: wppm_admin.ajax_url,
          type: 'POST',
          data: dataform,
          processData: false,
          contentType: false
        }) 
        .done(function( response ) {
          jQuery('#wppm_task_container').html(response);
        });
      }
    </script>
  <?php } 
}  
  ?>