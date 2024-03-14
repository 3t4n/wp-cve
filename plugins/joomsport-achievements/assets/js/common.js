function JSACH_filteredPartic(el){
    var fltrs = jQuery('select[name^="ef["]');
    var fltArr = {};
    for(var i=0; i<fltrs.length; i++){
        if(jQuery(fltrs[i]).val() != 0){
            fltArr[jQuery(fltrs[i]).attr("id")] = jQuery(fltrs[i]).val();
        }
    }
    //console.log(JSON.stringify(fltArr));
    var data = {
        'action': 'stageadf_filters',
        'jsfilters': JSON.stringify(fltArr),
    };

    jQuery.post(ajaxurl, data, function(response) {

       jQuery( "#JSACHV_participiants" ).html(response);
       //jQuery( "#JSACHV_participiants" ).trigger("liszt:updated");
       jQuery( "#JSACHV_participiants" ).trigger("chosen:updated");

    });
}
function JSACHV_updateRes(){
    
}
jQuery( document ).ready(function() {
    
    var tblTitles = [];
    jQuery('#JSACHV_results_TBL thead tr th').each(function(){
        tblTitles.push(jQuery(this).attr('adfIndex'));
        
    });
    
    
    jQuery('#JSACHV_participiants_SALL').click(function(){
        jQuery("#JSACHV_participiants option").each(function(){
            jQuery(this).attr('selected', true);
        });
        jQuery( "#JSACHV_participiants" ).trigger("chosen:updated");
    });
    jQuery('#JSACHV_participiants_ADD').click(function(){
        var vals = [];
        jQuery('input[name="partic_id[]"]').each(function(){
            vals.push(jQuery(this).val());
        });
        

        jQuery("#JSACHV_participiants option:selected").each(function(){

            if(jQuery.inArray( jQuery(this).val(), vals ) == -1){
                
                //jQuery('#JSACHV_results_TBL tbody').append('<tr><td><a href="javascript:void(0);" onclick="javascript:(this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode));"><i class="fa fa-trash" aria-hidden="true"></i></a><input type="hidden" name="partic_id[]" value="'+jQuery(this).val()+'"></td><td>'+jQuery(this).text()+'</td></tr>');
                var tr = jQuery('<tr />');
                for(var i=0;i<tblTitles.length; i++){
                    if(tblTitles[i] == '-1'){
                        var td = '<td><a href="javascript:void(0);" onclick="javascript:(this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode));"><i class="fa fa-trash" aria-hidden="true"></i></a><input type="hidden" name="partic_id[]" value="'+jQuery(this).val()+'"></td>';
                    }else if(tblTitles[i] == '0'){
                        var td = '<td>'+jQuery(this).text()+'</td>';
                    }else{
                        var td = '<td><input type="text" class="JSACHV_result_input" name="field_'+tblTitles[i]+'[]"></td>';
                    }
                    tr.append(td);
                }
                jQuery('#JSACHV_results_TBL tbody').append(tr);
            }
            jQuery(this).attr('selected', false);
        });
        jQuery( "#JSACHV_participiants" ).trigger("chosen:updated");
    });
    
    jQuery('body').on('focus',".jsachvdatefield", function(){
        jQuery(this).datepicker({ dateFormat: 'yy-mm-dd'});
   });
   jQuery(".jswf-chosen-select").chosen({disable_search_threshold: 10,width: "100%",disable_search:false});
    
    
});
function Delete_tbl_row(element) {
        var del_index = element.parentNode.parentNode.sectionRowIndex;
        var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
        element.parentNode.parentNode.parentNode.deleteRow(del_index);
}
function add_selval(){
    if(!jQuery("#addsel").val()){
            return false;
    }
    jQuery("#seltable>tbody").append('<tr class="ui-state-default"><td class="jsdadicon"><i class="fa fa-bars" aria-hidden="true"></i></td><td class="jsdadicondel"><input type="hidden" name="adeslid[]" value="0" /><a href="javascript:void(0);" title="Remove" onClick="javascript:delJoomSportAchvSelRow(this);"><input type="hidden" value="0" name="selid[]" /><i class="fa fa-trash" aria-hidden="true"></i></a></td><td><input type="text" name="selnames[]" value="'+jQuery("#addsel").val()+'" /></td></tr>');
    jQuery("#addsel").val('');
}
jQuery( document ).ready(function() {
    jQuery("#seltable>tbody").sortable(

    );
});    

