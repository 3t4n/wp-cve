document.addEventListener('DOMContentLoaded', function (e) {
    // Single Row Subheading
    jQuery('.white-label-subheading').each(function (index, element) {
        var $element = jQuery(element);
        var $element_parent = $element.parent('td');
        $element_parent.attr('colspan', 2);
        $element_parent.prev('th').remove();
    });

    // Modify Settings Tables
    const settings_tables = ['admin_welcome_panel_content', 'admin_remove_dashboard_widgets', 'admin_widget_content', 'admin_custom_dashboard_content', 'hidden_plugins', 'hidden_themes', 'sidebar_menus', 'hidden_admin_bar_nodes_backend', 'hidden_admin_bar_nodes_frontend', 'admin_footer_credit'];
    settings_tables.forEach((setting) => {
        jQuery('tr.' + setting + ' th').remove();
        jQuery('tr.' + setting + ' > td:first-of-type').attr('colspan', 2);
        jQuery('tr.' + setting + ' > td:first-of-type').css('padding', '0');
    });

    // Initiate Color Picker
    jQuery('.wp-color-picker-field').wpColorPicker();

    // Switches option sections
    jQuery('.group').hide();
    var banana_activetab = '';
    if (typeof(localStorage) != 'undefined' ) {
        banana_activetab = localStorage.getItem("banana_activetab");
    }

    //if url has section id as hash then set it as active or override the current local storage value
    if(window.location.hash){
        banana_activetab = window.location.hash;
        if (typeof(localStorage) != 'undefined' ) {
            localStorage.setItem("banana_activetab", banana_activetab);
        }
    }

    if (banana_activetab != '' && jQuery(banana_activetab).length ) {
        jQuery(banana_activetab).fadeIn(100);
    } else {
        jQuery('.group:first').fadeIn(100);
    }

    jQuery('.group .collapsed').each(function(){
        jQuery(this).find('input:checked').parent().parent().parent().nextAll().each(
        function(){
            if (jQuery(this).hasClass('last')) {
                jQuery(this).removeClass('hidden');
                return false;
            }
            jQuery(this).filter('.hidden').removeClass('hidden');
        });
    });

    if (banana_activetab != '' && jQuery(banana_activetab + '-tab').length ) {
        jQuery(banana_activetab + '-tab').addClass('nav-tab-active');
        white_label_login_preview(banana_activetab);
        white_label_upgrade_sidebar(banana_activetab);
    }
    else {
        jQuery('.nav-tab-wrapper a:first').addClass('nav-tab-active');
    }

    jQuery('.nav-tab-wrapper a').click(function(evt) {
        jQuery('.nav-tab-wrapper a').removeClass('nav-tab-active');
        jQuery(this).addClass('nav-tab-active').blur();
        var clicked_group = jQuery(this).attr('href');
        if (typeof(localStorage) != 'undefined' ) {
            localStorage.setItem("banana_activetab", jQuery(this).attr('href'));
        }
        jQuery('.group').hide();
        jQuery(clicked_group).fadeIn(100);
        white_label_login_preview(clicked_group);
        white_label_upgrade_sidebar(clicked_group);

        evt.preventDefault();
    });

    jQuery('.wpsa-browse').on('click', function (event) {
        event.preventDefault();

        var self = jQuery(this);

        // Create the media frame.
        var file_frame = wp.media.frames.file_frame = wp.media({
            title: self.data('uploader_title'),
            button: {
                text: self.data('uploader_button_text'),
            },
            multiple: false
        });

        file_frame.on('select', function () {
            attachment = file_frame.state().get('selection').first().toJSON();
            self.prev('.wpsa-url').val(attachment.url).change();
        });

        // Finally, open the modal
        file_frame.open();
    });
});

jQuery(document).ready(function() {
    jQuery(document).on('click', 'a.wl-show-field', function(){
        var wl_plugin_details = jQuery(this).attr('data-wl-plugin-details');
        var is_hidden = jQuery('td#' + wl_plugin_details + ' div.wl-field').hasClass('hidden')

        if (is_hidden) {
            jQuery('td#' + wl_plugin_details + ' a.white-label-help').removeClass('hidden');
            jQuery('td#' + wl_plugin_details + ' div.wl-field').removeClass('hidden');
        } else {
            jQuery('td#' + wl_plugin_details + ' a.white-label-help').addClass('hidden');
            jQuery('td#' + wl_plugin_details + ' div.wl-field').addClass('hidden');
        }

        var wl_theme_details = jQuery(this).attr('data-wl-theme-details');
        var is_hidden = jQuery('td#' + wl_theme_details + ' div.wl-field').hasClass('hidden')

        if (is_hidden) {
            jQuery('td#' + wl_theme_details + ' a.white-label-help').removeClass('hidden');
            jQuery('td#' + wl_theme_details + ' div.wl-field').removeClass('hidden');
        } else {
            jQuery('td#' + wl_theme_details + ' a.white-label-help').addClass('hidden');
            jQuery('td#' + wl_theme_details + ' div.wl-field').addClass('hidden');
        }
    });
});

function white_label_login_preview(tab) {
  // Hide Login Sidebar When Accessing Another Tab
  if (tab != '#white_label_login') {
      jQuery('.white-label-preview-box').hide();
  } else {
      jQuery('.white-label-preview-box').fadeIn(100);
  }
}

function white_label_upgrade_sidebar(tab) {
  // Hide Upgrade Sidebar When Accessing Upgrade Tab
  if (tab == '#white_label_upgrade') {
      jQuery('.white-label-sidebar > .white-label-metabox:nth-of-type(2)').hide();
  } else {
      jQuery('.white-label-sidebar .white-label-metabox').fadeIn(100);
  }
}