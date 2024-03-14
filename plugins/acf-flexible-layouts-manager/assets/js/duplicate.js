jQuery(document).ready(function($) {
    
    if (typeof acf == 'undefined')
        return;

    var is_acf_version_5_7_or_more = (typeof acf.data != 'undefined' && typeof acf.data.acf_version != 'undefined' && parseFloat(acf.data.acf_version) >= 5.7);

    var template = $('.acf_flm_add_layout_section').attr('data-target');

    if ( (typeof template !== 'undefined') && (template != 'new') && (template != '') ) {

        // Add icones Duplicate in post content and form (user/taxo)
        if (is_acf_version_5_7_or_more) {
            $('table.form-table > tbody > tr.acf-field-flexible-content > .acf-input > .acf-flexible-content > .values > .layout > .acf-fc-layout-controls a.acf-icon.-plus.small.light.acf-js-tooltip').before('<a class="acf-js-tooltip acf-flm dashicons dashicons-admin-page acf-icon-clone" href="javascript:void(0);" title="' + tradObject.duplication + '"></a>');
            $('.acf-postbox > .acf-fields > .acf-field-flexible-content > .acf-input > .acf-flexible-content > .values > .layout > .acf-fc-layout-controls a.acf-icon.-plus.small.light.acf-js-tooltip').before('<a class="acf-js-tooltip acf-flm dashicons dashicons-admin-page acf-icon-clone" href="javascript:void(0);" title="' + tradObject.duplication + '"></a>');
        } else {
            $('table.form-table > tbody > tr.acf-field-flexible-content > .acf-input > .acf-flexible-content > .values > .layout > .acf-fc-layout-controlls a.acf-icon.-plus.small.light.acf-js-tooltip').before('<a class="acf-js-tooltip acf-flm dashicons dashicons-admin-page acf-icon-clone" href="javascript:void(0);" title="' + tradObject.duplication + '"></a>');
            $('.acf-postbox > .acf-fields > .acf-field-flexible-content > .acf-input > .acf-flexible-content > .values > .layout > .acf-fc-layout-controlls a.acf-icon.-plus.small.light.acf-js-tooltip').before('<a class="acf-js-tooltip acf-flm dashicons dashicons-admin-page acf-icon-clone" href="javascript:void(0);" title="' + tradObject.duplication + '"></a>');
        }
        
        // Function to trigger click on the button
        triggerCLickDuplicate();
        
        // When new layout is add in flexible
        is_acf_version_5_7_or_more ?
            acf.addAction('append', function($el) { triggerCLickDuplicate(); })
            : acf.add_action('append', function($el) { triggerCLickDuplicate(); });
    }

    // Click on Duplicate
    function triggerCLickDuplicate() {

        $('.acf-icon-clone').click(function() {

            if (confirm(tradObject.confirmDuplication)) {
                
                //Get the name of the layout
                var nameLayout  = is_acf_version_5_7_or_more ? $(this).parent('.acf-fc-layout-controls').siblings('input[type="hidden"]').attr('name') : $(this).parent('.acf-fc-layout-controlls').siblings('input[type="hidden"]').attr('name');
                
                //Find the position of the second array in the name. Exemple acf[field_5ac35fefd0d75][3][acf_fc_layout] or acf[field_5ac35fefd0d75][5ac483673c181][acf_fc_layout]
                var $start      = nameLayout.indexOf('[', nameLayout.indexOf('[') + 1);
                var $end        = nameLayout.indexOf(']', nameLayout.indexOf(']') + 1);

                $start++;
                //Position of the layout in the template. With the previous exemple : 3 or 5ac483673c181
                var position    = nameLayout.substr($start, $end - $start);
                
                //Temporary fix ACF update < 5.8.1 
                if(position.indexOf("row-") === 0){
                    position = position.substr(4);
                }

                //Fix to check if it's a new layout. More than 4 number the field is new
                if (position.length > 4) {
                    alert(tradObject.problemDuplication);
                } else {

                    var divFlexible = $(this).parents('.acf-field-flexible-content');
                    var key         = divFlexible.attr('data-key');
                    var flexible    = divFlexible.attr('data-name');
                    var post_id     = divFlexible.find('.acf_flm_add_layout_section').attr('data-target');
                    
                    //Build params for AJAX request
                    data = {
                        action: 	'acf_flm_layout_duplicate',
                        position: 	position,
                        post_id: 	post_id,
                        flexible:   flexible,
                        key: key
                    };
                    
                    //Add loader
                    divFlexible.find('.acf-input').html('<div class="acf-fields -border" style="padding:7px; text-align:center;"><div class="spinner is-active" style="float:none;"></div></div>');
                    
                    //Update flexible with AJAX call
                    $.post(ajaxurl, data, function(response) {
                        if (response.success) {
                            location = location.href;
                        }
                    });
                }
            }
        });
    }
});