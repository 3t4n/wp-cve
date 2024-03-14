/**
 * Focus video admin
 *
 * Copyright 2013, Greg Priday
 * Released under GPL 3.0 - see http://www.gnu.org/licenses/gpl-3.0.html
 */

jQuery(function($){
    $('.focus-video-table' ).each(function(i, el){
        var $$ = $(el);
        $$.find('.field' ).hide();

        $$.find('.focus-video-type-select' ).change(function(){
            $$.find('.field' ).hide();
            $$.find('.field-' + $(this ).val() ).show();
        } ).change();
        
        $$.find('.focus-add-video' ).click(function(event){
            var $b = $(this);
            event.preventDefault();
            
            var frame = $b.data('frame');
            if(! frame){
                frame = wp.media({
                    title: $b.data('choose'),
                    // Tell the modal to show only images.
                    library: {
                        type: 'video'
                    },
                    button: {
                        text: $b.data('update'),
                        close: false
                    }
                });
                
                frame.on('select', function(){
                    // Grab the selected attachment.
                    var attachment = frame.state().get('selection').first().attributes;
                    var $f = $$.find(' .field-' + $b.data('video-type') + '-self');
                    
                    $f.find('strong' ).html(attachment.title);
                    $f.find('.field-video-self' ).val(attachment.id);
                    frame.close();
                });
            }
            
            frame.open();
            return false;
        });
        
        $$.find('.focus-remove-video' ).click(function(event){
            event.preventDefault();
            $$.find('.video-name' ).html('');
            $$.find('.field-video-self' ).val('');
            
            return false;
        })
    });
});