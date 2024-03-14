<?php
delete_option("conwr_email");
delete_option("conwr_api_key");
delete_option("conwr_admin_message");
delete_option("conwr_adjust_every");
delete_option("conwr_best_feed");
delete_option("conwr_hide_body");
delete_option("conwr_ignore_users");
delete_option("conwr_search_engines");
delete_option("conwr_skip_pages");
delete_option("conwr_use_js");

//backward compatibility
delete_option("stcon_api_key");
delete_option("stcon_email");
delete_option("scmtt_adjust_every");
delete_option("scmtt_best_feed");
delete_option("scmtt_hide_body");
delete_option("scmtt_ignore_users");
delete_option("scmtt_search_engines");
delete_option("scmtt_skip_pages");
delete_option("scmtt_use_js");
?>