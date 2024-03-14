jQuery(document).ready(function($){
    if (typeof acf == 'undefined' || !$('body').hasClass('post-php'))
        return;

    /** Add global variable "AFLM" inside acf global object. */
    if (acf.AFLM == 'undefined')
    acf.AFLM = true;
    // acf.AFLM = { 'features': ['add-auto-single-layout'] };

    /** Push this feature inside the features array of the global variable. */
    // acf.AFLM.features.push('add-auto-single-layout')
    
    var is_acf_version_5_7_or_more = (typeof acf.data != 'undefined' && typeof acf.data.acf_version != 'undefined' && parseFloat(acf.data.acf_version) >= 5.7);
        
    /** If ACF version is 5.7+, then use new JS syntax */
    if (is_acf_version_5_7_or_more) {

        /**
         *  Variables
         */
        var flexibleContent     = acf.getField($('.acf-field-flexible[data-type="flexible_content"]'));
        var addButtonSelector   = '[data-name="add-layout"]';

        /**
         *  PIT - ACF Flexible Content
         *  Add automatically one layout & prevent tooltip.
         */
        function acf_fc_auto_add_single_layout() {

            flexibleContent.on('click', addButtonSelector, function() {
                /** We have to use our event listener on the main flexible of the page to make it work with dynamically added elements inside (like new added layouts). */

                var $addButton          = $(this);
                var $acfTooltip         = $('.acf-fc-popup');
                var $flexibleParents    = $addButton.parents('.acf-input').parent();
                var $closestFlexParent  = $($flexibleParents[$flexibleParents.length - 1]);
                var acfFieldKey         = $closestFlexParent.data('key');
                var $acfField           = acf.findFields({
                    'key': acfFieldKey,
                    'parent': $closestFlexParent.parent(),
                    'sibling': false,
                });

                var flexibleTarget      = acf.getField($acfField);
                var $popupContent       = $(flexibleTarget.getPopupHTML());

                var $layout = null;
                if ($addButton.hasClass('acf-icon')) {
                    $layout = $addButton.closest('.layout');
                    $layout.addClass('-hover');
                }

                /** If there is only one layout to add */
                if ($popupContent.find('a').length == 1) {

                    var $layout         = $popupContent.find('a');
                    var layoutName      = $layout.data('layout');

                    /** Add new single layout */
                    flexibleTarget.add({
                        layout: layoutName,
                        before: false
                    });

                    /** Prevent tooltip from showing */
                    if ($acfTooltip.length)
                        $acfTooltip.hide();
                }
            });
        }

        /** Init */
        acf_fc_auto_add_single_layout();

    /** Old JS ACF syntax */
    } else {

        // ACF Flexible Content: Directly add layout if there's only one layout
        var flexible_content_open = acf.fields.flexible_content._open;
        acf.fields.flexible_content._open = function(e){
            
            var $popup = $(this.$el.children('.tmpl-popup').html());
            
            // Count layouts
            if($popup.find('a').length == 1){
                acf.fields.flexible_content.add($popup.find('a').attr('data-layout'));
                return false;
            }
            
            // More than one layout? Continue the JS execution
            return flexible_content_open.apply(this, arguments);
        }
    }
});