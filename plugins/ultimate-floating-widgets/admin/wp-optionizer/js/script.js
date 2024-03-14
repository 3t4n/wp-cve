(function($){
$(document).ready(function(){
    
    var init = function(){
        
        $('.wp-optzr-range').each(function(){
            var $range = $(this).find('input[type=range]');
            var unit = $range.attr('data-unit');
            var val = $range.val();
            
            if( unit === undefined )
                unit = '';
            
            $(this).attr( 'data-val', val + ' ' + unit );
        });
        
    }
    
    $('.wp-optzr-range input[type=range]').on('change input', function(e){
        var unit = $(this).attr('data-unit');
        var val = $(this).val();
        var $parent = $(this).parent();
        
        if( unit === undefined )
            unit = '';
        
        $parent.attr( 'data-val', val + ' ' + unit );
    });
    
    init();
    
});
})(jQuery);