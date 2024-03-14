jQuery(document).ready(function ($) {
   
  
    if($('#titlewrap input').val() == ''){
        
        // $('#taxonomy-status input[value="2"]').prop("checked", true);   


        // // // make status open by default        
        // // $( "#taxonomy-status li label").each(function( index ) {
            
        // //     var statusName = $(this).text().trim();

        // //     console.log(statusName);

        // //     $(this).find('input').prop("checked", false);   

        // //     // var value = $(this).val();

        // //     // console.log(value);

        // //     // if(value == 2){
        // //     //     $(this).prop("checked", true);   
        // //     // }
            
        // //     if(statusName == 'Open'){ 
        // //         console.log($(this).find('input').val());     
        // //         $(this).find('input').prop("checked", true);   
        // //     }

        // // });
        
        
        //set board if theres one board
        if($("#taxonomy-boards li").length == 1){
            $(this).find('input').prop("checked", true);       
        }
  
        
    }

});