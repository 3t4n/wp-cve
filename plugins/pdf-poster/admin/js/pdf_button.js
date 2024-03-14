jQuery(function($) {
    $(document).ready(function(){
            $('#insert-pdf').click(open_media_window);
        });


function open_media_window() {
    if (this.window === undefined) {
        this.window = wp.media({
                title: 'Choose PDF File',
                library: {type: 'application/pdf'},
                multiple: false,
                button: {text: 'Embed This File'}
            });

        var self = this; // Needed to retrieve our variable in the anonymous function below
        this.window.on('select', function() {
                var first = self.window.state().get('selection').first().toJSON();
                wp.media.editor.insert('[pdf_embed url="' + first.url + '"]');
            });
    }

    this.window.open();
    return false;
}	
	
	
});