jQuery(document).ready( function(){
    jQuery("body").on("click",".jsfw-enable",function(){
        var parent = jQuery(this).parents('.jsw_switch');
        jQuery('.jsfw-disable',parent).removeClass('selected');
        jQuery('.jsfw-enable',parent).removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('.checkbox',parent).attr('checked', true);
    });
    jQuery("body").on("click",".jsfw-disable",function(){
        var parent = jQuery(this).parents('.jsw_switch');
        jQuery('.jsfw-enable',parent).removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('.checkbox',parent).attr('checked', false);
    });
    
    jQuery("body").on("change","select[name='seasonRanking']",function(){
        if(jQuery(this).val() == 0){
            jQuery('.tblAchvPoints').show();
            jQuery('.jsach_method').hide();
        }else{
            jQuery('.tblAchvPoints').hide();
            jQuery('.jsach_method').show();
        }
        
    });

    
});    

jQuery(document).ready(function(){
    
    
    jQuery("body.post-type-jsprt_achv_stage .wrap .page-title-action").on("click",function(e){
        e.preventDefault();
        jQuery("<div></div>").attr('id','jsSeasSelect').appendTo('body');  
        var addnew = jQuery(this);
        var data = {
        'action': 'achvstage_seasonmodal',
        };

        jQuery.post(ajaxurl, data, function(response) {

           jQuery( "#jsSeasSelect" ).html(response);

        });
        jQuery( "#jsSeasSelect" ).dialog({modal: true,height: 250,width:450,
            buttons: {
              Next: function() {
                if(jQuery('#season_id').val()){
                    jQuery( this ).dialog( "close" );
                    
                    location.href = addnew.attr('href') + '&season_id='+jQuery('#season_id').val();
                }   
                
              }
            }


        });
        
    });
});    

function delJoomSportAchvSelRow(element) {
        var del_index = element.parentNode.parentNode;
        del_index.parentNode.removeChild(del_index);

}
jQuery(document).ready(function(){  
    jQuery('body').on('click','.jsportPopUl input',function(){
        var id = jQuery(this).attr("id");
        jQuery('.jsportPopUl textarea').hide();
        jQuery('#'+id+'_text').show();
    });
    
    jQuery('body').on('click','#jsportPopSkip',function(event){
        event.preventDefault();
        if(jQuery('#jsDeactivateOpt1').is(':checked')){
           var disb = 1; 
        }else{
            disb = 0;
        }
        var data = {
            'action': 'jsarch-updoption',
            'option': disb,
        };
        var href = jQuery(this).attr('href');
        jQuery.post(ajaxurl, data, function(response) {
            window.location = href;
        });
    });
    jQuery('body').on('click','#jsportPopSend',function(event){
        event.preventDefault();
        var ch_type = jQuery('input[name="jsDeactivateReason"]:checked').val();
        if(ch_type){
            var ch_text = jQuery('#jsDeactivateReason'+ch_type+'_text').val();
            
             if(jQuery('#jsDeactivateOpt1').is(':checked')){
                var disb = 1; 
             }else{
                 disb = 0;
             }
             var data = {
                 'action': 'jsarch-updoption',
                 'option': disb,
             };
             jQuery.post(ajaxurl, data, function(response) {
             });
             
             var href = jQuery(this).attr('href');
             var data = {
                    'action': 'jsarch-senddeactivation',
                    'ch_type': ch_type,
                    'ch_text': ch_text,
                };
             jQuery.post(ajaxurl, data, function(response) {
                 window.location = href;

             });
            
            
        }
    });
    
    jQuery('#jsach_type').on('change',function(){
        if(jQuery(this).val() == '2'){
            jQuery(".stagelistDiv").show();
        }else{
            jQuery(".stagelistDiv").hide();
        }
    });

    jQuery('.wpjsaDeleteConfirm').on('click', function () {
        return confirm('Are you sure you want to delete?');
    });
});