(function($){
    $(document).ready(function(){

        $(document).on('change', '.moove-taxonomy-type-select select', function(){
            var tax = $(this).closest('tr').attr('data-tax');
            if ( $( this ).val() === 'checkbox' ) {
                $( this ).closest('td').find( '.moove_select_all_btn' ).removeClass("moove-hidden").show();
                $(this).closest('table').find('tr.default-taxonomy-select[data-tax="'+tax+'"]').removeClass('hidden-tb-row');
            } else {    
                $( this ).closest('td').find(' .moove_select_all_btn' ).addClass("moove-hidden").hide();
                if ( $(this).val() === 'radio' ) {
                    $(this).closest('table').find('tr.default-taxonomy-select[data-tax="'+tax+'"]').removeClass('hidden-tb-row');
                } else {
                    $(this).closest('table').find('tr.default-taxonomy-select[data-tax="'+tax+'"]').addClass('hidden-tb-row');
                }
            }
        });
        $(document).on( 'click','.moove-radioselect-selectall', function(e){
            e.preventDefault();
            cntid = $( this ).closest('.tabs-panel').attr('id');
            $sector_checkBoxes = $('div#'+cntid+'.tabs-panel input[type="checkbox"]');
            $sector_selected_checkBoxes = $('div#'+cntid+'.tabs-panel input[type="checkbox"]:checked');

            if ($(this).hasClass('moove-radioselect-deselect')) {
                $sector_checkBoxes.attr( "checked", false );
            } else {
                $sector_checkBoxes.attr( "checked", true );
            }
            $( this ).toggleClass( 'moove-radioselect-deselect' ).attr('id');
        });
        $(document).on('click','.moove_updated_taxonomy_select_switcher .category-tabs li > a',function(e){
            e.preventDefault();
            var id = $(this).attr('href');
            $(this).closest('.moove_updated_taxonomy_select_switcher').find('.tabs').removeClass('tabs');
            $(this).closest('li').addClass('tabs');
            $(this).closest('.moove_updated_taxonomy_select_switcher').find('.tabs-panel').hide();
            $(id).show();
        });

        $(document).find('.moove-tax-mainchecklist').each(function(){
            var _this = $(this)[0];
            var parent_element = $(this);
            var top_parent = $(this).closest('.moove_radioselect-radio');
            if ( top_parent.length ) {
                var tax = top_parent.attr('data-taxonomy');
                _this.addEventListener ('DOMNodeInserted', function(e){
                    var element = e.target;
                    if ( $(element).length > 0 ) {
                        var cat_hdn = parent_element.closest('.tabs-panel').find('>input[type="hidden"]');
                        if ( cat_hdn.length > 0 ) {
                            var input = $(element).find('input');
                            var hierarchical = input.closest('ul').attr('data-hierarchical') === 'hierarchical';
                            input.attr('type', 'radio');
                            if ( ! hierarchical ) {
                                input.val($(element).text().trim());
                            }
                            var _val = input.val();
                            if ( _val ) {
                                _this.find('input[type="radio"][value="'+_val+'"]').prop('checked',true);
                                cat_hdn.val(_val);
                            }
                        }
                    }
                }, false);
            }
        });

        $(document).on('change','.moove-tax-popular input[type="radio"]',function(e){
            var selected = $(this).val();
            // console.log(selected);
            $(this).closest('.moove_updated_taxonomy_select_switcher').find('.moove-tax-mainchecklist input[type="radio"][value="'+selected+'"]').prop('checked',true);
        });

        $(document).on('change','.moove-tax-mainchecklist input[type="radio"]',function(e){
            
            $(this).closest('.moove_updated_taxonomy_select_switcher').find('.moove-tax-popular input[type="radio"]').prop('checked',false);
        });
        $(document).on('click','.category-add-submit',function(){
            var main_checklist = $(this).closest('.moove_updated_taxonomy_select_switcher.moove_radioselect-radio').find('ul.moove-tax-mainchecklist');
            if ( main_checklist.length > 0 ) {
                $("body").on('DOMSubtreeModified', main_checklist, function() {
                    var checkbox = main_checklist.find('input[type="checkbox"]');
                    if ( checkbox.length > 0 ) {
                        var text = checkbox.closest('label').text().trim();
                        // checkbox.attr('type','radio').attr('value',text).prop('checked',true);
                    }
                });
            }
        });

        

    });
})(jQuery);
