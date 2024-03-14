jQuery(function($){
    $('input[type=submit]').addClass('button-primary');
    $('input[type=submit].button-primary').click(function(e){
        parent.eval('update_buckets()');
        e.stopPropagation(); // prevents click from firing twice
    });
});