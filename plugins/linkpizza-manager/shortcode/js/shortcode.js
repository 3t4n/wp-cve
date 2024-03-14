/**
 * Created by arjanpronk on 15/06/16.
 */


jQuery(document).ready(function($) {

    var pzz_values = [];
    if(pzz_widget_loading_error == false && pzz_widgets.length > 0){
        for (var i =0; i< pzz_widgets.length; i++) {
            pzz_values.push( { 'text':pzz_widgets[i].title, 'value':pzz_widgets[i].id} );
        }
    }else{
        pzz_select_label = pzz_error_label;
    }

    tinymce.PluginManager.add('pzzwidget_button', function (editor, url) {
        editor.addButton('pzzwidget_button', {
            icon: true,
            image: url + '/Icon-grey.png',
            title: pzz_title_label,
            onclick: function () {
                editor.windowManager.open({
                    title: pzz_title_label,
                    body: [
                        {
                            type: 'listbox',
                            name: 'pzz_widget_options',
                            label: pzz_select_label,
                            values: pzz_values,
                            value: '3'
                        },
                        {
                            type: 'textbox',
                            name: 'height',
                            label: pzz_height_label,
                            value  : '300'
                        },
                        {
                            type: 'textbox',
                            name: 'width',
                            label: pzz_width_label,
                            value: '300'
                        }
                    ],
                    onsubmit: function (v) {
                        content =  '[pzzwidget id='+v.data.pzz_widget_options+' height='+v.data.height+' width='+v.data.width+']';
                        tinymce.execCommand('mceInsertContent', false, content);
                    }
                });
            }
        });
    });
});