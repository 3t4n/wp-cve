(function ($) {
  var plugin_row = $("tr[data-slug='wordpress-database-reset']");
  if (plugin_row.length == 1) {
    var row = '<tr class="plugin-update-tr active"><td colspan="4" class="plugin-update colspanchange"><div class="update-message notice inline notice-error notice-alt">';
    row += '<p style="padding: 10px;">WP Database Reset has been replaced by the free <a href="' + db_reset.info_link +'" class="thickbox open-plugin-details-modal">WP Reset</a> plugin and it will soon be removed from the plugin repository.<br>Please <a href="' + db_reset.install_link + '">click here to install WP Reset</a> - it\'s free, used by over 300,000 people and has numerous reset tools. If you have any questions - <a href="https://wordpress.org/support/plugin/wordpress-database-reset/" target="_blank">contact support</a></p>';
    row += '</div></td></tr>';

    $(row).insertAfter($(plugin_row));
    $(plugin_row).addClass('update');
  }
})(jQuery);
