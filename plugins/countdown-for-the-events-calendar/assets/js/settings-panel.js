(function( $ ) {
$(document).ready(function(){
    $('#select_all').on('click',function(){
        
        if(this.checked){
           
            $('.tecc-checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.tecc-checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.tecc-checkbox').on('click',function(){
        if($('.tecc-checkbox:checked').length == $('.tecc-checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
 

    var TECConAutoStartChange = function (){
        var optionText = $(".tecc-autostart select option:selected").val();                
        if(optionText=='no'){
            $('.tecc-events-list').hide();
            $('.tecc-single-event').show();
            $('.tecc-start-text').show();
            $('.tecc-end-text').show();
            $('.tecc-autostart-text').hide();   
            $('.tecc-autostart-future').hide();           
        }
        else{
            $('.tecc-events-list').show();
            $('.tecc-single-event').hide();
            $('.tecc-start-text').hide();
            $('.tecc-end-text').hide();
            $('.tecc-autostart-text').show();
            $('.tecc-autostart-future').show();            
        }
    }
    $('.tecc-autostart select').on('change', TECConAutoStartChange );    
    TECConAutoStartChange();

    var TECConfutureAutoStartChange = function (){
        var optionText = $(".tecc-autostart-future select option:selected").val();                
        if(optionText=='no'){
            $('.tecc-events-list').show();          
        }
        else{
            $('.tecc-events-list').hide();
                     
        }
    }
    $('.tecc-autostart-future select').on('change', TECConfutureAutoStartChange );    
    TECConfutureAutoStartChange();

});

})( jQuery );