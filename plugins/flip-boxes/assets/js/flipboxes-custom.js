jQuery(document).ready(function($){
    $('.cfb-flip').on('touchstart', function(){
        $(this).flip('toggle'); 
    }); 
    
    $('.cfb_wrapper').each(function(){
        var flipboxID = $(this).data('flipboxid');
        var cfb_flip = $(this).find('.cfb-flip');
        var effect = cfb_flip.data('effect');

        var cfb_event = (cfb_flip.hasClass('hover')) ? 'hover' : 'click';
 
        cfb_flip.flip({
            axis: effect,
            trigger: cfb_event,
            front: '.flipbox-front-layout',
            back: '.flipbox-back-layout',
            autoSize: false
        });
        
        $('.cfb-data a').on('touchstart', function(e){
            e.stopPropagation();
        });

        $(this).imagesLoaded(function() {
            var maxDataHeight = 0;
            $('#'+flipboxID+' '+'.cfb-flip[data-height="equal"]'+' '+'.cfb-data').each(function(){
                maxDataHeight = Math.max(maxDataHeight, $(this).outerHeight());
            });
            $('#'+flipboxID+' '+'.cfb-flip[data-height="equal"]'+' '+'.cfb-data').outerHeight(maxDataHeight);
        });
    });
    
    $('.cfb_wrapper').imagesLoaded(function() {
        $('.cfb-box-wrapper').each(function(){
            var $this = $(this);
            var frontHeight = $this.find('.flipbox-front-layout').outerHeight();
            var backHeight = $this.find('.flipbox-back-layout').outerHeight();
            var maxHeight = Math.max(frontHeight, backHeight);
            $this.find('.cfb-data').outerHeight(maxHeight); 
        });
    });
});