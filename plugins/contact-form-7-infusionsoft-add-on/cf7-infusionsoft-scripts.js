(function($){
    'use strict';

    $(document).ready(function() {
        var $InfusionSoftFieldNames = $('#infusionsoft-field-name');
        var $form = $InfusionSoftFieldNames.closest('form');
        var $tagGenerator = $form.find('.insert-box > input:not(.hidden)');

        $form.change(function() {
            var tag = $tagGenerator.val();
            var selectedField = $InfusionSoftFieldNames.val();

            // Update the generated tag
            if ( selectedField != '0' ) {
                // Form Tag
                if ( tag.match(/(\s+infusionsoft[a-z-]+)/) ) {
                    $tagGenerator.val(tag.replace(/(\s+infusionsoft[a-z-]+)/, selectedField));
                } else {
                    $tagGenerator.val(tag.replace(/(^\[\w+\*?)/, '$1 infusionsoft-' + selectedField));
                }
                // Mail tag
                $form.find('#tag-generator-panel-infusionsoft-mailtag').val('[infusionsoft-' + selectedField + ']');
                $form.find('span.mail-tag').text('[infusionsoft-' + selectedField + ']');
            } else {
                $tagGenerator.val(tag.replace(/(\s+infusionsoft[a-z-]+)/, ''));
                $form.find('#tag-generator-panel-infusionsoft-mailtag').val('[]');
                $form.find('span.mail-tag').text('[]');
            }
            
        });
    });
})(jQuery);
