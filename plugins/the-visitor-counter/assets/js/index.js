jQuery(document).ready(function () {

    // Background Color

    var bgPreview = jQuery('#background-color-preview');
    var bgInput = jQuery('input#bg-color');

    Pickr.create({
        el: '#back',
        useAsButton: true,
        components: {
            // Main components
            preview: true,
            opacity: true,
            hue: true,

            // Input / output Options
            interaction: {
                hex: true,
                rgba: true,
                hsva: true,
                input: true,
                clear: true,
                save: true
            }
        },
        onChange(hsva, instance) {
            bgPreview.css('background-color', hsva.toHEX().toString());
        },
        onSave(hsva, instance) {
            bgInput.val(hsva.toHEX().toString());
        }
    });

    // Text Color
    var textPreview = jQuery('#text-color-preview');
    var textInput = jQuery('input#text-color');

    Pickr.create({
        el: '#text',
        useAsButton: true,
        components: {
            // Main components
            preview: true,
            opacity: true,
            hue: true,

            // Input / output Options
            interaction: {
                hex: true,
                rgba: true,
                hsva: true,
                input: true,
                clear: true,
                save: true
            }
        },
        onChange(hsva, instance) {
            textPreview.css('background-color', hsva.toHEX().toString());
        },
        onSave(hsva, instance) {
            textInput.val(hsva.toHEX().toString());
        }
    });
});
