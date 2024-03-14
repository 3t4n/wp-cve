(function() {
    tinymce.PluginManager.add('pfai_button_script', function( editor, url ) {
        editor.addButton( 'pfai_button', {
            text: '[FA ICON]',
            icon: false,
            onclick: function() {
    editor.windowManager.open( {
        title: 'Insert Font Awesome Icon',
        body: [{
            type: 'textbox',
            name: 'faiconcodek',
            label: 'Complete Icon Class:(Eg: fab fa-accessible-icon)'
        },
{
            type: 'textbox',
            name: 'colork',
            label: 'Color:(Eg: #333333) leave blank for default'
        },
        {
           type: 'listbox', 
            name: 'sizek', 
            label: 'Size', 
            'values': [
                {text: 'Regular', value: ''},
                {text: 'Large', value: 'fa-lg'},
                {text: '2X', value: 'fa-2x'},
                {text: '3X', value: 'fa-3x'},
                {text: '4X', value: 'fa-4x'},
                {text: '5X', value: 'fa-5x'}
            ]
        },
     {
           type: 'listbox', 
            name: 'alignmentk', 
            label: 'Alignment', 
            'values': [
                {text: 'None', value: ''},
                {text: 'Align with text', value: 'fa-fw'}
            ]
        },
{
           type: 'listbox', 
            name: 'borderk', 
            label: 'Border', 
            'values': [
                {text: 'No', value: ''},
                {text: 'Yes', value: 'fa-border'}
            ]
        },
{
           type: 'listbox', 
            name: 'floatk', 
            label: 'Float', 
            'values': [
                {text: 'None', value: ''},
                {text: 'Left', value: 'fa-pull-left'},
                {text: 'Right', value: 'fa-pull-right'}
            ]
        },
{
           type: 'listbox', 
            name: 'animatek', 
            label: 'Animate', 
            'values': [
                {text: 'None', value: ''},
                {text: 'Spin', value: 'fa-spin'}
            ]
        },
        {
            type: 'listbox', 
            name: 'rotatek', 
            label: 'Rotate/Flip', 
            'values': [
                {text: 'none', value: ''},
                {text: 'Rotate 90degree', value: 'fa-rotate-90'},
                {text: 'Rotate 180degree', value: 'fa-rotate-180'},
                {text: 'Rotate 270degree', value: 'fa-rotate-270'},
                {text: 'Flip Horizontal', value: 'fa-flip-horizontal'},
                {text: 'Flip Vertical', value: 'fa-flip-vertical'}
            ]
        }],
        onsubmit: function( e ) {
            editor.insertContent( '[pfai pfaic="' + e.data.faiconcodek + ' ' + e.data.sizek + ' ' + e.data.alignmentk + ' ' + e.data.borderk + ' '  + e.data.floatk + ' ' + e.data.animatek + ' ' + e.data.rotatek + '" pfaicolr="'+ e.data.colork +'" ]');
        }
    });
}
        });
    });
})();