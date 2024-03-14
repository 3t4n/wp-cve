jQuery(document).ready(function() {
    if(tab == 'content'){
        if(number_columns == 1 || number_columns == 3){
            wp.codeEditor.initialize(document.querySelector("#options_cb_top_bar_content\\[center-content-top-bar-plugin\\]"), cm_html_settings);        
        }
        if(number_columns >= 2){
            wp.codeEditor.initialize(document.querySelector("#options_cb_top_bar_content\\[right-content-top-bar-plugin\\]"), cm_html_settings);
            wp.codeEditor.initialize(document.querySelector("#options_cb_top_bar_content\\[left-content-top-bar-plugin\\]"), cm_html_settings);
        }
    }else{
        jQuery('#options_cb_top_bar\\[color-top-bar-plugin\\]').wpColorPicker();
        jQuery('#options_cb_top_bar\\[color-text-top-bar-plugin\\]').wpColorPicker();
        wp.codeEditor.initialize(document.querySelector("#options_cb_top_bar\\[custom-css-top-bar-plugin\\]"), cm_settings);
    }
  })