
 /*
  *  author : hasan movahed 
  *  website link : www.wallfa.com  
  *
  */

jQuery(document).ready(function($){
   
    function checkTitleAjax(title, id,post_type) {
        var data = {
            action: 'title_checks',
            post_title: title,
            post_type: post_type,
            post_id: id
        };
        $.post(ajaxurl, data, function(response) {
            $('#message').remove();
            $('#poststuff').prepend('<div id=\"message\" class=\"updated below-h2 fade \"><p>'+response+'</p></div>');
        }); 
    };
    $('#title').change(function() {
        var title = $('#title').val();
        var id = $('#post_ID').val();
        var post_type = $('#post_type').val();
        checkTitleAjax(title, id,post_type);
    });

});
