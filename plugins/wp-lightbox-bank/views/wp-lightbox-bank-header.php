<script>
   jQuery(document).ready(function ()
   {
      jQuery(".nav-tab-wrapper > a#<?php echo esc_attr($_REQUEST["page"]); ?>").addClass("nav-tab-active");
   });
</script>
<h2 class="nav-tab-wrapper" style="max-width: 1000px;">
   <a class="nav-tab " id="wp_lightbox_bank" href="admin.php?page=wp_lightbox_bank"><?php _e("General Settings", wp_lightbox_bank); ?></a>
   <a class="nav-tab " id="wplb_display_settings" href="admin.php?page=wplb_display_settings"><?php _e("Display Settings", wp_lightbox_bank); ?></a>
   <a class="nav-tab " id="wplb_system_status" href="admin.php?page=wplb_system_status"><?php _e("System Status", wp_lightbox_bank); ?></a>
   <a class="nav-tab " id="wplb_recommendation" href="admin.php?page=wplb_recommendation"><?php _e("Recommendations", wp_lightbox_bank); ?></a>
   <a class="nav-tab " id="wplb_other_services" href="admin.php?page=wplb_other_services"><?php _e("Our Other Services", wp_lightbox_bank); ?></a>
</h2>